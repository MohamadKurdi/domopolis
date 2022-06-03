<?
	
	class ControllerFeedMultiSearch extends Controller {
		private $feedsDirectory = DIR_REFEEDS . 'multisearch/';
		private $eol = PHP_EOL;
		
		private function convert($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		}
		
		private function rms2($st, $uuml = false, $rmspace = false)
		{
			$st = str_replace('&Auml;','Ä',$st);
			$st = str_replace('&auml;','ä',$st);
			$st = str_replace('&Uuml;','Ü',$st);
			$st = str_replace('&uuml;','ü',$st);
			$st = str_replace('&Ouml;','Ö',$st);
			$st = str_replace('&ouml;','ö',$st);
			$st = str_replace('&szlig;','ß',$st);
			$st = str_replace('&Oslash;','Ø',$st);
			
			if ($uuml){
				return $st;
			}
			
			$st = str_replace(',','',$st);
			$st = str_replace('’','',$st);
			$st = str_replace('"','',$st);
			$st = str_replace(')','',$st);
			$st = str_replace('(','',$st);
			$st = str_replace('.','',$st);
			$st = str_replace('+','',$st);
			$st = str_replace('*','',$st);
			$st = str_replace('“','',$st);
			$st = str_replace('”','',$st);
			$st = str_replace('&#13;','',$st);
			$st = str_replace('\r\n','',$st);
			$st = str_replace("\x13",'', $st);			
			$st = str_replace('&quot;','-',$st);
			$st = str_replace('&nbsp;',' ',$st);
			$st = str_replace('&amp;','and',$st);
			$st = str_replace('&deg;','°',$st);
			$st = str_replace('&','',$st);
			$st = str_replace('«','',$st);
			$st = str_replace('»','',$st);
			$st = str_replace('.','',$st);
			$st = str_replace('/','-',$st);
			$st = str_replace('\\','-',$st);
			$st = str_replace('%','-',$st);
			$st = str_replace('№','-',$st);
			$st = str_replace('#','-',$st);
			$st = str_replace('_','-',$st);
			$st = str_replace('–','-',$st);
			$st = str_replace('---','-',$st);
			$st = str_replace('--','-',$st);
			$st = str_replace('\'','',$st);
			$st = str_replace('!','',$st);
			
			return html_entity_decode($st, ENT_COMPAT, 'UTF-8');
		}
		
		private function getCategories() {
			$query = $this->db->non_cached_query("SELECT cd.name, c.category_id, c.parent_id, c.sort_order FROM category c 
			LEFT JOIN category_description cd ON (c.category_id = cd.category_id) 
			LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) 
			WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND c.status = '1'
			AND c.sort_order <> '-1'");
			
			return $query->rows;
		}
		
		private function getProductsFast(){
			
			$query = $this->db->non_cached_query("SELECT
			p.product_id, GROUP_CONCAT(p2c.category_id SEPARATOR ',') as category_ids FROM product p
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN product_to_category AS p2c ON (p.product_id = p2c.product_id)
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)
			WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND p.date_available <= NOW()
			AND p.price > 0
			AND p.status = '1'
			AND p.is_virtual = '0'				
			GROUP BY p.product_id ORDER BY product_id");
			
			$i = 0;
			$total = count($query->rows);
			$this->echoLine('[MS]' . $total . ' товаров');
			
			$result = array();
			foreach ($query->rows as $row){			
				
				if ($i % 1000 == 0){
					$this->echoSimple($i.'...');
				}
				
				$result[$row['product_id']] = $this->model_catalog_product->getProduct($row['product_id']);
				$result[$row['product_id']]['categories'] = $row['category_ids'];
				$result[$row['product_id']]['attributes'] = $this->getProductAttributes($row['product_id']);
				$i++;
			}
			
			return $result;
		}
		
		public function getProductAttributes($product_id) {
			$query = $this->db->query("SELECT pa.attribute_id, pa.text, ad.name
			FROM product_attribute pa
			LEFT JOIN attribute_description ad ON (pa.attribute_id = ad.attribute_id)
			WHERE pa.product_id = '" . (int)$product_id . "'
			AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY pa.attribute_id");
			
			return $query->rows;
		}
		
		private function echoLine($line){
			echo $line . PHP_EOL;
		}
		
		private function echoSimple($line){
			echo $line;
		}
		
		private function getProductCategories($product_id){
			
			
		}
		
		public function generate(){
			ini_set('memory_limit', '3G');
			ini_set('display_error', 'On');		
			
			$this->load->model('localisation/currency');
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			
			$file = $this->feedsDirectory . 'multisearch.full.' . $this->config->get('config_store_id') . '.xml';
			
			$this->echoLine('[MS]' . $file);
			
			$multisearch = fopen($file, 'w+');
			fwrite($multisearch, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
			fwrite($multisearch, '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . PHP_EOL);
			fwrite($multisearch, '<yml_catalog date="' . date('Y-m-d H:i') . '">' . PHP_EOL);
			fwrite($multisearch, '<shop>' . PHP_EOL);
			
			fwrite($multisearch, '<categories>' . PHP_EOL);
			
			foreach ($this->getCategories() as $category) {			
				fwrite($multisearch, "<category id='" . (int)$category['category_id'] . "' ordering='" .  $category['sort_order'] . "' parentID = '" . (int)$category['parent_id'] . "' url='" . $this->url->link('product/category', 'category_id='. (int)$category['category_id']) . "'>" . $category['name'] . "</category>" . PHP_EOL);
			}
			
			fwrite($multisearch, '</categories>' . PHP_EOL);
			
			
			fwrite($multisearch, '<currencies>' . PHP_EOL);
			
			foreach ($this->model_localisation_currency->getCurrencies() as $currency) {			
				fwrite($multisearch, "<currency id='" . $currency['code'] . "' rate='" . number_format($this->currency->real_convert(1, $currency['code'],  $this->config->get('config_regional_currency')), 2, '.', '') . "' />" . PHP_EOL);
			}
			
			fwrite($multisearch, '</currencies>' . PHP_EOL);
			
			fwrite($multisearch, '<offers>' . PHP_EOL);
			
			
			$i = 0;
			$this->echoLine('[MS] Записываем в фид');
			foreach ($products = $this->getProductsFast() as $product){
				$stock_data = $this->model_catalog_product->parseProductStockData($product);
				
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 200, 200);
					} else {
					$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 200, 200);
				}
				
				$availability = 'true';
				if ($product['quantity'] == 0) {
					$availability = 'false';
				}
				
				if ($product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')){
					$availability = 'false';
				}
				
				if ($product['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')){
					$availability = 'false';
				}
				
				if ($i % 1000 == 0){
					$this->echoSimple($i .', '. $this->convert(memory_get_usage(true)) . '...');
				}
				
				fwrite($multisearch, '<offer id="'. $product['product_id'] .'" available="' . $availability . '">' . PHP_EOL);
				fwrite($multisearch, '<name><![CDATA[' . $this->rms2($product['name']) . ']]></name>' . PHP_EOL);
				fwrite($multisearch, '<vendor><![CDATA[' . $this->rms2($product['manufacturer']) . ']]></vendor>' . PHP_EOL);
				fwrite($multisearch, '<currencyId><![CDATA[' . $this->config->get('config_regional_currency') . ']]></currencyId>' . PHP_EOL);		
				fwrite($multisearch, '<url><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></url>' . PHP_EOL);
				fwrite($multisearch, '<picture><![CDATA[' . $image . ']]></picture>' . PHP_EOL);
				
				foreach (explode(',', $product['categories']) as $category_id){
					fwrite($multisearch, '<categoryId>' . $category_id . '</categoryId>' . PHP_EOL);
				}
				
				if (strtotime($product['date_added']) >= strtotime('2000-01-01')){
					fwrite($multisearch, '<createdAt>'. date('Y-m-d', strtotime($product['date_added'])) .'</createdAt>' . PHP_EOL);
				}
				
				if ($product['sort_order']){
					//	fwrite($multisearch, '<ordering>' . $product['sort_order'] . '</ordering>' . PHP_EOL);
				}
				
				if ($product['current_in_stock']){
					fwrite($multisearch, '<presence><![CDATA[Склад]]></presence>' . PHP_EOL);
					} elseif ($availability == 'true'){
					fwrite($multisearch, '<presence><![CDATA[Есть в наличии]]></presence>' . PHP_EOL);
					} elseif ($product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')){
					fwrite($multisearch, '<presence><![CDATA[Нет в наличии]]></presence>' . PHP_EOL);
					} elseif ($product['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')){
					fwrite($multisearch, '<presence><![CDATA[Наличие уточняйте]]></presence>' . PHP_EOL);
				}
				
				if ($product['new']){
					fwrite($multisearch, '<label>Новинка</label>' . PHP_EOL);
				}
				
				if ($product['additional_offer_count']){
					fwrite($multisearch, '<label>Акция</label>' . PHP_EOL);
				}
				
				if ($product['attributes']){
					foreach($product['attributes'] as $attribute){
						fwrite($multisearch, "<param filter='true' name='" . $this->rms2($attribute['name']) . "'><![CDATA[" . $this->rms2($attribute['text']) . "]]></param>" . PHP_EOL);
					}
				}
				
				if ($product['sku']){
					fwrite($multisearch, '<vendorCode><![CDATA[' . $this->rms2($product['sku']) . ']]></vendorCode>' . PHP_EOL);
					} elseif ($product['ean']){
					fwrite($multisearch, '<vendorCode><![CDATA[' . $this->rms2($product['ean']) . ']]></vendorCode>' . PHP_EOL);
					} elseif ($product['model']) {
					fwrite($multisearch, '<vendorCode><![CDATA[' . $this->rms2($product['model']) . ']]></vendorCode>' . PHP_EOL);
					} else {
					fwrite($multisearch, '<vendorCode><![CDATA[' . $this->rms2($product['product_id']) . ']]></vendorCode>' . PHP_EOL);
				}
				
				//AVAILABLE CODES
				$models = array();
				$models[] = str_replace(' ', '', $product['model']);
				$models[] = str_replace('-', '', $product['model']);
				$models[] = str_replace('.', '', $product['model']);
				$models[] = str_replace('.', ' ', $product['model']);
				$models[] = str_replace('/', '', $product['model']);
				$models[] = preg_replace("([^0-9])", "", $product['model']);
				$models[] = $product['sku'];
				$models[] = $product['ean'];
				$models[] = $product['product_id'];
				$models = array_unique($models);
				
				foreach ($models as $model){
					$model = trim($this->rms2($model));
					
					if ($model) {
						fwrite($multisearch, '<code><![CDATA[' . $model . ']]></code>' . PHP_EOL);
					}
				}
				
				if ($product['special'] > 0){
					fwrite($multisearch, '<label>Скидка</label>' . PHP_EOL);
					fwrite($multisearch, '<price><![CDATA[' . $this->currency->format($product['special'], '', '', false) . ']]></price>' . PHP_EOL);	
					fwrite($multisearch, '<oldprice><![CDATA[' . $this->currency->format($product['price'], '', '', false) . ']]></oldprice>' . PHP_EOL);
					} else {
					fwrite($multisearch, '<price><![CDATA[' . $this->currency->format($product['price'], '', '', false) . ']]></price>' . PHP_EOL);
				}
				
				fwrite($multisearch, '</offer>' . PHP_EOL);
				
				$i++;
			}
			
			
			fwrite($multisearch, '</offers>' . PHP_EOL);
			fwrite($multisearch, '</shop>' . PHP_EOL);
			fwrite($multisearch, '</yml_catalog>' . PHP_EOL);

			fclose($multisearch);
			
			$this->echoLine('[MS] End');
			
			
		}
		
		
		
	}
