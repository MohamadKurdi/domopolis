<?
class ModelKpPriceva extends Model {

	public function getPricevaCategories($data){
		$sql = "SELECT category_name, count(product_id) as total_products, category_name FROM priceva_data WHERE 1";

		if (isset($data['filter_priceva_store_id'])){
			$sql .= " AND store_id = '" . (int)$data['filter_priceva_store_id'] . "'";
		}

		$sql .= " GROUP BY category_name ORDER BY count(product_id) DESC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProductStockWaits($product_id, $warehouse_identifier){

		$query = $this->db->query("SELECT SUM(`" . $warehouse_identifier . "`) as '" . $warehouse_identifier . "' FROM product_stock_waits WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($query->row[$warehouse_identifier])){
			return $query->row[$warehouse_identifier];
		} else {
			return 0;			
		}
	}

	public function getCountProductsInOrders($product_id, $period, $filter_store_id){

		$sql = "SELECT SUM(quantity) as 'total' FROM order_product WHERE product_id = '" . (int)$product_id . "'";
		$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "'";
		
		if ($filter_store_id == 0){

			$sql.= " AND store_id IN (0,2,5)";

		} else {

			$sql.= " AND store_id = '" . (int)$filter_store_id . "'";

		}


		$sql .= ")";

		switch ($period){

			case 'month':
			$sql .= " AND order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH)) AND YEAR(date_added) = YEAR(DATE_ADD(NOW(), INTERVAL -1 MONTH)))";
			break;

			case '3month':
			$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND QUARTER(date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -1 QUARTER)) AND YEAR(date_added) = YEAR(DATE_ADD(NOW(), INTERVAL -1 QUARTER)))";
			break;

			case 'halfyear':
			if (date('M') <= 6){
				$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND MONTH(date_added) > 6 AND MONTH(date_added) <= 12 AND YEAR(date_added) = YEAR(DATE_ADD(NOW(), INTERVAL -1 YEAR)))";
			} else {
				$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND MONTH(date_added) >= 1 AND MONTH(date_added) <= 6 AND YEAR(date_added) = YEAR(NOW()))";
			}
			
			break;

			case 'year':
			$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND YEAR(date_added) = YEAR(DATE_ADD(NOW(), INTERVAL -1 YEAR)))";
			break;

			case '30day':
			$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND DATE(date_added) >= DATE_ADD(NOW(), INTERVAL -1 MONTH))";
			break;

			case '90day':
			$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND DATE(date_added) >= DATE_ADD(NOW(), INTERVAL -3 MONTH))";
			break;

			case '180day':
			$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND DATE(date_added) >= DATE_ADD(NOW(), INTERVAL -6 MONTH))";
			break;

			case '365day':
			$sql .= " AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND DATE(date_added) >= DATE_ADD(NOW(), INTERVAL -12 MONTH))";
			break;

		}

		$sql .= "";

	//	$this->log->debugsql($sql);

		$query = $this->db->query($sql);	

		return $query->row['total'];
	}

	public function getPricevaCompetitors($data){

		$sql = "SELECT company_name, count(product_id) as total_products FROM priceva_sources ps WHERE 1";

		if (isset($data['filter_manufacturer_id'])) {
			$sql .= " AND ps.product_id IN (SELECT product_id FROM product WHERE manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "')";			
		}

		if (isset($data['filter_store_id'])) {
			$sql .= " AND ps.store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_priceva_category'])) {
			$sql .= " AND ps.product_id IN (SELECT product_id FROM priceva_data WHERE category_name = '" . $this->db->escape($data['filter_priceva_category'])  . "')";
		}

		$sql .= " GROUP BY company_name ORDER BY count(product_id) DESC";

		$query = $this->db->query($sql);

		return $query->rows;

	}


	public function getProducts($data = array()) {
		$sql = "SELECT prd.*, p.*, pd.* FROM priceva_data prd LEFT JOIN product p ON (p.product_id = prd.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND language_id = '" . $this->config->get('config_language_id') . "')";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 

		if (!empty($data['filter_name'])) {						
			$art = preg_replace("([^0-9])", "", $data['filter_name']);


			$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
			$sql .= " OR (p.yam_product_id = '" . $this->db->escape($data['filter_name']) . "')";
			$sql .= " OR (p.model LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.model)>1 )";
			$sql .= " OR (p.ean LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.ean)>1 )";
			$sql .= " OR (p.sku LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.sku)>1 )";
			$sql .= " OR (p.asin LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.asin)>1 )";


			$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
			AND LENGTH(p.model)>1 )";
			$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
			AND LENGTH(p.sku)>1 ))";

		}			

		if (empty($data['filter_show_without_links'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1)";
		}	

		if (!empty($data['filter_show_without_links'])) {
			$sql .= " AND prd.product_id NOT IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1)";
		} 

		if (!empty($data['filter_competitors'])) {
			$competitors = explode(',', $data['filter_competitors']);
			$implode = [];

			foreach ($competitors as $competitor){
				$implode[] = "prd.product_id IN (SELECT product_id FROM priceva_sources WHERE company_name = '" . $this->db->escape($competitor) . "' AND active = 1)";
				$sql .= " AND (" . implode(' OR ', $implode) . ")";				
			}
		}

		if (!empty($data['filter_competitor_stock'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 1)";			
		}

		if (!empty($data['filter_competitor_not_stock'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 0)";			
		}

		if (!empty($data['filter_competitor_stock_all'])) {
			$sql .= " AND prd.product_id NOT IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 0)";			
		}

		if (!empty($data['filter_competitor_not_stock_all'])) {
			$sql .= " AND prd.product_id NOT IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 1)";			
		}


		if (!empty($data['filter_kitchenprofi_stock'])) {
			$sql .= " AND p.`" . $data['filter_current_stock_field'] . "` > 0";			
		}

		if (!empty($data['filter_kitchenprofi_not_stock'])) {
			$sql .= " AND p.`" . $data['filter_current_stock_field'] . "` <= 0";			
		}

		if (!empty($data['filter_kitchenprofi_stockwait'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM product_stock_waits WHERE `" . $data['filter_current_stock_field'] . "` > 0)";			
		}

		if (!empty($data['filter_kitchenprofi_not_stockwait'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM product_stock_waits WHERE `" . $data['filter_current_stock_field'] . "` <= 0)";			
		}


		if (isset($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if (isset($data['filter_store_id'])) {
			$sql .= " AND prd.store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_priceva_category'])) {
			$sql .= " AND prd.category_name = '" . $this->db->escape($data['filter_priceva_category']) . "'";
		}

		if (isset($data['filter_quantity_stockM']) && !is_null($data['filter_quantity_stockM'])) {
			$sql .= " AND p.quantity_stockM > 0";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_quantity_not_null'])) {
			$sql .= " AND p.quantity > 0";
		}

		if (!empty($data['filter_not_virtual']) && !is_null($data['filter_not_virtual'])) {
			$sql .= " AND p.is_virtual = 0";
		}

		if (!empty($data['filter_not_markdown']) && !is_null($data['filter_not_markdown'])) {
			$sql .= " AND p.is_markdown = 0";
		}

		if (!empty($data['filter_is_illiquid'])) {
			$sql .= " AND p.is_illiquid = 1";
		}



		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.quantity_stockM',
			'p.quantity_stockK',
			'p.status',
			'p.sort_order',
			'p.buybox'
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

//		$this->log->debugsql($sql);

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {

	//	$this->log->debug($data);

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total  FROM priceva_data prd LEFT JOIN product p ON (p.product_id = prd.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND language_id = '" . $this->config->get('config_language_id') . "')";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {						
			$art = preg_replace("([^0-9])", "", $data['filter_name']);

			if (!empty($data['filter_name'])) {						
				$art = preg_replace("([^0-9])", "", $data['filter_name']);


				$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
				$sql .= " OR (p.yam_product_id = '" . $this->db->escape($data['filter_name']) . "')";
				$sql .= " OR (p.model LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.model)>1 )";
				$sql .= " OR (p.ean LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.ean)>1 )";
				$sql .= " OR (p.sku LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.sku)>1 )";
				$sql .= " OR (p.asin LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.asin)>1 )";


				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.model)>1 )";
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.sku)>1 ))";

			}

			$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
			AND LENGTH(p.model)>1 )";
			$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
			AND LENGTH(p.sku)>1 ))";

		}

		if (empty($data['filter_count_explicit'])){
			if (empty($data['filter_show_without_links'])) {
				$sql .= " AND prd.product_id IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1)";
			} 

			if (!empty($data['filter_show_without_links'])) {
				$sql .= " AND prd.product_id NOT IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1)";
			} 
		}

		if (!empty($data['filter_competitors'])) {
			$competitors = explode(',', $data['filter_competitors']);
			$implode = [];

			foreach ($competitors as $competitor){
				$implode[] = "prd.product_id IN (SELECT product_id FROM priceva_sources WHERE company_name = '" . $this->db->escape($competitor) . "' AND active = 1)";
				$sql .= " AND (" . implode(' OR ', $implode) . ")";				
			}
		}

		if (!empty($data['filter_competitor_stock'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 1)";			
		}

		if (!empty($data['filter_competitor_not_stock'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 0)";			
		}

		if (!empty($data['filter_competitor_stock_all'])) {
			$sql .= " AND prd.product_id NOT IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 0)";			
		}

		if (!empty($data['filter_competitor_not_stock_all'])) {
			$sql .= " AND prd.product_id NOT IN (SELECT product_id FROM priceva_sources WHERE store_id = '" . (int)$data['filter_store_id'] . "' AND active = 1 AND in_stock = 1)";			
		}

		if (!empty($data['filter_kitchenprofi_stock'])) {
			$sql .= " AND p.`" . $data['filter_current_stock_field'] . "` > 0";			
		}

		if (!empty($data['filter_kitchenprofi_not_stock'])) {
			$sql .= " AND p.`" . $data['filter_current_stock_field'] . "` <= 0";			
		}

		if (!empty($data['filter_kitchenprofi_stockwait'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM product_stock_waits WHERE `" . $data['filter_current_stock_field'] . "` > 0)";			
		}

		if (!empty($data['filter_kitchenprofi_not_stockwait'])) {
			$sql .= " AND prd.product_id IN (SELECT product_id FROM product_stock_waits WHERE `" . $data['filter_current_stock_field'] . "` <= 0)";			
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_store_id'])) {
			$sql .= " AND prd.store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (isset($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if (!empty($data['filter_priceva_category'])) {
			$sql .= " AND prd.category_name = '" . $this->db->escape($data['filter_priceva_category']) . "'";
		}

		if (!empty($data['filter_quantity_not_null'])) {
			$sql .= " AND p.quantity > 0";
		}

		if (!empty($data['filter_not_virtual']) && !is_null($data['filter_not_virtual'])) {
			$sql .= " AND p.is_virtual = 0";
		}

		if (!empty($data['filter_not_markdown']) && !is_null($data['filter_not_markdown'])) {
			$sql .= " AND p.is_markdown = 0";
		}

		if (!empty($data['filter_is_illiquid'])) {
			$sql .= " AND p.is_illiquid = 1";
		}					

		$query = $this->db->query($sql);

		return $query->row['total'];
	}	





}

