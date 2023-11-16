<?php
	
	namespace hobotix;
	
	
	final class Fiscalisation
	{

		private $db		 = null;	
		private $config	 = null;

		public const formats = ['html', 'pdf', 'text', 'png', 'qrcode'];

		private $api_endpoints = [
			'checkbox' => 'https://api.checkbox.in.ua/api/v1/receipts/',
			'cashdesk' => 'https://api.cashdesk.com.ua/check/',
			'webcheck' => 'https://che.ck.ua/'
		];

		private $api_supported_formats = [
			'checkbox' => ['html', 'pdf', 'text', 'png', 'qrcode'],
			'cashdesk' => ['html', 'pdf', 'text', 'png', 'qrcode'],
			'webcheck' => ['pdf']
		];	

		public function __construct($registry){
			$this->config 	= $registry->get('config');
			$this->db 		= $registry->get('db');
		}

		public function setOrderPaidBy($order_id, $paid_by){
        	$this->db->ncquery("UPDATE `order` SET paid_by = '" . $this->db->escape($paid_by) . "' WHERE order_id = '" . (int)$order_id . "'");
        	$this->db->ncquery("UPDATE `order` SET payment_code = '" . $this->db->escape($paid_by) . "' WHERE order_id = '" . (int)$order_id . "'");
        	$this->addOrderToQueue($order_id);
    	}

    	public function getOrdersPaidByNonFiscalised($paid_by){
    		$query = $this->db->ncquery("SELECT DISTINCT order_id FROM `order` WHERE paid_by = '" . $this->db->escape($paid_by) . "' AND order_id NOT IN (SELECT order_id FROM order_receipt)");

    		return $query->rows;
    	}

    	public function addReceipt($data){
    		$sql = "INSERT IGNORE INTO order_receipt SET         
    			order_id            = '" . (int)$data['order_id'] . "', 
    			receipt_id          = '" . $this->db->escape($data['receipt_id']) . "',  
    			serial              = '" . (int)$data['serial'] . "',  
    			status              = '" . $this->db->escape($data['status']) . "',  
    			fiscal_code         = '" . $this->db->escape($data['fiscal_code']) . "',  
    			fiscal_date         = '" . $this->db->escape($data['fiscal_date']) . "',  
    			is_created_offline  = '" . (int)$data['is_created_offline'] . "',  
    			is_sent_dps         = '" . (int)$data['is_sent_dps'] . "',  
    			sent_dps_at         = '" . $this->db->escape($data['sent_dps_at']) . "',  
    			type                = '" . $this->db->escape($data['type']) . "',  
    			api                	= '" . $this->db->escape($data['api']) . "',
    			all_json_data       = '" . $this->db->escape(json_encode($data['all_json_data'])) . "'";

    		$this->db->ncquery( $sql );     

    		$this->addOrderToQueue($data['order_id']);
    	}

    	public function getOrderReceipt($order_id){
    		$sql = "SELECT * FROM order_receipt WHERE order_id = '".  (int)$order_id . "' LIMIT 1";

    		$query = $this->db->ncquery( $sql );       
    		if ($query->num_rows){
    			return $query->row;
    		}

    		return false;
    	}

    	public function checkIfOrderReceiptAlreadyExits($order_id){
    		$sql = "SELECT * FROM order_receipt WHERE order_id = '".  (int)$order_id . "'";

    		$query = $this->db->ncquery( $sql );       
    		if ($query->num_rows){
    			return true;
    		}

    		return false;
    	}

    	public function checkIfReceiptAlreadyExits($receipt_id){
    		$sql = "SELECT * FROM order_receipt WHERE receipt_id = '". $this->db->escape($receipt_id) . "'";

    		$query = $this->db->ncquery( $sql );       
    		if ($query->num_rows){
    			return true;
    		}

    		return false;
    	}


    	public function getReceiptLinks($receipt_id){    	
    		$result = [];

    		if (empty($receipt_id)){
    			return $result;
    		}

    		$query = $this->db->query("SELECT * FROM order_receipt WHERE receipt_id = '" . $this->db->escape($receipt_id) . "' LIMIT 1");

    		foreach (self::formats as $format){
    			$receipt_link = $this->getReceiptLink($receipt_id, $format, $query->row);

    			if ($receipt_link){
    				$result[mb_strtoupper($format)] = [
    					'type' => mb_strtoupper($format),
    					'link' => $receipt_link
    				];
    			}
    		}

    		return $result;
    	}


    	public function getReceiptLink($receipt_id, $format = 'html', $receipt_data = []){
    		if (!$receipt_data){
    			$query = $this->db->query("SELECT * FROM order_receipt WHERE receipt_id = '" . $this->db->escape($receipt_id) . "' LIMIT 1");
    			$receipt_data = $query->row;
    		}    		

    		if (!empty($this->api_endpoints[$receipt_data['api']])){
    			$api_url 				= $this->api_endpoints[$receipt_data['api']];
    			$api_supported_formats 	= $this->api_supported_formats[$receipt_data['api']];
    		} else {
    			return false;
    		}

    		if (!in_array($format, $api_supported_formats)){
    			return false;
    		}

    		switch ($format) {
    			case 'pdf':
    			$url = $api_url . $receipt_id .'/pdf';	    			
    			break;

    			case 'text':
    			$url = $api_url . $receipt_id .'/text';
    			break;

    			case 'png':
    			$url = $api_url . $receipt_id .'/png';
    			break;

    			case 'qrcode':
    			$url = $api_url . $receipt_id .'/qrcode';
    			break;

    			default:
    			$url = $api_url . $receipt_id .'/html';
    			break;
    		}

    		return $url;
    	}

    	public function addOrderToQueue($order_id){
			$this->db->query("INSERT IGNORE INTO order_to_1c_queue SET `order_id` = '" . (int)$order_id . "'");
		}
		
		public function removeOrderFromQueue($order_id){
			$this->db->query("DELETE FROM order_to_1c_queue WHERE `order_id` = '" . (int)$order_id . "'");
		}
	}