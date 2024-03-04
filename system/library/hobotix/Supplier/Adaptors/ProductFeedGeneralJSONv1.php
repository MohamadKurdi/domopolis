<?

namespace hobotix\Supplier\Adaptors;

class ProductFeedGeneralJSONv1 extends SuppliersGeneralClass {
	protected $type = 'JSON';


	public function getCategories(){
		$categories = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['categories'])){
			foreach ($this->content['categories'] as $item){				
				$categories[$item['category_id']] = [
					'category_id' 		=> $item['category_id'],
					'parent_id' 		=> $item['parent_id'],
					'category_name' 	=> $item['menu_name'],
					'category_name_full'=> $item['name'],
				];					
			}			
		}

		return $categories;
	}

	public function getAttributes(){
		$product_attributes = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['products'])){
			foreach ($this->content['products'] as $key => $product){
				if (!empty($product['attributes'])){
					foreach ($product['attributes'] as $attribute){
						if (trim($attribute['attribute_name'])){
							$product_attributes[$attribute['attribute_name']] = $attribute['attribute_name'];
						}
					}
				}
			}			
		}

		return $product_attributes;
	}

	private function reparseKPUAURI($image){
		return 'https://kitchen-profi.com.ua/image/' . $image;
	}

	public function getProducts(){
		$products = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		$this->setCategories();

		if (!empty($this->content['products'])){
			foreach ($this->content['products'] as $key => $product){

				$image 	= '';	
				$images = [];

				if (!empty($product['product']['image'])){
					$image = $this->reparseKPUAURI($product['product']['image']);
				}

				if (!empty($product['images'])){
					if (!empty($product['images'])){
						foreach ($product['images'] as $product_image){
							$images[] = $this->reparseKPUAURI($product_image['image']);
						}
					}						
				}

				$product_attributes = [];
				foreach ($product['attributes'] as $attribute){
					$product_attributes[$attribute['attribute_name']] = [
						'name' => [
							'uk' 	=> $attribute['attribute_name'] 
						],
						'text' => [
							'uk' 	=> $attribute['attribute_value'] 
						]
					];
				}				

				$products[] = [
						'supplier_product_id' 	=> $product['product']['product_id'],
						'status'				=> $product['product']['status'],
						'name'					=> ['uk' => ['translate_from' => 'ru', 'translate_data' => $product['descriptions'][5]['name']]],
						'description'			=> ['uk' => ['translate_from' => 'ru', 'translate_data' => $product['descriptions'][5]['description']]],
						'model' 				=> $product['product']['model'],
						'sku' 					=> !empty($product['product']['sku'])?$product['product']['sku']:('KP'.$product['product']['product_id']),
						'image' 				=> $image,
						'images' 				=> $images,
						'stock' 				=> ($product['product']['quantity'] > 0)?true:false,
						'quantity' 				=> (int)$product['product']['quantity'],
						'price' 				=> (float)$product['product']['price'],						
						'vendor' 				=> $product['product']['manufacturer'],
						'category'  			=> $product['product']['main_category_id'],
						'attributes'            => $product_attributes,
						'raw' 					=> json_encode($product)
					];
			}
		}

		return $products;
	}


	public function postAction(){
		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['products'])){
			foreach ($this->content['products'] as $key => $product){				
				$product['product']['supplier_product_id'] = $product['product']['product_id'];

				$product_id = $this->model_get->getProductFromSupplierMatchTable($product['product'], 'supplier_product_id');

				if ($product_id){			
					$query = $this->db->query("SELECT added_from_supplier FROM product WHERE product_id = '" . (int)$product_id ."' AND date_added >= DATE_SUB(NOW(), INTERVAL 2 HOUR)");

					if (!$query->num_rows || $query->row['added_from_supplier'] != '34779'){
						echoLine('[ProductFeedKPUAv1::postAction] Not added from current supplier or time high, skipping: ' . $product_id, 'w');
						continue;
					}

					echoLine('[ProductFeedKPUAv1::postAction] Found match product, updating: ' . $product_id, 'w');

					foreach (['ean', 'asin', 'location', 'mpn', 'weight', 'price', 'weight_class_id', 'length', 'width', 'height', 'length_class_id', 'pack_weight', 'pack_weight_class_id', 'pack_length', 'pack_width', 'pack_height', 'pack_length_class_id'] as $key){
						if (!empty($product['product'][$key])){
							$this->model_edit->editProductFields($product_id, [['name' => $key, 'value' => $product['product'][$key]]]);
						}
					}

					$this->model_edit->editProductNames($product_id, $product['descriptions'], 26);
					$this->model_edit->editProductDescriptions($product_id, $product['descriptions'], 26);
				}
			}

			$this->db->query("UPDATE product SET amzn_ignore = 1 WHERE added_from_supplier = '34779'");

			if ($this->config->get('config_rainforest_enable_offers_only_for_filled')){
				$this->db->query("UPDATE product SET filled_from_amazon = 1 WHERE added_from_supplier = '34779'");
			}
		}
	}
}