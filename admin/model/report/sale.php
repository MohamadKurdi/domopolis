<?php
class ModelReportSale extends Model {

	public function getOrders($data = []) {
		$sql = "SELECT MIN(tmp.date_added) AS date_start, 
		MAX(tmp.date_added) AS date_end,
		AVG(IF(tmp.profitability != 0, tmp.profitability, NULL)) AS avg_profitability,
		MIN(IF(tmp.profitability != 0, tmp.profitability, NULL)) AS min_profitability,
		MAX(IF(tmp.profitability != 0, tmp.profitability, NULL)) AS max_profitability,
		AVG(IF(tmp.total != 0, tmp.total, NULL)) AS avg_total,		
		COUNT(tmp.order_id) AS `orders`, 
		SUM(tmp.products) AS products, 
		SUM(tmp.tax) AS tax, ";

		if ($this->config->get('config_enable_amazon_specific_modes')){
			foreach (\hobotix\RainforestAmazon::amazonOffersType as $field){
				$sql .= " SUM(CASE WHEN tmp.amazon_offers_type = '" . $this->db->escape($field) . "' THEN 1 ELSE 0 END)/COUNT(*) * 100 AS pct_" . $field . ", ";
			}
		}

		$sql .= " SUM(tmp.total) AS total ";
		$sql .= " FROM (SELECT o.order_id, 
		(SELECT SUM(op.quantity) FROM `order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, 
		(SELECT SUM(ot.value) FROM `order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id) AS tax, 
		o.total, o.date_added, o.profitability, o.amazon_offers_type FROM `order` o";

		if (!empty($data['filter_category_id'])){
			$sql .= " JOIN order_product op ON o.order_id = op.order_id
  					JOIN product_to_category ptc ON op.product_id = ptc.product_id
  					JOIN category_path cp ON ptc.category_id = cp.category_id";
		} 

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_category_id'])){
			$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if (!empty($data['filter_amazon_offers_type'])) {
			$sql .= " AND amazon_offers_type = '" . $this->db->escape($data['filter_amazon_offers_type']) . "'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " GROUP BY o.order_id) tmp";
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(tmp.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(tmp.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(tmp.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(tmp.date_added)";
				break;									
		}
		
		$sql .= " ORDER BY tmp.date_added DESC";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	
	
	public function getTotalOrders($data = []) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT DAY(date_added)) AS total FROM `order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT WEEK(date_added)) AS total FROM `order` o";
				break;	
			case 'month':
				$sql = "SELECT COUNT(DISTINCT MONTH(date_added)) AS total FROM `order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added)) AS total FROM `order` o";
				break;									
		}

		if (!empty($data['filter_category_id'])){
			$sql .= " JOIN order_product op ON o.order_id = op.order_id
  					JOIN product_to_category ptc ON op.product_id = ptc.product_id
  					JOIN category_path cp ON ptc.category_id = cp.category_id";
		} 
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_category_id'])){
			$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if (!empty($data['filter_amazon_offers_type'])) {
			$sql .= " AND amazon_offers_type = '" . $this->db->escape($data['filter_amazon_offers_type']) . "'";
		}
				
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];	
	}
	
	public function getTaxes($data = []) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `order_total` ot LEFT JOIN `order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'"; 

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY ot.title, DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY ot.title, WEEK(o.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY ot.title, MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY ot.title, YEAR(o.date_added)";
				break;									
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

	public function getFinalSubcategories($parent_id = null){

		if ($parent_id){			
			$sql = " WITH RECURSIVE cte AS ( SELECT category_id, parent_id, 1 AS level FROM category "; 
			$sql .= " WHERE parent_id = '" . (int)$parent_id . "' "; 
			$sql .= " UNION ALL SELECT c.category_id, c.parent_id, p.level + 1 AS level FROM category c INNER JOIN cte p ON c.parent_id = p.category_id ) ";
			$sql .= " SELECT cte.category_id, cd.name FROM cte LEFT JOIN category_description cd ON (cte.category_id = cd.category_id AND cd.language_id = 2) ";
			$sql .= " WHERE cte.category_id NOT IN (SELECT parent_id FROM category)";
		} else {
			$sql = " SELECT c.category_id, cd.name FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id AND cd.language_id = 2) ";
			$sql .= " WHERE c.category_id NOT IN (SELECT parent_id FROM category)";
		}


		$query = $this->db->query($sql);

		return $query->rows;
	}	
	
	public function getTotalTaxes($data = []) {
		$sql = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM `order_total` ot LEFT JOIN `order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
				
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(o.date_added), ot.title";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;									
		}
		
		$sql .= ") tmp";
		
		$query = $this->db->query($sql);

		return $query->row['total'];	
	}	
	
	public function getShipping($data = []) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `order_total` ot LEFT JOIN `order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'shipping'"; 

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY ot.title, DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY ot.title, WEEK(o.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY ot.title, MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY ot.title, YEAR(o.date_added)";
				break;									
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
	
	public function getTotalShipping($data = []) {
		$sql = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM `order_total` ot LEFT JOIN `order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'shipping'";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
				
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(o.date_added), ot.title";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;									
		}
		
		$sql .= ") tmp";
		
		$query = $this->db->query($sql);

		return $query->row['total'];	
	}		
}