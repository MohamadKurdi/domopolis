<div class="dashboard-heading">Свежие заказы</div>
<div class="dashboard-content">
	<table class="list">
		<tbody>
			<?php if ($orders) { ?>
				<?php foreach ($orders as $order) { ?>
					<tr>
						<td  class="right" style="text-align: left; <?php if ($order['yam_express']) { ?>background-color:#cf4a61; color:white;<? } ?>">
							<?php echo $order['order_id']; ?>
						</td>
						<?php if ($this->config->get('config_amazon_profitability_in_stocks')) { ?>
							<td  class="center" style="width:20px;">
								<?php if ($order['amazon_offers_type']) { ?>
									<span style="padding:2px 3px; background-color:#e16a5d; display:inline-block; text-decoration:none;font-size:14px; color:#FFF;"><? echo $order['amazon_offers_type']; ?></span>
								<?php } ?>
							</td>
						<?php } ?>
						<td  class="left" style="text-align: left;">
						<?php if ($this->config->get('config_admin_flags_enable')) { ?>
							<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($order['flag']) ?>.png" title="<? echo $order['flag']; ?>" /> 
						<?php } ?>
						<b><?php echo $order['customer']; ?></b>

							<?php if ($order['do_not_call']) { ?>									
									<span style="color:#FFF; padding:3px; background-color:#cf4a61; font-weight:700;"><i class="fa fa-microphone-slash" aria-hidden="true"></i></span>
							<?php } ?>

							<div style="clear:both;"></div>
							<span style="font-size:10px; line-height:13px; 
							<?php if ($order['email_bad']) { ?>color:#ff5656<?php } else { ?>color:#02760e<? } ?>">
											<? echo $order['email']; ?>
											<?php if ($order['email_bad']) { ?><i class="fa fa-exclamation-triangle" style="color:#ff5656"></i><?php } ?>
											<?php if (!$order['email_bad']) { ?><i class="fa fa-check-circle" style="color:#02760e"></i><?php } ?>
										</span>
							
							<? if ($order['pwa']) { ?>	
								<div style="clear:both;"></div>
								<div style="display:inline-block; padding:2px 3px; font-size:9px; margin:3px; background:#7F00FF; color:#FFF;" >
									<i class="fa fa-chevron-circle-right" aria-hidden="true"></i> PWA/APP
								</div>																						
							<? } ?>

							<? if ($order['monocheckout']) { ?>											
										<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; background:#000; color:#FFF;" >
											<i class="fa fa-chevron-circle-right" aria-hidden="true"></i> MonoCheckout
										</div>																						
									<? } ?>

							<? if ($order['ukrcredits_order_status']) { ?>	
								<div style="clear:both;"></div>
								<div style="display:inline-block; padding:2px 3px; font-size:9px; margin:3px; background:#00AD07; color:#FFF;" >
									<i class="fa fa-percent" aria-hidden="true"></i> Кредит
								</div>																						
							<? } ?>
							
							<? if ($order['yam']) { ?>		
								<div style="clear:both;"></div>
								<div style="display:inline-block; padding:2px 3px; font-size:9px; margin:3px; background:#cf4a61; color:#FFF;" >
									<i class="fa fa-yoast" aria-hidden="true"></i> Я.Маркет
								</div>																						
							<? } ?>

							<? if ($order['yam_express']) { ?>		
								<div style="clear:both;"></div>
								<div style="display:inline-block; padding:2px 3px; font-size:9px; margin:3px; background:#cf4a61; color:#FFF;" >
									<i class="fa fa-yoast" aria-hidden="true"></i> Экспресс
								</div>																						
							<? } ?>
							
							<? if (!empty($order['customersegments'])) { ?>
								<div style="clear:both;"></div>																						
								<? foreach ($order['customersegments'] as $segment) { ?>
									<? if (strpos($segment['name'], 'Источник') !== false) { ?>
										<span style="display:inline-block; padding:2px 3px; font-size:9px; margin:3px; background:#<? if ($segment['bg_color']) { ?><? echo $segment['bg_color'] ?><? } else { ?>e6e9f3<? } ?>; color:#<? if ($segment['txt_color']) { ?><? echo $segment['txt_color'] ?><? } else { ?>696969<? } ?>">
											<? if ($segment['fa_icon']) { ?>
												<i class="fa <? echo $segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
											<? } ?><?php echo $segment['name']; ?>
											<? if ($segment['is_new']) { ?>
												<sup style="color:#ef5e67; font-size:10px;">new</sup>
											<? } ?>&nbsp;
										</span>
									<? } ?>
								<? } ?>
							<? } ?>
							
							<? if ($order['first_referrer'] || $order['affiliate']) { ?>
								<div style="clear:both;"></div>
								<? if ($order['first_referrer']) { ?>
									<span style="display:inline-block; padding:2px 3px; font-size:9px; margin:3px; background:#e6e9f3; color:#696969" >
										<i class="fa fa-mouse-pointer" aria-hidden="true"></i> <? echo $order['first_referrer']; ?>
									</span>
								<? } ?>
								<? if ($order['affiliate']) { ?>
									<span style="display:inline-block; padding:2px 3px; font-size:9px; margin:3px; background:#aaff56; color:#696969" >
										<i class="fa fa-chevron-circle-right" aria-hidden="true"></i> <? echo $order['affiliate']['firstname'] . ' ' . $order['affiliate']['lastname']; ?>
									</span>
								<? } ?>
							<? } ?>
							
						</td>
						
						<td class="left">
							<span style="display:inline-block;padding:2px 3px; font-size:14px; margin:3px; <?php if ($order['total_customer_orders'] == 1) { ?>background:#ff5656; color:#FFF;<?php } else { ?>background:#aaff56; color:#696969;<?php } ?>"><? echo $order['total_customer_orders']; ?> 
							<?php echo $order['total_customer_orders_txt']; ?></span>									
						</td>
						
						
						<td class="left status" style="font-size:11px; text-align: left; width: 160px;"><span class="status_color" style=" padding:5px 3px; max-width: 160px; background: #<?php echo isset($order['status_bg_color']) ? $order['status_bg_color'] : ''; ?>; color: #<?php echo isset($order['status_txt_color']) ? $order['status_txt_color'] : ''; ?>;">	<? if ($order['status_fa_icon']) { ?>
							<i class="fa <? echo $order['status_fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
						<? } ?><?php echo $order['status']; ?></span>
						</td>
						
						<td class="left" style="text-align: left; width: 40px;"><?php echo $order['date_added']; ?></td>

						<?php if ($this->config->get('config_show_profitability_in_order_list')){ ?>
							<td clas="right" style="text-align: center;">
								<?php if ((float)$order['profitability'] != 0) { ?>
									<span style="display:inline-block;padding:2px 3px; font-size:12px; <?php if ((float)$order['profitability'] < 0) { ?>background:#ff5656;<?php } else { ?>background:#000;<?php } ?> color:#fff; white-space:nowrap;"><? echo $order['profitability']; ?> %</span>
								<?php } ?>
							</td>
						<?php } ?>
						
						<td class="right" style="text-align: center;">
							<?php if ($order['preorder']) { ?>
								<div style="white-space: nowrap; display:inline-block; margin-top:4px; font-size:10px; padding:3px; background-color:#000; color:#fff;"><b>
									<i class="fa fa-question-circle" aria-hidden="true"></i>уточнить
								</div>
								<?php } else { ?>
								<b><?php echo $order['total']; ?></b>
								
								<?php if ($order['reward']) { ?>
									<br />
									<span style="display:inline-block;padding:2px 3px; font-size:12px; background:#aaff56; color:#696969;"><? echo $order['reward']; ?></span>
								<?php } ?>
								
								<?php if ($order['reward_used']) { ?>
									<br />
									<span style="display:inline-block;padding:2px 3px; font-size:12px; background:#ff5656; color:#FFF;"><? echo $order['reward_used']; ?></span>
								<?php } ?>
								<?php } ?>
								
							</td>
							
							<td class="right" style="white-space: nowrap; font-size:10px;">
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
							
							<td class="right" style="text-align: center;"><?php foreach ($order['action'] as $action) { ?>
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