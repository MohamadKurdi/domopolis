<?php

const IS_ADMIN = true;
if (!defined('IS_HTTPS')){
	define('IS_HTTPS', true);
}

const FAVICON = HTTP_CATALOG . '/icon/favicon-32x32.png';
const FILE_STYLE = 'stylesheet.css';
const FILE_STYLE2 = 'stylesheet.css';
const FILE_LOGO = 'logo.png';
const ICON_DIRECTORY = '';

if (!defined('IMAGE_QUALITY')){
	define('IMAGE_QUALITY', 80);
}

const GEOIP_LIB_PATH = '/var/lib/GeoIP/GeoLite2-City.mmdb';

const STOCK_CATEGORY = 6474;
const PRESENT_CATEGORY = 6475;
const BIRTHDAY_DISCOUNT_CATEGORY = 8308;
const GENERAL_DISCOUNT_CATEGORY = 6614;
const GENERAL_MARKDOWN_CATEGORY = 8422;

const STOCK = 6474;
const PRESENT = 6475;
const PRESENT_UA = 8307;

const YANDEX_MARKET_CUSTOMER_ID = 1533453;

const AUTH_UNAME_COOKIE = 'random_name_cookie';
const AUTH_PASSWD_COOKIE = 'random_passwd_cookie';
const AUTH_PASSWDSALT_COOKIE = 'random_salt_cookie';

const MYSQL_NOW_DATE_FORMAT = 'Y-m-d H:i:00';
const DEFAULT_SORT = 'p.quantity DESC, p.date_modified';

const ALERT_QUEUE = 'alert_queue';