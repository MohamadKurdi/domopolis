<?php
/*
* Shoputils
 *
 * ���������� � ������������� ����������
 *
 * ���� ���� ������ ������������ �����������, ������� ����� ����� � ������,
 * ������ � ���� ������. ���� �������� ����������: LICENSE.1.5.x.RUS.txt
 * ��� �� ������������ ���������� ����� ����� �� ������:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ���������� �� �������������
 * =================================================================
 *  ���� ���� ������������ ��� Opencart 1.5.x. Shoputils ��
 *  ����������� ���������� ������ ����� ���������� �� ����� ������ 
 *  ������ Opencart, ����� Opencart 1.5.x. 
 *  Shoputils �� ������������ ����������� ����������� ��� ������ 
 *  ������ Opencart.
 * =================================================================
*/

class ModelShippingShoputilsCitycourier extends Model {

    private $_tablename_description = 'shoputils_citycourier_description';

    public function getDescriptions(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . $this->_tablename_description);
        $rows = array();
        foreach ($query->rows as $row){
            $rows[$row['language_id']] = $row;
        }
        return $rows;
    }

    public function editDescriptions($data){
        $this->db->query("DELETE FROM " . DB_PREFIX . $this->_tablename_description);

        if (isset($data['langdata'])){
            foreach ($data['langdata'] as $key=>$langdata){
                $sql = "INSERT INTO " . DB_PREFIX . $this->_tablename_description . " SET language_id = '" . (int)$key . "', name = '" . $this->db->escape($langdata['name']) . "', description = '" . $this->db->escape($langdata['description']) . "'";
                $this->db->query($sql);
            }
        }
    }
}
?>