<?

class ControllerUserUserAlerts extends Controller {
    private $error = array();

    public function index() {
        $this->language->load('user/user');

        $this->document->setTitle('Мои уведомления');

        $this->load->model('user/user');
        $this->load->model('user/user_group');

        $this->getList();
    }
	
	
	protected function getList(){
		
		$user_id = $this->user->getID();
		$user = $this->model_user_user->getUser($user_id);
		
		if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
		
		$this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
		
		$this->data['breadcrumbs'][] = array(
            'text'      => 'Мои уведомления',
            'href'      => $this->url->link('user/user_alerts', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
		
		$this->data['heading_title'] = 'Мои уведомления: '.$user['firstname'].' '.$user['lastname'].'';
		
		
		$results = $this->model_user_user->getUserAlerts();
		
		
		$this->data['user_alerts'] = array();
		foreach ($results as $alert){
			
			if ($alert['entity_type'] == 'order'){
				
				$order_id = (int)$alert['entity_id'];
				$url = str_replace('&amp;','&',$this->url->link('sale/order/update', 'order_id='.(int)$order_id.'&token='.$this->session->data['token']));							
				
			}  elseif ($alert['entity_type'] == 'inboundcall') {
				
				$customer_id = (int)$alert['entity_id'];
				
				if ($customer_id) {				
					$alert['url'] = str_replace('&amp;','&',$this->url->link('sale/customer/update', 'customer_id='.(int)$customer_id.'&token='.$this->session->data['token']));
				}		
			
			}  elseif ($alert['entity_type'] == 'missedcall') {
				
				$url = str_replace('&amp;','&',$this->url->link('sale/callback', '&token='.$this->session->data['token']));											
				
			} else {
				$url = false;
			}
			
			$this->data['user_alerts'][]  = array(
				'alertlog_id' 	=> $alert['alertlog_id'],
				'type' 			=> $alert['alert_type'],
				'entity_type' 	=> $alert['entity_type'],
				'entity_id' 	=> $alert['entity_id'],
				'text' 			=> $alert['alert_text'],
				'datetime' 		=> date('Y.m.d' , strtotime($alert['datetime'])).'<br />'.date('H:i:s' , strtotime($alert['datetime'])),
				'url' 			=> $url				
			);
		}
		
		if (isset($this->request->get['ajax']) && ($this->request->get['ajax'] == 1)){
			$this->template = 'user/user_alerts_ajax.tpl';
		} else {		
			$this->template = 'user/user_alerts.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}

		$this->response->setOutput($this->render());
		
	}
}