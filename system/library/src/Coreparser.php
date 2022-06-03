<?php
	
	/**
		* Created by PhpStorm.
		* User: horror
		* Date: 08.06.2018
		* Time: 3:23
	*/
	class Coreparser
	{
		public $db;
		
		public function __construct($registry = false){
			
			if ($registry) {
				$this->db = $registry->get('db');
			}
			
		}
		/**
			* @param $url
			* @param bool|false $curl_debug
			* @return bool|mixed
		*/
		public function get_page($url, $curl_debug = false)
		{
			try {
				require_once(DIR_SYSTEM . 'library/UserAgentList.php');
				} catch (Exception $e) {
				
			}
			
			//  $_list = _userAgents();
			// $_UA = $_list[rand(0, _userAgents(true) - 1)];
			
			$_UA = "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0";
			
			//AMAZON PROXIES
			$_proxies = array(
            '144.76.214.96:8076',
            '144.76.214.97:8077',
            '144.76.214.98:8078',
            '144.76.214.99:8079',
            '144.76.214.100:8090',
            '144.76.214.101:8089',
            '144.76.214.102:8082',
			);
			if (strpos($url, 'wmf.com')) {
				
				/*
					$_proxies = array(
					'5.9.143.113:8077',
					'93.190.40.77:8077',
					);
				*/
				
			}
			
			$_proxy = $_proxies[rand(0, count($_proxies) - 1)];
			
			$_host = parse_url($url, PHP_URL_HOST);
			
			$_headers = array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'Host: ' . $_host,
            'Referer: ' . $url,
            'User-Agent: ' . $_UA,
            'X-MicrosoftAjax: Delta=true'
			);
			
			//Curl
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
	//		curl_setopt($ch, CURLOPT_PROXY, $_proxy);     // PROXY details with port
	//		curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'kpproxyuser:znmqP0la12kk');
	//		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt($ch, CURLOPT_HEADER, 0); // return headers 0 no 1 yes
			curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return page 1:yes
			curl_setopt($ch, CURLOPT_TIMEOUT, 200); // http request timeout 20 seconds
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects, need this if the url changes
			curl_setopt($ch, CURLOPT_MAXREDIRS, 2); //if http server gives redirection responce
			curl_setopt($ch, CURLOPT_USERAGENT, $_UA);
			curl_setopt($ch, CURLOPT_COOKIEJAR,
            DIR_SYSTEM . "temp/_cookies_" . md5($_host) . ".txt"); // cookies storage / here the changes have been made
			curl_setopt($ch, CURLOPT_COOKIEFILE, DIR_SYSTEM . "temp/_cookies_" . md5($_host) . ".txt");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // false for https
			curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br"); // the page encoding
			
			if ($curl_debug) {			
				curl_setopt($ch, CURLOPT_VERBOSE, true);
				$verbose = fopen('php://temp', 'w+');
				curl_setopt($ch, CURLOPT_STDERR, $verbose);
				
				rewind($verbose);
				$verboseLog = stream_get_contents($verbose);
				
			//	echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
			}
			
			$data = curl_exec($ch); // execute the http request		
			
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if ($httpcode == 404) {
				
				$plog = new Log('parser_fails_404.txt');
				$plog->write('404 FAIL : ' . $url . ' : proxy : ' . $_proxy . ' : UA : ' . $_UA);
				
				echo '404! Страница не найдена! : ' . $url;
				
				return false;
				
				die();
				
				} elseif ($httpcode == 503) {
				
				$plog = new Log('parser_fails_503.txt');
				$plog->write('503 FAIL : ' . $url . ' : proxy : ' . $_proxy . ' : UA : ' . $_UA);
				
				echo '503! Нам отказано в доступе! : ' . $url;
				
				return false;
				
				die();
				
				} elseif ($httpcode == 403) {
				
				$plog = new Log('parser_fails_403.txt');
				$plog->write('503 FAIL : ' . $url . ' : proxy : ' . $_proxy . ' : UA : ' . $_UA);
				
				echo '403! Нам отказано в доступе! : ' . $url;
				
				return false;
				
				die();
				
				} elseif ($httpcode == 400) {
				
				$plog = new Log('parser_fails_400.txt');
				$plog->write('400 FAIL : ' . $url . ' : proxy : ' . $_proxy . ' : UA : ' . $_UA);
				
				echo '400! Плохой запрос! : ' . $url;
				
				return false;
				
				die();
				
				} elseif ($httpcode == 500) {
				
				$plog = new Log('parser_fails_500.txt');
				$plog->write('500 FAIL : ' . $url . ' : proxy : ' . $_proxy . ' : UA : ' . $_UA);
				
				echo '500! Че-то пошло не так! : ' . $url;
				
				return false;
				
				die();
				
				} elseif ($httpcode == 301) {
				
				$plog = new Log('parser_fails_' . $httpcode . '.txt');
				$plog->write($httpcode . ' FAIL : ' . $url . ' : proxy : ' . $_proxy . ' : UA : ' . $_UA);
				
				echo $httpcode . '! Че-то пошло не так! : ' . $url . $_proxy . $_UA;
				
				} elseif ($httpcode != 200) {
				
				$plog = new Log('parser_fails_' . $httpcode . '.txt');
				$plog->write($httpcode . ' FAIL : ' . $url . ' : proxy : ' . $_proxy . ' : UA : ' . $_UA);
				
				echo $httpcode . '! Че-то пошло не так! : ' . $url . $_proxy . $_UA;
				
				return false;
				
				die();
				
			}
			
			curl_close($ch); // close the connection
			return $data;
		}
		
		
		/**
			* @param $price
			* @return float
		*/
		public function fixPrice4($price)
		{
			
			$price = str_replace(".", "", $price);
			$price = str_replace(",00", "", $price);
			$price = str_replace(",", ".", $price);
			
			return (float)trim(preg_replace('/[^\d.]/', '', $price));
		}
		
		/**
			* @param $price
			* @return float
		*/
		public function fixPrice2($price)
		{
			
			$price = str_replace(",", "", $price);
			$price = str_replace('.00', '', $price);
			
			return (float)trim(preg_replace('/[^\d.]/', '', str_replace(",", ".", $price)));
		}
		
		/**
			* @param $price
			* @return float
		*/
		public function fixPrice3($price)
		{
			
			$price = str_replace(",", "", $price);
			$price = str_replace('.00', '', $price);
			
			return (float)trim(preg_replace('/[^\d.]/', '', $price));
		}
		
		
		
		
		
	}		