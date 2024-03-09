<?	
	namespace hobotix;
	
	final class CouponRandom{


		private $db		 = null;	
		private $config	 = null;			
		
		public function __construct($registry){			
			$this->config 	= $registry->get('config');
			$this->db 		= $registry->get('db');		
		}

		public function checkIfCouponIsRandom($coupon){
			$query = $this->db->non_cached_query("SELECT * FROM coupon WHERE code = '" . $this->db->escape($coupon) . "'");

			if (!$query->num_rows){
				return false;
			}

			return $query->row['random'];
		}

		public function getCouponRandomStruct($coupon){
			$query = $this->db->non_cached_query("SELECT * FROM coupon WHERE code = '" . $this->db->escape($coupon) . "'");

			if (!$query->num_rows){
				return false;
			}

			if (!$query->row['random']){
				return false;
			}

			return explode($query->row['random_string'], $coupon);
		}

		public function getCouponRandomCount($coupon){
			$query = $this->db->query("SELECT COUNT(*) as total FROM coupon_random WHERE coupon_code = '" . $this->db->escape($coupon) . "'");
			
			return $query->row['total'];
		}

		public function getCouponRandom($coupon){
			$query = $this->db->non_cached_query("SELECT * FROM coupon WHERE code = '" . $this->db->escape($coupon) . "'");

			if (!$query->num_rows){
				return false;
			}

			if (!$query->row['random'] || !$query->row['random_string']){
				return $coupon;
			}

			return $this->generateCouponRandom($query->row);
		}

		public function getCouponRandomUsage($coupon){
			$query = $this->db->query("SELECT COUNT(DISTINCT ot.order_id) as total FROM order_total ot LEFT JOIN `order` o ON (o.order_id = ot.order_id) WHERE o.order_status_id > 0 AND ot.code = 'coupon' AND ot.title LIKE ('%" . $this->db->escape($coupon) . "%')");

			return $query->row['total'];
		}

		public function getCouponRandomParent($coupon, $field = 'coupon_code'){
			$query = $this->db->non_cached_query("SELECT * FROM coupon_random WHERE coupon_random = '" . $this->db->escape($coupon) . "' LIMIT 1");

			if ($query->num_rows){
				return $query->row[$field];
			}

			return false;
		}		

		public function generateCouponRandom($coupon){
			$random 		= generateRandomUppercaseString(4);
			$coupon_random 	= str_replace($coupon['random_string'], $random, $coupon['code']);

			while ($this->getCouponRandomParent($coupon_random)){
				$random 		= generateRandomUppercaseString(4);
				$coupon_random 	= str_replace($coupon['random_string'], $random, $coupon['code']);
			}			

			$this->db->non_cached_query("INSERT INTO coupon_random SET 
				coupon_id 		= '" . (int)$coupon['coupon_id'] . "', 
				coupon_code 	= '" . $this->db->escape($coupon['code']) . "', 
				coupon_random 	= '" . $this->db->escape($coupon_random) . "'");

			return $coupon_random;
		}
	}
		