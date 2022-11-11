<?php 
class ControllerTotalDiscountCount extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/discount_count');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/discount');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_discount->editSetting('discount_count', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
                $this->data['text_sum'] = $this->language->get('text_sum');
                $this->data['text_sum_all'] = $this->language->get('text_sum_all');
                $this->data['text_all'] = $this->language->get('text_all');
		$this->data['text_login'] = $this->language->get('text_login');

                $this->data['entry_amount'] = $this->language->get('entry_amount');
                $this->data['entry_proc'] = $this->language->get('entry_proc');
                $this->data['entry_sum'] = $this->language->get('entry_sum'); 
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
			'href'      => $this->url->link('total/discount_count', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/discount_count', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

                          
                if (isset($this->request->post['discount_count_rows_num'])) {
			$this->data['discount_count_rows_num'] = $this->request->post['discount_count_rows_num'];
		} else {
			$this->data['discount_count_rows_num'] = $this->config->get('discount_count_rows_num');
		}
                
                if (!$this->data['discount_count_rows_num']) 
                        $this->data['discount_count_rows_num'] = 1;
                
                           
                $step = 1;
                for ($num = 1; $num <= $this->data['discount_count_rows_num']; $num++) { 
                    $discount_count_row_step_count = 'discount_count_row' . $step . '_count';
                    $discount_count_row_step_proc = 'discount_count_row' . $step . '_proc';     
                    $discount_count_row_count = 'discount_count_row' . $num . '_count';
                    $discount_count_row_proc = 'discount_count_row' . $num . '_proc';
              
                    if ((int)$this->config->get($discount_count_row_count) > 0 && (int)$this->config->get($discount_count_row_proc) > 0) {
                            $this->data[$discount_count_row_step_count] = $this->config->get($discount_count_row_count);
                            $this->data[$discount_count_row_step_proc] = $this->config->get($discount_count_row_proc);
                            $step++;
                    }
                }
              
                $this->data['discount_count_rows_num'] = $step - 1;              
               
                $results = $this->model_setting_discount->getCustomerGroups();
		foreach ($results as $result) {
                    $this->data['customer_groups'][] = array(
                            'customer_group_id' => $result['customer_group_id'],
                            'name'              => $result['name']
                    );
		}
                
                if (isset($this->request->post['discount_count_sum'])) {
			$this->data['discount_count_sum'] = $this->request->post['discount_count_sum'];
		} else {
			$this->data['discount_count_sum'] = $this->config->get('discount_count_sum');
		}
                if (isset($this->request->post['discount_count_group'])) {
			$this->data['discount_count_group'] = $this->request->post['discount_count_group'];
		} else {
			$this->data['discount_count_group'] = $this->config->get('discount_count_group');
		}
                    
		if (isset($this->request->post['discount_count_status'])) {
			$this->data['discount_count_status'] = $this->request->post['discount_count_status'];
		} else {
			$this->data['discount_count_status'] = $this->config->get('discount_count_status');
		}

		if (isset($this->request->post['discount_count_sort_order'])) {
			$this->data['discount_count_sort_order'] = $this->request->post['discount_count_sort_order'];
		} else {
			$this->data['discount_count_sort_order'] = $this->config->get('discount_count_sort_order');
		}
                
                if (isset($this->request->post['discount_count_product1'])) {
			$this->data['discount_count_product1'] = $this->request->post['discount_count_product1'];
		} else {
			$this->data['discount_count_product1'] = $this->config->get('discount_count_product1');
		}
                  
                if (isset($this->request->post['discount_count_product2'])) {
			$this->data['discount_count_product2'] = $this->request->post['discount_count_product2'];
		} else {
			$this->data['discount_count_product2'] = $this->config->get('discount_count_product2');
		}
                
                if (isset($this->request->post['discount_count_product3'])) {
			$this->data['discount_count_product3'] = $this->request->post['discount_count_product3'];
		} else {
			$this->data['discount_count_product3'] = $this->config->get('discount_count_product3');
		}
                
                $this->data['href_edit_pro'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount_count', 'SSL');
                
		$this->template = 'total/discount_count.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/discount_count')) {
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