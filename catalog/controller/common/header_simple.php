<?php   
	class ControllerCommonHeaderSimple extends Controller {				
		protected function index(){
			$this->children = array('common/header/simple');
			$this->template = '';
			
			$this->render();
		}
		
	}	