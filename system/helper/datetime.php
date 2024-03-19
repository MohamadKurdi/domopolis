<?php

function date_added_sort($a, $b) {
    return strtotime($a['date_added']) - strtotime($b['date_added']);
}

function reformatDate($date){
	return date('Y-m-d', strtotime($date['year'] . '-' . $date['month'] . '-' . $date['day']));
}

function checkDateIsGood($date){
	return ($date != '0000-00-00 00:00:00' && $date != '0000-00-00');
}

function guessYear($date){
	if ($date['month'] < date('n')){
		return date('Y') + 1;
	} else {
		return date('Y');
	}
}

function getUkrainianWeekDayDeclenced($weekday) {

	$array = [
		1 => 'понеділок',
		2 => 'вівторок',
		3 => 'середу',
		4 => 'четвер',
		5 => "п'ятницю",
		6 => 'суботу',
		7 => 'неділю'
	];

	return $array[$weekday];		
}

function checkBirthdayOrDate($birthday, $format = 'd.m.Y') {			
	if ($birthday == '0000-00-00' || $birthday == '1970-01-01'){
		return false;
	}

	if (!strtotime($birthday)){
		return false;
	}

	if (date('Y-m-d', strtotime($birthday)) <= date('Y-m-d', strtotime('1900-01-01'))){
		return false;
	}

	return date($format, strtotime($birthday));
}

function formatDateInterval($mysqlDate){
	$date 	= new DateTime($mysqlDate);
	$now 	= new DateTime(); 

	$interval 	= $now->diff($date);
	$days 		= $interval->days;
	$years 		= floor($days / 365);
	$days 		-= $years * 365;
	$months 	= floor($days / 30); 
	$days 		-= $months * 30;

	$result = '';
	if ($years){
		$result = $years . ' лет';
	}

	if ($months){
		if ($result){
			$result .= ', ';
		}

		$result .= $months . ' мес.';
	}

	if ($days){
		if ($result){
			$result .= ', ';
		}
		
		$result .= $days . ' дн.';
	}

	return $result;
}

function days_diff($target){	
	return (int)date_diff(date_create(date('Y-m-d')), date_create($target))->format('%a');
}

function seconds_diff($target){
	$d1 = new DateTime(date('Y-m-d H:i:s'));
	$d2 = new DateTime($target);

	$diff = $d1->getTimestamp() - $d2->getTimestamp(); 

	return (int)$diff;
}

function dateDifference($date_1, $date_2, $differenceFormat = '%a'){
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);

	$interval = date_diff($datetime1, $datetime2);

	return $interval->format($differenceFormat);
}

function dateDiff($date1, $date2){
	$d = (strtotime($date2)-strtotime($date1))/(60*60*24);
	if (!round($d)) {
		$d = 1;
	} else {
		$d = round($d);
	}

	return $d;
}

function secToHR($seconds) {
	$hours 		= floor($seconds / 3600);
	$minutes 	= floor(($seconds / 60) % 60);
	$seconds 	= $seconds % 60;
	return $hours > 0 ? "$hours h., $minutes m." : ($minutes > 0 ? "$minutes m., $seconds s. " : "$seconds s.");
}

function secToHRCyr($seconds) {
	$hours 		= floor($seconds / 3600);
	$minutes 	= floor(($seconds / 60) % 60);
	$seconds 	= $seconds % 60;
	return $hours > 0 ? "$hours ч., $minutes мин." : ($minutes > 0 ? "$minutes мин., $seconds сек. " : "$seconds сек.");
}