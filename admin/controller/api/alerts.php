<?

class ControllerApiAlerts extends Controller { 


	public function index(){
		
	}
	
	public function addAlertHistory($data = array()){
		$user_id = $this->user->getID();
		
		$this->db->query("INSERT INTO alertlog SET 
			user_id = '" . (int)$user_id . "',
			alert_type = '" . $this->db->escape($data['type']) . "',
			alert_text = '" . $this->db->escape($data['text']) . "',
			entity_type = '" . $this->db->escape($data['entity_type']) . "',
			entity_id = '" . $this->db->escape($data['entity_id']) . "',
			datetime   = NOW()
		");
		
		
	}

	
	public function getAlert(){						
		$user_id = $this->user->getID();
		$token = $this->session->data['token'];
		
		if ($responce = $this->mAlert->getAlert($user_id)){			
			$alert = unserialize($responce);			
			//type:text:entity_type:entity_id
			
			$alert['sound'] = 'beep_alert';
			
			if ($alert['entity_type'] == 'order'){
				$order_id = (int)$alert['entity_id'];
				
				$alert['url'] = str_replace('&amp;','&',$this->url->link('sale/order/update', 'order_id='.(int)$order_id.'&token='.$token));				
				$alert['text'] = "<i class='fa fa-bell'></i>&nbsp;&nbsp;".$alert['text'].' : #'.$order_id;
				
				echo json_encode($alert);
				
			} elseif ($alert['entity_type'] == 'rating') {
				
				$alert['url'] = str_replace('&amp;','&',$this->url->link('catalog/shop_rating', 'token='.$token));								
				echo json_encode($alert);
				
			} elseif ($alert['entity_type'] == 'customer') {
				echo json_encode($alert);
			
			} elseif ($alert['entity_type'] == 'forgottencart') {
				
				$alert['url'] = str_replace('&amp;','&',$this->url->link('sale/order', 'filter_order_status_id=0&token='.$token));								
				echo json_encode($alert);
				
			}  elseif ($alert['entity_type'] == 'inboundcall') {
				
				$customer_id = (int)$alert['entity_id'];
				
				if ($customer_id) {				
					$alert['url'] = str_replace('&amp;','&',$this->url->link('sale/customer/update', 'customer_id='.(int)$customer_id.'&token='.$token));
				} else {
					$alert['url'] = 'undefined';
				}
				$alert['text'] = "<i class='fa fa-phone-square'></i>&nbsp;&nbsp;".$alert['text'].'';
				
				$alert['sound'] = 'incoming_alert';
				
				echo json_encode($alert);
			
			}  elseif ($alert['entity_type'] == 'missedcall') {
				
				$alert['url'] = str_replace('&amp;','&',$this->url->link('sale/callback', '&token='.$token));				
				$alert['text'] = "<i class='fa fa-phone-square'></i>&nbsp;&nbsp;".$alert['text'].'';
				
				echo json_encode($alert);
				
			}  elseif ($alert['entity_type'] == 'waitlist') {
				
				$alert['url'] = str_replace('&amp;','&',$this->url->link('catalog/waitlist', '&filter_product_id='.$alert['entity_id'].'&token='.$token));				
				$alert['text'] = "<i class='fa fa-bell'></i>&nbsp;&nbsp;".$alert['text'].'';
				
				echo json_encode($alert);
				
			} elseif ($alert['entity_type'] == 'no_money') {
				
				$alert['text'] = "<i class='fa fa-bell'></i>&nbsp;&nbsp;".$alert['text'].'';
				$alert['url'] = 'undefined';
				$alert['sound'] = 'fuck_alarm';
				
				echo json_encode($alert);
				
			} elseif ($alert['entity_type'] == 'import1c') {
				
				$alert['text'] = "<i class='fa fa-bell'></i>&nbsp;&nbsp;".$alert['text'];
				$alert['url'] = str_replace('&amp;','&',$this->url->link('catalog/stocks', '&token='.$token));
				
				echo json_encode($alert);
				
			} else  {
				echo json_encode($alert);
			}
			
			$this->addAlertHistory($alert);
			
		} else {
			exit();
		}

	}

}