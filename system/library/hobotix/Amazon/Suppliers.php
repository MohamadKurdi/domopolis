<?

namespace hobotix\Amazon;

class Suppliers
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\Suppliers';
	
	private $db 		= null;	
	private $config 	= null;
	private $registry 	= null;

	private $SellerRetriever 	= null;

	private $code 			= 'Amazon';
	private $prefix 		= 'AMZN';
	private $native_code 	= 'DE';

	public $supplierMinRatingForUse 	 = 26;
	public $supplierMinInnerRatingForUse = -100;

	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		$this->registry = $registry->get('registry');

		$this->supplierMinRatingForUse 		= $this->config->get('config_rainforest_supplierminrating') * 10;
		$this->supplierMinInnerRatingForUse = $this->config->get('config_rainforest_supplierminrating_inner');

		if ($this->config->get('config_rainforest_native_country_code')){
			$this->native_code = $this->config->get('config_rainforest_native_country_code');
		}

		require_once(dirname(__FILE__) . '/RainforestRetriever.php');
		require_once(dirname(__FILE__) . '/SellerRetriever.php');

		$this->SellerRetriever = new SellerRetriever($registry);
	}

	public function checkIfSupplierIsNative($supplier){
		if (!empty($supplier['vat_number'])){
			if (substr($supplier['vat_number'], 0, 2) == "DE"){
				echoLine('[Suppliers::checkIfSupplierIsNative] Native by VAT:' . $supplier['vat_number'], 's');
				return true;
			}
		}

		if (!empty($supplier['name'])){
			if (trim($supplier['name']) == $this->code){
				echoLine('[Suppliers::checkIfSupplierIsNative] Native by name:' . $supplier['name'], 's');
				return true;
			}
		}

		if (!empty($supplier['email'])){
			if (substr($supplier['email'], -2) == "de"){
				echoLine('[Suppliers::checkIfSupplierIsNative] Native by email:' . $supplier['email'], 's');
				return true;
			}
		}

		if (!empty($supplier['about_this_seller'])){
			preg_match_all('/(\+|00)?49[\s\-]?\(?[0-9]{2,3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{4,5}/', $supplier['about_this_seller'], $matches);

			if(!empty($matches[0][0])) {
				echoLine('[Suppliers::checkIfSupplierIsNative] Native by phone in about_this_seller:' . $matches[0][0], 's');
				return true;
			}
		}

		if (!empty($supplier['detailed_information'])){
			preg_match_all('/(\+|00)?49[\s\-]?\(?[0-9]{2,3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{4,5}/', $supplier['detailed_information'], $matches);

			if(!empty($matches[0][0])) {
				echoLine('[Suppliers::checkIfSupplierIsNative] Native by phone in detailed_information:' . $matches[0][0], 's');
				return true;
			}
		}

		return false;
	}

	public function guessSupplierPhone($supplier){
		if (!empty($supplier['about_this_seller'])){
			preg_match_all('/(\+|00)?49[\s\-]?\(?[0-9]{2,3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{4,5}/', $supplier['about_this_seller'], $matches);

			if(!empty($matches[0] && !empty($matches[0][0]))) {
				echoLine('[Suppliers::checkIfSupplierIsNative] Got phone in about_this_seller:' . $matches[0][0], 's');
				return $matches[0][0];
			}
		}

		if (!empty($supplier['detailed_information'])){
			preg_match_all('/(\+|00)?49[\s\-]?\(?[0-9]{2,3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{4,5}/', $supplier['detailed_information'], $matches);

			if(!empty($matches[0]) && !empty($matches[0][0])) {
				echoLine('[Suppliers::checkIfSupplierIsNative] Got phone in detailed_information:' . $matches[0][0], 's');
				return $matches[0][0];
			}
		}

		return '';
	}

	public function guessSupplierEmail($supplier){
		if (!empty($supplier['about_this_seller'])){
			preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/', $supplier['about_this_seller'], $matches);

			if(!empty($matches[0][0])) {
				echoLine('[Suppliers::checkIfSupplierIsNative] Got email in about_this_seller:' . $matches[0][0], 's');
				return mb_strtolower(trim($matches[0][0]));
			}
		}

		if (!empty($supplier['detailed_information'])){
			preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/', $supplier['detailed_information'], $matches);

			if(!empty($matches[0][0])) {
				echoLine('[Suppliers::checkIfSupplierIsNative] Got email in detailed_information:' . $matches[0][0], 's');
				return mb_strtolower(trim($matches[0][0]));
			}
		}

		return '';
	}

	public function getSellerQuality($sellerData){
		$sellerQuality = '';

		if (!empty($sellerData['telephone'])){
			$sellerQuality .= 'T';
		}

		if (!empty($sellerData['email'])){
			$sellerQuality .= 'E';
		}

		if ($sellerQuality){
			echoLine('[Suppliers::setSellerQuality] Got seller quality: ' . $sellerQuality, 's');
		}

		return $sellerQuality;		
	}

	/*
		Just for backward compatibility
	*/
	public function checkIfSupplierExists($name, $amazon_seller_id){
		return $this->checkIfSupplierExistsInCache($name, $amazon_seller_id);
	}

	public function checkIfSupplierExistsInCache($name, $amazon_seller_id){		
		return $this->checkIfSupplierExistsInDataBase($name, $amazon_seller_id);
	}

	public function getSupplier($name, $amazon_seller_id){
		return $this->getSupplierFromDataBase($name, $amazon_seller_id);
	}
	/*
		Just for backward compatibility end
	*/

	public function updateSupplierRating($amazon_seller_id, $data){
		$this->db->ncquery("UPDATE suppliers SET 
			rating50 			= '" . (int)$data['rating50'] . "',
			ratings_total 		= '" . (int)$data['ratings_total'] . "',
			positive_ratings100	= '" . (int)$data['positive_ratings100'] . "'
		WHERE amazon_seller_id LIKE '" . $this->db->escape($amazon_seller_id) . "'");
	}

	public function getSupplierFromDataBase($name, $amazon_seller_id){
		if ($amazon_seller_id){
			$query = $this->db->ncquery("SELECT * FROM suppliers WHERE amazon_seller_id LIKE '" . $this->db->escape($amazon_seller_id) . "' LIMIT 1");
			if ($query->num_rows){
				return $query->row;
			}
		}

		$query = $this->db->ncquery("SELECT * FROM suppliers WHERE supplier_name LIKE '" . $this->db->escape($name) . "' LIMIT 1");
		if ($query->num_rows){			
			return $query->row;
		}

		return false;
	}

	public function checkIfSupplierExistsInDataBase($name, $amazon_seller_id){
		if ($amazon_seller_id){
			$query = $this->db->ncquery("SELECT * FROM suppliers WHERE amazon_seller_id LIKE '" . $this->db->escape($amazon_seller_id) . "'");
			if ($query->num_rows){
				return true;
			}
		}

		$query = $this->db->ncquery("SELECT * FROM suppliers WHERE supplier_name LIKE '" . $this->db->escape($name) . "'");
		if ($query->num_rows){
			return true;
		}

		return false;
	}


	public function addSupplier($name, $amazon_seller_id) {

		if (!$name && !$amazon_seller_id){
			return 0;
		}

		if (!$this->checkIfSupplierExistsInDataBase($name, $amazon_seller_id)){
			$this->db->query("INSERT INTO suppliers SET 
				supplier_name 		= '" . $this->db->escape(trim($name)) . "',
				amazon_seller_id 	= '" . $this->db->escape($amazon_seller_id) . "',
				supplier_code 		= '" . $this->db->escape('AMZN') . "',
				supplier_type 		= '" . $this->db->escape($this->code) . "', 			
				supplier_country 	= '', 
				supplier_comment 	= '" . $this->db->escape('Auto from Rainforest API') . "', 
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
				amzn_coefficient 	= '0',
				store_link  		= '',
				business_name  		= '',
				registration_number = '',
				vat_number  		= '',
				business_type  		= '',
				about_this_seller  	= '',
				detailed_information = '',
				is_native 			= '0'");

			$supplier_id = $this->db->getLastId();
			$this->db->query("UPDATE suppliers SET supplier_code = '" . $this->db->escape('AMZN' . $supplier_id) . "' WHERE supplier_id = '" . (int)$supplier_id . "'");

			if ($amazon_seller_id){
				$amazonSellerData = $this->SellerRetriever->getSeller($amazon_seller_id);

				$sellerData = $this->SellerRetriever->checkData($amazonSellerData);

				if ($sellerData){

					if (empty($sellerData['vat_number'])){
						$sellerData['vat_number'] = '';
					}

					echoLine('[Suppliers::addSupplier] Got information from Amazon: ' . $sellerData['name']);					
					$telephone 	= $this->guessSupplierPhone($sellerData);
					$email 		= $this->guessSupplierEmail($sellerData);

					$sellerData['email'] 		= $email;
					$sellerData['telephone'] 	= $telephone;

					$is_native 	= $this->checkIfSupplierIsNative($sellerData);

					foreach (['business_name', 'registration_number', 'business_type'] as $key)
					if (empty($sellerData[$key])){
						$sellerData[$key] = '';
					}

					if (!empty($sellerData['vat_number']) && !empty(substr($sellerData['vat_number'], 0, 2))){
						$this->db->query("UPDATE suppliers SET supplier_country = '" . $this->db->escape(substr($sellerData['vat_number'], 0, 2)) . "' WHERE supplier_id = '" . (int)$supplier_id . "'");
					} else {
						if ($is_native){
							$this->db->query("UPDATE suppliers SET supplier_country = '" . $this->db->escape($this->native_code) . "' WHERE supplier_id = '" . (int)$supplier_id . "'");
						}
					}

					$this->db->query("UPDATE suppliers SET
						store_link  		= '" . $this->db->escape($sellerData['store_link']) . "',
						business_name  		= '" . $this->db->escape($sellerData['business_name']) . "',
						registration_number = '" . $this->db->escape($sellerData['registration_number']) . "',
						vat_number  		= '" . $this->db->escape($sellerData['vat_number']) . "',
						business_type  		= '" . $this->db->escape($sellerData['business_type']) . "',
						about_this_seller  	= '" . $this->db->escape($sellerData['about_this_seller']) . "',
						detailed_information = '" . $this->db->escape($sellerData['detailed_information']) . "',
						is_native 			= '" . (int)$is_native . "', 
						telephone 			= '" . $this->db->escape($telephone) . "',
						email 				= '" . $this->db->escape($email) . "'
						WHERE supplier_id = '" . (int)$supplier_id . "'");
				}
			}
		}
	}
}