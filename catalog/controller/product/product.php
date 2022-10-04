<?php

class ControllerProductProduct extends Controller
{
    private $error = array();

    public function getPriceInfo()
    {

        if (isset($this->request->get['x'])) {
            $product_id = (int)$this->request->get['x'];
        } else {
            $product_id = 0;
        }

        $this->data = $this->index($product_id, true);

        $this->log->debug($this->data);

        $this->template = 'blocks/price.tpl';
        $this->response->setOutput($this->render());
    }

    public function getProductsArrayDataJSON()
    {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $json = array();

        if (isset($this->request->post['x'])) {
            $tmp = $this->request->post['x'];
        }

        foreach ($tmp as $id) {
            if ((int)$id) {
                $product_ids[] = (int)$id;
            }
        }

        $results = array();
        foreach ($product_ids as $product_id) {
            $results[$product_id] = $this->model_catalog_product->getProduct($product_id);
        }

        $json = $this->model_catalog_product->prepareProductToArray($results, array(), true);

        $this->response->setOutput(json_encode($json));
    }

    public function getEcommerceInfo()
    {
        $json = array();

        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('catalog/product');
        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            $json['product_id'] = $product_info['product_id'];
            $json['name']       = $product_info['name'];
            $json['currency']   = $this->config->get('config_regional_currency');
            $json['price']      = ($product_info['special']) ? $this->currency->format(
                $product_info['special'],
                '',
                '',
                false
            ) : $this->currency->format($product_info['price'], '', '', false);
            $json['brand']      = $product_info['manufacturer'];
            $json['category']   = $this->model_catalog_product->getGoogleCategoryPath($product_info['product_id']);
            $json['config_vk_pricelist_id'] = $this->config->get('config_vk_pricelist_id');

            $json = array_map('prepareEcommString', $json);
        }

        $this->response->setOutput(json_encode($json));
    }

    public function getDeliveryInfo()
    {
        $this->load->model('catalog/product');
        $this->load->model('tool/simpleapicustom');
        $this->load->model('kp/deliverycounters');

        if (isset($this->request->get['x'])) {
            $product_id = (int)$this->request->get['x'];
        } else {
            $product_id = 0;
        }

        if ($product_id && $product_info = $this->model_catalog_product->getProduct($product_id)) {
            foreach ($this->language->loadRetranslate('product/product') as $translationСode => $translationText) {
                $this->data[$translationСode] = $translationText;
            }

            //Получим город клиента, если задан текущий - переназначим на текущий
            $this->data['delivery_city']['city'] = $this->language->get('default_city_' . $this->config->get('config_country_id'));

            //Это из апишки которая используется в верхнем подборе
            $customer_city = $this->model_tool_simpleapicustom->getAndCheckCurrentCity();

            if (!$customer_city) {
                $customer_city = $this->model_tool_simpleapicustom->getDefaultCustomerCityStructForDeliveryGuessing();
            }

            if (!empty($customer_city) && !empty($customer_city['city'])) {
                $this->data['delivery_city']['city'] = $customer_city['city'];
            }

            $delivery_city_nonmorphed = $this->data['delivery_city']['city'];

            //Сроки доставки
            $this->data['delivery_dates'] = array();
            if ($product_info['stock_dates']) {
                $this->data['delivery_dates']['start'] = date('d.m', strtotime('+'. $product_info['stock_dates']['start'] .' day'));
                $this->data['delivery_dates']['end'] = date('d.m', strtotime('+'. $product_info['stock_dates']['end'] .' day'));
            }

            $cdekDeliveryTerms = array();

            if (($this->data['delivery_city']['city'] != $this->language->get('default_city_' . $this->config->get('config_country_id')) || ($this->config->get('config_warehouse_identifier') != $this->config->get('config_warehouse_identifier_local'))) && $product_info['stock_dates'] && $this->model_tool_simpleapicustom->checkIfUseRUKZBYServices()) {
                if (!empty($customer_city) && !empty($customer_city['id'])) {
                    if ($cdekDeliveryTerms = $this->model_kp_deliverycounters->getCDEKDeliveryTerms($customer_city['id'])) {
                        $this->data['cdek_delivery_dates']['start'] = date('d.m', strtotime('+'. ($product_info['stock_dates']['start'] + $cdekDeliveryTerms['deliveryPeriodMin'] + 1) .' day'));
                        $this->data['cdek_delivery_dates']['end'] = date('d.m', strtotime('+'. ($product_info['stock_dates']['end'] + $cdekDeliveryTerms['deliveryPeriodMax'] + 1) .' day'));
                    }
                }
            }

            if ($product_info['stock_dates'] && $this->model_tool_simpleapicustom->checkIfUseUAServices()) {
                if (!empty($customer_city) && !empty($customer_city['id'])) {
                    if ($npDeliveryTerms = $this->model_kp_deliverycounters->getNovaPoshtaDeliveryTerms($customer_city['id'])) {
                        $this->data['np_delivery_dates']['start'] = date('d.m', strtotime('+'. ($product_info['stock_dates']['start'] + $npDeliveryTerms) .' day'));
                        $this->data['np_delivery_dates']['end'] = date('d.m', strtotime('+'. ($product_info['stock_dates']['end'] + $npDeliveryTerms) .' day'));
                    }
                }

                if ($this->data['delivery_city']['city'] != $this->language->get('default_city_' . $this->config->get('config_country_id')) && $this->config->get('config_delivery_display_logic') == 'v2'){
                    if (!empty($this->data['np_delivery_dates'])){
                        $this->data['delivery_dates']['start']  = $this->data['np_delivery_dates']['start'];
                        $this->data['delivery_dates']['end']    = $this->data['np_delivery_dates']['end'];
                    }
                }
            }

            //По умолчанию "доставка по"
            $this->data['delivery_text'] = $this->data['delivery_to_city_courier'];
            if ($this->config->get('config_warehouse_identifier') != $this->config->get('config_warehouse_identifier_local')) {
                //Если склады отличаются, то тоже доставка курьерской службой
                $this->data['delivery_text'] = $this->data['delivery_to_city_remote'];
            } else {
                //Если склад в стране есть, но доставка в другой город
                if ($this->data['delivery_city']['city'] != $this->language->get('default_city_' . $this->config->get('config_country_id'))) {
                    $this->data['delivery_text'] = $this->data['delivery_to_city_remote'];
                } else {
                    $this->data['delivery_city']['city'] = morphos\Russian\GeographicalNamesInflection::getCase($this->data['delivery_city']['city'], 'дательный');
                }
            }

            $this->data['pickup_text'] = false;
            if ($this->config->get('config_pickup_enable') && $delivery_city_nonmorphed == $this->language->get('default_city_' . $this->config->get('config_country_id')) && $product_info['stock_dates']) {
                $tmp1 = explode(PHP_EOL, $this->config->get('config_pickup_dayoff_' . date('n')));
                $tmp2 = explode(PHP_EOL, $this->config->get('config_pickup_dayoff_' . date('n', strtotime('+1 month'))));
                $dayoffs = array();
                $dayoffs_next = array();

                foreach ($tmp1 as $tmp1_line) {
                    if ((int)trim($tmp1_line)) {
                        $dayoffs[] = (int)trim($tmp1_line);
                    }
                }

                foreach ($tmp2 as $tmp2_line) {
                    if ((int)trim($tmp2_line)) {
                        $dayoffs_next[] = (int)trim($tmp2_line);
                    }
                }

                //Если товар есть на складе в текущей стране
                if ($product_info[$this->config->get('config_warehouse_identifier_local')]) {
                    $pickup_times = $this->config->get('config_pickup_times');
                    $pickup_times = explode(';', $pickup_times);

                    //Проверяем, работает или нет вообще СЕГОДНЯ
                    if (!empty($pickup_times[date('N') - 1]) && $pickup_times[date('N') - 1] != 'false' && !in_array(date('j'), $dayoffs)) {
                        $pickup_times_today = explode(':', $pickup_times[date('N') - 1]);

                        //Сегодня до начала открытия
                        if (date('G') < (int)$pickup_times_today[0]) {
                            $this->data['pickup_text'] = sprintf($this->data['pickup_text_today_from'], $pickup_times_today[0]);
                        }

                        //Сегодня после закрытия
                        if (date('G') >= (int)$pickup_times_today[0] && date('G') < (int)$pickup_times_today[1]) {
                            $this->data['pickup_text'] = sprintf($this->data['pickup_text_today_to'], $pickup_times_today[1]);
                        }

                        if (date('G') >= (int)$pickup_times_today[1]) {
                            //Проверяем, работает ли завтра, например в воскресенье
                            if (date('N') == 7) {
                                $next_day = 1;
                            } else {
                                $next_day = date('N') + 1;
                            }

                            //Если работает завтра
                            if (!empty($pickup_times[$next_day - 1]) && $pickup_times[$next_day - 1] != 'false') {
                                $pickup_times_tomorrow = explode(':', $pickup_times[$next_day - 1]);
                                $this->data['pickup_text'] = sprintf($this->data['pickup_text_tomorrow_from'], $pickup_times_tomorrow[0], $pickup_times_tomorrow[1]);
                            } else {
                                //Завтра не работает, значит открывается с понедельника
                                $pickup_times_on_monday = explode(':', $pickup_times[0]);
                                $this->data['pickup_text'] = sprintf($this->data['pickup_text_from_monday'], $pickup_times_on_monday[0], $pickup_times_on_monday[1]);
                            }
                        }
                    } else {
                        //Не работает, значит сегодня суббота или воскресенье, или выходной день, нужно найти когда откроется
                        if (date('N') == 6 || date('N') == 7) {
                            //Проверим, работает ли в понедельник
                            if (!in_array(date('j', strtotime('+' . (8 - date('N')) . ' day')), $dayoffs)) {
                                $pickup_times_on_monday = explode(':', $pickup_times[0]);
                                $this->data['pickup_text'] = sprintf($this->data['pickup_text_from_monday'], $pickup_times_on_monday[0], $pickup_times_on_monday[1]);
                            }
                        } else {
                            //Проверяем следующие 14 дней, первый день когда откроется - тот и отображаем
                            for ($i=1; $i<=14; $i++) {
                                //Проверяем, работает ли в этот день самовывоз (+$i дней от текущего)
                                if (!empty($pickup_times[date('N', strtotime('+' . $i . ' day')) - 1]) && $pickup_times[date('N', strtotime('+' . $i . ' day')) - 1] != 'false' && !in_array(date('j', strtotime('+' . $i . ' day')), $dayoffs)) {
                                    $pickup_times_on_this_day = explode(':', $pickup_times[date('N', strtotime('+' . $i . ' day')) - 1]);
                                    if ($this->config->get('config_language_id') == 6) {
                                        setlocale(LC_ALL, 'uk_UA.UTF-8');
                                        $dayname = getUkrainianWeekDayDeclenced(date('N', strtotime('+' . $i . ' day')));
                                    } else {
                                        setlocale(LC_ALL, 'ru_RU.UTF-8');
                                        $dayname = morphos\Russian\NounDeclension::getCase(mb_strtolower(strftime('%A', strtotime('+' . $i . ' day'))), 'винительный');
                                    }

                                    if ($i == 1) {
                                        //Завтра
                                        $this->data['pickup_text'] = sprintf($this->data['pickup_text_tomorrow_from'], $dayname . ', ' . (strftime('%d.%m', strtotime('+' . $i . ' day'))), $pickup_times_on_this_day[0], $pickup_times_on_this_day[1]);
                                    } elseif ($i == 2) {
                                        //Послезавтра, пока точно так же как и в любой другой день
                                        $this->data['pickup_text'] = sprintf($this->data['pickup_text_datomorrow_from'], $dayname . ', ' . (strftime('%d.%m', strtotime('+' . $i . ' day'))), $pickup_times_on_this_day[0], $pickup_times_on_this_day[1]);
                                    } else {
                                        //Просто в любой другой день
                                        $this->data['pickup_text'] = sprintf($this->data['pickup_text_dayoff_from'], $dayname . ', ' . (strftime('%d.%m', strtotime('+' . $i . ' day'))), $pickup_times_on_this_day[0], $pickup_times_on_this_day[1]);
                                    }

                                    break;
                                }
                            }
                        }
                    }

                    //Товара нет в наличии в стране, самовывоз с ближайшей рабочей даты (не точно)
                } else {
                }
            }

            //Получим id города клиента, переназначим на текущий, если нужно
            if (!empty($customer_city) && !empty($customer_city['id'])) {
                $this->data['delivery_city']['id'] = $customer_city['id'];
            }

            $this->template = 'blocks/delivery_info';
            if ($this->config->get('config_delivery_display_logic') == 'v2'){
                $this->template = 'blocks/delivery_info2';
            }

            $this->response->setOutput($this->render());
        }
    }

    public function index($product_id = false, $just_price = false)
    {

        $this->language->load('product/product');

        foreach ($this->language->loadRetranslate('product/product') as $translationСode => $translationText) {
            $this->data[$translationСode] = $translationText;
        }

        foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText) {
            $this->data[$translationСode] = $translationText;
        }

        if ($product_id) {
            $this->request->get['product_id'] = $product_id;
            $return = true;
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];
        } else {
            $product_id = (int)$product_id;
        }

        $this->load->model('catalog/product');
        $this->load->model('setting/setting');
        $product_info = $this->model_catalog_product->getProduct($product_id);


            $image_info = $this->model_catalog_product->getProductImageTitleAlt($product_id); // image
            
            if ($product_info && !empty($product_info['is_option_for_product_id'])) {
                $main_virtual_product = $this->model_catalog_product->getProduct($product_info['is_option_for_product_id']);
                
                if ($main_virtual_product) {
                //$this->redirect($this->url->link('product/product', 'product_id=' . $product_info['is_option_for_product_id'], 'SSL'));
                }
            }
            
            
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/js/cloud-zoom-new/cloudzoom.css');
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/js/liFixar/liFixar.css');
            $this->document->addStyle('catalog/view/javascript/countdown/jquery.countdown.css');
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/css/product/product.css');
            $this->document->addScript('catalog/view/theme/' . $this->config->get('config_template') . '/js/cloud-zoom-new/cloudzoom.js');
            $this->document->addScript('catalog/view/theme/' . $this->config->get('config_template') . '/js/liFixar/jquery.liFixar.js');
            $this->document->addScript('catalog/view/javascript/countdown/jquery.countdown.js');
            $this->data['page_type'] = 'product';
            $this->data['breadcrumbs'] = array();
            
            $this->data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/home'),
                'separator' => false
            );
            
            $this->load->model('catalog/category');
            $this->load->model('catalog/manufacturer');
            $this->load->model('catalog/review');
            $this->load->model('tool/image');
            $this->load->model('tool/video');
            
            if (!$just_price) {
                if (!empty($this->request->get['product_id']) && $this->config->get('full_product_path_breadcrumbs') == '1') {
                    $this->load->model('tool/path_manager');
                    unset($this->request->get['path']);
                    $this->request->get['path'] = $this->model_tool_path_manager->getFullProductPath($this->request->get['product_id'], true);
                }
                
                if (isset($this->request->get['path'])) {
                    $path = '';
                    
                    $parts = explode('_', (string)$this->request->get['path']);
                    
                    $category_id = (int)array_pop($parts);
                    
                    foreach ($parts as $path_id) {
                        if (!$path) {
                            $path = $path_id;
                        } else {
                            $path .= '_' . $path_id;
                        }
                        
                        $category_info = $this->model_catalog_category->getCategory($path_id);
                        
                        if ($category_info) {
                            $this->data['breadcrumbs'][] = array(
                                'text'      => $category_info['name'],
                                'href'      => $this->url->link('product/category', 'path=' . $path),
                                'separator' => $this->language->get('text_separator')
                            );
                        }
                    }
                    
                    // Set the last category breadcrumb
                    $category_info = $this->model_catalog_category->getCategory($category_id);
                    if ($category_info) {
                        $url = '';
                        
                        if (isset($this->request->get['sort'])) {
                            $url .= '&sort=' . $this->request->get['sort'];
                        }
                        
                        if (isset($this->request->get['order'])) {
                            $url .= '&order=' . $this->request->get['order'];
                        }
                        
                        if (isset($this->request->get['page'])) {
                            $url .= '&page=' . $this->request->get['page'];
                        }
                        
                        if (isset($this->request->get['limit'])) {
                            $url .= '&limit=' . $this->request->get['limit'];
                        }
                        
                        $this->data['breadcrumbs'][] = array(
                            'text'      => $category_info['name'],
                            'href'      => $this->url->link(
                                'product/category',
                                'path=' . $this->request->get['path'] . $url
                            ),
                            'separator' => $this->language->get('text_separator')
                        );
                        
                        $this->data['current_category'] = array(
                            'name'       => mb_strtolower($category_info['name']),
                            'all_prefix' => trim($category_info['all_prefix']),
                            'href'       => $this->url->link(
                                'product/category',
                                'path=' . $this->request->get['path'] . $url
                            )
                        );
                    }
                }
                
                if (isset($this->request->get['manufacturer_id']) || ($product_info && $product_info['manufacturer_id'] > 0)) {
                    $url = '';
                    
                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }
                    
                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }
                    
                    if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                    }
                    
                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }
                    
                    if (isset($this->request->get['manufacturer_id'])) {
                        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
                    } else {
                        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
                    }
                    
                    if (isset($this->request->get['path'])) {
                        if (isset($manufacturer_info['manufacturer_id'])) {
                            $mhref = $this->url->link(
                                'product/category',
                                'path=' . $this->request->get['path'] . '&manufacturer_id=' . $manufacturer_info['manufacturer_id']
                            );
                        } else {
                            $mhref = $this->url->link('product/category', 'path=' . $this->request->get['path']);
                        }
                    } else {
                        if (isset($manufacturer_info['manufacturer_id'])) {
                            $mhref = $this->url->link(
                                'product/manufacturer/info',
                                'manufacturer_id=' . $manufacturer_info['manufacturer_id'] . $url
                            );
                        } else {
                            $mref = false;
                        }
                    }
                    
                    $mbname = '';
                    if (!empty($category_info['name'])) {
                        $mbname = $category_info['name'];
                    }

                    if (!empty($manufacturer_info['name'])) {
                        $mbname = trim($mbname . ' ' . $manufacturer_info['name']);
                    }
                    
                    
                    if ($manufacturer_info && isset($mhref) && isset($mbname)) {
                        $this->data['category_manufacturer_info'] = array(
                            'text'      => $mbname,
                            'href'      => $mhref,
                            'separator' => $this->language->get('text_separator')
                        );
                    }
                }
            }
            
            //  $this->log->debug($this->data['breadcrumbs']);
            
            if ($product_info) {
                $this->language->load('module/set');
                $this->data['tab_sets'] = $this->language->get('tab_sets');
                if ($this->config->get('set_place_product_page') && $this->config->get('set_place_product_page') == 'before_tabs') {
                    $this->data['set_place'] = 'before_tabs';
                } else {
                    $this->data['set_place'] = 'in_tabs';
                }
                $this->load->model('catalog/set');
                
                
                $this->data['count_set'] = count($this->model_catalog_set->getSetsProduct($this->request->get['product_id']));
                $this->data['is_set'] = $this->model_catalog_set->isSetExist($this->request->get['product_id']);
                
                if ($this->data['is_set']) {
                    $this->data['set_products'] = $this->productload($this->data['is_set']);
                    // Сейчас будем искать комплеты, на большее кол-во персон
                    $personsArray = array();
                    // $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
                    $additionalSet = $this->model_catalog_set->getPersonsSetId($this->data['is_set']);
                    if ($additionalSet) {
                        foreach ($additionalSet as $k => $v) {
                            // TODO
                            $personsArray[] = array(
                                'persons' => $v['count_persone'],
                                'url'     => $this->url->link('product/product', 'product_id=' . $v['product_id'])
                            );
                        }
                    }
                    
                    $this->data['personsArray'] = $personsArray;
                }
                
                $this->data['active_coupon'] = $this->model_catalog_product->recalculateCouponPrice($product_info, $this->model_catalog_product->getProductActiveCoupons($product_id));
                $this->data['active_action'] = $this->model_catalog_product->getProductActiveAction($product_id);
                
                if ($this->data['active_action']) {
                    $this->data['active_action']['href'] = $this->url->link('information/actions', 'actions_id=' . $this->data['active_action']['actions_id']);
                    $this->data['active_action']['date_end'] = date('Y/m/d', $this->data['active_action']['date_end']);
                }
                
                if (!$just_price) {
                    if ($product_info['stock_status_id'] != $this->config->get('config_not_in_stock_status_id')) {
                        $this->load->model('catalog/viewed');
                        $this->model_catalog_viewed->addToViewed($product_id);
                    }
                    
                    $this->model_catalog_product->updateViewed($this->request->get['product_id']);
                    $this->load->model('catalog/review');
                    
                    $this->data['text_on'] = $this->language->get('text_on');
                    $this->data['text_no_reviews'] = $this->language->get('text_no_reviews');
                    $this->data['entry_good'] = $this->language->get('entry_good');
                    $this->data['entry_bads'] = $this->language->get('entry_bads');
                    $this->data['text_bads'] = $this->language->get('text_bads');
                    $this->data['text_good'] = $this->language->get('text_good');
                    $this->data['text_answer'] = $this->language->get('text_answer');
                    $this->data['text_comment'] = $this->language->get('text_comment');
                    
                    if (isset($this->request->get['page'])) {
                        $page = $this->request->get['page'];
                    } else {
                        $page = 1;
                    }
                    
                    $this->data['reviews_array'] = array();
                    
                    $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
                    $this->data['review_total'] = $review_total;
                    
                    $results = $this->model_catalog_review->getReviewsByProductId(
                        $this->request->get['product_id'],
                        ($page - 1) * 5,
                        50
                    );
                    
                    foreach ($results as $result) {
                        if ($result['html_status'] == 1) {
                            $this->data['reviews_array'][] = array(
                                'product_id' => $result['product_id'],
                                'review_id'  => $result['review_id'],
                                'author'     => $result['author'],
                                'text'       => html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),
                                'good'       => html_entity_decode($result['good'], ENT_QUOTES, 'UTF-8'),
                                'bads'       => html_entity_decode($result['bads'], ENT_QUOTES, 'UTF-8'),
                                'answer'     => html_entity_decode($result['answer'], ENT_QUOTES, 'UTF-8'),
                                'purchased'  => $result['purchased'],
                                'addimage'   => $result['addimage'],
                                'rating'     => (int)$result['rating'],
                                'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
                                'date_added' => date(
                                    $this->language->get('date_format_short'),
                                    strtotime($result['date_added'])
                                )
                            );
                        } else {
                            $this->data['reviews_array'][] = array(
                                'product_id' => $result['product_id'],
                                'review_id'  => $result['review_id'],
                                'author'     => $result['author'],
                                'text'       => $result['text'],
                                'good'       => $result['good'],
                                'bads'       => $result['bads'],
                                'answer'     => $result['answer'],
                                'purchased'  => $result['purchased'],
                                'addimage'   => $result['addimage'],
                                'rating'     => (int)$result['rating'],
                                'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
                                'date_added' => date(
                                    $this->language->get('date_format_short'),
                                    strtotime($result['date_added'])
                                )
                            );
                        }
                    }
                    
                    $pagination = new Pagination();
                    $pagination->total = $review_total;
                    $pagination->page = $page;
                    $pagination->limit = 50;
                    $pagination->text = $this->language->get('text_pagination');
                    $pagination->url = $this->url->link(
                        'product/product/review',
                        'product_id=' . $this->request->get['product_id'] . '&page={page}'
                    );
                    
                    $this->data['pagination'] = $pagination->render();
                    $this->model_catalog_product->catchAlsoViewed($this->request->get['product_id']);
                    
                    $this->load->model('catalog/superstat');
                    $this->model_catalog_superstat->addToSuperStat('p', $this->request->get['product_id']);
                    
                    if ($product_info['manufacturer_id'] > 0) {
                        $this->model_catalog_superstat->addToSuperStat('m', $product_info['manufacturer_id']);
                    }
                    
                    
                    $url = '';
                    
                    if (isset($this->request->get['path'])) {
                        $url .= '&path=' . $this->request->get['path'];
                    }
                    
                    if (isset($this->request->get['filter'])) {
                        $url .= '&filter=' . $this->request->get['filter'];
                    }
                    
                    if (isset($this->request->get['manufacturer_id'])) {
                        $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
                    }
                    
                    if (isset($this->request->get['search'])) {
                        $url .= '&search=' . $this->request->get['search'];
                    }
                    
                    if (isset($this->request->get['tag'])) {
                        $url .= '&tag=' . $this->request->get['tag'];
                    }
                    
                    if (isset($this->request->get['description'])) {
                        $url .= '&description=' . $this->request->get['description'];
                    }
                    
                    if (isset($this->request->get['category_id'])) {
                        $url .= '&category_id=' . $this->request->get['category_id'];
                    }
                    
                    if (isset($this->request->get['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->get['sub_category'];
                    }
                    
                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }
                    
                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }
                    
                    if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                    }
                    
                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }
                    
                    /*
                        $this->data['breadcrumbs'][] = array(
                        'text'      => $product_info['name'],
                        'href'      => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
                        'separator' => $this->language->get('text_separator')
                        );
                    */
                        ($product_info['seo_title'] == '') ? $this->document->setTitle($product_info['name']) : $this->document->setTitle($product_info['seo_title']);
                        $this->document->setDescription($product_info['meta_description']);
                        $this->document->setKeywords($product_info['meta_keyword']);

                        if ($this->config->get('hb_snippets_og_enable') == '1') {
                            $hb_snippets_ogp = $this->config->get('hb_snippets_ogp');
                            if (strlen($hb_snippets_ogp) > 4) {
                                $ogp_name = $product_info['name'];
                                $ogp_brand = $product_info['manufacturer'];
                                $ogp_model = $product_info['model'];
                                if ((float)$product_info['special']) {
                                    $ogp_price = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                                } else {
                                    $ogp_price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                                }

                                $hb_snippets_ogp = str_replace('{name}', $ogp_name, $hb_snippets_ogp);
                                $hb_snippets_ogp = str_replace('{model}', $ogp_model, $hb_snippets_ogp);
                                $hb_snippets_ogp = str_replace('{brand}', $ogp_brand, $hb_snippets_ogp);
                                $hb_snippets_ogp = str_replace('{price}', $ogp_price, $hb_snippets_ogp);
                            } else {
                                $hb_snippets_ogp = $product_info['name'];
                            }

                            $this->document->addOpenGraph('og:title', $hb_snippets_ogp);
                            $this->document->addOpenGraph('og:type', 'website');
                            $this->document->addOpenGraph('og:site_name', $this->config->get('config_name'));
                        // $this->document->addOpenGraph('og:image', HTTP_SERVER . 'image/' . $product_info['image']);
                            $this->document->addOpenGraph('og:url', $this->url->link('product/product', 'product_id=' . $product_id));
                            $this->document->addOpenGraph('og:description', $product_info['meta_description']);
                        //$this->document->addOpenGraph('article:publisher', $this->config->get('hb_snippets_fb_page'));
                        }

                        if ($product_info['stock_product_id']) {
                            $this->document->setNoindex(true);
                            $this->document->addLink($this->url->link(
                                'product/product',
                                'product_id=' . $product_info['stock_product_id']
                            ), 'canonical');
                        } else {
                            $this->document->addLink($this->url->link(
                                'product/product',
                                'product_id=' . $this->request->get['product_id']
                            ), 'canonical');
                        }

                        $this->data['this_href'] = $this->url->link('product/product', 'product_id=' . $this->request->get['product_id']);


                        $this->document->addRobotsMeta('index, follow');

                    $this->data['heading_title'] = ($product_info['seo_h1'] <> '') ? $product_info['seo_h1'] : $product_info['name']; //ето
                    
                    $this->data['img_alt'] = (!is_null($image_info['alt']))?$image_info['alt']: $product_info['name'];
                    $this->data['img_title'] = (!is_null($image_info['title']))?$image_info['title']: $product_info['name'];
                }
                
                $this->data['text_select'] = $this->language->get('text_select');
                $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
                $this->data['text_model'] = $this->language->get('text_model');
                $this->data['text_sku'] = $this->language->get('text_sku');
                $this->data['text_reward'] = $this->language->get('text_reward');
                $this->data['text_points'] = $this->language->get('text_points');
                $this->data['text_discount'] = $this->language->get('text_discount');
                $this->data['text_stock'] = $this->language->get('text_stock');
                $this->data['text_price'] = $this->language->get('text_price');
                $this->data['text_tax'] = $this->language->get('text_tax');
                $this->data['text_discount'] = $this->language->get('text_discount');
                $this->data['text_option'] = $this->language->get('text_option');
                $this->data['text_qty'] = $this->language->get('text_qty');
                $this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
                $this->data['text_or'] = $this->language->get('text_or');
                $this->data['text_write'] = $this->language->get('text_write');
                $this->data['text_note'] = $this->language->get('text_note');
                $this->data['text_share'] = $this->language->get('text_share');
                $this->data['text_wait'] = $this->language->get('text_wait');
                $this->data['text_tags'] = $this->language->get('text_tags');
                
                $this->data['text_no_image'] = $this->language->get('no_image');
                $this->data['text_price_all_users'] = $this->language->get('price_all_users');
                $this->data['text_count_limited'] = $this->language->get('count_limited');
                $this->data['text_show_more'] = $this->language->get('show_more');
                $this->data['text_detail'] = $this->language->get('detail');
                $this->data['text_details'] = $this->language->get('details');
                $this->data['text_video_obzor'] = $this->language->get('video_obzor');
                $this->data['text_all_collection'] = $this->language->get('all_collection');
                
                $this->data['text_brand'] = $this->language->get('brand');
                $this->data['text_country'] = $this->language->get('country');
                $this->data['text_code'] = $this->language->get('text_code');
                $this->data['text_collection'] = $this->language->get('collection');
                
                $this->data['text_complect_discount'] = $this->language->get('complect_discount');
                $this->data['text_buy'] = $this->language->get('buy');
                
                $this->data['entry_name'] = $this->language->get('entry_name');
                $this->data['entry_review'] = $this->language->get('entry_review');
                $this->data['entry_rating'] = $this->language->get('entry_rating');
                $this->data['entry_good'] = $this->language->get('entry_good');
                $this->data['entry_bad'] = $this->language->get('entry_bad');
                $this->data['entry_captcha'] = $this->language->get('entry_captcha');
                
                $this->data['button_cart'] = $this->language->get('button_cart');
                $this->data['button_wishlist'] = $this->language->get('button_wishlist');
                $this->data['button_compare'] = $this->language->get('button_compare');
                $this->data['button_upload'] = $this->language->get('button_upload');
                $this->data['button_continue'] = $this->language->get('button_continue');
                
                $this->data['tab_description'] = $this->language->get('tab_description');
                $this->data['tab_attribute'] = $this->language->get('tab_attribute');
                $this->data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
                $this->data['tab_related'] = $this->language->get('tab_related');
                
                $this->data['product_id']   = (int)$this->request->get['product_id'];
                $this->data['manufacturer'] = $manufacturer_info?(trim($product_info['manufacturer'])):'';
                $this->data['model']        = $product_info['model'];
                $this->data['sku']          = $product_info['sku'];
                $this->data['points']       = $this->currency->formatBonus($product_info['reward'], true);
                
                $this->data['models'] = array();
                
                //available models
                $this->data['models'][] = str_replace(' ', '', $product_info['model']);
                $this->data['models'][] = str_replace('-', '', $product_info['model']);
                $this->data['models'][] = str_replace('.', '', $product_info['model']);
                $this->data['models'][] = str_replace('.', ' ', $product_info['model']);
                $this->data['models'][] = str_replace('/', '', $product_info['model']);
                $this->data['models'][] = preg_replace("([^0-9])", "", $product_info['model']);
                $this->data['models'][] = $this->model_catalog_product->getProductDeName($this->request->get['product_id']);
                $this->data['models'][] = trim($product_info['ean']);
                
                $this->data['ean'] = $product_info['ean'];
                
                $this->data['enable_found_cheaper'] = $product_info['has_rrp'] && ($product_info['manufacturer_id'] == 201);
                
                $this->data['has_labels'] = array();
                
                if (!$just_price) {
                    /* MARKDOWN */
                    $this->data['is_markdown'] = $product_info['is_markdown'];
                    
                    if ($product_info['is_markdown']) {
                        $this->data['markdown_appearance']   = html_entity_decode($product_info['markdown_appearance'], ENT_QUOTES, 'UTF-8');
                        $this->data['markdown_condition']    = html_entity_decode($product_info['markdown_condition'], ENT_QUOTES, 'UTF-8');
                        $this->data['markdown_pack']         = html_entity_decode($product_info['markdown_pack'], ENT_QUOTES, 'UTF-8');
                        $this->data['markdown_equipment']    = html_entity_decode($product_info['markdown_equipment'], ENT_QUOTES, 'UTF-8');
                        
                        if ($product_info['markdown_product_id']) {
                            $markdown_product = $this->model_catalog_product->getProduct($product_info['markdown_product_id']);
                            
                            if ($markdown_product['image']) {
                                $image = $this->model_tool_image->resize($markdown_product['image'], 100, 100);
                            } else {
                                $image = false;
                            }
                            
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                $price = $this->currency->format($this->tax->calculate($markdown_product['price'], $markdown_product['tax_class_id'], $this->config->get('config_tax')));
                            } else {
                                $price = false;
                            }
                            
                            if ((float)$markdown_product['special']) {
                                $special = $this->currency->format($this->tax->calculate(
                                    $markdown_product['special'],
                                    $markdown_product['tax_class_id'],
                                    $this->config->get('config_tax')
                                ));
                            } else {
                                $special = false;
                            }
                            
                            if ($this->config->get('config_review_status')) {
                                $rating = (int)$markdown_product['rating'];
                            } else {
                                $rating = false;
                            }
                            
                            if ($markdown_product['minimum']) {
                                $p_minimum = $markdown_product['minimum'];
                            } else {
                                $p_minimum = 1;
                            }
                            
                            $stock_data = $this->model_catalog_product->parseProductStockData($markdown_product);
                            
                            
                            $this->data['markdown_product'] = array(
                                'product_id'            => $markdown_product['product_id'],
                                'stock_type'            => $stock_data['stock_type'],
                                'stock_text'            => $result['stock_text'],
                                'show_delivery_terms'   => $stock_data['show_delivery_terms'],
                                'thumb'                 => $image,
                                'name'                  => $markdown_product['name'],
                                'new'                   => $markdown_product['new'],
                                'description'           => utf8_substr(strip_tags(html_entity_decode(
                                    $markdown_product['description'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                )), 0, 100) . '..',
                                'price'                 => $price,
                                'minimum'               => $p_minimum,
                                'text_minimum'          => sprintf($this->language->get('text_minimum'), $p_minimum),
                                'special'               => $special,
                                'rating'                => $rating,
                                'count_reviews'         => $markdown_product['reviews'],
                                'sku'                   => $markdown_product['model']?$markdown_product['model']:$markdown_product['sku'],
                                'saving'                => round((($markdown_product['price'] - $markdown_product['special'])/($markdown_product['price'] + 0.01))*100, 0),
                                'can_not_buy'           => ($markdown_product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
                                'need_ask_about_stock'  => ($markdown_product['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')),
                                'stock_status'          => $markdown_product['stock_status'],
                                'reviews'               => sprintf($this->language->get('text_reviews'), (int)$markdown_product['reviews']),
                                'href'                  => $this->url->link('product/product', 'product_id=' . $markdown_product['product_id'])
                            );
                        }
                    }
                    
                    $this->data['markdowned_products'] = array();
                    if ($markdown_products = $this->model_catalog_product->getProductMarkdowns($product_info['product_id'])) {
                        foreach ($markdown_products as $markdown_product) {
                            if ($markdown_product['image']) {
                                $image = $this->model_tool_image->resize($markdown_product['image'], 100, 100);
                            } else {
                                $image = false;
                            }
                            
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                $price = $this->currency->format($this->tax->calculate($markdown_product['price'], $markdown_product['tax_class_id'], $this->config->get('config_tax')));
                            } else {
                                $price = false;
                            }
                            
                            if ((float)$markdown_product['special']) {
                                $special = $this->currency->format($this->tax->calculate(
                                    $markdown_product['special'],
                                    $markdown_product['tax_class_id'],
                                    $this->config->get('config_tax')
                                ));
                            } else {
                                $special = false;
                            }
                            
                            if ($this->config->get('config_review_status')) {
                                $rating = (int)$markdown_product['rating'];
                            } else {
                                $rating = false;
                            }
                            
                            if ($markdown_product['minimum']) {
                                $p_minimum = $markdown_product['minimum'];
                            } else {
                                $p_minimum = 1;
                            }
                            
                            $stock_data = $this->model_catalog_product->parseProductStockData($markdown_product);
                            
                            
                            $this->data['markdowned_products'][] = array(
                                'product_id'            => $markdown_product['product_id'],
                                'markdown_appearance'   => html_entity_decode($markdown_product['markdown_appearance'], ENT_QUOTES, 'UTF-8'),
                                'markdown_condition'    => html_entity_decode($markdown_product['markdown_condition'], ENT_QUOTES, 'UTF-8'),
                                'markdown_pack'         => html_entity_decode($markdown_product['markdown_pack'], ENT_QUOTES, 'UTF-8'),
                                'markdown_equipment'    => html_entity_decode($markdown_product['markdown_equipment'], ENT_QUOTES, 'UTF-8'),
                                'stock_type'            => $stock_data['stock_type'],
                                'stock_text'            => $markdown_product['stock_text'],
                                'show_delivery_terms'   => $stock_data['show_delivery_terms'],
                                'thumb'                 => $image,
                                'name'                  => $markdown_product['name'],
                                'description'           => utf8_substr(strip_tags(html_entity_decode(
                                    $markdown_product['description'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                )), 0, 100) . '..',
                                'price'                 => $price,
                                'minimum'               => $p_minimum,
                                'text_minimum'          => sprintf($this->language->get('text_minimum'), $p_minimum),
                                'special'               => $special,
                                'rating'                => $rating,
                                'count_reviews'         => $markdown_product['reviews'],
                                'sku'                   => $markdown_product['model']?$markdown_product['model']:$markdown_product['sku'],
                                'saving'                => round((($markdown_product['price'] - $markdown_product['special'])/($markdown_product['price'] + 0.01))*100, 0),
                                'can_not_buy'           => ($markdown_product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
                                'need_ask_about_stock'  => ($markdown_product['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')),
                                'stock_status'          => $markdown_product['stock_status'],
                                'reviews'               => sprintf($this->language->get('text_reviews'), (int)$markdown_product['reviews']),
                                'href'                  => $this->url->link('product/product', 'product_id=' . $markdown_product['product_id'])
                            );
                        }
                    }
                    
                    $this->data['models'] = array_unique($this->data['models']);
                    
                    foreach ($this->data['models'] as $key => $model) {
                        if ($model == $product_info['model']) {
                            unset($this->data['models'][$key]);
                        }
                    }
                }
                
                $this->data['location'] = (strpos(
                    $product_info['location'],
                    'certificate'
                ) === false) ? $product_info['location'] : false;
                
                /*
                    if ($product_info['quantity'] <= 0) {
                    $this->data['stock'] = $product_info['stock_status'];
                    } elseif ($this->config->get('config_stock_display')) {
                    $this->data['stock'] = $product_info['quantity'];
                    } else {
                    $this->data['stock'] = $this->language->get('text_instock');
                    }
                */

                //есть в наличии в данной стране ЛОГИКА ПЕРЕНАЗНАЧЕНА
                    if ($product_info[$this->config->get('config_warehouse_identifier')]) {
                        if (in_array($this->config->get('config_store_id'), array(0, 1))) {
                            $this->data['stock_type'] = 'in_stock_in_country';
                        //Белоруссия
                        } elseif (in_array($this->config->get('config_store_id'), array(5))) {
                            $this->data['stock_type'] = 'in_stock_in_moscow_for_by';
                        //Козохстон
                        } else {
                            $this->data['stock_type'] = 'in_stock_in_moscow_for_kzby';
                        }

                    //есть на складе в Москве
                    } elseif ($product_info['quantity_stockM']) {
                        if ($this->config->get('config_store_id') == 0) {
                            $this->data['stock_type'] = 'in_stock_in_central_msk';
                        } else {
                            $this->data['stock_type'] = 'in_stock_in_central';
                        }

                    //есть в наличии на центральном складе
                    } elseif ($product_info['quantity_stock']) {
                        $this->data['stock_type'] = 'in_stock_in_central';

                    //нету в наличии физически нигде, но статус в наличии
                    } elseif ($product_info['stock_status_id'] == $this->config->get('config_stock_status_id')) {
                        $this->data['stock_type'] = 'supplier_has';

                    //стоит статус "уточните наличие"
                    } elseif ($product_info['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')) {
                        $this->data['stock_type'] = 'need_ask_about_stock';

                    //нету нигде, статус нет у поставщика
                    } elseif ($product_info['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')) {
                        $this->data['stock_type'] = 'supplier_has_no_can_not_buy';

                    //хер пойми что за статус, какая-то непонятная ситуация, но отдать что-то надо
                    } else {
                        $this->data['stock_type'] = 'shit_knows_this_status:' . $product_info['stock_status_id'] . ':' . $product_info['stock_status'];
                    }

                    $this->data['stock_type_one_more_for_overload'] = $this->data['stock_type'];

                //Условие не показывать сроки доставки если нет на складе 06.06.2019

                    $this->data['show_delivery_terms'] = true;
                    if (!$product_info['quantity_stockM'] && !$product_info['quantity_stock'] && !$product_info['quantity_stockK']) {
                        if (!in_array($this->data['stock_type'], array('supplier_has', 'supplier_has', 'need_ask_about_stock', 'supplier_has_no_can_not_buy', 'shit_knows_this_status:' . $product_info['stock_status_id'] . ':' . $product_info['stock_status']))) {
                            $this->data['show_delivery_terms'] = false;
                        }
                    }

                    if (!$this->config->get('config_delivery_outstock_enable') && !$product[$this->config->get('config_warehouse_identifier')]) {
                        $this->data['show_delivery_terms'] = false;
                    }

                    $this->data['stock_text']       = $product_info['stock_text'];

                    $this->data['stock']            = $product_info['stock_status'];
                    $this->data['stock_status_id']  = $product_info['stock_status_id'];
                    $this->data['can_not_buy']      = ($product_info['stock_status_id'] == $this->config->get('config_not_in_stock_status_id'));
                    $this->data['stock_color']      = ($product_info['stock_status_id'] == $this->config->get('config_stock_status_id')) ? '#4C6600' : '#BA0000';

                    if ($this->data['can_not_buy']) {
                        $this->data['rees46_is_available'] = 0;
                    } else {
                        $this->data['rees46_is_available'] = $product_info['quantity'];
                    }



                    if (!$just_price) {
                        if ($manufacturer_info) {
                            $this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);

                    //overload location
                            if (isset($manufacturer_info['location']) && $manufacturer_info['location']) {
                                $this->data['location'] = (strpos(
                                    $product_info['location'],
                                    'certificate'
                                ) === false) ? $manufacturer_info['location'] : false;
                            }

                            $this->data['show_manufacturer'] = ($manufacturer_info && isset($manufacturer_info['sort_order']) && $manufacturer_info['sort_order'] != '-1');

                            $this->data['manufacturers_img'] = $this->model_tool_image->resize($manufacturer_info['image'], 300, 100);
                            $this->data['manufacturers_img_260'] = $this->model_tool_image->resize($manufacturer_info['image'], 260, 90);
                        }

                        if ($product_info['image']) {
                            $this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                            $this->data['popup_ohuevshiy'] = $this->model_tool_image->resize($product_info['image'], 1000, 1000);
                        } else {
                            $this->data['popup'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                            $this->data['popup_ohuevshiy'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 1000, 1000);
                        }

                        if ($product_info['image']) {
                            $this->data['second_popup'] = $this->model_tool_image->resize($product_info['image'], 300, 300);
                        } else {
                            $this->data['second_popup'] = $this->model_tool_image->resize(
                                $this->config->get('config_noimage'),
                                300,
                                300
                            );
                        }

                        if ($product_info['image']) {
                            $this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                        } else {
                            $this->data['thumb'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                        }

                        if ($product_info['image']) {
                            $this->data['smallimg'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
                        } else {
                            $this->data['smallimg'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
                        }


                        $this->data['images'] = array();

                        $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

                        foreach ($results as $result) {
                            $this->data['images'][] = array(
                                'popup'  => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
                                'popup_ohuevshiy'  => $this->model_tool_image->resize($result['image'], 1000, 1000),
                                'middle' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
                                'thumb'  => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
                            );
                        }


                        $this->data['videos'] = array();

                        $results = $this->model_catalog_product->getProductVideos($this->request->get['product_id']);

                        foreach ($results as $result) {
                            $this->data['videos'][] = array(
                                'thumb'  => $this->model_tool_image->resize($result['image'], 700, 400),
                                'title'  => $result['title'],
                                'video'  => $this->model_tool_video->getPath($result['video'])
                            );
                        }


                        $this->load->model('catalog/information');
                        $delivery_full = $this->model_catalog_information->getInformation(31);
                        $this->data['delivery_info'] = isset($delivery_full['description'])?html_entity_decode($delivery_full['description'], ENT_QUOTES, 'UTF-8'):'';

                        $this->data['youtubes'] = false;
                        $this->data['videoInt'] = 0;

                        if ($product_info['youtube']) {
                            $this->data['youtubes'] = explode(',', $product_info['youtube']);
                            $this->data['videoInt'] = 1;
                        }

                        $this->data['bought_for_week'] = (int)(($product_info['bought_for_month'] > 0) ? ($product_info['bought_for_month'] * 3 + $product_id % 4) : false);

                        if ($this->data['bought_for_week']) {
                            if ($this->config->get('config_language_id') == 6) {
                                if ($this->data['bought_for_week'] == 1) {
                                    $this->data['bought_for_week'] = sprintf($this->language->get('text_bought_for_week'), $this->data['bought_for_week'], $this->language->get('text_one_people'));
                                } else {
                                    $this->data['bought_for_week'] = sprintf($this->language->get('text_bought_for_week'), $this->data['bought_for_week'], $this->language->get('text_people'));
                                }
                            } else {
                                $this->data['bought_for_week'] = sprintf($this->language->get('text_bought_for_week'), $this->data['bought_for_week'], morphos\Russian\NounPluralization::pluralize($this->data['bought_for_week'], $this->language->get('text_people')));
                            }
                        } else {
                            $this->data['bought_for_week'] = false;
                        }

                        $this->data['picview'] = $this->url->link('product/picview', 'product_id=' . $product_id);
                    }

                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        if ($product_info['price_recommend']) {
                            $this->data['price_recommend'] = $this->currency->format($this->tax->calculate(
                                $product_info['price_recommend'],
                                $product_info['tax_class_id'],
                                $this->config->get('config_tax')
                            ));
                        } else {
                            $this->data['price_recommend'] = false;
                        }
                    } else {
                        $this->data['price'] = false;
                        $this->data['price_recommend'] = false;
                    }


                //PRICE_NATIONAL
                    if ($product_info['price_national'] && $product_info['price_national'] > 0 && $product_info['currency'] == $this->currency->getCode()) {
                        $this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price_national'], $product_info['tax_class_id'], $this->config->get('config_tax')), $product_info['currency'], 1);
                    }

                    if ((float)$product_info['special']) {
                        $this->data['special'] = $this->currency->format($this->tax->calculate(
                            $product_info['special'],
                            $product_info['tax_class_id'],
                            $this->config->get('config_tax')
                        ));
                        $this->data['saving'] = round(((preg_replace(
                            "([^0-9])",
                            "",
                            $this->data['price']
                        ) - preg_replace(
                            "([^0-9])",
                            "",
                            $this->data['special']
                        )) / (preg_replace(
                            "([^0-9])",
                            "",
                            $this->data['price']
                        ) + 0.01)) * 100, 0);
                    } else {
                        $this->data['special'] = false;
                        $this->data['saving'] = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
                    } else {
                        $this->data['tax'] = false;
                    }

                    $discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

                    $this->data['discounts'] = array();

                    foreach ($discounts as $discount) {
                        $this->data['discounts'][] = array(
                            'quantity' => $discount['quantity'],
                            'price'    => $this->currency->format($this->tax->calculate(
                                $discount['price'],
                                $product_info['tax_class_id'],
                                $this->config->get('config_tax')
                            ))
                        );
                    }

                    $free_delivery = false;
                    $final_price = preg_replace("([^0-9])", "", $this->data['price']);
                    if ($product_info['special']) {
                        $final_price = preg_replace("([^0-9])", "", $this->data['special']);
                    }

                    $shippingSettings = $this->config->get('dostavkaplus_module');


                    if ($this->config->get('config_country_id') == 176) {
                        $freeDelivery = getFreeDeliveryInfo($shippingSettings[1]);

                        if ((int)$final_price >= $freeDelivery) {
                            $free_delivery = 'moscow';
                        }

                        $freeDelivery = getFreeDeliveryInfo($shippingSettings[6]);

                        if ((int)$final_price >= $freeDelivery) {
                            $free_delivery = 'russia';
                        }
                    }

                    if ($this->config->get('config_country_id') == 220) {
                        $freeDelivery = getFreeDeliveryInfo($shippingSettings[2]);

                        if ((int)$final_price >= $freeDelivery) {
                            $free_delivery = 'kyiv';
                        }

                        $freeDelivery = getFreeDeliveryInfo($shippingSettings[3]);

                        if ((int)$final_price >= $freeDelivery) {
                            $free_delivery = 'ukraine';
                        }
                    }

                    $this->data['free_delivery'] = $free_delivery;

                    /* Варианты */
                    $variants = $this->model_catalog_product->getProductVariants($this->request->get['product_id']);
                    $this->data['variants'] = $this->model_catalog_product->prepareProductToArray($variants);

                    /*Additional offer*/
                    $additional_offers_results = $this->model_catalog_product->getProductAdditionalOffer($this->request->get['product_id']);

                    $this->data['additional_offers'] = array();

                    if (count($additional_offers_results) > 0) {
                        $this->document->addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
                        $this->document->addScript('catalog/view/javascript/countdown/jquery.countdown-' . $this->language->get('code') . '.js');
                        $this->document->addStyle('catalog/view/javascript/countdown/jquery.countdown.css');
                    }

                    foreach ($additional_offers_results as $additional_offer) {
                        $ao_product = $this->model_catalog_product->getProduct($additional_offer['ao_product_id']);
                        $ao_product_parent = $this->model_catalog_product->getProduct($additional_offer['product_id']);

                    //Если есть картинка фиксированная, то используем ее
                        if ($additional_offer['image'] && $additional_offer['image'] != 'no_image.jpg') {
                            $ao_image = $this->model_tool_image->resize($additional_offer['image'], 100, 100);
                        } else {
                            if ($ao_product['image']) {
                                $ao_image = $this->model_tool_image->resize($ao_product['image'], 100, 100);
                            } else {
                                $ao_image = $this->model_tool_image->resize($this->config->get('config_noimage'), 100, 100);
                            }
                        }

                    //Цена. Если есть фиксированная цена, то мы берем процент
                        if ($additional_offer['price'] && $additional_offer['price'] > 0) {
                            $ao_price = $this->currency->format($additional_offer['price'] * $additional_offer['quantity']);
                            $ao_price_numeric = ($additional_offer['price'] * $additional_offer['quantity']);
                        } elseif ($additional_offer['percent'] == 100) {
                            $ao_price = $this->language->get('text_present_ao');
                            $ao_price_numeric = 0;
                        } else {
                            if ($ao_product['special'] && $ao_product['special'] > 0) {
                                $ao_price = $this->currency->format(($ao_product['special'] - ($ao_product['special'] * $additional_offer['percent'] / 100)) * $additional_offer['quantity']);
                                $ao_price_numeric = ($ao_product['special'] - ($ao_product['special'] * $additional_offer['percent'] / 100)) * $additional_offer['quantity'];
                            } else {
                                $ao_price = $this->currency->format(($ao_product['price'] - ($ao_product['price'] * $additional_offer['percent'] / 100)) * $additional_offer['quantity']);
                                $ao_price_numeric = ($ao_product['price'] - ($ao_product['price'] * $additional_offer['percent'] / 100)) * $additional_offer['quantity'];
                            }
                        }

                        if ($ao_product['special'] && $ao_product['special'] > 0) {
                            $ao_real_price = $this->currency->format($ao_product['special'] * $additional_offer['quantity']);
                            $ao_real_price_numeric = ($ao_product['special'] * $additional_offer['quantity']);
                        } else {
                            $ao_real_price = $this->currency->format($ao_product['price'] * $additional_offer['quantity']);
                            $ao_real_price_numeric = ($ao_product['price'] * $additional_offer['quantity']);
                        }

                    //Цена товара - спецпредложения $ao_real_price_numeric

                        if ($ao_product_parent['special'] && $ao_product_parent['special'] > 0) {
                            $product_real_price = $this->currency->format($ao_product_parent['special']);
                            $product_real_price_numeric = $ao_product_parent['special'];
                        } else {
                            $product_real_price = $this->currency->format($ao_product_parent['price']);
                            $product_real_price_numeric = $ao_product_parent['price'];
                        }

                    //Сумма покупки без учета спецпредложения
                        $total_price_without_offer = $ao_real_price_numeric + $product_real_price_numeric;

                    //Сумма покупки c учетом спецпредложения
                        $total_price_with_offer = $product_real_price_numeric + $ao_price_numeric;

                    //Абсолютная разница
                        $absolute_diff = ($total_price_without_offer - $total_price_with_offer);

                    //процентная экономия
                        if ($total_price_without_offer == 0) {
                            $percent_diff = 0;
                        } else {
                            $percent_diff = round(($absolute_diff / $total_price_without_offer) * 100);
                        }


                        if ($additional_offer['date_start'] != '0000-00-00') {
                            $ao_date_start = date('d.m.Y', strtotime($additional_offer['date_start']));
                        } else {
                            $ao_date_start = false;
                        }

                        if ($additional_offer['date_end'] != '0000-00-00') {
                            $ao_date_end = date('d.m.Y', strtotime($additional_offer['date_end']));
                            $date_end = explode('-', $additional_offer['date_end']);
                            $ao_js_date_end = $date_end[0] . ', ' . (int)$date_end[1] . ' - 1, ' . $date_end[2];
                        } else {
                            $ao_js_date_end = false;
                            $ao_date_end = false;
                        }


                        $this->data['additional_offers'][] = array(
                            'additional_offer_id'       => $additional_offer['product_additional_offer_id'],
                            'ao_product_id'             => $additional_offer['ao_product_id'],
                            'ao_rating'                 => $ao_product['rating'],
                            'ao_href'                   => $this->url->link('product/product', 'product_id=' . $additional_offer['ao_product_id']),
                            'ao_product_name'           => $ao_product['name'],
                            'ao_product_model'          => $ao_product['model'],
                            'ao_price'                  => $ao_price,
                            'ao_real_price'             => $ao_real_price,
                            'product_real_price'        => $product_real_price,
                            'total_price_without_offer' => $this->currency->format($total_price_without_offer),
                            'total_price_with_offer'    => $this->currency->format($total_price_with_offer),
                            'absolute_diff'             => $this->currency->format($absolute_diff),
                            'percent_diff'              => $percent_diff,
                            'ao_image'            => $ao_image,
                            'ao_js_date_end'      => $ao_js_date_end,
                            'date_start'          => $ao_date_start,
                            'date_end'            => $ao_date_end,
                            'ao_quantity'         => $additional_offer['quantity']
                        );
                    }

                    if ($this->data['additional_offers']) {
                        $this->data['has_labels']['special'] = true;
                    }

                //BOF Product Series
                    $this->load->model('catalog/product_master');
                //get link of linked products + colors
                    $pds_allow_buying_series = $this->getData('pds_allow_buying_series', 0);

                $results = $this->model_catalog_product_master->getLinkedProducts($this->request->get['product_id'], '2', $pds_allow_buying_series); //'2' is Image
                
                $this->data['pds'] = array();
                
                $pds_detail_thumbnail_width = $this->getData('pds_detail_thumbnail_width', 50);
                $pds_detail_thumbnail_height = $this->getData('pds_detail_thumbnail_height', 50);
                $pds_preview_width = $this->getData('pds_preview_width', 200);
                $pds_preview_height = $this->getData('pds_preview_height', 200);
                $this->data['pds_enable_preview'] = $this->getData('pds_enable_preview', 1);
                
                foreach ($results as $result) {
                    $product_pds_image = ($result['special_attribute_value'] != '' && strtolower($result['special_attribute_value']) != 'no_image.jpg')
                    ? $this->model_tool_image->resize(
                        $result['special_attribute_value'],
                        $pds_detail_thumbnail_width,
                        $pds_detail_thumbnail_height
                    )
                    : $this->model_tool_image->resize(
                        $result['image'],
                        $pds_detail_thumbnail_width,
                        $pds_detail_thumbnail_height
                    );
                    
                    $product_main_image = ($result['image'] != '' && strtolower($result['image']) != 'no_image.jpg')
                    ? $this->model_tool_image->resize(
                        $result['image'],
                        $pds_preview_width,
                        $pds_preview_height
                    ) //user default main image
                    : $this->model_tool_image->resize(
                        $result['special_attribute_value'],
                        $pds_preview_width,
                        $pds_preview_height
                    ); // use series image
                    
                    $this->data['pds'][] = array(
                        'product_id'         => $result['product_id'],
                        'product_link'       => $this->url->link(
                            'product/product',
                            $url . '&product_id=' . $result['product_id']
                        ),
                        'product_name'       => $result['name'],
                        'product_pds_image'  => $product_pds_image,
                        'product_main_image' => $product_main_image
                    );
                }
                
                $this->load->language('product/pds');
                
                if (!isset($this->data['display_add_to_cart'])) {
                    $is_master = $this->model_catalog_product_master->isMaster(
                        $this->request->get['product_id'],
                        '2'
                    ); //2 is Image
                    $pds_allow_buying_series = $this->getData('pds_allow_buying_series', 0);
                    $this->data['display_add_to_cart'] = !$is_master || $pds_allow_buying_series;
                    $this->data['no_add_to_cart_message'] = $this->language->get('text_select_series_item');
                }
                
                $this->data['text_in_the_same_series'] = $this->language->get('text_in_the_same_series');
                //EOF Product Series
                
                $this->data['product_code'] = $this->request->get['product_id'];
                
                $this->data['options'] = array();
                
                $replaced_product_id = false;
                
                $_all_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
                
                if (!$_all_options && $product_info['is_option_for_product_id']) {
                    $_all_options = $this->model_catalog_product->getProductOptions($product_info['is_option_for_product_id']);
                }
                
                foreach ($_all_options as $option) {
                    if ($option['type'] == 'select' || $option['type'] == 'block' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                        $option_value_data = array();
                        
                        foreach ($option['option_value'] as $option_value) {
                            if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                                if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                                    $o_price = $this->currency->format($this->tax->calculate(
                                        $option_value['price'],
                                        $product_info['tax_class_id'],
                                        $this->config->get('config_tax')
                                    ));
                                } else {
                                    $o_price = false;
                                }
                                
                                $real_product = $this->model_catalog_product->getProduct($option_value['this_is_product_id']);
                                
                                if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$real_product['price']) {
                                    $o_price = $this->currency->format($this->tax->calculate(
                                        $real_product['price'],
                                        $product_info['tax_class_id'],
                                        $this->config->get('config_tax')
                                    ));
                                } else {
                                    $o_price = false;
                                }
                                
                                if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$real_product['special']) {
                                    $o_special = $this->currency->format($this->tax->calculate(
                                        $real_product['special'],
                                        $product_info['tax_class_id'],
                                        $this->config->get('config_tax')
                                    ));
                                } else {
                                    $o_special = false;
                                }
                                
                                $option_value_data[] = array(
                                    'product_option_value_id' => $option_value['product_option_value_id'],
                                    'option_value_id'         => $option_value['option_value_id'],
                                    'name'                    => $option_value['name'],
                                    'pbo_image'               => $this->model_tool_image->resize(
                                        $option_value['image'],
                                        $this->getData('pbo_image_block_width', 50),
                                        $this->getData('pbo_image_block_height', 50)
                                    ),
                                    'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                    'price'                   => $o_price,
                                    'special'                 => $o_special,
                                    'saving'                  => round((($real_product['price'] - $real_product['special']) / ($real_product['price'] + 0.01)) * 100, 0),
                                    'this_is_product_id'      => $option_value['this_is_product_id'],
                                    'this_is_product_href'    => $option_value['this_is_product_id']?$this->url->link('catalog/product', 'product_id=' . $option_value['this_is_product_id']):false,
                                    'real_product'            => $real_product,
                                    'real_product_type'       => $this->model_catalog_product->getProductAttributeValueById(
                                        $option_value['this_is_product_id'],
                                        1
                                    ),
                                    'selected_by_default'     => false,
                                    'price_prefix'            => $option_value['price_prefix']
                                );
                            }
                        }
                        
                        
                        $this->data['options'][] = array(
                            'product_option_id' => $option['product_option_id'],
                            'option_id'         => $option['option_id'],
                            'name'              => $option['name'],
                            'type'              => $option['type'],
                            'option_value'      => $option_value_data,
                            'required'          => $option['required']
                        );
                    } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
                        $this->data['options'][] = array(
                            'product_option_id' => $option['product_option_id'],
                            'option_id'         => $option['option_id'],
                            'name'              => $option['name'],
                            'type'              => $option['type'],
                            'option_value'      => $option['option_value'],
                            'required'          => $option['required']
                        );
                    }
                }
                
                //  include(DIR_SYSTEM . '../catalog/controller/product/options_boost.inc.php');
                
                if (file_exists(DIR_SYSTEM . '../catalog/controller/product/options_boost.inc.php')) {
                    foreach ($_all_options as $option) {
                        if ($option['type'] == 'image') {
                            foreach ($option['option_value'] as $option_value) {
                                foreach ($this->data['options'] as &$r_option) {
                                    if (is_array($r_option['option_value'])) {
                                        foreach ($r_option['option_value'] as &$r_option_value) {
                                            if ($r_option_value['product_option_value_id'] == $option_value['product_option_value_id']) {
                                                $r_option_value['pbo_image'] = $this->model_tool_image->resize(
                                                    $option_value['image'],
                                                    $this->getData('pbo_image_block_width', 50),
                                                    $this->getData('pbo_image_block_height', 50)
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                //overloading product name, model, price and etc by option value
                if ($this->data['options'] && count($this->data['options']) == 1) {
                    $key = 0; //default 0
                    //if we have a product which is given from previous
                    
                    if (isset($this->request->get['oid']) && (int)$this->request->get['oid'] && $this->request->get['oid'] > 0) {
                        foreach ($this->data['options'][0]['option_value'] as $iterator => $option_value) {
                            if ($option_value['this_is_product_id'] == $this->request->get['product_id'] && $option_value['real_product']) {
                                $key = $iterator;
                                break;
                            }
                            
                            if ($option_value['this_is_product_id'] == $this->request->get['oid'] && $option_value['real_product']) {
                                $key = $iterator;
                                break;
                            }
                        }
                    } else {
                        foreach ($this->data['options'][0]['option_value'] as $iterator => $option_value) {
                            if ($option_value['this_is_product_id'] == $this->request->get['product_id'] && $option_value['real_product']) {
                                $key = $iterator;
                                break;
                            }
                        }
                    }
                    
                    $this->data['options'][0]['option_value'][$key]['selected_by_default'] = true;
                    
                    if (isset($this->data['options'][0]['option_value'][$key]['real_product']) && $product_to_overload = $this->data['options'][0]['option_value'][$key]['real_product']) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$product_to_overload['price']) {
                            $product_to_overload['price'] = $this->currency->format($this->tax->calculate(
                                $product_to_overload['price'],
                                $product_info['tax_class_id'],
                                $this->config->get('config_tax')
                            ));
                        } else {
                            $product_to_overload['price'] = false;
                        }
                        
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$product_to_overload['special']) {
                            $product_to_overload['special'] = $this->currency->format($this->tax->calculate(
                                $product_to_overload['special'],
                                $product_info['tax_class_id'],
                                $this->config->get('config_tax')
                            ));
                        } else {
                            $product_to_overload['special'] = false;
                        }
                        
                        $overload_params_array = array(
                            'product_id'      => 'product_code',
                            'model'           => 'model',
                            'price'           => 'price',
                            'stock_status_id' => 'stock_status_id',
                            'special'         => 'special',
                            'ean'             => 'ean',
                        );
                        
                        foreach ($overload_params_array as $_param => $_datavalue) {
                            if (isset($product_to_overload[$_param])) {
                                if ($_param != 'product_id') {
                                    $product_info[$_param] = $product_to_overload[$_param];
                                }
                                $this->data[$_datavalue] = $product_to_overload[$_param];
                            }
                        }
                    }
                }
                
                $this->data['current_in_stock'] = (int)$product_info['current_in_stock_q'];

                if ((int)$this->data['current_in_stock'] <= 1) {
                    $this->data['current_in_stock_color'] = 'bad';
                }

                if ((int)$this->data['current_in_stock'] > 1 && (int)$this->data['current_in_stock'] <= 4) {
                    $this->data['current_in_stock_color'] = 'ask';
                }

                if ((int)$this->data['current_in_stock'] >= 5) {
                    $this->data['current_in_stock_color'] = 'good';
                }

                $this->data['stock'] = $product_info['stock_status'];
                $this->data['stock_status_id'] = $product_info['stock_status_id'];
                $this->data['can_not_buy'] = ($product_info['stock_status_id'] == $this->config->get('config_not_in_stock_status_id'));
                $this->data['need_ask_about_stock'] = ($product_info['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id'));
                $this->data['stock_color'] = ($product_info['stock_status_id'] == $this->config->get('config_stock_status_id')) ? '#4C6600' : '#BA0000';
                
                if ($this->data['can_not_buy']) {
                    $this->data['rees46_is_available'] = 0;
                } else {
                    $this->data['rees46_is_available'] = $product_info['quantity'];
                }
                
                if (!$just_price) {
                    $product_product_options = array();
                    
                    $product_product_options = $this->model_catalog_product->getProductProductOptions($this->request->get['product_id']);
                    
                    $this->data['product_product_options'] = array();
                    
                    foreach ($product_product_options as $product_product_option) {
                        $product_product_option_value_data = array();
                        
                        foreach ($product_product_option['product_option'] as $product_option_value) {
                            $special = false;
                            $product_option_info = $this->model_catalog_product->getProduct($product_option_value['product_option_id']);
                            
                            if ($product_option_info['current_in_stock']  && !empty($product_option_value['stock_status_id']) && $product_option_value['stock_status_id'] != $this->config->get('config_not_in_stock_status_id')) {
                                if (isset($product_option_value['image']) && file_exists(DIR_IMAGE . $product_option_value['image'])) {
                                    $thumb = $this->model_tool_image->resize($product_option_value['image'], 280, 320);
                                    $popup = $this->model_tool_image->resize(
                                        $product_option_value['image'],
                                        $this->config->get('config_image_popup_width'),
                                        $this->config->get('config_image_popup_height')
                                    );
                                } else {
                                    $thumb = $this->model_tool_image->resize($this->config->get('config_noimage'), 280, 320);
                                    $popup = $this->model_tool_image->resize(
                                        $this->config->get('config_noimage'),
                                        $this->config->get('config_image_popup_width'),
                                        $this->config->get('config_image_popup_height')
                                    );
                                }
                                
                                
                                
                                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                    $price = $this->currency->format($this->tax->calculate(
                                        $product_option_info['price'],
                                        $product_info['tax_class_id'],
                                        $this->config->get('config_tax')
                                    ));
                                } else {
                                    $price = false;
                                }
                                
                                if ((float)$product_option_info['special']) {
                                    $special = $this->currency->format($this->tax->calculate(
                                        $product_option_info['special'],
                                        $product_info['tax_class_id'],
                                        $this->config->get('config_tax')
                                    ));
                                } else {
                                    $special = false;
                                }
                                
                                $product_product_option_value_data[] = array(
                                    'product_option_id' => $product_option_value['product_option_id'],
                                    'name'              => $product_option_value['name'],
                                    'image'             => $thumb,
                                    'popup'             => $popup,
                                    'price'             => $price,
                                    'special'           => $special,
                                    'href'              => $this->url->link(
                                        'product/product',
                                        'product_id=' . $product_option_value['product_option_id']
                                    ),

                                );
                            }
                        }
                        
                        if (!empty($product_product_option_value_data)) {
                            $this->data['product_product_options'][] = array(
                                'product_product_option_id' => $product_product_option['product_product_option_id'],
                                'name'                      => $product_product_option['name'],
                                'type'                      => $product_product_option['type'],
                                'required'                  => $product_product_option['required'],
                                'product_option'            => $product_product_option_value_data
                            );
                        }
                    }
                }
                
                if ($product_info['minimum']) {
                    $this->data['minimum'] = $product_info['minimum'];
                } else {
                    $this->data['minimum'] = 1;
                }
                
                $this->data['logged'] = $this->customer->isLogged();
                
                if (!$just_price) {
                    $this->data['review_status'] = $this->config->get('config_review_status');
                    $this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
                    $this->data['rating'] = (int)$product_info['rating'];
                    
                    $this->data['review_count'] = $product_info['reviews'];
                    $this->data['currencycode'] = $this->currency->getCode();
                    $this->data['stockqty'] = $product_info['quantity'];
                    $this->data['hb_snippets_prod_enable'] = $this->config->get('hb_snippets_prod_enable');
                    $this->data['hb_snippets_bc_enable'] = $this->config->get('hb_snippets_bc_enable');
                    $this->data['language_decimal_point'] = $this->language->get('decimal_point');
                    
                    $this->data['mask'] = $this->config->get('config_phonemask');
                    if ($this->customer->isLogged()) {
                        $this->data['customer_telephone'] = $this->customer->getTelephone();
                        $this->data['customer_id'] = $this->customer->getTelephone();
                    } else {
                        $this->data['customer_id'] = false;
                        $this->data['customer_telephone'] = false;
                    }
                    
                    if ($product_info['description']) {
                        /*  if ($this->config->get('config_language_id') == 6){

                            $this->log->debug(tidy_repair_string($product_info['description']));

                            if ($tryToRepairDescripion = tidy_repair_string($this->data['description'])){
                            $this->data['description'] = $tryToRepairDescripion;
                            }
                            }
                        */

                            $this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8') . ' ';
                        } else {
                            $this->data['description'] = false;
                        }


                        $autolinks = $this->config->get('autolinks');

                        if (isset($autolinks) && (strpos(
                            $this->data['description'],
                            'iframe'
                        ) == false) && (strpos($this->data['description'], 'object') == false)
                    ) {
                            $xdescription = mb_convert_encoding(html_entity_decode(
                                $this->data['description'],
                                ENT_COMPAT,
                                "UTF-8"
                            ), 'HTML-ENTITIES', "UTF-8");

                            libxml_use_internal_errors(true);
                            $dom = new DOMDocument;
                            $dom->loadHTML('<div>' . $xdescription . '</div>');
                            libxml_use_internal_errors(false);


                            $xpath = new DOMXPath($dom);

                            foreach ($autolinks as $autolink) {
                                $keyword = $autolink['keyword'];
                                $xlink = mb_convert_encoding(
                                    html_entity_decode($autolink['link'], ENT_COMPAT, "UTF-8"),
                                    'HTML-ENTITIES',
                                    "UTF-8"
                                );
                                $target = $autolink['target'];
                                $tooltip = isset($autolink['tooltip']);

                                $pTexts = $xpath->query(
                                    sprintf('///text()[contains(., "%s")]', $keyword)
                                );

                                foreach ($pTexts as $pText) {
                                    $this->parseText($pText, $keyword, $dom, $xlink, $target, $tooltip);
                                }
                            }

                            $this->data['description'] = $dom->saveXML($dom->documentElement);
                        }

                        $this->data['payment_list'] = explode(PHP_EOL, $this->config->get('config_payment_list'));

                        $html_block = $this->model_setting_setting->getKeySettingValue('html_block', 'html_block_6');

                        if ($html_block) {
                            if (!empty($html_block['content'][$this->config->get('config_language_id')])) {
                                $this->data['payment_list'] = explode(PHP_EOL, $html_block['content'][$this->config->get('config_language_id')]);
                            }
                        }

                        $this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

                        $this->data['special_attribute_group_id'] = $this->config->get('config_special_attr_id');
                        $this->data['attribute_groups_special'] = $this->model_catalog_product->getProductAttributesByGroupId(
                            $this->request->get['product_id'],
                            $this->data['special_attribute_group_id']
                        );


                        $this->data['color_grouped_products'] = array();
                        if ($product_info['color_group']) {
                            $_cgrouped_products = $this->model_catalog_product->getColorGroupedProducts(
                                $this->request->get['product_id'],
                                $product_info['color_group']
                            );

                            if ($_cgrouped_products) {
                                foreach ($_cgrouped_products as $_cgproduct) {
                                    $_cgrealproduct = $this->model_catalog_product->getProduct($_cgproduct['product_id']);

                                    $this->data['color_grouped_products'][] = array(
                                        'image' => $this->model_tool_image->resize($_cgrealproduct['image'], 100, 100),
                                        'name'  => $_cgrealproduct['name'],
                                        '_href' => $this->url->link(
                                            'product/product',
                                            'product_id=' . $_cgrealproduct['product_id']
                                        )
                                    );
                                }
                            }
                        }

                        if ($product_info['collection_id']) {
                            $this->load->model('catalog/collection');

                            $_collection = $this->model_catalog_collection->getCollection($product_info['collection_id']);
                            if ($_collection) {
                                $this->data['collection_name'] = $_collection['name'];
                            } else {
                                $this->data['collection_name'] = $product_info['mpn'];
                            }
                            $this->data['collection_link'] = $this->url->link('product/collection', 'collection_id=' . $product_info['collection_id']);

                            $this->data['collection_dimensions'] = array(
                                'w' => $this->config->get('config_image_related_width'),
                                'h' => $this->config->get('config_image_related_height')
                            );

                            $cfilter_data = array(
                                'filter_collection_id' => $product_info['collection_id'],
                                'no_child'             => true,
                                'start'                => 0,
                                'limit'                => 50,
                                'filter_not_bad'       => true
                            );

                            $results = $this->model_catalog_product->getProducts($cfilter_data);
                            $this->data['collection'] = $this->model_catalog_product->prepareProductToArray($results);
                        }

                        $this->data['child_products'] = array();

                        $this->data['has_child'] = false;
                        if ($product_info['has_child']) {
                            $results = $this->model_catalog_product->getProductChild($this->request->get['product_id']);
                            $this->data['child_products'] = $this->model_catalog_product->prepareProductToArray($results, $bestsellers);
                            if ($this->data['child_products']) {
                                $this->data['has_child'] = true;
                            }
                        }


                    //нет в наличии в стране, подбираем что-то похожее со склада
                        $this->data['products_same_onstock'] = array();
                        if ($this->data['stock_type'] <> 'in_stock_in_country') {
                            $results = $this->model_catalog_product->guessSameProducts($product_info['name'], $product_info['product_id'], 12, true);
                            $this->data['products_same_onstock'] = $this->model_catalog_product->prepareProductToArray($results);
                        }


                        $this->data['dimensions'] = array(
                            'w' => $this->config->get('config_image_related_width'),
                            'h' => $this->config->get('config_image_related_height')
                        );



                    //related
                        $this->data['products'] = array();
                        $results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
                        $this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);


                    //similar
                        $this->data['products_similar'] = array();
                        $results = $this->model_catalog_product->getProductSimilar($this->request->get['product_id']);
                        $this->data['products_similar'] = $this->model_catalog_product->prepareProductToArray($results);

                    //sponsored
                        $this->data['products_sponsored'] = array();
                        $results = $this->model_catalog_product->getProductSponsored($this->request->get['product_id']);
                        $this->data['products_sponsored'] = $this->model_catalog_product->prepareProductToArray($results);



                        $this->data['color_group_products'] = array();
                    //try to get similar products of parent
                        $parent_product = false;
                        if (!$product_info['color_group'] && $product_info['is_option_for_product_id']) {
                            $parent_product = $this->model_catalog_product->getProduct($product_info['is_option_for_product_id']);
                        }

                        if ($product_info['color_group'] || $parent_product) {
                            if ($parent_product) {
                                $results = $this->model_catalog_product->getProductColourGroupRelated($parent_product['color_group'], $parent_product['product_id']);
                            } else {
                                $results = $this->model_catalog_product->getProductColourGroupRelated($product_info['color_group'], $this->request->get['product_id']);
                            }

                            $this->data['color_group_products'] = $this->model_catalog_product->prepareProductToArray($results);
                        }

                        if (!isset($category_id)) {
                            $category_id = $this->model_catalog_product->getOnlyProductPath($product_id);
                        }

                        if (!isset($this->request->get['collection_id'])) {
                            $collection_id = 0;
                        } else {
                            $collection_id = $this->request->get['collection_id'];
                        }

                        $result = $this->model_catalog_product->getPrevNextProduct($product_id, $category_id, $collection_id);

                        if (isset($this->request->get['path'])) {
                            $this->data['prev_product'] = array(
                                'product_id' => $result['prev']['product_id'],
                                'name'       => $result['prev']['name'],
                                'href'       => $this->url->link(
                                    'product/product',
                                    'path=' . $this->request->get['path'] . '&product_id=' . $result['prev']['product_id']
                                ),
                                'thumb'      => $this->model_tool_image->resize($result['prev']['image'], 100, 100)
                            );

                            $this->data['next_product'] = array(
                                'product_id' => $result['next']['product_id'],
                                'name'       => $result['next']['name'],
                                'href'       => $this->url->link(
                                    'product/product',
                                    'path=' . $this->request->get['path'] . '&product_id=' . $result['next']['product_id']
                                ),
                                'thumb'      => $this->model_tool_image->resize($result['next']['image'], 100, 100)
                            );
                        } elseif ($collection_id) {
                            $this->data['prev_product'] = array(
                                'product_id' => $result['prev']['product_id'],
                                'name'       => $result['prev']['name'],
                                'href'       => $this->url->link(
                                    'product/product',
                                    'collection_id=' . $collection_id . '&product_id=' . $result['prev']['product_id']
                                ),
                                'thumb'      => $this->model_tool_image->resize($result['prev']['image'], 100, 100)
                            );

                            $this->data['next_product'] = array(
                                'product_id' => $result['next']['product_id'],
                                'name'       => $result['next']['name'],
                                'href'       => $this->url->link(
                                    'product/product',
                                    'collection_id=' . $this->request->get['collection_id'] . '&product_id=' . $result['next']['product_id']
                                ),
                                'thumb'      => $this->model_tool_image->resize($result['next']['image'], 100, 100)
                            );
                        } else {
                            $this->data['prev_product'] = array(
                                'product_id' => $result['prev']['product_id'],
                                'name'       => $result['prev']['name'],
                                'href'       => $this->url->link(
                                    'product/product',
                                    'product_id=' . $result['prev']['product_id']
                                ),
                                'thumb'      => $this->model_tool_image->resize($result['prev']['image'], 100, 100)
                            );

                            $this->data['next_product'] = array(
                                'product_id' => $result['next']['product_id'],
                                'name'       => $result['next']['name'],
                                'href'       => $this->url->link(
                                    'product/product',
                                    'product_id=' . $result['next']['product_id']
                                ),
                                'thumb'      => $this->model_tool_image->resize($result['next']['image'], 100, 100)
                            );
                        }

                    //opengraph see also
                        $this->document->addOpenGraph('see_also', $this->data['next_product']['href']);
                        $this->document->addOpenGraph('see_also', $this->data['prev_product']['href']);


                        $this->data['tags'] = array();

                        if ($product_info['tag']) {
                            $tags = explode(',', $product_info['tag']);

                            foreach ($tags as $tag) {
                                $this->data['tags'][] = array(
                                    'tag'  => trim($tag),
                                    'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                                );
                            }
                        }

                    //GOOGLE CONVERSION CODE

                        if ($this->config->get('config_google_remarketing_type') == 'ecomm') {
                            $this->data['google_tag_params'] = array(
                                'ecomm_prodid'     => $product_info['product_id'],
                                'ecomm_pagetype'   => 'product',
                                'ecomm_totalvalue' => ($product_info['special']) ? $this->currency->format(
                                    $product_info['special'],
                                    '',
                                    '',
                                    false
                                ) : $this->currency->format($product_info['price'], '', '', false)
                            );
                        } else {
                            $this->data['google_tag_params'] = array(
                                'dynx_itemid'     => $product_info['product_id'],
                                'dynx_itemid2'    => $product_info['sku'],
                                'dynx_pagetype'   => 'offerdetail',
                                'dynx_totalvalue' => ($product_info['special']) ? $this->currency->format(
                                    $product_info['special'],
                                    '',
                                    '',
                                    false
                                ) : $this->currency->format($product_info['price'], '', '', false)
                            );
                        }

                        $this->data['categories'] = array();
                        foreach ($this->data['breadcrumbs'] as $k => $breadcrumb) {
                            if (!$k) {
                                continue;
                            }
                            $this->data['categories'][] = $breadcrumb['text'];
                        }

                        $this->data['google_ecommerce_info'] = array(
                            'price'      => ($product_info['special']) ? $this->currency->format(
                                $product_info['special'],
                                '',
                                '',
                                false
                            ) : $this->currency->format($product_info['price'], '', '', false),
                            'brand'      => $this->data['manufacturer'],
                            'name'       => $this->data['heading_title'],
                            'category'   => implode('/', $this->data['categories']),
                            'product_id' => $product_info['product_id'],
                            'currency'   => $this->config->get('config_regional_currency')
                        );

                        $this->data['google_ecommerce_info'] = array_map('prepareEcommString', $this->data['google_ecommerce_info']);

                    //END GOOGLE CONVERSION CODE


                        $this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
                        $this->data['profiles'] = $this->model_catalog_product->getProfiles($product_info['product_id']);

                        require_once(DIR_SYSTEM . 'library/microdata/schemaorg/schema_product.php');
                        require_once(DIR_SYSTEM . 'library/microdata/opengraph/product.php');
                        require_once(DIR_SYSTEM . 'library/microdata/twittercard/product.php');

                        $this->load->model('design/layout');
                        if ($this->data['is_set']) {
                            $this->template = 'product/product.set_kp.tpl';
                        }

                        if ($this->data['has_child']) {
                            $this->template = 'product/product.has_child.tpl';
                        } else {
                            $this->template = false;
                        }


                        if (!$this->template) {
                            $layout_id = $this->model_catalog_product->getProductLayoutId($product_info['product_id']);
                            if (!$layout_id) {
                                $layout_id = $this->model_design_layout->getLayout('product/product');
                            }

                            if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
                                $this->template = $template;
                            } else {
                                $template_overload = false;
                                $this->load->model('setting/setting');
                                $custom_template_module = $this->model_setting_setting->getSetting('custom_template_module', $this->config->get('config_store_id'));
                                if (!empty($custom_template_module['custom_template_module'])) {
                                    if (isset($this->request->get['path'])) {
                                        foreach ($custom_template_module['custom_template_module'] as $key => $module) {
                                            if (($module['type'] == 4) && !empty($module['product_categories'])) {
                                                $category_id = explode('_', $this->request->get['path']);
                                                $category_id = end($category_id);
                                                if (in_array($category_id, $module['product_categories'])) {
                                                    $this->template = $module['template_name'];
                                                    $template_overload = true;
                                                }
                                            }
                                        }
                                    }

                                    foreach ($custom_template_module['custom_template_module'] as $key => $module) {
                                        if (($module['type'] == 1) && !empty($module['products'])) {
                                            $products = explode(',', $module['products']);
                                            if (in_array($product_id, $products)) {
                                                $this->template = $module['template_name'];
                                                $template_overload = true;
                                            }
                                        }
                                    }
                                }


                                if (!$template_overload) {
                                    $this->template = 'product/product.tpl';
                                }
                            }
                        }

                    //BOF Product Block Option
                        $this->setData('pbo_text_block_padding', 10);
                        $this->setData('pbo_text_block_border_width', 3);
                        $this->setData('pbo_text_block_border_radius', 0);
                        $this->setData('pbo_text_block_background_color', '#ffffff');
                        $this->setData('pbo_text_block_text_color', '#000000');
                        $this->setData('pbo_text_block_border_color', '#E7E7E7');
                        $this->setData('pbo_text_block_selected_background_color', '#ffffff');
                        $this->setData('pbo_text_block_selected_text_color', '#000000');
                        $this->setData('pbo_text_block_selected_block_border_color', '#FFA500');

                        $this->setData('pbo_image_block_padding', 2);
                        $this->setData('pbo_image_block_border_width', 2);
                        $this->setData('pbo_image_block_border_radius', 5);
                        $this->setData('pbo_image_block_border_color', '#E7E7E7');
                        $this->setData('pbo_image_block_selected_block_border_color', '#FFA500');

                        $this->setData('pbo_options', array());
                    //EOF Product Block Option
                    }
                //Дичайшее решение, но за минуту
                    $this->data['original_data'] = $this->data;

                    $this->children = array(
                        'common/column_left',
                        'common/column_right',
                        'common/content_top',
                        'common/content_bottom',
                        'common/footer',
                        'common/header',
                        'product/product/onereview',
                        'product/product/review',
                    );

                    if (!empty($return)) {
                        return $this->data;
                    }


                    $this->response->setOutput($this->render());
                } else {
                    $url = '';

                    if (isset($this->request->get['path'])) {
                        $url .= '&path=' . $this->request->get['path'];
                    }

                    if (isset($this->request->get['filter'])) {
                        $url .= '&filter=' . $this->request->get['filter'];
                    }

                    if (isset($this->request->get['manufacturer_id'])) {
                        $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
                    }

                    if (isset($this->request->get['search'])) {
                        $url .= '&search=' . $this->request->get['search'];
                    }

                    if (isset($this->request->get['tag'])) {
                        $url .= '&tag=' . $this->request->get['tag'];
                    }

                    if (isset($this->request->get['description'])) {
                        $url .= '&description=' . $this->request->get['description'];
                    }

                    if (isset($this->request->get['category_id'])) {
                        $url .= '&category_id=' . $this->request->get['category_id'];
                    }

                    if (isset($this->request->get['sub_category'])) {
                        $url .= '&sub_category=' . $this->request->get['sub_category'];
                    }

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }

                    $this->data['breadcrumbs'][] = array(
                        'text'      => $this->language->get('text_error'),
                        'href'      => $this->url->link('product/product', $url . '&product_id=' . $product_id),
                        'separator' => $this->language->get('text_separator')
                    );

                    $this->document->setTitle($this->language->get('text_error'));

                    $this->data['heading_title'] = $this->language->get('text_error');

                    $this->data['text_error'] = $this->language->get('text_error');

                    $this->data['button_continue'] = $this->language->get('button_continue');

                    $this->data['continue'] = $this->url->link('common/home');

                    $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
                    } else {
                        $this->template = 'default/template/error/not_found.tpl';
                    }

                    $this->children = array(
                        'common/column_left',
                        'common/column_right',
                        'common/content_top',
                        'common/content_bottom',
                        'common/footer',
                        'common/header'
                    );

                    $this->response->setOutput($this->render());
                }
            }

            public function onereview()
            {
                $this->language->load('product/product');

                foreach ($this->language->loadRetranslate('product/product') as $translationСode => $translationText) {
                    $this->data[$translationСode] = $translationText;
                }

                $this->load->model('catalog/review');
                $this->load->model('tool/image');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

                $results = $this->model_catalog_review->getBestReviewsForProductID($this->request->get['product_id']);

                $this->data['product_id'] = (int)$this->request->get['product_id'];

                $this->data['reviews'] = array();
                foreach ($results as $result) {
                    if ($result['addimage'] && mb_strlen($result['addimage']) > 32) {
                        if (filter_var($result['addimage'], FILTER_VALIDATE_URL)) {
                            $image = $result['addimage'];
                        } else {
                            $size = getimagesize(DIR_IMAGE . $result['addimage']);
                            $image = $this->model_tool_image->resize($result['addimage'], $size[0], $size[1]);
                        }
                    } else {
                        $image = false;
                    }

                    $this->data['reviews'][] = array(
                        'author'     => $result['author'],
                        'answer'     => $result['answer'],
                        'text'       => $result['text'],
                        'good'       => $result['good'],
                        'bads'       => $result['bads'],
                        'addimage'   => $image,
                        'purchased'  => $result['purchased'],
                        'rating'     => (int)$result['rating'],
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
                    );
                }


                $this->template = 'product/onereview.tpl';

                $this->response->setOutput($this->render());
            }

            public function review()
            {
                $this->language->load('product/product');
                foreach ($this->language->loadRetranslate('product/product') as $translationСode => $translationText) {
                    $this->data[$translationСode] = $translationText;
                }


                $this->load->model('catalog/review');
                $this->load->model('tool/image');

                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

                if (isset($this->request->get['page'])) {
                    $page = $this->request->get['page'];
                } else {
                    $page = 1;
                }

                $this->data['reviews'] = array();

                $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
                $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

                $this->data['product_id'] = (int)$this->request->get['product_id'];

                foreach ($results as $result) {
                    if ($result['addimage'] && mb_strlen($result['addimage']) > 32) {
                        if (filter_var($result['addimage'], FILTER_VALIDATE_URL)) {
                            $image = $result['addimage'];
                        } else {
                            $size = getimagesize(DIR_IMAGE . $result['addimage']);
                            $image = $this->model_tool_image->resize($result['addimage'], $size[0], $size[1]);
                        }
                    } else {
                        $image = false;
                    }

                    $this->data['reviews'][] = array(
                        'author'     => $result['author'],
                        'answer'     => $result['answer'],
                        'text'       => $result['text'],
                        'good'       => $result['good'],
                        'bads'       => $result['bads'],
                        'addimage'   => $image,
                        'purchased'  => $result['purchased'],
                        'rating'     => (int)$result['rating'],
                        'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
                    );
                }

                $pagination = new Pagination();
                $pagination->total = $review_total;
                $pagination->page = $page;
                $pagination->limit = 5;
                $pagination->text = $this->language->get('text_pagination');
                $pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

                $this->data['pagination'] = $pagination->render();

                $this->template = 'product/review.tpl';

                $this->response->setOutput($this->render());
            }


            public function getRecurringDescription()
            {
                $this->language->load('product/product');
                $this->load->model('catalog/product');

                if (isset($this->request->post['product_id'])) {
                    $product_id = $this->request->post['product_id'];
                } else {
                    $product_id = 0;
                }

                if (isset($this->request->post['profile_id'])) {
                    $profile_id = $this->request->post['profile_id'];
                } else {
                    $profile_id = 0;
                }

                if (isset($this->request->post['quantity'])) {
                    $quantity = $this->request->post['quantity'];
                } else {
                    $quantity = 1;
                }

                $product_info = $this->model_catalog_product->getProduct($product_id);
                $profile_info = $this->model_catalog_product->getProfile($product_id, $profile_id);

                $json = array();

                if ($product_info && $profile_info) {
                    if (!$json) {
                        $frequencies = array(
                            'day'        => $this->language->get('text_day'),
                            'week'       => $this->language->get('text_week'),
                            'semi_month' => $this->language->get('text_semi_month'),
                            'month'      => $this->language->get('text_month'),
                            'year'       => $this->language->get('text_year'),
                        );

                        if ($profile_info['trial_status'] == 1) {
                            $price = $this->currency->format($this->tax->calculate(
                                $profile_info['trial_price'] * $quantity,
                                $product_info['tax_class_id'],
                                $this->config->get('config_tax')
                            ));
                            $trial_text = sprintf(
                                $this->language->get('text_trial_description'),
                                $price,
                                $profile_info['trial_cycle'],
                                $frequencies[$profile_info['trial_frequency']],
                                $profile_info['trial_duration']
                            ) . ' ';
                        } else {
                            $trial_text = '';
                        }

                        $price = $this->currency->format($this->tax->calculate(
                            $profile_info['price'] * $quantity,
                            $product_info['tax_class_id'],
                            $this->config->get('config_tax')
                        ));

                        if ($profile_info['duration']) {
                            $text = $trial_text . sprintf(
                                $this->language->get('text_payment_description'),
                                $price,
                                $profile_info['cycle'],
                                $frequencies[$profile_info['frequency']],
                                $profile_info['duration']
                            );
                        } else {
                            $text = $trial_text . sprintf(
                                $this->language->get('text_payment_until_canceled_description'),
                                $price,
                                $profile_info['cycle'],
                                $frequencies[$profile_info['frequency']],
                                $profile_info['duration']
                            );
                        }

                        $json['success'] = $text;
                    }
                }

                $this->response->setOutput(json_encode($json));
            }

            public function write()
            {
                $this->language->load('product/product');
                $this->load->model('catalog/review');

                $json = array();

                if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                    $this->request->post = $this->shortcodes->strip_shortcodes($this->request->post);


                    if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                        $json['error'] = $this->language->get('error_name');
                    }

                    $text_symbol = $this->config->get('config_review_text_symbol');

                    if (!isset($text_symbol)) {
                        if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                            $json['error'] = $this->language->get('error_text');
                        }
                    } else {
                        if ((utf8_strlen($this->request->post['text']) < $text_symbol) || (utf8_strlen($this->request->post['text']) > 1000)) {
                            $json['error'] = sprintf($this->language->get('error_text_symbol'), $text_symbol);
                        }
                    }

                    if (empty($this->request->post['rating'])) {
                        $json['error'] = $this->language->get('error_rating');
                    }

                /*
                if ($this->config->get('config_review_captcha')) {
                if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
                print $this->session->data['captcha'];
                $json['error'] = $this->language->get('error_captcha');
                }
                }
                */
                
                if (isset($this->request->files) && isset($this->request->files['add-review-image']) && is_array($this->request->files['add-review-image']) && mb_strlen($this->request->files['add-review-image']['name']) > 3) {
                    $upload_directory = DIR_IMAGE . 'data/review_upload/';
                    $blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5");
                    
                    $file_error = false;
                    foreach ($blacklist as $item) {
                        if (preg_match("/$item\$/i", $this->request->files['add-review-image']['name'])) {
                            $json['error'] = $this->language->get('error_filetype');
                            $file_error = true;
                        }
                    }
                    
                    if (!$file_error) {
                        $imageinfo = getimagesize($this->request->files['add-review-image']['tmp_name']);
                        if ($imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/jpg') {
                            $json['error'] = $this->language->get('error_filetype');
                            $file_error = true;
                        }
                    }
                    
                    if (!$file_error) {
                        $filenames = array(
                            'image/jpg'  => '.jpg',
                            'image/jpeg' => '.jpeg',
                            'image/png'  => '.png',
                            'image/webp' => '.webp'
                        );
                        
                        $upload_file_name = md5(time()) . $filenames[$imageinfo['mime']];
                        
                        if (move_uploaded_file(
                            $this->request->files['add-review-image']['tmp_name'],
                            $upload_directory . $upload_file_name
                        )) {
                            $this->request->post['addimage'] = 'data/review_upload/' . $upload_file_name;
                        } else {
                            $json['error'] = $this->language->get('error_filetype');
                        }
                    }
                }
                
                if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                    $json['error'] = $this->language->get('error_text');
                }
                
                if (empty($this->request->post['rating'])) {
                    $json['error'] = $this->language->get('error_rating');
                }
                
                if (empty($json['error'])) {
                    $this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);
                    

                    if (!$this->config->get('config_review_statusp')) {
                        $json['success'] = $this->language->get('text_success');
                    } else {
                        $json['success'] = $this->language->get('text_success_status');
                    }
                }
            }
            
            $this->response->setOutput(json_encode($json));
        }
        
        public function getFormattedPriceAjax()
        {
            $num = $this->request->get['num'];
            $q = $this->request->get['q'];
            
            
            if (!$q || !$num) {
                echo '-';
            } else {
                echo $this->currency->format($num * $q, '', 1);
            }
        }
        
        public function captcha()
        {
            $this->load->library('captcha');
            
            $captcha = new Captcha();
            
            $this->session->data['captcha'] = $captcha->getCode();
            
            $captcha->showImage();
        }
        
        public function upload()
        {
            $this->language->load('product/product');
            
            $json = array();
            
            if (!empty($this->request->files['file']['name'])) {
                $filename = basename(preg_replace(
                    '/[^a-zA-Z0-9\.\-\s+]/',
                    '',
                    html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')
                ));
                
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
                    $json['error'] = $this->language->get('error_filename');
                }
                
            // Allowed file extension types
                $allowed = array();
                
                $filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));
                
                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }
                
                if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }
                
            // Allowed file mime types
                $allowed = array();
                
                $filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));
                
                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }
                
                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }
                
            // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);
                
                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }
                
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
            
            if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
                $file = basename($filename) . '.' . md5(mt_rand());
                
            // Hide the uploaded file name so people can not link to it directly.
                $json['file'] = $this->encryption->encrypt($file);
                
                move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
                
                $json['success'] = $this->language->get('text_upload');
            }
            
            $this->response->setOutput(json_encode($json));
        }
        
        
        public function productload($setId = false)
        {

            $this->language->load('module/set');
            $this->load->model('catalog/set');
            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            
            $this->data['button_cart'] = $this->language->get('button_cart');
            $this->data['button_upload'] = $this->language->get('button_upload');
            
            $this->data['heading_products'] = $this->language->get('heading_products');
            $this->data['text_baseprice'] = $this->language->get('text_baseprice');
            $this->data['text_setprice'] = $this->language->get('text_setprice');
            $this->data['text_setquantity'] = $this->language->get('text_setquantity');
            
            $this->data['text_total'] = $this->language->get('text_total');
            $this->data['text_save'] = $this->language->get('text_save');
            $this->data['text_present'] = $this->language->get('text_present');
            $this->data['text_select'] = $this->language->get('text_select');
            $this->data['text_notactive'] = $this->language->get('text_notactive');
            
            $image_width = (int)$this->config->get('set_product_page_card_image_width') ? $this->config->get('set_product_page_card_image_width') : $this->config->get('config_image_product_width');
            $image_height = (int)$this->config->get('set_product_page_card_image_height') ? $this->config->get('set_product_page_card_image_height') : $this->config->get('config_image_product_height');
            
            $this->data['products'] = array();
            
            $this->data['set_id'] = 0;
            $this->data['active_set'] = true;
            $temp_save = 0;
            $old_total = 0;
            
            if ($setId) {
                $this->data['set_id'] = $setId;
                
                $set_info = $this->model_catalog_set->getSet($setId);
                $set_pricing = $this->model_catalog_set->getProductSet($setId);
                
                $results = $this->model_catalog_set->getProductsInSets($setId);
                if ($results) {
                    foreach ($results as $product) {
                        $active_product = true;
                        
                        if ($product['image']) {
                            $image = $this->model_tool_image->resize($product['image'], $image_width, $image_height);
                        } else {
                            $image = $this->model_tool_image->resize(
                                $this->config->get('config_noimage'),
                                $image_width,
                                $image_height
                            );
                        }
                        
                        if ((float)$product['base_special']) {
                            $real_price = (float)$product['base_special'];
                        } else {
                            $real_price = (float)$product['base_price'];
                        }
                        
                        $product_options = array();
                        
                        foreach ($this->model_catalog_product->getProductOptions($product['product_id']) as $option) {
                            if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                                $option_value_data = array();
                                
                                foreach ($option['option_value'] as $option_value) {
                                    $selected = false;
                                    
                                //if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                                    if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                                        $option_price = $this->currency->format($this->tax->calculate(
                                            $option_value['price'],
                                            $set_info['tax_class_id'],
                                            $this->config->get('config_tax')
                                        ));
                                    } else {
                                        $option_price = false;
                                    }
                                    if (isset($product['options'][$option['product_option_id']]) && $product['options'][$option['product_option_id']] == $option_value['product_option_value_id']) {
                                        $selected = true;
                                        $real_price = (float)$real_price + ($option_value['price_prefix'] . (float)$option_value['price']);
                                        if ((int)$option_value['quantity'] == 0) {
                                            $active_product = false;
                                        }
                                    }
                                    if ($option['type'] == 'checkbox' && isset($product['options'][$option['product_option_id']]) && in_array(
                                        $option_value['product_option_value_id'],
                                        $product['options'][$option['product_option_id']]
                                    )
                                ) {
                                        $selected = true;
                                        $real_price = (float)$real_price + ($option_value['price_prefix'] . (float)$option_value['price']);
                                        if ((int)$option_value['quantity'] == 0) {
                                            $active_product = false;
                                        }
                                    }
                                    $option_value_data[] = array(
                                        'product_option_value_id' => $option_value['product_option_value_id'],
                                        'option_value_id'         => $option_value['option_value_id'],
                                        'selected'                => $selected,
                                        'active'                  => (int)$option_value['quantity'],
                                        'name'                    => $option_value['name'],
                                        'image'                   => $this->model_tool_image->resize(
                                            $option_value['image'],
                                            50,
                                            50
                                        ),
                                        'price'                   => $option_price,
                                        'price_prefix'            => $option_value['price_prefix'],
                                        'ob_sku'                  => $option_value['ob_sku'],
                                        'ob_info'                 => $option_value['ob_info'],
                                        'ob_image'                => $option_value['ob_image'],
                                        'ob_sku_override'         => $option_value['ob_sku_override'],
                                        'raw_image'               => $option_value['image'],
                                        'raw_price'               => $option_value['price'],
                                    );
                                //}
                                }
                                
                                $product_options[] = array(
                                    'product_option_id' => $option['product_option_id'],
                                    'option_id'         => $option['option_id'],
                                    'selected'          => isset($product['options'][$option['product_option_id']]),
                                    'name'              => $option['name'],
                                    'type'              => $option['type'],
                                    'option_value'      => $option_value_data,
                                    'required'          => $option['required']
                                );
                            } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
                                $product_options[] = array(
                                    'product_option_id' => $option['product_option_id'],
                                    'option_id'         => $option['option_id'],
                                    'selected'          => isset($product['options'][$option['product_option_id']]),
                                    'name'              => $option['name'],
                                    'type'              => $option['type'],
                                    'option_value'      => isset($product['options'][$option['product_option_id']]) ? $product['options'][$option['product_option_id']] : $option['option_value'],
                                    'required'          => $option['required']
                                );
                            }
                        }
                        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            $price_product = $this->currency->format($this->tax->calculate(
                                $real_price,
                                $set_info['tax_class_id'],
                                $this->config->get('config_tax')
                            ));
                        } else {
                            $price_product = false;
                        }
                        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            $price_in_set = $this->currency->format($this->tax->calculate(
                                $product['price_in_set'],
                                $set_info['tax_class_id'],
                                $this->config->get('config_tax')
                            ));
                        } else {
                            $price_in_set = false;
                        }
                        
                        $sale_value = round((((float)$real_price - (float)$product['price_in_set']) / (float)$real_price) * 100);
                        
                        if (!$product['base_status'] || !$product['base_quantity']) {
                            $active_product = false;
                        }
                        
                        if (!$active_product) {
                            $this->data['active_set'] = false;
                        }
                        
                        $this->data['products'][] = array(
                            'product_id'     => $product['product_wop_id'],
                            'name'           => $product['name'],
                            'present'        => $product['present'],
                            'option_set'     => $product['options'],
                            'options'        => $product_options,
                            'thumb'          => $image,
                            'active'         => $active_product,
                            'price'          => $price_product,
                            'price_in_set'   => $price_in_set,
                            'sale_value'     => $sale_value,
                            'text_salevalue' => sprintf($this->language->get('text_salevalue'), $sale_value) . '%',
                            'quantity'       => (int)$product['quantity'],
                            'text_quantity'  => sprintf($this->language->get('text_quantity'), $product['quantity']),
                            'href'           => $this->url->link(
                                'product/product',
                                'product_id=' . $product['product_id']
                            )
                        );
                        
                        $old_total += (float)$real_price * (int)$product['quantity'];
                    }
                }
                $temp_save = (float)$old_total - (float)$set_info['price'];
            // var_dump($set_pricing['save']);
                $set_save = $this->currency->format($this->tax->calculate(
                    $set_pricing['save'],
                    $set_info['tax_class_id'],
                    $this->config->get('config_tax')
                ));
                
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $set_price = $this->currency->format($this->tax->calculate(
                        $set_pricing['special'],
                        $set_info['tax_class_id'],
                        $this->config->get('config_tax')
                    ));
                } else {
                    $set_price = false;
                }
                
                
                $this->data['set_price'] = $set_price;
                $this->data['set_save'] = $set_save;
            }
            
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/setproductform.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/setproductform.tpl';
            } else {
                $this->template = 'default/template/module/setproductform.tpl';
            }
            
            return $this->render();
            // $this->response->setOutput($this->render());
        }
        
        private function parseText($node, $keyword, $dom, $link, $target = '', $tooltip = 0)
        {
            if (mb_strpos($node->nodeValue, $keyword) !== false) {
                $keywordOffset = mb_strpos($node->nodeValue, $keyword, 0, 'UTF-8');
                $newNode = $node->splitText($keywordOffset);
                $newNode->deleteData(0, mb_strlen($keyword, 'UTF-8'));
                $span = $dom->createElement('a', $keyword);
                if ($tooltip) {
                    $span->setAttribute('href', '#');
                    $span->setAttribute('style', 'text-decoration:none');
                    $span->setAttribute('class', 'title');
                    $span->setAttribute('title', $keyword . '|' . $link);
                } else {
                    $span->setAttribute('href', $link);
                    $span->setAttribute('target', $target);
                //$span->setAttribute('style', 'text-decoration:none');
                }
                
                $node->parentNode->insertBefore($span, $newNode);
                $this->parseText($newNode, $keyword, $dom, $link, $target, $tooltip);
            }
        }
        
        public function updateOptionInfo()
        {
            $json = array();
            
            
            if (!isset($this->request->post['product_id'])) {
                $this->response->setOutput(json_encode($json));
            }
            
            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            
            $product_id = $this->request->post['product_id'];
            
        // AceShop\Mijoshop Support
            if (isset($this->request->post['option_oc']) && is_array($this->request->post['option_oc'])) {
                $post_option = $this->request->post['option_oc'];
            } elseif (isset($this->request->post['option']) && is_array($this->request->post['option'])) {
                $post_option = $this->request->post['option'];
            }
            
            
            # Get product options and add it to the current price
            if (!empty($post_option)) {
                foreach ($post_option as $option_id => $option_value_id) {
                //checkbox massaging
                    if (is_array($option_value_id)) {
                        $option_value_id = end($option_value_id);
                    }
                    
                    if ($option_value_id != $this->request->post['option_value_id']) {
                        continue;
                    }
                    $query = $this->db->query("SELECT * FROM product_option_value pov LEFT JOIN option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value_id . "' AND pov.product_id = '" . (int)$product_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
                    if ($query->num_rows) {
                    // Allow html in misc info field
                        $query->row['ob_info'] = html_entity_decode($query->row['ob_info']);
                        
                        $json = $query->row;
                        
                        $json['product_name'] = '';
                        $json['product_code'] = '';
                        $json['product_model'] = '';
                        $json['price'] = '';
                        $json['special'] = '';
                        $json['description'] = '';
                        $json['stock_status_id'] = '';
                        $json['can_not_buy'] = false;
                        
                        if ($query->row['this_is_product_id'] && $real_product = $this->model_catalog_product->getProduct($query->row['this_is_product_id'])) {
                            $attribute_groups = $this->model_catalog_product->getProductAttributes($real_product['product_id']);
                            
                            $attributes = array();
                            $special_attribute_group_id = $this->config->get('config_special_attr_id');
                            
                            foreach ($attribute_groups as $attribute_group) {
                                if ($attribute_group['attribute_group_id'] != $special_attribute_group_id) {
                                    foreach ($attribute_group['attribute'] as $attribute) {
                                        if ($attribute['sort_order'] != '-1') {
                                            $attributes[] = array(
                                                'name' => $attribute['name'],
                                                'text' => $attribute['text'],
                                            );
                                        }
                                    }
                                }
                            }
                            
                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                $price = $this->currency->format($this->tax->calculate(
                                    $real_product['price'],
                                    $real_product['tax_class_id'],
                                    $this->config->get('config_tax')
                                ));
                            } else {
                                $price = false;
                            }
                            
                            if ((float)$real_product['special']) {
                                $special = $this->currency->format($this->tax->calculate(
                                    $real_product['special'],
                                    $real_product['tax_class_id'],
                                    $this->config->get('config_tax')
                                ));
                            } else {
                                $special = false;
                            }
                            
                            if ($real_product) {
                                $json['product_name'] = $real_product['name'];
                                $json['product_code'] = $real_product['product_id'];
                                $json['product_model'] = $real_product['model'];
                                $json['stock_status_id'] = $real_product['stock_status_id'];
                                $json['can_not_buy'] = ($real_product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id'));
                                $json['stock_color'] = ($real_product['stock_status_id'] == $this->config->get('config_stock_status_id')) ? '#4C6600' : '#BA0000';
                                $json['price'] = $price;
                                $json['special'] = $special;
                                $json['attributes'] = $attributes;
                                $json['description'] = html_entity_decode($real_product['description']);
                            }
                        }
                        
                        if ($query->row['ob_image']) {
                            $json['ob_swatch'] = $this->model_tool_image->resize($query->row['ob_image'], 40, 40);
                            $json['ob_thumb'] = $this->model_tool_image->resize(
                                $query->row['ob_image'],
                                $this->config->get('config_image_thumb_width'),
                                $this->config->get('config_image_thumb_height')
                            );
                            $json['ob_popup'] = $this->model_tool_image->resize(
                                $query->row['ob_image'],
                                $this->config->get('config_image_popup_width'),
                                $this->config->get('config_image_popup_height')
                            );
                        } elseif (isset($query->row['image']) && $query->row['image'] != 'no_image.jpg') {
                            $json['ob_swatch'] = $this->model_tool_image->resize($query->row['image'], 40, 40);
                            $json['ob_thumb'] = $this->model_tool_image->resize(
                                $query->row['image'],
                                $this->config->get('config_image_thumb_width'),
                                $this->config->get('config_image_thumb_height')
                            );
                            $json['ob_popup'] = $this->model_tool_image->resize(
                                $query->row['image'],
                                $this->config->get('config_image_popup_width'),
                                $this->config->get('config_image_popup_height')
                            );
                        } else {
                            $json['ob_swatch'] = '';
                            $json['ob_thumb'] = '';
                            $json['ob_popup'] = '';
                        }
                    }
                }
            }
            
            $this->response->setOutput(json_encode($json));
        }
    }
