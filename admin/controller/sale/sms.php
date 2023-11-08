<?

class ControllerSaleSMS extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('sale/contact');		
		
		$this->load->model('setting/setting');
		$this->load->model('setting/store');

		$this->document->setTitle('SMS');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		$this->data['sms_alfas'] = array();
				
		
		foreach ($this->data['stores'] as $store){
			$alfa = $this->model_setting_setting->getKeySettingValue('config', 'config_sms_sign', $store['store_id']);
			
			$this->data['sms_alfas'][] = array(
				'store_id' => $store['store_id'],
				'name' => $store['name'],
				'alfa' => $alfa,
			);
			
		}
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => 'SMS',
			'href'      => $this->url->link('sale/sms', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
		);

		$this->data['cancel'] = $this->url->link('sale/sms', 'token=' . $this->session->data['token']);
		
		$this->load->model('sale/sms');
		
		$this->data['log'] = $this->model_sale_sms->getLog();
		
		
		$this->template = 'sale/sms.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		
	}
	
	
	public function send(){
	
		$this->load->model('sale/sms');
	
		$json = array();
	
		if ($this->request->post['phones']){
			$phones = explode(',', $this->request->post['phones']);
		} else {
			$json['error']['phones'] = 'Укажите телефон(ы), пожалуйста';
		}
		
		if (!$this->request->post['message']) {
				$json['error']['message'] = 'Введите сообщение, пожалуйста';
			}
	
		foreach ($phones as $phone){
			
			if ($phone[0] == '+'){
				$phone = substr($phone, 1);			
			}
			$phone = '+' . preg_replace("/\D+/", "", $phone);
			
			$options = array(
					'to'       => $phone,
					'from'     => $this->request->post['alfa'],				
					'message'  => $this->request->post['message']
			);
			$this->smsQueue->queue($options);
		
	
				usleep(100000);		
		}
		$json['success'] = 'Отправили SMS на '.count($phones).' номеров';
		$this->model_sale_sms->logSMS($phone,$this->request->post['message']);
		
						
		$this->response->setOutput(json_encode($json));	
	}
	
}