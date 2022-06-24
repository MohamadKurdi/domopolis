<?

class ControllerDPRainForest extends Controller {	
	private $maxSteps = 10;

	private $productRequestLimits = 30;
	private $offerRequestLimits = 30;
	private $rainforestAmazon;


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
		$iterations = ceil($total/$this->productRequestLimits);

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			echoLine('[EditFullProducts] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ($this->productRequestLimits * ($i-1)) . ' по ' . $this->productRequestLimits * $i);

			$slice = array_slice($products, $this->productRequestLimits * ($i-1), $this->productRequestLimits);

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

	public function addnewproductscron2(){


		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
		$timer = new FPCTimer();

		$categories = $this->rainforestAmazon->categoryRetriever->getCategories();

		$total = count($categories);
		$iterations = ceil($total/$this->productRequestLimits);
		echoLine('[OFFERS] Всего ' . $total . ' категорий!');		

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			echoLine('[AddNewProducts] Итерация ' . $i . ' из ' . $iterations . ', категории с ' . ($this->productRequestLimits * ($i-1)) . ' по ' . $this->productRequestLimits * $i);
			

			






		}



	}


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