<?php
class ControllerModuleOCAAB extends Controller {
	private $extension = 'ocaab';
	private $extensionType = 'module';
	
	protected function index($setting) {	
		static $module = 0;
		
		if ($setting['status']) {
			
			$status = true;
			
			$banner_data = array();
			
			$this->data['banners']		= array();
			$this->data['extension']	= $this->extension;
			
			$this->load->model('design/banner');			
			$this->load->model('tool/image');
			
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getCustomerGroupId();
			} else {
				$customer_group_id = 0;
			}
			
			$this->load->model('localisation/language');
			$this->load->model('catalog/product');
			
			$config_language = $this->config->get('config_language');
			
			$language_code = isset($this->session->data['language']) ? $this->session->data['language'] : $config_language;
			
			if (isset($this->request->get['path'])) {
				$path = '';
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = array_pop($parts);
			} else {
				$category_id = false;
			}
			
			if (isset($this->request->get['product_id'])) {
				$product_id = $this->request->get['product_id'];
				$product_categories = $this->model_catalog_product->getCategories($product_id);
			} else {
				$product_id = 0;
				$product_categories = array();
			}
			
			if (isset($this->request->get['information_id'])) {
				$information_id = $this->request->get['information_id'];
			} else {
				$information_id = 0;
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = $this->request->get['manufacturer_id'];
			} else {
				$manufacturer_id = 0;
			}
			
			$stores = isset($setting['stores']) ? $setting['stores'] : array();
			$customer_groups = isset($setting['customer_groups']) ? $setting['customer_groups'] : array();
			$languages = isset($setting['languages']) ? $setting['languages'] : array();
			$layouts = isset($setting['layouts']) ? $setting['layouts'] : array();
			$categories = isset($setting['categories']) ? $setting['categories'] : array();
			$manufacturers = isset($setting['manufacturers']) ? $setting['manufacturers'] : array();
			
			if (!in_array($this->config->get('config_store_id'), $stores)) {
				$status = false;
			}
			
			if (!in_array($customer_group_id, $customer_groups)) {
				$status = false;
			}
			
			if (!in_array($language_code, $languages) && !in_array('0', $languages)) {
				$status = false;
			}
			
			if (!in_array($manufacturer_id, $manufacturers) && !in_array('0', $manufacturers)) {
				$status = false;
			}
			
			$cat_status = false;
			
			if ($categories && ($product_categories || $category_id)) {
				if (in_array(0, $categories)) {
					$cat_status = true;
				} elseif ($product_id) {
					foreach ($product_categories as $product_category) {
						if (in_array($product_category['category_id'], $categories)) {
							$cat_status = true;
							break;
						}
					}
				} elseif ($category_id) {
					if (in_array($category_id, $categories)) {
						$cat_status = true;
					}
				} 
			} else {
				$cat_status = true;
			}
			
			if (!$cat_status) {
				$status = false;
			}
			
			if ($setting['date_start']) {
				if ($setting['date_start'] > date("Y-m-d H:i:s") || !$setting['date_start'] == "0000-00-00") {
					$status = false;
				}
			}
			
			if ($setting['date_end']) {
				if ($setting['date_end'] < date("Y-m-d H:i:s") || !$setting['date_end'] == "0000-00-00") {
					$status = false;
				}
			}	
			
			if ($status) {
				if ($categories && in_array(0, $categories)) {
					$categories = false;
				}				
				$cache = $this->extension . '.' . $module . '.' . $this->config->get('config_store_id') . '.' . $customer_group_id . '.' . $category_id . '.' . $information_id;
				$banner_data = $this->cache->get($cache);
				
				$this->data['banner_settings'] = array(
					'effect'			=> !empty($setting['banner_effect']) ? $setting['banner_effect'] : 'fade',
					'speed'				=> !empty($setting['banner_speed']) ? $setting['banner_speed'] : '500',
					'timeout'			=> !empty($setting['banner_pausetime']) ? $setting['banner_timeout'] : '4000',
					'pause'				=> !empty($setting['banner_pauseonhover']) ? $setting['banner_pause'] : '1'
				);
				
				$this->data['slideshow_settings'] = array(
					'effect'			=> !empty($setting['slideshow_effect']) ? $setting['slideshow_effect'] : 'random',
					'animSpeed'			=> !empty($setting['slideshow_animspeed']) ? $setting['slideshow_animspeed'] : '500',
					'pauseTime'			=> !empty($setting['slideshow_pausetime']) ? $setting['slideshow_pausetime'] : '4000',
					'startSlide'		=> !empty($setting['slideshow_startslider']) ? $setting['slideshow_startslider'] : '0',
					'directionNav'		=> !empty($setting['slideshow_directionnav']) ? $setting['slideshow_directionnav'] : 'true',
					'controlNav'		=> !empty($setting['slideshow_controlnav']) ? $setting['slideshow_controlnav'] : 'true',
					'pauseOnHover'		=> !empty($setting['slideshow_pauseonhover']) ? $setting['slideshow_pauseonhover'] : 'true',
					'manualAdvance'		=> !empty($setting['slideshow_manualadvance']) ? $setting['slideshow_manualadvance'] : 'false',
					'prevText'			=> !empty($setting['slideshow_prevtext']) ? $setting['slideshow_prevtext'] : 'Prev',
					'nextText'			=> !empty($setting['slideshow_nexttext']) ? $setting['slideshow_nexttext'] : 'Next',
					'randomStart'		=> !empty($setting['slideshow_randomstart']) ? $setting['slideshow_randomstart'] : 'false'
				);
				
				if (!$banner_data || !$setting['cache']) {
					$banner_data = array();
					
					if ($setting['banners']) {
						$x = 1;
						
						foreach ($setting['banners'] as $banner) {
							if ($banner['banner_id']) {
								$banners = array();
								
								$results = $this->model_design_banner->getBanner($banner['banner_id']);
								
								foreach ($results as $result) {
									$banners[] = array(
										'title' => $result['title'],
										'link'  => $result['link'],
										'image' => $this->model_tool_image->resize($result['image'], (int)$banner['image_width'], (int)$banner['image_height'], '',85)
									);
								}
								$banner_data[$x] = array(
									'banners'		=> $banners,
									'float'			=> $banner['float'],
									'clear'			=> $banner['clear'],
									'margin_left'	=> (int)$banner['margin_left'],
									'margin_right'	=> (int)$banner['margin_right'],
									'margin_top'	=> (int)$banner['margin_top'],
									'margin_bottom'	=> (int)$banner['margin_bottom'],
									'width'		=> (int)$banner['image_width'],
									'height'		=> (int)$banner['image_height']
								);
								$x++;
								
								$this->data['banners']['height'] = (int)$banner['image_height'];
							}	
						}
						if ($setting['cache']) {
							$this->cache->set($cache, $banner_data);
						}
					}
				}
				$this->data['banners'] = $banner_data;
			}	
		}
		if ($setting['display_type'] == 1) {
			$this->data['width'] 	= '100%';
			$this->data['height'] 	= '100%';
			
			$this->document->addScript('catalog/view/javascript/jquery/nivo-slider/jquery.nivo.slider.pack.js');
			
			if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/slideshow.css')) {
				$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/slideshow.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/slideshow.css');
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->extensionType . '/' . $this->extension . '_slideshow.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/' . $this->extensionType . '/' . $this->extension . '_slideshow.tpl';
			} else {
				$this->template = 'default/template/' . $this->extensionType . '/' . $this->extension . '_slideshow.tpl';
			}
		} else {
			$this->document->addScript('catalog/view/javascript/jquery/jquery.cycle.js');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->extensionType . '/' . $this->extension . '.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/' . $this->extensionType . '/' . $this->extension . '.tpl';
			} else {
				$this->template = 'default/template/' . $this->extensionType . '/' . $this->extension . '.tpl';
			}
		}
		$this->data['module'] = $module++;
		$this->render();
	}
}