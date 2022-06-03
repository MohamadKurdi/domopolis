<?php

include_once 'Sceleton.php';
include_once 'Coreparser.php';


    class Rosenthal extends Coreparser implements Sceleton
    {

        /**
         * Porzellantreff constructor.
         */


        public function  getData($url,$product_id = false, $secure = true)
        {

            include_once(DIR_SYSTEM . 'library/simple_html_dom.php');

            $_string_html = $this->get_page($url);
            $html = str_get_html($_string_html);
            if ($html) {
                $special = false;
				
                // Цена. Сначала пытаемся получить старую цену (Зачеркнутую / OldPrice)
                if ($html->find('.large-12 ul.no-bullet .old-price', 0)) {


                    if (strpos($url, '/en/')) {
                        $price = $this->fixPrice3($html->find('.large-12 ul.no-bullet .old-price', 0)->plaintext);
                        $special = $this->fixPrice3($html->find('.large-12 ul.no-bullet .sale-price-large',
                            0)->plaintext);
                    } else {
                        $price = $this->fixPrice4($html->find('.large-12 ul.no-bullet .old-price', 0)->plaintext);
                        $special = $this->fixPrice4($html->find('.large-12 ul.no-bullet .sale-price-large',
                            0)->plaintext);
                    }


                } else {
                    // $p = $html->find('.large-12 ul.no-bullet .sale-price-large');
                    $p = $html->find('.medium-6 ul.no-bullet .sale-price-large');
                    if (is_object($p) || $p) {

                        if (strpos($url, '/en/')) {
                            $price = $this->fixPrice3($html->find('.medium-6 ul.no-bullet .sale-price-large',
                                0)->plaintext);
                        } else {
                            $price = $this->fixPrice4($html->find('.medium-6 ul.no-bullet .sale-price-large',
                                0)->plaintext);
                        }

                    }
                }
                if (!isset($price) || !$price) {
                    return false;
                }

                // Название
                $name = trim($html->find('h1', 0)->plaintext);


                // Наличие
                $avail = true;
                if ($html->find('.medium-6 .price .availability a.unavailable', 0)) {
                    $avail = false;
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


        }



    }