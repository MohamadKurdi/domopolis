<?
	
	class ControllerPaymentLiqpayLaterpay extends Controller
	{
		
		/**
			* Index action
			*
			* @return void
		*/
		protected function index()
		{
			
			
			
		}
		
		public function laterpay(){
			
			if (!$this->validateLaterpay()){
				$this->redirect('account/order');
			}
			
			
			$this->load->model('checkout/order');
			
			$order_id = (int)$this->request->get['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			$description = 'Заказ #'.$order_id;
			
			$order_id .= '#'.time();
			$result_url = $this->url->link('account/order', '');
			$server_url = $this->url->link('payment/liqpay/server', '');
			
			$private_key = $this->config->get('liqpay_private_key');
			$public_key = $this->config->get('liqpay_public_key');
			$type = 'buy';
			$currency = $order_info['currency_code'];
			if ($currency == 'RUR') { $currency = 'RUB'; }
			
			$amount = $sAmount = number_format($order_info['total_national'], 2, '.', '');		
			
			$version  = '3';
			//$language = $this->language->get('code');
			
			//$language = $language == 'ru' ? 'ru' : 'en';
			$pay_way  = $this->config->get('liqpay_pay_way');
			$language = $this->config->get('liqpay_language');
			
			$send_data = array('version'    => $version,
			'public_key'  => $public_key,
			'amount'      => $amount,
			'currency'    => $currency,
			'description' => $description,
			'order_id'    => $order_id,
			'type'        => $type,
			'language'    => $language,
			'server_url'  => $server_url,
			'result_url'  => $result_url);
			if(isset($pay_way)){
				$send_data['pay_way'] = $pay_way;
			}
			
			$data = base64_encode(json_encode($send_data));
			
			$signature = base64_encode(sha1($private_key.$data.$private_key, 1));
			
			$this->data['action']         = $this->config->get('liqpay_action');
			$this->data['signature']      = $signature;
			$this->data['data']           = $data;
			$this->data['button_confirm'] = 'Оформить заказ и оплатить с помощью LiqPay';
			$this->data['url_confirm']    = $this->url->link('payment/liqpay/confirm');
			
			$this->template = $this->config->get('config_template').'/template/payment/liqpay_laterpay.tpl';
			
			if (!file_exists(DIR_TEMPLATE.$this->template)) {
				$this->template = 'default/template/payment/liqpay_laterpay.tpl';
			}
			
			$this->response->setOutput($this->render());
		}
		
		
		protected function validateLaterpay() {
			if ((!isset($this->request->get['order_id'])) || (!isset($this->request->get['order_tt'])) || (!isset($this->request->get['order_fl']))) {
				return false;
				} else {
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);
				if ((!$order_info) || ($this->request->get['order_id'] != $order_info['order_id']) || ($this->request->get['order_tt'] != $order_info['total_national']) || ($this->request->get['order_fl']) != md5($order_info['firstname'] . $order_info['lastname'])) {
					return false;
				}
			}
			return true;
		}		
		
	}	