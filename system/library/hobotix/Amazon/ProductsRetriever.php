<?
	
	namespace hobotix\Amazon;
	
	class ProductsRetriever extends RainforestRetriever
	{
		
		const CLASS_NAME = 'hobotix\\Amazon\\ProductsRetriever';	

		public function getProducts(){

			$result = [];
			$sql = "SELECT p.*, pd.name FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)  WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' AND added_from_amazon = 1 AND p.product_id NOT IN (SELECT product_id FROM product_amzn_data) AND (NOT ISNULL(p.asin) OR p.asin <> '')";

			$query = $this->db->ncquery($sql);

			foreach ($query->rows as $row){
				$result[] = [
					'product_id' 			=> $row['product_id'],
					'asin' 					=> $row['asin']								
				];
			}

			return $result;


		}	

		public function getProductsByAsin($asin){
			$results = [];
			$query = $this->db->query("SELECT product_id FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "')");

			foreach ($query->rows as $row){
				$results[] = $row['product_id'];
			}

			return $results;
		}

		public function getManufacturer($name){
			$query = $this->db->query("SELECT manufacturer_id FROM manufacturer_description WHERE name LIKE ('" . $this->db->escape($name) . "') AND language_id = '" . $this->config->get('config_rainforest_source_language_id') . "'");

			if ($query->num_rows){
				return $query->row['manufacturer_id'];
			} else {
				return false;
			}
		}

		public function getAttribute($name){
			$query = $this->db->query("SELECT attribute_id FROM atrribute_description WHERE name LIKE ('" . $this->db->escape($name) . "') AND language_id = '" . $this->config->get('config_rainforest_source_language_id') . "'");

			if ($query->num_rows){
				return $query->row['attribute_id'];
			} else {
				return false;
			}
		}
		

		public function addSimpleProductWithOnlyAsin($data) {			
			$this->db->query("INSERT INTO product SET 
					model 				= '" . $this->db->escape($data['asin']) . "', 
					asin 				= '" . $this->db->escape($data['asin']) . "', 
					image           	= '" . $this->db->escape($data['image']) . "', 
					added_from_amazon 	= '1', 
					stock_status_id 	= '" . $this->config->get('config_stock_status_id') . "',
					quantity 			= '0',
					status 				= '0',
					date_added 			= NOW()");

			$product_id = $this->db->getLastId();

			$this->db->query("DELETE FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");

			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['category_id'] . "', main_category = 1");
			
			$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
						
			foreach ($this->registry->get('languages') as $language_code => $language) {

				if ($language_code == $this->config->get('config_rainforest_source_language')){
					$name = $data['name'];	
					$translated = true;				
				} else {
					if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
						$name = $this->yandexTranslator->translate($data['name'], $this->config->get('config_rainforest_source_language'), $language_code, true);
						$translated = true;
					} else {
						$name = $data['name'];
						$translated = false;
					}
				}

				$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product_id . "', translated = '" . (int)$translated . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($name) . "'");
			}

			return $product_id;
		}








	}