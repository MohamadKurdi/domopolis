<?

namespace  hobotix\Cache;

class CacheEmpty{
	public function __construct(){		
	}

	public function exists($key){
		return false;
	}

	public function get($key){
		return false;
	}

	public function set($key, $value, $explicit = false) {
		return false;
	}

	public function addtolist($list, $value, $ttl = DB_CACHED_EXPIRE){		
		return false;		
	}

	public function rpoplist($list){		
		return false;
	}	

	public function delete($key) {
		return false;
	}

	public function flush($key = null) {
		return false;
	}
}
