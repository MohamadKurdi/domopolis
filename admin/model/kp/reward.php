<?
	class ModelKpReward extends Model {
	
	
		public function editReward($entity_id, $entity_type, $data){
		
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "entity_reward WHERE entity_id = '" . (int)$entity_id . "' AND entity_type = '" . $this->db->escape($entity_type) . "'");

			if (isset($data['reward'])) {
				foreach ($data['reward'] as $reward) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "entity_reward 
						SET
						store_id 	= '" . (int)$reward['store_id'] . "', 
						entity_type = '" . $this->db->escape($entity_type) . "', 
						entity_id 	= '" . (int)$entity_id . "', 
						percent 	= '" . (int)$reward['percent'] . "',
						points 		= '" . (int)$reward['points'] . "', 
						coupon_acts = '" . (float)$reward['coupon_acts'] . "', 
						date_start 	= '" . $this->db->escape($reward['date_start']) . "', 
						date_end 	= '" . $this->db->escape($reward['date_end']) . "'");
				}
			}
		}
		
		
		public function getEntityRewards($entity_id, $entity_type) {			
			$reward_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "entity_reward WHERE entity_id = '" . (int)$entity_id . "' AND entity_type = '" . $this->db->escape($entity_type) . "'");
			
			
			return $query->rows;
		}

	
	}
		