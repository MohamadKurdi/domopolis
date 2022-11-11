<?php
	class ModelCatalogactiontemplate extends Model {
		
		public function getactiontemplate($actiontemplate_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM actiontemplate a 
				LEFT JOIN actiontemplate_description ad ON (a.actiontemplate_id = ad.actiontemplate_id AND language_id = '" . $this->config->get('config_language_id') . "')  
				WHERE a.actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			return $query->row;
		}
		
		public function getKeyWords($actiontemplate_id) {
			$keywords = array(); 
			
			$query = $this->db->query("SELECT * FROM url_alias WHERE query = 'actiontemplate_id=" . (int)$actiontemplate_id . "'");
			
			foreach ($query->rows as $result) {
				$keywords[$result['language_id']] = $result['keyword'];					
			}
			
			return $keywords;
		}
		
		public function getActionTemplateHistory($customer_id, $actiontemplate_id){
			
			$query = $this->db->query("SELECT DISTINCT * FROM emailmarketing_logs WHERE customer_id = '" . (int)$customer_id . "' AND  actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			return $query->rows;
			
		}
		
		public function getActionTemplateLastHistory($customer_id, $actiontemplate_id){
			
			$query = $this->db->query("SELECT DISTINCT * FROM emailmarketing_logs WHERE customer_id = '" . (int)$customer_id . "' AND  actiontemplate_id = '" . (int)$actiontemplate_id . "' ORDER BY date_sent DESC LIMIT 1");
			
			return $query->row;
			
		}
		
		public function getActionTemplateLastHistoryDate($customer_id, $actiontemplate_id){
			
			$query = $this->db->query("SELECT date_sent FROM emailmarketing_logs WHERE customer_id = '" . (int)$customer_id . "' AND  actiontemplate_id = '" . (int)$actiontemplate_id . "' ORDER BY date_sent DESC LIMIT 1");

			return $query->num_rows?$query->row['date_sent']:false;
			
		}
		
		public function getActionTemplatesHistoryByCustomer($customer_id){
			
			$query = $this->db->query("SELECT 
			DISTINCT at.image, el.date_sent, atd.title,
			COUNT(emailmarketing_log_id) as count
			FROM emailmarketing_logs el 
			LEFT JOIN actiontemplate at ON el.actiontemplate_id = at.actiontemplate_id
			LEFT JOIN actiontemplate_description atd ON (at.actiontemplate_id = atd.actiontemplate_id AND language_id = 2)
			WHERE customer_id = '" . (int)$customer_id . "'
			GROUP BY el.actiontemplate_id ORDER BY el.date_sent DESC");
			
			return $query->rows;
			
		}
		
		public function getactiontemplates($data = array()) {
			$sql = "SELECT * FROM actiontemplate i LEFT JOIN actiontemplate_description id ON (i.actiontemplate_id = id.actiontemplate_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
			'id.title',
			'i.sort_order'
			);		
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY id.title";	
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
					$data['limit'] = 100;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;	
		}
		
		public function getactiontemplateDescriptions($actiontemplate_id) {
			$actiontemplate_description_data = array();
			
			$query = $this->db->query("SELECT * FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			foreach ($query->rows as $result) {
				$actiontemplate_description_data[$result['language_id']] = array(
				'seo_title'         => $result['seo_title'],
                'title'             => $result['title'],
                'meta_description'  => $result['meta_description'],
                'meta_keyword'      => $result['meta_keyword'],
				'description'       => $result['description']
				);
			}
			
			return $actiontemplate_description_data;
		}
		
		public function getactiontemplateDescription($actiontemplate_id, $language_id) {
			$actiontemplate_description_data = array();
			
			$query = $this->db->query("SELECT * FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND language_id = '" . (int)$language_id . "'");
			
			
			
			return $query->row['description'];
		}
		
		public function getactiontemplateTitle($actiontemplate_id, $language_id) {
			$actiontemplate_description_data = array();
			
			$query = $this->db->query("SELECT * FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND language_id = '" . (int)$language_id . "'");
			
			
			
			return $query->row['seo_title'];
		}
		
		
		public function getactiontemplateStores($actiontemplate_id) {
			$actiontemplate_store_data = array();
			
			$query = $this->db->query("SELECT * FROM actiontemplate_to_store WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			foreach ($query->rows as $result) {
				$actiontemplate_store_data[] = $result['store_id'];
			}
			
			return $actiontemplate_store_data;
		}
		
		
		public function getTotalactiontemplates() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM actiontemplate");
			
			return $query->row['total'];
		}	
		
	}
?>