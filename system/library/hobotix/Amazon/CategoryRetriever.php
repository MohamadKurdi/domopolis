<?
	
	namespace hobotix\Amazon;
	
	class CategoryRetriever extends RainforestRetriever
	{
						
		const CLASS_NAME = 'hobotix\\Amazon\\CategoryRetriever';

		public function getCategories(){
			$result = [];

			$sql = "SELECT c.*, cd.name FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id)  WHERE cd.language_id = '" . $this->config->get('config_language_id') . "' AND c.status = 1 AND c.amazon_sync_enable = 1 AND c.amazon_final_category = 1 AND LENGTH(c.amazon_category_id) > 0 AND (c.amazon_last_sync = '0000-00-00 00:00:00' OR DATE(c.amazon_last_sync) < DATE('" . date('Y-m-d', strtotime('-' . $this->config->get('config_rainforest_category_update_period') . ' day')) . "'))";

			//$this->log->debug($sql);

			$query = $this->db->ncquery($sql);

			foreach ($query->rows as $row){
				$result[] = [
					'category_id' 			=> $row['category_id'],
					'amazon_category_id' 	=> $row['amazon_category_id'],
					'amazon_category_name'	=> $row['amazon_category_name'],
					'amazon_last_sync'		=> $row['amazon_last_sync'],
					'amazon_fulfilled'		=> ($row['amazon_last_sync'] != '0000-00-00 00:00:00'),
					'name'					=> $row['name']						
				];
			}

			return $result;
		}

		public function setLastCategoryUpdateDate($category_id){
			$this->db->query("UPDATE category SET amazon_last_sync = NOW() WHERE category_id = '" . (int)$category_id . "'");
			return $this;
		}


		public function getCategoryFromAmazon($params = []){
			$this->checkIfPossibleToMakeRequest();

			$options = [
				'type' 			=> \hobotix\RainforestAmazon::rainforestTypeMapping[$this->config->get('config_rainforest_category_model')],
				'category_id' 	=> $params['amazon_category_id'],
				'sort_by'		=> 'most_recent',
				'page'			=> $params['page']

			];

			return $this->doRequest($options);
		}

	}