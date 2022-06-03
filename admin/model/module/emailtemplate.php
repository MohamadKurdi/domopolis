<?php
	/**
		* HTML Email template extension
		*
		* @author: Ben Johnson, opencart-templates
		* @email: info@opencart-templates.co.uk
		* @website: http://www.opencart-templates.co.uk
		*
	*/
	class ModelModuleEmailTemplate extends Model {
		
		
		
		public function getCustomerByOrderId($order_id){
			
			$query = $this->db->query("SELECT customer_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			if ($query->row && isset($query->row['customer_id'])){
				return $query->row['customer_id'];
				} else {
				return 0;
			}
		}
		/**
			* Get Email Template Config
			*
			* @param int||array $identifier
			* @param bool $outputFormatting
			* @return array
		*/
		public function getConfig($data = false, $outputFormatting = false, $keyCleanUp = false){
			$p = DB_PREFIX;
			$cond = array();
			
			if(is_array($data)){
				if(isset($data['store_id'])) {
					$cond[] = "`store_id` = '".intval($data['store_id'])."'";
				}
				if(isset($data['language_id'])) {
					$cond[] = "(`language_id` = '".intval($data['language_id'])."' OR `language_id` = 0)";
				}
				} elseif(is_numeric($data)) {
				$cond[] = "`emailtemplate_config_id` = '" . intval($data) . "'";
			}
			
			$query = "SELECT * FROM `{$p}emailtemplate_config`";
			if(!empty($cond)){
				$query .= " WHERE " . implode(" AND ", $cond);
			}
			$query .= " ORDER BY `language_id` DESC LIMIT 1";
			
			$result = $this->db->non_cached_query($query);
			if(empty($result->row)) return false;
			$row = $result->row;
			
			$cols = EmailTemplateConfigDAO::describe();
			foreach($cols as $col => $type){
				if(isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE){
					if($row[$col]){
						$row[$col] = unserialize(base64_decode($row[$col]));
						} else {
						$row[$col] = array();
					}
				}
			}
			
			if($outputFormatting){
				$row = $this->formatConfig($row, $keyCleanUp);
			}
			
			return $row;
		}
		
		/**
			* Return array of configs
			*
			* @param array - $data
		*/
		public function formatConfig($data = array(), $keyCleanUp = false){
			$this->load->model('tool/image');
			
			$data['emailtemplate_config_head_text'] = html_entity_decode($data['emailtemplate_config_head_text'], ENT_QUOTES, 'UTF-8');
			$data['emailtemplate_config_view_browser_text'] = html_entity_decode($data['emailtemplate_config_view_browser_text'], ENT_QUOTES, 'UTF-8');
			$data['emailtemplate_config_page_footer_text'] = html_entity_decode($data['emailtemplate_config_page_footer_text'], ENT_QUOTES, 'UTF-8');
			$data['emailtemplate_config_footer_text'] = html_entity_decode($data['emailtemplate_config_footer_text'], ENT_QUOTES, 'UTF-8');
			
			if ($data['emailtemplate_config_logo'] && file_exists(DIR_IMAGE . $data['emailtemplate_config_logo'])) {
				if ($data['emailtemplate_config_logo_width'] > 0 && $data['emailtemplate_config_logo_height'] > 0){
					$data['emailtemplate_config_logo_thumb'] = $this->model_tool_image->resize(
					$data['emailtemplate_config_logo'],
					$data['emailtemplate_config_logo_width'],
					$data['emailtemplate_config_logo_height']
					);
					} else {
					$data['emailtemplate_config_logo_thumb'] = $this->getImageUrl($data['emailtemplate_config_logo']);
				}
				} else {
				$data['emailtemplate_config_logo_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			if ($data['emailtemplate_config_header_bg_image'] && file_exists(DIR_IMAGE . $data['emailtemplate_config_header_bg_image'])) {
				$data['emailtemplate_config_header_bg_image_thumb'] = $this->getImageUrl($data['emailtemplate_config_header_bg_image']);
				} else {
				$data['emailtemplate_config_header_bg_image_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			foreach(array('left', 'right') as $col){
				if (isset($data['emailtemplate_config_shadow_top'][$col.'_img']) && file_exists(DIR_IMAGE . $data['emailtemplate_config_shadow_top'][$col.'_img'])) {
					$data['emailtemplate_config_shadow_top'][$col.'_thumb'] = $this->getImageUrl($data['emailtemplate_config_shadow_top'][$col.'_img']);
					} else {
					$data['emailtemplate_config_shadow_top'][$col.'_thumb'] = $this->model_tool_image->resize('no_image.jpg', 50, 50);
				}
				
				if (isset($data['emailtemplate_config_shadow_bottom'][$col.'_img']) && file_exists(DIR_IMAGE . $data['emailtemplate_config_shadow_bottom'][$col.'_img'])) {
					$data['emailtemplate_config_shadow_bottom'][$col.'_thumb'] = $this->getImageUrl($data['emailtemplate_config_shadow_bottom'][$col.'_img']);
					} else {
					$data['emailtemplate_config_shadow_bottom'][$col.'_thumb'] = $this->model_tool_image->resize('no_image.jpg', 50, 50);
				}
			}
			
			if($keyCleanUp){
				foreach($data as $col => $val){
					$key = (strpos($col, 'emailtemplate_config_') === 0 && substr($col, -3) != '_id') ? substr($col, 21) : $col;
					if(!isset($data[$key])){
						unset($data[$col]);
						$data[$key] = $val;
					}
				}
			}
			
			return $data;
		}
		
		/**
			* Return array of configs
			* @param array - $data
		*/
		public function getConfigs($data = array(), $outputFormatting = false, $keyCleanUp = false){
			$p = DB_PREFIX;
			$cond = array();
			
			if (isset($data['language_id'])) {
				$cond[] = "AND ec.`language_id` = '".intval($data['language_id'])."'";
				} elseif (isset($data['_language_id'])) {
				$cond[] = "OR ec.`language_id` = '".intval($data['_language_id'])."'";
			}
			
			if (isset($data['store_id'])) {
				$cond[] = "AND ec.`store_id` = '".intval($data['store_id'])."'";
				} elseif (isset($data['_store_id'])) {
				$cond[] = "OR ec.`store_id` = '".intval($data['_store_id'])."'";
			}
			
			if (isset($data['customer_group_id'])) {
				$cond[] = "AND ec.`customer_group_id` = '".intval($data['customer_group_id'])."'";
				} elseif (isset($data['_customer_group_id'])) {
				$cond[] = "OR ec.`customer_group_id` = '".intval($data['_customer_group_id'])."'";
			}
			
			if (isset($data['emailtemplate_config_id'])) {
				if(is_array($data['emailtemplate_config_id'])){
					$ids = array();
					foreach($data['emailtemplate_config_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "AND ec.`emailtemplate_config_id` IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "AND ec.`emailtemplate_config_id` = '".intval($data['emailtemplate_config_id'])."'";
				}
			}
			
			if (isset($data['not_emailtemplate_config_id'])) {
				if(is_array($data['not_emailtemplate_config_id'])){
					$ids = array();
					foreach($data['not_emailtemplate_config_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "AND ec.`emailtemplate_config_id` NOT IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "AND ec.`emailtemplate_config_id` != '".intval($data['not_emailtemplate_config_id'])."'";
				}
			}
			
			$query = "SELECT ec.* FROM `{$p}emailtemplate_config` ec";
			if(!empty($cond)){
				$query .= ' WHERE ' . ltrim(implode(' ', $cond), 'AND');
			}
			
			$sort_data = array(
			'ec.emailtemplate_config_id',
			'ec.emailtemplate_config_name',
			'ec.emailtemplate_config_modified',
			'ec.store_id',
			'ec.language_id',
			'ec.customer_group_id'
			);
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$query .= " ORDER BY `" . $data['sort'] . "`";
				} else {
				$query .= " ORDER BY ec.`emailtemplate_config_name`";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$query .= " DESC";
				} else {
				$query .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if (!isset($data['start']) || $data['start'] < 0) {
					$data['start'] = 0;
				}
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$result = $this->db->non_cached_query($query);
			if(empty($result->rows)) return array();
			$rows = $result->rows;
			
			$cols = EmailTemplateConfigDAO::describe();
			
			foreach($rows as $key => &$row){
				foreach($cols as $col => $type){
					
					if(isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE){
						if($row[$col]){
							$row[$col] = unserialize(base64_decode($row[$col]));
							} else {
							$row[$col] = array();
						}
					}
					
					if($keyCleanUp){
						$key = (strpos($col, 'emailtemplate_config_') === 0 && substr($col, -3) != '_id') ? substr($col, 21) : $col;
						if(!isset($row[$key])){
							$row[$key] = $row[$col];
							unset($row[$col]);
						}
					}
					
				}
			}
			
			return $rows;
		}
		
		/**
			* Add new Email Template Config by cloning an existing one.
			*
			* @return new row identifier
		*/
		public function cloneConfig($id, $data = array()){
			$id = intval($id);
			$inserts = array();
			$p = DB_PREFIX;
			$cols = EmailTemplateConfigDAO::describe("emailtemplate_config_id", "store_id", "language_id", "customer_group_id");
			
			if(isset($data['store_id'])){
				$store_id = intval($data['store_id']);
				} else {
				$store_id = -1;
			}
			
			if(isset($data['language_id'])){
				$language_id = intval($data['language_id']);
				} else {
				$language_id = 0;
			}
			
			if(isset($data['customer_group_id'])){
				$customer_group_id = intval($data['customer_group_id']);
				} else {
				$customer_group_id = 0;
			}
			
			$colsInsert = '';
			foreach($cols as $col => $type){
				if (!array_key_exists($col, $data)){
					$value = "`{$col}`";
					} else {
					switch ($type) {
						case EmailTemplateAbstract::INT:
		        		if(strtoupper($data[$col]) == 'NULL'){
							$value = 'NULL';
							} else {
							$value = intval($data[$col]);
						}
		        		break;
						case EmailTemplateAbstract::FLOAT:
		        		$value = floatval($data[$col]);
		        		break;
						case EmailTemplateAbstract::DATE_NOW:
		        		$value = 'NOW()';
		        		break;
						case EmailTemplateAbstract::SERIALIZE:
		        		$value = base64_encode(serialize($data[$col]));
		        		break;
						default:
		        		$value = $this->db->escape($data[$col]);
					}
					$value = "'{$value}'";
				}
				
				$colsInsert .= "{$value}, ";
			}
			
			$stmnt = "INSERT INTO {$p}emailtemplate_config (".implode(array_keys($cols),', ').", store_id, language_id, customer_group_id)
			SELECT ".$colsInsert." '{$store_id}', '{$language_id}', '{$customer_group_id}' FROM {$p}emailtemplate_config WHERE emailtemplate_config_id = '{$id}'";
			$this->db->non_cached_query($stmnt);
			
			$this->_deleteCache();
			
			return $this->db->getLastId();
		}
		
		/**
			* Edit existing config
			*
			* @param int - emailtemplate.emailtemplate_id
			* @param array - column => value
			* @return int affected row count
		*/
		public function updateConfig($id, array $data){
			if (empty($data) && !is_numeric($id)) return false;
			$id = intval($id);
			$p = DB_PREFIX;
			
			$cols = EmailTemplateConfigDAO::describe();
			
			$updates = $this->_build_query($cols, $data);
			if (!$updates) return false;
			
			$sql = "UPDATE {$p}emailtemplate_config SET ".implode($updates,", ") . " WHERE emailtemplate_config_id = '{$id}'";
			$this->db->non_cached_query($sql);
			
			$this->_deleteCache();
			
			$affected = $this->db->countAffected();
			return ($affected > 0) ? $affected : false;
		}
		
		/**
			* Delete config row
			*
			* @param mixed array||int - emailtemplate.id
			* @return int - row count effected
		*/
		public function deleteConfig($data){
			$ids = array();
			$p = DB_PREFIX;
			if(is_array($data)){
				foreach($data as $item){
					$ids[] = intval($item);
				}
				} else {
				$ids[] = intval($data);
			}
			
			if(($key = array_search(1, $ids)) !== false) {
				unset($ids[$key]);
			}
			
			$queries = array();
			$queries[] = "DELETE FROM {$p}emailtemplate_config WHERE emailtemplate_config_id IN('".implode("', '", $ids)."')";
			$queries[] = "UPDATE {$p}emailtemplate SET emailtemplate_config_id = '' WHERE emailtemplate_config_id IN('".implode("', '", $ids)."')";
			
			$affected = 0;
			foreach($queries as $query){
				$this->db->non_cached_query($query);
				$affected += $this->db->countAffected();
			}
			
			$this->_deleteCache();
			
			if($affected > 0){
				return $affected;
			}
			return false;
		}
		
		/**
			* Get Template
			* @param int $id
			* @return array
		*/
		public function getTemplate($ident, $language_id = null, $keyCleanUp = false){
			$p = DB_PREFIX;
			$return = array();
			
			if(is_numeric($ident)){
				$cond = "`emailtemplate_id` = '" . intval($ident) . "'";
				} else {
				$cond = "`emailtemplate_key` = '" . $this->db->escape($ident) . "' AND `emailtemplate_default` = 1";
			}
			
			$query = "SELECT * FROM {$p}emailtemplate WHERE " . $cond . " LIMIT 1";
			$result = $this->_fetch($query);
			
			if($result->row){
				$return = $result->row;
				
				$cols = EmailTemplateDAO::describe();
				
				foreach($cols as $col => $type){
					if(!isset($return[$col])) continue;
					
					if($type == EmailTemplateDAO::SERIALIZE && $return[$col]){
						$return[$col] = unserialize(base64_decode($return[$col]));
					}
					
					if($keyCleanUp){
						$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;
						if(!isset($return[$key])){
							$return[$key] = $return[$col];
							unset($return[$col]);
						}
					}
				}
				
				if($language_id){
					$result = $this->getTemplateDescription(array('emailtemplate_id' => $return['emailtemplate_id'], 'language_id' => $language_id), 1);
					
					if($result){
						$cols = EmailTemplateDescriptionDAO::describe();
						foreach($cols as $col => $type){
							$key = $col;
							if($keyCleanUp){
								$key = (strpos($col, 'emailtemplate_description_') === 0 && substr($col, -3) != '_id') ? substr($col, 24) : $col;
							}
							
							if(!isset($return[$key])){
								$return[$key] = $result[$col];
								unset($result[$col]);
							}
						}
					}
				}
			}
			
			return $return;
		}
		
		/**
			* Get Template
			* @param int $id
			* @return array
		*/
		public function getTemplateDescription($data = array(), $limit = null){
			$p = DB_PREFIX;
			$cond = array();
			$query = "SELECT * FROM {$p}emailtemplate_description";
			
			if(isset($data['emailtemplate_id'])){
				$cond[] = "`emailtemplate_id` = '".intval($data['emailtemplate_id'])."'";
				} else {
				return array();
			}
			
			if(isset($data['language_id'])){
				$cond[] = "`language_id` = '".intval($data['language_id'])."'";
			}
			
			if(!empty($cond)){
				$query .= ' WHERE ' . implode(' AND ', $cond);
			}
			if(is_numeric($limit)){
				$query .= ' LIMIT ' . intval($limit);
			}
			
			$result = $this->db->non_cached_query($query);
			
			return ($limit == 1) ? $result->row : $result->rows;
		}
		
		/**
			* Return array of templates
			* @param array - $data
		*/
		public function getTemplates($data = array(), $keyCleanUp = false){
			$p = DB_PREFIX;
			$cond = array();
			
			if (isset($data['store_id'])) {
				if(is_numeric($data['store_id'])){
					$cond[] = "e.`store_id` = '".intval($data['store_id'])."'";
					} else {
					$cond[] = "e.`store_id` IS NULL";
				}
			}
			
			if (isset($data['customer_group_id']) && $data['customer_group_id'] != 0) {
				$cond[] = "e.`customer_group_id` = '".intval($data['customer_group_id'])."'";
			}
			
			if (isset($data['emailtemplate_key']) && $data['emailtemplate_key'] != "") {
				$cond[] = "e.`emailtemplate_key` = '".$this->db->escape($data['emailtemplate_key'])."'";
			}
			
			if (isset($data['emailtemplate_status']) && $data['emailtemplate_status'] != "") {
				$cond[] = "e.`emailtemplate_status` = '".$this->db->escape($data['emailtemplate_status'])."'";
			}
			
			if (isset($data['emailtemplate_default'])) {
				$cond[] = "e.`emailtemplate_default` = '" . intval($data['emailtemplate_default']) . "'";
			}
			
			if (isset($data['emailtemplate_id'])) {
				if(is_array($data['emailtemplate_id'])){
					$ids = array();
					foreach($data['emailtemplate_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "e.`emailtemplate_id` IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "e.`emailtemplate_id` = '".intval($data['emailtemplate_id'])."'";
				}
			}
			
			if (isset($data['not_emailtemplate_id'])) {
				if(is_array($data['not_emailtemplate_id'])){
					$ids = array();
					foreach($data['not_emailtemplate_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "e.`emailtemplate_id` NOT IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "e.`emailtemplate_id` != '".intval($data['not_emailtemplate_id'])."'";
				}
			}
			
			$query = "SELECT e.* FROM `{$p}emailtemplate` e";
			
			if(!empty($cond)){
				$query .= ' WHERE ' . implode(' AND ', $cond);
			}
			
			$sort_data = array(
			'label' => 'e.`emailtemplate_label`',
			'key' => 'e.`emailtemplate_key`',
			'template' => 'e.`emailtemplate_template`',
			'modified' => 'e.`emailtemplate_modified`',
			'default' => 'e.`emailtemplate_default`',
			'shortcodes' => 'e.`emailtemplate_shortcodes`',
			'status' => 'e.`emailtemplate_status`',
			'id' => 'e.`emailtemplate_id`',
			'store' => 'e.`store_id`',
			'customer' => 'e.`customer_group_id`',
			'language' => 'ed.`language_id`'
			);
			if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
				$query .= " ORDER BY " . $sort_data[$data['sort']];
				} else {
				$query .= " ORDER BY e.`emailtemplate_label`";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$query .= " DESC";
				} else {
				$query .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if (!isset($data['start']) || $data['start'] < 0) {
					$data['start'] = 0;
				}
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$result = $this->db->non_cached_query($query);
			if(empty($result->rows)) return array();
			
			$rows = $result->rows;
			
			$cols = EmailTemplateDAO::describe();
			
			foreach($rows as $key => &$row){
				foreach($row as $col => $val){
					if(isset($cols[$col]) && $cols[$col] == EmailTemplateDAO::SERIALIZE){
						if($val){
							$row[$col] = unserialize(base64_decode($val));
						}
					}
					
					if($keyCleanUp){
						$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;
						
						if(!array_key_exists($key, $row)){
							$row[$key] = $val;
							unset($row[$col]);
						}
					}
				}
			}
			
			return $rows;
		}
		
		/**
			* Get Template Log
			* @param int $id
			* @return array
		*/
		public function getTemplateLog($id, $keyCleanUp = false){
			$p = DB_PREFIX;
			$id = intval($id);
			$return = array();
			
			if(!$id) return $return;
			
			$query = "SELECT * FROM {$p}emailtemplate_logs WHERE `emailtemplate_log_id` = '{$id}'";
			$result = $this->db->non_cached_query($query);
			$return = ($result->row) ? $result->row : array();
			
			if(!empty($return) && $keyCleanUp){
				$cols = EmailTemplateLogsDAO::describe();
				foreach($cols as $col => $type){
					$key = (strpos($col, 'emailtemplate_log_') === 0 && substr($col, -3) != '_id') ? substr($col, 18) : $col;
					if(!isset($return[$key])){
						$return[$key] = $return[$col];
						unset($return[$col]);
					}
				}
			}
			
			return $return;
		}
		
		/**
			* Return array of logs
			* @param array - $data
		*/
		public function getTemplateLogs($data = array(), $outputFormatting = false, $keyCleanUp = false){
			$p = DB_PREFIX;
			$cond = array();
			$query = "SELECT el.* FROM `{$p}emailtemplate_logs` el";
			
			if (isset($data['store_id']) && is_numeric($data['store_id'])) {
				if(is_numeric($data['store_id'])){
					$cond[] = "el.`store_id` = '".intval($data['store_id'])."'";
				}
			}
			
			if (isset($data['language_id']) && $data['language_id'] != 0) {
				$cond[] = "el.`language_id` = '".intval($data['language_id'])."'";
			}
			
			if (isset($data['customer_id']) && $data['customer_id'] != 0) {
				$cond[] = "el.`customer_id` = '".intval($data['customer_id'])."'";
			}
			
			if (isset($data['order_id']) && $data['order_id'] != 0) {
				$cond[] = "el.`order_id` = '".intval($data['order_id'])."'";
			}
			
			if (isset($data['emailtemplate_id']) && $data['emailtemplate_id'] != 0) {
				if(is_array($data['emailtemplate_id'])){
					$ids = array();
					foreach($data['emailtemplate_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "el.`emailtemplate_id` IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "el.`emailtemplate_id` = '".intval($data['emailtemplate_id'])."'";
				}
			}
			
			if(!empty($cond)){
				$query .= ' WHERE ' . implode(' AND ', $cond);
			}
			
			$sort_data = array(
			'id' => 'el.`emailtemplate_log_id`',
			'template' => 'el.`emailtemplate_id`',
			'store_id' => 'el.`store_id`',
			'date' => 'el.`emailtemplate_log_sent`',
			'type' => 'el.`emailtemplate_log_type`',
			'to' => 'el.`emailtemplate_log_to`',
			'from' => 'el.`emailtemplate_log_from`',
			'sender' => 'el.`emailtemplate_log_sender`',
			'subject' => 'el.`emailtemplate_log_subject`'
			);
			if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
				$query .= " ORDER BY " . $sort_data[$data['sort']];
				} else {
				$query .= " ORDER BY el.`emailtemplate_log_sent`";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$query .= " DESC";
				} else {
				$query .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if (!isset($data['start']) || $data['start'] < 0) {
					$data['start'] = 0;
				}
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$result = $this->db->non_cached_query($query);
			
			if(empty($result->rows)) return array();
			
			foreach($result->rows as $i => $row){
				foreach($row as $col => $val){
					if($keyCleanUp){
						$key = (strpos($col, 'emailtemplate_log_') === 0 && substr($col, -3) != '_id') ? substr($col, 18) : $col;
						if(!isset($result->rows[$i][$key])){
							unset($result->rows[$i][$col]);
							$result->rows[$i][$key] = $val;
						}
					}
				}
			}
			
			return $result->rows;
		}
		
		/**
			* Return top logs
			* @param array - $data
		*/
		public function getTotalTemplateLogs($data = array()){
			$p = DB_PREFIX;
			$cond = array();
			
			if (isset($data['store_id']) && is_numeric($data['store_id'])) {
				if(is_numeric($data['store_id'])){
					$cond[] = "el.`store_id` = '".intval($data['store_id'])."'";
				}
			}
			
			if (isset($data['language_id']) && $data['language_id'] != 0) {
				$cond[] = "el.`language_id` = '".intval($data['language_id'])."'";
			}
			
			if (isset($data['customer_id']) && $data['customer_id'] != 0) {
				$cond[] = "el.`customer_id` = '".intval($data['customer_id'])."'";
			}
			
			if (isset($data['emailtemplate_id']) && $data['emailtemplate_id'] != 0) {
				if(is_array($data['emailtemplate_id'])){
					$ids = array();
					foreach($data['emailtemplate_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "el.`emailtemplate_id` IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "el.`emailtemplate_id` = '".intval($data['emailtemplate_id'])."'";
				}
			}
			
			$query = "SELECT COUNT(*) AS total FROM `{$p}emailtemplate_logs` el";
			if(!empty($cond)){
				$query .= ' WHERE ' . implode(' AND ', $cond);
			}
			
			$result = $this->db->non_cached_query($query);
			if(empty($result->row)) return array();
			
			return $result->row['total'];
		}
		
		/**
			* Return last insert id for logs.
			* @return int
		*/
		public function getLastTemplateLogId(){
			$p = DB_PREFIX;
			$query = "SELECT MAX(emailtemplate_log_id) as emailtemplate_log_id FROM `{$p}emailtemplate_logs`";
			$result = $this->db->non_cached_query($query);
			
			return $result->row['emailtemplate_log_id'];
		}
		
		
		/**
			* Return total template(s)
			* @return int - total rows
		*/
		public function getTotalTemplates($data){
			$p = DB_PREFIX;
			$cond = array();
			
			if (isset($data['store_id'])) {
				if(is_numeric($data['store_id'])){
					$cond[] = "e.`store_id` = '".intval($data['store_id'])."'";
					} else {
					$cond[] = "e.`store_id` IS NULL";
				}
			}
			
			if (isset($data['customer_group_id']) && $data['customer_group_id'] != 0) {
				$cond[] = "e.`customer_group_id` = '".intval($data['customer_group_id'])."'";
			}
			
			if (isset($data['emailtemplate_key']) && $data['emailtemplate_key'] != "") {
				$cond[] = "e.`emailtemplate_key` = '".$this->db->escape($data['emailtemplate_key'])."'";
			}
			
			if (isset($data['emailtemplate_status']) && $data['emailtemplate_status'] != "") {
				$cond[] = "e.`emailtemplate_status` = '".$this->db->escape($data['emailtemplate_status'])."'";
			}
			
			if (isset($data['emailtemplate_default'])) {
				$cond[] = "e.`emailtemplate_default` = '" . intval($data['emailtemplate_default']) . "'";
			}
			
			if (isset($data['emailtemplate_id'])) {
				if(is_array($data['emailtemplate_id'])){
					$ids = array();
					foreach($data['emailtemplate_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "e.`emailtemplate_id` IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "e.`emailtemplate_id` = '".intval($data['emailtemplate_id'])."'";
				}
			}
			
			if (isset($data['not_emailtemplate_id'])) {
				if(is_array($data['not_emailtemplate_id'])){
					$ids = array();
					foreach($data['not_emailtemplate_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "e.`emailtemplate_id` NOT IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "e.`emailtemplate_id` != '".intval($data['not_emailtemplate_id'])."'";
				}
			}
			
			if (isset($data['emailtemplate_id'])) {
				if(is_array($data['emailtemplate_id'])){
					$ids = array();
					foreach($data['emailtemplate_id'] as $id){ $ids[] = intval($id); }
					$cond[] = "e.`emailtemplate_id` IN('".implode("', '", $ids)."')";
					} else {
					$cond[] = "e.`emailtemplate_id` = '".intval($data['emailtemplate_id'])."'";
				}
			}
			
			$query = "SELECT COUNT(*) AS total FROM `{$p}emailtemplate` e";
			if(!empty($cond)){
				$query .= ' WHERE ' . implode(' AND ', $cond);
			}
			
			$result = $this->db->non_cached_query($query);
			return $result->row['total'];
		}
		
		/**
			* Add new template row
			*
			* @return new row identifier
		*/
		public function insertTemplate(array $data){
			if (empty($data)) return false;
			
			$this->load->model('localisation/language');
			
			if($data['emailtemplate_key'] == ""){
				$defaultTemplate = $this->getTemplate($data['emailtemplate_key_select']);
				$default_emailtemplate_id = $defaultTemplate['emailtemplate_id'];
				$result = $this->getTemplateDescription(array('emailtemplate_id' => $default_emailtemplate_id));
				
				unset($defaultTemplate['emailtemplate_id']);
				unset($defaultTemplate['emailtemplate_label']);
				unset($defaultTemplate['emailtemplate_modified']);
				
				$defaultTemplateDescriptions = array();
				
				foreach($result as $row){
					$defaultTemplateDescriptions[$row['language_id']] = $row;
				}
				
				$data = array_merge($defaultTemplate, $data);
				
				$data['emailtemplate_key'] = $data['emailtemplate_key_select'];
				$data['emailtemplate_default'] = 0;
				$data['emailtemplate_shortcodes'] = 0;
				$data['store_id'] = 'NULL';
			}
			
			$cols = EmailTemplateDAO::describe('emailtemplate_id');
			
			$inserts = $this->_build_query($cols, $data);
			if (empty($inserts)) return false;
			
			$p = DB_PREFIX;
			$this->db->non_cached_query("INSERT INTO {$p}emailtemplate SET ".implode($inserts,", "));
			
			$new_id = $this->db->getLastId();
			
			$languages = $this->model_localisation_language->getLanguages();
			
			$cols = EmailTemplateDescriptionDAO::describe('emailtemplate_description_id', 'emailtemplate_id', 'language_id');
			$descriptions = array();
			
			foreach($languages as $language){
				$langId = $language['language_id'];
				
				if(!isset($descriptions[$langId])){
					$descriptions[$langId] = array();
				}
				
				foreach($cols as $col => $type){
					if(isset($data[$col][$langId])){
						$descriptions[$langId][$col] = $data[$col][$langId];
						} elseif(isset($defaultTemplateDescriptions[$langId][$col])) {
						$descriptions[$langId][$col] = $defaultTemplateDescriptions[$langId][$col];
						} else {
						$descriptions[$langId][$col] = '';
					}
				}
			}
			
			foreach($descriptions as $langId => $data){
				$data['language_id'] = intval($langId);
				$data['emailtemplate_id'] = $new_id;
				
				$this->insertTemplateDescription($data);
			}
			
			if(isset($default_emailtemplate_id)){
				$data = $this->getTemplateShortcodes($default_emailtemplate_id);
				$shortcodes = array();
				foreach($data as $row){
					$shortcodes[$row['emailtemplate_shortcode_code']] = $row['emailtemplate_shortcode_example'];
				}
				$this->insertTemplateShortCodes($new_id, $shortcodes);
			}
			
			if(!$this->_updateVqmod()){
				$this->session->data['vqmod_update_failed'] = true;
			}
			
			$this->_deleteCache();
			
			return $new_id;
		}
		
		/**
			* Add new template description row
			*
			* @return new row identifier
		*/
		public function insertTemplateDescription(array $data){
			if (empty($data)) return false;
			
			$p = DB_PREFIX;
			$cols = EmailTemplateDescriptionDAO::describe('emailtemplate_description_id');
			
			$inserts = $this->_build_query($cols, $data);
			if (empty($inserts)) return false;
			
			$sql = "INSERT INTO {$p}emailtemplate_description SET ".implode($inserts,", ");
			$this->db->non_cached_query($sql);
			
			$new_id = $this->db->getLastId();
			
			$this->_deleteCache();
			
			return $new_id;
		}
		
		/**
			* Insert Template Short Codes
		*/
		public function insertTemplateShortCodes($id, $data){
			$id = intval($id);
			$cols = EmailTemplateShortCodesDAO::describe();
			$p = DB_PREFIX;
			$return = 0;
			
			$this->db->non_cached_query("DELETE FROM {$p}emailtemplate_shortcode WHERE `emailtemplate_id` = '{$id}'");
			
			foreach($data as $code => $example){
				if($code == 'emailtemplate' || $code == 'config') continue;
				
				if(is_array($example) && $example){
					$example = base64_encode(serialize($example));
					$data = array(
					'emailtemplate_id' => $id,
					'emailtemplate_shortcode_type' => 'auto_serialize',
					'emailtemplate_shortcode_code' => $code,
					'emailtemplate_shortcode_example' => $example
					);
					} else {
					$data = array(
					'emailtemplate_id' => $id,
					'emailtemplate_shortcode_type' => 'auto',
					'emailtemplate_shortcode_code' => $code,
					'emailtemplate_shortcode_example' => $example
					);
				}
				
				$inserts = $this->_build_query($cols, $data);
				if (empty($inserts)) continue;
				
				$sql = "INSERT INTO {$p}emailtemplate_shortcode SET ".implode($inserts,", ");
				$this->db->non_cached_query($sql);
				
				$return++;
			}
			
			$this->db->non_cached_query("UPDATE {$p}emailtemplate SET `emailtemplate_shortcodes` = '1' WHERE `emailtemplate_id` = '{$id}'");
			
			$this->_deleteCache();
			
			return $return;
		}
		
		/**
			* Edit existing template row
			*
			* @param int - emailtemplate.id
			* @param array - column => value
			* @return returns true if row was updated with new data
		*/
		public function updateTemplate($id, array $data){
			$id = intval($id);
			$p = DB_PREFIX;
			$queries = array();
			$affected = 0;
			
			$cols = EmailTemplateDAO::describe('emailtemplate_id');
			
			$updates = $this->_build_query($cols, $data);
			
			if ($updates){
				$sql = "UPDATE {$p}emailtemplate SET ".implode($updates,", ") . " WHERE `emailtemplate_id` = '{$id}'";
				$this->db->non_cached_query($sql);
				
				$affected += $this->db->countAffected();
			}
			
			$cols = EmailTemplateDescriptionDAO::describe('emailtemplate_description', 'emailtemplate_id', 'language_id');
			$descriptions = array();
			
			foreach($cols as $col => $type){
				if(isset($data[$col]) && is_array($data[$col])){
					foreach($data[$col] as $langId => $val){
						if(!isset($descriptions[$langId])){
							$descriptions[$langId] = array();
						}
						$descriptions[$langId][$col] = $val;
					}
				}
			}
			
			foreach($descriptions as $langId => $data){
				$langId = intval($langId);
				$updates = $this->_build_query($cols, $data);
				if (empty($updates)) continue;
				
				$result = $this->db->non_cached_query("SELECT count(`emailtemplate_id`) AS total FROM {$p}emailtemplate_description WHERE `emailtemplate_id` = '{$id}' AND `language_id` = '{$langId}'");
				if($result->row['total'] == 0){
					$query = "INSERT INTO {$p}emailtemplate_description SET `emailtemplate_id` = '{$id}', `language_id` = '{$langId}', ".implode($updates,", ");
					} else {
					$query = "UPDATE {$p}emailtemplate_description SET ".implode($updates,", ") . " WHERE `emailtemplate_id` = '{$id}' AND `language_id` = '{$langId}'";
				}
				$this->db->non_cached_query($query);
				
				if($affected == 0 && $this->db->countAffected()){
					$this->db->non_cached_query("UPDATE {$p}emailtemplate SET `emailtemplate_modified` = NOW() WHERE `emailtemplate_id` = '{$id}'");
				}
				$affected += $this->db->countAffected();
			}
			
			if($affected > 0){
				if(!$this->_updateVqmod()){
					$this->session->data['vqmod_update_failed'] = true;
				}
			}
			
			$this->_deleteCache();
			
			return ($affected > 0) ? $affected : false;
		}
		
		/**
			* Delete template row
			*
			* @param mixed array||int - emailtemplate.id
			* @return int - row count effected
		*/
		public function deleteTemplate($data){
			$ids = array();
			$p = DB_PREFIX;
			if(is_array($data)){
				foreach($data as $var){
					$ids[] = intval($var);
				}
				} else {
				$ids[] = intval($data);
			}
			
			if(($key = array_search(1, $ids)) !== false) {
				unset($ids[$key]);
			}
			
			foreach($ids as $id){
				$sql = "SELECT emailtemplate_id FROM {$p}emailtemplate WHERE emailtemplate_key = (SELECT emailtemplate_key FROM {$p}emailtemplate WHERE emailtemplate_id = '{$id}' AND emailtemplate_default = 1) AND emailtemplate_id != '{$id}'";
				$result = $this->db->non_cached_query($sql);
				foreach($result->rows as $row){
					$ids[] = $row['emailtemplate_id'];
				}
			}
			
			$queries = array();
			$queries[] = "DELETE FROM `{$p}emailtemplate_description` WHERE `emailtemplate_id` IN('".implode("', '", $ids)."')";
			$queries[] = "DELETE FROM `{$p}emailtemplate_shortcode` WHERE `emailtemplate_id` IN('".implode("', '", $ids)."')";
			$queries[] = "DELETE FROM `{$p}emailtemplate` WHERE `emailtemplate_id` IN('".implode("', '", $ids)."')";
			
			foreach($queries as $query){
				$this->db->non_cached_query($query);
			}
			
			$affected = $this->db->countAffected();
			
			$this->_deleteCache();
			
			if(!$this->_updateVqmod()){
				$this->session->data['vqmod_update_failed'] = true;
			}
			
			return $affected;
		}
		
		/**
			* Delete template row
			*
			* @param mixed array||int - emailtemplate.id
			* @return int - row count effected
		*/
		public function deleteLogs($data){
			if(empty($data)) return false;
			
			$ids = array();
			$p = DB_PREFIX;
			if(is_array($data)){
				foreach($data as $var){
					$ids[] = intval($var);
				}
				} else {
				$ids[] = intval($data);
			}
			
			$query = "DELETE FROM `{$p}emailtemplate_logs` WHERE `emailtemplate_log_id` IN('".implode("', '", $ids)."')";
			
			$this->db->non_cached_query($query);
			
			$affected = $this->db->countAffected();
			
			$this->_deleteCache();
			
			return $affected;
		}
		
		/**
			* Delete template row
			*
			* @param mixed array
			* @return int - row count effected
		*/
		public function deleteTemplateDescription($data){
			$cond = array();
			$p = DB_PREFIX;
			
			if(isset($data['language_id'])){
				$cond[] = "`language_id` = '" . intval($data['language_id']) . "'";
			}
			
			$query = "DELETE FROM `{$p}emailtemplate_description` WHERE ".implode("', '", $cond);
			$this->db->non_cached_query($query);
			
			$this->_deleteCache();
			
			$affected = $this->db->countAffected();
			return ($affected > 0) ? $affected : false;
		}
		
		/**
			* Get template enum types
		*/
		public function getTemplateKeys(){
			$p = DB_PREFIX;
			$return = array();
			$query = "SELECT `emailtemplate_key`, count(`emailtemplate_id`) AS `total`
			FROM `{$p}emailtemplate`
			WHERE `emailtemplate_default` = 1 AND `emailtemplate_key` != ''
			GROUP BY `emailtemplate_key`
			ORDER BY `emailtemplate_key` ASC";
			$result = $this->_fetch($query);
			
			foreach($result->rows as $row){
				$return[] = array(
				'value' => $row['emailtemplate_key'],
				'label' => $row['emailtemplate_key'] . ($row['total'] > 1 ? (' ('.$row['total'].')') : '')
				);
			}
			
			return $return;
		}
		
		/**
			* Get template shortcodes
		*/
		public function getTemplateShortcodes($data, $keyCleanUp = false){
			$p = DB_PREFIX;
			$cond = array();
			
			if(is_array($data)){
				if (isset($data['emailtemplate_shortcode_id']) && $data['emailtemplate_shortcode_id'] != 0) {
					$cond[] = "es.`emailtemplate_shortcode_id` = '".intval($data['emailtemplate_shortcode_id'])."'";
				}
				
				if (isset($data['emailtemplate_id']) && $data['emailtemplate_id'] != 0) {
					$cond[] = "es.`emailtemplate_id` = '".intval($data['emailtemplate_id'])."'";
				}
				
				if (isset($data['emailtemplate_shortcode_type'])) {
					$cond[] = "es.`emailtemplate_shortcode_type` = '".$this->db->escape($data['emailtemplate_shortcode_type'])."'";
				}
				
				if (isset($data['emailtemplate_key'])) {
					$result = $this->_fetch("SELECT emailtemplate_id FROM {$p}emailtemplate WHERE emailtemplate_key = '".$this->db->escape($data['emailtemplate_key'])."' AND emailtemplate_default = 1 LIMIT 1");
					$cond[] = "es.`emailtemplate_id` = '".intval($result->row['emailtemplate_id'])."'";
				}
				} else {
				$cond[] = "es.`emailtemplate_id` = '".intval($data)."'";
			}
			
			$query = "SELECT es.* FROM `{$p}emailtemplate_shortcode` es";
			if(!empty($cond)){
				$query .= ' WHERE ' . implode(' AND ', $cond);
			}
			
			$sort_data = array(
			'id' => 'es.`emailtemplate_shortcode_id`',
			'emailtemplate_id' => 'es.`emailtemplate_id`',
			'code' => 'es.`emailtemplate_shortcode_code`',
			'example' => 'es.`emailtemplate_shortcode_example`',
			'type' => 'es.`emailtemplate_shortcode_type`'
			);
			if (isset($data['sort']) && in_array($data['sort'], array_keys($sort_data))) {
				$query .= " ORDER BY " . $sort_data[$data['sort']];
				} else {
				$query .= " ORDER BY es.`emailtemplate_shortcode_code`";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$query .= " DESC";
				} else {
				$query .= " ASC";
			}
			
			if (is_array($data) && (isset($data['start']) || isset($data['limit']))) {
				if (!isset($data['start']) || $data['start'] < 0) {
					$data['start'] = 0;
				}
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$result = $this->db->non_cached_query($query);
			$cols = EmailTemplateShortCodesDAO::describe();
			
			foreach($result->rows as $key => &$row){
				if($row['emailtemplate_shortcode_type'] == 'auto_serialize' && $row['emailtemplate_shortcode_example']){
					$row['emailtemplate_shortcode_example'] = unserialize(base64_decode($row['emailtemplate_shortcode_example']));
				}
				
				if($keyCleanUp){
					foreach($cols as $col => $type){
						$key = (strpos($col, 'emailtemplate_shortcode_') === 0 && substr($col, -3) != '_id') ? substr($col, 24) : $col;
						if(!isset($row[$key])){
							$row[$key] = $row[$col];
							unset($row[$col]);
						}
					}
				}
			}
			
			//echo '<pre>'; print_r($result->rows); exit;
			
			return $result->rows;
		}
		
		
		/**
			* Edit shortcode
			*
			* @param int - emailtemplate_shortcode.emailtemplate_shortcode_id
			* @param array - column => value
			* @return int affected row count
		*/
		public function updateTemplateShortcode($id, array $data){
			if (empty($data) && !is_numeric($id)) return false;
			$id = intval($id);
			$p = DB_PREFIX;
			
			$cols = EmailTemplateShortCodesDAO::describe();
			
			$updates = $this->_build_query($cols, $data);
			if (!$updates) return false;
			
			$sql = "UPDATE {$p}emailtemplate_shortcode SET ".implode($updates,", ") . " WHERE emailtemplate_shortcode_id = '{$id}'";
			$this->db->non_cached_query($sql);
			
			$this->_deleteCache();
			
			$affected = $this->db->countAffected();
			return ($affected > 0) ? $affected : false;
		}
		
		/**
			* Delete template shortcode(s)
			* Detech if template is custom and deletes shortcodes for custom templates
			* @todo admin on load custom template populate from default template if empty
			*
			* @param int template_id
			* @param array selected emailtemplate_shortcode_id - if empty deletes all
			* @return int - row count effected
		*/
		public function deleteTemplateShortcodes($id, $selected = array()){
			$id = intval($id);
			$p = DB_PREFIX;
			
			$cond = array();
			$cond[] = "`emailtemplate_id` = '{$id}'";
			
			if(!empty($selected)){
				$selectedCond = array();
				foreach ($selected as $selectedId){
					$selectedCond[] = intval($selectedId);
				}
				$cond[] = "`emailtemplate_shortcode_id` IN('" . implode("', '", $selectedCond) . "')";
			}
			
			$query = "DELETE FROM `{$p}emailtemplate_shortcode` WHERE ".implode(" AND ", $cond);
			$this->db->non_cached_query($query);
			$affected = $this->db->countAffected();
			
			if($affected > 0){
				$query = "SELECT 1 FROM `{$p}emailtemplate_shortcode` WHERE emailtemplate_id = '{$id}' LIMIT 1";
				$result = $this->db->non_cached_query($query);
				if($result->num_rows == 0){
					$query = "UPDATE `{$p}emailtemplate` SET `emailtemplate_shortcodes` = 0 WHERE `emailtemplate_id` = ".$id;
					$this->db->non_cached_query($query);
				}
				$this->_deleteCache();
			}
			
			return $affected;
		}
		
		/**
			* Get complete order email
			*
			* @param int $order_id
			* @param int $store_id - rare case overwrite order
			* @param int $language_id - rare case overwrite order
		*/
		public function getCompleteOrderEmail($order_id, $data = array(), $template_key = 'order.customer'){
			$order_id = intval($order_id);
			$order_status_id = $this->config->get('config_order_status_id');
			
			$this->load->model('sale/order');
			$this->load->model('tool/image');
			
			$order_info = $this->model_sale_order->getOrder($order_id);
			
			if(isset($data['language_id'])){
				$language_id = $data['language_id'];
				} elseif(isset($order->row['language_id'])){
				$language_id = $order->row['language_id'];
				} else {
				$language_id = $this->config->get('config_language_id');
			}
			
			if(isset($data['store_id'])){
				$store_id = $data['store_id'];
				} elseif(isset($order->row['store_id'])){
				$store_id = $order->row['store_id'];
				} else {
				$store_id = 0;
			}
			
			# Demo email template
			$template = new EmailTemplate($this->request, $this->registry);
			//	$template->store_id = $store_id;
			
			// Load Customer Group - check file exists for old versions of opencart
			if(isset($order_info['customer_group_id']) && $order_info['customer_group_id']){
				$this->load->model('sale/customer_group');
				$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);
			}
			
			// Load affiliate data into email
			if(isset($order_info['affiliate_id']) && $order_info['affiliate_id']){
				$this->load->model('sale/affiliate');
				$affiliate_info = $this->model_sale_affiliate->getAffiliate($order_info['affiliate_id']);
			}
			
			// Order Products
			$order_product_query = $this->db->non_cached_query("SELECT op.*, p.image, p.sku, p.quantity AS stock_quantity FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = op.product_id) WHERE order_id = '" . (int)$order_id . "'");
			
			$order_product_query_nogood = $this->db->non_cached_query("SELECT op.*, p.image, p.sku, p.quantity AS stock_quantity FROM " . DB_PREFIX . "order_product_nogood op LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = op.product_id) WHERE order_id = '" . (int)$order_id . "'");
			
			// Downloads
			$order_download_query = $this->db->non_cached_query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
			// Gift Voucher
			$chk = $this->db->non_cached_query("SHOW TABLES LIKE '" . DB_PREFIX . "order_voucher'");
			if($chk->num_rows){
				$order_voucher_query = $this->db->non_cached_query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
				} else {
				$order_voucher_query = false;
			}
			
			// Order Totals
			$order_total_query = $this->db->non_cached_query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			
			// Order Status
			$order_status_query = $this->db->non_cached_query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
			if ($order_status_query->num_rows) {
				$order_status = $order_status_query->row['name'];
				} else {
				$order_status = '';
			}
			
			// Send out order confirmation mail
			$language = new Language($order_info['language_directory']);
			$language->setPath(DIR_CATALOG.'language/');
			if($order_info['language_filename']){
				$language->load_full($order_info['language_filename']);
			}
			$langData = $language->load_full('mail/order');
			
			$template->addData($langData);
			
			$template->data['affiliate'] = (isset($affiliate_info)) ? $affiliate_info : '';
			$template->data['customer_group'] = (isset($customer_group_info['name'])) ? $customer_group_info['name'] : '';
			$template->data['new_order_status'] = $order_status;
			$template->data['subject'] = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);
			
			$template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
			$template->data['text_link'] = $language->get('text_new_link');
			$template->data['text_download'] = $language->get('text_new_download');
			$template->data['text_order_detail'] = $language->get('text_new_order_detail');
			$template->data['text_instruction'] = $language->get('text_new_instruction');
			$template->data['text_order_id'] = $language->get('text_new_order_id');
			$template->data['text_date_added'] = $language->get('text_new_date_added');
			$template->data['text_payment_method'] = $language->get('text_new_payment_method');
			$template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
			$template->data['text_email'] = $language->get('text_new_email');
			$template->data['text_telephone'] = $language->get('text_new_telephone');
			$template->data['text_ip'] = $language->get('text_new_ip');
			$template->data['text_payment_address'] = $language->get('text_new_payment_address');
			$template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
			$template->data['text_product'] = $language->get('text_new_product');
			$template->data['text_model'] = $language->get('text_new_model');
			$template->data['text_quantity'] = $language->get('text_new_quantity');
			$template->data['text_price'] = $language->get('text_new_price');
			$template->data['text_total'] = $language->get('text_new_total');
			$template->data['text_footer'] = $language->get('text_new_footer');
			$template->data['text_powered'] = $language->get('text_new_powered');
			
			$template->data['store_name'] = $order_info['store_name'];
			$template->data['store_url'] = $order_info['store_url'];
			$template->data['customer_id'] = $order_info['customer_id'];
			
			$template->data['customer_name'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
			$template->data['customer_firstname'] = $order_info['firstname'];
			$template->data['customer_lastname'] = $order_info['lastname'];
			
			$template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
			$template->data['link_tracking'] = $template->getTracking($template->data['link']);
			
			if ($order_download_query->num_rows) {
				$template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
				$template->data['download_tracking'] = $template->getTracking($template->data['download']);
				} else {
				$template->data['download'] = '';
			}
			
			$template->data['order_id'] = $order_id;
			if($language->get('date_format_short') && $language->get('date_format_short') != 'date_format_short'){
				$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
				} else {
				$template->data['date_added'] = $order_info['date_added'];
			}
			$template->data['payment_method'] = $order_info['payment_method'];
			$template->data['shipping_method'] = $order_info['shipping_method'];
			$template->data['changed'] = $order_info['changed'];
			$template->data['customer_confirm_url'] = $order_info['store_url'] . 'confirmorder' . $order_info['customer_confirm_url'];
			$template->data['email'] = $order_info['email'];
			$template->data['telephone'] = $order_info['telephone'];
			$template->data['fax'] = $order_info['fax'];
			$template->data['ip'] = $order_info['ip'];
			$template->data['bottom_text'] = $order_info['bottom_text'];
			
			$template->data['comment'] = ($order_info['comment']) ? str_replace(array("\r\n", "\r", "\n"), "<br />", $order_info['comment']) : '';
			$template->data['instruction'] = '';
			
			$this->load->model('setting/setting');		
			if (in_array($order_info['payment_code'], explode(',',$this->model_setting_setting->getKeySettingValue('config', 'config_confirmed_delivery_payment_ids', (int)$store_id)))){
				$template->data['payment_will_be_done_on_delivery'] = true;
				} else {
				//Есть какая-либо предоплата
				$template->data['payment_will_be_done_on_delivery'] = false;
			}
			
			if ($order_info['payment_country_id'] == 20){
				$template->data['only_full_payment'] = true;
				} else {
				$template->data['only_full_payment'] = false;
			}
			
			$template->data['shipping_address'] = EmailTemplate::formatAddress($order_info, 'shipping', $order_info['shipping_address_format']);
			$template->data['payment_address'] = EmailTemplate::formatAddress($order_info, 'payment', $order_info['payment_address_format']);
			
			// Products
			$template->data['products'] = array();
			foreach ($order_product_query->rows as $product) {
				$option_data = array();
				$order_option_query = $this->db->non_cached_query("SELECT oo.*, pov.* FROM " . DB_PREFIX . "order_option oo LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_option_value_id = oo.product_option_value_id) WHERE oo.order_id = '" . (int)$order_id . "' AND oo.order_product_id = '" . (int)$product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
						} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}
					
					$price = false;
					if ((float)$option['price']) {
						$price = $this->currency->format($option['price'], $this->config->get('config_currency'));
					}
					
					$option_data[] = array(
					'name'  => $option['name'],
					'price'  => $price,
					'price_prefix'  => $option['price_prefix'],
					'value' => (utf8_strlen($value) > 120 ? utf8_substr($value, 0, 120) . '..' : $value)
					);
				}
				
				if (isset($product['image']) && $product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 50, 50);
					$image_width = $image_height = 50;
					} else {
					$image = '';
					$image_width = $image_height = 0;
				}
				
				$url = $template->data['store_url'] . '?route=product/product&product_id='.$product['product_id'];
				
				if ($product['price_national'] > 0){
					$price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');				
					} else {
					$price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);	
				}
				
				if ($product['total_national'] > 0){
					$total = $this->currency->format($product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');		
					} else {
					$total = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
				}
				
				
				$template->data['products'][] = array(
				'url'     		=> $url,
				'url_tracking' 	=> $template->getTracking($url),
				'image'     	=> $image,
				'image_width' 	=> $image_width,
				'image_height'  => $image_height,
				'product_id'	=> $product['order_product_id'],
				'sku'			=> $product['sku'],
				'stock_quantity'=> ($product['stock_quantity'] - $product['quantity']),
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $price,
				'total'    => $total
				);
			}
			
			// Products NOGOOD
			$template->data['products_nogood'] = array();
			if (isset($product)){
				unset($product);
			}
			foreach ($order_product_query_nogood->rows as $product) {
				$option_data = array();
				$order_option_query = $this->db->non_cached_query("SELECT oo.*, pov.* FROM " . DB_PREFIX . "order_option oo LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_option_value_id = oo.product_option_value_id) WHERE oo.order_id = '" . (int)$order_id . "' AND oo.order_product_id = '" . (int)$product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
						} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}
					
					$price = false;
					if ((float)$option['price']) {
						$price = $this->currency->format($option['price'], $this->config->get('config_currency'));
					}
					
					$option_data[] = array(
					'name'  => $option['name'],
					'price'  => $price,
					'price_prefix'  => $option['price_prefix'],
					'value' => (utf8_strlen($value) > 120 ? utf8_substr($value, 0, 120) . '..' : $value)
					);
				}
				
				if (isset($product['image']) && $product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 50, 50);
					$image_width = $image_height = 50;
					} else {
					$image = '';
					$image_width = $image_height = 0;
				}
				
				$url = $template->data['store_url'] . '?route=product/product&product_id='.$product['product_id'];
				
				if ($product['price_national'] > 0){
					$price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');				
					} else {
					$price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);	
				}
				
				if ($product['total_national'] > 0){
					$total = $this->currency->format(0, $order_info['currency_code'], '1');		
					} else {
					$total = $this->currency->format(0, $order_info['currency_code'], $order_info['currency_value']);
				}
				
				
				$template->data['products_nogood'][] = array(
				'url'     		=> $url,
				'url_tracking' 	=> $template->getTracking($url),
				'image'     	=> $image,
				'image_width' 	=> $image_width,
				'image_height'  => $image_height,
				'product_id'	=> $product['order_product_id'],
				'sku'			=> $product['sku'],
				'stock_quantity'=> ($product['stock_quantity'] - $product['quantity']),
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $price,
				'total'    => $total
				);
			}
			
			// Vouchers
			$template->data['vouchers'] = array();
			if($order_voucher_query){
				foreach ($order_voucher_query->rows as $voucher) {
					$template->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
			}
			
			// Order Totals
			$template->data['totals'] = $order_total_query->rows;
			
			$emailData = array(
			'key' => $template_key,
			'language_id' => $language_id,
			'store_id' => $store_id
			);
			
			if(isset($data['emailtemplate_config_id'])){
				$emailData['emailtemplate_config_id'] = $data['emailtemplate_config_id'];
			}
			
			$template->load($emailData);
			
			if(isset($data['demo'])){
				//$template->set('css', false);
			}
			$template->set('insert_shortcodes', false);
			
			$template->build();
			
			return $template;
		}
		
		/**
			* Send Test Email with demo template
		*/
		public function sendTestEmail($toAddress, $store_id = 0, $language_id = 1){
			$language_id = intval($language_id);
			$store_id = intval($store_id);
			
			$result = $this->db->non_cached_query("SELECT `order_id` FROM `" . DB_PREFIX . "order` WHERE (`store_id` = '{$store_id}' OR `store_id` = 0) AND (`language_id` = '{$language_id}' OR `language_id` > 0) AND order_status_id > '0' ORDER BY `order_id` DESC LIMIT 1");
			if(!$result->row){
				$result = $this->db->non_cached_query("SELECT `order_id` FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' ORDER BY `order_id` DESC LIMIT 1");
			}
			
			if($result->row){
				$template = $this->getCompleteOrderEmail($result->row['order_id']);
				
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail = $template->hook($mail);
				$mail->setSubject('TEST - ' . $this->config->get('config_name'));
				$mail->setText($template->getPlainText());
				$mail->setTo($toAddress);
				if($mail->send()){
					return true;
				}
				return true;  # false; always returns true bug in mail.php
				} else {
				$this->session->data['error'] = $this->language->get('text_no_orders');
			}
			
			return false;
		}
		
		/**
			* Load show for admin emails
		*/
		public function getShowcase($config = null, $data = array()){
			$return = array();
			
			$this->load->model('catalog/product');
			$this->load->model('sale/order');
			$this->load->model('sale/product');
			$this->load->model('tool/image');
			
			$config = array_merge($config, $this->getStores($config['store_id']));
			
			if(!empty($config['customer_group_id'])){
				$customer_group_id = $config['customer_group_id'];
				} else {
				$customer_group_id = $config['config_customer_group_id'];
			}
			
			if(!empty($config['store_id'])){
				$store_id = $config['store_id'];
				} else {
				$store_id = 0;
			}
			
			if(!empty($config['language_id'])){
				$language_id = $config['language_id'];
				} else {
				$language = $this->model_localisation_language->getLanguageByCode($config['config_language']);
				$language_id = $config['config_language'];
			}
			
			require_once(VQMod::modCheck(DIR_SYSTEM . 'library/tax.php'));
			require_once(VQMod::modCheck(DIR_SYSTEM . 'library/customer.php'));
			
			$registry = clone $this->registry;
			
			$registry->set('customer', new Customer($registry));
			
			$oConfig = $registry->get('config');
			
			$oConfig->set('config_customer_group_id', $customer_group_id);
			
			$registry->set('config', $oConfig);
			
			$oTax = new Tax($registry);
			
			if (isset($config['config_tax_default'])){
				if($config['config_tax_default'] == 'shipping') {
					$oTax->setShippingAddress($config['config_country_id'], $config['config_zone_id']);
					} elseif($config['config_tax_default'] == 'payment'){
					$oTax->setPaymentAddress($config['config_country_id'], $config['config_zone_id']);
				}
				} else {
				if(method_exists($oTax, 'setZone')) {
					$oTax->setZone($config['config_country_id'], $config['config_zone_id']); # oc:151
				}
			}
			
			if(method_exists($oTax, 'setStoreAddress')){
				$oTax->setStoreAddress($config['config_country_id'], $config['config_zone_id']);
			}
			
			$products = array();
			$order_products = array();
			
			if($config['showcase_related'] && isset($data['order_id'])){
				$result = $this->model_sale_order->getOrderProducts($data['order_id']);
				if($result){
					foreach($result as $row){
						$order_products[$row['product_id']] = $row;
					}
					foreach($result as $row){
						$result2 = $this->model_sale_product->getProductRelated($row['product_id'], $language_id, $store_id, $customer_group_id);
						if($result2){
							foreach($result2 as $row2){
								if(!isset($products[$row2['product_id']]) && !isset($order_products[$row2['product_id']])){
									$products[$row2['product_id']] = $row2;
								}
							}
						}
					}
				}
			}
			
			if(count($products) < $config['showcase_limit']){
				$limit = count($order_products) + $config['showcase_limit'];
				switch($config['showcase']){
					case 'bestsellers':
					$result = $this->model_sale_product->getBestSellerProducts($limit, $language_id, $store_id, $customer_group_id);
					break;
					
					case 'latest':
					$result = $this->model_sale_product->getLatestProducts($limit, $language_id, $store_id, $customer_group_id);
					break;
					
					case 'specials':
					$result = $this->model_sale_product->getProductSpecials(array('start' => 0, 'limit' => $limit), $language_id, $store_id, $customer_group_id);
					break;
					
					case 'popular':
					$result = $this->model_sale_product->getPopularProducts($limit, $language_id, $store_id, $customer_group_id);
					break;
					
					case 'products':
					if($config['showcase_selection']){
						$result = array();
						$selection = explode(',', $config['showcase_selection']);
						foreach($selection as $product_id){
							if($product_id && !isset($products[$product_id])){
								$row = $this->model_sale_product->getProduct($product_id, $language_id, $store_id, $customer_group_id);
								if($row){
									$result[] = $row;
								}
							}
						}
					}
					break;
				}
				
				if($result){
					foreach($result as $row){
						if(count($products) >= $config['showcase_limit']){
							break;
						}
						if(!isset($products[$row['product_id']]) && !isset($order_products[$row['product_id']])){
							$products[$row['product_id']] = $row;
						}
					}
				}
			}
			
			if(!empty($products)){
				foreach($products as $row){
					if(!isset($row['product_id'])) continue;
					
					if ($row['image']) {
						$image = $this->model_tool_image->resize($row['image'], 100, 100);
						$image_width = 100;
						$image_height = 100;
						} else {
						$image = false;
						$image_width = 0;
						$image_height = 0;
					}
					
					if (!$config['config_customer_price']) {
						$price = $this->currency->format($oTax->calculate($row['price'], $row['tax_class_id'], $config['config_tax']));
						} else {
						$price = false;
					}
					
					$product_specials = $this->model_catalog_product->getProductSpecials($row['product_id']);
					
					$special = false;
					foreach ($product_specials  as $product_special) {
						if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
							$special = $this->currency->format($oTax->calculate($product_special['price'], $row['tax_class_id'], $config['config_tax']));
							break;
						}
					}
					
					$item = array(
					'product_id' => $row['product_id'],
					'image' => $image,
					'image_width' => $image_width,
					'image_height' => $image_height,
					'name' => $row['name'],
					'rating' => round($row['rating']),
					'reviews' => $row['reviews'] ? $row['reviews'] : 0,
					'name_short' => EmailTemplate::truncate_str($row['name'], 28, ''),
					'description' => EmailTemplate::truncate_str(strip_tags(html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8')), 100),
					'price' => $price,
					'special' => $special,
					'url' => 'index.php?route=product/product&amp;product_id=' . $row['product_id'],
					);
					
					if($item['name_short'] != $item['name']){
						$item['preview'] = $item['name'] . ' - ' . $item['description'];
						} else {
						$item['preview'] = $item['description'];
					}
					
					$return[] = $item;
				}
			}
			
			return $return;
		}
		
		/**
			* Method handles saving custom language changes to new file
		*/
		public function languageFile($dir, $language, $directory, $file, $data){
			$path = str_replace('../', '', $dir.$language.'/'.$directory.'/');
			$file = basename($file, ".php");
			$filepath = $path.$file.'.php';
			$newfile = $path.$file.'_.php';
			$changes = array();
			
			if(!file_exists($filepath)) return false;
			
			# Load original
			$oLanguage = new Language($language);
			$oLanguage->setPath($dir);
			$language_vars = $oLanguage->load_full($directory.'/'.$file, false);
			
			# Compare changes to original language file
			foreach ($language_vars as $key => $value){
				$data[$key] = trim(str_replace(array("\r\n", "\n", "\r"), '', html_entity_decode($data[$key], ENT_QUOTES, 'UTF-8')));
				$value = trim(str_replace(array("\r\n", "\n", "\r"), '', $value));
				if(isset($data[$key]) && strcmp($value, $data[$key]) !== 0){
					$changes[$key] = $data[$key];
				}
			}
			
			if(file_exists($newfile)){
				$oLanguage = new Language($language);
				$oLanguage->setPath($dir);
				$custom_language_vars = $oLanguage->load_full($directory.'/'.$file.'_');
				
				# Compare changes to custom language file
				foreach ($custom_language_vars as $key => $value){
					$changes[$key] = $data[$key];
				}
			}
			
			if(empty($changes)) return false;
			
			ksort($changes);
			
			# Write new file
			$contents = "<?php\n# Anything in here with the same name will overwrite the main file without underscore. \n\n";
			foreach($changes as $key => $value){
				$contents .= str_pad("\$_['" . $key . "']", 30, " ", STR_PAD_RIGHT) . "= '" . addslashes($value) . "'; \n";
			}
			$contents .= "\n?>";
			
			if(is_writable($newfile) && file_exists($newfile)){
				# only perform write if contents has changed in our custom language file
				if(file_get_contents($newfile) == $contents){
					return true;
					} elseif(file_put_contents($newfile, $contents)){
					return true;
				}
				} else {
				if(file_put_contents($newfile, $contents)){
					return true;
				}
			}
			
			# if we get to here then we have been unable to write
				return array(
				'file' => $newfile,
				'filename' => $file.'_.php',
				'path' => $path,
				'contents' => $contents
				);
			}
			
			/**
				* Method handles creating new tables and fixing opencart bugs
			*/
			public function install($req = array()){
				$this->load->model('setting/setting');
				
				$p = DB_PREFIX;
				$queries = array();
				
				// Check settings table has serialised - OC Version: < 1.5.0.5
				$chk = $this->db->non_cached_query("SHOW COLUMNS FROM `{$p}setting` LIKE 'serialized'");
				if(!$chk->num_rows){
					$result = $this->db->non_cached_query("ALTER TABLE `{$p}setting` ADD `serialized` tinyint(1) NOT NULL DEFAULT 0");
				}
				
				// Opencart missing ability to find a registered customers language
				$chk = $this->db->non_cached_query("SHOW COLUMNS FROM `{$p}customer` LIKE 'language_id'");
				if(!$chk->num_rows){
					$result = $this->db->non_cached_query("ALTER TABLE `{$p}customer` ADD `language_id` int(11) NOT NULL DEFAULT '0' AFTER `store_id`");
				}
				
				// Add order PDf field
				$chk = $this->db->non_cached_query("SHOW COLUMNS FROM `{$p}order` LIKE 'invoice_filename'");
				if(!$chk->num_rows){
					$result = $this->db->non_cached_query("ALTER TABLE `{$p}order` ADD `invoice_filename` varchar(255) NOT NULL AFTER `invoice_prefix`");
				}
				
				// Add order weight
				$chk = $this->db->non_cached_query("SHOW COLUMNS FROM `{$p}order` LIKE 'weight'");
				if(!$chk->num_rows){
					$result = $this->db->non_cached_query("ALTER TABLE `{$p}order` ADD `weight` decimal(15,8) NOT NULL DEFAULT '0.00000000' AFTER `invoice_prefix`");
				}
				
				// Default install
				$sql = DIR_APPLICATION.'model/module/emailtemplate/install.sql';
				if(!file_exists($sql)){
					$this->session->data['error'] = sprintf($this->language->get('error_install_sql'), $sql);
					return false;
				}
				$stmnts = $this->_parse_sql($sql);
				foreach($stmnts as $stmt){
					$this->db->non_cached_query($stmt);
				}
				
				// Shortcode install
				if(isset($req['insert_shortcodes'])){
					$sql = DIR_APPLICATION.'model/module/emailtemplate/install-shortcodes.sql';
					if(!file_exists($sql)){
						$this->session->data['error'] = sprintf($this->language->get('error_install_sql'), $sql);
						return false;
					}
					$stmnts = $this->_parse_sql($sql);
					foreach($stmnts as $stmt){
						$this->db->non_cached_query($stmt);
					}
					} else {
					$this->db->non_cached_query("UPDATE `{$p}emailtemplate` SET `emailtemplate_shortcodes` = '0' WHERE `emailtemplate_id` != 1");
				}
				
				// Delete old files
				if(!empty($req['install_cleanup'])){
					$old_files = array(
					'admin/view/template/mail/affiliate_approve.tpl',
					'admin/view/template/mail/affiliate_transaction.tpl',
					'admin/view/template/mail/customer_approve.tpl',
					'admin/view/template/mail/customer_reward.tpl',
					'admin/view/template/mail/customer_transaction.tpl',
					'admin/view/template/mail/customer_voucher.tpl',
					'catalog/language/*/product/product_.php',
					'catalog/view/theme/*/template/mail/_mail.tpl',
					'catalog/view/theme/*/template/mail/affiliate_forgotten.tpl',
					'catalog/view/theme/*/template/mail/affiliate_register_admin.tpl',
					'catalog/view/theme/*/template/mail/affiliate_register.tpl',
					'catalog/view/theme/*/template/mail/contact.tpl',
					'catalog/view/theme/*/template/mail/customer_forgotten.tpl',
					'catalog/view/theme/*/template/mail/customer_register.tpl',
					'catalog/view/theme/*/template/mail/ebay_order_confirm.tpl',
					'catalog/view/theme/*/template/mail/ebay_order_update.tpl',
					'catalog/view/theme/*/template/mail/openbay_order_admin.tpl',
					'catalog/view/theme/*/template/mail/play_order_update.tpl',
					'catalog/view/theme/*/template/mail/product_review.tpl',
					'catalog/view/theme/*/template/mail/voucher_customer.tpl',
					'system/library/email_template.php',
					'vqmod/xml/emailtemplate_admin.xml'
					);
					
					$base = substr(DIR_SYSTEM, 0, -7);
					
					foreach($old_files as $file){
						$files = glob($base . $file);
						if($files){
							foreach($files as $file){
								if(file_exists($file)){
									unlink($file);
								}
							}
						}
					}
				}
				
				# Update store data
				$stores = $this->getStores();
				
				foreach($stores as $store){
					if(!empty($req['install_cleanup'])){
						$this->model_setting_setting->deleteSetting('emailtemplate', $store["store_id"]);
					}
					
					$logo = isset($store['config_logo']) ? $store['config_logo'] : '';
					if($logo && file_exists(DIR_IMAGE.$logo)){
						list($config_logo_width, $config_logo_height) = getimagesize(DIR_IMAGE.$logo);
						} else {
						$config_logo_width = 0;
						$config_logo_height = 0;
					}
					$data = array(
					'emailtemplate_config_logo' => $logo,
					'emailtemplate_config_logo_height' => $config_logo_height,
					'emailtemplate_config_logo_width' => $config_logo_width,
					'emailtemplate_config_tracking_campaign_name' => $store["store_name"],
					'emailtemplate_config_name' => $store["store_name"],
					'emailtemplate_config_theme' => $store["config_template"],
					'emailtemplate_config_version' => EmailTemplate::$version,
					'store_id' => $store["store_id"]
					);
					
					if($store["store_id"] == 0){
						$this->updateConfig(1, $data);
						} else {
						$this->cloneConfig(1, $data);
					}
				}
				
				$languages = $this->model_localisation_language->getLanguages();
				
				$emailtemplates = $this->getTemplates();
				foreach($emailtemplates as $emailtemplate){
					$emailtemplates_descriptions = $this->getTemplateDescription(array('emailtemplate_id' => $emailtemplate['emailtemplate_id']));
					
					if(isset($req['insert_shortcodes'])){
						$this->updateTemplate($emailtemplate['emailtemplate_id'], array('emailtemplate_shortcodes' => 1));
					}
					
					foreach ($languages as $language){
						$data = isset($emailtemplates_descriptions[$language['language_id']]) ? $emailtemplates_descriptions[$language['language_id']] : current($emailtemplates_descriptions);
						
						if(isset($req['lang_shortcodes'])){
							$oLanguage = new Language($language['directory']);
							if(substr($emailtemplate['emailtemplate_key'], 0, 6) != 'admin.' && defined('DIR_CATALOG')){
								$oLanguage->setPath(DIR_CATALOG.'language/');
							}
							$oLanguage->load_full($language['filename']);
							
							$langData = array();
							$find = array();
							$replace = array();
							
							$language_files = explode(',', $emailtemplate['emailtemplate_language_files']);
							foreach($language_files as $language_file){
								if($language_file){
									$_langData = $oLanguage->load_full(trim($language_file));
									if($_langData){
										$langData = array_merge($langData, $_langData);
									}
								}
							}
							
							foreach($langData as $key => $val){
								if((is_string($val) && (strpos($val, '%s') === false) || is_int($val))){
									$find[$key] = '{$'.$key.'}';
									$replace[$key] = $val;
								}
							}
							
							foreach($data as $col => $val){
								if($val && is_string($val)){
									$data[$col] = str_replace($find, $replace, $val);
								}
							}
						}
						
						$data['language_id'] = $language['language_id'];
						$data['emailtemplate_id'] = $emailtemplate['emailtemplate_id'];
						
						$this->insertTemplateDescription($data);
					}
				}
				
				$this->deleteTemplateDescription(array('language_id' => 0));
				
				if(!empty($req['install_cleanup'])){
					$result = $this->db->non_cached_query("SHOW TABLES LIKE '{$p}email_templates'");
					if($result->rows){
						$result = $this->db->non_cached_query("SELECT * FROM `{$p}email_templates`");
						
						if($result->rows){
							foreach($result->rows as $row){
								switch($row['type']){
									case 'order_status':
									$defaultTemplate = $this->getTemplate('admin.order_update');
									break;
									case 'newsletter':
									$defaultTemplate = $this->getTemplate('admin.newsletter');
									break;
								}
								
								if($defaultTemplate){
									unset($defaultTemplate['emailtemplate_id']);
									unset($defaultTemplate['emailtemplate_modified']);
									
									$defaultTemplate['emailtemplate_default'] = 0;
									$defaultTemplate['emailtemplate_label'] = $row['name'];
									
									$defaultTemplate['emailtemplate_description_comment'] = array();
									$defaultTemplate['emailtemplate_description_comment'][$row['language_id']] = html_entity_decode($row['body'], ENT_QUOTES, 'UTF-8');
									
									$emailtemplate_id = $this->insertTemplate($defaultTemplate);
								}
							}
						}
						
						$this->db->non_cached_query("DROP TABLE IF EXISTS `{$p}email_templates`");
					}
				}
				
				$this->_updateVqmod();
				
				$this->_deleteCache();
				
				return true;
			}
			
			/**
				* Apply upgrade queries
			*/
			public function upgrade(){
				$p = DB_PREFIX;
				$dir = DIR_APPLICATION.'model/module/emailtemplate/upgrade/';
				$current_ver = $this->checkVersion();
				
				if(!is_dir($dir) || !$current_ver) return false;
				
				// 2.3
				$chk = $this->db->non_cached_query("SHOW COLUMNS FROM `{$p}order` LIKE 'weight'");
				if(!$chk->num_rows){
					$result = $this->db->non_cached_query("ALTER TABLE `{$p}order` ADD `weight` decimal(15,8) NOT NULL DEFAULT '0.00000000' AFTER `invoice_prefix`");
				}
				
				// 1.18
				$chk = $this->db->non_cached_query("SHOW COLUMNS FROM `{$p}emailtemplate_description` LIKE 'emailtemplate_description_comment'");
				if(!$chk->num_rows){
					$this->db->non_cached_query("ALTER TABLE `{$p}emailtemplate_description` ADD `emailtemplate_description_comment` longtext NOT NULL");
				}
				
				$upgrades = glob($dir.'*.sql');
				natsort($upgrades);
				
				foreach($upgrades as $i => $file){
					$ver = substr(substr($file, 0, -4), strlen($dir));
					
					if(version_compare($current_ver, $ver) >= 0) continue;
					
					$stmnts = $this->_parse_sql($file);
					foreach($stmnts as $stmt){
						$this->db->non_cached_query($stmt);
					}
				}
				
				$this->db->non_cached_query("UPDATE `{$p}emailtemplate_config` SET emailtemplate_config_version = '".EmailTemplate::$version."'");
				
				$this->_updateVqmod();
				
				$this->_deleteCache();
				
				return true;
			}
			
			/**
				* Method handles removing table
			*/
			public function uninstall(){
				$p = DB_PREFIX;
				$queries = array();
				$queries[] = "DROP TABLE IF EXISTS `{$p}emailtemplate`";
				$queries[] = "DROP TABLE IF EXISTS `{$p}emailtemplate_config`";
				$queries[] = "DROP TABLE IF EXISTS `{$p}emailtemplate_description`";
				$queries[] = "DROP TABLE IF EXISTS `{$p}emailtemplate_shortcode`";
				foreach($queries as $query){
					$this->db->non_cached_query($query);
				}
				
				$vqmodPath = str_replace("/system/", "/vqmod/", DIR_SYSTEM);
				$xmlFile = $vqmodPath."xml/emailtemplate.xml";
				if(file_exists($xmlFile)){
					unlink($xmlFile);
				}
				
				$this->_deleteCache();
				
				return true;
			}
			
			/**
				* Check version of files with databse
				*
				* @return version upgrading from
			*/
			public function checkVersion(){
				$p = DB_PREFIX;
				$chk = $this->db->non_cached_query("SHOW COLUMNS FROM `{$p}emailtemplate_config` LIKE 'emailtemplate_config_version'");
				if(!$chk->num_rows){
					$result = $this->db->non_cached_query("ALTER TABLE `{$p}emailtemplate_config` ADD `emailtemplate_config_version` varchar(64) NOT NULL");
					return '0.1';
					} else {
					$result = $this->db->non_cached_query("SELECT `emailtemplate_config_version` FROM `{$p}emailtemplate_config` WHERE `emailtemplate_config_id` = 1 LIMIT 1");
					
					if(version_compare(EmailTemplate::$version, $result->row['emailtemplate_config_version']) > 0){
						return $result->row['emailtemplate_config_version'];
					}
				}
				return false;
			}
			
			/**
				* Insert Log
			*/
			public function insertLog($data){
				$cols = EmailTemplateLogsDAO::describe();
				$p = DB_PREFIX;
				$logData = array();
				
				foreach($cols as $col => $type){
					$key = (strpos($col, 'emailtemplate_log_') === 0) ? substr($col, 18) : $col;
					
					if(!empty($data[$key])){
						$logData[$col] = $data[$key];
					}
				}
				
				$logData['emailtemplate_log_sent'] = '';
				
				if(!isset($logData['emailtemplate_log_type'])){
					$logData['emailtemplate_log_type'] = 'SYSTEM';
				}
				
				$inserts = $this->_build_query($cols, $logData);
				if (empty($inserts)) return false;
				
				$query = "INSERT IGNORE INTO {$p}emailtemplate_logs SET ".implode($inserts,", ");
				
				$this->db->non_cached_query($query);
				
				$email_log_id = $this->db->getLastId();
				
				if (isset($data['customer_id']) && $data['customer_id']){
					
					if (isset($data['order_id']) && $data['order_id']){
						$_order_id = $data['order_id'];
						} else {
						$_order_id = 0;
					}
					
					$query = "INSERT IGNORE INTO customer_history SET 
					customer_id = '" . (int)$data['customer_id'] . "', 
					order_id = '" . (int)$data['order_id'] . "', 
					date_added = NOW(), 
					email_id = '" . (int)$email_log_id . "',
					comment = 'Отправлен E-MAIL'";
					
					$this->db->non_cached_query($query);
				}
				
				return $email_log_id;
			}
			
			/**
				* Get template files
				*
				* @return array
			*/
			public function getTemplateFiles($theme){
				$return = array(
				'catalog' => array(),
				'catalog_default' => array(),
				'admin' => array(),
				'dirs' => array()
				);
				
				$base = str_replace('/system/', '/', DIR_SYSTEM);
				$dir = 'catalog/view/theme/' . $theme . '/template/mail/';
				$return['dirs']['catalog'] = $dir;
				$files = glob($base.$dir.'*.tpl');
				if($files){
					foreach($files as $file){
						$filename = basename($file);
						if($filename[0] == '_') continue;
						$return['catalog'][] = $filename;
					}
				}
				
				if($theme != "default"){
					$dir = 'catalog/view/theme/default/template/mail/';
					$return['dirs']['catalog_default'] = $dir;
					$files = glob($base.$dir.'*.tpl');
					if($files){
						foreach($files as $file){
							$filename = basename($file);
							if($filename[0] == '_') continue;
							$return['catalog_default'][] = $filename;
						}
					}
				}
				
				$dir = str_replace($base, '', DIR_TEMPLATE) .'mail/';
				$return['dirs']['admin'] = $dir;
				$files = glob($base.$dir.'*.tpl');
				if($files){
					foreach($files as $file){
						$filename = basename($file);
						if($filename[0] == '_') continue;
						$return['admin'][] = $filename;
					}
				}
				
				return $return;
			}
			
			/**
				* Get stores include default store OR return selected store if store_id
				*
				* @return array
			*/
			private $stores = null;
			public function getStores($store_id = null){
				if(is_null($this->stores)){
					$this->load->model('setting/store');
					$this->load->model('setting/setting');
					$this->load->model('localisation/language');
					
					$this->stores = array();
					$stores = array();
					$stores[0] = array(
					'store_id' 	 => 0,
					'name' => $this->config->get('config_name')
					);
					$stores = array_merge($stores, $this->model_setting_store->getStores());
					
					foreach ($stores as $result) {
						$storeId = $result['store_id'];
						$result = array_merge($result, $this->model_setting_setting->getSetting("config", $storeId));
						$language = $this->model_localisation_language->getLanguageByCode($result['config_language']);
						$this->stores[$storeId] = $result;
						$this->stores[$storeId]['store_title'] = $this->_stripHtml($result['config_title']);
						$this->stores[$storeId]['store_url'] = (isset($result['config_url'])) ? $result['config_url'] : (defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER);
						$this->stores[$storeId]['store_ssl'] = (isset($result['config_ssl'])) ? $result['config_ssl'] : (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : (defined('HTTPS_SERVER') ? HTTPS_SERVER : HTTP_SERVER));
						$this->stores[$storeId]['store_name'] = $this->_stripHtml($result['name']);
						$this->stores[$storeId]['store_name_short'] = $this->_truncate($result['name']);
						$this->stores[$storeId]['language_id'] = $language['language_id'];
						$this->stores[$storeId]['language_name'] = $language['name'];
					}
				}
				
				if(isset($this->stores[$store_id])){
					return $this->stores[$store_id];
					} else {
					return $this->stores;
				}
			}
			
			public function getUrl($route, $key, $value){
				$url = "index.php?route={$route}&{$key}={$value}";
				
				if($this->config->get('config_seo_url')){
					$query = $this->db->non_cached_query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					if(!empty($query->row['keyword'])){
						$url = $query->row['keyword'];
					}
				}
				
				return $url;
			}
			
			/**
				* Get xml modifications for vqmod
				*
				* @return array
			*/
			public function getVqmodXml(){
				$vqmodPath = str_replace("/system/", "/vqmod/", DIR_SYSTEM);
				
				$xml_files = array(
				'emailtemplate' => "",
				'vqmod_emailtemplate' => ""
				);
				
				$query = $this->db->non_cached_query("SELECT emailtemplate_key, emailtemplate_integrate_extension FROM ".DB_PREFIX."emailtemplate WHERE `emailtemplate_default` = 1 AND `emailtemplate_status` = 'enabled'");
				
				foreach($query->rows as $row){
					$file = DIR_APPLICATION . 'model/module/emailtemplate/vqmod/'. $row['emailtemplate_key'] . '.xml';
					if(file_exists($file)){
						if($row['emailtemplate_integrate_extension']){
							$xml_files['vqmod_emailtemplate'] .= "
							
							<!-- ".$row['emailtemplate_key']." -->
							".file_get_contents($file);
							} else {
							$xml_files['emailtemplate'] .= "
							
							<!-- ".$row['emailtemplate_key']." -->
							".file_get_contents($file);
						}
					}
				}
				
				foreach($xml_files as $file => $xml){
					if($xml) {
						$xml_files[$file] = "<modification>
						<id>Advanced Professional Email Template</id>
						<author>opencart-templates.co.uk</author>
						<modified>".date("H:i - jS M Y")."</modified>
						<version>".EmailTemplate::$version."</version>
						<vqmver>2.4.0</vqmver>
						" . $xml . "
						</modification>";
					}
				}
				
				return $xml_files;
			}
			
			/**
				* Image Absolute URL, no resize
				*
				* @duplicate: system/library/emailtemplate/email_template.php
			*/
			public function getImageUrl($filename){
				if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
					return;
				}
				
				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
					if($this->config->get('config_ssl')){
						$url = $this->config->get('config_ssl') . 'image/';
						} else {
						$url = defined("HTTPS_IMAGE") ? HTTPS_IMAGE : (defined("HTTPS_CATALOG") ? HTTPS_CATALOG : HTTPS_SERVER) . 'image/';
					}
					} else {
					if($this->config->get('config_url')){
						$url = $this->config->get('config_url') . 'image/';
						} else {
						$url = defined("HTTP_IMAGE") ? HTTP_IMAGE : (defined("HTTP_CATALOG") ? HTTP_CATALOG : HTTP_SERVER) . 'image/';
					}
				}
				
				return $url . $filename;
			}
			
			/**
				* Update vqmod xml
			*/
			private function _updateVqmod($clearCache = false){
				if(!EmailTemplate::$template_vqmod){
					return true;
				}
				
				$vqmodPath = substr(DIR_SYSTEM, 0, -7) . "vqmod/"; // remove 'system/'
				
				// Clean up
				if($clearCache){
					$files = glob($vqmodPath.'vqcache/vq*');
					
					if(file_exists($vqmodPath.'mods.cache')){
						$files[] = $vqmodPath.'mods.cache';
					}
					if(is_writable($vqmodPath."xml/emailtemplate.xml")){
						$files[] = $vqmodPath."xml/emailtemplate.xml";
					}
					if(is_writable($vqmodPath."xml/vqmod_emailtemplate.xml")){
						$files[] = $vqmodPath."xml/vqmod_emailtemplate.xml";
					}
					
					foreach ($files as $file) {
						if (file_exists($file)) {
							unlink($file);
							clearstatcache();
						}
					}
				}
				
				$vqmods = $this->getVqmodXml();
				if($vqmods){
					foreach($vqmods as $file => $xml){
						if($xml){
							$dom = new DOMDocument('1.0', 'UTF-8');
							$dom->preserveWhiteSpace = true;
							$dom->formatOutput = true;
							if(@$dom->loadXML($xml) !== false){
								if(!$dom->save($vqmodPath."xml/" . $file . ".xml")){
									return false;
								}
							}
						}
					}
				}
				
				return true;
			}
			
			/**
				* Delete all cache files that begin with emailtemplate_
				*
			*/
			private function _getEnumValues($table, $field){
				$table = $this->db->escape($table);
				$field = $this->db->escape($field);
				$result = $this->_fetch("SHOW COLUMNS FROM `{$table}` LIKE '{$field}'");
				$regex = "/'(.*?)'/";
				preg_match_all( $regex , $result->row['Type'], $enum_array );
				$enum_fields = $enum_array[1];
				foreach ($enum_fields as $key=>$value)
				{
					$label = str_replace("_", " ", $value);
					$label = strtolower($label);
					$label = ucwords($label);
					$label = trim($label);
					$enums[$value] = $label;
				}
				return $enums;
			}
			
			/**
				* Fetch query with caching
			*/
			private function _fetch($query){
				/*
					$queryName = 'emailtemplate_sql_'.md5($query);
					$result = $this->cache->get($queryName);
					if(!$result){
					$result = $this->db->non_cached_query($query);
					$this->cache->set($queryName, $result);
					}
					return $result;
				*/
				return $this->db->non_cached_query($query);
			}
			
			/**
				* Delete all cache files that begin with emailtemplate_
			*/
			private function _deleteCache($key = 'emailtemplate_sql_'){
				$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '*');
				if ($files) {
					foreach ($files as $file) {
						if (file_exists($file)) {
							unlink($file);
						}
					}
				}
			}
			
			/**
				* Method builds mysql for INSERT/UPDATE
				*
				* @param array $cols
				* @param array $data
				* @return array
			*/
			private function _build_query($cols, $data, $withoutCols = false){
				if(empty($data)) return $data;
				$return = array();
				
				foreach ($cols as $col => $type) {
					if (!array_key_exists($col, $data)) continue;
					
					switch ($type) {
						case EmailTemplateAbstract::INT:
						if(strtoupper($data[$col]) == 'NULL'){
							$value = 'NULL';
							} else {
							$value = intval($data[$col]);
						}
						break;
						case EmailTemplateAbstract::FLOAT:
						$value = floatval($data[$col]);
						break;
						case EmailTemplateAbstract::DATE_NOW:
						$value = 'NOW()';
						break;
						case EmailTemplateAbstract::SERIALIZE:
						$value = "'".base64_encode(serialize($data[$col]))."'";
						break;
						default:
						$value = "'".$this->db->escape($data[$col])."'";
					}
					
					if($withoutCols){
						$return[] = "'{$value}'";
						} else {
						$return[] = " `{$col}` = {$value}";
					}
				}
				
				return empty($return) ? false : $return;
			}
			
			/**
				* Parse SQL file and split into single sql statements.
				*
				* @param string $sql - file path
				* @return array
			*/
			private function _parse_sql($file){
				$sql = @fread(@fopen($file, 'r'), @filesize($file)) or die('problem reading sql:'.$file);
				$sql = str_replace('{$p}', DB_PREFIX, $sql);
				
				$lines = explode("\n", $sql);
				$linecount = count($lines);
				$sql = "";
				for ($i = 0; $i < $linecount; $i++){
					if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)){
						if (isset($lines[$i][0]) && $lines[$i][0] != "#"){
							$sql .= $lines[$i] . "\n";
							} else {
							$sql .= "\n";
						}
						$lines[$i] = "";
					}
				}
				
				$tokens = explode(';', $sql);
				$sql = "";
				
				$queries = array();
				$matches = array();
				
				$token_count = count($tokens);
				for ($i = 0; $i < $token_count; $i++){
					
					if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))){
						$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
						$unescaped_quotes = $total_quotes - $escaped_quotes;
						
						if (($unescaped_quotes % 2) == 0){
							$queries[] = trim($tokens[$i]);
							$tokens[$i] = "";
							} else {
							$temp = $tokens[$i] . ';';
							$tokens[$i] = "";
							$complete_stmt = false;
							
							for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++){
								$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
								$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
								$unescaped_quotes = $total_quotes - $escaped_quotes;
								
								if (($unescaped_quotes % 2) == 1){
									$queries[] = trim($temp . $tokens[$j]);
									$tokens[$j] = "";
									$temp = "";
									$complete_stmt = true;
									$i = $j;
									} else {
									$temp .= $tokens[$j] . ';';
									$tokens[$j] = "";
								}
								
							}
						}
					}
				}
				
				return $queries;
			}
			
			/**
				* Truncate Text
				*
				* @param string $text
				* @param int $limit
				* @param string $ellipsis
				* @return string
			*/
			private function _truncate($text, $limit = 20, $ellipsis = '...'){
				if(is_string($text) && strlen($text) > $limit){
					$text = trim(substr(strip_tags(html_entity_decode($text, ENT_QUOTES, 'UTF-8')), 0, $limit)) . $ellipsis;
				}
				return $text;
			}
			
			/**
				* Strip HTML with entity decode.
				*
				* @param string $text
				* @return string
			*/
			private function _stripHtml($text){
				if(is_string($text)){
					return strip_tags(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
				}
			}
		}