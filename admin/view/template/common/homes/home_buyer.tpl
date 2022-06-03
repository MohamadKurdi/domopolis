<style>
	.admin_home{width:100%;background: #f3f3f3 url(/admin/view/image/bg_grey.png) repeat;}
	.admin_home tr td{text-align:center; padding-bottom:30px;}
	.admin_home tr td i{font-size:72px; color:#3a4247;}
	.admin_home tr td a{color:#3a4247;}
	div.admin_button {float:left; text-align:center; border: 1px solid #ededed; margin-right:10px; margin-bottom:10px;}
	div.admin_button_status {float:left; padding: 20px; text-align:center;margin-right:10px; margin-bottom:10px;border-radius: 2px;width: 115px;height: 115px;}
	.admin_button i{font-size:56px;opacity: 0.8;padding: 20px;}
	.admin_button_status i {font-size:56px;opacity: 0.8;}
	.admin_button i:hover, .admin_button_status i:hover {opacity: 1;}
	.admin_button a, .admin_button_status a {text-decoration:none; color:#FFF; }
	/*.admin_button._red {background:#f91c02;}
	.admin_button._green {background:#00ad07;}
	.admin_button._blue {background:#0054b3}
	.admin_button._yellow {background:#ffcc00}*/
</style>
<table style="width:100%">
	<tr>
		<td style="width:40%;" valign="top">
			<div style="margin-top: 18px;  text-align:center;">	
				<input type="text" id="go_to_order" name="go_to_order" maxlength="6" style="line-height:40px;padding:0; text-align:center;">&nbsp;&nbsp;
				<a class="button" onclick="go_to_order()" style="padding:10px;">Перейти к заказу</a>
				<script>
					$(document).ready(function(){
						$('#go_to_order').keyup(function (e) {
							if (e.keyCode === 13) {
								go_to_order();
							}
						});			
					});				
					function go_to_order(){
						if ($('#go_to_order').val().length >= 5){
							$.ajax({
								url : 'index.php?route=sale/order/if_order_exists&order_id='+parseInt($('#go_to_order').val())+'&token=<? echo $token ?>',
								type : 'GET',
								dataType : 'text',
								success : function(text){
									if (text == 'n'){
										swal("Ошибка!", "Такого заказа не существует!", "error");
										} else {								
										document.location.href = 'index.php?route=sale/order/update&order_id='+text+'&token=<? echo $token ?>';
									}								
								}
							});																			
							} else {
							alert('Некорректный номер заказа');
						}
					}
				</script>
			</div>	
			<h1>Действия</h1>
			
			<div class="admin_button _green">
				<a href="<?php echo $order_url; ?>">
					<i class="fa fa-shopping-bag"></i><br />
					<span class="home_tabs_style">Заказы</span>
				</a>
			</div>
			<div class="admin_button _blue">
				<a href="<?php echo $waitlist_url ?>">
					<i class="fa fa-hourglass-2"></i><br />
					<span class="home_tabs_style">Лист ожидания</span>
				</a>  
			</div>
			<div class="admin_button _red">
				<a href="<?php echo $sale_return_url; ?>">
					<i class="fa fa-mail-reply-all"></i><br />
					<span class="home_tabs_style">Возвраты</span>
				</a>  
			</div>
			<div class="admin_button _yellow">
				<a href="<?php echo $parties_url ?>">
					<i class="fa fa-list"></i><br />
					<span class="home_tabs_style">Партии закупок</span>
				</a>  
			</div>
			
			<div style="clear:both;"></div>
			<h1>Фильтр заказов</h1>
			<?
				$this->load->model('localisation/order_status');									
				$order_statuses = $this->model_localisation_order_status->getOrderStatuses(array('start' => 0, 'limit' => 200));
			?>
			
			<? foreach ($order_statuses as $_status) { ?>
				<div class="admin_button_status" style="background: #<?php echo isset($_status['status_bg_color']) ? $_status['status_bg_color'] : ''; ?>; color: #<?php echo isset($_status['status_txt_color']) ? $_status['status_txt_color'] : ''; ?>;">
					<a href="index.php?route=sale/order&filter_order_status_id=<? echo $_status['order_status_id']; ?>&token=<?php echo $token ?>" style="color: #<?php echo isset($_status['status_txt_color']) ? $_status['status_txt_color'] : ''; ?>;">
						<? if ($_status['status_fa_icon']) { ?>
							<i class="fa <? echo $_status['status_fa_icon']; ?>" aria-hidden="true"  style="color: #<?php echo isset($_status['status_txt_color']) ? $_status['status_txt_color'] : ''; ?>"></i>
							<? } else { ?>
							<i class="fa fa-shopping-bag" aria-hidden="true"  style="color: #<?php echo isset($_status['status_txt_color']) ? $_status['status_txt_color'] : ''; ?>"></i>
						<? } ?>
						<br />
						<br />
						<? echo $_status['name']; ?>																	
					</a>  
				</div>
			<? } ?>
			<div style="clear:both;"></div>
			<h1>Отчеты</h1>
			
			<div class="admin_button _blue">
				<a href="<?php echo $mreport_ttnscan ?>">
					<i class="fa fa-area-chart"></i><br />
					<span class="home_tabs_style">Отчет по ТТН</span>
				</a>  
			</div>
			
			<div class="admin_button _green">
				<a href="<?php echo $mreport_nopaid ?>">
					<i class="fa fa-spinner"></i><br />
					<span class="home_tabs_style">Ожидание оплаты</span>
				</a>  
			</div>
			
			<div class="admin_button _red">
				<a href="<?php echo $mreport_needtocall ?>">
					<i class="fa fa-spinner"></i><br />
					<span class="home_tabs_style">Ожидание ответа</span>
				</a>  
			</div>
			
			
			<div class="admin_button _blue">
				<a href="<?php echo $mreport_forgottencart ?>">
					<i class="fa fa-pencil-square-o"></i><br />
					<span class="home_tabs_style">Незавершенные</span>
				</a>  
			</div>
			
			<div class="admin_button _green">
				<a href="<?php echo $mreport_minusscan ?>">
					<i class="fa fa-exclamation"></i><br />
					<span class="home_tabs_style">Проверка счетов</span>
				</a>  
			</div>
			
			<div style="clear:both;"></div>
		</td>
		<td style="width:60%;vertical-align: top;">
			<div class="latest">
				<div class="dashboard-heading"><?php echo $text_latest_10_orders; ?></div>
				<div class="dashboard-content">
					<table class="list">
						<thead>
							<tr>
								<td class="right"><?php echo $column_order; ?></td>
								<td class="left"><?php echo $column_customer; ?></td>
								<td class="left"><?php echo $column_status; ?></td>
								<td class="left"><?php echo $column_date_added; ?></td>
								<td class="right"><?php echo $column_total; ?></td>
								<td class="right"><?php echo $column_action; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php if ($orders) { ?>
								<?php foreach ($orders as $order) { ?>
									<tr>
										<td class="left"><?php echo $order['order_id']; ?></td>
										<td class="left"><?php echo $order['customer']; ?></td>
										<td class="left"><span class="status_color" style="background: #<?php echo isset($order['status_bg_color']) ? $order['status_bg_color'] : ''; ?>; color: #<?php echo isset($order['status_txt_color']) ? $order['status_txt_color'] : ''; ?>;">	<? if ($order['status_fa_icon']) { ?>
											<i class="fa <? echo $order['status_fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
										<? } ?><?php echo $order['status']; ?></span></td>
										<td class="left"><?php echo $order['date_added']; ?></td>
										<td class="right"><?php echo $order['total']; ?></td>
										<td class="right"><?php foreach ($order['action'] as $action) { ?>
											<a class="button" href="<?php echo $action['href']; ?>"><i class="fa fa-folder-open-o"></i></a>
										<?php } ?></td>
									</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>	
		</td>
	</tr>
</table>																														