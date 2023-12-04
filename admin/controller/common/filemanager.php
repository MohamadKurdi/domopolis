<?php
class ControllerCommonFileManager extends Controller {
	private $error = array();

	public function index() {
		$this->data 			= $this->language->load('common/filemanager');
		$this->data['title'] 	= $this->language->get('heading_title');

		if (IS_HTTPS) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['token'] 		= $this->session->data['token'];
		$this->data['directory'] 	= HTTPS_CATALOG . 'image/data/';

		$this->load->model('tool/image');

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->get['field'])) {
			$this->data['field'] = $this->request->get['field'];
		} else {
			$this->data['field'] = '';
		}

		if (isset($this->request->get['CKEditorFuncNum'])) {
			$this->data['fckeditor'] = $this->request->get['CKEditorFuncNum'];
		} else {
			$this->data['fckeditor'] = false;
		}

		if ($this->config->get('pim_status')) {
			$this->data['lang'] = 'en';
			if ($this->config->get('pim_language')) {
				$this->data['lang'] = $this->config->get('pim_language');
			}
			$this->template = 'common/pim.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function connector() {
		include_once DIR_SYSTEM.'library/filemanager/elFinderConnector.class.php';
		include_once DIR_SYSTEM.'library/filemanager/elFinder.class.php';
		include_once DIR_SYSTEM.'library/filemanager/elFinderVolumeDriver.class.php';
		include_once DIR_SYSTEM.'library/filemanager/elFinderVolumeLocalFileSystem.class.php';
		
		$this->config->set('config_error_display', 0);       
		$this->config->set('config_error_log', 0);
		function access($attr, $path, $data, $volume) {
			return strpos(basename($path), '.') === 0
			? !($attr == 'read' || $attr == 'write')
			:  null;
		}

		$opts = array(
			'debug' => true,
			'roots' => [
				[
					'driver'        => 'LocalFileSystem',
					'path'          => DIR_IMAGE . 'data/',
					'URL'           => HTTPS_CATALOG . DIR_IMAGE_NAME . 'data/',
					'accessControl' => 'access',                    
					'fileMode'     	=> 0775, 
					'dirMode'      	=> 0775,    
					'tmbPath'       => DIR_IMAGE . 'tmb/',			
					'tmbURL'        => HTTPS_CATALOG . DIR_IMAGE_NAME .'tmb/',
					'tmbBgColor'    => 'transparent',
					'tmbCrop'       => 'false', 
					'copyOverwrite' 	=> $this->config->get('pim_copyOverwrite'),
					'uploadOverwrite' 	=> $this->config->get('pim_uploadOverwrite'),
					'uploadMaxSize'  	=> ''.$this->config->get('pim_uploadMaxSize').'M',      			
				]
			]
		);
		$connector = new elFinderConnector(new elFinder($opts));
		$connector->run();
	}            

	public function image() {
		$this->load->model('tool/image');

		if (isset($this->request->get['image'])) {
			if (isset($this->request->get['size'])) {
				$this->response->setOutput($this->model_tool_image->resize(html_entity_decode($this->request->get['image'], ENT_QUOTES, 'UTF-8'), (int)$this->request->get['size'], (int)$this->request->get['size']));
			} else {
				$this->response->setOutput($this->model_tool_image->resize(html_entity_decode($this->request->get['image'], ENT_QUOTES, 'UTF-8'), 100, 100));
			}
		}
	}      
}