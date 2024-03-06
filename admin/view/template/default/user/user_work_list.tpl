<?php echo $header; ?>
<style>
	a.button.active { color: #fff !important;background-color: #6A6A6A;text-decoration: none !important; }
	tr.hovered td {background-color:#faf9f1 !important;}
	tr.blue:hover td {background:#99CCFF !important; color:#FFF !important;}
	td.td_alert {background:#FF99CC !important;}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1>Учет рабочего времени и действий менеджеров</h1>				  					
		</div>
		<div class="clear:both"></div>
		<div class="content" style="padding:5px;">
			<div> 
				<? foreach ($periods as $period) { ?>
					<a class="button <? if ($current_period == $period['date']) { ?>active<? } ?>" href="<? echo $period['href']; ?>" style="margin-right:10px; margin-bottom:5px;"><? echo $period['name']; ?></a>
				<? } ?>
			</div>
			<div style="margin-top:10px;"> 
				<table class="list">					
					<? foreach ($stats as $group => $users) { ?>
						
						<tr class="blue">
							<td colspan="18" class="left" style="font-size:16px; background-color:#99CCFF!important; color:#FFF!important;"><? echo $group; ?></td>
						</tr>
						
						<tr class="hovered">
							<td class="left"></td>									
							<td class="center">
								<span class="ktooltip_hover" title="Время первого действия"><i class="fa fa-sign-in" aria-hidden="true"></i><br />вход</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Время последнего действия"><i class="fa fa-sign-out" aria-hidden="true"></i><br />выход</span>
							</td>		
							<td class="center">
								<span class="ktooltip_hover" title="Общее количество действий"><i class="fa fa-refresh"  aria-hidden="true"></i><br />действия</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover"  title="Количество отредактированных заказов"><i class="fa fa-cart-arrow-down"></i><br />ред. зак</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover"  title="Количество контроля качества заказов"><i class="fa fa-handshake-o"></i><br />ред. csi</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество отредактированных покупателей"><i class="fa fa-users"></i><br />ред. пок</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Входящие звонки"><i class="fa fa-phone" aria-hidden="true"></i><br />входящие</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Исходящие звонки"><i class="fa fa-phone" aria-hidden="true"></i><br />исходящие</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество полученных Дней Рождения"><i class="fa fa-birthday-cake" aria-hidden="true"></i><br />измен. др</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество ручных обзвонов"><i class="fa fa-phone" aria-hidden="true"></i><br />обзвон</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество отправленных писем"><i class="fa fa-envelope-o" aria-hidden="true"></i><br />отпр. mail</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество проблемных заказов"><i class="fa fa-question-circle"></i><br />пробл. зак</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество присвоенных заказов"><i class="fa fa-cart-arrow-down"></i><br />присв. зак</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество выполненных заказов"><i class="fa fa-shopping-basket" aria-hidden="true"></i><br />вып. зак</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество отмененных заказов"><i class="fa fa-window-close" aria-hidden="true"></i><br />отм. зак</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество подтвержденных заказов"><i class="fa fa-thumbs-up" aria-hidden="true"></i><br />подтв. зак</span>
							</td>
							<td class="center">
								<span class="ktooltip_hover" title="Количество обработанных заказов"><i class="fa fa-recycle" aria-hidden="true"></i><br />обраб. зак</span>
							</td>
						</tr>
						<? foreach ($users as $user) { ?>
							<tr>
								<td class="left">
									<? echo $user['user_name']; ?>
									<a href="<? echo $user['edit'] ?>" target='_blank'><i class="fa fa-edit"></i></a>
								</td>
								<td class="center"   >
									<? echo $user['worktime_start']; ?>
								</td>
								<td class="center">
									<? echo $user['worktime_finish']; ?>
								</td>
								<td class="center">
									<? echo $user['daily_actions']; ?>
								</td>
								<td class="center">
									<? echo $user['edit_order_count']; ?>
								</td>
								<td class="center">
									<? echo $user['edit_csi_count']; ?>
								</td>
								<td class="center">
									<? echo $user['edit_customer_count']; ?>
								</td>
								<td class="center">
									<? echo $user['inbound_call_count']; ?><br />
									<? echo $user['inbound_call_duration']; ?>
								</td>
								<td class="center">
									<? echo $user['outbound_call_count']; ?><br />
									<? echo $user['outbound_call_duration']; ?>
								</td>
								<td class="center">
									<? echo $user['edit_birthday_count']; ?>
								</td>
								<td class="center">
									<? echo $user['customer_manual_count']; ?>
								</td>
								<td class="center">
									<? echo $user['sent_mail_count']; ?>
								</td>
								<td class="center  <? if ($user['problem_order_count']) { ?>td_alert<? } ?>">
									<? echo $user['problem_order_count']; ?>
								</td>	
								<td class="center">
									<? echo $user['owned_order_count']; ?>
								</td>							
								<td class="center">
									<? echo $user['success_order_count']; ?>
								</td>
								<td class="center">
									<? echo $user['cancel_order_count']; ?>
								</td>
								<td class="center">
									<? echo $user['confirmed_order_count']; ?>
								</td>
								<td class="center">
									<? echo $user['treated_order_count']; ?>
								</td>								
							</tr>
						<? } ?>												
						
					<? } ?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?> 	