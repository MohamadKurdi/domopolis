<?php

	namespace hobotix;
	
	class shortAlias {
		
		private $db;
		private $cache;
		private $cache_data;
		
		public function __construct($registry) {
			$this->db = $registry->get('db');
			$this->cache = $registry->get('cache');			
		}
		
		private function parse_url_if_valid($url)
		{
			// Массив с компонентами URL, сгенерированный функцией parse_url()
			$arUrl = parse_url($url);
			// Возвращаемое значение. По умолчанию будет считать наш URL некорректным.
			$ret = null;
			
			// Если не был указан протокол, или
			// указанный протокол некорректен для url
			if (!array_key_exists("scheme", $arUrl)
            || !in_array($arUrl["scheme"], array("http", "https")))
			// Задаем протокол по умолчанию - http
			$arUrl["scheme"] = "http";
			
			// Если функция parse_url смогла определить host
			if (array_key_exists("host", $arUrl) &&
            !empty($arUrl["host"]))
			// Собираем конечное значение url
			$ret = sprintf("%s://%s%s", $arUrl["scheme"],
			$arUrl["host"], $arUrl["path"]);
			
			// Если значение хоста не определено
			// (обычно так бывает, если не указан протокол),
			// Проверяем $arUrl["path"] на соответствие шаблона URL.
			else if (preg_match("/^\w+\.[\w\.]+(\/.*)?$/", $arUrl["path"]))
			// Собираем URL
			$ret = sprintf("%s://%s", $arUrl["scheme"], $arUrl["path"]);
			
			// Если url валидный и передана строка параметров запроса
			if ($ret && empty($ret["query"]))
			$ret .= sprintf("?%s", $arUrl["query"]);
			
			return $ret;
		}
		
		private function generateRandomString($length = 7) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
		
		//Получить длинную ссылку по короткой
		public function getURL($short_url, $update_times = false){
			
			$non_cached_query = $this->db->non_cached_query("SELECT alias FROM `short_url_alias` WHERE url LIKE '" . $this->db->escape(trim($short_url)) . "' LIMIT 1");
			
			if ($non_cached_query->num_rows){
				
				if ($update_times){
					$this->db->non_cached_query("UPDATE `short_url_alias` SET used = used+1 WHERE 	url_id = '" . (int)$non_cached_query->row['url_id'] . "'");
				}
				
				return str_replace('&amp;', '&', $non_cached_query->row['alias']);
				} else {
				return false;
			}
		}
		
		//Получить короткую ссылку по длинной
		public function getShortURL($url){
			
			$non_cached_query = $this->db->non_cached_query("SELECT url FROM `short_url_alias` WHERE alias LIKE '" . $this->db->escape(trim($url)) . "' LIMIT 1");
			
			if ($non_cached_query->num_rows) {
                return $non_cached_query->row['url'];
            }

            return false;
		}
		
		public function shortenURL($url, $domain = false, $length = 7){
		
			if (!$this->parse_url_if_valid($url)){
				return false;
			}
			
			if ($alias = $this->getShortURL($url)){
				} else {
				
				$alias = $this->generateRandomString($length);					
				$unique = !$this->getURL($alias);	
				
				while(!$unique){
					$alias = $this->generateRandomString($length);							
					$unique = !$this->getURL($alias);
				}
				
				$this->db->non_cached_query("INSERT INTO short_url_alias SET url = '" . $this->db->escape($alias) . "', alias = '" . $this->db->escape($url) . "', date_added = NOW()");
			}
			
			
			if ($domain){
				$domain = parse_url($domain, PHP_URL_HOST);	
				$alias = 'https://' . $domain . '/' . $alias;
				} else {
				$domain = parse_url($url, PHP_URL_HOST);				
				if ($domain){
					$alias = 'https://' . $domain . '/' . $alias;
					} else {
					return $alias;
				}
			}
			
			return $alias;
			
		}				
	}		