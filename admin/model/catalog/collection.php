<?php
class ModelCatalogCollection extends Model
{
        
    public function getAllCollections()
    {
        $query = $this->db->query("SELECT * FROM collection oc LEFT JOIN collection_description ocd ON (oc.collection_id = ocd.collection_id)  WHERE ocd.language_id = " . (int)$this->config->get('config_language_id'));
            
        return $query->rows;
    }
        
    public function getCollectionById($collection_id)
    {
            
        if ($collection_id > 0) {
            $query = $this->db->query("SELECT * FROM collection oc LEFT JOIN collection_description ocd ON (oc.collection_id = ocd.collection_id) WHERE ocd.language_id = " . (int)$this->config->get('config_language_id') . " AND oc.collection_id = " . (int)$collection_id . " LIMIT 1");
                
            return $query->row;
        } else {
            return false;
        }
    }
        
        
    public function addCollection($data)
    {
        $this->db->query("INSERT INTO collection SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', parent_id = '" . (int)$data['parent_id'] . "', no_brand = '" . (int)$data['no_brand'] . "', not_update_image = '" . (int)$data['not_update_image'] . "', `virtual` = '" . (int)$data['virtual'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "'");
            
        $collection_id = $this->db->getLastId();
        $this->session->data['new_collection_id'] = $collection_id;
            
            
        foreach ($data['collection_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO collection_description SET collection_id = '" . (int)$collection_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "', name_overload = '" . $this->db->escape($value['name_overload']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', type = '" . $this->db->escape(trim($value['type'])) . "', alternate_name = '" . $this->db->escape($value['alternate_name']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
        }
            
            
            
        if (isset($data['image'])) {
            $this->db->query("UPDATE collection SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE collection_id = '" . (int)$collection_id . "'");
        }
            
        if (isset($data['collection_image'])) {
            foreach ($data['collection_image'] as $collection_image) {
                $this->db->query("INSERT INTO collection_image SET collection_id = '" . (int)$collection_id . "', image = '" . $this->db->escape(html_entity_decode($collection_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$collection_image['sort_order'] . "'");
            }
        }
            
        if (isset($data['banner'])) {
            $this->db->query("UPDATE collection SET banner = '" . $this->db->escape(html_entity_decode($data['banner'], ENT_QUOTES, 'UTF-8')) . "' WHERE collection_id = '" . (int)$collection_id . "'");
        }
            
        if (isset($data['collection_store'])) {
            foreach ($data['collection_store'] as $store_id) {
                $this->db->query("INSERT INTO collection_to_store SET collection_id = '" . (int)$collection_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
            
        $this->db->query("DELETE FROM url_alias WHERE query = 'collection_id=" . (int)$collection_id. "'");
            
            
        if ($data['keyword']) {
            foreach ($data['keyword'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO url_alias SET query = 'collection_id=" . (int)$collection_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);
                }
            }
        }
            
        $this->load->model('kp/reward');
        $this->model_kp_reward->editReward($collection_id, 'co', $data);
            
        return (int)$collection_id;
    }
        
        
    public function editCollection($collection_id, $data)
    {
        $this->db->query("DELETE FROM collection_description WHERE collection_id = '" . (int)$collection_id . "'");
            
        foreach ($data['collection_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO collection_description SET collection_id = '" . (int)$collection_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', name_overload = '" . $this->db->escape($value['name_overload']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', type = '" . $this->db->escape(trim($value['type'])) . "', alternate_name = '" . $this->db->escape($value['alternate_name']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
        }
            
            
        $this->db->query("UPDATE collection SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "', parent_id = '" . (int)$data['parent_id'] . "', not_update_image = '" . (int)$data['not_update_image'] . "', `virtual` = '" . (int)$data['virtual'] . "', no_brand = '" . (int)$data['no_brand'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "' WHERE collection_id = '" . (int)$collection_id . "'");
            
        if (isset($data['image'])) {
            $this->db->query("UPDATE collection SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE collection_id = '" . (int)$collection_id . "'");
        }
            
            
        $this->db->query("DELETE FROM collection_image WHERE collection_id = '" . (int)$collection_id . "'");
        if (isset($data['collection_image'])) {
            foreach ($data['collection_image'] as $collection_image) {
                $this->db->query("INSERT INTO collection_image SET collection_id = '" . (int)$collection_id . "', image = '" . $this->db->escape(html_entity_decode($collection_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$collection_image['sort_order'] . "'");
            }
        }
            
        if (isset($data['banner'])) {
            $this->db->query("UPDATE collection SET banner = '" . $this->db->escape(html_entity_decode($data['banner'], ENT_QUOTES, 'UTF-8')) . "' WHERE collection_id = '" . (int)$collection_id . "'");
        }
            
        $this->db->query("DELETE FROM collection_to_store WHERE collection_id = '" . (int)$collection_id . "'");
            
        if (isset($data['collection_store'])) {
            foreach ($data['collection_store'] as $store_id) {
                $this->db->query("INSERT INTO collection_to_store SET collection_id = '" . (int)$collection_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
            
        $this->db->query("DELETE FROM url_alias WHERE query = 'collection_id=" . (int)$collection_id. "'");
            
        if ($data['keyword']) {
            foreach ($data['keyword'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO url_alias SET query = 'collection_id=" . (int)$collection_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);
                }
            }
        }
            
            
        $this->load->model('kp/reward');
        $this->model_kp_reward->editReward($collection_id, 'co', $data);

        return (int)$collection_id;
    }
        
    public function getCollectionImages($collection_id)
    {
        $query = $this->db->query("SELECT * FROM collection_image WHERE collection_id = '" . (int)$collection_id . "'");
            
        return $query->rows;
    }
        
    public function deleteCollection($collection_id)
    {
        $this->db->query("DELETE FROM collection WHERE collection_id = '" . (int)$collection_id . "'");
        $this->db->query("DELETE FROM collection_to_store WHERE collection_id = '" . (int)$collection_id . "'");
        $this->db->query("DELETE FROM url_alias WHERE query = 'collection_id=" . (int)$collection_id . "'");
        $this->db->query("DELETE FROM collection_description WHERE collection_id = '" . (int)$collection_id . "'");
        $this->db->query("UPDATE product SET collection_id = 0 WHERE collection_id = '" . (int)$collection_id . "'");
            
        $this->cache->delete('collection');
    }
        
        
    public function getCollections($data = array())
    {
        $sql = "SELECT *, c.name as name, c.image as image, m.name as mname, cd.alternate_name FROM collection c
			LEFT JOIN manufacturer m ON c.manufacturer_id = m.manufacturer_id
			LEFT JOIN collection_description cd ON cd.collection_id = c.collection_id
			WHERE cd.language_id = '" . $this->config->get('config_language_id') . "'";
        
            
        if (!empty($data['filter_name'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
            
        if (!empty($data['manufacturer_id'])) {
            $sql .= 'AND m.manufacturer_id = '.(int)$data['manufacturer_id'];
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
        
    public function getKeyWords($collection_id)
    {
        $keywords = array();
            
        $query = $this->db->query("SELECT * FROM url_alias WHERE query = 'collection_id=" . (int)$collection_id . "'");
            
        foreach ($query->rows as $result) {
            $keywords[$result['language_id']] = $result['keyword'];
        }
            
        return $keywords;
    }
        
    public function getCollectionDescriptions($collection_id)
    {
        $collection_description_data = array();
            
        $query = $this->db->query("SELECT * FROM collection_description WHERE collection_id = '" . (int)$collection_id . "'");
            
        foreach ($query->rows as $result) {
            $collection_description_data[$result['language_id']] = array(
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
            
        return $collection_description_data;
    }
        
        
    public function getCollectionStores($collection_id)
    {
        $collection_store_data = array();
            
        $query = $this->db->query("SELECT * FROM collection_to_store WHERE collection_id = '" . (int)$collection_id . "'");
            
        foreach ($query->rows as $result) {
            $collection_store_data[] = $result['store_id'];
        }
            
        return $collection_store_data;
    }
        
    public function getCollection($collection_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, 
			(SELECT name FROM collection c2 WHERE c2.collection_id = c.parent_id LIMIT 1) as parent, 
			(SELECT name FROM manufacturer m WHERE m.manufacturer_id = c.manufacturer_id LIMIT 1) as manufacturer,
			(SELECT name FROM manufacturer m2 WHERE m2.manufacturer_id = (SELECT c3.manufacturer_id FROM collection c3 WHERE c3.collection_id = c.parent_id LIMIT 1) LIMIT 1) as parent_mname 
			FROM collection c WHERE collection_id = '" . (int)$collection_id . "'");
            
        return $query->row;
    }
        
    public function getTotalCollections($data = false)
    {
        $sql = "SELECT COUNT(c.`manufacturer_id`) as total FROM collection c LEFT JOIN manufacturer m ON c.manufacturer_id = m.manufacturer_id";
            
        $sql .= ' WHERE 1=1 ';
            
        if (!empty($data['filter_name'])) {
            $sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
            
        if (!empty($data['manufacturer_id'])) {
            $sql .= 'AND m.manufacturer_id = '.(int)$data['manufacturer_id'];
        }
            
        // $query = $this->db->query("SELECT COUNT(*) AS total FROM collection");
        $query = $this->db->query($sql);
            
        return $query->row['total'];
    }
}
