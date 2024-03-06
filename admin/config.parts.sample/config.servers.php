<?php
define('SITE_NAMESPACE', 'MY_NAMESPACE');
define('HTTP_DOMAIN', 'my.domain');

define('HTTP_CATALOG', 'https://' . HTTP_DOMAIN . '/');
define('HTTPS_CATALOG', HTTP_CATALOG);
define('HTTP_SERVER', HTTP_CATALOG . 'admin/');
define('HTTPS_SERVER', HTTP_CATALOG . 'admin/');
define('HTTP_IMAGE',  HTTP_CATALOG . 'image/');
define('HTTPS_IMAGE', HTTP_CATALOG . 'image/');
define('HTTP_EXPORT', HTTP_CATALOG . 'export/');

define('HTTP_FILE_SERVER_IMAGE', 	HTTP_IMAGE);
define('HTTP_IMG_SERVER', 			'https://img.' . HTTP_DOMAIN . '/');
define('HTTP_IMG_SERVERS', 			'https://img{N}.' . HTTP_DOMAIN . '/');
define('HTTP_IMG_SERVERS_COUNT', 	4);

define('HTTPS_FILE_SERVER_IMAGE', 	HTTP_FILE_SERVER_IMAGE);
define('HTTPS_IMG_SERVER', 			HTTP_IMG_SERVER);
define('HTTPS_IMG_SERVERS', 		HTTP_IMG_SERVERS);
define('HTTPS_IMG_SERVERS_COUNT', 	HTTP_IMG_SERVERS_COUNT);

define('HTTPS_STATIC_SUBDOMAIN', 	'https://static.' . HTTP_DOMAIN . '/');