<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
	<head>
		<? require_once(dirname(__FILE__).'/../pwa.tpl'); ?>
	
		<meta charset="utf-8">
		<meta name="viewport"
		content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $title; ?></title>
		<link href="<? echo FAVICON; ?>" rel="icon" type="image/x-icon" />
		<base href="<?php echo $base; ?>" />
		<?php if ($description) { ?>
			<meta name="description" content="<?php echo $description; ?>" />
		<?php } ?>
		<?php if ($keywords) { ?>
			<meta name="keywords" content="<?php echo $keywords; ?>" />
		<?php } ?>
		<?php foreach ($links as $link) { ?>
			<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
		<?php } ?>
		<link rel="stylesheet" href="view/stylesheet/font-awesome-4.7.0/css/font-awesome.min.css">
		<link type="text/css" href="view/javascript/jquery/ui/themes/redmond/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/<? echo FILE_STYLE; ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/<? echo FILE_STYLE2; ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/tickets.css" />
		<?php foreach ($styles as $style) { ?>
			<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
		<?php } ?>
		<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic" rel="stylesheet"> 
		
		<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript" src="view/javascript/common.js"></script>
		
		<?php foreach ($scripts as $script) { ?>
			<script type="text/javascript" src="<?php echo $script; ?>"></script>
		<?php } ?>
		<script type="text/javascript">
			//-----------------------------------------
			// Confirm Actions (delete, uninstall)
			//-----------------------------------------
			$(document).ready(function(){
				// Confirm Delete
				$('#form').submit(function(){
					if ($(this).attr('action').indexOf('delete',1) != -1) {
						if (!confirm('<?php echo $text_confirm; ?>')) {
							return false;
						}
					}
				});
				// Confirm Uninstall
				$('a').click(function(){
					if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
						if (!confirm('<?php echo $text_confirm; ?>')) {
							return false;
						}
					}
				});
			});
		</script>
		
		<!-- Admin Header Notices 1.0 -->
		<style type="text/css">
			.pull-right {
            float:right;
			}
			.label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: bold;
            line-height: 1;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
			}
			.label-success {
            background-color: #5cb85c;
			}
			.label-info {
            background-color: #5bc0de;
			}
			.label-warning {
            background-color: #f0ad4e;
			}
			.label-danger {
            background-color: #d9534f;
			}
		</style>
		<!-- Admin Header Notices 1.0 -->
		
		<?php if ($this->config->get('admin_quick_edit_status') && ($this->config->get('aqe_alternate_row_colour') || $this->config->get('aqe_row_hover_highlighting'))) { ?>
			<style type="text/css">
				<?php if ($this->config->get('aqe_alternate_row_colour')) { ?>
					table.list tbody tr:not([class~=filter]):nth-child(even) td {background: #F8F8FB !important}
					table.list tbody tr:not([class~=filter]).selected_row td {background-color:#ffffde !important}
				<?php } ?>
				<?php if ($this->config->get('aqe_row_hover_highlighting')) { ?>
					table[class=list] tbody tr:not([class~=filter]):hover td {background: #faf9f1 !important}
					table[class=list] tbody tr:not([class~=filter]).selected_row:hover td {background: #ffefde !important}
				<?php } ?>
			</style>
		<?php } ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$('input[type=checkbox][name^="selected"]').change(function () {
					if ($(this).is(':checked')) {
						$(this).parents('tr').first().addClass('selected_row');
						} else {
						$(this).parents('tr').first().removeClass('selected_row');
					}
				});
			});
		</script>
		
		<link rel="Stylesheet" type="text/css" href="view/stylesheet/jpicker-1.1.6.min.css" />
		<link rel="Stylesheet" type="text/css" href="view/stylesheet/jpicker.css" />
		<script src="view/javascript/jquery/jpicker-1.1.6.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function()
			{
				$.fn.jPicker.defaults.images.clientPath='view/image/';
				var LiveCallbackElement = $('#Live'),
				LiveCallbackButton = $('#LiveButton');
				$('#pick_status_txt_color').jPicker({window:{title:'Binded Example'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
				$('#pick_status_bg_color').jPicker({window:{title:'Binded Example'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
			});
		</script>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div class="div1">
					<div class="div2">
						<?php if ($logged) { ?>
							<img class="d_img" src="view/image/<? echo FILE_LOGO; ?>" style="float:left;margin-top:0px;" title="<?php echo $heading_title; ?>" height="38px;" onclick="location = '<?php echo $home; ?>'" />	
							<div style="float:left; color:#000; font-weight:700; padding-left:20px;"><i class="fa fa-user-o icon_header"></i><div style="display: inline-block;"><? echo $this->user->getUserFullName(); ?> (<? echo $this->user->getUserName(); ?>)
							<a style="color:#788084" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link"></i></b></a>	<br /><? echo $this->user->getUserGroupName(); ?></div></div>
						<? } ?>
					</div>
					
					<?php if ($logged) { ?> 
						<div id="menu_top">
							<ul class="left">
								
								<li id="alertlog"><a class="top" href="<? echo $user_alerts; ?>" id="alert_history_preview_click"><span class="label label-danger" style="font-size:16px;"><i class='fa fa-bell' style="color:#FFF"></i></span></a></li>
								<!-- Admin Panel Notication -->
								<li id="notification" class="header-notifications delayed-load short-delayed-load" data-route='common/home/loadNotifications'>
									
									</li>							
								
								<li id="waitlists">
										<a class="top" href="<? echo $waitlist_ready; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-hourglass-half" ></i>&nbsp;НАЛ&nbsp;&nbsp;<?php echo $total_waitlist_ready; ?></span></a>										
									</li>
									<li id="waitlists-pre">
										<a class="top" href="<? echo $waitlist_pre; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-hourglass-half" ></i>&nbsp;ЗАЯВКИ&nbsp;&nbsp;<?php echo $total_waitlist_prewaits; ?></span></a>
									</li>
								
								
							</ul>
							<style>
								#menu_top > ul li ul{
								display:none;
								}
								
								#menu_top > ul > li.hover > ul{
								display:block;
								}
								
								#menu_top > ul > li.sfhover > ul{
								display:block;
								}
								
								#menu_top > ul > li > ul > li > ul{
								display:none;
								}
								
								#menu_top > ul > li > ul > li:hover > ul{
								display:block;
								}
							</style>
							
							<div style="clear: both;"></div>
						</div>
					<? } ?>
					
					<div style="clear: both;"></div>
				</div>
				<?php if ($logged) { ?>  	
					
					<div id="menu">						
						<ul class="left" style="margin-left:0px;">
							<? if ($user_sip_history) { ?>
								<li id="user_sip_history"><a href="<?php echo $user_sip_history; ?>" class="top"><i class="fa fa-phone-square icon_menu" aria-hidden="true"></i>Звонки</a></li>
							<? } ?>
							<li id="tasks"><a href="<?php echo $user_ticket; ?>" class="top"><i class="fa fa-calendar icon_menu" aria-hidden="true"></i>Задачи</a></li>
							<li id="add_task"><a id="trigger_add_task" class="top"><i class="fa fa-calendar-plus-o icon_menu" aria-hidden="true"></i>Задача</a></li> 
						</ul>
						
						<? if (isset($ONLYCURRENCY)) { ?>
							<div style="float:left; margin-left:40px; color:#FFF; font-size:26px; line-height:60px;">
								1€ = <? echo $ONLYCURRENCY; ?>
							</div>
							<? } else { ?>
							<div style="float:left; margin-left:40px; color:#FFF; font-size:14px; line-height:30px;">
								1€ = <? echo $RUBEUR; ?><br />
								1€ = <? echo $UAHEUR; ?><br />
							</div>
						<? } ?>
						
						<ul class="right">
							<li id="dashboard"><a href="<?php echo $home; ?>" class="top"><i class="fa fa-home icon_menu"></i><?php echo $text_dashboard; ?></a></li>     
							
							
							<? $this->load->model('localisation/order_status'); ?>
							<? $data = array('start' => 0, 'limit' => 100); $order_statuses = $this->model_localisation_order_status->getOrderStatuses($data); ?>
							
							<li id="orders"><a class="top" href="<?php echo $order; ?>"><i class="fa fa-check-square-o icon_menu"></i><?php echo $text_order; ?></a>
								<ul>
									<? foreach ($order_statuses as $status) { ?>
										<li><a href="<? echo $this->url->link('sale/order', 'filter_order_status_id='.$status['order_status_id']).'&token='.$token; ?>">
										<i class="fa fa-shopping-bag" style="color: #<?php echo isset($status['status_bg_color']) ? $status['status_bg_color'] : ''?>"></i>&nbsp;&nbsp;<? echo $status['name']; ?></a></li>				
									<? } ?>	
									<li><a href="<? echo $fucked_link; ?>">Незав. <span style="color:white;font-weight:700;">(<? echo $fucked_order_total; ?></span></a></li>
								</ul>
							</li>
							<li id="stocks"><a  class="top" href="<?php echo $stocks; ?>"><i class="fa fa-cube icon_menu"></i>Остатки</a></li>
							<li id="waitlist"><a class="top" href="<?php echo $waitlist; ?>"><i class="fa fa-hourglass-2 icon_menu"></i>Лист ож.</a></li>
							<li id="parties"><a class="top" href="<?php echo $parties; ?>"><i class="fa fa-cubes icon_menu"></i>Партии</a></li>
							<li id="buyer"><a class="top"><i class="fa fa-eur icon_menu"></i>Закупка</a>
								<ul>						
									<li><a class="home_icon_style" href="<?php echo $waitlist; ?>"><i class="fa fa-clock-o"></i><span>Лист ожидания</span></a></li> 
									<li><a class="home_icon_style" href="<?php echo $stocks; ?>"><i class="fa fa-cubes"></i><span>Свободные остатки</span></a></li>	
									<?php if ($this->config->get('config_country_id') == 176) { ?>
										<li><a class="home_icon_style" href="<?php echo $yandex; ?>" style="color:#cf4a61"><i class="fa fa-yahoo"></i><span>Yandex Market</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $priceva; ?>" style="color:#7F00FF"><i class="fa fa-product-hunt"></i><span>Мониторинг конкурентов</span></a></li>
									<?php } ?>
									
									<li><a class="home_icon_style" href="<?php echo $report_buyanalyze; ?>"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>Потребность в закупке</span></a></li>			
									<li><a class="home_icon_style" href="<?php echo $parties; ?>"><i class="fa fa-list-ol"></i><span>Закупочные партии</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $suppliers; ?>"><i class="fa fa-list-alt"></i><span>Справочник поставщиков</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $buyerprices; ?>"><i class="fa fa-exchange"></i><span>Проверка цен по ASIN / EAN</span></a></li>									
								</ul>
							</li>
							<li id="sip_history"><a class="top" href="<?php echo $user_sip; ?>"><i class="fa fa-phone icon_menu"></i>Звонки</a></li>
							
							<li id="return"><a class="top" href="<?php echo $return; ?>"><i class="fa fa-refresh icon_menu"></i><?php echo $text_return; ?></a></li>	
							<li id="reports"><a class="top"><i class="fa fa-area-chart icon_menu"></i><?php echo $text_reports; ?></a>
								<ul>	
									<li><a class="home_icon_style" href="<?php echo $mreport_ttnscan ?>"><i class="fa fa-area-chart"></i><span>Отчет по ТТН</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $mreport_needtocall ?>"><i class="fa fa-spinner"></i><span>Заказы в ожидании ответа</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $mreport_nopaid ?>"><i class="fa fa-spinner"></i><span>Заказы в ожидании оплаты</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $mreport_minusscan ?>"><i class="fa fa-exclamation"></i><span>Проверка счетов</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $mreport_forgottencart ?>"><i class="fa fa-pencil-square-o"></i><span>Незавершенные заказы</span></a></li>
									<li><a class="parent home_icon_style"><i class="fa fa-database"></i><span><?php echo $text_sale; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_adv_sale_order ?>">Универсальный отчет</a></li>	
											<li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
											<li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
											<li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
											<li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
											<li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
										</ul>
									</li>
									<li><a class="parent home_icon_style"><i class="fa fa-cubes"></i><span><?php echo $text_product; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
											<li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
										</ul>
									</li>
									<li><a class="parent home_icon_style"><i class="fa fa-users"></i><span><?php echo $text_customer; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
											<li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
											<li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
											<li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
										</ul>
									</li>
									<li><a class="parent home_icon_style"><i class="fa fa-handshake-o"></i><span><?php echo $text_affiliate; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_affiliate_commission; ?>"><?php echo $text_report_affiliate_commission; ?></a></li>
											<li><a href="<?php echo $report_affiliate_statistics; ?>"><?php echo $text_report_affiliate_statistics; ?></a></li>
											<li><a href="<?php echo $report_affiliate_statistics_all; ?>"><?php echo $text_report_affiliate_statistics_all; ?></a></li>
										</ul>
									</li>
								</ul>
							</li>	
							
							<li id="customer"><a class="top"><i class="fa fa-users icon_menu"></i><?php echo $text_customer; ?></a>
								<ul>
									<li><a class="home_icon_style" href="<?php echo $customer; ?>"><i class="fa fa-user-plus"></i><span><?php echo $text_customer; ?></span></a></li>
									<li><a class="home_icon_style" href="<?php echo $customer_group; ?>"><i class="fa fa-users"></i><span><?php echo $text_customer_group; ?></span></a></li>
									<li><a class="home_icon_style" href="<?php echo $customer_ban_ip; ?>"><i class="fa fa-minus-circle"></i><span><?php echo $text_customer_ban_ip; ?></span></a></li>
								</ul>
							</li>
							<li id="store"><a href="<?php echo $store; ?>" target="_blank" class="top"><i class="fa fa-share icon_menu"></i><?php echo $text_front; ?></a>
								<ul>
									<?php foreach ($stores as $stores) { ?>
										<li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
									<?php } ?>
								</ul>
							</li>																				
							
						</ul>
						<!--<ul class="right">
							<li id="store"><a href="<?php echo $store; ?>" target="_blank" class="top"><?php echo $text_front; ?></a>
							<ul>
							<?php foreach ($stores as $stores) { ?>
								<li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
							<?php } ?>
							</ul>
							</li>
							
							<li><a class="top" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link icon_down"></i></b></a></li>
						</ul>-->
						<div style="clear: both;"></div>
					</div>
				<?php } ?>
			</div>
			<div id="alert_history_preview"></div>
			<script>
				$('a#alert_history_preview_click').click(function(){
					$.ajax({
						url: 'index.php?route=user/user_alerts&token=<?php echo $token; ?>&ajax=1',
						dataType: 'html',				
						success : function(html){
							$('#alert_history_preview').html(html).dialog({width:800, height:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: "Мои уведомления"})				
						}
					});
					return false;
				});	
			</script>																																				