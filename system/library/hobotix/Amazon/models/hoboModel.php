<?php

namespace hobotix\Amazon;

class hoboModel{


	public function __construct($registry){
			$this->registry 		= $registry;
			$this->config 			= $registry->get('config');			
			$this->db 				= $registry->get('db');
			$this->log 				= $registry->get('log');	

			if ($this->config->get('config_rainforest_enable_translation')){
				$this->yandexTranslator = $registry->get('yandexTranslator');
		}
	}
}