<?php 
class ControllerAccountTracker extends Controller {
	private $error = array();

	public function index(){
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->link('account/order', '', 'SSL'));
		}


		$this->language->load('account/account');
		$this->language->load('account/tracker');

		foreach ($this->language->loadRetranslate('account/account') as $translationСode => $translationText){
			$this->data[$translationСode] = $translationText;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateTracker()) {			
			$this->response->redirect($this->url->link('account/order/info', 'order_id=' . (int)$this->request->post['order_id']));
		} else {

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);	

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_account'),
				'href'      => $this->url->link('account/account', ''),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['heading_title'] = $this->language->get('heading_title');

			if (isset($this->request->post['order_id'])) {
				$this->data['order_id'] = $this->request->post['order_id'];
			} elseif(isset($order_id)) {
				$this->data['order_id'] = $order_id;
			} else {
				$this->data['order_id'] = '';
			}

			if (isset($this->request->post['auth'])) {
				$this->data['auth'] = $this->request->post['auth'];
			} else {
				$this->data['auth'] = '';
			}

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}

			$this->data['action'] = $this->url->link('account/tracker', '');
			$this->template = 'account/tracker_login.tpl';

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'		
			);

			$this->response->setOutput($this->render());
		}
	}

	private function validateTracker($order_id = false){
		$this->load->model('account/order');
		$this->load->model('account/customer');

		$this->language->load('account/order');
		$this->language->load('account/account');
		$this->language->load('account/transaction');	
		$this->language->load('account/tracker');

		if ($order_id){
			$this->request->post['order_id'] = $order_id;
		}

		if (empty($this->request->post['order_id']) || empty($this->request->post['auth'])){
			$this->error['warning'] = $this->language->get('text_warning_1');
		} else {

			$order = $this->model_account_order->getOrder($this->request->post['order_id'], true);

			if (!$order){			
				$this->error['warning'] = $this->language->get('text_warning_2');		
			} else {
				$logged = false;					

				if ($order['email'] && mb_strlen($order['email']) > 4 && strpos($order['email'], '@') !== false && trim($order['email']) == trim($this->request->post['auth'])){
					$logged = true;
				}			

				if (!$logged && $order['telephone'] && mb_strlen($order['telephone']) > 5 && trim(preg_replace('([^0-9])', '', $order['telephone'])) && (trim($order['telephone']) == trim($this->request->post['auth']) || trim(preg_replace('([^0-9])', '', $order['telephone'])) == trim(preg_replace('([^0-9])', '', $this->request->post['auth'])))){

					$logged = true;

				}
				if (!$logged && $order['fax'] && mb_strlen($order['fax']) > 5 && trim(preg_replace('([^0-9])', '', $order['fax'])) && (trim($order['fax']) == trim($this->request->post['auth']) || trim(preg_replace('([^0-9])', '', $order['fax'])) == trim(preg_replace('([^0-9])', '', $this->request->post['auth'])))){
					$logged = true;
				}

				if (!$logged){
					$customer = $this->model_account_customer->getCustomer($order['customer_id']);
					$discount_card = $customer['discount_card'];

					if (!$logged 
						&& $discount_card 
						&& mb_strlen($discount_card) > 5 
						&& trim(preg_replace('([^0-9])', '', $discount_card)) 
						&& (trim($discount_card) == trim($this->request->post['auth']) || trim(preg_replace('([^0-9])', '', $discount_card)) == trim(preg_replace('([^0-9])', '', $this->request->post['auth'])))){
						$logged = true;
					}

				}

				if (!$logged){
					$this->error['warning'] = $this->language->get('text_warning_3');
				}
			}			
		}

		if ($logged){
			$this->customer->login($order['customer_id'], '', true);
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}													