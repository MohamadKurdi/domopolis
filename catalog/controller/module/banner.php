<?php  
class ControllerModuleBanner extends Controller {
	protected function index($setting) {
		static $module = 0;
		
		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->data['blocks'] = [];
		$this->data['blocks_sm'] = [];
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);


		foreach ($results as $result) {			
			if (empty($this->data['blocks'][$result['block']])){
				$this->data['blocks'][$result['block']] = [];
			}

			if (empty($this->data['blocks_sm'][$result['block_sm']])){
				$this->data['blocks_sm'][$result['block_sm']] = [];
			}

			if (empty($result['width'])){
				$result['width'] = $setting['width'];
			}

			if (empty($result['height'])){
				$result['height'] = $setting['height'];
			}

			if (empty($result['width_sm'])){
				$result['width_sm'] = $setting['width_sm'];
			}

			if (empty($result['height_sm'])){
				$result['height_sm'] = $setting['height_sm'];
			}

			if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['blocks'][$result['block']][] = [
					'title' 	=> $result['title'],
					'link'  	=> $result['link'],
					'class' 	=> $result['class'],
					'width' 	=> $result['width'],
					'height' 	=> $result['height'],
					'class_sm' 	=> $result['class_sm'],
					'width_sm' 	=> $result['width_sm'],
					'height_sm' => $result['height_sm'],
					'image'		=> $this->model_tool_image->resize($result['image'], $result['width'], $result['height']),
					'image_sm'	=> $this->model_tool_image->resize($result['image_sm'], $result['width_sm'], $result['height_sm']),
				];
			}

			if (file_exists(DIR_IMAGE . $result['image_sm'])) {
				$this->data['blocks_sm'][$result['block_sm']][] = [
					'title' 	=> $result['title'],
					'link'  	=> $result['link'],
					'class' 	=> $result['class'],
					'width' 	=> $result['width'],
					'height' 	=> $result['height'],
					'class_sm' 	=> $result['class_sm'],
					'width_sm' 	=> $result['width_sm'],
					'height_sm' => $result['height_sm'],
					'image'		=> $this->model_tool_image->resize($result['image'], $result['width'], $result['height']),
					'image_sm'	=> $this->model_tool_image->resize($result['image_sm'], $result['width_sm'], $result['height_sm']),
				];
			}
		}		

		
		$this->data['module'] = $module++;
		
		$this->template = 'module/banner_constructor';
		
		$this->render();
	}
}