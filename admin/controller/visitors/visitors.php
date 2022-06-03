<?php   
class Controllervisitorsvisitors extends Controller {   
	public function index(){
                // VARS
        $template="visitors/visitors.tpl";             // .tpl location and file
        
        $this->load->model('visitors/visitors');    // model class file
        
        $this->load->language('visitors/visitors'); //language class file
        
        $this->template = ''.$template.'';
		
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_visitors'] = $this->language->get('text_visitors');
		$this->data['text_counting_since'] = $this->language->get('text_counting_since');
		$this->data['text_all_time'] = $this->language->get('text_all_time');
		$this->data['text_today'] = $this->language->get('text_today');
		$this->data['text_yesterday'] = $this->language->get('text_yesterday');
		$this->data['text_last_week'] = $this->language->get('text_last_week');
		$this->data['text_last_30_days'] = $this->language->get('text_last_30_days');
		$this->data['text_last_year'] = $this->language->get('text_last_year');
		$this->data['text_chart_pie'] = $this->language->get('text_chart_pie');

		$this->data['column_unique_visitors'] = $this->language->get('column_unique_visitors');
		$this->data['column_total_views'] = $this->language->get('column_total_views');		
		$this->data['column_rank'] = $this->language->get('column_rank');
		$this->data['column_country'] = $this->language->get('column_country');
		$this->data['column_country_code'] = $this->language->get('column_country_code');
		$this->data['column_unique_visitors'] = $this->language->get('column_unique_visitors');
		
		
		
		
        $this->children = array(
            'common/header',
            'common/footer'
        );      
		
		
        $this->response->setOutput($this->render());
    }
	

	
}
?>