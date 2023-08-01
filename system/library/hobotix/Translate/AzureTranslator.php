<?

namespace hobotix\Translate;


class AzureTranslator
{

	private $db 	= null;	
	private $config = null;
	private $cloud 	= null;

	private $debug = false;

	private $enableCheck = false;

	private $hourLimit = 10000000;
	private $symbolLimit = 9000;
	private $sentensesDelimiter = '.';

	public function __construct($registry){
		$this->db 		= $registry->get('db');
		$this->config 	= $registry->get('config');

		try {
			$this->cloud = \Panda\Yandex\TranslateSdk\Cloud::createApi($this->config->get('config_yandex_translate_api_key'));							
		} catch (\Panda\Yandex\TranslateSdk\Exception\ClientException $e) {
			echoLine($e->getMessage());				
		}
	}

	public function setDebug($debug){
		$this->debug = $debug;
		return $this;
	}

	public function checkIfItIsPossibleToMakeRequest(){
		try {
			$this->translate('hello', 'en', 'ru');
		} catch (\Panda\Yandex\TranslateSdk\Exception\ClientException $e) {
			echoLine('[ControllerDPRainForest::addasinsqueuecron] Translator fail, stopping' , 'e');
			die();
		}		
	}

	public function resultIsBad($result){		
		$json = json_decode($result, true);

		if (!empty($json['code']) && $json['code'] == 7){
			if (!empty($json['message'])){
				return $json['message'];
			}
		}

		return false;
	}

	public function translateMulti($data = []){
	}

	public function translate($text, $from, $to, $returnString = false){
		$text = trim($text);

		$result = false;

		if (!mb_strlen($text)){
			return '';
		}

		if (is_numeric($text)){
			return $text;
		}			

		if ($this->enableCheck){
			if ($this->getHourlyAmount() >= ($this->hourLimit*0.95)){
				echoLine('[YandexTranslator] Лимит на часовой перевод почти достигнут, надо поспать минуту!');
				sleep(mt_rand(50,60));
			}
		}		
		
		if (mb_strlen($text, 'UTF-8') > $this->symbolLimit){
			$translationResult = '';
			$translateArray = $this->toBigPieces($text);			
			
			foreach ($translateArray as $translateItem){
				$translationResult .= $this->translate($translateItem, $from, $to, true);
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
			$translate = new \Panda\Yandex\TranslateSdk\Translate($text);

			if ($from){
				$translate->setSourceLang($from);
			}

			$translate->setTargetLang($to)->setFormat(\Panda\Yandex\TranslateSdk\Format::HTML);
			$result = $this->cloud->request($translate);

			$this->addStats($text);

		} catch (\Panda\Yandex\TranslateSdk\Exception\ClientException $e) {
			echoLine($e->getMessage(), 'e');
			die();		
		}

		if ($this->resultIsBad($result)){
			echoLine('[YandexTranslator] YandexTranslator Fail: ' . $this->resultIsBad($result), 'e');
			throw new \Panda\Yandex\TranslateSdk\Exception\ClientException('YT Fail! ' . $this->resultIsBad($result));			
		}

		if ($returnString){
			$json = json_decode($result, true);

			if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){

				if ($this->debug){
					echoLine('[YandexTranslator] ' . $from . ' -> ' . $to);
					echoLine('[YandexTranslator] ' . $text . ' -> ' . $json['translations'][0]['text']);
				}

				return $json['translations'][0]['text'];
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
				//Если в этой итерации получается так, что 
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