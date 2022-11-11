<?php 
class ControllerTotalDiscountProduct extends Controller {
	private $error = array(); 
     
  	public function index() {
            if (isset($_GET['discount_id'])) {

		$this->load->language('total/discount_product');

		$this->document->setTitle($this->language->get('heading_title')); 
		
                $this->load->model('setting/discount');
                $array_not_apply_id = array();
                
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
                    
                    $discount_id = $_POST['discount_id']; 
                    $list_product_id = $_POST['list_product_id'];
                    $saves_id = $this->config->get($discount_id);

                    if (isset($saves_id)) {
                        foreach ($saves_id as $save_id) {
                            if (!in_array($save_id, $list_product_id)) 
                                $array_not_apply_id[$discount_id][] = $save_id;   
                        }
                    } 
                    
                    $results = $this->model_setting_discount->getProductsId();
                    if (isset($_POST['selected'])) {
                        $array_selected_id = $_POST['selected'];
                        foreach ($results as $product) {
                            if (!in_array($product['product_id'], $array_selected_id) && in_array($product['product_id'], $list_product_id)) 
                                $array_not_apply_id[$discount_id][] = $product['product_id'];    
                        }  
                    } else {
                        foreach ($list_product_id as $product) {
                            $array_not_apply_id[$discount_id][] = $product;
                        }
                    }
                    
                    
                    if (isset($array_not_apply_id[$discount_id])) {
                        $this->model_setting_discount->editSettingKey('discount_product', $array_not_apply_id, $discount_id);
                    } else {
                        $this->model_setting_discount->deleteSettingKey('discount_product', $discount_id); 
                    }
                    
                    if (isset($_GET['from'])) {
                        $this->redirect($this->url->link('total/discount_product', 'token=' . $this->session->data['token'], 'SSL'));
                    } else {
                        $this->redirect($this->url->link('total/' . $discount_id, 'token=' . $this->session->data['token'], 'SSL'));
                    }
		} 
                
                
                
                $this->data['discount_id'] = $_GET['discount_id'];  
                if (isset($this->request->post['discount_not_apply_id'])) {
                        $array_not_apply_id = $this->request->post[$this->data['discount_id']];
                } else {
                        $array_not_apply_id = $this->config->get($this->data['discount_id']);  
                }
                  
                $this->data['from'] = ''; 
                if (isset($_GET['from'])) {
                    $this->data['from'] = '&from=' . $_GET['from']; 
                }
                    
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}
		
		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
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
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

                $this->load->language('total/' . $_GET['discount_id']);
                $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('heading_title'),
                        'href'      => $this->url->link('total/' . $_GET['discount_id'], 'token=' . $this->session->data['token'], 'SSL'),      		
                'separator' => ' :: '
                );

                $this->load->language('total/discount_product');
                $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('heading_title'),
                        'href'      => $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . $url . '&discount_id=' . $_GET['discount_id'] . $this->data['from'], 'SSL'),	
                'separator' => ' :: '
                );
                       
		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$product_total = $this->model_setting_discount->getTotalProducts($data);
         			
		$results = $this->model_setting_discount->getProducts($data);
				    	
		foreach ($results as $result) {
			
                    if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                            $image = $this->model_setting_discount->resize($result['image'], 40, 40);
                    } else {
                            $image = $this->model_setting_discount->resize('no_image.jpg', 40, 40);
                    }

                    $special = false;

                    $product_specials = $this->model_setting_discount->getProductSpecials($result['product_id']);

                    foreach ($product_specials  as $product_special) {
                            if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                                    $special = $product_special['price'];

                                    break;
                            }					
                    }
	   
                    $selected = 1;
                    if (isset($array_not_apply_id) && in_array($result['product_id'], $array_not_apply_id)) 
                        $selected = 0;
                    
      		                       
                    $this->data['products'][] = array(
                                    'product_id' => $result['product_id'],
                                    'name'       => $result['name'],
                                    'model'      => $result['model'],
                                    'price'      => $result['price'],
                                    'special'    => $special,
                                    'image'      => $image,
                                    'quantity'   => $result['quantity'],
                                    'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                                    'selected'   => $selected
                            );
                }
		
		$this->data['heading_title'] = $this->language->get('heading_title');		
				
		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');	
                     

                if ($_GET['discount_id'] == 'discount') {
                    $this->data['text_products'] = $this->language->get('text_products') . ' "' . $this->language->get('text_user') . '".';	
                } else if ($_GET['discount_id'] == 'discount_regular') {
                    $this->data['text_products'] = $this->language->get('text_products') . ' "' . $this->language->get('text_regular') . '".';
                } else if ($_GET['discount_id'] == 'discount_count') {
                    $this->data['text_products'] = $this->language->get('text_products') . ' "' . $this->language->get('text_count') . '".';
                } else if ($_GET['discount_id'] == 'discount_cumulative') {
                    $this->data['text_products'] = $this->language->get('text_products') . ' "' . $this->language->get('text_cumulative') . '".';
                } else if ($_GET['discount_id'] == 'discount_sum') {
                    $this->data['text_products'] = $this->language->get('text_products') . ' "' . $this->language->get('text_sum') . '".';
                }
			
		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_price'] = $this->language->get('column_price');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');			

                $this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
                $this->data['button_filter'] = $this->language->get('button_filter');
                
                
                $this->data['action'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'], 'SSL');
                $this->data['cancel'] = $this->url->link('total/' . $_GET['discount_id'], 'token=' . $this->session->data['token'], 'SSL');  
                if (isset($_GET['from']) && $_GET['from'] == 'list') 
                    $this->data['cancel'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'], 'SSL');  
 
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
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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
					
		$this->data['sort_name'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'] . '&sort=p.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=' . $_GET['discount_id'] . $this->data['from'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'total/discount_product.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
			
		$this->response->setOutput($this->render());  
                
                
            } else {

		$this->load->language('total/discount_product');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/discount');         

                if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
           
                    $this->model_setting_discount->editSettingAction('discount_product', $this->request->post);

                    $this->session->data['success'] = $this->language->get('text_success');

                    $this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
                $this->data['text_user'] = $this->language->get('text_user');
		$this->data['text_regular'] = $this->language->get('text_regular');
                $this->data['text_count'] = $this->language->get('text_count');
		$this->data['text_cumulative'] = $this->language->get('text_cumulative');
                $this->data['text_sum'] = $this->language->get('text_sum');
                
                $this->data['column_discount'] = $this->language->get('column_discount');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_products'] = $this->language->get('column_products');
                
                $this->data['entry_product1'] = $this->language->get('entry_product1');
                $this->data['entry_product2'] = $this->language->get('entry_product2');
                $this->data['entry_product3'] = $this->language->get('entry_product3');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
                $this->data['button_edit'] = $this->language->get('button_edit');

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
			'href'      => $this->url->link('total/discount_product', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'], 'SSL'); 
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
                
                if (isset($this->request->post['discount_product_status'])) {
			$this->data['discount_product_status'] = $this->request->post['discount_product_status'];
		} else {
			$this->data['discount_product_status'] = $this->config->get('discount_product_status');
		}
                
                if (isset($this->request->post['discount_action'])) {
			$this->data['discount_action'] = $this->request->post['discount_action'];
		} else {
			$this->data['discount_action'] = $this->config->get('discount_action');
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
                
                if (isset($this->request->post['discount_regular_action'])) {
			$this->data['discount_regular_action'] = $this->request->post['discount_regular_action'];
		} else {
			$this->data['discount_regular_action'] = $this->config->get('discount_regular_action');
		}
                
                if (isset($this->request->post['discount_regular_product1'])) {
			$this->data['discount_regular_product1'] = $this->request->post['discount_regular_product1'];
		} else {
			$this->data['discount_regular_product1'] = $this->config->get('discount_regular_product1');
		}
                  
                if (isset($this->request->post['discount_regular_product2'])) {
			$this->data['discount_regular_product2'] = $this->request->post['discount_regular_product2'];
		} else {
			$this->data['discount_regular_product2'] = $this->config->get('discount_regular_product2');
		}
                
                if (isset($this->request->post['discount_regular_product3'])) {
			$this->data['discount_regular_product3'] = $this->request->post['discount_regular_product3'];
		} else {
			$this->data['discount_regular_product3'] = $this->config->get('discount_regular_product3');
		}
                
                if (isset($this->request->post['discount_count_action'])) {
			$this->data['discount_count_action'] = $this->request->post['discount_count_action'];
		} else {
			$this->data['discount_count_action'] = $this->config->get('discount_count_action');
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
                
                if (isset($this->request->post['discount_sum_action'])) {
			$this->data['discount_sum_action'] = $this->request->post['discount_sum_action'];
		} else {
			$this->data['discount_sum_action'] = $this->config->get('discount_sum_action');
		}
                
                if (isset($this->request->post['discount_sum_product1'])) {
			$this->data['discount_sum_product1'] = $this->request->post['discount_sum_product1'];
		} else {
			$this->data['discount_sum_product1'] = $this->config->get('discount_sum_product1');
		}
                  
                if (isset($this->request->post['discount_sum_product2'])) {
			$this->data['discount_sum_product2'] = $this->request->post['discount_sum_product2'];
		} else {
			$this->data['discount_sum_product2'] = $this->config->get('discount_sum_product2');
		}
                
                if (isset($this->request->post['discount_sum_product3'])) {
			$this->data['discount_sum_product3'] = $this->request->post['discount_sum_product3'];
		} else {
			$this->data['discount_sum_product3'] = $this->config->get('discount_sum_product3');
		}
                

                if (isset($this->request->post['discount_cumulative_action'])) {
			$this->data['discount_cumulative_action'] = $this->request->post['discount_cumulative_action'];
		} else {
			$this->data['discount_cumulative_action'] = $this->config->get('discount_cumulative_action');
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
                
                
		$this->data['href_edit_user'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount&from=list', 'SSL');
		$this->data['href_edit_regular'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount_regular&from=list', 'SSL');
		$this->data['href_edit_count'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount_count&from=list', 'SSL');
		$this->data['href_edit_cumulative'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount_cumulative&from=list', 'SSL');  
                $this->data['href_edit_sum'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount_sum&from=list', 'SSL');
                
                $this->template = 'total/discount_product_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
            }
        }
        
        
        private function validate() {

		if (!$this->user->hasPermission('modify', 'total/discount_product') && !$this->user->hasPermission('modify', 'total/discount_product_list')) {
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