<?php
############################################################################################
#  AnyFeed Module for Opencart 1.5.x from HostJars http://opencart.hostjars.com    	       #
############################################################################################
class ModelFeedAnyFeed extends Model {
	
	public function getStockStatus() {
		$query = $this->db->query('SELECT stock_status_id, name FROM ' . DB_PREFIX . "stock_status WHERE language_id='" . (int)$this->config->get('config_language_id') . "'");
		return $query->rows;
	}
	
	public function getProfile($profile) {
		$query = $this->db->query("SELECT DISTINCT * FROM hj_any_feed_feeds WHERE `name` = '". $this->db->escape($profile) ."' AND `preset` = '0'");
		return (isset($query->row['id'])) ?	$query->row : 0;
	}
	
	public function getSeoKeyword($product_id){
		$query = $this->db->query("SELECT `keyword` FROM url_alias WHERE query = 'product_id=" . $this->db->escape($product_id) . "'");
		return (isset($query->row['keyword'])) ? $query->row['keyword'] : '';
	}

    public function getProductFilters($product_id) {
        $filters_ids = $this->db->query("
			SELECT f.`filter_id`, f.`filter_group_id`  FROM product_filter AS pf
			LEFT JOIN filter AS f
			    ON pf.`filter_id` = f.`filter_id`
			WHERE pf.`product_id` = '" . (int)$product_id. "'");

        $filters = array();
        foreach ($filters_ids->rows as $filters_id) {
            $filter_description = $this->db->query("
                SELECT fd.`name` FROM filter_description AS fd
                WHERE fd.`filter_id` = '" . (int)$filters_id['filter_id'] . "'
                AND fd.`language_id` = '" . (int)$this->config->get('config_language_id'). "'");
            $filter_group_description = $this->db->query("
                SELECT fgd.`name` FROM filter_group_description AS fgd
                WHERE fgd.`filter_group_id` = '" . (int)$filters_id['filter_group_id'] . "'
                AND fgd.`language_id` = '" . (int)$this->config->get('config_language_id'). "'");

            $filters[] = $filter_group_description->row['name'].' > '.$filter_description->row['name'];
        }
        return $filters;
    }

	public function getAttributes() {
		$query = $this->db->query("
			SELECT ad.name 
			FROM `product_attribute` AS pa 
			INNER JOIN `attribute_description` AS ad
			ON pa.attribute_id = ad.attribute_id
			GROUP BY pa.attribute_id");
		return ($query->num_rows > 0) ? $query->rows : '';
	}
	
	public function getMaxCategories() {
		$query = $this->db->query("SELECT count(*) AS maximum FROM `product_to_category` GROUP BY `product_id` ORDER BY count(*) DESC LIMIT 0,1");
		return (isset($query->row['maximum'])) ? $query->row['maximum'] : '';
	}

	public function getProductOptionNames() {
		$query = $this->db->query("SELECT `product_option`.`option_id`, `option_description`.`name` FROM `product_option` JOIN `option_description` on `product_option`.`option_id` = `option_description`.`option_id` GROUP BY `product_option`.`option_id`");
		$names = array();
		foreach ($query->rows as $row) {
			$names[] = $row['name'];
		}
		return $names;
	}

	public function getMaxAttributes() {
		$query = $this->db->query("SELECT count(*) AS maximum FROM `product_attribute` GROUP BY `product_id` ORDER BY count(*) DESC LIMIT 0,1");
		return (isset($query->row['maximum'])) ? $query->row['maximum'] : '';
	}

    public function getMaxFilters() {
        $query = $this->db->query("SELECT count(*) AS maximum FROM `product_filter` GROUP BY `product_id` ORDER BY count(*) DESC LIMIT 0,1");
        return (isset($query->row['maximum'])) ? $query->row['maximum'] : '';
    }

	public function getMaxImages() {
		$query = $this->db->query("SELECT count(*) AS maximum FROM `product_image` GROUP BY `product_id` ORDER BY count(*) DESC LIMIT 0,1");
		return (isset($query->row['maximum'])) ? $query->row['maximum'] : '';
	}

    public function isMijo(){
        $query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'mijo_extensions'");
        return ($query->num_rows > 0) ? true : false;
    }
}
?>