<?
	
class ControllerCommonErrorGen extends Controller {

	public function index(){
	
		$this->log->debug($this->url->link('product/category', 'path=6415' . '&filterinstock=1'));
	
	}
	
}