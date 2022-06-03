<?
	class ControllerApiYMLParser extends Controller { 
		private $models;
		private $justmodels;
		
		public function __construct($registry){
			
			parent::__construct($registry);
			
			$this->getModelArray();
			
		}
		
		private function getModelArray(){
			
			$query = $this->db->query("SELECT
			DISTINCT product_id, model, m.name as manufacturer 
			FROM product p
			LEFT JOIN manufacturer m ON m.manufacturer_id = p.manufacturer_id
			WHERE is_virtual = 0 AND stock_product_id = 0"
			);
			
			foreach ($query->rows as $row){
				$this->models[trim($row['model']).':'.trim($row['manufacturer'])] = (int)$row['product_id'];
				$this->justmodels[trim($row['model'])] = (int)$row['product_id'];
			}
		}
		
		private function getParamValue($data, $param){
			
			foreach ($data as $param_value){
				if ($param_value["@attributes"]["name"] == $param){
					return $param_value['@value'];
				}				
			}
			
			return false;
		}				
		
		private function findProduct($data, $return_full = false){
			$this->load->model('catalog/product');
			
			if (!empty($data['model']) && !empty($data['manufacturer'])){
				
				$search_string = trim($data['model']).':'.trim($data['manufacturer']);
				
				if (isset($this->models[$search_string])){
					if ($return_full){
						return $this->model_catalog_product->getProduct($this->models[$search_string]);
						} else {
						return $this->models[$search_string];
					}
				}
				
				} elseif (!empty($data['model'])){
				
				$search_string = trim($data['model']);
				
				if (isset($this->justmodels[$search_string])){
					if ($return_full){
						return $this->model_catalog_product->getProduct($this->justmodels[$search_string]);
						} else {
						return $this->justmodels[$search_string];
					}
				}							
				
			}
			
			return false;
		}
		
		public function parseYML($yml_path, $supplier_code, $params){
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			
			require_once(DIR_SYSTEM . 'library/XML2Array.php');
			$xml2array = new XML2Array();
			
			$log_yml = new Log('supplier_'. $supplier_code .'.txt');			
			$log_yml->write('> Начало загрузки');
			echo '> Начало загрузки' . PHP_EOL;
			
			$yml = file_get_contents($yml_path);
			$log_yml->write('> Получили файл');
			echo '> Получили файл' . PHP_EOL;
			
			//	$yml = mb_convert_encoding($yml, "utf-8", "windows-1251");
			//	$yml = htmlspecialchars_decode($yml);
			
			try {
				$yml = $xml2array->createArray($yml);				
				} catch (Exception $e){
				die ('Ошибка разбора XML. ' . $e->getMessage());
			}
			
			$log_yml->write('> Разобрали XML2Array');
			echo '> Разобрали XML2Array' . PHP_EOL;
			
			echo 'Поставщик: ' . $yml['yml_catalog']['shop']['name'] . PHP_EOL;
			if ($params['action'] == 'update') {
				
				$picfile = fopen(DIR_SYSTEM . 'temp/' . $supplier_code . SITE_NAMESPACE . 'images.txt', 'w+');
				
				foreach($yml['yml_catalog']['shop']['offers']['offer'] as $offer){
					
					if (isset($params['brands'])) {
						
						foreach ($params['brands'] as $brand){
							
							//если есть такой бренд у поставщика
							if ($offer['vendor'] == $brand['supplier_name']){
								
								if ($product = $this->findProduct(array('model' => $offer['vendorCode'], 'manufacturer' => $brand['catalog_name']), true)){
									
									echo '>> Нашли товар ' . $offer['vendor'] . ' :: ' . $offer['vendorCode'] .' '. $offer['name'] . ' :: ' . $product['name'] . PHP_EOL;
									
									
									$sp_id = isset($offer["@attributes"]["id"])?$offer["@attributes"]["id"]:0;
									
									//запишем в табличку								
									$this->db->query("
									INSERT INTO local_supplier_products SET 
									supplier_id = '" . (int)$params['supplier_id'] . "',
									supplier_product_id = '" . (int)$sp_id . "',
									product_id = '" . (int)$product['product_id'] . "',
									product_model = '" . $this->db->escape($product['model']) . "',
									product_ean = '" . $this->getParamValue($offer['param'], "ШтрихКод") . "',
									price = '" . (float)($offer['price'] * 0.6) . "',
									price_recommend = '" . (float)$offer['price'] . "',
									currency = '" . $this->db->escape($offer['currencyId']) . "',
									stock = '" . $this->getParamValue($offer['param'], "stock") . "',
									product_xml = '" . base64_encode(serialize($offer)) . "'
									ON DUPLICATE KEY UPDATE
									supplier_product_id = '" . (int)$sp_id . "',
									product_model = '" . $this->db->escape($product['model']) . "',
									product_ean = '" . $this->getParamValue($offer['param'], "ШтрихКод") . "',
									price = '" . (float)($offer['price'] * 0.6) . "',
									price_recommend = '" . (float)$offer['price'] . "',
									currency = '" . $this->db->escape($offer['currencyId']) . "',
									stock = '" . $this->getParamValue($offer['param'], "stock") . "',
									product_xml = '" . base64_encode(serialize($offer)) . "'
									");
									
									if ((int)$this->getParamValue($offer['param'], "stock") > 0){
										$this->db->query("INSERT IGNORE INTO product_price_national_to_store SET
										product_id = '" . (int)$product['product_id'] . "',
										store_id = 0,
										price = '" . (float)$offer['price'] . "',
										special = 0,
										currency = '" . $this->db->escape($product['currency']) . "'
										ON DUPLICATE KEY UPDATE
										price = '" . (float)$offer['price'] . "',
										special = 0
										");
										
										echo '[i] Установили РРЦ ' . $offer['price'] . ' / ' . $this->getParamValue($offer['param'], "stock") . PHP_EOL;
										
										$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_stock_status_id') . "' WHERE product_id = '" . (int)$product['product_id'] . "'");										
										echo '[i] Статус товара ' . $this->config->get('config_stock_status_id') . ' / в наличии' . PHP_EOL;
										
										} else {
										
										$this->db->query("DELETE FROM product_price_national_to_store WHERE 
										product_id = '" . (int)$product['product_id'] . "' AND
										store_id = 0								
										");
										
										echo '[i] Удалили РРЦ ' . PHP_EOL;
										
										$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_not_in_stock_status_id') . "' WHERE product_id = '" . (int)$product['product_id'] . "'");										
										echo '[i] Статус товара ' . $this->config->get('config_stock_status_id') . ' / наличие уточняйте' . PHP_EOL;
										
									}								
									
									/*	
										//картинки
										$dir = mb_strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $offer['vendor']));							
										if (!is_dir(DIR_IMAGE . 'data/' . $dir ) ){
										mkdir(DIR_IMAGE . 'data/' . $dir);
										}
										
										if (!is_array($offer['picture']) && $offer['picture']){
										$offer['picture'] = array($offer['picture']);
										}
										
										$product_image = array();
										foreach ($offer['picture'] as $pic){
										if (!file_exists(DIR_IMAGE . 'data/' . $dir . '/' . basename($pic))){
										echo '>>> Тырю картинку ' . basename($pic) . PHP_EOL;
										file_put_contents(DIR_IMAGE . 'data/' . $dir . '/' . basename($pic), file_get_contents($pic));
										}
										
										$product_image[] = 'data/' . $dir . '/' . basename($pic);
										}
										
										fwrite($picfile, (int)$product['product_id'] . ';' . $offer['vendorCode'] . ';' . implode(',', $product_image) . PHP_EOL);
									*/	
									
								}
							}
							
						}
						
						
					}
				}
				
				fclose($picfile);
				
				} elseif ($params['action'] == 'addtocatalog'){
				
				
				$log_yml_add = new Log('supplier_'. $supplier_code .'new.txt');	
				
				foreach($yml['yml_catalog']['shop']['offers']['offer'] as $offer){
					if ($product = $this->findProduct(array('model' => $offer['vendorCode']))){	
						
						echo '>> Нашли товар ' . $offer['vendor'] . ' :: ' . $offer['vendorCode'] .' '. $offer['name'] . ' :: ' . $product['name'] . PHP_EOL;
						
						} else {
						
						
						//проверить бренд
						$mcq = $this->db->query("SELECT manufacturer_id FROM manufacturer WHERE TRIM(name) = '" . $offer['vendor'] . "' LIMIT 1");
						if ($mcq->num_rows){
							echo '>> Нашли бренд ' . $offer['vendor'] . PHP_EOL;
							$manufacturer_id = $mcq->row['manufacturer_id'];
							
							} else {
							echo '>> Не нашли бренд ' . $offer['vendor'] . PHP_EOL;
							
							
							$data = array(
							'manufacturer_description' => array(
							"2" => array(								
							'description' => '',
							'location' => $this->getParamValue($offer['param'], "СтранаБренда"),
							'seo_title'  => '',
							'seo_h1'  => '',
							'meta_title'        => '',
							'short_description'        => '',
							'meta_description'  => '',
							'meta_keyword'      => ''
							),
							"5" => array(								
							'description' => '',
							'location' => $this->getParamValue($offer['param'], "СтранаБренда"),
							'seo_title'  => '',
							'seo_h1'  => '',
							'meta_title'        => '',
							'meta_description'  => '',
							'short_description'        => '',
							'meta_keyword'      => ''							
							),
							"8" => array(								
							'description' => '',
							'location' => $this->getParamValue($offer['param'], "СтранаБренда"),
							'seo_title'  => '',
							'seo_h1'  => '',
							'meta_title'        => '',
							'meta_description'  => '',
							'short_description'        => '',
							'meta_keyword'      => ''							
							),
							"9" => array(								
							'description' => '',
							'location' => $this->getParamValue($offer['param'], "СтранаБренда"),
							'seo_title'  => '',
							'seo_h1'  => '',
							'meta_title'        => '',
							'meta_description'  => '',
							'short_description'        => '',
							'meta_keyword'      => ''							
							),
							"26" => array(								
							'description' => '',
							'location' => $this->getParamValue($offer['param'], "СтранаБренда"),
							'seo_title'  => '',
							'seo_h1'  => '',
							'meta_title'        => '',
							'meta_description'  => '',
							'short_description'        => '',
							'meta_keyword'      => ''							
							)	
							),
							'manufacturer_store' => array(
							//'0', '1', '2', '5'
							),
							'name' => trim($offer['vendor']),							
							'sort_order' => 0,
							'tip'        => '',
							'menu_brand'        => '',
							'show_goods'  => 0,
							'banner_width' => 0,
							'banner_height' => 0,
							'keyword'    => ''
							);
							
							$manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($data);
							
							echo '>> Создали бренд ' . $manufacturer_id . ' - ' . $offer['vendor'] . PHP_EOL;	
							
						}		
						
						//Цвет
						$product_attribute = array();
						
						if ($color = $this->getParamValue($offer['param'], "color")) {
							$product_attribute[] = array(
							'attribute_id' => 128,
							'product_attribute_description' => array(
							"2" => array(
							'text' => $color,								
							),
							"5" => array(
							'text' => $color,						
							),
							"8" => array(
							'text' => $color,						
							),
							"9" => array(
							'text' => $color,						
							),
							"26" => array(
							'text' => $color,						
							)
							)
							);
						}
						
						//Размер
						if ($material = $this->getParamValue($offer['param'], "material")) {
							$product_attribute[] = array(
							'attribute_id' => 3,
							'product_attribute_description' => array(
							"2" => array(
							'text' => $material,								
							),
							"5" => array(
							'text' => $material,						
							),
							"8" => array(
							'text' => $material,						
							),
							"9" => array(
							'text' => $material,						
							),
							"26" => array(
							'text' => $material,						
							)
							)
							);
						}
						
						//картинки
						$dir = mb_strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $offer['vendor']));							
						if (!is_dir(DIR_IMAGE . 'data/' . $dir ) ){
							mkdir(DIR_IMAGE . 'data/' . $dir);
						}
						
						if (!is_array($offer['picture']) && $offer['picture']){
							$offer['picture'] = array($offer['picture']);
						}
						
						$product_image = array();
						foreach ($offer['picture'] as $pic){
							if (!file_exists(DIR_IMAGE . 'data/' . $dir . '/' . basename($pic))){
								echo '>>> Тырю картинку ' . basename($pic) . PHP_EOL;
								file_put_contents(DIR_IMAGE . 'data/' . $dir . '/' . basename($pic), file_get_contents($pic));
							}
							
							$product_image[] = array( 
							'image' => 'data/' . $dir . '/' . basename($pic),
							'sort_order' => 0
							);
						}
						
						$additional_images = $product_image;
						array_shift($additional_images);
						
						error_reporting(E_ERROR | E_PARSE);
						
						$data = array(
						'product_description' => array(
						"2" => array(
						'name' => $offer["name"],
						'description' => $offer["description"],
						'tag'         => '',
						'seo_title'         => '',
						'seo_h1'         => '',
						'name_of_option' => '',
						'meta_description'         => '',
						'meta_keyword'         => ''
						),
						"5" => array(
						'name' => $offer["name"],
						'description' => $offer["description"],
						'tag'         => '',
						'seo_h1'         => '',
						'seo_title'         => '',
						'name_of_option' => '',
						'meta_description'         => '',
						'meta_keyword'         => ''							
						),
						"9" => array(
						'name' => $offer["name"],
						'description' => $offer["description"],
						'tag'         => '',
						'seo_h1'         => '',
						'seo_title'         => '',
						'name_of_option' => '',
						'meta_description'         => '',
						'meta_keyword'         => ''							
						),
						"8" => array(
						'name' => $offer["name"],
						'description' => $offer["description"],
						'tag'         => '',
						'seo_h1'         => '',
						'seo_title'         => '',
						'name_of_option' => '',
						'meta_description'         => '',
						'meta_keyword'         => ''							
						),
						"26" => array(
						'name' => $offer["name"],
						'description' => $offer["description"],
						'tag'         => '',
						'seo_h1'         => '',
						'seo_title'         => '',
						'name_of_option' => '',
						'meta_description'         => '',
						'meta_keyword'         => ''							
						)
						),
						'product_store' => array(
						'0', '1', '2', '5'
						),
						'model'  => $offer['vendorCode'],
						'short_name' => $offer["name"],
						'image' => isset($product_image[0])?$product_image[0]['image']:'',
						'product_image' => $additional_images,
						'sku'    => '', 
						'upc'    => '', 
						'ean'    => $this->getParamValue($offer['param'], "ШтрихКод"), 
						'jan'    => '', 
						'isbn'    => '', 
						'mpn'     => '',
						'asin'     => '',
						'source'     => '',
						'price_national'     => '',
						'weight_amazon_key'     => '',
						'length_amazon_key'     => '',
						'pack_weight'     => '',
						'pack_weight_class_id'     => '',
						'pack_weight_amazon_key'     => '',
						'pack_length'     => '',
						'pack_width'     => '',
						'pack_height'     => '',
						'pack_length_class_id'     => '',
						'pack_length_amazon_key'     => '',
						'quantity_stock'     => '',
						'quantity_stockK'     => '',
						'quantity_stockM'     => '',
						'quantity_stockMN'     => '',
						'quantity_stockAS'     => '',
						'quantity_stockAS'     => '',
						'min_buy'     => '',
						'max_buy'     => '',
						'ignore_parse_date_to'     => '',
						'new'     => '',
						'color_group'     => '',
						'currency'     => '',
						'tnved'     => '',
						'is_option_with_id'     => '',
						'name_of_option'     => '',
						'ignore_parse'     => '',
						'new_date_to'     => '',
						'keyword'     => '', 
						'location'     => $this->getParamValue($offer['param'], "СтранаБренда"), 
						'minimum'     => '', 
						'subtract'     => '', 
						'date_available'    => '', 
						'manufacturer_id'    => $manufacturer_id, 
						'collection_id' => 0,							
						'product_attribute' => $product_attribute,
						'price'     => $this->currency->convert($offer['price'], 'RUB', 'EUR'), 
						'cost'     => 0,							
						'points'     => '', 
						'weight'     => '', 
						'weight_class_id'    => '', 
						'length'     => $this->getParamValue($offer['param'], "Длина"),  
						'width'     => $this->getParamValue($offer['param'], "Глубина"),  
						'height'     => $this->getParamValue($offer['param'], "Высота"),  
						'length_class_id'    => '1', 
						'tax_class_id'     => '', 
						'sort_order'     => '', 
						'status' => 0,
						'stock_status_id' => 7,
						'shipping'   => 1,
						'quantity'   => 999																				
						);
						
						$product_id = $this->model_catalog_product->addProduct($data);
						
						$this->justmodels[trim($offer['vendorCode'])] = $product_id;
						
						$sp_id = isset($offer["@attributes"]["id"])?$offer["@attributes"]["id"]:0;
						
						$this->db->query("
						INSERT INTO local_supplier_products SET 
						supplier_id = '" . (int)$params['supplier_id'] . "',
						supplier_product_id = '" . (int)$sp_id . "',
						product_id = '" . (int)$product_id . "',
						product_model = '" . $offer['vendorCode'] . "',
						product_ean = '" . $this->getParamValue($offer['param'], "ШтрихКод") . "',
						price = '" . (float)($offer['price'] * 0.6) . "',
						price_recommend = '" . (float)$offer['price'] . "',
						currency = '" . $this->db->escape($offer['currencyId']) . "',
						stock = '" . $this->getParamValue($offer['param'], "stock") . "',
						product_xml = '" . base64_encode(serialize($offer)) . "'
						ON DUPLICATE KEY UPDATE
						supplier_product_id = '" . (int)$sp_id . "',
						product_model = '" . $offer['vendorCode'] . "',
						product_ean = '" . $this->getParamValue($offer['param'], "ШтрихКод") . "',
						price = '" . (float)($offer['price'] * 0.6) . "',
						price_recommend = '" . (float)$offer['price'] . "',
						currency = '" . $this->db->escape($offer['currencyId']) . "',
						stock = '" . $this->getParamValue($offer['param'], "stock") . "',
						product_xml = '" . base64_encode(serialize($offer)) . "'
						");
						
						
						echo ">>> Добавили товар. " . $product_id . '(' . $offer["name"] . ')' . PHP_EOL;
						
						
						$log_yml_add->write('+товар ' . $product_id . ', ' . $offer["name"] . ', ' . $offer['vendorCode']);
						
						
						
					}
					
				}
			}
			
		}
	}
	
