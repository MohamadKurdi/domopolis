<?php
	class ModelToolImage extends Model {
		public function resize($filename, $width, $height, $return_real_path = false) {
			
			$uri = '';
			if (isset($this->request->server['REQUEST_URI'])) {
				$uri = $this->request->server['REQUEST_URI'];
			}
			
			$webp = false;
			
			if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
				$filename = 'no_image.jpg';
			} 
			
			$info = pathinfo($filename);
			
			$extension = $info['extension'];
			$basename = $info['basename'];
			$dirname = $info['dirname'];
			
			$old_image = $filename;
			$new_image_struct = Image::cachedname($filename, $extension, [$width, $height, IMAGE_QUALITY, false, $webp]);
			$new_image = $new_image_struct['full_path'];
			$new_image_relative = $new_image_struct['relative_path'];
			
			if (!file_exists($new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime($new_image))) {								
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height);
				$image->save($new_image, IMAGE_QUALITY);	
			}
			
			
			if ($return_real_path){
				return $new_image;
			}
			
			return HTTPS_CATALOG . $new_image_relative;			
		}
		
		public function greyscale($filename, $width = 0, $height = 0, $resize = false) {
			if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
				return;
			} 
			
			$info = pathinfo($filename);
			
			$extension = $info['extension'];
			
			$old_image = $filename;
			
			$new_image_struct = Image::cachedname($filename, $extension, array($width, $height, IMAGE_QUALITY, false, false, 'greyscale'));
			$new_image = $new_image_struct['full_path'];
			$new_image_relative = $new_image_struct['relative_path'];
			
			if (!file_exists($new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime($new_image))) {				
				
				list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);
				
				$image = new Image(DIR_IMAGE . $old_image);

				if ($resize) {
					$image->resize($width, $height);
				}
				$image->greyscale();
				$image->save($new_image, IMAGE_QUALITY);
			}
			
			return HTTPS_CATALOG . $new_image_relative;		
		}
	}					