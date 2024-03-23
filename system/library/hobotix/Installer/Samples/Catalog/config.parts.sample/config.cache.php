<?php
const CACHE_DRIVER = 'Empty';

const BCACHE_TTL = 36000;
const DB_CACHED_EXPIRE = 36000;

const REDIS_DATABASE = '6';
const REDIS_SOCKET = '/var/run/redis/redis.sock';
const REDIS_HOST = '127.0.0.1';
const REDIS_PORT = '6379';

const MEMCACHE_HOSTNAME = 'localhost';
const MEMCACHE_PORT = '11211';
const CACHE_NAMESPACE = 'nmspc:';

const ELASTICSEARCH_HOSTPORT = 'http://127.0.0.1:9200';