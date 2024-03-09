<?

namespace hobotix\Supplier\Adaptors;

class BerghoffXMLv1 extends SuppliersGeneralClass {
	protected $type = 'XML';

	public function getCategories(){
		$categories = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['rss'])){
			if (!empty($this->content['rss']['channel']['item'])){
				foreach ($this->content['rss']['channel']['item'] as $item){
					if (!empty($item['product_type'])){
						if (is_array($item['product_type'])){
							if (!empty($item['product_type'][0])){
								$categories[$item['product_type'][0]] = $item['product_type'][0];
							} elseif (!empty($item['product_type'][1])){
								$categories[$item['product_type'][1]] = $item['product_type'][1];
							}							
						} else {
							$categories[$item['product_type']] = [
								'category_id' 		=> $item['product_type'],
								'parent_id' 		=> '',
								'category_name' 	=> $item['product_type'],
							];
						}
					}
				}
			}
		}

		return $categories;
	}

	public function getProducts(){
		$products = [];

		if (!$this->getContent()){
			$this->setContent();
		}

		if (!empty($this->content['rss'])){
			if (!empty($this->content['rss']['channel']['item'])){
				foreach ($this->content['rss']['channel']['item'] as $item){	

					$image = '';	
					if (!empty($item['image_link'])){
						$image = $item['image_link'];
					}

					$images = [];
					if (!empty($item['additional_image_link'])){
						if (is_array($item['additional_image_link'])){
							foreach ($item['additional_image_link'] as $additional_image_link){
								$images[] = $additional_image_link;
							}						
						} else {
							$images[] = $item['additional_image_link'];
						}
					}

					$category = '';
					if (!empty($item['product_type'])){
						if (is_array($item['product_type'])){
							if (!empty($item['product_type'][0])){
								$category = $item['product_type'][0];
							} elseif (!empty($item['product_type'][1])){
								$category = $item['product_type'][1];
							}							
						} else {
							$category = $item['product_type'];
						}
					}

					$products[] = [
						'supplier_product_id' 	=> $item['id'],
						'name' 					=> $item['title'],
						'sku' 					=> $item['mpn'],
						'description' 			=> $item['description'],
						'image' 				=> $image,
						'images' 				=> $images,
						'stock' 				=> ($item['availability'] == 'in stock')?true:false,
						'price' 				=> (float)str_replace('UAH', '', $item['price']),
						'price_old' 			=> 0,
						'vendor' 				=> $item['vendor'],
						'category'  			=> $category,
						'raw' 					=> json_encode($item)
					];
				}
			}
		}

		return $products;
	}
}