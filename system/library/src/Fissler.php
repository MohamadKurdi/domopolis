<?php

include_once 'Sceleton.php';
include_once 'Coreparser.php';


class Fissler extends Coreparser implements Sceleton
{

    /**
     * Porzellantreff constructor.
     */


    public function  getData($url, $product_id = false, $secure = true)
    {

        include_once(DIR_SYSTEM . 'library/simple_html_dom.php');

        $_string_html = $this->get_page($url, true);
        $html = str_get_html($_string_html);
        if ($html) {
            // Название
            $name = $html->find('.prod-headline h1', 0)->plaintext;

            // Цена
            $price = 0;
            $special = 0;

            // Пытаемся найти зачеркнутую цену
            $oldPrice = $html->find('.product-info .price-wrapper .regular-price', 0);
            if ($oldPrice) {
                $price = $oldPrice->innertext;
                $special = $html->find('.product-info .price-wrapper  .special-price', 0)->innertext;
            } else {
                $price = $html->find('.product-info .price-wrapper  .price', 0)->innertext;
            }

            $price = $this->fixPrice4($price);
            $special = $this->fixPrice4($special);

            // Описание
            $description = $html->find('.product-description', 0)->innertext;

            // Ищем EAN
            $ean = false;
            if ($ean_e = $html->find('p.ean', 0)){
				$ean = $ean_e->innertext;
			}
			
			$stock = false;
			if ($html->find('.product-info .delivery-date .delivery-indicator-green', 0)){
				$stock = true;
			}

            $html->clear();

            return array(
                'price' => $price,
                'name' => $name,
                'special_price' => $special,
                'special' => $special,
                'stock' => $stock,
                'prime' => false
            );
        }

        return false;
    }


}