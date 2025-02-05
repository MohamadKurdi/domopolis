<?php
class ModelLocalisationLanguage extends Model {
	public function addLanguage($data) {
		$this->db->query("INSERT INTO language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', urlcode = '" . $this->db->escape($data['urlcode']) . "', hreflang = '" . $this->db->escape($data['hreflang']) . "', switch = '" . $this->db->escape($data['switch']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "', front = '" . (int)$data['front'] . "'");
		
		$this->cache->delete('language');
		
		$language_id = $this->db->getLastId();

	}

	public function addLanguageCli($language_id) {
		
			// Attribute 
		
		echo '[L] Атрибуты ' . PHP_EOL; 
		
		$query = $this->db->query("SELECT * FROM attribute_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $attribute) {
			$this->db->query("INSERT INTO attribute_description SET attribute_id = '" . (int)$attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "'");
		}
		
		echo '[L] Группы атрибутов ' . PHP_EOL; 
		
			// Attribute Group
		$query = $this->db->query("SELECT * FROM attribute_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $attribute_group) {
			$this->db->query("INSERT INTO attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "'");
		}
		
		$this->cache->delete('attribute');
		
		echo '[L] Баннер ' . PHP_EOL;
		
			// Banner
		$query = $this->db->query("SELECT * FROM banner_image_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $banner_image) {
			$this->db->query("INSERT INTO banner_image_description SET banner_image_id = '" . (int)$banner_image['banner_image_id'] . "', banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape('') . "'");
		}
		
		$this->cache->delete('banner');
		
		echo '[L] Категория ' . PHP_EOL;
		
			// Category
		$query = $this->db->query("SELECT * FROM category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "', meta_description = '" . $this->db->escape('') . "', meta_keyword = '" . $this->db->escape('') . "', description = '" . $this->db->escape('') . "'");
		}
		
		$this->cache->delete('category');
		
		echo '[L] Кастомер груп ' . PHP_EOL;
		
			// Customer Group
		$query = $this->db->query("SELECT * FROM customer_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $customer_group) {
			$this->db->query("INSERT INTO customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($customer_group['name']) . "', description = '" . $this->db->escape($customer_group['description']) . "'");
		}
		
			// Download
		$query = $this->db->query("SELECT * FROM download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
		}
		
			// Filter
		$query = $this->db->query("SELECT * FROM filter_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $filter) {
			$this->db->query("INSERT INTO filter_description SET filter_id = '" . (int)$filter['filter_id'] . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter['filter_group_id'] . "', name = '" . $this->db->escape($filter['name']) . "'");
		}
		
			// Filter Group
		$query = $this->db->query("SELECT * FROM filter_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $filter_group) {
			$this->db->query("INSERT INTO filter_group_description SET filter_group_id = '" . (int)$filter_group['filter_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($filter_group['name']) . "'");
		}
		
		echo '[L] Инфо ' . PHP_EOL;
		
			// Information
		$query = $this->db->query("SELECT * FROM information_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $information) {
			$this->db->query("INSERT INTO information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape('') . "', description = '" . $this->db->escape('') . "'");
		}		
		
		$this->cache->delete('information');
		
			// Length
		$query = $this->db->query("SELECT * FROM length_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($length['title']) . "', unit = '" . $this->db->escape($length['unit']) . "'");
		}	
		
		$this->cache->delete('length_class');
		
			// Option 
		$query = $this->db->query("SELECT * FROM option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $option) {
			$this->db->query("INSERT INTO option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "'");
		}
		
			// Option Value
		$query = $this->db->query("SELECT * FROM option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $option_value) {
			$this->db->query("INSERT INTO option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . $this->db->escape('') . "'");
		}
		
			// Order Status
		$query = $this->db->query("SELECT * FROM order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO order_status SET order_status_id = '" . (int)$order_status['order_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($order_status['name']) . "'");
		}	
		
		$this->cache->delete('order_status');
		
		echo '[L] Товар ' . PHP_EOL;
		
			// Product
		$total = $this->db->query("SELECT COUNT(*) as total FROM product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$total_products = $total->row['total'];
		
		$index = (int)($total_products / 500);	
		for ($i=0; $i<=$index; $i++){
			$start = $i * 500;
			
			$query = $this->db->query("SELECT * FROM product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT ". $start .", 500");
			
			foreach ($query->rows as $product) {
				$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "', meta_description = '" . $this->db->escape('') . "', meta_keyword = '" . $this->db->escape('') . "', description = '" . $this->db->escape('') . "', tag = '" . $this->db->escape('') . "'");
			}
		}
		
		$this->cache->delete('product');
		
		echo '[L] Значения атрибутов ' . PHP_EOL;
		
			// Product Attribute 
		$total = $this->db->query("SELECT COUNT(*) as total FROM product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$total_products = $total->row['total'];
		
		$total_products = $total->row['total'];
		
		$index = (int)($total_products / 500);	
		for ($i=0; $i<=$index; $i++){
			$start = $i * 500;
			
			$query = $this->db->query("SELECT * FROM product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT ". $start .", 500");
			
			foreach ($query->rows as $product_attribute) {
				$this->db->query("INSERT INTO product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape('') . "'");
			}
		}
		
			// Return Action 
		$query = $this->db->query("SELECT * FROM return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $return_action) {
			$this->db->query("INSERT INTO return_action SET return_action_id = '" . (int)$return_action['return_action_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_action['name']) . "'");
		}
		
			// Return Reason 
		$query = $this->db->query("SELECT * FROM return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $return_reason) {
			$this->db->query("INSERT INTO return_reason SET return_reason_id = '" . (int)$return_reason['return_reason_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_reason['name']) . "'");
		}
		
			// Return Status
		$query = $this->db->query("SELECT * FROM return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $return_status) {
			$this->db->query("INSERT INTO return_status SET return_status_id = '" . (int)$return_status['return_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_status['name']) . "'");
		}
		
			// Stock Status
		$query = $this->db->query("SELECT * FROM stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($stock_status['name']) . "'");
		}
		
		$this->cache->delete('stock_status');
		
			// Voucher Theme
		$query = $this->db->query("SELECT * FROM voucher_theme_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $voucher_theme) {
			$this->db->query("INSERT INTO voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($voucher_theme['name']) . "'");
		}
		
			// Weight Class
		$query = $this->db->query("SELECT * FROM weight_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($weight_class['title']) . "', unit = '" . $this->db->escape($weight_class['unit']) . "'");
		}
		
		$this->cache->delete('weight_class');
		
			// Profile
		$query = $this->db->query("SELECT * FROM profile_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $profile) {
			$this->db->query("INSERT INTO profile_description SET profile_id = '" . (int)$profile['profile_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($profile['name']));
		}		
	}
	
	public function editLanguage($language_id, $data) {
		$this->db->query("UPDATE language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', hreflang = '" . $this->db->escape($data['hreflang']) . "', switch = '" . $this->db->escape($data['switch']) . "', urlcode = '" . $this->db->escape($data['urlcode']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "', front = '" . (int)$data['front'] . "', fasttranslate = '" . $this->db->escape($data['fasttranslate']) . "' WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('language');
	}
	
	public function deleteLanguage($language_id) {
		$this->db->query("DELETE FROM language WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM attribute_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM attribute_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM banner_image_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM category_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM customer_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM download_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM filter_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM filter_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM information_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM information_attribute_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM length_class_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM option_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM option_value_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM order_status WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM product_attribute WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM product_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM return_action WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM return_reason WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM return_status WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM stock_status WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM voucher_theme_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM weight_class_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM profile_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM url_alias WHERE language_id = '" . (int)$language_id . "'");
	}
	
	public function getLanguage($language_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM language WHERE language_id = '" . (int)$language_id . "'");
		
		return $query->row;
	}
	
	public function getLanguageByCode($code) {
		$query = $this->db->query("SELECT language_id FROM language WHERE code = '" . $this->db->escape($code) . "' LIMIT 1");
		
		return (int)$query->row['language_id'];
	}
	
	public function getLanguageByCode2($code)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM language WHERE code = '" . $this->db->escape($code) . "' LIMIT 1");
		
		return $query->row;
	}
	
	public function getLanguages($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM language";
			
			$sort_data = array(
				'name',
				'code',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order, name";	
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
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {

			$language_data = array();
			
			$query = $this->db->query("SELECT * FROM language ORDER BY sort_order, name");
			
			foreach ($query->rows as $result) {
				$language_data[$result['code']] = array(
					'language_id' => $result['language_id'],
						'name'        => $result['code'],//$result['name']
						'code'        => $result['code'],
						'urlcode'     => $result['urlcode'],
						'hreflang'    => $result['hreflang'],
						'locale'      => $result['locale'],
						'image'       => $result['image'],
						'directory'   => $result['directory'],
						'filename'    => $result['filename'],
						'sort_order'  => $result['sort_order'],
						'front'       => $result['front'],
						'status'      => $result['status']
					);
			}
			
			return $language_data;			
		}
	}
	
	public function getTotalLanguages() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM language");
		
		return $query->row['total'];
	}
}