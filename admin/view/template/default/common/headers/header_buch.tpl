<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
	<?php require_once(dirname(__FILE__).'/../header_head.tpl'); ?>
	<body>
		<div id="container">
			<div id="header">
				<div class="div1">
					<div class="div2">
						<?php if ($logged) { ?>
							<div style="float:left; color:#000; font-weight:700; padding-left:20px;"><i class="fa fa-user-o icon_header"></i><div style="display: inline-block;"><? echo $this->user->getUserFullName(); ?> (<? echo $this->user->getUserName(); ?>)<br /><? echo $this->user->getUserGroupName(); ?></div></div>
						<? } ?>
					</div>
				<div style="clear: both;"></div>
				</div>
				<?php if ($logged) { ?>  	
					
					<div id="menu">
						<img class="d_img" src="view/image/<? echo FILE_LOGO; ?>" style="float:left;" title="<?php echo $heading_title; ?>" onclick="location = '<?php echo $home; ?>'" />
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
								</ul>
							</li>
							<li id="stocks"><a  class="top" href="<?php echo $stocks; ?>"><i class="fa fa-cube icon_menu"></i>Остатки</a></li>
							<li id="waitlist"><a class="top" href="<?php echo $waitlist; ?>"><i class="fa fa-question icon_menu"></i>Лист ож.</a></li>
							<li id="parties"><a class="top" href="<?php echo $parties; ?>"><i class="fa fa-cubes icon_menu"></i>Партии</a></li>
							<li id="suppliers"><a class="top" href="<?php echo $suppliers; ?>"><i class="fa fa-book icon_menu"></i>Справочник поставщиков</a></li>
							<li id="return"><a class="top" href="<?php echo $return; ?>"><i class="fa fa-refresh icon_menu"></i><?php echo $text_return; ?></a></li>	
							<li id="reports"><a class="top"><i class="fa fa-area-chart icon_menu"></i><?php echo $text_reports; ?></a>
								<ul>				  		
									<li><a class="home_icon_style parent"><i class="fa fa-database"></i><span><?php echo $text_sale; ?></span></a>
										<ul>
											<li><a href="<?php echo $report_adv_sale_order ?>">Универсальный отчет</a></li>
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
							
							<li><a class="top" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link icon_menu"></i></b></a></li>
							
						</ul>
						<div style="clear: both;"></div>
					</div>
				<?php } ?>
			</div>																							