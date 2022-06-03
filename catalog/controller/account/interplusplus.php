<?php
class ControllerAccountinterplusplus extends Controller {
    private $error = array();
    public function index() {
    $this->load->language('account/interplusplus');
    $this->data['button_pay'] = $this->language->get('button_pay');
		$this->data['button_back'] = $this->language->get('button_back');
    $this->data['heading_title'] = $this->language->get('heading_title');
    if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');
          $this->redirect($this->url->link('account/login', '', 'SSL'));
    	}
    if (isset($_POST['InvId']) & isset($_POST['Desc'])){
    $mrh_login = $this->config->get('interplusplus_login');
		$mrh_pass1 = $this->config->get('interplusplus_password1');
    $inv_id = $this->request->post['InvId'];
    $inv_desc = $this->request->post['Desc'];
    $query = $this->db->query ("SELECT `total`, `currency_code`, `currency_value` FROM `order` WHERE `order_id` = $inv_id " );
    $out_summ = $query->row['total'];
    $out_summ = $this->currency->format($out_summ, $query->row['currency_code'], $query->row['currency_value'], false);
    $ik_cur = $query->row['currency_code'];
    $this->data['back'] = HTTPS_SERVER . 'index.php?route=account/order';
	$action = 'https://sci.interkassa.com/';
    
    		$this->data['merchant_url'] = $action .

							'?ik_co_id=' 		. $mrh_login .
							'&ik_pm_no='		. $inv_id	.
							'&ik_cur='			. $ik_cur .
			        		'&ik_am='			. $out_summ .
							'&ik_desc='			. $inv_desc ;
        
    $this->data['send_text'] = $this->language->get('send_text');
    $this->data['send_text2'] = $this->language->get('send_text2');
    $this->data['inv_id'] = $inv_id;
    $this->data['out_summ'] = $this->currency->format($query->row['total'], $query->row['currency_code'], $query->row['currency_value'], true);

   if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .
     '/template/account/interplusplus.tpl')) {

        $this->template = $this->config->get('config_template') .
        '/template/account/interplusplus.tpl';
    } else {
        $this->template = 'default/template/account/interplusplus.tpl';
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
    else{
    echo "No data";
    }
    }

  public function callback() {
  $ik_shop_id = $this->config->get('interplusplus_login');
  if (isset($_POST['ik_pm_no']) & isset($_POST['ik_am']) & ($_POST['ik_inv_st'] == 'success') ){
	$secret_key = $this->config->get('interplusplus_password1');
	$tm=getdate(time());
	$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";
	$ik_payment_amount = $_POST['ik_am'];
	$ik_pm_no = $_POST['ik_pm_no'];
	$ik_paysystem_alias = $_POST['ik_pw_via'];
	$ik_payment_state = $_POST['ik_inv_st'];
	$ik_trans_id =  $_POST['ik_inv_id'] . " " . $_POST['ik_trn_id'];
	$ik_currency_exch = $_POST['ik_cur'];
	$status_data = $_POST['ik_sign'];
	$dataSet =  $_POST;
	unset($dataSet['ik_sign']);
	ksort($dataSet, SORT_STRING);
	array_push($dataSet, $secret_key);
	$signString = implode(':', $dataSet);
	$sign = base64_encode(md5($signString, true));

	if($status_data != $sign) {
	  echo "bad sign\n";
	  exit();
	}

	echo "200 OK";

    // save order info to mysql
    $query = $this->db->query("SELECT `payment_lastname`, `payment_firstname`, `email` FROM `order` WHERE `order_id` = '". $ik_pm_no . "' ; ");
	$user_n = $query->row['payment_lastname'];
	$user_f = $query->row['payment_firstname'];
	$user_e = $query->row['email'];
	$query = $this->db->query ("INSERT INTO `interplusplus` SET `num_order`	= '" . $ik_pm_no . "' , `sum` = '" . $ik_payment_amount . "' , `date_enroled` = ' " . $date . "', `user` = '" . $user_n . " " . $user_f . "', `email` = '" . $user_e . "' ");
	$interplusplus_status_id = $this->config->get('interplusplus_order_status_id');
	$query = $this->db->query ("UPDATE `order` SET `order_status_id` = $interplusplus_status_id WHERE `order_id` = $ik_pm_no ");

    $this->load->model('checkout/order');

    if ($this->config->get('interplusplus_success_alert_customer')){
        if ($this->config->get('interplusplus_success_comment_attach')) {
          $instros = explode('$', ($this->config->get('interplusplus_success_comment')));
              $instroz = "";
              foreach ($instros as $instro) {
                if ($instro == 'orderid' ||  $instro == 'itogo'){
                    if ($instro == 'orderid'){
                    $instro_other = $ik_pm_no;
                  }
                  if ($instro == 'itogo'){
                      $instro_other = $ik_payment_amount;
                  }
                }
                else {
                  $instro_other = nl2br(htmlspecialchars_decode($instro));
                }
                $instroz .=  $instro_other;
              }
          $message = $instroz;
          $this->model_checkout_order->update($ik_pm_no, $this->config->get('interplusplus_order_status_id'), $message, true);
        }
        else{
          $message = '';
          $this->model_checkout_order->update($ik_pm_no, $this->config->get('interplusplus_order_status_id'), $message, true);
        }
    }
    else{
      $this->model_checkout_order->update($ik_pm_no, $this->config->get('interplusplus_order_status_id'), false);
    }

    if ($this->config->get('interplusplus_success_alert_admin')) {
      
        $subject = sprintf(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $ik_pm_no);
        
        // Text 
        $this->load->language('account/interplusplus');
        $text = sprintf($this->language->get('success_admin_alert'), $ik_pm_no) . "\n";
        
        
      
        $mail = new Mail(); 
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($this->config->get('config_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($store_name);
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
        $mail->send();
        
        // Send to additional alert emails
        $emails = explode(',', $this->config->get('config_alert_emails'));
        
        foreach ($emails as $email) {
          if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
            $mail->setTo($email);
            $mail->send();
          }
        }
    }
  }
  else{
  echo "No data";
  }
}
  public function fail() {
  
    $this->load->language('account/interplusplus');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['button_ok'] = $this->language->get('button_ok');
    
    $ik_co_id = $this->config->get('interplusplus_login');
    
    if (isset($_POST['ik_pm_no'])){
    $inv_id = $_POST['ik_pm_no'];
    $out_summ = $_POST['ik_am'];
    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($inv_id);
    $mrh_login = $this->config->get('interplusplus_login');
     $ik_cur = $order_info['currency_code'];
    $inv_desc = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'] . ' ' . $order_info['email'] . ' заказ от ' .$order_info['date_added'];
	$action = 'https://sci.interkassa.com/';
				$online_url = $action .

							'?ik_co_id=' 		. $mrh_login .
							'&ik_pm_no='		. $inv_id	.
							'&ik_cur='			. $ik_cur .
			        		'&ik_am='			. $out_summ .
							'&ik_desc='			. $inv_desc ;

		    	
    $this->data['fail_text'] = '';
    if (isset($_POST['ik_x_corder'])) {
        $this->data['fail_text'] .=  $this->language->get('fail_text_first');
      }
    if ($this->config->get('interplusplus_fail_page_text_attach')) {

    	$instros = explode('$', ($this->config->get('interplusplus_fail_page_text')));
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

      $this->data['fail_text'] .= $instroz;
    }
    else{
	    $this->data['fail_text'] .=  sprintf($this->language->get('fail_text'), $inv_id, $online_url);
    }
    if ($this->customer->isLogged()) {
          $this->data['fail_text'] .=  sprintf($this->language->get('fail_text_loged'), $this->url->link('account/order', '', 'SSL'));
    }
    			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);
			
      if (isset($_POST['ik_x_corder'])) {
        $this->language->load('checkout/success');
        $this->data['breadcrumbs'][] = array(
          'href'      => $this->url->link('checkout/cart'),
          'text'      => $this->language->get('text_basket'),
          'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
          'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
          'text'      => $this->language->get('text_checkout'),
          'separator' => $this->language->get('text_separator')
        );
        $this->data['button_ok_url'] = $this->url->link('common/home');
      }
      else{
        if ($this->customer->isLogged()) {
    			$this->data['breadcrumbs'][] = array(
    				'text'      => $this->language->get('lich'),
    				'href'      => $this->url->link('account/account', '', 'SSL'),
    				'separator' => $this->language->get('text_separator')
    			);

    			$this->data['breadcrumbs'][] = array(
    				'text'      => $this->language->get('history'),
    				'href'      => $this->url->link('account/order', '', 'SSL'),
    				'separator' => $this->language->get('text_separator')
    			);
          $this->data['button_ok_url'] = $this->url->link('account/order', '', 'SSL');
        }
        else{
          $this->data['button_ok_url'] = $this->url->link('common/home');
        }
      }
  
  if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .
     '/template/account/interplusplus_fail.tpl')) {

        $this->template = $this->config->get('config_template') .
        '/template/account/interplusplus_fail.tpl';
    } else {
        $this->template = 'default/template/account/interplusplus_fail.tpl';
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
  else{
  	echo "No data";
	}
  }
  
  public function success() {
   $ik_co_id = $this->config->get('interplusplus_login');
   if (isset($_POST['ik_pm_no'])){
      $out_summ = $_POST["ik_am"];
      $inv_id = $_POST["ik_pm_no"];
      $this->load->language('account/interplusplus');
      $this->data['heading_title'] = $this->language->get('heading_title');
      $this->document->setTitle($this->language->get('heading_title'));
      $this->data['button_ok'] = $this->language->get('button_ok');
      $this->data['inv_id'] = $inv_id;
      $this->data['success_text'] = '';
      if (isset($_POST['ik_x_corder'])) {
        $this->data['success_text'] .=  $this->language->get('success_text_first');
      }

      if ($this->config->get('interplusplus_success_page_text_attach')) {

      $instros = explode('$', ($this->config->get('interplusplus_success_page_text')));
              $instroz = "";
              foreach ($instros as $instro) {
                if ($instro == 'orderid' ||  $instro == 'itogo'){
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

      $this->data['success_text'] .= $instroz;
      }
      else{
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($inv_id);
        if ($order_info['order_status_id'] == $this->config->get('interplusplus_order_status_id')) {
          $this->data['success_text'] .=  sprintf($this->language->get('success_text'), $inv_id);
        }
        else{
          $this->data['success_text'] .=  sprintf($this->language->get('success_text_wait'), $inv_id);
        }
      }
      if ($this->customer->isLogged()) {
        $this->data['success_text'] .=  sprintf($this->language->get('success_text_loged'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/order/info&order_id=' . $inv_id, '', 'SSL'));
      }
      
      $this->data['breadcrumbs'] = array();

      $this->data['breadcrumbs'][] = array(
        'text'      => $this->language->get('text_home'),
        'href'      => $this->url->link('common/home'),
        'separator' => false
      );
      
      if (isset($_POST['ik_x_corder'])) {
        $this->language->load('checkout/success');
        $this->data['breadcrumbs'][] = array(
          'href'      => $this->url->link('checkout/cart'),
          'text'      => $this->language->get('text_basket'),
          'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
          'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
          'text'      => $this->language->get('text_checkout'),
          'separator' => $this->language->get('text_separator')
        );
        $this->data['button_ok_url'] = $this->url->link('common/home');
      }
      else{
        if ($this->customer->isLogged()) {
          $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('lich'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
          );

          $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('history'),
            'href'      => $this->url->link('account/order', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
          );
          $this->data['button_ok_url'] = $this->url->link('account/order', '', 'SSL');
        }
        else{
          $this->data['button_ok_url'] = $this->url->link('common/home');
        }
      }

      if (isset($this->session->data['order_id'])) {
        $this->cart->clear();
        
        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['guest']);
        unset($this->session->data['comment']);
        unset($this->session->data['order_id']);  
        unset($this->session->data['coupon']);
        unset($this->session->data['reward']);
        unset($this->session->data['voucher']);
        unset($this->session->data['vouchers']);
      }

      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .
          '/template/account/interplusplus_success.tpl')) {

          $this->template = $this->config->get('config_template') .
          '/template/account/interplusplus_success.tpl';
      } else {
          $this->template = 'default/template/account/interplusplus_success.tpl';
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
    else{
      echo "No data";
    }
  }

  public function waiting() {
  	$ik_co_id = $this->config->get('interplusplus_login');
  	if (isset($_POST['ik_pm_no'])){
		$this->load->language('account/interplusplus');
	    $this->document->setTitle($this->language->get('heading_title'));
	    $this->data['heading_title'] = $this->language->get('heading_title');
	    $this->data['button_ok'] = $this->language->get('button_ok');
	    $inv_id = $_POST['ik_pm_no'];
	    $out_summ = $_POST['ik_am'];
	    $this->load->model('checkout/order');
	    $order_info = $this->model_checkout_order->getOrder($inv_id);
	    $mrh_login = $this->config->get('interplusplus_login');
	    $ik_cur = $order_info['currency_code'];
	    $inv_desc = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'] . ' ' . $order_info['email'] . ' заказ от ' .$order_info['date_added'];
		$action = 'https://sci.interkassa.com/';
					$online_url = $action .

								'?ik_co_id=' 		. $mrh_login .
								'&ik_pm_no='		. $inv_id	.
								'&ik_cur='			. $ik_cur .
				        		'&ik_am='			. $out_summ .
								'&ik_desc='			. $inv_desc ;

			    	
	    $this->data['waiting_text'] = '';
	    if (isset($_POST['ik_x_corder'])) {
	        $this->data['waiting_text'] .=  $this->language->get('waiting_text_first');
	    }
	    if ($this->config->get('interplusplus_waiting_page_text_attach')) {

	    	$instros = explode('$', ($this->config->get('interplusplus_waiting_page_text')));
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

	      $this->data['waiting_text'] .= $instroz;
	    }
	    else{
		    $this->data['waiting_text'] .=  sprintf($this->language->get('waiting_text'), $inv_id, $online_url);
	    }
	    if ($this->customer->isLogged()) {
	          $this->data['waiting_text'] .=  sprintf($this->language->get('waiting_text_loged'), $this->url->link('account/order', '', 'SSL'));
	    }
	    		$this->data['breadcrumbs'] = array();

				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home'),
					'separator' => false
				);
				
	      if (isset($_POST['ik_x_corder'])) {
	        $this->language->load('checkout/success');
	        $this->data['breadcrumbs'][] = array(
	          'href'      => $this->url->link('checkout/cart'),
	          'text'      => $this->language->get('text_basket'),
	          'separator' => $this->language->get('text_separator')
	        );
	        
	        $this->data['breadcrumbs'][] = array(
	          'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
	          'text'      => $this->language->get('text_checkout'),
	          'separator' => $this->language->get('text_separator')
	        );
	        $this->data['button_ok_url'] = $this->url->link('common/home');
	      }
	      else{
	        if ($this->customer->isLogged()) {
	    			$this->data['breadcrumbs'][] = array(
	    				'text'      => $this->language->get('lich'),
	    				'href'      => $this->url->link('account/account', '', 'SSL'),
	    				'separator' => $this->language->get('text_separator')
	    			);

	    			$this->data['breadcrumbs'][] = array(
	    				'text'      => $this->language->get('history'),
	    				'href'      => $this->url->link('account/order', '', 'SSL'),
	    				'separator' => $this->language->get('text_separator')
	    			);
	          $this->data['button_ok_url'] = $this->url->link('account/order', '', 'SSL');
	        }
	        else{
	          $this->data['button_ok_url'] = $this->url->link('common/home');
	        }
	      }
	  
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .
		    '/template/account/interplusplus_waiting.tpl')) {

		    $this->template = $this->config->get('config_template') .
		    '/template/account/interplusplus_waiting.tpl';
		} else {
		    $this->template = 'default/template/account/interplusplus_waiting.tpl';
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
	else{
		echo "No data";
	}
  }
}
?>