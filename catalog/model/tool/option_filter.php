<?php

class ModelToolOptionFilter extends Model {

    public function getOptionFilter($category_id) {

        $data = array(
            'filter_category_id' => $category_id,
        );
        $results_produkt = $this->model_catalog_product->getProducts($data);


        foreach ($results_produkt as $key => $value) {

            $product_id = $value['product_id'];

            $product_option_data[$product_id] = array();

            $product_option_query = $this->db->query("SELECT * FROM product_option po LEFT JOIN `option` o ON (po.option_id = o.option_id) LEFT JOIN option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.sort_order");

            foreach ($product_option_query->rows as $product_option) {
                if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                    $product_option_value_data[$product_id] = array();

                    $product_option_value_query = $this->db->query("SELECT *  FROM product_option_value pov LEFT JOIN option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int) $product_id . "' AND pov.product_option_id = '" . (int) $product_option['product_option_id'] . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY ov.sort_order DESC");

                    foreach ($product_option_value_query->rows as $product_option_value) {
                        $product_option_value_data[$product_id][] = array(
                            'product_option_value_id' => $product_option_value['product_option_value_id'],
                            'option_value_id' => $product_option_value['option_value_id'],
                            'name' => $product_option_value['name'],
                            'quantity' => $product_option_value['quantity'],
                            'order' => $product_option_value['sort_order'],
                            'subtract' => $product_option_value['subtract']
                        );
                    }


                    $product_option_data[$product_id][] = array(
                        'product_option_id' => $product_option['product_option_id'],
                        'option_id' => $product_option['option_id'],
                        'name' => $product_option['name'],
                        'type' => $product_option['type'],
                        'option_value' => $product_option_value_data[$product_id]
                    );
                } else {
                    $product_option_data[$product_id][] = array(
                        'product_option_id' => $product_option['product_option_id'],
                        'option_id' => $product_option['option_id'],
                        'name' => $product_option['name'],
                        'type' => $product_option['type'],
                        'option_value' => $product_option['option_value']
                    );
                }
            }
        }

        $poleMoznosti = array();
        if (isset($product_option_data)){
        foreach ($product_option_data as $value) {

            // moze byt viac typov moznosti
            foreach ($value as $value2) {
                if (isset($value2['option_value']) && is_array($value2['option_value']) ){
                foreach ($value2['option_value'] as $value3) {

                    if (isset($value3['quantity']) && $value3['quantity'] > 0){
                        $plusInt = (isset($poleMoznosti[$value2['name']][$value3['option_value_id']][0])) ? ++$poleMoznosti[$value2['name']][$value3['option_value_id']][0] : 1;
                        $poleMoznosti[$value2['name']][$value3['option_value_id']] = array($plusInt,$value3['name'], 'order' => $value3['order']);
                    }}
            }}
        }}

        function aasort(&$array, $key) {
            $sorter = array();
            $ret = array();

            reset($array);
            foreach ($array as $ii => $va) {
                $sorter[$ii] = $va[$key];
            }

           
            asort($sorter);
            foreach ($sorter as $ii => $va) {
                $ret[$ii] = $array[$ii];
            }

            $array = $ret;
        }


//
//        // sort
        foreach ($poleMoznosti as $key => $value) {
           
            aasort($poleMoznosti[$key],"order");
        }

        return $poleMoznosti;
    }

}

?>
