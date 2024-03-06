<?php
class ControllerCommonMaintenance extends Controller {
	public function index() {
		if ($this->config->get('config_maintenance')) {
			$route = '';

			if (isset($this->request->get['route'])) {
				$part = explode('/', $this->request->get['route']);

				if (isset($part[0])) {
					$route .= $part[0];
				}			
			}

			$this->user = new \hobotix\UserExtended($this->registry);

			if (($route != 'payment') && !$this->user->isLogged()) {
				return $this->forward('common/maintenance/info');
			}						
		}
	}

	public function info() {
		$this->language->load('common/maintenance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
			'text'      => $this->language->get('text_maintenance'),
			'href'      => $this->url->link('common/maintenance'),
			'separator' => false
		);

		$this->data['message'] = $this->language->get('text_message');

		$this->template = 'common/maintenance';

		$this->children = array(
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}
}