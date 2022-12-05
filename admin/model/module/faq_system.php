<?php
	class ModelModuleFAQSystem extends Model {
		
		public function addCategory($data){
			$sql = "INSERT INTO faq_category 
			SET sort_order ='" . (int)$data['sort_order'] . "',
			status     ='" . (int)$data['status'] . "'";
			
			$this->db->query($sql);
			
			$category_id = $this->db->getLastId();
			
			foreach($data['category_description'] as $key => $value){
				$sql = "INSERT INTO faq_category_description 
				SET category_id ='" . (int)$category_id . "', 
				language_id ='" . (int)$key ."', 
				name        ='" . $this->db->escape($value['name']) . "'";
				
				$this->db->query($sql);	
			}		
			
			if ($data['keyword']) {
				foreach ($data['keyword'] as $language_id => $keyword) {
					if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'faq_category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
				}
			}
		}
		
		public function editCategory($category_id, $data){
			$sql = "UPDATE faq_category 
			SET sort_order ='" . (int)$data['sort_order'] . "',
			status     ='" . (int)$data['status'] . "'
			WHERE category_id ='" . (int)$category_id . "'";
			
			$this->db->query($sql);
			
			$this->db->query("DELETE FROM faq_category_description WHERE category_id='" . (int)$category_id . "'");
			
			foreach($data['category_description'] as $key => $value){
				$sql = "INSERT INTO faq_category_description 
				SET category_id ='" . (int)$category_id . "', 
				language_id ='" . (int)$key ."', 
				name        ='" . $this->db->escape($value['name']) . "'";
				$this->db->query($sql);	
			}		
			
			
			$this->db->query("DELETE FROM url_alias WHERE query = 'faq_category_id=" . (int)$category_id. "'");
			
			if ($data['keyword']) {
				foreach ($data['keyword'] as $language_id => $keyword) {
					if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'faq_category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
				}
			}
			
		}
		
		public function getCategories(){
			$sql = "SELECT c.*, cd.* FROM faq_category c 
			LEFT JOIN faq_category_description cd ON (c.category_id = cd.category_id)
			WHERE cd.language_id ='" . (int)$this->config->get('config_language_id') ."'
			ORDER BY c.sort_order ASC";
			
			$query = $this->db->query($sql);
			
			return $query->rows;	
		}
		
		public function getCategory($category_id){
			$sql = "SELECT * FROM faq_category 
			WHERE category_id ='" . (int)$category_id . "'";
			
			$query = $this->db->query($sql);
			
			return $query->row;	
		}
		
		public function getCategoryDescriptions($category_id) {
			$category_description_data = array();
			
			$query = $this->db->query("SELECT * FROM faq_category_description WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$category_description_data[$result['language_id']] = array(
				'name'             => $result['name']
				);
			}
			
			return $category_description_data;
		}
		
		public function getKeyWords($category_id) {
			$keywords = array();
			
			$query = $this->db->query("SELECT * FROM url_alias WHERE query = 'faq_category_id=" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$keywords[$result['language_id']] = $result['keyword'];					
			}
			
			return $keywords;
		}
		
		public function deleteCategory($category_id){
			$this->db->query("DELETE FROM faq_category WHERE category_id ='" . (int)$category_id . "'");
			$this->db->query("DELETE FROM faq_category_description WHERE category_id ='" . (int)$category_id . "'");
		}
		
		public function addQuestion($data){
			$sql = "INSERT INTO faq_question 
			SET category_id ='" . (int)$data['category_id'] ."',
			status      ='" . (int)$data['status'] ."',
			sort_order  ='" . (int)$data['sort_order'] ."'";
			
			$this->db->query($sql);			
			
			$question_id = $this->db->getLastId();
			
			if (isset($this->request->post['question_description'])){
				foreach($this->request->post['question_description'] as $key => $value){
					$sql = "INSERT INTO faq_question_description 
					SET question_id ='" . (int)$question_id . "',
					language_id ='" . (int)$key . "',
					title       ='" . $this->db->escape($value['title']) . "',
					description ='" . $this->db->escape($value['description']) . "'";
					
					$this->db->query($sql);			
				}
			}
		}
		
		public function editQuestion($question_id, $data){
			$sql = "UPDATE faq_question 
			SET category_id ='" . (int)$data['category_id'] ."',
			status      ='" . (int)$data['status'] ."',
			sort_order  ='" . (int)$data['sort_order'] ."'
			WHERE question_id ='" . (int)$question_id . "'";
			
			$this->db->query($sql);			
			
			$this->db->query("DELETE FROM faq_question_description WHERE question_id='" . (int)$question_id . "'");
			
			if (isset($this->request->post['question_description'])){
				foreach($this->request->post['question_description'] as $key => $value){
					$sql = "INSERT INTO faq_question_description 
					SET question_id ='" . (int)$question_id . "',
					language_id ='" . (int)$key . "',
					title       ='" . $this->db->escape($value['title']) . "',
					description ='" . $this->db->escape($value['description']) . "'";
					
					$this->db->query($sql);			
				}
			}
		}
		
		public function getQuestions($data = array()){
			
			$sql = "SELECT q.*, qd.*, cd.* FROM faq_question q 
			LEFT JOIN faq_question_description qd ON (q.question_id = qd.question_id)
			LEFT JOIN faq_category_description cd ON (q.category_id = cd.category_id)
			WHERE cd.language_id='" . (int)$this->config->get('config_language_id') . "' AND qd.language_id='" . (int)$this->config->get('config_language_id') . "' ";
			
			if (isset($data['filter_status'])){
				$sql .= "AND q.status ='" . (int)$data['filter_status'] . "' ";
			}	
			
			$sql .= "ORDER BY q.category_id ASC, q.sort_order ASC" ;
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getQuestion($question_id){
			$sql = "SELECT * FROM faq_question WHERE question_id='" . (int)$question_id . "'";
			$query = $this->db->query($sql);
			
			return $query->row;
		}
		
		public function getQuestionDescriptions($question_id){
			$question_description_data = array();
			
			$query = $this->db->query("SELECT * FROM faq_question_description WHERE question_id = '" . (int)$question_id . "'");
			
			foreach ($query->rows as $result) {
				$question_description_data[$result['language_id']] = array(
				'title'             => $result['title'],
				'description'      => $result['description']
				);
			}
			
			return $question_description_data;
		}
		
		public function deleteQuestion($question_id){
			$this->db->query("DELETE FROM faq_question WHERE question_id='" . (int)$question_id . "'");
			$this->db->query("DELETE FROM faq_question_description WHERE question_id='" . (int)$question_id . "'");
		}
	}
?>