<?php
class ControllerCheckoutCustomerConfirm extends Controller { 
	public function index() {
		
		if (isset($this->request->get['order_id']) && $this->request->get['order_id']>0) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;			
		}
				
		$this->language->load('checkout/success');
		$this->data['breadcrumbs'] = array(); 
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/cart'),
			'text'      => $this->language->get('text_basket'),
			'separator' => $this->language->get('text_separator')
		);
		
		$this->data['breadcrumbs'][] = array(
			'href' 		=> $this->url->link('account/account'),
			'text'      => sprintf($this->language->get('text_do_confirm'),$order_id),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['heading_title'] = sprintf($this->language->get('text_do_confirm'),$order_id);
		$this->document->setTitle($this->language->get('text_do_confirm'));
		
	//logic	
		$this->load->model('checkout/order');
		$this->load->model('account/order');
										
		$order_info = $this->model_checkout_order->getOrder($order_id);
		if (!$order_info){
			$this->data['confirm_error']='Такой заказ не существует!';			
		} else {						
			
			//direct update order_status
			$q = mysql_query("SELECT `order_status_id` FROM `order` WHERE order_id='".(int)$order_id."' LIMIT 1 ");			
			$a = mysql_fetch_assoc($q);
			$order_info['order_status_id'] = $a['order_status_id'];
			
			$confirmed_statuses = explode(',',$this->config->get('config_confirmed_delivery_payment_ids'));			
			$does_not_need_payment = in_array($order_info['payment_code'], $confirmed_statuses);
			
			//Проверяем, были ли в истории статусы "подтвержден"
			$histories = $this->model_account_order->getOrderHistories($order_id);		
			
			if ($does_not_need_payment){
				//платеж не нужен!
				$can_be_confirmed = true;
				foreach ($histories as $history){
					if ($history['order_status_id'] == $this->config->get('config_confirmed_order_status_id')){
						$can_be_confirmed = false;
						break;
					}				
				}
			} else {	
				$can_be_confirmed = true;
				foreach ($histories as $history){
					if ($history['order_status_id'] == $this->config->get('config_confirmed_nopaid_order_status_id')){
						$can_be_confirmed = false;
						break;
					}				
				}
			}
				
			//Заказ в статусе оформлен - можно подтверждать! И нет уже подтверждений в сессии
			if ($can_be_confirmed) {									
				//а вот теперь проверяем контрольную сумму
				if (isset($this->request->get['confirm']) && $this->request->get['confirm']==md5(sin($order_id+2))){
						$this->load->model('checkout/order');
						$this->data['order_info'] = $order_info;
						
						if ($does_not_need_payment){
							$this->model_checkout_order->update($order_id, $this->config->get('config_confirmed_order_status_id'), '', true);
						} else {
							$this->model_checkout_order->update($order_id, $this->config->get('config_confirmed_nopaid_order_status_id'), '', true);
						}
						
						$this->data['confirm_message'] = sprintf($this->language->get('text_success_message'), $order_info['firstname'], $order_id);
						
						$this->session->data['cfd_order'] = $order_id;
				} else {
					$this->data['confirm_error'] = sprintf($this->language->get('text_control'), $order_info['firstname'], $order_id);;
				}				
			} elseif (!$can_be_confirmed || ($order_info['order_status_id'] == $this->config->get('config_confirmed_order_status_id'))) {			
			//Заказ уже подтверждался. Статус равен
						$this->data['confirm_error'] = sprintf($this->language->get('text_already'), $order_id);			
			}			
		}
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['continue'] = $this->url->link('common/home');
	
		$this->data['order_url'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/customerconfirm.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/customerconfirm.tpl';
		} else {
			$this->template = 'default/template/common/customerconfirm.tpl';
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
?>