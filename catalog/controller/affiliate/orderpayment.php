<?php
class ControllerAffiliateOrderpayment extends Controller {
    private $error = array();

    public function index() {
        
        if (!$this->affiliate->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('affiliate/orderpayment', '');

            $this->redirect($this->url->link('affiliate/login', '', 'SSL'));
        }

        $this->language->load('affiliate/orderpayment');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('module/affiliate');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->model_module_affiliate->editOrderPayment($this->request->post);
            $my_request_payment = (double)$this->model_module_affiliate->ConvertLocalCurrency((double)$this->request->post['request_payment']);
        
            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->currency->format($my_request_payment));

            $this->redirect($this->url->link('affiliate/account', '', 'SSL'));
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('affiliate/account', ''),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('affiliate/orderpayment', ''),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_request_payment_history'] = $this->language->get('text_request_payment_history');

        $this->data['entry_request_payment'] = $this->language->get('entry_request_payment');
        $this->data['entry_payment'] = $this->language->get('entry_payment');
        
        $this->load->model('module/affiliate');
        $this->data['title_request_payment'] = sprintf($this->language->get('title_request_payment'),$this->currency->format($this->config->get('affiliate_total')));

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');

        $this->data['action'] = $this->url->link('affiliate/orderpayment', '');
        $this->data['text_request_balanse'] = $this->language->get('text_request_balanse');
        $this->load->model('affiliate/transaction');
        $this->data['balance'] = $this->currency->format($this->model_affiliate_transaction->getBalance());
		$this->data['max_balance_double'] = (double)$this->model_affiliate_transaction->getBalance();
		$this->data['min_balance_double'] = (double)$this->config->get('affiliate_total');
        $this->data['min_balance'] = $this->currency->format($this->config->get('affiliate_total'));
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->load->model('affiliate/affiliate');
            $affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
        }
        if (isset($this->request->post['request_payment_history'])) {
            $this->data['request_payment_history'] = $this->request->post['request_payment_history'];
        } elseif (!empty($affiliate_info)) {
            $this->data['request_payment_history'] = $this->currency->format($affiliate_info['request_payment']);
        } else {
            
        $this->load->model('affiliate/affiliate');
        $affiliate_info_error = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
            $this->data['request_payment_history'] = $this->currency->format($affiliate_info_error['request_payment']);
        }

        if (isset($this->request->post['request_payment'])) {
            $this->data['request_payment'] = $this->request->post['request_payment'];
        } elseif (!empty($affiliate_info)) {
            $this->data['request_payment'] = $affiliate_info['request_payment'];
        } else {
            $this->data['request_payment'] = '0.00';
        }
        if (isset($this->error['balance_max'])) {
            $this->data['error_max'] = $this->error['balance_max'];
        } else {
            $this->data['error_max'] = '';
        }
        if (isset($this->error['balance_nil'])) {
            $this->data['error_nil'] = $this->error['balance_nil'];
        } else {
            $this->data['error_nil'] = '';
        }

        if (isset($this->error['balance_min'])) {
            $this->data['error_min'] = sprintf($this->error['balance_min'],$this->currency->format($this->config->get('affiliate_total')));
        } else {
            $this->data['error_min'] = '';
        }
        
        $this->data['back'] = $this->url->link('affiliate/account', '');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/orderpayment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/affiliate/orderpayment.tpl';
        } else {
            $this->template = 'default/template/affiliate/orderpayment.tpl';
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
    private function validate() {
        
        $this->load->model('affiliate/transaction');
        $balance = (double)$this->model_affiliate_transaction->getBalance();
        $min_balance = (double)$this->config->get('affiliate_total');
        $request_payment = (double)$this->model_module_affiliate->ConvertLocalCurrency((double)$this->request->post['request_payment']);
        
         
         
        if ($balance < $min_balance) {
            $this->error['balance_nil'] = $this->language->get('error_nil');
        } else {
            
            if ($request_payment < $min_balance) {
                $this->error['balance_min'] = $this->language->get('error_min');
            }
            if (($request_payment > $min_balance) && ($request_payment > $balance )) {
                $this->error['balance_max'] = $this->language->get('error_max');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>