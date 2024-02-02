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










}