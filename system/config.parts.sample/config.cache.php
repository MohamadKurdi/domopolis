<?php
define('CACHE_DRIVER', 'Empty');

define ('BCACHE_TTL', 36000);
define ('DB_CACHED_EXPIRE', 36000);

define('REDIS_DATABASE', '6');
define('REDIS_SOCKET', '/var/run/redis/redis.sock');
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', '6379');

define('MEMCACHE_HOSTNAME', 'localhost');
define('MEMCACHE_PORT', '11211');
define('CACHE_NAMESPACE', 'nmspc:');