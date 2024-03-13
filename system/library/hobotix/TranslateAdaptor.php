<?php

namespace hobotix;

class TranslateAdaptor {	
	private $registry			= null;
	private $config				= null;
	private $db  				= null;
	private $translateObject 	= null;

    private const cacheStringLengthLimit = 255;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

        if ($this->config->get('config_enable_translation_cache')){
            echoLine('[TranslateAdaptor::__construct] Translation cache is enabled!', 'w');
        }

        if (!$this->translateObject){
            $this->use($this->config->get('config_translation_library'));
        }
	}

	public function getTranslateLibraries(){
		$results = [];

		$libraries = glob(dirname(__FILE__) . '/Translate/*');        
        foreach ($libraries as $library) {
            $results[] = pathinfo($library,  PATHINFO_FILENAME);
        }

        return $results;
	}

	public function use($translateClass){
		if (file_exists(DIR_SYSTEM . '/library/hobotix/Translate/' . $translateClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/Translate/' . $translateClass . '.php');
			$translateClass = "hobotix" . "\\" . "Translate" . "\\" . $translateClass;
			$this->translateObject = new $translateClass($this->registry);

			echoLine('[TranslateAdaptor::use] Using ' . $translateClass . ' translation library', 'w');
		} else {
			echoLine('[TranslateAdaptor::use] Tried to use ' . $translateClass . ' translation library, but failed', 'e');	
		}

		return $this;
	}

	public function setDebug($debug){
		if (method_exists($this->translateObject, 'setDebug')){
			return $this->translateObject->setDebug($debug);
		}

        return $this;
	}

	public function checkIfItIsPossibleToMakeRequest(){
		if (method_exists($this->translateObject, 'checkIfItIsPossibleToMakeRequest')){
			try {
				$result = $this->translateObject->checkIfItIsPossibleToMakeRequest();
			} catch (\Exception $e){
				$result = $e->getMessage();
			}
		}

		if (empty($result)){
			echoLine('[TranslateAdaptor::checkIfItIsPossibleToMakeRequest] Could not anything, maybe general api fail! ' . $result, 'e');			
			return false;			
		}			

		echoLine('[TranslateAdaptor::checkIfItIsPossibleToMakeRequest] Can make request!', 's');

		return $result;
	}

    private function updateCacheHitUsage($translation_cache_id){
        $this->db->query("UPDATE translation_cache SET `usages` = `usages` + 1 WHERE translation_cache_id = '" . (int)$translation_cache_id . "'");
    }

    private function setTranslationStringCache($from, $to, $text, $translation){
        if (mb_strlen($text) >= self::cacheStringLengthLimit){
            return false;
        }

        $this->db->query("INSERT INTO translation_cache SET 
            language_code_from = '" . $this->db->escape($from) . "',
            language_code_to = '" . $this->db->escape($to) . "',
            string = '" . $this->db->escape($text) . "',
            translation = '" . $this->db->escape($translation) . "'
            ON DUPLICATE KEY UPDATE
            translation = '" . $this->db->escape($translation) . "'");

        return true;
    }

    private function getTranslationStringFromCache($from, $to, $text){
        if (mb_strlen($text) >= self::cacheStringLengthLimit){
            return false;
        }

        $query = $this->db->ncquery("SELECT translation_cache_id, translation FROM translation_cache WHERE 
            language_code_from = '" . $this->db->escape($from) . "'
            AND language_code_to = '" . $this->db->escape($to) . "'
            AND string = '" . $this->db->escape($text) . "'");

        if ($query->num_rows){
            echoLine('[TranslateAdaptor::cache] Got translation string from cache: ' . $query->row['translation'], 's');

            $this->updateCacheHitUsage($query->row['translation_cache_id']);
            return $query->row['translation'];
        }

        return false;
    }

	public function translate($text, $from, $to, $returnString = false){
		if (method_exists($this->translateObject, 'translate')){
            $translation = false;

			try {
                if ($returnString && $this->config->get('config_enable_translation_cache')){
                    $translation = $this->getTranslationStringFromCache($from, $to, $text);

                    if ($translation){
                        return $translation;
                    } else {
                        $translation = $this->translateObject->translate($text, $from, $to, $returnString);
                        $this->setTranslationStringCache($from, $to, $text, $translation);
                    }
                } else {
                    $translation = $this->translateObject->translate($text, $from, $to, $returnString);
                }
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		}

		return $translation;
	}
}