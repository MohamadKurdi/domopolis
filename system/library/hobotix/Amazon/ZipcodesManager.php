<?

namespace hobotix\Amazon;

class ZipcodesManager
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\ZipcodesManager';
	
	private $db;	
	private $config;


	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');		
	}










}