<?

namespace hobotix\Amazon;

class PriceUpdaterQueue
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\PriceUpdaterQueue';
	
	private $db		= null;	
	private $config = null;


	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');		
	}

	public function getQueue(){
		$query = $this->db->query("SELECT * FROM odinass_product_queue WHERE 1");

		$results = [];
		foreach ($query->rows as $row){
			$results[] = $row['product_id'];
		}

		return $results;
	}

	public function cleanQueue(){
		$this->db->query("TRUNCATE odinass_product_queue");

		return $this;		
	}

	public function addProductIDToQueue($product_id){
		$this->db->query("INSERT IGNORE INTO odinass_product_queue SET product_id = '" . (int)$product_id . "'");		
	}

	public function addToQueue($product_identifier){
		if (is_numeric($product_identifier)){
			$this->addProductIDToQueue($product_identifier);
			return true;
		}

		$query = $this->db->query("SELECT product_id FROM product WHERE 
			asin LIKE ('" . $this->db->esape($product_identifier) . "') 
			OR ean LIKE ('" . $this->db->esape($product_identifier) . "') 
			OR sku LIKE ('" . $this->db->esape($product_identifier) . "')");

		foreach ($query->rows as $row){
			$this->addProductIDToQueue($row['product_id']);
		}
	}
}