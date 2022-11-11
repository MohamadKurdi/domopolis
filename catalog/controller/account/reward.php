<?php
	class ControllerAccountReward extends Controller {
		public function index() {
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/reward', '', 'SSL');
				
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->language->load('account/reward');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
			);
			
			
			$this->load->model('account/reward');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_description'] = $this->language->get('column_description');
			$this->data['column_points'] = $this->language->get('column_points');
			
			$this->data['column_order_id'] = $this->language->get('column_order_id');
			$this->data['column_points_paid'] = $this->language->get('column_points_paid');
			$this->data['column_date_paid'] = $this->language->get('column_date_paid');
			$this->data['column_active_points'] = $this->language->get('column_active_points');
			$this->data['column_date_inactivate'] = $this->language->get('column_date_inactivate');
			
			$this->data['text_total'] = $this->language->get('text_total');
			$this->data['text_empty'] = $this->language->get('text_empty');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}		
			
			$this->data['rewards'] = array();
			
			$data = array(				  
			'sort'  => 'date_added',
			'order' => 'ASC',
			'start' => ($page - 1) * 50,
			'limit' => 50
			);
			
			$reward_total = $this->model_account_reward->getTotalRewards($data);			
			$results = $this->model_account_reward->getRewards($data);
			
			foreach ($results as $result) {
				
				$points_active = ($result['points'] > 0)?$this->currency->formatBonus($result['points'] - $result['points_paid']):false;
				
				$date_inactivate = false;
				if ($result['points'] > 0 && ($result['points'] != $result['points_paid'])){
					$date_inactivate = true;
				}
				
				$this->data['rewards'][] = array(
				'points'      	=> $this->currency->formatBonus($result['points']),				
				'class'		  	=> ($result['points'] > 0)?'text-success':'text-danger',
				'description' 	=> $result['description'],
				'reason_code' 	=> $result['reason_code'],
				'order_id' 	  	=> $result['order_id'],
				'order_href' 	=> $result['order_id']?$this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'):false,
				'date_added'  	=> date('d.m.Y', strtotime($result['date_added'])),
				'time_added'  	=> date('H:i:s', strtotime($result['date_added'])),
				'points_paid' 	=> $result['points_paid']?$this->currency->formatBonus($result['points_paid']):false,
				'points_active' 	=> $points_active,			
				'date_paid'   		=> $result['points_paid']?date('d.m.Y', strtotime($result['date_paid'])):false,
				'date_inactivate'  	=> $date_inactivate?date('d.m.Y', strtotime($result['date_added'] . ' + ' . (int)$this->config->get('config_reward_lifetime') . ' days')):false
				);
			}
			
			$this->data['total_active'] = $this->customer->getRewardPoints();
			$this->data['total_queue'] = $this->customer->getRewardTotalInQueue();
			
			$this->data['total'] = $this->data['total_active'] + $this->data['total_queue'];
			$this->data['total'] = $this->currency->formatBonus($this->data['total']);
			
			$this->data['total_active'] = $this->currency->formatBonus($this->data['total_active']);
			$this->data['total_queue'] = $this->currency->formatBonus($this->data['total_queue']);
			
			
			$this->data['text_all'] = $this->language->get('text_all');
			$this->data['text_active'] = $this->language->get('text_active');
			
			$this->data['column_date_activate'] = $this->language->get('column_date_activate');
			$this->data['column_date_activate_points'] = $this->language->get('column_date_activate_points');
			
			$this->data['queues'] = array();
			$queue_results = $this->customer->getRewardQueue();
			
			if ($this->data['total_queue']){
				
				if (count($queue_results) == 1){				
					$this->data['text_inactive'] = sprintf($this->language->get('text_inactive'), date('d.m.Y', strtotime($this->customer->getRewardMaxQueueDate())));
				} else {
					$this->data['text_inactive'] = sprintf($this->language->get('text_inactive_to'), date('d.m.Y', strtotime($this->customer->getRewardMaxQueueDate())));
				}
			}
			
			unset($result);
			foreach ($queue_results as $result) {				
				$this->data['queues'][] = array(
				'points'      	=> $this->currency->formatBonus($result['points']),				
				'description' 	=> $result['description'],
				'reason_code' 	=> $result['reason_code'],
				'order_id' 	  	=> $result['order_id'],
				'order_href' 	=> $result['order_id']?$this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'):false,
				'date_added'  	=> date('d.m.Y', strtotime($result['date_added'])),
				'points_paid' 	=> $result['points_paid']?$this->currency->formatBonus($result['points_paid']):false,				
				'date_activate'  	=> date('d.m.Y', strtotime($result['date_activate'])),
				);
			}
						
			
			$pagination = new Pagination();
			$pagination->total = $reward_total;
			$pagination->page = $page;
			$pagination->limit = 50; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/reward', 'page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			
			$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reward.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/reward.tpl';
				} else {
				$this->template = 'default/template/account/reward.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
			);
			
			$this->response->setOutput($this->render());		
		} 		
	}		