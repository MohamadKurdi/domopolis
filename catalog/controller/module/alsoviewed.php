<?php  
class ControllerModuleAlsoviewed extends Controller {
	public function index() {
		$this->language->load('module/alsoviewed');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$ajaxrequest = true;
		
		$this->data['data']['AlsoViewedConfig'] = $this->config->get('alsoviewed_module');
		
		$this->data['position'] = $this->data['data']['AlsoViewedConfig'][0]['position'];
		
		if ($ajaxrequest == false) {
			$this->data['product_id'] = (isset($this->request->get['product_id'])) ? $this->request->get['product_id'] : 0;
			$this->template = 'module/alsoviewed.tpl';
			
			$this->response->setOutput($this->render());
			return false;			
		} else {
			$this->template = 'module/alsoviewed/alsoviewed.tpl';				
		}
		
		if (isset($this->request->get['product_id'])) {
			$alsoViewedProducts = $this->listAlsoViewedById((int)$this->request->get['product_id'],(int)$this->data['data']['AlsoViewed']['NumberOfProducts']);
		} else {
			$alsoViewedProducts = array();
		}
		
		$this->load->model('tool/image');

		$this->data['products'] = $this->model_catalog_product->prepareProductToArray($alsoViewedProducts);
		
		$this->response->setOutput($this->render());
	}
	
	public function getindex() {
		echo($this->index());
	}
	
	
	private function listAlsoViewedById($product_id, $limit = 5) {
		$this->load->model('catalog/product');
		
		$product_alsoviewed_data = $this->cache->get($this->registry->createCacheQueryString(__METHOD__, $setting, [$product_id, $limit]));
		
		if (!$product_alsoviewed_data) {				
			$sql = "SELECT * FROM `alsoviewed` a LEFT JOIN product p ON p.product_id = a.high WHERE a.`low` = '" . (int)$product_id . "' AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') ."' ORDER BY `number` DESC LIMIT " . (int)$limit . "";
			$data_low = $this->db->non_cached_query($sql);
			
			$count_data_low = count($data_low->rows);
			$rows = $data_low->rows;
			
			if ($count_data_low < $limit){
				$limit = $limit - $count_data_low;
				
				$sql = "SELECT * FROM `alsoviewed` a LEFT JOIN product p ON p.product_id = a.low WHERE a.`high` = '" . (int)$product_id . "' AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') ."' ORDER BY `number` DESC LIMIT " . (int)$limit . "";
				$data_high = $this->db->non_cached_query($sql);
				
				foreach ($data_high->rows as $dhrow){
					$rows[] = $dhrow;
				}
			}
			
			$products = array();
			foreach ($rows as $row) {
				if ($row['low'] == $product_id) {
					$pid = $row['high'];
				} else {
					$pid = $row['low'];
				}
				if($row){				
					if ($_product = $this->model_catalog_product->getProduct($pid)) {
						$products[$pid] = $_product; 
					}
				}
			}
			
			if (!$products){
				$sql = "SELECT product_id FROM `product` p WHERE 
				p.product_id <> '" . (int)$product_id . "' AND 
				p.is_option_for_product_id = 0 AND 
				p.stock_product_id = 0 AND 
				p.status = 1 AND 
				p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') ."'
				ORDER BY RAND() LIMIT " . (int)$limit . "";									
				
				$query = $this->db->query($sql);
				
				foreach ($query->rows as $row){
					$pid = $row['product_id'];
					if ($_product = $this->model_catalog_product->getProduct($pid)) {
						$products[$pid] = $_product; 
					}
				}					
			}
			
			$product_alsoviewed_data = $products;
			
			$this->cache->set($this->registry->createCacheQueryString(__METHOD__, $setting, [$product_id, $limit]), $products);
			
		}
		
		return $product_alsoviewed_data;
	}
	
}