<?php
class ControllerModuleCustomproduct extends Controller {
	protected function index($setting) {

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
		}else{
			$category_id = 0;
		}

		$this->data['unique_id'] = md5(serialize($setting));

		if($setting['category']==0 || $setting['category'] == $category_id){



			$this->load->model('catalog/product'); 					
			$this->load->model('tool/image');
			$this->language->load('module/customproduct'); 

			$this->data['heading_title'] = $setting[(int)$this->config->get('config_language_id')]['name'];

			$this->data['description'] = $setting[(int)$this->config->get('config_language_id')]['description'];

			$this->data['button_cart'] = $this->language->get('button_cart');

			$this->data['products'] = array();

			$this->data['dimensions'] = array(
				'w' => $setting['image_width'],
				'h' => $setting['image_height']
			);
			$this->data['position'] = $setting['position'];

			$products = explode(',', $setting['products']);       

			if ($setting['random'] && $setting['random_limit']){
				if ($setting['random'] == 'specials'){							

					$special_data = array(
						'start' => 0,
						'limit' => (int)$setting['random_limit'],
						'sort' => 'RAND()',
						'return_just_ids' => true
					);		

					if ($category_id && $setting['category'] == $category_id) {
						$special_data['filter_category_id'] = $category_id;
					}

					$products = $this->model_catalog_product->getProductSpecials($special_data);
				}					
			}	

			if ($setting['random'] && $setting['random_limit']){
				if ($setting['random'] == 'list'){			

					$random_keys = array_rand ( $products, $setting['random_limit'] );
					$tmp_products = $products;
					$products = array();
					foreach ($random_keys as $__key){
						$products[] = $tmp_products[$__key];
					}													
				}
			}

			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($products);

			$this->data['href'] = ($setting[(int)$this->config->get('config_language_id')]['href'])?$setting[(int)$this->config->get('config_language_id')]['href']:$this->url->link('common/home');

			if ($setting['image'] && mb_strlen($setting['image']) > 2){
				$this->data['image'] = $this->model_tool_image->resize($setting['image'], $setting['big_image_width'], $setting['big_image_height'], '', 80);						

				if ($setting['sort_order']%2 == 0){
					$this->data['class'] = 'layout-left';				
				} else {
					$this->data['class'] = 'layout-right';
				}

				$this->template = 'module/customproduct_with_image';

			} else {
				$this->template = 'module/customproduct';
			}

			$this->render();
		}
	}
}