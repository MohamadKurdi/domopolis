<?php

class ControllerAPIHelpCrunch extends Controller {
	public function index(){

		$this->template = 'module/helpcrunch.tpl';
        $this->response->setOutput($this->render());

	}
}