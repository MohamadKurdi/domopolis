<?php   
	class ControllerCommonHeaderLandingNoShop extends Controller {				
		protected function index(){
			$this->children = array('common/header/landingnoshop');
			$this->template = '';
			
			$this->render();
		}
		
	}	