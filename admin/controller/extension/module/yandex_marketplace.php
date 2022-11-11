<?php

require_once DIR_SYSTEM . 'library/yandex_beru/yandex_beru.php';

class ControllerExtensionModuleYandexMarketplace extends Controller {
	private $error = array();
	private $api;
	
	public function index() {
		$this->api = new yandex_beru();
		
		$this->load->language('extension/module/yandex_marketplace');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/yandex_beru.css');
		
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		$this->load->model('localisation/tax_class');
		$this->load->model('setting/store');
		$this->load->model('extension/module/yandex_beru');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('yandex_beru', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/yandex_marketplace', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/yandex_marketplace', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		$data['token'] = $this->session->data['user_token'];
		
		//Tab generals
		
		$data['api_yandex_fbs_link'] = HTTPS_CATALOG.'yandex_market';
		$data['api_yandex_dbs_link'] = HTTPS_CATALOG.'yandex_market_dbs';
		
		if (isset($this->request->post['yandex_beru_title'])) {
			$data['yandex_beru_title'] = $this->request->post['yandex_beru_title'];
		} else {
			$data['yandex_beru_title'] = $this->config->get('yandex_beru_title');
		}
		
		if (isset($this->request->post['yandex_beru_tax_class_id'])) {
			$data['yandex_beru_tax_class_id'] = $this->request->post['yandex_beru_tax_class_id'];
		} else {
			$data['yandex_beru_tax_class_id'] = $this->config->get('yandex_beru_tax_class_id');
		}
		
		if (isset($this->request->post['yandex_beru_store'])) {
			$data['yandex_beru_store'] = $this->request->post['yandex_beru_store'];
		} elseif($this->config->get('yandex_beru_store')) {
			$data['yandex_beru_store'] = $this->config->get('yandex_beru_store');
		} else {
			$data['yandex_beru_store'] = array();
		}
		
		if (isset($this->request->post['yandex_beru_sort_order'])) {
			$data['yandex_beru_sort_order'] = $this->request->post['yandex_beru_sort_order'];
		} else {
			$data['yandex_beru_sort_order'] = $this->config->get('yandex_beru_sort_order');
		}
		
		if (isset($this->request->post['yandex_beru_status'])) {
			$data['yandex_beru_status'] = $this->request->post['yandex_beru_status'];
		} else {
			$data['yandex_beru_status'] = $this->config->get('yandex_beru_status');
		}
		
		if (isset($this->request->post['yandex_beru_status_DBS'])) {
			$data['yandex_beru_status_DBS'] = $this->request->post['yandex_beru_status_DBS'];
		} else {
			$data['yandex_beru_status_DBS'] = $this->config->get('yandex_beru_status_DBS');
		}
		
		if (isset($this->request->post['yandex_beru_auth_token'])) {
			$data['yandex_beru_auth_token'] = $this->request->post['yandex_beru_auth_token'];
		} else {
			$data['yandex_beru_auth_token'] = $this->config->get('yandex_beru_auth_token');
		}
		
		if (isset($this->request->post['yandex_beru_auth_token_DBS'])) {
			$data['yandex_beru_auth_token_DBS'] = $this->request->post['yandex_beru_auth_token_DBS'];
		} else {
			$data['yandex_beru_auth_token_DBS'] = $this->config->get('yandex_beru_auth_token_DBS');
		}
		
		if (isset($this->request->post['yandex_beru_company_id'])) {
			$data['yandex_beru_company_id'] = $this->request->post['yandex_beru_company_id'];
		} else {
			$data['yandex_beru_company_id'] = $this->config->get('yandex_beru_company_id');
		}
		
		if (isset($this->request->post['yandex_beru_company_id_DBS'])) {
			$data['yandex_beru_company_id_DBS'] = $this->request->post['yandex_beru_company_id_DBS'];
		} else {
			$data['yandex_beru_company_id_DBS'] = $this->config->get('yandex_beru_company_id_DBS');
		}
		
		if (isset($this->request->post['yandex_beru_oauth_DBS'])) {
			$data['yandex_beru_oauth_DBS'] = $this->request->post['yandex_beru_oauth_DBS'];
		} else {
			$data['yandex_beru_oauth_DBS'] = $this->config->get('yandex_beru_oauth_DBS');
		}
		
		if (isset($this->request->post['yandex_beru_oauth'])) {
			$data['yandex_beru_oauth'] = $this->request->post['yandex_beru_oauth'];
		} else {
			$data['yandex_beru_oauth'] = $this->config->get('yandex_beru_oauth');
		}
		
		if (isset($this->request->post['yandex_beru_weight_kg'])) {
			$data['yandex_beru_weight_kg'] = $this->request->post['yandex_beru_weight_kg'];
		} else {
			$data['yandex_beru_weight_kg'] = $this->config->get('yandex_beru_weight_kg');
		}
		
		if (isset($this->request->post['yandex_beru_length_cm'])) {
			$data['yandex_beru_length_cm'] = $this->request->post['yandex_beru_length_cm'];
		} else {
			$data['yandex_beru_length_cm'] = $this->config->get('yandex_beru_length_cm');
		}
		
		if (!empty($this->request->post['yandex_beru_active_tab'])) {
			$data['active_tab'] = $this->request->post['yandex_beru_active_tab'];
		} else {
			$data['active_tab'] = 'general';
		}
		
		$order_statuses = $this->getInfo()->getOfferStatuses();
		
		$data['statuses'] = array();
		
		foreach($order_statuses as $beru_status){
			
			$data['statuses'][$beru_status]['name'] = $this->language->get('order_status_'. $beru_status);
			
			if(!empty($this->config->get('yandex_beru_statuses')) && isset($this->config->get('yandex_beru_statuses')[$beru_status])){
				$data['statuses'][$beru_status]['val'] = $this->config->get('yandex_beru_statuses')[$beru_status];
			}else{
				$data['statuses'][$beru_status]['val'] = false;
			}	
		}

		$order_statuses_dbs = $this->getInfo()->getOfferStatusesDbs();

		foreach($order_statuses_dbs as $beru_status_dbs){
			
			$data['statuses_dbs'][$beru_status_dbs]['name'] = $this->language->get('order_status_'. $beru_status_dbs);
			
			if(!empty($this->config->get('yandex_beru_statuses_dbs')) && isset($this->config->get('yandex_beru_statuses_dbs')[$beru_status_dbs])){
				$data['statuses_dbs'][$beru_status_dbs]['val'] = $this->config->get('yandex_beru_statuses_dbs')[$beru_status_dbs];
			}else{
				$data['statuses_dbs'][$beru_status_dbs]['val'] = false;
			}	
		}

		$this->load->model('localisation/order_status');
		$data['opencart_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => 0,
			'name'	   => $this->language->get('text_store_default')
		);
		
		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());
		
		
		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		
		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		
//	Сопоставление полей для генерации прайслистов / загрузки товаров на беру
//	https://yandex.ru/support/partnermarket/offers.html
		
		$required_fields = $this->getInfo()->getRequiredOfferFields();

		if (isset($this->request->post['yandex_beru_fieldsets'])) {
			$fieldsets = $this->request->post['yandex_beru_fieldsets'];
		} else {
			$fieldsets = $this->config->get('yandex_beru_fieldsets');
		}
		
		$data['sources'] = array();
		
		foreach($this->getInfo()->getSources() as $source_key){
			$data['sources'][] = [
				'key'	=> $source_key,
				'name'	=> $this->language->get('text_source_'. $source_key)
			];
		}
		
		$data['required_fields'] = [];
		
		foreach($required_fields as $required_field_key => $required_field){
			
			if(!empty($fieldsets) && !empty($fieldsets[$required_field_key])){
				$source = $fieldsets[$required_field_key]['source'];
				$field = $fieldsets[$required_field_key]['field'];
				unset($fieldsets[$required_field_key]);
			}else{
				$source = 'general';
				$field = '';
			}
			
			$fields_arr = $this->model_extension_module_yandex_beru->getSourceFields(['source' => $source]);
			
			$fields = array();
			
			foreach($fields_arr as $fields_item){
				$fields[] = [
					'key'		=> $fields_item['key'],
					'name'		=> $fields_item['name'],
					'selected'	=> ($field == $fields_item['key'])?true:false
				];
			}
			
			$data['required_fields'][] = [
				'key'	=> $required_field_key,
				'name'	=> $this->language->get('text_field_name_'. $required_field_key),
				'info'	=> $this->language->get('text_field_info_'. $required_field_key),
				'field'	=> $fields,
				'source'=> $source
			];
			
		}
		$additional_fields = $this->getInfo()->getAdditionalOfferFields();
		
		$data['additional_fields'] = array();
		
		foreach($additional_fields as $additional_field_key => $additional_field){
			$data['additional_fields'][$additional_field_key] = [
				'name'	=> $this->language->get('text_field_name_'. $additional_field_key),
				'info'	=> $this->language->get('text_field_info_'. $additional_field_key),
				'childs'=> $additional_field['childs']
			];
		}
		
		$data['additional_field_rows'] = array();
		
		if(!empty($fieldsets)){
			foreach($fieldsets as $field_key => $added_field){
				if(array_key_exists($field_key, $data['additional_fields'])){
					$childs_fields_data = array();

					if(!empty($data['additional_fields'][$field_key]['childs'])){
						$child_field_row_arr = $this->getInfo()->getFieldRowArr($field_key);

						foreach($child_field_row_arr as $child_field_item){
							$child_fields_arr = $this->model_extension_module_yandex_beru->getSourceFields(['source' => $added_field[$child_field_item]['source']]);

							$child_fields = array();

							foreach($child_fields_arr as $fields_item){
								$child_fields[] = [
									'key'		=> $fields_item['key'],
									'name'		=> $fields_item['name'],
									'selected'	=> ($added_field[$child_field_item]['field'] == $fields_item['key'])?true:false
								];
							}
							
							$childs_fields_data[] = [
								'key'	=> $child_field_item,
								'name'	=> $this->language->get('text_field_name_'. $field_key.'_'.$child_field_item),
								'info'	=> $this->language->get('text_field_info_'. $field_key.'_'.$child_field_item),
								'field'	=> $child_fields,
								'source'=> $added_field[$child_field_item]['source']
							];		
						}

					}else{
						$child_fields_arr = $this->model_extension_module_yandex_beru->getSourceFields(['source' => $added_field['source']]);

						$child_fields = array();

						foreach($child_fields_arr as $fields_item){
							$child_fields[] = [
								'key'		=> $fields_item['key'],
								'name'		=> $this->language->get('text_field_name_'. $fields_item['key']),
								'selected'	=> ($added_field['field'] == $fields_item['key'])?true:false
							];
						}
					}
					
					$data['additional_field_rows'][] = [
						'key' 		=> $field_key,
						'name'		=> $this->language->get('text_field_name_'. $field_key),
						'info' 		=> $this->language->get('text_field_info_'. $field_key),
						'field'		=> !empty($child_fields)?$child_fields:"",
						'source' 	=> !empty($added_field['source'])?$added_field['source']:"",
						'childs' 	=> $childs_fields_data
					];	
				}	
			}
		}


		$data['paymentMethods'] = array(
			'YANDEX'			=>	'банковской картой при оформлении',
			'APPLE_PAY'			=>	'Apple Pay при оформлении',
			'GOOGLE_PAY'		=>	'Google Pay при оформлении',
			'CARD_ON_DELIVERY'	=>	'банковской картой при получении',
			'CASH_ON_DELIVERY'	=>	'наличными при получении',
		);


		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/yandex_marketplace', $data));
	}
	
	public function getSourceFieldRow(){
		$this->load->language('extension/module/yandex_marketplace');
		
		$this->api = new yandex_beru();
		
		$this->load->model('extension/module/yandex_beru');
		
		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			
			$row_key = !empty($this->request->get['row'])?$this->request->get['row']:"";
			
			$rows = $this->getInfo()->getFieldRowArr($row_key);
			$data['rows'] = array();
		
			foreach($rows as $row){
				$data['rows'][$row] = [
					'name'	=> $this->language->get('text_field_name_'.$row_key),
					'info'	=> $this->language->get('text_field_info_'.$row_key)
				];
			}	
			
			
			$data['row_data'] = $this->getInfo()->getFieldRow($row_key);
			$data['row_data']['name'] = $this->language->get('text_field_name_'.$row_key);
			$data['row_data']['info'] = $this->language->get('text_field_name_'.$row_key);
			
			$data['row_key'] = $row_key;
			
			$data['sources'] = array();
		
			foreach($this->getInfo()->getSources() as $source_key){
				$data['sources'][] = [
					'key'	=> $source_key,
					'name'	=> $this->language->get('text_source_'. $source_key)
				];
			}
			
			$data['fields'] = $this->model_extension_module_yandex_beru->getSourceFields(['source' => 'general']);
			$this->response->setOutput($this->load->view('extension/module/yandex_marketplace/field_row_data', $data));
		}
	
	}
	public function getInfo() {

		static $instance;

		if (!$instance) {
			$instance = $this->api->loadComponent('info');
		}

		return $instance;
	}
	
	public function getSourceFields(){
		
		$this->load->model('extension/module/yandex_beru');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$json['success']['fields'] = $this->model_extension_module_yandex_beru->getSourceFields($this->request->post);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateKey() {
		if (!$this->user->hasPermission('modify', 'extension/module/yandex_marketplace')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('extension/module/yandex_beru');
		
		if(!$this->model_extension_module_yandex_beru->validateKeys($this->request->post)){
			$this->error['warning'] = $this->language->get('error_key');	
		}
		
		return !$this->error;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/yandex_marketplace')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
//		if(!empty($this->request->post['yandex_beru_statuses'])){
//			foreach($this->request->post['yandex_beru_statuses'] as $yandex_beru_status){
//				if($yandex_beru_status == 0){
//					$this->error['warning'] = $this->language->get('error_empty_order_status');
//					break;
//				}
//			}
//		}


		if($this->request->post['yandex_beru_status'] == "1"){

			if(empty($this->request->post['yandex_beru_length_cm'])){
				$this->error['warning'] = $this->language->get('error_empty_length_cm');
			}
			
			if(empty($this->request->post['yandex_beru_weight_kg'])){
				$this->error['warning'] = $this->language->get('error_empty_weight_kg');
			}
			
			if(!empty($this->request->post['yandex_beru_fieldsets'])){
				foreach($this->request->post['yandex_beru_fieldsets'] as $yandex_beru_fieldset){
					if(isset($yandex_beru_fieldset['field'])){
						if(empty($yandex_beru_fieldset['field'])){
							$this->error['warning'] = $this->language->get('error_empty_fields');
							break;
						}
					}else{
						foreach($yandex_beru_fieldset as $yandex_beru_field_group_item){
							if(isset($yandex_beru_field_group_item['field'])){
								if(empty($yandex_beru_field_group_item['field'])){
									$this->error['warning'] = $this->language->get('error_empty_fields');
									break 2;
								}
							}	
						}
					}
				}
			}

		}

		return !$this->error;
	}
	
	public function install() {

		$check_table_1 = $this->db->query("SHOW TABLES FROM " . DB_DATABASE . " LIKE '" . DB_PREFIX . "yb_history_price'");

		if(empty($check_table_1->rows)){

			$this->db->query("CREATE TABLE " . DB_PREFIX . "yb_history_price( ".
			"offer_id varchar(255) NOT NULL, ".
			"offer_name varchar(255) NOT NULL, ".
			"user int(11) NOT NULL, ".
			"price float NOT NULL, ".
			"date_update datetime NOT NULL)"
			);
			
		}
		

		
		$check_table_2 = $this->db->query("SHOW TABLES FROM " . DB_DATABASE . " LIKE '" . DB_PREFIX . "yb_offers'");


		if(empty($check_table_2->rows)){

			$this->db->query("CREATE TABLE " . DB_PREFIX . "yb_offers( ".
			"`key` varchar(100) NOT NULL, ".
			"shopSku varchar(255) NOT NULL, ".
			"yandex_sku varchar(255) NOT NULL DEFAULT '', ".
			"yandex_category varchar(255) NOT NULL DEFAULT '', ".
			"status varchar(255) NOT NULL DEFAULT '', ".
			"marketSkuName varchar(255) NOT NULL DEFAULT '', ".
			"marketCategoryName varchar(255) NOT NULL DEFAULT '', ".
			"offer_price float NOT NULL DEFAULT '0', ".
			"minPriceOnBeru float NOT NULL DEFAULT '0', ".
			"maxPriceOnBeru float NOT NULL DEFAULT '0', ".
			"defaultPriceOnBeru float NOT NULL DEFAULT '0', ".
			"byboxPriceOnBeru float NOT NULL DEFAULT '0', ".
			"outlierPrice float NOT NULL DEFAULT '0', " . 
			"PRIMARY KEY (`key`)) "
			);
			
		}


		$check_table_3 = $this->db->query("SHOW TABLES FROM " . DB_DATABASE . " LIKE '" . DB_PREFIX . "yb_product_group'");

		if(empty($check_table_3->rows)){

			$this->db->query("CREATE TABLE " . DB_PREFIX . "yb_product_group( ".
				"group_id int(11) NOT NULL AUTO_INCREMENT, ".
				"name varchar(255) NOT NULL, ".
				"filter_name text NULL DEFAULT NULL, ".
				"filter_model text NULL DEFAULT NULL, ".
				"filter_category text NULL DEFAULT NULL, ".
				"filter_product text NULL DEFAULT NULL, ".
				"filter_option text NULL DEFAULT NULL, ".
				"filter_price_from float NULL DEFAULT NULL, ".
				"filter_price_to float NULL DEFAULT NULL, ".
				"filter_quantity_from int(11) NULL DEFAULT NULL, ".
				"filter_quantity_to int(11) NULL DEFAULT NULL, ".
				"filter_status tinyint(1) NULL DEFAULT NULL, " . 
				"PRIMARY KEY (`group_id`)) "
			);
		


		}


		$check_table_4 = $this->db->query("SHOW TABLES FROM " . DB_DATABASE . " LIKE '" . DB_PREFIX . "yb_product_to_product_group'");

		if(empty($check_table_4->rows)){

			$this->db->query("CREATE TABLE " . DB_PREFIX . "yb_product_to_product_group( ".
				"product_id int(11) NOT NULL , ".
				"group_id int(11) NOT NULL, ".
				"PRIMARY KEY (`product_id`, `group_id`))"
				
			);
		
		}

		$check_table_5 = $this->db->query("SHOW TABLES FROM " . DB_DATABASE . " LIKE '" . DB_PREFIX . "yb_order_boxes'");

		if(empty($check_table_5->rows)){

			$this->db->query("CREATE TABLE " . DB_PREFIX . "yb_order_boxes( ".
				"box_id int(11) NOT NULL , ".
				"order_id int(11) NOT NULL , ".
				"weight int(64) NOT NULL , ".
				"width int(64) NOT NULL , ".
				"height int(64) NOT NULL , ".
				"depth int(64) NOT NULL , ".
				"market_box_id int(11) NOT NULL , ".
				"fulfilmentId varchar(128) NOT NULL , ".
				"group_id int(11) NOT NULL, ".
				"PRIMARY KEY (`box_id`))"
		
			);
		
		}
		
		$check_table_6 = $this->db->query("SHOW TABLES FROM " . DB_DATABASE . " LIKE '" . DB_PREFIX . "_yb_regions'");

		if(empty($check_table_6->rows)){

			$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "yb_regions(
				  `region_id` int(11) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `type` varchar(255) NOT NULL,
				  `parent` int(11) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
			);
			
			$this->db->query("INSERT INTO `oc_yb_regions` (`region_id`, `name`, `type`, `parent`) VALUES
				(11004, 'Республика Адыгея', 'REPUBLIC', 26),
				(26, 'Южный федеральный округ', 'COUNTRY_DISTRICT', 225),
				(225, 'Россия', 'COUNTRY', 0),
				(11111, 'Республика Башкортостан', 'REPUBLIC', 40),
				(40, 'Приволжский федеральный округ', 'COUNTRY_DISTRICT', 225),
				(11330, 'Республика Бурятия', 'REPUBLIC', 73),
				(73, 'Дальневосточный федеральный округ', 'COUNTRY_DISTRICT', 225),
				(10231, 'Республика Алтай', 'REPUBLIC', 59),
				(59, 'Сибирский федеральный округ', 'COUNTRY_DISTRICT', 225),
				(11010, 'Республика Дагестан', 'REPUBLIC', 102444),
				(102444, 'Северо-Кавказский федеральный округ', 'COUNTRY_DISTRICT', 225),
				(11012, 'Республика Ингушетия', 'REPUBLIC', 102444),
				(11013, 'Кабардино-Балкарская Республика', 'REPUBLIC', 102444),
				(11015, 'Республика Калмыкия', 'REPUBLIC', 26),
				(11020, 'Карачаево-Черкесская Республика', 'REPUBLIC', 102444),
				(10933, 'Республика Карелия', 'REPUBLIC', 17),
				(17, 'Северо-Западный федеральный округ', 'COUNTRY_DISTRICT', 225),
				(10939, 'Республика Коми', 'REPUBLIC', 17),
				(11077, 'Республика Марий Эл', 'REPUBLIC', 40),
				(11117, 'Республика Мордовия', 'REPUBLIC', 40),
				(11443, 'Республика Саха (Якутия)', 'REPUBLIC', 73),
				(11021, 'Республика Северная Осетия — Алания', 'REPUBLIC', 102444),
				(10233, 'Республика Тыва', 'REPUBLIC', 59),
				(11148, 'Удмуртская Республика', 'REPUBLIC', 40),
				(11340, 'Республика Хакасия', 'REPUBLIC', 59),
				(11024, 'Чеченская Республика', 'REPUBLIC', 102444),
				(11235, 'Алтайский край', 'REPUBLIC', 59),
				(10995, 'Краснодарский край', 'REPUBLIC', 26),
				(11309, 'Красноярский край', 'REPUBLIC', 59),
				(11409, 'Приморский край', 'REPUBLIC', 73),
				(11069, 'Ставропольский край', 'REPUBLIC', 102444),
				(11457, 'Хабаровский край', 'REPUBLIC', 73),
				(11375, 'Амурская область', 'REPUBLIC', 73),
				(10842, 'Архангельская область', 'REPUBLIC', 17),
				(10946, 'Астраханская область', 'REPUBLIC', 26),
				(10645, 'Белгородская область', 'REPUBLIC', 3),
				(3, 'Центральный федеральный округ', 'COUNTRY_DISTRICT', 225),
				(10650, 'Брянская область', 'REPUBLIC', 3),
				(10658, 'Владимирская область', 'REPUBLIC', 3),
				(10950, 'Волгоградская область', 'REPUBLIC', 26),
				(10853, 'Вологодская область', 'REPUBLIC', 17),
				(10672, 'Воронежская область', 'REPUBLIC', 3),
				(10687, 'Ивановская область', 'REPUBLIC', 3),
				(11266, 'Иркутская область', 'REPUBLIC', 59),
				(10857, 'Калининградская область', 'REPUBLIC', 17),
				(10693, 'Калужская область', 'REPUBLIC', 3),
				(11398, 'Камчатский край', 'REPUBLIC', 73),
				(11070, 'Кировская область', 'REPUBLIC', 40),
				(10699, 'Костромская область', 'REPUBLIC', 3),
				(11158, 'Курганская область', 'REPUBLIC', 52),
				(52, 'Уральский федеральный округ', 'COUNTRY_DISTRICT', 225),
				(10705, 'Курская область', 'REPUBLIC', 3),
				(10712, 'Липецкая область', 'REPUBLIC', 3),
				(11403, 'Магаданская область', 'REPUBLIC', 73),
				(10897, 'Мурманская область', 'REPUBLIC', 17),
				(11079, 'Нижегородская область', 'REPUBLIC', 40),
				(10904, 'Новгородская область', 'REPUBLIC', 17),
				(11316, 'Новосибирская область', 'REPUBLIC', 59),
				(11318, 'Омская область', 'REPUBLIC', 59),
				(11084, 'Оренбургская область', 'REPUBLIC', 40),
				(10772, 'Орловская область', 'REPUBLIC', 3),
				(11095, 'Пензенская область', 'REPUBLIC', 40),
				(11108, 'Пермский край', 'REPUBLIC', 40),
				(10926, 'Псковская область', 'REPUBLIC', 17),
				(11029, 'Ростовская область', 'REPUBLIC', 26),
				(10776, 'Рязанская область', 'REPUBLIC', 3),
				(11131, 'Самарская область', 'REPUBLIC', 40),
				(11146, 'Саратовская область', 'REPUBLIC', 40),
				(11450, 'Сахалинская область', 'REPUBLIC', 73),
				(11162, 'Свердловская область', 'REPUBLIC', 52),
				(10795, 'Смоленская область', 'REPUBLIC', 3),
				(10802, 'Тамбовская область', 'REPUBLIC', 3),
				(10819, 'Тверская область', 'REPUBLIC', 3),
				(11353, 'Томская область', 'REPUBLIC', 59),
				(10832, 'Тульская область', 'REPUBLIC', 3),
				(11176, 'Тюменская область', 'REPUBLIC', 52),
				(11153, 'Ульяновская область', 'REPUBLIC', 40),
				(11225, 'Челябинская область', 'REPUBLIC', 52),
				(21949, 'Забайкальский край', 'REPUBLIC', 73),
				(10841, 'Ярославская область', 'REPUBLIC', 3),
				(213, 'Москва', 'CITY', 1),
				(1, 'Москва и Московская область', 'REPUBLIC', 3),
				(2, 'Санкт-Петербург', 'CITY', 10174),
				(10174, 'Санкт-Петербург и Ленинградская область', 'REPUBLIC', 17),
				(10243, 'Еврейская автономная область', 'REPUBLIC', 73),
				(10176, 'Ненецкий автономный округ', 'REPUBLIC', 17),
				(11193, 'Ханты-Мансийский автономный округ - Югра', 'REPUBLIC', 52),
				(10251, 'Чукотский автономный округ', 'REPUBLIC', 73),
				(11232, 'Ямало-Ненецкий автономный округ', 'REPUBLIC', 52),
				(977, 'Республика Крым', 'REPUBLIC', 26),
				(959, 'Севастополь', 'CITY', 977);
			");
		
		}


		$check_order_shipment_id = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME='shipment_id'");

		if(empty($check_order_shipment_id->rows)){


			$this->db->query("ALTER TABLE " . DB_PREFIX . "order ADD COLUMN shipment_id int(11) NOT NULL DEFAULT '0'"
				
			);

		}
		
		$check_order_shipment_scheme = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME='shipment_id'");

		if(empty($check_order_shipment_scheme->rows)){

			$this->db->query("ALTER TABLE " . DB_PREFIX . "order ADD COLUMN shipment_scheme varchar(10) NOT NULL DEFAULT '' ");

		}

		$check_order_market_order_id = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME='market_order_id'");

		if(empty($check_order_market_order_id->rows)){

			$this->db->query("ALTER TABLE " . DB_PREFIX . "order ADD COLUMN market_order_id int(11) NOT NULL DEFAULT '0'");

		}

		$check_order_shipment_date = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME='shipment_date'");
		

		if(empty($check_order_shipment_date->rows)){

			$this->db->query("ALTER TABLE " . DB_PREFIX . "order ADD COLUMN shipment_date date NULL DEFAULT NULL");

		}

		

    }


}