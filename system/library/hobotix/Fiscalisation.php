<?php
	
	namespace hobotix;
	
	
	final class Fiscalisation
	{

		private $db		 = null;	
		private $config	 = null;


		public function __construct($registry){
			$this->config 	= $registry->get('config');
			$this->db 		= $registry->get('db');
		}

		public function setOrderPaidBy($order_id, $paid_by){
        		$this->db->ncquery("UPDATE `order` SET paid_by = '" . $this->db->escape($paid_by) . "' WHERE order_id = '" . (int)$order_id . "'");
    	}


	}