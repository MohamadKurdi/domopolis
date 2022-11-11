<?php
	
	
	class ControllerKPPWA extends Controller {
		
		
		public function index(){
		}			
		
		public function logged(){	
			
			$this->response->setOutput(json_encode(array('logged' => $this->customer->isLogged())));
		
		}
		
		public function keys(){			
			$this->data['pwaInstallKey'] = $this->session->data['pwaInstallKey'] = md5('pwainstall' . $this->request->server['REMOTE_ADDR'] . date('d') . $this->config->get('config_encryption'));
			$this->data['pwaSessionKey'] = $this->session->data['pwaSessionKey'] = md5('pwasession' . $this->request->server['REMOTE_ADDR'] . date('d') . $this->config->get('config_encryption'));
			
			$this->response->setOutput(json_encode(
				array(
					'pwaInstallKey' => $this->session->data['pwaInstallKey'],
					'pwaSessionKey' => $this->session->data['pwaSessionKey']
				)
			));
		}
		
		public function sps(){
			
			if (!empty($this->session->data['pwaSessionKey'])){
				if ($this->validateKey($this->session->data['pwaSessionKey'], 'pwasession')){
					
					if (!$this->customer->getPWASession()){
						$this->customer->setPWASession();
						$this->updateCounter('pwasession');
					}
					
					if ($this->customer->isLogged() && $this->config->get('rewardpoints_appinstall')){
						$this->customer->addApplicationReward($this->customer->isLogged(), $description = $this->language->get('text_bonus_application'), $points = $this->config->get('rewardpoints_appinstall'), $order_id = 0, $reason_code = 'APPINSTALL_POINTS_ADD');
					}
					
					$this->response->setOutput('ok');
					
					} else {
					$this->response->setOutput('fail, checksum');
				}
				} else {
				$this->response->setOutput('fail, no key');
			}
			
		}
		
		public function spi(){
			if (!empty($this->session->data['pwaInstallKey'])){
				if ($this->validateKey($this->session->data['pwaInstallKey'], 'pwainstall')){
					$this->response->setOutput('ok');
					
					$this->updateCounter('pwainstall');
					
					} else {
					$this->response->setOutput('fail, checksum');
				}
				} else {
				$this->response->setOutput('fail, no key');
			}		
		}
		
		
		private function validateKey($key, $type){
			return ($key == md5($type . $this->request->server['REMOTE_ADDR'] . date('d') . $this->config->get('config_encryption')));
		}
		
		private function updateCounter($counter){
			$this->db->query("UPDATE counters SET value = value+1 WHERE store_id = '" .(int)$this->config->get('config_store_id'). "' AND counter = '" . $this->db->escape($counter) . "'");
		}
	}				