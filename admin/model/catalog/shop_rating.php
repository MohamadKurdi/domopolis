<?php
	class ModelCatalogShopRating extends Model {
		
		public function getAllRatings(){
			
			$sql = "SELECT * FROM " . DB_PREFIX . "shop_rating ORDER BY date_added DESC";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
			
		}
		public function getCustomTypes(){
			
			$sql = "SELECT * FROM " . DB_PREFIX . "shop_rating_custom_types ORDER BY id";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
			
		}
		public function getRateCustomRatings($rate_id){
			$customs =$this->getCustomTypes();
			
			$rates= array();
			foreach($customs as $custom){
				$sql = "SELECT * FROM " . DB_PREFIX . "shop_rating_custom_values WHERE custom_id = '".$custom['id']."' AND rate_id = '".$rate_id."' ORDER BY id";
				$query = $this->db->query($sql);
				$result = $query->row;
				
				if($result = $query->row){
					$rates[$custom['id']] = array(
					'type_id' => $custom['id'],
					'title' => $custom['title'],
					'value' => $result['custom_value'],
					);
				}
			}
			
			return $rates;
			
		}
		
		public function getRating($rate_id){
			
			$sql = "SELECT * FROM " . DB_PREFIX . "shop_rating WHERE rate_id = '".$rate_id."'";
			
			$query = $this->db->query($sql);
			
			return $query->row;
			
		}
		public function getRatingAnswer($rate_id){
			
			$sql = "SELECT * FROM " . DB_PREFIX . "shop_rating_answers WHERE rate_id = '".$rate_id."'";
			
			$query = $this->db->query($sql);
			
			return $query->row;
			
		}
		public function getRatingAnswers(){
			
			$sql = "SELECT id, rate_id FROM " . DB_PREFIX . "shop_rating_answers WHERE comment <> ''";
			
			$query = $this->db->query($sql);
			
			$arr = array();
			foreach($query->rows as $ans){
				$arr[$ans['rate_id']] = $ans['id'];
			}
			return $arr;
			
		}
		public function customerRatingsCount($customer_id){
			
			$store_id =$this->config->get('config_store_id');
			
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "shop_rating WHERE store_id ='" . $store_id . "' AND customer_id = '".$customer_id."'";
			
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
			
		}
		
		public function addAnswer($rate_id, $answer)
		{
			
			
			if ($answer['answer_id']) {
				$sql = "UPDATE " . DB_PREFIX . "shop_rating_answers SET comment='" . $answer['answer'] . "' WHERE id = '" . $answer['answer_id'] . "'";
				$this->db->query($sql);
				$answer_id=$answer['answer_id'];
				} else {
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "shop_rating_answers SET rate_id = '" . $rate_id . "', user_id = '" . (int)$this->user->getId() . "', date_added =  NOW(), comment = '" . $this->db->escape($answer['answer']) . "'");
				$answer_id = $this->db->getLastId();
				
			}
			if($answer_id && $this->config->get('shop_rating_notify') && $answer['answer']){
				$answer_item = $this->getRatingAnswer($rate_id);
				
				if ($answer_item['notified'] != 1) {
					$rating = $this->getRating($rate_id);
					$this->load->language('catalog/shop_rating');
					
					$subject = sprintf($this->language->get('text_mail_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
					
					$message = sprintf($this->language->get('text_mail_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n";
					$message .= $this->language->get('text_mail_comment') . "\n";
					$message .= html_entity_decode($answer['answer'], ENT_QUOTES, 'UTF-8') . "\n\n";
					
					$mail = new Mail($this->registry); 
	
					$mail->setTo($rating['customer_email']);
					$mail->setFrom($this->config->get('config_email'));					
					$mail->setSender(html_entity_decode($this->config->get('config_mail_trigger_name_from'), ENT_QUOTES, 'UTF-8'));
					$mail->setSubject($subject);
					$mail->setText($message);
					$mail->send();
					
					
					$emails = explode(',', $this->config->get('config_mail_alert'));
					
					foreach ($emails as $email) {
						if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
							$mail->setTo($email);
							$mail->send();
						}
					}
					
					
					$sql = "UPDATE " . DB_PREFIX . "shop_rating_answers SET notified='1' WHERE id = '" . $answer_id . "'";
					$this->db->query($sql);
				}
			}
			
		}
		
		
		public  function createCustomType($title){
			$this->db->query("INSERT INTO " . DB_PREFIX . "shop_rating_custom_types SET title = '" . $this->db->escape($title) . "', status = '0' ");
			return array('id' => $this->db->getLastId(), 'title' => $title);
			
		}
		public  function statusCustomType($type_id){
			$sql = "UPDATE " . DB_PREFIX . "shop_rating_custom_types SET status=IF(status=0, 1, 0) WHERE id = '".$type_id."'";
			if($this->db->query($sql)){
				return array('id' => $type_id, 'status' => 'success');
				}else{
				return array('id' => $type_id, 'status' => 'error');
			}
		}
		public  function removeCustomType($type_id){
			$sql = "DELETE FROM " . DB_PREFIX . "shop_rating_custom_types WHERE id = '".$type_id."'";
			if($this->db->query($sql)){
				return array('id' => $type_id, 'status' => 'success');
				}else{
				return array('id' => $type_id, 'status' => 'error');
			}
		}
		public  function changeStatus($rate_id){
			$sql = "UPDATE " . DB_PREFIX . "shop_rating SET rate_status=IF(rate_status=0, 1, 0) WHERE rate_id = '".$rate_id."'";
			
			if($this->db->query($sql)){
				return 'OK';
				}else{
				return 'DB Error';
			}
			
			
		}
		public  function changeDate($rate_id, $new_date){
			
			$date = date('Y-m-d H:i', strtotime($new_date)).':00';
			
			$sql = "UPDATE " . DB_PREFIX . "shop_rating SET date_added='".$date."' WHERE rate_id = '".$rate_id."'";
			$this->db->query($sql);
			
		}
		
		
		public  function changeComment($rate_id, $rating_params){
			
			$sql = "UPDATE " . DB_PREFIX . "shop_rating SET ";
			
			$act = false;
			
			if(isset($rating_params['comment'])){
				$sql .= " comment='". $rating_params['comment'] . "'";
				$act = true;
			}
			
			if(isset($rating_params['good'])){
				if($act){
					$sql .= ',';
				}
				$sql .= " good='" . $rating_params['good'] . "'";
				$act = true;
			}
			
			if(isset($rating_params['bad'])){
				if($act){
					$sql .= ',';
				}
				$sql .= " bad='" . $rating_params['bad'] . "'";
				$act = true;
			}
			
			$sql .= "  WHERE rate_id = '".$rate_id."'";
			
			if($act){
				$this->db->query($sql);
			}
			
						
			$this->db->query("DELETE FROM " . DB_PREFIX . "shop_rating_description WHERE rate_id = '" . (int)$rate_id . "'");
			foreach ($rating_params['shop_rating_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "shop_rating_description SET 
				rate_id = '" . (int)$rate_id . "', 
				language_id = '" . (int)$language_id . "',
				comment = '" . $this->db->escape(strip_tags($value['comment'])) . "',
				good = '" . $this->db->escape(strip_tags($value['good'])) . "',
				bad = '" . $this->db->escape(strip_tags($value['bads'])) . "'");
			}
			
		}
		
		public function getShopRatingDescriptions($rate_id) {
			$shop_rating_description_description_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shop_rating_description WHERE rate_id = '" . (int)$rate_id . "'");
			
			foreach ($query->rows as $result) {
				$shop_rating_description_description_data[$result['language_id']] = array(
				'comment'     	=> $result['comment'],
				'good' 			=> $result['good'],
				'bad' 			=> $result['bad'],		
				'answer' 			=> $result['answer'],
				);
			}
			
			return $shop_rating_description_description_data;
		}
		
		
		
		public function update() {
		}
		public function install() {
		}
		public function uninstall() {
			
		}
		
		
		public function toogleEvent($set) {
			
			$sql = "SELECT COUNT(*) AS srrm FROM " . DB_PREFIX . "event WHERE `code` = 'shop_rating_request_mail'" ;
			$events = $this->db->query($sql);
			
			
			if($events->row['srrm'] == 0 && $set == 1){
				$this->load->model('extension/event');
				$this->model_extension_event->addEvent('shop_rating_request_mail', 'post.order.history.add', 'information/shop_rating/request_mail');
				}elseif($events->row['srrm'] != 0 && $set == 0){
				$this->load->model('extension/event');
				$this->model_extension_event->deleteEvent('shop_rating_request_mail');
			}
		}
		
		
	}	