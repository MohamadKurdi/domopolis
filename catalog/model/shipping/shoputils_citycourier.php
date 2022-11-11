<?php
/*
* Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/

class ModelShippingShoputilsCitycourier extends Model {

    private $_tablename = 'shoputils_citycourier_description';

	function getQuote($address) {
		$this->load->language('shipping/shoputils_citycourier');

		if ($this->config->get('shoputils_citycourier_status')) {
      		$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shoputils_citycourier_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

      		if (!$this->config->get('shoputils_citycourier_geo_zone_id')) {
        		$error = false;
      		} elseif ($query->num_rows) {
        		$error = false;
      		} else {
        		$error = true;
      		}
		} else {
			$error = true;
		}

        if (($total = $this->cart->getSubTotal()) < $this->config->get('shoputils_citycourier_minimal_order')) {
			$error = sprintf($this->language->get('error_minimal_order'), $this->currency->format($this->config->get('shoputils_citycourier_minimal_order')));
		}

        $percent = (float)$this->config->get('shoputils_citycourier_percent');

        $cost = floor($total * ($percent / 100));

        $minprice = (float)$this->config->get('shoputils_citycourier_minimal_price');
        if (!empty($minprice)) {
            $cost = $minprice > $cost ? $minprice : $cost;
        }

        $free_shipping = (float)$this->config->get('shoputils_citycourier_free_shipping');
        if (!empty($free_shipping)) {
            $cost = $total > $free_shipping ? 0 : $cost;
        }

        if ($cost == 0){
           $text = $this->language->get('text_free_shipping');
        } else {
           $text = $this->currency->format($cost);
        }

        $langdata = $this->getDescriptions((int)$this->config->get('config_language_id'));
        $title = empty($langdata['name']) ? $this->language->get('text_title') : $langdata['name'];
        $description = empty($langdata['description']) ? $this->language->get('text_description') : $langdata['description'];

        $method_data = array(
          'code'         => 'shoputils_citycourier',
          'title'      => $title,
          'sort_order' => $this->config->get('shoputils_citycourier_sort_order'),
        );

		if (!$error) {
			$quote_data = array();

      		$quote_data['shoputils_citycourier'] = array(
        		'code'           => 'shoputils_citycourier.shoputils_citycourier',
        		'title'        => $title,
                'description'  => $description,
        		'cost'         => $cost,
        		'tax_class_id' => 0,
				'text'         => $text
      		);

      		$method_data['quote'] = $quote_data;
      		$method_data['error'] = false;
		} else {
            $method_data = false;
        }

		return $method_data;
	}

    public function getDescriptions($language_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . $this->_tablename . " WHERE language_id=' " .$language_id."'");
        if (isset($query->rows[0])){
            $rows = $query->rows[0];
        } else {
            $rows = array();
        }
        return $rows;
    }
}
?>