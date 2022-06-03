<?php
class ControllerCommonPreload extends Controller {
	public function index() {
		$this->getChild('module/ocfilter/initialise');
	}
}