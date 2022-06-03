function showVisitorStatsReports(cur_page)
{
	showReportsPagination(cur_page);

	str = getVisitorStatsReportsSelectedValues();
	str = str + "&page=" + cur_page;
	
	var xmlhttp;    
	if (str=="")
	{
	  	document.getElementById("visitor_stats_reports").innerHTML="";
	  	return;
	}
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
	  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    	document.getElementById("visitor_stats_reports").innerHTML=xmlhttp.responseText;
	    }
	}
	xmlhttp.open("GET","view/template/visitors/visitor_stats_reports.php?"+str,true);
	xmlhttp.send();
}

function showReportsPagination(cur_page)
{
	str = getVisitorStatsReportsSelectedValues();
	str = str + "&page=" + cur_page;
	 
	var xmlhttp;    
	if (str=="")
	{
	  	document.getElementById("reports_pagination_top").innerHTML="";
	  	return;
	}
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
	  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    	document.getElementById("reports_pagination_top").innerHTML=xmlhttp.responseText;
	    }
	}
	xmlhttp.open("GET","view/template/visitors/reports_pagination.php?"+str,true);
	xmlhttp.send();
}


function getVisitorStatsReportsSelectedValues()
{
	var retVal = "";
	var selectedValue = "";
	var sortOrder = "";
	var rowsPerPage = "";
	var show = "";
	
	if (document.getElementById("select_reports").checked == true)
	{
		selectedValue = $('#viewReportsSelect').val();
		if (selectedValue == 0)
		{
			retVal = "view=0";
		}
		else if (selectedValue == 1)
		{
			retVal = "view=1";
		}
		else if (selectedValue == 2)
		{
			retVal = "view=2";
		}
		else if (selectedValue == 3)
		{
			retVal = "view=3";
		}
		else if (selectedValue == 4)
		{
			retVal = "view=4";
		}
		else if (selectedValue == 5)
		{
			retVal = "view=5";
		}
	}
	else if (document.getElementById("custom_reports").checked == true)
	{
		var dtStart = document.getElementById('date-start').value;
		var dtEnd = document.getElementById('date-end').value;
		
		retVal = "date_start="+dtStart+"&date_end="+dtEnd;
	}
	
	show = $('#selectShow').val();
	retVal = retVal + "&show=" + show;
	
	sortOrder = $('#selectViewReportsOrder').val();
	if (sortOrder == 0)
	{
		retVal = retVal + "&order=country=asc";
	}
	else if (sortOrder == 1)
	{
		retVal = retVal + "&order=country=desc";
	}
	else if (sortOrder == 2)
	{
		retVal = retVal + "&order=ip=asc";
	}
	else if (sortOrder == 3)
	{
		retVal = retVal + "&order=ip=desc";
	}
	else if (sortOrder == 4)
	{
		retVal = retVal + "&order=date_time_visited=asc";
	}
	else if (sortOrder == 5)
	{
		retVal = retVal + "&order=date_time_visited=desc";
	}
	
	rowsPerPage = $('#selectReportsRowsPerPage').val();
	retVal = retVal + "&rowsperpage=" + rowsPerPage;
	
	return retVal;
}









