<?php
class ControllerModuleKeyworder extends Controller {
	protected function index($setting) {
		if ((isset($this->request->get['route'])) && ($this->request->get['route'] == 'product/category') && (isset($this->request->get['path']))) {
			$this->language->load('module/keyworder'); 

			$this->data['heading_title'] = $this->language->get('heading_title_m');
			$this->data['button_all'] = $this->language->get('button_all');

			$this->load->model('module/keyworder');
			
			$this->load->model('catalog/product');

			$this->load->model('tool/image');

			$catparts = explode('_', (string)$this->request->get['path']);
		
			$category_id = (int)array_pop($catparts);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['href_all'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url);

			$this->data['manufacturers'] = array();

			$results = $this->model_module_keyworder->getManufacturersByCategory($category_id);

			foreach ($results as $result) {
				if ((isset($result['manufacturer_id'])) && ($result['manufacturer_id'] > 0)) {
					if (($setting['image_status'] > 0) && (!empty($result['image']))) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} elseif ($setting['image_status'] > 0) {
						$image = $this->model_tool_image->resize('no_image.jpg', $setting['width'], $setting['height']);
					} else {
						$image = null;
					}

					if ($setting['count'] > 0) {
						$data = array(
							'filter_category_id'     => $category_id,
							'filter_manufacturer_id' => $result['manufacturer_id']
						);

						$total = $this->model_catalog_product->getTotalProducts($data);

						$this->data['manufacturers'][] = array(
							'manufacturer_id' => $result['manufacturer_id'],
							'name'       	  => $result['name'] . ' (' . $total . ')',
							'image'       	  => $image,
							'href'            => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&manufacturer_id=' . $result['manufacturer_id'] . $url)
						);
					} else {
						$this->data['manufacturers'][] = array(
							'manufacturer_id' => $result['manufacturer_id'],
							'name'       	  => $result['name'],
							'image'       	  => $image,
							'href'            => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&manufacturer_id=' . $result['manufacturer_id'] . $url)
						);
					}
				}
			}

			if ($this->data['manufacturers']) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/keyworder_manufacturer.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/keyworder_manufacturer.tpl';
				} else {
					$this->template = 'default/template/module/keyworder_manufacturer.tpl';
				}

				$this->render();
			}
		} elseif ((isset($this->request->get['route'])) && ($this->request->get['route'] == 'product/manufacturer/info') && (isset($this->request->get['manufacturer_id']))) {
			$this->language->load('module/keyworder'); 

			$this->data['heading_title'] = $this->language->get('heading_title_c');

			$this->load->model('module/keyworder');
			
			$this->load->model('catalog/product');

			$this->load->model('tool/image');
		
			$manufacturer_id = $this->request->get['manufacturer_id'];

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['categories'] = array();

			$results = $this->model_module_keyworder->getCategoriesByManufacturer($manufacturer_id);

			foreach ($results as $result) {
				if (($setting['image_status'] > 0) && (!empty($result['image']))) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} elseif ($setting['image_status'] > 0) {
					$image = $this->model_tool_image->resize('no_image.jpg', $setting['width'], $setting['height']);
				} else {
					$image = null;
				}

				if ($setting['count'] > 0) {
					$data = array(
						'filter_category_id'     => $result['category_id'],
						'filter_manufacturer_id' => $manufacturer_id
					);

					$total = $this->model_catalog_product->getTotalProducts($data);

					$this->data['categories'][] = array(
						'category_id' 	  => $result['category_id'],
						'name'       	  => $result['name'] . ' (' . $total . ')',
						'image'       	  => $image,
						'href'            => $this->url->link('product/category', 'path=' . $result['category_id'] . '&manufacturer_id=' . $manufacturer_id . $url)
					);
				} else {
					$this->data['categories'][] = array(
						'category_id'     => $result['category_id'],
						'name'       	  => $result['name'],
						'image'       	  => $image,
						'href'            => $this->url->link('product/category', 'path=' . $result['category_id'] . '&manufacturer_id=' . $manufacturer_id . $url)
					);
				}
			}

			if ($this->data['categories']) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/keyworder_category.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/keyworder_category.tpl';
				} else {
					$this->template = 'default/template/module/keyworder_category.tpl';
				}

				$this->render();
			}
		}
	}
}
?>