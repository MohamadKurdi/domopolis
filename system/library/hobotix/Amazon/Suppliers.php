<?

namespace hobotix\Amazon;

class Suppliers
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\Suppliers';
	
	private $db;	
	private $config;	
	public  $supplierData = [];

	private $code 		= 'Amazon';
	private $country 	= 'EUR';

	public $supplierMinRatingForUse 	 = 26;
	public $supplierMinInnerRatingForUse = -100;

	public function __construct($registry){

		$this->config = $registry->get('config');
		$this->db = $registry->get('db');

		$this->supplierMinRatingForUse 		= $this->config->get('config_rainforest_supplierminrating') * 10;
		$this->supplierMinInnerRatingForUse = $this->config->get('config_rainforest_supplierminrating_inner');

		/* Cache
		$query = $this->db->ncquery("SELECT supplier_name, supplier_id, amzn_good, amzn_bad, amzn_coefficient FROM suppliers WHERE supplier_type LIKE '" . $this->db->escape($this->code) . "'");
		foreach ($query->rows as $row){
			$this->supplierData[trim($row['supplier_name'])] = $row;
		}
		*/

	}


	/*
		Just for backward compatibility
	*/
	public function checkIfSupplierExists($name){
		return $this->checkIfSupplierExistsInCache($name);
	}

	public function checkIfSupplierExistsInCache($name){
		return !empty($this->supplierData[trim($name)]);
	}

	public function getSupplier($name){
		if ($this->checkIfSupplierExists($name)){
			return $this->supplierData[trim($name)];
		} else {
			return $this->getSupplierFromDataBase($name);
		}

		return false;
	}

	public function getSupplierFromDataBase($name){
		$query = $this->db->ncquery("SELECT * FROM suppliers WHERE supplier_name LIKE '" . $this->db->escape($name) . "' LIMIT 1");

		if ($query->num_rows){
			return $query->row;
		} else {
			return false;
		}
	}

	public function checkIfSupplierExistsInDataBase($name){
		$query = $this->db->ncquery("SELECT * FROM suppliers WHERE supplier_name LIKE '" . $this->db->escape($name) . "'");

		return $query->num_rows;
	}


	public function addSupplier($name) {

		if (!$name){
			return 0;
		}

		if (!$this->checkIfSupplierExistsInDataBase($name)){

			$this->db->query("INSERT INTO suppliers SET 
				supplier_name 		= '" . $this->db->escape(trim($name)) . "',
				supplier_code 		= '" . $this->db->escape('AMZN') . "',
				supplier_type 		= '" . $this->db->escape($this->code) . "', 			
				supplier_country 	= '" . $this->db->escape($this->country) . "', 
				supplier_comment 	= '" . $this->db->escape('Auto API') . "', 
				supplier_m_coef 	= '0',
				supplier_l_coef 	= '0', 
				supplier_n_coef 	= '0',
				supplier_parent_id 	= '0',
				sort_order 			= '0',
				terms_instock 		= '',
				terms_outstock  	= '',
				supplier_inner 		= '0',
				amzn_good			= '0',
				amzn_bad			= '0',
				amzn_coefficient 	= '0'");

			$this->supplierData[trim($name)] = true;

			$supplier_id = $this->db->getLastId();
			$this->db->query("UPDATE suppliers SET supplier_code = '" . $this->db->escape('AMZN' . $supplier_id) . "' WHERE supplier_id = '" . (int)$supplier_id . "'");

		}
	}

}