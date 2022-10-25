<?
	
	class ControllerUserWork extends Controller {
		
		public function index() {
			$this->language->load('user/user');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->getList();
		}
		
		private function getList(){
			$this->document->setTitle('Учет рабочего времени и действий менеджеров');
			$this->data['heading_title'] = 'Учет рабочего времени и действий менеджеров';
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('user/user');
			$this->load->model('kp/work');
			$this->load->model('user/user_group');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/work', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);
			
			if (empty($this->request->get['period'])){
				$period = date('Y-m-d');				
				} else {
				$period = date('Y-m-d', strtotime($this->request->get['period']));
			}
			
			$this->data['current_period'] = $period;
			
			$this->data['periods'] = array();
			
			for ($i = 0; $i <= 10; $i++) {
				
				switch ($i) {
					
					case 0 : $_name = 'Сегодня';break;
					case 1 : $_name = 'Вчера';break;
					case 2 : $_name = 'Позавчера';break;
					
					default : $_name = date('d.m', strtotime("-$i day"));break;
				}
				
				$this->data['periods'][] = array(
				'name' => $_name,
				'date' =>  date('Y-m-d', strtotime("-$i day")),
				'href' => $this->url->link('user/work', 'period='. date('Y-m-d', strtotime("-$i day")) .'&token=' . $this->session->data['token'], 'SSL'),
				);				
			}
			
			$results = $this->model_kp_work->getAllUserStatsForDate($period);
			
			$this->data['stats'] = array();
			
			
			foreach ($results as $result){
			
				if (!$this->user->getIsAV()){
					
					if ($this->user->getIsMM()){
					
						if ($result['user_group_id'] != 12) {
							continue;
						}
						
					} elseif ($this->user->getID() != $result['user_id']) {
						continue;
					}
				}
				
				if (!isset($this->data['stats'][$result['group_name']])){
					$this->data['stats'][$result['group_name']] = array();					
				}
												
				$this->data['stats'][$result['group_name']][] = array(
				'user_name' 				=> $this->model_user_user->getRealUserNameById($result['user_id']),
				'user_id'   				=> $result['user_id'],
				'inbound_call_count' 		=> $result['inbound_call_count'],
				'inbound_call_duration' 	=> secToHR($result['inbound_call_duration']),
				'outbound_call_count' 		=> $result['outbound_call_count'],
				'outbound_call_duration' 	=> secToHR($result['outbound_call_duration']),
				'owned_order_count' 		=> $result['owned_order_count'],
				'edit_order_count' 			=> $result['edit_order_count'],
				'edit_csi_count' 			=> $result['edit_csi_count'],
				'edit_birthday_count' 		=> $result['edit_birthday_count'],
				'edit_customer_count' 		=> $result['edit_customer_count'],
				'sent_mail_count' 			=> $result['sent_mail_count'],
				'worktime_start' 			=> $result['worktime_start'],
				'worktime_finish' 			=> $result['worktime_finish'],
				'daily_actions' 			=> $result['daily_actions'],
				'problem_order_count' 		=> $result['problem_order_count'],
				'success_order_count' 		=> $result['success_order_count'],
				'cancel_order_count' 		=> $result['cancel_order_count'],
				'treated_order_count' 		=> $result['treated_order_count'],
				'confirmed_order_count' 	=> $result['confirmed_order_count']
				);
				
				
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'user/user_work_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
		}
		
	}
