<?php
	class ModelToolOnline extends Model {	
		
		private function isBot($useragent){		
			return CRAWLER_SESSION_DETECTED;		
		}
		
		public function whosonline($ip, $customer_id, $url, $referer, $useragent) {
			$this->db->query("DELETE FROM `customer_online` WHERE (UNIX_TIMESTAMP(`date_added`) + 1800) < UNIX_TIMESTAMP(NOW())");
			
			$this->db->query("REPLACE INTO `customer_online` SET `ip` = '" . $this->db->escape($ip) . "', `customer_id` = '" . (int)$customer_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `useragent` = '" . $this->db->escape($useragent) . "', is_bot = '" . (int)$this->isBot($useragent) . "', is_pwa = '" . (int)$this->customer->getPWASession() . "', `date_added` = NOW()");
		}
	}	