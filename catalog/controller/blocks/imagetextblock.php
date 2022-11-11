<?
class ControllerBlocksImageTextBlock extends Controller {
	protected function index($args) {
		
		$this->data = $args;		
		
		$this->template = $this->config->get('config_template') . '/template/blocks/image_with_text.tpl';
		
		$this->render();				
	}
}
?>