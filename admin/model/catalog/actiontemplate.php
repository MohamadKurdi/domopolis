<?php
	class ModelCatalogActionTemplate extends Model {
		public function addActionTemplate($data) {

			if ($data['use_for_manual']){
				$this->db->query("UPDATE actiontemplate SET use_for_manual = 0 WHERE 1");
			}
			
			$this->db->query("INSERT INTO actiontemplate SET 
				sort_order 		= '" . (int)$data['sort_order'] . "', 
				bottom 			= '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', 
				status 			= '" . (int)$data['status'] . "', 
				use_for_manual 	= '" . (int)$data['use_for_manual'] . "',
				data_function	= '" . $this->db->escape($data['data_function']) . "',
				image 			= '" . $this->db->escape($data['image']) . "'");
			
			$actiontemplate_id = $this->db->getLastId(); 
			
			foreach ($data['actiontemplate_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO actiontemplate_description SET 
					actiontemplate_id 	= '" . (int)$actiontemplate_id . "', 
					language_id 		= '" . (int)$language_id . "', 
					seo_title 			= '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', 
					title 				= '" . $this->db->escape($value['title']) . "',
					file_template 		= '" . $this->db->escape($value['file_template']) . "', 
					meta_description 	= '" . $this->db->escape($value['meta_description']) . "', 
					meta_keyword 		= '" . $this->db->escape($value['meta_keyword']) . "',  
					description 		= '" . $this->db->escape($value['description']) . "'");
			}
			
			
			if ($data['keyword']) {
				foreach ($data['keyword'] as $language_id => $keyword) {
					if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'actiontemplate_id=" . (int)$actiontemplate_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
				}
			}
		}
		
		public function editActionTemplate($actiontemplate_id, $data) {

			if ($data['use_for_manual']){
				$this->db->query("UPDATE actiontemplate SET use_for_manual = 0 WHERE 1");
			}

			$this->db->query("UPDATE actiontemplate SET 
				sort_order 		= '" . (int)$data['sort_order'] . "', 
				bottom 			= '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', 
				status 			= '" . (int)$data['status'] . "', 
				use_for_manual 	= '" . (int)$data['use_for_manual'] . "', 
				data_function	= '" . $this->db->escape($data['data_function']) . "',
				image 			= '" . $this->db->escape($data['image']) . "' 
				WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			$this->db->query("DELETE FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			foreach ($data['actiontemplate_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO actiontemplate_description SET 
					actiontemplate_id 	= '" . (int)$actiontemplate_id . "', 
					language_id 		= '" . (int)$language_id . "', 
					seo_title 			= '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', 
					title 				= '" . $this->db->escape($value['title']) . "',
					file_template 		= '" . $this->db->escape($value['file_template']) . "', 
					meta_description 	= '" . $this->db->escape($value['meta_description']) . "', 
					meta_keyword 		= '" . $this->db->escape($value['meta_keyword']) . "',  
					description 		= '" . $this->db->escape($value['description']) . "'");
			}
			
			
			$this->db->query("DELETE FROM url_alias WHERE query = 'actiontemplate_id=" . (int)$actiontemplate_id. "'");
			
			if ($data['keyword']) {
				foreach ($data['keyword'] as $language_id => $keyword) {
					if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'actiontemplate_id=" . (int)$actiontemplate_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
				}
			}
		}
		
		public function deleteActionTemplate($actiontemplate_id) {
			$this->db->query("DELETE FROM actiontemplate WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			$this->db->query("DELETE FROM emailmarketing_logs WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			$this->db->query("DELETE FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");		
			$this->db->query("DELETE FROM url_alias WHERE query = 'actiontemplate_id=" . (int)$actiontemplate_id . "'");
		}	
		
		public function getActionTemplate($actiontemplate_id, $language_id = false) {
			if (!$language_id){
				$language_id = $this->config->get('config_language_id');
			}

			$query = $this->db->query("SELECT DISTINCT at.*, ad.title, ad.seo_title, ad.file_template FROM 
				actiontemplate at LEFT JOIN actiontemplate_description ad ON (at.actiontemplate_id = ad.actiontemplate_id AND language_id = '" . (int)$language_id . "')
				WHERE at.actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			return $query->row;
		}

		public function getActionTemplateCoupons($actiontemplate_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM coupon WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			return $query->rows;
		}
		
		public function getKeyWords($actiontemplate_id) {
			$keywords = [];
			
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
		
		public function getActionTemplateSendCount($actiontemplate_id){
			$query = $this->db->query("SELECT count(*) as total FROM emailmarketing_logs WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			return $query->row['total'];
		}		
		
		public function getActionTemplateSendCountByUser($actiontemplate_id, $user_id){
			$query = $this->db->query("SELECT count(*) as total FROM emailmarketing_logs WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND user_id = '" . (int)$user_id . "'");
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
			} else {
				return 0;
			}
		}	
		
		public function getActionTemplateSendCountByMonth($actiontemplate_id, $user_id, $month, $year){
			$query = $this->db->query("SELECT count(*) as total FROM emailmarketing_logs WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND MONTH(date_sent) = '" . (int)$month . "' AND YEAR(date_sent) = '" . (int)$year . "' AND user_id = '" . (int)$user_id . "'");
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
			} else {
				return 0;
			}
		}
		
		public function getActionTemplateSendParamsCountByMonth($actiontemplate_id, $user_id, $month, $year, $param){
			$query = $this->db->query("SELECT SUM(`" . $this->db->escape($param) . "`) as total FROM emailmarketing_logs WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND MONTH(date_sent) = '" . (int)$month . "' AND YEAR(date_sent) = '" . (int)$year . "' AND user_id = '" . (int)$user_id . "' GROUP BY actiontemplate_id");
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
			} else {
				return 0;
			}
		}
		
		public function getActionTemplateDatesCount($actiontemplate_id){
			$query = $this->db->query("SELECT MIN(date_sent) as min_date, MAX(date_sent) as max_date FROM emailmarketing_logs WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			return $query->row;
		}
		
		public function getActionTemplateLastHistory($customer_id, $actiontemplate_id){
			$query = $this->db->query("SELECT DISTINCT * FROM emailmarketing_logs WHERE customer_id = '" . (int)$customer_id . "' AND  actiontemplate_id = '" . (int)$actiontemplate_id . "' ORDER BY date_sent DESC LIMIT 1");
			
			return $query->row;
		}
		
		public function getActionTemplatesHistoryByCustomer($customer_id){
			$query = $this->db->query("SELECT 
			DISTINCT at.image, el.date_sent, atd.title,
			COUNT(emailmarketing_log_id) as count
			FROM emailmarketing_logs el 
			JOIN actiontemplate at ON el.actiontemplate_id = at.actiontemplate_id
			LEFT JOIN actiontemplate_description atd ON (at.actiontemplate_id = atd.actiontemplate_id AND language_id = 2)
			WHERE customer_id = '" . (int)$customer_id . "'
			GROUP BY el.actiontemplate_id ORDER BY el.date_sent DESC");
			
			return $query->rows;
		}
		
		public function getActionTemplates($data = array()) {
			$sql = "SELECT i.*, id.*, c.code, c.currency, c.manager_id FROM actiontemplate i 
					LEFT JOIN actiontemplate_description id ON (i.actiontemplate_id = id.actiontemplate_id) 
					LEFT JOIN coupon c ON (i.actiontemplate_id = c.actiontemplate_id) 
					WHERE i.status = 1 AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
			'id.title',
			'i.sort_order'
			);	
			
			if (!empty($data['manager_id'])){				
				$sql .= " AND (ISNULL(c.manager_id) OR c.manager_id = 0 OR c.manager_id = '" . (int)$data['manager_id'] . "')";			
			}

			if (!empty($data['filter_use_for_manual'])){				
				$sql .= " AND use_for_manual = 1";			
			}
			
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
			
			if (!empty($data['filter_use_for_manual']) && $query->num_rows){
				return $query->row;
			}

			return $query->rows;	
		}
		
		public function getactiontemplateDescriptions($actiontemplate_id) {
			$actiontemplate_description_data = [];
			
			$query = $this->db->query("SELECT * FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			foreach ($query->rows as $result) {
				$actiontemplate_description_data[$result['language_id']] = array(
				'seo_title'         => $result['seo_title'],
                'title'             => $result['title'],
                'meta_description'  => $result['meta_description'],
                'meta_keyword'      => $result['meta_keyword'],
				'description'       => $result['description'],
				'file_template'     => $result['file_template']
				);
			}
			
			return $actiontemplate_description_data;
		}
		
		public function getactiontemplateDescription($actiontemplate_id, $language_id) {
			$actiontemplate_description_data = [];
			
			$query = $this->db->query("SELECT * FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND language_id = '" . (int)$language_id . "'");

			return $query->row['description'];
		}
		
		public function getactiontemplateTitle($actiontemplate_id, $language_id) {
			$actiontemplate_description_data = [];
			
			$query = $this->db->query("SELECT * FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND language_id = '" . (int)$language_id . "'");

			return $query->row['seo_title'];
		}
		
		public function getActionTemplateName($actiontemplate_id) {
			$query = $this->db->query("SELECT * FROM actiontemplate_description WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

			return isset($query->row['title'])?$query->row['title']:false;
		}	
		
		public function getactiontemplateStores($actiontemplate_id) {
			$actiontemplate_store_data = [];
			
			$query = $this->db->query("SELECT * FROM actiontemplate_to_store WHERE actiontemplate_id = '" . (int)$actiontemplate_id . "'");
			
			foreach ($query->rows as $result) {
				$actiontemplate_store_data[] = $result['store_id'];
			}
			
			return $actiontemplate_store_data;
		}
		
		
		public function getTotalActionTemplates() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM actiontemplate");
			
			return $query->row['total'];
		}	
	}