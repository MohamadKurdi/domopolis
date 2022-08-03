<?php
	class Language {
		private $default 	= 'english';
		private $directory	= 'english';
		private $data 		= [];
		private $path 		= DIR_LANGUAGE;

		private $db			= null;
		private $config		= null;
		
		public function __construct($directory, $registry = false) {			
			$this->path = DIR_LANGUAGE;
			
			$this->directory = $directory;
			
			if ($registry){
				$this->config 	= $registry->get('config');
				$this->db 		= $registry->get('db');								
			}
		}
		
		public function setPath($path){
			if(!is_dir($path)){
				trigger_error('Error: check path exists: '.$path);
				exit;
			}
			$this->path = $path;
		}
		
		public function get($key) {
			return (isset($this->data[$key]) ? $this->data[$key] : $key);
		}
		
		public function loadRetranslate($filename){
			$file = $this->path . $this->directory . '/retranslate/' . $filename . '.php';
			
			if (file_exists($file)) {
				$_ = [];				
				require($file);				
				return $_;
			}
		}
		
		public function loadCatalogLanguage($language_id, $filename){
			if ($this->db){
				$query = $this->db->query("SELECT directory FROM language WHERE language_id = '" . (int)$language_id . "' LIMIT 1");

				if ($query->num_rows){
					$file = DIR_CATALOG .'language/'. $query->row['directory'] . '/' . $filename . '.php';

					if (file_exists($file)) {
						$_ = [];				
						require($file);				
						return $_;
					}
				}
			}
		}
		
		public function getCatalogLanguageString($language_id, $filename, $string){
		
			$stringsArray = $this->loadCatalogLanguage($language_id, $filename);
		
			if (!empty($stringsArray[$string])){
				return $stringsArray[$string];
			}
			
			return '';
		}
		
		public function load($filename) {
			$file = $this->path . $this->directory . '/' . $filename . '.php';
			
			if (file_exists($file)) {
				$_ = array();
				
				require($file);
				
				$this->data = array_merge($this->data, $_);
				
				return $this->data;
			}
			
			$file = $this->path . $this->default . '/' . $filename . '.php';
			
			if (file_exists($file)) {
				$_ = array();
				
				require($file);
				
				$this->data = array_merge($this->data, $_);
				
				return $this->data;
				} else {
				trigger_error('Error: Could not load language ' . $filename . '!');				
			}
		}
		
		public function load_full($filename, $loadOverwrite = true) {
			// Standard
			$file = $this->path . $this->directory . '/' . $filename . '.php';
			if (file_exists($file)) {
				$_ = array();		
				require($file);		
				$this->data = array_merge($this->data, $_);
				
				// Standard with overwrite
				if($loadOverwrite){
					$file = $this->path . $this->directory . '/' . $filename . '_.php';		
					if (file_exists($file)) {
						$_ = array();		
						require($file);		
						$this->data = array_merge($this->data, $_);
					}
				}
				
				return $this->data;
			}
			
			// Default
			$file = $this->path . $this->default . '/' . $filename . '.php';		
			if (file_exists($file)) {
				$_ = array();		
				require($file);		
				$this->data = array_merge($this->data, $_);
				
				// Default with overwrite
				if($loadOverwrite){
					$file = $this->path . $this->directory . '/' . $filename . '_.php';		
					if (file_exists($file)) {
						$_ = array();		
						require($file);		
						$this->data = array_merge($this->data, $_);
					}
				}
				
				return $this->data;
				} else {
				trigger_error('Error: Could not load language file: ' . $file . '!');
			}
		} 
	}	