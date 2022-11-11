<?
class ControllerCommonFooterInfoButtons extends Controller {
	public function index() {
				
		
		$this->load->model('catalog/information');
		$this->load->model('tool/image');
		
		if ($this->customer->isOpt() || 
			(isset($this->request->get['route']) && ($this->request->get['route'] == 'account/simpleregisterb2b' || $this->request->get['route'] == 'account/loginb2b')) || 
			(isset($this->request->get['route']) && $this->request->get['route'] == 'information/information' && isset($this->request->get['information_id']) && $this->request->get['information_id'] == 20)
		){
			
			$data = array(
				'igroup' => 'b2b_info'
			);
			
		} else {
			
			$data = array(
				'igroup' => 'general_info'
			);
			
		}
			
		$results = $this->model_catalog_information->getInformations($data);

		$this->data['informations'] = array();
			
		foreach ($results as $result){
			
			$this->data['dimensions'] = array(
					'w' => 50,
					'h' => 50
				);
				
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
				} else {
					$image = false;
				}			

			if (isset($this->request->get['route']) && $this->request->get['route'] == 'information/information' && isset($this->request->get['information_id'])){
				$current_info_id = $this->request->get['information_id'];
			} else {
				$current_info_id = -1;
			}
					
			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'active' => ($result['information_id']==$current_info_id),
				'title' => $result['title'],
				'image' => $image,
				'href' => $this->url->link('information/information', 'information_id='.(int)$result['information_id']),
			);
		}
			
		$this->template = $this->template = $this->config->get('config_template') . '/template/common/footer_info_includes/general.tpl';
		
		$this->response->setOutput($this->render());
	}
}