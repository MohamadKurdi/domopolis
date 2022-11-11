<?php  
	class ControllerModuleAlsoviewed extends Controller {
		public function index() {
			$this->language->load('module/alsoviewed');
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['button_cart'] = $this->language->get('button_cart');
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$this->data['data']['AlsoViewed'] = str_replace('http', 'https', $this->config->get('AlsoViewed'));
				} else {
				$this->data['data']['AlsoViewed'] = $this->config->get('AlsoViewed');
			}
			
			
			
			$ajaxrequest = true;//!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
			
			$this->data['data']['AlsoViewedConfig'] = $this->config->get('alsoviewed_module');
			
			$this->data['position'] = $this->data['data']['AlsoViewedConfig'][0]['position'];
			
			if ($ajaxrequest == false) {
				$this->data['product_id'] = (isset($this->request->get['product_id'])) ? $this->request->get['product_id'] : 0;
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsoviewed.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/alsoviewed.tpl';
					} else {
					$this->template = 'default/template/module/alsoviewed.tpl';
				}
				
				$this->response->setOutput($this->render());
				return false;			
				} else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsoviewed/alsoviewed.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/alsoviewed/alsoviewed.tpl';
					} else {
					$this->template = 'default/template/module/alsoviewed/alsoviewed.tpl';
				}
				
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
		
		
		private function listAlsoViewedById($product_id, $limit=5) {
			$this->load->model('catalog/product');
			
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getCustomerGroupId();
				} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}			
			
			$product_alsoviewed_data = $this->cache->get('product.alsoviewed.' .(int)$product_id. '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . $limit);
			
			if (!$product_alsoviewed_data) {
				
				//get low products
				$sql = "SELECT * FROM `alsoviewed` a LEFT JOIN product p ON p.product_id = a.high WHERE a.`low` = '" . (int)$product_id . "' AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') ."' ORDER BY `number` DESC LIMIT " . (int)$limit . "";
				$data_low = $this->db->non_cached_query($sql);
				
				$count_data_low = count($data_low->rows);
				$rows = $data_low->rows;
				
				if ($count_data_low < $limit){
					$limit = $limit - $count_data_low;
					
					//get high products
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
				
				//overload by random products
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
				
				$this->cache->set('product.alsoviewed.' .(int)$product_id. '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . $limit, $products);
				
			}
			
			return $product_alsoviewed_data;
		}
		
	}
?>