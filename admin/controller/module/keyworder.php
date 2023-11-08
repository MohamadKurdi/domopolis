<?php
class ControllerModuleKeyworder extends Controller {
	private $error = array();

    public function index() {
        $this->language->load('module/keyworder');

        $this->document->setTitle($this->language->get('heading_title_fake'));

        $this->load->model('module/keyworder');
		
		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
		} else {
			$filter_manufacturer_id = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . (int)$this->request->get['filter_manufacturer_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . (int)$this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/keyworder', 'token=' . $this->session->data['token'] . $url),
      		'separator' => ' :: '
   		);

		$this->data['keyworder_manufacturer'] = $this->url->link('module/keyworder', 'token=' . $this->session->data['token']);

		$this->data['settings'] = $this->url->link('module/keyworder/settings', 'token=' . $this->session->data['token']);

		$this->data['scan'] = $this->url->link('module/keyworder/scanKeyworder', 'token=' . $this->session->data['token'] . $url);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

        $this->data['keyworder'] = array();

        $data = array(
			'filter_manufacturer_id'	=> $filter_manufacturer_id,
			'filter_category_id'	 	=> $filter_category_id,
			'start' 					=> ($page - 1) * 10,
			'limit' 					=> 10
		);

		$results = $this->model_module_keyworder->getKeyworder($data);

		$total = $this->model_module_keyworder->getTotalKeyworder($data);

		$this->load->model('catalog/manufacturer');

		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		foreach ($results['val'] as $result) {
			if (isset($results['dat'][$result['keyworder_id']])) {
				foreach ($results['dat'][$result['keyworder_id']] as $dat) {					
					$keyworder_description[$result['keyworder_id']][$dat['language_id']] = array(
						'seo_h1' 			=> $dat['seo_h1'],
						'seo_title' 		=> $dat['seo_title'],
						'meta_keyword' 		=> $dat['meta_keyword'],
						'meta_description'	=> $dat['meta_description'],
						'description' 		=> $dat['description'],
						'category_status' 	=> $dat['category_status'],
						'keyworder_status' 	=> $dat['keyworder_status'],
						'image'             => $dat['image'],
						'thumb'             => $this->model_tool_image->resize($dat['image'], 50, 50)
					);
				}
			} else {
				$keyworder_description[$result['keyworder_id']] = null;
			}

			foreach ($this->data['manufacturers'] as $man) {
				if ($man['manufacturer_id'] == $result['manufacturer_id']) {
					$manufacturer_name = $man['name'];
				}
			}

			foreach ($this->data['categories'] as $cat) {
				if ($cat['category_id'] == $result['category_id']) {
					$category_name = $cat['name'];
				}
			}

			$this->data['keyworder'][$result['keyworder_id']] = array(
				'keyworder_id'	   		=> $result['keyworder_id'],
				'manufacturer_id'		=> $result['manufacturer_id'],
				'category_id' 			=> $result['category_id'],
				'manufacturer_name'		=> $manufacturer_name,
				'category_name' 		=> $category_name,
				'keyworder_description' => $keyworder_description[$result['keyworder_id']]
			);
        }

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['text_select_manufacturer'] = $this->language->get('text_select_manufacturer');
		$this->data['text_select_category'] = $this->language->get('text_select_category');
		$this->data['text_category_status'] = $this->language->get('text_category_status');
		$this->data['text_manufacturer_status'] = $this->language->get('text_manufacturer_status');
		
		$this->data['column_brand'] = $this->language->get('column_brand');
		$this->data['column_category'] = $this->language->get('column_category');
		$this->data['column_h1'] = $this->language->get('column_h1');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_meta_keyword'] = $this->language->get('column_meta_keyword');
		$this->data['column_meta_description'] = $this->language->get('column_meta_description');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['button_keyworder_manufacturer'] = $this->language->get('button_keyworder_manufacturer');
		$this->data['button_settings'] = $this->language->get('button_settings');
		$this->data['button_scan'] = $this->language->get('button_scan');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->data['token'];
			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

 		if (isset($this->error['warning'])) {
			$this->data['warning'] = $this->error['warning'];
		} else {
			$this->data['warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';
		
		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . (int)$this->request->get['filter_manufacturer_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . (int)$this->request->get['filter_category_id'];
		}

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

		$url = '';
		
		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . (int)$this->request->get['filter_manufacturer_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . (int)$this->request->get['filter_category_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/keyworder', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_category_id'] = $filter_category_id;

		$this->data['filter_manufacturer_id'] = $filter_manufacturer_id;

		$this->template = 'module/keyworder_manufacturer.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }

	public function saveKeyworder() {
		$this->language->load('module/keyworder');

		if ((isset($this->request->post['keyworder_id'])) && ($this->validate_modify())) {
			$this->load->model('module/keyworder');

			$this->model_module_keyworder->saveKeyworder($this->request->post);
		}
	}

    public function scanKeyworder() {
		$this->language->load('module/keyworder');

		if ($this->validate_modify()) {
			$this->load->model('module/keyworder');

			$this->model_module_keyworder->scanKeyworder();
            
			$url = '';
		
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . (int)$this->request->get['filter_manufacturer_id'];
			}

			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . (int)$this->request->get['filter_category_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->session->data['success'] = sprintf($this->language->get('text_success_scan'), $this->model_module_keyworder->getCountAdded(), $this->model_module_keyworder->getCountDeleted());

		//	$this->redirect($this->url->link('module/keyworder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->index();
    }

	public function settings() {
		$this->language->load('module/keyworder');

		$this->document->setTitle($this->language->get('heading_title_settings'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_modify()) {
			$this->model_setting_setting->editSetting('keyworder', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/keyworder/settings', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title_settings');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_notice'] = $this->language->get('text_notice');
		$this->data['text_module_location'] = $this->language->get('text_module_location');
		$this->data['text_module_template'] = $this->language->get('text_module_template');
		$this->data['text_template_help'] = $this->language->get('text_template_help');
		$this->data['text_column_name'] = $this->language->get('text_column_name');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_image_description'] = $this->language->get('text_image_description');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_size'] = $this->language->get('entry_size');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_count'] = $this->language->get('entry_count');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_keyworder_manufacturer'] = $this->language->get('button_keyworder_manufacturer');
		$this->data['button_settings'] = $this->language->get('button_settings');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['column_h1'] = $this->language->get('column_h1');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_meta_keyword'] = $this->language->get('column_meta_keyword');
		$this->data['column_meta_description'] = $this->language->get('column_meta_description');
		$this->data['column_description'] = $this->language->get('column_description');

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

 		if (isset($this->error['warning'])) {
			$this->data['warning'] = $this->error['warning'];
		} else {
			$this->data['warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/keyworder', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_settings'),
			'href'      => $this->url->link('module/keyworder/settings', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);

		$this->data['keyworder_manufacturer'] = $this->url->link('module/keyworder', 'token=' . $this->session->data['token']);

		$this->data['action'] = $this->url->link('module/keyworder/settings', 'token=' . $this->session->data['token']);
		
		$this->data['generate_url'] = $this->url->link('module/keyworder/generate', 'token=' . $this->session->data['token']);

		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

		$this->data['modules'] = array();
		
		if (isset($this->request->post['keyworder_module'])) {
			$this->data['modules'] = $this->request->post['keyworder_module'];
		} elseif ($this->config->get('keyworder_module')) {
			$this->data['modules'] = $this->config->get('keyworder_module');
		}

		$this->data['keyworder_template'] = array();
		
		if (isset($this->request->post['keyworder_template'])) {
			$this->data['keyworder_template'] = $this->request->post['keyworder_template'];
		} elseif ($this->config->get('keyworder_template')) {
			$this->data['keyworder_template'] = $this->config->get('keyworder_template');
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/keyworder.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function generate(){
		if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate_modify()) {
			$this->load->model('module/keyworder');			
			$this->model_module_keyworder->generateKeyworders($this->request->post['keyworder_template']);			
			
			$this->response->setOutput('Сгенерировали! Чудо-то какое!');			
		}		
	}

    private function validate_modify() {
        if (!$this->user->hasPermission('modify', 'module/keyworder')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
		if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function install() {
        $this->load->model('module/keyworder');

        $this->model_module_keyworder->install();
    }

    public function uninstall() {
        $this->load->model('module/keyworder');

        $this->model_module_keyworder->uninstall();
    }
}
?>
