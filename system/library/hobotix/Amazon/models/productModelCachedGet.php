<?php

namespace hobotix\Amazon;

class productModelCachedGet extends hoboModel{

	private $cacheprefix = 'amazon.rainforest';

	private function getKey($scope, $key){
		return $this->cacheprefix . '.' . $scope . '.' . md5($key);
	}

	public function getManufacturer($name){
		if ($this->cache->get($this->getKey('manufacturers', $name), true)){
			return $this->cache->get($this->getKey('manufacturers', $name), true);
		}

		$query = $this->db->ncquery("SELECT manufacturer_id FROM manufacturer WHERE name LIKE ('" . $this->db->escape($name) . "') LIMIT 1");

		if ($query->num_rows){
			$this->cache->set($this->getKey('manufacturers', $name), $query->row['manufacturer_id']);			
			return $query->row['manufacturer_id'];
		}
		
		return false;
	}

	public function getAttributeInfo($attribute_id){
		if ($this->cache->get($this->getKey('attributes_info', $attribute_id), true)){
			return $this->cache->get($this->getKey('attributes_info', $attribute_id), true);
		}

		$query = $this->db->ncquery("SELECT * FROM attribute WHERE attribute_id = '" . (int)$attribute_id . "' LIMIT 1");

		if ($query->num_rows){
			$this->cache->set($this->getKey('attributes_info', $attribute_id), $query->row);
			return $query->row;
		}
		
		return false;
	}

	public function getAttribute($name){
		if ($this->cache->get($this->getKey('attributes', $name), true)){
			return $this->cache->get($this->getKey('attributes', $name), true);
		}

		$query = $this->db->ncquery("SELECT attribute_id FROM attribute_description WHERE language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "' AND name LIKE ('" . $this->db->escape($name) . "') LIMIT 1");

		if ($query->num_rows){
			$this->cache->set($this->getKey('attributes', $name), $query->row['attribute_id']);
			return $query->row['attribute_id'];
		}
		
		return false;
	}

	public function getCategory($name){
		if ($this->cache->get($this->getKey('categories', $name), true)){			
			return $this->cache->get($this->getKey('categories', $name), true);
		}

		$query = $this->db->ncquery("SELECT cd.category_id FROM category_description cd LEFT JOIN category c ON (cd.category_id = c.category_id) WHERE c.amazon_final_category = 1 AND cd.language_id = '" . $this->config->get('config_rainforest_source_language_id') . "' AND cd.name LIKE ('" . $this->db->escape($name) . "') LIMIT 1");

		if ($query->num_rows){
			$this->cache->set($this->getKey('categories', $name), $query->row['category_id']);
			return $query->row['category_id'];
		}
		
		return false;
	}


}