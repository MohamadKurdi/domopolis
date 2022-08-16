<?php

	abstract class Controller {
		protected $registry;	
		protected $id;
		protected $layout;
		protected $template;
		protected $overrided_template;
		protected $children = array();
		protected $data = array();
		protected $output;
		protected $minifier;
		
		public function __construct($registry) {
			$GLOBALS['controller_name'] = get_class ($this);
			$this->registry = $registry;
			
			if( ! empty( $this->request->get['mfp'] ) ) {
				preg_match( '/path\[([^]]*)\]/', $this->request->get['mfp'], $mf_matches );
				
				if( ! empty( $mf_matches[1] ) ) {
					$this->request->get['path'] = $mf_matches[1];
					
					if( isset( $this->request->get['category_id'] ) || ( isset( $this->request->get['route'] ) && in_array( $this->request->get['route'], array( 'product/search', 'product/special', 'product/manufacturer/info' ) ) ) ) {
						$mf_matches = explode( '_', $mf_matches[1] );
						$this->request->get['category_id'] = end( $mf_matches );
					}
				}
				
				unset( $mf_matches );
			}
		}
		
		public function __get($key) {
			return $this->registry->get($key);
		}
		
		public function __set($key, $value) {
			$this->registry->set($key, $value);
		}
		
		protected function forward($route, $args = array()) {
			return new Action($route, $args);
		}
		
		protected function redirect($url, $status = 302) {
			header('Status: ' . $status);
			header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
			exit();				
		}
		
		protected function getChildData($child, $args = array()) {
			$action = new Action($child, $args);
			
			if (file_exists($action->getFile())) {
				require_once($action->getFile());
				
				$class = $action->getClass();
				
				$controller = new $class($this->registry);
				
				return $controller->{$action->getMethod()}($action->getArgs());

				} else {
				trigger_error('Error: Could not load controller ' . $child . '!');
				exit();					
			}		
		}
		
		protected function getChild($child, $args = array()) {
			$action = new Action($child, $args);
			
			if (file_exists($action->getFile())) {
				require_once($action->getFile());
				
				$class = $action->getClass();
				
				$controller = new $class($this->registry);
				
				$controller->{$action->getMethod()}($action->getArgs());
				
				return $controller->output;
				} else {
				trigger_error('Error: Could not load controller ' . $child . '!');
				exit();					
			}		
		}
		
		protected function hasAction($child, $args = array()) {
			$action = new Action($child, $args);
			
			if (file_exists($action->getFile())) {
				require_once($action->getFile());
				
				$class = $action->getClass();
				
				$controller = new $class($this->registry);
				
				if(method_exists($controller, $action->getMethod())){
					return true;
					}else{
					return false;
				}
				} else {
				return false;				
			}		
		}
		
		protected function minify($content){
			
			$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s'       // shorten multiple whitespace sequences
			);
			
			$replace = array(
			'>',
			'<',
			'\\1'
			);
			
			return preg_replace($search, $replace, $content);
		}
		
		protected function setBlockCachedOutput($out){
			
			if (IS_HTTPS){
				$out = str_ireplace('http://', 'https://', $out);
			}
			
			$this->output = $out;
			
		}

		protected function setDataLang($field_name)
		{
			$this->data[$field_name] = $this->language->get($field_name);
		}
		

		protected function setData($field_name, $default_value)
		{
			$this->data[$field_name] = $this->getData($field_name, $default_value);
		}
		

		protected function getData($field_name, $default_value)
		{
			if (isset($this->request->post[$field_name]))
			{
				return $this->request->post[$field_name];
			}
			elseif ($this->config->has($field_name))
			{ 
				return $this->config->get($field_name);
			}
			
			return $default_value;
		}			
		

		protected function getDataImage($field_name, $width, $height)
		{
			$this->load->model('tool/image');
			
			if (isset($this->request->post[$field_name]) && file_exists(DIR_IMAGE . $this->request->post[$field_name]))
			{
				return $this->model_tool_image->resize($this->request->post[$field_name], $width, $height);
			}
			elseif ($this->config->has($field_name))
			{ 
				return $this->model_tool_image->resize($this->config->get($field_name), $width, $height);
			}
			else
			{
				return $this->model_tool_image->resize('no_image.jpg', $width, $height);
			}
		}
		
		protected function render() {

			foreach ($this->children as $child) {
				$this->data[basename($child)] = $this->getChild($child);
			}						
			
			if (isset($_GET['print_file_path']) && $_GET['print_file_path'] == 'true') {
				print DIR_TEMPLATE . $this->template;
				print "<br />";
			}
			
			if (file_exists(DIR_TEMPLATE . $this->template)) {
				extract($this->data);
				
				ob_start();
				
				require(DIR_TEMPLATE . $this->template);
				
				$this->output = ob_get_contents();
				
				ob_end_clean();
				
				if (defined('THIS_IS_CATALOG') && is_object($this->shortcodes)) {
					$this->output = $this->shortcodes->do_shortcode($this->output);
				}
				
				if (IS_HTTPS && defined('THIS_IS_CATALOG')){
					$this->output = str_ireplace('http://', 'https://', $this->output);
				}
				
				
				return $this->output;
				} else {
				return '[' . $this->template . '] DOESNT EXIST';
				trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
				exit();				
			}
		}
	}
	
	
	class notAbstractController extends Controller{
	
	}