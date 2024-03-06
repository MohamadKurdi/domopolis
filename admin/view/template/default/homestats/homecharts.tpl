<div>
	<div>
		<div style="width:100%; height:100%;">											
			<div class="dashboard-heading">Заказы за неделю</div>
			<div class="dashboard-content">
				<div id="report_week" style="width: 100%; height: 350px; margin: auto;"></div>
			</div>
		</div>
	</div>
</div>
<div class="clr"></div>

<div>
	<div>
		<div style="width:100%; height:100%;">											
			<div class="dashboard-heading">Лояльность покупателей (повторные заказы за неделю)</div>
			<div class="dashboard-content">
				<div id="loyality_week" style="width: 100%; height: 350px; margin: auto;"></div>
			</div>
		</div>
	</div>
</div>
<div class="clr"></div>

<div>
	<div>
		<div style="width:100%; height:100%;">											
			<div class="dashboard-heading">Отмены за неделю</div>
			<div class="dashboard-content">
				<div id="report_week_cancel" style="width: 100%; height: 350px; margin: auto;"></div>
			</div>
		</div>
	</div>
</div>
<div class="clr"></div>
<div>
	<div>
		<div style="width:100%; height:100%;">											
			<div class="dashboard-heading">Количество заказов за год</div>
			<div class="dashboard-content">
				<div id="report" style="width: 100%; height: 350px; margin: auto;"></div>
			</div>
		</div>
	</div>	
</div>
<!--[if IE]>
	<script type="text/javascript" src="view/javascript/jquery/flot2/excanvas.js"></script>
<![endif]--> 
<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.resize.min.js"></script>
<script type="text/javascript">
	function getSalesChart(range, div, url) {
		if (div == 'report_week_cancel'){
			var color = '#f91c02';
			} else if (div == 'loyality_week') { 
			var color = '#ff7f00';
			} else {
			var color = '#1065D2';
		}
		$.ajax({
			type: 'get',
			url: url + range,
			dataType: 'json',
			beforeSend: function(){
				$('#'+div).html("<div class='lds-hourglass'></div>");
			},
			async: true,
			success: function(json) {
				var option = {	
					shadowSize: 0,
					colors: ['#4ea24e', color],
					bars: { 
						show: true,
						fill: true,
						lineWidth: 1
					},
					grid: {
						backgroundColor: '#FFFFFF',
						hoverable: true
					},
					points: {
						show: false
					},
					xaxis: {
						show: true,
						ticks: json['xaxis']
					}
				}
				
				if (typeof(json.cancelled_order) != 'undefined'){
					$.plot($('#'+div), [json.order, json.cancelled_order], option);
					} else if (typeof(json.loyal_order) != 'undefined') {
					$.plot($('#'+div), [json.order, json.loyal_order], option);
					} else {
					$.plot($('#'+div), [json.order], option);
				}
				
				$('#'+div).bind('plothover', function(event, pos, item) {
					$('.tooltip').remove();
					
					if (item) {
						$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
						
						$('#tooltip').css({
							position: 'absolute',
							left: item.pageX - ($('#tooltip').outerWidth() / 2),
							top: item.pageY - $('#tooltip').outerHeight(),
							pointer: 'cursor'
						}).fadeIn('slow');	
						
						$('#'+div).css('cursor', 'pointer');		
						} else {
						$('#'+div).css('cursor', 'auto');
					}
				});
			}
		});
	}
	
	$(document).ready(function(){
		//getSalesChart($('#range').val());
		getSalesChart('weekloyality', 'loyality_week', 'index.php?route=common/home/loyalitychart&token=<?php echo $token; ?>&range=');
		getSalesChart('years', 'report', 'index.php?route=common/home/chart&token=<?php echo $token; ?>&range=');
		getSalesChart('week_orders', 'report_week', 'index.php?route=common/home/chart&token=<?php echo $token; ?>&range=');
		getSalesChart('week_orders', 'report_week_cancel', 'index.php?route=common/home/cancelchart&token=<?php echo $token; ?>&range=');
	})
</script> 																	