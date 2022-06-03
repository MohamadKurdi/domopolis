<?php
	class ControllerModuleLatest extends Controller {
		protected function index($setting) {
			
			$product_id = 0;
			$path_id = 0;
			$cid = 0;
			$category_id = false;
			$store_id = $this->config->get('config_store_id');
			$language_id = $this->config->get('config_language_id');
			$currency_id = $this->currency->getId();
			if (isset($this->request->get['path'])){
				$path_id = $this->request->get['path'];
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = (int)array_pop($parts);
				$cid = $category_id;
				} else {
				$cid = 0;			
			}
			if (isset($this->request->get['manufacturer_id'])){
				$mid = $this->request->get['manufacturer_id'];			
				} else {
				$mid = 0;
			}
			
			$limit = $setting['limit'];
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getCustomerGroupId();
				} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}	
			
			$this->bcache->SetFile('module_'.$mid.$currency_id.$language_id.$cid.md5(serialize($setting)). '.tpl', 'latest_module'.$store_id);
			
			if ($this->bcache->CheckFile()) {		
				
				$out = $this->bcache->ReturnFileContent();
				$this->setBlockCachedOutput($out);
				
				} else {
				
				$do_not_continue = false;
				if ($mid){
					
					$this->load->model('catalog/manufacturer');
					$ao = $this->model_catalog_manufacturer->getManufacturerContentByManufacturerId($mid);
					
					if ($ao) {
						$out = '';
						$do_not_continue = true;
						$this->bcache->WriteFile($out);			
					} 
				}
				
				
				if (!$do_not_continue)	{	
					
					$this->language->load('module/latest');
					
					$this->data['heading_title'] = $this->language->get('heading_title');
					
					$this->data['button_cart'] = $this->language->get('button_cart');
					
					$this->load->model('catalog/product');
					
					$this->load->model('tool/image');
					
					$this->data['products'] = array();
					
					if ($mid) {
						$data = array(			
						'sort'  => 'p.date_added',
						'order' => 'DESC',
						'start' => 0,
						'limit' => $setting['limit'],
						'filter_manufacturer_id' => $mid
						);
						} elseif ($cid)	{
						$data = array(			
						'sort'  => 'p.date_added',
						'order' => 'DESC',
						'start' => 0,
						'limit' => $setting['limit'],
						'filter_category_id' => $cid
						);
						} else {
						$data = array(			
						'sort'  => 'p.date_added',
						'order' => 'DESC',
						'start' => 0,
						'limit' => $setting['limit']
						);
						
					}
					
					$this->data['position'] = $setting['position'];
					$this->data['dimensions'] = array(
					'w' => $setting['image_width'],
					'h' => $setting['image_height']
					);
					
					$results = $this->model_catalog_product->getProducts($data);
					
					$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/module/latest.tpl';
						} else {
						$this->template = 'default/template/module/latest.tpl';
					}
					
					$out = $this->render();
					$this->bcache->WriteFile($out);
				}
			}
		}
	}