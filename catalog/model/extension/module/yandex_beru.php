<?php
require_once DIR_SYSTEM . 'library/yandex_beru/yandex_beru.php';

class ModelExtensionModuleYandexBeru extends Model {
	
	public function getQuantityBySKU($shopSku) {
		$offer_key = $this->getKeyByShopSku($shopSku);
		$offer_key_data = explode('-',$offer_key);
//		sku формата {product_id}_{option_id}_{option_value_id}_{option_id}_{option_value_id}..
		$product_id = array_shift($offer_key_data);
//		Получим количество товара
		$offer_quantity = $this->getProductQuantity($product_id);
//		Если есть опции то проверяем количетсво каждой опции и выбираем минимальное (если опция вычитается при покупке)
//		Если опция не вычитается оставляем предыдущее количетсво
	
		if(!empty($offer_key_data)){
			
			$options = array_chunk($offer_key_data, 2);
			
			foreach($options as $option){
				$option_data = $this->getProductOption($product_id, $option[0], $option[1]);
//				Если нет данных по комбинации опций у товара (администратор мог удалить) то возвращаем 0

				if(!empty($option_data)){
					if($option_data['subtract'] && $option_data['quantity'] < $offer_quantity){
						$offer_quantity = $option_data['quantity'];
					}
				}else{
					return 0;
				}
			}
		}
		return $offer_quantity;		
	}
	
	public function getProductOptionValue($product_id, $option_id, $option_value_id) {
		$query = $this->db->query("
			SELECT 
				pov.*, 
				ovd.*,
				od.name as option_name,
				o.type
			FROM " . DB_PREFIX . "product_option_value pov 
			LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) 
			LEFT JOIN " . DB_PREFIX . "option o ON (pov.option_id = o.option_id)
			LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id)
			WHERE pov.option_value_id = '" . (int)$option_value_id . "'
			AND pov.option_id = '" . (int)$option_id . "' 
			AND pov.product_id = '" . (int)$product_id . "' 
			AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getProductData($product_id){
		$query = $this->db->query("
			SELECT 
				p.*, 
				pd.*
			FROM " . DB_PREFIX . "product p
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
			WHERE p.product_id = '" . (int)$product_id . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
		
		
	}
	
	public function getKeyByShopSku($shopSku){
		$query = $this->db->query("SELECT `key` FROM " . DB_PREFIX . "yb_offers WHERE `shopSku` = '" . $this->db->escape($shopSku) . "'");
		
		if($query->num_rows){
			return $query->row['key'];
		}else{
			return false;
		}
		
	}
	public function getProductQuantity($product_id){
		$query = $this->db->query("SELECT DISTINCT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		return  $query->num_rows?$query->row['quantity']:0;
	}
	
	public function getProductOption($product_id, $option_id, $option_value_id){
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . (int)$option_id . "'AND option_value_id = '" . (int)$option_value_id . "'");
		return $query->row;
	}
	
	public function setOrderShipmentId($order_id, $shipment_id){
		$this->db->query("UPDATE " . DB_PREFIX . "order SET `shipment_id` = '" . (int)$shipment_id . "' WHERE `order_id` = '" . (int)$order_id . "'");		
	}
	
	public function setMarketOrderId($order_id, $market_order_id){
		$this->db->query("UPDATE " . DB_PREFIX . "order SET `market_order_id` = '" . (int)$market_order_id . "' WHERE `order_id` = '" . (int)$order_id . "'");		
	}
	
	public function setMarketShipmentDate($order_id, $shipment_date){
		$log = new Log('yandex_beru_order_accept_date.log');
		
		$new_date = DateTime::createFromFormat('d-m-Y', $shipment_date)->format('Y-m-d');
		$log->write("UPDATE " . DB_PREFIX . "order SET `shipment_date` = '" . $new_date . "' WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "order SET `shipment_date` = '" . $new_date . "' WHERE `order_id` = '" . (int)$order_id . "'");	
	}
	
	public function setMarketOrderType($order_id, $order_type){
		$this->db->query("UPDATE " . DB_PREFIX . "order SET `shipment_scheme` = '" . $this->db->escape($order_type) . "' WHERE `order_id` = '" . (int)$order_id . "'");		
	}
	
	public function getMarketOrderType($order_id){
		$query = $this->db->query("SELECT `shipment_scheme` FROM " . DB_PREFIX . "order WHERE `order_id` = '" . (int)$order_id . "'");
		
		if($query->num_rows){
			return $query->row['shipment_scheme'];
		}else{
			return false;
		} 
	}
	
	public function getMarketOrderId($order_id){
		$query = $this->db->query("SELECT `market_order_id` FROM " . DB_PREFIX . "order WHERE `order_id` = '" . (int)$order_id . "'");
		
		if($query->num_rows){
			return $query->row['market_order_id'];
		}else{
			return false;
		}
	}
	
	public function getShopOrderId($market_order_id){
		$query = $this->db->query("SELECT `order_id` FROM " . DB_PREFIX . "order WHERE `market_order_id` = '" . (int)$market_order_id . "'");
		
		if($query->num_rows){
			return $query->row['order_id'];
		}else{
			return false;
		}
	}
//	Получение статуза заказа Маркета согласно таблице сопоставления в настройках модуля
	public function getMarketStatus($status_id, $dbs = ''){
		$market_status = "";
		
		if(!empty($dbs)){
			$statuses = $this->config->get('yandex_beru_statuses_dbs');
		} else {
			$statuses = $this->config->get('yandex_beru_statuses');
		}
		if($statuses){
			$statuses = array_flip($statuses);
			if(array_key_exists($status_id, $statuses)){
				$status_keys = explode('-',$statuses[$status_id]);
				if(count($status_keys)==2){
					return [
						'status_id' => $status_keys[1],
						'parent_id' => $status_keys[0]
					];
				}else{
					return [
						'status_id' => $status_keys[0],
						'parent_id' => false
					];
				}
			}
		}
		
		return false;
	}
//	Получение статуза заказа Опенкарт согласно таблице сопоставления в настройках модуля	
	public function getShopStatus($market_status, $market_substatus, $dbs = ''){

		if(!empty($dbs)){
			$statuses = $this->config->get('yandex_beru_statuses_dbs');
		} else {
			$statuses = $this->config->get('yandex_beru_statuses');
		}
		
		
		if($statuses){
			if(array_key_exists($market_status.'-'.$market_substatus, $statuses)){
				return 	$statuses[$market_status.'-'.$market_substatus];
			}elseif(array_key_exists($market_status, $statuses)){
				return 	$statuses[$market_status];
			}
		}
		
		return false;
	}
//	Изменение статуса заказа на Маркете
	public function updateOrderStatus($order_id, $status_id){
		$market_order_id = $this->getMarketOrderId($order_id);
				
		$market_order_type = $this->getMarketOrderType($order_id);
		
		$log = new Log('updateOrderStatus.log');
		$log->write($order_id);
		$log->write($market_order_type);
		$log->write($market_order_id);
		if($market_order_id){
			if($market_order_type == 'FBS'){
				$market_status = $this->getMarketStatus($status_id);
				
				if($market_status){
					$this->api = new yandex_beru();
					$this->api->setAuth($this->config->get('yandex_beru_oauth'),$this->config->get('yandex_beru_auth_token'),$this->config->get('yandex_beru_company_id'));
		//			{
		//  "orders":
		//  [
		//    {
		//      "id": {int64},
		//      "status": "{enum}", 
		//      "substatus": "{enum}"
		//    }
		//    ...
		//  ]
		//}
					if($market_status['parent_id']){
						$order_data = [
							'id'		=> $market_order_id,
							'status'	=> $market_status['parent_id'],
							'substatus' => $market_status['status_id']
						];
					}else{
						$order_data = [
							'id'	=> $market_order_id,
							'status'=> $market_status['status_id']
						];
					}

					$post_data['orders'][] = $order_data; 

					$component = $this->api->loadComponent('ordersStatusUpdate');

					$component->setData($post_data);
					$response = $this->api->sendData($component);

					if(!empty($response['result']['orders'][0]['errorDetails'])){
						return $response['result']['orders'][0]['errorDetails'];
					}else{
						return false;
					}
				}
			}elseif($market_order_type == 'DBS'){
				//TODO
				$market_status = $this->getMarketStatus($status_id,'DBS');
				
				$this->api = new yandex_beru();
				
				$this->api->setAuth($this->config->get('yandex_beru_oauth'),$this->config->get('yandex_beru_auth_token_DBS'),$this->config->get('yandex_beru_company_id_DBS'));
				
				if($market_status['parent_id']){
					$order_data = [
						'status'	=> $market_status['parent_id'],
						'substatus' => $market_status['status_id']
					];
				}else{
					$order_data = [
						'status'=> $market_status['status_id']
					];
				}
				$post_data['order'] = $order_data; 

				$component = $this->api->loadComponent('ordersStatus');

				$component->setData($post_data);
				$component->setOrder($market_order_id);
				$response = $this->api->sendData($component);
				$log->write(print_r($response,1));
				if(!empty($response['result']['order']['errors'])){
					return $response['result']['order']['errors'];
				}elseif(!empty($response['error']['message'])){
					return $response['error']['message'];
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
		
	}
	
//	Изменение статуса в магазине
	public function changeShopStatus($market_order_id, $market_status, $market_substatus){
		
		$notify = false; 
		$comment = "Обновление статуса через Yandex Market";
		
		$shop_order_id = $this->getShopOrderId($market_order_id);
		$shop_status_id = $this->getShopStatus($market_status,$market_substatus);
		
		if($shop_order_id && $shop_status_id){
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$shop_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$shop_order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$shop_order_id . "', order_status_id = '" . (int)$shop_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			
			return true;
			
		}
		return false;
	}

	public function checkNameFile($name_file){

		$check = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = 'yandex_market' AND setting LIKE '%\"name_file\":\"$name_file\"%'");

		return $check->row;

	}

	public function getCategory($categories = array()){

		if(!empty($categories)){

			$result_categories = array();

			foreach ($categories as $categories_id) {

				$result_categories = array_merge_recursive($result_categories, $categories_id);

			}
			
			$result_categories = array_unique($result_categories);

			$sql ="SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN  " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE ";

			foreach ($result_categories as $key => $result_category) {
				if($key == "0"){
					$sql .= "c.category_id = '" . $result_category . "'"; 
				} else {
					$sql .= " or c.category_id = '" . $result_category . "'"; 
				}
			}

 			$query = $this->db->query($sql);

			return $query->rows;

		} else {

			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN  " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id ='" . (int)$this->config->get('config_language_id') . "'");

			return $query->rows;

		}

	}


	public function getProducts($product_filter){

		$sql = "SELECT DISTINCT p.product_id FROM  " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " LEFT JOIN  " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)"; 

		$sql .= " WHERE p.status='1'"; 

		//Категории
		if($product_filter['yandex_market_category_all'] == 'on' or empty($product_filter['yandex_market_category_list'])){

			$sql.= 'AND p2c.category_id IS NOT NULL';

		} else {
			$sql .= " AND ("; 

			foreach ($product_filter['yandex_market_category_list'] as $key => $category) {

				if($key == "0"){
					$sql .= "p2c.category_id = '" . $category . "'"; 
				} else {
					$sql .= " or p2c.category_id = '" . $category . "'"; 
				}
			}

			$sql .= ") "; 
		}
		//Категории

		//Производители
		if(!empty($product_filter['manufacturers_selected'])){

			$sql .= " AND ("; 

			foreach ($product_filter['manufacturers_selected'] as $key => $manufacturer) {

				if($key == "0"){
					$sql .= "p.manufacturer_id = '" . $manufacturer . "'"; 
				} else {
					$sql .= " or p.manufacturer_id = '" . $manufacturer . "'"; 
				}
			}

			$sql .= ") "; 

		}

		//Производители

		//цена от
		if(!empty($product_filter['price_to'])){

			$sql .= " AND p.price >= '" . $product_filter['price_to'] . "'"; 

		}

		//цена от

		//цена до
		if(!empty($product_filter['price_from'])){

			$sql .= " AND p.price <= '" . $product_filter['price_from'] . "'"; 

		}
		//цена до

		$query = $this->db->query($sql);
		$productsArray = array();
		foreach ($query->rows as $key => $products) {
			$productsArray[$key] =  $products['product_id'];
		}

		return $productsArray;

	}


	public function getProductInfo($product_id, $setting, $filtr_number){

		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/category');

		$product_attributes = $this->getProductAttributes($product_id);

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		$product_info_main = $this->settingRows($setting['main_fields'], $product_id, $query,$product_attributes,$setting['filtres'],$filtr_number);

		if(!empty($setting['all_fields'])){

			$product_info_all = $this->settingRows($setting['all_fields'], $product_id, $query,$product_attributes, $setting['filtres'],$filtr_number);

			foreach ($product_info_main as $k => $v) {
				$product_info[$k] = array_merge($v, $product_info_all[$k]);
			}
				
		} else{

			$product_info = $product_info_main;

		}

		return $product_info;
	}

	private function settingRows($setting, $product_id, $query, $product_attributes, $filtres, $filtr_number){

		foreach($setting as $key => $fields){

			if($fields['source'] == 'general' and $fields['field'] != '0'){
				if($fields['field'] == 'description'){
					$product_info[$product_id][$key] = array(
						'main'			=> "<![CDATA[" . $query->row[$fields['field']] . "]]>",
					);
				} else {
					$product_info[$product_id][$key] = array(
						'main'			=> $query->row[$fields['field']],
					);
				}
			} elseif($fields['source'] == 'data' and $fields['field'] != '0'){
				$product_info[$product_id][$key] = array(
					'main'			=> $query->row[$fields['field']],
				);
			} elseif($fields['source'] == 'links' and $fields['field'] != '0'){
				if($fields['field'] == 'manufacturer'){
					$product_info[$product_id][$key] = array(
						'main'			=> $product_info[$product_id][$key] = $this->db->query("SELECT DISTINCT name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . $query->row['manufacturer_id'] . "'")->row['name'],
					);
				} else {

					 $categories = $this->db->query("SELECT DISTINCT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $product_id . "'")->rows;
					 foreach ($categories as $category) {
						 foreach ($category as $cat) {
							 $categories_id[] = $cat;
						 }
					}

					if($filtres[$filtr_number]['yandex_market_category_all'] == 'on' or empty($filtres[$filtr_number]['yandex_market_category_list'])){

						$res_category = $this->model_catalog_category->getCategory($cat);

					} else {

						$res_arr = array_intersect($filtres[$filtr_number]['yandex_market_category_list'],$categories_id);

						$keys_category = array_keys($res_arr);
						$res_category = $this->model_catalog_category->getCategory($res_arr[$keys_category['0']]);

					}

					$product_info[$product_id][$key] = array(
						'main'			=> $res_category['name'],
					);


				}
			} elseif($fields['source'] == 'attribute' and $fields['field'] != '0'){
				if(!empty($product_attributes[$fields['field']])){
					$product_info[$product_id][$key] = array(
						'main'			=> $product_attributes[$fields['field']],
					);
				}
			}

			if(!empty($fields['unit']) && !empty($product_info[$product_id][$key]['main'])){

				$product_info[$product_id][$key]['unit'] = $fields['unit'];
					
			}
			
			if (!empty($fields['name_param']) && !empty($product_info[$product_id][$key]['main'])) {

				$product_info[$product_id][$key]['name_param'] = $fields['name_param'];

			} 

			if (!empty($fields['childs']) && !empty($product_info[$product_id][$key]['main'])) {
					
				$product_info[$product_id][$key]['child'] =  $this->settingRows($fields['childs'],$product_id,$query, $product_attributes, $filtres,$filtr_number);

			}

		}

		return $product_info;

	}

 	public function getProduct($product_id) {
		
		$query = $this->db->query("
		SELECT DISTINCT 
		p.*, 
		pd.*,
		GROUP_CONCAT(cd.category_id SEPARATOR ',') AS category_id,
		GROUP_CONCAT(cd.name SEPARATOR ',') AS cat_name
		FROM 
			" . DB_PREFIX . "product p 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) 
		LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = p2c.category_id) 
		WHERE 
			p.product_id = '" . (int)$product_id . "' 
		AND 
			pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		AND 
			cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		GROUP BY p.product_id ");
		
		$product_data = $query->row;
		
		return $product_data;
	}

	public function getProductAttributes($product_id){
		$attributes = array();
		
		$query = $this->db->query("
			SELECT 
				* 
			FROM 
				" . DB_PREFIX . "product_attribute pa
			WHERE 
				pa.product_id = '" . (int)$product_id . "' 
			AND 
				pa.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach($query->rows as $row){
			$attributes[$row['attribute_id']] = $row['text'];
		}
		
		return $attributes;
	}

	public function getOptionValue($option_value_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int)$option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getImages($product_id){

		$images = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . $product_id . "'");

		return $images->rows;

	}

	public function getSpecial($product_id){

		$special = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . $product_id . "' ORDER BY priority LIMIT 1");

		return $special->row;
	}

	public function getVersionModule(){

		$version = $this->db->query("SELECT DISTINCT version FROM " . DB_PREFIX . "modification WHERE code = 'Yandex_beru'");

		return $version->row['version'];

	}

	public function getProductName($product_id){

		$version = $this->db->query("SELECT DISTINCT name FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') ."'");

		return $version->row['name'];

	}
	
	public function getQuantity($product_id){

		$query = $this->db->query("SELECT DISTINCT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id . "'");

		return $query->row['quantity'];

	}

	public function getShippings(){

		$query = $this->db->query("SELECT * FROM `oc_setting` WHERE `code` LIKE 'yandex_beru_DBS'");

		return $query->row;


	}
	
	public function getProductsShipping($shipping_products, $filter){

		$sql = "SELECT DISTINCT p.product_id FROM  " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		$sql .= " LEFT JOIN  " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)"; 
		$sql .= " WHERE (p.status='1'"; 

		//Категории
		if(empty($filter['category'])){
			$sql.= 'AND p2c.category_id IS NOT NULL';
		} else {
			$sql .= " AND ("; 
			foreach ($filter['category'] as $key => $category) {
				if($key == "0"){
					$sql .= "p2c.category_id = '" . $category . "'"; 
				} else {
					$sql .= " or p2c.category_id = '" . $category . "'"; 
				}
			}
			$sql .= ") "; 
		}

		//цена от
		if(!empty($filter['price_from'])){
			$sql .= " AND p.price >= '" . $filter['price_from'] . "'"; 
		}
		//цена от

		//цена до
		if(!empty($filter['price_to'])){
			$sql .= " AND p.price <= '" . $filter['price_to'] . "'"; 
		}
		//цена до

		//кол-во от
		if(!empty($filter['quantity_from'])){
			$sql .= " AND p.quantity >= '" . $filter['quantity_from'] . "'"; 
		}
		//кол-во от

		//кол-во до
		if(!empty($filter['quantity_to'])){
			$sql .= " AND p.quantity <= '" . $filter['quantity_to'] . "'"; 
		}
		//кол-во до

		//model
		if(!empty($filter['model'])){
			$sql .= " AND p.model = '" . $filter['model'] . "'"; 
		}
		//model

		$sql .= ")";

		if(!empty($shipping_products)){
			$sql .= " OR (";
			$product_string = "";
			foreach ($shipping_products as $key => $shipping_product) {
				if($key == '0'){
					$product_string .= "p.product_id='" . $shipping_product . "'";
				} else {
					$product_string .= " or p.product_id='" . $shipping_product . "'";
				}
			}
			$sql .= $product_string . ")";
		}

		$query = $this->db->query($sql);
		$productsArray = array();
		foreach ($query->rows as $key => $products) {
			$productsArray[$key] =  $products['product_id'];
		}

		$productsArray = array_unique($productsArray);

		return $productsArray;

	}

	public function checkCustomer($telephone){

		$query = $this->db->query("SELECT DISTINCT customer_id FROM " . DB_PREFIX . "customer WHERE telephone = '" .  $this->db->escape($telephone) . "'");

		return  $query->row['customer_id'];

	}


	public function addCustomer($customer_info){

		$customer_group_id = $this->config->get('config_customer_group_id');
		
		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($customer_info['firstName']) . "', lastname = '" . $this->db->escape($customer_info['lastName']) . "', email = '', telephone = '" . $this->db->escape($customer_info['phone']) . "', custom_field = '', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', date_added = NOW()");

		return $this->db->getLastId();

	}


	public function editOrder($order_info, $customer_id){

		//$log = new Log ('cart_dbs.log');

		$shop_status_id = $this->getShopStatus($order_info['status'],$order_info['substatus'],$dbs = 1);

		$payment = $order_info['paymentMethod'];

		$this->db->query("UPDATE " . DB_PREFIX . "order SET `firstname` = '" . $order_info['buyer']['firstName'] . "', `lastname` = '" . $order_info['buyer']['lastName'] . "', `shipping_method` = '" . $order_info['delivery']['serviceName'] . "', payment_method = '" . $payment . "', order_status_id = '" . $shop_status_id . "' WHERE market_order_id='" . (int)$order_info['id'] . "'");

	}

}

