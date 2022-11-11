<?php
	
	include_once 'Sceleton.php';
	include_once 'Coreparser.php';
	
    /**
		* Created by PhpStorm.
		* User: horror
		* Date: 08.06.2018
		* Time: 2:03
	*/
    class Seltmannshop extends Coreparser implements Sceleton
    {
		
        /**
			* Porzellantreff constructor.
		*/
		
		public function __construct($registry = false){
		
			parent::__construct($registry);
									
		}
		
		
        public function  getData($url, $product_id = false, $secure = true)
        {
            include_once(DIR_SYSTEM . 'library/simple_html_dom.php');
				
			
            $_string_html = $this->get_page($url);
            $html = str_get_html($_string_html);
            if ($html) {
				
                $special = false;
				
                // Цена. Сначала пытаемся получить старую цену (Зачеркнутую / OldPrice)
				//Есть зачеркнутая
				
				if ($html->find('.product-essential .productright .price-box p.special-price span.price')){			
				
					$price = $this->fixPrice4($html->find('.product-essential .productright .price-box p.old-price span.price', 0)->plaintext);
					$special = $this->fixPrice4($html->find('.product-essential .productright .price-box p.special-price span.price', 0)->plaintext);
				} else {
					$price = $this->fixPrice4($html->find('.product-essential .productright .price-box span.regular-price span.price', 0)->plaintext);
				}
				
				if ($special >= $price) {
                    $special = false;
				}
				
                if (!isset($price) || !$price) {
                    return false;
				}
				
                // Название
                $name = trim($html->find('.product-essential .productright .product-name h1', 0)->plaintext);
				
                // Наличие
                $avail = false;
				if (strpos($_string_html, '"availability": "InStock",')){
					 $avail = true;
				}
                
				
                return array(
				'price'         => $price,
				'name'          => $name,
				'special_price' => $special,
				'special'       => $special,
				'stock'         => $avail,
				'prime'         => false
                );
				
                $html->clear();
			}
			
            return false;
		}
		
		
		
	}	