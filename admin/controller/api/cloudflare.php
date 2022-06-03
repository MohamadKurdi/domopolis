<?

class ControllerApiCloudFlare extends Controller { 
	private $cf;	
	private $email = 'it@vialex.de';
	private $key = 'cfb04f55dc2bd4b9c87823a2f56aa2d3cc404';
	
	public function index(){
		
	}
	
	public function getStats(){
		require_once(DIR_SYSTEM . 'library/CloudFlare.php');
		$this->cf = new cloudflare_api($this->email, $this->key);
		
		$domain = $this->request->get['domain'];
		
		$stats = $this->cf->stats($domain, 40);
		
		var_dump($stats);
			
		if (isset($stats['response'])){
		
			echo 'Страниц: '. $stats['response']['result']['objs'][0]['trafficBreakdown']['pageviews']['regular'] .', Запросов: '. $stats['response']['result']['objs'][0]['requestsServed']['cloudflare'] .' / '. $stats['response']['result']['objs'][0]['requestsServed']['user'];
		 
		} else {
			
			echo "Ошибка соединения с CloudFlare";
			
		}
		
	}
	
	public function purgeCache(){
		require_once(DIR_SYSTEM . 'library/CloudFlare.php');
		$this->cf = new cloudflare_api($this->email, $this->key);
		
		$domain = $this->request->get['domain'];
		$this->cf->fpurge_ts($domain);
		
		$this->load->model('user/user');					
		$name = $this->model_user_user->getRealUserNameById($this->user->getID());
		
		$text = $name . '<br />очистил кэш CloudFlare для домена<br />'.$domain.'!';
		
		$data = array(
						'type' => 'error',
						'text' => $text, 
						'entity_type' => '', 
						'entity_id' => 0
		);
			
		$this->mAlert->insertAlertForGroup('admins', $data);
		
		
	}
	
	public function setDevMode(){
		require_once(DIR_SYSTEM . 'library/CloudFlare.php');
		$this->cf = new cloudflare_api($this->email, $this->key);		
		
		$domain = $this->request->get['domain'];
		$mode = $this->request->get['mode'];
		
		$stats = $this->cf->devmode($domain, $mode);
		
		$this->load->model('user/user');					
		$name = $this->model_user_user->getRealUserNameById($this->user->getID());
		
		if ($mode){
			$text = $name . '<br />включил режим разработчика для домена<br />'.$domain.'!';
		} else {
			$text = $name . '<br />отключил режим разработчика для домена<br />'.$domain.'!';
		}
		
		$data = array(
						'type' => 'warning',
						'text' => $text, 
						'entity_type' => '', 
						'entity_id' => 0
		);
			
		$this->mAlert->insertAlertForGroup('admins', $data);
	}
	
	public function getDevMode(){
		require_once(DIR_SYSTEM . 'library/CloudFlare.php');
		$this->cf = new cloudflare_api($this->email, $this->key);		
		
		$domain = $this->request->get['domain'];
		
		$stats = $this->cf->stats($domain);
		
	//	echo(json_encode($stats));
				
		if (isset($stats['response']['result']['objs'][0]['dev_mode']) && $stats['response']['result']['objs'][0]['dev_mode'] > 0){
			echo "<span style='color:white; padding:3px; background:#e4c25a; font-weight:700; display:inline-block; text-align:center'>вкл</span>";				
		} else {
			echo "<span style='color:white; padding:3px; background:#4ea24e; font-weight:700; display:inline-block; text-align:center'>выкл</span>";
		}
		
	}
}
?>