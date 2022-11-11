<?

class ControllerApiTelegramParser extends Controller { 
	
	public function action($data){	
		if (!isset($data['text'])){
			$data['text'] = '';
		}	
		echo $this->parseMessage($data);	
	}
	
	public function normalizeWord($word){
		return trim(mb_strtolower($word));
	}
	
	public function parseMessage($data){
		
		$text = $data['text'];
		
		try {
		
			$words = explode(' ', $text);
		
			switch ($this->normalizeWord($words[0])){
			
				case 'заказ': 
					$this->load->model('sale/order');
					switch (trim(mb_strtolower($words[2]))){
						case 'статус' : return 	$this->model_sale_order->getOrderLastStatusName(trim(mb_strtolower($words[1]))); break;
					
						default : return "Возможно, вы хотите узнать статус? Тогда <заказ 11645 статус>"; break;
					}
					break;
			
				case '111': return "Азаза"; break;
			
				case 'сколько': 
					$this->load->model('sale/order');
					switch ($this->normalizeWord($words[1])){
						case 'заказов' : 
							switch ($this->normalizeWord($words[2])){
							
								case 'сегодня' : return 'Сегодня оформлено ' . $this->model_sale_order->countOrdersTotalOnDate(date('Y-m-d')) . ' заказов!'; break;
								case 'вчера' : return 'Вчера оформлено ' . $this->model_sale_order->countOrdersTotalOnDate(date('Y-m-d', time() - 60 * 60 * 24)) . ' заказов!'; break;
							
								default : return ''; break;
							}
					
						default : return "Я не понимаю команды " . $text; break;
					}
			
				default: return "Я не понимаю команды " . $text; break;
			}
		
		} catch(Exception $e) {
			
			return $e->getMessage();
			
		}
		
		
	}
	
}