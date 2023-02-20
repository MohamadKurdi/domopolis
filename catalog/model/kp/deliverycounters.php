<?
class ModelKpDeliveryCounters extends Model {		
	private $length_class_id = 1;
	private $weight_class_id = 1;
	private $cdekStep = 5;
	private $defaultProducts = '{"2489512::::": {"key": "2489512::::","product_id": "2489512","from_stock": "","current_in_stock": "1","fully_in_stock": "1","amount_in_stock": "7","is_special_offer": "","is_special_offer_present": "","ao_id": "0","name": "Товар какой-то","model": "1282409964","manufacturer_id": "202","manufacturer": "WMF","shipping": "0","image": "","option": {},"download": {},"quantity": "1","minimum": "1","points_only_purchase": "0","subtract": "1","stock": "1","currency": "","price_old": "119.0000","saving": "-34","price": "78.936842105263","price_national": "5000","total": "78","total_national": "5000","reward": "0","points": "0","tax_class_id": "0","weight": "300","weight_class_id": "8","length": "1382.00000000","width": "976.00000000","height": "409.00000000","length_class_id": "1","profile_id": "0","profile_name": "","recurring": "","recurring_frequency": "0","recurring_price": "0","recurring_cycle": "0","recurring_duration": "0","recurring_trial": "0","recurring_trial_frequency": "0","recurring_trial_price": "0","recurring_trial_cycle": "0","recurring_trial_duration": "0","set": "0","childProductArray": {},"set_name": ""  }}';


	private function _normalizeDate($value = '') {
		return str_replace('-', '.', $value);
	}

	private function _clear($value) {
		$value = mb_convert_case($value, MB_CASE_LOWER, "UTF-8");
		return trim($value);
	}

	private function declination($number, $titles) {  
		$cases = array (2, 0, 1, 1, 1, 2);  
		return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];  
	}

	private function updateNovaPoshtaDeliveryTermsInDatabase($novaposhta_city_guid){
		$result = array();

		$this->load->library('hobotix/Shipping/NovaPoshta');
		$novaPoshta = new \hobotix\shipping\NovaPoshta($this->registry);

		$deliveryPeriod = $novaPoshta->getDeliveryDate($novaposhta_city_guid, date('y.m.d'));

		if ($deliveryPeriod){
			$query = $this->db->query("UPDATE  novaposhta_cities_ww  SET deliveryPeriod = '" . (int)$deliveryPeriod . "' WHERE Ref = '" . $this->db->escape($novaposhta_city_guid) . "'");
		}

		return $deliveryPeriod;
	}

	//Новая Почта
	public function getNovaPoshtaDeliveryTerms($novaposhta_city_guid){

		$deliveryPeriod = false;

		$query = $this->db->query("SELECT deliveryPeriod FROM novaposhta_cities_ww WHERE Ref = '" . $this->db->escape($novaposhta_city_guid) . "'");

		if ($query->num_rows && $query->row['deliveryPeriod']){
			$deliveryPeriod = $query->row['deliveryPeriod'];					
		}

		if (!$deliveryPeriod || mt_rand(0,10) == 5){
			$deliveryPeriod = $this->updateNovaPoshtaDeliveryTermsInDatabase($novaposhta_city_guid);
		}	

		return $deliveryPeriod;

	}

		
	private function _convertItem(&$value, $key, $length_class_id) {
		$value = $this->length->convert($value, $length_class_id, $this->length_class_id);
	}

	private function updateCDEKDeliveryTermsInDatabase($cdek_city_code){
		$result = array();
		$deliveryPeriodMin = false;
		$deliveryPeriodMax = false;

		$tariffs = $this->guessCDEKPriceAjax($cdek_city_code, 4, true);

		foreach ($tariffs as $tariff){
			if (!empty($tariff['deliveryPeriodMin'])){
				$deliveryPeriodMin = $tariff['deliveryPeriodMin'];					
			}

			if (!empty($tariff['deliveryPeriodMax'])){
				$deliveryPeriodMax = $tariff['deliveryPeriodMax'];					
			}

			if ($deliveryPeriodMin && $deliveryPeriodMax){
				break;
			}
		}

		$query = $this->db->query("UPDATE cdek_cities SET deliveryPeriodMin = '" . (int)$deliveryPeriodMin . "', deliveryPeriodMax = '" . (int)$deliveryPeriodMax . "' WHERE code = '" . (int)$cdek_city_code . "'");

		return array(
			'deliveryPeriodMin' => $deliveryPeriodMin,
			'deliveryPeriodMax' => $deliveryPeriodMax
		);
	}

	public function getCDEKDeliveryTerms($cdek_city_code){
		$result = array();

		if ($cdek_city_code){
			$query = $this->db->query("SELECT deliveryPeriodMin, deliveryPeriodMax FROM cdek_cities WHERE code = '" . (int)$cdek_city_code . "'");

			if ($query->num_rows && $query->row['deliveryPeriodMin'] && $query->row['deliveryPeriodMax']){
				$result = array(
					'deliveryPeriodMin' => $query->row['deliveryPeriodMin'],
					'deliveryPeriodMax' => $query->row['deliveryPeriodMax'],
				);										
			}

				//Если в БД нету, либо 10% вероятность, то обновляем информацию в базе
			if (!$result || mt_rand(0,10) == 5){
				$result = $this->updateCDEKDeliveryTermsInDatabase($cdek_city_code);
			}								
		}

		return $result;
	}


	public function guessCDEKPriceAjax($cdek_city_code, $type, $return_full_data = false){
		$output = array('success' => false);

		$city_from_id = $this->config->get('cdek_city_from_id');			


		return json_encode($output);

	}

	private function getCDEKDiscount($total, $tariff_Id = 0, $geo_zones = array()) {						
		return $discounts;
	}

		/*
			* Устанавливаем режим доставки (дверь-дверь=1, дверь-склад=2, склад-дверь=3, склад-склад=4)
		*/
			private function getCDEKTariff($tariff_id){
				$cdek_tariff_list = $this->config->get('cdek_tariff_list');

				if (!empty($cdek_tariff_list[$tariff_id])){
					return $cdek_tariff_list[$tariff_id];
				}

				return false;
			}

		}				