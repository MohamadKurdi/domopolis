<?

namespace hobotix\Supplier\Adaptors;

class GrillMarketYMLv1 extends SuppliersGeneralClass {
	protected 	$type = 'XML';

	public function getCategories(){
		$categories = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		$tmp_categories = [];
		if (!empty($this->content['yml_catalog'])){
			if (!empty($this->content['yml_catalog']['shop'])){
				if (!empty($this->content['yml_catalog']['shop']['categories'])){
					foreach ($this->content['yml_catalog']['shop']['categories']['category'] as $key => $item){
						$tmp_categories[$item['@attributes']['id']] = $item['@value'];
					}
				}
			}
		}


		if (!empty($this->content['yml_catalog'])){
			if (!empty($this->content['yml_catalog']['shop'])){
				if (!empty($this->content['yml_catalog']['shop']['categories'])){
					foreach ($this->content['yml_catalog']['shop']['categories']['category'] as $key => $item){		

						$category_name_full = $item['@value'];
						if (!empty($item['@attributes']['parentId'])){
							if (!empty($tmp_categories[$item['@attributes']['parentId']])){
								$category_name_full = ($tmp_categories[$item['@attributes']['parentId']] . ' > ' . $category_name_full);
							}
						}


						$categories[$item['@attributes']['id']] = [
							'category_id' 		=> $item['@attributes']['id'],
							'parent_id' 		=> !empty($item['@attributes']['parentId'])?$item['@attributes']['parentId']:'',
							'category_name' 	=> $item['@value'],
							'category_name_full'=> $category_name_full,
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
							if (!empty($offer['name'])){
								$name['ru'] = checkCDATA($offer['name']);
							}							

							$description = [];
							if (!empty($offer['description'])){
								$description['ru'] = checkCDATA($offer['description']);
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
											'ru' => mb_ucfirst(checkCDATA($param['@attributes']['name']))											
										],
										'text' => [
											'ru' => mb_ucfirst(checkCDATA($param['@value']))
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
								'image' 				=> $image,
								'images' 				=> $images,
								'stock' 				=> ((bool)$offer['@attributes']['available'])?true:false,
								'quantity' 				=> (int)$offer['quantity_in_stock'],
								'price' 				=> (float)$offer['price'],						
								'vendor' 				=> $offer['vendor'],
								'category'  			=> $offer['categoryId'],
								'attributes'            => $product_attributes,
								'raw' 					=> json_encode($offer)
							];
						}
					}
				}
			}
		}

		var_dump($products);die();

		return $products;
	}

}