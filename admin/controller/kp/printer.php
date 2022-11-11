<?
	
	class ControllerKPPrinter extends Controller {
		private $error = array();
		
		private function mb_ucfirst($word)
		{
			return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, mb_strlen($word), 'UTF-8');
		}
		
		private function bool_real_stripos($haystack, $needle){
			
			return !(stripos($haystack, $needle) === false);			
		}
		
		private  function ucfirst_utf8($stri){
			if($stri{0}>="\xc3")
			return (($stri{1}>="\xa0")?
			($stri{0}.chr(ord($stri{1})-32)):
			($stri{0}.$stri{1})).substr($stri,2);
			else return ucfirst($stri);
		}
		
		private function num2str($num, $currency_morph = array('рубль','рубля','рублей',0)) {
			$nul='ноль';
			$ten=array(
			array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
			array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
			);
			$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
			$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
			$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
			$unit=array();
			$unit[] = array('копейка' ,'копейки' ,'копеек',	 1);
			$unit[] = $currency_morph;		
			$unit[] = array('тысяча'  ,'тысячи'  ,'тысяч'     ,1);
			$unit[] = array('миллион' ,'миллиона','миллионов' ,0);
			$unit[] = array('миллиард','милиарда','миллиардов',0);
			//
			list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
			$out = array();
			if (intval($rub)>0) {
				foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
					if (!intval($v)) continue;
					$uk = sizeof($unit)-$uk-1; // unit key
					$gender = $unit[$uk][3];
					list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
					// mega-logic
					$out[] = $hundred[$i1]; # 1xx-9xx
					if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
					else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
					// units without rub & kop
					if ($uk>1) $out[]= $this->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
				} //foreach
			}
			else $out[] = $nul;
			$out[] = $this->morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
			$_kop = ', ' . $kop.' '. $this->morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
			return $this->mb_ucfirst(trim(preg_replace('/ {2,}/', ' ', join(' ', $out) . $_kop)));
		}
		
		private function num2strUA($num, $currency_morph = array('гривня','гривні','гривень',0)) {
			$nul='нуль';
			$ten=array(
			array('','один','два','три','чотири','п`ять','шість','сім', 'вісім','дев`ять'),
			array('','одна','дві','три','четыре','п`ять','шість','сім', 'вісім','дев`ять'),
			);
			$a20=array('десять','одиннадцять','дванадцять','тринадцять','чотирнадцять' ,'п`ятнадцять','шістнадцять','сімнадцять','вісімнадцять','дев`ятнадцять');
			$tens=array(2=>'двадцять','тридцять','сорок','п`ятдесят','шістдесят','сімьдесят' ,'вісімдесят','дев`яносто');
			$hundred=array('','сто','двісті','триста','чотириста','п`ятсот','шістсот', 'сімсот','вісімсот','дев`ятсот');
			$unit=array();
			$unit[] = array('копійка' ,'копійки' ,'копійок',	 1);
			$unit[] = $currency_morph;		
			$unit[] = array('тисяча'  ,'тисячі'  ,'тисяч'     ,1);
			$unit[] = array('мільйон' ,'мильйона','мильйонів' ,0);
			$unit[] = array('мільярд','мильярда','мильярдів',0);
			//
			list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
			$out = array();
			if (intval($rub)>0) {
				foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
					if (!intval($v)) continue;
					$uk = sizeof($unit)-$uk-1; // unit key
					$gender = $unit[$uk][3];
					list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
					// mega-logic
					$out[] = $hundred[$i1]; # 1xx-9xx
					if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
					else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
					// units without rub & kop
					if ($uk>1) $out[]= $this->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
				} //foreach
			}
			else $out[] = $nul;
			$out[] = $this->morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
			$_kop = ', ' . $kop.' '. $this->morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
			return $this->mb_ucfirst(trim(preg_replace('/ {2,}/', ' ', join(' ', $out) . $_kop)));
		}
		
		/**
			* Склоняем словоформу
			* @ author runcore
		*/
		private function morph($n, $f1, $f2, $f5) {
			$n = abs(intval($n)) % 100;
			if ($n>10 && $n<20) return $f5;
			$n = $n % 10;
			if ($n>1 && $n<5) return $f2;
			if ($n==1) return $f1;
			return $f5;
		}
		
		
		public function createDocument(){
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				die('no_permission');
			}
			
			$order_id = (int)$this->request->get['order_id'];
			$customer_id = (int)$this->request->get['customer_id'];
			$doctype = $this->request->get['doctype'];
			$action = $this->request->get['action'];
			$this->data['noprint'] = (isset($this->request->get['noprint']) && $this->request->get['noprint'] == 'true');
			
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('localisation/currency');
			$this->load->model('localisation/legalperson');
			
			$this->data['order'] = $this->model_sale_order->getOrder($order_id);
			$this->data['order_products'] = $this->model_sale_order->getOrderProducts($order_id);
			$this->data['order_totals'] = $this->model_sale_order->getOrderTotals($order_id);
			$this->data['customer'] = $this->model_sale_customer->getCustomer($customer_id);
			$this->data['legalperson'] = $this->model_localisation_legalperson->getLegalPersonParsed($this->data['order']['legalperson_id']);						
			
			$currency = $this->model_localisation_currency->getCurrency($this->data['order']['currency_id']);
			$currency_morph = explode(';', $currency['morph']);
			$currency_morph[] = 0;
			$invoice_no = $this->data['invoice_no'] = $this->model_sale_order->createInvoiceNo($order_id, false);
			
			$this->data['total'] = 0;
			$this->data['shipping'] = 0;
			$this->data['shipping_total'] = false;
			foreach ($this->data['order_totals'] as $_ot){
				if ($_ot['code'] == 'total'){
					$this->data['total'] = $_ot['value_national'];
				}
				if ($_ot['code'] == 'sub_total'){
					$this->data['sub_total'] = $_ot['value_national'];
				}
				if ($_ot['code'] == 'shipping' && $_ot['value_national'] > 0){
					$this->data['shipping'] = $_ot['value_national'];
					$this->data['shipping_total'] = $_ot;
				}
			}
			
			
			if ($this->data['shipping']){
				$percent = (float)($this->data['shipping'] / ($this->data['total'] - $this->data['shipping']));
				
				$temp_total = 0;
				$last_not_null_idx = 0;
				foreach ($this->data['order_products'] as $key => &$_op){
					
					if ($_op['pricewd_national'] > 0){
						$last_not_null_idx = $key;
					}
					
					$_op['pricewd_national'] += ($_op['pricewd_national'] * $percent);
					$_op['totalwd_national'] = (float)number_format($_op['pricewd_national'], 2, '.', '') * $_op['quantity'];
					$temp_total += $_op['totalwd_national'];
				}
				
				unset($_op);
				$temp_total2 = 0;
				$diff = abs((float)$temp_total - (float)$this->data['total']);
				if ($diff > 1){
					if ((float)$temp_total - (float)$this->data['total'] > 0){
						
						$this->data['order_products'][$last_not_null_idx]['pricewd_national'] -= $diff / $this->data['order_products'][$last_not_null_idx]['quantity'];
						//	$this->data['order_products'][$last_not_null_idx]['pricewd_national'] = (float)number_format($this->data['order_products'][$last_not_null_idx]['pricewd_national'], 2, '.', '');
						$this->data['order_products'][$last_not_null_idx]['totalwd_national'] = $this->data['order_products'][$last_not_null_idx]['pricewd_national'] * $this->data['order_products'][$last_not_null_idx]['quantity'];
						$temp_total2 += $this->data['order_products'][$last_not_null_idx]['totalwd_national'];
						
						} elseif ((float)$temp_total - (float)$this->data['total'] < 0){
						
						$this->data['order_products'][$last_not_null_idx]['pricewd_national'] += $diff / $this->data['order_products'][$last_not_null_idx]['quantity'];	
						//	$this->data['order_products'][$last_not_null_idx]['pricewd_national'] = (float)number_format($this->data['order_products'][$last_not_null_idx]['pricewd_national'], 2, '.', '');
						$this->data['order_products'][$last_not_null_idx]['totalwd_national'] = $this->data['order_products'][$last_not_null_idx]['pricewd_national'] * $this->data['order_products'][$last_not_null_idx]['quantity'];
						$temp_total2 += $this->data['order_products'][$last_not_null_idx]['totalwd_national'];
						
					}
				}
				
				if ($diff > 1){
					if (abs((float)$temp_total2 - (float)$this->data['total']) < 1){
						$this->data['total'] = $temp_total2;
					}
					} elseif ($diff > 0 && $diff < 1){
					if (abs((float)$temp_total - (float)$this->data['total']) < 1){
						$this->data['total'] = $temp_total;
					}
				}
			}
			
			$this->data['morped_currency'] = $this->morph($this->data['total'], $currency_morph[0], $currency_morph[1], $currency_morph[2]);
			
			if ($this->data['order']['shipping_country_id'] == 220) {
				$this->data['total_txt'] = $this->num2strUA($this->data['total'], $currency_morph);
				} else {
				$this->data['total_txt'] = $this->num2str($this->data['total'], $currency_morph);
			}
			
			$this->data['total_quantity'] = 0;
			unset($_op);
			reset($this->data['order_products']);
			foreach ($this->data['order_products'] as $_op){
				$this->data['total_quantity'] += $_op['quantity'];
			}
			
			if ($this->data['order']['shipping_country_id'] == 220) {
				$this->data['total_quantity_txt'] = $this->num2strUA($this->data['total'], $currency_morph);
				} else {
				$this->data['total_quantity_txt'] = $this->num2str($this->data['total_quantity'], $currency_morph);
			}
			
			$this->data['stamp'] = (isset($this->data['legalperson']['legalperson_print']) && $this->data['legalperson']['legalperson_print'])?$this->data['legalperson']['legalperson_print']:'';
			
			if ($doctype == 'cashless'){
				
				if ($this->data['order']['shipping_country_id'] == 176) {				
					$this->template = 'sale/documents/cashless.ru.tpl';
					
					} elseif ($this->data['order']['shipping_country_id'] == 220) {					
					$this->template = 'sale/documents/cashless.ua.tpl';
					} else {
					$this->template = 'sale/documents/nocountry.tpl';
				}
				
				} elseif ($doctype == 'torg12') {
				if ($this->data['order']['shipping_country_id'] == 176) {
					$this->template = 'sale/documents/torg12.ru.tpl';
				}
				
			}				
			
			$html = $this->render();		
			
			
			if ($action == 'print') {
				
				$html = str_replace(' !important', '', $html);
				$html = html_entity_decode($html);
				
				$top = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head></head><body style="font-family:Calibri, Verdana, Arial">';
				$bottom = "</body></html>";
				
				$html = $top . $html . $bottom;
				
				$this->response->setOutput($html);
				
				} elseif ( $action == 'preview' ) {
				
				$html = str_replace(' !important', '', $html);
				$html = html_entity_decode($html);
				
				$this->response->setOutput(json_encode(array('html' => $html)));
				
				} elseif($action == 'pdf') {
				
				$mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4'
				]);		
				
				$top = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head></head><body style="font-family:Calibri, Verdana, Arial">';
				$bottom = "</body></html>";
				$html = $top . $html . $bottom;
				$html = str_replace(' !important', '', $html);
				
				$mpdf->WriteHTML($html);
				
				$date = ($this->data['order']['invoice_date'] == '0000-00-00')?date('d.m.Y'):date('d.m.Y', strtotime($this->data['order']['invoice_date']));
				$filename = prepareFileName("Счет на оплату №" . $invoice_no . "-" . $order_id . " от " . $date . ".pdf");
				
				$mpdf->Output($filename, 'D');
				
				} else {
				
				$this->responce->setOutput('no_action');
				
			}
			
			
			
			
			
			
		}
		
		
	}					