<?php

class ControllerPaymentWayforpay extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('payment/wayforpay');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
//------------------------------------------------------------

		$this->load->model('setting/store');

        $this->data['stores'] = $this->model_setting_store->getStores();  
		$this->data['action_without_store'] = $this->url->link('payment/wayforpay', 'token=' . $this->session->data['token']);
		
		if (isset($this->request->get['store_id'])) {
            $this->data['store_id'] = $this->request->get['store_id'];
        } else {
            $this->data['store_id'] = 0;
        }
		
		if (isset($this->request->post['store_id'])){
			$store_id = $this->request->post['store_id'];
			unset($this->request->post['store_id']);
		} else {
			$store_id = 0;			
		}
		
		$this->data['action'] = $this->url->link('payment/wayforpay', 'store_id=' . $this->data['store_id'] . '&token=' . $this->session->data['token']);

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('wayforpay', $this->request->post, $store_id);
            $this->session->data['success'] = $this->language->get('text_success');
        //   $this->response->redirect($this->url->link('payment/wayforpay', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $arr = array(
            "heading_title", "text_payment", "text_success", "text_pay", "text_card",
            "entry_merchant", "entry_secretkey", "entry_order_status",
            "entry_returnUrl", "entry_serviceUrl", "entry_language", "entry_status",
            "entry_sort_order", "error_permission", "error_merchant", "error_secretkey");

        foreach ($arr as $v) {
            $this->data[$v] = $this->language->get($v)?$this->language->get($v):'';
        }
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        #$this->data['LUURL'] = "index.php?route=payment/wayforpay/callback";


//------------------------------------------------------------
        $arr = array("warning", "merchant", "secretkey", "type");
        foreach ($arr as $v)
            $this->data['error_' . $v] = (isset($this->error[$v])) ? $this->error[$v] : "";
//------------------------------------------------------------

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token']),
            'separator' => ' :: '
        );

    //    $this->data['action'] = $this->url->link('payment/wayforpay', 'token=' . $this->session->data['token']);
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token']);
        
        $wayforpay_config = $this->model_setting_setting->getSetting('wayforpay', $this->data['store_id']);

        if (isset($this->request->post['wayforpay_geo_zone_id'])) {
			$this->data['wayforpay_geo_zone_id'] = $this->request->post['wayforpay_geo_zone_id'];
		} else {
			$this->data['wayforpay_geo_zone_id'] = $wayforpay_config['wayforpay_geo_zone_id'];
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

//------------------------------------------------------------
        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $arr = array("wayforpay_merchant", "wayforpay_secretkey", "wayforpay_returnUrl", "wayforpay_serviceUrl",
            "wayforpay_language", "wayforpay_status", "wayforpay_status_fake", "wayforpay_sort_order", "wayforpay_order_status_id", "wayforpay_ismethod");

        foreach ($arr as $v) {
            $this->data[$v] = (isset($this->request->post[$v])) ? $this->request->post[$v] : $wayforpay_config[$v];
        }
//------------------------------------------------------------

        $this->template = 'payment/wayforpay.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

//------------------------------------------------------------
    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/wayforpay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['wayforpay_merchant']) {
            $this->error['merchant'] = $this->language->get('error_merchant');
        }

        if (!$this->request->post['wayforpay_secretkey']) {
            $this->error['secretkey'] = $this->language->get('error_secretkey');
        }

        return (!$this->error) ? true : false;
    }
}
