<?php

define('SITE_NAMESPACE', 	'SITE_NAMESPACE');
define('THIS_IS_CATALOG', 	TRUE);
define('HTTP_DOMAIN', 		'store.domain');
define('HTTP_SERVER', 		'https://'. HTTP_DOMAIN .'/');

define('HTTP_CATALOG', 				HTTP_SERVER);
define('HTTP_FILE_SERVER', 			HTTP_SERVER);
define('HTTP_ADMIN', 				HTTP_SERVER . 'admin/');
define('HTTP_IMAGE', 				HTTP_SERVER . 'image/');
define('HTTP_FILE_SERVER_IMAGE', 	HTTP_IMAGE);
define('HTTP_IMG_SERVER', 			'https://img.' . HTTP_DOMAIN . '/');
define('HTTP_IMG_SERVERS', 			'https://img{N}.' . HTTP_DOMAIN . '/');
define('HTTP_IMG_SERVERS_COUNT', 	4);

define('HTTPS_SERVER', 				HTTP_SERVER);
define('HTTPS_IMAGE', 				HTTP_IMAGE);
define('HTTPS_CATALOG', 			HTTP_CATALOG);
define('HTTPS_FILE_SERVER', 		HTTP_FILE_SERVER);
define('HTTPS_ADMIN', 				HTTP_ADMIN);
define('HTTPS_FILE_SERVER_IMAGE', 	HTTP_FILE_SERVER_IMAGE);
define('HTTPS_IMG_SERVER', 			HTTP_IMG_SERVER);
define('HTTPS_IMG_SERVERS', 		HTTP_IMG_SERVERS);
define('HTTPS_IMG_SERVERS_COUNT', 	HTTP_IMG_SERVERS_COUNT);


define('HTTPS_STATIC_SUBDOMAIN', 	'https://'. HTTP_DOMAIN . '/');


