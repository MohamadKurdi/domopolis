<?
	
	class ControllerUserMyWork extends Controller {
		
		public function index() {
			$this->language->load('user/user');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->document->setTitle('Мои результаты работы');
			$this->data['heading_title'] = 'Мои результаты работы';
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('user/user');
			$this->load->model('user/ticket');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => 'Мои результаты работы',
			'href'      => $this->url->link('user/ticket', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			$this->template = 'user/user_work_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
				
		