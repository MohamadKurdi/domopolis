<?php
class ModelModuleCategoryreviews extends Model {		

	private $NOW;
#		'" . $this->NOW . "'
	public function __construct($registry) {
		$this->NOW = date('Y-m-d H:m') . ':00';
		parent::__construct($registry);
	}

	public function addCategoryreview($category_id, $data) {
		$this->db->query("INSERT INTO category_review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', category_id = '" . (int)$category_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = '" . $this->NOW . "'");
	}
	

	/* FOR PAGINATION
	public function getCategoryreviewsByCategoryId($category_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}		
		if ($limit < 1) {
			$limit = 20;
		}		
		$query = $this->db->query("SELECT author, rating, text, date_added FROM category_review WHERE category_id = '" . (int)$category_id . "' AND status = '1' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		return $query->rows;
	}
	*/	

	public function getCategoryreviewsByCategoryId($category_id) {
		$query = $this->db->query("SELECT author, rating, text, date_added FROM category_review WHERE category_id = '" . (int)$category_id . "' AND status = '1' ORDER BY date_added DESC");
			
		return $query->rows;
	}


	public function getTotalCategoryreviewsByCategoryId($category_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM category_review WHERE category_id = '" . (int)$category_id . "' AND status = '1'");
		
		return $query->row['total'];
	}
}
?>