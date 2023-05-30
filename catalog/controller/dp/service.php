<?

class ControllerDPService extends Controller {	

	public function __construct($registry){
		ini_set('memory_limit', '4G');

		parent::__construct($registry);

		if (php_sapi_name() != 'cli'){
			die();
		}
	}

	public function test(){
		$this->log->debug($this->url->link('product/product', 'product_id=991916'));
		$this->log->debug($this->url->link('common/home'));
	}


	private function do_resize($image, $w, $h){
		$new_image = $this->model_tool_image->resize_avif($image, $w, $h);
        echoLine('[preresize] ' . $image . ' -> ' . $new_image . ' ('. $w . '*' . $h . ')', 's');
	}

	public function preresize(){
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$filter = array(
                'filter_status'                     => true,
                'filter_not_bad'                    => true
            );
		$total = $this->model_catalog_product->getTotalProducts($filter);
        $iterations = ceil($total/$this->config->get('config_google_merchant_feed_limit'));

        echoLine('[preresize] Всего товаров ' . $total);

         for ($i = 1; $i <= ($iterations); $i++) {
                $timer = new FPCTimer();
                $totalGet = ceil($this->config->get('config_google_merchant_feed_limit')/$this->config->get('config_google_merchant_one_iteration_limit'));
                for ($iGet = 1; $iGet <= $totalGet; $iGet++) {

                    $filter = array(
                        'start'                     => ($i-1)*$this->config->get('config_google_merchant_feed_limit') + ($iGet-1)*$this->config->get('config_google_merchant_one_iteration_limit'),
                        'limit'                     => $this->config->get('config_google_merchant_one_iteration_limit'),
                        'filter_status'             => true,
                        'filter_not_bad'            => true,
                        'filter_get_product_mode'   => 'preresize',                    
                        'sort'                      => 'p.product_id',                        
                        'order'                     => 'DESC'
                    );

                    echoLine('[preresize] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ($filter['start']) . ' по ' . ($filter['start'] + $filter['limit']));
                    $products = $this->model_catalog_product->getProducts($filter);
                    $k = 0;

                    foreach ($products as $product) {
                      	//main
                      	$this->do_resize($product['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                      	$this->do_resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                      	$this->do_resize($product['image'], 1000, 1000);

                      	if (!empty($product['images'])){
                      		foreach ($product['images'] as $image){
                      			$this->do_resize($image['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                      			$this->do_resize($image['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                      			$this->do_resize($image['image'], 1000, 1000);
                      		}
                      	}
                    }

                    echoLine('');
                }

                echoLine('');
                echoLine('[preresize] памяти занято ' . convertSize(memory_get_usage(true)));
                echoLine('[preresize] собираем мусор, освобождаем память ' . convertSize(memory_get_usage(true)));
                gc_collect_cycles();

                echoLine('[preresize] Времени на итерацию ' . $timer->getTime() . ' сек.');
                unset($timer);
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

		$categories = $this->model_catalog_category->getAllCategoriesEvenDisabled();

		foreach ($categories as $category_info){

			$filter_data = [
				'filter_sub_category' 			=> true,				
				'no_child'      				=> true 
			];

			$filter_data['filter_category_id'] 		= $category_info['category_id'];

			if ($category_info['category_id'] == GENERAL_MARKDOWN_CATEGORY) {
				$filter_data['filter_enable_markdown'] = true;
			}

			if (!empty($category_info['deletenotinstock'])) {
				$filter_data['filter_current_in_stock'] = true;
			}			

			$product_count = $this->model_catalog_product->getTotalProducts($filter_data);

			if ($product_count == 0){
				echoLine('[ControllerDPService::countProducts] Category ' . $category_info['name'] . ', has products: ' . $product_count, 'w');
			} else {
				echoLine('[ControllerDPService::countProducts] Category ' . $category_info['name'] . ', has products: ' . $product_count, 's');
			}
			
			$this->db->query("UPDATE category SET product_count = '" . (int)$product_count . "' WHERE category_id = '" . (int)$category_info['category_id'] . "'");
		}

		
		if ($this->config->get('config_disable_empty_categories')){
			echoLine('[ControllerDPService::countProducts] DISABLE EMPTY CATEGORIES = YES', 'i');
			$this->db->query("UPDATE category SET status = '0' WHERE product_count = 0");
		}

		if ($this->config->get('config_enable_non_empty_categories')){
			echoLine('[ControllerDPService::countProducts] ENABLE NON-EMPTY CATEGORIES = YES', 'i');
			$this->db->query("UPDATE category SET status = '1' WHERE product_count > 0");
		}
	}
}