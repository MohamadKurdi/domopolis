<?php


function buildRelativeDate($string){
	$now = date_create();

	if ($string[0] == '+'){
		$invert = false;
	} elseif ($string[0] == '-') {
		$invert = true;
	} else {
		return date();
	}

	$string = substr($string, 1);

	$_srd_ru = array('дней', 'дня', 'дня', 'день', 'неделя', 'недели', 'месяц', 'месяца', 'месяцев', 'полгода', 'год', 'года');
	$_srd_php = array('days', 'days', 'days', 'days', 'weeks', 'weeks', 'months', 'months', 'months', '6 month', 'years', 'years');

	$diff = date_interval_create_from_date_string(str_replace($_srd_ru, $_srd_php, $string));

	if ($invert){
		return date_format(date_sub($now, $diff), 'Y-m-d');
	} else {
		return date_format(date_add($now, $diff), 'Y-m-d');
	}			
}

function buildDateMagicSQL($date_from, $date_to, $param, $remove_year = false){
	$sql = "";

	$date_from = trim($date_from);
	$date_to = trim($date_to);

	if (!$date_to){
		$date_to = date('Y-m-d');
	}

	if (!$date_from){
		$date_from = date('Y-m-d');
	}

	if ($date_from[0] == '+' || $date_from[0] == '-'){
		$date_from = buildRelativeDate($date_from);
	}

	if ($date_to[0] == '+' || $date_to[0] == '-'){
		$date_to = buildRelativeDate($date_to);
	}


	if ($remove_year){				
		$date_from = date('m-d', strtotime($date_from));
		$date_to = date('m-d', strtotime($date_to));

		if (!empty($date_from)) {
			$sql .= " DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-".$date_from."'))";
			$sql .= " AND DATE($param) > '0000-00-00'";

			if (empty($date_to)){
				$sql .= " AND DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
			}
		}


		if (!empty($date_to)) {
			if ($sql){
				$and = " AND ";
			}
			$sql .= " $and DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-".$date_to."'))";
			$sql .= " AND DATE($param) > '0000-00-00'";

			if (empty($date_from)){
				$sql .= " AND DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
			}
		}								
	} else {

		if (!empty($date_from)) {
			$sql .= " DATE($param) >= '$date_from'";
		}

		if ($sql){
			$and = " AND ";
		}

		if (!empty($date_to)) {
			$sql .= " $and DATE($param) <= '$date_to'";

			if (empty($date_from)){
				$sql .= " AND DATE($param) > '0000-00-00'";
			}
		}				
	}

	return $sql;
}