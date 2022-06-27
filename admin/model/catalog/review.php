<?php
	class ModelCatalogReview extends Model {
		public function addReview($data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "review SET addimage = '" . $this->db->escape($data['addimage']) . "', answer = '" . $this->db->escape(strip_tags($data['answer'])) . "', good = '" . $this->db->escape(strip_tags($data['good'])) . "', bads = '" . $this->db->escape(strip_tags($data['bads'])) . "', html_status = '" . (int)$data['html_status'] . "', purchased = '" . (int)$data['purchased'] . "', author = '" . $this->db->escape($data['author']) . "', product_id = '" . $this->db->escape($data['product_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "'");
			
			$review_id = $this->db->getLastId();
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "review_description WHERE review_id = '" . (int)$review_id . "'");
			foreach ($data['review_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "review_description SET 
				review_id = '" . (int)$review_id . "', 
				language_id = '" . (int)$language_id . "',
				answer = '" . $this->db->escape(strip_tags($value['answer'])) . "',
				good = '" . $this->db->escape(strip_tags($value['good'])) . "',
				bads = '" . $this->db->escape(strip_tags($value['bads'])) . "',
				text = '" . $this->db->escape(strip_tags($value['text'])) . "'");
			}
			
			return $review_id;
			
		}
		
		public function editReview($review_id, $data) {
			$this->db->query("UPDATE " . DB_PREFIX . "review SET addimage = '" . $this->db->escape($data['addimage']) . "', answer = '" . $this->db->escape(strip_tags($data['answer'])) . "', good = '" . $this->db->escape(strip_tags($data['good'])) . "', bads = '" . $this->db->escape(strip_tags($data['bads'])) . "', html_status = '" . (int)$data['html_status'] . "', purchased = '" . (int)$data['purchased'] . "', author = '" . $this->db->escape($data['author']) . "', product_id = '" . $this->db->escape($data['product_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "' WHERE review_id = '" . (int)$review_id . "'");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "review_description WHERE review_id = '" . (int)$review_id . "'");
			foreach ($data['review_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "review_description SET 
				review_id = '" . (int)$review_id . "', 
				language_id = '" . (int)$language_id . "',
				answer = '" . $this->db->escape(strip_tags($value['answer'])) . "',
				good = '" . $this->db->escape(strip_tags($value['good'])) . "',
				bads = '" . $this->db->escape(strip_tags($value['bads'])) . "',
				text = '" . $this->db->escape(strip_tags($value['text'])) . "'");
			}
			
			return $review_id;
		}
		
		public function getReviewDescriptions($review_id) {
			$review_description_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "review_description WHERE review_id = '" . (int)$review_id . "'");
			
			foreach ($query->rows as $result) {
				$review_description_data[$result['language_id']] = array(
				'answer'     	=> $result['answer'],
				'good' 			=> $result['good'],
				'bads' 			=> $result['bads'],
				'text' 			=> $result['text']						
				);
			}
			
			return $review_description_data;
		}
		
		public function deleteReview($review_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");
			
		}
		
		public function getReview($review_id) {
			$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "review r WHERE r.review_id = '" . (int)$review_id . "'");
			
			return $query->row;
		}
		
		public function getReviews($data = array()) {
			$sql =  $sql = "SELECT r.review_id, r.purchased, html_status, r.addimage, pd.name, r.author, r.rating, r.status, r.date_added, r.customer_id, r.good, r.bads, r.text FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";																																					  
			
			$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY r.date_added";	
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
		
		public function getTotalReviews() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review");
			
			return $query->row['total'];
		}
		
		public function getTotalReviewsAwaitingApproval() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE status = '0'");
			
			return $query->row['total'];
		}	
	}