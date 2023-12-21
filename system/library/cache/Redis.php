<?

namespace  hobotix\Cache;

class CacheRedis{
	private $redis = null;
	private $ttl   = 3600;

	public function __construct(){
		$this->redis = new \Redis();
		$this->ttl   = DB_CACHED_EXPIRE;

		if (@$this->redis->pconnect(REDIS_SOCKET)){
			$this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
			$this->redis->select(REDIS_DATABASE);				
		} elseif(@$this->redis->pconnect(REDIS_HOST, REDIS_PORT)) {					
			$this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
			$this->redis->select(REDIS_DATABASE);
		}				
	}

	public function exists($key){
		return $this->redis->exists($key);
	}

	public function get($key){
		return $this->redis->get($key);
	}

	public function set($key, $value, $explicit = false) {
		if ($explicit){
			return $this->redis->set($key, $value);
		} else {
			return $this->redis->set($key, $value, ['ex' => $this->ttl]);
		}
	}

	public function addtolist($list, $value, $ttl = DB_CACHED_EXPIRE){		
		if (!$this->redis->lPushx($list, $value)){
			$this->redis->lPush($list, $value);
			$this->redis->expire($list, $ttl);
		}		
	}

	public function rpoplist($list){		
		return $this->redis->rPop($list);
	}	

	public function delete($key) {		
	}

	public function flush() {
		$this->redis->select(REDIS_DATABASE);
		return $this->redis->flushDb();
	}
}
