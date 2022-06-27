<?php
class ModelCatalogCategoryreview extends Model
{

    public function addCategoryreview($data)
    {
        $this->db->query("INSERT INTO category_review SET author = '" . $this->db->escape($data['author']) . "', category_id = '" . $this->db->escape($data['category_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "'");
    }

    public function editCategoryreview($categoryreview_id, $data)
    {
        $this->db->query("UPDATE category_review SET author = '" . $this->db->escape($data['author']) . "', category_id = '" . $this->db->escape($data['category_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "' WHERE categoryreview_id = '" . (int)$categoryreview_id . "'");
    }

    public function deleteCategoryreview($categoryreview_id)
    {
        $this->db->query("DELETE FROM category_review WHERE categoryreview_id = '" . (int)$categoryreview_id . "'");
    }

    public function getCategoryreview($categoryreview_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT cd.name FROM category_description cd WHERE cd.category_id = cr.category_id AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS category FROM category_review cr WHERE cr.categoryreview_id = '" . (int)$categoryreview_id . "'");

        return $query->row;
    }

    public function getCategoryreviews($data = array())
    {
        $sql = "SELECT cr.categoryreview_id, cd.name, cr.author, cr.rating, cr.status, cr.date_added FROM category_review cr LEFT JOIN category_description cd ON (cr.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'cd.name',
            'cr.author',
            'cr.rating',
            'cr.status',
            'cr.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY cr.date_added";
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

    public function getTotalCategoryreviews()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM category_review");

        return $query->row['total'];
    }

    public function getTotalCategoryreviewsAwaitingApproval()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM category_review WHERE status = '0'");

        return $query->row['total'];
    }
}
