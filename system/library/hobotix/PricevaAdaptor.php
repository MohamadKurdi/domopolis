<?

namespace hobotix;


final class PricevaAdaptor
{

	private $db;
	private $config;


	private $oldLogicCompetitorFieldMapping = [
		0 => 'competitors',
		1 => 'competitors_ua'
	];

	private $pricevaRelevance = [
    0 => 'не оценивалась',
    1 => 'актуально',
    2 => 'средне актуально',
    3 => 'устарело',
	];

	public function __construct($registry){

		$this->config = $registry->get('config');
		$this->db = $registry->get('db');

		if ($this->config->get('config_priceva_competitor_field_mapping')){
			echoLine('[PricevaAdaptor] Using field ' . $this->config->get('config_priceva_competitor_field_mapping') . ' for competitors urls', 'i');

			 $this->oldLogicCompetitorFieldMapping[0] = trim($this->config->get('config_priceva_competitor_field_mapping'));
		}
	}

	public function getPricevaRelevance(){
		return $this->pricevaRelevance;
	}

	public function getOldLogicCompetitorFieldMapping() {
		return $this->oldLogicCompetitorFieldMapping;
	}

	public function cleanTables($store_id){
		$this->db->query("DELETE FROM priceva_data WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM priceva_sources WHERE store_id = '" . (int)$store_id . "'");
	}

	public function getPricevaData($store_id, $product_id){
		$query = $this->db->query("SELECT * FROM priceva_sources WHERE store_id = '" . (int)$store_id . "' AND product_id = '" . (int)$product_id . "' AND active = 1");

		return $query->rows;
	}

	private function addCompetitorURLS($store_id, $product_id, &$competitors){
		if (!empty($this->oldLogicCompetitorFieldMapping[$store_id])){

			$query = $this->db->query("SELECT `" . $this->oldLogicCompetitorFieldMapping[$store_id] . "` as competitors FROM product WHERE product_id = '" . (int)$product_id . "'");

			if ($query->num_rows){
				$existent_competitors = explode(PHP_EOL, $query->row['competitors']);			
			} else {
				$existent_competitors = [];			
			}

			$competitors1 = array_unique(array_merge($existent_competitors, $competitors));
			$competitors1 = implode(PHP_EOL, $competitors1);

			$this->db->query("UPDATE product SET `" . $this->oldLogicCompetitorFieldMapping[$store_id] . "` = '" . $this->db->escape($competitors1) . "' WHERE product_id = '" . (int)$product_id . "'");

		}
	}

	private function recalculateDiscountOnProduct(&$source){

		if (!empty($source->discount) && (float)$source->discount){

			echoLine('Скидка: ' . $source->discount);
			echoLine('Цена: ' . $source->price);

			if ($source->discount_type == 1){
				$tmp = $source->price;
				$source->price += $source->discount;
				$source->discount = $tmp;
			}

			if ($source->discount_type == 0){

			}

			echoLine('Новая скидка: ' . $source->discount);
			echoLine('Новая цена: ' . $source->price);
		}

		return $source;
	}


	public function updateProductData($store_id, $products){

		foreach ($products as $product){
			
			echoLine('[PricevaAdaptor] Товар ' . $product->client_code . ', ' . $product->name);

			$this->db->query("INSERT INTO priceva_data SET
				store_id 			= '" . (int)$store_id . "',
				product_id			= '" . (int)$product->client_code . "',
				name				= '" . $this->db->escape($product->name) . "',
				articul				= '" . $this->db->escape($product->articul) . "',
				category_name		= '" . $this->db->escape($product->category_name) . "',
				brand_name			= '" . $this->db->escape($product->brand_name) . "',
				default_currency	= '" . $this->db->escape($product->default_currency) . "',
				default_price		= '" . (float)($product->default_price) . "',
				default_available	= '" . (int)$product->default_available . "',
				default_discount	= '" . (float)($product->default_discount) . "',
				repricing_min		= '" . (float)($product->repricing_min) . "'
				ON DUPLICATE KEY UPDATE
				name				= '" . $this->db->escape($product->name) . "',
				articul				= '" . $this->db->escape($product->articul) . "',
				category_name		= '" . $this->db->escape($product->category_name) . "',
				brand_name			= '" . $this->db->escape($product->brand_name) . "',
				default_currency	= '" . $this->db->escape($product->default_currency) . "',
				default_price		= '" . (float)($product->default_price) . "',
				default_available	= '" . (int)$product->default_available . "',
				default_discount	= '" . (float)($product->default_discount) . "',
				repricing_min		= '" . (float)($product->repricing_min) . "'");

			$competitors = [];	
			if (!empty($product->sources)){			

				foreach ($product->sources as $source){

					$source = $this->recalculateDiscountOnProduct($source);

					$this->db->query("INSERT IGNORE INTO priceva_sources SET
						store_id 			= '" . (int)$store_id . "',
						product_id			= '" . (int)$product->client_code . "',
						url 				= '" . $this->db->escape($source->url) . "',
						url_md5				= '" . md5($this->db->escape($source->url)) . "',
						company_name		= '" . $this->db->escape($source->company_name) . "',
						region_name			= '" . $this->db->escape($source->region_name) . "',
						active				= '" . (int)$source->active . "',
						status				= '" . (int)$source->status . "',
						in_stock			= '" . (int)(!empty($source->in_stock)?$source->in_stock:'0') . "',						
						currency			= '" . $this->db->escape(!empty($source->currency)?$source->currency:'') . "',
						last_check_date		= '" . (!empty($source->last_check_date)?date('Y-m-d H:i:s', $source->last_check_date):'0000-00-00 00:00:00') . "',
						relevance_status	= '" . (int)(!empty($source->relevance_status)?$source->relevance_status:'0') . "',
						price				= '" . (float)(!empty($source->price)?$source->price:'0') . "',
						discount 			= '" . (float)(!empty($source->discount)?$source->discount:'0') . "',
						discount_type 		= '" . (int)(!empty($source->discount_type)?$source->discount_type:'0') . "'");

						$competitors[] = $source->url;

					}					
			}

			$this->addCompetitorURLS($store_id, $product->client_code, $competitors);			

			}

		}

	}