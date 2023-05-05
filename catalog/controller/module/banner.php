<?php  
class ControllerModuleBanner extends Controller {
	protected function index($setting) {
		static $module = 0;
		
		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->data['slides'] = [];		
		$results = $this->model_design_banner->getBanners($setting['banner_id']);

		foreach ($results as $result){

			$this->data['slides'][$result['banner_id']] = [
				'banner_id' => $result['banner_id'],
				'class'  	=> $result['class'],
				'class_sm'  => $result['class_sm'],
				'images'  	=> []
			];


			foreach ($result['images'] as $image){
				if (empty($image['width'])){
					$image['width'] = $setting['width'];
				}

				if (empty($image['height'])){
					$image['height'] = $setting['height'];
				}

				if (empty($image['width_sm'])){
					$image['width_sm'] = $setting['width_sm'];
				}

				if (empty($image['height_sm'])){
					$image['height_sm'] = $setting['height_sm'];
				}


				if (file_exists(DIR_IMAGE . $image['image'])) {
					$this->data['slides'][$result['banner_id']]['images'][] = [
						'title' 	=> $image['title'],
						'link'  	=> $image['link'],
						'class' 	=> $image['class'],
						'width' 	=> $image['width'],
						'height' 	=> $image['height'],
						'class_sm' 	=> $image['class_sm'],
						'width_sm' 	=> $image['width_sm'],
						'height_sm' => $image['height_sm'],
						'image'		=> $this->model_tool_image->resize($image['image'], $image['width'], $image['height']),
						'image_sm'	=> $this->model_tool_image->resize($image['image_sm'], $image['width_sm'], $image['height_sm']),
					];
				}
			}
		}
		
		$this->data['module'] = $module++;
		
		$this->template = 'module/banner_constructor';
		
		$this->render();
	}
}