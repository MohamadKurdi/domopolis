<?

namespace hobotix;


class SupplierAdaptor
{

	private $db;	
	private $config;		
	private $log;		

	public function __construct($registry){

		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');






	}



















}