<?php
class ModelPaymentinterplusplus extends Model { 
public function getMethod($address, $total) {
		$this->load->language('account/interplusplus');
		
		if ($total <= 0) {
			$status = true;
		} else {
			$status = false;
		}
		
    if ($this->config->get('interplusplus_status')) {
			$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('interplusplus_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (!$this->config->get('interplusplus_geo_zone_id')) {
				$status = TRUE;
			} elseif ($query->num_rows) {
				$status = TRUE;
			} else {
				$status = FALSE;
			}
		} else {
			$status = FALSE;
		}
    
		$method_data = array();
		if ($status) {  
			if ($this->config->get('interplusplus_name_attach')) {
				$metname = $this->config->get('interplusplus_name');
			}
			else{
				$metname = $this->language->get('text_title');
			}
			$method_data = array( 
				'code'       => 'interplusplus',
				'title'      => $metname,
				'sort_order' => $this->config->get('interplusplus_sort_order')
			);
		}
		
    	return $method_data;
  	}
}
?>