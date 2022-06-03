<?php

include_once 'Sceleton.php';
include_once 'Coreparser.php';

/**
 * Created by PhpStorm.
 * User: horror
 * Date: 08.06.2018
 * Time: 2:03
 */
class Porzellantreff extends Coreparser implements Sceleton
{

    /**
     * Porzellantreff constructor.
     */


    public function  getData($url, $product_id = false, $secure = true)
    {
        $url = trim($url);
        $data = array();
        $data['special_price'] = "";

        libxml_use_internal_errors(true);
        $_html = $this->get_page($url);
        $doc = new DOMDocument();
        @$doc->loadHTML($_html);
        $xpath = new DOMXPath($doc);
        libxml_clear_errors();


        $nodes = $xpath->query('//div[@class="product-info-col"]//span[@itemprop="price"]');
        foreach ($nodes as $node) {
            if ($nodes->length == 1) {
                $data['price'] = $this->fixPrice4($node->nodeValue);
            } else {
                $data['price'] = "";
            }

            break;
        }


        $nodes = $xpath->query('//h1[@class="title"]');
        foreach ($nodes as $node) {
            $data['name'] = trim($node->nodeValue);
            break;
        }


        $nodes = $xpath->query('//div[@class="product-info-col"]//div[@class="msrp"]');
        foreach ($nodes as $node) {
            $data['special_price'] = $this->fixPrice4($node->nodeValue);
            break;
        }


        $nodes = $xpath->query('//p[@class="delivery-time link-delivery-time"]');
        foreach ($nodes as $node) {
            $data['stock'] = trim($node->nodeValue);
            break;
        }


        $nodes = $xpath->query('//div[@class="product-info-col"]//div[@class="msrp"]');
        if ($nodes->length == 1) {
            $data['sale'] = true;

        } else {
            $data['sale'] = false;
        }

        return array(
            'price' => ($data['sale'] == true) ? $data['special_price'] : $data['price'],
            'name' => $data['name'],
            'special_price' => ($data['sale'] == true) ? $data['price'] : $data['special_price'],
            'special' => ($data['sale'] == true) ? $data['price'] : $data['special_price'],
            'stock' => $data['stock'],
            'prime' => false,
        );
    }


}