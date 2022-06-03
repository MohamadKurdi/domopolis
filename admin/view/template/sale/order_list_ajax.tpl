<div class="box">
	<style>
		span.get_ttn_info {cursor:pointer; display:inline-block; border-bottom:1px dashed black;}
	</style>
    <div class="content" style="min-height:auto;">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>      
						<td class="left">ID</td>
						<td class="left">Покупатель</td>
						<td class="left">Товары</td>
						<td class="left">Инфо</td>
						<td class="left">Статус</td>
						<td class="left">Люди</td>
						<td class="right"><?php echo $column_total; ?></td>
						<td class="left">Дата:</td>            
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($orders) { ?>
						<?php foreach ($orders as $order) { ?>
							<tr>
								<td class="right" style="color: #<?php echo $order['status_txt_color']; ?>;">
									<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:20px;">
										<?php echo $order['order_id']; ?>&nbsp;<span class="add2ticket" data-query="object=order&object_value=<?php echo $order['order_id']; ?>"></span>
									</div>
									
									
									<? if ($order['can_get_csi']) { ?>
										<? if ($order['csi_reject']) { ?>
											<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:14px; background-color:#F96E64; display:inline-block; color:#FFF">
												CSI: отказ
											</div>
											<? } elseif ($order['csi_average'] > 0) { ?>
											<div style="text-align:right;"><div style="display:inline-block;" class="rateYo" data-rateyo-rating="<? echo $order['csi_average']; ?>"></div></div>
											<? } elseif ($order['nbt_csi']) { ?>
											<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:14px; background-color:#F96E64; display:inline-block; color:#FFF">
												<i class="fa fa-deaf" aria-hidden="true"></i> CSI: недозвон
											</div>	
											<? } else { ?>
											<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:14px; background-color:#F96E64; display:inline-block; color:#FFF">
												CSI: не задан
											</div>
										<? } ?>
									<? } ?>
									
									<? if ($order['parties']) { ?>
										<div><? $_c = 1; foreach ($order['parties'] as $_partie) { ?>
											<div><b><? echo $_c; ?>:(<a style="color:#<? echo $order['status_txt_color']; ?>" href="<?php echo $_partie['href']; ?>"><?php echo $_partie['part_num']; ?></a>)</b></div>
										<? $_c++; } ?>
										</div>
									<? } ?>
									
									<? if ($order['is_opt']) { ?>			    
										<span style="display:block; margin-top:5px; font-size:10px;">опт</span>				
										<? } else { ?>			  
										<span style="display:block; margin-top:5px; font-size:10px;">розница</span>				
									<? } ?>
								</span>
								<!--
									изм. <?php echo $order['changed']; ?> раз<br />
									истор: <?php echo $order['histories']; ?><br />
								-->
							</td>
							<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;">
								<? if ($order['is_mudak']) { ?>
									<span style="background:white; color:red; padding:3px;"><i class='fa fa-ambulance' style='color:red; font-size:16px;'></i>&nbsp;&nbsp;<a href="<? echo $order['customer_href']; ?>" style="font-size:16px;" target="_blank"><?php echo $order['customer']; ?></a>&nbsp;<span class="add2ticket" data-query="object=customer&object_value=<?php echo $order['customer_id']; ?>"></span></span>
									<? } else { ?>
									<a href="<? echo $order['customer_href']; ?>" style="font-size:16px;" target="_blank"><?php echo $order['customer']; ?></a>&nbsp;<span class="add2ticket" data-query="object=order&object_value=<?php echo $order['order_id']; ?>"></span>
								<? } ?>
								<? /* if ($order['customer_info']['discount_card']) { ?>
									<br />
									<div style="padding:3px; background:#d4ffaa; border:1px dotted #CCC; font-size:11px; display:inline-block;">
										<i class="fa fa-credit-card-alt" aria-hidden="true"></i> Карта # <? echo $order['customer_info']['discount_card']; ?>, скидка <? echo $order['customer_info']['discount_percent']; ?>%
									</div>
								<? } */ ?>
								<br />
								<span style="font-size:10px; line-height:13px;"><? echo $order['email']; ?></span>
								<br />
							<span style="font-size:11px; line-height:14px;">Всего <? echo $order['total_customer_orders']; ?> заказ(ов):</a> <a href="<? echo $order['total_customer_orders_a']; ?>">id</a> <a href="<? echo $order['total_customer_orders_n_a']; ?>">имя</a></span><br />						
							<?php if ($order['telephone']) { ?>
								<span style="font-size:11px; line-height:14px;"><?php echo $order['telephone']; ?>
									<span class='click2call' data-phone="<?php echo $order['telephone']; ?>"></span>
								<? } ?>
								<?php if ($order['fax']) { ?>
									<br /><span style="font-size:11px; line-height:14px;"><?php echo $order['fax']; ?>
										<span class='click2call' data-phone="<?php echo $order['fax']; ?>"></span>
									<? } ?>
									<?php if ($order['shipping_country'] || $order['shipping_city']) { ?>
										<div style="font-size:12px; line-height:14px;">
											<span> 
												<?php if ($order['shipping_country_info']) { ?>
													<img src="view/image/flags/<? echo mb_strtolower($order['shipping_country_info']['iso_code_2']) ?>.png" title="<? echo mb_strtolower($order['shipping_country_info']['iso_code_2']) ?>" /> 
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
								<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;">
									<table>
										<tr>
											<?php $i=1; foreach ($order['products'] as $product) { ?>
												<td style="padding:0px; border:0px;">		  
													<div onclick="$(this).children('.tltp').toggle();"><a class="colorbox"><img src="<?php echo $product['thumb']; ?>" id="image" />
													</a><? if ($product['from_stock']) { ?><span style="display:block;font-size:10px;background-color:#85B200; color:white;text-align:center;">СКЛАД</span><? } ?><? if ($product['part_num']) { ?><span style="display:block;font-size:10px;"><?php echo $product['part_num']; ?></span><? } ?>
													
													<div class="tltp" style="position:absolute;display:none;background:#fff;padding:10px;width:350px;"><a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a></br>
														<img src="<?php echo $product['lthumb']; ?>" style="float:left;" />
														
														<div class="width:120px; float:right;padding-top:20px;">
															<?php foreach ($product['option'] as $option) { ?>
																<?php if ($option['type'] != 'file') { ?>
																	&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
																	<?php } else { ?>
																	&nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
																<?php } ?></br>
															<?php } ?>
															<br />
															Партия: <?php echo $product['part_num']; ?><br />
															<br />
															В заказе: <?php echo $product['quantity']; ?> шт.<br />
															Цена: <?php echo $product['price']; ?>
														</div>
													</div>
													</div>
												</td>		
												<? if ($i%5==0) { ?></tr><tr><? } ?>
											<?php $i++; } ?>
										</tr>
									</table>
									
									
									
								</td>
								<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;">
									
									<span style="font-size:11px; line-height:14px;"><?php echo str_replace(array('http://','https://','/'),'',$order['store_url']); ?></span>
									
									<br/>
									<?php if ($order['payment_method']) { ?>
										<span style="font-size:11px; line-height:14px;"><?php echo $order['payment_method']; ?></span></br>
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
									
									<? if (strpos($order['shipping_method'], 'урьером') || strpos($order['shipping_method'], 'урьерской') || strpos($order['shipping_method'], 'амовывоз')) { ?>
										<? if ($order['order_status_id'] != $this->config->get('config_cancelled_status_id')) { ?>
											<br />
											<i class="fa fa-truck update_delivery_actual" data-order-id="<?php echo $order['order_id']; ?>" id="fa_truck_<?php echo $order['order_id']; ?>" style="cursor:pointer;color:<? if ($order['date_delivery_actual'] == '0000-00-00') { ?>red<? } else { ?>green<? } ?>"></i><span></span>&nbsp;
											<input type="text" class="date" name="date_delivery_actual_<?php echo $order['order_id']; ?>" id="date_delivery_actual_<?php echo $order['order_id']; ?>" value="<? echo $order['date_delivery_actual'] ?>" style="width:50px; font-size:10px" />
										<? } ?>
									<? } ?>
									
									<? if ($order['related_orders']) { ?>
										<? foreach ($order['related_orders'] as $_pro_order_id) { ?>
											<span style="display:inline-block; margin-bottom:5px; padding:4px 7px; background-color:#7F00FF; color:#FFF; border:2px solid white; font-size:10px;"><i class="fa fa-truck" aria-hidden="true"></i> + <? echo $_pro_order_id; ?></span>
										<? } ?>
									<? } ?>
									
									<? if ($order['ttn']) { ?>
										<br />
										<span style="font-size:11px; line-height:14px;">ТТН: <span class="get_ttn_info" data-ttn="<? echo $order['ttn']; ?>" data-delivery-code="<?php echo $order['delivery_code']; ?>"><b><? echo $order['ttn']; ?></b></span>&nbsp;&nbsp;<span style="display:none;"></span>
										</span><br />
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
								<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;">
									<span class="status_color" style="background: #<?php echo $order['status_bg_color']; ?>; ">
										<? if ($order['status_fa_icon']) { ?>
											<i class="fa <? echo $order['status_fa_icon']; ?>" aria-hidden="true"></i>
										<? } ?>
										<?php echo $order['status']; ?>
									</span>
									
									<? if ($order['reject_reason'] && ($order['order_status_id'] == $this->config->get('config_cancelled_status_id'))) { ?>
										<span style="display:block;border-radius: 2px;padding: 10px 0 10px 5px; background:#F96E64; color:white;"><? echo $order['reject_reason']; ?></span>
									<? } ?>
									
									<? if ($order['closed']) { ?><span class="ktooltip_hover" title="Этот заказ закрыт и заблокирован для редактирования" style="display:inline-block; margin-top: 2px;padding: 3px 5px;background-color: #cf4a61;color: #FFF;border: 1px solid #cf4a61;font-size: 14px;border-radius: 2px;"><i class="fa fa-lock" aria-hidden="true"></i></span><? } ?>
									
									<? if ($order['is_reorder']) { ?><span class="ktooltip_hover" title="Это перезаказ по заказу #<? echo $order['is_reorder']; ?>" style="display:inline-block; margin-top: 2px;padding: 3px 5px;background-color: #ff7f00;color: #FFF;border: 1px solid #ff7f00;font-size: 14px;border-radius: 2px;"><i class="fa fa-undo" aria-hidden="true"></i></span><? } ?>
									
									<? if ($order['salary_paid']) { ?><span class="ktooltip_hover" title="По этому заказу выплачена комиссия" style="display:inline-block; margin-top: 2px;padding: 3px 5px;background-color: #4ea24e;color: #FFF;border: 1px solid #4ea24e;font-size: 14px;border-radius: 2px;"><i class="fa fa-eur" aria-hidden="true"></i></span><? } ?>
									
									<? if ($order['from_waitlist']) { ?><span class="ktooltip_hover" title="Этот заказ оформлен из листа ожидания" style="display:inline-block; margin-top: 2px;padding: 3px 5px;background-color: #4ea24e;color: #FFF;border: 1px solid #4ea24e;font-size: 14px;border-radius: 2px;"><i class="fa fa-clock-o" aria-hidden="true"></i></span><? } ?>
									
									<? if ($order['wait_full']) { ?><span class="ktooltip_hover" title="Клиент ждет полную комплектацию заказа" style="display:inline-block; margin-top: 2px;padding: 3px 5px;background-color: #85B200;color: #FFF;border: 1px solid #85B200;font-size: 14px;border-radius: 2px;"><i class="fa fa-th-list" aria-hidden="true"></i></span><? } ?>
									
									<? if ($order['ua_logistics']) { ?><span class="ktooltip_hover" title="Этот заказ нужно отправить через Украину" style="display:inline-block; margin-top: 2px;padding: 3px 5px;background-color: #005BBB;color: #FFF;border: 1px solid #005BBB;font-size: 14px;border-radius: 2px;"><i class="fa fa-bus" aria-hidden="true"></i>&nbsp;<img src="view/image/flags/ua.png" /></span><? } ?>
									
									<? if ($order['urgent']) { ?><span  class="ktooltip_hover" title="Этот заказ со срочной доставкой" style="display:inline-block; margin-top: 2px; padding:3px; background-color:red; color:#FFF; border:1px solid red; font-size:14px;border-radius: 2px;"><i class="fa fa-space-shuttle" aria-hidden="true"></i></span><? } ?>
									
									<? if ($order['urgent_buy']) { ?><span class="ktooltip_hover" title="Этот заказ имеет приоритет закупки" style="display:inline-block; margin-top: 2px;padding:3px 5px;background-color: #7F00FF;color: #FFF;border: 1px solid #7F00FF;font-size: 14px;border-radius: 2px;"><i class="fa fa-amazon" aria-hidden="true"></i></span><? } ?>
									
									<? if ($order['probably_cancel']) { ?><span class="ktooltip_hover" title="Скорее всего этот заказ необходимо отменить" style="display:inline-block; margin-top: 2px;padding:3px 5px;background-color: #ff0000;color: #FFF;border: 1px solid #ff0000;font-size: 14px;border-radius: 2px;"><i class="fa fa-window-close" aria-hidden="true" ></i><span style="font-size:12px;"><? echo $order['probably_cancel_reason']; ?></span></span><? } ?>
									
									<? if ($order['probably_close']) { ?><span  class="ktooltip_hover" title="Скорее всего этот заказ необходимо закрыть" style="display:inline-block; margin-top: 2px;padding:3px 5px;background-color: #cccccc;color: #696969;border: 1px solid #cccccc;font-size: 14px;border-radius: 2px;"><i class="fa fa-check" aria-hidden="true"></i><span style="font-size:12px;"><? echo $order['probably_close_reason']; ?></span></span><? } ?>
									
									<? if ($order['probably_problem']) { ?><span  class="ktooltip_hover" title="У этого заказа проблемы с доставкой" style="display:inline-block; margin-top: 2px;padding:3px 5px;background-color: #ff7f00;color: #696969;border: 1px solid #ff7f00; font-size: 14px;border-radius: 2px;"><i class="fa fa-question-circle" aria-hidden="true"></i> <span style="font-size:12px;"><? echo $order['probably_problem_reason']; ?></span></span><? } ?>
									
									
									<? if ($order['last_comment']) { ?>
										<span style="font-size:10px; line-height:11px; padding:5px 0 5px 5px; display:block;margin-top: 4px;border-left: 1px solid #ccc;"><? echo $order['last_comment']; ?></span>
									<? } ?>			  
								</td>			  
								<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;">
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
								<td class="left" style="color: #<?php echo $order['status_txt_color']; ?>;"><b>доб:</b> <?php echo $order['date_added']; ?><br />
									<b>изм:</b> <?php echo $order['date_modified']; ?>
								</td>
								<td class="right" style="text-align: center;color: #<?php echo $order['status_txt_color']; ?>;">
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
	</div>
</div>
<div id="ttninfo"></div>
<script>$('.get_ttn_info').click(function(){
	var span = $(this);
	span.next().html('<i class="fa fa-spinner fa-spin"></i>');
	span.next().show();
	var ttn = span.attr('data-ttn');
	var code = span.attr('data-delivery-code');
	$('#ttninfo').load(
	'index.php?route=sale/order/ttninfoajax&token=<? echo $token ?>',
	{
		ttn : ttn,
		delivery_code : code
	}, 
	function(){
		span.next().hide();
		$(this).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Информация по накладной '+ttn}); 
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