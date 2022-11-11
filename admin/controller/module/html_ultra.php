<?php
class ControllerModuleHtmlUltra extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/html_ultra');

		//подключаем свои стили для вкладок и скрипты
		$this->document->addScript('view/javascript/bootstrap/jquery.js');
		$this->document->addScript('http://code.jquery.com/jquery-migrate-1.0.0.js');
		$this->document->addScript('view/javascript/jquery/superfish/js/superfish.js');
		$this->document->addScript('view/javascript/bootstrap/bootstrap.min.js');
		$this->document->addScript('view/javascript/bootstrap/moment-with-locales.min.js');
		$this->document->addScript('view/javascript/bootstrap/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('view/stylesheet/bootstrap/bootstrap.css');  
		$this->document->addStyle('view/stylesheet/bootstrap/bootstrap-datetimepicker.min.css');
		$this->document->addStyle('view/stylesheet/html_ultra.css');
		
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('module/html_ultra');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('html_ultra', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
			
			//применить
			if(isset($this->request->post['apply']) and $this->request->post['apply']) {
				$this->redirect($this->url->link('module/html_ultra', 'token=' . $this->session->data['token'], 'SSL'));				
			}else{
				$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
			}			
            
			
		}
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		 
		//переменные модуля
		$this->data['text_osnov'] = $this->language->get('text_osnov');
		$this->data['text_decor'] = $this->language->get('text_decor');
		$this->data['text_dop_setting'] = $this->language->get('text_dop_setting');

		$this->data['text_edit'] = $this->language->get('text_edit');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');

		$this->data['text_help_dop'] = $this->language->get('text_help_dop');
		$this->data['test_php_on'] = $this->language->get('test_php_on');
		$this->data['php_status_help'] = $this->language->get('php_status_help');
		$this->data['on'] = $this->language->get('on');
		$this->data['off'] = $this->language->get('off');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['entry_date_from_to'] = $this->language->get('entry_date_from_to');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_grup_сustomers'] = $this->language->get('entry_grup_сustomers');
		$this->data['entry_сustomer'] = $this->language->get('entry_сustomer');
		$this->data['entry_authorization'] = $this->language->get('entry_authorization');
		$this->data['authorization_all'] = $this->language->get('authorization_all');
		$this->data['authorization_on'] = $this->language->get('authorization_on');
		$this->data['authorization_off'] = $this->language->get('authorization_off');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_language'] = $this->language->get('entry_language');
		$this->data['entry_paragraph_deleted'] = $this->language->get('entry_paragraph_deleted');
		$this->data['entry_paragraph_enable'] = $this->language->get('entry_paragraph_enable');
		$this->data['entry_information'] = $this->language->get('entry_information');
		$this->data['entry_time_from_to'] = $this->language->get('entry_time_from_to'); 
		
		$this->data['entry_days_week'] = $this->language->get('entry_days_week'); 
		$this->data['entry_days_week_monday'] = $this->language->get('entry_days_week_monday'); 
		$this->data['entry_days_week_tuesday'] = $this->language->get('entry_days_week_tuesday'); 
		$this->data['entry_days_week_medium'] = $this->language->get('entry_days_week_medium'); 
		$this->data['entry_days_week_thursday'] = $this->language->get('entry_days_week_thursday'); 
		$this->data['entry_days_week_friday'] = $this->language->get('entry_days_week_friday'); 
		$this->data['entry_days_week_saturday'] = $this->language->get('entry_days_week_saturday'); 
		$this->data['entry_days_week_sunday'] = $this->language->get('entry_days_week_sunday'); 
		$this->data['entry_time_now'] = $this->language->get('entry_time_now'); 
		
		$this->data['text_enabled_editor'] = $this->language->get('text_enabled_editor');
		$this->data['text_disable_editor'] = $this->language->get('text_disable_editor');
		
		$this->data['entry_produkt_consider'] = $this->language->get('entry_produkt_consider');
		
		//Оформление
		$this->data['entry_decor_status'] = $this->language->get('entry_decor_status');
		$this->data['text_placeholder_decor'] = $this->language->get('text_placeholder_decor');
		$this->data['entry_css'] = $this->language->get('entry_css');
		$this->data['text_placeholder_css'] = $this->language->get('text_placeholder_css');
		$this->data['help_titel_decor'] = $this->language->get('help_titel_decor');
		$this->data['help_text_decor'] = $this->language->get('help_text_decor');
		$this->data['help_titel_shortcodes'] = $this->language->get('help_titel_shortcodes');
		$this->data['help_text_shortcodes'] = $this->language->get('help_text_shortcodes');
		
		  
		//Справка 
		$this->data['help_titel_main'] = $this->language->get('help_titel_main');
		$this->data['help_text_main'] = $this->language->get('help_text_main');
		//связь с разработчиком
		$this->data['help_titel_developer'] = $this->language->get('help_titel_developer');
		$this->data['help_text_developer'] = $this->language->get('help_text_developer');

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['entry_html_ultra'] = $this->language->get('entry_html_ultra');
		$this->data['text_required'] 	= $this->language->get('text_required');
		
		$this->data['token_sesiya'] 	= $this->session->data['token'];
		
		//вывод сообщений об ошибке если нужно
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/html_ultra', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/html_ultra', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		
		if (isset($this->request->post['html_ultra_module'])) {
			$this->data['modules'] = $this->request->post['html_ultra_module'];
		} elseif ($this->config->get('html_ultra_module')) { 
			$this->data['modules'] = $this->config->get('html_ultra_module');
		}	

		//получаем настройки модулей
		$module_info = $this->model_module_html_ultra->getModule("html_ultra");
			
//---------- Доп настройки --------------//		  

		//Избавляемся от ошибки пустого модуля
		if (!empty($module_info)) {
			$this->data['module_info'] = $module_info;
		} else {
			$this->data['module_info'] = '';
		}	
		
		//Функция сортировки 
		function cmpS($a, $b) {return strnatcmp($a["name"], $b["name"]);}

		//выводим список магазинов	
		$this->data['stores'] = $this->model_module_html_ultra->getStores();
		
		//Производитель
		$manufacturer_info_sort = $this->model_module_html_ultra->getManufacturer();
		usort($manufacturer_info_sort, "cmpS");
		$this->data['manufacturer_info'] = $manufacturer_info_sort;
		  
		$this->load->model('catalog/category');
		
		//Категории
		$categories_info_sort = $this->model_module_html_ultra->getCategory();
		usort($categories_info_sort, "cmpS");			
		$this->data['categories_info'] = $categories_info_sort;
		  
		//Группы клиентов
		$grup_clients_info_sort = $this->model_module_html_ultra->getGroupCustomer();
		usort($grup_clients_info_sort, "cmpS");
		$this->data['grup_clients_info'] = $grup_clients_info_sort;
		  
		//Клиенты
		//Функция сортировки 
		function cmp_сustomer($a, $b) {return strnatcmp($a["firstname"], $b["firstname"]);}
		
	//	$сustomer_info_sort = $this->model_module_html_ultra->getCustomer();
		usort($сustomer_info_sort, "cmp_сustomer");
		$this->data['сustomer_info'] = $сustomer_info_sort;
		  
		//Товары
	//	$product_info_sort = $this->model_module_html_ultra->getProduct();
		usort($product_info_sort, "cmpS");
		$this->data['product_info'] = $product_info_sort;
		
		//Страницы
		//Функция сортировки 
		function cmp_information($a, $b) {return strnatcmp($a["title"], $b["title"]);}
		
		$information_sort = $this->model_module_html_ultra->getInformation();
		usort($information_sort, "cmp_information");
		
		$this->data['information_sort'] = $information_sort;   
		  
		//Языки
		$this->data['language_info'] = $this->model_module_html_ultra->getLanguage();
		$this->data['language_id'] = $this->config->get('config_language_id'); 		
		
		$modul_setting = array();
  			foreach($module_info as $moidule_key => $module_value){
				if (strpos($module_value['key'], 'html_setting') !== false){
					$setting_key = explode("html_setting_", $module_value['key']);					
					$modul_setting[$setting_key[1]] = array(
						'key' => $module_value['key'],
						'value' => unserialize($module_value['value'])
					);
				}
			} 
		ksort($modul_setting);
		
		$this->data['setting'] = $modul_setting; 			
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();						
						
						
						
		$this->template = 'module/html_ultra.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/html_ultra')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>