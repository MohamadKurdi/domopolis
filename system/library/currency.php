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
					$this->currencies[$result['code']] = $result;

					if ($result['plus_percent'][0] == '+' || $result['plus_percent'][0] == 'p'){
						$result['plus_percent'] = '+' . trim($result['plus_percent'], '-+pm');
					} elseif ($result['plus_percent'][0] == '-' || $result['plus_percent'][0] == 'm') {
						$result['plus_percent'] = '-' . trim($result['plus_percent'], '-+pm');
					} else {
						$result['plus_percent'] = '+' . trim($result['plus_percent'], '-+pm');
					}
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
			This function returns currency
		*/
		public function getCurrency($code){
			if (!empty($this->currencies[$code])){
				return $this->currencies[$code];
			}

			return false;
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
		
		public function format($number, $currency = '', $value = '', $format = true, $real = false, $real_code = false, $left_explicit = false, $decimal_overload = false) {
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

			if ($decimal_overload){
				$decimal_place = $decimal_overload;
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

			if (!$format){
				return (float)$string;
			}
			
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
			This function updates any field of currency
		*/
		public function updateCurrencyField($code, $field, $value){
			$this->db->query("UPDATE currency SET `" . $this->db->escape($field) . "` = '" . (float)$value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($code) . "'");
		}

		/*
			This function updates cryptopairs rates, if they are set
		*/
		public function updateCryptoPairs(){
			foreach ($this->currencies as $code => $currency){
				if ($currency['cryptopair']){
					$json = json_decode(file_get_contents('https://api.binance.com/api/v3/avgPrice?symbol=' . $currency['cryptopair']), true);

					if (!empty($json['price']) && is_numeric($json['price'])){
						echoLine('[Currency::updatecryptopairs] ' . $currency['cryptopair'] . ': ' . $json['price']);
						if (strpos($currency['cryptopair'], $currency['code']) === 0){							
							$json['price'] = 1/$json['price'];
						}

						$this->updateCurrencyField($currency['code'], 'cryptopair_value', $json['price']);						
					}
				}
			}
		}

		/*
			This function gets Fixer.Io rates
		*/
		public function getFixerIoRates(){
			echoLine('[Currency::getFixerIoRates]' . ' starting');

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://data.fixer.io/api/latest?access_key='. $this->config->get('config_fixer_io_token') .'&base=' . $this->config->get('config_currency') . '&symbols=' . implode(',', array_keys($this->currencies)));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$content = curl_exec($curl);
			curl_close($curl);
			
			$json = json_decode($content, true);	
			
			if ($json && isset($json['success']) && $json['success']) {
				echoLine('[Currency::getFixerIoRates]' . ' got rates');
				foreach ($json['rates'] as $code => $rate){
					echoLine('[Currency::getFixerIoRates] Rate for ' . $this->config->get('config_currency') . '/' . $code . ' = ' . $rate);
				}

				return $json['rates'];
			}

			return false;
		}

		/*
			This function gets KhalikBank rates
		*/
		public function getHalykbankRates(){
			echoLine('[Currency::getHalykbankRates]' . ' starting');

			try{
				$httpClient = new \GuzzleHttp\Client();
				$httpResponce = $httpClient->request('GET', "https://back.halykbank.kz/common/currency-history", ['timeout' => 30]);	
				$json = json_decode($httpResponce->getBody(), true);

				if ($json && !empty($json['data']['currencyHistory'][0]['cards']['EUR/KZT']['sell'])) {
					echoLine('[Currency::getHalykbankRates] Real EUR/KZT rate:' . number_format($json['data']['currencyHistory'][0]['cards']['EUR/KZT']['sell'], 2));
					return number_format($json['data']['currencyHistory'][0]['cards']['EUR/KZT']['sell'], 2);
				}
			} catch (\GuzzleHttp\Exception\ConnectException $e){
				echoLine('[Currency::getHalykbankRates] Failed to get rate' . $e->getMessage());
				return false;
			}

			echoLine('[Currency::getHalykbankRates] Failed to get rate');
			return false;
		}


		/*
			This function gets PrivatBank rates
		*/
		public function getPrivatbankRates(){
			echoLine('[Currency::getPrivatbankRates]' . ' starting');

			try{
				$httpClient = new \GuzzleHttp\Client();
				$httpResponce = $httpClient->request('GET', "https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11", ['timeout' => 30]);	
				$json = json_decode($httpResponce->getBody(), true);			

				if (is_array($json)){
					foreach ($json as $line){
						if ($line['base_ccy'] == 'UAH' && $line['ccy'] == 'EUR'){
							echoLine('[Currency::getPrivatbankRates] Real EUR/UAH rate:' . number_format($line['sale'], 2));						
							return number_format($line['sale'], 2);
						}
					}
				}

			} catch (\GuzzleHttp\Exception\ConnectException $e){
				echoLine('[Currency::getPrivatbankRates] Failed to get rate' . $e->getMessage());
				return false;
			}

			echoLine('[Currency::getPrivatbankRates] Failed to get rate');
			return false;
		}

		/*
			This function gets AlfaBank.by rates
		*/
		public function getAlfabankByRates(){
			echoLine('[Currency::getAlfabankByRates]' . ' starting');

			try{
				$httpClient = new \GuzzleHttp\Client();
				$httpResponce = $httpClient->request('GET', "https://developerhub.alfabank.by:8273/partner/1.0.1/public/rates", ['timeout' => 30]);	
				$json = json_decode($httpResponce->getBody(), true);	

				if (is_array($json)){
					foreach ($json['rates'] as $line){
						if ($line['sellIso'] == 'EUR' && $line['buyIso'] == 'BYN'){
							echoLine('[Currency::getAlfabankByRates] Real EUR/BYN rate:' . number_format($line['buyRate'], 2));						
							return number_format($line['buyRate'], 2);
						}
					}
				}

			} catch (\GuzzleHttp\Exception\ConnectException $e){
				echoLine('[Currency::getAlfabankByRates] Failed to get rate' . $e->getMessage());
				return false;
			}

			echoLine('[Currency::getAlfabankByRates] Failed to get rate');
			return false;
		}

		/*
			This function gets RSB rates
		*/
		public function getRsbRates(){
			echoLine('[Currency::getRsbRates]' . ' starting');

			try{
				$httpClient = new \GuzzleHttp\Client();
				$httpResponce = $httpClient->request('GET', 'https://www.rsb.ru/local/ajax/getcoursemass1.php?callback=test2&'.date('d.m.Y').'&course_type=cards&currency=eur&_=1596197030' . mt_rand(100,999), ['timeout' => 30, 'curl' => [CURLOPT_HTTPHEADER => ['Host: www.rsb.ru', 'Referer: https://www.rsb.ru/courses/'], CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0']]);	
				$json = json_decode(str_replace(array('test2(', ');'), '',$httpResponce->getBody()), true);

				if (is_array($json) && !empty($json['currentItem']['sell'])){
					$value = str_replace(',', '.',$json['currentItem']['sell']);

					echoLine('[Currency::getRsbRates] Real EUR/RUB rate:' . number_format($value, 2));						
					return number_format($value, 2);
				}

			} catch (\GuzzleHttp\Exception\ConnectException $e){
				echoLine('[Currency::getRsbRates] Failed to get rate' . $e->getMessage());
				return false;
			}

			echoLine('[Currency::getRsbRates] Failed to get rate');	
			return false;
		}

		public function recalculate($code, $value_real){			

			if ($currency = $this->getCurrency($code)){
				$currency['value'] 			= (float)$currency['value'];
				$currency['old_value'] 		= (float)$currency['old_value'];
				$currency['old_value_real'] = (float)$currency['old_value_real'];
				$currency['value_minimal'] 	= (float)$currency['value_minimal'];

				$result = [
					'diff' 		=> false,
					'new_value' => $currency['value']
				];

				if ($diff = round(($currency['old_value_real'] - $value_real), 4) <> 0){
					$result['diff'] = $diff;
				}

				$new_value = $value_real;
				//If currency has auto_percent adding
				if ($currency['auto_percent']){
					$new_value = ($value_real + $value_real / 100 * $currency['auto_percent']);
				}

				if ($currency['value_minimal'] && $currency['value_minimal'] > 0){
					echoLine('[Currency::recalculate] Minimal value for ' . $code . ' is set:' . $currency['value_minimal']);

					if ($diff && $diff > 0 && abs($diff) > 0.1){
					}

					if (!($diff === false) && $diff < 0 && abs($diff) > 0.1){								
						echoLine('[Currency::recalculate] Rate for ' . $code . ' is up:' . abs($diff));

						if (($currency['old_value'] + abs($diff)) >$currency['value_minimal']){
							$new_value = ($currency['old_value'] + abs($diff));
							echoLine('[Currency::recalculate] New rate for ' . $code . ' after increasing of real rate:' . $new_value);							
						}

						if (($currency['old_value'] + abs($diff)) > $new_value){
							$new_value = ($currency['old_value'] + abs($diff));
							echoLine('[Currency::recalculate] Auto percent for ' . $code . ' is more, new value changed:' . $new_value);	
						}

						if ($new_value < $currency['value_minimal']){
							$new_value = $currency['value_minimal'];
							echoLine('[Currency::recalculate] New value less than min for ' . $code . ' overloading:' . $new_value);
						}
					}

					if ($new_value < $currency['value_minimal']){
						$new_value = $currency['value_minimal'];
						echoLine('[Currency::recalculate] New value less than min for ' . $code . ' overloading:' . $new_value);
					}
				}				

				if ($new_value != $currency['value_minimal'] && abs($new_value - $currency['value']) < ($currency['value']/100)*(int)$this->config->get('config_currency_auto_threshold')){					
					$new_value = $currency['value'];
					echoLine('[Currency::recalculate] New value not in threshold ' . $this->config->get('config_currency_auto_threshold') . ' overloading:' . $currency['value']);
				}

				$result['new_value'] = $new_value;
				return $result;

			} else {
				echoLine('[Currency::recalculate] Not supported currency: ' . $code);
				return false;
			}
		}
	}				