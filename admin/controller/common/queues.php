<?php   
class ControllerCommonQueues extends Controller {   
	public function index() {
	}


	public function countRainforestQueue(){
		$query = $this->db->query("SELECT count(*) as total FROM amzn_product_queue WHERE 1");
		$count = $query->row['total'];

		$body = $count;
		$class= 'good';

		if ($count > 10){
			$class = 'warn';
		}

		if ($count > 20){
			$class = 'bad';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countSmsQueue(){
		$query = $this->db->query("SELECT count(*) as total FROM queue_sms WHERE 1");
		$count = $query->row['total'];

		$body = $count;
		$class= 'good';

		if ($count > 10){
			$class = 'warn';
		}

		if ($count > 20){
			$class = 'bad';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countMailQueue(){
		$query = $this->db->query("SELECT count(*) as total FROM queue_mail WHERE 1");
		$count = $query->row['total'];

		$body = $count;
		$class= 'good';

		if ($count > 10){
			$class = 'warn';
		}

		if ($count > 20){
			$class = 'bad';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countMailWizzQueue(){

		if ($this->config->get('config_mailwizz_enable')){
			$query = $this->db->query("SELECT count(*) as total FROM mailwizz_queue WHERE 1");
			$count = $query->row['total'];

			$body = $count;
			$class= 'good';

			if ($count > 10){
				$class = 'warn';
			}

			if ($count > 20){
				$class = 'bad';
			}
		} else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countYandexStockQueue(){

		if ($this->config->get('config_yam_fbs_campaign_id')){
			$query = $this->db->query("SELECT count(*) as total FROM yandex_stock_queue WHERE 1");
			$count = $query->row['total'];

			$body = $count;
			$class= 'good';

			if ($count > 500){
				$class = 'warn';
			}

			if ($count > 1000){
				$class = 'bad';
			}

		} else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countYandexOrderQueue(){
		if ($this->config->get('config_yam_fbs_campaign_id')){
			$query = $this->db->query("SELECT count(*) as total FROM yandex_queue WHERE 1");
			$count = $query->row['total'];

			$body = $count;
			$class= 'good';

			if ($count > 5){
				$class = 'warn';
			}

			if ($count > 10){
				$class = 'bad';
			}

		} else {

			$body = 'OFF';
			$class= 'warn';

		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countRewardQueue(){
		$query = $this->db->query("SELECT count(*) as total FROM customer_reward_queue WHERE 1");
		$count = $query->row['total'];

		$body = $count;
		$class= 'good';

		if ($count > 100){
			$class = 'warn';
		}

		if ($count > 200){
			$class = 'bad';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countProductTo1CQueue(){
		$query = $this->db->query("SELECT count(*) as total FROM odinass_product_queue WHERE 1");
		$count = $query->row['total'];

		$body = $count;
		$class= 'good';

		if ($count > 10){
			$class = 'warn';
		}

		if ($count > 20){
			$class = 'bad';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

	public function countOrderTo1CQueue(){
		$query = $this->db->query("SELECT count(*) as total FROM order_to_1c_queue WHERE 1");
		$count = $query->row['total'];

		$body = $count;
		$class= 'good';

		if ($count > 10){
			$class = 'warn';
		}

		if ($count > 20){
			$class = 'bad';
		}

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		$this->response->setOutput(json_encode($json));

	}

}