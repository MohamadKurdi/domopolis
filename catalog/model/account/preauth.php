<?
	class ModelAccountPreauth extends Model {
		
		public function CheckPreauth(){
			
			if (isset($this->request->get['utoken'])){			
				$this->load->model('account/customer');
								
				if (!empty($this->request->get['utm_term']) && is_array($this->request->get['utm_term'])){
					$this->request->get['utm_term'] = '';
				}
				
				if (is_array($this->request->get['utoken'])){
					$this->request->get['utoken'] = '';
				}
				
				if (!empty($this->request->get['order_id']) && is_array($this->request->get['order_id'])){
					$this->request->get['order_id'] = '';
				}

				if (empty($this->request->get['order_id']) && !empty($this->request->get['customer_id'])){
					if ($this->model_account_customer->validateUtokenForCustomerID($this->request->get['customer_id'], trim($this->request->get['utoken']))){	
						return (int)$this->request->get['customer_id'];
					}
				}
				
				if (!empty($this->request->get['order_id']) && $customer_id = $this->model_account_customer->getCustomerIDFromOrderID($this->request->get['order_id'])){			
					if ($this->model_account_customer->validateUtokenForCustomerID($customer_id, trim($this->request->get['utoken']))){					
						return $customer_id;
					}
				}

				$customer_info = $this->model_account_customer->getCustomerByUtoken($this->request->get['utoken']);		
				
				if ($customer_info){				
					$email = trim($customer_info['email']);

					if (md5($email.$this->config->get('config_encryption')) == $this->request->get['utoken']){	
						return $customer_info['email'];					
					}
					
					} else {

					return false;				
				}
				
				} else {
				return false;			
			}	
		}				
	}