<?php
	class ControllerCatalogYandex extends Controller {
		private $controllerYaMarketApi;
		private $modelCatalogProduct;
		
		
		public function index() {
			$this->language->load('catalog/product');
			
			$this->load->model('kp/yandex');
			$this->load->model('catalog/group_price');
			
			$this->document->setTitle('Yandex Market'); 
			
			loadAndRenameCatalogModels('controller/yamarket/api.php', '', '');
			$this->controllerYaMarketApi = new ControllerYaMarketApi($this->registry);
			
			//Это блядь лайфхак от бога, просто			
			loadAndRenameCatalogModels('model/catalog/product.php', 'ModelCatalogProduct', 'ModelCatalogProductCatalog');
			$this->modelCatalogProduct = new ModelCatalogProductCatalog($this->registry);
			
			
			
			$this->getList();
		}
		
		public function hideshow(){
		
			$product_id = $this->request->get['product_id'];
		
			$this->hideOrShowProductInMarketViaAPI($product_id);
		
		
		}
		
		private function hideOrShowProductInMarketViaAPI($product_id){
			
			
			if ($this->config->get('config_yam_fbs_campaign_id')){
				$hiddenOffersClient = new \Yandex\Marketplace\Partner\Clients\HiddenOffersClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
				
				
				$query = $this->db->query("SELECT yam_product_id, yam_hidden FROM product WHERE product_id = '" . $product_id . "'");
				
				if ($query->num_rows){	
					
					
					if ($query->row['yam_hidden']){
						
						$json = ['hiddenOffers' => [
						[
						'offerId' 	=> $query->row['yam_product_id']
						]					
						]];
						
						$hiddenOffersClientResponse = $hiddenOffersClient->showOffers($this->config->get('config_yam_fbs_campaign_id'), $json);
						
						} else {
						
						$json = ['hiddenOffers' => [
						[
						'offerId' 	=> $query->row['yam_product_id'],
						'priority'	=> true,
						'comment' 	=> 'Скрыто из интерфейса управления KitchenProfi',
						]					
						]];
						
						$hiddenOffersClientResponse = $hiddenOffersClient->hideOffers($this->config->get('config_yam_fbs_campaign_id'), $json);
						
					}
					
					if ($hiddenOffersClientResponse->getStatus() == 'OK'){
						
						$this->db->query("UPDATE product SET yam_hidden = 1 - yam_hidden WHERE product_id = '" . $product_id . "'");
						$query = $this->db->query("SELECT yam_hidden FROM product WHERE product_id = '" . $product_id . "'");
						
						if ($query->row['yam_hidden']){
							$this->response->setOutput('скрыт');
						} else {
							$this->response->setOutput('ок');
						}
						
					} else {
					
						$this->response->setOutput('BUG!');
					
					}
					
				}
			}
			
		}
		
		
		private function updateYandexPriceInMarketViaAPI($product_id){
			
			if ($this->config->get('config_yam_offer_id_price_enable') && $this->config->get('config_yam_fbs_campaign_id') && !$this->config->get('config_yam_enable_plus_percent')){
				
				$this->load->model('catalog/product');
				
				//Получаем цену для YAM
				$query = $this->db->query("SELECT yam_product_id, yam_price, yam_special FROM product WHERE product_id = '" . $product_id . "'");
				
				if ($query->num_rows){
					
					$json = ['offers' => []];
					
					if ((float)$query->row['yam_special']){
						
						$json['offers'][] = [
						'id' 	=> $query->row['yam_product_id'],
						'price' => [
						'currencyId' 	=> 'RUR',
						'value'			=> (float)$query->row['yam_special'],
						'discountBase'	=> (float)$query->row['yam_price'],
						]						
						];
						
						} else {
						
						$json['offers'][] = [
						'id' 	=> $query->row['yam_product_id'],
						'price' => [
						'currencyId' 	=> 'RUR',
						'value'			=> (float)$query->row['yam_price'],
						]					
						];
						
					}
					
					$priceClient = new \Yandex\Marketplace\Partner\Clients\PriceClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
					$updatePricesResponse = $priceClient->updatePrices($this->config->get('config_yam_fbs_campaign_id'), $json);
					
					return $updatePricesResponse->getStatus();
				}
				
				return false;
				
			}
			
			return false;
			
		}
		
		public function updateProductFieldAjax(){
			if (!$this->user->hasPermission('modify', 'catalog/yandex')) {
				die('no_permission');
			}
			
			if (!$this->user->hasPermission('modify', 'catalog/yandex')) {
				die('no_permission');
			}
			
			
			$data = $this->request->post;
			
			
			
			if (isset($data['product_id']) && isset($data['field']) && isset($data['value'])){
				
				$check_field_query = $this->db->query("SHOW COLUMNS FROM `product` WHERE Field = '" . $this->db->escape($data['field']) . "'");
				if ($check_field_query->num_rows){
					
					$this->db->query("UPDATE `product` SET `" . $this->db->escape($data['field']) . "` = '" . $this->db->escape($data['value']) . "' WHERE product_id = '" . (int)$data['product_id'] . "'");
					
					if ($data['field'] == 'yam_price'){
						$this->db->query("UPDATE `product_price_national_to_yam` SET `price` = '" . $this->db->escape($data['value']) . "' WHERE product_id = '" . (int)$data['product_id'] . "' AND store_id = 0");
					}
					
					if ($data['field'] == 'yam_price' || $data['field'] == 'yam_special'){
						$this->response->setOutput($this->updateYandexPriceInMarketViaAPI($data['product_id']));					
					}
					} else {
					
					
					die('NO FIELD');
					
					
				}				
				
				} else {
				
				die('NO FIELD');
				
			}
			
			
		}
		
		
		protected function getList() {				
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
				} else {
				$filter_name = null;
			}
			
			if (isset($this->request->get['filter_yam_hidden'])) {
				$filter_yam_hidden = $this->request->get['filter_yam_hidden'];
				} else {
				$filter_yam_hidden = null;
			}
			
			if (isset($this->request->get['filter_is_illiquid'])) {
				$filter_is_illiquid = $this->request->get['filter_is_illiquid'];
				} else {
				$filter_is_illiquid = null;
			}
			
			if (isset($this->request->get['filter_buybox_failed'])) {
				$filter_buybox_failed = $this->request->get['filter_buybox_failed'];
				} else {
				$filter_buybox_failed = null;
			}
			
			if (isset($this->request->get['filter_notinfeed'])) {
				$filter_notinfeed = $this->request->get['filter_notinfeed'];
				} else {
				$filter_notinfeed = null;
			}
			
			if (isset($this->request->get['filter_yam_not_created'])) {
				$filter_yam_not_created = $this->request->get['filter_yam_not_created'];
				} else {
				$filter_yam_not_created = null;
			}
			
			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
				} else {
				$filter_status = null;
			}
			
			if (isset($this->request->get['filter_priceva_category'])) {
				$filter_priceva_category = $this->request->get['filter_priceva_category'];
				} else {
				$filter_priceva_category = null;
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
				} else {
				$filter_manufacturer_id = null;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'pd.name';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}	
			
			if (isset($this->request->get['filter_priceva_category'])) {
				$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
			}
			
			if (isset($this->request->get['filter_yam_hidden'])) {
				$url .= '&filter_yam_hidden=' . $this->request->get['filter_yam_hidden'];
			}
			
			if (isset($this->request->get['filter_is_illiquid'])) {
				$url .= '&filter_is_illiquid=' . $this->request->get['filter_is_illiquid'];
			}	
			
			if (isset($this->request->get['filter_buybox_failed'])) {
				$url .= '&filter_buybox_failed=' . $this->request->get['filter_buybox_failed'];
			}
			
			if (isset($this->request->get['filter_notinfeed'])) {
				$url .= '&filter_notinfeed=' . $this->request->get['filter_notinfeed'];
			}	
			
			if (isset($this->request->get['filter_yam_not_created'])) {
				$url .= '&filter_yam_not_created=' . $this->request->get['filter_yam_not_created'];
			}	
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['products'] = array();
			
			$data = array(
			'filter_name'	  			=> $filter_name, 
			'filter_is_illiquid'   		=> $filter_is_illiquid,
			'filter_buybox_failed'   	=> $filter_buybox_failed,
			'filter_notinfeed'   		=> $filter_notinfeed,
			'filter_yam_hidden'   		=> $filter_yam_hidden,
			'filter_yam_not_created'   	=> $filter_yam_not_created,
			'filter_manufacturer_id'   	=> $filter_manufacturer_id,
			'filter_priceva_category'   	=> $filter_priceva_category,
			'filter_status'   		 	=> true,
			'filter_quantity_not_null'  => true,
			'filter_quantity_stockM' 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true,
			'sort'            		 	=> $sort,
			'order'           			=> $order,
			'start'           			=> ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           			=> $this->config->get('config_admin_limit')
			);
			
			$this->load->model('tool/image');
			
			$this->data['yandex_pricetypes'] = $this->controllerYaMarketApi->getYamAPIValue('priceTypes');
			
			$product_total = $this->model_kp_yandex->getTotalProducts($data);
			$results = $this->model_kp_yandex->getProducts($data);
			
			foreach ($results as $result) {
				$action = array();
				
				if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
					$image = $this->model_tool_image->resize($result['image'], 60, 60);
					} else {
					$image = $this->model_tool_image->resize('no_image.jpg', 60, 60);
				}
				
				$product_info = $this->modelCatalogProduct->getProduct($result['product_id'], false);
				
				$price = $this->currency->format_with_left($product_info['price'], $this->config->get('config_regional_currency'));
				
				$special = false;
				if ((float)$product_info['special']) {
					$special = $this->currency->format_with_left($product_info['special'], $this->config->get('config_regional_currency'));
				}
				
				
				if ((float)$product_info['yam_price_national'] > 0){
					$yam_price = $this->currency->format_with_left($product_info['yam_price_national'], $this->config->get('config_regional_currency'), 1);
					
					$yam_special = false;
					if ((float)$product_info['yam_special_national']) {
						$yam_special = $this->currency->format_with_left($product_info['yam_special_national'], $this->config->get('config_regional_currency'), 1);
					}
					
					$yam_set = true;
					} else {
					
					$yam_price = $price;
					$yam_special = $special;
					
					$yam_set = false;
					
				}
				
				
				$yandex_prices = $this->model_kp_yandex->getYandexMarketRecommendedPrices($result['product_id']);
				
				//Если не включена настройка автоназначения цен, то цену не нужно форматировать
				if (!$this->config->get('config_yam_enable_plus_percent')) {
					$yam_price = preg_replace("([^0-9])", "", $yam_price);	
					$yam_special = preg_replace("([^0-9])", "", $yam_special);	
				}
				
				$market_data = $this->model_kp_yandex->getYandexMarketAdditionalData($result['product_id']);
				$yam_real_price = $this->currency->format_with_left($market_data['yam_real_price'], $this->config->get('config_regional_currency'), 1);
				
				//ЕСЛИ НЕ ЗАДАНЫ ЦЕНЫ КОМИСИИ ИЗ АПИ СТАТИСТИКИ
				if ($yam_special){
					$yam_tmp = preg_replace("([^0-9])", "", $yam_special);
					} else {
					$yam_tmp = preg_replace("([^0-9])", "", $yam_price);	
				}
				
				
				
				if (!empty($market_data['AGENCY_COMMISSION']) && !empty($market_data['FEE'])){
					$yam_money = $yam_tmp - ((float)$market_data['AGENCY_COMMISSION'] + (float)$market_data['FEE']);
					$yam_money = $this->currency->format_with_left($yam_money, $this->config->get('config_regional_currency'), 1);
					
					$yam_tariffs_decoded = json_decode($market_data['yam_fees'], true);
					
					$yam_tariffs = [];
					foreach ($yam_tariffs_decoded as $yam_tariff){
						$yam_tariffs[] = [
						'type' 		=> $yam_tariff['type'],
						'percent' 	=> $yam_tariff['percent'],
						'amount' 	=> $this->currency->format_with_left($yam_tariff['amount'], $this->config->get('config_regional_currency'), 1) 
						];
					}
				}
				
				
				$yam_money_default = $yam_money_default_unformatted = $yam_tmp - ($yam_tmp/100)*$this->config->get('config_yam_default_comission');
				$yam_money_default = $this->currency->format_with_left($yam_money_default, $this->config->get('config_regional_currency'), 1);
				
				//А вот тут мы играемся с себестоимостью	
				$costs = $this->model_kp_product->getProductCostsForStore($result['product_id'], 0);
				$cost = false;
				$cost_formatted = false;	
				$cost_percent_diff = false;
				
				if (!empty($costs['cost'])){
					$cost_in_eur = $this->currency->format_with_left($costs['cost'], 'EUR', 1);
					$cost = $this->currency->real_convert($costs['cost'], 'EUR', $this->config->get('config_regional_currency'), true);
					
					if ($cost > 0) {
						$cost_formatted = $this->currency->format_with_left($cost, $this->config->get('config_regional_currency'), 1);
						
						$cost_percent_diff = round(((float)$yam_money_default_unformatted - (float)$cost) / (float)$cost * 100,2);
					}
				}
				
				
				$min_sale_price = false;
				$min_sale_price_formatted = false;
				$min_sale_price_percent_diff = false;
				
				if (!empty($costs['min_sale_price'])){
					$min_sale_price_in_eur = $this->currency->format_with_left($costs['min_sale_price'], 'EUR', 1);
					$min_sale_price = $this->currency->real_convert($costs['min_sale_price'], 'EUR', $this->config->get('config_regional_currency'), true);
					
					if ($min_sale_price > 0) {
						$min_sale_price_formatted = $this->currency->format_with_left($min_sale_price, $this->config->get('config_regional_currency'), 1);
						
						$min_sale_price_percent_diff = round(((float)$yam_money_default_unformatted - (float)$min_sale_price) / (float)$min_sale_price * 100,2);
					}
				}
				
				
				
				//Определяем проблемный товар
				$problem = false;
				if ($result['yam_hidden']){
					$problem = true;
				}
				
				if ($result['yam_not_created']){
					$problem = true;
				}
				
				if ($min_sale_price_percent_diff && $min_sale_price_percent_diff < 16.5){
					$problem = true;
					$min_sale_price_percent_problem = true;
					} else {
					$min_sale_price_percent_problem = false;
				}
				
				if ($cost_percent_diff && $cost_percent_diff < 20){
					$problem = true;
					$cost_percent_problem = true;
					} else {
					$cost_percent_problem = false;
				}
				
				if ((float)$yam_money_default_unformatted < (float)$min_sale_price){
					$price_too_low_problem = true;
					} else {
					$price_too_low_problem = false;
				}
				
				
				if ((float)$product_info['yam_special_national']) {
					if ((float)$product_info['yam_special_national'] != $market_data['yam_real_price']){
						$problem = true;
					}
					} else {
					if ((float)$product_info['yam_price_national'] != $market_data['yam_real_price']){
						$problem = true;
					}	
				}
				
				$product = array(
				'product_id' 						=> $result['product_id'],
				'problem'							=> $problem,
				
				'min_sale_price_formatted'			=> $min_sale_price_formatted,
				'price_too_low_problem'				=> $price_too_low_problem,
				'min_sale_price_percent_diff'		=> $min_sale_price_percent_diff,
				'min_sale_price_percent_problem'	=> $min_sale_price_percent_problem,
				'min_sale_price_in_eur'				=> $min_sale_price_in_eur,
				
				'cost_formatted'					=> $cost_formatted,
				'cost_in_eur'						=> $cost_in_eur,
				'cost_percent_diff'					=> $cost_percent_diff,
				'cost_percent_problem'				=> $cost_percent_problem,
				
				'name'       		=> $product_info['name'],
				'model'      		=> $result['model'],
				'ean'      	 		=> $result['ean'],
				
				'manufacturer'		=> $product_info['manufacturer'],
				
				'yam_category_name'	=> !empty($market_data['yam_category_name'])?$market_data['yam_category_name']:'',
				
				'yam_set'      		=> $yam_set,
				
				'yam_price'      	=> $yam_price,
				'yam_real_price'	=> $yam_real_price,
				'yam_special'      	=> $yam_special,
				
				'yam_money'      		=> $yam_money,
				'yam_money_default'		=> $yam_money_default,			
				'yam_tariffs'			=> $yam_tariffs,
				
				'yam_hidden'   		=> $result['yam_hidden'],
				'yam_not_created'   => $result['yam_not_created'],
				'yam_marketSku'   	=> $result['yam_marketSku'],
				
				'yam_in_feed'      	=> $result['yam_in_feed'],
				'yam_disable' 		=> $result['yam_disable'],
				'is_illiquid' 		=> $result['is_illiquid'],

				'ozon_in_feed'      => $result['ozon_in_feed'],
				
				'price'      		=> $price,
				'special'      		=> $special,
				
				'image'      		=> $image,
				
				'quantity'   		=> $result['quantity'],
				'quantity_stockM'   => $result['quantity_stockM'],
				
				'yam_percent'   			=> (float)$result['yam_percent']?(float)$result['yam_percent']:(float)$this->config->get('config_yam_plus_percent'),
				'config_yam_plus_percent' 	=> ($this->config->get('config_yam_enable_plus_percent') && (float)$this->config->get('config_yam_plus_percent')),
				'yam_percent_overload' 		=> (float)$result['yam_percent'],
				
				'yam_special_percent' 					=> (float)$result['yam_special_percent']?(float)$result['yam_special_percent']:(float)$this->config->get('config_yam_plus_for_main_price'),
				'config_yam_enable_plus_for_main_price' => ($this->config->get('config_yam_enable_plus_for_main_price') && (float)$this->config->get('config_yam_plus_for_main_price')),
				'yam_special_percent_overload' 			=> (float)$result['yam_special_percent'],
				
				'yam_product_id' 						=> $result['yam_product_id'],
				
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'edit' 		 => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
				'view'		 => HTTPS_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id'], 
				'hideOrShow' => $this->url->link('catalog/yandex/hideshow', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'),
				
				'yam_href'   => 'https://partner.market.yandex.ru/supplier/' . $this->config->get('config_yam_fbs_campaign_id') . '/assortment/offer-card?offerId=' .  $result['yam_product_id'],
				
				);
				
				$product['price_warning'] = false;				
				foreach ($this->controllerYaMarketApi->getYamAPIValue('priceTypes') as $price_type){
					if (!empty($yandex_prices[$price_type]) && (float)$yandex_prices[$price_type]){
						
						$product[$price_type . '_equals'] = ($yam_tmp == (float)$yandex_prices[$price_type]);	
						$product['price_warning'] = ($product['price_warning'] || !$product[$price_type . '_equals']);
						
						$product[$price_type . '_unformatted'] = (float)$yandex_prices[$price_type];
						$product[$price_type] = $this->currency->format_with_left($yandex_prices[$price_type], $this->config->get('config_regional_currency'), 1);
						
						} else {
						$product[$price_type] = false;
					}
				}
				
				
				
				$this->data['products'][] = $product;
			}
			
			$this->data['heading_title'] = 'Yandex Market';		
			
			
			$this->data['token'] = $this->session->data['token'];
			
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
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}	
			
			if (isset($this->request->get['filter_priceva_category'])) {
				$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
			}
			
			if (isset($this->request->get['filter_yam_hidden'])) {
				$url .= '&filter_yam_hidden=' . $this->request->get['filter_yam_hidden'];
			}
			
			if (isset($this->request->get['filter_is_illiquid'])) {
				$url .= '&filter_is_illiquid=' . $this->request->get['filter_is_illiquid'];
			}	
			
			if (isset($this->request->get['filter_buybox_failed'])) {
				$url .= '&filter_buybox_failed=' . $this->request->get['filter_buybox_failed'];
			}
			
			if (isset($this->request->get['filter_notinfeed'])) {
				$url .= '&filter_notinfeed=' . $this->request->get['filter_notinfeed'];
			}	
			
			if (isset($this->request->get['filter_yam_not_created'])) {
				$url .= '&filter_yam_not_created=' . $this->request->get['filter_yam_not_created'];
			}	
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['sort_name'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
			$this->data['sort_model'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
			$this->data['sort_price'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
			$this->data['sort_quantity'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&sort=p.quantity_stockM' . $url, 'SSL');
			$this->data['sort_order'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
			
			
			$this->load->model('catalog/manufacturer');
			$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers(['filter_yandex_in_stock' => true]);
			
			$this->data['yandex_categories'] = $this->model_kp_yandex->getYandexMarketCategories();
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}			
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}	
			
			if (isset($this->request->get['filter_priceva_category'])) {
				$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
			}
			
			if (isset($this->request->get['filter_yam_hidden'])) {
				$url .= '&filter_yam_hidden=' . $this->request->get['filter_yam_hidden'];
			}
			
			if (isset($this->request->get['filter_is_illiquid'])) {
				$url .= '&filter_is_illiquid=' . $this->request->get['filter_is_illiquid'];
			}	
			
			if (isset($this->request->get['filter_buybox_failed'])) {
				$url .= '&filter_buybox_failed=' . $this->request->get['filter_buybox_failed'];
			}
			
			if (isset($this->request->get['filter_notinfeed'])) {
				$url .= '&filter_notinfeed=' . $this->request->get['filter_notinfeed'];
			}	
			
			if (isset($this->request->get['filter_yam_not_created'])) {
				$url .= '&filter_yam_not_created=' . $this->request->get['filter_yam_not_created'];
			}	
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}
			if (isset($this->request->get['filter_priceva_category'])) {
				$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['href_filter_is_illiquid'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&filter_is_illiquid=1' . $url, 'SSL');
			$this->data['href_filter_buybox_failed'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&filter_buybox_failed=1' . $url, 'SSL');	
			$this->data['href_filter_notinfeed'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&filter_notinfeed=1' . $url, 'SSL');
			$this->data['href_filter_yam_hidden'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&filter_yam_hidden=1' . $url, 'SSL');
			$this->data['href_filter_yam_not_created'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'] . '&filter_yam_not_created=1' . $url, 'SSL');
			
			$this->data['filter_name'] 				= $filter_name;
			$this->data['filter_priceva_category'] 	= $filter_priceva_category;
			$this->data['filter_manufacturer_id'] 	= $filter_manufacturer_id;
			$this->data['filter_is_illiquid'] 		= $filter_is_illiquid;
			$this->data['filter_buybox_failed'] 	= $filter_buybox_failed;
			$this->data['filter_notinfeed'] 		= $filter_notinfeed;
			$this->data['filter_yam_not_created'] 	= $filter_yam_not_created;
			
			
			//Посчитаем неликвид и нет в фиде
			$data = array(			
			'filter_is_illiquid'   		=> true,
			'filter_notinfeed'   		=> null,
			'filter_status'   		 	=> true,
			'filter_quantity_not_null'  => true,
			'filter_quantity_stockM' 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true
			);
			
			$this->data['total_not_liquid'] = $this->model_kp_yandex->getTotalProducts($data);
			
			//Посчитаем неликвид и нет в фиде
			$data = array(			
			'filter_is_illiquid'   		=> null,
			'filter_notinfeed'   		=> true,
			'filter_status'   		 	=> true,
			'filter_quantity_not_null'  => true,
			'filter_quantity_stockM' 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true
			);
			
			$this->data['total_not_in_feed'] = $this->model_kp_yandex->getTotalProducts($data);
			
			//Посчитаем неликвид и нет в фиде
			$data = array(			
			'filter_is_illiquid'   		=> null,
			'filter_notinfeed'   		=> null,
			'filter_yam_hidden'   		=> true,
			'filter_status'   		 	=> true,
			'filter_quantity_not_null'  => true,
			'filter_quantity_stockM' 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true
			);
			
			$this->data['total_yam_hidden'] = $this->model_kp_yandex->getTotalProducts($data);
			
			//Посчитаем несозданные карточки
			$data = array(			
			'filter_is_illiquid'   		=> null,
			'filter_notinfeed'   		=> null,
			'filter_yam_hidden'   		=> null,
			'filter_yam_not_created'   	=> true,
			'filter_status'   		 	=> true,
			'filter_quantity_not_null'  => true,
			'filter_quantity_stockM' 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true
			);
			
			$this->data['total_yam_not_created'] = $this->model_kp_yandex->getTotalProducts($data);
			
			//Посчитаем несозданные карточки
			$data = array(			
			'filter_is_illiquid'   		=> null,
			'filter_notinfeed'   		=> null,
			'filter_yam_hidden'   		=> null,
			'filter_yam_not_created'   	=> null,
			'filter_buybox_failed'   	=> true,
			'filter_status'   		 	=> true,
			'filter_quantity_not_null'  => true,
			'filter_quantity_stockM' 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true
			);
			
			$this->data['total_buybox_failed'] = $this->model_kp_yandex->getTotalProducts($data);
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'catalog/yandex.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}																