<?php 
class ControllerTotalDiscountCumulative extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/discount_cumulative');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/discount');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_discount->editSetting('discount_cumulative', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

                $this->data['text_all_time'] = $this->language->get('text_all_time');
                $this->data['text_period_time'] = $this->language->get('text_period_time');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
                $this->data['text_all_status'] = $this->language->get('text_all_status');
                $this->data['text_all'] = $this->language->get('text_all');

                $this->data['entry_not_color'] = $this->language->get('entry_not_color');
                $this->data['entry_bronze'] = $this->language->get('entry_bronze');
                $this->data['entry_silver'] = $this->language->get('entry_silver');
                $this->data['entry_gold'] = $this->language->get('entry_gold');
                $this->data['entry_red'] = $this->language->get('entry_red');
                $this->data['entry_green'] = $this->language->get('entry_green');
                $this->data['entry_blue'] = $this->language->get('entry_blue');
                $this->data['entry_black'] = $this->language->get('entry_black');     
                $this->data['entry_name_color'] = $this->language->get('entry_name_color');
                $this->data['entry_cumulative'] = $this->language->get('entry_cumulative');
                $this->data['entry_proc'] = $this->language->get('entry_proc');
                $this->data['entry_start_date'] = $this->language->get('entry_start_date');
                $this->data['entry_end_date'] = $this->language->get('entry_end_date');
                $this->data['entry_sum_date'] = $this->language->get('entry_sum_date');
                $this->data['entry_sum_status'] = $this->language->get('entry_sum_status');
                $this->data['entry_group'] = $this->language->get('entry_group');                
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
                $this->data['entry_products'] = $this->language->get('entry_products');
                $this->data['entry_product1'] = $this->language->get('entry_product1');
                $this->data['entry_product2'] = $this->language->get('entry_product2');
                $this->data['entry_product3'] = $this->language->get('entry_product3');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
                $this->data['button_edit'] = $this->language->get('button_edit'); 
                $this->data['button_add'] = $this->language->get('button_add');
                $this->data['button_remove'] = $this->language->get('button_remove');                

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/discount_cumulative', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/discount_cumulative', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['order_statuses'] = $this->model_setting_discount->getOrderStatuses();
                
                if (isset($this->request->post['discount_cumulative_order_status'])) {
			$this->data['discount_cumulative_order_status'] = $this->request->post['discount_cumulative_order_status'];
		} else {
			$this->data['discount_cumulative_order_status'] = $this->config->get('discount_cumulative_order_status');
		}
                
                           
                if (isset($this->request->post['discount_cumulative_rows_num'])) {
			$this->data['discount_cumulative_rows_num'] = $this->request->post['discount_cumulative_rows_num'];
		} else {
			$this->data['discount_cumulative_rows_num'] = $this->config->get('discount_cumulative_rows_num');
		}
                
                if (!$this->data['discount_cumulative_rows_num']) {
                        $this->data['discount_cumulative_rows_num'] = 1;
                }
                           
                $step = 1;
                for ($num = 1; $num <= $this->data['discount_cumulative_rows_num']; $num++) {
                    $discount_cumulative_row_step_name = 'discount_cumulative_row' . $step . '_name';
                    $discount_cumulative_row_step_color = 'discount_cumulative_row' . $step . '_color';
                    $discount_cumulative_row_step_cumulative = 'discount_cumulative_row' . $step . '_cumulative';
                    $discount_cumulative_row_step_proc = 'discount_cumulative_row' . $step . '_proc'; 
                    $discount_cumulative_row_step_start = 'discount_cumulative_row' . $step . '_start';
                    $discount_cumulative_row_step_end = 'discount_cumulative_row' . $step . '_end';
                    $discount_cumulative_row_step_sum = 'discount_cumulative_row' . $step . '_sum';
                    
                    $discount_cumulative_row_name = 'discount_cumulative_row' . $num . '_name';
                    $discount_cumulative_row_color = 'discount_cumulative_row' . $num . '_color';
                    $discount_cumulative_row_cumulative = 'discount_cumulative_row' . $num . '_cumulative';
                    $discount_cumulative_row_proc = 'discount_cumulative_row' . $num . '_proc';
                    $discount_cumulative_row_start = 'discount_cumulative_row' . $num . '_start';
                    $discount_cumulative_row_end = 'discount_cumulative_row' . $num . '_end';
                    $discount_cumulative_row_sum = 'discount_cumulative_row' . $num . '_sum';
              
                    if ((int)$this->config->get($discount_cumulative_row_cumulative) > 0 && (int)$this->config->get($discount_cumulative_row_proc) > 0) {
                            $this->data[$discount_cumulative_row_step_name] = $this->config->get($discount_cumulative_row_name);
                            $this->data[$discount_cumulative_row_step_color] = $this->config->get($discount_cumulative_row_color);
                            $this->data[$discount_cumulative_row_step_cumulative] = $this->config->get($discount_cumulative_row_cumulative);
                            $this->data[$discount_cumulative_row_step_proc] = $this->config->get($discount_cumulative_row_proc);
                            $this->data[$discount_cumulative_row_step_start] = $this->config->get($discount_cumulative_row_start);
                            $this->data[$discount_cumulative_row_step_end] = $this->config->get($discount_cumulative_row_end);
                            $this->data[$discount_cumulative_row_step_sum] = $this->config->get($discount_cumulative_row_sum);
                            $step++;
                    }
                }
              
                $this->data['discount_cumulative_rows_num'] = $step - 1;              
               
		$this->data['order_statuses'] = $this->model_setting_discount->getOrderStatuses();  
                
                if (isset($this->request->post['discount_cumulative_order_status'])) {
			$this->data['discount_cumulative_order_status'] = $this->request->post['discount_cumulative_order_status'];
		} else {
			$this->data['discount_cumulative_order_status'] = $this->config->get('discount_cumulative_order_status');
		}
                
                $results = $this->model_setting_discount->getCustomerGroups();
		foreach ($results as $result) {
                    $this->data['customer_groups'][] = array(
                            'customer_group_id' => $result['customer_group_id'],
                            'name'              => $result['name']
                    );
		}
                if (isset($this->request->post['discount_cumulative_group'])) {
			$this->data['discount_cumulative_group'] = $this->request->post['discount_cumulative_group'];
		} else {
			$this->data['discount_cumulative_group'] = $this->config->get('discount_cumulative_group');
		}
                
		if (isset($this->request->post['discount_cumulative_status'])) {
			$this->data['discount_cumulative_status'] = $this->request->post['discount_cumulative_status'];
		} else {
			$this->data['discount_cumulative_status'] = $this->config->get('discount_cumulative_status');
		}

		if (isset($this->request->post['discount_cumulative_sort_order'])) {
			$this->data['discount_cumulative_sort_order'] = $this->request->post['discount_cumulative_sort_order'];
		} else {
			$this->data['discount_cumulative_sort_order'] = $this->config->get('discount_cumulative_sort_order');
		}
                
                if (isset($this->request->post['discount_cumulative_product1'])) {
			$this->data['discount_cumulative_product1'] = $this->request->post['discount_cumulative_product1'];
		} else {
			$this->data['discount_cumulative_product1'] = $this->config->get('discount_cumulative_product1');
		}
                  
                if (isset($this->request->post['discount_cumulative_product2'])) {
			$this->data['discount_cumulative_product2'] = $this->request->post['discount_cumulative_product2'];
		} else {
			$this->data['discount_cumulative_product2'] = $this->config->get('discount_cumulative_product2');
		}
                
                if (isset($this->request->post['discount_cumulative_product3'])) {
			$this->data['discount_cumulative_product3'] = $this->request->post['discount_cumulative_product3'];
		} else {
			$this->data['discount_cumulative_product3'] = $this->config->get('discount_cumulative_product3');
		}

		$this->data['href_edit_pro'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount_cumulative', 'SSL');
                
		$this->template = 'total/discount_cumulative.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/discount_cumulative')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>