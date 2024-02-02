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












}