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

	public function createCacheQueryString($method, $setting = [], $options = []){
		$md5 = md5(serialize($setting) . serialize($options));

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
			$md5;
	}
}