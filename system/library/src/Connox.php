<?php

include_once 'Sceleton.php';
include_once 'Coreparser.php';

    /**
     * Created by PhpStorm.
     * User: horror
     * Date: 08.06.2018
     * Time: 2:03
     */
    class Connox extends Coreparser implements Sceleton
    {




        /**
         * Porzellantreff constructor.
         */



        public function  getData($url,$product_id = false, $secure = true)
        {
            /**
             * New parser
             */
            $url = trim($url);
            $thing=array();
            $thing['price_red']= "";
            libxml_use_internal_errors(true);
            $_string_html = $this->get_page($url);
            $doc = new DOMDocument();
            @$doc->loadHTML($_string_html);
            $xpath = new DOMXPath($doc);
            libxml_clear_errors();
			
		

            //цена товара [contains(@class,"head")] .//span[@class="price"]
            $nodes = $xpath->query('.//span[contains(@class,"price")]');
            foreach ($nodes as $node) {
                $thing['price'] =$this->fixPrice4($node->nodeValue);
                break;
            }
			
				var_dump($nodes);

            //название товара
            $nodes = $xpath->query('.//div[@class="product-details"]/h1');
            foreach ($nodes as $node) {
                $thing['name'] = trim($node->nodeValue);
                break;
            }

            //старая цена
            $nodes = $xpath->query('.//div[@class="product-price"]//div//span[@data-badge]/strike');
            foreach ($nodes as $node) {
                $thing['special_price'] = $this->fixPrice4($node->nodeValue);
                break;
            }

            //новая цена скидкой
            $nodes = $xpath->query('//div[@class="product-price"]//div//span[@data-badge="sale"]//span[@class="price red"]');
            foreach ($nodes as $node) {
                $thing['price_red'] = $this->fixPrice4($node->nodeValue);
                break;
            }


            //check is goods on stock
            $nodes =$xpath->query('//div[@class="product-price"]//div//span[@class="darkred"]| //span[@class="info"]/strong[@class]');
            foreach ($nodes as $node) {
                $thing['stock'] = $node->textContent;
                break;
            }


            //проверка если роспродажа 0 нет. 1 да.
            $nodes = $xpath->query('//div[@class="product-price"]//div//span[@data-badge="sale"]');
            if ($nodes->length == 1) {
                $thing['sale']= true;

            }else{
                $thing['sale']= false;
            }


            return array(
                'price' => ($thing['sale']== true) ? $thing['special_price'] : $thing['price'],
                'name' => $thing['name'],
                'special_price' =>($thing['price_red'])?$thing['price_red']:"",
                'special' => ($thing['price_red'])?$thing['price_red']:"",
                'stock' => $thing['stock'],
                'prime' => false
            );
        }



    }