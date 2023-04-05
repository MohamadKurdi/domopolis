<?
	use GeoIp2\Database\Reader;
	class ModelKpGeoIP extends Model {
		private $geoReader;
		
		public function __construct($registry){
			parent::__construct($registry);

			try{
				if (defined('GEOIP_LIB_PATH')){
					$this->geoReader = new GeoIp2\Database\Reader(GEOIP_LIB_PATH);
				} else {
					$this->geoReader = false;
				}				
			} catch (InvalidArgumentException $e){
				$this->geoReader = false;
			}
		}
		
		public function getCityByIpAddr($ip){
			$ip = trim($ip);
			if (!$ip){
				return false;
			}

			if (!$this->geoReader){
				return false;
			}

			try{
				$record = $this->geoReader->city($ip);	
				
				
				$data = array(
				'country_code' 		=> $record->country->isoCode,
				'country_name' 		=> $record->country->names['ru'],
				'continent_code' 	=> $record->continent->name,
				'city' 				=> $record->city->names['ru'],
				);
				
				} catch (Exception $e) {	
				$data = false;
			}
			
			return $data;
			
		}
		
		
		public function getInfoByIpAddr($ip){
			return false;
			//	return geoip_record_by_name($ip);						
			
		}
		
		public function getCurrentTimeInCityLong($city, $country = false, $full = false){
			$city = trim($city);
			
			$sql = "SELECT gg.timezone FROM geoname_alternatename ga 
			LEFT JOIN geoname_geoname gg 
			ON ga.geonameid = gg.geonameid
			WHERE 
			LOWER(ga.alternateName) LIKE '" . $this->db->escape(mb_strtolower($city)) . "'";
			
			if ($country){
				$sql .= " AND gg.country = '" . $this->db->escape($country) . "'";
			}
			
			$sql .=	"LIMIT 1";
			
			$query = $this->db->query($sql);
			
			$timezone = false;
			if ($query->num_rows && isset($query->row['timezone']) && $query->row['timezone']){				
				$timezone = $query->row['timezone'];
			}
			
			$this->db->query("INSERT IGNORE INTO direct_timezones SET geomd5 = '" . $this->db->escape(md5($city . $country)) . "', timezone = '" . $this->db->escape($timezone) . "'");
			
			if (!$timezone){
				return false;
			}
			
			return $this->getCurrentTimeInTimezone($timezone, $full);
		}
		
		public function guessCity($city){
			$city = trim(mb_strtolower($city));
			
			if (count($exploded = explode(',', $city)) > 1){
				$city = $exploded[count($exploded) - 1];
			}
			
			$city = str_replace('г.', '', $city);
			$city = str_replace('г ', '', $city);
			$city = trim($city);
			
			return $city;
			
		}
		
		public function getCurrentTimeInTimezone($timezone, $full){				
			$time = false;
			
			try {					
				$date = new DateTime("now", new DateTimeZone($timezone));			
				} catch (Exception $e) {
				$time = false;
			}
			if ($full){
				$time = $date->format('Y-m-d H:i:s');
				} else {
				$time = $date->format('H:i');
			}
			
			return $time;
		}
		
		public function getCurrentTimeInCity($city, $country = false, $full = false){
			$city = trim($city);
			
			$sql = "SELECT timezone FROM direct_timezones WHERE geomd5 = '" . $this->db->escape(md5($city . $country)) . "' LIMIT 1";
			
			$time = false;
			$query = $this->db->query($sql);
			
			if ($query->num_rows){
				if (!empty($query->row['timezone'])){
					return $this->getCurrentTimeInTimezone($query->row['timezone'], $full);
					} else {
					return false;
				}
			}
			
			return $this->getCurrentTimeInCityLong($city, $country, $full);
			
		}
		
		public function canCallNow($current_time){
			$hour = explode(':',$current_time);
			$hour = (int)$hour[0];
			
			if ($hour > 20 || $hour < 9){
				return false;
				} else {
				return true;
				}
			
		}
		
	}							