<?php

	class Image {
		private $file;
		private $image;
		private $width;
		private $height;
		private $bits;
		private $mime;
		private $hasAlpha;
		private $log;

		public function __construct($file) {
			require_once(DIR_SYSTEM . 'library/log.php');
			$this->log = new Log('imagick-error.txt');

			if (is_file($file)) {            
				try {
					$this->init($file);
				} catch (\ImagickException $e) {
					$this->log->write(get_class($this) . ', ' .  $this->file . ': ' . $e->getMessage());
					$this->log->debug($e->getMessage());

					$this->init(DIR_IMAGE . 'no_image.jpg');
				}
			}
		}

		protected function init($file){
			$this->file     = $file;
			$this->image    = new \Imagick($file);
			$this->width    = $this->image->getImageWidth();
			$this->height   = $this->image->getImageHeight();
			$this->bits     = $this->image->getImageLength();
			$this->mime     = $this->image->getFormat();
			$this->hasAlpha = $this->image->getImageAlphaChannel();
		}

		public static function cachedname($filename, $extension, $data = array()){
			$md5 = md5($filename . implode('.', $data));

			$directory              = DIR_IMAGECACHE . substr($md5, 0, 3) . '/';
			$image_name             = $md5 . 'x' . $data[0] . 'x' . $data[1] . 'x' . $data[2] .  '.' . $extension;
			$full_image_path        = $directory . $image_name;
			$relative_image_path    = DIR_IMAGECACHE_NAME . substr($md5, 0, 3) . '/' . $image_name;

			if (!is_dir($directory)){
				mkdir($directory, 0775, true);
			}

			return ['full_path' => $full_image_path, 'relative_path' => $relative_image_path];
		}

		public function getFile() {
			return $this->file;
		}

		public function getImage() {
			return $this->image;
		}

		public function getWidth() {
			return $this->width;
		}

		public function getHeight() {
			return $this->height;
		}

		public function getBits() {
			return $this->bits;
		}

		public function getMime() {
			return $this->mime;
		}

		public function savepng($file, $quality = IMAGE_QUALITY) {    
			$this->image->setImageFormat('png');
			$this->save($file, $quality = IMAGE_QUALITY);
		}

		public function saveavif($file, $quality = IMAGE_QUALITY) {          
			$this->image->setImageFormat('avif');
			$this->save($file, $quality = IMAGE_QUALITY);
		}

		public function savewebp($file, $quality = IMAGE_QUALITY) {
			$this->image->setImageFormat('webp');
			$this->save($file, $quality = IMAGE_QUALITY);
		}

		public function save($file, $quality = IMAGE_QUALITY) {      
			try {
				$this->image->setCompressionQuality($quality);
				$this->image->stripImage();
				$this->image->writeImage($file);

			} catch (\ImagickException $e) {
				$this->log->write(get_class($this) . ', ' .  $this->file . ': ' . $e->getMessage());
				$this->log->debug($e->getMessage());
			}
		}

    /**
     * @param int $width
     * @param int $height
     * @param string $default
     *
     * @return void
     */
    public function resize($width = 0, $height = 0, $default = '') {

    	try {

    		if (!$width){
    			$width = $this->width;
    		}

    		if (!$width){
    			$height = $this->height;
    		}

    		if (!$this->width || !$this->height) {
    			return;
    		}

    		switch ($default) {
    			case 'w':
    			$height = $width;
    			break;
    			case 'h':
    			$width = $height;
    			break;
    		}

    		$this->image->thumbnailImage($width, $height, 1, 0);        

    		$this->width    = $this->image->getImageWidth();
    		$this->height   = $this->image->getImageHeight();

    		if ($width == $height && $this->width != $this->height) {           
    			$squareImage = new Imagick();

    			if ($this->mime == 'image/png') {
    				$imagickPixel = new \ImagickPixel('transparent');
    			} else {
    				$imagickPixel = new \ImagickPixel('white');
    			}

    			$squareImage->newImage($width, $height, $imagickPixel);          

    			$x = (int)(($width - $this->width) / 2);
    			$y = (int)(($height - $this->height) / 2);

    			$squareImage->compositeImage($this->image, \Imagick::COMPOSITE_COPYOPACITY , $x, $y);
    			$squareImage->compositeImage($this->image, \Imagick::COMPOSITE_DEFAULT , $x, $y);
    			$this->image = $squareImage;


    			$this->width = $this->image->getImageWidth();
    			$this->height = $this->image->getImageHeight();
    		}

    	} catch (\ImagickException $e) {
    		require_once(DIR_SYSTEM . 'library/log.php');
    		$this->log = new Log('imagick-error.txt');
    		$this->log->write(get_class($this) . ', ' . $this->file . ': ' . $e->getMessage());

    		$this->log->debug($e->getMessage());
    	}
    }


    /**
     * @param string $watermark
     * @param string $position
     *
     * @return void
     */
    public function watermark($watermark, $position = 'bottomright') {
    	$watermark = new Imagick($watermark);

    	switch ($position) {
    		case 'overlay':
    		for ($width = 0; $width < $this->width; $width += $watermark->getImageWidth()) {
    			for ($height = 0; $height < $this->height; $height += $watermark->getImageHeight()) {
    				$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $width, $height);
    			}
    		}
    		break;
    		case 'topleft':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, 0, 0);
    		break;
    		case 'topcenter':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, intval(($this->width - $watermark->getImageWidth()) / 2), 0);
    		break;
    		case 'topright':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $this->width - $watermark->getImageWidth(), 0);
    		break;
    		case 'middleleft':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, 0, intval(($this->height - $watermark->getImageHeight()) / 2));
    		break;
    		case 'middlecenter':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, intval(($this->width - $watermark->getImageWidth()) / 2), intval(($this->height - $watermark->getImageHeight()) / 2));
    		break;
    		case 'middleright':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $this->width - $watermark->getImageWidth(), intval(($this->height - $watermark->getImageHeight()) / 2));
    		break;
    		case 'bottomleft':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, 0, $this->height - $watermark->getImageHeight());
    		break;
    		case 'bottomcenter':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, intval(($this->width - $watermark->getImageWidth()) / 2), $this->height - $watermark->getImageHeight());
    		break;
    		case 'bottomright':
    		$this->image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $this->width - $watermark->getImageWidth(), $this->height - $watermark->getImageHeight());
    		break;
    	}
    }

   /**
     * @param int $top_x
     * @param int $top_y
     * @param int $bottom_x
     * @param int $bottom_y
     *
     * @return void
     */
   public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
   	$this->image->cropImage($bottom_x - $top_x, $bottom_y - $top_y, $top_x, $top_y);

   	$this->width = $this->image->getImageWidth();
   	$this->height = $this->image->getImageHeight();
   }

    /**
     * @param int $degree
     * @param string $color
     *
     * @return void
     */
    public function rotate($degree, $color = 'FFFFFF') {
    	$this->image->rotateImage($color, $degree);

    	$this->width = $this->image->getImageWidth();
    	$this->height = $this->image->getImageHeight();
    }

    public function greyscale(){
    	$this->image->setImageType(\Imagick::IMGTYPE_GRAYSCALEMATTE);
    }

    private function filter($filter) {
    	imagefilter($this->image, $filter);
    }

    private function text($text, $x = 0, $y = 0, $size = 5, $color = '000000') {
    	$draw = new \ImagickDraw();
    	$draw->setFontSize($size);
    	$draw->setFillColor(new \ImagickPixel($this->html2rgb($color)));
    	$this->image->annotateImage($draw, $x, $y, 0, $text);
    }

    private function merge($merge, $x = 0, $y = 0, $opacity = 100) {
    	$merge->getImage->setImageOpacity($opacity / 100);
    	$this->image->compositeImage($merge, \Imagick::COMPOSITE_ADD, $x, $y);
    }

    private function html2rgb($color) {
    	if ($color[0] == '#') {
    		$color = substr($color, 1);
    	}
    	if (strlen($color) == 6) {
    		list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    	} elseif (strlen($color) == 3) {
    		list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    	} else {
    		return false;
    	}
    	$r = hexdec($r);
    	$g = hexdec($g);
    	$b = hexdec($b);
    	return array($r, $g, $b);
    }

    function __destruct() {
    }
}