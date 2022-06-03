<?php 
	class ControllerInformationactiontemplate extends Controller {
		
		private function getList(){
			
			
		}
		
		private function makedate($match){
			$s = $match[0];
			$s = explode(' ', $s);
			
			if (IS_DEBUG){
				$date = $this->model_catalog_actiontemplate->getActionTemplateLastHistoryDate(1353, (int)$this->request->get['actiontemplate_id']);
			} else {
				$date = $this->model_catalog_actiontemplate->getActionTemplateLastHistoryDate($this->customer->getID(), (int)$this->request->get['actiontemplate_id']);
			}
			
			if (!$date) {
				if ($s[1] == 'plus'){
					$return = date_add(date_create(), date_interval_create_from_date_string($s[2] . ' ' . $s[3]));
					} elseif ($s[1] == 'minus') {
					$return = date_sub(date_create(), date_interval_create_from_date_string($s[2] . ' ' . $s[3]));
				}
				} else {
				if ($s[1] == 'plus'){
					$return = date_add(date_create_from_format('Y-m-d H:i:s', $date), date_interval_create_from_date_string($s[2] . ' ' . $s[3]));
					} elseif ($s[1] == 'minus') {
					$return = date_sub(date_create_from_format('Y-m-d H:i:s', $date), date_interval_create_from_date_string($s[2] . ' ' . $s[3]));
				}
			}
			
			return date_format($return, 'd.m.Y');
		}
		
		public function index() {  
			
			$this->load->model('catalog/actiontemplate');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			//preauth
			$this->load->model('account/preauth');
			if (!$this->customer->isLogged()){
				if ($email = $this->model_account_preauth->CheckPreauth()){
					$this->customer->login($email, '', true);				
				}			
			}
			
			if (!IS_DEBUG && (!$this->customer->isLogged() || !isset($this->request->get['actiontemplate_id']))){
				header('Location: ' . $this->url->link('account/login'), true, 301);		
			}
			
			if (true) {
				$actiontemplate_id = (int)$this->request->get['actiontemplate_id'];
				$actiontemplate_info = $this->model_catalog_actiontemplate->getactiontemplate($actiontemplate_id);
				
				
				if ($actiontemplate_info) {
					
					$this->db->query("UPDATE actiontemplate SET viewed = viewed+1 WHERE actiontemplate_id = '" .(int)$actiontemplate_id. "'");
					
					$this->document->setKeywords($actiontemplate_info['meta_keyword']);
					$this->document->setDescription($actiontemplate_info['meta_description']);
					
					$this->data['breadcrumbs'][] = array(
					'text'      => $actiontemplate_info['seo_title'],
					'href'      => $this->url->link('information/actiontemplate', 'actiontemplate_id=' .  $actiontemplate_id),      		
					'separator' => $this->language->get('text_separator')
					);
					
					$this->data['heading_title'] = $actiontemplate_info['seo_title'];
					
					$this->data['button_continue'] = $this->language->get('button_continue');
					
					
					$this->data['continue'] = $this->url->link('common/home');
					
					$canonical = $this->url->link('information/actiontemplate', 'actiontemplate_id=' .  $actiontemplate_id);		
					
					$this->document->addLink('canonical', $canonical);
					
					
					//шаблонизируем-с
					$this->load->model('account/customer');
					if (IS_DEBUG){
						$customer = $this->model_account_customer->getCustomer(1353);	
						} else {
						$customer = $this->model_account_customer->getCustomer($this->customer->getID());
					}
					$template = html_entity_decode($actiontemplate_info['description'], ENT_QUOTES, 'UTF-8');
					$title = $actiontemplate_info['seo_title'];
					
					$tags = array(
					'{customer_name}' => 'customer-firstname',
					'{customer_lastname}' => 'customer-lastname',
					'{customer_email}' => 'customer-email',
					'{customer_utoken}' => 'customer-utoken',
					'{store_url}'       => $this->config->get('config_url'),
					);
					
					
					foreach ($tags as $key => $value){
						$tag = explode('-', $value);
						if ($tag && isset(${$tag[0]}) && isset(${$tag[0]}[$tag[1]])){
							$template = str_replace($key, ${$tag[0]}[$tag[1]], $template);
							$title = str_replace($key, ${$tag[0]}[$tag[1]], $title);
							} else {
							$template = str_replace($key, $value, $template);
							$title = str_replace($key, $value, $title);
						}				
					}
					
					
					
					$template = preg_replace_callback('/\{date (plus|minus) (\d+) days\}/', "self::makedate", $template);
					
					
					$this->document->setTitle($title);
					$this->data['description'] = $template;
					
					if ($actiontemplate_info['bottom']) {
						
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/actiontemplate.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/information/actiontemplate.tpl';
							} else {
							$this->template = 'default/template/information/actiontemplate.tpl';
						}
						
						} else {
						
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/actiontemplate_noshop.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/information/actiontemplate_noshop.tpl';
							} else {
							$this->template = 'default/template/information/actiontemplate_noshop.tpl';
						}
						
					}
					
					if ($actiontemplate_info['bottom']) {
						
						$this->children = array(
						'common/column_left',
						'common/column_right',
						'common/content_top',
						'common/content_bottom',
						'common/footer',
						'common/header'
						);
						
						} else {
						
						$this->children = array(
						'common/footer_landingnoshop',
						'common/header_landingnoshop'
						);
						
					}
					
					$this->response->setOutput($this->render());
					
					
					} else {
					
					
					$this->document->setTitle($this->language->get('text_error'));
					
					$this->data['heading_title'] = $this->language->get('text_error');
					
					$this->data['text_error'] = $this->language->get('text_error');
					
					$this->data['button_continue'] = $this->language->get('button_continue');
					
					$this->data['continue'] = $this->url->link('common/home');
					
					
					$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
						} else {
						$this->template = 'default/template/error/not_found.tpl';
					}
					
					$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
					);
					
					$this->response->setOutput($this->render());
				}
				
				}
				
			}
			
		}
	?>	