<?php
class ControllerPaymentUkrCredits extends Controller {
	protected $version = '1.0.5';
    private $error = [];

    public function index() {

		$token = version_compare(VERSION,'3.0','>=') ? 'user_' : '';
		$type = '';
		$dir = version_compare(VERSION,'2.3','>=') ? 'extension/payment' : 'payment';
		$payments_page = version_compare(VERSION,'3.0','>=') ? 'marketplace/extension' : (version_compare(VERSION,'2.3','>=') ? 'extension/extension' : 'extension/payment');
		
		$data = [];
		$data += $this->load->language($dir.'/ukrcredits');
		
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data += $this->request->post;
		} elseif ($this->config->get('ukrcredits_settings')) {
			$data += $this->config->get('ukrcredits_settings');
		} else {
			$data['pp_shop_id'] 			= '';
			$data['pp_shop_password'] 		= '';
			$data['pp_status'] 				= 0;
			$data['pp_sort_order'] 			= 10;
			$data['pp_hold'] 				= 0;
			$data['pp_discount'] 			= 0;
			$data['pp_special'] 			= 0;
			$data['pp_stock'] 				= 0;
			$data['pp_stock_status_id'] 	= [];
			$data['pp_pq'] 					= 24;
			$data['pp_min_total'] 			= '';
			$data['pp_max_total'] 			= 100000;
			$data['pp_merchantType'] 		= 'PP';
			$data['pp_markup_type'] 		= 'fixed';			
			$data['pp_markup_custom_PP'] 	= [1 => 1.5, 2 => 2.5, 3 => 4.5, 4 => 7, 5 => 9, 6 => 11.5, 7 => 13.5, 8 => 15.5, 9 => 16.5, 10 => 17, 11 => 17.5, 12 => 19, 13 => 20.5, 14 => 22, 15 => 23.5, 16 => 24.5, 17 => 26, 18 => 27, 19 => 28.5, 20 => 29.5, 21 => 31, 22 => 32, 23 => 33, 24 => 34.5]; 
			$data['pp_markup_acquiring'] 	= '2.0';			
			$data['pp_markup'] 				= 1.000;
			$data['pp_enabled'] 			= 0;
			$data['pp_product_allowed'] 		= [];
			$data['pp_categories_allowed'] 		= [];
			$data['pp_manufacturers_allowed'] 	= [];
			$data['pp_geo_zone_id'] 		= 0;
			$data['pp_svg_logo'] 			= '';

			$data['ii_shop_id'] 			= '';
			$data['ii_shop_password'] 		= '';
			$data['ii_status'] 				= 0;
			$data['ii_sort_order'] 			= 11;
			$data['ii_hold'] 				= 0;
			$data['ii_discount'] 			= 0;
			$data['ii_special'] 			= 0;
			$data['ii_stock'] 				= 0;
			$data['ii_stock_status_id'] 	= [];
			$data['ii_pq'] 					= 24;
			$data['ii_min_total'] 			= '';
			$data['ii_max_total'] 			= 100000;
			$data['ii_merchantType'] 		= 'II';
			$data['ii_markup_type'] 		= 'fixed';			
			$data['ii_markup_custom_II'] 	= array(1 => 1.5, 2 => 2.5, 3 => 4.5, 4 => 6.4, 5 => 7.6, 6 => 8.2, 7 => 8.7, 8 => 9.7, 9 => 10.6, 10 => 11.6, 11 => 12.2, 12 => 12.5, 13 => 12.8, 14 => 13.1, 15 => 13.4, 16 => 13.7, 17 => 14, 18 => 14.3, 19 => 14.7, 20 => 15.5, 21 => 16.2, 22 => 17, 23 => 17.6, 24 => 18.3); 
			$data['ii_markup_acquiring'] 	= '0';			
			$data['ii_markup'] 				= 1.000;
			$data['ii_enabled'] 			= 0;
			$data['ii_product_allowed'] 		= [];
			$data['ii_categories_allowed'] 		= [];
			$data['ii_manufacturers_allowed'] 	= [];
			$data['ii_geo_zone_id'] 		= 0;
			$data['ii_svg_logo'] 			= '';
			
			$data['mb_shop_id'] 			= '';
			$data['mb_shop_password'] 		= '';
			$data['mb_status'] 				= 0;
			$data['mb_sort_order'] 			= 12;
			$data['mb_discount'] 			= 0;
			$data['mb_special'] 			= 0;
			$data['mb_stock'] 				= 0;
			$data['mb_stock_status_id'] 	= [];
			$data['mb_pq'] 					= 24;
			$data['mb_min_total'] 			= 500;
			$data['mb_max_total'] 			= 100000;
			$data['mb_merchantType'] 		= 'MB';
			$data['mb_markup_type'] 		= 'fixed';
			$data['mb_markup_custom_MB'] 	= array(2 => 2.9, 3 => 4.1, 4 => 5.9, 5 => 7.2, 6 => 8.3, 7 => 9.5, 8 => 10.8, 9 => 12, 10 => 13.2, 11 => 14.3, 12 => 15.5, 13 => 16.6, 14 => 17.7, 15 => 18.8, 16 => 19.8, 17 => 20.9, 18 => 21.9, 19 => 23, 20 => 24, 21 => 29.4, 22 => 25.9, 23 => 26.8, 24 => 27.8); 
			$data['mb_markup_acquiring'] 	= '0';
			$data['mb_markup'] 				= 1.000;
			$data['mb_enabled'] 			= 0;
			$data['mb_product_allowed'] 		= [];
			$data['mb_categories_allowed'] 		= [];
			$data['mb_manufacturers_allowed'] 	= [];
			$data['mb_geo_zone_id'] 		= 0;
			$data['mb_svg_logo'] 			= '';
			
			$data['completed_status_id'] = 0;
			$data['canceled_status_id'] = 0;
			$data['clientwait_status_id'] = 0;
			$data['created_status_id'] = 0;
			$data['failed_status_id'] = 0;
			$data['rejected_status_id'] = 0;
			
			$data['icons_size'] = '25';
			$data['css_button'] = 'btn btn-primary btn-lg btn-block';
			$data['selector_button'] = '#button-cart';
			$data['selector_block'] = '#product';
			$data['show_icons'] = 1;
			$data['css_custom'] = '';

			foreach ($data['languages'] as $language){
				$data['button_name'][$language['language_id']] 	= 'Купить в кредит';

				$data['title_pp'][$language['language_id']]    				= '';
				$data['text_in_product_pp'][$language['language_id']]    	= '';
				$data['text_in_cart_pp'][$language['language_id']]    		= '';

				$data['title_ii'][$language['language_id']]    				= '';
				$data['text_in_product_ii'][$language['language_id']]    	= '';
				$data['text_in_cart_ii'][$language['language_id']]    		= '';

				$data['title_mb'][$language['language_id']]    				= '';
				$data['text_in_product_mb'][$language['language_id']]    	= '';
				$data['text_in_cart_mb'][$language['language_id']]    		= '';
			}
		}
		
		if ($this->config->get("ukrcredits_status")) {
			$setting["ukrcredits_status"] = $this->config->get("ukrcredits_status");
		}

		if ($this->config->get("ukrcredits_pp_status")) {
			$setting["ukrcredits_pp_status"] = $this->config->get("ukrcredits_pp_status");
		}

		if ($this->config->get("ukrcredits_ii_status")) {
			$setting["ukrcredits_ii_status"] = $this->config->get("ukrcredits_ii_status");
		}

		if ($this->config->get("ukrcredits_mb_status")) {
			$setting["ukrcredits_mb_status"] = $this->config->get("ukrcredits_mb_status");
		}

		if ($this->config->get("ukrcredits_pp_sort_order")) {
			$setting["ukrcredits_pp_sort_order"] = $this->config->get("ukrcredits_pp_sort_order");
		}

		if ($this->config->get("ukrcredits_ii_sort_order")) {
			$setting["ukrcredits_ii_sort_order"] = $this->config->get("ukrcredits_ii_sort_order");
		}

		if ($this->config->get("ukrcredits_mb_sort_order")) {
			$setting["ukrcredits_mb_sort_order"] = $this->config->get("ukrcredits_mb_sort_order");
		}

		$setting["ukrcredits_settings"] = $this->config->get("ukrcredits_settings");
		$this->model_setting_setting->editSetting("ukrcredits", $setting);

		if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
			if ($this->request->post["pp_status"] || $this->request->post["ii_status"] || $this->request->post["mb_status"]) {
				$setting["ukrcredits_status"] = 1;
			}

			if ($this->request->post["pp_status"]) {
				$setting["ukrcredits_pp_status"] = 1;
			}

			if ($this->request->post["ii_status"]) {
				$setting["ukrcredits_ii_status"] = 1;
			}

			if ($this->request->post["mb_status"]) {
				$setting["ukrcredits_mb_status"] = 1;
			}

			if (!isset($this->request->post["pp_product_allowed"])) {
				$this->request->post["pp_product_allowed"] = [];
			}

			if (!isset($this->request->post["pp_stock_status_id"])) {
				$this->request->post["pp_stock_status_id"] = [];
			}

			if (!isset($this->request->post["pp_categories_allowed"])) {
				$this->request->post["pp_categories_allowed"] = [];
			}

			if (!isset($this->request->post["pp_manufacturers_allowed"])) {
				$this->request->post["pp_manufacturers_allowed"] = [];
			}

			if (!isset($this->request->post["ii_stock_status_id"])) {
				$this->request->post["ii_stock_status_id"] = [];
			}

			if (!isset($this->request->post["ii_product_allowed"])) {
				$this->request->post["ii_product_allowed"] = [];
			}

			if (!isset($this->request->post["ii_categories_allowed"])) {
				$this->request->post["ii_categories_allowed"] = [];
			}

			if (!isset($this->request->post["pp_manufacturers_allowed"])) {
				$this->request->post["pp_manufacturers_allowed"] = [];
			}

			if (!isset($this->request->post["mb_product_allowed"])) {
				$this->request->post["mb_product_allowed"] = [];
			}

			if (!isset($this->request->post["mb_stock_status_id"])) {
				$this->request->post["mb_stock_status_id"] = [];
			}

			if (!isset($this->request->post["mb_categories_allowed"])) {
				$this->request->post["mb_categories_allowed"] = [];
			}

			if (!isset($this->request->post["mb_manufacturers_allowed"])) {
				$this->request->post["mb_manufacturers_allowed"] = [];
			}

			$setting["ukrcredits_sort_order"] 		= $this->request->post["pp_sort_order"] . "," . $this->request->post["ii_sort_order"] . "," . $this->request->post["mb_sort_order"];
			$setting["ukrcredits_pp_sort_order"] 	= $this->request->post["pp_sort_order"];
			$setting["ukrcredits_ii_sort_order"] 	= $this->request->post["ii_sort_order"];
			$setting["ukrcredits_mb_sort_order"] 	= $this->request->post["mb_sort_order"];
			$setting["ukrcredits_license"] 			= $this->config->get("ukrcredits_license");
			$setting["ukrcredits_settings"] 		= $this->request->post;

			$this->model_setting_setting->editSetting("ukrcredits", $setting);
			$this->session->data["success"] = $this->language->get("text_success");
			if (version_compare(VERSION, "2.0", ">=")) {
				$this->response->redirect($this->url->link($payments_page, $token . "token=" . $this->session->data[$token . "token"] . "&type=payment", true));
			} else {
				$this->redirect($this->url->link("extension/payment", "token=" . $this->session->data["token"], "SSL"));
			}
		}
		
		$data['token'] = $this->session->data[$token.'token'];
		$data['text_token'] = $token.'token';
		
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['shop_id_pp'])) {
            $data['error_shop_id_pp'] = $this->error['shop_id_pp'];
        } else {
            $data['error_shop_id_pp'] = '';
        }
        
        if (isset($this->error['shop_password_pp'])) {
            $data['error_shop_password_pp'] = $this->error['shop_password_pp'];
        } else {
            $data['error_shop_password_pp'] = '';
        }                
        
        if (isset($this->error['pq_pp'])) {
            $data['error_pq_pp'] = $this->error['pq_pp'];
        } else {
            $data['error_pq_pp'] = '';
        }  

        if (isset($this->error['shop_id_ii'])) {
            $data['error_shop_id_ii'] = $this->error['shop_id_ii'];
        } else {
            $data['error_shop_id_ii'] = '';
        }
        
        if (isset($this->error['shop_password_ii'])) {
            $data['error_shop_password_ii'] = $this->error['shop_password_ii'];
        } else {
            $data['error_shop_password_ii'] = '';
        }                
        
        if (isset($this->error['pq_ii'])) {
            $data['error_pq_ii'] = $this->error['pq_ii'];
        } else {
            $data['error_pq_ii'] = '';
        }  		

        if (isset($this->error['shop_id_mb'])) {
            $data['error_shop_id_mb'] = $this->error['shop_id_mb'];
        } else {
            $data['error_shop_id_mb'] = '';
        }
        
        if (isset($this->error['shop_password_mb'])) {
            $data['error_shop_password_mb'] = $this->error['shop_password_mb'];
        } else {
            $data['error_shop_password_mb'] = '';
        }                
        
        if (isset($this->error['pq_mb'])) {
            $data['error_pq_mb'] = $this->error['pq_mb'];
        } else {
            $data['error_pq_mb'] = '';
        }  
        
		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token.'token=' . $this->session->data[$token.'token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($payments_page, $token.'token=' . $this->session->data[$token.'token'] . '&type=payment', true)
		);

        $data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($dir.'/ukrcredits', $token.'token=' . $this->session->data[$token.'token'], true)
        );
                
        $data['action'] = $this->url->link($dir.'/ukrcredits', $token.'token=' . $this->session->data[$token.'token'], true);
        $data['cancel'] = $this->url->link($payments_page, $token.'token=' . $this->session->data[$token.'token'] . '&type=payment', true);

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/stock_status');
        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
		
		$data['pp_products_allowed'] = [];
		if (!isset($data['pp_product_allowed'])) {
			$data['pp_product_allowed'] = [];
		}
		foreach ($data['pp_product_allowed'] as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info) {
				$data['pp_products_allowed'][] = array(
					'product_id' 	=> $product_info['product_id'],
					'name'        	=> $product_info['name']
				);
			}
		}

		$data['pp_category_allowed'] = [];
		if (!isset($data['pp_categories_allowed'])) {
			$data['pp_categories_allowed'] = [];
		}

		foreach ($data['pp_categories_allowed'] as $category_id) {
			$category_info = $this->model_catalog_category->getCategories(['filter_category_id' => $category_id])[0];
			if ($category_info) {
				$data['pp_category_allowed'][] = array(
					'category_id' 	=> $category_info['category_id'],
					'name'        	=> $category_info['name']
				);
			}
		}
		
		$data['pp_manufacturer_allowed'] = [];
		if (!isset($data['pp_manufacturers_allowed'])) {
			$data['pp_manufacturers_allowed'] = [];
		}

		foreach ($data['pp_manufacturers_allowed'] as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			if ($manufacturer_info) {
				$data['pp_manufacturer_allowed'][] = array(
					'manufacturer_id' 	=> $manufacturer_info['manufacturer_id'],
					'name'        		=> $manufacturer_info['name']
				);
			}
		}
		
		$data['ii_products_allowed'] = [];
		if (!isset($data['ii_product_allowed'])) {
			$data['ii_product_allowed'] = [];
		}		
		foreach ($data['ii_product_allowed'] as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info) {
				$data['ii_products_allowed'][] = array(
					'product_id' => $product_info['product_id'],
					'name'        => $product_info['name']
				);
			}
		}

		$data['ii_category_allowed'] = [];
		if (!isset($data['ii_categories_allowed'])) {
			$data['ii_categories_allowed'] = [];
		}

		foreach ($data['ii_categories_allowed'] as $category_id) {
			$category_info = $this->model_catalog_category->getCategories(['filter_category_id' => $category_id])[0];
			if ($category_info) {
				$data['ii_category_allowed'][] = array(
					'category_id' 	=> $category_info['category_id'],
					'name'        	=> $category_info['name']
				);
			}
		}
		
		$data['ii_manufacturer_allowed'] = [];
		if (!isset($data['ii_manufacturers_allowed'])) {
			$data['ii_manufacturers_allowed'] = [];
		}

		foreach ($data['ii_manufacturers_allowed'] as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			if ($manufacturer_info) {
				$data['ii_manufacturer_allowed'][] = array(
					'manufacturer_id' 	=> $manufacturer_info['manufacturer_id'],
					'name'        		=> $manufacturer_info['name']
				);
			}
		}

		$data['mb_products_allowed'] = [];
		if (!isset($data['mb_product_allowed'])) {
			$data['mb_product_allowed'] = [];
		}		
		foreach ($data['mb_product_allowed'] as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info) {
				$data['mb_products_allowed'][] = array(
					'product_id' => $product_info['product_id'],
					'name'        => $product_info['name']
				);
			}
		}

		$data['mb_category_allowed'] = [];
		if (!isset($data['mb_categories_allowed'])) {
			$data['mb_categories_allowed'] = [];
		}

		foreach ($data['mb_categories_allowed'] as $category_id) {
			$category_info = $this->model_catalog_category->getCategories(['filter_category_id' => $category_id])[0];
			if ($category_info) {
				$data['mb_category_allowed'][] = array(
					'category_id' 	=> $category_info['category_id'],
					'name'        	=> $category_info['name']
				);
			}
		}
		
		$data['mb_manufacturer_allowed'] = [];
		if (!isset($data['mb_manufacturers_allowed'])) {
			$data['mb_manufacturers_allowed'] = [];
		}

		foreach ($data['mb_manufacturers_allowed'] as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			if ($manufacturer_info) {
				$data['mb_manufacturer_allowed'][] = array(
					'manufacturer_id' 	=> $manufacturer_info['manufacturer_id'],
					'name'        		=> $manufacturer_info['name']
				);
			}
		}
		
		$data['oc15'] = true;
		$this->document->addScript('view/javascript/jquery/uctabs.js');
		$this->document->addStyle('view/stylesheet/bootstrap.css');
		$data['column_left'] = false;
		$this->data = $data;
		$this->template = 'payment/ukrcredits.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);	

		$this->response->setOutput($this->render());
    }
	
	public function license() {
		
	}
    
    public function validate() {
        if (!$this->user->hasPermission('modify', 'payment/ukrcredits')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['pp_shop_id'] && $this->request->post['pp_status']) {
            $this->error['shop_id_pp'] = $this->language->get('error_shop_id_pp');
        }

        if (!$this->request->post['pp_shop_password'] && $this->request->post['pp_status']) {
            $this->error['shop_password_pp'] = $this->language->get('error_shop_password');
        }

        if (($this->request->post['pp_pq'] > 24 || $this->request->post['pp_pq'] <= 0) && $this->request->post['pp_status']) {
            $this->error['pq_pp'] = $this->language->get('error_pq');
        }
		
        if (!$this->request->post['ii_shop_id'] && $this->request->post['ii_status']) {
            $this->error['shop_id_ii'] = $this->language->get('error_shop_id_ii');
        }

        if (!$this->request->post['ii_shop_password'] && $this->request->post['ii_status']) {
            $this->error['shop_password_ii'] = $this->language->get('error_shop_password');
        }

        if (($this->request->post['ii_pq'] > 24 || $this->request->post['ii_pq'] <= 0) && $this->request->post['ii_status']) {
            $this->error['pq_ii'] = $this->language->get('error_pq');
        }
		
        if (!$this->request->post['mb_shop_id'] && $this->request->post['mb_status']) {
            $this->error['shop_id_mb'] = $this->language->get('error_shop_id_mb');
        }

        if (!$this->request->post['mb_shop_password'] && $this->request->post['mb_status']) {
            $this->error['shop_password_mb'] = $this->language->get('error_shop_password');
        }

        if (($this->request->post['mb_pq'] > 24 || $this->request->post['mb_pq'] <= 2) && $this->request->post['mb_status']) {
            $this->error['pq_mb'] = $this->language->get('error_pq');
        }
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
        return !$this->error;
    }

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `product_ukrcredits` (
		  `product_id` int(11) NOT NULL,
		  `product_pp` int(1) NOT NULL,
		  `product_ii` int(1) NOT NULL,
		  `product_mb` int(1) NOT NULL,
		  `partscount_pp` int(2) NOT NULL,
		  `partscount_ii` int(2) NOT NULL,
		  `partscount_mb` int(2) NOT NULL,
		  `markup_pp` decimal(15,4) NOT NULL,
		  `markup_ii` decimal(15,4) NOT NULL,
		  `markup_mb` decimal(15,4) NOT NULL,
		  `special_pp` int(1) NOT NULL,
		  `special_ii` int(1) NOT NULL,
		  `special_mb` int(1) NOT NULL,
		  `discount_pp` int(1) NOT NULL,
		  `discount_ii` int(1) NOT NULL,
		  `discount_mb` int(1) NOT NULL,
		  PRIMARY KEY (`product_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `order_ukrcredits` (
		  `order_id` int(11) NOT NULL,
		  `ukrcredits_payment_type` varchar(2) NOT NULL,
		  `ukrcredits_order_id` varchar(64) NOT NULL,
		  `ukrcredits_order_status` varchar(64) NOT NULL,
		  `ukrcredits_order_substatus` varchar(64) NOT NULL,
		  PRIMARY KEY (`order_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		if (version_compare(VERSION,'2.0','>=') && version_compare(VERSION,'3.0','<')) {
			$this->load->model('extension/extension');
			$this->model_extension_extension->install('payment', 'ukrcredits_pp');
			$this->model_extension_extension->install('payment', 'ukrcredits_ii');
			$this->model_extension_extension->install('payment', 'ukrcredits_mb');			
		} else {
			$this->load->model('setting/extension');
			$this->model_setting_extension->install('payment', 'ukrcredits_pp');
			$this->model_setting_extension->install('payment', 'ukrcredits_ii');
			$this->model_setting_extension->install('payment', 'ukrcredits_mb');
		}
		if (version_compare(VERSION,'2.3','>=') && version_compare(VERSION,'3.0','<')) {
			$this->load->model('extension/event');
			$this->model_extension_event->addEvent('payment_ukrcredits', 'catalog/controller/extension/payment/*/before', 'module/ukrcredits/controller', 1, 0);
		}
	}
	
	public function uninstall() {
		if (version_compare(VERSION,'2.0','>=') && version_compare(VERSION,'3.0','<')) {
			$this->load->model('extension/extension');
			$this->model_extension_extension->uninstall('payment', 'ukrcredits_pp');
			$this->model_extension_extension->uninstall('payment', 'ukrcredits_ii');
			$this->model_extension_extension->uninstall('payment', 'ukrcredits_mb');			
		} else {
			$this->load->model('setting/extension');
			$this->model_setting_extension->uninstall('payment', 'ukrcredits_pp');
			$this->model_setting_extension->uninstall('payment', 'ukrcredits_ii');
			$this->model_setting_extension->uninstall('payment', 'ukrcredits_mb');
		}
		if (version_compare(VERSION,'2.3','>=') && version_compare(VERSION,'3.0','<')) {
			$this->load->model('extension/event');
			$this->model_extension_event->deleteEvent('payment_ukrcredits');
		}
	}
	
	public function controller(&$route) {
		if ($route == 'extension/payment/ukrcredits' || $route == 'extension/total/totalukrcredits') {

			$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
			list($base, $folder, $extension) = array_pad(explode('/', $route), 3, NULL);
			
			if (($base == 'extension') && !is_file(DIR_APPLICATION.'controller/'.$route.'.php') && (!is_null($folder) && !is_null($extension) && is_file(DIR_APPLICATION.'controller/'.$folder.'/'.$extension.'.php'))) {
				$route = $folder.'/'.$extension;
			}
		}
	}
}