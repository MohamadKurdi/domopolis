<?php
class ControllerModuleDiscountInfo extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/discount_info');

		$this->document->setTitle($this->language->get('heading_title'));
		
                $this->load->model('setting/discount');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {    
                    
                    $this->model_setting_discount->editSettingInfo('discount_info', $this->request->post);		

                    $this->session->data['success'] = $this->language->get('text_success');

                    $this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
                $this->data['text_column_show'] = $this->language->get('text_column_show');
                $this->data['text_all'] = $this->language->get('text_all');
		$this->data['text_login'] = $this->language->get('text_login');
 
		$this->data['entry_discount'] = $this->language->get('entry_discount');
                $this->data['entry_show'] = $this->language->get('entry_show');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension'); 
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');             
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['dimension'])) {
			$this->data['error_dimension'] = $this->error['dimension'];
		} else {
			$this->data['error_dimension'] = array();
		}
				
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/discount_info', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/discount_info', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['discount_info_module'])) {
			$this->data['modules'] = $this->request->post['discount_info_module'];
		} elseif ($this->config->get('discount_info_module')) { 
			$this->data['modules'] = $this->config->get('discount_info_module');
		}	
                
                $results = $this->model_setting_discount->getCustomerGroups();

		foreach ($results as $result) {
                    $this->data['customer_groups'][] = array(
                            'customer_group_id' => $result['customer_group_id'],
                            'name'              => $result['name'],
                            'sort_order'        => $result['sort_order']
                    );
		}
				
		$this->data['layouts'] = $this->model_setting_discount->getLayouts();
                
                $this->data['discounts'] = array();
                
                $this->load->language('total/discount_cumulative');
                $this->data['discounts'][] = array(
                                'discount_id'   => 'discount_cumulative',
                                'discount_name' => $this->language->get('heading_title')
                );
                $this->load->language('total/discount_sum');
                $this->data['discounts'][] = array(
                                'discount_id'   => 'discount_sum',
                                'discount_name' => $this->language->get('heading_title')
                );
                $this->load->language('total/discount_count');
                $this->data['discounts'][] = array(
                                'discount_id'   => 'discount_count',
                                'discount_name' => $this->language->get('heading_title')
                );
                $this->load->language('total/discount_regular');
                $this->data['discounts'][] = array(
                                'discount_id'   => 'discount_regular',
                                'discount_name' => $this->language->get('heading_title')
                );
                $this->load->language('total/discount');
                $this->data['discounts'][] = array(
                                'discount_id'   => 'discount',
                                'discount_name' => $this->language->get('heading_title')
                );
                     
				
		$this->template = 'module/discount_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/discount_info')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['discount_info_module'])) {
			foreach ($this->request->post['discount_info_module'] as $key => $value) {
				if (!$value['width']) {
					$this->error['dimension'][$key] = $this->language->get('error_dimension');
				}			
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