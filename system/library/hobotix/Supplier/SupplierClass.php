<?

namespace hobotix\Supplier;

class SupplierClass {

	public $db 			= null;	
	public $log 		= null;	
	public $config 		= null;
	public $registry 	= null;

	protected $supplier_id = null;

	public function __construct($registry){
		$this->registry 		= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');
	}


	public function setSupplierId($supplier_id){
		$this->supplier_id = $supplier_id;
	}

	public function getImage($supplierImage, $secondAttempt = false){

		if (!trim($supplierImage)){
			return '';
		}

		$localImageName 		= md5($supplierImage) . '.' . pathinfo($supplierImage,  PATHINFO_EXTENSION);
		$localImageDir  		= 'data/supplier/' . mb_substr($localImageName, 0, 3) . '/' . mb_substr($localImageName, 4, 6) . '/';
		$localImagePath 		= DIR_IMAGE . $localImageDir;
		$fullLocalImagePath 	= $localImagePath . $localImageName;
		$relativeLocalImagePath = $localImageDir . $localImageName;

		if (!file_exists($fullLocalImagePath)){
			try{
				$httpClient = new \GuzzleHttp\Client();
				$httpResponse = $httpClient->request('GET', $supplierImage, ['stream' => true]);	

				if (!is_dir($localImagePath)){
					mkdir($localImagePath, 0775, true);
				}

				file_put_contents($fullLocalImagePath, $httpResponse->getBody()->getContents());					

			} catch (GuzzleHttp\Exception\ValueError $e){
				echoLine('[SupplierClass::getImage]: Could not get picture: ' . $e->getMessage(), 'e');
				return '';
			} catch (GuzzleHttp\Exception\ClientException $e){
				echoLine('[SupplierClass::getImage]: Could not get picture: ' . $e->getMessage(), 'e');
				return '';
			} catch (\Exception $re){

				if (!$secondAttempt){
					echoLine('[SupplierClass::getImage]: Could not get picture, maybe timeout ' . $re->getMessage(), 'e');
					sleep(mt_rand(3, 5));
					$this->getImage($supplierImage, true);

				} else {
					return '';
				}
			}
		}

		echoLine('[SupplierClass::getImage] Picture: ' . $supplierImage . ' -> ' . $localImageName, 's');

		return $localImageDir . $localImageName;
	}	







}