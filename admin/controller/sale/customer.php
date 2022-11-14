<?php
	class ControllerSaleCustomer extends Controller {
		private $error = array();		
		
		public function index() {
			$this->language->load('sale/customer');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/customer');
			$export = false;
			
			if ($export) {
				$this->getCSV();
				} else {
				$this->getList();
			}
		}
		
		private function removeNewLines($s){
			
			$s = str_replace(PHP_EOL, ' ', $s);
			
			$s = trim(preg_replace('/\s+/', ' ', $s));
			
			return $s;
		}
		
		public function insert() {
			$this->language->load('sale/customer');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/customer');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_customer->addCustomer($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
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
				
				if (isset($this->request->get['filter_gender'])) {
					$url .= '&filter_gender=' . $this->request->get['filter_gender'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}
				
				if (isset($this->request->get['filter_ip'])) {
					$url .= '&filter_ip=' . $this->request->get['filter_ip'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['filter_birthday_from'])) {
					$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
				}
				
				if (isset($this->request->get['filter_birthday_to'])) {
					$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
				}
				
				if (isset($this->request->get['order_first_date_from'])) {
					$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
				}
				
				if (isset($this->request->get['order_first_date_to'])) {
					$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
				}
				
				if (isset($this->request->get['filter_order_count'])) {
					$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
				}
				
				if (isset($this->request->get['filter_order_good_count'])) {
					$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
				}
				
				if (isset($this->request->get['filter_total_sum'])) {
					$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
				}
				
				if (isset($this->request->get['filter_avg_cheque'])) {
					$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
				}
				
				if (isset($this->request->get['filter_interest_brand'])) {
					$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
				}
				
				if (isset($this->request->get['filter_interest_category'])) {
					$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
				}
				
				if (isset($this->request->get['filter_segment_id'])) {
					$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
				}
				
				if (isset($this->request->get['filter_has_discount'])) {
					$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
				}
				
				if (isset($this->request->get['filter_no_discount'])) {
					$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
				}
				
				if (isset($this->request->get['filter_no_birthday'])) {
					$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
				}
				
				if (isset($this->request->get['filter_mail_checked'])) {
					$url .= '&filter_mail_checked=1';
				}
				
				if (isset($this->request->get['filter_push_signed'])) {
					$url .= '&filter_push_signed=1';
				}
				
				if (isset($this->request->get['filter_nbt_customer'])) {
					$url .= '&filter_nbt_customer=1';
				}
				
				if (isset($this->request->get['filter_last_call'])) {
					$url .= '&filter_last_call=' . $this->request->get['filter_last_call'];
				}
				
				if (isset($this->request->get['filter_nbt_customer_exclude'])) {
					$url .= '&filter_nbt_customer_exclude=1';
				}
				
				if (isset($this->request->get['filter_segment_intersection'])) {
					$url .= '&filter_segment_intersection=1';
				}
				
				if (isset($this->request->get['filter_source'])) {
					$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
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
				
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('sale/customer');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/customer');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				
				$this->model_sale_customer->editCustomer($this->request->get['customer_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
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
				
				if (isset($this->request->get['filter_gender'])) {
					$url .= '&filter_gender=' . $this->request->get['filter_gender'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}
				
				if (isset($this->request->get['filter_ip'])) {
					$url .= '&filter_ip=' . $this->request->get['filter_ip'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['filter_birthday_from'])) {
					$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
				}
				
				if (isset($this->request->get['filter_birthday_to'])) {
					$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
				}
				
				if (isset($this->request->get['order_first_date_from'])) {
					$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
				}
				
				if (isset($this->request->get['order_first_date_to'])) {
					$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
				}
				
				if (isset($this->request->get['filter_order_count'])) {
					$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
				}
				
				if (isset($this->request->get['filter_order_good_count'])) {
					$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
				}
				
				if (isset($this->request->get['filter_total_sum'])) {
					$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
				}
				
				if (isset($this->request->get['filter_avg_cheque'])) {
					$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
				}
				
				if (isset($this->request->get['filter_interest_brand'])) {
					$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
				}
				
				if (isset($this->request->get['filter_interest_category'])) {
					$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
				}
				
				if (isset($this->request->get['filter_segment_id'])) {
					$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
				}
				
				if (isset($this->request->get['filter_has_discount'])) {
					$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
				}
				
				if (isset($this->request->get['filter_no_discount'])) {
					$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
				}
				
				if (isset($this->request->get['filter_no_birthday'])) {
					$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
				}
				
				if (isset($this->request->get['filter_source'])) {
					$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
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
				
				if (isset($this->request->get['filter_custom_filter'])) {
					$url .= '&filter_custom_filter=' . $this->request->get['filter_custom_filter'];
				}
				
				if (isset($this->request->get['filter_mail_opened'])) {
					$url .= '&filter_mail_opened=1';
				}
				
				if (isset($this->request->get['filter_mail_checked'])) {
					$url .= '&filter_mail_checked=1';
				}
				
				if (isset($this->request->get['filter_push_signed'])) {
					$url .= '&filter_push_signed=1';
				}
				
				if (isset($this->request->get['filter_nbt_customer'])) {
					$url .= '&filter_nbt_customer=1';
				}
				
				if (isset($this->request->get['filter_last_call'])) {
					$url .= '&filter_last_call=' . $this->request->get['filter_last_call'];
				}
				
				if (isset($this->request->get['filter_nbt_customer_exclude'])) {
					$url .= '&filter_nbt_customer_exclude=1';
				}
				
				if (isset($this->request->get['filter_segment_intersection'])) {
					$url .= '&filter_segment_intersection=1';
				}
				
				if (isset($this->request->get['filter_mail_status'])) {
					$url .= '&filter_mail_status=' . $this->request->get['filter_mail_status'];
				}
				
				$this->redirect($this->url->link('sale/customer/update', 'customer_id='.$this->request->get['customer_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('sale/customer');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/customer');
			
			if (isset($this->request->post['selected']) && $this->validateDelete($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $customer_id) {
					$this->model_sale_customer->deleteCustomer($customer_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_source'])) {
					$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_customer_group_id'])) {
					$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_gender'])) {
					$url .= '&filter_gender=' . $this->request->get['filter_gender'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}
				
				if (isset($this->request->get['filter_ip'])) {
					$url .= '&filter_ip=' . $this->request->get['filter_ip'];
				}
				
				if (isset($this->request->get['filter_country_id'])) {
					$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['filter_birthday_from'])) {
					$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
				}
				
				if (isset($this->request->get['filter_birthday_to'])) {
					$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
				}
				
				if (isset($this->request->get['order_first_date_from'])) {
					$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
				}
				
				if (isset($this->request->get['order_first_date_to'])) {
					$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
				}
				
				if (isset($this->request->get['filter_order_count'])) {
					$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
				}
				
				if (isset($this->request->get['filter_order_good_count'])) {
					$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
				}
				
				if (isset($this->request->get['filter_total_sum'])) {
					$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
				}
				
				if (isset($this->request->get['filter_avg_cheque'])) {
					$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
				}
				
				if (isset($this->request->get['filter_interest_brand'])) {
					$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
				}
				
				if (isset($this->request->get['filter_interest_category'])) {
					$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
				}
				
				if (isset($this->request->get['filter_segment_id'])) {
					$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
				}
				
				if (isset($this->request->get['filter_has_discount'])) {
					$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
				}
				
				if (isset($this->request->get['filter_no_discount'])) {
					$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
				}
				
				if (isset($this->request->get['filter_no_birthday'])) {
					$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
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
				
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		public function approve() {
			$this->language->load('sale/customer');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/customer');
			
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$this->error['warning'] = $this->language->get('error_permission');
				} elseif (isset($this->request->post['selected'])) {
				$approved = 0;
				
				foreach ($this->request->post['selected'] as $customer_id) {
					$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					
					if ($customer_info && !$customer_info['approved']) {
						$this->model_sale_customer->approve($customer_id);
						
						$approved++;
					}
				}
				
				$this->session->data['success'] = sprintf($this->language->get('text_approved'), $approved);
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_source'])) {
					$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_gender'])) {
					$url .= '&filter_gender=' . $this->request->get['filter_gender'];
				}
				
				if (isset($this->request->get['filter_customer_group_id'])) {
					$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}
				
				if (isset($this->request->get['filter_ip'])) {
					$url .= '&filter_ip=' . $this->request->get['filter_ip'];
				}
				
				if (isset($this->request->get['filter_country_id'])) {
					$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['filter_birthday_from'])) {
					$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
				}
				
				if (isset($this->request->get['filter_birthday_to'])) {
					$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
				}
				
				if (isset($this->request->get['order_first_date_from'])) {
					$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
				}
				
				if (isset($this->request->get['order_first_date_to'])) {
					$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
				}
				
				if (isset($this->request->get['filter_order_count'])) {
					$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
				}
				
				if (isset($this->request->get['filter_order_good_count'])) {
					$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
				}
				
				if (isset($this->request->get['filter_total_sum'])) {
					$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
				}
				
				if (isset($this->request->get['filter_avg_cheque'])) {
					$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
				}
				
				if (isset($this->request->get['filter_interest_brand'])) {
					$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
				}
				
				if (isset($this->request->get['filter_interest_category'])) {
					$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
				}
				
				if (isset($this->request->get['filter_segment_id'])) {
					$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
				}
				
				if (isset($this->request->get['filter_has_discount'])) {
					$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
				}
				
				if (isset($this->request->get['filter_no_discount'])) {
					$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
				}
				
				if (isset($this->request->get['filter_no_birthday'])) {
					$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
				}
				
				if (isset($this->request->get['filter_source'])) {
					$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
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
				
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		public function getCSV () {
			set_time_limit(100);
			ini_set('memory_limit','1G');
			
			
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
			
			if (isset($this->request->get['filter_phone'])) {
				$filter_phone = $this->request->get['filter_phone'];
				} else {
				$filter_phone = null;
			}
			
			if (isset($this->request->get['filter_source'])) {
				$filter_source = $this->request->get['filter_source'];
				} else {
				$filter_source = null;
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
			
			if (isset($this->request->get['filter_gender'])) {
				$filter_gender = $this->request->get['filter_gender'];
				} else {
				$filter_gender = null;
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$filter_approved = $this->request->get['filter_approved'];
				} else {
				$filter_approved = null;
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$filter_ip = $this->request->get['filter_ip'];
				} else {
				$filter_ip = null;
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$filter_country_id = $this->request->get['filter_country_id'];
				} else {
				$filter_country_id = null;
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
				} else {
				$filter_date_added = null;
			}
			
			if (isset($this->request->get['filter_birthday_from'])) {
				$filter_birthday_from = $this->request->get['filter_birthday_from'];
				} else {
				$filter_birthday_from = null;
			}
			
			if (isset($this->request->get['filter_birthday_to'])) {
				$filter_birthday_to = $this->request->get['filter_birthday_to'];
				} else {
				$filter_birthday_to = null;
			}
			
			if (isset($this->request->get['order_first_date_from'])) {
				$order_first_date_from = $this->request->get['order_first_date_from'];
				} else {
				$order_first_date_from = null;
			}
			
			if (isset($this->request->get['order_first_date_to'])) {
				$order_first_date_to = $this->request->get['order_first_date_to'];
				} else {
				$order_first_date_to = null;
			}
			
			
			if (isset($this->request->get['filter_order_count'])) {
				$filter_order_count = $this->request->get['filter_order_count'];
				} else {
				$filter_order_count = null;
			}
			
			if (isset($this->request->get['filter_order_good_count'])) {
				$filter_order_good_count = $this->request->get['filter_order_good_count'];
				} else {
				$filter_order_good_count = null;
			}
			
			if (isset($this->request->get['filter_total_sum'])) {
				$filter_total_sum = $this->request->get['filter_total_sum'];
				} else {
				$filter_total_sum = null;
			}
			
			if (isset($this->request->get['filter_avg_cheque'])) {
				$filter_avg_cheque = $this->request->get['filter_avg_cheque'];
				} else {
				$filter_avg_cheque = null;
			}
			
			if (isset($this->request->get['filter_interest_brand'])) {
				$filter_interest_brand = $this->request->get['filter_interest_brand'];
				} else {
				$filter_interest_brand = null;
			}
			
			if (isset($this->request->get['filter_interest_category'])) {
				$filter_interest_category = $this->request->get['filter_interest_category'];
				} else {
				$filter_interest_category = null;
			}
			
			if (isset($this->request->get['filter_segment_id'])) {
				$filter_segment_id = $this->request->get['filter_segment_id'];
				} else {
				$filter_segment_id = null;
			}
			
			if (isset($this->request->get['filter_has_discount'])) {
				$filter_has_discount = $this->request->get['filter_has_discount'];
				} else {
				$filter_has_discount = null;
			}
			
			if (isset($this->request->get['filter_no_discount'])) {
				$filter_no_discount = $this->request->get['filter_no_discount'];
				} else {
				$filter_no_discount = null;
			}
			
			if (isset($this->request->get['filter_no_birthday'])) {
				$filter_no_birthday = $this->request->get['filter_no_birthday'];
				} else {
				$filter_no_birthday = null;
			}
			
			if (isset($this->request->get['filter_mail_status'])) {
				$filter_mail_status = $this->request->get['filter_mail_status'];
				} else {
				$filter_mail_status = null;
			}
			
			if (isset($this->request->get['filter_custom_filter'])) {
				$filter_custom_filter = $this->request->get['filter_custom_filter'];
				} else {
				$filter_custom_filter = null;
			}
			
			
			
			if (isset($this->request->get['filter_mail_opened'])) {
				$filter_mail_opened = 1;
				} else {
				$filter_mail_opened = 0;
			}
			
			
			if (isset($this->request->get['filter_mail_checked'])) {
				$filter_mail_checked = 1;
				} else {
				$filter_mail_checked = 0;
			}
			
			if (isset($this->request->get['filter_push_signed'])) {
				$filter_push_signed = 1;
				} else {
				$filter_push_signed = 0;
			}
			
			if (isset($this->request->get['filter_nbt_customer'])) {
				$filter_nbt_customer = 1;
				} else {
				$filter_nbt_customer = 0;
			}
			
			if (isset($this->request->get['filter_last_call'])) {
				$filter_last_call = $this->request->get['filter_last_call'];
				} else {
				$filter_last_call = null;
			}
			
			if (isset($this->request->get['filter_nbt_customer_exclude'])) {
				$filter_nbt_customer_exclude = 1;
				} else {
				$filter_nbt_customer_exclude = 0;
			}
			
			if (isset($this->request->get['filter_segment_intersection'])) {
				$filter_segment_intersection = 1;
				} else {
				$filter_segment_intersection = 0;
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
			
			
			$data = array(
			'filter_name'              	=> $filter_name,
			'filter_email'             	=> $filter_email,
			'filter_phone'             	=> $filter_phone,
			'filter_source'            	=> $filter_source,
			'filter_customer_group_id' 	=> $filter_customer_group_id,
			'filter_status'            	=> $filter_status,
			'filter_gender'            	=> $filter_gender,
			'filter_approved'          	=> $filter_approved,
			'filter_date_added'        	=> $filter_date_added,
			'filter_birthday_from'      => $filter_birthday_from,
			'filter_birthday_to'        	=> $filter_birthday_to,
			'order_first_date_from'        => $order_first_date_from,
			'order_first_date_to'        => $order_first_date_to,
			'filter_order_count'        => $filter_order_count,
			'filter_order_good_count'        => $filter_order_good_count,
			'filter_total_sum'        => $filter_total_sum,
			'filter_avg_cheque'        => $filter_avg_cheque,
			'filter_interest_brand'        => $filter_interest_brand,
			'filter_interest_category'        => $filter_interest_category,
			'filter_custom_filter'        => $filter_custom_filter,
			'filter_segment_id'        => $filter_segment_id,
			'filter_mail_status'       => $filter_mail_status,
			'filter_mail_checked'      => $filter_mail_checked,
			'filter_mail_opened'       => $filter_mail_opened,
			'filter_push_signed'       => $filter_push_signed,
			'filter_nbt_customer'      => $filter_nbt_customer,
			'filter_last_call'         => $filter_last_call,
			'filter_nbt_customer_exclude'      => $filter_nbt_customer_exclude,
			'filter_segment_intersection'      => $filter_segment_intersection,
			'filter_country_id'        => $filter_country_id,
			'filter_has_discount'      => $filter_has_discount,
			'filter_no_discount'       => $filter_no_discount,
			'filter_no_birthday'       => $filter_no_birthday,
			'filter_ip'                => $filter_ip,
			'sort'                     => $sort,
			'order'                    => $order,
			);
			
			$this->load->model('sale/customer');
			$total = $this->model_sale_customer->getTotalCustomers($data);
			
			header( 'Content-Type: text/csv charset=utf-8' );
			header("Content-Disposition: attachment; filename=customer_export_". date('Y_m_d') . '_' . substr(md5(json_encode($data)), 0, 5) .".csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$file = fopen('php://output', 'w');								
			
			$google_csv = (isset($this->request->get['gc_format']) && $this->request->get['gc_format'] == 1)?true:false;
			$viber_csv = (isset($this->request->get['viber_format']) && $this->request->get['viber_format'] == 1)?true:false;
			if ($google_csv){
				$header = array(
				'Email','First Name','Last Name','Country','Zip','Phone'
				);
				
				fputcsv($file, $header);
				
				$rArray = array();
				$limit = ceil($total / 10);
				
				$yu = 0;
				for ($z = 0; $z < 10; $z++) {
					
					$data['start'] = $z * $limit;
					$data['limit'] = $limit;
					
					$results = $this->model_sale_customer->getCustomers($data);
					
					foreach ($results as $row) {
						$yu++;
						
						$exploded = explode(' ', trim($row['firstname']));
						if (count($exploded) == 2 && mb_strlen(trim($exploded[0])) > 1 && mb_strlen(trim($exploded[1])) > 1){
							$firstname = $exploded[0];
							$lastname = $exploded[1];
							} elseif (count($exploded) == 3 && mb_strlen(trim($exploded[0])) > 1 && mb_strlen(trim($exploded[1])) > 1){
							$firstname = $exploded[1];
							$lastname = $exploded[0];
							} else {
							$firstname 	= $row['firstname'];
							$lastname 	= $row['lastname'];
						}
						
						if (filter_var(trim($row['email']), FILTER_VALIDATE_EMAIL)){
							$email = $row['email'];
							} else {
							$email = '';
						}
						
						$countries = [
						'176' 	=> 'RU',
						'220' 	=> 'UA',
						'109' 	=> 'KZ',
						'20' 	=> 'BY',
						];
						
						$zips = [
						'176' 	=> '100000',
						'220' 	=> '01032',
						'109' 	=> '010001',
						'20' 	=> '220002',
						];
						
						$csvdata = [
						trim($email),
						trim($firstname),
						trim($lastname),
						$countries[$row['country_id']],
						$zips[$row['country_id']],
						trim($row['telephone'])
						];						
						
						fputcsv($file, $csvdata);
					}
				}
				
				} elseif ($viber_csv) {
				
				$rArray = array();
				$limit = ceil($total / 10);
				
				$yu = 0;
				for ($z = 0; $z < 10; $z++) {
					$data['start'] = $z * $limit;
					$data['limit'] = $limit;
					
					$results = $this->model_sale_customer->getCustomers($data);
					
					foreach ($results as $r) {
						$yu++;
						
						$csvdata = array(
						$this->removeNewLines($r['telephone'])
						);
						
						fputcsv($file, $csvdata);
					}
				}
				
				} else {
				
				$header = array(
				'EMAIL',
				'FNAME',
				'LNAME',
				'UTOKEN',
				);
				
				//	fputcsv($file, array('TOTAL: ', $total, '', ''));
				//	fputcsv($file, array(' ', ' ', ' ', ' '));
				fputcsv($file, $header);
				
				
				$rArray = array();
				$limit = ceil($total / 10);
				
				$yu = 0;
				for ($z = 0; $z < 10; $z++) {
					$data['start'] = $z * $limit;
					$data['limit'] = $limit;
					
					$results = $this->model_sale_customer->getCustomers($data);
					
					
					foreach ($results as $r) {
						$yu++;
						// $rArray[] = array($r['email'], str_replace('\n', " ", $r['firstname']), str_replace('\n', " ", $r['lastname']), $r['utoken']);
						fputcsv($file, array($r['email'], str_replace('\n', " ", $r['firstname']), str_replace('\n', " ", $r['lastname']), $r['utoken']));
					}
				}
				
			}
			
			fclose($file);
		}
		
		protected function getList() {
			
			if ($this->user->getID() == 17 || $this->user->getID() == 75){
				$this->config->set('config_admin_limit', 25);
			}
			
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
			
			if (isset($this->request->get['filter_phone'])) {
				$filter_phone = $this->request->get['filter_phone'];
				} else {
				$filter_phone = null;
			}
			
			if (isset($this->request->get['filter_source'])) {
				$filter_source = $this->request->get['filter_source'];
				} else {
				$filter_source = null;
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
			
			if (isset($this->request->get['filter_gender'])) {
				$filter_gender = $this->request->get['filter_gender'];
				} else {
				$filter_gender = null;
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$filter_approved = $this->request->get['filter_approved'];
				} else {
				$filter_approved = null;
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$filter_ip = $this->request->get['filter_ip'];
				} else {
				$filter_ip = null;
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$filter_country_id = $this->request->get['filter_country_id'];
				} else {
				$filter_country_id = null;
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
				} else {
				$filter_date_added = null;
			}
			
			if (isset($this->request->get['filter_birthday_from'])) {
				$filter_birthday_from = $this->request->get['filter_birthday_from'];
				} else {
				$filter_birthday_from = null;
			}
			
			if (isset($this->request->get['filter_birthday_to'])) {
				$filter_birthday_to = $this->request->get['filter_birthday_to'];
				} else {
				$filter_birthday_to = null;
			}
			
			if (isset($this->request->get['order_first_date_from'])) {
				$order_first_date_from = $this->request->get['order_first_date_from'];
				} else {
				$order_first_date_from = null;
			}
			
			if (isset($this->request->get['order_first_date_to'])) {
				$order_first_date_to = $this->request->get['order_first_date_to'];
				} else {
				$order_first_date_to = null;
			}
			
			if (isset($this->request->get['filter_order_count'])) {
				$filter_order_count = $this->request->get['filter_order_count'];
				} else {
				$filter_order_count = null;
			}
			
			if (isset($this->request->get['filter_order_good_count'])) {
				$filter_order_good_count = $this->request->get['filter_order_good_count'];
				} else {
				$filter_order_good_count = null;
			}
			
			if (isset($this->request->get['filter_total_sum'])) {
				$filter_total_sum = $this->request->get['filter_total_sum'];
				} else {
				$filter_total_sum = null;
			}
			
			if (isset($this->request->get['filter_avg_cheque'])) {
				$filter_avg_cheque = $this->request->get['filter_avg_cheque'];
				} else {
				$filter_avg_cheque = null;
			}
			
			if (isset($this->request->get['filter_interest_brand'])) {
				$filter_interest_brand = $this->request->get['filter_interest_brand'];
				} else {
				$filter_interest_brand = null;
			}
			
			if (isset($this->request->get['filter_interest_category'])) {
				$filter_interest_category = $this->request->get['filter_interest_category'];
				} else {
				$filter_interest_category = null;
			}
			
			if (isset($this->request->get['filter_segment_id'])) {
				$filter_segment_id = $this->request->get['filter_segment_id'];
				} else {
				$filter_segment_id = null;
			}
			
			if (isset($this->request->get['filter_has_discount'])) {
				$filter_has_discount = $this->request->get['filter_has_discount'];
				} else {
				$filter_has_discount = null;
			}
			
			if (isset($this->request->get['filter_no_discount'])) {
				$filter_no_discount = $this->request->get['filter_no_discount'];
				} else {
				$filter_no_discount = null;
			}
			
			if (isset($this->request->get['filter_no_birthday'])) {
				$filter_no_birthday = $this->request->get['filter_no_birthday'];
				} else {
				$filter_no_birthday = null;
			}
			
			if (isset($this->request->get['filter_mail_status'])) {
				$filter_mail_status = $this->request->get['filter_mail_status'];
				} else {
				$filter_mail_status = null;
			}
			
			if (isset($this->request->get['filter_custom_filter'])) {
				$filter_custom_filter = $this->request->get['filter_custom_filter'];
				} else {
				$filter_custom_filter = null;
			}
			
			
			if (isset($this->request->get['filter_mail_opened'])) {
				$filter_mail_opened = 1;
				} else {
				$filter_mail_opened = 0;
			}
			
			
			if (isset($this->request->get['filter_mail_checked'])) {
				$filter_mail_checked = 1;
				} else {
				$filter_mail_checked = 0;
			}
			
			if (isset($this->request->get['filter_push_signed'])) {
				$filter_push_signed = 1;
				} else {
				$filter_push_signed = 0;
			}
			
			if (isset($this->request->get['filter_nbt_customer'])) {
				$filter_nbt_customer = 1;
				} else {
				$filter_nbt_customer = 0;
			}
			
			if (isset($this->request->get['filter_last_call'])) {
				$filter_last_call = $this->request->get['filter_last_call'];
				} else {
				$filter_last_call = null;
			}
			
			if (isset($this->request->get['filter_nbt_customer_exclude'])) {
				$filter_nbt_customer_exclude = 1;
				} else {
				$filter_nbt_customer_exclude = 0;
			}
			
			if (isset($this->request->get['filter_segment_intersection'])) {
				$filter_segment_intersection = 1;
				} else {
				$filter_segment_intersection = 0;
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
			
			if (isset($this->request->get['filter_phone'])) {
				$url .= '&filter_phone=' . urlencode(html_entity_decode($this->request->get['filter_phone'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_source'])) {
				$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_gender'])) {
				$url .= '&filter_gender=' . $this->request->get['filter_gender'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_birthday_from'])) {
				$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
			}
			
			if (isset($this->request->get['filter_birthday_to'])) {
				$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
			}
			
			if (isset($this->request->get['order_first_date_from'])) {
				$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
			}
			
			if (isset($this->request->get['order_first_date_to'])) {
				$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
			}
			
			if (isset($this->request->get['filter_order_count'])) {
				$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
			}
			
			if (isset($this->request->get['filter_order_good_count'])) {
				$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
			}
			
			if (isset($this->request->get['filter_total_sum'])) {
				$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
			}
			
			if (isset($this->request->get['filter_avg_cheque'])) {
				$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
			}
			
			if (isset($this->request->get['filter_interest_brand'])) {
				$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
			}
			
			if (isset($this->request->get['filter_interest_category'])) {
				$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
			}
			
			if (isset($this->request->get['filter_segment_id'])) {
				$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
			}
			
			if (isset($this->request->get['filter_has_discount'])) {
				$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
			}
			
			if (isset($this->request->get['filter_no_discount'])) {
				$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
			}
			
			if (isset($this->request->get['filter_no_birthday'])) {
				$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
			}
			
			if (isset($this->request->get['filter_mail_status'])) {
				$url .= '&filter_mail_status=' . $this->request->get['filter_mail_status'];
			}
			
			if (isset($this->request->get['filter_custom_filter'])) {
				$url .= '&filter_custom_filter=' . $this->request->get['filter_custom_filter'];
			}
			
			if (isset($this->request->get['filter_mail_opened'])) {
				$url .= '&filter_mail_opened=1';
			}
			
			if (isset($this->request->get['filter_mail_checked'])) {
				$url .= '&filter_mail_checked=1';
			}
			
			if (isset($this->request->get['filter_push_signed'])) {
				$url .= '&filter_push_signed=1';
			}
			
			if (isset($this->request->get['filter_nbt_customer'])) {
				$url .= '&filter_nbt_customer=1';
			}
			
			if (isset($this->request->get['filter_last_call'])) {
				$url .= '&filter_last_call=' . $this->request->get['filter_last_call'];
			}
			
			if (isset($this->request->get['filter_nbt_customer_exclude'])) {
				$url .= '&filter_nbt_customer_exclude=1';
			}
			
			if (isset($this->request->get['filter_segment_intersection'])) {
				$url .= '&filter_segment_intersection=1';
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
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			$this->data['approve'] = $this->url->link('sale/customer/approve', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['insert'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			$this->load->model('localisation/country');
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			$tmp = array();
			foreach ($this->data['countries'] as $c){
				$tmp[$c['country_id']] = $c;
			}
			$this->data['countries'] = $tmp;
			
			$this->data['sources'] = $this->model_sale_customer->getSources();			
			$this->data['customers'] = array();
			
			$export = false;
			
			$data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_phone'             => $filter_phone,
			'filter_source'            => $filter_source,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status'            => $filter_status,
			'filter_gender'            => $filter_gender,
			'filter_approved'          => $filter_approved,
			'filter_date_added'        => $filter_date_added,
			'filter_mail_status'       => $filter_mail_status,
			'filter_mail_checked'      => $filter_mail_checked,
			'filter_mail_opened'       => $filter_mail_opened,
			'filter_push_signed'       => $filter_push_signed,
			'filter_nbt_customer'       => $filter_nbt_customer,
			'filter_last_call'       	=> $filter_last_call,
			'filter_nbt_customer_exclude'       => $filter_nbt_customer_exclude,
			'filter_segment_intersection'       => $filter_segment_intersection,
			'filter_custom_filter'     => $filter_custom_filter,
			'filter_country_id'        => $filter_country_id,
			'filter_ip'                => $filter_ip,
			'filter_birthday_from'     => $filter_birthday_from,
			'filter_birthday_to'       => $filter_birthday_to,
			'order_first_date_from'     => $order_first_date_from,
			'order_first_date_to'       => $order_first_date_to,
			'filter_order_count'       => $filter_order_count,
			'filter_order_good_count'  => $filter_order_good_count,
			'filter_total_sum'         => $filter_total_sum,
			'filter_avg_cheque'        => $filter_avg_cheque,
			'filter_interest_brand'    => $filter_interest_brand,
			'filter_interest_category' => $filter_interest_category,
			'filter_has_discount'      => $filter_has_discount,
			'filter_no_discount'       => $filter_no_discount,
			'filter_no_birthday'       => $filter_no_birthday,
			'filter_segment_id' 		=> $filter_segment_id,
			'sort'                     => $sort,
			'order'                    => $order,
			'campaing_id'              => isset($_GET['campaing_id']) ? $_GET['campaing_id'] : false,
			);
			
			//		$this->config->set('config_admin_limit', 5);
			
			$customer_total = $this->model_sale_customer->getTotalCustomers($data);
			// Експорт в ЦСВ
			if (!$export) {
				$data['start'] = ($page - 1) * $this->config->get('config_admin_limit');
				$data['limit'] = $this->config->get('config_admin_limit');
			}
			$results = $this->model_sale_customer->getCustomers($data);
			
			//			var_dump($results);
			
			if ($export) {
				header('Content-Type: text/csv charset=utf-8');
				$file = fopen('php://output', 'w');
				
				$header = array(
				'Email',
				'Имя',
				'Фамилия',
				'Utoken'
				);
				
				fputcsv($file, $header);
				
				// foreach ($results as $r) {
				// 		// $i =
				// 		// fputcsv($file, array($r['email'], $r['firstname'], $r['lastname'], $r['utoken']));
				// }
				fclose($file);
			}
			
			$store_urls = array();
			
			$this->load->model('catalog/actiontemplate');
			$this->load->model('tool/image');
			$this->load->model('kp/csi');
			$this->load->model('sale/order');
			$this->load->model('localisation/order_status');
			
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => '<i class="fa fa-edit"></i>',
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL')
				);
				
				$this->load->model('setting/setting');
				$store_url = ($result['store_id']==0)?HTTP_CATALOG:$this->model_setting_setting->getKeySettingValue('config', 'config_url' , $result['store_id']);
				$preauth_url = $store_url.'?utm_term='.$result['email'].'&utoken='.md5(md5($result['email'].$result['email']));
				
				
				$actiontemplate_results = $this->model_catalog_actiontemplate->getActionTemplatesHistoryByCustomer($result['customer_id']);
				
				$actiontemplate_history = array();
				if ($actiontemplate_results){
					foreach ($actiontemplate_results as $_atresult){
						$actiontemplate_history[] = array(
						'image' => $this->model_tool_image->resize($_atresult['image'], 50, 50),
						'date_sent' => date('d.m.Y', strtotime($_atresult['date_sent'])),
						'title' => $_atresult['title']
						);
					}				
				}
				
				$orders_for_csi = array();
				foreach ($this->model_kp_csi->getCompletedOrdersWithoutCSI($result['customer_id']) as $ofc){
					$ofco = $this->model_sale_order->getOrder($ofc);
					
					$orders_for_csi[] = array(
					'order_id' => $ofco['order_id'],
					'order_status' => $this->model_localisation_order_status->getOrderStatus($ofco['order_status_id']),
					'href'     => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $ofco['order_id']),
					'last_modified' => date('d.m.Y', strtotime($ofco['date_modified']))
					);
				}
				
				
				$this->data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'store_id'       => $result['store_id'],
				'name'           => $result['name'],
				'is_mudak'		 => $this->model_sale_customer->getIsMudak($result['customer_id']),
				'segments'		 => $this->model_sale_customer->getCustomerSegments($result['customer_id']),
				'actiontemplate_history' => $actiontemplate_history,
				'gender'		 => $result['gender'],
				'source'         => $result['source'],
				'order_count'    => $result['order_count'],
				'total_cheque'   => $result['total_cheque'],
				'avg_cheque'     => $result['avg_cheque'],
				'order_good_count'  => $result['order_good_count'],
				'total_calls'    => $result['total_calls'],
				'currency'       => $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$result['store_id']),
				'country'        => $result['country_id']?$this->data['countries'][$result['country_id']]['iso_code_2']:false,
				'city'           => $result['city'],
				'discount_card'  => $result['discount_card'],
				'discount_percent'  => $result['discount_percent'],
				'birthday'       => $result['birthday']?date($this->language->get('date_format_short'), strtotime($result['birthday'])):'',
				'email'          => $result['email'],
				'email_bad'		 => !$this->emailBlackList->check($result['email']),
                'phone'          => $result['telephone'],
				'fax'            => $result['fax'],
				'orders_for_csi' => $orders_for_csi,
				'last_my_call'	 => $this->model_sale_customer->getMyLastCall($result['customer_id']),
				'csi_reject'	 => $result['csi_reject'],
				'csi_average'	 => $result['csi_average'],
				'customer_group' => $result['customer_group'],
				'customer_comment' => $result['customer_comment'],
				'cashless_info' => $result['cashless_info'],
				'nbt_customer' 	 => $result['nbt_customer'],
				'rja_customer' 	 => $result['rja_customer'],
				'nbt_csi' 		 => $result['nbt_csi'],
				'status'         => ($result['status'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'ip'             => $result['ip'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['customer_id'], $this->request->post['selected']),
				'letter_href'    => $this->url->link('sale/customer/printlist', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
				'preauth_url'    => $preauth_url,
				'mail_status'    => $result['mail_status'],
				'mail_opened'    => $result['mail_opened'],
				'mail_clicked'   => $result['mail_clicked'],
				'customer_href'  => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
				'has_push'       => $result['has_push'],
				'cron_sent'      => $result['cron_sent'],
				'printed2912'    => $result['printed2912'],
				'action'         => $action
				);
			}
			
			//for filter
			$this->load->model('catalog/manufacturer');
			$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_default'] = $this->language->get('text_default');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_email'] = $this->language->get('column_email');
			$this->data['column_customer_group'] = $this->language->get('column_customer_group');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_approved'] = $this->language->get('column_approved');
			$this->data['column_ip'] = $this->language->get('column_ip');
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_login'] = $this->language->get('column_login');
			$this->data['column_action'] = $this->language->get('column_action');
			
			$this->data['button_approve'] = $this->language->get('button_approve');
			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');
			$this->data['button_filter'] = $this->language->get('button_filter');
			
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
			
			if (isset($this->request->get['filter_phone'])) {
				$url .= '&filter_phone=' . urlencode(html_entity_decode($this->request->get['filter_phone'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_source'])) {
				$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_gender'])) {
				$url .= '&filter_gender=' . $this->request->get['filter_gender'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_birthday_from'])) {
				$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
			}
			
			if (isset($this->request->get['filter_birthday_to'])) {
				$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
			}
			
			if (isset($this->request->get['order_first_date_from'])) {
				$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
			}
			
			if (isset($this->request->get['order_first_date_to'])) {
				$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
			}
			
			if (isset($this->request->get['filter_order_count'])) {
				$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
			}
			
			if (isset($this->request->get['filter_order_good_count'])) {
				$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
			}
			
			if (isset($this->request->get['filter_total_sum'])) {
				$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
			}
			
			if (isset($this->request->get['filter_avg_cheque'])) {
				$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
			}
			
			if (isset($this->request->get['filter_interest_brand'])) {
				$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
			}
			
			if (isset($this->request->get['filter_interest_category'])) {
				$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
			}
			
			if (isset($this->request->get['filter_segment_id'])) {
				$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
			}
			
			if (isset($this->request->get['filter_has_discount'])) {
				$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
			}
			
			if (isset($this->request->get['filter_no_discount'])) {
				$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
			}
			
			if (isset($this->request->get['filter_no_birthday'])) {
				$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
			}
			
			if (isset($this->request->get['filter_mail_status'])) {
				$url .= '&filter_mail_status=' . $this->request->get['filter_mail_status'];
			}
			
			if (isset($this->request->get['filter_custom_filter'])) {
				$url .= '&filter_custom_filter=' . $this->request->get['filter_custom_filter'];
			}
			
			if (isset($this->request->get['filter_mail_opened'])) {
				$url .= '&filter_mail_opened=1';
			}
			
			if (isset($this->request->get['filter_mail_checked'])) {
				$url .= '&filter_mail_checked=1';
			}
			
			if (isset($this->request->get['filter_push_signed'])) {
				$url .= '&filter_push_signed=1';
			}
			
			if (isset($this->request->get['filter_nbt_customer'])) {
				$url .= '&filter_nbt_customer=1';
			}
			
			if (isset($this->request->get['filter_last_call'])) {
				$url .= '&filter_last_call=' . $this->request->get['filter_last_call'];
			}
			
			if (isset($this->request->get['filter_nbt_customer_exclude'])) {
				$url .= '&filter_nbt_customer_exclude=1';
			}
			
			if (isset($this->request->get['filter_segment_intersection'])) {
				$url .= '&filter_segment_intersection=1';
			}
			
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['sort_name'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
			$this->data['sort_email'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
			$this->data['sort_customer_group'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');
			$this->data['sort_status'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
			$this->data['sort_approved'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.approved' . $url, 'SSL');
			$this->data['sort_ip'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.ip' . $url, 'SSL');
			$this->data['sort_date_added'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');
			$this->data['export_to_csv'] = $this->url->link('sale/customer/getCSV', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');
			$this->data['export_to_csv_gc'] = $this->url->link('sale/customer/getCSV', 'token=' . $this->session->data['token'] . '&sort=c.date_added&gc_format=1' . $url, 'SSL');
			$this->data['export_to_csv_viber'] = $this->url->link('sale/customer/getCSV', 'token=' . $this->session->data['token'] . '&sort=c.date_added&viber_format=1&filter_custom_filter=hasviberphone' . $url, 'SSL');
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_phone'])) {
				$url .= '&filter_phone=' . urlencode(html_entity_decode($this->request->get['filter_phone'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_source'])) {
				$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_gender'])) {
				$url .= '&filter_gender=' . $this->request->get['filter_gender'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_birthday_from'])) {
				$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
			}
			
			if (isset($this->request->get['filter_birthday_to'])) {
				$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
			}
			
			if (isset($this->request->get['order_first_date_from'])) {
				$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
			}
			
			if (isset($this->request->get['order_first_date_to'])) {
				$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
			}
			
			if (isset($this->request->get['filter_order_count'])) {
				$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
			}
			
			if (isset($this->request->get['filter_order_good_count'])) {
				$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
			}
			
			if (isset($this->request->get['filter_total_sum'])) {
				$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
			}
			
			if (isset($this->request->get['filter_avg_cheque'])) {
				$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
			}
			
			if (isset($this->request->get['filter_interest_brand'])) {
				$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
			}
			
			if (isset($this->request->get['filter_interest_category'])) {
				$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
			}
			
			if (isset($this->request->get['filter_segment_id'])) {
				$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
			}
			
			if (isset($this->request->get['filter_has_discount'])) {
				$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
			}
			
			if (isset($this->request->get['filter_no_discount'])) {
				$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
			}
			
			if (isset($this->request->get['filter_no_birthday'])) {
				$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
			}
			
			if (isset($this->request->get['filter_mail_status'])) {
				$url .= '&filter_mail_status=' . $this->request->get['filter_mail_status'];
			}
			
			if (isset($this->request->get['filter_custom_filter'])) {
				$url .= '&filter_custom_filter=' . $this->request->get['filter_custom_filter'];
			}
			
			if (isset($this->request->get['filter_mail_opened'])) {
				$url .= '&filter_mail_opened=1';
			}
			
			if (isset($this->request->get['filter_mail_checked'])) {
				$url .= '&filter_mail_checked=1';
			}
			
			if (isset($this->request->get['filter_push_signed'])) {
				$url .= '&filter_push_signed=1';
			}
			
			if (isset($this->request->get['filter_nbt_customer'])) {
				$url .= '&filter_nbt_customer=1';
			}
			
			if (isset($this->request->get['filter_last_call'])) {
				$url .= '&filter_last_call=' . $this->request->get['filter_last_call'];
			}
			
			if (isset($this->request->get['filter_nbt_customer_exclude'])) {
				$url .= '&filter_nbt_customer_exclude=1';
			}
			
			if (isset($this->request->get['filter_segment_intersection'])) {
				$url .= '&filter_segment_intersection=1';
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
			
			$this->data['filter_no_verified_address'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&filter_custom_filter=noverifiedaddress', 'SSL');
			$this->data['filter_no_passport_url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&filter_custom_filter=nopassport', 'SSL');
			$this->data['filter_no_discount_url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&filter_custom_filter=nodiscount', 'SSL');
			$this->data['filter_birthday_month'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&filter_birthday_from='.date('m-d').'&filter_birthday_to='.date('m-d', strtotime("+1 month")), 'SSL');
			$this->data['filter_birthday_week'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&filter_birthday_from='.date('m-d').'&filter_birthday_to='.date('m-d', strtotime("+1 week")), 'SSL');
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_phone'])) {
				$url .= '&filter_phone=' . urlencode(html_entity_decode($this->request->get['filter_phone'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_source'])) {
				$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_gender'])) {
				$url .= '&filter_gender=' . $this->request->get['filter_gender'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}
			
			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_birthday_from'])) {
				$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
			}
			
			if (isset($this->request->get['filter_birthday_to'])) {
				$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
			}
			
			if (isset($this->request->get['order_first_date_from'])) {
				$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
			}
			
			if (isset($this->request->get['order_first_date_to'])) {
				$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
			}
			
			if (isset($this->request->get['filter_order_count'])) {
				$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
			}
			
			if (isset($this->request->get['filter_order_good_count'])) {
				$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
			}
			
			if (isset($this->request->get['filter_total_sum'])) {
				$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
			}
			
			if (isset($this->request->get['filter_avg_cheque'])) {
				$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
			}
			
			if (isset($this->request->get['filter_interest_brand'])) {
				$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
			}
			
			if (isset($this->request->get['filter_interest_category'])) {
				$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
			}
			
			if (isset($this->request->get['filter_segment_id'])) {
				$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
			}
			
			if (isset($this->request->get['filter_has_discount'])) {
				$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
			}
			
			if (isset($this->request->get['filter_no_discount'])) {
				$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
			}
			
			if (isset($this->request->get['filter_no_birthday'])) {
				$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
			}
			
			if (isset($this->request->get['filter_mail_status'])) {
				$url .= '&filter_mail_status=' . $this->request->get['filter_mail_status'];
			}
			
			if (isset($this->request->get['filter_custom_filter'])) {
				$url .= '&filter_custom_filter=' . $this->request->get['filter_custom_filter'];
			}
			
			if (isset($this->request->get['filter_mail_opened'])) {
				$url .= '&filter_mail_opened=1';
			}
			
			if (isset($this->request->get['filter_mail_checked'])) {
				$url .= '&filter_mail_checked=1';
			}
			
			if (isset($this->request->get['filter_push_signed'])) {
				$url .= '&filter_push_signed=1';
			}
			
			if (isset($this->request->get['filter_nbt_customer'])) {
				$url .= '&filter_nbt_customer=1';
			}
			
			if (isset($this->request->get['filter_last_call'])) {
				$url .= '&filter_last_call=' . $this->request->get['filter_last_call'];
			}
			
			if (isset($this->request->get['filter_nbt_customer_exclude'])) {
				$url .= '&filter_nbt_customer_exclude=1';
			}
			
			if (isset($this->request->get['filter_segment_intersection'])) {
				$url .= '&filter_segment_intersection=1';
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
			$pagination->url = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['filter_name'] = $filter_name;
			$this->data['filter_email'] = $filter_email;
			$this->data['filter_phone'] = $filter_phone;
			$this->data['filter_source'] = $filter_source;
			$this->data['filter_gender'] = $filter_gender;
			$this->data['filter_customer_group_id'] = $filter_customer_group_id;
			$this->data['filter_status'] = $filter_status;
			$this->data['filter_approved'] = $filter_approved;
			$this->data['filter_country_id'] = $filter_country_id;
			$this->data['filter_ip'] = $filter_ip;
			$this->data['filter_date_added'] = $filter_date_added;
			$this->data['filter_birthday_from'] = $filter_birthday_from;
			$this->data['filter_birthday_to'] = $filter_birthday_to;
			$this->data['order_first_date_from'] = $order_first_date_from;
			$this->data['order_first_date_to'] = $order_first_date_to;
			$this->data['filter_order_count'] = $filter_order_count;
			$this->data['filter_order_good_count'] = $filter_order_good_count;
			$this->data['filter_total_sum'] = $filter_total_sum;
			$this->data['filter_avg_cheque'] = $filter_avg_cheque;
			$this->data['filter_interest_brand'] = $filter_interest_brand;
			$this->data['filter_interest_category'] = $filter_interest_category;
			$this->data['filter_segment_id'] = $filter_segment_id;
			$this->data['filter_has_discount'] = $filter_has_discount;
			$this->data['filter_no_discount'] = $filter_no_discount;
			$this->data['filter_no_birthday'] = $filter_no_birthday;
			$this->data['filter_mail_status'] = $filter_mail_status;
			$this->data['filter_mail_opened'] = $filter_mail_opened;
			$this->data['filter_mail_checked'] = $filter_mail_checked;
			$this->data['filter_push_signed'] = $filter_push_signed;
			$this->data['filter_nbt_customer'] = $filter_nbt_customer;
			$this->data['filter_last_call'] = $filter_last_call;
			$this->data['filter_nbt_customer_exclude'] = $filter_nbt_customer_exclude;
			$this->data['filter_segment_intersection'] = $filter_segment_intersection;
			$this->data['filter_custom_filter'] = $filter_custom_filter;
			
			
			$this->load->model('sale/customer_group');
			
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
			$this->data['mail_status'] = $this->model_sale_customer->getMailStatus();
			// Нужно получить все рассылки
			$this->data['mail_campaings'] = $this->model_sale_customer->getAllMailCampaings();
			$this->data['filter_mail_campaings'] = isset($_GET['campaing_id']) ? $_GET['campaing_id'] : false;
			
			$this->data['segments'] =  $this->model_sale_customer_group->getCustomerSegments(array('start' => 0, 'limit' => 1000));
			
			
			$this->load->model('setting/store');
			
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'sale/customer_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function printlist(){
			$this->data['token'] = $this->session->data['token'];
			if (isset($this->request->get['customer_id'])){
				$this->load->model('sale/customer');
				
				$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
				$this->data['name'] = $customer_info['firstname'] .' ' . $customer_info['lastname'];
				
				$this->data['store_id'] = $customer_info['store_id'];
				
				$adrss = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
				$adrs = array_shift($adrss);
				
				$this->data['country'] = $adrs['country'];
				$this->data['company'] = $adrs['company'];
				$this->data['country_id'] = $adrs['country_id'];
				$this->data['address1'] = $adrs['address_1'];
				$this->data['address2'] = $adrs['address_2'];
				$this->data['city'] = $adrs['city'];
				$this->data['zone'] = $adrs['zone'];
				$this->data['postcode'] = $adrs['postcode'];
				
				$this->template = 'sale/letter110x220.tpl';
				$this->response->setOutput($this->render());
			}
			else die('Нет такого покупателя');
		}
		
		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_none'] = $this->language->get('text_none');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
			$this->data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
			
			$this->data['column_ip'] = $this->language->get('column_ip');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_action'] = $this->language->get('column_action');
			
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_lastname'] = $this->language->get('entry_lastname');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_fax'] = $this->language->get('entry_fax');
			$this->data['entry_password'] = $this->language->get('entry_password');
			$this->data['entry_confirm'] = $this->language->get('entry_confirm');
			$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
			$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_company'] = $this->language->get('entry_company');
			$this->data['entry_company_id'] = $this->language->get('entry_company_id');
			$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
			$this->data['entry_address_1'] = $this->language->get('entry_address_1');
			$this->data['entry_address_2'] = $this->language->get('entry_address_2');
			$this->data['entry_city'] = $this->language->get('entry_city');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_default'] = $this->language->get('entry_default');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			$this->data['entry_description'] = $this->language->get('entry_description');
			$this->data['entry_amount'] = $this->language->get('entry_amount');
			$this->data['entry_points'] = $this->language->get('entry_points');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_address'] = $this->language->get('button_add_address');
			$this->data['button_add_history'] = $this->language->get('button_add_history');
			$this->data['button_add_transaction'] = $this->language->get('button_add_transaction');
			$this->data['button_add_reward'] = $this->language->get('button_add_reward');
			$this->data['button_remove'] = $this->language->get('button_remove');
			
			$this->data['tab_general'] = $this->language->get('tab_general');
			$this->data['tab_address'] = $this->language->get('tab_address');
			$this->data['tab_history'] = $this->language->get('tab_history');
			$this->data['tab_transaction'] = $this->language->get('tab_transaction');
			$this->data['tab_reward'] = $this->language->get('tab_reward');
			$this->data['tab_ip'] = $this->language->get('tab_ip');
			
			$this->document->addScript('view/javascript/jquery/ui/jquery-ui-timepicker-addon.js');
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->request->get['customer_id'])) {
				$this->data['customer_id'] = $this->request->get['customer_id'];
				} else {
				$this->data['customer_id'] = 0;
			}
			
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['firstname'])) {
				$this->data['error_firstname'] = $this->error['firstname'];
				} else {
				$this->data['error_firstname'] = '';
			}
			
			if (isset($this->error['lastname'])) {
				$this->data['error_lastname'] = $this->error['lastname'];
				} else {
				$this->data['error_lastname'] = '';
			}
			
			if (isset($this->error['email'])) {
				$this->data['error_email'] = $this->error['email'];
				} else {
				$this->data['error_email'] = '';
			}
			
			if (isset($this->error['telephone'])) {
				$this->data['error_telephone'] = $this->error['telephone'];
				} else {
				$this->data['error_telephone'] = '';
			}
			
			if (isset($this->error['password'])) {
				$this->data['error_password'] = $this->error['password'];
				} else {
				$this->data['error_password'] = '';
			}
			
			if (isset($this->error['confirm'])) {
				$this->data['error_confirm'] = $this->error['confirm'];
				} else {
				$this->data['error_confirm'] = '';
			}
			
			if (isset($this->error['address_firstname'])) {
				$this->data['error_address_firstname'] = $this->error['address_firstname'];
				} else {
				$this->data['error_address_firstname'] = '';
			}
			
			if (isset($this->error['address_lastname'])) {
				$this->data['error_address_lastname'] = $this->error['address_lastname'];
				} else {
				$this->data['error_address_lastname'] = '';
			}
			
			if (isset($this->error['address_tax_id'])) {
				$this->data['error_address_tax_id'] = $this->error['address_tax_id'];
				} else {
				$this->data['error_address_tax_id'] = '';
			}
			
			if (isset($this->error['address_address_1'])) {
				$this->data['error_address_address_1'] = $this->error['address_address_1'];
				} else {
				$this->data['error_address_address_1'] = '';
			}
			
			if (isset($this->error['address_city'])) {
				$this->data['error_address_city'] = $this->error['address_city'];
				} else {
				$this->data['error_address_city'] = '';
			}
			
			if (isset($this->error['address_postcode'])) {
				$this->data['error_address_postcode'] = $this->error['address_postcode'];
				} else {
				$this->data['error_address_postcode'] = '';
			}
			
			if (isset($this->error['address_country'])) {
				$this->data['error_address_country'] = $this->error['address_country'];
				} else {
				$this->data['error_address_country'] = '';
			}
			
			if (isset($this->error['address_zone'])) {
				$this->data['error_address_zone'] = $this->error['address_zone'];
				} else {
				$this->data['error_address_zone'] = '';
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
			
			if (isset($this->request->get['filter_source'])) {
				$url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_gender'])) {
				$url .= '&filter_gender=' . $this->request->get['filter_gender'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_birthday_from'])) {
				$url .= '&filter_birthday_from=' . $this->request->get['filter_birthday_from'];
			}
			
			if (isset($this->request->get['filter_birthday_to'])) {
				$url .= '&filter_birthday_to=' . $this->request->get['filter_birthday_to'];
			}
			
			if (isset($this->request->get['order_first_date_from'])) {
				$url .= '&order_first_date_from=' . $this->request->get['order_first_date_from'];
			}
			
			if (isset($this->request->get['order_first_date_to'])) {
				$url .= '&order_first_date_to=' . $this->request->get['order_first_date_to'];
			}
			
			if (isset($this->request->get['filter_order_count'])) {
				$url .= '&filter_order_count=' . $this->request->get['filter_order_count'];
			}
			
			if (isset($this->request->get['filter_order_good_count'])) {
				$url .= '&filter_order_good_count=' . $this->request->get['filter_order_good_count'];
			}
			
			if (isset($this->request->get['filter_total_sum'])) {
				$url .= '&filter_total_sum=' . $this->request->get['filter_total_sum'];
			}
			
			if (isset($this->request->get['filter_avg_cheque'])) {
				$url .= '&filter_avg_cheque=' . $this->request->get['filter_avg_cheque'];
			}
			
			if (isset($this->request->get['filter_interest_brand'])) {
				$url .= '&filter_interest_brand=' . $this->request->get['filter_interest_brand'];
			}
			
			if (isset($this->request->get['filter_interest_category'])) {
				$url .= '&filter_interest_category=' . $this->request->get['filter_interest_category'];
			}
			
			if (isset($this->request->get['filter_segment_id'])) {
				$url .= '&filter_segment_id=' . $this->request->get['filter_segment_id'];
			}
			
			if (isset($this->request->get['filter_has_discount'])) {
				$url .= '&filter_has_discount=' . $this->request->get['filter_has_discount'];
			}
			
			if (isset($this->request->get['filter_no_discount'])) {
				$url .= '&filter_no_discount=' . $this->request->get['filter_no_discount'];
			}
			
			if (isset($this->request->get['filter_no_birthday'])) {
				$url .= '&filter_no_birthday=' . $this->request->get['filter_no_birthday'];
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
			
			if (isset($this->request->get['filter_custom_filter'])) {
				$url .= '&filter_custom_filter=' . $this->request->get['filter_custom_filter'];
			}
			
			if (isset($this->request->get['filter_mail_status'])) {
				$url .= '&filter_mail_status=' . $this->request->get['filter_mail_status'];
			}
			
			if (isset($this->request->get['filter_mail_opened'])) {
				$url .= '&filter_mail_opened=1';
			}
			
			if (isset($this->request->get['filter_mail_checked'])) {
				$url .= '&filter_mail_checked=1';
			}
			
			if (isset($this->request->get['filter_push_signed'])) {
				$url .= '&filter_push_signed=1';
			}
			
			if (isset($this->request->get['filter_nbt_customer'])) {
				$url .= '&filter_nbt_customer=1';
			}
			
			if (isset($this->request->get['filter_last_call'])) {
				$url .= '&filter_last_call=' . $this->request->get['filter_last_call'];
			}
			
			if (isset($this->request->get['filter_nbt_customer_exclude'])) {
				$url .= '&filter_nbt_customer_exclude=1';
			}
			
			if (isset($this->request->get['filter_segment_intersection'])) {
				$url .= '&filter_segment_intersection=1';
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['customer_id'])) {
				$this->data['action'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
				} else {
				$this->data['action'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL');
			}
			
			$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');			
			$this->data['letter_href'] = !empty($this->request->get['customer_id'])?$this->url->link('sale/customer/printlist', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'):'';
			
			$this->data['mail_status']	= '';
			$this->data['mail_opened']	= '';
			$this->data['mail_clicked']	= '';
			
			if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
				
				
				//mail info
				if ($customer_info['mail_status']){
					if ($customer_info['mail_status'] == 'delivery'){
						$mail_status = "<span style='color:white; padding:3px; background:#4ea24e;'>".$customer_info['mail_status']."</span>";
						} else {
						$mail_status = "<span style='color:white; padding:3px; background:#cf4a61;'>".$customer_info['mail_status']."</span>";
					}
					} else {
					$mail_status = "<span style='color:white; padding:3px; background:#e4c25a;'>unknown</span>";
				}
				
				if ($customer_info['mail_opened']){
					$mail_opened = "<span style='color:white; padding:3px; background:#4ea24e;'>".$customer_info['mail_opened']."</span>";
					} else {
					$mail_opened = "<span style='color:white; padding:3px; background:#e4c25a;'>".$customer_info['mail_opened']."</span>";
				}
				
				if ($customer_info['mail_clicked']){
					$mail_clicked = "<span style='color:white; padding:3px; background:#4ea24e;'>".$customer_info['mail_clicked']."</span>";
					} else {
					$mail_clicked = "<span style='color:white; padding:3px; background:#e4c25a;'>".$customer_info['mail_clicked']."</span>";
				}
				
				$this->data['mail_status']	= $mail_status;
				$this->data['mail_opened']	= $mail_opened;
				$this->data['mail_clicked']	= $mail_clicked;
				
			}
			
			$this->load->model('catalog/actiontemplate');
			$this->load->model('tool/image');
			$atdata = array(
			'manager_id' => $this->user->getID()			
			);
			$this->data['actiontemplates'] = $this->model_catalog_actiontemplate->getactiontemplates($atdata);
			
			foreach ($this->data['actiontemplates'] as &$at){
				$at['image'] = $this->model_tool_image->resize($at['image'], 100, 100);
				
				if (isset($customer_info)) {
					$at['sent'] = $this->model_catalog_actiontemplate->getActionTemplateLastHistory($customer_info['customer_id'], $at['actiontemplate_id']);
					} else {
					$at['sent'] = '';
				}
			}
			
			$this->load->model('kp/csi');
			$this->load->model('sale/order');
			$this->load->model('localisation/order_status');
			
			$this->data['orders_for_csi'] = array();
			foreach ($orders_for_csi = $this->model_kp_csi->getCompletedOrdersWithoutCSI($customer_info['customer_id']) as $ofc){
				$ofco = $this->model_sale_order->getOrder($ofc);
				
				$this->data['orders_for_csi'][] = array(
				'order_id' => $ofco['order_id'],
				'order_status' => $this->model_localisation_order_status->getOrderStatus($ofco['order_status_id']),
				'href'     => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $ofco['order_id']),
				'last_modified' => date('d.m.Y', strtotime($ofco['date_modified']))
				);
			}
			
			
			$this->load->model('setting/store');
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			if (isset($customer_info['store_id'])){
				$this->load->model('setting/setting');
				$this->data['store_name'] = $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']);
				$this->data['currency_code'] = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$customer_info['store_id']);
				
				$this->load->model('localisation/currency');
				$currency = $this->model_localisation_currency->getCurrencyByCode($this->data['currency_code']);
				$this->data['currency_name'] = $currency['title'];
			}
			
			if (isset($this->request->post['firstname'])) {
				$this->data['firstname'] = $this->request->post['firstname'];
				} elseif (!empty($customer_info)) {
				$this->data['firstname'] = $customer_info['firstname'];
				} else {
				$this->data['firstname'] = '';
			}
			
			if (isset($this->request->post['lastname'])) {
				$this->data['lastname'] = $this->request->post['lastname'];
				} elseif (!empty($customer_info)) {
				$this->data['lastname'] = $customer_info['lastname'];
				} else {
				$this->data['lastname'] = '';
			}
			
			if (isset($this->request->post['mudak'])) {
				$this->data['mudak'] = $this->request->post['mudak'];
				} elseif (!empty($customer_info)) {
				$this->data['mudak'] = $customer_info['mudak'];
				} else {
				$this->data['mudak'] = 0;
			}
			
			if (isset($this->request->post['gender'])) {
				$this->data['gender'] = $this->request->post['gender'];
				} elseif (!empty($customer_info)) {
				$this->data['gender'] = $customer_info['gender'];
				} else {
				$this->data['gender'] = 0;
			}
			
			if (isset($this->request->post['cron_sent'])) {
				$this->data['cron_sent'] = $this->request->post['cron_sent'];
				} elseif (!empty($customer_info)) {
				$this->data['cron_sent'] = $customer_info['cron_sent'];
				} else {
				$this->data['cron_sent'] = 0;
			}
			
			if (isset($this->request->post['printed2912'])) {
				$this->data['printed2912'] = $this->request->post['printed2912'];
				} elseif (!empty($customer_info)) {
				$this->data['printed2912'] = $customer_info['printed2912'];
				} else {
				$this->data['printed2912'] = 0;
			}
			
			if (isset($this->request->post['store_id'])) {
				$this->data['store_id'] = $this->request->post['store_id'];
				} elseif (!empty($customer_info)) {
				$this->data['store_id'] = $customer_info['store_id'];
				} else {
				$this->data['store_id'] = '';
			}
			
			if (isset($this->request->post['customer_comment'])) {
				$this->data['customer_comment'] = $this->request->post['customer_comment'];
				} elseif (!empty($customer_info)) {
				$this->data['customer_comment'] = $customer_info['customer_comment'];
				} else {
				$this->data['customer_comment'] = '';
			}
			
			if (isset($this->request->post['cashless_info'])) {
				$this->data['cashless_info'] = $this->request->post['cashless_info'];
				} elseif (!empty($customer_info)) {
				$this->data['cashless_info'] = $customer_info['cashless_info'];
				} else {
				$this->data['cashless_info'] = '';
			}
			
			if (isset($this->request->post['discount_card'])) {
				$this->data['discount_card'] = $this->request->post['discount_card'];
				} elseif (!empty($customer_info)) {
				$this->data['discount_card'] = $customer_info['discount_card'];
				} else {
				$this->data['discount_card'] = '';
			}
			
			if (isset($this->request->post['birthday'])) {
				$this->data['birthday'] = $this->request->post['birthday'];
				} elseif (!empty($customer_info)) {
				$this->data['birthday'] = $customer_info['birthday'];
				} else {
				$this->data['birthday'] = '';
			}
			
			if (isset($this->request->post['passport_serie'])) {
				$this->data['passport_serie'] = $this->request->post['passport_serie'];
				} elseif (!empty($customer_info)) {
				$this->data['passport_serie'] = $customer_info['passport_serie'];
				} else {
				$this->data['passport_serie'] = '';
			}
			
			if (isset($this->request->post['passport_given'])) {
				$this->data['passport_given'] = $this->request->post['passport_given'];
				} elseif (!empty($customer_info)) {
				$this->data['passport_given'] = $customer_info['passport_given'];
				} else {
				$this->data['passport_given'] = '';
			}
			
			if (isset($this->request->post['email'])) {
				$this->data['email'] = $this->request->post['email'];
				} elseif (!empty($customer_info)) {
				$this->data['email'] = $customer_info['email'];
				} else {
				$this->data['email'] = '';
			}
			
			if (isset($this->request->post['telephone'])) {
				$this->data['telephone'] = $this->request->post['telephone'];
				} elseif (!empty($customer_info)) {
				$this->data['telephone'] = $customer_info['telephone'];
				} else {
				$this->data['telephone'] = '';
			}
			
			$this->data['telephone_real'] = preg_replace("/\D+/", "", $this->data['telephone']);
			
			if (isset($this->request->post['fax'])) {
				$this->data['fax'] = $this->request->post['fax'];
				} elseif (!empty($customer_info)) {
				$this->data['fax'] = $customer_info['fax'];
				} else {
				$this->data['fax'] = '';
			}
			
			if (isset($this->request->post['newsletter'])) {
				$this->data['newsletter'] = $this->request->post['newsletter'];
				} elseif (!empty($customer_info)) {
				$this->data['newsletter'] = $customer_info['newsletter'];
				} else {
				$this->data['newsletter'] = '';
			}
			
			if (isset($this->request->post['viber_news'])) {
				$this->data['viber_news'] = $this->request->post['viber_news'];
				} elseif (!empty($customer_info)) {
				$this->data['viber_news'] = $customer_info['viber_news'];
				} else {
				$this->data['viber_news'] = '';
			}
			
			if (isset($this->request->post['newsletter_news'])) {
				$this->data['newsletter_news'] = $this->request->post['newsletter_news'];
				} elseif (!empty($customer_info)) {
				$this->data['newsletter_news'] = $customer_info['newsletter_news'];
				} else {
				$this->data['newsletter_news'] = '';
			}
			
			if (isset($this->request->post['newsletter_personal'])) {
				$this->data['newsletter_personal'] = $this->request->post['newsletter_personal'];
				} elseif (!empty($customer_info)) {
				$this->data['newsletter_personal'] = $customer_info['newsletter_personal'];
				} else {
				$this->data['newsletter_personal'] = '';
			}
			
			if (!empty($customer_info)) {
				$this->data['nbt_customer'] = $customer_info['nbt_customer'];
				} else {
				$this->data['nbt_customer'] = 0;
			}
			
			if (!empty($customer_info)) {
				$this->data['rja_customer'] = $customer_info['rja_customer'];
				} else {
				$this->data['rja_customer'] = 0;
			}
			
			$this->load->model('sale/customer_group');
			
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
			
			if (isset($this->request->post['customer_group_id'])) {
				$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
				} elseif (!empty($customer_info)) {
				$this->data['customer_group_id'] = $customer_info['customer_group_id'];
				} else {
				$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (!empty($customer_info)) {
				$this->data['status'] = $customer_info['status'];
				} else {
				$this->data['status'] = 1;
			}
			
			if (isset($this->request->post['notify'])) { 
				$this->data['notify'] = 1;
				} else {
				$this->data['notify'] = 0;
			};
			
			if (isset($this->request->post['password'])) {
				$this->data['password'] = $this->request->post['password'];
				} else {
				$this->data['password'] = '';
			}
			
			if (isset($this->request->post['confirm'])) {
				$this->data['confirm'] = $this->request->post['confirm'];
				} else {
				$this->data['confirm'] = '';
			}
			
			$this->load->model('localisation/country');
			
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			if (isset($this->request->post['address'])) {
				$this->data['addresses'] = $this->request->post['address'];
				} elseif (isset($this->request->get['customer_id'])) {
				$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
				} else {
				$this->data['addresses'] = array();
			}
			
			if (isset($this->request->post['address_id'])) {
				$this->data['address_id'] = $this->request->post['address_id'];
				} elseif (!empty($customer_info)) {
				$this->data['address_id'] = $customer_info['address_id'];
				} else {
				$this->data['address_id'] = '';
			}
			
			$this->data['ips'] = array();
			
			if (!empty($customer_info)) {
				$results = $this->model_sale_customer->getIpsByCustomerId($this->request->get['customer_id']);
				
				foreach ($results as $result) {
					$ban_ip_total = $this->model_sale_customer->getTotalBanIpsByIp($result['ip']);
					
					$this->data['ips'][] = array(
					'ip'         => $result['ip'],
					'total'      => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
					'date_added' => date('d/m/y', strtotime($result['date_added'])),
					'filter_ip'  => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL'),
					'ban_ip'     => $ban_ip_total
					);
				}
			}
			
			$this->template = 'sale/customer_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$this->error['firstname'] = $this->language->get('error_firstname');
			}
			
			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				//$this->error['lastname'] = $this->language->get('error_lastname');
			}
			
			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				//$this->error['email'] = $this->language->get('error_email');
			}
			
			$customer_info = $this->model_sale_customer->getCustomerByEmail($this->request->post['email']);
			
			if (!isset($this->request->get['customer_id'])) {
				if ($customer_info) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
				} else {
				if ($customer_info && ($this->request->get['customer_id'] != $customer_info['customer_id'])) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			}
			/*
				if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$this->error['telephone'] = $this->language->get('error_telephone');
				}
			*/
			if ($this->request->post['password'] || (!isset($this->request->get['customer_id']))) {
				if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
					$this->error['password'] = $this->language->get('error_password');
				}
				
				if ($this->request->post['password'] != $this->request->post['confirm']) {
					$this->error['confirm'] = $this->language->get('error_confirm');
				}
			}
			
			
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete($customer_ids) {
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($customer_ids as $customer_id){
				
				$order_total = $this->model_sale_customer->getTotalOrders($customer_id);
				
				if ($order_total > 0) {
					$this->error['warning'] = 'Нельзя удалить покупателя, у которого есть заказы. Сначала нужно присвоить их другому, либо удалить историю заказов';
					break;
				}
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		public function login() {
			$json = array();
			
			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
				} else {
				$customer_id = 0;
			}
			
			$this->load->model('sale/customer');
			
			$customer_info = $this->model_sale_customer->getCustomer($customer_id);
			
			if ($customer_info) {
				$token = md5(mt_rand());
				
				$this->model_sale_customer->editToken($customer_id, $token);

				if (isset($this->request->get['store_id'])) {
					$store_id = $this->request->get['store_id'];
					} else {
					$store_id = 0;
				}
				
				$this->load->model('setting/store');
				
				$store_info = $this->model_setting_store->getStore($store_id);

				
				if ($store_info) {
					$this->redirect($store_info['url'] . 'account/login&token=' . $token);
					} else {
					$this->redirect(HTTPS_CATALOG . 'account/login&token=' . $token);
				}

				} else {
				$this->language->load('error/not_found');
				
				$this->document->setTitle($this->language->get('heading_title'));
				
				$this->data['heading_title'] = $this->language->get('heading_title');
				
				$this->data['text_not_found'] = $this->language->get('text_not_found');
				
				$this->data['breadcrumbs'] = array();
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
				);
				
				$this->template = 'error/not_found.tpl';
				$this->children = array(
				'common/header',
				'common/footer'
				);
				
				$this->response->setOutput($this->render());
			}
		}
		
		public function customerViewed(){
			$this->load->model('sale/customer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$customer_id = $this->request->get['customer_id'];
			
			
			//get categories
			$results = $this->model_sale_customer->getCustomerViews('c', $customer_id, 20);
			$this->data['categories'] = array();
			
			foreach ($results as $result){
				$category = $this->model_catalog_category->getCategory($result['entity_id']);
				
				if ($category) {
					
					$this->data['categories'][] = array(
					'category_id' => $result['entity_id'],
					'name'        => $category['name'],
					'image'		  => $this->model_tool_image->resize($category['image'], 20, 20),
					'times'       => $result['times']
					);
					
				}
			}
			
			//get manufacturers
			$results = $this->model_sale_customer->getCustomerViews('m', $customer_id, 20);
			$this->data['manufacturers'] = array();
			
			foreach ($results as $result){
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($result['entity_id']);
				
				if ($manufacturer) {
					
					$this->data['manufacturers'][] = array(
					'manufacturer_id' => $result['entity_id'],
					'name'        => $manufacturer['name'],
					'image'		  => $this->model_tool_image->resize($manufacturer['image'], 20, 20),
					'times'       => $result['times']
					);
					
				}
			}
			
			//get products
			$results = $this->model_sale_customer->getCustomerViews('p', $customer_id, 30);
			$this->data['products'] = array();
			
			foreach ($results as $result){
				$product = $this->model_catalog_product->getProduct($result['entity_id']);
				
				if ($product) {
					$this->data['products'][] = array(
					'product_id' => $result['entity_id'],
					'model'        => $product['model'],
					'name'        => $product['name'],
					'image'		  => $this->model_tool_image->resize($product['image'], 20, 20),
					'times'       => $result['times']
					);
					
				}
			}
			
			$this->data['ordered_products'] = $this->model_sale_customer->getCustomerOrderProducts($customer_id);
			
			
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'sale/customer_viewed.tpl';
			
			$this->response->setOutput($this->render());
			
			
		}
		
		public function history() {
			$this->language->load('sale/customer');
			
			$this->load->model('sale/customer');
			$this->load->model('sale/sms');
			$this->load->model('sale/customer_group');
			$this->load->model('module/emailtemplate');
			$this->load->model('localisation/order_status');
			$this->load->model('user/user');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) {
				$this->model_sale_customer->addHistory($this->request->get['customer_id'], $this->request->post['comment'], 0, 0, $this->user->getID(), $this->request->post['need_call']);
				
				$this->data['success'] = $this->language->get('text_success');
				} else {
				$this->data['success'] = '';
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
				$this->data['error_warning'] = $this->language->get('error_permission');
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_comment'] = $this->language->get('column_comment');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$this->data['histories'] = array();			
			$results = $this->model_sale_customer->getHistories($this->request->get['customer_id'], ($page - 1) * 30, 30);
			
			
			foreach ($results as $result) {
				
				if ($result['manager_id']){
					$manager = $this->model_user_user->getRealUserNameById($result['manager_id']);
					} else {
					$manager = '';
				}
				
				if ($result['call_id']){
					$call = $this->model_sale_customer->getCustomerCallByID($result['call_id']);
					$call['filename'] = str_replace(SIP_REMOTE_PATH, SIP_REPLACE_PATH, $call['filelink']);
					} else {
					$call = false;
				}
				
				if ($result['order_id']){
					$order_id = (int)$result['order_id'];
					} else {
					$order_id = false;
				}
				
				if ($result['need_call'] && $result['need_call'] != '0000-00-00 00:00:00'){
					$need_call = date('d.m.Y в H:i', strtotime($result['need_call']));
					} else {
					$need_call = '';
				}
				
				if ($result['order_status_id']){
					$order_status = $this->model_localisation_order_status->getOrderStatus($result['order_status_id']);
					} else {
					$order_status = false;
				}
				
				if ($result['prev_order_status_id']){
					$prev_order_status = $this->model_localisation_order_status->getOrderStatus($result['prev_order_status_id']);
					} else {
					$prev_order_status = false;
				}
				
				if ($result['segment_id']){
					$segment = $this->model_sale_customer_group->getCustomerSegment($result['segment_id']);
					} else {
					$segment = false;
				}								
				
				if ($result['sms_id']){
					$sms = $this->model_sale_sms->getSMSByID($result['sms_id'], $this->request->get['customer_id'], $result['order_id']);
					} else {
					$sms = false;
				}
				
				
				$this->data['histories'][] = array(				
				'comment'     			=> $result['comment'],
				'need_call'   			=> $need_call,
				'call'		  			=> $call,
				'call_id'			    => $result['call_id'],
				'order_id'	  			=> (int)$result['order_id'],
				'sms_id'	  			=> (int)$result['sms_id'],
				'sms'	  				=> $sms,
				'email_id'	  			=> (int)$result['email_id'],
				'email'	  				=> $email,
				'segment_id'	  		=> (int)$result['segment_id'],
				'segment'	  			=> $segment,
				'order_status_id'	  	=> (int)$result['order_status_id'],
				'prev_order_status_id'	=> (int)$result['prev_order_status_id'],
				'order_status'		    => $order_status,
				'prev_order_status'	    => $prev_order_status,
				'is_error'    			=> (int)$result['is_error'],
				'manager'     			=> $manager,
				'date_added' 			=> date('d.m.Y H:i:s', strtotime($result['date_added']))
				);
			}
			
			$histories_total = $this->model_sale_customer->getTotalHistories($this->request->get['customer_id']);
			
			$pagination = new Pagination();
			$pagination->total = $histories_total;
			$pagination->page = $page;
			$pagination->limit = 30;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/customer/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'sale/customer_history.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function transaction() {
			$this->language->load('sale/customer');
			
			$this->load->model('sale/customer');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) {
				
				if ($this->request->post['amount'] != 0){
					
					$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
					$this->load->model('setting/setting');
					$currency_code = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$customer_info['store_id']);
					$default_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_currency', (int)$customer_info['store_id']);
					
					$amount_national = $this->request->post['amount'];
					$amount = $this->currency->convert($amount_national, $currency_code, $default_currency);
					
					if (isset($this->request->post['order_id']) && (int)$this->request->post['order_id']){
						$this->request->post['description'] .= ' (заказ №'. (int)$this->request->post['order_id'] .')';
					}
					
					$this->model_sale_customer->addTransaction($this->request->get['customer_id'], $this->request->post['description'], $amount, $amount_national, $currency_code, (int)$this->request->post['order_id'], false, false, 'manual_admin');
					
					$this->data['success'] = $this->language->get('text_success');
					
					} else {
					$this->data['error_warning'] = 'Нулевая сумма!';
				}
				} else {
				$this->data['success'] = '';
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
				$this->data['error_warning'] = $this->language->get('error_permission');
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_balance'] = $this->language->get('text_balance');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_description'] = $this->language->get('column_description');
			$this->data['column_amount'] = $this->language->get('column_amount');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
			$this->load->model('setting/setting');
			$this->data['store_name'] = $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']);
			$this->data['store_currency'] = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$customer_info['store_id']);
			$this->data['transactions'] = array();
			
			$results = $this->model_sale_customer->getTransactions($this->request->get['customer_id'], ($page - 1) * 20, 20);
			
			foreach ($results as $result) {
				
				$amount_national = $this->currency->format($result['amount_national'], $this->data['store_currency'], 1);
				
				$this->data['transactions'][] = array(
				'amount_national' => $amount_national,
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'added_from'  => $result['added_from'],
				'legalperson_id'  => $result['legalperson_id'],
				'legalperson_name'  => $result['legalperson_name'],
				'legalperson_name_1C'  => $result['legalperson_name_1C']
				);
			}
			
			$this->data['balance'] = $this->currency->format($this->model_sale_customer->getTransactionTotal($this->request->get['customer_id']), $this->config->get('config_currency'));
			
			$this->data['balance_national'] = $this->currency->format($this->model_sale_customer->getTransactionTotalNational($this->request->get['customer_id']), $this->data['store_currency'], 1);
			
			$transaction_total = $this->model_sale_customer->getTotalTransactions($this->request->get['customer_id']);
			
			$pagination = new Pagination();
			$pagination->total = $transaction_total;
			$pagination->page = $page;
			$pagination->limit = 20;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/customer/transaction', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->template = 'sale/customer_transaction.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function changeTransactionAmountAjax(){
			$new_amount = $this->request->post['new_amount'];
			$transaction_id = $this->request->post['transaction_id'];
			$currency_code = $this->request->post['currency_code'];
			$store_id = $this->request->post['store_id'];
			
			$this->load->model('sale/order');
			if ($this->user->canDoTransactions()) {
				
				$this->load->model('setting/setting');
				$default_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_currency', (int)$store_id);
				
				$new_eur_amount = $this->currency->convert($new_amount, $currency_code, $default_currency);
				
				$this->db->query("UPDATE customer_transaction SET amount = '" . $this->db->escape($new_eur_amount) . "', amount_national = '" . $this->db->escape($new_amount) . "' WHERE customer_transaction_id = '" . (int)$transaction_id . "'");
				
				$this->load->model('feed/exchange1c');
				$this->model_feed_exchange1c->addOrderToQueue($order_id);
				$this->model_feed_exchange1c->getOrderTransactionsXML($order_id);
				
				} else {
				
			}
		}
		
		public function setCustomerNBTAjax(){
			$customer_id = $this->request->get['customer_id'];
			
			$this->db->query("UPDATE `customer` SET nbt_customer = (1 - nbt_customer) WHERE customer_id = '" . (int)$customer_id . "'");				
			
			
			$check = $this->db->query("SELECT nbt_customer FROM `customer` WHERE customer_id = '" . (int)$customer_id . "'");
			echo (int)$check->row['nbt_customer'];
		}
		
		public function setCustomerRJAAjax(){
			$customer_id = $this->request->get['customer_id'];
			
			$this->db->query("UPDATE `customer` SET rja_customer = (1 - rja_customer) WHERE customer_id = '" . (int)$customer_id . "'");				
			
			
			$check = $this->db->query("SELECT rja_customer FROM `customer` WHERE customer_id = '" . (int)$customer_id . "'");
			echo (int)$check->row['rja_customer'];
		}
		
		
		public function transactionorder() {
			$this->language->load('sale/customer');
			
			$this->load->model('sale/customer');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) {
				
				
				if (!isset($this->request->post['amount'])){
					$this->request->post['amount'] = 0;
				}
				
				$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
				$this->load->model('setting/setting');
				$currency_code = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$customer_info['store_id']);
				$default_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_currency', (int)$customer_info['store_id']);
				
				$amount_national = $this->request->post['amount'];
				$amount = $this->currency->convert($amount_national, $currency_code, $default_currency);
				
				$legalperson_id = (int)$this->request->post['legalperson_id'];
				
				if (isset($this->request->post['send_transaction_sms']) && $this->request->post['send_transaction_sms'] && isset($this->request->post['transaction_sms_text']) && mb_strlen($this->request->post['transaction_sms_text'], 'UTF-8') > 2) {
					$send_sms = true;
					} else {
					$send_sms = false;
				}
				
				if (isset($this->request->post['date_added'])){
					$date_added = $this->request->post['date_added'];
					} else {
					$date_added = false;
				}
				
				$transaction_id = $this->model_sale_customer->addTransaction($this->request->get['customer_id'], $this->request->post['description'], $amount, $amount_national, $currency_code, (int)$this->request->post['order_id'], $send_sms, $date_added, 'manual_admin', $legalperson_id);
				
				if ($transaction_id){
					$this->data['success'] = 'Транзакция добавлена!';
					} else {
					$this->data['success'] = 'Что-то пошло не так!';
				}
				
				} else {
				$this->data['success'] = '';
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
				$this->data['error_warning'] = $this->language->get('error_permission');
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_balance'] = $this->language->get('text_balance');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_description'] = $this->language->get('column_description');
			$this->data['column_amount'] = $this->language->get('column_amount');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
			$this->load->model('setting/setting');
			$this->data['store_name'] = $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']);
			$this->data['store_currency'] = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$customer_info['store_id']);
			$this->data['transactions'] = array();
			
			$results = $this->model_sale_customer->getTransactions($this->request->get['customer_id'], ($page - 1) * 10, 10, $this->request->get['order_id']);
			
			foreach ($results as $result) {
				
				$amount_national = $this->currency->format($result['amount_national'], $this->data['store_currency'], 1);
				$amount_national_value = $result['amount_national'];
				
				$this->data['transactions'][] = array(
				'transaction_id' => $result['customer_transaction_id'],
				'amount_national' => $amount_national,
				'amount_national_value' => number_format($amount_national_value, 0, '',''),
				'sms_sent' 	  => ($result['sms_sent'])?"<span style='color:#4ea24e'>SMS отпр. ".date('d.m.Y в H:i', strtotime($result['sms_sent']))."<span/>":"<span style='color:#cf4a61;'>SMS не отправлено</span>",
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'date_added'  => date('d.m.Y', strtotime($result['date_added'])).'<br />'.date('H:i:s', strtotime($result['date_added'])),
				'added_from'  => $result['added_from'],
				'legalperson_id'  => $result['legalperson_id'],
				'legalperson_name'  => $result['legalperson_name'],
				'legalperson_name_1C'  => $result['legalperson_name_1C']
				);
			}
			
			$this->data['balance'] = $this->currency->format($this->model_sale_customer->getTransactionTotal($this->request->get['customer_id'], $this->request->get['order_id']), $this->config->get('config_currency'));
			
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
			$order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
			$order_total = $order_info['total_national'];
			
			
			
			$customer_balance_national = $this->model_sale_customer->getTransactionTotalNational($this->request->get['customer_id']);
			$balance_national = $this->model_sale_customer->getTransactionTotalNational($this->request->get['customer_id'], $this->request->get['order_id']);
			
			$this->data['notequal'] = ($customer_balance_national != $balance_national);
			
			$this->data['order_total'] = $this->currency->format($order_total, $this->data['store_currency'], 1);
			$this->data['customer_balance_national'] = 	$this->currency->format($customer_balance_national, $this->data['store_currency'], 1);
			$this->data['balance_national'] = $this->currency->format($balance_national, $this->data['store_currency'], 1);
			$this->data['ifplus'] = ($order_total - $balance_national) > 0;
			$this->data['current_difference'] = $this->currency->format($order_total - $balance_national, $this->data['store_currency'], 1);
			
			if (isset($this->request->post['send_transaction_sms']) && $this->request->post['send_transaction_sms'] && isset($this->request->post['transaction_sms_text']) && mb_strlen($this->request->post['transaction_sms_text'], 'UTF-8') > 2) {
				$options = array(
				'to'       => $order_info['telephone'],
				'from'     => $this->model_setting_setting->getKeySettingValue('config', 'config_sms_sign', (int)$order_info['store_id']),
				'message'  => $this->request->post['transaction_sms_text']
				);
				
				$sms_id = $this->smsQueue->queue($options);
				
				if ($sms_id){
					$sms_status = 'В очереди';
					} else {
					$sms_status = 'Неудача';
				}
				$sms_data = array(
				'order_status_id' => $order_info['order_status_id'],
				'sms' => $options['message']
				);
				
				$this->model_sale_order->addOrderSmsHistory((int)$this->request->post['order_id'], $sms_data, $sms_status, $sms_id);
			}
			
			$transaction_total = $this->model_sale_customer->getTotalTransactions($this->request->get['customer_id'], $this->request->get['order_id']);
			
			$this->data['has_rights'] = $this->user->canDoTransactions();
			
			$pagination = new Pagination();
			$pagination->total = $transaction_total;
			$pagination->page = $page;
			$pagination->limit = 10;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/customer/transactionorder', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->template = 'sale/order_transaction.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function callshistory(){
			$customer_id = $this->request->get['customer_id'];
			$this->load->model('sale/customer');
			$this->load->model('user/user');
			
			$results = $this->model_sale_customer->getCustomerCalls($customer_id);
			
			$this->data['user_calls'] = array();
			
			foreach ($results as $result){
				
				$this->data['user_calls'][] = array(
				'customer_call_id' => $result['customer_call_id'],
				'date_end'         => date('d.m.Y', strtotime($result['date_end'])).'<br />'.date('H:i:s', strtotime($result['date_end'])),
				'inbound'		   => $result['inbound'],
				'length'		   => $result['length'],
				'customer_id'      => $result['customer_id'],
				'manager_id'       => $result['manager_id'],
				'manager_sip_link' => $this->url->link('user/user_sip/history', 'user_id='.(int)$result['manager_id'].'&token='.$this->session->data['token']),
				'manager'          => $this->model_user_user->getRealUserNameById($result['manager_id']),
				'customer_name'    => $result['customer_name'],
				'customer_phone'   => $result['customer_phone'],
				'internal_pbx_num' => $result['internal_pbx_num'],
				'filename'         => str_replace(SIP_REMOTE_PATH, SIP_REPLACE_PATH, $result['filelink'])
				);
				
			}
			
			
			$this->template = 'sale/customer_calls.tpl';
			
			$this->response->setOutput($this->render());
			
		}
		
		public function reward() {
			$this->language->load('sale/customer');
			
			$this->load->model('sale/customer');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) {
				$this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points'], false, 'MANUAL');
				
				
				//$this->customer->addReward($this->request->get['customer_id'], 'ТЕСТ', -5010, 232814, 'ORDER_PAYMENT');
				
				$this->data['success'] = $this->language->get('text_success');
				} else {
				$this->data['success'] = '';
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
				$this->data['error_warning'] = $this->language->get('error_permission');
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_balance'] = $this->language->get('text_balance');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_description'] = $this->language->get('column_description');
			$this->data['column_points'] = $this->language->get('column_points');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$this->data['rewards_queue'] = array();
			$results = $this->model_sale_customer->getRewardsQueue($this->request->get['customer_id']);
			
			foreach ($results as $result) {
				$this->data['rewards_queue'][] = array(
				'points'      	=> $this->currency->formatBonus($result['points']),								
				'description' 	=> $result['description'],
				'reason_code' 	=> $result['reason_code'],
				'order_id' 	  	=> $result['order_id'],				
				'date_added'  	=> date('d.m.Y', strtotime($result['date_added'])),
				'date_activate'  	=> date('d.m.Y', strtotime($result['date_activate']))
				);
			}
			
			$this->data['rewards'] = array();
			
			$results = $this->model_sale_customer->getRewards($this->request->get['customer_id'], ($page - 1) * 50, 50);
			
			$this->load->model('setting/setting');
			$this->load->model('user/user');
			$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
			$config_reward_lifetime = $this->model_setting_setting->getKeySettingValue('config', 'config_reward_lifetime', (int)$customer_info['store_id']);
			
			foreach ($results as $result) {
				$this->data['rewards'][] = array(
				'points'      	=> $this->currency->formatBonus($result['points']),				
				'class'		  	=> ($result['points'] > 0)?'green':'red',
				'description' 	=> $result['description'],
				'reason_code' 	=> $result['reason_code'],
				'order_id' 	  	=> $result['order_id'],
				'user_id' 	  	=> $result['user_id'],
				'user' 	  		=> $result['user_id']?$this->model_user_user->getRealUserNameById($result['user_id']):'',
				'burned'		=> $result['burned'],
				'date_added'  	=> date('d.m.Y', strtotime($result['date_added'])),
				'time_added'  	=> date('H:i:s', strtotime($result['date_added'])),
				'points_paid' 	=> $result['points_paid']?$this->currency->formatBonus($result['points_paid']):false,
				'points_active' 	=> ($result['points'] > 0)?$this->currency->formatBonus($result['points'] - $result['points_paid']):false,			
				'date_paid'   		=> $result['points_paid']?date('d.m.Y', strtotime($result['date_paid'])):false,
				'date_inactivate'  	=> ($result['points'] > 0)?date('d.m.Y', strtotime($result['date_added'] . ' + ' . (int)$config_reward_lifetime . ' days')):false
				);
			}
			
			$this->data['balance'] = $this->currency->formatBonus($this->model_sale_customer->getRewardTotal($this->request->get['customer_id']));
			
			$reward_total = $this->model_sale_customer->getTotalRewards($this->request->get['customer_id']);
			
			$pagination = new Pagination();
			$pagination->total = $reward_total;
			$pagination->page = $page;
			$pagination->limit = 50;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/customer/reward', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->template = 'sale/customer_reward.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function addBanIP() {
			$this->language->load('sale/customer');
			
			$json = array();
			
			if (isset($this->request->post['ip'])) {
				if (!$this->user->hasPermission('modify', 'sale/customer')) {
					$json['error'] = $this->language->get('error_permission');
					} else {
					$this->load->model('sale/customer');
					
					$this->model_sale_customer->addBanIP($this->request->post['ip']);
					
					$json['success'] = $this->language->get('text_success');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function removeBanIP() {
			$this->language->load('sale/customer');
			
			$json = array();
			
			if (isset($this->request->post['ip'])) {
				if (!$this->user->hasPermission('modify', 'sale/customer')) {
					$json['error'] = $this->language->get('error_permission');
					} else {
					$this->load->model('sale/customer');
					
					$this->model_sale_customer->removeBanIP($this->request->post['ip']);
					
					$json['success'] = $this->language->get('text_success');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function autocomplete() {
			$json = array();
			
			if (isset($this->request->get['filter_name'])) {
				$this->load->model('sale/customer');
				
				$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
				);
				
				$results = $this->model_sale_customer->getCustomers($data);
				
				foreach ($results as $result) {
					$json[] = array(
					'customer_id'       => $result['customer_id'],
					'customer_group_id' => $result['customer_group_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'customer_group'    => $result['customer_group'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'email'             => $result['email'],
					'telephone'         => $result['telephone'],
					'fax'               => $result['fax'],
					'address'           => $this->model_sale_customer->getAddresses($result['customer_id'])
					);
				}
			}
			
			$sort_order = array();
			
			foreach ($json as $key => $value) {
				$sort_order[$key] = $value['name'];
			}
			
			array_multisort($sort_order, SORT_ASC, $json);
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function country() {
			$json = array();
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
			
			if ($country_info) {
				$this->load->model('localisation/zone');
				
				$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
				);
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function address() {
			$json = array();
			
			if (!empty($this->request->get['address_id'])) {
				$this->load->model('sale/customer');
				
				$json = $this->model_sale_customer->getAddress($this->request->get['address_id']);
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function updateCustomerFieldsAjax(){
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				die('no_permission');
			}
			
			
			$data = $this->request->post;
			
			if (isset($data['customer_id']) && isset($data['field']) && isset($data['value'])){
				$check_field_query = $this->db->query("SHOW COLUMNS FROM `customer` WHERE Field = '" . $this->db->escape($data['field']) . "'");
				if ($check_field_query->num_rows){
					$this->db->query("UPDATE `customer` SET `" . $this->db->escape($data['field']) . "` = '" .  $this->db->escape($data['value']) . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");
				}
				
				echo $this->db->escape($data['value']);				
			}
			
		}
		
		public function updateCumulativeDiscountPercent(){
			$this->load->model('sale/customer_group');
			$this->load->model('setting/store');
			$this->load->model('setting/setting');
			$this->load->model('localisation/currency');
			$customer_groups = $this->model_sale_customer_group->getCustomerGroups();
			$stores = $this->model_setting_store->getStores();
			
			$stores[] = array(
			'store_id' => 0
			);
			
			foreach ($stores as $store){
				$currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']);
				
				foreach ($customer_groups as $customer_group) {
					
					$sql = "SELECT
					d.discount_id,
					d.days,
					d.summ,
					d.currency,
					d.percent,
					d.products_special,
					d.first_order		
					FROM
					shoputils_cumulative_discounts d              
					LEFT JOIN
					shoputils_cumulative_discounts_to_store d2s ON (d.discount_id = d2s.discount_id)
					LEFT JOIN
					shoputils_cumulative_discounts_to_customer_group d2cg ON (d.discount_id = d2cg.discount_id)					
					WHERE  
					d2s.store_id= '" . (int)$store['store_id'] . "' AND
					d2cg.customer_group_id='" . (int)$customer_group['customer_group_id'] . "'
					ORDER BY 
					d.percent ASC";
					
					$discounts = $this->db->query($sql);
					
					if (count($discounts->rows)){
						foreach ($discounts->rows as $row){
							if ($row['currency'] == $currency){
								echo " >>> Пересчет скидок для магазина " . $store['store_id'] . " и группы " . $customer_group['customer_group_id']. "" . PHP_EOL;
								
								$update_query = "UPDATE customer SET discount_percent = '" . $row['percent'] . "', discount_id = '" . $row['discount_id'] . "' WHERE store_id = '" . (int)$store['store_id'] . "' AND total_product_cheque >= '" . (float)$row['summ'] . "'";
								echo " >>> $update_query" . PHP_EOL;
								$this->db->query($update_query);
							}							
						}
					}
				}
			}
			
			
			
		}
		
		public function updateGenders(){
			$this->load->model('sale/customer');
			//	$gender = $this->model_sale_customer->get_gender($firstname);
			
			//echo 'getting all customers' . PHP_EOL;
			$query = $this->db->query("SELECT customer_id, firstname, gender FROM customer WHERE (gender = 0 OR ISNULL(gender)) AND (LENGTH(firstname)) > 1 AND NOT (firstname LIKE('%&%') OR firstname LIKE('%@%'))");
			
			$t = count($query->rows);
			$i = 0;
			foreach ($query->rows as $customer){
				
				echo $i.' / '.$t.' ';
				$i++;
				
				$gender = $this->model_sale_customer->get_gender($customer['firstname']);
				
				$tr = new \Transliterator\Transliterator(\Transliterator\Settings::LANG_RU, \Transliterator\Settings::SYSTEM_Passport_2003);
				$customer['firstname'] = $tr->cyr2Lat($customer['firstname']);		

				echoLine($customer['firstname'] . ': ' . $gender);
				
				if ($gender == 'female' || $gender == 'mostly_female') {
					echo $customer['firstname'] . ' - woman' . PHP_EOL;
					$gender_id = 2;
					} elseif ($gender == 'male' || $gender == 'mostly_male') {
					echo $customer['firstname'] . ' - man' . PHP_EOL;
					$gender_id = 1;
					} else {
					echo $customer['firstname'] . ' - unknown' . PHP_EOL;
					$gender_id = 0;
				}
				
				if ($gender_id != $customer['gender']){
				//	$this->db->query("UPDATE customer SET gender = '" .(int)$gender_id. "' WHERE customer_id = '" . (int)$customer['customer_id'] . "'");
				}
				
			}			
		}
	}																	