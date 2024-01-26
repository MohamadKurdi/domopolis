<?

namespace hobotix\Supplier;

class SupplierProduct extends SupplierFrameworkClass {
	public function parseProduct($product, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		echoLine('[SupplierProduct::parseProduct] Working with product: ' . $product['sku'], 'w');

		if (!empty($product['category'])){
			$category = $this->registry->get('supplierAdaptor')->SupplierCategory->getCategoryMatchFull($product['category']);			

			if ($category && !$category['products']){
				echoLine('[SupplierProduct::parseProduct] Category ' . $product['category'] . ' has marker not to add products, skipping', 'e');
				return;
			}
		}

		$product_exists = false;
		$product_id 	= $this->model_get->getProductFromSupplierMatchTable($product, $this->getSupplierSetting('sync_field'));
		if (!$product_id){
			echoLine('[SupplierProduct::parseProduct] Not found matched product in supplier_products', 'e');
		
			$product['manufacturer_id']	= $this->parseProductManufacturer($product);
			$product_id 				= $this->model_get->getProductBySKU($product);
			$product_exists 			= $product_id;				

			if ($product_id){							
				echoLine('[SupplierProduct::parseProduct] Product found by sku in main table:' . $product_id, 's');				
			} else {
				echoLine('[SupplierProduct::parseProduct] Not found product id in main table, adding product', 'w');	
				$product_id = $this->addProductSimple($product);		
			}
		} else {
			echoLine('[SupplierProduct::parseProduct] Found matched product in supplier_products: ' . $product_id, 's');
			$product_exists = $product_id;
		}

		$product['product_id'] = $product_id;

		$this->addProductToSupplierTable($product, $supplier_id);
		echoLine('[SupplierProduct::parseProduct] Added or modified mapping to supplier_products', 'i');

		if (!$product_exists){			
			$product['manufacturer_id']	= $this->parseProductManufacturer($product);

			$this->updateProductManufacturer($product_id, $product);
			$this->parseProductImages($product_id, $product);			
			$this->parseProductDescriptions($product_id, $product);
			$this->parseProductAttributes($product_id, $product);
		}
		
		$this->parseProductStatus($product_id, $product);
		$this->parseProductStock($product_id, $product);		
		$this->parseProductPrice($product_id, $product);		
	}


	public function parseProductStatus($product_id, $product, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		if (isset($product['status'])){
			if ($this->getSupplierSetting('auto_enable')){
				if ($product['status']){					
					$this->registry->get('supplierAdaptor')->PriceLogic->enableProduct($product_id);
				} else {
					$this->registry->get('supplierAdaptor')->PriceLogic->disableProduct($product_id);
				}
			}
		}
	}

	public function parseProductStock($product_id, $product, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		if ($this->getSupplierSetting('stock')){
			echoLine('[SupplierProduct::parseProductStock] General stock logic for supplier in ON', 's');

			$category = $this->registry->get('supplierAdaptor')->SupplierCategory->getCategoryMatchFull($product['category']);			

			if ($category && !$category['stocks']){
				echoLine('[SupplierProduct::parseProductStock] Category ' . $product['category'] . ' has marker not to set stock, skipping', 'e');
				return false;
			}

			if ($product['stock']){
				$this->registry->get('supplierAdaptor')->PriceLogic->setProductIsOnStock($product_id, $product);
			} else {
				$this->registry->get('supplierAdaptor')->PriceLogic->setProductIsNotOnStock($product_id, $product);
			}

		}  else {
			echoLine('[SupplierProduct::parseProductPrice] General pricing logic for supplier in OFF', 's');
			return false;
		}
	}

	public function parseProductPrice($product_id, $product, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}
		
		if ($this->getSupplierSetting('prices')){			
			echoLine('[SupplierProduct::parseProductPrice] General pricing logic for supplier in ON', 's');

			$category = $this->registry->get('supplierAdaptor')->SupplierCategory->getCategoryMatchFull($product['category']);			

			if ($category && !$category['prices']){
				echoLine('[SupplierProduct::parseProductPrice] Category ' . $product['category'] . ' has marker not to set prices, skipping', 'e');
				return false;
			}

			if ($this->getSupplierSetting('rrp_in_feed')){
				echoLine('[SupplierProduct::parseProductPrice] We have RRP in feed, updating prices', 's');

				$product_current_info = $this->model_get->getProductPriceStatus($product_id);

				if (!(float)$product['price']){
					if (!(float)$product_current_info['price']){
						echoLine('[SupplierProduct::parseProductPrice] Price is zero, prev is zero too, disabling product!', 'e');
						$this->registry->get('supplierAdaptor')->PriceLogic->disableProduct($product_id);
						return false;
					}
				} else {
					if (!(float)$product_current_info['price']){
						echoLine('[SupplierProduct::parseProductPrice] Price is non zero, prev is zero, enabling product!', 'e');
						$this->registry->get('supplierAdaptor')->PriceLogic->enableProduct($product_id);
					}
				}							

				$price 			= $this->currency->convert($product['price'], $this->getSupplierSetting('currency'), $this->config->get('config_currency'));
				$price_special 	= $this->currency->convert($product['price_special'], $this->getSupplierSetting('currency'), $this->config->get('config_currency'));

				$price_national 			= $this->currency->convert($product['price'], $this->getSupplierSetting('currency'), $this->config->get('config_regional_currency'));
				$price_special_national 	= $this->currency->convert($product['price_special'], $this->getSupplierSetting('currency'), $this->config->get('config_regional_currency'));

				echoLine('[SupplierProduct::parseProductPrice] Setting prices, general is ' . $price . ', national is ' . $price_national, 'i');

				$this->registry->get('supplierAdaptor')->PriceLogic->updateProductPriceInDatabase($product_id, $price);
				$this->registry->get('supplierAdaptor')->PriceLogic->updateProductPriceNationalToStoreInDatabase($product_id, $price_national, 0);
			} else {
				echoLine('[SupplierProduct::parseProductPrice] It is no RRP in feed, doing magic', 'w');
			}
			
		} else {
			echoLine('[SupplierProduct::parseProductPrice] General pricing logic for supplier in OFF', 's');
			return false;
		}
	}

	public function parseProductAttributes($product_id, $product){
		if (!empty($product['attributes'])){
			
			foreach ($product['attributes'] as $key => $attribute){
				$attribute_id = $this->registry->get('supplierAdaptor')->SupplierAttribute->getAttributeMatch($key);

				if ($attribute_id){
					echoLine('[SupplierProduct::parseProductAttributes] Found match for ' . $key . ' to attribute ' . $attribute_id);
				}
				
				if (!$attribute_id){
					$attribute_id = $this->model_cached_get->getAttribute($key);
				}			

				if (!$attribute_id){
					echoLine('[SupplierProduct::parseProductAttributes] Attribute not found: ' . $key, 'e');

					$attribute_description = $this->mapTranslation($attribute, 'name', $this->getSupplierSetting('language_in_feed'));
					
					$attribute_data = [
						'attribute_group_id' 	=> $this->config->get('config_default_attr_id'),
						'attribute_description' => $attribute_description
					];

					$attribute_id = $this->model_edit->addAttribute($attribute_data);
				}

				$product_attribute_description = $this->mapTranslation($attribute, 'text', $this->getSupplierSetting('language_in_feed'));
				$product_attribute[] = [
						'attribute_id' 					=> $attribute_id,
						'product_attribute_description' => $product_attribute_description
				];
			}

			if ($product_attribute){
				$this->model_edit->editProductAttributes($product_id, $product_attribute);
			}
		}
	}

	public function parseProductDescriptions($product_id, $product){
		if (!empty($product['description'])){
			$product_description_data = $this->mapTranslation($product, 'description', $this->getSupplierSetting('language_in_feed'));

			$this->model_edit->editProductDescriptions($product_id, $product_description_data);
		}
	}

	public function parseProductImages($product_id, $product){
		if (!empty($product['image'])){
			$this->model_edit->editProductFields($product_id, [['name' => 'image', 'type' => 'varchar', 'value' => $this->getImage($product['image'])]]);
		}

		if (!empty($product['images'])){
			$images = [];
			$sort_order = 0;
			foreach ($product['images'] as $image){
					$downloaded_image = $this->getImage($image);

					if ($downloaded_image){
						$images[] = [
							'image' 		=> $downloaded_image,
							'sort_order' 	=> $sort_order
						];
					}

					$sort_order++;
			}

			if ($images){
				$this->model_edit->editProductImages($product_id, $images);
			}
		}	
	}

	public function updateProductManufacturer($product_id, $product){
		$this->model_edit->editProductDescriptionFieldExplicit($product_id, 'manufacturer_name', $product['vendor']);
		$this->model_edit->editProductFields($product_id, [['name' => 'manufacturer_id', 'type' => 'int', 'value' => $product['manufacturer_id']]]);
	}

	public function parseProductManufacturer($product){
		$manufacturer_id = 0;

		if (!empty($product['vendor'])){
			$product['vendor'] = atrim($product['vendor']);

			echoLine('[SupplierProduct::parseProductManufacturer] Vendor: ' . $product['vendor'], 'i');			

			$manufacturer_id = $this->model_cached_get->getManufacturer($product['vendor']);			
			if (!$manufacturer_id){
				if ($this->config->get('config_rainforest_auto_create_manufacturers')){
					echoLine('[SupplierProduct::parseProductManufacturer] Setting config_rainforest_auto_create_manufacturers = ON, creating vendor: ' . $product['vendor'], 'w');					
					$manufacturer_id = $this->registry->get('supplierAdaptor')->SupplierManufacturer->addManufacturer($product['vendor']);					
				} else {
					echoLine('[SupplierProduct::parseProductManufacturer] Setting config_rainforest_auto_create_manufacturers = OFF, editing just description', 'w');					
				}
			}
		}	

		return $manufacturer_id;			
	}

	public function cleanUpProductToSupplierTable($product, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		$this->db->query("DELETE FROM supplier_products WHERE `" . $this->db->escape($this->getSupplierSetting('sync_field')) . "` = '" . $this->db->escape($product[$this->getSupplierSetting('sync_field')]) . "' AND supplier_id = '" . (int)$supplier_id . "'");		
	}

	public function setProductIdInSupplierTable($product_supplier_id, $product_id){
		$this->db->query("UPDATE supplier_products SET product_id = '"  . (int)$product_id . "' WHERE product_supplier_id = '" . (int)$product_supplier_id . "'");
	}

	public function addProductToSupplierTable($product, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}
		
		if (empty($product['product_id'])){
			$product['product_id'] = 0;
		}

		$this->db->query("INSERT INTO supplier_products SET
			supplier_id 		= '" . (int)$supplier_id . "',
			supplier_product_id = '" . $this->db->escape($product['supplier_product_id']) . "',
			sku 				= '" . $this->db->escape($product['sku']) . "',
			product_id 			= '" . (int)$product['product_id'] . "',
			price 				= '" . (float)$product['price'] . "',
			price_special 		= '" . (float)$product['price_special'] . "',
			stock 				= '" . (int)$product['stock'] . "',
			quantity 			= '" . (int)$product['quantity'] . "',
			raw 				= '" . $this->db->escape($product['raw']) . "'
			ON DUPLICATE KEY UPDATE
			product_id 			= '" . (int)$product['product_id'] . "',
			price 				= '" . (float)$product['price'] . "',
			price_special 		= '" . (float)$product['price_special'] . "',
			stock 				= '" . (int)$product['stock'] . "',
			quantity 			= '" . (int)$product['quantity'] . "',
			raw 				= '" . $this->db->escape($product['raw']) . "'");

		return $this->db->getLastId();
	}

	public function addProductSimple($product){
		if (isset($product['status'])){
			$status = $product['status'];
		} else {
			$status = $this->getSupplierSetting('auto_enable');
		}

		if ($product['stock']){
			$quantity 			= 9999;
			$stock_status_id 	= (int)$this->config->get('config_stock_status_id');
		} else {
			$quantity 			= 0;
			$stock_status_id 	= (int)$this->config->get('config_not_in_stock_status_id');
		}	

		$this->db->query("INSERT INTO product SET 
			model 					= '" . $this->db->escape($product['model']) . "', 
			sku 					= '" . $this->db->escape($product['sku']) . "', 		
			ean 					= '" . $this->db->escape($product['ean']) . "',
			added_from_amazon 		= '0', 			
			stock_status_id 		= '" . (int)$stock_status_id . "',
			manufacturer_id 		= '" . (int)$product['manufacturer_id'] . "',
			quantity 				= '" . (int)$quantity . "',
			status 					= '" . (int)$status . "',
			date_added 				= NOW()");
		
		$product_id = $this->db->getLastId();

		$this->db->query("DELETE FROM product_to_store WHERE product_id 	= '" . (int)$product_id . "'");		
		$this->db->query("INSERT INTO product_to_store SET product_id 		= '" . (int)$product_id . "', store_id = '0'");

		if (!empty($this->config->get('config_rainforest_add_to_stores'))){
			foreach ($this->config->get('config_rainforest_add_to_stores') as $store_id){
				if ($store_id > 0){
					$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}				
			}
		}

		$category_id = false;
		if (!empty($product['category'])){
			$category_id = $this->registry->get('supplierAdaptor')->SupplierCategory->getCategoryMatch($product['category']);			
		}
		
		if (!$category_id){
			$category_id = $this->config->get('config_rainforest_default_unknown_category_id');
			echoLine('[SupplierProduct::addProductSimple] Did not found category mapping for ' . $product['category'], 'e');
		} else {
			echoLine('[SupplierProduct::addProductSimple] Found category mapping for ' . $product['category'] . ' ' . $category_id, 'i');
		}

		$this->db->query("DELETE FROM product_to_category WHERE product_id 	= '" . (int)$product_id . "'");
		$this->db->query("INSERT INTO product_to_category SET product_id 	= '" . (int)$product_id . "', category_id = '" . (int)$category_id . "', main_category = 1");
		echoLine('[SupplierProduct::addProductSimple] Adding product to category ' . $category_id, 'i');

		$this->db->query("DELETE FROM product_description WHERE product_id 	= '" . (int)$product_id . "'");
		$product_name_data = $this->mapTranslation($product, 'name', $this->getSupplierSetting('language_in_feed'));
		$this->model_edit->addProductNames($product_id, $product_name_data);

		if ($this->config->get('config_seo_url_from_id') && $this->registry->get('url')->checkIfGenerate('product_id')){
			$this->db->query("DELETE FROM url_alias WHERE query = '" . $this->db->escape('product_id=' . $product_id) . "'");

			foreach ($this->registry->get('languages') as $language_code => $language) {
				$this->db->query("INSERT INTO url_alias SET query = 'product_id=". (int)$product_id ."', keyword = '" . $this->db->escape('p' . $product_id) . "', language_id = '" . (int)$language['language_id'] 	 ."'");	
			}
		}	

		return $product_id;
	}
}