<?php

namespace hobotix\Supplier\Model;

class modelCachedGet extends hoboModel{

	private $cacheprefix = 'suppliers.data';

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

		$query = $this->db->ncquery("SELECT attribute_id FROM attribute_description WHERE LOWER(name) LIKE ('" . $this->db->escape(mb_strtolower($name)) . "') LIMIT 1");

		if ($query->num_rows){
			$this->cache->set($this->getKey('attributes', $name), $query->row['attribute_id']);
			return $query->row['attribute_id'];
		}
		
		return false;
	}
}