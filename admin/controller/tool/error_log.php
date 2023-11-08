<?php 
class ControllerToolErrorLog extends Controller { 
	private $error = array();

	public function index() {		
		$this->language->load('tool/error_log');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_clear'] = $this->language->get('button_clear');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),       		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/error_log', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
		);
		
		$dir = DIR_LOGS;
		$tmp_files = scandir($dir);
		
		$this->data['files'] = array();
		
		foreach ($tmp_files as $text){
			if (!is_dir($dir . $text) && ($text != '.') && ($text != '..') && ($text != 'index.php') && ($text != 'index.html') && ($text != '.htaccess')){
				$key = explode('_', $text);
				$key = $key[0];
				$this->data['files'][$key][] = $text;
			}
		}

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'tool/error_log.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function loadtext(){
		$dir = DIR_LOGS;

		$file = $this->db->escape(str_replace('..','',$this->request->post['tpl_file']));
		
		if (file_exists($dir . $file)){
			echo file_get_contents($dir . $file);
		} else {
			echo '';
		}
		
	}
	
	public function clearlog(){
		$dir = DIR_LOGS;

		$file = $this->db->escape(str_replace('..','',$this->request->post['tpl_file']));
				
		
		if (file_exists($dir . $file)){
			
			copy($dir . $file, $dir . 'backups/' . date('Y_m_d_H_i_s') .'_'. $file);
			
			$handle = fopen($dir . $file, 'w+'); 
			fclose($handle); 
			echo 'ok';
		} else {
			echo 'Ошибка!';
		}
		
	}

	public function clear() {
		$this->language->load('tool/error_log');

		$file = DIR_LOGS . $this->config->get('config_error_filename');

		$handle = fopen($file, 'w+'); 

		fclose($handle); 			

		$this->session->data['success'] = $this->language->get('text_success');

		$this->redirect($this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL'));		
	}
}