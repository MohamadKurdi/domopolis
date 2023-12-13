<?

namespace hobotix\Supplier\Adaptors;

class PosudaUAYMLv1 extends SuppliersGeneralClass {
	protected 	$type = 'XML';

	public function getCategories(){
		$categories = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['yml_catalog'])){
			if (!empty($this->content['yml_catalog']['shop']['categories'])){
				foreach ($this->content['yml_catalog']['shop']['categories']['category'] as $key => $item){				
					$categories[$item['@attributes']['id']] = [
						'category_id' 		=> $item['@attributes']['id'],
						'parent_id' 		=> !empty($item['@attributes']['parentId'])?($item['@attributes']['parentId']):'',
						'category_name' 	=> $item['@value'],
					];					
				}
			}
		}

		return $categories;
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
	
	public function getProducts(){
		$products = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		$this->setCategories();

		if (!empty($this->content['yml_catalog'])){
			if (!empty($this->content['yml_catalog']['shop']['offers'])){
				foreach ($this->content['yml_catalog']['shop']['offers']['offer'] as $key => $item){	

					$image 	= '';	
					$images = [];

					if (is_array($item['picture'])){
						$image = $item['picture'][0];

						if (count($item['picture']) > 1){
							array_shift($item['picture']);
							foreach ($item['picture'] as $picture){
								if ($picture){
									$images[] = $picture;
								}							
							}
						}						
					} else {
						$image = $item['picture'];
					}

					$category = '';
					if (!empty($item['categoryId'])){
						$category = $this->getCategory($item['categoryId']);
					}					

					if (empty($item['vendor'])){
						$item['vendor'] = 'NoName';
					}

					$id = false;
					if (!empty($item['@attributes'])){
						$id = $item['@attributes']['id'];
					} elseif (!empty($item['id'])){
						$id = $item['id'];
					}

					$products[] = [
						'supplier_product_id' 	=> $id,
						'name'					=> [0 => $item['name']],
						'description' 			=> [0 => !empty($item['description'])?$item['description']:''],
						'model' 				=> $item['code'],
						'sku' 					=> $item['code'],
						'image' 				=> $image,
						'images' 				=> $images,
						'stock' 				=> ($item['stock'] == 'В наличии')?true:false,
						'price' 				=> (float)$item['priceRUAH'],						
						'vendor' 				=> $this->vendor($item['vendor'], 'vendor'),
						'vendor_country'		=> $this->vendor($item['vendor'], 'country'),
						'category'  			=> $category,
						'raw' 					=> json_encode($item)
					];

				}
			}
		}

		return $products;
	}
}