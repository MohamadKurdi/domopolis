<?php
class ControllerApiInfo1C extends Controller
{

    private $SoapClient;

    private function check_key($array)
    {
        $gen_key = false;
        return (sha1($array['operation_id']) == mb_strtolower(str_replace(' ', '', $array['key'])));
    }

    public function getOrderJSON($order_id = 0)
    {
        $query = $this->db->query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");

        if ($query->num_rows) {
            $this->response->setOutput(json_encode($query->row));
        } else {
            die("order $order_id doesn't exist");
        }
    }

    public function addTTNHistory($order_id, $ttn, $shipping_code = '')
    {
        $this->load->model('sale/order');
        $this->load->model('setting/setting');
        $ttn = trim($ttn);


        if (!$order_info = $this->model_sale_order->getOrder($order_id)) {
            die("order $order_id doesn't exist");
        }

        if ($this->model_sale_order->getIfOrderClosed($order_id)) {
            die("order $order_id is closed ");
        }

        if (!$shipping_code) {
            $shipping_code = $order_info['shipping_code'];
        }

        $existingTTNS = $this->model_sale_order->getOrderTtnHistory($order_id);
        foreach ($existingTTNS as $existingTTN) {
            if ($ttn == trim($existingTTN['ttn'])) {
                die("ttn $ttn already added");
            }
        }

        $this->db->query("UPDATE `order` SET `ttn` = '" . $this->db->escape($ttn) . "' WHERE order_id = " . (int)$order_id);
        $adding_query = $this->db->query("INSERT IGNORE INTO `order_ttns` SET	order_id = '" . (int)$order_id . "', ttn = '" . $this->db->escape($ttn) . "', delivery_code = '" . $this->db->escape($shipping_code) . "', date_ttn = NOW(), sms_sent = NOW()");
       
        if ($adding_query->num_rows){
            $this->smsAdaptor->sendDeliveryNote($order_info, ['ttn' => $ttn, 'order_status_id' => $order_info['order_status_id']]);
        }
    }

    public function ping1CToUpdateProducts()
    {
        $this->load->model('kp/info1c');
        $this->load->model('kp/product');

        if ($products = $this->rainforestAmazon->offersParser->PriceLogic->priceUpdaterQueue->getQueue()) {
            $this->model_kp_info1c->ping1CToUpdateProducts($products);
            $this->model_kp_product->reindexElastic($products);
            $this->rainforestAmazon->offersParser->PriceLogic->priceUpdaterQueue->cleanQueue();
        }
    }

    public function describeTableFields($table)
    {

        $query = $this->db->query("show full columns from `" . $this->db->escape($table) . "`");

        $result = array();
        foreach ($query->rows as $row) {
            $result[] = array(
                'field' => $row['Field'],
                'type'  => $row['Type'],
                'comment'  => $row['Comment'],
            );
        }

        $this->response->setOutput(json_encode($result));
    }

    public function describeProductFields()
    {

        $query = $this->db->query("show full columns from `product`");

        $result = array();
        foreach ($query->rows as $row) {
            $result[] = array(
                'field'     => $row['Field'],
                'type'      => $row['Type'],
                'comment'   => $row['Comment'],
            );
        }

        $this->response->setOutput(json_encode($result));
    }

    public function editProductFields($product_id, $fields)
    {

        $prepared   = $this->db->prepareUpdateQuery('product', $fields);
        $result     = $this->db->doUpdateQuery('product', $product_id, 'product_id', $prepared['result']);

        $this->response->setJSON(array_merge($prepared, $result));
    }

    public function describeOrderFields()
    {

        $query = $this->db->query("show full columns from `order`");

        $result = array();
        foreach ($query->rows as $row) {
            $result[] = array(
                'field'     => $row['Field'],
                'type'      => $row['Type'],
                'comment'   => $row['Comment'],
            );
        }

        $this->response->setOutput(json_encode($result));
    }

    public function editOrderFields($order_id, $fields)
    {
        $jsonResultFields = array();
        foreach ($fields as $field => $value) {
            if (in_array($field, ['order_id', 'ttn'])) {
                die("You can not change $field in ORDER table");
            }


            $check = $this->db->query("SHOW FULL COLUMNS FROM `order` LIKE '" . $this->db->escape($field) . "'");

            if (!$check->num_rows) {
                die("no $field in ORDER table");
            }

            if ($check->row['Field'] == 'courier_id') {
                $check->row['Type'] = 'varchar';
            }

            if (mb_stripos($check->row['Type'], 'int') !== false) {
                if (!is_numeric($value)) {
                    die("invalid data type for $field, must be integer");
                }

                $type = 'int';
            }

            if (mb_stripos($check->row['Type'], 'varchar') !== false) {
                $type = 'varchar';
            }

            if (mb_stripos($check->row['Type'], 'text') !== false) {
                $type = 'varchar';
            }

            if (mb_stripos($check->row['Type'], 'decimal') !== false) {
                if (!is_numeric($value)) {
                    die("invalid type for $field, must be float");
                }

                $type = 'decimal';
            }

            if (mb_stripos($check->row['Type'], 'datetime') !== false) {
                if (date('Y-m-d H:i:s', strtotime($value)) != $value) {
                    die("invalid data type for $field, must be datetime Y-m-d H:i:s");
                }

                $type = 'datetime';
            }

            if (mb_stripos($check->row['Type'], 'date') !== false) {
                if (date('Y-m-d', strtotime($value)) != $value) {
                    die("invalid data type for $field, must be date Y-m-d");
                }

                $type = 'date';
            }

            $jsonResultFields[] = array(
                'name'  => $check->row['Field'],
                'value' => $value,
                'type'  => $type
            );
        }



        $sql = "UPDATE `order` SET ";

        foreach ($jsonResultFields as $field) {
            if ($field['name'] == 'courier_id') {
                $this->load->model('user/user');
                $field['value'] = $this->model_user_user->getUserIDByUsername($field['value']);
            }

            if ($field['type'] == 'int') {
                $implode[] = " `" . $field['name'] . "` = '" . (int)$field['value'] . "'";
            }

            if ($field['type'] == 'decimal') {
                $implode[] = " `" . $field['name'] . "` = '" . (float)$field['value'] . "'";
            }

            if ($field['type'] == 'varchar') {
                $implode[] = " `" . $field['name'] . "` = '" . $this->db->escape($field['value']) . "'";
            }

            if ($field['type'] == 'date') {
                $implode[] = " `" . $field['name'] . "` = '" . date('Y-m-d', strtotime($field['value'])) . "'";
            }

            if ($field['type'] == 'datetime') {
                $implode[] = " `" . $field['name'] . "` = '" . date('Y-m-d H:i:s', strtotime($field['value'])) . "'";
            }
        }

        $implode[] = " `date_modified` = NOW() ";


        $sql .= implode(',', $implode);

        $sql .= " WHERE order_id = '" . (int)$order_id . "'";

        $this->db->query("INSERT INTO `order_save_history` SET 
			`order_id` = '" . (int)$order_id . "', 
			`user_id` = '" . 54 . "',
			`data` = '',
			`datetime` = NOW()
			");

        $this->db->query($sql);

        $this->response->setOutput('SQL EXECUTED:' . $sql);
    }

    public function getOrdersInCourierServiceJSON()
    {

        $order_data = array();

            //На пвз
        $query = $this->db->query("SELECT order_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_in_pickup_status_id') . "'");

        foreach ($query->rows as $row) {
            $order_data[] = $row['order_id'];
        }

            //Доставляется по москве
        $query = $this->db->query("SELECT order_id FROM `order` WHERE shipping_country_id = 176 AND 
			(shipping_city LIKE '%Москва%'
				OR shipping_city LIKE '%Moscow%'
				OR shipping_city LIKE '%москва%'
				)
			AND order_status_id = 2
			");

        foreach ($query->rows as $row) {
            $order_data[] = $row['order_id'];
        }

        $order_data = array_unique($order_data);

        $this->response->setOutput(json_encode($order_data));
    }

    public function getOrdersInCourierService()
    {

        $order_data = array();
        $query = $this->db->query("SELECT order_id FROM `order` WHERE order_status_id = 25");

        foreach ($query->rows as $row) {
            $order_data[] = $row['order_id'];
        }
            
        $query = $this->db->query("SELECT order_id FROM `order` WHERE shipping_country_id = 176 AND 
			(shipping_city LIKE '%Москва%'
				OR shipping_city LIKE '%Moscow%'
				OR shipping_city LIKE '%москва%'
				)
			AND order_status_id = 2
			");

        foreach ($query->rows as $row) {
            $order_data[] = $row['order_id'];
        }

        $order_data = array_unique($order_data);

        $this->response->setOutput(implode(',', $order_data));
    }

    public function getStockWaitsFrom1C()
    {
        $this->load->model('kp/info1c');

        if (!$this->config->get('config_odinass_update_stockwaits')){
            echoLine('[info1c::getStockWaitsFrom1C] config_odinass_update_stockwaits is disabled!', 'e');
            return;
        }

        if (!$stockwaits1c = $this->model_kp_info1c->getStockWaitsFrom1C()){
            return;
        }

        $stockwaits = array();

        foreach (json_decode($stockwaits1c, true)['items'] as $stockwait) {
            $idx = $stockwait['product_id'].':'.$stockwait['quantity_stock'];

            if (!isset($stockwaits[$idx])) {
                $stockwaits[$idx] = array(
                    'quantity'      => $stockwait['quantity'],
                    'warehouse'     => $stockwait['quantity_stock'],
                    'product_id'    => $stockwait['product_id']
                );
            } else {
                $stockwaits[$idx] = array(
                    'quantity'      => $stockwaits[$idx]['quantity'] + $stockwait['quantity'],
                    'warehouse'     => $stockwait['quantity_stock'],
                    'product_id'    => $stockwait['product_id']
                );
            }
        }

        $tmp_stockwaits = [];
        unset($stockwait);
        foreach ($stockwaits as $stockwait) {
            if (empty($tmp_stockwaits[$stockwait['product_id']])) {
                $tmp_stockwaits[$stockwait['product_id']] = [];
            }

            if ($stockwait['warehouse']) {
                $tmp_stockwaits[$stockwait['product_id']][$stockwait['warehouse']] = $stockwait['quantity'];
            }
        }

        $warehouseArray = ['quantity_stock', 'quantity_stockM', 'quantity_stockK'];
        $this->db->query('DELETE FROM product_stock_waits WHERE 1');

        $implode = [];
        foreach ($warehouseArray as $warehouseSingle) {
            $implode[] = ' `' . $warehouseSingle . '_onway' . '` = 0';
        }

        $this->db->query('UPDATE product SET ' . implode(',', $implode) . ' WHERE 1');

        unset($stockwait);
        foreach ($tmp_stockwaits as $product_id => $stockwait) {
            $this->db->query("INSERT INTO product_stock_waits SET product_id = '" . (int)$product_id . "'");

            foreach ($stockwait as $warehouse => $quantity) {
                echoLine('[stockwaits] Товар ' . $product_id . ', склад ' . $warehouse . ', Количество ' . $quantity);

                $this->db->query("UPDATE product_stock_waits SET `" . $warehouse . "` = '" . (int)$quantity  . "' WHERE product_id = '" . (int)$product_id . "'");

                if (in_array($warehouse, $warehouseArray)) {
                    $this->db->query("UPDATE product SET `" . $warehouse . '_onway' . "` = '" . (int)$quantity  . "' WHERE product_id = '" . (int)$product_id . "'");
                }
            }
        }
    }

    public function updateLegalPersonAccountsCron()
    {
        $this->load->model('kp/info1c');
        $this->load->model('localisation/legalperson');

        $legalpersons = $this->model_localisation_legalperson->getLegalPersons();

        foreach ($legalpersons as $legalperson) {
            $info = $this->model_kp_info1c->getLegalPersonAccountFrom1C($legalperson['legalperson_id']);
            $this->db->query("UPDATE legalperson SET account_info = '" . $this->db->escape(serialize($info)) . "' WHERE legalperson_id = '" . (int)$legalperson['legalperson_id'] . "'");
        }
    }

    public function passWebServiceProxy($function, $param_name, $string_data)
    {

        $this->load->model('kp/info1c');
        $result = $this->model_kp_info1c->passWebServiceProxy($function, $param_name, $string_data);

        $this->response->setOutput($result);
    }

    public function getOrderSales($order_id = false, $delivery_num = false)
    {
        if (!$order_id) {
            $order_id = $this->request->get['order_id'];
        }

        if (!$delivery_num) {
            $delivery_num = $this->request->get['delivery_num'];
        }

        $this->load->model('feed/exchange1c');
        $this->model_feed_exchange1c->makeSalesResultXML($order_id, true, false, false, $delivery_num);
    }

    public function makeSalesResultXML($order_id = false, $delivery_num = false)
    {
        if (!$order_id) {
            $order_id = $this->request->get['order_id'];
        }

        if (!$delivery_num) {
            $delivery_num = $this->request->get['delivery_num'];
        }

        $this->load->model('feed/exchange1c');
        $this->model_feed_exchange1c->makeSalesResultXML($order_id, true, false, false, $delivery_num);
    }

    public function updateLocalPricesXML()
    {
        if (!$this->config->get('config_odinass_update_local_prices')) {
            echoLine('РРЦ отключено!');
            return;
        }

        $this->load->model('kp/info1c');
        $this->load->model('catalog/product');
        $this->load->model('sale/supplier');
        $this->load->model('setting/setting');
        $this->load->model('setting/store');
        echo 'Получаем JSON из 1С' . PHP_EOL;
        $json = $this->model_kp_info1c->getLocalPricesXML();

        echo 'Всего товаров:' . count($json) . PHP_EOL;

        $stores = $this->model_setting_store->getStores();
        $currency_mapping = array(
            '0' => $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', 0)
        );

        foreach ($stores as $store) {
            $currency_mapping[$store['store_id']] = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']);
        }

        $currency_mapping = array_flip($currency_mapping);

        foreach ($json as $key => $data) {
            if ($product = $this->model_catalog_product->getProduct(trim($key))) {
                echo '[i] Товар ' . $key . ' найден' . PHP_EOL;
                $this->model_catalog_product->clearProductStorePricesNational($product['product_id']);


                foreach ($data as $price) {
                    if (!empty($price['supplier'])) {
                        $supplier = $this->model_sale_supplier->getSupplierByName($price['supplier']);
                        $supplier_id = $supplier['supplier_id'];
                        echo '[ss] Поставщик ' . $price['supplier'] . ' найден, ID' . $supplier_id . ' валюта ' . $price['currency'] . PHP_EOL;

                        if (!$supplier_id) {
                            $supplier_data = array(
                                'supplier_name' => $this->db->escape(trim($price['supplier'])),
                                'supplier_code' => mb_strtoupper(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $price['supplier'])),
                                'supplier_type' => 'Официальный магазин сторонних поставщиков',
                                'supplier_country' => $price['currency'],
                                'supplier_comment' => '',
                                'supplier_m_coef' => '',
                                'supplier_l_coef' => '',
                                'supplier_n_coef' => '',
                                'supplier_parent_id' => '0',
                                'sort_order' => '0',
                                'supplier_inner' => '1'
                            );


                            echo '[ss] Поставщик ' . $price['supplier'] . ' не найден, добавлен' . PHP_EOL;
                            $supplier_id = $this->model_sale_supplier->addSupplier($supplier_data);
                        }

                        $availability = ($price['availability'] == 'true')?100:0;

                        if (!empty($price['cost'])) {
                            $cost = $price['cost'];
                        } else {
                            $cost = ($price['price']*0.6);
                        }

                            //Запишем сразу в табличку наличия у локального поставщика
                        $this->db->query("
							INSERT INTO local_supplier_products SET 
							supplier_id = '" . (int)$supplier_id . "',
							supplier_product_id = '" . (int)0 . "',
							product_id = '" . (int)$product['product_id'] . "',
							product_model = '" . $this->db->escape($product['model']) . "',
							product_ean = '',
							price = '" . (float)$cost . "',
							price_recommend = '" . (float)$price['price'] . "',
							currency = '" . $this->db->escape($price['currency']) . "',
							stock = '" . $availability . "',
							product_xml = '" . base64_encode(serialize($price)) . "'
							ON DUPLICATE KEY UPDATE
							supplier_product_id = '" . (int)0 . "',
							product_model = '" . $this->db->escape($product['model']) . "',
							product_ean = '',
							price = '" . (float)$cost . "',
							price_recommend = '" . (float)$price['price'] . "',
							currency = '" . $this->db->escape($price['currency']) . "',
							stock = '" . $availability . "',
							product_xml = '" . base64_encode(serialize($price)) . "'
							");
                    }

                    //установка РРЦ в товар
                    //удаляем все записи в переназначении на магазын

                    $store_id = 0;
                    if (isset($currency_mapping[$price['currency']])) {
                        $store_id = $currency_mapping[$price['currency']];
                    }

                    echo '[i] Доабвлена РРЦ ' . $price['price'] . ' ' . $price['currency'] . ', магазин ' . $store_id . PHP_EOL;

                    if ($price['price'] > 0) {
                        $this->db->query("INSERT IGNORE INTO product_price_national_to_store SET store_id = '" . (int)$store_id . "', product_id = '" . (int)$product['product_id'] . "', price = '" . (float)$price['price'] . "', dot_not_overload_1c = '0', settled_from_1c = '1',  currency = '" . $this->db->escape($price['currency']) . "'");
                    }
                }
            } else {
                echo '[i] Товар ' . $key . ' не найден' . PHP_EOL;
            }
        }
    }

    public function getAllVouchersList()
    {
        $this->load->model('sale/voucher');

        $data = array(
            'start' => 0,
            'limit' => 4000,
            'sort'  => 'v.date_added',
            'order' => 'DESC'
        );

        $results = $this->model_sale_voucher->getVouchers($data);

        $return = array();
        foreach ($results as $result) {
            $voucher_histories = $this->model_sale_voucher->getVoucherHistories($result['voucher_id']);
            $voucher_history = array();

            foreach ($voucher_histories as $history) {
                $voucher_history[] = array(
                    'order_id'      => $history['order_id'],
                    'customer'      => $history['customer'],
                    'date_added'    => date('Y-m-d', strtotime($history['date_added'])),
                );
            }

            $total_uses = $this->model_sale_voucher->getTotalVoucherFromOrderTotal($result['code']);

            $return[] = array(
                'voucher_id'    => $result['voucher_id'],
                'code'          => $result['code'],
                'amount'        => $result['amount'],
                'currency'      => $result['curr'],
                'isActive'      => (count($voucher_history) == 0 && $total_uses == 0),
                'total_uses'    => $total_uses,
                'history'       => $voucher_history
            );
        }

        $this->response->setOutput(json_encode($return));
    }

    public function getFullWaitlist()
    {
        $this->load->model('catalog/product');

        $data = array(
            'start' => 0,
            'limit' => 4000
        );

        $results = $this->model_catalog_product->getProductsWaitList($data);

        $return = array();
        foreach ($results as $result) {
            $return[] = array(
                'waitlist_id'   => $result['order_product_id'],
                'date_added'    => $result['date_added'],
                'store_id'      => $result['store_id'],
                'order_id'      => $result['order_id'],
                'currency'      => $result['currency'],
                'quantity'      => $result['quantity'],
                'product'       => array(
                    'product_id'    => $result['product_id'],
                    'product_model' => $result['model'],
                    'product_name'  => $result['name'],
                    'product_sku'   => $result['sku'],
                    'product_ean'   => $result['ean'],
                    'product_asin'  => $result['asin'],
                    'product_price_in_waitlist' => $result['price_in_order'],
                ),
                'customer' => array(
                    'customer_id'           => $result['customer_id'],
                    'customer_firstname'    => $result['firstname'],
                    'customer_lastname'     => $result['lastname'],
                )
            );
        }

        $this->response->setOutput(json_encode($return));
    }

    public function getOrderTrackerXML($order_id = false)
    {

        if (!$order_id) {
            $order_id = $this->request->get['order_id'];
        }

        $this->load->model('kp/info1c');
        $order1c = $this->model_kp_info1c->getOrderTrackerXML($order_id);

        print_r('<pre>');
        print_r($order1c);
        print_r('</pre>');

        $this->load->model('sale/order');
        $this->load->model('localisation/order_status');

        $attach = array();
        $this->load->model('sale/order');
        $this->load->model('user/user');

        $order = $this->model_sale_order->getOrder($order_id);
        $order_status = $this->model_localisation_order_status->getOrderStatusName($order['order_status_id']);
        $order_manager_bitrix_id = $this->model_user_user->getUserBitrixIDByID($order['manager_id']);

        $order1c = $order1c["Документ"];
        $reply = "Заказ [B]#". $order1c['ИД'] . "[/B], от ". date('Y.m.d', strtotime($order1c['ДатаЗаказа']));
        $reply .= "[BR]";
        $reply .= "Сейчас в статусе: [B]" . $order_status . "[/B]";

        if ($order_manager_bitrix_id) {
            $reply .= "[BR]";
            $reply .= "Ответственный: [USER=$order_manager_bitrix_id]" . $this->model_user_user->getRealUserNameById($order['manager_id']) . "[/USER]";
        }

        $grid = array();

        if (isset($order1c['ОбщееСостояниеЗаказа']['Товар']['Наименование'])) {
            $_tmp = $order1c['ОбщееСостояниеЗаказа']['Товар'];
            unset($order1c['ОбщееСостояниеЗаказа']['Товар']);
            $order1c['ОбщееСостояниеЗаказа']['Товар'] = array($_tmp);
        }

        foreach ($order1c['ОбщееСостояниеЗаказа']['Товар'] as $product) {
            $msg = '';

            $order_product = $this->model_sale_order->findOrderProductsLine($order['order_id'], trim($product['Код']), trim($product['ЗаказаноПокупателем']));
            if ($order_product) {
                if ($order_product['distinct']) {
                    if ($order_product['row']['part_num']) {
                        $msg = ' :idea: Товар в партии: ' . $order_product['row']['part_num'];
                    }
                }
            }



            if ($product['ОжидаютОтгрузкиПокупателю'] > 0) {
                $msg .= ":idea: " . $product['ОжидаютОтгрузкиПокупателю'] . " шт. ждет доставки покупателю";

                if (isset($product['ОжидаютОтгрузкиПокупателюДетально'])) {
                    $_mmsg = array();
                    $free_counter = 0;
                    foreach ($product['ОжидаютОтгрузкиПокупателюДетально'] as $partie) {
                        echo '<pre>';
                        print_r($partie);
                        echo '</pre>';

                        if (!isset($partie["Номер"]) && isset($partie[0]) && is_array($partie[0])) {
                            foreach ($partie as $subpartie) {
                                $_mmsg[] = "[I][B]" . $subpartie['Номер'] . "[/B]: " . $subpartie['Количество'] . " шт.[/I]";
                            }
                        }

                        if (isset($partie["ЭтоСвободныеОстатки"]) && $partie["ЭтоСвободныеОстатки"]) {
                            $free_counter += $partie['Количество'];
                        }

                        if (isset($partie["Номер"]) && isset($partie['Количество'])) {
                            $_mmsg[] = "[I][B]" . $partie['Номер'] . "[/B]: " . $partie['Количество'] . " шт.[/I]";
                        }


                        if (count($_mmsg)) {
                            $msg .= " (".implode(', ', $_mmsg).")";
                        }
                    }
                }

                $is_from_free = ($product['ОжидаютОтгрузкиПокупателю'] == $free_counter);
            }
        }
    }

    private function updateStockXML($xml, $update, $updateStockGroups)
    {        
        $log_odinass = new Log('import-from-1c.log');
        $log_odinass->write('> Начало загрузки');

        $xml = htmlspecialchars_decode($xml);

        try {
            $xtoa  = new \AlexTartan\Array2Xml\XmlToArray(['version'=>'1.0','encoding'=>'UTF-8','attributesKey' => '@attributes','cdataKey'=>'@cdata','valueKey'=>'@value','formatOutput'  => false]);
            $input = $xtoa->buildArrayFromString($xml);
        } catch (\AlexTartan\Array2Xml\Exception\ConversionException $e) {
            die('Ошибка разбора XML. ' . $e->getMessage());
        }            

        if ($input && is_array($input) && isset($input['document']) && is_array($input['document'])) {
            $input = $input['document'];
        } else {
            die('Ошибка разбора XML');
        }

        if ($input['mode'] == 'products') {
            if ($this->check_key($input)) {
                $this->load->model('localisation/country');
                $stocks = array();

                $countries = $this->model_localisation_country->getCountries();
                foreach ($countries as $country) {
                    if ($country['warehouse_identifier']) {
                        $stocks[$country['warehouse_identifier']] = $country['name'];
                    }
                }

                if (isset($input['products']['product']['product_id'])) {
                    $input['products']['product'] = array($input['products']['product']);
                }

                $log_odinass->write('>> Товары');
                $this->db->query("UPDATE `product` SET `quantity_updateMarker` = '0' WHERE 1");

                $count = 0;
                $total_count = 0;
                $products_in_stock = $products_in_stock_msk = $products_in_stock_kyiv = $products_in_stock_de = [];
                $total_p_on_stocks = $total_q_on_stocks = [];                

                foreach ($stocks as $k => $v) {
                    $total_p_on_stocks[$k] = 0;
                    $total_q_on_stocks[$k] = 0;
                }                

                $unique_products = array();
                foreach ($input['products']['product'] as $value) {    

                    if (!isset($unique_products[(int)($value['product_id'])])) {
                        $unique_products[(int)($value['product_id'])] = [];
                    }

                    foreach ($stocks as $k => $v) {
                        if (isset($value[$k])) {
                            $unique_products[(int)($value['product_id'])][$k] = $value[$k];
                            $total_count += $value[$k];
                        }
                    }

                    if (isset($value['actual_cost'])) {
                        $unique_products[($value['product_id'])]['actual_cost'] = (float)str_replace(',', '.', $value['actual_cost']);
                    }

                    if (isset($value['SebestoimostK'])) {
                        $unique_products[($value['product_id'])]['costK'] = (float)str_replace(',', '.', $value['SebestoimostK']);
                    }

                    if (isset($value['SebestoimostM'])) {
                        $unique_products[($value['product_id'])]['costM'] = (float)str_replace(',', '.', $value['SebestoimostM']);
                    }

                    if (isset($value['MVTCK'])) {
                        $unique_products[($value['product_id'])]['min_sale_priceK'] = (float)str_replace(',', '.', $value['MVTCK']);
                    }

                    if (isset($value['MVTCM'])) {
                        $unique_products[($value['product_id'])]['min_sale_priceM'] = (float)str_replace(',', '.', $value['MVTCM']);
                    }

                    if (isset($value['product_vendor'])) {
                        $unique_products[($value['product_id'])]['product_vendor'] = $value['product_vendor'];
                    }                    
                }
                    
                $log_odinass->write('[updateStockXML] Всего ' . count($unique_products) . ' товаров');                    
                $log_odinass->write('[updateStockXML] Всего ' . $total_count . ' единиц товаров');


                $bad_products = [];
                foreach ($unique_products as $key => &$value) {
                    $value['product_id'] = $key;

                    $query = $this->db->query("SELECT product_id, location FROM `product` WHERE product_id = '". (int)(trim($value['product_id'])) ."' LIMIT 1");
                    $product = $query->row;

                    if (!$product) {
                        if (isset($value['product_vendor']) && $value['product_vendor']) {
                            $log_odinass->write('Товара '.(int)($value['product_id']).' не существует, попробуем найти по артикулу: ' . $value['product_vendor'] . '');

                            $sku = preg_replace("([^0-9])", "", $value['product_vendor']);

                            $query = $this->db->query("SELECT product_id FROM `product` WHERE ((REPLACE(REPLACE(REPLACE(REPLACE(model,' ',''), '.', ''), '/', ''), '-', '') = '" . $this->db->escape($sku) . "' OR REPLACE(REPLACE(REPLACE(REPLACE(sku,' ',''), '.', ''), '/', ''), '-', '') = '" . $this->db->escape($sku) . "') AND LENGTH(model)>1 AND stock_product_id = 0) LIMIT 1");

                            $product = $query->row;

                            if ($product) {
                                $log_odinass->write('Нашли по артикулу: ' . $value['product_vendor'] . ', код товара: ' . $product['product_id'] . '');
                                echoLine('[updateStockXML] Нашли по артикулу: ' . $value['product_vendor'] . ', код товара: ' . $product['product_id'], 's');
                                $value['product_id'] = $product['product_id'];
                            } else {
                                $log_odinass->write('Товара '.(int)($value['product_id']).' не существует, артикула: ' . $value['product_vendor'] . ' также не существует');
                                echoLine('[updateStockXML] Товара '.(int)($value['product_id']).' не существует, артикула: ' . $value['product_vendor'] . ' также не существует', 'e');

                                $bad_products[$value['product_id']] = $value['product_vendor'];
                            }
                        }
                    }

                    foreach ($stocks as $k => $v) {
                        if (!isset($value[$k])) {
                            $value[$k] = '0';
                        }
                    }

                    if (!isset($value['actual_cost'])) {
                        $value['actual_cost'] = 0;
                    }

                    if (!isset($product['location'])) {
                        $product['location'] = '';
                    }

                    if ($product) {
                        if ($product['location'] == 'certificate' || $product['location'] == 'newcertificate'){
                            continue;
                        }

                        foreach ($stocks as $k => $v) {
                            if ($value[$k] > 0) {
                                $total_p_on_stocks[$k] += 1;
                                $total_q_on_stocks[$k] += $value[$k];
                            }
                        }

                        reset($stocks);

                        $total_in_stock = false;
                        foreach ($stocks as $k => $v) {
                            if ($value[$k] > 0) {
                                $total_in_stock = true;
                                break;
                            }
                        }

                        if (!empty($value['quantity_stockK'])) {
                            $products_in_stock_kyiv[] = $value['product_id'];
                            $this->rainforestAmazon->offersParser->PriceLogic->setProductStockInWarehouse($value['product_id'], 'quantity_stockK');
                        }

                        if (!empty($value['quantity_stockM'])) {
                            $products_in_stock_msk[] = $value['product_id'];
                            $this->rainforestAmazon->offersParser->PriceLogic->setProductStockInWarehouse($value['product_id'], 'quantity_stockM');
                        }

                        if (!empty($value['quantity_stock'])) {
                            $products_in_stock_de[] = $value['product_id'];
                        }

                        if ($total_in_stock) {
                            $products_in_stock[] = $value['product_id'];
                        }

                        $log_odinass->write('Товар '.(int)($value['product_id']).', данные ' . implode(',', $value));
                        echoLine('[updateStockXML] Товар '.(int)($value['product_id']).', данные ' . implode(',', $value));

                        $sql = ("UPDATE `product` SET 
							`quantity_stock`         = '" . (!empty($value['quantity_stock'])?(int)$value['quantity_stock']:'0') . "',
							`quantity_stockK` 	     = '" . (!empty($value['quantity_stockK'])?(int)$value['quantity_stockK']:'0') . "',
							`quantity_stockM` 	     = '" . (!empty($value['quantity_stockM'])?(int)$value['quantity_stockM']:'0') . "',
							`quantity_stockMN` 	     = '" . (!empty($value['quantity_stockM'])?(int)$value['quantity_stockM']:'0') . "',
							`quantity_stockAS` 	     = '" . (!empty($value['quantity_stockM'])?(int)$value['quantity_stockM']:'0') . "',
							`actual_cost` 	         = '" . (float)$value['actual_cost'] . "',
                            `quantity_updateMarker`   = 1	
							WHERE product_id = '" . (int)$value['product_id'] . "'");

                        $this->db->query($sql);

                        if (!empty($value['costM']) && !empty($value['min_sale_priceM'])) {
                            if ($this->config->get('config_warehouse_identifier') == 'quantity_stockM'){
                                $sql = "INSERT INTO product_costs SET 
                                product_id      = '" . (int)$value['product_id'] . "', 
                                store_id        = 0, 
                                cost            = '" . (float)$value['costM'] . "',
                                currency        = 'EUR',
                                min_sale_price  = '" . (float)$value['min_sale_priceM'] . "'
                                ON DUPLICATE KEY UPDATE
                                cost            = '" . (float)$value['costM'] . "',
                                min_sale_price  = '" . (float)$value['min_sale_priceM'] . "'";

                                $this->db->query($sql);
                            }
                        }

                        if (!empty($value['costK']) && !empty($value['min_sale_priceK'])) {
                            $value_store_id = 1;

                            if ($this->config->get('config_warehouse_identifier') == 'quantity_stockK'){
                                 $value_store_id = 0;
                            }

                            $sql = "INSERT INTO product_costs SET 
							product_id 		= '" . (int)$value['product_id'] . "', 
							store_id 		= '" . (int)$value_store_id . "', 
							cost 			= '" . (float)$value['costK'] . "',
							currency 		= 'EUR',
							min_sale_price 	= '" . (float)$value['min_sale_priceK'] . "'
							ON DUPLICATE KEY UPDATE
							cost 			= '" . (float)$value['costK'] . "',
							min_sale_price 	= '" . (float)$value['min_sale_priceK'] . "'";

                            $this->db->query($sql);
                        }

                        $count += $this->db->countAffected();
                    }
                }

                $this->db->query("UPDATE `product` SET `quantity_stock`= '0', `quantity_stockK`= '0', `quantity_stockM`= '0', `quantity_stockMN` = '0',`quantity_stockAS`= '0' WHERE `quantity_updateMarker` = '0'");                   
                $this->db->query("UPDATE `product` SET `stock_status_id` = '" . (int)$this->config->get('config_stock_status_id') . "' WHERE `quantity_updateMarker` = '0' AND stock_status_id = '" . (int)$this->config->get('config_in_stock_status_id') . "'");            
                
                if ($this->config->get('config_yam_fbs_campaign_id')){
                    $this->db->query("INSERT INTO yandex_stock_queue (yam_product_id, stock) SELECT yam_product_id, quantity_stockM FROM product WHERE (quantity_stockM > 0 OR yam_in_feed = 1) ON DUPLICATE KEY UPDATE stock = quantity_stockM");
                }

                $products_in_stock = array_unique($products_in_stock);
                $products_in_stock_msk = array_unique($products_in_stock_msk);
                $products_in_stock_kyiv = array_unique($products_in_stock_kyiv);
                $products_in_stock_de = array_unique($products_in_stock_de);                    
                $log_odinass->write('Всего в quantity_stockM ' . count($products_in_stock_msk) . ' товаров');                    
                $log_odinass->write('Всего в quantity_stockK ' . count($products_in_stock_kyiv) . ' товаров');                    
                $log_odinass->write('Всего в quantity_stock ' . count($products_in_stock_de) . ' товаров');

                $this->load->model('kp/product');
                if ($updateStockGroups) {                        
                    $this->db->query("DELETE FROM product_to_category WHERE category_id = 6475");
                    $this->db->query("DELETE FROM product_to_category WHERE category_id = 8307");
                    $this->db->query("DELETE FROM product_to_category WHERE category_id = 8308");
                        
                    $this->db->query("DELETE FROM product_to_category WHERE category_id = 6474 AND product_id NOT IN (SELECT product_id FROM product WHERE stock_product_id > 0)");

                    foreach ($products_in_stock_msk as $product_in_present_id) {
                        $this->model_kp_product->copyProductToPresent($product_in_present_id, 20, 2, true);
                            //  $this->model_kp_product->addToYobanyiChaliukActionProduct($product_in_present_id, 80);
                    }

                    foreach ($products_in_stock_msk as $product_in_present_id) {
                            //$this->model_kp_product->copyProductToPresentUA($product_in_present_id, 20, 2, true);
                    }

                    foreach ($products_in_stock_kyiv as $product_in_present_id) {
                        $this->model_kp_product->copyProductToPresentUA($product_in_present_id);
                    }

                    foreach ($products_in_stock_de as $product_in_de_id) {
                            //  $this->model_kp_product->copyProductToStock($product_in_de_id, 15, 10000, false);
                            //  $this->model_kp_product->addToYobanyiChaliukActionProduct($product_in_de_id, 80);
                    }

                    foreach ($products_in_stock as $product_in_stock_id) {
                            //$this->model_kp_product->copyProductToStock($product_in_stock_id);
                    }
                }

                $bad_log = new Log('invalid_products_from_1c.txt');
                echoLine('BAD PRODUCTS: ' . json_encode($bad_products), 'e');

                foreach ($stocks as $k => $v) {
                    $this->db->query("INSERT IGNORE INTO stocks_dynamics SET date_added = DATE(NOW()), warehouse_identifier = '" . $this->db->escape($k) . "', p_count = '" . (int)$total_p_on_stocks[$k] . "', q_count = '" . (int)$total_q_on_stocks[$k] . "' ON DUPLICATE KEY UPDATE p_count = '" . (int)$total_p_on_stocks[$k] . "', q_count = '" . (int)$total_q_on_stocks[$k] . "'");
                }

                $this->rainforestAmazon->offersParser->PriceLogic->setProductStockStatusesGlobal();
                $this->model_kp_product->setLastUpdate();
            }
        }
    }

    public function clisetProductStockStatusesGlobal(){
        $this->rainforestAmazon->offersParser->PriceLogic->setProductStockStatusesGlobal();
    }

    public function updateProductField($product_id, $field, $value)
    {
        $this->load->model('catalog/product');
        $result = $this->model_catalog_product->getProduct($product_id);

        if (!$result) {
            die('no product exist');
        }

        if ($field == 'product_id') {
            die("can't alter $field");
        }

        $query = $this->db->query("SHOW COLUMNS FROM product LIKE '" . $this->db->escape($field) . "'");
        if (!$query->num_rows) {
            die("no field $field exist in table");
        }

        $this->db->query("UPDATE product SET `" . $this->db->escape($field) . "` = '" . $this->db->escape($value) . "' WHERE product_id = '" . (int)$product_id . "'");
        $result2 = $this->model_catalog_product->getProduct($product_id);

        $array = array(
            'previous' => $result[$field],
            'current'  => $result2[$field]
        );

        $this->response->setOutput(json_encode($array));
    }

    public function setProductPrices($product_id, $prices = array())
    {
        $this->load->model('catalog/product');
        $this->load->model('setting/store');
        $this->load->model('setting/setting');
        $this->load->model('kp/price');

        $product = $this->model_catalog_product->getProduct($product_id);

        if (!$product) {
            echo json_encode(array('error' => 'product_not_exist'));
            die();
        }

            //Основные цены
        if (!isset($prices['prices'])) {
            echo json_encode(array('error' => 'no_prices_branch'));
            die();
        }

        if ($prices['prices']['main_price']['currency'] != $this->config->get('config_currency')) {
            $main_price = $this->currency->convert($prices['prices']['main_price']['price'], $prices['prices']['main_price']['currency'], $this->config->get('config_currency'));
        } else {
            $main_price = $prices['prices']['main_price']['price'];
        }

        if ($prices['prices']['main_cost']['currency'] != $this->config->get('config_currency')) {
            $main_cost = $this->currency->convert($prices['prices']['main_cost']['price'], $prices['prices']['main_cost']['currency'], $this->config->get('config_currency'));
        } else {
            $main_cost = $prices['prices']['main_cost']['price'];
        }

        if ($prices['prices']['mpp_price']['currency'] != $this->config->get('config_currency')) {
            $mpp_price = $this->currency->convert($prices['prices']['mpp_price']['price'], $prices['prices']['mpp_price']['currency'], $this->config->get('config_currency'));
        } else {
            $mpp_price = $prices['prices']['mpp_price']['price'];
        }

            /*
                $this->db->query("UPDATE product SET
                price           = '" . (float)$main_price . "',
                cost            = '" . (float)$main_cost . "',
                mpp_price       = '" . (float)$mpp_price . "',
                price_national  = '" . (float)$prices['prices']['price_national']['price'] . "',
                currency        = '" . $this->db->escape($prices['prices']['price_national']['currency']) . "'
                WHERE product_id = '" . (int)$product_id . "'"
                );
            */

                $this->db->query("UPDATE product SET 
					price 				= '" . (float)$main_price . "', 
					cost 				= '0', 
					mpp_price 			= '" . (float)$mpp_price . "',			
					price_national 		= '" . (float)$prices['prices']['price_national']['price'] . "',
					currency 			= '" . $this->db->escape($prices['prices']['price_national']['currency']) . "'
					WHERE product_id 	= '" . (int)$product_id . "'");


            //Цены для Яндекс Маркета, пока обрабатываем c исключением, потому что не факт
        if (!empty($prices['prices']['yandex_market']) && $this->config->get('config_yam_enable_sync_from_1c')) {
            $yam_price = '';
            if (!empty($prices['prices']['yandex_market']['yam_price'])) {
                $yam_price = (float)$prices['prices']['yandex_market']['yam_price']['price'];
            }

            $yam_currency = '';
            if (!empty($prices['prices']['yandex_market']['yam_price']['currency'])) {
                $yam_currency = $prices['prices']['yandex_market']['yam_price']['currency'];
            }

            $yam_disable = 0;
            if (!empty($prices['prices']['yandex_market']['yam_disable'])) {
                $yam_disable = $prices['prices']['yandex_market']['yam_disable'];
            }

            $this->db->query("UPDATE product SET  
						yam_price 			= '" . $yam_price . "',
						yam_disable 		= '" . (int)$yam_disable . "',
						yam_currency		= '" . $this->db->escape($yam_currency) . "'
						WHERE product_id 	= '" . (int)$product_id . "'");

            $this->db->query("DELETE FROM product_price_national_to_yam WHERE product_id = '" . (int)$product_id . "'");

            foreach ($prices['prices']['yandex_market']['product_price_national_to_yam']['prices'] as $product_price_national_to_yam) {
            //Если валюта магазина отличается от заданной, то надо переконвертировать в заданную
                $store_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $product_price_national_to_yam['store_id']);
                if ($product_price_national_to_yam['currency'] != $store_currency) {
                    $product_price_national_to_yam['price'] = $this->currency->convert($product_price_national_to_yam['price'], $product_price_national_to_yam['currency'], $store_currency);
                }

                if ($product_price_national_to_yam['price'] > 0) {
                    $this->db->query("INSERT INTO product_price_national_to_yam SET 
								product_id 			= '" . (int)$product_id . "',
								store_id 			= '" . (int)$product_price_national_to_yam['store_id'] . "',
								price 				= '" . (float)$product_price_national_to_yam['price'] . "',
								currency 			= '" . $this->db->escape($store_currency) . "',
								dot_not_overload_1c 	= '0',
								settled_from_1c 		= '1'
								");
                }
            }
        }


            //product_price_to_store, EUR
            //Логика v2, если ветка не задана, то мы ее не обнуляем. для обнуления ветки следует передать ее пустую, либо с нулевыми ценами
        if (!empty($prices['prices']['product_price_to_store'])) {
            $this->db->query("DELETE FROM product_price_to_store WHERE product_id = '" . (int)$product_id . "'");


            foreach ($prices['prices']['product_price_to_store']['prices'] as $price_to_store) {
                if ($price_to_store['price'] > 0) {
                    $this->db->query("INSERT INTO product_price_to_store SET 
								product_id 	= '" . (int)$product_id . "',
								store_id 	= '" . (int)$price_to_store['store_id'] . "',
								price 		= '" . (float)$price_to_store['price'] . "',
								dot_not_overload_1c 	= '0',
								settled_from_1c 		= '1'			
								");
                }
            }
        }

            //product_price_to_store_national, CURRENCY
        if (!empty($prices['prices']['product_price_national_to_store'])) {
            $this->db->query("DELETE FROM product_price_national_to_store WHERE product_id = '" . (int)$product_id . "'");


            if (!empty($prices['prices']['product_price_national_to_store']['prices'])) {
                foreach ($prices['prices']['product_price_national_to_store']['prices'] as $price_national_to_store) {
                    //Если валюта магазина отличается от заданной, то надо переконвертировать в заданную
                    $store_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $price_national_to_store['store_id']);
                    if ($price_national_to_store['currency'] != $store_currency) {
                        $price_national_to_store['price'] = $this->currency->convert($price_national_to_store['price'], $price_national_to_store['currency'], $store_currency);
                    }

                    if ($price_national_to_store['price'] > 0) {
                        $this->db->query("INSERT INTO product_price_national_to_store SET 
									product_id 			= '" . (int)$product_id . "',
									store_id 			= '" . (int)$price_national_to_store['store_id'] . "',
									price 				= '" . (float)$price_national_to_store['price'] . "',
									currency 			= '" . $this->db->escape($store_currency) . "',
									dot_not_overload_1c 	= '0',
									settled_from_1c 		= '1'
									");
                    }
                }
            }
        }

            //Скидки
        if (!empty($prices['prices']['product_specials'])) {
            $this->db->query("DELETE FROM product_special WHERE product_id = '" . (int)$product_id . "'");
                    

            if (!empty($prices['prices']['product_specials']['prices'])) {
                foreach ($prices['prices']['product_specials']['prices'] as $price_special) {
                    $store_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $price_special['store_id']);
                    if ($price_special['currency'] != $store_currency) {
                        //  $price_special['price'] = $this->currency->convert($price_special['price'], $price_special['currency'], $store_currency);
                    }

                    $date_end = '0000-00-00';
                    if ($price_special['date_end'] == '0000-00-00') {
                        $date_end = '0000-00-00';
                    } elseif (!strtotime($price_special['date_end'])) {
                        $date_end = '0000-00-00';
                    } elseif (date('Y-m-d') > date('Y-m-d', strtotime($price_special['date_end']))) {
                        $date_end = date('Y-m-d', strtotime($price_special['date_end']));
                    } else {
                        $date_end = date('Y-m-d', strtotime($price_special['date_end']));
                    }

                    if (mb_strlen($price_special['store_id']) == 0) {
                        $price_special['store_id'] = '-1';
                    }

                    if ($price_special['price'] > 0) {
                        $this->db->query("INSERT INTO product_special SET 
									product_id 			= '" . (int)$product_id . "',					
									customer_group_id	= '1',
									priority			= '0',
									price 				= '" . (float)$price_special['price'] . "',
									old_price 			= '0',				
									store_id 			= '" . (int)$price_special['store_id'] . "',
									currency_scode 		= '" . $this->db->escape($price_special['currency']) . "',
									points_special 		= '0',
									date_start			= '0000-00-00',
									date_end			= '" . $this->db->escape($date_end) . "',
									parser_info_price	= NOW(),
									set_by_stock		= 0
									");
                    }
                }
            }
        }

                $this->db->query("UPDATE product_special SET customer_group_id = 1 WHERE customer_group_id = 13 AND product_id = '" . (int)$product_id . "'");

                $this->getProductPrices($product_id);
                return;
    }

    public function getProductPrices($product_id, $return = false)
    {
        $this->load->model('catalog/product');
        $this->load->model('setting/store');
        $this->load->model('setting/setting');
        $this->load->model('kp/price');
        $this->load->model('kp/product');
        $this->load->model('kp/priceva');

        $result = $this->model_catalog_product->getProduct($product_id);
        $product = array();

        if ($result) {
            $product['product_id'] = $result['product_id'];
            $product['model']   = $result['model'];
            $product['sku']     = $result['sku'];
            $product['ean']     = $result['ean'];

            $product['prices']  = array();
            $product['prices']['main_price'] =
            array(
                'name'      => 'Основная цена товара',
                'price'     => $result['price'],
                'currency'  => $this->config->get('config_currency')
            );

            $product['prices']['main_cost'] =
            array(
                'name'      => 'Основная закупочная цена товара',
                'price'     => $result['cost'],
                'currency'  => $this->config->get('config_currency')
            );

            $product['prices']['mpp_price'] =
            array(
                'name'      => 'Минимально возможная цена товара (МВЦ)',
                'price'     => $result['mpp_price'],
                'currency'  => $this->config->get('config_currency')
            );

            $product['prices']['amazon_best_price'] =
            array(
                'name'      => 'Лучшее предложение на амазон',
                'price'     => $result['amazon_best_price'],
                'currency'  => $this->config->get('config_currency')
            );

            $product['prices']['amazon_lowest_price'] =
            array(
                'name'      => 'Самое дешевое предложение на амазон',
                'price'     => $result['amazon_lowest_price'],
                'currency'  => $this->config->get('config_currency')
            );


        //YANDEX MARKET
            $product['prices']['yandex_market'] = [];

            $product['prices']['yandex_market']['yam_price'] =
            array(
                'name'      => 'Общая цена для Yandex Market',
                'price'     => $result['yam_price'],
                'currency'  => $result['yam_currency'],
            );

            $product['prices']['yandex_market']['yam_disable'] = $result['yam_disable'];

        //PRICES TO YAM
            $product['prices']['yandex_market']['product_price_national_to_yam'] = array(
                'name' => 'Переназначение цены для Yandex Market в локальной валюте',
                'code' => 'product_price_national_to_yam'
            );
            $query = $this->db->query("SELECT * FROM product_price_national_to_yam WHERE product_id = '" . (int)$product_id . "'");

            if ($query->num_rows) {
                $product['prices']['yandex_market']['product_price_national_to_yam']['prices'] = array();
                foreach ($query->rows as $row) {
                    $product['prices']['yandex_market']['product_price_national_to_yam']['prices'][$row['store_id']] = array(
                        'price'                 => $row['price'],
                        'store_id'              => $row['store_id'],
                        'dot_not_overload_1c'   => $row['dot_not_overload_1c'],
                        'currency'              => $row['currency']
                    );
                }
            }


            $product['prices']['price_national'] =
            array(
                'name'      => 'Основная цена в нацвалюте (только для сертификатов)',
                'price'     => $result['price_national'],
                'currency'  => $result['currency'],
            );

        //PRICES TO STORE
            $product['prices']['product_price_to_store'] = array(
                'name' => 'Переназначение цены для стран в основной валюте',
                'code' => 'product_price_to_store'
            );
            $query = $this->db->query("SELECT * FROM product_price_to_store WHERE product_id = '" . (int)$product_id . "'");

            if ($query->num_rows) {
                $product['prices']['product_price_to_store']['prices'] = array();
                foreach ($query->rows as $row) {
                    $product['prices']['product_price_to_store']['prices'][$row['store_id']] = array(
                        'price'     => $row['price'],
                        'store_id'  => $row['store_id'],
                        'dot_not_overload_1c'   => $row['dot_not_overload_1c'],
                        'settled_from_1c'       => $row['settled_from_1c'],
                        'currency'  => $this->config->get('config_currency')
                    );
                }
            }

        //PRICES TO STORE NATIONAL
            $product['prices']['product_price_national_to_store'] = array(
                'name' => 'Переназначение цены для стран в локальной валюте (РРЦ)',
                'code' => 'product_price_national_to_store'
            );
            $query = $this->db->query("SELECT * FROM product_price_national_to_store WHERE product_id = '" . (int)$product_id . "'");

            if ($query->num_rows) {
                $product['prices']['product_price_national_to_store']['prices'] = array();
                foreach ($query->rows as $row) {
                    $product['prices']['product_price_national_to_store']['prices'][$row['store_id']] = array(
                        'price'                 => $row['price'],
                        'store_id'              => $row['store_id'],
                        'dot_not_overload_1c'   => $row['dot_not_overload_1c'],
                        'settled_from_1c'       => $row['settled_from_1c'],
                        'currency'              => $row['currency']
                    );
                }
            }

            $query = $this->db->query("SELECT * FROM product_special ps WHERE product_id = '" . (int)$product_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");
            if ($query->num_rows) {
                $product['prices']['product_specials'] = array(
                    'name' => 'Скидочные цены',
                    'prices' => array()
                );

                foreach ($query->rows as $row) {
                    $product['prices']['product_specials']['prices'][] = array(
                        'price'     => $row['price'],
                        'currency'  => $row['currency_scode'],
                        'store_id'  => $row['store_id'],
                        'date_end'  => $row['date_end']
                    );
                }
            }

            $product['prices']['amazon_offers'] = array(
                'name'      => 'Предложения amazon',
                'ASIN'      => $result['asin'],
                'EAN'       => $result['ean'],
                'offers'    => array()
            );

            $amazon_offers = $this->model_kp_product->getProductAmazonOffers($result['asin']);
            foreach ($amazon_offers as $amazon_offer) {
                $product['prices']['amazon_offers']['offers'][] = $amazon_offer;
            }


            $product['prices']['frontend_prices'] = array(
                'name' => 'Результирующая цена на фронте',
                'prices' => array()
            );

            $stores = $this->model_setting_store->getStores();
            $stores[] = array('store_id' => 0, 'name' => $this->model_setting_setting->getKeySettingValue('config', 'config_name', 0));
            foreach ($stores as $store) {
                $price_data = $this->model_kp_price->getProductResultPriceByStore($product_id, $store['store_id']);
                $product['prices']['frontend_prices']['prices'][$store['store_id']] = array(
                    'store_id'  => $store['store_id'],
                    'store_name'=> $store['name'],
                    'currency'  => $price_data['currency'],
                    'price'     => $price_data['price'],
                    'special'   => $price_data['special'],
                );
            }

            if ($this->config->get('config_priceva_enable_api')) {
                $product['prices']['competitor_prices'] = array(
                'name' => 'Данные цен конкурентов',
                'prices' => array()
                        );

                        $query = $this->db->query("SELECT * FROM priceva_sources WHERE product_id = '" . $product_id . "'");

                foreach ($query->rows as $row) {
                    $final_competitor_price = false;
                    if ((float)$row['price'] > 0) {
                        $final_competitor_price = (float)$row['price'];
                    }

                    if ((float)$row['discount'] > 0) {
                        $final_competitor_price = (float)$row['discount'];
                    }


                    $product['prices']['competitor_prices']['prices'][] = [
                    'store_id'          => $row['store_id'],
                    'company_name'      => $row['company_name'],
                    'url'               => $row['url'],
                    'currency'          => $row['currency'],
                    'price'             => (float)$row['price'],
                    'discount'          => (float)$row['discount'],
                    'final_price'       => (float)$final_competitor_price,
                    'in_stock'          => (int)$row['in_stock'],
                    'last_check_date'   => date('Y-m-d', strtotime($row['last_check_date'])),
                    'relevance_status'  => $this->pricevaAdaptor->getPricevaRelevance()[$row['relevance_status']]


                    ];
                }
            }
        }

        if ($return) {
            return $product;
        } else {
            header("Content-Type: application/json");
            $this->response->setOutput(json_encode($product));
        }
    }

    public function getFullProduct($product_id, $return = false)
    {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('kp/product');

        $result = $this->model_catalog_product->getProduct($product_id);
        $product = array();
        if ($result) {
            foreach ($result as $key => $value) {
                $product[$key] = $value;
            }
            if ($product['asin']) {
                $product['amazon_listing_link'] = "https://www.amazon.de/gp/offer-listing/".$product['asin']."/ref=olp_f_primeEligible&f_primeEligible=true&f_new=true";
                $product['amazon_direct_link'] = "https://www.amazon.de/dp/".$product['asin'];
            } else {
                $product['amazon_listing_link'] = '';
                $product['amazon_direct_link'] = '';
            }
            $descriptions = $this->model_catalog_product->getProductDescriptions($product_id);
            foreach ($descriptions as &$desc) {
                unset($desc['description']);
                unset($desc['meta_description']);
                unset($desc['meta_title']);
                unset($desc['seo_title']);
                unset($desc['seo_h1']);
                unset($desc['tag']);
            }
            $product['image'] = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));

            $product['images'] = array();
            foreach ($this->model_catalog_product->getProductImages($product_id) as $image) {
                $product['images'][] = $this->model_tool_image->resize($image['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            }

            $product['multilang_description'] = $descriptions;
            $product['categories'] = $this->model_catalog_product->getProductCategories($product_id);

            $product['can_not_buy'] = ($product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id'));

            if (!$product['tnved'] || mb_strlen($product['tnved']) < 2) {
                $product['tnved'] = $this->model_catalog_product->getProductTNVEDByCategory($product_id);
            }

            $product['specials'] = $this->model_catalog_product->getProductSpecials($product_id);
            $product['attributes'] = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($product_id, 2);
        }

        if ($return) {
            return $product;
        } else {
            $this->response->setOutput(json_encode($product));
        }
    }

    public function updateProductEANExplicit($product_id, $ean, $sku, $user, $cleanup = false)
    {
        $log = new Log('odinass_eansku_change.txt');

        $this->load->model('catalog/product');
        $result = $this->model_catalog_product->getProduct($product_id);

        if (!$result) {
            die('no product exist');
        }

        $str = $user . ' изменил товар ' . $product_id . ' ('. $result['name'] .')';
        $json = array();

        if ($ean && mb_strlen(trim($ean)) > 1) {
            $this->db->query("UPDATE product SET ean = '" . $this->db->escape($ean) . "' WHERE product_id = '" . (int)$product_id . "'");

            $str .= ' EAN: ' . $result['ean'] . ' -> ' . $ean;

            $json []= array('ean' => 'ok');
        }

        if ($sku && mb_strlen(trim($sku)) > 1) {
            $this->db->query("UPDATE product SET sku = '" . $this->db->escape($sku) . "' WHERE product_id = '" . (int)$product_id . "'");

            $str .= ' SKU: ' . $result['sku'] . ' -> ' . $sku;

            $json []= array('sku' => 'ok');
        }

        if ($cleanup) {
            $this->db->query("UPDATE local_supplier_products SET product_ean = '' WHERE product_id = '" . (int)$product_id . "'");

            $json[] = array(
                'cleanup' => 'ok'
            );
        }

        if ($json) {
            $log->write($str);
        }

        $this->response->setOutput(json_encode($json));
    }

    public function getFullProductsBySKU($sku_list = array())
    {

        $result = array();

        if ($sku_list) {
            $this->load->model('catalog/product');
            foreach ($sku_list as $sku) {
                $products = $this->model_catalog_product->getProductsBySKU($sku);

                $result[trim($sku)] = array();

                foreach ($products as $product) {
                    $result[trim($sku)][] = $this->getFullProduct($product['product_id'], true);
                }
            }
        }

        $this->response->setOutput(json_encode($result));
    }

    public function getProductImage($product_id, $width = 150, $height = 150)
    {

        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $result = $this->model_catalog_product->getProductImage($product_id);

        if ($result) {
            $path = $this->model_tool_image->resize($result, $width, $height);
        } else {
            $path = $this->model_tool_image->resize('no_image.jpg', $width, $height);
        }


        $html = "<html><head></head><body>";
        $html .= "<img src='".$path."' width='".$width."px' height='".$height."px' />";
        $html .= "</body></html>";

        $this->response->setOutput($html);
    }

    public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false)
    {

        $this->load->model('sale/order');
        $this->load->model('sale/customer');
        $this->load->model('localisation/order_status');
        $this->load->model('setting/setting');
        $order_info = $this->model_sale_order->getOrder($order_id);

        $responce = array();

        if (!$order_info) {
            $responce['error'] = "true";
            $responce['error_msg'] = 'Заказ не существует';
            $this->response->setOutput(json_encode($responce));
        } else {
            if (!$this->model_localisation_order_status->getOrderStatusName($order_status_id)) {
                $responce['error'] = "true";
                $responce['error_msg'] = 'Статус не существует';
                $this->response->setOutput(json_encode($responce));
            } else {
                if ($order_info['order_status_id'] == (int)$order_status_id) {
                    $responce['error'] = "true";
                    $responce['error_msg'] = 'Заказ сейчас в этом статусе';
                    $this->response->setOutput(json_encode($responce));
                } elseif ($order_info['order_status_id'] == $this->config->get('config_complete_status_id')) {
                    $responce['error'] = "true";
                    $responce['error_msg'] = 'Заказ закрыт';
                    $this->response->setOutput(json_encode($responce));
                } elseif ($order_info['order_status_id'] == $this->config->get('config_cancelled_status_id')) {
                    $responce['error'] = "true";
                    $responce['error_msg'] = 'Заказ отменен';
                    $this->response->setOutput(json_encode($responce));
                } else {
                    if ($order_status_id == $this->config->get('config_complete_status_id') || $order_status_id == $this->config->get('config_cancelled_status_id')) {
                        $this->db->query("UPDATE `order` SET closed = '1' WHERE order_id = '" . (int)$order_id . "'");
                    }

                    $this->db->query("UPDATE `order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
                    $this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', user_id = 54, comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

                    $this->db->query("INSERT INTO `order_save_history` SET 
								`order_id` = '" . (int)$order_id . "', 
								`user_id` = '54',
								`datetime` = NOW()
								");

                    if ($order_status_id == $this->config->get('config_complete_status_id')) {
                        $reward_query = $this->db->query("SELECT SUM(reward) as total FROM order_product WHERE order_id = '" . $order_id . "'");

                        if ($reward_query->num_rows && $reward_query->row['total']) {
                            $description = sprintf($this->language->getCatalogLanguageString($order_info['language_id'], 'total/reward', 'text_reward_add_description'), $order_id);

                            $this->customer->addRewardToQueue($order_info['customer_id'], $description, $reward_query->row['total'], $order_id, 'ORDER_COMPLETE_ADD');
                        }
                    }

                    if ($order_status_id == $this->config->get('config_cancelled_status_id')) {
                        $this->customer->clearRewardTableByOrder($order_info['customer_id'], $order_id);
                        $this->customer->clearRewardQueueByOrder($order_info['customer_id'], $order_id);

                        $pointsPaidForThisOrder = $this->customer->getRewardReservedByOrder($order_info['customer_id'], $order_id);
                        if ($pointsPaidForThisOrder > 0) {
                            $description = sprintf($this->language->getCatalogLanguageString($order_info['language_id'], 'total/reward', 'text_reward_return_description'), $order_id);
                            $this->customer->addReward($order_info['customer_id'], $description, $pointsPaidForThisOrder, $order_id, 'ORDER_RETURN');
                        }
                    }

                    if ($order_info['yam'] && $order_info['yam_id']) {
                        $this->load->model('api/yamarket');
                        $this->model_api_yamarket->addToQueue($order_id, $order_status_id);
                    }

                    if ($comment && mb_strlen($comment) > 2) {
                        $customer_comment_text = 'изменен статус на: <b>'.$this->model_localisation_order_status->getOrderStatusName($order_status_id).'</b> ('.$comment.')';
                    } else {
                        $customer_comment_text = 'изменен статус на: <b>'.$this->model_localisation_order_status->getOrderStatusName($order_status_id).'</b>';
                    }

                    $_data = array(
                        'customer_id'             => $order_info['customer_id'],
                        'comment'                 => $customer_comment_text,
                        'order_id'                => $order_id,
                        'call_id'                 => 0,
                        'manager_id'              => 54,
                        'need_call'               => 0,
                        'segment_id'              => 0,
                        'order_status_id'         => $order_status_id,
                        'prev_order_status_id'    => $order_info['order_status_id']
                    );
                    $this->model_sale_customer->addHistoryExtended($_data);

                    if ($notify) {
                        $template = new EmailTemplate($this->request, $this->registry);

                        $template->data['payment_address'] = EmailTemplate::formatAddress($order_info, 'payment', $order_info['payment_address_format']);

                        $template->data['shipping_address'] = EmailTemplate::formatAddress($order_info, 'shipping', $order_info['shipping_address_format']);
                        if ($template->data['shipping_address'] == '') {
                            $template->data['shipping_address'] = $template->data['payment_address'];
                        }

                        $template->data['products'] = array();
                        if (isset($data['show_products'])) {
                            $this->load->model('tool/image');
                            $this->load->model('catalog/product');
                            $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

                            foreach ($products as $product) {
                                $product = array_merge($this->model_catalog_product->getProduct($product['product_id']), $product);

                            // Product Options
                                $option_data = array();
                                $options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
                                foreach ($options as $option) {
                                    if ($option['type'] != 'file') {
                                        $option_data[] = array(
                                            'name'  => $option['name'],
                                            'value' => $option['value'],
                                            'type'  => $option['type']
                                        );
                                    } else {
                                        $option_data[] = array(
                                            'name'  => $option['name'],
                                            'value' => substr($option['value'], 0, strrpos($option['value'], '.')),
                                            'type'  => $option['type'],
                                            'href'  => $this->url->link('sale/order/download', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&order_option_id=' . $option['order_option_id'], 'SSL')
                                        );
                                    }
                                }

                                if (isset($product['image'])) {
                                    $image = $this->model_tool_image->resize($product['image'], 50, 50);
                                } else {
                                    $image = '';
                                }

                                $url = $order_info['store_url'] . '?route=product/product&product_id='.$product['product_id'];

                                if ($product['price_national'] > 0) {
                                    $price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');
                                } else {
                                    $price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
                                }

                                if ($product['total_national'] > 0) {
                                    $total = $this->currency->format($product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');
                                } elseif ($product['price_national'] > 0) {
                                    $total = $this->currency->format($product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');
                                } else {
                                    $total = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
                                }


                                $template->data['products'][] = array(
                                    'url'           => $url,
                                    'url_tracking'  => $template->getTracking($url),
                                    'order_product_id' => $product['order_product_id'],
                                    'product_id'       => $product['product_id'],
                                    'name'             => $product['name'],
                                    'model'            => $product['model'],
                                    'image'            => $image,
                                    'option'           => $option_data,
                                    'quantity'         => $product['quantity'],
                                    'price'            => $price,
                                    'total'            => $total,
                                    'href'             => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
                                );
                            }
                        }

                        $template->data['vouchers'] = array();
                        if (isset($data['show_vouchers'])) {
                            $vouchers = $this->model_sale_order->getOrderVouchers($order_id);
                            foreach ($vouchers as $voucher) {
                                $template->data['vouchers'][] = array(
                                    'description' => $voucher['description'],
                                    'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
                                    'href'        => $this->url->link('sale/voucher/update', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
                                );
                            }
                        }

                        $template->data['totals'] = array();
                        if (isset($data['show_totals'])) {
                            $template->data['totals'] = $this->model_sale_order->getOrderTotals($order_id);
                        }

                        $template->data['downloads'] = array();
                        if (isset($data['show_downloads'])) {
                            foreach ($products as $product) {
                                $results = $this->model_sale_order->getOrderDownloads($order_id, $product['order_product_id']);
                                foreach ($results as $result) {
                                    $template->data['downloads'][] = array(
                                        'name'      => $result['name'],
                                        'filename'  => $result['mask'],
                                        'remaining' => $result['remaining']
                                    );
                                }
                            }
                        }

                        $attachments = array();
                        if (isset($data['attach_invoice_pdf'])) {
                            $this->load->model('module/emailtemplate/invoice');
                            $template->data['emailtemplate_invoice_pdf'] = $this->model_module_emailtemplate_invoice->getInvoice($this->request->get['order_id'], true);
                            $attachments[] = $template->data['emailtemplate_invoice_pdf'];
                        }

                        $language = new Language($order_info['language_directory']);
                        $language->load($order_info['language_filename']);
                        $language->load('mail/order');

                        $subject = sprintf($language->get('text_subject'), $order_info['store_name'], $order_id);

                        $message  = $language->get('text_order') . ' ' . $order_id . "\n";
                        $message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

                        $data['order_status_id'] = $order_status_id;
                        $order_status_query = $this->db->query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

                        if ($order_status_query->num_rows) {
                            $message .= $language->get('text_order_status') . "\n";
                            $message .= $order_status_query->row['name'] . "\n\n";
                        }

                        if ($order_info['customer_id']) {
                            $message .= $language->get('text_link') . "\n";
                            $message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
                        }

                        if ($comment) {
                            $message .= $language->get('text_comment') . "\n\n";
                            $message .= strip_tags(html_entity_decode($comment, ENT_QUOTES, 'UTF-8')) . "\n\n";
                        }

                        $message .= $language->get('text_footer');

                        if (!empty($template->data['products'])) {
                            $message .= "\n" . $language->get('text_products') . "\n";
                            foreach ($template->data['products'] as $product) {
                                $message .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($product['total'], ENT_NOQUOTES, 'UTF-8') . "\n";
                                foreach ($product['option'] as $option) {
                                    $message .= chr(9) . '-' . $option['name'] . ' ' . (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
                                }
                            }
                        }

                        if (!empty($template->data['vouchers'])) {
                            foreach ($template->data['vouchers'] as $voucher) {
                                $message .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
                            }
                        }

                        if (!empty($template->data['totals'])) {
                            $message .= "\n" . $language->get('text_total') . "\n";
                            foreach ($template->data['totals'] as $total) {
                                $message .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
                            }
                        }

                        if (!empty($template->data['downloads'])) {
                            $message .= "\n" . $language->get('text_download') . "\n";
                            $message .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
                        }

                        $template->addData($data);

                        $template->addData($order_info);

                        $template->data['new_comment'] = html_entity_decode($comment, ENT_QUOTES, 'UTF-8');
                        $template->data['invoice'] = html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8');
                        $template->data['invoice_tracking'] = $template->getTracking($template->data['invoice']);

                        $template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));

                        if ($order_status_query->num_rows) {
                            $template->data['order_status'] = $order_status_query->row['name'];
                        }

                        $mail = new Mail($this->registry);
                        $mail->setTo($order_info['email']);

                        $mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
                        $mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));

                        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                        $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                        $template_data = array('key' =>'admin.order_update');
                        if (!empty($data['field_template'])) {
                            $template_data['emailtemplate_id'] = $data['field_template'];
                        }
                        if (!empty($data['order_status_id'])) {
                            $template_data['order_status_id'] = $data['order_status_id'];
                        }
                        if (!empty($data['customer_id'])) {
                            $template_data['customer_id'] = $data['customer_id'];
                        }
                        if (isset($order_info['store_id'])) {
                            $template_data['store_id'] = $order_info['store_id'];
                        }
                        if (isset($order_info['language_id'])) {
                            $template_data['language_id'] = $order_info['language_id'];
                        }

                        $template->load($template_data);

                        $mail = $template->hook($mail);
                        foreach ($attachments as $attachment) {
                            $mail->addAttachment($attachment);
                        }

                        $mail->setTo($order_info['email']);
                        $mail->send();

                        $template->sent();
                    }

                    $this->Fiscalisation->addOrderToQueue($order_id);

                    $responce['success'] = true;
                    $responce['new_status'] = $order_status_id;
                    $this->response->setOutput(json_encode($responce));
                }
            }
        }
    }

    public function getStocksFrom1C($update = true, $updateStockGroups = false)
    {
        $this->load->model('kp/info1c');

        $this->getStockWaitsFrom1C();
        $result = $this->model_kp_info1c->getStocksFrom1C();

        $this->updateStockXML($result, $update, $updateStockGroups);
    }

    public function getOrderCurrentStatusJSON($orders)
    {
        $this->load->model('sale/order');

        foreach ($orders as $order_id) {
            $order_info = $this->db->query("SELECT o.order_id, os.name, os.order_status_id FROM `order` o LEFT JOIN order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = 2) WHERE o.order_id = '" . (int)$order_id . "' LIMIT 1");
            if (!$order_info->num_rows) {
                $responce[$order_id] = array(
                    'error' => "true",
                    'error_msg' => 'Order does not exist'
                );
            } else {
                foreach ($order_info->row as $key => $value) {
                    $responce[$order_id][$key] = $value;
                }
            }
        }

        $this->response->setOutput(json_encode($responce));
    }

    public function getOrderCurrentStatus($orders)
    {
        $this->load->model('sale/order');

        $orders = explode(',', $orders);

        $responce = array();

        foreach ($orders as $order_id) {
            $order_info = $this->db->query("SELECT o.order_id, os.name, os.order_status_id FROM `order` o LEFT JOIN order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = 2) WHERE o.order_id = '" . (int)$order_id . "' LIMIT 1");
            if (!$order_info->num_rows) {
                $responce[$order_id] = array(
                    'error' => "true",
                    'error_msg' => 'Order does not exist'
                );
            } else {
                foreach ($order_info->row as $key => $value) {
                    $responce[$order_id][$key] = $value;
                }
            }
        }

        $this->response->setOutput(json_encode($responce));
    }

    public function getActualPrices()
    {
        $this->load->model('kp/info1c');
        $this->load->model('catalog/product');

        if ($result = $this->model_kp_info1c->getActualCostFrom1C()) {
            $this->db->query("UPDATE product SET mpp_price = 0 WHERE 1");

            foreach ($result['items'] as $product) {
                echoLine($product['idProduct'] . ' : ' . $product['price'] . ' : ' . $product['datePrice'] . ' : ' .  $product['mpp_price']);
                        
                $this->db->query("UPDATE product SET 	
							mpp_price = '" . (float)$product['mpp_price'] . "', 
							actual_cost = '" . (float)$product['price'] . "', 
							actual_cost_date = '" . $this->db->escape(date('Y-m-d', strtotime($product['datePrice'])))  . "' 
							WHERE product_id = '" . (int)$product['idProduct'] . "'");
            }
        }
    }

    public function putOrderTrackingInfo($json_data = '')
    {
        $this->load->model('sale/order');
        $this->load->model('setting/setting');

        $return = array();

        if ($json_data['trackerType'] && $json_data['trackerType'] == 'LeaveMainWarehouse' && $json_data['NumPart']) {
            $orders = array();
            foreach ($json_data['items'] as $item) {
                $orders[] = $item['idOrder'];
            }

            array_unique($orders);

            foreach ($orders as $order_id) {
                $order_info = $this->model_sale_order->getOrder($order_id);

                if ($order_info) {
                    $check_query = $this->db->query("SELECT * FROM order_tracker_sms WHERE tracker_type = 'LeaveMainWarehouse' AND order_id = '" . (int)$order_id . "' AND partie_num = '" . $this->db->escape($json_data['NumPart']) . "'");

                    if (!$check_query->num_rows) {                        
                        $this->db->query("INSERT INTO order_tracker_sms SET tracker_type = 'LeaveMainWarehouse', order_id = '" . (int)$order_id . "', partie_num = '" . $this->db->escape($json_data['NumPart']) . "', date_sent = NOW()");
                        
                        $this->smsAdaptor->sendInWarehouse($order_info, ['order_status_id' => $order_info['order_status_id']]);

                    } else {
                        $return[$order_id] = 'already sent';
                    }
                }
            }
        } else {
            $return['error'] = 'trackerType mismatch';
        }

        $this->response->setOutput(json_encode($return));
    }

    public function trackerCron()
    {
        $this->load->model('feed/exchange1c');
        $this->load->model('sale/order');
        $this->load->model('kp/info1c');

        $query = $this->db->query("SELECT DISTINCT(order_id) FROM `order` WHERE order_status_id IN (2, 33, 26, 15, 14, 16, 25)");

        $i = 1;
        foreach ($query->rows as $row) {
            $order_id = $row['order_id'];

            $order1c = $this->model_kp_info1c->getOrderTrackerXML($order_id);

            if ($order1c) {
                echo $i . '/' . $query->num_rows . ': получили заказ ' . $order_id . PHP_EOL;
                $this->db->query("UPDATE `order` SET tracker_xml = '" . $this->db->escape(json_encode($order1c)) . "' WHERE order_id = '" . (int)$order_id . "'");
            } else {
                echo $i . '/' . $query->num_rows . ': ошибка получения ' . $order_id . PHP_EOL;
            }

            $i++;
        }
    }

    public function exportOrdersCron()
    {
        $this->load->model('feed/exchange1c');
        $this->load->model('sale/order');

        $query = $this->db->query("SELECT order_id FROM order_to_1c_queue ORDER BY RAND() LIMIT 20");

        foreach ($query->rows as $result) {
            $order_id = $result['order_id'];
            $order = $this->model_sale_order->getOrder($order_id);

            if ($order && in_array((int)$order['order_status_id'], $this->config->get('config_odinass_order_status_id'))) {                    

                $this->model_feed_exchange1c->getOrderXML($order_id);
                $this->model_feed_exchange1c->makeSalesResultXML($order_id);
                $this->model_feed_exchange1c->getOrderReturnsXML($order_id);
                $this->model_feed_exchange1c->getOrderTransactionsXML($order_id);
                $this->model_feed_exchange1c->getOrderSuppliesXML($order_id);

                echo $order_id . PHP_EOL;                
            }

            $this->model_feed_exchange1c->removeOrderFromQueue($order_id);
        }
    }

    public function legalpersonList()
    {
        $query = $this->db->query("SELECT * FROM legalperson WHERE 1");
        $this->response->setOutput(json_encode($query->rows));
    }

    public function productPurchaseSet($purchaseData)
    {
    }

    public function transactionSyncSet($transactionData)
    {
            $this->load->model('sale/customer');
            $this->load->model('sale/order');

            $fields = ['customer_id','description','amount_national','currency_code','order_id','legalperson_id','guid'];

        if (empty($transactionData['customer_transaction_id']) && empty($transactionData['guid'])) {
            $result = [
                'status'    => 'error',
                'message'   => 'Both customer_transaction_id and guid are empty. What the fuck?',
                'data_sent' => $transactionData
            ];

            $this->response->setOutput(json_encode($result));
            return;
        }
            
        foreach ($fields as $field) {
            if (!isset($transactionData[$field])) {
                $result = [
                    'status'    => 'error',
                    'message'   => 'Field ' . $field . ' is empty. Need all fields: ' . implode(',', $fields),
                    'data_sent' => $transactionData
                ];

                $this->response->setOutput(json_encode($result));
                return;
            }
        }

            $transaction = false;
        if (!empty($transactionData['customer_transaction_id'])) {
            $transaction = $this->model_sale_customer->getTransactionByID($transactionData['customer_transaction_id']);
        } elseif (!empty($transactionData['guid'])) {
            $transaction = $this->model_sale_customer->getTransactionByGUID($transactionData['guid']);
        }

        if (!empty($transactionData['date_added'])) {
            $date_added = date('Y-m-d H:i:s', strtotime($transactionData['date_added']));
        } elseif ($transaction) {
            $date_added = $transaction['date_added'];
        } else {
            $date_added = date('Y-m-d H:i:s');
        }

        if (!empty($transactionData['added_from'])) {
            $added_from = $transactionData['added_from'];
        } elseif ($transaction) {
            $added_from = $transaction['added_from'];
        } else {
            $added_from = '1c_sync';
        }

            $data = array(
                'customer_id'       => $transactionData['customer_id'],
                'description'       => $transactionData['description'],
                'amount_national'   => $transactionData['amount_national'],
                'currency_code'     => $transactionData['currency_code'],
                'order_id'          => $transactionData['order_id'],
                'date_added'        => $date_added,
                'added_from'        => $added_from,
                'legalperson_id'    => $transactionData['legalperson_id'],
                'guid'              => $transactionData['guid']
            );

            if ($transactionData['order_id']) {
                $order_info = $this->model_sale_order->getOrder($transactionData['order_id']);
                if ($order_info['currency_value']) {
                    $data['amount'] = $this->currency->reconvert((float)$transactionData['amount_national'], $order_info['currency_value']);
                } else {
                    $data['amount'] = $this->currency->convert((float)$transactionData['amount_national'], $transactionData['currency_code'], 'EUR');
                }
            } else {
                $data['amount'] = $this->currency->convert((float)$transactionData['amount_national'], $transactionData['currency_code'], 'EUR');
            }
            
            if ($transaction) {
                $customer_transaction_id = $this->model_sale_customer->updateTransactionFromAPI($transaction['customer_transaction_id'], $data);
                $result = ['status' => 'success', 'message' => 'Transaction found and edited'];
            } else {
                $customer_transaction_id = $this->model_sale_customer->addTransactionFromAPI($data);
                $result = ['status' => 'success', 'message' => 'Transaction not found and added'];
            }

            $result['data_sent']        = $transactionData;
            $result['transaction_data'] = $this->model_sale_customer->getTransactionByID($customer_transaction_id);

            $this->response->setOutput(json_encode($result));
    }

    public function transactionSyncGet($transactionData)
    {
            $this->load->model('sale/customer');
            $this->load->model('sale/order');
            $transactions = array();

        if (!empty($transactionData['order_id'])) {
            $order = $this->model_sale_order->getOrder($transactionData['order_id']);
            if (!$order) {
                die('order does not exist, check please');
            }

            $customer_transactions = $this->model_sale_customer->getTransactions($order['customer_id'], 0, 10000, $order['order_id']);
        } elseif ($transactionData['customer_id']) {
            $customer = $this->model_sale_customer->getCustomer($transactionData['customer_id']);
            if (!$customer) {
                die('customer does not exist, check please');
            }

            $customer_transactions = $this->model_sale_customer->getTransactions($customer['customer_id'], 0, 10000);
        }


        foreach ($customer_transactions as $customer_transaction) {
            $ct_date = date('Y-m-d', strtotime($customer_transaction['date_added']));
            $ct_time = date('H:i:s', strtotime($customer_transaction['date_added']));

            $transactions[] = array(
                'customer_transaction_id'       => $customer_transaction['customer_transaction_id']
                ,'guid'                         => $customer_transaction['guid']
                ,'date'                         => $ct_date
                ,'time'                         => $ct_time
                ,'description'                  => $customer_transaction['description']
                ,'currency'                     => $customer_transaction['currency_code']
                ,'sum'                          => $customer_transaction['amount_national']
                ,'sumEUR'                       => $customer_transaction['amount']
                ,'added_from'                   => $customer_transaction['added_from']
                ,'legalperson_id'               => $customer_transaction['legalperson_id']
                ,'legalperson_name_1C'          => $customer_transaction['legalperson_name_1C']
                ,'legalperson_name'             => $customer_transaction['legalperson_name']
                ,'isHandMade'                   => ($customer_transaction['added_from'] == 'manual_admin')
                ,'isAutoCheck'                  => ($customer_transaction['added_from'] == 'auto_order_close')
            );
        }
            $this->response->setOutput(json_encode($transactions));
    }

    public function updateOrdersInCourierService($json_data = '')
    {
            $this->load->model('sale/order');
            $this->load->model('setting/setting');

        foreach ($json_data as $courier_order) {
            if (isset($courier_order['idCourier'])) {
                $order = $this->model_sale_order->getOrder($courier_order['id']);

                if (!isset($courier_order['comment'])) {
                    $courier_order['comment'] = '';
                }

                if ($order) {
                    $this->db->query("INSERT INTO order_courier_history (order_id, courier_id, date_added, date_status, status, comment, json) 
							VALUES ('" . (int)$order['order_id'] . "', 
								'" . $this->db->escape($courier_order['idCourier']) . "',
								NOW(),
								'" . $this->db->escape($courier_order['DateStatus']) . "',
								'" . $this->db->escape($courier_order['Status']) . "',
								'" . $this->db->escape($courier_order['comment']) . "',
								'" . $this->db->escape(json_encode($courier_order)) . "'
								)
								ON DUPLICATE KEY UPDATE date_status = '" . $this->db->escape($courier_order['DateStatus']) . "',
								status = '" . $this->db->escape($courier_order['Status']) . "',
								comment = '" . $this->db->escape($courier_order['comment']) . "',
								json = '" . $this->db->escape(json_encode($courier_order)) . "'
								");


                    if ($courier_order['Status'] == 'в кассе' && $order['order_status_id'] != $this->config->get('config_complete_status_id')) {
                        $probably_close_reason = "В курьерской службе заказ отмечен, как выданный";
                        $this->db->query("UPDATE `order` SET probably_close = 1, probably_close_reason = '" . $this->db->escape($probably_close_reason) . "' WHERE order_id = '" . (int)$order['order_id'] . "'");
                    }

                    if ((strpos($courier_order['Status'], 'отказ') !== false) && $order['order_status_id'] != $this->config->get('config_cancelled_status_id')) {
                        $probably_cancel_reason = "Курьерская служба сообщает об отказе";
                        $this->db->query("UPDATE `order` SET probably_cancel = 1, probably_cancel_reason = '" . $this->db->escape($probably_cancel_reason) . "' WHERE order_id = '" . (int)$order['order_id'] . "'");
                    }
                }
            } else {
                die('No order found');
            }
        }

                die('parsed');
    }
}
