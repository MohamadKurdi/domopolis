<?php
	
	class ControllerKPService extends Controller {

		public function pingAPI($ping){
			header('HTTP/1.1 200 OK');
			$this->response->setOutput('OK');			
		}

		public function deleteduplicates(){
		/*	$this->load->model('catalog/product');
			$query = $this->db->query("SELECT product_id FROM product WHERE product_id NOT IN (SELECT product_id FROM tmp_product)");

			$i = 1;
			foreach ($query->rows as $row){
				echoLine($i . '/' . $query->num_rows);
				$i++;

				$this->model_catalog_product->deleteProductSimple($row['product_id']);
			}
		*/
		}

		public function smsQueue(){
			if (!$this->config->get('config_sms_enable_queue_worker')){
				echoLine('[ControllerKPService::smsQueue] CRON IS DISABLED IN ADMIN', 'e');
				return;
			}

			if ($this->config->has('config_sms_enable_queue_worker_time_start') && $this->config->has('config_sms_enable_queue_worker_time_end')){
				$interval = new \hobotix\Interval($this->config->get('config_sms_enable_queue_worker_time_start') . '-' . $this->config->get('config_sms_enable_queue_worker_time_end'));

				if (!$interval->isNow()){
					echoLine('[ControllerKPService::smsQueue] NOT ALLOWED TIME', 'e');
					return;
				} else {
					echoLine('[ControllerKPService::smsQueue] ALLOWED TIME', 's');				
				}
			}
			
			$this->smsQueue->cron();
		}

		public function fillSpecialCategory(){
			if ($this->config->get('config_special_controller_logic') && $this->config->get('config_special_category_id')){
				$this->db->query("DELETE FROM product_to_category WHERE category_id = '" . (int)$this->config->get('config_special_category_id') . "'");
				$this->db->query("INSERT IGNORE INTO product_to_category (product_id, category_id) SELECT DISTINCT ps.product_id, '" . $this->config->get('config_special_category_id') . "' FROM product_special ps LEFT JOIN product p ON ps.product_id = p.product_id WHERE p.status = 1 AND p.quantity > 0 AND ps.price < p.price AND ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");
			}

			return $this;
		}

		public function fillMarkDownCategory(){
			$this->db->non_cached_query("DELETE FROM product_to_category WHERE category_id = '" . GENERAL_MARKDOWN_CATEGORY . "'");
			$this->db->non_cached_query("DELETE FROM product_to_category WHERE product_id IN (SELECT product_id FROM product WHERE is_markdown = 1)");
			$this->db->non_cached_query("INSERT INTO product_to_category (product_id, category_id) (SELECT DISTINCT product_id, " . GENERAL_MARKDOWN_CATEGORY . " FROM product WHERE is_markdown = 1)");

			return $this;
		}

		public function fillAlsoBoughtProducts(){
			if ($this->config->get('config_also_bought_auto_enable')){
				echoLine('[Starting fillAlsoBoughtProducts] config_also_bought_auto_enable = ON', 's');

				$query = $this->db->query("SELECT GROUP_CONCAT(product_id SEPARATOR ',') as also_bought FROM order_product WHERE product_id IN (SELECT product_id FROM product) GROUP BY order_id HAVING COUNT(product_id) > 1");

				foreach ($query->rows as $row){
					if ($also_bought = explode(',', $row['also_bought'])){
						$tmp = $also_bought;
						foreach ($also_bought as $product_id){
							foreach ($tmp as $also_bought_id){
								if ($product_id != $also_bought_id){
									$this->db->query("INSERT IGNORE INTO product_also_bought (product_id, also_bought_id) VALUES ('" . $product_id . "', '" . $also_bought_id . "')");
								}								
							}
						}
					}
				}
			} else {
				echoLine('[Starting fillAlsoBoughtProducts] config_also_bought_auto_enable = OFF', 'e');
			}

			return $this;
		}

		public function fillRelatedCategories(){
			if ($this->config->get('config_related_categories_auto_enable')){
				echoLine('[Starting fillRelatedCategories] config_related_categories_auto_enable = ON', 's');

				$query = $this->db->query("SELECT DISTINCT(pab.product_id), GROUP_CONCAT(pab.also_bought_id SEPARATOR ',') as also_bought, p2c.category_id FROM product_also_bought pab LEFT JOIN product_to_category p2c ON (pab.product_id = p2c.product_id AND p2c.main_category = 1) WHERE NOT ISNULL(p2c.category_id) GROUP BY pab.product_id");

				foreach ($query->rows as $row){
					if ($also_bought = explode(',', $row['also_bought'])){
						foreach ($also_bought as $also_bought_id){
							$this->db->query("INSERT IGNORE INTO category_related (category_id, related_category_id) SELECT '" . (int)$row['category_id'] . "', category_id FROM product_to_category WHERE product_id = '" . $also_bought_id . "' ORDER BY main_category DESC LIMIT 1");					
						}
					}
				}
			}  else {
				echoLine('[Starting fillAlsoBoughtProducts] config_related_categories_auto_enable = OFF', 'e');
			}

			//return $this;
		}
		
		public function optimizeProductsDB(){
			echoLine ('[optimizeProductsDB] Регулярные задачи обслуживания товарной базы', 'i');

			echoLine('[optimizeProductsDB] Очистка несуществующих записей', 'i');
			$this->db->query("DELETE FROM product_description WHERE product_id NOT IN (SELECT product_id FROM product)");
			
			$this->db->query("DELETE FROM product_also_bought WHERE product_id NOT IN (SELECT product_id FROM product)");
			$this->db->query("DELETE FROM product_also_bought WHERE also_bought_id NOT IN (SELECT product_id FROM product)");

			$this->db->query("DELETE FROM product_also_viewed WHERE product_id NOT IN (SELECT product_id FROM product)");
			$this->db->query("DELETE FROM product_also_viewed WHERE also_viewed_id NOT IN (SELECT product_id FROM product)");

			$this->db->query("DELETE FROM category_related WHERE category_id NOT IN (SELECT category_id FROM category)");
			$this->db->query("DELETE FROM category_related WHERE related_category_id NOT IN (SELECT category_id FROM category)");

			$this->db->query("DELETE FROM product_amzn_data WHERE product_id NOT IN (SELECT product_id FROM product)");		

			$this->db->query("UPDATE manufacturer SET new = '0' WHERE new = '1' AND date_added <= DATE_SUB(NOW(), INTERVAL 3 MONTH)");		

			echoLine('[optimizeProductsDB] Нормализация рейтинга товаров', 'i');
			$this->db->query("UPDATE product SET xrating = (SELECT AVG(rating) as xrating FROM review WHERE status = 1 AND product_id = product.product_id GROUP BY product_id)");
			$this->db->query("UPDATE product SET xreviews = (SELECT COUNT(*) as trating FROM review WHERE status = 1 AND product_id = product.product_id GROUP BY product_id)");

			echoLine('[optimizeProductsDB] Добавление несуществующих языковых записей review', 'i');
			$query = $this->db->query("SELECT review_id FROM review WHERE review_id NOT IN (SELECT review_id FROM review_description)");
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();
			
			foreach ($query->rows as $row){				
				foreach ($languages as $language){										
					$this->db->query("INSERT IGNORE INTO review_description SET review_id = '" . (int)$row['review_id'] . "', language_id = '" . $language['language_id'] . "', text = '', answer = '', good = '', bads = ''");			
				}
			}

            if ($this->config->get('config_rainforest_cleanup_empty_manufacturers')){
                echoLine('[optimizeProductsDB] Cleaning empty manufacturers', 'i');
                $this->load->model('catalog/manufacturer');

                $query = $this->db->query("SELECT manufacturer_id, name FROM manufacturer WHERE manufacturer_id NOT IN (SELECT manufacturer_id FROM product)");

                foreach ($query->rows as $row){
                    echoLine('[optimizeProductsDB] Cleaning manufacturer: ' . $row['name'] . ' with id ' . $row['manufacturer_id'], 'w');
                    $this->model_catalog_manufacturer->deleteManufacturer($row['manufacturer_id']);
                }
            }

            if ($this->config->get('config_rainforest_cleanup_empty_attributes')){
                echoLine('[optimizeProductsDB] Cleaning empty attributes', 'i');
                $this->load->model('catalog/attribute');

                $this->db->query("DELETE FROM product_attribute WHERE text = ''");
                $this->db->query("DELETE FROM product_feature WHERE text = ''");
                $this->db->query("DELETE FROM attribute_description WHERE name = ''");
                $this->db->query("DELETE FROM attribute WHERE attribute_id NOT IN (SELECT attribute_id FROM attribute_description)");
                $this->db->query("DELETE FROM product_attribute WHERE attribute_id NOT IN (SELECT attribute_id FROM attribute)");
                $this->db->query("DELETE FROM product_attribute WHERE `text` NOT REGEXP '[a-zA-Zа-яА-Я]'");

                $query = $this->db->query("SELECT a.attribute_id, ad.name FROM attribute a LEFT JOIN attribute_description ad ON (a.attribute_id = ad.attribute_id AND language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE (a.attribute_id NOT IN (SELECT attribute_id FROM product_attribute) AND a.attribute_id NOT IN (SELECT feature_id FROM product_feature))");

                foreach ($query->rows as $row){
                    echoLine('[optimizeProductsDB] Cleaning attribute: ' . $row['name'] . ' with id ' . $row['attribute_id'], 'w');
                    $this->model_catalog_attribute->deleteAttribute($row['attribute_id']);
                }
            }


			echoLine('[optimizeProductsDB] Нормализация наличия видео у товаров', 'i');
			$this->db->query("UPDATE product SET xhasvideo = 1 WHERE product_id IN (SELECT DISTINCT product_id FROM product_video)");

			echoLine('[optimizeProductsDB] Выравнивание количества офферов', 'i');
			$this->db->query("UPDATE product SET product.amzn_offers_count = (SELECT COUNT(*) FROM product_amzn_offers WHERE product_amzn_offers.asin = product.asin)");
			$this->db->query("UPDATE product SET product.amzn_no_offers = 1 WHERE amzn_offers_count = 0 AND amzn_last_offers <> '0000-00-00 00:00:00'");
			$this->db->query("UPDATE product SET product.amzn_no_offers = 0 WHERE amzn_offers_count > 0 OR amzn_last_offers = '0000-00-00 00:00:00'");	

			$bought_fields = [
				'bought_for_week' 		=> '7 DAY',
				'bought_for_month' 		=> '1 MONTH',
				'bought_for_3month' 	=> '3 MONTH',
				'bought_for_6month' 	=> '7 DAY',
				'bought_for_12month' 	=> '12 MONTH',
				'bought_for_alltime' 	=> '',
			];		
			
			foreach ($bought_fields as $field => $interval){
				echoLine('[optimizeProductsDB] Подсчет количества продаж за ' . $interval, 'i');	

				$sql = "UPDATE product p SET p.`" . $field . "` = ";
				$sql .= " (SELECT SUM(quantity) FROM order_product op WHERE op.product_id = p.product_id AND op.order_id IN (SELECT o.order_id FROM `order` o WHERE o.order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' ";

				if ($interval){
					$sql .= " AND DATE(o.date_added) >= DATE(DATE_SUB(NOW(), INTERVAL " . $this->db->escape($interval) ."))";
				}

				$sql .= "))";

				$this->db->query($sql);
			}			

			echoLine('[optimizeProductsDB] Подсчёт продаж по категориям', 'i');	
			$this->db->query("UPDATE category SET bought_for_month = (SELECT SUM(quantity) FROM order_product op WHERE op.product_id IN (SELECT product_id FROM product_to_category WHERE category_id = category.category_id) AND op.order_id IN (SELECT o.order_id FROM `order` o WHERE o.order_status_id > 0 AND DATE(o.date_added) >= DATE(DATE_SUB(NOW(),INTERVAL 30 DAY))))");

			echoLine('[optimizeProductsDB] Финальные категории', 'i');	
			$this->db->query("UPDATE category SET final = 0 WHERE 1");
			$this->db->query("UPDATE category SET final = 1 WHERE category_id NOT IN ( SELECT parent_id FROM ( SELECT parent_id FROM category ) AS subquery )");			

			echoLine('[optimizeProductsDB] Подсчёт продаж по брендам', 'i');	
			$this->db->query("UPDATE manufacturer SET bought_for_month = (SELECT SUM(quantity) FROM order_product op WHERE op.product_id IN (SELECT product_id FROM product WHERE manufacturer_id = manufacturer.manufacturer_id) AND op.order_id IN (SELECT o.order_id FROM `order` o WHERE o.order_status_id > 0 AND DATE(o.date_added) >= DATE(DATE_SUB(NOW(),INTERVAL 30 DAY))))");

			echoLine('[optimizeProductsDB] Подсчёт количества товаров по брендам', 'i');	
			$this->db->query("UPDATE manufacturer SET products_total 			= (SELECT count(product_id) AS total FROM product WHERE manufacturer_id = manufacturer.manufacturer_id)");
			$this->db->query("UPDATE manufacturer SET products_total_enabled 	= (SELECT count(product_id) AS total FROM product WHERE status = 1 AND manufacturer_id = manufacturer.manufacturer_id)");

			echoLine('[optimizeProductsDB] Выравнивание количества со складами', 'i');
			$this->db->query("UPDATE product SET quantity = (quantity_stock + quantity_stockK + quantity_stockM) WHERE quantity < (quantity_stock + quantity_stockK + quantity_stockM)");

			echoLine('[optimizeProductsDB] Выравнивание количества', 'i');
			$this->db->query("UPDATE product SET quantity = 9999 WHERE stock_status_id = '" . $this->config->get('config_stock_status_id') . "' AND quantity = 0");
			$this->db->query("UPDATE product SET quantity = 9999 WHERE stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "' AND quantity = 0");

			echoLine('[optimizeProductsDB] Нормализация markdown', 'i');
			$this->db->query("UPDATE `product` SET ean = '', asin = '', isbn = ''	WHERE stock_product_id > 0");
            $this->db->query("UPDATE `order_product_nogood` opn SET product_id = (SELECT p.stock_product_id FROM product p WHERE p.product_id = opn.product_id LIMIT 1) WHERE product_id IN (SELECT product_id FROM product WHERE stock_product_id > 0)");
            $this->db->query("UPDATE `return` opn SET product_id = (SELECT p.stock_product_id FROM product p WHERE p.product_id = opn.product_id LIMIT 1) WHERE product_id IN (SELECT product_id FROM product WHERE stock_product_id > 0)");
            $this->db->query("UPDATE `order_product` opn SET product_id = (SELECT p.stock_product_id FROM product p WHERE p.product_id = opn.product_id LIMIT 1) WHERE product_id IN (SELECT product_id FROM product WHERE stock_product_id > 0)");
            $this->db->query("UPDATE `product` SET status = 0 WHERE is_markdown = 1 AND (quantity_stock + quantity_stockK + quantity_stockM) = 0");

            if ($this->config->get('config_enable_amazon_specific_modes')){
            	$this->db->query("UPDATE `product` SET display_in_catalog = 1 WHERE main_variant_id > 0 AND (quantity_stock + quantity_stockK + quantity_stockM) > 0");
            	$this->db->query("UPDATE `product` SET display_in_catalog = 0 WHERE main_variant_id > 0 AND (quantity_stock + quantity_stockK + quantity_stockM) = 0");
            }

            echoLine('[optimizeProductsDB] Нормализация manufacturer_name to product_description', 'i');
            $this->db->query("UPDATE product_description pd LEFT JOIN product p0 ON pd.product_id = p0.product_id SET pd.manufacturer_name =( SELECT m.name FROM manufacturer m LEFT JOIN product p1 ON p1.manufacturer_id = m.manufacturer_id WHERE p1.product_id = pd.product_id ) WHERE ( NOT ISNULL(p0.manufacturer_id) AND p0.manufacturer_id <> '0' )");

			
			echoLine('[optimizeProductsDB] Исправление багов в описаниях', 'i');
			//$this->db->query("UPDATE product_description SET description = REPLACE(description, '< /', '</');");			
			
			echoLine('[optimizeProductsDB] Очистка несуществующих категорий', 'i');
			$this->db->non_cached_query("DELETE FROM product_to_category WHERE category_id NOT IN (SELECT distinct category_id FROM category)");			

			echoLine('[optimizeProductsDB] Коррекция маркеров амазона', 'i');
			$this->db->non_cached_query("UPDATE product p SET fill_from_amazon = 1 WHERE filled_from_amazon = 1 AND (fill_from_amazon = 0 OR ISNULL(fill_from_amazon))");

			echoLine('[optimizeProductsDB] Включение полностью полученных товаров в включенных категориях', 'i');
			if ($this->config->get('config_enable_amazon_specific_modes')){
				$this->db->non_cached_query("UPDATE product SET status = 1 WHERE filled_from_amazon = 1 AND product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1))");
			}
			
			echoLine('[optimizeProductsDB] Очистка неликвида', 'i');
			$this->db->non_cached_query("UPDATE product p SET is_illiquid = 0 WHERE quantity_stock = 0 AND quantity_stockK = 0 AND quantity_stockM = 0");

			$this->db->non_cached_query("DELETE FROM product_special WHERE price = 0");
			$this->db->non_cached_query("DELETE FROM product_price_to_store WHERE price = 0");
			$this->db->non_cached_query("DELETE FROM product_price_national_to_store WHERE price = 0");
			$this->db->non_cached_query("DELETE FROM product_price_national_to_yam WHERE price = 0");		

			$this->fillSpecialCategory()->fillMarkDownCategory()->fillAlsoBoughtProducts()->fillRelatedCategories();	
		}

        public function dailyWork(){
            $this->echoLine('[dailyWork] Statistics on employee work', 'w');

            $this->echoLine('[dailyWork] Initializing records for today', 'w');
            $this->db->query("INSERT IGNORE INTO user_worktime (`user_id`, `date`) SELECT user_id, DATE(NOW()) FROM user WHERE count_worktime = 1 ");

            $this->echoLine('[dailyWork] Counting inbound calls', 'w');
            $this->db->query("UPDATE user_worktime uw SET inbound_call_count = (SELECT COUNT(DISTINCT cc.customer_call_id) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 1 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting outbound calls', 'w');
            $this->db->query("UPDATE user_worktime uw SET outbound_call_count = (SELECT COUNT(DISTINCT cc.customer_call_id) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 0 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Calculating inbound call duration', 'w');
            $this->db->query("UPDATE user_worktime uw SET inbound_call_duration = (SELECT SUM(cc.length) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 1 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Calculating outbound call duration', 'w');
            $this->db->query("UPDATE user_worktime uw SET outbound_call_duration = (SELECT SUM(cc.length) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 0 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting assigned orders', 'w');
            $this->db->query("UPDATE user_worktime uw SET owned_order_count = (SELECT COUNT(DISTINCT(order_id)) FROM `order` WHERE DATE(date_added) = DATE(NOW()) AND manager_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting order modifications', 'w');
            $this->db->query("UPDATE user_worktime uw SET edit_order_count = (SELECT COUNT(DISTINCT(order_id)) FROM `order_save_history` WHERE DATE(datetime) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(datetime)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Calculating first action of the day', 'w');
            $this->db->query("UPDATE user_worktime uw SET worktime_start = (SELECT MIN(date) FROM `adminlog` WHERE DATE(date) = DATE(NOW()) AND user_id = uw.user_id) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Calculating last action of the day', 'w');
            $this->db->query("UPDATE user_worktime uw SET worktime_finish = (SELECT MAX(date) FROM `adminlog` WHERE DATE(date) = DATE(NOW()) AND user_id = uw.user_id) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting daily actions', 'w');
            $this->db->query("UPDATE user_worktime uw SET daily_actions = (SELECT COUNT(DISTINCT log_id) FROM `adminlog` WHERE DATE(date) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting completed orders for the day', 'w');
            $this->db->query("UPDATE user_worktime uw SET success_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting canceled orders for the day', 'w');
            $this->db->query("UPDATE user_worktime uw SET cancel_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting processed orders for the day', 'w');
            $this->db->query("UPDATE user_worktime uw SET treated_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id = '" . $this->config->get('config_treated_status_id') . "' AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting confirmed orders for the day', 'w');
            $this->db->query("UPDATE user_worktime uw SET confirmed_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id IN(" . implode(',',$this->config->get('config_manager_confirmed_order_status_id')) . ") AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");

            $this->echoLine('[dailyWork] Counting problem orders at the moment', 'w');
            $this->db->query("UPDATE user_worktime uw SET problem_order_count = (SELECT COUNT(DISTINCT order_id) as count FROM `order` WHERE (order_status_id IN (" . implode(',', $this->config->get('config_problem_order_status_id')) . ") OR (probably_cancel=1 OR probably_close=1 OR probably_problem=1) AND order_status_id > '0') AND manager_id = uw.user_id) WHERE date = DATE(NOW())");
        }

        public function optimizeCustomerDB(){
            $this->load->model('setting/setting');
            $this->load->model('setting/store');

            echoLine('Normalizing fields', 'w');

            echoLine('[optimizeCustomerDB] Normalizing empty names', 'w');
            $this->db->query("UPDATE customer SET firstname = email WHERE LENGTH(firstname)<=2 OR ISNULL(firstname)");

            echoLine('[optimizeCustomerDB] Normalizing addresses', 'w');
            $this->db->query("UPDATE address SET address_1 = REPLACE(address_1, 'г ', '')");
            $this->db->query("UPDATE address SET address_2 = REPLACE(address_2, 'г ', '')");
            $this->db->query("UPDATE address SET city = REPLACE(city, 'г ', '')");
            $this->db->query("UPDATE address SET address_1 = REPLACE(address_1, 'г.', '')");
            $this->db->query("UPDATE address SET address_2 = REPLACE(address_2, 'г.', '')");
            $this->db->query("UPDATE address SET city = REPLACE(city, 'г.', '')");

            $this->db->query("UPDATE address SET address_1 = REPLACE(address_1, 'м ', '')");
            $this->db->query("UPDATE address SET address_2 = REPLACE(address_2, 'м ', '')");
            $this->db->query("UPDATE address SET city = REPLACE(city, 'м ', '')");
            $this->db->query("UPDATE address SET address_1 = REPLACE(address_1, 'м.', '')");
            $this->db->query("UPDATE address SET address_2 = REPLACE(address_2, 'м.', '')");
            $this->db->query("UPDATE address SET city = REPLACE(city, 'м.', '')");


            echoLine('[optimizeCustomerDB] TRIM fields', 'w');
            $this->db->query("UPDATE address SET address_1 = TRIM(address_1)");
            $this->db->query("UPDATE address SET address_2 = TRIM(address_2)");
            $this->db->query("UPDATE address SET city = TRIM(city)");
            $this->db->query("UPDATE address SET firstname = TRIM(firstname)");
            $this->db->query("UPDATE address SET lastname = TRIM(lastname)");
            $this->db->query("UPDATE customer SET firstname = TRIM(firstname)");
            $this->db->query("UPDATE customer SET lastname = TRIM(lastname)");

            echoLine('[optimizeCustomerDB] Authorization key', 'w');
            $this->db->query("UPDATE customer SET utoken = md5(concat(email, '" . $this->config->get('config_encryption') . "')) WHERE 1");


            echoLine('[optimizeCustomerDB] Remove bad email', 'w');
            $this->db->query("UPDATE customer SET email = telephone WHERE email = '" . $this->config->get('config_email') . "'");

            echoLine('[optimizeCustomerDB] Normalizing phone', 'w');
            $this->db->query("UPDATE customer SET normalized_telephone = TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) WHERE LENGTH(telephone)>0");

            echoLine('[optimizeCustomerDB] Normalizing fax', 'w');
            $this->db->query("UPDATE customer SET normalized_fax = TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) WHERE LENGTH(fax)>0");

            echoLine('[optimizeCustomerDB] Normalizing birthday', 'w');
            $this->db->query("UPDATE  customer SET birthday = '0000-00-00' WHERE birthday IN ('1970-01-01', '-0001-11-30')");

            echoLine('[optimizeCustomerDB] Recalculating birthday - day', 'w');
            $this->db->query("UPDATE customer SET birthday_date = DAY(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");

            echoLine('[optimizeCustomerDB] Recalculating birthday - month', 'w');
            $this->db->query("UPDATE customer SET birthday_month = MONTH(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");

            $this->db->query("UPDATE customer SET birthday_month = '0', birthday_date = '0' WHERE birthday = '0000-00-00'");

            echoLine('[optimizeCustomerDB] Checking push notification subscription', 'w');
            $this->db->query("UPDATE customer SET has_push = 1 WHERE customer_id IN (SELECT DISTINCT customer_id FROM customer_push_ids WHERE 1)");

            echoLine('[optimizeCustomerDB] Binding products to customer', 'w');
            $this->db->query("UPDATE order_product op SET customer_id = (SELECT customer_id FROM `order` o WHERE op.order_id = o.order_id LIMIT 1)");

            echoLine('[optimizeCustomerDB] Calculating order count', 'w');
            $this->db->query("UPDATE customer c SET order_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0)");

            echoLine('[optimizeCustomerDB] Calculating completed order count', 'w');
            $this->db->query("UPDATE customer c SET order_good_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");

            echoLine('[optimizeCustomerDB] Calculating cancelled order count', 'w');
            $this->db->query("UPDATE customer c SET order_bad_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_cancelled_status_id') . "')");

            echoLine('[optimizeCustomerDB] Calculating date of last completed order', 'w');
            $this->db->query("UPDATE customer c SET order_good_last_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added DESC LIMIT 1)");

            echoLine('[optimizeCustomerDB] Calculating date of first completed order', 'w');
            $this->db->query("UPDATE customer c SET order_good_first_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added ASC LIMIT 1)");

            echoLine('[optimizeCustomerDB] Calculating date of last order', 'w');
            $this->db->query("UPDATE customer c SET order_last_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added DESC LIMIT 1)");

            echoLine('[optimizeCustomerDB] Calculating date of first order', 'w');
            $this->db->query("UPDATE customer c SET order_first_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added ASC LIMIT 1)");

            echoLine('[optimizeCustomerDB] Selecting source of first order', 'w');
            $this->db->query("UPDATE customer c SET first_order_source = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(first_referrer, '/', 3), '://', -1), '/', 1), '?', 1) AS domain FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added ASC LIMIT 1)");

            echoLine('[optimizeCustomerDB] Calculating total amount', 'w');
            $this->db->query("UPDATE customer c SET total_cheque = (SELECT SUM(total_national) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'  GROUP BY customer_id)");

            echoLine('[optimizeCustomerDB] Calculating total product amount', 'w');
            $this->db->query("UPDATE customer c SET total_product_cheque = (SELECT SUM(total_national) FROM `order_product` WHERE order_id IN (SELECT order_id FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'))");

            echoLine('[optimizeCustomerDB] Calculating average check', 'w');
            $this->db->query("UPDATE customer c SET avg_cheque = (SELECT AVG(total_national) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'  GROUP BY customer_id)");

            echoLine('[optimizeCustomerDB] Calculating call count', 'w');
            $this->db->query("UPDATE customer c SET total_calls = (SELECT COUNT(customer_call_id) FROM `customer_calls` WHERE customer_id = c.customer_id)");

            echoLine('[optimizeCustomerDB] Calculating average call duration', 'w');
            $this->db->query("UPDATE customer c SET avg_calls_duration = (SELECT AVG(length) FROM `customer_calls` WHERE customer_id = c.customer_id AND length > 0 GROUP BY customer_id)");

            echoLine('[optimizeCustomerDB] Copying country', 'w');
            $this->db->query("UPDATE customer c SET country_id = (SELECT country_id FROM `address` WHERE address_id = c.address_id LIMIT 1)");


            echoLine('[optimizeCustomerDB] Rebuilding countries', 'w');
            foreach ($this->model_setting_store->getStores() as $store){
                $sql = "UPDATE customer c SET country_id = '" . (int)$this->model_setting_setting->getKeyValue('config_country_id', $store['store_id']) . "' WHERE store_id = '" . $store['store_id'] . "'";
                echoLine('[optimizeCustomerDB] ' . $sql, 'w');
                $this->db->query($sql);

                $sql = "UPDATE address SET country_id = '" . (int)$this->model_setting_setting->getKeyValue('config_country_id', $store['store_id']) . "' WHERE customer_id IN (SELECT customer_id FROM customer WHERE store_id = '" . (int)$store['store_id'] . "') AND country_id = 0";

                echoLine('[optimizeCustomerDB] ' . $sql, 'w');
                $this->db->query($sql);
            }

            echoLine('[optimizeCustomerDB] Copying city', 'w');
            $this->db->query("UPDATE customer c SET city = (SELECT city FROM `address` a WHERE a.address_id = c.address_id LIMIT 1)");

            echoLine('[optimizeCustomerDB] Updating customer CSI', 'w');
            $this->db->query("UPDATE customer c SET c.csi_average = ( SELECT AVG(o.csi_average) FROM `order` o WHERE o.customer_id = c.customer_id AND o.csi_average > 0 AND o.csi_reject = 0 GROUP BY o.customer_id )");


            echoLine('[optimizeCustomerDB] Updating segment dynamics', 'w');
            $this->db->query("INSERT INTO segments_dynamics (segment_id, customer_count, total_cheque, avg_cheque, order_good_count, order_bad_count, order_good_to_bad, avg_csi, date_added) (SELECT segment_id, customer_count, total_cheque, avg_cheque, order_good_count, order_bad_count, order_good_to_bad, avg_csi, NOW() FROM segments WHERE enabled = 1)");

            echoLine('[optimizeCustomerDB] Updating customer count in segment', 'w');
            $this->db->query("UPDATE segments s SET customer_count = (SELECT COUNT(*) FROM customer_segments cs WHERE cs.segment_id = s.segment_id)");

            echoLine('[optimizeCustomerDB] Updating segment total amount', 'w');
            $this->db->query("UPDATE segments s SET total_cheque =( SELECT SUM(total) AS total FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");

            echoLine('[optimizeCustomerDB] Updating segment average check', 'w');
            $this->db->query("UPDATE segments s SET avg_cheque =( SELECT AVG(total) AS total FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");

            echoLine('[optimizeCustomerDB] Updating completed order count in segments', 'w');
            $this->db->query("UPDATE segments s SET order_good_count = (SELECT COUNT(DISTINCT order_id) FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");

            echoLine('[optimizeCustomerDB] Updating cancelled order count in segments', 'w');
            $this->db->query("UPDATE segments s SET order_bad_count = (SELECT COUNT(DISTINCT order_id) FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_cancelled_status_id') . "')");

            echoLine('[optimizeCustomerDB] Updating customer CSI in segments', 'w');
            $this->db->query("UPDATE segments s SET avg_csi = (SELECT AVG(csi_average) FROM customer WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND csi_average > 0)");

            $this->db->query("UPDATE segments s SET order_good_to_bad = (order_bad_count/order_good_count)*100");
        }
				
		public function optimizeDB(){
			
			echo '[C] Чистим записи журнала событий. 2 месяца.'  . PHP_EOL;
			$this->db->query("DELETE FROM adminlog WHERE DATE(`date`) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)");
			$this->db->query("DELETE FROM `short_url_alias` WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");	

			echo '[C] Чистим записи журнала переводчика. 1 месяц'  . PHP_EOL;
			$this->db->query("DELETE FROM translate_stats WHERE DATE(`time`) <= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");		
			
			echo '[C] Чистим записи журнала уведомлений. 2 месяц.'  . PHP_EOL;
			$this->db->query("DELETE FROM alertlog WHERE DATE(`datetime`) <= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
			
			echo '[C] Чистим записи динамики уволенных менеджеров.'  . PHP_EOL;
			$this->db->query("DELETE FROM manager_order_status_dynamics WHERE manager_id IN (SELECT user_id FROM user WHERE status = 0)");
			$this->db->query("DELETE FROM manager_order_status_dynamics WHERE manager_id NOT IN (SELECT user_id FROM user)");
			
			echo '[C] Чистим записи звонков. 1 год.'  . PHP_EOL;
			$this->db->query("DELETE FROM customer_calls WHERE DATE(`date_end`) <= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");
			
			echo '[C] Чистим записи почты. 2 месяца.'  . PHP_EOL;
			$this->db->query("DELETE FROM emailtemplate_logs WHERE DATE(emailtemplate_log_sent) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)");
			$this->db->query("UPDATE emailtemplate_logs SET emailtemplate_log_content = '' WHERE 1");
			$this->db->query("UPDATE emailtemplate_logs SET emailtemplate_log_text = '' WHERE 1");
			
			echo '[C] Чистим историю клиента от некорректных записей. История за 6 месяцев'  . PHP_EOL;
			$this->db->query("DELETE FROM customer WHERE ip = '' AND DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)");
			$this->db->query("DELETE FROM address WHERE customer_id NOT IN (SELECT customer_id FROM customer)");
			$this->db->query("DELETE FROM customer_ip WHERE customer_id NOT IN (SELECT customer_id FROM customer)");
			$this->db->query("DELETE FROM customer_email_campaigns WHERE customer_id NOT IN (SELECT customer_id FROM customer)");
			$this->db->query("DELETE FROM customer_calls WHERE customer_id NOT IN (SELECT customer_id FROM customer)");
			$this->db->query("DELETE FROM customer_history WHERE customer_id NOT IN (SELECT customer_id FROM customer)");
			$this->db->query("DELETE FROM customer_history WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)");
			$this->db->query("DELETE FROM customer_history WHERE segment_id > 0");
			$this->db->query("DELETE FROM superstat_viewed WHERE DATE(date) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)");

			echo '[C] Уменьшаем объем таблички заказов'  . PHP_EOL;
			$this->db->query("UPDATE `order` SET bottom_text = '', user_agent = '', shipping_address_struct = '', payment_address_struct = '' WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)");			
			
			echo '[C] Чистим записи чеков. 3 месяцев.'  . PHP_EOL;
			$this->db->query("DELETE FROM order_invoice_history WHERE DATE(datetime) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
			$this->db->query("DELETE FROM order_invoice_history WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");			
			
			echo '[C] Чистим записи сохранения выполненных заказов. 2-3 месяца.'  . PHP_EOL;
			$this->db->query("DELETE FROM order_save_history WHERE DATE(datetime) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH) AND order_id IN (SELECT DISTINCT order_id FROM `order` WHERE order_status_id IN (17, 18, 23))");
			$this->db->query("DELETE FROM order_save_history WHERE DATE(datetime) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND order_id NOT IN (SELECT DISTINCT order_id FROM `order` WHERE order_status_id IN (17, 18, 23))");

			echoLine('[optimizeDB] Cleaning offers history older than three month', 'i');						
			$this->db->query("DELETE FROM  product_offers_history  WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");

			echoLine('[optimizeDB] Cleaning products tables from unexistent products', 'i');	
			foreach ((array)\hobotix\RainforestAmazon::productRelatedTables as $table){
				echoLine('[optimizeDB::products] Working with table ' . $table, 'w');
				$sql = "DELETE FROM `" . $table . "` WHERE product_id NOT IN (SELECT product_id FROM product)";
				$this->db->query($sql);
			}

			echoLine('[optimizeDB] Cleaning review descriptions', 'i');
			$this->db->query("DELETE FROM review_description WHERE review_id NOT IN (SELECT review_id FROM review)");
			
			echo '[C] Динамика сегментов. Год'  . PHP_EOL;
			$this->db->query("DELETE FROM segments_dynamics WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)");
			
			echo '[C] Незавершенные заказы. 3 месяца'  . PHP_EOL;
			$this->db->query("DELETE FROM `order` WHERE order_status_id = 0 AND DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
			
			echo '[C] Объединенные заказы. 2 дня'  . PHP_EOL;
			$this->db->query("DELETE FROM `order` WHERE order_status_id = 23 AND DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");

			$this->db->query("DELETE FROM `order` WHERE order_id = 0");
			$this->db->query("DELETE FROM order_product WHERE order_id = 0");
			
			$this->db->query("DELETE FROM order_product WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			$this->db->query("DELETE FROM order_total WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			$this->db->query("DELETE FROM order_history WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			$this->db->query("DELETE FROM order_save_history WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			$this->db->query("DELETE FROM emailtemplate_logs WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			$this->db->query("DELETE FROM order_simple_fields WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			$this->db->query("DELETE FROM simple_custom_data WHERE object_type = 1 AND object_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			$this->db->query("DELETE FROM simple_custom_data WHERE data LIKE 'a:0:{}'");
			$this->db->query("DELETE FROM simple_custom_data WHERE object_type = 1 AND object_id IN (SELECT DISTINCT order_id FROM `order` WHERE order_status_id IN (17, 18, 23))");
			$this->db->query("DELETE FROM simple_custom_data WHERE object_type = 2");
						
			echo '[C] Невалидные коллбеки'  . PHP_EOL;
			$this->db->query("DELETE FROM callback WHERE status_id = 0 AND DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
			$this->db->query("DELETE FROM callback WHERE sip_queue = '' OR sip_queue = '0' AND DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
			$this->db->query("DELETE FROM callback WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
			
			echo '[C] SESSION GARBAGE COLLECTOR'  . PHP_EOL;
			$handler = new Hobotix\SessionHandler\SessionHandler();
			$handler->setDbDetails(DB_SESSION_HOSTNAME, DB_SESSION_USERNAME, DB_SESSION_PASSWORD, DB_SESSION_DATABASE);
			$handler->setDbTable(DB_SESSION_TABLE);
			$handler->gc($handler->lifeTime);			

			echoLine('[optimizeDB] Optimizing products tables', 'w');
			foreach ((array)\hobotix\RainforestAmazon::productRelatedTables as $table){
				echoLine('[optimizeDB::products] Optimizing ' . $table, 'w');
				$sql = "OPTIMIZE table `" . $table . "`";
				$this->db->query($sql);
			}

			$to_optimize_tables = ['adminlog', 'translate_stats', 'superstat_viewed', 'emailtemplate_logs', 'customer', 'customer_online', 'address', 'customer_history', 'order_invoice_history', 'order_save_history', 'product_offers_history', 'order', 'manufacturer', 'manufacturer_description'];
			
			echoLine('[optimizeDB] Optimizing other tables', 'w');
			foreach ((array)$to_optimize_tables as $table){
				echoLine('[optimizeDB::optimize] Optimizing ' . $table, 'w');
				$sql = "OPTIMIZE table `" . $table . "`";
				$this->db->query($sql);
			}	
		}
	}	