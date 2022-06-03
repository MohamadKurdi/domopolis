<?
	
	class ControllerKPManagerQuality extends Controller {
		protected $error = array();
		
		private function secToHR($seconds) {
			$hours = floor($seconds / 3600);
			$minutes = floor(($seconds / 60) % 60);
			$seconds = $seconds % 60;
			return $hours > 0 ? "$hours ч., $minutes мин." : ($minutes > 0 ? "$minutes мин., $seconds сек. " : "$seconds сек.");
		}
		
		public function index() {
			$this->language->load('user/user');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->getList();
		}
		
		private function countPercentByParam($num, $param_array, $percent_array){
			
			if ($num <= $param_array[0]){
				return $percent_array[0];
			}
			
			if ($num <= $param_array[1]){
				return $percent_array[1];
			}
			
			return $percent_array[2];
			
		}
		
		private function getList(){
			$this->document->setTitle('Учет эффективности менеджеров');
			$this->data['heading_title'] = 'Учет эффективности менеджеров';
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('user/user');
			$this->load->model('kp/work');
			$this->load->model('user/user_group');
			$this->load->model('localisation/order_status');
			
			require_once(DIR_APPLICATION . 'controller/kp/salary.php');
			$percentage_params = ControllerKPSalary::$static_percentage_params;
			$fixed_salary = ControllerKPSalary::$static_fixed_salary;
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('kp/managerquality', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);
			
			if (empty($this->request->get['period']) || $this->request->get['period'] == 'now'){
				$period = 'now';				
				} else {
				$period = date('Y-m-d', strtotime($this->request->get['period']));
			}
			
			$this->data['current_period'] = $period;
			
			$this->data['periods'] = array();
			
			$this->data['periods'][] = array(
			'name' => 'Сейчас',
			'date' =>  'now',
			'href' => $this->url->link('kp/managerquality', 'period=now&token=' . $this->session->data['token'], 'SSL'),
			);
			
			for ($i = 1; $i <= 14; $i++) {
				
				switch ($i) {
					
					case 0 : $_name = 'Сегодня c утра';break;
					case 1 : $_name = 'Вчера';break;
					case 2 : $_name = 'Позавчера';break;
					
					default : $_name = date('d.m', strtotime("-$i day"));break;
				}
				
				$this->data['periods'][] = array(
				'name' => $_name,
				'date' =>  date('Y-m-d', strtotime("-$i day")),
				'href' => $this->url->link('kp/managerquality', 'period='. date('Y-m-d', strtotime("-$i day")) .'&token=' . $this->session->data['token'], 'SSL'),
				);				
			}
			
			//Надо получить текущую группу магазина
			if ($this->user->getIsManager()){
				$filter_groups = array($this->user->getUserGroup());
			} else {
				$filter_groups = MANAGER_GROUPS;
			}
			
			$managers = $this->model_user_user->getUsersByGroups($filter_groups, true);			
			
			$data = array(
			'start' => 0,
			'limit' => 200
			);
			$order_statuses = $this->model_localisation_order_status->getOrderStatuses($data);
			
			$this->data['order_statuses'] = array();
			
			foreach ($order_statuses as $order_status){
				
				$this->data['order_statuses'][$order_status['order_status_id']] = array();
				
				$this->data['order_statuses'][$order_status['order_status_id']]['order_status'] = array(
					'order_status_id' => $order_status['order_status_id'],
					'status_fa_icon'  => $order_status['status_fa_icon'],
					'name'            => $order_status['name'],
					'status_bg_color' => $order_status['status_bg_color'],
					'status_txt_color'=> $order_status['status_txt_color'],
					'is_problem'      => in_array($order_status['order_status_id'], $this->config->get('config_problem_quality_order_status_id'))
				);
				
				if ($period == 'now') {
															
					foreach ($managers as &$manager){
						$manager['kpi_stats'] = $this->model_kp_work->getManagerLastKPI($manager['user_id']);
						
						$this->data['order_statuses'][$order_status['order_status_id']]['managers'][] = array(
							'name' 		=> $manager['realname'],
							'user_id' 	=> $manager['user_id'],							
							'href'      => $this->url->link('sale/order', 'filter_order_status_id=' . $order_status['order_status_id'] . '&filter_manager_id=' . $manager['user_id'] . '&token=' . $this->session->data['token']),
							'count'     => $this->model_kp_work->getCurrentCountOrderStatusForManager($manager['user_id'], $order_status['order_status_id']),
							'diff_morning' => ($this->model_kp_work->getCurrentCountOrderStatusForManager($manager['user_id'], $order_status['order_status_id']) - 
												$this->model_kp_work->getCountOrderStatusForManagerForDate($manager['user_id'], $order_status['order_status_id'], date('Y-m-d'))),
							'was_today_morning' => $this->model_kp_work->getCountOrderStatusForManagerForDate($manager['user_id'], $order_status['order_status_id'], date('Y-m-d')),
							'last_order_date'  => $this->model_kp_work->getCurrentCountOrderStatusForManager($manager['user_id'], $order_status['order_status_id'])?date('Y-m-d', strtotime($this->model_kp_work->getLastOrderAdditionDate($manager['user_id'], $order_status['order_status_id']))):false,
							'last_order_date_diff' => (int)((-1) * ceil((strtotime($this->model_kp_work->getLastOrderAdditionDate($manager['user_id'], $order_status['order_status_id'])) - time()) / 86400))
						);					
					}
					
				} else {
				
					foreach ($managers as &$manager){
						
						$manager['kpi_stats'] = $this->model_kp_work->getManagerOnDateKPI($manager['user_id'], date('Y-m-d', strtotime($period)));
					
						$this->data['order_statuses'][$order_status['order_status_id']]['managers'][] = array(
							'name' 		=> $manager['realname'],
							'user_id' 	=> $manager['user_id'],
							'href'      => $this->url->link('sale/order', 'filter_order_status_id=' . $order_status['order_status_id'] . '&filter_manager_id=' . $manager['user_id'] . '&token=' . $this->session->data['token']),
							'count'     => $this->model_kp_work->getCountOrderStatusForManagerForDate($manager['user_id'], $order_status['order_status_id'], date('Y-m-d', strtotime("+1 day", strtotime($period)))),
							//то, что за 25 число сделано = "утро 26 - утро 25"
							'diff_day'  => ($this->model_kp_work->getCountOrderStatusForManagerForDate($manager['user_id'], $order_status['order_status_id'], date('Y-m-d', strtotime("+1 day", strtotime($period)))) - 
												$this->model_kp_work->getCountOrderStatusForManagerForDate($manager['user_id'], $order_status['order_status_id'], date('Y-m-d', strtotime($period)))),						
							'was_that_morning'   => $this->model_kp_work->getCountOrderStatusForManagerForDate($manager['user_id'], $order_status['order_status_id'], date('Y-m-d', strtotime($period)))
						);					
					}
				
				
				}															
				
				
			}
			
			$this->data['managers'] = $managers;
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'user/manager_quality.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
		}
		
		
		
		
		public function cronDynamics(){
			
			$this->load->model('kp/work');		
			$this->load->model('user/user');
			$this->load->model('localisation/order_status');
			
			$managers = $this->model_user_user->getUsersByGroups(array(12, 19), false);
			
			$data = array(
			'start' => 0,
			'limit' => 200
			);
			$order_statuses = $this->model_localisation_order_status->getOrderStatuses($data);
			
			foreach ($managers as $manager){
				
				echo '>> ' . $manager['realname'] . ' начинаем...' . PHP_EOL;
				foreach ($order_statuses as $order_status){
					$count = $this->model_kp_work->getCurrentCountOrderStatusForManager($manager['user_id'], $order_status['order_status_id']);
					
					echo '>>> ' . $order_status['name'] . ' : ' . $count . PHP_EOL;
					
					$this->model_kp_work->addtCurrentCountOrderStatusForManagerDynamics($manager['user_id'], $order_status['order_status_id'], $count);
				}
				
				
			}
			
			
			
			
			
		}
		
	}		