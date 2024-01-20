<?php
	class ModelCatalogProduct extends Model {
		
		public function addProduct($data) {
			
			if (!isset($data['is_markdown'])){
				$data['is_markdown'] = 0;				
			}
			
			if (!isset($data['markdown_product_id'])){
				$data['markdown_product_id'] = 0;				
			}

			if (!isset($data['big_business'])){
				$data['big_business'] = 0;				
			}
			
			if ($data['is_markdown']){
				$data['main_category_id'] = GENERAL_MARKDOWN_CATEGORY;	
				$data['product_category'] = array(GENERAL_MARKDOWN_CATEGORY);	
			}
			
			$this->db->query("INSERT INTO product SET 
				model 				= '" . $this->db->escape($data['model']) . "',
				short_name 			= '" . $this->db->escape($data['short_name']) . "',
				short_name_de 		= '" . $this->db->escape($data['short_name_de']) . "',
				sku 				= '" . $this->db->escape($data['sku']) . "',
				upc 				= '" . $this->db->escape($data['upc']) . "',
				ean 				= '" . $this->db->escape($data['ean']) . "',
				jan 				= '" . $this->db->escape($data['jan']) . "',
				isbn 				= '" . $this->db->escape($data['isbn']) . "',
				mpn 				= '" . $this->db->escape($data['mpn']) . "',
				asin 				= '" . $this->db->escape($data['asin']) . "',
				location 			= '" . $this->db->escape($data['location']) . "',
				color_group 		= '" . $this->db->escape($data['color_group']) . "',
				source 				= '" . $this->db->escape($data['source']) . "',
				competitors 		= '" . $this->db->escape($data['competitors']) . "',
				competitors_ua 		= '" . $this->db->escape($data['competitors_ua']) . "',
				quantity 			= '" . (int)$data['quantity'] . "',
				minimum 			= '" . (int)$data['minimum'] . "',
				package 			= '" . (int)$data['package'] . "',
				subtract 			= '" . (int)$data['subtract'] . "',
				has_child 			= '" . (int)$data['has_child'] . "',
				stock_status_id 	= '" . (int)$data['stock_status_id'] . "',
				date_available 		= '" . $this->db->escape($data['date_available']) . "',
				manufacturer_id 	= '" . (int)$data['manufacturer_id'] . "',
				collection_id 		= '" . (int)$data['collection_id'] . "',
				shipping 			= '" . (int)$data['shipping'] . "',
				price 				= '" . (float)$data['price'] . "',
				price_delayed 		= '" . (float)$data['price_delayed'] . "',
				price_special		= '" . (float)$data['price_special'] . "',
				price_special_delayed = '" . (float)$data['price_special_delayed'] . "',
				mpp_price 			= '" . (float)$data['mpp_price'] . "',
				cost 				= '" . (float)$data['cost'] . "',
				costprice 			= '" . (float)$data['costprice'] . "',
				price_national 		= '" . (float)$data['price_national'] . "',
				currency 			= '" . $this->db->escape($data['currency']) . "',
				points 				= '" . (int)$data['points'] . "',
				weight 				= '" . (float)$data['weight'] . "',
				weight_class_id 	= '" . (int)$data['weight_class_id'] . "',
				weight_amazon_key 	= '" . $this->db->escape($data['weight_amazon_key']) . "',
				length 				= '" . (float)$data['length'] . "',
				width 				= '" . (float)$data['width'] . "',
				height 				= '" . (float)$data['height'] . "',
				length_class_id 	= '" . (int)$data['length_class_id'] . "',
				length_amazon_key 	= '" . $this->db->escape($data['length_amazon_key']) . "',
				pack_weight 		= '" . (float)$data['pack_weight'] . "',
				pack_weight_class_id 	= '" . (int)$data['pack_weight_class_id'] . "',
				pack_weight_amazon_key 	= '" . $this->db->escape($data['pack_weight_amazon_key']) . "',
				pack_length 			= '" . (float)$data['pack_length'] . "',
				pack_width 			= '" . (float)$data['pack_width'] . "',
				pack_height 		= '" . (float)$data['pack_height'] . "',
				pack_length_class_id 	= '" . (int)$data['pack_length_class_id'] . "',
				pack_length_amazon_key 	= '" . $this->db->escape($data['pack_length_amazon_key']) . "',
				status 				= '" . (int)$data['status'] . "',
				tax_class_id 		= '" . $this->db->escape($data['tax_class_id']) . "',
				sort_order 			= '" . (int)$data['sort_order'] . "',
				quantity_stock 		= '" . (int)$data['quantity_stock'] . "',
				min_buy 			= '" . (int)$data['min_buy'] . "',
				max_buy 			= '" . (int)$data['max_buy'] . "',
				quantity_stockM 	= '" . (int)$data['quantity_stockM'] . "',
				quantity_stockK 	= '" . (int)$data['quantity_stockK'] . "',
				tnved 				= '" . $data['tnved'] . "',
				ignore_parse 		= '" . (int)$data['ignore_parse'] . "',
				big_business 		= '" . (int)$data['big_business'] . "',
				new 				= '" . (int)$data['new'] . "',
				ignore_parse_date_to 	= '" .  $this->db->escape($data['ignore_parse_date_to']) . "',
				new_date_to 			= '" .  $this->db->escape($data['new_date_to']) . "',
				is_markdown 			= '" . (int)$data['is_markdown'] . "',
				markdown_product_id 	= '" . (int)$data['markdown_product_id'] . "',
				main_variant_id 		= '" . (int)$data['main_variant_id'] . "',
				variant_1_is_color 		= '" . (int)$data['variant_1_is_color'] . "',
				variant_2_is_color 		= '" . (int)$data['variant_2_is_color'] . "',
				display_in_catalog 		= '" . (int)$data['display_in_catalog'] . "',
				is_option_with_id 		= '" . (int)$data['is_option_with_id'] . "',
				yam_disable 		= '" . (int)$data['yam_disable'] . "',
				is_illiquid 		= '" . (int)$data['is_illiquid'] . "',
				yam_price 			= '" . (float)$data['yam_price'] . "',
				yam_percent 		= '" . (float)$data['yam_percent'] . "',
				yam_special 		= '" . (float)$data['yam_special'] . "',
				yam_special_percent = '" . (float)$data['yam_special_percent'] . "',
				yam_currency 		= '" . $this->db->escape($data['yam_currency']) . "',
				yam_product_id 		= '" . "',
				priceva_enable 		= '" . (int)$data['priceva_enable'] . "',
				priceva_disable 	= '" . (int)$data['priceva_disable'] . "',
				hotline_disable 	= '" . (int)$data['hotline_disable'] . "',
				amzn_ignore 		= '" . (int)$data['amzn_ignore'] . "',
				date_added 			= NOW()");
			
			$product_id = $this->db->getLastId();

			$this->db->query("UPDATE product SET yam_product_id = '" . $this->db->escape($this->config->get('config_yam_offer_id_prefix') . $product_id) . "' WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['youtube'])) {
				$this->db->query("UPDATE product SET youtube = '" . $this->db->escape($data['youtube']) . "' WHERE product_id = '" . (int)$product_id . "'");
			}	
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
				
				if (isset($data['def_img']) && $data['def_img'] != "") {
					$this->db->query("UPDATE product SET image = '" . $this->db->escape($data['def_img']) . "' WHERE product_id = '" . (int)$product_id . "'");  
				}
			}

			$manufacturer_name_overloaded = false;
			if (!empty($data['manufacturer_id'])){
				$query = $this->db->query("SELECT name FROM manufacturer WHERE manufacturer_id = '" . (int)$data['manufacturer_id'] . "'");

				if ($query->num_rows && !empty($query->row['name'])){
					$manufacturer_name_overloaded = $query->row['name'];
				}
			}
			
			foreach ($data['product_description'] as $language_id => $value) {

				if ($manufacturer_name_overloaded){
					$value['manufacturer_name'] = $manufacturer_name_overloaded;
				}

				$this->db->query("INSERT INTO product_description SET 
					product_id 			= '" . (int)$product_id . "', 
					language_id 		= '" . (int)$language_id . "', 
					name 				= '" . $this->db->escape($value['name']) . "', 
					short_name_d 		= '" . $this->db->escape($value['short_name_d']) . "', 
					name_of_option 		= '" . $this->db->escape($value['name_of_option']) . "', 
					meta_keyword 		= '" . $this->db->escape($value['meta_keyword']) . "', 
					seo_title 			= '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', 
					seo_h1 				= '" . ((isset($value['seo_h1']))?($this->db->escape($value['seo_h1'])):'') . "', 
					meta_description 	= '" . $this->db->escape($value['meta_description']) . "', 
					description 		= '" . $this->db->escape($value['description']) . "',
					description_full 	= '" . ((isset($value['description_full']))?($this->db->escape($value['description_full'])):'') . "',  
					tag 				= '" . $this->db->escape($value['tag']) . "', 
					color 				= '" . $this->db->escape($value['color']) . "', 
					material 			= '" . $this->db->escape($value['material']) . "', 
					variant_name 		= '" . $this->db->escape($value['variant_name']) . "', 
					variant_name_1  	= '" . $this->db->escape($value['variant_name_1']) . "', 
					variant_name_2 		= '" . $this->db->escape($value['variant_name_2']) . "', 
					variant_value_1  	= '" . $this->db->escape($value['variant_value_1']) . "', 
					variant_value_2 	= '" . $this->db->escape($value['variant_value_2']) . "',
					markdown_appearance = '" . $this->db->escape($value['markdown_appearance']) . "', 
					markdown_condition 	= '" . $this->db->escape($value['markdown_condition']) . "', 
					markdown_pack 		= '" . $this->db->escape($value['markdown_pack']) . "', 
					markdown_equipment 	= '" . $this->db->escape($value['markdown_equipment']) . "', 
					manufacturer_name 	= '" . $this->db->escape($value['manufacturer_name']) . "',
					translated 			= '" . (int)$value['translated'] . "'");
			}
			
			if (isset($data['product_store'])) {
				foreach ($data['product_store'] as $store_id) {
					$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}
			}

			if ($this->config->get('ukrcredits_status')){
				if (isset($data['partscount_pp'])) {
					if ($data['partscount_pp'] || $data['partscount_ii'] || $data['partscount_mb'] || $data['product_pp'] || $data['product_ii'] || $data['product_mb'] || $data['markup_pp'] || $data['markup_ii'] || $data['markup_mb']) {
						$this->db->query("DELETE FROM product_ukrcredits WHERE product_id = '" . (int)$product_id . "'");

						$this->db->query("INSERT INTO product_ukrcredits SET product_id = '" . (int)$product_id . "', product_ii = '" . $this->db->escape($data['product_ii']) . "', product_pp = '" . $this->db->escape($data['product_pp']) . "', product_mb = '" . $this->db->escape($data['product_mb']) . "', partscount_pp = '" . $this->db->escape($data['partscount_pp']) . "', partscount_ii = '" . $this->db->escape($data['partscount_ii']) . "', partscount_mb = '" . $this->db->escape($data['partscount_mb']) . "', markup_pp = '" . $this->db->escape($data['markup_pp']) . "', markup_ii = '" . $this->db->escape($data['markup_ii']) . "', markup_mb = '" . $this->db->escape($data['markup_mb']) . "'");
					}
				}
			}
			
			if (isset($data['product_attribute'])) {
				foreach ($data['product_attribute'] as $product_attribute) {
					if ($product_attribute['attribute_id']) {
						$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
						
						foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
							$this->db->query("INSERT INTO product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
						}
					}
				}
			}

			if ($this->config->get('config_use_separate_table_for_features') && isset($data['product_feature'])) {
				foreach ($data['product_feature'] as $product_feature) {
					if ($product_feature['feature_id']) {
						$this->db->query("DELETE FROM product_feature WHERE product_id = '" . (int)$product_id . "' AND feature_id = '" . (int)$product_feature['feature_id'] . "'");
						
						foreach ($product_feature['product_feature_description'] as $language_id => $product_feature_description) {				
							$this->db->query("INSERT INTO product_feature SET product_id = '" . (int)$product_id . "', feature_id = '" . (int)$product_feature['feature_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_feature_description['text']) . "'");
						}
					}
				}
			}
			
			if (isset($data['product_option'])) {
				
				$this->addBatchOptions($data);
				
				foreach ($data['product_option'] as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'block' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						$this->db->query("INSERT INTO product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
						
						$product_option_id = $this->db->getLastId();
						
						if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
							foreach ($product_option['product_option_value'] as $product_option_value) {
								$this->db->query("INSERT INTO product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
								
								//Q: Options Boost
								$ob_pov_id = $this->db->getLastId();
								if(isset($product_option_value['ob_sku'])) { $this->db->query("UPDATE product_option_value SET ob_sku = '" . $this->db->escape($product_option_value['ob_sku']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_info'])) { $this->db->query("UPDATE product_option_value SET ob_info = '" . $this->db->escape($product_option_value['ob_info']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_image'])) { $this->db->query("UPDATE product_option_value SET ob_image = '" . $this->db->escape($product_option_value['ob_image']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_sku_override'])) { $this->db->query("UPDATE product_option_value SET ob_sku_override = '" . $this->db->escape($product_option_value['ob_sku_override']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								unset($ob_pov_id);
								//
							} 
							}else{
							$this->db->query("DELETE FROM product_option WHERE product_option_id = '".$product_option_id."'");
						}
						} else { 
						$this->db->query("INSERT INTO product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
					}
				}
			}

			$this->db->query("DELETE FROM product_stock_status WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_stock_status'])) {			
				foreach ($data['product_stock_status'] as $key => $value){					
					if ($value > 0) {						
						$this->db->query("INSERT INTO product_stock_status SET store_id = '" . (int)$key . "', product_id = '" . (int)$product_id . "', stock_status_id = '" . (int)$value . "'");						
					}
				}				
			}
			
			if (!empty($data['product_product_option'])) {
				foreach ($data['product_product_option'] as $product_product_option) {
					
					$this->db->query("INSERT INTO product_product_option SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$product_product_option['category_id'] . "', type = '" . $this->db->escape($product_product_option['type']) . "', required = '" . (int)$product_product_option['required'] . "', sort_order = '" . (int)$product_product_option['sort_order'] . "'");
					
					$product_product_option_id = $this->db->getLastId();
					
					if (!empty($product_product_option['product_option'])) {
						foreach ($product_product_option['product_option'] as $product_option => $value) {
							$this->db->query("INSERT INTO product_product_option_value SET product_product_option_id = '" . (int)$product_product_option_id . "', product_id = '" . (int)$product_id . "', product_option_id = '" . (int)$value['product_id'] . "', sort_order = '" . (int)$value['sort_order'] . "'");
						} 
					}
				}
			}
			
			if (isset($data['product_discount'])) {
				foreach ($data['product_discount'] as $product_discount) {
					$this->db->query("INSERT INTO product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
				}
			}
			
			if (isset($data['product_special'])) {
				foreach ($data['product_special'] as $product_special) {
					$this->db->query("INSERT INTO product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', store_id = '" . (int)$product_special['store_id'] . "', currency_scode = '" . $this->db->escape($product_special['currency_scode']) . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
				}
			}
			
			if (isset($data['product_image'])) {
				foreach ($data['product_image'] as $product_image) {
					
					if ($this->config->get('multiimageuploader_deletedef') && isset($data['def_img']) && $data['def_img'] == $product_image['image']) { continue;}
					if ($this->config->get('pim_deletedef') && isset($data['def_img']) && $data['def_img'] == $product_image['image']) { continue;}
					
					$this->db->query("INSERT INTO product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
				}
			}

			if (isset($data['product_video'])) {
				foreach ($data['product_video'] as $product_video) {									
					$this->db->query("INSERT INTO product_video SET product_id = '" . (int)$product_id . "', video = '" . $this->db->escape(html_entity_decode($product_video['video'], ENT_QUOTES, 'UTF-8')) . "', image = '" . $this->db->escape(html_entity_decode($product_video['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_video['sort_order'] . "'");

					$product_video_id = $this->db->getLastId();

					foreach ($product_video['product_video_description'] as $language_id => $value) {
						$this->db->query("INSERT INTO product_video_description SET 
							product_video_id 	= '" . (int)$product_video_id . "',
							language_id 		= '" . (int)$language_id . "',
							product_id 			= '" . (int)$product_id . "',
							title 				= '" . $this->db->escape($value['title']) . "'");
					}
				}
			}
			
			$this->db->query("DELETE FROM ocfilter_option_value_to_product WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM ocfilter_option_value_to_product_description WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['ocfilter_product_option'])) {
				foreach ($data['ocfilter_product_option'] as $option_id => $values) {
					foreach ($values['values'] as $value_id => $value) {
						if (isset($value['selected'])) {
							$this->db->query("INSERT INTO ocfilter_option_value_to_product SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_id . "', value_id = '" . $this->db->escape($value_id) . "', slide_value_min = '" . (isset($value['slide_value_min']) ? (float)$value['slide_value_min'] : 0) . "', slide_value_max = '" . (isset($value['slide_value_max']) ? (float)$value['slide_value_max'] : 0) . "'");
							
							foreach ($value['description'] as $language_id => $description) {
								if (trim($description['description'])) {
									$this->db->query("INSERT INTO ocfilter_option_value_to_product_description SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_id . "', value_id = '" . $this->db->escape($value_id) . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($description['description']) . "'");
								}
							}
						}
					}
				}
			}
			
			if (isset($data['product_download'])) {
				foreach ($data['product_download'] as $download_id) {
					$this->db->query("INSERT INTO product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				}
			}
			
			if (isset($data['product_category'])) {
				foreach ($data['product_category'] as $category_id) {
					$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
				}
			}
			
			if (isset($data['faproduct_facategory']) && $data['faproduct_facategory'] && count($data['faproduct_facategory']) > 0) {				
				foreach ($data['faproduct_facategory'] as $category_id) {
					$this->db->query("INSERT INTO faproduct_to_facategory SET 
					product_id = '" . (int)$product_id . "', 
					facategory_id = '" . (int)$category_id . "'"
					);
				}
			}
			if (isset($data['facategory_show'])) {
				$this->db->query("INSERT INTO facategory_to_faproduct SET 
				product_id = '" . (int)$product_id . "', 
				facategory_id = '" . (int)$data['facategory_show'] . "'"
				);
			}
			
			if (isset($data['product_filter'])) {
				foreach ($data['product_filter'] as $filter_id) {
					$this->db->query("INSERT INTO product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}

			if (isset($data['product_additional_offer'])) {
				foreach ($data['product_additional_offer'] as $product_additional_offer) {
					$this->db->query("INSERT INTO product_additional_offer SET 
						product_id 			= '" . (int)$product_id . "', 
						customer_group_id 	= '" . (int)$product_additional_offer['customer_group_id'] . "', 
						priority 			= '" . (int)$product_additional_offer['priority'] . "', 
						quantity 			= '" . (int)$product_additional_offer['quantity'] . "', 
						ao_product_id 		= '" . (int)$product_additional_offer['ao_product_id'] . "', 
						price 				= '" . (float)$product_additional_offer['price'] . "', 
						percent 			= '" . (int)$product_additional_offer['percent'] . "', 
						date_start 			= '" . $this->db->escape($product_additional_offer['date_start']) . "', 
						date_end 			= '" . $this->db->escape($product_additional_offer['date_end']) . "', 
						image 				= '" . $this->db->escape($product_additional_offer['image']) . "', 
						ao_group 			= '" . $this->db->escape($product_additional_offer['ao_group']) . "', 
						description 		= '" . $this->db->escape($product_additional_offer['description']) . "'");
				}
			}
			
			if (isset($data['product_related'])) {
				foreach ($data['product_related'] as $related_id) {
					$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
					$this->db->query("INSERT INTO product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
					$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
				}
			}
			
			if (isset($data['product_similar'])) {
				foreach ($data['product_similar'] as $similar_id) {
					$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$product_id . "' AND similar_id = '" . (int)$similar_id . "'");
					$this->db->query("INSERT INTO product_similar SET product_id = '" . (int)$product_id . "', similar_id = '" . (int)$similar_id . "'");
					$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$similar_id . "' AND similar_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_similar SET product_id = '" . (int)$similar_id . "', similar_id = '" . (int)$product_id . "'");
				}
			}

			if (isset($data['product_sponsored'])) {
				foreach ($data['product_sponsored'] as $sponsored_id) {
					$this->db->query("DELETE FROM product_sponsored WHERE product_id = '" . (int)$product_id . "' AND sponsored_id = '" . (int)$sponsored_id . "'");
					$this->db->query("INSERT INTO product_sponsored SET product_id = '" . (int)$product_id . "', sponsored_id = '" . (int)$sponsored_id . "'");
					$this->db->query("DELETE FROM product_sponsored WHERE product_id = '" . (int)$sponsored_id . "' AND sponsored_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_sponsored SET product_id = '" . (int)$sponsored_id . "', sponsored_id = '" . (int)$product_id . "'");
				}
			}

			
			if (isset($data['product_similar_to_consider'])) {
				foreach ($data['product_similar_to_consider'] as $similar_to_consider_id) {
					$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$product_id . "' AND similar_to_consider_id = '" . (int)$similar_to_consider_id . "'");
					$this->db->query("INSERT INTO product_similar_to_consider SET product_id = '" . (int)$product_id . "', similar_to_consider_id = '" . (int)$similar_to_consider_id . "'");
					$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$similar_to_consider_id . "' AND similar_to_consider_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_similar_to_consider SET product_id = '" . (int)$similar_to_consider_id . "', similar_to_consider_id = '" . (int)$product_id . "'");
				}
			}

			
			if (isset($data['product_view_to_purchase'])) {
				foreach ($data['product_view_to_purchase'] as $view_to_purchase_id) {
					$this->db->query("DELETE FROM product_view_to_purchase WHERE product_id = '" . (int)$product_id . "' AND view_to_purchase_id = '" . (int)$view_to_purchase_id . "'");
					$this->db->query("INSERT INTO product_view_to_purchase SET product_id = '" . (int)$product_id . "', view_to_purchase_id = '" . (int)$view_to_purchase_id . "'");
					$this->db->query("DELETE FROM product_view_to_purchase WHERE product_id = '" . (int)$view_to_purchase_id . "' AND view_to_purchase_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_view_to_purchase SET product_id = '" . (int)$view_to_purchase_id . "', view_to_purchase_id = '" . (int)$product_id . "'");
				}
			}
			
			if (isset($data['product_also_viewed'])) {
				foreach ($data['product_also_viewed'] as $also_viewed_id) {
					$this->db->query("DELETE FROM product_also_viewed WHERE product_id = '" . (int)$product_id . "' AND also_viewed_id = '" . (int)$also_viewed_id . "'");
					$this->db->query("INSERT INTO product_also_viewed SET product_id = '" . (int)$product_id . "', also_viewed_id = '" . (int)$also_viewed_id . "'");
					$this->db->query("DELETE FROM product_also_viewed WHERE product_id = '" . (int)$also_viewed_id . "' AND also_viewed_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_also_viewed SET product_id = '" . (int)$also_viewed_id . "', also_viewed_id = '" . (int)$product_id . "'");
				}
			}
			
			if (isset($data['product_also_bought'])) {
				foreach ($data['product_also_bought'] as $also_bought_id) {
					$this->db->query("DELETE FROM product_also_bought WHERE product_id = '" . (int)$product_id . "' AND also_bought_id = '" . (int)$also_bought_id . "'");
					$this->db->query("INSERT INTO product_also_bought SET product_id = '" . (int)$product_id . "', also_bought_id = '" . (int)$also_bought_id . "'");
					$this->db->query("DELETE FROM product_also_bought WHERE product_id = '" . (int)$also_bought_id . "' AND also_bought_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_also_bought SET product_id = '" . (int)$also_bought_id . "', also_bought_id = '" . (int)$product_id . "'");
				}
			}

			if (isset($data['product_shop_by_look'])) {
				foreach ($data['product_shop_by_look'] as $shop_by_look_id) {
					$this->db->query("DELETE FROM product_shop_by_look WHERE product_id = '" . (int)$product_id . "' AND shop_by_look_id = '" . (int)$shop_by_look_id . "'");
					$this->db->query("INSERT INTO product_shop_by_look SET product_id = '" . (int)$product_id . "', shop_by_look_id = '" . (int)$shop_by_look_id . "'");
					$this->db->query("DELETE FROM product_shop_by_look WHERE product_id = '" . (int)$shop_by_look_id . "' AND shop_by_look_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_shop_by_look SET product_id = '" . (int)$shop_by_look_id . "', shop_by_look_id = '" . (int)$product_id . "'");
				}
			}
			
			$this->db->query("DELETE FROM product_child WHERE product_id = '" . (int)$product_id . "'");
			if (isset($data['product_child'])) {									
				foreach ($data['product_child'] as $child_id) {
					$this->db->query("INSERT INTO product_child SET product_id = '" . (int)$product_id . "', child_id = '" . (int)$child_id . "'");
				}
			}

		
			
			if (isset($data['main_category_id']) && $data['main_category_id'] > 0) {
				
				$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['main_category_id'] . "'");
				$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['main_category_id'] . "', main_category = 1");
				} elseif (isset($data['product_category'][0])) {
				$this->db->query("UPDATE product_to_category SET main_category = 1 WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['product_category'][0] . "'");
			}
			
			$this->db->query("DELETE FROM product_reward WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_reward'])) {
				foreach ($data['product_reward'] as $reward){	
					$this->db->query("INSERT INTO product_reward SET product_id = '" . (int)$product_id . "', 
					customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "', 
					points		= '" . (int)$reward['points'] . "',
					store_id 	= '" . (int)$reward['store_id']. "',
					percent 	= '" . (int)$reward['percent'] . "',
					date_end 	= '" . (int)$reward['date_end'] . "',
					date_start 	= '" . (int)$reward['date_start'] . "',
					coupon_acts = '" . (int)$reward['coupon_acts'] . "'");
				}
			}
			
			if (isset($data['product_layout'])) {
				foreach ($data['product_layout'] as $store_id => $layout) {
					if ($layout['layout_id']) {
						$this->db->query("INSERT INTO product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}
			
			if ($this->url->checkIfGenerate('product_id')){
				if ($data['keyword']) {
					foreach ($data['keyword'] as $language_id => $keyword) {
						if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
					}
				}
			}
			
			if (isset($data['product_profiles'])) {
				foreach ($data['product_profiles'] as $profile) {
					$this->db->query("INSERT INTO `product_profile` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$profile['customer_group_id'] . ", `profile_id` = " . (int)$profile['profile_id']);
				}
			} 

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'add', 'entity_type' => 'product', 'entity_id' => $product_id]);
			
			
			return (int)$product_id;
		}

		public function editProductVariantDescriptions($products, $data){
		}
		
		public function editProduct($product_id, $data) {
			
			if (!isset($data['has_child'])){
				$data['has_child'] = 0;
			}
			
			if (!isset($data['ignore_parse'])){
				$data['ignore_parse'] = 0;				
			}
			
			if (!isset($data['new'])){
				$data['new'] = 0;				
			}
			
			if (!isset($data['is_markdown'])){
				$data['is_markdown'] = 0;				
			}
			
			if (!isset($data['big_business'])){
				$data['big_business'] = 0;				
			}
			
			if (!isset($data['markdown_product_id'])){
				$data['markdown_product_id'] = 0;				
			}
			
			if ($data['is_markdown']){
				$data['main_category_id'] = GENERAL_MARKDOWN_CATEGORY;
				$data['product_category'] = array(GENERAL_MARKDOWN_CATEGORY);	
			}
			
			$this->db->query("UPDATE product SET 
				model 					= '" . $this->db->escape($data['model']) . "', 
				short_name 				= '" . $this->db->escape($data['short_name']) . "', 
				short_name_de 			= '" . $this->db->escape($data['short_name_de']) . "', 
				sku 					= '" . $this->db->escape($data['sku']) . "',
				upc 					= '" . $this->db->escape($data['upc']) . "', 
				ean 					= '" . $this->db->escape($data['ean']) . "', 
				jan 					= '" . $this->db->escape($data['jan']) . "', 
				isbn 					= '" . $this->db->escape($data['isbn']) . "', 
				mpn 					= '" . $this->db->escape($data['mpn']) . "', 
				asin 					= '" . $this->db->escape($data['asin']) . "', 
				old_asin 				= '" . $this->db->escape($data['old_asin']) . "', 
				color_group 			= '" . $this->db->escape($data['color_group']) . "', 
				location 				= '" . $this->db->escape($data['location']) . "', 
				source 					= '" . $this->db->escape($data['source']) . "', 
				competitors 			= '" . $this->db->escape($data['competitors']) . "', 
				competitors_ua 			= '" . $this->db->escape($data['competitors_ua']) . "', 
				quantity 				= '" . (int)$data['quantity'] . "', 
				minimum 				= '" . (int)$data['minimum'] . "', 
				package 				= '" . (int)$data['package'] . "', 
				subtract 				= '" . (int)$data['subtract'] . "', 
				has_child 				= '" . (int)$data['has_child'] . "', 
				stock_status_id 		= '" . (int)$data['stock_status_id'] . "', 
				date_available 			= '" . $this->db->escape($data['date_available']) . "', 
				date_added 				= '" . $this->db->escape($data['date_added']) . "', 
				manufacturer_id 		= '" . (int)$data['manufacturer_id'] . "',
				collection_id 			= '" . (int)$data['collection_id'] . "', 
				shipping 				= '" . (int)$data['shipping'] . "', 
				price 					= '" . (float)$data['price'] . "', 
				price_delayed 			= '" . (float)$data['price_delayed'] . "',
				price_special			= '" . (float)$data['price_special'] . "',
				price_special_delayed 	= '" . (float)$data['price_special_delayed'] . "',
				mpp_price 				= '" . (float)$data['mpp_price'] . "', 
				cost 					= '" . (float)$data['cost'] . "',
				costprice 				= '" . (float)$data['costprice'] . "', 
				special_cost 			= '" . (float)$data['special_cost'] . "', 
				price_national 			= '" . (float)$data['price_national'] . "', 
				currency 				= '" . $this->db->escape($data['currency']) . "',  
				points 					= '" . (int)$data['points'] . "', 
				weight 					= '" . (float)$data['weight'] . "', 
				weight_class_id 		= '" . (int)$data['weight_class_id'] . "', 
				weight_amazon_key 		= '" . $this->db->escape($data['weight_amazon_key']) . "',
				length 					= '" . (float)$data['length'] . "', 
				width 					= '" . (float)$data['width'] . "', 
				height 					= '" . (float)$data['height'] . "', 
				length_class_id 		= '" . (int)$data['length_class_id'] . "', 
				length_amazon_key 		= '" . $this->db->escape($data['length_amazon_key']) . "', 
				pack_weight 			= '" . (float)$data['pack_weight'] . "', 
				pack_weight_class_id 	= '" . (int)$data['pack_weight_class_id'] . "', 
				pack_weight_amazon_key 	= '" . $this->db->escape($data['pack_weight_amazon_key']) . "', 
				pack_length 			= '" . (float)$data['pack_length'] . "', 
				pack_width 				= '" . (float)$data['pack_width'] . "',
				pack_height 			= '" . (float)$data['pack_height'] . "', 
				pack_length_class_id 	= '" . (int)$data['pack_length_class_id'] . "', 
				pack_length_amazon_key 	= '" . $this->db->escape($data['pack_length_amazon_key']) . "', 
				status 					= '" . (int)$data['status'] . "', 
				tax_class_id 			= '" . $this->db->escape($data['tax_class_id']) . "', 
				sort_order 				= '" . (int)$data['sort_order'] . "', 
				quantity_stock 			= '" . (int)$data['quantity_stock'] . "', 
				min_buy 				= '" . (int)$data['min_buy'] . "', 
				max_buy 				= '" . (int)$data['max_buy'] . "', 
				quantity_stockM 		= '" . (!empty($data['quantity_stockM'])?(int)$data['quantity_stockM']:'0') . "', 
				quantity_stockK 		= '" . (!empty($data['quantity_stockK'])?(int)$data['quantity_stockK']:'0') . "',
				quantity_stockMN 		= '" . (!empty($data['quantity_stockMN'])?(int)$data['quantity_stockMN']:'0') . "', 
				quantity_stockAS 		= '" . (!empty($data['quantity_stockAS'])?(int)$data['quantity_stockAS']:'0') . "', 
				quantity_stockM_onway 	= '" . (!empty($data['quantity_stockM_onway'])?(int)$data['quantity_stockM_onway']:'0') . "', 
				quantity_stockK_onway 	= '" . (!empty($data['quantity_stockK_onway'])?(int)$data['quantity_stockK_onway']:'0') . "',
				quantity_stockMN_onway 	= '" . (!empty($data['quantity_stockMN_onway'])?(int)$data['quantity_stockMN_onway']:'0') . "', 
				quantity_stockAS_onway 	= '" . (!empty($data['quantity_stockAS_onway'])?(int)$data['quantity_stockAS_onway']:'0') . "', 
				tnved 					= '" . $data['tnved'] . "', 
				ignore_parse 			= '" . (int)$data['ignore_parse'] . "', 
				big_business 			= '" . (int)$data['big_business'] . "', 
				new 					= '" . (int)$data['new'] . "', 
				ignore_parse_date_to 	= '" .  $this->db->escape($data['ignore_parse_date_to']) . "', 
				new_date_to 			= '" .  $this->db->escape($data['new_date_to']) . "', 
				is_markdown 			= '" . (int)$data['is_markdown'] . "',  
				markdown_product_id 	= '" . (int)$data['markdown_product_id'] . "', 
				main_variant_id 		= '" . (int)$data['main_variant_id'] . "', 
				variant_1_is_color		= '" . (int)$data['variant_1_is_color'] . "', 
				variant_2_is_color 		= '" . (int)$data['variant_2_is_color'] . "', 
				display_in_catalog 		= '" . (int)$data['display_in_catalog'] . "',  
				is_option_with_id 		= '" . (int)$data['is_option_with_id'] . "', 
				yam_disable 			= '" . (int)$data['yam_disable'] . "', 
				is_illiquid 			= '" . (int)$data['is_illiquid'] . "', 
				yam_price 				= '" . (float)$data['yam_price'] . "', 
				yam_percent 			= '" . (float)$data['yam_percent'] . "', 
				yam_special 			= '" . (float)$data['yam_special'] . "', 
				yam_special_percent 	= '" . (float)$data['yam_special_percent'] . "', 
				yam_currency 			= '" . $this->db->escape($data['yam_currency']) . "', 
				yam_product_id 			= '" . $this->db->escape($this->config->get('config_yam_offer_id_prefix') . $product_id) . "', 
				priceva_enable 			= '" . (int)$data['priceva_enable'] . "', 
				priceva_disable 		= '" . (int)$data['priceva_disable'] . "',
				hotline_disable 		= '" . (int)$data['hotline_disable'] . "',
				amzn_last_search 		= '" . $this->db->escape($data['amzn_last_search']) . "', 
				amzn_ignore 			= '" . (int)$data['amzn_ignore'] . "',  
				fill_from_amazon 		= '" . (int)$data['fill_from_amazon'] . "', 
				amzn_last_offers 		= '" . $this->db->escape($data['amzn_last_offers']) . "',  
				date_modified 			= NOW() 
				WHERE product_id = '" . (int)$product_id . "'");

			if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']){
				$this->db->query("UPDATE product SET status = '" . (int)$data['status'] . "' 	WHERE main_variant_id = '" . (int)$product_id . "'");              
                $this->db->query("UPDATE product SET minimum = '" . (int)$data['minimum'] . "' 	WHERE main_variant_id = '" . (int)$product_id . "'");
                $this->db->query("UPDATE product SET shipping = '" . (int)$data['shipping'] . "' WHERE main_variant_id = '" . (int)$product_id . "'");				
			}
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
				
				if (isset($data['def_img']) && $data['def_img'] != "") {
					$this->db->query("UPDATE product SET image = '" . $this->db->escape($data['def_img']) . "' WHERE product_id = '" . (int)$product_id . "'");  
				}
			}
			
			if (isset($data['youtube'])) {
				$this->db->query("UPDATE product SET youtube = '" . $this->db->escape($data['youtube']) . "' WHERE product_id = '" . (int)$product_id . "'");
			}		
			
			$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
			
			$fields_to_copy = [
					'color',
					'material',
					'variant_name',
					'variant_name_1',
					'variant_name_2',
					'variant_value_1',
					'variant_value_2',
					'manufacturer_name'
				];			

			$copy_product_description 	= [];
			$rainforest_source_text		= [];

			$manufacturer_name_overloaded = false;
			if (!empty($data['manufacturer_id'])){
				$query = $this->db->query("SELECT name FROM manufacturer WHERE manufacturer_id = '" . (int)$data['manufacturer_id'] . "'");

				if ($query->num_rows && !empty($query->row['name'])){
					$manufacturer_name_overloaded = $query->row['name'];
				}
			}

			foreach ($data['product_description'] as $language_id => $value) {	

				if ($manufacturer_name_overloaded){
					$value['manufacturer_name'] = $manufacturer_name_overloaded;
				}				

				$this->db->query("INSERT INTO product_description SET 
					product_id 			= '" . (int)$product_id . "', 
					language_id 		= '" . (int)$language_id . "', 
					name 				= '" . $this->db->escape($value['name']) . "', 
					short_name_d 		= '" . $this->db->escape($value['short_name_d']) . "', 
					name_of_option 		= '" . $this->db->escape($value['name_of_option']) . "', 
					meta_keyword 		= '" . $this->db->escape($value['meta_keyword']) . "', 
					seo_title 			= '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', 
					seo_h1 				= '" . ((isset($value['seo_h1']))?($this->db->escape($value['seo_h1'])):'') . "', 
					meta_description 	= '" . $this->db->escape($value['meta_description']) . "', 
					description 		= '" . $this->db->escape($value['description']) . "',
					description_full 	= '" . ((isset($value['description_full']))?($this->db->escape($value['description_full'])):'') . "',
					tag 				= '" . $this->db->escape($value['tag']) . "', 
					color 				= '" . $this->db->escape($value['color']) . "', 
					material 			= '" . $this->db->escape($value['material']) . "', 
					variant_name 		= '" . $this->db->escape($value['variant_name']) . "', 
					variant_name_1  	= '" . $this->db->escape($value['variant_name_1']) . "', 
					variant_name_2 		= '" . $this->db->escape($value['variant_name_2']) . "',
					variant_value_1  	= '" . $this->db->escape($value['variant_value_1']) . "', 
					variant_value_2 	= '" . $this->db->escape($value['variant_value_2']) . "',
					markdown_appearance = '" . $this->db->escape($value['markdown_appearance']) . "', 
					markdown_condition 	= '" . $this->db->escape($value['markdown_condition']) . "', 
					markdown_pack 		= '" . $this->db->escape($value['markdown_pack']) . "', 
					markdown_equipment 	= '" . $this->db->escape($value['markdown_equipment']) . "', 
					manufacturer_name 	= '" . $this->db->escape($value['manufacturer_name']) . "',
					translated 			= '" . (int)$value['translated'] . "'");

				if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']){
					$variants = $this->getProductVariantsIds($product_id);
					if ($variants){
					$this->db->query("UPDATE product_description SET
						description 		= '" . $this->db->escape($value['description']) . "',						
						variant_name_1  	= '" . $this->db->escape($value['variant_name_1']) . "',
						variant_name_2 		= '" . $this->db->escape($value['variant_name_2']) . "',
						manufacturer_name 	= '" . $this->db->escape($value['manufacturer_name']) . "'
						WHERE product_id IN (" . implode(',', $variants) . ") AND language_id = '" . (int)$language_id . "'");
					}
				}

				if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode']){
					foreach ($fields_to_copy as $field){						
						if (!empty($value[$field])){
							if ($language_id == $this->config->get('config_rainforest_source_language_id')){
								$rainforest_source_text[$field] = $value[$field];								
							} else {
								$copy_product_description[$field][$language_id] = $value[$field];
							}
						}
					}
				}
			}

			if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode'] && !empty($rainforest_source_text)){
				foreach ($fields_to_copy as $field){
					if (!empty($copy_product_description[$field])){
						foreach ($copy_product_description[$field] as $language_id => $value){
							if (!empty($value) && !empty($rainforest_source_text[$field])){

								$sql = " UPDATE product_description p1 INNER JOIN product_description p2 ";
								$sql .= " ON p1.product_id = p2.product_id ";
								$sql .= " AND p2.language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "'";
								$sql .= " AND p2.`" . $field . "` LIKE '" . $this->db->escape($rainforest_source_text[$field]) . "'";
								$sql .= " SET p1.`" . $field . "` = '" . $this->db->escape($value) . "' ";
								$sql .= " WHERE p1.language_id = '" . (int)$language_id . "'";

								$this->db->query($sql);
							}
						}
					}
				}
			}		
			
			$this->db->query("DELETE FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_store'])) {
				foreach ($data['product_store'] as $store_id) {
					$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}
			}

			if ($this->config->get('ukrcredits_status')){
				if (isset($data['partscount_pp'])) {
					if ($data['partscount_pp'] || $data['partscount_ii'] || $data['partscount_mb'] || $data['product_pp'] || $data['product_ii'] || $data['product_mb'] || $data['markup_pp'] || $data['markup_ii'] || $data['markup_mb']) {
						$this->db->query("DELETE FROM product_ukrcredits WHERE product_id = '" . (int)$product_id . "'");
						$this->db->query("INSERT INTO product_ukrcredits SET product_id = '" . (int)$product_id . "', product_ii = '" . $this->db->escape($data['product_ii']) . "', product_pp = '" . $this->db->escape($data['product_pp']) . "', product_mb = '" . $this->db->escape($data['product_mb']) . "', partscount_pp = '" . $this->db->escape($data['partscount_pp']) . "', partscount_ii = '" . $this->db->escape($data['partscount_ii']) . "', partscount_mb = '" . $this->db->escape($data['partscount_mb']) . "', markup_pp = '" . $this->db->escape($data['markup_pp']) . "', markup_ii = '" . $this->db->escape($data['markup_ii']) . "', markup_mb = '" . $this->db->escape($data['markup_mb']) . "'");
					}
				}					
			}
			
			
			$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['product_attribute'])) {
				foreach ($data['product_attribute'] as $product_attribute) {
					if ($product_attribute['attribute_id']) {
						$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
						
						$copy_product_attribute_description = [];
						foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
							$this->db->query("INSERT INTO product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");

							if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode']){
								if ($language_id == $this->config->get('config_rainforest_source_language_id')){
									$rainforest_source_text = $product_attribute_description['text'];
								} else {
									$copy_product_attribute_description[$language_id] = [
										'text' => $product_attribute_description['text']
									];
								}
							}
						}

						if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode'] && !empty($rainforest_source_text)){
							foreach ($copy_product_attribute_description as $language_id => $copy_attribute_description) {	

								$sql = "UPDATE product_attribute p1 INNER JOIN product_attribute p2 ON p1.product_id = p2.product_id ";
								$sql .= " AND p2.language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "'";
								$sql .= " AND p2.attribute_id = '" . (int)$product_attribute['attribute_id'] . "'";
								$sql .= " AND p2.text LIKE '" . $this->db->escape($rainforest_source_text) . "'";
								$sql .= " SET p1.text = '" . $this->db->escape($copy_attribute_description['text']) . "'";
								$sql .= " WHERE p1.attribute_id = '" . (int)$product_attribute['attribute_id'] . "'";
								$sql .= " AND p1.language_id = '" . (int)$language_id . "'";

								$this->db->query($sql);
							}
						}
					}				
				}			
			}

			if ($this->config->get('config_use_separate_table_for_features')){
				$this->db->query("DELETE FROM product_feature WHERE product_id = '" . (int)$product_id . "'");

				if (!empty($data['product_feature'])) {
					foreach ($data['product_feature'] as $product_feature) {
						if ($product_feature['feature_id']) {
							$this->db->query("DELETE FROM product_feature WHERE product_id = '" . (int)$product_id . "' AND feature_id = '" . (int)$product_feature['feature_id'] . "'");

							$copy_product_feature_description = [];
							foreach ($product_feature['product_feature_description'] as $language_id => $product_feature_description) {				
								$this->db->query("INSERT INTO product_feature SET product_id = '" . (int)$product_id . "', feature_id = '" . (int)$product_feature['feature_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_feature_description['text']) . "'");

								if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode']){
									if ($language_id == $this->config->get('config_rainforest_source_language_id')){
										$rainforest_source_text = $product_feature_description['text'];
									} else {
										$copy_product_feature_description[$language_id] = [
											'text' => $product_feature_description['text']
										];
									}
								}
							}

							if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode'] && !empty($rainforest_source_text)){
								foreach ($copy_product_feature_description as $language_id => $copy_feature_description) {	

									$sql = "UPDATE product_feature p1 INNER JOIN product_feature p2 ON p1.product_id = p2.product_id ";
									$sql .= " AND p2.language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "'";
									$sql .= " AND p2.feature_id = '" . (int)$product_feature['feature_id'] . "'";
									$sql .= " AND p2.text LIKE '" . $this->db->escape($rainforest_source_text) . "'";
									$sql .= " SET p1.text = '" . $this->db->escape($copy_feature_description['text']) . "'";
									$sql .= " WHERE p1.feature_id = '" . (int)$product_feature['feature_id'] . "'";
									$sql .= " AND p1.language_id = '" . (int)$language_id . "'";

									$this->db->query($sql);
								}
							}
						}				
					}			
				}
			}
			
			$this->db->query("DELETE FROM product_price_to_store WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_price_to_store'])) {			
				foreach ($data['product_price_to_store'] as $key => $value){				
					if ($value && (!empty($value['price'])) || !empty($value['dot_not_overload_1c']) || !empty($value['price_delayed'])) {						
						$this->db->query("INSERT INTO product_price_to_store SET store_id = '" . (int)$key . "', product_id = '" . (int)$product_id . "', price = '" . (float)$value['price'] . "', price_delayed = '" . (float)$value['price_delayed'] . "', dot_not_overload_1c = '" . (int)$value['dot_not_overload_1c'] . "', settled_from_1c = '" . (int)$value['settled_from_1c'] . "'");						
					}
				}				
			}
			
			$this->db->query("DELETE FROM product_price_national_to_store WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_price_national_to_store'])) {
				$this->load->model('setting/setting');												
				foreach ($data['product_price_national_to_store'] as $key => $value){
					if ($value && (!empty($value['price']) || !empty($value['dot_not_overload_1c']))) {										
						$this->db->query("INSERT INTO product_price_national_to_store SET store_id = '" . (int)$key . "', product_id = '" . (int)$product_id . "', price = '" . (float)$value['price'] . "', price_delayed = '" . (float)$value['price_delayed'] . "', dot_not_overload_1c = '" . (int)$value['dot_not_overload_1c'] . "', settled_from_1c = '" . (int)$value['settled_from_1c'] . "',  currency = '" . $this->db->escape($this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$key)) . "'");						
					}
				}				
			}
			
			$this->db->query("DELETE FROM product_price_national_to_yam WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_price_national_to_yam'])) {
				$this->load->model('setting/setting');				
				foreach ($data['product_price_national_to_yam'] as $key => $value){
					if ($value && (!empty($value['price']) || !empty($value['dot_not_overload_1c']))) {					
						$this->db->query("INSERT INTO product_price_national_to_yam SET store_id = '" . (int)$key . "', product_id = '" . (int)$product_id . "', price = '" . (float)$value['price'] . "', dot_not_overload_1c = '" . (int)$value['dot_not_overload_1c'] . "', settled_from_1c = '" . (int)$value['settled_from_1c'] . "',  currency = '" . $this->db->escape($this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$key)) . "'");
					}
				}				
			}

			$this->db->query("DELETE FROM product_stock_status WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_stock_status'])) {			
				foreach ($data['product_stock_status'] as $key => $value){
					if ($value > 0) {
						$this->db->query("INSERT INTO product_stock_status SET store_id = '" . (int)$key . "', product_id = '" . (int)$product_id . "', stock_status_id = '" . (int)$value . "'");
					}
				}
			}	
			
			$this->db->query("DELETE FROM product_stock_limits WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_stock_limits'])) {			
				foreach ($data['product_stock_limits'] as $key => $value){
					if (is_array($value)) {
						$this->db->query("INSERT INTO product_stock_limits SET store_id = '" . (int)$key . "', product_id = '" . (int)$product_id . "', min_stock = '" . (int)$value['min_stock'] . "', rec_stock = '" . (int)$value['rec_stock'] . "'");
					}
				}
				
			}					
			
			$this->db->query("DELETE FROM product_option WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_option'])) {
				
				$this->addBatchOptions($data);
				
				foreach ($data['product_option'] as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'block' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						$this->db->query("INSERT INTO product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
						
						$product_option_id = $this->db->getLastId();
						
						if (isset($product_option['product_option_value'])  && count($product_option['product_option_value']) > 0 ) {
							foreach ($product_option['product_option_value'] as $product_option_value) {
								$this->db->query("INSERT INTO product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', this_is_product_id = '" . (int)$product_option_value['this_is_product_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
								

								$ob_pov_id = $this->db->getLastId();
								if(isset($product_option_value['ob_sku'])) { $this->db->query("UPDATE product_option_value SET ob_sku = '" . $this->db->escape($product_option_value['ob_sku']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_info'])) { $this->db->query("UPDATE product_option_value SET ob_info = '" . $this->db->escape($product_option_value['ob_info']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_image'])) { $this->db->query("UPDATE product_option_value SET ob_image = '" . $this->db->escape($product_option_value['ob_image']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_sku_override'])) { $this->db->query("UPDATE product_option_value SET ob_sku_override = '" . $this->db->escape($product_option_value['ob_sku_override']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								unset($ob_pov_id);
							}
							}else{
							$this->db->query("DELETE FROM product_option WHERE product_option_id = '".$product_option_id."'");
						}
						} else { 
						$this->db->query("INSERT INTO product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
					}					
				}
			}
			
			$this->db->query("DELETE FROM product_product_option WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_product_option_value WHERE product_id = '" . (int)$product_id . "'");
			
			if (!empty($data['product_product_option'])) {
				foreach ($data['product_product_option'] as $product_product_option) {
					
					$this->db->query("INSERT INTO product_product_option SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$product_product_option['category_id'] . "', type = '" . $this->db->escape($product_product_option['type']) . "', required = '" . (int)$product_product_option['required'] . "', sort_order = '" . (int)$product_product_option['sort_order'] . "'");
					
					$product_product_option_id = $this->db->getLastId();
					
					if (!empty($product_product_option['product_option'])) {
						foreach ($product_product_option['product_option'] as $product_option => $value) {
							$this->db->query("INSERT INTO product_product_option_value SET product_product_option_id = '" . (int)$product_product_option_id . "', product_id = '" . (int)$product_id . "', product_option_id = '" . (int)$value['product_id'] . "', sort_order = '" . (int)$value['sort_order'] . "'");
						} 
					}
				}
			}
			
			$this->db->query("DELETE FROM product_discount WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_discount'])) {
				foreach ($data['product_discount'] as $product_discount) {
					$this->db->query("INSERT INTO product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
				}
			}
			
			$this->db->query("DELETE FROM product_special WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_special'])) {
				foreach ($data['product_special'] as $product_special) {
					$this->db->query("INSERT INTO product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', store_id = '" . (int)$product_special['store_id'] . "', currency_scode = '" . $this->db->escape($product_special['currency_scode']) . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
				}
			}
			
			$this->db->query("DELETE FROM product_additional_offer WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_additional_offer'])) {
				foreach ($data['product_additional_offer'] as $product_additional_offer) {					
					if (isset($product_additional_offer['product_additional_offer_id']) && $product_additional_offer['product_additional_offer_id']) {
						
						$this->db->query("INSERT INTO product_additional_offer SET 
							product_additional_offer_id 	= '" . (int)$product_additional_offer['product_additional_offer_id'] . "',
							product_id 						= '" . (int)$product_id . "', 
							customer_group_id 				= '" . (int)$product_additional_offer['customer_group_id'] . "',
							priority 						= '" . (int)$product_additional_offer['priority'] . "', 
							quantity 						= '" . (int)$product_additional_offer['quantity'] . "', 
							ao_product_id 					= '" . (int)$product_additional_offer['ao_product_id'] . "', 
							price 							= '" . (float)$product_additional_offer['price'] . "', 
							percent 						= '" . (int)$product_additional_offer['percent'] . "', 
							date_start 						= '" . $this->db->escape($product_additional_offer['date_start']) . "',
							date_end 						= '" . $this->db->escape($product_additional_offer['date_end']) . "', 
							image 							= '" . $this->db->escape($product_additional_offer['image']) . "',
							ao_group 						= '" . $this->db->escape($product_additional_offer['ao_group']) . "',
							description 					= '" . $this->db->escape($product_additional_offer['description']) . "'");
						
						$this->db->query("DELETE FROM product_additional_offer_to_store WHERE product_additional_offer_id = '" . (int)$product_additional_offer['product_additional_offer_id'] . "'");
						
						foreach ($product_additional_offer['store_id'] as $product_additional_offer_store_id){
							$this->db->query("INSERT INTO product_additional_offer_to_store SET 
								product_additional_offer_id = '" . (int)$product_additional_offer['product_additional_offer_id'] . "', 
								store_id = '" . $product_additional_offer_store_id . "'");
						}
						
						} else {
						
							$this->db->query("INSERT INTO product_additional_offer SET 
								product_id 				= '" . (int)$product_id . "', 
								customer_group_id 		= '" . (int)$product_additional_offer['customer_group_id'] . "',
								priority 				= '" . (int)$product_additional_offer['priority'] . "', 
								quantity 				= '" . (int)$product_additional_offer['quantity'] . "', 
								ao_product_id 			= '" . (int)$product_additional_offer['ao_product_id'] . "', 
								price 					= '" . (float)$product_additional_offer['price'] . "', 
								percent 				= '" . (int)$product_additional_offer['percent'] . "', 
								date_start 				= '" . $this->db->escape($product_additional_offer['date_start']) . "',
								date_end 				= '" . $this->db->escape($product_additional_offer['date_end']) . "', 
								image 					= '" . $this->db->escape($product_additional_offer['image']) . "', 
								description 			= '" . $this->db->escape($product_additional_offer['description']) . "'");
						
						$product_additional_offer_id = $this->db->getLastId();
						
						$this->db->query("DELETE FROM product_additional_offer_to_store WHERE product_additional_offer_id = '" . (int)$product_additional_offer_id . "'");
						
						foreach ($product_additional_offer['store_id'] as $product_additional_offer_store_id){
							$this->db->query("INSERT INTO product_additional_offer_to_store SET 
								product_additional_offer_id = '" . (int)$product_additional_offer_id . "', 
								store_id = '" . $product_additional_offer_store_id . "'");
						}						
					}
				}
			}

			if (isset($data['faproduct_facategory'])) {
				$this->load->model('catalog/faproduct');
				$this->model_catalog_faproduct->setFAproductCats((int)$product_id, $data['faproduct_facategory']);
			}

			if (isset($data['facategory_show'])) {
				$this->db->query("DELETE FROM facategory_to_faproduct WHERE product_id = '" . (int)$product_id . "'");
				if ($data['facategory_show'] != 0) {
					$this->db->query("INSERT INTO facategory_to_faproduct SET 
					product_id 		= '" . (int)$product_id . "', 
					facategory_id 	= '" . (int)$data['facategory_show'] . "'"
					);
				}
			} 
			
			$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_image'])) {
				foreach ($data['product_image'] as $product_image) {
					$this->db->query("INSERT INTO product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
				}
			}

			$this->db->query("DELETE FROM product_video WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_video_description WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_video'])) {
				foreach ($data['product_video'] as $product_video) {
					$this->db->query("INSERT INTO product_video SET product_id = '" . (int)$product_id . "', video = '" . $this->db->escape(html_entity_decode($product_video['video'], ENT_QUOTES, 'UTF-8')) . "', image = '" . $this->db->escape(html_entity_decode($product_video['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_video['sort_order'] . "'");

					$product_video_id = $this->db->getLastId();

					foreach ($product_video['product_video_description'] as $language_id => $value) {
						$this->db->query("INSERT INTO product_video_description SET 
							product_video_id 	= '" . (int)$product_video_id . "',
							language_id 		= '" . (int)$language_id . "',
							product_id 			= '" . (int)$product_id . "',
							title 				= '" . $this->db->escape($value['title']) . "'");
					}
				}
			}
			
			$this->db->query("DELETE FROM ocfilter_option_value_to_product WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM ocfilter_option_value_to_product_description WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['ocfilter_product_option'])) {
				foreach ($data['ocfilter_product_option'] as $option_id => $values) {
					foreach ($values['values'] as $value_id => $value) {
						if (isset($value['selected'])) {
							$this->db->query("INSERT INTO ocfilter_option_value_to_product SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_id . "', value_id = '" . $this->db->escape($value_id) . "', slide_value_min = '" . (isset($value['slide_value_min']) ? (float)$value['slide_value_min'] : 0) . "', slide_value_max = '" . (isset($value['slide_value_max']) ? (float)$value['slide_value_max'] : 0) . "'");
							
							foreach ($value['description'] as $language_id => $description) {
								if (trim($description['description'])) {
									$this->db->query("INSERT INTO ocfilter_option_value_to_product_description SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_id . "', value_id = '" . $this->db->escape($value_id) . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($description['description']) . "'");
								}
							}
						}
					}
				}
			}
			
			$this->db->query("DELETE FROM product_to_download WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_download'])) {
				foreach ($data['product_download'] as $download_id) {
					$this->db->query("INSERT INTO product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				}
			}
			
			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_category'])) {
				foreach ($data['product_category'] as $category_id) {
					$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
					
					$this->db->query("INSERT IGNORE INTO feed_queue SET category_id = '" . (int)$category_id . "'");
				}		
			}
			
			$this->db->query("DELETE FROM product_filter WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_filter'])) {
				foreach ($data['product_filter'] as $filter_id) {
					$this->db->query("INSERT INTO product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}		
			}
			
			$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_related'])) {
				foreach ($data['product_related'] as $related_id) {
					$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
					$this->db->query("INSERT INTO product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
					$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
				}
			}

			$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_similar WHERE similar_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_similar'])) {
				foreach ($data['product_similar'] as $similar_id) {
					$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$product_id . "' AND similar_id = '" . (int)$similar_id . "'");
					$this->db->query("INSERT INTO product_similar SET product_id = '" . (int)$product_id . "', similar_id = '" . (int)$similar_id . "'");
					$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$similar_id . "' AND similar_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_similar SET product_id = '" . (int)$similar_id . "', similar_id = '" . (int)$product_id . "'");
				}
			}

			$this->db->query("DELETE FROM product_sponsored WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_sponsored WHERE sponsored_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_sponsored'])) {
				foreach ($data['product_sponsored'] as $sponsored_id) {
					$this->db->query("DELETE FROM product_sponsored WHERE product_id = '" . (int)$product_id . "' AND sponsored_id = '" . (int)$sponsored_id . "'");
					$this->db->query("INSERT INTO product_sponsored SET product_id = '" . (int)$product_id . "', sponsored_id = '" . (int)$sponsored_id . "'");
					$this->db->query("DELETE FROM product_sponsored WHERE product_id = '" . (int)$sponsored_id . "' AND sponsored_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_sponsored SET product_id = '" . (int)$sponsored_id . "', sponsored_id = '" . (int)$product_id . "'");
				}
			}
			
			$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_similar_to_consider WHERE similar_to_consider_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_similar_to_consider'])) {
				foreach ($data['product_similar_to_consider'] as $similar_to_consider_id) {
					$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$product_id . "' AND similar_to_consider_id = '" . (int)$similar_to_consider_id . "'");
					$this->db->query("INSERT INTO product_similar_to_consider SET product_id = '" . (int)$product_id . "', similar_to_consider_id = '" . (int)$similar_to_consider_id . "'");
					$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$similar_to_consider_id . "' AND similar_to_consider_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_similar_to_consider SET product_id = '" . (int)$similar_to_consider_id . "', similar_to_consider_id = '" . (int)$product_id . "'");
				}
			}

			$this->db->query("DELETE FROM product_view_to_purchase WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_view_to_purchase WHERE view_to_purchase_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_view_to_purchase'])) {
				foreach ($data['product_view_to_purchase'] as $view_to_purchase_id) {
					$this->db->query("DELETE FROM product_view_to_purchase WHERE product_id = '" . (int)$product_id . "' AND view_to_purchase_id = '" . (int)$view_to_purchase_id . "'");
					$this->db->query("INSERT INTO product_view_to_purchase SET product_id = '" . (int)$product_id . "', view_to_purchase_id = '" . (int)$view_to_purchase_id . "'");
				}
			}

			$this->db->query("DELETE FROM product_also_viewed WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_also_viewed WHERE also_viewed_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_also_viewed'])) {
				foreach ($data['product_also_viewed'] as $also_viewed_id) {
					$this->db->query("DELETE FROM product_also_viewed WHERE product_id = '" . (int)$product_id . "' AND also_viewed_id = '" . (int)$also_viewed_id . "'");
					$this->db->query("INSERT INTO product_also_viewed SET product_id = '" . (int)$product_id . "', also_viewed_id = '" . (int)$also_viewed_id . "'");
					$this->db->query("DELETE FROM product_also_viewed WHERE product_id = '" . (int)$also_viewed_id . "' AND also_viewed_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_also_viewed SET product_id = '" . (int)$also_viewed_id . "', also_viewed_id = '" . (int)$product_id . "'");
				}
			}

			$this->db->query("DELETE FROM product_also_bought WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_also_bought WHERE also_bought_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_also_bought'])) {
				foreach ($data['product_also_bought'] as $also_bought_id) {
					$this->db->query("DELETE FROM product_also_bought WHERE product_id = '" . (int)$product_id . "' AND also_bought_id = '" . (int)$also_bought_id . "'");
					$this->db->query("INSERT INTO product_also_bought SET product_id = '" . (int)$product_id . "', also_bought_id = '" . (int)$also_bought_id . "'");
					$this->db->query("DELETE FROM product_also_bought WHERE product_id = '" . (int)$also_bought_id . "' AND also_bought_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_also_bought SET product_id = '" . (int)$also_bought_id . "', also_bought_id = '" . (int)$product_id . "'");
				}
			}

			$this->db->query("DELETE FROM product_shop_by_look WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_shop_by_look WHERE shop_by_look_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_shop_by_look'])) {
				foreach ($data['product_shop_by_look'] as $shop_by_look_id) {
					$this->db->query("DELETE FROM product_shop_by_look WHERE product_id = '" . (int)$product_id . "' AND shop_by_look_id = '" . (int)$shop_by_look_id . "'");
					$this->db->query("INSERT INTO product_shop_by_look SET product_id = '" . (int)$product_id . "', shop_by_look_id = '" . (int)$shop_by_look_id . "'");
					$this->db->query("DELETE FROM product_shop_by_look WHERE product_id = '" . (int)$shop_by_look_id . "' AND shop_by_look_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO product_shop_by_look SET product_id = '" . (int)$shop_by_look_id . "', shop_by_look_id = '" . (int)$product_id . "'");
				}
			}

			
			$this->db->query("DELETE FROM product_child WHERE product_id = '" . (int)$product_id . "'");
			if (isset($data['product_child'])) {										
				foreach ($data['product_child'] as $child_id) {
					$this->db->query("INSERT INTO product_child SET product_id = '" . (int)$product_id . "', child_id = '" . (int)$child_id . "'");
				}
			}
			
			if (isset($data['main_category_id']) && $data['main_category_id'] > 0) {
				
				$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['main_category_id'] . "'");
				$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['main_category_id'] . "', main_category = 1");
				} elseif (isset($data['product_category'][0])) {
				$this->db->query("UPDATE product_to_category SET main_category = 1 WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['product_category'][0] . "'");
			}
			
			$this->db->query("DELETE FROM product_reward WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_reward'])) {
				foreach ($data['product_reward'] as $reward){	
					$this->db->query("INSERT INTO product_reward SET product_id = '" . (int)$product_id . "', 
					customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "', 
					points		= '" . (int)$reward['points'] . "',
					store_id 	= '" . (int)$reward['store_id']. "',
					percent 	= '" . (int)$reward['percent'] . "',
					date_end 	= '" . $this->db->escape($reward['date_end']) . "',
					date_start 	= '" . $this->db->escape($reward['date_start']) . "',
					coupon_acts = '" . (int)$reward['coupon_acts'] . "'");
				}
			}
			
			$this->db->query("DELETE FROM product_to_layout WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_layout'])) {
				foreach ($data['product_layout'] as $store_id => $layout) {
					if ($layout['layout_id']) {
						$this->db->query("INSERT INTO product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}
		
			if ($this->url->checkIfGenerate('product_id')){	
				$this->db->query("DELETE FROM url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
				
				if ($data['keyword']) {
					foreach ($data['keyword'] as $language_id => $keyword) {
						if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
					}
				}
			}
			
			$this->db->query("DELETE FROM `product_profile` WHERE product_id = " . (int)$product_id);	
			
			if (isset($data['product_profiles'])) {							
				foreach ($data['product_profiles'] as $profile) {			
					$this->db->query("INSERT INTO `product_profile` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$profile['customer_group_id'] . ", `profile_id` = " . (int)$profile['profile_id']);		
				}		
			}		
			
			$this->rainforestAmazon->offersParser->PriceLogic->priceUpdaterQueue->addToQueue($product_id);


			if ($this->config->get('seogen_status')){
				$this->load->model('module/seogen');

				foreach ($data['product_description'] as $language_id => $value) {
					$this->model_module_seogen->urlifyProduct($product_id, $language_id);
				}
			}

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'edit', 'entity_type' => 'product', 'entity_id' => $product_id]);

			$this->syncProductNamesInOrders($product_id);
			
			return (int)$product_id;
		}
		
		public function copyProduct($product_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			if ($query->num_rows) {
				$this->load->model('catalog/faproduct');
				
				$data = $query->row;			
				$data['viewed'] = '0';
				$data['keyword'] = [];
				$data['status'] = '0';
				
				$data = array_merge($data, array('product_attribute' 	=> $this->getProductAttributes($product_id)));
				$data = array_merge($data, array('product_description' 	=> $this->getProductDescriptions($product_id)));			
				$data = array_merge($data, array('product_discount' 	=> $this->getProductDiscounts($product_id)));

				if ($this->config->get('ukrcredits_status')){
					$data = array_merge($data, $this->getProductUkrcredits($product_id));
				}				
				
				$this->load->model('catalog/ocfilter');
				$data = array_merge($data, array('ocfilter_product_option' => $this->model_catalog_ocfilter->getProductOCFilterValues($product_id)));
				
				$data = array_merge($data, array('product_filter' 	=> $this->getProductFilters($product_id)));
				$data = array_merge($data, array('product_image' 	=> $this->getProductImages($product_id)));		
				$data = array_merge($data, array('product_option'	=> $this->getProductOptions($product_id)));
				$data = array_merge($data, array('product_related' 	=> $this->getProductRelated($product_id)));
				$data = array_merge($data, array('product_reward' 	=> $this->getProductRewards($product_id)));
				$data = array_merge($data, array('product_special' 	=> $this->getProductSpecials($product_id)));
				$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
				$data = array_merge($data, array('faproduct_facategory' => $this->model_catalog_faproduct ->getFAProductCategories($product_id)));
                $data = array_merge($data, array('faproduct_facategory' => $this->model_catalog_faproduct ->getFAcategoryShow($product_id)));
				$data = array_merge($data, array('product_download' 	=> $this->getProductDownloads($product_id)));
				$data = array_merge($data, array('product_layout' 		=> $this->getProductLayouts($product_id)));
				$data = array_merge($data, array('product_store' 		=> $this->getProductStores($product_id)));
				$data = array_merge($data, array('product_profiles' 	=> $this->getProfiles($product_id)));
				
				$new_product_id = $this->addProduct($data);
				
				$this->db->query("UPDATE product SET stock_product_id = '" .(int)$product_id. "' WHERE product_id = '" . (int)$new_product_id . "'");

				$this->load->model('kp/content');
				$this->model_kp_content->addContent(['action' => 'copy', 'entity_type' => 'product', 'entity_id' => $new_product_id]);
				
				return $new_product_id;
			}
		}	
				
		public function getProductOptionPrices($product_id){
			
			$options = $this->db->query("SELECT DISTINCT this_is_product_id FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");
			
			if ($options->num_rows > 1){
				
				$prices = [];
				$specials = [];
				$all = [];
				foreach ($options->rows as $option){
					
					if ($product = $this->getProductPrice($option['this_is_product_id'])){
						if ($product['price']){
							$prices[] = $product['price'];
							$all[] = $product['price'];
						}
						
						if ($product['special']){
							$specials[] = $product['special'];
							$all[] = $product['special'];
						}
					}
				}
				
				$tmp = [];					
				if (min($all) < min($prices)){
					//Это означает, что у нас есть скидка и мы можем ее вывести
					$tmp['price']['min'] = min($prices);
					$tmp['price']['max'] = max($prices);
					$tmp['price']['only'] = (max($prices) == min($prices))?min($prices):false;
					
					$tmp['special']['min'] = min($all);
					$tmp['special']['max'] = max($all);
					$tmp['special']['only'] = (max($all) == min($all))?min($all):false;
					} else {
					
					$tmp['price']['min'] = min($prices);
					$tmp['price']['max'] = max($prices);
					$tmp['price']['only'] = (max($prices) == min($prices))?min($prices):false;
					
					$tmp['special']['min'] = false;
					$tmp['special']['max'] = false;
					$tmp['special']['only'] = false;
					
				}
				
				return array(
				'price'   => $tmp['price']['min'],
				'special' => $tmp['special']['min']
				);
				} else {
				return false;
			}						
		}
		
		public function copyProductNoStock($product_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			if ($query->num_rows) {
				$this->load->model('catalog/faproduct');
				
				$data = $query->row;				
				$data['viewed'] = '0';
				$data['keyword'] = '';
				$data['status'] = '0';
				
				$data = array_merge($data, array('product_attribute' 	=> $this->getProductAttributes($product_id)));
				$data = array_merge($data, array('product_description' 	=> $this->getProductDescriptions($product_id)));			
				$data = array_merge($data, array('product_discount' 	=> $this->getProductDiscounts($product_id)));
				$data = array_merge($data, array('product_filter' 		=> $this->getProductFilters($product_id)));
				$data = array_merge($data, array('product_image' 		=> $this->getProductImages($product_id)));		
				$data = array_merge($data, array('product_option' 		=> $this->getProductOptions($product_id)));
				$data = array_merge($data, array('product_related' 		=> $this->getProductRelated($product_id)));
				$data = array_merge($data, array('product_reward' 		=> $this->getProductRewards($product_id)));
				$data = array_merge($data, array('faproduct_facategory' => $this->model_catalog_faproduct ->getFAProductCategories($product_id)));
                $data = array_merge($data, array('faproduct_facategory' => $this->model_catalog_faproduct ->getFAcategoryShow($product_id)));
				$data = array_merge($data, array('product_special' 		=> $this->getProductSpecials($product_id)));
				$data = array_merge($data, array('product_category' 	=> $this->getProductCategories($product_id)));
				$data = array_merge($data, array('product_download' 	=> $this->getProductDownloads($product_id)));
				$data = array_merge($data, array('product_layout' 		=> $this->getProductLayouts($product_id)));
				$data = array_merge($data, array('product_store' 		=> $this->getProductStores($product_id)));
				$data = array_merge($data, array('product_profiles' 	=> $this->getProfiles($product_id)));
				
				$new_product_id = $this->addProduct($data);	

				$this->load->model('kp/content');
				$this->model_kp_content->addContent(['action' => 'copy', 'entity_type' => 'product', 'entity_id' => $new_product_id]);					
				
				return $new_product_id;
			}
		}

		public function disableProduct($product_id){
			$this->db->query("UPDATE product SET `status` = 0 WHERE product_id = '" . (int)$product_id . "'");

			return $this;
		}

		public function enableProduct($product_id){
			$this->db->query("UPDATE product SET `status` = 1 WHERE product_id = '" . (int)$product_id . "'");

			return $this;
		}

		public function syncProductNamesInOrders($product_id, $language_id = false, $name = false){
			if ($this->config->get('config_sync_product_names_in_orders')){
				if ($language_id){
					if ($name){
						$sql = "UPDATE order_product SET name = '" . $this->db->escape($name) . "' WHERE product_id = '" . (int)$product_id . "' AND order_id IN (SELECT o.order_id FROM `order` o WHERE DATE(o.date_added) >= '" . date('Y-m-d', strtotime('-3 month')) . "' AND language_id = '" . (int)$language_id . "')";

						$this->db->query($sql);
					} else {
						$descriptions 	= $this->getProductDescriptions($product_id);
						$orders_query 	= $this->db->query("SELECT order_product_id FROM `order_product` op LEFT JOIN `order` o ON (op.order_id = o.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND DATE(o.date_added) >= '" . date('Y-m-d', strtotime('-3 month')) . "' AND language_id = '" . (int)$language_id . "'");

						foreach ($orders_query->rows as $row){
							if (!empty($descriptions[$language_id])){
								$name = $descriptions[$language_id]['name'];

								$sql = "UPDATE `order_product` SET name = '" . $this->db->escape($name) . "' WHERE order_product_id = '" . (int)$row['order_product_id'] . "'";				
								$this->db->query($sql);
							}								
						}
					}
				} else {
					$descriptions 	= $this->getProductDescriptions($product_id);
					$orders_query 	= $this->db->query("SELECT order_product_id, language_id FROM `order_product` op LEFT JOIN `order` o ON (op.order_id = o.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND DATE(o.date_added) >= '" . date('Y-m-d', strtotime('-3 month')) . "'");

					foreach ($orders_query->rows as $row){
						if (!empty($descriptions[$row['language_id']])){
							$name = $descriptions[$row['language_id']]['name'];
						} else {
							$name = $descriptions[$this->config->get('config_language_id')]['name'];
						}			

						$sql = "UPDATE `order_product` SET name = '" . $this->db->escape($name) . "' WHERE order_product_id = '" . (int)$row['order_product_id'] . "'";				
						$this->db->query($sql);
					}
				}				
			}				
		}

		public function syncProductNamesFromOrders($product_id, $language_id, $name){
			if ($this->config->get('config_sync_product_names_in_orders')){
				$this->db->query("UPDATE product_description SET name = '" . $this->db->escape($name) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");

				$this->syncProductNamesInOrders($product_id);
			}
		}

		public function deleteProductSimple($product_id){
			foreach ((array)\hobotix\RainforestAmazon::productRelatedTables as $table){
				$sql = "DELETE FROM `" . $table . "` WHERE product_id = '" . (int)$product_id . "'";
				$this->db->query($sql);
			}

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'delete', 'entity_type' => 'product', 'entity_id' => $product_id]);

			$this->db->query("DELETE FROM product_sponsored WHERE sponsored_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_similar WHERE similar_id = '" . (int)$product_id . "'");
		}		

		public function deleteImageCache($image){
			if (!$this->config->get('config_delete_products_images_enable')){
				return;
			}

			$level = error_reporting();
			error_reporting(0);			

			if (file_exists(DIR_IMAGE . $image) && is_file(DIR_IMAGE . $image)){
				@unlink(DIR_IMAGE . $image);
			}

			foreach (['config_image_category_', 'config_image_thumb_', 'config_image_popup_', 'config_image_product_', 'config_image_additional_', 'config_image_related_', 'config_image_compare_', 'config_image_cart_'] as $size){
				$cachedName = Image::cachedname($image, 'jpg', [$this->config->get($size . 'width'), $this->config->get($size . 'height'), $this->config->get('config_image_jpeg_quality'), false]);
				if (file_exists($cachedName['full_path']) && is_file($cachedName['full_path'])){
					@unlink($cachedName['full_path']);
				}

				$cachedName = Image::cachedname($image, 'webp', [$this->config->get($size . 'width'), $this->config->get($size . 'height'), $this->config->get('config_image_webp_quality'), false]);
				if (file_exists($cachedName['full_path']) && is_file($cachedName['full_path'])){
					@unlink($cachedName['full_path']);
				}

				$cachedName = Image::cachedname($image, 'avif', [$this->config->get($size . 'width'), $this->config->get($size . 'height'), $this->config->get('config_image_avif_quality'), false]);
				if (file_exists($cachedName['full_path']) && is_file($cachedName['full_path'])){
					@unlink($cachedName['full_path']);
				}
			}

			foreach ([[1000, 1000], [600, 600], [300, 300]] as $size){
				$cachedName = Image::cachedname($image, 'jpg', [$size[0], $size[1], $this->config->get('config_image_jpeg_quality'), false]);
				if (file_exists($cachedName['full_path']) && is_file($cachedName['full_path'])){
					@unlink($cachedName['full_path']);
				}

				$cachedName = Image::cachedname($image, 'webp', [$size[0], $size[1], $this->config->get('config_image_webp_quality'), false]);
				if (file_exists($cachedName['full_path']) && is_file($cachedName['full_path'])){
					@unlink($cachedName['full_path']);
				}

				$cachedName = Image::cachedname($image, 'avif', [$size[0], $size[1], $this->config->get('config_image_avif_quality'), false]);
				if (file_exists($cachedName['full_path']) && is_file($cachedName['full_path'])){
					@unlink($cachedName['full_path']);
				}
			}

			error_reporting($level);
		}
	
		public function deleteProduct($product_id, $recursion = true, $asin_deletion_mode = false) {	
			if ($this->config->get('config_never_delete_products_in_orders')){
				if ($this->rainforestAmazon->offersParser->PriceLogic->checkIfProductIsInOrders($product_id)){
					$this->disableProduct($product_id);
					return;
				}
			}

			if ($this->config->get('config_never_delete_products_in_warehouse')){
				if ($this->rainforestAmazon->offersParser->PriceLogic->checkIfProductIsOnAnyWarehouse($product_id)){
					$this->disableProduct($product_id);
					return;
				}
			}

			if (empty($this->session->data['config_rainforest_asin_deletion_mode'])){
				$this->session->data['config_rainforest_asin_deletion_mode'] = $this->config->get('config_rainforest_asin_deletion_mode');
			}

			if (empty($this->session->data['config_rainforest_variant_edition_mode'])){
				$this->session->data['config_rainforest_variant_edition_mode'] = $this->config->get('config_rainforest_variant_edition_mode');
			}

			if ($this->config->get('config_enable_amazon_specific_modes') && ($asin_deletion_mode || $this->session->data['config_rainforest_asin_deletion_mode'])){
				$query = $this->db->query("SELECT p.asin, pd.name FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND language_id = '26') WHERE p.product_id = '" . (int)$product_id . "' LIMIT 1");

				if ($query->num_rows && !empty($query->row['asin'])){
					$asin = $query->row['asin'];
					$this->db->query("INSERT IGNORE INTO deleted_asins SET asin = '" . $this->db->escape($query->row['asin']) . "', name = '" . $this->db->escape($query->row['name']) . "', date_added = NOW(), user_id = '" . $this->user->getID() . "'");
				}
			}


			if ($this->config->get('config_enable_amazon_specific_modes') && $this->config->get('config_enable_amazon_asin_file_cache')){
				$query = $this->db->query("SELECT p.asin FROM product p WHERE p.product_id = '" . (int)$product_id . "' LIMIT 1");

				if ($query->num_rows && !empty($query->row['asin'])){
					$file = $this->registry->get('rainforestAmazon')->infoUpdater->createAsinCacheFileName($query->row['asin'])['full'];					
					if ($file && file_exists($file)){
						unlink($file);
					}				
				}
			}

			//IMAGES CLEANUP
			if ($this->config->get('config_enable_amazon_specific_modes')){
				$query = $this->db->query("SELECT image FROM product WHERE product_id = '" . (int)$product_id . "'");
				if ($query->num_rows){
					$this->deleteImageCache($query->row['image']);
				}


				$query = $this->db->query("SELECT image FROM product_image WHERE product_id = '" . (int)$product_id . "'");
				foreach ($query->rows as $row){					
					$this->deleteImageCache($row['image']);
				}
			}		

			if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']){
				if ($recursion){
					$products_to_delete = $this->getProductVariantsIds($product_id);

					if ($products_to_delete){
						foreach ($products_to_delete as $product_id_to_delete){
							if ((int)$product_id_to_delete && $product_id != $product_id_to_delete){							
								$this->deleteProduct($product_id_to_delete, false);
							}
						}
					}
				}
			}		

			foreach ((array)\hobotix\RainforestAmazon::productRelatedTables as $table){
				$this->db->query("DELETE FROM `" . $table . "` WHERE product_id = '" . (int)$product_id . "'");
			}

			$this->db->query("DELETE FROM product_sponsored WHERE sponsored_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_similar WHERE similar_id = '" . (int)$product_id . "'");
			
			$this->load->model('catalog/set');
			$results = $this->model_catalog_set->getSetsByProductId($product_id);
			if($results){
				foreach($results as $result){
					$results = $this->model_catalog_set->deleteSet($result['set_id']);
				}
			} 		

			$this->db->query("DELETE FROM url_alias WHERE query = 'product_id=" . (int)$product_id. "'");			

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'delete', 'entity_type' => 'product', 'entity_id' => $product_id]);
		}

		public function countVariantProducts($product_id){
			return $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE main_variant_id = '" . (int)$product_id . "' AND product_id <> '" . (int)$product_id . "'")->row['total'];
		}

		public function getProductIdByASIN($asin){
			$query = $this->db->query("SELECT p.product_id FROM product p WHERE p.asin = '" . $this->db->escape($asin) . "' LIMIT 1");

			if ($query->num_rows && !empty($query->row['product_id'])){
				return $query->row['product_id'];				
			}

			return false;
		}

		public function getAsinByProductID($product_id){
			$query = $this->db->query("SELECT p.asin FROM product p WHERE p.product_id = '" . (int)$product_id . "' LIMIT 1");

			if ($query->num_rows && !empty($query->row['asin'])){
				return $query->row['asin'];				
			}

			return false;
		}

		public function getVariantProducts($product_id, $language_id){

			$sql = "SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE main_variant_id = '" . (int)$product_id . "' AND p.product_id <> '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'";

			$query = $this->db->query($sql);
			return $query->rows;
		}

		public function getOtherVariantProducts($product_id){
			$sql = "SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE main_variant_id = '" . (int)$product_id . "' AND p.product_id <> '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$query = $this->db->query($sql);
			return $query->rows;
		}

		public function getAllProductVariants($product_id){
			$variants = $this->getProductVariantsIds($product_id);

			$sql = "SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id IN (". implode(',', $variants) .") AND p.product_id <> '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name DESC";

			$query = $this->db->query($sql);
			return $query->rows;
		}
		
		public function getProductVariantsIds($product_id){
			$variants = [$product_id];
			$main_variant_id = 0;

			$query = $this->db->query("SELECT main_variant_id FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			if ($query->num_rows && !empty($query->row['main_variant_id'])){
				$main_variant_id = $query->row['main_variant_id'];
				$variants[] = $main_variant_id;
			}

			if ($main_variant_id){
				$query = $this->db->query("SELECT product_id FROM product WHERE main_variant_id IN(" . (int)$product_id . ", " . (int)$main_variant_id . ")");				
			} else {
				$query = $this->db->query("SELECT product_id FROM product WHERE main_variant_id = '" . (int)$product_id . "'");
			}

			if ($query->num_rows){
				foreach ($query->rows as $row){
					$variants[] = $row['product_id'];
				}						
			}


			$variants = array_unique($variants);

			return $variants;
		}
		
		public function getProduct($product_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			return $query->row;
		}
		
		public function getKeyWords($product_id) {
			$keywords = [];			

			if ($keywords = $this->url->linkfromid('product_id', $product_id)){
				return $keywords;
			}

			$query = $this->db->query("SELECT * FROM url_alias WHERE query = 'product_id=" . (int)$product_id . "'");			
			foreach ($query->rows as $result) {
				$keywords[$result['language_id']] = $result['keyword'];					
			}
			
			return $keywords;
		}
		
		public function getProductPrice($product_id) {
			$query = $this->db->query("SELECT price FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			
			if (isset($query->row['price'])) {
				return $query->row['price'];
				} else {
				return false;
			}
		}
						
		public function getProductStorePrice($product_id, $store_id) {
			$query = $this->db->query("SELECT price FROM product_price_to_store WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$store_id . "' LIMIT 1");
			
			if (isset($query->row['price'])) {
				return $query->row['price'];
				} else {
				return false;
			}
		}
	
		public function getProductStorePriceNational($product_id, $store_id) {
			$query = $this->db->query("SELECT price FROM product_price_national_to_store WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$store_id . "' LIMIT 1");
			
			if (isset($query->row['price'])) {
				return $query->row['price'];
				} else {
				return false;
			}
		}
		
		public function getProductStorePricesAll($product_id) {
			$query = $this->db->query("SELECT price FROM product_price_to_store WHERE product_id = '" . (int)$product_id . "'");
			
			return $query->rows;
		}
		
		public function getColorGroupedProducts($product_id, $color_group){
			$query = $this->db->query("SELECT product_id from product where color_group LIKE '" . $this->db->escape($color_group) . "'");
			
			return $query->rows;
		}
		
		public function getProductQuantityStock($product_id){
			$query = $this->db->query("SELECT quantity_stock from product where product_id = ".(int)$product_id);
			return $query->row['quantity_stock'];
		}
		
		public function getProductMainCategoryId($product_id) {
			$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1' LIMIT 1");
			
			return ($query->num_rows ? (int)$query->row['category_id'] : 0);
		}
		
		public function getProductsBySKU($sku){
			$sql = "SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			$sql .= " AND (TRIM(p.model) LIKE '" . $this->db->escape(trim($sku)) . "'";
			$sql .= " OR TRIM(p.sku) LIKE '" . $this->db->escape(trim($sku)) . "')";
			$sql .= " ORDER BY pd.name";	
			
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getProducts($data = array()) {
			$sql = "SELECT p.*, pd.*, c.name as collection_name FROM product p ";
			$sql .= " LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			$sql .= " LEFT JOIN collection c ON (p.collection_id = c.collection_id)";
			
			if (!empty($data['filter_category'])) {
					$data['filter_category_id'] = $data['filter_category'];
			}

			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			if (!empty($data['filter_name'])) {						
				$art = preg_replace("([^0-9])", "", $data['filter_name']);
				
				if (!empty($data['exact_model'])){
					
					$sql .= " AND (pd.name LIKE '" . $this->db->escape($data['filter_name']) . "'";
					$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
					$sql .= " OR (p.model LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.model)>1 )";
					$sql .= " OR (p.ean LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.ean)>1 )";
					$sql .= " OR (p.sku LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.sku)>1 )";
					$sql .= " OR (p.asin LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.asin)>1 )";
					
					} else {
					
					$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
					$sql .= " OR (p.model LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.model)>1 )";
					$sql .= " OR (p.ean LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.ean)>1 )";
					$sql .= " OR (p.sku LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.sku)>1 )";
					$sql .= " OR (p.asin LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.asin)>1 )";
					
				}
				
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.model)>1 )";
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.sku)>1 ))";
				
			}
			
			if (!empty($data['filter_model'])) {
				
				if (!empty($data['exact_model'])){
					
					$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "'";
					
					} else {
					
					$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
					
				}
			}
			
			if (isset($data['filter_collection_id']) && !is_null($data['filter_collection_id'])) {
				$sql .= " AND p.collection_id = '" . $this->db->escape($data['filter_collection_id']) . "'";
			}

			if (isset($data['filter_manufacturer_id']) && !is_null($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . $this->db->escape($data['filter_manufacturer_id']) . "'";
			}

			if (isset($data['filter_stock_status_ids']) && is_array($data['filter_stock_status_ids'])) {
				$sql .= " AND p.stock_status_id IN (" . implode(',', $data['filter_stock_status_ids']) . ")";
			}

			if (isset($data['filter_has_image']) && !is_null($data['filter_has_image'])) {
				$sql .= " AND p.image <> ''";
			}

			if (isset($data['filter_date_added_from']) && $data['filter_date_added_from']) {
				$sql .= " AND p.date_added >= '" . date('Y-m-d', strtotime($data['filter_date_added_from'])) . "'";
			}
			
			if (!empty($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_quantity_stockM']) && !is_null($data['filter_quantity_stockM'])) {
				$sql .= " AND p.quantity_stockM > 0";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
			
			$sql .= " GROUP BY p.product_id";
			
			$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order',
			'p.date_added',
			'RAND()',
			'on_stock+RAND()'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'on_stock+RAND()'){
					$sql .= " ORDER BY (" . $this->config->get('config_warehouse_identifier') . " > 0), RAND()";	
				} else {
					$sql .= " ORDER BY " . $data['sort'];	
				}				
				} else {
				$sql .= " ORDER BY pd.name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC') && mb_strpos($data['order'], 'RAND') === false) {
				$sql .= " DESC";
				} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}

		public function getProductsByAsin($asin){
			$results = [];
			$query = $this->db->query("SELECT product_id FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "')");

			foreach ($query->rows as $row){
				$results[] = $row['product_id'];
			}

			return $results;
		}
		
		public function getAdditionalOfferById($ao_id, $ao_product_id) {
			$query = $this->db->query("SELECT * FROM product_additional_offer WHERE product_additional_offer_id = '" . (int)$ao_id . "' AND ao_product_id = '" . (int)$ao_product_id . "' ORDER BY priority, price LIMIT 1");
			
			return $query->row;
		}
		
		public function getProductAdditionalOffer($product_id, $active = false) {			
			if (!$active) {				
				$query = $this->db->query("SELECT * FROM product_additional_offer WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");				
				} else {				
				$query = $this->db->query("SELECT * FROM product_additional_offer WHERE product_id = '" . (int)$product_id . "' AND date_end >= NOW() ORDER BY priority, price");				
			}
			
			return $query->rows;
		}
			
		public function getProductAdditionalOfferGroups() {
			$query = $this->db->query("SELECT DISTINCT ao_group FROM product_additional_offer ORDER BY priority, price");
			
			$results = [];				
			
			foreach ($query->rows as $row){
				$results[] = $row['ao_group'];
			}
			
			return $results;
		}
		
		public function getProductAdditionalOfferByAOID($product_id) {
			$query = $this->db->query("SELECT * FROM product_additional_offer WHERE ao_product_id = '" . (int)$product_id . "' ORDER BY priority, price");
			
			return $query->rows;
		}
		
		public function getProductAdditionalOfferStoresByAOID($product_additional_offer_id) {
			$query = $this->db->query("SELECT * FROM product_additional_offer_to_store WHERE product_additional_offer_id = '" . (int)$product_additional_offer_id . "'");						
			
			$store_data = [];
			
			foreach ($query->rows as $row){
				$store_data[] = $row['store_id'];
			}
			
			return $store_data;
		}	
		
		public function getProductWaitListOrders($product_id){
			$query = $this->db->query("SELECT order_id, quantity FROM order_product_nogood opn WHERE product_id = '" .(int)$product_id. "' AND waitlist = 1");
			
			return $query->rows;		
		}
		
		public function deleteProductFromWaitList($order_product_id){
			$this->db->query("UPDATE order_product_nogood SET waitlist = 0 WHERE order_product_id = '" . (int)$order_product_id . "'");				
		}
		
		public function createNewOrderFromWaitlist($order_product_id, $new_order_products){
			$order_id_query = $this->db->query("SELECT order_id FROM order_product_nogood WHERE order_product_id = '" . (int)$order_product_id . "'");	
			
			$this->load->model('sale/order');
			
			if ($order_id_query->row['order_id']){
				
				if ($old_order = $this->model_sale_order->getOrder($order_id_query->row['order_id'])){
					$data = $old_order;
					
					$this->db->query("INSERT INTO `order` 
					SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', 
					store_id = '" . (int)$data['store_id'] . "', 
					store_name = '" . $this->db->escape($data['store_name']) . "',
					store_url = '" . $this->db->escape($data['store_url']) . "', 
					customer_id = '" . (int)$data['customer_id'] . "',
					customer_group_id = '" . (int)$data['customer_group_id'] . "', 
					firstname = '" . $this->db->escape($data['firstname']) . "', 
					lastname = '" . $this->db->escape($data['lastname']) . "', 
					email = '" . $this->db->escape($data['email']) . "', 
					telephone = '" . $this->db->escape($data['telephone']) . "', 
					fax = '" . $this->db->escape($data['fax']) . "', 
					payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
					payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
					payment_company = '" . $this->db->escape($data['payment_company']) . "', 
					payment_company_id = '" . $this->db->escape($data['payment_company_id']) . "', 
					payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) . "', 
					payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
					payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
					payment_city = '" . $this->db->escape($data['payment_city']) . "', 
					payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "',
					payment_country = '" . $this->db->escape($data['payment_country']) . "', 
					payment_country_id = '" . (int)$data['payment_country_id'] . "', 
					payment_zone = '" . $this->db->escape($data['payment_zone']) . "',
					payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
					payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
					payment_method = '" . $this->db->escape($data['payment_method']) . "', 
					payment_code = '" . $this->db->escape($data['payment_code']) . "', 
					shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
					shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
					shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
					shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
					shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
					shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
					shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "',
					shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
					shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
					shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
					shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
					shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
					shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
					shipping_code = '" . $this->db->escape($data['shipping_code']) . "',
					comment = '" . $this->db->escape($data['comment']) . "', 
					order_status_id = '" . (int)$this->config->get('config_order_status_id') . "', 
					affiliate_id  = '" . (int)$data['affiliate_id'] . "', 
					language_id = '" . (int)$data['language_id'] . "',
					from_waitlist = '1', 
					currency_id = '" . (int)$data['currency_id']. "', 
					currency_code = '" . $this->db->escape($data['currency_code']) . "', 
					currency_value = '" . (float)$data['currency_value'] . "', 
					date_added = NOW(), 
					date_modified = NOW()					
					");
					
					$new_order_id = $this->db->getLastId();
					
					$main_total = 0;
					$main_total_national = 0;
					
					foreach ($new_order_products as $new_product_id){								
						$this->db->query("UPDATE order_product_nogood SET waitlist = 0, new_order_id = '" .(int)$new_order_id. "' WHERE order_product_id = '" . (int)$new_product_id . "' LIMIT 1");	
						
						$product_query = $this->db->query("SELECT * FROM order_product_nogood WHERE order_product_id = '" . (int)$new_product_id . "' LIMIT 1");	
						
						$order_product = $product_query->row;
						
						$product_price = $this->getProductPrice($order_product['product_id']);				
						
						$product_specials = $this->getProductSpecials($order_product['product_id']);
						foreach ($product_specials  as $product_special) {
							if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
								$special = $product_special['price'];
								break;
							}					
						}
						
						if ($special){
							$product_price = $special;
						}
						
						$product_total = $product_price * $order_product['quantity'];				
						$product_price_national = $this->currency->convert($product_price, $this->config->get('config_currency'), $data['currency_code']);				
						$product_total_national = $product_price_national * $order_product['quantity'];
						
						//products
						$this->db->query("INSERT INTO order_product SET				
						order_id = '" . (int)$new_order_id . "', 
						product_id = '" . (int)$order_product['product_id'] . "', 
						name = '" . $this->db->escape($order_product['name']) . "', 
						model = '" . $this->db->escape($order_product['model']) . "', 
						quantity = '" . (int)$order_product['quantity'] . "', 
						price = '" . (float)$product_price . "', 
						price_national = '" . (float)$product_price_national . "',
						total = '" . (float)$product_total . "',
						total_national = '" . (float)$product_total_national . "',
						tax = '" . (float)$order_product['tax'] . "', 
						reward = '" . (int)$order_product['reward'] . "', 
						good = '1', 
						taken = '0'"
						);
						
						$main_total += ($order_product['price'] * $order_product['quantity']);
						$main_total_national += ($order_product['price_national'] * $order_product['quantity']);				
					}
					
					//totals
					//totals
					//main_total
					$this->db->query("UPDATE `order` SET total = '" . $main_total . "' WHERE order_id = '" . (int)$new_order_id . "'");
					$this->db->query("UPDATE `order` SET total_national = '" . $main_total_national . "' WHERE order_id = '" . (int)$new_order_id . "'");
					//sub_total
					$main_total_text = $this->currency->format($main_total_national, $data['currency_code'], 1);
					
					$this->db->query("INSERT INTO order_total SET 						 
					order_id = '" . (int)$new_order_id . "', 
					code = '" . $this->db->escape('sub_total') . "', 
					title = '" . $this->db->escape('Сумма') . "', 
					text = '" . $this->db->escape($main_total_text) . "', 
					`value` = '" . (float)$main_total . "',
					`value_national` = '" . (float)$main_total_national . "',
					sort_order = '" . '1' . "'");
					//total_total
					$this->db->query("INSERT INTO order_total SET 						 
					order_id = '" . (int)$new_order_id . "', 
					code = '" . $this->db->escape('total') . "', 
					title = '" . $this->db->escape('Итого') . "', 
					text = '" . $this->db->escape($main_total_text) . "', 
					`value` = '" . (float)$main_total . "',
					`value_national` = '" . (float)$main_total_national . "',
					sort_order = '" . '10' . "'");
					
					
					
					//echo last order
					return $new_order_id;
					} else {
					//не существует такой заказ
					return false;				
				}									
				} else {
				//нету записи в базе
				return false;
			}		
		}
		
		public function getProductsWaitListTotalReady() {
			$query = $this->db->query("SELECT COUNT(*) as total FROM order_product_nogood opn WHERE opn.waitlist = 1 AND opn.supplier_has = 1");
			
			return $query->row['total'];
		}
		
		public function getProductsWaitListTotalPreWaits() {
			$query = $this->db->query("SELECT COUNT(*) as total FROM order_product_nogood opn WHERE opn.waitlist = 1 AND opn.is_prewaitlist = 1");
			
			return $query->row['total'];
		}
		
		public function getProductsWaitList($data = array()) {
			$sql = "SELECT 			
			p.*, 			
			opn.*,
			p.image, 
			pd.name,
			p.model,
			p.ean,
			p.asin,
			p.source,
			p.sku,
			p.price,
			p.status,
			opn.quantity,
			opn.order_id,
			opn.order_product_id,
			opn.supplier_has,
			opn.price_national as price_in_order
			FROM product p 
			LEFT JOIN order_product_nogood opn ON (opn.product_id = p.product_id)
			LEFT JOIN `order` o ON (o.order_id = opn.order_id)
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE opn.waitlist = 1 AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			if (!empty($data['filter_name'])) {
				$art = preg_replace("([^0-9])", "", $data['filter_name']);
				$sql .= " AND (pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.model)>1 )";
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.sku)>1 ))";
			}
			
			if (!empty($data['filter_model'])) {
				$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
			}
			
			if (!empty($data['filter_product_id'])) {
				$sql .= " AND p.product_id = '" . (int)$data['filter_product_id'] . "'";
			}
			
			if (isset($data['collection_id']) && !is_null($data['collection_id'])) {
				$sql .= " AND p.collection_id = '" . $this->db->escape($data['collection_id']) . "'";
			}
			
			if (!empty($data['filter_customer_id'])) {
				$sql .= " AND o.customer_id = '" . (int)($data['filter_customer_id']) . "'";
			}
			
			
			if (!empty($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (!empty($data['filter_order_id'])) {
				$sql .= " AND opn.order_id = '" . (int)($data['filter_order_id']) . "'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
			
			if (isset($data['filter_supplier_has']) && !is_null($data['filter_supplier_has'])) {
				$sql .= " AND (opn.supplier_has = '" . (int)$data['filter_supplier_has'] . "'
				OR ((quantity_stockM > 0 OR quantity_stock > 0) AND opn.store_id IN (0, 5, 2))
				OR ((quantity_stockK > 0 OR quantity_stock > 0) AND opn.store_id IN (1))
				)";
			}
			
			//	$sql .= " GROUP BY opn.product_id";
			
			$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'opn.order_id',
			'p.status',
			'p.sort_order',
			'o.date_added',
			'opn.is_prewaitlist DESC, o.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY opn.is_prewaitlist DESC, o.date_added";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
				} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
			
		public function getProductsByCategoryId($category_id) {
			$query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");
			
			return $query->rows;
		} 		
		
		public function getProductTNVEDByCategory($product_id){
			$query = $this->db->query("SELECT tnved FROM category WHERE LENGTH(tnved) > 1 AND category_id IN (SELECT DISTINCT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "') LIMIT 1");
			
			if ($query->num_rows && isset($query->row['tnved'])){
				return $query->row['tnved'];
				} else {
				return '';
			}
		}
		
		public function getProductDescriptions($product_id) {
			$product_description_data = [];
			
			$query = $this->db->query("SELECT * FROM product_description WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_description_data[$result['language_id']] = array(
				'name'             	=> $result['name'],
				'short_name_d'     	=> $result['short_name_d'],
				'name_of_option'   	=> $result['name_of_option'],
				'description'      	=> $result['description'],
				'description_full'  => $result['description_full'],
				'meta_keyword'     	=> $result['meta_keyword'],
                'seo_title'        	=> $result['seo_title'],
				'seo_h1' 				=> $result['seo_h1'],
                'meta_description' 		=> $result['meta_description'],
				'tag'              		=> $result['tag'],
				'color'              	=> $result['color'],
				'material'              => $result['material'],
				'material'              => $result['material'],
				'variant_name'          => $result['variant_name'],
				'variant_name_1'        => $result['variant_name_1'],
				'variant_name_2'        => $result['variant_name_2'],
				'variant_value_1'       => $result['variant_value_1'],
				'variant_value_2'		=> $result['variant_value_2'],
				'translated'			=> $result['translated'],
				'markdown_appearance'  	=> $result['markdown_appearance'],
				'markdown_condition' 	=> $result['markdown_condition'],
				'markdown_pack'			=> $result['markdown_pack'],
				'markdown_equipment'	=> $result['markdown_equipment'],				
				'manufacturer_name'		=> $result['manufacturer_name']
				);
			}
			
			return $product_description_data;
		}
		
		public function getProductCategories($product_id) {
			$product_category_data = [];
			
			$query = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_category_data[] = $result['category_id'];
			}
			
			return $product_category_data;
		}
		
		public function getProductFilters($product_id) {
			$product_filter_data = [];
			
			$query = $this->db->query("SELECT * FROM product_filter WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_filter_data[] = $result['filter_id'];
			}
			
			return $product_filter_data;
		}

		public function getProductUkrcredits($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_ukrcredits WHERE product_id = '" . (int)$product_id . "'");

			return $query->row;
		}
		
		public function getProductAttributes($product_id) {
			$product_attribute_data = [];
			
			$product_attribute_query = $this->db->query("SELECT attribute_id FROM product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");
			
			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_description_data = [];
				
				$product_attribute_description_query = $this->db->query("SELECT * FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
				
				foreach ($product_attribute_description_query->rows as $product_attribute_description) {
					$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
				}
				
				$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
				);
			}
			
			return $product_attribute_data;
		}

		public function getProductFeatures($product_id) {			
			$product_feature_data = [];
			
			$product_feature_query = $this->db->query("SELECT feature_id FROM product_feature WHERE product_id = '" . (int)$product_id . "' GROUP BY feature_id");
			
			foreach ($product_feature_query->rows as $product_feature) {
				$product_feature_description_data = [];
				
				$product_feature_description_query = $this->db->query("SELECT * FROM product_feature WHERE product_id = '" . (int)$product_id . "' AND feature_id = '" . (int)$product_feature['feature_id'] . "'");
				
				foreach ($product_feature_description_query->rows as $product_feature_description) {
					$product_feature_description_data[$product_feature_description['language_id']] = array('text' => $product_feature_description['text']);
				}
				
				$product_feature_data[] = array(
				'feature_id'                  	=> $product_feature['feature_id'],
				'product_attribute_description' => $product_feature_description_data
				);
			}
			
			return $product_feature_data;
		}
		
		public function getProductAttributesByLanguage($product_id, $language_id) {
			$attributes = [];
			
			$query = $this->db->query("SELECT attribute_id, text FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
			
			foreach ($query->rows as $row){
				$attributes[$row['attribute_id']] = $row['text'];
			}
			
			return $attributes;
		}
		
		public function getProductAttributesNamesValuesByLanguage($product_id, $language_id) {
			$attributes = [];
			
			$query = $this->db->query("SELECT a.attribute_group_id, ad.name, pa.attribute_id, pa.text FROM product_attribute pa LEFT JOIN attribute_description ad ON (pa.attribute_id = ad.attribute_id) LEFT JOIN attribute a ON (ad.attribute_id = a.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND pa.language_id = '" . (int)$language_id . "' AND ad.language_id = '" . (int)$language_id . "' ORDER BY ad.name");
			
			foreach ($query->rows as $row){
				$attributes[$row['attribute_id']] = array(
				'name' => $row['name'],
				'text' => $row['text'],
				'group_id' => $row['attribute_group_id']
				);
			}
			
			return $attributes;
		}
		
		public function getSimilarProductsByAttributes($product_id, $category_id, $language_id, $attributes = array(), $limit = 10, $stock = false){
			//full query. Точные совпадения ВСЕХ атрибутов!
			$sql = "SELECT DISTINCT pa.product_id FROM product_attribute pa";
			$sql .= " LEFT JOIN product_to_category p2c ON (pa.product_id = p2c.product_id)";
			$sql .= " LEFT JOIN product p ON (pa.product_id = p.product_id)";
			$sql .= " WHERE pa.product_id <> '". (int)$product_id ."' ";
			foreach ($attributes as $id => $text){
				$sql .= " AND pa.product_id IN (SELECT product_id FROM product_attribute WHERE attribute_id = '" . (int)$id . "' AND text LIKE('%" . $this->db->escape(trim($text)) . "%'))";
			}	
			$sql .= " AND p2c.category_id = '" . (int)$category_id . "' AND language_id = '". (int)$language_id ."' AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') ."'";
			
			//Есть в наличии на складах
			if ($stock == 1){
				$sql .= ' AND ((quantity_stock + quantity_stockM + quantity_stockK + quantity_stockMN + quantity_stockAS) > 0)';
			}
			
			//Есть просто в наличии
			if ($stock == 2){
				$sql .= ' AND ((quantity_stock + quantity_stockM + quantity_stockK + quantity_stockMN + quantity_stockAS) = 0) AND quantity > 0';
			}
			
			$sql .= " ORDER BY p.price DESC LIMIT " . $limit;
			
			$query = $this->db->query($sql);
			
			$result = [];
			foreach ($query->rows as $row){
				$result[] = $row['product_id'];
			}
			
			if (count($result) < 8){
				
				
			}
			
			return $result;
		}
		
		public function getProductOptions($product_id) {
			$product_option_data = [];
			
			$product_option_query = $this->db->query("SELECT * FROM `product_option` po LEFT JOIN `option` o ON (po.option_id = o.option_id) LEFT JOIN `option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($product_option_query->rows as $product_option) {
				$product_option_value_data = [];	
				
				$product_option_value_query = $this->db->query("SELECT * FROM product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'this_is_product_id'      => $product_option_value['this_is_product_id'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],						
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix'],
					'ob_sku'                  	 => $product_option_value['ob_sku'], //Q: Options Boost
            		'ob_info'                    => $product_option_value['ob_info'], //Q: Options Boost
            		'ob_image'                   => $product_option_value['ob_image'], //Q: Options Boost
					'ob_sku_override'            => $product_option_value['ob_sku_override'], //Q: Options Boost
					);
				}
				
				$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],			
				'product_option_value' => $product_option_value_data,
				'option_value'         => $product_option['option_value'],
				'required'             => $product_option['required']				
				);
			}
			
			return $product_option_data;
		}
		
		public function getProductImages($product_id) {
			$query = $this->db->query("SELECT * FROM product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
			
			return $query->rows;
		}

		public function getProductVideos($product_id) {
			$query = $this->db->query("SELECT * FROM product_video WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

			$product_video_data = [];
			foreach ($query->rows as $row){

				$product_video_data[] = [
					'image'						=> $row['image'],
					'video'						=> $row['video'],
					'sort_order' 				=> $row['sort_order'],
					'product_video_description' => $this->getProductVideosDescriptions($row['product_video_id'])
				];

			}
			
			return $product_video_data;
		}

		public function getProductVideosDescriptions($product_video_id) {

			$sql = "SELECT * FROM product_video_description WHERE product_video_id = '" . (int)$product_video_id . "'";
			$query = $this->db->query($sql);
					
			$product_video_description_data = [];

			foreach ($query->rows as $row){

				$product_video_description_data[$row['language_id']] = [
					'title' => $row['title']
				];

			}

			return $product_video_description_data;
		}
		
		public function getProductImage($product_id) {
			$query = $this->db->query("SELECT image FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			
			return isset($query->row['image'])?$query->row['image']:false;
		}
		
		public function getProductDiscounts($product_id) {
			$query = $this->db->query("SELECT * FROM product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");
			
			return $query->rows;
		}
		
		public function getProductSpecials($product_id) {
			$query = $this->db->query("SELECT * FROM product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");						
			
			return $query->rows;
		}

		public function getProductSpecialsBackup($product_id) {
			$query = $this->db->query("SELECT * FROM product_special_backup WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");						
			
			return $query->rows;
		}

		public function deleteProductSpecialsBackup($product_id) {
			$this->db->query("DELETE FROM product_special_backup WHERE product_id = '" . (int)$product_id . "'");						
		}

		public function restoreProductSpecialsBackup($product_id) {
			$query = $this->db->query("SELECT * FROM product_special_backup WHERE product_id = '" . (int)$product_id . "'");						

			foreach ($query->rows as &$row){
				unset($row['product_special_id']);

				$sql = "INSERT INTO product_special " . buildUpdateStatement($row, $this->db);
			}

			$this->db->query($sql);		
			$this->deleteProductSpecialsBackup($product_id);
		}
		
		public function getProductSpecialsWithRecalc($product_id) {
			$query = $this->db->query("SELECT * FROM product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
			
			foreach ($query->rows as &$row){
				if ($row['currency_scode'] && ($row['currency_scode'] != $this->config->get('config_currency'))){
					$row['price'] =  $this->currency->convert($query->row['price'], $query->row['currency_scode'], $this->config->get('config_currency'), false, false);
				}
				
			}
			
			return $query->rows;
		}

		public function getStockStatuses($product_id){
			$query = $this->db->query("SELECT * FROM product_stock_status WHERE product_id = '" . (int)$product_id . "'");
			
			$product_stock_statuses = [];
			foreach ($query->rows as $row){
				$product_stock_statuses[$row['store_id']] = $row['stock_status_id'];				
			}
			
			return $product_stock_statuses;
		}
		
		public function getProductActualCost($product_id) {
			$query = $this->db->query("SELECT actual_cost FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			
			if ($query->row) {
				return $query->row['actual_cost'];
				} else {
				return false;
			}			
		}
		
		public function getProductSpecialOne($product_id) {
			$query = $this->db->query("SELECT price, currency_scode FROM product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price LIMIT 1");
			
			if ($query->row) {
				
				return $query->row['price'];
				
				if ($query->row['currency_scode'] && ($query->row['currency_scode'] != $this->config->get('config_currency'))){
					return $this->currency->convert($query->row['price'], $query->row['currency_scode'], $this->config->get('config_currency'), false, false);
					} else {			
					return $query->row['price'];
				}
				} else {
				return false;
			}
		}
		
		public function getProductStorePrices($product_id){
			$query = $this->db->query("SELECT * FROM product_price_to_store WHERE product_id = '" . (int)$product_id . "'");
			
			$product_prices_to_store = [];
			foreach ($query->rows as $row){
				$product_prices_to_store[$row['store_id']] = $row;				
			}
			
			return $product_prices_to_store;		
		}
		
		public function getProductStorePricesYam($product_id){
			$query = $this->db->query("SELECT * FROM product_price_national_to_yam WHERE product_id = '" . (int)$product_id . "'");
			
			$product_price_national_to_yam = [];
			foreach ($query->rows as $row){
				$product_price_national_to_yam[$row['store_id']] = $row;			
			}
			
			return $product_price_national_to_yam;		
		}
		
		public function getProductStorePricesNational($product_id){
			$query = $this->db->query("SELECT * FROM product_price_national_to_store WHERE product_id = '" . (int)$product_id . "'");
			
			$product_prices_national_to_store = [];
			foreach ($query->rows as $row){
				$product_prices_national_to_store[$row['store_id']] = $row;			
			}
			
			return $product_prices_national_to_store;		
		}
		
		public function clearProductStorePricesNational($product_id){
			$query = $this->db->query("DELETE FROM product_price_national_to_store WHERE product_id = '" . (int)$product_id . "' AND dot_not_overload_1c = 0");
			
		}
		
		public function getProductStockLimits($product_id){
			$query = $this->db->query("SELECT * FROM product_stock_limits WHERE product_id = '" . (int)$product_id . "'");
			
			$product_stock_limits = [];
			
			if ($query->num_rows) {
				foreach ($query->rows as $row){
					$product_stock_limits[$row['store_id']] = array(
					'min_stock' => $row['min_stock'],
					'rec_stock' => $row['rec_stock']
					);
				}
			}
			
			return $product_stock_limits;
		}
		
		public function getProductSpecialsForCustomer($product_id, $customer_group_id, $order_currency = false, $store_id = false) {
			$this->load->model('setting/setting');
			
			$sql = "SELECT price, currency_scode FROM product_special ps WHERE ps.product_id = '" . (int)$product_id . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1";
			
			if ($store_id){
				$sql = "SELECT price, currency_scode FROM product_special ps WHERE ps.product_id = '" . (int)$product_id . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (ps.store_id = '" . (int)$store_id . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1";
			}
			
			$query = $this->db->query($sql);	
			
			if ($order_currency){
				
				if ($query->num_rows){
					
					if (!$query->row['currency_scode']){
						return $query->row['price'];
					}
					
					if ($query->row['currency_scode'] && $order_currency != $query->row['currency_scode']){
						
						return $this->currency->convert($query->row['price'], $query->row['currency_scode'], $order_currency, false, false);
						
						} elseif ($query->row['currency_scode'] && $order_currency == $query->row['currency_scode']) {
						
						$main_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_currency', $store_id);
						return $this->currency->convert($query->row['price'], $query->row['currency_scode'], $main_currency, false, false);
						
					}
					
				}
				
				} else {
				
				if ($query->num_rows){
					return $query->row['price'];
				}
			}
			
			return false;			
		}

		public function getProductRewards($product_id) {
			$query = $this->db->query("SELECT * FROM product_reward WHERE product_id = '" . (int)$product_id . "'");
			
			return $query->rows;
		}
		
		public function getProductDownloads($product_id) {
			$product_download_data = [];
			
			$query = $this->db->query("SELECT * FROM product_to_download WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_download_data[] = $result['download_id'];
			}
			
			return $product_download_data;
		}
		
		public function getProductStores($product_id) {
			$product_store_data = [];
			
			$query = $this->db->query("SELECT * FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_store_data[] = $result['store_id'];
			}
			
			return $product_store_data;
		}
		
		public function getProductLayouts($product_id) {
			$product_layout_data = [];
			
			$query = $this->db->query("SELECT * FROM product_to_layout WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_layout_data[$result['store_id']] = $result['layout_id'];
			}
			
			return $product_layout_data;
		}
		
		public function getProductDeName($product_id){
			$this->load->model('localisation/language');
			
			$language_id = $this->model_localisation_language->getLanguageByCode($this->config->get('config_de_language'));
			
			$query = $this->db->query("SELECT name FROM product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "' LIMIT 1");
			
			if (isset($query->row['name'])){
				$name = $query->row['name'];			
				} else {
				$name = '';
			}
			
			return $name;
		}
		
		//Сопутствующие, стоковая логика, амазон frequently_bought_together
		public function getProductRelated($product_id) {
			$product_related_data = [];
			
			$query = $this->db->query("SELECT * FROM product_related WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_related_data[] = $result['related_id'];
			}
			
			return $product_related_data;
		}

		//Похожие, амазон compare_with_similar
		public function getProductSimilar($product_id) {
			$product_similar_data = [];
			
			$query = $this->db->query("SELECT * FROM product_similar WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_similar_data[] = $result['similar_id'];
			}
			
			return $product_similar_data;
		}

		//Спонсоред, sponsored_products
		public function getProductSponsored($product_id) {
			$product_sponsored_data = [];
			
			$query = $this->db->query("SELECT * FROM product_sponsored WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_sponsored_data[] = $result['sponsored_id'];
			}
			
			return $product_sponsored_data;
		}

		//Похожие, амазон similar_to_consider
		public function getProductSimilarToConsider($product_id) {
			$product_similar_data = [];
			
			$query = $this->db->query("SELECT * FROM product_similar_to_consider WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_similar_data[] = $result['similar_to_consider_id'];
			}
			
			return $product_similar_data;
		}

		//Купили после просмотра, амазон view_to_purchase
		public function getProductViewToPurchase($product_id) {
			$product_view_to_purchase_data = [];
			
			$query = $this->db->query("SELECT * FROM product_view_to_purchase WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_view_to_purchase_data[] = $result['view_to_purchase_id'];
			}
			
			return $product_view_to_purchase_data;
		}

		//Также просматривали, амазон also_viewed
		public function getProductAlsoViewed($product_id) {
			$product_also_viewed_data = [];
			
			$query = $this->db->query("SELECT * FROM product_also_viewed WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_also_viewed_data[] = $result['also_viewed_id'];
			}
			
			return $product_also_viewed_data;
		}
		
		//Также купили, амазон also_bought
		public function getProductAlsoBought($product_id) {
			$product_also_bought_data = [];
			
			$query = $this->db->query("SELECT * FROM product_also_bought WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_also_bought_data[] = $result['also_bought_id'];
			}
			
			return $product_also_bought_data;
		}

		//Купить по внешнему виду, амазон shop_by_look
		public function getProductShopByLook($product_id) {
			$product_shop_by_look_data = [];
			
			$query = $this->db->query("SELECT * FROM product_shop_by_look WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_shop_by_look_data[] = $result['shop_by_look_id'];
			}
			
			return $product_shop_by_look_data;
		}

		public function getProductChild($product_id) {
			$product_child_data = [];
			
			$query = $this->db->query("SELECT * FROM product_child WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_child_data[] = $result['child_id'];
			}
			
			return $product_child_data;
		}
		
		public function getProductRelatedWithoutNotIsStock($product_id) {
			$product_related_data = [];
			
			$query = $this->db->query("SELECT * FROM product_related pr
			LEFT JOIN product p ON (pr.related_id = p.product_id) 
			WHERE pr.product_id = '" . (int)$product_id . "' AND p.stock_status_id <> '" .(int)$this->config->get('config_not_in_stock_status_id'). "' LIMIT 8");
			
			foreach ($query->rows as $result) {
				$product_related_data[] = $result['related_id'];
			}
			
			return $product_related_data;
		}
		
		public function getThisProductIsSet($product_id){
			$query = $this->db->query("SELECT * FROM `set` WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			
			if ($query->row){
				return $query->row;		
				} else {
				return false;
			}
		}
		
		public function getSet($set_id){
			$query = $this->db->query("SELECT * FROM `product_to_set` WHERE set_id = '" . (int)$set_id . "'");
			
			return $query->rows;		
		}
		
		public function getProfiles($product_id) {
			return $this->db->query("SELECT * FROM `product_profile` WHERE product_id = " . (int)$product_id)->rows;
		}

		public function getTotalProductsWithInvalidAsin() {
			$query = $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE asin = 'INVALID'");
			
			return $query->row['total'];
		}

		public function getTotalProductsWithDoubleAsin() {
			$query = $this->db->query("SELECT COUNT(asin) as total FROM product WHERE asin <> 'INVALID' GROUP BY asin HAVING(COUNT(`product_id`)) > 1");
			
			return $query->num_rows?$query->num_rows:0;
		}

		public function getTotalProductsParsed() {
			$query = $this->db->query("SELECT COUNT(product_id) as total FROM product_amzn_data WHERE 1");
			
			return $query->row['total'];
		}

		public function getTotalProductsGotOffers() {
			$query = $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE asin <> '' AND amzn_last_offers != '0000-00-00 00:00:00'");
			
			return $query->row['total'];
		}

		public function getTotalProductsHaveOffers() {
			$query = $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE asin <> '' AND amzn_last_offers != '0000-00-00 00:00:00' AND amzn_no_offers = 0");
			
			return $query->row['total'];
		}

		public function getTotalProductsHaveNoOffers() {
			$query = $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE asin <> '' AND amzn_last_offers != '0000-00-00 00:00:00' AND amzn_no_offers = 1");
			
			return $query->row['total'];
		}

		public function getTotalProductsGotOffersByDate($date) {
			$query = $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE asin <> '' AND DATE(amzn_last_offers) = '" . $this->db->escape($date) . "'");
			
			return $query->row['total'];
		}

		public function getTotalProductsFilled() {
			$query = $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE filled_from_amazon = 1 AND product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE amazon_can_get_full = 1))");
			
			return $query->row['total'];
		}

		public function getTotalProductsNeedToBeFilled() {
			$query = $this->db->query("SELECT COUNT(DISTINCT p2c.product_id) as total FROM product_to_category p2c LEFT JOIN product p ON (p.product_id = p2c.product_id) WHERE category_id IN (SELECT category_id FROM category WHERE amazon_can_get_full = 1) AND fill_from_amazon = 1 AND filled_from_amazon = 0");
			
			return $query->row['total'];
		}

		public function getTotalProductsSimple($data = array()) {
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}

		public function getTotalProductsSimpleNoVariants($data = array()) {
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " AND (p.main_variant_id = '0' OR ISNULL(p.main_variant_id))";

			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}

		public function getTotalProductsModified($date) {
			if (is_array($date) && !empty($date['from']) && !empty($date['to'])){
				$query = $this->db->query("SELECT COUNT(product_id) as total FROM product LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND DATE(date_modified) >= DATE('" . $date['from'] . "') AND DATE(date_added) <= DATE('" . $date['to'] . "')");
			} else {
				$query = $this->db->query("SELECT COUNT(product_id) as total FROM product LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND DATE(date_modified) = DATE('" . $date . "')");
			}
			
			return $query->row['total'];
		}

		public function getTotalProductsAdded($date) {
			if (is_array($date) && !empty($date['from']) && !empty($date['to'])){
				$query = $this->db->query("SELECT COUNT(p.product_id) as total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND DATE(date_added) >= DATE('" . $date['from'] . "') AND DATE(date_added) <= DATE('" . $date['to'] . "')");
			} else {
				$query = $this->db->query("SELECT COUNT(p.product_id) as total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND DATE(date_added) = DATE('" . $date . "')");
			}
			
			return $query->row['total'];
		}
		
		public function getTotalProducts($data = array()) {
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (!empty($data['filter_name'])) {						
				$art = preg_replace("([^0-9])", "", $data['filter_name']);
				
				if (!empty($data['exact_model'])){
					
					$sql .= " AND (pd.name LIKE '" . $this->db->escape($data['filter_name']) . "'";
					$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
					$sql .= " OR (p.model LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.model)>1 )";
					$sql .= " OR (p.ean LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.ean)>1 )";
					$sql .= " OR (p.sku LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.sku)>1 )";
					$sql .= " OR (p.asin LIKE '" .$this->db->escape($data['filter_name']). "' AND LENGTH(p.asin)>1 )";
					
					} else {
					
					$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "'";
					$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
					$sql .= " OR (p.model LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.model)>1 )";
					$sql .= " OR (p.ean LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.ean)>1 )";
					$sql .= " OR (p.sku LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.sku)>1 )";
					$sql .= " OR (p.asin LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.asin)>1 )";
					
				}
				
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.model)>1 )";
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.sku)>1 ))";
				
			}
			
			if (!empty($data['filter_model'])) {
				
				if (!empty($data['exact_model'])){
					
					$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "'";
					
					} else {
					
					$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
					
				}
			}

			if (!empty($data['filter_category_id'])) {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
			
			if (!empty($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_quantity_stockM']) && !is_null($data['filter_quantity_stockM'])) {
				$sql .= " AND p.quantity_stockM > 0";
			}

			if ($this->config->get('config_enable_amazon_specific_modes') && !empty($this->session->data['config_rainforest_variant_edition_mode'])) {
            	$sql .= " AND (p.main_variant_id = '0' OR ISNULL(p.main_variant_id))";
        	}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}	
		
		public function getTotalOrdersWaitList($data = array()) {
			$sql = "SELECT COUNT(DISTINCT o.order_id) AS total FROM product p
			LEFT JOIN order_product_nogood opn ON (opn.product_id = p.product_id)
			LEFT JOIN `order` o ON (o.order_id = opn.order_id)
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE opn.waitlist = 1 AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (!empty($data['filter_name'])) {
				$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			}
			
			if (!empty($data['filter_model'])) {
				$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
			}
			
			if (!empty($data['filter_product_id'])) {
				$sql .= " AND p.product_id = '" . (int)$data['filter_product_id'] . "'";
			}
			
			if (!empty($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (!empty($data['filter_customer_id'])) {
				$sql .= " AND o.customer_id = '" . (int)($data['filter_customer_id']) . "'";
			}
			
			if (!empty($data['filter_order_id'])) {
				$sql .= " AND opn.order_id = '" . (int)($data['filter_order_id']) . "'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
			
		}
		
		public function getTotalProductsWaitList($data = array()) {
			$sql = "SELECT COUNT(p.product_id) AS total FROM product p
			LEFT JOIN order_product_nogood opn ON (opn.product_id = p.product_id)
			LEFT JOIN `order` o ON (o.order_id = opn.order_id)
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
			
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE opn.waitlist = 1 AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (!empty($data['filter_name'])) {
				$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			}
			
			if (!empty($data['filter_model'])) {
				$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
			}
			
			if (!empty($data['filter_product_id'])) {
				$sql .= " AND p.product_id = '" . (int)$data['filter_product_id'] . "'";
			}
			
			if (!empty($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (!empty($data['filter_customer_id'])) {
				$sql .= " AND o.customer_id = '" . (int)($data['filter_customer_id']) . "'";
			}
			
			if (!empty($data['filter_order_id'])) {
				$sql .= " AND opn.order_id = '" . (int)($data['filter_order_id']) . "'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
			
			if (isset($data['filter_supplier_has']) && !is_null($data['filter_supplier_has'])) {
				$sql .= " AND (opn.supplier_has = '" . (int)$data['filter_supplier_has'] . "'
				OR ((quantity_stockM > 0 OR quantity_stock > 0) AND opn.store_id IN (0, 5, 2))
				OR ((quantity_stockK > 0 OR quantity_stock > 0) AND opn.store_id IN (1))
				)";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}	
		
		public function getTotalProductsByTaxClassId($tax_class_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE tax_class_id = '" . (int)$tax_class_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalProductsByStockStatusId($stock_status_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE stock_status_id = '" . (int)$stock_status_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalProductsByWeightClassId($weight_class_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE weight_class_id = '" . (int)$weight_class_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalProductsByLengthClassId($length_class_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE length_class_id = '" . (int)$length_class_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalProductsByDownloadId($download_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product_to_download WHERE download_id = '" . (int)$download_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalProductsByManufacturerId($manufacturer_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalProductsByCollectionId($collection_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE collection_id = '" . (int)$collection_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalProductsByAttributeId($attribute_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
			
			return $query->row['total'];
		}	
		
		public function getTotalProductsByOptionId($option_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product_option WHERE option_id = '" . (int)$option_id . "'");
			
			return $query->row['total'];
		}	
		
		public function getProductProductOptions($product_id) {
			$product_option_data = [];
			
			$product_option_query = $this->db->query("SELECT * FROM product_product_option ppo LEFT JOIN category_description cd ON (ppo.category_id = cd.category_id) WHERE ppo.product_id = '" . (int)$product_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ppo.sort_order");
			
			foreach ($product_option_query->rows as $product_option) {
				
				$product_option_value_data = [];
				
				$product_option_value_query = $this->db->query("SELECT ppov.*, pd.name, p.image, p.price FROM product_product_option_value ppov LEFT JOIN product p ON (ppov.product_option_id = p.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE ppov.product_product_option_id = '" . (int)$product_option['product_product_option_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ppov.sort_order");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
					'product_product_option_value_id' => $product_option_value['product_product_option_value_id'],
					'product_option_id'         	  => $product_option_value['product_option_id'],
					'name'                    		  => $product_option_value['name'],
					'image'                   		  => $product_option_value['image'],
					'price'                   		  => $product_option_value['price'],
					'sort_order'            		  => $product_option_value['sort_order'],					
					);
				}
				
				$this->load->model('catalog/category');
				
				$category_info = $this->model_catalog_category->getCategory($product_option['category_id']);
				
				//	var_dump($category_info);
				
				$product_option_data[] = array(
				'product_product_option_id'    => $product_option['product_product_option_id'],
				'category_id'            	   => $product_option['category_id'],
				'name'                 		   => $category_info['name'],
				'type'                 		   => $product_option['type'],
				'required'             		   => $product_option['required'],
				'sort_order' 				   => $product_option['sort_order'],
				'product_option' 			   => $product_option_value_data
				);				
			}	
			
			return $product_option_data;					
		}
		
		public function addBatchOptions($data) {
			if (isset($data['product_batchoption'])) {
				
				foreach ($data['product_batchoption'] as $product_id) {
					
					// Delete all existing if delete box is checked for additional items
					if (isset($data['batchdelete'])) {
						$this->db->query("DELETE FROM product_option WHERE product_id = '" . (int)$product_id . "'");
						$this->db->query("DELETE FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");
					}
					
					// Add new Options
					if (isset($data['product_option'])) {
						foreach ($data['product_option'] as $k1 => $product_option) {
							//$this->db->query("INSERT INTO product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
							$this->db->query("INSERT INTO product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
							
							$product_option_id = $this->db->getLastId();
							
							if (isset($product_option['product_option_value'])) {
								foreach ($product_option['product_option_value'] as $k2 => $product_option_value) {
									$this->db->query("INSERT INTO product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . $this->db->escape($product_option_value['option_value_id']) . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
									
									$product_option_value_id = $this->db->getLastId();
									
									//Q: Options Boost
									if(isset($product_option_value['ob_sku'])) { $this->db->query("UPDATE product_option_value SET ob_sku = '" . $this->db->escape($product_option_value['ob_sku']) . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'"); }
									if(isset($product_option_value['ob_info'])) { $this->db->query("UPDATE product_option_value SET ob_info = '" . $this->db->escape($product_option_value['ob_info']) . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'"); }
									if(isset($product_option_value['ob_image'])) { $this->db->query("UPDATE product_option_value SET ob_image = '" . $this->db->escape($product_option_value['ob_image']) . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'"); }
									if(isset($product_option_value['ob_sku_override'])) { $this->db->query("UPDATE product_option_value SET ob_sku_override = '" . $this->db->escape($product_option_value['ob_sku_override']) . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'"); }
								}
							}
						}
					}
				}
			}
		}
		
		public function getProductStocks($data = array()){
			
			$this->load->model('localisation/language');			
			$language_id = $this->model_localisation_language->getLanguageByCode($this->config->get('config_de_language'));			
			
			$sql = "SELECT 
			p.product_id,
			p.image, 
			pd.name,
			pdde.name as de_name,
			p.manufacturer_id, 
			m.name as manufacturer_name,			
			COUNT(op.order_id) as total_p_in_orders,
			SUM(op.quantity) as total_q_in_orders,
			p.model, 
			p.sku, 
			p.ean,
			p.asin,
			p.tnved,
			p.amazon_offers_type,
			p.amazon_best_price,
			p.amazon_seller_quality,
			p.actual_cost, 
			p.costprice,
			p.profitability,
			quantity_stock, 
			quantity_stockM, 
			quantity_stockK,
			quantity_stock_onway, 
			quantity_stockM_onway, 
			quantity_stockK_onway,  
			quantity_stockMN,
			quantity_stockAS
			FROM product p 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND pd.language_id = 2)
			LEFT JOIN product_description pdde ON (p.product_id = pdde.product_id AND pdde.language_id = '" . (int)$language_id . "')
			LEFT JOIN order_product op ON (op.product_id = p.product_id) 
			LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
			WHERE p.status = 1";
			
			if (!empty($data['filter_manufacturer_id'])){
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (!empty($data['identifier'])){
				$sql .= " AND ((p.". trim($data['identifier']) ." > 0) OR (p.". trim($data['identifier'] . '_onway') ." > 0))";
				} else {
				$sql .= " AND ((p.quantity_stock > 0) OR (p.quantity_stockM > 0) OR (p.quantity_stockK > 0) OR (p.quantity_stockMN > 0) OR (p.quantity_stockAS > 0) OR (p.quantity_stock_onway > 0) OR (p.quantity_stockM_onway > 0) OR (p.quantity_stockK_onway > 0))";
			}
			
			$sql .= " GROUP BY p.product_id";
			
			if (!empty($data['identifier']) && empty($data['filter_sort'])){
				$sql .= " ORDER BY p.". trim($data['identifier']) ." DESC ";
				} else {
				
				if (!empty($data['filter_sort'])){
					
					if ($data['filter_sort'] == 'quantity-desc'){
						$sql .= " ORDER BY p.". trim($data['identifier']) ." DESC ";
					}
					
					if ($data['filter_sort'] == 'quantity-asc'){
						$sql .= " ORDER BY p.". trim($data['identifier']) ." ASC ";
					}
					
					if ($data['filter_sort'] == 'name-desc'){
						$sql .= " ORDER BY pd.name DESC ";
					}
					
					if ($data['filter_sort'] == 'name-asc'){
						$sql .= " ORDER BY pd.name ASC ";
					}
					
					} else {
					
					$sql .= " ORDER BY pd.name DESC";
					
				}
			}
						
			$query = $this->db->query($sql);			
			return $query->rows;			
		}
		
		public function getStockTotals($warehouse_identifier){
			
			
			
		
		}
		
		public function getStockDynamics($warehouse_identifier, $range){
		
		$data = [];
		
		$data['total_p_count'] = [];
		$data['total_q_count'] = [];
		$data['xaxis'] = [];
		
		$data['total_p_count']['label'] = 'Количество товаров';
		$data['total_q_count']['label'] = 'Количество единиц, шт.';
		
		
		switch ($range) {
			
			case 'week':
			$date_start = strtotime('-' . date('w') . ' days'); 
			
			for ($i = 0; $i < 7; $i++) {
				$date = date('Y-m-d', $date_start + ($i * 86400));
				
				$query = $this->db->query("SELECT * FROM `stocks_dynamics` WHERE warehouse_identifier = '" . $this->db->escape($warehouse_identifier) . "' AND (DATE(date_added) = '" . $this->db->escape($date) . "') ORDER BY date_added DESC LIMIT 1");
				
				if ($query->num_rows) {
					$data['total_p_count']['data'][] = array($i, (int)$query->row['p_count']);
					$data['total_q_count']['data'][] = array($i, (int)$query->row['q_count']);
					} else {
					$data['total_p_count']['data'][] = array($i, 0);
					$data['total_q_count']['data'][] = array($i, 0);
				}	
				
				setlocale(LC_TIME, "ru_RU.UTF8");
				$data['xaxis'][] = array($i, strftime("%a", strtotime($date)));
			}
			
			break;
			
			default:				
			case 'month':
			for ($i = 1; $i <= date('t'); $i++) {
				$date = date('Y') . '-' . date('m') . '-' . $i;
				
				$query = $this->db->query("SELECT * FROM `stocks_dynamics` WHERE warehouse_identifier = '" . $this->db->escape($warehouse_identifier) . "' AND (DATE(date_added) = '" . $this->db->escape($date) . "') ORDER BY date_added DESC LIMIT 1");
				
				if ($query->num_rows) {
					$data['total_p_count']['data'][] = array($i, (int)$query->row['p_count']);
					$data['total_q_count']['data'][] = array($i, (int)$query->row['q_count']);
					} else {
					$data['total_p_count']['data'][] = array($i, 0);
					$data['total_q_count']['data'][] = array($i, 0);
				}	
				
				setlocale(LC_TIME, "ru_RU.UTF8");
				$data['xaxis'][] = array($i, date('j', strtotime($date)));
			}
			
			break;			
			
		}
		
		return $data;
		}
		
		public function getTotalProductsByLayoutId($layout_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
			
			return $query->row['total'];
		}
		
		
		public function getStockQuantities($product_id){
			$query = $this->db->query("SELECT quantity_stock,quantity_stockK,quantity_stockM,quantity_stockMN,quantity_stockAS, FROM product WHERE product_id = '" . (int)$product_id . "'");
			
			return $query->row;
		}
		
		public function getChildStockProductId($product_id){
			$query = $this->db->query("SELECT product_id FROM product WHERE stock_product_id = '" . (int)$product_id . "'");
			
			if ($query->num_rows && isset($query->row['product_id'])){
				return $query->row['product_id'];
				} else {
				return false;
			}
		}
	}								