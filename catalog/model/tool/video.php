<?php
class ModelToolVideo extends Model {



	public function getPath($video){
			$DIR_IMAGE_NAME = DIR_IMAGE_NAME;
			if (file_exists(DIR_IMAGE . $video) && is_file(DIR_IMAGE . $video)){
				//do nothing
			} elseif (defined('DIR_IMAGE_MAIN') && file_exists(DIR_IMAGE_MAIN . $video) && is_file(DIR_IMAGE_MAIN . $video)){
				$DIR_IMAGE_NAME = DIR_IMAGE_NAME_MAIN;
			} else {
				return '';
			}

			$videoServer = $this->config->get('config_ssl');	

			if ($this->config->get('config_img_ssl')){					
				$videoServer = $this->config->get('config_img_ssl');
			}

			if ($this->config->get('config_static_subdomain')){
				$videoServer = $this->config->get('config_static_subdomain');
			}

			if ($this->config->get('config_img_ssls') && ($this->config->get('config_img_server_count') || $this->config->get('config_img_server_count') === '0')){					
				$first_symbol = ord(basename($video));
				$videoServer = str_replace('{N}', ($first_symbol % ($this->config->get('config_img_server_count')+1)), $this->config->get('config_img_ssls')); 
			}

			return $videoServer . $DIR_IMAGE_NAME . $video;			
		}
}