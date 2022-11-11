<?php
header("Content-type: text/html; charset=utf-8");

class ControllerCommonOcjoyajaxcart extends Controller {

    private $_path = HTTPS_SERVER;
    private $_name = 'Ocjoyajaxcart';

    public function index () {
        $this->language->load('module/ocjoyajaxcart');

        $this->data['type'] = $this->config->get($this->_name . '_type');
        $this->data['position'] = $this->config->get($this->_name . '_position');
        $this->data['topions'] = $this->config->get($this->_name . '_topions');
        $this->data['offset_x'] = $this->config->get($this->_name . '_offset_x');
        $this->data['offset_y'] = $this->config->get($this->_name . '_offset_y');
        $this->data['fheight'] = $this->config->get($this->_name . '_fheight');
        $this->data['fwidth'] = $this->config->get($this->_name . '_fwidth');
        $this->data['postype'] = $this->config->get($this->_name . '_postype');
        $this->data['image'] = $this->config->get($this->_name . '_image');
        $this->data['tcolor'] = $this->config->get($this->_name . '_tcolor');
        $this->data['tsize'] = $this->config->get($this->_name . '_tsize');
        $this->data['tmtop'] = $this->config->get($this->_name . '_tmtop');
        $this->data['tmright'] = $this->config->get($this->_name . '_tmright');
        $this->data['tmbottom'] = $this->config->get($this->_name . '_tmbottom');
        $this->data['tmleft'] = $this->config->get($this->_name . '_tmleft');
        $this->data['color_bgp'] = $this->config->get($this->_name . '_color_bgp');
        $this->data['head_bgp'] = $this->config->get($this->_name . '_head_bgp');
        $this->data['bhead_bgp'] = $this->config->get($this->_name . '_bhead_bgp');
        $this->data['chead_bgp'] = $this->config->get($this->_name . '_chead_bgp');
        $this->data['close_bg'] = $this->config->get($this->_name . '_close_bg');
        $this->data['remove_bg'] = $this->config->get($this->_name . '_remove_bg');
        $this->data['color_a'] = $this->config->get($this->_name . '_color_a');
        $this->data['color'] = $this->config->get($this->_name . '_color');
        $this->data['border'] = $this->config->get($this->_name . '_border');
        $this->data['scroll'] = $this->config->get($this->_name . '_scroll');
        $this->data['color_fgp'] = $this->config->get($this->_name . '_color_fgp');
        $this->data['color_fbgp'] = $this->config->get($this->_name . '_color_fbgp');
        $this->data['pbutton'] = $this->config->get($this->_name . '_pbutton');
        $this->data['empty'] = $this->config->get($this->_name . '_empty');
        $this->data['overlay'] = $this->config->get($this->_name . '_overlay');
        $this->data['pselect'] = $this->config->get($this->_name . '_pselect');
        $this->data['bselect'] = $this->config->get($this->_name . '_bselect');
        $this->data['flytype'] = $this->config->get($this->_name . '_flytype');
        $this->data['flyimage'] = $this->config->get($this->_name . '_flyimage');
        $this->data['color_f'] = $this->config->get($this->_name . '_color_f');
        $this->data['frselect'] = $this->config->get($this->_name . '_frselect');
        $this->data['size_f'] = $this->config->get($this->_name . '_size_f');
        $this->data['speed'] = $this->config->get($this->_name . '_speed');
        $this->data['rtselect'] = $this->config->get($this->_name . '_rtselect');
        $this->data['radius'] = $this->config->get($this->_name . '_radius');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array('text' => $this->language->get('text_home'), 'href' => $this->url->link('common/home'), 'separator' => false);

        $this->data['text_gift'] = $this->language->get('gift');
        $this->data['text_special_offer'] = $this->language->get('special_offer');
        $this->data['text_code'] = $this->language->get('code');
        $this->data['text_products_in_set'] = $this->language->get('products_in_set');

        $this->data['text_show'] = $this->language->get('show');
        $this->data['text_hide'] = $this->language->get('hide');

        if (isset($this->request->request['remove'])) {
            $this->cart->remove($this->request->request['remove']);
            unset($this->session->data['vouchers'][$this->request->request['remove']]);
        }
        if (isset($this->request->request['update'])) {
            $this->cart->update($this->request->request['update'], $this->request->request['qty']);
        }

        // Totals
        $this->load->model('setting/extension');

        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }

                $sort_order = array();
                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }
        }

        $this->data['totals'] = $total_data;

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
        $this->data['text_empty'] = $this->language->get('text_empty');
        $this->data['text_cart'] = $this->language->get('text_cart');
        $this->data['text_checkout'] = $this->language->get('text_checkout');

        $this->data['config_ocjoyajaxcart_countname'] = $this->config->get('config_ocjoyajaxcart_countname');
        $this->data['config_ocjoyajaxcart_countdesc'] = $this->config->get('config_ocjoyajaxcart_countdesc');
        $this->data['config_ocjoyajaxcart_showmodel'] = $this->config->get('config_ocjoyajaxcart_showmodel');

        $this->data['button_remove'] = $this->language->get('button_remove');

        $this->load->model('tool/image');

        $this->data['products'] = array();
        
//        print '<pre>';
//        print_r($this->cart->getProducts());
//        exit();

        foreach ($this->cart->getProducts() as $product) {
            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }

            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['option_value'];
                } else {
                    $filename = $this->encryption->decrypt($option['option_value']);

                    $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
                }

                $option_data[] = array('name' => $option['name'], 'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value), 'type' => $option['type']);
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            if ($product['price_national'] && $product['price_national'] > 0 && $product['currency'] == $this->currency->getCode()) {
                $price = $this->currency->format($this->tax->calculate($product['price_national'], $product['tax_class_id'], $this->config->get('config_tax')), $product['currency'], 1);
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')));
                $total_national = $this->currency->format($product['total_national'], $this->config->get('config_regional_currency'), '1');

            } else {
                $total = false;
                $total_national = false;
            }

            $this->data['products'][] = array(
                'key' => $product['key'],
                'thumb' => $image,
                'name' => $product['name'],
                'is_special_offer' => $product['is_special_offer'],
                'is_special_offer_present' => $product['is_special_offer_present'],
                'model' => $product['model'],
                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'option' => $option_data,
				'minimum' => $product['minimum'],
                'quantity' => $product['quantity'],
                'price' => $price,
                'total' => $total,
                'total_national' => $total_national,
                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'childProductArray' => $product['childProductArray']
            );
        }

        // Gift Voucher
        $this->data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $this->data['vouchers'][] = array('key' => $key, 'description' => $voucher['description'], 'amount' => $this->currency->format($voucher['amount']));
            }
        }

        $this->data['cart'] = $this->url->link('checkout/cart');

        $this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        $this->load->model('catalog/product');
        $this->data['ajaxcartproducts'] = array();

        $data = array('sort' => 'p.date_added', 'order' => 'DESC', 'start' => 0);

        $this->data['button_cart'] = $this->language->get('button_cart');
        $this->data['button_wishlist'] = $this->language->get('button_wishlist');
        $this->data['button_compare'] = $this->language->get('button_compare');

        $this->data['text_ajaxcart_head'] = $this->language->get('text_ajaxcart_head');
        $this->data['text_ajaxcart_empty'] = $this->language->get('text_ajaxcart_empty');
        $this->data['text_ajaxcart_continue'] = $this->language->get('text_ajaxcart_continue');
        $this->data['text_gotoorder'] = $this->language->get('text_gotoorder');
        $this->data['text_gotoshipping'] = $this->language->get('text_gotoshipping');

        $this->data['column_image'] = $this->language->get('column_image');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_delete'] = $this->language->get('column_delete');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_subtotal'] = $this->language->get('column_subtotal');

        $results = $this->model_catalog_product->getAjaxcartProducts($data);

        if (!empty($results)) {
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], 80, 80);
                } else {
                    $image = $this->model_tool_image->resize('no_image.jpg', 80, 80);

                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float) $result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $result['rating'];
                } else {
                    $rating = false;
                }

                $this->data['ajaxcartproducts'][] = array(
					'product_id' => $result['product_id'], 
					'thumb' => $image, 
					'name' => $result['name'], 
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..', 
					'price' => $price, 
					'special' => $special,
					'rating' => $rating, 
					'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
            }
        }

        $this->template = 'mattimeo/template/common/ocjoyajaxcart.tpl';

        $this->response->setOutput($this->render());

        $this->render();
    }
}

?>