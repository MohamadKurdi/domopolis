<?php 


function reformatDate($date){
	return date('Y-m-d', strtotime($date['year'] . '-' . $date['month'] . '-' . $date['day']));
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

function formatDateInterval($mysqlDate){
	$date 	= new DateTime($mysqlDate);
	$now 	= new DateTime(); 

	$interval = $now->diff($date);
	$days = $interval->days;
	$years = floor($days / 365);
	$days -= $years * 365;
	$months = floor($days / 30); 
	$days -= $months * 30;

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


function secToHR($seconds) {
	$hours = floor($seconds / 3600);
	$minutes = floor(($seconds / 60) % 60);
	$seconds = $seconds % 60;
	return $hours > 0 ? "$hours h., $minutes m." : ($minutes > 0 ? "$minutes m., $seconds s. " : "$seconds s.");
}