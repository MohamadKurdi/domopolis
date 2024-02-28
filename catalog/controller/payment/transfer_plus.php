<?php
class ControllerPaymentTransferPlus extends Controller {
    private $type = 'payment';
   	private $name = 'transfer_plus';

	protected function index() {        
        $this->data = array_merge($this->data, $this->load->language('payment/transfer_plus'));

        $currentPayment = $this->getCurrentPayment();

        if (!empty($currentPayment['info']) && !empty($currentPayment['info'][$this->config->get('config_language_id')])) {
		    $this->data['info'] = html_entity_decode($currentPayment['info'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        } else {
            $this->data['info'] = '';
        }

        $this->data['text_instruction']  = nl2br($this->language->get('text_instruction'));
		$this->data['continue']          = $this->url->link('checkout/success');
        $this->data['name']              = $this->name;

        $this->template = 'payment/transfer_plus.tpl';
		$this->render(); 
	}

	public function confirm() {
        $this->language->load('payment/transfer_plus');
		
		$this->load->model('checkout/order');

        $currentPayment = $this->getCurrentPayment();

        if (!empty($currentPayment['info']) && !empty($currentPayment['info'][$this->config->get('config_language_id')])) {
            $comment = html_entity_decode($currentPayment['info'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        } else {
            $comment = '';
        }

        $this->data['name'] = 'transfer_plus';
		
        if (isset($this->session->data['order_id']) && isset($currentPayment['order_status_id'])) {
		    $this->model_checkout_order->confirm($this->session->data['order_id'], $currentPayment['order_status_id'], $comment, true);
        }
	}

    private function getCurrentPayment() {
        if (!empty($this->session->data['payment_method']['code'])) {
            $current_payment_method = $this->session->data['payment_method']['code'];

            $arr_payment_info = explode('.', $current_payment_method);

            $modules = $this->config->get('transfer_plus_module');

            if (isset($arr_payment_info[1])) {
                foreach ($modules as $key => $value) {
                    if ($key == $arr_payment_info[1]) {
                        return $value;
                        break;
                    }
                }
            }
        }

        return false;
    }
}