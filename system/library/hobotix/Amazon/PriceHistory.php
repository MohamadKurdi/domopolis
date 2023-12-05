<?

namespace hobotix\Amazon;

class PriceHistory
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\PriceHistory';
	
	private $db;	
	private $config;


	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');		
	}

	public function clearHistory($asin){
		$this->db->query("DELETE FROM product_offers_history WHERE asin = '" . $this->db->escape($asin) . "' AND DATE(date_added) <= '" . $this->db->escape(date('Y-m-d', strtotime('-6 month'))) . "'");		
	}

	public function getOffersHistory($asin){
		$query = $this->db->query("SELECT * FROM product_offers_history WHERE asin = '" . $this->db->escape($asin) . "' ORDER BY date_added DESC");		

		return $query->rows;
	}

	private function addBestOfferHistoryBugEcho($data){
		//echoLine('[PriceHistory::addBestOfferHistoryBugEcho] Got Bug in history adding!', 'e');
		return true;
	}

	public function addBestOfferHistory($data){		
		if (empty($data['amazon_best_price'])){
			return $this->addBestOfferHistoryBugEcho($data);
		}

		if (empty($data['price'])){
			return $this->addBestOfferHistoryBugEcho($data);
		}

		if (empty($data['offer_data'])){
			return $this->addBestOfferHistoryBugEcho($data);
		}

		if (empty($data['original_offer_data'])){
			return $this->addBestOfferHistoryBugEcho($data);
		}

		$this->clearHistory($data['asin']);

		$this->db->query("INSERT INTO product_offers_history SET 
			asin 				= '" . $this->db->escape($data['asin']) . "',
			store_id 			= '" . (int)$data['store_id'] . "',
			date_added  		= NOW(),
			original_offer_data = '" . $this->db->escape(json_encode($data['original_offer_data'])) . "',
			offer_data  		= '" . $this->db->escape(json_encode($data['offer_data'])) . "',
			weight 				= '" . (float)$data['weight'] . "',
			amazon_best_price  	= '" . (float)$data['amazon_best_price'] . "',
			price 				= '" . (float)$data['price'] . "',
			costprice 			= '" . (float)$data['costprice'] . "',
			profitability 		= '" . (float)$data['profitability'] . "',
			skipped 			= '" . $this->db->escape($data['skipped']) . "'");
	}
}