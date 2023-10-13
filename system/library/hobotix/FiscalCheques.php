<?php
	
	namespace hobotix;
	
	
	final class FiscalCheques
	{

		private $db		 = null;	
		private $config	 = null;


		public function __construct($registry){
			
			$this->config 	= $registry->get('config');
			$this->db 		= $registry->get('db');

		}































	}