<?
	
	namespace hobotix\Amazon;
	
	class CategoryRetriever extends RainforestRetriever
	{
						
		const CLASS_NAME = 'hobotix\\Amazon\\CategoryRetriever';

		private $testCategory = false;

		public function checkSynced(){
			$query = $this->db->query("SELECT COUNT(DISTINCT c.category_id) as total FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id)  WHERE cd.language_id = '" . $this->config->get('config_language_id') . "' AND c.amazon_sync_enable = 1 AND c.amazon_final_category = 1 AND LENGTH(c.amazon_category_id) > 0 AND (c.amazon_last_sync = '0000-00-00 00:00:00' OR DATE(c.amazon_last_sync) <= DATE('" . date('Y-m-d', strtotime('-' . $this->config->get('config_rainforest_category_update_period') . ' day')) . "')) AND amazon_synced = 0");

			echoLine('[CategoryRetriever::checkSynced] There is ' . $query->row['total'] . ' categories in current sync iteration', 'i');

			if ($query->row['total'] == 0){
				echoLine('[CategoryRetriever::checkSynced] All categories synced in last iteration, starting new iteration', 's');
				$this->setAllCategoriesNotSynced();
			}
		}

		public function setAllCategoriesNotSynced(){
			$this->db->query("UPDATE category SET amazon_synced = 0 WHERE 1");			
		}

		public function setCategorySynced($category_id){
			$this->db->query("UPDATE category SET amazon_synced = 1 WHERE category_id = '" . (int)$category_id . "'");			
		}		

		public function getCategoriesWordsToParse(){
			$sql = "SELECT * FROM category_search_words csw ";
			$sql .= " LEFT JOIN category_description cd ON (csw.category_id = cd.category_id) ";
			$sql .= "	WHERE cd.language_id = '" . $this->config->get('config_language_id') . "'";
			$sql .= "	AND category_word_type <> 'disabled'";
			$sql .= "	AND ( ";
			$sql .= " (csw.category_word_last_search = '0000-00-00 00:00:00' OR DATE(csw.category_word_last_search) <= DATE('" . date('Y-m-d', strtotime('-' . $this->config->get('config_rainforest_category_queue_update_period') . ' day')) . "')) ";
			$sql .= "	OR (category_word_total_pages > 0 AND category_word_pages_parsed < category_word_total_pages) ";
			$sql .= " ) ";
			$sql .= "	ORDER BY category_word_last_search DESC ";
			$sql .= "	LIMIT " . \hobotix\RainforestAmazon::categoryWordsParserLimit . "";

			$query = $this->db->query($sql);

			return $query->rows;
		}

		public function getCategorySearchWordInfo($category_search_word_id){
			$query = $this->db->query("SELECT * FROM category_search_words WHERE category_search_word_id = '" . (int)$category_search_word_id . "'");

			return $query->row;
		}

		public function setCategorySearchWordTotalProducts($category_search_word_id, $total_products){
			$this->db->query("UPDATE category_search_words SET category_word_total_products = '" . (int)$total_products . "' WHERE category_search_word_id = '" . (int)$category_search_word_id . "'");

			return $this;
		}

		public function setCategorySearchWordTotalPages($category_search_word_id, $total_pages){
			$this->db->query("UPDATE category_search_words SET category_word_total_pages = '" . (int)$total_pages . "' WHERE category_search_word_id = '" . (int)$category_search_word_id . "'");

			return $this;
		}

		public function setCategorySearchWordLastScan($category_search_word_id){
			$this->db->query("UPDATE category_search_words SET category_word_last_search = NOW() WHERE category_search_word_id = '" . (int)$category_search_word_id . "'");

			return $this;
		}

		public function setCategorySearchWordPagesParsed($category_search_word_id, $pages_parsed){
			$this->db->query("UPDATE category_search_words SET category_word_pages_parsed = '" . (int)$pages_parsed . "' WHERE category_search_word_id = '" . (int)$category_search_word_id . "'");

			return $this;
		}

		public function setCategorySearchWordPagesParsedPlusOne($category_search_word_id, $pages_parsed = 1){
			$this->db->query("UPDATE category_search_words SET category_word_pages_parsed = category_word_pages_parsed + '" . (int)$pages_parsed . "' WHERE category_search_word_id = '" . (int)$category_search_word_id . "'");

			return $this;
		}

		public function updateCategorySearchWordProductAddedPlus($category_search_word_id, $product_plus = 1){
			$this->db->query("UPDATE category_search_words SET category_word_product_added = category_word_product_added + " . (int)$product_plus . " WHERE category_search_word_id = '" . (int)$category_search_word_id . "'");

			return $this;
		}

		public function getCategories(){
			$result = [];

			$sql = "SELECT c.*, cd.name FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id)  WHERE cd.language_id = '" . $this->config->get('config_language_id') . "' AND c.amazon_sync_enable = 1 AND c.amazon_final_category = 1 AND LENGTH(c.amazon_category_id) > 0 AND (c.amazon_last_sync = '0000-00-00 00:00:00' OR DATE(c.amazon_last_sync) <= DATE('" . date('Y-m-d', strtotime('-' . $this->config->get('config_rainforest_category_update_period') . ' day')) . "')) AND amazon_synced = 0";

			if ($this->testCategory){
				$sql .= " AND c.category_id = " . (int)$this->testCategory;
			}

			$sql .= " ORDER BY RAND() LIMIT " . (int)\hobotix\RainforestAmazon::categoryParserLimit;
			

			$query = $this->db->ncquery($sql);

			foreach ($query->rows as $row){
				$result[$row['category_id']] = [
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

		public function getCategoriesFromAmazonAsync($categories){

			$multi = curl_multi_init();
			$channels 	= [];
			$results 	= [];
			
			foreach ($categories as $category){

				$options = [
					'type' 			=> \hobotix\RainforestAmazon::rainforestTypeMapping[$this->config->get('config_rainforest_category_model')],
					'category_id' 	=> $category['amazon_category_id'],
					'sort_by'		=> 'most_recent',
					'page'			=> $category['page']
				];

				$channels[$category['category_id']] = $this->createRequest($options);	
				$results[$category['category_id']] = [];
				curl_multi_add_handle($multi, $channels[$category['category_id']]);									
			}			
			
			$running = null;
			do {
				curl_multi_exec($multi, $running);
			} while ($running);
			
			foreach ($channels as $channel) {
				curl_multi_remove_handle($multi, $channel);
			}
			curl_multi_close($multi);
			
			foreach ($channels as $category_id => $channel) {
				$results[$category_id] = $this->parseResponse(curl_multi_getcontent($channel));				
			}
			
			
			return $results;
		}
	}