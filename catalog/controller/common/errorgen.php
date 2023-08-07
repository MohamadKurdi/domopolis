<?
	
class ControllerCommonErrorGen extends Controller {

	public function testViber(){

		$this->smsAdaptor->sendSMS(
			[
				'to' 		=> '+380632708881',
				'message' 	=> 'THIS IS CHAIN TEST'
			]
		);




	}

	public function index(){
	
		$this->log->debug($this->url->link('product/category', 'path=6415' . '&filterinstock=1'));
	
	}
	
}