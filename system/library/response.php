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
		
		private function size_convert($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
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
				$this->outputDebug();				
			}
		}

		public function outputDebug(){
			if (defined('DEBUGSQL') && DEBUGSQL) {						
					$time = microtime();
					$time = explode(' ', $time);
					$time = $time[1] + $time[0];
					$finish = $time;
					$start = explode(' ', $GLOBALS['start']);
					$start = $start[1] + $start[0];
					$total_time = round(($finish - $start), 4);
					
					$queries = $GLOBALS['sql'];
					
					echo '<div id="debug" style="position:relative; bottom:0; z-index:1000; width:100%;min-height:100px; padding:20px; background: darkred; "><div style="width:1000px;margin:0 auto;">';
					echo '<div style="color:white; font-size:14px; line-height:20px">Page gen time ' . $total_time. ' seconds | ';
					echo 'Total sql queries ' . count($GLOBALS['sql']) . '</div>';
					echo '<div style="color:white; font-size:14px; line-height:20px">Memory usage ' . $this->size_convert(memory_get_usage(true)) . '</div>';
					foreach ($queries as $query) {
						$sql = explode ('[sep]', $query);
						
						$querytime 	= round($sql[1],4);						
						$controller = $sql[2];						
						$iscached 	= $sql[3];
						
						if ($querytime > 0.004) {
							echo '<div style=" width:990px; color:red; font-size:12px;background:white;margin-bottom:0px;padding:2px;"> время:' . $querytime  . ' сек. | controller: <span style="color:black;">'.$controller. '</span> кэш: <span style="color:black;">'. $iscached. '</span></div>'; 
							} else { 
							echo '<div style=" width:990px; color:grey; font-size:12px;background:white;margin-bottom:0px;padding:2px;"> время:' . $querytime  . ' сек.  | controller:  <span style="color:black;">'.$controller. '</span> | кэш: <span style="color:black;">'. $iscached. '</span></div>'; 
						}
						
						echo '<div style=" width:990px; color:#666; font-size:10px;background:white;margin-bottom:2px;padding:1px 2px; border-top:1px solid #ddd">' . $sql[0] . '</div>';
						
					};
					
					echo '</div></div>';				
				}
		}
		
		
	}			