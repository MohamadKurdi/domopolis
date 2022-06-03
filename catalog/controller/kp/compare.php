<?
	
	class ControllerKPCompare extends Controller {
		private $directory = DIR_EXPORT . 'competitors/';
		private $country = 'ru';
		private $delimiter = ',';
		private $mappingArray = array();
		
		private function echoLine($str){
			echo $str . PHP_EOL;	
		}
		
		private function format($price){
			$price = str_replace(' ', $price);
		}
		
		private function findProduct($sku){
			$q = $this->db->non_cached_query("SELECT product_id FROM product WHERE 
			status = 1
			AND product_id IN (SELECT product_id FROM product_description WHERE 1)
			AND product_id IN (SELECT product_id FROM product_to_store WHERE 1)
			AND
			(TRIM(sku) LIKE '" . $this->db->escape(trim($sku)) . "' OR 
			TRIM(sku) LIKE '" . $this->db->escape(trim('0' . $sku)) . "' OR
			TRIM(model) LIKE '" . $this->db->escape(trim($sku)) . "' OR 
			TRIM(ean) LIKE '" . $this->db->escape(trim($sku)) . "')");
			
			if ($q->num_rows) 
			return $q->row['product_id'];
			else 
			return false;
		}
		
		private function fillMappingArray(){
			
			$this->echoLine('[CMP] Заполняем массивы поиска...');
			
			$q = $this->db->non_cached_query("SELECT product_id, sku, model, ean FROM product WHERE 
			status = 1
			AND product_id IN (SELECT product_id FROM product_description WHERE 1)
			AND product_id IN (SELECT product_id FROM product_to_store WHERE 1)");
			
			$this->mappingArray['sku'] = array();
			$this->mappingArray['sku0'] = array();
			$this->mappingArray['skuCLR'] = array();
			
			$this->mappingArray['model'] = array();
			$this->mappingArray['ean'] = array();
			
			foreach ($q->rows as $row){
				$this->mappingArray['sku'][trim($row['sku'])] = $row['product_id'];				
				$this->mappingArray['sku0'][trim('0' . $row['sku'])] = $row['product_id'];				
				$this->mappingArray['skuCLR'][trim(str_replace(array(' ', '-', '.'), '', $row['sku']))] = $row['product_id'];
				
				$this->mappingArray['model'][trim($row['model'])] = $row['product_id'];
				$this->mappingArray['ean'][trim($row['ean'])] = $row['product_id'];
			}
		}
		
		private function findProductFast($sku){
			$sku = trim($sku);
			$skuCLR = trim(str_replace(array(' ', '-', '.'), '', $sku));
			
			if (isset($this->mappingArray['sku'][$sku])){
				return $this->mappingArray['sku'][$sku];
			}
			
			if (isset($this->mappingArray['sku0'][$sku])){
				return $this->mappingArray['sku0'][$sku];
			}
			
			if (isset($this->mappingArray['skuCLR'][$skuCLR])){
				return $this->mappingArray['skuCLR'][$skuCLR];
			}
			
			if (isset($this->mappingArray['model'][$sku])){
				return $this->mappingArray['model'][$sku];
			}
			
			if (isset($this->mappingArray['ean'][$sku])){
				return $this->mappingArray['ean'][$sku];
			}
			
			return false;
		}
		
		private function updateCompetitors($product_info, $url){				
			
			if ($this->country == 'ru'){
				$competitors = trim($product_info['competitors']);
			}
			
			if ($this->country == 'ua'){
				$competitors = trim($product_info['competitors_ua']);
			}
			
			
			if ($url){
				$lines = explode(PHP_EOL, $competitors);
				
				$add = true;
				foreach ($lines as $line){
					if (trim($line) == trim($url)){
						$add = false;
						break;
					}
				}
				
				if ($add){
					
					if (mb_strlen(str_replace(PHP_EOL, '', $competitors)) > 0){
						$competitors .= PHP_EOL;
					}
					
					$competitors .= trim($url);
					
					if ($this->country == 'ru'){
						$this->db->non_cached_query("UPDATE product SET competitors = '" . $this->db->escape($competitors) . "' WHERE product_id = '" . (int)$product_info['product_id'] . "'");
					}
					
					if ($this->country == 'ua'){
						$this->db->non_cached_query("UPDATE product SET competitors_ua = '" . $this->db->escape($competitors) . "' WHERE product_id = '" . (int)$product_info['product_id'] . "'");
					}
					
				}
			}			
		}
		
		private function findIndex($string, $field){
			
			$exploded = explode($this->delimiter, str_replace('"','',$string));
			
			$i = 0;
			foreach ($exploded as $name){
				if (trim($name) == trim($field)){
					$this->echoLine('[CMP] Нашли индекс поля ' . $field . ': ' . $i);
					return $i;
					break;
				}
				
				$i++;
			}
			
			$this->echoLine('[CMP] Нихуя не нашли индекс поля ' . $field);
			return false;
			
			
		}
		
		public function clear_supplier(){
			
			
			
			
		}
		
		
		public function compare($country = 'ru'){
			
			$this->country = $country;
			$this->directory = $this->directory . $country . '/';
			
			if(php_sapi_name()!=="cli"){
				die('cli only');	
			}
			
			$log = new Log('competitors.not_found.txt');
			
			$this->load->model('catalog/product');
			
			$files = scandir($this->directory);
			
			$this->fillMappingArray();
			
			foreach ($files as $file){
				if ($file != '.' && $file != '..'){
					
					$content = file ($this->directory . $file);
					$this->echoLine('[CMP] ' . $file);
					
					$sku_idx = $this->findIndex($content[0], '_SKU_');
					$url_idx = $this->findIndex($content[0], '_URL_');
					
					if (($sku_idx === false) || ($url_idx === false)){
						$this->echoLine('[CMP] не нашли индексы полей');
						break;
					}
					
					$t = count($content);
					$i = 0;
					$found = 0;
					$notfound = 0;
					foreach ($content as $line){
						$i++;
						
						$exploded = str_getcsv ($line, $this->delimiter);												
						
						$sku = $exploded[$sku_idx];
						$url = $exploded[$url_idx];
						
						if (!trim($sku)){
							$this->echoLine('[CMP] Нет SKU');
							continue;
						}
						
						if (!trim($url)){
							$this->echoLine('[CMP] Нет URL');
							continue;
						}											
						
						if ($product_id = $this->findProductFast($sku)){
							$product = $this->model_catalog_product->getProduct($product_id, false);
							$found++;
							
							$price = $product['price'];
							if ($product['special']){
								$price = $product['special'];
							}
							
							$kp_price = $this->currency->format($price, '', '', false);		
							
							$this->echoLine('[CMP '. $i . '/' . $t .'] Файл ' . $file . ', товар ' . $sku . ' найден, цена ' . $kp_price);
							
							$this->updateCompetitors($product, $url);
							
							} else {
							$notfound++;
							$log->write($file . ': ' . $sku);
							$this->echoLine('[CMP '. $i . '/' . $t .'] Файл ' . $file . ', ' . $sku . ' не найден');
						}						
					}
				}										
			}
			
		}		
		
	}					