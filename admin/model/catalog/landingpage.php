<?php
class ModelCataloglandingpage extends Model
{
    public function addlandingpage($data)
    {
        $this->db->query("INSERT INTO landingpage SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "', image = '" . $this->db->escape($data['image']) . "'");
            
        $landingpage_id = $this->db->getLastId();
            
        foreach ($data['landingpage_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO landingpage_description SET landingpage_id = '" . (int)$landingpage_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "',  description = '" . $this->db->escape($value['description']) . "'");
        }
            
        if (isset($data['landingpage_store'])) {
            foreach ($data['landingpage_store'] as $store_id) {
                $this->db->query("INSERT INTO landingpage_to_store SET landingpage_id = '" . (int)$landingpage_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
            
        if (isset($data['landingpage_layout'])) {
            foreach ($data['landingpage_layout'] as $store_id => $layout) {
                if ($layout) {
                    $this->db->query("INSERT INTO landingpage_to_layout SET landingpage_id = '" . (int)$landingpage_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
                }
            }
        }
            
        if ($data['keyword']) {
            foreach ($data['keyword'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO url_alias SET query = 'landingpage_id=" . (int)$landingpage_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);
                }
            }
        }
            
        $this->cache->delete('landingpage');
    }
        
    public function editlandingpage($landingpage_id, $data)
    {
        $this->db->query("UPDATE landingpage SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "', image = '" . $this->db->escape($data['image']) . "'");
            
        $this->db->query("DELETE FROM landingpage_description WHERE landingpage_id = '" . (int)$landingpage_id . "'");
            
        foreach ($data['landingpage_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO landingpage_description SET landingpage_id = '" . (int)$landingpage_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "',  description = '" . $this->db->escape($value['description']) . "'");
        }
            
        $this->db->query("DELETE FROM landingpage_to_store WHERE landingpage_id = '" . (int)$landingpage_id . "'");
            
        if (isset($data['landingpage_store'])) {
            foreach ($data['landingpage_store'] as $store_id) {
                $this->db->query("INSERT INTO landingpage_to_store SET landingpage_id = '" . (int)$landingpage_id . "', store_id = '" . (int)$store_id . "'");
            }
        }
            
        $this->db->query("DELETE FROM landingpage_to_layout WHERE landingpage_id = '" . (int)$landingpage_id . "'");
            
        if (isset($data['landingpage_layout'])) {
            foreach ($data['landingpage_layout'] as $store_id => $layout) {
                if ($layout['layout_id']) {
                    $this->db->query("INSERT INTO landingpage_to_layout SET landingpage_id = '" . (int)$landingpage_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
                }
            }
        }
            
        $this->db->query("DELETE FROM url_alias WHERE query = 'landingpage_id=" . (int)$landingpage_id. "'");
            
        if ($data['keyword']) {
            foreach ($data['keyword'] as $language_id => $keyword) {
                if ($keyword) {
                    $this->db->query("INSERT INTO url_alias SET query = 'landingpage_id=" . (int)$landingpage_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);
                }
            }
        }
            
        $this->cache->delete('landingpage');
    }
        
    public function deletelandingpage($landingpage_id)
    {
        $this->db->query("DELETE FROM landingpage WHERE landingpage_id = '" . (int)$landingpage_id . "'");
        $this->db->query("DELETE FROM landingpage_description WHERE landingpage_id = '" . (int)$landingpage_id . "'");
        $this->db->query("DELETE FROM landingpage_to_store WHERE landingpage_id = '" . (int)$landingpage_id . "'");
        $this->db->query("DELETE FROM landingpage_to_layout WHERE landingpage_id = '" . (int)$landingpage_id . "'");
        $this->db->query("DELETE FROM url_alias WHERE query = 'landingpage_id=" . (int)$landingpage_id . "'");
            
        $this->cache->delete('landingpage');
    }
        
    public function getlandingpage($landingpage_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM landingpage WHERE landingpage_id = '" . (int)$landingpage_id . "'");
            
        return $query->row;
    }
        
    public function getKeyWords($landingpage_id)
    {
        $keywords = array();
            
        $query = $this->db->query("SELECT * FROM url_alias WHERE query = 'landingpage_id=" . (int)$landingpage_id . "'");
            
        foreach ($query->rows as $result) {
            $keywords[$result['language_id']] = $result['keyword'];
        }
            
        return $keywords;
    }
        
    public function getlandingpages($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM landingpage i LEFT JOIN landingpage_description id ON (i.landingpage_id = id.landingpage_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
                
            $sort_data = array(
            'id.title',
            'i.sort_order'
            );
                
            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY id.title";
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
        } else {
            $landingpage_data = $this->cache->get('landingpage.' . (int)$this->config->get('config_language_id'));
                
            if (!$landingpage_data) {
                    $query = $this->db->query("SELECT * FROM landingpage i LEFT JOIN landingpage_description id ON (i.landingpage_id = id.landingpage_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");
                    
                    $landingpage_data = $query->rows;
                    
                    $this->cache->set('landingpage.' . (int)$this->config->get('config_language_id'), $landingpage_data);
            }
                
                    return $landingpage_data;
        }
    }
        
    public function getlandingpageDescriptions($landingpage_id)
    {
        $landingpage_description_data = array();
            
        $query = $this->db->query("SELECT * FROM landingpage_description WHERE landingpage_id = '" . (int)$landingpage_id . "'");
            
        foreach ($query->rows as $result) {
            $landingpage_description_data[$result['language_id']] = array(
            'seo_title'         => $result['seo_title'],
            'title'             => $result['title'],
            'meta_description'  => $result['meta_description'],
            'meta_keyword'      => $result['meta_keyword'],
            'description'       => $result['description']
            );
        }
            
        return $landingpage_description_data;
    }
        
    public function getlandingpageStores($landingpage_id)
    {
        $landingpage_store_data = array();
            
        $query = $this->db->query("SELECT * FROM landingpage_to_store WHERE landingpage_id = '" . (int)$landingpage_id . "'");
            
        foreach ($query->rows as $result) {
            $landingpage_store_data[] = $result['store_id'];
        }
            
        return $landingpage_store_data;
    }
        
    public function getlandingpageLayouts($landingpage_id)
    {
        $landingpage_layout_data = array();
            
        $query = $this->db->query("SELECT * FROM landingpage_to_layout WHERE landingpage_id = '" . (int)$landingpage_id . "'");
            
        foreach ($query->rows as $result) {
            $landingpage_layout_data[$result['store_id']] = $result['layout_id'];
        }
            
        return $landingpage_layout_data;
    }
        
    public function getTotallandingpages()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM landingpage");
            
        return $query->row['total'];
    }
        
    public function getTotallandingpagesByLayoutId($layout_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM landingpage_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
            
        return $query->row['total'];
    }
}
