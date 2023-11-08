<?php
	class ControllerModuleTextandheadings extends Controller {
		// module: Текст и Заголовки!
		// version: 0.1 Comercial
		// autor: Шуляк Роман   roma78sha@gmail.com   www.opencartforum.com/index.php?app=core&module=search&do=user_activity&search_app=downloads&mid=678008
		private $error = array(); 
		
		public function index() {   
			$this->language->load('module/textandheadings');
			
			$this->document->setTitle($this->language->get('heading_title'));
			$this->document->addStyle('view/stylesheet/textandheadings.css');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
				if(isset($this->request->post['com'])){
					$com = $this->request->post['com'];
					foreach ($com as $langname => $lang){
						foreach ($lang as $filename => $file){
							foreach ($file as $varname => $var){
								$href = DIR_CATALOG."language/".$langname."/".$filename.".php";
								// var_dump($href); var_dump($varname); 
								
								// 1- название переменной, например heading_title
								// 2- значение
								// 3- путь к файлу
								tahChangeTitle($varname, $var, $href);
							} 
						}
					}
				}
				
				if(isset($this->request->post['var'])){
					$postvar = $this->request->post['var'];
					foreach ($postvar as $lang => $var){ // var_dump($var);
						foreach ($var as $dirname => $dir){ // var_dump($dir);
							foreach ($dir as $filename => $file){
								foreach ($file as $vname => $v){
									$href = DIR_CATALOG."language/".$lang."/".$dirname."/".$filename.".php"; 
									// var_dump($href);var_dump($vname);
									
									// 1- название переменной, например heading_title
									// 2- значение
									// 3- путь к файлу
									tahChangeTitle($vname, $v, $href);
								}
							}
						}
					}
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('module/textandheadings', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['text_common'] = $this->language->get('text_common');
			$this->data['text_infoclose'] = $this->language->get('text_infoclose');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['dimension'])) {
				$this->data['error_dimension'] = $this->error['dimension'];
				} else {
				$this->data['error_dimension'] = array();
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/textandheadings', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
			);
			
			$this->data['action'] = $this->url->link('module/textandheadings', 'token=' . $this->session->data['token']);
			
			$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
			
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();
			$this->data['languages'] = $languages; // var_dump($languages);
			
			$languageArrey = array();
			
			foreach ($languages as $keys1 => $lang){   // var_dump($lang);
				
				$langfile = DIR_CATALOG . 'language/' . $lang["directory"] . '/' . $lang["filename"] . '.php'; // var_dump($langfile);
				$langfileCom[$lang["directory"]]["filename"] = $lang["filename"];
				$langfileCom[$lang["directory"]]["var"] = languageLoad($langfile);
				
				$langname = $lang["directory"];
				
				$dir = glob(DIR_CATALOG . 'language/' . $lang["directory"] . '/*', GLOB_ONLYDIR); // var_dump($dir, "+++");
				
				foreach ($dir as $keys2 => $indir){ // var_dump($indir);
					$files = glob($indir . '/*.php'); // var_dump($files);
					$dirname = basename($indir);  // var_dump($dirname, '111');
					
					foreach ($files as $keys3 => $file){ // var_dump($file);
						
						$filename = basename($file, '.php'); // var_dump($filename);
						
						$contents = languageLoad($file); // var_dump($contents);
						
						// var_dump($langname, $dirname, $filename);
						$languageArrey[$langname][$dirname][$filename] = $contents;
						
					}
				}
			} // var_dump($languageArrey);
			
			$this->data['languageArrey'] = $languageArrey;
			$this->data['langfileCom'] = $langfileCom; // var_dump($this->data['langfileCom']); 
			
			$this->template = 'module/textandheadings.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validate() {
			if (!$this->user->hasPermission('modify', 'module/banner')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (isset($this->request->post['banner_module'])) {
				foreach ($this->request->post['banner_module'] as $key => $value) {
					if (!$value['width'] || !$value['height']) {
						$this->error['dimension'][$key] = $this->language->get('error_dimension');
					}			
				}
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}	
		}
		
	}
	function languageLoad($file = ""){
		if (file_exists($file)) {
			$_ = array();
			
			include $file;
			
			return $_;
			} else {
			trigger_error('Error: Could not load language ' . $file . '!');
			//	exit();
		}
	}
	
	/* ========================================================================================== */
	/* Замена                                                                                     */
	/* ========================================================================================== */ 
	function tahChangeTitle($for_edit = "", $v = "", $hr = ""){
		
		// искомая строка
		// $for_edit="heading_title";
		
		// на эту меняем
		$replace = "\$_['".$for_edit."'] = '" . $v . "';";
		$fopen=@file($hr);
		
		foreach($fopen as $key=>$value){
			if(substr_count($value,$for_edit)){
				
				// добавка
				$replace .= " // " . $value; // str_replace(" ","",$value);
				
				$fopen[$key] = $replace.PHP_EOL;
				file_put_contents($hr, join('', $fopen));
			}
		}
		
	}