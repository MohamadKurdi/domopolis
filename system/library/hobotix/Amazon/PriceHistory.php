<?

namespace hobotix\Amazon;

class PriceHistory
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\PriceHistory';
	
	private $db;	
	private $config;


	public function __construct($registry){

		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');		

	}


	








}