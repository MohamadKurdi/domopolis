<?php
	class Log {
		private $filename;
		
		public function __construct($filename) {
			$this->filename = $filename;
		}
		
		public function clear() {
			$file = DIR_LOGS . $this->filename;
			$handle = fopen($file, 'w+');
			fclose($handle); 
		}
		
		public function write($message) {
			$file = DIR_LOGS . $this->filename;
			$handle = fopen($file, 'a+');
			if (is_resource($handle)){		
				fwrite($handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true)  . "\n");			
			}
			fclose($handle); 
		}
		
		public function debugsql($sql, $echo = false){
			$this->debug($sql, false, true, $echo);			
		}
		
		public function debug($variable, $message = false, $sql = false, $echo = false){
			
			if ((defined('IS_DEBUG') && IS_DEBUG) || $echo || php_sapi_name()=== 'cli') {
				print '<pre>';									
				if ($message){
					print_r($message);
				}
				
				if ($sql){				
					echo (new \Doctrine\SqlFormatter\SqlFormatter())->format($variable);
					} else {
					if ($variable){
						print_r($variable);
						} else {
						var_dump($variable);
					}
				}
				print '</pre>';
			}
			
		}
	}