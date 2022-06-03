<?php
include_once DIR_SYSTEM . 'library/amazonPrice.php';
include_once 'Sceleton.php';
include_once 'Coreparser.php';


/**
 * Class Wmf
 */
class Wmf extends Coreparser implements Sceleton
{

    /**
     * Porzellantreff constructor.
     */


    public function  getData($url, $product_id = false, $secure = true)
    {
        sleep(mt_rand(3, 7));

        libxml_use_internal_errors(true);
        $f = new DOMDocument("1.0", "utf-8");

        try {
            $html = $this->get_page($url);
        } catch (Exception $e) {
            if ($secure) {
                sleep(5);

                $amazon = new amazonPrice();
                $amazon->getPriceByURL($url, false, false);
            } else {
                return false;
            }
        }

        // Если не получилось открыть страницу, ждем 5 секунд, и пробуем еще раз

        $f->loadHTML($html);
        libxml_clear_errors();

        $finder = new DomXPath($f);

        // Цена
        $nodes = $finder->query('.//*[@itemprop="price"]');
        foreach ($nodes as $node) {
            // Получаем цену
            preg_match("/[0-9]*[.,]?[0-9]+/", $node->getAttribute('content'), $matches);
            $price = str_replace(',', '.', $matches[0]);
            break;
        }

        // Зачеркнутая цена. Если она есть, то цена = цена со скидкой.
        $nodes = $finder->query('.//*[@id="product_addtocart_form"]//div[contains(concat(" ", normalize-space(@class), " "), " price-box ")]//p[contains(concat(" ", normalize-space(@class), " "), " old-price ")]');
        $specialPrice = 0;
        foreach ($nodes as $node) {
            // Получаем цену
            preg_match("/[0-9]*[.,]?[0-9]+/", $node->nodeValue, $matches);
            $specialPrice = str_replace(',', '.', $matches[0]);
            break;
        }

        // Наличие
        $nodes = $finder->query('.//*[@itemprop="availability"]');
        foreach ($nodes as $node) {
            $stock = $node->getAttribute('content');
            break;
        }
        $in_stock = false;
        if ($stock == 'http://schema.org/InStock') {
            $in_stock = true;
        } elseif ($stock == 'http://schema.org/LimitedAvailability') {
            $in_stock = 'limited';
        }

        // Название
        $nodes = $finder->query('.//h1[@itemprop="name"]');
        foreach ($nodes as $node) {
            $name = $node->nodeValue;
            break;
        }

        // Если наша цена == special_price, то меняем цены местами
        if (@$specialPrice > 0) {
            $nodes = $finder->query('.//*[@id="product_addtocart_form"]//div[contains(concat(" ", normalize-space(@class), " "), " price-box ")]//p[contains(concat(" ", normalize-space(@class), " "), " special-price ")]');
            foreach ($nodes as $node) {
                // Получаем цену
                preg_match("/[0-9]*[.,]?[0-9]+/", $node->nodeValue, $matches);
                $price = str_replace(',', '.', $matches[0]);
            }


            $tmp = $price;
            $price = $specialPrice;
            $specialPrice = $tmp;
        }

        return array(
            'price' => $price,
            'stock' => $in_stock,
            'name' => $name,
            'special' => @$specialPrice,
            'special_price' => @$specialPrice,
            'prime' => false
        );
    }


}