<?php

class ModelKpGeoIP extends Model
{
    private $geoReader;

    public function __construct($registry)
    {
        parent::__construct($registry);

        try {
            if (defined('GEOIP_LIB_PATH')) {
                $this->geoReader = new GeoIp2\Database\Reader(GEOIP_LIB_PATH);
            } else {
                $this->geoReader = false;
            }
        } catch (InvalidArgumentException $e) {
            $this->geoReader = false;
        }
    }

    public function getCityByIpAddr($ip)
    {
        $ip = trim($ip);
        if (!$ip) {
            return false;
        }

        if (!$this->geoReader) {
            return false;
        }

        try {
            $record = $this->geoReader->city($ip);

            if ($record) {

                $city = false;
                if (!empty($record->city->names)) {
                    if (!empty($record->city->names['ru'])) {
                        $city = $record->city->names['ru'];
                    } elseif (!empty($record->city->names['en'])) {
                        $city = $record->city->names['en'];
                    }
                }

                $country = false;
                if (!empty($record->country->names)) {
                    if (!empty($record->country->names['ru'])) {
                        $country = $record->country->names['ru'];
                    } elseif (!empty($record->country->names['en'])) {
                        $country = $record->country->names['en'];
                    }
                }

                $data = [
                    'country_code' => $record->country->isoCode,
                    'country_name' => $country,
                    'continent_code' => $record->continent->name,
                    'city' => $city,
                ];
            }


        } catch (Exception $e) {
            $data = false;
        }

        return $data;

    }

    public function getInfoByIpAddr($ip)
    {
        return false;
    }

    public function getCurrentTimeInCityLong($city, $country = false)
    {
        $city = trim($city);

        $sql = "SELECT gg.timezone FROM geonames gg 
			WHERE 
			name LIKE '" . $this->db->escape($city) . "'";

        if ($country) {
            $sql .= " AND UPPER(gg.country) = '" . $this->db->escape(mb_strtoupper($country)) . "'";
        }

        $sql .= "LIMIT 1";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return $this->getCurrentTimeInTimezone($query->row['timezone'], false);
        }

        return false;
    }

    public function guessCity($city)
    {
        $city = trim(mb_strtolower($city));

        if (count($exploded = explode(',', $city)) > 1) {
            $city = $exploded[count($exploded) - 1];
        }

        $city = str_replace('г.', '', $city);
        $city = str_replace('г ', '', $city);
        $city = trim($city);

        return $city;
    }

    public function getCurrentTimeInTimezone($timezone, $full)
    {
        $time = false;

        try {
            $date = new DateTime("now", new DateTimeZone($timezone));
        } catch (Exception $e) {
            $time = false;
        }
        if ($full) {
            $time = $date->format('Y-m-d H:i:s');
        } else {
            $time = $date->format('H:i');
        }

        return $time;
    }

    public function getCurrentTimeInCity($city, $country = false, $full = false)
    {
        return $this->getCurrentTimeInCityLong($city, $country);
    }

    public function canCallNow($current_time)
    {
        $hour = explode(':', $current_time);
        $hour = (int)$hour[0];

        if ($hour > 20 || $hour < 9) {
            return false;
        } else {
            return true;
        }
    }
}