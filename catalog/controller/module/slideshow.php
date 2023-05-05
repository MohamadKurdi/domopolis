<?php  
class ControllerModuleSlideshow extends Controller {
	protected function index($setting) {
		static $module = 0;
		
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		
		$this->data['width'] 	= $setting['width'];
		$this->data['height'] 	= $setting['height'];
		
		$this->data['width_sm'] 	= $setting['width_sm'];
		$this->data['height_sm'] 	= $setting['height_sm'];
		
		$this->data['banners'] 		= array();
		
		if (isset($setting['banner_id'])) {
			$results = $this->model_design_banner->getBanner($setting['banner_id']);					

			foreach ($results as $result) {
				if (file_exists(DIR_IMAGE . $result['image'])) {
					$this->data['banners'][] = array(
						'title' 		=> $result['title'],
						'link'  		=> !empty($result['link_o'])?$result['link_o']:$result['link'],
						'block_text'  	=> html_entity_decode($result['block_text'], ENT_COMPAT, 'UTF-8'),
						'button_text'  	=> html_entity_decode($result['button_text'], ENT_COMPAT, 'UTF-8'),
						'image' 		=> $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),	
						'image_sm' 		=> $this->model_tool_image->resize($result['image_sm'], $setting['width_sm'], $setting['height_sm']),
					);
				}
			}
		}
		
		$this->data['module'] = $module++;
						
		$this->template = 'module/slideshow';
		
		$this->render();
	}
}