<?php

define ('MFPCACHE_DIR', 'cache_mfp/');
define ('BCACHE_DIR', 'bcache/');
define ('PAGECACHE_DIR', 'pagecache/');
define ('BCACHE_TTL', 36000);
define ('DB_CACHED_EXPIRE', 36000);

define('MEMCACHE_HOSTNAME', 'localhost');
define('MEMCACHE_PORT', '11211');

define('CACHE_NAMESPACE', 'cache:');

define('CACHE_DRIVER', 'Redis');
define('REDIS_DATABASE', '6');
define('REDIS_SOCKET', '/var/run/redis/redis.sock');
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', '6379');