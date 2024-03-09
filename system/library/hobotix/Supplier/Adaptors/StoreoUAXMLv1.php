<?

namespace hobotix\Supplier\Adaptors;

class StoreoUAXMLv1 extends SuppliersGeneralClass {
	protected 	$type = 'XML';
	
	protected   $exclude_categories = [
	];

	protected   $exclude_attributes = [
	];

	public function getCategories(){
		$categories = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['yml_catalog'])){
			if (!empty($this->content['yml_catalog']['shop'])){
				if (!empty($this->content['yml_catalog']['shop']['categories'])){
					foreach ($this->content['yml_catalog']['shop']['categories']['category'] as $key => $item){				
						$categories[$item['@attributes']['id']] = [
							'category_id' 		=> $item['@attributes']['id'],
							'parent_id' 		=> '',
							'category_name' 	=> $item['@value'],
							'category_name_full'=> $item['@value'],
						];					
					}
				}
			}
		}

		return $categories;
	}

	public function getAttributes(){
		$product_attributes = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['yml_catalog'])){
			if (!empty($this->content['yml_catalog']['shop'])){
				if (!empty($this->content['yml_catalog']['shop']['offers'])){
					if (!empty($this->content['yml_catalog']['shop']['offers']['offer'])){
						foreach ($this->content['yml_catalog']['shop']['offers']['offer'] as $offer){
							if (!empty($offer['param'])){
								$params = checkSingleXMLItemByField($offer['param'], '@attributes');

								foreach ($params as $param){
									$product_attributes[mb_ucfirst(checkCDATA($param['@attributes']['name']))] = mb_ucfirst(checkCDATA($param['@attributes']['name']));
								}
							}
						}
					}
				}
			}
		}

		return $product_attributes;
	}


	public function getProducts(){
		$products = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		$this->setCategories();

		if (!empty($this->content['yml_catalog'])){
			if (!empty($this->content['yml_catalog']['shop'])){
				if (!empty($this->content['yml_catalog']['shop']['offers'])){
					if (!empty($this->content['yml_catalog']['shop']['offers']['offer'])){
						foreach ($this->content['yml_catalog']['shop']['offers']['offer'] as $offer){

							$name = [];
							if (!empty($offer['name_ua'])){
								$name['uk'] = checkCDATA($offer['name_ua']);
							}
							if (!empty($offer['name'])){
								$name['ru'] = ['translate_from' => 'uk', 'translate_data' => checkCDATA($offer['name'])];
							}

							$description = [];
							if (!empty($offer['description_ua'])){
								$description['uk'] = checkCDATA($offer['description_ua']);
							}
							if (!empty($offer['description'])){
								$description['ru'] = ['translate_from' => 'uk', 'translate_data' => checkCDATA($offer['description'])];
							}

							$image 	= '';	
							$images = [];

							$pictures 	= checkSingleXMLItem($offer['picture']);							
							$image 		= $pictures[0];

							foreach ($pictures as $picture){
								$images[] = $picture;
							}

							if (!empty($images[0])){
								unset($images[0]);
							}

							$product_attributes = [];
							if (!empty($offer['param'])){
								$params = checkSingleXMLItemByField($offer['param'], '@attributes');

								foreach ($params as $param){
									$product_attributes[mb_ucfirst(checkCDATA($param['@attributes']['name']))] = [
										'name' 	=> [
											'uk' => mb_ucfirst(checkCDATA($param['@attributes']['name'])),
											'ru' => ['translate_from' => 'uk', 'translate_data' => mb_ucfirst(checkCDATA($param['@attributes']['name']))]
										],
										'text' => [
											'uk' => mb_ucfirst(checkCDATA($param['@value'])),
											'ru' => ['translate_from' => 'uk', 'translate_data' => mb_ucfirst(checkCDATA($param['@value']))]
										]
									];
								}
							}

							$products[] = [
								'supplier_product_id' 	=> $offer['@attributes']['id'],
								'status'				=> (bool)$offer['@attributes']['available'],
								'name'					=> $name,
								'description' 			=> $description,
								'model' 				=> $offer['vendorCode'],
								'sku' 					=> $offer['vendorCode'],
								'ean' 					=> $offer['EAN'],
								'image' 				=> $image,
								'images' 				=> $images,
								'stock' 				=> ((bool)$offer['@attributes']['in_stock'])?true:false,
								'quantity' 				=> (int)$offer['quantity_in_stock'],
								'price' 				=> (float)$offer['price'],						
								'vendor' 				=> $offer['vendor'],
								'vendor_country'		=> $offer['country'],
								'category'  			=> $offer['categoryId'],
								'attributes'            => $product_attributes,
								'raw' 					=> json_encode($offer)
							];
						}
					}
				}
			}
		}

		return $products;
	}

}