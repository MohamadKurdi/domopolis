<?

namespace hobotix\Supplier;

class SupplierManufacturer extends SupplierFrameworkClass {

	public function addManufacturer($name){		
		$this->db->query("INSERT INTO manufacturer SET name = '" . $this->db->escape($name) . "', date_added = NOW()");
		$manufacturer_id = $this->db->getLastId();

		$this->db->query("DELETE FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("INSERT INTO manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");

		$this->db->query("DELETE FROM manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		foreach ($this->registry->get('languages') as $language_code => $language) {
			$this->db->query("INSERT INTO manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language['language_id'] . "', seo_title = '" . $this->db->escape($name) . "'");
		}

		return (int)$manufacturer_id;
	}
	
}