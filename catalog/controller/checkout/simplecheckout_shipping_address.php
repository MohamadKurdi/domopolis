<?php
    /*
        @author	Dmitriy Kubarev
        @link	http://www.simpleopencart.com
    */
    
    include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');
    
    class ControllerCheckoutSimpleCheckoutShippingAddress extends SimpleController {
        private $_templateData = array();
        
        private function init() {
            $this->loadLibrary('simple/simplecheckout');
            
            $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
            
            $this->language->load('checkout/simplecheckout');
            
             foreach ($this->language->loadRetranslate('checkout/simplecheckout') as $translationСode => $translationText){
				$this->_templateData[$translationСode] = $translationText;
			}
            
            $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');
            
            if ($get_route == 'checkout/simplecheckout_shipping_address') {
                $this->simplecheckout->init('shipping_address');
            }
        }
        
        public function index() {
            if (!$this->simplecheckout->hasShipping()) {
                return;
            }
            
            $this->init();
            
            if ($this->simplecheckout->isBlockHidden('shipping_address') || (!$this->simplecheckout->isBlockHidden('shipping_address') && !$this->simplecheckout->isBlockHidden('payment_address') && $this->simplecheckout->isAddressSame())) {
                return;
            }
            
            $this->_templateData['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
            $this->_templateData['text_select']                    = $this->language->get('text_select');
            $this->_templateData['text_add_new']                   = $this->language->get('text_add_new');
            $this->_templateData['text_select_address']            = $this->language->get('text_select_address');
            
            $this->_templateData['rows']                           = $this->simplecheckout->getRows('shipping_address');
            $this->_templateData['hidden_rows']                    = $this->simplecheckout->getHiddenRows('shipping_address');
            
            if (!$this->simplecheckout->validateFields('shipping_address')) {
                $this->simplecheckout->addError('shipping_address');
            }
            
            $this->_templateData['display_header'] = $this->simplecheckout->getSettingValue('displayHeader', 'shipping_address');
            $this->_templateData['display_error']  = $this->simplecheckout->displayError('shipping_address');
            $this->_templateData['has_error']      = $this->simplecheckout->hasError('shipping_address');
            $this->_templateData['hide']           = $this->simplecheckout->isBlockHidden('shipping_address');
            
            $this->_templateData['customer_data'] = $this->session->data['simple']['customer'];	
            
            $this->load->model('catalog/product');
            $this->load->model('tool/image');
            
             $version = $this->simplecheckout->getOpencartVersion();
            
            if ($version >= 200) {
                $this->load->model('tool/upload');
            }
            
            if ($version < 210) {
                $this->loadLibrary('encryption');
            }
            
            $this->_templateData['column_image']         = $this->language->get('column_image');
            $this->_templateData['column_name']          = $this->language->get('column_name');
            $this->_templateData['column_model']         = $this->language->get('column_model');
            $this->_templateData['column_quantity']      = $this->language->get('column_quantity');
            $this->_templateData['column_price']         = $this->language->get('column_price');
            $this->_templateData['column_total']         = $this->language->get('column_total');
            $this->_templateData['text_until_cancelled'] = $this->language->get('text_until_cancelled');
            $this->_templateData['text_freq_day']        = $this->language->get('text_freq_day');
            $this->_templateData['text_freq_week']       = $this->language->get('text_freq_week');
            $this->_templateData['text_freq_month']      = $this->language->get('text_freq_month');
            $this->_templateData['text_freq_bi_month']   = $this->language->get('text_freq_bi_month');
            $this->_templateData['text_freq_year']       = $this->language->get('text_freq_year');
            $this->_templateData['text_trial']           = $this->language->get('text_trial');
            $this->_templateData['text_recurring']       = $this->language->get('text_recurring');
            $this->_templateData['text_length']          = $this->language->get('text_length');
            $this->_templateData['text_recurring_item']  = $this->language->get('text_recurring_item');
            $this->_templateData['text_payment_profile'] = $this->language->get('text_payment_profile');
            $this->_templateData['text_cart']            = $this->language->get('text_cart');
            
            $this->_templateData['text_clear_cart']               = $this->language->get('text_clear_cart');
            $this->_templateData['text_clear_cart_question']      = $this->language->get('text_clear_cart_question');
            
            $this->_templateData['button_update'] = $this->language->get('button_update');
            $this->_templateData['button_remove'] = $this->language->get('button_remove');
            
            $this->_templateData['products'] = array();
            
            $products = $this->cart->getProducts();
            
            $points_total = 0;
            
            foreach ($products as $product) {
                
                $product_total = 0;
                
                foreach ($products as $product_2) {
                    if ($product_2['product_id'] == $product['product_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }
                
                $option_data = array();
                
                foreach ($product['option'] as $option) {
                    if ($version >= 200) {
                        if ($option['type'] != 'file') {
                            $value = $option['value'];
                            } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                            
                            if ($upload_info) {
                                $value = $upload_info['name'];
                                } else {
                                $value = '';
                            }
                        }
                        } else {
                        if ($option['type'] != 'file') {
                            $value = $option['option_value'];
                            } else {
                            $encryption = new Encryption($this->config->get('config_encryption'));
                            $option_value = $encryption->decrypt($option['option_value']);
                            $filename = substr($option_value, 0, strrpos($option_value, '.'));
                            $value = $filename;
                        }
                    }
                    
                    $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }
                
                if ($product['image']) {
                    if ($version < 220) {
                        $image_cart_width = $this->config->get('config_image_cart_width');
                        $image_cart_width = $image_cart_width ? $image_cart_width : 40;
                        $image_cart_height = $this->config->get('config_image_cart_height');
                        $image_cart_height = $image_cart_height ? $image_cart_height : 40;
                        } elseif ($version < 300) {
                        $image_cart_width = $this->config->get($this->config->get('config_theme') . '_image_cart_width');
                        $image_cart_width = $image_cart_width ? $image_cart_width : 40;
                        $image_cart_height = $this->config->get($this->config->get('config_theme') . '_image_cart_height');
                        $image_cart_height = $image_cart_height ? $image_cart_height : 40;
                        } else {
                        $image_cart_width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width');
                        $image_cart_width = $image_cart_width ? $image_cart_width : 40;
                        $image_cart_height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height');
                        $image_cart_height = $image_cart_height ? $image_cart_height : 40;
                    }
                    
                    $image = $this->model_tool_image->resize($product['image'], $image_cart_width, $image_cart_height);
                    } else {
                    $image = '';
                }
                
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->simplecheckout->formatCurrency($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                    $price = false;
                }
                
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $total = $this->simplecheckout->formatCurrency($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
                    } else {
                    $total = false;
                }
                
                $old_price = null;
                
                $product_info = $this->model_catalog_product->getProduct($product['product_id']);
                
                if ($product_info['special']) {
                    $old_price = $this->simplecheckout->formatCurrency($this->tax->calculate($product_info['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                }
                
                if ($version >= 200) {
                    $recurring = '';
                    
                    if ($product['recurring']) {
                        $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                        );
                        
                        if ($product['recurring']['trial']) {
                            $recurring = sprintf($this->language->get('text_trial_description'), $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                        }
                        
                        if ($product['recurring']['duration']) {
                            $recurring .= sprintf($this->language->get('text_payment_description'), $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                            } else {
                            $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                        }
                    }
                    
                    $this->_templateData['products'][] = array(
                    'key'       => isset($product['key']) ? $product['key'] : '',
                    'cart_id'   => isset($product['cart_id']) ? $product['cart_id'] : '',
                    'thumb'     => $image,
                    'name'      => $product['name'],
                    'model'     => $product['model'],
                    'minimum'   => $product['minimum'],
                    'option'    => $option_data,
                    'recurring' => $recurring,
                    'quantity'  => $product['quantity'],
                    'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                    'price'     => $price,
                    'old_price' => $old_price,
                    'total'     => $total,
                    'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                    );
                    } elseif ($version >= 156) {
                    $profile_description = '';
                    
                    if ($product['recurring']) {
                        $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                        );
                        
                        if ($product['recurring_trial']) {
                            $recurring_price = $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
                            $profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
                        }
                        
                        $recurring_price = $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
                        
                        if ($product['recurring_duration']) {
                            $profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                            } else {
                            $profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                        }
                    }
                    
                    $this->_templateData['products'][] = array(
                    'key'                 => $product['key'],
                    'thumb'               => $image,
                    'name'                => $product['name'],
                    'model'               => $product['model'],
                    'minimum'             => $product['minimum'],
                    'option'              => $option_data,
                    'quantity'            => $product['quantity'],
                    'stock'               => $product['stock'],
                    'reward'              => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'               => $price,
                    'old_price'           => $old_price,
                    'total'               => $total,
                    'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'recurring'           => $product['recurring'],
                    'profile_name'        => isset($product['profile_name']) ? $product['profile_name'] : '',
                    'profile_description' => $profile_description,
                    );
                    } else {
                    $this->_templateData['products'][] = array(
                    'key'       => $product['key'],
                    'thumb'     => $image,
                    'name'      => $product['name'],
                    'model'     => $product['model'],
                    'minimum'   => $product['minimum'],
                    'option'    => $option_data,
                    'quantity'  => $product['quantity'],
                    'stock'     => $product['stock'],
                    'reward'    => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'old_price' => $old_price,
                    'price'     => $price,
                    'total'     => $total,
                    'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                    );
                }
                
                if ($product['points']) {
                    $points_total += $product['points'];
                }
            }
            
            $totals = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->_templateData['modules'] = array();

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            if ($version < 200 || $version >= 300) {
                $this->load->model('setting/extension');

                $results = $this->model_setting_extension->getExtensions('total');
            } else {
                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('total');
            }

            foreach ($results as $key => $result) {
                if ($version < 300) {
                    $sort_order[$key] = $this->config->get($result['code'] . '_sort_order');
                } else {
                    $sort_order[$key] = $this->config->get('total_' . $result['code'] . '_sort_order');
                }                
            }

            array_multisort($sort_order, SORT_ASC, $results);
            
            $shipping_cost = isset($this->session->data['shipping_method']) && isset($this->session->data['shipping_method']['cost']) ? $this->session->data['shipping_method']['cost'] : 0;
            $skip_zero_cost_shipping = $this->simplecheckout->getSettingValue('skipZeroCostShipping', 'cart');
            $ignore_shipping = $this->simplecheckout->getSettingValue('ignoreShipping');

            foreach ($results as $result) {
                if ($version < 300) {
                    $status = $this->config->get($result['code'] . '_status');
                } else {
                    $status = $this->config->get('total_' . $result['code'] . '_status');
                }

                if ($result['code'] == 'shipping' && ((!$shipping_cost && $skip_zero_cost_shipping) || $ignore_shipping)) {
                    $status = false;
                }

                if ($status) {
                    $this->simplecheckout->loadModel('total/' . $result['code']);

                    if ($version < 220) {
                        $this->{'model_total_' . $result['code']}->getTotal($totals, $total, $taxes);
                    } else {
                        $this->{'model_total_' . $result['code']}->getTotal($total_data);
                    }

                    $this->_templateData['modules'][$result['code']] = true;
                }
            }

            $sort_order = array();

            foreach ($totals as $key => $value) {
                $sort_order[$key] = $value['sort_order'];

                if (!isset($value['text'])) {
                    $totals[$key]['text'] = $this->simplecheckout->formatCurrency($value['value']);
                }

                if (!empty($value['code']) && $value['code'] == 'shipping' && isset($this->session->data['shipping_method']) && isset($this->session->data['shipping_method']['text'])) {
                    $totals[$key]['text'] = $this->session->data['shipping_method']['text'];
                }
            }

            array_multisort($sort_order, SORT_ASC, $totals);
        }

        $this->_templateData['totals'] = $totals;
            
            $this->_templateData['entry_coupon'] = $this->language->get('entry_coupon');
            $this->_templateData['entry_voucher'] = $this->language->get('entry_voucher');
            
            $points = $this->customer->getRewardPoints();
            $points_to_use = $points > $points_total ? $points_total : $points;
            $this->_templateData['points'] = $points_to_use;
            
            $this->_templateData['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_to_use);
            
            $this->_templateData['reward']  = isset($this->session->data['reward']) ? $this->session->data['reward'] : '';
            $this->_templateData['voucher'] = isset($this->session->data['voucher']) ? $this->session->data['voucher'] : '';
            $this->_templateData['coupon']  = isset($this->session->data['coupon']) ? $this->session->data['coupon'] : '';
            
            $this->_templateData['display_weight'] = $this->simplecheckout->displayWeight();
            
            if ($this->_templateData['display_weight']) {
                $this->_templateData['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            }
            
            $this->_templateData['additional_path'] = $this->simplecheckout->getAdditionalPath();
            $this->_templateData['hide'] = $this->simplecheckout->isBlockHidden('cart');
            
            $currentTheme = $this->config->get('config_template');
            
            if ($currentTheme == 'shoppica' || $currentTheme == 'shoppica2') {
                $this->_templateData['cart_total'] = $this->simplecheckout->formatCurrency($total);
                } else {
                $minicart = $this->simplecheckout->getSettingValue('minicartText', 'cart');
                
                $text_items = '';
                $language_code = $this->simplecheckout->getCurrentLanguageCode();
                
                if ($minicart && !empty($minicart[$language_code])) {
                    $text_items = $minicart[$language_code];
                }
                
                if (!$text_items) {
                    $this->language->load('checkout/cart');
                    $text_items = $this->language->get('text_items');
                    $this->language->load('checkout/simplecheckout');
                }
                
                if (strpos($text_items, '{quantity}') !== false || strpos($text_items, '{total}') !== false) {
                    $find = array(
                    '{quantity}', 
                    '{total}'
                    );
                    
                    $replace = array(
                    '{quantity}' => $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), 
                    '{total}' => $this->simplecheckout->formatCurrency($total)
                    );
                    
                    $this->_templateData['cart_total'] = str_replace($find, $replace, $text_items);
                    } else {
                    $this->_templateData['cart_total'] = sprintf($text_items, $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->simplecheckout->formatCurrency($total));
                } 
            }
            
            $this->setOutputContent($this->renderPage('checkout/simplecheckout_shipping_address', $this->_templateData));
        }
        
        public function update_session() {
            if (!$this->simplecheckout->hasShipping()) {
                return;
            }
            
            $this->init();
            
            if (empty($this->session->data['simple']['shipping_address'])) {
                return;
            }
            
            $version = $this->simplecheckout->getOpencartVersion();
            
            $address = $this->session->data['simple']['shipping_address'];
            
            if ($version >= 200) {
                $this->session->data['shipping_address'] = $address;
                } else {
                if (!$this->customer->isLogged()) {
                    $this->session->data['guest']['shipping'] = $address;
                }
                
                unset($this->session->data['shipping_address_id']);
                unset($this->session->data['shipping_country_id']);
                unset($this->session->data['shipping_zone_id']);
                unset($this->session->data['shipping_postcode']);
                
                if (!empty($address['address_id'])) {
                    $this->session->data['shipping_address_id'] = $address['address_id'];
                }
                
                if (!empty($address['country_id'])) {
                    $this->session->data['shipping_country_id'] = $address['country_id'];
                    } else {
                    $this->session->data['shipping_country_id'] = 0;
                }
                
                if (!empty($address['zone_id'])) {
                    $this->session->data['shipping_zone_id'] = $address['zone_id'];
                    } else {
                    $this->session->data['shipping_zone_id'] = 0;
                }
                
                if (!empty($address['postcode'])) {
                    $this->session->data['shipping_postcode'] = $address['postcode'];
                }
                
                if (!$this->session->data['shipping_country_id'] && !$this->session->data['shipping_zone_id']) {
                    unset($this->session->data['shipping_country_id']);
                    unset($this->session->data['shipping_zone_id']);
                }
            }
            
            if ($version == 152 && !empty($this->session->data['guest']['shipping']) && is_array($this->session->data['guest']['shipping'])) {
                $clear = true;
                foreach ($this->session->data['guest']['shipping'] as $key => $value) {
                    if ($value) {
                        $clear = false;
                        break;
                    }
                }
                if ($clear) {
                    unset($this->session->data['guest']['shipping']);
                }
            }
            
            if ($address['country_id'] || $address['zone_id']) {
                if ($version > 151) {
                    $this->tax->setShippingAddress($address['country_id'], $address['zone_id']);
                    } else {
                    $this->tax->setZone($address['country_id'], $address['zone_id']);
                }
                } else {
                if ($version > 151) {
                    $this->tax->setShippingAddress(0, 0);
                    } else {
                    $this->tax->setZone(0, 0);
                }
                
                if (!$this->customer->isLogged() && $this->config->get('config_tax_default') == 'shipping') {
                    if ($version > 151) {
                        $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                        } else {
                        $this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
                    }
                }
            }
        }
    }
