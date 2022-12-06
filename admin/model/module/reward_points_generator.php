<?php
class ModelModuleRewardPointsGenerator extends Model {
	
	public function generateRewardPoints($data) {

		$total = 0;

		$query = $this->db->query("SELECT tax_class_id,COUNT(tax_class_id) as count FROM product GROUP BY tax_class_id");

		foreach($query->rows as $row) {

			if (!empty($row['tax_class_id']) && !empty($data['use_tax_class'])) {
				$rates = $this->getTaxRateByTaxClass($row['tax_class_id']);

				$PRICE = "((p.price * " . $rates['percentage'] . ") + (" . $rates['fixed'] . "))";
			} else {
				$PRICE = "p.price";
			}

			$this->db->query("UPDATE product AS p SET p.points = CEILING(" . $PRICE . " * " . (int)$data['unit'] . " / " . (int)$data['points'] . ") WHERE tax_class_id = " . (int)$row['tax_class_id']);

		/*	
			$this->db->query("UPDATE product_option_value AS pov JOIN product AS p ON pov.product_id = p.product_id SET pov.points = CEILING(" . $PRICE . " * " . (int)$data['unit'] . " / " . (int)$data['points'] . ") WHERE p.tax_class_id = " . (int)$row['tax_class_id']);
		*/
			$this->db->query("DELETE pr.* FROM product_reward AS pr JOIN product AS p ON pr.product_id = p.product_id WHERE p.tax_class_id = " . (int)$row['tax_class_id']);

			if (isset($data['product_reward'])) {
				foreach ($data['product_reward'] as $customer_group_id => $value) {
					$this->db->query("INSERT INTO product_reward (product_id, customer_group_id, points) SELECT p.product_id, '" . (int)$customer_group_id . "', FLOOR(" . $PRICE . " * " . (int)$value['points'] . " / " . (int)$data['unit'] . ") FROM product AS p WHERE p.tax_class_id = " . (int)$row['tax_class_id']);

					if (!empty($data['product_reward'][$customer_group_id]['no_generate_special'])) {
						$query = $this->db->query("
							SELECT
								GROUP_CONCAT(tmp.product_id) AS product_id,
								COUNT(tmp.product_id) AS count
								FROM (SELECT pr.product_id, (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = " . (int)$customer_group_id . " AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM product_reward AS pr JOIN product AS p ON pr.product_id = p.product_id AND p.tax_class_id = " . (int)$row['tax_class_id'] . ") as tmp
							WHERE tmp.special IS NOT NULL
						");

						if (!empty($query->row['product_id'])) {
							$this->db->query("DELETE FROM product_reward WHERE customer_group_id = " . (int)$customer_group_id . " AND product_id IN (" . $query->row['product_id'] . ")");
						}

						$total -= $query->row['count'];
					}
				}
			}

			$total += $row['count'];
		}

		return $total;

	}

	public function generateRewardPointsByProductId($product_id) {

		$this->load->model('catalog/product');

		$product = $this->model_catalog_product->getProduct($product_id);

		$data = $this->config->get('reward_points_generator');

		if (!empty($data['use_tax_class']) && !empty($product['tax_class_id'])) {

			$rates = $this->getTaxRateByTaxClass($product['tax_class_id']);

			$PRICE = "((price * " . $rates['percentage'] . ") + (" . $rates['fixed'] . "))";
		} else {
			$PRICE = "price";
		}

		$this->db->query("UPDATE product SET points = CEILING(" . $PRICE . " * " . (int)$data['unit'] . " / " . (int)$data['points'] . ") WHERE product_id = " . (int)$product_id);
	/*
		$this->db->query("UPDATE product_option_value SET points = CEILING(" . $PRICE . " * " . (int)$data['unit'] . " / " . (int)$data['points'] . ") WHERE product_id = " . (int)$product_id);
	*/
		$this->db->query("DELETE FROM product_reward WHERE product_id = " . (int)$product_id);

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				$this->db->query("INSERT INTO product_reward (product_id, customer_group_id, points) SELECT product_id , '" . (int)$customer_group_id . "', FLOOR(" . $PRICE . " * " . (int)$value['points'] . " / " . (int)$data['unit'] . ") FROM product WHERE product_id = " . (int)$product_id);

				if (!empty($data['product_reward'][$customer_group_id]['no_generate_special'])) {

					if ($this->hasActiveSpecial($product_id,$customer_group_id)) {
						$this->db->query("DELETE FROM product_reward WHERE customer_group_id = " . (int)$customer_group_id . " AND product_id = " . (int)$product_id);
					}
				}
			}
		}
	}

	private function getTaxRateByTaxClass($tax_class_id) {
		$query = $this->db->query("SELECT tax_rate_id FROM tax_rule WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		$tax_rate_ids = $query->rows;

		$rates = array(
			'percentage' => 1,
			'fixed' => 0,
		);

		foreach ($tax_rate_ids as $tax_rate_id) {
			$query = $this->db->query("SELECT rate,type FROM tax_rate WHERE tax_rate_id = '" . $tax_rate_id['tax_rate_id'] . "'");

			if ($query->row['type']=='P') {
				if ($rates['percentage']==0) {
					$rates['percentage']=$query->row['rate']/100+1;
				} else {
					$rates['percentage']*=($query->row['rate']/100+1);
				}
			} else {
				$rates['fixed']+=$query->row['rate'];
			}
		}

		return $rates;

	}

	private function hasActiveSpecial($product_id,$customer_group_id) {
		$query = $this->db->query("SELECT ps.price FROM product_special ps JOIN product AS p ON ps.product_id = p.product_id WHERE p.product_id = " . (int)$product_id . " AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1");

		return $query->num_rows;
	}
}