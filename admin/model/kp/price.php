<?
	class ModelKpPrice extends Model {
		
		public function guessPrice($price){
			
			return number_format($price * $this->getCoefficient($price), 2, '.', ' ');
			
		}
		
		public function guessPriceByProductId($product_id){
			
			
			
			
			
		}
		
		
		public function getProductResultPriceByStore($product_id, $store_id)
        {
			
			$this->load->model('setting/setting');
			$this->load->model('localisation/currency');
            $customer_group_id = $this->config->get('config_customer_group_id');
			$config_regional_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$store_id);		
			$regional_currency = $this->model_localisation_currency->getCurrencyByCode($config_regional_currency);
			
			$percent = false;
			if ($regional_currency['plus_percent']){
				if ($regional_currency['plus_percent'][0] == '+' || $regional_currency['plus_percent'][0] == 'p'){
					$plus = true;
					} elseif ($regional_currency['plus_percent'][0] == '-' || $regional_currency['plus_percent'][0] == 'm') {
					$plus = false;
					} else {
					$plus = true;
				}
				$percent = (int)preg_replace("/[^0-9]/", '', $regional_currency['plus_percent']);
			}
			
			$sql = "SELECT p.*,
			(SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
			(SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND (ps.store_id = '" . (int)$store_id . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special,
			(SELECT date_end FROM product_special ps WHERE ps.product_id = p.product_id AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND (ps.store_id = '" . (int)$store_id . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special_date_end,
			(SELECT currency_scode FROM product_special ps WHERE ps.product_id = p.product_id AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND (ps.store_id = '" . (int)$store_id . "' OR ps.store_id = -1) ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special_currency,
			(SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND pp2s.store_id = '" . (int)$store_id . "' LIMIT 1) as store_overload_price,
			(SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND ppn2s.store_id = '" . (int)$store_id . "' LIMIT 1) as store_overload_price_national						
			FROM product p 		
			WHERE p.product_id = '" . (int)$product_id . "'";		
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				$price = $query->row['price'];
				
				//Перезагрузка цены в евро
				if (isset($query->row['store_overload_price']) && (int)$query->row['store_overload_price']) {
					$price = $query->row['store_overload_price'];
				}		
				
				//Перезагрузка цены в национальной валюте, которая фиксируется и не поддается оверлоаду	/ РРЦ		
				$price_national = false;
				$price_national_currency = false;
				if (isset($query->row['store_overload_price_national']) && $query->row['store_overload_price_national']) {
					$price_national = $query->row['store_overload_price_national'];
					$price_national_currency = $config_regional_currency;
				}							
				
				//Единая цена в какой-то валюте
				$price_national_currency = false;
				if ((int)$query->row['price_national']) {
					$price_national = $query->row['price_national'];
					$price_national_currency = $query->row['currency'];
				}
				
				//Скидочная цена
				$special = false;
				if ((int)$query->row['special']){					
					//Если задана скидка, то по дефолту она в евро
					$special = $this->currency->convert($query->row['special'], $this->config->get('config_currency'), $config_regional_currency, false, false);
					
					if (isset($query->row['special_currency']) && $query->row['special_currency']) {
						if ($query->row['special_currency'] == $config_regional_currency){						
							$special = $query->row['special'];
							} else {
							$special  = $this->currency->convert($query->row['special'], $query->row['special_currency'], $this->config->get('config_currency'), false, false);	
						}
					}	
				}
				
			
				
				if ((int)$price_national){					
					if ($price_national_currency != $config_regional_currency){					
						$price = $this->currency->convert($price_national, $price_national_currency, $config_regional_currency, false, false);
						} else {
						$price = $price_national;
					}
					} else {
					//Наценка на валюту
					if ($percent){
						if ($plus){
							$price = $price + ($price / 100 * $percent);
							} else {
							$price = $price - ($price / 100 * $percent);
						}
					}
					
					$price = $this->currency->convert($price, $this->config->get('config_currency'), $config_regional_currency, false, false);
				}
				
				if ($price <= $special) {
					$special = false;
				}
				
				$price_data = array(	
				'currency'                 => $config_regional_currency,
				'price'                    => $price,			
				'special'                  => $special,
				);
				
				
				} else {
				$price_data = false;
				}			
				
				return $price_data;
			}
			
			public function guessCostByPrice($price, $do_return_coef = false, $bb = false){
				$this->load->model('setting/setting');
				$coefficients = $this->model_setting_setting->getKeySettingValue('config', 'config_overprice', 0);
				
				$return_coef = $return_cost = 0;
				
				foreach (explode(';', $coefficients) as $coef) {
					$coef = explode(':', $coef);
					
					if ($coef[0] <= ($price / $coef[1])){
						$return_coef = $coef[1];
						$return_cost = $price / $coef[1];			
					}
					
				}
				
				if ($do_return_coef) {
					return $return_coef;
					} else {
					return $return_cost;
				}
				
			}
			
			public function guessCoefficient($price){
				$this->load->model('setting/setting');
				$coefficients = $this->model_setting_setting->getKeySettingValue('config', 'config_overprice', 0);
				
				foreach (explode(';', $coefficients) as $coef) {
					$coef = explode(':', $coef);	
					
					if ($coef[0] <= ($price / $coef[1])){
						$return_coef = $coef[1];
					}			
				}
				
				return $return_coef;
			}
			
			public function getCoefficient($cost){
				$this->load->model('setting/setting');
				$coefficients = $this->model_setting_setting->getKeySettingValue('config', 'config_overprice', 0);
				
				$return_coef = 0;
				foreach (explode(';', $coefficients) as $coef){
					$coef = explode(':', $coef);	
					
					if ($coef[0] <= $cost){
						$return_coef = $coef[1];
					}			
				}			
				return $return_coef;
			}
			
			public function getProductMinPrice($product_id){
				$this->load->model('catalog/product');
				
				$price = $this->model_catalog_product->getProductPrice($product_id);
				$special = $this->model_catalog_product->getProductSpecialOne($product_id);
				
				return array(
				'min_price' => min($price, $special),
				'price' => $price,
				'special' => $special,
				);
			}
			
			/**
				* @param $product_id
				*
				* @return array
			*/
			public function getProductPriceFromSources ($product_id) {
				return;
			}
			
		}							