<?php
	class ControllerModuleAjaxSearch extends Controller {
		
		public function callback() {
			
			$this->load->model('catalog/supersearch');
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/collection');
			$this->load->model('tool/image');
			
			
			$key = (isset($this->request->get['keyword'])) ? $this->request->get['keyword'] : null;
			$json = array();
			
			if (mb_strlen($key)>1) {
				$results = $this->model_catalog_supersearch->search($key);
				
				foreach ($results as $result) {
					
					if ($result['type'] == 'm'){
						$manufacturer = $this->model_catalog_manufacturer->getManufacturer($result['id']);
						
						$json['results'][] = array(
						'type'  => 'm',
						'label'	=> $manufacturer['name'],
						'url' 	=> $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['id']),
						'price' => '',
						'desc' 	=> '<i>Смотрите все товары бренда &rarr;</i>',
						'img' 	=> ($manufacturer['image']) ? $this->model_tool_image->resize($manufacturer['image'], 50, 50) : ''
						);		
						
					}
					
					if ($result['type'] == 'c'){
						$category = $this->model_catalog_category->getCategory($result['id']);
						
						$json['results'][] = array(
						'type'  => 'm',
						'label'	=> $category['name'],
						'url' 	=> $this->url->link('product/category', 'path=' . $result['id']),
						'price' => '',
						'desc' 	=> '<i>Смотрите все товары категории &rarr;</i>',
						'img' 	=> ($category['image']) ? $this->model_tool_image->resize($category['image'], 50, 50) : ''
						);										
					}
					
					
					if ($result['type'] == 'p'){
						$product = $this->model_catalog_product->getProduct($result['id']);
						
						if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
							} else {
							$price = sprintf("%.2f", $result['price']);
						}
						
						if ((float)$product['special']) {
							$price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
						}
						
						
						$json['results'][] = array(
						'type'  => 'p',
						'label'	=> $product['name'],
						'url' 	=> $this->url->link('product/product', 'product_id=' . $result['id']),
						'price' => $price,
						'desc' 	=> mb_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, 40) . '..',
						'img' 	=> ($product['image']) ? $this->model_tool_image->resize($product['image'], 50, 50) : ''
						);				
					}
					
					
					
					
					
				}
				
				$json['results']['length'] = count($results);
				$this->response->setOutput(json_encode($json));
				} else {
				return false;			
			}
		}
	}	