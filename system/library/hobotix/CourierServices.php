<?

namespace hobotix;


class CourierServices {
	private $ttn;	
	private $registry;
	private $config;
	private $db;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');		
	}

	public function getInfo($code, $shipping_code, $phone = '') {				
		if (!empty($this->registry->get('shippingmethods')[$shipping_code]) && !empty($this->registry->get('shippingmethods')[$shipping_code]['class'])){
			$deliveryClass = $this->registry->get('shippingmethods')[$shipping_code]['class'];

			if (file_exists(DIR_SYSTEM . '/library/hobotix/Shipping/' . $deliveryClass . '.php')){
				require_once (DIR_SYSTEM . '/library/hobotix/Shipping/' . $deliveryClass . '.php');
				$deliveryClass = "hobotix" . "\\" . "shipping" . "\\" . $deliveryClass;
				$deliveryObject = new $deliveryClass($this->registry);

				if (method_exists($deliveryObject, 'trackAndFormat')){
					try {
						$result = $deliveryObject->trackAndFormat($code, $phone);
					} catch (\Exception $e){
						$result = $e->getMessage();
					}
				}
			}			
		}

		if (empty($result)){
			$result = 'Could not get information about tracking code';
		}			

		return $result;
	}	

	public function updateReferences($deliveryClass){
		if (file_exists(DIR_SYSTEM . '/library/hobotix/Shipping/' . $deliveryClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/Shipping/' . $deliveryClass . '.php');
			$deliveryClass = "hobotix" . "\\" . "shipping" . "\\" . $deliveryClass;
			$deliveryObject = new $deliveryClass($this->registry);

			if (method_exists($deliveryObject, 'updateReferences')){
				try {
					$deliveryObject->updateReferences();
				} catch (\Exception $e){
					echoLine ($e->getMessage());
				}
			}
		}					
	}


	public function trackMultiCodes($data){
		$result = [];

		foreach ($data as $shipping_code => $codes){
			if (!empty($this->registry->get('shippingmethods')[$shipping_code]) && !empty($this->registry->get('shippingmethods')[$shipping_code]['class'])){
				$deliveryClass = $this->registry->get('shippingmethods')[$shipping_code]['class'];

				if (file_exists(DIR_SYSTEM . '/library/hobotix/Shipping/' . $deliveryClass . '.php')){
					require_once (DIR_SYSTEM . '/library/hobotix/Shipping/' . $deliveryClass . '.php');
					$deliveryClass = "hobotix" . "\\" . "shipping" . "\\" . $deliveryClass;
					$deliveryObject = new $deliveryClass($this->registry);

					if (method_exists($deliveryObject, 'trackMultiCodes')){
						try {
							$result[$shipping_code] = $deliveryObject->trackMultiCodes($codes);
						} catch (\Exception $e){
							$result = $e->getMessage();
						}
					}
				}			
			}
		}

		return $result;
	}

	
}				