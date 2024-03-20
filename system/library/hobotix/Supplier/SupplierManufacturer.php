<?php

namespace hobotix\Supplier;

class SupplierManufacturer extends SupplierFrameworkClass {
    private $manufacturers = [];

    public function setManufacturers($supplier_id = null){
        if (!$supplier_id){
            $supplier_id = $this->supplier_id;
        }

        if (!$this->manufacturers){
            $query = $this->db->query("SELECT * FROM supplier_manufacturers WHERE supplier_id = '" . (int)$supplier_id . "'");

            foreach ($query->rows as $row){
                $this->manufacturers[$row['manufacturer']] = $row;
            }
        }
    }

    public function unsetManufacturers(){
        $this->manufacturers = [];
    }

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

    public function updateManufacturers($manufacturers, $supplier_id = null){
        if (!$supplier_id){
            $supplier_id = $this->supplier_id;
        }

        foreach ($manufacturers as $manufacturer){
            echoLine('[SupplierAdaptor::updateCategories] Got manufacturer ' . $manufacturer['vendor'] . ' for supplier ' . $supplier_id, 's');

            $this->db->query("INSERT IGNORE INTO supplier_manufacturers SET 
				supplier_id 		= '" . (int)$supplier_id . "',
				manufacturer 		= '" . $this->db->escape($manufacturer['vendor']) . "',
				manufacturer_full	= '" . $this->db->escape($manufacturer['vendor_full']) . "'");
        }
    }

    public function clearManufacturers($supplier_id = null){
        if (!$supplier_id){
            $supplier_id = $this->supplier_id;
        }

        $this->db->query("DELETE FROM supplier_manufacturers WHERE supplier_id = '" . (int)$supplier_id . "'");
    }

    public function getManufacturerMatch($supplier_manufacturer, $supplier_id = null){
        if (!$supplier_id){
            $supplier_id = $this->supplier_id;
        }

        $this->setManufacturers($supplier_id);

        if (!empty($this->manufacturers[$supplier_manufacturer])){
            return $this->manufacturers[$supplier_manufacturer];
        }

        return false;
    }
}