<?php

class ControllerModuleGeoip extends Controller {

    /**
     * @var GeoIP
     */
    private $geoip;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->geoip = $registry->get('geoip');
    }

    protected function index() {

        $this->saveInSession();

		$this->language->load('module/geoip');

        $this->data['text_zone'] = $this->language->get('text_zone');
        $this->data['text_search_zone'] = $this->language->get('text_search_zone');
        $this->data['text_search_placeholder'] = $this->language->get('text_search_placeholder');
        $this->data['text_choose_region'] = $this->language->get('text_choose_region');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');

        $this->data['http_server'] = $this->geoip->getCurrentHttpServer();

        $geoip_settings = $this->config->get('geoip_setting');
        $this->data['from_ajax'] = !empty($geoip_settings['from_ajax']);

        $short_city_name = $this->geoip->getShortCityName();
        $city_name = $this->geoip->getCityName();
        $zone_name = $this->geoip->getZoneName();
        $country_name = $this->geoip->getCountryName();
        $popup_city_name = $this->geoip->getPopupCityName();


        if (empty($geoip_settings['from_ajax'])) {

            if ($popup_city_name) {
                $zone = $popup_city_name;
            }
            elseif ($short_city_name) {
                $zone = $short_city_name;
            }
            elseif ($city_name) {
                $zone = $city_name;
            }
            elseif ($zone_name) {
                $zone = $zone_name;
            }
            elseif ($country_name) {
                $zone = $country_name;
            }
            else {
                $zone = $this->language->get('text_unknown');
            }
        }
        else {

            $zone = '';
        }

        $this->data['zone'] = $zone;

        $session = $this->registry->get('session');

        if (!empty($session->data['geoip_confirm']) || !empty($this->request->cookie['geoip_confirm'])) {

            $confirm_region = false;
        }
        else {

            $confirm_region = !empty($geoip_settings['popup_active']) && ($short_city_name || $zone_name);

            $time = !empty($geoip_settings['popup_cookie_time']) ? time() + $geoip_settings['popup_cookie_time'] : null;

            $session->data['geoip_confirm'] = 1;
            setcookie('geoip_confirm', 1, $time, '/', rtrim(preg_replace('#^http://(www\.)?#', '', HTTP_SERVER, 1), '/'));
        }

        $this->data['confirm_region'] = $confirm_region;

        $this->data['text_confirm_region'] = sprintf($this->language->get('text_confirm_region'), $short_city_name ? $short_city_name : $zone_name);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/geoip.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/geoip.tpl';
		} else {
			$this->template = 'default/template/module/geoip.tpl';
		}

		$this->response->setOutput($this->render());		
	}

    public function search() {

        $json = array();

        if (isset($this->request->get['term'])) {

            $json = $this->geoip->search($this->request->get['term']);
        }

	//	var_dump($json);
        echo $this->request->get['callback'] . '(' . json_encode($json) . ')';
        die;
    }

    public function save() {

        $fias_id = isset($this->request->get['fias_id']) ? $this->request->get['fias_id'] : 0;

        if ($fias_id && $this->geoip->save($fias_id)) {

            $this->forceSaveInSession();
            $this->geoip->setCurrency(true);
        }
    }

    public function getList() {

        $this->language->load('module/geoip');

        $this->data['text_search_placeholder'] = $this->language->get('text_search_placeholder');

        $this->load->model('module/geoip');

        $cities_rows = $this->model_module_geoip->getCities();

        $cities = array();

        foreach ($cities_rows as $row) {
            $cities[$row['fias_id']] = $row['name'];
        }

        $count_columns = 3;
        $this->data['columns'] = array_chunk($cities, ceil(count($cities) / $count_columns), true);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/geoip/cities_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/geoip/cities_list.tpl';
        }
        else {
            $this->template = 'default/template/module/geoip/cities_list.tpl';
        }

        $this->response->setOutput($this->render());
    }

    public function getCity() {

        $short_city_name = $this->geoip->getShortCityName();
        $city_name = $this->geoip->getCityName();
        $zone_name = $this->geoip->getZoneName();
        $country_name = $this->geoip->getCountryName();
        $popup_city_name = $this->geoip->getPopupCityName();

        if ($popup_city_name) {
            $zone = $popup_city_name;
        }
        elseif ($short_city_name) {
            $zone = $short_city_name;
        }
        elseif ($city_name) {
            $zone = $city_name;
        }
        elseif ($zone_name) {
            $zone = $zone_name;
        }
        elseif ($country_name) {
            $zone = $country_name;
        }
        else {
            $zone = $this->language->get('text_unknown');
        }

        echo $this->request->get['callback'] . '(' . json_encode(array('zone' => $zone)) . ')';
        die;
    }

    /**
     * Записывает адреса доставки и оплаты в сессию,
     * только если эти значения не были установлены ранее.
     * Не перезаписывает уже установленных значений.
     */
    private function saveInSession() {

        $session = $this->registry->get('session');
        $zone_id = $this->geoip->getZoneId();
        $country_id = $this->geoip->getCountryId();
        $city_name = $this->geoip->getCityName();
        $postcode = $this->geoip->getPostcode();

        if ($country_id) {

            if (empty($session->data['shipping_country_id'])) {
                $session->data['shipping_country_id'] = $country_id;

                if (empty($session->data['shipping_zone_id'])) {
                    $session->data['shipping_zone_id'] = 0;
                }
            }

            if (empty($session->data['payment_country_id'])) {
                $session->data['payment_country_id'] = $country_id;

                if (empty($session->data['payment_zone_id'])) {
                    $session->data['payment_zone_id'] = 0;
                }
            }

            if (empty($session->data['guest']['shipping']['country_id'])) {
                $session->data['guest']['shipping']['country_id'] = $country_id;
            }

            if (empty($session->data['guest']['payment']['country_id'])) {
                $session->data['guest']['payment']['country_id'] = $country_id;
            }
        }

        if ($zone_id) {

            if (empty($session->data['shipping_zone_id'])) {
                $session->data['shipping_zone_id'] = $zone_id;
            }

            if (empty($session->data['payment_zone_id'])) {
                $session->data['payment_zone_id'] = $zone_id;
            }

            if (empty($session->data['guest']['shipping']['zone_id'])) {
                $session->data['guest']['shipping']['zone_id'] = $zone_id;
            }

            if (empty($session->data['guest']['payment']['zone_id'])) {
                $session->data['guest']['payment']['zone_id'] = $zone_id;
            }
        }

        if ($postcode) {

            if (empty($session->data['shipping_postcode'])) {
                $session->data['shipping_postcode'] = $postcode;
            }

            if (empty($session->data['guest']['shipping']['postcode'])) {
                $session->data['guest']['shipping']['postcode'] = $postcode;
            }

            if (empty($session->data['guest']['payment']['postcode'])) {
                $session->data['guest']['payment']['postcode'] = $postcode;
            }
        }

        if ($city_name) {

            if (empty($session->data['guest']['shipping']['city'])) {
                $session->data['guest']['shipping']['city'] = $city_name;
            }

            if (empty($session->data['guest']['payment']['city'])) {
                $session->data['guest']['payment']['city'] = $city_name;
            }
        }
    }

    /**
     * Записывает адреса доставки и оплаты в сессию.
     * Используется, когда пользователь меняет регион вручную.
     */
    private function forceSaveInSession() {

        $session = $this->registry->get('session');
        $zone_id = $this->geoip->getZoneId();
        $country_id = $this->geoip->getCountryId();
        $city_name = $this->geoip->getCityName();
        $postcode = $this->geoip->getPostcode();

        if (!$this->customer->isLogged()) {

            $session->data['guest']['shipping']['zone_id'] = $session->data['guest']['payment']['zone_id'] = $zone_id;
            $session->data['guest']['shipping']['country_id'] = $session->data['guest']['payment']['country_id'] = $country_id;
            $session->data['guest']['shipping']['city'] = $session->data['guest']['payment']['city'] = $city_name;
            $session->data['guest']['shipping']['postcode'] = $session->data['guest']['payment']['postcode'] = $postcode;
        }

        $session->data['shipping_zone_id'] = $session->data['payment_zone_id'] = $zone_id;
        $session->data['shipping_country_id'] = $session->data['payment_country_id'] = $country_id;
        $session->data['shipping_postcode'] = $postcode;
    }
}