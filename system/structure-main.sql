-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Янв 07 2023 г., 20:40
-- Версия сервера: 10.6.7-MariaDB-2ubuntu1.1-log
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dp_domopolis`
--

-- --------------------------------------------------------

--
-- Структура таблицы `actions`
--

DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions` (
  `actions_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb3_bin DEFAULT NULL,
  `image_to_cat` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `image_size` int(11) NOT NULL DEFAULT 0,
  `date_start` int(11) NOT NULL DEFAULT 0,
  `date_end` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `fancybox` int(11) NOT NULL DEFAULT 0,
  `product_related` text COLLATE utf8mb3_bin DEFAULT NULL,
  `category_related_id` int(11) NOT NULL,
  `category_related_no_intersections` tinyint(1) NOT NULL,
  `category_related_limit_products` int(11) NOT NULL,
  `ao_group` varchar(100) COLLATE utf8mb3_bin NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `deletenotinstock` tinyint(1) NOT NULL DEFAULT 0,
  `only_in_stock` int(11) NOT NULL DEFAULT 0,
  `display_all_active` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `actions_description`
--

DROP TABLE IF EXISTS `actions_description`;
CREATE TABLE `actions_description` (
  `actions_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `meta_keywords` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `meta_description` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `h1` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `caption` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `anonnce` text COLLATE utf8mb3_bin NOT NULL,
  `description` text COLLATE utf8mb3_bin NOT NULL,
  `content` text COLLATE utf8mb3_bin NOT NULL,
  `image_overload` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `image_to_cat_overload` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `label` varchar(64) COLLATE utf8mb3_bin NOT NULL,
  `label_background` varchar(32) COLLATE utf8mb3_bin NOT NULL,
  `label_color` varchar(32) COLLATE utf8mb3_bin NOT NULL,
  `label_text` text COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `actions_to_category`
--

DROP TABLE IF EXISTS `actions_to_category`;
CREATE TABLE `actions_to_category` (
  `actions_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `actions_to_category_in`
--

DROP TABLE IF EXISTS `actions_to_category_in`;
CREATE TABLE `actions_to_category_in` (
  `actions_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `actions_to_layout`
--

DROP TABLE IF EXISTS `actions_to_layout`;
CREATE TABLE `actions_to_layout` (
  `actions_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `actions_to_product`
--

DROP TABLE IF EXISTS `actions_to_product`;
CREATE TABLE `actions_to_product` (
  `actions_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `actions_to_store`
--

DROP TABLE IF EXISTS `actions_to_store`;
CREATE TABLE `actions_to_store` (
  `actions_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `actiontemplate`
--

DROP TABLE IF EXISTS `actiontemplate`;
CREATE TABLE `actiontemplate` (
  `actiontemplate_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `actiontemplate_description`
--

DROP TABLE IF EXISTS `actiontemplate_description`;
CREATE TABLE `actiontemplate_description` (
  `actiontemplate_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` longtext NOT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `company` varchar(32) NOT NULL,
  `company_id` varchar(32) NOT NULL,
  `tax_id` varchar(32) NOT NULL,
  `address_1` varchar(500) NOT NULL,
  `address_2` varchar(500) NOT NULL,
  `city` varchar(128) NOT NULL,
  `novaposhta_city_guid` varchar(32) NOT NULL,
  `cdek_city_guid` varchar(16) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 0,
  `zone_id` int(11) NOT NULL DEFAULT 0,
  `passport_serie` varchar(50) NOT NULL,
  `passport_given` text NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `for_print` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `address_simple_fields`
--

DROP TABLE IF EXISTS `address_simple_fields`;
CREATE TABLE `address_simple_fields` (
  `address_id` int(11) NOT NULL,
  `metadata` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `adminlog`
--

DROP TABLE IF EXISTS `adminlog`;
CREATE TABLE `adminlog` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `action` varchar(50) NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  `url` varchar(200) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `advanced_coupon`
--

DROP TABLE IF EXISTS `advanced_coupon`;
CREATE TABLE `advanced_coupon` (
  `advanced_coupon_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL,
  `options` text NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `status` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `advanced_coupon_history`
--

DROP TABLE IF EXISTS `advanced_coupon_history`;
CREATE TABLE `advanced_coupon_history` (
  `advanced_coupon_history_id` int(11) NOT NULL,
  `advanced_coupon_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `affiliate`
--

DROP TABLE IF EXISTS `affiliate`;
CREATE TABLE `affiliate` (
  `affiliate_id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `fax` varchar(32) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `company` varchar(32) NOT NULL,
  `website` varchar(255) NOT NULL,
  `address_1` varchar(128) NOT NULL,
  `address_2` varchar(128) NOT NULL,
  `city` varchar(128) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `code` varchar(64) NOT NULL,
  `commission` decimal(4,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(64) NOT NULL,
  `payment` varchar(64) NOT NULL,
  `cheque` varchar(100) NOT NULL,
  `paypal` varchar(64) NOT NULL,
  `bank_name` varchar(64) NOT NULL,
  `bank_branch_number` varchar(64) NOT NULL,
  `bank_swift_code` varchar(64) NOT NULL,
  `bank_account_name` varchar(64) NOT NULL,
  `bank_account_number` varchar(64) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `coupon` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `qiwi` varchar(100) NOT NULL DEFAULT '',
  `card` varchar(100) NOT NULL DEFAULT '',
  `yandex` varchar(100) NOT NULL DEFAULT '',
  `webmoney` varchar(100) NOT NULL DEFAULT '',
  `request_payment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `webmoneyWMZ` varchar(100) NOT NULL DEFAULT '',
  `webmoneyWMU` varchar(100) NOT NULL DEFAULT '',
  `webmoneyWME` varchar(100) NOT NULL DEFAULT '',
  `webmoneyWMY` varchar(100) NOT NULL DEFAULT '',
  `webmoneyWMB` varchar(100) NOT NULL DEFAULT '',
  `webmoneyWMG` varchar(100) NOT NULL DEFAULT '',
  `AlertPay` varchar(100) NOT NULL DEFAULT '',
  `Moneybookers` varchar(100) NOT NULL DEFAULT '',
  `LIQPAY` varchar(100) NOT NULL DEFAULT '',
  `SagePay` varchar(100) NOT NULL DEFAULT '',
  `twoCheckout` varchar(100) NOT NULL DEFAULT '',
  `GoogleWallet` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `affiliate_statistics`
--

DROP TABLE IF EXISTS `affiliate_statistics`;
CREATE TABLE `affiliate_statistics` (
  `id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `count_transitions` int(11) NOT NULL DEFAULT 0,
  `affiliate_ip_name` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `affiliate_transaction`
--

DROP TABLE IF EXISTS `affiliate_transaction`;
CREATE TABLE `affiliate_transaction` (
  `affiliate_transaction_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL,
  `album_type` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `last_modified` datetime NOT NULL,
  `album_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `alertlog`
--

DROP TABLE IF EXISTS `alertlog`;
CREATE TABLE `alertlog` (
  `alertlog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alert_type` varchar(30) NOT NULL,
  `alert_text` varchar(500) NOT NULL,
  `entity_type` varchar(20) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `alsoviewed`
--

DROP TABLE IF EXISTS `alsoviewed`;
CREATE TABLE `alsoviewed` (
  `id` bigint(20) NOT NULL,
  `low` int(11) DEFAULT 0,
  `high` int(11) DEFAULT 0,
  `number` int(11) DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `amazon_orders`
--

DROP TABLE IF EXISTS `amazon_orders`;
CREATE TABLE `amazon_orders` (
  `order_id` int(11) NOT NULL,
  `amazon_id` varchar(255) NOT NULL,
  `date_added` date NOT NULL,
  `total` decimal(15,4) NOT NULL,
  `grand_total` decimal(15,4) NOT NULL,
  `gift_card` decimal(15,4) NOT NULL,
  `cancelled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `amazon_orders_blobs`
--

DROP TABLE IF EXISTS `amazon_orders_blobs`;
CREATE TABLE `amazon_orders_blobs` (
  `amazon_id` varchar(30) NOT NULL,
  `amazon_blob` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `amazon_orders_products`
--

DROP TABLE IF EXISTS `amazon_orders_products`;
CREATE TABLE `amazon_orders_products` (
  `order_product_id` int(11) NOT NULL,
  `amazon_id` varchar(255) NOT NULL,
  `asin` varchar(30) NOT NULL,
  `name` varchar(400) NOT NULL,
  `image` varchar(500) NOT NULL,
  `href` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `product_id` int(11) NOT NULL,
  `delivery_num` int(11) NOT NULL,
  `delivery_status` varchar(255) NOT NULL,
  `delivery_status_ru` varchar(255) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `is_problem` tinyint(1) NOT NULL,
  `is_return` tinyint(1) NOT NULL,
  `is_dispatched` tinyint(1) NOT NULL,
  `is_expected` tinyint(1) NOT NULL,
  `date_expected` date NOT NULL,
  `is_delivered` tinyint(1) NOT NULL,
  `date_delivered` date NOT NULL,
  `is_arriving` int(11) NOT NULL,
  `date_arriving_exact` date NOT NULL,
  `date_arriving_from` date NOT NULL,
  `date_arriving_to` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `amzn_add_queue`
--

DROP TABLE IF EXISTS `amzn_add_queue`;
CREATE TABLE `amzn_add_queue` (
  `asin` varchar(32) NOT NULL,
  `date_added` datetime NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `amzn_product_queue`
--

DROP TABLE IF EXISTS `amzn_product_queue`;
CREATE TABLE `amzn_product_queue` (
  `asin` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `apri`
--

DROP TABLE IF EXISTS `apri`;
CREATE TABLE `apri` (
  `order_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `apri_unsubscribe`
--

DROP TABLE IF EXISTS `apri_unsubscribe`;
CREATE TABLE `apri_unsubscribe` (
  `md5_email` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute`
--

DROP TABLE IF EXISTS `attribute`;
CREATE TABLE `attribute` (
  `attribute_id` int(11) NOT NULL,
  `attribute_group_id` int(11) NOT NULL,
  `dimension_type` enum('length','width','height','dimensions','weight','all') DEFAULT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attributes_category`
--

DROP TABLE IF EXISTS `attributes_category`;
CREATE TABLE `attributes_category` (
  `attribute_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attributes_similar_category`
--

DROP TABLE IF EXISTS `attributes_similar_category`;
CREATE TABLE `attributes_similar_category` (
  `attribute_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_description`
--

DROP TABLE IF EXISTS `attribute_description`;
CREATE TABLE `attribute_description` (
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_group`
--

DROP TABLE IF EXISTS `attribute_group`;
CREATE TABLE `attribute_group` (
  `attribute_group_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_group_description`
--

DROP TABLE IF EXISTS `attribute_group_description`;
CREATE TABLE `attribute_group_description` (
  `attribute_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_group_tooltip`
--

DROP TABLE IF EXISTS `attribute_group_tooltip`;
CREATE TABLE `attribute_group_tooltip` (
  `attribute_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tooltip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_tooltip`
--

DROP TABLE IF EXISTS `attribute_tooltip`;
CREATE TABLE `attribute_tooltip` (
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tooltip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `attribute_value_image`
--

DROP TABLE IF EXISTS `attribute_value_image`;
CREATE TABLE `attribute_value_image` (
  `attribute_value_image` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_value` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `information_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `banner`
--

DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `banner_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `banner_image`
--

DROP TABLE IF EXISTS `banner_image`;
CREATE TABLE `banner_image` (
  `banner_image_id` int(11) NOT NULL,
  `banner_id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_sm` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `banner_image_description`
--

DROP TABLE IF EXISTS `banner_image_description`;
CREATE TABLE `banner_image_description` (
  `banner_image_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `banner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `block_text` text NOT NULL,
  `button_text` varchar(255) NOT NULL,
  `overload_image` varchar(255) NOT NULL,
  `overload_image_sm` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `callback`
--

DROP TABLE IF EXISTS `callback`;
CREATE TABLE `callback` (
  `call_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `telephone` varchar(30) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `comment_buyer` text NOT NULL,
  `email_buyer` text NOT NULL,
  `manager_id` int(11) NOT NULL,
  `is_missed` tinyint(1) NOT NULL DEFAULT 0,
  `is_cheaper` tinyint(1) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sip_queue` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `menu_image` tinyint(1) NOT NULL DEFAULT 1,
  `not_update_image` tinyint(1) NOT NULL DEFAULT 0,
  `parent_id` int(11) DEFAULT 0,
  `virtual_parent_id` int(11) NOT NULL DEFAULT -1,
  `top` tinyint(1) NOT NULL,
  `column` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `menu_banner` varchar(255) DEFAULT '',
  `banner_link` varchar(255) NOT NULL DEFAULT '',
  `menu_icon` text DEFAULT NULL,
  `product_count` int(11) NOT NULL,
  `tnved` varchar(255) NOT NULL,
  `overprice` varchar(255) NOT NULL,
  `google_category_id` int(11) NOT NULL,
  `separate_feeds` tinyint(1) NOT NULL DEFAULT 0,
  `no_general_feed` tinyint(1) NOT NULL DEFAULT 0,
  `deletenotinstock` tinyint(1) NOT NULL DEFAULT 0,
  `intersections` tinyint(1) NOT NULL DEFAULT 0,
  `exclude_from_intersections` tinyint(1) NOT NULL DEFAULT 0,
  `default_weight` decimal(15,4) NOT NULL,
  `default_weight_class_id` int(11) NOT NULL,
  `default_length` decimal(15,4) NOT NULL,
  `default_width` decimal(15,4) NOT NULL,
  `default_height` decimal(15,4) NOT NULL,
  `default_length_class_id` int(11) NOT NULL,
  `priceva_enable` tinyint(1) NOT NULL DEFAULT 0,
  `submenu_in_children` tinyint(1) NOT NULL DEFAULT 0,
  `amazon_sync_enable` tinyint(1) NOT NULL,
  `amazon_category_id` varchar(255) NOT NULL,
  `amazon_category_name` varchar(1024) NOT NULL,
  `amazon_category_link` varchar(1024) DEFAULT NULL,
  `amazon_parent_category_id` varchar(256) NOT NULL,
  `amazon_parent_category_name` varchar(1024) DEFAULT NULL,
  `amazon_final_category` tinyint(1) DEFAULT 0,
  `amazon_can_get_full` tinyint(1) NOT NULL DEFAULT 0,
  `amazon_last_sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `amazon_overprice_rules` varchar(512) NOT NULL,
  `yandex_category_name` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_amazon_bestseller_tree`
--

DROP TABLE IF EXISTS `category_amazon_bestseller_tree`;
CREATE TABLE `category_amazon_bestseller_tree` (
  `category_id` varchar(255) NOT NULL,
  `parent_id` varchar(255) NOT NULL,
  `final_category` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(512) DEFAULT NULL,
  `full_name` varchar(1024) DEFAULT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_amazon_tree`
--

DROP TABLE IF EXISTS `category_amazon_tree`;
CREATE TABLE `category_amazon_tree` (
  `category_id` varchar(255) NOT NULL,
  `parent_id` varchar(255) NOT NULL,
  `final_category` tinyint(1) NOT NULL,
  `name` varchar(512) NOT NULL,
  `full_name` varchar(1024) NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_description`
--

DROP TABLE IF EXISTS `category_description`;
CREATE TABLE `category_description` (
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alternate_name` longtext NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `all_prefix` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_h1` varchar(255) NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0,
  `tag` text DEFAULT NULL,
  `alt_image` text DEFAULT NULL,
  `title_image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_filter`
--

DROP TABLE IF EXISTS `category_filter`;
CREATE TABLE `category_filter` (
  `category_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_menu_content`
--

DROP TABLE IF EXISTS `category_menu_content`;
CREATE TABLE `category_menu_content` (
  `category_menu_content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` text NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `href` varchar(1024) NOT NULL,
  `standalone` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_path`
--

DROP TABLE IF EXISTS `category_path`;
CREATE TABLE `category_path` (
  `category_id` int(11) NOT NULL,
  `path_id` int(11) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_product_count`
--

DROP TABLE IF EXISTS `category_product_count`;
CREATE TABLE `category_product_count` (
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `product_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_psm_template`
--

DROP TABLE IF EXISTS `category_psm_template`;
CREATE TABLE `category_psm_template` (
  `category_psm_template_id` int(11) NOT NULL,
  `category_entity` varchar(64) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `l_code` varchar(5) DEFAULT NULL,
  `template` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_related`
--

DROP TABLE IF EXISTS `category_related`;
CREATE TABLE `category_related` (
  `category_id` int(11) NOT NULL,
  `related_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_review`
--

DROP TABLE IF EXISTS `category_review`;
CREATE TABLE `category_review` (
  `categoryreview_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `author` varchar(64) NOT NULL,
  `text` text NOT NULL,
  `rating` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_to_actions`
--

DROP TABLE IF EXISTS `category_to_actions`;
CREATE TABLE `category_to_actions` (
  `category_id` int(11) NOT NULL,
  `actions_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_to_layout`
--

DROP TABLE IF EXISTS `category_to_layout`;
CREATE TABLE `category_to_layout` (
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_to_store`
--

DROP TABLE IF EXISTS `category_to_store`;
CREATE TABLE `category_to_store` (
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `category_yam_tree`
--

DROP TABLE IF EXISTS `category_yam_tree`;
CREATE TABLE `category_yam_tree` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `final_category` tinyint(1) NOT NULL,
  `name` varchar(512) NOT NULL,
  `full_name` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `cdek_cities`
--

DROP TABLE IF EXISTS `cdek_cities`;
CREATE TABLE `cdek_cities` (
  `city_id` int(11) NOT NULL,
  `city_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` int(11) NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fias_guid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kladr_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_code` int(11) NOT NULL,
  `fias_region_guid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kladr_region_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_codes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_zone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_limit` decimal(14,2) NOT NULL,
  `WarehouseCount` int(11) NOT NULL,
  `dadata_BELTWAY_HIT` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dadata_BELTWAY_DISTANCE` int(11) NOT NULL DEFAULT 0,
  `deliveryPeriodMin` int(11) NOT NULL,
  `deliveryPeriodMax` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cdek_deliverypoints`
--

DROP TABLE IF EXISTS `cdek_deliverypoints`;
CREATE TABLE `cdek_deliverypoints` (
  `deliverypoint_id` int(11) NOT NULL,
  `code` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_code` int(11) NOT NULL,
  `region` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_code` int(11) NOT NULL,
  `city` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_сode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_full` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nearest_station` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nearest_metro_station` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phones` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_сode` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `take_only` tinyint(1) NOT NULL,
  `is_handout` tinyint(1) NOT NULL,
  `is_reception` tinyint(1) NOT NULL,
  `is_dressing_room` tinyint(1) NOT NULL,
  `have_cashless` tinyint(1) NOT NULL,
  `have_cash` tinyint(1) NOT NULL,
  `allowed_cod` tinyint(1) NOT NULL,
  `site` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `office_image_list` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_time_list` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight_min` decimal(14,2) NOT NULL,
  `weight_max` decimal(14,2) NOT NULL,
  `weight_limits` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cdek_zones`
--

DROP TABLE IF EXISTS `cdek_zones`;
CREATE TABLE `cdek_zones` (
  `zone_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `country_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_code` int(11) NOT NULL,
  `kladr_region_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fias_region_guid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `collection`
--

DROP TABLE IF EXISTS `collection`;
CREATE TABLE `collection` (
  `collection_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(500) NOT NULL DEFAULT '',
  `not_update_image` tinyint(1) NOT NULL DEFAULT 0,
  `manufacturer_id` int(11) NOT NULL DEFAULT 0,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `virtual` tinyint(1) NOT NULL DEFAULT 0,
  `no_brand` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `collection_description`
--

DROP TABLE IF EXISTS `collection_description`;
CREATE TABLE `collection_description` (
  `collection_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name_overload` varchar(255) NOT NULL,
  `alternate_name` mediumtext NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(1024) NOT NULL,
  `type` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_h1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `collection_image`
--

DROP TABLE IF EXISTS `collection_image`;
CREATE TABLE `collection_image` (
  `collection_id` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `collection_to_store`
--

DROP TABLE IF EXISTS `collection_to_store`;
CREATE TABLE `collection_to_store` (
  `collection_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `competitors`
--

DROP TABLE IF EXISTS `competitors`;
CREATE TABLE `competitors` (
  `competitor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `classname` varchar(255) NOT NULL,
  `currency` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `competitor_price`
--

DROP TABLE IF EXISTS `competitor_price`;
CREATE TABLE `competitor_price` (
  `competitor_price_id` int(11) NOT NULL,
  `competitor_id` int(11) NOT NULL,
  `competitor_has` tinyint(1) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `our_price` decimal(15,2) NOT NULL,
  `our_cost` decimal(15,2) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `competitor_urls`
--

DROP TABLE IF EXISTS `competitor_urls`;
CREATE TABLE `competitor_urls` (
  `competitor_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `date_added` date NOT NULL,
  `sku` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `counters`
--

DROP TABLE IF EXISTS `counters`;
CREATE TABLE `counters` (
  `counter_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `counter` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL,
  `iso_code_3` varchar(3) NOT NULL,
  `warehouse_identifier` varchar(30) NOT NULL,
  `address_format` text NOT NULL,
  `postcode_required` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `countrybrand`
--

DROP TABLE IF EXISTS `countrybrand`;
CREATE TABLE `countrybrand` (
  `countrybrand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(500) NOT NULL DEFAULT '',
  `template` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `flag` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `countrybrand_description`
--

DROP TABLE IF EXISTS `countrybrand_description`;
CREATE TABLE `countrybrand_description` (
  `countrybrand_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name_overload` varchar(255) NOT NULL,
  `alternate_name` mediumtext NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(1024) NOT NULL,
  `type` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_h1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `countrybrand_image`
--

DROP TABLE IF EXISTS `countrybrand_image`;
CREATE TABLE `countrybrand_image` (
  `countrybrand_id` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `countrybrand_to_store`
--

DROP TABLE IF EXISTS `countrybrand_to_store`;
CREATE TABLE `countrybrand_to_store` (
  `countrybrand_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `country_to_fias`
--

DROP TABLE IF EXISTS `country_to_fias`;
CREATE TABLE `country_to_fias` (
  `country_id` int(11) NOT NULL,
  `fias_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon`
--

DROP TABLE IF EXISTS `coupon`;
CREATE TABLE `coupon` (
  `coupon_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `code` varchar(64) NOT NULL,
  `type` char(1) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  `discount_sum` varchar(500) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `logged` tinyint(1) NOT NULL,
  `shipping` tinyint(1) NOT NULL,
  `total` decimal(15,4) NOT NULL,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `uses_total` int(11) NOT NULL,
  `uses_customer` varchar(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `affiliate_id` int(11) NOT NULL DEFAULT 0,
  `show_in_segments` tinyint(1) NOT NULL,
  `birthday` tinyint(4) NOT NULL DEFAULT 0,
  `days_from_send` int(11) NOT NULL,
  `actiontemplate_id` int(11) NOT NULL,
  `promo_type` varchar(20) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `display_list` tinyint(1) NOT NULL DEFAULT 0,
  `action_id` int(11) NOT NULL,
  `only_in_stock` tinyint(1) NOT NULL DEFAULT 0,
  `display_in_account` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_category`
--

DROP TABLE IF EXISTS `coupon_category`;
CREATE TABLE `coupon_category` (
  `coupon_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_collection`
--

DROP TABLE IF EXISTS `coupon_collection`;
CREATE TABLE `coupon_collection` (
  `coupon_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_description`
--

DROP TABLE IF EXISTS `coupon_description`;
CREATE TABLE `coupon_description` (
  `coupon_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_history`
--

DROP TABLE IF EXISTS `coupon_history`;
CREATE TABLE `coupon_history` (
  `coupon_history_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_manufacturer`
--

DROP TABLE IF EXISTS `coupon_manufacturer`;
CREATE TABLE `coupon_manufacturer` (
  `coupon_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_product`
--

DROP TABLE IF EXISTS `coupon_product`;
CREATE TABLE `coupon_product` (
  `coupon_product_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_review`
--

DROP TABLE IF EXISTS `coupon_review`;
CREATE TABLE `coupon_review` (
  `coupon_id` int(11) NOT NULL,
  `code` varchar(8) COLLATE utf8mb3_bin NOT NULL,
  `coupon_history_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `csvprice_pro`
--

DROP TABLE IF EXISTS `csvprice_pro`;
CREATE TABLE `csvprice_pro` (
  `setting_id` int(11) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text DEFAULT NULL,
  `serialized` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `csvprice_pro_crontab`
--

DROP TABLE IF EXISTS `csvprice_pro_crontab`;
CREATE TABLE `csvprice_pro_crontab` (
  `job_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `job_key` varchar(64) NOT NULL,
  `job_type` enum('import','export') DEFAULT NULL,
  `job_file_location` enum('dir','web','ftp') DEFAULT NULL,
  `job_time_start` datetime NOT NULL,
  `job_offline` tinyint(1) NOT NULL DEFAULT 0,
  `job_data` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `serialized` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `csvprice_pro_images`
--

DROP TABLE IF EXISTS `csvprice_pro_images`;
CREATE TABLE `csvprice_pro_images` (
  `catalog_id` int(11) NOT NULL,
  `image_key` char(32) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `csvprice_pro_profiles`
--

DROP TABLE IF EXISTS `csvprice_pro_profiles`;
CREATE TABLE `csvprice_pro_profiles` (
  `profile_id` int(11) NOT NULL,
  `key` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` text DEFAULT NULL,
  `serialized` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `morph` varchar(255) NOT NULL,
  `code` varchar(3) NOT NULL,
  `flag` varchar(30) NOT NULL,
  `symbol_left` varchar(12) NOT NULL,
  `symbol_right` varchar(12) NOT NULL,
  `decimal_place` char(1) NOT NULL,
  `value` float(15,8) NOT NULL,
  `old_value` float(15,8) NOT NULL,
  `value_real` float(15,8) NOT NULL,
  `value_eur_official` decimal(15,4) NOT NULL,
  `old_value_real` float(15,8) NOT NULL,
  `value_uah_unreal` float(15,4) NOT NULL,
  `cryptopair` varchar(16) NOT NULL,
  `cryptopair_value` decimal(15,4) NOT NULL,
  `value_minimal` decimal(15,4) NOT NULL,
  `plus_percent` varchar(4) NOT NULL DEFAULT '0',
  `auto_percent` decimal(15,2) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0,
  `language_id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `customer_comment` text NOT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `normalized_telephone` varchar(32) NOT NULL,
  `fax` varchar(32) NOT NULL,
  `normalized_fax` varchar(32) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `cart` text DEFAULT NULL,
  `wishlist` text DEFAULT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT 0,
  `newsletter_news` tinyint(1) NOT NULL DEFAULT 1,
  `newsletter_personal` tinyint(1) NOT NULL DEFAULT 1,
  `viber_news` tinyint(1) NOT NULL DEFAULT 1,
  `newsletter_ema_uid` varchar(32) NOT NULL,
  `newsletter_news_ema_uid` varchar(32) NOT NULL,
  `newsletter_personal_ema_uid` varchar(32) NOT NULL,
  `address_id` int(11) NOT NULL DEFAULT 0,
  `customer_group_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `approved` tinyint(1) NOT NULL,
  `discount_card` varchar(10) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `utoken` varchar(255) NOT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `tracking` varchar(20) NOT NULL,
  `affiliate_paid` int(11) NOT NULL DEFAULT 0,
  `affiliate_life_time` date DEFAULT NULL,
  `affiliate_bonus_time_constant` date DEFAULT NULL,
  `number_reminder_sent` int(11) NOT NULL,
  `date_last_action` date NOT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'Organic',
  `mudak` tinyint(1) NOT NULL DEFAULT 0,
  `gender` tinyint(4) DEFAULT 0,
  `mail_status` varchar(30) DEFAULT NULL,
  `mail_opened` int(11) DEFAULT 0,
  `mail_clicked` int(11) NOT NULL DEFAULT 0,
  `has_push` tinyint(1) NOT NULL,
  `notify` tinyint(1) NOT NULL,
  `passport_serie` varchar(30) NOT NULL,
  `passport_given` text NOT NULL,
  `cashless_info` text NOT NULL,
  `sendpulse_push_id` varchar(255) NOT NULL,
  `birthday` varchar(20) NOT NULL,
  `birthday_month` int(11) DEFAULT NULL,
  `birthday_date` int(11) DEFAULT NULL,
  `order_count` int(11) NOT NULL,
  `order_good_count` int(11) NOT NULL,
  `order_bad_count` int(11) NOT NULL,
  `order_good_first_date` date NOT NULL,
  `order_good_last_date` date NOT NULL,
  `order_last_date` date NOT NULL,
  `order_first_date` date NOT NULL,
  `avg_cheque` decimal(15,4) NOT NULL,
  `total_cheque` decimal(15,4) NOT NULL,
  `total_product_cheque` decimal(15,4) NOT NULL,
  `total_calls` int(11) NOT NULL,
  `avg_calls_duration` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `city_population` int(11) NOT NULL,
  `first_order_source` varchar(255) NOT NULL,
  `csi_average` float(2,1) NOT NULL,
  `csi_reject` tinyint(1) NOT NULL,
  `nbt_csi` tinyint(1) NOT NULL DEFAULT 0,
  `nbt_customer` tinyint(1) NOT NULL DEFAULT 0,
  `rja_customer` tinyint(1) NOT NULL DEFAULT 0,
  `cron_sent` tinyint(1) NOT NULL DEFAULT 0,
  `printed2912` tinyint(1) NOT NULL DEFAULT 0,
  `social_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_ban_ip`
--

DROP TABLE IF EXISTS `customer_ban_ip`;
CREATE TABLE `customer_ban_ip` (
  `customer_ban_ip_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_calls`
--

DROP TABLE IF EXISTS `customer_calls`;
CREATE TABLE `customer_calls` (
  `customer_call_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `inbound` tinyint(1) NOT NULL,
  `customer_phone` varchar(100) NOT NULL,
  `length` int(11) NOT NULL,
  `date_end` datetime NOT NULL,
  `comment` text NOT NULL,
  `internal_pbx_num` varchar(10) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `filelink` varchar(1024) NOT NULL,
  `order_id` int(11) NOT NULL,
  `sip_queue` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_emails_blacklist`
--

DROP TABLE IF EXISTS `customer_emails_blacklist`;
CREATE TABLE `customer_emails_blacklist` (
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_emails_whitelist`
--

DROP TABLE IF EXISTS `customer_emails_whitelist`;
CREATE TABLE `customer_emails_whitelist` (
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_email_campaigns`
--

DROP TABLE IF EXISTS `customer_email_campaigns`;
CREATE TABLE `customer_email_campaigns` (
  `customer_email_campaigns_id` bigint(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `campaign_id` varchar(255) NOT NULL,
  `mail_status` varchar(50) NOT NULL,
  `mail_opened` int(11) NOT NULL DEFAULT 0,
  `mail_clicked` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_email_campaigns_names`
--

DROP TABLE IF EXISTS `customer_email_campaigns_names`;
CREATE TABLE `customer_email_campaigns_names` (
  `email_campaign_mailwizz_id` varchar(100) NOT NULL,
  `email_campaign_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_field`
--

DROP TABLE IF EXISTS `customer_field`;
CREATE TABLE `customer_field` (
  `customer_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `custom_field_value_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `value` text NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_group`
--

DROP TABLE IF EXISTS `customer_group`;
CREATE TABLE `customer_group` (
  `customer_group_id` int(11) NOT NULL,
  `approval` int(11) NOT NULL,
  `company_id_display` int(11) NOT NULL,
  `company_id_required` int(11) NOT NULL,
  `tax_id_display` int(11) NOT NULL,
  `tax_id_required` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_group_description`
--

DROP TABLE IF EXISTS `customer_group_description`;
CREATE TABLE `customer_group_description` (
  `customer_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_group_price`
--

DROP TABLE IF EXISTS `customer_group_price`;
CREATE TABLE `customer_group_price` (
  `customer_group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_history`
--

DROP TABLE IF EXISTS `customer_history`;
CREATE TABLE `customer_history` (
  `customer_history_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL,
  `call_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `order_status_id` int(11) NOT NULL,
  `prev_order_status_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL DEFAULT 0,
  `segment_id` int(11) NOT NULL DEFAULT 0,
  `sms_id` varchar(50) NOT NULL DEFAULT '0',
  `email_id` int(11) NOT NULL DEFAULT 0,
  `need_call` datetime DEFAULT NULL,
  `is_error` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_ip`
--

DROP TABLE IF EXISTS `customer_ip`;
CREATE TABLE `customer_ip` (
  `customer_ip_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_online`
--

DROP TABLE IF EXISTS `customer_online`;
CREATE TABLE `customer_online` (
  `ip` varchar(40) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `referer` text NOT NULL,
  `date_added` datetime NOT NULL,
  `useragent` varchar(400) NOT NULL,
  `is_bot` tinyint(1) NOT NULL,
  `is_pwa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_online_history`
--

DROP TABLE IF EXISTS `customer_online_history`;
CREATE TABLE `customer_online_history` (
  `customer_count` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_push_ids`
--

DROP TABLE IF EXISTS `customer_push_ids`;
CREATE TABLE `customer_push_ids` (
  `customer_id` int(11) NOT NULL,
  `sendpulse_push_id` varchar(255) NOT NULL,
  `onesignal_push_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_reward`
--

DROP TABLE IF EXISTS `customer_reward`;
CREATE TABLE `customer_reward` (
  `customer_reward_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `reason_code` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `points_paid` int(11) NOT NULL DEFAULT 0,
  `date_paid` date NOT NULL,
  `burned` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_reward_queue`
--

DROP TABLE IF EXISTS `customer_reward_queue`;
CREATE TABLE `customer_reward_queue` (
  `customer_reward_queue_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_added` date NOT NULL,
  `points` int(11) NOT NULL,
  `date_activate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_search_history`
--

DROP TABLE IF EXISTS `customer_search_history`;
CREATE TABLE `customer_search_history` (
  `customer_history_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_segments`
--

DROP TABLE IF EXISTS `customer_segments`;
CREATE TABLE `customer_segments` (
  `customer_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_simple_fields`
--

DROP TABLE IF EXISTS `customer_simple_fields`;
CREATE TABLE `customer_simple_fields` (
  `customer_id` int(11) NOT NULL,
  `metadata` text DEFAULT NULL,
  `newsletter_news` text DEFAULT NULL,
  `newsletter_personal` text DEFAULT NULL,
  `viber_news` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_test`
--

DROP TABLE IF EXISTS `customer_test`;
CREATE TABLE `customer_test` (
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_transaction`
--

DROP TABLE IF EXISTS `customer_transaction`;
CREATE TABLE `customer_transaction` (
  `customer_transaction_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `amount_national` decimal(15,4) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `date_added` datetime NOT NULL,
  `sms_sent` datetime DEFAULT NULL,
  `equiring` tinyint(1) NOT NULL DEFAULT 0,
  `added_from` varchar(40) NOT NULL,
  `account` varchar(64) NOT NULL,
  `legalperson_id` int(11) NOT NULL,
  `paykeeper_id` varchar(255) NOT NULL,
  `concardis_id` varchar(255) NOT NULL,
  `f3_id` varchar(255) NOT NULL,
  `f3_paykeeper` text NOT NULL,
  `f3_ofd` text NOT NULL,
  `f3_ofd_link` text NOT NULL,
  `pspReference` varchar(128) NOT NULL,
  `guid` varchar(128) NOT NULL,
  `json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `customer_viewed`
--

DROP TABLE IF EXISTS `customer_viewed`;
CREATE TABLE `customer_viewed` (
  `customer_id` int(11) NOT NULL,
  `type` enum('c','m','p') NOT NULL,
  `entity_id` int(11) NOT NULL,
  `times` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `custom_field`
--

DROP TABLE IF EXISTS `custom_field`;
CREATE TABLE `custom_field` (
  `custom_field_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `required` tinyint(1) NOT NULL,
  `location` varchar(32) NOT NULL,
  `position` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `custom_field_description`
--

DROP TABLE IF EXISTS `custom_field_description`;
CREATE TABLE `custom_field_description` (
  `custom_field_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `custom_field_to_customer_group`
--

DROP TABLE IF EXISTS `custom_field_to_customer_group`;
CREATE TABLE `custom_field_to_customer_group` (
  `custom_field_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `custom_field_value`
--

DROP TABLE IF EXISTS `custom_field_value`;
CREATE TABLE `custom_field_value` (
  `custom_field_value_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `custom_field_value_description`
--

DROP TABLE IF EXISTS `custom_field_value_description`;
CREATE TABLE `custom_field_value_description` (
  `custom_field_value_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `custom_url_404`
--

DROP TABLE IF EXISTS `custom_url_404`;
CREATE TABLE `custom_url_404` (
  `custom_url_404_id` int(11) NOT NULL,
  `hit` int(11) DEFAULT NULL,
  `url_404` varchar(255) DEFAULT NULL,
  `url_redirect` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `deleted_asins`
--

DROP TABLE IF EXISTS `deleted_asins`;
CREATE TABLE `deleted_asins` (
  `asin` varchar(16) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `direct_timezones`
--

DROP TABLE IF EXISTS `direct_timezones`;
CREATE TABLE `direct_timezones` (
  `geomd5` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `download`
--

DROP TABLE IF EXISTS `download`;
CREATE TABLE `download` (
  `download_id` int(11) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `mask` varchar(128) NOT NULL,
  `remaining` int(11) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `download_description`
--

DROP TABLE IF EXISTS `download_description`;
CREATE TABLE `download_description` (
  `download_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `emailmarketing_logs`
--

DROP TABLE IF EXISTS `emailmarketing_logs`;
CREATE TABLE `emailmarketing_logs` (
  `emailmarketing_log_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `actiontemplate_id` int(11) NOT NULL,
  `mail_from` varchar(255) NOT NULL,
  `mail_from_email` varchar(255) NOT NULL,
  `mail_to` varchar(255) NOT NULL,
  `mail_to_email` varchar(255) NOT NULL,
  `date_sent` datetime NOT NULL,
  `html` longtext NOT NULL,
  `title` varchar(500) NOT NULL,
  `transmission_id` varchar(255) NOT NULL,
  `mail_status` varchar(20) NOT NULL,
  `mail_opened` int(11) NOT NULL,
  `mail_clicked` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `emailtemplate`
--

DROP TABLE IF EXISTS `emailtemplate`;
CREATE TABLE `emailtemplate` (
  `emailtemplate_id` int(11) NOT NULL,
  `emailtemplate_key` varchar(64) NOT NULL,
  `emailtemplate_label` varchar(255) NOT NULL,
  `emailtemplate_template` varchar(255) NOT NULL,
  `emailtemplate_mail_to` varchar(255) NOT NULL,
  `emailtemplate_mail_cc` varchar(255) NOT NULL,
  `emailtemplate_mail_bcc` varchar(255) NOT NULL,
  `emailtemplate_mail_from` varchar(255) NOT NULL,
  `emailtemplate_mail_sender` varchar(255) NOT NULL,
  `emailtemplate_mail_replyto` varchar(255) NOT NULL,
  `emailtemplate_mail_replyto_name` varchar(255) NOT NULL,
  `emailtemplate_mail_attachment` varchar(255) NOT NULL,
  `emailtemplate_attach_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `emailtemplate_language_files` varchar(255) NOT NULL,
  `emailtemplate_wrapper_tpl` varchar(255) NOT NULL,
  `emailtemplate_tracking_campaign_source` varchar(255) NOT NULL,
  `emailtemplate_status` enum('ENABLED','DISABLED') NOT NULL,
  `emailtemplate_default` tinyint(1) NOT NULL DEFAULT 1,
  `emailtemplate_shortcodes` tinyint(1) NOT NULL DEFAULT 0,
  `emailtemplate_showcase` tinyint(1) NOT NULL DEFAULT 1,
  `emailtemplate_plain_text` tinyint(1) NOT NULL DEFAULT 0,
  `emailtemplate_integrate_extension` tinyint(1) NOT NULL DEFAULT 0,
  `emailtemplate_condition` text NOT NULL COMMENT 'serialized array[](key,operator,value,label)',
  `emailtemplate_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `emailtemplate_log` tinyint(1) NOT NULL,
  `emailtemplate_view_browser` tinyint(1) NOT NULL,
  `emailtemplate_view_browser_theme` tinyint(1) NOT NULL,
  `emailtemplate_config_id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `customer_group_id` int(11) DEFAULT NULL,
  `order_status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `emailtemplate_config`
--

DROP TABLE IF EXISTS `emailtemplate_config`;
CREATE TABLE `emailtemplate_config` (
  `emailtemplate_config_id` int(11) NOT NULL,
  `emailtemplate_config_name` varchar(64) NOT NULL,
  `emailtemplate_config_email_width` smallint(6) NOT NULL,
  `emailtemplate_config_email_responsive` tinyint(1) NOT NULL,
  `emailtemplate_config_page_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_body_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_body_font_color` varchar(32) NOT NULL,
  `emailtemplate_config_body_link_color` varchar(32) NOT NULL,
  `emailtemplate_config_body_heading_color` varchar(32) NOT NULL,
  `emailtemplate_config_body_section_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_page_footer_text` text NOT NULL,
  `emailtemplate_config_footer_text` text NOT NULL,
  `emailtemplate_config_footer_align` varchar(32) NOT NULL,
  `emailtemplate_config_footer_valign` varchar(32) NOT NULL,
  `emailtemplate_config_footer_font_color` varchar(32) NOT NULL,
  `emailtemplate_config_footer_height` smallint(6) NOT NULL,
  `emailtemplate_config_footer_section_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_header_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_header_bg_image` varchar(255) NOT NULL,
  `emailtemplate_config_header_height` smallint(6) NOT NULL,
  `emailtemplate_config_header_border_color` varchar(32) NOT NULL,
  `emailtemplate_config_header_section_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_head_text` text NOT NULL,
  `emailtemplate_config_head_section_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_view_browser_text` text NOT NULL,
  `emailtemplate_config_invoice_color` varchar(32) NOT NULL,
  `emailtemplate_config_invoice_download` tinyint(1) NOT NULL DEFAULT 1,
  `emailtemplate_config_invoice_header` text NOT NULL,
  `emailtemplate_config_invoice_footer` text NOT NULL,
  `emailtemplate_config_logo` varchar(255) NOT NULL,
  `emailtemplate_config_logo_align` varchar(32) NOT NULL,
  `emailtemplate_config_logo_font_color` varchar(32) NOT NULL,
  `emailtemplate_config_logo_font_size` smallint(6) NOT NULL,
  `emailtemplate_config_logo_height` smallint(6) NOT NULL,
  `emailtemplate_config_logo_valign` varchar(32) NOT NULL,
  `emailtemplate_config_logo_width` smallint(6) NOT NULL,
  `emailtemplate_config_shadow_top` text NOT NULL,
  `emailtemplate_config_shadow_left` text NOT NULL,
  `emailtemplate_config_shadow_right` text NOT NULL,
  `emailtemplate_config_shadow_bottom` text NOT NULL,
  `emailtemplate_config_showcase` varchar(32) NOT NULL,
  `emailtemplate_config_showcase_limit` smallint(6) NOT NULL,
  `emailtemplate_config_showcase_selection` varchar(255) NOT NULL,
  `emailtemplate_config_showcase_title` varchar(255) NOT NULL,
  `emailtemplate_config_showcase_related` tinyint(1) NOT NULL,
  `emailtemplate_config_showcase_section_bg_color` varchar(32) NOT NULL,
  `emailtemplate_config_page_align` enum('left','right','center') NOT NULL DEFAULT 'center',
  `emailtemplate_config_text_align` varchar(32) NOT NULL,
  `emailtemplate_config_tracking` tinyint(1) NOT NULL DEFAULT 1,
  `emailtemplate_config_tracking_campaign_name` varchar(255) NOT NULL,
  `emailtemplate_config_tracking_campaign_term` varchar(255) NOT NULL,
  `emailtemplate_config_wrapper_tpl` varchar(255) NOT NULL,
  `emailtemplate_config_theme` varchar(64) NOT NULL,
  `emailtemplate_config_table_quantity` tinyint(1) NOT NULL,
  `emailtemplate_config_customer_register_validate_email` tinyint(1) NOT NULL,
  `emailtemplate_config_style` varchar(64) NOT NULL,
  `emailtemplate_config_version` varchar(64) NOT NULL,
  `emailtemplate_config_status` enum('ENABLED','DISABLED') NOT NULL,
  `emailtemplate_config_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `emailtemplate_config_log` tinyint(1) NOT NULL,
  `emailtemplate_config_log_read` tinyint(1) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `emailtemplate_description`
--

DROP TABLE IF EXISTS `emailtemplate_description`;
CREATE TABLE `emailtemplate_description` (
  `emailtemplate_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `emailtemplate_description_subject` varchar(120) NOT NULL,
  `emailtemplate_description_preview` varchar(255) NOT NULL,
  `emailtemplate_description_content1` longtext NOT NULL,
  `emailtemplate_description_content2` longtext NOT NULL,
  `emailtemplate_description_content3` longtext NOT NULL,
  `emailtemplate_description_comment` longtext NOT NULL,
  `emailtemplate_description_unsubscribe_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `emailtemplate_logs`
--

DROP TABLE IF EXISTS `emailtemplate_logs`;
CREATE TABLE `emailtemplate_logs` (
  `emailtemplate_log_id` bigint(20) UNSIGNED NOT NULL,
  `emailtemplate_log_sent` datetime DEFAULT NULL,
  `emailtemplate_log_read` datetime DEFAULT NULL,
  `emailtemplate_log_read_last` datetime DEFAULT NULL,
  `emailtemplate_log_type` enum('','SYSTEM','CONTACT','MARKETING') NOT NULL,
  `emailtemplate_log_to` varchar(255) NOT NULL,
  `emailtemplate_log_from` varchar(255) NOT NULL,
  `emailtemplate_log_sender` varchar(255) NOT NULL,
  `emailtemplate_log_subject` varchar(255) NOT NULL,
  `emailtemplate_log_text` longtext NOT NULL,
  `emailtemplate_log_html` longtext NOT NULL,
  `emailtemplate_log_content` longtext NOT NULL,
  `emailtemplate_log_enc` varchar(255) NOT NULL,
  `emailtemplate_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `transmission_id` varchar(255) NOT NULL DEFAULT '0',
  `mail_status` varchar(30) DEFAULT NULL,
  `mail_opened` int(11) NOT NULL DEFAULT 0,
  `mail_clicked` int(11) NOT NULL DEFAULT 0,
  `marketing` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `emailtemplate_shortcode`
--

DROP TABLE IF EXISTS `emailtemplate_shortcode`;
CREATE TABLE `emailtemplate_shortcode` (
  `emailtemplate_shortcode_id` int(11) NOT NULL,
  `emailtemplate_shortcode_code` varchar(255) NOT NULL,
  `emailtemplate_shortcode_type` enum('language','auto','auto_serialize') NOT NULL DEFAULT 'language',
  `emailtemplate_shortcode_example` text NOT NULL,
  `emailtemplate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `email_emailto`
--

DROP TABLE IF EXISTS `email_emailto`;
CREATE TABLE `email_emailto` (
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `entity_reward`
--

DROP TABLE IF EXISTS `entity_reward`;
CREATE TABLE `entity_reward` (
  `entity_reward_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` enum('c','m','co','') COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `percent` int(11) NOT NULL DEFAULT 0,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `coupon_acts` tinyint(1) NOT NULL DEFAULT 0,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `extension`
--

DROP TABLE IF EXISTS `extension`;
CREATE TABLE `extension` (
  `extension_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `facategory`
--

DROP TABLE IF EXISTS `facategory`;
CREATE TABLE `facategory` (
  `facategory_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `facategory_to_faproduct`
--

DROP TABLE IF EXISTS `facategory_to_faproduct`;
CREATE TABLE `facategory_to_faproduct` (
  `facategory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `faproduct_to_facategory`
--

DROP TABLE IF EXISTS `faproduct_to_facategory`;
CREATE TABLE `faproduct_to_facategory` (
  `product_id` int(11) NOT NULL,
  `facategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `faq_category`
--

DROP TABLE IF EXISTS `faq_category`;
CREATE TABLE `faq_category` (
  `category_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `faq_category_description`
--

DROP TABLE IF EXISTS `faq_category_description`;
CREATE TABLE `faq_category_description` (
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `faq_question`
--

DROP TABLE IF EXISTS `faq_question`;
CREATE TABLE `faq_question` (
  `question_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `faq_question_description`
--

DROP TABLE IF EXISTS `faq_question_description`;
CREATE TABLE `faq_question_description` (
  `question_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` text COLLATE utf8mb3_bin NOT NULL,
  `description` text COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `feed_queue`
--

DROP TABLE IF EXISTS `feed_queue`;
CREATE TABLE `feed_queue` (
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `filter`
--

DROP TABLE IF EXISTS `filter`;
CREATE TABLE `filter` (
  `filter_id` int(11) NOT NULL,
  `filter_group_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `filterpro_seo`
--

DROP TABLE IF EXISTS `filterpro_seo`;
CREATE TABLE `filterpro_seo` (
  `url` varchar(255) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `filter_description`
--

DROP TABLE IF EXISTS `filter_description`;
CREATE TABLE `filter_description` (
  `filter_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `filter_group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `filter_group`
--

DROP TABLE IF EXISTS `filter_group`;
CREATE TABLE `filter_group` (
  `filter_group_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `filter_group_description`
--

DROP TABLE IF EXISTS `filter_group_description`;
CREATE TABLE `filter_group_description` (
  `filter_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `geo`
--

DROP TABLE IF EXISTS `geo`;
CREATE TABLE `geo` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `full_name` varchar(512) NOT NULL,
  `postcode` varchar(6) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `lat` double(10,6) NOT NULL,
  `long` float(10,6) NOT NULL,
  `population` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `geoname_alternatename`
--

DROP TABLE IF EXISTS `geoname_alternatename`;
CREATE TABLE `geoname_alternatename` (
  `alternatenameId` int(11) NOT NULL,
  `geonameid` int(11) DEFAULT NULL,
  `isoLanguage` varchar(7) DEFAULT NULL,
  `alternateName` varchar(200) DEFAULT NULL,
  `isPreferredName` tinyint(1) DEFAULT NULL,
  `isShortName` tinyint(1) DEFAULT NULL,
  `isColloquial` tinyint(1) DEFAULT NULL,
  `isHistoric` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `geoname_geoname`
--

DROP TABLE IF EXISTS `geoname_geoname`;
CREATE TABLE `geoname_geoname` (
  `geonameid` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `asciiname` varchar(200) DEFAULT NULL,
  `alternatenames` varchar(4000) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `fclass` char(1) DEFAULT NULL,
  `fcode` varchar(10) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `cc2` varchar(200) DEFAULT NULL,
  `admin1` varchar(20) DEFAULT NULL,
  `admin2` varchar(80) DEFAULT NULL,
  `admin3` varchar(20) DEFAULT NULL,
  `admin4` varchar(20) DEFAULT NULL,
  `population` int(11) DEFAULT NULL,
  `elevation` int(11) DEFAULT NULL,
  `gtopo30` int(11) DEFAULT NULL,
  `timezone` varchar(40) DEFAULT NULL,
  `moddate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_ip`
--

DROP TABLE IF EXISTS `geo_ip`;
CREATE TABLE `geo_ip` (
  `start` bigint(20) NOT NULL,
  `end` bigint(20) NOT NULL,
  `geo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_zone`
--

DROP TABLE IF EXISTS `geo_zone`;
CREATE TABLE `geo_zone` (
  `geo_zone_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `google_base_category`
--

DROP TABLE IF EXISTS `google_base_category`;
CREATE TABLE `google_base_category` (
  `google_base_category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `hj_any_feed_feeds`
--

DROP TABLE IF EXISTS `hj_any_feed_feeds`;
CREATE TABLE `hj_any_feed_feeds` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `settings` blob DEFAULT NULL,
  `version` varchar(64) DEFAULT NULL,
  `preset` int(11) DEFAULT NULL,
  `fields` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `imagemaps`
--

DROP TABLE IF EXISTS `imagemaps`;
CREATE TABLE `imagemaps` (
  `imagemap_id` int(11) NOT NULL,
  `module_code` varchar(64) NOT NULL,
  `module_id` int(11) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information`
--

DROP TABLE IF EXISTS `information`;
CREATE TABLE `information` (
  `information_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `igroup` varchar(50) NOT NULL,
  `show_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information_attribute`
--

DROP TABLE IF EXISTS `information_attribute`;
CREATE TABLE `information_attribute` (
  `information_attribute_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `igroup` varchar(50) NOT NULL,
  `show_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information_attribute_description`
--

DROP TABLE IF EXISTS `information_attribute_description`;
CREATE TABLE `information_attribute_description` (
  `information_attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information_attribute_to_layout`
--

DROP TABLE IF EXISTS `information_attribute_to_layout`;
CREATE TABLE `information_attribute_to_layout` (
  `information_attribute_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information_attribute_to_store`
--

DROP TABLE IF EXISTS `information_attribute_to_store`;
CREATE TABLE `information_attribute_to_store` (
  `information_attribute_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information_description`
--

DROP TABLE IF EXISTS `information_description`;
CREATE TABLE `information_description` (
  `information_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` longtext NOT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information_to_layout`
--

DROP TABLE IF EXISTS `information_to_layout`;
CREATE TABLE `information_to_layout` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `information_to_store`
--

DROP TABLE IF EXISTS `information_to_store`;
CREATE TABLE `information_to_store` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `interplusplus`
--

DROP TABLE IF EXISTS `interplusplus`;
CREATE TABLE `interplusplus` (
  `inter_id` int(11) NOT NULL,
  `num_order` int(11) DEFAULT NULL,
  `sum` int(11) DEFAULT NULL,
  `user` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_enroled` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `justin_cities`
--

DROP TABLE IF EXISTS `justin_cities`;
CREATE TABLE `justin_cities` (
  `city_id` int(11) NOT NULL,
  `Uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `RegionUuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `RegionDescr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `RegionDescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SCOATOU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `WarehouseCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `justin_city_regions`
--

DROP TABLE IF EXISTS `justin_city_regions`;
CREATE TABLE `justin_city_regions` (
  `region_id` int(11) NOT NULL,
  `Uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityUuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityDescr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityDescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SCOATOU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `justin_streets`
--

DROP TABLE IF EXISTS `justin_streets`;
CREATE TABLE `justin_streets` (
  `street_id` int(11) NOT NULL,
  `Uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityUuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityDescr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityDescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `justin_warehouses`
--

DROP TABLE IF EXISTS `justin_warehouses`;
CREATE TABLE `justin_warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `Uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `RegionUuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityUuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityDescr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityDescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `possibility_to_pay_by_card` tinyint(1) NOT NULL,
  `possibility_to_accept_payment` tinyint(1) NOT NULL,
  `availability_of_parcel_locker` tinyint(1) NOT NULL,
  `Address` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Lat` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Lng` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departNumber` int(11) NOT NULL,
  `houseNumber` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `StatusDepart` int(11) NOT NULL,
  `parcels_without_pay` tinyint(1) NOT NULL,
  `Monday` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Tuesday` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Wednesday` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Thursday` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Friday` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Saturday` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Sunday` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `justin_zones`
--

DROP TABLE IF EXISTS `justin_zones`;
CREATE TABLE `justin_zones` (
  `zone_id` int(11) NOT NULL,
  `Uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescrRu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SCOATOU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `justin_zone_regions`
--

DROP TABLE IF EXISTS `justin_zone_regions`;
CREATE TABLE `justin_zone_regions` (
  `region_id` int(11) NOT NULL,
  `Uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ZoneUuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ZoneDescr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ZoneDescrRU` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ZoneType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `keyworder`
--

DROP TABLE IF EXISTS `keyworder`;
CREATE TABLE `keyworder` (
  `keyworder_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `keyworder_description`
--

DROP TABLE IF EXISTS `keyworder_description`;
CREATE TABLE `keyworder_description` (
  `keyworder_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `seo_h1` varchar(255) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_status` tinyint(1) NOT NULL DEFAULT 1,
  `keyworder_status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `landingpage`
--

DROP TABLE IF EXISTS `landingpage`;
CREATE TABLE `landingpage` (
  `landingpage_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `landingpage_description`
--

DROP TABLE IF EXISTS `landingpage_description`;
CREATE TABLE `landingpage_description` (
  `landingpage_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `landingpage_to_layout`
--

DROP TABLE IF EXISTS `landingpage_to_layout`;
CREATE TABLE `landingpage_to_layout` (
  `landingpage_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `landingpage_to_store`
--

DROP TABLE IF EXISTS `landingpage_to_store`;
CREATE TABLE `landingpage_to_store` (
  `landingpage_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `code` varchar(5) NOT NULL,
  `urlcode` varchar(2) NOT NULL,
  `locale` varchar(255) NOT NULL,
  `switch` varchar(5) NOT NULL,
  `hreflang` varchar(10) NOT NULL,
  `image` varchar(64) NOT NULL,
  `directory` varchar(32) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL,
  `fasttranslate` longtext NOT NULL,
  `front` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `layout`
--

DROP TABLE IF EXISTS `layout`;
CREATE TABLE `layout` (
  `layout_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `layout_route`
--

DROP TABLE IF EXISTS `layout_route`;
CREATE TABLE `layout_route` (
  `layout_route_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `route` varchar(255) NOT NULL,
  `template` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `legalperson`
--

DROP TABLE IF EXISTS `legalperson`;
CREATE TABLE `legalperson` (
  `legalperson_id` int(11) NOT NULL,
  `legalperson_name` varchar(255) NOT NULL,
  `legalperson_name_1C` varchar(255) NOT NULL,
  `legalperson_desc` text NOT NULL,
  `legalperson_additional` text NOT NULL,
  `legalperson_print` varchar(255) NOT NULL,
  `legalperson_legal` tinyint(1) NOT NULL,
  `legalperson_country_id` int(11) NOT NULL,
  `account_info` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `length_class`
--

DROP TABLE IF EXISTS `length_class`;
CREATE TABLE `length_class` (
  `length_class_id` int(11) NOT NULL,
  `value` decimal(15,8) NOT NULL,
  `system_key` varchar(100) NOT NULL,
  `amazon_key` varchar(100) NOT NULL,
  `variants` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `length_class_description`
--

DROP TABLE IF EXISTS `length_class_description`;
CREATE TABLE `length_class_description` (
  `length_class_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `unit` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `local_supplier_products`
--

DROP TABLE IF EXISTS `local_supplier_products`;
CREATE TABLE `local_supplier_products` (
  `supplier_id` int(11) NOT NULL,
  `supplier_product_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_model` varchar(255) NOT NULL,
  `product_ean` varchar(20) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_recommend` decimal(15,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `stock` int(11) NOT NULL,
  `product_xml` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `mailwizz_queue`
--

DROP TABLE IF EXISTS `mailwizz_queue`;
CREATE TABLE `mailwizz_queue` (
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `manager_kpi`
--

DROP TABLE IF EXISTS `manager_kpi`;
CREATE TABLE `manager_kpi` (
  `manager_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `kpi_json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `manager_order_status_dynamics`
--

DROP TABLE IF EXISTS `manager_order_status_dynamics`;
CREATE TABLE `manager_order_status_dynamics` (
  `manager_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `manager_order_status_dynamics2`
--

DROP TABLE IF EXISTS `manager_order_status_dynamics2`;
CREATE TABLE `manager_order_status_dynamics2` (
  `manager_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer`
--

DROP TABLE IF EXISTS `manufacturer`;
CREATE TABLE `manufacturer` (
  `manufacturer_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `tip` varchar(15) NOT NULL,
  `menu_brand` tinyint(1) NOT NULL DEFAULT 0,
  `show_goods` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `back_image` varchar(500) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `banner_width` int(11) NOT NULL,
  `banner_height` int(11) NOT NULL,
  `tpl` varchar(255) NOT NULL,
  `priceva_enable` tinyint(4) NOT NULL DEFAULT 0,
  `priceva_feed` varchar(32) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer_description`
--

DROP TABLE IF EXISTS `manufacturer_description`;
CREATE TABLE `manufacturer_description` (
  `manufacturer_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb3_bin NOT NULL,
  `alternate_name` longtext COLLATE utf8mb3_bin NOT NULL,
  `location` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `short_description` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0,
  `seo_title` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `seo_h1` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `tag` text COLLATE utf8mb3_bin DEFAULT NULL,
  `products_title` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `products_meta_description` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `collections_title` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `collections_meta_description` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `categories_title` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `categories_meta_description` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `articles_title` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `articles_meta_description` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `newproducts_title` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `newproducts_meta_description` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `special_title` varchar(500) COLLATE utf8mb3_bin NOT NULL,
  `special_meta_description` varchar(500) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer_page_content`
--

DROP TABLE IF EXISTS `manufacturer_page_content`;
CREATE TABLE `manufacturer_page_content` (
  `manufacturer_page_content_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `products` text NOT NULL,
  `collections` text NOT NULL,
  `categories` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer_to_layout`
--

DROP TABLE IF EXISTS `manufacturer_to_layout`;
CREATE TABLE `manufacturer_to_layout` (
  `manufacturer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer_to_store`
--

DROP TABLE IF EXISTS `manufacturer_to_store`;
CREATE TABLE `manufacturer_to_store` (
  `manufacturer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `maxmind_geo_country`
--

DROP TABLE IF EXISTS `maxmind_geo_country`;
CREATE TABLE `maxmind_geo_country` (
  `start` bigint(20) NOT NULL,
  `end` bigint(20) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `multi_pay_payment`
--

DROP TABLE IF EXISTS `multi_pay_payment`;
CREATE TABLE `multi_pay_payment` (
  `payment_id` int(11) NOT NULL,
  `service_cod` varchar(50) DEFAULT NULL,
  `service_account` varchar(40) NOT NULL,
  `operation_id` varchar(40) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `curr` varchar(5) DEFAULT NULL,
  `amount` decimal(16,8) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `nauthor`
--

DROP TABLE IF EXISTS `nauthor`;
CREATE TABLE `nauthor` (
  `nauthor_id` int(11) NOT NULL,
  `adminid` varchar(64) COLLATE utf8mb3_bin NOT NULL,
  `name` varchar(64) COLLATE utf8mb3_bin NOT NULL,
  `image` varchar(255) COLLATE utf8mb3_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `nauthor_description`
--

DROP TABLE IF EXISTS `nauthor_description`;
CREATE TABLE `nauthor_description` (
  `nauthor_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `ctitle` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `description` text COLLATE utf8mb3_bin NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `ncategory`
--

DROP TABLE IF EXISTS `ncategory`;
CREATE TABLE `ncategory` (
  `ncategory_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb3_bin DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `top` tinyint(1) NOT NULL,
  `column` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `ncategory_description`
--

DROP TABLE IF EXISTS `ncategory_description`;
CREATE TABLE `ncategory_description` (
  `ncategory_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb3_bin NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8mb3_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `ncategory_to_layout`
--

DROP TABLE IF EXISTS `ncategory_to_layout`;
CREATE TABLE `ncategory_to_layout` (
  `ncategory_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `ncategory_to_store`
--

DROP TABLE IF EXISTS `ncategory_to_store`;
CREATE TABLE `ncategory_to_store` (
  `ncategory_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `ncomments`
--

DROP TABLE IF EXISTS `ncomments`;
CREATE TABLE `ncomments` (
  `ncomment_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL DEFAULT 0,
  `author` varchar(64) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `text` text COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `nauthor_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `acom` int(11) NOT NULL DEFAULT 0,
  `date_added` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `image2` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `sort_order` int(11) DEFAULT NULL,
  `gal_thumb_w` int(11) NOT NULL,
  `gal_thumb_h` int(11) NOT NULL,
  `gal_popup_w` int(11) NOT NULL,
  `gal_popup_h` int(11) NOT NULL,
  `gal_slider_h` int(11) NOT NULL,
  `gal_slider_t` int(11) NOT NULL,
  `date_pub` datetime DEFAULT NULL,
  `gal_slider_w` int(11) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news_description`
--

DROP TABLE IF EXISTS `news_description`;
CREATE TABLE `news_description` (
  `news_id` int(11) NOT NULL DEFAULT 0,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `ctitle` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description2` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_desc` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `ntags` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `recipe` text COLLATE utf8mb3_bin NOT NULL,
  `cfield1` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `cfield2` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `cfield3` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `cfield4` varchar(255) COLLATE utf8mb3_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news_gallery`
--

DROP TABLE IF EXISTS `news_gallery`;
CREATE TABLE `news_gallery` (
  `news_image_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `image` varchar(512) DEFAULT NULL,
  `text` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `news_related`
--

DROP TABLE IF EXISTS `news_related`;
CREATE TABLE `news_related` (
  `news_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news_to_layout`
--

DROP TABLE IF EXISTS `news_to_layout`;
CREATE TABLE `news_to_layout` (
  `news_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news_to_ncategory`
--

DROP TABLE IF EXISTS `news_to_ncategory`;
CREATE TABLE `news_to_ncategory` (
  `news_id` int(11) NOT NULL,
  `ncategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news_to_store`
--

DROP TABLE IF EXISTS `news_to_store`;
CREATE TABLE `news_to_store` (
  `news_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news_video`
--

DROP TABLE IF EXISTS `news_video`;
CREATE TABLE `news_video` (
  `news_video_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb3_bin NOT NULL,
  `video` varchar(255) COLLATE utf8mb3_bin DEFAULT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `novaposhta_cities`
--

DROP TABLE IF EXISTS `novaposhta_cities`;
CREATE TABLE `novaposhta_cities` (
  `CityID` int(11) NOT NULL,
  `Ref` varchar(36) NOT NULL,
  `Description` varchar(200) NOT NULL,
  `DescriptionRu` varchar(200) NOT NULL,
  `Area` varchar(36) NOT NULL,
  `SettlementType` varchar(36) NOT NULL,
  `SettlementTypeDescription` varchar(200) NOT NULL,
  `SettlementTypeDescriptionRu` varchar(200) NOT NULL,
  `RegionsDescription` varchar(255) NOT NULL,
  `RegionsDescriptionRu` varchar(255) NOT NULL,
  `AreaDescription` varchar(255) NOT NULL,
  `AreaDescriptionRu` varchar(255) NOT NULL,
  `Index1` varchar(32) NOT NULL,
  `Index2` varchar(32) NOT NULL,
  `Delivery1` tinyint(1) NOT NULL,
  `Delivery2` tinyint(1) NOT NULL,
  `Delivery3` tinyint(1) NOT NULL,
  `Delivery4` tinyint(1) NOT NULL,
  `Delivery5` tinyint(1) NOT NULL,
  `Delivery6` tinyint(1) NOT NULL,
  `Delivery7` tinyint(1) NOT NULL,
  `Conglomerates` text NOT NULL,
  `Latitude` varchar(32) NOT NULL,
  `Longitude` varchar(32) NOT NULL,
  `PreventEntryNewStreetsUser` text NOT NULL,
  `IsBranch` tinyint(1) NOT NULL,
  `SpecialCashCheck` tinyint(1) NOT NULL,
  `Warehouse` int(11) NOT NULL,
  `WarehouseCount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `novaposhta_cities_ww`
--

DROP TABLE IF EXISTS `novaposhta_cities_ww`;
CREATE TABLE `novaposhta_cities_ww` (
  `CityID` int(11) NOT NULL,
  `Ref` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescriptionRu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Delivery1` int(11) NOT NULL,
  `Delivery2` int(11) NOT NULL,
  `Delivery3` int(11) NOT NULL,
  `Delivery4` int(11) NOT NULL,
  `Delivery5` int(11) NOT NULL,
  `Delivery6` int(11) NOT NULL,
  `Delivery7` int(11) NOT NULL,
  `Area` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SettlementType` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SettlementTypeDescriptionRu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SettlementTypeDescription` int(11) NOT NULL,
  `WarehouseCount` int(11) NOT NULL DEFAULT 0,
  `deliveryPeriod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `novaposhta_streets`
--

DROP TABLE IF EXISTS `novaposhta_streets`;
CREATE TABLE `novaposhta_streets` (
  `StreetID` int(11) NOT NULL,
  `Ref` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CityRef` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescriptionRu` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `StreetsTypeRef` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `StreetsType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `novaposhta_warehouses`
--

DROP TABLE IF EXISTS `novaposhta_warehouses`;
CREATE TABLE `novaposhta_warehouses` (
  `WarehouseID` int(11) NOT NULL,
  `SiteKey` int(11) NOT NULL,
  `Ref` varchar(36) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `DescriptionRu` varchar(500) NOT NULL,
  `ShortAddress` varchar(500) NOT NULL,
  `ShortAddressRu` varchar(500) NOT NULL,
  `TypeOfWarehouseRef` varchar(64) NOT NULL,
  `TypeOfWarehouse` varchar(36) NOT NULL,
  `TypeOfWarehouseRu` varchar(64) NOT NULL,
  `CityRef` varchar(36) NOT NULL,
  `CityDescription` varchar(200) NOT NULL,
  `CityDescriptionRu` varchar(200) NOT NULL,
  `Number` int(11) NOT NULL,
  `Phone` varchar(50) NOT NULL,
  `Longitude` double NOT NULL,
  `Latitude` double NOT NULL,
  `PostFinance` tinyint(1) NOT NULL,
  `BicycleParking` tinyint(1) NOT NULL,
  `PaymentAccess` tinyint(1) NOT NULL,
  `POSTerminal` tinyint(1) NOT NULL,
  `InternationalShipping` tinyint(1) NOT NULL,
  `TotalMaxWeightAllowed` int(11) NOT NULL,
  `PlaceMaxWeightAllowed` int(11) NOT NULL,
  `Reception` text NOT NULL,
  `Delivery` text NOT NULL,
  `Schedule` text NOT NULL,
  `DistrictCode` varchar(20) NOT NULL,
  `WarehouseStatus` varchar(20) NOT NULL,
  `CategoryOfWarehouse` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `novaposhta_zones`
--

DROP TABLE IF EXISTS `novaposhta_zones`;
CREATE TABLE `novaposhta_zones` (
  `ZoneID` int(11) NOT NULL,
  `Ref` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DescriptionRu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `AreasCenter` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option`
--

DROP TABLE IF EXISTS `ocfilter_option`;
CREATE TABLE `ocfilter_option` (
  `option_id` int(11) NOT NULL,
  `type` varchar(16) NOT NULL DEFAULT 'checkbox',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `selectbox` tinyint(1) NOT NULL DEFAULT 0,
  `grouping` tinyint(4) NOT NULL DEFAULT 0,
  `color` tinyint(1) NOT NULL DEFAULT 0,
  `image` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option_description`
--

DROP TABLE IF EXISTS `ocfilter_option_description`;
CREATE TABLE `ocfilter_option_description` (
  `option_id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `postfix` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option_to_category`
--

DROP TABLE IF EXISTS `ocfilter_option_to_category`;
CREATE TABLE `ocfilter_option_to_category` (
  `option_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option_to_store`
--

DROP TABLE IF EXISTS `ocfilter_option_to_store`;
CREATE TABLE `ocfilter_option_to_store` (
  `option_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option_value`
--

DROP TABLE IF EXISTS `ocfilter_option_value`;
CREATE TABLE `ocfilter_option_value` (
  `value_id` bigint(20) NOT NULL,
  `option_id` int(11) NOT NULL DEFAULT 0,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(6) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option_value_description`
--

DROP TABLE IF EXISTS `ocfilter_option_value_description`;
CREATE TABLE `ocfilter_option_value_description` (
  `value_id` bigint(20) NOT NULL,
  `option_id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option_value_to_product`
--

DROP TABLE IF EXISTS `ocfilter_option_value_to_product`;
CREATE TABLE `ocfilter_option_value_to_product` (
  `ocfilter_option_value_to_product_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `value_id` bigint(20) NOT NULL,
  `slide_value_min` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `slide_value_max` decimal(15,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_option_value_to_product_description`
--

DROP TABLE IF EXISTS `ocfilter_option_value_to_product_description`;
CREATE TABLE `ocfilter_option_value_to_product_description` (
  `product_id` int(11) NOT NULL,
  `value_id` bigint(20) NOT NULL,
  `option_id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ocfilter_page`
--

DROP TABLE IF EXISTS `ocfilter_page`;
CREATE TABLE `ocfilter_page` (
  `ocfilter_page_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `ocfilter_params` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `oc_feedback`
--

DROP TABLE IF EXISTS `oc_feedback`;
CREATE TABLE `oc_feedback` (
  `feedback_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `oc_sms_log`
--

DROP TABLE IF EXISTS `oc_sms_log`;
CREATE TABLE `oc_sms_log` (
  `id` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date_send` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `oc_yandex_category`
--

DROP TABLE IF EXISTS `oc_yandex_category`;
CREATE TABLE `oc_yandex_category` (
  `yandex_category_id` int(11) NOT NULL,
  `level1` varchar(50) NOT NULL,
  `level2` varchar(50) NOT NULL,
  `level3` varchar(50) NOT NULL,
  `level4` varchar(50) NOT NULL,
  `level5` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `odinass_product_queue`
--

DROP TABLE IF EXISTS `odinass_product_queue`;
CREATE TABLE `odinass_product_queue` (
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `option`
--

DROP TABLE IF EXISTS `option`;
CREATE TABLE `option` (
  `option_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `option_description`
--

DROP TABLE IF EXISTS `option_description`;
CREATE TABLE `option_description` (
  `option_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `option_tooltip`
--

DROP TABLE IF EXISTS `option_tooltip`;
CREATE TABLE `option_tooltip` (
  `option_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tooltip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `option_value`
--

DROP TABLE IF EXISTS `option_value`;
CREATE TABLE `option_value` (
  `option_value_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `option_value_description`
--

DROP TABLE IF EXISTS `option_value_description`;
CREATE TABLE `option_value_description` (
  `option_value_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int(11) NOT NULL COMMENT 'Номер заказа',
  `order_id2` varchar(30) NOT NULL COMMENT 'Вторичный номер заказа (не используется)',
  `part_num` varchar(100) NOT NULL COMMENT 'Текущий номер партии, в которой доставляется заказ',
  `invoice_no` int(11) NOT NULL DEFAULT 0 COMMENT 'Номер инвойса (не используется)',
  `invoice_prefix` varchar(26) NOT NULL COMMENT 'Префикс инвойса (не используется)',
  `invoice_filename` varchar(255) NOT NULL COMMENT 'Файл инвойса (не используется)',
  `invoice_date` date NOT NULL COMMENT 'Дата инвойса (не используется) ',
  `store_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Код виртуального магазина, в котором оформлен заказ',
  `store_name` varchar(64) NOT NULL COMMENT 'Название виртуального магазина, в котором оформлен заказ',
  `store_url` varchar(255) NOT NULL COMMENT 'URL виртуального магазина, в котором оформлен заказ ',
  `customer_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Идентификатор покупателя',
  `customer_group_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Идентификатор группы покупателя',
  `firstname` varchar(32) NOT NULL COMMENT 'Имя покупателя',
  `lastname` varchar(32) NOT NULL COMMENT 'Фамилия покупателя',
  `email` varchar(96) NOT NULL COMMENT 'Электронная почта покупателя',
  `telephone` varchar(32) NOT NULL COMMENT 'Номер телефона покупателя',
  `fax` varchar(32) NOT NULL COMMENT 'Дополнительный номер телефона',
  `faxname` varchar(64) NOT NULL COMMENT 'Контактное лицо дополнительного номера',
  `payment_firstname` varchar(32) NOT NULL COMMENT 'Имя плательщика',
  `payment_lastname` varchar(32) NOT NULL COMMENT 'Фамилия плательщика',
  `payment_company` varchar(32) NOT NULL COMMENT 'Название компании плательщика',
  `payment_company_id` varchar(32) NOT NULL COMMENT 'Идентификатор компании плательщика (не используется)',
  `payment_tax_id` varchar(32) NOT NULL COMMENT 'Налоговый класс плательщика (не используется)',
  `payment_address_1` varchar(500) NOT NULL COMMENT 'Адрес плательщика (строка 1)',
  `payment_address_struct` mediumtext NOT NULL COMMENT 'Разобранный адрес оплаты',
  `payment_address_2` varchar(500) NOT NULL COMMENT 'Адрес плательщика (строка 2) ',
  `payment_city` varchar(128) NOT NULL COMMENT 'Город плательщика',
  `payment_postcode` varchar(10) NOT NULL COMMENT 'Почтовый индекс плательщика',
  `payment_country` varchar(128) NOT NULL COMMENT 'Страна плательщика',
  `payment_country_id` int(11) NOT NULL COMMENT 'Идентификатор страны плательщика',
  `payment_zone` varchar(128) NOT NULL COMMENT 'Геозона плательщика (не используется) ',
  `payment_zone_id` int(11) NOT NULL COMMENT 'Идентификатор геозоны плательщика (не используется)',
  `payment_address_format` text NOT NULL COMMENT 'Формат адреса плательщика',
  `payment_method` varchar(128) NOT NULL COMMENT 'Метод оплаты (название)',
  `payment_code` varchar(128) NOT NULL COMMENT 'Метод оплаты (код)',
  `payment_secondary_method` varchar(255) NOT NULL COMMENT 'Вторичный метод оплаты (платежная система, название)',
  `payment_secondary_code` varchar(255) NOT NULL COMMENT 'Вторичный метод оплаты (платежная система, код)',
  `shipping_firstname` varchar(32) NOT NULL COMMENT 'Имя получателя',
  `shipping_lastname` varchar(32) NOT NULL COMMENT 'Фамилия получателя',
  `shipping_passport_serie` varchar(30) NOT NULL COMMENT 'Серия паспорта получателя',
  `shipping_passport_given` text NOT NULL COMMENT 'Кем выдан паспорт получателя',
  `shipping_company` varchar(32) NOT NULL COMMENT 'Название компании получателя',
  `shipping_address_1` varchar(500) NOT NULL COMMENT 'Адрес получателя (строка 1)',
  `shipping_address_struct` mediumtext NOT NULL,
  `shipping_address_2` varchar(500) NOT NULL COMMENT 'Адрес получателя (строка 2)',
  `shipping_city` varchar(128) NOT NULL COMMENT 'Город получателя',
  `shipping_postcode` varchar(10) NOT NULL COMMENT 'Индекс получателя',
  `shipping_country` varchar(128) NOT NULL COMMENT 'Страна получателя',
  `shipping_country_id` int(11) NOT NULL COMMENT 'Код страны получателя',
  `shipping_zone` varchar(128) NOT NULL COMMENT 'Геозона получателя (не используется)',
  `shipping_zone_id` int(11) NOT NULL COMMENT 'Идентификатор геозоны получателя (не используется)',
  `shipping_address_format` text NOT NULL COMMENT 'Формат адреса получателя',
  `shipping_method` varchar(512) DEFAULT NULL COMMENT 'Метод доставки (название)',
  `shipping_code` varchar(128) NOT NULL COMMENT 'Метод доставки (код)',
  `comment` longtext NOT NULL COMMENT 'Комментарий покупателя',
  `original_comment` longtext NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT 0.0000 COMMENT 'Итог в основной валюте (EUR)',
  `total_national` decimal(15,4) NOT NULL COMMENT 'Итог в национальной валюте',
  `prepayment` decimal(10,2) NOT NULL COMMENT 'Предоплата в основной валюте (не используется) ',
  `prepayment_national` decimal(15,4) NOT NULL COMMENT 'Предоплата в национальной валюте (не используется) ',
  `total_paid` tinyint(1) NOT NULL COMMENT 'Признак полной оплаты (не используется) ',
  `total_paid_date` datetime NOT NULL COMMENT 'Дата полной оплаты (не используется) ',
  `prepayment_paid` tinyint(1) NOT NULL COMMENT 'Признак оплаты предоплаты (не используется) ',
  `prepayment_paid_date` datetime NOT NULL COMMENT 'Дата оплаты предоплаты (не используется) ',
  `order_status_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Идентификатор статуса заказа (текущий статус заказ)',
  `affiliate_id` int(11) NOT NULL COMMENT 'Идентификатор партнера CPA',
  `commission` decimal(15,4) NOT NULL COMMENT 'Комиссия партнера CPA',
  `language_id` int(11) NOT NULL COMMENT 'Идентификатор языка',
  `currency_id` int(11) NOT NULL COMMENT 'Идентификатор валюты',
  `currency_code` varchar(3) NOT NULL COMMENT 'Код валюты ISO-3',
  `currency_value` decimal(15,8) NOT NULL DEFAULT 1.00000000 COMMENT 'Курс к основной валюте на момент оформления',
  `ip` varchar(40) NOT NULL COMMENT 'IP адрес клиента',
  `forwarded_ip` varchar(40) NOT NULL COMMENT 'Реальный IP адрес клиента',
  `user_agent` varchar(255) NOT NULL COMMENT 'Юзер-агент клиента',
  `accept_language` varchar(255) NOT NULL COMMENT 'Accept-Language клиента',
  `product_review_reminder` int(11) NOT NULL COMMENT 'Связь с напоминалкой о необходимости оставить отзыв',
  `weight` decimal(15,8) NOT NULL COMMENT 'Общий вес товаров',
  `date_added` datetime NOT NULL COMMENT 'Дата добавления',
  `date_added_timestamp` int(11) NOT NULL,
  `date_modified` datetime NOT NULL COMMENT 'Дата изменения',
  `date_maxpay` date NOT NULL COMMENT 'Дата оплаты до действительности действий',
  `date_delivery` date NOT NULL COMMENT 'Дата доставки ОТ',
  `date_delivery_to` date NOT NULL COMMENT 'Дата доставки ДО',
  `date_delivery_actual` date NOT NULL COMMENT 'ТОЧНАЯ дата доставки',
  `display_date_in_account` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Отображать дату доставки в кабинете клиента',
  `date_country` date NOT NULL COMMENT 'Дата поставки в страну',
  `date_buy` date NOT NULL COMMENT 'Дата покупки',
  `date_sent` date NOT NULL COMMENT 'Дата отправки',
  `manager_id` int(11) NOT NULL COMMENT 'Идентификатор менеджера',
  `courier_id` int(11) NOT NULL COMMENT 'Идентификатор курьера',
  `old_manager_id` int(11) DEFAULT NULL COMMENT 'Используется для переприсвоения заказа',
  `first_referrer` text DEFAULT NULL COMMENT 'HTTP REFERER первый',
  `last_referrer` text DEFAULT NULL COMMENT 'HTTP REFERER второй',
  `changed` int(11) NOT NULL DEFAULT 0 COMMENT 'Количество изменений заказа',
  `review_alert` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' 	Связь с напоминалкой о необходимости оставить отзыв',
  `ttn` varchar(255) NOT NULL COMMENT 'Текущая ТТН',
  `bottom_text` text NOT NULL COMMENT 'Полный текст внизу письма (условия доставки, оплаты) этого заказа',
  `paying_prepay` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Включить оплату предоплаты',
  `pay_equire` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Включить оплату PayKeeper',
  `pay_equire2` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Включить еще какой-то эквайринг (не используется) ',
  `pay_equirePP` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Включить оплату PayPal',
  `pay_equireLQP` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Включить оплату LiqPay',
  `pay_equireWPP` tinyint(1) NOT NULL DEFAULT 0,
  `pay_equireCP` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Включить оплату Concardis Payengine',
  `pay_type` varchar(50) NOT NULL COMMENT 'Тип оплаты (нал, безнал, банк)',
  `bill_file` varchar(255) NOT NULL COMMENT 'Файл счёта',
  `bill_file2` varchar(255) NOT NULL COMMENT 'Файл счета - 2',
  `reject_reason_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Идентификатор причины отмены',
  `urgent` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Заказ срочный',
  `urgent_buy` tinyint(1) NOT NULL COMMENT 'Срочно закупить',
  `wait_full` tinyint(1) NOT NULL COMMENT 'Клиент согласен ждать полный заказ',
  `from_waitlist` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Заказ оформлен из листа ожидания',
  `legalperson_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ИДентификатор нашего юрлица, на которое выставляется счет',
  `card_id` int(11) NOT NULL COMMENT 'Идентификатор карты, на которую приходит оплата',
  `probably_cancel` tinyint(1) DEFAULT 0 COMMENT 'Признак необходимости отмены',
  `probably_cancel_reason` varchar(500) NOT NULL COMMENT 'Причина необходимости отмены',
  `probably_close` tinyint(1) DEFAULT 0 COMMENT 'Необходимость закрытия',
  `probably_close_reason` varchar(500) NOT NULL COMMENT 'Причина необходимости закрытия',
  `probably_problem` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Признак проблемного заказа',
  `probably_problem_reason` varchar(500) NOT NULL COMMENT 'Причина проблемного заказа',
  `csi_reject` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Отказ от информации',
  `csi_mark` int(11) NOT NULL COMMENT 'Общая оценка',
  `csi_comment` text NOT NULL COMMENT 'Комментарий к общей оценке',
  `speed_mark` int(11) NOT NULL COMMENT 'Оценка сроков',
  `speed_comment` text NOT NULL COMMENT 'Комментарий к оценке сроков',
  `manager_mark` int(11) NOT NULL COMMENT 'Оценка обслуживания',
  `manager_comment` text NOT NULL COMMENT 'Комментарий к оценке менеджера',
  `quality_mark` int(11) NOT NULL COMMENT 'Оценка качества товара',
  `quality_comment` text NOT NULL COMMENT 'Комментарий к оценке качества',
  `courier_mark` int(11) NOT NULL COMMENT 'Оценка курьера',
  `courier_comment` text NOT NULL COMMENT 'Комментарий к оценке курьера',
  `csi_average` float(2,1) NOT NULL COMMENT 'Средняя оценка CSI',
  `salary_paid` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Выплачена зарплата менеджеру',
  `closed` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Закрыт / заблокирован',
  `ua_logistics` tinyint(1) NOT NULL COMMENT 'Логистика заказа через Украину',
  `tracker_xml` mediumtext NOT NULL COMMENT 'XML трекера из 1С',
  `tracking_id` varchar(255) NOT NULL COMMENT 'Трек-код для определения партнера CPA',
  `concardis_id` varchar(100) NOT NULL COMMENT 'Идентификатор заявки Concardis Payengine',
  `concardis_json` text DEFAULT NULL COMMENT 'JSON ответа Concardis Payengine',
  `template` varchar(24) NOT NULL COMMENT 'Идентификатор шаблона (не используется)',
  `preorder` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Это заявка на уточнение наличия и цены',
  `marketplace` varchar(20) NOT NULL,
  `pwa` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Заказ оформлен из PWA-приложения, либо из Android-приложения',
  `yam` tinyint(1) NOT NULL DEFAULT 0,
  `yam_id` int(11) NOT NULL,
  `yam_shipment_date` date NOT NULL,
  `yam_status` varchar(32) NOT NULL,
  `yam_substatus` varchar(64) NOT NULL,
  `yam_fake` tinyint(1) NOT NULL DEFAULT 0,
  `yam_shipment_id` int(11) NOT NULL,
  `yam_box_id` int(11) NOT NULL,
  `fcheque_link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_amazon`
--

DROP TABLE IF EXISTS `order_amazon`;
CREATE TABLE `order_amazon` (
  `order_id` int(11) NOT NULL,
  `amazon_order_id` varchar(255) NOT NULL,
  `free_shipping` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_amazon_product`
--

DROP TABLE IF EXISTS `order_amazon_product`;
CREATE TABLE `order_amazon_product` (
  `order_product_id` int(11) NOT NULL,
  `amazon_order_item_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_amazon_report`
--

DROP TABLE IF EXISTS `order_amazon_report`;
CREATE TABLE `order_amazon_report` (
  `order_id` int(11) NOT NULL,
  `submission_id` varchar(255) NOT NULL,
  `status` enum('processing','error','success') NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_courier_history`
--

DROP TABLE IF EXISTS `order_courier_history`;
CREATE TABLE `order_courier_history` (
  `order_id` int(11) NOT NULL,
  `courier_id` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `date_status` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_download`
--

DROP TABLE IF EXISTS `order_download`;
CREATE TABLE `order_download` (
  `order_download_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `mask` varchar(128) NOT NULL,
  `remaining` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_field`
--

DROP TABLE IF EXISTS `order_field`;
CREATE TABLE `order_field` (
  `order_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `custom_field_value_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `value` text NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_fraud`
--

DROP TABLE IF EXISTS `order_fraud`;
CREATE TABLE `order_fraud` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `country_match` varchar(3) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `high_risk_country` varchar(3) NOT NULL,
  `distance` int(11) NOT NULL,
  `ip_region` varchar(255) NOT NULL,
  `ip_city` varchar(255) NOT NULL,
  `ip_latitude` decimal(10,6) NOT NULL,
  `ip_longitude` decimal(10,6) NOT NULL,
  `ip_isp` varchar(255) NOT NULL,
  `ip_org` varchar(255) NOT NULL,
  `ip_asnum` int(11) NOT NULL,
  `ip_user_type` varchar(255) NOT NULL,
  `ip_country_confidence` varchar(3) NOT NULL,
  `ip_region_confidence` varchar(3) NOT NULL,
  `ip_city_confidence` varchar(3) NOT NULL,
  `ip_postal_confidence` varchar(3) NOT NULL,
  `ip_postal_code` varchar(10) NOT NULL,
  `ip_accuracy_radius` int(11) NOT NULL,
  `ip_net_speed_cell` varchar(255) NOT NULL,
  `ip_metro_code` int(11) NOT NULL,
  `ip_area_code` int(11) NOT NULL,
  `ip_time_zone` varchar(255) NOT NULL,
  `ip_region_name` varchar(255) NOT NULL,
  `ip_domain` varchar(255) NOT NULL,
  `ip_country_name` varchar(255) NOT NULL,
  `ip_continent_code` varchar(2) NOT NULL,
  `ip_corporate_proxy` varchar(3) NOT NULL,
  `anonymous_proxy` varchar(3) NOT NULL,
  `proxy_score` int(11) NOT NULL,
  `is_trans_proxy` varchar(3) NOT NULL,
  `free_mail` varchar(3) NOT NULL,
  `carder_email` varchar(3) NOT NULL,
  `high_risk_username` varchar(3) NOT NULL,
  `high_risk_password` varchar(3) NOT NULL,
  `bin_match` varchar(10) NOT NULL,
  `bin_country` varchar(2) NOT NULL,
  `bin_name_match` varchar(3) NOT NULL,
  `bin_name` varchar(255) NOT NULL,
  `bin_phone_match` varchar(3) NOT NULL,
  `bin_phone` varchar(32) NOT NULL,
  `customer_phone_in_billing_location` varchar(8) NOT NULL,
  `ship_forward` varchar(3) NOT NULL,
  `city_postal_match` varchar(3) NOT NULL,
  `ship_city_postal_match` varchar(3) NOT NULL,
  `score` decimal(10,5) NOT NULL,
  `explanation` text NOT NULL,
  `risk_score` decimal(10,5) NOT NULL,
  `queries_remaining` int(11) NOT NULL,
  `maxmind_id` varchar(8) NOT NULL,
  `error` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_history`
--

DROP TABLE IF EXISTS `order_history`;
CREATE TABLE `order_history` (
  `order_history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `notify` tinyint(1) NOT NULL DEFAULT 0,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `courier` tinyint(1) NOT NULL,
  `yam_status` varchar(32) NOT NULL,
  `yam_substatus` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_invoice_history`
--

DROP TABLE IF EXISTS `order_invoice_history`;
CREATE TABLE `order_invoice_history` (
  `order_invoice_id` bigint(20) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_name` varchar(50) NOT NULL,
  `html` mediumtext NOT NULL,
  `datetime` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_option`
--

DROP TABLE IF EXISTS `order_option`;
CREATE TABLE `order_option` (
  `order_option_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `product_option_id` int(11) NOT NULL,
  `product_option_value_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product`
--

DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product` (
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ao_id` int(11) NOT NULL,
  `ao_product_id` int(11) NOT NULL,
  `good` tinyint(1) NOT NULL DEFAULT 1,
  `taken` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_num` int(11) NOT NULL DEFAULT 1,
  `part_num` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(11) NOT NULL,
  `initial_quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `pricewd_national` decimal(15,2) NOT NULL,
  `price_national` decimal(15,2) NOT NULL,
  `original_price_national` decimal(15,2) NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `totalwd_national` decimal(15,2) NOT NULL,
  `total_national` decimal(15,2) NOT NULL,
  `tax` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `reward` int(11) NOT NULL,
  `reward_one` int(11) NOT NULL,
  `source` varchar(500) NOT NULL,
  `from_stock` tinyint(1) NOT NULL DEFAULT 0,
  `from_bd_gift` tinyint(1) NOT NULL DEFAULT 0,
  `is_returned` tinyint(1) NOT NULL DEFAULT 0,
  `quantity_from_stock` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `totals_json` text NOT NULL,
  `date_added_fo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product_bought`
--

DROP TABLE IF EXISTS `order_product_bought`;
CREATE TABLE `order_product_bought` (
  `bought_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `supplier` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product_history`
--

DROP TABLE IF EXISTS `order_product_history`;
CREATE TABLE `order_product_history` (
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ao_id` int(11) NOT NULL,
  `good` tinyint(1) NOT NULL DEFAULT 1,
  `taken` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_num` int(11) NOT NULL DEFAULT 1,
  `part_num` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `price_national` decimal(15,2) NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `total_national` decimal(15,2) NOT NULL,
  `tax` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `reward` int(11) NOT NULL,
  `from_stock` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product_nogood`
--

DROP TABLE IF EXISTS `order_product_nogood`;
CREATE TABLE `order_product_nogood` (
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `is_prewaitlist` tinyint(1) DEFAULT NULL,
  `telephone` varchar(25) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `date_added` datetime NOT NULL,
  `ao_id` int(11) NOT NULL,
  `ao_product_id` int(11) NOT NULL,
  `good` tinyint(1) NOT NULL DEFAULT 1,
  `taken` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `price_national` decimal(15,2) NOT NULL,
  `original_price_national` decimal(15,2) NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `total_national` decimal(15,2) NOT NULL,
  `tax` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `reward` int(11) NOT NULL,
  `waitlist` tinyint(1) NOT NULL DEFAULT 1,
  `supplier_has` tinyint(1) NOT NULL DEFAULT 0,
  `new_order_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product_reserves`
--

DROP TABLE IF EXISTS `order_product_reserves`;
CREATE TABLE `order_product_reserves` (
  `order_reserve_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `not_changeable` tinyint(1) NOT NULL DEFAULT 0,
  `uuid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product_supply`
--

DROP TABLE IF EXISTS `order_product_supply`;
CREATE TABLE `order_product_supply` (
  `order_product_supply_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `order_set_id` int(11) NOT NULL,
  `set_id` int(11) DEFAULT 0,
  `is_for_order` tinyint(1) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `url` text NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product_tracker`
--

DROP TABLE IF EXISTS `order_product_tracker`;
CREATE TABLE `order_product_tracker` (
  `order_product_tracker_id` int(11) NOT NULL,
  `order_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_product_status` varchar(255) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_product_untaken`
--

DROP TABLE IF EXISTS `order_product_untaken`;
CREATE TABLE `order_product_untaken` (
  `order_product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ao_id` int(11) NOT NULL,
  `ao_product_id` int(11) NOT NULL,
  `good` tinyint(1) NOT NULL DEFAULT 1,
  `taken` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_num` int(11) NOT NULL DEFAULT 1,
  `part_num` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(11) NOT NULL,
  `initial_quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `pricewd_national` decimal(15,2) NOT NULL,
  `price_national` decimal(15,2) NOT NULL,
  `original_price_national` decimal(15,2) NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `totalwd_national` decimal(15,2) NOT NULL,
  `total_national` decimal(15,2) NOT NULL,
  `tax` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `reward` int(11) NOT NULL,
  `reward_one` int(11) NOT NULL,
  `source` varchar(500) NOT NULL,
  `from_stock` tinyint(1) NOT NULL DEFAULT 0,
  `from_bd_gift` tinyint(1) NOT NULL DEFAULT 0,
  `is_returned` tinyint(1) NOT NULL DEFAULT 0,
  `quantity_from_stock` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `totals_json` text NOT NULL,
  `date_added_fo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_recurring`
--

DROP TABLE IF EXISTS `order_recurring`;
CREATE TABLE `order_recurring` (
  `order_recurring_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `profile_name` varchar(255) NOT NULL,
  `profile_description` varchar(255) NOT NULL,
  `recurring_frequency` varchar(25) NOT NULL,
  `recurring_cycle` smallint(6) NOT NULL,
  `recurring_duration` smallint(6) NOT NULL,
  `recurring_price` decimal(10,4) NOT NULL,
  `trial` tinyint(1) NOT NULL,
  `trial_frequency` varchar(25) NOT NULL,
  `trial_cycle` smallint(6) NOT NULL,
  `trial_duration` smallint(6) NOT NULL,
  `trial_price` decimal(10,4) NOT NULL,
  `profile_reference` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_recurring_transaction`
--

DROP TABLE IF EXISTS `order_recurring_transaction`;
CREATE TABLE `order_recurring_transaction` (
  `order_recurring_transaction_id` int(11) NOT NULL,
  `order_recurring_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `amount` decimal(10,4) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_reject_reason`
--

DROP TABLE IF EXISTS `order_reject_reason`;
CREATE TABLE `order_reject_reason` (
  `reject_reason_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_related`
--

DROP TABLE IF EXISTS `order_related`;
CREATE TABLE `order_related` (
  `order_id` int(11) NOT NULL,
  `related_order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_save_history`
--

DROP TABLE IF EXISTS `order_save_history`;
CREATE TABLE `order_save_history` (
  `order_save_id` bigint(20) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_set`
--

DROP TABLE IF EXISTS `order_set`;
CREATE TABLE `order_set` (
  `order_set_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `set_product_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `good` tinyint(1) NOT NULL DEFAULT 1,
  `taken` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_num` int(11) NOT NULL DEFAULT 1,
  `part_num` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `pricewd_national` decimal(15,2) NOT NULL,
  `price_national` decimal(15,2) NOT NULL,
  `original_price_national` decimal(15,4) NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `totalwd_national` decimal(15,2) NOT NULL,
  `total_national` decimal(15,2) NOT NULL,
  `tax` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `reward` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_simple_fields`
--

DROP TABLE IF EXISTS `order_simple_fields`;
CREATE TABLE `order_simple_fields` (
  `order_id` int(11) NOT NULL,
  `metadata` text DEFAULT NULL,
  `courier_city_shipping_address` text DEFAULT NULL,
  `courier_city_dadata_unrestricted_value` text DEFAULT NULL,
  `courier_city_dadata_beltway_hit` text DEFAULT NULL,
  `courier_city_dadata_beltway_distance` text DEFAULT NULL,
  `courier_city_dadata_geolocation` text DEFAULT NULL,
  `courier_city_dadata_postalcode` text DEFAULT NULL,
  `novaposhta_warehouse` text DEFAULT NULL,
  `novaposhta_city_guid` varchar(256) DEFAULT NULL,
  `novaposhta_street` text DEFAULT NULL,
  `novaposhta_house_number` text DEFAULT NULL,
  `novaposhta_flat` text DEFAULT NULL,
  `cdek_city_guid` varchar(256) DEFAULT NULL,
  `cdek_warehouse` text DEFAULT NULL,
  `cdek_street` text DEFAULT NULL,
  `cdek_house_number` text DEFAULT NULL,
  `cdek_flat` text DEFAULT NULL,
  `justin_warehouse` text DEFAULT NULL,
  `ukrpost_postcode` text DEFAULT NULL,
  `test_field` text DEFAULT NULL,
  `newsletter_news` int(11) NOT NULL,
  `newsletter_personal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_sms_history`
--

DROP TABLE IF EXISTS `order_sms_history`;
CREATE TABLE `order_sms_history` (
  `order_history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `sms_status` varchar(32) NOT NULL,
  `sms_id` varchar(40) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_ttn` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_status`
--

DROP TABLE IF EXISTS `order_status`;
CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `status_bg_color` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status_txt_color` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status_fa_icon` varchar(50) NOT NULL,
  `front_bg_color` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_status_linked`
--

DROP TABLE IF EXISTS `order_status_linked`;
CREATE TABLE `order_status_linked` (
  `order_status_id` int(11) NOT NULL,
  `linked_order_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_total`
--

DROP TABLE IF EXISTS `order_total`;
CREATE TABLE `order_total` (
  `order_total_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(512) DEFAULT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `value_national` decimal(15,4) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `for_delivery` smallint(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_total_tax`
--

DROP TABLE IF EXISTS `order_total_tax`;
CREATE TABLE `order_total_tax` (
  `order_total_id` int(11) NOT NULL DEFAULT 0,
  `code` varchar(255) DEFAULT NULL,
  `tax` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_to_1c_queue`
--

DROP TABLE IF EXISTS `order_to_1c_queue`;
CREATE TABLE `order_to_1c_queue` (
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_tracker`
--

DROP TABLE IF EXISTS `order_tracker`;
CREATE TABLE `order_tracker` (
  `order_tracker_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_tracker_sms`
--

DROP TABLE IF EXISTS `order_tracker_sms`;
CREATE TABLE `order_tracker_sms` (
  `tracker_sms_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `partie_num` varchar(10) NOT NULL,
  `tracker_type` varchar(20) NOT NULL,
  `date_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_ttns`
--

DROP TABLE IF EXISTS `order_ttns`;
CREATE TABLE `order_ttns` (
  `order_ttn_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `ttn` varchar(255) NOT NULL,
  `date_ttn` date NOT NULL,
  `sms_sent` datetime NOT NULL,
  `delivery_code` varchar(55) NOT NULL,
  `taken` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `order_voucher`
--

DROP TABLE IF EXISTS `order_voucher`;
CREATE TABLE `order_voucher` (
  `order_voucher_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `from_name` varchar(64) NOT NULL,
  `from_email` varchar(96) NOT NULL,
  `to_name` varchar(64) NOT NULL,
  `to_email` varchar(96) NOT NULL,
  `voucher_theme_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `amount` decimal(15,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `parser_queue`
--

DROP TABLE IF EXISTS `parser_queue`;
CREATE TABLE `parser_queue` (
  `parser_queue_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `processed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `pavoslidergroups`
--

DROP TABLE IF EXISTS `pavoslidergroups`;
CREATE TABLE `pavoslidergroups` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `params` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `pavosliderlayers`
--

DROP TABLE IF EXISTS `pavosliderlayers`;
CREATE TABLE `pavosliderlayers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `params` text NOT NULL,
  `layersparams` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `paypal_iframe_order`
--

DROP TABLE IF EXISTS `paypal_iframe_order`;
CREATE TABLE `paypal_iframe_order` (
  `paypal_iframe_order_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `capture_status` enum('Complete','NotComplete') DEFAULT NULL,
  `currency_code` char(3) NOT NULL,
  `authorization_id` varchar(30) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `paypal_iframe_order_transaction`
--

DROP TABLE IF EXISTS `paypal_iframe_order_transaction`;
CREATE TABLE `paypal_iframe_order_transaction` (
  `paypal_iframe_order_transaction_id` int(11) NOT NULL,
  `paypal_iframe_order_id` int(11) NOT NULL,
  `transaction_id` char(20) NOT NULL,
  `parent_transaction_id` char(20) NOT NULL,
  `created` datetime NOT NULL,
  `note` varchar(255) NOT NULL,
  `msgsubid` char(38) NOT NULL,
  `receipt_id` char(20) NOT NULL,
  `payment_type` enum('none','echeck','instant','refund','void') DEFAULT NULL,
  `payment_status` char(20) NOT NULL,
  `pending_reason` char(50) NOT NULL,
  `transaction_entity` char(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `debug_data` text NOT NULL,
  `call_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `paypal_order`
--

DROP TABLE IF EXISTS `paypal_order`;
CREATE TABLE `paypal_order` (
  `paypal_order_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `capture_status` enum('Complete','NotComplete') DEFAULT NULL,
  `currency_code` char(3) NOT NULL,
  `authorization_id` varchar(30) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `paypal_order_transaction`
--

DROP TABLE IF EXISTS `paypal_order_transaction`;
CREATE TABLE `paypal_order_transaction` (
  `paypal_order_transaction_id` int(11) NOT NULL,
  `paypal_order_id` int(11) NOT NULL,
  `transaction_id` char(20) NOT NULL,
  `parent_transaction_id` char(20) NOT NULL,
  `created` datetime NOT NULL,
  `note` varchar(255) NOT NULL,
  `msgsubid` char(38) NOT NULL,
  `receipt_id` char(20) NOT NULL,
  `payment_type` enum('none','echeck','instant','refund','void') DEFAULT NULL,
  `payment_status` char(20) NOT NULL,
  `pending_reason` char(50) NOT NULL,
  `transaction_entity` char(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `debug_data` text NOT NULL,
  `call_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `priceva_data`
--

DROP TABLE IF EXISTS `priceva_data`;
CREATE TABLE `priceva_data` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `articul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_price` decimal(15,2) NOT NULL,
  `default_available` tinyint(1) NOT NULL,
  `default_discount` decimal(15,2) NOT NULL,
  `repricing_min` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `priceva_sources`
--

DROP TABLE IF EXISTS `priceva_sources`;
CREATE TABLE `priceva_sources` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `url` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_md5` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_check_date` datetime NOT NULL,
  `relevance_status` int(11) NOT NULL DEFAULT 0,
  `price` decimal(15,2) NOT NULL,
  `in_stock` tinyint(1) NOT NULL,
  `discount_type` int(11) NOT NULL,
  `discount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `model` varchar(64) NOT NULL COMMENT 'Артикул или модель',
  `short_name` varchar(50) NOT NULL COMMENT 'Короткое название',
  `short_name_de` varchar(50) NOT NULL,
  `sku` varchar(64) NOT NULL COMMENT 'Артикул SKU',
  `upc` varchar(255) NOT NULL,
  `ean` varchar(50) NOT NULL,
  `jan` varchar(13) NOT NULL,
  `virtual_isbn` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `mpn` varchar(255) NOT NULL,
  `asin` varchar(255) NOT NULL,
  `old_asin` varchar(32) NOT NULL,
  `location` varchar(128) NOT NULL,
  `source` text NOT NULL,
  `competitors` text NOT NULL,
  `competitors_ua` text NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `quantity_stock` int(11) NOT NULL DEFAULT 0,
  `quantity_stockM` int(11) NOT NULL DEFAULT 0,
  `quantity_stockK` int(11) NOT NULL DEFAULT 0,
  `quantity_stockMN` int(11) NOT NULL DEFAULT 0,
  `quantity_stockAS` int(11) NOT NULL DEFAULT 0,
  `quantity_updateMarker` tinyint(1) NOT NULL DEFAULT 0,
  `quantity_stock_onway` int(11) NOT NULL DEFAULT 0,
  `quantity_stockK_onway` int(11) NOT NULL DEFAULT 0,
  `quantity_stockM_onway` int(11) NOT NULL DEFAULT 0,
  `quantity_stockMN_onway` int(11) NOT NULL DEFAULT 0,
  `quantity_stockAS_onway` int(11) NOT NULL DEFAULT 0,
  `stock_status_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `collection_id` bigint(20) NOT NULL,
  `shipping` tinyint(1) NOT NULL DEFAULT 1,
  `cost` decimal(15,2) NOT NULL,
  `actual_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `actual_cost_date` date NOT NULL DEFAULT '0000-00-00',
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_national` decimal(15,2) NOT NULL,
  `mpp_price` decimal(15,2) NOT NULL,
  `yam_price` decimal(15,2) NOT NULL,
  `yam_special` decimal(15,2) NOT NULL,
  `yam_recprice` decimal(15,2) NOT NULL,
  `yam_percent` decimal(15,2) NOT NULL DEFAULT 0.00,
  `yam_special_percent` decimal(15,2) NOT NULL,
  `yam_currency` varchar(3) NOT NULL DEFAULT 'RUB',
  `currency` varchar(5) NOT NULL,
  `historical_price` decimal(15,4) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `points_only_purchase` tinyint(1) NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `date_available` date NOT NULL,
  `weight` decimal(15,4) NOT NULL DEFAULT 0.0000 COMMENT 'Вес НЕТТО',
  `weight_class_id` int(11) NOT NULL DEFAULT 1 COMMENT 'Идентификатор единицы измерения веса НЕТТО',
  `weight_amazon_key` varchar(100) NOT NULL,
  `length` decimal(15,8) NOT NULL DEFAULT 0.00000000 COMMENT 'Длинна НЕТТО',
  `width` decimal(15,8) NOT NULL DEFAULT 0.00000000 COMMENT 'Ширина НЕТТО',
  `height` decimal(15,8) NOT NULL DEFAULT 0.00000000 COMMENT 'Высота НЕТТО',
  `length_class_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Идентификатор единицы измерения РАЗМЕРА НЕТТО',
  `length_amazon_key` varchar(100) NOT NULL,
  `pack_weight` decimal(15,8) NOT NULL COMMENT 'Вес БРУТТО',
  `pack_weight_class_id` int(11) NOT NULL COMMENT 'Идентификатор единицы измерения ВЕСА БРУТТО',
  `pack_weight_amazon_key` varchar(100) NOT NULL,
  `pack_length` decimal(15,8) NOT NULL COMMENT 'Длинна БРУТТО',
  `pack_width` decimal(15,8) NOT NULL COMMENT 'Ширина БРУТТО',
  `pack_height` decimal(15,8) NOT NULL COMMENT 'Высота БРУТТО',
  `pack_length_class_id` int(11) NOT NULL COMMENT 'Идентификатор единицы измерения РАЗМЕРА БРУТТО',
  `pack_length_amazon_key` varchar(100) NOT NULL,
  `subtract` tinyint(1) NOT NULL DEFAULT 1,
  `minimum` int(11) NOT NULL DEFAULT 1,
  `package` int(11) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `viewed` int(11) NOT NULL DEFAULT 0,
  `youtube` text NOT NULL,
  `special_cost` decimal(15,2) NOT NULL,
  `historical_cost` decimal(15,2) NOT NULL,
  `parser_price` decimal(15,2) NOT NULL,
  `parser_special_price` decimal(15,2) NOT NULL,
  `skip` int(11) NOT NULL,
  `tnved` varchar(255) NOT NULL,
  `ignore_parse` tinyint(1) NOT NULL,
  `new` tinyint(1) NOT NULL DEFAULT 0,
  `stock_product_id` int(11) NOT NULL,
  `is_option_with_id` int(11) NOT NULL,
  `is_option_for_product_id` int(11) NOT NULL,
  `color_group` varchar(100) NOT NULL,
  `is_virtual` tinyint(1) NOT NULL,
  `is_related_set` tinyint(1) NOT NULL,
  `has_child` tinyint(1) NOT NULL,
  `ignore_parse_date_to` date NOT NULL,
  `new_date_to` date NOT NULL,
  `min_buy` int(11) NOT NULL,
  `max_buy` int(11) NOT NULL,
  `can_be_presented` tinyint(1) NOT NULL,
  `bought_for_week` int(11) NOT NULL,
  `bought_for_month` int(11) NOT NULL,
  `big_business` tinyint(1) NOT NULL DEFAULT 0,
  `is_markdown` int(11) NOT NULL,
  `markdown_product_id` int(11) NOT NULL,
  `lock_points` tinyint(1) NOT NULL DEFAULT 0,
  `yam_disable` tinyint(1) NOT NULL DEFAULT 0,
  `yam_product_id` varchar(32) NOT NULL,
  `yam_marketSku` int(11) NOT NULL,
  `priceva_enable` tinyint(1) NOT NULL DEFAULT 0,
  `priceva_disable` tinyint(1) NOT NULL DEFAULT 0,
  `yam_in_feed` tinyint(1) NOT NULL DEFAULT 0,
  `ozon_in_feed` tinyint(1) NOT NULL DEFAULT 0,
  `yam_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `yam_not_created` tinyint(1) NOT NULL DEFAULT 0,
  `is_illiquid` tinyint(1) NOT NULL DEFAULT 0,
  `amzn_invalid_asin` tinyint(1) NOT NULL DEFAULT 0,
  `amzn_not_found` tinyint(1) DEFAULT 0 COMMENT 'Товар не найден на Amazon',
  `amzn_last_search` date DEFAULT NULL COMMENT 'Дата последнего обновления данных Amazon',
  `amzn_no_offers` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Нет предложений на Amazon',
  `amzn_no_offers_counter` int(11) NOT NULL DEFAULT 0,
  `amzn_offers_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Количество офферов на Amazon',
  `amzn_last_offers` datetime NOT NULL COMMENT 'Дата и время последнего обновления офферов Amazon',
  `amzn_ignore` tinyint(1) NOT NULL COMMENT 'Не обновлять цену с Amazon',
  `amzn_rating` decimal(15,2) NOT NULL,
  `amazon_best_price` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Лучший оффер на Amazon',
  `amazon_lowest_price` decimal(15,2) NOT NULL,
  `added_from_amazon` tinyint(1) NOT NULL DEFAULT 0,
  `fill_from_amazon` tinyint(1) NOT NULL DEFAULT 0,
  `filled_from_amazon` tinyint(1) NOT NULL DEFAULT 0,
  `description_filled_from_amazon` tinyint(4) NOT NULL DEFAULT 0,
  `amazon_product_link` varchar(1024) DEFAULT NULL,
  `amazon_product_image` varchar(1024) DEFAULT NULL,
  `main_variant_id` int(11) DEFAULT 0,
  `variant_1_is_color` tinyint(1) DEFAULT 0,
  `variant_2_is_color` tinyint(1) DEFAULT 0,
  `display_in_catalog` tinyint(1) DEFAULT 0,
  `reviews_parsed` tinyint(1) NOT NULL DEFAULT 0,
  `xrating` decimal(15,2) DEFAULT 0.00,
  `xreviews` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_additional_offer`
--

DROP TABLE IF EXISTS `product_additional_offer`;
CREATE TABLE `product_additional_offer` (
  `product_additional_offer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ao_group` varchar(100) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `ao_product_id` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `percent` int(11) NOT NULL DEFAULT 0,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_additional_offer_to_store`
--

DROP TABLE IF EXISTS `product_additional_offer_to_store`;
CREATE TABLE `product_additional_offer_to_store` (
  `product_additional_offer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_also_bought`
--

DROP TABLE IF EXISTS `product_also_bought`;
CREATE TABLE `product_also_bought` (
  `product_id` int(11) NOT NULL,
  `also_bought_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_also_viewed`
--

DROP TABLE IF EXISTS `product_also_viewed`;
CREATE TABLE `product_also_viewed` (
  `product_id` int(11) NOT NULL,
  `also_viewed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_amzn_data`
--

DROP TABLE IF EXISTS `product_amzn_data`;
CREATE TABLE `product_amzn_data` (
  `product_id` int(11) NOT NULL,
  `asin` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `file` varchar(512) COLLATE utf8mb3_bin NOT NULL,
  `json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `product_amzn_offers`
--

DROP TABLE IF EXISTS `product_amzn_offers`;
CREATE TABLE `product_amzn_offers` (
  `amazon_offer_id` int(11) NOT NULL,
  `asin` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priceCurrency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priceAmount` decimal(15,2) NOT NULL,
  `importFeeCurrency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importFeeAmount` decimal(15,2) NOT NULL,
  `deliveryCurrency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deliveryAmount` decimal(15,2) NOT NULL,
  `deliveryIsFree` tinyint(1) NOT NULL,
  `deliveryIsFba` tinyint(1) NOT NULL,
  `deliveryIsShippedCrossBorder` tinyint(1) NOT NULL,
  `deliveryComments` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minDays` int(11) NOT NULL,
  `deliveryFrom` date NOT NULL,
  `deliveryTo` date NOT NULL,
  `conditionIsNew` tinyint(1) NOT NULL,
  `conditionTitle` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conditionComments` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sellerName` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sellerLink` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sellerRating50` int(11) NOT NULL,
  `sellerRatingsTotal` int(11) NOT NULL,
  `sellerPositiveRatings100` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `is_min_price` tinyint(1) NOT NULL,
  `isPrime` tinyint(1) NOT NULL,
  `isBuyBoxWinner` tinyint(1) NOT NULL DEFAULT 0,
  `isBestOffer` tinyint(1) NOT NULL,
  `offerRating` decimal(15,2) NOT NULL,
  `offer_id` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_anyrelated`
--

DROP TABLE IF EXISTS `product_anyrelated`;
CREATE TABLE `product_anyrelated` (
  `product_id` int(11) NOT NULL,
  `anyrelated_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_attribute`
--

DROP TABLE IF EXISTS `product_attribute`;
CREATE TABLE `product_attribute` (
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `text` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_barbara_tab`
--

DROP TABLE IF EXISTS `product_barbara_tab`;
CREATE TABLE `product_barbara_tab` (
  `product_additional_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_child`
--

DROP TABLE IF EXISTS `product_child`;
CREATE TABLE `product_child` (
  `product_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_costs`
--

DROP TABLE IF EXISTS `product_costs`;
CREATE TABLE `product_costs` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `cost` decimal(15,2) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_sale_price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_description`
--

DROP TABLE IF EXISTS `product_description`;
CREATE TABLE `product_description` (
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `short_name_d` varchar(50) NOT NULL,
  `name_of_option` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `markdown_appearance` text NOT NULL,
  `markdown_condition` text NOT NULL,
  `markdown_pack` text NOT NULL,
  `markdown_equipment` text NOT NULL,
  `color` varchar(512) DEFAULT NULL,
  `material` varchar(512) DEFAULT NULL,
  `variant_name` varchar(512) NOT NULL,
  `variant_name_1` varchar(512) NOT NULL,
  `variant_name_2` varchar(512) DEFAULT NULL,
  `variant_value_1` varchar(512) DEFAULT NULL,
  `variant_value_2` varchar(512) DEFAULT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_h1` varchar(255) NOT NULL,
  `tag` text NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0,
  `translated` tinyint(1) NOT NULL DEFAULT 0,
  `alt_image` text DEFAULT NULL,
  `title_image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_discount`
--

DROP TABLE IF EXISTS `product_discount`;
CREATE TABLE `product_discount` (
  `product_discount_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `priority` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `points` decimal(15,4) NOT NULL,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_filter`
--

DROP TABLE IF EXISTS `product_filter`;
CREATE TABLE `product_filter` (
  `product_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_front_price`
--

DROP TABLE IF EXISTS `product_front_price`;
CREATE TABLE `product_front_price` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `special` decimal(15,2) NOT NULL,
  `reward` decimal(15,2) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_image`
--

DROP TABLE IF EXISTS `product_image`;
CREATE TABLE `product_image` (
  `product_image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_master`
--

DROP TABLE IF EXISTS `product_master`;
CREATE TABLE `product_master` (
  `master_product_id` int(11) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_option`
--

DROP TABLE IF EXISTS `product_option`;
CREATE TABLE `product_option` (
  `product_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `option_value` text NOT NULL,
  `required` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_option_value`
--

DROP TABLE IF EXISTS `product_option_value`;
CREATE TABLE `product_option_value` (
  `product_option_value_id` int(11) NOT NULL,
  `product_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `option_value_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtract` tinyint(1) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `price_prefix` varchar(1) NOT NULL,
  `points` int(11) NOT NULL,
  `points_prefix` varchar(1) NOT NULL,
  `weight` decimal(15,8) NOT NULL,
  `weight_prefix` varchar(1) NOT NULL,
  `ob_sku` varchar(64) NOT NULL DEFAULT '',
  `ob_info` varchar(255) NOT NULL DEFAULT '',
  `ob_image` varchar(255) NOT NULL DEFAULT '',
  `ob_sku_override` int(11) NOT NULL DEFAULT 0,
  `this_is_product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_price_history`
--

DROP TABLE IF EXISTS `product_price_history`;
CREATE TABLE `product_price_history` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_price_national_to_store`
--

DROP TABLE IF EXISTS `product_price_national_to_store`;
CREATE TABLE `product_price_national_to_store` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `special` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_price_national_to_store1`
--

DROP TABLE IF EXISTS `product_price_national_to_store1`;
CREATE TABLE `product_price_national_to_store1` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `special` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_price_national_to_yam`
--

DROP TABLE IF EXISTS `product_price_national_to_yam`;
CREATE TABLE `product_price_national_to_yam` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `special` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_price_to_store`
--

DROP TABLE IF EXISTS `product_price_to_store`;
CREATE TABLE `product_price_to_store` (
  `product_id` bigint(20) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `special` decimal(15,2) NOT NULL,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_product_option`
--

DROP TABLE IF EXISTS `product_product_option`;
CREATE TABLE `product_product_option` (
  `product_product_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_product_option_value`
--

DROP TABLE IF EXISTS `product_product_option_value`;
CREATE TABLE `product_product_option_value` (
  `product_product_option_value_id` int(11) NOT NULL,
  `product_product_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_option_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_profile`
--

DROP TABLE IF EXISTS `product_profile`;
CREATE TABLE `product_profile` (
  `product_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_purchase`
--

DROP TABLE IF EXISTS `product_purchase`;
CREATE TABLE `product_purchase` (
  `purchase_uuid` varchar(64) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_name` varchar(256) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_recurring`
--

DROP TABLE IF EXISTS `product_recurring`;
CREATE TABLE `product_recurring` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_related`
--

DROP TABLE IF EXISTS `product_related`;
CREATE TABLE `product_related` (
  `product_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_related_set`
--

DROP TABLE IF EXISTS `product_related_set`;
CREATE TABLE `product_related_set` (
  `product_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_reward`
--

DROP TABLE IF EXISTS `product_reward`;
CREATE TABLE `product_reward` (
  `product_reward_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `customer_group_id` int(11) NOT NULL DEFAULT 0,
  `store_id` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `percent` int(11) NOT NULL,
  `max_percent` int(11) NOT NULL,
  `coupon_acts` tinyint(1) NOT NULL DEFAULT 0,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_shop_by_look`
--

DROP TABLE IF EXISTS `product_shop_by_look`;
CREATE TABLE `product_shop_by_look` (
  `product_id` int(11) NOT NULL,
  `shop_by_look_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_similar`
--

DROP TABLE IF EXISTS `product_similar`;
CREATE TABLE `product_similar` (
  `product_id` int(11) NOT NULL,
  `similar_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_similar_to_consider`
--

DROP TABLE IF EXISTS `product_similar_to_consider`;
CREATE TABLE `product_similar_to_consider` (
  `product_id` int(11) NOT NULL,
  `similar_to_consider_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_sources`
--

DROP TABLE IF EXISTS `product_sources`;
CREATE TABLE `product_sources` (
  `product_source_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `source` varchar(500) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_special`
--

DROP TABLE IF EXISTS `product_special`;
CREATE TABLE `product_special` (
  `product_special_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL DEFAULT 1,
  `priority` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `old_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `store_id` int(11) NOT NULL DEFAULT -1,
  `currency_scode` varchar(3) DEFAULT NULL,
  `points_special` decimal(15,2) NOT NULL DEFAULT 0.00,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `parser_info_price` date NOT NULL DEFAULT '0000-00-00',
  `set_by_stock` tinyint(1) NOT NULL DEFAULT 0,
  `set_by_stock_illiquid` tinyint(1) NOT NULL DEFAULT 0,
  `date_settled_by_stock` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_special_attribute`
--

DROP TABLE IF EXISTS `product_special_attribute`;
CREATE TABLE `product_special_attribute` (
  `product_special_attribute_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `product_special_backup`
--

DROP TABLE IF EXISTS `product_special_backup`;
CREATE TABLE `product_special_backup` (
  `product_special_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `old_price` decimal(15,4) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT -1,
  `points_special` decimal(15,4) NOT NULL,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `parser_info_price` date NOT NULL,
  `set_by_stock` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_sponsored`
--

DROP TABLE IF EXISTS `product_sponsored`;
CREATE TABLE `product_sponsored` (
  `product_id` int(11) NOT NULL,
  `sponsored_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_status`
--

DROP TABLE IF EXISTS `product_status`;
CREATE TABLE `product_status` (
  `product_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `product_show` int(11) NOT NULL,
  `category_show` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_sticker`
--

DROP TABLE IF EXISTS `product_sticker`;
CREATE TABLE `product_sticker` (
  `product_sticker_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `langdata` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `foncolor` varchar(255) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL,
  `available` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_stock_limits`
--

DROP TABLE IF EXISTS `product_stock_limits`;
CREATE TABLE `product_stock_limits` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL DEFAULT 0,
  `rec_stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_stock_status`
--

DROP TABLE IF EXISTS `product_stock_status`;
CREATE TABLE `product_stock_status` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `stock_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_stock_waits`
--

DROP TABLE IF EXISTS `product_stock_waits`;
CREATE TABLE `product_stock_waits` (
  `product_id` int(11) NOT NULL,
  `quantity_stock` int(11) NOT NULL DEFAULT 0,
  `quantity_stockM` int(11) NOT NULL DEFAULT 0,
  `quantity_stockK` int(11) NOT NULL DEFAULT 0,
  `quantity_stockMN` int(11) NOT NULL DEFAULT 0,
  `quantity_stockAS` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_tab`
--

DROP TABLE IF EXISTS `product_tab`;
CREATE TABLE `product_tab` (
  `tab_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `type` enum('default','regular','reserved') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'regular',
  `key` varchar(128) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `login` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_tab_content`
--

DROP TABLE IF EXISTS `product_tab_content`;
CREATE TABLE `product_tab_content` (
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tab_id` int(11) NOT NULL,
  `content` text COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_tab_default`
--

DROP TABLE IF EXISTS `product_tab_default`;
CREATE TABLE `product_tab_default` (
  `tab_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `content` text COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_tab_name`
--

DROP TABLE IF EXISTS `product_tab_name`;
CREATE TABLE `product_tab_name` (
  `tab_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_tmp`
--

DROP TABLE IF EXISTS `product_tmp`;
CREATE TABLE `product_tmp` (
  `product_id` int(11) NOT NULL,
  `model` varchar(64) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `short_name_de` varchar(50) NOT NULL,
  `sku` varchar(64) NOT NULL,
  `upc` varchar(255) NOT NULL,
  `ean` varchar(50) NOT NULL,
  `jan` varchar(13) NOT NULL,
  `virtual_isbn` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `mpn` varchar(255) NOT NULL,
  `asin` varchar(255) NOT NULL,
  `location` varchar(128) NOT NULL,
  `source` text NOT NULL,
  `competitors` text NOT NULL,
  `competitors_ua` text NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `quantity_stock` int(11) NOT NULL DEFAULT 0,
  `quantity_stockM` int(11) NOT NULL DEFAULT 0,
  `quantity_stockK` int(11) NOT NULL DEFAULT 0,
  `quantity_stockMN` int(11) NOT NULL DEFAULT 0,
  `quantity_stockAS` int(11) NOT NULL DEFAULT 0,
  `stock_status_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `collection_id` bigint(20) NOT NULL,
  `shipping` tinyint(1) NOT NULL DEFAULT 1,
  `cost` decimal(15,2) NOT NULL,
  `actual_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `actual_cost_date` date NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `price_national` decimal(15,4) NOT NULL,
  `mpp_price` decimal(15,4) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `historical_price` decimal(15,4) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `points_only_purchase` tinyint(1) NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `date_available` date NOT NULL,
  `weight` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `weight_class_id` int(11) NOT NULL DEFAULT 1,
  `weight_amazon_key` varchar(100) NOT NULL,
  `length` decimal(15,8) NOT NULL DEFAULT 0.00000000,
  `width` decimal(15,8) NOT NULL DEFAULT 0.00000000,
  `height` decimal(15,8) NOT NULL DEFAULT 0.00000000,
  `length_class_id` int(11) NOT NULL DEFAULT 0,
  `length_amazon_key` varchar(100) NOT NULL,
  `pack_weight` decimal(15,8) NOT NULL,
  `pack_weight_class_id` int(11) NOT NULL,
  `pack_weight_amazon_key` varchar(100) NOT NULL,
  `pack_length` decimal(15,8) NOT NULL,
  `pack_width` decimal(15,8) NOT NULL,
  `pack_height` decimal(15,8) NOT NULL,
  `pack_length_class_id` int(11) NOT NULL,
  `pack_length_amazon_key` varchar(100) NOT NULL,
  `subtract` tinyint(1) NOT NULL DEFAULT 1,
  `minimum` int(11) NOT NULL DEFAULT 1,
  `package` int(11) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `viewed` int(11) NOT NULL DEFAULT 0,
  `youtube` text NOT NULL,
  `special_cost` decimal(15,2) NOT NULL,
  `historical_cost` decimal(15,2) NOT NULL,
  `parser_price` decimal(15,2) NOT NULL,
  `parser_special_price` decimal(15,2) NOT NULL,
  `skip` int(11) NOT NULL,
  `tnved` varchar(255) NOT NULL,
  `ignore_parse` tinyint(1) NOT NULL,
  `new` tinyint(1) NOT NULL DEFAULT 0,
  `stock_product_id` int(11) NOT NULL,
  `is_option_with_id` int(11) NOT NULL,
  `is_option_for_product_id` int(11) NOT NULL,
  `color_group` varchar(100) NOT NULL,
  `is_virtual` tinyint(1) NOT NULL,
  `is_related_set` tinyint(1) NOT NULL,
  `has_child` tinyint(1) NOT NULL,
  `ignore_parse_date_to` date NOT NULL,
  `new_date_to` date NOT NULL,
  `min_buy` int(11) NOT NULL,
  `max_buy` int(11) NOT NULL,
  `can_be_presented` tinyint(1) NOT NULL,
  `bought_for_week` int(11) NOT NULL,
  `bought_for_month` int(11) NOT NULL,
  `big_business` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_to_category`
--

DROP TABLE IF EXISTS `product_to_category`;
CREATE TABLE `product_to_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `main_category` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_to_download`
--

DROP TABLE IF EXISTS `product_to_download`;
CREATE TABLE `product_to_download` (
  `product_id` int(11) NOT NULL,
  `download_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_to_layout`
--

DROP TABLE IF EXISTS `product_to_layout`;
CREATE TABLE `product_to_layout` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_to_set`
--

DROP TABLE IF EXISTS `product_to_set`;
CREATE TABLE `product_to_set` (
  `set_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `clean_product_id` int(11) DEFAULT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `price_in_set` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `quantity` int(11) NOT NULL,
  `present` int(11) DEFAULT NULL,
  `show_in_product` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_to_store`
--

DROP TABLE IF EXISTS `product_to_store`;
CREATE TABLE `product_to_store` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_to_tab`
--

DROP TABLE IF EXISTS `product_to_tab`;
CREATE TABLE `product_to_tab` (
  `product_id` int(11) NOT NULL,
  `tab_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
  `main_asin` varchar(32) NOT NULL,
  `variant_asin` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_variants_ids`
--

DROP TABLE IF EXISTS `product_variants_ids`;
CREATE TABLE `product_variants_ids` (
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_video`
--

DROP TABLE IF EXISTS `product_video`;
CREATE TABLE `product_video` (
  `product_video_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(1024) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_video_description`
--

DROP TABLE IF EXISTS `product_video_description`;
CREATE TABLE `product_video_description` (
  `product_video_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `language_id` varchar(255) DEFAULT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product_view_to_purchase`
--

DROP TABLE IF EXISTS `product_view_to_purchase`;
CREATE TABLE `product_view_to_purchase` (
  `product_id` int(11) NOT NULL,
  `view_to_purchase_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `product_yam_data`
--

DROP TABLE IF EXISTS `product_yam_data`;
CREATE TABLE `product_yam_data` (
  `product_id` int(11) NOT NULL,
  `yam_real_price` decimal(15,2) NOT NULL,
  `yam_hidings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `yam_category_name` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yam_category_id` int(11) NOT NULL,
  `yam_fees` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `AGENCY_COMMISSION` decimal(15,2) NOT NULL,
  `FEE` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_yam_recommended_prices`
--

DROP TABLE IF EXISTS `product_yam_recommended_prices`;
CREATE TABLE `product_yam_recommended_prices` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BUYBOX` decimal(15,2) NOT NULL,
  `DEFAULT_OFFER` decimal(15,2) NOT NULL,
  `MIN_PRICE_MARKET` decimal(15,2) NOT NULL,
  `MAX_DISCOUNT_BASE` decimal(15,2) NOT NULL,
  `MARKET_OUTLIER_PRICE` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `price` decimal(10,4) NOT NULL,
  `frequency` enum('day','week','semi_month','month','year') NOT NULL,
  `duration` int(10) UNSIGNED NOT NULL,
  `cycle` int(10) UNSIGNED NOT NULL,
  `trial_status` tinyint(4) NOT NULL,
  `trial_price` decimal(10,4) NOT NULL,
  `trial_frequency` enum('day','week','semi_month','month','year') NOT NULL,
  `trial_duration` int(10) UNSIGNED NOT NULL,
  `trial_cycle` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `profile_description`
--

DROP TABLE IF EXISTS `profile_description`;
CREATE TABLE `profile_description` (
  `profile_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `queue_mail`
--

DROP TABLE IF EXISTS `queue_mail`;
CREATE TABLE `queue_mail` (
  `queue_mail_id` int(11) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `queue_push`
--

DROP TABLE IF EXISTS `queue_push`;
CREATE TABLE `queue_push` (
  `queue_push_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `queue_sms`
--

DROP TABLE IF EXISTS `queue_sms`;
CREATE TABLE `queue_sms` (
  `queue_sms_id` int(11) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `redirect`
--

DROP TABLE IF EXISTS `redirect`;
CREATE TABLE `redirect` (
  `redirect_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `from_url` varchar(600) COLLATE utf8mb3_bin NOT NULL,
  `to_url` varchar(600) COLLATE utf8mb3_bin NOT NULL,
  `response_code` int(11) NOT NULL DEFAULT 301,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `times_used` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `referrer_patterns`
--

DROP TABLE IF EXISTS `referrer_patterns`;
CREATE TABLE `referrer_patterns` (
  `pattern_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `url_mask` varchar(256) NOT NULL,
  `url_param` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `return`
--

DROP TABLE IF EXISTS `return`;
CREATE TABLE `return` (
  `return_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `product` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `pricewd_national` decimal(15,2) NOT NULL,
  `price_national` decimal(15,4) NOT NULL,
  `total` decimal(15,4) NOT NULL,
  `totalwd_national` decimal(15,2) NOT NULL,
  `total_national` decimal(15,4) NOT NULL,
  `quantity` int(11) NOT NULL,
  `opened` tinyint(1) NOT NULL,
  `return_reason_id` int(11) NOT NULL,
  `return_action_id` int(11) NOT NULL,
  `return_status_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date_ordered` date NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `to_supplier` tinyint(4) NOT NULL DEFAULT 0,
  `reorder_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `return_action`
--

DROP TABLE IF EXISTS `return_action`;
CREATE TABLE `return_action` (
  `return_action_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `return_history`
--

DROP TABLE IF EXISTS `return_history`;
CREATE TABLE `return_history` (
  `return_history_id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `return_status_id` int(11) NOT NULL,
  `notify` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `return_reason`
--

DROP TABLE IF EXISTS `return_reason`;
CREATE TABLE `return_reason` (
  `return_reason_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `return_status`
--

DROP TABLE IF EXISTS `return_status`;
CREATE TABLE `return_status` (
  `return_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `sorthex` int(11) NOT NULL DEFAULT 0,
  `customer_id` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `addimage` text NOT NULL,
  `html_status` tinyint(1) NOT NULL,
  `purchased` tinyint(1) NOT NULL,
  `answer` text NOT NULL,
  `bads` text NOT NULL,
  `good` text NOT NULL,
  `rating` int(11) NOT NULL,
  `rating_mark` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `review_description`
--

DROP TABLE IF EXISTS `review_description`;
CREATE TABLE `review_description` (
  `review_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bads` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `good` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `review_fields`
--

DROP TABLE IF EXISTS `review_fields`;
CREATE TABLE `review_fields` (
  `review_id` int(11) NOT NULL,
  `mark` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `comm_comfort` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `review_name`
--

DROP TABLE IF EXISTS `review_name`;
CREATE TABLE `review_name` (
  `review_name_id` int(11) NOT NULL,
  `l_code` varchar(5) DEFAULT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `review_template`
--

DROP TABLE IF EXISTS `review_template`;
CREATE TABLE `review_template` (
  `review_template_id` int(11) NOT NULL,
  `l_code` varchar(5) DEFAULT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `search_history`
--

DROP TABLE IF EXISTS `search_history`;
CREATE TABLE `search_history` (
  `text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `times` int(11) NOT NULL,
  `results` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `segments`
--

DROP TABLE IF EXISTS `segments`;
CREATE TABLE `segments` (
  `segment_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `txt_color` varchar(255) NOT NULL,
  `bg_color` varchar(255) NOT NULL,
  `fa_icon` varchar(255) NOT NULL,
  `determination` longtext NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `customer_count` int(11) NOT NULL,
  `total_cheque` int(11) NOT NULL,
  `avg_cheque` int(11) NOT NULL,
  `order_good_count` int(11) NOT NULL,
  `order_bad_count` int(11) NOT NULL,
  `order_good_to_bad` int(11) NOT NULL,
  `avg_csi` float(2,1) NOT NULL,
  `new_days` int(11) NOT NULL DEFAULT 7,
  `group` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `segments_dynamics`
--

DROP TABLE IF EXISTS `segments_dynamics`;
CREATE TABLE `segments_dynamics` (
  `segment_dynamics_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `customer_count` int(11) NOT NULL,
  `total_cheque` int(11) NOT NULL,
  `avg_cheque` int(11) NOT NULL,
  `order_good_count` int(11) NOT NULL,
  `order_bad_count` int(11) NOT NULL,
  `order_good_to_bad` int(11) NOT NULL,
  `avg_csi` float(2,1) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `seocities`
--

DROP TABLE IF EXISTS `seocities`;
CREATE TABLE `seocities` (
  `seocity_id` bigint(20) NOT NULL,
  `seocity_name` varchar(255) NOT NULL,
  `seocity_name2` varchar(255) NOT NULL,
  `seocity_phone` varchar(255) NOT NULL,
  `seocity_phone2` varchar(255) NOT NULL,
  `seocity_delivery_info` varchar(255) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `seo_hreflang`
--

DROP TABLE IF EXISTS `seo_hreflang`;
CREATE TABLE `seo_hreflang` (
  `language_id` int(11) NOT NULL,
  `query` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `set`
--

DROP TABLE IF EXISTS `set`;
CREATE TABLE `set` (
  `set_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `percent` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `enable_productcard` tinyint(1) DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `tax_class_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `count_persone` int(11) NOT NULL,
  `set_group` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0,
  `group` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` longtext DEFAULT NULL,
  `serialized` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `set_description`
--

DROP TABLE IF EXISTS `set_description`;
CREATE TABLE `set_description` (
  `set_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `set_to_category`
--

DROP TABLE IF EXISTS `set_to_category`;
CREATE TABLE `set_to_category` (
  `set_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `set_to_store`
--

DROP TABLE IF EXISTS `set_to_store`;
CREATE TABLE `set_to_store` (
  `set_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `shoputils_citycourier_description`
--

DROP TABLE IF EXISTS `shoputils_citycourier_description`;
CREATE TABLE `shoputils_citycourier_description` (
  `language_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Shoputils citycourier shipping ';

-- --------------------------------------------------------

--
-- Структура таблицы `shoputils_cumulative_discounts`
--

DROP TABLE IF EXISTS `shoputils_cumulative_discounts`;
CREATE TABLE `shoputils_cumulative_discounts` (
  `discount_id` int(11) NOT NULL,
  `days` int(11) NOT NULL DEFAULT 0,
  `summ` decimal(11,2) NOT NULL DEFAULT 0.00,
  `percent` decimal(5,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb3_unicode_ci NOT NULL,
  `products_special` tinyint(1) NOT NULL DEFAULT 0,
  `first_order` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts';

-- --------------------------------------------------------

--
-- Структура таблицы `shoputils_cumulative_discounts_cmsdata`
--

DROP TABLE IF EXISTS `shoputils_cumulative_discounts_cmsdata`;
CREATE TABLE `shoputils_cumulative_discounts_cmsdata` (
  `language_id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT 0,
  `description_before` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `description_after` text COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts CMS data';

-- --------------------------------------------------------

--
-- Структура таблицы `shoputils_cumulative_discounts_description`
--

DROP TABLE IF EXISTS `shoputils_cumulative_discounts_description`;
CREATE TABLE `shoputils_cumulative_discounts_description` (
  `discount_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts descriptions';

-- --------------------------------------------------------

--
-- Структура таблицы `shoputils_cumulative_discounts_to_customer_group`
--

DROP TABLE IF EXISTS `shoputils_cumulative_discounts_to_customer_group`;
CREATE TABLE `shoputils_cumulative_discounts_to_customer_group` (
  `discount_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts to customer group';

-- --------------------------------------------------------

--
-- Структура таблицы `shoputils_cumulative_discounts_to_manufacturer`
--

DROP TABLE IF EXISTS `shoputils_cumulative_discounts_to_manufacturer`;
CREATE TABLE `shoputils_cumulative_discounts_to_manufacturer` (
  `discount_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `shoputils_cumulative_discounts_to_store`
--

DROP TABLE IF EXISTS `shoputils_cumulative_discounts_to_store`;
CREATE TABLE `shoputils_cumulative_discounts_to_store` (
  `discount_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts to store';

-- --------------------------------------------------------

--
-- Структура таблицы `shop_rating`
--

DROP TABLE IF EXISTS `shop_rating`;
CREATE TABLE `shop_rating` (
  `rate_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `rate_status` int(11) NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `shop_rate` int(11) DEFAULT NULL,
  `site_rate` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `good` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bad` text COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `shop_rating_answers`
--

DROP TABLE IF EXISTS `shop_rating_answers`;
CREATE TABLE `shop_rating_answers` (
  `id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `comment` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `notified` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `shop_rating_custom_types`
--

DROP TABLE IF EXISTS `shop_rating_custom_types`;
CREATE TABLE `shop_rating_custom_types` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `shop_rating_custom_values`
--

DROP TABLE IF EXISTS `shop_rating_custom_values`;
CREATE TABLE `shop_rating_custom_values` (
  `id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `custom_value` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `shop_rating_description`
--

DROP TABLE IF EXISTS `shop_rating_description`;
CREATE TABLE `shop_rating_description` (
  `rate_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `good` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bad` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `short_url_alias`
--

DROP TABLE IF EXISTS `short_url_alias`;
CREATE TABLE `short_url_alias` (
  `url_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `alias` varchar(1024) NOT NULL,
  `used` int(11) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `simple_cart`
--

DROP TABLE IF EXISTS `simple_cart`;
CREATE TABLE `simple_cart` (
  `simple_cart_id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `email` varchar(96) DEFAULT NULL,
  `firstname` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `products` text DEFAULT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `simple_custom_data`
--

DROP TABLE IF EXISTS `simple_custom_data`;
CREATE TABLE `simple_custom_data` (
  `object_type` tinyint(4) NOT NULL,
  `object_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `sms_log`
--

DROP TABLE IF EXISTS `sms_log`;
CREATE TABLE `sms_log` (
  `id` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date_send` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `socnetauth2_customer2account`
--

DROP TABLE IF EXISTS `socnetauth2_customer2account`;
CREATE TABLE `socnetauth2_customer2account` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(100) NOT NULL,
  `identity` varchar(300) NOT NULL,
  `link` varchar(300) NOT NULL,
  `provider` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `socnetauth2_precode`
--

DROP TABLE IF EXISTS `socnetauth2_precode`;
CREATE TABLE `socnetauth2_precode` (
  `id` int(11) NOT NULL,
  `identity` varchar(300) NOT NULL,
  `code` varchar(300) NOT NULL,
  `cdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `socnetauth2_records`
--

DROP TABLE IF EXISTS `socnetauth2_records`;
CREATE TABLE `socnetauth2_records` (
  `id` int(11) NOT NULL,
  `state` varchar(100) NOT NULL,
  `redirect` varchar(300) NOT NULL,
  `cdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `special_attribute`
--

DROP TABLE IF EXISTS `special_attribute`;
CREATE TABLE `special_attribute` (
  `special_attribute_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_group_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_name` varchar(100) NOT NULL DEFAULT '',
  `special_attribute_value` varchar(2000) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `special_attribute_group`
--

DROP TABLE IF EXISTS `special_attribute_group`;
CREATE TABLE `special_attribute_group` (
  `special_attribute_group_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_group_name` varchar(100) NOT NULL DEFAULT '',
  `special_attribute_group_description` varchar(4000) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `sphinx_suggestions`
--

DROP TABLE IF EXISTS `sphinx_suggestions`;
CREATE TABLE `sphinx_suggestions` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `trigrams` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `freq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `stocks_dynamics`
--

DROP TABLE IF EXISTS `stocks_dynamics`;
CREATE TABLE `stocks_dynamics` (
  `stock_dynamics_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `warehouse_identifier` varchar(30) NOT NULL,
  `p_count` int(11) NOT NULL,
  `q_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `stock_status`
--

DROP TABLE IF EXISTS `stock_status`;
CREATE TABLE `stock_status` (
  `stock_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ssl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `subscribe`
--

DROP TABLE IF EXISTS `subscribe`;
CREATE TABLE `subscribe` (
  `subscribe_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `subscribe_auth_description`
--

DROP TABLE IF EXISTS `subscribe_auth_description`;
CREATE TABLE `subscribe_auth_description` (
  `subscribe_auth_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `subscribe_authorization` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `subscribe_email_description`
--

DROP TABLE IF EXISTS `subscribe_email_description`;
CREATE TABLE `subscribe_email_description` (
  `subscribe_desc_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `subscribe_descriptions` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `superstat_viewed`
--

DROP TABLE IF EXISTS `superstat_viewed`;
CREATE TABLE `superstat_viewed` (
  `entity_type` enum('p','c','m') NOT NULL,
  `entity_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `times` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_code` varchar(50) NOT NULL,
  `supplier_type` varchar(50) NOT NULL,
  `supplier_country` varchar(255) NOT NULL,
  `supplier_inner` tinyint(1) NOT NULL,
  `supplier_comment` text NOT NULL,
  `supplier_m_coef` decimal(15,2) NOT NULL,
  `supplier_l_coef` decimal(15,2) NOT NULL,
  `supplier_n_coef` decimal(15,2) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `supplier_parent_id` int(11) NOT NULL,
  `1c_uuid` varchar(40) NOT NULL,
  `link_mask` varchar(256) NOT NULL,
  `terms_instock` varchar(32) NOT NULL,
  `terms_outstock` varchar(32) NOT NULL,
  `amzn_good` tinyint(1) NOT NULL,
  `amzn_bad` tinyint(1) NOT NULL,
  `amzn_coefficient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `tax_class`
--

DROP TABLE IF EXISTS `tax_class`;
CREATE TABLE `tax_class` (
  `tax_class_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `tax_rate`
--

DROP TABLE IF EXISTS `tax_rate`;
CREATE TABLE `tax_rate` (
  `tax_rate_id` int(11) NOT NULL,
  `geo_zone_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(32) NOT NULL,
  `rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `type` char(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `tax_rate_to_customer_group`
--

DROP TABLE IF EXISTS `tax_rate_to_customer_group`;
CREATE TABLE `tax_rate_to_customer_group` (
  `tax_rate_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `tax_rule`
--

DROP TABLE IF EXISTS `tax_rule`;
CREATE TABLE `tax_rule` (
  `tax_rule_id` int(11) NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `tax_rate_id` int(11) NOT NULL,
  `based` varchar(10) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `telegram_chats`
--

DROP TABLE IF EXISTS `telegram_chats`;
CREATE TABLE `telegram_chats` (
  `id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user or chat identifier',
  `type` char(10) DEFAULT '' COMMENT 'chat type private groupe or channel',
  `title` char(255) DEFAULT '' COMMENT 'chat title null if case of single chat with the bot',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date creation',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `telegram_messages`
--

DROP TABLE IF EXISTS `telegram_messages`;
CREATE TABLE `telegram_messages` (
  `update_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'The update''s unique identifier.',
  `message_id` bigint(20) DEFAULT NULL COMMENT 'Unique message identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'User identifier',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date the message was sent in timestamp format',
  `chat_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Chat identifier.',
  `forward_from` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User id. For forwarded messages, sender of the original message',
  `forward_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'For forwarded messages, date the original message was sent in Unix time',
  `reply_to_message` longtext DEFAULT NULL COMMENT 'Message object. For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.',
  `text` longtext DEFAULT NULL COMMENT 'For text messages, the actual UTF-8 text of the message',
  `audio` text DEFAULT NULL COMMENT 'Audio object. Message is an audio file, information about the file',
  `document` text DEFAULT NULL COMMENT 'Document object. Message is a general file, information about the file',
  `photo` text DEFAULT NULL COMMENT 'Array of PhotoSize objects. Message is a photo, available sizes of the photo',
  `sticker` text DEFAULT NULL COMMENT 'Sticker object. Message is a sticker, information about the sticker',
  `video` text DEFAULT NULL COMMENT 'Video object. Message is a video, information about the video',
  `voice` text DEFAULT NULL COMMENT 'Voice Object. Message is a Voice, information about the Voice',
  `caption` longtext DEFAULT NULL COMMENT 'For message with caption, the actual UTF-8 text of the caption',
  `contact` text DEFAULT NULL COMMENT 'Contact object. Message is a shared contact, information about the contact',
  `location` text DEFAULT NULL COMMENT 'Location object. Message is a shared location, information about the location',
  `new_chat_participant` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User id. A new member was added to the group, information about them (this member may be bot itself)',
  `left_chat_participant` bigint(20) NOT NULL DEFAULT 0 COMMENT 'User id. A member was removed from the group, information about them (this member may be bot itself)',
  `new_chat_title` char(255) DEFAULT '' COMMENT 'A group title was changed to this value',
  `new_chat_photo` text DEFAULT NULL COMMENT 'Array of PhotoSize objects. A group photo was change to this value',
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `telegram_users`
--

DROP TABLE IF EXISTS `telegram_users`;
CREATE TABLE `telegram_users` (
  `id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user identifier',
  `first_name` char(255) NOT NULL DEFAULT '' COMMENT 'User first name',
  `last_name` char(255) DEFAULT '' COMMENT 'User last name',
  `username` char(255) DEFAULT '' COMMENT 'User username',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date creation',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `telegram_users_chats`
--

DROP TABLE IF EXISTS `telegram_users_chats`;
CREATE TABLE `telegram_users_chats` (
  `user_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user identifier',
  `chat_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user or chat identifier'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `temp`
--

DROP TABLE IF EXISTS `temp`;
CREATE TABLE `temp` (
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `user_group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `entity_type` varchar(30) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `is_recall` tinyint(1) NOT NULL DEFAULT 0,
  `entity_string` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `reply` text NOT NULL,
  `priority` varchar(10) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_max` datetime DEFAULT NULL,
  `date_at` datetime NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ticket_sort`
--

DROP TABLE IF EXISTS `ticket_sort`;
CREATE TABLE `ticket_sort` (
  `ticket_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `tracker`
--

DROP TABLE IF EXISTS `tracker`;
CREATE TABLE `tracker` (
  `id` bigint(20) NOT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `date_visited` date DEFAULT NULL,
  `time_visited` time DEFAULT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `page_viewed` text DEFAULT NULL,
  `arrived_from_page` text DEFAULT NULL,
  `remote_host` text DEFAULT NULL,
  `request_uri` text DEFAULT NULL,
  `isbot` int(11) DEFAULT NULL,
  `current_page` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `translate_stats`
--

DROP TABLE IF EXISTS `translate_stats`;
CREATE TABLE `translate_stats` (
  `time` datetime NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `trigger_history`
--

DROP TABLE IF EXISTS `trigger_history`;
CREATE TABLE `trigger_history` (
  `trigger_history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `actiontemplate_id` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `url_alias`
--

DROP TABLE IF EXISTS `url_alias`;
CREATE TABLE `url_alias` (
  `url_alias_id` int(11) NOT NULL,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `url_alias_cached`
--

DROP TABLE IF EXISTS `url_alias_cached`;
CREATE TABLE `url_alias_cached` (
  `store_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `args` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checksum` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `bitrix_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `code` varchar(40) NOT NULL,
  `is_av` tinyint(1) NOT NULL,
  `is_mainmanager` tinyint(1) NOT NULL,
  `is_headsales` tinyint(1) NOT NULL DEFAULT 0,
  `internal_pbx_num` varchar(255) NOT NULL,
  `internal_auth_pbx_num` varchar(255) NOT NULL,
  `outbound_pbx_num` varchar(255) NOT NULL,
  `own_orders` tinyint(1) NOT NULL DEFAULT 0,
  `count_worktime` tinyint(1) NOT NULL,
  `count_content` tinyint(1) NOT NULL DEFAULT 0,
  `edit_csi` tinyint(1) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ticket` tinyint(1) NOT NULL,
  `unlock_orders` tinyint(1) NOT NULL,
  `do_transactions` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `user_content`
--

DROP TABLE IF EXISTS `user_content`;
CREATE TABLE `user_content` (
  `user_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `date` date NOT NULL,
  `action` varchar(10) NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `entity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `user_group`
--

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group` (
  `user_group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `permission` text NOT NULL,
  `template_prefix` varchar(255) NOT NULL,
  `alert_namespace` varchar(25) NOT NULL,
  `ticket` tinyint(1) NOT NULL,
  `sip_queue` varchar(4) NOT NULL,
  `bitrix_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `user_group_to_store`
--

DROP TABLE IF EXISTS `user_group_to_store`;
CREATE TABLE `user_group_to_store` (
  `user_group_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `user_worktime`
--

DROP TABLE IF EXISTS `user_worktime`;
CREATE TABLE `user_worktime` (
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `inbound_call_count` int(11) NOT NULL DEFAULT 0,
  `inbound_call_duration` int(11) NOT NULL DEFAULT 0,
  `outbound_call_count` int(11) NOT NULL DEFAULT 0,
  `outbound_call_duration` int(11) NOT NULL DEFAULT 0,
  `owned_order_count` int(11) NOT NULL DEFAULT 0,
  `edit_order_count` int(11) NOT NULL DEFAULT 0,
  `edit_birthday_count` int(11) NOT NULL,
  `edit_customer_count` int(11) NOT NULL DEFAULT 0,
  `sent_mail_count` int(11) NOT NULL DEFAULT 0,
  `worktime_start` time DEFAULT NULL,
  `worktime_finish` time DEFAULT NULL,
  `daily_actions` int(11) NOT NULL,
  `success_order_count` int(11) NOT NULL DEFAULT 0,
  `cancel_order_count` int(11) NOT NULL DEFAULT 0,
  `treated_order_count` int(11) NOT NULL DEFAULT 0,
  `confirmed_order_count` int(11) NOT NULL DEFAULT 0,
  `problem_order_count` int(11) NOT NULL DEFAULT 0,
  `edit_csi_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `vk_export_album`
--

DROP TABLE IF EXISTS `vk_export_album`;
CREATE TABLE `vk_export_album` (
  `category_id` int(11) NOT NULL,
  `vk_album_id` varchar(32) NOT NULL,
  `mode` enum('user','group') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `vk_export_photo`
--

DROP TABLE IF EXISTS `vk_export_photo`;
CREATE TABLE `vk_export_photo` (
  `product_id` int(11) NOT NULL,
  `vk_photo_id` varchar(32) NOT NULL,
  `category_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `location` enum('albums','wall') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `voucher`
--

DROP TABLE IF EXISTS `voucher`;
CREATE TABLE `voucher` (
  `voucher_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `curr` varchar(5) NOT NULL,
  `from_name` varchar(64) NOT NULL,
  `from_email` varchar(96) NOT NULL,
  `to_name` varchar(64) NOT NULL,
  `to_email` varchar(96) NOT NULL,
  `voucher_theme_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `amount_national` decimal(15,4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `voucher_history`
--

DROP TABLE IF EXISTS `voucher_history`;
CREATE TABLE `voucher_history` (
  `voucher_history_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `voucher_theme`
--

DROP TABLE IF EXISTS `voucher_theme`;
CREATE TABLE `voucher_theme` (
  `voucher_theme_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `voucher_theme_description`
--

DROP TABLE IF EXISTS `voucher_theme_description`;
CREATE TABLE `voucher_theme_description` (
  `voucher_theme_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `wc_continents`
--

DROP TABLE IF EXISTS `wc_continents`;
CREATE TABLE `wc_continents` (
  `code` char(2) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `geonameId` char(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `wc_countries`
--

DROP TABLE IF EXISTS `wc_countries`;
CREATE TABLE `wc_countries` (
  `code` char(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `iso3` char(3) NOT NULL,
  `number` smallint(3) UNSIGNED ZEROFILL NOT NULL,
  `continent_code` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `weight_class`
--

DROP TABLE IF EXISTS `weight_class`;
CREATE TABLE `weight_class` (
  `weight_class_id` int(11) NOT NULL,
  `value` decimal(15,8) NOT NULL DEFAULT 0.00000000,
  `system_key` varchar(100) NOT NULL,
  `amazon_key` varchar(100) NOT NULL,
  `variants` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `weight_class_description`
--

DROP TABLE IF EXISTS `weight_class_description`;
CREATE TABLE `weight_class_description` (
  `weight_class_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `unit` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `yandex_feeds`
--

DROP TABLE IF EXISTS `yandex_feeds`;
CREATE TABLE `yandex_feeds` (
  `store_id` int(11) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `entity_type` varchar(1) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `yandex_queue`
--

DROP TABLE IF EXISTS `yandex_queue`;
CREATE TABLE `yandex_queue` (
  `order_id` int(11) NOT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `substatus` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `yandex_stock_queue`
--

DROP TABLE IF EXISTS `yandex_stock_queue`;
CREATE TABLE `yandex_stock_queue` (
  `yam_product_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `zone`
--

DROP TABLE IF EXISTS `zone`;
CREATE TABLE `zone` (
  `zone_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `code` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `zone_to_geo_zone`
--

DROP TABLE IF EXISTS `zone_to_geo_zone`;
CREATE TABLE `zone_to_geo_zone` (
  `zone_to_geo_zone_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL DEFAULT 0,
  `geo_zone_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `_temp`
--

DROP TABLE IF EXISTS `_temp`;
CREATE TABLE `_temp` (
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `_temp_discount`
--

DROP TABLE IF EXISTS `_temp_discount`;
CREATE TABLE `_temp_discount` (
  `id` int(11) NOT NULL,
  `card_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`actions_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `deletenotinstock` (`deletenotinstock`),
  ADD KEY `only_in_stock` (`only_in_stock`),
  ADD KEY `display_all_active` (`display_all_active`);

--
-- Индексы таблицы `actions_description`
--
ALTER TABLE `actions_description`
  ADD PRIMARY KEY (`actions_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `title` (`title`),
  ADD KEY `caption` (`caption`);

--
-- Индексы таблицы `actions_to_category`
--
ALTER TABLE `actions_to_category`
  ADD KEY `action_id` (`actions_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `actions_to_category_in`
--
ALTER TABLE `actions_to_category_in`
  ADD KEY `actions_id` (`actions_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `actions_to_layout`
--
ALTER TABLE `actions_to_layout`
  ADD PRIMARY KEY (`actions_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Индексы таблицы `actions_to_product`
--
ALTER TABLE `actions_to_product`
  ADD KEY `actions_id` (`actions_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `actions_to_store`
--
ALTER TABLE `actions_to_store`
  ADD PRIMARY KEY (`actions_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `actiontemplate`
--
ALTER TABLE `actiontemplate`
  ADD PRIMARY KEY (`actiontemplate_id`);

--
-- Индексы таблицы `actiontemplate_description`
--
ALTER TABLE `actiontemplate_description`
  ADD PRIMARY KEY (`actiontemplate_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `tax_id` (`tax_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `city` (`city`),
  ADD KEY `address_1` (`address_1`),
  ADD KEY `for_print` (`for_print`),
  ADD KEY `verified` (`verified`),
  ADD KEY `firstname` (`firstname`);

--
-- Индексы таблицы `address_simple_fields`
--
ALTER TABLE `address_simple_fields`
  ADD PRIMARY KEY (`address_id`);

--
-- Индексы таблицы `adminlog`
--
ALTER TABLE `adminlog`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `advanced_coupon`
--
ALTER TABLE `advanced_coupon`
  ADD PRIMARY KEY (`advanced_coupon_id`),
  ADD UNIQUE KEY `name` (`code`);

--
-- Индексы таблицы `advanced_coupon_history`
--
ALTER TABLE `advanced_coupon_history`
  ADD PRIMARY KEY (`advanced_coupon_history_id`),
  ADD KEY `advanced_coupon_id` (`advanced_coupon_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `affiliate`
--
ALTER TABLE `affiliate`
  ADD PRIMARY KEY (`affiliate_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `code` (`code`);

--
-- Индексы таблицы `affiliate_statistics`
--
ALTER TABLE `affiliate_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `affiliate_id` (`affiliate_id`);

--
-- Индексы таблицы `affiliate_transaction`
--
ALTER TABLE `affiliate_transaction`
  ADD PRIMARY KEY (`affiliate_transaction_id`),
  ADD KEY `affiliate_id` (`affiliate_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Индексы таблицы `alertlog`
--
ALTER TABLE `alertlog`
  ADD PRIMARY KEY (`alertlog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `alsoviewed`
--
ALTER TABLE `alsoviewed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `low_2` (`low`,`high`),
  ADD KEY `low` (`low`),
  ADD KEY `high` (`high`),
  ADD KEY `number` (`number`);

--
-- Индексы таблицы `amazon_orders`
--
ALTER TABLE `amazon_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `amazon_id` (`amazon_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `amazon_orders_blobs`
--
ALTER TABLE `amazon_orders_blobs`
  ADD UNIQUE KEY `amazon_id` (`amazon_id`) USING BTREE;

--
-- Индексы таблицы `amazon_orders_products`
--
ALTER TABLE `amazon_orders_products`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `delivery_num` (`delivery_num`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `asin` (`asin`),
  ADD KEY `amazon_id` (`amazon_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `delivery_status` (`delivery_status`),
  ADD KEY `is_problem` (`is_problem`),
  ADD KEY `is_return` (`is_return`),
  ADD KEY `is_dispatched` (`is_dispatched`),
  ADD KEY `is_delivered` (`is_delivered`),
  ADD KEY `date_delivered` (`date_delivered`),
  ADD KEY `is_arriving` (`is_arriving`),
  ADD KEY `date_arriving_from` (`date_arriving_from`),
  ADD KEY `date_arriving_to` (`date_arriving_to`),
  ADD KEY `date_arriving_exact` (`date_arriving_exact`),
  ADD KEY `is_expected` (`is_expected`),
  ADD KEY `date_expected` (`date_expected`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `delivery_status_ru` (`delivery_status_ru`);

--
-- Индексы таблицы `amzn_add_queue`
--
ALTER TABLE `amzn_add_queue`
  ADD UNIQUE KEY `asin` (`asin`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `amzn_product_queue`
--
ALTER TABLE `amzn_product_queue`
  ADD UNIQUE KEY `asin` (`asin`);

--
-- Индексы таблицы `apri`
--
ALTER TABLE `apri`
  ADD PRIMARY KEY (`order_id`);

--
-- Индексы таблицы `attribute`
--
ALTER TABLE `attribute`
  ADD PRIMARY KEY (`attribute_id`),
  ADD KEY `attribute_group_id` (`attribute_group_id`),
  ADD KEY `dimension_type` (`dimension_type`);

--
-- Индексы таблицы `attributes_category`
--
ALTER TABLE `attributes_category`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Индексы таблицы `attributes_similar_category`
--
ALTER TABLE `attributes_similar_category`
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `attribute_description`
--
ALTER TABLE `attribute_description`
  ADD PRIMARY KEY (`attribute_id`,`language_id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `attribute_group`
--
ALTER TABLE `attribute_group`
  ADD PRIMARY KEY (`attribute_group_id`);

--
-- Индексы таблицы `attribute_group_description`
--
ALTER TABLE `attribute_group_description`
  ADD PRIMARY KEY (`attribute_group_id`,`language_id`);

--
-- Индексы таблицы `attribute_group_tooltip`
--
ALTER TABLE `attribute_group_tooltip`
  ADD PRIMARY KEY (`attribute_group_id`,`language_id`);

--
-- Индексы таблицы `attribute_tooltip`
--
ALTER TABLE `attribute_tooltip`
  ADD PRIMARY KEY (`attribute_id`,`language_id`);

--
-- Индексы таблицы `attribute_value_image`
--
ALTER TABLE `attribute_value_image`
  ADD PRIMARY KEY (`attribute_value_image`);

--
-- Индексы таблицы `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banner_id`);

--
-- Индексы таблицы `banner_image`
--
ALTER TABLE `banner_image`
  ADD PRIMARY KEY (`banner_image_id`),
  ADD KEY `banner_id` (`banner_id`);

--
-- Индексы таблицы `banner_image_description`
--
ALTER TABLE `banner_image_description`
  ADD PRIMARY KEY (`banner_image_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `banner_id` (`banner_id`);

--
-- Индексы таблицы `callback`
--
ALTER TABLE `callback`
  ADD PRIMARY KEY (`call_id`),
  ADD KEY `sip_queue` (`sip_queue`),
  ADD KEY `is_cheaper` (`is_cheaper`),
  ADD KEY `is_missed` (`is_missed`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `category_id` (`category_id`,`parent_id`),
  ADD KEY `separate_feeds` (`separate_feeds`),
  ADD KEY `no_general_feed` (`no_general_feed`),
  ADD KEY `google_category_id` (`google_category_id`),
  ADD KEY `deletenotinstock` (`deletenotinstock`),
  ADD KEY `intersections` (`intersections`),
  ADD KEY `priceva_enable` (`priceva_enable`),
  ADD KEY `submenu_in_children` (`submenu_in_children`),
  ADD KEY `amazon_last_sync` (`amazon_last_sync`),
  ADD KEY `status` (`status`),
  ADD KEY `product_count` (`product_count`),
  ADD KEY `amazon_sync_enable` (`amazon_sync_enable`),
  ADD KEY `amazon_category_id` (`amazon_category_id`),
  ADD KEY `yandex_category_name` (`yandex_category_name`),
  ADD KEY `amazon_category_name` (`amazon_category_name`),
  ADD KEY `amazon_parent_category_id` (`amazon_parent_category_id`),
  ADD KEY `amazon_parent_category_name` (`amazon_parent_category_name`),
  ADD KEY `amazon_final_category` (`amazon_final_category`),
  ADD KEY `amazon_can_get_full` (`amazon_can_get_full`),
  ADD KEY `exclude_from_intersections` (`exclude_from_intersections`);

--
-- Индексы таблицы `category_amazon_bestseller_tree`
--
ALTER TABLE `category_amazon_bestseller_tree`
  ADD UNIQUE KEY `category_id` (`category_id`) USING BTREE,
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `final_category` (`final_category`),
  ADD KEY `name` (`name`),
  ADD KEY `full_name` (`full_name`);

--
-- Индексы таблицы `category_amazon_tree`
--
ALTER TABLE `category_amazon_tree`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `final_category` (`final_category`),
  ADD KEY `name` (`name`),
  ADD KEY `full_name` (`full_name`);

--
-- Индексы таблицы `category_description`
--
ALTER TABLE `category_description`
  ADD PRIMARY KEY (`category_id`,`language_id`),
  ADD KEY `name` (`name`);
ALTER TABLE `category_description` ADD FULLTEXT KEY `FULLTEXT_name` (`name`);

--
-- Индексы таблицы `category_filter`
--
ALTER TABLE `category_filter`
  ADD PRIMARY KEY (`category_id`,`filter_id`);

--
-- Индексы таблицы `category_menu_content`
--
ALTER TABLE `category_menu_content`
  ADD PRIMARY KEY (`category_menu_content_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `sort_order` (`sort_order`),
  ADD KEY `category_id_2` (`category_id`,`language_id`);

--
-- Индексы таблицы `category_path`
--
ALTER TABLE `category_path`
  ADD PRIMARY KEY (`category_id`,`path_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `path_id` (`path_id`),
  ADD KEY `level` (`level`);

--
-- Индексы таблицы `category_product_count`
--
ALTER TABLE `category_product_count`
  ADD KEY `store_id` (`store_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `category_psm_template`
--
ALTER TABLE `category_psm_template`
  ADD PRIMARY KEY (`category_psm_template_id`);

--
-- Индексы таблицы `category_related`
--
ALTER TABLE `category_related`
  ADD UNIQUE KEY `category_id_2` (`category_id`,`related_category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `category_review`
--
ALTER TABLE `category_review`
  ADD PRIMARY KEY (`categoryreview_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `category_to_actions`
--
ALTER TABLE `category_to_actions`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `actions_id` (`actions_id`);

--
-- Индексы таблицы `category_to_layout`
--
ALTER TABLE `category_to_layout`
  ADD PRIMARY KEY (`category_id`,`store_id`);

--
-- Индексы таблицы `category_to_store`
--
ALTER TABLE `category_to_store`
  ADD PRIMARY KEY (`category_id`,`store_id`);

--
-- Индексы таблицы `category_yam_tree`
--
ALTER TABLE `category_yam_tree`
  ADD KEY `name` (`name`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `full_name` (`full_name`);

--
-- Индексы таблицы `cdek_cities`
--
ALTER TABLE `cdek_cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `city_uuid` (`city_uuid`),
  ADD KEY `region_code` (`region_code`),
  ADD KEY `code` (`code`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city` (`city`),
  ADD KEY `country` (`country`),
  ADD KEY `region` (`region`),
  ADD KEY `WarehouseCount` (`WarehouseCount`),
  ADD KEY `dadata_BELTWAY_HIT` (`dadata_BELTWAY_HIT`),
  ADD KEY `dadata_BELTWAY_DISTANCE` (`dadata_BELTWAY_DISTANCE`);

--
-- Индексы таблицы `cdek_deliverypoints`
--
ALTER TABLE `cdek_deliverypoints`
  ADD PRIMARY KEY (`deliverypoint_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `region_code` (`region_code`),
  ADD KEY `city_code` (`city_code`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `name` (`name`),
  ADD KEY `region` (`region`),
  ADD KEY `city` (`city`),
  ADD KEY `address` (`address`);

--
-- Индексы таблицы `cdek_zones`
--
ALTER TABLE `cdek_zones`
  ADD PRIMARY KEY (`zone_id`),
  ADD UNIQUE KEY `region_uuid` (`region_uuid`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `region_code` (`region_code`),
  ADD KEY `country` (`country`),
  ADD KEY `region` (`region`),
  ADD KEY `country_code` (`country_code`);

--
-- Индексы таблицы `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `collection_description`
--
ALTER TABLE `collection_description`
  ADD UNIQUE KEY `collection_id_2` (`collection_id`,`language_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `collection_image`
--
ALTER TABLE `collection_image`
  ADD KEY `collection_id` (`collection_id`);

--
-- Индексы таблицы `collection_to_store`
--
ALTER TABLE `collection_to_store`
  ADD UNIQUE KEY `collection_id_2` (`collection_id`,`store_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `competitors`
--
ALTER TABLE `competitors`
  ADD PRIMARY KEY (`competitor_id`);

--
-- Индексы таблицы `competitor_price`
--
ALTER TABLE `competitor_price`
  ADD PRIMARY KEY (`competitor_price_id`),
  ADD KEY `competitor_id` (`competitor_id`),
  ADD KEY `sku` (`sku`),
  ADD KEY `currency` (`currency`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `competitor_urls`
--
ALTER TABLE `competitor_urls`
  ADD KEY `competitor_id` (`competitor_id`),
  ADD KEY `sku` (`sku`);

--
-- Индексы таблицы `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`counter_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `value` (`value`),
  ADD KEY `counter` (`counter`);

--
-- Индексы таблицы `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`),
  ADD KEY `warehouse_identifier` (`warehouse_identifier`);

--
-- Индексы таблицы `countrybrand`
--
ALTER TABLE `countrybrand`
  ADD PRIMARY KEY (`countrybrand_id`);

--
-- Индексы таблицы `countrybrand_description`
--
ALTER TABLE `countrybrand_description`
  ADD UNIQUE KEY `countrybrand_id_2` (`countrybrand_id`,`language_id`),
  ADD KEY `countrybrand_id` (`countrybrand_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `countrybrand_image`
--
ALTER TABLE `countrybrand_image`
  ADD KEY `countrybrand_id` (`countrybrand_id`);

--
-- Индексы таблицы `countrybrand_to_store`
--
ALTER TABLE `countrybrand_to_store`
  ADD UNIQUE KEY `countrybrand_id_2` (`countrybrand_id`,`store_id`),
  ADD KEY `countrybrand_id` (`countrybrand_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `country_to_fias`
--
ALTER TABLE `country_to_fias`
  ADD UNIQUE KEY `country_id` (`country_id`,`fias_id`);

--
-- Индексы таблицы `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`coupon_id`),
  ADD KEY `affiliate_id` (`affiliate_id`),
  ADD KEY `show_in_segments` (`show_in_segments`),
  ADD KEY `promo_type` (`promo_type`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `display_list` (`display_list`),
  ADD KEY `type` (`type`),
  ADD KEY `currency` (`currency`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `date_end` (`date_end`),
  ADD KEY `status` (`status`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `logged` (`logged`),
  ADD KEY `code` (`code`),
  ADD KEY `only_in_stock` (`only_in_stock`),
  ADD KEY `uses_total` (`uses_total`),
  ADD KEY `uses_customer` (`uses_customer`),
  ADD KEY `birthday` (`birthday`),
  ADD KEY `display_in_account` (`display_in_account`);

--
-- Индексы таблицы `coupon_category`
--
ALTER TABLE `coupon_category`
  ADD PRIMARY KEY (`coupon_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `coupon_collection`
--
ALTER TABLE `coupon_collection`
  ADD PRIMARY KEY (`coupon_id`,`collection_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Индексы таблицы `coupon_description`
--
ALTER TABLE `coupon_description`
  ADD UNIQUE KEY `coupon_id_2` (`coupon_id`,`language_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `coupon_history`
--
ALTER TABLE `coupon_history`
  ADD PRIMARY KEY (`coupon_history_id`),
  ADD UNIQUE KEY `coupon_id_2` (`coupon_id`,`order_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `coupon_id_3` (`coupon_id`,`customer_id`);

--
-- Индексы таблицы `coupon_manufacturer`
--
ALTER TABLE `coupon_manufacturer`
  ADD PRIMARY KEY (`coupon_id`,`manufacturer_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`);

--
-- Индексы таблицы `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD PRIMARY KEY (`coupon_product_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `coupon_review`
--
ALTER TABLE `coupon_review`
  ADD PRIMARY KEY (`code`),
  ADD KEY `coupon_history_id` (`coupon_history_id`);

--
-- Индексы таблицы `csvprice_pro`
--
ALTER TABLE `csvprice_pro`
  ADD PRIMARY KEY (`setting_id`);

--
-- Индексы таблицы `csvprice_pro_crontab`
--
ALTER TABLE `csvprice_pro_crontab`
  ADD PRIMARY KEY (`job_id`);

--
-- Индексы таблицы `csvprice_pro_images`
--
ALTER TABLE `csvprice_pro_images`
  ADD PRIMARY KEY (`catalog_id`,`image_key`),
  ADD KEY `image_key` (`image_key`);

--
-- Индексы таблицы `csvprice_pro_profiles`
--
ALTER TABLE `csvprice_pro_profiles`
  ADD PRIMARY KEY (`profile_id`);

--
-- Индексы таблицы `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Индексы таблицы `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `customer_group_id` (`customer_group_id`),
  ADD KEY `source` (`source`),
  ADD KEY `email` (`email`) USING BTREE,
  ADD KEY `telephone` (`telephone`),
  ADD KEY `fax` (`fax`),
  ADD KEY `status` (`status`),
  ADD KEY `approved` (`approved`),
  ADD KEY `normalized_fax` (`normalized_fax`),
  ADD KEY `normalized_telephone` (`normalized_telephone`) USING BTREE,
  ADD KEY `language_id` (`language_id`),
  ADD KEY `mail_status` (`mail_status`),
  ADD KEY `mail_opened` (`mail_opened`),
  ADD KEY `mail_clicked` (`mail_clicked`),
  ADD KEY `order_count` (`order_count`),
  ADD KEY `order_good_count` (`order_good_count`),
  ADD KEY `avg_cheque` (`avg_cheque`),
  ADD KEY `total_cheque` (`total_cheque`),
  ADD KEY `total_calls` (`total_calls`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city` (`city`),
  ADD KEY `avg_calls_duration` (`avg_calls_duration`),
  ADD KEY `order_bad_count` (`order_bad_count`),
  ADD KEY `order_last_date` (`order_last_date`),
  ADD KEY `order_first_date` (`order_first_date`),
  ADD KEY `order_good_first_date` (`order_good_first_date`),
  ADD KEY `order_good_last_date` (`order_good_last_date`),
  ADD KEY `birthday` (`birthday`),
  ADD KEY `first_order_source` (`first_order_source`),
  ADD KEY `has_push` (`has_push`),
  ADD KEY `city_population` (`city_population`),
  ADD KEY `csi_average` (`csi_average`),
  ADD KEY `csi_reject` (`csi_reject`),
  ADD KEY `nbt_csi` (`nbt_csi`),
  ADD KEY `nbt_customer` (`nbt_customer`),
  ADD KEY `birthday_month` (`birthday_month`),
  ADD KEY `birthday_date` (`birthday_date`),
  ADD KEY `cron_sent` (`cron_sent`),
  ADD KEY `rja_customer` (`rja_customer`),
  ADD KEY `social_id` (`social_id`(1024)),
  ADD KEY `firstname` (`firstname`),
  ADD KEY `password` (`password`),
  ADD KEY `token` (`token`),
  ADD KEY `utoken` (`utoken`),
  ADD KEY `mudak` (`mudak`),
  ADD KEY `printed2912` (`printed2912`),
  ADD KEY `discount_card` (`discount_card`),
  ADD KEY `newsletter` (`newsletter`),
  ADD KEY `newsletter_news` (`newsletter_news`),
  ADD KEY `newsletter_personal` (`newsletter_personal`),
  ADD KEY `mailwizz_uid` (`newsletter_ema_uid`),
  ADD KEY `newsletter_news_ema_uid` (`newsletter_news_ema_uid`),
  ADD KEY `newsletter_personal_ema_uid` (`newsletter_personal_ema_uid`),
  ADD KEY `viber_news` (`viber_news`);

--
-- Индексы таблицы `customer_ban_ip`
--
ALTER TABLE `customer_ban_ip`
  ADD PRIMARY KEY (`customer_ban_ip_id`),
  ADD KEY `ip` (`ip`);

--
-- Индексы таблицы `customer_calls`
--
ALTER TABLE `customer_calls`
  ADD PRIMARY KEY (`customer_call_id`),
  ADD UNIQUE KEY `customer_phone` (`customer_phone`,`date_end`,`internal_pbx_num`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `inbound` (`inbound`),
  ADD KEY `length` (`length`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Индексы таблицы `customer_emails_blacklist`
--
ALTER TABLE `customer_emails_blacklist`
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `customer_emails_whitelist`
--
ALTER TABLE `customer_emails_whitelist`
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `customer_email_campaigns`
--
ALTER TABLE `customer_email_campaigns`
  ADD PRIMARY KEY (`customer_email_campaigns_id`),
  ADD UNIQUE KEY `customer_id_2` (`customer_id`,`campaign_id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `customer_email_campaigns_names`
--
ALTER TABLE `customer_email_campaigns_names`
  ADD UNIQUE KEY `email_campaign_mailwizz_id` (`email_campaign_mailwizz_id`);

--
-- Индексы таблицы `customer_field`
--
ALTER TABLE `customer_field`
  ADD PRIMARY KEY (`customer_id`,`custom_field_id`,`custom_field_value_id`),
  ADD KEY `custom_field_id` (`custom_field_id`),
  ADD KEY `custom_field_value_id` (`custom_field_value_id`);

--
-- Индексы таблицы `customer_group`
--
ALTER TABLE `customer_group`
  ADD PRIMARY KEY (`customer_group_id`);

--
-- Индексы таблицы `customer_group_description`
--
ALTER TABLE `customer_group_description`
  ADD PRIMARY KEY (`customer_group_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `customer_history`
--
ALTER TABLE `customer_history`
  ADD PRIMARY KEY (`customer_history_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `segment_id` (`segment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `call_id` (`call_id`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `sms_id` (`sms_id`),
  ADD KEY `email_id` (`email_id`);

--
-- Индексы таблицы `customer_ip`
--
ALTER TABLE `customer_ip`
  ADD PRIMARY KEY (`customer_ip_id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `customer_id_customer_ip` (`customer_id`,`ip`) USING BTREE;

--
-- Индексы таблицы `customer_online`
--
ALTER TABLE `customer_online`
  ADD PRIMARY KEY (`ip`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `is_bot` (`is_bot`),
  ADD KEY `is_pwa` (`is_pwa`),
  ADD KEY `is_bot_2` (`is_bot`,`is_pwa`);

--
-- Индексы таблицы `customer_online_history`
--
ALTER TABLE `customer_online_history`
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `customer_push_ids`
--
ALTER TABLE `customer_push_ids`
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `onesignal_push_id` (`onesignal_push_id`);

--
-- Индексы таблицы `customer_reward`
--
ALTER TABLE `customer_reward`
  ADD PRIMARY KEY (`customer_reward_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `reason_code` (`reason_code`),
  ADD KEY `points` (`points`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `burned` (`burned`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `customer_reward_queue`
--
ALTER TABLE `customer_reward_queue`
  ADD PRIMARY KEY (`customer_reward_queue_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `reason_code` (`reason_code`);

--
-- Индексы таблицы `customer_search_history`
--
ALTER TABLE `customer_search_history`
  ADD PRIMARY KEY (`customer_history_id`),
  ADD UNIQUE KEY `customer_id_text` (`customer_id`,`text`) USING BTREE,
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `text` (`text`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `customer_segments`
--
ALTER TABLE `customer_segments`
  ADD UNIQUE KEY `customer_id_2` (`customer_id`,`segment_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `segment_id` (`segment_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `customer_simple_fields`
--
ALTER TABLE `customer_simple_fields`
  ADD PRIMARY KEY (`customer_id`);

--
-- Индексы таблицы `customer_transaction`
--
ALTER TABLE `customer_transaction`
  ADD PRIMARY KEY (`customer_transaction_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `paykeeper_id` (`paykeeper_id`),
  ADD KEY `pspReference` (`pspReference`),
  ADD KEY `concardis_id` (`concardis_id`),
  ADD KEY `guid` (`guid`);

--
-- Индексы таблицы `customer_viewed`
--
ALTER TABLE `customer_viewed`
  ADD UNIQUE KEY `customer_id_2` (`customer_id`,`type`,`entity_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `type` (`type`),
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `times` (`times`);

--
-- Индексы таблицы `custom_field`
--
ALTER TABLE `custom_field`
  ADD PRIMARY KEY (`custom_field_id`);

--
-- Индексы таблицы `custom_field_description`
--
ALTER TABLE `custom_field_description`
  ADD PRIMARY KEY (`custom_field_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `custom_field_to_customer_group`
--
ALTER TABLE `custom_field_to_customer_group`
  ADD PRIMARY KEY (`custom_field_id`,`customer_group_id`),
  ADD KEY `customer_group_id` (`customer_group_id`);

--
-- Индексы таблицы `custom_field_value`
--
ALTER TABLE `custom_field_value`
  ADD PRIMARY KEY (`custom_field_value_id`),
  ADD KEY `custom_field_id` (`custom_field_id`);

--
-- Индексы таблицы `custom_field_value_description`
--
ALTER TABLE `custom_field_value_description`
  ADD PRIMARY KEY (`custom_field_value_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `custom_field_id` (`custom_field_id`);

--
-- Индексы таблицы `custom_url_404`
--
ALTER TABLE `custom_url_404`
  ADD PRIMARY KEY (`custom_url_404_id`);

--
-- Индексы таблицы `deleted_asins`
--
ALTER TABLE `deleted_asins`
  ADD PRIMARY KEY (`asin`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `name` (`name`(768));

--
-- Индексы таблицы `direct_timezones`
--
ALTER TABLE `direct_timezones`
  ADD PRIMARY KEY (`geomd5`);

--
-- Индексы таблицы `download`
--
ALTER TABLE `download`
  ADD PRIMARY KEY (`download_id`);

--
-- Индексы таблицы `download_description`
--
ALTER TABLE `download_description`
  ADD PRIMARY KEY (`download_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `emailmarketing_logs`
--
ALTER TABLE `emailmarketing_logs`
  ADD PRIMARY KEY (`emailmarketing_log_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `actiontemplate_id` (`actiontemplate_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `date_sent` (`date_sent`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `mail_opened` (`mail_opened`),
  ADD KEY `mail_clicked` (`mail_clicked`);

--
-- Индексы таблицы `emailtemplate`
--
ALTER TABLE `emailtemplate`
  ADD PRIMARY KEY (`emailtemplate_id`),
  ADD KEY `KEY` (`emailtemplate_key`);

--
-- Индексы таблицы `emailtemplate_config`
--
ALTER TABLE `emailtemplate_config`
  ADD PRIMARY KEY (`emailtemplate_config_id`);

--
-- Индексы таблицы `emailtemplate_description`
--
ALTER TABLE `emailtemplate_description`
  ADD PRIMARY KEY (`emailtemplate_id`,`language_id`);

--
-- Индексы таблицы `emailtemplate_logs`
--
ALTER TABLE `emailtemplate_logs`
  ADD PRIMARY KEY (`emailtemplate_log_id`),
  ADD KEY `transmission_id` (`transmission_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `marketing` (`marketing`),
  ADD KEY `mail_status` (`mail_status`);

--
-- Индексы таблицы `emailtemplate_shortcode`
--
ALTER TABLE `emailtemplate_shortcode`
  ADD PRIMARY KEY (`emailtemplate_shortcode_id`),
  ADD KEY `emailtemplate_id` (`emailtemplate_id`);

--
-- Индексы таблицы `entity_reward`
--
ALTER TABLE `entity_reward`
  ADD PRIMARY KEY (`entity_reward_id`),
  ADD KEY `entity_type` (`entity_type`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `date_end` (`date_end`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `points` (`points`);

--
-- Индексы таблицы `extension`
--
ALTER TABLE `extension`
  ADD PRIMARY KEY (`extension_id`),
  ADD KEY `type` (`type`),
  ADD KEY `code` (`code`);

--
-- Индексы таблицы `facategory`
--
ALTER TABLE `facategory`
  ADD PRIMARY KEY (`facategory_id`);

--
-- Индексы таблицы `facategory_to_faproduct`
--
ALTER TABLE `facategory_to_faproduct`
  ADD PRIMARY KEY (`facategory_id`,`product_id`);

--
-- Индексы таблицы `faproduct_to_facategory`
--
ALTER TABLE `faproduct_to_facategory`
  ADD PRIMARY KEY (`product_id`,`facategory_id`);

--
-- Индексы таблицы `faq_category`
--
ALTER TABLE `faq_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `faq_category_description`
--
ALTER TABLE `faq_category_description`
  ADD PRIMARY KEY (`category_id`,`language_id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `faq_question`
--
ALTER TABLE `faq_question`
  ADD PRIMARY KEY (`question_id`);

--
-- Индексы таблицы `feed_queue`
--
ALTER TABLE `feed_queue`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`filter_id`),
  ADD KEY `filter_group_id` (`filter_group_id`);

--
-- Индексы таблицы `filterpro_seo`
--
ALTER TABLE `filterpro_seo`
  ADD PRIMARY KEY (`url`);

--
-- Индексы таблицы `filter_description`
--
ALTER TABLE `filter_description`
  ADD PRIMARY KEY (`filter_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `filter_group_id` (`filter_group_id`);

--
-- Индексы таблицы `filter_group`
--
ALTER TABLE `filter_group`
  ADD PRIMARY KEY (`filter_group_id`);

--
-- Индексы таблицы `filter_group_description`
--
ALTER TABLE `filter_group_description`
  ADD PRIMARY KEY (`filter_group_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `geo`
--
ALTER TABLE `geo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `geoname_alternatename`
--
ALTER TABLE `geoname_alternatename`
  ADD PRIMARY KEY (`alternatenameId`),
  ADD KEY `geonameid` (`geonameid`),
  ADD KEY `isoLanguage` (`isoLanguage`),
  ADD KEY `alternateName` (`alternateName`);

--
-- Индексы таблицы `geoname_geoname`
--
ALTER TABLE `geoname_geoname`
  ADD PRIMARY KEY (`geonameid`),
  ADD KEY `name` (`name`),
  ADD KEY `asciiname` (`asciiname`),
  ADD KEY `latitude` (`latitude`),
  ADD KEY `longitude` (`longitude`),
  ADD KEY `fclass` (`fclass`),
  ADD KEY `fcode` (`fcode`),
  ADD KEY `country` (`country`),
  ADD KEY `cc2` (`cc2`),
  ADD KEY `admin1` (`admin1`),
  ADD KEY `population` (`population`),
  ADD KEY `elevation` (`elevation`),
  ADD KEY `timezone` (`timezone`);

--
-- Индексы таблицы `geo_ip`
--
ALTER TABLE `geo_ip`
  ADD PRIMARY KEY (`start`,`end`);

--
-- Индексы таблицы `geo_zone`
--
ALTER TABLE `geo_zone`
  ADD PRIMARY KEY (`geo_zone_id`);

--
-- Индексы таблицы `google_base_category`
--
ALTER TABLE `google_base_category`
  ADD PRIMARY KEY (`google_base_category_id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `hj_any_feed_feeds`
--
ALTER TABLE `hj_any_feed_feeds`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `imagemaps`
--
ALTER TABLE `imagemaps`
  ADD PRIMARY KEY (`imagemap_id`),
  ADD KEY `module_code` (`module_code`),
  ADD KEY `module_id` (`module_id`);

--
-- Индексы таблицы `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`information_id`);

--
-- Индексы таблицы `information_attribute`
--
ALTER TABLE `information_attribute`
  ADD PRIMARY KEY (`information_attribute_id`);

--
-- Индексы таблицы `information_attribute_description`
--
ALTER TABLE `information_attribute_description`
  ADD PRIMARY KEY (`information_attribute_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `information_attribute_to_layout`
--
ALTER TABLE `information_attribute_to_layout`
  ADD PRIMARY KEY (`information_attribute_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Индексы таблицы `information_attribute_to_store`
--
ALTER TABLE `information_attribute_to_store`
  ADD PRIMARY KEY (`information_attribute_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `information_description`
--
ALTER TABLE `information_description`
  ADD PRIMARY KEY (`information_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `information_to_layout`
--
ALTER TABLE `information_to_layout`
  ADD PRIMARY KEY (`information_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Индексы таблицы `information_to_store`
--
ALTER TABLE `information_to_store`
  ADD PRIMARY KEY (`information_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `interplusplus`
--
ALTER TABLE `interplusplus`
  ADD PRIMARY KEY (`inter_id`);

--
-- Индексы таблицы `justin_cities`
--
ALTER TABLE `justin_cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `RegionUuid` (`RegionUuid`),
  ADD KEY `Descr` (`Descr`),
  ADD KEY `DescrRU` (`DescrRU`),
  ADD KEY `WarehouseCount` (`WarehouseCount`);

--
-- Индексы таблицы `justin_city_regions`
--
ALTER TABLE `justin_city_regions`
  ADD PRIMARY KEY (`region_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `CityUuid` (`CityUuid`);

--
-- Индексы таблицы `justin_streets`
--
ALTER TABLE `justin_streets`
  ADD PRIMARY KEY (`street_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `CityUuid` (`CityUuid`);

--
-- Индексы таблицы `justin_warehouses`
--
ALTER TABLE `justin_warehouses`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `StatusDepart` (`StatusDepart`),
  ADD KEY `RegionUuid` (`RegionUuid`),
  ADD KEY `CityUuid` (`CityUuid`),
  ADD KEY `CityDescr` (`CityDescr`),
  ADD KEY `CityDescrRU` (`CityDescrRU`),
  ADD KEY `Descr` (`Descr`),
  ADD KEY `DescrRU` (`DescrRU`);

--
-- Индексы таблицы `justin_zones`
--
ALTER TABLE `justin_zones`
  ADD PRIMARY KEY (`zone_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`);

--
-- Индексы таблицы `justin_zone_regions`
--
ALTER TABLE `justin_zone_regions`
  ADD PRIMARY KEY (`region_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `ZoneUuid` (`ZoneUuid`);

--
-- Индексы таблицы `keyworder`
--
ALTER TABLE `keyworder`
  ADD PRIMARY KEY (`keyworder_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `keyworder_description`
--
ALTER TABLE `keyworder_description`
  ADD PRIMARY KEY (`keyworder_id`,`language_id`),
  ADD KEY `keyworder_status` (`keyworder_status`),
  ADD KEY `category_status` (`category_status`);

--
-- Индексы таблицы `landingpage`
--
ALTER TABLE `landingpage`
  ADD PRIMARY KEY (`landingpage_id`);

--
-- Индексы таблицы `landingpage_description`
--
ALTER TABLE `landingpage_description`
  ADD PRIMARY KEY (`landingpage_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `landingpage_to_layout`
--
ALTER TABLE `landingpage_to_layout`
  ADD PRIMARY KEY (`landingpage_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Индексы таблицы `landingpage_to_store`
--
ALTER TABLE `landingpage_to_store`
  ADD PRIMARY KEY (`landingpage_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`),
  ADD KEY `name` (`name`),
  ADD KEY `urlcode` (`urlcode`),
  ADD KEY `code` (`code`),
  ADD KEY `status` (`status`),
  ADD KEY `front` (`front`);

--
-- Индексы таблицы `layout`
--
ALTER TABLE `layout`
  ADD PRIMARY KEY (`layout_id`);

--
-- Индексы таблицы `layout_route`
--
ALTER TABLE `layout_route`
  ADD PRIMARY KEY (`layout_route_id`),
  ADD KEY `layout_id` (`layout_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `route` (`route`),
  ADD KEY `store_id_route` (`store_id`,`route`) USING BTREE,
  ADD KEY `layout_id_store_id` (`layout_id`,`store_id`) USING BTREE;

--
-- Индексы таблицы `legalperson`
--
ALTER TABLE `legalperson`
  ADD PRIMARY KEY (`legalperson_id`),
  ADD KEY `legalperson_country_id` (`legalperson_country_id`);

--
-- Индексы таблицы `length_class`
--
ALTER TABLE `length_class`
  ADD PRIMARY KEY (`length_class_id`),
  ADD KEY `system_key` (`system_key`),
  ADD KEY `amazon_key` (`amazon_key`);

--
-- Индексы таблицы `length_class_description`
--
ALTER TABLE `length_class_description`
  ADD PRIMARY KEY (`length_class_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `local_supplier_products`
--
ALTER TABLE `local_supplier_products`
  ADD UNIQUE KEY `supplier_id_2` (`supplier_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `product_model` (`product_model`),
  ADD KEY `stock` (`stock`),
  ADD KEY `product_ean` (`product_ean`),
  ADD KEY `currency` (`currency`),
  ADD KEY `supplier_product_id` (`supplier_product_id`);

--
-- Индексы таблицы `mailwizz_queue`
--
ALTER TABLE `mailwizz_queue`
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `manager_kpi`
--
ALTER TABLE `manager_kpi`
  ADD UNIQUE KEY `manager_id` (`manager_id`,`date_added`),
  ADD KEY `manager_id_2` (`manager_id`);

--
-- Индексы таблицы `manager_order_status_dynamics`
--
ALTER TABLE `manager_order_status_dynamics`
  ADD UNIQUE KEY `manager_id_2` (`manager_id`,`order_status_id`,`date`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `order_status_id` (`order_status_id`);

--
-- Индексы таблицы `manager_order_status_dynamics2`
--
ALTER TABLE `manager_order_status_dynamics2`
  ADD UNIQUE KEY `manager_id_2` (`manager_id`,`order_status_id`,`date`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `order_status_id` (`order_status_id`);

--
-- Индексы таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`manufacturer_id`),
  ADD KEY `priceva_enable` (`priceva_enable`),
  ADD KEY `priceva_feed` (`priceva_feed`),
  ADD KEY `menu_brand` (`menu_brand`);

--
-- Индексы таблицы `manufacturer_description`
--
ALTER TABLE `manufacturer_description`
  ADD PRIMARY KEY (`manufacturer_id`,`language_id`),
  ADD KEY `location` (`location`);

--
-- Индексы таблицы `manufacturer_page_content`
--
ALTER TABLE `manufacturer_page_content`
  ADD PRIMARY KEY (`manufacturer_page_content_id`);

--
-- Индексы таблицы `manufacturer_to_layout`
--
ALTER TABLE `manufacturer_to_layout`
  ADD PRIMARY KEY (`manufacturer_id`,`store_id`);

--
-- Индексы таблицы `manufacturer_to_store`
--
ALTER TABLE `manufacturer_to_store`
  ADD PRIMARY KEY (`manufacturer_id`,`store_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `maxmind_geo_country`
--
ALTER TABLE `maxmind_geo_country`
  ADD PRIMARY KEY (`start`,`end`);

--
-- Индексы таблицы `multi_pay_payment`
--
ALTER TABLE `multi_pay_payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `operation_id` (`operation_id`),
  ADD KEY `datetime` (`datetime`);

--
-- Индексы таблицы `nauthor`
--
ALTER TABLE `nauthor`
  ADD PRIMARY KEY (`nauthor_id`);

--
-- Индексы таблицы `nauthor_description`
--
ALTER TABLE `nauthor_description`
  ADD PRIMARY KEY (`nauthor_id`,`language_id`);

--
-- Индексы таблицы `ncategory`
--
ALTER TABLE `ncategory`
  ADD PRIMARY KEY (`ncategory_id`);

--
-- Индексы таблицы `ncategory_description`
--
ALTER TABLE `ncategory_description`
  ADD PRIMARY KEY (`ncategory_id`,`language_id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `ncategory_to_layout`
--
ALTER TABLE `ncategory_to_layout`
  ADD PRIMARY KEY (`ncategory_id`,`store_id`);

--
-- Индексы таблицы `ncategory_to_store`
--
ALTER TABLE `ncategory_to_store`
  ADD PRIMARY KEY (`ncategory_id`,`store_id`);

--
-- Индексы таблицы `ncomments`
--
ALTER TABLE `ncomments`
  ADD PRIMARY KEY (`ncomment_id`),
  ADD KEY `news_id` (`news_id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Индексы таблицы `news_description`
--
ALTER TABLE `news_description`
  ADD PRIMARY KEY (`news_id`,`language_id`);

--
-- Индексы таблицы `news_gallery`
--
ALTER TABLE `news_gallery`
  ADD PRIMARY KEY (`news_image_id`);

--
-- Индексы таблицы `news_related`
--
ALTER TABLE `news_related`
  ADD PRIMARY KEY (`news_id`,`product_id`);

--
-- Индексы таблицы `news_to_layout`
--
ALTER TABLE `news_to_layout`
  ADD PRIMARY KEY (`news_id`,`store_id`);

--
-- Индексы таблицы `news_to_ncategory`
--
ALTER TABLE `news_to_ncategory`
  ADD PRIMARY KEY (`news_id`,`ncategory_id`);

--
-- Индексы таблицы `news_to_store`
--
ALTER TABLE `news_to_store`
  ADD PRIMARY KEY (`news_id`,`store_id`);

--
-- Индексы таблицы `news_video`
--
ALTER TABLE `news_video`
  ADD PRIMARY KEY (`news_video_id`);

--
-- Индексы таблицы `novaposhta_cities`
--
ALTER TABLE `novaposhta_cities`
  ADD PRIMARY KEY (`CityID`),
  ADD UNIQUE KEY `Ref` (`Ref`),
  ADD KEY `Area` (`Area`),
  ADD KEY `SettlementType` (`SettlementType`),
  ADD KEY `Description` (`Description`),
  ADD KEY `DescriptionRu` (`DescriptionRu`),
  ADD KEY `WarehouseCount` (`WarehouseCount`),
  ADD KEY `Warehouse` (`Warehouse`),
  ADD KEY `Index1` (`Index1`),
  ADD KEY `Index2` (`Index2`);

--
-- Индексы таблицы `novaposhta_cities_ww`
--
ALTER TABLE `novaposhta_cities_ww`
  ADD PRIMARY KEY (`CityID`),
  ADD UNIQUE KEY `Ref` (`Ref`),
  ADD KEY `Area` (`Area`),
  ADD KEY `WarehouseCount` (`WarehouseCount`),
  ADD KEY `Description` (`Description`),
  ADD KEY `DescriptionRu` (`DescriptionRu`);

--
-- Индексы таблицы `novaposhta_streets`
--
ALTER TABLE `novaposhta_streets`
  ADD PRIMARY KEY (`StreetID`),
  ADD UNIQUE KEY `Ref_2` (`Ref`),
  ADD KEY `CityRef` (`CityRef`);

--
-- Индексы таблицы `novaposhta_warehouses`
--
ALTER TABLE `novaposhta_warehouses`
  ADD PRIMARY KEY (`WarehouseID`),
  ADD UNIQUE KEY `Ref` (`Ref`),
  ADD KEY `WarehouseID` (`WarehouseID`),
  ADD KEY `TypeOfWarehouse` (`TypeOfWarehouse`),
  ADD KEY `CityRef` (`CityRef`),
  ADD KEY `SiteKey` (`SiteKey`),
  ADD KEY `TypeOfWarehouseRef` (`TypeOfWarehouseRef`),
  ADD KEY `TypeOfWarehouseRu` (`TypeOfWarehouseRu`),
  ADD KEY `WarehouseStatus` (`WarehouseStatus`);

--
-- Индексы таблицы `novaposhta_zones`
--
ALTER TABLE `novaposhta_zones`
  ADD PRIMARY KEY (`ZoneID`),
  ADD UNIQUE KEY `Ref` (`Ref`) USING BTREE,
  ADD KEY `AreasCenter` (`AreasCenter`);

--
-- Индексы таблицы `ocfilter_option`
--
ALTER TABLE `ocfilter_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `keyword` (`keyword`),
  ADD KEY `status` (`status`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Индексы таблицы `ocfilter_option_description`
--
ALTER TABLE `ocfilter_option_description`
  ADD PRIMARY KEY (`option_id`,`language_id`);

--
-- Индексы таблицы `ocfilter_option_to_category`
--
ALTER TABLE `ocfilter_option_to_category`
  ADD PRIMARY KEY (`category_id`,`option_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `ocfilter_option_to_store`
--
ALTER TABLE `ocfilter_option_to_store`
  ADD PRIMARY KEY (`store_id`,`option_id`);

--
-- Индексы таблицы `ocfilter_option_value`
--
ALTER TABLE `ocfilter_option_value`
  ADD PRIMARY KEY (`value_id`,`option_id`),
  ADD KEY `keyword` (`keyword`);

--
-- Индексы таблицы `ocfilter_option_value_description`
--
ALTER TABLE `ocfilter_option_value_description`
  ADD PRIMARY KEY (`value_id`,`language_id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `ocfilter_option_value_to_product`
--
ALTER TABLE `ocfilter_option_value_to_product`
  ADD PRIMARY KEY (`ocfilter_option_value_to_product_id`),
  ADD KEY `slide_value_min_slide_value_max` (`slide_value_min`,`slide_value_max`),
  ADD KEY `option_id_value_id` (`option_id`,`value_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `ocfilter_option_value_to_product_description`
--
ALTER TABLE `ocfilter_option_value_to_product_description`
  ADD PRIMARY KEY (`product_id`,`value_id`,`option_id`,`language_id`);

--
-- Индексы таблицы `ocfilter_page`
--
ALTER TABLE `ocfilter_page`
  ADD PRIMARY KEY (`ocfilter_page_id`),
  ADD KEY `category_id_ocfilter_params` (`category_id`,`ocfilter_params`),
  ADD KEY `keyword` (`keyword`);

--
-- Индексы таблицы `oc_feedback`
--
ALTER TABLE `oc_feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Индексы таблицы `oc_sms_log`
--
ALTER TABLE `oc_sms_log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `oc_yandex_category`
--
ALTER TABLE `oc_yandex_category`
  ADD PRIMARY KEY (`yandex_category_id`),
  ADD KEY `level1` (`level1`,`level2`,`level3`),
  ADD KEY `level4` (`level4`);

--
-- Индексы таблицы `odinass_product_queue`
--
ALTER TABLE `odinass_product_queue`
  ADD PRIMARY KEY (`product_id`);

--
-- Индексы таблицы `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Индексы таблицы `option_description`
--
ALTER TABLE `option_description`
  ADD PRIMARY KEY (`option_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `option_tooltip`
--
ALTER TABLE `option_tooltip`
  ADD PRIMARY KEY (`option_id`,`language_id`);

--
-- Индексы таблицы `option_value`
--
ALTER TABLE `option_value`
  ADD PRIMARY KEY (`option_value_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Индексы таблицы `option_value_description`
--
ALTER TABLE `option_value_description`
  ADD PRIMARY KEY (`option_value_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `customer_group_id` (`customer_group_id`),
  ADD KEY `payment_company_id` (`payment_company_id`),
  ADD KEY `payment_tax_id` (`payment_tax_id`),
  ADD KEY `payment_country_id` (`payment_country_id`),
  ADD KEY `payment_zone_id` (`payment_zone_id`),
  ADD KEY `shipping_country_id` (`shipping_country_id`),
  ADD KEY `shipping_zone_id` (`shipping_zone_id`),
  ADD KEY `order_status_id` (`order_status_id`),
  ADD KEY `affiliate_id` (`affiliate_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `invoice_prefix` (`invoice_prefix`),
  ADD KEY `from_waitlist` (`from_waitlist`),
  ADD KEY `card_id` (`card_id`),
  ADD KEY `probably_close` (`probably_close`),
  ADD KEY `probably_problem` (`probably_problem`),
  ADD KEY `probably_cancel` (`probably_cancel`),
  ADD KEY `csi_average` (`csi_average`),
  ADD KEY `csi_reject` (`csi_reject`),
  ADD KEY `date_modified` (`date_modified`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `salary_paid` (`salary_paid`),
  ADD KEY `closed` (`closed`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `tracking_id` (`tracking_id`),
  ADD KEY `template` (`template`),
  ADD KEY `preorder` (`preorder`),
  ADD KEY `shipping_code` (`shipping_code`),
  ADD KEY `payment_code` (`payment_code`),
  ADD KEY `courier_id` (`courier_id`),
  ADD KEY `marketplace` (`marketplace`),
  ADD KEY `telephone` (`telephone`),
  ADD KEY `firstname` (`firstname`),
  ADD KEY `email` (`email`),
  ADD KEY `shipping_city` (`shipping_city`),
  ADD KEY `date_delivery` (`date_delivery`),
  ADD KEY `ua_logistics` (`ua_logistics`),
  ADD KEY `concardis_id` (`concardis_id`),
  ADD KEY `legalperson_id` (`legalperson_id`),
  ADD KEY `date_added_timestamp` (`date_added_timestamp`),
  ADD KEY `pwa` (`pwa`),
  ADD KEY `yam` (`yam`),
  ADD KEY `yam_id` (`yam_id`),
  ADD KEY `yam_status` (`yam_status`),
  ADD KEY `yam_substatus` (`yam_substatus`),
  ADD KEY `yam_shipment_id` (`yam_shipment_id`),
  ADD KEY `fcheque_link` (`fcheque_link`);

--
-- Индексы таблицы `order_amazon`
--
ALTER TABLE `order_amazon`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `amazon_order_id` (`amazon_order_id`);

--
-- Индексы таблицы `order_amazon_product`
--
ALTER TABLE `order_amazon_product`
  ADD PRIMARY KEY (`order_product_id`);

--
-- Индексы таблицы `order_amazon_report`
--
ALTER TABLE `order_amazon_report`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `order_courier_history`
--
ALTER TABLE `order_courier_history`
  ADD UNIQUE KEY `order_id_2` (`order_id`,`status`,`courier_id`) USING BTREE,
  ADD KEY `order_id` (`order_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `order_download`
--
ALTER TABLE `order_download`
  ADD PRIMARY KEY (`order_download_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_product_id` (`order_product_id`);

--
-- Индексы таблицы `order_field`
--
ALTER TABLE `order_field`
  ADD PRIMARY KEY (`order_id`,`custom_field_id`,`custom_field_value_id`),
  ADD KEY `custom_field_id` (`custom_field_id`),
  ADD KEY `custom_field_value_id` (`custom_field_value_id`);

--
-- Индексы таблицы `order_fraud`
--
ALTER TABLE `order_fraud`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `maxmind_id` (`maxmind_id`);

--
-- Индексы таблицы `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`order_history_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_status_id` (`order_status_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `courier` (`courier`);

--
-- Индексы таблицы `order_invoice_history`
--
ALTER TABLE `order_invoice_history`
  ADD PRIMARY KEY (`order_invoice_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_option`
--
ALTER TABLE `order_option`
  ADD PRIMARY KEY (`order_option_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_product_id` (`order_product_id`),
  ADD KEY `product_option_id` (`product_option_id`),
  ADD KEY `product_option_value_id` (`product_option_value_id`),
  ADD KEY `order_product_id_2` (`order_product_id`,`type`,`name`,`product_option_value_id`);

--
-- Индексы таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_id_2` (`product_id`,`total`,`price`,`tax`,`quantity`),
  ADD KEY `sort_order` (`sort_order`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `part_num` (`part_num`),
  ADD KEY `delivery_num` (`delivery_num`),
  ADD KEY `from_stock` (`from_stock`),
  ADD KEY `date_added` (`date_added_fo`),
  ADD KEY `reward` (`reward`),
  ADD KEY `reward_one` (`reward_one`),
  ADD KEY `ao_id` (`ao_id`);

--
-- Индексы таблицы `order_product_bought`
--
ALTER TABLE `order_product_bought`
  ADD PRIMARY KEY (`bought_id`);

--
-- Индексы таблицы `order_product_history`
--
ALTER TABLE `order_product_history`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `order_product_nogood`
--
ALTER TABLE `order_product_nogood`
  ADD UNIQUE KEY `order_product_id` (`order_product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `order_product_reserves`
--
ALTER TABLE `order_product_reserves`
  ADD PRIMARY KEY (`order_reserve_id`),
  ADD KEY `order_product_id` (`order_product_id`),
  ADD KEY `country_id` (`country_code`),
  ADD KEY `uuid` (`uuid`);

--
-- Индексы таблицы `order_product_supply`
--
ALTER TABLE `order_product_supply`
  ADD PRIMARY KEY (`order_product_supply_id`);

--
-- Индексы таблицы `order_product_tracker`
--
ALTER TABLE `order_product_tracker`
  ADD PRIMARY KEY (`order_product_tracker_id`),
  ADD KEY `order_product` (`order_product`),
  ADD KEY `order_product_status` (`order_product_status`);

--
-- Индексы таблицы `order_product_untaken`
--
ALTER TABLE `order_product_untaken`
  ADD KEY `order_product_id` (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `order_recurring`
--
ALTER TABLE `order_recurring`
  ADD PRIMARY KEY (`order_recurring_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `profile_id` (`profile_id`);

--
-- Индексы таблицы `order_recurring_transaction`
--
ALTER TABLE `order_recurring_transaction`
  ADD PRIMARY KEY (`order_recurring_transaction_id`),
  ADD KEY `order_recurring_id` (`order_recurring_id`);

--
-- Индексы таблицы `order_reject_reason`
--
ALTER TABLE `order_reject_reason`
  ADD KEY `reject_reason_id` (`reject_reason_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `order_related`
--
ALTER TABLE `order_related`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `related_order_id` (`related_order_id`);

--
-- Индексы таблицы `order_save_history`
--
ALTER TABLE `order_save_history`
  ADD PRIMARY KEY (`order_save_id`),
  ADD KEY `datetime` (`datetime`);

--
-- Индексы таблицы `order_set`
--
ALTER TABLE `order_set`
  ADD PRIMARY KEY (`order_set_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `order_simple_fields`
--
ALTER TABLE `order_simple_fields`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `novaposhta_city_guid` (`novaposhta_city_guid`),
  ADD KEY `cdek_city_guid` (`cdek_city_guid`);
ALTER TABLE `order_simple_fields` ADD FULLTEXT KEY `metadata` (`metadata`);

--
-- Индексы таблицы `order_sms_history`
--
ALTER TABLE `order_sms_history`
  ADD PRIMARY KEY (`order_history_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `order_status_id` (`order_status_id`);

--
-- Индексы таблицы `order_total`
--
ALTER TABLE `order_total`
  ADD PRIMARY KEY (`order_total_id`),
  ADD KEY `idx_orders_total_orders_id` (`order_id`),
  ADD KEY `order_id` (`order_id`,`value`,`code`),
  ADD KEY `title` (`title`(255)),
  ADD KEY `for_delivery` (`for_delivery`),
  ADD KEY `value_national` (`value_national`),
  ADD KEY `code` (`code`,`title`);

--
-- Индексы таблицы `order_total_tax`
--
ALTER TABLE `order_total_tax`
  ADD PRIMARY KEY (`order_total_id`);

--
-- Индексы таблицы `order_to_1c_queue`
--
ALTER TABLE `order_to_1c_queue`
  ADD PRIMARY KEY (`order_id`);

--
-- Индексы таблицы `order_tracker`
--
ALTER TABLE `order_tracker`
  ADD PRIMARY KEY (`order_tracker_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `order_tracker_sms`
--
ALTER TABLE `order_tracker_sms`
  ADD PRIMARY KEY (`tracker_sms_id`),
  ADD KEY `partie_num` (`partie_num`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `tracker_type` (`tracker_type`);

--
-- Индексы таблицы `order_ttns`
--
ALTER TABLE `order_ttns`
  ADD PRIMARY KEY (`order_ttn_id`);

--
-- Индексы таблицы `order_voucher`
--
ALTER TABLE `order_voucher`
  ADD PRIMARY KEY (`order_voucher_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `voucher_theme_id` (`voucher_theme_id`);

--
-- Индексы таблицы `parser_queue`
--
ALTER TABLE `parser_queue`
  ADD PRIMARY KEY (`parser_queue_id`);

--
-- Индексы таблицы `pavoslidergroups`
--
ALTER TABLE `pavoslidergroups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pavosliderlayers`
--
ALTER TABLE `pavosliderlayers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `paypal_iframe_order`
--
ALTER TABLE `paypal_iframe_order`
  ADD PRIMARY KEY (`paypal_iframe_order_id`);

--
-- Индексы таблицы `paypal_iframe_order_transaction`
--
ALTER TABLE `paypal_iframe_order_transaction`
  ADD PRIMARY KEY (`paypal_iframe_order_transaction_id`);

--
-- Индексы таблицы `paypal_order`
--
ALTER TABLE `paypal_order`
  ADD PRIMARY KEY (`paypal_order_id`);

--
-- Индексы таблицы `paypal_order_transaction`
--
ALTER TABLE `paypal_order_transaction`
  ADD PRIMARY KEY (`paypal_order_transaction_id`);

--
-- Индексы таблицы `priceva_data`
--
ALTER TABLE `priceva_data`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `articul` (`articul`),
  ADD KEY `default_currency` (`default_currency`),
  ADD KEY `default_available` (`default_available`),
  ADD KEY `category_name` (`category_name`);

--
-- Индексы таблицы `priceva_sources`
--
ALTER TABLE `priceva_sources`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`,`url_md5`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `active` (`active`),
  ADD KEY `status` (`status`),
  ADD KEY `currency` (`currency`),
  ADD KEY `in_stock` (`in_stock`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `model` (`model`),
  ADD KEY `sku` (`sku`),
  ADD KEY `ean` (`ean`),
  ADD KEY `mpn` (`mpn`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `upc` (`upc`) USING BTREE,
  ADD KEY `product_id` (`product_id`,`model`,`sku`,`manufacturer_id`,`sort_order`,`status`),
  ADD KEY `color_group` (`color_group`),
  ADD KEY `is_virtual` (`is_virtual`),
  ADD KEY `asin` (`asin`),
  ADD KEY `date_available` (`date_available`),
  ADD KEY `is_option_for_product_id` (`is_option_for_product_id`),
  ADD KEY `is_option_with_id` (`is_option_with_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `stock_product_id` (`stock_product_id`),
  ADD KEY `has_child` (`has_child`),
  ADD KEY `quantity` (`quantity`),
  ADD KEY `weight_class_id` (`weight_class_id`),
  ADD KEY `length_class_id` (`length_class_id`),
  ADD KEY `quantity_stock` (`quantity_stock`),
  ADD KEY `quantity_stockK` (`quantity_stockK`),
  ADD KEY `new` (`new`),
  ADD KEY `stock_status_id` (`stock_status_id`),
  ADD KEY `is_markdown` (`is_markdown`),
  ADD KEY `markdown_product_id` (`markdown_product_id`),
  ADD KEY `lock_points` (`lock_points`),
  ADD KEY `priceva_enable` (`priceva_enable`),
  ADD KEY `priceva_disable` (`priceva_disable`),
  ADD KEY `yam_product_id` (`yam_product_id`),
  ADD KEY `yam_in_feed` (`yam_in_feed`),
  ADD KEY `yam_hidden` (`yam_hidden`),
  ADD KEY `is_illiquid` (`is_illiquid`),
  ADD KEY `amzn_invalid_asin` (`amzn_invalid_asin`),
  ADD KEY `amzn_not_found` (`amzn_not_found`),
  ADD KEY `amzn_last_search` (`amzn_last_search`),
  ADD KEY `amzn_no_offers` (`amzn_no_offers`),
  ADD KEY `amzn_ignore` (`amzn_ignore`),
  ADD KEY `quantity_stockK_onway` (`quantity_stockK_onway`),
  ADD KEY `quantity_stock_onway` (`quantity_stock_onway`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `new_date_to` (`new_date_to`),
  ADD KEY `added_from_amazon` (`added_from_amazon`),
  ADD KEY `main_variant_id` (`main_variant_id`),
  ADD KEY `display_in_catalog` (`display_in_catalog`),
  ADD KEY `variant_1_is_color` (`variant_1_is_color`),
  ADD KEY `variant_2_is_color` (`variant_2_is_color`),
  ADD KEY `filled_from_amazon` (`filled_from_amazon`),
  ADD KEY `fill_from_amazon` (`fill_from_amazon`),
  ADD KEY `price` (`price`),
  ADD KEY `price_national` (`price_national`),
  ADD KEY `amzn_offers_count` (`amzn_offers_count`),
  ADD KEY `amzn_rating` (`amzn_rating`),
  ADD KEY `xrating` (`xrating`),
  ADD KEY `amazon_best_price` (`amazon_best_price`),
  ADD KEY `viewed` (`viewed`),
  ADD KEY `quantity_updateMarker` (`quantity_updateMarker`);

--
-- Индексы таблицы `product_additional_offer`
--
ALTER TABLE `product_additional_offer`
  ADD PRIMARY KEY (`product_additional_offer_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `date_end` (`date_end`),
  ADD KEY `percent` (`percent`),
  ADD KEY `ao_product_id` (`ao_product_id`),
  ADD KEY `customer_group_id` (`customer_group_id`),
  ADD KEY `date_start` (`date_start`);

--
-- Индексы таблицы `product_additional_offer_to_store`
--
ALTER TABLE `product_additional_offer_to_store`
  ADD KEY `product_additional_offer_id` (`product_additional_offer_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `product_also_bought`
--
ALTER TABLE `product_also_bought`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`also_bought_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `also_bought_id` (`also_bought_id`);

--
-- Индексы таблицы `product_also_viewed`
--
ALTER TABLE `product_also_viewed`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`also_viewed_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `also_viewed_id` (`also_viewed_id`);

--
-- Индексы таблицы `product_amzn_data`
--
ALTER TABLE `product_amzn_data`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `asin` (`asin`);

--
-- Индексы таблицы `product_amzn_offers`
--
ALTER TABLE `product_amzn_offers`
  ADD PRIMARY KEY (`amazon_offer_id`),
  ADD KEY `product_id` (`asin`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `is_min_price` (`is_min_price`),
  ADD KEY `isPrime` (`isPrime`),
  ADD KEY `isBestOffer` (`isBestOffer`),
  ADD KEY `isBuyBoxWinner` (`isBuyBoxWinner`),
  ADD KEY `minDays` (`minDays`);

--
-- Индексы таблицы `product_anyrelated`
--
ALTER TABLE `product_anyrelated`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `anyrelated_id` (`anyrelated_id`);

--
-- Индексы таблицы `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD PRIMARY KEY (`product_id`,`attribute_id`,`language_id`),
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_id_language_id` (`product_id`,`language_id`) USING BTREE,
  ADD KEY `attribute_id_language_id` (`attribute_id`,`language_id`) USING BTREE,
  ADD KEY `text` (`text`(1024)) USING BTREE;

--
-- Индексы таблицы `product_barbara_tab`
--
ALTER TABLE `product_barbara_tab`
  ADD PRIMARY KEY (`product_additional_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_child`
--
ALTER TABLE `product_child`
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_costs`
--
ALTER TABLE `product_costs`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Индексы таблицы `product_description`
--
ALTER TABLE `product_description`
  ADD PRIMARY KEY (`product_id`,`language_id`),
  ADD KEY `name` (`name`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `translated` (`translated`),
  ADD KEY `color` (`color`),
  ADD KEY `material` (`material`),
  ADD KEY `variant_name` (`variant_name`),
  ADD KEY `variant_name_1` (`variant_name_1`),
  ADD KEY `variant_name_2` (`variant_name_2`),
  ADD KEY `variant_value_1` (`variant_value_1`),
  ADD KEY `variant_value_2` (`variant_value_2`),
  ADD KEY `short_name_d` (`short_name_d`);

--
-- Индексы таблицы `product_discount`
--
ALTER TABLE `product_discount`
  ADD PRIMARY KEY (`product_discount_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_filter`
--
ALTER TABLE `product_filter`
  ADD PRIMARY KEY (`product_id`,`filter_id`);

--
-- Индексы таблицы `product_front_price`
--
ALTER TABLE `product_front_price`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Индексы таблицы `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`product_image_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Индексы таблицы `product_master`
--
ALTER TABLE `product_master`
  ADD PRIMARY KEY (`master_product_id`,`product_id`,`special_attribute_group_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_option`
--
ALTER TABLE `product_option`
  ADD PRIMARY KEY (`product_option_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Индексы таблицы `product_option_value`
--
ALTER TABLE `product_option_value`
  ADD PRIMARY KEY (`product_option_value_id`),
  ADD KEY `option_value_id` (`option_value_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_option_id` (`product_option_id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `subtract` (`subtract`),
  ADD KEY `quantity` (`quantity`);

--
-- Индексы таблицы `product_price_history`
--
ALTER TABLE `product_price_history`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`),
  ADD KEY `type` (`type`),
  ADD KEY `source` (`source`);

--
-- Индексы таблицы `product_price_national_to_store`
--
ALTER TABLE `product_price_national_to_store`
  ADD UNIQUE KEY `product_store` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`),
  ADD KEY `price` (`price`);

--
-- Индексы таблицы `product_price_national_to_store1`
--
ALTER TABLE `product_price_national_to_store1`
  ADD UNIQUE KEY `product_store` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Индексы таблицы `product_price_national_to_yam`
--
ALTER TABLE `product_price_national_to_yam`
  ADD UNIQUE KEY `product_store` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Индексы таблицы `product_price_to_store`
--
ALTER TABLE `product_price_to_store`
  ADD PRIMARY KEY (`product_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `settled_from_1c` (`settled_from_1c`),
  ADD KEY `dot_not_overload_1c` (`dot_not_overload_1c`),
  ADD KEY `price` (`price`);

--
-- Индексы таблицы `product_product_option`
--
ALTER TABLE `product_product_option`
  ADD PRIMARY KEY (`product_product_option_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `product_product_option_value`
--
ALTER TABLE `product_product_option_value`
  ADD PRIMARY KEY (`product_product_option_value_id`),
  ADD KEY `product_product_option_id` (`product_product_option_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_option_id` (`product_option_id`);

--
-- Индексы таблицы `product_profile`
--
ALTER TABLE `product_profile`
  ADD PRIMARY KEY (`product_id`,`profile_id`,`customer_group_id`);

--
-- Индексы таблицы `product_purchase`
--
ALTER TABLE `product_purchase`
  ADD KEY `purchase_uuid` (`purchase_uuid`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `product_recurring`
--
ALTER TABLE `product_recurring`
  ADD PRIMARY KEY (`product_id`,`store_id`);

--
-- Индексы таблицы `product_related`
--
ALTER TABLE `product_related`
  ADD PRIMARY KEY (`product_id`,`related_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `related_id` (`related_id`);

--
-- Индексы таблицы `product_related_set`
--
ALTER TABLE `product_related_set`
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_reward`
--
ALTER TABLE `product_reward`
  ADD PRIMARY KEY (`product_reward_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_group_id` (`customer_group_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `percent` (`percent`),
  ADD KEY `coupon_acts` (`coupon_acts`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `date_end` (`date_end`);

--
-- Индексы таблицы `product_shop_by_look`
--
ALTER TABLE `product_shop_by_look`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`shop_by_look_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `shop_by_look_id` (`shop_by_look_id`);

--
-- Индексы таблицы `product_similar`
--
ALTER TABLE `product_similar`
  ADD PRIMARY KEY (`product_id`,`similar_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `similar_id` (`similar_id`);

--
-- Индексы таблицы `product_similar_to_consider`
--
ALTER TABLE `product_similar_to_consider`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`similar_to_consider_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `similar_to_consider_id` (`similar_to_consider_id`);

--
-- Индексы таблицы `product_sources`
--
ALTER TABLE `product_sources`
  ADD PRIMARY KEY (`product_source_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `source` (`source`(255));

--
-- Индексы таблицы `product_special`
--
ALTER TABLE `product_special`
  ADD PRIMARY KEY (`product_special_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `priority` (`priority`),
  ADD KEY `currency` (`currency_scode`),
  ADD KEY `price` (`price`),
  ADD KEY `set_by_stock` (`set_by_stock`),
  ADD KEY `set_by_stock_illiquid` (`set_by_stock_illiquid`),
  ADD KEY `date_settled_by_stock` (`date_settled_by_stock`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `date_end` (`date_end`);

--
-- Индексы таблицы `product_special_attribute`
--
ALTER TABLE `product_special_attribute`
  ADD PRIMARY KEY (`product_special_attribute_id`);

--
-- Индексы таблицы `product_special_backup`
--
ALTER TABLE `product_special_backup`
  ADD PRIMARY KEY (`product_special_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `priority` (`priority`);

--
-- Индексы таблицы `product_sponsored`
--
ALTER TABLE `product_sponsored`
  ADD PRIMARY KEY (`product_id`,`sponsored_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sponsored_id` (`sponsored_id`);

--
-- Индексы таблицы `product_status`
--
ALTER TABLE `product_status`
  ADD KEY `status_id` (`status_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_sticker`
--
ALTER TABLE `product_sticker`
  ADD PRIMARY KEY (`product_sticker_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_stock_limits`
--
ALTER TABLE `product_stock_limits`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `min_buy` (`min_stock`),
  ADD KEY `rec_buy` (`rec_stock`);

--
-- Индексы таблицы `product_stock_status`
--
ALTER TABLE `product_stock_status`
  ADD UNIQUE KEY `product_id_3` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `product_stock_waits`
--
ALTER TABLE `product_stock_waits`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `quantity_stockM` (`quantity_stockM`),
  ADD KEY `quantity_stockK` (`quantity_stockK`),
  ADD KEY `quantity_stockMN` (`quantity_stockMN`),
  ADD KEY `quantity_stockAS` (`quantity_stockAS`),
  ADD KEY `quantity_stock` (`quantity_stock`);

--
-- Индексы таблицы `product_tab`
--
ALTER TABLE `product_tab`
  ADD PRIMARY KEY (`tab_id`);

--
-- Индексы таблицы `product_tab_content`
--
ALTER TABLE `product_tab_content`
  ADD PRIMARY KEY (`product_id`,`language_id`,`tab_id`);

--
-- Индексы таблицы `product_tab_default`
--
ALTER TABLE `product_tab_default`
  ADD PRIMARY KEY (`tab_id`,`language_id`);

--
-- Индексы таблицы `product_tab_name`
--
ALTER TABLE `product_tab_name`
  ADD PRIMARY KEY (`tab_id`,`language_id`);

--
-- Индексы таблицы `product_tmp`
--
ALTER TABLE `product_tmp`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `model` (`model`),
  ADD KEY `sku` (`sku`),
  ADD KEY `ean` (`ean`),
  ADD KEY `jan` (`jan`),
  ADD KEY `isbn` (`isbn`),
  ADD KEY `mpn` (`mpn`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `upc` (`upc`) USING BTREE,
  ADD KEY `product_id` (`product_id`,`model`,`sku`,`manufacturer_id`,`sort_order`,`status`),
  ADD KEY `virtual_isbn` (`virtual_isbn`),
  ADD KEY `color_group` (`color_group`),
  ADD KEY `is_virtual` (`is_virtual`),
  ADD KEY `asin` (`asin`),
  ADD KEY `date_available` (`date_available`),
  ADD KEY `is_option_for_product_id` (`is_option_for_product_id`),
  ADD KEY `is_option_with_id` (`is_option_with_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `stock_product_id` (`stock_product_id`),
  ADD KEY `weight_amazon_key` (`weight_amazon_key`),
  ADD KEY `length_amazon_key` (`length_amazon_key`),
  ADD KEY `pack_weight_amazon_key` (`pack_weight_amazon_key`),
  ADD KEY `pack_length_amazon_key` (`pack_length_amazon_key`),
  ADD KEY `is_related_set` (`is_related_set`),
  ADD KEY `has_child` (`has_child`),
  ADD KEY `big_business` (`big_business`),
  ADD KEY `quantity` (`quantity`),
  ADD KEY `weight_class_id` (`weight_class_id`),
  ADD KEY `length_class_id` (`length_class_id`),
  ADD KEY `quantity_stock` (`quantity_stock`),
  ADD KEY `quantity_stockM` (`quantity_stockM`),
  ADD KEY `quantity_stockK` (`quantity_stockK`),
  ADD KEY `new` (`new`),
  ADD KEY `stock_status_id` (`stock_status_id`);

--
-- Индексы таблицы `product_to_category`
--
ALTER TABLE `product_to_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `main_category` (`main_category`);

--
-- Индексы таблицы `product_to_download`
--
ALTER TABLE `product_to_download`
  ADD PRIMARY KEY (`product_id`,`download_id`);

--
-- Индексы таблицы `product_to_layout`
--
ALTER TABLE `product_to_layout`
  ADD PRIMARY KEY (`product_id`,`store_id`);

--
-- Индексы таблицы `product_to_set`
--
ALTER TABLE `product_to_set`
  ADD KEY `set_id` (`set_id`),
  ADD KEY `clean_product_id` (`clean_product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_to_store`
--
ALTER TABLE `product_to_store`
  ADD PRIMARY KEY (`product_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_to_tab`
--
ALTER TABLE `product_to_tab`
  ADD PRIMARY KEY (`product_id`,`tab_id`);

--
-- Индексы таблицы `product_variants`
--
ALTER TABLE `product_variants`
  ADD UNIQUE KEY `variant_asin` (`variant_asin`) USING BTREE,
  ADD KEY `main_asin` (`main_asin`) USING BTREE;

--
-- Индексы таблицы `product_variants_ids`
--
ALTER TABLE `product_variants_ids`
  ADD UNIQUE KEY `variant_id` (`variant_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_video`
--
ALTER TABLE `product_video`
  ADD PRIMARY KEY (`product_video_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Индексы таблицы `product_video_description`
--
ALTER TABLE `product_video_description`
  ADD KEY `language_id` (`language_id`),
  ADD KEY `product_video_id` (`product_video_id`),
  ADD KEY `product_video_id_2` (`product_video_id`,`language_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_view_to_purchase`
--
ALTER TABLE `product_view_to_purchase`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`view_to_purchase_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `view_to_purchase_id` (`view_to_purchase_id`);

--
-- Индексы таблицы `product_yam_data`
--
ALTER TABLE `product_yam_data`
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `yam_category_id` (`yam_category_id`);

--
-- Индексы таблицы `product_yam_recommended_prices`
--
ALTER TABLE `product_yam_recommended_prices`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Индексы таблицы `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`);

--
-- Индексы таблицы `profile_description`
--
ALTER TABLE `profile_description`
  ADD PRIMARY KEY (`profile_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `queue_mail`
--
ALTER TABLE `queue_mail`
  ADD PRIMARY KEY (`queue_mail_id`);

--
-- Индексы таблицы `queue_push`
--
ALTER TABLE `queue_push`
  ADD PRIMARY KEY (`queue_push_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `queue_sms`
--
ALTER TABLE `queue_sms`
  ADD PRIMARY KEY (`queue_sms_id`);

--
-- Индексы таблицы `redirect`
--
ALTER TABLE `redirect`
  ADD PRIMARY KEY (`redirect_id`),
  ADD KEY `active` (`active`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `date_end` (`date_end`),
  ADD KEY `from_url` (`from_url`(255));

--
-- Индексы таблицы `referrer_patterns`
--
ALTER TABLE `referrer_patterns`
  ADD PRIMARY KEY (`pattern_id`);

--
-- Индексы таблицы `return`
--
ALTER TABLE `return`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `return_reason_id` (`return_reason_id`),
  ADD KEY `return_action_id` (`return_action_id`),
  ADD KEY `return_status_id` (`return_status_id`),
  ADD KEY `reorder_id` (`reorder_id`);

--
-- Индексы таблицы `return_action`
--
ALTER TABLE `return_action`
  ADD PRIMARY KEY (`return_action_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `return_history`
--
ALTER TABLE `return_history`
  ADD PRIMARY KEY (`return_history_id`),
  ADD KEY `return_id` (`return_id`),
  ADD KEY `return_status_id` (`return_status_id`);

--
-- Индексы таблицы `return_reason`
--
ALTER TABLE `return_reason`
  ADD PRIMARY KEY (`return_reason_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `return_status`
--
ALTER TABLE `return_status`
  ADD PRIMARY KEY (`return_status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `rating` (`rating`),
  ADD KEY `status` (`status`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `review_description`
--
ALTER TABLE `review_description`
  ADD UNIQUE KEY `review_id_2` (`review_id`,`language_id`),
  ADD KEY `review_id` (`review_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `review_fields`
--
ALTER TABLE `review_fields`
  ADD KEY `review_id` (`review_id`);

--
-- Индексы таблицы `review_name`
--
ALTER TABLE `review_name`
  ADD PRIMARY KEY (`review_name_id`);

--
-- Индексы таблицы `review_template`
--
ALTER TABLE `review_template`
  ADD PRIMARY KEY (`review_template_id`);

--
-- Индексы таблицы `search_history`
--
ALTER TABLE `search_history`
  ADD UNIQUE KEY `text` (`text`),
  ADD KEY `times` (`times`),
  ADD KEY `results` (`results`),
  ADD KEY `times_2` (`times`,`results`);

--
-- Индексы таблицы `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`segment_id`),
  ADD KEY `sort_order` (`sort_order`),
  ADD KEY `group` (`group`);

--
-- Индексы таблицы `segments_dynamics`
--
ALTER TABLE `segments_dynamics`
  ADD PRIMARY KEY (`segment_dynamics_id`),
  ADD KEY `segment_id` (`segment_id`) USING BTREE,
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `seocities`
--
ALTER TABLE `seocities`
  ADD PRIMARY KEY (`seocity_id`);

--
-- Индексы таблицы `seo_hreflang`
--
ALTER TABLE `seo_hreflang`
  ADD UNIQUE KEY `language_id_2` (`language_id`,`query`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `query` (`query`);

--
-- Индексы таблицы `set`
--
ALTER TABLE `set`
  ADD PRIMARY KEY (`set_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `tax_class_id` (`tax_class_id`);

--
-- Индексы таблицы `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `group` (`group`),
  ADD KEY `key` (`key`),
  ADD KEY `value` (`value`(1024)),
  ADD KEY `group_2` (`group`,`key`);

--
-- Индексы таблицы `set_description`
--
ALTER TABLE `set_description`
  ADD PRIMARY KEY (`set_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `set_to_category`
--
ALTER TABLE `set_to_category`
  ADD PRIMARY KEY (`set_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `set_to_store`
--
ALTER TABLE `set_to_store`
  ADD PRIMARY KEY (`set_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `shoputils_citycourier_description`
--
ALTER TABLE `shoputils_citycourier_description`
  ADD UNIQUE KEY `IDX_oc_shoputils_citycourier_description` (`language_id`);

--
-- Индексы таблицы `shoputils_cumulative_discounts`
--
ALTER TABLE `shoputils_cumulative_discounts`
  ADD PRIMARY KEY (`discount_id`);

--
-- Индексы таблицы `shoputils_cumulative_discounts_description`
--
ALTER TABLE `shoputils_cumulative_discounts_description`
  ADD UNIQUE KEY `IDX_shoputils_cumulative_discounts_description` (`discount_id`,`language_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `shoputils_cumulative_discounts_to_customer_group`
--
ALTER TABLE `shoputils_cumulative_discounts_to_customer_group`
  ADD UNIQUE KEY `IDX_shoputils_cumulative_discounts_to_customer_group` (`discount_id`,`customer_group_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `customer_group_id` (`customer_group_id`);

--
-- Индексы таблицы `shoputils_cumulative_discounts_to_manufacturer`
--
ALTER TABLE `shoputils_cumulative_discounts_to_manufacturer`
  ADD UNIQUE KEY `discount_id` (`discount_id`,`manufacturer_id`),
  ADD KEY `discount_id_2` (`discount_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`);

--
-- Индексы таблицы `shoputils_cumulative_discounts_to_store`
--
ALTER TABLE `shoputils_cumulative_discounts_to_store`
  ADD UNIQUE KEY `IDX_shoputils_cumulative_discounts_to_store` (`discount_id`,`store_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `shop_rating`
--
ALTER TABLE `shop_rating`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `shop_rating_answers`
--
ALTER TABLE `shop_rating_answers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `shop_rating_custom_types`
--
ALTER TABLE `shop_rating_custom_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `shop_rating_custom_values`
--
ALTER TABLE `shop_rating_custom_values`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `shop_rating_description`
--
ALTER TABLE `shop_rating_description`
  ADD UNIQUE KEY `rate_id_2` (`rate_id`,`language_id`),
  ADD KEY `rate_id` (`rate_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `short_url_alias`
--
ALTER TABLE `short_url_alias`
  ADD PRIMARY KEY (`url_id`),
  ADD KEY `url` (`url`),
  ADD KEY `alias` (`alias`),
  ADD KEY `date_added` (`date_added`);

--
-- Индексы таблицы `simple_cart`
--
ALTER TABLE `simple_cart`
  ADD PRIMARY KEY (`simple_cart_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `email` (`email`);

--
-- Индексы таблицы `simple_custom_data`
--
ALTER TABLE `simple_custom_data`
  ADD PRIMARY KEY (`object_type`,`object_id`,`customer_id`),
  ADD KEY `object_id` (`object_id`),
  ADD KEY `object_type` (`object_type`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Индексы таблицы `sms_log`
--
ALTER TABLE `sms_log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `socnetauth2_customer2account`
--
ALTER TABLE `socnetauth2_customer2account`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `socnetauth2_precode`
--
ALTER TABLE `socnetauth2_precode`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `socnetauth2_records`
--
ALTER TABLE `socnetauth2_records`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `special_attribute`
--
ALTER TABLE `special_attribute`
  ADD PRIMARY KEY (`special_attribute_id`);

--
-- Индексы таблицы `special_attribute_group`
--
ALTER TABLE `special_attribute_group`
  ADD PRIMARY KEY (`special_attribute_group_id`);

--
-- Индексы таблицы `sphinx_suggestions`
--
ALTER TABLE `sphinx_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `keyword` (`keyword`);

--
-- Индексы таблицы `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `stocks_dynamics`
--
ALTER TABLE `stocks_dynamics`
  ADD PRIMARY KEY (`stock_dynamics_id`),
  ADD UNIQUE KEY `date_added` (`date_added`,`warehouse_identifier`);

--
-- Индексы таблицы `stock_status`
--
ALTER TABLE `stock_status`
  ADD PRIMARY KEY (`stock_status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- Индексы таблицы `subscribe`
--
ALTER TABLE `subscribe`
  ADD PRIMARY KEY (`subscribe_id`);

--
-- Индексы таблицы `subscribe_auth_description`
--
ALTER TABLE `subscribe_auth_description`
  ADD PRIMARY KEY (`subscribe_auth_id`);

--
-- Индексы таблицы `subscribe_email_description`
--
ALTER TABLE `subscribe_email_description`
  ADD PRIMARY KEY (`subscribe_desc_id`);

--
-- Индексы таблицы `superstat_viewed`
--
ALTER TABLE `superstat_viewed`
  ADD UNIQUE KEY `entity_type_2` (`entity_type`,`entity_id`,`store_id`,`date`),
  ADD KEY `entity_type` (`entity_type`),
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `supplier_name` (`supplier_name`),
  ADD KEY `1c_uuid` (`1c_uuid`),
  ADD KEY `amzn_good` (`amzn_good`),
  ADD KEY `amzn_bad` (`amzn_bad`),
  ADD KEY `supplier_type` (`supplier_type`);

--
-- Индексы таблицы `tax_class`
--
ALTER TABLE `tax_class`
  ADD PRIMARY KEY (`tax_class_id`);

--
-- Индексы таблицы `tax_rate`
--
ALTER TABLE `tax_rate`
  ADD PRIMARY KEY (`tax_rate_id`),
  ADD KEY `geo_zone_id` (`geo_zone_id`);

--
-- Индексы таблицы `tax_rate_to_customer_group`
--
ALTER TABLE `tax_rate_to_customer_group`
  ADD PRIMARY KEY (`tax_rate_id`,`customer_group_id`),
  ADD KEY `customer_group_id` (`customer_group_id`);

--
-- Индексы таблицы `tax_rule`
--
ALTER TABLE `tax_rule`
  ADD PRIMARY KEY (`tax_rule_id`),
  ADD KEY `tax_class_id` (`tax_class_id`),
  ADD KEY `tax_rate_id` (`tax_rate_id`);

--
-- Индексы таблицы `telegram_chats`
--
ALTER TABLE `telegram_chats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `telegram_messages`
--
ALTER TABLE `telegram_messages`
  ADD PRIMARY KEY (`update_id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `telegram_users`
--
ALTER TABLE `telegram_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Индексы таблицы `telegram_users_chats`
--
ALTER TABLE `telegram_users_chats`
  ADD PRIMARY KEY (`user_id`,`chat_id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- Индексы таблицы `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `user_group_id` (`user_group_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `priority` (`priority`),
  ADD KEY `from_user` (`from_user_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `date_max` (`date_max`),
  ADD KEY `sort_order` (`sort_order`),
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `entity_type` (`entity_type`),
  ADD KEY `date_at` (`date_at`),
  ADD KEY `is_recall` (`is_recall`);

--
-- Индексы таблицы `ticket_sort`
--
ALTER TABLE `ticket_sort`
  ADD UNIQUE KEY `ticket_id` (`ticket_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Индексы таблицы `tracker`
--
ALTER TABLE `tracker`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `translate_stats`
--
ALTER TABLE `translate_stats`
  ADD KEY `time` (`time`);

--
-- Индексы таблицы `trigger_history`
--
ALTER TABLE `trigger_history`
  ADD PRIMARY KEY (`trigger_history_id`),
  ADD KEY `trigger_history_id` (`trigger_history_id`,`order_id`,`customer_id`),
  ADD KEY `trigger_history_id_2` (`trigger_history_id`,`order_id`,`actiontemplate_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `type` (`type`);

--
-- Индексы таблицы `url_alias`
--
ALTER TABLE `url_alias`
  ADD PRIMARY KEY (`url_alias_id`),
  ADD UNIQUE KEY `query_language` (`query`,`language_id`) USING BTREE,
  ADD KEY `keyword` (`keyword`) USING BTREE,
  ADD KEY `language_id` (`language_id`),
  ADD KEY `query` (`query`) USING BTREE;

--
-- Индексы таблицы `url_alias_cached`
--
ALTER TABLE `url_alias_cached`
  ADD KEY `store_id` (`store_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `route` (`route`),
  ADD KEY `args` (`args`),
  ADD KEY `checksum` (`checksum`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_group_id` (`user_group_id`),
  ADD KEY `bitrix_id` (`bitrix_id`),
  ADD KEY `unlock_orders` (`unlock_orders`),
  ADD KEY `ip` (`ip`),
  ADD KEY `status` (`status`),
  ADD KEY `email` (`email`),
  ADD KEY `username` (`username`),
  ADD KEY `ticket` (`ticket`),
  ADD KEY `ip_2` (`ip`,`status`),
  ADD KEY `user_id` (`user_id`,`status`),
  ADD KEY `user_id_2` (`user_id`,`status`);

--
-- Индексы таблицы `user_content`
--
ALTER TABLE `user_content`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `entity_type` (`entity_type`),
  ADD KEY `action` (`action`),
  ADD KEY `user_id_2` (`user_id`,`date`,`action`);

--
-- Индексы таблицы `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`user_group_id`),
  ADD KEY `alert_namespace` (`alert_namespace`),
  ADD KEY `ticket` (`ticket`),
  ADD KEY `sip_queue` (`sip_queue`),
  ADD KEY `bitrix_id` (`bitrix_id`);

--
-- Индексы таблицы `user_group_to_store`
--
ALTER TABLE `user_group_to_store`
  ADD KEY `user_group_id` (`user_group_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Индексы таблицы `user_worktime`
--
ALTER TABLE `user_worktime`
  ADD UNIQUE KEY `user_id` (`user_id`,`date`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Индексы таблицы `vk_export_album`
--
ALTER TABLE `vk_export_album`
  ADD PRIMARY KEY (`category_id`,`vk_album_id`,`mode`),
  ADD KEY `vk_album_id` (`vk_album_id`);

--
-- Индексы таблицы `vk_export_photo`
--
ALTER TABLE `vk_export_photo`
  ADD PRIMARY KEY (`product_id`,`vk_photo_id`),
  ADD KEY `vk_photo_id` (`vk_photo_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`voucher_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `voucher_theme_id` (`voucher_theme_id`);

--
-- Индексы таблицы `voucher_history`
--
ALTER TABLE `voucher_history`
  ADD PRIMARY KEY (`voucher_history_id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `voucher_theme`
--
ALTER TABLE `voucher_theme`
  ADD PRIMARY KEY (`voucher_theme_id`);

--
-- Индексы таблицы `voucher_theme_description`
--
ALTER TABLE `voucher_theme_description`
  ADD PRIMARY KEY (`voucher_theme_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Индексы таблицы `wc_continents`
--
ALTER TABLE `wc_continents`
  ADD PRIMARY KEY (`code`);

--
-- Индексы таблицы `wc_countries`
--
ALTER TABLE `wc_countries`
  ADD PRIMARY KEY (`code`);

--
-- Индексы таблицы `weight_class`
--
ALTER TABLE `weight_class`
  ADD PRIMARY KEY (`weight_class_id`),
  ADD KEY `amazon_key` (`amazon_key`);

--
-- Индексы таблицы `weight_class_description`
--
ALTER TABLE `weight_class_description`
  ADD PRIMARY KEY (`weight_class_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `weight_class_id` (`weight_class_id`);

--
-- Индексы таблицы `yandex_feeds`
--
ALTER TABLE `yandex_feeds`
  ADD KEY `store_id` (`store_id`),
  ADD KEY `entity_type` (`entity_type`);

--
-- Индексы таблицы `yandex_queue`
--
ALTER TABLE `yandex_queue`
  ADD PRIMARY KEY (`order_id`);

--
-- Индексы таблицы `yandex_stock_queue`
--
ALTER TABLE `yandex_stock_queue`
  ADD UNIQUE KEY `yam_product_id` (`yam_product_id`);

--
-- Индексы таблицы `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`zone_id`),
  ADD KEY `country_id` (`country_id`);

--
-- Индексы таблицы `zone_to_geo_zone`
--
ALTER TABLE `zone_to_geo_zone`
  ADD PRIMARY KEY (`zone_to_geo_zone_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `geo_zone_id` (`geo_zone_id`);

--
-- Индексы таблицы `_temp_discount`
--
ALTER TABLE `_temp_discount`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `actions`
--
ALTER TABLE `actions`
  MODIFY `actions_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `actiontemplate`
--
ALTER TABLE `actiontemplate`
  MODIFY `actiontemplate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `adminlog`
--
ALTER TABLE `adminlog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `advanced_coupon`
--
ALTER TABLE `advanced_coupon`
  MODIFY `advanced_coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `advanced_coupon_history`
--
ALTER TABLE `advanced_coupon_history`
  MODIFY `advanced_coupon_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `affiliate`
--
ALTER TABLE `affiliate`
  MODIFY `affiliate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `affiliate_statistics`
--
ALTER TABLE `affiliate_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `affiliate_transaction`
--
ALTER TABLE `affiliate_transaction`
  MODIFY `affiliate_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `alertlog`
--
ALTER TABLE `alertlog`
  MODIFY `alertlog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `alsoviewed`
--
ALTER TABLE `alsoviewed`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `amazon_orders`
--
ALTER TABLE `amazon_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `amazon_orders_products`
--
ALTER TABLE `amazon_orders_products`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `attribute`
--
ALTER TABLE `attribute`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `attribute_group`
--
ALTER TABLE `attribute_group`
  MODIFY `attribute_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `attribute_value_image`
--
ALTER TABLE `attribute_value_image`
  MODIFY `attribute_value_image` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `banner`
--
ALTER TABLE `banner`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `banner_image`
--
ALTER TABLE `banner_image`
  MODIFY `banner_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `callback`
--
ALTER TABLE `callback`
  MODIFY `call_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `category_menu_content`
--
ALTER TABLE `category_menu_content`
  MODIFY `category_menu_content_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `category_psm_template`
--
ALTER TABLE `category_psm_template`
  MODIFY `category_psm_template_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `category_review`
--
ALTER TABLE `category_review`
  MODIFY `categoryreview_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `cdek_cities`
--
ALTER TABLE `cdek_cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `cdek_deliverypoints`
--
ALTER TABLE `cdek_deliverypoints`
  MODIFY `deliverypoint_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `cdek_zones`
--
ALTER TABLE `cdek_zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `collection`
--
ALTER TABLE `collection`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `competitors`
--
ALTER TABLE `competitors`
  MODIFY `competitor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `competitor_price`
--
ALTER TABLE `competitor_price`
  MODIFY `competitor_price_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `counters`
--
ALTER TABLE `counters`
  MODIFY `counter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `country`
--
ALTER TABLE `country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `countrybrand`
--
ALTER TABLE `countrybrand`
  MODIFY `countrybrand_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `coupon`
--
ALTER TABLE `coupon`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `coupon_history`
--
ALTER TABLE `coupon_history`
  MODIFY `coupon_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `coupon_product`
--
ALTER TABLE `coupon_product`
  MODIFY `coupon_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `csvprice_pro`
--
ALTER TABLE `csvprice_pro`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `csvprice_pro_crontab`
--
ALTER TABLE `csvprice_pro_crontab`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `csvprice_pro_profiles`
--
ALTER TABLE `csvprice_pro_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_ban_ip`
--
ALTER TABLE `customer_ban_ip`
  MODIFY `customer_ban_ip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_calls`
--
ALTER TABLE `customer_calls`
  MODIFY `customer_call_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_email_campaigns`
--
ALTER TABLE `customer_email_campaigns`
  MODIFY `customer_email_campaigns_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_group`
--
ALTER TABLE `customer_group`
  MODIFY `customer_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_history`
--
ALTER TABLE `customer_history`
  MODIFY `customer_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_ip`
--
ALTER TABLE `customer_ip`
  MODIFY `customer_ip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_reward`
--
ALTER TABLE `customer_reward`
  MODIFY `customer_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_reward_queue`
--
ALTER TABLE `customer_reward_queue`
  MODIFY `customer_reward_queue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_search_history`
--
ALTER TABLE `customer_search_history`
  MODIFY `customer_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `customer_transaction`
--
ALTER TABLE `customer_transaction`
  MODIFY `customer_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `custom_field`
--
ALTER TABLE `custom_field`
  MODIFY `custom_field_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `custom_field_value`
--
ALTER TABLE `custom_field_value`
  MODIFY `custom_field_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `custom_url_404`
--
ALTER TABLE `custom_url_404`
  MODIFY `custom_url_404_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `download`
--
ALTER TABLE `download`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `emailmarketing_logs`
--
ALTER TABLE `emailmarketing_logs`
  MODIFY `emailmarketing_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `emailtemplate`
--
ALTER TABLE `emailtemplate`
  MODIFY `emailtemplate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `emailtemplate_config`
--
ALTER TABLE `emailtemplate_config`
  MODIFY `emailtemplate_config_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `emailtemplate_logs`
--
ALTER TABLE `emailtemplate_logs`
  MODIFY `emailtemplate_log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `emailtemplate_shortcode`
--
ALTER TABLE `emailtemplate_shortcode`
  MODIFY `emailtemplate_shortcode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `entity_reward`
--
ALTER TABLE `entity_reward`
  MODIFY `entity_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `extension`
--
ALTER TABLE `extension`
  MODIFY `extension_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `facategory`
--
ALTER TABLE `facategory`
  MODIFY `facategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `faq_category`
--
ALTER TABLE `faq_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `faq_question`
--
ALTER TABLE `faq_question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `filter`
--
ALTER TABLE `filter`
  MODIFY `filter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `filter_group`
--
ALTER TABLE `filter_group`
  MODIFY `filter_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `geo`
--
ALTER TABLE `geo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `geo_zone`
--
ALTER TABLE `geo_zone`
  MODIFY `geo_zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `google_base_category`
--
ALTER TABLE `google_base_category`
  MODIFY `google_base_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `hj_any_feed_feeds`
--
ALTER TABLE `hj_any_feed_feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `imagemaps`
--
ALTER TABLE `imagemaps`
  MODIFY `imagemap_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `information`
--
ALTER TABLE `information`
  MODIFY `information_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `information_attribute`
--
ALTER TABLE `information_attribute`
  MODIFY `information_attribute_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `interplusplus`
--
ALTER TABLE `interplusplus`
  MODIFY `inter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `justin_cities`
--
ALTER TABLE `justin_cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `justin_city_regions`
--
ALTER TABLE `justin_city_regions`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `justin_streets`
--
ALTER TABLE `justin_streets`
  MODIFY `street_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `justin_warehouses`
--
ALTER TABLE `justin_warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `justin_zones`
--
ALTER TABLE `justin_zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `justin_zone_regions`
--
ALTER TABLE `justin_zone_regions`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `keyworder`
--
ALTER TABLE `keyworder`
  MODIFY `keyworder_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `landingpage`
--
ALTER TABLE `landingpage`
  MODIFY `landingpage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `language`
--
ALTER TABLE `language`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `layout`
--
ALTER TABLE `layout`
  MODIFY `layout_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `layout_route`
--
ALTER TABLE `layout_route`
  MODIFY `layout_route_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `legalperson`
--
ALTER TABLE `legalperson`
  MODIFY `legalperson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `length_class`
--
ALTER TABLE `length_class`
  MODIFY `length_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `length_class_description`
--
ALTER TABLE `length_class_description`
  MODIFY `length_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `manufacturer_page_content`
--
ALTER TABLE `manufacturer_page_content`
  MODIFY `manufacturer_page_content_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `multi_pay_payment`
--
ALTER TABLE `multi_pay_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `nauthor`
--
ALTER TABLE `nauthor`
  MODIFY `nauthor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ncategory`
--
ALTER TABLE `ncategory`
  MODIFY `ncategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ncomments`
--
ALTER TABLE `ncomments`
  MODIFY `ncomment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `news_gallery`
--
ALTER TABLE `news_gallery`
  MODIFY `news_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `news_video`
--
ALTER TABLE `news_video`
  MODIFY `news_video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `novaposhta_cities`
--
ALTER TABLE `novaposhta_cities`
  MODIFY `CityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `novaposhta_cities_ww`
--
ALTER TABLE `novaposhta_cities_ww`
  MODIFY `CityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `novaposhta_streets`
--
ALTER TABLE `novaposhta_streets`
  MODIFY `StreetID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `novaposhta_warehouses`
--
ALTER TABLE `novaposhta_warehouses`
  MODIFY `WarehouseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `novaposhta_zones`
--
ALTER TABLE `novaposhta_zones`
  MODIFY `ZoneID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ocfilter_option`
--
ALTER TABLE `ocfilter_option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ocfilter_option_value`
--
ALTER TABLE `ocfilter_option_value`
  MODIFY `value_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ocfilter_option_value_to_product`
--
ALTER TABLE `ocfilter_option_value_to_product`
  MODIFY `ocfilter_option_value_to_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ocfilter_page`
--
ALTER TABLE `ocfilter_page`
  MODIFY `ocfilter_page_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `oc_feedback`
--
ALTER TABLE `oc_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `oc_sms_log`
--
ALTER TABLE `oc_sms_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `oc_yandex_category`
--
ALTER TABLE `oc_yandex_category`
  MODIFY `yandex_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `option`
--
ALTER TABLE `option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `option_value`
--
ALTER TABLE `option_value`
  MODIFY `option_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Номер заказа';

--
-- AUTO_INCREMENT для таблицы `order_download`
--
ALTER TABLE `order_download`
  MODIFY `order_download_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_history`
--
ALTER TABLE `order_history`
  MODIFY `order_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_invoice_history`
--
ALTER TABLE `order_invoice_history`
  MODIFY `order_invoice_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_option`
--
ALTER TABLE `order_option`
  MODIFY `order_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_product`
--
ALTER TABLE `order_product`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_product_bought`
--
ALTER TABLE `order_product_bought`
  MODIFY `bought_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_product_history`
--
ALTER TABLE `order_product_history`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_product_nogood`
--
ALTER TABLE `order_product_nogood`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_product_reserves`
--
ALTER TABLE `order_product_reserves`
  MODIFY `order_reserve_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_product_supply`
--
ALTER TABLE `order_product_supply`
  MODIFY `order_product_supply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_product_tracker`
--
ALTER TABLE `order_product_tracker`
  MODIFY `order_product_tracker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_recurring`
--
ALTER TABLE `order_recurring`
  MODIFY `order_recurring_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_recurring_transaction`
--
ALTER TABLE `order_recurring_transaction`
  MODIFY `order_recurring_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_reject_reason`
--
ALTER TABLE `order_reject_reason`
  MODIFY `reject_reason_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_save_history`
--
ALTER TABLE `order_save_history`
  MODIFY `order_save_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_set`
--
ALTER TABLE `order_set`
  MODIFY `order_set_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_sms_history`
--
ALTER TABLE `order_sms_history`
  MODIFY `order_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_total`
--
ALTER TABLE `order_total`
  MODIFY `order_total_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_tracker`
--
ALTER TABLE `order_tracker`
  MODIFY `order_tracker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_tracker_sms`
--
ALTER TABLE `order_tracker_sms`
  MODIFY `tracker_sms_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_ttns`
--
ALTER TABLE `order_ttns`
  MODIFY `order_ttn_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_voucher`
--
ALTER TABLE `order_voucher`
  MODIFY `order_voucher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `parser_queue`
--
ALTER TABLE `parser_queue`
  MODIFY `parser_queue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pavoslidergroups`
--
ALTER TABLE `pavoslidergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pavosliderlayers`
--
ALTER TABLE `pavosliderlayers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `paypal_iframe_order`
--
ALTER TABLE `paypal_iframe_order`
  MODIFY `paypal_iframe_order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `paypal_iframe_order_transaction`
--
ALTER TABLE `paypal_iframe_order_transaction`
  MODIFY `paypal_iframe_order_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `paypal_order`
--
ALTER TABLE `paypal_order`
  MODIFY `paypal_order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `paypal_order_transaction`
--
ALTER TABLE `paypal_order_transaction`
  MODIFY `paypal_order_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_additional_offer`
--
ALTER TABLE `product_additional_offer`
  MODIFY `product_additional_offer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_amzn_offers`
--
ALTER TABLE `product_amzn_offers`
  MODIFY `amazon_offer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_barbara_tab`
--
ALTER TABLE `product_barbara_tab`
  MODIFY `product_additional_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_discount`
--
ALTER TABLE `product_discount`
  MODIFY `product_discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_image`
--
ALTER TABLE `product_image`
  MODIFY `product_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_option`
--
ALTER TABLE `product_option`
  MODIFY `product_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_option_value`
--
ALTER TABLE `product_option_value`
  MODIFY `product_option_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_product_option`
--
ALTER TABLE `product_product_option`
  MODIFY `product_product_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_product_option_value`
--
ALTER TABLE `product_product_option_value`
  MODIFY `product_product_option_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_reward`
--
ALTER TABLE `product_reward`
  MODIFY `product_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_sources`
--
ALTER TABLE `product_sources`
  MODIFY `product_source_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_special`
--
ALTER TABLE `product_special`
  MODIFY `product_special_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_special_attribute`
--
ALTER TABLE `product_special_attribute`
  MODIFY `product_special_attribute_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_sticker`
--
ALTER TABLE `product_sticker`
  MODIFY `product_sticker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_tab`
--
ALTER TABLE `product_tab`
  MODIFY `tab_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_tmp`
--
ALTER TABLE `product_tmp`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_video`
--
ALTER TABLE `product_video`
  MODIFY `product_video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `queue_mail`
--
ALTER TABLE `queue_mail`
  MODIFY `queue_mail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `queue_push`
--
ALTER TABLE `queue_push`
  MODIFY `queue_push_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `queue_sms`
--
ALTER TABLE `queue_sms`
  MODIFY `queue_sms_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `redirect`
--
ALTER TABLE `redirect`
  MODIFY `redirect_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `referrer_patterns`
--
ALTER TABLE `referrer_patterns`
  MODIFY `pattern_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `return`
--
ALTER TABLE `return`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `return_action`
--
ALTER TABLE `return_action`
  MODIFY `return_action_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `return_history`
--
ALTER TABLE `return_history`
  MODIFY `return_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `return_reason`
--
ALTER TABLE `return_reason`
  MODIFY `return_reason_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `return_status`
--
ALTER TABLE `return_status`
  MODIFY `return_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `review_name`
--
ALTER TABLE `review_name`
  MODIFY `review_name_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `review_template`
--
ALTER TABLE `review_template`
  MODIFY `review_template_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `segments`
--
ALTER TABLE `segments`
  MODIFY `segment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `segments_dynamics`
--
ALTER TABLE `segments_dynamics`
  MODIFY `segment_dynamics_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `seocities`
--
ALTER TABLE `seocities`
  MODIFY `seocity_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `set`
--
ALTER TABLE `set`
  MODIFY `set_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `setting`
--
ALTER TABLE `setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `shoputils_cumulative_discounts`
--
ALTER TABLE `shoputils_cumulative_discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `shop_rating`
--
ALTER TABLE `shop_rating`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `shop_rating_answers`
--
ALTER TABLE `shop_rating_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `shop_rating_custom_types`
--
ALTER TABLE `shop_rating_custom_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `shop_rating_custom_values`
--
ALTER TABLE `shop_rating_custom_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `short_url_alias`
--
ALTER TABLE `short_url_alias`
  MODIFY `url_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `simple_cart`
--
ALTER TABLE `simple_cart`
  MODIFY `simple_cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sms_log`
--
ALTER TABLE `sms_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `socnetauth2_customer2account`
--
ALTER TABLE `socnetauth2_customer2account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `socnetauth2_precode`
--
ALTER TABLE `socnetauth2_precode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `socnetauth2_records`
--
ALTER TABLE `socnetauth2_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `special_attribute`
--
ALTER TABLE `special_attribute`
  MODIFY `special_attribute_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sphinx_suggestions`
--
ALTER TABLE `sphinx_suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `stocks_dynamics`
--
ALTER TABLE `stocks_dynamics`
  MODIFY `stock_dynamics_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `stock_status`
--
ALTER TABLE `stock_status`
  MODIFY `stock_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscribe`
--
ALTER TABLE `subscribe`
  MODIFY `subscribe_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscribe_auth_description`
--
ALTER TABLE `subscribe_auth_description`
  MODIFY `subscribe_auth_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscribe_email_description`
--
ALTER TABLE `subscribe_email_description`
  MODIFY `subscribe_desc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tax_class`
--
ALTER TABLE `tax_class`
  MODIFY `tax_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tax_rate`
--
ALTER TABLE `tax_rate`
  MODIFY `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tax_rule`
--
ALTER TABLE `tax_rule`
  MODIFY `tax_rule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tracker`
--
ALTER TABLE `tracker`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `trigger_history`
--
ALTER TABLE `trigger_history`
  MODIFY `trigger_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `url_alias`
--
ALTER TABLE `url_alias`
  MODIFY `url_alias_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_group`
--
ALTER TABLE `user_group`
  MODIFY `user_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `voucher`
--
ALTER TABLE `voucher`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `voucher_history`
--
ALTER TABLE `voucher_history`
  MODIFY `voucher_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `voucher_theme`
--
ALTER TABLE `voucher_theme`
  MODIFY `voucher_theme_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `weight_class`
--
ALTER TABLE `weight_class`
  MODIFY `weight_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `weight_class_description`
--
ALTER TABLE `weight_class_description`
  MODIFY `weight_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zone`
--
ALTER TABLE `zone`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `zone_to_geo_zone`
--
ALTER TABLE `zone_to_geo_zone`
  MODIFY `zone_to_geo_zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `_temp_discount`
--
ALTER TABLE `_temp_discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `telegram_users_chats`
--
ALTER TABLE `telegram_users_chats`
  ADD CONSTRAINT `telegram_users_chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `telegram_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `telegram_users_chats_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `telegram_chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
