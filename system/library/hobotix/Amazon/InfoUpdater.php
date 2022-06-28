<?

namespace hobotix\Amazon;

class InfoUpdater
{

	const CLASS_NAME = 'hobotix\\Amazon\\InfoUpdater';

	private $db;	
	private $config;
	private $lengthCache = false;
	private $weightCache = false;

	private $rfClient;

	public function __construct($registry, $rfClient){

		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->log = $registry->get('log');
		$this->rfClient = $rfClient;
		$this->setDimensionsCache();

	}


	public function setProductIsFilledFromAmazon($product_id){
		$this->db->query("UPDATE product SET filled_from_amazon = 1 WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateProductAmznData($product, $updateDimensions = true){
		
		$this->db->query("INSERT INTO product_amzn_data SET
			product_id = '" . (int)$product['product_id'] . "', 
			asin = '" . $this->db->escape($product['asin']) . "',
			json = '" . $this->db->escape($product['json']) . "'
			ON DUPLICATE KEY UPDATE
			asin = '" . $this->db->escape($product['asin']) . "',
			json = '" . $this->db->escape($product['json']) . "'");

		if ($updateDimensions){
			$this->parseAndUpdateProductDimensions($product['json']);
		}

		return $this;
	}

	public function setDimensionsCache(){
		if (!$this->weightCache) {
			$this->weightCache = [];

			$query = $this->db->query("SELECT * FROM weight_class WHERE 1");

			foreach ($query->rows as $row){
				$this->weightCache[$row['amazon_key']] = $row;
			}
		}

		if (!$this->lengthCache) {
			$this->lengthCache = [];

			$query = $this->db->query("SELECT * FROM length_class WHERE 1");

			foreach ($query->rows as $row){
				$this->lengthCache[$row['amazon_key']] = $row;
			}
		}
	}

	public function parseDimesionsString($string){
		$string = atrim($string);
		$exploded1 = explode(';', $string);

		if (count($exploded1) != 2){
			return false;
		}

		$exploded_length = explode('x', atrim($exploded1[0]));

		$exploded_weight_class = explode(' ', atrim($exploded1[1]));
		$exploded_length_class = explode(' ', atrim($exploded_length[2]));

		if (count($exploded_length) != 3 || count($exploded_weight_class) != 2 || count($exploded_length_class) != 2){
			return false;
		}

		$length_class_id = 0;
		$weight_class_id = 0;
		if (!empty($this->weightCache[atrim($exploded_weight_class[1])])){
			$weight_class_id = $this->weightCache[atrim($exploded_weight_class[1])]['weight_class_id'];
		}

		if (!empty($this->lengthCache[atrim($exploded_length_class[1])])){
			$length_class_id = $this->lengthCache[atrim($exploded_length_class[1])]['length_class_id'];
		}

		$weight = (float)atrim($exploded_weight_class[0]);
		if (!$weight_class_id){
			echoLine('Не найдена единица измерения веса: ' . atrim($exploded_weight_class[1]));
			$weight = 0;
		}

		$length = (float)atrim($exploded_length[0]);
		$width 	= (float)atrim($exploded_length[1]);
		$height = (float)atrim($exploded_length[2]);

		if (!$length_class_id){
			echoLine('Не найдена единица измерения размера: ' . atrim($exploded_length_class[1]));

			$length = 0;
			$width 	= 0;
			$height = 0;
		}

		return [
			'length' 			=> $length,
			'width' 			=> $width,
			'height' 			=> $height,
			'weight'			=> (float)atrim($exploded_weight_class[0]),
			'length_class_id' 	=> $length_class_id,
			'weight_class_id' 	=> $weight_class_id,
		];
		

	}

	public function parseAndUpdateProductDimensions($json){			

		if (!$json || empty($json['dimensions'])){
			return false;
		}

		if ($data = $this->parseDimesionsString($json['dimensions'])){			

		$this->db->query("UPDATE product SET
				length 					= '" . (float)$data['length'] . "',
				width					= '" . (float)$data['width'] . "',
				height					= '" . (float)$data['height'] . "',
				weight 					= '" . (float)$data['weight'] . "',
				length_class_id 		= '" . (int)$data['length_class_id'] . "',
				weight_class_id 		= '" . (int)$data['weight_class_id'] . "',
				pack_length 			= '" . (float)$data['length'] . "',
				pack_width				= '" . (float)$data['width'] . "',
				pack_height				= '" . (float)$data['height'] . "',
				pack_weight 			= '" . (float)$data['weight'] . "',
				pack_length_class_id 	= '" . (int)$data['length_class_id'] . "',
				pack_weight_class_id 	= '" . (int)$data['weight_class_id'] . "'
				WHERE asin = '" . $this->db->escape($json['asin']) . "'");

			return true;

		} else {

			echoLine($json['dimensions']);

		}

		return false;

	}

		//Работа с справочником товаров
	public function updateProductNotFoundOnAmazon($product_id){

		$this->db->query("UPDATE product SET amzn_not_found = 1 WHERE product_id = '" . $product_id . "'");			

		return $this;
		
	}

	public function updateProductAmazonLastSearch($product_id){
		$this->db->query("UPDATE product SET amzn_last_search = NOW() WHERE product_id = '" . $product_id . "'");				

		return $this;
	}	

	public function updateASINInDatabase($product){
		$this->db->query("UPDATE product SET asin = '" . $this->db->escape($product['asin']) . "' WHERE product_id = '" . $product['product_id'] . "'");				

		return $this;
	}						


	public function validateASINAndUpdateIfNeeded($product){

		if (empty($product['asin']) && empty($product['ean'])){
			return $product;
		}

		if (empty($product['asin'])){
			
			$rfRequests = [new \CaponicaAmazonRainforest\Request\ProductRequest($this->config->get('config_rainforest_api_domain_1'), null, ['customer_zipcode' => $this->config->get('config_rainforest_api_zipcode_1'), 'gtin' => $product['ean']])];
			$apiEntities = $this->rfClient->retrieveProducts($rfRequests);	

			if (!$apiEntities){
				return false;				
			}

			foreach ($apiEntities as $key => $rfProduct) {
				
				if ($rfProduct->getAsin()){

					$product['asin'] = $rfProduct->getAsin();						
					$this->updateASINInDatabase($product);

				}					
			}

		}


		return $product;

	}






}		