<?php
	class ModelUserUser extends Model {
		
		public function deleteUser($user_id) {
			$this->db->query("DELETE FROM `user` WHERE user_id = '" . (int)$user_id . "'");
		}
		
		public function getUser($user_id) {
			$query = $this->db->ncquery("SELECT *, CONCAT(firstname, ' ', lastname) as realname  FROM `user` WHERE user_id = '" . (int)$user_id . "' AND status = 1");
			
			return $query->row;
		}
		
	}	