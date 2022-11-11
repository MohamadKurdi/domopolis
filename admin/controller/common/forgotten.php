<?php
class ControllerCommonForgotten extends Controller {
	private $error = array();

	public function index() {
		$this->redirect($this->url->link('common/login', '', 'SSL'));				
	}
}