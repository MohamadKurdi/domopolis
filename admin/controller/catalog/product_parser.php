<?php

class ControllerCatalogProductParser extends Controller {
    protected $error = array();

    public function index() {
        $brandCashArray = array();
        
        // Добавляем в очередь для парсинга
        if (isset($this->request->post['add_to_queue']) && $this->request->post['add_to_queue']) {
            $manufacturer_id = $this->request->post['manufacturer_id'];

            $this->db->query("INSERT INTO `parser_queue` (`manufacturer_id`, `add_date`) VALUES ('".(int)$manufacturer_id."', '".date('Y-m-d H:i:s')."')");
            $this->data['add_to_queue_message'] = true;
        }


        // Получаем очередь парсинга
        $query = $this->db->query("SELECT pq.*, m.name FROM `parser_queue` pq LEFT JOIN `manufacturer` m ON (pq.manufacturer_id = m.manufacturer_id) WHERE `pq`.`processed` = 0 ORDER BY `pq`.`parser_queue_id` DESC ");
        $this->data['queue_list'] = $query->rows;

        // Тут нужно вывести все процессы, относительно парсера
        $sql = "SELECT * FROM `".DB_PREFIX."setting` WHERE `group` = 'parser' ORDER BY `setting_id` DESC";
        $q = $this->db->query($sql);

        foreach ($q->rows as $parser) {
            $info = json_decode($parser['value']);
            $percent = explode(":", $info->count);

            if ($info->brand_id && !isset($brandCashArray[$info->brand_id])) {
                // Получаем название бренда, по ID
                $sql = "SELECT * FROM `".DB_PREFIX."manufacturer` WHERE `manufacturer_id` = '{$info->brand_id}'";
                $b = $this->db->query($sql);
                $brandCashArray[$info->brand_id] = isset($b->row['name'])?$b->row['name']:'Непонятное имя';
            }

            // Сколько времени работает парсер
            $work_time = $info->work_time;
            if ($work_time < 60) {
                $work_time .= " сек.";
            } elseif ($work_time < 3600) {
                $work_time = round($work_time / 60, 2);
                $work_time .= " мин.";
            } else {
                $work_time = round($work_time / 60 / 60, 2);
                $work_time .= " час.";
            }

            $this->data['parsers'][$parser['setting_id']] = array(
                'brand_name' => $info->brand_id ? $brandCashArray[$info->brand_id] : '?',
                'start_time' => date('H:i', $info->start_time),
                'start_date' => date('d F, Y', $info->start_time),
                'status' => $info->status,
                'process_percent' => $percent[1] ? ceil(($percent[0] / $percent[1]) * 100) : '',
                'url' => $this->url->link('catalog/product_parser/get_products', 'token=' . $this->session->data['token']."&id=".$parser['setting_id'], 'SSL'),
                'info' => $percent[0]." из ".$percent[1],
                'work_time' => $work_time,
                'info_product' => $info->work_time && $percent[0] ? round($info->work_time / $percent[0], 4).' сек.' : '&mdash;',
                'file' => isset($info->file_url) ? str_replace("/parser", "cron/parser", $info->file_url) : '#'

            );
        }

        $this->load->model('catalog/manufacturer');

        // Нужно получить все бренды
        $this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

        $this->template = 'catalog/product_list_parser.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function get_products () {
        $this->load->model('kp/price');
        $this->load->model('catalog/product');

        $sql = "SELECT * FROM `".DB_PREFIX."setting` WHERE `setting_id` = ".(int)$this->request->get['id'];
        $q = $this->db->query($sql);
        $info = json_decode($q->row['value']);

        // Получаем ID бренда
        $brand_id = $info->brand_id;

        if (isset($this->request->post['ok']) && $this->request->post['ok']) {
            $sql  = "UPDATE product p1, (SELECT * FROM product) p2 SET p1.cost = p2.parser_price, p1.parser_price = 0 where p1.product_id = p2.product_id AND p1.skip = 0 AND p2.parser_price > 0 AND p1.`manufacturer_id` = ".(int)$brand_id;
            $q = $this->db->query($sql);


            $sql = "UPDATE product p1, (SELECT * FROM product) p2 SET p1.special_cost = p2.parser_special_price, p1.parser_special_price = 0 where p1.product_id = p2.product_id AND p1.skip = 0 AND p2.parser_special_price > 0 AND p1.`manufacturer_id`= ".(int)$brand_id;
            $q = $this->db->query($sql);

            $sql = "UPDATE product SET parser_price = 0, parser_special_price = 0, skip = 0 where skip = 1 AND manufacturer_id = ".(int)$brand_id;
            $q = $this->db->query($sql);

            // Вызываем ф-цию для пересчета цена, и обновдения
            $this->load->model('catalog/product');
            $affected = $this->model_catalog_product->setNewPrices($brand_id);

            $this->data['update_success'] = true;
            $this->data['all_url'] = str_replace("&amp;", "&", $this->url->link('catalog/product_parser', 'token=' . $this->session->data['token'], 'SSL'));



            $tmp = (array)$info;
            $tmp['status'] = "processed";
            $sql = "UPDATE `".DB_PREFIX."setting` set `value` = '".json_encode($tmp)."' WHERE `setting_id` = ".(int)$this->request->get['id'];
            $this->db->query($sql);
        }


        // Получаем кол-ко всех товаров
        $product_total = 0;
        $sql = "SELECT count(*) FROM `".DB_PREFIX."product` WHERE ((`manufacturer_id` = ".(int)$brand_id.") AND ((`asin` <> '') OR (`source` <> '')) AND (`parser_price` <> '') AND (`parser_price` <> '0:0') AND (`parser_price` <> '0.00:0'))";
        $q = $this->db->query($sql);
        $product_total = $q->row['count(*)'];

        // Текущая страница
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }


        // Получаем все товары, данного бренда, у которые есть ASIN или Source
        $startLimit = ($page - 1) * $this->config->get('config_admin_limit');
        $limit = $this->config->get('config_admin_limit');
        $sql = "SELECT * FROM `".DB_PREFIX."product` WHERE ((`manufacturer_id` = ".(int)$brand_id.") AND ((`asin` <> '') OR (`source` <> '')) AND (`parser_price` <> '') AND (`parser_price` <> '0:0') AND (`parser_price` <> '0.00:0')) LIMIT {$startLimit}, {$limit}";
        $q = $this->db->query($sql);

        foreach ($q->rows as $p) {
            // $parserPrice = explode(":", $p['parser_price']);

            $parserPriceWithGuess = $this->model_kp_price->guessPrice($p['parser_price']);
            $parserPriceSpecialWithGuess = $this->model_kp_price->guessPrice($p['parser_special_price']);
            $skip = $p['skip'];

            $priceDiffStatus = '+';
            $priceDiff = $parserPriceWithGuess - $p['price'];
            if ($p['price'] > $parserPriceWithGuess) {
                $priceDiffStatus = '-';
                $priceDiff = $p['price'] - $parserPriceWithGuess;
            }

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$p['product_id'] . "' ORDER BY priority, price");
            $specialQuery = $query->rows;

            $special = 0;
            foreach ($specialQuery  as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                    $special = $product_special['price'];
                    break;
                }
            }

            $priceSpecialDiffStatus = '+';
            $pricespecialDiff = $parserPriceSpecialWithGuess - $special;
            if ($special > $parserPriceSpecialWithGuess) {
                $priceSpecialDiffStatus = '-';
                $pricespecialDiff = $special - $parserPriceSpecialWithGuess;
            }

            $digital = false;
            $percentSpecial = 0;

            $percent = round(abs((($p['price'] - $parserPriceWithGuess) / (($p['price'] + $parserPriceWithGuess) / 2)) * 100));

            if ($special) {
                $percentSpecial = round(abs((($special - $parserPriceSpecialWithGuess) / (($special + $parserPriceSpecialWithGuess) / 2)) * 100));
            }

            if (!$special || !$parserPriceSpecialWithGuess) {
                // $digital = true;
                $percentSpecial = '?';
            }
            /*if ($percent < 20 && isset($p['special'])) {
                if ($percentSpecial > 20) {
                    // $digital = true;
                }
            } else {
                $digital = true;
            }*/

            if ($percent > 20 || $percentSpecial > 20) {
                $digital = true;
            }


            $this->data['product'][] = array (
                'name' => $p['short_name'],
                'id' => $p['product_id'],
                'price' => $p['price'],
                'parser_price' => $p['parser_price'],
                'parser_price_with_guess' => $parserPriceWithGuess,
                'priceDiff' => number_format($priceDiff, 4, '.', '').', '.$percent.'%',
                'priceDiffStatus' => $priceDiffStatus,
                'special' => $special,
                'special_in_parser' => $p['parser_special_price'],
                'special_in_parser_with_guess' => $parserPriceSpecialWithGuess,
                'priceSpecialDiff' => number_format($pricespecialDiff, 4, '.', '').', '.$percentSpecial.'%',
                'priceSpecialDiffStatus' => $priceSpecialDiffStatus,
                'skip' => $skip,
                'product_url_edit' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'].'&product_id='.$p['product_id'], 'SSL'),
                'digital' => $digital
            );
        }

        // $this->url->link('catalog/product_parser/get_products', 'token=' . $this->session->data['token'], 'SSL'),


        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/product_parser/get_products', 'token=' . $this->session->data['token'] ."&id=".$this->request->get['id'] . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->template = 'catalog/product_list_parser_products.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->data['update_url'] = str_replace('&amp;', '&', $this->url->link('catalog/product_parser/update_product', 'token=' . $this->session->data['token'], 'SSL'));
        $this->response->setOutput($this->render());

    }
    
    public function update_product () {
        $type = $this->request->post['type'];
        if ($type == 'update_skip') {
            $productId = $this->request->post['id'];
            $value = $this->request->post['value'];

            $sql = "SELECT * FROM `".DB_PREFIX."product` WHERE `product_id` = ".(int)$productId;
            $q = $this->db->query($sql);

            if ($q->row['parser_price']) {

                $sql = "UPDATE `".DB_PREFIX."product` SET `skip` = '".$value."' WHERE `product_id` = ".(int)$productId;
                $q = $this->db->query($sql);
            }
        }

        if ($type == 'update_price') {
            $productId = $this->request->post['id'];
            $value = $this->request->post['value'];

            $sql = "SELECT * FROM `".DB_PREFIX."product` WHERE `product_id` = ".(int)$productId;
            $q = $this->db->query($sql);

            if ($q->row['parser_price']) {

                $sql = "UPDATE `".DB_PREFIX."product` SET `parser_price` = '".$value."' WHERE `product_id` = ".(int)$productId;
                $q = $this->db->query($sql);
            }

        }
        if ($type == 'update_special_price') {
            $productId = $this->request->post['id'];
            $value = $this->request->post['value'];

            $sql = "SELECT * FROM `".DB_PREFIX."product` WHERE `product_id` = ".(int)$productId;
            $q = $this->db->query($sql);

            if ($q->row['parser_price']) {

                $sql = "UPDATE `".DB_PREFIX."product` SET `parser_special_price` = '".$value."' WHERE `product_id` = ".(int)$productId;
                $q = $this->db->query($sql);
            }
        }

    }

    public function getGuessPrice ($price = false) {

    }
}

// 2430675
?>



