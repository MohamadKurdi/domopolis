<?

namespace hobotix\Supplier\Adaptors;

class PosudaUAXMLExportFullv2 extends SuppliersGeneralClass {
	protected 	$type = 'XML';
	
	protected   $exclude_categories = [
		'Новинки',
		'Акция'
	];

	protected   $exclude_attributes = [
		'Бренд'
	];

	public function getCategories(){
		$categories = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['itemlist'])){
			if (!empty($this->content['itemlist']['item'])){
				foreach ($this->content['itemlist']['item'] as $item){
					if (!empty($item['product_category'])){
						if (!empty($item['product_category']['category'])){
							$item['product_category']['category'] = checkValueXMLItem($item['product_category']['category']);

							foreach ($item['product_category']['category'] as $category){
								$exploded 	= explode('>', $category['@value']);
								$name 		= end($exploded);			
								
								if ($name){
									$categories[$category['@attributes']['id']] = [
										'category_id' 			=> $category['@attributes']['id'],
										'parent_id' 			=> '',
										'category_name' 		=> $name,
										'category_name_full' 	=> $category['@value']
									];	
								}								
							}																
						}
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

		if (!empty($this->content['itemlist'])){
			if (!empty($this->content['itemlist']['item'])){
				foreach ($this->content['itemlist']['item'] as $item){
					if (!empty($item['product_filter'])){
						if (!empty($item['product_filter']['filter'])){
							$filters = checkSingleXMLItemByField($item['product_filter']['filter'], 'name');

							foreach ($filters as $filter){
								$product_attributes[mb_ucfirst(checkCDATA($filter['group_name']))] = mb_ucfirst(checkCDATA($filter['group_name']));
							}
						}
					}

					if (!empty($item['product_attribute'])){
						if (!empty($item['product_attribute']['attribute'])){
							$attributes = checkSingleXMLItemByField($item['product_attribute']['attribute'], 'group_ru');						

							foreach ($attributes as $attribute){
								$product_attributes[mb_ucfirst(checkCDATA($attribute['group_ru']))] = mb_ucfirst(checkCDATA($attribute['group_ru']));											
							}
						}
					}
				}
			}
		}

		return $product_attributes;
	}

	private function vendor($vendor, $return = 'vendor'){
		$exploded = explode(',', $vendor);

		if (count($exploded) == 2){
			if ($return == 'vendor'){
				return trim($exploded[0]);
			}

			if ($return == 'country'){
				return trim($exploded[1]);
			}
		}

		return $vendor;
	}

	private function reparseCategories($categories){
		$tmp = [];
		foreach ($categories as $category){
			if (!in_array($category['@value'], $this->exclude_categories)){
				$tmp[] = $category;
			}
		}

		return $tmp;
	}

	private function reparsePosudaUAURI($uri){
		return str_replace(['http:///', 'https:///'], 'https://posuda.ua/', $uri);
	}
	
	public function getProducts(){
		$products = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		$this->setCategories();

		if (!empty($this->content['itemlist'])){
			if (!empty($this->content['itemlist']['item'])){
				foreach ($this->content['itemlist']['item'] as $item){		

					$name = [];
					if (!empty($item['name_ua'])){
						$name['uk'] = checkCDATA($item['name_ua']);
					}
					if (!empty($item['name_ru'])){
						$name['ru'] = checkCDATA($item['name_ru']);
					}

					$description = [];
					if (!empty($item['description_ua'])){
						$description['uk'] = checkCDATA($item['description_ua']);
					}
					if (!empty($item['description_ru'])){
						$description['ru'] = checkCDATA($item['description_ru']);
					}

					$image 	= '';	
					$images = [];

					if (!empty($item['image'])){
						$image = $this->reparsePosudaUAURI($item['image']);
					}

					if (!empty($item['additional_images'])){
						if (!empty($item['additional_images']['image'])){
							$images = checkSingleXMLItem($item['additional_images']['image']);
							$images = array_map([$this, 'reparsePosudaUAURI'], $images);
						}						
					}

					$category = '';
					if (!empty($item['product_category']['category'])){
						$categories = checkValueXMLItem($item['product_category']['category']);
						$categories = $this->reparseCategories($categories);
						$category   = end($categories);

						if (!empty($category['@attributes']['id'])){
							$category = $this->getCategory($category['@attributes']['id']);
						} else {
							$category = false;
						}
					}			

					if (empty($item['manufacturer'])){
						$item['manufacturer'] = 'NoName';
					}

					$product_attributes = [];
					if (!empty($item['product_filter'])){
						if (!empty($item['product_filter']['filter'])){
							$filters = checkSingleXMLItemByField($item['product_filter']['filter'], 'name');

							foreach ($filters as $filter){
								if (!in_array(mb_ucfirst(checkCDATA($filter['group_name'])), $this->exclude_attributes)){
									$product_attributes[mb_ucfirst(checkCDATA($filter['group_name']))] = [
										'name' 	=> [
											'ru' => mb_ucfirst(checkCDATA($filter['group_name'])),
										],
										'text' => [
											'ru' => mb_ucfirst(checkCDATA($filter['name']))
										]
									];				
								}					
							}
						}
					}

					if (!empty($item['product_attribute'])){
						if (!empty($item['product_attribute']['attribute'])){
							$attributes = checkSingleXMLItemByField($item['product_attribute']['attribute'], 'group_ru');						

							foreach ($attributes as $attribute){
								if (!in_array(mb_ucfirst(checkCDATA($attribute['group_ru'])), $this->exclude_attributes)){
									$product_attributes[mb_ucfirst(checkCDATA($attribute['group_ru']))] = [
										'name' 	=> [
											'ru' => mb_ucfirst(checkCDATA($attribute['group_ru'])),
											'ua' => checkCDATA($attribute['group_ua']),
										],
										'text' => [
											'ru' => mb_ucfirst(checkCDATA($attribute['attribute_ru'])),
											'ua' => checkCDATA($attribute['attribute_ua']),
										]
									];							
								}
							}
						}
					}

					if (empty($item['model']) && !empty($item['sku'])){
						$item['model'] = $item['sku'];
					}

					if (empty($item['stock_status']) && !empty($item['model'])){
						$item['sku'] = $item['model'];
					}

					$products[] = [
						'supplier_product_id' 	=> $item['product_id'],
						'status'				=> $item['status'],
						'name'					=> $name,
						'description' 			=> $description,
						'model' 				=> $item['model'],
						'sku' 					=> $item['sku'],
						'image' 				=> $image,
						'images' 				=> $images,
						'stock' 				=> ($item['stock_status'] == 'In Stock')?true:false,
						'quantity' 				=> (int)$item['quantity'],
						'price' 				=> (float)$item['price'],						
						'vendor' 				=> $this->vendor($item['manufacturer'], 'vendor'),
						'vendor_country'		=> $this->vendor($item['manufacturer'], 'country'),
						'category'  			=> $category,
						'attributes'            => $product_attributes,
						'raw' 					=> json_encode($item)
					];

				}
			}
		}

		return $products;
	}
}