<?php

class ControllerModulePriceFilter extends Controller {

    protected function index($setting) {
        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);
        } else {
            $parts = array();
        }

        $category_id = end($parts);

        $display_in_all = (isset($setting['display_in_all']) && $setting['display_in_all'] == '1') ? true : false;

        if ($display_in_all || isset($setting['category_source']) && in_array($category_id, $setting['category_source'])) {
            $this->load->model('catalog/category');
            $this->load->model('tool/price_filter');
            $this->language->load('module/price_filter');

            $this->data['heading_title'] = $this->language->get('heading_title');


            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }
            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }
            if (isset($this->request->get['manufacturers_filter'])) {
                $url .= '&manufacturers_filter=' . $this->request->get['manufacturers_filter'];
            }
            if (isset($this->request->get['option_filter'])) {
                $url .= '&option_filter=' . $this->request->get['option_filter'];
            }
            if (isset($this->request->get['attributes_filter'])) {
                if (is_array($this->request->get['attributes_filter'])) {
                    foreach ($this->request->get['attributes_filter'] as $attribute_id => $attributes) {
                        if (is_array($attributes)) {
                            foreach ($attributes as $value) {
                                $url .= '&attributes_filter[' . $attribute_id . '][]=' . $value;
                            }
                        }
                    }
                }
            }
            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $this->data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));



            $this->load->model('catalog/product');

            $this->data['price_filter_groups'] = array();

            if (isset($this->request->get['manufacturers_filter'])) {
                $manufacturers_filter_category = $this->request->get['manufacturers_filter'];
            } else {
                $manufacturers_filter_category = null;
            }
            if (isset($this->request->get['option_filter'])) {
                $option_filter = $this->request->get['option_filter'];
            } else {
                $option_filter = null;
            }

             if (isset($this->request->get['filter'])) {
                $filter = $this->request->get['filter'];
            } else {
                $filter = null;
            }
            if (isset($this->request->get['attributes_filter'])) {
                $attributes_filter = $this->request->get['attributes_filter'];
            } else {
                $attributes_filter = null;
            }

            $price_filter_data = $this->model_tool_price_filter->getPriceFilter($category_id, $attributes_filter, $manufacturers_filter_category, $option_filter,$filter);
            // pre($price_filter_data);

            if ($price_filter_data) {

                $symbolR = $this->currency->getSymbolRight();
                $symbolL = $this->currency->getSymbolLeft();
                if ($symbolR != '' || $symbolL != '') {
                    if ($symbolR != "") {
                        $this->data['symbol'] = ' $amountmin.html($slider.slider("values", 0) + \'' . $symbolR . '\');  ';
                        $this->data['symbol'] .= ' $amountmax.html($slider.slider("values", 1) + \'' . $symbolR . '\');  ';
                        $this->data['symbol_html'] = ' $amountmin.html(ui.values[0] + \'' . $symbolR . '\'); ';
                        $this->data['symbol_html'] .= ' $amountmax.html(ui.values[1] + \'' . $symbolR . '\'); ';
                    } else if ($symbolL != '') {
                        $this->data['symbol'] = ' $amountmin.html(\'' . $symbolL . '\' + $slider.slider("values", 0)); ';
                        $this->data['symbol'] .= ' $amountmax.html(\'' . $symbolL . '\' + $slider.slider("values", 1)); ';
                        $this->data['symbol_html'] = ' $amountmin.html(\'' . $symbolL . '\' +ui.values[0]); ';
                        $this->data['symbol_html'] .= ' $amountmax.html(\'' . $symbolL . '\' +ui.values[1]); ';
                    }

                    if ($symbolR != "" && $symbolL != '') {
                        $this->data['symbol'] = ' $amountmin.html(\'' . $symbolL . '\' +$slider.slider("values", 0) + \'' . $symbolR . '\'); ';
                        $this->data['symbol'] .= ' $amountmax.html(\'' . $symbolL . '\' + $slider.slider("values", 1)+ \'' . $symbolR . '\'); ';
                        $this->data['symbol_html'] = ' $amountmin.html(\'' . $symbolL . '\' +ui.values[0] + \'' . $symbolR . '\'); ';
                        $this->data['symbol_html'] .= ' $amountmax.html(\'' . $symbolL . '\' +ui.values[1]+ \'' . $symbolR . '\'); ';
                    }
                } else {
                    $this->data['symbol'] = '';
                }
                if ($this->data['symbol'] == '') {
                    $this->data['formatprice'] = $this->currency->getSymbolRight();
                }
                $this->data['price_min'] = min($price_filter_data);
                $this->data['price_max'] = max($price_filter_data);

                if (isset($this->request->get['minprice'])) {
                    $this->data['price_minprice'] = $this->request->get['minprice'];
                } else {
                    $this->data['price_minprice'] = $this->data['price_min'];
                }

                if (isset($this->request->get['maxprice'])) {
                    $this->data['price_maxprice'] = $this->request->get['maxprice'];
                } else {
                    $this->data['price_maxprice'] = $this->data['price_max'];
                }

                if ($this->data['price_min'] != $this->data['price_max']) {
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/price_filter.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/module/price_filter.tpl';
                    } else {
                        $this->template = 'default/template/module/price_filter.tpl';
                    }
                    $this->render();
                }
            }
        }
    }

}

?>
