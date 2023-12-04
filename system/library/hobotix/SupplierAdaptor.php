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

	private $product 		= null;			
	private $category 		= null;
	private $manufacturer 	= null;

	public function __construct($registry){
		$this->registry 		= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');

		require_once(DIR_SYSTEM . 'library/hobotix/Suppliers/SuppliersGeneralClass.php');

		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierClass.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierProduct.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierCategory.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierManufacturer.php');

		$this->SupplierProduct 		= new Supplier\SupplierProduct($registry);
		$this->SupplierCategory 	= new Supplier\SupplierCategory($registry);
		$this->SupplierManufacturer = new Supplier\SupplierManufacturer($registry);
	}


	public function getSupplierLibraries(){
		$results = [];

		$suppliers = glob(dirname(__FILE__) . '/Suppliers/*');        
        foreach ($suppliers as $supplier) {
        	if (pathinfo($supplier,  PATHINFO_FILENAME) != 'SuppliersGeneralClass'){
        		$results[] = pathinfo($supplier,  PATHINFO_FILENAME);
        	}
        }

        return $results;
	}


	public function setDebug($debug){
		if (method_exists($this->supplierObject, 'setDebug')){
			return $this->supplierObject->setDebug($debug);
		}
	}

	public function use($supplierClass, $data = []){
		if (file_exists(DIR_SYSTEM . '/library/hobotix/Suppliers/' . $supplierClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/Suppliers/' . $supplierClass . '.php');
			$supplierClass = "hobotix" . "\\" . "Suppliers" . "\\" . $supplierClass;
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

	public function getSuppliers(){
		$query = $this->db->query("SELECT * FROM suppliers WHERE parser <> '' AND parser_status = 1");

		return $query->rows;
	}

	public function setSupplierId($supplier_id){
		$this->supplier_id = $supplier_id;

		$this->SupplierProduct->setSupplierId($supplier_id);
		$this->SupplierCategory->setSupplierId($supplier_id); 
		$this->SupplierManufacturer->setSupplierId($supplier_id); 
	}

	public function setFeed($feed){
		if (method_exists($this->supplierObject, 'setFeed')){
			echoLine('[SupplierAdaptor::setFeed] Set feed ' . $feed, 's');
			return $this->supplierObject->setFeed($feed);
		} else {
			echoLine('[SupplierAdaptor::setFeed] No setFeed function!', 'e');
		}
	}

	public function getCategories(){		
		if (method_exists($this->supplierObject, 'getCategories')){
			try {
				$result = $this->supplierObject->getCategories();
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		} else {
			echoLine('[SupplierAdaptor::getCategories] No getCategories function!', 'e');
		}		

		return $result;
	}

	public function getProducts(){		
		if (method_exists($this->supplierObject, 'getProducts')){
			try {
				$result = $this->supplierObject->getProducts();
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		} else {
			echoLine('[SupplierAdaptor::getProducts] No getProducts function!', 'e');
		}	

		return $result;
	}
}