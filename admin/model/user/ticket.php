<?

class ModelUserTicket extends Controller {
	private $fields = array(
		'user_group_id',
		'user_id',
		'from_user_id',
		'entity_type',
		'entity_id',
		'entity_string',
		'is_recall',
		'message',
		'priority',
		'date_added',
		'date_max',
		'status'
	);
	
	public function	addTicket($data){
				
		if ($data && is_array($data)){
			
			foreach ($this->fields as $_field){
				
				if ($_field == 'entity_type' && (!isset($data[$_field]) || !$data[$_field])){
					$data[$_field] = 'general';
				}
				
				if (!isset($data[$_field])){
					$data[$_field] = '';
				}
			}				
		
			$this->db->query("INSERT INTO tickets SET 
				user_group_id	= '" . (int)$data['user_group_id'] . "',
				user_id	= '" . (int)$data['user_id'] . "',
				from_user_id	= '" . $this->user->getID() . "',
				entity_type	= '" . $this->db->escape($data['entity_type']) . "',
				entity_id	= '" . (int)$data['entity_id'] . "',
				is_recall	= '" . (int)$data['is_recall'] . "',
				message	= '" . $this->db->escape($data['message']) . "',
				reply	= '',
				priority	= '" . $this->db->escape($data['priority']) . "',
				date_added	= NOW(),
				date_max	= '" . $this->db->escape($data['date_max']) . "',
				date_at	= '" . $this->db->escape($data['date_at']) . "',
				status	= '1'						
			");			
			
			return $this->db->getLastID();
			
		} else return false;
				
		
	}
	
	public function getTicket($ticket_id){
		$sql = "SELECT * FROM tickets t WHERE ticket_id = '". (int)$ticket_id ."' LIMIT 1";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getTickets($data){
		$data['user_id']       = $this->user->getID();
		$data['user_group_id'] = $this->user->getUserGroup();	
		
		if (!isset($data['sort']) || !$data['sort']){
			$data['sort'] = 'sort_order ASC';
		}
		
		if (!isset($data['by_group'])){
			$data['by_group'] = 0;
		}
		
				
		$sql = "SELECT * FROM tickets t WHERE 1";
		
		if ($data['by_group']){
			$sql .= " AND t.user_group_id = '" . (int)$data['user_group_id'] . "'";
		} else {
			if (!isset($data['filter_from_user_id'])){
				$sql .= " AND t.user_id = '" . (int)$data['user_id'] . "'";
			}
		}
		
		if (isset($data['filter_date_added'])){
			$sql .= " AND DATE(t.date_added) = '" . date('Y-m-d', strtotime($this->db->escape($data['filter_date_added']))) . "'";
		}
		
		if (isset($data['filter_date_at'])){
			$sql .= " AND DATE(t.date_at) = '" . date('Y-m-d', strtotime($this->db->escape($data['filter_date_at']))) . "'";
		}
		
		if (isset($data['filter_priority'])){
			$sql .= " AND priority = '" . $this->db->escape($data['filter_priority']) . "'";
		}
		
		if (isset($data['filter_orders'])){
			$sql .= " AND entity_type = 'order' AND entity_id > 0";
		}
		
		if (isset($data['filter_customers'])){
			$sql .= " AND entity_type = 'customer' AND entity_id > 0";
		}
		
		if (isset($data['filter_calls'])){
			$sql .= " AND ((entity_type = 'call' AND LENGTH(entity_string)>0) OR is_recall = 1)";
		}
		
		if (isset($data['filter_from_user_id'])){
			$sql .= " AND from_user_id = '" . (int)$data['filter_from_user_id'] . "'";
		}
		
		if (isset($data['filter_done'])){
			$sql .= " AND status = 0";
		} else {
			$sql .= " AND status = 1";
		}
			
		$sql .= " ORDER BY " . $data['sort'];
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 40;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	
	
	
	
	
}