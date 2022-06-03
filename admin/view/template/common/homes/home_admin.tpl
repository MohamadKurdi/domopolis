<style>
	table tr td {vertical-align:top;}
</style>
<table style="width:100%">
	<tr>
		<td style="width:40%;">
			<table style="width:100%" cellspacing="0">
				<div class="prettystats delayed-load" data-route='common/home/loadPrettyStats'>
					
				</div>
				<div class="homecharts delayed-load" data-route='common/home/loadChartStats'>
					
				</div>
			</table>
			
		</td>
		
		<td class="table-admin" style="width:60%;">
			
			<div id="order_filters" class="filters delayed-load" data-route='common/home/loadOrderStats'>
			</div>
			<div style="clear:both; height:10px;">
			</div>
			<div class="latest delayed-load" data-route='common/home/getLastTwentyOrders'>
				
			</div>
		</td>
	</tr>
</table>

