<?php

function GetReportsSql($useLimit, $view, $show, $order, $date_start, $date_end, $table, $startFromRow, $resultsPerPage)
{
	$sqlQuery = "";
	
	if ($view !== "")
	{
		switch($view)
		{
			case 0: // all time
				$sqlQuery = "SELECT id, ip, country, CONCAT(date_visited, ' ', time_visited) AS date_time_visited, browser, page_viewed, request_uri FROM ".$table.
					" WHERE isbot = 0 ";
				
				if ($show == 0)
				{
					$sqlQuery .= " GROUP BY ip ";
				}
				
				$sqlQuery .= " ORDER BY ".$order;
				
				break;
			case 1: // today
				$sqlQuery = "SELECT id, ip, country, CONCAT(date_visited, ' ', time_visited) AS date_time_visited, browser, page_viewed, request_uri FROM ".$table.
					" WHERE isbot = 0 AND date_visited = CURDATE() ";
					
				if ($show == 0)
				{
					$sqlQuery .= " GROUP BY ip ";
				}
				
				$sqlQuery .= " ORDER BY ".$order;
				
				break;
			case 2: // yesterday
				$sqlQuery = "SELECT id, ip, country, CONCAT(date_visited, ' ', time_visited) AS date_time_visited, browser, page_viewed, request_uri FROM ".$table.
					" WHERE isbot = 0 AND (DATE_SUB(CURDATE(),INTERVAL 1 DAY) = date_visited) ";
				
				if ($show == 0)
				{
					$sqlQuery .= " GROUP BY ip ";
				}
				
				$sqlQuery .= " ORDER BY ".$order;
				
				break;
			case 3: // week
				$sqlQuery = "SELECT id, ip, country, CONCAT(date_visited, ' ', time_visited) AS date_time_visited, browser, page_viewed, request_uri FROM ".$table.
					" WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()) ";
				
				if ($show == 0)
				{
					$sqlQuery .= " GROUP BY ip ";
				}
				
				$sqlQuery .= " ORDER BY ".$order;
				
				break;
			case 4: // last 30 days
				$sqlQuery = "SELECT id, ip, country, CONCAT(date_visited, ' ', time_visited) AS date_time_visited, browser, page_viewed, request_uri FROM ".$table.
					" WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()) ";
				
				if ($show == 0)
				{
					$sqlQuery .= " GROUP BY ip ";
				}
				
				$sqlQuery .= " ORDER BY ".$order;
				
				break;
			case 5: // last year
				$sqlQuery = "SELECT id, ip, country, CONCAT(date_visited, ' ', time_visited) AS date_time_visited, browser, page_viewed, request_uri FROM ".$table.
					" WHERE isbot = 0 AND (date_visited BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 YEAR) AND CURDATE()) ";
				
				if ($show == 0)
				{
					$sqlQuery .= " GROUP BY ip ";
				}
				
				$sqlQuery .= " ORDER BY ".$order;
				
				break;
		}
		
	}
	else 
	{
		$sqlQuery = "SELECT id, ip, country, CONCAT(date_visited, ' ', time_visited) AS date_time_visited, browser, page_viewed, request_uri FROM ".$table.
					" WHERE isbot = 0 AND (date_visited BETWEEN '".$date_start."' AND '".$date_end."') ";
					
		if ($show == 0)
		{
			$sqlQuery .= " GROUP BY ip ";
		}
		
		$sqlQuery .= " ORDER BY ".$order;
	}

	if ($useLimit == true)
	{
		$sqlQueryLimit = " LIMIT ".$startFromRow.",".$resultsPerPage.";";
		$sqlQuery .= $sqlQueryLimit; 	
	}

	return $sqlQuery;
}	

?>
