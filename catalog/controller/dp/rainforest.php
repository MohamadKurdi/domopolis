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

	/*
	Second workers (tried, but unneded, because of db slow latency)
	*/
	public function parsetechcategory_fork(){
		$this->parsetechcategory();
	}

	public function editfullproductscron_fork(){
		$this->editfullproductscron();
	}

	public function editfullproductscronl2_fork(){
		$this->editfullproductscronl2();
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

				if (!$this->rainforestAmazon->productsRetriever->getProductsByAsin($rfSimpleProduct['asin'])){					
					echoLine('[parseCategoryPage] Товар ' . $rfSimpleProduct['asin'] . ' не найден, ' . $counters);		
				
					$this->rainforestAmazon->productsRetriever->addSimpleProductWithOnlyAsin(
						[
							'asin' 					=> $rfSimpleProduct['asin'], 
							'amazon_best_price' 	=> (!empty($rfSimpleProduct['price']))?$rfSimpleProduct['price']['value']:'0',
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

	public function addnewproductscron(){
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
	}

	public function editfullproductscron($parsetechcategory = false){

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
		$timer = new FPCTimer();

		if ($parsetechcategory){
			$products = $this->rainforestAmazon->productsRetriever->getProductsFromTechCategory();
		} else {
			$products = $this->rainforestAmazon->productsRetriever->getProducts();		
		}

		echoLine('[editfullproductscron] Всего товаров ' . count($products));

		$total = count($products);
		$iterations = ceil($total/\hobotix\RainforestAmazon::productRequestLimits);

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			echoLine('[editfullproductscron] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . (\hobotix\RainforestAmazon::productRequestLimits * ($i-1)) . ' по ' . \hobotix\RainforestAmazon::productRequestLimits * $i);

			$slice = array_slice($products, \hobotix\RainforestAmazon::productRequestLimits * ($i-1), \hobotix\RainforestAmazon::productRequestLimits);

			$results = $this->rainforestAmazon->simpleProductParser->getProductByASINS($slice);

			foreach ($results as $product_id => $result){				
				$this->rainforestAmazon->infoUpdater->updateProductAmazonLastSearch($product_id);

				if ($result){
					echoLine('[editfullproductscron] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);				

					if ($parsetechcategory){
						$this->rainforestAmazon->productsRetriever->editJustProductCategory($product_id, $result);					
					} else {
						$this->rainforestAmazon->productsRetriever->editFullProduct($product_id, $result);
					}

				} else {

					echoLine('[EditFullProducts] Товар ' . $product_id . ', не найден, ASIN ');
					$this->rainforestAmazon->infoUpdater->updateASINInDatabase(['product_id' => $product_id, 'asin' => 'INVALID']);

				}

			}

			echoLine('[EditFullProducts] Времени на итерацию: ' . $timer->getTime() . ' сек.');
			unset($timer);
		}

		$this->rainforestAmazon->productsRetriever->model_product_edit->resetUnexsistentVariants();
	}

	public function parsetechcategory(){
		if ($this->config->get('config_rainforest_default_technical_category_id') && $this->config->get('config_rainforest_default_unknown_category_id')){
			$this->editfullproductscron(true);
		}
	}

	public function editfullproductscronl2(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
		$timer = new FPCTimer();

		$products = $this->rainforestAmazon->productsRetriever->getProductsWithFullDataButNotFullfilled();

		if ($products){
			$i = 1;
			$total = count($products);
			echoLine('[editfullproductscronl2] Всего товаров: ' . $total);

			foreach ($products as $product){
				echoLine('[editfullproductscronl2] Товар ' . $product['asin'] . ' ' . $i . '/' . $total);

				$this->rainforestAmazon->productsRetriever->editFullProduct($product['product_id'], json_decode($product['json'], true));
				$i++;
			}
		}

		$this->rainforestAmazon->productsRetriever->model_product_edit->resetUnexsistentVariants();
	}

	public function updateimagesfromamazon(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithNoImages();

		foreach ($products as $product_id => $amazon_product_image){
			$this->rainforestAmazon->productsRetriever->model_product_edit->editProductFields($product_id, [['name' => 'image', 'type' => 'varchar', 'value' => $this->rainforestAmazon->productsRetriever->getImage($amazon_product_image)]]);
		}
	}

	public function setpricesfast(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$total = $this->rainforestAmazon->productsRetriever->model_product_get->getTotalProductsWithFastPrice();		

		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::generalDBQueryLimit);
		echoLine('[setpricesfast] Всего товаров: ' . $total);
		$k = 1;		

		for ($i = 1; $i <= $iterations; $i++){
			$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithFastPrice(($i-1) * (int)\hobotix\RainforestAmazon::generalDBQueryLimit);
			if ($products){		
				foreach ($products as $product){
					echoLine('[setpricesfast] Товар ' . $product['product_id'] . ' / ' . $product['asin'] . ' ' . $i . '/' . $k . '/' . $total);
						
					$this->rainforestAmazon->offersParser->PriceLogic->updateProductPrices($product['asin'], $product['amazon_best_price'], true);
					$k++;	
				}
			}	
		}		
	}

	public function puttofilecache(){

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$total = $this->rainforestAmazon->productsRetriever->model_product_get->getTotalProductsWithFullDataInDB();		

		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::generalDBQueryLimit);
		echoLine('[puttofilecache] Всего товаров: ' . $total);
		$k = 1;		

		for ($i = 1; $i <= $iterations; $i++){
			$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithFullDataInDB(($i-1) * (int)\hobotix\RainforestAmazon::generalDBQueryLimit);
			if ($products){		
				foreach ($products as $product){

					if ($product['json']){
						echoLine('[puttofilecache] Товар ' . $product['product_id'] . ' / ' . $product['asin'] . ' ' . $i . '/' . $k . '/' . $total);
						
						$this->rainforestAmazon->infoUpdater->updateProductAmznData($product, false);
						$k++;	
					}
				}
			}	
		}
	}	

	/*
	Фиксит переводы строк
	*/
	public function fixtranslations(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->rainforestAmazon->productsRetriever->yandexTranslator->setDebug(true);
		$this->rainforestAmazon->productsRetriever->model_product_edit->cleanFailedTranslations();


		//1. product_description
		foreach (hobotix\Amazon\productModelEdit::descriptionFields as $field) {
			echoLine('[fixtranslations] Исправление перевода: ' . $field);
			$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithNoFieldTranslation($field);

			$i = 1;
			$total = count($products);
			foreach ($products as $product){
				$product_translate_data = [];
				foreach ($this->registry->get('languages') as $language_code => $language) {
					$source = atrim($product['source_' . $field]);

					if ($product['language_id'] == $language['language_id'] && $this->config->get('config_rainforest_enable_language_' . $language_code)){	
						echoLine('[fixtranslations] Товар: ' . $product['product_id'] . ', ' . $i . '/' . $total);

						$translated = $this->rainforestAmazon->productsRetriever->yandexTranslator->translate($source, $this->config->get('config_rainforest_source_language'), $language_code, true);

						$product_translate_data[$language['language_id']] = [
							$field 			=> $translated						
						];
					}
				}

				if ($product_translate_data){
					$this->rainforestAmazon->productsRetriever->model_product_edit->editProductDescriptionField($product['product_id'], $field, $product_translate_data);		
				}	

				$i++;			
			}
		}

		//2. product_attribute
		$attributes = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithNoAttributeTranslation();

		$i = 1;
		$total = count($attributes);
		foreach ($attributes as $attribute){
			$attribute_translate_data = [];
			foreach ($this->registry->get('languages') as $language_code => $language) {
				$source = atrim($attribute['source_text']);

				if ($attribute['language_id'] == $language['language_id'] && $this->config->get('config_rainforest_enable_language_' . $language_code)){
					echoLine('[fixtranslations] Атрибут: ' . $attribute['product_id'] . ':' . $attribute['attribute_id'] . ', ' . $i . '/' . $total);

					$translated = $this->rainforestAmazon->productsRetriever->yandexTranslator->translate($source, $this->config->get('config_rainforest_source_language'), $language_code, true);

					$attribute_translate_data[$language['language_id']] = [
						'text' 			=> $translated						
					];
				}
			}

			if ($attribute_translate_data){
				$this->rainforestAmazon->productsRetriever->model_product_edit->editProductAttributeText($attribute['product_id'], $attribute['attribute_id'], $attribute_translate_data);		
			}
			
			$i++;
		}

		//3. product_video_titles
		$videos = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithNoVideoTitleTranslation();

		$i = 1;
		$total = count($videos);
		foreach ($videos as $video){
			$video_translate_data = [];
			foreach ($this->registry->get('languages') as $language_code => $language) {
				$source = atrim($video['source_title']);

				if ($video['language_id'] == $language['language_id'] && $this->config->get('config_rainforest_enable_language_' . $language_code)){
					echoLine('[fixtranslations] Видео: ' . $video['product_id'] . ':' . $video['product_video_id'] . ', ' . $i . '/' . $total);

					$translated = $this->rainforestAmazon->productsRetriever->yandexTranslator->translate($source, $this->config->get('config_rainforest_source_language'), $language_code, true);

					$video_translate_data[$language['language_id']] = [
						'title' 			=> $translated						
					];
				}
			}			

			if ($video_translate_data){
				$this->rainforestAmazon->productsRetriever->model_product_edit->editProductVideoTitle($video['product_video_id'], $video_translate_data);		
			}
			
			$i++;
		}	
	}

	/*
	Фиксит привязки вариантов методом записи в табличку с айдишками
	*/
	public function fixvariantsbyids(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$total = $this->rainforestAmazon->productsRetriever->model_product_get->getTotalProductsWithFullData();

		$this->rainforestAmazon->productsRetriever->model_product_edit->clearIdsVariantsTable();

		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::generalDBQueryLimit);
		echoLine('[fixvariants] Всего товаров: ' . $total);
		$k = 1;	

		for ($i = 1; $i <= $iterations; $i++){
			$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithFullData(($i-1) * (int)\hobotix\RainforestAmazon::generalDBQueryLimit);
			if ($products){		
				foreach ($products as $product){
					echoLine('[fixvariants] Товар ' . $product['product_id'] . '/' . $product['asin'] . ' ' . $i . '/' . $k . '/' . $total);

					$this->rainforestAmazon->productsRetriever->fixProductVariants($product['product_id'], json_decode($product['json'], true), false);
					$k++;			
				}
			}	
		}

		$this->rainforestAmazon->productsRetriever->model_product_edit->deNormalizeVariantsTable();
		$this->rainforestAmazon->productsRetriever->model_product_edit->resetUnexsistentVariants();						
	}

	/*
	Начальное заполнение таблички вариантов, v2 логика вариантов на асинах
	*/
	public function setvariants(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$total = $this->rainforestAmazon->productsRetriever->model_product_get->getTotalProductsWithFullData();		

		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::generalDBQueryLimit);
		echoLine('[fixvariants] Всего товаров: ' . $total);
		$k = 1;	

		for ($i = 1; $i <= $iterations; $i++){
			$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithFullData(($i-1) * (int)\hobotix\RainforestAmazon::generalDBQueryLimit);
			if ($products){		
				foreach ($products as $product){
					echoLine('[fixvariants] Товар ' . $product['product_id'] . '/' . $product['asin'] . ' ' . $i . '/' . $k . '/' . $total);

					$this->rainforestAmazon->productsRetriever->model_product_edit->setProductVariants(json_decode($product['json'], true));
					$k++;			
				}
			}	
		}		
		$this->rainforestAmazon->productsRetriever->model_product_edit->resetUnexsistentVariants();						
	}

	/*
	Фиксит привязки вариантов методом прохода таблички c asin
	*/
	public function fixvariants(){


		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$total = $this->rainforestAmazon->productsRetriever->model_product_get->getTotalProductsWithVariantsSet();		

		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::generalDBQueryLimit);
		echoLine('[fixvariants] Всего товаров: ' . $total);
		$k = 1;	

		for ($i = 1; $i <= $iterations; $i++){
			$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithVariantsSet(($i-1) * (int)\hobotix\RainforestAmazon::generalDBQueryLimit);
			if ($products){		
				foreach ($products as $product){
					echoLine('[fixvariants] Товар ' . $product['main_asin'] . ' -> ' . $product['variant_asin'] . ' ' . $i . '/' . $k . '/' . $total);

					if ($product['main_asin'] == $product['variant_asin']){
						$this->rainforestAmazon->productsRetriever->model_product_edit->updateProductMainVariantIdByAsin($product['main_asin'], 0);						
					} else {
						$this->rainforestAmazon->productsRetriever->model_product_edit->updateProductMainVariantIdByParentAsinByAsin($product['variant_asin'], $product['main_asin']);		
					}
					
					$k++;	
				}
			}	
		}		
		$this->rainforestAmazon->productsRetriever->model_product_edit->resetUnexsistentVariants();						
	}


	/*
	Фиксит названия товаров функцией normalizeProductName из InfoUpdater
	*/
	public function fixnames(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');

		$total = $this->rainforestAmazon->infoUpdater->getTotalNames();
		$iterations = ceil($total/(int)\hobotix\Amazon\InfoUpdater::descriptionsQueryLimit);
		echoLine('[fixnames] Всего товаров: ' . $total);
		$k = 1;			

		for ($i = 1; $i <= $iterations; $i++){
			$products = $this->rainforestAmazon->infoUpdater->getNames(($i-1) * (int)\hobotix\Amazon\InfoUpdater::descriptionsQueryLimit);
			if ($products){		
				foreach ($products as $product){
					echoLine('[fixnames] ' . $i . '/' . $iterations);					

					$this->rainforestAmazon->productsRetriever->model_product_edit->updateProductName($product['product_id'], [
						'name' 			=>	$this->rainforestAmazon->infoUpdater->normalizeProductName($product['name']),						
						'language_id'	=>	$product['language_id']
					]);
					
				}
			}	
		}		
	}

	/*
	Добавляет отзывы товаров из уже заполненных и сохраненных данных
	*/
	public function fixreviews(){


		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$total = $this->rainforestAmazon->productsRetriever->model_product_get->getTotalProductsWithFullData(['reviews_parsed' => 0]);		

		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::generalDBQueryLimit);
		echoLine('[fixreviews] Всего товаров: ' . $total);
		$k = 1;	

		for ($i = 1; $i <= $iterations; $i++){
			$products = $this->rainforestAmazon->productsRetriever->model_product_get->getProductsWithFullData(($i-1) * (int)\hobotix\RainforestAmazon::generalDBQueryLimit, ['reviews_parsed' => 0]);
			if ($products){		
				foreach ($products as $product){
					echoLine('[fixreviews] Товар ' . $product['product_id'] . '/' . $product['asin'] . ' ' . $i . '/' . $k . '/' . $total);

					$this->rainforestAmazon->productsRetriever->parseProductTopReviews($product['product_id'], json_decode($product['json'], true));

					$this->rainforestAmazon->productsRetriever->model_product_edit->editProductFields($product['product_id'], [['type' => 'int','name' => 'reviews_parsed', 'value' => 1]]);

					$k++;			
				}
			}	
		}		




	}
}