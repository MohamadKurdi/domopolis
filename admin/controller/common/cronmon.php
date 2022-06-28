<?php   
class ControllerCommonCronMon extends Controller {   
	public function index() {
		$this->language->load('common/home');

		$this->document->setTitle('Монитор cron');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => 'Монитор cron',
			'href'      => $this->url->link('common/panel', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['token'] = $this->session->data['token'];		

		$this->data['processes'] = [];
		$this->data['processes'] = $this->simpleProcess->getProcesses();
		$this->template = 'common/cronmon.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());			
	}


}