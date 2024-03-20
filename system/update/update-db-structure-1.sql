ALTER TABLE `suppliers` ADD `skip_no_manufacturer` BOOLEAN NOT NULL DEFAULT FALSE AFTER `skip_no_category`;
ALTER TABLE `supplier_manufacturers` ADD UNIQUE(`supplier_id`, `manufacturer`);
ALTER TABLE `supplier_manufacturers` ADD `manufacturer_full` VARCHAR(256) NOT NULL AFTER `manufacturer`;