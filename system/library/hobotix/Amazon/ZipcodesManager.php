<?

namespace hobotix\Amazon;

class ZipcodesManager {
	
	const CLASS_NAME = 'hobotix\\Amazon\\ZipcodesManager';
	
	private $db			= null;	
	private $config 	= null;
	private $registry	= null;
	
	public $usedZipCodes 	= [];

	private $csv_source 		= 'https://raw.githubusercontent.com/plzTeam/web-snippets/master/plz-suche/data/zuordnung_plz_ort.csv';
	private $badRequestsLimit 	= 100;

	public const longPreparingThreshold = 2;

	public function __construct($registry){
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');	

		for ($i = 1; $i <= \hobotix\RainforestAmazon::zipcodeCount; $i++){
			if ($this->config->get('config_rainforest_api_zipcode_' . $i)){
				$this->usedZipCodes[] = $this->config->get('config_rainforest_api_zipcode_' . $i);
			}
		}

		if ($this->config->get('config_rainforest_checkzipcodes_bad_request_limit')){
			echoLine('[ZipcodesManager::__construct] Bad Request Limit ' . $this->config->get('config_rainforest_checkzipcodes_bad_request_limit'), 'd');
			$this->badRequestsLimit = (int)$this->config->get('config_rainforest_checkzipcodes_bad_request_limit');
		}
	}

	public function getUsedZipCodes(){
		return $this->usedZipCodes;
	}

	public function getRandomZipCode(int $requests = 1){
		$zipCode = $this->usedZipCodes[array_rand($this->usedZipCodes)];
		echoLine('[ZipcodesManager::getRandomZipCode] Using zipCode: ' . $zipCode, 'i');

		$this->updateZipCodeUsage($zipCode, $requests);

		return $zipCode;
	}

	public function getRandomZipCodeFromDatabase(){
		$query = $this->db->query("SELECT zipcode FROM amazon_zipcodes WHERE ISNULL(dropped) AND request_count = 0 ORDER BY RAND() LIMIT 1");

		if ($query->num_rows){
			echoLine('[ZipcodesManager::getRandomZipCodeFromDatabase] Got new zipcode ' . $query->row['zipcode'], 'w');
			return $query->row['zipcode'];
		} else {
			throw new \Exception('Can not get any zipcode from database, all are used, or empty');
		}
	}

	public function getZipCodeInfo(string $zipcode){
		$query = $this->db->query("SELECT * FROM amazon_zipcodes WHERE zipcode = '" . $this->db->escape($zipcode) . "'");

		return $query->row;
	}

	public function getBadZipCodes(){
		$zipcodes = [];

		if (!$this->badRequestsLimit){
			echoLine('[ZipcodesManager::getBadZipCodes] Bad request limit not set', 'w');
			return [];
		}

		$query = $this->db->ncquery("SELECT * FROM amazon_zipcodes WHERE error_count > '" . (int)$this->badRequestsLimit . "' AND ISNULL(dropped)");

		foreach ($query->rows as $row){
			if (in_array($row['zipcode'], $this->usedZipCodes)){
				$zipcodes[] = $row['zipcode'];
			}
		}

		return $zipcodes;
	}

	private function updateZipCodeUsage(string $zipcode, int $requests = 1){
		echoLine('[ZipcodesManager::updateZipCodeUsage] Updating usage count for zipcode ' . $zipcode . ' +' . $requests . ' requests', 'w');
		$this->db->query("UPDATE amazon_zipcodes SET request_count = request_count + " . (int)$requests . ", last_used = NOW() WHERE zipcode = '" . $this->db->escape($zipcode) . "'");
	}

	public function updateZipCodeDateAdded(string $zipcode){
		$this->db->query("UPDATE amazon_zipcodes SET added = NOW() WHERE zipcode = '" . $this->db->escape($zipcode) . "' AND ISNULL(added)");
	}

	public function updateZipCodeDateDropped(string $zipcode){
		echoLine('[ZipcodesManager::updateZipCodeErrorCount] Updating dropped time for zipcode ' . $zipcode, 'w');
		$this->db->query("UPDATE amazon_zipcodes SET dropped = NOW() WHERE zipcode = '" . $this->db->escape($zipcode) . "' AND ISNULL(dropped)");
	}

	public function updateZipCodeErrorCount(string $zipcode){
		echoLine('[ZipcodesManager::updateZipCodeErrorCount] Updating error count +1 for zipcode ' . $zipcode, 'w');
		$this->db->query("UPDATE amazon_zipcodes SET error_count = error_count + 1 WHERE zipcode = '" . $this->db->escape($zipcode) . "'");
	}

	public function zipCodeRequest(string $zipcode, string $action){
		$body = [
			[
				'zipcode' 	=> $zipcode,
				'domain' 	=> $this->config->get('config_rainforest_api_domain_1')
			]
		];

		$ch = curl_init('https://api.rainforestapi.com/zipcodes?api_key=' . $this->config->get('config_rainforest_api_key'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 180);			
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

		if ($action == 'add'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		} elseif ($action == 'delete') {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}

		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response, true);

		if (!empty($response) && !empty($response['request_info']) && !empty($response['request_info']['success'])){
			echoLine('[ZipcodesManager::request] Success operation with zipcodes ' . $response['request_info']['message'], 's');
		} else {
			print_r($response);
			echoLine('[ZipcodesManager::request] Failed operation with zipcodes!', 'e');
			throw new \Exception('Failed operation with zipcodes!');
		}
	}

	public function checkZipCodes(){
		$queryString = http_build_query([
			'api_key' => $this->config->get('config_rainforest_api_key'),
			'domain'  => $this->config->get('config_rainforest_api_domain_1')
		]);

		$error 		= false;	
		$warning 	= false;

		$ch = curl_init(sprintf('%s?%s', 'https://api.rainforestapi.com/zipcodes', $queryString));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$answer 	= curl_exec($ch);
		$httpcode 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($httpcode != 200){
			$error = 'CODE_NOT_200_MAYBE_PAYMENT_FAIL';
			$answer .= ' HTTPCODE: ' . $httpcode;
			$answer = trim($answer);
		}

		if (!$error && !json_decode($answer)){
			$error = 'JSON_DECODE';
			$answer .= '<br />HTTPCODE: ' . $httpcode;
		}

		if ($answer_decoded = json_decode($answer, true)){
			$answer = $answer_decoded;
		}

		return $answer;
	}

	public function checkDB(){
		$query = $this->db->query("SELECT COUNT(zipcode) as total FROM amazon_zipcodes");

		if (!$query->row['total']){
			echoLine('[ZipcodesManager::checkDB] Checking database, have no zipcodes, filling database', 'e');
			$this->fillZipCodes();
		} else {
			echoLine('[ZipcodesManager::checkDB] Checking database, have ' . $query->row['total'] . ' zipcodes', 'i');
		}

		return true;
	}

	public function fillZipCodes(){
		$csv 		= file_get_contents($this->csv_source);
		$zipcodes   = explode(PHP_EOL, $csv);

		array_shift($zipcodes);

		foreach ($zipcodes as $zipcode){
			$line = explode(',', $zipcode);

			$this->db->query("INSERT INTO amazon_zipcodes SET
			zipcode_area 	= '" . $this->db->escape($line[1]) . "',
			zipcode_area2 	= '" . $this->db->escape($line[3]) . "',
			zipcode 		= '" . $this->db->escape($line[2]) . "'
			ON DUPLICATE KEY UPDATE 
			zipcode_area 	= '" . $this->db->escape($line[1]) . "',
			zipcode_area2 	= '" . $this->db->escape($line[3]) . "'");
		}		
	}
}