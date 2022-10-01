<?php
	class Response {
		private $headers = array(); 
		private $level = 0;
		private $output;
		private $db = false;
		private $config = false;
		private $bcache = false;
		private $cache = false;
		private $fastTranslate = false;
		
		public function __construct($registry = false) {
			if ($registry) {
				$this->bcache 	= $registry->get('bcache');
				$this->cache  	= $registry->get('cache');
				$this->config 	= $registry->get('config');
				$this->db 		= $registry->get('db');
				$this->language = $registry->get('language');
				
				if (defined('THIS_IS_CATALOG') && $this->db && $this->cache && $this->config && $this->config->get('config_language_id')){
					$this->fastTranslate = $this->prepareFastTranslateArray($registry->get('languages')[$this->config->get('config_language')]['fasttranslate']);
				}
				
			}
		}
		
		public function addHeader($header) {
			$this->headers[] = $header;
		}
		
		private function doLanguageLinksRebuild(){
			
		}				
		
		private function prepareFastTranslateArray($fastTranslate){
			$fTFrom = array();
			$ftTo = array();
			
			$fTexploded = explode(PHP_EOL, $fastTranslate);
			if (is_array($fTexploded)){
				foreach ($fTexploded as $line){
					$lineExploded = explode('::', $line);
					
					if (is_array($lineExploded) && count($lineExploded) == 2){
						$fTFrom[] = trim($lineExploded[0]);
						$ftTo[] = trim($lineExploded[1]);
					}
				}
			}
			
			return array(
			'fTFrom' 	=> $fTFrom,
			'ftTo' 		=> $ftTo
			);
		}
		
		private function doFastTranslate(){
			
			if ($this->fastTranslate){
				$this->output = str_replace($this->fastTranslate['fTFrom'], $this->fastTranslate['ftTo'], $this->output);
			}
			
			return $this;
		}
		
		public function redirect($url) {
			header('Location: ' . $url);
			exit;
		}
		
		public function setCompression($level) {
			$this->level = $level;
		}
		
		public function setJSON($json) {
			$this->addHeader('Content-Type: application/json');
		
			$this->output = trim(json_encode($json));
			
			return $this;
		}
		
		public function setOutput($output) {
			if (IS_HTTPS && defined('THIS_IS_CATALOG')){
				$output = str_ireplace('http://', 'https://', $output);
			}			

			$this->output = $output;	
								
			$this->doFastTranslate();			
			
			return $this;
		}
		
		public function setNonHTTPSOutput($output) {
			$this->output = trim($output);
			
			return $this;
		}
		
		private function compress($data, $level = 0) {
			if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
				$encoding = 'gzip';
			} 
			
			if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
				$encoding = 'x-gzip';
			}
			
			if (!isset($encoding)) {
				return $data;
			}
			
			if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
				return $data;
			}
			
			if (headers_sent()) {
				return $data;
			}
			
			if (connection_status()) { 
				return $data;
			}
			
			$this->addHeader('Content-Encoding: ' . $encoding);			
			
			if ((int)$level > 0){
				return gzencode($data, (int)$level);
			} else {
				return $data;
			}
		}
		
		public function returnOutput(){
			
			return $this->output;			
			
		}

		public function output() {
			if ($this->output) {
				$output = $this->output;	
				
				if (!headers_sent()) {
					foreach ($this->headers as $header) {
						header($header, true);
					}
				}
				
				echo trim($output);
				echo $this->outputDebug();				
			}
		}

		public function outputDebug(){
			$output = '';
			if (defined('DEBUGSQL') && DEBUGSQL) {						
					global $FPCTimer;

					$totalSQLTime = 0;
					foreach ($GLOBALS['sql-debug'] as $query) {
						$totalSQLTime += $query['querytime'];
					}
					
					$output .= '<div id="debug" style="position:relative; bottom:0; z-index:1000; width:100%;min-height:100px; padding:20px; background: #ff3333; ">';
					$output .= '<div style="width:1000px;margin:0 auto;">';
					$output .= '	<div style="color:white; font-size:14px; line-height:20px">Page gen time ' . $FPCTimer->getTime() . ' sec</div>';
					$output .= '	<div style="color:white; font-size:14px; line-height:20px">Total SQL time ' . $totalSQLTime . ' sec</div>';
					$output .= '	<div style="color:white; font-size:14px; line-height:20px">Total SQL queries ' . count($GLOBALS['sql-debug']) . '</div>';
					$output .= '	<div style="color:white; font-size:14px; line-height:20px">Mem PHP ' . size_convert(memory_get_usage(false)) . '</div>';
					$output .= '	<div style="color:white; font-size:14px; line-height:20px">Mem SYS ' . size_convert(memory_get_usage(true)) . '</div>';
					$output .= '	<div style="color:white; font-size:14px; line-height:20px">Peak Mem PHP ' . size_convert(memory_get_peak_usage(false)) . '</div>';
					$output .= '	<div style="color:white; font-size:14px; line-height:20px">Peak Mem SYS ' . size_convert(memory_get_peak_usage(true)) . '</div>';
					foreach ($GLOBALS['sql-debug'] as $query) {																		
						$output .= '	<div style=" width:990px; color:'  . ($query['bad']?'#ff3333':'#009900') . '; font-size:12px;background:white;margin-bottom:0px;padding:2px;">';
						$output .= ' [' . (($query['bad'])?'LONG QUERY':'OK QUERY') . ']';
						$output .= ' [' . sprintf('%.7f', $query['querytime'])  . ' sec] <span style="color:black;">[' . $query['cached'] . ']</span></div>'; 

						$output .= '	<div style="width:990px;color:#666; font-size:8px;background:white;padding:5px;border-top:1px solid #ddd; font-family:Courier">';

						foreach ($query['backtrace'] as $key => $backtrace){
							$path = explode('/', $backtrace['file']);
							$path = array_slice($path, -3, 3, true);

							$output .= '<div style="line-height:10px;">';
							$output .= $key . ': ' . $backtrace['class'] . $backtrace['type'] . $backtrace['function'] . ' called from @' . implode('/', $path) . ' at line ' . $backtrace['line'];
							$output .= '</div>';
						}

						$output .= '	</div>';
						
						$output .= '<div style=" width:990px; color:#666; font-size:10px;background:white;margin-bottom:3px;padding:5px; border-top:1px solid #ddd;  font-family:Courier">' . $query['sql'] . '</div>';
						
					};
					
					$output .= '</div>';
					$output .= '</div>';				
				}

			return $output;
		}
		
		
	}			