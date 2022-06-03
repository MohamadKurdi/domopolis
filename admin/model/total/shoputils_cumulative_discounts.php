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

class ModelTotalShoputilsCumulativeDiscounts extends Model {

    private $_tablename = 'shoputils_cumulative_discounts';
    private $_tablename_cmsdata = 'shoputils_cumulative_discounts_cmsdata';
    private $_tablename_to_store = 'shoputils_cumulative_discounts_to_store';
    private $_tablename_description = 'shoputils_cumulative_discounts_description';
    private $_tablename_to_customer_group = 'shoputils_cumulative_discounts_to_customer_group';
	private $_tablename_to_manufacturer = 'shoputils_cumulative_discounts_to_manufacturer';

    public function getDiscountsCMSData(){
        if (isset($this->session->data['selected_store_id'])){
            $selected_store_condition = ' WHERE store_id = '.$this->session->data['selected_store_id'];
        } else {
            $selected_store_condition = '';
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . $this->_tablename_cmsdata.$selected_store_condition);
        $rows = array();
        foreach ($query->rows as $row){
            $rows[$row['language_id']] = $row;
        }
        return $rows;
    }

    public function getAllDiscounts(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . $this->_tablename ." ORDER BY discount_id");
        $rows = $query->rows;

        foreach ($rows as &$row){
            $query = $this->db->query("SELECT store_id FROM " . DB_PREFIX . $this->_tablename_to_store . " WHERE discount_id = '".$row['discount_id']."'");
            $row['stores'] = array();
            foreach($query->rows as $item){
                $row['stores'][] = $item['store_id'];
            }

            $query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . $this->_tablename_to_customer_group . " WHERE discount_id = '".$row['discount_id']."'");
            $row['customer_groups'] = array();
            foreach($query->rows as $item){
                $row['customer_groups'][] = $item['customer_group_id'];
            }
			
			$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . $this->_tablename_to_manufacturer . " WHERE discount_id = '".$row['discount_id']."'");
            $row['manufacturers'] = array();
            foreach($query->rows as $item){
                $row['manufacturers'][] = $item['manufacturer_id'];
            }


            $query = $this->db->query("SELECT language_id, description FROM " . DB_PREFIX . $this->_tablename_description . " WHERE discount_id = '".$row['discount_id']."'");
            $row['descriptions'] = array();
            foreach($query->rows as $item){
                $row['descriptions'][$item['language_id']] = $item['description'];
            }
        }
        return $rows;
    }

    public function triggerDeleteStore($store_id = null){
        $this->db->query('DELETE FROM ' . $this->_tablename_cmsdata . ' WHERE store_id="'.$store_id.'"');
        $this->db->query('DELETE FROM ' . $this->_tablename_to_store . ' WHERE store_id="'.$store_id.'"');
    }

    public function triggerDeleteLanguage($language_id = null){
        $this->db->query('DELETE FROM ' . $this->_tablename_cmsdata . ' WHERE language_id="'.$language_id.'"');
        $this->db->query('DELETE FROM ' . $this->_tablename_description . ' WHERE language_id="'.$language_id.'"');
    }

    public function triggerDeleteCustomerGroup($customer_group_id = null){
        $this->db->query('DELETE FROM ' . $this->_tablename_to_customer_group . ' WHERE customer_group_id="'.$customer_group_id.'"');
    }
	
	  public function triggerDeleteManufacturer($manufacturer_id = null){
        $this->db->query('DELETE FROM ' . $this->_tablename_to_manufacturer . ' WHERE manufacturer_id="'.$manufacturer_id.'"');
    }


    public function editDiscounts($data){
        if (isset($this->session->data['selected_store_id'])){
            $selected_store_condition = ' WHERE store_id = '.$this->session->data['selected_store_id'];
            $store_id = $this->session->data['selected_store_id'];
        } else {
            $selected_store_condition = '';
            $store_id = 0;
        }


        $this->db->query("DELETE FROM " . DB_PREFIX . $this->_tablename_cmsdata . $selected_store_condition);
        $this->db->query("DELETE FROM " . DB_PREFIX . $this->_tablename);
        $this->db->query("DELETE FROM " . DB_PREFIX . $this->_tablename_description);
        $this->db->query("DELETE FROM " . DB_PREFIX . $this->_tablename_to_customer_group);
        $this->db->query("DELETE FROM " . DB_PREFIX . $this->_tablename_to_store);

        if (isset($data['cmsdata'])){
            foreach ($data['cmsdata'] as $key=>$cmsdata){
                $sql = "INSERT INTO " . DB_PREFIX . $this->_tablename_cmsdata . " SET store_id = '".$store_id."', language_id = '" . (int)$key . "', description_before = '" . $this->db->escape($cmsdata['description_before']) . "', description_after = '" . $this->db->escape($cmsdata['description_after']) . "'";
                $this->db->query($sql);
            }
        }

        if (isset($data['discounts'])){
            foreach ($data['discounts'] as $discount){
                $this->db->query("INSERT INTO " . DB_PREFIX . $this->_tablename." SET days = '" . (int)$discount['days'] . "', summ = '" . $this->db->escape($discount['summ']) . "', currency = '" . $this->db->escape($discount['currency']) . "', percent = '" . $this->db->escape($discount['percent']) . "'");
                $discount_id = $this->db->getLastId();

                if (isset($discount['stores'])){
                    foreach ($discount['stores'] as $store){
                        $store_id = (int)$store;
                        $this->db->query("INSERT INTO " . DB_PREFIX . $this->_tablename_to_store." SET discount_id = '" . $discount_id . "', store_id = '" . $store_id . "'");
                    }
                }
				
				 if (isset($discount['manufacturers'])){
                    foreach ($discount['manufacturers'] as $manufacturer){
                        $manufacturer_id = (int)$manufacturer;
                        $this->db->query("INSERT INTO " . DB_PREFIX . $this->_tablename_to_manufacturer." SET discount_id = '" . $discount_id . "', manufacturer_id = '" . $manufacturer_id . "'");
                    }
                }

                if (isset($discount['customer_groups'])){
                    foreach ($discount['customer_groups'] as $customer_group){
                        $customer_group_id = (int)$customer_group;
                        $this->db->query("INSERT INTO " . DB_PREFIX . $this->_tablename_to_customer_group." SET discount_id = '" . $discount_id . "', customer_group_id = '" . $customer_group_id . "'");
                    }
                }

                if (isset($discount['descriptions'])){
                    foreach ($discount['descriptions'] as $language => $description){
                        $language_id = (int)$language;
                        $this->db->query("INSERT INTO " . DB_PREFIX . $this->_tablename_description." SET discount_id = '" . $discount_id . "', language_id = '" . $language_id . "', description = '" . $this->db->escape($description) . "'");
                    }
                }
            }
        }
    }

}
?>