<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerModuleGallery extends Controller {
	private $cacher = array();
	private $current_language_id;
	/*
	* Faster check category_id and product_id
	*/
	private function checkModuleLayout($setting){
		$layout_id = $setting['layout_id'];
		if ($layout_id == $this->config->get('config_gallery_module_category_layout_id')) {
			if (!empty($setting['album_show_on_categories'])) {
				if (in_array($this->getCategoryId(), $setting['album_show_on_categories'])) {
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
		}elseif ($layout_id == $this->config->get('config_gallery_module_product_layout_id')) {
			if (!empty($setting['album_show_on_products'])) {
				if (in_array($this->getProductId(), $setting['album_show_on_products'])) {
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}	
		}
		return true;
	}
	protected function index($setting) {
		$this->data['microtime'] = microtime(true);
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('catalog/gallery');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/photo_gallery.manager.css');
 		$this->current_language_id = $this->config->get('config_language_id');
		$module_cache_name = md5($setting['layout_id'].$setting['position'].$setting['name'].$this->current_language_id);

		if (!$this->checkModuleLayout($setting)) {
			return;
		}

		$this->cacher = $this->cache->get('album_module.'.$module_cache_name);
		if (empty($this->cacher) || (!$this->config->get('config_gallery_modules_cache_enabled'))) {
			$this->cacher['galleries_link'] = $this->url->link('gallery/gallery');
			switch ($setting['module_type']) {
				case 0: // Galleries
					$this->cacher['show_header'] = $setting['show_header'][$this->current_language_id];
					if ($this->cacher['show_header']) {
						$this->cacher['heading_title'] = $setting['header'][$this->current_language_id];
					}
					if (!empty($setting['album_list'])) {
						$albums = array();
						foreach ($setting['album_list'] as $key => $album_id) {
							$pre_album = $this->model_catalog_gallery->getAlbum($album_id);
							if (!empty($pre_album)) {
								$albums[$key] = $pre_album;
								//Add data
								if ($setting['show_counter']) {
									$cached = $this->cache->get('album_photos.'.md5($album_id.'0'));
									if (!empty($cached)) {
										$albums[$key]['images'] = $cached;	
									}else{
										$cached_data = $this->getAlbumImages($album_id, 0);
										$this->cache->set('album_photos.'.md5($album_id.'0'), $cached_data);
										$albums[$key]['images'] = $cached_data;
									}
								}
								//counter
								$album_name_postfix = ($setting['show_counter'] ? ' ('.count($albums[$key]['images']).')' : '');

								$albums[$key]['album_name'] = $albums[$key]['album_data']['album_name'][$this->current_language_id].$album_name_postfix;
								$albums[$key]['album_link'] = $this->url->link('gallery/photos', 'album_id='.$albums[$key]['album_id']);
								if (!empty($albums[$key]['album_data']['cover_image']['image'])) {
									$albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize($albums[$key]['album_data']['cover_image']['image'], $setting['cover_image_width'], $setting['cover_image_height']);
								}else{
									$albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.jpg', $setting['cover_image_width'], $setting['cover_image_height']);
								}

								$this->cacher['cover_image_width'] = $setting['cover_image_width'];
								$this->cacher['cover_image_height'] = $setting['cover_image_height'];
								
								$this->cacher['show_album_galleries_link'] = $setting['show_album_galleries_link'];
								$this->cacher['album_galleries_link_text'] = $setting['album_galleries_link_text'];
							}
						}
						$this->cacher['albums'] = $albums;
						if ($setting['show_covers']) {
							$this->setTemplate('gallery_gallery_grid');
						}else{
							$this->setTemplate('gallery_gallery_list');
						}
					}else{
						return;
					}

				break;
				case 1: // Photos
					$this->cacher['show_header'] = $setting['show_header'][$this->current_language_id];
					if ($this->cacher['show_header']) {
						$this->cacher['heading_title'] = $setting['header'][$this->current_language_id];
					}
					if (!empty($setting['photo_album_list'])) {
						foreach ($setting['photo_album_list'] as $key => $album_id) {
							$albums[$key] = $this->model_catalog_gallery->getAlbum($album_id);
							//Add data
							if (!empty($albums[$key]['album_data']['album_description']) && $setting['show_album_description']) {
								$albums[$key]['album_description'] = html_entity_decode($albums[$key]['album_data']['album_description'][$this->current_language_id], ENT_QUOTES, 'UTF-8');
							}

							$albums[$key]['album_name'] = $albums[$key]['album_data']['album_name'][$this->current_language_id];
							$albums[$key]['album_link'] = $this->url->link('gallery/photos', 'album_id='.$albums[$key]['album_id']);
							
							$cached = $this->cache->get('album_photos.'.md5($album_id.$setting['photos_limit']));
							if (!empty($cached)) {
								$albums[$key]['images'] = $cached;	
							}else{
								$cached = $this->getAlbumImages($album_id, $setting['photos_limit']);
								$this->cache->set('album_photos.'.md5($album_id.$setting['photos_limit']), $cached);
								$albums[$key]['images'] = $cached;
							}
							
							$this->cacher['show_album_link'] = $setting['show_album_link'];
							$this->cacher['album_link_text'] = $setting['album_link_text'];
							
							$this->cacher['gallery_thumb_image_width'] = $setting['gallery_thumb_image_width'];
							$this->cacher['gallery_thumb_image_height'] = $setting['gallery_thumb_image_height'];
							switch ($albums[$key]['album_data']['js_lib_type']) {
								case 0: // ColorBox 
									if ($this->config->get('config_gallery_include_colorbox')) {
										$this->cacher['scripts'][] = 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js';
										$this->cacher['styles'][] = 'catalog/view/javascript/jquery/colorbox/colorbox.css';
									}
									$albums[$key]['js_lib_type_text'] = 'colorbox';
								break;
								case 1: // LightBox 
									if ($this->config->get('config_gallery_include_lightbox')) {
										$this->cacher['scripts'][] = 'catalog/view/javascript/jquery/lightbox/lightbox.min.js';
										$this->cacher['styles'][] = 'catalog/view/javascript/jquery/lightbox/lightbox.css';
									}
									$albums[$key]['js_lib_type_text'] = 'lightbox';
								break;
								case 2: // FancyBox 
									if ($this->config->get('config_gallery_include_fancybox')) {
										$this->cacher['scripts'][] = 'catalog/view/javascript/jquery/fancybox/jquery.fancybox.pack.js';
										$this->cacher['styles'][] = 'catalog/view/javascript/jquery/fancybox/jquery.fancybox.css';
									}
									$albums[$key]['js_lib_type_text'] = 'fancybox';
								break;
							}

							
							foreach ($albums[$key]['images'] as $img_key => $image) {
								if (empty($image['image'])) {
									$image['thumb'] = $this->model_tool_image->resize('no_image.jpg', $setting['gallery_thumb_image_width'] , $setting['gallery_thumb_image_height']);
									$image['popup'] = $this->model_tool_image->resize('no_image.jpg', $setting['gallery_popup_image_width'] , $setting['gallery_popup_image_height']);
								}else{
									$image['thumb'] = $this->model_tool_image->resize($image['image'], $setting['gallery_thumb_image_width'] , $setting['gallery_thumb_image_height']);
									$image['popup'] = $this->model_tool_image->resize($image['image'], $setting['gallery_popup_image_width'] , $setting['gallery_popup_image_height']);										
								}
								$albums[$key]['images'][$img_key] = $image;
							}
							$this->cacher['albums'] = $albums;
						}
						
						if (count($this->cacher['albums']) > 1 ) {
							$this->setTemplate('gallery_photos_with_tabs');
						}else{
							$this->cacher['album'] = end($this->cacher['albums']);
							$this->setTemplate('gallery_photos_without_tabs');
						}
					}else{
						return;
					}
				break;
			}
			$this->cache->set('album_module.'.$module_cache_name, $this->cacher);
		}

		if (!empty($this->cacher['albums']) && $this->config->get('config_gallery_include_jstabs') && count($this->cacher['albums']) >= 2) {
			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
		}
		
		if (!empty($this->cacher['scripts'])) {
			foreach ($this->cacher['scripts'] as $key => $script) {
				$this->document->addScript($script);
			}
		}
		if (!empty($this->cacher['styles'])) {
			foreach ($this->cacher['styles'] as $key => $style) {
				$this->document->addStyle($style);
			}
		}
		
		$this->data['no_conflict'] = substr(md5(rand(0, 99)), 20);
		$this->data['microtime'] = microtime(true) - $this->data['microtime'];
		
		foreach ($this->cacher as $key => $value) {
			$this->data[$key] = $value;
		}
		$this->setTemplate($this->cacher['template_name']);
		$this->render();		
	}
	private function setTemplate($template){
		$this->cacher['template_name'] = $template;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/'.$template.'.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/'.$template.'.tpl';
		} else {
			$this->template = 'default/template/module/'.$template.'.tpl';
		}
	}
	private function getAlbumImages($album_id, $limit = 0){
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('catalog/gallery');
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$album = $this->model_catalog_gallery->getAlbum($album_id);

		$album_photos = array();
		switch ($album['album_type']) {
			case 0: //Category
				foreach ($album['album_data']['album_categories'] as $category_id) {
					$products = $this->model_catalog_category->getCategories($category_id);
					$data = array(
						'filter_category_id' => $category_id
					);
					
					$products = $this->model_catalog_product->getProducts($data);
					
					foreach ($products as $product){
						if ($product['image']) {
							$key = md5($product['image']);
							$album_photos[$key]['image'] = $product['image'];
							$album_photos[$key]['title'] = $product['name'];
						}
						if ($album['album_data']['include_additional_images']) {
							$images = $this->model_catalog_product->getProductImages($product['product_id']);
							if (!empty($images)) {
								foreach ($images as $image) {
									$key = md5($image['image']);
									$album_photos[$key]['image'] = $image['image'];
									$album_photos[$key]['title'] = $product['name'];
								}
							}
						}
					}
				}
				break;
			case 1: //Directory
				foreach (explode(PHP_EOL, $album['album_data']['album_directory']) as $directory) {
					if (!empty($directory)) {
						
					$data = glob($directory);
					$data = array_filter($data, 'is_file');
						if (!empty($data)) {
							foreach ($data as $value) {
								$key = md5($value);
								if ($value{0} == 'i') {
									$value = str_replace('image/', '', $value);
								}
								$album_photos[$key]['image'] = $value;
								$album_photos[$key]['title'] = '';
							}
						}
					}
				}
				break;
			case 2: //Custom images
				if (!empty($album['album_data']['gallery_images'])) {
					foreach ($album['album_data']['gallery_images'] as $gallery_image) {
						$key = md5($gallery_image['image']);
						$album_photos[$key]['image'] = $gallery_image['image'];
						$album_photos[$key]['title'] = $gallery_image['description'][$this->current_language_id];
					}
				}
			break;
		}
		//Limit photos
		$result = array();
		if ($limit == 0) {
			$result = $album_photos;
		}else{
			reset($album_photos);
			for ($counter =0; $counter  < $limit; $counter ++) { 
				$elem = current($album_photos);
				if (!empty($elem)) {
					$result[] = $elem;
					next($album_photos);
				}else{
					break;
				}
			}
		}
		return $result;
	}
	private function getProductId(){
		return $this->request->get['product_id'];
	}
	private function getCategoryId(){
		$path = explode('_', (string)$this->request->get['path']);
		return end($path);
	}
}
?>