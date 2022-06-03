<?
class ControllerModulencategories2 extends Controller {
	protected function index() {
//BLOG CATEGORIES
		$this->load->model('catalog/ncategory');
		$this->data['ncategories'] = array();
		$ncategories = $this->model_catalog_ncategory->getncategories(0);
		
		foreach ($ncategories as $ncategory){
			
			if ($ncategory['image']) {
					$img = $ncategory['image'];
				} else {
					$img = 'no_image.jpg';
				}
				
				
			$nchildren = $this->model_catalog_ncategory->getncategories($ncategory['ncategory_id']);
			$nchildren_data = array();
			foreach ($nchildren as $nchild){
				if ($nchild['image']) {
					$cimg = $nchild['image'];
				} else {
					$cimg = 'no_image.jpg';
				}

			//l2
				$nchildren1 = $this->model_catalog_ncategory->getncategories($nchild['ncategory_id']);
				$nchildren_data1 = array();	
				foreach ($nchildren1 as $nchild1){
							if ($nchild1['image']) {
								$c1img = $nchild1['image'];
							} else {
								$c1img = 'no_image.jpg';
							}
							
							$nchildren_data1[] = array(
								'name' => $nchild1['name'],
								'href' => $this->url->link('news/ncategory', 'ncat=' . $nchild1['ncategory_id']),
								'thumb' => $this->model_tool_image->resize($c1img, 40, 40)			
							);	
							
						//	var_dump($nchildren_data1);
					
					}
					
					
				$nchildren_data[] = array(
					'children' => $nchildren_data1,
					'name' => $nchild['name'],
					'href' => $this->url->link('news/ncategory', 'ncat=' . $nchild['ncategory_id']),
					'thumb' => $this->model_tool_image->resize($cimg, 40, 40)			
			);
				
			}
			
			
			
			$this->data['ncategories'][] = array(
				'children' => $nchildren_data,			
				'name' => $ncategory['name'],
				'href' => $this->url->link('news/ncategory', 'ncat=' . $ncategory['ncategory_id']),
				'thumb' => $this->model_tool_image->resize($img, 40, 40)
			
			);
						
		}
	}
	
?>