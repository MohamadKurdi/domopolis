<?php

class ModelCatalogSet extends Model { 

	public function getSets($data = array()) { 
		$sql = "SELECT * FROM `set` s LEFT JOIN set_description sd ON (s.set_id = sd.set_id)";
		
        $sql .= " WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.status>0 ";
        
        $sql .= " ORDER BY sort_order";
        	
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
	}

    public function getPersonsSetId ($setId) {
        $sql = "SELECT * FROM `set` WHERE `set_id` <> ".(int)$setId." AND `set_group` = (SELECT `set_group` FROM `set` WHERE `set_id` = ".(int)$setId.")";
        // print $sql;
        $query = $this->db->non_cached_query($sql);
//        print '<pre>';
//        print_r($query);
//        exit();
        return $query->rows;
    }


	public function getSet($set_id) { 
		$sql = "SELECT * FROM `set` s LEFT JOIN set_description sd ON (s.set_id = sd.set_id)";
		
        $sql .= " WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.status>0 AND s.set_id = '" . $set_id . "'";
			
		$query = $this->db->query($sql);
		
        return $query->row;
	}

    public function getProductSet($set_id) {
		$query = $this->db->query("SELECT p.price as price, ps.price as special FROM `set` s LEFT JOIN `product` p ON (s.product_id = p.product_id) LEFT JOIN `product_special` ps ON (ps.product_id = p.product_id) WHERE s.set_id = ".(int)$set_id);

        $price = $query->row['price'];
        $special = $query->row['special'];
        $save = $price - $special;

        return array('price' => $price, 'special' => $special, 'save' => $save);


	}



	public function isSetExist($product_id) {
	   
	    $set_id = 0; 
        
		$sql = "SELECT set_id FROM `set` WHERE product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);
		
        if(isset($query->row['set_id'])){
            $set_id = $query->row['set_id'];
        }
        
        return $set_id;
	}


	public function getSetsProduct($product_id) { 
		$sql = "SELECT DISTINCT * FROM `product_to_set` ps LEFT JOIN `set` s ON (ps.set_id = s.set_id) LEFT JOIN `set_description` sd ON (ps.set_id = sd.set_id)";
		
        // $sql .= " WHERE ps.clean_product_id = '" . (int)$product_id . "' AND ps.show_in_product='1' AND sd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.status>0";
        $sql .= " WHERE s.product_id = '" . (int)$product_id . "' AND ps.show_in_product='1' AND sd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.status>0";

        $sql .= " GROUP BY s.set_id ORDER BY s.sort_order";
        // print $sql;
			
		$query = $this->db->query($sql);
		
        return $query->rows;
	}

    public function getSetCategories($set_id) {
        $categories = array();
        $sql = "SELECT category_id FROM `set_to_category` WHERE set_id = '" . (int)$set_id . "'";
        $query = $this->db->query($sql);
        if($query->rows){
           foreach($query->rows as $row){
            $categories[] = $row['category_id'];
           } 
        }
        return $categories;
    }

	public function getProductsInSets($set_id = 0) {
 
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		} 
        
        $product_data =  array();
        
        $active_set = true;
       
		$sql = "SELECT * FROM `product_to_set`";
		
        $sql .= " WHERE set_id = '" . (int)$set_id . "'";
        
        $sql .= " ORDER BY sort_order";	
			
		$query = $this->db->query($sql);
        
        foreach($query->rows as $product){
            $sql2 = "SELECT *, (SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $this->NOW . "') AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $this->NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
            $sql2 .= " WHERE p.product_id='" . $product['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
            $query2 = $this->db->query($sql2);
            $options = array();
            $tmp = explode(':', $product['product_id']);
            $product_id = $tmp[0];
            if(isset($tmp[1])) {
                $options = unserialize(base64_decode($tmp[1]));
            }
            if($query2->row){
                $product_data[] = array(
                    'product_id'      => $product_id,
                    'product_wop_id'  => $product['product_id'],
                    'options'         => $options, 
                    'name'            => $query2->row['name'],
                    'present'         => $product['present'],
                    'image'           => $query2->row['image'],
                    'base_price'      => $query2->row['price'],
                    'base_special'    => $query2->row['special'],
                    'base_discount'   => $query2->row['discount'],
                    'base_quantity'   => $query2->row['quantity'],
                    'base_status'     => $query2->row['status'],
                    'tax_class_id'    => $query2->row['tax_class_id'],
                    'price_in_set'    => $product['price_in_set'],
                    'show_in_product' => isset($product['show_in_product']) ? $product['show_in_product'] : 0,
                    'quantity'        => $product['quantity'],
                    'sort_order'      => $product['sort_order']
                );                
            } else {
                $active_set = false;
            }        
        }
        if(!$active_set){
            $product_data =  array();
        }
        return $product_data;
	}

	public function getTotalSet() {
     	$sql = "SELECT COUNT(DISTINCT set_id) AS total FROM `set`";
	    
        $query = $this->db->query($sql);
           
		return $query->row['total'];
	}	
}
?>
