<?

class ControllerSaleLoadOrderText extends Controller {

	public function index() {
		$this->language->load('sale/order');
		$this->load->model('sale/order');

		$tpl = $this->request->post('tpl_file');
		$order_id = (int)$this->request->post('order_id');
		
		
	}
	
}