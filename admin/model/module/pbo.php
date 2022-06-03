<?php
class ModelModulePbo extends Model
{
	public function getStandardOptions()
	{
		$query = $this->db->query("SELECT od.name "
		. " , o.option_id "
		. " , o.type "
		. " FROM " . DB_PREFIX . "option_description od "
		. " LEFT JOIN `" . DB_PREFIX . "option` o "
		. " ON o.option_id = od.option_id "
		. " WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'"
		. " AND (o.type = 'radio' "
		. " OR o.type = 'select' "
		. " OR o.type = 'image' "
		. " ) "
		. " ORDER BY o.type ASC, od.name ASC ");
		
		return $query->rows;
	}
}
?>