<?php   
	class ControllerReportMreports  extends Controller {   
		public function index() {
			$this->language->load('common/home');
			
			if (isset($this->request->get['report'])){
				
				$report_type = $this->request->get['report'];
				$report_tpl = 'ttnscan.tpl';
				
				switch ($report_type){
					case 'ttnscan' :
					$this->document->setTitle('Отчет по заказам в обработке');
					$report_tpl = 'ttnscan.tpl';						
					break;
					
					case 'courierscan' :
					$this->document->setTitle('Отчет по заказам в ПВЗ');
					$report_tpl = 'courierscan.tpl';						
					break;
					
					case 'needtocall' :
					$this->document->setTitle('Заказы в ожидании ответа');
					$report_tpl = 'needtocall.tpl';
					break;
					
					case 'nopaid' :
					$this->document->setTitle('Заказы в ожидании оплаты');
					$report_tpl = 'nopaid.tpl';
					break;
					
					case 'minusscan' :
					$this->document->setTitle('Проверка счетов покупателей');
					$report_tpl = 'minusscan.tpl';
					break;
					
					case 'forgottencart' :
					$this->document->setTitle('Незавершенные заказы');
					$report_tpl = 'forgottencart.tpl';
					break;
					
					default :
					$report_tpl = 'ttnscan.tpl';
					break;
				}
				
				$this->data['token'] = $this->session->data['token'];
				
				
				$this->template = "report/mreports/$report_tpl";
				$this->children = array(
				'common/header',
				'common/footer'
				);
				
				$this->response->setOutput($this->render());
				
				} else {
				
				
				
			}
			
			
		}
	}	