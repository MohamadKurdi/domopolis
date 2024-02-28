<?php
	class ModelModuleReferrer extends Model {
		
		public function simple_decode($url){
			if (!$url){
				return '';
			}

			$decoded_url = urldecode($url );
			$pure_url = htmlspecialchars_decode($decoded_url);
			$pure_url = str_replace("&amp;","&",$pure_url);
						
			return $pure_url;
		}
		
		public function decode($url){
			if (!$url){
				return '';
			}

			$decoded_url = urldecode($url);
			$pure_url = htmlspecialchars_decode($decoded_url);
			$pure_url = str_replace("&amp;","&",$pure_url);
			
			$params=preg_split("/[?,&]/",$pure_url);
			$page=$params[0];
			array_shift($params);
			
			$patterns = $this->getPatterns();
			
			foreach( $patterns as $pattern ){
				if (strpos($page,$pattern['url_mask']) === FALSE )
                continue;
				
				foreach( $params as $param ){
					$pos = strpos($param, $pattern['url_param'] . "=");
					//echo $param . "<br>";
					if( $pos !== 0 )
                    continue;
					
					return $pattern["name"] . ": " . substr($param,strlen($pattern['url_param'])+1);
				}
			}
			
			return $pure_url;
		}
		
		public function getPatterns(){
			$patterns = $this->cache->get('referrer_patterns');
			if( $patterns )
            return $patterns;
			
			$patterns = array();
			$query = $this->db->query("SELECT name, url_mask, url_param FROM `referrer_patterns`");
			foreach($query->rows as $row ) {
				array_push( $patterns, array( "name" => $row["name"], "url_mask" => $row["url_mask"], "url_param" => $row["url_param"]) );
			}
			$this->cache->set('referrer_patterns',$patterns);
			return $patterns;
		}
		
		public function deleteRecord($pattern_id) {
			$this->db->query("DELETE FROM `referrer_patterns` WHERE `pattern_id` = '" . (int)$pattern_id . "'");
			$this->cache->delete('referrer_patterns');
		}
		
		public function updateRecord($data) {
			if($data['name'] == '' or $data['url_mask'] == '' or $data['url_param'] == '')
            return false;
			if($data['pattern_id'] != 0 ) {
				$this->db->query("UPDATE `referrer_patterns` SET `name` = '" . $this->db->escape($data['name']) . "', `url_mask` = '" . $this->db->escape($data['url_mask']) . "', `url_param` = '" . $this->db->escape($data['url_param']) . "' WHERE `pattern_id` = '" . (int)$data['pattern_id'] . "'");
				} else {
				$this->db->query("INSERT INTO `referrer_patterns` SET `name` = '" .  $this->db->escape($data['name']) . "', `url_mask` = '" . $this->db->escape($data['url_mask']) . "', `url_param` = '" . $this->db->escape($data['url_param']) . "'");
			}
			$this->cache->delete('referrer_patterns');
			return true;
		}
		
		// Get List
		public function getRecords($data = array()) {
			if (!$data) {
				$query = $this->db->query("SELECT * FROM `referrer_patterns` ORDER BY pattern_id");
				return $query->rows;
			}
			
			$sql = "SELECT * FROM `referrer_patterns` ";
			
			$sort_data = array('name', 'pattern');
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
				} else {
				$sql .= " ORDER BY pattern_id";
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
				} else {
				$sql .= " DESC";
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
			return  $query->rows;
		}
		
		// Total Records
		public function getRecordsCount() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `referrer_patterns`;");
			return $query->row['total'];
		}		
		
	}