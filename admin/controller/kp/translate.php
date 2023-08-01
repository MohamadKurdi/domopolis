<?
	
	class ControllerKPTranslate extends Controller {
		protected $error = array();
		
		public function ajax() {
			if (!$this->config->get('config_translate_api_enable')){
				return;
			}

			$q 	= $this->request->post['q'];
			$source 	= $this->request->post['source'];
			$target 	= $this->request->post['target'];
			
			$this->load->model('kp/translate');
			
			$translated = $this->model_kp_translate->translate($q, $source, $target);
			
			$json = json_decode($translated, true);
			if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){
				$json['translations'][0]['text'] = htmlspecialchars_decode($json['translations'][0]['text'], ENT_QUOTES);
			}
			$translated = json_encode($json);
			
			$this->response->setOutput($translated);
		}				
	}																								