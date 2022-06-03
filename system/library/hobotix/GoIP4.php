<?

namespace hobotix;

class GoIP4{
	private $config = null;

	private $login 		= null;
	private $password 	= null;
	private $server 	= null;
	private $timeout = 10;
	private $retries = 10;

	public function __construct($registry){
		$this->config = $registry->get('config');

		$this->login = $this->config->get('config_goip4_user');
		$this->password = $this->config->get('config_goip4_passwd');
		$this->server = $this->config->get('config_goip4_uri');
	}

	private function sendUSSD($ussd, $line){	
		$login 		= $this->login;
		$password 	= $this->password;
		$server 	= $this->server;
		$timeout 	= $this->timeout;
		$retries 	= $this->retries;
		
		//send
		$mid = str_pad(rand(1,10000),8,"0",STR_PAD_LEFT); 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "http://$login:$password@$server/default/en_US/ussd_info.html");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "line=$line&smskey=$mid&action=USSD&telnum=$ussd&send=Send");
		$out = curl_exec($curl);
		curl_close($curl);
		
		$done=false;
		$c=0;
		
		while (!$done and $c <= $retries) {
			unset($a);
			sleep(1);
			$c += 1;			
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "http://$server/default/en_US/send_status.xml?u=$login&p=$password");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			$out = curl_exec($curl);			
			curl_close($curl);
			
			$a = xml2array($out, 0);
			
			if ($a && isset($a["send-sms-status"]) && $a["send-sms-status"]["status$line"] == "DONE") {
				$done = true;
			}
		}
		
		if (!$done && isset($a["send-sms-status"]["error$line"])){
			return false;
		} else {
			return $a["send-sms-status"]["error$line"];
		}
		
		
		
	}

	public function getKyivstarBalance($line = 1){		
		$ussd = urlencode("*111#");		
		
		if ($responce = $this->sendUSSD($ussd, $line)) {
		//Na rahunku 45.00 grn. Detalno pro bonusy za nomerom *100# Novi igry v AppClub! SMS na 8870. 3 dni po 0 grn, dali 0,62 grn/den'

			$responce = str_replace('Na rahunku ','', $responce);
			$responce = explode('grn.', $responce);
			$responce = (int)trim($responce[0]);

			return $responce;

		} else {

			return null;

		}

	}
	
	public function getVodafoneBalance($line = 1){			
		$ussd = urlencode("*101#");

		
		if ($responce = $this->sendUSSD($ussd, $line)){
		//Na Vashem schete 81.00 grn. Tarif 'Vodafone Light+'. Nomer deystvitelen do 27.03.2018. Super Internet za 1,10 grn/den': *101*

			$responce = str_replace('Na Vashomu rahunku ','', $responce);
			#$responce = str_replace('Na Vashem schete ','', $responce); # Old style, was in russian lang, 22.02.2022 ukr lang
			$responce = explode('grn.', $responce);
			$responce = (int)trim($responce[0]);
			
			return $responce;
			
		} else {

			return null;

		}

	}








}