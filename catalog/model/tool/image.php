<?php
class ModelToolImage extends Model {	
	public function resize($filename, $width, $height, $type = '', $quality = IMAGE_QUALITY, $do_not_resize = false, $webp = false, $avif = false) {

		$uri = '';
		if (isset($this->request->server['REQUEST_URI'])) {
			$uri = $this->request->server['REQUEST_URI'];
		}

		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			$filename = 'no_image.jpg';
		} 	

		$info 		= pathinfo($filename);
		$extension 	= $info['extension'];
		$basename 	= $info['basename'];
		$dirname 	= $info['dirname'];		

		if (stripos($uri, 'admin') === false) {
			if (!empty(IMAGE_CONVERT_FALLBACK[$extension])){
				if (IMAGE_CONVERT_FALLBACK[$extension] == 'default'){
					$webp = false;
					$avif = false;

					if ($quality == IMAGE_QUALITY){
						$quality = IMAGE_JPEG_QUALITY;
					}

				} elseif (IMAGE_CONVERT_FALLBACK[$extension] == 'webp'){
					$avif = false;	
					if (WEBPACCEPTABLE) {				
						$webp = true;

						if ($quality == IMAGE_QUALITY){
							$quality = IMAGE_WEBP_QUALITY;
						}
					}
				}
			} else {
				if (WEBPACCEPTABLE) {				
					$webp = true;					
				}

				if (AVIFACCEPTABLE) {				
					$avif = true;					
				}		
			}	
		}		

		if ($webp) {
			$extension = 'webp';
		}

		if ($avif) {
			$extension = 'avif';
		}

		$old_image = $filename;

		$new_image_struct 	= Image::cachedname($filename, $extension, array($width, $height, $quality, $do_not_resize));
		$new_image 			= $new_image_struct['full_path'];
		$new_image_relative = $new_image_struct['relative_path'];

		if (IS_DEBUG || (!file_exists($new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime($new_image)))) {				
			$image = new Image(DIR_IMAGE . $old_image);

			if (!$do_not_resize){
				$image->resize($width, $height, $type);
			}	

			if ($avif){
				$image->saveavif($new_image, $quality);	
			} elseif ($webp){
				$image->savewebp($new_image, $quality);	
			} else {
				$image->save($new_image, $quality);	
			}
		}


		//давайте получим сервер
		//дефолтно у нас конфиг_урл - он существует в 100% случаев
		$img_server = $this->config->get('config_ssl');	


		if ($this->config->get('config_img_ssl')){					
			$img_server = $this->config->get('config_img_ssl');
		}

		if ($this->config->get('config_img_ssls') && ($this->config->get('config_img_server_count') || $this->config->get('config_img_server_count') === '0')){					
			$first_symbol = ord($basename);
			$img_server = str_replace('{N}', ($first_symbol % ($this->config->get('config_img_server_count')+1)), $this->config->get('config_img_ssls')); 
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
		} elseif ($extension == 'avif') {
			return 'image/avif';		
		} elseif ($extension == 'webp') {
			return 'image/webp';
		}

		return '';

	}

	public function resize_webp($filename, $width, $height, $type = '', $quality = IMAGE_QUALITY, $do_not_resize = false){
		return $this->resize($filename, $width, $height, $type = '', $quality, $do_not_resize, $webp = true, $avif = false);
	}

	public function resize_avif($filename, $width, $height, $type = '', $quality = IMAGE_QUALITY, $do_not_resize = false){
		return $this->resize($filename, $width, $height, $type = '', $quality, $do_not_resize, $webp = false, $avif = true);
	}

}			