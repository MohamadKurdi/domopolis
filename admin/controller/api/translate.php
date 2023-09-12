<?
	
	class ControllerAPITranslate extends Controller {
		protected $error = array();
		
		public function ajax() {
			if (!$this->config->get('config_translate_api_enable')){
				return;
			}

			$text 		= $this->request->post['q'];
			$source 	= $this->request->post['source'];
			$target 	= $this->request->post['target'];
					
			$translated = $this->translateAdaptor->translate($text, $source, $target, true);			
			$translated = htmlspecialchars_decode($translated, ENT_QUOTES);	

			//BACKWARD COMPATIBILITY
			$json = json_encode([
				'translations' => [
					'0' => [
						'text' => $translated
					]
				]
			]);

			$this->response->setOutput($json);
		}				
	}																								