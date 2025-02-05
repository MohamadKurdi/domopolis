<?php
class ControllerFeedHotlineYml extends Controller {
	private function isOldVersion() {
		$v = explode('.', VERSION);
		return $v[2] < 3;
	}

	private $error = array();

	public function index() {
		$this->load->language('feed/hotline_yml');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate($this->request->post))) {
			if (isset($this->request->post['hotline_yml_categories'])) {
				$this->request->post['hotline_yml_categories'] = implode(',', $this->request->post['hotline_yml_categories']);
			}
			if (isset($this->request->post['hotline_yml_manufacturers'])) {
				$this->request->post['hotline_yml_manufacturers'] = implode(',', $this->request->post['hotline_yml_manufacturers']);
			}
			if (isset($this->request->post['hotline_yml_categ_mapping'])) {
				$this->request->post['hotline_yml_categ_mapping'] = serialize($this->request->post['hotline_yml_categ_mapping']);
			}
			if (isset($this->request->post['hotline_yml_blacklist'])) {
				$this->request->post['hotline_yml_blacklist'] = implode(',', $this->request->post['hotline_yml_blacklist']);
			}
			if (isset($this->request->post['hotline_yml_pricefrom'])) {
				$this->request->post['hotline_yml_pricefrom'] = floatval($this->request->post['hotline_yml_pricefrom']);
			}
			if (isset($this->request->post['hotline_yml_attributes'])) {
				$this->request->post['hotline_yml_attributes'] = implode(',', $this->request->post['hotline_yml_attributes']);
			}
			if (isset($this->request->post['hotline_yml_color_options'])) {
				$this->request->post['hotline_yml_color_options'] = implode(',', $this->request->post['hotline_yml_color_options']);
			}
			if (isset($this->request->post['hotline_yml_size_options'])) {
				$this->request->post['hotline_yml_size_options'] = implode(',', $this->request->post['hotline_yml_size_options']);
			}
			if (isset($this->request->post['hotline_yml_size_units'])) {
				$this->request->post['hotline_yml_size_units'] = serialize($this->request->post['hotline_yml_size_units']);
			}

			$this->model_setting_setting->editSetting('hotline_yml', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('feed/hotline_yml', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_categories'] = $this->language->get('tab_categories');
		$this->data['tab_attributes'] = $this->language->get('tab_attributes');
		$this->data['tab_tailor'] = $this->language->get('tab_tailor');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_blacklist'] = $this->language->get('text_blacklist');
		$this->data['text_whitelist'] = $this->language->get('text_whitelist');

		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_data_feed'] = $this->language->get('entry_data_feed');
		//$this->data['entry_shopname'] = $this->language->get('entry_shopname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_datamodel'] = $this->language->get('entry_datamodel');
		$this->data['datamodels'] = $this->language->get('datamodels');
		$this->data['entry_delivery_cost'] = $this->language->get('entry_delivery_cost');
		
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_manufacturers'] = $this->language->get('entry_manufacturers');
		$this->data['entry_blacklist_type'] = $this->language->get('entry_blacklist_type');
		$this->data['entry_blacklist'] = $this->language->get('entry_blacklist');
		$this->data['entry_whitelist'] = $this->language->get('entry_whitelist');
		$this->data['entry_pricefrom'] = $this->language->get('entry_pricefrom');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_unavailable'] = $this->language->get('entry_unavailable');
		$this->data['entry_in_stock'] = $this->language->get('entry_in_stock');
		$this->data['entry_out_of_stock'] = $this->language->get('entry_out_of_stock');

		$this->data['entry_pickup'] = $this->language->get('entry_pickup');
		$this->data['entry_sales_notes'] = $this->language->get('entry_sales_notes');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_numpictures'] = $this->language->get('entry_numpictures');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_cron_run'] = $this->language->get('entry_cron_run');
		$this->data['cron_path'] = 'php '.realpath(DIR_CATALOG.'../export/hotline_yml.php');
		$this->data['entry_export_url'] = $this->language->get('entry_export_url');
		$this->data['export_url'] = HTTP_CATALOG.'export/hotline_yml.xml';

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('text_feed'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('feed/hotline_yml', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('feed/hotline_yml', 'token=' . $this->session->data['token']);

		$this->data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token']);

		if (isset($this->request->post['hotline_yml_status'])) {
			$this->data['hotline_yml_status'] = $this->request->post['hotline_yml_status'];
		} else {
			$this->data['hotline_yml_status'] = $this->config->get('hotline_yml_status');
		}

		$this->data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/hotline_yml';

		/*
		if (isset($this->request->post['hotline_yml_shopname'])) {
			$this->data['hotline_yml_shopname'] = $this->request->post['hotline_yml_shopname'];
		} else {
			$this->data['hotline_yml_shopname'] = $this->config->get('hotline_yml_shopname');
		}

		if (isset($this->request->post['hotline_yml_company'])) {
			$this->data['hotline_yml_company'] = $this->request->post['hotline_yml_company'];
		} else {
			$this->data['hotline_yml_company'] = $this->config->get('hotline_yml_company');
		}
		*/

		if (isset($this->request->post['hotline_yml_datamodel'])) {
			$this->data['hotline_yml_datamodel'] = $this->request->post['hotline_yml_datamodel'];
		} else {
			$this->data['hotline_yml_datamodel'] = $this->config->get('hotline_yml_datamodel');
		}
		
		if (isset($this->request->post['hotline_yml_delivery_cost'])) {
			$this->data['hotline_yml_delivery_cost'] = $this->request->post['hotline_yml_delivery_cost'];
		} else {
			$this->data['hotline_yml_delivery_cost'] = $this->config->get('hotline_yml_delivery_cost');
		}

		if (isset($this->request->post['hotline_yml_pricefrom'])) {
			$this->data['pricefrom'] = $this->request->post['hotline_yml_pricefrom'];
		} else {
			$this->data['pricefrom'] = $this->config->get('hotline_yml_pricefrom');
		}

		if (isset($this->request->post['hotline_yml_currency'])) {
			$this->data['hotline_yml_currency'] = $this->request->post['hotline_yml_currency'];
		} else {
			$this->data['hotline_yml_currency'] = $this->config->get('hotline_yml_currency');
		}

		if (isset($this->request->post['hotline_yml_blacklist_type'])) {
			$this->data['blacklist_type'] = $this->request->post['hotline_yml_blacklist_type'];
		} else {
			$this->data['blacklist_type'] = $this->config->get('hotline_yml_blacklist_type');
		}

		if (isset($this->request->post['hotline_yml_blacklist'])) {
			$blacklist = $this->request->post['hotline_yml_blacklist'];
		} else {
			$blacklist = explode(',', $this->config->get('hotline_yml_blacklist'));
		}
		$this->load->model('catalog/product');
		
		$this->data['blacklist'] = array();
		
		foreach ($blacklist as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$this->data['blacklist'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}

		if (isset($this->request->post['hotline_yml_unavailable'])) {
			$this->data['hotline_yml_unavailable'] = $this->request->post['hotline_yml_unavailable'];
		} elseif ($this->config->get('hotline_yml_unavailable')) {
			$this->data['hotline_yml_unavailable'] = $this->config->get('hotline_yml_unavailable');
		} else {
			$this->data['hotline_yml_unavailable'] = '';
		}

		if (isset($this->request->post['hotline_yml_in_stock'])) {
			$this->data['hotline_yml_in_stock'] = $this->request->post['hotline_yml_in_stock'];
		} elseif ($this->config->get('hotline_yml_in_stock')) {
			$this->data['hotline_yml_in_stock'] = $this->config->get('hotline_yml_in_stock');
		} else {
			$this->data['hotline_yml_in_stock'] = 7;
		}

		if (isset($this->request->post['hotline_yml_out_of_stock'])) {
			$this->data['hotline_yml_out_of_stock'] = $this->request->post['hotline_yml_out_of_stock'];
		} elseif ($this->config->get('hotline_yml_in_stock')) {
			$this->data['hotline_yml_out_of_stock'] = $this->config->get('hotline_yml_out_of_stock');
		} else {
			$this->data['hotline_yml_out_of_stock'] = 5;
		}

		if (isset($this->request->post['hotline_yml_pickup'])) {
			$this->data['hotline_yml_pickup'] = $this->request->post['hotline_yml_pickup'];
		} else {
			$this->data['hotline_yml_pickup'] = $this->config->get('hotline_yml_pickup');
		}

		if (isset($this->request->post['hotline_yml_sales_notes'])) {
			$this->data['hotline_yml_sales_notes'] = $this->request->post['hotline_yml_sales_notes'];
		} else {
			$this->data['hotline_yml_sales_notes'] = $this->config->get('hotline_yml_sales_notes');
		}

		if (isset($this->request->post['hotline_yml_store'])) {
			$this->data['hotline_yml_store'] = $this->request->post['hotline_yml_store'];
		} else {
			$this->data['hotline_yml_store'] = $this->config->get('hotline_yml_store');
		}
		
		if (isset($this->request->post['hotline_yml_numpictures'])) {
			$this->data['hotline_yml_numpictures'] = $this->request->post['hotline_yml_numpictures'];
		} else {
			$this->data['hotline_yml_numpictures'] = $this->config->get('hotline_yml_numpictures');
		}

		//++++ Для вкладки аттрибутов ++++
		$this->data['tab_attributes_description'] = str_replace('%attr_url%', $this->url->link('catalog/attribute', 'token=' . $this->session->data['token']), $this->language->get('tab_attributes_description'));
		$this->data['entry_attributes'] = $this->language->get('entry_attributes');
		$this->data['entry_adult'] = $this->language->get('entry_adult');
		$this->data['entry_manufacturer_warranty'] = $this->language->get('entry_manufacturer_warranty');
		$this->data['entry_country_of_origin'] = $this->language->get('entry_country_of_origin');
		$this->data['entry_product_rel'] = $this->language->get('entry_product_rel');
		$this->data['entry_product_accessory'] = $this->language->get('entry_product_accessory');
		$this->data['entry_attr_vs_description'] = $this->language->get('entry_attr_vs_description');

		if (isset($this->request->post['hotline_yml_attributes'])) {
			$this->data['hotline_yml_attributes'] = $this->request->post['hotline_yml_attributes'];
		} elseif ($this->config->get('hotline_yml_attributes') != '') {
			$this->data['hotline_yml_attributes'] = explode(',', $this->config->get('hotline_yml_attributes'));
		} else {
			$this->data['hotline_yml_attributes'] = array();
		}
		if (isset($this->request->post['hotline_yml_adult'])) {
			$this->data['hotline_yml_adult'] = $this->request->post['hotline_yml_adult'];
		} else {
			$this->data['hotline_yml_adult'] = $this->config->get('hotline_yml_adult');
		}
		if (isset($this->request->post['hotline_yml_manufacturer_warranty'])) {
			$this->data['hotline_yml_manufacturer_warranty'] = $this->request->post['hotline_yml_manufacturer_warranty'];
		} else {
			$this->data['hotline_yml_manufacturer_warranty'] = $this->config->get('hotline_yml_manufacturer_warranty');
		}
		if (isset($this->request->post['hotline_yml_country_of_origin'])) {
			$this->data['hotline_yml_country_of_origin'] = $this->request->post['hotline_yml_country_of_origin'];
		} else {
			$this->data['hotline_yml_country_of_origin'] = $this->config->get('hotline_yml_country_of_origin');
		}
		if (isset($this->request->post['hotline_yml_attr_vs_description'])) {
			$this->data['hotline_yml_attr_vs_description'] = $this->request->post['hotline_yml_attr_vs_description'];
		} else {
			$this->data['hotline_yml_attr_vs_description'] = $this->config->get('hotline_yml_attr_vs_description');
		}

		if (isset($this->request->post['hotline_yml_product_rel'])) {
			$this->data['hotline_yml_product_rel'] = $this->request->post['hotline_yml_product_rel'];
		} else {
			$this->data['hotline_yml_product_rel'] = $this->config->get('hotline_yml_product_rel');
		}
		if (isset($this->request->post['hotline_yml_product_accessory'])) {
			$this->data['hotline_yml_product_accessory'] = $this->request->post['hotline_yml_product_accessory'];
		} else {
			$this->data['hotline_yml_product_accessory'] = $this->config->get('hotline_yml_product_accessory');
		}
		
		$this->load->model('catalog/attribute');
		$results = $this->model_catalog_attribute->getAttributes(array('sort'=>'attribute_group'));
		$this->data['attributes'] = $results;
		//---- Для вкладки аттрибутов ----

		//++++ Для магазинов одежды ++++
		$this->data['entry_color_option'] = $this->language->get('entry_color_option');
		$this->data['entry_size_option'] = $this->language->get('entry_size_option');
		$this->data['entry_size_unit'] = $this->language->get('entry_size_unit');
		$this->data['entry_optioned_name'] = $this->language->get('entry_optioned_name');
		$this->data['optioned_name_no'] = $this->language->get('optioned_name_no');
		$this->data['optioned_name_short'] = $this->language->get('optioned_name_short');
		$this->data['optioned_name_long'] = $this->language->get('optioned_name_long');
		
		$this->load->model('catalog/option');
		$results = $this->model_catalog_option->getOptions(array('sort' => 'name'));
		$this->data['options'] = $results;
		
		$this->data['tab_tailor_description'] = $this->language->get('tab_tailor_description');
		$this->data['is_old_version'] = $this->isOldVersion();

		if (isset($this->request->post['hotline_yml_color_options'])) {
			$this->data['hotline_yml_color_options'] = $this->request->post['hotline_yml_color_options'];
		} elseif ($this->config->get('hotline_yml_color_options') != '') {
			$this->data['hotline_yml_color_options'] = explode(',', $this->config->get('hotline_yml_color_options'));
		} else {
			$this->data['hotline_yml_color_options'] = array();
		}
		if (isset($this->request->post['hotline_yml_size_options'])) {
			$this->data['hotline_yml_size_options'] = $this->request->post['hotline_yml_size_options'];
		} elseif ($this->config->get('hotline_yml_size_options') != '') {
			$this->data['hotline_yml_size_options'] = explode(',', $this->config->get('hotline_yml_size_options'));
		} else {
			$this->data['hotline_yml_size_options'] = array();
		}
		if (isset($this->request->post['hotline_yml_size_units'])) {
			$this->data['hotline_yml_size_units'] = $this->request->post['hotline_yml_size_units'];
		} elseif ($this->config->get('hotline_yml_size_units') != '') {
			$this->data['hotline_yml_size_units'] = unserialize($this->config->get('hotline_yml_size_units'));
		} else {
			$this->data['hotline_yml_size_units'] = array();
		}

		if (isset($this->request->post['hotline_yml_optioned_name'])) {
			$this->data['hotline_yml_optioned_name'] = $this->request->post['hotline_yml_optioned_name'];
		} else {
			$this->data['hotline_yml_optioned_name'] = $this->config->get('hotline_yml_optioned_name');
		}
		$this->data['size_units_orig'] = array(
			'RU' => 'Россия (СНГ)',
			'EU' => 'Европа',
			'UK' => 'Великобритания',
			'US' => 'США',
			'INT' => 'Международная');
		$this->data['size_units_type'] = array(
			'INCH' => 'Дюймы',
			'Height' => 'Рост в сантиметрах',
			'Months' => 'Возраст в месяцах',
			'Years' => 'Возраст в годах',
			'Round' => 'Окружность в сантиметрах');
		//---- Для магазинов одежды ----

		$this->load->model('localisation/stock_status');

		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		$this->load->model('catalog/manufacturer');
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers(0);

		if (isset($this->request->post['hotline_yml_categories'])) {
			$this->data['hotline_yml_categories'] = $this->request->post['hotline_yml_categories'];
		} elseif ($this->config->get('hotline_yml_categories') != '') {
			$this->data['hotline_yml_categories'] = explode(',', $this->config->get('hotline_yml_categories'));
		} else {
			$this->data['hotline_yml_categories'] = array();
		}
		if (isset($this->request->post['hotline_yml_manufacturers'])) {
			$this->data['hotline_yml_manufacturers'] = $this->request->post['hotline_yml_manufacturers'];
		} elseif ($this->config->get('hotline_yml_manufacturers') != '') {
			$this->data['hotline_yml_manufacturers'] = explode(',', $this->config->get('hotline_yml_manufacturers'));
		} else {
			$this->data['hotline_yml_manufacturers'] = array();
		}
		if (isset($this->request->post['hotline_yml_categ_mapping'])) {
			$this->data['hotline_yml_categ_mapping'] = $this->request->post['hotline_yml_categ_mapping'];
		} elseif ($this->config->get('hotline_yml_categ_mapping') != '') {
			$this->data['hotline_yml_categ_mapping'] = unserialize($this->config->get('hotline_yml_categ_mapping'));
		} else {
			$this->data['hotline_yml_categ_mapping'] = array();
		}


		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		$allowed_currencies = array_flip(array('RUR', 'RUB', 'BYN', 'KZT', 'UAH'));
		$this->data['currencies'] = array_intersect_key($currencies, $allowed_currencies);

		$this->template = 'feed/hotline_yml.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete() {
		$text = $this->request->get['text'];
		$parts = explode('/', $text);
		foreach ($parts as $key=>$val) {
			$parts[$key] = trim($val);
		}
		if (count($parts) < 2) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1) AS text FROM oc_yandex_category WHERE level1 LIKE '".$this->db->escape($parts[0])."%' AND level2='' AND level3=''");
		}
		elseif (count($parts) < 3) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2 LIKE '".$this->db->escape($parts[1])."%' AND level3=''");
		}
		elseif (count($parts) < 4) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2,'/',level3) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2='".$this->db->escape($parts[1])."' AND level3 LIKE '".$this->db->escape($parts[2])."%'");
		}
		elseif (count($parts) < 5) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2,'/',level3,'/',level4) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2='".$this->db->escape($parts[1])."' AND level3='".$this->db->escape($parts[2])."' AND level4 LIKE '".$this->db->escape($parts[3])."%'");
		}
		else {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2,'/',level3,'/',level4,'/',level5) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2='".$this->db->escape($parts[1])."' AND level3='".$this->db->escape($parts[2])."' AND level4='".$this->db->escape($parts[3])."' AND level5 LIKE '".$this->db->escape($parts[4])."%'");
		}
		$ret = array();
		foreach ($q->rows as $row) {
			$ret[] = $row['text'];
		}
		echo json_encode($ret);
	}
	
	private function validate($data) {
		if (!$this->user->hasPermission('modify', 'feed/hotline_yml')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		/*
		elseif (!empty(array_intersect($data['hotline_yml_size_options'], $data['hotline_yml_color_options']))) {
			$this->error['warning'] = $this->language->get('error_intersects_options');
		}
		*/

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
