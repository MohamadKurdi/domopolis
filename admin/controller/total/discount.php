<?php 
class ControllerTotalDiscount extends Controller { 
	private $error = array(); 
	 
	public function index() { 
            
		$this->load->language('total/discount');
                
		$this->load->model('setting/discount');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
                     
                        $this->model_setting_discount->editSetting('discount', $this->request->post);

                        if($_POST['clickedRow'] > 0) {
                            $this->redirect($this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&row=' . $_POST['clickedRow'], 'SSL'));
                        } else {
                            $this->session->data['success'] = $this->language->get('text_success');
                            $this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
                        }
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
                $this->data['text_all'] = $this->language->get('text_all');      
                $this->data['text_edit_list'] = $this->language->get('text_edit_list');
                $this->data['text_my_change'] = $this->language->get('text_my_change');    
		
                $this->data['entry_group'] = $this->language->get('entry_group');                
                $this->data['entry_proc'] = $this->language->get('entry_proc');
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
			'href'      => $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
                
                $results = $this->model_setting_discount->getCustomerGroups();
		foreach ($results as $result) {
                    $this->data['customer_groups'][] = array(
                            'customer_group_id' => $result['customer_group_id'],
                            'name'              => $result['name']
                    );
		}

                if (isset($this->request->post['discount_rows_num'])) {
			$this->data['discount_rows_num'] = $this->request->post['discount_rows_num'];
		} else {
			$this->data['discount_rows_num'] = $this->config->get('discount_rows_num');
		}  
                
                if (!$this->data['discount_rows_num']) 
                        $this->data['discount_rows_num'] = 1;
                    
                $step = 1;
                for ($num = 1; $num <= $this->data['discount_rows_num']; $num++) { 
                    $discount_row_step_proc = 'discount_row' . $step . '_proc';     
                    $discount_row_proc = 'discount_row' . $num . '_proc';
                    $discount_group_row_step = 'discount_group_row' . $step;     
                    $discount_group_row = 'discount_group_row' . $num;
                    $discount_customer_list_step = 'discount_row' . $step . '_customer_list';     
                    $discount_customer_list = 'discount_row' . $num . '_customer_list';
              
                    if ((int)$this->config->get($discount_row_proc) > 0) {
                        $this->data[$discount_row_step_proc] = $this->config->get($discount_row_proc);
                        $this->data[$discount_group_row_step] = $this->config->get($discount_group_row);

                        if ($this->config->get($discount_customer_list)) {

                            $customer_list = array();
                            $customer_list = $this->config->get($discount_customer_list);
                            for($i = 0; $i<count($customer_list); $i++) {
                                $this->data[$discount_customer_list_step][] = array (
                                        'customer_id' => $customer_list[$i]
                                );
                            }
                        } else {
                            $this->data[$discount_customer_list_step] = 0;
                        }
                            
                            $step++;
                    }
                }
                $this->data['discount_rows_num'] = $step - 1;       
                
                
                if (isset($this->request->post['discount_status'])) {
			$this->data['discount_status'] = $this->request->post['discount_status'];
		} else {
			$this->data['discount_status'] = $this->config->get('discount_status');
		}

		if (isset($this->request->post['discount_sort_order'])) {
			$this->data['discount_sort_order'] = $this->request->post['discount_sort_order'];
		} else {
			$this->data['discount_sort_order'] = $this->config->get('discount_sort_order');
		}
                
                if (isset($this->request->post['discount_product1'])) {
			$this->data['discount_product1'] = $this->request->post['discount_product1'];
		} else {
			$this->data['discount_product1'] = $this->config->get('discount_product1');
		}
                  
                if (isset($this->request->post['discount_product2'])) {
			$this->data['discount_product2'] = $this->request->post['discount_product2'];
		} else {
			$this->data['discount_product2'] = $this->config->get('discount_product2');
		}
                
                if (isset($this->request->post['discount_product3'])) {
			$this->data['discount_product3'] = $this->request->post['discount_product3'];
		} else {
			$this->data['discount_product3'] = $this->config->get('discount_product3');
		}
                
		$this->data['href_edit_pro'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount', 'SSL');
                $this->data['href_change_list'] = $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'], 'SSL');
                
                $this->template = 'total/discount.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	} 
        
        
   
        public function customerList() {
            if (isset($_GET['row'])) {
                $this->data['row'] = $_GET['row'];  
                
                $this->load->language('total/discount');
                $this->load->model('setting/discount');
                $array_apply_id = array();
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

                    $list_customer_id = $_POST['list_customer_id'];
                    $discount_row = 'discount_row' . $_POST['row'] . '_customer_list';
                    $saves_id = $this->config->get($discount_row);
                    
                    if (isset($saves_id) AND $saves_id <> '0') {
                        foreach ($saves_id as $save_id) {
                            if (!in_array($save_id, $list_customer_id)) 
                                $array_apply_id[$discount_row][] = $save_id;   
                        }
                    } 
                    
                    if (isset($_POST['selected'])) {
                        $array_selected_id = $_POST['selected'];                
                        for ($i = 0; $i < count($array_selected_id); $i++) {
                                $array_apply_id[$discount_row][] = $array_selected_id[$i];    
                        }  
                    } 
                    
                    if (isset($array_apply_id[$discount_row])) {
                        $this->model_setting_discount->editSettingKey('discount', $array_apply_id, $discount_row);
                    } else {
                        $this->model_setting_discount->deleteSettingKey('discount', $discount_row); 
                    }

                    $this->session->data['success'] = $this->language->get('text_success');
                    $this->redirect($this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL'));
                } 

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');	
                $this->data['text_edit_help'] = $this->language->get('text_edit_help');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');		
		
                $this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
                $this->data['button_filter'] = $this->language->get('button_filter');
                      
                
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
						
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
						
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
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
			'href'      => $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
                
                $this->data['text_row_discount'] = $this->language->get('text_string') . $_GET['row'] . ' - ' . $this->config->get('discount_row' . $_GET['row'] . '_proc') . '%';
                $this->data['breadcrumbs'][] = array(
       		'text'      =>  $this->data['text_row_discount'],
			'href'      => $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&row=' . $_GET['row'], 'SSL'),
      		'separator' => ' :: '
   		);
                
                
		$this->data['customers'] = array();

		$data = array(
			'filter_name'              => $filter_name, 
			'filter_email'             => $filter_email, 
			'filter_customer_group_id' => $filter_customer_group_id, 
			'filter_status'            => $filter_status, 
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
                
		$customer_total = $this->model_setting_discount->getTotalCustomers($data);
		$results = $this->model_setting_discount->getCustomers($data);   

                $discount_row = 'discount_row' . $_GET['row'] . '_customer_list';
                if (isset($this->request->post[$discount_row])) {
                        $array_apply_id = $this->request->post[$discount_row];
                } else { 
                        $array_apply_id = $this->config->get($discount_row);
                }

                foreach ($results as $result) {	  
                        $selected = 0;
                        if (isset($array_apply_id) && in_array($result['customer_id'], $array_apply_id)) 
                        	$selected = 1;
                                        
                        
                    
			$this->data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'       => $selected
			);
		}	

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&sort=name&row=' . $_GET['row'] . $url, 'SSL');
		$this->data['sort_email'] = $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&sort=c.email&row=' . $_GET['row'] . $url, 'SSL');
		$this->data['sort_customer_group'] = $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&sort=customer_group&row=' . $_GET['row'] . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&sort=c.status&row=' . $_GET['row'] . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&row=' . $_GET['row'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
		$this->data['filter_status'] = $filter_status;
		
                $this->data['customer_groups'] = $this->model_setting_discount->getCustomerGroups();	
		$this->data['stores'] = $this->model_setting_discount->getStores();
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
                
                $this->data['action'] = $this->url->link('total/discount/customerList', 'token=' . $this->session->data['token'] . '&row=' . $_GET['row'], 'SSL');
		$this->data['cancel'] = $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL');
		
                $this->template = 'total/discount_select_user.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
                $this->response->setOutput($this->render());
                
                
                
            } else {
                $this->index();
            }
                
        }

        
        
	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/discount')) {
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