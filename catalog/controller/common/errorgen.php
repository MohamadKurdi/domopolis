<?
	
class ControllerCommonErrorGen extends Controller {

	public function testViber(){
		$this->load->model('checkout/order');
		$this->load->model('account/customer');

		$order_info 	= $this->model_checkout_order->getOrder(317671);
		$customer_info 	= $this->model_account_customer->getCustomer($order_info['customer_id']);
		$data = [			
			'points_amount' => 1000,
			'points_days_left' => 5
		];

		$this->smsAdaptor->sendRewardReminder($customer_info, $data);


	}

	public function index(){
	
		$this->log->debug($this->url->link('product/category', 'path=6415' . '&filterinstock=1'));
	
	}
	
}