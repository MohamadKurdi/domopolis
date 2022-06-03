<?php
class ModelShippingPickupAdvanced extends Model
{
	public function install()
	{
		$this->db->query('ALTER TABLE `' . DB_PREFIX . 'setting` CHANGE `value` `value` LONGTEXT');
		$this->db->query('ALTER TABLE `' . DB_PREFIX . 'order` CHANGE `shipping_method` `shipping_method` VARCHAR(512)');
		$this->db->query('ALTER TABLE `' . DB_PREFIX . 'order_total` CHANGE `title` `title` VARCHAR(512)');
	}
}
?>
