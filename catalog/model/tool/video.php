<?php
class ModelToolVideo extends Model {



	public function getPath($video){

			if (!file_exists(DIR_IMAGE . $video)){
				return '';
			}

			$VideoServer = $this->config->get('config_ssl');	


			if ($this->config->get('config_img_ssl')){					
				$VideoServer = $this->config->get('config_img_ssl');
			}

			if ($this->config->get('config_static_subdomain')){
				$VideoServer = $this->config->get('config_static_subdomain');
			}

			if ($this->config->get('config_img_ssls') && ($this->config->get('config_img_server_count') || $this->config->get('config_img_server_count') === '0')){					
				$first_symbol = ord(basename($video));
				$VideoServer = str_replace('{N}', ($first_symbol % ($this->config->get('config_img_server_count')+1)), $this->config->get('config_img_ssls')); 
			}

			return $VideoServer . DIR_IMAGE_NAME . $video;			
		}

















}