<?php

include_once 'Sceleton.php';
include_once 'Coreparser.php';


class Kahlaporzellanshop extends Coreparser implements Sceleton
{

    /**
     * Porzellantreff constructor.
     */


    public function  getData($url, $product_id = false, $secure = true)
    {

        include_once(DIR_SYSTEM . 'library/simple_html_dom.php');

        $_string_html = $this->get_page($url);
        $html = str_get_html($_string_html);
        if ($html) {
            // Название
            $name = $html->find('.product-name h1', 0)->plaintext;

            // Цена
            $price = 0;
            $special = 0;

            // Пытаемся найти зачеркнутую цену
            $oldPrice = $html->find('h1 .price-box .old-price', 0);
            if ($oldPrice) {
                $price = $oldPrice->find('.price', 0)->innertext;
                $special = $html->find('h1 .price-box .special-price .price', 0)->innertext;
            } else {
                $price = $html->find('h1 .price-box .regular-price .price', 0)->innertext;
            }

            $price = $this->fixPrice2($price);
            $special = $this->fixPrice2($special);

            // Описание
            $description = $html->find('.std', 0)->innertext;

            // Ищем EAN
            $ean = false;
            foreach ($html->find('#product-attribute-specs-table tr') as $tr) {
                $itemName = trim($tr->find('th.label', 0)->innertext);

                if ($itemName == 'EAN') {
                    $ean = $tr->find('td.data', 0)->plaintext;
                }
            }

            $html->clear();

            return array(
                'price' => $price,
                'name' => $name,
                'special_price' => $special,
                'special' => $special,
                'stock' => '?',
                'prime' => false
            );
        }

        return false;
    }


}