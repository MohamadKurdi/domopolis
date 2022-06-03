<?php

    class ModelModuleGeoip extends Model {

        public function getCities() {

            $query = $this->db->query("SELECT * FROM geoip_city");

            return $query->rows;
        }

        public function getRules() {

            $query = $this->db->query("SELECT * FROM geoip_rule");

            return $query->rows;
        }

        public function getRedirects() {

            $query = $this->db->query("SELECT * FROM geoip_redirect");

            return $query->rows;
        }

        public function getCurrencies() {

            $query = $this->db->query("SELECT * FROM geoip_currency");

            return $query->rows;
        }

        public function editCities($cities) {

            foreach ($cities as $city) {

                $this->db->query("INSERT INTO geoip_city (`fias_id`, `name`) VALUES ("
                        . (int)$city['fias_id'] . ", '" . $this->db->escape($city['name']) . "')"
                        . " ON DUPLICATE KEY UPDATE name = name");
            }
        }

        public function editRules($rules) {

            foreach ($rules as $rule) {

                $this->db->query("INSERT INTO geoip_rule (`fias_id`, `key`, `value`) VALUES ("
                        . (int)$rule['fias_id'] . ", '" . $this->db->escape($rule['key']) . "', '"
                        . $this->db->escape($rule['value']) . "')");
            }
        }

        public function editRedirects($redirects) {

            foreach ($redirects as $redirect) {

                $this->db->query("INSERT INTO geoip_redirect (`fias_id`, `url`) VALUES ("
                        . (int)$redirect['fias_id'] . ", '" . $this->db->escape($redirect['url']) . "')");
            }
        }

        public function editCurrencies($currencies) {

            foreach ($currencies as $currency) {

                $this->db->query("INSERT INTO geoip_currency (`country_id`, `code`) VALUES ("
                        . (int)$currency['country_id'] . ", '" . $this->db->escape($currency['code']) . "')");
            }
        }

        public function deleteCities() {

            $this->db->query("TRUNCATE geoip_city");
        }

        public function deleteRules() {

            $this->db->query("TRUNCATE geoip_rule");
        }

        public function deleteRedirects() {

            $this->db->query("TRUNCATE geoip_redirect");
        }

        public function deleteCurrencies() {

            $this->db->query("TRUNCATE geoip_currency");
        }

        public function getFiasName($fiasId) {

            $row = $this->db->query("SELECT CONCAT(shortname, ' ', offname) name FROM fias WHERE fias_id = " . (int)$fiasId)->row;

            return $row ? $row['name'] : null;
        }

        public function findFiasByName($term) {

            $parts = explode(' ', $term, 2);
            $where = '';

            if (isset($parts[1])) {
                $where .= "(f1.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[0])) . "%'
                        AND (f2.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[1])) . "%' OR f3.offname LIKE '%" . $this->db->escape(utf8_strtolower($parts[1])) . "%')) OR ";
            }

            $where .= "(f1.offname LIKE '%" . $this->db->escape(utf8_strtolower($term)) . "%')";

            $query = $this->db->query("SELECT CONCAT_WS(', ',
                                                    f1.offname,
                                                    CONCAT(f2.offname, ' ', f2.shortname),
                                                    CONCAT(f3.offname, ' ', f3.shortname)) label,
                                            f1.offname value,
                                            f1.fias_id
                                        FROM fias f1
                                            LEFT JOIN fias f2 ON f2.fias_id = f1.parent_id
                                            LEFT JOIN fias f3 ON f3.fias_id = f2.parent_id
                                        WHERE (" . $where . ")
                                            AND f1.level IN (0, 1, 4)
                                        ORDER BY f1.level, f2.level, f3.level
                                        LIMIT 10");																		

            return $query->rows;
        }
    }