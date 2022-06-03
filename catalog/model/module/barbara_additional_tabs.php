<?php
class ModelModulebarbaraAdditionalTabs extends Model {
		public function getbarbaraTab($product_id){
				$this->install();
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_barbara_tab` WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
				$result = array();
				
				foreach ($query->rows as $product_additional) {
						$language_id = $this->config->get('config_language_id');
						$name = unserialize($product_additional['name']);
						$name = isset($name[$language_id]) ? $name[$language_id] : false;
						$description = unserialize($product_additional['description']);
						$description = isset($description[$language_id]) ? $description[$language_id] : false;
						
						if ($name || $description) {
								$result[] = array(
										'name' 				=>	$name,
										'description' =>	$description,
										'sort_order' 	=>	$product_additional['sort_order']
								);
						}
				}

				return $result;
		}
			
		public function install() {
				$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_barbara_tab` (
					`product_additional_id` int(11) NOT NULL AUTO_INCREMENT,
				  `product_id` int(11) NOT NULL,
				  `name` text NOT NULL,
				  `description` text NOT NULL,
				  `sort_order` int(5) NOT NULL,
				  PRIMARY KEY (`product_additional_id`),
					KEY `product_id` (`product_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
				$this->db->query($sql);
		}
}
