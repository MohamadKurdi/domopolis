<?php
class ModelCatalogCategoryWall extends Model
{

    public function getWallCategories($parent_id = 0)
    {
        $category_data = array();

        $query = $this->db->query("SELECT * FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

        foreach ($query->rows as $result) {
            $category_data[] = array(
                'category_id' => $result['category_id'],
                'name'        => $this->getPath($result['category_id'], $this->config->get('config_language_id')),
                'status'      => $result['status'],
                'sort_order'  => $result['sort_order']
                );

            $category_data = array_merge($category_data, $this->getWallCategories($result['category_id']));
        }

        return $category_data;
    }

    public function getPath($category_id)
    {
        $query = $this->db->query("SELECT name, parent_id FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

        if ($query->row['parent_id']) {
            return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $query->row['name'];
        } else {
            return $query->row['name'];
        }
    }
}
