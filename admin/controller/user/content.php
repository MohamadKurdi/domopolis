<?
	
	class ControllerUserContent extends Controller {
		
		public function index() {
			$this->language->load('user/user');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->getList();
		}



		private function getList(){
			$this->document->setTitle('Учет рабочего времени и работы контентов');
			$this->data['heading_title'] = 'Учет рабочего времени и работы контентов';
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('user/user');
			$this->load->model('kp/work');
			$this->load->model('kp/content');
			$this->load->model('user/user_group');






		}










	}