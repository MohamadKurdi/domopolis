<?
	
	class ControllerCheckoutGetSP extends Controller {
		public function index() {
			$this->language->load('checkout/manual');
			
			
		}	
		public function getShippings(){
			$this->language->load('checkout/manual');
			$json = array();
			$this->load->library('user');
			$this->user = new User($this->registry);
			
			$this->load->model('setting/setting');
			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');
			$this->load->model('setting/extension');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
			
			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format = $country_info['address_format'];
				} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';	
				$address_format = '';
			}
			
			$zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);
			
			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
				} else {
				$zone = '';
				$zone_code = '';
			}					
			
			$address_data = array(
			'firstname'      	=> $this->request->post['shipping_firstname'],
			'lastname'       	=> $this->request->post['shipping_lastname'],
			'company'        	=> $this->request->post['shipping_company'],
			'address_1'      	=> $this->request->post['shipping_address_1'],
			'address_2'      	=> $this->request->post['shipping_address_2'],
			'postcode'       	=> $this->request->post['shipping_postcode'],
			'city'           	=> $this->request->post['shipping_city'],
			'zone_id'        	=> $this->request->post['shipping_zone_id'],
			'zone'           	=> $zone,
			'zone_code'      	=> $zone_code,
			'country_id'     	=> $this->request->post['shipping_country_id'],
			'country'        	=> $country,	
			'iso_code_2'     	=> $iso_code_2,
			'iso_code_3'     	=> $iso_code_3,
			'do_not_check_city'     => true,
			'bypass_point_status'   => true,
			'address_format' 		=> $address_format
			);
			
			$results = $this->model_setting_extension->getExtensions('shipping');
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);
					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
					
					if ($quote && $quote['quote']) {
						$json['shipping_method'][$result['code']] = array( 
						'title'      => $quote['title'],
						'quote'      => $quote['quote'], 
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
						);
					}
				}
			}
			
			$sort_order = array();
			
			foreach ($json['shipping_method'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}			

			if ($json['shipping_method']){
				array_multisort($sort_order, SORT_ASC, $json['shipping_method']);	
			}
			
			$this->response->setOutput(json_encode($json));	
		}
		
		public function getPayments(){
			$json = [];

			$this->language->load('checkout/manual');
			$this->load->library('user');		
			$this->user = new User($this->registry);			
			
			$this->load->model('setting/setting');
			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');
			$this->load->model('setting/extension');			
			
			$this->config->set('config_currency', $this->request->post['currency_code']);
			$this->config->set('config_store_id', $this->request->post['store_id']);
			
			$settings = $this->cache->get('settings.structure'.(int)$this->config->get('config_store_id'));
			if (!$settings) {	
				$query = $this->db->query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");
				
				$settings = $query->rows;
				$this->cache->set('settings.structure'.(int)$this->config->get('config_store_id'), $settings);
			}
			
			foreach ($settings as $setting) {
				if (!$setting['serialized']) {
					$this->config->set($setting['key'], $setting['value']);
					} else {
					$this->config->set($setting['key'], unserialize($setting['value']));
				}
			}
			
			$total 		= (float)$this->request->post['total_num'];
			$secondary 	= (int)$this->request->get['secondary'];
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);
			
			if ($country_info) {
				$country 		= $country_info['name'];
				$iso_code_2 	= $country_info['iso_code_2'];
				$iso_code_3 	= $country_info['iso_code_3'];
				$address_format = $country_info['address_format'];
				} else {
				$country 		= '';
				$iso_code_2 	= '';
				$iso_code_3 	= '';	
				$address_format = '';
			}
			
			$zone_info = $this->model_localisation_zone->getZone($this->request->post['payment_zone_id']);
			
			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
				} else {
				$zone = '';
				$zone_code = '';
			}					
			
			$address_data = array(
			'firstname'      => $this->request->post['payment_firstname'],
			'lastname'       => $this->request->post['payment_lastname'],
			'company'        => $this->request->post['payment_company'],
			'address_1'      => $this->request->post['payment_address_1'],
			'address_2'      => $this->request->post['payment_address_2'],
			'postcode'       => $this->request->post['payment_postcode'],
			'city'           => $this->request->post['payment_city'],
			'zone_id'        => $this->request->post['payment_zone_id'],
			'zone'           => $zone,
			'zone_code'      => $zone_code,
			'country_id'     => $this->request->post['payment_country_id'],
			'country'        => $country,	
			'iso_code_2'     => $iso_code_2,
			'iso_code_3'     => $iso_code_3,
			'address_format' => $address_format
			);
			
			$json['payment_method'] = array();
			
			$results = $this->model_setting_extension->getExtensions('payment');
			
			foreach ($results as $result) {													
				if ($this->config->get($result['code'] . '_status')) {					
					if ($secondary){
						$add = $this->config->get($result['code'] . '_ismethod');
						} else {
						$add = !$this->config->get($result['code'] . '_ismethod');
					}
				
					$this->load->model('payment/' . $result['code']);					
					$method = $this->{'model_payment_' . $result['code']}->getMethod($address_data, false, true); 	
										
					if ($method && $add) {											
						if (isset($method['quote'])) {
							foreach ($method['quote'] as $val) {
								$json['payment_method'][$val['code']] = $val;
							}
							} else {
							$json['payment_method'][$result['code']] = $method;
						}
					}
				}
			}
			
			$sort_order = array(); 
			
			foreach ($json['payment_method'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			
			array_multisort($sort_order, SORT_ASC, $json['payment_method']);	
			
			$this->response->setOutput(json_encode($json));	
		}
	}	