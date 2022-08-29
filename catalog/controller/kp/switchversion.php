<?
	
	class ControllerKPSwitchVersion extends Controller {
		
		private function checkIfNowIsNewVersion(){			
			return ($this->config->get('config_template') == 'kp');			
		}

		public function convert(){
			$this->load->model('tool/image');

			$image = $this->model_tool_image->resize('data/logo/brabantia-logo.png', 218, 218);
					
			header('Content-Type: image/webp');
			echo file_get_contents($image);
		}


		public function convert2(){
			$width = $height = 120;

			$image = new Imagick('/home/kp/web/kitchen-profi.ru/public_html/image/data/logo/brabantia-logo.png');
			$alphaChannel = $image->getImageAlphaChannel();

			//$image->thumbnailImage(100, 100, \Imagick::FILTER_CATROM, 1, );	 
			$image->thumbnailImage($width, $height, 1, 0);

			$this->width = $image->getImageWidth();
            $this->height = $image->getImageHeight();

			$image2 = new Imagick();
            if ($this->mime == 'image/png') {
                $imagickPixel = new \ImagickPixel('transparent');
            } else {
                $imagickPixel = new \ImagickPixel('white');
            }

            $image2->newImage($width, $height, $imagickPixel);          
            
            $x = (int)(($width - $this->width) / 2);
            $y = (int)(($height - $this->height) / 2);

           // var_dump($x);
           // var_dump($y);

            $image2->compositeImage($image, \Imagick::COMPOSITE_COPYOPACITY , $x, $y);
            $image2->compositeImage($image, \Imagick::COMPOSITE_DEFAULT , $x, $y);
            $image = $image2;
			

			$image->setImageFormat('webp'); 
			header('Content-Type: image/webp');
			echo $image->getImageBlob();

		}
		
		public function index(){
		
		
		
		}

	}