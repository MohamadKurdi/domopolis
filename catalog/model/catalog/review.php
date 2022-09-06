<?php
	class ModelCatalogReview extends Model {
		private $fields = array('text', 'good', 'bads', 'answer');
		
		public function getLastReview(){
			$query = $this->db->query("SELECT review_id FROM review WHERE customer_id = '".$this->customer->getId()."' ORDER BY `review_id` DESC LIMIT 1");
			return $query->row['review_id'];
		}
		
		private function getReviewTranslation($review){
			
			
			$query = $this->db->query("SELECT * FROM review_description WHERE review_id = '" . $review['review_id'] . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");
			
			if ($query->row){
				foreach ($this->fields as $field){
					if (!empty(trim($query->row[$field]))){
						$review[$field] = $query->row[$field];
					}				
				}						
			}
			
		 	return $review;
		}
		
		private function getReviewsTranslation($reviews){
			if (!empty($reviews['review_id'])){
				return $this->getReviewTranslation($reviews);
			}
			
			$parsed = array();
			
			foreach ($reviews as $review){
				$parsed[] = $this->getReviewTranslation($review);
				
			}
			
			return $parsed;
		}
		
		
		public function addReview($product_id, $data) {
		
			if (!isset($data['addimage'])) {
				$data['addimage'] = '';
			}
			if (!isset($data['good'])) {
				$data['good'] = '';
			}
			if (!isset($data['bads'])) {
				$data['bads'] = '';
			}
			
			if (!$this->config->get('config_review_statusp'))  {
				$review_statusp = 0;
				} else {
				$review_statusp = 1;
			}
			
			$this->db->query("INSERT INTO review SET addimage = '" . $this->db->escape($data['addimage']) . "', good = '" . $this->db->escape($data['good']) . "', bads = '" . $this->db->escape($data['bads']) . "', status = '" . $review_statusp . "', author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
			
			$review_id = $this->db->getLastId();
		/*
			$this->load->model('catalog/product');

   			$product_info = $this->model_catalog_product->getProduct($product_id);
			if(empty($product_info)) return false;

			$template = new EmailTemplate($this->request, $this->registry);
            
			$template->addData($data, 'review');
			$template->addData($product_info, 'product');

			$template->data['product_link'] = $this->url->link('product/product', 'product_id=' . $product_id);
			$template->data['product_link_tracking'] = $template->getTracking($template->data['product_link']);
			$template->data['review_approve'] = (defined('HTTP_ADMIN') ? HTTP_ADMIN : HTTP_SERVER.'admin/') . 'index.php?route=catalog/review/update&review_id=' . $review_id;

			$template->load('product.review');

			$template->send();
		*/
		}
		
		public function getDickAssReviewsByProductId($product_id, $start = 0, $limit = 20) {
			
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 20;
			}
			
			$query = $this->db->query("SELECT 
			r.review_id, 
			r.answer,
			r.html_status, 
			r.purchased, 
			r.addimage, 
			r.good, 
			r.bads, 
			r.author, 
			r.rating, 
			r.text, 
			rd.text as text_overload,
				rd.good as good_overload,
				rd.answer as answer_overload,
				rd.bads as bads_overload,
			p.product_id, 
			pd.name, 
			p.price, 
			p.image, 
			r.date_added 
			FROM review r 
			LEFT JOIN review_description rd ON (r.review_id = rd.review_id) 
			LEFT JOIN product p ON (r.product_id = p.product_id) 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			WHERE (p.product_id = '" . (int)$product_id . "' OR p.product_id IN (SELECT p2.product_id FROM product p2 WHERE p2.is_option_for_product_id = '" . (int)$product_id . "') )
			AND p.date_available <= NOW() 
			AND p.status = '1' 
			AND r.status = '1' 
			AND rd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
			
			foreach ($query->rows as &$row){
				
				foreach ($this->fields as $field){
					if (!empty(trim($row[$field . '_overload']))){
						$row[$field] = $row[$field . '_overload'];
					}				
				}	
			}
			
			return $query->rows;
			
		}
		
		public function getDickAssTotalReviewsByProductId($product_id) {
			$query = $this->db->query("
			SELECT COUNT(*) AS total FROM review r 
			LEFT JOIN product p ON (r.product_id = p.product_id) 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			WHERE (p.product_id = '" . (int)$product_id . "' OR p.product_id IN (SELECT p2.product_id FROM product p2 WHERE p2.is_option_for_product_id = '" . (int)$product_id . "') )
			AND p.date_available <= NOW() 
			AND p.status = '1' 
			AND r.status = '1' 
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			return $query->row['total'];
		}
		
		public function getBestFuckenReviewForProductID($product_id) {
			
			$query = $this->db->query("
			SELECT r.review_id, 
			r.answer, 
			r.html_status, 
			r.purchased, 
			r.addimage, 
			r.good, 
			r.bads, 
			r.author, 
			r.rating, 
			r.text, 
			rd.text as text_overload,
			rd.good as good_overload,
			rd.answer as answer_overload,
			rd.bads as bads_overload,
			p.product_id, 
			pd.name, 
			p.price, 
			p.image, 
			r.date_added 
			FROM review r 
			LEFT JOIN review_description rd ON (r.review_id = rd.review_id) 
			LEFT JOIN product p ON (r.product_id = p.product_id) 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			WHERE p.product_id = '" . (int)$product_id . "' 
			AND p.date_available <= NOW() 
			AND p.status = '1' 
			AND r.status = '1'
			AND r.rating >= '4' 
			AND rd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			ORDER BY r.date_added DESC LIMIT 1");
			
			
			foreach ($query->rows as &$row){
				
				foreach ($this->fields as $field){
					if (!empty(trim($row[$field . '_overload']))){
						$row[$field] = $row[$field . '_overload'];
					}				
				}	
			}
			
			return $query->rows;
			
			
		}
		
		
		public function getReviewsByProductId($product_id, $start = 0, $limit = 20, $filter_length = false) {
			if ($start <= 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 20;
			}

			$sql = "SELECT 
			r.review_id, 
			r.answer, 
			r.html_status, 
			r.purchased, 
			r.addimage, 
			r.good, 
			r.bads, 
			r.author, 
			r.rating, 
			r.text, 
			rd.text as text_overload,
			rd.good as good_overload,
			rd.answer as answer_overload,
			rd.bads as bads_overload,
			p.product_id, 
			pd.name, 
			p.price, 
			p.image, 
			r.date_added 
			FROM review r 
			LEFT JOIN product p ON (r.product_id = p.product_id)
			LEFT JOIN review_description rd ON (r.review_id = rd.review_id) 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() 
			AND p.status = '1' 
			AND r.status = '1'";

			if ($filter_length){
				$sql .= " AND LENGTH(r.text) >= " . (int)$filter_length;
			}

			$sql .= " AND rd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;
			
			$query = $this->db->query($sql);
			
			
			foreach ($query->rows as &$row){				
				foreach ($this->fields as $field){
					if (!empty(trim($row[$field . '_overload']))){
						$row[$field] = $row[$field . '_overload'];
					}				
				}	
			}
			
			return $query->rows;
		}
		
		public function getTotalReviewsByProductId($product_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM review r LEFT JOIN product p ON (r.product_id = p.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			return $query->row['total'];
		}
	}		