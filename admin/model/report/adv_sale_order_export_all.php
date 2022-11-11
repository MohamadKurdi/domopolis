<?php
class ModelReportAdvSaleOrderExportAll extends Model {
	public function getSaleOrdersExportAll($data = array()) { 
		$query = $this->db->query("SET SESSION group_concat_max_len=500000");
		
		$token = $this->session->data['token'];

		if (isset($data['filter_date_start']) && $data['filter_date_start']) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = '';
		}

		if (isset($data['filter_date_end']) && $data['filter_date_end']) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_range'])) {
			$range = $data['filter_range'];
		} else {
			$range = 'current_year'; //show Current Year in Statistical Range by default
		}

		switch($range) 
		{
			case 'custom';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
				$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";				
				break;			
			case 'today';
				$date_start = "DATE(o.date_added) = CURDATE()";
				$date_end = '';
				break;
			case 'yesterday';
				$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
				$date_end = " AND DATE(o.date_added) < CURDATE()";
				break;					
			case 'week';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				break;
			case 'month';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = '';				
				break;					
			case 'last_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = '';				
				break;					
			case 'last_year';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;	
		}
		
		$date = ' WHERE (' . $date_start . $date_end . ')';

		$osi = '';
		$sdate = '';
    	if (isset($data['filter_order_status_id']) && is_array($data['filter_order_status_id'])) {
			if ((isset($data['filter_status_date_start']) && $data['filter_status_date_start']) && (isset($data['filter_status_date_end']) && $data['filter_status_date_end'])) {
      			foreach($data['filter_order_status_id'] as $key => $val)
				{				
	        	if (!empty($osi)) $osi .= ' OR ';
    	    	$osi .= " (SELECT DISTINCT oh.order_id FROM `" . DB_PREFIX . "order_history` oh WHERE o.order_id = oh.order_id AND oh.order_status_id = '" . (int)$this->db->escape($key) . "' AND DATE(oh.date_added) >= '" . $this->db->escape($data['filter_status_date_start']) . "' AND DATE(oh.date_added) <= '" . $this->db->escape($data['filter_status_date_end']) . "')";
      			}
				$osi = ' AND (' . $osi . ') ';
			} else {
      			foreach($data['filter_order_status_id'] as $key => $val)
				{				
	        	if (!empty($osi)) $osi .= ' OR ';
    	   		$osi .= 'o.order_status_id = ' . (int)$this->db->escape($key);
      			}
				$osi = ' AND (' . $osi . ') ';
				
				$status_date_start = '';
				$status_date_end = '';
				$sdate = $status_date_start . $status_date_end;				
			}
		} else {
			if (isset($data['filter_status_date_start']) && $data['filter_status_date_start']) {		
				$status_date_start = "AND DATE(o.date_modified) >= '" . $this->db->escape($data['filter_status_date_start']) . "'";
			} else {
				$status_date_start = '';
			}
			if (isset($data['filter_status_date_end']) && $data['filter_status_date_end']) {
				$status_date_end = "AND DATE(o.date_modified) <= '" . $this->db->escape($data['filter_status_date_end']) . "'";	
			} else {
				$status_date_end = '';
			}

			$osi = ' AND o.order_status_id > 0';
			$sdate = $status_date_start . $status_date_end;
		}

		$order_id_from = '';
		$order_id_to = '';
		if (isset($data['filter_order_id_from']) && $data['filter_order_id_from']) {		
			$order_id_from = " AND o.order_id >= '" . $this->db->escape($data['filter_order_id_from']) . "'";
		} else {
			$order_id_from = '';
		}
		if (isset($data['filter_order_id_to']) && $data['filter_order_id_to']) {
			$order_id_to = " AND o.order_id <= '" . $this->db->escape($data['filter_order_id_to']) . "'";	
		} else {
			$order_id_to = '';
		}
		$order_id = $order_id_from . $order_id_to;
		
		$store = '';
    	if (isset($data['filter_store_id']) && is_array($data['filter_store_id'])) {
      		foreach($data['filter_store_id'] as $key => $val)
		{
        if (!empty($store)) $store .= ' OR ';
        $store .= 'o.store_id = ' . (int)$this->db->escape($key);
      	}
		$store = ' AND (' . $store . ') ';
	    }
		
		$cur = '';
    	if (isset($data['filter_currency']) && is_array($data['filter_currency'])) {
      		foreach($data['filter_currency'] as $key => $val)
		{
        if (!empty($cur)) $cur .= ' OR ';
        $cur .= 'o.currency_id = ' . (int)$this->db->escape($key);
      	}
		$cur = ' AND (' . $cur . ') ';
	    }
		
		$tax = '';
    	if (isset($data['filter_taxes']) && is_array($data['filter_taxes'])) {
      		foreach($data['filter_taxes'] as $key => $val)
		{
        if (!empty($tax)) $tax .= ' OR ';
        $tax .= " (SELECT HEX(ot.title) FROM `" . DB_PREFIX . "order_total` ot WHERE o.order_id = ot.order_id AND ot.code = 'tax' AND HEX(ot.title) = '" . $this->db->escape($key) . "')";		
      	}
		$tax = ' AND (' . $tax . ') ';
	    }

		$tclass = '';
    	if (isset($data['filter_tax_classes']) && is_array($data['filter_tax_classes'])) {
      		foreach($data['filter_tax_classes'] as $key => $val)
		{
        if (!empty($tclass)) $tclass .= ' OR ';
		$tclass .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "tax_class` tc, `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND p.tax_class_id = '" . (int)$this->db->escape($key) . "')";	
      	}
		$tclass = ' AND (' . $tclass . ') ';
	    }
		
		$geo_zone = '';
    	if (isset($data['filter_geo_zones']) && is_array($data['filter_geo_zones'])) {
      		foreach($data['filter_geo_zones'] as $key => $val)
		{
        if (!empty($geo_zone)) $geo_zone .= ' OR ';
		$geo_zone .= " (SELECT gz.geo_zone_id FROM `" . DB_PREFIX . "geo_zone` gz, `" . DB_PREFIX . "zone_to_geo_zone` zgz WHERE ((gz.geo_zone_id = zgz.geo_zone_id AND zgz.zone_id = 0 AND zgz.country_id = o.payment_country_id AND gz.geo_zone_id = '" . $this->db->escape($key) . "') OR (gz.geo_zone_id = zgz.geo_zone_id AND o.payment_zone_id = zgz.zone_id AND gz.geo_zone_id = '" . (int)$this->db->escape($key) . "')))";	
      	}
		$geo_zone = ' AND (' . $geo_zone . ') ';
	    }
		
		$cgrp = '';
    	if (isset($data['filter_customer_group_id']) && is_array($data['filter_customer_group_id'])) {
      		foreach($data['filter_customer_group_id'] as $key => $val)
		{
        if (!empty($cgrp)) $cgrp .= ' OR ';
        $cgrp .= " ((SELECT c.customer_group_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND c.customer_group_id = '" . (int)$this->db->escape($key) . "') OR (o.customer_group_id = '" . (int)$this->db->escape($key) . "' AND o.customer_id = 0))";
      	}
		$cgrp = ' AND (' . $cgrp . ') ';
	    }
		
		$cust = '';
		if (!empty($data['filter_customer_name'])) {
			$cust = " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
		} else {
			$cust = '';
		}

		$email = '';
		if (!empty($data['filter_customer_email'])) {
			$email = " AND LCASE(o.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";			
		} else {
			$email = '';
		}

		$tel = '';
		if (!empty($data['filter_customer_telephone'])) {
			$tel = " AND LCASE(o.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";			
		} else {
			$tel = '';
		}

		$ip = '';
		if (!empty($data['filter_ip'])) {
        	$ip = " AND LCASE(o.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
		} else {
			$ip = '';
		}
		
		$pcomp = '';
		if (!empty($data['filter_payment_company'])) {
			$pcomp = " AND LCASE(o.payment_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
		} else {
			$pcomp = '';
		}

		$paddr = '';
		if (!empty($data['filter_payment_address'])) {
			$paddr = " AND LCASE(CONCAT(o.payment_address_1, ', ', o.payment_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
		} else {
			$paddr = '';
		}

		$pcity = '';
		if (!empty($data['filter_payment_city'])) {
			$pcity = " AND LCASE(o.payment_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
		} else {
			$pcity = '';
		}

		$pzone = '';
		if (!empty($data['filter_payment_zone'])) {
			$pzone = " AND LCASE(o.payment_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
		} else {
			$pzone = '';
		}

		$ppsc = '';
		if (!empty($data['filter_payment_postcode'])) {
			$ppsc = " AND LCASE(o.payment_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";			
		} else {
			$ppsc = '';
		}

		$pcntr = '';
		if (!empty($data['filter_payment_country'])) {
			$pcntr = " AND LCASE(o.payment_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";			
		} else {
			$pcntr = '';
		}

		$pmeth = '';
    	if (isset($data['filter_payment_method']) && is_array($data['filter_payment_method'])) {
      		foreach($data['filter_payment_method'] as $key => $val)
		{
        if (!empty($pmeth)) $pmeth .= ' OR ';
        $pmeth .= " o.payment_code = '" . $this->db->escape($key) . "'";
      	}
		$pmeth = ' AND (' . $pmeth . ') ';
	    }

		$scomp = '';
		if (!empty($data['filter_shipping_company'])) {
			$scomp = " AND LCASE(o.shipping_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_company'], 'UTF-8')) . "%'";
		} else {
			$scomp = '';
		}

		$saddr = '';
		if (!empty($data['filter_shipping_address'])) {
			$saddr = " AND LCASE(CONCAT(o.shipping_address_1, ', ', o.shipping_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_address'], 'UTF-8')) . "%'";
		} else {
			$saddr = '';
		}

		$scity = '';
		if (!empty($data['filter_shipping_city'])) {
			$scity = " AND LCASE(o.shipping_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_city'], 'UTF-8')) . "%'";
		} else {
			$scity = '';
		}


		$szone = '';
		if (!empty($data['filter_shipping_zone'])) {
			$szone = " AND LCASE(o.shipping_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_zone'], 'UTF-8')) . "%'";
		} else {
			$szone = '';
		}

		$spsc = '';
		if (!empty($data['filter_shipping_postcode'])) {
			$spsc = " AND LCASE(o.shipping_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_postcode'], 'UTF-8')) . "%'";			
		} else {
			$spsc = '';
		}

		$scntr = '';
		if (!empty($data['filter_shipping_country'])) {
			$scntr = " AND LCASE(o.shipping_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_country'], 'UTF-8')) . "%'";			
		} else {
			$scntr = '';
		}

		$smeth = '';
    	if (isset($data['filter_shipping_method']) && is_array($data['filter_shipping_method'])) {
      		foreach($data['filter_shipping_method'] as $key => $val)
		{
        if (!empty($smeth)) $smeth .= ' OR ';
        $smeth .= " o.shipping_code = '" . $this->db->escape($key) . "'";
      	}
		$smeth = ' AND (' . $smeth . ') ';
	    }

		$cat = '';
    	if (isset($data['filter_category']) && is_array($data['filter_category'])) {
      		foreach($data['filter_category'] as $key => $val)
		{
        if (!empty($cat)) $cat .= ' OR ';
        $cat .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product_to_category` p2c, `" . DB_PREFIX . "order_product` op WHERE p2c.product_id = op.product_id AND o.order_id = op.order_id AND p2c.category_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$cat = ' AND (' . $cat . ') ';
		} else {
		$cat = '';
		}

		$manu = '';
    	if (isset($data['filter_manufacturer']) && is_array($data['filter_manufacturer'])) {
      		foreach($data['filter_manufacturer'] as $key => $val)
		{
        if (!empty($manu)) $manu .= ' OR ';
        $manu .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND p.manufacturer_id = '" . (int)$this->db->escape($key) . "')";		
      	}
		$manu = ' AND (' . $manu . ') ';
	    }
		
		$sku = '';
		if (!empty($data['filter_sku'])) {
        	$sku = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND LCASE(p.sku) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_sku'], 'UTF-8')) . "%')";	
		} else {
			$sku = '';
		}
		
		$prod = '';
		if (!empty($data['filter_product_id'])) {
        	$prod = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_id'], 'UTF-8')) . "%')";				
		} else {
			$prod = '';
		}
		
		$mod = '';
		if (!empty($data['filter_model'])) {
        	$mod = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.model) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_model'], 'UTF-8')) . "%')";		
		} else {
			$mod = '';
		}
		
		$opt = '';
    	if (isset($data['filter_option']) && is_array($data['filter_option'])) {	
      		foreach($data['filter_option'] as $key => $val)
		{
        if (!empty($opt)) $opt .= ' AND ';
        $opt .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_option` oo, `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND oo.order_product_id = op.order_product_id AND HEX(CONCAT(oo.name, oo.value, oo.type)) = '" . $this->db->escape($key) . "' AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_id'], 'UTF-8')) . "%')";
      	}
		$opt = ' AND ' . $opt;	
		}

		$atr = '';
    	if (isset($data['filter_attribute']) && is_array($data['filter_attribute'])) {	
      		foreach($data['filter_attribute'] as $key => $val)
		{
        if (!empty($atr)) $atr .= ' AND ';
        $atr .= " (SELECT DISTINCT op.product_id FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE o.order_id = op.order_id AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND HEX(CONCAT(agd.name, ad.name, pa.text)) = '" . $this->db->escape($key) . "' GROUP BY op.order_id)";		
      	}
		$atr = ' AND ' . $atr;
		}
		
		$loc = '';
    	if (isset($data['filter_location']) && is_array($data['filter_location'])) {
      		foreach($data['filter_location'] as $key => $val)
		{
        if (!empty($loc)) $loc .= ' OR ';
        $loc .= " (SELECT DISTINCT HEX(p.location) FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND HEX(p.location) = '" . $this->db->escape($key) . "')";
      	}
		$loc = ' AND (' . $loc . ') ';
	    }

		$affn = '';
    	if (isset($data['filter_affiliate_name']) && is_array($data['filter_affiliate_name'])) {
      		foreach($data['filter_affiliate_name'] as $key => $val)
		{
        if (!empty($affn)) $affn .= ' OR ';
        $affn .= " (SELECT at.affiliate_id FROM `" . DB_PREFIX . "affiliate_transaction` at WHERE at.order_id = o.order_id AND at.affiliate_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$affn = ' AND (' . $affn . ') ';
	    }

		$affe = '';
    	if (isset($data['filter_affiliate_email']) && is_array($data['filter_affiliate_email'])) {
      		foreach($data['filter_affiliate_email'] as $key => $val)
		{
        if (!empty($affe)) $affe .= ' OR ';
        $affe .= " (SELECT at.affiliate_id FROM `" . DB_PREFIX . "affiliate_transaction` at WHERE at.order_id = o.order_id AND at.affiliate_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$affe = ' AND (' . $affe . ') ';
	    }

		$cpn = '';
    	if (isset($data['filter_coupon_name']) && is_array($data['filter_coupon_name'])) {
      		foreach($data['filter_coupon_name'] as $key => $val)
		{
        if (!empty($cpn)) $cpn .= ' OR ';
        $cpn .= " (SELECT cph.coupon_id FROM `" . DB_PREFIX . "coupon_history` cph WHERE cph.order_id = o.order_id AND cph.coupon_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$cpn = ' AND (' . $cpn . ') ';
	    }

		$cpc = '';
		if (!empty($data['filter_coupon_code'])) {
			$cpc = " AND (SELECT cph.coupon_id FROM `" . DB_PREFIX . "coupon` cp, `" . DB_PREFIX . "coupon_history` cph WHERE cph.coupon_id = cp.coupon_id AND cph.order_id = o.order_id AND LCASE(cp.code) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_coupon_code'], 'UTF-8')) . "%')";	
		} else {
			$cpc = '';
		}

		$gvc = '';
		if (!empty($data['filter_voucher_code'])) {
        	$gvc = " AND (SELECT gvh.voucher_id FROM `" . DB_PREFIX . "voucher` gv, `" . DB_PREFIX . "voucher_history` gvh WHERE gvh.voucher_id = gv.voucher_id AND gvh.order_id = o.order_id AND LCASE(gv.code) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_voucher_code'], 'UTF-8')) . "%')";	
		} else {
			$gvc = '';
		}

		$sql = "SELECT *, 
		MIN(o.date_added) AS date_start, 
		MAX(o.date_added) AS date_end, 
		(SELECT CONCAT('<a href=\"index.php?route=sale/order/info&token=$token&order_id=',op.order_id,'\">',op.order_id,'</a>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_ord_id, 
		(SELECT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_ord_idc, 
		(SELECT (SELECT DATE_FORMAT(o.date_added, '%d/%m/%Y') FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.date_added) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_order_date, 
		(SELECT (SELECT CONCAT(IFNULL(o.invoice_prefix,'&nbsp;'),IFNULL(o.invoice_no,'&nbsp;')) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY CONCAT(o.invoice_prefix,o.invoice_no)) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_inv_no, 
		(SELECT (SELECT CONCAT(o.firstname,' ',o.lastname,IF (o.payment_company = '','',CONCAT(' / ',o.payment_company))) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY CONCAT(o.firstname,o.lastname)) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_name, 
		(SELECT (SELECT o.email FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.email) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_email, 
		(SELECT IFNULL((SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE op.order_id = o.order_id AND cgd.customer_group_id = o.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cgd.name),'&nbsp;') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_group, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT CONCAT('<a href=\"index.php?route=catalog/product/update&token=$token&product_id=',op.product_id,'\">',op.product_id,'</a>') FROM `" . DB_PREFIX . "product` p WHERE op.product_id = p.product_id),op.product_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_pid, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(op.product_id SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_pidc, 
		GROUP_CONCAT((SELECT GROUP_CONCAT((SELECT IF (p.sku = '','&nbsp;&nbsp;',p.sku) FROM `" . DB_PREFIX . "product` p WHERE op.product_id = p.product_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_sku, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(op.model SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_model, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(op.name SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_name, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT GROUP_CONCAT(CONCAT(oo.name,': ',oo.value) SEPARATOR '; ') FROM `" . DB_PREFIX . "order_option` oo WHERE op.order_product_id = oo.order_product_id AND oo.type != 'textarea' ORDER BY op.order_product_id),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_option, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT GROUP_CONCAT(CONCAT(agd.name,'" . $this->language->get('text_separator') . "',ad.name,'" . $this->language->get('text_separator') . "',pa.text) SEPARATOR '; ') FROM `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY agd.name, ad.name, pa.text ASC),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_attributes, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT m.name FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "manufacturer` m WHERE op.product_id = p.product_id AND p.manufacturer_id = m.manufacturer_id),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_manu, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(IFNULL((SELECT GROUP_CONCAT(cd.name SEPARATOR ', ') FROM `" . DB_PREFIX . "category_description` cd, `" . DB_PREFIX . "category` c, `" . DB_PREFIX . "product_to_category` p2c WHERE op.product_id = p2c.product_id AND p2c.category_id = c.category_id AND (c.category_id = cd.category_id OR c.parent_id = cd.category_id) AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status > 0),'&nbsp;') SEPARATOR '&nbsp;<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_category, 
		GROUP_CONCAT((SELECT GROUP_CONCAT((SELECT o.currency_code FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_currency,  
		GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*op.price, 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_price, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(op.quantity SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_quantity, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*op.total, 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_total_excl_vat, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*op.tax*op.quantity, 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_tax, 
		GROUP_CONCAT((SELECT GROUP_CONCAT(ROUND(o.currency_value*(op.total+(op.tax*op.quantity)), 2) SEPARATOR '<br>') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id ORDER BY op.order_product_id) ORDER BY o.order_id DESC SEPARATOR '<br>') AS product_total_incl_vat, 
		(SELECT (SELECT o.currency_code FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.currency_code) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_currency, 		
		(IFNULL((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id),'&nbsp;')) AS order_products, 
		(SELECT IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE op.order_id = o.order_id AND ot.order_id = o.order_id AND ot.code = 'sub_total' GROUP BY ot.order_id),'0') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_sub_total, 
		(SELECT IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE op.order_id = o.order_id AND ot.order_id = o.order_id AND ot.code = 'shipping' GROUP BY ot.order_id),'0')  FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_shipping, 
		(SELECT IFNULL((SELECT ROUND(o.currency_value*SUM(ot.value), 2) FROM `" . DB_PREFIX . "order_total` ot WHERE op.order_id = o.order_id AND ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id),'0') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_tax, 
		(SELECT (SELECT ROUND(o.currency_value*o.total, 2) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.total) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_value, 
		(SELECT (SELECT IF (o.shipping_method = '','&nbsp;',o.shipping_method) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_method) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_shipping_method, 
		(SELECT (SELECT IF (o.payment_method = '','&nbsp;',o.payment_method) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_method) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_payment_method, 
		(SELECT IFNULL((SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE op.order_id = o.order_id AND os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY os.name),'&nbsp;') FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_status, 
		(SELECT (SELECT o.store_name FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.store_name) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS order_store, 
		(SELECT (SELECT IF (o.customer_id = 0,'0',CONCAT('<a href=\"index.php?route=sale/customer/update&token=$token&customer_id=',o.customer_id,'\">',o.customer_id,'</a>')) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.customer_id) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS customer_cust_id, 
		(SELECT (SELECT IF (o.customer_id = 0,'0',o.customer_id) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.customer_id) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS customer_cust_idc, 
		(SELECT (SELECT CONCAT(o.payment_firstname,' ',o.payment_lastname) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY CONCAT(o.payment_firstname,' ',o.payment_lastname)) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_name, 
		(SELECT (SELECT o.payment_company FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_company) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_company, 
		(SELECT (SELECT o.payment_address_1 FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_address_1) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_address_1, 
		(SELECT (SELECT o.payment_address_2 FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_address_2) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_address_2, 
		(SELECT (SELECT o.payment_city FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_city) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_city, 
		(SELECT (SELECT o.payment_zone FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_zone) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_zone, 
		(SELECT (SELECT o.payment_postcode FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_postcode) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_postcode, 
		(SELECT (SELECT o.payment_country FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.payment_country) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS billing_country, 
		(SELECT (SELECT o.telephone  FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.email) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS customer_telephone, 
		(SELECT (SELECT CONCAT(o.shipping_firstname,' ',o.shipping_lastname) FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY CONCAT(o.shipping_firstname,' ',o.shipping_lastname)) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_name, 
		(SELECT (SELECT o.shipping_company FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_company) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_company, 		
		(SELECT (SELECT o.shipping_address_1 FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_address_1) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_address_1, 
		(SELECT (SELECT o.shipping_address_2 FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_address_2) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_address_2, 
		(SELECT (SELECT o.shipping_city FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_city) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_city, 
		(SELECT (SELECT o.shipping_zone FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_zone) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_zone, 
		(SELECT (SELECT o.shipping_postcode FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_postcode) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_postcode, 
		(SELECT (SELECT o.shipping_country FROM `" . DB_PREFIX . "order` o WHERE op.order_id = o.order_id GROUP BY o.shipping_country) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS shipping_country 
		
		FROM `" . DB_PREFIX . "order` o" . $date . $sdate . $osi . $order_id . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		
		$sql .= " GROUP BY o.order_id ORDER BY o.order_id DESC";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getSaleOrdersExportAllExcel($data = array()) { 	
		$query = $this->db->query("SET SESSION group_concat_max_len=500000");
		
		$token = $this->session->data['token'];

		if (isset($data['filter_date_start']) && $data['filter_date_start']) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = '';
		}

		if (isset($data['filter_date_end']) && $data['filter_date_end']) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = '';
		}

		if (isset($data['filter_range'])) {
			$range = $data['filter_range'];
		} else {
			$range = 'current_year'; //show Current Year in Statistical Range by default
		}

		switch($range) 
		{
			case 'custom';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
				$date_end = " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";				
				break;			
			case 'today';
				$date_start = "DATE(o.date_added) = CURDATE()";
				$date_end = '';
				break;
			case 'yesterday';
				$date_start = "DATE(o.date_added) >= DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
				$date_end = " AND DATE(o.date_added) < CURDATE()";
				break;					
			case 'week';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-7 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";	
				break;
			case 'month';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-30 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;			
			case 'quarter';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-91 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;
			case 'year';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d', strtotime('-365 day'))) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";					
				break;
			case 'current_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - WEEKDAY(CURDATE())";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";			
				break;	
			case 'current_month';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = " AND MONTH(o.date_added) = MONTH(CURDATE())";			
				break;
			case 'current_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(CURDATE())";
				$date_end = " AND YEAR(o.date_added) = YEAR(CURDATE())";					
				break;					
			case 'current_year';
				$date_start = "YEAR(o.date_added) = YEAR(CURDATE())";
				$date_end = '';				
				break;					
			case 'last_week';
				$date_start = "DATE(o.date_added) >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY";
				$date_end = " AND DATE(o.date_added) < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-2 DAY";				
				break;	
			case 'last_month';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/%m/01')";				
				break;
			case 'last_quarter';
				$date_start = "QUARTER(o.date_added) = QUARTER(DATE_ADD(NOW(), INTERVAL -3 MONTH))";
				$date_end = '';				
				break;					
			case 'last_year';
				$date_start = "DATE(o.date_added) >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 YEAR, '%Y/01/01')";
				$date_end = " AND DATE(o.date_added) < DATE_FORMAT(CURRENT_DATE, '%Y/01/01')";				
				break;					
			case 'all_time';
				$date_start = "DATE(o.date_added) >= '" . $this->db->escape(date('Y-m-d','0')) . "'";
				$date_end = " AND DATE(o.date_added) <= DATE (NOW())";						
				break;	
		}
		
		$date = ' WHERE (' . $date_start . $date_end . ')';

		$osi = '';
		$sdate = '';
    	if (isset($data['filter_order_status_id']) && is_array($data['filter_order_status_id'])) {
			if ((isset($data['filter_status_date_start']) && $data['filter_status_date_start']) && (isset($data['filter_status_date_end']) && $data['filter_status_date_end'])) {
      			foreach($data['filter_order_status_id'] as $key => $val)
				{				
	        	if (!empty($osi)) $osi .= ' OR ';
    	    	$osi .= " (SELECT DISTINCT oh.order_id FROM `" . DB_PREFIX . "order_history` oh WHERE o.order_id = oh.order_id AND oh.order_status_id = '" . (int)$this->db->escape($key) . "' AND DATE(oh.date_added) >= '" . $this->db->escape($data['filter_status_date_start']) . "' AND DATE(oh.date_added) <= '" . $this->db->escape($data['filter_status_date_end']) . "')";
      			}
				$osi = ' AND (' . $osi . ') ';
			} else {
      			foreach($data['filter_order_status_id'] as $key => $val)
				{				
	        	if (!empty($osi)) $osi .= ' OR ';
    	   		$osi .= 'o.order_status_id = ' . (int)$this->db->escape($key);
      			}
				$osi = ' AND (' . $osi . ') ';
				
				$status_date_start = '';
				$status_date_end = '';
				$sdate = $status_date_start . $status_date_end;				
			}
		} else {
			if (isset($data['filter_status_date_start']) && $data['filter_status_date_start']) {		
				$status_date_start = "AND DATE(o.date_modified) >= '" . $this->db->escape($data['filter_status_date_start']) . "'";
			} else {
				$status_date_start = '';
			}
			if (isset($data['filter_status_date_end']) && $data['filter_status_date_end']) {
				$status_date_end = "AND DATE(o.date_modified) <= '" . $this->db->escape($data['filter_status_date_end']) . "'";	
			} else {
				$status_date_end = '';
			}

			$osi = ' AND o.order_status_id > 0';
			$sdate = $status_date_start . $status_date_end;
		}

		$order_id_from = '';
		$order_id_to = '';
		if (isset($data['filter_order_id_from']) && $data['filter_order_id_from']) {		
			$order_id_from = " AND o.order_id >= '" . $this->db->escape($data['filter_order_id_from']) . "'";
		} else {
			$order_id_from = '';
		}
		if (isset($data['filter_order_id_to']) && $data['filter_order_id_to']) {
			$order_id_to = " AND o.order_id <= '" . $this->db->escape($data['filter_order_id_to']) . "'";	
		} else {
			$order_id_to = '';
		}
		$order_id = $order_id_from . $order_id_to;
		
		$store = '';
    	if (isset($data['filter_store_id']) && is_array($data['filter_store_id'])) {
      		foreach($data['filter_store_id'] as $key => $val)
		{
        if (!empty($store)) $store .= ' OR ';
        $store .= 'o.store_id = ' . (int)$this->db->escape($key);
      	}
		$store = ' AND (' . $store . ') ';
	    }
		
		$cur = '';
    	if (isset($data['filter_currency']) && is_array($data['filter_currency'])) {
      		foreach($data['filter_currency'] as $key => $val)
		{
        if (!empty($cur)) $cur .= ' OR ';
        $cur .= 'o.currency_id = ' . (int)$this->db->escape($key);
      	}
		$cur = ' AND (' . $cur . ') ';
	    }
		
		$tax = '';
    	if (isset($data['filter_taxes']) && is_array($data['filter_taxes'])) {
      		foreach($data['filter_taxes'] as $key => $val)
		{
        if (!empty($tax)) $tax .= ' OR ';
        $tax .= " (SELECT HEX(ot.title) FROM `" . DB_PREFIX . "order_total` ot WHERE o.order_id = ot.order_id AND ot.code = 'tax' AND HEX(ot.title) = '" . $this->db->escape($key) . "')";		
      	}
		$tax = ' AND (' . $tax . ') ';
	    }

		$tclass = '';
    	if (isset($data['filter_tax_classes']) && is_array($data['filter_tax_classes'])) {
      		foreach($data['filter_tax_classes'] as $key => $val)
		{
        if (!empty($tclass)) $tclass .= ' OR ';
		$tclass .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "tax_class` tc, `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND p.tax_class_id = '" . (int)$this->db->escape($key) . "')";	
      	}
		$tclass = ' AND (' . $tclass . ') ';
	    }
		
		$geo_zone = '';
    	if (isset($data['filter_geo_zones']) && is_array($data['filter_geo_zones'])) {
      		foreach($data['filter_geo_zones'] as $key => $val)
		{
        if (!empty($geo_zone)) $geo_zone .= ' OR ';
		$geo_zone .= " (SELECT gz.geo_zone_id FROM `" . DB_PREFIX . "geo_zone` gz, `" . DB_PREFIX . "zone_to_geo_zone` zgz WHERE ((gz.geo_zone_id = zgz.geo_zone_id AND zgz.zone_id = 0 AND zgz.country_id = o.payment_country_id AND gz.geo_zone_id = '" . $this->db->escape($key) . "') OR (gz.geo_zone_id = zgz.geo_zone_id AND o.payment_zone_id = zgz.zone_id AND gz.geo_zone_id = '" . (int)$this->db->escape($key) . "')))";	
      	}
		$geo_zone = ' AND (' . $geo_zone . ') ';
	    }
		
		$cgrp = '';
    	if (isset($data['filter_customer_group_id']) && is_array($data['filter_customer_group_id'])) {
      		foreach($data['filter_customer_group_id'] as $key => $val)
		{
        if (!empty($cgrp)) $cgrp .= ' OR ';
        $cgrp .= " ((SELECT c.customer_group_id FROM `" . DB_PREFIX . "customer` c WHERE c.customer_id = o.customer_id AND c.customer_group_id = '" . (int)$this->db->escape($key) . "') OR (o.customer_group_id = '" . (int)$this->db->escape($key) . "' AND o.customer_id = 0))";
      	}
		$cgrp = ' AND (' . $cgrp . ') ';
	    }
		
		$cust = '';
		if (!empty($data['filter_customer_name'])) {
			$cust = " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_name'], 'UTF-8')) . "%'";
		} else {
			$cust = '';
		}

		$email = '';
		if (!empty($data['filter_customer_email'])) {
			$email = " AND LCASE(o.email) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_email'], 'UTF-8')) . "%'";			
		} else {
			$email = '';
		}

		$tel = '';
		if (!empty($data['filter_customer_telephone'])) {
			$tel = " AND LCASE(o.telephone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_customer_telephone'], 'UTF-8')) . "%'";			
		} else {
			$tel = '';
		}

		$ip = '';
		if (!empty($data['filter_ip'])) {
        	$ip = " AND LCASE(o.ip) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_ip'], 'UTF-8')) . "%'";
		} else {
			$ip = '';
		}
		
		$pcomp = '';
		if (!empty($data['filter_payment_company'])) {
			$pcomp = " AND LCASE(o.payment_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_company'], 'UTF-8')) . "%'";
		} else {
			$pcomp = '';
		}

		$paddr = '';
		if (!empty($data['filter_payment_address'])) {
			$paddr = " AND LCASE(CONCAT(o.payment_address_1, ', ', o.payment_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_address'], 'UTF-8')) . "%'";
		} else {
			$paddr = '';
		}

		$pcity = '';
		if (!empty($data['filter_payment_city'])) {
			$pcity = " AND LCASE(o.payment_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_city'], 'UTF-8')) . "%'";
		} else {
			$pcity = '';
		}

		$pzone = '';
		if (!empty($data['filter_payment_zone'])) {
			$pzone = " AND LCASE(o.payment_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_zone'], 'UTF-8')) . "%'";
		} else {
			$pzone = '';
		}

		$ppsc = '';
		if (!empty($data['filter_payment_postcode'])) {
			$ppsc = " AND LCASE(o.payment_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_postcode'], 'UTF-8')) . "%'";			
		} else {
			$ppsc = '';
		}

		$pcntr = '';
		if (!empty($data['filter_payment_country'])) {
			$pcntr = " AND LCASE(o.payment_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_payment_country'], 'UTF-8')) . "%'";			
		} else {
			$pcntr = '';
		}

		$pmeth = '';
    	if (isset($data['filter_payment_method']) && is_array($data['filter_payment_method'])) {
      		foreach($data['filter_payment_method'] as $key => $val)
		{
        if (!empty($pmeth)) $pmeth .= ' OR ';
        $pmeth .= " o.payment_code = '" . $this->db->escape($key) . "'";
      	}
		$pmeth = ' AND (' . $pmeth . ') ';
	    }

		$scomp = '';
		if (!empty($data['filter_shipping_company'])) {
			$scomp = " AND LCASE(o.shipping_company) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_company'], 'UTF-8')) . "%'";
		} else {
			$scomp = '';
		}

		$saddr = '';
		if (!empty($data['filter_shipping_address'])) {
			$saddr = " AND LCASE(CONCAT(o.shipping_address_1, ', ', o.shipping_address_2)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_address'], 'UTF-8')) . "%'";
		} else {
			$saddr = '';
		}

		$scity = '';
		if (!empty($data['filter_shipping_city'])) {
			$scity = " AND LCASE(o.shipping_city) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_city'], 'UTF-8')) . "%'";
		} else {
			$scity = '';
		}

		$szone = '';
		if (!empty($data['filter_shipping_zone'])) {
			$szone = " AND LCASE(o.shipping_zone) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_zone'], 'UTF-8')) . "%'";
		} else {
			$szone = '';
		}

		$spsc = '';
		if (!empty($data['filter_shipping_postcode'])) {
			$spsc = " AND LCASE(o.shipping_postcode) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_postcode'], 'UTF-8')) . "%'";			
		} else {
			$spsc = '';
		}

		$scntr = '';
		if (!empty($data['filter_shipping_country'])) {
			$scntr = " AND LCASE(o.shipping_country) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_shipping_country'], 'UTF-8')) . "%'";			
		} else {
			$scntr = '';
		}

		$smeth = '';
    	if (isset($data['filter_shipping_method']) && is_array($data['filter_shipping_method'])) {
      		foreach($data['filter_shipping_method'] as $key => $val)
		{
        if (!empty($smeth)) $smeth .= ' OR ';
        $smeth .= " o.shipping_code = '" . $this->db->escape($key) . "'";
      	}
		$smeth = ' AND (' . $smeth . ') ';
	    }

		$cat = '';
    	if (isset($data['filter_category']) && is_array($data['filter_category'])) {
      		foreach($data['filter_category'] as $key => $val)
		{
        if (!empty($cat)) $cat .= ' OR ';
        $cat .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product_to_category` p2c, `" . DB_PREFIX . "order_product` op WHERE p2c.product_id = op.product_id AND o.order_id = op.order_id AND p2c.category_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$cat = ' AND (' . $cat . ') ';
		} else {
		$cat = '';
		}

		$manu = '';
    	if (isset($data['filter_manufacturer']) && is_array($data['filter_manufacturer'])) {
      		foreach($data['filter_manufacturer'] as $key => $val)
		{
        if (!empty($manu)) $manu .= ' OR ';
        $manu .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND p.manufacturer_id = '" . (int)$this->db->escape($key) . "')";		
      	}
		$manu = ' AND (' . $manu . ') ';
	    }
		
		$sku = '';
		if (!empty($data['filter_sku'])) {
        	$sku = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND LCASE(p.sku) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_sku'], 'UTF-8')) . "%')";	
		} else {
			$sku = '';
		}
		
		$prod = '';
		if (!empty($data['filter_product_id'])) {
        	$prod = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_id'], 'UTF-8')) . "%')";				
		} else {
			$prod = '';
		}
		
		$mod = '';
		if (!empty($data['filter_model'])) {
        	$mod = " AND (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND LCASE(op.model) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_model'], 'UTF-8')) . "%')";		
		} else {
			$mod = '';
		}
		
		$opt = '';
    	if (isset($data['filter_option']) && is_array($data['filter_option'])) {	
      		foreach($data['filter_option'] as $key => $val)
		{
        if (!empty($opt)) $opt .= ' AND ';
        $opt .= " (SELECT DISTINCT op.order_id FROM `" . DB_PREFIX . "order_option` oo, `" . DB_PREFIX . "order_product` op WHERE o.order_id = op.order_id AND oo.order_product_id = op.order_product_id AND HEX(CONCAT(oo.name, oo.value, oo.type)) = '" . $this->db->escape($key) . "' AND LCASE(op.name) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_product_id'], 'UTF-8')) . "%')";
      	}
		$opt = ' AND ' . $opt;	
		}

		$atr = '';
    	if (isset($data['filter_attribute']) && is_array($data['filter_attribute'])) {	
      		foreach($data['filter_attribute'] as $key => $val)
		{
        if (!empty($atr)) $atr .= ' AND ';
        $atr .= " (SELECT DISTINCT op.product_id FROM `" . DB_PREFIX . "order_product` op, `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE o.order_id = op.order_id AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND HEX(CONCAT(agd.name, ad.name, pa.text)) = '" . $this->db->escape($key) . "' GROUP BY op.order_id)";		
      	}
		$atr = ' AND ' . $atr;
		}
		
		$loc = '';
    	if (isset($data['filter_location']) && is_array($data['filter_location'])) {
      		foreach($data['filter_location'] as $key => $val)
		{
        if (!empty($loc)) $loc .= ' OR ';
        $loc .= " (SELECT DISTINCT HEX(p.location) FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "order_product` op WHERE p.product_id = op.product_id AND o.order_id = op.order_id AND HEX(p.location) = '" . $this->db->escape($key) . "')";
      	}
		$loc = ' AND (' . $loc . ') ';
	    }

		$affn = '';
    	if (isset($data['filter_affiliate_name']) && is_array($data['filter_affiliate_name'])) {
      		foreach($data['filter_affiliate_name'] as $key => $val)
		{
        if (!empty($affn)) $affn .= ' OR ';
        $affn .= " (SELECT at.affiliate_id FROM `" . DB_PREFIX . "affiliate_transaction` at WHERE at.order_id = o.order_id AND at.affiliate_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$affn = ' AND (' . $affn . ') ';
	    }

		$affe = '';
    	if (isset($data['filter_affiliate_email']) && is_array($data['filter_affiliate_email'])) {
      		foreach($data['filter_affiliate_email'] as $key => $val)
		{
        if (!empty($affe)) $affe .= ' OR ';
        $affe .= " (SELECT at.affiliate_id FROM `" . DB_PREFIX . "affiliate_transaction` at WHERE at.order_id = o.order_id AND at.affiliate_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$affe = ' AND (' . $affe . ') ';
	    }

		$cpn = '';
    	if (isset($data['filter_coupon_name']) && is_array($data['filter_coupon_name'])) {
      		foreach($data['filter_coupon_name'] as $key => $val)
		{
        if (!empty($cpn)) $cpn .= ' OR ';
        $cpn .= " (SELECT cph.coupon_id FROM `" . DB_PREFIX . "coupon_history` cph WHERE cph.order_id = o.order_id AND cph.coupon_id = '" . (int)$this->db->escape($key) . "')";
      	}
		$cpn = ' AND (' . $cpn . ') ';
	    }

		$cpc = '';
		if (!empty($data['filter_coupon_code'])) {
			$cpc = " AND (SELECT cph.coupon_id FROM `" . DB_PREFIX . "coupon` cp, `" . DB_PREFIX . "coupon_history` cph WHERE cph.coupon_id = cp.coupon_id AND cph.order_id = o.order_id AND LCASE(cp.code) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_coupon_code'], 'UTF-8')) . "%')";	
		} else {
			$cpc = '';
		}

		$gvc = '';
		if (!empty($data['filter_voucher_code'])) {
        	$gvc = " AND (SELECT gvh.voucher_id FROM `" . DB_PREFIX . "voucher` gv, `" . DB_PREFIX . "voucher_history` gvh WHERE gvh.voucher_id = gv.voucher_id AND gvh.order_id = o.order_id AND LCASE(gv.code) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_voucher_code'], 'UTF-8')) . "%')";	
		} else {
			$gvc = '';
		}

		$sql = "SELECT *, 
		(SELECT cgd.name FROM `" . DB_PREFIX . "customer_group_description` cgd WHERE op.order_id = o.order_id AND cgd.customer_group_id = o.customer_group_id AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_group, 
		op.order_product_id, 
		op.product_id, 
		(SELECT p.sku FROM " . DB_PREFIX . "product p WHERE op.order_id = o.order_id AND op.product_id = p.product_id) AS product_sku, 
		op.model AS product_model, 
		op.name AS product_name, 
		(SELECT GROUP_CONCAT(CONCAT(oo.name,': ',oo.value) SEPARATOR '; ') FROM `" . DB_PREFIX . "order_option` oo WHERE op.order_product_id = oo.order_product_id AND oo.type != 'textarea' ORDER BY op.order_product_id) AS product_option, 
		(SELECT GROUP_CONCAT(CONCAT(agd.name,'" . $this->language->get('text_separator') . "',ad.name,'" . $this->language->get('text_separator') . "',pa.text) SEPARATOR '; ') FROM `" . DB_PREFIX . "product_attribute` pa, `" . DB_PREFIX . "attribute_description` ad, `" . DB_PREFIX . "attribute` a, `" . DB_PREFIX . "attribute_group_description` agd WHERE pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.product_id = op.product_id AND pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ad.attribute_id = a.attribute_id AND a.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY agd.name, ad.name, pa.text ASC) AS product_attributes, 
		(SELECT m.name FROM `" . DB_PREFIX . "product` p, `" . DB_PREFIX . "manufacturer` m WHERE op.product_id = p.product_id AND p.manufacturer_id = m.manufacturer_id) AS product_manu, 
		(SELECT GROUP_CONCAT(cd.name SEPARATOR ', ') FROM `" . DB_PREFIX . "category_description` cd, `" . DB_PREFIX . "category` c, `" . DB_PREFIX . "product_to_category` p2c WHERE op.product_id = p2c.product_id AND p2c.category_id = c.category_id AND (c.category_id = cd.category_id OR c.parent_id = cd.category_id) AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status > 0) AS product_category, 
		(o.currency_value*op.price) AS product_price, 
		op.quantity AS product_quantity, 
		(o.currency_value*op.total) AS product_total_excl_vat, 
		(o.currency_value*op.tax*op.quantity) AS product_tax, 
		(o.currency_value*(op.total+(op.tax*op.quantity))) AS product_total_incl_vat, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'sub_total') AS order_sub_total, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'handling') AS order_handling, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'low_order_fee') AS order_low_order_fee, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'shipping') AS order_shipping, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'reward') AS order_reward, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'coupon') AS order_coupon, 
		(SELECT CONCAT(cp.name,' [',cp.code,']') FROM `" . DB_PREFIX . "coupon` cp, `" . DB_PREFIX . "coupon_history` cph WHERE cph.order_id = op.order_id AND cph.coupon_id = cp.coupon_id) AS order_coupon_code, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'tax') AS order_tax, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'credit') AS order_credit, 
		(SELECT o.currency_value*SUM(ot.value) FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = o.order_id AND ot.code = 'voucher') AS order_voucher, 
		(SELECT v.code FROM `" . DB_PREFIX . "voucher` v, `" . DB_PREFIX . "voucher_history` vh WHERE vh.order_id = op.order_id AND vh.voucher_id = v.voucher_id) AS order_voucher_code, 
		(o.currency_value*o.commission) AS order_commission, 
		(o.currency_value*o.total) AS order_value, 
		(SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE op.order_id = o.order_id AND os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status 
		
		FROM `" . DB_PREFIX . "order` o INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)" . $date . $sdate . $osi . $order_id . $store . $cur . $tax . $tclass . $geo_zone . $cgrp . $cust . $email . $tel . $ip . $pcomp . $paddr . $pcity . $pzone . $ppsc . $pcntr . $pmeth . $scomp . $saddr . $scity . $szone . $spsc . $scntr . $smeth . $cat . $manu . $sku . $prod . $mod . $opt . $atr . $loc  . $affn . $affe . $cpn . $cpc . $gvc;
		
		$sql .= " ORDER BY date_added DESC";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
}	
?>