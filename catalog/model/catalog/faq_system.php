<?php
class ModelCatalogFAQSystem extends Model {
	public function getCategories() {
		$sql = "SELECT c.*, cd.* FROM faq_category c 
				LEFT JOIN faq_category_description cd ON (c.category_id = cd.category_id)
				WHERE cd.language_id ='" . (int)$this->config->get('config_language_id') . "' AND c.status = 1 
				ORDER BY c.sort_order ASC";
		
		$query = $this->db->query($sql);

		return $query->rows;	
	}
	
	public function getCategory($category_id) {
		$sql = "SELECT c.*, cd.* FROM faq_category c 
				LEFT JOIN faq_category_description cd ON (c.category_id = cd.category_id)
				WHERE cd.language_id ='" . (int)$this->config->get('config_language_id') . "' AND c.status = 1
				AND c.category_id = '" . (int)$category_id . "'
				ORDER BY c.sort_order ASC";
		
		$query = $this->db->query($sql);

		return $query->row;	
	}
	
	public function getQuestionsByCategoryId($category_id){
		$sql = "SELECT q.*, qd.* FROM faq_question q
				LEFT JOIN faq_question_description qd ON (q.question_id = qd.question_id)
				WHERE q.category_id ='" . (int)$category_id . "' AND qd.language_id ='" . (int)$this->config->get('config_language_id') . "' AND q.status = 1 
				ORDER BY q.sort_order ASC";
				
		$query = $this->db->query($sql);

		return $query->rows;	
	}
	
	public function proposeQuestion($data){
		$category_id = $this->getFirstActiveCategoryId();
		
		$this->db->query("INSERT INTO faq_question SET category_id = '" . $category_id . "', status = 0");
		
		$question_id = $this->db->getLastId();
		
		$sql = "INSERT INTO faq_question_description 
				SET question_id ='" . (int)$question_id . "',
					language_id ='" . (int)$this->config->get('config_language_id') ."',
					title       ='" . $this->db->escape($data['question']) ."'";
					
		$this->db->query($sql);			
	}
	
	private function getFirstActiveCategoryId(){
		$query = $this->db->query("SELECT category_id FROM faq_category WHERE status=1 ORDER BY category_id ASC");
		
		if ($query->num_rows){
			return $query->row['category_id'];
		}
		
		return 0;
	}
}
?>