<?
	
	class ControllerKPSparkPost extends Controller {	
		
		
		private $sparkPostEventTypes = ['message_event','track_event','unsubscribe_event'];
		
		private function validateAuthorization(){			
			
			if (!empty($this->request->server['PHP_AUTH_USER']) && 
			$this->request->server['PHP_AUTH_USER'] == $this->config->get('config_sparkpost_api_user') && 
			$this->request->server['PHP_AUTH_PW'] == $this->config->get('config_sparkpost_api_key')
			){
				return true;
				} else {
				header('WWW-Authenticate: Basic realm="SPARKPOST OPENCART API"');
				header('HTTP/1.0 401 Unauthorized');
				exit;
			}
			
		}
		
		private function parseSparkPostEvent($event){
			$result = [
			'type' 			=> '',
			'email' 		=> '',
			'customer_id'	=> ''
			];
			
			foreach ($this->sparkPostEventTypes as $eventType){
				
				if (isset($event['msys'][$eventType])){
					$result = [
					'type' 			=> $event['msys'][$eventType]['type'],
					'email' 		=> $event['msys'][$eventType]['rcpt_to'],
					'customer_id'	=> $this->customer->searchCustomer($event['msys'][$eventType]['rcpt_to'])
					];
					
					return $result;
				}
			}
			
			return false;
			
		}				
		
		public function api(){
			if (!$this->config->get('config_sparkpost_bounce_enable')){
				header('HTTP/1.0 400 Bad Request');
				die('webhook_disabled');
			}


			$this->validateAuthorization();
			
			$sparklog = new Log('sparkpost.txt');
			$inputJSON = json_decode(file_get_contents('php://input'), true);
			
			
			if (!is_array($inputJSON)){
				header('HTTP/1.0 400 Bad Request');
				die('no_input');
			}
			
			foreach ($inputJSON as $input) {
				if ($event = $this->parseSparkPostEvent($input)){
					
					$sparklog->write($event['customer_id'] . ' ' . $event['email'] . ' ' . $event['type']);
					
					//BOUNCE, плохой мейл, в черный список
					if ($event['type'] == 'bounce'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
						$this->emailBlackList->blacklist($event['email'], $event['type']);
						$this->customer->clearSubscriptions($event['customer_id']);
					}
					
					//Спам, плохой мейл, в черный список
					if ($event['type'] == 'spam_complaint'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
						$this->emailBlackList->blacklist($event['email'], $event['type']);
						$this->customer->clearSubscriptions($event['customer_id']);
					}
					
					//Спам, плохой мейл, в черный список
					if ($event['type'] == 'out_of_band'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
						$this->emailBlackList->blacklist($event['email'], $event['type']);
						$this->customer->clearSubscriptions($event['customer_id']);
					}
					
					//Спам, плохой мейл, в черный список
					if ($event['type'] == 'policy_rejection'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
						$this->emailBlackList->blacklist($event['email'], $event['type']);
						$this->customer->clearSubscriptions($event['customer_id']);
					}
					
					//Отписка
					if ($event['type'] == 'list_unsubscribe'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
						$this->emailBlackList->blacklist($event['email'], $event['type']);
						$this->customer->clearSubscriptions($event['customer_id']);
					}
					
					if ($event['type'] == 'link_unsubscribe'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
						$this->emailBlackList->blacklist($event['email'], $event['type']);
						$this->customer->clearSubscriptions($event['customer_id']);
					}
					
					
					//Отправлено 
					if ($event['type'] == 'injection'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
					}
					
					//Задержка 
					if ($event['type'] == 'delay'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
					}
					
					//Доставлено 
					if ($event['type'] == 'delivery'){
						$this->customer->updateMailStatus($event['customer_id'], $event['type']);
						$this->emailBlackList->whitelist($event['email'], $event['type']);
					}
					
					
					//События клика и открытия
					if ($event['type'] == 'click'){
						$this->customer->updateClick($event['customer_id']);
						$this->customer->updateMailStatus($event['customer_id'], 'delivery');
					}
					
					if ($event['type'] == 'open'){
						$this->customer->updateOpen($event['customer_id']);
						$this->customer->updateMailStatus($event['customer_id'], 'delivery');
					}
					
					
				}
			}
		}
	}								