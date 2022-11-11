<?php
class ControllerModuleCategoryWall extends Controller {
	private $error = array();

	public function index() {

		$this->language->load('module/category_wall');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/category_wall/category_wall.css');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('category_wall', $this->request->post);

			if ($this->request->post['apply']) {
				$this->redirect($this->url->link('module/category_wall', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['default_store'] = $this->config->get('config_name');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');

		$this->data['text_all'] = $this->language->get('text_all');
		$this->data['text_featured'] = $this->language->get('text_featured');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_accordion'] = $this->language->get('text_accordion');
		$this->data['text_flyout'] = $this->language->get('text_flyout');
		$this->data['text_fixed'] = $this->language->get('text_fixed');
		$this->data['text_grid'] = $this->language->get('text_grid');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_limit'] = $this->language->get('text_limit');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_count'] = $this->language->get('entry_count');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_items'] = $this->language->get('entry_items');
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		$this->data['entry_child'] = $this->language->get('entry_child');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_image_size'] = $this->language->get('entry_image_size');
		$this->data['entry_sub_image'] = $this->language->get('entry_sub_image');
		$this->data['entry_design'] = $this->language->get('entry_design');
		$this->data['entry_view'] = $this->language->get('entry_view');
		$this->data['entry_child_limit'] = $this->language->get('entry_child_limit');
		$this->data['entry_child2_limit'] = $this->language->get('entry_child2_limit');
		$this->data['entry_column'] = $this->language->get('entry_column');
		$this->data['entry_item_title'] = $this->language->get('entry_item_title');
		$this->data['entry_link'] = $this->language->get('entry_link');

		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_box_class'] = $this->language->get('entry_box_class');
		$this->data['entry_heading_class'] = $this->language->get('entry_heading_class');
		$this->data['entry_content_class'] = $this->language->get('entry_content_class');

		$this->data['tab_module'] = $this->language->get('tab_module');
		$this->data['tab_module_setting'] = $this->language->get('tab_module_setting');
		$this->data['tab_showcase'] = $this->language->get('tab_showcase');
		$this->data['tab_other'] = $this->language->get('tab_other');
		$this->data['tab_categories'] = $this->language->get('tab_categories');
		$this->data['tab_manufacturers'] = $this->language->get('tab_manufacturers');
		$this->data['tab_custom_items'] = $this->language->get('tab_custom_items');

		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_insert'] = $this->language->get('button_insert');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/category_wall', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/category_wall', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->data['modules'] = array();

		if (isset($this->request->post['category_wall_module'])) {
			$this->data['modules'] = $this->request->post['category_wall_module'];
		} elseif ($this->config->get('category_wall_module')) { 
			$this->data['modules'] = $this->config->get('category_wall_module');
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('catalog/category_wall');
		$this->data['categories'] = $this->model_catalog_category_wall->getWallCategories(0);

		$this->load->model('catalog/manufacturer');
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->template = 'module/category_wall.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/category_wall')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>