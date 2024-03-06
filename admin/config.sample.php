<?php

require_once(dirname(__FILE__) . '/config.parts/config.servers.php');
require_once(dirname(__FILE__) . '/config.parts/config.path.php');
require_once(dirname(__FILE__) . '/config.parts/config.admin.php');
require_once(dirname(__FILE__) . '/config.parts/config.sip.php');
require_once(dirname(__FILE__) . '/config.parts/config.cache.php');
require_once(dirname(__FILE__) . '/config.parts/config.sessiondb.php');


define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', '/run/mysqld/mysqld.sock');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_DATABASE', '');
define('DB_PREFIX', '');

