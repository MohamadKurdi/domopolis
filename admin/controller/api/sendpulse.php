<?
class ControllerApiSendPulse extends Controller { 
	private $sendPulse;	
	
	public function __construct($registry){
		
		parent::__construct($registry);
		
		require_once( DIR_SYSTEM . 'library/sendpulse/sendpulseInterface.php' );
		require_once( DIR_SYSTEM . 'library/sendpulse/sendpulse.php' );
				
		$this->sendPulse = new SendpulseApi( SENDPULSE_ID, SENDPULSE_KEY, 'memcache' );
	}
	
	
	public function index(){
		
		
		
		
	}
	
	public function connectCustomers(){
		$this->load->model('setting/store');
		$this->load->model('setting/setting');
		
		$stores = $this->model_setting_store->getStores();
		$stores[] = array(
			'store_id' => 0
		);
		
		$sendpulse_ids = array();
		foreach ($stores as $store){
			
			if ($sendpulse_key = $this->model_setting_setting->getKeySettingValue('config', 'config_sendpulse_id', $store['store_id'])) {
			
				$sendpulse_ids[] = array(
					'sendpulse_key' => $sendpulse_key,
					'store_id'      => $store['store_id']
				);
			
			}
		}
		
		$this->db->query("DELETE FROM customer_push_ids WHERE 1");	
		
		foreach ($sendpulse_ids as $sendpulse_store){
			
			$result = $this->sendPulse->pushCountWebsiteSubscriptions( $sendpulse_store['sendpulse_key'] );
			$total = (int)$result->total;
			
			if ($total > 0) {
			
				if ($total > 30) {
					$index = (int)($total / 30);
				} else {
					$index = 0;
				}
						

				for ($i=0; $i<=$index; $i++){								
				
					$start = $i * 30;
					$subscribers = $this->sendPulse->pushListWebsiteSubscriptions($sendpulse_store['sendpulse_key'], 30, $start);
					
					foreach ($subscribers as $subscriber){
						
						if (is_object($subscriber)){
							
							if (isset($subscriber->variables) && isset($subscriber->variables->customer_id) && (int)$subscriber->variables->customer_id > 0 && $subscriber->status == 1){
								
								$customer_id = (int)$subscriber->variables->customer_id;
								$this->db->query("INSERT INTO customer_push_ids SET customer_id = '" . $customer_id . "', sendpulse_push_id = '" . $this->db->escape($subscriber->id) . "'");
								
							}
							
							
						}
						
						
					}
				}
			}	
		}
		
	
	}
	
}