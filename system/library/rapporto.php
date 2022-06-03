<?php
	class Rapporto {
		
		private $login = 'kitchenprofi_rest';
		private $passwd = 'Kvi7hG7Z';
		private $host = 'http://lk.rapporto.ru';
		private $port = 9080;
		
		private $alphaName = 9080;
		
		private $db;
		private $cache;
		private $config;
		
		public function setAlphaName($alphaName){
			
			$this->alphaName = $alphaName;
			
		}
		
		public function __construct($registry) {
			
			$this->db = $registry->get('db');
			$this->cache = $registry->get('cache');
			$this->config = $registry->get('config');
			
			$this->setAlphaName($this->config->get('config_sms_sign'));
			
			
		}
		
		private function doRequest($data){
							
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->host);
			curl_setopt($ch, CURLOPT_PORT, $this->port);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length: '.strlen($data)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			
			$response = curl_exec($ch);
			
			
			return $response;
			
			
			
		}
		
		public function sendViberText($data){
			
		}
		
		public function sendViberMedia($data){
			
		}
		
		
		public function sendCascade($data){
			
			
			
			
			
			
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
	}	