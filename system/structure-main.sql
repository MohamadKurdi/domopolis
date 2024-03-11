-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2024 at 05:47 PM
-- Server version: 10.11.7-MariaDB-1:10.11.7+maria~ubu2204
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dp_domopolis`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE `actions` (
  `actions_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_to_cat` varchar(500) NOT NULL,
  `image_size` int(11) NOT NULL DEFAULT 0,
  `date_start` int(11) NOT NULL DEFAULT 0,
  `date_end` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `fancybox` int(11) NOT NULL DEFAULT 0,
  `product_related` text DEFAULT NULL,
  `category_related_id` int(11) NOT NULL,
  `category_related_no_intersections` tinyint(1) NOT NULL,
  `category_related_limit_products` int(11) NOT NULL,
  `ao_group` varchar(100) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `deletenotinstock` tinyint(1) NOT NULL DEFAULT 0,
  `only_in_stock` int(11) NOT NULL DEFAULT 0,
  `display_all_active` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `actions_description`
--

CREATE TABLE `actions_description` (
  `actions_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '',
  `meta_description` varchar(255) NOT NULL DEFAULT '',
  `h1` varchar(255) NOT NULL DEFAULT '',
  `caption` varchar(255) NOT NULL DEFAULT '',
  `anonnce` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `image_overload` varchar(255) NOT NULL,
  `image_to_cat_overload` varchar(255) NOT NULL,
  `label` varchar(64) NOT NULL,
  `label_background` varchar(32) NOT NULL,
  `label_color` varchar(32) NOT NULL,
  `label_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `actions_to_category`
--

CREATE TABLE `actions_to_category` (
  `actions_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actions_to_category_in`
--

CREATE TABLE `actions_to_category_in` (
  `actions_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actions_to_layout`
--

CREATE TABLE `actions_to_layout` (
  `actions_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `actions_to_product`
--

CREATE TABLE `actions_to_product` (
  `actions_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actions_to_store`
--

CREATE TABLE `actions_to_store` (
  `actions_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `actiontemplate`
--

CREATE TABLE `actiontemplate` (
  `actiontemplate_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `use_for_manual` tinyint(1) DEFAULT 0,
  `use_for_forgotten` tinyint(1) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0,
  `data_function` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actiontemplate_description`
--

CREATE TABLE `actiontemplate_description` (
  `actiontemplate_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` longtext NOT NULL,
  `file_template` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address_simple_fields`
--

CREATE TABLE `address_simple_fields` (
  `address_id` int(11) NOT NULL,
  `metadata` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adminlog`
--

CREATE TABLE `adminlog` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `action` varchar(50) NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  `url` varchar(200) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advanced_coupon`
--

CREATE TABLE `advanced_coupon` (
  `advanced_coupon_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL,
  `options` text NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `status` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advanced_coupon_history`
--

CREATE TABLE `advanced_coupon_history` (
  `advanced_coupon_history_id` int(11) NOT NULL,
  `advanced_coupon_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_statistics`
--

CREATE TABLE `affiliate_statistics` (
  `id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `count_transitions` int(11) NOT NULL DEFAULT 0,
  `affiliate_ip_name` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_transaction`
--

CREATE TABLE `affiliate_transaction` (
  `affiliate_transaction_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL,
  `album_type` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `last_modified` datetime NOT NULL,
  `album_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alertlog`
--

CREATE TABLE `alertlog` (
  `alertlog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alert_type` varchar(30) NOT NULL,
  `alert_text` varchar(500) NOT NULL,
  `entity_type` varchar(20) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alsoviewed`
--

CREATE TABLE `alsoviewed` (
  `id` bigint(20) NOT NULL,
  `low` int(11) DEFAULT 0,
  `high` int(11) DEFAULT 0,
  `number` int(11) DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amazon_orders`
--

CREATE TABLE `amazon_orders` (
  `order_id` int(11) NOT NULL,
  `amazon_id` varchar(255) NOT NULL,
  `date_added` date NOT NULL,
  `total` decimal(15,4) NOT NULL,
  `grand_total` decimal(15,4) NOT NULL,
  `gift_card` decimal(15,4) NOT NULL,
  `cancelled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amazon_orders_blobs`
--

CREATE TABLE `amazon_orders_blobs` (
  `amazon_id` varchar(30) NOT NULL,
  `amazon_blob` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amazon_orders_products`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amazon_zipcodes`
--

CREATE TABLE `amazon_zipcodes` (
  `zipcode_id` int(11) NOT NULL,
  `zipcode_area` varchar(255) NOT NULL,
  `zipcode_area2` varchar(255) NOT NULL,
  `zipcode` varchar(32) NOT NULL,
  `error_count` int(11) NOT NULL DEFAULT 0,
  `request_count` int(11) NOT NULL DEFAULT 0,
  `last_used` datetime DEFAULT NULL,
  `added` datetime DEFAULT NULL,
  `dropped` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amzn_add_queue`
--

CREATE TABLE `amzn_add_queue` (
  `asin` varchar(32) NOT NULL,
  `date_added` datetime NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_logic` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amzn_add_variants_queue`
--

CREATE TABLE `amzn_add_variants_queue` (
  `product_id` int(11) NOT NULL,
  `asin` varchar(16) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amzn_product_queue`
--

CREATE TABLE `amzn_product_queue` (
  `asin` varchar(32) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `apri`
--

CREATE TABLE `apri` (
  `order_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `apri_unsubscribe`
--

CREATE TABLE `apri_unsubscribe` (
  `md5_email` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute`
--

CREATE TABLE `attribute` (
  `attribute_id` int(11) NOT NULL,
  `attribute_group_id` int(11) NOT NULL,
  `dimension_type` enum('length','width','height','dimensions','weight','all') DEFAULT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attributes_category`
--

CREATE TABLE `attributes_category` (
  `attribute_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attributes_similar_category`
--

CREATE TABLE `attributes_similar_category` (
  `attribute_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_description`
--

CREATE TABLE `attribute_description` (
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_group`
--

CREATE TABLE `attribute_group` (
  `attribute_group_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_group_description`
--

CREATE TABLE `attribute_group_description` (
  `attribute_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_group_tooltip`
--

CREATE TABLE `attribute_group_tooltip` (
  `attribute_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tooltip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_tooltip`
--

CREATE TABLE `attribute_tooltip` (
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tooltip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_value_image`
--

CREATE TABLE `attribute_value_image` (
  `attribute_value_image` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_value` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `information_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banner_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `class` varchar(256) NOT NULL,
  `class_sm` varchar(256) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_image`
--

CREATE TABLE `banner_image` (
  `banner_image_id` int(11) NOT NULL,
  `banner_id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_sm` varchar(255) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width_sm` int(11) NOT NULL,
  `height_sm` int(11) NOT NULL,
  `class` varchar(255) NOT NULL,
  `class_sm` varchar(255) NOT NULL,
  `block` int(11) NOT NULL,
  `block_sm` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_image_description`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `callback`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

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
  `amazon_synced` tinyint(1) NOT NULL DEFAULT 0,
  `amazon_overprice_rules` longtext NOT NULL,
  `yandex_category_name` varchar(1024) NOT NULL,
  `hotline_category_name` varchar(1024) NOT NULL,
  `hotline_enable` tinyint(1) NOT NULL DEFAULT 0,
  `final` tinyint(1) NOT NULL DEFAULT 0,
  `homepage` tinyint(1) NOT NULL,
  `popular` tinyint(1) NOT NULL,
  `special` tinyint(1) NOT NULL DEFAULT 0,
  `viewed` int(11) DEFAULT 0,
  `bought_for_month` int(11) DEFAULT NULL,
  `overload_formula_data` longtext NOT NULL,
  `overload_max_wc_multiplier` decimal(15,2) DEFAULT 0.00,
  `overload_max_multiplier` decimal(15,2) DEFAULT 0.00,
  `overload_ignore_volumetric_weight` tinyint(1) DEFAULT 0,
  `need_reprice` tinyint(1) NOT NULL DEFAULT 0,
  `need_special_reprice` tinyint(1) NOT NULL DEFAULT 0,
  `special_reprice_plus` tinyint(1) NOT NULL DEFAULT 0,
  `special_reprice_minus` tinyint(1) NOT NULL DEFAULT 0,
  `last_reprice` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_amazon_bestseller_tree`
--

CREATE TABLE `category_amazon_bestseller_tree` (
  `category_id` varchar(255) NOT NULL,
  `parent_id` varchar(255) NOT NULL,
  `final_category` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(512) DEFAULT NULL,
  `name_native` varchar(512) NOT NULL,
  `full_name` varchar(1024) DEFAULT NULL,
  `full_name_native` varchar(1024) NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_amazon_tree`
--

CREATE TABLE `category_amazon_tree` (
  `category_id` varchar(255) NOT NULL,
  `parent_id` varchar(255) NOT NULL,
  `final_category` tinyint(1) NOT NULL,
  `name` varchar(512) NOT NULL,
  `full_name` varchar(1024) NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_description`
--

CREATE TABLE `category_description` (
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tagline` varchar(2048) NOT NULL,
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
  `title_image` text DEFAULT NULL,
  `google_tree` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_filter`
--

CREATE TABLE `category_filter` (
  `category_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_hotline_tree`
--

CREATE TABLE `category_hotline_tree` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `final_category` tinyint(1) NOT NULL,
  `name` varchar(512) NOT NULL,
  `full_name` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_menu_content`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_overprice_rules`
--

CREATE TABLE `category_overprice_rules` (
  `rule_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `multiplier` decimal(15,2) NOT NULL,
  `default_multiplier` decimal(15,2) NOT NULL,
  `multiplier_old` decimal(15,2) NOT NULL,
  `default_multiplier_old` decimal(15,2) NOT NULL,
  `discount` tinyint(1) NOT NULL DEFAULT 0,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_path`
--

CREATE TABLE `category_path` (
  `category_id` int(11) NOT NULL,
  `path_id` int(11) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_product_count`
--

CREATE TABLE `category_product_count` (
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `product_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_psm_template`
--

CREATE TABLE `category_psm_template` (
  `category_psm_template_id` int(11) NOT NULL,
  `category_entity` varchar(64) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `l_code` varchar(5) DEFAULT NULL,
  `template` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_related`
--

CREATE TABLE `category_related` (
  `category_id` int(11) NOT NULL,
  `related_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_review`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_search_words`
--

CREATE TABLE `category_search_words` (
  `category_search_word_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_word_type` varchar(64) NOT NULL,
  `category_search_word` varchar(256) NOT NULL,
  `category_word_category` varchar(512) NOT NULL,
  `category_word_category_id` varchar(64) NOT NULL,
  `category_search_sort` varchar(32) NOT NULL,
  `category_search_min_price` decimal(15,2) NOT NULL,
  `category_search_max_price` double(15,2) NOT NULL,
  `category_search_min_offers` int(11) NOT NULL,
  `category_search_min_rating` decimal(15,1) NOT NULL DEFAULT 0.0,
  `category_search_min_reviews` int(11) NOT NULL DEFAULT 0,
  `category_search_has_prime` tinyint(1) NOT NULL DEFAULT 0,
  `category_search_exact_words` varchar(512) NOT NULL DEFAULT '0',
  `category_search_auto` tinyint(1) NOT NULL DEFAULT 0,
  `category_word_last_search` datetime NOT NULL,
  `category_word_total_products` int(11) NOT NULL,
  `category_word_total_pages` int(11) NOT NULL,
  `category_word_pages_parsed` int(11) NOT NULL,
  `category_word_product_added` int(11) NOT NULL,
  `category_word_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_to_actions`
--

CREATE TABLE `category_to_actions` (
  `category_id` int(11) NOT NULL,
  `actions_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_to_layout`
--

CREATE TABLE `category_to_layout` (
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_to_store`
--

CREATE TABLE `category_to_store` (
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_yam_tree`
--

CREATE TABLE `category_yam_tree` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `final_category` tinyint(1) NOT NULL,
  `name` varchar(512) NOT NULL,
  `full_name` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_cities`
--

CREATE TABLE `cdek_cities` (
  `city_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `kladr_code` varchar(255) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `country_id` int(11) NOT NULL,
  `country` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `region_code` int(11) NOT NULL,
  `fias_guid` varchar(64) NOT NULL,
  `sub_region` varchar(255) NOT NULL,
  `postal_codes` varchar(255) NOT NULL,
  `longitude` varchar(32) NOT NULL,
  `latitude` varchar(32) NOT NULL,
  `time_zone` varchar(255) NOT NULL,
  `WarehouseCount` int(11) NOT NULL,
  `dadata_BELTWAY_HIT` varchar(10) DEFAULT NULL,
  `dadata_BELTWAY_DISTANCE` int(11) NOT NULL DEFAULT 0,
  `deliveryPeriodMin` int(11) NOT NULL,
  `deliveryPeriodMax` int(11) NOT NULL,
  `min_WD` decimal(15,2) NOT NULL,
  `min_WW` decimal(15,2) NOT NULL,
  `parsed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_city`
--

CREATE TABLE `cdek_city` (
  `id` varchar(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `cityName` varchar(64) NOT NULL,
  `regionName` varchar(64) NOT NULL,
  `center` tinyint(1) NOT NULL DEFAULT 0,
  `cache_limit` float(5,4) NOT NULL,
  `deliveryPeriodMin` int(11) NOT NULL,
  `deliveryPeriodMax` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_deliverypoints`
--

CREATE TABLE `cdek_deliverypoints` (
  `deliverypoint_id` int(11) NOT NULL,
  `code` varchar(12) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `region_code` int(11) NOT NULL,
  `region` varchar(64) NOT NULL,
  `city_code` int(11) NOT NULL,
  `city` varchar(64) NOT NULL,
  `postal_сode` varchar(255) NOT NULL,
  `longitude` varchar(32) NOT NULL,
  `latitude` varchar(32) NOT NULL,
  `address` varchar(255) NOT NULL,
  `address_full` varchar(255) NOT NULL,
  `address_comment` varchar(255) NOT NULL,
  `nearest_station` varchar(255) NOT NULL,
  `nearest_metro_station` varchar(255) NOT NULL,
  `work_time` varchar(255) NOT NULL,
  `phones` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `type` varchar(32) NOT NULL,
  `owner_сode` varchar(32) NOT NULL,
  `take_only` tinyint(1) NOT NULL,
  `is_handout` tinyint(1) NOT NULL,
  `is_reception` tinyint(1) NOT NULL,
  `is_dressing_room` tinyint(1) NOT NULL,
  `have_cashless` tinyint(1) NOT NULL,
  `have_cash` tinyint(1) NOT NULL,
  `allowed_cod` tinyint(1) NOT NULL,
  `site` varchar(255) NOT NULL,
  `office_image_list` varchar(500) NOT NULL,
  `work_time_list` text NOT NULL,
  `weight_min` decimal(14,2) NOT NULL,
  `weight_max` decimal(14,2) NOT NULL,
  `weight_limits` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_dispatch`
--

CREATE TABLE `cdek_dispatch` (
  `dispatch_id` int(11) NOT NULL,
  `dispatch_number` varchar(30) NOT NULL,
  `date` varchar(32) NOT NULL,
  `server_date` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order`
--

CREATE TABLE `cdek_order` (
  `order_id` int(11) NOT NULL,
  `dispatch_id` int(11) NOT NULL,
  `act_number` varchar(20) DEFAULT NULL,
  `dispatch_number` varchar(20) NOT NULL,
  `return_dispatch_number` varchar(20) NOT NULL,
  `city_id` int(11) NOT NULL,
  `city_name` varchar(128) NOT NULL,
  `city_postcode` int(11) DEFAULT NULL,
  `recipient_city_id` int(11) NOT NULL,
  `recipient_city_name` varchar(128) NOT NULL,
  `recipient_city_postcode` int(11) DEFAULT NULL,
  `recipient_name` varchar(128) NOT NULL,
  `recipient_email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) NOT NULL,
  `tariff_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `reason_id` int(11) DEFAULT 0,
  `delay_id` int(11) DEFAULT NULL,
  `delivery_recipient_cost` float(15,4) DEFAULT 0.0000,
  `cod` float(8,4) DEFAULT 0.0000,
  `cod_fact` float(8,4) DEFAULT 0.0000,
  `comment` varchar(255) DEFAULT NULL,
  `seller_name` varchar(255) DEFAULT NULL,
  `address_street` varchar(50) DEFAULT NULL,
  `address_house` varchar(30) DEFAULT NULL,
  `address_flat` varchar(10) DEFAULT NULL,
  `address_pvz_code` varchar(10) DEFAULT NULL,
  `delivery_cost` float(8,4) DEFAULT 0.0000,
  `delivery_last_change` varchar(32) DEFAULT NULL,
  `delivery_date` varchar(32) NOT NULL,
  `delivery_recipient_name` varchar(50) DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'RUB',
  `currency_cod` varchar(3) DEFAULT 'RUB',
  `last_exchange` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_add_service`
--

CREATE TABLE `cdek_order_add_service` (
  `service_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `price` float(8,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_call`
--

CREATE TABLE `cdek_order_call` (
  `call_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `time_beg` time NOT NULL,
  `time_end` time NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `recipient_name` varchar(128) DEFAULT NULL,
  `delivery_recipient_cost` float(15,4) DEFAULT 0.0000,
  `address_street` varchar(50) NOT NULL,
  `address_house` varchar(30) NOT NULL,
  `address_flat` varchar(10) NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_call_history_delay`
--

CREATE TABLE `cdek_order_call_history_delay` (
  `order_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `date_next` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_call_history_fail`
--

CREATE TABLE `cdek_order_call_history_fail` (
  `order_id` int(11) NOT NULL,
  `fail_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_call_history_good`
--

CREATE TABLE `cdek_order_call_history_good` (
  `order_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `date_deliv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_courier`
--

CREATE TABLE `cdek_order_courier` (
  `courier_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `time_beg` time NOT NULL,
  `time_end` time NOT NULL,
  `lunch_beg` time DEFAULT NULL,
  `lunch_end` time DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `city_name` varchar(128) NOT NULL,
  `send_phone` varchar(255) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `address_street` varchar(50) NOT NULL,
  `address_house` varchar(30) NOT NULL,
  `address_flat` varchar(10) NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_delay_history`
--

CREATE TABLE `cdek_order_delay_history` (
  `order_id` int(11) NOT NULL,
  `delay_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_package`
--

CREATE TABLE `cdek_order_package` (
  `package_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `number` varchar(20) NOT NULL,
  `brcode` varchar(20) NOT NULL,
  `weight` int(11) NOT NULL,
  `size_a` float(15,4) DEFAULT 0.0000,
  `size_b` float(15,4) DEFAULT 0.0000,
  `size_c` float(15,4) DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_package_item`
--

CREATE TABLE `cdek_order_package_item` (
  `package_item_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `ware_key` varchar(20) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL,
  `cost` float(15,4) NOT NULL DEFAULT 0.0000,
  `payment` float(15,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_reason`
--

CREATE TABLE `cdek_order_reason` (
  `reason_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_schedule`
--

CREATE TABLE `cdek_order_schedule` (
  `attempt_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `time_beg` time NOT NULL,
  `time_end` time NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `recipient_name` varchar(128) DEFAULT NULL,
  `address_street` varchar(50) DEFAULT NULL,
  `address_house` varchar(30) DEFAULT NULL,
  `address_flat` varchar(10) DEFAULT NULL,
  `address_pvz_code` varchar(10) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_schedule_delay`
--

CREATE TABLE `cdek_order_schedule_delay` (
  `order_id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `delay_id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_order_status_history`
--

CREATE TABLE `cdek_order_status_history` (
  `order_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `date` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `city_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cdek_zones`
--

CREATE TABLE `cdek_zones` (
  `zone_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `country` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `region_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_description`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_image`
--

CREATE TABLE `collection_image` (
  `collection_id` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_to_store`
--

CREATE TABLE `collection_to_store` (
  `collection_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competitors`
--

CREATE TABLE `competitors` (
  `competitor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `classname` varchar(255) NOT NULL,
  `currency` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competitor_price`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competitor_urls`
--

CREATE TABLE `competitor_urls` (
  `competitor_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `date_added` date NOT NULL,
  `sku` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

CREATE TABLE `counters` (
  `counter_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `counter` varchar(32) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL,
  `iso_code_3` varchar(3) NOT NULL,
  `warehouse_identifier` varchar(30) NOT NULL,
  `address_format` text NOT NULL,
  `postcode_required` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countrybrand`
--

CREATE TABLE `countrybrand` (
  `countrybrand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(500) NOT NULL DEFAULT '',
  `template` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `flag` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countrybrand_description`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countrybrand_image`
--

CREATE TABLE `countrybrand_image` (
  `countrybrand_id` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countrybrand_to_store`
--

CREATE TABLE `countrybrand_to_store` (
  `countrybrand_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country_to_fias`
--

CREATE TABLE `country_to_fias` (
  `country_id` int(11) NOT NULL,
  `fias_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `coupon_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `code` varchar(64) NOT NULL,
  `type` char(1) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  `discount_sum` varchar(500) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `min_currency` varchar(10) NOT NULL,
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
  `display_in_account` tinyint(1) NOT NULL DEFAULT 0,
  `random` tinyint(1) NOT NULL DEFAULT 0,
  `random_string` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_category`
--

CREATE TABLE `coupon_category` (
  `coupon_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_collection`
--

CREATE TABLE `coupon_collection` (
  `coupon_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_description`
--

CREATE TABLE `coupon_description` (
  `coupon_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_history`
--

CREATE TABLE `coupon_history` (
  `coupon_history_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_manufacturer`
--

CREATE TABLE `coupon_manufacturer` (
  `coupon_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_product`
--

CREATE TABLE `coupon_product` (
  `coupon_product_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_random`
--

CREATE TABLE `coupon_random` (
  `coupon_random_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `coupon_code` varchar(32) NOT NULL,
  `coupon_random` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_review`
--

CREATE TABLE `coupon_review` (
  `coupon_id` int(11) NOT NULL,
  `code` varchar(8) NOT NULL,
  `coupon_history_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `csvprice_pro`
--

CREATE TABLE `csvprice_pro` (
  `setting_id` int(11) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text DEFAULT NULL,
  `serialized` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `csvprice_pro_crontab`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `csvprice_pro_images`
--

CREATE TABLE `csvprice_pro_images` (
  `catalog_id` int(11) NOT NULL,
  `image_key` char(32) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `csvprice_pro_profiles`
--

CREATE TABLE `csvprice_pro_profiles` (
  `profile_id` int(11) NOT NULL,
  `key` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` text DEFAULT NULL,
  `serialized` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

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
  `onetime_code` int(11) DEFAULT NULL,
  `onetime_code_valid` datetime DEFAULT NULL,
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
  `passport_date` date NOT NULL,
  `passport_inn` varchar(64) NOT NULL,
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
  `sent_manual_letter` tinyint(1) NOT NULL DEFAULT 0,
  `social_id` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_ban_ip`
--

CREATE TABLE `customer_ban_ip` (
  `customer_ban_ip_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_calls`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_emails_blacklist`
--

CREATE TABLE `customer_emails_blacklist` (
  `email` varchar(128) NOT NULL,
  `status` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_emails_whitelist`
--

CREATE TABLE `customer_emails_whitelist` (
  `email` varchar(128) NOT NULL,
  `status` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_campaigns`
--

CREATE TABLE `customer_email_campaigns` (
  `customer_email_campaigns_id` bigint(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `campaign_id` varchar(255) NOT NULL,
  `mail_status` varchar(50) NOT NULL,
  `mail_opened` int(11) NOT NULL DEFAULT 0,
  `mail_clicked` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_campaigns_names`
--

CREATE TABLE `customer_email_campaigns_names` (
  `email_campaign_mailwizz_id` varchar(100) NOT NULL,
  `email_campaign_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_field`
--

CREATE TABLE `customer_field` (
  `customer_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `custom_field_value_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `value` text NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_group`
--

CREATE TABLE `customer_group` (
  `customer_group_id` int(11) NOT NULL,
  `approval` int(11) NOT NULL,
  `company_id_display` int(11) NOT NULL,
  `company_id_required` int(11) NOT NULL,
  `tax_id_display` int(11) NOT NULL,
  `tax_id_required` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_group_description`
--

CREATE TABLE `customer_group_description` (
  `customer_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_group_price`
--

CREATE TABLE `customer_group_price` (
  `customer_group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_history`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_ip`
--

CREATE TABLE `customer_ip` (
  `customer_ip_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_online`
--

CREATE TABLE `customer_online` (
  `ip` varchar(40) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `referer` text NOT NULL,
  `date_added` datetime NOT NULL,
  `useragent` varchar(400) NOT NULL,
  `is_bot` tinyint(1) NOT NULL,
  `is_pwa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_online_history`
--

CREATE TABLE `customer_online_history` (
  `customer_count` int(11) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_push_ids`
--

CREATE TABLE `customer_push_ids` (
  `customer_id` int(11) NOT NULL,
  `sendpulse_push_id` varchar(255) NOT NULL,
  `onesignal_push_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_reward`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_reward_queue`
--

CREATE TABLE `customer_reward_queue` (
  `customer_reward_queue_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `reason_code` varchar(32) NOT NULL,
  `date_added` date NOT NULL,
  `points` int(11) NOT NULL,
  `date_activate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_search_history`
--

CREATE TABLE `customer_search_history` (
  `customer_history_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `text` varchar(500) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_segments`
--

CREATE TABLE `customer_segments` (
  `customer_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_simple_fields`
--

CREATE TABLE `customer_simple_fields` (
  `customer_id` int(11) NOT NULL,
  `metadata` text DEFAULT NULL,
  `newsletter_news` text DEFAULT NULL,
  `newsletter_personal` text DEFAULT NULL,
  `viber_news` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_transaction`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_viewed`
--

CREATE TABLE `customer_viewed` (
  `customer_id` int(11) NOT NULL,
  `type` enum('c','m','p') NOT NULL,
  `entity_id` int(11) NOT NULL,
  `times` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field`
--

CREATE TABLE `custom_field` (
  `custom_field_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `required` tinyint(1) NOT NULL,
  `location` varchar(32) NOT NULL,
  `position` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_description`
--

CREATE TABLE `custom_field_description` (
  `custom_field_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_to_customer_group`
--

CREATE TABLE `custom_field_to_customer_group` (
  `custom_field_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_value`
--

CREATE TABLE `custom_field_value` (
  `custom_field_value_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_value_description`
--

CREATE TABLE `custom_field_value_description` (
  `custom_field_value_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_url_404`
--

CREATE TABLE `custom_url_404` (
  `custom_url_404_id` int(11) NOT NULL,
  `hit` int(11) DEFAULT NULL,
  `url_404` varchar(255) DEFAULT NULL,
  `url_redirect` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_asins`
--

CREATE TABLE `deleted_asins` (
  `asin` varchar(16) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `direct_timezones`
--

CREATE TABLE `direct_timezones` (
  `geomd5` varchar(64) NOT NULL,
  `timezone` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE `download` (
  `download_id` int(11) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `mask` varchar(128) NOT NULL,
  `remaining` int(11) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `download_description`
--

CREATE TABLE `download_description` (
  `download_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailmarketing_logs`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplate`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplate_config`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplate_description`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplate_logs`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplate_shortcode`
--

CREATE TABLE `emailtemplate_shortcode` (
  `emailtemplate_shortcode_id` int(11) NOT NULL,
  `emailtemplate_shortcode_code` varchar(255) NOT NULL,
  `emailtemplate_shortcode_type` enum('language','auto','auto_serialize') NOT NULL DEFAULT 'language',
  `emailtemplate_shortcode_example` text NOT NULL,
  `emailtemplate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_emailto`
--

CREATE TABLE `email_emailto` (
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `entity_reward`
--

CREATE TABLE `entity_reward` (
  `entity_reward_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` enum('c','m','co','') NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `percent` int(11) NOT NULL DEFAULT 0,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `coupon_acts` tinyint(1) NOT NULL DEFAULT 0,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `excluded_asins`
--

CREATE TABLE `excluded_asins` (
  `text` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `times` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extension`
--

CREATE TABLE `extension` (
  `extension_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facategory`
--

CREATE TABLE `facategory` (
  `facategory_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facategory_to_faproduct`
--

CREATE TABLE `facategory_to_faproduct` (
  `facategory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faproduct_to_facategory`
--

CREATE TABLE `faproduct_to_facategory` (
  `product_id` int(11) NOT NULL,
  `facategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_category`
--

CREATE TABLE `faq_category` (
  `category_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `faq_category_description`
--

CREATE TABLE `faq_category_description` (
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `faq_question`
--

CREATE TABLE `faq_question` (
  `question_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `faq_question_description`
--

CREATE TABLE `faq_question_description` (
  `question_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `feed_queue`
--

CREATE TABLE `feed_queue` (
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filter`
--

CREATE TABLE `filter` (
  `filter_id` int(11) NOT NULL,
  `filter_group_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filterpro_seo`
--

CREATE TABLE `filterpro_seo` (
  `url` varchar(255) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filter_description`
--

CREATE TABLE `filter_description` (
  `filter_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `filter_group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filter_group`
--

CREATE TABLE `filter_group` (
  `filter_group_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filter_group_description`
--

CREATE TABLE `filter_group_description` (
  `filter_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geo`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geoname_alternatename`
--

CREATE TABLE `geoname_alternatename` (
  `alternatenameId` int(11) NOT NULL,
  `geonameid` int(11) DEFAULT NULL,
  `isoLanguage` varchar(7) DEFAULT NULL,
  `alternateName` varchar(200) DEFAULT NULL,
  `isPreferredName` tinyint(1) DEFAULT NULL,
  `isShortName` tinyint(1) DEFAULT NULL,
  `isColloquial` tinyint(1) DEFAULT NULL,
  `isHistoric` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geoname_geoname`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geo_ip`
--

CREATE TABLE `geo_ip` (
  `start` bigint(20) NOT NULL,
  `end` bigint(20) NOT NULL,
  `geo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `geo_zone`
--

CREATE TABLE `geo_zone` (
  `geo_zone_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `google_base_category`
--

CREATE TABLE `google_base_category` (
  `google_base_category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hj_any_feed_feeds`
--

CREATE TABLE `hj_any_feed_feeds` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `settings` blob DEFAULT NULL,
  `version` varchar(64) DEFAULT NULL,
  `preset` int(11) DEFAULT NULL,
  `fields` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imagemaps`
--

CREATE TABLE `imagemaps` (
  `imagemap_id` int(11) NOT NULL,
  `module_code` varchar(64) NOT NULL,
  `module_id` int(11) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `information_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `igroup` varchar(50) NOT NULL,
  `show_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information_attribute`
--

CREATE TABLE `information_attribute` (
  `information_attribute_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `igroup` varchar(50) NOT NULL,
  `show_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information_attribute_description`
--

CREATE TABLE `information_attribute_description` (
  `information_attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information_attribute_to_layout`
--

CREATE TABLE `information_attribute_to_layout` (
  `information_attribute_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information_attribute_to_store`
--

CREATE TABLE `information_attribute_to_store` (
  `information_attribute_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information_description`
--

CREATE TABLE `information_description` (
  `information_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` longtext NOT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information_to_layout`
--

CREATE TABLE `information_to_layout` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information_to_store`
--

CREATE TABLE `information_to_store` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interplusplus`
--

CREATE TABLE `interplusplus` (
  `inter_id` int(11) NOT NULL,
  `num_order` int(11) DEFAULT NULL,
  `sum` int(11) DEFAULT NULL,
  `user` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_enroled` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `justin_cities`
--

CREATE TABLE `justin_cities` (
  `city_id` int(11) NOT NULL,
  `Uuid` varchar(64) NOT NULL,
  `Descr` varchar(255) NOT NULL,
  `DescrRU` varchar(255) NOT NULL,
  `Code` varchar(255) NOT NULL,
  `RegionUuid` varchar(64) NOT NULL,
  `RegionDescr` varchar(255) NOT NULL,
  `RegionDescrRU` varchar(255) NOT NULL,
  `SCOATOU` varchar(255) NOT NULL,
  `WarehouseCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `justin_city_regions`
--

CREATE TABLE `justin_city_regions` (
  `region_id` int(11) NOT NULL,
  `Uuid` varchar(64) NOT NULL,
  `Descr` varchar(255) NOT NULL,
  `DescrRU` varchar(255) NOT NULL,
  `Code` varchar(255) NOT NULL,
  `CityUuid` varchar(64) NOT NULL,
  `CityDescr` varchar(255) NOT NULL,
  `CityDescrRU` varchar(255) NOT NULL,
  `SCOATOU` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `justin_streets`
--

CREATE TABLE `justin_streets` (
  `street_id` int(11) NOT NULL,
  `Uuid` varchar(64) NOT NULL,
  `Code` varchar(64) NOT NULL,
  `Descr` varchar(255) NOT NULL,
  `DescrRU` varchar(255) NOT NULL,
  `CityUuid` varchar(64) NOT NULL,
  `CityDescr` varchar(255) NOT NULL,
  `CityDescrRU` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `justin_warehouses`
--

CREATE TABLE `justin_warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `Uuid` varchar(64) NOT NULL,
  `Descr` varchar(255) NOT NULL,
  `DescrRU` varchar(255) NOT NULL,
  `Code` varchar(64) NOT NULL,
  `RegionUuid` varchar(64) NOT NULL,
  `CityUuid` varchar(64) NOT NULL,
  `CityDescr` varchar(255) NOT NULL,
  `CityDescrRU` varchar(255) NOT NULL,
  `possibility_to_pay_by_card` tinyint(1) NOT NULL,
  `possibility_to_accept_payment` tinyint(1) NOT NULL,
  `availability_of_parcel_locker` tinyint(1) NOT NULL,
  `Address` varchar(500) NOT NULL,
  `Lat` varchar(32) NOT NULL,
  `Lng` varchar(32) NOT NULL,
  `departNumber` int(11) NOT NULL,
  `houseNumber` varchar(32) NOT NULL,
  `StatusDepart` int(11) NOT NULL,
  `parcels_without_pay` tinyint(1) NOT NULL,
  `Monday` varchar(32) NOT NULL,
  `Tuesday` varchar(32) NOT NULL,
  `Wednesday` varchar(32) NOT NULL,
  `Thursday` varchar(32) NOT NULL,
  `Friday` varchar(32) NOT NULL,
  `Saturday` varchar(32) NOT NULL,
  `Sunday` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `justin_zones`
--

CREATE TABLE `justin_zones` (
  `zone_id` int(11) NOT NULL,
  `Uuid` varchar(64) NOT NULL,
  `Code` varchar(32) NOT NULL,
  `Descr` varchar(255) NOT NULL,
  `DescrRu` varchar(255) NOT NULL,
  `SCOATOU` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `justin_zone_regions`
--

CREATE TABLE `justin_zone_regions` (
  `region_id` int(11) NOT NULL,
  `Uuid` varchar(64) NOT NULL,
  `Code` varchar(64) NOT NULL,
  `Descr` varchar(255) NOT NULL,
  `DescrRU` varchar(255) NOT NULL,
  `ZoneUuid` varchar(64) NOT NULL,
  `ZoneDescr` varchar(255) NOT NULL,
  `ZoneDescrRU` varchar(255) NOT NULL,
  `ZoneType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyworder`
--

CREATE TABLE `keyworder` (
  `keyworder_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyworder_description`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landingpage`
--

CREATE TABLE `landingpage` (
  `landingpage_id` int(11) NOT NULL,
  `bottom` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landingpage_description`
--

CREATE TABLE `landingpage_description` (
  `landingpage_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `seo_title` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landingpage_to_layout`
--

CREATE TABLE `landingpage_to_layout` (
  `landingpage_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landingpage_to_store`
--

CREATE TABLE `landingpage_to_store` (
  `landingpage_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layout`
--

CREATE TABLE `layout` (
  `layout_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layout_route`
--

CREATE TABLE `layout_route` (
  `layout_route_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `route` varchar(255) NOT NULL,
  `template` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `legalperson`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `length_class`
--

CREATE TABLE `length_class` (
  `length_class_id` int(11) NOT NULL,
  `value` decimal(15,8) NOT NULL,
  `system_key` varchar(100) NOT NULL,
  `amazon_key` varchar(100) NOT NULL,
  `variants` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `length_class_description`
--

CREATE TABLE `length_class_description` (
  `length_class_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `unit` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `local_supplier_products`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailwizz_queue`
--

CREATE TABLE `mailwizz_queue` (
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manager_kpi`
--

CREATE TABLE `manager_kpi` (
  `manager_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `kpi_json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manager_order_status_dynamics`
--

CREATE TABLE `manager_order_status_dynamics` (
  `manager_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manager_order_status_dynamics2`
--

CREATE TABLE `manager_order_status_dynamics2` (
  `manager_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `manufacturer_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `tip` varchar(15) NOT NULL,
  `menu_brand` tinyint(1) NOT NULL DEFAULT 0,
  `homepage` tinyint(1) NOT NULL DEFAULT 0,
  `show_goods` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `back_image` varchar(500) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `banner_width` int(11) NOT NULL,
  `banner_height` int(11) NOT NULL,
  `tpl` varchar(255) NOT NULL,
  `new` tinyint(1) NOT NULL DEFAULT 0,
  `priceva_enable` tinyint(4) NOT NULL DEFAULT 0,
  `priceva_feed` varchar(32) NOT NULL DEFAULT '0',
  `hotline_enable` tinyint(1) NOT NULL DEFAULT 0,
  `bought_for_month` int(11) DEFAULT 0,
  `products_total` int(11) DEFAULT 0,
  `products_total_enabled` int(11) DEFAULT 0,
  `sort_order` int(11) NOT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer_description`
--

CREATE TABLE `manufacturer_description` (
  `manufacturer_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `alternate_name` longtext NOT NULL,
  `location` varchar(255) NOT NULL,
  `short_description` varchar(500) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0,
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_h1` varchar(255) NOT NULL DEFAULT '',
  `tag` text DEFAULT NULL,
  `products_title` varchar(500) NOT NULL,
  `products_meta_description` varchar(500) NOT NULL,
  `collections_title` varchar(500) NOT NULL,
  `collections_meta_description` varchar(500) NOT NULL,
  `categories_title` varchar(500) NOT NULL,
  `categories_meta_description` varchar(500) NOT NULL,
  `articles_title` varchar(500) NOT NULL,
  `articles_meta_description` varchar(500) NOT NULL,
  `newproducts_title` varchar(500) NOT NULL,
  `newproducts_meta_description` varchar(500) NOT NULL,
  `special_title` varchar(500) NOT NULL,
  `special_meta_description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer_page_content`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer_to_layout`
--

CREATE TABLE `manufacturer_to_layout` (
  `manufacturer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer_to_store`
--

CREATE TABLE `manufacturer_to_store` (
  `manufacturer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maxmind_geo_country`
--

CREATE TABLE `maxmind_geo_country` (
  `start` bigint(20) NOT NULL,
  `end` bigint(20) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mono_orders`
--

CREATE TABLE `mono_orders` (
  `Id` int(11) NOT NULL,
  `InvoiceId` varchar(50) DEFAULT NULL,
  `CheckoutOrderId` varchar(255) NOT NULL,
  `OrderId` int(10) DEFAULT NULL,
  `SecretKey` varchar(51) DEFAULT NULL,
  `is_refunded` int(10) DEFAULT 0,
  `amount_refunded` decimal(15,4) DEFAULT 0.0000,
  `refund_status` varchar(51) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `payment_data` longtext DEFAULT NULL,
  `is_hold` int(10) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `multi_pay_payment`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nauthor`
--

CREATE TABLE `nauthor` (
  `nauthor_id` int(11) NOT NULL,
  `adminid` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `nauthor_description`
--

CREATE TABLE `nauthor_description` (
  `nauthor_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `ctitle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ncategory`
--

CREATE TABLE `ncategory` (
  `ncategory_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
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
-- Table structure for table `ncategory_description`
--

CREATE TABLE `ncategory_description` (
  `ncategory_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ncategory_to_layout`
--

CREATE TABLE `ncategory_to_layout` (
  `ncategory_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ncategory_to_store`
--

CREATE TABLE `ncategory_to_store` (
  `ncategory_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ncomments`
--

CREATE TABLE `ncomments` (
  `ncomment_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL DEFAULT 0,
  `author` varchar(64) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

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
-- Table structure for table `news_description`
--

CREATE TABLE `news_description` (
  `news_id` int(11) NOT NULL DEFAULT 0,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `ctitle` varchar(255) NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description2` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `meta_desc` varchar(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `ntags` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `recipe` text NOT NULL,
  `cfield1` varchar(255) NOT NULL DEFAULT '',
  `cfield2` varchar(255) NOT NULL DEFAULT '',
  `cfield3` varchar(255) NOT NULL DEFAULT '',
  `cfield4` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `news_gallery`
--

CREATE TABLE `news_gallery` (
  `news_image_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `image` varchar(512) DEFAULT NULL,
  `text` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_related`
--

CREATE TABLE `news_related` (
  `news_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `news_to_layout`
--

CREATE TABLE `news_to_layout` (
  `news_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `news_to_ncategory`
--

CREATE TABLE `news_to_ncategory` (
  `news_id` int(11) NOT NULL,
  `ncategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `news_to_store`
--

CREATE TABLE `news_to_store` (
  `news_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `news_video`
--

CREATE TABLE `news_video` (
  `news_video_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `novaposhta_cities`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `novaposhta_cities_ww`
--

CREATE TABLE `novaposhta_cities_ww` (
  `CityID` int(11) NOT NULL,
  `Ref` varchar(64) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `DescriptionRu` varchar(255) NOT NULL,
  `Delivery1` int(11) NOT NULL,
  `Delivery2` int(11) NOT NULL,
  `Delivery3` int(11) NOT NULL,
  `Delivery4` int(11) NOT NULL,
  `Delivery5` int(11) NOT NULL,
  `Delivery6` int(11) NOT NULL,
  `Delivery7` int(11) NOT NULL,
  `Area` varchar(64) NOT NULL,
  `SettlementType` varchar(64) NOT NULL,
  `SettlementTypeDescriptionRu` varchar(255) NOT NULL,
  `SettlementTypeDescription` int(11) NOT NULL,
  `WarehouseCount` int(11) NOT NULL DEFAULT 0,
  `deliveryPeriod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `novaposhta_streets`
--

CREATE TABLE `novaposhta_streets` (
  `StreetID` int(11) NOT NULL,
  `Ref` varchar(64) NOT NULL,
  `CityRef` varchar(64) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `DescriptionRu` varchar(500) NOT NULL,
  `StreetsTypeRef` varchar(255) NOT NULL,
  `StreetsType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `novaposhta_warehouses`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `novaposhta_zones`
--

CREATE TABLE `novaposhta_zones` (
  `ZoneID` int(11) NOT NULL,
  `Ref` varchar(64) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `DescriptionRu` varchar(255) NOT NULL,
  `AreasCenter` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option_description`
--

CREATE TABLE `ocfilter_option_description` (
  `option_id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `postfix` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option_to_category`
--

CREATE TABLE `ocfilter_option_to_category` (
  `option_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option_to_store`
--

CREATE TABLE `ocfilter_option_to_store` (
  `option_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option_value`
--

CREATE TABLE `ocfilter_option_value` (
  `value_id` bigint(20) NOT NULL,
  `option_id` int(11) NOT NULL DEFAULT 0,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(6) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option_value_description`
--

CREATE TABLE `ocfilter_option_value_description` (
  `value_id` bigint(20) NOT NULL,
  `option_id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option_value_to_product`
--

CREATE TABLE `ocfilter_option_value_to_product` (
  `ocfilter_option_value_to_product_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `value_id` bigint(20) NOT NULL,
  `slide_value_min` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `slide_value_max` decimal(15,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_option_value_to_product_description`
--

CREATE TABLE `ocfilter_option_value_to_product_description` (
  `product_id` int(11) NOT NULL,
  `value_id` bigint(20) NOT NULL,
  `option_id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ocfilter_page`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oc_feedback`
--

CREATE TABLE `oc_feedback` (
  `feedback_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oc_yandex_category`
--

CREATE TABLE `oc_yandex_category` (
  `yandex_category_id` int(11) NOT NULL,
  `level1` varchar(50) NOT NULL,
  `level2` varchar(50) NOT NULL,
  `level3` varchar(50) NOT NULL,
  `level4` varchar(50) NOT NULL,
  `level5` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `odinass_product_queue`
--

CREATE TABLE `odinass_product_queue` (
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option`
--

CREATE TABLE `option` (
  `option_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_description`
--

CREATE TABLE `option_description` (
  `option_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_tooltip`
--

CREATE TABLE `option_tooltip` (
  `option_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tooltip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_value`
--

CREATE TABLE `option_value` (
  `option_value_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_value_description`
--

CREATE TABLE `option_value_description` (
  `option_value_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

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
  `shipping_passport_serie` varchar(30) NOT NULL COMMENT 'Серия и номер паспорта получателя',
  `shipping_passport_date` date NOT NULL,
  `shipping_passport_inn` varchar(64) NOT NULL,
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
  `costprice` decimal(15,2) NOT NULL DEFAULT 0.00,
  `profitability` decimal(15,1) NOT NULL DEFAULT 0.0,
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
  `forgotten_cart_reminder` int(11) NOT NULL DEFAULT 0,
  `forgotten_cart_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
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
  `pay_equireWPP` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Включить оплату Mono',
  `pay_equireMono` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Включить оплату Mono',
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
  `monocheckout` tinyint(1) NOT NULL DEFAULT 0,
  `yam` tinyint(1) NOT NULL DEFAULT 0,
  `yam_id` int(11) NOT NULL,
  `yam_express` tinyint(1) DEFAULT 0,
  `yam_campaign_id` int(11) DEFAULT NULL,
  `yam_shipment_date` date NOT NULL,
  `yam_status` varchar(32) NOT NULL,
  `yam_substatus` varchar(64) NOT NULL,
  `yam_fake` tinyint(1) NOT NULL DEFAULT 0,
  `yam_shipment_id` int(11) NOT NULL,
  `yam_box_id` int(11) NOT NULL,
  `fcheque_link` varchar(1024) NOT NULL,
  `needs_checkboxua` tinyint(1) NOT NULL DEFAULT 0,
  `paid_by` varchar(64) NOT NULL,
  `do_not_call` tinyint(1) NOT NULL DEFAULT 0,
  `amazon_offers_type` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_amazon`
--

CREATE TABLE `order_amazon` (
  `order_id` int(11) NOT NULL,
  `amazon_order_id` varchar(255) NOT NULL,
  `free_shipping` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_amazon_product`
--

CREATE TABLE `order_amazon_product` (
  `order_product_id` int(11) NOT NULL,
  `amazon_order_item_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_amazon_report`
--

CREATE TABLE `order_amazon_report` (
  `order_id` int(11) NOT NULL,
  `submission_id` varchar(255) NOT NULL,
  `status` enum('processing','error','success') NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_courier_history`
--

CREATE TABLE `order_courier_history` (
  `order_id` int(11) NOT NULL,
  `courier_id` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `date_status` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_download`
--

CREATE TABLE `order_download` (
  `order_download_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `mask` varchar(128) NOT NULL,
  `remaining` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_field`
--

CREATE TABLE `order_field` (
  `order_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `custom_field_value_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `value` text NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_fraud`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_invoice_history`
--

CREATE TABLE `order_invoice_history` (
  `order_invoice_id` bigint(20) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_name` varchar(50) NOT NULL,
  `html` mediumtext NOT NULL,
  `datetime` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_option`
--

CREATE TABLE `order_option` (
  `order_option_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `product_option_id` int(11) NOT NULL,
  `product_option_value_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

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
  `amazon_offers_type` varchar(3) DEFAULT NULL,
  `from_stock` tinyint(1) NOT NULL DEFAULT 0,
  `from_bd_gift` tinyint(1) NOT NULL DEFAULT 0,
  `is_returned` tinyint(1) NOT NULL DEFAULT 0,
  `quantity_from_stock` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `totals_json` text NOT NULL,
  `date_added_fo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_bought`
--

CREATE TABLE `order_product_bought` (
  `bought_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `supplier` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_history`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_nogood`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_reserves`
--

CREATE TABLE `order_product_reserves` (
  `order_reserve_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `not_changeable` tinyint(1) NOT NULL DEFAULT 0,
  `uuid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_supply`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_tracker`
--

CREATE TABLE `order_product_tracker` (
  `order_product_tracker_id` int(11) NOT NULL,
  `order_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_product_status` varchar(255) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_untaken`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_receipt`
--

CREATE TABLE `order_receipt` (
  `order_receipt_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `receipt_id` varchar(60) NOT NULL,
  `serial` int(11) NOT NULL,
  `status` varchar(32) NOT NULL,
  `fiscal_code` varchar(32) NOT NULL,
  `fiscal_date` varchar(32) NOT NULL,
  `is_created_offline` tinyint(1) NOT NULL,
  `is_sent_dps` tinyint(1) NOT NULL,
  `sent_dps_at` varchar(64) NOT NULL,
  `all_json_data` mediumtext NOT NULL,
  `type` varchar(64) NOT NULL,
  `api` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_recurring`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_recurring_transaction`
--

CREATE TABLE `order_recurring_transaction` (
  `order_recurring_transaction_id` int(11) NOT NULL,
  `order_recurring_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `amount` decimal(10,4) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_reject_reason`
--

CREATE TABLE `order_reject_reason` (
  `reject_reason_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_related`
--

CREATE TABLE `order_related` (
  `order_id` int(11) NOT NULL,
  `related_order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_save_history`
--

CREATE TABLE `order_save_history` (
  `order_save_id` bigint(20) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_set`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_simple_fields`
--

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
  `newsletter_personal` int(11) NOT NULL,
  `viber_news` int(11) NOT NULL,
  `do_not_call` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_sms_history`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `status_bg_color` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status_txt_color` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `status_fa_icon` varchar(50) NOT NULL,
  `front_bg_color` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_linked`
--

CREATE TABLE `order_status_linked` (
  `order_status_id` int(11) NOT NULL,
  `linked_order_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_total`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_total_tax`
--

CREATE TABLE `order_total_tax` (
  `order_total_id` int(11) NOT NULL DEFAULT 0,
  `code` varchar(255) DEFAULT NULL,
  `tax` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_to_1c_queue`
--

CREATE TABLE `order_to_1c_queue` (
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tracker`
--

CREATE TABLE `order_tracker` (
  `order_tracker_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tracker_sms`
--

CREATE TABLE `order_tracker_sms` (
  `tracker_sms_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `partie_num` varchar(10) NOT NULL,
  `tracker_type` varchar(20) NOT NULL,
  `date_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_ttns`
--

CREATE TABLE `order_ttns` (
  `order_ttn_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `ttn` varchar(255) NOT NULL,
  `date_ttn` date NOT NULL,
  `sms_sent` datetime NOT NULL,
  `delivery_code` varchar(55) NOT NULL,
  `tracking_status` varchar(512) NOT NULL,
  `tracking_data` text NOT NULL,
  `taken` tinyint(1) DEFAULT 0,
  `rejected` tinyint(1) NOT NULL DEFAULT 0,
  `waiting` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_ukrcredits`
--

CREATE TABLE `order_ukrcredits` (
  `order_id` int(11) NOT NULL,
  `ukrcredits_payment_type` varchar(2) NOT NULL,
  `ukrcredits_order_id` varchar(64) NOT NULL,
  `ukrcredits_order_status` varchar(64) NOT NULL,
  `ukrcredits_order_substatus` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_voucher`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_tries`
--

CREATE TABLE `otp_tries` (
  `ip_addr` varchar(128) NOT NULL,
  `tries` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parser_queue`
--

CREATE TABLE `parser_queue` (
  `parser_queue_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `processed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pavoslidergroups`
--

CREATE TABLE `pavoslidergroups` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `params` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pavosliderlayers`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `priceva_data`
--

CREATE TABLE `priceva_data` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `articul` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `default_currency` varchar(5) NOT NULL,
  `default_price` decimal(15,2) NOT NULL,
  `default_available` tinyint(1) NOT NULL,
  `default_discount` decimal(15,2) NOT NULL,
  `repricing_min` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `priceva_sources`
--

CREATE TABLE `priceva_sources` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `url` varchar(2048) NOT NULL,
  `url_md5` varchar(64) NOT NULL,
  `company_name` varchar(512) NOT NULL,
  `region_name` varchar(512) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `last_check_date` datetime NOT NULL,
  `relevance_status` int(11) NOT NULL DEFAULT 0,
  `price` decimal(15,2) NOT NULL,
  `in_stock` tinyint(1) NOT NULL,
  `discount_type` int(11) NOT NULL,
  `discount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

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
  `product_group_id` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `collection_id` bigint(20) NOT NULL,
  `shipping` tinyint(1) NOT NULL DEFAULT 1,
  `cost` decimal(15,2) NOT NULL,
  `costprice` decimal(15,2) NOT NULL,
  `profitability` decimal(15,1) NOT NULL,
  `actual_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `actual_cost_date` date NOT NULL DEFAULT '0000-00-00',
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_delayed` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_special` decimal(15,2) NOT NULL,
  `price_special_delayed` decimal(15,2) NOT NULL,
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
  `bought_for_3month` int(11) NOT NULL,
  `bought_for_6month` int(11) NOT NULL,
  `bought_for_12month` int(11) NOT NULL,
  `bought_for_alltime` int(11) NOT NULL,
  `big_business` tinyint(1) NOT NULL DEFAULT 0,
  `is_markdown` int(11) NOT NULL,
  `markdown_product_id` int(11) NOT NULL,
  `lock_points` tinyint(1) NOT NULL DEFAULT 0,
  `yam_disable` tinyint(1) NOT NULL DEFAULT 0,
  `yam_product_id` varchar(32) NOT NULL,
  `yam_marketSku` int(11) NOT NULL,
  `hotline_disable` tinyint(1) NOT NULL DEFAULT 0,
  `priceva_enable` tinyint(1) NOT NULL DEFAULT 0,
  `priceva_disable` tinyint(1) NOT NULL DEFAULT 0,
  `yam_in_feed` tinyint(1) NOT NULL DEFAULT 0,
  `ozon_in_feed` tinyint(1) NOT NULL DEFAULT 0,
  `vk_in_feed` tinyint(1) NOT NULL DEFAULT 0,
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
  `amazon_offers_type` enum('A','P','AP','O','N') NOT NULL,
  `amazon_seller_quality` varchar(5) NOT NULL,
  `amzn_ignore` tinyint(1) NOT NULL COMMENT 'Не обновлять цену с Amazon',
  `amzn_rating` decimal(15,2) NOT NULL,
  `amazon_best_price` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Лучший оффер на Amazon',
  `amazon_lowest_price` decimal(15,2) NOT NULL,
  `added_from_amazon` tinyint(1) NOT NULL DEFAULT 0,
  `added_from_supplier` int(11) DEFAULT 0,
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
  `xreviews` int(11) DEFAULT 0,
  `xhasvideo` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_additional_offer`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_additional_offer_to_store`
--

CREATE TABLE `product_additional_offer_to_store` (
  `product_additional_offer_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_also_bought`
--

CREATE TABLE `product_also_bought` (
  `product_id` int(11) NOT NULL,
  `also_bought_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_also_viewed`
--

CREATE TABLE `product_also_viewed` (
  `product_id` int(11) NOT NULL,
  `also_viewed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_amzn_data`
--

CREATE TABLE `product_amzn_data` (
  `product_id` int(11) NOT NULL,
  `asin` varchar(255) NOT NULL,
  `file` varchar(512) NOT NULL,
  `json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `product_amzn_offers`
--

CREATE TABLE `product_amzn_offers` (
  `amazon_offer_id` int(11) NOT NULL,
  `asin` varchar(128) NOT NULL,
  `priceCurrency` varchar(5) NOT NULL,
  `priceAmount` decimal(15,2) NOT NULL,
  `importFeeCurrency` varchar(5) NOT NULL,
  `importFeeAmount` decimal(15,2) NOT NULL,
  `deliveryCurrency` varchar(5) NOT NULL,
  `deliveryAmount` decimal(15,2) NOT NULL,
  `deliveryIsFree` tinyint(1) NOT NULL,
  `deliveryIsFba` tinyint(1) NOT NULL,
  `deliveryIsShippedCrossBorder` tinyint(1) NOT NULL,
  `deliveryComments` varchar(512) NOT NULL,
  `minDays` int(11) NOT NULL,
  `deliveryFrom` date NOT NULL,
  `deliveryTo` date NOT NULL,
  `conditionIsNew` tinyint(1) NOT NULL,
  `conditionTitle` varchar(512) NOT NULL,
  `conditionComments` varchar(512) NOT NULL,
  `sellerName` varchar(512) NOT NULL,
  `sellerID` varchar(64) NOT NULL,
  `sellerLink` varchar(1024) NOT NULL,
  `sellerRating50` int(11) NOT NULL,
  `sellerRatingsTotal` int(11) NOT NULL,
  `sellerPositiveRatings100` int(11) NOT NULL,
  `sellerQuality` varchar(5) NOT NULL,
  `date_added` datetime NOT NULL,
  `is_min_price` tinyint(1) NOT NULL,
  `isPrime` tinyint(1) NOT NULL,
  `isBuyBoxWinner` tinyint(1) NOT NULL DEFAULT 0,
  `isBestOffer` tinyint(1) NOT NULL,
  `isNativeOffer` tinyint(1) NOT NULL DEFAULT 0,
  `offerCountry` varchar(3) NOT NULL,
  `offerRating` decimal(15,2) NOT NULL,
  `offer_id` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_anyrelated`
--

CREATE TABLE `product_anyrelated` (
  `product_id` int(11) NOT NULL,
  `anyrelated_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute`
--

CREATE TABLE `product_attribute` (
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `text` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_child`
--

CREATE TABLE `product_child` (
  `product_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_costs`
--

CREATE TABLE `product_costs` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `cost` decimal(15,2) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `min_sale_price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_description`
--

CREATE TABLE `product_description` (
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `short_name_d` varchar(50) NOT NULL,
  `name_of_option` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `description_full` longtext NOT NULL,
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
  `title_image` text DEFAULT NULL,
  `manufacturer_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_discount`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_feature`
--

CREATE TABLE `product_feature` (
  `product_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `text` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_filter`
--

CREATE TABLE `product_filter` (
  `product_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_front_price`
--

CREATE TABLE `product_front_price` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `special` decimal(15,2) NOT NULL,
  `reward` decimal(15,2) NOT NULL,
  `currency` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_groups`
--

CREATE TABLE `product_groups` (
  `product_group_id` int(11) NOT NULL,
  `product_group_name` varchar(255) NOT NULL,
  `product_group_exclude_remarketing` tinyint(1) NOT NULL DEFAULT 0,
  `product_group_feed` tinyint(1) NOT NULL DEFAULT 0,
  `product_group_feed_file` varchar(255) NOT NULL,
  `product_group_text_color` varchar(255) NOT NULL,
  `product_group_bg_color` varchar(255) NOT NULL,
  `product_group_fa_icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `product_image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_master`
--

CREATE TABLE `product_master` (
  `master_product_id` int(11) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_offers_history`
--

CREATE TABLE `product_offers_history` (
  `offer_history_id` int(11) NOT NULL,
  `asin` varchar(32) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL,
  `original_offer_data` longtext NOT NULL,
  `offer_data` longtext NOT NULL,
  `weight` decimal(15,2) NOT NULL,
  `amazon_best_price` decimal(15,2) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `costprice` decimal(15,2) NOT NULL,
  `profitability` decimal(15,2) NOT NULL,
  `skipped` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_option`
--

CREATE TABLE `product_option` (
  `product_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `option_value` text NOT NULL,
  `required` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_option_value`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_history`
--

CREATE TABLE `product_price_history` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `currency` varchar(8) NOT NULL,
  `type` varchar(64) NOT NULL,
  `source` varchar(64) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_national_to_store`
--

CREATE TABLE `product_price_national_to_store` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_delayed` decimal(15,2) NOT NULL,
  `special` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_national_to_store1`
--

CREATE TABLE `product_price_national_to_store1` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `special` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_national_to_yam`
--

CREATE TABLE `product_price_national_to_yam` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `special` decimal(15,2) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_to_store`
--

CREATE TABLE `product_price_to_store` (
  `product_id` bigint(20) NOT NULL,
  `store_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_delayed` decimal(15,2) NOT NULL DEFAULT 0.00,
  `special` decimal(15,2) NOT NULL,
  `settled_from_1c` tinyint(1) NOT NULL DEFAULT 0,
  `dot_not_overload_1c` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_product_option`
--

CREATE TABLE `product_product_option` (
  `product_product_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_product_option_value`
--

CREATE TABLE `product_product_option_value` (
  `product_product_option_value_id` int(11) NOT NULL,
  `product_product_option_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_option_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_profile`
--

CREATE TABLE `product_profile` (
  `product_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_purchase`
--

CREATE TABLE `product_purchase` (
  `purchase_uuid` varchar(64) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_name` varchar(256) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_recurring`
--

CREATE TABLE `product_recurring` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_related`
--

CREATE TABLE `product_related` (
  `product_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_related_set`
--

CREATE TABLE `product_related_set` (
  `product_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_reward`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_shop_by_look`
--

CREATE TABLE `product_shop_by_look` (
  `product_id` int(11) NOT NULL,
  `shop_by_look_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_similar`
--

CREATE TABLE `product_similar` (
  `product_id` int(11) NOT NULL,
  `similar_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_similar_to_consider`
--

CREATE TABLE `product_similar_to_consider` (
  `product_id` int(11) NOT NULL,
  `similar_to_consider_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sources`
--

CREATE TABLE `product_sources` (
  `product_source_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `source` varchar(500) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_special`
--

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
  `set_by_reprice` tinyint(1) NOT NULL DEFAULT 0,
  `set_by_stock` tinyint(1) NOT NULL DEFAULT 0,
  `set_by_stock_illiquid` tinyint(1) NOT NULL DEFAULT 0,
  `date_settled_by_stock` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_special_attribute`
--

CREATE TABLE `product_special_attribute` (
  `product_special_attribute_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_special_backup`
--

CREATE TABLE `product_special_backup` (
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
  `set_by_reprice` tinyint(1) NOT NULL DEFAULT 0,
  `set_by_stock` tinyint(1) NOT NULL DEFAULT 0,
  `set_by_stock_illiquid` tinyint(1) NOT NULL DEFAULT 0,
  `date_settled_by_stock` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sponsored`
--

CREATE TABLE `product_sponsored` (
  `product_id` int(11) NOT NULL,
  `sponsored_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_status`
--

CREATE TABLE `product_status` (
  `product_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `product_show` int(11) NOT NULL,
  `category_show` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sticker`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stock_limits`
--

CREATE TABLE `product_stock_limits` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL DEFAULT 0,
  `rec_stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stock_status`
--

CREATE TABLE `product_stock_status` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `stock_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stock_waits`
--

CREATE TABLE `product_stock_waits` (
  `product_id` int(11) NOT NULL,
  `quantity_stock` int(11) NOT NULL DEFAULT 0,
  `quantity_stockM` int(11) NOT NULL DEFAULT 0,
  `quantity_stockK` int(11) NOT NULL DEFAULT 0,
  `quantity_stockMN` int(11) NOT NULL DEFAULT 0,
  `quantity_stockAS` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tab`
--

CREATE TABLE `product_tab` (
  `tab_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `type` enum('default','regular','reserved') NOT NULL DEFAULT 'regular',
  `key` varchar(128) NOT NULL DEFAULT '',
  `login` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tab_content`
--

CREATE TABLE `product_tab_content` (
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tab_id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tab_default`
--

CREATE TABLE `product_tab_default` (
  `tab_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tab_name`
--

CREATE TABLE `product_tab_name` (
  `tab_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_to_category`
--

CREATE TABLE `product_to_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `main_category` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_to_download`
--

CREATE TABLE `product_to_download` (
  `product_id` int(11) NOT NULL,
  `download_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_to_layout`
--

CREATE TABLE `product_to_layout` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_to_set`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_to_store`
--

CREATE TABLE `product_to_store` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_to_tab`
--

CREATE TABLE `product_to_tab` (
  `product_id` int(11) NOT NULL,
  `tab_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ukrcredits`
--

CREATE TABLE `product_ukrcredits` (
  `product_id` int(11) NOT NULL,
  `product_pp` int(1) NOT NULL,
  `product_ii` int(1) NOT NULL,
  `product_mb` int(1) NOT NULL,
  `partscount_pp` int(2) NOT NULL,
  `partscount_ii` int(2) NOT NULL,
  `partscount_mb` int(2) NOT NULL,
  `markup_pp` decimal(15,4) NOT NULL,
  `markup_ii` decimal(15,4) NOT NULL,
  `markup_mb` decimal(15,4) NOT NULL,
  `special_pp` int(1) NOT NULL,
  `special_ii` int(1) NOT NULL,
  `special_mb` int(1) NOT NULL,
  `discount_pp` int(1) NOT NULL,
  `discount_ii` int(1) NOT NULL,
  `discount_mb` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `main_asin` varchar(32) NOT NULL,
  `variant_asin` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants_ids`
--

CREATE TABLE `product_variants_ids` (
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_video`
--

CREATE TABLE `product_video` (
  `product_video_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(1024) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_video_description`
--

CREATE TABLE `product_video_description` (
  `product_video_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `language_id` varchar(255) DEFAULT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_view_to_purchase`
--

CREATE TABLE `product_view_to_purchase` (
  `product_id` int(11) NOT NULL,
  `view_to_purchase_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_yam_data`
--

CREATE TABLE `product_yam_data` (
  `product_id` int(11) NOT NULL,
  `yam_real_price` decimal(15,2) NOT NULL,
  `yam_hidings` text NOT NULL,
  `yam_category_name` varchar(1024) NOT NULL,
  `yam_category_id` int(11) NOT NULL,
  `yam_fees` text NOT NULL,
  `AGENCY_COMMISSION` decimal(15,2) NOT NULL,
  `FEE` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_yam_recommended_prices`
--

CREATE TABLE `product_yam_recommended_prices` (
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `BUYBOX` decimal(15,2) NOT NULL,
  `DEFAULT_OFFER` decimal(15,2) NOT NULL,
  `MIN_PRICE_MARKET` decimal(15,2) NOT NULL,
  `MAX_DISCOUNT_BASE` decimal(15,2) NOT NULL,
  `MARKET_OUTLIER_PRICE` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile_description`
--

CREATE TABLE `profile_description` (
  `profile_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue_mail`
--

CREATE TABLE `queue_mail` (
  `queue_mail_id` int(11) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue_push`
--

CREATE TABLE `queue_push` (
  `queue_push_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue_sms`
--

CREATE TABLE `queue_sms` (
  `queue_sms_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `raw` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `redirect`
--

CREATE TABLE `redirect` (
  `redirect_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `from_url` varchar(600) NOT NULL,
  `to_url` varchar(600) NOT NULL,
  `response_code` int(11) NOT NULL DEFAULT 301,
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `times_used` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Table structure for table `referrer_patterns`
--

CREATE TABLE `referrer_patterns` (
  `pattern_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `url_mask` varchar(256) NOT NULL,
  `url_param` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_action`
--

CREATE TABLE `return_action` (
  `return_action_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_history`
--

CREATE TABLE `return_history` (
  `return_history_id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `return_status_id` int(11) NOT NULL,
  `notify` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_reason`
--

CREATE TABLE `return_reason` (
  `return_reason_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_status`
--

CREATE TABLE `return_status` (
  `return_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

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
  `date_approved` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `auto_gen` tinyint(1) NOT NULL DEFAULT 0,
  `store_id` int(11) NOT NULL DEFAULT 0,
  `rewarded` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_description`
--

CREATE TABLE `review_description` (
  `review_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  `answer` mediumtext NOT NULL,
  `bads` mediumtext NOT NULL,
  `good` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_fields`
--

CREATE TABLE `review_fields` (
  `review_id` int(11) NOT NULL,
  `mark` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `comm_comfort` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_name`
--

CREATE TABLE `review_name` (
  `review_name_id` int(11) NOT NULL,
  `l_code` varchar(5) DEFAULT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_template`
--

CREATE TABLE `review_template` (
  `review_template_id` int(11) NOT NULL,
  `l_code` varchar(5) DEFAULT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_history`
--

CREATE TABLE `search_history` (
  `text` varchar(500) NOT NULL,
  `times` int(11) NOT NULL,
  `results` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `segments`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `segments_dynamics`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seocities`
--

CREATE TABLE `seocities` (
  `seocity_id` bigint(20) NOT NULL,
  `seocity_name` varchar(255) NOT NULL,
  `seocity_name2` varchar(255) NOT NULL,
  `seocity_phone` varchar(255) NOT NULL,
  `seocity_phone2` varchar(255) NOT NULL,
  `seocity_delivery_info` varchar(255) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seo_hreflang`
--

CREATE TABLE `seo_hreflang` (
  `language_id` int(11) NOT NULL,
  `query` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `set`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT 0,
  `group` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` longtext DEFAULT NULL,
  `serialized` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `set_description`
--

CREATE TABLE `set_description` (
  `set_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `set_to_category`
--

CREATE TABLE `set_to_category` (
  `set_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `set_to_store`
--

CREATE TABLE `set_to_store` (
  `set_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `id` int(11) NOT NULL,
  `shift_id` varchar(64) NOT NULL,
  `serial` int(11) NOT NULL,
  `status` varchar(32) NOT NULL,
  `z_report_id` varchar(64) NOT NULL,
  `all_json_data` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shoputils_citycourier_description`
--

CREATE TABLE `shoputils_citycourier_description` (
  `language_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Shoputils citycourier shipping ';

-- --------------------------------------------------------

--
-- Table structure for table `shoputils_cumulative_discounts`
--

CREATE TABLE `shoputils_cumulative_discounts` (
  `discount_id` int(11) NOT NULL,
  `days` int(11) NOT NULL DEFAULT 0,
  `summ` decimal(11,2) NOT NULL DEFAULT 0.00,
  `percent` decimal(5,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `products_special` tinyint(1) NOT NULL DEFAULT 0,
  `first_order` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts';

-- --------------------------------------------------------

--
-- Table structure for table `shoputils_cumulative_discounts_cmsdata`
--

CREATE TABLE `shoputils_cumulative_discounts_cmsdata` (
  `language_id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT 0,
  `description_before` text NOT NULL,
  `description_after` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts CMS data';

-- --------------------------------------------------------

--
-- Table structure for table `shoputils_cumulative_discounts_description`
--

CREATE TABLE `shoputils_cumulative_discounts_description` (
  `discount_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts descriptions';

-- --------------------------------------------------------

--
-- Table structure for table `shoputils_cumulative_discounts_to_customer_group`
--

CREATE TABLE `shoputils_cumulative_discounts_to_customer_group` (
  `discount_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts to customer group';

-- --------------------------------------------------------

--
-- Table structure for table `shoputils_cumulative_discounts_to_manufacturer`
--

CREATE TABLE `shoputils_cumulative_discounts_to_manufacturer` (
  `discount_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shoputils_cumulative_discounts_to_store`
--

CREATE TABLE `shoputils_cumulative_discounts_to_store` (
  `discount_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Cumulative discounts to store';

-- --------------------------------------------------------

--
-- Table structure for table `shop_rating`
--

CREATE TABLE `shop_rating` (
  `rate_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `rate_status` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `shop_rate` int(11) DEFAULT NULL,
  `site_rate` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `good` text DEFAULT NULL,
  `bad` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_rating_answers`
--

CREATE TABLE `shop_rating_answers` (
  `id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `comment` text NOT NULL,
  `notified` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_rating_custom_types`
--

CREATE TABLE `shop_rating_custom_types` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_rating_custom_values`
--

CREATE TABLE `shop_rating_custom_values` (
  `id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `custom_value` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_rating_description`
--

CREATE TABLE `shop_rating_description` (
  `rate_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `comment` mediumtext NOT NULL,
  `good` mediumtext NOT NULL,
  `bad` mediumtext NOT NULL,
  `answer` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `short_url_alias`
--

CREATE TABLE `short_url_alias` (
  `url_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `alias` varchar(1024) NOT NULL,
  `used` int(11) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simple_cart`
--

CREATE TABLE `simple_cart` (
  `simple_cart_id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `email` varchar(96) DEFAULT NULL,
  `firstname` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `products` text DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `reminder` int(11) NOT NULL DEFAULT 0,
  `reminder_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simple_custom_data`
--

CREATE TABLE `simple_custom_data` (
  `object_type` tinyint(4) NOT NULL,
  `object_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_log`
--

CREATE TABLE `sms_log` (
  `id` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date_send` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `socnetauth2_customer2account`
--

CREATE TABLE `socnetauth2_customer2account` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(100) NOT NULL,
  `identity` varchar(300) NOT NULL,
  `link` varchar(300) NOT NULL,
  `provider` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `socnetauth2_precode`
--

CREATE TABLE `socnetauth2_precode` (
  `id` int(11) NOT NULL,
  `identity` varchar(300) NOT NULL,
  `code` varchar(300) NOT NULL,
  `cdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `socnetauth2_records`
--

CREATE TABLE `socnetauth2_records` (
  `id` int(11) NOT NULL,
  `state` varchar(100) NOT NULL,
  `redirect` varchar(300) NOT NULL,
  `cdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `special_attribute`
--

CREATE TABLE `special_attribute` (
  `special_attribute_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_group_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_name` varchar(100) NOT NULL DEFAULT '',
  `special_attribute_value` varchar(2000) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `special_attribute_group`
--

CREATE TABLE `special_attribute_group` (
  `special_attribute_group_id` int(10) UNSIGNED NOT NULL,
  `special_attribute_group_name` varchar(100) NOT NULL DEFAULT '',
  `special_attribute_group_description` varchar(4000) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sphinx_suggestions`
--

CREATE TABLE `sphinx_suggestions` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `trigrams` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `freq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks_dynamics`
--

CREATE TABLE `stocks_dynamics` (
  `stock_dynamics_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `warehouse_identifier` varchar(30) NOT NULL,
  `p_count` int(11) NOT NULL,
  `q_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_status`
--

CREATE TABLE `stock_status` (
  `stock_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ssl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribe`
--

CREATE TABLE `subscribe` (
  `subscribe_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribe_auth_description`
--

CREATE TABLE `subscribe_auth_description` (
  `subscribe_auth_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `subscribe_authorization` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribe_email_description`
--

CREATE TABLE `subscribe_email_description` (
  `subscribe_desc_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `subscribe_descriptions` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superstat_viewed`
--

CREATE TABLE `superstat_viewed` (
  `entity_type` enum('p','c','m') NOT NULL,
  `entity_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `times` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

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
  `amzn_coefficient` int(11) NOT NULL,
  `amazon_seller_id` varchar(255) NOT NULL,
  `store_link` varchar(512) NOT NULL,
  `business_name` varchar(512) NOT NULL,
  `registration_number` varchar(255) NOT NULL,
  `vat_number` varchar(255) NOT NULL,
  `business_type` varchar(255) NOT NULL,
  `about_this_seller` longtext NOT NULL,
  `detailed_information` longtext NOT NULL,
  `telephone` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_native` tinyint(1) NOT NULL DEFAULT 0,
  `rating50` int(11) DEFAULT NULL,
  `ratings_total` int(11) DEFAULT NULL,
  `positive_ratings100` int(11) DEFAULT NULL,
  `path_to_feed` varchar(1024) DEFAULT NULL,
  `rrp_in_feed` tinyint(1) NOT NULL DEFAULT 0,
  `language_in_feed` varchar(3) NOT NULL,
  `sync_field` varchar(32) NOT NULL,
  `parser` varchar(64) DEFAULT NULL,
  `parser_status` tinyint(1) NOT NULL DEFAULT 0,
  `admin_status` tinyint(1) NOT NULL DEFAULT 0,
  `stock` tinyint(1) NOT NULL DEFAULT 0,
  `prices` tinyint(1) NOT NULL DEFAULT 0,
  `auto_enable` tinyint(1) NOT NULL DEFAULT 0,
  `skip_no_category` int(11) NOT NULL DEFAULT 0,
  `same_as_warehouse` tinyint(1) NOT NULL DEFAULT 0,
  `currency` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_attributes`
--

CREATE TABLE `supplier_attributes` (
  `supplier_attribute_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_attribute` varchar(512) NOT NULL,
  `attribute_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_categories`
--

CREATE TABLE `supplier_categories` (
  `supplier_category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_infeed_id` varchar(512) NOT NULL,
  `supplier_infeed_parent` varchar(512) NOT NULL,
  `supplier_category` varchar(1024) NOT NULL,
  `supplier_category_full` varchar(2048) NOT NULL,
  `category_id` int(11) NOT NULL,
  `products` tinyint(1) NOT NULL DEFAULT 0,
  `stocks` tinyint(1) NOT NULL DEFAULT 0,
  `prices` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `product_supplier_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_product_id` varchar(512) NOT NULL,
  `sku` varchar(256) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_special` decimal(15,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `raw` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_class`
--

CREATE TABLE `tax_class` (
  `tax_class_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_rate`
--

CREATE TABLE `tax_rate` (
  `tax_rate_id` int(11) NOT NULL,
  `geo_zone_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(32) NOT NULL,
  `rate` decimal(15,4) NOT NULL DEFAULT 0.0000,
  `type` char(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_rate_to_customer_group`
--

CREATE TABLE `tax_rate_to_customer_group` (
  `tax_rate_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_rule`
--

CREATE TABLE `tax_rule` (
  `tax_rule_id` int(11) NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `tax_rate_id` int(11) NOT NULL,
  `based` varchar(10) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_chats`
--

CREATE TABLE `telegram_chats` (
  `id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user or chat identifier',
  `type` char(10) DEFAULT '' COMMENT 'chat type private groupe or channel',
  `title` char(255) DEFAULT '' COMMENT 'chat title null if case of single chat with the bot',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date creation',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_messages`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_users`
--

CREATE TABLE `telegram_users` (
  `id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user identifier',
  `first_name` char(255) NOT NULL DEFAULT '' COMMENT 'User first name',
  `last_name` char(255) DEFAULT '' COMMENT 'User last name',
  `username` char(255) DEFAULT '' COMMENT 'User username',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date creation',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_users_chats`
--

CREATE TABLE `telegram_users_chats` (
  `user_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user identifier',
  `chat_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Unique user or chat identifier'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_sort`
--

CREATE TABLE `ticket_sort` (
  `ticket_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracker`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translate_stats`
--

CREATE TABLE `translate_stats` (
  `time` datetime NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trigger_history`
--

CREATE TABLE `trigger_history` (
  `trigger_history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `actiontemplate_id` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `url_alias`
--

CREATE TABLE `url_alias` (
  `url_alias_id` int(11) NOT NULL,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `url_alias_cached`
--

CREATE TABLE `url_alias_cached` (
  `store_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `route` varchar(255) NOT NULL,
  `args` varchar(255) NOT NULL,
  `checksum` varchar(64) NOT NULL,
  `url` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

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
  `extended_stats` tinyint(1) NOT NULL DEFAULT 0,
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
  `do_transactions` tinyint(1) NOT NULL,
  `dev_template` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_content`
--

CREATE TABLE `user_content` (
  `user_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `date` date NOT NULL,
  `action` varchar(10) NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `entity_id` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `user_group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `permission` text NOT NULL,
  `template_prefix` varchar(255) NOT NULL,
  `alert_namespace` varchar(25) NOT NULL,
  `ticket` tinyint(1) NOT NULL,
  `sip_queue` varchar(4) NOT NULL,
  `bitrix_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_group_to_store`
--

CREATE TABLE `user_group_to_store` (
  `user_group_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_worktime`
--

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
  `edit_csi_count` int(11) NOT NULL,
  `customer_manual_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_history`
--

CREATE TABLE `voucher_history` (
  `voucher_history_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_theme`
--

CREATE TABLE `voucher_theme` (
  `voucher_theme_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_theme_description`
--

CREATE TABLE `voucher_theme_description` (
  `voucher_theme_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wayforpay_orders`
--

CREATE TABLE `wayforpay_orders` (
  `wayforpay_order_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `wayforpay_id` varchar(64) NOT NULL,
  `status` varchar(256) DEFAULT NULL,
  `callback` longtext DEFAULT NULL,
  `full_info` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wc_continents`
--

CREATE TABLE `wc_continents` (
  `code` char(2) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `geonameId` char(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wc_countries`
--

CREATE TABLE `wc_countries` (
  `code` char(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `iso3` char(3) NOT NULL,
  `number` smallint(3) UNSIGNED ZEROFILL NOT NULL,
  `continent_code` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weight_class`
--

CREATE TABLE `weight_class` (
  `weight_class_id` int(11) NOT NULL,
  `value` decimal(15,8) NOT NULL DEFAULT 0.00000000,
  `system_key` varchar(100) NOT NULL,
  `amazon_key` varchar(100) NOT NULL,
  `variants` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weight_class_description`
--

CREATE TABLE `weight_class_description` (
  `weight_class_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `unit` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yandex_feeds`
--

CREATE TABLE `yandex_feeds` (
  `store_id` int(11) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `entity_type` varchar(1) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yandex_queue`
--

CREATE TABLE `yandex_queue` (
  `order_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL,
  `substatus` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yandex_stock_queue`
--

CREATE TABLE `yandex_stock_queue` (
  `yam_product_id` varchar(32) NOT NULL,
  `stock` int(11) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zone`
--

CREATE TABLE `zone` (
  `zone_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `code` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zone_to_geo_zone`
--

CREATE TABLE `zone_to_geo_zone` (
  `zone_to_geo_zone_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL DEFAULT 0,
  `geo_zone_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`actions_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `deletenotinstock` (`deletenotinstock`),
  ADD KEY `only_in_stock` (`only_in_stock`),
  ADD KEY `display_all_active` (`display_all_active`);

--
-- Indexes for table `actions_description`
--
ALTER TABLE `actions_description`
  ADD PRIMARY KEY (`actions_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `title` (`title`),
  ADD KEY `caption` (`caption`);

--
-- Indexes for table `actions_to_category`
--
ALTER TABLE `actions_to_category`
  ADD KEY `action_id` (`actions_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `actions_to_category_in`
--
ALTER TABLE `actions_to_category_in`
  ADD KEY `actions_id` (`actions_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `actions_to_layout`
--
ALTER TABLE `actions_to_layout`
  ADD PRIMARY KEY (`actions_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Indexes for table `actions_to_product`
--
ALTER TABLE `actions_to_product`
  ADD KEY `actions_id` (`actions_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `actions_to_store`
--
ALTER TABLE `actions_to_store`
  ADD PRIMARY KEY (`actions_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `actiontemplate`
--
ALTER TABLE `actiontemplate`
  ADD PRIMARY KEY (`actiontemplate_id`),
  ADD KEY `use_for_manual` (`use_for_manual`),
  ADD KEY `use_for_forgotten` (`use_for_forgotten`);

--
-- Indexes for table `actiontemplate_description`
--
ALTER TABLE `actiontemplate_description`
  ADD PRIMARY KEY (`actiontemplate_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `address`
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
-- Indexes for table `address_simple_fields`
--
ALTER TABLE `address_simple_fields`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `adminlog`
--
ALTER TABLE `adminlog`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `advanced_coupon`
--
ALTER TABLE `advanced_coupon`
  ADD PRIMARY KEY (`advanced_coupon_id`),
  ADD UNIQUE KEY `name` (`code`);

--
-- Indexes for table `advanced_coupon_history`
--
ALTER TABLE `advanced_coupon_history`
  ADD PRIMARY KEY (`advanced_coupon_history_id`),
  ADD KEY `advanced_coupon_id` (`advanced_coupon_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `affiliate`
--
ALTER TABLE `affiliate`
  ADD PRIMARY KEY (`affiliate_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `affiliate_statistics`
--
ALTER TABLE `affiliate_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `affiliate_id` (`affiliate_id`);

--
-- Indexes for table `affiliate_transaction`
--
ALTER TABLE `affiliate_transaction`
  ADD PRIMARY KEY (`affiliate_transaction_id`),
  ADD KEY `affiliate_id` (`affiliate_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Indexes for table `alertlog`
--
ALTER TABLE `alertlog`
  ADD PRIMARY KEY (`alertlog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `alsoviewed`
--
ALTER TABLE `alsoviewed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `low_2` (`low`,`high`),
  ADD KEY `low` (`low`),
  ADD KEY `high` (`high`),
  ADD KEY `number` (`number`);

--
-- Indexes for table `amazon_orders`
--
ALTER TABLE `amazon_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `amazon_id` (`amazon_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `amazon_orders_blobs`
--
ALTER TABLE `amazon_orders_blobs`
  ADD UNIQUE KEY `amazon_id` (`amazon_id`) USING BTREE;

--
-- Indexes for table `amazon_orders_products`
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
-- Indexes for table `amazon_zipcodes`
--
ALTER TABLE `amazon_zipcodes`
  ADD PRIMARY KEY (`zipcode_id`),
  ADD UNIQUE KEY `zipcode_2` (`zipcode`),
  ADD KEY `error_count` (`error_count`),
  ADD KEY `request_count` (`request_count`),
  ADD KEY `dropped` (`dropped`);

--
-- Indexes for table `amzn_add_queue`
--
ALTER TABLE `amzn_add_queue`
  ADD UNIQUE KEY `asin` (`asin`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `amzn_add_variants_queue`
--
ALTER TABLE `amzn_add_variants_queue`
  ADD UNIQUE KEY `asin` (`asin`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `amzn_product_queue`
--
ALTER TABLE `amzn_product_queue`
  ADD UNIQUE KEY `asin` (`asin`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `apri`
--
ALTER TABLE `apri`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `attribute`
--
ALTER TABLE `attribute`
  ADD PRIMARY KEY (`attribute_id`),
  ADD KEY `attribute_group_id` (`attribute_group_id`),
  ADD KEY `dimension_type` (`dimension_type`);

--
-- Indexes for table `attributes_category`
--
ALTER TABLE `attributes_category`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `attributes_similar_category`
--
ALTER TABLE `attributes_similar_category`
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `attribute_description`
--
ALTER TABLE `attribute_description`
  ADD PRIMARY KEY (`attribute_id`,`language_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `attribute_group`
--
ALTER TABLE `attribute_group`
  ADD PRIMARY KEY (`attribute_group_id`);

--
-- Indexes for table `attribute_group_description`
--
ALTER TABLE `attribute_group_description`
  ADD PRIMARY KEY (`attribute_group_id`,`language_id`);

--
-- Indexes for table `attribute_group_tooltip`
--
ALTER TABLE `attribute_group_tooltip`
  ADD PRIMARY KEY (`attribute_group_id`,`language_id`);

--
-- Indexes for table `attribute_tooltip`
--
ALTER TABLE `attribute_tooltip`
  ADD PRIMARY KEY (`attribute_id`,`language_id`);

--
-- Indexes for table `attribute_value_image`
--
ALTER TABLE `attribute_value_image`
  ADD PRIMARY KEY (`attribute_value_image`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banner_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `banner_image`
--
ALTER TABLE `banner_image`
  ADD PRIMARY KEY (`banner_image_id`),
  ADD KEY `banner_id` (`banner_id`);

--
-- Indexes for table `banner_image_description`
--
ALTER TABLE `banner_image_description`
  ADD PRIMARY KEY (`banner_image_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `banner_id` (`banner_id`);

--
-- Indexes for table `callback`
--
ALTER TABLE `callback`
  ADD PRIMARY KEY (`call_id`),
  ADD KEY `sip_queue` (`sip_queue`),
  ADD KEY `is_cheaper` (`is_cheaper`),
  ADD KEY `is_missed` (`is_missed`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
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
  ADD KEY `exclude_from_intersections` (`exclude_from_intersections`),
  ADD KEY `amzn_synced` (`amazon_synced`),
  ADD KEY `homepage` (`homepage`),
  ADD KEY `popular` (`popular`),
  ADD KEY `viewed` (`viewed`),
  ADD KEY `bought` (`bought_for_month`),
  ADD KEY `final` (`final`),
  ADD KEY `special` (`special`),
  ADD KEY `hotline_enable` (`hotline_enable`),
  ADD KEY `need_reprice` (`need_reprice`);

--
-- Indexes for table `category_amazon_bestseller_tree`
--
ALTER TABLE `category_amazon_bestseller_tree`
  ADD UNIQUE KEY `category_id` (`category_id`) USING BTREE,
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `final_category` (`final_category`),
  ADD KEY `name` (`name`),
  ADD KEY `full_name` (`full_name`),
  ADD KEY `full_name_native` (`full_name_native`),
  ADD KEY `name_native` (`name_native`);

--
-- Indexes for table `category_amazon_tree`
--
ALTER TABLE `category_amazon_tree`
  ADD UNIQUE KEY `category_id` (`category_id`) USING BTREE,
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `final_category` (`final_category`),
  ADD KEY `name` (`name`),
  ADD KEY `full_name` (`full_name`);

--
-- Indexes for table `category_description`
--
ALTER TABLE `category_description`
  ADD PRIMARY KEY (`category_id`,`language_id`),
  ADD KEY `name` (`name`);
ALTER TABLE `category_description` ADD FULLTEXT KEY `FULLTEXT_name` (`name`);

--
-- Indexes for table `category_filter`
--
ALTER TABLE `category_filter`
  ADD PRIMARY KEY (`category_id`,`filter_id`);

--
-- Indexes for table `category_hotline_tree`
--
ALTER TABLE `category_hotline_tree`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `name` (`name`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `full_name` (`full_name`),
  ADD KEY `full_name_2` (`full_name`);

--
-- Indexes for table `category_menu_content`
--
ALTER TABLE `category_menu_content`
  ADD PRIMARY KEY (`category_menu_content_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `sort_order` (`sort_order`),
  ADD KEY `category_id_2` (`category_id`,`language_id`);

--
-- Indexes for table `category_overprice_rules`
--
ALTER TABLE `category_overprice_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `category_path`
--
ALTER TABLE `category_path`
  ADD PRIMARY KEY (`category_id`,`path_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `path_id` (`path_id`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `category_product_count`
--
ALTER TABLE `category_product_count`
  ADD KEY `store_id` (`store_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `category_psm_template`
--
ALTER TABLE `category_psm_template`
  ADD PRIMARY KEY (`category_psm_template_id`);

--
-- Indexes for table `category_related`
--
ALTER TABLE `category_related`
  ADD UNIQUE KEY `category_id_2` (`category_id`,`related_category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `category_review`
--
ALTER TABLE `category_review`
  ADD PRIMARY KEY (`categoryreview_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `category_search_words`
--
ALTER TABLE `category_search_words`
  ADD PRIMARY KEY (`category_search_word_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `category_word_type` (`category_word_type`),
  ADD KEY `category_word_last_search` (`category_word_last_search`),
  ADD KEY `category_word_total_pages` (`category_word_total_pages`),
  ADD KEY `category_word_pages_parsed` (`category_word_pages_parsed`),
  ADD KEY `category_search_auto` (`category_search_auto`),
  ADD KEY `category_word_user_id` (`category_word_user_id`);

--
-- Indexes for table `category_to_actions`
--
ALTER TABLE `category_to_actions`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `actions_id` (`actions_id`);

--
-- Indexes for table `category_to_layout`
--
ALTER TABLE `category_to_layout`
  ADD PRIMARY KEY (`category_id`,`store_id`);

--
-- Indexes for table `category_to_store`
--
ALTER TABLE `category_to_store`
  ADD PRIMARY KEY (`category_id`,`store_id`);

--
-- Indexes for table `category_yam_tree`
--
ALTER TABLE `category_yam_tree`
  ADD KEY `name` (`name`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `full_name` (`full_name`);

--
-- Indexes for table `cdek_cities`
--
ALTER TABLE `cdek_cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `region_code` (`region_code`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city` (`city`),
  ADD KEY `country` (`country`),
  ADD KEY `region` (`region`),
  ADD KEY `WarehouseCount` (`WarehouseCount`),
  ADD KEY `dadata_BELTWAY_HIT` (`dadata_BELTWAY_HIT`),
  ADD KEY `dadata_BELTWAY_DISTANCE` (`dadata_BELTWAY_DISTANCE`),
  ADD KEY `parsed` (`parsed`);

--
-- Indexes for table `cdek_city`
--
ALTER TABLE `cdek_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cdek_deliverypoints`
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
-- Indexes for table `cdek_dispatch`
--
ALTER TABLE `cdek_dispatch`
  ADD PRIMARY KEY (`dispatch_id`);

--
-- Indexes for table `cdek_order`
--
ALTER TABLE `cdek_order`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `dispatch_id` (`dispatch_id`),
  ADD KEY `dispatch_number` (`dispatch_number`);

--
-- Indexes for table `cdek_order_add_service`
--
ALTER TABLE `cdek_order_add_service`
  ADD PRIMARY KEY (`service_id`,`order_id`);

--
-- Indexes for table `cdek_order_call`
--
ALTER TABLE `cdek_order_call`
  ADD PRIMARY KEY (`call_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `cdek_order_call_history_delay`
--
ALTER TABLE `cdek_order_call_history_delay`
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `cdek_order_call_history_fail`
--
ALTER TABLE `cdek_order_call_history_fail`
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `cdek_order_call_history_good`
--
ALTER TABLE `cdek_order_call_history_good`
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `cdek_order_courier`
--
ALTER TABLE `cdek_order_courier`
  ADD PRIMARY KEY (`courier_id`);

--
-- Indexes for table `cdek_order_delay_history`
--
ALTER TABLE `cdek_order_delay_history`
  ADD KEY `order_id` (`order_id`,`delay_id`);

--
-- Indexes for table `cdek_order_package`
--
ALTER TABLE `cdek_order_package`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `cdek_order_package_item`
--
ALTER TABLE `cdek_order_package_item`
  ADD PRIMARY KEY (`package_item_id`);

--
-- Indexes for table `cdek_order_reason`
--
ALTER TABLE `cdek_order_reason`
  ADD PRIMARY KEY (`reason_id`,`order_id`);

--
-- Indexes for table `cdek_order_schedule`
--
ALTER TABLE `cdek_order_schedule`
  ADD PRIMARY KEY (`attempt_id`);

--
-- Indexes for table `cdek_order_schedule_delay`
--
ALTER TABLE `cdek_order_schedule_delay`
  ADD KEY `order_id` (`order_id`,`attempt_id`);

--
-- Indexes for table `cdek_order_status_history`
--
ALTER TABLE `cdek_order_status_history`
  ADD KEY `order_id` (`order_id`,`status_id`);

--
-- Indexes for table `cdek_zones`
--
ALTER TABLE `cdek_zones`
  ADD PRIMARY KEY (`zone_id`),
  ADD UNIQUE KEY `region_code` (`region_code`) USING BTREE,
  ADD KEY `country_id` (`country_id`),
  ADD KEY `country` (`country`),
  ADD KEY `region` (`region`),
  ADD KEY `country_code` (`country_code`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `collection_description`
--
ALTER TABLE `collection_description`
  ADD UNIQUE KEY `collection_id_2` (`collection_id`,`language_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `collection_image`
--
ALTER TABLE `collection_image`
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `collection_to_store`
--
ALTER TABLE `collection_to_store`
  ADD UNIQUE KEY `collection_id_2` (`collection_id`,`store_id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `competitors`
--
ALTER TABLE `competitors`
  ADD PRIMARY KEY (`competitor_id`);

--
-- Indexes for table `competitor_price`
--
ALTER TABLE `competitor_price`
  ADD PRIMARY KEY (`competitor_price_id`),
  ADD KEY `competitor_id` (`competitor_id`),
  ADD KEY `sku` (`sku`),
  ADD KEY `currency` (`currency`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `competitor_urls`
--
ALTER TABLE `competitor_urls`
  ADD KEY `competitor_id` (`competitor_id`),
  ADD KEY `sku` (`sku`);

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`counter_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `value` (`value`),
  ADD KEY `counter` (`counter`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`),
  ADD KEY `warehouse_identifier` (`warehouse_identifier`);

--
-- Indexes for table `countrybrand`
--
ALTER TABLE `countrybrand`
  ADD PRIMARY KEY (`countrybrand_id`);

--
-- Indexes for table `countrybrand_description`
--
ALTER TABLE `countrybrand_description`
  ADD UNIQUE KEY `countrybrand_id_2` (`countrybrand_id`,`language_id`),
  ADD KEY `countrybrand_id` (`countrybrand_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `countrybrand_image`
--
ALTER TABLE `countrybrand_image`
  ADD KEY `countrybrand_id` (`countrybrand_id`);

--
-- Indexes for table `countrybrand_to_store`
--
ALTER TABLE `countrybrand_to_store`
  ADD UNIQUE KEY `countrybrand_id_2` (`countrybrand_id`,`store_id`),
  ADD KEY `countrybrand_id` (`countrybrand_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `country_to_fias`
--
ALTER TABLE `country_to_fias`
  ADD UNIQUE KEY `country_id` (`country_id`,`fias_id`);

--
-- Indexes for table `coupon`
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
-- Indexes for table `coupon_category`
--
ALTER TABLE `coupon_category`
  ADD PRIMARY KEY (`coupon_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `coupon_collection`
--
ALTER TABLE `coupon_collection`
  ADD PRIMARY KEY (`coupon_id`,`collection_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `coupon_description`
--
ALTER TABLE `coupon_description`
  ADD UNIQUE KEY `coupon_id_2` (`coupon_id`,`language_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `coupon_history`
--
ALTER TABLE `coupon_history`
  ADD PRIMARY KEY (`coupon_history_id`),
  ADD UNIQUE KEY `coupon_id_2` (`coupon_id`,`order_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `coupon_id_3` (`coupon_id`,`customer_id`);

--
-- Indexes for table `coupon_manufacturer`
--
ALTER TABLE `coupon_manufacturer`
  ADD PRIMARY KEY (`coupon_id`,`manufacturer_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`);

--
-- Indexes for table `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD PRIMARY KEY (`coupon_product_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `coupon_random`
--
ALTER TABLE `coupon_random`
  ADD PRIMARY KEY (`coupon_random_id`),
  ADD KEY `coupon_random` (`coupon_random`),
  ADD KEY `coupon_code` (`coupon_code`);

--
-- Indexes for table `coupon_review`
--
ALTER TABLE `coupon_review`
  ADD PRIMARY KEY (`code`),
  ADD KEY `coupon_history_id` (`coupon_history_id`);

--
-- Indexes for table `csvprice_pro`
--
ALTER TABLE `csvprice_pro`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `csvprice_pro_crontab`
--
ALTER TABLE `csvprice_pro_crontab`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `csvprice_pro_images`
--
ALTER TABLE `csvprice_pro_images`
  ADD PRIMARY KEY (`catalog_id`,`image_key`),
  ADD KEY `image_key` (`image_key`);

--
-- Indexes for table `csvprice_pro_profiles`
--
ALTER TABLE `csvprice_pro_profiles`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `customer`
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
  ADD KEY `social_id` (`social_id`),
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
  ADD KEY `viber_news` (`viber_news`),
  ADD KEY `sent_old_alert` (`sent_manual_letter`);

--
-- Indexes for table `customer_ban_ip`
--
ALTER TABLE `customer_ban_ip`
  ADD PRIMARY KEY (`customer_ban_ip_id`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `customer_calls`
--
ALTER TABLE `customer_calls`
  ADD PRIMARY KEY (`customer_call_id`),
  ADD UNIQUE KEY `customer_phone` (`customer_phone`,`date_end`,`internal_pbx_num`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `inbound` (`inbound`),
  ADD KEY `length` (`length`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `customer_emails_blacklist`
--
ALTER TABLE `customer_emails_blacklist`
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `customer_emails_whitelist`
--
ALTER TABLE `customer_emails_whitelist`
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `customer_email_campaigns`
--
ALTER TABLE `customer_email_campaigns`
  ADD PRIMARY KEY (`customer_email_campaigns_id`),
  ADD UNIQUE KEY `customer_id_2` (`customer_id`,`campaign_id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer_email_campaigns_names`
--
ALTER TABLE `customer_email_campaigns_names`
  ADD UNIQUE KEY `email_campaign_mailwizz_id` (`email_campaign_mailwizz_id`);

--
-- Indexes for table `customer_field`
--
ALTER TABLE `customer_field`
  ADD PRIMARY KEY (`customer_id`,`custom_field_id`,`custom_field_value_id`),
  ADD KEY `custom_field_id` (`custom_field_id`),
  ADD KEY `custom_field_value_id` (`custom_field_value_id`);

--
-- Indexes for table `customer_group`
--
ALTER TABLE `customer_group`
  ADD PRIMARY KEY (`customer_group_id`);

--
-- Indexes for table `customer_group_description`
--
ALTER TABLE `customer_group_description`
  ADD PRIMARY KEY (`customer_group_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `customer_history`
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
-- Indexes for table `customer_ip`
--
ALTER TABLE `customer_ip`
  ADD PRIMARY KEY (`customer_ip_id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `customer_id_customer_ip` (`customer_id`,`ip`) USING BTREE;

--
-- Indexes for table `customer_online`
--
ALTER TABLE `customer_online`
  ADD PRIMARY KEY (`ip`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `is_bot` (`is_bot`),
  ADD KEY `is_pwa` (`is_pwa`),
  ADD KEY `is_bot_2` (`is_bot`,`is_pwa`);

--
-- Indexes for table `customer_online_history`
--
ALTER TABLE `customer_online_history`
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `customer_push_ids`
--
ALTER TABLE `customer_push_ids`
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `onesignal_push_id` (`onesignal_push_id`);

--
-- Indexes for table `customer_reward`
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
-- Indexes for table `customer_reward_queue`
--
ALTER TABLE `customer_reward_queue`
  ADD PRIMARY KEY (`customer_reward_queue_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `reason_code` (`reason_code`);

--
-- Indexes for table `customer_search_history`
--
ALTER TABLE `customer_search_history`
  ADD PRIMARY KEY (`customer_history_id`),
  ADD UNIQUE KEY `customer_id_text` (`customer_id`,`text`) USING BTREE,
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `text` (`text`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `customer_segments`
--
ALTER TABLE `customer_segments`
  ADD UNIQUE KEY `customer_id_2` (`customer_id`,`segment_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `segment_id` (`segment_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `customer_simple_fields`
--
ALTER TABLE `customer_simple_fields`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_transaction`
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
-- Indexes for table `customer_viewed`
--
ALTER TABLE `customer_viewed`
  ADD UNIQUE KEY `customer_id_2` (`customer_id`,`type`,`entity_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `type` (`type`),
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `times` (`times`);

--
-- Indexes for table `custom_field`
--
ALTER TABLE `custom_field`
  ADD PRIMARY KEY (`custom_field_id`);

--
-- Indexes for table `custom_field_description`
--
ALTER TABLE `custom_field_description`
  ADD PRIMARY KEY (`custom_field_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `custom_field_to_customer_group`
--
ALTER TABLE `custom_field_to_customer_group`
  ADD PRIMARY KEY (`custom_field_id`,`customer_group_id`),
  ADD KEY `customer_group_id` (`customer_group_id`);

--
-- Indexes for table `custom_field_value`
--
ALTER TABLE `custom_field_value`
  ADD PRIMARY KEY (`custom_field_value_id`),
  ADD KEY `custom_field_id` (`custom_field_id`);

--
-- Indexes for table `custom_field_value_description`
--
ALTER TABLE `custom_field_value_description`
  ADD PRIMARY KEY (`custom_field_value_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `custom_field_id` (`custom_field_id`);

--
-- Indexes for table `custom_url_404`
--
ALTER TABLE `custom_url_404`
  ADD PRIMARY KEY (`custom_url_404_id`);

--
-- Indexes for table `deleted_asins`
--
ALTER TABLE `deleted_asins`
  ADD PRIMARY KEY (`asin`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `name` (`name`(768));

--
-- Indexes for table `direct_timezones`
--
ALTER TABLE `direct_timezones`
  ADD PRIMARY KEY (`geomd5`);

--
-- Indexes for table `download`
--
ALTER TABLE `download`
  ADD PRIMARY KEY (`download_id`);

--
-- Indexes for table `download_description`
--
ALTER TABLE `download_description`
  ADD PRIMARY KEY (`download_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `emailmarketing_logs`
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
-- Indexes for table `emailtemplate`
--
ALTER TABLE `emailtemplate`
  ADD PRIMARY KEY (`emailtemplate_id`),
  ADD KEY `KEY` (`emailtemplate_key`);

--
-- Indexes for table `emailtemplate_config`
--
ALTER TABLE `emailtemplate_config`
  ADD PRIMARY KEY (`emailtemplate_config_id`);

--
-- Indexes for table `emailtemplate_description`
--
ALTER TABLE `emailtemplate_description`
  ADD PRIMARY KEY (`emailtemplate_id`,`language_id`);

--
-- Indexes for table `emailtemplate_logs`
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
-- Indexes for table `emailtemplate_shortcode`
--
ALTER TABLE `emailtemplate_shortcode`
  ADD PRIMARY KEY (`emailtemplate_shortcode_id`),
  ADD KEY `emailtemplate_id` (`emailtemplate_id`);

--
-- Indexes for table `entity_reward`
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
-- Indexes for table `excluded_asins`
--
ALTER TABLE `excluded_asins`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `text` (`text`);

--
-- Indexes for table `extension`
--
ALTER TABLE `extension`
  ADD PRIMARY KEY (`extension_id`),
  ADD KEY `type` (`type`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `facategory`
--
ALTER TABLE `facategory`
  ADD PRIMARY KEY (`facategory_id`);

--
-- Indexes for table `facategory_to_faproduct`
--
ALTER TABLE `facategory_to_faproduct`
  ADD PRIMARY KEY (`facategory_id`,`product_id`);

--
-- Indexes for table `faproduct_to_facategory`
--
ALTER TABLE `faproduct_to_facategory`
  ADD PRIMARY KEY (`product_id`,`facategory_id`);

--
-- Indexes for table `faq_category`
--
ALTER TABLE `faq_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `faq_category_description`
--
ALTER TABLE `faq_category_description`
  ADD PRIMARY KEY (`category_id`,`language_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `faq_question`
--
ALTER TABLE `faq_question`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `feed_queue`
--
ALTER TABLE `feed_queue`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`filter_id`),
  ADD KEY `filter_group_id` (`filter_group_id`);

--
-- Indexes for table `filterpro_seo`
--
ALTER TABLE `filterpro_seo`
  ADD PRIMARY KEY (`url`);

--
-- Indexes for table `filter_description`
--
ALTER TABLE `filter_description`
  ADD PRIMARY KEY (`filter_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `filter_group_id` (`filter_group_id`);

--
-- Indexes for table `filter_group`
--
ALTER TABLE `filter_group`
  ADD PRIMARY KEY (`filter_group_id`);

--
-- Indexes for table `filter_group_description`
--
ALTER TABLE `filter_group_description`
  ADD PRIMARY KEY (`filter_group_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `geo`
--
ALTER TABLE `geo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `geoname_alternatename`
--
ALTER TABLE `geoname_alternatename`
  ADD PRIMARY KEY (`alternatenameId`),
  ADD KEY `geonameid` (`geonameid`),
  ADD KEY `isoLanguage` (`isoLanguage`),
  ADD KEY `alternateName` (`alternateName`);

--
-- Indexes for table `geoname_geoname`
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
-- Indexes for table `geo_ip`
--
ALTER TABLE `geo_ip`
  ADD PRIMARY KEY (`start`,`end`);

--
-- Indexes for table `geo_zone`
--
ALTER TABLE `geo_zone`
  ADD PRIMARY KEY (`geo_zone_id`);

--
-- Indexes for table `google_base_category`
--
ALTER TABLE `google_base_category`
  ADD PRIMARY KEY (`google_base_category_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `hj_any_feed_feeds`
--
ALTER TABLE `hj_any_feed_feeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `imagemaps`
--
ALTER TABLE `imagemaps`
  ADD PRIMARY KEY (`imagemap_id`),
  ADD KEY `module_code` (`module_code`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`information_id`);

--
-- Indexes for table `information_attribute`
--
ALTER TABLE `information_attribute`
  ADD PRIMARY KEY (`information_attribute_id`);

--
-- Indexes for table `information_attribute_description`
--
ALTER TABLE `information_attribute_description`
  ADD PRIMARY KEY (`information_attribute_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `information_attribute_to_layout`
--
ALTER TABLE `information_attribute_to_layout`
  ADD PRIMARY KEY (`information_attribute_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Indexes for table `information_attribute_to_store`
--
ALTER TABLE `information_attribute_to_store`
  ADD PRIMARY KEY (`information_attribute_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `information_description`
--
ALTER TABLE `information_description`
  ADD PRIMARY KEY (`information_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `information_to_layout`
--
ALTER TABLE `information_to_layout`
  ADD PRIMARY KEY (`information_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Indexes for table `information_to_store`
--
ALTER TABLE `information_to_store`
  ADD PRIMARY KEY (`information_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `interplusplus`
--
ALTER TABLE `interplusplus`
  ADD PRIMARY KEY (`inter_id`);

--
-- Indexes for table `justin_cities`
--
ALTER TABLE `justin_cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `RegionUuid` (`RegionUuid`),
  ADD KEY `Descr` (`Descr`),
  ADD KEY `DescrRU` (`DescrRU`),
  ADD KEY `WarehouseCount` (`WarehouseCount`);

--
-- Indexes for table `justin_city_regions`
--
ALTER TABLE `justin_city_regions`
  ADD PRIMARY KEY (`region_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `CityUuid` (`CityUuid`);

--
-- Indexes for table `justin_streets`
--
ALTER TABLE `justin_streets`
  ADD PRIMARY KEY (`street_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `CityUuid` (`CityUuid`);

--
-- Indexes for table `justin_warehouses`
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
-- Indexes for table `justin_zones`
--
ALTER TABLE `justin_zones`
  ADD PRIMARY KEY (`zone_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`);

--
-- Indexes for table `justin_zone_regions`
--
ALTER TABLE `justin_zone_regions`
  ADD PRIMARY KEY (`region_id`),
  ADD UNIQUE KEY `Uuid` (`Uuid`),
  ADD KEY `ZoneUuid` (`ZoneUuid`);

--
-- Indexes for table `keyworder`
--
ALTER TABLE `keyworder`
  ADD PRIMARY KEY (`keyworder_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `keyworder_description`
--
ALTER TABLE `keyworder_description`
  ADD PRIMARY KEY (`keyworder_id`,`language_id`),
  ADD KEY `keyworder_status` (`keyworder_status`),
  ADD KEY `category_status` (`category_status`);

--
-- Indexes for table `landingpage`
--
ALTER TABLE `landingpage`
  ADD PRIMARY KEY (`landingpage_id`);

--
-- Indexes for table `landingpage_description`
--
ALTER TABLE `landingpage_description`
  ADD PRIMARY KEY (`landingpage_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `landingpage_to_layout`
--
ALTER TABLE `landingpage_to_layout`
  ADD PRIMARY KEY (`landingpage_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Indexes for table `landingpage_to_store`
--
ALTER TABLE `landingpage_to_store`
  ADD PRIMARY KEY (`landingpage_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`),
  ADD KEY `name` (`name`),
  ADD KEY `urlcode` (`urlcode`),
  ADD KEY `code` (`code`),
  ADD KEY `status` (`status`),
  ADD KEY `front` (`front`);

--
-- Indexes for table `layout`
--
ALTER TABLE `layout`
  ADD PRIMARY KEY (`layout_id`);

--
-- Indexes for table `layout_route`
--
ALTER TABLE `layout_route`
  ADD PRIMARY KEY (`layout_route_id`),
  ADD KEY `layout_id` (`layout_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `route` (`route`),
  ADD KEY `store_id_route` (`store_id`,`route`) USING BTREE,
  ADD KEY `layout_id_store_id` (`layout_id`,`store_id`) USING BTREE;

--
-- Indexes for table `legalperson`
--
ALTER TABLE `legalperson`
  ADD PRIMARY KEY (`legalperson_id`),
  ADD KEY `legalperson_country_id` (`legalperson_country_id`);

--
-- Indexes for table `length_class`
--
ALTER TABLE `length_class`
  ADD PRIMARY KEY (`length_class_id`),
  ADD KEY `system_key` (`system_key`),
  ADD KEY `amazon_key` (`amazon_key`);

--
-- Indexes for table `length_class_description`
--
ALTER TABLE `length_class_description`
  ADD PRIMARY KEY (`length_class_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `local_supplier_products`
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
-- Indexes for table `mailwizz_queue`
--
ALTER TABLE `mailwizz_queue`
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `manager_kpi`
--
ALTER TABLE `manager_kpi`
  ADD UNIQUE KEY `manager_id` (`manager_id`,`date_added`),
  ADD KEY `manager_id_2` (`manager_id`);

--
-- Indexes for table `manager_order_status_dynamics`
--
ALTER TABLE `manager_order_status_dynamics`
  ADD UNIQUE KEY `manager_id_2` (`manager_id`,`order_status_id`,`date`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `order_status_id` (`order_status_id`);

--
-- Indexes for table `manager_order_status_dynamics2`
--
ALTER TABLE `manager_order_status_dynamics2`
  ADD UNIQUE KEY `manager_id_2` (`manager_id`,`order_status_id`,`date`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `order_status_id` (`order_status_id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`manufacturer_id`),
  ADD KEY `priceva_enable` (`priceva_enable`),
  ADD KEY `priceva_feed` (`priceva_feed`),
  ADD KEY `menu_brand` (`menu_brand`),
  ADD KEY `homepage` (`homepage`),
  ADD KEY `hotline_enable` (`hotline_enable`),
  ADD KEY `products_total` (`products_total`),
  ADD KEY `product_total_enabled` (`products_total_enabled`);

--
-- Indexes for table `manufacturer_description`
--
ALTER TABLE `manufacturer_description`
  ADD PRIMARY KEY (`manufacturer_id`,`language_id`),
  ADD KEY `location` (`location`);

--
-- Indexes for table `manufacturer_page_content`
--
ALTER TABLE `manufacturer_page_content`
  ADD PRIMARY KEY (`manufacturer_page_content_id`);

--
-- Indexes for table `manufacturer_to_layout`
--
ALTER TABLE `manufacturer_to_layout`
  ADD PRIMARY KEY (`manufacturer_id`,`store_id`);

--
-- Indexes for table `manufacturer_to_store`
--
ALTER TABLE `manufacturer_to_store`
  ADD PRIMARY KEY (`manufacturer_id`,`store_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `maxmind_geo_country`
--
ALTER TABLE `maxmind_geo_country`
  ADD PRIMARY KEY (`start`,`end`);

--
-- Indexes for table `mono_orders`
--
ALTER TABLE `mono_orders`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `OrderId` (`OrderId`),
  ADD KEY `CheckoutOrderId` (`CheckoutOrderId`);

--
-- Indexes for table `multi_pay_payment`
--
ALTER TABLE `multi_pay_payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `operation_id` (`operation_id`),
  ADD KEY `datetime` (`datetime`);

--
-- Indexes for table `nauthor`
--
ALTER TABLE `nauthor`
  ADD PRIMARY KEY (`nauthor_id`);

--
-- Indexes for table `nauthor_description`
--
ALTER TABLE `nauthor_description`
  ADD PRIMARY KEY (`nauthor_id`,`language_id`);

--
-- Indexes for table `ncategory`
--
ALTER TABLE `ncategory`
  ADD PRIMARY KEY (`ncategory_id`);

--
-- Indexes for table `ncategory_description`
--
ALTER TABLE `ncategory_description`
  ADD PRIMARY KEY (`ncategory_id`,`language_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `ncategory_to_layout`
--
ALTER TABLE `ncategory_to_layout`
  ADD PRIMARY KEY (`ncategory_id`,`store_id`);

--
-- Indexes for table `ncategory_to_store`
--
ALTER TABLE `ncategory_to_store`
  ADD PRIMARY KEY (`ncategory_id`,`store_id`);

--
-- Indexes for table `ncomments`
--
ALTER TABLE `ncomments`
  ADD PRIMARY KEY (`ncomment_id`),
  ADD KEY `news_id` (`news_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `news_description`
--
ALTER TABLE `news_description`
  ADD PRIMARY KEY (`news_id`,`language_id`);

--
-- Indexes for table `news_gallery`
--
ALTER TABLE `news_gallery`
  ADD PRIMARY KEY (`news_image_id`);

--
-- Indexes for table `news_related`
--
ALTER TABLE `news_related`
  ADD PRIMARY KEY (`news_id`,`product_id`);

--
-- Indexes for table `news_to_layout`
--
ALTER TABLE `news_to_layout`
  ADD PRIMARY KEY (`news_id`,`store_id`);

--
-- Indexes for table `news_to_ncategory`
--
ALTER TABLE `news_to_ncategory`
  ADD PRIMARY KEY (`news_id`,`ncategory_id`);

--
-- Indexes for table `news_to_store`
--
ALTER TABLE `news_to_store`
  ADD PRIMARY KEY (`news_id`,`store_id`);

--
-- Indexes for table `news_video`
--
ALTER TABLE `news_video`
  ADD PRIMARY KEY (`news_video_id`);

--
-- Indexes for table `novaposhta_cities`
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
-- Indexes for table `novaposhta_cities_ww`
--
ALTER TABLE `novaposhta_cities_ww`
  ADD PRIMARY KEY (`CityID`),
  ADD UNIQUE KEY `Ref` (`Ref`),
  ADD KEY `Area` (`Area`),
  ADD KEY `WarehouseCount` (`WarehouseCount`),
  ADD KEY `Description` (`Description`),
  ADD KEY `DescriptionRu` (`DescriptionRu`);

--
-- Indexes for table `novaposhta_streets`
--
ALTER TABLE `novaposhta_streets`
  ADD PRIMARY KEY (`StreetID`),
  ADD UNIQUE KEY `Ref_2` (`Ref`),
  ADD KEY `CityRef` (`CityRef`);

--
-- Indexes for table `novaposhta_warehouses`
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
-- Indexes for table `novaposhta_zones`
--
ALTER TABLE `novaposhta_zones`
  ADD PRIMARY KEY (`ZoneID`),
  ADD UNIQUE KEY `Ref` (`Ref`) USING BTREE,
  ADD KEY `AreasCenter` (`AreasCenter`);

--
-- Indexes for table `ocfilter_option`
--
ALTER TABLE `ocfilter_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `keyword` (`keyword`),
  ADD KEY `status` (`status`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `ocfilter_option_description`
--
ALTER TABLE `ocfilter_option_description`
  ADD PRIMARY KEY (`option_id`,`language_id`);

--
-- Indexes for table `ocfilter_option_to_category`
--
ALTER TABLE `ocfilter_option_to_category`
  ADD PRIMARY KEY (`category_id`,`option_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `ocfilter_option_to_store`
--
ALTER TABLE `ocfilter_option_to_store`
  ADD PRIMARY KEY (`store_id`,`option_id`);

--
-- Indexes for table `ocfilter_option_value`
--
ALTER TABLE `ocfilter_option_value`
  ADD PRIMARY KEY (`value_id`,`option_id`),
  ADD KEY `keyword` (`keyword`);

--
-- Indexes for table `ocfilter_option_value_description`
--
ALTER TABLE `ocfilter_option_value_description`
  ADD PRIMARY KEY (`value_id`,`language_id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `ocfilter_option_value_to_product`
--
ALTER TABLE `ocfilter_option_value_to_product`
  ADD PRIMARY KEY (`ocfilter_option_value_to_product_id`),
  ADD KEY `slide_value_min_slide_value_max` (`slide_value_min`,`slide_value_max`),
  ADD KEY `option_id_value_id` (`option_id`,`value_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `ocfilter_option_value_to_product_description`
--
ALTER TABLE `ocfilter_option_value_to_product_description`
  ADD PRIMARY KEY (`product_id`,`value_id`,`option_id`,`language_id`);

--
-- Indexes for table `ocfilter_page`
--
ALTER TABLE `ocfilter_page`
  ADD PRIMARY KEY (`ocfilter_page_id`),
  ADD KEY `category_id_ocfilter_params` (`category_id`,`ocfilter_params`),
  ADD KEY `keyword` (`keyword`);

--
-- Indexes for table `oc_feedback`
--
ALTER TABLE `oc_feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `oc_yandex_category`
--
ALTER TABLE `oc_yandex_category`
  ADD PRIMARY KEY (`yandex_category_id`),
  ADD KEY `level1` (`level1`,`level2`,`level3`),
  ADD KEY `level4` (`level4`);

--
-- Indexes for table `odinass_product_queue`
--
ALTER TABLE `odinass_product_queue`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `option_description`
--
ALTER TABLE `option_description`
  ADD PRIMARY KEY (`option_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `option_tooltip`
--
ALTER TABLE `option_tooltip`
  ADD PRIMARY KEY (`option_id`,`language_id`);

--
-- Indexes for table `option_value`
--
ALTER TABLE `option_value`
  ADD PRIMARY KEY (`option_value_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `option_value_description`
--
ALTER TABLE `option_value_description`
  ADD PRIMARY KEY (`option_value_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `order`
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
  ADD KEY `fcheque_link` (`fcheque_link`),
  ADD KEY `needs_checkboxua` (`needs_checkboxua`),
  ADD KEY `monocheckout` (`monocheckout`),
  ADD KEY `amazon_offers_type` (`amazon_offers_type`),
  ADD KEY `yam_campaign_id` (`yam_campaign_id`),
  ADD KEY `yam_express` (`yam_express`);

--
-- Indexes for table `order_amazon`
--
ALTER TABLE `order_amazon`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `amazon_order_id` (`amazon_order_id`);

--
-- Indexes for table `order_amazon_product`
--
ALTER TABLE `order_amazon_product`
  ADD PRIMARY KEY (`order_product_id`);

--
-- Indexes for table `order_amazon_report`
--
ALTER TABLE `order_amazon_report`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_courier_history`
--
ALTER TABLE `order_courier_history`
  ADD UNIQUE KEY `order_id_2` (`order_id`,`status`,`courier_id`) USING BTREE,
  ADD KEY `order_id` (`order_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `order_download`
--
ALTER TABLE `order_download`
  ADD PRIMARY KEY (`order_download_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_product_id` (`order_product_id`);

--
-- Indexes for table `order_field`
--
ALTER TABLE `order_field`
  ADD PRIMARY KEY (`order_id`,`custom_field_id`,`custom_field_value_id`),
  ADD KEY `custom_field_id` (`custom_field_id`),
  ADD KEY `custom_field_value_id` (`custom_field_value_id`);

--
-- Indexes for table `order_fraud`
--
ALTER TABLE `order_fraud`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `maxmind_id` (`maxmind_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`order_history_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_status_id` (`order_status_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `courier` (`courier`);

--
-- Indexes for table `order_invoice_history`
--
ALTER TABLE `order_invoice_history`
  ADD PRIMARY KEY (`order_invoice_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_option`
--
ALTER TABLE `order_option`
  ADD PRIMARY KEY (`order_option_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_product_id` (`order_product_id`),
  ADD KEY `product_option_id` (`product_option_id`),
  ADD KEY `product_option_value_id` (`product_option_value_id`),
  ADD KEY `order_product_id_2` (`order_product_id`,`type`,`name`,`product_option_value_id`);

--
-- Indexes for table `order_product`
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
-- Indexes for table `order_product_bought`
--
ALTER TABLE `order_product_bought`
  ADD PRIMARY KEY (`bought_id`);

--
-- Indexes for table `order_product_history`
--
ALTER TABLE `order_product_history`
  ADD PRIMARY KEY (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_product_nogood`
--
ALTER TABLE `order_product_nogood`
  ADD UNIQUE KEY `order_product_id` (`order_product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_product_reserves`
--
ALTER TABLE `order_product_reserves`
  ADD PRIMARY KEY (`order_reserve_id`),
  ADD KEY `order_product_id` (`order_product_id`),
  ADD KEY `country_id` (`country_code`),
  ADD KEY `uuid` (`uuid`);

--
-- Indexes for table `order_product_supply`
--
ALTER TABLE `order_product_supply`
  ADD PRIMARY KEY (`order_product_supply_id`);

--
-- Indexes for table `order_product_tracker`
--
ALTER TABLE `order_product_tracker`
  ADD PRIMARY KEY (`order_product_tracker_id`),
  ADD KEY `order_product` (`order_product`),
  ADD KEY `order_product_status` (`order_product_status`);

--
-- Indexes for table `order_product_untaken`
--
ALTER TABLE `order_product_untaken`
  ADD KEY `order_product_id` (`order_product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_receipt`
--
ALTER TABLE `order_receipt`
  ADD PRIMARY KEY (`order_receipt_id`),
  ADD UNIQUE KEY `order_id_2` (`order_id`,`fiscal_code`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fiscal_code` (`fiscal_code`),
  ADD KEY `is_sent_dps` (`is_sent_dps`),
  ADD KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `order_recurring`
--
ALTER TABLE `order_recurring`
  ADD PRIMARY KEY (`order_recurring_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `profile_id` (`profile_id`);

--
-- Indexes for table `order_recurring_transaction`
--
ALTER TABLE `order_recurring_transaction`
  ADD PRIMARY KEY (`order_recurring_transaction_id`),
  ADD KEY `order_recurring_id` (`order_recurring_id`);

--
-- Indexes for table `order_reject_reason`
--
ALTER TABLE `order_reject_reason`
  ADD KEY `reject_reason_id` (`reject_reason_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `order_related`
--
ALTER TABLE `order_related`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `related_order_id` (`related_order_id`);

--
-- Indexes for table `order_save_history`
--
ALTER TABLE `order_save_history`
  ADD PRIMARY KEY (`order_save_id`),
  ADD KEY `datetime` (`datetime`);

--
-- Indexes for table `order_set`
--
ALTER TABLE `order_set`
  ADD PRIMARY KEY (`order_set_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_simple_fields`
--
ALTER TABLE `order_simple_fields`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `novaposhta_city_guid` (`novaposhta_city_guid`),
  ADD KEY `cdek_city_guid` (`cdek_city_guid`);
ALTER TABLE `order_simple_fields` ADD FULLTEXT KEY `metadata` (`metadata`);

--
-- Indexes for table `order_sms_history`
--
ALTER TABLE `order_sms_history`
  ADD PRIMARY KEY (`order_history_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `order_status_id` (`order_status_id`);

--
-- Indexes for table `order_total`
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
-- Indexes for table `order_total_tax`
--
ALTER TABLE `order_total_tax`
  ADD PRIMARY KEY (`order_total_id`);

--
-- Indexes for table `order_to_1c_queue`
--
ALTER TABLE `order_to_1c_queue`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_tracker`
--
ALTER TABLE `order_tracker`
  ADD PRIMARY KEY (`order_tracker_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_tracker_sms`
--
ALTER TABLE `order_tracker_sms`
  ADD PRIMARY KEY (`tracker_sms_id`),
  ADD KEY `partie_num` (`partie_num`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `tracker_type` (`tracker_type`);

--
-- Indexes for table `order_ttns`
--
ALTER TABLE `order_ttns`
  ADD PRIMARY KEY (`order_ttn_id`),
  ADD UNIQUE KEY `order_id_ttn` (`order_id`,`ttn`) USING BTREE,
  ADD KEY `status` (`tracking_status`),
  ADD KEY `ttn` (`ttn`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `rejected` (`rejected`),
  ADD KEY `waiting` (`waiting`);

--
-- Indexes for table `order_ukrcredits`
--
ALTER TABLE `order_ukrcredits`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_voucher`
--
ALTER TABLE `order_voucher`
  ADD PRIMARY KEY (`order_voucher_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `voucher_theme_id` (`voucher_theme_id`);

--
-- Indexes for table `otp_tries`
--
ALTER TABLE `otp_tries`
  ADD PRIMARY KEY (`ip_addr`);

--
-- Indexes for table `parser_queue`
--
ALTER TABLE `parser_queue`
  ADD PRIMARY KEY (`parser_queue_id`);

--
-- Indexes for table `pavoslidergroups`
--
ALTER TABLE `pavoslidergroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pavosliderlayers`
--
ALTER TABLE `pavosliderlayers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `priceva_data`
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
-- Indexes for table `priceva_sources`
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
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `model` (`model`),
  ADD KEY `sku` (`sku`),
  ADD KEY `ean` (`ean`),
  ADD KEY `mpn` (`mpn`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `upc` (`upc`) USING BTREE,
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
  ADD KEY `quantity_updateMarker` (`quantity_updateMarker`),
  ADD KEY `getProduct` (`product_id`,`date_available`,`status`),
  ADD KEY `Amazon Filled` (`added_from_amazon`,`filled_from_amazon`),
  ADD KEY `getProducts` (`status`,`date_available`,`is_markdown`,`main_variant_id`,`display_in_catalog`,`price`,`price_national`,`added_from_amazon`,`filled_from_amazon`) USING BTREE,
  ADD KEY `price_delayed` (`price_delayed`),
  ADD KEY `amazon_offers_type` (`amazon_offers_type`),
  ADD KEY `amazon_seller_quality` (`amazon_seller_quality`),
  ADD KEY `hotline_disable` (`hotline_disable`),
  ADD KEY `added_from_supplier` (`added_from_supplier`),
  ADD KEY `product_group_id` (`product_group_id`);

--
-- Indexes for table `product_additional_offer`
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
-- Indexes for table `product_additional_offer_to_store`
--
ALTER TABLE `product_additional_offer_to_store`
  ADD KEY `product_additional_offer_id` (`product_additional_offer_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `product_also_bought`
--
ALTER TABLE `product_also_bought`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`also_bought_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `also_bought_id` (`also_bought_id`);

--
-- Indexes for table `product_also_viewed`
--
ALTER TABLE `product_also_viewed`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`also_viewed_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `also_viewed_id` (`also_viewed_id`);

--
-- Indexes for table `product_amzn_data`
--
ALTER TABLE `product_amzn_data`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `asin` (`asin`);

--
-- Indexes for table `product_amzn_offers`
--
ALTER TABLE `product_amzn_offers`
  ADD PRIMARY KEY (`amazon_offer_id`),
  ADD KEY `product_id` (`asin`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `is_min_price` (`is_min_price`),
  ADD KEY `isPrime` (`isPrime`),
  ADD KEY `isBestOffer` (`isBestOffer`),
  ADD KEY `isBuyBoxWinner` (`isBuyBoxWinner`),
  ADD KEY `minDays` (`minDays`),
  ADD KEY `sellerName` (`sellerName`),
  ADD KEY `isDeOffer` (`isNativeOffer`),
  ADD KEY `sellerID` (`sellerID`);

--
-- Indexes for table `product_anyrelated`
--
ALTER TABLE `product_anyrelated`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `anyrelated_id` (`anyrelated_id`);

--
-- Indexes for table `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD PRIMARY KEY (`product_id`,`attribute_id`,`language_id`),
  ADD KEY `attribute_id` (`attribute_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_id_language_id` (`product_id`,`language_id`) USING BTREE,
  ADD KEY `attribute_id_language_id` (`attribute_id`,`language_id`) USING BTREE,
  ADD KEY `text` (`text`) USING BTREE;

--
-- Indexes for table `product_child`
--
ALTER TABLE `product_child`
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_costs`
--
ALTER TABLE `product_costs`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `product_description`
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
-- Indexes for table `product_discount`
--
ALTER TABLE `product_discount`
  ADD PRIMARY KEY (`product_discount_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_feature`
--
ALTER TABLE `product_feature`
  ADD PRIMARY KEY (`product_id`,`feature_id`,`language_id`),
  ADD KEY `feature_id` (`feature_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_id_language_id` (`product_id`,`language_id`) USING BTREE;

--
-- Indexes for table `product_filter`
--
ALTER TABLE `product_filter`
  ADD PRIMARY KEY (`product_id`,`filter_id`);

--
-- Indexes for table `product_front_price`
--
ALTER TABLE `product_front_price`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `product_groups`
--
ALTER TABLE `product_groups`
  ADD PRIMARY KEY (`product_group_id`),
  ADD KEY `product_group_exclude_remarketing` (`product_group_exclude_remarketing`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`product_image_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `product_master`
--
ALTER TABLE `product_master`
  ADD PRIMARY KEY (`master_product_id`,`product_id`,`special_attribute_group_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_offers_history`
--
ALTER TABLE `product_offers_history`
  ADD PRIMARY KEY (`offer_history_id`),
  ADD KEY `asin` (`asin`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `product_option`
--
ALTER TABLE `product_option`
  ADD PRIMARY KEY (`product_option_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `product_option_value`
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
-- Indexes for table `product_price_history`
--
ALTER TABLE `product_price_history`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`),
  ADD KEY `type` (`type`),
  ADD KEY `source` (`source`);

--
-- Indexes for table `product_price_national_to_store`
--
ALTER TABLE `product_price_national_to_store`
  ADD UNIQUE KEY `product_store` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`),
  ADD KEY `price` (`price`),
  ADD KEY `getProduct` (`product_id`,`store_id`,`price`),
  ADD KEY `price_delayed` (`price_delayed`);

--
-- Indexes for table `product_price_national_to_store1`
--
ALTER TABLE `product_price_national_to_store1`
  ADD UNIQUE KEY `product_store` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `product_price_national_to_yam`
--
ALTER TABLE `product_price_national_to_yam`
  ADD UNIQUE KEY `product_store` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `product_price_to_store`
--
ALTER TABLE `product_price_to_store`
  ADD PRIMARY KEY (`product_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `settled_from_1c` (`settled_from_1c`),
  ADD KEY `dot_not_overload_1c` (`dot_not_overload_1c`),
  ADD KEY `price` (`price`),
  ADD KEY `price_delayed` (`price_delayed`);

--
-- Indexes for table `product_product_option`
--
ALTER TABLE `product_product_option`
  ADD PRIMARY KEY (`product_product_option_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_product_option_value`
--
ALTER TABLE `product_product_option_value`
  ADD PRIMARY KEY (`product_product_option_value_id`),
  ADD KEY `product_product_option_id` (`product_product_option_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_option_id` (`product_option_id`);

--
-- Indexes for table `product_profile`
--
ALTER TABLE `product_profile`
  ADD PRIMARY KEY (`product_id`,`profile_id`,`customer_group_id`);

--
-- Indexes for table `product_purchase`
--
ALTER TABLE `product_purchase`
  ADD KEY `purchase_uuid` (`purchase_uuid`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `product_recurring`
--
ALTER TABLE `product_recurring`
  ADD PRIMARY KEY (`product_id`,`store_id`);

--
-- Indexes for table `product_related`
--
ALTER TABLE `product_related`
  ADD PRIMARY KEY (`product_id`,`related_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `related_id` (`related_id`);

--
-- Indexes for table `product_related_set`
--
ALTER TABLE `product_related_set`
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_reward`
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
-- Indexes for table `product_shop_by_look`
--
ALTER TABLE `product_shop_by_look`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`shop_by_look_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `shop_by_look_id` (`shop_by_look_id`);

--
-- Indexes for table `product_similar`
--
ALTER TABLE `product_similar`
  ADD PRIMARY KEY (`product_id`,`similar_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `similar_id` (`similar_id`);

--
-- Indexes for table `product_similar_to_consider`
--
ALTER TABLE `product_similar_to_consider`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`similar_to_consider_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `similar_to_consider_id` (`similar_to_consider_id`);

--
-- Indexes for table `product_sources`
--
ALTER TABLE `product_sources`
  ADD PRIMARY KEY (`product_source_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `source` (`source`(255));

--
-- Indexes for table `product_special`
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
  ADD KEY `date_end` (`date_end`),
  ADD KEY `getProduct` (`product_id`,`customer_group_id`,`price`,`store_id`,`date_start`,`date_end`);

--
-- Indexes for table `product_special_attribute`
--
ALTER TABLE `product_special_attribute`
  ADD PRIMARY KEY (`product_special_attribute_id`);

--
-- Indexes for table `product_special_backup`
--
ALTER TABLE `product_special_backup`
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_sponsored`
--
ALTER TABLE `product_sponsored`
  ADD PRIMARY KEY (`product_id`,`sponsored_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sponsored_id` (`sponsored_id`);

--
-- Indexes for table `product_status`
--
ALTER TABLE `product_status`
  ADD KEY `status_id` (`status_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_sticker`
--
ALTER TABLE `product_sticker`
  ADD PRIMARY KEY (`product_sticker_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_stock_limits`
--
ALTER TABLE `product_stock_limits`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `min_buy` (`min_stock`),
  ADD KEY `rec_buy` (`rec_stock`);

--
-- Indexes for table `product_stock_status`
--
ALTER TABLE `product_stock_status`
  ADD UNIQUE KEY `product_id_3` (`product_id`,`store_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `product_stock_waits`
--
ALTER TABLE `product_stock_waits`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `quantity_stockM` (`quantity_stockM`),
  ADD KEY `quantity_stockK` (`quantity_stockK`),
  ADD KEY `quantity_stockMN` (`quantity_stockMN`),
  ADD KEY `quantity_stockAS` (`quantity_stockAS`),
  ADD KEY `quantity_stock` (`quantity_stock`);

--
-- Indexes for table `product_tab`
--
ALTER TABLE `product_tab`
  ADD PRIMARY KEY (`tab_id`);

--
-- Indexes for table `product_tab_content`
--
ALTER TABLE `product_tab_content`
  ADD PRIMARY KEY (`product_id`,`language_id`,`tab_id`);

--
-- Indexes for table `product_tab_default`
--
ALTER TABLE `product_tab_default`
  ADD PRIMARY KEY (`tab_id`,`language_id`);

--
-- Indexes for table `product_tab_name`
--
ALTER TABLE `product_tab_name`
  ADD PRIMARY KEY (`tab_id`,`language_id`);

--
-- Indexes for table `product_to_category`
--
ALTER TABLE `product_to_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `main_category` (`main_category`);

--
-- Indexes for table `product_to_download`
--
ALTER TABLE `product_to_download`
  ADD PRIMARY KEY (`product_id`,`download_id`);

--
-- Indexes for table `product_to_layout`
--
ALTER TABLE `product_to_layout`
  ADD PRIMARY KEY (`product_id`,`store_id`);

--
-- Indexes for table `product_to_set`
--
ALTER TABLE `product_to_set`
  ADD KEY `set_id` (`set_id`),
  ADD KEY `clean_product_id` (`clean_product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_to_store`
--
ALTER TABLE `product_to_store`
  ADD PRIMARY KEY (`product_id`,`store_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_to_tab`
--
ALTER TABLE `product_to_tab`
  ADD PRIMARY KEY (`product_id`,`tab_id`);

--
-- Indexes for table `product_ukrcredits`
--
ALTER TABLE `product_ukrcredits`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD UNIQUE KEY `variant_asin` (`variant_asin`) USING BTREE,
  ADD KEY `main_asin` (`main_asin`) USING BTREE;

--
-- Indexes for table `product_variants_ids`
--
ALTER TABLE `product_variants_ids`
  ADD UNIQUE KEY `variant_id` (`variant_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_video`
--
ALTER TABLE `product_video`
  ADD PRIMARY KEY (`product_video_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `product_video_description`
--
ALTER TABLE `product_video_description`
  ADD KEY `language_id` (`language_id`),
  ADD KEY `product_video_id` (`product_video_id`),
  ADD KEY `product_video_id_2` (`product_video_id`,`language_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_view_to_purchase`
--
ALTER TABLE `product_view_to_purchase`
  ADD UNIQUE KEY `product_id_2` (`product_id`,`view_to_purchase_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`),
  ADD KEY `view_to_purchase_id` (`view_to_purchase_id`);

--
-- Indexes for table `product_yam_data`
--
ALTER TABLE `product_yam_data`
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `yam_category_id` (`yam_category_id`);

--
-- Indexes for table `product_yam_recommended_prices`
--
ALTER TABLE `product_yam_recommended_prices`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `profile_description`
--
ALTER TABLE `profile_description`
  ADD PRIMARY KEY (`profile_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `queue_mail`
--
ALTER TABLE `queue_mail`
  ADD PRIMARY KEY (`queue_mail_id`);

--
-- Indexes for table `queue_push`
--
ALTER TABLE `queue_push`
  ADD PRIMARY KEY (`queue_push_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `queue_sms`
--
ALTER TABLE `queue_sms`
  ADD PRIMARY KEY (`queue_sms_id`);

--
-- Indexes for table `redirect`
--
ALTER TABLE `redirect`
  ADD PRIMARY KEY (`redirect_id`),
  ADD KEY `active` (`active`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `date_end` (`date_end`),
  ADD KEY `from_url` (`from_url`(255));

--
-- Indexes for table `referrer_patterns`
--
ALTER TABLE `referrer_patterns`
  ADD PRIMARY KEY (`pattern_id`);

--
-- Indexes for table `return`
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
-- Indexes for table `return_action`
--
ALTER TABLE `return_action`
  ADD PRIMARY KEY (`return_action_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `return_history`
--
ALTER TABLE `return_history`
  ADD PRIMARY KEY (`return_history_id`),
  ADD KEY `return_id` (`return_id`),
  ADD KEY `return_status_id` (`return_status_id`);

--
-- Indexes for table `return_reason`
--
ALTER TABLE `return_reason`
  ADD PRIMARY KEY (`return_reason_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `return_status`
--
ALTER TABLE `return_status`
  ADD PRIMARY KEY (`return_status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `rating` (`rating`),
  ADD KEY `status` (`status`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `rewarded` (`rewarded`);

--
-- Indexes for table `review_description`
--
ALTER TABLE `review_description`
  ADD UNIQUE KEY `review_id_2` (`review_id`,`language_id`),
  ADD KEY `review_id` (`review_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `review_fields`
--
ALTER TABLE `review_fields`
  ADD KEY `review_id` (`review_id`);

--
-- Indexes for table `review_name`
--
ALTER TABLE `review_name`
  ADD PRIMARY KEY (`review_name_id`);

--
-- Indexes for table `review_template`
--
ALTER TABLE `review_template`
  ADD PRIMARY KEY (`review_template_id`);

--
-- Indexes for table `search_history`
--
ALTER TABLE `search_history`
  ADD UNIQUE KEY `text` (`text`),
  ADD KEY `times` (`times`),
  ADD KEY `results` (`results`),
  ADD KEY `times_2` (`times`,`results`);

--
-- Indexes for table `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`segment_id`),
  ADD KEY `sort_order` (`sort_order`),
  ADD KEY `group` (`group`);

--
-- Indexes for table `segments_dynamics`
--
ALTER TABLE `segments_dynamics`
  ADD PRIMARY KEY (`segment_dynamics_id`),
  ADD KEY `segment_id` (`segment_id`) USING BTREE,
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `seocities`
--
ALTER TABLE `seocities`
  ADD PRIMARY KEY (`seocity_id`);

--
-- Indexes for table `seo_hreflang`
--
ALTER TABLE `seo_hreflang`
  ADD UNIQUE KEY `language_id_2` (`language_id`,`query`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `query` (`query`);

--
-- Indexes for table `set`
--
ALTER TABLE `set`
  ADD PRIMARY KEY (`set_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `tax_class_id` (`tax_class_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `group` (`group`),
  ADD KEY `key` (`key`),
  ADD KEY `value` (`value`(1024)),
  ADD KEY `group_2` (`group`,`key`);

--
-- Indexes for table `set_description`
--
ALTER TABLE `set_description`
  ADD PRIMARY KEY (`set_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `set_to_category`
--
ALTER TABLE `set_to_category`
  ADD PRIMARY KEY (`set_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `set_to_store`
--
ALTER TABLE `set_to_store`
  ADD PRIMARY KEY (`set_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shoputils_citycourier_description`
--
ALTER TABLE `shoputils_citycourier_description`
  ADD UNIQUE KEY `IDX_oc_shoputils_citycourier_description` (`language_id`);

--
-- Indexes for table `shoputils_cumulative_discounts`
--
ALTER TABLE `shoputils_cumulative_discounts`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `shoputils_cumulative_discounts_description`
--
ALTER TABLE `shoputils_cumulative_discounts_description`
  ADD UNIQUE KEY `IDX_shoputils_cumulative_discounts_description` (`discount_id`,`language_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `shoputils_cumulative_discounts_to_customer_group`
--
ALTER TABLE `shoputils_cumulative_discounts_to_customer_group`
  ADD UNIQUE KEY `IDX_shoputils_cumulative_discounts_to_customer_group` (`discount_id`,`customer_group_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `customer_group_id` (`customer_group_id`);

--
-- Indexes for table `shoputils_cumulative_discounts_to_manufacturer`
--
ALTER TABLE `shoputils_cumulative_discounts_to_manufacturer`
  ADD UNIQUE KEY `discount_id` (`discount_id`,`manufacturer_id`),
  ADD KEY `discount_id_2` (`discount_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`);

--
-- Indexes for table `shoputils_cumulative_discounts_to_store`
--
ALTER TABLE `shoputils_cumulative_discounts_to_store`
  ADD UNIQUE KEY `IDX_shoputils_cumulative_discounts_to_store` (`discount_id`,`store_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `shop_rating`
--
ALTER TABLE `shop_rating`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `shop_rating_answers`
--
ALTER TABLE `shop_rating_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_rating_custom_types`
--
ALTER TABLE `shop_rating_custom_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_rating_custom_values`
--
ALTER TABLE `shop_rating_custom_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_rating_description`
--
ALTER TABLE `shop_rating_description`
  ADD UNIQUE KEY `rate_id_2` (`rate_id`,`language_id`),
  ADD KEY `rate_id` (`rate_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `short_url_alias`
--
ALTER TABLE `short_url_alias`
  ADD PRIMARY KEY (`url_id`),
  ADD KEY `url` (`url`),
  ADD KEY `alias` (`alias`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `simple_cart`
--
ALTER TABLE `simple_cart`
  ADD PRIMARY KEY (`simple_cart_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `simple_custom_data`
--
ALTER TABLE `simple_custom_data`
  ADD PRIMARY KEY (`object_type`,`object_id`,`customer_id`),
  ADD KEY `object_id` (`object_id`),
  ADD KEY `object_type` (`object_type`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `sms_log`
--
ALTER TABLE `sms_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `socnetauth2_customer2account`
--
ALTER TABLE `socnetauth2_customer2account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `socnetauth2_precode`
--
ALTER TABLE `socnetauth2_precode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `socnetauth2_records`
--
ALTER TABLE `socnetauth2_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `special_attribute`
--
ALTER TABLE `special_attribute`
  ADD PRIMARY KEY (`special_attribute_id`);

--
-- Indexes for table `special_attribute_group`
--
ALTER TABLE `special_attribute_group`
  ADD PRIMARY KEY (`special_attribute_group_id`);

--
-- Indexes for table `sphinx_suggestions`
--
ALTER TABLE `sphinx_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `keyword` (`keyword`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `stocks_dynamics`
--
ALTER TABLE `stocks_dynamics`
  ADD PRIMARY KEY (`stock_dynamics_id`),
  ADD UNIQUE KEY `date_added` (`date_added`,`warehouse_identifier`);

--
-- Indexes for table `stock_status`
--
ALTER TABLE `stock_status`
  ADD PRIMARY KEY (`stock_status_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `subscribe`
--
ALTER TABLE `subscribe`
  ADD PRIMARY KEY (`subscribe_id`);

--
-- Indexes for table `subscribe_auth_description`
--
ALTER TABLE `subscribe_auth_description`
  ADD PRIMARY KEY (`subscribe_auth_id`);

--
-- Indexes for table `subscribe_email_description`
--
ALTER TABLE `subscribe_email_description`
  ADD PRIMARY KEY (`subscribe_desc_id`);

--
-- Indexes for table `superstat_viewed`
--
ALTER TABLE `superstat_viewed`
  ADD UNIQUE KEY `entity_type_2` (`entity_type`,`entity_id`,`store_id`,`date`),
  ADD KEY `entity_type` (`entity_type`),
  ADD KEY `entity_id` (`entity_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `supplier_name` (`supplier_name`),
  ADD KEY `1c_uuid` (`1c_uuid`),
  ADD KEY `amzn_good` (`amzn_good`),
  ADD KEY `amzn_bad` (`amzn_bad`),
  ADD KEY `supplier_type` (`supplier_type`),
  ADD KEY `supplier_code` (`supplier_code`),
  ADD KEY `amazon_seller_id` (`amazon_seller_id`),
  ADD KEY `is_de` (`is_native`),
  ADD KEY `rating50` (`rating50`),
  ADD KEY `ratings_total` (`ratings_total`),
  ADD KEY `telephone` (`telephone`),
  ADD KEY `email` (`email`),
  ADD KEY `supplier_country` (`supplier_country`),
  ADD KEY `parser_status` (`parser_status`),
  ADD KEY `admin_status` (`admin_status`);

--
-- Indexes for table `supplier_attributes`
--
ALTER TABLE `supplier_attributes`
  ADD PRIMARY KEY (`supplier_attribute_id`),
  ADD UNIQUE KEY `supplier_id_2` (`supplier_id`,`supplier_attribute`),
  ADD KEY `supplier_attribute` (`supplier_attribute`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `supplier_categories`
--
ALTER TABLE `supplier_categories`
  ADD PRIMARY KEY (`supplier_category_id`),
  ADD UNIQUE KEY `supplier_id_2` (`supplier_id`,`supplier_category_full`) USING HASH,
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `supplier_infeed_id` (`supplier_infeed_id`),
  ADD KEY `supplier_infeed_parent` (`supplier_infeed_parent`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD PRIMARY KEY (`product_supplier_id`),
  ADD UNIQUE KEY `supplier_id_2` (`supplier_id`,`supplier_product_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tax_class`
--
ALTER TABLE `tax_class`
  ADD PRIMARY KEY (`tax_class_id`);

--
-- Indexes for table `tax_rate`
--
ALTER TABLE `tax_rate`
  ADD PRIMARY KEY (`tax_rate_id`),
  ADD KEY `geo_zone_id` (`geo_zone_id`);

--
-- Indexes for table `tax_rate_to_customer_group`
--
ALTER TABLE `tax_rate_to_customer_group`
  ADD PRIMARY KEY (`tax_rate_id`,`customer_group_id`),
  ADD KEY `customer_group_id` (`customer_group_id`);

--
-- Indexes for table `tax_rule`
--
ALTER TABLE `tax_rule`
  ADD PRIMARY KEY (`tax_rule_id`),
  ADD KEY `tax_class_id` (`tax_class_id`),
  ADD KEY `tax_rate_id` (`tax_rate_id`);

--
-- Indexes for table `telegram_chats`
--
ALTER TABLE `telegram_chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telegram_messages`
--
ALTER TABLE `telegram_messages`
  ADD PRIMARY KEY (`update_id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `telegram_users`
--
ALTER TABLE `telegram_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `telegram_users_chats`
--
ALTER TABLE `telegram_users_chats`
  ADD PRIMARY KEY (`user_id`,`chat_id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `tickets`
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
-- Indexes for table `ticket_sort`
--
ALTER TABLE `ticket_sort`
  ADD UNIQUE KEY `ticket_id` (`ticket_id`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `tracker`
--
ALTER TABLE `tracker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `translate_stats`
--
ALTER TABLE `translate_stats`
  ADD KEY `time` (`time`);

--
-- Indexes for table `trigger_history`
--
ALTER TABLE `trigger_history`
  ADD PRIMARY KEY (`trigger_history_id`),
  ADD KEY `trigger_history_id` (`trigger_history_id`,`order_id`,`customer_id`),
  ADD KEY `trigger_history_id_2` (`trigger_history_id`,`order_id`,`actiontemplate_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `url_alias`
--
ALTER TABLE `url_alias`
  ADD PRIMARY KEY (`url_alias_id`),
  ADD UNIQUE KEY `query_language` (`query`,`language_id`) USING BTREE,
  ADD KEY `keyword` (`keyword`) USING BTREE,
  ADD KEY `language_id` (`language_id`),
  ADD KEY `query` (`query`) USING BTREE;

--
-- Indexes for table `url_alias_cached`
--
ALTER TABLE `url_alias_cached`
  ADD KEY `store_id` (`store_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `route` (`route`),
  ADD KEY `args` (`args`),
  ADD KEY `checksum` (`checksum`);

--
-- Indexes for table `user`
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
-- Indexes for table `user_content`
--
ALTER TABLE `user_content`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `entity_type` (`entity_type`),
  ADD KEY `action` (`action`),
  ADD KEY `user_id_2` (`user_id`,`date`,`action`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`user_group_id`),
  ADD KEY `alert_namespace` (`alert_namespace`),
  ADD KEY `ticket` (`ticket`),
  ADD KEY `sip_queue` (`sip_queue`),
  ADD KEY `bitrix_id` (`bitrix_id`);

--
-- Indexes for table `user_group_to_store`
--
ALTER TABLE `user_group_to_store`
  ADD KEY `user_group_id` (`user_group_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `user_worktime`
--
ALTER TABLE `user_worktime`
  ADD UNIQUE KEY `user_id` (`user_id`,`date`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`voucher_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `voucher_theme_id` (`voucher_theme_id`);

--
-- Indexes for table `voucher_history`
--
ALTER TABLE `voucher_history`
  ADD PRIMARY KEY (`voucher_history_id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `voucher_theme`
--
ALTER TABLE `voucher_theme`
  ADD PRIMARY KEY (`voucher_theme_id`);

--
-- Indexes for table `voucher_theme_description`
--
ALTER TABLE `voucher_theme_description`
  ADD PRIMARY KEY (`voucher_theme_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `wayforpay_orders`
--
ALTER TABLE `wayforpay_orders`
  ADD PRIMARY KEY (`wayforpay_order_id`);

--
-- Indexes for table `wc_continents`
--
ALTER TABLE `wc_continents`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `wc_countries`
--
ALTER TABLE `wc_countries`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `weight_class`
--
ALTER TABLE `weight_class`
  ADD PRIMARY KEY (`weight_class_id`),
  ADD KEY `amazon_key` (`amazon_key`);

--
-- Indexes for table `weight_class_description`
--
ALTER TABLE `weight_class_description`
  ADD PRIMARY KEY (`weight_class_id`,`language_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `weight_class_id` (`weight_class_id`);

--
-- Indexes for table `yandex_feeds`
--
ALTER TABLE `yandex_feeds`
  ADD KEY `store_id` (`store_id`),
  ADD KEY `entity_type` (`entity_type`);

--
-- Indexes for table `yandex_queue`
--
ALTER TABLE `yandex_queue`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `yandex_stock_queue`
--
ALTER TABLE `yandex_stock_queue`
  ADD UNIQUE KEY `yam_product_id` (`yam_product_id`);

--
-- Indexes for table `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`zone_id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `zone_to_geo_zone`
--
ALTER TABLE `zone_to_geo_zone`
  ADD PRIMARY KEY (`zone_to_geo_zone_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `geo_zone_id` (`geo_zone_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actions`
--
ALTER TABLE `actions`
  MODIFY `actions_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actiontemplate`
--
ALTER TABLE `actiontemplate`
  MODIFY `actiontemplate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adminlog`
--
ALTER TABLE `adminlog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advanced_coupon`
--
ALTER TABLE `advanced_coupon`
  MODIFY `advanced_coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advanced_coupon_history`
--
ALTER TABLE `advanced_coupon_history`
  MODIFY `advanced_coupon_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `affiliate`
--
ALTER TABLE `affiliate`
  MODIFY `affiliate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `affiliate_statistics`
--
ALTER TABLE `affiliate_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `affiliate_transaction`
--
ALTER TABLE `affiliate_transaction`
  MODIFY `affiliate_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alertlog`
--
ALTER TABLE `alertlog`
  MODIFY `alertlog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alsoviewed`
--
ALTER TABLE `alsoviewed`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amazon_orders`
--
ALTER TABLE `amazon_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amazon_orders_products`
--
ALTER TABLE `amazon_orders_products`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amazon_zipcodes`
--
ALTER TABLE `amazon_zipcodes`
  MODIFY `zipcode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attribute`
--
ALTER TABLE `attribute`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attribute_group`
--
ALTER TABLE `attribute_group`
  MODIFY `attribute_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attribute_value_image`
--
ALTER TABLE `attribute_value_image`
  MODIFY `attribute_value_image` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner_image`
--
ALTER TABLE `banner_image`
  MODIFY `banner_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `callback`
--
ALTER TABLE `callback`
  MODIFY `call_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_hotline_tree`
--
ALTER TABLE `category_hotline_tree`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_menu_content`
--
ALTER TABLE `category_menu_content`
  MODIFY `category_menu_content_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_overprice_rules`
--
ALTER TABLE `category_overprice_rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_psm_template`
--
ALTER TABLE `category_psm_template`
  MODIFY `category_psm_template_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_review`
--
ALTER TABLE `category_review`
  MODIFY `categoryreview_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_search_words`
--
ALTER TABLE `category_search_words`
  MODIFY `category_search_word_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_cities`
--
ALTER TABLE `cdek_cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_deliverypoints`
--
ALTER TABLE `cdek_deliverypoints`
  MODIFY `deliverypoint_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_dispatch`
--
ALTER TABLE `cdek_dispatch`
  MODIFY `dispatch_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_order_call`
--
ALTER TABLE `cdek_order_call`
  MODIFY `call_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_order_courier`
--
ALTER TABLE `cdek_order_courier`
  MODIFY `courier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_order_package`
--
ALTER TABLE `cdek_order_package`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_order_package_item`
--
ALTER TABLE `cdek_order_package_item`
  MODIFY `package_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_order_reason`
--
ALTER TABLE `cdek_order_reason`
  MODIFY `reason_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cdek_zones`
--
ALTER TABLE `cdek_zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competitors`
--
ALTER TABLE `competitors`
  MODIFY `competitor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competitor_price`
--
ALTER TABLE `competitor_price`
  MODIFY `competitor_price_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
  MODIFY `counter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countrybrand`
--
ALTER TABLE `countrybrand`
  MODIFY `countrybrand_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_history`
--
ALTER TABLE `coupon_history`
  MODIFY `coupon_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_product`
--
ALTER TABLE `coupon_product`
  MODIFY `coupon_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_random`
--
ALTER TABLE `coupon_random`
  MODIFY `coupon_random_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `csvprice_pro`
--
ALTER TABLE `csvprice_pro`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `csvprice_pro_crontab`
--
ALTER TABLE `csvprice_pro_crontab`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `csvprice_pro_profiles`
--
ALTER TABLE `csvprice_pro_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_ban_ip`
--
ALTER TABLE `customer_ban_ip`
  MODIFY `customer_ban_ip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_calls`
--
ALTER TABLE `customer_calls`
  MODIFY `customer_call_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_email_campaigns`
--
ALTER TABLE `customer_email_campaigns`
  MODIFY `customer_email_campaigns_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_group`
--
ALTER TABLE `customer_group`
  MODIFY `customer_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_history`
--
ALTER TABLE `customer_history`
  MODIFY `customer_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_ip`
--
ALTER TABLE `customer_ip`
  MODIFY `customer_ip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_reward`
--
ALTER TABLE `customer_reward`
  MODIFY `customer_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_reward_queue`
--
ALTER TABLE `customer_reward_queue`
  MODIFY `customer_reward_queue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_search_history`
--
ALTER TABLE `customer_search_history`
  MODIFY `customer_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_transaction`
--
ALTER TABLE `customer_transaction`
  MODIFY `customer_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field`
--
ALTER TABLE `custom_field`
  MODIFY `custom_field_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field_value`
--
ALTER TABLE `custom_field_value`
  MODIFY `custom_field_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_url_404`
--
ALTER TABLE `custom_url_404`
  MODIFY `custom_url_404_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `download`
--
ALTER TABLE `download`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailmarketing_logs`
--
ALTER TABLE `emailmarketing_logs`
  MODIFY `emailmarketing_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailtemplate`
--
ALTER TABLE `emailtemplate`
  MODIFY `emailtemplate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailtemplate_config`
--
ALTER TABLE `emailtemplate_config`
  MODIFY `emailtemplate_config_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailtemplate_logs`
--
ALTER TABLE `emailtemplate_logs`
  MODIFY `emailtemplate_log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailtemplate_shortcode`
--
ALTER TABLE `emailtemplate_shortcode`
  MODIFY `emailtemplate_shortcode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `entity_reward`
--
ALTER TABLE `entity_reward`
  MODIFY `entity_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extension`
--
ALTER TABLE `extension`
  MODIFY `extension_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facategory`
--
ALTER TABLE `facategory`
  MODIFY `facategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_category`
--
ALTER TABLE `faq_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_question`
--
ALTER TABLE `faq_question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filter`
--
ALTER TABLE `filter`
  MODIFY `filter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filter_group`
--
ALTER TABLE `filter_group`
  MODIFY `filter_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `geo`
--
ALTER TABLE `geo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `geo_zone`
--
ALTER TABLE `geo_zone`
  MODIFY `geo_zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `google_base_category`
--
ALTER TABLE `google_base_category`
  MODIFY `google_base_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hj_any_feed_feeds`
--
ALTER TABLE `hj_any_feed_feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imagemaps`
--
ALTER TABLE `imagemaps`
  MODIFY `imagemap_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
  MODIFY `information_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `information_attribute`
--
ALTER TABLE `information_attribute`
  MODIFY `information_attribute_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interplusplus`
--
ALTER TABLE `interplusplus`
  MODIFY `inter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `justin_cities`
--
ALTER TABLE `justin_cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `justin_city_regions`
--
ALTER TABLE `justin_city_regions`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `justin_streets`
--
ALTER TABLE `justin_streets`
  MODIFY `street_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `justin_warehouses`
--
ALTER TABLE `justin_warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `justin_zones`
--
ALTER TABLE `justin_zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `justin_zone_regions`
--
ALTER TABLE `justin_zone_regions`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keyworder`
--
ALTER TABLE `keyworder`
  MODIFY `keyworder_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landingpage`
--
ALTER TABLE `landingpage`
  MODIFY `landingpage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layout`
--
ALTER TABLE `layout`
  MODIFY `layout_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layout_route`
--
ALTER TABLE `layout_route`
  MODIFY `layout_route_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `legalperson`
--
ALTER TABLE `legalperson`
  MODIFY `legalperson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `length_class`
--
ALTER TABLE `length_class`
  MODIFY `length_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `length_class_description`
--
ALTER TABLE `length_class_description`
  MODIFY `length_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manufacturer_page_content`
--
ALTER TABLE `manufacturer_page_content`
  MODIFY `manufacturer_page_content_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mono_orders`
--
ALTER TABLE `mono_orders`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `multi_pay_payment`
--
ALTER TABLE `multi_pay_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nauthor`
--
ALTER TABLE `nauthor`
  MODIFY `nauthor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ncategory`
--
ALTER TABLE `ncategory`
  MODIFY `ncategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ncomments`
--
ALTER TABLE `ncomments`
  MODIFY `ncomment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_gallery`
--
ALTER TABLE `news_gallery`
  MODIFY `news_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_video`
--
ALTER TABLE `news_video`
  MODIFY `news_video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `novaposhta_cities`
--
ALTER TABLE `novaposhta_cities`
  MODIFY `CityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `novaposhta_cities_ww`
--
ALTER TABLE `novaposhta_cities_ww`
  MODIFY `CityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `novaposhta_streets`
--
ALTER TABLE `novaposhta_streets`
  MODIFY `StreetID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `novaposhta_warehouses`
--
ALTER TABLE `novaposhta_warehouses`
  MODIFY `WarehouseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `novaposhta_zones`
--
ALTER TABLE `novaposhta_zones`
  MODIFY `ZoneID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ocfilter_option`
--
ALTER TABLE `ocfilter_option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ocfilter_option_value`
--
ALTER TABLE `ocfilter_option_value`
  MODIFY `value_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ocfilter_option_value_to_product`
--
ALTER TABLE `ocfilter_option_value_to_product`
  MODIFY `ocfilter_option_value_to_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ocfilter_page`
--
ALTER TABLE `ocfilter_page`
  MODIFY `ocfilter_page_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oc_feedback`
--
ALTER TABLE `oc_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oc_yandex_category`
--
ALTER TABLE `oc_yandex_category`
  MODIFY `yandex_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `option`
--
ALTER TABLE `option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `option_value`
--
ALTER TABLE `option_value`
  MODIFY `option_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Номер заказа';

--
-- AUTO_INCREMENT for table `order_download`
--
ALTER TABLE `order_download`
  MODIFY `order_download_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `order_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_invoice_history`
--
ALTER TABLE `order_invoice_history`
  MODIFY `order_invoice_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_option`
--
ALTER TABLE `order_option`
  MODIFY `order_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_bought`
--
ALTER TABLE `order_product_bought`
  MODIFY `bought_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_history`
--
ALTER TABLE `order_product_history`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_nogood`
--
ALTER TABLE `order_product_nogood`
  MODIFY `order_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_reserves`
--
ALTER TABLE `order_product_reserves`
  MODIFY `order_reserve_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_supply`
--
ALTER TABLE `order_product_supply`
  MODIFY `order_product_supply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_tracker`
--
ALTER TABLE `order_product_tracker`
  MODIFY `order_product_tracker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_receipt`
--
ALTER TABLE `order_receipt`
  MODIFY `order_receipt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_recurring`
--
ALTER TABLE `order_recurring`
  MODIFY `order_recurring_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_recurring_transaction`
--
ALTER TABLE `order_recurring_transaction`
  MODIFY `order_recurring_transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_reject_reason`
--
ALTER TABLE `order_reject_reason`
  MODIFY `reject_reason_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_save_history`
--
ALTER TABLE `order_save_history`
  MODIFY `order_save_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_set`
--
ALTER TABLE `order_set`
  MODIFY `order_set_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_sms_history`
--
ALTER TABLE `order_sms_history`
  MODIFY `order_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_total`
--
ALTER TABLE `order_total`
  MODIFY `order_total_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_tracker`
--
ALTER TABLE `order_tracker`
  MODIFY `order_tracker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_tracker_sms`
--
ALTER TABLE `order_tracker_sms`
  MODIFY `tracker_sms_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_ttns`
--
ALTER TABLE `order_ttns`
  MODIFY `order_ttn_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_voucher`
--
ALTER TABLE `order_voucher`
  MODIFY `order_voucher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parser_queue`
--
ALTER TABLE `parser_queue`
  MODIFY `parser_queue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pavoslidergroups`
--
ALTER TABLE `pavoslidergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pavosliderlayers`
--
ALTER TABLE `pavosliderlayers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_additional_offer`
--
ALTER TABLE `product_additional_offer`
  MODIFY `product_additional_offer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_amzn_offers`
--
ALTER TABLE `product_amzn_offers`
  MODIFY `amazon_offer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_discount`
--
ALTER TABLE `product_discount`
  MODIFY `product_discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_groups`
--
ALTER TABLE `product_groups`
  MODIFY `product_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `product_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_offers_history`
--
ALTER TABLE `product_offers_history`
  MODIFY `offer_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_option`
--
ALTER TABLE `product_option`
  MODIFY `product_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_option_value`
--
ALTER TABLE `product_option_value`
  MODIFY `product_option_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_product_option`
--
ALTER TABLE `product_product_option`
  MODIFY `product_product_option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_product_option_value`
--
ALTER TABLE `product_product_option_value`
  MODIFY `product_product_option_value_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_reward`
--
ALTER TABLE `product_reward`
  MODIFY `product_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sources`
--
ALTER TABLE `product_sources`
  MODIFY `product_source_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_special`
--
ALTER TABLE `product_special`
  MODIFY `product_special_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_special_attribute`
--
ALTER TABLE `product_special_attribute`
  MODIFY `product_special_attribute_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sticker`
--
ALTER TABLE `product_sticker`
  MODIFY `product_sticker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tab`
--
ALTER TABLE `product_tab`
  MODIFY `tab_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_video`
--
ALTER TABLE `product_video`
  MODIFY `product_video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue_mail`
--
ALTER TABLE `queue_mail`
  MODIFY `queue_mail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue_push`
--
ALTER TABLE `queue_push`
  MODIFY `queue_push_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue_sms`
--
ALTER TABLE `queue_sms`
  MODIFY `queue_sms_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `redirect`
--
ALTER TABLE `redirect`
  MODIFY `redirect_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrer_patterns`
--
ALTER TABLE `referrer_patterns`
  MODIFY `pattern_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return`
--
ALTER TABLE `return`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_action`
--
ALTER TABLE `return_action`
  MODIFY `return_action_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_history`
--
ALTER TABLE `return_history`
  MODIFY `return_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_reason`
--
ALTER TABLE `return_reason`
  MODIFY `return_reason_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_status`
--
ALTER TABLE `return_status`
  MODIFY `return_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_name`
--
ALTER TABLE `review_name`
  MODIFY `review_name_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_template`
--
ALTER TABLE `review_template`
  MODIFY `review_template_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `segments`
--
ALTER TABLE `segments`
  MODIFY `segment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `segments_dynamics`
--
ALTER TABLE `segments_dynamics`
  MODIFY `segment_dynamics_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seocities`
--
ALTER TABLE `seocities`
  MODIFY `seocity_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `set`
--
ALTER TABLE `set`
  MODIFY `set_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shoputils_cumulative_discounts`
--
ALTER TABLE `shoputils_cumulative_discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop_rating`
--
ALTER TABLE `shop_rating`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop_rating_answers`
--
ALTER TABLE `shop_rating_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop_rating_custom_types`
--
ALTER TABLE `shop_rating_custom_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop_rating_custom_values`
--
ALTER TABLE `shop_rating_custom_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `short_url_alias`
--
ALTER TABLE `short_url_alias`
  MODIFY `url_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `simple_cart`
--
ALTER TABLE `simple_cart`
  MODIFY `simple_cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_log`
--
ALTER TABLE `sms_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `socnetauth2_customer2account`
--
ALTER TABLE `socnetauth2_customer2account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `socnetauth2_precode`
--
ALTER TABLE `socnetauth2_precode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `socnetauth2_records`
--
ALTER TABLE `socnetauth2_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `special_attribute`
--
ALTER TABLE `special_attribute`
  MODIFY `special_attribute_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sphinx_suggestions`
--
ALTER TABLE `sphinx_suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks_dynamics`
--
ALTER TABLE `stocks_dynamics`
  MODIFY `stock_dynamics_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_status`
--
ALTER TABLE `stock_status`
  MODIFY `stock_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribe`
--
ALTER TABLE `subscribe`
  MODIFY `subscribe_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribe_auth_description`
--
ALTER TABLE `subscribe_auth_description`
  MODIFY `subscribe_auth_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribe_email_description`
--
ALTER TABLE `subscribe_email_description`
  MODIFY `subscribe_desc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_attributes`
--
ALTER TABLE `supplier_attributes`
  MODIFY `supplier_attribute_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_categories`
--
ALTER TABLE `supplier_categories`
  MODIFY `supplier_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `product_supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_class`
--
ALTER TABLE `tax_class`
  MODIFY `tax_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_rate`
--
ALTER TABLE `tax_rate`
  MODIFY `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_rule`
--
ALTER TABLE `tax_rule`
  MODIFY `tax_rule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker`
--
ALTER TABLE `tracker`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trigger_history`
--
ALTER TABLE `trigger_history`
  MODIFY `trigger_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `url_alias`
--
ALTER TABLE `url_alias`
  MODIFY `url_alias_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `user_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_history`
--
ALTER TABLE `voucher_history`
  MODIFY `voucher_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_theme`
--
ALTER TABLE `voucher_theme`
  MODIFY `voucher_theme_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wayforpay_orders`
--
ALTER TABLE `wayforpay_orders`
  MODIFY `wayforpay_order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weight_class`
--
ALTER TABLE `weight_class`
  MODIFY `weight_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weight_class_description`
--
ALTER TABLE `weight_class_description`
  MODIFY `weight_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zone`
--
ALTER TABLE `zone`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zone_to_geo_zone`
--
ALTER TABLE `zone_to_geo_zone`
  MODIFY `zone_to_geo_zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `telegram_users_chats`
--
ALTER TABLE `telegram_users_chats`
  ADD CONSTRAINT `telegram_users_chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `telegram_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `telegram_users_chats_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `telegram_chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
