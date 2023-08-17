<?

namespace hobotix\Translate;


class AzureTranslator
{

	private $db 	= null;	
	private $config = null;

	private $debug = false;
	private $endpoint = 'https://api.cognitive.microsofttranslator.com/translate/';

	private $enableCheck = false;

	private $hourLimit = 10000000;
	private $symbolLimit = 9000;
	private $sentensesDelimiter = '.';

	public function __construct($registry){
		$this->db 		= $registry->get('db');
		$this->config 	= $registry->get('config');
		$this->setDebug(true);
	}

	public function setDebug($debug){
		$this->debug = $debug;
		return $this;
	}

	public function checkIfItIsPossibleToMakeRequest(){
		try {
			$this->translate('hello', 'en', 'ru', true);
			echoLine('[AzureTranslator::checkIfItIsPossibleToMakeRequest] Is ok, continue' , 's');
		} catch (\Exception $e) {
			echoLine('[AzureTranslator::checkIfItIsPossibleToMakeRequest] Translator fail, stopping' , 'e');
			die();
		}		

		return true;
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
			$params = [
				'api-version' 	=> '3.0',    			
    			'to' 			=> $to
  			];  			

  			if ($from){
  				$params['from'] = $from;
  			}

  			$ch = curl_init($this->endpoint . '?' . http_build_query($params));
  			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([['text' => $text]]));
  			curl_setopt($ch, CURLOPT_HTTPHEADER, [
  				"Content-type: application/json",
  				"Ocp-Apim-Subscription-Key: " . $this->config->get('config_azure_translate_api_key'),
  				"Ocp-Apim-Subscription-Region: " . $this->config->get('config_azure_translate_api_region'),
  				"X-ClientTraceId: " . com_create_guid()
  			]);

  			$result = curl_exec($ch);
  			$info 	= curl_getinfo($ch);

  			if ($info['http_code'] != '200'){
  				echoLine('[AzureTranslator] Azure Translator Fail, http code is not 200, but: ' . $this->resultIsBad($result), 'e');
  				throw new \Exception('Azure Translator Fail, http code is not 200, but ' . $info['http_code']);
  			}

  			curl_close($ch);

			$this->addStats($text);

		} catch (\Exception $e) {
			echoLine($e->getMessage(), 'e');
			throw new \Exception($e->getMessage());			
			die();		
		}

		if ($this->resultIsBad($result)){
			echoLine('[AzureTranslator] AzureTranslator Fail: ' . $this->resultIsBad($result), 'e');
			throw new \Exception('Azure Translator Fail! ' . $this->resultIsBad($result));			
		}

		if ($returnString){
			$json = json_decode($result, true);

			if (!empty($json[0]) && !empty($json[0]['translations']) && !empty($json[0]['translations'][0]) && !empty($json[0]['translations'][0]['text'])){

				if ($this->debug){
					echoLine('[AzureTranslator] ' . $from . ' -> ' . $to);
					echoLine('[AzureTranslator] ' . $text . ' -> ' . $json[0]['translations'][0]['text']);
				}

				return $json[0]['translations'][0]['text'];
			} else {
				throw new \Exception('No translation got from Azure');
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