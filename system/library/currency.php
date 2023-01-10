<?php
	class Currency {
		private $code 		= null;
		private $config 	= null;
		private $db 		= null;
		private $language 	= null;
		private $request 	= null;
		private $session 	= null;
		private $cache 		= null;
		private $log 		= null;
		public 	$percent 	= 0;
		public 	$plus 		= true;
		public 	$currencies = [];
		
		public function __construct($registry) {
			$this->config 	= $registry->get('config');
			$this->db     	= $registry->get('db');
			$this->language = $registry->get('language');
			$this->request 	= $registry->get('request');
			$this->session 	= $registry->get('session');
			$this->cache 	= $registry->get('cache');
			$this->log 		= $registry->get('log');
			
			if (!$this->currencies = $this->cache->get('currencies')){
				$query = $this->db->query("SELECT * FROM currency WHERE 1");
				
				$this->currencies = [];
				foreach ($query->rows as $result) {				
					$this->currencies[$result['code']] = [
						'currency_id'   	=> $result['currency_id'],
						'title'         	=> $result['title'],
						'code'          	=> $result['code'],
						'symbol_left'   	=> $result['symbol_left'],
						'symbol_right'  	=> $result['symbol_right'],
						'decimal_place' 	=> $result['decimal_place'],
						'cryptopair' 		=> $result['cryptopair'],	
						'value'         	=> $result['value'],
						'value_real'    	=> $result['value_real'],
						'value_uah_unreal' 	=> $result['value_uah_unreal'],
						'plus_percent' 		=> $result['plus_percent'],
					];
				}

				$this->cache->set('currencies', $this->currencies);
			}
			
			$this->set($this->config->get('config_regional_currency'));						
		}

		/*
			This function returns all currencies
		*/
		public function getCurrencies(){
			return $this->currencies;
		}

		/*
			This function implements stock logic, where currency is detected from request
		*/
		public function setCurrencyFromRequest(){
			if (isset($this->request->get['currency']) && (array_key_exists($this->request->get['currency'], $this->currencies))) {
				$this->set($this->request->get['currency']);
			} elseif ((isset($this->session->data['currency'])) && (array_key_exists($this->session->data['currency'], $this->currencies))) {
				$this->set($this->session->data['currency']);
			} elseif ((isset($this->request->cookie['currency'])) && (array_key_exists($this->request->cookie['currency'], $this->currencies))) {
				$this->set($this->request->cookie['currency']);		
			} else {
				$this->set($this->config->get('config_regional_currency'));
			}			
		}
		
		public function set($currency) {
			$this->code = $currency;
			
			if (!isset($this->session->data['currency']) || ($this->session->data['currency'] != $currency)) {
				$this->session->data['currency'] = $currency;
			}
			
			if ($this->currencies[$currency]['plus_percent']){
				if ($this->currencies[$currency]['plus_percent'][0] == '+' || $this->currencies[$currency]['plus_percent'][0] == 'p'){
					$this->plus = true;
					} elseif ($this->currencies[$currency]['plus_percent'][0] == '-' || $this->currencies[$currency]['plus_percent'][0] == 'm') {
					$this->plus = false;
					} else {
					$this->plus = true;
				}
				
				$this->percent = (int)(trim($this->currencies[$currency]['plus_percent']));
			}
			
			if(php_sapi_name()!=="cli"){	
				if (!isset($this->request->cookie['currency']) || ($this->request->cookie['currency'] != $currency)) {
					setcookie('currency', $currency, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
				}
			}
		}				
				
		public function format_with_left($number, $currency = '', $value = '') {
			return $this->format($number, $currency, $value, $format = true, $real = false, $real_code = false, true);		
		}
		
		public function format($number, $currency = '', $value = '', $format = true, $real = false, $real_code = false, $left_explicit = false) {
			if ($currency && $this->has($currency)) {
				$symbol_left   = $this->currencies[$currency]['symbol_left'];
				$symbol_right  = $this->currencies[$currency]['symbol_right'];
				$decimal_place = $this->currencies[$currency]['decimal_place'];
				} else {
				$symbol_left   = $this->currencies[$this->code]['symbol_left'];
				$symbol_right  = $this->currencies[$this->code]['symbol_right'];
				$decimal_place = $this->currencies[$this->code]['decimal_place'];
				
				$currency = $this->code;
			}
			
			if ($value) {
				$value = $value;
				} else {
				if ($real){
					$value = $this->currencies[$currency]['value_real'];
					} else {
					$value = $this->currencies[$currency]['value'];
				}
			}
			
			if (defined('THIS_IS_CATALOG') || $left_explicit){
				$symbol_right = $symbol_left;
			}
			
			if ($value) {
				$value = (float)$number * $value;
				} else {
				$value = $number;
			}
			
			$string = '';
			
			if (($symbol_left) && ($format)) {
				//$string .= $symbol_left;
			}
			
			if ($format) {
				$decimal_point = $this->language->get('decimal_point');
				} else {
				$decimal_point = '.';
			}
			
			if ($format) {
				$thousand_point = $this->language->get('thousand_point');
				} else {
				$thousand_point = '';
			}
			
			$string .= number_format(round($value, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);
			
			if (!$real_code) {
				
				if (($symbol_right) && ($format)) {
					$string .= ' ' . $symbol_right;
				}
				
				} else {
				
				if (($this->code) && ($format)) {
					$string .= $this->code;
				}
				
			}
			
			return $string;
		}
		
		public function reconvert($value, $cource){
			
			if ($cource == 0){
				return $value;			
			}
			
			return round($value / $cource);			
		}
		
		public function convert($value, $from, $to, $real = false, $round = true) {
			
			$_from = $from;
			$_to = $to;
			
			if (isset($this->currencies[$from])) {
				if ($real) {
					$from = $this->currencies[$from]['value_real']?$this->currencies[$from]['value_real']:$this->currencies[$from]['value'];
					} else {
					$from = $this->currencies[$from]['value'];
				}
				} else {
				$from = 0;
			}
			
			if (isset($this->currencies[$to])) {
				if ($real) {
					$to = $this->currencies[$to]['value_real']?$this->currencies[$to]['value_real']:$this->currencies[$to]['value'];
					} else {
					$to = $this->currencies[$to]['value'];
				}
				} else {
				$to = 0;
			}
			
			
			if ($round) {	
				
				if ($from == 0){
					$result = round($value);
					} else {		
					if (is_numeric($value)){
						$result = round(($value * ($to / $from)));			
						} else {
						$result = round((int)$value);
					}
				}
				} else {
				if ($from == 0){
					$result = round($value);
					} else {				
					$result = $value * ($to / $from);	
				}
			}
			/*	if ($result < ($value * ($to / $from))){ $result += 1; 	} */
			
			//$result = $value * ($to / $from);
			//OVERLOAD FOR EUR
			if ($_from == 'EUR' && $_to == 'EUR'){
				$result = $value * ($to / $from);
			}
			
			
			return $result;
		}
		
		public function real_convert($value, $from, $to, $real = false){
			if (isset($this->currencies[$from])) {
				if ($real) {
					$from = $this->currencies[$from]['value_real'];
					} else {
					$from = $this->currencies[$from]['value'];
				}
				} else {
				$from = 0;
			}
			
			if (isset($this->currencies[$to])) {
				if ($real) {
					$to = $this->currencies[$to]['value_real'];
					} else {
					$to = $this->currencies[$to]['value'];
				}
				} else {
				$to = 0;
			}
			
			if ($from == 0){
				$result = $value;
				} else {
				$result = $value * ($to / $from);
			}
			/*	if ($result < ($value * ($to / $from))){ $result += 1; 	} */
			
			return $result;			
		}
		
		public function makeDiscountOnNumber($value, $percent){			
			return ($value - $value / 100 * $percent);			
		}
		
		public function shit_convert_to_uah($value, $from, $to = 'UAH'){
			if (isset($this->currencies[$from])) {
				$from = $this->currencies[$from]['value_uah_unreal'];
				} else {
				$from = 0;
			}
			
			$result = $value * $from;			
			
			return $result;			
		}
		
		public function getId($currency = '') {
			if (!$currency) {
				return $this->currencies[$this->code]['currency_id'];
				} elseif ($currency && isset($this->currencies[$currency])) {
				return $this->currencies[$currency]['currency_id'];
				} else {
				return 0;
			}
		}
		
		public function getUAHValueUnreal($currency = '') {
			if (!$currency) {
				return $this->currencies[$this->code]['value_uah_unreal'];
				} elseif ($currency && isset($this->currencies[$currency])) {
				return $this->currencies[$currency]['value_uah_unreal'];
				} else {
				return 0;
			}
		}
		
		public function getSymbolLeft($currency = '') {
			if (!$currency) {
				return $this->currencies[$this->code]['symbol_left'];
				} elseif ($currency && isset($this->currencies[$currency])) {
				return $this->currencies[$currency]['symbol_left'];
				} else {
				return '';
			}
		}
		
		public function getSymbolRight($currency = '') {
			if (!$currency) {
				return $this->currencies[$this->code]['symbol_right'];
				} elseif ($currency && isset($this->currencies[$currency])) {
				return $this->currencies[$currency]['symbol_right'];
				} else {
				return '';
			}
		}
		
		public function getDecimalPlace($currency = '') {
			if (!$currency) {
				return $this->currencies[$this->code]['decimal_place'];
				} elseif ($currency && isset($this->currencies[$currency])) {
				return $this->currencies[$currency]['decimal_place'];
				} else {
				return 0;
			}
		}
		
		public function getCode() {
			return $this->code;
		}
		
		public function getValue($currency = '') {
			if (!$currency) {
				return $this->currencies[$this->code]['value'];
				} elseif ($currency && isset($this->currencies[$currency])) {
				return $this->currencies[$currency]['value'];
				} else {
				return 0;
			}
		}
		
		public function has($currency) {
			return isset($this->currencies[$currency]);
		}
		
		public function formatNegativeBonus($points, $strong = false) {
			
			if ((int)$points < 0){
				
				if ($strong){
					$points_txt = '<b>' . (int)$points . '</b>';
					} else {
					$points_txt = $points;
				}
				
				if ($this->config->get('config_language_id') == 6){									
					return $points_txt . ' ' . getUkrainianPluralWord($points, $this->language->get('text_bonus_pluralized'));			
					} else {
					return $points_txt . ' ' . morphos\Russian\NounPluralization::pluralize($points, $this->language->get('text_bonus'));
				}
				} else {
				
				return false;
			}			
		}
		
		public function formatBonus($points, $strong = false) {
			
			if ((int)$points){
				
				if ($strong){
					$points_txt = '<b>+' . $points . '</b>';
					} else {
					$points_txt = $points;
				}
				
				if ($this->config->get('config_language_id') == 6){									
					return $points_txt . ' ' . getUkrainianPluralWord($points, $this->language->get('text_bonus_pluralized'));			
					} else {
					return $points_txt . ' ' . morphos\Russian\NounPluralization::pluralize($points, $this->language->get('text_bonus'));
				}
				} else {
				
				return false;
			}			
		}
		
		public function formatPoints($points, $format = true) {
			$decimal_point  = ($this->language->get('decimal_point'))  ? $this->language->get('decimal_point') : '.';
			$thousand_point = ($this->language->get('thousand_point')) ? $this->language->get('thousand_point') : '';
			$decimal_place  = $this->currencies[$this->code]['decimal_place'];
			$return_string  = '';
			
			if ($format) {
				$symbol_right  = $this->config->get('rewardpoints_currency_suffix');
				
				if ($this->config->get('rewardpoints_currency_prefix')) {
					$return_string .= $this->config->get('rewardpoints_currency_prefix');
				}
				
				if ($this->config->get('rewardpoints_currency_mode')) {
					$return_string .= number_format(round($points, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);
					} else {
					$return_string .= (int)$points;
				}
				
				if ($this->config->get('rewardpoints_currency_suffix')) {
					$return_string .= $this->config->get('rewardpoints_currency_suffix');
				}
				
				} else {
				if ($this->config->get('rewardpoints_currency_mode')) {
					$return_string .= number_format(round($points, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);
					} else {
					$return_string .= (int)$points;
				}
			}
			return $return_string;
		}


		/*
			This function updates cryptopairs rates, if they are set
		*/
		public function updatecryptopairs(){
			foreach ($this->currencies as $code => $currency){
				if ($currency['cryptopair']){
					$json = json_decode(file_get_contents('https://api.binance.com/api/v3/avgPrice?symbol=' . $currency['cryptopair']), true);

					if (!empty($json['price']) && is_numeric($json['price'])){
						echoLine('[Currency::updatecryptopairs] ' . $currency['cryptopair'] . ': ' . $json['price']);
						if (strpos($currency['cryptopair'], $currency['code']) === 0){							
							$json['price'] = 1/$json['price'];
						}

						$this->db->query("UPDATE currency SET cryptopair_value = '" . (float)$json['price'] . "' WHERE code = '" . $this->db->escape($currency['code']) . "'");
					}
				}
			}
		}

		public function recalculate($currency){












		}

	}				