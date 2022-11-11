<?php echo $header; ?>
<style>
	.tooltip{position:absolute;z-index:1070;display:block;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-style:normal;font-weight:400;letter-spacing:normal;line-break:auto;line-height:1.42857;text-align:left;text-align:start;text-decoration:none;text-shadow:none;text-transform:none;white-space:normal;word-break:normal;word-spacing:normal;word-wrap:normal;font-size:11px;opacity:0;filter:alpha(opacity=0)}.tooltip.in{opacity:.9;filter:alpha(opacity=90)}.tooltip.top{margin-top:-3px;padding:5px 0}.tooltip.right{margin-left:3px;padding:0 5px}.tooltip.bottom{margin-top:3px;padding:5px 0}.tooltip.left{margin-left:-3px;padding:0 5px}.tooltip-inner{max-width:200px;padding:3px 8px;color:#fff;text-align:center;background-color:#000;border-radius:3px}.tooltip-arrow{position:absolute;width:0;height:0;border-color:transparent;border-style:solid}.tooltip.top .tooltip-arrow{bottom:0;left:50%;margin-left:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.top-left .tooltip-arrow{bottom:0;right:5px;margin-bottom:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.top-right .tooltip-arrow{bottom:0;left:5px;margin-bottom:-5px;border-width:5px 5px 0;border-top-color:#000}.tooltip.right .tooltip-arrow{top:50%;left:0;margin-top:-5px;border-width:5px 5px 5px 0;border-right-color:#000}.tooltip.left .tooltip-arrow{top:50%;right:0;margin-top:-5px;border-width:5px 0 5px 5px;border-left-color:#000}.tooltip.bottom .tooltip-arrow{top:0;left:50%;margin-left:-5px;border-width:0 5px 5px;border-bottom-color:#000}.tooltip.bottom-left .tooltip-arrow{top:0;right:5px;margin-top:-5px;border-width:0 5px 5px;border-bottom-color:#000}.tooltip.bottom-right .tooltip-arrow{top:0;left:5px;margin-top:-5px;border-width:0 5px 5px;border-bottom-color:#000}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head" style="height:70px;">
			<h1> Динамика расхода товара с остатков</h1>			
			<div class="buttons"><span id="sync_results" style="margin-right:30px;"></span><a href="<? echo $backhref; ?>" class="button">Вернуться</a></div>
			<div style="clear:both"></div>
		</div>
		
		<div class="content">
			<table class="list">
				<tr>
					<td style="font-size:18px; padding:4px 5px; background-color:#faf9f1;">
						<img src="view/image/flags/de.png" title="ru">&nbsp;Центральный склад за неделю
					</td>
					<td style="font-size:18px; padding:4px 5px; background-color:#faf9f1;">
						<img src="view/image/flags/ru.png" title="ru">&nbsp;Склад Москва за неделю
					</td>
				</tr>
				<tr>
					<td style="width:50%; padding:10px;">
						<div id="quantity_stock_p_total_week" style="width:100%; height:350px;"></div>
					</td>
					<td style="width:50%; padding:10px;">
						<div id="quantity_stockM_p_total_week" style="width:100%; height:350px;"></div>
					</td>
				</tr>
				<tr>
					<td style="font-size:18px; padding:4px 5px; background-color:#faf9f1;">
						<img src="view/image/flags/de.png" title="ru">&nbsp;Центральный склад за месяц
					</td>
					<td style="font-size:18px; padding:4px 5px; background-color:#faf9f1;">
						<img src="view/image/flags/ru.png" title="ru">&nbsp;Склад Москва за месяц
					</td>
				</tr>
				<tr>
					<td style="width:50%; padding:10px;">
						<div id="quantity_stock_p_total_month" style="width:100%; height:350px;"></div>
					</td>
					<td style="width:50%; padding:10px;">
						<div id="quantity_stockM_p_total_month" style="width:100%; height:350px;"></div>
					</td>
				</tr>			
			</table>
		</div>
	</div>
</div>

<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.resize.min.js"></script>
<script type="text/javascript"><!--
	function getStocksChart(warehouse, range) {
		$.ajax({
			type: 'get',
			url: 'index.php?route=catalog/stocks/getStockDynamicsAjax&warehouse_identifier='+ warehouse +'&token=<?php echo $token; ?>&range=' + range,
			dataType: 'json',
			async: false,
			success: function(json) {
				var option = {	
					shadowSize: 0,
					colors: ['#4ea24e', '#1065D2'],
					bars: { 
						//	show: true,
						//	fill: true,
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
					},
					series: {            
						lines: {
							show: true,
							fill: true
						}
					}
				}
				
				$.plot($('#'+ warehouse +'_p_total_'+range), [json.total_q_count, json.total_p_count], option);
				
				$('#'+ warehouse +'_p_total_'+range).bind('plothover', function(event, pos, item) {
					$('.tooltip').remove();
					
					if (item) {
						$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
						
						$('#tooltip').css({
							position: 'absolute',
							left: item.pageX - ($('#tooltip').outerWidth() / 2),
							top: item.pageY - $('#tooltip').outerHeight(),
							pointer: 'cursor'
						}).fadeIn('slow');	
						
						$('#'+ warehouse +'_p_total_'+range).css('cursor', 'pointer');		
						} else {
						$('#'+ warehouse +'_p_total_'+range).css('cursor', 'auto');
					}
				});
				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	
	$(document).ready(function(){
		getStocksChart('quantity_stock', 'week');
		getStocksChart('quantity_stockM', 'week');		
		getStocksChart('quantity_stock', 'month');
		getStocksChart('quantity_stockM', 'month');
	})
//--></script>
<?php echo $footer; ?> 										