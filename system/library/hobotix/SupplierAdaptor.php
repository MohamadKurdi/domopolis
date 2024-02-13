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

		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/Adaptors/SuppliersGeneralClass.php');

		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierFrameworkClass.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierProduct.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierCategory.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierManufacturer.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/SupplierAttribute.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Supplier/PriceLogic.php');

		$this->SupplierProduct 		= new Supplier\SupplierProduct($registry);
		$this->SupplierCategory 	= new Supplier\SupplierCategory($registry);
		$this->SupplierManufacturer = new Supplier\SupplierManufacturer($registry);
		$this->SupplierAttribute 	= new Supplier\SupplierAttribute($registry);
		$this->PriceLogic 			= new Supplier\PriceLogic($registry);
	}


	public function getSupplierLibraries(){
		$results = [];

		$suppliers = glob(dirname(__FILE__) . '/Supplier/Adaptors/*');        
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
		if (file_exists(DIR_SYSTEM . '/library/hobotix/Supplier/Adaptors/' . $supplierClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/Supplier/Adaptors/' . $supplierClass . '.php');
			$supplierClass = "hobotix" . "\\" . "Supplier\Adaptors" . "\\" . $supplierClass;
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
		$this->SupplierAttribute->setSupplierId($supplier_id);
		$this->PriceLogic->setSupplierId($supplier_id);
	}

	public function setFeed($feed){
		if (method_exists($this->supplierObject, 'setFeed')){
			echoLine('[SupplierAdaptor::setFeed] Set feed ' . $feed, 's');
			return $this->supplierObject->setFeed($feed);
		} else {
			echoLine('[SupplierAdaptor::setFeed] No setFeed function!', 'e');
		}
	}

	public function postAction(){					
		if (method_exists($this->supplierObject, 'postAction')){
			try {
				$results = $this->supplierObject->postAction();
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		} else {
			echoLine('[SupplierAdaptor::postActions] No postActions function!', 'e');
		}		
	}

	public function postProductAction($product_id){		
		if (method_exists($this->supplierObject, 'postAction')){
			try {
				$results = $this->supplierObject->postAction($product_id);
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		} else {
			echoLine('[SupplierAdaptor::postProductAction] No postProductAction function!', 'e');
		}		
	}

	public function getCategories(){		
		if (method_exists($this->supplierObject, 'getCategories')){
			try {
				$results = $this->supplierObject->getCategories();
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		} else {
			echoLine('[SupplierAdaptor::getCategories] No getCategories function!', 'e');
		}		

		return $results;
	}

	public function getAttributes(){		
		if (method_exists($this->supplierObject, 'getAttributes')){
			try {
				$results = $this->supplierObject->getAttributes();
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		} else {
			echoLine('[SupplierAdaptor::getAttributes] No getAttributes function!', 'e');
		}		

		return $results;
	}

	public function getProducts(){		
		$results = [];

		if (method_exists($this->supplierObject, 'getProducts')){
			try {				
				$products = $this->supplierObject->getProducts();

				foreach ($products as $product){
					if (!is_array($product['name'])){
						$product['name'] = [$product['name']];
					}

					if (empty($product['description'])){
						$product['description'] = [];
					} elseif (!is_array($product['description'])){
						$product['description'] = [$product['description']];
					}

					if (isset($product['quantity'])){
						$product['quantity'] = (int)$product['quantity'];
					} else {
						if (isset($product['stock']) && $product['stock']){
							$product['quantity'] = 100;
						} else {
							$product['quantity'] = 0;
						}
					}

					atrim_array($product['name']);
					atrim_array($product['description']);

					$results[] = [
						'supplier_product_id' 	=> atrim($product['supplier_product_id']),
						'name'					=> $product['name'],
						'description' 			=> $product['description'],
						'status' 				=> (int)$product['status'],
						'model' 				=> !empty($product['model'])?atrim($product['model']):atrim($product['supplier_product_id']),
						'sku' 					=> !empty($product['sku'])?atrim($product['sku']):atrim($product['supplier_product_id']),
						'ean' 					=> !empty($product['ean'])?atrim($product['ean']):'',
						'asin' 					=> !empty($product['asin'])?atrim($product['asin']):'',
						'image' 				=> !empty($product['image'])?atrim($product['image']):'',
						'images' 				=> !empty($product['images'])?$product['images']:[],
						'stock' 				=> isset($product['stock'])?$product['stock']:true,
						'quantity' 				=> (int)$product['quantity'],
						'price' 				=> !empty($product['price'])?(float)$product['price']:0,
						'price_special'			=> !empty($product['price_special'])?(float)$product['price_special']:0,
						'vendor' 				=> !empty($product['vendor'])?atrim($product['vendor']):'NoName',
						'vendor_country'		=> !empty($product['vendor_country'])?atrim($product['vendor_country']):'',
						'category'  			=> !empty($product['category'])?atrim($product['category']):'',
						'attributes'  			=> !empty($product['attributes'])?$product['attributes']:[],
						'raw' 					=> $product['raw']
					];
				}

			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		} else {
			echoLine('[SupplierAdaptor::getProducts] No getProducts function!', 'e');
		}	

		return $results;
	}
}