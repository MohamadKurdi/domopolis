<style>
	table tr td {vertical-align:top;}
</style>
<style>
	h2,.box > .content h2 {font-size: 18px; clear: both; border-bottom: none; font-weight: 400}

	.tile-block{width:25%; float: left; margin-left:5px;}
	.tile-block.narrow{width:15%; float: left; margin-left:5px;}

	@media only screen and (max-width: 600px) {
		.tile-block{width:100%; float: left; margin-left:0px;}
	}

	.tile-body {overflow: hidden; line-height: 14px;}

	.tile.good > .tile-heading, .tile.good > .tile-footer{background-color: #00ad07; }
	.tile.good > .tile-body{background-color: #00ad07;opacity: 0.8;}

	.tile.warn > .tile-heading, .tile.warn > .tile-footer{background-color: #ff7815; }
	.tile.warn > .tile-body{background-color: #ff7815;opacity: 0.8;}

	.tile.bad > .tile-heading, .tile.bad > .tile-footer{background-color: #ce1400; }
	.tile.bad > .tile-body{background-color: #ce1400;opacity: 0.8;}

	.tile.unknown > .tile-heading, .tile.unknown > .tile-footer{background-color: #24a4c1; }
	.tile.unknown > .tile-body{background-color: #24a4c1;opacity: 0.8;}

	.tile-result{color:#FFF; font-size:28px; max-height: 45px; line-height: 28px;}
	.tile-result.small{font-size: 14px; max-height: 45px; line-height: 14px;}		

	.tile .tile-body i{font-size:28px;}
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
				<div id="amazon_stats" style="width:49%; float:left;" class="amazonstats delayed-load" data-route='common/home/loadProductStats'>
				</div>
			<?php } ?>


			<div id="order_filters" <?php if ($stores_count == 1) { ?>style="width:50%; float:right;"<?php } ?> class="filters delayed-load" data-route='common/home/loadOrderStats'>
			</div>

			<div style="clear:both;  height:10px;" ></div>			
			<div id="monitor">
				<div class="tile-block narrow">
					<div class="tile info-loader unknown" data-path="common/panel/getSMSBalance">
						<div class="tile-heading">Баланс SMS-шлюза</div>
						<div class="tile-body">
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							смс-шлюз <?php echo $this->config->get('config_smsgate_library');?>
						</div>
					</div>
				</div>

				<div class="tile-block narrow">
					<div class="tile info-loader unknown" data-path="common/queues/countSmsQueue">
						<div class="tile-heading">Очередь SMS</div>
						<div class="tile-body">
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							сколько смсок в очереди
						</div>
					</div>
				</div>

				<div class="tile-block narrow">
					<div class="tile info-loader unknown" data-path="common/panel/getOpenAIInfo" data-update-interval="300000">
						<div class="tile-heading">🤖 OpenAI API</div>
						<div class="tile-body">
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							всякие умные штуки
						</div>
					</div>
				</div>	

				<?php if ($this->config->get('config_translation_library')) { ?>
				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/get<?php echo $this->config->get('config_translation_library'); ?>Info" data-update-interval="600000">
						<div class="tile-heading">🤖 <?php echo $this->config->get('config_translation_library'); ?> API</div>
						<div class="tile-body">
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							<?php echo $this->config->get('config_translation_library'); ?>
						</div>
					</div>
				</div>
				<?php } ?>

			</div>	
			
			<?php if ($this->config->get('config_enable_amazon_specific_modes') && $this->config->get('config_rainforest_enable_api')) { ?>
				<div style="clear:both; height:10px;"></div>
				<div class="latest delayed-load" data-route='common/home/getRainForestStats' data-reload="30000"></div>
			<?php } ?>

			<?php if ($this->config->get('config_cron_stats_display_enable')) { ?>
				<div style="clear:both; height:10px;"></div>
				<div class="latest delayed-load" data-route='common/home/getCronStats' data-reload="9000"></div>
			<?php } ?>

			<div style="clear:both; height:10px;"></div>
			<div class="latest delayed-load" data-route='common/home/getLastTwentyOrders'></div>
		</td>
	</tr>
</table>

<script>
		function updateStats(elem, uri){

			$.ajax({
					url: uri,
					type: 'GET',
					async: true,
					dataType: 'json',
					beforeSend: function(){
						$(elem).removeClass('unknown').removeClass('good').removeClass('warn').removeClass('bad').addClass('unknown');
						$(elem).children('.tile-body').children('.tile-result').html('<i class="fa fa-spinner fa-spin">');
					},		
					success: function(json){					
						$(elem).children('.tile-body').children('.tile-result').html(json.body);
						if (json.footer){
							$(elem).children('.tile-footer').html(json.footer);
						}
						$(elem).removeClass('unknown').removeClass('good').removeClass('warn').removeClass('bad').addClass(json.class);
					},
					error: function(error){
						console.log(error);
						$(elem).removeClass('unknown').removeClass('good').removeClass('warn').removeClass('bad').addClass('bad');
					}
				});

		}

		function initUpdateStats(){
			$('div.info-loader').each(async function(index, elem){
				let uri = 'index.php?route='+ $(elem).attr('data-path') +'&nolog=1&token=<?php echo $token; ?>';

				if ($(elem).attr('data-x')){
					uri += ('&x=' + $(elem).attr('data-x'));
				}

				if ($(elem).attr('data-defer')){
					setTimeout(function(){ updateStats(elem, uri); } , $(elem).attr('data-defer')); 
					console.log($(elem).attr('data-path') + ' setTimeout ' +  $(elem).attr('data-defer'));
				} else {

					if ($(elem).attr('data-update-interval')){
						setInterval(function(){ updateStats(elem, uri); } , $(elem).attr('data-update-interval')); 
						updateStats(elem, uri);
						console.log($(elem).attr('data-path') + ' setInterval ' +  $(elem).attr('data-update-interval'));
					} else {
						setInterval(function(){ updateStats(elem, uri); } , 10000); 
						updateStats(elem, uri);
					}
				}

			});
		}

		$(document).ready(function() {			  
			initUpdateStats();
		});
	</script>
