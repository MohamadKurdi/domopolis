<?php
class Length {
	private $lengths = [];

	private $db 	= null;
	private $config = null;
	private $cache 	= null;

	public function __construct($registry) {
		$this->db 		= $registry->get('db');
		$this->config 	= $registry->get('config');
		$this->cache 	= $registry->get('cache');

		if (!$this->lengths = $this->cache->get('lengths')){
			$length_class_query = $this->db->query("SELECT * FROM length_class lc LEFT JOIN length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			$this->lengths = [];
			foreach ($length_class_query->rows as $result) {
				$this->lengths[$result['length_class_id']] = $result;
			}
			
			$this->cache->set('lengths', $this->lengths);
		}
	}

	public function convert($value, $from, $to) {
		if ($from == $to) {
			return $value;
		}

		if (isset($this->lengths[$from])) {
			$from = $this->lengths[$from]['value'];
		} else {
			$from = 0;
		}

		if (isset($this->lengths[$to])) {
			$to = $this->lengths[$to]['value'];
		} else {
			$to = 0;
		}		
		
		if ($from == 0){
			return 0;
		}

		return $value * ($to / $from);
	}

	public function format($value, $length_class_id, $decimal_point = '.', $thousand_point = ',') {
		if (isset($this->lengths[$length_class_id])) {
			return number_format($value, 2, $decimal_point, $thousand_point) . ' ' . $this->lengths[$length_class_id]['unit'];
		} else {
			return number_format($value, 2, $decimal_point, $thousand_point);
		}
	}

	public function getUnit($length_class_id) {
		if (isset($this->lengths[$length_class_id])) {
			return $this->lengths[$length_class_id]['unit'];
		} else {
			return '';
		}
	}		
}