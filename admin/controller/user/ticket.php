<?
	
	class ControllerUserTicket extends Controller {
		
		public function index() {
			$this->language->load('user/user');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->getList();
		}
		
		private function getList(){
			$this->document->setTitle('Мои задачи');
			$this->data['heading_title'] = 'Мои задачи';
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('user/user');
			$this->load->model('user/ticket');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/ticket', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			$this->template = 'user/user_tickets.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
		}
		
		public function reloadticket(){
			$this->load->model('user/user');
			$this->load->model('user/ticket');
			$this->load->model('user/user_group');
			$this->load->model('sale/customer');
			$this->load->model('sale/order');
			setlocale(LC_ALL, 'ru_RU.UTF-8');
			
			$_ticket = $this->request->post['ticket_id'];
			$this->data['token'] = $this->session->data['token'];
			
			$ticket = $this->model_user_ticket->getTicket($_ticket);
			
			$this->data['ticket'] = array();
			if ($ticket){
				if ($ticket['from_user_id']){
					$_username = $this->model_user_user->getRealUserNameById($ticket['from_user_id']);
					} else {
					$_username = 'Авто';
				}
				
				switch ($ticket['entity_type']){
					
					case 'order': 
					$order = $this->model_sale_order->getOrder($ticket['entity_id']);
					$_entity_name = '<i class="fa fa-cart-arrow-down></i>&nbsp;Заказ';
					$_entity_xname = false;
					$_entity_url = $this->url->link('sale/order/update', 'order_id='.$ticket['entity_id'].'&token='.$this->data['token']);
					$_entity_addon = $order['telephone'].'<span class="click2call" data-phone="'.$order['telephone'].'">';
					break;
					
					case 'customer': 
					$customer = $this->model_sale_customer->getCustomer($ticket['entity_id']);
					$_entity_name = '<i class="fa fa-user"></i>&nbsp;Клиент';
					$_entity_xname = $customer['firstname'];
					$_entity_url = $this->url->link('sale/customer/update', 'customer_id='.$ticket['entity_id'].'&token='.$this->data['token']);
					$_entity_addon = $customer['telephone'].'<span class="click2call" data-phone="'.$customer['telephone'].'">';
					break;
					
					case 'call': 			
					$_entity_xname = $ticket['entity_string'];
					$_entity_name = '<i class="fa fa-user"></i>&nbsp;Звонок '. $_entity_xname .'<span class="click2call" data-phone="'.$customer['telephone'].'">';
					$_entity_url = false;
					$_entity_addon = false;
					break;
					
					
					default: 
					$_entity_name = 'Клиент';
					$_entity_addon = false;
					$_entity_xname = false;
					$_entity_url = false;
					break;
				}
				
				$_is_whose_ticket = false;
				if ($ticket['user_id'] && $ticket['user_id'] == $this->data['user_id']){
					$_is_whose_ticket = 'Моя задача';
					} elseif (!$ticket['user_id']){
					if ($ticket['user_group_id']){
						$_is_whose_ticket = $this->model_user_user_group->getUserGroupName($ticket['user_group_id']);
						} else {
						$_is_whose_ticket = false;
					}
					} elseif ($ticket['user_id'] && $ticket['user_id'] != $this->data['user_id']){
					$_is_whose_ticket = $this->model_user_user->getRealUserLastNameById($ticket['user_id']);
				}
				
				$this->data['ticket'] = array(
				'ticket_id'			=> $ticket['ticket_id'],
				'user_group_id' 	=> $ticket['user_group_id'],
				'user_id'			=> $ticket['user_id'],
				'is_my_ticket'		=> ($ticket['user_id'] == $this->data['user_id']  || $ticket['user_group_id'] == $this->data['user_group_id']),
				'is_whose_ticket'   => $_is_whose_ticket,
				'from_user_id'		=> $ticket['from_user_id'],
				'is_posted_by_me'   => ($ticket['from_user_id'] == $this->data['user_id']),
				'from_user_name' 	=> $_username,
				'entity_type'		=> $ticket['entity_type'],
				'entity_name'		=> $_entity_name,
				'entity_xname'		=> $_entity_xname,
				'entity_addon'		=> $_entity_addon,
				'entity_id'			=> $ticket['entity_id'],
				'entity_url'		=> $_entity_url,
				'message'			=> $ticket['message'],
				'reply'				=> $ticket['reply'],
				'priority'			=> $ticket['priority'],
				'date_added'		=> date('Y.m.d H:i:s', strtotime($ticket['date_added'])),
				'at_time_is_near'   => (date('Y-m-d H:i:s', strtotime($ticket['date_at'])) <= date('Y-m-d H:i:s', strtotime('+1 hour')) && date('Y-m-d H:i:s', strtotime($ticket['date_at'])) >= date('Y-m-d H:i:s', strtotime('-1 hour'))),
				'max_time_is_near'  => (date('Y-m-d H:i:s', strtotime($ticket['date_max'])) <= date('Y-m-d H:i:s', strtotime('+1 hour')) && date('Y-m-d H:i:s', strtotime($ticket['date_max'])) >= date('Y-m-d H:i:s', strtotime('-1 hour'))),
				'date_max'			=> $ticket['date_max']!='0000-00-00 00:00:00'?strftime('%a %d %b %k:%M', strtotime($ticket['date_max'])):false,
				'date_at'			=> $ticket['date_at']!='0000-00-00 00:00:00'?strftime('%a %d %b %k:%M', strtotime($ticket['date_at'])):false,
				'status'			=> $ticket['status'],
				);
			}
			
			$this->template = 'user/user_tickets_ajax.tpl';
			$this->response->setOutput($this->render());
		}
		
		public function gettickets(){
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			$this->load->model('user/ticket');
			$this->load->model('sale/customer');
			$this->load->model('sale/order');
			setlocale(LC_ALL, 'ru_RU.UTF-8');
			
			$_filter = $this->request->get['_filter'];
			$_page = (isset($this->request->get['page']))?$this->request->get['page']:1;				
			$this->data['token'] = $this->session->data['token'];
			
			$_data = array(
			'start' => ($_page - 1) * 40,
			'limit' => 40
			);
			
			switch ($_filter) {
				
				case 'my': $_data['by_group'] = 0;						
				break;
				
				case 'my_group': $_data['by_group'] = 1;				
				break;
				
				case 'today': $_data['filter_date_at'] = date('Y-m-d');	$_data['by_group'] = 0;			
				break;
				
				case 'by_order': $_data['filter_orders'] = 1;				
				break;
				
				case 'by_customer': $_data['filter_customers'] = 1;				
				break;
				
				case 'by_call': $_data['filter_calls'] = 1;				
				break;
				
				case 'urgent': $_data['filter_priority'] = 'red';					
				break;
				
				case 'middle_urgent': $_data['filter_priority'] = 'orange';				
				break;
				
				case 'non_urgent': $_data['filter_priority'] = 'green';					
				break;
				
				case 'set_by_me': $_data['filter_from_user_id'] = $this->user->getID();
				break;
				
				case 'my_done': $_data['by_group'] = 0; $_data['filter_done'] = 1;
				break;
				
				default: $_data['by_group'] = 0;			
				break;
			}
			
			$tickets = $this->model_user_ticket->getTickets($_data);
			
			$this->data['tickets'] = array();
			$this->data['user_id'] = $this->user->getID();
			$this->data['user_group_id'] = $this->user->getUserGroup();		
			
			if ($tickets){
				
				foreach ($tickets as $ticket){
					
					if ($ticket['from_user_id']){
						$_username = $this->model_user_user->getRealUserNameById($ticket['from_user_id']);
						} else {
						$_username = 'Авто';
					}
					
					switch ($ticket['entity_type']){
						
						case 'order': 
						$order = $this->model_sale_order->getOrder($ticket['entity_id']);
						$_entity_name = '<i class="fa fa-cart-arrow-down"></i>&nbsp;Заказ';
						$_entity_xname = false;
						$_entity_url = $this->url->link('sale/order/update', 'order_id='. $ticket['entity_id'] . '&token='.$this->data['token']);
						$_entity_addon = $order['telephone'].'<span class="click2call" data-phone="'.$order['telephone'].'">';
						break;
						
						case 'customer': 
						$customer = $this->model_sale_customer->getCustomer($ticket['entity_id']);
						$_entity_name = '<i class="fa fa-user"></i>&nbsp;Клиент';
						$_entity_xname = $customer['firstname'];
						$_entity_url = $this->url->link('sale/customer/update', 'customer_id='.$ticket['entity_id'].'&token='.$this->data['token']);
						$_entity_addon = $customer['telephone'].'<span class="click2call" data-phone="'.$customer['telephone'].'">';
						break;
						
						
						default: 
						$_entity_name = 'Клиент';
						$_entity_addon = false;
						$_entity_xname = false;
						$_entity_url = false;
						break;
					}
					
					
					$_is_whose_ticket = false;
					if ($ticket['user_id'] && $ticket['user_id'] == $this->data['user_id']){
						$_is_whose_ticket = 'Моя задача';
						} elseif (!$ticket['user_id']){
						if ($ticket['user_group_id']){
							$_is_whose_ticket = $this->model_user_user_group->getUserGroupName($ticket['user_group_id']);
							} else {
							$_is_whose_ticket = false;
						}
						} elseif ($ticket['user_id'] && $ticket['user_id'] != $this->data['user_id']){
						$_is_whose_ticket = $this->model_user_user->getRealUserLastNameById($ticket['user_id']);
					}
					
					$this->data['tickets'][] = array(
					'ticket_id'			=> $ticket['ticket_id'],
					'user_group_id' 	=> $ticket['user_group_id'],
					'user_id'			=> $ticket['user_id'],
					'is_my_ticket'		=> ($ticket['user_id'] == $this->data['user_id']  || $ticket['user_group_id'] == $this->data['user_group_id']),
					'is_whose_ticket'   => $_is_whose_ticket,
					'from_user_id'		=> $ticket['from_user_id'],
					'is_posted_by_me'   => ($ticket['from_user_id'] == $this->data['user_id']),
					'from_user_name' 	=> $_username,
					'entity_type'		=> $ticket['entity_type'],
					'entity_name'		=> $_entity_name,
					'entity_xname'		=> $_entity_xname,
					'entity_id'			=> $ticket['entity_id'],
					'entity_url'		=> $_entity_url,
					'entity_addon'		=> $_entity_addon,
					'message'			=> $ticket['message'],
					'reply'				=> $ticket['reply'],
					'priority'			=> $ticket['priority'],
					'date_added'		=> date('Y.m.d H:i:s', strtotime($ticket['date_added'])),
					'at_time_is_near'      => (date('Y-m-d H:i:s', strtotime($ticket['date_at'])) <= date('Y-m-d H:i:s', strtotime('+1 hour')) && date('Y-m-d H:i:s', strtotime($ticket['date_at'])) >= date('Y-m-d H:i:s', strtotime('-1 hour'))),
					'max_time_is_near'      => (date('Y-m-d H:i:s', strtotime($ticket['date_max'])) <= date('Y-m-d H:i:s', strtotime('+1 hour')) && date('Y-m-d H:i:s', strtotime($ticket['date_max'])) >= date('Y-m-d H:i:s', strtotime('-1 hour'))),
					'date_max'			=> $ticket['date_max']!='0000-00-00 00:00:00'?strftime('%a %d %b %k:%M', strtotime($ticket['date_max'])):false,
					'date_at'			=> $ticket['date_at']!='0000-00-00 00:00:00'?strftime('%a %d %b %k:%M', strtotime($ticket['date_at'])):false,
					'status'			=> $ticket['status'],
					);
				}
				
			}
			
			
			$this->template = 'user/user_tickets_ajax.tpl';
			$this->response->setOutput($this->render());
		}
		
		
		public function ticketdone(){
			$_ticket = $this->request->post['ticket_id'];
			
			if ($_ticket){
				$this->db->query("UPDATE tickets SET status=(1-status) WHERE ticket_id = '" .(int)$_ticket. "'");
			}
			
			echo (int)$this->db->countAffected();
		}
		
		public function ticketreply(){
			$_ticket = $this->request->post['ticket_id'];
			$_reply = $this->request->post['reply'];
			$_user_id = $this->user->getID();
			$_user_group_id = $this->user->getUserGroup();
			
			if ($_ticket){
				$this->db->query("UPDATE tickets SET reply = '" . $this->db->escape($_reply) . "' WHERE ticket_id = '" .(int)$_ticket. "'  AND (user_group_id = '" . $_user_group_id . "' OR user_id = '" . $_user_id . "')");
			}
			
			echo (int)$this->db->countAffected();
		}
		
		public function ticketdelete(){
			$_ticket = $this->request->post['ticket_id'];
			$_user_id = $this->user->getID();
			$_user_group_id = $this->user->getUserGroup();
			
			if ($_ticket){
				$this->db->query("DELETE FROM tickets WHERE ticket_id = '" .(int)$_ticket. "' AND from_user_id = '" . $_user_id . "'");
				$this->db->query("DELETE FROM ticket_sort WHERE ticket_id = '" .(int)$_ticket. "'");
			}
			
			echo (int)$this->db->countAffected();
		}
		
		public function ticketmakemine(){
			$_ticket = $this->request->post['ticket_id'];
			$_user_id = $this->user->getID();
			$_user_group_id = $this->user->getUserGroup();
			
			if ($_ticket){
				$this->db->query("UPDATE tickets SET user_id = '".$_user_id."' WHERE ticket_id = '" .(int)$_ticket. "' AND user_group_id = '" . $_user_group_id . "'");
			}
			
			echo (int)$this->db->countAffected();		
		}
		
		public function add_ticket(){
			$data = $this->request->post;
			
			$this->load->model('user/ticket');
			
			echo $this->model_user_ticket->addTicket($data);
		}
		
		public function showAddTicketFormAjax(){
			$this->load->model('sale/customer');
			$this->load->model('sale/order');
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->data['this_is_order'] = false;
			$this->data['this_is_customer'] = false;
			$this->data['this_is_call'] = false;
			
			$query = array();
			parse_str(str_replace('&amp;', '&', base64_decode($this->request->post['query'])), $query);
			
			//по маршруту - общая задача
			if( !empty( $query['route'] )){							
				if ($query['route']=='sale/customer/update'){
					$this->data['this_is_customer'] = true;
					$this->data['customer_id'] = (isset($query['customer_id']))?$query['customer_id']:false;
					$this->data['telephone'] = (isset($query['customer_id']))?$this->model_sale_customer->getCustomerPhone($query['customer_id']):false;
					$this->data['customer_name'] = (isset($query['customer_id']))?$this->model_sale_customer->getCustomerName($query['customer_id']):false;
				}
				
				if ($query['route']=='sale/order/update'){
					$this->data['this_is_order'] = true;
					$this->data['order_id'] = (isset($query['order_id']))?$query['order_id']:false;
					$this->data['customer_id'] = (isset($query['order_id']))?$this->model_sale_order->getCustomerIdByOrderId($query['order_id']):false;
					$this->data['customer_name'] = (isset($query['order_id']))?$this->model_sale_customer->getCustomerName($this->data['customer_id']):false;
					$this->data['telephone'] = (isset($query['order_id']))?$this->model_sale_order->getOrderPhone($query['order_id']):false;
				}
			} 
			
			//по прямым параметрам - задача
			if( !empty( $query['object'] )){
				
				if ($query['object'] == 'customer'){
					$this->data['this_is_customer'] = true;
					$this->data['customer_id'] = (isset($query['object_value']))?$query['object_value']:false;
					$this->data['customer_name'] = (isset($query['object_value']))?$this->model_sale_customer->getCustomerName($query['object_value']):false;
					$this->data['telephone'] = (isset($query['object_value']))?$this->model_sale_customer->getCustomerPhone($query['object_value']):false;
				}	
				
				if ($query['object'] == 'order'){	
					$this->data['this_is_order'] = true;
					$this->data['order_id'] = (isset($query['object_value']))?$query['object_value']:false;
					$this->data['customer_id'] = (isset($query['object_value']))?$this->model_sale_order->getCustomerIdByOrderId($query['object_value']):false;
					$this->data['customer_name'] = (isset($query['object_value']))?$this->model_sale_customer->getCustomerName($this->data['customer_id']):false;
					$this->data['telephone'] = (isset($query['object_value']))?$this->model_sale_order->getOrderPhone($query['object_value']):false;
				}
				
				if ($query['object'] == 'call'){
					$this->data['this_is_call'] = true;
					$this->data['telephone'] = (isset($query['object_value']))?$query['object_value']:false;
					$this->data['customer_id'] = (isset($query['object_value2']))?$query['object_value2']:false;
					$this->data['order_id'] = (isset($query['object_value3']))?$query['object_value3']:false;
				}	
			}
			
			$this->data['user_id'] = $this->user->getID();
			$this->data['users'] = $this->model_user_user->getUsers(array('filter_ticket' => 1));
			$this->data['user_groups'] = $this->model_user_user_group->getUserGroups(array('filter_ticket' => 1));
			
			
			$this->template = 'user/user_ticket_add.tpl';
			
			$this->response->setOutput($this->render());
			
		}
		
		public function resorttickets(){
			$_tickets = $this->request->post['ticket'];
			
			$sql = "INSERT INTO ticket_sort (ticket_id, sort_order) VALUES ";
			// key = sort_order, value= ticket_num
			foreach ($_tickets as $key => $value){
				$sql .= '('. (int)$value . ', '. (int)$key . '),';
			}
			$sql = substr($sql, 0, -1);
			$sql .= " ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order)";				
			$this->db->query($sql);
			
			$sql = "UPDATE tickets t, ticket_sort ts SET t.sort_order = ts.sort_order WHERE t.ticket_id = ts.ticket_id";
			$this->db->query($sql);
		}
		
	}						