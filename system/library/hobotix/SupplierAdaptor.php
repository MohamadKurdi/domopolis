<?

namespace hobotix;


class SupplierAdaptor
{

	private $registry		= null;
	private $config			= null;
	private $db  			= null;
	private $currency  		= null;
	private $supplierObject = null;
	private $supplier_id 	= null;			

	public function __construct($registry){
		$this->registry 		= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');
	}

	public function use($supplierClass, $data = []){
		if (file_exists(DIR_SYSTEM . '/library/hobotix/Suppliers/' . $supplierClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/Suppliers/' . $supplierClass . '.php');
			$supplierClass = "hobotix" . "\\" . "Supplier" . "\\" . $supplierClass;
			$this->supplierObject = new $supplierClass($this->registry);

			echoLine('[SupplierAdaptor::use] Using ' . $supplierClass . ' supplier library', 's');			
		} else {
			echoLine('[TranslateAdaptor::use] Tried to use ' . $supplierClass . ' supplier library, but failed', 'e');	
		}	 	

		if (!empty($data['path_to_feed'])){
			$this->setFeed($data['path_to_feed']);
		}

		if (!empty($data['supplier_id'])){
			$this->setSupplierId($data['supplier_id']);
		}

		return $this;
	}

	public function setSupplierId($supplier_id){
		$this->supplier_id = $supplier_id;
	}

	public function setFeed($feed){
		if (method_exists($this->supplierObject, 'setFeed')){
			echoLine('[SupplierAdaptor::setFeed] Set feed ' . $feed, 's');
			return $this->supplierObject->setFeed($feed);
		} else {
			echoLine('[SupplierAdaptor::setFeed] No setFeed function!', 'e');
		}
	}

	public function setDebug($debug){
		if (method_exists($this->supplierObject, 'setDebug')){
			return $this->supplierObject->setDebug($debug);
		}
	}

	public function getCategories(){		
		if (method_exists($this->supplierObject, 'getCategories')){
			try {
				$result = $this->supplierObject->getCategories();
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		}

		return $result;
	}

	public function updateCategories($categories, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		foreach ($categories as $category){
			echoLine('[SupplierAdaptor::updateCategories] Got category ' . $category . ' for supplier ' . $supplier_id, 's');

			$this->db->query("INSERT IGNORE INTO supplier_categories SET 
				supplier_id 		= '" . (int)$supplier_id . "',
				supplier_category 	= '" . $this->db->escape($category) . "'");
		}
	}

	public function getSupplierLibraries(){
		$results = [];

		$suppliers = glob(dirname(__FILE__) . '/Suppliers/*');        
        foreach ($suppliers as $supplier) {
            $results[] = pathinfo($supplier,  PATHINFO_FILENAME);
        }

        return $results;
	}

















}