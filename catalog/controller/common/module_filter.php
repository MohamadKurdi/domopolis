<?php  
	class ControllerCommonModuleFilter extends Controller {
		protected function index($args) {
			
			$this->load->model('design/layout');
			
			$module_data = array();
			
			$this->load->model('setting/extension');
			
			$layout_id = $this->model_design_layout->getLayout('common/module_filter');
			
			$extensions = $this->model_setting_extension->getExtensions('module');		
			
			foreach ($extensions as $extension) {
				$modules = $this->config->get($extension['code'] . '_module');
				
				if ($modules) {
					foreach ($modules as $module) {
						
						$addmodule = false;
						$addmodule = ($addmodule || ($module['layout_id'] == $layout_id && $module['status']));
						if (isset($args['filter_sort_order']) && $args['filter_sort_order']){						
							$filter_sort_order = explode(',', $args['filter_sort_order']);						
							$addmodule = $addmodule && (in_array($module['sort_order'], $filter_sort_order));
						}
						
						
						if (isset($args['filter_group']) && $args['filter_group']){					
							$filter_group = explode(',', $args['filter_group']);						
							$addmodule = $addmodule && (isset($module['module_group']) && in_array($module['module_group'], $filter_group));
						}
						
						if ($addmodule) {														
							
							$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
							);				
						}
					}
				}
			}
			
			$sort_order = array(); 
			
			foreach ($module_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			
			array_multisort($sort_order, SORT_ASC, $module_data);
			
			$this->data['modules'] = array();
			
			foreach ($module_data as $module) {
				$module = $this->getChild('module/' . $module['code'], $module['setting']);
				
				if ($module) {
					$this->data['modules'][] = $module;
				}
			}
			
			$this->template = $this->config->get('config_template') . '/template/common/module_filter.tpl';
			
			$this->render();
			
			
		}
	}
?>