<?php

class ControllerYaMarketApi extends Controller
{

    private $orderStatusMappings = [];
    private $orderStatusComments = [];
    private $orderSubStatusComments = [];
    private $priceSuggestMappings = [];

    private $excluded_names = [];

    private $offerPriceLimit = 499;
    private $skuStatsLimit = 499;
    private $stocksLimit = 499;

    private $priceTypes = ['BUYBOX', 'DEFAULT_OFFER', 'MIN_PRICE_MARKET', 'MAX_DISCOUNT_BASE', 'MARKET_OUTLIER_PRICE'];

    private $apiUserID = 177;

    public function getYamAPIValue($value)
    {
        $this->createMappings();
        return $this->{$value};
    }

    private function createMappings()
    {
        $this->priceSuggestMappings = [
            'BUYBOX' => 'Минимальная цена на Маркете. Это самая низкая цена из предложенных партнерами Маркета, и по ней товар продается сейчас.<br />
				Эта цена обновляется в режиме реального времени. Если вы установите цену ниже или равной минимальной, начнет показываться ваше предложение.<br />
				Если для этого значения в параметре price указана цена, которая совпадает с вашей, значит, ваш товар уже показывается на витрине. Если кроме вас этот товар продают другие продавцы по такой же цене, их предложения также будут отображаться вместе с вашими по очереди.',

            'DEFAULT_OFFER' => 'Рекомендованная Маркетом цена, которая привлекает покупателей. Рассчитывается только для популярных на сервисе товаров и обновляется раз в четыре часа.',

            'MIN_PRICE_MARKET' => 'Минимальная цена на Маркете. Самая низкая цена среди всех предложений товара на Маркете. Эта цена обновляется в режиме реального времени и обеспечивает большее количество показов на Маркете, чем минимальная на Маркете и рекомендованная цена.',

            'MAX_DISCOUNT_BASE' => 'Максимальная цена товара без скидки (старая цена), при которой он может быть показан со скидкой. Данная цена рассчитывается автоматически на основе SKU на Яндексе',

            'MARKET_OUTLIER_PRICE' => 'Максимальная цена товара, которая обеспечивает показы на Маркете. Если ваша цена выше указанной, товар будет скрыт, так как цена превышает рекомендованную Маркетом на 40% или больше',
        ];

        $this->orderStatusMappings = [
            'CANCELLED' => $this->config->get('config_cancelled_status_id'),
            'DELIVERED' => $this->config->get('config_complete_status_id'),
            'DELIVERY' => $this->config->get('config_delivering_status_id'),
            'PICKUP' => $this->config->get('config_delivering_status_id'),
            'PROCESSING' => $this->config->get('config_order_status_id'),
            'PENDING' => $this->config->get('config_order_status_id'),
            'UNPAID' => $this->config->get('config_confirmed_nopaid_order_status_id')
        ];

        $this->orderStatusComments = [
            'CANCELLED' => 'Ya.Market: Заказ отменен',
            'DELIVERED' => 'Ya.Market: Заказ получен покупателем',
            'DELIVERY' => 'Ya.Market: Заказ передан в службу доставки',
            'PICKUP' => 'Ya.Market: Заказ доставлен в пункт самовывоза',
            'PROCESSING' => 'Ya.Market: Заказ находится в обработке',
            'PENDING' => 'Ya.Market: По заказу требуются дополнительные действия со стороны Маркета',
            'UNPAID' => 'Ya.Market: Заказ оформлен, но еще не оплачен (если выбрана оплата при оформлении)',
        ];

        $this->orderSubStatusComments = [
            'STARTED' => 'Ya.Market: Заказ подтвержден, его можно начать обрабатывать.',

            //Возможные значения для заказа в статусе PENDING:
            'ANTIFRAUD' => 'Ya.Market: Маркет проверяет, является ли заказ мошенническим.',

            //Возможные значения для заказа в статусе CANCELLED:
            'DELIVERY_SERVICE_UNDELIVERED' => 'Ya.Market: служба доставки не смогла доставить заказ.',
            'PENDING_EXPIRED' => 'Ya.Market: магазин не ответил на запрос POST /order/accept о новом заказе в течение: 2 часов — при отгрузке в сортировочный центр или пункт приема, 30 минут — при отгрузке курьерам Яндекс Go.',
            'PROCESSING_EXPIRED' => 'Ya.Market: магазин не обработал заказ в течение семи дней.',
            'REPLACING_ORDER' => 'Ya.Market: покупатель решил заменить товар другим по собственной инициативе.',
            'RESERVATION_EXPIRED' => 'Ya.Market: покупатель не завершил оформление зарезервированного заказа в течение 10 минут.',
            'RESERVATION_FAILED' => 'Ya.Market: Маркет не может продолжить дальнейшую обработку заказа.',
            'SHOP_FAILED' => 'Ya.Market: магазин не может выполнить заказ.',
            'SHOP_PENDING_CANCELLED' => 'Ya.Market: магазин отклонил новый заказ в ответ на запрос POST /order/accept.',
            'WAREHOUSE_FAILED_TO_SHIP' => 'Ya.Market: вы не отгрузили товар со склада.',
            'USER_CHANGED_MIND' => 'Ya.Market: покупатель отменил заказ по собственным причинам.',
            'USER_NOT_PAID' => 'Ya.Market: покупатель не оплатил заказ (для типа оплаты PREPAID) в течение 30 минут.',
            'USER_REFUSED_DELIVERY' => 'Ya.Market: покупателя не устраивают условия доставки.',
            'USER_REFUSED_PRODUCT' => 'Ya.Market: покупателю не подошел товар.',
            'USER_REFUSED_QUALITY' => 'Ya.Market: покупателя не устраивает качество товара.',
            'USER_UNREACHABLE' => 'Ya.Market: не удалось связаться с покупателем.',

            //Возможные значения для заказа в статусе PICKUP
            'PICKUP_SERVICE_RECEIVED' => 'Ya.Market: заказ поступил в пункт выдачи.',

            //    Подстатус актуален только для заказов с типом доставки почтой, в пункт выдачи заказов и постаматы.
            'PICKUP_USER_RECEIVED' => 'Ya.Market: покупатель получил заказ.',

            //   Подстатус не является финальным. Если пользователь вернет заказ, подстатус изменится на PICKUP_SERVICE_RECEIVED.
        ];


        return $this;
    }

    private function validate()
    {
        if (!empty($this->request->server['HTTP_AUTHORIZATION'])) {
            if (!empty($this->config->get('config_yam_fbs_yaMarketToken'))) {
                if ($this->request->server['HTTP_AUTHORIZATION'] == $this->config->get('config_yam_fbs_yaMarketToken')) {
                    header('X-YAM-AUTH: FBS');
                    return 'FBS';
                }
            }

            if (!empty($this->config->get('config_yam_express_yaMarketToken'))) {
                if ($this->request->server['HTTP_AUTHORIZATION'] == $this->config->get('config_yam_express_yaMarketToken')) {
                    header('X-YAM-AUTH: EXPRESS');
                    return 'EXPRESS';
                }
            }
        }

        return false;
    }

    private function sendOrderToBitrix($order_id, $type = 'created')
    {
        $this->load->model('checkout/order');
        $this->load->model('kp/bitrixBot');

        if (!$this->config->get('config_bitrix_bot_enable')) {
            return false;
        }

        $this->createMappings();

        $order = $this->model_checkout_order->getOrder($order_id);

        if ($order['yam_status'] == 'DELIVERED') {
            return;
        }

        if ($order['yam_status'] == 'DELIVERY') {
            return;
        }

        if ($order['yam_status'] == 'PICKUP') {
            return;
        }

        $products = $this->model_checkout_order->getOrderProducts($order_id);

        if ($order) {
            $message = 'Что-то пришло в API, но возможно произошла ошибка добавления заказа, сообщите об этом кому-нибудь';
            $attach = [];

            if ($type == 'created') {
                if ($order['yam_fake']) {

                    $message = ':?:  Тестовый заказ ' . $order_id . ' в Yandex Market, можно пропустить';
                    $attach = [];

                } else {
                    if ($order['yam_express']) {
                        $message = ':!::!::!: Новый экспресс заказ! ' . $order_id . ' в Yandex Market';
                    } else {
                        $message = ':idea: Новый заказ ' . $order_id . ' в Yandex Market';
                    }

                    $attach = [
                        ['MESSAGE' => 'Код KitchenProfi: [B]' . $order['order_id'] . '[/B]'],
                        ['MESSAGE' => 'Код Yandex Market: [B]' . $order['yam_id'] . '[/B]'],

                        ['DELIMITER' => ['SIZE' => 200, 'COLOR' => "#c6c6c6"]],

                        ['MESSAGE' => 'Дата отгрузки: [B]' . $order['yam_shipment_date'] . '[/B]'],
                        ['MESSAGE' => 'Сумма: [B]' . $this->currency->format($order['total_national'], $this->config->get('config_regional_currency'), 1) . '[/B]'],

                        ['DELIMITER' => ['SIZE' => 200, 'COLOR' => "#c6c6c6"]],

                        ['MESSAGE' => 'Статус: [B]' . $order['yam_status'] . '[/B] ([I]' . $this->orderStatusComments[$order['yam_status']] . '[/I])'],
                        ['MESSAGE' => 'Подстатус: [B]' . $order['yam_substatus'] . '[/B] ([I]' . $this->orderSubStatusComments[$order['yam_substatus']] . '[/I])'],

                        ['DELIMITER' => ['SIZE' => 200, 'COLOR' => "#c6c6c6"]],
                    ];

                    foreach ($products as $product) {
                        $attach[] = ['MESSAGE' => $product['name'] . ', код ' . $product['product_id'] . ', артикул ' . $product['model']];
                    }
                }
            }

            if ($type == 'edit') {
                $message = ':!: Изменен заказ ' . $order_id . ' в Yandex Market';

                $attach = [
                    ['MESSAGE' => 'Код KitchenProfi: [B]' . $order['order_id'] . '[/B]'],
                    ['MESSAGE' => 'Код Yandex Market: [B]' . $order['yam_id'] . '[/B]'],
                    ['DELIMITER' => ['SIZE' => 200, 'COLOR' => "#c6c6c6"]],

                    ['MESSAGE' => 'Дата отгрузки: [B]' . $order['yam_shipment_date'] . '[/B]'],
                    ['MESSAGE' => 'Сумма: [B]' . $this->currency->format($order['total_national'], $this->config->get('config_regional_currency'), 1) . '[/B]'],

                    ['DELIMITER' => ['SIZE' => 200, 'COLOR' => "#c6c6c6"]],

                    ['MESSAGE' => 'Новый статус: [B]' . $order['yam_status'] . '[/B] ([I]' . $this->orderStatusComments[$order['yam_status']] . '[/I])'],
                    ['MESSAGE' => $order['yam_substatus'] ? ('Подстатус: [B]' . $order['yam_substatus'] . '[/B] ([I]' . $this->orderSubStatusComments[$order['yam_substatus']] . '[/I])') : '[I]Yandex ничего не передал сюда[/I]'],

                    ['DELIMITER' => ['SIZE' => 200, 'COLOR' => "#c6c6c6"]],
                ];

                foreach ($products as $product) {
                    $attach[] = ['MESSAGE' => $product['name'] . ', код ' . $product['product_id'] . ', артикул ' . $product['model']];
                }
            }

            $result = $this->model_kp_bitrixBot->sendMessage($message, $attach, 'chat75716');
            $this->log->debug($result);
        }
    }

    public function getLastStockUpdate()
    {
        $query = $this->db->query("SELECT date_modified FROM temp WHERE `key` LIKE('stock_last_sync') LIMIT 1");

        return $query->row['date_modified'];
    }

    private function getRequestBody($decode = true)
    {
        $inputJSON = file_get_contents('php://input');

        if (!$json = json_decode($inputJSON, true)) {
            $constants = get_defined_constants(true);
            $json_errors = array();
            foreach ($constants["json"] as $name => $value) {
                if (!strncmp($name, "JSON_ERROR_", 11)) {
                    $json_errors[$value] = $name;
                }
            }

            header('HTTP/1.0 400 Bad Request');
            die($json_errors[json_last_error()]);
            return false;
        }

        if ($decode) {
            return $json;
        } else {
            return json_encode($json);
        }
    }

    public function index()
    {
    }

    private function loadSettings($store_id)
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->loadSettings($store_id);

        if ($this->config->get('config_yam_offer_feed_template')) {
            $this->stock_path = [$this->config->get('config_yam_offer_feed_template')];
        }

        if ($this->config->get('config_yam_excludewords')) {
            $this->excluded_names = explode(PHP_EOL, $this->config->get('config_yam_excludewords'));
        }

        return $this;
    }

    public function setproducthidden()
    {
    }

    public function setproductnothidden()
    {
    }

    public function hiddenofferscron_HiddenOffersClient()
    {
        $this->load->model('catalog/product');
        ini_set('memory_limit', '2G');
        $stores = [0];

        $this->load->library('hobotix/Market/YandexMarketExtender');

        $hobotixYamClient = new \Yandex\Marketplace\Partner\Clients\HobotixYamClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
        $hiddenOffersClient = new \Yandex\Marketplace\Partner\Clients\HiddenOffersClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        $this->db->query("UPDATE product SET yam_hidden = 0");

        foreach ($stores as $store_id) {
            echoLine('[HIDDENOFFERS] ' . $store_id);

            $this->loadSettings($store_id);

            $jsonArray = [];
            $hiddenOffersObject = $hiddenOffersClient->getInfo($this->config->get('config_yam_fbs_campaign_id'), [], 'F7000001237E49D1');
        }
    }

    private function updateProductYamData($product_id, $data)
    {
        $this->db->query("DELETE FROM product_yam_data WHERE product_id = '" . (int)$product_id . "'");

        $this->db->query("INSERT INTO product_yam_data SET
				product_id 			= '" . (int)$product_id . "',
				yam_real_price 		= '" . (float)$data['price'] . "',
				yam_category_name	= '" . $this->db->escape($data['category_name']) . "',
				yam_category_id		= '" . (int)$data['category_id'] . "',
				yam_fees			= '" . $this->db->escape($data['tariffs']) . "',
				AGENCY_COMMISSION	= '" . (float)$data['AGENCY_COMMISSION'] . "',
				FEE					= '" . (float)$data['FEE'] . "'");

        if ((int)$data['market_sku'] < 0) {
            $this->db->query("UPDATE product SET yam_marketSku = '-1', yam_not_created = 1 WHERE product_id = '" . (int)$product_id . "'");
        } else {
            $this->db->query("UPDATE product SET yam_marketSku = '" . (int)$data['market_sku'] . "', yam_not_created = 0 WHERE product_id = '" . (int)$product_id . "'");
        }
    }

    public function skustatscron()
    {
        $this->load->model('catalog/product');
        ini_set('memory_limit', '2G');
        $stores = [0];

        $this->load->library('hobotix/Market/YandexMarketStatsExtender');

        $statsClient = new \Yandex\Marketplace\Partner\Clients\HobotixStatsClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
        $this->db->query("UPDATE product SET yam_hidden = 0 WHERE 1");
        $this->db->query("UPDATE product SET yam_not_created = 0 WHERE 1");

        foreach ($stores as $store_id) {
            echoLine('[STATS] ' . $store_id);

            $this->loadSettings($store_id);
            $sql = "SELECT DISTINCT(p.product_id), p.yam_product_id FROM product p WHERE yam_in_feed = 1 ";

            $query = $this->db->query($sql);

            $products = [];
            foreach ($query->rows as $row) {
                $products[] = $row;
            }

            $total = count($products);
            $iterations = ceil($total / $this->skuStatsLimit);

            for ($i = 1; $i <= $iterations; $i++) {
                $jsonArray = ['shopSkus' => []];

                echoLine('[SKUSTATS] Итерация ' . $i . ', товары с ' . ($this->skuStatsLimit * ($i - 1)) . ' по ' . $this->skuStatsLimit * $i);

                $slice = array_slice($products, $this->skuStatsLimit * ($i - 1), $this->skuStatsLimit);

                foreach ($slice as $product) {
                    if ($product['yam_product_id']) {
                        $jsonArray['shopSkus'][] = $product['yam_product_id'];
                    }
                }

                $statsResponse = $statsClient->getStatsBySkus($this->config->get('config_yam_fbs_campaign_id'), $jsonArray);

                $result = $statsResponse->getResult();
                $shopSkuses = $result->getAll();

                foreach ($shopSkuses as $oneSku) {

                    $tariffJson = [];
                    $Tariffs = $oneSku->getTariffs();

                    $AGENCY_COMMISSION = 0;
                    $FEE = 0;

                    foreach ($Tariffs as $Tariff) {
                        $tariffJson[] = [
                            'type' => $Tariff->getType(),
                            'percent' => $Tariff->getPercent(),
                            'amount' => $Tariff->getAmount(),
                        ];

                        if ($Tariff->getType() == 'AGENCY_COMMISSION') {
                            $AGENCY_COMMISSION = $Tariff->getAmount();
                        }

                        if ($Tariff->getType() == 'FEE') {
                            $FEE = $Tariff->getAmount();
                        }
                    }


                    $data = [
                        'product_id' => str_replace($this->config->get('config_yam_offer_id_prefix'), '', $oneSku->getShopSKU()),
                        'price' => $oneSku->getPrice(),
                        'market_sku' => $oneSku->getMarketSku(),
                        'category_id' => $oneSku->getCategoryId(),
                        'category_name' => $oneSku->getCategoryName(),

                        'tariffs' => json_encode($tariffJson),
                        'AGENCY_COMMISSION' => $AGENCY_COMMISSION,
                        'FEE' => $FEE
                    ];

                    $this->updateProductYamData($data['product_id'], $data);

                }
            }
        }
    }

    public function stockscron()
    {
        $this->load->model('catalog/product');
        ini_set('memory_limit', '2G');
        $stores = [0];

        $this->load->library('hobotix/Market/YandexMarketStocksClientExtender');

        $hobotixStocksClient = new \Yandex\Marketplace\Partner\Clients\HobotixStocksClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        foreach ($stores as $store_id) {
            echoLine('[ControllerYaMarketApi::stockscron] ' . $store_id, 'i');

            $this->loadSettings($store_id);

            $sql = "DELETE FROM yandex_stock_queue WHERE DATE(updated_at) <= DATE_SUB(NOW(), INTERVAL 1 DAY)";
            $query = $this->db->ncquery($sql);

            $sql = "UPDATE yandex_stock_queue SET stock = 0 WHERE stock < 0";
            $query = $this->db->ncquery($sql);

            $sql = "SELECT * FROM yandex_stock_queue";
            $query = $this->db->ncquery($sql);

            $products = [];
            foreach ($query->rows as $row) {
                $products[] = $row;
            }

            if ($products) {
                date_default_timezone_set("Europe/Moscow");

                $total = count($products);
                $iterations = ceil($total / $this->stocksLimit);

                for ($i = 1; $i <= $iterations; $i++) {

                    $jsonArray = ['skus' => []];

                    echoLine('[ControllerYaMarketApi::stockscron] Iteration ' . $i . ', products from ' . ($this->stocksLimit * ($i - 1)) . ' to ' . $this->stocksLimit * $i);

                    $slice = array_slice($products, $this->stocksLimit * ($i - 1), $this->stocksLimit);

                    foreach ($slice as $product) {
                        $jsonArray['skus'][] = [
                            'sku' => (string)$product['yam_product_id'],
                            'warehouseId' => (int)$this->config->get('config_yam_fbs_warehouse_id'),
                            'items' => [
                                [
                                    'type' => 'FIT',
                                    'count' => (int)$product['stock'],
                                    'updatedAt' => date('c', strtotime($product['updated_at']))
                                ]
                            ]
                        ];
                    }

                    $hobotixStocksResult = $hobotixStocksClient->updateStocks($this->config->get('config_yam_fbs_campaign_id'), $jsonArray);
                    echoLine('[ControllerYaMarketApi::stockscron] Answer is: ' . $hobotixStocksResult->getStatus());

                }

            }

            $query = $this->db->ncquery('TRUNCATE yandex_stock_queue');
        }
    }

    public function offerpricescron()
    {
        $this->load->model('catalog/product');
        ini_set('memory_limit', '2G');
        $stores = [0];

        $this->load->library('hobotix/Market/YandexMarketExtender');

        $hobotixYamClient = new \Yandex\Marketplace\Partner\Clients\HobotixYamClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        foreach ($stores as $store_id) {
            echoLine('[ControllerYaMarketApi::offerpricescron] ' . $store_id, 'i');

            $this->loadSettings($store_id);
            $this->db->query("DELETE FROM product_yam_recommended_prices WHERE store_id = '" . $store_id . "'");

            $sql = "SELECT DISTINCT(p.product_id), p.yam_product_id FROM product p WHERE yam_in_feed = 1";

            $query = $this->db->ncquery($sql);

            $products = [];
            foreach ($query->rows as $row) {
                $products[] = $row;
            }

            $total = count($products);
            $iterations = ceil($total / $this->offerPriceLimit);

            for ($i = 1; $i <= $iterations; $i++) {
                $jsonArray = ['offers' => []];

                echoLine('[ControllerYaMarketApi::offerpricescron] Iteration ' . $i . ', products from ' . ($this->offerPriceLimit * ($i - 1)) . ' to ' . $this->offerPriceLimit * $i);

                $slice = array_slice($products, $this->offerPriceLimit * ($i - 1), $this->offerPriceLimit);

                foreach ($slice as $product) {
                    if ($product['yam_product_id']) {
                        $jsonArray['offers'][] = ['offerId' => $product['yam_product_id']];
                    }
                }

                $offersResponseResult = $hobotixYamClient->getRecommendedPrices($this->config->get('config_yam_fbs_campaign_id'), $jsonArray);

                echoLine('[ControllerYaMarketApi::offerpricescron] Total products: ' . count($offersResponseResult['offers']), 's');

                foreach ($offersResponseResult['offers'] as $offer) {
                    $product_id = str_replace($this->config->get('config_yam_offer_id_prefix'), '', $offer['offerId']);

                    $sql = "INSERT INTO product_yam_recommended_prices SET product_id = '" . (int)$product_id . "', store_id = '" . $store_id . "', currency = '" . $this->db->escape($this->config->get('config_regional_currency')) . "' ";

                    foreach ($offer['priceSuggestion'] as $priceSuggestion) {
                        if (in_array($priceSuggestion['type'], $this->priceTypes)) {
                            $sql .= ", " . $priceSuggestion['type'] . " = '" . (float)$priceSuggestion['price'] . "'";
                        }
                    }

                    $this->db->query($sql);
                }
            }
        }
    }

    public function offerprice()
    {
        $this->load->model('catalog/product');
        $product_id = (int)trim($this->request->get['product_id']);
        $this->createMappings();

        if ($product = $this->model_catalog_product->getProduct($product_id)) {

            $pricesClient = new \Yandex\Marketplace\Partner\Clients\PriceClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
            $offersResponse = $pricesClient->getRecommendedPrices($this->config->get('config_yam_fbs_campaign_id'), ['offers' => [['offerId' => $product['yam_product_id']]]]);

            $result = $offersResponse->getResult();
            $recommendedPrices = $result->getOffers();

            $json = [];

            foreach ($recommendedPrices as $recommendedPrice) {

                $pricesSuggestion = $recommendedPrice->getPriceSuggestion();

                foreach ($pricesSuggestion as $priceSuggestion) {
                    $json[] = [
                        'type' => $priceSuggestion->getType(),
                        'price' => $this->currency->format($priceSuggestion->getPrice(), $this->config->get('config_regional_currency'), 1),
                        'explanation' => $this->priceSuggestMappings[$priceSuggestion->getType()]
                    ];
                }

            }

            header('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function receptiontransferact()
    {
        $this->load->library('hobotix/Market/YandexMarketExtender');

        $hobotixYamClient = new \Yandex\Marketplace\Partner\Clients\HobotixYamClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
        $response = $hobotixYamClient->getReceptionTransferAct($this->config->get('config_yam_fbs_campaign_id'));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . 'reception_transfer_act-' . $this->config->get('config_yam_fbs_campaign_id') . date('Y_m_d') . '.pdf"');
        echo $response;
    }

    public function receptiontransferact_express()
    {
        $this->load->library('hobotix/Market/YandexMarketExtender');

        $hobotixYamClient = new \Yandex\Marketplace\Partner\Clients\HobotixYamClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
        $response = $hobotixYamClient->getReceptionTransferAct($this->config->get('config_yam_express_campaign_id'));

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . 'reception_transfer_act-' . $this->config->get('config_yam_express_campaign_id') . date('Y_m_d') . '.pdf"');
        echo $response;
    }

    public function orderlabel()
    {
        $this->load->model('account/order');
        $order_id = (int)trim($this->request->get['order_id']);

        $orderProcessingClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        $yam_order = $this->model_account_order->getOrderYam($order_id);

        if ($yam_order) {
            $yam_id = $yam_order['yam_id'];

            if ($yam_order['yam_campaign_id']) {
                $yam_campaign_id = $yam_order['yam_campaign_id'];
            } else {
                $yam_campaign_id = $this->config->get('config_yam_fbs_campaign_id');
            }

        } else {
            $yam_id = false;
            $yam_campaign_id = false;
        }

        if ($yam_id) {
            $response = $orderProcessingClient->getDeliveryLabels($yam_campaign_id, $yam_id);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . 'yam-order-' . $order_id . '.pdf"');
            echo $response;
        }
    }

    public function boxlabel()
    {
        $this->load->model('account/order');
        $box_id = (int)trim($this->request->get['box_id']);

        $orderProcessingClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        if ($yamOrder = $this->model_account_order->getOrderByBoxID($box_id)) {
            $response = $orderProcessingClient->getDeliveryLabels($yamOrder['yam_campaign_id'], $yamOrder['yam_id'], $yamOrder['yam_shipment_id'], $box_id);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . 'yam-box-' . $yamOrder['order_id'] . '-' . $box_id . '.pdf"');
            echo $response;
        }
    }

    public function shipmentlabel()
    {
        $this->load->model('account/order');
        $shipment_id = (int)trim($this->request->get['shipment_id']);

        $orderProcessingClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        if ($yamOrders = $this->model_account_order->getYamOrdersByShipmentID($shipment_id)) {
            $pdf = new \Clegginabox\PDFMerger\PDFMerger;

            foreach ($yamOrders as $yamOrder) {
                $response = $orderProcessingClient->getDeliveryLabels($yamOrder['yam_campaign_id'], $yamOrder['yam_id'], $yamOrder['yam_shipment_id'], $yamOrder['yam_box_id']);
                file_put_contents(DIR_SYSTEM . 'temp/' . $yamOrder['yam_box_id'] . '.pdf', $response);
                $pdf->addPDF(DIR_SYSTEM . 'temp/' . $yamOrder['yam_box_id'] . '.pdf', 'all');
            }

            $pdf->merge('browser', 'yam-shipment-' . $this->request->get['shipment_id'] . '.pdf', 'P');
        }
    }

    public function ordertoyamqueue()
    {

        if (php_sapi_name() !== "cli") {
            die('cli only');
        }
        $this->load->library('hobotix/Market/YandexMarketExtender');
        $this->load->model('account/order');

        $query = $this->db->non_cached_query("SELECT * FROM yandex_queue LIMIT 1");

        $orderProcessingClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
        $hobotixYamClient = new \Yandex\Marketplace\Partner\Clients\HobotixYamClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        foreach ($query->rows as $row) {
            echoLine('[ControllerYaMarketApi::ordertoyamqueue] Working with: ' . $row['order_id'] . ' -> ' . $row['status'] . ' -> ' . $row['substatus']);

            $yam_order = $this->model_account_order->getOrderYam($row['order_id']);

            if ($yam_order) {
                $yam_id = $yam_order['yam_id'];

                if ($yam_order['yam_campaign_id']) {
                    $yam_campaign_id = $yam_order['yam_campaign_id'];
                } else {
                    $yam_campaign_id = $this->config->get('config_yam_fbs_campaign_id');
                }

            } else {
                $yam_id = false;
                $yam_campaign_id = false;
            }

            if ($yam_id) {
                try {
                    $updateOrderStatusResponse = $orderProcessingClient->updateOrderStatus($yam_campaign_id, $yam_id, ["order" => ["status" => $row['status'], "substatus" => $row['substatus']]]);

                    if ($row['status'] == 'PROCESSING') {
                        echoLine('[ControllerYaMarketApi::ordertoyamqueue]' . $row['order_id'] . ' -> ' . $yam_id . ' -> ' . $row['status'] . ' -> ' . $row['substatus'], 'i');
                        $this->db->non_cached_query("DELETE FROM yandex_queue WHERE order_id = '" . (int)$row['order_id'] . "'");
                    }

                    if ($row['status'] == 'CANCELLED') {
                        if ($updateOrderStatusResponse->getCancelRequested()) {

                            echoLine('[ControllerYaMarketApi::ordertoyamqueue]' . $row['order_id'] . ' -> ' . $yam_id . ' -> ' . $row['status'] . ' -> ' . $row['substatus'], 'i');
                            $this->db->non_cached_query("DELETE FROM yandex_queue WHERE order_id = '" . (int)$row['order_id'] . "'");

                        } else {
                        }
                    }

                } catch (\Yandex\Marketplace\Partner\Exception\PartnerRequestException $e) {

                    echoLine('ControllerYaMarketApi::ordertoyamqueue] Got error: ' . $row['order_id'] . ' -> ' . $yam_id . ' -> ' . $row['status'] . ' -> ' . $row['substatus'], 'e');
                    echoLine($e->getMessage());

                    $this->db->non_cached_query("DELETE FROM yandex_queue WHERE order_id = '" . (int)$row['order_id'] . "'");
                }

            } else {
                echoLine('[ControllerYaMarketApi::ordertoyamqueue] Is not in YAM ' . $row['order_id'] . ' -> ' . $yam_id . ' -> ' . $row['status'] . ' -> ' . $row['substatus'], 'e');
                $this->db->non_cached_query("DELETE FROM yandex_queue WHERE order_id = '" . (int)$row['order_id'] . "'");
            }
        }
    }

    public function stocks()
    {
        $this->load->model('catalog/product');

        if (!$this->validate()) {
            header('HTTP/1.0 403 Forbidden');
            die;
        }

        $lastStockUpdate = $this->getLastStockUpdate();

        $stocksClient = new \Yandex\Marketplace\Partner\Clients\StocksClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
        $stocks = $stocksClient->getStocks($this->getRequestBody(false));

        $json = array(
            'skus' => []
        );

        foreach ($stocks->getSkus() as $product_id) {
            $product = $this->model_catalog_product->getProductYAM($product_id, false);

            if ($product) {

                $json['skus'][] = [
                    'sku' => $product_id,
                    'warehouseId' => (int)$stocks->getWarehouseId(),
                    'items' => [[
                        'type' => 'FIT',
                        'count' => (int)$product[$this->config->get('config_warehouse_identifier_local')],
                        'updatedAt' => date('c', strtotime($lastStockUpdate))
                    ]]
                ];

            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function cart()
    {
        $this->load->model('catalog/product');

        if (!$this->validate()) {
            header('HTTP/1.0 403 Forbidden');
            die;
        }


        $orderProcessingFromMarketClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingBeruClient();
        $cart = $orderProcessingFromMarketClient->getCart($this->getRequestBody(false));

        $json = array(
            'cart' => ['items' => []]
        );

        foreach ($cart->getItems() as $item) {

            $product = $this->model_catalog_product->getProductYAM($item->getOfferId(), false);

            if ($product) {
                $json['cart']['items'][] = [
                    'feedId' => (int)$item->getFeedId(),
                    'offerId' => (string)$item->getOfferId(),
                    'count' => (int)$product[$this->config->get('config_warehouse_identifier_local')],
                    'delivery' => true
                ];
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function updateOrderYamStatus($order_id, $yam_status, $yam_substatus, $comment)
    {
        $comment = '';

        if (!empty($this->orderStatusMappings[$yam_status])) {
            $comment .= PHP_EOL;
            $comment .= $this->orderStatusComments[$yam_status];
            $comment .= PHP_EOL;
            $comment .= $this->orderSubStatusComments[$yam_substatus];

            $this->db->query("UPDATE `order` SET order_status_id = '" . (int)$this->orderStatusMappings[$yam_status] . "', yam_status = '" . $this->db->escape($yam_status) . "', yam_substatus = '" . $this->db->escape($yam_substatus) . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

            $this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$this->orderStatusMappings[$yam_status] . "', yam_status = '" . $this->db->escape($yam_status) . "', yam_substatus = '" . $this->db->escape($yam_substatus) . "',  notify = '0', comment = '" . $this->db->escape($comment) . "', date_added = NOW(), user_id = '" . (int)$this->apiUserID . "'");

            $this->Fiscalisation->addOrderToQueue($order_id);
        }
    }

    public function addBox($yam_id, $campaign_id, $shipment_id)
    {
        $this->load->model('account/order');

        $orderProcessingClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        try {
            $putInfoOrderBoxesResponse = $orderProcessingClient->putInfoOrderBoxes($campaign_id, $yam_id, $shipment_id, ['boxes' => [['fulfilmentId' => (string)($yam_id . '-1')]]]);

            if ($putInfoOrderBoxesResponse) {
                $boxes = $putInfoOrderBoxesResponse->getBoxes();

                foreach ($boxes as $box) {
                    $this->model_account_order->updateYamBox($yam_id, $box->getId());
                }
            }

        } catch (\Yandex\Marketplace\Partner\Exception\PartnerRequestException $e) {
        }
    }

    public function addBoxForSelfTest($yam_id, $campaign_id, $shipment_id)
    {
        $this->load->model('account/order');

        $orderProcessingClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));

        try {
            $putInfoOrderBoxesResponse = $orderProcessingClient->putInfoOrderBoxes($campaign_id, $yam_id, $shipment_id,
                ['boxes' => [[
                    'fulfilmentId' => (string)($yam_id . '-1'),
                    'weight' => 1000,
                    'width' => 12,
                    'height' => 22,
                    'depth' => 23
                ],
                    [
                        'fulfilmentId' => (string)($yam_id . '-2'),
                        'weight' => 1000,
                        'width' => 12,
                        'height' => 22,
                        'depth' => 23
                    ]
                ]]
            );
        } catch (\Yandex\Marketplace\Partner\Exception\PartnerRequestException $e) {
            var_dump($e);
        }

        $boxes = $putInfoOrderBoxesResponse->getBoxes();

        foreach ($boxes as $box) {
            $this->model_account_order->updateYamBox($yam_id, $box->getId());
        }
    }

    public function orderstatus()
    {
        $this->load->model('catalog/product');
        $this->load->model('checkout/order');
        $this->load->model('account/order');
        $this->load->model('sale/customer');
        $this->load->model('localisation/country');

        $this->createMappings();

        if (!$this->validate()) {
            header('HTTP/1.0 403 Forbidden');
            die;
        }

        $orderProcessingFromMarketClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingBeruClient();
        $order = $orderProcessingFromMarketClient->orderStatus($this->getRequestBody(false));

        $yam_order = $this->model_account_order->getYamOrder($order->getID());

        if ($yam_order) {
            $order_id = $yam_order['order_id'];
            $yam_campaign_id = $yam_order['yam_campaign_id'];
        } else {
            $order_id = false;
            $yam_campaign_id = false;
        }

        if ($order_id) {
            $this->updateOrderYamStatus($order_id, $order->getStatus(), $order->getSubStatus(), $order->getNotes());
            $this->sendOrderToBitrix($order_id, 'edit');

            if ($order->getStatus() == 'PROCESSING' && $order->getSubStatus() == 'STARTED') {

                $this->model_checkout_order->update($order_id, $this->config->get('config_treated_status_id'), 'Обработан автоматически из YAM API', false, $this->apiUserID);
                $this->model_checkout_order->update($order_id, $this->config->get('config_confirmed_order_status_id'), 'Подтвержден автоматически из YAM API', false, $this->apiUserID);

                $this->Fiscalisation->addOrderToQueue($order_id);

                foreach ($order->getDelivery()->getShipments() as $shipment) {
                    if ($shipment->getShipmentDate()) {
                        $yam_shipment_date = $shipment->getShipmentDate();
                        $yam_shipment_id = $shipment->getId();
                    }
                }

                try {
                    $this->addBox($order->getID(), $yam_campaign_id, $yam_shipment_id);
                } catch (\Yandex\Marketplace\Partner\Exception\PartnerRequestException $e) {

                }
            }

        } else {

            if ($order_id = $this->createOrder($order)) {
                $this->updateOrderYamStatus($order_id, $order->getStatus(), $order->getSubStatus(), $order->getNotes());
                $this->Fiscalisation->addOrderToQueue($order_id);

                if ($order->getStatus() == 'CANCELLED') {
                }

            } else {
                header('HTTP/1.0 400 Bad Request');
                die('Попытка изменения статуса несуществующего заказа. Запросу на смену статуса не предшествовал запрос на создание.');
            }
        }
    }

    public function orderaccept()
    {
        $this->load->model('catalog/product');
        $this->load->model('checkout/order');
        $this->load->model('account/order');
        $this->load->model('sale/customer');
        $this->load->model('localisation/country');

        if (!$this->validate()) {
            header('HTTP/1.0 403 Forbidden');
            die;
        }

        $campaign_type = $this->validate();
        if ($campaign_type == 'FBS') {
            $yam_campaign_id = $this->config->get('config_yam_fbs_campaign_id');
        } elseif ($campaign_type == 'EXPRESS') {
            $yam_campaign_id = $this->config->get('config_yam_express_campaign_id');
        }

        $orderProcessingFromMarketClient = new \Yandex\Marketplace\Partner\Clients\OrderProcessingBeruClient();
        $order = $orderProcessingFromMarketClient->acceptOrder($this->getRequestBody(false));

        $order_id = $this->createOrder($order, $yam_campaign_id);

        if (is_array($order_id)) {
            $order_id = (string)$order_id['order_id'];
        }

        $json['order'] = [
            'accepted' => true,
            'id' => (string)$order_id
        ];

        $this->response->setOutput(json_encode($json));
    }

    private function createOrder($order, $yam_campaign_id = false)
    {
        $json = ['order' => []];

        $yam_shipment_date = '';
        foreach ($order->getDelivery()->getShipments() as $shipment) {
            if ($shipment->getShipmentDate()) {
                $yam_shipment_date = $shipment->getShipmentDate();
                $yam_shipment_id = $shipment->getId();
            }
        }

        $yamCustomer = $this->model_sale_customer->getCustomer(YANDEX_MARKET_CUSTOMER_ID);
        $data = [
            'language_id' => $this->config->get('config_language_id'),
            'currency_id' => $this->currency->getId(),
            'currency_code' => $this->currency->getCode(),
            'currency_value' => $this->currency->getValue($this->currency->getCode()),
            'ip' => $this->request->server['REMOTE_ADDR'],
            'accept_language' => 'ru',
            'forwarded_ip' => '',
            'user_agent' => 'YAM API',
            'first_referrer' => 'YAM API',
            'last_referrer' => 'YAM API',
            'store_id' => $this->config->get('config_store_id'),
            'store_url' => $this->config->get('config_ssl'),
            'store_name' => $this->config->get('config_name'),
            'invoice_prefix' => '',

            'yam_id' => $order->getId(),
            'yam_fake' => $order->getFake(),
            'yam_shipment_date' => $yam_shipment_date,
            'yam_shipment_id' => $yam_shipment_id,
            'yam_campaign_id' => (int)$yam_campaign_id,
            'yam_express' => (int)($yam_campaign_id == $this->config->get('config_yam_express_campaign_id')),

            'comment' => $order->getNotes(),

            'affiliate_id' => 37,

            'customer_id' => YANDEX_MARKET_CUSTOMER_ID,
            'customer_group_id' => $yamCustomer['customer_group_id'],
            'firstname' => $yamCustomer['firstname'],
            'lastname' => $yamCustomer['lastname'],
            'telephone' => $yamCustomer['telephone'],
            'fax' => $yamCustomer['fax'],
            'email' => $yamCustomer['email'],
            'shipping_firstname' => $yamCustomer['firstname'],
            'shipping_lastname' => $yamCustomer['lastname'],
            'shipping_country_id' => $this->config->get('config_country_id'),
            'shipping_country' => $this->model_localisation_country->getCountry($this->config->get('config_country_id'))['name'],
            'shipping_city' => $order->getDelivery()->getRegion()->getName(),
            'shipping_address_1' => '',
            'shipping_address_2' => '',
            'shipping_company' => '',
            'shipping_company_id' => '',
            'shipping_tax_id' => '',
            'shipping_postcode' => '',
            'shipping_zone_id' => '',
            'shipping_code' => '',
            'shipping_address_format' => '',
            'shipping_zone' => '',
            'shipping_method' => '',
            'shipping_address_format' => '',


            'payment_firstname' => $yamCustomer['firstname'],
            'payment_lastname' => $yamCustomer['lastname'],
            'payment_country_id' => $this->config->get('config_country_id'),
            'payment_country' => $this->model_localisation_country->getCountry($this->config->get('config_country_id'))['name'],
            'payment_city' => $order->getDelivery()->getRegion()->getName(),
            'payment_address_1' => '',
            'payment_address_2' => '',
            'payment_company' => '',
            'payment_company_id' => '',
            'payment_tax_id' => '',
            'payment_postcode' => '',
            'payment_zone_id' => '',
            'payment_zone' => '',
            'payment_code' => '',
            'payment_address_format' => '',
            'payment_method' => '',
            'payment_secondary_method' => '',
            'payment_secondary_code' => '',

            'postcode' => '',
            'commission' => '',

        ];

        if ($order_id = $this->model_account_order->getYamOrder($order->getID())) {
        } else {

            $products = [];
            $sub_total = 0;
            $sub_total_national = 0;
            foreach ($order->getItems() as $item) {
                $product = $this->model_catalog_product->getProductYAM($item->getOfferId(), false);

                $price = $item->getPrice();

                if ($item->getSubsidy()) {
                    $price = $price + $item->getSubsidy();
                }

                $total = $price * $item->getCount();
                $price_national = $this->currency->convert($price, $this->config->get('config_currency_national'), $this->config->get('config_currency'));
                $total_national = $this->currency->convert($total, $this->config->get('config_currency_national'), $this->config->get('config_currency'));

                $sub_total += $total;
                $sub_total_national += $total_national;

                $products[] = [
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'quantity' => $item->getCount(),
                    'subtract' => $product['subtract'],
                    'price_national' => $price_national,
                    'total_national' => $total_national,
                    'price' => $price,
                    'total' => $total,
                    'from_stock' => true,
                    'option' => [],
                    'cost' => 0,
                    'download' => [],
                    'tax' => 0,
                    'reward' => 0,
                    'ao_id' => 0,
                    'set' => 0
                ];
            }


            $totals = [
                [
                    'code' => 'sub_total',
                    'title' => 'Сумма',
                    'text' => $this->currency->format($sub_total_national, $this->config->get('config_currency_national'), 1),
                    'value' => $sub_total,
                    'value_national' => $sub_total_national,
                    'sort_order' => $this->config->get('sub_total_sort_order')
                ],
                [
                    'code' => 'shipping',
                    'title' => 'Доставка',
                    'text' => $this->currency->format(0),
                    'value' => 0,
                    'value_national' => 0,
                    'sort_order' => $this->config->get('total_sort_order')
                ],
                [
                    'code' => 'total',
                    'title' => 'Итого',
                    'text' => $this->currency->format($sub_total_national, $this->config->get('config_currency_national'), 1),
                    'value' => $sub_total,
                    'value_national' => $sub_total_national,
                    'sort_order' => $this->config->get('total_sort_order')
                ],
            ];

            $data['total'] = $sub_total;
            $data['total_national'] = $sub_total_national;

            $data['vouchers'] = [];
            $data['products'] = $products;
            $data['totals'] = $totals;

            $order_id = $this->model_checkout_order->addOrder($data);
            $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'), 'Создан через YAM API', false, $this->apiUserID);

            $this->sendOrderToBitrix($order_id, 'create');
            $this->Fiscalisation->addOrderToQueue($order_id);

            return $order_id;
        }

        return $order_id;
    }

}