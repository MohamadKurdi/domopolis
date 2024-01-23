<?php
	class ModelDesignLayout extends Model {	
		public function getLayout($route) {
			$query = $this->db->query("SELECT * FROM layout_route WHERE '" . $this->db->escape($route) . "' LIKE CONCAT(route, '%') AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1");
			
			if ($query->num_rows) {
				return $query->row['layout_id'];
				} else {
				return 0;	
			}
		}
		
		public function getLayoutTemplateByLayoutId($layout_id) {
			$query = $this->db->query("SELECT * FROM layout_route WHERE layout_id = '" . (int)($layout_id) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1");
			
			if ($query->num_rows) {
				return $query->row['template'];
				} else {
				return false;	
			}
		}
		
		public function getLayoutForAllPages(){
			return 18;
		}
	}