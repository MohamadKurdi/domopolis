<?php
class Language {
	private $defaultLanguage 				= 'english';
	private $currentLanguageDirectory		= 'english';
	private $defaultDirectory				= 'default';
	
	private $data 			= [];
	private $path 			= DIR_LANGUAGE;

	private $db			= null;
	private $config		= null;

	public function __construct($directory, $registry = false) {			
		$this->path = DIR_LANGUAGE;
		$this->currentLanguageDirectory = $directory;

		if ($registry){
			$this->config 	= $registry->get('config');
			$this->db 		= $registry->get('db');								
		}
	}

	public function mapCode($language_code){
		$mappings = loadJsonConfig('language_mapping');

		if (!empty($mappings[$language_code])){
			//echoLine('[Language::mapCode] Mapping ' . $language_code . ' to ' . $mappings[$language_code], 'w');
			return $mappings[$language_code];
		}

		return $language_code;
	}

	private function mergeOrReturn($fileOverload, $fileDefault, $merge = false, $return = false){
		$_ = [];

		if (file_exists($fileOverload)) {						
			require($fileOverload);				
		} elseif (file_exists($fileDefault)){				
			require($fileDefault);							
		}

		if ($merge){
			$this->data = array_merge($this->data, $_);
		}

		if ($return == 'data'){
			return $this->data;
		} elseif ($return == 'loaded'){
			return $_;
		}

		return;
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
		$fileOverload 	= $this->path . $this->currentLanguageDirectory . '/retranslate/' . $filename . '.php';
		$fileDefault 	= $this->path . $this->defaultDirectory . '/' . $this->currentLanguageDirectory . '/retranslate/' . $filename . '.php';

		return $this->mergeOrReturn($fileOverload, $fileDefault, false, 'loaded');
	}

	public function loadCatalogLanguage($language_id, $filename){
		if ($this->db){
			$query = $this->db->query("SELECT `directory` FROM language WHERE language_id = '" . (int)$language_id . "' LIMIT 1");

			if ($query->num_rows){
				$fileOverload 	= DIR_CATALOG . 'language/' . $query->row['directory'] . '/' . $filename . '.php';
				$fileDefault 	= DIR_CATALOG . 'language/' . $this->defaultDirectory . '/' . $query->row['directory'] . '/' . $filename . '.php';

				return $this->mergeOrReturn($fileOverload, $fileDefault, false, 'loaded');
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
		$fileOverload 	= $this->path . $this->currentLanguageDirectory . '/' . $filename . '.php';
		$fileDefault 	= $this->path . $this->defaultDirectory . '/' . $this->currentLanguageDirectory . '/' . $filename . '.php';
		return $this->mergeOrReturn($fileOverload, $fileDefault, true, 'data');

		$fileOverload 	= $this->path . $this->defaultLanguage . '/' . $filename . '.php';
		$fileDefault 	= $this->path . $this->defaultDirectory . '/' . $this->defaultLanguage . '/' . $filename . '.php';
		return $this->mergeOrReturn($fileOverload, $fileDefault, true, 'data');
	}

	public function load_full($filename, $loadOverwrite = true) {
		$fileOverload 	= $this->path . $this->currentLanguageDirectory . '/' . $filename . '.php';
		$fileDefault 	= $this->path . $this->defaultDirectory . '/'. $this->currentLanguageDirectory . '/' . $filename . '.php';
		$this->mergeOrReturn($fileOverload, $fileDefault, true, false);

		if($loadOverwrite){
			$fileOverloadOverwrite 	= str_replace('.php', '_.php', $fileOverload);
			$fileDefaultOverwrite 	= str_replace('.php', '_.php', $fileDefault);	
			return $this->mergeOrReturn($fileOverloadOverwrite, $fileDefaultOverwrite, true, 'data');
		}

		$fileOverload 	= $this->path . $this->defaultLanguage . '/' . $filename . '.php';
		$fileDefault 	= $this->path . $this->defaultDirectory . '/'. $this->currentLanguageDirectory . '/' . $filename . '.php';		
		$this->mergeOrReturn($fileOverload, $fileDefault, true, false);

		if($loadOverwrite){
			$fileOverloadOverwrite 	= str_replace('.php', '_.php', $fileOverload);
			$fileDefaultOverwrite 	= str_replace('.php', '_.php', $fileDefault);
			return $this->mergeOrReturn($fileOverloadOverwrite, $fileDefaultOverwrite, true, 'data');
		}
	} 
}	