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
		<link rel="stylesheet" type="text/css" href="view/stylesheet/mobile.css?v=<?php echo rand(); ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/tickets.css" />
		<?php foreach ($styles as $style) { ?>
			<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
		<?php } ?>
		
		<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript" src="view/javascript/common.js"></script>
		
		<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic" rel="stylesheet"> 
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
		<style>
			span._red{display:inline-block; font-size:18px; background:#FF8080; color:#fff;}
			span._orange{display:inline-block; font-size:18px; background:#F93; color:#fff;}
			span._green{display:inline-block; font-size:18px; background:#CCFFCC; color:#000!important;}
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
							<div style="float:left; color:#000; font-weight:700; padding-left:20px;"><? if ($this->user->getID() == 69) { ?><img src="view/images/fsmall.png" style="margin-right:5px;" /><? } else { ?><i class="fa fa-user-o icon_header"></i><? } ?><div style="display: inline-block;"><? echo $this->user->getUserFullName(); ?> (<? echo $this->user->getUserName(); ?>)
								<a style="color:#788084" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link"></i></b></a>
							<br /><? echo $this->user->getUserGroupName(); ?></div></div>
						<? } ?>
						<?php if ($logged) { ?> 
							<div id="menu_top" style="margin-top:5px;">
								<ul class="left">
									
									<li id="alertlog"><a class="top" href="<? echo $user_alerts; ?>" id="alert_history_preview_click"><span class="label label-danger" style="font-size:16px;"><i class='fa fa-bell' style="color:#FFF"></i></span></a></li>
									<!-- Admin Panel Notication -->
									<li id="notification" class="header-notifications delayed-load short-delayed-load" data-route='common/home/loadNotifications'>
									
									</li>
									<!-- Admin Header Notices 1.0 -->
									<li id="callbacks">
										<a class="top" href="<? echo $callback; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-phone-square" ></i> &nbsp;&nbsp;<?php echo $total_callbacks; ?></span></a>		
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
							
							<div id="csi_menu_top" style="margin-top:5px; float:left; margin-left:20px;">
								<? if (!empty($kpi_stats)) { ?>
									<span class="ktooltip_hover label <? echo $kpi_stats['complete_cancel_percent_clr']; ?>" style="" data-tooltip-content="#complete_cancel_percent-html-content">ПНЗМ <? echo $kpi_stats['complete_cancel_percent']; ?></span>
									<span class="ktooltip_hover label <? echo $kpi_stats['average_confirm_time_clr']; ?>" style="margin-left:10px" data-tooltip-content="#average_confirm_time-html-content">СВПЗ <? echo $kpi_stats['average_confirm_time']; ?> дн.</span>
									<span class="ktooltip_hover label <? echo $kpi_stats['average_process_time_clr']; ?>" style="margin-left:10px" data-tooltip-content="#average_process_time-html-content">СВВЗ <? echo $kpi_stats['average_process_time']; ?> дн.</span>
									
									<div style="display:none;" >
										<div id="complete_cancel_percent-html-content">
											<b style="font-size:18px;">ПНЗМ - Процент Неконверсионных Заказов Менеджера</b><br /><br />
											<span class="label _green">Зеленая зона до <? echo $kpi_stats['complete_cancel_percent_params'][0]; ?>%</span><br /><br />
											<span class="label _orange">Желтая зона от <? echo $kpi_stats['complete_cancel_percent_params'][0]; ?>% до <? echo $kpi_stats['complete_cancel_percent_params'][1]; ?>%</span><br /><br />
											<span class="label _red">Красная зона более <? echo $kpi_stats['complete_cancel_percent_params'][1]; ?>%</span>
										</div>
									</div>
									
									<div style="display:none;" >
										<div id="average_confirm_time-html-content">
											<b style="font-size:18px;">СВПЗ - Среднее Время Подтверждения Заказов</b><br /><br />
											<span class="label _green">Зеленая зона до <? echo $kpi_stats['average_confirm_time_params'][0]; ?> дн.</span><br /><br />
											<span class="label _orange">Желтая зона от <? echo $kpi_stats['average_confirm_time_params'][0]; ?> до <? echo $kpi_stats['average_confirm_time_params'][1]; ?> дн.</span><br /><br />
											<span class="label _red">Красная зона более <? echo $kpi_stats['average_confirm_time_params'][1]; ?> дн.</span>
										</div>
									</div>
									
									<div style="display:none;" >
										<div id="average_process_time-html-content">
											<b style="font-size:18px;">СВВЗ - Среднее Время Закрытия Заказов</b><br /><br />
											<span class="label _green">Зеленая зона до <? echo $kpi_stats['average_process_time_params'][0]; ?> дн.</span><br /><br />
											<span class="label _orange">Желтая зона от <? echo $kpi_stats['average_process_time_params'][0]; ?> до <? echo $kpi_stats['average_process_time_params'][1]; ?> дн.</span><br /><br />
											<span class="label _red">Красная зона более <? echo $kpi_stats['average_process_time_params'][1]; ?> дн.</span>
										</div>
									</div>
								<? } ?>
							</div>
						<? } ?>
					</div>
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
							<li id="dashboard"><a href="<?php echo $home; ?>&no_redirect=1" class="top"><i class="fa fa-home icon_menu"></i><?php echo $text_dashboard; ?></a></li>     
							<li id="worktime"><a class="top"><i class="fa fa-eye icon_menu"></i>Работа</a>
								<ul>
									<li id="user_worktime"><a class="home_icon_style" href="<?php echo $user_worktime; ?>"><i class="fa fa-eye"  aria-hidden="true"></i><span>Сводка за день</span></a></li>
									<li id="manager_quality"><a class="home_icon_style" href="<?php echo $manager_quality; ?>"><i class="fa fa-bar-chart"  aria-hidden="true"></i><span>Сводка по менеджерам</span></a></li>
									<? if ($this->user->canUnlockOrders()) { ?>
										<li id="salary_manager"><a class="home_icon_style" href="<?php echo $salary_manager; ?>"><i class="fa fa-handshake-o"  aria-hidden="true"></i><span>Отчет по закрытым заказам - менеджера</span></a></li>
									<? } ?>
									<? if ($this->user->getUserGroup() == 14) { ?>
										<li id="salary_customerservice"><a class="home_icon_style" href="<?php echo $salary_customerservice; ?>"><i class="fa fa-users"  aria-hidden="true"></i><span>Клиент-сервис за месяц</span></a></li>
									<? } ?>
								</ul>
							</li>
							<li id="orders"><a class="top" href="<?php echo $order; ?>"><i class="fa fa-check-square-o icon_menu"></i><?php echo $text_order; ?></a>							
							</li>						
							<li id="buyer"><a class="top"><i class="fa fa-eur icon_menu"></i>Закупка</a>
								<ul>
									<li><a class="home_icon_style" href="<?php echo $waitlist; ?>"><i class="fa fa-clock-o"></i><span>Лист ожидания</span></a></li> 
									<li><a class="home_icon_style" href="<?php echo $stocks; ?>"><i class="fa fa-cubes"></i><span>Свободные остатки</span></a></li>	
									
									<li><a class="home_icon_style" href="<?php echo $yandex; ?>" style="color:#cf4a61"><i class="fa fa-yahoo"></i><span>Yandex Market</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $priceva; ?>" style="color:#7F00FF"><i class="fa fa-product-hunt"></i><span>Мониторинг конкурентов</span></a></li>
									
									<li><a class="home_icon_style" href="<?php echo $report_buyanalyze; ?>"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><span>Потребность в закупке</span></a></li>			
									<li><a class="home_icon_style" href="<?php echo $parties; ?>"><i class="fa fa-list-ol"></i><span>Закупочные партии</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $suppliers; ?>"><i class="fa fa-list-alt"></i><span>Справочник поставщиков</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $buyerprices; ?>"><i class="fa fa-exchange"></i><span>Проверка цен по ASIN / EAN</span></a></li>
									
									<li><a class="home_icon_style" href="<?php echo $amazonorder; ?>"><i class="fa fa-amazon"></i><span>Заказы поставщикам на Amazon.de</span></a></li>
								</ul>
							</li>
							<li id="return"><a class="top" href="<?php echo $return; ?>"><i class="fa fa-refresh icon_menu"></i><?php echo $text_return; ?></a></li>		
							
							<li id="customer"><a class="top"><i class="fa fa-users icon_menu"></i>Клиенты</a>
								<ul>
									<li><a class="home_icon_style" href="<?php echo $customer; ?>"><i class="fa fa-user-plus"></i><span><?php echo $text_customer; ?></span></a></li>
									<li><a class="home_icon_style" href="<?php echo $customer_group; ?>"><i class="fa fa-users"></i><span><?php echo $text_customer_group; ?></span></a></li>
									<li><a class="home_icon_style" href="<?php echo $customer_ban_ip; ?>"><i class="fa fa-minus-circle"></i><span><?php echo $text_customer_ban_ip; ?></span></a></li>
									<li><a href="<?php echo $customer_manual; ?>"><i class="fa fa-phone"></i> Обзвон старых клиентов</a></li>
									<li><a href="<?php echo $segments_link; ?>"><i class="fa fa-bar-chart"></i> Настройка сегментации</a></li>
									<li><a href="<?php echo $actiontemplate; ?>"><i class="fa fa-envelope-o"></i> Шаблоны регулярных рассылок</a></li>	
									<li><a class="home_icon_style" href="<?php echo $coupon; ?>"><i class="fa fa-barcode"></i><span>Промокоды</span></a></li>
									<li><a class="home_icon_style parent"><i class="fa fa-cc"></i><span><?php echo $text_voucher; ?></span></a>
										<ul>
											<li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
											<li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
										</ul>
									</li>
								</ul>
							</li>
							
							<li id="sip_history"><a class="top" href="<?php echo $user_sip; ?>"><i class="fa fa-phone icon_menu"></i>Звонки</a></li>
							<li id="sms"><a class="top" href="<? echo $sms_link; ?>"><i class="fa fa-envelope-o icon_menu"></i>SMS</a></li>
							
							<li id="coupon"><a class="top" href="<?php echo $courier_face; ?>"><i class="fa fa-user-o icon_menu"></i>Курьер</a></li>
							
							<? /* <li id="opt"><a class="top"><i class="fa fa-pie-chart icon_menu"></i>Опт</a>
								<ul>
								<li><a href="<?php echo $optprices; ?>">Управление ценами</a></li>       
								<li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
								<li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>	   
								</ul>
							</li> */  ?>
							
							<li id="catalog"><a class="top"><i class="fa fa-bars icon_menu"></i><?php echo $text_catalog; ?></a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $category; ?>"><i class="fa fa-minus"></i><span><?php echo $text_category; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $product; ?>"><i class="fa fa-cubes"></i><span><?php echo $text_product; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $manufacturer; ?>"><i class="fa fa-barcode"></i><span><?php echo $text_manufacturer; ?></span></a></li>								
							</ul>
							</li>
								
							<li id="reports"><a class="top"><i class="fa fa-area-chart icon_menu"></i><?php echo $text_reports; ?></a>
								<ul>	
									<li><a href="<?php echo $report_reject; ?>"><i class="fa fa-bar-chart"></i> Причины отмен</a></li>
									<li><a href="<?php echo $report_marketplace; ?>"><i class="fa fa-bar-chart"></i> Маркетплейсы</a></li>
								<? /* ?>
									<li><a class="home_icon_style" href="<?php echo $mreport_ttnscan ?>"><i class="fa fa-area-chart"></i><span>Отчет по ТТН</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $mreport_needtocall ?>"><i class="fa fa-spinner"></i><span>Заказы в ожидании ответа</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $mreport_nopaid ?>"><i class="fa fa-spinner"></i><span>Заказы в ожидании оплаты</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $mreport_forgottencart ?>"><i class="fa fa-pencil-square-o"></i><span>Незавершенные заказы</span></a></li>
								<?	*/ ?>
									<li><a class="home_icon_style" href="<?php echo $mreport_minusscan ?>"><i class="fa fa-exclamation"></i><span>Проверка счетов</span></a></li>																		
									<li><a class="home_icon_style parent"><i class="fa fa-database"></i><span><?php echo $text_sale; ?></span></a>
										<ul>											
											<li><a href="<?php echo $report_adv_sale_order ?>">Универсальные отчеты</a></li>
											<li><a href="<?php echo $report_adv_product_purchased; ?>">Универсальный отчет по товарам</a></li> 
											<li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
											<li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
											<li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
											<li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
											<li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
										</ul>
									</li>
									<li><a class="home_icon_style parent"><i class="fa fa-cubes"></i><span><?php echo $text_product; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
											<li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
										</ul>
									</li>
									<li><a class="home_icon_style parent"><i class="fa fa-users"></i><span><?php echo $text_customer; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
											<li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
											<li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
											<li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
										</ul>
									</li>
									<li><a class="home_icon_style parent"><i class="fa fa-handshake-o"></i><span><?php echo $text_affiliate; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_affiliate_commission; ?>"><?php echo $text_report_affiliate_commission; ?></a></li>
											<li><a href="<?php echo $report_affiliate_statistics; ?>"><?php echo $text_report_affiliate_statistics; ?></a></li>
											<li><a href="<?php echo $report_affiliate_statistics_all; ?>"><?php echo $text_report_affiliate_statistics_all; ?></a></li>
										</ul>
									</li>
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


	<?php if ($this->config->get('config_country_id') == 176) { ?>
		<?php if ($this->user->getUserGroup() == 27) { ?>
			<div style="margin-top:20px; margin-bottom:20px; background-color:red; color:white; text-align:center; font-size:32px;">
				Українські замовлення не обробляються у цьому магазині з 14:00 07.05.2023!<br />
				Останнє замовлення, оформлене у цій адмінці: 315925<br />
				<small>Нова адмінка Кітчен-Профі Україна: <a style="color:white;" href="https://kitchen-profi.com.ua/admin/?xkey=754">https://kitchen-profi.com.ua/admin/?xkey=754</a></small><br />
				Слава Україні!
			</div>
		<?php } ?>
	<?php } ?>

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