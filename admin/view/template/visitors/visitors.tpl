<?php echo $header; ?>

<?php

include_once("../findvisitors/geoipfunctions.php");

$chartValues = "";
$javascriptMapCodeValues = "";

$db_host = DB_HOSTNAME;
$db_username = DB_USERNAME;
$db_password = DB_PASSWORD;
$db_database = DB_DATABASE;

// Set table name
$db_table_tracker = DB_PREFIX."tracker";
?>

<div id="content">
  <div class="breadcrumb">
  </div>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/visitors.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <div class="visitors">
      
        <div class="visitors-heading"><?php echo $text_visitors; ?></div>
        	<div class="visitors-content">
          



<?php
echo "<div style='height:420px;overflow-y:scroll;'>"; //main div

echo "<div class='datagrid' style=''>";

echo "<table width='100%'>";
echo "<tr><td>";
echo $text_counting_since;
$date_counting_started = DateCountingStarted($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "</td>";
echo "<td align='right'>".$date_counting_started;
echo "</td></tr>";
echo "</table>";

echo "</div>";

echo "<br>";

echo "<div class='datagrid' style='width:330px;'>";

echo "<table><thead><tr><th></th><th>$column_unique_visitors</th><th>$column_total_views</th></tr></thead>";

echo "<tr><td>";
echo $text_all_time;
$total_unique_visitors = GetTotalUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "</td>";
echo "<td align='right'>".$total_unique_visitors;
echo "</td>";
$total_views = TotalVisitsAllTime($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "<td align='right'>".$total_views;
echo "</td>";
echo "</tr>";
//
echo "<tr class='alt'><td>";
echo $text_today;
$today_unique_visitors = GetTodayUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "</td>";
echo "<td align='right'>".$today_unique_visitors;
echo "</td>";
echo "<td align='right'>";
$today_views = GetTodayTotalViews($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo $today_views;
echo "</td>";
echo "</tr>";
//
echo "<tr><td>";
echo $text_yesterday;
$yesterday_unique_visitors = GetYesterdayUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "</td>";
echo "<td align='right'>".$yesterday_unique_visitors;
echo "</td>";
echo "<td align='right'>";
$yesterday_views = GetYesterdayTotalViews($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo $yesterday_views;
echo "</td>";
echo "</tr>";
//
echo "<tr class='alt'><td>";
echo $text_last_week;
$last_week_unique_visitors = GetLastWeekUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "</td>";
echo "<td align='right'>".$last_week_unique_visitors;
echo "</td>";
echo "<td align='right'>";
$last_week_views = GetLastWeekTotalViews($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo $last_week_views;
echo "</td>";
echo "</tr>";
//
echo "<tr><td>";
echo $text_last_30_days;
$last_30days_unique_visitors = GetLast30DaysUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "</td>";
echo "<td align='right'>".$last_30days_unique_visitors;
echo "</td>";
echo "<td align='right'>";
$last_30days_views = GetLast30DaysTotalViews($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo $last_30days_views;
echo "</td>";
echo "</tr>";
//
echo "<tr class='alt'><td>";
echo $text_last_year;
$last_year_unique_visitors = GetLastYearUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo "</td>";
echo "<td align='right'>".$last_year_unique_visitors;
echo "</td>";
echo "<td align='right'>";
$last_year_views = GetLastYearTotalViews($db_table_tracker, $db_host, $db_username, $db_password, $db_database);
echo $last_year_views;
echo "</td>";
echo "</tr>";

echo "</table>";

echo "</div>";

echo "<br>";

echo "<div class='datagrid' style='width:330px;'>";
//echo "<script type='text/javascript'>alert('".$number."')</script>";

echo "<table><thead><tr><th>$column_rank</th><th>$column_country</th><th>$column_country_code</th><th>$column_unique_visitors</th></tr></thead>";
ShowCountriesUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database, $chartValues);
echo "</table>";
echo "</div>";

echo "</div>"; // end main div

ShowMapUniqueVisitors($db_table_tracker, $db_host, $db_username, $db_password, $db_database, $javascriptMapCodeValues);



function GetUniqueVisitorsByCountry($db_table_tracker, $host, $user, $pass, $db, $country_name) 
{
	$sqlQuery = "SELECT DISTINCT(ip) FROM `$db_table_tracker` WHERE country = '$country_name' AND isbot=0";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = mysqli_num_rows($result);
	
	mysqli_close($con);
	
	return $number;
}

function GetTotalVisitsByCountry($db_table_tracker, $host, $user, $pass, $db, $country_name) 
{
	$sqlQuery = "SELECT COUNT(*) AS CNT FROM `$db_table_tracker` WHERE country = '$country_name' AND isbot=0";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	return $number;
}



?>

        </div>
      </div>
      
      
      <div class="visitors-pie">
      	<div class="visitors-pie-heading"><?php echo $text_chart_pie; ?></div>
        	<div class="visitors-pie-content">
				
				<div id="piechart_3d" style="width: 360px; height: 420px;"></div>
				
			</div>
		</div>
		
		<div class="visitors-map">
	      	<div class="visitors-map-heading"><?php echo "Unique Visitors Map"; ?></div>
	        	<div class="visitors-map-content">
					
					<div id="map_canvas" style="width: 100%; height: 420px;"></div>
					
				</div>
			</div>
			
			
			
			
			
			
			
			
			
			
		<div class="visitors-reports">
	      	<div class="visitors-reports-heading"><?php echo "Reports"; ?>
	      	 
	      		<script type="text/javascript" src="view/javascript/visitor_stats_ajax.js"></script>
	      	 
	      	</div>
	        	<div class="visitors-reports-content">
	        	
		        	<form action="">
			        	<div class='datagrid' style='width:1000px;'>
				        	<table border=0>
				        		<thead>
					        		<th align='left'><input type="radio" id="select_reports" name="select_reports" value="View" checked="true" onclick="EnableReportsView(true);">View:</th>
					        		<th><input type="radio" id="custom_reports" name="select_reports" value="Custom Date" onclick="EnableReportsView(false);">Custom Date:</th>
					        		<th>Show:</th>
					        		<th>Sort By:</th>
					        		<th>Rows Per Page:</th>
					        		<th></th>
					        		<tr class='alt'>
						        		<td>
									      	 <select id="viewReportsSelect" onchange="">
									      	 	<option value="0">All Time</option>
									      	 	<option value="1">Today</option>
									      	 	<option value="2">Yesterday</option>
									      	 	<option value="3">Last Week</option>
									      	 	<option value="4">Last 30 Days</option>
									      	 	<option value="5">Last Year</option>
									      	 </select>
							      	 	</td> 
							      	 	
							      	 	<td>
									      	 <?php
									      	 	$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
									      	 	$filter_date_end = date('Y-m-d');
									      	 ?>
									      	 Date Start: <input class="date-start" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" type="text">
									      	 Date End: <input class="date-end" id="date-end" name="filter_date_start" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" type="text">
						      	 		</td>
						      	 		
						      	 		<td align='right'>
						      	 			<select id="selectShow">
						      	 				<option value="0">Unique Visitors</option>
						      	 				<option value="1">Total Views</option>
						      	 			</select>
						      	 		</td>
						      	 		
						      	 		<td align='right'>
						      	 			<select id="selectViewReportsOrder">
						      	 				<option value="0">Country (A-Z)</option>
						      	 				<option value="1">Country (Z-A)</option>
						      	 				<option value="2">IP Address - Ascending</option>
						      	 				<option value="3">IP Address - Descending</option>
						      	 				<option value="4">Date Visited - Ascending</option>
						      	 				<option value="5">Date Visited - Descending</option>
						      	 			</select>
						      	 		</td>
						      	 		
						      	 		<td align='right'>
						      	 			<select id="selectReportsRowsPerPage">
						      	 				<option value="10">10</option>
						      	 				<option value="25">25</option>
						      	 				<option value="50">50</option>
						      	 				<option value="75">75</option>
						      	 				<option value="100">100</option>
						      	 			</select>
						      	 		</td>
						      	 		
						      	 		<td align='right'>
						      	 			<a onclick="showVisitorStatsReports(1);" class="button-reports">GO</a>
						      	 		</td>
						      	 		
						      	 	</tr>
					      	 	</thead>
					      	 	
					      	 </table>
				      	 </div>
			      	 </form>
		      	 
		      	 
			      	<script type="text/javascript"><!--
					function EnableReportsView(enable_status)
					{
						document.getElementById('mapSelectView').disabled=!enable_status;
						document.getElementById('date-start').disabled=enable_status;
						document.getElementById('date-end').disabled=enable_status;
					}
					//--></script>
					
			      	<script type="text/javascript"><!--
					$(document).ready(function() {
						$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
						
						$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
					});
					//--></script> 
					
					<div id="reports_pagination_top" style="text-align: left;"></div>
					<div id="visitor_stats_reports"></div>
					
				</div>
			</div>
      	</div>
			

			
      	</div>
      	
      	
		
      </div>
      
    </div>
  </div>
</div>


<?php
echo "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";
echo "<script type='text/javascript'>";
echo "  google.load('visualization', '1', {packages:['corechart']}); ";
echo "  google.setOnLoadCallback(drawChart); ";
echo "  function drawChart() { ";
echo "      var data = google.visualization.arrayToDataTable([";
echo $chartValues;
echo "     ]);";
echo "    var options = {";
echo "      title: '',";
echo "      is3D: true,";
echo "      chartArea:{left:20,top:0,width:'100%',height:'100%'}";
echo "    };";
echo "    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));";
echo "    chart.draw(data, options);";
echo "  }";
echo "</script>";
?>


<?php
echo "<script src='http://maps.google.com/maps/api/js?sensor=false' type='text/javascript'></script>";
echo "<script type='text/javascript'>";
echo "	function initialize() {";
echo "		var map_options = {";
echo "			center: new google.maps.LatLng('31', '26'),";
echo "			zoom: 1,";
echo "			mapTypeId: google.maps.MapTypeId.ROADMAP";
echo "		};";
							
echo "		var google_map = new google.maps.Map(document.getElementById('map_canvas'), map_options);";
							
echo "		var info_window = new google.maps.InfoWindow({";
echo "			content: 'loading'";
echo "		});";

echo $javascriptMapCodeValues;
//echo " 		google.maps.event.addListener(m, 'click', function() {";
//echo "			info_window.setContent(this.html);";
//echo "			info_window.open(google_map, this);";
//echo "		});";

echo "  }";
echo " initialize();";
echo "</script>";
?>



<?php

function ShowCountriesUniqueVisitors($table, $host, $user, $pass, $db, &$chartValues) {

	$arGetCountries = array();
	$arGetCountries = GetVisitorsPerCountry($table, $host, $user, $pass, $db);

	$arrlength = count($arGetCountries);
	
	$chartValues = "[\'Task\', \'Unique views per country\'],";
	
	for($i=0; $i<$arrlength; $i++) {
		
		$arData = array();
		
		$one_element = $arGetCountries[$i]; 
		$arData = explode("-", $one_element); // split value country name from country code
		$arrlength2 = count($arData);
		
		$country_code = "";
		$country_unique_visitors = 0;
	  	for($j=0; $j<$arrlength2; $j++) {

			if ($j == 0) {

				if ($i % 2) {
					echo "<tr class='alt'>";
				}
				else {
					echo "<tr>";
				}
				
				echo "<td align=right>";
				echo $i+1;
				echo "</td>";
				
				echo "<td>";
				$country_code = $arData[1];
		  		if ($country_code !== "") {
					GetFlag(strtolower($country_code));
				}

				$country_name = $arData[0];
				$country_unique_visitors = GetUniqueVisitorsByCoutryName($table, $host, $user, $pass, $db, $country_name);
				
				$tempVal = "[\'$country_name\', $country_unique_visitors],";
				$chartValues = $chartValues.$tempVal;
				//echo "<script type='text/javascript'>alert('$chartValues')</script>";
				
				echo $country_name;
				echo "</td>";
				
				echo "<td align=center>";
				echo $country_code;
				echo "</td>";
				
				echo "<td align=right>";
				echo $country_unique_visitors;
				echo "</td>";
				
				echo "</tr>";
			}
			
		}
		
	}
	
	$chartValues = str_replace("\\", "", $chartValues);
	
}

function GetFlag($country_code) {
	echo "<img src='../findvisitors/images/flags/$country_code.png' /> ";
}

function GetUniqueVisitorsByCoutryName($table, $host, $user, $pass, $db, $country_name) {
	
	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0 AND country = '".$country_name."' GROUP BY ip";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = mysqli_num_rows($result);
	
	/*$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	if ($number == null) {
		$number = 0;
	}
	 * */
	
	mysqli_close($con);
	
	return $number;
}

function DateCountingStarted($table, $host, $user, $pass, $db) {
	
	$sqlQuery = "SELECT date_visited FROM ".$table." ORDER BY id ASC LIMIT 1";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	//$number = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	
	$date_started = $row['date_visited'];
	
	mysqli_close($con);
	
	return $date_started;
}

// Count unique visitors
function GetTotalUniqueVisitors($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT COUNT(*) FROM ".$table." WHERE isbot = 0 GROUP by ip"; //"SELECT DISTINCT ip FROM ".$table;
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = mysqli_num_rows($result);
	
	mysqli_close($con);
	
	return $number;
}

function TotalVisitsAllTime($table, $host, $user, $pass, $db) {
	
	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	return $number;
}

// Count today unique visitors
function GetTodayUniqueVisitors($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT * FROM ".$table." WHERE isbot = 0 AND date_visited = CURDATE() GROUP BY ip";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = 0;
	while($row = mysqli_fetch_array($result)) {
		$number = $number +1;
	}
	
	mysqli_close($con);
	
	return $number;
}

// Count today total views
function GetTodayTotalViews($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0 AND date_visited = CURDATE()";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	return $number;
}

// Count yesterday unique visitors
function GetYesterdayUniqueVisitors($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT * FROM ".$table." WHERE isbot = 0 AND (DATE_SUB(CURDATE(),INTERVAL 1 DAY) = date_visited) GROUP BY ip";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = 0;
	while($row = mysqli_fetch_array($result)) {
		$number = $number +1;
	}
	
	mysqli_close($con);
	
	return $number;
}

// Count yesterday total views
function GetYesterdayTotalViews($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0 AND (DATE_SUB(CURDATE(),INTERVAL 1 DAY) = date_visited)";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	return $number;
}

// Count last week unique visitors
function GetLastWeekUniqueVisitors($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT * FROM ".$table." WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()) GROUP BY ip";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = 0;
	while($row = mysqli_fetch_array($result)) {
		$number = $number +1;
	}
	
	mysqli_close($con);
	
	return $number;
}

// Count last week total views
function GetLastWeekTotalViews($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE())";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	return $number;
}

// Count last 30 days unique visitors
function GetLast30DaysUniqueVisitors($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT * FROM ".$table." WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()) GROUP BY ip";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = 0;
	while($row = mysqli_fetch_array($result)) {
		$number = $number +1;
	}
	
	mysqli_close($con);
	
	return $number;
}

// Count last 30 days total views
function GetLast30DaysTotalViews($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE())";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	return $number;
}

// Count last year unique visitors
function GetLastYearUniqueVisitors($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT * FROM ".$table." WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 YEAR) AND CURDATE()) GROUP BY ip";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$number = 0;
	while($row = mysqli_fetch_array($result)) {
		$number = $number +1;
	}
	
	mysqli_close($con);
	
	return $number;
}

// Count last year total views
function GetLastYearTotalViews($table, $host, $user, $pass, $db) {

	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 YEAR) AND CURDATE())";
	
	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$row = mysqli_fetch_array($result);
	
	$number = $row['CNT'];
	
	mysqli_close($con);
	
	return $number;
}

function GetVisitorsPerCountry($table, $host, $user, $pass, $db) {
		
	//$sqlQuery = "SELECT DISTINCT ip FROM ".$table." WHERE isbot = 0 ORDER BY id DESC";
	// I'm using the CNT to order it DESC by the higher return value and by id DESC to order it with the latest id value.
	// So i can show the country with the most unique visits in ascending order.
	$sqlQuery = "SELECT *, COUNT(country) AS CNT FROM ".$table." WHERE isbot = 0 GROUP BY country ORDER BY CNT DESC, id DESC";
	
	$con=mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	$arCountries = array();
	
	while($row = mysqli_fetch_array($result)) {
		$returned_ip = $row['ip'];
		
		$record = GetCountryByIP($returned_ip);
		if (!is_null($record)) {
			$country = $record->country_name;
			$country_code = $record->country_code;
			$city = $record->city;
		
			$value = $country."-".$country_code;
		 
			if (trim($country) !== "") {
				$exists = in_array($value, $arCountries);
				if ($exists !== true) {
					
					$arCountries[] = $value;
						
				}
			}
		}
		
	}
	
	mysqli_close($con);

	return $arCountries;	
}

// Number of times a particular browser has been used to visit the site
function GetBrowserViewCount($table, $host, $user, $pass, $db) {
	
	$sqlQuery = "SELECT *, count(*) FROM ".$table." GROUP by browser";
	
	$con=mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
	  echo "Browser Name = " .$row['browser']. " Used : " .$row ['count(*)'];
	  echo "<br>";
	}
	
	mysqli_close($con);
}

// Number of times user visited
function HowManyTimesUserVisited($table, $host, $user, $pass, $db, $ip) {
	
	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE ip = ".$ip;
	
	$con=mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
	  echo $ip." Number of times visited : " .$row['CNT'];
	  echo "<br>";
	}
	
	mysqli_close($con);
}

// Pages user visited
function PagesUserVisited($table, $host, $user, $pass, $db, $ip) {
	
	$sqlQuery = "SELECT *,count(*) FROM ".$table." WHERE ip = ".$ip." GROUP BY page";
	
	$con=mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
	  echo " Page : ".$row['page_viewed']." - Viewd : ".$row['CNT'];
	  echo "<br>";
	}
	
	mysqli_close($con);
}

// Number of visits by this user in last 30 days
function PagesUserVisitedTheLast30Days($table, $host, $user, $pass, $db, $ip) {
	
	$sqlQuery = "SELECT *, COUNT(*) AS CNT FROM ".$table." WHERE ip = ’".$ip."′ AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) GROUP BY page_viewed";
	
	$con=mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
	  echo "Page : ".$row['page_viewed']." Viewed : ".$row['CNT']." times";
	  echo "<br>";
	}
	
	mysqli_close($con);
}


// How many times a particular page has been visited
function TimesPageHasBeenVisited($table, $host, $user, $pass, $db, $pagename) {
	
	$sqlQuery = "SELECT COUNT(*) AS CNT FROM ".$table." WHERE page = '".$pagename."'";
	
	$con=mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
	  echo "Page : ".$pagename." Viewed : ".$row['CNT']." times";
	  echo "<br>";
	}
	
	mysqli_close($con);
}

// Who visits a certain page
function WhoVisitsCertainPage($table, $host, $user, $pass, $db, $pagename) {
	
	$sqlQuery = "SELECT *, COUNT(*) AS CNT FROM ".$table." WHERE page = '".$pagename."' GROUP BY ip";
	
	$con=mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
	  echo "Visited ".$row['CNT']." times. By: ".$row['ip'];
	  echo "<br>";
	}
	
	mysqli_close($con);
}

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
function GetDistinctIPByCountryName($table, $host, $user, $pass, $db, $countryName, &$longitude, &$latitude, &$ip) {

	$sqlQuery = "SELECT ip, country, longitude, latitude FROM ".$table." WHERE (country = '".$countryName."' AND isbot =0) GROUP BY ip ORDER BY longitude, latitude ASC";

	$con = mysqli_connect($host, $user, $pass, $db);
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$result = mysqli_query($con, $sqlQuery);
	
	while($row = mysqli_fetch_array($result)) {
		$longitude = $row['longitude'];
		$latitude = $row['latitude'];
		$ip = $row['ip'];
	}
	
	mysqli_close($con);
	
	return true;
}

function ShowMapUniqueVisitors($table, $host, $user, $pass, $db, &$javascriptMapCodeValues) {
	
	$arGetCountries = array();
	$arGetCountries = GetVisitorsPerCountry($table, $host, $user, $pass, $db);

	$arrlength = count($arGetCountries);
	
	// create javascript vars and code
	$javascriptArrayVars = "";
	$javascriptFillArrayVars = "";
	$javascriptAddMarkerCode = "";

	for($i=0; $i<$arrlength; $i++) {
		
		$arData = array();
		
		$one_element = $arGetCountries[$i]; 
		$arData = explode("-", $one_element); // split value country name from country code
		$countryName = $arData[0];
		$countryCode = $arData[1];
		$countryCode = strtolower($countryCode);
		
		$longitude = 0;
		$latitude = 0;
		$ip = "";
		$javascriptArrayVars = "var t = [];
								var x = [];
								var y = [];
								var h = [];";
		
		
		if (GetDistinctIPByCountryName($table, $host, $user, $pass, $db, $countryName, $longitude, $latitude, $ip) == true) {
			
			$javascriptFillArrayVars = $javascriptFillArrayVars." t.push('".$countryName."'); x.push('".$longitude."'); y.push('".$latitude."'); ";
			$javascriptFillArrayVars = $javascriptFillArrayVars." h.push('<div class=\'map-html-marker\' style=\'width:100px;\'><img src=\'../findvisitors/images/flags/$countryCode.png\' /> ".$countryName."</div>'); ";
			
		}
		
	
	}
	

	if (($javascriptArrayVars !== "") AND ($javascriptFillArrayVars !== "")) {
		
		$javascriptAddMarkerCode = " 	var i = 0;
										for ( item in t ) {
										var m = new google.maps.Marker({
											map:		google_map,
											animation:	google.maps.Animation.DROP,
											title:		t[i],
											position:	new google.maps.LatLng(y[i],x[i]),
											html:		h[i],
											icon:		'../findvisitors/images/marker.png'
										});
		
										google.maps.event.addListener(m, 'click', function() {
											info_window.setContent(this.html);
											info_window.open(google_map, this);
										});
										i++;
									} "; 
	
	} 
	
	$javascriptMapCodeValues = $javascriptArrayVars.$javascriptFillArrayVars.$javascriptAddMarkerCode;

}



?>


<?php echo $footer; ?>
