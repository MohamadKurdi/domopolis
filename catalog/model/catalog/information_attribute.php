<?php
class ModelCatalogInformationAttribute extends Model {
    public function getInformation($information_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM information_attribute i LEFT JOIN information_attribute_description id ON (i.information_attribute_id = id.information_attribute_id) LEFT JOIN information_attribute_to_store i2s ON (i.information_attribute_id = i2s.information_attribute_id) WHERE i.information_attribute_id = '" . (int)$information_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1'");
        return $query->row;
    }

    public function getInformations($data = array()) {
        $sql = "SELECT * FROM information_attribute i LEFT JOIN information_attribute_description id ON (i.information_attribute_id = id.information_attribute_id) LEFT JOIN information_attribute_to_store i2s ON (i.information_attribute_id = i2s.information_attribute_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (isset($data['igroup'])){
            $sql .= " AND i.igroup LIKE('" . $this->db->escape($data['igroup']) . "')";
        }

        $sql .= " AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getInformationLayoutId($information_id) {
        $query = $this->db->query("SELECT * FROM information_attribute_to_layout WHERE information_attribute_id = '" . (int)$information_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return false;
        }
    }
}
?>