<?php
class Weight {
	private $weights = [];

	private $db 	= null;
	private $config = null;
	private $cache 	= null;

	public function __construct($registry) {
		$this->db 		= $registry->get('db');
		$this->config 	= $registry->get('config');
		$this->cache 	= $registry->get('cache');

		if (!$this->weights = $this->cache->get('weights')){
			$weight_class_query = $this->db->query("SELECT * FROM weight_class wc LEFT JOIN weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			$this->weights = [];
			foreach ($weight_class_query->rows as $result) {
				$this->weights[$result['weight_class_id']] = $result;
			}

			$this->cache->set('weights', $this->weights);
		}
	}

	public function convert($value, $from, $to) {
		if ($from == $to) {
			return $value;
		}

		if (isset($this->weights[$from])) {
			$from = $this->weights[$from]['value'];
		} else {
			$from = 0;
		}

		if (isset($this->weights[$to])) {
			$to = $this->weights[$to]['value'];
		} else {
			$to = 0;
		}	

		if ($from == 0){
			return 0;
		} else {
			return $value * ($to / $from);
		}
	}

	public function format($value, $weight_class_id, $decimal_point = '.', $thousand_point = ',') {
		if (isset($this->weights[$weight_class_id])) {
			return number_format($value, 2, $decimal_point, $thousand_point) . ' ' . $this->weights[$weight_class_id]['unit'];
		} else {
			return number_format($value, 2, $decimal_point, $thousand_point);
		}
	}

	public function getUnit($weight_class_id) {
		if (isset($this->weights[$weight_class_id])) {
			return $this->weights[$weight_class_id]['unit'];
		} else {
			return '';
		}
	}	
}