<?php
/**
 * e-mail: s-m-o-k@list.ru
 */
class ControllerPaymentPayonline extends Controller {
	public function index() {

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$merchantId = $this->config->get('payonline_merchant');
		$orderId = $this->session->data['order_id'];

		$total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$amount = number_format(round($total, 2), 2, ".", "");

		$currency = $order_info['currency_code'];

		$securityKey = $this->config->get('payonline_security');

		$baseQuery = "MerchantId=" . $merchantId . "&OrderId=" . $orderId . "&Amount=" . $amount . "&Currency=" . $currency;

		$queryWithSecurityKey = $baseQuery . "&PrivateSecurityKey=" . $securityKey;

		$hash = md5($queryWithSecurityKey);

		$this->data['fields']['MerchantId'] = $merchantId;
		$this->data['fields']['OrderId'] = $orderId;
		$this->data['fields']['Amount'] = $amount;
		$this->data['fields']['Currency'] = $currency;
		$this->data['fields']['SecurityKey'] = $hash;
		$this->data['fields']['ReturnUrl'] = $this->url->link('checkout/success');

		$this->data['action'] = "https://secure.payonlinesystem.com/ru/payment/";

		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payonline.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/payonline.tpl';
		} else {
			$this->template = 'default/template/payment/payonline.tpl';
		}

		$this->render();
	}


	public function callback() {
		$this->log->write(print_r($this->request->post, true));

		$payonline_securityKey = $this->config->get('payonline_security');

		$order_amount = $this->request->post['Amount'];

		$securityKey = $this->request->post['SecurityKey'];
		$orderId = $this->request->post['OrderId'];
		$hash = "DateTime=" . $this->request->post['DateTime'];
		$hash .= "&TransactionID=" . $this->request->post['TransactionID'];
		$hash .= "&OrderId=" . $this->request->post['OrderId'];
		$hash .= "&Amount=" . $this->request->post['Amount'];
		$hash .= "&Currency=" . $this->request->post['Currency'];
		$hash .= "&PrivateSecurityKey=" . $payonline_securityKey;

		if(md5($hash) == $securityKey) {
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($orderId);

			if(!$order_info) {
				$this->log->write('!order_info not found');
				return 0;
			}

			if(number_format($order_amount) >= number_format($order_info['total'])) {
				$this->model_checkout_order->confirm($orderId, $this->config->get('payonline_order_status_id'));
			} else {
				$this->log->write("!$order_amount !" . number_format($order_info['currency_code']));
			}
		} else {
			$this->log->write('!security key invalid');
		}
	}
}

?>