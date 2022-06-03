<?php

class ModelToolPriceFilter extends Model {

    public function getPriceFilter($category_id,$attributes_filter,$manufacturers_filter_category,$option_filter,$filter) {
       
        if (isset($category_id)){
        $data = array(
            'filter_category_id' => $category_id,
            'attributes_filter' => $attributes_filter,
            'filter_manufacturer_id' => $manufacturers_filter_category,
            'option_filter' => $option_filter,
            'filter_filter' => $filter
        );}else{
           $data = array(
            'attributes_filter' => $attributes_filter,
            'filter_manufacturer_id' => $manufacturers_filter_category,
            'option_filter' => $option_filter,
               'filter_filter' => $filter
        );  
        }
        $results_produkt = $this->model_catalog_product->getProducts($data);


        $poleMoznosti = array();
        foreach ($results_produkt as $key => $value) {

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->tax->calculate($value['price'], $value['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = false;
            }

            if ((float) $value['special']) {
                $special = $this->tax->calculate($value['special'], $value['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $special = false;
            }

            $tempPrice = ($special) ? $special : $price;
            
            $tempPrice = $this->currency->format($tempPrice,'', '', false);
            
            $tempPrice = str_replace(' ', '', $tempPrice);
            $tempPrice = str_replace(',', '', $tempPrice);
            
            $tempPrice = floatval($tempPrice);
            //$tempPrice = round($tempPrice,0,PHP_ROUND_HALF_DOWN);
           
            $poleMoznosti[$value['product_id']] = $tempPrice;
        }

        $poleMoznosti2 = array();
        foreach($poleMoznosti as $key => $row){
            if ($row == min($poleMoznosti)){
                  $poleMoznosti2[$key] = floor($row);
            }else if( $row == max($poleMoznosti)){
                  $poleMoznosti2[$key] = ceil($row);
            }else{
                  $poleMoznosti2[$key] = round($row,0);
            }
        }
        
     
        return $poleMoznosti2;
    }

}

?>
