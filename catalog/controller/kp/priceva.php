<?php

class ControllerKPPriceva extends Controller {	
	private $pricevaAPIS 	= [];
	private $stores 		= [0,1,2,5];	
	private $pricevaAdaptor = null;


	private $sleeps = [60, 120];
	private $limit = 900;


	public function __construct($registry){
		parent::__construct($registry);

		if ($this->config->get('config_priceva_enable_api')){
			$this->pricevaAdaptor = new \hobotix\PricevaAdaptor($registry);
			foreach ($this->stores as $store_id){
				if ($apiKey = $this->config->get('config_priceva_api_key_' . $store_id)){

					try{
						$this->pricevaAPIS[$store_id] = new Priceva\PricevaAPI($apiKey);
						$this->pricevaAPIS[$store_id]->main_ping();
					}catch( \Exception $e ){
						echoLine('[ControllerKPPriceva] Error at PING stage, exiting', 'e');
						die($e->getMessage());
					}
				}
			}
		} else {
			echoLine('[ControllerKPPriceva] Priceva API is not enabled, exiting', 'e');
		}
	}

	private function sleepRand(){
		$sec = mt_rand($this->sleeps[0], $this->sleeps[1]);
		echoLine('[ControllerKPPriceva::sleepRand] Sleeping for: ' . $sec . ' sec, due to restrictions', 'w');
		sleep($sec);
	}


	public function cron(){
		if (php_sapi_name() != 'cli'){
			die();
		}

		$filters        = new \Priceva\Params\Filters();
		$fields 		= new \Priceva\Params\ProductFields();
		$sources 		= new \Priceva\Params\Sources();		

		$fields[] = 'client_code';
		$fields[] = 'articul';

		$fields[] = 'name';
		$fields[] = 'active';
		$fields[] = 'default_price';
		$fields[] = 'default_available';
		$fields[] = 'default_discount_type';
		$fields[] = 'default_discount';
		$fields[] = 'repricing_min';
		$fields[] = 'default_currency';

		$sources[ 'add' ] = true;
		$sources[ 'add_term' ] = true;
		

		foreach ($this->stores as $store_id){

			$filters[ 'limit' ] = $this->limit;
			$filters[ 'page' ]  = 1;

			if ($apiKey = $this->config->get('config_priceva_api_key_' . $store_id)){
				$this->pricevaAdaptor->cleanTables($store_id);

				echoLine('[ControllerKPPriceva::cron] Working in store: ' . $store_id . ', key: ' . $apiKey, 'i');
				echoLine('[ControllerKPPriceva::cron] Working at page: ' . $filters[ 'page' ] , 'i');

				$reports = $this->pricevaAPIS[$store_id]->product_list($filters, $sources);
				$count = $total_count = (int)$reports->get_result()->pagination->pages_cnt;

				$products = $reports->get_result()->objects;
				$this->pricevaAdaptor->updateProductData($store_id, $products);

				echoLine('[ControllerKPPriceva::cron] Pages total: ' . $count, 'i');
				$this->sleepRand();

				while( $count > 1 ){
					try{
						$count--;
						$filters[ 'page' ] = $filters[ 'page' ] + 1;

						echoLine('[ControllerKPPriceva::cron] : Working at page: ' . $filters[ 'page' ] , 'i');
						$reports = $this->pricevaAPIS[$store_id]->product_list($filters, $sources);

						$products = $reports->get_result()->objects;
						$this->pricevaAdaptor->updateProductData($store_id, $products);			

						$this->sleepRand();
						
					} catch (\Priceva\PricevaException $e){
						echoLine('[ControllerKPPriceva::cron] Got PricevaException: ' . $e->getMessage(), 'e');												
						$count++;
						if ($count > $total_count){
							$count = 1;							
						}
						$filters[ 'page' ] = $filters[ 'page' ] - 1;

						$this->sleepRand();
					}		
				}
			}
		}
	}
}