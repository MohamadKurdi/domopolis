<?php 
class ControllerLocalisationOrderBottomForms extends Controller { 
	private $error = array();

	public function index() {		

		$this->document->setTitle('Редактирование шаблонов подтверждения заказа');
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => 'Редактирование шаблонов подтверждения заказа',
			'href'      => $this->url->link('localisation/order_bottom_forms', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		
		$dir = DIR_TEMPLATE . 'sale/bottom_texts/';
		$tmp_files = scandir($dir);
		
		$this->data['texts'] = array();
		
		foreach ($tmp_files as $text){
			if (($text != '.') && ($text != '..')){
				$key = explode('_', $text);
				$key = $key[0];
				$this->data['texts'][$key][] = $text;
			}
		}

		if (isset($this->request->get['text_to_edit'])){
			if (mb_substr($this->request->get['text_to_edit'], -4) == '.TPL'){
				$this->data['text_to_edit'] = $this->db->escape($this->request->get['text_to_edit']);
			} else {
				$this->data['text_to_edit'] = 'EMPTY_EMPTY.TPL';
			}
			
			
			
			
			
		} else {
			$this->data['text_to_edit'] = false;
		}
		
		$this->data['action'] = $this->url->link('localisation/order_bottom_forms/save', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->template = 'localisation/order_bottom_forms.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	
	public function loadtext(){
		$dir = DIR_TEMPLATE . 'sale/bottom_texts/';
		
		if (mb_substr($this->request->post['tpl_file'], -4) == '.TPL'){
			$file = $this->db->escape($this->request->post['tpl_file']);
		} else {
			$file = 'EMPTY_EMPTY.TPL';
		}
		
		
		if (file_exists($dir . $file)){
			echo file_get_contents($dir . $file);
		} else {
			echo '';
		}
		
	}
	
	
	public function savetext() {	
	
		$dir = DIR_TEMPLATE . 'sale/bottom_texts/';
		
		if (mb_substr($this->request->post['tpl_file'], -4) == '.TPL'){
			$file = $this->db->escape($this->request->post['tpl_file']);
		} else {
			$file = 'EMPTY_EMPTY.TPL';
		}
		
		if ($this->request->post['content']){
			$content = html_entity_decode($this->request->post['content']);
		}
	
		if (file_exists($dir . $file)){
			file_put_contents($dir . $file, $content);
		} else {
			echo '';
		}
	
	
	}
}
	
?>