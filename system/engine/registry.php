<?php
final class Registry {
	private $data = array();

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function has($key) {
		return isset($this->data[$key]);
	}

	public function mainDBIsUsed(){
		if (!$this->has('current_db')){
			return true;
		}

		if ($this->has('current_db') && $this->get('current_db') == 'dbmain'){
			return true;
		}

		if ($this->has('current_db') && $this->get('current_db') == 'dbcs'){
			return false;
		}

		return true;
	}	

	public function hasDBCS(){
		return ($this->has('dbcs') && $this->get('dbcs'));
	}

	public function setSyncDB(){
		if ($this->has('dbcs') && $this->get('dbcs')){
			$this->set('dbmain', $this->get('db'));
			$this->set('db', $this->get('dbcs'));
			$this->set('current_db', 'dbcs');
		}

		return $this;
	}

	public function setMainDB(){
		if ($this->has('dmain') && $this->get('dmain')){
			$this->set('db', $this->get('dmain'));	
			$this->set('current_db', 'dbmain');
		}	

		return $this;
	}

	public function createCacheQueryString($method, $setting = [], $options = []){
		return  
			$method . 
			$this->get('config')->get('config_store_id') . 
			$this->get('config')->get('config_language_id') . 
			$this->get('customer_group_id') . 
			$this->get('currency')->getId() . 
			(int)ADD_METRICS_TO_FRONT . 
			(int)WEBPACCEPTABLE . 
			(int)AVIFACCEPTABLE . 
			(int)IS_MOBILE_SESSION . 
			(int)IS_TABLET_SESSION . 
			md5(serialize($setting) . serialize($options));
	}

	public function createCacheQueryStringData($method, $setting = [], $options = []){
		return  
			$method . 
			$this->get('config')->get('config_store_id') . 
			$this->get('config')->get('config_language_id') . 
			$this->get('customer_group_id') . 
			$this->get('currency')->getId() .
			md5(serialize($setting) . serialize($options));
	}
}