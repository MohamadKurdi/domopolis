<?php
	class ControllerModuleOCFilter extends Controller {
		private $error = array();
		
		public function index() {
			$this->data = array_merge($this->data, $this->load->language('module/ocfilter'));
			
			$this->document->setTitle($this->language->get('heading_title_module'));
			$this->document->addStyle('view/stylesheet/ocfilter/ocfilter.css');
			$this->document->addScript('view/javascript/ocfilter/ocfilter.js');
			
			$this->load->model('setting/setting');
			
			$this->data['heading_title'] = $this->language->get('heading_title_module');
			
			# OCFilter main settings
			#		 		key                    default
			
			$main_settings = array(
			'ocfilter_module' => array(array(
			'status' 							=> 0,
			'position' 						=> 'column_left',
			'sort_order' 					=> 0,
			)),
			
			'ocfilter_show_price' 					=> 0,
			'ocfilter_show_selected' 			=> 0,
			'ocfilter_show_diagram' 				=> 0,
			'ocfilter_show_counter' 				=> 0,
			'ocfilter_manual_price'   			=> 0,
			'ocfilter_consider_discount' 	=> 0,
			'ocfilter_consider_special' 		=> 0,
			'ocfilter_show_options_limit' 	=> '',
			'ocfilter_show_values_limit'		=> '',
			'ocfilter_hide_empty_values' 	=> 0,
			'ocfilter_manufacturer' 				=> 0,
			'ocfilter_manufacturer_type' 	=> 'checkbox',
			'ocfilter_stock_status' 				=> 0,
			'ocfilter_stock_status_type' 	=> 'radio',
			'ocfilter_stock_status_method' => 'quantity',
			'ocfilter_stock_out_value'     => 0,
			'ocfilter_noindex_limit' => 2
			);
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
				$this->model_setting_setting->editSetting('ocfilter', array_merge($main_settings, $this->request->post));
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				if (!isset($this->request->get['apply'])) {
					$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
					} else {
					$this->redirect($this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
				}
			}
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
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
			'text'      => $this->language->get('heading_title_module'),
			'href'      => $this->url->link('module/ocfilter', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			$this->data['save'] = $this->url->link('module/ocfilter', 'token=' . $this->session->data['token']);
			$this->data['apply'] = $this->url->link('module/ocfilter', 'token=' . $this->session->data['token'] . '&apply');
			
			$this->data['install'] = $this->url->link('module/ocfilter/install', 'update=1&token=' . $this->session->data['token']);
			
			$this->data['reinstall'] = $this->url->link('module/ocfilter', 'update=1&token=' . $this->session->data['token']);
			$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('catalog/ocfilter');
			$this->data['categories'] = $this->model_catalog_ocfilter->getCategories(0);
			
			$this->data['installed'] = $this->model_catalog_ocfilter->existsTables() && !isset($this->request->get['update']);
			
			if (!$this->data['installed']) {
				$this->data['validate_install'] = $this->validateInstall();
				
				$this->data['files'] = array();
				
				foreach ($this->model_catalog_ocfilter->getCodeSteps() as $step) {
					$basename = basename($step['file']);
					
					if (file_exists($step['file'])) {
						if (!is_writable($step['file'])) {
							$text = sprintf($this->language->get('text_file_not_writable'), $basename);
							} else {
							$text = sprintf($this->language->get('text_file_writable'), $basename);
						}
						} else {
						$text = sprintf($this->language->get('text_file_not_exist'), $basename);
					}
					
					$this->data['files'][] = array(
					'path' 	 => str_replace($basename, '', $step['file']),
					'text'    => $text
					);
				}
			}
			
			$this->load->model('setting/store');
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			$this->load->model('design/layout');
			$this->data['layouts'] = $this->model_design_layout->getLayouts();
			
			$this->data['positions'] = array(
			'content_top',
			'content_bottom',
			'column_left',
			'column_right'
			);
			
			$this->data['types'] = array(
			'checkbox',
			'radio',
			'select'
			);
			
			foreach ($main_settings as $key => $value) {
				if (isset($this->request->post[$key])) {
					$this->data[$key] = $this->request->post[$key];
					} else if ($this->config->has($key)) {
					$this->data[$key] = $this->config->get($key);
					} else {
					$this->data[$key] = $value;
				}
			}
			
			$this->template = 'module/ocfilter.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function copyAttributesCLI($store_id){			
			$this->load->model('catalog/ocfilter');				
			
			$data = [
				'truncate' 		=> true,
				'option'		=> false,
				'filter'		=> false,
				'attribute'		=> true,
				'option_store' 	=> [$store_id],
				'type'			=> 'checkbox'
			];
			
			$this->model_catalog_ocfilter->copyAttributesToOCFilter($data);				
		}
		
		public function copyAttributes() {
			$json = array();
			
			$this->load->language('module/ocfilter');
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['option_store']) && isset($this->request->post['type'])) {
				$this->load->model('catalog/ocfilter');
				
				$this->model_catalog_ocfilter->copyAttributesToOCFilter($this->request->post);
				
				$json['message'] = $this->language->get('text_ready');
				} else {
				$json['message'] = $this->language->get('error_fields');
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		private function validate() {
			if (!$this->user->hasPermission('modify', 'module/ocfilter')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$query = $this->db->query("SELECT layout_id FROM " . DB_PREFIX . "layout_route WHERE route = 'product/category' AND store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1");
			
			if ($query->num_rows) {
				$this->request->post['ocfilter_module'][0]['layout_id'] = $query->row['layout_id'];
				} else {
				$this->error['warning'] = sprintf($this->language->get('error_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		private function validateInstall() {
			$this->config->load('ocfilter');
			$this->load->model('catalog/ocfilter');
					
			
			return true;
		}
		
		public function install() {
			if (!isset($this->request->get['update'])) {
				$this->redirect($this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->load->language('module/ocfilter');
			$this->load->model('catalog/ocfilter');
			
			if ($this->validateInstall()) {
			//	$this->model_catalog_ocfilter->installCode();
			//	$this->model_catalog_ocfilter->createTables();
				
				$this->load->model('user/user_group');
				
				$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'catalog/ocfilter');
				$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'catalog/ocfilter');
				
				$this->session->data['success'] = sprintf($this->language->get('text_success_create'), $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->redirect($this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}