<?php
	class ControllerSaleCustomerManual extends Controller {
		private $manufacturers 	= [];
		private $collections 	= [];
		private $limit 			= 30;

		
		public function index() {
			$this->language->load('sale/customer');
			$this->load->model('sale/customer');
			$this->load->model('tool/image');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/collection');
			$this->load->model('setting/setting');
			
			$this->document->setTitle('Обзвон покупателей');

			$this->getList();

		}

		private function getManufacturer($manufacturer_id){
			if (!empty($this->manufacturers[$manufacturer_id])){
				return $this->manufacturers[$manufacturer_id];
			}

			$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

			if ($manufacturer){
				$manufacturer = [
					'name' 	=> $manufacturer['name'],
					'image' => $this->model_tool_image->resize($manufacturer['image'], 30, 30)
				];

				$this->manufacturers[$manufacturer_id] = $manufacturer;
			}

			return $manufacturer;
		}

		private function getCollection($collection_id){
			if (!empty($this->collections[$collection_id])){
				return $this->collections[$collection_id];
			}

			$collection = $this->model_catalog_collection->getCollection($collection_id);

			if ($collection){
				$collection = [
					'name' 	=> $collection['name'],
					'image' => $this->model_tool_image->resize($collection['image'], 30, 30)
				];

				$this->collections[$collection_id] = $collection;
			}

			return $collection;
		}

		protected function getList() {
			$this->data['heading_title'] = 'Обзвон покупателей';

			$filters = [
				'filter_order_good_count' 	=> 5,
				'order_good_last_date_to' 		=> date('Y-m-d', strtotime('-3 month')),
				'order_good_last_date_from' 	=> date('Y-m-d', strtotime('-15 month')),
				'filter_had_not_sent_manual_letter' => true, 
				'filter_simple_email' 			=> true,
				'filter_nbt_customer' 			=> false,
				'filter_nbt_customer_exclude' 	=> false,
				'filter_mail_status' 			=> 'delivered',
				'filter_phone'			 => '',
				'filter_name'			 => '',
				'filter_email'			 => '',
				'filter_exclude_customer_id' => YANDEX_MARKET_CUSTOMER_ID
			];

			$data = [];

			foreach ($filters as $filter => $default_value){
				if (isset($this->request->get[$filter])) {
					${$filter} = $this->request->get[$filter];
				} else {
					${$filter} = $default_value;
				}

				$this->data[$filter] = ${$filter};
				$data[$filter] = ${$filter};
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
			foreach ($filters as $filter => $default_value){
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . urlencode(html_entity_decode($this->request->get[$filter], ENT_QUOTES, 'UTF-8'));
				}
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

			$this->data['config_customer_manual_test_mode'] 	= $this->config->get('config_customer_manual_test_mode');
			$this->data['config_customer_manual_tracking_code'] = $this->config->get('config_customer_manual_tracking_code');

			$this->load->model('catalog/actiontemplate');
			$current_actiontemplate = $this->model_catalog_actiontemplate->getActionTemplates(['filter_use_for_manual' => true]);

			$this->data['actiontemplate_id'] 	= null;
			$this->data['actiontemplate_title'] = null;
			if (!empty($current_actiontemplate) && !empty($current_actiontemplate['actiontemplate_id'])){
				$this->data['actiontemplate_id'] 	= $current_actiontemplate['actiontemplate_id'];
				$this->data['actiontemplate_title'] = $current_actiontemplate['title'];

				$this->data['heading_title']		.= ' (' . $current_actiontemplate['title'] . ')';
			}

			$this->data['customers'] = [];

			$data['start'] = ($page - 1) * $this->limit;
			$data['limit'] = $this->limit;

			$customer_total = $this->model_sale_customer->getTotalCustomers($data);
			$results 		= $this->model_sale_customer->getCustomers($data);

			$this->load->model('localisation/country');
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			$countries = array();
			foreach ($this->data['countries'] as $country){
				$countries[$country['country_id']] = $country;
			}
			$this->data['countries'] = $countries;

			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
					'text' => '<i class="fa fa-edit"></i>',
					'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL')
				);

				$customer_manufacturers = [];
				$ordered_manufacturers = $this->model_sale_customer->getCustomerOrderManufacturers($result['customer_id']);			

				foreach ($ordered_manufacturers as $manufacturer_id){
					if ($manufacturer_id){						
						$customer_manufacturers[] = $this->getManufacturer($manufacturer_id);
					}
				}

				$customer_collections 	= [];
				$ordered_collections 	= $this->model_sale_customer->getCustomerOrderCollections($result['customer_id']);

				foreach ($ordered_collections as $collection){
					if ($collection){
						$manufacturer_name = $this->getManufacturer($collection['manufacturer_id'])['name'];

						if (empty($customer_collections[$manufacturer_name])){
							$customer_collections[$manufacturer_name] = [];
						}

						$customer_collections[$manufacturer_name][] = $this->getCollection($collection['collection_id']);
					}
				}

				$currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$result['store_id']);

				$this->data['customers'][] = array(
					'customer_id'    => $result['customer_id'],
					'store_id'       => $result['store_id'],
					'language_id'    => $result['language_id'],
					'name'           => $result['name'],
					'source'         => $result['source'],
					'total_cheque'   => $this->currency->format($result['total_cheque'], $currency, 1),
					'avg_cheque'     => $this->currency->format($result['avg_cheque'], $currency, 1),
					'order_count'    => $result['order_count'],
					'order_good_count'  => $result['order_good_count'],
					'country'        => $result['country_id']?$this->data['countries'][$result['country_id']]['iso_code_2']:false,
					'city'           => $result['city'],
					'email'          => $result['email'],
					'email_bad'		 => !$this->emailBlackList->check($result['email']),
					'phone'          => $result['telephone'],
					'fax'            => $result['fax'],
					'csi_reject'	 => $result['csi_reject'],
					'csi_average'	 => $result['csi_average'],
					'customer_group' => $result['customer_group'],
					'customer_comment' 		=> $result['customer_comment'],
					'sent_manual_letter' 	=> $result['sent_manual_letter'],
					'nbt_customer' 	 => $result['nbt_customer'],
					'rja_customer' 	 => $result['rja_customer'],
					'order_good_first_date' 		=> date('Y-m-d', strtotime($result['order_good_first_date'])),
					'order_good_first_date_diff'	=> formatDateInterval($result['order_good_first_date']),
					'order_good_last_date' 			=> date('Y-m-d', strtotime($result['order_good_last_date'])),
					'order_good_last_date_diff'		=> formatDateInterval($result['order_good_last_date']),
					'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'mail_status'    => $result['mail_status'],
					'mail_opened'    => $result['mail_opened'],
					'mail_clicked'   => $result['mail_clicked'],
					'manufacturers'	 => $customer_manufacturers,
					'collections'	 => $customer_collections,
					'preauth_url'    => $this->model_sale_customer->getCustomerPreauthLink($result['email'], $result['store_id']),
					'customer_href'  => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
					'action'         => $action
				);
			}

			$url = '';			
			foreach ($filters as $filter => $default_value){
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . urlencode(html_entity_decode($this->request->get[$filter], ENT_QUOTES, 'UTF-8'));
				}
			}						
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$pagination 			= new Pagination();
			$pagination->total 		= $customer_total;
			$pagination->page 		= $page;
			$pagination->limit 		= $this->limit;
			$pagination->text 		= $this->language->get('text_pagination');
			$pagination->url 		= $this->url->link('sale/customer_manual', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();

			$this->data['token'] = $this->session->data['token'];

			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
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

			$this->data['sort'] = $sort;
			$this->data['order'] = $order;


			$this->template = 'sale/customer_manual.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
	}