<?php
final class Cache { 	
	private $expire 		= 3600;
	private $prefix 		= '';
	private $driver 		= 'Redis';
	
	public function __construct($driver) {
		if (!$driver){
			$driver = $this->driver;
		}	

		$file = dirname(__FILE__) . '/cache/' . $driver . '.php';

		if (file_exists($file)) {
			require_once($file);

			$class = "\hobotix\Cache\Cache" . $driver;

			$this->adaptor = new $class();
		} else {
			exit('Error: Could not load cache driver type ' . $driver . '!');
		}
	} 	
	
	public function exists($key, $explicit = false) {
		if (defined('ADMIN_SESSION_DETECTED') && ADMIN_SESSION_DETECTED && defined('BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED') && BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED && !$explicit){	
			return false;
		}

		if (defined('IS_DEBUG') && IS_DEBUG  && !$explicit){
			return false;
		}
		
		return $this->adaptor->exists($key);
	}
	
	
	public function get($key, $explicit = false) {
		if (defined('ADMIN_SESSION_DETECTED') && ADMIN_SESSION_DETECTED && defined('BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED') && BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED && !$explicit){
			return false;
		}

		if (defined('IS_DEBUG') && IS_DEBUG && !$explicit){
			return false;
		}
		
		return $this->adaptor->get($key);
	}
	
	public function set($key, $value, $ttl = DB_CACHED_EXPIRE, $explicit = false) {
		if (defined('ADMIN_SESSION_DETECTED') && ADMIN_SESSION_DETECTED && defined('BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED') && BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED && !$redis_explicit){
			return false;
		}

		if (defined('IS_DEBUG') && IS_DEBUG){
			return false;
		}

		return $this->adaptor->set($key, $value, $explicit);
	}

	public function addtolist($list, $value, $ttl = DB_CACHED_EXPIRE){
		return $this->adaptor->addtolist($list, $value, $ttl = DB_CACHED_EXPIRE);	
	}
	
	public function rpoplist($list){		
		return $this->adaptor->rpoplist($list);	
	}		
		
	public function delete($key) {
		return $this->adaptor->delete($key);
	}
	
	public function flush() {
		return $this->adaptor->flush($key);
	}	
}