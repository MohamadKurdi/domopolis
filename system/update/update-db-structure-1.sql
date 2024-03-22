CREATE TABLE `attribute_variants` (`attribute_id` INT(11) NOT NULL , `attribute_variant` VARCHAR(256) NOT NULL , INDEX (`attribute_id`)) ENGINE = InnoDB;
ALTER TABLE `attribute_variants` ADD `attribute_variant_id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`attribute_variant_id`);
ALTER TABLE `attribute_variants` ADD `language_id` INT NOT NULL AFTER `attribute_id`, ADD INDEX (`language_id`);
ALTER TABLE `attribute_variants` ADD INDEX(`attribute_variant`);
CREATE TABLE `attributes_required_category` (`attribute_id` INT NOT NULL , `category_id` INT NOT NULL , INDEX (`category_id`), INDEX (`attribute_id`)) ENGINE = InnoDB;

DROP TABLE IF EXISTS `barbara_blog_category`, `barbara_blog_category_description`, `barbara_blog_category_filter`, `barbara_blog_category_path`, `barbara_blog_category_to_layout`,
`barbara_blog_category_to_store`, `barbara_blog_post`, `barbara_blog_postmeta`, `barbara_blog_post_description`, `barbara_blog_post_filter`, `barbara_blog_post_to_category`,
`barbara_blog_post_to_layout`, `barbara_blog_post_to_store`, `barbara_blog_related_product`, `barbara_blog_setting`, `barbara_blog_setting_general`, `barbara_singleclick`, `barbara_stickers`;