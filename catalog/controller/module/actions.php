<?php  
class ControllerModuleActions extends Controller {
	private $_name = 'actions';

	protected function index($module) {
		$this->load->model('catalog/actions');
		$this->load->model('tool/image');

		$this->language->load('module/actions');
    	
		$this->data['text_read_more'] = $this->language->get('text_read_more');
		$this->data['text_read_all'] = $this->language->get('text_read_all');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		/*if ( $module['date'] != '' ) {
				$date_format = $module['date'];
		} else {
				$date_format = 'd.m.Y';
		}*/

		if ( isset($module['limit']) ) {
			$actions_limit = $module['limit'];
		} else {
			$actions_limit = 3;
		}

		/*$this->data['col_width'] = (int)(100 / $actions_limit);	 
		
		$this->data['style'] = $module['style'];
		*/
		
		$actions_setting = $this->config->get('actions_setting');
		
		$this->data['continue'] = $this->url->link('information/actions');
		
		$results = $this->model_catalog_actions->getActionsAll(0, $actions_limit);

		$this->data['actions'] = array();
			
		foreach ($results as $result) {
					
			$date_start = date( 'j', $result['date_start'] ) . ' ' .$this->model_catalog_actions->getMonthName(date( 'n', $result['date_start'] ));
			$date_end = date( 'j', $result['date_end'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $result['date_end'] ));
			
			if ($result['image'] AND $actions_setting['show_module_image'] ) {
				$image = $this->model_tool_image->resize($result['image'], $actions_setting['image_module_width'], $actions_setting['image_module_height']);
			} else {
				$image = FALSE;
			}
			
			if($actions_setting['show_module_date']) {
				$date = sprintf($this->language->get('date_actions_format'), $date_start, $date_end);
			} else {
				$date = FALSE;
			}
			if(!empty($result['anonnce'])) {
				$anonnce = utf8_substr(strip_tags(html_entity_decode($result['anonnce'], ENT_QUOTES, 'UTF-8')), 0, $actions_setting['module_maxlen']);
			} else {
				$anonnce = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $actions_setting['module_maxlen']);
			}
			$this->data['actions'][] = array(
				'caption'		=> $result['caption'],
				'date_start'	=> $date_start, //date( $date_format, $result['date_start'] ),
				'date_end'		=> $date_end, //date( $date_format, $result['date_end'] ),
				'date'			=> $date,
				'thumb'			=> $image,
				'anonnce'		=> $anonnce, 
				//html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'href'			=> $this->url->link('information/actions', 'actions_id=' . $result['actions_id'])
			);					
		}
		
		/*foreach ($this->model_catalog_actions->getActionsAll(0, $actions_limit) as $result ) {
				if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], 100, 100);
						$image_small = $this->model_tool_image->resize($result['image'], 70, 70);
				} else {
						$image = FALSE;
						$image_small = FALSE;
				}
			$this->data['actions_all'][] = array(
				'date'			=> date( $date_format, $result['date_start'] ),
				'caption'		=> $result['caption'],
				'thumb'			=> $image,
				'thumb_small'	=> $image_small,
				'description'	=> html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'href'			=> $this->url->link('information/actions', 'actions_id=' . $result['actions_id'])
			);
		}*/

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/actions-styles.css')) {
			$css = 'catalog/view/theme/'.$this->config->get('config_template') . '/stylesheet/actions-styles.css'; 
		} else {
			$css = 'catalog/view/theme/default/stylesheet/stylesheet-actions.css';
		}
		
		$this->document->addStyle($css);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/actions.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/actions.tpl';
		} else {
			$this->template = 'default/template/module/actions.tpl';
		}
		$this->render();
	}
}
?>