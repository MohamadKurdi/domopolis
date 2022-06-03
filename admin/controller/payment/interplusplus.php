<?php
class ControllerPaymentinterplusplus extends Controller {
	private $error = array();

	public function index() {
    $this->install();
		$this->load->language('payment/interplusplus');
		$this->document->setTitle ($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
        $this->data['stores'][] = array('store_id' => 0, 'name' => 'По умолчанию');       
		$this->data['action_without_store'] = $this->url->link('payment/interplusplus', 'token=' . $this->session->data['token'], 'SSL');
				
		
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
		
		$this->data['action'] = $this->url->link('payment/interplusplus', 'store_id=' . $this->data['store_id'] . '&token=' . $this->session->data['token'], 'SSL');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('interplusplus', $this->request->post, $store_id);
			$this->session->data['success'] = $this->language->get('text_success');
		//	$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if ($this->data['store_id'] == 0)
		{
			$http_link = HTTP_CATALOG;
		} else {
			$http_link = $this->model_setting_setting->getKeySettingValue('config', 'config_url', $this->data['store_id']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_liqpay'] = $this->language->get('text_liqpay');
		$this->data['text_card'] = $this->language->get('text_card');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_password1'] = $this->language->get('entry_password1');
		$this->data['copy_result_url'] 	= $http_link . 'index.php?route=account/interplusplus/callback';
		$this->data['copy_success_url']	= $http_link . 'index.php?route=account/interplusplus/success';
		$this->data['copy_fail_url'] 	= $http_link . 'index.php?route=account/interplusplus/fail';
		$this->data['copy_waiting_url'] 	= $http_link . 'index.php?route=account/interplusplus/waiting';
		$this->data['entry_interplusplus_name_tab'] = $this->language->get('entry_interplusplus_name_tab');
		$this->data['text_my'] = $this->language->get('text_my');
		$this->data['text_default'] = $this->language->get('text_default');
    	$this->data['entry_style'] = $this->language->get('entry_style');
    	$this->data['entry_on_status'] = $this->language->get('entry_on_status');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['tab_general'] = $this->language->get('tab_general');
   		$this->data['entry_interplusplus_instruction_tab'] = $this->language->get('entry_interplusplus_instruction_tab');
    	$this->data['entry_interplusplus_instruction'] = $this->language->get('entry_interplusplus_instruction');
    	$this->data['entry_interplusplus_mail_instruction_tab'] = $this->language->get('entry_interplusplus_mail_instruction_tab');
    	$this->data['entry_interplusplus_mail_instruction'] = $this->language->get('entry_interplusplus_mail_instruction');
    	$this->data['entry_interplusplus_success_comment_tab'] = $this->language->get('entry_interplusplus_success_comment_tab');
    	$this->data['entry_interplusplus_success_comment'] = $this->language->get('entry_interplusplus_success_comment');
    	$this->data['entry_interplusplus_name'] = $this->language->get('entry_interplusplus_name');
    	$this->data['entry_interplusplus_success_alert_admin_tab'] = $this->language->get('entry_interplusplus_success_alert_admin_tab');
    	$this->data['entry_interplusplus_success_alert_customer_tab'] = $this->language->get('entry_interplusplus_success_alert_customer_tab');
    	$this->data['entry_interplusplus_success_page_tab'] = $this->language->get('entry_interplusplus_success_page_tab');
    	$this->data['entry_interplusplus_success_page_text'] = $this->language->get('entry_interplusplus_success_page_text');
    	$this->data['entry_interplusplus_fail_page_tab'] = $this->language->get('entry_interplusplus_fail_page_tab');
    	$this->data['entry_interplusplus_fail_page_text'] = $this->language->get('entry_interplusplus_fail_page_text');
    	$this->data['entry_interplusplus_waiting_page_tab'] = $this->language->get('entry_interplusplus_waiting_page_tab');
    	$this->data['entry_interplusplus_waiting_page_text'] = $this->language->get('entry_interplusplus_waiting_page_text');
    	$this->data['entry_button_later'] = $this->language->get('entry_button_later');


		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['login'])) {
			$this->data['error_login'] = $this->error['login'];
		} else {
			$this->data['error_login'] = '';
		}

		if (isset($this->error['password1'])) {
			$this->data['error_password1'] = $this->error['password1'];
		} else {
			$this->data['error_password1'] = '';
		}
		
		$interplusplus_config = $this->model_setting_setting->getSetting('interplusplus', $this->data['store_id']);
		
		if (isset($this->request->post['interplusplus_style'])) {
			$this->data['interplusplus_style'] = $this->request->post['interplusplus_style'];
		} else {
			$this->data['interplusplus_style'] = $interplusplus_config['interplusplus_style'];
		}
    
    if (isset($this->request->post['interplusplus_instruction_attach'])) {
			$this->data['interplusplus_instruction_attach'] = $this->request->post['interplusplus_instruction_attach'];
		} else {
			$this->data['interplusplus_instruction_attach'] = $this->config->get('interplusplus_instruction_attach');
		}  
		if (isset($this->request->post['interplusplus_instruction'])) {
      $this->data['interplusplus_instruction'] = $this->request->post['interplusplus_instruction'];
		} else {
			$this->data['interplusplus_instruction'] = $interplusplus_config['interplusplus_instruction'];
		}


		if (isset($this->request->post['interplusplus_name_attach'])) {
			$this->data['interplusplus_name_attach'] = $this->request->post['interplusplus_name_attach'];
		} else {
			$this->data['interplusplus_name_attach'] = $interplusplus_config['interplusplus_name_attach'];
		}  
		if (isset($this->request->post['interplusplus_name'])) {
      	$this->data['interplusplus_name'] = $this->request->post['interplusplus_name'];
		} else {
			$this->data['interplusplus_name'] = $interplusplus_config['interplusplus_name'];
		}

		if (isset($this->request->post['interplusplus_success_alert_admin'])) {
			$this->data['interplusplus_success_alert_admin'] = $this->request->post['interplusplus_success_alert_admin'];
		} else {
			$this->data['interplusplus_success_alert_admin'] = $interplusplus_config['interplusplus_success_alert_admin'];
		}

		if (isset($this->request->post['interplusplus_success_alert_customer'])) {
			$this->data['interplusplus_success_alert_customer'] = $this->request->post['interplusplus_success_alert_customer'];
		} else {
			$this->data['interplusplus_success_alert_customer'] = $interplusplus_config['interplusplus_success_alert_customer'];
		}

		if (isset($this->request->post['interplusplus_mail_instruction_attach'])) {
			$this->data['interplusplus_mail_instruction_attach'] = $this->request->post['interplusplus_mail_instruction_attach'];
		} else {
			$this->data['interplusplus_mail_instruction_attach'] = $interplusplus_config['interplusplus_mail_instruction_attach'];
		}  
		if (isset($this->request->post['interplusplus_mail_instruction'])) {
      	$this->data['interplusplus_mail_instruction'] = $this->request->post['interplusplus_mail_instruction'];
		} else {
			$this->data['interplusplus_mail_instruction'] = $interplusplus_config['interplusplus_mail_instruction'];
		}

		if (isset($this->request->post['interplusplus_success_comment_attach'])) {
			$this->data['interplusplus_success_comment_attach'] = $this->request->post['interplusplus_success_comment_attach'];
		} else {
			$this->data['interplusplus_success_comment_attach'] = $interplusplus_config['interplusplus_success_comment_attach'];
		}  
		if (isset($this->request->post['interplusplus_success_comment'])) {
      	$this->data['interplusplus_success_comment'] = $this->request->post['interplusplus_success_comment'];
		} else {
			$this->data['interplusplus_success_comment'] = $interplusplus_config['interplusplus_success_comment'];
		}

		if (isset($this->request->post['interplusplus_success_page_text_attach'])) {
			$this->data['interplusplus_success_page_text_attach'] = $this->request->post['interplusplus_success_page_text_attach'];
		} else {
			$this->data['interplusplus_success_page_text_attach'] = $interplusplus_config['interplusplus_success_page_text_attach'];
		}  
		if (isset($this->request->post['interplusplus_success_page_text'])) {
      	$this->data['interplusplus_success_page_text'] = $this->request->post['interplusplus_success_page_text'];
		} else {
			$this->data['interplusplus_success_page_text'] = $interplusplus_config['interplusplus_success_page_text'];
		}

		if (isset($this->request->post['interplusplus_fail_page_text_attach'])) {
			$this->data['interplusplus_fail_page_text_attach'] = $this->request->post['interplusplus_fail_page_text_attach'];
		} else {
			$this->data['interplusplus_fail_page_text_attach'] = $interplusplus_config['interplusplus_fail_page_text_attach'];
		}  
		if (isset($this->request->post['interplusplus_fail_page_text'])) {
      	$this->data['interplusplus_fail_page_text'] = $this->request->post['interplusplus_fail_page_text'];
		} else {
			$this->data['interplusplus_fail_page_text'] = $interplusplus_config['interplusplus_fail_page_text'];
		}

		if (isset($this->request->post['interplusplus_waiting_page_text_attach'])) {
			$this->data['interplusplus_waiting_page_text_attach'] = $this->request->post['interplusplus_waiting_page_text_attach'];
		} else {
			$this->data['interplusplus_waiting_page_text_attach'] = $interplusplus_config['interplusplus_waiting_page_text_attach'];
		}  
		if (isset($this->request->post['interplusplus_waiting_page_text'])) {
      	$this->data['interplusplus_waiting_page_text'] = $this->request->post['interplusplus_waiting_page_text'];
		} else {
			$this->data['interplusplus_waiting_page_text'] = $interplusplus_config['interplusplus_waiting_page_text'];
		}

		if (isset($this->request->post['interplusplus_button_later'])) {
      	$this->data['interplusplus_button_later'] = $this->request->post['interplusplus_button_later'];
		} else {
			$this->data['interplusplus_button_later'] = $interplusplus_config['interplusplus_button_later'];
		}

    
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      =>  $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/free_checkout', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	

		if (isset($this->request->post['interplusplus_login'])) {
			$this->data['interplusplus_login'] = $this->request->post['interplusplus_login'];
		} else {
			$this->data['interplusplus_login'] = $interplusplus_config['interplusplus_login'];
		}

		if (isset($this->request->post['interplusplus_password1'])) {
			$this->data['interplusplus_password1'] = $this->request->post['interplusplus_password1'];
		} else {
			$this->data['interplusplus_password1'] = $interplusplus_config['interplusplus_password1'];
		}
    
    if (isset($this->request->post['interplusplus_on_status_id'])) {
			$this->data['interplusplus_on_status_id'] = $this->request->post['interplusplus_on_status_id'];
		} else {
			$this->data['interplusplus_on_status_id'] = $interplusplus_config['interplusplus_on_status_id'];
		}

		if (isset($this->request->post['interplusplus_order_status_id'])) {
			$this->data['interplusplus_order_status_id'] = $this->request->post['interplusplus_order_status_id'];
		} else {
			$this->data['interplusplus_order_status_id'] = $interplusplus_config['interplusplus_order_status_id'];
		}

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['interplusplus_geo_zone_id'])) {
			$this->data['interplusplus_geo_zone_id'] = $this->request->post['interplusplus_geo_zone_id'];
		} else {
			$this->data['interplusplus_geo_zone_id'] = $interplusplus_config['interplusplus_geo_zone_id'];
		}

		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['interplusplus_status'])) {
			$this->data['interplusplus_status'] = $this->request->post['interplusplus_status'];
		} else {
			$this->data['interplusplus_status'] = $interplusplus_config['interplusplus_status'];
		}

		if (isset($this->request->post['interplusplus_sort_order'])) {
			$this->data['interplusplus_sort_order'] = $this->request->post['interplusplus_sort_order'];
		} else {
			$this->data['interplusplus_sort_order'] = $interplusplus_config['interplusplus_sort_order'];
		}

		$this->template = 'payment/interplusplus.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
  public function install() {
     $query = $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "interplusplus (inter_id INT(11) AUTO_INCREMENT, num_order INT(8), sum INT(7), user TEXT, email TEXT, status TEXT, date_created DATETIME, date_enroled DATE, PRIMARY KEY (inter_id))");
     }
  public function status() {
  	$this->load->language('payment/interplusplus');
		$this->document->setTitle ($this->language->get('heading_title'));
    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['status_title'] = $this->language->get('status_title');
    
    $viewstatuses = $this->db->query("SELECT `num_order`, `sum`, `user`, `email`, `date_enroled` FROM `" . DB_PREFIX . "interplusplus` ORDER BY `date_enroled` DESC");
    $this->data['viewstatuses'] = $viewstatuses->rows;
    
    $this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      =>  $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_order'),
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);
		
    
  	$this->template = 'payment/interplusplus_view_status.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    
  }

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/interplusplus')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['interplusplus_login']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['interplusplus_password1']) {
			$this->error['password1'] = $this->language->get('error_password1');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>