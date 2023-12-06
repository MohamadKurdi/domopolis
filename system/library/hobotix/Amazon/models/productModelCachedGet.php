<?php

namespace hobotix\Amazon;

class productModelCachedGet extends hoboModel{

	private $cacheprefix = 'amazon.rainforest';

	private function getKey($scope, $key = ''){		
		if ($key){
			return $this->cacheprefix . '.' . $scope . '.' . md5($key);
		} else {
			return $this->cacheprefix . '.' . $scope;
		}
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

	public function checkIfTempCategoryExists($name){
		if ($this->cache->get($this->getKey('categories.temp', $name), true)){			
			return $this->cache->get($this->getKey('categories.temp', $name), true);
		}

		$query = $this->db->ncquery("SELECT cd.category_id FROM category_description cd LEFT JOIN category c ON (cd.category_id = c.category_id) WHERE cd.language_id = '" . $this->config->get('config_rainforest_source_language_id') . "' AND cd.name LIKE ('" . $this->db->escape($name) . "') LIMIT 1");

		if ($query->num_rows){
			$this->cache->set($this->getKey('categories.temp', $name), $query->row['category_id']);
			return $query->row['category_id'];
		}
		
		return false;
	}


	public function getAmazonCategoryID($name, $path, $amazon_category_id){
		$query = $this->db->ncquery("SELECT * FROM `" . \hobotix\RainforestAmazon::categoryModeTables[$this->config->get('config_rainforest_category_model')] . "` WHERE 
			full_name LIKE '" . $this->db->escape($path) . "' OR full_name LIKE '" . $this->db->escape(htmlspecialchars($path)) . "'
			OR (category_id LIKE '" . $this->db->escape($amazon_category_id) . "' AND name LIKE ('" . $this->db->escape($name) . "'))");

		if ($query->num_rows){
			return $query->row['category_id'];
		}

		return false;
	}

	public function getCategoryExtended($name, $path, $amazon_category_id){
		$query = $this->db->ncquery("SELECT * FROM category WHERE amazon_category_name LIKE '" . $this->db->escape($path) . "' OR amazon_category_name LIKE '" . $this->db->escape(htmlspecialchars($path)) . "'");
		if ($query->num_rows){
			echoLine('[getCategoryExtended] Found category by direct name: ' . $path, 's');
			return $query->row['category_id'];
		}

		$check_amazon_category_id = $this->getAmazonCategoryID($name, $path, $amazon_category_id);
		if ($check_amazon_category_id){
			echoLine('[getCategoryExtended] Validated amazon_category_id by ID + Name: ' . $check_amazon_category_id, 's');
			$query = $this->db->ncquery("SELECT * FROM category WHERE amazon_category_id LIKE '" . $this->db->escape($check_amazon_category_id) . "'");		
			if ($query->num_rows){
				echoLine('[getCategoryExtended] Found category by validated amazon_category_id: ' . $check_amazon_category_id, 's');
				return $query->row['category_id'];
			}
		}

		$query = $this->db->ncquery("SELECT * FROM category WHERE amazon_category_id LIKE '" . $this->db->escape($amazon_category_id) . "'");		
		if ($query->num_rows){
			echoLine('[getCategoryExtended] Found category by amazon_category_id: ' . $amazon_category_id, 's');
			return $query->row['category_id'];
		}

		return false;
	}

	public function getCategory($name, $path = '', $amazon_category_id = ''){
		if ($this->cache->get($this->getKey('categories', $name . $path . $amazon_category_id), true)){			
			return $this->cache->get($this->getKey('categories', $name . $path . $amazon_category_id), true);
		}

		$category_id = false;

		if (!$category_id){
			$category_id = $this->getCategoryExtended($name, $path, $amazon_category_id);
		}

		if (!$category_id){
			$query = $this->db->ncquery("SELECT cd.category_id FROM category_description cd 
				LEFT JOIN category c ON (cd.category_id = c.category_id) WHERE 
				c.amazon_final_category = 1 
				AND cd.language_id = '" . $this->config->get('config_rainforest_source_language_id') . "' 
				AND cd.name LIKE ('" . $this->db->escape($name) . "') LIMIT 1");

			if ($query->num_rows){
				$category_id = $query->row['category_id'];
			}
		}
		
		if ($category_id){
			$this->cache->set($this->getKey('categories', $name . $path . $amazon_category_id), $category_id);			
		}
		
		return $category_id;
	}
}