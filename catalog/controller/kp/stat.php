<?php
	
	class ControllerKPStat extends Controller {
		public function online(){
			$json = array();
			
			$this->load->model('catalog/viewed');		    
                
			if (!empty($this->request->get['stat'])){
				$stats = explode('_', (string)$this->request->get['stat']);
				foreach ($stats as $stat){
					$stat = explode(':', $stat);

					if (!empty($stat[0]) && !empty($stat[1])){
						if ($stat[0] == 'c'){
							$this->model_catalog_viewed->addToCustomerViewed('c', (int)$stat[1]);
							$this->model_catalog_viewed->updateCategoryViewed((int)$stat[1]);

							$json[] = ['c' => (int)$stat[1]];
						}

						if ($stat[0] == 'p'){
							$this->model_catalog_viewed->addToCustomerViewed('p', (int)$stat[1]);

							$this->model_catalog_viewed->addToViewed((int)$stat[1]);  
							$this->model_catalog_viewed->updateProductViewed((int)$stat[1]);          				

							if ($this->config->get('config_product_alsoviewed_enable')){
								$this->model_catalog_viewed->catchAlsoViewed((int)$stat[1]);
							}     

							$json[] = ['p' => (int)$stat[1]];       						
						}

						if ($stat[0] == 'm'){
							$this->model_catalog_viewed->addToCustomerViewed('m', (int)$stat[1]);

							$json[] = ['m' => (int)$stat[1]];
						}
					}
				}            	
			}

			if ($this->config->get('config_customer_online')) {
				$this->load->model('tool/online');
				
				if (isset($this->request->server['REMOTE_ADDR'])) {
					$ip = $this->request->server['REMOTE_ADDR'];	
					} else {
					$ip = ''; 
				}
				
				if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
					$url = 'https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];	
					} else {
					$url = '';
				}
				
				if (isset($this->request->server['HTTP_REFERER'])) {
					$referer = $this->request->server['HTTP_REFERER'];	
					} else {
					$referer = '';
				}
				
				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$useragent = $this->request->server['HTTP_USER_AGENT'];	
					} else {
					$useragent = '';
				}
				
				$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer, $useragent);
				$json['success'] = true;
			}
			
			$this->response->setOutput(json_encode($json));
		}
	}
