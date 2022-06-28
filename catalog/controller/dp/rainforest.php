<?

class ControllerDPRainForest extends Controller {	
	private $maxSteps = 10;
	private $categoriesData = null;

	private $iterations = null;
	private $current_iteration = null;
	private $current_category = null;

	private $existentAsins = [];
	private $rainforestAmazon = null;

	public function __construct($registry){
		ini_set('memory_limit', '2G');

		parent::__construct($registry);

		if (php_sapi_name() != 'cli'){
			die();
		}

		if (!$this->config->get('config_rainforest_enable_api')){
			die('RNF API NOT ENABLED');
		}

		if (!$this->config->get('config_rainforest_category_model')){
			die('RNF Category API Workmode not set');
		}

		$this->fullFillExistentAsins();

	}

	private function fullFillExistentAsins(){
		$query = $this->db->ncquery("SELECT DISTINCT asin FROM product");

		foreach ($query->rows as $row){
			$this->existentAsins[] = $row['asin'];
		}
	}

	private function addExistentAsin($asin){
		$this->existentAsins[] = $asin;
	}

	private function asinExists($asin){
		return in_array($asin, $this->existentAsins);
	}

	private function recursiveTree($category_id, $type){
		$childCategories = $this->rainforestAmazon->categoryParser->setType($type)->getCategoryChildren($category_id);

		if ($childCategories) {
			foreach ($childCategories as $childCategory){

				echoLine('[ControllerDPRainForest] Категория ' . $childCategory['path']);

				$this->rainforestAmazon->categoryParser->setType($type)->createCategory($childCategory);										

				if ($childCategory['has_children']){
					$this->recursiveTree($childCategory['id'], $type);
				}
			}
		}
	}
		
	public function addcategoriescron(){
		$type = $this->config->get('config_rainforest_category_model');
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon'); 
		
		if ($type == 'bestsellers') {
			if (!empty($this->config->get('config_rainforest_root_categories'))){
				//Если задана корневая категория, то создаем ее, это работает только блять с бестселлерами
				$this->rainforestAmazon->categoryParser->setType($type)->createTopCategoryFromSettings(prepareEOLArray($this->config->get('config_rainforest_root_categories')));
			}
		}
		

		//Если тип у нас стандартный - то мы создадим корневые категории автоматически.
		//В случае с бестселлерами это не работает почему-то
		if ($type == 'standard'){
			$topCategories = $this->rainforestAmazon->categoryParser->setType($type)->getTopCategories();

			foreach ($topCategories['categories'] as $topCategory){
				$this->rainforestAmazon->categoryParser->setType($type)->createCategory($topCategory);				
			}
		}
		

		unset($topCategory);
		foreach ($this->rainforestAmazon->categoryParser->setType($type)->getTopCategoriesFromDataBase() as $topCategory){
			$this->recursiveTree($topCategory['category_id'], $type);
		}

		$this->rainforestAmazon->categoryParser->setType($type)->updateFinalCategories();

		if ($this->config->get('config_rainforest_enable_auto_tree')){
			$this->rainforestAmazon->categoryParser->setType($type)->rebuildAmazonTreeToStoreTree();
			$this->rainforestAmazon->categoryParser->setType($type)->model_catalog_category->repairCategories();			
		}
	}

	public function editfullproductscron(){

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
		$timer = new FPCTimer();

		$products = $this->rainforestAmazon->productsRetriever->getProducts();		

		echoLine('[EditFullProducts] Всего товаров ' . count($products));

		$total = count($products);
		$iterations = ceil($total/\hobotix\RainforestAmazon::productRequestLimits);

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			echoLine('[EditFullProducts] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . (\hobotix\RainforestAmazon::productRequestLimits * ($i-1)) . ' по ' . \hobotix\RainforestAmazon::productRequestLimits * $i);

			$slice = array_slice($products, \hobotix\RainforestAmazon::productRequestLimits * ($i-1), \hobotix\RainforestAmazon::productRequestLimits);

			$results = $this->rainforestAmazon->simpleProductParser->getProductByASINS($slice);

			foreach ($results as $product_id => $result){
				$this->rainforestAmazon->infoUpdater->updateProductAmazonLastSearch($product_id);

				if ($result){
					echoLine('[EditFullProducts] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);				

					$this->rainforestAmazon->productsRetriever->editFullProduct($product_id, $result);

				} else {

					echoLine('[EditFullProducts] Товар ' . $product_id . ', не найден, ASIN ' . $result['asin']);
					$this->rainforestAmazon->infoUpdater->updateASINInDatabase(['product_id' => $product_id, 'asin' => '']);

				}

			}

			echoLine('[EditFullProducts] Времени на итерацию: ' . $timer->getTime() . ' сек.');
			unset($timer);
		}
	}

	public function updateimagesfromamazon(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithNoImages();

		foreach ($products as $product_id => $amazon_product_image){
			$this->rainforestAmazon->productsRetriever->model_product_edit->editProductFields($product_id, [['name' => 'image', 'type' => 'varchar', 'value' => $this->rainforestAmazon->productsRetriever->getImage($amazon_product_image)]]);
		}
	}

	public function updatenametranslations(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->rainforestAmazon->productsRetriever->yandexTranslator->setDebug(true);
		$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithNoTranslation();


		foreach ($products as $product){
			foreach ($this->registry->get('languages') as $language_code => $language) {
				$source_name = atrim($product['source_name']);

				if ($product['language_id'] == $language['language_id'] && $this->config->get('config_rainforest_enable_language_' . $language_code)){	

					echoLine('[updatenametranslations] Товар: ' . $product['product_id']);

					$name = $this->rainforestAmazon->productsRetriever->yandexTranslator->translate($source_name, $this->config->get('config_rainforest_source_language'), $language_code, true);

					$translated = false;
					if ($name && $name != atrim($source_name)){
						$translated = true;
					}

					$product_name_data[$language['language_id']] = [
						'name' 			=> $name,
						'translated' 	=> $translated
					];


					$this->rainforestAmazon->productsRetriever->model_product_edit->editProductNames($product['product_id'], $product_name_data);
				}
			}

			
		}
	}

	public function parseCategoryPage($category_id, $rfCategory){
		$categoryResultIndex = \hobotix\RainforestAmazon::categoryModeResultIndexes[$this->config->get('config_rainforest_category_model')];

		$continue = false;
		if (!empty($rfCategory[$categoryResultIndex]) && count($rfCategory[$categoryResultIndex])){
			$continue = true;				

			$i = 1;
			$total = count($rfCategory[$categoryResultIndex]);
			foreach ($rfCategory[$categoryResultIndex] as $rfSimpleProduct){
			//	echoLine('[parseCategoryPage] Товар ' . $rfSimpleProduct['asin'] . ': ' . $rfSimpleProduct['title']);
				$counters = ($this->current_iteration . '/' . $this->iterations . ' : ');
				$counters .= ($this->current_category . '/' . \hobotix\RainforestAmazon::categoryRequestLimits . ' : ');
				$counters .= ($i . '/' . $total);

				if (!$this->asinExists($rfSimpleProduct['asin'])){					
					echoLine('[parseCategoryPage] Товар ' . $rfSimpleProduct['asin'] . ' не найден, ' . $counters);						

					$this->rainforestAmazon->productsRetriever->addSimpleProductWithOnlyAsin(
						[
							'asin' 					=> $rfSimpleProduct['asin'], 
							'category_id' 			=> $category_id, 
							'name' 					=> $rfSimpleProduct['title'], 
							'amazon_product_link' 	=> $rfSimpleProduct['link'],
							'amazon_product_image'  => $rfSimpleProduct['image'], 
							'image' 				=> $this->rainforestAmazon->productsRetriever->getImage($rfSimpleProduct['image']), 
							'added_from_amazon' 	=> 1
						]
					);

					$this->addExistentAsin($rfSimpleProduct['asin']);

				} else {
					echoLine('[parseCategoryPage] Товар ' . $rfSimpleProduct['asin'] . ' найден ' . $counters);						

					//Логика работы с найденными товарами - только в случае стандартной модели, если первый товар найден - то останавливаем работу
					if ($this->config->get('config_rainforest_category_model') == 'standard' && $this->categoriesData[$category_id]['amazon_fulfilled']){
						echoLine('[parseCategoryPage] Товар ' . $rfSimpleProduct['asin'] . ' найден, останавливаем парсинг категории');
						return false;						
						break;
					}
				}

				$i++;
			}
		}

		return $continue;
	}

	public function addnewproductscron2(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
		$timer = new FPCTimer();

		$this->categoriesData = $this->rainforestAmazon->categoryRetriever->getCategories();

		foreach ($this->categoriesData as $category_id => $category){
			$this->categoriesData[$category_id]['page'] = 1;
		}

		$total = count($this->categoriesData);		
		$this->iterations = $iterations = ceil($total/\hobotix\RainforestAmazon::categoryRequestLimits);
		echoLine('[AddNewProducts2] Всего ' . $total . ' категорий!');		

		$otherPageRequests = [];		
		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			$this->current_iteration = $i;
			echoLine('[AddNewProducts2] Шаг 1 Итерация ' . $i . ' из ' . $iterations . ', категории с ' . (\hobotix\RainforestAmazon::categoryRequestLimits * ($i-1)) . ' по ' . \hobotix\RainforestAmazon::categoryRequestLimits * $i);
			
			$slice = array_slice($this->categoriesData, \hobotix\RainforestAmazon::categoryRequestLimits * ($i-1), \hobotix\RainforestAmazon::categoryRequestLimits);
			$rfCategoryJSONS = $this->rainforestAmazon->categoryRetriever->getCategoriesFromAmazonAsync($slice);

			$continue = [];
			
			$this->current_category = 0;
			foreach ($rfCategoryJSONS as $category_id => $rfCategoryJSON){	
				$this->current_category++;		
				if ($this->parseCategoryPage($category_id, $rfCategoryJSON)){

					$this->rainforestAmazon->categoryRetriever->setJsonResult($rfCategoryJSON);

					if (!$this->rainforestAmazon->categoryRetriever->getNextPage()){
						echoLine('[AddNewProducts2] Категория ' . $this->categoriesData[$category_id]['name'] . ' всё, ставим маркер завершения');
						$this->rainforestAmazon->categoryRetriever->setLastCategoryUpdateDate($category_id);
					} 

					for ($z = 2; $z <= $this->rainforestAmazon->categoryRetriever->getTotalPages(); $z++){
						$this->categoriesData[$category_id]['page'] = $z;
						$this->categoriesData[$category_id]['total'] = $this->rainforestAmazon->categoryRetriever->getTotalPages();

						$otherPageRequests[] = $this->categoriesData[$category_id];
					}
				}
			}
		}

		$total = count($otherPageRequests);
		$this->iterations = $iterations = ceil($total/\hobotix\RainforestAmazon::categoryRequestLimits);
		echoLine('[AddNewProducts2] Всего eще ' . $total . ' запросов!');
		for ($i = 1; $i <= $iterations; $i++){
			$this->current_iteration = $i;
			echoLine('[AddNewProducts2] Шаг 2 Итерация ' . $i . ' из ' . $iterations . ', категории с ' . (\hobotix\RainforestAmazon::categoryRequestLimits * ($i-1)) . ' по ' . \hobotix\RainforestAmazon::categoryRequestLimits * $i);
			$slice = array_slice($otherPageRequests, \hobotix\RainforestAmazon::categoryRequestLimits * ($i-1), \hobotix\RainforestAmazon::categoryRequestLimits);

			$rfCategoryJSONS = $this->rainforestAmazon->categoryRetriever->getCategoriesFromAmazonAsync($slice);

			$this->current_category = 0;
			foreach ($rfCategoryJSONS as $category_id => $rfCategoryJSON){
				$this->current_category++;

				$this->parseCategoryPage($category_id, $rfCategoryJSON);
				$this->rainforestAmazon->categoryRetriever->setJsonResult($rfCategoryJSON);

				if (!$this->rainforestAmazon->categoryRetriever->getNextPage()){
					echoLine('[AddNewProducts2] Категория ' . $this->categoriesData[$category_id]['name'] . ' всё, ставим маркер завершения');
					$this->rainforestAmazon->categoryRetriever->setLastCategoryUpdateDate($category_id);
				}
			}
		}

		$this->updateimagesfromamazon();
	//	$this->updatenametranslations();
	}

	//OLD SLOW DEPRECATED FUNCTION
	public function addnewproductscron(){		

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
		$timer = new FPCTimer();

		$categories = $this->rainforestAmazon->categoryRetriever->getCategories();

		echoLine('[AddNewProducts] Всего категорий ' . count($categories));

		foreach ($categories as $category){
			echoLine('[AddNewProducts] Категория ' . $category['name'] . ': ' . html_entity_decode($category['amazon_category_name']));

			$params = [
				'amazon_category_id' => $category['amazon_category_id'],
				'page'				 => 1
			];

			$rfCategoryObject = $this->rainforestAmazon->categoryRetriever->getCategoryFromAmazon($params);
			echoLine('[AddNewProducts] Страница 1' . ', время ' . $timer->getTime());
			
			$rfCategory = $rfCategoryObject->getJsonResult();

			$categoryResultIndex = \hobotix\RainforestAmazon::categoryModeResultIndexes[$this->config->get('config_rainforest_category_model')];

			if (!empty($rfCategory[$categoryResultIndex]) && count($rfCategory[$categoryResultIndex])){
					$continue = true;				
					echoLine('[AddNewProducts] Товаров ' . count($rfCategory[$categoryResultIndex]));

					foreach ($rfCategory[$categoryResultIndex] as $rfSimpleProduct){
						echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ': ' . $rfSimpleProduct['title']);

					if (!$this->rainforestAmazon->productsRetriever->getProductsByAsin($rfSimpleProduct['asin'])){
						echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ' не найден, добавляем, продолжаем парсинг категории');						
						
						$this->rainforestAmazon->productsRetriever->addSimpleProductWithOnlyAsin(
							[
								'asin' 				=> $rfSimpleProduct['asin'], 
								'category_id' 		=> $category['category_id'], 
								'name' 				=> $rfSimpleProduct['title'], 
								'image' 			=> $this->rainforestAmazon->productsRetriever->getImage($rfSimpleProduct['image']), 
								'added_from_amazon' => 1
							]
						);

					} else {
						echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ' найден, продолжаем');						

						//Логика работы с найденными товарами - только в случае стандартной модели
						if ($this->config->get('config_rainforest_category_model') == 'standard' && $category['amazon_fulfilled']){
							echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ' найден, останавливаем парсинг категории');
							$continue = false;						
							break;
						}
					}
				}		
			} else {

				echoLine('[AddNewProducts] EMPTY ' . $categoryResultIndex);

			}					

			if (isset($continue) && !$continue){
				continue;
			}

			while(!empty($rfCategoryObject) && $rfCategoryObject->getJsonResult() && $rfCategoryObject->getNextPage()){
				echoLine('[AddNewProducts] Страница ' . $rfCategoryObject->getNextPage() . ', время ' . $timer->getTime());

				$params = [
					'amazon_category_id' => $category['amazon_category_id'],
					'page'				 => $rfCategoryObject->getNextPage()
				];

				$rfCategoryObject = $this->rainforestAmazon->categoryRetriever->getCategoryFromAmazon($params);
				$rfCategory = $rfCategoryObject->getJsonResult();

				if (!empty($rfCategory[$categoryResultIndex]) && count($rfCategory[$categoryResultIndex])){
					$continue = true;
					echoLine('[AddNewProducts] Товаров ' . count($rfCategory[$categoryResultIndex]));

					foreach ($rfCategory[$categoryResultIndex] as $rfSimpleProduct){
						echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ': ' . $rfSimpleProduct['title']);

						if (!$this->rainforestAmazon->productsRetriever->getProductsByAsin($rfSimpleProduct['asin'])){
							echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ' не найден, добавляем, продолжаем парсинг категории');
							
							$this->rainforestAmazon->productsRetriever->addSimpleProductWithOnlyAsin(
							[
								'asin' 				=> $rfSimpleProduct['asin'], 
								'category_id' 		=> $category['category_id'], 
								'name' 				=> $rfSimpleProduct['title'], 
								'image' 			=> $this->rainforestAmazon->productsRetriever->getImage($rfSimpleProduct['image']), 
								'added_from_amazon' => 1
							]);

						} else {

							echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ' найден, продолжаем');							

							//Логика работы с найденными товарами - только в случае стандартной модели
							if ($this->config->get('config_rainforest_category_model') == 'standard' && $category['amazon_fulfilled']){
								echoLine('[AddNewProducts] Товар ' . $rfSimpleProduct['asin'] . ' найден, останавливаем парсинг категории');
								$continue = false;
								break;
							}
						}
					}

					if (isset($continue) && !$continue){
						break;
					}

				} else {
					
					echoLine('[AddNewProducts] EMPTY ' . $categoryResultIndex);

				}
			}

			$this->rainforestAmazon->categoryRetriever->setLastCategoryUpdateDate($category['category_id']);
		}
	}

}