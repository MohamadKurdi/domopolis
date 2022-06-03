<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_install) { ?>
		<div class="warning"><?php echo $error_install; ?></div>
	<?php } ?>
	<?php if ($error_image) { ?>
		<div class="warning"><?php echo $error_image; ?></div>
	<?php } ?>
	<?php if ($error_image_cache) { ?>
		<div class="warning"><?php echo $error_image_cache; ?></div>
	<?php } ?>
	<?php if ($error_cache) { ?>
		<div class="warning"><?php echo $error_cache; ?></div>
	<?php } ?>
	<?php if ($error_download) { ?>
		<div class="warning"><?php echo $error_download; ?></div>
	<?php } ?>
	<?php if ($error_logs) { ?>
		<div class="warning"><?php echo $error_logs; ?></div>
	<?php } ?>
	<style>
		table tr td {vertical-align:top;}
	</style>
	<div class="box">   
		<div class="content">		
			<div style="clear:both"></div>
			<div style="width:100%; margin-bottom:10px;">
				<div class="dashboard-heading">Сторонние сервисы / API / Кэширование</div>
				<div class="dashboard-content">
					<table style="width:100%">	
						<tr>
							<td style="width:50%;">
								<table style="width:100%">
									<tr>
										<td colspan="2" align="left" style="padding:5px;"><span style="padding:4px 7px; background-color:#aece4e; color:white">Связь, телефония</span></td>
									</tr>
									<tr>
										<td style="width:250px;"><b>Epochta SMS (смски)</b></td>
										<td style="padding-top:10px; padding-bottom:10px;"><span id="epochtabalance"><i class="fa fa-spinner fa-spin"></i> загружается баланс...</span></td>
									</tr>
									<tr>
										<td style="width:250px;"><b>Zadarma (Телефония)</b></td>
										<td style="padding-top:10px; padding-bottom:10px;"><span id="zadarmabalance"><i class="fa fa-spinner fa-spin"></i> загружается баланс...</span></td>
									</tr>
								</table>
							</td>
							<td style="width:50%;">
								<table style="width:100%">
									<tr>
										<td colspan="2" align="left" style="padding:5px;"><span style="padding:4px 7px; background-color:#aece4e; color:white">Парсеры</span></td>
									</tr>
									<tr>
										<td style="width:250px;"><b>Парсер Amazon (получение цен)</b></td>
										<td style="padding-top:10px; padding-bottom:10px;"><span id="amazonparser"><i class="fa fa-spinner fa-spin"></i> загружается информация парсера...</span></td>
									</tr>	
									<tr>
										<td style="width:250px;"><b>Парсер Брендов (получение цен)</b></td>
										<td style="padding-top:10px; padding-bottom:10px;"><span id="vbparser"><i class="fa fa-spinner fa-spin"></i> загружается информация парсера...</span></td>
									</tr>
									<tr>
										<td style="width:250px;"><b>REDIS (движок кэширования)</b></td>
										<td style="padding-top:10px; padding-bottom:10px;"><span id="redisinfo"><i class="fa fa-spinner fa-spin"></i> подключение...</span></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="width:50%;">
								<table style="width:100%">
									<tr>
										<td colspan="2" align="left" style="padding:5px;"><span style="padding:4px 7px; background-color:#aece4e; color:white">Почтовые сервисы : ElasticEmail / MailGun / SparkPost, рассылки</span></td>
									</tr>
									<tr>
										<tr>
											<td style="width:250px;"><b>SparkPost API (почта)</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="e_mandrillapi"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
										<tr>
											<td style="width:250px;"><b>SparkPost дневной лимит</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="e_sparkpostday"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
										<tr>
											<td style="width:250px;"><b>SparkPost бесплатный лимит</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="e_sparkpostmonthpaid"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
										<tr>
											<td style="width:250px;"><b>SparkPost месячный лимит</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="e_sparkpostmonth"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
										<? /*
											<tr>
											<td style="width:250px;"><b>ElasticEmail API</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="elasticemail"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
											</tr>
										*/ ?>
										<tr>
											<td style="width:250px;"><b>MainGun API</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="mailgunapi"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>	
									</table>
								</td>
								<td style="width:50%;">
									<table style="width:100%">	
										<tr>
											<td colspan="2" align="left" style="padding:5px;"><span style="padding:4px 7px; background-color:#aece4e; color:white">Почтовые сервисы : Sparkpost, внутренняя почта</span></td>
										</tr>
										<tr>
											<td style="width:250px;"><b>SparkPost API (почта)</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="mandrillapi"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
										<tr>
											<td style="width:250px;"><b>SparkPost дневной лимит</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="sparkpostday"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
										<tr>
											<td style="width:250px;"><b>SparkPost бесплатный лимит</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="sparkpostmonthpaid"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
										<tr>
											<td style="width:250px;"><b>SparkPost месячный лимит</b></td>
											<td style="padding-top:5px; padding-bottom:5px;"><span id="sparkpostmonth"><i class="fa fa-spinner fa-spin"></i> загружается информация...</span></td>
										</tr>
									</table>			
								</td>
							</tr>
						</table>
					</div>			
				</div>		
				<div style="clear:both"></div>
				<div style="width:100%; margin-bottom:10px;">
					<table style="width:100%">
						<tr>
							<td style="width:50%;" rowspan="">
								<div style="width:100%; margin-bottom:10px;">
									<div class="dashboard-heading">Домены</div>
									<div class="dashboard-content">
										<table class="list" style="width:100%">
											<thead>
												<tr>
													<td class="left">Домен</td>
													<td class="left">Оплачен до</td>
													<td class="left">Дней осталось</td>
												</tr>
											</thead>
											<? $i = 0; ?>
											<? foreach ($domains as $domain) {  ?>
												<tr>
													<td class="left" style="white-space: nowrap;">
														<? echo $domain; ?>
													</td>
													<td class="left" id="valid_<? echo $i; ?>">
														<i class="fa fa-spinner fa-spin"></i>
													</td>
													<td class="left" id="left_<? echo $i; ?>">
														<i class="fa fa-spinner fa-spin"></i>
													</td>
												</tr>
												<script>
													$(document).ready(function(){
														$.ajax({
															url : 'index.php?route=common/home/getWhoisExpire&domain=<? echo $domain; ?>&token=<?php echo $token; ?>',
															dataType: 'json',
															success : function(json){
																$('#valid_<? echo $i; ?>').html(json.valid);
																$('#left_<? echo $i; ?>').html(json.days);
															},
															error : function(e){
																console.log(e);
															}
														});													
													});
												</script>
											<?  $i++; } ?>
										</table>
									</div>
								</div>
							</td>
							<td style="width:50%;">
								<div class="dashboard-heading">CloudFlare : CDN / Кэширование статики</div>
								<div class="dashboard-content" id='cloudflare'>
									<table class="list" style="width:100%">
										<thead>
											<tr>
												<td style="width:250px;"><b>Домен</b></td>
												<td style="padding-top:10px; padding-bottom:10px;"><b>Страницы, Запросов (CF/Srv) - за 24h</b></td>
												<td style="padding-top:10px; padding-bottom:10px;"><b>Режим разработки</b></td>
											</tr>
										</thead>
										<? foreach ($cloudflare_domains as $cloudflare_domain) { ?>
											<tr>
												<td style="width:250px;"><b><? echo $cloudflare_domain ?></b></td>
												<td style="padding-top:10px; padding-bottom:10px;"><span class="cloudflare_stats" data-domain="<? echo $cloudflare_domain ?>"><i class="fa fa-spinner fa-spin"></i></span></td>
												<td style="padding-top:10px; padding-bottom:10px;"><span class="cloudflare_devmode" data-domain="<? echo $cloudflare_domain ?>"><i class="fa fa-spinner fa-spin"></i></span>
													<span style="display:inline-block;float:right; ">
														<span class="cloudflare_devmode_on" data-domain="<? echo $cloudflare_domain ?>" style="cursor:pointer; border-bottom:1px dashed black;">вкл режим</span>&nbsp;&nbsp;			  
														<span class="cloudflare_devmode_off" data-domain="<? echo $cloudflare_domain ?>" style="cursor:pointer; border-bottom:1px dashed black;">выкл режим</span>&nbsp;&nbsp;			
														<span class="cloudflare_clrcache" data-domain="<? echo $cloudflare_domain ?>" style="cursor:pointer; border-bottom:1px dashed black;">очистить кэш</span>			  
													</span>
												</td>
											</tr>
										<? } ?>
									</table>									
								</div>	
							</td>
						</tr>
						<tr>
							<td style="width:50%;">
								<div style="width:100%; margin-bottom:10px;">
									<div class="dashboard-heading">Курсы валют  <a href="index.php?route=localisation/currency&token=<?php echo $token; ?>" style="float:right;">перейти к редактированию</a></div>
									<div class="dashboard-content" id='currencies'>
										<div style="padding-top:20px; text-align:center; width:100%"><i class="fa fa-spinner fa-spin"></i> загружаются курсы валют...</div>
									</div>	
								</div>
							</td>
							<td style="width:50%;">
								<div style="width:100%; margin-bottom:10px;">
									<div class="dashboard-heading">Телефония</div>
									<div class="dashboard-content">
										<table style="width:100%">
											<tr>
												<td colspan="7"><b>АТС FreePBX / Asterisk</b> <span id="amistatus"><i class="fa fa-spinner fa-spin"></i> подключаюсь...</span></td>
											</tr>		
											<? foreach ($managers as $manager) { ?>
												<? if ($manager['internal_pbx_num']) { ?>
													<tr>
														<td><b><a href="index.php?route=user/user_sip/history&token=<?php echo $token; ?>&user_id=<? echo $manager['user_id']; ?>"><? echo $manager['realname']; ?></a></b></td>
														<td style="padding-top:5px; padding-bottom:5px;">
															<span class="peerinfo" data-peerid="<? echo $manager['internal_pbx_num'] ?>" style="font-size:14px;"><i class="fa fa-spinner fa-spin"></i> получаю информацию...</span>									
														</td>
														<td>
															<span class="penaltyinfo" data-peerid="<? echo $manager['internal_pbx_num'] ?>" style="font-size:14px;"><i class="fa fa-spinner fa-spin"></i> получаю приоритет...</span>					
														</td>
														<td>
															<span class="peeronduty" data-managerid="<? echo $manager['user_id'] ?>"  style="display:inline-block; float:right; cursor:pointer; border-bottom:1px dashed white; padding:3px; color:white;background:#cf4a61;">сделать дежурным</span>
														</td>
														<td>
															<span class="peeron0" data-managerid="<? echo $manager['internal_pbx_num'] ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#cf4a61;">0</span>
														</td>
														<td>
															<span class="peeron5" data-managerid="<? echo $manager['internal_pbx_num'] ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#e4c25a;">5</span>
														</td>
														<td>
															<span class="peeron10" data-managerid="<? echo $manager['internal_pbx_num'] ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#4ea24e;">10</span>		
														</td>
													</tr>	
												<? } ?>
											<? } ?>
										</table>
										<table style="width:100%">
											<tr>
												<td colspan="2" style="padding-top:30px;"><b>Телефония Украина (GSM-VOIP шлюз GoIP4)</b> <span id="goip4status"><i class="fa fa-spinner fa-spin"></i> подключаюсь...</span></td>
												<tr>
													<td><b>Линия 1 / 0963209777</b></td>
													<td style=""><span id="getline1balance" style="cursor:pointer; border-bottom:1px dashed black; display:inline-block;">Проверить баланс</span> <span id="goip4line1status"></span></td>
												</tr>
												<tr>
													<td><b>Линия 2 / 0993209777</b></td>
													<td><span id="getline2balance" style="cursor:pointer; border-bottom:1px dashed black; display:inline-block;">Проверить баланс</span>  <span id="goip4line2status"></span></td>
												</tr>
												<tr>
													<td><b>Линия 3 / 0967478822</b></td>
													<td style=""><span id="getline3balance" style="cursor:pointer; border-bottom:1px dashed black; display:inline-block;">Проверить баланс</span>  <span id="goip4line3status"></span></td>
												</tr>
												<tr>
													<td ><b>Линия 4 / 0507478822</b></td>
													<td style=""><span id="getline4balance" style="cursor:pointer; border-bottom:1px dashed black; display:inline-block;">Проверить баланс</span>  <span id="goip4line4status"></span></td>
												</tr>
											</tr>		
										</table>	
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>	
				<div style="clear:both"></div>
				<div style="width:100%; margin-bottom:10px;">
					<div class="dashboard-heading">Проверка заказов в ожидании ответа клиента<span style="display:inline-block; float:right; cursor:pointer" onclick="$('#needtocallscan').height(1000);"><span class="text_down">развернуть</span><i class="fa fa-chevron-down icon_down"></i></span></div>
					<div class="dashboard-content" id="needtocallscan" style="min-height:20px; height:20px; overflow-y:scroll;">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>		 
				<div style="clear:both"></div>
				<div style="width:100%; margin-bottom:10px;">
					<div class="dashboard-heading">Проверка заказов в ожидании оплаты<span style="display:inline-block; float:right; cursor:pointer" onclick="$('#nopaidscan').height(1000);"><span class="text_down">развернуть</span><i class="fa fa-chevron-down icon_down"></i></span></div>
					<div class="dashboard-content" id="nopaidscan" style="min-height:20px; height:20px; overflow-y:scroll;">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>		 
				<div style="clear:both"></div>
				<div style="width:100%; margin-bottom:10px;">
					<div class="dashboard-heading">Проверка незавершенных заказов<span style="display:inline-block; float:right; cursor:pointer" onclick="$('#orderscan').height(1000);"><span class="text_down">развернуть</span><i class="fa fa-chevron-down icon_down"></i></span></div>
					<div class="dashboard-content" id="orderscan" style="min-height:20px; height:20px; overflow-y:scroll;">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>		 
				<div style="clear:both"></div>
				<div style="width:100%; margin-bottom:10px;">
					<div class="dashboard-heading">Проверка обрабатываемых заказов <span style="display:inline-block; float:right; cursor:pointer" onclick="$('#ttnscan').height(1000);"><span class="text_down">развернуть</span><i class="fa fa-chevron-down icon_down"></i></span></div>
					<div class="dashboard-content" id="ttnscan" style="min-height:20px; height:20px; overflow-y:scroll;">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>		 
				<div id="ttninfo"></div>
				<div style="clear:both"></div>
				<div style="width:100%; margin-bottom:10px;">
					<div class="dashboard-heading">Проверка счетов покупателей <span style="display:inline-block; float:right; cursor:pointer" onclick="$('#minusscan').height(1000);"><span class="text_down">развернуть</span><i class="fa fa-chevron-down icon_down"></i></span></div>
					<div class="dashboard-content" id="minusscan" style="min-height:20px; height:20px; overflow-y:scroll;">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>		 		
			</div>
			<script>
				$(document).ready(function(){								
					$('#orderscan').load('index.php?route=common/home/getOrdersResult&token=<?php echo $token; ?>');	
				});
			</script>
			<style>
				span.get_ttn_info {cursor:pointer; display:inline-block; border-bottom:1px dashed black;}
			</style>
			<script>
				$(document).ready(function(){								
					$('#ttnscan').load('index.php?route=common/home/getTTNScanResult&token=<?php echo $token; ?>', function(){ init_ttns(); });	
				});
			</script>
			<script>
				$(document).ready(function(){								
					$('#minusscan').load('index.php?route=common/home/getMinusScanResult&token=<?php echo $token; ?>');	
				});
			</script>
			<script>
				function init_ttns(){
					$('.get_ttn_info').click(function(){
						var span = $(this);
						span.next().html('<i class="fa fa-spinner fa-spin"></i>');
						span.next().show();
						var ttn = span.attr('data-ttn');
						$('#ttninfo').load(
						'index.php?route=sale/order/ttninfoajax2&token=<? echo $token ?>',
						{
							ttn : ttn
						}, 
						function(){
							span.next().hide();
							$(this).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Информация по накладной '+ttn}); 
						});
					});
				}</script>
				<script>
					$(document).ready(function(){								
						$('#needtocallscan').load('index.php?route=common/home/getNeedToCallResult&token=<?php echo $token; ?>');	
					});
				</script>
				<script>
					$(document).ready(function(){								
						$('#nopaidscan').load('index.php?route=common/home/getNoPaidResult&token=<?php echo $token; ?>');	
					});
				</script>
				<script>
					$(document).ready(function(){												
						$('.cloudflare_devmode').each(function(i, e){					
							$(this).load('index.php?route=api/cloudflare/getdevmode&domain='+$(this).attr('data-domain')+'&token=<?php echo $token; ?>');
						});
						$('.cloudflare_stats').each(function(i, e){					
							$(this).load('index.php?route=api/cloudflare/getstats&domain='+$(this).attr('data-domain')+'&token=<?php echo $token; ?>');
						});
						$('.cloudflare_clrcache').click(function(){
							var domain = $(this).attr('data-domain');
							swal({ title: "Точно очистить кэш на CloudFlare?", text: "Это приведет к замедлению отображения у покупателей и повышенной нагружзке на сервер", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, очистить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
								$.get('index.php?route=api/cloudflare/purgeCache&domain='+domain+'&token=<?php echo $token; ?>');
							});
						});
						$('.cloudflare_devmode_on').click(function(){					
							$.get('index.php?route=api/cloudflare/setdevmode&mode=1&domain='+$(this).attr('data-domain')+'&token=<?php echo $token; ?>', 
							function(){
								$('.cloudflare_devmode').each(function(i, e){				
									$(this).html('<i class="fa fa-spinner fa-spin"></i>');
								});
								$('.cloudflare_devmode').each(function(i, e){					
									$(this).load('index.php?route=api/cloudflare/getdevmode&domain='+$(this).attr('data-domain')+'&token=<?php echo $token; ?>');
								});					
							}
							);
						});
						$('.cloudflare_devmode_off').click(function(){					
							$.get('index.php?route=api/cloudflare/setdevmode&mode=0&domain='+$(this).attr('data-domain')+'&token=<?php echo $token; ?>', 
							function(){
								$('.cloudflare_devmode').each(function(i, e){				
									$(this).html('<i class="fa fa-spinner fa-spin"></i>');
								});
								$('.cloudflare_devmode').each(function(i, e){					
									$(this).load('index.php?route=api/cloudflare/getdevmode&domain='+$(this).attr('data-domain')+'&token=<?php echo $token; ?>');
								});					
							}
							);
						});
					});		
				</script>
				<script>
					$(document).ready(function(){
						$('#currencies').load('index.php?route=localisation/currency&ajax=1&token=<?php echo $token; ?>');
						$('#epochtabalance').load('index.php?route=common/home/getEpochtaSMSBalance&token=<?php echo $token; ?>');
						$('#zadarmabalance').load('index.php?route=common/home/getZadarmaBalance&token=<?php echo $token; ?>');
						$('#mandrillapi').load('index.php?route=common/home/getMandrillUserInfo&token=<?php echo $token; ?>');
						$('#sparkpostday').load('index.php?route=common/home/getSparkPostLimits&_period=daily&token=<?php echo $token; ?>');
						$('#sparkpostmonth').load('index.php?route=common/home/getSparkPostLimits&_period=month&token=<?php echo $token; ?>');
						$('#sparkpostmonthpaid').load('index.php?route=common/home/getSparkPostLimits&_period=monthpaid&token=<?php echo $token; ?>');
						/*external*/
						$('#e_mandrillapi').load('index.php?route=common/home/getMandrillUserInfo&external=1&token=<?php echo $token; ?>');
						$('#e_sparkpostday').load('index.php?route=common/home/getSparkPostLimits&external=1&_period=daily&token=<?php echo $token; ?>');
						$('#e_sparkpostmonth').load('index.php?route=common/home/getSparkPostLimits&external=1&_period=month&token=<?php echo $token; ?>');
						$('#e_sparkpostmonthpaid').load('index.php?route=common/home/getSparkPostLimits&external=1&_period=monthpaid&token=<?php echo $token; ?>');
						/*---*/
						//	$('#elasticemail').load('index.php?route=common/home/getElasticEmailStats&token=<?php echo $token; ?>');
						$('#mailgunapi').load('index.php?route=common/home/getMailGunEmailStats&token=<?php echo $token; ?>');
						$('#amazonparser').load('index.php?route=common/home/getParserInfo&token=<?php echo $token; ?>');
						$('#vbparser').load('index.php?route=common/home/getParserInfo2&token=<?php echo $token; ?>');
						$('#redisinfo').load('index.php?route=common/home/getRedisInfo&token=<?php echo $token; ?>');					
					});
				</script>
				<script>
					function reloadPenalties(){
						$('.penaltyinfo').each(function(i, e){				
							$(this).html('<i class="fa fa-spinner fa-spin"></i> получаю приоритет...');
						});
						$('.peerinfo').each(function(i, e){					
							$(this).load('index.php?route=api/extcall/getPeerAjax&peer='+$(this).attr('data-peerid')+'&token=<?php echo $token; ?>');
						});
						$('.penaltyinfo').each(function(i, e){				
							$(this).load('index.php?route=api/extcall/getQueueMemberPenaltyAjax&member_id='+$(this).attr('data-peerid')+'&queue_id=<? echo DEFAULT_QUEUE; ?>&token=<?php echo $token; ?>');
						});				
					}
					$(document).ready(function(){
						reloadPenalties();
						$('#goip4status').load('index.php?route=api/goip4/getStatus&general=1&token=<?php echo $token; ?>');
						$('#getline1balance').click(function(){
							$('#goip4line1status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getKyivstarBalance&token=<?php echo $token; ?>');
						});
						$('#getline2balance').click(function(){
							$('#goip4line2status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getVodafoneBalance&token=<?php echo $token; ?>');
						});
						$('#getline3balance').click(function(){
							$('#goip4line3status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getKyivstarBalance&line=3&token=<?php echo $token; ?>');
						});
						$('#getline4balance').click(function(){
							$('#goip4line4status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getVodafoneBalance&line=4&token=<?php echo $token; ?>');
						});
						$('#amistatus').load('index.php?route=api/extcall/getAStatusAjax&ajax=1&token=<?php echo $token; ?>');			
						$('.peeron0').click(function(){
							$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=0&queue_id=<? echo DEFAULT_QUEUE; ?>&token=<?php echo $token; ?>', function(){ reloadPenalties(); }
							);
						});
						$('.peeron5').click(function(){
							$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=5&queue_id=<? echo DEFAULT_QUEUE; ?>&token=<?php echo $token; ?>', function(){ reloadPenalties(); }
							);
						});
						$('.peeron10').click(function(){
							$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=10&queue_id=<? echo DEFAULT_QUEUE; ?>&token=<?php echo $token; ?>', function(){ reloadPenalties(); }
							);
						});
						$('.peeronduty').click(function(){					
							$.get('index.php?route=api/extcall/setMainManagerAjax&manager_id='+$(this).attr('data-managerid')+'&queue_id=<? echo DEFAULT_QUEUE; ?>&token=<?php echo $token; ?>', function(){ reloadPenalties(); }
							);
						});
					});		
				</script>
		<?php echo $footer; ?>																					