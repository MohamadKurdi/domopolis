<?
	
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

		public function fillRelatedCategories(){
			if ($this->config->get('config_related_categories_auto_enable')){
				$query = $this->db->query("SELECT DISTINCT(pab.product_id), GROUP_CONCAT(pab.also_bought_id SEPARATOR ',') as also_bought, p2c.category_id FROM product_also_bought pab LEFT JOIN product_to_category p2c ON (pab.product_id = p2c.product_id AND p2c.main_category = 1) WHERE NOT ISNULL(p2c.category_id) GROUP BY pab.product_id");

				foreach ($query->rows as $row){
				//	echoLine('[fillRelatedCategories] ' . $row['product_id']);
					if ($also_bought = explode(',', $row['also_bought'])){
						foreach ($also_bought as $also_bought_id){
							$this->db->query("INSERT IGNORE INTO category_related (category_id, related_category_id) SELECT '" . (int)$row['category_id'] . "', category_id FROM product_to_category WHERE product_id = '" . $also_bought_id . "' ORDER BY main_category DESC LIMIT 1");	

							$this->db->query("INSERT IGNORE INTO category_related (category_id, related_category_id) SELECT category_id, '" . (int)$row['category_id'] . "' FROM product_to_category WHERE product_id = '" . $also_bought_id . "' ORDER BY main_category DESC LIMIT 1");					
						}
					}
				}
			}

			return $this;
		}
		
		public function optimizeProductsDB(){
			echo 'Регулярные задачи обслуживания товарной базы' . PHP_EOL;			
			
			echo '>> Очистка несуществующих записей'  . PHP_EOL;
			$this->db->query("DELETE FROM product_description WHERE product_id NOT IN (SELECT product_id FROM product)");
			
			$this->db->query("DELETE FROM product_also_bought WHERE product_id NOT IN (SELECT product_id FROM product)");
			$this->db->query("DELETE FROM product_also_bought WHERE also_bought_id NOT IN (SELECT product_id FROM product)");

			$this->db->query("DELETE FROM product_also_viewed WHERE product_id NOT IN (SELECT product_id FROM product)");
			$this->db->query("DELETE FROM product_also_viewed WHERE also_viewed_id NOT IN (SELECT product_id FROM product)");

			$this->db->query("DELETE FROM category_related WHERE category_id NOT IN (SELECT category_id FROM category)");
			$this->db->query("DELETE FROM category_related WHERE related_category_id NOT IN (SELECT category_id FROM category)");

			echo '>> Удаление товаров у которых нет на амазоне офферов...' . PHP_EOL;
			if ($this->config->get('config_enable_amazon_specific_modes') && $this->config->get('config_rainforest_delete_no_offers') && $this->config->get('config_rainforest_delete_no_offers_counter')){
				$this->load->model('catalog/product');

				$query = $this->db->query("SELECT product_id FROM product WHERE amzn_no_offers = 1 AND amzn_no_offers_counter >= '" . (int)$this->config->get('config_rainforest_delete_no_offers_counter') . "' AND asin <> '' AND amzn_last_offers != '0000-00-00 00:00:00'");

				foreach ($query->rows as $row){
					echo '>> Удаляем товар ' . $row['product_id'] . PHP_EOL;
					$this->model_catalog_product->deleteProduct($row['product_id'], false, true);
				}
			}

			echo '>> Нормализация рейтинга товаров...' . PHP_EOL;
			$this->db->query("UPDATE product SET xrating = (SELECT AVG(rating) as xrating FROM review WHERE status = 1 AND product_id = product.product_id GROUP BY product_id)");
			$this->db->query("UPDATE product SET xreviews = (SELECT COUNT(*) as trating FROM review WHERE status = 1 AND product_id = product.product_id GROUP BY product_id)");

			echo '>> Выравнивание количества офферов...' . PHP_EOL;
			$this->db->query("UPDATE product SET product.amzn_offers_count = (SELECT COUNT(*) FROM product_amzn_offers WHERE product_amzn_offers.asin = product.asin)");
			$this->db->query("UPDATE product SET product.amzn_no_offers = 1 WHERE amzn_offers_count = 0 AND amzn_last_offers <> '0000-00-00 00:00:00'");
			$this->db->query("UPDATE product SET product.amzn_no_offers = 0 WHERE amzn_offers_count > 0 OR amzn_last_offers = '0000-00-00 00:00:00'");			
			
			echo '>> Подсчет количества продаж за неделю...'  . PHP_EOL;			
			$this->db->query("UPDATE product p SET bought_for_week = (SELECT SUM(quantity) FROM order_product op WHERE op.product_id = p.product_id AND op.order_id IN (SELECT o.order_id FROM `order` o WHERE o.order_status_id > 0 AND  DATE(o.date_added) >= DATE(DATE_SUB(NOW(),INTERVAL 7 DAY))))");
			echo PHP_EOL;			
			
			echo '>> Подсчет количества продаж за месяц...'  . PHP_EOL;			
			$this->db->query("UPDATE product p SET bought_for_month = (SELECT SUM(quantity) FROM order_product op WHERE op.product_id = p.product_id AND op.order_id IN (SELECT o.order_id FROM `order` o WHERE o.order_status_id > 0 AND DATE(o.date_added) >= DATE(DATE_SUB(NOW(),INTERVAL 30 DAY))))");
			echo PHP_EOL;
			
			echo '>> Обнуление количества товаров со статусом нет в наличии'  . PHP_EOL;
			$this->db->query("UPDATE product p SET quantity = 0, quantity_stock = 0, quantity_stockK = 0, quantity_stockM = 0, quantity_stockMN = 0, quantity_stockAS = 0, quantity_stock_onway = 0, quantity_stockK_onway = 0, quantity_stockM_onway = 0 WHERE stock_status_id IN (10,9)");
			
			echo '>> Исправление багов в описаниях'  . PHP_EOL;
			$this->db->query("UPDATE product_description SET description = REPLACE(description, '< /', '</');");
			//	$this->db->query("UPDATE product_description SET description = REPLACE(description, '< / strong>', '</strong>')");
			
			echo '>> Очистка несуществующих категорий...'  . PHP_EOL;
			$this->db->non_cached_query("DELETE FROM product_to_category WHERE category_id NOT IN (SELECT distinct category_id FROM category)");			

			echo '>> Коррекция маркеров амазона...'  . PHP_EOL;
			$this->db->non_cached_query("UPDATE product p SET fill_from_amazon = 1 WHERE filled_from_amazon = 1 AND (fill_from_amazon = 0 OR ISNULL(fill_from_amazon))");

			echo '>> Включение полностью полученных товаров в включенных категориях...'  . PHP_EOL;
			if ($this->config->get('config_enable_amazon_specific_modes')){
				$this->db->non_cached_query("UPDATE product SET status = 1 WHERE filled_from_amazon = 1 AND product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1))");
			}
			
			echo '>> Очистка неликвида...'  . PHP_EOL;
			$this->db->non_cached_query("UPDATE product p SET is_illiquid = 0 WHERE quantity_stock = 0 AND quantity_stockK = 0 AND quantity_stockM = 0");
			
			//Очистка нулевых переназначенных цен
			$this->db->non_cached_query("DELETE FROM product_special WHERE price = 0");
			$this->db->non_cached_query("DELETE FROM product_price_to_store WHERE price = 0");
			$this->db->non_cached_query("DELETE FROM product_price_national_to_store WHERE price = 0");
			$this->db->non_cached_query("DELETE FROM product_price_national_to_yam WHERE price = 0");		

			$this->fillSpecialCategory()->fillMarkDownCategory()->fillRelatedCategories();	
		}		
		
		public function dailyWork(){						
			echo 'Статистика по работе сотрудников...' . PHP_EOL;
			
			echo 'Инициализация записей на сегодня...' . PHP_EOL;
			$this->db->query("INSERT IGNORE INTO user_worktime (`user_id`, `date`) SELECT user_id, DATE(NOW()) FROM user WHERE count_worktime = 1 ");
			
			echo '>> Подсчет количества входящих звонков...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET inbound_call_count = (SELECT COUNT(DISTINCT cc.customer_call_id) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 1 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества исходящих звонков...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET outbound_call_count = (SELECT COUNT(DISTINCT cc.customer_call_id) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 0 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет длительности входящих звонков...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET inbound_call_duration = (SELECT SUM(cc.length) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 1 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет длительности исходящих звонков...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET outbound_call_duration = (SELECT SUM(cc.length) FROM customer_calls cc WHERE DATE(cc.date_end) = DATE(NOW()) AND cc.inbound = 0 AND cc.manager_id = uw.user_id GROUP BY DATE(cc.date_end)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества присвоенных заказов...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET owned_order_count = (SELECT COUNT(DISTINCT(order_id)) FROM `order` WHERE DATE(date_added) = DATE(NOW()) AND manager_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества модификаций заказов...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET edit_order_count = (SELECT COUNT(DISTINCT(order_id)) FROM `order_save_history` WHERE DATE(datetime) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(datetime)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет первого действия за день...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET worktime_start = (SELECT MIN(date) FROM `adminlog` WHERE DATE(date) = DATE(NOW()) AND user_id = uw.user_id) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет последнего действия за день...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET worktime_finish = (SELECT MAX(date) FROM `adminlog` WHERE DATE(date) = DATE(NOW()) AND user_id = uw.user_id) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества действий за день...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET daily_actions = (SELECT COUNT(DISTINCT log_id) FROM `adminlog` WHERE DATE(date) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества выполнений заказов за день...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET success_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества отмен заказов за день...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET cancel_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества обработанных заказов за день...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET treated_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id = '" . $this->config->get('config_treated_status_id') . "' AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества подтвержденных заказов за день...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET confirmed_order_count = (SELECT COUNT(DISTINCT order_history_id) FROM `order_history` WHERE order_status_id IN(" . implode(',',$this->config->get('config_manager_confirmed_order_status_id')) . ") AND DATE(date_added) = DATE(NOW()) AND user_id = uw.user_id GROUP BY DATE(date_added)) WHERE date = DATE(NOW())");
			echo PHP_EOL;
			
			echo '>> Подсчет количества проблемных заказов на момент времени...'  . PHP_EOL;
			echo ''. PHP_EOL;
			$this->db->query("UPDATE user_worktime uw SET problem_order_count = (SELECT COUNT(DISTINCT order_id) as count FROM `order` WHERE (order_status_id IN (" . implode(',', $this->config->get('config_problem_order_status_id')) . ") OR (probably_cancel=1 OR probably_close=1 OR probably_problem=1) AND order_status_id > '0') AND manager_id = uw.user_id) WHERE date = DATE(NOW())");
			echo PHP_EOL;
		}
				
		public function optimizeCustomerDB(){
			$this->load->model('setting/setting');
			$this->load->model('setting/store');

			echo 'Нормализуем поля...' . PHP_EOL;
			
			echo '>> Нормализация пустых имен...'  . PHP_EOL;
			echo 'QUERY:' .  "UPDATE customer SET firstname = email WHERE LENGTH(firstname)<=2 OR ISNULL(firstname)" . PHP_EOL;
			$this->db->query("UPDATE customer SET firstname = email WHERE LENGTH(firstname)<=2 OR ISNULL(firstname)");
			echo PHP_EOL;
			
			echo '>> Нормализация адресов...'  . PHP_EOL;
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
			
			
			echo '>> TRIM полей...'  . PHP_EOL;
			$this->db->query("UPDATE address SET address_1 = TRIM(address_1)");
			$this->db->query("UPDATE address SET address_2 = TRIM(address_2)");
			$this->db->query("UPDATE address SET city = TRIM(city)");
			$this->db->query("UPDATE address SET firstname = TRIM(firstname)");
			$this->db->query("UPDATE address SET lastname = TRIM(lastname)");
			$this->db->query("UPDATE customer SET firstname = TRIM(firstname)");
			$this->db->query("UPDATE customer SET lastname = TRIM(lastname)");
			echo PHP_EOL;
			
			echo '>> Ключ авторизации...'  . PHP_EOL;
			echo 'QUERY:' .  "UPDATE customer SET utoken = md5(md5(concat(email,email))) WHERE 1" . PHP_EOL;
			$this->db->query("UPDATE customer SET utoken = md5(md5(concat(email,email))) WHERE 1");
			echo PHP_EOL;
			
			
			echo '>> Убрать хреновую почту...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer SET email = telephone WHERE email = '" . $this->config->get('config_email') . "'" . PHP_EOL;
			$this->db->query("UPDATE customer SET email = telephone WHERE email = '" . $this->config->get('config_email') . "'");
			echo PHP_EOL;
			
			echo '>> Нормализация телефона...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer SET normalized_telephone = TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) WHERE LENGTH(telephone)>0" . PHP_EOL;
			$this->db->query("UPDATE customer SET normalized_telephone = TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) WHERE LENGTH(telephone)>0");
			echo PHP_EOL;
			
			echo '>> Нормализация телефона 2...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer SET normalized_fax = TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) WHERE LENGTH(fax)>0" . PHP_EOL;
			$this->db->query("UPDATE customer SET normalized_fax = TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) WHERE LENGTH(fax)>0");
			echo PHP_EOL;
			
			echo '>> Нормализация ДР...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE  customer SET birthday = '0000-00-00' WHERE birthday IN ('1970-01-01', '-0001-11-30')" . PHP_EOL;
			$this->db->query("UPDATE  customer SET birthday = '0000-00-00' WHERE birthday IN ('1970-01-01', '-0001-11-30')");
			echo PHP_EOL;
			
			echo '>> Пересчет даты рождения - день...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer SET birthday_date = DAY(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';" . PHP_EOL;
			$this->db->query("UPDATE customer SET birthday_date = DAY(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");
			echo PHP_EOL;
			
			echo '>> Пересчет даты рождения - месяц...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer SET birthday_month = MONTH(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';" . PHP_EOL;
			$this->db->query("UPDATE customer SET birthday_month = MONTH(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");
			echo PHP_EOL;
			
			echo '>> Проверка подписки на уведомления...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer SET has_push = 0 WHERE 1" . PHP_EOL;
			$this->db->query("UPDATE customer SET has_push = 0 WHERE 1");
			echo 'QUERY:' . "UPDATE customer SET has_push = 1 WHERE customer_id IN (SELECT DISTINCT customer_id FROM customer_push_ids WHERE 1)" . PHP_EOL;
			$this->db->query("UPDATE customer SET has_push = 1 WHERE customer_id IN (SELECT DISTINCT customer_id FROM customer_push_ids WHERE 1)");
			echo PHP_EOL;
			
			echo '>> Привязка товаров к покупателю...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE order_product op SET customer_id = (SELECT customer_id FROM `order` o WHERE op.order_id = o.order_id LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE order_product op SET customer_id = (SELECT customer_id FROM `order` o WHERE op.order_id = o.order_id LIMIT 1)");
			echo PHP_EOL;
			
			echo '>> Подсчет количества заказов...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET order_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET order_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0)");
			echo PHP_EOL;
			
			echo '>> Подсчет количества выполненных заказов...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET order_good_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')" . PHP_EOL;
			$this->db->query("UPDATE customer c SET order_good_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");
			echo PHP_EOL;
			
			echo '>> Подсчет количества отмененных заказов...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET order_bad_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_cancelled_status_id') . "')" . PHP_EOL;
			$this->db->query("UPDATE customer c SET order_bad_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_cancelled_status_id') . "')");
			echo PHP_EOL;
			
			echo '>> Подсчет даты последнего выполненного заказа...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET order_good_last_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added DESC LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET order_good_last_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added DESC LIMIT 1)");
			echo PHP_EOL;
			
			echo '>> Подсчет даты первого выполненного заказа...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET order_good_first_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added ASC LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET order_good_first_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added ASC LIMIT 1)");
			echo PHP_EOL;
			
			echo '>> Подсчет даты последнего заказа...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET order_last_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added DESC LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET order_last_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added DESC LIMIT 1)");
			echo PHP_EOL;
			
			echo '>> Подсчет даты первого заказа...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET order_first_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added ASC LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET order_first_date = (SELECT DATE(date_added) FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added ASC LIMIT 1)");
			echo PHP_EOL;
			
			echo '>> Выборка источника первого заказа...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET first_order_source = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(first_referrer, '/', 3), '://', -1), '/', 1), '?', 1) AS domain FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added ASC LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET first_order_source = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(first_referrer, '/', 3), '://', -1), '/', 1), '?', 1) AS domain FROM `order` WHERE customer_id = c.customer_id AND order_status_id > 0 ORDER BY date_added ASC LIMIT 1)");
			echo PHP_EOL;
			
			echo '>> Подсчет полной суммы...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET total_cheque = (SELECT SUM(total_national) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'  GROUP BY customer_id)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET total_cheque = (SELECT SUM(total_national) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'  GROUP BY customer_id)");
			echo PHP_EOL;
			
			echo '>> Подсчет полной суммы по товарам...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET total_product_cheque = (SELECT SUM(total_national) FROM `order_product` WHERE order_id IN (SELECT order_id FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'))" . PHP_EOL;
			$this->db->query("UPDATE customer c SET total_product_cheque = (SELECT SUM(total_national) FROM `order_product` WHERE order_id IN (SELECT order_id FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'))");
			echo PHP_EOL;
			
			/*	echo '>> Пересчет накопительной скидки...' . PHP_EOL;	
				$action = new Action('sale/customer/updateCumulativeDiscountPercent');
				if (isset($action)) {
				$controller->dispatch($action, new Action('error/not_found'));
				}
				echo PHP_EOL;
			*/
			
			echo '>> Подсчет среднего чека...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET avg_cheque = (SELECT AVG(total_national) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'  GROUP BY customer_id)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET avg_cheque = (SELECT AVG(total_national) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'  GROUP BY customer_id)");
			echo PHP_EOL;
			
			echo '>> Подсчет количества звонков...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET total_calls = (SELECT COUNT(customer_call_id) FROM `customer_calls` WHERE customer_id = c.customer_id)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET total_calls = (SELECT COUNT(customer_call_id) FROM `customer_calls` WHERE customer_id = c.customer_id)");
			echo PHP_EOL;
			
			echo '>> Подсчет средней длины звонка...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET avg_calls_duration = (SELECT AVG(length) FROM `customer_calls` WHERE customer_id = c.customer_id AND length > 0 GROUP BY customer_id)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET avg_calls_duration = (SELECT AVG(length) FROM `customer_calls` WHERE customer_id = c.customer_id AND length > 0 GROUP BY customer_id)");
			echo PHP_EOL;
			
			echo '>> Копирование страны...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET country_id = (SELECT country_id FROM `address` WHERE address_id = c.address_id LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET country_id = (SELECT country_id FROM `address` WHERE address_id = c.address_id LIMIT 1)");
			echo PHP_EOL;
			
			
			echo '>> Перестройка стран...' . PHP_EOL;
			foreach ($this->model_setting_store->getStores() as $store){
				$sql = "UPDATE customer c SET country_id = '" . (int)$this->model_setting_setting->getKeyValue('config_country_id', $store['store_id']) . "' WHERE store_id = '" . $store['store_id'] . "'";
				echoLine('[optimizeCustomerDB] ' . $sql);
				$this->db->query($sql);

				$sql = "UPDATE address SET country_id = '" . (int)$this->model_setting_setting->getKeyValue('config_country_id', $store['store_id']) . "' WHERE customer_id IN (SELECT customer_id FROM customer WHERE store_id = '" . (int)$store['store_id'] . "') AND country_id = 0";

				echoLine('[optimizeCustomerDB] ' . $sql);
				$this->db->query($sql);
			}			
			
			echo '>> Копирование города...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET city = (SELECT city FROM `address` a WHERE a.address_id = c.address_id LIMIT 1)" . PHP_EOL;
			$this->db->query("UPDATE customer c SET city = (SELECT city FROM `address` a WHERE a.address_id = c.address_id LIMIT 1)");
			echo PHP_EOL;
			
			echo '>> Подбор геозон...' . PHP_EOL;
			echo '>>> Москва' . PHP_EOL;
			echo 'QUERY:' . "UPDATE address SET zone_id = 39 WHERE city LIKE 'Москва'" . PHP_EOL;
			$this->db->query("UPDATE address SET zone_id = 39 WHERE city LIKE 'Москва'");
			
			echo '>>> Петербург' . PHP_EOL;
			echo 'QUERY:' . "UPDATE address SET zone_id = 80 WHERE city LIKE 'Санкт-Петербург'" . PHP_EOL;
			$this->db->query("UPDATE address SET zone_id = 80 WHERE city LIKE 'Санкт-Петербург'");
			echo PHP_EOL;
			
			echo '>> Обновление CSI клиентов...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE customer c SET c.csi_average =( SELECT AVG(o.csi_average) FROM `order` o WHERE o.customer_id = c.customer_id AND o.csi_average > 0 AND o.csi_reject = 0 GROUP BY customer_id )" . PHP_EOL;
			$this->db->query("UPDATE customer c SET c.csi_average = ( SELECT AVG(o.csi_average) FROM `order` o WHERE o.customer_id = c.customer_id AND o.csi_average > 0 AND o.csi_reject = 0 GROUP BY o.customer_id )");
			
			
			echo '>> Обновление динамики сегментации...' . PHP_EOL;
			echo 'QUERY:' . "INSERT INTO segments_dynamics (segment_id, customer_count, total_cheque, avg_cheque, order_good_count, order_bad_count, order_good_to_bad, avg_csi, date_added) (SELECT segment_id, customer_count, total_cheque, avg_cheque, order_good_count, order_bad_count, order_good_to_bad, avg_csi, NOW() FROM segments WHERE enabled = 1)" . PHP_EOL;
			$this->db->query("INSERT INTO segments_dynamics (segment_id, customer_count, total_cheque, avg_cheque, order_good_count, order_bad_count, order_good_to_bad, avg_csi, date_added) (SELECT segment_id, customer_count, total_cheque, avg_cheque, order_good_count, order_bad_count, order_good_to_bad, avg_csi, NOW() FROM segments WHERE enabled = 1)");
			echo PHP_EOL;
			
			echo '>> Обновление количества клиентов в сегменте...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE segments s SET customer_count = (SELECT COUNT(*) FROM customer_segments cs WHERE cs.segment_id = s.segment_id)" . PHP_EOL;
			$this->db->query("UPDATE segments s SET customer_count = (SELECT COUNT(*) FROM customer_segments cs WHERE cs.segment_id = s.segment_id)");
			echo PHP_EOL;
			
			echo '>> Обновление чека сегмента...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE segments s SET total_cheque =( SELECT SUM(total) AS total FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')" . PHP_EOL;
			$this->db->query("UPDATE segments s SET total_cheque =( SELECT SUM(total) AS total FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");
			echo PHP_EOL;
			
			echo '>> Обновление среднего чека сегмента...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE segments s SET avg_cheque =( SELECT AVG(total) AS total FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')" . PHP_EOL;
			$this->db->query("UPDATE segments s SET avg_cheque =( SELECT AVG(total) AS total FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");
			echo PHP_EOL;
			
			echo '>> Обновление количества выполненных заказов в сегментах...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE segments s SET order_good_count = (SELECT COUNT(DISTINCT order_id) FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')" . PHP_EOL;
			$this->db->query("UPDATE segments s SET order_good_count = (SELECT COUNT(DISTINCT order_id) FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");
			echo PHP_EOL;
			
			echo '>> Обновление количества отмененных заказов в сегментах...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE segments s SET order_bad_count = (SELECT COUNT(DISTINCT order_id) FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_cancelled_status_id') . "')" . PHP_EOL;
			$this->db->query("UPDATE segments s SET order_bad_count = (SELECT COUNT(DISTINCT order_id) FROM `order` WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND order_status_id = '" . $this->config->get('config_cancelled_status_id') . "')");
			
			echo '>> Обновление CSI клиентов...' . PHP_EOL;
			echo 'QUERY:' . "UPDATE segments s SET avg_csi = (SELECT AVG(csi_average) FROM customer WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND csi_average > 0)" . PHP_EOL;
			$this->db->query("UPDATE segments s SET avg_csi = (SELECT AVG(csi_average) FROM customer WHERE customer_id IN( SELECT customer_id FROM customer_segments WHERE segment_id = s.segment_id ) AND csi_average > 0)");
			
			$this->db->query("UPDATE segments s SET order_good_to_bad = (order_bad_count/order_good_count)*100");
			echo PHP_EOL;			
		}
		
		
		public function optimizeDB(){
			
			echo '[C] Чистим записи журнала событий. 2 месяца.'  . PHP_EOL;
			$this->db->query("DELETE FROM adminlog WHERE DATE(`date`) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)");
			$this->db->query("DELETE FROM `short_url_alias` WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
			
			
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
			
			echo '[C] Чистим записи чеков. 3 месяцев.'  . PHP_EOL;
			$this->db->query("DELETE FROM order_invoice_history WHERE DATE(datetime) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
			$this->db->query("DELETE FROM order_invoice_history WHERE order_id NOT IN (SELECT DISTINCT order_id FROM `order`)");
			
			echo '[C] Чистим записи сохранения выполненных заказов. 2-3 месяца.'  . PHP_EOL;
			$this->db->query("DELETE FROM order_save_history WHERE DATE(datetime) <= DATE_SUB(CURDATE(), INTERVAL 2 MONTH) AND order_id IN (SELECT DISTINCT order_id FROM `order` WHERE order_status_id IN (17, 18, 23))");
			$this->db->query("DELETE FROM order_save_history WHERE DATE(datetime) <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND order_id NOT IN (SELECT DISTINCT order_id FROM `order` WHERE order_status_id IN (17, 18, 23))");
			
			echo '[C] Динамика сегментов. Год'  . PHP_EOL;
			$this->db->query("DELETE FROM segments_dynamics WHERE DATE(date_added) <= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)");
			
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
			
			
			echo '[O] Оптимизация таблиц...'  . PHP_EOL;
			echo '[OТ] Оптимизация таблицы adminlog...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table adminlog");
			
			echo '[OТ] Оптимизация таблицы superstat_viewed...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table superstat_viewed");
			
			echo '[OТ] Оптимизация таблицы emailtemplate_logs...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table emailtemplate_logs");
			
			echo '[OТ] Оптимизация таблицы customer...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table customer");
			
			echo '[OТ] Оптимизация таблицы address...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table address");
			
			echo '[OТ] Оптимизация таблицы customer_history...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table customer_history");
			
			echo '[OТ] Оптимизация таблицы order_invoice_history...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table order_invoice_history");
			
			echo '[OТ] Оптимизация таблицы order_save_history...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table order_save_history");
			
			echo '[OТ] Оптимизация таблицы order...'  . PHP_EOL;
			$this->db->query("OPTIMIZE table `order`");					
		}

	}	