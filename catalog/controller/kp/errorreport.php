<?php
	
	class ControllerKPErrorReport extends Controller {
		
		public function error(){
			echoLine('Такого контроллера не существует!');			
		}
		
		public function test(){
			
			var_dump($this->url->link('checkout/success'));
		
			var_dump($this->url->link('payment/liqpay/server'));
		}
		
		public function write(){
			$this->language->load('product/product');
			$this->load->model('kp/bitrixBot');	
			
			
			$json = array();
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$this->request->post = $this->shortcodes->strip_shortcodes($this->request->post);
				
				
			/*	if ((utf8_strlen($name = $this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
					//$json['error'] = 'Пожалуйста, представьтесь';
				}
				
				if ((utf8_strlen($telephone = $this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 25)) {
					//$json['error'] = 'Пожалуйста, укажите номер телефона для обратной связи';
				}
			*/
				
				if ((utf8_strlen($text = $this->request->post['text']) < 3) || (utf8_strlen($this->request->post['text']) > 1000)) {
					$json['error'] = 'Пожалуйста, расскажите нам, в чем проблема';
				}
				
				if ($this->customer->isLogged()){
					$name = $this->customer->getFirstName();
					$customer_id = $this->customer->getId();
					$telephone = $this->customer->getTelephone();	
				}
				
				if (!empty($this->session->data['simple'])) {
					$name = $this->session->data['simple']['firstname'];
					$telephone = $this->session->data['simple']['telephone'];
				} 
				
				if (!empty($this->session->data['guest'])){
					$name = $this->session->data['guest']['firstname'];
					$telephone = $this->session->data['guest']['telephone'];
				}

				$session = json_encode($this->session->data);
				
				if (!isset($json['error'])) {
					$json['success'] = 'Спасибо за ваше сообщение. Мы все исправим.';
					
					$this->model_kp_bitrixBot->sendMessage($message = ':!: [B] Сообщение об ошибке с сайта[/B]', 
					$attach = Array(
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					Array(
					'MESSAGE' =>  "
					[B]Айди:[/B] $customer_id,
					[B]Текст:[/B] $text,
					[B]Сессия:[/B] $session",
					'COLOR' => '#FF0000',
					),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					Array(
					'MESSAGE' =>  "[B]IP:[/B] " . $_SERVER['REMOTE_ADDR'] . "
					[B]UA:[/B] " . $_SERVER['HTTP_USER_AGENT'] . "
					[B]Страница:[/B] " . $_SERVER['HTTP_REFERER'] . "
					",
					'COLOR' => '#FF0000',
					),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					), 
					'chat69010');
					
					$this->model_kp_bitrixBot->sendMessage($message = ':!: [B] Сообщение об ошибке с сайта[/B]', 
					$attach = Array(
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					Array(
					'MESSAGE' =>  "
					[B]Имя:[/B] ". $this->session->data['simple']['customer']['firstname'] ."
					[B]Customer ID:[/B]" .  $customer_id . "
					[B]Телефон 2:[/B]" .  $this->session->data['simple']['customer']['telephone'] . "
					[B]Город:[/B]" .  $this->session->data['simple']['customer']['city'] . "
					[B]Сообщение:[/B] $text",
					'COLOR' => '#FF0000',
					),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					Array(
					'MESSAGE' =>  "[B]IP:[/B] " . $_SERVER['REMOTE_ADDR'] . "
					[B]UA:[/B] " . $_SERVER['HTTP_USER_AGENT'] . "
					[B]Страница:[/B] " . $_SERVER['HTTP_REFERER'] . "
					",
					'COLOR' => '#FF0000',
					),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					), 
					'chat9667');
				}
				
			}
			
			
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
	
