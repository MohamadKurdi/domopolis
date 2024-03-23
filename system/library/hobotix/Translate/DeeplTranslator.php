<?php

namespace hobotix\Translate;


class DeeplTranslator
{

	private $db 	= null;	
	private $config = null;
	private $debug  = null;

	private $deeplTranslator = null;

	private $enableCheck = false;

	private $hourLimit = 10000000;
	private $symbolLimit = 10000;
	private $sentensesDelimiter = '.';

	public function __construct($registry){
		$this->db 		= $registry->get('db');
		$this->config 	= $registry->get('config');

		if ($this->config->get('config_deepl_translate_api_key')){
			$this->deeplTranslator = new \DeepL\Translator($this->config->get('config_deepl_translate_api_key'));
		}		
	}

	public function checkIfItIsPossibleToMakeRequest(){
		try {
			$this->translate('hello', 'en', 'ru', true);
			echoLine('[DeeplTranslator::checkIfItIsPossibleToMakeRequest] Is ok, continue' , 's');
		} catch (\Exception $e) {
			echoLine('[DeeplTranslator::checkIfItIsPossibleToMakeRequest] Translator fail, stopping' , 'e');
			die();
		}		

		return true;
	}

	public function setDebug($debug){
		$this->debug = $debug;
		return $this;
	}


	public function resultIsBad($result){		
		$json = json_decode($result, true);

		if (!empty($json['error'])){
			if (!empty($json['error']['message'])){
				return $json['error']['message'] . ' (' . $json['error']['code'] . ')';
			}
		}

		return false;
	}


	public function translate($text, $from, $to, $returnString = false){
		$text 	= trim($text);
		$result = false;

		if (!mb_strlen($text)){
			return '';
		}

		if (is_numeric($text)){
			return $text;
		}

		if (!$from){
			$from = null;
		}			

		if (mb_strlen($text, 'UTF-8') > $this->symbolLimit){
			$translationResult = '';
			$translateArray = $this->toBigPieces($text);			
			
			foreach ($translateArray as $translateItem){
				$translationResult .= $this->translate($translateItem, $from, $to, true);
			}
			
			if ($returnString){
				return $translationResult;
			}

			return json_encode([
				'translations' => [
					'0' => [
						'text' => $translationResult
					]
				]
			]);		
		}

		try {
			$deeplResult = $this->deeplTranslator->translateText($text, $from, $to);
			$text = $deeplResult->text;

			if ($deeplResult->text){
				$result = json_encode([
					'translations' => [
							'0' => [
								'text' => $deeplResult->text
							]
						]
				]);
			}

			$this->addStats($text);

		} catch (\DeepL\DocumentTranslationException $e) {
			echoLine($e->getMessage(), 'e');
			throw new \Exception($e->getMessage());			
			die();		
		}

		if ($returnString){		
			if (!empty($deeplResult->text)){

				if ($this->debug){
					echoLine('[DeeplTranslator] ' . $from . ' -> ' . $to);
					echoLine('[DeeplTranslator] ' . $text . ' -> ' . $deeplResult->text);
				}

				return $deeplResult->text;
			} else {
				throw new \Exception('No translation got from deeplResult');
			}
		}

		return $result;
	}


	private function getHourlyAmount(){
		return $this->db->ncquery("SELECT SUM(amount) as total FROM translate_stats WHERE  time >= '" . date('Y-m-d', strtotime('-1 hour')) . "'")->row['total'];
	}

	private function addStats($text){
		$this->db->query("INSERT INTO translate_stats SET time = NOW(), amount = '" . (int)mb_strlen($text) . "'");
	}

	private function toSentenses ($text) {
		$sentArray = explode($this->sentensesDelimiter, $text);
		return $sentArray;
	}

	private function toBigPieces ($text) {
		while (strpos($text, '..') !== false){
			$text = str_replace(($this->sentensesDelimiter . $this->sentensesDelimiter), $this->sentensesDelimiter, $text);
		}

		$sentArray = $this->toSentenses($text);
		$i = 0;
		$bigPiecesArray[0] = '';
		for ($k = 0; $k < count($sentArray); $k++) {				
			if (mb_strlen($bigPiecesArray[$i] . $sentArray[$k] . $this->sentensesDelimiter) > $this->symbolLimit){
				$i++;
				$bigPiecesArray[$i] = $sentArray[$k] . $this->sentensesDelimiter;
			} else {
				$bigPiecesArray[$i] .= ($sentArray[$k] . $this->sentensesDelimiter);

				if (mb_strlen($bigPiecesArray[$i], 'UTF-8') > $this->symbolLimit){
					$i++;
					$bigPiecesArray[$i] = '';
				}
			}
		}

		return $bigPiecesArray;
	}

	private function fromBigPieces (array $bigPiecesArray) {
		ksort($bigPiecesArray);
		return implode($bigPiecesArray);
	}




}