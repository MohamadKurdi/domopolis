<?
	
	class ControllerUserContent extends Controller {
		
		public function index() {
			$this->language->load('user/user');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->getList();
		}



		private function getList(){
			$this->document->setTitle('Учет рабочего времени и работы контентов');
			$this->data['heading_title'] = 'Учет рабочего времени и работы контентов';
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('user/user');
			$this->load->model('kp/work');
			$this->load->model('kp/content');
			$this->load->model('user/user_group');


			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);

			if (!empty($this->request->get['date_from'])){
				$date_from = date('Y-m-d', $this->request->get['date_from']);				
				} else {
				$date_from = date('Y-m-d', strtotime('first day of this month'));	
			}

			if (!empty($this->request->get['date_to'])){
				$date_to = date('Y-m-d', $this->request->get['date_to']);				
				} else {
				$date_to = date('Y-m-d', strtotime('first day of this month'));	
			}

			if (!empty($this->request->get['period'])){
				$period = $this->request->get['period'];				
				} else {
				$period = 'month';	
			}

			$this->data['periods'] 			= [];
			$this->data['current_period']  	= $period;	

			$this->data['periods']['month'] = [				
				'date_from' => date('Y-m-d', strtotime('first day of this month')),
				'date_to' 	=> date('Y-m-d'),
				'href' 		=> $this->url->link('user/content', 'period=month' .'&token=' . $this->session->data['token']), 	
				'name' 		=> 'С начала месяца' 				
			];

			$this->data['periods']['prev_month'] = [				
				'date_from' => date('Y-m-d', strtotime('first day of previous month')),
				'date_to' 	=> date('Y-m-d', strtotime('last day of previous month')),
				'href' 		=> $this->url->link('user/content', 'period=prev_month' .'&token=' . $this->session->data['token']), 
				'name' 		=> 'За прошлый месяц' 				
			];

			$this->data['periods']['week'] = [				
				'date_from' => date('Y-m-d', strtotime('last Monday')),
				'date_to' 	=> date('Y-m-d'),
				'href' 		=> $this->url->link('user/content', 'period=week' .'&token=' . $this->session->data['token']), 
				'name' 		=> 'С начала недели' 				
			];

			$this->data['periods']['prev_week'] = [				
				'date_from' => date('Y-m-d', strtotime('last Monday -1 week')),
				'date_to' 	=> date('Y-m-d', strtotime('last Sunday')),
				'href' 		=> $this->url->link('user/content', 'period=prev_week' .'&token=' . $this->session->data['token']), 
				'name' 		=> 'За прошлую неделю' 				
			];

			$this->data['periods']['today'] = [				
				'date_from' => date('Y-m-d'),
				'date_to' 	=> date('Y-m-d'),
				'href' 		=> $this->url->link('user/content', 'period=today' .'&token=' . $this->session->data['token']), 
				'name' 		=> 'Сегодня' 				
			];

			$this->data['periods']['yesterday'] = [				
				'date_from' => date('Y-m-d', strtotime('-1 day')),
				'date_to' 	=> date('Y-m-d', strtotime('-1 day')),
				'href' 		=> $this->url->link('user/content', 'period=yesterday' .'&token=' . $this->session->data['token']), 
				'name' 		=> 'Вчера' 				
			];

			$filter_data = [
				'date_from' => $this->data['periods'][$period]['date_from'],
				'date_to' 	=> $this->data['periods'][$period]['date_to']
			];

			$this->data['filter_data'] = $filter_data;

			$users = $this->model_kp_content->getDistinctUsers($filter_data);
			$this->data['users'] = [];

			foreach ($users as $user){
				$this->data['users'][] = [
					'user_id' 		=> $user['user_id'],
					'name' 			=> $this->model_user_user->getRealUserNameById($user['user_id']),
					'product'  		=> $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'product'])),
					'category'  	=> $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'category'])),
					'manufacturer'  => $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'manufacturer'])),
					'collection'  	=> $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'collection'])),
					'information'  	=> $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'information'])),
					'landingpage'  	=> $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'landingpage'])),
					'attribute'  	=> $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'attribute'])),
					'option'  		=> $this->model_kp_content->getUserStats($user['user_id'], array_merge($filter_data, ['entity_type' => 'option'])),
				];
			}


			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'user/user_content_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());			

		}










	}