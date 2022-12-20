<?
class ModelKpContent extends Model {

	public function addContent($data){

	if ($this->user->isLogged() && $this->user->getCountContent() && $this->registry->mainDBIsUsed()){			

			$this->db->query("INSERT INTO user_content SET 
				user_id 	= '" . (int)$this->user->getID() . "',
				datetime 	= NOW(),
				date 		= NOW(),
				action    	= '" . $this->db->escape($data['action']) . "',
				entity_type = '" . $this->db->escape($data['entity_type']) . "',
				entity_id 	= '" . (int)$data['entity_id'] . "'
				");

		}
	}


	public function getDistinctUsers($filter_data){

		return $this->db->query("SELECT DISTINCT user_id FROM user_content WHERE `date` >= '" . $this->db->escape($filter_data['date_from']) . "' AND `date` <= '" . $this->db->escape($filter_data['date_to']) . "'")->rows;		

	}

	public function getUserStats($user_id, $filter_data){
		$results = [];

		$query = $this->db->query("SELECT action, count(entity_id) as total FROM user_content 
			WHERE user_id 	= '" . (int)$user_id . "'
			AND `date` 			>= '" . $this->db->escape($filter_data['date_from']) . "' 
			AND `date` 		<= '" . $this->db->escape($filter_data['date_to']) . "'			
			AND entity_type = '" . $this->db->escape($filter_data['entity_type']) . "'			
			GROUP BY action
			"
		);		


		foreach ($query->rows as $row){
			$results[$row['action']] = $row['total'];
		}

		return $results;

	}
}
