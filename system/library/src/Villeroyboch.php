<?php

include_once 'Sceleton.php';
include_once 'Coreparser.php';


class Villeroyboch extends Coreparser implements Sceleton
{

    /**
     * Porzellantreff constructor.
     */


    public function  getData($url, $product_id = false, $secure = true)
    {

        libxml_use_internal_errors(true);
        $f = new DOMDocument("1.0", "utf-8");

        $_string_html = $this->get_page($url);
		
		if (!$_string_html){
			return false;
		}
		
        $f->loadHTML($_string_html);
        libxml_clear_errors();

        $finder = new DomXPath($f);

        // Узнаем, возмжно это набор?
        $nodes = $finder->query('.//*[@id="super-product-table"]');
        if ($nodes->length) {
            $price = 0;
            $specialPrice = 0;
            $nodes = $finder->query('.//*[@id="super-product-table"]//tbody//tr');
            foreach ($nodes as $node) {
                // Сначала проверяем, может у нас товар со скидкой
                $n = $finder->query('.//*[@class="old-price"]', $node);
                if ($n->length) {
                    foreach ($n as $x) {
                        preg_match("/[0-9]*[.,]?[0-9]+/", str_replace('.', '', $x->nodeValue), $matches);
                        $p = str_replace(',', '.', $matches[0]);
                        break;
                    }

                    $n2 = $finder->query('.//*[@class="special-price"]', $node);

                    foreach ($n2 as $x) {
                        preg_match("/[0-9]*[.,]?[0-9]+/", str_replace('.', '', $x->nodeValue), $matches);
                        $s = str_replace(',', '.', $matches[0]);
                        break;
                    }

                } else {
                    // Получаем нормальную цену
                    $n = $finder->query('.//*[@class="price"]', $node);
                    foreach ($n as $x) {
                        preg_match("/[0-9]*[.,]?[0-9]+/", str_replace('.', '', $x->nodeValue), $matches);
                        $p = str_replace(',', '.', $matches[0]);
                        $s = str_replace(',', '.', $matches[0]);
                        break;
                    }
                }


                // Получили цену позиции
                // Нужно получить кол-во
                $cnt = 1;
                $n = $finder->query('.//*[@title="Anzahl"]/@value', $node);
                foreach ($n as $x) {
                    $cnt = $x->textContent;
                    break;
                }
                $price += ($p * $cnt);
                $specialPrice += ($s * $cnt);
            }

            if ($specialPrice == $price) {
                $specialPrice = 0;
            }
        } else {
            // Это не набор

            // Сначала пытаемся получить зачеркнутую цену.
            $nodes = $finder->query('.//*/div[@id="product-shop"]//p[@class="old-price"]');
            $oldPrice = 0;
            foreach ($nodes as $node) {
                preg_match("/[0-9]*[.,]?[0-9]+/", str_replace('.', '', $node->nodeValue), $matches);
                $oldPrice = str_replace(',', '.', $matches[0]);
                break;
            }

            // У нас есть старая и новая цена. То есть товар акционный
            if ($oldPrice) {
                $price = $oldPrice;
                // Получаем специальную цену.
                $nodes = $finder->query('.//*/div[@id="product-shop"]//p[@class="special-price"]');
                $specialPrice = 0;
                foreach ($nodes as $node) {
                    preg_match("/[0-9]*[.,]?[0-9]+/", str_replace('.', '', $node->nodeValue), $matches);
                    $specialPrice = str_replace(',', '.', $matches[0]);
                    break;
                }
            } else {
                $nodes = $finder->query('.//*/div[@id="product-shop"]//span[@class="price"]');
                $specialPrice = 0;
                foreach ($nodes as $node) {
                    preg_match("/[0-9]*[.,]?[0-9]+/", str_replace('.', '', $node->nodeValue), $matches);
                    $price = str_replace(',', '.', $matches[0]);
                    break;
                }

            }
        }

        $nodes = $finder->query('.//h1[@class="product-name"]');
        foreach ($nodes as $node) {
            $name = $node->nodeValue;
            break;
        }

        // Наличие. Изначально думаем что товар есть в наличии. А потом проверяем, вдруг его нет.
        $availability = true;
        $nodes = $finder->query('.//*[contains(@class,"out-of-stock")]');
        if ($nodes->length) {
            $availability = false;
        }


        return array(
            'price'         => $price,
            'name'          => $name,
            'special_price' => $specialPrice,
            'special'       => $specialPrice,
            'stock'         => $availability,
            'prime'         => false
        );
    }


}