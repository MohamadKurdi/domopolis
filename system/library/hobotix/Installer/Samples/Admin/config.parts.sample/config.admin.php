<?php

define('IS_ADMIN', true);
if (!defined('IS_HTTPS')){
	define('IS_HTTPS', true);
}

define('FAVICON', HTTP_CATALOG . '/icon/favicon-32x32.png');
define('FILE_STYLE', 'stylesheet.css');
define('FILE_STYLE2', 'stylesheet.css');
define('FILE_LOGO', 'logo.png');
define('ICON_DIRECTORY', '');

if (!defined('IMAGE_QUALITY')){
	define('IMAGE_QUALITY', 80);
}

define('GEOIP_LIB_PATH', '/var/lib/GeoIP/GeoLite2-City.mmdb');

define('STOCK_CATEGORY', 6474);
define('PRESENT_CATEGORY', 6475);
define('BIRTHDAY_DISCOUNT_CATEGORY', 8308);
define('GENERAL_DISCOUNT_CATEGORY', 6614);
define('GENERAL_MARKDOWN_CATEGORY', 8422);

define('STOCK', 6474);	
define('PRESENT', 6475);
define('PRESENT_UA', 8307);

define('YANDEX_MARKET_CUSTOMER_ID', 1533453);

define('AUTH_UNAME_COOKIE', 'random_name_cookie');
define('AUTH_PASSWD_COOKIE', 'random_passwd_cookie');
define('AUTH_PASSWDSALT_COOKIE', 'random_salt_cookie');

define('MYSQL_NOW_DATE_FORMAT', 'Y-m-d H:i:00');
define('DEFAULT_SORT', 'p.quantity DESC, p.date_modified');

define('ALERT_QUEUE', 'alert_queue');