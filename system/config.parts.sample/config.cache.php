<?


//НАСТРОЙКИ КЭШИРОВАНИЯ
//Выбор модуля кэширования : memcached, xcache, redis
define('CACHE_DRIVER', 'redis');

//static module cache
define ('BCACHE_TTL', 36000);
define ('DB_CACHED_EXPIRE', 36000);

//MEMCACHE
define('MEMCACHE_HOSTNAME', 'localhost');
define('MEMCACHE_PORT', '11211');

//XCACHE
define('CACHE_NAMESPACE', 'nmspc:');

//REDIS
define('REDIS_DATABASE', '6');
define('REDIS_SOCKET', '/var/run/redis/redis.sock');
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', '6379');