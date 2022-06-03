<?php
class ControllerPaymentinterplusplus extends Controller {
	protected function index() {
	  $this->language->load('payment/interplusplus');
	  $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

	  $this->data['continue'] = $this->url->link('checkout/success');

				$this->load->language('account/interplusplus');
			    $mrh_login = $this->config->get('interplusplus_login');
				$mrh_pass1 = $this->config->get('interplusplus_password1');
			    $inv_id = $this->session->data['order_id'];
			    $inv_desc = $order_info['payment_firstname'] . '_' . $order_info['payment_lastname'] . '_' . $order_info['email'] . '_заказ_от_' .  str_replace(" ","_",$order_info['date_added']);
			    $out_summ = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE);
			    $ik_cur = $order_info['currency_code'];
			    $action = 'https://sci.interkassa.com/';	

					$online_url = $action .

							'?ik_co_id=' 		. $mrh_login .
							'&ik_pm_no='		. $inv_id	.
							'&ik_cur='			. $ik_cur .
			        		'&ik_am='			. $out_summ .
							'&ik_desc='			. $inv_desc .
							'ik_x_corder=1';
			    
			    	
				    		$this->data['pay_url'] = $online_url;
				    		$this->data['button_confirm'] = $this->language->get('button_confirm');
				    		$this->data['payment_url'] = $this->url->link('checkout/success');
				    		$this->data['button_later'] = $this->language->get('button_pay_later');

					if ($this->config->get('interplusplus_instruction_attach')){
				      $this->data['text_instruction'] = $this->language->get('text_instruction');

				      $instros = explode('$', ($this->config->get('interplusplus_instruction')));
				      $instroz = "";
				      foreach ($instros as $instro) {
				      	if ($instro == 'href' || $instro == 'orderid' ||  $instro == 'itogo'){
				      		if ($instro == 'href'){
				            $instro_other = $online_url;
				        	}
				            if ($instro == 'orderid'){
				            $instro_other = $inv_id;
					       	}
					       	if ($instro == 'itogo'){
					            $instro_other = $out_summ;
					       	}
				       	}
				       	else {
				       		$instro_other = nl2br(htmlspecialchars_decode($instro));
				       	}
				       	$instroz .=  $instro_other;
				      }
				      $this->data['interplusplusi'] = $instroz;
				    }

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/interplusplus.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/interplusplus.tpl';
		} else {
            $this->template = 'default/template/payment/interplusplus.tpl';
        }
		
		$this->render();		 
	}
	
	public function confirm() {
  		$this->language->load('payment/interplusplus');
		$this->load->model('checkout/order');
			if ($this->config->get('interplusplus_mail_instruction_attach')){
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				$mrh_login = $this->config->get('interplusplus_login');
				$out_summ = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE);
				$inv_id = $this->session->data['order_id'];
				$ik_cur = $order_info['currency_code'];
				$inv_desc = $order_info['payment_firstname'] . '_' . $order_info['payment_lastname'] . '_' . $order_info['email'] . '_заказ_от_' .  str_replace(" ","_",$order_info['date_added']);
			    $action = 'https://sci.interkassa.com/';	

					$online_url = $action .

							'?ik_co_id=' 		. $mrh_login .
							'&ik_pm_no='		. $inv_id	.
							'&ik_cur='			. $ik_cur .
			        		'&ik_am='			. $out_summ .
							'&ik_desc='			. $inv_desc ;

		    	$comment  = $this->language->get('text_instruction') . "\n\n";
		    	$instros = explode('$', ($this->config->get('interplusplus_mail_instruction')));
				      $instroz = "";
				      foreach ($instros as $instro) {
				      	if ($instro == 'href' || $instro == 'orderid' ||  $instro == 'itogo'){
				      		if ($instro == 'href'){
				            	$instro_other = $online_url;
				        	}
				            if ($instro == 'orderid'){
				            	$instro_other = $inv_id;
					       	}
					       	if ($instro == 'itogo'){
					            $instro_other = $out_summ;
					       	}
				       	}
				       	else {
				       		$instro_other = nl2br($instro);
				       	}
				       	$instroz .=  $instro_other;
				      }
				$comment .= $instroz;
		    	$comment = htmlspecialchars_decode($comment);
		    	$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('interplusplus_on_status_id'), $comment, true);
	    	}
	    	else{
				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('interplusplus_on_status_id'), true);
			}
	}
}
?>