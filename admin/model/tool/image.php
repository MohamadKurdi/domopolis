<?php
class ModelToolImage extends Model {

	public function video($filename){
		if (file_exists(DIR_IMAGE . $filename) && is_file(DIR_IMAGE . $filename)){
			return HTTPS_IMAGE . $filename;
		} elseif (defined('DIR_IMAGE_MAIN') && file_exists(DIR_IMAGE_MAIN . $filename) && is_file(DIR_IMAGE_MAIN . $filename)){
			return HTTPS_IMAGE_MAIN . $filename;
		} else {			
			return '';
		}				
	}


	public function resize($filename, $width, $height, $type = '', $quality = IMAGE_QUALITY, $do_not_resize = false, $webp = false, $avif = false) {

		$uri = '';
		if (isset($this->request->server['REQUEST_URI'])) {
			$uri = $this->request->server['REQUEST_URI'];
		}

        if (defined('LOCALHOST_ENVIRONMENT')){
            $filename = 'no_image.jpg';
        }

		if (!$filename || !trim($filename)){
			$filename = 'no_image.jpg';
		}
		
		$DIR_IMAGE = DIR_IMAGE;	
		if (file_exists(DIR_IMAGE . $filename) && is_file(DIR_IMAGE . $filename)){			
		} elseif (defined('DIR_IMAGE_MAIN') && file_exists(DIR_IMAGE_MAIN . $filename) && is_file(DIR_IMAGE_MAIN . $filename)){
			$DIR_IMAGE = DIR_IMAGE_MAIN;		
		} else {			
			$filename = 'no_image.jpg';
		}

		$info 		= pathinfo($filename);
		$extension 	= !empty($info['extension'])?$info['extension']:'jpg';
		$basename 	= $info['basename'];
		$dirname 	= $info['dirname'];		

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

		if (!file_exists($new_image) || (filemtime($DIR_IMAGE . $old_image) > filemtime($new_image))) {				
			$image = new Image($DIR_IMAGE . $old_image);

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

	public function greyscale($filename, $width = 0, $height = 0, $resize = false) {
		if (!trim($filename)){
			$filename = 'no_image.jpg';
		}

		$DIR_IMAGE = DIR_IMAGE;	
		if (file_exists(DIR_IMAGE . $filename) && is_file(DIR_IMAGE . $filename)){

		} elseif (defined('DIR_IMAGE_MAIN') && file_exists(DIR_IMAGE_MAIN . $filename) && is_file(DIR_IMAGE_MAIN . $filename)){
			$DIR_IMAGE = DIR_IMAGE_MAIN;		
		} else {			
			return;
		}	

		$info = pathinfo($filename);

		$extension = $info['extension'];

		$old_image = $filename;

		$new_image_struct = Image::cachedname($filename, $extension, array($width, $height, IMAGE_QUALITY, false, false, 'greyscale'));
		$new_image = $new_image_struct['full_path'];
		$new_image_relative = $new_image_struct['relative_path'];

		if (!file_exists($new_image) || (filemtime($DIR_IMAGE . $old_image) > filemtime($new_image))) {				

			list($width_orig, $height_orig) = getimagesize($DIR_IMAGE . $old_image);

			$image = new Image($DIR_IMAGE . $old_image);

			if ($resize) {
				$image->resize($width, $height);
			}
			$image->greyscale();
			$image->save($new_image, IMAGE_QUALITY);
		}

		return HTTPS_CATALOG . $new_image_relative;		
	}
}					