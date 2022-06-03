<?php  
class ControllerModuleCookLink extends Controller {
	private $posts_url = null;
	private $media_url = null;
	private $image_dir = 'data/cook';

	
	protected function index($module) {

		$this->posts_url = $this->config->get('config_main_wp_blog_domain') . '/wp-json/posts';
		$this->media_url = $this->config->get('config_main_wp_blog_domain') . '/wp-json/media';
		
		$debug = isset($_GET['debug']);
		
		if (!is_dir(DIR_IMAGE . $this->image_dir)) {
			mkdir(DIR_IMAGE . $this->image_dir, 0755);         	
		}
		
		$store_id = $this->config->get('config_store_id');
		$language_id = $this->config->get('config_language_id');
		
		if (!$module['amount_caches']){
			$module['amount_caches'] = 5;			
		}
		
		$cache_block = mt_rand(0, $module['amount_caches']);		
		$this->bcache->SetFile('module_' . $language_id . md5(serialize($module)) . '_' . $cache_block . '.tpl', 'kpcook_module' . $store_id);
		
		if ($this->bcache->CheckFile()) {		
		
			$out = $this->bcache->ReturnFileContent();
			$this->setBlockCachedOutput($out);
			
		} else {
		
		$this->language->load('module/cooklink');
		$this->load->model('tool/image');

		
		$this->data['heading_title'] = (isset($module['title']))?$module['title']:'';
		
		if (!$module['amount_single']){
			$module['amount_single'] = 3;			
		}
		if (!$module['width']){
			$module['width'] = 200;			
		}
		if (!$module['height']){
			$module['height'] = 200;			
		}
		
		$this->data['position'] = $module['position'];
		
		$limit = (int)$module['amount_single'];
		
		//constructing params		
		$post_type = 'post';
		$param_string = '';
		if (mb_strlen($module['query']) > 2){			
			$params = explode(';', trim($module['query']));

			if (is_array($params)){			
				foreach ($params as $param){
					$param_value = explode(':', $param);
					
					if (isset($param_value[0]) && isset($param_value[1])) {
						if ($param_value[0] == 'post_type'){
							$post_type = $param_value[1];
						} else {
							$param_string .= '&filter[' . $param_value[0] . ']=' . $param_value[1];
						}
					}
				}								
			}			
		}
		$param_string = '?type=' . $post_type . $param_string . '&filter[posts_per_page]=' . $limit;
		
		if ($debug) {
			var_dump($this->posts_url . $param_string);
		}
		
		$json = @file_get_contents($this->posts_url . $param_string);		

		$this->data['posts'] = array();
		
		if (!(($posts = json_decode($json, true)) === NULL)){			
						
			foreach ($posts as $post){

				//сохраним картинку
				
				if (is_array($post['featured_image']) && isset($post['featured_image']['guid']) && $post['featured_image']['guid']) {					
					$remote_image_file = $post['featured_image']['guid'];
					//копируем файл себе
					
					$allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
					
					if (in_array(pathinfo($remote_image_file, PATHINFO_EXTENSION), $allowed_extensions)){
						$local_image_file = DIR_IMAGE . $this->image_dir .'/'. md5($remote_image_file) .'.'. pathinfo($remote_image_file, PATHINFO_EXTENSION);
						
						if (!file_exists($local_image_file)) {
							file_put_contents($local_image_file, @file_get_contents($remote_image_file));
						}
						
						$image = $this->image_dir .'/'. pathinfo($local_image_file, PATHINFO_BASENAME);
					} else {
						$image = 'no_image.jpg';						
					}															
				} else {
					$image = 'no_image.jpg';
				}
				
				$excerpt = str_replace('[…]','',$post['excerpt']);
				$excerpt = str_replace('[recipe]','', $excerpt);
								
				$this->data['posts'][] = array(
					'image' => $this->model_tool_image->resize($image, $module['width'], $module['height']),
					'title'	=> $post['title'],
					'href' => $post['link'],
					'excerpt' => trim($excerpt)
				);				
			}
						
			
		}
		
		//var_dump($this->data['posts']);
		
	
		
	

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cooklink.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/cooklink.tpl';
		} else {
			$this->template = 'default/template/module/cooklink.tpl';
		}

			$out = $this->render();
			$this->bcache->WriteFile($out);
		}			
	}
}
?>