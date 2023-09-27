<?php
class Request {
	public $get 	= [];
	public $post 	= [];
	public $cookie 	= [];
	public $files 	= [];
	public $server 	= [];
	public $data 	= [];
	public $method  = 'GET';

	private $numericParams = [
		'page',
		'limit',
		'product_id',
		'category_id',
		'manufacturer_id',
		'collection_id',
		'user_id'
	];

	public function __construct() {
		$_GET 		= $this->clean($_GET);
		$_POST 		= $this->clean($_POST);
		$_REQUEST 	= $this->clean($_REQUEST);
		$_COOKIE 	= $this->clean($_COOKIE);
		$_FILES 	= $this->clean($_FILES);
		$_SERVER 	= $this->clean($_SERVER);

		$this->get 		= $_GET;
		$this->post 	= $_POST;
		$this->request 	= $_REQUEST;
		$this->cookie 	= $_COOKIE;
		$this->files 	= $_FILES;
		$this->server 	= $_SERVER;
		if (!empty($_SERVER['REQUEST_METHOD'])){
			$this->method 	= $_SERVER['REQUEST_METHOD'];
		}
		$this->data     = array_merge($_GET, $_POST);
	}

	public function isPUT(){
		return ($this->method == 'PUT');
	}

	public function isGET(){
		return ($this->method == 'GET');
	}

	public function isPOST(){
		return ($this->method == 'POST');
	}

	public function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				if (is_array($value)){
					$data[$this->clean($key)] = $this->clean($value);
				} else {
					$data[$this->clean($key)] = $this->cleanParam($value, $key);
				}
			}
		} else { 			
			$data = $this->cleanParam($data, false);
		}

		return $data;
	}

	public function cleanParam($value, $key){
		if ($key && in_array($key, $this->numericParams)){
			return (int)preg_replace('/\D/', '', $value);
		}

		return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
	}
}