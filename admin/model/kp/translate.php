<?	
	class ModelKpTranslate extends Model {
		
		public function translate($text, $from, $to, $returnString = false){
			return $this->translateAdaptor->setDebug(true)->translate($text, $from, $to, $returnString = false);
		}	
		
		public function updateAttributeTranslation($product_id, $attribute_id, $language_id, $text){
			$this->db->query("INSERT IGNORE INTO product_attribute SET text = '" . $this->db->escape($text) . "', product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', attribute_id = '" . (int)$attribute_id . "'");
			$this->db->query("UPDATE product_attribute SET text = '" . $this->db->escape($text) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "' AND attribute_id = '" . (int)$attribute_id . "'");
		}

		public function updateAttributeNameTranslation($attribute_id, $language_id, $name){										
			$this->db->query("UPDATE attribute_description SET name = '" . $this->db->escape($name) . "' WHERE attribute_id = '" . (int)$attribute_id . "' AND language_id = '" . (int)$language_id . "'");
		}
		
		public function updateReviewTranslation($review_id, $language_id, $text, $field){										
			$this->db->query("UPDATE review_description SET `" . $field . "` = '" . $this->db->escape($text) . "' WHERE review_id = '" . (int)$review_id . "' AND language_id = '" . (int)$language_id . "'");
		}
		
		public function updateShopRatingTranslation($rate_id, $language_id, $text, $field){										
			$this->db->query("UPDATE shop_rating_description SET `" . $field . "` = '" . $this->db->escape($text) . "' WHERE rate_id = '" . (int)$rate_id . "' AND language_id = '" . (int)$language_id . "'");
		}
		
		public function updateDescriptionTranslation($product_id, $language_id, $text){										
			$this->db->query("UPDATE product_description SET description = '" . $this->db->escape($text) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}
		
		public function updateNameTranslation($product_id, $language_id, $text){										
			$this->db->query("UPDATE product_description SET name = '" . $this->db->escape($text) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}
		
		public function setTranslationMarker($product_id, $language_id, $marker = 1){										
			$this->db->query("UPDATE product_description SET translated = '" . (int)$marker . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}
				
		public function updateCollectionDescriptionTranslation($collection_id, $language_id, $text){										
			$this->db->query("UPDATE collection_description SET description = '" . $this->db->escape($text) . "' WHERE collection_id = '" . (int)$collection_id . "' AND language_id = '" . (int)$language_id . "'");
		}

		public function updateCategoryNameTranslation($category_id, $language_id, $text){								
			$this->db->query("UPDATE category_description SET name = '" . $this->db->escape($text) . "' WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "'");
		}
	}					