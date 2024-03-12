<?php
	class Log {
		private $filename 	= '';
		private $firePHP 	= null;
		
		public function __construct($filename = '') {
			$this->filename = $filename;			

			if ((defined('ADMIN_SESSION_DETECTED') && ADMIN_SESSION_DETECTED) || (defined('IS_DEBUG') && IS_DEBUG)){
				if (class_exists('FirePHP')){
						$this->firePHP = \FirePHP::init();
				}			
			}
		}
		
		public function clear() {
			$file 	= DIR_LOGS . $this->filename;
			$handle = fopen($file, 'w+');
			fclose($handle); 
		}
		
		public function write($message) {
			if ($this->filename){
				$file 	= DIR_LOGS . $this->filename;
				$handle = fopen($file, 'a+');

				if (is_resource($handle)){		
					fwrite($handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true)  . "\n");			
				}

				fclose($handle); 
			}
		}

		public function sql($sql, $echo = false){
			$this->debug($sql, false, true, $echo);			
		}
		
		public function debugsql($sql, $echo = false){
			$this->debug($sql, false, true, $echo);			
		}

		public function fire($variable, $level = 'log'){	
			if (!$this->firePHP){
				return;
			}

			if ($level == 'log'){
				$this->firePHP->log($variable);
			}

			if ($level == 'info'){
				$this->firePHP->info($variable);
			}

			if ($level == 'warn'){
				$this->firePHP->warn($variable);
			}

			if ($level == 'error'){
				$this->firePHP->error($variable);
			}

			return;
		}		
		
		public function debug($variable, $message = false, $sql = false, $echo = false){			
			if ((defined('IS_DEBUG') && IS_DEBUG) || $echo || is_cli()) {	

				if (!is_cli()) { print '<pre>';	}

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

				if (!is_cli()) { print '</pre>'; } else { print PHP_EOL; }
			}			
		}
	}