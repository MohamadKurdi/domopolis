
<?php   
	class ControllerCommonHeaderSimple extends Controller {
		private $top_block_id = 'top_covid3';
		
		protected function index(){
			$this->children = array('common/header/simple');
			$this->template = '';
			
			$this->render();
		}
		
	}	