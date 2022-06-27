<?php 
    class ModelCatalogGroupPrice extends Model {	
        
        public function install() {
            $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customer_group_price (
            `customer_group_id` INT( 11 ) NOT NULL, 
            `category_id` INT( 11 ) NOT NULL ,
            `price` FLOAT NOT NULL,
            `type` TINYINT( 1 ) )");
        }
        
        public function getAllCategoriesPrice() {
            $prices = array();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_price");
            if ($query->num_rows) {
                foreach ($query->rows as $row) {
                    $prices[$row['category_id']][$row['customer_group_id']] = $row;
                }
            }
            return $prices;    
        }
        
        public function getAllDbCategories() {
            $category_data = $this->cache->get('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
            
            if (!$category_data || !is_array($category_data)) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  ORDER BY c.parent_id, c.sort_order, cd.name");
                
                $category_data = array();
                foreach ($query->rows as $row) {
                    $category_data[$row['parent_id']][$row['category_id']] = $row;
                }
                
                $this->cache->set('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $category_data);
            }
            
            return $category_data;
        }
        
        public function getAllCategoriesWithParents($categories, $parent_id = 0, $parent_name = '') {
            $output = array();
            
            if (array_key_exists($parent_id, $categories)) {
                if ($parent_name != '') {
                    $parent_name .= $this->language->get('text_separator');
                }
                
                foreach ($categories[$parent_id] as $category) {
                    $output[$category['category_id']] = array(
                    'category_id' => $category['category_id'],
                    'name'        => $parent_name . $category['name']
                    );
                    
                    $output += $this->getAllCategoriesWithParents($categories, $category['category_id'], $parent_name . $category['name']);
                }
            }                                                                  
            
            return $output;
        }      
        
        public function saveCategoryPrices($post) {
            
            if (!is_array($post) || !isset($post['group_price'])) {
                return FALSE;
            }
            
            $categories_price     = $this->model_catalog_group_price->getAllCategoriesPrice();
            
            foreach ($post['group_price'] as $category_id => $groups ) {
                foreach ($groups as $customer_group_id => $price ) {
                    
                    $type = (isset($post['group_price_type'][$category_id][$customer_group_id]) 
                    && (int) $post['group_price_type'][$category_id][$customer_group_id]) 
                    ? (int) $post['group_price_type'][$category_id][$customer_group_id]
                    : 1;
                    
                    if (isset($categories_price[$category_id][$customer_group_id])) {
                        $this->db->query("UPDATE " . DB_PREFIX . "customer_group_price SET price = " . (float)$price . ", type = " . (int)$type . "  
                        WHERE customer_group_id = " . (int)$customer_group_id . " AND category_id = " . (int)$category_id);
                    }
                    else if (!empty($price)) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_price (customer_group_id, category_id, price, type) 
                        VALUES (" . (int)$customer_group_id . ", " . (int)$category_id .", " . (float)$price . ", " . (int)$type . ")");
                    }
                }
            }
        }
        
        public function deleteCategoryPrices($category_id) {
            
            if ($category_id !== NULL && $category_id !== FALSE) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_price  WHERE category_id = " . (int)$category_id);
            }
            
        }
        
        public function getLastCategoryId() {
            $query = $this->db->query("SELECT MAX(category_id) AS category_id FROM " . DB_PREFIX . "category WHERE date_added > NOW()-2");
            return $query->row['category_id'] ? $query->row['category_id'] : FALSE;
        }
        
        public function updateDbPrice($get) {
            
            $this->load->model('catalog/category');
            
            // category_id can be 0 (update price for all categories) or int (update price for this category)
            if (!isset($get['category_id']) || ($get['category_id'] != 0 && !$this->model_catalog_category->getCategory($get['category_id']))
            || !isset($get['price_value']) || !(float)$get['price_value'] 
            || !isset($get['price_type']) || !in_array((int)$get['price_type'], array(1,2), TRUE)) {
                return FALSE;
            }
            
            $query =	"UPDATE " . DB_PREFIX . "product p " ;
            
            $category_id = (int)$get['category_id'];    
            if ($category_id != 0) {
                $query .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) ";	
            }
            
            $price_type = (int)$get['price_type'];    
            $price_value = (float)$get['price_value'];
            
            switch ($price_type) {
                case 1:
                $query .= " SET price = p.price + p.price * " . (float)$price_value / 100;
                break;
                case 2:
                $query .= " SET price = p.price + " . (float)$price_value;
            }
            
            if ($category_id != 0) {
                $query .= " WHERE p2c.category_id = " . (int)$category_id;	
            }
            
            $this->db->query($query);
            
            return $this->db->countAffected();
        }
        
        
        
        
        
        
        public function updatePrice($product_id, $price) {
			
			if (!$this->config->get('config_group_price_enable')){
				return $price;
			}
			
			$min_price = $this->getMinPrice($product_id);
			
			// currency price
			if ($min_price['type'] == 2) {
				$price = $price + $min_price['price'];
				if ($price < 0 ) {
					$price = 0;
				}      
			}
			// % price
			else {
				$price = $price + $price * $min_price['price'] / 100;
			}
			
			return $price;
		}
		
		private function getMinPrice($product_id) {
			
			$price = array('price' => 0, 'type' => 1);
			
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getCustomerGroupId();
				} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$categories = array();
			$this->load->model('catalog/product');		
			$categories_arr = $this->model_catalog_product->getCategories($product_id);
			foreach ($categories_arr as $category_arr) {
				$categories[] = $category_arr['category_id'];
			}    
			
			if (count($categories)>0){
				
				// try to get category group price
				$query = "SELECT MIN(price) as price, type FROM customer_group_price 
				WHERE category_id IN (" .  implode(',', $categories) . ")" . " AND customer_group_id = " . (int)$customer_group_id . "";    
				$query = $this->db->query($query);
				
				if (isset($query->row['price'])) {
					$price['price'] = $query->row['price'];    
					$price['type']  = $query->row['type'];
				}
				// try to get base group prise 0 - all categories
				else {
					
					$query = "SELECT MIN(price) as price, type FROM customer_group_price 
					WHERE category_id = 0 " . " AND customer_group_id = " . (int)$customer_group_id . "";    
					$query = $this->db->query($query);
					
					if (isset($query->row['price'])) {
						$price['price'] = $query->row['price'];    
						$price['type']  = $query->row['type'];
					}
					
				}
				} else {
				$query = "SELECT MIN(price) as price, type FROM customer_group_price 
				WHERE category_id = 0 " . " AND customer_group_id = " . (int)$customer_group_id . "";    
				$query = $this->db->query($query);
				
				if (isset($query->row['price'])) {
					$price['price'] = $query->row['price'];    
					$price['type']  = $query->row['type'];
				}
			}
			
			return $price;    
		}
        
    }
