<?php echo $header; ?>
<div id="content" class="page-order-list">
	<style>
		span.get_ttn_info {cursor:pointer; display:inline-block; border-bottom:1px dashed black;}
		.ktooltip_product_hidden{display:none;}
		.tracker-order-info{background:#fff;width:100%!important;float:none!important}
		.tracker-order-info ul{padding-left:2px;padding-right:2px}
		.tracker-order-info ul li{display:inline-block;width:60px;margin:0 5px 5px 0;vertical-align:top;text-align:center;padding:3px 0 0;font-size:9px}
		.tracker-order-info ul li i{font-size:18px!important}
		.tracker-order-info ul li.undone{color:#7F7F7F}
		.tracker-order-info ul li.done{color:#7cc04b}
		@media screen and (max-width: 1450px) {
		select[name='filter_reject_reason_id']{
		
		max-width: 210px;
		}
		}
	</style>
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1 style="padding:3px 5px; border:2px solid #dbdbdb; border-radius:5px; background:<? if (!$filter_order_status_id) { ?>#dbdbdb; <? } else { ?>#FFF<? } ?>"><a style="color:#404040; text-decoration:none;" href="index.php?route=sale/order&token=<?php echo $token ?>">Заказы</a></h1>
			
			<h1 style="margin-left:10px; padding:3px 5px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'general_problems') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
			<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=general_problems<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a></h1>
			
			<h1 style="margin-left:10px; padding:3px 5px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'need_approve') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
			<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=need_approve&token=<?php echo $token ?>"><i class="fa fa-eur" aria-hidden="true"></i></a></h1>
			
			<h1 class="ktooltip_hover" title="Заказы, которые возможно, необходимо закрыть" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'probably_close') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
			<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=probably_close<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>"><i class="fa fa-check" aria-hidden="true"></i></a></h1>
			
			<h1 class="ktooltip_hover" title="Заказы, по которым требуется связь" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'flow_problems') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
			<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=flow_problems<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>"><i class="fa fa-phone-square" aria-hidden="true"></i></a></h1>
			
			<h1 class="ktooltip_hover" title="Заказы, которые возможно, необходимо отменить" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'probably_cancel') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
			<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=probably_cancel<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>"><i class="fa fa-window-close" aria-hidden="true"></i></a></h1>
			
			<h1 class="ktooltip_hover" title="Заказы, которые возможно имеют проблемы с доставкой" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'probably_problem') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
			<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=probably_problem<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a></h1>
			
			<h1 class="ktooltip_hover" title="Заказы, по которым истек срок обещанной доставки" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'overdue') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
			<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=overdue<? if ($this->user->getOwnOrders()) { ?>&filter_manager_id=<? echo $this->user->getID(); ?><? } ?>&token=<?php echo $token ?>"><i class="fa fa-hourglass-end" aria-hidden="true"></i></a></h1>
			
			<? if ($this->user->canEditCSI()) { ?>	
				
				<? foreach ($csi_filters as $csi_filter) { ?>
					
					<h1 class="ktooltip_hover" title="Заказы, которые необходимо оценить: <? echo $csi_filter['name']; ?>" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'need_csi_' . $csi_filter['order_status_id']) { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
					<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=need_csi_<? echo $csi_filter['order_status_id']; ?>&token=<?php echo $token ?>">CSI <i class="fa <? echo$csi_filter['status_fa_icon']; ?>" aria-hidden="true"></i></a></h1>
					
				<? } ?>
				
				<h1 class="ktooltip_hover" title="Заказы, по которым недозвон по CSI" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #ff5656; background: <? if ($filter_order_status_id == 'nbt_csi') { ?> #dbdbdb; <? } else { ?>#FFF<? } ?>">
				<a style="color:#ff5656; text-decoration:none;" href="index.php?route=sale/order&filter_order_status_id=nbt_csi&token=<?php echo $token ?>">CSI <i class="fa fa-deaf" aria-hidden="true"></i></a></h1>
				
			<? } ?>
			
			<div style="float:right;">
				
				<input type="text" id="go_to_order" name="go_to_order" maxlength="6" style="line-height:55px; font-size:24px; width:100px; padding:0; text-align:center; float:left;">
				<h1 class="ktooltip_hover" title="Перейти к заказу" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #6A6A6A;">
				<a style="color:#6A6A6A; text-decoration:none;" onclick="go_to_order()"><i class="fa fa-external-link-square" aria-hidden="true"></i></a></h1>
				
				<h1 class="ktooltip_hover" title="Дополнительные функции" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #6A6A6A;">
				<a style="color:#6A6A6A; text-decoration:none;" onclick="$('#additional_buttons').toggle();"><i class="fa fa-bars" aria-hidden="true"></i></a></h1>
				
				<? if ($this->user->getIsAV() /* || $this->user->canUnlockOrders() */) { ?>	
					<h1 class="ktooltip_hover" title="Удалить заказы" style="margin-left:10px; padding:3px 8px; border-radius:5px; border:2px solid #6A6A6A;">
					<a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" style="color:#6A6A6A; text-decoration:none;" ><i class="fa fa-window-close-o" aria-hidden="true"></i></a></h1>
				<? } ?>
			</div>
		</div>
		<div style="clear:both;"></div>
		<div style="display:none; text-align:right; padding:7px;" id="additional_buttons" >
			
			<table class="list">
				<tr>
					<td width="20%" class="center" style="padding:10px 5px;">
						<a style="border:2px solid #7F00FF; color:#7F00FF" class="button" onclick="window.open('index.php?route=report/export_xls/createOrderNowWhere&token=<? echo $token; ?>&date='+$('#order_to_deliveries_date').val());">Товары Германия</a>
					</td>
					<td width="30%" class="center" style="padding:10px 5px; color:#7F00FF">
						<select name="filter_order_store_id_to_cheque" id="filter_order_store_id_to_cheque" style="width:250px;font-size:16px; padding:6px 10px;" >
							<option value="*"></option>				
							<?php foreach ($order_stores as $order_store) { ?>
								<?php if ($order_store['store_id'] === $filter_order_store_id) { ?>
									<option value="<?php echo $order_store['store_id']; ?>" selected="selected"><?php echo $order_store['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_store['store_id']; ?>"><?php echo $order_store['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
						<a style="border:2px solid #7F00FF; color:#7F00FF" onclick="$('#form').attr('action', '<?php echo $invoice; ?>'); $('#form').attr('target', '_blank'); $('#form').submit();" class="button"><?php echo $button_invoice; ?></a>
					</td>	
					<td width="60%" class="center" style="padding:10px 5px;">
						<input type="text" style="font-size:16px; width:100px; padding:6px 10px; text-align:center;" class="date" id="order_to_deliveries_date_to_cheque" />&nbsp;
						<a style="border:2px solid #7F00FF; color:#7F00FF" class="button" onclick="window.open('index.php?route=catalog/parties/getOnDateCheques&token=<? echo $token; ?>&date='+$('#order_to_deliveries_date_to_cheque').val()+'&store_id='+$('#filter_order_store_id_to_cheque').attr('value'))">Все чеки на доставку</a> 
					</td>									
				</tr>
				
				<?php if ($this->config->get('config_country_id') == 176) { ?>	
					<tr>
						<td width="25%" class="center" style="padding:10px 5px;">
							<input type="text" style="font-size:16px; width:100px; padding:6px 10px; text-align:center;" class="date" id="order_to_deliveries_date" />&nbsp;

							<a style="border:2px solid #7F00FF; color:#7F00FF" class="button" onclick="window.open('index.php?route=report/export_xls/createOrderDeliveryMoscow&token=<? echo $token; ?>&date='+$('#order_to_deliveries_date').val());">Заявка Москва</a>

							<a style="border:2px solid #7F00FF; color:#7F00FF" class="button" onclick="window.open('index.php?route=report/export_xls/createOrderDeliveryMoscow&shipping_country_id=20&token=<? echo $token; ?>&date='+$('#order_to_deliveries_date').val());">Заявка Беларусь</a>

						</td>
						<td width="25%" class="center" style="padding:10px 5px;">

							<a style="border:2px solid #7F00FF; color:#7F00FF" href="<?php echo HTTPS_CATALOG . 'yamarket-partner-api/acts/receptiontransferact'?>" target="_blank" class="button"><i class="fa fa-yoast"></i> Маркет АКТ Приема-передачи</a>

						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<div style="clear:both;"></div>
		<div class="content">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 100%;">
					<tr class="filter">
						<td colspan="2">
							<input title="ID / Партия" placeholder="ID / Партия" type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="width:100px;" />
							&nbsp;&nbsp;<input type="text" placeholder="Имя / ID / Email" name="filter_customer" value="<?php echo $filter_customer; ?>" />
						</td>
						
						<td>
							<input type="text" placeholder="Дисконт. карта" name="filter_discount_card" value="<?php echo $filter_discount_card; ?>" />
						</td>
						<td>
							<select name="filter_order_store_id" style="width:180px;">
								<option value="*" <?php if (is_null($filter_order_store_id)) { ?>selected="selected"<? } ?>>Магазин, страна</option>								
								<?php foreach ($order_stores as $order_store) { ?>
									<?php if (!is_null($filter_order_store_id) && $order_store['store_id'] == $filter_order_store_id) { ?>
										<option value="<?php echo $order_store['store_id']; ?>" selected="selected"><?php echo $order_store['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_store['store_id']; ?>"><?php echo $order_store['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>
						<td>							
							
						</td>
						
						<td colspan="2">							
							<select name="filter_manager_id" class="">
								<option value="*">Менеджер</option>
								<?php foreach ($all_managers as $manager) { ?>
									<?php if ($manager['user_id'] == $filter_manager_id) { ?>
										<option value="<?php echo $manager['user_id']; ?>" selected="selected"><?php echo $manager['realname']; ?> (<?php echo $manager['user_id']; ?>)</option>
										<?php } else { ?>
										<option value="<?php echo $manager['user_id']; ?>"><?php echo $manager['realname']; ?> (<?php echo $manager['user_id']; ?>)</option>
									<?php } ?>
								<?php } ?>
							</select>
							<div style="margin-top:3px;"><input id="only_my_orders" type="checkbox" class="checkbox" name="only_my_orders" <? if ($filter_manager_id == $this->user->getID()){ ?> checked="checked" <? } ?> /><label for="only_my_orders">Только мои заказы</label></div>
						</td>
						
					</tr>
					<tr class="filter">
						<td style="color: #999;" colspan="2">
							<input type="text" name="filter_date_added" placeholder="Оформлен от" value="<?php echo $filter_date_added; ?>" size="12" class="date" /> <input type="text" name="filter_date_added_to" placeholder="Оформлен до" value="<?php echo $filter_date_added_to; ?>" size="12" class="date" /> 
						</td>
						<td>
							<input type="text" name="filter_date_modified" placeholder="Изменен" value="<?php echo $filter_date_modified; ?>" size="12" class="date" style="text-align: left; width:160px;" /> 
						</td>
						<td>
							<input type="text" name="filter_date_delivery" placeholder="Дата доставки" value="<?php echo $filter_date_delivery; ?>" size="12" class="date" style="text-align: left; width:160px;" />
						</td>
						
						<td colspan="2">						
							<select name="filter_order_status_id" class="">
								<option value="*">Фильтр заказов по статусу</option>
								
								<?php if ($filter_order_status_id == '0') { ?>
									<option value="0" selected="selected"><?php echo $text_missing; ?></option>
									<?php } else { ?>
									<option value="0"><?php echo $text_missing; ?></option>
								<?php } ?>
								
								<?php if ($filter_order_status_id == 'all_except_success') { ?>
									<option value="all_except_success" selected="selected">Все, кроме выполненных</option>
									<?php } else { ?>
									<option value="all_except_success">Все, кроме выполненных</option>
								<?php } ?>
								
								<?php if ($filter_order_status_id == 'all_except_closed') { ?>
									<option value="all_except_closed" selected="selected">Все, кроме закрытых</option>
									<?php } else { ?>
									<option value="all_except_closed">Все, кроме закрытых</option>
								<?php } ?>
								
								<option value="*"> --- Трекинг отмен --- </option>
								<?php if ($filter_order_status_id == 'cancel_with_wait_for_payment') { ?>
									<option value="cancel_with_wait_for_payment" selected="selected">Отмена при ожидании оплаты</option>
									<?php } else { ?>
									<option value="cancel_with_wait_for_payment">Отмена при ожидании оплаты</option>
								<?php } ?>
								
								<?php if ($filter_order_status_id == 'cancel_with_payment') { ?>
									<option value="cancel_with_payment" selected="selected">Отмена после оплаты</option>
									<?php } else { ?>
									<option value="cancel_with_payment">Отмена после оплаты</option>
								<?php } ?>
								
								<?php if ($filter_order_status_id == 'cancel_with_process') { ?>
									<option value="cancel_with_process" selected="selected">Отмена при выполнении</option>
									<?php } else { ?>
									<option value="cancel_with_process">Отмена при выполнении</option>
								<?php } ?>
								
								<option value="*"> --- Умные отборы --- </option>
								<?php if ($filter_order_status_id == 'birthday_discount_used') { ?>
									<option value="birthday_discount_used" selected="selected">Скидка из SMS на день рождения</option>
									<?php } else { ?>
									<option value="birthday_discount_used">Скидка из SMS на день рождения</option>
								<?php } ?>
								
								<? if ($this->user->canEditCSI()) { ?>
									<option value="*"> --- CSI Прозвон --- </option>
									
									<? foreach ($csi_filters as $csi_filter) { ?>
										
										<?php if ($filter_order_status_id == 'need_csi_' . $csi_filter['order_status_id'] ) { ?>
											<option value="need_csi_<? echo $csi_filter['order_status_id']; ?>" selected="selected">Прозвон CSI: <? echo $csi_filter['name']; ?></option>
											<?php } else { ?>
											<option value="need_csi_<? echo $csi_filter['order_status_id']; ?>">Прозвон CSI: <? echo $csi_filter['name']; ?></option>
										<?php } ?>
										
									<? } ?>
									
								<? } ?>
								
								<option value="*"> --- CSI Статистика --- </option>
								<?php if ($filter_order_status_id == 'has_csi') { ?>
									<option value="has_csi" selected="selected">CSI просмотр</option>
									<?php } else { ?>
									<option value="has_csi">CSI просмотр</option>
								<?php } ?>
								
								<?php if ($filter_order_status_id == 'nbt_csi') { ?>
									<option value="nbt_csi" selected="selected">CSI недозвон</option>
									<?php } else { ?>
									<option value="nbt_csi">CSI недозвон</option>
								<?php } ?>
								
								<option value="*"> --- Действия --- </option>
								<?php if ($filter_order_status_id == 'need_approve') { ?>
									<option value="need_approve" selected="selected">Требующие подтверждения</option>
									<?php } else { ?>
									<option value="need_approve">Требующие подтверждения</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'general_problems') { ?>
									<option value="general_problems" selected="selected">Все проблемные заказы</option>
									<?php } else { ?>
									<option value="general_problems">Все проблемные заказы</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'flow_problems') { ?>
									<option value="flow_problems" selected="selected">Требующие связи</option>
									<?php } else { ?>
									<option value="flow_problems">Требующие связи</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'probably_close') { ?>
									<option value="probably_close" selected="selected">Возможно, необходимо закрыть</option>
									<?php } else { ?>
									<option value="probably_close">Возможно, необходимо закрыть</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'probably_cancel') { ?>
									<option value="probably_cancel" selected="selected">Возможно, необходимо отменить</option>
									<?php } else { ?>
									<option value="probably_cancel">Возможно, необходимо отменить</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'overdue') { ?>
									<option value="overdue" selected="selected">Просроченные текущие</option>
									<?php } else { ?>
									<option value="overdue">Просроченные текущие</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'overdue_complete') { ?>
									<option value="overdue_complete" selected="selected">Просроченные выполненные</option>
									<?php } else { ?>
									<option value="overdue_complete">Просроченные выполненные</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'overdue_cancel') { ?>
									<option value="overdue_cancel" selected="selected">Просроченные отмененные</option>
									<?php } else { ?>
									<option value="overdue_cancel">Просроченные отмененные</option>
								<?php } ?>
								<?php if ($filter_order_status_id == 'probably_problem') { ?>
									<option value="probably_problem" selected="selected">Возможно, проблема с доставкой</option>
									<?php } else { ?>
									<option value="probably_problem">Возможно, проблема с доставкой</option>
								<?php } ?>
								<option value="*"> --- По статусам --- </option>
								<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>					
						</td>
						<td colspan="2" >
							<select name="filter_reject_reason_id" class="">
								<option value="*">Причины отмен заказа</option>
								<?php foreach ($reject_reasons as $reject_reason) { ?>
									<?php if ($reject_reason['reject_reason_id'] == $filter_reject_reason_id) { ?>
										<option value="<?php echo $reject_reason['reject_reason_id']; ?>" selected="selected"><?php echo $reject_reason['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $reject_reason['reject_reason_id']; ?>"><?php echo $reject_reason['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr class="filter">
						<td colspan="2" style="color: #999;">
							
							<div style="margin-bottom:10px;">
								<input style="max-width:120px;" type="text" placeholder="Сумма заказа" name="filter_total" value="<?php echo $filter_total; ?>" />
								
								<select name="filter_payment_method" class="" style="width:180px;">
									<option value="*">Способ оплаты</option>
									<?php foreach ($payment_methods as $payment_method) { ?>
										<?php if ($filter_payment_method == $payment_method['code']) { ?>
											<option value="<?php echo $payment_method['code']; ?>" selected="selected"><?php echo $payment_method['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								
							</div>	
							
							<div>
								<input type="hidden" id="filter_product_id" name="filter_product_id" value="<?php echo $filter_product_id; ?>" />
								<input type="text" id="filter_product_product" name="product" value="<?php echo $filtered_product['name']; ?>" style="width:350px;" />
								<br /><b>ID</b>&nbsp;<span id="filter_product_id_id"><?php echo $filtered_product['product_id']; ?></span>&nbsp;&nbsp;
								<b>SKU</b>&nbsp;<span id="filter_product_model"><?php echo $filtered_product['model']; ?></span>&nbsp;&nbsp;
								<b>EAN</b>&nbsp;<span id="filter_product_ean"><?php echo $filtered_product['ean']; ?></span>&nbsp;&nbsp;
								<span onclick="$('#filter_product_product').val(''); $('#filter_product_id').val(''); filter();" style="border-bottom:1px dashed #999; cursor:pointer;">очистить</span>
							</div>
						</td>
						<td>
							
							<select name="filter_affiliate_id" class="" style="max-width:180px;">
								<option value="*">Партнер / Источник</option>
								<?php foreach ($all_affiliates as $affiliate) { ?>
									<?php if ($filter_affiliate_id == $affiliate['affiliate_id']) { ?>
										<option value="<?php echo $affiliate['affiliate_id']; ?>" selected="selected"><?php echo $affiliate['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $affiliate['affiliate_id']; ?>"><?php echo $affiliate['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
							<div style="margin-top:10px;">	
								<input title="Источник" type="text" placeholder="*источник*" name="filter_referrer" value="<?php echo $filter_referrer; ?>" size="15" style="text-align: left; width:160px;" />
							</div>
							
							<div style="margin-top:10px;">								
								<input type="text" name="filter_ttn" placeholder="TTH" value="<?php echo $filter_ttn; ?>" size="25" style="text-align: left; width:120px;" />
							</div>
						</td>
						<td>
							<div>
								<select name="filter_shipping_method" class="" style="max-width:180px;">
									<option value="*">Способ доставки</option>
									<?php foreach ($shipping_methods as $shipping_method) { ?>
										<?php if ($filter_shipping_method == $shipping_method['code']) { ?>
											<option value="<?php echo $shipping_method['code']; ?>" selected="selected"><?php echo $shipping_method['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $shipping_method['code']; ?>"><?php echo $shipping_method['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>	
							
							<div style="margin-top:10px;">	
								<select name="filter_courier_id" style="width:180px;">
									<option value="*">Курьер</option>
									<?php foreach ($all_couriers as $courier) { ?>
										<?php if ($courier['user_id'] == $filter_courier_id) { ?>
											<option value="<?php echo $courier['user_id']; ?>" selected="selected"><?php echo $courier['realname']; ?> (<?php echo $courier['user_id']; ?>)</option>
											<?php } else { ?>
											<option value="<?php echo $courier['user_id']; ?>"><?php echo $courier['realname']; ?> (<?php echo $courier['user_id']; ?>)</option>
										<?php } ?>
									<?php } ?>
								</select>
								<div style="margin-top:3px;"><input id="only_my_orders_courier" type="checkbox" class="checkbox" name="only_my_orders_courier" <? if ($filter_courier_id == $this->user->getID()){ ?> checked="checked" <? } ?> /><label for="only_my_orders_courier">Только мои заказы</label></div>
							</div>
							
							<div style="margin-top:10px;">	
								<select name="filter_courier_status" class="">
									<option value="*">Курьерская служба</option>
									<?php foreach ($courier_statuses as $courier_status) { ?>
										<?php if ($filter_courier_status == $courier_status) { ?>
											<option value="<?php echo $courier_status; ?>" selected="selected"><?php echo $courier_status; ?></option>
											<?php } else { ?>
											<option value="<?php echo $courier_status; ?>"><?php echo $courier_status; ?></option>
										<?php } ?>
									<?php } ?>
								</select>&nbsp;<span class="ktooltip_hover" data-tooltip-content="#courier-html-content"><i class="fa fa-question-circle" aria-hidden="true" style="font-size:24px;"></i></span>
							</div>						
							
							<div style="display:none;" >
								<div id="courier-html-content">
									<b>Новый</b> - только заведена заявка, заказ еще не сдан в курьерскую службу<br />
									<b>На сборку</b> - прозвонили, согласовали дату, ждут сдачи на склад<br />
									<b>На выяснении</b> - не принят/не сдан/не согласован<br />
									<b>Отказ созвон</b> - клиент не подтверждает доставку или я отозвал<br />
									<b>Отказ доставка</b> - отказались на адресе<br />
									<b>На складе</b> - сдан/принят, ждет доставки, дата планируемой доставки история прозвонов в крайнем правом столбце<br />
									<b>В доставке</b> - доставляется, дата  в крайнем правом столбце<br />
									<b>Отказ доставка</b> - отказ на адресе, клиент не вышел на связь с курьером после предварительного подтверждения доставки<br />
									<b>В кассе</b> - доставлен, курьер сдал деньги в кассу<br />
									<b>Прошел</b> - попал в отчет курьерской службы передо мной	каждая последующая доставка заказа после отказ созвона, <br />отказ доставки или довоз следующей части, <br />оформляется новой заявкой с единицей, двойкой и т.д.
								</div>
							</div>
						</td>
						<td colspan="2" class="input_aling">
							<div style="margin-top:5px;">
								<input id="checkbox_9" class="checkbox" type="checkbox" name="filter_urgent" <? if ($filter_urgent) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_9" style="color:red;"><i class="fa fa-space-shuttle" style="padding: 3px 3px;background-color: red;color: #FFF;"></i>&nbsp;Срочный заказ</label>								
							</div>
							
							<div style="margin-top:8px;">
								<input id="checkbox_10" class="checkbox" type="checkbox" name="filter_urgent_buy" <? if ($filter_urgent_buy) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_10" style="color:#7F00FF;"><i class="fa fa-amazon" style="padding: 3px 5px;background-color: #7F00FF;color: #FFF;"></i>&nbsp;Приоритетная закупка</label>
							</div>
							
							<div style="margin-top:8px;">
								<input id="checkbox_15" class="checkbox" type="checkbox" name="filter_preorder" <? if ($filter_preorder) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_15" style="color:#000;"><i class="fa fa-question" style="padding: 3px 5px;background-color: #000;color: #FFF;"></i>&nbsp;Уточнить цену и нал</label>
							</div>
							
							<div style="margin-top:8px;">
								<input id="checkbox_11" class="checkbox" type="checkbox" name="filter_wait_full" <? if ($filter_wait_full) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_11" style="color:#85B200;"><i class="fa fa-th-list" style="padding: 3px 5px;background-color: #85B200;color: #FFF;"></i>&nbsp;Клиент ждет полную комплектацию</label>
							</div>
							
							<?php if ($this->config->get('config_special_logistics_enable')) { ?>
							<div style="margin-top:8px;">
								<input id="checkbox_12" class="checkbox" type="checkbox" name="filter_ua_logistics" <? if ($filter_ua_logistics) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_12" style="color:#005BBB;"><i class="fa fa-bus" style="padding: 3px 5px;background-color: #005BBB;color: #FFF;"></i>&nbsp;Специальная логистика</label>
							</div>
							<?php } ?>
						</td>
						<td class="input_aling">	
							
							<div style="margin-top:5px;">
								<input id="checkbox_14" class="checkbox" type="checkbox" name="filter_pwa" <? if ($filter_pwa) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_14" style="color:#7F00FF;"><i class="fa fa-download" style="padding: 3px 3px;background-color: #7F00FF;color: #FFF;"></i>&nbsp;PWA/APP Заказы</label>								
							</div>
							
							<?php if ($this->config->get('config_country_id') == 176) { ?>
								<div style="margin-top:5px;">
									<input id="checkbox_22" class="checkbox" type="checkbox" name="filter_yam" <? if ($filter_yam) { ?>checked="checked"<? } ?> value="1" /> 
									<label for="checkbox_22" style="color:#cf4a61"><i class="fa fa-yoast" style="padding: 3px 3px;background-color: #cf4a61;color: #FFF;"></i>&nbsp;Я.Маркет заказы</label>								
								</div>

								<div style="margin-top:5px;">
									<a style="display:inline-block; float:left; margin-right:5px;" href="<?php echo HTTPS_CATALOG . 'yamarket-partner-api/acts/receptiontransferact'?>" target="_blank" class="button"><i class="fa fa-yoast"></i> АКТ ПП</a>


									<input type="text" name="filter_yam_id" placeholder="Ya.Market ID" value="<?php echo $filter_yam_id; ?>" size="25" style="text-align: left; width:120px; display:inline-block; float:left;" />
								</div>
							<?php } ?>
							
						</td>
					</tr>
					<tr>
						<td colspan="7" align="right" style="padding-right:10px;"><a onclick="filter(false);" class="button"><?php echo $button_filter; ?></a>&nbsp;<a onclick="filter(true);" class="button">CSV</a></td>
					</tr>
				</table>
				<div class="filter_bord"></div>
				<table class="list">
					<thead>
						<tr>
							<? if ($this->user->getIsAV() || $this->user->canUnlockOrders()) { ?>	
								<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<? } ?>
							<td class="right" width="120px" style="max-width:120px; width:120px;"><?php if ($sort == 'o.order_id') { ?>
								<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>">ID / Партия</a>
								<?php } else { ?>
								<a href="<?php echo $sort_order; ?>">ID / Партия</a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'customer') { ?>
								<a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>">Имя / ID / Email</a>
								<?php } else { ?>
								<a href="<?php echo $sort_customer; ?>">Имя / ID / Email</a>
							<?php } ?></td>
							<td>Товары</td>
							<td>Инфо</td>
							<td class="left" width="150px" style="width:150px; max-width:150px;"><?php if ($sort == 'status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>">Статус / причина отказа</a>
							<?php } ?>
							</td>
							<td class="left"><?php if ($sort == 'manager_id') { ?>
								<a href="<?php echo $sort_manager_id; ?>" class="<?php echo strtolower($order); ?>">Люди</a>
								<?php } else { ?>
								<a href="<?php echo $sort_manager_id; ?>">Люди</a>
							<?php } ?>
							</td>

							<?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
							<td class="right">
								Рентабельность
							</td>
							<?php } ?>

							<td class="right">
								<?php if ($sort == 'o.total') { ?>
									<a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
									<?php } else { ?>
									<a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								Скидки
							</td>
							<td class="left">							
								<?php if ($sort == 'o.date_added') { ?>
									<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><i class="fa fa-plus"></i></a>
									<?php } else { ?>
									<a href="<?php echo $sort_date_added; ?>"><i class="fa fa-plus"></i></a>
								<?php } ?>
								/ <?php if ($sort == 'o.date_modified') { ?>
									<a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><i class="fa fa-edit"></i></a>
									<?php } else { ?>
									<a href="<?php echo $sort_date_modified; ?>"><i class="fa fa-edit"></i></a>
								<?php } ?>				
							</td>            
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($orders) { ?>
							<?php foreach ($orders as $order) { ?>
								<tr>
									<? if ($this->user->getIsAV() || $this->user->canUnlockOrders()) { ?>	
										<td style="text-align: center; color: #<?php echo $order['status_txt_color']; ?>;"><?php if ($order['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
											<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
										<?php } ?></td>
									<? } ?>
									<td class="right" width="120px" style="max-width:120px; width:120px; color: #<?php echo $order['status_txt_color']; ?>;" aria-label="ID / Партия">
										
										<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:18px;">
											<?php echo $order['order_id']; ?>&nbsp;<span style="font-size:12px;" class="add2ticket" data-query="object=order&object_value=<?php echo $order['order_id']; ?>"></span>
										</div>
										
										
										<? if ($order['yam'] && $order['yam_id']) { ?>	
											<div style="margin-bottom:5px; margin-top:5px; padding:4px 7px; font-weight:400; font-size:16px; color:#cf4a61;" >
												<?php echo $order['yam_id']; ?> <i class="fa fa-yoast" aria-hidden="true"></i> 
											</div>											
										<?php } ?>
										
										<? if ($order['preorder']) { ?>
											<div><div style="white-space: nowrap; font-size:10px; display:inline-block; margin-top:4px; padding:3px; background-color:#000; color:#fff;"><b>
											<i class="fa fa-question-circle" aria-hidden="true"></i></b> уточнить цену</b></div></div>
									<? } ?>
									
									<? if ($order['first_referrer']) { ?>											
										<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; <? if ($order['is_marketplace']) { ?>background:#ff5656; color:#FFF;<? } else { ?>background:#e6e9f3; color:#696969;<? } ?>" >
											<i class="fa fa-mouse-pointer" aria-hidden="true"></i> <? echo $order['first_referrer']; ?>
										</div>
									<? } ?>
									
									<? if ($order['affiliate']) { ?>											
										<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; background:#aaff56; color:#696969;" >
											<i class="fa fa-chevron-circle-right" aria-hidden="true"></i> <? echo $order['affiliate']['firstname'] . ' ' . $order['affiliate']['lastname']; ?>
										</div>																						
									<? } ?>
									
									<? if ($order['pwa']) { ?>											
										<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; background:#7F00FF; color:#FFF;" >
											<i class="fa fa-chevron-circle-right" aria-hidden="true"></i> PWA/APP
										</div>																						
									<? } ?>
									
									<? if ($order['yam']) { ?>											
										<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; background:#cf4a61; color:#FFF;" >
											<i class="fa fa-yoast" aria-hidden="true"></i> Я.Маркет
										</div>	
										
									<? } ?>
									
									<? if ($order['can_get_csi'] && !$order['yam']) { ?>
										<? if (isset($order['customer_info']['csi_reject']) && $order['customer_info']['csi_reject']) { ?>
											<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:10px; background-color:#F96E64; display:inline-block; color:#FFF">
												<i class="fa fa-user"></i> CSI: отказ
											</div>
										<? } ?>
										<? if (isset($order['csi_reject']) && $order['csi_reject']) { ?>
											<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:10px; background-color:#F96E64; display:inline-block; color:#FFF">
												<i class="fa fa-cart-arrow-down"></i> CSI: отказ
											</div>
											<? } elseif ($order['csi_average'] > 0) { ?>
											<div style="text-align:right;"><div style="display:inline-block;" class="rateYo" data-rateyo-rating="<? echo $order['csi_average']; ?>"></div></div>
											<? } elseif ($order['nbt_csi']) { ?>
											<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:10px; background-color:#F96E64; display:inline-block; color:#FFF">
												<i class="fa fa-deaf" aria-hidden="true"></i> CSI: недозвон
											</div>												
											<? } else { ?>
											<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:10px; background-color:#F96E64; display:inline-block; color:#FFF">
												<i class="fa fa-cart-arrow-down"></i> CSI: не задан
											</div>
										<? } ?>
										<? if ($order['orders_without_csi']) { ?>								
											<div style="margin-top:5px; padding:4px 7px; font-weight:400; font-size:10px; background-color:#F96E64; display:inline-block; color:#FFF">
												<i class="fa fa-list-ul" aria-hidden="true"></i> <a href="<? echo $order['orders_without_csi_fltr']; ?>" style="color:#FFF">Еще заказы +<? echo (count($order['orders_without_csi']) - 1); ?></a>
											</div>
										<? } ?>
									<? } ?>
									
									<? if ($order['parties']) { ?>
										<div><? $_c = 1; foreach ($order['parties'] as $_partie) { ?>
											<div><b style="font-size:10px;"><? echo $_c; ?>:(<a style="color:#<? echo $order['status_txt_color']; ?>" href="<?php echo $_partie['href']; ?>"><?php echo $_partie['part_num']; ?></a>)</b></div>
										<? $_c++; } ?>
										</div>
									<? } ?>
									
									<?php if (!$order['yam']) { ?>
										<? if ($order['is_opt']) { ?>			    
											<span style="display:block; margin-top:5px; font-size:10px;">опт</span>				
											<? } else { ?>			  
											<span style="display:block; margin-top:5px; font-size:10px;">розница</span>				
										<? } ?>
									<?php } ?>
								</span>
							</td>
							<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Имя / ID / Email">
								<? if ($order['customer_has_birthday']) { ?>
									<i style="font-size:20px; color:#7CC04B;" class="fa fa-gift ktooltip_hover" title="День рождения <? echo $order['customer_info']['birthday_date'] . '.' . $order['customer_info']['birthday_month']; ?>" aria-hidden="true"></i>&nbsp;
								<? } ?>
								
								
								<? if ($order['is_mudak']) { ?>
									<span style="background:white; color:red; padding:3px;"><i class='fa fa-ambulance' style='color:red; font-size:16px;'></i>&nbsp;&nbsp;
									<? } ?>	
									<a href="<? echo $order['customer_href']; ?>" style="font-size:16px;" target="_blank"><?php echo $order['customer']; ?></a>&nbsp;<span class="add2ticket" data-query="object=order&object_value=<?php echo $order['order_id']; ?>"></span>&nbsp;<span><a href="<? echo $order['customer_pl']; ?>" target="_blank"><i class="fa fa-envelope" aria-hidden="true"></i></a></span>									
									
									<?php if ($order['yam'] && $order['yam_fake']) { ?>
										<br />
										<span style="display:inline-block;padding:2px 3px; background:#F96E64; color:#fff; font-size:14px; margin:3px;"><i class="fa fa-exclamation-triangle"></i> ТЕСТОВЫЙ ЗАКАЗ</span>
									<?php } ?>
									
									<br />
									<span style="display:inline-block;padding:2px 3px; font-size:14px; margin:3px; <?php if ($order['total_customer_orders'] == 1) { ?>background:#ff5656; color:#FFF;<?php } else { ?>background:#aaff56; color:#696969;<?php } ?>"><? echo $order['total_customer_orders']; ?> 
									<?php echo $order['total_customer_orders_txt']; ?></span>
									
									<?php if ($order['total_customer_orders'] > 1) { ?>
										<a href="<? echo $order['total_customer_orders_a']; ?>">фильтр</a>					
										<?php } else { ?>
									<?php } ?>
									
									<?php if (!$order['yam']) { ?>
										<br />
										<span style="font-size:10px; line-height:13px; <?php if ($order['email_bad']) { ?>color:#ff5656<?php } else { ?>color:#02760e<? } ?>">
											<? echo $order['email']; ?>
											<?php if ($order['email_bad']) { ?><i class="fa fa-exclamation-triangle" style="color:#ff5656"></i><?php } ?>
											<?php if (!$order['email_bad']) { ?><i class="fa fa-check-circle" style="color:#02760e"></i><?php } ?>
										</span>
										<br />
									<?php } ?>
									
									<?php if (!$order['yam']) { ?>
										<?php if ($order['telephone']) { ?>
											<span style="font-size:14px; line-height:14px;"><?php echo $order['telephone']; ?>
												<span class='click2call' data-phone="<?php echo $order['telephone']; ?>" <? if ($order['shipping_city'] && $order['current_time']  && !$order['can_call_now']) { ?>style="color:#cf4a61;"<? } ?>></span>
											<? } ?>
											<?php if ($order['fax']) { ?>
												<br /><span style="font-size:14px; line-height:14px;"><?php echo $order['fax']; ?>
													<span class='click2call' data-phone="<?php echo $order['fax']; ?>" <? if ($order['shipping_city'] && $order['current_time']  && !$order['can_call_now']) { ?>style="color:#cf4a61;"<? } ?>></span>
													
													<? if ($order['faxname']) { ?>
														<br /><span style="font-size:11px; line-height:13px; display:inline-block; padding:3px; color:#FFF; background-color:grey; margin-bottom:4px;"><?php echo $order['faxname']; ?></span>
													<? } ?>
													
												<? } ?>
											<?php } ?>
											<?php if ($order['shipping_country'] || $order['shipping_city']) { ?>
												<div style="font-size:12px; line-height:14px;">
													<span> 
														<?php if ($order['shipping_country_info']) { ?>
															<?php if ($this->config->get('config_admin_flags_enable')) { ?>
																<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($order['shipping_country_info']['iso_code_2']) ?>.png" title="<? echo mb_strtolower($order['shipping_country_info']['iso_code_2']) ?>" /> 
															<?php } ?>
															<? } elseif ($order['shipping_country']) { ?>
															<?php echo $order['shipping_country']; ?>, 
														<? } ?>  
														<?php if ($order['shipping_city']) { ?>
															<?php echo $order['shipping_city']; ?>
														<? } ?>
													</span>
													<? if ($order['shipping_city']) { ?>
														<? if ($order['current_time']) { ?>
															<? if ($order['can_call_now']) { ?>
																<span style="display:inline-block; padding:3px; color:#FFF; background-color:grey;"><i class="fa fa-clock-o"></i>&nbsp;<? echo $order['current_time']; ?></span>
																<? } else { ?>
																<i class="fa fa-warning" style="color:#cf4a61;"></i>&nbsp;<span style="display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;"><i class="fa fa-clock-o"></i>&nbsp;<? echo $order['current_time']; ?></span>
															<? } ?>
														<? } ?>													
													<? } ?>
												</div>
											<? } ?>
										</td>
										<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Товары">
											<table>
												<tr>
													<?php $i=1; foreach ($order['products'] as $product) { ?>
														<td style="padding:0px; border:0px;">		  
															<div style="cursor:pointer;" class="ktooltip_product_click" data-tooltip-content="#tooltip_product_<? echo $order['order_id']; ?><? echo $product['product_id']; ?><? echo $i; ?>">
																<img src="<?php echo $product['thumb']; ?>" id="image" loading="lazy" />
																<? if ($product['from_stock']) { ?>
																<span style="display:block;font-size:10px;background-color:#85B200; color:white;text-align:center;">СКЛАД</span><? } ?>
																
																<? if ($order['yam'] && $this->config->get('config_yam_offer_id_prefix') && $this->config->get('config_yam_offer_id_prefix_enable')) { ?>
																	<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; background:#cf4a61; color:#FFF;">
																		<?php echo $this->config->get('config_yam_offer_id_prefix') . $product['product_id']; ?>
																	</div><? } ?>
																	
																	<? if ($product['part_num']) { ?><span style="display:block;font-size:10px;"><?php echo $product['part_num']; ?></span>
																	<? } ?>															
															</div>
															<div class="ktooltip_product_hidden">
																<div id="tooltip_product_<? echo $order['order_id']; ?><? echo $product['product_id']; ?><? echo $i; ?>"><a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a></br>
																	<div style="width:150px;float:left;"><img src="<?php echo $product['lthumb']; ?>" style="" loading="lazy" /></div>
																	
																	<div class="width:160px;float:right;margin-left:10px;padding-top:20px;">
																		<?php foreach ($product['option'] as $option) { ?>
																			<?php if ($option['type'] != 'file') { ?>
																				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
																				<?php } else { ?>
																				&nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
																			<?php } ?></br>
																		<?php } ?>
																		<br />
																		<?php echo $product['part_num']; ?><br />
																		<br />
																		<?php echo $product['quantity']; ?> шт.<br />
																		<?php echo $product['price']; ?>
																	</div>
																</div>
															</div>
														</td>		
														<? if ($i%5==0) { ?></tr><tr><? } ?>
													<?php $i++; } ?>
												</tr>
											</table>
											
											
											
										</td>
										<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Инфо">
											
											<span style="font-size:11px; line-height:14px;"><?php echo str_replace(array('http://','https://','/'),'',$order['store_url']); ?></span>
											
											<br/>
											<?php if ($order['payment_method']) { ?>
												<span style="font-size:11px; line-height:14px;"><?php echo $order['payment_method']; ?></span> 
												<span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:black;"><?php echo $order['payment_code']; ?></span>

												<?php if ($order['paid_by']) { ?>
													<span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:black;"><i class="fa fa-check"></i> <?php echo $order['paid_by']; ?></span>
												<?php } ?>

												<?php if ($order['needs_checkboxua']) { ?>
													<?php if ($order['receipt_id']) { ?>
														<span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:green;"><i class="fa fa-check"></i> ДФС <?php echo $order['fiscal_code']; ?></span>	
													<?php } else { ?>
														<span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:red;"><i class="fa fa-refresh"></i> ДФС</span>
													<?php } ?>	

													<?php if ($order['is_sent_dps']) { ?>
														<span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:green;"><i class="fa fa-check"></i> <?php $order['sent_dps_at']; ?></span>	
													<?php } ?>												
												<? } ?>
											</br>
											<?php } ?>		
											<?php if ($order['receipt_id']) { ?>
												<span style="font-size:11px; line-height:14px; display:inline-block;">Фискальный чек:</span>

												<a target="_blank" href="<?php echo $order['html_link']; ?>"><span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:grey;">HTML</span></a>
         										<a target="_blank" href="<?php echo $order['pdf_link']; ?>"><span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:grey;">PDF</span></a>
         										<a target="_blank" href="<?php echo $order['text_link']; ?>"><span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:grey;">TXT</span></a>
         										<a target="_blank" href="<?php echo $order['qrcode_link']; ?>"><span style="font-size:9px; line-height:10px; display:inline-block; padding:3px; color:#FFF; background-color:grey;">QR</span></a>

         										<br>
											<?php } ?>


											<?php if ($order['payment_secondary_method']) { ?>
												<span style="font-size:11px; line-height:14px;"><?php echo $order['payment_secondary_method']; ?></span></br>
											<?php } ?>

											<?php if ($order['pay_type']) { ?>
												<span style="font-size:11px; line-height:14px;"><?php echo $order['pay_type']; ?></span></br>
											<?php } ?>

											<?php if ($order['shipping_method']) { ?>
												<span style="font-size:11px; line-height:14px;"><?php echo $order['shipping_method']; ?></span>
											<?php } ?>
											
											<?php if ($order['shipping_address'] && !$order['yam']) { ?>
												<br /><span class="ktooltip_hover" style="font-size:11px; line-height:14px;" data-tooltip-content="#address-info-content-<?php echo $order['order_id']; ?>"><?php if (!$order['address_ok']) { ?><i class="fa fa-exclamation-triangle" style="color:red;"></i><? } ?> <i class="fa fa-map-marker"></i> <?php echo $order['shipping_address']; ?></span>											
											<?php } ?>
											
											
											
											<? if ($order['yam']) { ?>	
												<br />
												<?php if ($order['yam_shipment_date'] && $order['yam_shipment_date'] != '0000-00-00') { ?>
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#7F00FF; color:#FFF;" >
														<i class="fa fa-yoast" aria-hidden="true"></i> Дата отгрузки <?php echo $order['yam_shipment_date']; ?>
													</div>	
													<?php } else { ?>
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
														<i class="fa fa-yoast" aria-hidden="true"></i> Не задана дата отгрузки
													</div>	
												<?php } ?>
											<?php } ?>
											
											<? if ($order['yam']) { ?>	
												<br />
												<?php if ($order['yam_shipment_id']) { ?>
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#7F00FF; color:#FFF;" >
														<i class="fa fa-yoast" aria-hidden="true"></i> Отгрузка <?php echo $order['yam_shipment_id']; ?>
													</div>	
													
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#00ad07; color:#FFF;">
														<a href="<?php echo HTTPS_CATALOG . 'yamarket-partner-api/shipment/label?shipment_id=' . $order['yam_shipment_id']; ?>" target='_blank' style="color:#FFFFFF;text-decoration:none;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> стикера</a>
													</div>	
													
													<?php } else { ?>
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
														<i class="fa fa-yoast" aria-hidden="true"></i> Не задана поставка
													</div>	
												<?php } ?>
											<?php } ?>
											
											<? if ($order['yam'] && $order['yam_shipment_date'] && $order['yam_shipment_id']) { ?>	
												<br />
												<?php if ($order['yam_box_id']) { ?>
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#7F00FF; color:#FFF;" >
														<i class="fa fa-yoast" aria-hidden="true"></i> Коробка <?php echo $order['yam_box_id']; ?>
													</div>		
													
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#00ad07; color:#FFF;">
														<a href="<?php echo HTTPS_CATALOG . 'yamarket-partner-api/box/label?box_id=' . $order['yam_box_id']; ?>" target='_blank' style="color:#FFFFFF;text-decoration:none;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> стикер</a>
													</div>		
													
													
													<?php } else { ?>
													<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
														<i class="fa fa-yoast" aria-hidden="true"></i> Не задана коробка
													</div>	
												<?php } ?>
											<?php } ?>
											
											<? if (strpos($order['shipping_method'], 'урьером') || strpos($order['shipping_method'], 'урьерской') || strpos($order['shipping_method'], 'амовывоз')) { ?>
												<? if ($order['order_status_id'] != $this->config->get('config_cancelled_status_id')) { ?>
													<br />
													<i class="fa fa-truck update_delivery_actual" data-order-id="<?php echo $order['order_id']; ?>" id="fa_truck_<?php echo $order['order_id']; ?>" style="cursor:pointer;color:<? if ($order['date_delivery_actual'] == '0000-00-00') { ?>red<? } else { ?>green<? } ?>"></i><span></span>&nbsp;
													<input type="text" class="date" name="date_delivery_actual_<?php echo $order['order_id']; ?>" id="date_delivery_actual_<?php echo $order['order_id']; ?>" value="<? echo $order['date_delivery_actual'] ?>" style="width:60px; font-size:10px" />
												<? } ?>
											<? } ?>
											
											<? if ($order['related_orders']) { ?>
												<? foreach ($order['related_orders'] as $_pro_order_id) { ?>
													<span style="display:inline-block; margin-bottom:5px; padding:4px 7px; background-color:#7F00FF; color:#FFF; border:2px solid white; font-size:10px;"><i class="fa fa-truck" aria-hidden="true"></i> + <? echo $_pro_order_id; ?></span>
												<? } ?>
											<? } ?>
											
											<? if ($order['ttn']) { ?>
												<br />
												<?php if (!empty($order['ttn_info']['taken'])) { ?><i class="fa fa-check-circle" style="color:#00AD07;"></i><? } ?>
												<?php if (!empty($order['ttn_info']['waiting'])) { ?><i class="fa fa-clock-o" style="color:#ffaa56;"></i><? } ?>	
												<?php if (!empty($order['ttn_info']['rejected'])) { ?><i class="fa fa-times" style="color:#ff5656;"></i><? } ?>

												<span style="font-size:11px; line-height:14px;"><span class="get_ttn_info" data-ttn="<? echo $order['ttn']; ?>" data-delivery-code="<?php echo $order['delivery_code']; ?>" data-delivery-phone="<?php echo $order['telephone']; ?>"><b><? echo $order['ttn']; ?></b></span>&nbsp;&nbsp;<span style="display:none;"></span>
												</span> 	

												<?php if (!empty($order['ttn_info']['tracking_status'])) { ?>
													<br /><span style="display:inline-block;padding:2px 3px; font-size:10px; margin:3px; background:grey; color:#FFF;"><?php echo $order['ttn_info']['tracking_status']; ?></span>
												<?php } ?>
												<br />
											<? } ?>
											
											
											<? if ($order['comment']) { ?>
												<br /><span style="font-size:10px; line-height:11px; padding-top:3px; display:inline-block; max-width:250px;"><? echo $order['comment']; ?></span><br />
											<? } ?>
											
											<? /*
												<? if (!mb_stripos($order['shipping_method'], 'москва') && !mb_stripos($order['shipping_method'], 'киев')) { ?>
												<span style="font-size:11px; line-height:14px; font-weight:700;"><? echo $order['sms_date']; ?></span>
												<? } ?>
											*/ ?>
										</td>
										<td class="left" style="width:150px; max-width:150px; color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Статус/причина отказа" width="150px">
											
											<? $general_tracker_status = $order['general_tracker_status']; if ($general_tracker_status) { ?>											
												<div class="tracker-order-info">		
													<ul>
														<li class="done">
															<i class="fa fa-map-marker" aria-hidden="true"></i>
															<br />Принят
														</li>
														<li class="<? if (in_array('first_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
															<i class="fa fa-spinner" aria-hidden="true"></i>
															<br />Обработка
														</li>
														<li class="<? if (in_array('second_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
															<i class="fa fa-check" aria-hidden="true"></i>
															<br />Подтвержден
														</li>
														<li class="<? if (in_array('third_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
															<i class="fa fa-pie-chart" aria-hidden="true"></i>
															<br />Комплектуем
														</li>
														<li class="<? if (in_array('fourth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
															<i class="fa fa-truck" aria-hidden="true"></i>
															<br />Транзит
														</li>
														<li class="<? if (in_array('fifth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">				
															<i class="fa fa-clock-o" aria-hidden="true"></i>
															<br />Склад							
														</li>
														
														<? if ($order['is_on_pickpoint']) { ?>	
															<li class="<? if (in_array('sixth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
																<i class="fa fa-car" aria-hidden="true"></i>
																<br />В ПВЗ								
															</li>
															<? } else { ?>
															<li class="<? if (in_array('sixth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
																<i class="fa fa-car" aria-hidden="true"></i>
																<br />Доставка								
															</li>
															
														<? } ?>
														
														<? if (!$order['is_on_pickpoint']) { ?>	
															<li class="<? if (in_array('seventh_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
																<i class="fa fa-check-circle" aria-hidden="true"></i>
																<br />Доставлен								
															</li>
															<? } else { ?>
															<li class="<? if (in_array('seventh_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
																<i class="fa fa-check-circle" aria-hidden="true"></i>
																<br />Получен								
															</li>
															
														<? } ?>
														
													</ul>
												</div>
											<? } ?>
											
											
											<span class="status_color" style="font-size:14px; display:inline-block; padding:5px 4px; background: #<?php echo $order['status_bg_color']; ?>; ">
												<? if ($order['status_fa_icon']) { ?>
													<i class="fa <? echo $order['status_fa_icon']; ?>" aria-hidden="true"></i>
												<? } ?>
												<?php echo $order['status']; ?>
											</span>
											
											<?php if ($order['yam'] && $order['yam_status']) { ?>
												<br />
												<span class="status_color" style="font-size:12px; margin-top:5px; display:inline-block; padding:5px 4px; background: #cf4a61; color:#FFFFFF">
													<i class="fa fa-yoast" aria-hidden="true"></i> <?php echo $order['yam_status']; ?>
												</span>
											<?php } ?>
											
											<?php if ($order['yam'] && $order['yam_substatus']) { ?>
												<br />
												<span class="status_color" style="font-size:12px;  margin-top:5px; display:inline-block; padding:5px 4px; background: #ff7815; color:#FFFFFF">
													<i class="fa fa-yoast" aria-hidden="true"></i> <?php echo $order['yam_substatus']; ?>
												</span>
											<?php } ?>
											
											<? if ($order['courier_status']) { ?>
												<br />
												<span class="status_color" style="font-size:10px; display:inline-block; padding:5px 4px; color:#FFF; background: <? if ($order['courier_color'] == 'red') { ?>#ff5656<? } else { ?>#7cc04b<? } ?>; ">
													<i class="fa fa-truck" aria-hidden="true"></i>
													<?php echo $order['courier_status']; ?>
													
													<a class="view-courier-history" data-order-id="<? echo $order['order_id']; ?>" style=""><i class="fa fa-history"></i></a>
												</span>
											<? } ?>
											
											<? if ($order['reject_reason'] && ($order['order_status_id'] == $this->config->get('config_cancelled_status_id'))) { ?>
												<br /><span style="font-size:10px; display:inline-block; padding:5px 4px; background:#F96E64; color:white;"><? echo $order['reject_reason']; ?></span>
											<? } ?>
											
											<? if ($order['is_reorder']) { ?><span class="ktooltip_hover" title="Это перезаказ по заказу #<? echo $order['is_reorder']; ?>" style="display:inline-block; font-size:10px; margin-top: 2px;padding: 3px 5px;background-color: #ff7f00;color: #FFF;border: 1px solid #ff7f00;border-radius: 2px;"><i class="fa fa-undo" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['closed']) { ?><span class="ktooltip_hover" title="Этот заказ закрыт и заблокирован для редактирования" style="display:inline-block; font-size:10px; margin-top: 2px;padding: 3px 5px;background-color: #cf4a61;color: #FFF;border: 1px solid #cf4a61;border-radius: 2px;"><i class="fa fa-lock" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['salary_paid']) { ?><span class="ktooltip_hover" title="По этому заказу выплачена комиссия" style="display:inline-block; margin-top: 2px; font-size:10px; padding: 3px 5px;background-color: #4ea24e;color: #FFF;border: 1px solid #4ea24e;border-radius: 2px;"><i class="fa fa-eur" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['from_waitlist']) { ?><span class="ktooltip_hover" title="Этот заказ оформлен из листа ожидания" style="display:inline-block; font-size:10px; margin-top: 2px;padding: 3px 5px;background-color: #4ea24e;color: #FFF;border: 1px solid #4ea24e;border-radius: 2px;"><i class="fa fa-clock-o" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['wait_full']) { ?><span class="ktooltip_hover" title="Клиент ждет полную комплектацию заказа" style="display:inline-block; font-size:10px; margin-top: 2px;padding: 3px 5px;background-color: #85B200;color: #FFF;border: 1px solid #85B200;border-radius: 2px;"><i class="fa fa-th-list" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['ua_logistics']) { ?><span class="ktooltip_hover" title="Специальная логистика" style="display:inline-block; font-size:10px; margin-top: 2px;padding: 3px 5px;background-color: #005BBB;color: #FFF;border: 1px solid #005BBB;border-radius: 2px;"><i class="fa fa-bus" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['urgent']) { ?><span  class="ktooltip_hover" title="Этот заказ со срочной доставкой" style="display:inline-block; margin-top: 2px; font-size:10px; padding:3px; background-color:red; color:#FFF; border:1px solid red; border-radius: 2px;"><i class="fa fa-space-shuttle" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['urgent_buy']) { ?><span class="ktooltip_hover" title="Этот заказ имеет приоритет закупки" style="display:inline-block; margin-top: 2px;font-size:10px;padding:3px 5px;background-color: #7F00FF;color: #FFF;border: 1px solid #7F00FF;border-radius: 2px;"><i class="fa fa-amazon" aria-hidden="true"></i></span><? } ?>
											
											<? if ($order['probably_cancel']) { ?><span class="ktooltip_hover" title="Скорее всего этот заказ необходимо отменить" style="display:inline-block;font-size:10px; margin-top: 2px;padding:3px 5px;background-color: #cf4a61;color: #FFF;border: 1px solid #cf4a61;border-radius: 2px;"><i class="fa fa-window-close" aria-hidden="true" ></i><span style="font-size:12px;"><? echo $order['probably_cancel_reason']; ?></span></span><? } ?>
											
											<? if ($order['probably_close']) { ?><span  class="ktooltip_hover" title="Скорее всего этот заказ необходимо закрыть" style="display:inline-block;font-size:10px; margin-top: 2px;padding:3px 5px;background-color: #cccccc;color: #696969;border: 1px solid #cccccc;border-radius: 2px;"><i class="fa fa-check" aria-hidden="true"></i><span style="font-size:12px;"><? echo $order['probably_close_reason']; ?></span></span><? } ?>
											
											<? if ($order['probably_problem']) { ?><span  class="ktooltip_hover" title="У этого заказа проблемы с доставкой" style="display:inline-block;font-size:10px; margin-top: 2px;padding:3px 5px;background-color: #ff7f00;color: #696969;border: 1px solid #ff7f00; border-radius: 2px;"><i class="fa fa-question-circle" aria-hidden="true"></i> <span style="font-size:12px;"><? echo $order['probably_problem_reason']; ?></span></span><? } ?>																				
											
											<? if ($order['courier_comment']) { ?>
												<span style="font-size:10px; line-height:11px; padding:5px 0 5px 5px; display:block;margin-top: 4px;border-left: 1px solid #7cc04b;"><i class="fa fa-truck" aria-hidden="true" style="color:<? if ($order['courier_color'] == 'red') { ?>#ff5656<? } else { ?>#7cc04b<? } ?>"></i> <? echo $order['courier_comment']; ?></span>
											<? } ?>
											
											<? if ($order['last_comment']) { ?>
												<span style="font-size:10px; line-height:11px; padding:5px 0 5px 5px; display:block;margin-top: 4px;border-left: 1px solid #ccc;"><? echo $order['last_comment']; ?></span>
											<? } ?>			  
										</td>			  
										<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Люди">
											
											<? if ($order['manager']) { ?>
												<span onclick="$(this).children('.manager_info').load('<? echo $order['manager']['url']; ?>'); $(this).children('.manager_info').toggle();" style="cursor:pointer; display:inline-block; border-bottom:1px dashed;">
													<i class="fa fa-user-o"></i> <?php echo $order['manager']['realname']; ?>
												<div class="manager_info" style="display:none;position:absolute;background:#FFF;padding:20px;border:1px solid grey;"></div></span>
											<?php } ?>
											
											<? if ($order['courier']) { ?>
												<div style="white-space: nowrap;margin-top:4px;"><i class="fa fa-truck"></i> <?php echo $order['courier']['realname']; ?></div>
											<?php } ?>
											
											<? if ($order['closed']) { ?>
												<div style="white-space: nowrap; margin-top:4px; padding:3px; background-color:#cccccc;"><b><i class="fa fa-check"></i></b> <?php echo $order['days_from_accept']; ?> дн.</div>
												<? } elseif ($order['date_accepted']) { ?>
												<div style="white-space: nowrap; margin-top:4px; padding:3px; background-color:#ff5656; color:white;"><b><i class="fa fa-check-circle"></i></b> <?php echo $order['days_from_accept']; ?> дн.</div>
											<? } ?>
											
											
										</td>

									<?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
										<td clas="right">
											<?php if ((float)$order['profitability'] > 0) { ?>
												<span style="display:inline-block;padding:2px 3px; font-size:12px; background:#000; color:#fff; white-space:nowrap;"><? echo $order['profitability']; ?> %</span>

												<br />
												<span style="display:inline-block;padding:2px 3px; font-size:10px; background:#ff5656; color:#FFF; white-space:nowrap;"><? echo $order['costprice']; ?></span>

												<br />
												<span style="display:inline-block;padding:2px 3px; font-size:10px; background:#ff5656; color:#FFF; white-space:nowrap;"><? echo $order['costprice_national']; ?></span>
											<?php } ?>
										</td>
									<?php } ?>

									<td class="right" style="color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Итого">
											<?php if ($order['preorder']) { ?>
												<div style="white-space: nowrap; display:inline-block; margin-top:4px; padding:3px; background-color:#000; color:#fff;"><b>
												<i class="fa fa-question-circle" aria-hidden="true"></i></b> уточнить</b></div>
												<?php } else { ?>
												
												<span style="white-space:nowrap;"><b><?php echo $order['total']; ?></b></span>
												
												<?php if ($order['reward']) { ?>
													<br />
													<span style="display:inline-block;padding:2px 3px; font-size:12px; background:#aaff56; color:#696969; white-space:nowrap;"><? echo $order['reward']; ?></span>
												<?php } ?>
												
												<?php if ($order['reward_used']) { ?>
													<br />
													<span style="display:inline-block;padding:2px 3px; font-size:12px; background:#ff5656; color:#FFF; white-space:nowrap;"><? echo $order['reward_used']; ?></span>
												<?php } ?>
										<?php } ?>
									</td>
									
									<td class="right" style="white-space: nowrap; font-size:10px; color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Итоги">
										<?php foreach ($order['totals'] as $total) { ?>
											
											<span style="<?php if ($total['value_national'] < 0){ ?>color:red;<?php } else { ?>color:green<?php } ?>">
												
												<?php if ($total['code'] == 'sub_total') { ?>
													Товар: <?php echo $total['value_national_formatted'];?>
												<?php } ?>
												
												<?php if ($total['code'] == 'shipping') { ?>
													<br />Доставка: <?php echo $total['value_national_formatted'];?>
												<?php } ?>
												
												<?php if ($total['code'] == 'reward') { ?>
													<br />Бонусами: <?php echo $total['value_national_formatted'];?>
												<?php } ?>
												
												<?php if ($total['code'] == 'additionaloffer') { ?>
													<br />Подарок: <?php echo $total['value_national_formatted'];?>
												<?php } ?>
												
												<?php if ($total['code'] == 'coupon') { ?>
													<br />Промокод: <?php echo $total['value_national_formatted'];?>
												<?php } ?>
												
												<?php if (!in_array($total['code'], [ 'reward', 'coupon', 'additionaloffer' ]) && $total['value_national'] < 0) { ?>
													<br />Скидка: <?php echo $total['value_national_formatted'];?>
												<?php } ?>
												
												<?php if ($total['code'] == 'total') { ?>
													<br /><b>Итог: <?php echo $total['value_national_formatted'];?></b>
												<?php } ?>
												
											</span>																						
											
										<?php } ?>	
										
										<?php if ($order['total_discount']) { ?>
											<span style="color:red;">
												<br /><br /><b>Скидок: <?php echo $order['total_discount'];?></b>
											</span>
										<?php } ?>
										
										<?php if ($order['total_discount_percent']) { ?>
											<span style="color:red;">
												<br /><b>Скидок: <?php echo $order['total_discount_percent'];?>%</b>
											</span>
										<?php } ?>
										
										<?php if ($order['yam_comission']) { ?>
											<span style="color:red;">
												<br /><br /><b>Комиссия: <?php echo $order['yam_comission'];?></b>
											</span>
										<?php } ?>
									</td>
									
									<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;"  aria-label="Дата покупки">										
										<div style="white-space: nowrap; padding:3px; background-color:#ecafa9"><b><i class="fa fa-plus"></i></b><?php echo $order['date_added']; ?></div>
										<div style="white-space: nowrap; margin-top:4px; padding:3px; background-color:#ecafa9"><b><i class="fa fa-clock-o"></i></b><?php echo $order['time_added']; ?></div>
										<? if ($order['date_accepted']) { ?>
											<div style="white-space: nowrap; margin-top:4px; padding:3px; background-color:#aaff56;"><b><i class="fa fa-check-circle"></i></b><?php echo $order['date_accepted']; ?></div>
										<? } ?>
										<div style="white-space: nowrap; margin-top:4px;"><b><i class="fa fa-edit"></i></b><?php echo $order['date_modified']; ?></div>
									</td>
									<td class="right" style="text-align: center;color: #<?php echo $order['status_txt_color']; ?>;" aria-label="Действие">
										<a class="button view-history" data-order-id="<? echo $order['order_id']; ?>" style="margin-bottom:2px;padding:4px 4px;"><i class="fa fa-history"></i></a>
										<a class="button view-cheques" data-order-id="<? echo $order['order_id']; ?>" style="margin-bottom:2px;padding:4px 7px;"><i class="fa fa-usd"></i></a><br/>
										<?php foreach ($order['action'] as $action) { ?>
											<a class="button" <? if (isset($action['target'])) { ?>target="<? echo $action['target']; ?>"<? } ?> href="<?php echo $action['href']; ?>" style="margin-bottom:2px;padding:4px 4px;"><?php echo $action['text']; ?></a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="10"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
			<div id="mailpreview"></div>
		</div>
	</div>
</div>
<div id="ttninfo"></div>
<style>
	.SumoSelect{width:300px!important;}
	@media screen and (max-width: 1400px) {
	.SumoSelect{width:200px!important;}
	}
	
	.SumoSelect.sumo_filter_courier_status{width:200px!important;}
</style>

<script>
	$('#only_my_orders').change(function(){
		if ($(this).is(':checked')){
			$('select[name=\'filter_manager_id\']').val("<? echo $this->user->getID(); ?>");
			} else {
			$('select[name=\'filter_manager_id\']').val("*");
		}
	});
	
	$('#only_my_orders_courier').change(function(){
		if ($(this).is(':checked')){
			$('select[name=\'filter_courier_id\']').val("<? echo $this->user->getID(); ?>");
			} else {
			$('select[name=\'filter_courier_id\']').val("*");
		}
	});
</script>
<script>
	$('.update_delivery_actual').click(function(){
		var _ud_order_id = $(this).attr('data-order-id');
		var _ud_date_delivery_actual = $('#date_delivery_actual_'+_ud_order_id).val();
		var span = $(this);
		
		$.ajax({
			url: 'index.php?route=sale/order/updateOrderDeliveryInfoAjax&token=<?php echo $token; ?>&order_id=' +  _ud_order_id + '&date_delivery_actual='+_ud_date_delivery_actual,
			type: 'GET',
			dataType : 'text',
			beforeSend: function(){
				span.next().html('<i class="fa fa-spinner fa-spin"></i>');			
			},
			success:function(e){
				span.next().hide();
				if (e=='1'){
					span.css('color', 'green');
					} else {
					span.css('color', 'red');
				}
			},
			error:function(e){console.log(e);}		
		})
		
	});
</script>
<script>$('.get_ttn_info').click(function(){
	var span = $(this);
	span.next().html('<i class="fa fa-spinner fa-spin"></i>');
	span.next().show();
	var ttn = span.attr('data-ttn');
	var code = span.attr('data-delivery-code');
	var phone = span.attr('data-delivery-phone');
	$('#ttninfo').load(
	'index.php?route=sale/order/ttninfoajax&token=<? echo $token ?>',
	{
		ttn : ttn,
		delivery_code : code,
		phone: phone
	}, 
	function(){
		span.next().hide();
		$(this).dialog({width:900, height:700, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Информация по накладной '+ttn}); 
	});
});</script>
<script>
	$('.view-cheques').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order/invoicehistory&token=<?php echo $token; ?>&order_id=' +  $(this).attr('data-order-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
</script>
<script>
	$('.view-history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=' +  $(this).attr('data-order-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
</script>
<script>
	$('.view-courier-history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order/courierhistory&token=<?php echo $token; ?>&order_id=' +  $(this).attr('data-order-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
</script>
<script type="text/javascript"><!--
	function filter(csv) {
		url = 'index.php?route=sale/order&token=<?php echo $token; ?>';
		
		var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
		
		if (filter_order_id) {
			url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
		}
		
		var filter_customer = $('input[name=\'filter_customer\']').attr('value');
		
		if (filter_customer) {
			url += '&filter_customer=' + encodeURIComponent(filter_customer);
		}
		
		var filter_discount_card = $('input[name=\'filter_discount_card\']').attr('value');
		
		if (filter_discount_card) {
			url += '&filter_discount_card=' + encodeURIComponent(filter_discount_card);
		}
		
		var filter_referrer = $('input[name=\'filter_referrer\']').attr('value');
		
		if (filter_referrer) {
			url += '&filter_referrer=' + encodeURIComponent(filter_referrer);
		}
		
		var filter_courier_status = $('select[name=\'filter_courier_status\']').children("option:selected").val();
		
		if (filter_courier_status != '*') {
			url += '&filter_courier_status=' + encodeURIComponent(filter_courier_status);
		}	
		
		var filter_shipping_method = $('select[name=\'filter_shipping_method\']').children("option:selected").val();
		
		if (filter_shipping_method != '*') {
			url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
		}	
		
		var filter_payment_method = $('select[name=\'filter_payment_method\']').children("option:selected").val();
		
		if (filter_payment_method != '*') {
			url += '&filter_payment_method=' + encodeURIComponent(filter_payment_method);
		}	
		
		var filter_order_status_id = $('select[name=\'filter_order_status_id\']').children("option:selected").val();
		var filter_order_store_id  = $('select[name=\'filter_order_store_id\']').children("option:selected").val();
		var filter_reject_reason_id  = $('select[name=\'filter_reject_reason_id\']').children("option:selected").val();
		var filter_affiliate_id  = $('select[name=\'filter_affiliate_id\']').children("option:selected").val();
		
		if (filter_order_status_id != '*' && filter_order_status_id != 'undefined') {
			url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
		}
		if (filter_order_store_id != '*' && filter_order_store_id != 'undefined') {
			url += '&filter_order_store_id=' + encodeURIComponent(filter_order_store_id);
		}
		
		if (filter_reject_reason_id != '*' && filter_reject_reason_id != 'undefined') {
			url += '&filter_reject_reason_id=' + encodeURIComponent(filter_reject_reason_id);
		}
		
		if (filter_affiliate_id != '*' && filter_affiliate_id != 'undefined') {
			url += '&filter_affiliate_id=' + encodeURIComponent(filter_affiliate_id);
		}
		
		var filter_product_id = $('input[name=\'filter_product_id\']').attr('value');
		
		if (filter_product_id) {
			url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
		}	
		
		var filter_ttn = $('input[name=\'filter_ttn\']').attr('value');
		
		if (filter_ttn) {
			url += '&filter_ttn=' + encodeURIComponent(filter_ttn);
		}	

		<?php if ($this->config->get('config_country_id') == 176) { ?>	
			var filter_yam_id = $('input[name=\'filter_yam_id\']').attr('value');

			if (filter_yam_id) {
				url += '&filter_yam_id=' + encodeURIComponent(filter_yam_id);
			}
		<?php } ?>	

		var filter_urgent = $('input[name=\'filter_urgent\']:checked').val();
		
		if (filter_urgent !== undefined) {
			url += '&filter_urgent=1';
		}
		
		var filter_preorder = $('input[name=\'filter_preorder\']:checked').val();
		
		if (filter_preorder !== undefined) {
			url += '&filter_preorder=1';
		}
		
		var filter_urgent_buy = $('input[name=\'filter_urgent_buy\']:checked').val();
		
		if (filter_urgent_buy !== undefined) {
			url += '&filter_urgent_buy=1';
		}
		
		var filter_wait_full = $('input[name=\'filter_wait_full\']:checked').val();
		
		if (filter_wait_full !== undefined) {
			url += '&filter_wait_full=1';
		}
		
		var filter_ua_logistics = $('input[name=\'filter_ua_logistics\']:checked').val();
		
		if (filter_ua_logistics !== undefined) {
			url += '&filter_ua_logistics=1';
		}
		
		var filter_pwa = $('input[name=\'filter_pwa\']:checked').val();
		
		if (filter_pwa !== undefined) {
			url += '&filter_pwa=1';
		}
		
		<?php if ($this->config->get('config_country_id') == 176) { ?>	
			var filter_yam = $('input[name=\'filter_yam\']:checked').val();

			if (filter_yam !== undefined) {
				url += '&filter_yam=1';
			}
		<?php } ?>
		
		var filter_total = $('input[name=\'filter_total\']').attr('value');
		
		if (filter_total) {
			url += '&filter_total=' + encodeURIComponent(filter_total);
		}	
		
		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		var filter_date_added_to = $('input[name=\'filter_date_added_to\']').attr('value');
		
		if (filter_date_added_to) {
			url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
		}
		
		var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
		
		if (filter_date_modified) {
			url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
		}
		
		var filter_date_delivery = $('input[name=\'filter_date_delivery\']').attr('value');
		
		if (filter_date_delivery) {
			url += '&filter_date_delivery=' + encodeURIComponent(filter_date_delivery);
		}
		
		var filter_manager_id = $('select[name=\'filter_manager_id\']').children("option:selected").val();
		
		if (filter_manager_id != '*') {
			url += '&filter_manager_id=' + encodeURIComponent(filter_manager_id);
		}	
		
		var filter_courier_id = $('select[name=\'filter_courier_id\']').children("option:selected").val();
		
		if (filter_courier_id != '*') {
			url += '&filter_courier_id=' + encodeURIComponent(filter_courier_id);
		}
		
		if (csv){
			url += '&filter_do_csv=1';
		}
		
		location = url;
	}
//--></script>  
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		
		$(".rateYo").rateYo({
			precision: 1,
			starWidth: "14px",
			readOnly: true,
			normalFill: "#ccc",
			multiColor: {																
				"startColor": "#cf4a61", //RED
				"endColor"  : "#4ea24e"  //GREEN
			}
		});
	});
//--></script> 
<script type="text/javascript"><!--
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});
//--></script> 
<script type="text/javascript"><!--
	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var self = this, currentCategory = '';
			
			$.each(items, function(index, item) {
				if (item.category != currentCategory) {
					ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
					
					currentCategory = item.category;
				}
				
				self._renderItem(ul, item);
			});
		}
	});
	
	$('input[name=\'filter_customer\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							category: item.customer_group,
							label: item.name,
							value: item.customer_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'filter_customer\']').val(ui.item.label);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
//--></script>

<script type="text/javascript"><!--
	$('input[name=\'product\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&only_enabled=1&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							value: item.name,
							product_id: item.product_id,
							model: item.model,
							ean:   item.ean,
							option:item.option,
							price: item.price,
							image: item.image
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product\']').attr('value', ui.item.value);
			$('input[name=\'filter_product_id\']').attr('value', ui.item.product_id);
			filter();
			return false;
		},
		focus: function(event, ui) {
			$('input[name=\'product\']').attr('value', ui.item.value);
			$('#filter_product_id_id').text(ui.item.product_id);
			$('#filter_product_model').text(ui.item.model);
			$('#filter_product_ean').text(ui.item.ean);
		}
	});
//--></script>
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
<script type="text/javascript">
	$(document).ready(function() {
		$('.ktooltip_product_click').tooltipster(
		{
			trigger: 'click',
			contentAsHTML : true,
			contentCloning: true,
			theme: 'tooltipster-shadow'
		}
		);
	});
</script>	
<script>
	function setChequeViewTriggers(){
		console.log('setChequeViewTriggers');
		
		$('.view-cheque').click(function(){
			var _this = $(this);
			$.ajax({
				url: 'index.php?route=sale/order/singleinvoice_log&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'),
				dataType: 'html',
				success : function(html){
					$('#mailpreview').html(html).dialog({title:'Чек от ' + _this.attr('data-order_invoice-datetime') + ', сформирован ' + _this.attr('data-order_invoice-author') , width:800, modal:true,resizable:true,  position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
				}
			})	
		});	
		
		$('.print-cheque').click(function(){
			window.open('index.php?route=sale/order/singleinvoice_log&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');			
		});	
		
		$('.pdf-cheque').click(function(){
			window.open('index.php?route=sale/order/invoicehistory2pdf&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');			
		});	
		
		$('.pdf-cheque-a5').click(function(){
			window.open('index.php?route=sale/order/invoicehistory2pdf_a5&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');			
		});
		$('.pdf-cheque-auto').click(function(){
			window.open('index.php?route=sale/order/invoicehistory2pdf_auto&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');
		});	
	}
</script>
<?php echo $footer; ?>																							