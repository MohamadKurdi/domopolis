<?

class ControllerDPService extends Controller {	

	public function __construct($registry){
		ini_set('memory_limit', '4G');

		parent::__construct($registry);

		if (php_sapi_name() != 'cli'){
			die();
		}
	}

	public function index(){
		$this->fixAmazonModes();
		$this->countProducts();
	}

	public function fixAmazonModes(){
		$this->db->query("UPDATE product SET fill_from_amazon = 1 WHERE filled_from_amazon = 1 AND added_from_amazon = 1");
	}


	public function countProducts(){

		if (!$this->config->get('config_product_count')){
			echoLine('[ControllerDPService::countProducts] PRODUCT COUNT IS DISABLED IN ADMIN');
			return;
		}

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		$categories = $this->model_catalog_category->getAllCategories();

		$filter_data = array(
			'filter_sub_category' 			=> true,				
			'no_child'      				=> true 
		);

		foreach ($categories as $category_info){

			$filter_data['filter_category_id'] 		= $category_info['category_id'];

			if ($category_info['category_id'] == GENERAL_MARKDOWN_CATEGORY) {
				$filter_data['filter_enable_markdown'] = true;
			}

			if (!empty($category_info['deletenotinstock'])) {
				$filter_data['filter_current_in_stock'] = true;
			}			

			$product_count = $this->model_catalog_product->getTotalProducts($filter_data);

			echoLine('[countProducts] Категория ' . $category_info['name'] . ', товаров: ' . $product_count);
			$this->db->query("UPDATE category SET product_count = '" . (int)$product_count . "' WHERE category_id = '" . (int)$category_info['category_id'] . "'");
		}
	}

}