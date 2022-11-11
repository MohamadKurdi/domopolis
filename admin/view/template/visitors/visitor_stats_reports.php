<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />

</head>
<body>

<?php

include_once ("db_config.php");
include_once ("common_functions.php");
include_once ("phpexcelreader/excel_reader.php");     // include the class
include_once ("Browser.php");

$host = $db_visitors_host;
$user = $db_visitors_username;
$pass = $db_visitors_password;
$db = $db_visitors_database;

// Set table name
$table = $db_visitors_table_prefix."tracker";

$number_of_rows = 0;

$view = "";
$show = 0;
$order = "";
$date_start = "";
$date_end = "";
$startFromRow = 0;
$resultsPerPage = 10;
$cur_page = 0;

$previous_value = "";

$qs = $_SERVER['QUERY_STRING'];
if (strpos($qs, "view") !== false) 
{
	$view = $_GET['view'];
	$show = $_GET['show'];
	$order = $_GET['order'];
	$order = str_replace("=", " ", $order);
	$resultsPerPage = $_GET['rowsperpage'];
	$cur_page = $_GET['page'];
}
elseif (strpos($qs, "date_start") !== false) 
{
	$date_start = $_GET['date_start'];
	$date_end = $_GET['date_end'];
	$show = $_GET['show'];
	$order = $_GET['order'];
	$order = str_replace("=", " ", $order);
	$resultsPerPage = $_GET['rowsperpage'];
	$cur_page = $_GET['page'];
}

function GetReports()
{
	global $cur_page, $view, $show, $order, $date_start, $date_end, $table, $startFromRow, $resultsPerPage;
	
	$row_number = 0;
	if ($cur_page == 1) {
		$startFromRow = 0;
		$row_number = 0;
	}
	else {
		$startFromRow = ($cur_page * $resultsPerPage) - $resultsPerPage;
		$row_number = $startFromRow;	
	}
	$sqlQuery = "";
	$sqlQuery = GetReportsSql(true, $view, $show, $order, $date_start, $date_end, $table, $startFromRow, $resultsPerPage);
		
	global $host, $user, $pass, $db;
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
		$row_number = $row_number + 1;
		
		if ($row_number % 2) {
			echo "<tr>";
		}
		else {
			echo "<tr class='alt'>";
		}

		echo "<td width='50'>";
		echo $row_number;
		echo "</td>";
		
		echo "<td width='150'>";
		$country = $row["country"];
		$country_code = GetCountryCode($country);
		$country_code = strtolower($country_code);
		$flag = "<img src='../findvisitors/images/flags/$country_code.png' /> ";
		echo $flag;
		echo $country;
		echo "</td>";

		echo "<td width='130'>";
		$date_time_visited = $row["date_time_visited"];
		echo $date_time_visited;
		echo "</td>";
				
		echo "<td width='110'>";
		$ip = $row["ip"];
		echo $ip;
		echo "</td>";
		
		echo "<td width='100'>";
		$total_visits = GetTotalVisitsByIp($table, $ip);
		echo $total_visits;
		echo "</td>";
		
		echo "<td width='100'>";
		$db_browser = $row["browser"];
		$browser = new Browser($db_browser);
		$browserName = $browser->getBrowser();
		$browserFlag = "<img src='../findvisitors/images/browsers/$browserName.png' width='16px' /> ";
		echo $browserFlag;
		echo $browserName;
		echo "</td>";

		echo "<td width='100'>";
		echo $browser->getPlatform();
		echo "</td>";
		
		echo "</tr>";
	}
	
	mysqli_close($con);
}	

function GetTotalRows($sqlQuery)
{
	global $host, $user, $pass, $db;
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$total_rows = mysqli_num_rows($result);
		
	mysqli_close($con);
	
	return $total_rows;
}

function GetCountryCode($country)
{
	$country_code = "";
	
	// creates an object instance of the class, and read the excel file data
	$excel = new PhpExcelReader;
	$excel->read('phpexcelreader/iso3166.xls');
	
	$sheet = $excel->sheets[0];  
	
	$x = 1;	
	while($x <= $sheet['numRows']) {
		
		$country_array_value = $sheet['cells'][$x][2];
		if ($country == $country_array_value)
  		{
  			$country_code = $sheet['cells'][$x][1];
			return $country_code;
  		}
 
	  	$x++;
	}
	
	return $country_code;
}

function GetTotalVisitsByIp($table, $ip)
{
	global $previous_value;
	if ($previous_value == $ip)	{
		return "";
	}
	
	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0 AND ip = '".$ip."'";
	
	global $host, $user, $pass, $db;
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	$previous_value = $ip;	
	
	return $number;
}

?>

<div class='datagrid' style='width: 1000px;'>
<table>
	<thead>
		<th></th>
		<th>Country</th>
		<th>Date Visited</th>
		<th>IP Address</th>
		<th>Views By IP</th>
		<th>Browser</th>
		<th>OS/Device</th>
	</thead>
	
	<?php GetReports(); ?>
	
	
</table>
</div>

</body>
</html>
