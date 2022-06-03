<?php
	class ModelToolImage extends Model {
		public function resize($filename, $width, $height, $type = "", $quality = IMAGE_QUALITY, $do_not_resize = false, $webp = false) {
			
			$uri = '';
			if (isset($this->request->server['REQUEST_URI'])) {
				$uri = $this->request->server['REQUEST_URI'];
			}
			
			if (stripos($uri, 'admin') === false) {
				if (isset($this->request->server['HTTP_ACCEPT']) && isset($this->request->server['HTTP_USER_AGENT'])) {
					if( strpos( $this->request->server['HTTP_ACCEPT'], 'image/webp' ) !== false ) {	
						$webp = true;	
					}
				}
			}
			
			if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
				$filename = 'no_image.jpg';
			} 
			
			$info = pathinfo($filename);
			
			$extension = $info['extension'];
			$basename = $info['basename'];
			$dirname = $info['dirname'];
			
			if ($webp) {
				$extension = 'webp';
			}
			
			$old_image = $filename;
			
			$new_image_struct = Image::cachedname($filename, $extension, array($width, $height, $quality, $do_not_resize, $webp));
			$new_image = $new_image_struct['full_path'];
			$new_image_relative = $new_image_struct['relative_path'];
			
			if (!file_exists($new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime($new_image))) {				
				$image = new Image(DIR_IMAGE . $old_image);

				if (!$do_not_resize){
					$image->resize($width, $height, $type);
				}								
				if ($webp){
					$image->savewebp($new_image, $quality);	
					} else {
					$image->save($new_image, $quality);	
				}
			}
			
			
			//давайте получим сервер
			//дефолтно у нас конфиг_урл - он существует в 100% случаев
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$img_server = $this->config->get('config_ssl');	
				
				
				if ($this->config->get('config_img_ssl')){
					//подмена сервера на img_url - первый шард-сервер, если он существует
					$img_server = $this->config->get('config_img_ssl');
				}
				
				if ($this->config->get('config_img_ssls') && ($this->config->get('config_img_server_count') || $this->config->get('config_img_server_count') === '0')){
					//У нас существует несколько дополнительных шард-серверов
					$first_symbol = ord($basename);
					
					$img_server = str_replace('{N}', ($first_symbol % ($this->config->get('config_img_server_count')+1)), $this->config->get('config_img_ssls')); 
				}
				
				} else {
				$img_server = $this->config->get('config_url');	
				
				if ($this->config->get('config_img_url')){
					//подмена сервера на img_url - первый шард-сервер, если он существует
					$img_server = $this->config->get('config_img_url');
				}
				
				if ($this->config->get('config_img_urls') && ($this->config->get('config_img_server_count') || $this->config->get('config_img_server_count') === '0')){
					//У нас существует несколько дополнительных шард-серверов
					$first_symbol = ord($basename);
					
					$img_server = str_replace('{N}', ($first_symbol % ($this->config->get('config_img_server_count')+1)), $this->config->get('config_img_urls')); 
				}
			}
			
			return $img_server . $new_image_relative;				
		}
		
		public function getMime($file){
			$info = pathinfo($file);			
			$extension = !empty($info['extension'])?strtolower($info['extension']):'';
			
			if ($extension == 'jpeg' || $extension == 'jpg') {
				return 'image/jpeg';
				} elseif ($extension == 'png') {
				return 'image/png';
				} elseif ($extension == 'gif') {
				return 'image/gif';
				} elseif ($extension == 'webp') {
				return 'image/webp';
			}
			
			return '';
			
		}
		
		public function resize_webp($filename, $width, $height, $type = "", $quality = IMAGE_QUALITY, $do_not_resize = false){
			return $this->resize($filename, $width, $height, $type = "", $quality, $do_not_resize, $webp = true);
		}
	}			