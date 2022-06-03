<style>
	.admin_home{width:100%;background: #f3f3f3 url(/admin/view/image/bg_grey.png) repeat;}
	.admin_home tr td{text-align:center; padding-bottom:30px;}
	.admin_home tr td i{font-size:72px; color:#3a4247;}
	.admin_home tr td a{color:#3a4247;}
	div.admin_button {float:left; text-align:center; border: 1px solid #ededed; margin-right:10px; margin-bottom:10px; width: 120px;}
	div.admin_button_status {float:left; padding: 10px; text-align:center;margin-right:10px; margin-bottom:10px;border-radius: 2px;width: 105px;height: 95px;}
	div.admin_button_status a {font-size:11px;}
	.admin_button i{font-size:30px;opacity: 0.8;padding: 10px;}
	.admin_button_status i {font-size:30px;opacity: 0.8;}
	.admin_button i:hover, .admin_button_status i:hover {opacity: 1;}
	.admin_button a, .admin_button_status a {text-decoration:none; color:#FFF; }
	.admin_button.red {color:#f91c02;}
	.admin_button.red i {color:#f91c02;}
	/*
	.admin_button._green {background:#00ad07;}
	.admin_button._blue {background:#0054b3}
	.admin_button._yellow {background:#ffcc00}
	*/
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
									console.log(text);
									console.log(parseInt(text) == '0');
									if (parseInt(text) == '0'){
										swal("Ошибка!", "Такого заказа не существует!", "error");
										} else {								
										document.location.href = 'index.php?route=sale/order/update&order_id='+text+'&token=<? echo $token ?>';
									}								
								},
								error : function(e){
									console.log(e);
								}
							});																			
							} else {
							alert('Некорректный номер заказа');
						}
					}
				</script>
			</div>	
			
			<?
				$this->load->model('localisation/order_status');	
				$order_statuses = $this->model_localisation_order_status->getOrderStatuses(array('start' => 0, 'limit' => 200));
				$order_problem_statuses = $this->config->get('config_problem_order_status_id');
				$order_toapprove_statuses = $this->config->get('config_toapprove_order_status_id');
				
				$good_os = array();
				$problem_os = array();
				$toapprove_os = array();
				foreach ($order_statuses as $_status){
					
					if (in_array($_status['order_status_id'], $order_problem_statuses)){
						$problem_os[] = $_status;
					}
					
					if (in_array($_status['order_status_id'], $order_toapprove_statuses)){
						$toapprove_os[] = $_status;
					}
					
					if (!in_array($_status['order_status_id'], $order_toapprove_statuses) && !in_array($_status['order_status_id'], $order_problem_statuses))					
					$good_os[] = $_status;
				}
			?>
			
			<div style="clear:both;"></div>
			<h1 style="color:#ff5656"><a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=general_problems<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>">Требует моих действий <? echo $total_problem_orders; ?> заказов</a> <i class="fa fa-info ktooltip_click" style="color:#cccccc" title="Заказы с последней датой модификации не позже вчерашнего дня"></i></h1>
			<? foreach ($problem_os as $_status) { ?>
				<div class="admin_button_status" style="background: #<?php echo isset($_status['status_bg_color']) ? $_status['status_bg_color'] : ''; ?>; color: #<?php echo isset($_status['status_txt_color']) ? $_status['status_txt_color'] : ''; ?>;">
					<a href="index.php?route=sale/order&filter_order_status_id=<? echo $_status['order_status_id']; ?><? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>" style="color: #<?php echo isset($_status['status_txt_color']) ? $_status['status_txt_color'] : ''; ?>;">
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
			<div class="admin_button_status" style="background: #ff0000; color: #FFF">
				<a href="index.php?route=sale/order&filter_order_status_id=probably_cancel<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>" style="color: #FFF">
					<i class="fa fa-window-close" aria-hidden="true"  style="color: #FFF"></i>
					<br />
					<br />
					Необходимо отменить																
				</a>  
			</div>		
			
			<div class="admin_button_status" style="background: #cccccc; color: #696969">
				<a href="index.php?route=sale/order&filter_order_status_id=probably_close<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>" style="color: #696969">
					<i class="fa fa-check" aria-hidden="true"  style="color: #696969"></i>
					<br />
					<br />
					Необходимо закрыть																
				</a>  
			</div>		
			
			<div class="admin_button_status" style="background: #ff7f00; color: #FFF">
				<a href="index.php?route=sale/order&filter_order_status_id=probably_problem<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>" style="color: #FFF">
					<i class="fa fa-question-circle" aria-hidden="true"  style="color: #FFF"></i>
					<br />
					<br />
					Необходимо проверить																
				</a>  
			</div>	
			
			<div class="admin_button_status" style="background: #ff5656; color: #FFF">
				<a href="index.php?route=sale/order&filter_order_status_id=overdue<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>" style="color: #FFF">
					<i class="fa fa-hourglass-end" aria-hidden="true"  style="color: #FFF"></i>
					<br />
					<br />
					Просроченные заказы													
				</a>  
			</div>	
			
			<div style="clear:both;"></div>
			<h1 style="color:#ff5656"><a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=need_approve&token=<?php echo $token ?>">Надо бы подтвердить <? echo $total_toapprove_orders; ?> заказов</a> <i class="fa fa-info ktooltip_click" style="color:#cccccc" title="смена статуса на подтвержден - принесет вам больше денег!"></i></h1>
			<? foreach ($toapprove_os as $_status) { ?>
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
					<span class="home_tabs_style">ТТН</span>
				</a>  
			</div>
			
			<div class="admin_button _green">
				<a href="<?php echo $mreport_nopaid ?>">
					<i class="fa fa-spinner"></i><br />
					<span class="home_tabs_style">Ждем оплату</span>
				</a>  
			</div>
			
			<div class="admin_button">
				<a href="<?php echo $mreport_needtocall ?>">
					<i class="fa fa-spinner"></i><br />
					<span class="home_tabs_style">Ждем ответ</span>
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
					<span class="home_tabs_style">Счета</span>
				</a>  
			</div>
			
			<div style="clear:both;"></div>
			<h1>Фильтр заказов</h1>
			<? foreach ($good_os as $_status) { ?>
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
			<div class="clr"></div>
			
		</td>
		<td style="width:60%;vertical-align: top;">
			<div style="clear:both;"></div>			
			<div class="admin_button _green">
				<a href="<?php echo $order_url; ?>">
					<i class="fa fa-shopping-bag"></i><br />
					<span class="home_tabs_style">Заказы</span>
				</a>
			</div>
			<div class="admin_button <? if ($total_callbacks > 0) { ?>red<? } ?>">
				<a href="<?php echo $callback_url; ?>">
					<i class="fa fa-phone-square"></i><br />
					<span class="home_tabs_style">Обратный (<? echo $total_callbacks; ?>)</span>
				</a>
			</div>
			<div class="admin_button <? if ($total_waitlist_ready > 0) { ?>red<? } ?>">
				<a href="<?php echo $waitlist_url ?>">
					<i class="fa fa-hourglass-2"></i><br />
					<span class="home_tabs_style">Ожидание (<? echo $total_waitlist_ready; ?>)</span>
				</a>  
			</div>
			<div class="admin_button _yellow">
				<a href="<?php echo $fucked_link_url ?>">
					<i class="fa fa-minus-circle"></i><br />
					<span class="home_tabs_style">Незавершенные</span>
				</a>  
			</div>
			<div class="admin_button _green">
				<a href="<?php echo $customer_url ?>">
					<i class="fa fa-group"></i><br />
					<span class="home_tabs_style">Клиенты</span>
				</a>  
			</div>
			<div class="admin_button">
				<a href="<?php echo $sale_return_url; ?>">
					<i class="fa fa-mail-reply-all"></i><br />
					<span class="home_tabs_style">Возвраты</span>
				</a>  
			</div>
			<div class="admin_button _yellow">
				<a href="<?php echo $parties_url ?>">
					<i class="fa fa-list"></i><br />
					<span class="home_tabs_style">Партии</span>
				</a>  
			</div>
			<div style="clear:both;"></div>						
			
			<div class="latest delayed-load" data-route='common/home/getLastTwentyOrders'>
				
			</div>
		</td>
	</tr>
</table>	
