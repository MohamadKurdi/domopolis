DROP TABLE IF EXISTS `attributes_required_category`;
CREATE TABLE IF NOT EXISTS `attributes_required_category` (
                                                              `attribute_id` int(11) NOT NULL,
                                                              `category_id` int(11) NOT NULL,
                                                              KEY `category_id` (`category_id`),
                                                              KEY `attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `attribute_variants`;
CREATE TABLE IF NOT EXISTS `attribute_variants` (
                                                    `attribute_variant_id` int(11) NOT NULL AUTO_INCREMENT,
                                                    `attribute_id` int(11) NOT NULL,
                                                    `language_id` int(11) NOT NULL,
                                                    `attribute_variant` varchar(256) NOT NULL,
                                                    PRIMARY KEY (`attribute_variant_id`),
                                                    KEY `attribute_id` (`attribute_id`),
                                                    KEY `attribute_variant` (`attribute_variant`),
                                                    KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `supplier_manufacturers`;
CREATE TABLE IF NOT EXISTS `supplier_manufacturers` (
                                                        `supplier_manufacturer_id` int(11) NOT NULL AUTO_INCREMENT,
                                                        `supplier_id` int(11) NOT NULL,
                                                        `manufacturer` varchar(256) NOT NULL,
                                                        `manufacturer_full` varchar(256) NOT NULL,
                                                        `products` tinyint(1) NOT NULL DEFAULT 0,
                                                        `prices` tinyint(1) NOT NULL DEFAULT 0,
                                                        `stocks` tinyint(1) NOT NULL DEFAULT 0,
                                                        PRIMARY KEY (`supplier_manufacturer_id`),
                                                        UNIQUE KEY `supplier_id_2` (`supplier_id`,`manufacturer`),
                                                        KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;