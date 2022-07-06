<?
	
	class ControllerKPMailGun extends Controller {	


		private function validateAuthorization($data){	
		
			if (\abs(\time() - $data['timestamp']) > 15) {
				return false;
			}
			
			// returns true if signature is valid
			if ( \hash_equals(\hash_hmac('sha256', $data['timestamp'] . $data['token'], $this->config->get('config_mailgun_api_signing_key')), $data['signature'])){
			return true;
				} else {
				header('HTTP/1.0 401 Unauthorized');
				exit;
			}
			
		}
		
		
		
		private function parseMailGunEvent($event){
			$result = [
			'type' 			=> '',
			'email' 		=> '',
			'customer_id'	=> ''
			];
			
			if (isset($event['event-data'])){
				$result = [
				'type' 			=> $event['event-data']['event'],
				'email' 		=> $event['event-data']['recipient'],
				'customer_id'	=> $this->customer->searchCustomer($event['event-data']['recipient'])
				];
				
				return $result;
			}
			
			return false;
			
		}
		
		
		
		public function api(){
			if (!$this->config->get('config_mailgun_bounce_enable')){
				header('HTTP/1.0 400 Bad Request');
				die('webhook_disabled');
			}
			
			$mailgunlog = new Log('mailgun.txt');
			
			$inputJSON = json_decode(file_get_contents('php://input'), true);
		
			if (!is_array($inputJSON)){
				header('HTTP/1.0 400 Bad Request');
				die('no_input');
			}
			
			$this->validateAuthorization($inputJSON['signature']);

			if ($event = $this->parseMailGunEvent($inputJSON)){

				$mailgunlog->write($event['customer_id'] . ' ' . $event['email'] . ' ' . $event['type']);
				
				//BOUNCE, плохой мейл, в черный список
				if ($event['type'] == 'failed'){
					$this->customer->updateMailStatus($event['customer_id'], $event['type']);
					$this->emailBlackList->blacklist($event['email'], $event['type']);
					$this->customer->clearSubscriptions($event['customer_id']);
					
					echo $event['type'];
				}
				
				//Спам, плохой мейл, в черный список
				if ($event['type'] == 'complained'){
					$this->customer->updateMailStatus($event['customer_id'], $event['type']);
					$this->emailBlackList->blacklist($event['email'], $event['type']);
					$this->customer->clearSubscriptions($event['customer_id']);
					
					echo $event['type'];
				}
				
				//Отписка
				if ($event['type'] == 'unsubscribed'){
					$this->customer->updateMailStatus($event['customer_id'], $event['type']);
					$this->emailBlackList->blacklist($event['email'], $event['type']);
					$this->customer->clearSubscriptions($event['customer_id']);
					
					echo $event['type'];
				}
				
				//Доставлено 
				if ($event['type'] == 'delivered'){
					$this->customer->updateMailStatus($event['customer_id'], $event['type']);
					$this->emailBlackList->whitelist($event['email'], $event['type']);
					
					echo $event['type'];
				}
				
				
				//События клика и открытия
				if ($event['type'] == 'clicked'){
					$this->customer->updateClick($event['customer_id']);
					$this->customer->updateMailStatus($event['customer_id'], 'delivery');
					
					echo $event['type'];
				}
				
				if ($event['type'] == 'opened'){
					$this->customer->updateOpen($event['customer_id']);
					$this->customer->updateMailStatus($event['customer_id'], 'delivery');
					
					echo $event['type'];
				}
				
			}
		}
	}												