<?php
class ModelSaleSupplier extends Model {
	public function addSupplier($data) {
		$this->db->query("INSERT INTO suppliers SET 
		supplier_name 		= '" . $this->db->escape($data['supplier_name']) . "',
		supplier_code 		= '" . $this->db->escape($data['supplier_code']) . "',
		supplier_type 		= '" . $this->db->escape($data['supplier_type']) . "', 			
		supplier_country 	= '" . $this->db->escape($data['supplier_country']) . "', 
		supplier_comment 	= '" . $this->db->escape($data['supplier_comment']) . "', 
		supplier_m_coef 	= '" . (float)$data['supplier_m_coef'] . "',
		supplier_l_coef 	= '" . (float)$data['supplier_l_coef'] . "', 
		supplier_n_coef 	= '" . (float)$data['supplier_n_coef'] . "',
		supplier_parent_id 	= '" . (int)$data['supplier_parent_id'] . "',
		sort_order 			= '" . (int)($data['sort_order']) . "',
		terms_instock 		= '" . $this->db->escape($data['terms_instock']) . "',
		terms_outstock  	= '" . $this->db->escape($data['terms_outstock']) . "',
		supplier_inner 		= '" . (int)$data['supplier_inner'] . "',
		amzn_good			= '" . (int)$data['amzn_good'] . "',
		amzn_bad			= '" . (int)$data['amzn_bad'] . "',
		amzn_coefficient 	= '" . (int)$data['amzn_coefficient'] . "'
		amazon_seller_id  	= '" . $this->db->escape($data['amazon_seller_id']) . "',
		store_link  		= '" . $this->db->escape($data['store_link']) . "',
		business_name  		= '" . $this->db->escape($data['business_name']) . "',
		registration_number = '" . $this->db->escape($data['registration_number']) . "',
		vat_number  		= '" . $this->db->escape($data['vat_number']) . "',
		business_type  		= '" . $this->db->escape($data['business_type']) . "',
		about_this_seller  	= '" . $this->db->escape($data['about_this_seller']) . "',
		detailed_information 	= '" . $this->db->escape($data['detailed_information']) . "',
		telephone 				= '" . $this->db->escape($data['telephone']) . "',
		email 					= '" . $this->db->escape($data['email']) . "',
		is_native 				= '" . (int)$data['is_native'] . "',
		rating50 				= '" . (int)$data['rating50'] . "',
		ratings_total 			= '" . (int)$data['ratings_total'] . "',
		positive_ratings100 	= '" . (int)$data['positive_ratings100'] . "',
		path_to_feed 			= '" . $this->db->escape($data['path_to_feed']) . "',
		rrp_in_feed 			= '" . (int)$data['rrp_in_feed'] . "',
		parser 					= '" . $this->db->escape($data['parser']) . "',
		currency 				= '" . $this->db->escape($data['currency']) . "',
		parser_status 			= '" . (int)$data['parser_status'] . "',
		stock 					= '" . (int)$data['stock'] . "',
		prices 					= '" . (int)$data['prices'] . "'");
		
		$supplier_id = $this->db->getLastId();
		
		return $supplier_id;
	}
	
	public function editSupplier($supplier_id, $data) {
		$this->db->query("UPDATE suppliers SET 
		supplier_name 		= '" . $this->db->escape($data['supplier_name']) . "',
		supplier_type 		= '" . $this->db->escape($data['supplier_type']) . "', 
		supplier_country 	= '" . $this->db->escape($data['supplier_country']) . "',
		supplier_code 		= '" . $this->db->escape($data['supplier_code']) . "', 
		supplier_comment 	= '" . $this->db->escape($data['supplier_comment']) . "', 
		supplier_m_coef 	= '" . (float)$data['supplier_m_coef'] . "',
		supplier_l_coef 	= '" . (float)$data['supplier_l_coef'] . "', 
		supplier_n_coef 	= '" . (float)$data['supplier_n_coef'] . "',
		supplier_parent_id 	= '" . (int)$data['supplier_parent_id'] . "',
		sort_order 			= '" . (int)$data['sort_order'] . "',
		terms_instock 		= '" . $this->db->escape($data['terms_instock']) . "',
		terms_outstock  	= '" . $this->db->escape($data['terms_outstock']) . "',
		supplier_inner 		= '" . (int)$data['supplier_inner'] . "',
		amzn_good			= '" . (int)$data['amzn_good'] . "',
		amzn_bad			= '" . (int)$data['amzn_bad'] . "',
		amzn_coefficient 	= '" . (int)$data['amzn_coefficient'] . "',
		amazon_seller_id  	= '" . $this->db->escape($data['amazon_seller_id']) . "',
		store_link  		= '" . $this->db->escape($data['store_link']) . "',
		business_name  		= '" . $this->db->escape($data['business_name']) . "',
		registration_number = '" . $this->db->escape($data['registration_number']) . "',
		vat_number  		= '" . $this->db->escape($data['vat_number']) . "',
		business_type  		= '" . $this->db->escape($data['business_type']) . "',
		about_this_seller  	= '" . $this->db->escape($data['about_this_seller']) . "',
		detailed_information 	= '" . $this->db->escape($data['detailed_information']) . "',
		telephone 				= '" . $this->db->escape($data['telephone']) . "',
		email 					= '" . $this->db->escape($data['email']) . "',
		is_native 				= '" . (int)$data['is_native'] . "',
		rating50 				= '" . (int)$data['rating50'] . "',
		ratings_total 			= '" . (int)$data['ratings_total'] . "',
		positive_ratings100 	= '" . (int)$data['positive_ratings100'] . "',
		path_to_feed 			= '" . $this->db->escape($data['path_to_feed']) . "',
		parser 					= '" . $this->db->escape($data['parser']) . "',
		currency 				= '" . $this->db->escape($data['currency']) . "',
		rrp_in_feed 			= '" . (int)$data['rrp_in_feed'] . "',
		parser_status 			= '" . (int)$data['parser_status'] . "',
		stock 					= '" . (int)$data['stock'] . "',
		prices 					= '" . (int)$data['prices'] . "'
		WHERE supplier_id = '" . (int)$supplier_id . "'");				
	}
	
	public function deleteSupplier($supplier_id) {
		$this->db->query("DELETE FROM suppliers WHERE supplier_id = '" . (int)$supplier_id . "'");		
	}
	
	public function getSupplier($supplier_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM suppliers WHERE supplier_id = '" . (int)$supplier_id . "'");
		
		return $query->row;
	}

	public function getSupplierByAmazonSellerID($amazon_seller_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM suppliers WHERE amazon_seller_id = '" . $this->db->escape($amazon_seller_id) . "'");
		
		return $query->row;
	}
	
	public function getSupplierByName($supplier) {
		$query = $this->db->query("SELECT DISTINCT * FROM suppliers WHERE supplier_name = '" . $this->db->escape(trim($supplier)) . "'");
		
		if ($query->num_rows){
			return $query->row;
		} else {
			return false;			
		}
	}

	public function tryToGuessCategory($name) {
		$this->load->model('catalog/category');

		$data = [
			'filter_name_extended' 	=> $name,
			'start' 				=> 0,
			'limit' 				=> 5
		];

		return $this->model_catalog_category->getCategories($data);
	}

	public function getSupplierCategories($supplier_id) {
		$query = $this->db->query("SELECT * FROM supplier_categories WHERE supplier_id = '" . (int)$supplier_id . "'");

		return $query->rows;
	}

	public function getTotalSupplierCategories($supplier_id) {
		$query = $this->db->query("SELECT COUNT(*) as total FROM supplier_categories WHERE supplier_id = '" . (int)$supplier_id . "'");

		return $query->row['total'];
	}

	public function updateSupplierCategory($supplier_category_id, $category_id) {
		$query = $this->db->query("UPDATE supplier_categories SET category_id = '" . (int)$category_id . "' WHERE supplier_category_id = '" . (int)$supplier_category_id . "'");		
	}

	public function updateSupplierCategoryField($supplier_category_id, $field, $value) {
		$query = $this->db->query("UPDATE supplier_categories SET `" . $this->db->escape($field) . "` = '" . (int)$value . "' WHERE supplier_category_id = '" . (int)$supplier_category_id . "'");		
	}

	public function getTotalSellerOffers($data = []){
		$sql = "SELECT COUNT(*) as total FROM product_amzn_offers WHERE 1";

		if (!empty($data['filter_amazon_seller_id'])){
			$sql .= " AND sellerID = '" . $this->db->escape($data['filter_amazon_seller_id']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSellerOffers($data = []){
		$sql = "SELECT DISTINCT pao.*, p.price, p.product_id, p.profitability, p.image, pd.name FROM product_amzn_offers pao ";
		$sql .= " LEFT JOIN product p ON (pao.asin = p.asin) ";
		$sql .= " LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE 1 ";

		if (!empty($data['filter_amazon_seller_id'])){
			$sql .= " AND sellerID = '" . $this->db->escape($data['filter_amazon_seller_id']) . "'";
		}

		$sort_data = array(
			'pao.date_added',
		);
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY asin";
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
	
	public function getTotalAmazonOrders($supplier_id){		
		$query = $this->db->query("SELECT 
			COUNT(DISTINCT amazon_id) as total_orders, 
			SUM(price * quantity) as total_sum, 
			SUM(quantity) as total_products
			FROM amazon_orders_products
			WHERE supplier_id = '" . (int)$supplier_id . "' GROUP BY supplier_id");
		
		if ($query->num_rows){
			return $query->row;		
		} else {
			return array(
				'total_orders' => 0,
				'total_sum' => 0,
				'total_products' =>	0
			);
		}		
	}
	
	public function getSupplierEANCodes($product_id){		
		$query = $this->db->query("SELECT 
		lsp.product_ean,
		s.supplier_name
		FROM local_supplier_products lsp
		LEFT JOIN suppliers s ON s.supplier_id = lsp.supplier_id
		WHERE 
		lsp.product_id = '" . (int)$product_id . "'
		");
		
		if ($query->num_rows){
			return $query->rows;		
		} else {
			return false;
		}		
	}
	
	public function getAllSupplierCountryCodes(){
		$result = [];

		$query = $this->db->query("SELECT DISTINCT supplier_country FROM suppliers");

		foreach ($query->rows as $row){
			$result[] = $row['supplier_country'];
		}

		return $result;
	}
	
	public function getSupplierInfo($product_id){
		$query = $this->db->query("SELECT 
			lsp.*,
			s.supplier_name
			FROM local_supplier_products lsp
			LEFT JOIN suppliers s ON s.supplier_id = lsp.supplier_id
			WHERE 
			lsp.product_id = '" . (int)$product_id . "'
			");
		
		if ($query->num_rows){
			return $query->rows;		
		} else {
			return false;
		}		
	}
	
	public function getSupplierStocks($product_id){		
		$query = $this->db->query("SELECT 
			lsp.price,
			lsp.price_recommend,
			lsp.currency,
			lsp.stock,
			s.supplier_name
			FROM local_supplier_products lsp
			LEFT JOIN suppliers s ON s.supplier_id = lsp.supplier_id
			WHERE 
			lsp.product_id = '" . (int)$product_id . "'
			");
		
		if ($query->num_rows){
			return $query->rows;		
		} else {
			return false;
		}				
	}
	
	public function getTotalAmazonOrdersComplex($supplier_id){		
		$query = $this->db->query("SELECT 
			COUNT(DISTINCT ao.amazon_id) as total_orders, 
			SUM(price * quantity) as total_sum, 
			AVG(price * quantity) as avg_sum, 
			AVG(price) as avg_price, 
			SUM(quantity) as total_products,
			SUM(gift_card) as total_gift_card,
			SUM(cancelled) as total_cancelled
			FROM amazon_orders_products aop
			LEFT JOIN amazon_orders ao ON aop.amazon_id = ao.amazon_id
			WHERE supplier_id = '" . (int)$supplier_id . "' GROUP BY supplier_id");
		
		if ($query->num_rows){
			return $query->row;		
		} else {
			return array(
				'total_orders' => 0,
				'total_sum' => 0,
				'avg_sum' =>	0,
				'avg_price' =>	0,
				'total_products' =>	0,
				'total_gift_card' =>	0,
				'total_cancelled' =>	0,
			);
		}		
	}
	
	public function getAmazonBrands($supplier_id){
		$query = $this->db->query("SELECT 
			m.name,
			m.manufacturer_id,
			COUNT(DISTINCT aop.amazon_id) as total_orders,
			SUM(aop.price*aop.quantity) as total_sum,
			AVG(aop.price) as avg_price,
			SUM(aop.quantity) as total_products
			FROM amazon_orders_products aop 
			LEFT JOIN product p ON aop.asin = p.asin 
			LEFT JOIN manufacturer m ON p.manufacturer_id = m.manufacturer_id								
			WHERE aop.supplier_id = '" . (int)$supplier_id . "'
			GROUP by m.manufacturer_id
			ORDER BY total_orders DESC; 						
			");
		
		return $query->rows;
	}
	
	public function getSuppliersMain($data= []) {
		$sql = "SELECT * FROM suppliers";
		
		$sort_data = array(
			'supplier_name',
			'sort_order',
			'sort_order, supplier_name'
		);	
		
		$sql .= " WHERE 1";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND supplier_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_supplier_name'])) {
			$sql .= " AND supplier_name LIKE '%" . $this->db->escape($data['filter_supplier_name']) . "%'";
		}
		
		$sql .= " AND supplier_parent_id = 0";
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY sort_order, supplier_name";	
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
				$data['limit'] = 200;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;		
	}
	
	public function getOPSupply($order_product_id){
		$query = $this->db->query("SELECT DISTINCT * FROM order_product_supply WHERE order_product_id = '" . (int)$order_product_id . "'");
		
		return $query->rows;
	}
	
	public function getOPSupplyForSet($set_product_id){
		$query = $this->db->query("SELECT DISTINCT * FROM order_product_supply WHERE order_set_id = '" . (int)$set_product_id . "'");
		
		return $query->rows;
	}
	
	public function getSuppliers($data = []) {
		$sql = "SELECT * FROM suppliers";
		
		$sort_data = array(
			'supplier_name',
			'sort_order',
			'sort_order, supplier_name'
		);	
		
		$sql .= " WHERE 1";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LOWER(supplier_name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%'";
		}

		if (!empty($data['filter_supplier_name'])) {
			$sql .= " AND LOWER(supplier_name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_supplier_name'])) . "%'";
		}

		if (!empty($data['filter_supplier_country'])) {
			$sql .= " AND LOWER(supplier_country) LIKE '" . $this->db->escape(mb_strtolower($data['filter_supplier_country'])) . "'";
		}
		
		if (!empty($data['filter_parent_id'])) {
			$sql .= " AND supplier_parent_id = '" . (int)$data['filter_parent_id'] . "'";
		}

		if (!empty($data['filter_rating_from'])) {
			$sql .= " AND rating50 > '" . (int)$data['filter_rating_from'] . "'";
		}

		if (!empty($data['filter_reviews_from'])) {
			$sql .= " AND ratings_total > '" . (int)$data['filter_reviews_from'] . "'";
		}

		if (!empty($data['filter_has_telephone'])) {
			$sql .= " AND telephone <> ''";
		}

		if (!empty($data['filter_has_email'])) {
			$sql .= " AND email <> ''";
		}

		if (!empty($data['filter_has_number'])) {
			$sql .= " AND vat_number <> ''";
		}
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY sort_order, supplier_name";	
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
				$data['limit'] = 200;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;		
	}
	
	public function getTotalSuppliers($data = []) {
		$sql = "SELECT COUNT(*) AS total FROM suppliers WHERE 1";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LOWER(supplier_name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%'";
		}

		if (!empty($data['filter_supplier_name'])) {
			$sql .= " AND LOWER(supplier_name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_supplier_name'])) . "%'";
		}

		if (!empty($data['filter_supplier_country'])) {
			$sql .= " AND LOWER(supplier_country) LIKE '" . $this->db->escape(mb_strtolower($data['filter_supplier_country'])) . "'";
		}
		
		if (!empty($data['filter_parent_id'])) {
			$sql .= " AND supplier_parent_id = '" . (int)$data['filter_parent_id'] . "'";
		}

		if (!empty($data['filter_rating_from'])) {
			$sql .= " AND rating50 > '" . (int)$data['filter_rating_from'] . "'";
		}

		if (!empty($data['filter_reviews_from'])) {
			$sql .= " AND ratings_total > '" . (int)$data['filter_reviews_from'] . "'";
		}

		if (!empty($data['filter_has_telephone'])) {
			$sql .= " AND telephone <> ''";
		}

		if (!empty($data['filter_has_email'])) {
			$sql .= " AND email <> ''";
		}

		if (!empty($data['filter_has_number'])) {
			$sql .= " AND vat_number <> ''";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
}