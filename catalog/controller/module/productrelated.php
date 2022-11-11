<?

class ControllerModuleProductRelated extends Controller {
	protected function index($setting) {
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
		
		$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		
		$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
		
		$this->template = 'module/inproduct_related';
		
		$this->render();
	}
}			