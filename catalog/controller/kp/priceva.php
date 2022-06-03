<?php

class ControllerKPPriceva extends Controller {	
	private $pricevaAPIS = [];
	private $stores = [0,1,2,5];	
	private $pricevaAdaptor = null;


	private $sleeps = [30, 50];
	private $limit = 1000;


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

						die($e->getMessage());

					}

				}


			}

		} else {

			die('Please enable API in settings');

		}
	}

	private function sleepRand(){

		$sec = mt_rand($this->sleeps[0], $this->sleeps[1]);
		echoLine('[PRICEVA] Спим: ' . $sec);
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

				echoLine('[PRICEVA] Магазин: ' . $store_id . ', key: ' . $apiKey);
				echoLine('[PRICEVA] Страница: ' . $filters[ 'page' ]);

				$reports = $this->pricevaAPIS[$store_id]->product_list($filters, $sources);
				$count = $total_count = (int)$reports->get_result()->pagination->pages_cnt;

				$products = $reports->get_result()->objects;

				$this->pricevaAdaptor->updateProductData($store_id, $products);

				echoLine('[PRICEVA] Всего страниц: ' . $count);
				$this->sleepRand();

				while( $count > 1 ){
					try{
						$count--;
						$filters[ 'page' ] = $filters[ 'page' ] + 1;

						echoLine('[PRICEVA] Страница: ' . $filters[ 'page' ]);
						$reports = $this->pricevaAPIS[$store_id]->product_list($filters, $sources);

						$products = $reports->get_result()->objects;
						$this->pricevaAdaptor->updateProductData($store_id, $products);			

						$this->sleepRand();
						
					} catch (\Priceva\PricevaException $e){

						echoLine('[PRICEVA] Ошибка: ' . $e->getMessage());												
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