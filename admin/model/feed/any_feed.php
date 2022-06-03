<?php
#####################################################################################
#  Module Order Feed for Opencart 1.5.x From HostJars opencart.hostjars.com 		#
#####################################################################################

class ModelFeedAnyFeed extends Model {
	private $version = '0.1';
	
	public function createTable() {
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "hj_any_feed_feeds (id INT(11) AUTO_INCREMENT, name VARCHAR(256), settings BLOB, version VARCHAR(64), preset INT(1), fields BLOB, PRIMARY KEY (id))");
		$this->insertPresetFeeds();
	}
	
	public function deleteTable() {
		$this->db->query("DROP TABLE " . DB_PREFIX . "hj_any_feed_feeds");
	}
	
	public function getProfile($profile, $preset=0) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "hj_any_feed_feeds WHERE `name` = '". $this->db->escape($profile) ."' AND `preset` = '". $this->db->escape($preset). "'");
		return (isset($query->row['id'])) ?	$query->row : 0;
	}
	
	public function save($feeds) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "hj_any_feed_feeds WHERE `preset` != 1"); 
		
		//build the sql and insert each preset feed
		foreach($feeds as $name => $feed) {
			$sql = 'INSERT INTO ' . DB_PREFIX . 'hj_any_feed_feeds SET ';
			$values = array();
			foreach ($feed as $column => $value) {
				if($column == 'version' || $column == 'name' || $column == 'preset') {
					$values[] = '`' . $column . "`='" . $this->db->escape($value) . "'";
				} else {
					$values[] = '`' . $column . "`='" . $this->db->escape(json_encode($value)) . "'";
				}
			}
			$values[] = "`preset`='0'";
			$values[] = "`version`='" . $this->version . "'";
			$sql .= implode(',', $values);
			$this->db->query($sql);
		}
	}
	
	public function getProfiles() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "hj_any_feed_feeds WHERE `preset` = '0'");
		return (isset($query->row['id'])) ?	$query->rows : array();
	}
	
	public function getPresets() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "hj_any_feed_feeds WHERE `preset` = 1");
		return (isset($query->row['id'])) ?	$query->rows : array();
	}
	
	public function deleteProfile($profile) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "hj_any_feed_feeds WHERE `name` = '". $this->db->escape($profile) ."'");
	}
	
	private function insertPresetFeeds () {
		
		//get the preset fields
		$preset_feeds = $this->getPresetFeeds();

		//build the sql and insert each preset feed
		foreach($preset_feeds as $name => $feed) {
			$sql = 'INSERT INTO ' . DB_PREFIX . 'hj_any_feed_feeds SET ';
			$values = array();
			foreach ($feed as $column => $value) {
				if($column == 'version' || $column == 'name' || $column == 'preset') {
					$values[] = '`' . $column . "`='" . $this->db->escape($value) . "'";
				} else {
					$values[] = '`' . $column . "`='" . $this->db->escape(json_encode($value)) . "'";
				}				
			}
			$values[] = "`preset`='1'";
			$sql .= implode(',', $values);
			$this->db->query($sql);
		};
	}
	
	public function getPresetFeeds () {
		//array format
		/*
			'Feed Name' => array(
				'version' => 'feed version',
				'settings' => array (
					'type' => 'feed type csv/xml',
					'delimiter' => 'feed delimiter (, | ^ etc),
					'cdata' => 'use cdata tags for xml'
				),
				'fields' => array (
					'Field Display Name' => array (
						'field setting id' => 'setting value',
					),					
				)
			
			)
		 */
		$preset_feeds = array(
			'Empty' => array(
				'version' => '0.1',
				'settings' => array(
					'type' => 'CSV',
					'delimiter' => ',',
					'cdata' => '0',
					'url' => '',
					'enable' => 1,
				),
				'fields' => array (
				),
			),
			'Basic' => array(
				'version' => '0.1',
				'settings' => array(
					'type' => 'CSV',
					'delimiter' => ',',
					'cdata' => '0',
					'url' => '',
					'enable' => 1,
				),
				'fields' => array (
					'Name' => array(
						'settings' => array (
							'Field Name' => array(
								'type'=>'text',
								'name' => 'name',
								'value' => 'product_name',
							),
						)
					),
					'Description' => array(
						'settings' => array (
							'Field Name' => array(
								'type'=>'text',
								'name' => 'name',
								'value' => 'description',
							),
							'Strip HTML' => array(
								'type'=>'checkbox',
								'name' => 'strip_html',
								'value' => '1',
							),
						),
					),
					'Model' => array(
						'settings' => array (
							'Field Name' => array(
								'type'=>'text',
								'name' => 'name',
								'value' => 'model',
							),
						),
					),
					'Price' => array(
						'settings' => array (
							'Field Name' => array(
								'type'=>'text',
								'name' => 'name',
								'value' => 'price',
							),
						),
						
					),
					'Special Price' => array(
						'settings' => array (
							'Field Name' => array(
								'type'=>'text',
								'name' => 'name',
								'value' => 'special_price',
							),
						),
					),
					'Image' => array(
						'settings' => array (
							'Field Name' => array(
								'type'=>'text',
								'name' => 'name',
								'value' => 'image',
							),
						),
					),
					'Categories' => array(
						'settings' => array (
							'Field Name' => array(
								'type'=>'text',
								'name' => 'name',
								'value' => 'categories',
							),
						),
					),
				),
			),
// 			'Google Base' => array(
// 				'version' => '0.1',
// 				'settings' => array(
// 					'type' => 'CSV',
// 					'delimiter' => ',',
// 					'cdata' => '0',
// 					'url' => '',
// 					'enable' => 1,
// 				),
// 				'fields' => array (
// 					'Name' => array(
// 						'settings' => array (
// 							'Field Name' => array(
// 								'type'=>'text',
// 								'name' => 'name',
// 								'value' => '',
// 							),
// 						),
// 					),
// 				),
// 			),
// 			'Ebay' => array(
// 				'version' => '0.1',
// 				'settings' => array(
// 					'type' => 'XML',
// 					'delimiter' => ',',
// 					'cdata' => '0',
// 					'url' => '',
// 					'enable' => 1,
// 				),
// 				'fields' => array (
// 					'Name' => array(
// 						'settings' => array (
// 							'Field Name' => array(
// 								'type'=>'text',
// 								'name' => 'name',
// 								'value' => '',
// 							),
// 						),
// 					),
// 				),
// 			),
		);
		
		foreach ($preset_feeds as $key=>$value) {
			$name = $this->makeStandardName($key);
			$preset_feeds[$key]['name'] = $name;
		}
		return $preset_feeds;
	}
	
	public function makeStandardName($name) {
		$name = strtolower($name);
		$name = str_replace(' ', '_', $name);
		return $name;
	}
	public function makeNiceName($name) {
		$name = ucwords($name);
		$name = str_replace('_', ' ', $name);
		return $name;
	}
	public function getVersion() {
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('sale/order');
		if(!method_exists($this->model_sale_order, 'getOrderOption'))	{
			return '1.5.1';
		}
		else{
			return '1.5.2';
		}
	}
}
?>