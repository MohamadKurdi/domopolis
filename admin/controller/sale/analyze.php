<?php
	class ControllerSaleAnalyze extends Controller {
		private $error = array();
		
		private function mb_ucfirst($word)
		{
			return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, mb_strlen($word), 'UTF-8');
		}
		
		private function bool_real_stripos($haystack, $needle){
			
			return !(stripos($haystack, $needle) === false);
			
		}
		
		
		public function analyzeBuyPriority(){
			$order_id = (int)$this->request->get['order_id'];
			
			$this->load->model('sale/analyze');
			
			$result = $this->model_sale_analyze->analyzeBuyPriority($order_id);
			
			$total_result = '';
			foreach ($result as $string){
				
				$total_result .= $string['text'] . '<br />';
				
			}
			
			echo $total_result;
			
		}
		
		
		
	}	