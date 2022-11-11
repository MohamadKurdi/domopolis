<?

#DEPRECATED, MOVED TO LIBRARY

class ControllerApiGOIP4 extends Controller { 
	private $login 		= null;
	private $password 	= null;
	private $server 	= null;
	private $timeout = 10;
	private $retries = 10;
	
	public function __construct($registry){
		parent::__construct($registry);

		$this->login = $this->config->get('config_goip4_user');
		$this->password = $this->config->get('config_goip4_passwd');
		$this->server = $this->config->get('config_goip4_uri');
	
	}
	
	private function yesNoStyle($s){
	
		if (trim(strip_tags(trim($s))) == 'Y'){
			return "<span style='color:white; padding:3px; background:#4ea24e; font-weight:700; display:inline-block; text-align:center'>Y</span>";
		} elseif (trim(strip_tags(trim($s))) == 'N'){
			return "<span style='color:white; padding:3px; background:#cf4a61; font-weight:700; display:inline-block;  text-align:center'>N</span>";
		} else {
			return "<span style='color:white; padding:3px; background:#e4c25a; font-weight:700; display:inline-block;  text-align:center'>$s</span>";
		}
	
	}
	
	public function getStatus($general = true, $line = 1){
		$login = $this->login;
		$password = $this->password;
		$server = $this->server;
		$timeout = $this->timeout;
		$retries = $this->retries;
		
	
		if (isset($this->request->get['line'])){
			$line = (int)$this->request->get['line'];
		}
		
		if (isset($this->request->get['general'])){
			$general = (int)$this->request->get['general'];
		}
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "http://$login:$password@$server/default/en_US/status.xml?u=$login&p=$password");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_POST, false);
		$out = curl_exec($curl);
		curl_close($curl);

		$a = xml2array($out, 0);
		
		if (isset($a["status"])){
			
			if ($general){
				
				$result = "";
				
				for ($i = 1; $i <= 4;  $i++ ){
					
					if (isset($a["status"]["l$i"."_gsm_cur_oper"]) && $a["status"]["l$i"."_gsm_cur_oper"]){
						$result .= 	" Line $i: " . $this->yesNoStyle($a["status"]["l$i"."_gsm_cur_oper"]) . $this->yesNoStyle($a["status"]["l$i"."_gsm_sim"]) . $this->yesNoStyle($a["status"]["l$i"."_gsm_status"]) . $this->yesNoStyle($a["status"]["l$i"."_status_line"]);
					} else {
						$result .= 	" Line $i: " . $this->yesNoStyle($a["status"]["l$i"."_gsm_sim"]) . $this->yesNoStyle($a["status"]["l$i"."_gsm_status"]) . $this->yesNoStyle($a["status"]["l$i"."_status_line"]);
					}
				
				
				}
				
				echo $result;
				
			} else {
						
			}
			
		} else {
			echo "<span style='color:white; padding:3px; background:red; font-weight:700; display:inline-block; text-align:center'>ошибка подключения</span>";
		}		
	}
	
	private function sendUSSD($ussd, $line){	
		$login 		= $this->login;
		$password 	= $this->password;
		$server 	= $this->server;
		$timeout 	= $this->timeout;
		$retries 	= $this->retries;
		
		//send
		$mid=str_pad(rand(1,10000),8,"0",STR_PAD_LEFT); 
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
		if (isset($this->request->get['line'])){
			$line = (int)$this->request->get['line'];
		}

		$json = false;
		if (isset($this->request->get['json'])){
			$json = (int)$this->request->get['json'];
		}

		$ussd = urlencode("*111#");		
		
		if ($responce = $this->sendUSSD($ussd, $line)) {
		//Na rahunku 45.00 grn. Detalno pro bonusy za nomerom *100# Novi igry v AppClub! SMS na 8870. 3 dni po 0 grn, dali 0,62 grn/den'
		
			$responce = str_replace('Na rahunku ','', $responce);
			$responce = explode('grn.', $responce);
			$responce = (int)trim($responce[0]);
		
			if ($responce < 20){
				echo "<span style='color:white; padding:3px; background:red; font-weight:700; display:inline-block; text-align:center'>$responce грн.</span>";
			} elseif ($responce < 50){
				echo "<span style='color:white; padding:3px; background:orange; font-weight:700; display:inline-block; text-align:center'>$responce грн.</span>";
			} else {
				echo "<span style='color:white; padding:3px; background:green; font-weight:700; display:inline-block; text-align:center'>$responce грн.</span>";
			}
		
		} else {
		
			echo "<span style='color:white; padding:3px; background:red; font-weight:700; display:inline-block; text-align:center'>ошибка получения баланса</span>";
		
		}
	
	}
	
	public function getVodafoneBalance($line = 2){
		if (isset($this->request->get['line'])){
			$line = (int)$this->request->get['line'];
		}		

		$json = false;
		if (isset($this->request->get['json'])){
			$json = (int)$this->request->get['json'];
		}

		$ussd = urlencode("*101#");

		
		if ($responce = $this->sendUSSD($ussd, $line)){
		//Na Vashem schete 81.00 grn. Tarif 'Vodafone Light+'. Nomer deystvitelen do 27.03.2018. Super Internet za 1,10 grn/den': *101*
		
			$responce = str_replace('Na Vashomu rahunku ','', $responce);
			#$responce = str_replace('Na Vashem schete ','', $responce); # Old style, was in russian lang, 22.02.2022 ukr lang
			$responce = explode('grn.', $responce);
			$responce = (int)trim($responce[0]);
			
			if ($responce < 20){
				echo "<span style='color:white; padding:3px; background:red; font-weight:700; display:inline-block; text-align:center'>$responce грн.</span>";
			} elseif ($responce < 50){
				echo "<span style='color:white; padding:3px; background:orange; font-weight:700; display:inline-block; text-align:center'>$responce грн.</span>";
			} else {
				echo "<span style='color:white; padding:3px; background:green; font-weight:700; display:inline-block; text-align:center'>$responce грн.</span>";
			}
			
		} else {
		
			echo "<span style='color:white; padding:3px; background:red; font-weight:700; display:inline-block; text-align:center'>ошибка получения баланса</span>";
		
		}
				
	}
	
	
}
