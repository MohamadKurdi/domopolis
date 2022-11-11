<?php
class ControllerPaymentTransferPlus extends Controller {
    private $type = 'payment';
   	private $name = 'transfer_plus';

	protected function index() {
        $this->data = array_merge($this->data, $this->load->language($this->type . '/' . $this->name));

        $this->data['text_instruction'] = nl2br($this->language->get('text_instruction'));

        $m = $this->getCurrentPayment();

        if (isset($m['info'])) {
		    $this->data['info'] = html_entity_decode($m['info'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        }
        else {
            $this->data['info'] = '';
        }

		$this->data['continue'] = $this->url->link('checkout/success');

        $this->data['name'] = $this->name;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' .$this->type . '/' .$this->name. '.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/' .$this->type . '/' .$this->name. '.tpl';
        }
        else {
            $this->template = 'default/template/' .$this->type . '/' .$this->name. '.tpl';
        }

		$this->render(); 
	}


	public function confirm() {
        $this->language->load($this->type . '/' . $this->name);
		
		$this->load->model('checkout/order');

        $m = $this->getCurrentPayment();

        if (isset($m['info'])) {
            $comment = html_entity_decode($m['info'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        } else {
            $comment = '';
        }

        $this->data['name'] = $this->name;
		
        if (isset($this->session->data['order_id']) and isset($m['order_status_id'])) {
		    $this->model_checkout_order->confirm($this->session->data['order_id'], $m['order_status_id'], $comment, true);
        }
	}


    private function getCurrentPayment() {
        if (isset($this->session->data['payment_method']['code'])) {
            $current_payment_method = $this->session->data['payment_method']['code'];

            $arr_payment_info = explode('.', $current_payment_method);

            $modules = $this->config->get($this->name.'_module');

            if (isset($arr_payment_info[1])) {
                foreach ($modules as $key => $value) {
                    if ($key == $arr_payment_info[1]) {
                        $m = $value;
                        return $m;

                        break;
                    }
                }
            }
        }

        return false;
    }
}
?>