<?php
	final class Loader {
		protected $registry;
		
		public function __construct($registry) {
			$this->registry = $registry;
		}
		
		public function __get($key) {
			return $this->registry->get($key);
		}
		
		public function __set($key, $value) {
			$this->registry->set($key, $value);
		}
		
		public function library(string $library):void {
			$file = DIR_SYSTEM . 'library' . DIRECTORY_SEPARATOR . $library . '.php';
			
			if (file_exists($file)) {
				include_once($file);
				} else {
				trigger_error('Error: Could not load library ' . $library . '!');
				exit();					
			}
		}
		
		public function helper(string $helper):void {
			$file = DIR_SYSTEM . 'helper' . DIRECTORY_SEPARATOR . $helper . '.php';
			
			if (file_exists($file)) {
				include_once($file);
				} else {
				trigger_error('Error: Could not load helper ' . $helper . '!');
				exit();					
			}
		}

        public function single_model(string $model, string $load_from = DIR_APPLICATION):void {
            $file  = $load_from . 'model' . DIRECTORY_SEPARATOR . $model . '.php';
            $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

            if (file_exists($file)) {
                include_once($file);

                $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
            } else {
                trigger_error('Error: Could not load model ' . $model . '!');
                exit();
            }
        }
		
		public function model(string|array $model, string $load_from = DIR_APPLICATION):void {
            if (is_array($model)){
                $this->models($model, $load_from);
            } else {
                $this->single_model($model, $load_from);
            }
		}

		public function models(array $models = [], string $load_from = DIR_APPLICATION):void {
			foreach ($models as $model){
				$this->model($model, $load_from);
			}
		}
		
		public function load_catalog_model(string $model):void {
			$this->model($model, DIR_CATALOG);
		}
		
		public function database($driver, $hostname, $username, $password, $database):void {
			$file  = DIR_SYSTEM . 'database' . DIRECTORY_SEPARATOR . $driver . '.php';
			$class = 'Database' . preg_replace('/[^a-zA-Z0-9]/', '', $driver);
			
			if (file_exists($file)) {
				include_once($file);
				
				$this->registry->set(str_replace('/', '_', $driver), new $class($hostname, $username, $password, $database));
				} else {
				trigger_error('Error: Could not load database ' . $driver . '!');
				exit();				
			}
		}
		
		public function controller(string $route){
			$route 		= preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);				
			$response 	= new Response($this->registry);
			$front 		= new Front($this->registry);
			
			$front->dispatch(new Action($route), '');
			
			return $response->returnOutput();
		}
		
		public function view(string $route, array $data = []): string {
			$route 		= preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);			
			$template 	= DIR_TEMPLATE . $route . '.twig';
			$isTwig 	= true;
			
			if (!file_exists($template)){
				$template = DIR_TEMPLATE . $route . '.tpl';
				$isTwig = false;
			}
						
			$code = '';
			
			if (file_exists($template)) {				
				if ($isTwig){
					$twigLoader = new \Twig\Loader\FilesystemLoader(DIR_TEMPLATE);
					$twig 		= new \Twig\Environment($twigLoader);
					$template 	= $twig->load($route . '.twig');
					
					return $template->render($data);
				} else {					
					extract($data);
					ob_start();					
					require($template);
					$output = ob_get_contents();					
					ob_end_clean();
					
					return $output;					
				}				
			} else {
				return '[' . $this->template . '] DOESNT EXIST';
				trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
				exit();				
			}
			
			return $output;
		}
		
		public function config(string $config) {
			$this->config->load($config);
		}
		
		public function language(string $language) {
			return $this->language->load($language);
		}		
	} 					