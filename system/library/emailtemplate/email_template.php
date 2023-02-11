<?php
	/**
		* HTML Email template extension
		*
		* @author: Ben Johnson, opencart-templates
		* @email: info@opencart-templates.co.uk
		* @website: http://www.opencart-templates.co.uk
		*
	*/
	if(!defined('DIR_SYSTEM')) exit;
	
	class EmailTemplate extends Template {
		
		public static $version = '2.4.1';
		public static $debug = false;
		public static $template_vqmod = true;
		
		public $data = array();
		
		private $registry;
		private $request;
		private $config;
		private $customer;
		private $db;
		private $model;
		public  $model_tool_image;
		
		private $server;
		private $server_image;
		
		private $built = false;
		private $parse_shortcodes = true;
		private $insert_shortcodes = false;
		
		private $html = null;
		private $content = '';
		private $css = null;
		private $wrapper_tpl = '_main.tpl';
		
		private $language_id;
		private $language_data =  array();
		public  $store_id;
		private $customer_id;
		private $customer_group_id;
		
		private $emailtemplate_id;
		private $emailtemplate_config_id;
		private $emailtemplate_default_id;
		private $emailtemplate_log_id;
		private $emailtemplate_log_enc;
		
		/**
			* @param Request $request
		*/
		public function __construct(Request $request, Registry $registry) {
			$this->registry = $registry;
			$this->request = $request;
			$this->config = $registry->get('config');
			$this->language = $registry->get('language');
			$this->load = $registry->get('load');
			$this->db = $registry->get('db');
			$this->user = $registry->get('user');
			$this->customer = $registry->get('customer');
			
			$this->language_id = $this->config->get("config_language_id");
			
			$this->store_id = $this->config->get("config_store_id");
			if(!$this->store_id) $this->store_id = 0;
			
			if(($this->customer instanceof Customer) && $this->customer->isLogged()){
				$this->customer_id = $this->customer->getId();
				$this->customer_group_id = $this->customer->getCustomerGroupId();
				} else {
				$this->customer_id = 0;
				$this->customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			# Load models
			$this->load->model('tool/image');
			$this->model_tool_image = new ModelToolImage($this->registry);
			
			$this->load->model('module/emailtemplate');
			$this->model = new ModelModuleEmailTemplate($this->registry);
			
			if (isset($request->server['HTTPS']) && (($request->server['HTTPS'] == 'on') || ($request->server['HTTPS'] == '1'))) {
				$this->data['server'] = defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTPS_SERVER;
				} else {
				$this->data['server'] = defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER;
			}
			$this->data['server_admin'] = defined("HTTP_ADMIN") ? HTTP_ADMIN : ($this->data['server'].'admin/');
			$this->data['server_image'] = defined("HTTP_IMAGE") ? HTTP_IMAGE : ($this->data['server'].'image/');
		}
		
		/**
			* Load Template + Config + Set all store data
			*
			* @param $keys
			* @param $data - pass data which overwrites config, used in admin preview system
		*/
		public function load($data){
			if(is_array($data)){
				if(isset($data['language_id']) && $data['language_id'] >= 1){
					$this->language_id = intval($data['language_id']);
				}
				
				if(isset($data['store_id']) && $data['store_id'] >= 0){
					$this->store_id = intval($data['store_id']);
				}
				
				if(isset($data['customer_id']) && $data['customer_id'] >= 0){
					$this->customer_id = intval($data['customer_id']);
				}
				
				if(isset($data['customer_group_id']) && $data['customer_group_id'] >= 1){
					$this->customer_group_id = intval($data['customer_group_id']);
				}
				
				if(isset($data['emailtemplate_config_id']) && $data['emailtemplate_config_id']){
					$this->emailtemplate_config_id = intval($data['emailtemplate_config_id']);
				}
				
				$filter = array();
				
				if(isset($data['emailtemplate_id'])){
					$filter['emailtemplate_id'] = intval($data['emailtemplate_id']);
				}
				
				if(isset($data['emailtemplate_key'])){
					$filter['emailtemplate_key'] = intval($data['emailtemplate_key']);
				}
				
				if(isset($data['order_status_id'])){
					$filter['order_status_id'] = intval($data['order_status_id']);
				}
				
				if(isset($data['key'])){
					if(is_numeric($data['key'])){
						$filter['emailtemplate_id'] = $data['key'];
						} else {
						$filter['emailtemplate_key'] = $data['key'];
					}
				}
				} else {
				if(is_numeric($data)){
					$filter = array('emailtemplate_id' => $data);
					} else {
					$filter = array('emailtemplate_key' => $data);
				}
			}
			
			$templates = $this->model->getTemplates($filter);
			
			if(empty($templates)){
				//EmailTemplate::systemLog('Could not load email template: ' . key($filter) . '=' . current($filter));
				return false;
			}
			
			$keys = array(
			'language_id' => $this->language_id,
			'customer_group_id' => $this->customer_group_id,
			'store_id' => $this->store_id
			);
			
			if(isset($data['order_status_id'])){
				$keys['order_status_id'] = $data['order_status_id'];
			}
			
			foreach ($templates as &$template) {
				$template['power'] = 0;
				
				foreach ($keys as $_key => $_value) {
					$template['power'] = $template['power'] << 1;
					
					if (isset($template[$_key]) && $template[$_key] == $_value) {
						$template['power'] |= 1;
					}
				}
				
				if(!empty($template['emailtemplate_condition'])){
					if(!is_array($template['emailtemplate_condition'])){
						$template['emailtemplate_condition'] = unserialize(base64_decode($template['emailtemplate_condition']));
					}
					if(is_array($template['emailtemplate_condition'])){
						foreach($template['emailtemplate_condition'] as $condition){
							$template['power'] = $template['power'] << 1;
							$key = trim($condition['key']);
							
							if(isset($this->data[$key])){
								switch(html_entity_decode($condition['operator'], ENT_COMPAT, "UTF-8")){
									case '==':
									if($this->data[$key] == $condition['value'])
									$template['power'] |= 1;
									break;
									case '!=':
									if($this->data[$key] != $condition['value'])
									$template['power'] |= 1;
									break;
									case '>':
									if($this->data[$key] > $condition['value'])
									$template['power'] |= 1;
									break;
									case '<':
									if($this->data[$key] < $condition['value'])
									$template['power'] |= 1;
									break;
									case '>=':
									if($this->data[$key] >= $condition['value'])
									$template['power'] |= 1;
									break;
									case '<=':
									if($this->data[$key] <= $condition['value'])
									$template['power'] |= 1;
									break;
									case 'IN':
									$haystack = explode(',', $condition['value']);
									if(is_array($haystack) && in_array($this->data[$key], $haystack))
									$template['power'] |= 1;
									break;
									case 'NOTIN':
									$haystack = explode(',', $condition['value']);
									if(is_array($haystack) && !in_array($this->data[$key], $haystack))
									$template['power'] |= 1;
									break;
								}
							}
						}
					}
				}
			}
			unset($template);
			
			// template with highest power
			$this->data['emailtemplate'] = $templates[0];
			$template_default = array();
			foreach ($templates as $template) {
				if ($template['emailtemplate_default']) {
					$this->emailtemplate_default_id = $template['emailtemplate_id'];
					$template_default = $template;
				}
				
				if ($this->data['emailtemplate']['power'] < $template['power']) {
					$this->data['emailtemplate'] = $template;
				}
			}
			
			// many templates with same top power, select one at random
			$template_ids = array();
			foreach ($templates as $i => $template) {
				if ($template['power'] != 0 && $this->data['emailtemplate']['power'] == $template['power']) {
					$template_ids[] = $i;
				}
			}
			if($template_ids){
				$this->data['emailtemplate'] = $templates[$template_ids[array_rand($template_ids)]];
			}
			
			foreach($this->data['emailtemplate'] as $key => $val){
				if(empty($val) && !empty($template_default[$key])){
					$val = $template_default[$key];
				}
				
				if (strpos($key, 'emailtemplate_') === 0 && substr($key, -3) != '_id') {
					unset($this->data['emailtemplate'][$key]);
					$this->data['emailtemplate'][substr($key, strlen('emailtemplate_'))] = $val;
					} else {
					$this->data['emailtemplate'][$key] = $val;
				}
			}
			
			$this->emailtemplate_id = $this->data['emailtemplate']['emailtemplate_id'];
			
			unset($this->data['emailtemplate']['vqmod']);
			
			if($this->data['emailtemplate']['emailtemplate_config_id']){
				$this->emailtemplate_config_id = $this->data['emailtemplate']['emailtemplate_config_id'];
			}
			
			$description_default = array();
			if($this->emailtemplate_default_id != $this->emailtemplate_id){
				$description_default = $this->model->getTemplateDescription(array(
				'emailtemplate_id' => $this->emailtemplate_default_id,
				'language_id' => $this->language_id
				), 1);
			}
			
			$description = $this->model->getTemplateDescription(array(
			'emailtemplate_id' => $this->emailtemplate_id,
			'language_id' => $this->language_id
			), 1);
			
			if(is_array($description)){
				foreach($description as $key => $val){
					if(isset($this->data['emailtemplate'][$key])) continue;
					
					if(empty($val) && !empty($description_default[$key])){
						$val = $description_default[$key];
					}
					
					if (strpos($key, 'emailtemplate_description_') === 0 && substr($key, -3) != '_id') {
						$this->data['emailtemplate'][substr($key, strlen('emailtemplate_description_'))] = $val;
						} else {
						$this->data['emailtemplate'][$key] = $val;
					}
				}
			}
			
			if($this->emailtemplate_config_id && false){
				$this->data['config'] = $this->model->getConfig($this->data['emailtemplate']['emailtemplate_config_id']);
				} else {
				$configs = $this->model->getConfigs(array('store_id' => $this->store_id));
				
				if($configs){
					if(count($configs) == 1){
						$this->data['config'] = $configs[0];
						} elseif(count($configs) > 1){
						foreach ($configs as &$config) {
							$config['power'] = 0;
							foreach ($keys as $_key => $_value) {
								$config['power'] = $config['power'] << 1;
								if (!empty($config[$_key]) && $config[$_key] == $_value) {
									$config['power'] |= 1;
								}
							}
						}
						unset($config);
						$this->data['config'] = $configs[0];
						
						foreach ($configs as $config) {
							if ($this->data['config']['power'] < $config['power']) {
								$this->data['config'] = $config;
							}
						}
					}
				}
			}
			
			# fail-safe load main config
			if(empty($this->data['config'])){
				$this->data['config'] = $this->model->getConfig(1);
			}
			
			$this->data['config'] = $this->model->formatConfig($this->data['config']);
			
			$this->emailtemplate_config_id = $this->data['config']['emailtemplate_config_id'];
			
			foreach($this->data['config'] as $key => $val){
				if (strpos($key, 'emailtemplate_config_') === 0 && substr($key, -3) != '_id') {
					unset($this->data['config'][$key]);
					$this->data['config'][substr($key, strlen('emailtemplate_config_'))] = $val;
					} else {
					$this->data['config'][$key] = $val;
				}
			}
			
			if($this->data['config']['log'] || $this->data['emailtemplate']['log']){
				$this->emailtemplate_log_id = $this->model->getLastTemplateLogId() + 1;
			}
			
			$config_keys = array('title', 'name', 'url', 'owner', 'address', 'email', 'display_email', 'telephone', 'telephone2', 'fax');
			
			if($this->config->get('config_store_id') == $this->store_id){
				foreach($config_keys as $_key){
					$this->data['store_'.$_key] = $this->config->get('config_'.$_key);
				}
				} else {
				$this->load->model('setting/store');
				$this->load->model('setting/setting');
				
				$this->model_setting_store = new ModelSettingStore($this->registry);
				$this->model_setting_setting = new ModelSettingSetting($this->registry);
				
				$store_info = array_merge(
				$this->model_setting_setting->getSetting("config", $this->store_id),
				$this->model_setting_store->getStore($this->store_id)
				);
				
				foreach($config_keys as $_key){
					if(isset($store_info[$_key])){
						$this->data['store_'.$_key] =  $store_info[$_key];
						} elseif(isset($store_info['config_'.$_key])){
						$this->data['store_'.$_key] =  $store_info['config_'.$_key];
						} else {
						$this->data['store_'.$_key] =  '';
					}
				}
			}
			
			if(!$this->data['store_url']){
				$this->data['store_url'] = $this->data['server'];
			}
			
			$this->data['title'] = $this->data['store_name'];
			
			if($this->data['emailtemplate']['wrapper_tpl'] && file_exists($this->_getTemplatePath($this->data['emailtemplate']['wrapper_tpl']).$this->data['emailtemplate']['wrapper_tpl'])){
				$this->wrapper_tpl = $this->data['emailtemplate']['wrapper_tpl'];
				} elseif($this->data['config']['wrapper_tpl'] && file_exists($this->_getTemplatePath($this->data['config']['wrapper_tpl']).$this->data['config']['wrapper_tpl'])){
				$this->wrapper_tpl = $this->data['config']['wrapper_tpl'];
			}
			
			if($this->data['emailtemplate']['shortcodes'] == 0){
				$this->insert_shortcodes = true;
			}
			
			$this->html = null;
			
			return true;
		}
		
		/**
			* Check if template loaded
			* @return boolean
		*/
		public function isLoaded(){
			return (!empty($this->data['emailtemplate']));
		}
		
		/**
			* Build template
			*
			* @return object
		*/
		public function build(){
			if(!$this->isLoaded()) return false;
			
			$this->built = true;
			
			$this->load->model('localisation/language');
			$this->model_language = new ModelLocalisationLanguage($this->registry);
			$language = $this->model_language->getLanguage($this->language_id);
			
			$oLanguage = new Language($language['directory']);
			
			// In Admin Area
			if(defined('DIR_CATALOG')){
				if(substr($this->data['emailtemplate']['key'], 0, 6) == 'admin.'){
					$dir =  DIR_LANGUAGE;
					} else {
					$dir = DIR_CATALOG . 'language/';
				}
				} else {
				if(substr($this->data['emailtemplate']['key'], 0, 6) == 'admin.'){
					$dir =  defined('HTTP_ADMIN') ? HTTP_ADMIN . 'language/' : substr(DIR_SYSTEM, 0, -7) . 'admin/language/';
					} else {
					$dir = DIR_LANGUAGE;
				}
			}
			$oLanguage->setPath($dir);
			
			$language_files = explode(',', $this->data['emailtemplate']['language_files']);
			$language_files[] = 'mail/emailtemplate';
			
			foreach($language_files as $language_file){
				if($language_file){
					$langData = $oLanguage->load_full(trim($language_file));
					if(!empty($langData)){
						$this->language_data = array_merge($langData, $this->language_data);
					}
				}
			}
			
			if($this->insert_shortcodes){
				$this->model->insertTemplateShortCodes($this->emailtemplate_id, $this->data);
			}
			
			/* $this->data['emailtemplate_id'] = $this->data['emailtemplate']['emailtemplate_id'];
			$this->data['emailtemplate_config_id'] = $this->data['config']['emailtemplate_config_id']; */
			
			#$this->data = array_merge($this->data['config'], $this->data); //compatible with old templates
			
			if(!isset($this->data['store_id'])){
				$this->data['store_id'] = $this->store_id;
			}
			if(!isset($this->data['language_id'])){
				$this->data['language_id'] = $this->language_id;
			}
			if(!isset($this->data['customer_group_id'])){
				$this->data['customer_group_id'] = $this->customer_group_id;
			}
			if(!isset($this->data['customer_id'])){
				$this->data['customer_id'] = $this->customer_id;
			}
			
			$languageData = $oLanguage->load_full($language['filename']);
			if($languageData){
				$this->data = array_merge($languageData, $this->data);
			}
			
			$this->data['config']['theme_dir'] = $this->data['config']['theme'] . '/template/mail/';
			
			foreach(array('top','bottom','left','right') as $var){
				$cells = '';
				if($this->data['config']['shadow_'.$var]['start'] && $this->data['config']['shadow_'.$var]['end'] &&  $this->data['config']['shadow_'.$var]['length'] > 0){
					$gradient = $this->_generateGradientArray($this->data['config']['shadow_'.$var]['start'], $this->data['config']['shadow_'.$var]['end'], $this->data['config']['shadow_'.$var]['length']);
					foreach($gradient as $hex => $width){
						switch($var){
							case 'top':
							case 'bottom':
        					$cells .= "<tr class='emailShadow'><td bgcolor='#{$hex}' style='background:#{$hex}; height:1px; font-size:1px; line-height:0; mso-margin-top-alt:1px' height='1'>&nbsp;</td></tr>\n";
        					break;
							default:
        					$cells .= "<td class='emailShadow' bgcolor='#{$hex}' style='background:#{$hex}; width:{$width}px !important; font-size:1px; line-height:0; mso-margin-top-alt:1px' width='{$width}'>&nbsp;</td>\n";
        					break;
						}
						
						$this->data['config']['shadow_'.$var]['bg'] = $cells;
					}
				}
			}
			
			$this->data['config']['head_text'] = html_entity_decode($this->data['config']['head_text'], ENT_QUOTES, 'UTF-8');
			$this->data['config']['page_footer_text'] = html_entity_decode($this->data['config']['page_footer_text'], ENT_QUOTES, 'UTF-8');
			$this->data['config']['footer_text'] = html_entity_decode($this->data['config']['footer_text'], ENT_QUOTES, 'UTF-8');
			
			if($this->data['config']['showcase_title']){
				$this->data['config']['showcase_title'] = html_entity_decode($this->data['config']['showcase_title'], ENT_QUOTES, 'UTF-8');
				} elseif(isset($this->data['heading_showcase'])) {
				$this->data['config']['showcase_title'] = html_entity_decode($this->data['heading_showcase'], ENT_QUOTES, 'UTF-8');
				} else {
				$this->data['config']['showcase_title'] = '';
			}
			
			$this->data['emailtemplate']['comment'] = html_entity_decode($this->data['emailtemplate']['comment'], ENT_QUOTES, 'UTF-8');
			$this->data['emailtemplate']['unsubscribe_text'] = html_entity_decode($this->data['emailtemplate']['unsubscribe_text'], ENT_QUOTES, 'UTF-8');
			
			for($i=1; $i <= EmailTemplateDescriptionDAO::$content_count; $i++) {
				if(!empty($this->data['emailtemplate']['content'.$i])){
					$this->data['emailtemplate']['content'.$i] = html_entity_decode($this->data['emailtemplate']['content'.$i], ENT_QUOTES, 'UTF-8');
				}
			}
			
			$this->data['config']['header_bg_image'] = ($this->data['config']['header_bg_image']) ? $this->getImageUrl($this->data['config']['header_bg_image']) : '';
			
			$this->data['config']['email_full_width'] = $this->data['config']['email_width'] + ($this->data['config']['shadow_left']['length'] + $this->data['config']['shadow_right']['length']);
			
			$this->data['config']['shadow_top']['left_img'] = ($this->data['config']['shadow_top']['left_img']) ? $this->getImageUrl($this->data['config']['shadow_top']['left_img']) : '';
			$this->data['config']['shadow_top']['left_img_height'] = (int)$this->data['config']['shadow_top']['length'] + (int)$this->data['config']['shadow_top']['overlap'];
			$this->data['config']['shadow_top']['right_img'] = ($this->data['config']['shadow_top']['right_img']) ? $this->getImageUrl($this->data['config']['shadow_top']['right_img']) : '';
			$this->data['config']['shadow_top']['right_img_height'] = (int)$this->data['config']['shadow_top']['length'] + (int)$this->data['config']['shadow_top']['overlap'];
			$this->data['config']['shadow_bottom']['left_img'] = ($this->data['config']['shadow_bottom']['left_img']) ? $this->getImageUrl($this->data['config']['shadow_bottom']['left_img']) : '';
			$this->data['config']['shadow_bottom']['left_img_height'] = (int)$this->data['config']['shadow_bottom']['length'] + (int)$this->data['config']['shadow_bottom']['overlap'];
			$this->data['config']['shadow_bottom']['right_img'] = ($this->data['config']['shadow_bottom']['right_img']) ? $this->getImageUrl($this->data['config']['shadow_bottom']['right_img']) : '';
			$this->data['config']['shadow_bottom']['right_img_height'] = $this->data['config']['shadow_bottom']['length'] + $this->data['config']['shadow_bottom']['overlap'];
			
			$this->data['config']['shadow_top']['left_img_width'] = $this->data['config']['shadow_left']['length'] + $this->data['config']['shadow_left']['overlap'];
			$this->data['config']['shadow_top']['right_img_width'] = $this->data['config']['shadow_right']['length'] + $this->data['config']['shadow_right']['overlap'];
			$this->data['config']['shadow_bottom']['left_img_width'] = $this->data['config']['shadow_left']['length'] + $this->data['config']['shadow_left']['overlap'];
			$this->data['config']['shadow_bottom']['right_img_width'] = $this->data['config']['shadow_right']['length'] + $this->data['config']['shadow_right']['overlap'];
			
			if($this->data['config']['logo']){
				if($this->data['config']['logo_width'] > 0 && $this->data['config']['logo_height'] > 0){
					$this->data['logo'] = $this->model_tool_image->resize($this->data['config']['logo'], $this->data['config']['logo_width'], $this->data['config']['logo_height']);
					} else {
					$this->data['logo'] = $this->getImageUrl($this->data['config']['logo']);
				}
				} else {
				$this->data['logo'] = $this->getImageUrl($this->data['config']['logo']);
			}
			
			$this->data['tracking'] = $this->getTracking();
			
			$this->data['store_url_tracking'] = $this->getTracking($this->data['store_url']);
			
			if($this->data['config']['log'] || $this->data['emailtemplate']['log']){
				$this->emailtemplate_log_enc = substr(md5(uniqid(rand(), true)), 0, 20);
				
				if($this->data['config']['log_read']){
					$this->data['emailtemplate']['tracking_img'] = $this->data['store_url'] . 'index.php?route=module/emailtemplate/record&id='.$this->emailtemplate_log_id . '&enc=' . $this->emailtemplate_log_enc;
				}
			}
			
			if($this->data['emailtemplate']['view_browser']){
				$url = $this->data['store_url'] . 'index.php?route=module/emailtemplate/view&id='.$this->emailtemplate_log_id . '&enc=' . $this->emailtemplate_log_enc;
				$this->data['view_browser'] = str_replace('{$url}', $url, $this->data['config']['view_browser_text']);
			}
			
			if($this->data['config']['showcase'] && $this->data['emailtemplate']['showcase']){
				$filter_data = array();
				
				if(isset($this->data['order_id'])){
					$filter_data['order_id'] = $this->data['order_id'];
				}
				
				if(isset($this->data['customer_group_id'])){
					$filter_data['customer_group_id'] = $this->data['customer_group_id'];
					} elseif (($this->customer instanceof Customer) && $this->customer->isLogged()) {
					$filter_data['customer_group_id'] = $this->customer->getCustomerGroupId();
					} else {
					$filter_data['customer_group_id'] = $this->config->get('config_customer_group_id');
				}
				
				$this->data['showcase_selection'] = $this->model->getShowcase($this->data['config'], $filter_data);
				
				if(!empty($this->data['showcase_selection'])){
					foreach($this->data['showcase_selection'] as &$showcase){
						if($showcase['url']){
							if(defined('HTTP_CATALOG')){
								$showcase['url'] = $this->data['store_url'] . $showcase['url'];
							}
							$showcase['url_tracking'] = $this->getTracking($showcase['url']);
						}
					}
					unset($showcase);
				}
			}
			
			if($this->parse_shortcodes){
				$replace = $this->_fetchShortCodes();
				
				if($this->language_data){
					$this->data = array_merge($this->data, $this->language_data);
				}
				
				foreach($this->data as $key => $val){
					if(is_string($val) && strpos($val, '{$') !== false){
						$this->data[$key] = str_replace($replace['find'], $replace['replace'], $val);
					}
				}
				foreach($this->data['emailtemplate'] as $key => $val){
					if(is_string($val) && strpos($val, '{$') !== false){
						$this->data['emailtemplate'][$key] = str_replace($replace['find'], $replace['replace'], $val);
					}
				}
				foreach($this->data['config'] as $key => $val){
					if(is_string($val) && strpos($val, '{$') !== false){
						$this->data['config'][$key] = str_replace($replace['find'], $replace['replace'], $val);
					}
				}
			}
			
			return $this;
		}
		
		public function justHook(Mail $mail){
			if(!$this->isLoaded()) return $mail;
			$mail->setEmailTemplate($this);
			return $mail;
		}
		
		/**
			* Apply email template settings to Mail object
			*
			* @param object - Mail
			* @return object
		*/
		public function hook(Mail $mail){
			if(!$this->isLoaded()) return $mail;
			
			if(!$this->built){
				$this->build();
			}
			
			$mail->setEmailTemplate($this);
			
			if($this->data['emailtemplate']['subject']){
				$mail->setSubject($this->data['emailtemplate']['subject']);
			}
			
			if(!isset($this->data['subject'])){
				$this->data['subject'] = $mail->getSubject();
			}
			
			if(is_null($this->html)){
				$this->html = $this->fetch();
			}
			if($this->html){
				$mail->setHtml($this->html);
				
				if($this->data['emailtemplate']['plain_text']){
					$mail->setText($this->getPlainText($this->html));
				}
			}
			
			if($this->data['emailtemplate']['mail_to'] != ""){
				$mail->setTo($this->data['emailtemplate']['mail_to']);
			}
			if($this->data['emailtemplate']['mail_bcc'] != ""){
				$mail->setBcc($this->data['emailtemplate']['mail_bcc']);
			}
			if($this->data['emailtemplate']['mail_cc'] != ""){
				$mail->setCc($this->data['emailtemplate']['mail_cc']);
			}
			if($this->data['emailtemplate']['mail_from'] != ""){
				$mail->setFrom($this->data['emailtemplate']['mail_from']);
			}
			if($this->data['emailtemplate']['mail_sender'] != ""){
				$mail->setSender($this->data['emailtemplate']['mail_sender']);
			}
			if($this->data['emailtemplate']['mail_replyto'] != "" && $this->data['emailtemplate']['mail_replyto'] != $this->data['emailtemplate']['mail_to']){
				$mail->setReplyTo($this->data['emailtemplate']['mail_replyto'], $this->data['emailtemplate']['mail_replyto_name']);
			}
			if($this->data['emailtemplate']['mail_attachment']){
				$dir = substr(DIR_SYSTEM, 0, -7); // remove '/system'
				$mail->addAttachment($dir.$this->data['emailtemplate']['mail_attachment']);
			}						
			
			return $mail;
		}
		
		/**
			* @param string $filename - same as 1st parameter in Template::fetch()
			* @param string $content - if $filename is null then the content will be used as the body
			* @returns string
		*/
		public function fetch($filename = null, $content = null, $parseHtml = true) {
			if(!$this->isLoaded()) return false;
			
			if(!$this->built){
				$this->build();
			}
			
			$this->html = '';
			$this->content = '';
			
			if(!is_null($content)){
				$this->content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
				} elseif(!is_null($filename)) {
				$this->content = $this->_fetchTemplate($filename);
				} elseif(isset($this->data['emailtemplate']) && $this->data['emailtemplate']['template']){
				$this->content = $this->_fetchTemplate($this->data['emailtemplate']['template']);
			}
			
			if(!$this->content){
				$this->content = $this->data['emailtemplate']['content1'];
			}
			
			if ($this->wrapper_tpl) {
				$wrapper = $this->_fetchTemplate($this->wrapper_tpl);
				
				$this->html = str_ireplace('{CONTENT}', $this->content, $wrapper);
				
				if($parseHtml){
					$this->html = $this->_parseHtml($this->html, true);
				}
				} else {
				$this->html = $this->content;
			}
			
			if($parseHtml){
				$this->html = $this->_parseHtml($this->html, ($this->wrapper_tpl));
			}
			
			return $this->html;
		}
		
		
		/**
			* Send Email
		*/
		public function send($sendAdditional = false) {
			$mail = new Mail($this->registry);
			$mail = $this->hook($mail);
			$mail->send();
			
			if($sendAdditional){
				$emails = explode(',', $this->config->get('config_alert_emails'));
				foreach ($emails as $email) {
					if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
			}
			
			$this->sent();
		}
		
		/**
			* Perform actions after email has been sent.
		*/
		public function sent() {
			if(isset($this->data['emailtemplate_invoice_pdf']) && file_exists($this->data['emailtemplate_invoice_pdf'])){
				@unlink($this->data['emailtemplate_invoice_pdf']);
			}
		}
		
		public function myLog($data, $marketing = false){
			
			$marketing_id = 0;
			
			if ($marketing){
				$this->db->query("INSERT INTO emailmarketing_logs SET	
				customer_id = '" . (int)$data['customer_id'] .  "',
				actiontemplate_id = '" . (int)$data['template_id'] .  "',
				store_id = '" . (int)$data['store_id'] .  "',
				date_sent  = '" . date('Y-m-d H:i:s') . "',
				mail_to = '" . $this->db->escape($data['mail_to']) .  "',
				mail_to_email = '" . $this->db->escape($data['mail_to_email']) .  "',
				mail_from_email = '" . $this->db->escape($data['mail_from_email']) .  "',
				mail_from = '" . $this->db->escape($data['mail_from']) .  "',
				title = '" . $this->db->escape($data['title']) .  "',
				html = '" . $this->db->escape($data['html']) .  "',				
				transmission_id = '" . (int)$data['transmission_id'] .  "',
				mail_status = 'injection',
				user_id = '" . $this->user->getID() .  "'
				");
				
				$marketing_id = $this->db->getLastId();
			}
			
			$this->db->query("INSERT INTO emailtemplate_logs SET		
			emailtemplate_log_sent = '" . date('Y-m-d H:i:s') . "',
			emailtemplate_log_type = 'MARKETING',
			emailtemplate_log_to = '" . $this->db->escape($data['mail_to_email']) .  "',
			emailtemplate_log_from = '" . $this->db->escape($data['mail_from_email']) .  "',
			emailtemplate_log_sender = '" . $this->db->escape($data['mail_from']) .  "',
			emailtemplate_log_subject = '" . $this->db->escape($data['title']) .  "',
			emailtemplate_log_text = '',
			emailtemplate_log_html = '" . $this->db->escape($data['html']) .  "',
			emailtemplate_log_content = '',
			emailtemplate_id = 'admin.newsletter',
			customer_id = '" . (int)$data['customer_id'] .  "',
			store_id = '" . (int)$data['store_id'] .  "',
			transmission_id = '" . (int)$data['transmission_id'] .  "',
			mail_status = 'injection',
			marketing = '" . $marketing_id .  "'
			");
			
			$email_log_id = $this->db->getLastId();
			
			$this->db->query("INSERT IGNORE INTO customer_history SET 
			customer_id = '" . (int)$data['customer_id'] . "', 				
			date_added = NOW(), 
			email_id = '" . (int)$email_log_id . "',
			comment = 'Отправлен маркетинговый E-MAIL'");
			
			return $this->db->getLastId();
		}
		
		/**
			* Log Mail
		*/
		public function log($data) {
			if(/*$this->data['emailtemplate']['log'] || $this->data['config']['log']*/ true){
				
				$log = array_merge($this->data, $data);
				
				$log['emailtemplate_log_id'] = $this->emailtemplate_log_id;
				$log['emailtemplate_log_content'] = $this->_parseHtml($this->content, false) . '<br /><br />';
				$log['emailtemplate_log_enc'] = $this->emailtemplate_log_enc;
				
				$log['emailtemplate_id'] = $this->data['emailtemplate']['emailtemplate_id'];
				$log['store_id'] = $this->store_id;
				$log['customer_id'] = $this->customer_id;
				
				if (isset($log['order_id']) && $log['order_id']){
					$log['order_id'] = $log['order_id'];
					
					if (!$log['customer_id']){
						$log['customer_id'] = $this->model->getCustomerByOrderId($log['order_id']);									
					}
				}					
				
				return $this->model->insertLog($log);
			}
		}
		
		/**
			* Property
		*/
		public function get($key){
			//if(property_exists($this, $key)){
			if(isset($this->$key) && $key[0] != '_'){
				return $this->$key;
			}
		}
		public function set($key, $val){
			if(isset($this->$key) && $key[0] != '_'){
				$this->$key = $val;
				return true;
			}
			return false;
		}
		
		/**
			* Appends template data
			*
			* [code]
			* $template->addData($my_data_array, 'prefix'); // array(prefix)
			* $template->addData('my_value', $my_value); // string=key,value
			*
			* @return object
		*/
		public function addData($param1, $param2 = ''){
			if(is_array($param1)){
				// $param2 acts as prefix
				if($param2){
					$param2 = rtrim($param2, "_") . "_";
					foreach ($param1 as $key => $value){
						$param1[$param2.$key] = $value;
						unset($param1[$key]);
					}
				}
				$this->data = array_merge($this->data, $param1);
				} elseif(is_string($param1) && $param2 != "") {
				$this->data[$param1] = $param2;
			}
			
			return $this;
		}
		
		/**
			* Appends $this->data with $data
			*
			* @deprecated
		*/
		public function appendData($data) {
			return $this->addData($data);
		}
		
		/**
			* Get Tracking
		*/
		public function getTracking($url = null){
			if(!isset($this->data['config']['tracking'])) return $url;
			
			if(!isset($this->data['tracking'])){
				$tracking = array();
				$tracking['utm_campaign'] = $this->data['config']['tracking_campaign_name'];
				$tracking['utm_medium'] = 'email';
				
				if($this->data['config']['tracking_campaign_term']){
					$tracking['utm_term'] = $this->data['config']['tracking_campaign_term'];
				}
				
				if($this->data['emailtemplate']['tracking_campaign_source']){
					$tracking['utm_source'] = $this->data['emailtemplate']['tracking_campaign_source'];
					} elseif(isset($this->request->get['route'])) {
					$tracking['utm_source'] =  $this->request->get['route'];
				}
				
				$this->data['tracking'] = http_build_query($tracking);
			}
			
			if($url){
				return $url . (strpos($url, '?') === false ? '?' : '&amp;') . $this->data['tracking'];
				} else {
				return $this->data['tracking'];
			}
		}
		
		
		
		public function getPlainText($html = null){
			if(is_null($html)){
				$html = $this->html;
			}
			
			$html = str_replace("\r\n", "\n", $html); // replace \r\n to \n
			$html = str_replace("\r", "\n", $html); // remove \rs
			
			$dom = new DOMDocument('1.0', 'UTF-8');
			if (!$dom->loadHTML($html))
			EmailTemplate::systemLog("Could not load HTML - badly formed?");
			
			$output = $this->_html_to_plain_text($dom->getElementById('emailPage'));
			
			// remove leading and trailing spaces on each line
			$output = preg_replace("/[ \t]*\n[ \t]*/im", "\n", $output);
			
			// remove leading and trailing whitespace
			$output = trim($output);
			
			return $output;
		}
		
		/**
			* Get HTML email template
		*/
		public function getHtml(){
			if($this->html === null){
				$this->fetch();
			}
			return $this->html;
		}
		
		/**
			* Get CSS File
		*/
		public function fetchCss(){
			if($this->css === null){
				$themePath = (defined('DIR_CATALOG') ? DIR_CATALOG : DIR_APPLICATION) . 'view/theme/';
				
				foreach(array(
				$themePath.$this->data['config']['theme'].'/stylesheet/module/email_template.php.css',
				$themePath.'default/stylesheet/email_template.php.css',
				DIR_SYSTEM.'library/emailtemplate/email_template.php.css'
				) as $cssPath){
					if(file_exists($cssPath)) {
						
						extract($this->data);
						
						ob_start();
						
						include($cssPath);
						
						$this->css = ob_get_contents();
						
						if (ob_get_length()) ob_end_clean();
						
						//preg_replace('#/\*.*?\*/#s', '', $css);
						//preg_replace('/\s*([{}|:;,])\s+/', '$1', $css);
						//preg_replace('/\s\s+(.*)/', '$1', $css);
						//str_replace(';}', '}', $css);
						
						break;
					}
				}
			}
			
			return $this->css;
		}
		
		/**
			* Image Absolute URL, no resize
		*/
		public function getImageUrl($filename){
			if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
				return;
			}
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				if($this->config->get('config_ssl')){
					$url = $this->config->get('config_ssl') . 'image/';
					} else {
					$url = defined("HTTPS_IMAGE") ? HTTPS_IMAGE : (defined("HTTPS_CATALOG") ? HTTPS_CATALOG : HTTPS_SERVER) . 'image/';
				}
				} else {
				if($this->config->get('config_url')){
					$url = $this->config->get('config_url') . 'image/';
					} else {
					$url = defined("HTTP_IMAGE") ? HTTP_IMAGE : (defined("HTTP_CATALOG") ? HTTP_CATALOG : HTTP_SERVER) . 'image/';
				}
			}
			
			return $url . $filename;
		}
		
		/**
			* Get Template Path
		*/
		protected function _getTemplatePath($file){
			if(defined('DIR_CATALOG')){
				if(false && file_exists(DIR_TEMPLATE.'mail/'.$file)){
					$path = DIR_TEMPLATE.'mail/';
					} elseif(file_exists(DIR_CATALOG.'view/theme/'.$this->data['config']['theme'].'/template/mail/'.$file)) {
					$path = DIR_CATALOG.'view/theme/'.$this->data['config']['theme'].'/template/mail/';
					} else {
					$path = DIR_CATALOG.'view/theme/default/template/mail/';
				}
				} else {
				if(file_exists(DIR_TEMPLATE.$this->data['config']['theme'].'/template/mail/'.$file)) {
					$path = DIR_TEMPLATE.$this->data['config']['theme'].'/template/mail/';
					} else {
					$path = DIR_TEMPLATE.'default/template/mail/';
				}
			}
			
			return $path;
		}
		
		/**
			* Get Email Wrapper Filename
			*
			* @param string - filename
			* @return object - EmailTemplate
		*/
		private function _fetchTemplate($file){
			if(!$file) return false;
			
			$path = $this->_getTemplatePath($file);
			
			$template_file = $path.$file;
			
			if (file_exists($template_file) && is_file($template_file)) {
				extract($this->data);
				
				ob_start();
				
				include($template_file);
				
				$content = ob_get_contents();
				
				if (ob_get_length()) ob_end_clean();
				
				return $content;
				} else {
				EmailTemplate::systemLog('Error: Could not load template: ' .$file . ', in: '. $path);
				exit();
			}
		}
		
		/**
			* Generate array of hex values for shadow
			* @param $from - HEX colour from
			* @param $until - HEX colour from
			* @param $length - distance of shadow
			* @return Array(hex=>width)
		*/
		private function _generateGradientArray($from, $until, $length){
			$from = ltrim($from,'#');
			$until = ltrim($until,'#');
			$from = array(hexdec(substr($from,0,2)),hexdec(substr($from,2,2)),hexdec(substr($from,4,2)));
			$until = array(hexdec(substr($until,0,2)),hexdec(substr($until,2,2)),hexdec(substr($until,4,2)));
			
			if($length > 1){
				$red=($until[0]-$from[0])/($length-1);
				$green=($until[1]-$from[1])/($length-1);
				$blue=($until[2]-$from[2])/($length-1);
				$return = array();
				
				for($i=0;$i<$length;$i++){
					$newred=dechex($from[0]+round($i*$red));
					if(strlen($newred)<2) $newred="0".$newred;
					
					$newgreen=dechex($from[1]+round($i*$green));
					if(strlen($newgreen)<2) $newgreen="0".$newgreen;
					
					$newblue=dechex($from[2]+round($i*$blue));
					if(strlen($newblue)<2) $newblue="0".$newblue;
					
					$hex = $newred.$newgreen.$newblue;
					if(isset($return[$hex])){
						$return[$hex] ++;
						} else {
						$return[$hex] = 1;
					}
				}
				return $return;
				} else {
				$red=($until[0]-$from[0]);
				$green=($until[1]-$from[1]);
				$blue=($until[2]-$from[2]);
				
				$newred=dechex($from[0]+round($red));
				if(strlen($newred)<2) $newred="0".$newred;
				
				$newgreen=dechex($from[1]+round($green));
				if(strlen($newgreen)<2) $newgreen="0".$newgreen;
				
				$newblue=dechex($from[2]+round($blue));
				if(strlen($newblue)<2) $newblue="0".$newblue;
				
				return array($newred.$newgreen.$newblue => $length);
			}
			
		}
		
		/**
			* Method parses add inline CSS styles
			*
			* @param string $html
			* @param bool - does the HTML have a valid doc type?
			* @return string $html
		*/
		private function _parseHtml($html, $hasDocType = true){
			if($this->isLoaded()){
				$replace = $this->_fetchShortCodes();
				$html = str_replace($replace['find'], $replace['replace'], $html);
			}
			
			if($hasDocType === false){
				$html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Email Template</title></head><body id="emailTemplate">' . $html . '</body></html>';
			}
			
			$this->fetchCss();
			if($this->css && $html){
				$cssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
				$html = $cssToInlineStyles->convert($html, $this->css);
				//$html = str_ireplace('{CSS}', $this->css, $html);
			}
			
			if($hasDocType === false){
				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->loadHTML($html);
				
				$oWrapper = $dom->getElementsByTagName('body')->item(0);
				$html = '';
				foreach($oWrapper->childNodes as $node) {
					$html .= $dom->saveXml($node);
				}
			}
			
			return $html;
		}
		
		/**
			*  GET SHORTCODES
			*
			*  Get all empty shortcodes from database & merge with data
		*/
		private function _fetchShortCodes(){
			$find = array();
			$replace = array();
			$data = array();
			
			$shortcodes = $this->model->getTemplateShortcodes(array(
			'emailtemplate_id' => $this->emailtemplate_id
			));
			foreach($shortcodes as $row){
				$data[$row['emailtemplate_shortcode_code']] = '';
			}
			
			$data = array_merge($data, $this->data);
			
			foreach($data as $key => $var){
				if(is_array($var)){
					foreach($var as $key2 => $var2){
						if(is_string($var2) || is_int($var2)){
							$find[] = '{$'.$key.'.'.$key2.'}';
							$replace[] = $var2;
						}
					}
					} elseif(is_string($var) || is_int($var)){
					$find[] = '{$'.$key.'}';
					$replace[] = $var;
				}
			}
			
			return array('find' => $find, 'replace' => $replace);
		}
		
		/**
			* Format Address
			*
			* @param Array data eg array(firstname=>'', shipping_firstname=>'', payment_firstname=>'')
			* @param String prefix of address: '' or 'shipping' or 'payment'
			* @param String address formatting e.g '{firstname}...'
		*/
		public static function formatAddress($address, $address_prefix = '', $format = null){
			$find = array();
			$replace = array();
			if($address_prefix != ""){
				$address_prefix = trim($address_prefix, '_') . '_';
			}
			if (is_null($format) || $format == '') {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			$vars = array(
	 		'firstname',
	 		'lastname',
	 		'company',
	 		'address_1',
	 		'address_2',
	 		'city',
	 		'postcode',
	 		'zone',
	 		'zone_code',
	 		'country'
			);
			foreach($vars as $var){
				$find[$var] = '{'.$var.'}';
				if($address_prefix && isset($address[$address_prefix.$var])){
					$replace[$var] =  $address[$address_prefix.$var];
					} elseif(isset($address[$var])){
					$replace[$var] =  $address[$var];
					} else {
					$replace[$var] =  '';
				}
			}
			return str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		}
		
		public static function truncate_str($str, $length = 100, $breakWords = true, $append = '...') {
			$str = strip_tags(html_entity_decode($str, ENT_QUOTES, 'UTF-8'));
			
			$strLength = utf8_strlen($str);
			if ($strLength <= $length) {
				return $str;
			}
			
			if (!$breakWords) {
				while ($length < $strLength AND preg_match('/^\pL$/', mb_substr($str, $length, 1))) {
					$length++;
				}
			}
			
			$str = utf8_substr($str, 0, $length) . $append;
			$str = preg_replace('/\s{3,}/',' ', $str);
			$str = trim($str);
			
			return $str;
		}
		
		public static function systemLog($txt, $file = 'emailtemplate.log'){
			$filePath = DIR_LOGS . $file;
			
			if(file_exists($filePath)){
				$fh = fopen($filePath, 'a');
				} else {
				$fh = fopen($filePath, 'w');
			}
			
			$time = @date('Y-m-d H:i:s');
			
			fwrite($fh, "{$time} - {$txt}\r\n");
			
			return fclose($fh);
		}
		
		// Works on both HTML, and XHTML
		// Sent in from developer @gob33 of 'Package Tracking Service'.
		public static function isHTML($str) {
			$html = array('A','ABBR','ACRONYM','ADDRESS','APPLET','AREA','B','BASE','BASEFONT','BDO','BIG','BLOCKQUOTE',
			'BODY','BR','BUTTON','CAPTION','CENTER','CITE','CODE','COL','COLGROUP','DD','DEL','DFN','DIR','DIV','DL',
			'DT','EM','FIELDSET','FONT','FORM','FRAME','FRAMESET','H1','H2','H3','H4','H5','H6','HEAD','HR','HTML',
			'I','IFRAME','IMG','INPUT','INS','ISINDEX','KBD','LABEL','LEGEND','LI','LINK','MAP','MENU','META',
			'NOFRAMES','NOSCRIPT','OBJECT','OL','OPTGROUP','OPTION','P','PARAM','PRE','Q','S','SAMP','SCRIPT',
			'SELECT','SMALL','SPAN','STRIKE','STRONG','STYLE','SUB','SUP','TABLE','TBODY','TD','TEXTAREA','TFOOT',
			'TH','THEAD','TITLE','TR','TT','U','UL','VAR');
			
			return preg_match("~(&lt;\/?)\b(".implode('|', $html).")\b([^>]*&gt;)~i", $str, $c);
		}
		
		private function _html_to_plain_text($node) {
			if ($node instanceof DOMText) {
				return preg_replace("/\\s+/im", " ", $node->wholeText);
			}
			if ($node instanceof DOMDocumentType) {
				// ignore
				return "";
			}
			
			// Next
			$nextNode = $node->nextSibling;
			while ($nextNode != null) {
				if ($nextNode instanceof DOMElement) {
					break;
				}
				$nextNode = $nextNode->nextSibling;
			}
			$nextName = null;
			if ($nextNode instanceof DOMElement && $nextNode != null) {
				$nextName = strtolower($nextNode->nodeName);
			}
			
			// Previous
			$nextNode = $node->previousSibling;
			while ($nextNode != null) {
				if ($nextNode instanceof DOMElement) {
					break;
				}
				$nextNode = $nextNode->previousSibling;
			}
			$prevName = null;
			if ($nextNode instanceof DOMElement && $nextNode != null) {
				$prevName = strtolower($nextNode->nodeName);
			}
			
			$name = strtolower($node->nodeName);
			
			// start whitespace
			switch ($name) {
				case "hr":
				return "------\n";
				
				case "style":
				case "head":
				case "title":
				case "meta":
				case "script":
				// ignore these tags
				return "";
				
				case "h1":
				case "h2":
				case "h3":
				case "h4":
				case "h5":
				case "h6":
				// add two newlines
				$output = "\n";
				break;
				
				case "p":
				case "div":
				// add one line
				$output = "\n";
				break;
				
				default:
				// print out contents of unknown tags
				$output = "";
				break;
			}
			
			// debug $output .= "[$name,$nextName]";
			
			if($node->childNodes){
				for ($i = 0; $i < $node->childNodes->length; $i++) {
					$n = $node->childNodes->item($i);
					
					$text = $this->_html_to_plain_text($n);
					
					$output .= $text;
				}
			}
			
			// end whitespace
			switch ($name) {
				case "style":
				case "head":
				case "title":
				case "meta":
				case "script":
				// ignore these tags
				return "";
				
				case "h1":
				case "h2":
				case "h3":
				case "h4":
				case "h5":
				case "h6":
				$output .= "\n";
				break;
				
				case "p":
				case "br":
				// add one line
				if ($nextName != "div")
				$output .= "\n";
				break;
				
				case "div":
				// add one line only if the next child isn't a div
				if (($nextName != "div" && $nextName != null) || ($node->hasAttribute('class') && strstr($node->getAttribute('class'), 'emailtemplateSpacing')))
				$output .= "\n";
				break;
				
				case "a":
				// links are returned in [text](link) format
				$href = $node->getAttribute("href");
				if ($href == null) {
					// it doesn't link anywhere
					if ($node->getAttribute("name") != null) {
						$output = "$output";
					}
					} else {
					if ($href == $output || ($node->hasAttribute('class') && strstr($node->getAttribute('class'), 'emailtemplateNoDisplay'))) {
						// link to the same address: just use link
						$output;
						} else {
						// No display
						$output = $href . "\n" . $output;
					}
				}
				
				// does the next node require additional whitespace?
				switch ($nextName) {
					case "h1": case "h2": case "h3": case "h4": case "h5": case "h6":
					$output .= "\n";
					break;
				}
				
				default:
				// do nothing
			}
			
			return $output;
		}
	}
	
	
	/**
		* Data Access Object - Abstract
	*/
	abstract class EmailTemplateAbstract
	{
		/**
			* Data Types
		*/
		const INT = "INT";
		const TEXT = "TEXT";
		const SERIALIZE = "SERIALIZE";
		const FLOAT = "FLOAT";
		const DATE_NOW = "DATE_NOW";
		
		/**
			* Filter from array, by unsetting element(s)
			* @param string/array $filter - match array key
			* @param array to be filtered
			* @return array
		*/
		protected static function filterArray($filter, $array){
			if($filter === null) return $array;
			
			if(is_array($filter)){
				foreach($filter as $f){
					unset($array[$f]);
				}
				} else {
				unset($array[$filter]);
			}
			
			return $array;
		}
		
	}
	
	/**
		* Email Templates `emailtemplate`
	*/
	class EmailTemplateDAO extends EmailTemplateAbstract
	{
		/**
			* Columns & Data Types.
			* @see EmailTemplateDAOAbstract::describe()
		*/
		public static function describe(){
			$filter = func_get_args();
			$cols = array(
			'emailtemplate_id' => EmailTemplateAbstract::INT,
			'emailtemplate_key' => EmailTemplateAbstract::TEXT,
			'emailtemplate_label' => EmailTemplateAbstract::TEXT,
			'emailtemplate_template' => EmailTemplateAbstract::TEXT,
			'emailtemplate_modified' => EmailTemplateAbstract::DATE_NOW,
			'emailtemplate_log' => EmailTemplateAbstract::INT,
			'emailtemplate_view_browser' => EmailTemplateAbstract::INT,
			'emailtemplate_view_browser_theme' => EmailTemplateAbstract::INT,
			'emailtemplate_mail_attachment' => EmailTemplateAbstract::TEXT,
			'emailtemplate_mail_to' => EmailTemplateAbstract::TEXT,
			'emailtemplate_mail_cc' => EmailTemplateAbstract::TEXT,
			'emailtemplate_mail_bcc' => EmailTemplateAbstract::TEXT,
			'emailtemplate_mail_from' => EmailTemplateAbstract::TEXT,
			'emailtemplate_mail_sender' => EmailTemplateAbstract::TEXT,
			'emailtemplate_mail_replyto' => EmailTemplateAbstract::TEXT,
			'emailtemplate_mail_replyto_name' => EmailTemplateAbstract::TEXT,
			'emailtemplate_attach_invoice' => EmailTemplateAbstract::INT,
			'emailtemplate_language_files' => EmailTemplateAbstract::TEXT,
			'emailtemplate_wrapper_tpl' => EmailTemplateAbstract::TEXT,
			'emailtemplate_tracking_campaign_source' => EmailTemplateAbstract::TEXT,
			'emailtemplate_default' => EmailTemplateAbstract::INT,
			'emailtemplate_status' => EmailTemplateAbstract::TEXT,
			'emailtemplate_shortcodes' => EmailTemplateAbstract::INT,
			'emailtemplate_showcase' => EmailTemplateAbstract::INT,
			'emailtemplate_plain_text' => EmailTemplateAbstract::INT,
			'emailtemplate_integrate_extension' => EmailTemplateAbstract::INT,
			'emailtemplate_condition' => EmailTemplateAbstract::SERIALIZE,
			'emailtemplate_config_id' => EmailTemplateAbstract::INT,
			'store_id' => EmailTemplateAbstract::INT,
			'customer_id' => EmailTemplateAbstract::INT,
			'customer_group_id' => EmailTemplateAbstract::INT,
			'order_status_id' => EmailTemplateAbstract::INT
			);
			
			return (!$filter)? $cols : self::filterArray($filter, $cols);
		}
	}
	
	/**
		* Email Templates `emailtemplate_description`
	*/
	class EmailTemplateDescriptionDAO extends EmailTemplateAbstract
	{
		public static $content_count = 3;
		
		/**
			* Columns & Data Types.
			* @see EmailTemplateDAOAbstract::describe()
		*/
		public static function describe(){
			$filter = func_get_args();
			$cols = array(
			'emailtemplate_id' => EmailTemplateAbstract::INT,
			'language_id' => EmailTemplateAbstract::INT,
			'emailtemplate_description_subject' => EmailTemplateAbstract::TEXT,
			'emailtemplate_description_preview' => EmailTemplateAbstract::TEXT,
			'emailtemplate_description_content1' => EmailTemplateAbstract::TEXT,
			'emailtemplate_description_content2' => EmailTemplateAbstract::TEXT,
			'emailtemplate_description_content3' => EmailTemplateAbstract::TEXT,
			'emailtemplate_description_comment' => EmailTemplateAbstract::TEXT,
			'emailtemplate_description_unsubscribe_text' => EmailTemplateAbstract::TEXT
			);
			
			return (!$filter)? $cols : self::filterArray($filter, $cols);
		}
	}
	
	/**
		* Email Templates `emailtemplate_shortcode`
	*/
	class EmailTemplateShortCodesDAO extends EmailTemplateAbstract
	{
		/**
			* Columns & Data Types.
			* @see EmailTemplateDAOAbstract::describe()
		*/
		public static function describe(){
			$filter = func_get_args();
			$cols = array(
			'emailtemplate_shortcode_id' => EmailTemplateAbstract::INT,
			'emailtemplate_shortcode_code' => EmailTemplateAbstract::TEXT,
			'emailtemplate_shortcode_type' => EmailTemplateAbstract::TEXT,
			'emailtemplate_shortcode_example' => EmailTemplateAbstract::TEXT,
			'emailtemplate_id' => EmailTemplateAbstract::INT
			);
			
			return (!$filter)? $cols : self::filterArray($filter, $cols);
		}
	}
	
	/**
		* Email Templates `emailtemplate_logs`
	*/
	class EmailTemplateLogsDAO extends EmailTemplateAbstract
	{
		/**
			* Columns & Data Types.
			* @see EmailTemplateDAOAbstract::describe()
		*/
		public static function describe(){
			$filter = func_get_args();
			$cols = array(
			'emailtemplate_log_id' => EmailTemplateAbstract::INT,
			'emailtemplate_log_sent' => EmailTemplateAbstract::DATE_NOW,
			'emailtemplate_log_read' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_read_last' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_type' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_to' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_from' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_sender' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_text' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_html' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_content' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_subject' => EmailTemplateAbstract::TEXT,
			'emailtemplate_log_enc' => EmailTemplateAbstract::TEXT,
			'emailtemplate_id' => EmailTemplateAbstract::INT,
			'order_id' => EmailTemplateAbstract::INT,
			'customer_id' => EmailTemplateAbstract::INT,
			'store_id' => EmailTemplateAbstract::INT,
			'transmission_id' => EmailTemplateAbstract::TEXT
			);
			
			return (!$filter)? $cols : self::filterArray($filter, $cols);
		}
	}
	
	/**
		* Config `emailtemplate_config`
	*/
	class EmailTemplateConfigDAO extends EmailTemplateAbstract
	{
		/**
			* Columns & Data Types.
			* @see EmailTemplateAbstract::describe()
		*/
		public static function describe(){
			$filter = func_get_args();
			$cols = array(
			'emailtemplate_config_id' => EmailTemplateAbstract::INT,
			'emailtemplate_config_name' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_email_width' => EmailTemplateAbstract::INT,
			'emailtemplate_config_email_responsive' => EmailTemplateAbstract::INT,
			'emailtemplate_config_wrapper_tpl' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_page_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_body_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_body_font_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_body_link_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_body_heading_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_body_section_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_page_footer_text' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_footer_text' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_footer_align' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_footer_valign' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_footer_font_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_footer_height' => EmailTemplateAbstract::INT,
			'emailtemplate_config_footer_section_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_header_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_header_bg_image' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_header_height' => EmailTemplateAbstract::INT,
			'emailtemplate_config_header_border_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_header_section_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_head_text' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_head_section_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_view_browser_text' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_invoice_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_invoice_download' => EmailTemplateAbstract::INT,
			'emailtemplate_config_invoice_header' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_invoice_footer' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_logo' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_logo_align' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_logo_font_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_logo_font_size' => EmailTemplateAbstract::INT,
			'emailtemplate_config_logo_height' => EmailTemplateAbstract::INT,
			'emailtemplate_config_logo_valign' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_logo_width' => EmailTemplateAbstract::INT,
			'emailtemplate_config_shadow_top' => EmailTemplateAbstract::SERIALIZE,
			'emailtemplate_config_shadow_left' => EmailTemplateAbstract::SERIALIZE,
			'emailtemplate_config_shadow_right' => EmailTemplateAbstract::SERIALIZE,
			'emailtemplate_config_shadow_bottom' => EmailTemplateAbstract::SERIALIZE,
			'emailtemplate_config_showcase' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_showcase_limit' => EmailTemplateAbstract::INT,
			'emailtemplate_config_showcase_selection' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_showcase_title' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_showcase_related' => EmailTemplateAbstract::INT,
			'emailtemplate_config_showcase_section_bg_color' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_text_align' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_page_align' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_tracking' => EmailTemplateAbstract::INT,
			'emailtemplate_config_tracking_campaign_name' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_tracking_campaign_term' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_theme' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_table_quantity' => EmailTemplateAbstract::INT,
			'emailtemplate_config_customer_register_validate_email' => EmailTemplateAbstract::INT,
			'emailtemplate_config_style' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_log' => EmailTemplateAbstract::INT,
			'emailtemplate_config_log_read' => EmailTemplateAbstract::INT,
			'emailtemplate_config_status' => EmailTemplateAbstract::TEXT,
			'emailtemplate_config_version' => EmailTemplateAbstract::TEXT,
			'customer_group_id' => EmailTemplateAbstract::INT,
			'language_id' => EmailTemplateAbstract::INT,
			'store_id' => EmailTemplateAbstract::INT
			);
			
			return (!$filter)? $cols : self::filterArray($filter, $cols);
		}
	}