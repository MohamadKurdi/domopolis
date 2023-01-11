<?php
	
    class ModelCatalogProduct extends Model
    {
        public function updateViewed($product_id){
            $this->db->non_cached_query("UPDATE product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
		}
		
		public function getProductActiveCoupons($product_id){
			
			if (!$this->config->get('coupon_status')){
				return false;
			}
			
			$sql = "SELECT * FROM coupon  
			WHERE 
			status = 1
			AND display_list = 1
			AND birthday = 0
			AND (DATE(date_start) <= NOW() OR DATE(date_start) = '0000-00-00')
			AND (DATE(date_end) >= NOW() OR DATE(date_end) = '0000-00-00')
			AND ((type = 'F' AND currency = '" . $this->db->escape($this->config->get('config_regional_currency')) . "') OR (type = 'P'))
			AND (coupon_id IN (SELECT coupon_id FROM coupon_category WHERE category_id IN (SELECT p2c.category_id FROM product_to_category p2c LEFT JOIN category_to_store c2s ON (p2c.category_id = c2s.category_id) WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND product_id = '" . (int)$product_id . "'))
			OR coupon_id IN (SELECT coupon_id FROM coupon_collection WHERE collection_id IN (SELECT collection_id FROM product WHERE product_id = '" . (int)$product_id . "'))
			OR coupon_id IN (SELECT coupon_id FROM coupon_manufacturer WHERE manufacturer_id IN (SELECT manufacturer_id FROM product WHERE product_id = '" . (int)$product_id . "'))
			OR coupon_id IN (SELECT coupon_id FROM coupon_product WHERE product_id = '" . (int)$product_id . "'))";
			
			$sql .= " AND (only_in_stock = 0 OR (only_in_stock = 1 AND (SELECT `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$product_id . "') > 0))";
			
			if ($this->customer->isLogged()){
				$sql .= " AND (uses_customer = 0 OR uses_customer > (SELECT COUNT(*) FROM coupon_history WHERE customer_id = '" . $this->customer->getID() . "' AND coupon_id = coupon.coupon_id))";
			}
			
			$sql .= " ORDER BY date_start DESC LIMIT 1";	
			
			$query = $this->db->query($sql);
			
			return $query->row;
		}
		
		public function getAllProductActiveCoupons($product_id){
			
			if (!$this->config->get('coupon_status')){
				return false;
			}
			
			$sql = "SELECT *, (SELECT `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$product_id . "') as current_in_stock FROM coupon  
			WHERE 
			status = 1
			AND birthday = 0
			AND (DATE(date_start) <= NOW() OR DATE(date_start) = '0000-00-00')
			AND (DATE(date_end) >= NOW() OR DATE(date_end) = '0000-00-00')
			AND ((type = 'F' AND currency = '" . $this->db->escape($this->config->get('config_regional_currency')) . "') OR (type IN ('P', '3', '4', '5')))
			AND (coupon_id IN (SELECT coupon_id FROM coupon_category WHERE category_id IN (SELECT p2c.category_id FROM product_to_category p2c LEFT JOIN category_to_store c2s ON (p2c.category_id = c2s.category_id) WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND product_id = '" . (int)$product_id . "'))
			OR coupon_id IN (SELECT coupon_id FROM coupon_collection WHERE collection_id IN (SELECT collection_id FROM product WHERE product_id = '" . (int)$product_id . "'))
			OR coupon_id IN (SELECT coupon_id FROM coupon_manufacturer WHERE manufacturer_id IN (SELECT manufacturer_id FROM product WHERE product_id = '" . (int)$product_id . "'))
			OR coupon_id IN (SELECT coupon_id FROM coupon_product WHERE product_id = '" . (int)$product_id . "'))";
			
			$sql .= " AND (only_in_stock = 0 OR (only_in_stock = 1 AND (SELECT `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$product_id . "') > 0))";
			
			if ($this->customer->isLogged()){
				$sql .= " AND (uses_customer = 0 OR uses_customer > (SELECT COUNT(*) FROM coupon_history WHERE customer_id = '" . $this->customer->getID() . "' AND coupon_id = coupon.coupon_id))";
			}
			
			$sql .= " ORDER BY date_start DESC";						
			
			$query = $this->db->query($sql);					
			
			$result = [];
			if ($query->num_rows){
				foreach ($query->rows as $row){
					$result[] = (int)$row['coupon_id'];
				}
			}
			
			
			return $result;
		}
		
		public function recalculateCouponPrice($product_info, $coupon_info){
			
			if (!$this->config->get('coupon_status')){
				return false;
			}
			
			if (!$coupon_info){
				return false;
			}
			
			$price_to_recalculate = $product_info['price'];
			if ($product_info['special']){
				$price_to_recalculate = $product_info['special'];
			}
			
			if ($coupon_info['type'] == 'P' && $coupon_info['discount']){
				$coupon_price = ($price_to_recalculate - ($price_to_recalculate/100)*$coupon_info['discount']);				
				} elseif ($coupon_info['type'] == 'F' && $coupon_info['discount']){
				$coupon_price = ($price_to_recalculate - $coupon_info['discount']);	
			}
			
			if ($coupon_price < $price_to_recalculate){
				
				return array(
				'code' 			=> $coupon_info['code'],
				'coupon_price' 	=> $this->currency->format($this->tax->calculate($coupon_price, $product_info['tax_class_id'], $this->config->get('config_tax'))),
				'date_end'		=> date('Y/m/d', strtotime($coupon_info['date_end']))
				);
			}
			
			return false;
		}
		
		public function getProductActiveAction($product_id){
			
			$sql = "SELECT a.*, ad.caption, ad.label, ad.label_background, ad.label_color FROM actions a
			LEFT JOIN actions_description ad ON (ad.actions_id = a.actions_id AND ad.language_id = '" . $this->config->get('config_language_id') . "')
			LEFT JOIN `actions_to_store` a2s ON (a.actions_id = a2s.actions_id)
			WHERE 
			status = 1
			AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND (a.date_start <= UNIX_TIMESTAMP())
			AND (a.date_end >= UNIX_TIMESTAMP())
			AND (a.actions_id IN (SELECT actions_id FROM actions_to_product WHERE product_id = '" . (int)$product_id . "')
			OR a.actions_id IN (SELECT actions_id FROM actions_to_category_in WHERE category_id IN (SELECT p2c.category_id FROM product_to_category p2c LEFT JOIN category_to_store c2s ON (p2c.category_id = c2s.category_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND product_id = '" . (int)$product_id . "')))";
			
			$sql .= " AND (only_in_stock = 0 OR (only_in_stock = 1 AND (SELECT `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$product_id . "') > 0))";
			
			$sql .= " ORDER BY date_end DESC LIMIT 1";
			
			$query = $this->db->query($sql);
			
			return $query->row;
		}
		
		public function getAllProductActiveActionsForLabel($product_id){
			
			$activeCoupons = $this->getAllProductActiveCoupons($product_id);
			
			$sql = "SELECT a.actions_id, ad.label, ad.label_background, ad.label_color, ad.label_text FROM actions a
			LEFT JOIN actions_description ad ON (ad.actions_id = a.actions_id AND ad.language_id = '" . $this->config->get('config_language_id') . "')
			LEFT JOIN `actions_to_store` a2s ON (a.actions_id = a2s.actions_id)
			WHERE 
			status = 1
			AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND (a.date_start <= UNIX_TIMESTAMP())
			AND (a.date_end >= UNIX_TIMESTAMP())
			AND (";
			
			$sql .= PHP_EOL;
			
			$sql .= " a.actions_id IN (SELECT actions_id FROM actions_to_product WHERE product_id = '" . (int)$product_id . "')
			OR a.actions_id IN (SELECT actions_id FROM actions_to_category_in WHERE category_id IN (SELECT p2c.category_id FROM product_to_category p2c LEFT JOIN category_to_store c2s ON (p2c.category_id = c2s.category_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND product_id = '" . (int)$product_id . "'))";
			
			$sql .= " AND (only_in_stock = 0 OR (only_in_stock = 1 AND (SELECT `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$product_id . "') > 0))";
			
			
			if ($activeCoupons){
				$sql .= PHP_EOL;
				$sql .= " OR a.actions_id IN (SELECT action_id FROM coupon WHERE coupon_id IN (" . implode(',', $activeCoupons) . "))";
			}
			
			$sql .= ")";
			$sql .= " ORDER BY date_end DESC";	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
        public function catchAlsoViewed($product_id){
			
            if (empty($this->session->data['alsoViewed'])) {
				$this->session->data['alsoViewed'] = $product_id;
				} else {
				if (strstr($this->session->data['alsoViewed'], $product_id) == false) {
					$this->session->data['alsoViewed'] .= ',' . $product_id;
				}
			}
			
			$alsoViewed = explode(',', $this->session->data['alsoViewed']);
			
			sort($alsoViewed);
			
			$groupedalsoViewed = [];
			foreach ($alsoViewed as $k => $b) {
				for ($i = 1; $i < count($alsoViewed); $i++) {
					if (!empty($alsoViewed[$k + $i])) {
						$groupedalsoViewed[] = array('low' => $b, 'high' => $alsoViewed[$k + $i]);
					}
				}
			}
			
			if (empty($this->session->data['alsoViewed'])) {
				$this->session->data['alsoViewed'] = $product_id;
			}
			
			$alsoViewed = explode(',', $this->session->data['alsoViewed']);
			
			$groupedalsoViewed = array_slice($groupedalsoViewed, -3);
			
			foreach ($groupedalsoViewed as $p) {
				if (mt_rand(0, 1) == 1) {
					$this->db->non_cached_query("INSERT INTO `alsoviewed` (low, high, number, date_added) VALUES ('" . (int)$p['low'] . "', '" . (int)$p['high'] . "', '1', NOW()) ON DUPLICATE KEY UPDATE number = number+1");
				}
			}
		}		
		
		public function prepareProductToArray($results, $bestsellers = array(), $ajax = false){					
			$array = [];
			
			$dimensions = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
			);
			
			foreach ($results as $result) {	
				if ($result){

					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $dimensions['w'], $dimensions['h']);
						$image_mime = $this->model_tool_image->getMime($result['image']);
						$image_webp = $this->model_tool_image->resize_webp($result['image'], $dimensions['w'], $dimensions['h']);
					} else {
						$image = $this->model_tool_image->resize($this->config->get('config_noimage'), $dimensions['w'], $dimensions['h']);
						$image_mime = $this->model_tool_image->getMime($this->config->get('config_noimage'));
						$image_webp = $this->model_tool_image->resize_webp($this->config->get('config_noimage'), $dimensions['w'], $dimensions['h']);
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}
					
					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}	
					
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
					} else {
						$tax = false;
					}		
					
					if (isset($result['display_price_national']) && $result['display_price_national'] && $result['display_price_national'] > 0 && $result['currency'] == $this->currency->getCode()){
						$price = $this->currency->format($this->tax->calculate($result['display_price_national'], $result['tax_class_id'], $this->config->get('config_tax')), $result['currency'], 1);
					}
					
					if ($option_prices = $this->getProductOptionPrices($result['product_id'])){
						if (isset($option_prices['special']) && $option_prices['special']){
							$special = $option_prices['special'];
						} else {
							$special = false;
						}

						if (isset($option_prices['price']) && $option_prices['price']){
							$price = $option_prices['price'];
						}

						if ($option_prices['result']){
							$result['price'] = $option_prices['result']['price'];
							$result['special'] = $option_prices['result']['special'];
						}
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'];
					} else {
						$rating = false;
					}
					
					$is_not_certificate = (strpos($result['location'], 'certificate') === false);
					
					$_description = '';
					if ($is_not_certificate){
						
						if (mb_strlen($result['description']) > 10){
							$_description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 128) . '..';
						}
						
					} else {
						$_description = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
					}
					
					$stock_data = $this->parseProductStockData($result);
					$ecommerceData = array(
						'id'		=> (int)$result['product_id'],
						'name' 		=> prepareEcommString($result['name']),
						'gtin' 		=> prepareEcommString($result['ean']),			
						'brand' 	=> prepareEcommString($result['manufacturer']),		
						'price' 	=> prepareEcommString($special?$special:$price),
						'category' 	=> prepareEcommString($this->getGoogleCategoryPath($result['product_id']))
					);

					
					$array[] = array(
						'new'         				=> $result['new'],
						'bestseller'  				=> $bestsellers?in_array($result['product_id'], $bestsellers):false,
						'show_action' 				=> $result['additional_offer_count'],
						'additional_offer_product' 	=> $result['additional_offer_product'],
						'active_coupon'				=> $ajax?$this->recalculateCouponPrice($result, $this->getProductActiveCoupons($result['product_id'])):false,
						'active_actions'			=> $ajax?$this->getAllProductActiveActionsForLabel($result['product_id']):false,
						'product_id'  				=> $result['product_id'],
						'is_certificate'			=> (strpos($result['location'], 'certificate') !== false),
						'ecommerceData'				=> $ecommerceData,
						'stock_type'  				=> $stock_data['stock_type'],
						'stock_text'  				=> $result['stock_text'],						
						'show_delivery_terms' 		=> $stock_data['show_delivery_terms'],
						'manufacturer' 				=> $result['manufacturer'],
						'thumb'       				=> $image,
						'thumb_mime'  				=> $image_mime,
						'thumb_webp'  				=> $image_webp,
						'is_set' 	  				=> $result['set_id'],
						'name'        				=> $result['name'],
						'variant_name_1'        	=> $result['variant_name_1'],
						'variant_name_2'        	=> $result['variant_name_2'],
						'variant_value_1'        	=> $result['variant_value_1'],
						'variant_value_2'        	=> $result['variant_value_2'],
						'weight'                   	=> $this->weight->format($result['weight'], $result['weight_class_id']),											
						'length'                   	=> $this->length->format($result['length'], $result['length_class_id']),
						'width'                    	=> $this->length->format($result['width'], $result['length_class_id']),
						'height'                   	=> $this->length->format($result['height'], $result['length_class_id']),
						'description' 				=> $_description,
						'price'       				=> $price,
						'special'     				=> $special,
						'points'	  				=> $this->currency->formatBonus($result['reward'], true),
						'colors'	 				=> $this->getProductColorsByGroup($result['product_id'], $result['color_group']),
						'options'	  				=> $this->getProductOptionsForCatalog($result['product_id']),	
						'variants_count'			=> $result['variants_count'],			
						'variants_text'				=> ($result['variants_count'])?('+ ' . $result['variants_count'] . ' ' . morphos\Russian\NounPluralization::pluralize($result['variants_count'], $this->language->get('text_variant'))):'',
						'saving'      				=> round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
						'tax'         				=> $tax,
						'rating'      				=> $result['rating'],
						'count_reviews' 			=> $result['reviews'],
						'minimum' 					=> $result['minimum'],
						'sku'      	  				=> $result['model']?$result['model']:$result['sku'],
						'sort_order'  				=> $result['sort_order'],
						'can_not_buy' 				=> ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
						'need_ask_about_stock' 		=> ($result['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')),
						'has_child'  				=> $result['has_child'],
						'stock_status'  			=> $result['stock_status'],
						'location'      			=> $result['location'],
						'reviews'     				=> sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
						'quickview'   				=> $this->url->link('product/quickview', 'product_id=' . $result['product_id']),
						'href'        				=> $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}
			}
			
			return $array;
		}
		
		public function getProductYAM($product_id, $cached = true){
			
			if ($this->config->get('config_yam_offer_id_prefix')){
				$product_id = (int)str_replace($this->config->get('config_yam_offer_id_prefix'), '', $product_id);
			}
			
			return $this->getProduct($product_id, $cached);
		}

		public function getUncachedProductForStore($product_id, $store_id){		
			$this->load->model('setting/setting');	
			$this->model_setting_setting->loadSettings($store_id);

			return $this->getProduct($product_id, false);
		}
		
		public function getProduct($product_id, $cached = true, $simple = false) {
			
			$this->load->model('catalog/group_price');
			$this->load->model('catalog/product_status');
			$this->load->model('kp/product');		
			
			$product_data = $this->cache->get($this->registry->createCacheQueryStringData(__METHOD__, [$product_id], [$simple]));
			
			if (!$cached){
				$product_data = false;
			}
			
			if (!$product_data) {
				$sql = '';		
				$sql .= "SELECT DISTINCT *, ";				
				if ($simple != 'simple'){
					$sql .= " p.image, p.xrating as rating, p.xreviews as reviews, p.sort_order, ";
					$sql .= " pd.name AS name, pd.color AS color, pd.material AS material, pd.alt_image, pd.title_image,";
					$sql .= " m.name AS manufacturer, m.image as manufacturer_img, ";
					$sql .= " (SELECT st.set_id FROM `set` st WHERE p.product_id = st.product_id LIMIT 1) as set_id, ";
					$sql .= " (SELECT COUNT(*) FROM product_additional_offer pao LEFT JOIN product_additional_offer_to_store pao2s ON (pao.product_additional_offer_id = pao2s.product_additional_offer_id) WHERE  pao.product_id = p.product_id AND pao.date_end > NOW() AND (ISNULL(pao2s.store_id) OR pao2s.store_id = '" . (int)$this->config->get('config_store_id') . "')) AS additional_offer_count, ";

					if ($this->config->get('config_no_zeroprice')){
						$sql .= " (SELECT COUNT(p3.product_id) FROM product p3 ";

						if (!$this->config->get('config_single_store_enable')){
							$sql .= " LEFT JOIN product_to_store p32s ON (p3.product_id = p32s.product_id) ";
						}						

						$sql .= " WHERE p3.main_variant_id = p.product_id  AND (p3.price > 0 OR p3.price_national > 0) AND p3.status = 1 AND p3.is_markdown = 0 ";
						
						if (!$this->config->get('config_single_store_enable')){
							$sql .= " AND p32s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
						}

						$sql .= ")";
					} else {
						$sql .= " (SELECT COUNT(p3.product_id) FROM product p3 ";
						
						if (!$this->config->get('config_single_store_enable')){
							$sql .= " LEFT JOIN product_to_store p32s ON (p3.product_id = p32s.product_id) ";
						}

						$sql .= " WHERE p3.main_variant_id = p.product_id  AND (p3.price > 0 OR p3.price_national > 0) AND p3.status = 1 AND p3.is_markdown = 0 ";

						if (!$this->config->get('config_single_store_enable')){
							$sql .= " AND p32s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
						}

						$sql .= " AND (";
						$sql .= " p.price > 0 OR p.price_national > 0";
						$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
						$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
						$sql .= "))";
					}
					$sql .= " AS variants_count, ";

					$sql .= " (SELECT c5.google_category_id FROM category c5 WHERE c5.category_id = (SELECT p2cm5.category_id FROM product_to_category p2cm5 WHERE p2cm5.product_id = p.product_id ORDER BY main_category DESC LIMIT 1)) as google_category_id, ";
					$sql .= " (SELECT GROUP_CONCAT(p2c5.category_id SEPARATOR ':') FROM product_to_category p2c5 WHERE p2c5.product_id = p.product_id GROUP BY p2c5.product_id) as categories, ";
					$sql .= " (SELECT GROUP_CONCAT(pi5.image SEPARATOR ':') FROM product_image pi5 WHERE pi5.product_id = p.product_id GROUP BY pi5.product_id) as images, ";
					$sql .= " (SELECT category_id FROM product_to_category p2cm WHERE p2cm.product_id = p.product_id ORDER BY main_category DESC LIMIT 1) as main_category_id, ";
					$sql .= " (SELECT ao_product_id FROM product_additional_offer pao LEFT JOIN product_additional_offer_to_store pao2s ON (pao.product_additional_offer_id = pao2s.product_additional_offer_id) WHERE pao.product_id = p.product_id AND pao.date_end > NOW() AND pao.percent = 100 AND (ISNULL(pao2s.store_id) OR pao2s.store_id = '" . (int)$this->config->get('config_store_id') . "')  ORDER BY priority ASC LIMIT 1) AS additional_offer_product_id, ";	
				}


				$sql .= " (SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND price > 0 AND pd2.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, ";
				$sql .= "(SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special, ";
				$sql .= "(SELECT date_end FROM product_special ps WHERE ps.product_id = p.product_id AND price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special_date_end, ";
				$sql .= "(SELECT currency_scode FROM product_special ps WHERE ps.product_id = p.product_id AND price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special_currency, ";
				$sql .= "(SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as store_overload_price, ";
				$sql .= "(SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as store_overload_price_national, ";		

				if ($this->config->get('config_yam_offer_id_price_enable')){
					$sql .= "(SELECT price FROM product_price_national_to_yam ppn2yam WHERE ppn2yam.product_id = p.product_id AND price > 0 AND ppn2yam.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as yam_overload_price_national, ";		
				}

				$sql .= " (SELECT stock_status_id FROM product_stock_status pss WHERE pss.product_id = p.product_id AND pss.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as overload_stock_status_id, ";
				$sql .= "(SELECT name FROM stock_status sst WHERE sst.stock_status_id = (SELECT stock_status_id FROM product_stock_status pss WHERE pss.product_id = p.product_id AND pss.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) AND sst.language_id = '" . (int)$this->config->get('config_language_id') . "') as overload_stock_status,	";
				$sql .= "(SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status ";
				

				$sql .= " FROM product p 
				LEFT JOIN product_description pd ON (p.product_id = pd.product_id) ";

				if (!$this->config->get('config_single_store_enable')){
					$sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) ";
				}

				if ($simple != 'simple'){
					$sql .= " LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) ";
				}

				$sql .= " WHERE 
				p.product_id = '" . (int)$product_id . "' 
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND p.status = '1' 
				AND p.date_available <= NOW() ";

				if (!$this->config->get('config_single_store_enable')){
					$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
				}

				if (!$cached){
					$query = $this->db->non_cached_query($sql);
					} else {
					$query = $this->db->query($sql);
				}
				
				if ($query->num_rows) {
					$yam_price = $query->row['yam_price'];
					
					if ($query->row['yam_currency'] != $this->config->get('config_regional_currency')){
						$yam_price = $this->currency->convert($yam_price, $query->row['yam_currency'], $this->config->get('config_regional_currency'));
					}
					
					$yam_special = $query->row['yam_special'];
					if ($query->row['yam_currency'] != $this->config->get('config_regional_currency')){
						$yam_special = $this->currency->convert($yam_special, $query->row['yam_currency'], $this->config->get('config_regional_currency'));
					}
					
					if (!empty($query->row['yam_overload_price_national'])){
						$yam_price = $query->row['yam_overload_price_national'];
					}
					
					if (!$query->row['yam_product_id']){
						$query->row['yam_product_id'] = $this->config->get('config_yam_offer_id_prefix') . $product_id;
					}
					
					if (isset($query->row['store_overload_price']) && $query->row['store_overload_price']) {
						$query->row['price'] = $query->row['store_overload_price'];
					}
					
					$do_percent = true;
					$overload_price_national = false;
					$has_rrp = false;
					if (isset($query->row['store_overload_price_national']) && $query->row['store_overload_price_national']) {
						$query->row['price'] = $this->currency->convert($query->row['store_overload_price_national'],
						$this->config->get('config_regional_currency'), $this->config->get('config_currency'),
						false, false);
						$overload_price_national = $query->row['price'];
						$do_percent = false;
						$has_rrp = true;
					}

					$price_opt = $this->model_catalog_group_price->updatePrice($product_id,
					($query->row['discount'] ? $query->row['discount'] : $query->row['price']));
					if ($price_opt < $query->row['price']) {
						$price_recommend = $query->row['price'];
						} else {
						$price_recommend = false;
					}
					
					if ($query->row['price_national']) {
						$display_price_national = $query->row['price_national'];
						$price_national = $this->model_catalog_group_price->updatePrice($product_id,
						$query->row['price_national']);
						} else {
						$display_price_national = false;
						$price_national = false;
					}
					
					if ($query->row['special'] && isset($query->row['special_currency']) && $query->row['special_currency']){
						if ($query->row['special_currency'] != $this->config->get('config_currency')){
							$query->row['special'] = $this->currency->convert($query->row['special'],
							$query->row['special_currency'], $this->config->get('config_currency'),
							false, false);						
						}
					}
					
					$special = $this->model_catalog_group_price->updatePrice($product_id, $query->row['special']);					
					if ($this->currency->percent) {
						if ($this->currency->plus) {
							if ($do_percent) {
								$price_opt = $price_opt + ($price_opt / 100 * (int)$this->currency->percent);
								$special = $special + ($special / 100 * (int)$this->currency->percent);
							}
							
							} else {
							if ($do_percent) {
								$price_opt = $price_opt - ($price_opt / 100 * (int)$this->currency->percent);
								$special = $special - ($special / 100 * (int)$this->currency->percent);
							}
							
						}
					}

					$mpp_price = 0;
					if ($this->currency->percent) {
						if ($this->currency->plus) {
							$mpp_price = $mpp_price + ($mpp_price / 100 * (int)$this->currency->percent);
							} else {
							$mpp_price = $mpp_price - ($mpp_price / 100 * (int)$this->currency->percent);						
						}
					}
					
					if ($price_opt <= $special) {
						$special = false;
					}
					
					$new = false;
					if ($query->row['new'] && $query->row['date_added'] > date("Y-m-d H:i:s", strtotime('-45 day'))) {
						$new = true;
					}
					
					if ($query->row['stock_product_id']) {
						$main_stock_query = $this->db->query("SELECT quantity_stock, `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$query->row['stock_product_id'] . "'");
						if ($main_stock_query->num_rows && isset($main_stock_query->row['quantity_stock']) && isset($main_stock_query->row[$this->config->get('config_warehouse_identifier')])) {
							$query->row['quantity_stock'] = $main_stock_query->row['quantity_stock'];
							$query->row[$this->config->get('config_warehouse_identifier')] = $main_stock_query->row[$this->config->get('config_warehouse_identifier')];
						}
					}
					
					if ($simple != 'simple'){
						$additional_offer_product = false;
						if ($query->row['additional_offer_product_id'] && $query->row['additional_offer_product_id'] != $product_id){
							$additional_offer_product = $this->getProduct($query->row['additional_offer_product_id']);
						}
					}
					
					//Логика подмены SKU
					if ($this->config->get('config_product_replace_sku_with_product_id')){
						$query->row['sku'] = $query->row['product_id'];
						$query->row['model'] = $query->row['product_id'];
						
						if ($this->config->get('config_product_use_sku_prefix')){
							$query->row['sku'] = trim($this->config->get('config_product_use_sku_prefix')) . $query->row['sku'];
							$query->row['model'] = trim($this->config->get('config_product_use_sku_prefix')) . $query->row['model'];
						}
					}

					//Логика переназначения статусов наличия
					if ($query->row['overload_stock_status_id'] && $query->row['overload_stock_status']){
						$query->row['stock_status_id'] 	= $query->row['overload_stock_status_id'];
						$query->row['stock_status'] 	= $query->row['overload_stock_status'];
					}

					//Логика работы только со складом, отменяет переназначение статуса на складе
					if ($this->config->get('config_warehouse_only')){
						if (!$query->row[$this->config->get('config_warehouse_identifier')]){
							$query->row['quantity_stock'] 			= 0;
							$query->row['quantity'] 				= 0;
							$query->row['stock_status_id']			= $this->config->get('config_overload_stock_status_id');
							$overload_stock_status_query 			= $this->db->query("SELECT name FROM stock_status WHERE stock_status_id = '" . (int)$this->config->get('config_overload_stock_status_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");
							$query->row['stock_status']				= $overload_stock_status_query->row['name'];

						}
					}

					if ($simple == 'simple'){

						$product_data = [
							'product_id'               => $query->row['product_id'],
							'price'                    => $price_opt,
							'mpp_price'                => $mpp_price,
							'has_rrp'				   => $has_rrp,
							'price_national'           => $price_national,
							'display_price_national'   => $display_price_national,
							'price_recommend'          => $price_recommend,
							'currency'                 => $query->row['currency'],		
							'special'                  => $special,
							'special_date_end'         => $query->row['special_date_end'],
							'quantity'                 => $query->row['quantity'],							
							'quantity_stock'           => $query->row['quantity_stock'],
							'current_in_stock'		   => $this->config->get('config_warehouse_identifier')?($query->row[$this->config->get('config_warehouse_identifier')]>0):false,
							'current_in_stock_q'	   => $query->row[$this->config->get('config_warehouse_identifier')]
						];


					} else {

						$product_data = [
							'product_id'               	=> $query->row['product_id'],
							'new'               	   	=> $new,
							'stock_product_id'         	=> $query->row['stock_product_id'],					
							'name'                     	=> $query->row['name'],
							'variant_name_1'        	=> $query->row['variant_name_1'],
							'variant_name_2'        	=> $query->row['variant_name_2'],
							'variant_value_1'        	=> $query->row['variant_value_1'],
							'variant_value_2'        	=> $query->row['variant_value_2'],
							'is_certificate'		   	=> (strpos($query->row['location'], 'certificate') !== false),
							'description'              	=> $query->row['description'],
							'meta_description'         	=> $query->row['meta_description'],
							'meta_keyword'             	=> $query->row['meta_keyword'],
							'seo_h1'                   	=> $query->row['seo_h1'],
							'seo_title'                	=> $query->row['seo_title'],
							'tag'                      	=> $query->row['tag'],
							'color'					   	=> $query->row['color'],
							'material'				   	=> $query->row['material'],							
							'markdown_product_id'      	=> $query->row['markdown_product_id'],
							'is_markdown'              	=> $query->row['is_markdown'],
							'markdown_appearance'      	=> $query->row['markdown_appearance'],
							'markdown_condition'       	=> $query->row['markdown_condition'],
							'markdown_pack'            	=> $query->row['markdown_pack'],
							'markdown_equipment'       	=> $query->row['markdown_equipment'],							
							'variants_count'		   => $query->row['variants_count'],
							'main_variant_id'		   => $query->row['main_variant_id'],								
							'set_id'                   => $query->row['set_id'],
							'model'                    => $query->row['model'],
							'sku'                      => $query->row['sku'],
							'upc'                      => $query->row['upc'],
							'ean'                      => $query->row['ean'],
							'jan'                      => $query->row['jan'],
							'isbn'                     => $query->row['isbn'],
							'mpn'                      => $query->row['mpn'],
							'asin'                     => $query->row['asin'],
							'amazon_product_link'     => $query->row['amazon_product_link'],
							'location'                 => $query->row['location'],
							'current_in_stock'		   => $this->config->get('config_warehouse_identifier')?($query->row[$this->config->get('config_warehouse_identifier')]>0):false,
							'current_in_stock_q'	   => $query->row[$this->config->get('config_warehouse_identifier')],
							'quantity'                 => $query->row['quantity'],							
							'quantity_stock'           => $query->row['quantity_stock'],
							'quantity_stockM'          => $query->row['quantity_stockM'],
							'quantity_stockK'          => $query->row['quantity_stockK'],
							'quantity_stockMN'         => $query->row['quantity_stockM'],
							'quantity_stockAS'         => $query->row['quantity_stockM'],
							'stock_status'             => $query->row['stock_status'],
							'stock_status_id'          => $query->row['stock_status_id'],
							'image'                    => $query->row['image'],
							'images'                   => $query->row['images'],
							'main_category_id'         => $query->row['main_category_id'],
							'categories'			   => $query->row['categories'],
							'google_category_id'	   => $query->row['google_category_id'],
							'manufacturer_id'          => $query->row['manufacturer_id'],
							'collection_id'            => $query->row['collection_id'],
							'manufacturer'             => $query->row['manufacturer'],
							'manufacturer_img'         => $query->row['manufacturer_img'],
							'competitors'              => $query->row['competitors'],
							'competitors_ua'           => $query->row['competitors_ua'],
							'price'                    => $price_opt,
							'mpp_price'                => $mpp_price,
							'has_rrp'				   => $has_rrp,
							'price_national'           => $price_national,
							'display_price_national'   => $display_price_national,
							'currency'                 => $query->row['currency'],
							'price_recommend'          => $price_recommend,							
							'special'                  => $special,
							'special_date_end'         => $query->row['special_date_end'],
							'points'                   => $query->row['points'],
							'tax_class_id'             => $query->row['tax_class_id'],
							'date_available'           => $query->row['date_available'],
							'weight'                   => $query->row['weight'],					
							'weight_class_id'          => $query->row['weight_class_id'],
							'length'                   => $query->row['length'],
							'width'                    => $query->row['width'],
							'height'                   => $query->row['height'],
							'length_class_id'          => $query->row['length_class_id'],
							'pack_weight'              => $query->row['pack_weight'],
							'pack_weight_class_id'     => $query->row['pack_weight_class_id'],
							'pack_length'              => $query->row['pack_length'],
							'pack_width'               => $query->row['pack_width'],
							'pack_height'              => $query->row['pack_height'],
							'pack_length_class_id'     => $query->row['pack_length_class_id'],
							'subtract'                 => $query->row['subtract'],
							'has_child'                => $query->row['has_child'],
							'rating'                   => $query->row['rating'] ? round($query->row['rating']): 0,
							'reviews'                  => $query->row['reviews'] ? $query->row['reviews'] : 0,
							'minimum'                  => $query->row['minimum'] ? $query->row['minimum']: 1,
							'sort_order'               => $query->row['sort_order'],
							'status'                   => $query->row['status'],
							'date_added'               => $query->row['date_added'],
							'date_modified'            => $query->row['date_modified'],
							'viewed'                   => $query->row['viewed'],					
							'color_group'              => $query->row['color_group'],
							'bought_for_month'         => $query->row['bought_for_month'],
							'bought_for_week'          => $query->row['bought_for_week'],
							'is_option_with_id'        => $query->row['is_option_with_id'],
							'is_option_for_product_id' => $query->row['is_option_for_product_id'],
							'youtube'                  => $query->row['youtube'],
							'additional_offer_count'   => $query->row['additional_offer_count'],
							'additional_offer_product_id' => $query->row['additional_offer_product_id'],
							'additional_offer_product' => $additional_offer_product,
							'alt_image'                => $query->row['alt_image'],
							'title_image'              => $query->row['title_image'],
							'yam_price_national'	   => $yam_price,
							'yam_special_national'	   => $yam_special,
							'yam_currency'			   => $query->row['yam_currency'],
							'yam_percent'			   => $query->row['yam_percent'],
							'yam_special_percent'	   => $query->row['yam_special_percent'],
							'yam_product_id'		   => $query->row['yam_product_id'],
							'yam_in_feed'		   	   => $query->row['yam_in_feed'],
							'yam_disable'		   	   => $query->row['yam_disable'],
							'is_illiquid'		   	   => $query->row['is_illiquid'],
							'region_id'				   => $this->config->get('config_googlelocal_code')
						]; 

						if (!$simple || $simple != 'simple'){
							//$product_data['statuses'] 		= $this->model_catalog_product_status->getHTMLProductStatuses($product_id);
							$product_data['reward'] 		= $this->cart->getCurrentProductReward($product_data);
							$product_data['stock_text'] 	= $this->parseProductStockDataOneString($product_data);					
							$product_data['stock_dates'] 	= $this->parseProductStockDataOneString($product_data, true);					
						}
					}
					
					} else {
					$product_data = false;
				}
				
				$this->cache->set($this->registry->createCacheQueryStringData(__METHOD__, [$product_id], [$simple]), $product_data);
			}
			
			return $product_data;
		}
		
		public function getProductOptionPrices($product_id)	{

			if (!$this->config->get('config_option_price_enable')){
				return false;
			}	
			
			$prices_data = $this->cache->get('product.pricerange.' . (int)$product_id . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id'));
			
			if (!$prices_data) {
				
				$prices_data = array(
				'price'   => false,
				'special' => false,
				'result'  => false
				);
				
				$options = $this->db->query("SELECT DISTINCT this_is_product_id FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");
				
				if ($options->num_rows > 1) {
					$we_have_discount = false;
					
					$prices = [];
					$specials = [];
					foreach ($options->rows as $index => $option) {
						if ($product = $this->getProduct($option['this_is_product_id'])) {
							$prices[$index] = (float)$product['price'];
							$specials[$index] = (float)$product['special'];
						}
					}
					
					$tmp = [];
					//минимальная и максимальная обычная цена у нас есть в любом случае
					//это мин-макс из массива обычных цен
					$tmp['price'] = array(
					'min'  => min($prices),
					'max'  => max($prices),
					'only' => (max($prices) == min($prices)) ? min($prices) : false
					);
					
					$tmp['special'] = array(
					'min'  => false,
					'max'  => false,
					'only' => false
					);
					
					//изначально минимальная цена - обычная цена, переназначим дальше
					$min_sale_price = min($prices);
					
					//теперь нужно определить факт наличия скидки
					//первый факт наличия - граница снизу - минимальная скидка меньше минимальной цены
					foreach ($specials as $key => $special) {
						if ($special > 0 && $special < $min_sale_price) {
							$min_sale_price = $special;
							$we_have_discount = $we_have_discount || true;
						}
					}
					
					//разберем второй факт наличия скидки - граница сверху
					//Скидка сверху существует только в том случае, если нет ни одной обычной цены выше самой высокой скидочной за исключением цены, к которой эта скидочная применяется
					//Однако мы не можем применить однозначно выражение max($special), поскольку количественное значение максимальной скидки у нас может быть применена к 2+ обычным ценам
					$max_special = max($specials);
					
					//все дальнейшее имеет смысл если у нас вообще есть скидки
					if ($max_special > 0) {
						
						//найдем максимальную цену, которая привязана к максимальной скидочной
						$max_price = 0;
						$max_price_which_has_special_key = 0;
						foreach ($specials as $key => $special) {
							if ($special == $max_special) {
								if ($prices[$key] >= $max_price) {
									$max_price = $prices[$key];
									$max_price_which_has_special_key = $key;
								}
							}
						}
						
						//у нас есть максимальная цена, привязанная к максимальной скидочной. на следующем шаге мы проверяем, существует ли какая-либо цена, кроме данной, больше нежели максимальная скидочная
						//запомним эти цены для перезагрузки процента скидки
						$prices_data['result'] = array(
						'price'   => $prices[$max_price_which_has_special_key],
						'special' => $specials[$max_price_which_has_special_key]
						);
						
						//если такая существует - то глобальная максимальная цена - это она, если нет - это максимальная скидочная, которую мы нашли
						unset($prices[$max_price_which_has_special_key]);
						$max_prices = [];
						foreach ($prices as $key => $price) {
							if ($price > $max_special) {
								$max_prices[] = $price;
							}
						}
						
						//если такие цены существуют - то это максимальная из них и скидку мы покажем только в случае, если у нас есть нижняя граница
						if (count($max_prices)) {
							$we_have_discount = $we_have_discount || false;
							$max_sale_price = max($max_prices);
							} else {
							$we_have_discount = $we_have_discount || true;
							$max_sale_price = $max_special;
						}
						
						//скидка у нас существует, и мы в нее впишем минимальную и максимальную цену, за которую можем купить товар
						if ($we_have_discount) {
							
							$tmp['special'] = array(
							'min'  => $min_sale_price,
							'max'  => $max_sale_price,
							'only' => ($min_sale_price == $max_sale_price) ? $min_sale_price : false
							);
							
						}
					}
					
					if ($tmp['price']['only']) {
						$prices_data['price'] = $this->currency->format($tmp['price']['only']);
						} else {
						$prices_data['price'] = $this->currency->format($tmp['price']['min']) . ' - ' . $this->currency->format($tmp['price']['max']);
					}
					
					if ($tmp['special']['only']) {
						$prices_data['special'] = $this->currency->format($tmp['special']['only']);
						} else {
						if ($tmp['special']['min'] && $tmp['special']['max']) {
							$prices_data['special'] = $this->currency->format($tmp['special']['min']) . ' - ' . $this->currency->format($tmp['special']['max']);
							} else {
							$prices_data['special'] = false;
						}
					}
					
					} else {
					$prices_data = false;
				}
				
				$this->cache->set('product.pricerange.' . (int)$product_id . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id'),
				$prices_data);
			}
			
			return $prices_data;
		}
				
		public function getProductsAverageRating($data = []){			
		}
		
		public function getProductsAverageRatingCount($data = []){
		}
		
		public function cleanupCategoryWithCurrentStockData($category_id){
			$this->db->query("DELETE FROM product_to_category WHERE category_id = '" . $category_id . "' AND product_id NOT IN (SELECT product_id FROM product WHERE `" . $this->config->get('config_warehouse_identifier') . "` > 0)");
		}
		
		public function getProductsByIDS($productIDS, $data = []){
			$results = [];

			foreach ($productIDS as $product_id) {
				$product_id = (int)trim($product_id);
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {

					if (!empty($data['filter_quantity'])){
						if ($product_info['quantity'] > 0){
							$results[$product_id] = $product_info;
						}
					} else {
						$results[$product_id] = $product_info;
					}				
				}
			}

			return $results;
		}
		
		public function getProducts($data = []) {
			
			if (!isset($data['no_child'])) {
				$data['no_child'] = false;
			}
			
			if (!isset($data['return_parent'])) {
				$data['return_parent'] = false;
			}		

			if ($this->config->get('config_warehouse_only')){
				$default_stock_status = $this->config->get('config_overload_stock_status_id');
			} else {
				$default_stock_status = $this->config->get('config_stock_status_id');
			}

			$sql = "SELECT DISTINCT p.product_id, p.is_option_for_product_id, p.xrating AS rating, ";

			//Логика переназначения статуса при работе только со склада
			if ($this->config->get('config_warehouse_only')){							
				$sql .= " IF(p." . $this->config->get('config_warehouse_identifier') . " > 0, " . $this->config->get('config_in_stock_status_id') . ", " . $this->config->get('config_overload_stock_status_id') . ") as stock_status_id";
			} else {
				$sql .= " IF( (p.quantity_stock + p.quantity_stockK + p.quantity_stockM + p.quantity_stockMN + p.quantity_stockAS) > 0, 
				IF(p." . $this->config->get('config_warehouse_identifier') . " > 0, " . $this->config->get('config_in_stock_status_id') . ", " . $default_stock_status . "), IF ((SELECT stock_status_id FROM product_stock_status WHERE product_id = p.product_id AND store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0,  (SELECT stock_status_id FROM product_stock_status WHERE product_id = p.product_id AND store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1), p.stock_status_id)
				) as stock_status_id";
			}

			if (!empty($data['sort']) && $data['sort'] == 'p.price') {
				$sql .= ", ";
				$sql .= " (SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.quantity = '1' AND price > 0 AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) AND pd2.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount,  ";
				$sql .= " (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as store_overload_price,  ";
				$sql .= " (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as store_national_overload_price,  ";
				$sql .= " (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND price > 0 AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special,  ";
				$sql .= " (SELECT currency_scode FROM product_special ps WHERE ps.product_id = p.product_id AND price > 0 AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS currency_scode ";
			}

			if (!empty($data['filter_different_categories'])){
				$sql .= ", ";
				$sql .= "(SELECT category_id FROM product_to_category p2c2 WHERE p2c2.product_id = p.product_id ORDER BY main_category DESC LIMIT 1) as main_category_id";				
			}

			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM category_path cp LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id)";
					} else {
					$sql .= " FROM product_to_category p2c";
				}
				
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN product p ON (pf.product_id = p.product_id)";
					} else {
					$sql .= " LEFT JOIN product p ON (p2c.product_id = p.product_id)";
				}
				} else {
				$sql .= " FROM product p";
			}
			
			$sql .= " LEFT JOIN product_description pd ON (p.product_id = pd.product_id) ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) ";
			}

			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			}

			if (!empty($data['sort']) && $data['sort'] == 'p.viewed' && !empty($data['filter_only_viewed'])) {
				if ((int)$data['filter_only_viewed'] > 1){
					$sql .= " AND p.viewed > '" . (int)$data['filter_only_viewed'] . "'";
				} else {
					$sql .= " AND p.viewed > 0 ";
				}
			}

			if (!empty($data['sort']) && $data['sort'] == 'p.date_added' && empty($data['new']) && empty($data['newlong'])) {
				if (!empty($data['filter_date_added_threshold'])){					
					$sql .= " AND p.date_added >= '" . date('Y-m-d', strtotime('-'. (int)$data['filter_date_added_threshold'] .' day')) . "' ";
				} else {
					$sql .= " AND p.date_added >= '" . date('Y-m-d', strtotime('-1 week')) . "' ";					
				}
				$sql .= " AND p.quantity > 0 ";
				$sql .= " AND stock_status_id = '" . $default_stock_status . "'";
			}
			
			if ($this->config->get('config_option_products_enable') && $data['no_child']) {
				$sql .= " AND p.is_option_with_id = '0' ";
			}

			if (!empty($data['filter_also_bought_with'])) {
				$sql .= " AND p.product_id IN (SELECT also_bought_id FROM product_also_bought WHERE product_id = '" . (int)$data['filter_also_bought_with'] . "') ";
			}

			if (empty($data['filter_with_variants'])){
				$sql .= " AND ((p.main_variant_id = '0' OR ISNULL(p.main_variant_id)) OR p.display_in_catalog = 1)";
			}

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}

			if ($this->config->get('config_enable_amazon_specific_modes') && $this->config->get('config_rainforest_show_only_filled_products_in_catalog')){
				$sql .= " AND ((p.added_from_amazon = 0) OR (p.added_from_amazon = 1 AND p.filled_from_amazon = 1))";	
			}
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
				
				if (!empty($data['filter_filter'])) {
					$implode = [];
					
					$filters = explode(',', $data['filter_filter']);
					
					foreach ($filters as $filter_id) {
						$implode[] = (int)$filter_id;
					}
					
					$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
				}
			}				
			
			if (!empty($data['filter_category_id_intersect'])) {				
				if (is_array($filter_category_id_intersect = explode(':', $data['filter_category_id_intersect']))){
					$filter_category_id_intersect = array_map('intval', $filter_category_id_intersect);
				}
								
				if (!empty($filter_category_id_intersect)){
					if (!empty($data['filter_sub_category_intersect'])) {
						$sql .= " AND p.product_id IN (SELECT product_id FROM category_path cpi LEFT JOIN product_to_category p2ci ON (cpi.category_id = p2ci.category_id) WHERE cpi.path_id IN (" . implode(',', $filter_category_id_intersect) . "))";
						} else {
						$sql .= " AND p.product_id IN (SELECT product_id FROM product_to_category p2ci WHERE p2ci.category_id IN (" . implode(',', $filter_category_id_intersect) . ")";
					}
				}
			}
			
			if (!empty($data['filter_category_id']) && $this->config->get('config_special_category_id') && (int)$data['filter_category_id'] == (int)$this->config->get('config_special_category_id')) {
				$sql .= " AND p.product_id IN (SELECT product_id FROM product_special ps WHERE ps.price < p.price AND ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (store_id = '" . (int)$this->config->get('config_store_id') . "' OR store_id = -1)) AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' AND p.quantity > 0";
			}							
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
				
				if (!empty($data['filter_name'])) {
					$implode = [];
					
					$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
					
					if (!empty($data['filter_description'])) {
						$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.asin) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$art = preg_replace("([^0-9])", "", $data['filter_name']);
					$sql .= " OR LCASE(REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" . $this->db->escape(utf8_strtolower($art)) . "' AND LENGTH(p.model)>1)";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(((REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '')))) = '" . $this->db->escape(utf8_strtolower(str_replace(array(
					' ',
					'.',
					'/',
					'-'
					), '', $data['filter_name']))) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.product_id) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (!empty($data['filter_ocfilter'])) {
				$this->load->model('catalog/ocfilter');
				
				$ocfilter_product_sql = $this->model_catalog_ocfilter->getProductSQL($data['filter_ocfilter']);
				
    			if ($ocfilter_product_sql) {
					$sql .= $ocfilter_product_sql;
				}
			}
			
			if (!empty($data['exclude_manufacturer_id'])) {
				$sql .= " AND (p.manufacturer_id <> '" . (int)$data['exclude_manufacturer_id'] . "')";
			}
			
			if (!empty($data['filter_collection_id'])) {
				$sql .= " AND p.collection_id = '" . (int)$data['filter_collection_id'] . "'";
			}
			
			if (!empty($this->request->get['path'])){
				$path = '';				
				$parts = explode('_', (string)$this->request->get['path']);				
				$category_id = (int)array_pop($parts);
				$category_info = $this->model_catalog_category->getCategory($category_id);
				
				if ($category_info['deletenotinstock']){
					$data['filter_current_in_stock'] = 1;
				}
			}
			
			if (!empty($data['filter_current_in_stock'])) {
				$sql .= " AND p." . $this->config->get('config_warehouse_identifier') . " > 0";
			}
			
			if (!empty($data['filter_exclude_certs'])) {
				$sql .= " AND p.location <> 'certificate'";
			}
			
			if (!empty($data['filterinstock'])) {
				$sql .= " AND p." . $this->config->get('config_warehouse_identifier') . " > 0";
			}

			if (!empty($data['filter_quantity'])) {
				$sql .= " AND p.quantity > 0";
			}
			
			if (!empty($data['filter_not_bad'])) {
				$sql .= " AND p.stock_status_id NOT IN (" . $this->config->get('config_not_in_stock_status_id') . ',' . $this->config->get('config_partly_in_stock_status_id') . ")";
			}
			
			if (!empty($data['new'])) {
				if (!$this->config->get('config_ignore_manual_marker_productnews')){
					$sql .= " AND p.new = 1 ";
				}
				$sql .= " AND (DATE(p.new_date_to) > '". date('Y-m-d') . "' OR DATE(p.date_added) > '" . date('Y-m-d', strtotime('-' . $this->config->get('config_new_days') . ' day')) . "')";
			}
			
			if (!empty($data['newlong'])) {
				if (!$this->config->get('config_ignore_manual_marker_productnews')){
					$sql .= " AND p.new = 1 ";
				}
				$sql .= " AND p.date_added > '" . date('Y-m-d H:i:s', strtotime('-' . $this->config->get('config_newlong_days') . ' day')) . "'";
			}
			
			if (!empty($data['filter_enable_markdown'])) {
				$sql .= " AND p.is_markdown = 1 ";
				} else {
				$sql .= " AND p.is_markdown = 0 ";
			}


			if (!empty($data['sort']) && $data['sort'] == 'rand'){
				$sql .= " AND (" . mt_rand() . ")";
			}

			if (!empty($data['sort']) && $data['sort'] == 'rand-10'){
				$sql .= " AND (" . mt_rand(0, 10) . ")";
			}

			if (!empty($data['sort']) && $data['sort'] == 'rand-100'){
				$sql .= " AND (" . mt_rand(0, 100) . ")";
			}
			
			if (!empty($data['filter_different_categories'])){
				$sql .= " GROUP BY main_category_id";
			} else {
				$sql .= " GROUP BY p.product_id";
			}
			
			if (!empty($data['sort']) && in_array($data['sort'], $this->registry->get('sorts_available'))) {
				if ($data['sort'] == 'p.product_id'){
					$sql .= " ORDER BY p.product_id";
					$data['sort'] = null;					
				} elseif ($data['sort'] == 'rand' || $data['sort'] == 'rand-10' || $data['sort'] == 'rand-100'){
					$sql .= " ORDER BY stock_status_id ASC, RAND()";
					$data['sort'] = null;				
				} elseif ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY stock_status_id ASC, LCASE(" . $data['sort'] . ")";
					} elseif ($data['sort'] == 'p.price') {

					if ($this->config->get('config_warehouse_only')){
						$sql .= " ORDER BY (p.`quantity` > 0) DESC, (p.`" . $this->config->get('config_warehouse_identifier') . "` > 0) DESC, (CASE WHEN (special IS NOT NULL AND currency_scode <> '" . $this->db->escape($this->config->get('config_regional_currency')) . "') THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
					} else {
						$sql .= " ORDER BY (p.`quantity` > 0) DESC, (p.`" . $this->config->get('config_warehouse_identifier') . "` > 0) DESC, (CASE WHEN (special IS NOT NULL AND currency_scode <> '" . $this->db->escape($this->config->get('config_regional_currency')) . "') THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";						
					}

					} elseif ($data['sort'] == 'p.viewed' || $data['sort'] == 'p.date_added') {
						$sql .= " ORDER BY (p.`quantity` > 0) DESC, " . $data['sort'];
					} else {
					$sql .= " ORDER BY (p.`" . $this->config->get('config_warehouse_identifier') . "` > 0) DESC, stock_status_id ASC, " . $data['sort'];
				}
				} else {
				$sql .= " ORDER BY (p.`" . $this->config->get('config_warehouse_identifier') . "` > 0) DESC, stock_status_id ASC, p.sort_order";
			}

			if (!empty($data['sort']) && ($data['sort'] == 'p.viewed' || $data['sort'] == 'p.date_added')){
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql .= " DESC";
				} else {
					$sql .= " ASC";
				}

			} else {
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql .= " DESC";
				} else {
					$sql .= " ASC";
				}
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
			
			$product_data = [];
			
			if (in_array(__FUNCTION__,
			array('getProducts', 'getTotalProducts', 'getProductSpecials', 'getTotalProductSpecials'))) {
				if (!empty($this->request->get['mfp']) || (null != ($mfSettings = $this->config->get('mega_filter_settings')) && !empty($mfSettings['in_stock_default_selected']))) {
					if (!empty($this->request->get['mfp']) || $this->config->get('mfp_is_activated')) {
						$this->load->model('module/mega_filter');
						
						$sql = MegaFilterCore::newInstance($this, $sql)->getSQL(__FUNCTION__);
					}
				}
			}			

			$query = $this->db->query($sql);

			if (!isset($data['filter_get_product_mode'])) {
				$simple = false;
			} elseif ($data['filter_get_product_mode'] == 'simple'){
				$simple = 'simple';
			} elseif ($data['filter_get_product_mode'] == 'feed'){
				$simple = 'feed';
			}
			
			foreach ($query->rows as $result) {
				if ($data['return_parent'] && $result['is_option_for_product_id']) {	
					$product_data[$result['is_option_for_product_id']] = $this->getProduct($result['is_option_for_product_id'], true, $simple);		
					} else {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id'], true, $simple);
				}
			}			
			
			return $product_data;
		}
		
		public function getProductDeName($product_id){			
			$query = $this->db->query("SELECT name FROM product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->registry->get('languages_all')[$this->config->get('config_de_language')] . "' LIMIT 1");
			
			if (isset($query->row['name'])){
				$name = $query->row['name'];			
				} else {
				$name = '';
			}
			
			return $name;
		}
		
		public function getProductAdditionalOffer($product_id){
			
			$query = $this->db->query("SELECT ao.* FROM product_additional_offer ao LEFT JOIN product_additional_offer_to_store ao2s ON (ao.product_additional_offer_id = ao2s.product_additional_offer_id)
			WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()))
			AND (ISNULL(ao2s.store_id) OR ao2s.store_id = '" . $this->config->get('config_store_id') . "')
			ORDER BY priority ASC, price ASC");
			
			return $query->rows;
		}
		
		public function getProductAdditionalOfferById($product_additional_offer_id){
			
			$query = $this->db->query("SELECT * FROM product_additional_offer WHERE product_additional_offer_id = '" . (int)$product_additional_offer_id . "' AND customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) LIMIT 1");

			
			return $query->row;
		}

		public function getFeaturedReviews($data = []){

			$sql = "SELECT 
			r.product_id 
			FROM review r 
			JOIN product p ON r.product_id = p.product_id ";
			
			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) ";
			}
			

			$sql .= " WHERE r.status = '1' 
			AND p.status = '1'
			AND p.is_markdown = '0' 
			AND r.date_added >= '" . date('Y-m-d', strtotime('-1 month')) . "'
			AND p.date_available <= NOW() ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
			}

			if (!empty($data['manufacturer_id'])){
				$sql .= " AND p.manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
			}

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}

			if (!empty($data['filter_length'])){
				$sql .= " AND LENGTH(r.text) >= " . (int)$data['filter_length'];
			}

			if (!empty($data['category_id'])){
				$sql .= " AND (r.product_id IN (SELECT product_id FROM product_to_category WHERE category_id = '" . (int)$data['category_id'] . "') 
				OR r.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE parent_id = '" . (int)$data['category_id'] . "')))";
			}

			$sql .= " ORDER BY r.date_added DESC LIMIT " . (int)$data['limit'] . "";

			$query = $this->db->query($sql);

			$product_data = [];
			foreach ($query->rows as $row){
				$product_info = $this->model_catalog_product->getProduct($row['product_id']);

				if ($product_info) {					
					$product_data[$row['product_id']] = $product_info;
				}				
			}

			return $product_data;
		}
		
		public function getProductSpecials($data = []){
			
			if (!isset($data['no_child'])) {
				$data['no_child'] = false;
			}
			
			$sql = "SELECT DISTINCT ps.product_id, p.points_only_purchase, ps.price,
			(IF((p.quantity_stock + p.quantity_stockK + p.quantity_stockM + p.quantity_stockMN + p.quantity_stockAS) > 0, 
			IF(p." . $this->config->get('config_warehouse_identifier') . " > 0, " . $this->config->get('config_in_stock_status_id') . ", " . $this->config->get('config_stock_status_id') . "), p.stock_status_id)
			) as stock_status_id,
			(SELECT AVG(rating) FROM review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, 
			(SELECT points_special FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . date(MYSQL_NOW_DATE_FORMAT) . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . date(MYSQL_NOW_DATE_FORMAT) . "')) AND (store_id = '" . (int)$this->config->get('config_store_id') . "' OR store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS points_special
			FROM product_special ps 
			LEFT JOIN product p ON (ps.product_id = p.product_id) 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			LEFT JOIN product_to_category p2c ON (p2c.product_id = ps.product_id) 
			LEFT JOIN category_path cp ON (cp.category_id = p2c.category_id) 
			WHERE p.status = '1' 
			AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) 
			AND (p2s.store_id = '" . (int)$this->config->get('config_store_id') . "')
			AND (pd.language_id = '" . (int)$this->config->get('config_language_id') . "')";

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}
			
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (!empty($data['filter_not_bad'])) {
				$sql .= " AND p.stock_status_id NOT IN (" . $this->config->get('config_not_in_stock_status_id') . ',' . $this->config->get('config_partly_in_stock_status_id') . ")";
			}
			
			if ($this->config->get('config_option_products_enable') && $data['no_child']) {
				$sql .= " AND p.is_option_with_id = '0' ";
			}
			
			if (!empty($data['filter_current_in_stock'])) {
				$sql .= " AND p." . $this->config->get('config_warehouse_identifier') . " > 0";
			}
			
			if (!empty($data['filter_exclude_certs'])) {
				$sql .= " AND p.location <> 'certificate'";
			}
			
			
			if (!empty($data['filter_enable_markdown'])) {
				$sql .= " AND is_markdown = 1 ";
				} else {
				$sql .= " AND is_markdown = 0 ";
			}
			
			$sql .= " AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND p.date_available <= '" . date(MYSQL_NOW_DATE_FORMAT) . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . date(MYSQL_NOW_DATE_FORMAT) . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . date(MYSQL_NOW_DATE_FORMAT) . "')) GROUP BY ps.product_id";
			
			if (isset($data['sort']) && in_array($data['sort'], $this->registry->get('sorts_available'))) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY stock_status_id ASC, LCASE(" . $data['sort'] . ")";
					} elseif ($data['sort'] == 'p.price') {
					$sql .= " ORDER BY (p.quantity > 0) DESC, ps.price ";
					} else {
					$sql .= " ORDER BY stock_status_id ASC, " . $data['sort'];
				}
				} else {
				$sql .= " ORDER BY stock_status_id ASC, p.sort_order";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
				} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
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
			
			$product_data = [];
			
			if (in_array(__FUNCTION__,
			array('getProducts', 'getTotalProducts', 'getProductSpecials', 'getTotalProductSpecials'))) {
				if (!empty($this->request->get['mfp']) || (null != ($mfSettings = $this->config->get('mega_filter_settings')) && !empty($mfSettings['in_stock_default_selected']))) {
					if (!empty($this->request->get['mfp']) || $this->config->get('mfp_is_activated')) {
						$this->load->model('module/mega_filter');
						
						$sql = MegaFilterCore::newInstance($this, $sql)->getSQL(__FUNCTION__);
					}
				}
			}
			
			
			$query = $this->db->query($sql);
			
			if (empty($data['return_just_ids'])) {
				
				foreach ($query->rows as $result) {								
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);				
				}
				
				} else {
				
				foreach ($query->rows as $result) {
					$product_data[] = $result['product_id'];
				}
				
			}
			
			return $product_data;
		}		
		
		public function getLatestProducts($limit){
			
			$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$query = $this->db->query("SELECT p.product_id FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND is_markdown = 0 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . (int)$limit,
				$product_data);
			}
			
			return $product_data;
		}
		
		public function getPopularProducts($limit){
			$product_data = [];
			
			$query = $this->db->query("SELECT p.product_id FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND is_markdown = 0 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		public function getBestSellerProducts($limit){
			
			$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$product_data = [];
				
				$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM order_product op 
					LEFT JOIN `order` o ON (op.order_id = o.order_id) 
					LEFT JOIN `product` p ON (op.product_id = p.product_id) 
					LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
					WHERE o.order_status_id > '0' 
					AND p.status = '1' 
					AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'
					AND p.quantity > 0 
					AND p.is_markdown = 0 
					AND p.date_available <= NOW() 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
					GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . (int)$limit,
				$product_data);
			}
			
			return $product_data;
		}
		
		public function getBestSellerProductsForCollection($limit, $collection_id, $manufacturer_id = false, $return_ids = false){
			
			$product_data = [];
			
			$sql = "SELECT op.product_id, COUNT(*) AS total
			FROM order_product op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			LEFT JOIN `product` p ON (op.product_id = p.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 			
			WHERE 
			o.order_status_id > '0'";
			if ($manufacturer_id){
				$sql .= " AND p.manufacturer_id = " . (int)$manufacturer_id;
			}
			$sql .= " AND p.status = '1'
			AND p.quantity > 0 
			AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'  
			AND p.date_available <= NOW()
			AND is_markdown = 0 
			AND	p.collection_id = '" . (int)$collection_id . "' AND 
			p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit;
			
			$query = $this->db->query($sql);
			
			if ($return_ids){
				foreach ($query->rows as $result) {
					$product_data[] = $result['product_id'];
				}				
				} else {
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
			}
			
			return $product_data;
		}
		
		public function getBestSellerProductsForCategoryByTIME($limit, $category_id, $month = 3, $manufacturer_id = false, $return_ids = false){
			
			$product_data = [];
			
			$sql = "SELECT op.product_id, COUNT(*) AS total
			FROM order_product op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			LEFT JOIN `product` p ON (op.product_id = p.product_id) ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN `product_to_store` p2s ON (p.product_id = p2s.product_id) ";
			}

			$sql .= " LEFT JOIN `product_to_category` p2c ON (p2c.product_id = p.product_id)
			LEFT JOIN `category_path` cp ON (p2c.category_id = cp.category_id)
			WHERE 
			o.order_status_id > '0'";

			if ($manufacturer_id){
				$sql .= " AND p.manufacturer_id = " . (int)$manufacturer_id;
			}

			$sql .= " AND p.status = '1'
			AND DATE(o.date_added) >= '" . date('Y-m-d', strtotime("- $month month")) . "'
			AND p.quantity > 0
			AND p." . $this->config->get('config_warehouse_identifier') . " > 0
			AND is_markdown = 0
			AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
			AND p.date_available <= NOW() 
			AND cp.path_id = '" . (int)$category_id . "'";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			}

			$sql .= " GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit;
			
			$query = $this->db->query($sql);
			
			if ($return_ids){
				foreach ($query->rows as $result) {
					$product_data[] = $result['product_id'];
				}				
				} else {
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
			}
			
			return $product_data;
		}
				
		public function getBestSellerProductsForCategory($limit, $category_id, $manufacturer_id = false, $return_ids = false){
			
			$product_data = [];
			
			$sql = "SELECT op.product_id, COUNT(*) AS total
			FROM order_product op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			LEFT JOIN `product` p ON (op.product_id = p.product_id) ";
			

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN `product_to_store` p2s ON (p.product_id = p2s.product_id) ";
			}

			$sql .= " LEFT JOIN `product_to_category` p2c ON (p2c.product_id = p.product_id)
			LEFT JOIN `category_path` cp ON (p2c.category_id = cp.category_id)
			WHERE 
			o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'";
			
			if ($manufacturer_id){
				$sql .= " AND p.manufacturer_id = " . (int)$manufacturer_id;
			}

			$sql .= " AND p.status = '1'
			AND p.quantity > 0 
			AND p.is_markdown = 0
			AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
			AND p.date_available <= NOW()  
			AND cp.path_id = '" . (int)$category_id . "'";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			}

			$sql .= " GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit;
			
			$query = $this->db->query($sql);
			
			if ($return_ids){
				foreach ($query->rows as $result) {
					$product_data[] = $result['product_id'];
				}				
				} else {
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
			}
			
			return $product_data;
		}
		
		public function getBestSellerProductsForManufacturer($limit, $manufacturer_id){
			
			$product_data = [];
			
			$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total
			FROM order_product op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			LEFT JOIN `product` p ON (op.product_id = p.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 		
			WHERE 
			o.order_status_id > '0' AND 
			p.status = '1'
			AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'
			AND p.quantity > 0 
			AND is_markdown = 0
			AND p.date_available <= NOW() AND 
			p.manufacturer_id = " . (int)$manufacturer_id . "  AND
			p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}		
		
		public function getPrevNextProduct($product_id, $category_id, $collection_id){
			
			$pnproduct_data = $this->cache->get('product.pn.' . $product_id . '.' . $category_id . '.' . $collection_id . '.' . (int)$this->config->get('config_store_id'));
			
			if (!$pnproduct_data) {
				
				if ($collection_id) {
					$sql = "SELECT p.product_id FROM product p ";
					if (!$this->config->get('config_single_store_enable')){
						$sql .= " LEFT JOIN product_to_store p2s ON (p2s.product_id = p.product_id)";
					}

					$sql .= " WHERE p.status = '1' AND p.date_available <= '" . date(MYSQL_NOW_DATE_FORMAT) . "'";

					if (!$this->config->get('config_single_store_enable')){
						$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
					}

					$sql .= " AND collection_id = '" . (int)$collection_id . " '";
					$sql .= " ORDER BY product_id ASC";

					} else {

					$sql = "SELECT p2c.product_id FROM category_path cp
					LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id) ";

					if (!$this->config->get('config_single_store_enable')){
						$sql .= " LEFT JOIN product_to_store p2s ON (p2s.product_id = p2c.product_id)";
					}

					$sql .= " LEFT JOIN product p ON (p.product_id = p2c.product_id) ";
					$sql .= " WHERE p.status = '1' AND p.date_available <= '" . date(MYSQL_NOW_DATE_FORMAT) . "' ";

					if (!$this->config->get('config_single_store_enable')){
						$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
					}
					
					$sql .= " AND cp.path_id = '" . (int)$category_id . " '";
					$sql .= " ORDER BY p2c.product_id ASC";
				}

				$query = $this->db->query($sql);
				
				$q = [];
				foreach ($query->rows as $row) {
					$q[] = $row['product_id'];
				}
				
				$next = $prev = 0;
				
				if (count($q) > 1) {
					$count = count($q);
					$key = array_search($product_id, $q);
					
					if ($key == $count - 1) {
						$next = $q[0];
						$prev = $q[$key - 1];
						} elseif ($key == 0) {
						$next = $q[1];
						$prev = $q[$count - 1];
						} else {
						$next = $q[$key + 1];
						$prev = $q[$key - 1];
					}
					} elseif (count($q) == 1) {
					$key = array_search($product_id, $q);
					$prev = $q[$key];
					$next = $q[$key];
				}
				
				$pnproduct_data = array(
				'next' => $this->getProduct($next),
				'prev' => $this->getProduct($prev)
				);
				
				$this->cache->set('product.pn.' . $product_id . '.' . $category_id . '.' . $collection_id . '.' . (int)$this->config->get('config_store_id'),
				$pnproduct_data);
				
			}
			
			return $pnproduct_data;
		}
		
		public function getOnlyProductPath($product_id){
			$sql = "SELECT p2c.category_id as category_id FROM product_to_category p2c LEFT JOIN product_to_store p2s ON (p2s.product_id = " . (int)$product_id . ")";
			$sql .= " WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$sql .= " AND p2c.product_id = '" . (int)$product_id . "'";
			$sql .= " GROUP BY p2c.category_id";
			$sql .= " LIMIT 1";
			$query = $this->db->query($sql);
			if (isset($query->row['category_id'])) {
				return $query->row['category_id'];
				} else {
				return 1;
			}
		}

		public function getProductAttributesFlat($product_id){
			if (!$product_attribute_flat_data = $this->cache->get($this->registry->createCacheQueryStringData(__METHOD__, [$product_id]))){

				$sql = '';
				$sql .= "SELECT ad.name as 'attribute_name', agd.name as 'attribute_group', pa.text as 'attribute_value' ";
				$sql .= "FROM product_attribute pa  ";
				$sql .= "LEFT JOIN attribute a ON (a.attribute_id = pa.attribute_id)  ";
				$sql .= "LEFT JOIN attribute_description ad ON (ad.attribute_id = a.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "') ";
				$sql .= "LEFT JOIN attribute_group_description agd ON (agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "')  ";
				$sql .= "WHERE pa.product_id = '" . (int)$product_id . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
				$product_attribute_flat_data = $this->db->query($sql)->rows;

				$this->cache->set($this->registry->createCacheQueryStringData(__METHOD__, [$product_id]), $product_attribute_flat_data);
			}
			
			return $product_attribute_flat_data;
		}
		
		public function getProductAttributes($product_id, $filter_data = []){
			if (!$product_attribute_group_data = $this->cache->get($this->registry->createCacheQueryStringData(__METHOD__, [$product_id], $filter_data))){

				$product_attribute_group_data = [];

				$sql = "SELECT ag.attribute_group_id, agd.name FROM product_attribute pa LEFT JOIN attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id)";

				$sql .= " WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

				if (!empty($filter_data['exclude_groups'])){
					$sql .= " AND ag.attribute_group_id NOT IN (" . implode(',', $filter_data['exclude_groups']) . ")";
				}

				$sql .= " GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name";

				$product_attribute_group_query = $this->db->query($sql);

				foreach ($product_attribute_group_query->rows as $product_attribute_group) {
					$product_attribute_data = [];

					$sql = "SELECT a.attribute_id, a.attribute_group_id, a.dimension_type, a.sort_order, ad.name, pa.text FROM product_attribute pa LEFT JOIN attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id) ";

					$sql .= " WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

					if (isset($filter_data['exclude_sort_order'])){
						$sql .= " AND a.sort_order <> '" . (int)$filter_data['exclude_sort_order'] . "'";
					}

					if (!empty($filter_data['exclude_dimension_types'])){
						foreach ($filter_data['exclude_dimension_types'] as $exclude_dimension_type){
							$sql .= " AND (ISNULL(a.dimension_type) OR a.dimension_type <> '" . $this->db->escape($exclude_dimension_type) . "')";
						}						
					}

					$sql .= " ORDER BY a.sort_order, ad.name";

					$product_attribute_query = $this->db->query($sql);

					foreach ($product_attribute_query->rows as $product_attribute) {
						$product_attribute_data[] = array(
							'attribute_id' 			=> $product_attribute['attribute_id'],
							'attribute_group_id' 	=> $product_attribute['attribute_group_id'],
							'dimension_type' 		=> $product_attribute['dimension_type'],
							'name'         			=> $product_attribute['name'],
							'text'         			=> $product_attribute['text'],
							'sort_order'   			=> $product_attribute['sort_order']
						);
					}

					$product_attribute_group_data[] = array(
						'attribute_group_id' => $product_attribute_group['attribute_group_id'],
						'name'               => $product_attribute_group['name'],
						'attribute'          => $product_attribute_data
					);
				}

				$this->cache->set($this->registry->createCacheQueryStringData(__METHOD__, [$product_id], $filter_data), $product_attribute_group_data);

			}
			
			return $product_attribute_group_data;
		}		
		
		public function getIfOptionIsProduct($option_id, $product_option_value_id){
			
			$check_value_query = $this->db->query("SELECT this_is_product_id FROM product_option_value WHERE product_option_id = '" . (int)$option_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "'");
			
			if (!$check_value_query->row || !isset($check_value_query->row['this_is_product_id'])) {
				return false;
				} else {
				$product_id = $check_value_query->row['this_is_product_id'];
				
				return $this->getProduct($product_id);
			}			
		}
		
		public function getProductOptions($product_id){
			$product_option_data = [];
			
			$product_option_query = $this->db->query("SELECT * FROM product_option po LEFT JOIN `option` o ON (po.option_id = o.option_id) LEFT JOIN option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
			
			foreach ($product_option_query->rows as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'block' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$product_option_value_data = [];
					
					$product_option_value_query = $this->db->query("SELECT * FROM product_option_value pov LEFT JOIN option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
					
					foreach ($product_option_value_query->rows as $product_option_value) {
						$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix'],
						'ob_sku'                  => $product_option_value['ob_sku'],
						'ob_info'                 => $product_option_value['ob_info'],
						'ob_image'                => $product_option_value['ob_image'],
						'ob_sku_override'         => $product_option_value['ob_sku_override'],
						'this_is_product_id'      => $product_option_value['this_is_product_id']
						);
					}
					
					$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option_value_data,
					'required'          => $product_option['required']
					);
					} else {
					$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
					);
				}
			}
			
			return $product_option_data;
		}
		
		public function getProductProductOptions($product_id){
			$product_option_data = [];
			
			$product_option_query = $this->db->query("SELECT * FROM product_product_option ppo
			LEFT JOIN category_description cd
			ON (ppo.category_id = cd.category_id)
			WHERE ppo.product_id = '" . (int)$product_id . "'
			AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY ppo.sort_order");
			
			
			foreach ($product_option_query->rows as $product_option) {
				
				$product_option_value_data = [];
				
				$product_option_value_query = $this->db->query("SELECT * FROM product_product_option_value ppov
				LEFT JOIN product p ON (ppov.product_option_id = p.product_id)
				LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
				WHERE ppov.product_product_option_id = '" . (int)$product_option['product_product_option_id'] . "'
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
				ORDER BY ppov.sort_order");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
					'product_option_id' => $product_option_value['product_option_id'],
					'name'              => $product_option_value['name'],
					'image'             => $product_option_value['image'],
					'price'             => $product_option_value['price']
					);
				}
				
				$product_option_data[] = array(
				'product_product_option_id' => $product_option['product_product_option_id'],
				'name'                      => $product_option['name'],
				'type'                      => $product_option['type'],
				'required'                  => $product_option['required'],
				'product_option'            => $product_option_value_data
				);
			}
			
			return $product_option_data;
		}
				
		public function getProductImageTitleAlt($product_id){
			$data = [];
			$query =$this->db->query("SELECT alt_image,title_image FROM product_description WHERE product_id = '". (int)$product_id ."'  ");
			//return $query->rows;
			
			foreach ($query->rows as $result) {
				$data['alt'] =$result['alt_image'];
				$data['title'] = $result['title_image'];
				
			}
			return $data;
		}
		
		public function getProductDiscounts($product_id){
			
			$query = $this->db->query("SELECT * FROM product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");
			
			return $query->rows;
		}
		
		public function getProductImages($product_id){
			$query = $this->db->query("SELECT * FROM product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
			
			return $query->rows;
		}

		public function getProductVideos($product_id){
			$query = $this->db->query("SELECT pv.*, pd.title FROM product_video pv LEFT JOIN product_video_description pd ON (pv.product_video_id = pd.product_video_id) WHERE pv.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . $this->config->get('config_language_id') . "' ORDER BY sort_order ASC");
			
			return $query->rows;
		}

		public function getProductVariants($product_id, $exclude_product_id = false){
			$product_data = [];
			
			$sql = "SELECT p.product_id FROM product p 		
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE p.status = '1' 
			AND p.is_markdown = '0'			
			AND p.main_variant_id = '" . (int)$product_id . "'";

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}

			$sql .= " AND p.date_available <= NOW()
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'									
			AND (pd.language_id = '" . (int)$this->config->get('config_language_id') . "')";


			$query = $this->db->query($sql);

			$product_data[$product_id] = $this->getProduct($product_id);
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			if ($exclude_product_id && !empty($product_data[$exclude_product_id])){
				unset($product_data[$exclude_product_id]);
			}
			
			return $product_data;
		}		
		
		public function getProductRelated($product_id){
			$product_data = [];
			
			$sql = "SELECT * FROM product_related pr 
				LEFT JOIN product p ON (pr.related_id = p.product_id) 
				LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
				WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND quantity > 0 AND is_markdown = 0 
				AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
				AND p.date_available <= NOW() 
				AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}

			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
			}
			
			return $product_data;
		}

		public function getProductSimilar($product_id){
			$product_data = [];
			
			$sql = "SELECT * FROM product_similar ps 
				LEFT JOIN product p ON (ps.similar_id = p.product_id) 
				LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
				WHERE ps.product_id = '" . (int)$product_id . "' 
				AND p.status = '1' 
				AND quantity > 0 
				AND is_markdown = 0 
				AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
				AND p.date_available <= NOW() 
				AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}

			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[$result['similar_id']] = $this->getProduct($result['similar_id']);
			}
			
			return $product_data;
		}

		public function getProductSponsored($product_id){
			$product_data = [];
			
			$sql = "SELECT * FROM product_sponsored ps 
				LEFT JOIN product p ON (ps.sponsored_id = p.product_id) 
				LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
				WHERE ps.product_id = '" . (int)$product_id . "' 
				AND p.status = '1' 
				AND quantity > 0 
				AND is_markdown = 0 
				AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
				AND p.date_available <= NOW() 
				AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}

			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[$result['sponsored_id']] = $this->getProduct($result['sponsored_id']);
			}
			
			return $product_data;
		}
		
		public function getSimilarProductsByName($product_name, $product_id, $limit, $in_stock = false, $recursive_results = array()){
			$product_data = [];					
			
			$sql = "SELECT DISTINCT pd.product_id FROM product_description pd 
			LEFT JOIN product p ON (pd.product_id = p.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' 
			AND TRIM(LCASE(pd.name)) LIKE ('" . $this->db->escape(trim(mb_strtolower($product_name))) . "%')
			AND pd.product_id <> '" . (int)$product_id . "'			
			AND p.status = '1'
			AND p.is_markdown = 0
			AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			if ($recursive_results){
				$_pids = [];
				foreach($recursive_results as $__rritem){
					$_pids[] = (int)$__rritem['product_id'];
				}	
				
				if ($_pids && count($_pids) > 0){
					$sql .= " AND pd.product_id NOT IN (" . implode(',', $_pids) . ")";
				}
			}
			
			//проверяем категории товара
			$_cssql = $this->db->query("SELECT DISTINCT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			if ($_cssql->num_rows){
				$sql .= " AND pd.product_id IN (SELECT DISTINCT product_id FROM product_to_category WHERE category_id IN (SELECT DISTINCT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "'))";
			}
			
			if ($in_stock){
				
				$_cqwuery = $this->db->query("SHOW COLUMNS FROM product LIKE '" . $this->db->escape($this->config->get('config_warehouse_identifier')) . "'");
				
				if ($_cqwuery->num_rows){
					$_qfield = 'p.`' . $this->config->get('config_warehouse_identifier').'`';
					} else {
					$_qfield = 'p.quantity';
				}
				
				$sql .= " AND " . $_qfield . " > 0 AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'";
			}
			
			$sql .= " ORDER BY " . $_qfield . " DESC, p.image DESC LIMIT " . (int)$limit . "";
			
			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		public function guessSameProducts($product_name, $product_id, $limit, $in_stock = false){							
			$exploded = explode(' ', $product_name);
			
			$results = [];
			
			//Попытка получить по четырем словам
			if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2]) && isset($exploded[3])){
				$results = $this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2] . ' ' . $exploded[3], $product_id, $limit, $in_stock);					
			}
			
			//Попытка получить по трем словам
			if (count($results) < $limit){
				if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2])){	
					$results = array_merge($this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2], $product_id, ($limit - count($results)),  $in_stock, $results), $results);						
				}
			}			
			
			//Попытка получить по двум словам
			if (count($results) < $limit){				
				if (isset($exploded[0]) && isset($exploded[1])){
					$results = array_merge($this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1], $product_id, ($limit - count($results)),  $in_stock, $results), $results);	
				}
			}
			
			//Попытка получить по одному слову
			if (count($results) < $limit){			
				if (isset($exploded[0])){
					$results = array_merge($this->getSimilarProductsByName($exploded[0], $product_id, ($limit - count($results)), $in_stock, $results), $results);	
				}				
			}
			
			return $results;
		}
		
		public function getProductChild($product_id){
			$product_data = [];
			
			$query = $this->db->query("SELECT * FROM product_child pr LEFT JOIN product p ON (pr.child_id = p.product_id) LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			foreach ($query->rows as $result) {
				$product_data[$result['child_id']] = $this->getProduct($result['child_id']);
			}
			
			return $product_data;
		}
						
		public function getProductColourGroupRelated($color_group, $product_id){
			$product_data = [];
			
			$query = $this->db->query("SELECT DISTINCT p.product_id FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE color_group = '" . $this->db->escape($color_group) . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		public function getColorGroupedProducts($product_id, $color_group){
			$query = $this->db->query("SELECT p.product_id FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) where color_group LIKE '" . $this->db->escape($color_group) . "' AND p.status = '1' AND p.date_available <= NOW() AND p.product_id <> '" . (int)$product_id . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			return $query->rows;
		}
		
		public function getProductLayoutId($product_id){
			$query = $this->db->query("SELECT * FROM product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if ($query->num_rows) {
				return $query->row['layout_id'];
				} else {
				return false;
			}
		}
		
		public function getCategories($product_id){
			$query = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			return $query->rows;
		}
		
		public function getOneCategory($product_id){
			$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			
			if ($query->num_rows && isset($query->row['category_id'])){
				return $query->row['category_id'];
			}
		}
		
		public function getTotalProducts($data = []){
			
			if (!isset($data['no_child'])) {
				$data['no_child'] = false;
			}			
			
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM category_path cp LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id)";
					} else {
					$sql .= " FROM product_to_category p2c";
				}
				
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN product p ON (pf.product_id = p.product_id)";
					} else {
					$sql .= " LEFT JOIN product p ON (p2c.product_id = p.product_id)";
				}
				} else {
				$sql .= " FROM product p";
			}
			
			$sql .= " LEFT JOIN product_description pd ON (p.product_id = pd.product_id) ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) ";
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			}
			
			if ($this->config->get('config_option_products_enable') && $data['no_child']) {
				$sql .= " AND p.is_option_with_id = '0' ";
			}

			if (empty($data['filter_with_variants'])){
				$sql .= " AND ((p.main_variant_id = '0' OR ISNULL(p.main_variant_id)) OR p.display_in_catalog = 1)";	
			}

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}		

			if ($this->config->get('config_enable_amazon_specific_modes') && $this->config->get('config_rainforest_show_only_filled_products_in_catalog')){
				$sql .= " AND ((p.added_from_amazon = 0) OR (p.added_from_amazon = 1 AND p.filled_from_amazon = 1))";	
			}
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
				
				if (!empty($data['filter_filter'])) {
					$implode = [];
					
					$filters = explode(',', $data['filter_filter']);
					
					foreach ($filters as $filter_id) {
						$implode[] = (int)$filter_id;
					}
					
					$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
				}
			}
			
			if (!empty($data['filter_category_id']) && $this->config->get('config_special_category_id') && (int)$data['filter_category_id'] == (int)$this->config->get('config_special_category_id')) {
				$sql .= " AND p.product_id IN (SELECT product_id FROM product_special ps WHERE ps.price < p.price AND ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (store_id = '" . (int)$this->config->get('config_store_id') . "' OR store_id = -1)) AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' AND p.quantity > 0";
			}			
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
				
				if (!empty($data['filter_name'])) {
					$implode = [];
					
					$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
					
					if (!empty($data['filter_description'])) {
						$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.asin) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(((REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '')))) = '" . $this->db->escape(utf8_strtolower(str_replace(array(
					' ',
					'.',
					'/',
					'-'
					), '', $data['filter_name']))) . "'";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_category_id_intersect'])) {				
				if (is_array($filter_category_id_intersect = explode(':', $data['filter_category_id_intersect']))){
					$filter_category_id_intersect = array_map('intval', $filter_category_id_intersect);
				}
				
				
				if (!empty($filter_category_id_intersect)){
					if (!empty($data['filter_sub_category_intersect'])) {
						$sql .= " AND p.product_id IN (SELECT product_id FROM category_path cpi LEFT JOIN product_to_category p2ci ON (cpi.category_id = p2ci.category_id) WHERE cpi.path_id IN (" . implode(',', $filter_category_id_intersect) . "))";
						} else {
						$sql .= " AND p.product_id IN (SELECT product_id FROM product_to_category p2ci WHERE p2ci.category_id IN (" . implode(',', $filter_category_id_intersect) . ")";
					}
				}
			}
			
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (!empty($data['filter_ocfilter'])) {
				$this->load->model('catalog/ocfilter');
				
				$ocfilter_product_sql = $this->model_catalog_ocfilter->getProductSQL($data['filter_ocfilter']);
				
    			if ($ocfilter_product_sql) {
					$sql .= $ocfilter_product_sql;
				}
			}
			
			if (!empty($data['filter_collection_id'])) {
				$sql .= " AND p.collection_id = '" . (int)$data['filter_collection_id'] . "'";
			}
			
			if (!empty($data['new'])) {
				if (!$this->config->get('config_ignore_manual_marker_productnews')){
					$sql .= " AND p.new = 1 ";
				}
				$sql .= " AND (DATE(p.new_date_to) > '". date('Y-m-d') . "' OR DATE(p.date_added) > '" . date('Y-m-d', strtotime('-' . $this->config->get('config_new_days') . ' day')) . "')";
			}
			
			if (!empty($data['newlong'])) {
				if (!$this->config->get('config_ignore_manual_marker_productnews')){
					$sql .= " AND p.new = 1 ";
				}
				$sql .= " AND p.date_added > '" . date('Y-m-d H:i:s', strtotime('-' . $this->config->get('config_newlong_days') . ' day')) . "'";
			}
			
			if (!empty($data['filter_current_in_stock'])) {
				$sql .= " AND p." . $this->config->get('config_warehouse_identifier') . " > 0";
			}
			
			if (!empty($data['filterinstock'])) {
				$sql .= " AND p." . $this->config->get('config_warehouse_identifier') . " > 0";
			}

			if (!empty($data['filter_quantity'])) {
				$sql .= " AND p.quantity > 0";
			}
			
			if (!empty($data['filter_enable_markdown'])) {
				$sql .= " AND p.is_markdown = 1 ";
				} else {
				$sql .= " AND p.is_markdown = 0 ";
			}
			
			if (!empty($data['filter_not_bad'])) {
				$sql .= " AND p.stock_status_id NOT IN (" . $this->config->get('config_not_in_stock_status_id') . ',' . $this->config->get('config_partly_in_stock_status_id') . ")";
			}
			
			if (in_array(__FUNCTION__,
			array('getProducts', 'getTotalProducts', 'getProductSpecials', 'getTotalProductSpecials'))) {
				if (!empty($this->request->get['mfp']) || (null != ($mfSettings = $this->config->get('mega_filter_settings')) && !empty($mfSettings['in_stock_default_selected']))) {
					if (!empty($this->request->get['mfp']) || $this->config->get('mfp_is_activated')) {
						$this->load->model('module/mega_filter');
						
						$sql = MegaFilterCore::newInstance($this, $sql)->getSQL(__FUNCTION__);
					}
				}
			}	
			
			$query = $this->db->query($sql);
			
			return (int)$query->row['total'];
		}
		
		public function getProfiles($product_id){
			return $this->db->query("SELECT `pd`.* FROM `product_profile` `pp` JOIN `profile_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`profile_id` = `pp`.`profile_id` JOIN `profile` `p` ON `p`.`profile_id` = `pd`.`profile_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->registry->get('customer_group_id') . " ORDER BY `sort_order` ASC")->rows;		
		}
		
		public function getProfile($product_id, $profile_id){
			
			return $this->db->query("SELECT * FROM `profile` `p` JOIN `product_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->registry->get('customer_group_id'))->row;
		}
		
		protected function getPath($parent_id, $current_path = '') {
			$this->load->model('catalog/category');	
			$category_info = $this->model_catalog_category->getCategory($parent_id);
			
			if ($category_info) {
				if (!$current_path) {
					$new_path = $category_info['category_id'];
					} else {
					$new_path = $category_info['category_id'] . '_' . $current_path;
				}
				
				$path = $this->getPath($category_info['parent_id'], $new_path);
				
				if ($path) {
					return $path;
					} else {
					return $new_path;
				}
			}
		}
		
		public function getGoogleCategoryPath($product_id){
			if (!$string = $this->cache->get($this->registry->createCacheQueryStringData(__METHOD__, [$product_id]))){
				
				$category = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1")->row;
				
				$path = $string = '';
				if (!empty($category['category_id'])){				
					$path = $this->getPath($category['category_id']);
				}
				
				if ($path) {
					$string = '';
					
					foreach (explode('_', $path) as $path_id) {
						$category_info = $this->model_catalog_category->getCategory($path_id);
						
						if ($category_info) {
							if (!$string) {
								$string = $category_info['name'];
								} else {
								$string .= '/' . $category_info['name'];
							}
						}
					}
				}
				
				$this->cache->set($this->registry->createCacheQueryStringData(__METHOD__, [$product_id]), $string);
			}
			
			return $string;
		}
		
		public function getProductMarkdowns($product_id) {
			$product_markdown_data = [];

			$sql = "SELECT * FROM product p ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)";
			}

			$sql .= " WHERE p.markdown_product_id = '" . (int)$product_id . "' 
			AND p.status = 1 ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			}
			
			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_markdown_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_markdown_data;
		}

		public function getProductMainCategoryId($product_id) {						
			$query = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1");
			
			if ($query->num_rows){
				return $query->row['category_id'];
			}

			return 0;
		}
		
		public function getProductCategories($product_id) {
			$product_category_data = [];
			
			$query = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_category_data[] = $result['category_id'];
			}
			
			return $product_category_data;
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
		
		public function getSimilarProductsByAttributes($product_id, $category_id, $language_id, $store_id, $attributes = array(), $limit = 10, $stock = false){
			//full query. Точные совпадения ВСЕХ атрибутов!
			$sql = "SELECT DISTINCT pa.product_id FROM product_attribute pa";
			$sql .= " LEFT JOIN product_to_category p2c ON (pa.product_id = p2c.product_id)";			
			$sql .= " LEFT JOIN product p ON (pa.product_id = p.product_id)";
			$sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) ";
			$sql .= " WHERE pa.product_id <> '". (int)$product_id ."' ";
			foreach ($attributes as $id => $text){
				$sql .= " AND pa.product_id IN (SELECT product_id FROM product_attribute WHERE attribute_id = '" . (int)$id . "' AND text LIKE('%" . $this->db->escape(trim($text)) . "%'))";
			}	
			$sql .= " AND p2c.category_id = '" . (int)$category_id . "' 
			AND p.status = '1'
			AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$store_id . "'
			AND language_id = '". (int)$language_id ."' 
			AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') ."'";
			
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
		
		public function getAjaxcartProducts($data = []){
			$type_pr = (int)$this->config->get('config_type_ap');
			
			if (!isset($data['limit'])) {
				$data['limit'] = 5;
			}
			
			if ($type_pr == 1) {
				
				$product_data = [];
				
				$query = $this->db->query("SELECT * FROM product_to_category WHERE category_id = '" . (int)$this->config->get('config_parent_id') . "'");
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				return $product_data;
				
				} elseif ($type_pr == 2) {
				
				$product_data = [];
				
				$query = $this->db->query("SELECT p.product_id FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT 50");
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				return $product_data;
				
				} elseif ($type_pr == 3) {
				
				$sql = "SELECT DISTINCT ps.product_id,
				(SELECT AVG(rating) FROM review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM product_special ps 
				LEFT JOIN product p ON (ps.product_id = p.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) GROUP BY ps.product_id";
				
				$sort_data = array(
				'pd.name',
				'p.model',
				'ps.price',
				'rating',
				'p.sort_order'
				);
				
				if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
					if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
						$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
						} else {
						$sql .= " ORDER BY " . $data['sort'];
					}
					} else {
					$sql .= " ORDER BY p.sort_order";
				}
				
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql .= " DESC, LCASE(pd.name) DESC";
					} else {
					$sql .= " ASC, LCASE(pd.name) ASC";
				}
				
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}
					
					//if ($data['limit'] < 1) {
					$data['limit'] = 20;
					//}
					
					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}
				
				$product_data = [];
				
				$query = $this->db->query($sql);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				return $product_data;
				
				} elseif ($type_pr == 4) {

				$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . '50');
				
				if (!$product_data) {
					$product_data = [];
					
					$query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM order_product op LEFT JOIN `order` o ON (op.order_id = o.order_id) LEFT JOIN `product` p ON (op.product_id = p.product_id) LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT 50");
					
					foreach ($query->rows as $result) {
						$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
					}
					
					$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . '50',
					$product_data);
				}
				
				return $product_data;
				
				} elseif ($type_pr == 5) {
				
				$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . '50');
				
				if (!$product_data) {
					$query = $this->db->query("SELECT p.product_id FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT 50");
					
					foreach ($query->rows as $result) {
						$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
					}
					
					$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . '50',
					$product_data);
				}
				
				return $product_data;
				} elseif ($type_pr == 6) {
				
				$product_data = $this->cache->get('product.lastbought.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . $data['limit']);
				
				if (!$product_data) {
					$query = $this->db->query("SELECT DISTINCT op.product_id FROM order_product op
					LEFT JOIN product p ON p.product_id = op.product_id
					LEFT JOIN `order` o ON (op.order_id = o.order_id)
					LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
					WHERE p.status = '1'
					AND o.order_status_id > 0
					AND p.date_available <= NOW() 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
					ORDER BY op.order_product_id DESC LIMIT " . $data['limit'] . "");
					
					foreach ($query->rows as $result) {
						$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
					}
					
					$this->cache->set('product.lastbought.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->registry->get('customer_group_id') . '.' . $data['limit'],
					$product_data);
				}
				
				return $product_data;
			}
		}

		public function fillSpecialCategory(){
			if ($this->config->get('config_special_controller_logic') && $this->config->get('config_special_category_id')){
				$this->db->query("DELETE FROM product_to_category WHERE category_id = '" . (int)$this->config->get('config_special_category_id') . "'");
				$this->db->query("INSERT IGNORE INTO product_to_category (product_id, category_id) SELECT DISTINCT ps.product_id, '" . $this->config->get('config_special_category_id') . "' FROM product_special ps LEFT JOIN product p ON ps.product_id = p.product_id WHERE p.status = 1 AND p.quantity > 0 AND ps.price < p.price AND ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");
			}
		}
		
		public function getTotalProductSpecials($data = []){
			
			if (!isset($data['no_child'])) {
				$data['no_child'] = false;
			}
			
			$sql = "SELECT COUNT(DISTINCT ps.product_id) AS total FROM product_special ps 
			LEFT JOIN product p ON (ps.product_id = p.product_id) 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE p.status = '1' 
			AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND ps.customer_group_id = '" . (int)$this->registry->get('customer_group_id') . "' 
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) 
			AND (ps.store_id = '" . (int)$this->config->get('config_store_id') . "' OR ps.store_id = -1) 
			AND (p2s.store_id = '" . (int)$this->config->get('config_store_id') . "')
			AND (pd.language_id = '" . (int)$this->config->get('config_language_id') . "')";

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}
			
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (!empty($data['filter_not_bad'])) {
				$sql .= " AND p.stock_status_id NOT IN (" . (int)$this->config->get('config_not_in_stock_status_id') . ',' . (int)$this->config->get('config_partly_in_stock_status_id') . ")";
			}
			
			if ($this->config->get('config_option_products_enable') && $data['no_child']) {
				$sql .= " AND p.is_option_with_id = '0' ";
			}
			
			if (!empty($data['filter_current_in_stock'])) {
				$sql .= " AND p." . $this->config->get('config_warehouse_identifier') . " > 0";
			}
			
			if (!empty($data['filterinstock'])) {
				$sql .= " AND p." . $this->config->get('config_warehouse_identifier') . " > 0";
			}
			
			if (!empty($data['filter_enable_markdown'])) {
				$sql .= " AND p.is_markdown = 1 ";
				} else {
				$sql .= " AND p.is_markdown = 0 ";
			}						
			
			$sql .= "ORDER BY ps.store_id DESC, ps.priority ASC";
			
			if (in_array(__FUNCTION__,
			array('getProducts', 'getTotalProducts', 'getProductSpecials', 'getTotalProductSpecials'))) {
				if (!empty($this->request->get['mfp']) || (null != ($mfSettings = $this->config->get('mega_filter_settings')) && !empty($mfSettings['in_stock_default_selected']))) {
					if (!empty($this->request->get['mfp']) || $this->config->get('mfp_is_activated')) {
						$this->load->model('module/mega_filter');
						
						$sql = MegaFilterCore::newInstance($this, $sql)->getSQL(__FUNCTION__);
					}
				}
			}
			
			$query = $this->db->query($sql);						
			
			if (isset($query->row['total'])) {
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getProductColorsByGroup($product_id, $color_group = ''){

			if (!$this->config->get('config_color_grouping_products_enable')){
				return [];
			}
			
			if (!$color_group) {
				return [];
			}
			
			$this->load->model('tool/image');
			
			$colors = [];
			
			$results = $this->getColorGroupedProducts($product_id, $color_group);
			
			foreach ($results as $result) {
				if ($product = $this->getProduct($result['product_id'])) {
					
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], 39, 39);
						} else {
						$image = $this->model_tool_image->resize('no_image.jpg', 39, 39);
					}
					
					$colors[] = array(
					'product_id' => $product['product_id'],
					'image'      => $image,
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'name'       => $product['name']
					);
				}
			}
			
			return $colors;			
		}
			
		public function getProductOptionsForCatalog($product_id){

			if (!$this->config->get('config_option_products_enable')){
				return false;
			}
			
			$all_options = $this->getProductOptions($product_id);
			
			$options = [];
			
			foreach ($all_options as $option) {
				if ($option['type'] == 'select' || $option['type'] == 'block' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_value_data = [];
					
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							
							$option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'this_is_product_id'      => $option_value['this_is_product_id'],
							'href'                    => $option_value['this_is_product_id'] ? $this->url->link('product/product',
							'product_id=' . $product_id . '&oid=' . $option_value['this_is_product_id']) : false,
							);
							
						}
					}
					
					
					$options[] = array(
					'product_option_id' => $option['product_option_id'],
					'option_id'         => $option['option_id'],
					'name'              => $option['name'],
					'type'              => $option['type'],
					'option_value'      => $option_value_data,
					'required'          => $option['required']
					);
				}
				
				
			}
			
			return $options;
		}

		public function getTotalVariants($product_id){

			$sql = "SELECT COUNT(product_id) as total FROM product p WHERE main_variant_id = '" . (int)$product_id . "'";

			if ($this->config->get('config_no_zeroprice')){
				$sql .= " AND (p.price > 0 OR p.price_national > 0)";
			} else {
				$sql .= " AND (";
				$sql .= " p.price > 0 OR p.price_national > 0";
				$sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
				$sql .= ")";
			}

			return $this->db->query($sql)->row['total'];
		}
	
		public function getProductAttributesByGroupId($product_id, $a_group_id){
			$product_attribute_group_data = [];
			
			$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM product_attribute pa LEFT JOIN attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND ag.attribute_group_id = '" . (int)$a_group_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
			
			foreach ($product_attribute_group_query->rows as $product_attribute_group) {
				$product_attribute_data = [];
				
				$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM product_attribute pa LEFT JOIN attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");
				
				foreach ($product_attribute_query->rows as $product_attribute) {
					$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
					);
				}
				
				$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
				);
			}
			
			return $product_attribute_group_data;
		}
		
		public function parseStockTerm($term){
			
			if (!$term){
				return false;
			}
			
			$exploded = explode('-', $term);
			
			if (!empty($exploded[1])){
				if ($this->config->get('config_language') == 'uk'){
					$result = $exploded[0] . '-' . $exploded[1] . ' ' . getUkrainianPluralWord((int)$exploded[1], $this->language->get('text_dt_day_text'));
					} else {
					$result = $exploded[0] . '-' . \morphos\Russian\TimeSpeller::spellInterval(DateInterval::createFromDateString('+' . (int)$exploded[1] . ' day'));
				}
				
				
				} else {
				
				if ($this->config->get('config_language') == 'uk'){
					$result = $exploded[0] . ' ' . getUkrainianPluralWord((int)$exploded[1], $this->language->get('text_dt_day_text'));
					} else {
					$result = \morphos\Russian\TimeSpeller::spellInterval(DateInterval::createFromDateString('+' . (int)$exploded[0] . ' day'));
				}
				
			}
			
			//и ебучий случай, но как же не хочется разбираться с морфосом
			$result = str_ireplace('1 месяц', '30 дней', $result);
			$result = str_ireplace('1 час', '', $result);
			
			return trim($result);
		}
		
		public function parseStockTermToArray($term){
			
			if (!$term){
				return false;
			}
			
			$exploded = explode('-', $term);
			
			if ($exploded[0] && $exploded[1]){
				
				return array(
				'start' => (int)trim($exploded[0]),
				'end'	=> (int)trim($exploded[1])
				);
				
				} else {
				return false;
			}		
		}
				
		public function parseProductStockDataOneString($product, $returnUnformatted = false){
			$stockText = '';
			$stockTerm = '';
			$stockClass = 'ask';

			//Перезначение настройкой
			if (!$this->config->get('config_delivery_outstock_enable') && !$product[$this->config->get('config_warehouse_identifier')]){
				return '';
			}
			
			//Переназначение по статусу частично в наличии
			if ($product['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')){
				$stockText = $this->language->get('text_dt_ask_stock');	
				$stockTerm = false;
				$stockClass = 'ask';
			}
			
			//Переназначение по статусу полностью нет в наличии
			if ($product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')){
				$stockText = $this->language->get('text_dt_not_in_stock');	
				$stockClass = 'bad';
				$stockTerm = false;
			}
			
			//Значение по-умолчанию для статуса "есть в наличии"
			if ($product['stock_status_id'] == $this->config->get('config_stock_status_id') || $product['stock_status_id'] == $this->config->get('config_in_stock_status_id')){
				$stockText = $this->language->get('text_dt_preorder');	
				$stockTerm = $this->config->get('config_delivery_outstock_term');
				$stockClass = 'good';
			}
			
			//Если есть на складе в текущей стране (самый простой случай)
			if ($product[$this->config->get('config_warehouse_identifier_local')]) {
				
				//Поскольку заменяется КЗ и БЕ на рашку где-то, то это вот для обратной совместимости
				if (in_array($this->config->get('config_warehouse_identifier_local'), array('quantity_stockMN', 'quantity_stockAS'))){
					
					$stockText = $this->language->get('text_dt_in_stock');				
					$stockTerm = $this->config->get('config_delivery_russia_term');
					$stockClass = 'good';
					
					} else {
					
					$stockText = $this->language->get('text_dt_in_stock');				
					$stockTerm = $this->config->get('config_delivery_instock_term');
					$stockClass = 'good';
					
				}
				
				//Если есть на складе в том складе, откуда выполняется отправка (МСК => KЗ, БУ)
				} elseif ($product[$this->config->get('config_warehouse_identifier')]) {
				
				$stockText = $this->language->get('text_dt_in_stock');				
				$stockTerm = $this->config->get('config_delivery_russia_term');
				$stockClass = 'good';
				
				//Есть на центральном складе
				} elseif ($product['quantity_stock']){
				
				$stockText = $this->language->get('text_dt_in_stock');				
				$stockTerm = $this->config->get('config_delivery_central_term');
				$stockClass = 'good';
				
			}
			
							
			if ($returnUnformatted){
				return  $this->parseStockTermToArray($stockTerm);
			}

			$result = '';
			
			if ($stockText && $stockTerm){
				$result = $stockText . ', ' . $this->parseStockTerm($stockTerm);
				} elseif ($stockText){
				$result = $stockText;
				} elseif ($stockTerm){
				$result = $stockTerm;
			}
			
			if (is_array($result)){
			//	$this->log->debug($result);			
			}

			$result = "<span class='terms $stockClass'>" . $result . '</span>';
			
			return $result;
		}
		
		public function parseProductStockData($result){
			
			//НАЛИЧИЕ 
			$stock_data = [];
			if ($result[$this->config->get('config_warehouse_identifier')]) {
				
				if (in_array($this->config->get('config_store_id'),array(0, 1))){			
					$stock_data['stock_type'] = 'in_stock_in_country';					
					//Белоруссия
					} elseif (in_array($this->config->get('config_store_id'),array(5))) {
					$stock_data['stock_type'] = 'in_stock_in_moscow_for_by';
					//Козохстон
					} else {
					$stock_data['stock_type'] = 'in_stock_in_moscow_for_kzby';
				}
				
				//есть на складе в Москве
				}	elseif ($result['quantity_stockM']) {
				
				if ($this->config->get('config_store_id') == 0){
					$stock_data['stock_type'] = 'in_stock_in_central_msk';
					} else {
					$stock_data['stock_type'] = 'in_stock_in_central';
				}
				
				//есть в наличии на центральном складе
				} elseif ($result['quantity_stock']) {
				$stock_data['stock_type'] = 'in_stock_in_central';
				
				//нету в наличии физически нигде, но статус в наличии
				} elseif ($result['stock_status_id'] == $this->config->get('config_stock_status_id')) {
				$stock_data['stock_type'] = 'supplier_has';
				
				//стоит статус "уточните наличие"
				} elseif ($result['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')) {
				$stock_data['stock_type'] = 'need_ask_about_stock';
				
				//нету нигде, статус нет у поставщика
				} elseif ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')) {
				$stock_data['stock_type'] = 'supplier_has_no_can_not_buy';
				
				//хер пойми что за статус, какая-то непонятная ситуация, но отдать что-то надо
				} else {
				$stock_data['stock_type'] = 'shit_knows_this_status:' . $result['stock_status_id'] . ':' . $result['stock_status'];
			}								
			
			//Условие не показывать сроки доставки если нет на складе 06.06.2019
			$stock_data['show_delivery_terms'] = true;
			if (!$result['quantity_stockM'] && !$result['quantity_stock'] && !$result['quantity_stockK']){
				if (!in_array($stock_data['stock_type'], array('supplier_has', 'supplier_has', 'need_ask_about_stock', 'supplier_has_no_can_not_buy', 'shit_knows_this_status:' . $result['stock_status_id'] . ':' . $result['stock_status']))){
					$stock_data['show_delivery_terms'] = false;
				}
			}
			
			
			$stock_data['stock'] = $result['stock_status'];
			$stock_data['stock_status_id'] = $result['stock_status_id'];					
			$stock_data['stock_color'] = ($result['stock_status_id'] == $this->config->get('config_stock_status_id')) ? '#4C6600' : '#BA0000';
			
			return $stock_data;		
		}
		
		public function getProductAttributeValueById($product_id, $attribute_id){
			
			$query = $this->db->query("SELECT pa.text FROM product_attribute pa WHERE pa.product_id = '" . (int)$product_id . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.attribute_id = '" . (int)$attribute_id . "' LIMIT 1");
			
			if ($query->num_rows) {
				return $query->row['text'];
				} else {
				return false;
			}		
		}
	}																													