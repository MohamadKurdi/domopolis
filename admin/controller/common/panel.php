<?php   
class ControllerCommonPanel extends Controller {   
	public function index() {
		$this->language->load('common/home');

		$this->document->setTitle('Панель мониторинга');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => 'Панель магазина',
			'href'      => $this->url->link('common/panel', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);



		$this->load->model('user/user');
		$this->data['managers'] = $this->model_user_user->getUsersByGroups(array(12, 19), true);		

		$this->data['token'] = $this->session->data['token'];			

		$this->template = 'common/panel2.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());			
	}

	public function getPricevaInfo(){
		$store_id = (int)$this->request->get['store_id'];
		$body = '';
		$class= 'good';

		if ($this->config->get('config_priceva_api_key_' . $store_id)){
			try{

				$priceva = new Priceva\PricevaAPI($this->config->get('config_priceva_api_key_' . $store_id));
				$result = $priceva->main_ping();

				if ($result->get_result()){
					$body = 'OK';
					$class= 'good';
				} else {
					$body = $result->get_errors();
					$class = 'bad';

				}

			} catch (\Priceva\PricevaException $e) {
				$body = $e->getMessage();
				$class = 'bad';
			}



		}  else {

			$body = 'OFF';
			$class= 'warn';

		}


		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));	
	}

	public function getJustinInfo(){
		$body = '';
		$class= 'good';

		$this->load->library('hobotix/Shipping//Justin');
		$Justin = new \hobotix\shipping\Justin($this->registry, 'UA');

		if ($this->config->get('config_justin_api_key')){
			if ($result = $Justin->checkStatus()){

				$body = $result;
				$class= 'good';

			} else {

				$body = 'FAIL';
				$class= 'bad';

			}	
		} else {
			$body = 'OFF';
			$class= 'warn';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));
	}

	public function getCDEKInfo(){
		$body = '';
		$class= 'good';

		$this->load->library('hobotix/Shipping/Cdek');
		$Cdek = new \hobotix\shipping\Cdek($this->registry);

		if ($result = $Cdek->checkStatus()){

			$body = $result;
			$class= 'good';

		} else {

			$body = 'FAIL';
			$class= 'bad';

		}	

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));
	}


	public function getNovaPoshtaInfo(){
		$body = '';
		$class= 'good';

		$this->load->library('hobotix/Shipping/NovaPoshta');
		$novaPoshta = new \hobotix\shipping\NovaPoshta($this->registry);	

		if ($result = $novaPoshta->checkStatus()){

			$body = $result;
			$class= 'good';

		} else {

			$body = 'FAIL';
			$class= 'bad';

		}	

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));
	}

	public function pingAPI(){
		$json = $this->PageCache->pingAPI();	

		$this->response->setOutput(json_encode($json));
	}


	public function getServerResponceTime(){
		$json = $this->PageCache->getServerResponceTime();	

		$this->response->setOutput(json_encode($json));
	}

	public function getSparkPostInfo(){
		$body = '';
		$class= 'good';


		if ($this->config->get('config_sparkpost_api_key')){
			
			try{
				$httpClient = new \Http\Adapter\Guzzle6\Client(new \GuzzleHttp\Client());
				$sparkPost = new \SparkPost\SparkPost($httpClient, ['key' => $this->config->get('config_sparkpost_api_key'), 'host' => $this->config->get('config_sparkpost_api_url'), 'async' => false]);
				$result = $sparkPost->request('GET', 'account')->getBody();
				$result = $result['results'];	

				if ($result && !empty($result['status']) && !empty($result['subscription'])){

					$body = $result['status'] . ', LIM: ' . $result['subscription']['plan_volume'] . '';
					$class= 'good';

				} else {

					$body = 'FAIL';
					$class= 'bad';

				}				

			} catch (\Exception $e) {
				$body = $e->getMessage();
				$class = 'bad';
			}

		}  else {

			$body = 'OFF';
			$class= 'warn';

		}


		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));	
	}

	public function getMailGunInfo(){
		$body = '';
		$class= 'good';

		if ($this->config->get('config_mailgun_bounce_enable') && $this->config->get('config_mailgun_api_private_key')){

			try{
				$configurator = new \Mailgun\HttpClient\HttpClientConfigurator();
				$configurator->setApiKey($this->config->get('config_mailgun_api_private_key'));
				$configurator->setEndpoint($this->config->get('config_mailgun_api_url'));

				$mgClient = new \Mailgun\Mailgun($configurator, new \Mailgun\Hydrator\ArrayHydrator());
				$result = $mgClient->domains()->index();
	
				$found = false;				

				foreach ($result['items'] as $item){
					if ($item['name'] == $this->config->get('config_mailgun_api_transaction_domain')){
						$body  = $item['name'];
						$class = 'good';
						$found = true;
					}
				}

				if (!$found){
					$body  = 'FAIL';
					$class = 'bad';
				}

			} catch (\Mailgun\Exception $e) {
				$body = $e->getMessage();
				$class = 'bad';
			}

		} else {

			$body = 'OFF';
			$class= 'warn';

		}


		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));	

	}


	public function getMailWizzInfo(){
		$body = '';
		$class= 'good';

		if ($this->config->get('config_mailwizz_enable')){

			$config = new \EmsApi\Config([
				'apiUrl'    => $this->config->get('config_mailwizz_api_uri'),
				'apiKey'    => $this->config->get('config_mailwizz_api_key'),
				'components' => ['cache' => [
					'class'     => \EmsApi\Cache\File::class,
					'filesPath' => DIR_CACHE
				]]]);

			\EmsApi\Base::setConfig($config);

			$endpoint = new \EmsApi\Endpoint\Campaigns();
			$response = $endpoint->getCampaigns(1, 2);
			if ($response && $response->body){
				if (!empty($response->body['status']) && $response->body['status'] == 'success'){
					$body = $response->body['data']['count'] . ' рассылок';
					$class= 'good';
				} else {
					$body = $response->body['status'];
					$class= 'bad';
				}

			} else {
				$body = 'FAIL: ERR';
				$class= 'bad';
			}


		} else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));				
	}


	public function getYandexTranslateInfo(){
		$body = '';
		$class= 'good';

		if ($this->config->get('config_yandex_translate_api_enable')){

			try {
				$cloud = Panda\Yandex\TranslateSdk\Cloud::createApi($this->config->get('config_yandex_translate_api_key'));	

				$translate = new Panda\Yandex\TranslateSdk\Translate('привет');
				$translate->setSourceLang('ru')->setTargetLang('uk')->setFormat(Panda\Yandex\TranslateSdk\Format::PLAIN_TEXT);
				$result = $cloud->request($translate);

				$json = json_decode($result, true);
				if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){
					$body = $json['translations'][0]['text'];					
				} else {

					$class = 'bad';
					$body = 'FAIL: ERR';
					if (!empty($json['message'])){
						$body = $json['message'];
					}										
				}

			} catch (Panda\Yandex\TranslateSdk\Exception\ClientException $e) {
				$body = $e->getMessage();
				$class = 'bad';
			}

		} else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function getLDAPInfo(){
		$body = '';
		$class= 'good';


		if ($this->config->get('config_ldap_auth_enable')){
			
			$connection = @fsockopen($this->config->get('config_ldap_host'), 3268, $error, $error_msg, 3);

			if (is_resource($connection)){					
				fclose($connection);
				$ldap = @ldap_connect($this->config->get('config_ldap_host'), 3268);					
				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);

				//getCurrentUserPassword
				$password = base64_decode($this->request->cookie[AUTH_PASSWD_COOKIE]);
				$password = str_replace(AUTH_PASSWDSALT_COOKIE, '', $this->data['password']);

				if($bind = @ldap_bind($ldap, $this->user->getUserName() . '@' . $this->config->get('config_ldap_domain'), $password)){
					$body = 'AUTH OK';
					$class= 'good';					
				} else {
					$body = 'AUTH FAIL';
					$class= 'warn';	
				}

			} else {

				$body = $error_msg;
				$class= 'bad';

			}

		}   else {

			$body = 'OFF';
			$class= 'warn';

		}


		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function getGoip4BalanceUSSD(){
		$body = '';
		$class= 'good';

		$line = 1;
		if (!empty($this->request->get['x'])){
			$line = (int)$this->request->get['x'];
		}

		$this->load->library('hobotix/GoIP4');
		$goIP4 = new \hobotix\GoIP4($this->registry);

		if (method_exists($goIP4, $this->config->get('config_goip4_simnumber_checkfunction_' . $line))){

			$balance = $goIP4->{$this->config->get('config_goip4_simnumber_checkfunction_' . $line)}($line);

			if ($balance === null){

				$body = 'USSD FAIL';
				$class= 'warn';

			} else {
				$body = $balance;

				if ($balance < 50){
					$class = 'warn';
				}

				if ($balance < 10){
					$class = 'bad';
				}

			}
		} else {
			$body = 'NO METHOD';
			$class = 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function getGoip4Info(){
		$body = '';
		$class= 'good';

		$login = $this->config->get('config_goip4_user');
		$password = $this->config->get('config_goip4_passwd');
		$server = $this->config->get('config_goip4_uri');

		if ($server){

			try{

				$httpClient = new GuzzleHttp\Client();
				$httpResponce = $httpClient->request('GET', "http://$login:$password@$server/default/en_US/status.xml?u=$login&p=$password", ['timeout' => 10]);			 	

				if ($httpResponce->getStatusCode() == 200 && $a = xml2array($httpResponce->getBody(), 0)){

					if (isset($a["status"])){
						$body = '';			
						for ($i = 1; $i <= $this->config->get('config_goip4_simnumber');  $i++ ){
							$body .= ($a["status"]["l$i"."_gsm_sim"] . $a["status"]["l$i"."_gsm_status"] . $a["status"]["l$i"."_status_line"]);

							if ($a["status"]["l$i"."_gsm_sim"] != 'Y' || $a["status"]["l$i"."_gsm_status"] != 'Y' || $a["status"]["l$i"."_status_line"] != 'Y'){
								$class = 'bad';
							}
						}
					} else {

						$body = 'FAIL';
						$class= 'bad';

					}

				} else {

					$body = 'FAIL: ' . $httpResponce->getStatusCode();
					$class= 'bad';

				}

			} catch (\GuzzleHttp\Exception\ConnectException $e){

				$body = $e->getMessage();
				$class = 'bad';

			}

		} else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));
	}

	public function get1СInfo(){
		$body = '';
		$class= 'good';

		$this->load->model('kp/info1c');
		$query = $this->db->query("SELECT MAX(product_id) as product_id FROM product WHERE quantity_stockK > 0");		

		try {

			$result = $this->model_kp_info1c->ping1CToUpdateProducts([$query->row['product_id']]);
			if ($result && $result->return){
				$body = $result->return;
				$class= 'good';
			} else {
				$body = 'FAIL';
				$class= 'bad';
			}

		}  catch (Exception $e){
			$body = $e->getMessage();
			$class= 'bad';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function getReacherInfo(){
		$body = '';
		$class= 'good';

		if ($this->config->get('config_reacher_enable')){
			
			$json = $this->emailBlackList->reacherVerify($this->config->get('config_email'));

			$result = json_decode($json, true);

			if ($result && !empty($result['mx']) && !empty($result['mx']['accepts_mail'])){
				$body = 'accepts_mail';
				$class = 'good';
			} else {
				$body = 'ERR';
				$class= 'bad';
			}

		}   else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));	
	}

	public function getIpapiInfo(){
		$body = '';
		$class= 'good';


		$ipapi = new maciejkrol\ipapicom\ipapi ($this->config->get('config_ip_api_key'));
		$result = $ipapi->locate ($this->request->server['REMOTE_ADDR']);

		if (!empty($result['city'])){
			$body = $result['city'];
			$class= 'good';
		} else {
			$body = 'FAIL';
			$class= 'bad';
		}


		$json = [
		'body'  	=> $body,
		'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));	


	}

	public function getElasticInfo(){
		$body = '';
		$class= 'good';

		$this->load->library('hobotix/ElasticSearch');

		try{
			$elasticSearch = new \hobotix\ElasticSearch($this->registry, true);

			if ($elasticSearch){
				$field = $elasticSearch->buildField('name');
				$field2 = $elasticSearch->buildField('names');
				$field3 = $elasticSearch->buildField('description');

				$product_total = $elasticSearch->fuzzyP('products' . $this->config->get('config_elasticsearch_index_suffix'), 'тарелка', $field, $field2, $field3, ['getTotal' => true]);

				$body =  $product_total;

				if ($product_total){					
					$class= 'good';
				} else {
					$class= 'warn';
				}
			}

		} catch (Exception $e){
			$body = $e->getMessage();
			$class= 'bad';
		}

		$json = [
		'body'  	=> $body,
		'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));	


	}

	public function getAsteriskInfo(){
		$body = '';
		$class= 'good';

		if ($this->config->get('config_asterisk_ami_host')){

			$socket = fsockopen($this->config->get('config_asterisk_ami_host'), "5038", $errno, $errstr, 10);

			if (!$socket){

				$body = $errstr;
				$class= 'bad';

			} else {

				fputs($socket, "Action: Login\r\n");
				fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
				fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");

				$status = '';
				$status .= fgets($socket);

				$body = $status;
				$class= 'good';

				fclose($socket);
				

			}

		} else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));	
	}

	public function getYandexAPIInfo(){

		$body = '';
		$class= 'good';

		if ($this->config->get('config_yam_fbs_campaign_id')){

			try{

				$StatsClient = new \Yandex\Marketplace\Partner\Clients\StatsClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

			if ($statsResponse = $StatsClient->getStatsByOrders($this->config->get('config_yam_fbs_campaign_id'), ['limit' => 1])){
				
				foreach ($statsResponse->getResult()->getOrders() as $statsOrder){
					$body = $statsOrder->getStatus();
					$class = 'good';
					break;
				}

			}

		} catch (\Yandex\Marketplace\Partner\Exception\PartnerRequestException $e){

			$body = 'FAIL: ERR ' . $e->getMessage();
			$class= 'bad';

		}

	}  else {

		$body = 'OFF';
		$class= 'warn';

	}

	$json = [
		'body'  	=> $body,
		'class' 	=> $class,
	];

	$this->response->setOutput(json_encode($json));	


}

public function getDadataInfo(){
	$body = 'OK';
	$class = 'good';

	try{

		$dadata = new \Dadata\DadataClient($this->config->get('config_dadata_api_key'), $this->config->get('config_dadata_secret_key'));
		$result = $dadata->getDailyStats();

		if (isset($result['services']) && isset($result['services']['suggestions'])){

			$body = (int)$result['services']['suggestions'] . 'Q';
			$class = 'good';

		} else {

			$body = 'FAIL: ERR';
			$class = 'bad';

		}

	} catch (\GuzzleHttp\Exception\ConnectException $e){

		$body = $e->getMessage();
		$class = 'bad';

	}

	

	$json = [
		'body'  	=> $body,
		'class' 	=> $class,
	];

	$this->response->setOutput(json_encode($json));	

}



public function getRainforestInfo(){
	$body = '';
	$class= 'good';

	if ($this->config->get('config_rainforest_enable_api')){

		$result = $this->rainforestAmazon->checkIfPossibleToMakeRequest(true);

		if ($result['status'] === true){
			$body = 'OK';
			$class= 'good';
		} else {
			$body = $result['message'];
			$class= 'bad';
		}

	} else {

		$body = 'OFF';
		$class= 'warn';

	}

	$json = [
		'body'  	=> $body,
		'class' 	=> $class,
	];

	$this->response->setOutput(json_encode($json));	
}

public function getRedisInfo(){
	$json = $this->PageCache->getRedisInfo();

	$this->response->setOutput(json_encode($json));			
}

public function getPageCacheInfo(){
	$json = $this->PageCache->getPageCacheInfo();

	$this->response->setOutput(json_encode($json));
}


public function getZadarmaBalance(){						
	$key = $this->config->get('config_zadarma_api_key');
	$secret = $this->config->get('config_zadarma_secret_key');

	$zd = new \Zadarma_API\Api($key, $secret);
	$answerObject = $zd->getBalance();


	if ($answerObject->balance) {
		$body = (int)$answerObject->balance . ' ' . $answerObject->currency;
		$class = 'good';

		if ((int)$answerObject->balance < 10){
			$class = 'bad';
		}

		if ((int)$answerObject->balance > 10 && (int)$answerObject->balance < 30){
			$class = 'warn';
		}

	} else {
		$body = 'FAIL: ERR';
		$class='bad';
	}

	$json = [
		'body'  	=> $body,
		'class' 	=> $class
	];

	$this->response->setOutput(json_encode($json));
}



public function getEpochtaBalance(){

	$ePochta = new \Enniel\Epochta\SMS(['public_key' => $this->config->get('config_smsgate_secret_key'), 'private_key' => $this->config->get('config_smsgate_api_key')]);

	$result = $ePochta->getUserBalance()->getBody()->getContents();	    
	$result = json_decode($result, true);
	if ($result){

		$body = (int)$result['result']['balance_currency'] . ' ' . $result['result']['currency'];
		$class= 'good';

		if ((float)$result['result']['balance_currency'] < 300){
			$class = 'warn';
		}

		if ((float)$result['result']['balance_currency'] < 100){
			$class = 'bad';
		}

	} else {

		$body = 'FAIL: ERR';
		$class='bad';

	}

	$json = [
		'body'  	=> $body,
		'class' 	=> $class,
	];

	$this->response->setOutput(json_encode($json));

}

}				