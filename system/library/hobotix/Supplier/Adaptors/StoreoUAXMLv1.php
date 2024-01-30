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


}