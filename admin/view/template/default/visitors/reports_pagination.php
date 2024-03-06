<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />

<script type="text/javascript" src="view/javascript/visitor_stats_ajax.js"></script>

</head>
<body>

<?php

include_once ("db_config.php");
include_once ("common_functions.php");

$host = $db_visitors_host;
$user = $db_visitors_username;
$pass = $db_visitors_password;
$db = $db_visitors_database;

// Set table name
$table = $db_visitors_table_prefix."tracker";	

$view = "";
$order = "";
$show = 0;
$date_start = "";
$date_end = "";
$startFromRow = 0;
$resultsPerPage = 10;
$cur_page = 0;
$total_pages = 0;
$pagination_links = "";

$first_page = 0;
$previous_page = 0;
$next_page = 0;
$last_page = 0;

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

function CreatePagination()
{
	global $pagination_links, $view, $show, $order, $date_start, $date_end, $table, $startFromRow, $resultsPerPage;
	
	$sqlQuery = "";
	$sqlQuery = GetReportsSql(false, $view, $show, $order, $date_start, $date_end, $table, $startFromRow, $resultsPerPage);
	
	$total_rows = GetTotalRows($sqlQuery);
	
	if ($total_rows > 0)
	{
		echo "$total_rows Records found.&nbsp;&nbsp;&nbsp;<br/>";

		$pagination_links = "<div>";
		 
		$total_pages = ceil($total_rows / $resultsPerPage);
		
		global $cur_page, $previous_page, $next_page;
		
		echo "<a href='#' onclick='showVisitorStatsReports(1); return false;'>< First Page</a>";
		echo "&nbsp;&nbsp;&nbsp;";
		
		$previous_page = $cur_page - 1;
		if ($previous_page <= 0) {
			$previous_page = 1;
		}
		echo "<a href='#' onclick='showVisitorStatsReports($previous_page); return false;'><< Previous Page</a>";
		echo "&nbsp;&nbsp;&nbsp;";
		
		$next_page = $cur_page + 1;
		if ($next_page >= $total_pages) {
			$next_page = $total_pages;
		}
		echo "<a href='#' onclick='showVisitorStatsReports($next_page); return false;'>Next Page >></a>";
		echo "&nbsp;&nbsp;&nbsp;";
		
		echo "<a href='#' onclick='showVisitorStatsReports($total_pages); return false;'>Last Page ></a>";
		echo "&nbsp;&nbsp;&nbsp;";

		echo "Page:&nbsp;";
		echo "<select id='select_pages' onchange='showVisitorStatsReports(this.value); return false;'>";
		for($i=1; $i<=$total_pages; $i++)
		{
			if ($i == $cur_page) {
				echo "<option value='$i' selected>$i</option>";				
			}
			else {
				echo "<option value='$i'>$i</option>";	
			}
		}
		echo "</select>";
		echo "&nbsp;/&nbsp;$total_pages";
		$pagination_links .= "</div>";
		
		echo $pagination_links;
	}
	else {
		echo "<div><font color='red'><b>No records found.</b></font></div>";	
	}
	
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


?>

<div id="pagination"">
	
	<?php CreatePagination(); ?>
	
</div>

</body>
</html>
