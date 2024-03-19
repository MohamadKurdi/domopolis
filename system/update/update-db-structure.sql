CREATE TABLE `supplier_manufacturers` (`supplier_manufacturer_id` INT NOT NULL AUTO_INCREMENT , `supplier_id` INT NOT NULL , `manufacturer` VARCHAR(256) NOT NULL , `products` BOOLEAN NOT NULL DEFAULT FALSE , `prices` BOOLEAN NOT NULL DEFAULT FALSE , `stocks` BOOLEAN NOT NULL DEFAULT FALSE , PRIMARY KEY (`supplier_manufacturer_id`), INDEX (`supplier_id`)) ENGINE = InnoDB;