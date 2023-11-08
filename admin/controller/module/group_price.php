<?php
class ControllerModuleGroupPrice extends Controller {

	private $error = array(); 
		
	public function index() {   
	
		$this->load->language('module/group_price');

		$this->document->setTitle($this->language->get('heading_title'));
    
    $this->load->model('catalog/group_price');		
    
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      
			$this->model_catalog_group_price->saveCategoryPrices($this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			//$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');		
		$this->data['button_cancel'] = $this->language->get('button_cancel');
    $this->data['button_save']   = $this->language->get('button_save');
    $this->data['refresh_link_title']   = $this->language->get('refresh_link_title');
    $this->data['refresh_link_confirm'] = $this->language->get('refresh_link_confirm');
    $this->data['refresh_link_success'] = $this->language->get('refresh_link_success');
    $this->data['token'] = $this->session->data['token'];
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  	$this->data['breadcrumbs'] = array();

   	$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
    		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
     		'separator' => false
   	);

   	$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
		   	'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
     		'separator' => ' :: '
   	);
		
   	$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
    		'href'      => $this->url->link('module/group_price', 'token=' . $this->session->data['token']),
     		'separator' => ' :: '
   	);
		
    $this->data['action'] = $this->url->link('module/group_price', 'token=' . $this->session->data['token']);
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

    $this->load->model('sale/customer_group');
    $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
    
    $categories      = $this->model_catalog_group_price->getAllDbCategories();
    $this->data['categories'] = array('0' => array('category_id' => 0, 'name' => $this->language->get('group_base_price')));
    $this->data['categories'] += $this->model_catalog_group_price->getAllCategoriesWithParents($categories);
    
    $this->data['categories_price']     = $this->model_catalog_group_price->getAllCategoriesPrice();
    
    $this->data['currency']     = $this->config->get('config_currency');
		
		$this->template = 'module/group_price.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

  public function install() {
    $this->load->model('catalog/group_price');		
		$this->model_catalog_group_price->install();  
	}

  public function uninstall() {
    $this->load->model('catalog/group_price');		
		$this->model_catalog_group_price->uninstall();  
  }
    
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/group_price')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}		
		return !$this->error ? TRUE : FALSE;
	}
	
  /**
   * update price in DB from ajax
   */
  public function updateDbPrice() {
    
    $json = array();
    $this->load->language('module/group_price');
    $this->load->model('catalog/group_price');
    
    if (!$this->validate()) {
      $json['error'] = $this->language->get('error_permission');
    }
    else {
      $result = $this->model_catalog_group_price->updateDbPrice($this->request->get);
      if ($result === FALSE) {
        $json['error'] = $this->language->get('refresh_link_error');
      }
      else {
        $json['success'] = $this->language->get('refresh_link_success') . $result;
      }
   }  
    $this->response->setOutput(json_encode($json));
  }
  
}
