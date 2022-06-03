<?php
	class ControllerModuleDiscountOnLeave extends Controller  {
		// Module Unifier
		private $moduleName = 'DiscountOnLeave';
		private $moduleNameSmall = 'discountonleave';
		private $moduleData_module = 'discountonleave_module';
		private $moduleModel = 'model_module_discountonleave';
		
		// Module Unifier
		
		public function index($setting) {
			
			$this->data['url'] = preg_replace('/https?\:/', '', $this->url->link("module/".$this->moduleNameSmall."/getPopup"));
			$this->load->model('module/discountonleave');
			$this->data['data'] = $this->config->get('DiscountOnLeave');
			
		//	$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template').'/stylesheet/'.$this->moduleNameSmall.'/animate.css');
			
			
			$this->data['customCSS'] = "";
			if(isset($this->data['data']['DiscountOnLeave']['DiscountOnLeave'])) {
				foreach($this->data['data']['DiscountOnLeave']['DiscountOnLeave'] as $popup) {
					$this->data['customCSS'].="\n".$popup['custom_css'];
				}
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/' . $this->moduleNameSmall . '.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/' . $this->moduleNameSmall . '.tpl';
				} else {
				$this->template = 'default/template/module/' . $this->moduleNameSmall . '.tpl';
			}
			
			$this->render();
		}
		
		protected function showPopup($popup_id)
		{			
			if (!isset($this->session->data['discountonleave_repeat']) || !in_array($popup_id, $this->session->data['discountonleave_repeat'])) {
				$this->session->data['discountonleave_repeat'][] = $popup_id;
				return true;
				} else {
				return false;
			}	
		}
		
		public function cookieCheck($days, $popup_id) {
			if(!isset($_COOKIE["discountonleave_popup".$popup_id])) {
				setcookie("discountonleave_popup".$popup_id,true, time()+3600*24*$days);
				return true;
				} else {
				return false;
			}
		}
		
		private function checkRepeatConditions($popup) {
			
			if ($popup['repeat']==0){
				//показывать всегда (навряд-ли это кто-то использует)
				return true;
				
				} elseif ($popup['repeat']==1) {
				//показывать раз за сессию (сверяем просмотренную страницу)			
				
				if ($popup['mouseout'] == 0) {			
					return $this->showPopup($popup['id'], $popup['repeat']) && $this->checkNumPageViewed($popup['num_page'], 2);			
					} elseif ($popup['mouseout'] == 1){
					return $this->showPopup($popup['id'], $popup['repeat']) && $this->checkNumPageViewed($popup['num_page'], 0);	
				}
				
				} elseif ($popup['repeat']==2){			
				//Раз в Х дней (сверяем куку прошедших дней и номер просмотренной страницы)						
				if ($popup['mouseout'] == 0) {				
					if ($this->checkNumPageViewed($popup['num_page'], 2)){						
						return $this->cookieCheck($popup['days'], $popup['id']);
						} else {
						return false;
					}				
					} elseif ($popup['mouseout'] == 1){					
					if ($this->checkNumPageViewed($popup['num_page'], 0)){	
						if (isset($_GET['discount_debug'])){
							return true;
						}
						return $this->cookieCheck($popup['days'], $popup['id']);
						} else {
						return false;
					}	
				}
				
				} elseif ($popup['repeat']==3){
				//Раз в Эн Страниц за сессию (сверяем кратность просмотренных страниц)
				return $this->checkNumPageViewed($popup['num_page'], 1);
				
			}		
		}
		
		private function checkShowToThisUser($popup){
			//показывать залогиненным
			if ($popup['show_to_logged']){
				//если векл. настройка ТОЛЬКО ЗАЛОГИНЕННЫМ
				if ($popup['only_show_to_logged'] == 'y'){
					return $this->customer->isLogged();
					} else {
					//А иначе на вообще пофиг, показываем всем
					return true;
				}						
				//не показывать залогиненным	
				} else {
				if ($this->customer->isLogged()){
					//не показываем, если залогинен
					return false;
					} else {
					//если не залогинен - показываем
					return true;
				}
			}			
		}
		
		private function checkNumPageViewed($pages, $equal = 0){
											
			if (isset($this->request->get['discount_debug'])){
				$this->session->data['pages_viewed'] = (int)$pages;			
			}
			
			//проверка совпадения количества страниц или 	
			if (!isset($this->session->data['pages_viewed'])){
				$this->session->data['pages_viewed'] = 1;
			}
			
			if ($equal == 0){
				//проверить равенство проcмотренных страниц с параметром
				return ($this->session->data['pages_viewed'] == $pages);
				} elseif ($equal == 1) {
				//проверить целочисленное деление - кратность кол-ва просмотренных страниц параметру
				return (($this->session->data['pages_viewed'] % $pages) == 0);
				} elseif ($equal == 2) {
				return ($this->session->data['pages_viewed'] > $pages);
			}
		}
		
		public function getPopup(){
			if(!isset($this->session->data['popups_repeat']))
			$this->session->data['popups_repeat'] = array();
			$json = array(
			'match' => false,
			'title' => "",
			'content' => "",
			'width' => "",
			'height' => "",
			'position' => "",
			'preventclose' => "",
			'delay' => "",
			'mouseout' => ""
			);
			
			
			$this->load->model('module/discountonleave');
			$this->data['data'] = $this->config->get('DiscountOnLeave');
			
			if (isset($_GET['uri'])) { $uri=$_GET['uri']; } else { $uri=""; }
			
			$uri = htmlspecialchars_decode((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].$uri);
			
			foreach ($this->data['data']['DiscountOnLeave'] as $popup) {			
				if($popup['status']=="1" && $this->checkShowToThisUser($popup)) {
					
					if($popup['method'] == "0") {	
						$parsedURI = parse_url($uri);	
						if((isset($parsedURI['query']) && $parsedURI['query'] == 'route=common/home') ||  (!isset($parsedURI['query']) && $parsedURI['path']=='/')) {
							if($this->checkRepeatConditions($popup)) {
								$json['match'] = true;
								$json['title'] = $popup['title'][$this->config->get('config_language_id')];
								$json['content'] = html_entity_decode($popup['content'][$this->config->get('config_language_id')]);
								$json['width'] = $popup['width'];
								$json['height'] = $popup['height'];
								$json['position'] = $popup['position'];
								$json['preventclose'] = $popup['preventclose'];
								$json['custom_css'] = $popup['custom_css'];
								$json['popup_id'] = $popup['id'];
								$json['delay'] = $popup['delay'];
								$json['mouseout'] = $popup['mouseout'];
							}	
						}
					}		
					
					if($popup['method'] == "1") {	
						$excludedURLs = array();
						$excludedURLs = array_map("urldecode",preg_split("/\\r\\n|\\r|\\n/", html_entity_decode($popup['excluded_urls'])));																									
						if(!in_array($uri,$excludedURLs)) {				
							if($this->checkRepeatConditions($popup)) {
								$json['match'] = true;
								$json['title'] = $popup['title'][$this->config->get('config_language_id')];
								$json['content'] = html_entity_decode($popup['content'][$this->config->get('config_language_id')]);
								$json['width'] = $popup['width'];
								$json['height'] = $popup['height'];
								$json['position'] = $popup['position'];
								$json['preventclose'] = $popup['preventclose'];
								$json['custom_css'] = $popup['custom_css'];
								$json['popup_id'] = $popup['id'];
								$json['delay'] = $popup['delay'];
								$json['mouseout'] = $popup['mouseout'];
							}	
						}
					}
					
					if($popup['method'] == "2") {	
						$URLs = array();
						$URLs = array_map("urldecode",preg_split("/\\r\\n|\\r|\\n/", html_entity_decode($popup['url'])));
						foreach($URLs as $url) {
							if(!empty($url) && strpos($uri, $url) !== false) {
								if($this->checkRepeatConditions($popup)) {
									$json['match'] = true;
									$json['title'] = $popup['title'][$this->config->get('config_language_id')];
									$json['content'] = html_entity_decode($popup['content'][$this->config->get('config_language_id')]);
									$json['width'] = $popup['width'];
									$json['height'] = $popup['height'];
									$json['position'] = $popup['position'];
									$json['preventclose'] = $popup['preventclose'];
									$json['custom_css'] = $popup['custom_css'];
									$json['popup_id'] = $popup['id'];
									$json['delay'] = $popup['delay'];
									$json['mouseout'] = $popup['mouseout'];
								}	
							}
						}
					}
					
					
				}
			}
			$this->response->setOutput(json_encode($json));		
		}
		
		
	}
?>