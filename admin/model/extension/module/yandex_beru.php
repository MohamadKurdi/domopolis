<?php
class ModelExtensionModuleYandexBeru extends Model {
	
	public function validateKeys($data = array()) {
		if (empty($data['yandex_beru_oauth']) || empty($data['yandex_beru_company_id'])) {
			return false;
		}

		return $this->checkAccessTokens();
	}
	
	
	
	public function checkAccessTokens() {
     
//		todo добавить валидацию токенов если это нужно
		return true;
		
    }
	
//	Получение полных данных отоваре
	
	private function getProduct($product_id) {
		
		$query = $this->db->query("
			SELECT DISTINCT 
				p.*, 
				pd.*
			FROM 
				" . DB_PREFIX . "product p 
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
			
			WHERE 
				p.product_id = '" . (int)$product_id . "' 
			AND 
				pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		$product_data = $query->row;
		
		if(!empty($product_data['manufacturer_id'])){
			$manufacturer_query = $this->db->query("
				SELECT DISTINCT 
					m.name 
				FROM 
					" . DB_PREFIX . "manufacturer m
				WHERE 
					m.manufacturer_id = '" . (int)$product_data['manufacturer_id'] . "'");
			
			if($manufacturer_query->num_rows){
				$product_data['manufacturer'] = $manufacturer_query->row['name'];
			}else{
				$product_data['manufacturer'] = '';
			}
		}
		
		$category_query  =  $this->db->query("
				SELECT DISTINCT 
					cd.name 
				FROM 
					" . DB_PREFIX . "product_to_category p2c
				LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = p2c.category_id) 
				
				WHERE 
					p2c.product_id = '" . (int)$product_id . "'
				AND 
					cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if($category_query->num_rows){
			$product_data['category'] = $category_query->row['name'];
		}else{
			$product_data['category'] = '';
		}
		
		if(!empty($product_data['weight'])){
			$product_data['weight'] = $this->weight->convert($product_data['weight'], $product_data['weight_class_id'], $this->config->get('yandex_beru_weight_kg'));
		}
		if(!empty($product_data['length'])){
			$product_data['length'] = $this->length->convert($product_data['length'], $product_data['length_class_id'], $this->config->get('yandex_beru_length_cm'));
		}
		if(!empty($product_data['width'])){
			$product_data['width'] = $this->length->convert($product_data['width'], $product_data['length_class_id'], $this->config->get('yandex_beru_length_cm'));
		}
		if(!empty($product_data['height'])){
			$product_data['height'] = $this->length->convert($product_data['height'], $product_data['length_class_id'], $this->config->get('yandex_beru_length_cm'));
		}
		return $product_data;
	}
	
//	Получение значений аттрибутов товара
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
	
	public function getProductCategory($product_id) {

		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' LIMIT 1");

		if($query->num_rows){
			return $query->row['category_id'];
		}else{
			return false;
		}
	}
	
	public function getSourceFields($data = array()) {
		
		if (!empty($data['source'])) {
			switch ($data['source']) {
				case 'general':
					return $this->getSourceGeneralFields();
					break;
				case 'data':
					return $this->getSourceDataFields();
					break;
				case 'links':
					return $this->getSourceLinksFields();
					break;
				case 'attribute':
					return $this->getSourceAttributeFields();
					break;
				case 'option':
					return $this->getSourceOptionFields();
					break;
				default :
					return array();
					break;
			}
		} else {
			return array();
		}
	}

	private function getSourceGeneralFields() {
		$fields = [
			[
				'key'	=>	'name',
				'name'	=>	'Название товара'
			],
			[
				'key'	=>	'description',
				'name'	=>	'Описание'
			],
			[
				'key'	=>	'meta_title',
				'name'	=>	'Мета-тег Title'
			],
			[
				'key'	=>	'meta_description',
				'name'	=>	'Мета-тег Description'
			],
			[
				'key'	=>	'meta_keyword',
				'name'	=>	'Мета-тег Keyword'
			],
			[
				'key'	=>	'tag',
				'name'	=>	'Теги товара'
			],
		];
		return $fields;
	}

	private function getSourceDataFields() {
		$fields = [
			[
				'key'	=>	'model',
				'name'	=>	'Модель'
			],
			[
				'key'	=>	'sku',
				'name'	=>	'Артикул'
			],
			[
				'key'	=>	'upc',
				'name'	=>	'UPC'
			],
			[
				'key'	=>	'ean',
				'name'	=>	'EAN'
			],
			[
				'key'	=>	'jan',
				'name'	=>	'JAN'
			],
			[
				'key'	=>	'isbn',
				'name'	=>	'ISBN'
			],
			[
				'key'	=>	'mpn',
				'name'	=>	'MPN'
			],
			[
				'key'	=>	'location',
				'name'	=>	'Расположение'
			],
			[
				'key'	=>	'price',
				'name'	=>	'Цена'
			],
			[
				'key'	=>	'quantity',
				'name'	=>	'Количество'
			],
			[
				'key'	=>	'minimum',
				'name'	=>	'Минимальное количество'
			],
			[
				'key'	=>	'shipping',
				'name'	=>	'Необходима доставка'
			],
			[
				'key'	=>	'date_available',
				'name'	=>	'Дата поступления'
			],
			[
				'key'	=>	'length',
				'name'	=>	'Размеры (Длинна)'
			],
			[
				'key'	=>	'width',
				'name'	=>	'Размеры (Ширина)'
			],
			[
				'key'	=>	'height',
				'name'	=>	'Размеры (Высота)'
			],
			[
				'key'	=>	'weight',
				'name'	=>	'Вес'
			],
		];
		return $fields;
	}

	private function getSourceLinksFields() {
		$fields = [
			[
				'key'	=>	'manufacturer',
				'name'	=>	'Производитель'
			],
			[
				'key'	=>	'category',
				'name'	=>	'Категория'
			],
		];
		return $fields;
	}

	public function getSourceAttributeFields() {
		$this->load->model('catalog/attribute');
		
		$fields = array();
		
		$attributes  = $this->model_catalog_attribute->getAttributes();
		
		foreach ($attributes as $attribute) {
			$fields[] = [
				'key'	=>	$attribute['attribute_id'],
				'name'	=>	$attribute['name'],
			];
		}
		return $fields;
	}
	public function getSourceOptionFields() {
		$this->load->model('catalog/option');
		
		$fields = array();
		
		$options  = $this->model_catalog_option->getOptions();
		
		foreach ($options as $option) {
			$fields[] = [
				'key'	=>	$option['option_id'],
				'name'	=>	$option['name'],
			];
		}
		return $fields;
	}
	
	//	Product groups
	public function addProductGroup($data) {
		$sql = "INSERT INTO " . DB_PREFIX . "yb_product_group SET name = '" . $this->db->escape($data['name']) . "', filter_name = '" . $this->db->escape($data['filter_name']) . "', filter_model = '" . $this->db->escape($data['filter_model']) . "'";

		if (isset($data['filter_category']) && $data['filter_category'] !== '') {
			$sql .= ", filter_category = '" . json_encode($data['filter_category']) . "'";
		} else {
			$sql .= ", filter_category = 'null'";
		}
		
		if (isset($data['filter_product']) && $data['filter_product'] !== '') {
			$sql .= ", filter_product = '" . json_encode($data['filter_product']) . "'";
		} else {
			$sql .= ", filter_product = 'null'";
		}

		if (isset($data['filter_option']) && $data['filter_option'] !== '') {
			$sql .= ", filter_option = '" . (float)$data['filter_option'] . "'";
		} else {
			$sql .= ", filter_option = 'null'";
		}

		if (isset($data['filter_price_from']) && $data['filter_price_from'] !== '') {
			$sql .= ", filter_price_from = '" . (float)$data['filter_price_from'] . "'";
		}

		if (isset($data['filter_price_to']) && $data['filter_price_to'] !== '') {
			$sql .= ", filter_price_to = '" . (float)$data['filter_price_to'] . "'";
		}

		if (isset($data['filter_quantity_from']) && $data['filter_quantity_from'] !== '') {
			$sql .= ", filter_quantity_from = '" . (int)$data['filter_quantity_from'] . "'";
		}

		if (isset($data['filter_quantity_to']) && $data['filter_quantity_to'] !== '') {
			$sql .= ", filter_quantity_to = '" . (int)$data['filter_quantity_to'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= ", filter_status = '" . (int)$data['filter_status'] . "'";
		}

		$this->db->query($sql);

		$group_id = $this->db->getLastId();

		$filtered_products = $this->getProductIdsByFilters($data);

		foreach ($filtered_products as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "yb_product_to_product_group SET product_id = '" . (int)$product['product_id'] . "', group_id = '" . (int)$group_id . "'");
		}
	}

	public function editProductGroup($group_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "yb_product_group SET name = '" . $this->db->escape($data['name']) . "', filter_name = '" . $this->db->escape($data['filter_name']) . "', filter_model = '" . $this->db->escape($data['filter_model']) . "'";

		if (isset($data['filter_category']) && $data['filter_category'] !== '') {
			$sql .= ", filter_category = '" . json_encode($data['filter_category']) . "'";
		} else {
			$sql .= ", filter_category = 'null'";
		}
		
		if (isset($data['filter_product']) && $data['filter_product'] !== '') {
			$sql .= ", filter_product = '" . json_encode($data['filter_product']) . "'";
		} else {
			$sql .= ", filter_product = 'null'";
		}

		if (isset($data['filter_option']) && $data['filter_option'] !== '') {
			$sql .= ", filter_option = '" . (float)$data['filter_option'] . "'";
		} else {
			$sql .= ", filter_option = 'null'";
		}

		if (isset($data['filter_price_from']) && $data['filter_price_from'] !== '') {
			$sql .= ", filter_price_from = '" . (float)$data['filter_price_from'] . "'";
		} else {
			$sql .= ", filter_price_from = NULL";
		}

		if (isset($data['filter_price_to']) && $data['filter_price_to'] !== '') {
			$sql .= ", filter_price_to = '" . (float)$data['filter_price_to'] . "'";
		} else {
			$sql .= ", filter_price_to = NULL";
		}

		if (isset($data['filter_quantity_from']) && $data['filter_quantity_from'] !== '') {
			$sql .= ", filter_quantity_from = '" . (int)$data['filter_quantity_from'] . "'";
		} else {
			$sql .= ", filter_quantity_from = NULL";
		}

		if (isset($data['filter_quantity_to']) && $data['filter_quantity_to'] !== '') {
			$sql .= ", filter_quantity_to = '" . (int)$data['filter_quantity_to'] . "'";
		} else {
			$sql .= ", filter_quantity_to = NULL";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= ", filter_status = '" . (int)$data['filter_status'] . "'";
		} else {
			$sql .= ", filter_status = NULL";
		}

		$sql .= " WHERE group_id = '" . (int)$group_id . "'";

		$this->db->query($sql);

		$this->db->query("DELETE FROM " . DB_PREFIX . "yb_product_to_product_group WHERE group_id = '" . (int)$group_id . "'");

		$filtered_products = $this->getProductIdsByFilters($data);

		foreach ($filtered_products as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "yb_product_to_product_group SET product_id = '" . (int)$product['product_id'] . "', group_id = '" . (int)$group_id . "'");
		}
	}

	public function deleteProductGroup($group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "yb_product_group WHERE group_id = '" . (int)$group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "yb_product_to_product_group WHERE group_id = '" . (int)$group_id . "'");
	}

	public function getProductGroup($group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "yb_product_group WHERE group_id = '" . (int)$group_id . "'");

		return $query->row;
	}

	public function getProductGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "yb_product_group ORDER BY group_id DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalProductGroups() {
		$query = $this->db->query("SELECT COUNT(DISTINCT group_id) AS total FROM " . DB_PREFIX . "yb_product_group");

		return $query->row['total'];
	}
	// 	/Product groups

	// Products in groups
	public function getProductsFromGroup($group_id) {
		$group_product_data = array();

		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "yb_product_to_product_group WHERE group_id = '" . (int)$group_id . "'");

		foreach ($query->rows as $result) {
			$group_product_data[] = $result['product_id'];
		}

		return $group_product_data;
	}
	
	// Products in groups
	public function getProductsFromGroups($groups = array(), $data = array()) {
		$groups = array_map('intval', $groups);
		$groups_str = implode(",", $groups);
		
		$group_product_data = array();
		
		$sql = "SELECT product_id FROM " . DB_PREFIX . "yb_product_to_product_group WHERE group_id IN (" . $this->db->escape($groups_str) . ") GROUP BY product_id";
	
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$group_product_data[] = $result['product_id'];
		}

		return $group_product_data;
	}
	
	public function getProductsByFilters($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_option po ON (p.product_id = po.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
//		if (!empty($data['filter_product'])) {
//			$sql .= "AND ( 1 ";
//		}
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " AND p2c.category_id IN ('". implode("','", array_map('intval', $data['filter_category'])) . "')";
		}

		if (!empty($data['filter_option'])) {
			$sql .= " AND po.option_id IN ('". implode("','", array_map('intval', $data['filter_option'])) . "')";
		}

		if (isset($data['filter_price_from']) && $data['filter_price_from'] !== '') {
			$sql .= " AND p.price >= '" . (float)($data['filter_price_from']) . "'";
		}

		if (isset($data['filter_price_to']) && $data['filter_price_to'] !== '') {
			$sql .= " AND p.price <= '" . (float)($data['filter_price_to']) . "'";
		}

		if (isset($data['filter_quantity_from']) && $data['filter_quantity_from'] !== '') {
			$sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_from'] . "'";
		}

		if (isset($data['filter_quantity_to']) && $data['filter_quantity_to'] !== '') {
			$sql .= " AND p.quantity <= '" . (int)$data['filter_quantity_to'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

//		if (!empty($data['filter_product'])) {
//			$sql .= ") OR p.product_id IN ('". implode("','", array_map('intval', $data['filter_product'])) . "')";
//		}
		
		if (!empty($data['filter_product'])) {
			$sql .= " AND p.product_id IN ('". implode("','", array_map('intval', $data['filter_product'])) . "')";
		}
		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalProductsByFilters($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_option po ON (p.product_id = po.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
//		if (!empty($data['filter_product'])) {
//			$sql .= "AND ( 1 ";
//		}
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " AND p2c.category_id IN ('". implode("','", array_map('intval', $data['filter_category'])) . "')";
		}
		
		if (!empty($data['filter_option'])) {
			$sql .= " AND po.option_id IN ('". implode("','", array_map('intval', $data['filter_option'])) . "')";
		}

		if (isset($data['filter_price_from']) && $data['filter_price_from'] !== '') {
			$sql .= " AND p.price >= '" . (float)($data['filter_price_from']) . "'";
		}

		if (isset($data['filter_price_to']) && $data['filter_price_to'] !== '') {
			$sql .= " AND p.price <= '" . (float)($data['filter_price_to']) . "'";
		}

		if (isset($data['filter_quantity_from']) && $data['filter_quantity_from'] !== '') {
			$sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_from'] . "'";
		}

		if (isset($data['filter_quantity_to']) && $data['filter_quantity_to'] !== '') {
			$sql .= " AND p.quantity <= '" . (int)$data['filter_quantity_to'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
//		if (!empty($data['filter_product'])) {
//			$sql .= ") OR p.product_id IN ('". implode("','", array_map('intval', $data['filter_product'])) . "')";
//		}
		if (!empty($data['filter_product'])) {
			$sql .= " AND p.product_id IN ('". implode("','", array_map('intval', $data['filter_product'])) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProductIdsByFilters($data = array()) {
		$sql = "SELECT DISTINCT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_option po ON (p.product_id = po.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

//		if (!empty($data['filter_product'])) {
//			$sql .= "AND ( 1 ";
//		}
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " AND p2c.category_id IN ('". implode("','", array_map('intval', $data['filter_category'])) . "')";
		}
		
		if (!empty($data['filter_option'])) {
			$sql .= " AND po.option_id IN ('". implode("','", array_map('intval', $data['filter_option'])) . "')";
		}


		if (isset($data['filter_price_from']) && $data['filter_price_from'] !== '') {
			$sql .= " AND p.price >= '" . (float)($data['filter_price_from']) . "'";
		}

		if (isset($data['filter_price_to']) && $data['filter_price_to'] !== '') {
			$sql .= " AND p.price <= '" . (float)($data['filter_price_to']) . "'";
		}

		if (isset($data['filter_quantity_from']) && $data['filter_quantity_from'] !== '') {
			$sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_from'] . "'";
		}

		if (isset($data['filter_quantity_to']) && $data['filter_quantity_to'] !== '') {
			$sql .= " AND p.quantity <= '" . (int)$data['filter_quantity_to'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
//		if (!empty($data['filter_product'])) {
//			$sql .= ") OR p.product_id IN ('". implode("','", array_map('intval', $data['filter_product'])) . "')";
//		}
		
		if (!empty($data['filter_product'])) {
			$sql .= " AND p.product_id IN ('". implode("','", array_map('intval', $data['filter_product'])) . "')";
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	// /Products in groups

	public function getOptionList() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;

	}
	
	public function getPrimaryOptionsCombinations($product_id){


		$product_options = $this->getPrimaryProductoptions($product_id);
		
		$primary_options_combinations = array();
		
		$this->getPrimaryOptionCombinations($product_options, $primary_options_combinations);

		
		if($primary_options_combinations){
			return $primary_options_combinations;
		}else{
			return array("");
		}
		
		
	}
	
	public function getPrimaryOptionCombinations($product_options, &$primary_options_combinations, $prefix = ""){
		
		if($product_options){
			$product_option = array_shift($product_options);
			
			foreach($product_option['option_values'] as $option_value){
				$prefix_new = $prefix.'-'.$product_option['option_id'].'-'.$option_value;
				
				$this->getPrimaryOptionCombinations($product_options, $primary_options_combinations,$prefix_new);
			}
		}else{
			$primary_options_combinations[] = $prefix;
		}
	}
	
	public function getPrimaryProductoptions($product_id){
		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po WHERE po.product_id = '" . (int)$product_id . "' AND po.required = 1 ORDER BY option_id ASC");
		
		$product_options = array();
		foreach ($product_option_query->rows as $product_option) {
			$option_values = array();
			
			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$option_values[] = $product_option_value['option_value_id'];
			}
			if($option_values){
				$product_options[] = [
					'option_id' => $product_option['option_id'],
					'option_values' => $option_values
				];
			}
		}

		


		return $product_options;
	}
	
	public function getOffer($shopSku){
		
		$query = $this->db->query("SELECT DISTINCT *, status AS beru_status FROM " . DB_PREFIX . "yb_offers WHERE shopSku = '" . $this->db->escape($shopSku) . "'");	
		
		return $query->row;
	}
	public function getOfferByKey($key){
		
		$query = $this->db->query("SELECT DISTINCT *, status AS beru_status FROM " . DB_PREFIX . "yb_offers WHERE `key` = '" . $this->db->escape($key) . "'");
		
		return $query->row;
	}
//	Добавление сохраненных предложений
	public function addOffer($offer_data){
		$sql = "
			INSERT INTO " . DB_PREFIX . "yb_offers 
			SET 
				`key` = '" . $this->db->escape($offer_data['key']) . "',
				`yandex_sku` = '" . $this->db->escape(isset($offer_data['marketSku'])?$offer_data['marketSku']:'') . "',
				`yandex_category` = '" . $this->db->escape(isset($offer_data['marketCategoryId'])?$offer_data['marketCategoryId']:''). "',
				`marketSkuName` = '" . $this->db->escape(isset($offer_data['marketSkuName'])?$offer_data['marketSkuName']:'') . "',
				`marketCategoryName` = '" . $this->db->escape(isset($offer_data['marketCategoryName'])?$offer_data['marketCategoryName']:'') . "', 
				`status` = '',
				`shopSku` = '" . $this->db->escape(isset($offer_data['shopSku'])?$offer_data['shopSku']:$offer_data['key']) . "'";
					
		$this->db->query($sql);
	}
	
//	Обновление сохраненных предложений
	public function updateOffer($offer_data){
		$sql = "
			UPDATE " . DB_PREFIX . "yb_offers 
			SET 
				`yandex_sku` = '" . $this->db->escape(isset($offer_data['marketSku'])?$offer_data['marketSku']:'') . "',
				`yandex_category` = '" . $this->db->escape(isset($offer_data['marketCategoryId'])?$offer_data['marketCategoryId']:''). "',
				`marketSkuName` = '" . $this->db->escape(isset($offer_data['marketSkuName'])?$offer_data['marketSkuName']:'') . "',
				`marketCategoryName` = '" . $this->db->escape(isset($offer_data['marketCategoryName'])?$offer_data['marketCategoryName']:'') . "'";
		
		if(!empty($offer_data['status'])){
			$sql .= " ,`status` = '".$this->db->escape($offer_data['status'])."'";
		}
		
		$sql .= "WHERE `shopSku` = '" . $this->db->escape($offer_data['shopSku']) . "'";
					
		$this->db->query($sql);
	}
	
	public function updateOfferShopSku($key, $shopSku){
		$this->db->query("UPDATE " . DB_PREFIX . "yb_offers SET `shopSku` = '" . $this->db->escape($shopSku) . "' WHERE `key` = '" . $this->db->escape($key) . "'");
	}
	
	public function getOffers($data = array()){
		
		$sql = "SELECT DISTINCT o.shopSku FROM " . DB_PREFIX . "yb_offers o WHERE 1 ";
		
		if (!empty($data['filter_shopSku'])) {
			$sql .= " AND o.shopSku LIKE '%" . $this->db->escape($data['filter_shopSku']) . "%'";
		}
		
		if (!empty($data['filter_marketSkuName'])) {
			$sql .= " AND o.marketSkuName LIKE '%" . $this->db->escape($data['filter_marketSkuName']) . "%'";
		}
		
		if (!empty($data['filter_status'])) {
			$sql .= " AND o.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
		
		if(!empty($data['filter_loaded'])){
			$sql .= " AND o.status != ''";
//			READY — товар прошел модерацию.
//			IN_WORK — товар проходит модерацию.
//			NEED_CONTENT — для товара без SKU на Яндексе market-sku / marketSku нужно найти карточку самостоятельно или создать ее.
//			NEED_INFO — товар не прошел модерацию из-за ошибок или недостающих сведений в описании товара.
//			REJECTED — товар не прошел модерацию, так как Беру не планирует размещать подобные товары.
//			SUSPENDED — товар не прошел модерацию, так как Беру пока не размещает подобные товары.
//			OTHER — товар не прошел модерацию по другой причине.
		}

		
		
		if (isset($data['filter_price_from'])) {

			$sql .= " AND o.offer_price >= " . (float)$data['filter_price_from'] . " ";
		}
		
		if (isset($data['filter_price_to'])) {
			$sql .= " AND o.offer_price <= " . (float)$data['filter_price_to'] . " ";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if (empty($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}

			if (empty($data['limit']) || $data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotalOffers($data = array()){
		$sql = "SELECT COUNT(DISTINCT o.shopSku) AS total FROM " . DB_PREFIX . "yb_offers o WHERE 1 ";
		
		if (!empty($data['filter_shopSku'])) {
			$sql .= " AND o.shopSku LIKE '%" . $this->db->escape($data['filter_shopSku']) . "%'";
		}
		
		if (!empty($data['filter_marketSkuName'])) {
			$sql .= " AND o.marketSkuName LIKE '%" . $this->db->escape($data['filter_marketSkuName']) . "%'";
		}
		
		if (!empty($data['filter_status'])) {
			$sql .= " AND o.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
		
		if(!empty($data['filter_loaded'])){
			$sql .= " AND o.status != ''";
		}
		
		if (isset($data['filter_price_from'])) {
			$sql .= " AND o.offer_price >= " . (float)$data['filter_price_from'] . " ";
		}
		
		if (isset($data['filter_price_to'])) {
			$sql .= " AND o.offer_price <= " . (float)$data['filter_price_to'] . " ";
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
//	обновление статуса предложения
	public function updateOfferStatus($shopSku, $status){
		$this->db->query("UPDATE " . DB_PREFIX . "yb_offers SET status = '". $this->db->escape($status)."' WHERE shopSku = '" . $this->db->escape($shopSku) . "'");
	}
	
	public function getFullOfferInfo($id, $filter_data = array(), $type = 'shopSku'){
		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/option');
		
		if($type == 'shopSku'){
			$offer_info = $this->getOffer($id);
		}else{
			$offer_info = $this->getOfferByKey($id);
		}
		
		if($offer_info){
			$offer_key_data = explode('-',$offer_info['key']);
		}else{
			$offer_key_data = explode('-',$id);
		}
		
		$product_id = array_shift($offer_key_data);

		$options_text = '';
		
		$product_options = array();
		
		if(!empty($offer_key_data)){
			
			$options = array_chunk($offer_key_data, 2);
			
			foreach($options as $option){
				
				$option_value = $this->model_catalog_option->getOptionValue($option[1]);
				$options_text .= ' ' . $option_value['name'];
				
				$product_options[$option[1]] = $option_value;
			}
			
		}
		
		$offer_data = array();
		
		$product_info = $this->getProduct($product_id);
		
		$product_info['name'] = $product_info['name'].$options_text;
		
		$product_attributes = $this->getProductAttributes($product_id);
		
		foreach($filter_data as $filter_data_row){
			if(array_key_exists($filter_data_row, $product_info)){
				$offer_data[$filter_data_row] = $product_info[$filter_data_row];
			}elseif(array_key_exists($filter_data_row, $offer_info)){
				$offer_data[$filter_data_row] = $offer_info[$filter_data_row];
			}else{
//				Необходимо получить данные о товаре через 
				$fieldsets = $this->config->get('yandex_beru_fieldsets');
				
//				Проверяем задано ли в сопоставлении полей 
				if(array_key_exists($filter_data_row, $fieldsets)){
//					Если source не задан значит это массив
					if(isset($fieldsets[$filter_data_row]['source'])){
						
						switch ($fieldsets[$filter_data_row]['source']) {
							case 'general':
							case 'data':
							case 'links':
								$offer_data[$filter_data_row] = isset($product_info[$fieldsets[$filter_data_row]['field']])?$product_info[$fieldsets[$filter_data_row]['field']]:"";
								break;
							case 'attribute':
								$offer_data[$filter_data_row] = isset($product_attributes[$fieldsets[$filter_data_row]['field']])?$product_attributes[$fieldsets[$filter_data_row]['field']]:"";
								break;
							case 'option':
								$offer_data[$filter_data_row] = isset($product_options[$fieldsets[$filter_data_row]['field']])?$product_options[$fieldsets[$filter_data_row]['field']]:"";
								break;
							default :
								break;
						}
					}else{
						foreach($fieldsets[$filter_data_row] as $key => $filter_data_row_item){
							switch ($filter_data_row_item['source']) {
								case 'general':
								case 'data':
								case 'links':
									$offer_data[$filter_data_row][$key] = isset($product_info[$filter_data_row_item['field']])?$product_info[$filter_data_row_item['field']]:"";
									break;
								case 'attribute':
									$offer_data[$filter_data_row][$key] = isset($product_attributes[$filter_data_row_item['field']])?$product_attributes[$filter_data_row_item['field']]:"";
									break;
								case 'option':
									$offer_data[$filter_data_row][$key] = isset($product_options[$filter_data_row_item['field']])?$product_options[$filter_data_row_item['field']]:"";
									break;
								default :
									break;
							}	
						}
					}
				}else{
					$offer_data[$filter_data_row] = '';
				}
			
			}	
		}
		
		return $offer_data;
	}

	public function updatePrice($price, $shopSku){

		$sql =  $this->db->query("UPDATE " . DB_PREFIX . "yb_offers SET offer_price = '" . $price . "' WHERE shopSku =  '" . $shopSku . "'");

		
	}

	public function updateRecomendPrice($marketSku, $priceSuggestion){

		$sql = '';

		foreach ($priceSuggestion as $price) {
			
			switch ($price['type']) {
				case "MIN_PRICE_MARKET":
					$sql .= "minPriceOnBeru = '" . $price['price'] . "', ";
					break;
				case "BUYBOX":
					$sql .= "byboxPriceOnBeru = '" . $price['price'] . "', ";
					break;
				case "DEFAULT_OFFER":
					$sql .= "defaultPriceOnBeru = '" . $price['price'] . "', ";
					break;
				case "MAX_DISCOUNT_BASE":
					$sql .= "maxPriceOnBeru = '" . $price['price'] . "', ";
					break;		
				case "MARKET_OUTLIER_PRICE":
					$sql .= "outlierPrice = '" . $price['price'] . "', ";
					break;						
			}

		}

		if($sql != ""){
			$sql = substr($sql,0,-2);

			$this->db->query("UPDATE " . DB_PREFIX . "yb_offers SET " . $sql . " WHERE yandex_sku = '" . $marketSku . "'");
		}
		
	}


	public function logPrice($data){

		foreach ($data as $offer) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "yb_offers WHERE yandex_sku = '" . $offer['marketSku'] . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "yb_history_price SET user = '" . (int)$this->user->getId() . "', price = '" . $this->db->escape($offer['price']['value']) . "', offer_id = '" . $this->db->escape($query->row['shopSku']) . "', offer_name = '" . $this->db->escape($query->row['marketSkuName']) . "',  date_update =  NOW()");

		}

	}

	public function getHistoryPrice($data){

		$sql ="SELECT * FROM " . DB_PREFIX . "yb_history_price yhb LEFT JOIN " . DB_PREFIX . "user u ON(yhb.user = u.user_id) LEFT JOIN " . DB_PREFIX . "yb_offers yo ON(yhb.offer_id = yo.shopSku) WHERE 1";


		if (isset($data['filter_date_form'])) {

			$sql .= " AND yhb.date_update >= '" . $data['filter_date_form'] . "' ";
		}
		
		if (isset($data['filter_date_to'])) {
			$sql .= " AND yhb.date_update <= '" . $data['filter_date_to'] . "' ";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if (empty($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}

			if (empty($data['limit']) || $data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);

		return $query->rows;

	}

	public function getTotalHistoryPrice($data = array()){

		$sql = "SELECT COUNT(yhp.offer_id) AS total FROM " . DB_PREFIX . "yb_history_price yhp WHERE 1 ";
		
		if (isset($data['filter_date_form'])) {
			$sql .= " AND yhp.date_update >= '" . $data['filter_date_form'] . "' ";
		}
		
		if (isset($data['filter_date_to'])) {
			$sql .= " AND yhp.date_update <= '" . $data['filter_date_to'] . "' ";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	
	public function findOfferErrors($shopSku, $type = 'shopSku'){
		$errors = array();
		
		$filter_data = ['shopSku','name','category','manufacturer','manufacturerCountries','vendor','image'];
		$offer_data = $this->getFullOfferInfo($shopSku, $filter_data, $type);
		
		foreach($filter_data as $field){
			if(empty($offer_data[$field])){
				$errors[] = 'error_'.$field;
			}
		}
		
		return $errors;
	}
	
	public function getUpdatesOfferInfo($shopSku){
		$fieldsets = $this->config->get('yandex_beru_fieldsets');

		$filter_data = ['shopSku','name','category','yandex_sku','image'];
		foreach($fieldsets as $key => $fieldset){
			$filter_data[] = $key;
		}
		
		
		//Текущие данные о товаре хранящиеся в базе
		$offer_data = $this->getFullOfferInfo($shopSku, $filter_data);
		
		//Дополняем данные информацией пришедшей с рекомендаций. 
		$this->api = new yandex_beru();			
		$this->api->setAuth($this->config->get('yandex_beru_oauth'),$this->config->get('yandex_beru_auth_token'),$this->config->get('yandex_beru_company_id'));
		
		$component = $this->api->loadComponent('offerMappingEntriesSuggestions');
		
		$post_data['offers'][0] = $this->getFullOfferInfo($shopSku,["shopSku","name","category","vendor"], 'shopSku');
		
		$component->setData($post_data);
		
		$response = $this->api->sendData($component);
						
		if(is_array($response)){
//			верные данные всегда массив, ошибки строка.
//			По вернувшимся предложениям обновляем таблицу					
			if(isset($response['result']['offers']['0'])){
				$response_offer = $response['result']['offers']['0'];
								
				$offer_data['shopSku'] = isset($response_offer['shopSku'])?$response_offer['shopSku']:'';
				$offer_data['yandex_sku'] = isset($response_offer['marketSku'])?$response_offer['marketSku']:'';
				$offer_data['marketSkuName'] = isset($response_offer['marketSkuName'])?$response_offer['marketSkuName']:'';
				$offer_data['marketCategoryName'] = isset($response_offer['marketCategoryName'])?$response_offer['marketCategoryName']:'';			
			}
		}
		
		$offer_data['manufacturerCountries'] = [$offer_data['manufacturerCountries']];
		$marketSku = $offer_data['yandex_sku'];
		
//		unset($offer_data['yandex_sku']);
		
		if($offer_data['image']){
			$offer_data['urls'][] = HTTPS_CATALOG.'images/'.$offer_data['image'];
		}
//		unset($offer_data['image']);
		
		$offerMappingEntrie = [
			'offer' => $offer_data,
			'mapping' => [
				'marketSku' => $marketSku
			]	 
		];
		
		return $offerMappingEntrie;
	}
//	Упаковки для заказа
	public function gerOrderBoxes($order_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "yb_order_boxes WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}
	public function setOrderBoxes($order_id, $boxes){
		$this->db->query("DELETE FROM " . DB_PREFIX . "yb_order_boxes WHERE order_id = '" . (int)$order_id . "'");
		foreach($boxes as $box){
			$this->db->query("INSERT INTO " . DB_PREFIX . "yb_order_boxes SET order_id = '" . (int)$order_id . "', depth = '" . (int)$box['depth'] . "', width = '" . (int)$box['width'] . "', height = '" . (int)$box['height'] . "', weight = '" . (int)$box['weight'] . "', market_box_id = '".(int)$box['id']."', fulfilmentId = '".$this->db->escape($box['fulfilmentId'])."'");
		}
		return true;
	}
	public function gerOrderShipmentId($order_id){
		$query = $this->db->query("SELECT `shipment_id` FROM " . DB_PREFIX . "order WHERE `order_id` = '" . (int)$order_id . "'");
		
		if($query->num_rows){
			return $query->row['shipment_id'];
		}else{
			return false;
		} 
	}
	public function gerMarketOrderId($order_id){
		$query = $this->db->query("SELECT `market_order_id` FROM " . DB_PREFIX . "order WHERE `order_id` = '" . (int)$order_id . "'");
		
		if($query->num_rows){
			return $query->row['market_order_id'];
		}else{
			return false;
		} 
	}
	
	public function getMarketOrderType($order_id){
		$query = $this->db->query("SELECT `shipment_scheme` FROM " . DB_PREFIX . "order WHERE `order_id` = '" . (int)$order_id . "'");
		
		if($query->num_rows){
			return $query->row['shipment_scheme'];
		}else{
			return false;
		} 
	}
	
	public function getOrderShipmentDate($order_id){
		$query = $this->db->query("SELECT `shipment_date` FROM " . DB_PREFIX . "order WHERE `order_id` = '" . (int)$order_id . "'");
		
		if($query->num_rows){
			return $query->row['shipment_date'];
		}else{
			return false;
		} 
	}

	public function delete($module_id){

		$this->db->query("DELETE FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");

	}

	public function getLastYandex(){

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "module WHERE code = 'yandex_market' ORDER BY module_id DESC LIMIT 1");

		if ($query->row) {
			return json_decode($query->row['setting'], true);
		} else {
			return array();
		}

		return $query->row;

	}


	public function addShipping($data){

		$json = json_encode($data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "yb_shipping_dbs SET setting = '" . $this->db->escape($json) . "'");

	}

	public function editShipping($data, $shipping_id){

		$json = json_encode($data);

		$this->db->query("UPDATE " . DB_PREFIX . "yb_shipping_dbs SET `setting` = '" . $this->db->escape($json) . "' WHERE `shipping_id` = '" . $shipping_id . "'");

	}

	public function getShippings($shipping_id){
		

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "yb_shipping_dbs WHERE `shipping_id` = '" . $shipping_id . "'");

		return $query->row;

	}

	public function getProducts($filter, $shipping_products){

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
					$product_string .= "p.product_id='" . $shipping_product['product_id'] . "'";
				} else {
					$product_string .= " or p.product_id='" . $shipping_product['product_id'] . "'";
				}
			}
			$sql .= $product_string . ")";
		}

		if (isset($filter['start'])) {
			if ($filter['start'] < 0) {
				$filter['start'] = 0;
			}

		}

		$sql .= "LIMIT " . (int)$filter['start'] . "," . (int)$this->config->get('config_limit_admin');

		$query = $this->db->query($sql);
		$productsArray = array();
		foreach ($query->rows as $key => $products) {
			$productsArray[$key] =  $products['product_id'];
		}

		$productsArray = array_unique($productsArray);

		return $productsArray;

	}

	public function getTotalProducts($filter, $shipping_products){


		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM  " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
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
					$product_string .= "p.product_id='" . $shipping_product['product_id'] . "'";
				} else {
					$product_string .= " or p.product_id='" . $shipping_product['product_id'] . "'";
				}
			}
			$sql .= $product_string . ")";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];

	}

	public function addRegion($region_info){

		print_r('<pre>');

			print_r($region_info);

		print_r('<pre>');

		if(!empty($region_info['parent'])){

			$chek_region =$this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "yb_regions WHERE `region_id` = '" . (int)$region_info['id'] . "'");

			if(empty($chek_region->rows)){

				$sql = "INSERT INTO " . DB_PREFIX . "yb_regions SET region_id = '" . (int)$region_info['id'] . "', name = '" . $this->db->escape($region_info['name']) . "', type = '" . $this->db->escape($region_info['type']) . "', parent = '" . (int)$region_info['parent']['id'] . "'";

				$query = $this->db->query($sql);

			}

			$this->addRegion($region_info['parent']);

		} else {

			$chek_region =$this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "yb_regions WHERE `region_id` = '" . (int)$region_info['id'] . "'");
			
			if(empty($chek_region->rows)){
				$sql = "INSERT INTO " . DB_PREFIX . "yb_regions SET region_id = '" . (int)$region_info['id'] . "', name = '" . $this->db->escape($region_info['name']) . "', type = '" . $this->db->escape($region_info['type']) . "'";

				$query = $this->db->query($sql);

			}

		}


	}


	public function getShippingZone(){

		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "yb_regions WHERE `type` = 'REPUBLIC' or `name` = 'Москва'";

		$republics = $this->db->query($sql)->rows;

		foreach ($republics as $key => $republic) {

			$republic_info[$key]['name'] = $republic['name'];
			$republic_info[$key]['id'] = $republic['region_id'];
		
			$name_parent = $this->getParentRegion($republic['parent']);

			$republic_info[$key]['name'] .= $name_parent;
		
		}

		return $republic_info;

	}

	public function getParentRegion($parent_id, $name =''){

		$parent_region =  $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "yb_regions WHERE `region_id` = '" . $parent_id ."'")->rows;

		if(!empty($parent_region)){

			$name .= ", " . $parent_region['0']['name'];

			$test = $this->getParentRegion($parent_region['0']['parent'], $name);

			return $test;

		} else {

			return $name;

		}

	}


	public function addDeliveryService($deliveryService){

		$this->db->query("TRUNCATE " . DB_PREFIX . "yb_deliveryService");

		foreach ($deliveryService as $service) {

			$sql = "INSERT INTO " . DB_PREFIX . "yb_deliveryService SET service_id = '" . (int)$service['id'] . "', name = '" . $this->db->escape($service['name']) . "'";

			$query = $this->db->query($sql);

		}

	}

	
}