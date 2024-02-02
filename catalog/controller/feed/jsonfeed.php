<?php
class ControllerFeedJSONFeed extends Controller {
	private $sql 	= "SELECT product_id FROM product 
						WHERE manufacturer_id NOT IN (202, 201, 609) 
						AND product_id NOT IN (SELECT product_id FROM supplier_products) 
						AND status = 1";
	private $file 	= 'products_feed0.json';

	public function getCategories() {
		$sql = "SELECT cp.category_id AS category_id, 
		c.tnved, 
		c.priceva_enable, 
		c.hotline_enable, 
		c.deletenotinstock,
		c.overload_max_wc_multiplier, 
		c.overload_max_multiplier, 
		c.overload_ignore_volumetric_weight, 
		c.intersections, 
		c.google_category_id, 
		c.hotline_category_name,
		c.yandex_category_name,
		cd2.menu_name, 
		cd2.alternate_name,
		cd1.name as simple_name, 
		c.parent_id, 
		c.need_reprice, 
		c.last_reprice, 
		c.sort_order,
		c.status,
		(SELECT menu_icon FROM category c4 WHERE c4.category_id = cp.category_id) as menu_icon, 
		(SELECT image FROM category c5 WHERE c5.category_id = cp.category_id) as image, 
		GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name
		FROM category_path cp 
		LEFT JOIN category c ON (cp.path_id = c.category_id) 
		LEFT JOIN category_description cd1 ON (c.category_id = cd1.category_id) 
		LEFT JOIN category_description cd2 ON (cp.category_id = cd2.category_id) 
		WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";				
		
		$sql .= " GROUP BY cp.category_id ORDER BY name";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function feed(){		
		if (!is_cli()){
			die('cli only!');
		}

		$this->load->model('catalog/product');

		$json = [
			'products' 		=> [],
			'categories' 	=> $this->getCategories(),
		];
		$query = $this->db->query($this->sql);

		$total 	= $query->num_rows;
		$i 		= 1;
		echoLine('[ControllerFeedJSONFeed:feed] Total products: ' . $total, 'i');
		foreach ($query->rows as $row){
			if ($i % 10 == 0){
				echoSimple($i, 'w');
			}

			$product = $this->model_catalog_product->getProduct($row['product_id']);

			if ($product){
				$json['products'][$row['product_id']] = [];			

				$json['products'][$row['product_id']]['product'] 		= $this->model_catalog_product->getProduct($row['product_id']);
				$json['products'][$row['product_id']]['images'] 		= $this->model_catalog_product->getProductImages($row['product_id']);
				$json['products'][$row['product_id']]['descriptions'] 	= $this->model_catalog_product->getProductDescriptions($row['product_id']);
				$json['products'][$row['product_id']]['attributes'] 	= $this->model_catalog_product->getProductAttributesFlat($row['product_id']);
				$json['products'][$row['product_id']]['attributes_full']= $this->model_catalog_product->getProductAttributesFull($row['product_id']);			
			} else {
				echoSimple($row['product_id'], 'e');
			}

			$i++;	
		}

		file_put_contents(DIR_REFEEDS . $this->file, json_encode($json));
		echoLine('[ControllerFeedJSONFeed:feed] Wrote to file ' . DIR_REFEEDS . $this->file, 's');
	}

}