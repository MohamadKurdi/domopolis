<?php
/*
* Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
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