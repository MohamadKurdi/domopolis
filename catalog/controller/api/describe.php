<?php

class ControllerAPIDescribe extends Controller {

	public function index(){
			$apiConfig = loadJSONConfig('api');
			$this->response->setOutput(json_encode($apiConfig));
	}

}