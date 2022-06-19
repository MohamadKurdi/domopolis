<?
	
	namespace hobotix\Amazon;
	
	class ProductsRetriever extends RainforestRetriever
	{

		
		const CLASS_NAME = 'hobotix\\Amazon\\ProductsRetriever';

		

		public function getProductsByAsin($asin){
			$results = [];
			$query = $this->db->query("SELECT product_id FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "')");

			foreach ($query->rows as $row){
				$results[] = $row['product_id'];
			}

			return $results;
		}
		










	}