<?
class ControllerFeedExchange1c extends Controller {
			
	
	public function index() {
		
		
		
		
	}	
	
	//авторизация для штатной проверки	
	public function modeCheckauth() {

		// Проверяем включен или нет модуль
		if (!$this->config->get('exchange1c_status')) {
			echo "failure\n";
			echo "1c module OFF";
			exit;
		}

		// Разрешен ли IP
		if ($this->config->get('exchange1c_allow_ip') != '') {
			$ip = $_SERVER['REMOTE_ADDR'];
			$allow_ips = explode("\r\n", $this->config->get('exchange1c_allow_ip'));

			if (!in_array($ip, $allow_ips)) {
				echo "failure\n";
				echo "IP is not allowed";
				exit;
			}
		}
		
		// Авторизуем
		if (($this->config->get('exchange1c_username') != '') && (@$_SERVER['PHP_AUTH_USER'] != $this->config->get('exchange1c_username'))) {
			echo "failure\n";
			echo "error login";
		}
		
		if (($this->config->get('exchange1c_password') != '') && (@$_SERVER['PHP_AUTH_PW'] != $this->config->get('exchange1c_password'))) {
			echo "failure\n";
			echo "error password";
			exit;
		}

		echo "success\n";
		echo "key\n";
		echo md5($this->config->get('exchange1c_password')) . "\n";
	}
	
	//штатная функция
	public function modeSaleInit() {
		$limit = 100000 * 1024;
	
		echo "zip=no\n";
		echo "file_limit=".$limit."\n";
	}
	
	
	//Штатная обработка
	public function modeQueryOrders() {
		
		if ($date_from = $this->config->get('exchange1c_order_date')){
			$date_from = date("Y-m-d H:i:s" ,$date_from);
		} else {
			$date_from = "2016-11-15 00:00:00";
		}
			
		$order_statuses = implode(',', $this->config->get('config_odinass_order_status_id'));
		
		$query = $this->db->query("SELECT DISTINCT order_id FROM `order` WHERE date_added >= '" .$date_from. "' AND order_status_id IN (". $order_statuses .")");
		
		$order_ids_list = array();
		foreach ($query->rows as $_row){
			$order_ids_list[] = $_row['order_id']; 
		}
		
		$dir = DIR_EXPORT . 'odinass/orders_to_commerceml/'.date('Y_m_d_H_i_s');
		if (!is_dir($dir)){
			mkdir($dir, 0755, $recursive = true);
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getOrderXML(0, true, $dir, $order_ids_list, true);
		
		$this->load->model('setting/setting');
		$config = $this->model_setting_setting->getSetting('exchange1c');
		$config['exchange1c_order_date'] = date('Y-m-d H:i:s');
	//	$this->model_setting_setting->editSetting('exchange1c', $config);
	}
	
	public function getDeliveriesAndShippings(){
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getDeliveriesAndShippings($do_echo);
				
	}
	
	public function getOrderStatusCodes(){
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getOrderStatusCodes($do_echo);
	}
	
	public function getProductsLeft(){
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getProductsLeft();
	}

	
	public function initiateOrdersFromDateToDateXML(){
		$this->load->model('feed/exchange1c');
		
		if (isset($this->request->get['date_from'])){
			$date_from = date('Y-m-d' ,strtotime($this->request->get['date_from']));
		} else {
			$date_from = "2017-01-01";
		}
		
		if (isset($this->request->get['date_to'])){
			$date_to = date('Y-m-d' ,strtotime($this->request->get['date_to']));
		} else {
			$date_to = date('Y-m-d');
		}
		
		$order_statuses = implode(',', $this->config->get('config_odinass_order_status_id'));
		
		if (isset($this->request->get['only_complete']) && $this->request->get['only_complete']){
			$order_statuses = '17';
		}
		
		$query = $this->db->query("SELECT DISTINCT order_id FROM `order` WHERE date_added >= '" .$date_from. "' AND date_added <= '" .$date_to. "' AND order_status_id IN (". $order_statuses .")");
		
		$orders = array();
		foreach ($query->rows as $row){
			$orders[] = $row['order_id'];
		}
		
		$out = '';
		
		$dir = DIR_EXPORT . 'odinass/orders_from_to/'.$date_from.'_to_'.$date_to;
		if (!is_dir($dir)){
			mkdir($dir);
		}
		
		foreach ($orders as $order){
			$this->model_feed_exchange1c->getOrderXML($order, false, $dir);	
			$this->model_feed_exchange1c->makeSalesResultXML($order);
		//	$out .= HTTP_EXPORT . 'odinass/orders/'.$order.'.xml'.PHP_EOL;
		}
		
		$out .= 'odinass/orders_from_to/'.$date_from.'_to_'.$date_to;
		
		header("Content-Type: text/html");
		header("Charset: UTF-8");
		print_r($out);
	}

	public function getDimensions(){
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getDimensions($do_echo);
		
	}
	
	public function getCurrencies(){
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getCurrencies($do_echo);
		
	}
	
	public function getCategoriesTree(){
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getCategoriesTree($do_echo);
		
	}
	
	public function getOrderTransactionsXML($asked_order_id = 0) {
		
		if ($asked_order_id > 0){
			$order_id = (int)$asked_order_id;
		} elseif (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getOrderTransactionsXML($order_id, $do_echo);
		
	}
	
	public function getOrderReturnsXML($asked_order_id = 0) {
		
		if ($asked_order_id > 0){
			$order_id = (int)$asked_order_id;
		} elseif (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getOrderReturnsXML($order_id, $do_echo);
		
	}
	
	public function makeSalesResultXML($asked_order_id = 0) {
		
		if ($asked_order_id > 0){
			$order_id = (int)$asked_order_id;
		} elseif (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->makeSalesResultXML($order_id, $do_echo);
		
	}
	
	public function getOrderXML($order_id = 0) {
		
		if ($order_id > 0){
			$order_id = (int)$order_id;
		} elseif (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		
		if (isset($this->request->get['do_echo']) && $this->request->get['do_echo'] == '1'){
			$do_echo = true;
		} else {
			$do_echo = false;
		}
		
		$this->load->model('feed/exchange1c');
		$this->model_feed_exchange1c->getOrderXML($order_id, $do_echo);
		
	}
}