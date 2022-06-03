<?php
	
	class ControllerKPStat extends Controller {
		public function online(){
			$json = array();
			if ($this->config->get('config_customer_online')) {
				$this->load->model('tool/online');
				
				if (isset($this->request->server['REMOTE_ADDR'])) {
					$ip = $this->request->server['REMOTE_ADDR'];	
					} else {
					$ip = ''; 
				}
				
				if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
					$url = 'https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];	
					} else {
					$url = '';
				}
				
				if (isset($this->request->server['HTTP_REFERER'])) {
					$referer = $this->request->server['HTTP_REFERER'];	
					} else {
					$referer = '';
				}
				
				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$useragent = $this->request->server['HTTP_USER_AGENT'];	
					} else {
					$useragent = '';
				}
				
				$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer, $useragent);
				$json['success'] = true;
			}
			
			$this->response->setOutput(json_encode($json));
		}
	}
