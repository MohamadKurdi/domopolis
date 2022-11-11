<?php 
class ControllerInformationlandingpage extends Controller {
	
	private function getList(){
		
		
	}
	
	
	public function index() {  
		
		$this->language->load('information/landingpage');

		$this->load->model('catalog/landingpage');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		if (isset($this->request->get['landingpage_id'])) {
			$landingpage_id = (int)$this->request->get['landingpage_id'];


			$landingpage_info = $this->model_catalog_landingpage->getlandingpage($landingpage_id);

			if ($landingpage_info) {

				$this->db->query("UPDATE landingpage SET viewed = viewed+1 WHERE landingpage_id = '" .(int)$landingpage_id. "'");

				$this->document->setKeywords($landingpage_info['meta_keyword']);
				$this->document->setDescription($landingpage_info['meta_description']);

				($landingpage_info['seo_title'] == '')?$this->document->setTitle($landingpage_info['title']):$this->document->setTitle($landingpage_info['seo_title']);

				$this->data['breadcrumbs'][] = array(
					'text'      => $landingpage_info['title'],
					'href'      => $this->url->link('information/landingpage', 'landingpage_id=' .  $landingpage_id),      		
					'separator' => $this->language->get('text_separator')
				);

				$this->data['heading_title'] = $landingpage_info['title'];

				$this->data['button_continue'] = $this->language->get('button_continue');


				$this->data['description'] = html_entity_decode($landingpage_info['description'], ENT_QUOTES, 'UTF-8');

				$this->data['continue'] = $this->url->link('common/home');

				$canonical = $this->url->link('information/landingpage', 'landingpage_id=' .  $landingpage_id);		

				$this->document->addLink('canonical', $canonical);

				$this->load->model('design/layout');
				$layout_id = $this->model_catalog_landingpage->getlandingpageLayoutId($landingpage_id);
				if (!$layout_id){				
					$layout_id = $this->model_design_layout->getLayout('information/landingpage');				
				}

				if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
					$this->template = $template;			
				}

				$template_overload = false;
				$this->load->model('setting/setting');
				$custom_template_module = $this->model_setting_setting->getSetting('custom_template_module', $this->config->get('config_store_id'));
				if(!empty($custom_template_module['custom_template_module'])){
					foreach ($custom_template_module['custom_template_module'] as $key => $module) {
						if (($module['type'] == 2) && !empty($module['landingpages'])) {
							if (in_array($landingpage_id, $module['landingpages'])) {
								$this->template = $module['template_name'];
								$template_overload = true;
							}
						}
					}
				}

				if (!$template_overload) {

					if ($landingpage_info['bottom']) {
						$this->template = 'information/landingpage';

					} else {
						$this->template = 'information/landingpage_noshop';

						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/landingpage_noshop.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/information/landingpage_noshop.tpl';
						} else {
							$this->template = 'default/template/information/landingpage_noshop.tpl';
						}

					}
				}

				if ($landingpage_info['bottom']) {

					$this->children = array(
						'common/column_left',
						'common/column_right',
						'common/content_top',
						'common/content_bottom',
						'common/footer',
						'common/header'
					);

				} else {

					$this->children = array(
						'common/footer_landingnoshop',
						'common/header_landingnoshop'
					);

				}

				$this->response->setOutput($this->render());


			} else {


				$this->document->setTitle($this->language->get('text_error'));

				$this->data['heading_title'] = $this->language->get('text_error');

				$this->data['text_error'] = $this->language->get('text_error');

				$this->data['button_continue'] = $this->language->get('button_continue');

				$this->data['continue'] = $this->url->link('common/home');


				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}

				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);

				$this->response->setOutput($this->render());
			}

		} else {
			
			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => 'Темы',
				'href'      => $this->url->link('information/landingpages'),
				'separator' => false
			);
			
			$this->document->setTitle('Темы');

			$landingpages = $this->model_catalog_landingpage->getlandingpages();
			
			$this->data['landingpages'] = array();
			
			$this->load->model('tool/image');
			
			foreach ($landingpages as $landingpage){
				
				if ($landingpage['image']){
					$_image = $this->model_tool_image->resize($landingpage['image'], 300, 300);
				} else {
					$_image = $this->model_tool_image->resize('no_image.jpg', 300, 300);
				}

				
				$this->data['landingpages'][] = array(
					'name' => $landingpage['title'],
					'href' => $this->url->link('information/landingpage', 'landingpage_id=' .  $landingpage['landingpage_id']),
					'image' => $_image					
				);				
			}

			$this->template = $this->config->get('config_template') . '/template/information/landingpages.tpl';

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());
			
		}



		
	}

}
?>