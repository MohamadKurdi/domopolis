<?php
class ModelCatalogFAproduct extends Model
{

    public function addFACategory($data)
    {
        $this->db->query("INSERT INTO facategory SET `name` = '" . $data['name'] . "'");
    }

    public function editFACategory($facategory_id, $data)
    {
        $this->db->query("UPDATE facategory SET `name` = '" . $data['name'] . "' WHERE `facategory_id` = ".$facategory_id);
    }

    public function deleteFACategory($facategory_id)
    {
        $this->db->query("DELETE FROM facategory WHERE facategory_id = '" . (int)$facategory_id . "'");
        $this->db->query("DELETE FROM faproduct_to_facategory WHERE facategory_id = '" . (int)$facategory_id . "'");
        $this->db->query("DELETE FROM facategory_to_faproduct WHERE facategory_id = '" . (int)$facategory_id . "'");
    }

    public function getFACategories()
    {
            $category_data = array();
        
            $query = $this->db->query("SELECT * FROM facategory");
        
        foreach ($query->rows as $result) {
            $category_data[] = array(
                'facategory_id' => $result['facategory_id'],
                'name'        => $result['name']
            );
        }
        
        return $category_data;
    }

    public function getFACategory($facategory_id)
    {
        $query = $this->db->query("SELECT * FROM facategory WHERE facategory_id = '" . (int)$facategory_id . "'");
        
        return $query->row;
    }


    public function getFAproductCats($id)
    {
        if (isset($id) && $id) {
            $query = $this->db->query("SELECT `facategory_id` FROM `faproduct_to_facategory` WHERE `product_id` = ".$id);
            $cats = array();
            if ($query->rows) {
                foreach ($query->rows as $row) {
                    $cats[]=$row['facategory_id'];
                }
            }
            return $cats;
        } else {
            return false;
        }
    }

    public function setFAproductCats($pid, $cids)
    {
        $this->db->query("DELETE FROM `faproduct_to_facategory` WHERE `product_id` = ".$pid);
        foreach ($cids as $cid) {
            $this->db->query("INSERT INTO `faproduct_to_facategory` SET `product_id` = ".$pid.", `facategory_id` = ".$cid);
        }
    }

    public function getFAProductCategories($product_id)
    {
                                $product_category_data = array();
                                $query = $this->db->query("SELECT * FROM faproduct_to_facategory WHERE product_id = '" . (int)$product_id . "'");
        foreach ($query->rows as $result) {
                $product_category_data[] = $result['facategory_id'];
        }
                                return $product_category_data;
    }

    public function getFAcategoryShow($product_id)
    {
        $query = $this->db->query("SELECT `facategory_id` FROM facategory_to_faproduct WHERE product_id = '" . (int)$product_id . "'");
        if ($query->num_rows) {
            return $query->row['facategory_id'];
        } else {
            return 0;
        }
    }
}
