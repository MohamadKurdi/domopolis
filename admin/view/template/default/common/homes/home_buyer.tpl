<?php echo $header; ?>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">   
		<div class="content">
			<div id="template_bygroup">
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
							<div style="clear:both; height:10px;"></div>
							<div class="latest delayed-load" data-route='common/home/getLastTwentyOrders'></div>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>																														