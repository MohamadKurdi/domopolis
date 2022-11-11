<?php
class ModelTotalSet extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('set_status')) {
			$this->load->language('total/set');
			$this->load->model('localisation/currency');
            $this->load->model('catalog/set');
            $this->load->model('catalog/product');
            $sets_in_cart = array();
            if(isset($this->session->data['set'])){
              	foreach ($this->session->data['set'] as $key_set => $products_set) {
              	     $set_temp = explode('_', $key_set);
                     $set_id = $set_temp[0];
                     $set_active = true;      
                     foreach($products_set as $key => $product_set_count){
                        if(!isset($this->session->data['cart'][$key])||$this->session->data['cart'][$key]<$product_set_count){
                            $set_active = false;
                        } 
                     }
                     if($set_active){
                        $set_info = $this->model_catalog_set->getSet($set_id);
                        if($set_info){
                            $prod_str = array();
                            $sale = 0;
                            $old_total = 0;
                            $cart_product = $this->cart->getProducts();
                            foreach($products_set as $key => $product_set_count){
                                 $prod_str[] = $cart_product[$key]['name'];
                                 
                                 $current_product_price = $this->tax->calculate($cart_product[$key]['price'], $cart_product[$key]['tax_class_id'], $this->config->get('config_tax'));
                                 $old_total += (float)$current_product_price*(int)$product_set_count;
                                     
                                 //$old_total += (float)$cart_product[$key]['price']*(int)$product_set_count;  
                            }
                            $price_set = $this->tax->calculate($set_info['price'], $set_info['tax_class_id'], $this->config->get('config_tax'));
                            $sale = (float)$old_total-(float)$price_set;                             
                            //$sale = (float)$old_total-(float)$set_info['price'];
                            $sets_in_cart[] = array(
                                'name'     => $set_info['name'] . ' (' . implode(', ', $prod_str) . ')',
                                'price_set'=> (float)$price_set,
                                'sale'     => (float)$sale
                            );
                        } else {
                            unset($this->session->data['set'][$key_set]);
                        }
                     } else { 
                        unset($this->session->data['set'][$key_set]);
                     }
              	}                
            }          
            if($sets_in_cart){
                foreach($sets_in_cart as $set_in_cart){
                    $text_title = '';
                    $text_title .= $this->language->get('text_set');
                    $text_title .= '"' . $set_in_cart['name'] . '"';
                    $current_sale_set = $this->getSales($set_in_cart['sale']);  
         			$total_data[] = array( 
                            'code'       => 'set',
                    		'title'      => $text_title,
                    		'text'       => '-' . $this->currency->format($current_sale_set),
                    		'value'      => $current_sale_set,
            				'sort_order' => $this->config->get('set_sort_order')
         			);
         			$total -= (float)$current_sale_set;
                }
            } else {
                unset($this->session->data['set']);
            }
		}
	}

	function getSales($sale_set, $price_set = 0, $sale = 0) {
        $current_sale = 0;
        if($sale){
		  $current_sale = (float)$price_set*((float)$sale/100)+(float)$sale_set;  
		} else {
		  $current_sale = (float)$sale_set;
		}
        return $current_sale;
	}
}
?>