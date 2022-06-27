<?php
class ModelCatalogCountrybrand extends Model
{
    
    
    public function getAllCountriesFromManufacturer($language_id)
    {
        $query = $this->db->query("SELECT DISTINCT(location) FROM manufacturer_description WHERE LENGTH(location) > 0 AND language_id = '" . (int)$language_id . "'");
            
        $result = [];
        foreach ($query->rows as $row) {
            $result[] = $row['location'];
        }

        return $result;
    }
        
    public function getTotalManufacturersByCountryBrand($countrybrand_id)
    {
        
        $descriptions = $this->getCountrybrandDescriptions($countrybrand_id);
        
        $query = $this->db->query("SELECT COUNT(DISTINCT manufacturer_id) as total FROM manufacturer_description WHERE LENGTH(location) > 0 AND language_id = '" . (int)$this->config->get('config_language_id') . "' AND TRIM(location) = '" . $this->db->escape(trim($descriptions[$this->config->get('config_language_id')]['type'])) . "'");
            
        return $query->row['total'];
    }
        
    public function getAllCountrybrands()
    {
        $query = $this->db->query("SELECT * FROM countrybrand oc LEFT JOIN countrybrand_description ocd ON (oc.countrybrand_id = ocd.countrybrand_id)  WHERE ocd.language_id = " . (int)$this->config->get('config_language_id'));
            
        return $query->rows;
    }
        
    public function getCountrybrandById($countrybrand_id)
    {
            
        if ($countrybrand_id > 0) {
            $query = $this->db->query("SELECT * FROM countrybrand oc LEFT JOIN countrybrand_description ocd ON (oc.countrybrand_id = ocd.countrybrand_id) WHERE ocd.language_id = " . (int)$this->config->get('config_language_id') . " AND oc.countrybrand_id = " . (int)$countrybrand_id . " LIMIT 1");
                
            return $query->row;
        } else {
            return false;
        }
    }
        
        
    public function addCountrybrand($data)
    {
        $this->db->query("INSERT INTO countrybrand SET name = '" . $this->db->escape($data['name']) . "', flag = '" . $this->db->escape($data['flag']) . "', sort_order = '" . (int)$data['sort_order'] . "', template = '" . $this->db->escape($data['template']) . "'");
            
        $countrybrand_id = $this->db->getLastId();
        $this->session->data['new_countrybrand_id'] = $countrybrand_id;
            
            
        foreach ($data['countrybrand_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO countrybrand_description SET countrybrand_id = '" . (int)$countrybrand_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "', name_overload = '" . $this->db->escape($value['name_overload']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', type = '" . $this->db->escape(trim($value['type'])) . "', alternate_name = '" . $this->db->escape($value['alternate_name']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
        }
            
            
            
        if (isset($data['image'])) {
            $this->db->query("UPDATE countrybrand SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        }
            
        if (isset($data['countrybrand_image'])) {
            foreach ($data['countrybrand_image'] as $countrybrand_image) {
                $this->db->query("INSERT INTO countrybrand_image SET countrybrand_id = '" . (int)$countrybrand_id . "', image = '" . $this->db->escape(html_entity_decode($countrybrand_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$countrybrand_image['sort_order'] . "'");
            }
        }
            
        if (isset($data['banner'])) {
            $this->db->query("UPDATE countrybrand SET banner = '" . $this->db->escape(html_entity_decode($data['banner'], ENT_QUOTES, 'UTF-8')) . "' WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        }
            
        if (isset($data['countrybrand_store'])) {
            foreach ($data['countrybrand_store'] as $store_id) {
                $this->db->query("INSERT INTO countrybrand_to_store SET countrybrand_id = '" . (int)$countrybrand_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
            
        $this->db->query("DELETE FROM url_alias WHERE query = 'countrybrand_id=" . (int)$countrybrand_id. "'");
            
            
        if ($data['keyword']) {
            foreach ($data['keyword'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO url_alias SET query = 'countrybrand_id=" . (int)$countrybrand_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);
                }
            }
        }
            
        $this->load->model('kp/reward');
        $this->model_kp_reward->editReward($countrybrand_id, 'co', $data);
            
        return $countrybrand_id;
    }
        
        
    public function editCountrybrand($countrybrand_id, $data)
    {
                        
        $this->db->query("UPDATE countrybrand SET name = '" . $this->db->escape($data['name']) . "', flag = '" . $this->db->escape($data['flag']) . "', sort_order = '" . (int)$data['sort_order'] . "', template = '" . $this->db->escape($data['template']) . "' WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        
        
        $this->db->query("DELETE FROM countrybrand_description WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
            
        foreach ($data['countrybrand_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO countrybrand_description SET countrybrand_id = '" . (int)$countrybrand_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', name_overload = '" . $this->db->escape($value['name_overload']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', type = '" . $this->db->escape(trim($value['type'])) . "', alternate_name = '" . $this->db->escape($value['alternate_name']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
        }
            
            
        if (isset($data['image'])) {
            $this->db->query("UPDATE countrybrand SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        }
            
            
        $this->db->query("DELETE FROM countrybrand_image WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        if (isset($data['countrybrand_image'])) {
            foreach ($data['countrybrand_image'] as $countrybrand_image) {
                $this->db->query("INSERT INTO countrybrand_image SET countrybrand_id = '" . (int)$countrybrand_id . "', image = '" . $this->db->escape(html_entity_decode($countrybrand_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$countrybrand_image['sort_order'] . "'");
            }
        }
            
        if (isset($data['banner'])) {
            $this->db->query("UPDATE countrybrand SET banner = '" . $this->db->escape(html_entity_decode($data['banner'], ENT_QUOTES, 'UTF-8')) . "' WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        }
            
        $this->db->query("DELETE FROM countrybrand_to_store WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
            
        if (isset($data['countrybrand_store'])) {
            foreach ($data['countrybrand_store'] as $store_id) {
                $this->db->query("INSERT INTO countrybrand_to_store SET countrybrand_id = '" . (int)$countrybrand_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
            
        $this->db->query("DELETE FROM url_alias WHERE query = 'countrybrand_id=" . (int)$countrybrand_id. "'");
            
        if ($data['keyword']) {
            foreach ($data['keyword'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO url_alias SET query = 'countrybrand_id=" . (int)$countrybrand_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);
                }
            }
        }
            
            
        $this->load->model('kp/reward');
        $this->model_kp_reward->editReward($countrybrand_id, 'co', $data);
    }
        
    public function getCountrybrandImages($countrybrand_id)
    {
        $query = $this->db->query("SELECT * FROM countrybrand_image WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
            
        return $query->rows;
    }
        
    public function deleteCountrybrand($countrybrand_id)
    {
        $this->db->query("DELETE FROM countrybrand WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        $this->db->query("DELETE FROM countrybrand_to_store WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
        $this->db->query("DELETE FROM url_alias WHERE query = 'countrybrand_id=" . (int)$countrybrand_id . "'");
        $this->db->query("DELETE FROM countrybrand_description WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
            
        $this->cache->delete('countrybrand');
    }
        
        
    public function getCountrybrands($data = array())
    {
        $sql = "SELECT *, c.name as name, c.image as image, cd.alternate_name FROM countrybrand c			
			LEFT JOIN countrybrand_description cd ON cd.countrybrand_id = c.countrybrand_id
			WHERE cd.language_id = '" . $this->config->get('config_language_id') . "'";
        
            
        if (!empty($data['filter_name'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
            
        $sort_data = array(
        'c.name',
        'sort_order'
        );
            
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY c.name";
        }
            
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
            
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
                
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
                
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
            
            $query = $this->db->query($sql);
            
            return $query->rows;
    }
        
    public function getKeyWords($countrybrand_id)
    {
        $keywords = array();
            
        $query = $this->db->query("SELECT * FROM url_alias WHERE query = 'countrybrand_id=" . (int)$countrybrand_id . "'");
            
        foreach ($query->rows as $result) {
            $keywords[$result['language_id']] = $result['keyword'];
        }
            
        return $keywords;
    }
        
    public function getCountrybrandDescriptions($countrybrand_id)
    {
        $countrybrand_description_data = array();
            
        $query = $this->db->query("SELECT * FROM countrybrand_description WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
            
        foreach ($query->rows as $result) {
            $countrybrand_description_data[$result['language_id']] = array(
            'meta_keyword'          => $result['meta_keyword'],
            'alternate_name'        => $result['alternate_name'],
            'meta_description'      => $result['meta_description'],
            'name_overload'         => $result['name_overload'],
            'description'           => $result['description'],
            'type'                  => $result['type'],
            'short_description'     => $result['short_description'],
            'seo_title'             => $result['seo_title'],
            'seo_h1'                => $result['seo_h1']
            );
        }
            
        return $countrybrand_description_data;
    }
        
        
    public function getCountrybrandStores($countrybrand_id)
    {
        $countrybrand_store_data = array();
            
        $query = $this->db->query("SELECT * FROM countrybrand_to_store WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
            
        foreach ($query->rows as $result) {
            $countrybrand_store_data[] = $result['store_id'];
        }
            
        return $countrybrand_store_data;
    }
        
    public function getCountrybrand($countrybrand_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM countrybrand c WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
            
        return $query->row;
    }
        
    public function getTotalCountrybrands($data = false)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM countrybrand");

            
        return $query->row['total'];
    }
}
