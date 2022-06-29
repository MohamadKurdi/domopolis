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

			<?php if ($this->config->get('config_amazon_product_stats_enable')) { ?>
				<div id="amazon_stats" style="width:49%; float:left;" class="amazonstats delayed-load" data-route='common/home/loadProductStats' data-reload="4000">
				</div>
			<?php } ?>


			<div id="order_filters" <?php if ($stores_count == 1) { ?>style="width:50%; float:right;"<?php } ?> class="filters delayed-load" data-route='common/home/loadOrderStats'>
			</div>

			<?php if ($this->config->get('config_cron_stats_display_enable')) { ?>
				<div style="clear:both; height:10px;">
				</div>
				<div class="latest delayed-load" data-route='common/home/getCronStats' data-reload="4000">
				
				</div>
			<?php } ?>

			<div style="clear:both; height:10px;">
			</div>
			<div class="latest delayed-load" data-route='common/home/getLastTwentyOrders'>
				
			</div>
		</td>
	</tr>
</table>

