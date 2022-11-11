<?php
	class Video {
		private $file = null;
		private $image = null;		
		
		public function __construct($file) {
			if (file_exists($file)) {
				$this->file = $file;

			}
		}

		public function getPath(){

			if (isnull($this->file)){
				return '';
			}

			$video_server = $this->config->get('config_ssl');	


			if ($this->config->get('config_img_ssl')){					
				$video_server = $this->config->get('config_img_ssl');
			}

			if ($this->config->get('config_static_subdomain')){
				$video_server = $this->config->get('config_static_subdomain');
			}


			if ($this->config->get('config_img_ssls') && ($this->config->get('config_img_server_count') || $this->config->get('config_img_server_count') === '0')){					
				$first_symbol = ord($basename);
				$video_server = str_replace('{N}', ($first_symbol % ($this->config->get('config_img_server_count')+1)), $this->config->get('config_img_ssls')); 
			}

			return $video_server . $this->file;			
		}


	}