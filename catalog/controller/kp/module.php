<?php
	
	
	class ControllerKPModule extends Controller {
		
		public function index(){
			
			if (empty($this->request->get['modpath'])){
				$this->response->setOutput('');
				} else {
				$this->data = [];
				if (!empty($this->request->get['group'])){
					$exploded = explode(';', $this->request->get['modpath']);
					foreach ($exploded as $line){
						if (trim($line)){
							
							try{
								
								$this->data[] = [
								'path' 	=> $line,
								'html'	=> $this->getChild($line)
								];
								
								} catch (Exception $e){
							}
							
						}
					}		
					
					$this->response->setOutput(json_encode($this->data));
					
					} else {
					
					
					
					try{				
						$this->data['data'] = $this->getChild($this->request->get['modpath']);
						} catch (Exception $e){
						$this->data['data'] = '';
					}
					
					$this->template = $this->config->get('config_template') . '/template/structured/module.tpl';
					$this->response->setOutput($this->render());
					
				}
				
			}
		}
		
		
	}
