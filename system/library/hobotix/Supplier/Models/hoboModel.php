<?php

namespace hobotix\Supplier\Model;

class hoboModel{
	public function __construct($registry){
			$this->registry 		= $registry;
			$this->config 			= $registry->get('config');			
			$this->cache 			= $registry->get('cache');
			$this->db 				= $registry->get('db');
			$this->log 				= $registry->get('log');	

			if ($this->config->get('config_rainforest_enable_translation')){
				$this->translateAdaptor = $registry->get('translateAdaptor');
			}
	}
}