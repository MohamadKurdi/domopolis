<?php
class ModelToolVideo extends Model {



	public function getPath($video){

			if (!file_exists(DIR_IMAGE . $video)){
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
				$first_symbol = ord(basename($video));
				$video_server = str_replace('{N}', ($first_symbol % ($this->config->get('config_img_server_count')+1)), $this->config->get('config_img_ssls')); 
			}

			return $video_server . $video;			
		}

















}