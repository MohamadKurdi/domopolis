<?
	
class ControllerCommonErrorGen extends Controller {

	public function testViber(){
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder(317671);
		$data = [
			'amount' => 200,
			'order_status_id' => 7
		];

		$this->smsAdaptor->sendPayment($order_info, $data);


	}

	public function index(){
	
		$this->log->debug($this->url->link('product/category', 'path=6415' . '&filterinstock=1'));
	
	}
	
}