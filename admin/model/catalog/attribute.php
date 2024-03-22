<?php 
class ModelCatalogAttribute extends Model {
	public function addAttribute($data) {
		$this->db->query("INSERT INTO attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "', dimension_type = '" . $this->db->escape($data['dimension_type']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$attribute_id = $this->db->getLastId();

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

        $this->db->query("DELETE FROM attribute_variants WHERE attribute_id = '" . (int)$attribute_id . "'");

        if (!empty($data['attribute_variants'])){
            foreach ($data['attribute_variants'] as $language_id => $value){
                $exploded = prepareEOLArray($value);

                foreach ($exploded as $line){
                    $this->db->query("INSERT INTO attribute_variants SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', attribute_variant = '" . $this->db->escape($line) . "'");
                }
            }
        }

		$this->load->model('kp/content');
		$this->model_kp_content->addContent(['action' => 'add', 'entity_type' => 'attribute', 'entity_id' => $attribute_id]);
		
		return $attribute_id;
	}

	public function editAttribute($attribute_id, $data) {
		$this->db->query("UPDATE attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "', dimension_type = '" . $this->db->escape($data['dimension_type']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE attribute_id = '" . (int)$attribute_id . "'");

		$this->db->query("DELETE FROM attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

        $this->db->query("DELETE FROM attribute_variants WHERE attribute_id = '" . (int)$attribute_id . "'");

        if (!empty($data['attribute_variants'])){
            foreach ($data['attribute_variants'] as $language_id => $value){
                $exploded = prepareEOLArray($value);

                foreach ($exploded as $line){
                    $this->db->query("INSERT INTO attribute_variants SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', attribute_variant = '" . $this->db->escape($line) . "'");
                }
            }
        }

		$this->load->model('kp/content');
		$this->model_kp_content->addContent(['action' => 'edit', 'entity_type' => 'attribute', 'entity_id' => $attribute_id]);
	}

	public function deleteAttribute($attribute_id) {
		$this->db->query("DELETE FROM attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
        $this->db->query("DELETE FROM attribute_value_image WHERE attribute_id = '" . (int)$attribute_id . "'");
        $this->db->query("DELETE FROM attribute_variants WHERE attribute_id = '" . (int)$attribute_id . "'");
        $this->db->query("DELETE FROM attributes_category WHERE attribute_id = '" . (int)$attribute_id . "'");
        $this->db->query("DELETE FROM attributes_similar_category WHERE attribute_id = '" . (int)$attribute_id . "'");
        $this->db->query("DELETE FROM attributes_required_category WHERE attribute_id = '" . (int)$attribute_id . "'");

        $this->load->model('kp/content');
		$this->model_kp_content->addContent(['action' => 'delete', 'entity_type' => 'attribute', 'entity_id' => $attribute_id]);
    }

	public function getAttribute($attribute_id) {
		$query = $this->db->query("SELECT * FROM attribute a LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE a.attribute_id = '" . (int)$attribute_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

    public function getRandAttributesValues ($attribute_id) {
        $query = $this->db->query("SELECT DISTINCT `text` FROM `product_attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' AND `text` <> '' ORDER BY RAND() LIMIT 30");
        return $query->rows;
    }

    public function getRandOriginalAttributesValues ($attribute_id) {
        $query = $this->db->query("SELECT DISTINCT `text` FROM `product_attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "' AND language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "' AND `text` <> '' ORDER BY RAND() LIMIT 30");
        return $query->rows;
    }

    public function getSomeAttributesValues ($attribute_id, $language_id = false) {
        if (!$language_id){
            $language_id = $this->config->get('config_language_id');
        }

        $query = $this->db->query("SELECT DISTINCT `text` FROM `product_attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "' AND language_id = '" . (int)$language_id . "' AND `text` <> '' AND LENGTH(text) < 50 LIMIT 10");
        return $query->rows;
    }

    public function getTotalAttributeValues ($attribute_id, $filter_data = []) {
        $sql = "SELECT COUNT(DISTINCT `text`) as total FROM `product_attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "' AND `text` <> ''";

        if (!empty($filter_data['language_id'])){
            $sql .= " AND language_id = '" . (int)$filter_data['language_id'] . "'";
        }

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function replaceAttributeValue ($value_from, $value_to) {
        if (mb_strlen($value_from) && mb_strlen($value_to)){
            $this->db->query("UPDATE product_attribute SET text = '" . $this->db->escape($value_to) . "' WHERE text LIKE '" . $value_from . "'");
        }
    }

    public function deleteAttributeValue ($value_from) {
        if (mb_strlen($value_from)){
            $this->db->query("DELETE FROM product_attribute WHERE text LIKE '" . $value_from . "'");
        }
    }

    public function getAttributeValues ($attribute_id, $filter_data = []) {
        $sql = "SELECT DISTINCT `text`, GROUP_CONCAT(product_id SEPARATOR ',') as products FROM `product_attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "' AND `text` <> ''";

        if (!empty($filter_data['language_id'])){
            $sql .= " AND language_id = '" . (int)$filter_data['language_id'] . "'";
        }

        $sql .= " GROUP BY `text` ORDER BY product_id DESC";

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

    public function updateAttributeImageValues ($attribute_id, $images, $informations) {
    	if (!$this->config->get('config_enable_attributes_values_logic')){
    		return;
    	}

        $this->db->query("DELETE FROM attribute_value_image WHERE attribute_id = '" . (int)$attribute_id . "'");

        $insertArray = [];
        foreach ($images as $attributeNameValue => $image) {
            if ($image) {
                $insertArray[$attributeNameValue]['image'] = $image;
            }
        }
        foreach ($informations as $attributeNameValue => $info) {
            if ($info) {
                $insertArray[$attributeNameValue]['information_id'] = $info;
            }
        }

        foreach ($insertArray as $valueName => $item) {
            $keys = [];
            $values = [];
            foreach ($item as $itemKey => $itemValue) {
                $keys[] = "`".$itemKey."`";
                $values[] = "'".$itemValue."'";
            }


            $this->db->query("INSERT INTO attribute_value_image (`attribute_id`, `attribute_value`, ".implode(', ', $keys).") VALUES ('".(int)$attribute_id."', '".$this->db->escape($valueName)."', ".implode(", ", $values)." )");
        }
    }

    public function getAttributeImagesByAttributeId ($attribute_id) {
    	if (!$this->config->get('config_enable_attributes_values_logic')){
    		return [];
    	}


        $query = $this->db->query("SELECT * FROM attribute_value_image WHERE attribute_id = '" . (int)$attribute_id . "'");
        $imagesArray = [];
        foreach ($query->rows as $image) {
            if ($image['image']) {
                $imagesArray[$image['attribute_value']] = $image['image'];
            }
        }

        return $imagesArray;
    }

    public function getAttributeInformationByAttributeId ($attribute_id) {
    	if (!$this->config->get('config_enable_attributes_values_logic')){
    		return [];
    	}

        $query = $this->db->query("SELECT * FROM attribute_value_image WHERE attribute_id = '" . (int)$attribute_id . "'");
        $informationArray = [];
        foreach ($query->rows as $i) {
            $informationArray[$i['attribute_value']] = $i['information_id'];
        }

        return $informationArray;
    }

    public function guessAttributes($data = []) {
    	$attributes = [];

    	$sql = "SELECT * FROM attribute a LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE 1";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY a.attribute_id";

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

		foreach ($query->rows as $row){
			$attributes[$row['attribute_id']] = $this->getAttribute($row['attribute_id']);
		}
		
		return $attributes;
    }

	public function getAttributes($data = []) {
		$sql = "SELECT *, (SELECT agd.name FROM attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM attribute a LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

        if ($this->config->get('config_use_separate_table_for_features') && $this->config->get('config_special_attr_id')){
            $sql .= " AND a.attribute_group_id <> '" . $this->config->get('config_special_attr_id') . "'";
        }

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.dimension_type',
			'a.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY attribute_group, ad.name";	
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
	}

	public function getAttributeDescriptions($attribute_id) {
		$attribute_data = [];

		$query = $this->db->query("SELECT * FROM attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");

		foreach ($query->rows as $result) {
			$attribute_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_data;
	}

    public function getAttributeVariants($attribute_id) {
        $attribute_variants = [];
        $query = $this->db->query("SELECT * FROM attribute_variants WHERE `attribute_id` = '" . (int)$attribute_id . "'");

        foreach ($query->rows as $row){
            if (empty($attribute_variants[$row['language_id']])){
                $attribute_variants[$row['language_id']] = [];
            }

            $attribute_variants[$row['language_id']][] = $row['attribute_variant'];
        }

        foreach ($attribute_variants as $language_id => &$value){
            $value = createEOLArray($value);
        }

        return $attribute_variants;
    }

	public function getAttributesByAttributeGroupId($data = []) {
		$sql = "SELECT *, (SELECT agd.name FROM attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM attribute a LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'dimension_type',
			'a.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ad.name";	
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
	}

	public function getTotalAttributes($data = []) {
        $sql = "SELECT COUNT(*) AS total FROM attribute a LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_attribute_group_id'])) {
            $sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
        }

        if ($this->config->get('config_use_separate_table_for_features') && $this->config->get('config_special_attr_id')){
            $sql .= " AND a.attribute_group_id <> '" . $this->config->get('config_special_attr_id') . "'";
        }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}	

	public function getTotalAttributesByAttributeGroupId($attribute_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM attribute WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		return $query->row['total'];
	}		
}