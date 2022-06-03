<?
	
	class ControllerModuleProductRelated extends Controller {
		protected function index($setting) {
			
			$store_id = $this->config->get('config_store_id');
			$language_id = $this->config->get('config_language_id');
			$currency_id = $this->currency->getId();
			$product_id = $this->request->get['product_id'];
			
			$this->bcache->SetFile('related.'.$product_id.'.tpl', 'products_related'.$store_id.$language_id.$currency_id);
			
			if ($this->bcache->CheckFile()) {		
				
				$out = $this->bcache->ReturnFileContent();
				$this->setBlockCachedOutput($out);
				
				} else {
				
				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				
				$this->data['products'] = array();
				
				$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
				
				$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/inproduct_related.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/inproduct_related.tpl';
					} else {
					$this->template = 'default/template/module/inproduct_related.tpl';
				}
				
				$out = $this->render();
				$this->bcache->WriteFile($out);
			}
		}
	}			