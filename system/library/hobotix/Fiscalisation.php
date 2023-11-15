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
        	$this->db->ncquery("UPDATE `order` SET payment_code = '" . $this->db->escape($paid_by) . "' WHERE order_id = '" . (int)$order_id . "'");
    	}

    	public function getOrdersPaidByNonFiscalised($paid_by){
    		$this->db->ncquery("SELECT DISTINCT order_id FROM `order` WHERE paid_by = '" . $this->db->escape($paid_by) . "' AND ");

    	}

    	public function addFiscalInformation($data){
    		$sql = "INSERT IGNORE INTO order_receipt SET         
    			order_id            = '" . (int)$data['order_id'] . "', 
    			receipt_id          = '" . $this->db->escape($request['id']) . "',  
    			serial              = '" . (int)$request['serial'] . "',  
    			status              = '" . $this->db->escape($request['status']) . "',  
    			fiscal_code         = '" . $this->db->escape($request['fiscal_code']) . "',  
    			fiscal_date         = '" . $this->db->escape($request['fiscal_date']) . "',  
    			is_created_offline  = '" . (int)$request['is_created_offline'] . "',  
    			is_sent_dps         = '" . (int)$request['is_sent_dps'] . "',  
    			sent_dps_at         = '" . $this->db->escape($request['sent_dps_at']) . "',  
    			type                = '" . $this->db->escape($request['type']) . "',  
    			all_json_data       = '" . $this->db->escape(json_encode($request)) . "'";

    		$this->db->ncquery( $sql );     
    	}


	}