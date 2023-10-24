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
	 * Get Template
	 * @param int $id
	 * @return array
	 */
	public function getTemplate($id, $language_id = null, $keyCleanUp = false){
		$p = DB_PREFIX;
		$id = intval($id);

		$query = "SELECT * FROM emailtemplate WHERE `emailtemplate_id` = '{$id}'";
		$result = $this->_fetch($query);
		$return = ($result->row) ? $result->row : array();

		if($keyCleanUp){
			$cols = EmailTemplateDAO::describe();
			foreach($cols as $col => $type){
				$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;
				if(!isset($return[$key])){
					$return[$key] = $return[$col];
					unset($return[$col]);
				}
			}
		}

		if($language_id){
			$result = $this->getTemplateDescription(array('emailtemplate_id' => $id, 'language_id' => $language_id), 1);

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

		return $return;
	}

	/**
	 * Get Templates
	 *
	 * @return array
	 */
	public function getTemplates($data = array()){
		$p = DB_PREFIX;
		$cond = array();

		if (isset($data['store_id'])) {
			if(is_numeric($data['store_id'])){
				$cond[] = "e.`store_id` = '".intval($data['store_id'])."'";
			} else {
				$cond[] = "e.`store_id` IS NULL";
			}
		}

		if (isset($data['language_id']) && $data['language_id'] != 0) {
			$cond[] = "ed.`language_id` = '".intval($data['language_id'])."'";
		}

		if (isset($data['customer_group_id']) && $data['customer_group_id'] != 0) {
			$cond[] = "e.`customer_group_id` = '".intval($data['customer_group_id'])."'";
		}

		if (isset($data['emailtemplate_key']) && $data['emailtemplate_key'] != "") {
			$cond[] = "e.`emailtemplate_key` = '".$this->db->escape($data['emailtemplate_key'])."'";
		}

		if (isset($data['emailtemplate_status']) && $data['emailtemplate_status'] != "") {
			$cond[] = "e.`emailtemplate_status` = '".$this->db->escape($data['emailtemplate_status'])."'";
		} else {
			$cond[] = "e.`emailtemplate_status` = 'ENABLED'";
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

		if (isset($data['language_id']) && $data['language_id'] != 0) {
			$query = "SELECT e.*, ed.* FROM `emailtemplate` e LEFT JOIN `emailtemplate_description` ed ON(ed.emailtemplate_id = e.emailtemplate_id)";
		} else {
			$query = "SELECT e.* FROM `emailtemplate` e";
		}

		if(!empty($cond)){
			$query .= ' WHERE ' . implode(' AND ', $cond);
		}

		$sort_data = array(
			'label' => 'e.`emailtemplate_label`',
			'key' => 'e.`emailtemplate_key`',
			'template' => 'e.`emailtemplate_template`',
			'modified' => 'e.`emailtemplate_modified`',
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
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->_fetch($query);

		$return = array();

		foreach($result->rows as $row){
			$return[] = $row;
		}

		return $return;
	}

	/**
	 * Get Template
	 * @param array $data
	 * @return array
	 */
	public function getTemplateDescription($data = array(), $limit = null){
		$p = DB_PREFIX;
		$cond = array();
		$query = "SELECT * FROM emailtemplate_description";

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

		$result = $this->_fetch($query);

		return ($limit == 1) ? $result->row : $result->rows;
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
				$result = $this->_fetch("SELECT emailtemplate_id FROM emailtemplate WHERE emailtemplate_key = '".$this->db->escape($data['emailtemplate_key'])."' AND emailtemplate_default = 1 LIMIT 1");
				$cond[] = "es.`emailtemplate_id` = '".intval($result->row['emailtemplate_id'])."'";
			}
		} else {
			$cond[] = "es.`emailtemplate_id` = '".intval($data)."'";
		}

		$query = "SELECT es.* FROM `emailtemplate_shortcode` es";
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

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->_fetch($query);
		$cols = EmailTemplateShortCodesDAO::describe();

		foreach($result->rows as $key => &$row){
			foreach($cols as $col => $type){

				if(isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE){
					if($row[$col]){
						$row[$col] = unserialize(base64_decode($row[$col]));
					} else {
						$row[$col] = array();
					}
				}

				if($keyCleanUp){
					$key = (strpos($col, 'emailtemplate_shortcode_') === 0 && substr($col, -3) != '_id') ? substr($col, 24) : $col;
					if(!isset($row[$key])){
						$row[$key] = $row[$col];
						unset($row[$col]);
					}
				}

			}
		}

		return $result->rows;
	}

	/**
	 * Load showcase for store emails
	 * NOTE: duplicate method admin/model/module/emailtemplate.php
	 */
	public function getShowcase($config = null, $data = array()){
		$return = array();

		$this->load->model('catalog/product');
		$this->load->model('account/order');
		$this->load->model('tool/image');

		$products = array();
		$order_products = array();

		if($config['showcase_related'] && isset($data['order_id'])){
			$result = $this->model_account_order->getOrderProducts($data['order_id']);
			if($result){
				foreach($result as $row){
					$order_products[$row['product_id']] = $row;
				}
				foreach($result as $row){
					$result2 = $this->model_catalog_product->getProductRelated($row['product_id']);
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
			switch($config['showcase']){
				case 'bestsellers':
					$result = $this->model_catalog_product->getBestSellerProducts($config['showcase_limit']);
				break;

				case 'latest':
					$result = $this->model_catalog_product->getLatestProducts($config['showcase_limit']);
				break;

				case 'specials':
					$result = $this->model_catalog_product->getProductSpecials(array('start' => 0, 'limit' => $config['showcase_limit']));
				break;

				case 'popular':
					$result = $this->model_catalog_product->getPopularProducts($config['showcase_limit']);
				break;

				case 'products':
					if($config['showcase_selection']){
						$result = array();
						$selection = explode(',', $config['showcase_selection']);
						foreach($selection as $product_id){
							if($product_id && !isset($products[$product_id])){
								$row = $this->model_catalog_product->getProduct($product_id);
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

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($row['price'], $row['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$row['special']) {
					$special = $this->currency->format($this->tax->calculate($row['special'], $row['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
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
					'description' => EmailTemplate::truncate_str($row['description'], 100),
					'price' => $price,
					'special' => $special,
					'url' => $this->url->link('product/product', 'product_id=' . $row['product_id'])
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
	 * Get Email Template Config
	 *
	 * @param int||array $identifier
	 * @param bool $outputFormatting
	 * @param bool $keyCleanUp
	 * @return array
	 */
	public function getConfig($data, $outputFormatting = false, $keyCleanUp = false){
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
		} else {
			return false;
		}

		$query = "SELECT * FROM `emailtemplate_config`";
		if(!empty($cond)){
			$query .= " WHERE " . implode(" AND ", $cond);
		}
		$query .= " ORDER BY `language_id` DESC LIMIT 1";

		$result = $this->_fetch($query);
		if(empty($result->row)) return false;
		$row = $result->row;

		$cols = EmailTemplateConfigDAO::describe();
		foreach($cols as $col => $type){
			if(isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE){
				if($row[$col]){
					$row[$col] = unserialize(base64_decode($row[$col]));
				}
			}
		}

		if($outputFormatting){
			$row = $this->formatConfig($row);
		}

		if($outputFormatting){
			foreach($row as $col => $val){
				$key = (strpos($col, 'emailtemplate_config_') === 0 && substr($col, -3) != '_id') ? substr($col, 21) : $col;
				if(!isset($row[$key])){
					unset($row[$col]);
					$row[$key] = $val;
				}
			}
		}

		return $row;
	}

	/**
	 * Return array of configs
	 * @param array - $data
	 */
	public function getConfigs($data = array(), $outputFormatting = false){
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

		if (isset($data['status']) && $data['status'] != "") {
			$cond[] = "AND ec.`emailtemplate_config_status` = '".$this->db->escape($data['status'])."'";
		} else {
			$cond[] = "AND ec.`emailtemplate_config_status` = 'ENABLED'";
		}

		$query = "SELECT ec.* FROM `emailtemplate_config` ec";
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
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$query .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$result = $this->_fetch($query);
		if(empty($result->rows)) return array();
		$rows = $result->rows;

		$cols = EmailTemplateConfigDAO::describe();
		foreach($rows as $key => $row){
			foreach($cols as $col => $type){
				if(isset($row[$col]) && $type == EmailTemplateDAO::SERIALIZE){
					if($row[$col]){
						$row[$col] = unserialize(base64_decode($row[$col]));
					}
				}
			}
			$rows[$key] = $row;
		}

		return $rows;
	}


	/**
	 * Return array of configs
	 *
	 * @param array - $data
	 */
	public function formatConfig($data = array()){
		$this->load->model('tool/image');

		$data['emailtemplate_config_head_text'] = html_entity_decode($data['emailtemplate_config_head_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_view_browser_text'] = html_entity_decode($data['emailtemplate_config_view_browser_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_page_footer_text'] = html_entity_decode($data['emailtemplate_config_page_footer_text'], ENT_QUOTES, 'UTF-8');
		$data['emailtemplate_config_footer_text'] = html_entity_decode($data['emailtemplate_config_footer_text'], ENT_QUOTES, 'UTF-8');

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

		return $data;
	}


	/**
	 * Insert Template Short Codes
	 */
	public function insertTemplateShortCodes($id, $data){
		$id = intval($id);
		$cols = EmailTemplateShortCodesDAO::describe();
		$p = DB_PREFIX;
		$return = 0;

		$this->db->non_cached_query("DELETE FROM emailtemplate_shortcode WHERE `emailtemplate_id` = '{$id}'");

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
			if (empty($inserts)) return false;

			$this->db->non_cached_query("INSERT INTO emailtemplate_shortcode SET " . implode(", ", $inserts));
			$return++;
		}

		$this->db->non_cached_query("UPDATE emailtemplate SET `emailtemplate_shortcodes` = '1' WHERE `emailtemplate_id` = '{$id}'");

		$this->_deleteCache();

		return $return;
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

			if(!empty($data[$col])){
				$logData[$col] = $data[$col];
			} elseif(!empty($data[$key])){
				$logData[$col] = $data[$key];
			}
		}
		
		//var_dump($cols);
		//die();

		$logData['emailtemplate_log_sent'] = '';

		if(!isset($logData['emailtemplate_log_type'])){
			$logData['emailtemplate_log_type'] = 'SYSTEM';
		}

		$inserts = $this->_build_query($cols, $logData);
		if (empty($inserts)) return false;

		$query = "INSERT IGNORE INTO emailtemplate_logs SET " . implode(',', $inserts);				
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
				comment = 'Отправлен транзакционный E-MAIL'";
				
			$this->db->non_cached_query($query);
		}

		return $email_log_id;
	}

	/**
	 * Get Template Log
	 * @return array
	 */
	public function getTemplateLog($data, $keyCleanUp = false){
		$p = DB_PREFIX;
		$cond = array();

		if(is_array($data)){
			if(isset($data['emailtemplate_log_id'])) {
				$cond[] = "`emailtemplate_log_id` = '".intval($data['emailtemplate_log_id'])."'";
			}
			if(isset($data['emailtemplate_log_enc'])) {
				$cond[] = "`emailtemplate_log_enc` = '".$this->db->escape($data['emailtemplate_log_enc'])."'";
			}
		} elseif(is_numeric($data)) {
			$cond[] = "`emailtemplate_log_id` = '" . intval($data) . "'";
		} else {
			return false;
		}

		$query = "SELECT * FROM `emailtemplate_logs`";
		if(!empty($cond)){
			$query .= " WHERE " . implode(" AND ", $cond);
		}
		$query .= " LIMIT 1";

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
	 * Return last insert id for logs.
	 * @return int
	 */
	public function getLastTemplateLogId(){
		$p = DB_PREFIX;
		$query = "SELECT MAX(emailtemplate_log_id) as emailtemplate_log_id FROM `emailtemplate_logs`";
		$result = $this->db->non_cached_query($query);

		return $result->row['emailtemplate_log_id'];
	}

	/**
	 * Record Email as read, if not already read.
	 * Else update last read
	 *
	 * @param unknown $id
	 * @param unknown $enc
	 * @return boolean
	 */
	public function readTemplateLog($id, $enc){
		$p = DB_PREFIX;
		$id = intval($id);
		$enc = $this->db->escape($enc);

		// Update recent read
		$sql = "UPDATE emailtemplate_logs SET emailtemplate_log_read_last = NOW() WHERE emailtemplate_log_id = '{$id}' AND emailtemplate_log_enc = '{$enc}'";
		$this->db->non_cached_query($sql);

		// Should always affected log if everything is correct
		if($this->db->countAffected() > 0){

			// Update first read only if its empty, we already know enc is correct
			$sql = "UPDATE emailtemplate_logs SET emailtemplate_log_read = NOW() WHERE emailtemplate_log_id = '{$id}' AND emailtemplate_log_read IS NULL";
			$this->db->non_cached_query($sql);

			return true;
		}

		return false;
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
	 * Fetch query with caching
	 *
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
	 * Delete all cache files that begin with emailtemplate_
	 *
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
}