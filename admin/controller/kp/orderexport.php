<?
	
	class ControllerKPOrderExport extends Controller {
		protected $error = array();
		
		public function index() {
			
			$this->language->load('sale/contact');		
			
			$this->load->model('setting/setting');
			$this->load->model('setting/store');
			
			$this->document->setTitle('Выгрузка заказов для учета');
			
			$this->data['button_send'] = $this->language->get('button_send');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['token'] = $this->session->data['token'];
			
			
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => 'Выгрузка заказов для учета',
			'href'      => $this->url->link('kp/orderexport', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			
			$this->template = 'report/orderexport.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
			
		}
		
		public function parseList(){
			$_data = $this->request->post['input'];
			$_data = explode(PHP_EOL, $_data);
			$output = '';
			
			$orders = array();			
			foreach ($_data as $order){
				$tmp = explode('-', $order);				
				$orders[$order] = (int)$tmp[0];				
			}
			
			
			$this->response->setOutput(json_encode($output));
			
		}
		
		
	}			