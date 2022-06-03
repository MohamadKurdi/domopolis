<?php
	
	include_once 'Sceleton.php';
	include_once 'Coreparser.php';
	
    /**
		* Created by PhpStorm.
		* User: horror
		* Date: 08.06.2018
		* Time: 2:03
	*/
    class Shopmaxwellandwilliams extends Coreparser implements Sceleton
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
			
		/*	
            if ($this->db) {
			
                $query = $this->db->query("SELECT manufacturer_id FROM product WHERE product_id = '" . (int)$product_id . "'");
				
					
				var_dump("SELECT manufacturer_id FROM product WHERE product_id = '" . (int)$product_id . "'");
			
				
                if ($query->num_rows && isset($query->row['manufacturer_id'])) {
                    if ($query->row['manufacturer_id'] == 201) {
                        return false;
					}
				}
			}
		*/	
			
            $_string_html = $this->get_page($url);
            $html = str_get_html($_string_html);
            if ($html) {
				
                $special = false;
				
                // Цена. Сначала пытаемся получить старую цену (Зачеркнутую / OldPrice)
				//Есть зачеркнутая
				
				if ($html->find('.product-view .price-box .regular-price span.price-msrp')){			
				
					$price = $this->fixPrice4($html->find('.product-view .price-box .regular-price span.price-msrp', 0)->plaintext);
					$special = $this->fixPrice4($html->find('.product-view .price-box .regular-price span.price', 0)->plaintext);
				} else {
					$price = $this->fixPrice4($html->find('.product-view .price-box .regular-price span.price', 0)->plaintext);
				}
				
				if ($special >= $price) {
                    $special = false;
				}
				
                if (!isset($price) || !$price) {
                    return false;
				}
				
                // Название
                $name = trim($html->find('h1[itemprop=name]', 0)->plaintext);
				
                // Наличие
                $avail = false;
				if ($html->find('link[itemprop=availability]') && $html->find('link[itemprop=availability]', 0)->href == 'http://schema.org/InStock'){
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