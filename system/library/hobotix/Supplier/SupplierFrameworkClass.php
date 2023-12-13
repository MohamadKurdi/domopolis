<?

namespace hobotix\Supplier;

class SupplierFrameworkClass {

	public $db 			= null;	
	public $log 		= null;	
	public $config 		= null;
	public $registry 	= null;
	public $currency 	= null;

	public $translateAdaptor 		= null;

	protected $supplier_id = null;
	protected $supplier    = null;

	private $mapLanguages = [
		'ua' => 'ru',
		'uk' => 'ua',
		'kz' => 'ru',
		'by' => 'ru'
	];

	public function __construct($registry){
		$this->registry 		= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');
		$this->currency 		= $registry->get('currency');

		require_once(dirname(__FILE__) . '/Models/hoboModel.php');	
		require_once(dirname(__FILE__) . '/Models/modelEdit.php');
		require_once(dirname(__FILE__) . '/Models/modelGet.php');
		require_once(dirname(__FILE__) . '/Models/modelCachedGet.php');

		$this->model_edit 		= new Model\modelEdit($registry);
		$this->model_get 		= new Model\modelGet($registry);
		$this->model_cached_get = new Model\modelCachedGet($registry);

		if ($this->config->get('config_rainforest_enable_translation')){
			$this->translateAdaptor = $registry->get('translateAdaptor');
		}
	}

	public function setSupplierId($supplier_id){
		$this->supplier_id = $supplier_id;

		$query 			= $this->db->query("SELECT * FROM suppliers WHERE supplier_id = '" . (int)$supplier_id . "' LIMIT 1");
		$this->supplier = $query->row;
	}

	public function getSupplierSetting($setting = ''){
		if (!empty($this->supplier[$setting])){
			return $this->supplier[$setting];
		}

		return false;
	}

	public function mapTranslation($data, $field, $language_code_from){
		$mapped_data = [];

		$translated_data = $this->translateArrayWithCheck($data[$field], $language_code_from);

		foreach ($translated_data as $language_id => $value){			
			$mapped_data[$language_id] = [
				$field 			=> $value['translated'],
				'translated' 	=> true
			];
		}

		return $mapped_data;
	}

	public function translateArrayWithCheck($data, $language_code_from){
		$translated_data = [];

		$real_language_code_from = $this->registry->get('language')->mapCode($language_code_from);		

		foreach ($this->registry->get('languages') as $language_code => $language) {
			$real_language_code_to =  $this->registry->get('language')->mapCode($language_code);	

			if ($language_code == $language_code_from){
				if (!empty($data[$real_language_code_from])){
					$translated = atrim($data[$real_language_code_from]);
				} else {
					$translated = atrim($data[0]);
				}				
			} else {
				if (!empty($data[$real_language_code_to]) && $language['front']){
					$translated = atrim($data[$real_language_code_to]);
				} elseif (!empty($data[1]) && $language['front']){
					$translated = atrim($data[1]);	
				} elseif ($language['front']){

					if (!empty($data[$real_language_code_from])){
						$translated = $this->translateAdaptor->translate(atrim($data[$real_language_code_from]), ($real_language_code_from)?$real_language_code_from:false, $real_language_code_to, true);
					} else {
						$translated = $this->translateAdaptor->translate(atrim($data[0]), ($real_language_code_from)?$real_language_code_from:false, $real_language_code_to, true);
					}
					
				} else {
					$translated = '';
				}
			}

			$translated_data[$language['language_id']] = [
				'translated' 	=> $translated
			];
		}

		return $translated_data;
	}				

	public function getImage($supplierImage, $secondAttempt = false){
		if (!trim($supplierImage)){
			return '';
		}

		if ($this->supplier_id){
			$supplier_id = $this->supplier_id;
		} else {
			$supplier_id = 0;
		}

		$localImageName 		= md5($supplierImage) . '.' . pathinfo($supplierImage,  PATHINFO_EXTENSION);
		$localImageDir  		= 'data/supplier/' . $supplier_id . '/' . mb_substr($localImageName, 0, 3) . '/' . mb_substr($localImageName, 4, 6) . '/';
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
					echoLine('[SupplierClass::getImage]: Could not get picture ' . $re->getMessage(), 'e');
					sleep(mt_rand(3, 5));
					$this->getImage($supplierImage, true);

				} else {
					echoLine('[SupplierClass::getImage]: Finally could not get image, returning false', 'e');
					return false;
				}
			}
		}

		echoLine('[SupplierClass::getImage] Trying to get: ' . $supplierImage . ' -> ' . $localImageName, 's');

		return $localImageDir . $localImageName;
	}	

}