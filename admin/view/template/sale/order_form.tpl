<?php echo $header; ?>
<? require_once(dirname(__FILE__).'/order_form.js.tpl'); ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($error_error) { ?>
		<div class="warning"><?php echo $error_error; ?></div>
	<?php } ?>
	<?php if ($warning_marketplace) { ?>
		<div class="warning"><?php echo $warning_marketplace; ?></div>
	<?php } ?>
	
	<?php if ($warning_yam_fake) { ?>
		<div class="warning"><?php echo $warning_yam_fake; ?></div>
	<?php } ?>
	
	<?php if ($warning_preorder) { ?>
		<div class="warning" style="background-color:#000;"><?php echo $warning_preorder; ?></div>
	<?php } ?>
	<?php if ($warning_birthday) { ?>
		<div class="success"><?php echo $warning_birthday; ?></div>
	<?php } ?>
	<?php if (isset($success)) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1>
				<?php echo $heading_title; ?> 
				
				<?php if ($yam && $yam_id) { ?>
					<span style="color:red;padding-left:10px;padding-right:10px;"><i class="fa fa-yoast" aria-hidden="true"></i> <?php echo $yam_id; ?></span>
				<?php } ?>
				
				<span class="add2ticket" style="font-size:36px;" data-query="object=order&object_value=<?php echo $order_id; ?>"></span>&nbsp;
				
				<span id="pushHobotunManagers" style="font-size:28px; cursor:pointer;" data-order-id="<?php echo $order_id; ?>"><i class="fa fa-fax" aria-hidden="true"></i></span>&nbsp;
				
				<span id="manager_of_order" style="font-weight:400; border-bottom:1px dashed black; cursor:pointer;" onclick="$('#show_manager_change').toggle();"><? if(isset($manager)) { ?><? echo $manager['realname']; ?><? } ?></span>
				
				<? if ($closed) { ?>
					&nbsp;&nbsp;<span id="order_lock" class="ktooltip_hover" title="Этот заказ закрыт и заблокирован для редактирования" style="display:inline-block; color: #cf4a61; font-size: 28px;"><i class="fa fa-lock" aria-hidden="true"></i></span>
				<? } else { ?>
					&nbsp;&nbsp;<span id="order_lock" class="ktooltip_hover" title="Этот заказ открыт для редактирования" style="display:inline-block; color: #4ea24e; font-size: 28px;"><i class="fa fa-unlock" aria-hidden="true"></i></span>
				<? } ?>
				
				<? if ($salary_paid) { ?>
					&nbsp;&nbsp;<span id="order_salary_paid" class="ktooltip_hover" title="По этому заказу выплачена комиссия" style="display:inline-block; color: #4ea24e; font-size: 28px;"><i class="fa fa-eur" aria-hidden="true"></i></span>
				<? } else { ?>
					&nbsp;&nbsp;<span id="order_salary_paid" class="ktooltip_hover" title="По этому заказу не выплачена комиссия" style="display:inline-block; color: grey; font-size: 28px;"><i class="fa fa-eur" aria-hidden="true"></i></span>
				<? } ?>
			</h1>
			<input type="hidden" name="order_id" value="<? echo $order_id; ?>" />
			<div id="show_manager_change" style="display:none;">
				<? if ($is_superAV || $is_superManager) { ?>
					<select id="changemanager" onchange="$('#manager_of_order').load('<? echo str_replace('&amp;', '&', $changemanager_url); ?>&manager_id='+$('#changemanager option:selected').val());">
						<option style="padding:2px;" value='0'>Не назначать никого</option>
						<? foreach ($all_managers as $mngr) { ?>
							<option style="padding:2px;" value='<? echo $mngr['user_id']; ?>'><? echo $mngr['realname'].'/'. $mngr['user_id']; ?></option>
						<? } ?>
					</select>
				<? } ?>
			</div>
			
			<div style="float:right; text-align:right;">
				<input type="text" id="go_to_order" name="go_to_order" maxlength="6" style="line-height:40px; font-size:20px; width:100px; padding:0; text-align:center; float:left;">
				<h1 class="ktooltip_hover" title="Перейти к заказу" style="margin-left:10px; padding:3px 5px; font-size:20px; border-radius:5px; border:2px solid #6A6A6A;">
					<a style="color:#6A6A6A; text-decoration:none;" onclick="go_to_order()"><i class="fa fa-external-link-square" aria-hidden="true"></i></a>
				</h1>
				
				<h1 class="ktooltip_hover" title="Посмотреть Чек" style="margin-left:10px; font-size:20px; padding:3px 5px; border-radius:5px; border:2px solid #6A6A6A;">
					<span style="color:#6A6A6A;" class="view-cheque" data-order_invoice-datetime="<?php echo $last_invoice['datetime']; ?>" data-order_invoice-author="<?php echo $last_invoice['realname']; ?>" data-order_invoice_id="<?php echo $last_invoice['order_invoice_id']; ?>"><i class="fa fa fa-eye"></i> ЧК</span>
				</h1>
				
				<h1 class="ktooltip_hover" title="Печатать Чек" style="margin-left:10px; font-size:20px; padding:3px 5px; border-radius:5px; border:2px solid #6A6A6A;">
					<span style="color:#6A6A6A;" class="print-cheque" data-order_invoice_id="<?php echo $last_invoice['order_invoice_id']; ?>"><i class="fa fa fa-print"></i> ЧК</span>
				</h1>
				
				<h1 class="ktooltip_hover" title="Коммерческое предложение" style="margin-left:10px; font-size:20px; padding:3px 5px; border-radius:5px; border:2px solid #6A6A6A;">
					<a href="<?php echo $xls1_download; ?>" style="color:#6A6A6A; text-decoration:none;"><i class="fa fa-file-excel-o"></i> КП</a>
				</h1>
				
				<h1 class="ktooltip_hover" title="Спецификация" style="margin-left:10px; font-size:20px; padding:3px 5px; border-radius:5px; border:2px solid #6A6A6A;">
					<a href="<?php echo $xls2_download; ?>" style="color:#6A6A6A; text-decoration:none;"><i class="fa fa-file-excel-o"></i> СП</a>
				</h1>

				<h1 class="ktooltip_hover" title="Предпросмотр письма" style="margin-left:10px; font-size:20px; padding:3px 5px; border-radius:5px; border:2px solid #6A6A6A;">
					<a onclick="$('#mailpreview').load('<? echo $url_mailpreview; ?>').dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true});" style="color:#6A6A6A; text-decoration:none;"><i class="fa fa-eye"></i> К</a>
				</h1>
				
				<h1 class="ktooltip_hover" title="Письмо клиенту" style="margin-left:10px; font-size:20px; padding:3px 5px; border-radius:5px; border:2px solid #6A6A6A;">
					<a href="<?php echo $url_resend; ?>" style="color:#6A6A6A; text-decoration:none;"><i class="fa fa-envelope"></i> К</a>
				</h1>
				
				<h1 class="ktooltip_hover" title="Письмо Админу" style="margin-left:10px; font-size:20px; padding:3px 5px; border-radius:5px; border:2px solid #6A6A6A;">
					<a href="<?php echo $url_resendadmin; ?>" style="color:#6A6A6A; text-decoration:none;"><i class="fa fa-envelope"></i> А</a>
				</h1>
				
				<h1 class="ktooltip_hover" title="Отмена" style="margin-left:10px; font-size:24px; padding:3px 5px; border-radius:5px; border: 2px solid #cf4a61; background:#cf4a61;">
					<a href="<?php echo $cancel; ?>" style="color:#FFF; text-decoration:none;"><i class="fa fa-mail-reply"></i></a>
				</h1>
				
				<h1 class="ktooltip_hover" title="Сохранить" style="margin-left:10px; font-size:24px; padding:3px 5px; border-radius:5px; border: 2px solid #4ea24e; background:#4ea24e;">
					<a id="main_save" onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" style="color:#FFF; text-decoration:none;" ><i class="fa fa-save"></i></a>
				</h1>
			</div>
			
		</div>
		<div class="clr"></div>
		<div class="content">			
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				
				<input type="hidden" name="language_id" value="<?php echo $language_id; ?>" /> 
				<input type="hidden" style="display:none;" id="part_num" name="part_num" value="" />
				<span style="display:none;">Номер партии: <input id="part_num_real" name="part_num_real" type="text" style="width:100px;" value="<? echo $part_num; ?>" /></span>
				
				<? if ($can_get_csi && !$yam) { ?>
					<div class="blue_heading" onclick="$('#tab-csi').toggle();" style="">Показать / скрыть информацию о оценке заказа</div>
					<div id="tab-csi">
						
						<div style="padding:5px 5px; margin-bottom:10px; <? if ($can_edit_csi) { ?>border-left:2px solid #cf4a61;<? } ?> display:inline-block;">
							<? if ($can_edit_csi) { ?>
								<input name="nbt_csi" id="nbt_csi" type="checkbox" class="checkbox onchangeedit_customer csi_input" value="1" <? if ($nbt_csi) { ?>checked="checked"<? } ?> />
								<label for="nbt_csi"><span style="display:inline-block;background-color:#F96E64;color:#FFF; padding:5px 5px;">Недозвон</span></label>
							<? } else { ?>
								<? if ($nbt_csi) { ?><span style="display:inline-block;background-color:#F96E64;color:#FFF; padding:5px 5px;">Недозвон</span><? } ?>
							<? } ?>
						</div>
						
						<div style="padding:5px 5px; margin-bottom:10px; display:inline-block;">
							<? if ($can_edit_csi) { ?>
								<input name="csi_reject" id="csi_reject" type="checkbox" class="checkbox onchangeedit_direct csi_input" value="1" <? if ($csi_reject) { ?>checked="checked"<? } ?> />
								<label for="csi_reject"><span style="display:inline-block;background-color:#F96E64;color:#FFF; padding:5px 5px;">Полный отказ от оценки заказа</span></label>
							<? } else { ?>
								<? if ($csi_reject) { ?><span style="display:inline-block;background-color:#F96E64;color:#FFF; padding:5px 5px;">Полный отказ от оценки заказа</span><? } ?>
							<? } ?>
						</div>
						
						<? if ($customer_csi_reject) { ?>
							<div style="padding:5px 5px; margin-bottom:10px; margin-left:20px; display:inline-block;">
								<span style="display:inline-block;background-color:#F96E64;color:#FFF; padding:5px 5px;">Покупатель отказался от оценок ранее</span>
							</div>
						<? } ?>
						
						<div style="padding:5px 5px; margin-bottom:10px; margin-left:20px;  display:inline-block; background-color:<? if ($order_status_id == $this->config->get('config_complete_status_id')) { ?>#4ea24e<? } else { ?>#cf4a61<? } ?>;color:#FFF;">
							Статус заказа: <? echo $order_status; ?>
						</div>
						<div style="display:inline-block;" id="csi_average_result_wrapper" <? if ($csi_reject) { ?>style="display:none;"<? } ?>>
							<div style="padding:5px 5px; margin-bottom:10px; margin-left:20px; display:inline-block; background-color:#F96E64; color:#FFF; margin-right:10px;">
								Средняя оценка:
							</div> 	<div style="display:inline-block;" id="csi_average_result"></div>
							
							<div style="margin-left:10px; padding:5px 5px; font-weight:700; display:inline-block; background-color:#F96E64; color:#FFF; font-size:18px;" id="csi_average_result_text" ><? echo (float)$csi_average; ?></div>
						</div>
						
						<? if ($orders_for_csi) { ?>
							<div style="padding:5px 5px; <? if ($can_edit_csi) { ?>border-left:2px solid #cf4a61;<? } ?> display:block;">
								Все заказы без CSI: 
								<? foreach ($orders_for_csi as $ofc) { ?>
									<div style="padding:5px 5px; margin-left:20px; display:inline-block; background-color:#<? echo $ofc['order_status']['status_bg_color']; ?>; color:#<? echo $ofc['order_status']['status_txt_color']; ?>; margin-right:10px;">
										<i class="fa <? echo $ofc['order_status']['status_fa_icon'] ?>"></i> <? echo $ofc['order_id']; ?>, <? echo $ofc['order_status']['name'] ?> <? echo $ofc['last_modified'] ?> <? if ($ofc['order_id'] == $order_id) { ?>, текущий<? } ?>
									</div>
								<? } ?>							
							</div>
						<? } ?>
						
						<div id="csi_table" <? if ($csi_reject) { ?>style="display:none;"<? } ?>>
							<table style="width:100%;" class="">
								<tr>
									<td width="33%">
										<table style="width:100%; border:0px;">
											<tr>
												<td style="font-size:18px;"><span style="font-weight:700;display:inline-block;line-height:24px;margin-top:-3px;"><i class="fa fa-handshake-o"></i>&nbsp;&nbsp;Общее впечатление</span>
													
													<input type="hidden" name="csi_mark" value="<? echo $csi_mark; ?>" id="csi_mark" class="onchangeedit_direct csi_input" />
													<div id="csi_mark_div" data-update-field="csi_mark" style="display:inline-block; margin-left:20px;"></div></td>												
												</tr>
												<tr>
													<td>	
														<? if ($can_edit_csi) { ?>												
															<textarea style="width:90%" name="csi_comment" class="onfocusedit_direct csi_input"><? echo $csi_comment; ?></textarea>
														<? } else { ?>
															<div style="padding:5px 5px; border-left:2px solid #cf4a61; display:inline-block; min-height:50px;">
																<? echo $csi_comment; ?>
															</div>
														<? } ?>
													</td>
												</tr>
											</table>
										</td>
										<td width="34%">
											<table style="width:100%; border:0px;">
												<tr>
													<td style="font-size:18px;"><span style="font-weight:700;display:inline-block;line-height:24px;margin-top:-3px;"><i class="fa fa-wheelchair-alt" aria-hidden="true"></i>&nbsp;&nbsp;Скорость доставки</span>
														
														<input type="hidden" name="speed_mark" value="<? echo $speed_mark; ?>" id="speed_mark" class="onchangeedit_direct csi_input" />
														<div id="speed_mark_div" data-update-field="speed_mark" style="display:inline-block; margin-left:20px;"></div></td>												
													</tr>
													<tr>
														<td>
															<? if ($can_edit_csi) { ?>
																<textarea style="width:90%" name="speed_comment" class="onfocusedit_direct csi_input"><? echo $speed_comment; ?></textarea>
															<? } else { ?>
																<div style="padding:5px 5px; border-left:2px solid #cf4a61; display:inline-block; min-height:50px;">
																	<? echo $speed_comment; ?>
																</div>
															<? } ?>
														</td>
													</tr>
												</table>
											</td>	
											<td width="33%">
												<table style="width:100%; border:0px;">
													<tr>
														<td style="font-size:18px;"><span style="font-weight:700;display:inline-block;line-height:24px;margin-top:-3px;"><i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;&nbsp;Качество товара</span>
															
															<input type="hidden" name="quality_mark" value="<? echo $quality_mark; ?>" id="quality_mark" class="onchangeedit_direct csi_input" />
															<div id="quality_mark_div" data-update-field="quality_mark" style="display:inline-block; margin-left:20px;"></div></td>												
														</tr>
														<tr>
															<td>
																<? if ($can_edit_csi) { ?>
																	<textarea style="width:90%" name="quality_comment" class="onfocusedit_direct csi_input"><? echo $quality_comment; ?></textarea>
																<? } else { ?>
																	<div style="padding:5px 5px; border-left:2px solid #cf4a61; display:inline-block; min-height:50px;">
																		<? echo $quality_comment; ?>
																	</div>
																<? } ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td width="33%">
													<table style="width:100%; border:0px;">
														<tr>
															<td style="font-size:18px;"><span style="font-weight:700;display:inline-block;line-height:24px;margin-top:-3px;"><i class="fa fa-headphones" aria-hidden="true"></i>&nbsp;&nbsp;Работа менеджера</span>
																
																<input type="hidden" name="manager_mark" value="<? echo $manager_mark; ?>" id="manager_mark" class="onchangeedit_direct csi_input" />
																<div id="manager_mark_div" data-update-field="manager_mark" style="display:inline-block; margin-left:20px;"></div></td>												
															</tr>
															<tr>
																<td>
																	<? if ($can_edit_csi) { ?>
																		<textarea style="width:90%" name="manager_comment" class="onfocusedit_direct csi_input"><? echo $manager_comment; ?></textarea>
																	<? } else { ?>
																		<div style="padding:5px 5px; border-left:2px solid #cf4a61; display:inline-block; min-height:50px;">
																			<? echo $manager_comment; ?>
																		</div>
																	<? } ?>
																</td>
															</tr>
														</table>
													</td>
													<td width="34%">
														<table style="width:100%; border:0px;">
															<tr>
																<td style="font-size:18px;"><span style="font-weight:700;display:inline-block;line-height:24px;margin-top:-3px;"><i class="fa fa-car" aria-hidden="true"></i>&nbsp;&nbsp;Работа курьера</span>
																	
																	<input type="hidden" name="courier_mark" value="<? echo $courier_mark; ?>" id="courier_mark" class="onchangeedit_direct csi_input" />
																	<div id="courier_mark_div" data-update-field="courier_mark" style="display:inline-block; margin-left:20px;"></div></td>												
																</tr>
																<tr>
																	<td>
																		<? if ($can_edit_csi) { ?>
																			<textarea style="width:90%" name="courier_comment" class="onfocusedit_direct csi_input"><? echo $courier_comment; ?></textarea>
																		<? } else { ?>
																			<div style="padding:5px 5px; border-left:2px solid #cf4a61; display:inline-block; min-height:50px;">
																				<? echo $courier_comment; ?>
																			</div>
																		<? } ?>
																	</td>
																</tr>
															</table>
														</td>	
														<td width="33%">
															<? if ($can_edit_csi && $csi_average == 0) { ?>
																<a style="display:none;" class="button" id="finish_csi_button" onclick="finishCSI()">Завершить опрос</a>
															<? } ?>
														</td>
													</tr>
												</table>
											</div>
										</div>
									<? } ?>
									
									<div class="blue_heading" onclick="$('#tab-order').toggle();" style="">Показать / скрыть информацию о заказе</div>
									<div id="tab-order" style="display:none">
										<table class="ordermain">
											<tr>
												<td><?php echo $text_order_id; ?></td>
												<td>#<?php echo $order_id; ?></td>
												<td><?php echo $text_shipping_method; ?></td>
												<td><?php if ($shipping_method) { ?><?php echo $shipping_method; ?><?php } ?></td>
											</tr>
											<tr>
												<td><?php echo $entry_order_status; ?></td>
												<td>
													<input type="hidden" id="order_status_id" name="order_status_id" value="<? echo $order_status_id; ?>" />
													<? echo $order_status; ?>
												</td>
												<td><?php echo $text_payment_method; ?></td>
												<td><?php echo $payment_method; ?></td>
											</tr>
											<tr>
												<td><?php echo $text_customer; ?></td>
												<td><?php if ($customer_link) { ?><a href="<?php echo $customer_link; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a><?php } else { ?><?php echo $firstname; ?> <?php echo $lastname; ?><?php } ?></td>
												<td><?php echo $text_reward; ?></td>
												<td><?php if ($reward && $customer) { ?><?php echo $reward; ?>
													<?php if (!$reward_total) { ?>
														<span id="reward"><b> </b> <a id="reward-add"><?php echo $text_reward_add; ?></a></span>
													<?php } else { ?>
														<span id="reward"><b> </b> <a id="reward-remove"><?php echo $text_reward_remove; ?></a></span>
														<?php } ?><?php } ?></td>
													</tr>
													<tr>
														<td><?php echo $text_telephone; ?></td>
														<td><?php echo $telephone; ?><span class='click2call' data-phone="<?php echo $telephone; ?>"></span></td>
														<td><?php echo $text_ip; ?></td>
														<td><?php if ($ip) { ?>
															
															<img id="geoip-flag" src="view/image/flags/de.png" style="display:none;" />
															<?php echo $ip; ?>
															
															<div style="font-size:12px;">
																<span id="geoip-results">
																</span>
																
																<script>
																	$(document).ready(function(){
																		$.ajax({
																			url: "index.php?route=sale/order/getCityByIpAddrAjax&ip=<?php echo $ip; ?>&token=<?php echo $token; ?>",
																			type: 'GET',
																			dataType: 'json',
																			success: function(json){
																				var html = '';
																				html += 'Страна: ' + json.country + '<br />';
																				html += 'Регион: ' + json.regionName + '<br />';
																				html += 'Город: ' + json.city + '<br />';
																				html += 'Провайдер: ' + json.org + '<br />';
																				html += 'Индекс: ' + json.zip + '<br />';
																				html += 'Таймзона: ' + json.timezone + '<br />';
																				
																				$('#geoip-results').html(html);
																				$('#geoip-flag').attr('src','view/image/flags/'+ json.countryCode.toLowerCase() +'.png').show();
																				
																				if (json.countryCode){
																					let selectedDCode = $('select[name=\'shipping_country_id\'] > option:selected').attr('data-iso');
																					let selectedPCode = $('select[name=\'payment_country_id\'] > option:selected').attr('data-iso');
																					
																					if (json.countryCode != selectedDCode){
																						
																						$('select[name=\'shipping_country_id\']').parent('td').parent('tr').after("<tr><td></td><td style='background-color:#cf4a61; padding:5px; color:#fff;' colspan='2'>Выбранная страна не совпадает с локацией клиента (" + json.country + ")</td></tr>");
																						$('select[name=\'shipping_country_id\']').before("<i class='fa fa-question-circle' style='font-size:24px; color:#cf4a61'></i> ");
																						
																					}
																					
																					if (json.countryCode != selectedPCode){
																						
																						$('select[name=\'payment_country_id\']').parent('td').parent('tr').after("<tr><td></td><td style='background-color:#cf4a61; padding:5px; color:#fff;' colspan='2'>Выбранная страна не совпадает с локацией клиента (" + json.country + ")</td></tr>");
																						$('select[name=\'payment_country_id\']').before("<i class='fa fa-question-circle' style='font-size:24px; color:#cf4a61'></i> ");
																						
																					}
																				}
																			}
																		});
																	});
																</script>
															</div>
														<?php } ?>
													</td>
												</tr>
												<tr>
													<td><?php echo $text_email; ?></td>
													<td><?php echo $email; ?></td>
													<td><?php echo $text_forwarded_ip; ?></td>
													<td><?php if ($forwarded_ip) { ?>
														<? foreach ($forwarded_ip as $fip) { if (trim($fip['ip'])) { ?>
															<div>
																<? if ($fip['ip_geoip_full_info']['country_code'] && file_exists(DIR_APPLICATION . '/view/image/flags/' . mb_strtolower($fip['ip_geoip_full_info']['country_code']) . '.png')) { ?>
																	<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($fip['ip_geoip_full_info']['country_code']); ?>.png" title="<? echo mb_strtolower($fip['ip_geoip_full_info']['country_code']) ?>" />
																<? } ?> &nbsp;<?php echo $fip['ip']; ?>
																
																<div style="font-size:12px;">
																	<i class="fa fa-globe" aria-hidden="true"></i> Континент: <? echo $fip['ip_geoip_full_info']['continent_code']?$fip['ip_geoip_full_info']['continent_code']:'не определен' ?>, страна: <? echo $fip['ip_geoip_full_info']['country_name']?$fip['ip_geoip_full_info']['country_name']:'не определен' ?>, город: <? echo $fip['ip_geoip_full_info']['city']?$fip['ip_geoip_full_info']['city']:'не определен' ?>
																</div>
															</div>
														<?php } ?>
													<? } ?>
													<?php } ?></td>
												</tr>
												<tr>
													<td><?php echo $text_customer_group; ?></td>
													<td><?php if ($customer_group) { ?><?php echo $customer_group; ?></td><?php } ?>
													<td><?php echo $text_accept_language; ?></td>
													<td><?php if ($accept_language) { ?><?php echo $accept_language; ?><?php } ?></td>
												</tr>
												<tr>
													<td><?php echo $text_date_added; ?></td>
													<td><?php echo $date_added; ?></td>
													<td><?php echo $text_fax; ?></td>
													<td><?php if ($fax) { ?><?php echo $fax; ?><span class='click2call' data-phone="<?php echo $fax; ?>"></span><?php } ?></td>
												</tr>
												<tr>
													<td><?php echo $text_date_modified; ?></td>
													<td><?php echo $date_modified; ?></td>
													<td><?php echo $text_store_name; ?></td>
													<td><?php echo $store_name; ?></td>
												</tr>
												<tr>
													<td><?php echo $text_invoice_no; ?></td>
													<td><?php if ($invoice_no) { ?>
														<?php echo $invoice_no; ?>
													<?php } else { ?>
														<span id="invoice"><a class="button" id="invoice-generate"><?php echo $text_generate; ?></a></span>
														<?php } ?></td>
														<td><?php echo $text_store_url; ?></td>
														<td><a onclick="window.open('<?php echo $store_url; ?>');"><u><?php echo $store_url; ?></u></a></td>
													</tr>
													<tr>
														<td><?php echo $text_total; ?></td>
														<td><?php echo $total; ?> (<? echo $currency_code; ?>)
															<input type="hidden" id="total_num" name="total_num" value="<?php echo $total_num; ?>" />
															<input type="hidden" id="currency_code" name="currency_code" value="<?php echo $currency_code; ?>" />
															<?php if ($credit && $customer) { ?>
																<?php if (!$credit_total) { ?>
																	<span id="credit"><b>[</b> <a id="credit-add"><?php echo $text_credit_add; ?></a> <b>]</b></span>
																<?php } else { ?>
																	<span id="credit"><b>[</b> <a id="credit-remove"><?php echo $text_credit_remove; ?></a> <b>]</b></span>
																<?php } ?>
																<?php } ?></td>
																<td><?php echo $text_user_agent; ?></td>
																<td><?php if ($user_agent) { ?><?php echo $user_agent; ?><?php } ?></td>
																
																<!-- SOFORP Order Referer - begin -->
																<tr>
																	<td><?php echo $text_first_referrer; ?></td>
																	<td><?php echo $first_referrer; ?></td>
																	<td><?php echo $text_last_referrer; ?></td>
																	<td><?php echo $last_referrer; ?></td>
																</tr>
																<!-- SOFORP Order Referer - end -->
																
															</tr>
															<?php if ($affiliate_id) { ?>
																<tr>
																	<td><?php echo $text_affiliate; ?></td>
																	<td><a href="<?php echo $affiliate_url; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a>
																		<input type="hidden" name="affiliate_id" value="<?php echo $affiliate_id; ?>" /></td>
																		<td><?php echo $text_commission; ?></td>
																		<td><?php echo $commission; ?>
																		<?php if (!$commission_total) { ?>
																			<span id="commission"><a class="button" id="commission-add"><?php echo $text_commission_add; ?></a></span>
																		<?php } else { ?>
																			<span id="commission"><a class="button" id="commission-remove"><?php echo $text_commission_remove; ?></a></span>
																			<?php } ?></td>
																		</tr>
																	<?php } ?>
																</table>
															</div>	  
															<div class="clr"></div>				
															<div class="blue_heading" onclick="$('#tab-actions-order').toggle();" style="display:none">Операции с заказом: допоставка, новый на покупателя, и.т.п</div>
															<div id="tab-actions-order" style="display:none">		
																<table style="width:100%;">
																	<tr>
																		<td colspan="4" class="blue_heading" style="background:#F96E64;color:white;font-size:16px;padding:7px;text-align:center;">Допоставки пока в тестовом режиме - будьте внимательны</td></tr>
																		<tr>
																			
																			<? if ($order_id2) { ?>
																				<td>Код допоставки</td>	
																				<td>
																					<input type="text" name="order_id2" value="<? echo $order_id2; ?>"  /><br />
																					<span class="help">Код привязки "РОДИТЕЛЬ-1"</span>
																				</td>
																				<td>Это допоставка для: </td>
																				<td>
																					<a href="<? echo $main_order_href; ?>" target="_blank">заказ #<? echo $main_order_num; ?></a>
																				</td>
																			<? } else { ?>
																				<td>Код допоставки</td>
																				<td>
																					<input type="text" name="order_id2" value=""  />
																					<span class="help">Код привязки "РОДИТЕЛЬ-1"</span>
																				</td>
																				<? if ($linked_orders) { ?>
																					<td>Есть допоставки: </td>
																					<td>
																						<? foreach ($linked_orders as $_lio) { ?>
																							заказ #<a href="<? echo $_lio['href']; ?>" target="_blank"><? echo $_lio['order_id']; ?> / <? echo $_lio['order_id2']; ?></a>
																							<br />
																						<? } ?>
																					</td>
																				<? } else { ?>
																					<td>У заказа нет допоставок</td>
																					<td></td>
																				<? } ?>
																			<? } ?>
																		</tr>
																		<tr>
																			<td colspan="4" height="5px;">
																				
																			</td>
																		</tr>
																	</table>
																	<table style="width:100%;">
																		<tr>
																			<td colspan="4" class="blue_heading" style="background:#F96E64;color:white;font-size:16px;padding:7px;text-align:center;">Внимание! Все операции по разделению / объединению заказов - необратимы.</td></tr>
																			<tr>
																				<td>
																					<input type="checkbox" id="do_separate_order" name="do_separate_order" value="1" />								
																				</td>
																				<td style="text-align:center;">
																					
																					<a class="button redbg" id="button_do_separate_order">Разделить на допоставки</a>
																					
																				</td>
																				<td>
																					<span class="help">
																						Будут созданы новые заказы с добавочными номерами "<? echo $order_id; ?>-1",...,"<? echo $order_id; ?>-N" в зависимости от количества поставок, на которые разделен заказ. Будут пересчитаны все итоги, кроме внесенных сумм.
																					</span>
																				</td>				
																			</tr>
																			<tr>	
																				
																			</tr>	
																			<tr>
																				<td>
																					<input id="show_products_from_order" value="" />	
																				</td>
																				<td style="width:40%; text-align:center;">
																					<a class="button" onclick="">Показать товары из заказа</a>
																				</td>
																				<td colspan="2">
																					<span class="help">
																						Будут выведены товары из заказа для добавления в текущий заказ
																					</span>
																				</td>				
																			</tr>
																		</table>
																	</div>
																	
																	<div class="clr"></div>
																	<div onclick="$('#tab-customer-history').toggle();" class="blue_heading">CRM : История заказов, транзакций, звонков, взаимоотношений покупателя</div>
																	
																	<div id="tab-customer-history" style="display:none; margin-bottom:5px;">
																		<div id="htabs" class="htabs">
																			<a href="#tab-customer-orders" id='tab_orders_switch'>Все заказы покупателя</a>
																			<a href="#tab-customer-reward" id='tab_customer_reward_switch'>Программа лояльности (бонусы)</a>
																			<a href="#tab-customer-waitlist" id='tab_waitlist_switch'>Лист ожидания покупателя</a>
																			<a href="#tab-customer-calls" id='tab_calls_switch'>История звонков покупателя</a>
																			<a href="#tab-customer-v-history" id='tab_customer_v_history_switch'>Хронология взаимоотношений с покупателем</a>
																			<div class="clr"></div>
																		</div>
																		<div id="tab-customer-orders">
																			<div id="orders-list"></div>
																		</div>
																		<div id="tab-customer-reward">
																			<div id="reward-list"></div>
																		</div>
																		<div id="tab-customer-waitlist">
																			<div id="waitlist-list"></div>
																		</div>
																		<div id="tab-customer-calls">
																			<div id="calls-list"></div>
																		</div>
																		<div id="tab-customer-v-history">
																			<div id="customer-v-history-list"></div>
																		</div>
																	</div>
																	<div class="clr"></div>
																	<div id="tab-customer">
																		<table class="orderadress" style="width:100%">
																			<th colspan="4">Основная информация</th>
																			<? if ($customer_info['is_mudak']) { ?>
																				<tr><td colspan="4" style="background: #ea8686; padding:3px; text-align:center; font-weight:700;">Неблагонадежный покупатель! Обслуживание только на условиях предоплаты!</td></tr>
																			<? } ?>
																			<tr>							
																				<td width="10%"><?php echo $entry_customer; ?></td>
																				<td width="40%">
																					
																					<b><? if ($customer_info['is_mudak']) { ?><i class='fa fa-ambulance' style='color:#cf4a61; font-size:20px;'></i>&nbsp;<? } ?>
																					<?php if ($customer_link) { ?>
																						<a target="_blank" style="font-size:20px;" href="<? echo $customer_link; ?>"><?php echo $customer; ?></a>
																						<? } ?></b>&nbsp;&nbsp;<i style="font-size:20px; cursor:pointer;" class="fa fa-sign-in go_to_store" data-customer-id="<?php echo $customer_id; ?>" data-store-id="<?php echo $store_id; ?>"></i>
																						&nbsp;&nbsp;<span class="add2ticket" data-query="object=customer&object_value=<?php echo $customer_id; ?>"></span>
																						
																						<? if ($customer_info['rja_customer']) { ?>
																							&nbsp;&nbsp;&nbsp;<span id="rja_customer" class="is_nbt ktooltip_hover" title="Отказ давать адрес" style="display:inline-block; font-size: 16px;"><i class="fa fa-snowflake-o" aria-hidden="true"></i> ОТКАЗ АДР.</span>
																						<? } else { ?>
																							&nbsp;&nbsp;&nbsp;<span id="rja_customer" class="ktooltip_hover" title="Все ок с адресом" style="display:inline-block;  font-size: 16px;"><i class="fa fa-snowflake-o" aria-hidden="true"></i> ОТКАЗ АДР.</span>
																						<? } ?>
																						
																						
																						<div style="display:inline-block; float:right;">
																							<input type="hidden" id="input_customer_id" name="customer_id" value="<?php echo $customer_id; ?>" style="width:100px;" />
																							id : <?php echo $customer_id; ?>
																							<a class="button save_button" onclick="$('#input_customer_id').get(0).type = 'text'" ><i class="fa fa-edit"></i> ID</a>
																							<a class="button redbg csi_input" id="button_do_empty_order" onclick="" style="">НОВЫЙ</a>
																							<input type="checkbox" class="csi_input" name="do_empty_order" id="do_empty_order" value="1" />
																						</div>
																						<div>									
																						</div>
																						<? if ($customer_info['customer_segments'] || $pwa || $yam) { ?>
																							<div style="clear:both;"></div>
																							
																							<?php if ($pwa) { ?>
																								<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; background:#7F00FF; color:#FFF;" >
																									<i class="fa fa-chevron-circle-right" aria-hidden="true"></i> PWA/APP
																								</div>	
																							<? } ?>
																							
																							<?php if ($yam) { ?>
																								<div style="display:inline-block; padding:2px 3px; font-size:10px; margin:3px; background:#cf4a61; color:#FFF;" >
																									<i class="fa fa-yoast" aria-hidden="true"></i> Я.Маркет
																								</div>	
																							<? } ?>
																							
																							<? if ($customer_info['customer_segments']) { ?>
																								<? foreach ($customer_info['customer_segments'] as $_segment) { ?>
																									<span style="display:inline-block; padding:3px 4px; font-size:10px; margin:3px; background:#<? if ($_segment['bg_color']) { ?><? echo $_segment['bg_color'] ?><? } else { ?>e6e9f3<? } ?>; color:#<? if ($_segment['txt_color']) { ?><? echo $_segment['txt_color'] ?><? } else { ?>696969<? } ?>">
																										<? if ($_segment['fa_icon']) { ?>
																											<i class="fa <? echo $_segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
																										<? } ?><?php echo $_segment['name']; ?>
																										<? if ($_segment['is_new']) { ?>
																											<sup style="color:#ef5e67; font-size:10px;">Новый</sup>
																											<? } ?>&nbsp;
																										</span>
																									<? } ?>
																								<? } ?>
																							<? } ?>
																						</td>
																						
																						<td class="left" width="10%">
																							<?php foreach ($stores as $store) { ?>
																								<?php if ($store['store_id'] == $store_id) { ?>
																									<?php echo $store['name']; ?>										
																								<?php } ?>
																							<?php } ?>
																							<br /><span class="help">изменить привязку виртуального магазина нельзя с 21.11.2021</span>
																							<input type="hidden" name="store_id" value="<? echo $store_id; ?>" />
																							
																						</td>
																						
																						<td class="left" width="40%">
																							<? if ($yam) { ?>	
																								<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
																									<i class="fa fa-yoast" aria-hidden="true"></i> Заказ Маркета <?php echo $yam_id; ?>
																								</div>	
																							<?php } ?>
																							
																							<? if ($yam_fake) { ?>	
																								<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#F96E64; color:#FFF;" >
																									<i class="fa fa-yoast" aria-hidden="true"></i> Тестовый
																								</div>	
																							<?php } ?>
																							
																							<? if ($yam_status) { ?>	
																								<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
																									<i class="fa fa-yoast" aria-hidden="true"></i> Статус Маркета <?php echo $yam_status; ?>
																								</div>	
																							<?php } ?>
																							
																							<? if ($yam_substatus) { ?>	
																								<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#ff7815; color:#FFF;" >
																									<i class="fa fa-yoast" aria-hidden="true"></i> Подстатус Маркета <?php echo $yam_substatus; ?>
																								</div>	
																							<?php } ?>
																							
																							<hr />
																							<? if ($yam) { ?>	
																								<?php if ($yam_shipment_date && $yam_shipment_date != '0000-00-00') { ?>
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#7F00FF; color:#FFF;" >
																										<i class="fa fa-yoast" aria-hidden="true"></i> Дата отгрузки <?php echo $yam_shipment_date; ?>
																									</div>	
																								<?php } else { ?>
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
																										<i class="fa fa-yoast" aria-hidden="true"></i> Не задана дата отгрузки
																									</div>	
																								<?php } ?>
																							<?php } ?>
																							
																							<? if ($yam) { ?>	
																								<?php if ($yam_shipment_id) { ?>
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#7F00FF; color:#FFF;" >
																										<i class="fa fa-yoast" aria-hidden="true"></i> Поставка <?php echo $yam_shipment_id; ?>
																									</div>	
																									
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#00ad07; color:#FFF;">
																										<a href="<?php echo HTTPS_CATALOG . 'yamarket-partner-api/shipment/label?shipment_id=' . $yam_shipment_id; ?>" target='_blank' style="color:#FFFFFF;text-decoration:none;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Стикера поставки <?php echo $yam_shipment_id; ?></a>
																									</div>
																									
																								<?php } else { ?>
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
																										<i class="fa fa-yoast" aria-hidden="true"></i> Не задана поставка
																									</div>	
																								<?php } ?>
																							<?php } ?>
																							
																							<? if ($yam && $yam_shipment_date && $yam_shipment_id) { ?>	
																								<?php if ($yam_box_id) { ?>
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#7F00FF; color:#FFF;" >
																										<i class="fa fa-yoast" aria-hidden="true"></i> Коробка <?php echo $yam_box_id; ?>
																									</div>		
																									
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#00ad07; color:#FFF;">
																										<a href="<?php echo HTTPS_CATALOG . 'yamarket-partner-api/order/label?order_id=' . $order_id; ?>" target='_blank' style="color:#FFFFFF;text-decoration:none;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Стикер заказа <?php echo $yam_id; ?></a>
																									</div>
																									
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#00ad07; color:#FFF;">
																										<a href="<?php echo HTTPS_CATALOG . 'yamarket-partner-api/box/label?box_id=' . $yam_box_id; ?>" target='_blank' style="color:#FFFFFF;text-decoration:none;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Стикер коробки <?php echo $yam_box_id; ?></a>
																									</div>
																									
																								<?php } else { ?>
																									<div style="display:inline-block; padding:2px 3px; font-size:14px; margin:3px; background:#cf4a61; color:#FFF;" >
																										<i class="fa fa-yoast" aria-hidden="true"></i> Не задана коробка
																									</div>	
																								<?php } ?>
																							<?php } ?>
																						</td>
																						
																						
																					</tr>
																					<tr>
																						<td colspan="2">
								<? /* if ($customer_info['discount_card']) { ?>
									<div style="clear:both;"></div>
									<div style="padding:3px; background:#FFF; border:1px dotted #CCC; display:inline-block; margin-right:8px;">
									<i class="fa fa-credit-card-alt" aria-hidden="true"></i> Карта # <? echo $customer_info['discount_card']; ?>, скидка <? echo $customer_info['discount_percent']; ?>%
									</div>
								<? } */ ?>
								<div style="padding:3px; background:#FFF; border:1px dotted #CCC; display:inline-block; margin-right:8px;">
									Заказов: <? echo $customer_info['order_count']; ?>
								</div>
								<div style="padding:3px; background:#FFF; border:1px dotted #CCC; display:inline-block; margin-right:8px;">
									Куплено на: <? echo $customer_info['total_cheque']; ?>
								</div>
								<div style="padding:3px; background:#FFF; border:1px dotted #CCC; display:inline-block; margin-right:8px;">
									Ср. чек: <? echo $customer_info['avg_cheque']; ?>
								</div>
								<? if ($customer_info['customer_comment']) { ?><div style="font-size:12px; padding:3px; background:#FFF; border:1px dotted #CCC; "><? echo $customer_info['customer_comment']; ?></div><? } ?>
							</td>
							
							<td width="10%">
								Неблагонадежный								
							</td>
							<td width="40%">
								<? if ($customer_info['is_mudak']) { ?>
									<input id="checkbox_4" class="checkbox" type="checkbox" name="customer_is_mudak" value='1' checked='checked'  />
									<label for="checkbox_4"><i class='fa fa-ambulance' style='color:#cf4a61; font-size:16px; cursor:pointer'></i></label>
									<span class='help'>При снятии обязательно изменить группу!</span>
								<? } else { ?>
									<input id="checkbox_3" class="checkbox" type="checkbox" name="customer_is_mudak" value='1' />
									<label for="checkbox_3"><i class='fa fa-ambulance' style='color:#cf4a61; font-size:16px; cursor:pointer'></i></label>
								<? } ?>
							</td>
							
						</tr>
						<tr>							
							<td width="10%">Имя, Фамилия</td>
							<td width="40%">
								<input class="onfocusedit_direct" type="text" name="firstname" value="<?php echo $firstname; ?>" style="width:40%;" /><span></span>&nbsp;
								<input class="onfocusedit_direct" type="text" name="lastname" value="<?php echo $lastname; ?>" style="width:40%;" /><span></span>
								<?php if ($error_lastname) { ?>
									<span class="error"><?php echo $error_lastname; ?></span>
								<?php } ?>
								<?php if ($error_firstname) { ?>
									<span class="error"><?php echo $error_firstname; ?></span>
								<?php } ?>
							</td>
							
							<td><?php echo $entry_email; ?></td>
							<td width="40%"><input class="onfocusedit_direct" type="text" name="email" value="<?php echo $email; ?>" /><span></span>
								<?php if ($error_email) { ?>
									<span class="error"><?php echo $error_email; ?></span>
									<?php  } ?></td>
								</tr>
								
								<tr>
									<td class="left">Группа</td>
									<td class="left"><select id="customer_group_id" name='customer_group_id' class="onchangeedit_direct">
										<?php foreach ($customer_groups as $customer_group) { ?>
											<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
												<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select><span></span></td>
									
									<td><?php echo $entry_telephone; ?></td>
									<td><input style="max-width:200px;" class="onfocusedit_direct" type="text" name="telephone" value="<?php echo $telephone; ?>" /><span></span>
										<span class='click2call' data-phone="<?php echo $telephone; ?>" <? if ($shipping_city && $current_shipping_time && !$can_call_now) { ?>style="color:#cf4a61;"<? } ?>></span>
										<span class="add2ticket" data-query="object=customer&object_value=<?php echo $customer_id; ?>"></span>
										
										<? if ($shipping_city && $current_payment_time) { ?>
											<? if ($can_call_now) { ?>
												<span style="display:inline-block; padding:3px; color:#FFF; background-color:grey;"><i class="fa fa-clock-o"></i>&nbsp;<? echo $current_payment_time; ?></span>
											<? } else { ?>
												<i class="fa fa-warning" style="color:#cf4a61;"></i>&nbsp;<span style="display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;"><i class="fa fa-clock-o"></i>&nbsp;<? echo $current_payment_time; ?></span>
											<? } ?>
										<? } ?>	
										
										<?php if ($error_telephone) { ?>
											<span class="error"><?php echo $error_telephone; ?></span>
											<?php  } ?></td>
											
											
										</tr>
										
										<tr>
											<td>Комментарий:</td><td><textarea name="comment" cols="60" rows="2" class="onfocusedit_direct" style="resize:both"><?php echo $comment; ?></textarea><span></span>
												<span class="help">этот комментарий вводит покупатель при оформлении, однако его можно редактировать и он <span style="color:#cf4a61">доступен для просмотра покупателю</span></span>
											</td>
											
											<td><div>Доп. телефон:</div>
												<div style="margin-top:5px;">контактное лицо доп. номера</div>
											</td>
											<td>	
												<div><input style="max-width:200px;" class="onfocusedit_direct" type="text" name="fax" value="<?php echo $fax; ?>" /><span></span>
													<span class='click2call' data-phone="<?php echo $fax; ?>" <? if ($shipping_city && $current_shipping_time && !$can_call_now) { ?>style="color:#cf4a61;"<? } ?>></span>
												</div>
												<div style="margin-top:5px;">
													<input style="max-width:250px;" class="onfocusedit_direct" type="text" name="faxname" value="<?php echo $faxname; ?>" />
												</div>
											</td>
										</tr>
										
										<tr>
											
											<td>День рождения</td>
											<td><input type="text" class="date onfocusedit_direct" name="birthday" value="<?php echo ($customer_info['birthday'] != '0000-00-00' && $customer_info['birthday'] != '1970-01-01' && $customer_info['birthday'] != '-0001-11-30')?$customer_info['birthday']:''; ?>"/><span></span></td>						
											
											<td>Паспорт серия:</td>
											<td><input class="" type="text" name="passport_serie" value="<?php echo $customer_info['passport_serie']; ?>" style="width:80%" /><span></span>
											</td>
											
											
											
										</tr>
										<tr>
											
											<td style="border-left:2px solid #cf4a61;">Доставка вместе 
												<div style="margin-top:10px;" onclick="addRelatedOrderRow();">
													<i class="fa fa-plus" style="color:#4ea24e; font-size:20px;"></i>
												</div>
											</td>
											
											<td id="related-orders-td">
												<?php $roiRow = 1; if ($related_orders) { ?>
													<?php  foreach ($related_orders as $related_order_id) { ?>
														<div style="margin-bottom:5px;">
															<input type="text" style="width:140px;" name="related_order_id[]" id="related_order_id_<?php echo $roiRow; ?>" value="<? echo $related_order_id; ?>" />&nbsp;<i class="fa fa-arrow-circle-left"></i>&nbsp;
															
															<?php foreach ($possible_related_orders as $_pro_order_id) { ?>		
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$('#related_order_id_<?php echo $roiRow; ?>').val('<? echo $_pro_order_id; ?>')"><? echo $_pro_order_id; ?></span>&nbsp;&nbsp;
																<?php if (!in_array($_pro_order_id, $related_orders)) { ?>
																<?php } ?>
															<?php } ?>										
															<span onclick="$(this).parent().remove();">
																<i class="fa fa-close" style="color:#cf4a61; font-size:20px;"></i>
															</span>
														</div>
														<?php $roiRow++; } ?>
													<?php } ?>	
													
													<script>
														var roiRow = <?php echo $roiRow; ?>;
													</script>
													<script>
														function addRelatedOrderRow(){
															var html = '<div style="margin-bottom:5px;">';
															html += '<input type="text" style="width:140px;" name="related_order_id[]" id="related_order_id_'+ roiRow +'" value="" />&nbsp;<i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;';
															<?php foreach ($possible_related_orders as $_pro_order_id) { ?>
																html += '<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$(\'#related_order_id_'+roiRow+'\').val(\'<? echo $_pro_order_id; ?>\')"><? echo $_pro_order_id; ?></span>&nbsp;&nbsp;&nbsp;';
															<?php } ?>
															html += '<span onclick="$(this).parent().remove();"><i class="fa fa-close" style="color:#cf4a61; font-size:20px;"></i></span>';
															html += '</div>';
															
															roiRow++;
															
															$('#related-orders-td').append(html);										
														}
													</script>
												</td>
												
												
												
												<td>Паспорт выдан:</td>
												<td><input class="" type="text" name="passport_given" value="<?php echo $customer_info['passport_given']; ?>" style="width:80%" /><span></span>
												</td>
											</tr>
											<tr>
												<td colspan="4" style="text-align:left;">
													<input id="do_update_customer" class="checkbox" type="checkbox" value="1" name="do_update_customer" />
													<label for="do_update_customer">Обновить справочник покупателей, <small>будут обновлены: имя, фамилия, мейл, день рождения, телефон, группа покупателя, первый/основной адрес из адреса оплаты. Также имя покупателя скопируется в детали оплаты, серия-номер паспорта в детали доставки.</small></label>								
												</td>
											</tr>
											<tr>
												<td colspan="4"  style="border-left:2px solid #cf4a61;">
													<table width="100%" border=0px>
														<tr>
															<td width="350px">
																<? if ($from_waitlist) { ?>
																	<div style="margin-bottom:4px;">
																		<span style="color:#4ea24e; font-weight:700;padding-left:49px;"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;&nbsp;Заказ из листа ожидания</span>
																	</div>
																<? } ?>
																<div>
																	<input id="preorder_checkbox" class="checkbox onchangeedit_direct" type="checkbox" name="preorder" value="1" <? if ($preorder) { ?>checked="checked"<? } ?> />&nbsp;
																	<label for="preorder_checkbox"><span style="color:#000; font-weight:700;"><i class="fa fa-question" aria-hidden="true"></i>&nbsp;&nbsp;Уточнить цену и нал.</span></label>
																</div>
																<div style="margin-top:7px;">
																	<input id="urgent_checkbox" class="checkbox onchangeedit_direct" type="checkbox" name="urgent" value="1" <? if ($urgent) { ?>checked="checked"<? } ?> />&nbsp;
																	<label for="urgent_checkbox"><span style="color:#cf4a61; font-weight:700;"><i class="fa fa-space-shuttle" aria-hidden="true"></i>&nbsp;&nbsp;Срочный заказ</span></label>
																</div>
																<div style="margin-top:7px;">
																	<input id="urgent_buy_checkbox" class="checkbox onchangeedit_direct" type="checkbox" name="urgent_buy" value="1" <? if ($urgent_buy) { ?>checked="checked"<? } ?> />&nbsp;
																	<label for="urgent_buy_checkbox"><span style="color:#7F00FF; font-weight:700;"><i class="fa fa-amazon" aria-hidden="true"></i>&nbsp;&nbsp;Приоритетная закупка</span></label>
																	&nbsp;&nbsp;
																	<span id="analyze-buy-preview"></span><i class="fa fa-bell" id="analyze-buy-button" style="cursor:pointer;color: #7F00FF"></i>	
																</div>
																<div style="margin-top:7px;">
																	<input id="wait_full_checkbox" class="checkbox onchangeedit_direct" type="checkbox" name="wait_full" value="1" <? if ($wait_full) { ?>checked="checked"<? } ?> />&nbsp;
																	<label for="wait_full_checkbox"><span style="color:#85B200; font-weight:700;"><i class="fa fa-th-list" aria-hidden="true"></i>&nbsp;&nbsp;Клиент ждет полную комплектацию</span></label>								
																</div>
																<div style="margin-top:7px;">
																	<input id="ua_logistics_checkbox" class="checkbox onchangeedit_direct" type="checkbox" name="ua_logistics" value="1" <? if ($ua_logistics) { ?>checked="checked"<? } ?> />&nbsp;
																	<label for="ua_logistics_checkbox"><span style="color:#005BBB; font-weight:700;"><i class="fa fa-bus" aria-hidden="true"></i>&nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?>ua.png" />&nbsp;&nbsp;Логистика через Украину</span></label>								
																</div>	
															</td>										
															<td>
																<div>
																	Планируемая <b>дата доставки заказа от</b>
																	<input class="date onfocusedit_direct" autocomplete="false" name="date_delivery" value="<? echo $date_delivery; ?>" /><span></span>
																	
																	<i title="По умолчанию: <? echo date('m.d'); ?> <? if ($shipping_country_id == 220) { ?>+10 дней<? } else { ?>+2 недели +2 дня<? } ?>" class="fa fa-question-circle ktooltip_hover" aria-hidden="true"></i>											
																</div>
																<div style="margin-top:7px;">
																	Планируемая <b>дата доставки заказа до</b>
																	<input  class="date onfocusedit_direct" autocomplete="false" name="date_delivery_to" value="<? echo $date_delivery_to; ?>" /><span></span>
																	
																	<i title="По умолчанию: 
																	<? echo date('m.d'); ?> <? if ($shipping_country_id == 220) { ?>+2 недели<? } else { ?>+2 недели +5 дней<? } ?>" class="fa fa-question-circle ktooltip_hover" aria-hidden="true"></i>		
																	
																	<i style="color:#cf4a61;" title="Внимание! Эта дата будет указана в чеке курьеру!" class="fa fa-exclamation-triangle ktooltip_hover" aria-hidden="true"></i>									
																</div>	
																<div style="margin-top:7px;">
																	<b><i class="fa fa-truck" style="color:red"></i> Фактическая дата доставки</b> заказа
																	<input class="date onfocusedit_direct csi_input" autocomplete="false" name="date_delivery_actual" value="<? echo $date_delivery_actual; ?>" /><span></span>
																	
																	<i title="Устанавливается после того, как будут известны сроки. По умолчанию: Дата доставки до" class="fa fa-question-circle ktooltip_hover" aria-hidden="true"></i>		
																	
																	<i style="color:#cf4a61;" title="Внимание! Эта дата будет указана в чеке курьеру!" class="fa fa-exclamation-triangle ktooltip_hover" aria-hidden="true"></i>						
																</div>	
															</td>
															<td>
																<div>
																	<b>Отображать</b> в ЛК?
																	<i class="fa fa-car" aria-hidden="true"></i>
																	<select name="display_date_in_account" class="onchangeedit_direct">
																		<? if ($display_date_in_account) { ?>
																			<option value="1" selected="selected">Отображать</option>
																			<option value="0" >Скрыть</option>
																		<? } else { ?>
																			<option value="1">Отображать</option>
																			<option value="0" selected="selected">Скрыть</option>
																		<? } ?>
																	</select><span></span>
																	<span class="help">включить отображение информации о согласовании даты доставки в ЛК клиента</span>	
																</div>
																<div style="margin-top:7px;">
																	<input name="do_recalculate_date" id="do_recalculate_date" value="1" type="checkbox">&nbsp;<a id="button_recalculate_date" onclick="" class="button redbg">Заполнить даты от сегодня</a>&nbsp;&nbsp;
																</div>
																
															</td>
															<td width="10%" style="text-align:right;">
																<a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button save_button">Сохранить</a>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										
									</div>
									<div class="clr"></div>
									<? if ($order_products_return) { ?>
										<div id="tab-returns">
											<div id="tab-product">
												<table class="list orderproduct" style="width:100%">
													<thead>
														<tr><th colspan="12" style="background-color:#F96E64">Возвраты по заказу</th></tr>
														<tr>
															<td>id</td>
															<td class="right">П/К</td>
															<td style="width:45px;"></td>
															<td class="left"><?php echo $column_product; ?></td>
															<td class="left"><?php echo $column_model; ?></td>
															<td class="left">Кол-во</td>
															<td class="left">Цена</td>
															<td class="left">Сумма</td>
															<td class="left">Дата</td>
															<td class="left">Перезаказ</td>
															<td class="left">Причина</td>
															<td class="left">Статус</td>
															<td class="left">Действия</td>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($order_products_return as $order_product_return) { ?>
															<tr>
																<td class="left"><? echo $order_product_return['return_id']; ?></td>
																<td valign="center" style="text-align:center; color:#FFF; background-color:<? if ($order_product_return['to_supplier'] == 1) { ?>#F96E64<? } elseif ($order_product_return['to_supplier'] == 2) { ?>#85B200<? } elseif ($order_product_return['to_supplier'] == 0) { ?>#1f4962<? } ?>;">
																	<? if ($order_product_return['to_supplier'] == 1) { ?>П
																	<? } elseif ($order_product_return['to_supplier'] == 2) { ?>О
																<? } elseif ($order_product_return['to_supplier'] == 0) { ?>К<? } ?>
															</td>
															<td class="left" style="padding:0 5px;"><?php if ($order_product_return['image'] != "") {?><img src="<?php echo $order_product_return['image'];?>" style="padding: 1px;"><?php } ?></td>
															<td class="left"><?php echo $order_product_return['name']; ?><br /><a href="<? echo $order_product_return['product_adminlink']; ?>" target="_blank" style="padding-bottom:4px"><i class="fa fa-edit" style="font-size:16px;"></i></a></td>
															<td class="left"><?php echo $order_product_return['model']; ?><br /><span style="font-size:10px"><a href="<?php echo $store_url; ?>index.php?route=product/product&product_id=<? echo $order_product_return['product_id']; ?>" target="_blank">сайт &rarr;</a></span></td>
															<td class="left"><?php echo $order_product_return['quantity']; ?></td>
															<td class="left" style="white-space: nowrap;"><?php echo $order_product_return['price']; ?>
															<br /><span style="font-size:10px; color:#666">1С: <b><?php echo $order_product_return['pricewd_national']; ?></b>
															</span>
														</td>
														<td class="left" style="white-space: nowrap;"><?php echo $order_product_return['total']; ?><br /><span style="font-size:10px; color:#666">1С: <b><?php echo $order_product_return['totalwd_national']; ?></b>
														</span></td>
														<td class="left"><?php echo $order_product_return['date_added']; ?></td>
														<td class="left"><?php echo $order_product_return['reorder_id']; ?></td>
														<td class="left"><?php echo $order_product_return['reason']; ?>
													</td>
													<td class="left"><?php echo $order_product_return['status']; ?></td>
													<td class="right">
														<a class="return-history-button" data-return-id='<? echo $order_product_return['return_id']; ?>' style="padding-bottom:4px"><i class="fa fa-clock-o" style="font-size:16px;"></i></a>
														<a href="<? echo $order_product_return['edit']; ?>" target="_blank" style="padding-bottom:4px"><i class="fa fa-edit" style="font-size:16px;"></i></a>
													</td>
												</tr>
											<? } ?>
											<tr>
												<td colspan="12" style="padding-top:5px;">
													<span class="help">
														<span style="background-color:#F96E64; padding:3px; display:inline-block; color:white;">П</span> - возврат поставщику, 
														<span style="background-color:#1f4962; padding:3px; display:inline-block; color:white;">К</span> - возврат клиенту, 
														<span style="background-color:#85B200; padding:3px; display:inline-block; color:white;">О</span> - отказ клиента до поставки 
													</span>
												</td>
											</tr>
										</tbody>
									</div>
									<div id="return-history-preview"></div>
									<div class="clr"></div>
								<? } ?>
								<div id="tab-product">
									<table class="list orderproduct" style="width:100%">
										<thead>
											<tr><th colspan="11">Товары, отмены и обработанные отказы</th></tr>
											<tr>
												<td></td>
												<td class="left">Наличие</td>
												<td class="left"></td>
												<td style="width:45px;"></td>
												<td class="left"><?php echo $column_product; ?></td>
												<td class="left"><?php echo $column_model; ?></td>
												<td class="right">Кол-во</td>
												<td class="right">Склад</td>
												<td class="right">Цена, <? echo $currency_code; ?></td>
												<td class="right"><?php echo $column_total; ?>, <? echo $currency_code; ?></td>
												<? if (!$order_products_nogood) { ?>
													<? if ($is_buyer) { ?>
														<td class="right" style="min-width: 55px;"><span onclick="$('.buyer-row').toggle()" style="cursor:pointer; border-bottom:1px dashed black;">показать&#160;<i class="fa fa-euro"></i></span></td>
													<? } ?>
												<? } else { ?>
													<td class="right"><i class="fa fa-clock-o"></i></td>
												<? } ?>
												
											</tr>
										</thead>
										<?php $product_row = 0; ?>
										<?php $option_row = 0; ?>
										<?php $download_row = 0; ?>
										<tbody id="product">								
											<style type="text/css">
												.red{background-color:#cf4a61}
												.green{background-color:#4ea24e}
											</style>
											<?php if ($order_products || $order_products_nogood || $order_products_untaken) { ?>
												
												<? if ($order_products_untaken) { ?>
													<tr>
														<td colspan="11" style="background-color:#7F00FF!important; color:white;padding:7px;text-align:center;">Отказы, которые привез обратно курьер. Необходимо оформить возвраты!</td>
													</tr>			
													
													<?php foreach ($order_products_untaken as $order_product) { ?>
														<tr id="product-row-untaken<?php echo $product_row; ?>" class="filter">
															<td class="center" style="width: 3px;">
															</td>
															<td style="text-align:center;">
															</td>
															<td style="text-align:center;">
															</td>
															<td class="left" style="padding:0 5px;"><?php if ($order_product['image'] != "") {?><img src="<?php echo $order_product['image'];?>" style="padding: 1px;"><?php } ?></td>
															
															<td class="left"><?php echo $order_product['name']; ?>
															
															<? if ($order_product['ao_text']) { ?>															
																<br /><span class="help"><? echo $order_product['ao_text'];  ?></span>
															<? } ?>
															
															<div style="margin-top:5px; min-width:200px;">
																<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;"  onclick="$('#td-replace-row-<?php echo $product_row; ?>').load('index.php?route=sale/order/getReplaceProductsAjax&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&product_id=<? echo $order_product['product_id']; ?>'); $('#product-replace-row-<?php echo $product_row; ?>').show();">зам</a>&nbsp;<span style='cursor:pointer;' onclick="$('#product-replace-row-<?php echo $product_row; ?>').hide();"><i class="fa fa-close"></i></span>
																
																&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="product_fast_hbt_<? echo $order_product['product_id']; ?>" class="product_fast_hbt" data-product-id='<? echo $order_product['product_id']; ?>'>нал</a>&nbsp;<i class="fa fa-info"></i>
																
																&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="ask_buyers_<? echo $order_product['product_id']; ?>" class="ask_buyers" data-quantity='<? echo $order_product['quantity']; ?>' data-product-id='<? echo $order_product['product_id']; ?>' >нал?</a>&nbsp;<i title="Спросить закупщиков по наличию." class="fa fa-question-circle  ktooltip_hover" aria-hidden="true"></i>
																
																&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="ask_buyers_<? echo $order_product['product_id']; ?>" class="ask_hobotun" data-quantity='<? echo $order_product['quantity']; ?>' data-product-id='<? echo $order_product['product_id']; ?>' >нал?</a>&nbsp;<i title="Спросить Хоботуна по наличию." class="fa fa-smile-o  ktooltip_hover" aria-hidden="true"></i>&nbsp;&nbsp;
																
																&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" href="<?php echo $store_url; ?>index.php?route=product/product&product_id=<? echo $order_product['product_id']; ?>" target="_blank">сайт<i class="fa fa-external-link" aria-hidden="true"></i></a>
																
																<span id="fast_hbt_preview_<? echo $order_product['product_id']; ?>"></span>
																<script>
																	$('#product_fast_hbt_<? echo $order_product['product_id'] ?>').click(function(){
																		$.ajax({
																			url:  'index.php?route=catalog/product/getFastHBT&product_id=<? echo $order_product['product_id'] ?>&token=<? echo $token ?>',
																			dataType: 'html',
																			beforeSend : function(){
																				$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('');
																				$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
																			},
																			success : function(html){
																				$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('');
																				$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Наличие по <? echo $order_product['product_id'] ?>'});
																			}
																		})
																	});
																</script>
															</div>
														</td>
														<td class="left">
															<div style="font-size:11px; padding:3px; display:inline-block; color:#FFF; background-color:#4ea24e;"><?php echo $order_product['product_id']; ?></div>
															<div style="font-size:11px; display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><?php echo $order_product['model']; ?></div>
															<div style="font-size:11px; display:inline-block; padding:3px; color:#FFF; background-color:grey;">EAN: <?php echo $order_product['ean']; ?></div>
														</td>
														<td class="right">
															<span id="quantity_<?php echo $product_row; ?>"><?php echo $order_product['quantity']; ?></span>
														</td>
														
														<td>
														</td>
														<td class="right">
															<span id="price_<?php echo $product_row; ?>"><?php echo $order_product['price_national']; ?></span>
														</td>
														<td class="right">
															<span id="total_<?php echo $product_row; ?>"><?php echo $order_product['total_national']; ?></span>
														</td>
														
														<td align="center" width="50px" style="width:50px;">
															<a class="icon_product" href="<? echo $order_product['adminlink']; ?>" target="_blank" style="padding-bottom:4px; display:inline-block;"><i class="fa fa-edit" style="font-size:20px;"></i></a>
															
															<a class="icon_product" style="padding-bottom:4px; display:inline-block;" onclick="$('#return_dialog').load('index.php?route=sale/order/order_return_ajax&token=<? echo $token ?>', {order_id: '<? echo $order_id; ?>', order_product_id: '<? echo $order_product['order_product_id']; ?>', index: '<?php echo $product_row; ?>', 'from' : 'order_product_untaken'}, function(){ $(this).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Оформление возврата по заказу <? echo $order_id; ?>'}); } )"><i class="fa fa-mail-reply" style="font-size:20px;" ></i></a>
														</td>
														
														<tr id="product-replace-row-<?php echo $product_row; ?>" style="display:none;">
															<td id="td-replace-row-<?php echo $product_row; ?>" colspan="10" style="padding:5px;">
																
															</td>
														</tr>
														
													</tr>											
												<? } ?>
												<?php unset($order_product); ?>	
											<? } ?>
											
											<? if ($order_products_nogood) { ?>
												<tr>
													<td colspan="11"  style="background-color:#cf4a61!important; color:white;padding:7px;text-align:center;">Нет в наличии (эти товары не участвуют в подсчете)</td>
												</tr>
												
												<?php foreach ($order_products_nogood as $order_product) { ?>
													
													<tr id="product-row-nogood<?php echo $product_row; ?>" class="filter">
														
														<td class="center" style="width: 3px;">
															&rarr;
														</td>
														
														<? if ($products_can_not_be_deleted) { ?>
															<td data-id="<?php echo $order_product['order_product_id']; ?>" class="left <? echo $order_product['good']?'green':'red' ?>" style=	'color:white;font-weight:700;text-align:center;cursor:not-allowed;'>
																<span id='goodProduct_result_<?php echo $order_product['order_product_id']; ?>'><?php echo $order_product['good']?'Есть':'Нет'; ?></span><input id="<?php echo $order_product['order_product_id']; ?>_good" type="hidden" name="order_product_nogood[<?php echo $product_row; ?>][good]" value="<?php echo $order_product['good']; ?>" />
															</td>
														<? } else { ?>
															<td data-id="<?php echo $order_product['order_product_id']; ?>" class="good_td left <? echo $order_product['good']?'green':'red' ?>" style=	'color:white;font-weight:700;text-align:center;cursor:pointer;'>
																<span id='goodProduct_result_<?php echo $order_product['order_product_id']; ?>'><?php echo $order_product['good']?'Есть':'Нет'; ?></span><input id="<?php echo $order_product['order_product_id']; ?>_good" type="hidden" name="order_product_nogood[<?php echo $product_row; ?>][good]" value="<?php echo $order_product['good']; ?>" />
															</td>
														<? } ?>
														
														<td style="text-align:center;">
															<div>
																<input class="nogood_waitlist checkbox" type="checkbox" id="order_product_nogood_<?php echo $product_row; ?>_waitlist" name="order_product_nogood[<?php echo $product_row; ?>][waitlist]" data-id="<?php echo $product_row; ?>" data-order-product-id="<? echo $order_product['order_product_id']; ?>" value="1" <? if ($order_product['waitlist']) { ?>checked="checked"<? } ?> /><label for="order_product_nogood_<?php echo $product_row; ?>_waitlist"><i title="Включить/выключить в листе ожидания" class="fa fa-clock-o ktooltip_hover_side" style="font-size:24px;color:<? if ($order_product['waitlist']) { ?>#4ea24e;<? } else { ?>#cf4a61;<? } ?>"></i></label>
															</div>
														<? /*	<div style="margin-top:8px;">	
															<input class="nogood_onsite checkbox csi_input" type="checkbox" id="order_product_nogood_<?php echo $product_row; ?>_onsite" name="order_product_nogood[<?php echo $product_row; ?>][onsite]" data-id="<?php echo $product_row; ?>" data-is-good-product='0' data-order-product-id="<? echo $order_product['order_product_id']; ?>" value="1" <? if ($order_product['onsite']) { ?>checked="checked"<? } ?> /><label for="order_product_nogood_<?php echo $product_row; ?>_onsite"><i  title="Включить/выключить на сайте / в каталоге" class="fa fa-shopping-basket ktooltip_hover_side" aria-hidden="true" style="font-size:20px;color:<? if ($order_product['onsite']) { ?>#4ea24e;<? } else { ?>#cf4a61;<? } ?>"></i></label>
															</div>
														*/ ?>
													</td>
													<input type="hidden" name="order_product_nogood[<?php echo $product_row; ?>][order_product_id]" value="<?php echo $order_product['order_product_id']; ?>" />
													
													<td class="left" style="padding:0 5px;"><?php if ($order_product['image'] != "") {?><img src="<?php echo $order_product['image'];?>" style="padding: 1px;"><?php } ?></td>
													<td class="left"><?php echo $order_product['name']; ?>
													
													<? if ($order_product['ao_text']) { ?>															
														<br /><span class="help"><? echo $order_product['ao_text'];  ?></span>
													<? } ?>
													
													<div style="margin-top:5px; min-width:200px;">
														<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;"  onclick="$('#td-replace-row-<?php echo $product_row; ?>').load('index.php?route=sale/order/getReplaceProductsAjax&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&product_id=<? echo $order_product['product_id']; ?>'); $('#product-replace-row-<?php echo $product_row; ?>').show();">зам</a>&nbsp;<span style='cursor:pointer;' onclick="$('#product-replace-row-<?php echo $product_row; ?>').hide();"><i class="fa fa-close"></i></span>
														
														&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="product_fast_hbt_<? echo $order_product['product_id']; ?>" class="product_fast_hbt" data-product-id='<? echo $order_product['product_id']; ?>'>нал</a>&nbsp;<i class="fa fa-info"></i>
														
														&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="ask_buyers_<? echo $order_product['product_id']; ?>" class="ask_buyers" data-quantity='<? echo $order_product['quantity']; ?>' data-product-id='<? echo $order_product['product_id']; ?>' >нал?</a>&nbsp;<i title="Спросить закупщиков по наличию." class="fa fa-question-circle  ktooltip_hover" aria-hidden="true"></i>
														
														&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="ask_buyers_<? echo $order_product['product_id']; ?>" class="ask_hobotun" data-quantity='<? echo $order_product['quantity']; ?>' data-product-id='<? echo $order_product['product_id']; ?>' >нал?</a>&nbsp;<i title="Спросить Хоботуна по наличию." class="fa fa-smile-o  ktooltip_hover" aria-hidden="true"></i>&nbsp;&nbsp;
														
														&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" href="<?php echo $store_url; ?>index.php?route=product/product&product_id=<? echo $order_product['product_id']; ?>" target="_blank">сайт<i class="fa fa-external-link" aria-hidden="true"></i></a>
														
														<span id="fast_hbt_preview_<? echo $order_product['product_id']; ?>"></span>
														<script>
															$('#product_fast_hbt_<? echo $order_product['product_id'] ?>').click(function(){
																$.ajax({
																	url:  'index.php?route=catalog/product/getFastHBT&product_id=<? echo $order_product['product_id'] ?>&token=<? echo $token ?>',
																	dataType: 'html',
																	beforeSend : function(){
																		$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('');
																		$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
																	},
																	success : function(html){
																		$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('');
																		$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Наличие по <? echo $order_product['product_id'] ?>'});
																	}
																})
															});
														</script>
													</div>
												</td>
												<td class="left">
													<div><?php echo $order_product['model']; ?></div>
													<div style="margin-top:4px;"><span style="font-size:11px; display:inline-block; padding:3px; color:#FFF; background-color:grey;">EAN: <?php echo $order_product['ean']; ?></span></div>
												</td>
												<td class="right">
													<input disabled='disabled' class="quantity_change_nogood" id="quantity_<?php echo $product_row; ?>" data-id="<?php echo $product_row; ?>" type="text" name="order_product_nogood[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>" style="width:60px;background:light-grey;"
													/></td>
													<td>
													</td>
													<td class="right">
														<input disabled='disabled' class="price_change_nogood" id="price_national_<?php echo $product_row; ?>" data-id="<?php echo $product_row; ?>" type="text" name="order_product_nogood[<?php echo $product_row; ?>][price_national]" value="<?php echo $order_product['price_national']; ?>" style="width:80%;background:light-grey;" />
													</td>
													<td class="right">
														<input disabled='disabled'  class="total_nochange_nogood" id="total_national_<?php echo $product_row; ?>" type="text" name="order_product_nogood[<?php echo $product_row; ?>][total_national]" value="<?php echo $order_product['total_national']; ?>" style="width:80%;background:light-grey;" />
													</td>
													<td class="right" style="width:50px;">
														<? if ($order_product['new_order_id']) { ?>
															Новый заказ: <a href="<? echo $order_product['new_order_href']; ?>" target="_blank"><? echo $order_product['new_order_id']; ?></a><br />
														<? } else { ?>
															<a class="icon_product" href="<? echo $order_product['product_waitlist_href']; ?>" target="_blank" style="font-size:20px; display:inline-block; padding-bottom:3px"><i class="fa fa-clock-o"></i></a>
															<a class="icon_product" href="<? echo $order_product['order_waitlist_href']; ?>" target="_blank" style="font-size:20px; display:inline-block; padding-bottom:3px"><i class="fa fa-clock-o"></i></a>
															<a class="icon_product" href="<? echo $order_product['adminlink']; ?>" target="_blank" style="padding-bottom:4px; display:inline-block;"><i class="fa fa-edit" style="font-size:20px;"></i></a>
														<? }  ?>
													</td>
												</tr>
												
												<? if ($order_product['set']) { ?>
													<? foreach ($order_product['set'] as $set_product) { ?>
														<tr>
															<td colspan="3" style="border-right:2px solid #85B200;">
																
															</td>
															<td class="left" style="padding:0 5px;"><?php if ($set_product['image'] != "") {?><img src="<?php echo $set_product['image'];?>" style="padding: 1px;"><?php } ?></td>
															<td>
																<? if ($set_product['short_name']) { ?>
																	<?php echo $set_product['short_name'];?>
																<? } else { ?>
																	<?php echo $set_product['name'];?>
																<? } ?>
															</td>
															<td><?php echo $set_product['model'];?><br /><span style="font-size:10px"><a href="<?php echo $store_url; ?>index.php?route=product/product&product_id=<? echo $set_product['product_id']; ?>" target="_blank">на сайте <i class="fa fa-external-link" aria-hidden="true"></i></a></span></td>
															<td><?php echo $set_product['quantity'];?></td>
															<td><?php echo $set_product['price_national'];?></td>
															<td><?php echo $set_product['total_national'];?></td>
															<td>
																<a href="<? echo $set_product['adminlink']; ?>" target="_blank" style="padding-bottom:4px">Редакт. <i class="fa fa-edit"></i></a>
															</td>
														</tr>
													<? } ?>
												<? } ?>
												
												<tr id="product-replace-row-<?php echo $product_row; ?>" style="display:none;">
													<td id="td-replace-row-<?php echo $product_row; ?>" colspan="10" style="padding:5px;">
														
													</td>
												</tr>
												
												<?php $product_row++; ?>
											<?php } ?>											
										<? } ?>
										
										<?php if ($order_products_nogood || $order_products_untaken) { ?>
											
											<tr>
												<td colspan="11" style="background-color:#4ea24e!important;  color:white;padding:7px;text-align:center;">Есть в наличии (учитываются в формировании сумм)</td>
											</tr>
											<thead>
												<tr>
													<td></td>
													<td class="left">Наличие</td>
													<!--td class="left">Доставлен</td-->
													<td class="left">Поставка / партия</td>
													<td style="width:45px;"></td>
													<td class="left"><?php echo $column_product; ?></td>
													<td class="left"><?php echo $column_model; ?></td>
													<td class="right">Кол-во</td>
													<td class="right">Склад</td>
													<td class="right">Цена, <? echo $currency_code; ?></td>
													<td class="right"><?php echo $column_total; ?>, <? echo $currency_code; ?></td>
													<td>
														<? if ($is_buyer) { ?>
															<span onclick="$('.buyer-row').toggle()" style="cursor:pointer; border-bottom:1px dashed black;">показать<i class="fa fa-euro"></i></span>
														<? } ?>
													</td>
												</tr>
											</thead>
											
											
										<?php } ?>
										
										<? $delivery_num = 1; ?>
										<? $set_supply_row = 0; ?>
										<? $supply_row = 0; ?>
										<?php foreach ($order_products as $order_product) { ?>
											
											<? if ($delivery_num != $order_product['delivery_num']) {  $delivery_num = $order_product['delivery_num']; ?>
											<tr>
												<td colspan="10" style="height:1px;background:#85B200;"></td>
											</tr>
										<? } ?>
										
										<tr id="product-row<?php echo $product_row; ?>" class="filter">
											<td class="center" style="width: 3px;">
												
												<? if ($products_can_not_be_deleted) { ?>
													&rarr;
												<? } else { ?>
													<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick='swal({ title: "Точно удалить товар?", text: "При новом добавлении товар будет добавлен с новой ценой!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, удалить!", cancelButtonText: "Отмена",  closeOnConfirm: false }, function() { $("#product-row<?php echo $product_row; ?>").remove(); recount_totals(); $("#main_save").trigger("click"); });' />
												<? } ?>
												
											</td>
											
											<? if ($products_can_not_be_deleted) { ?>
												
												<td class="left <? echo $order_product['good']?'green':'red' ?>" style='color:white;font-weight:700;text-align:center;cursor:not-allowed;'>
													<span id='goodProduct_result_<?php echo $order_product['order_product_id']; ?>'><?php echo $order_product['good']?'Есть':'Нет'; ?></span>
													<input id="<?php echo $order_product['order_product_id']; ?>_good" type="hidden" name="order_product[<?php echo $product_row; ?>][good]" value="<?php echo $order_product['good']; ?>" />
												</td>
												
											<? } else { ?>
												
												<td data-id="<?php echo $order_product['order_product_id']; ?>" class="good_td left <? echo $order_product['good']?'green':'red' ?>" style='color:white;font-weight:700;text-align:center;cursor:pointer;'>
													<span id='goodProduct_result_<?php echo $order_product['order_product_id']; ?>'><?php echo $order_product['good']?'Есть':'Нет'; ?></span>
													<input id="<?php echo $order_product['order_product_id']; ?>_good" type="hidden" name="order_product[<?php echo $product_row; ?>][good]" value="<?php echo $order_product['good']; ?>" />
												</td>
												
											<? } ?>
											
												<!--td data-id="<?php echo $order_product['order_product_id']; ?>" class="taken_td left <? echo $order_product['taken']?'green':'red' ?>" style='color:white;font-weight:700;text-align:center;cursor:pointer;'>
													<span id='takenProduct_result_<?php echo $order_product['order_product_id']; ?>'><?php echo $order_product['taken']?'Да':'Нет'; ?></span>
													<input id="<?php echo $order_product['order_product_id']; ?>_taken" type="hidden" name="order_product[<?php echo $product_row; ?>][taken]" value="<?php echo $order_product['taken']; ?>" />
												</td-->
												<input id="<?php echo $order_product['order_product_id']; ?>_taken" type="hidden" name="order_product[<?php echo $product_row; ?>][taken]" value="<?php echo $order_product['taken']; ?>" />
												<td class="left" style="text-align:center;">
													
													<div>
														<i class="fa fa-minus delivery-num-minus" data-product-id="<?php echo $product_row; ?>" style="cursor:pointer;"></i>&nbsp;
														<span id="delivery_num_actual_<?php echo $product_row; ?>" style="font-size:16px;"><?php echo $order_product['delivery_num']; ?></span>&nbsp;
														<i class="fa fa-plus delivery-num-plus" data-product-id="<?php echo $product_row; ?>"  style="cursor:pointer;"></i>
														
														<input id="delivery_num_<?php echo $product_row; ?>" class="delivery_num" type="hidden" name="order_product[<?php echo $product_row; ?>][delivery_num]" value="<?php echo $order_product['delivery_num']; ?>" style="width:20px;" />
													</div>	
													<div style="margin-top:5px;">
														<input data-product-id="<?php echo $product_row; ?>" data-order-product-id="<? echo $order_product['order_product_id']; ?>" data-delivery-num="<?php echo $order_product['delivery_num']; ?>" class="part_num" id="part_num_<?php echo $product_row; ?>" type="text" name="order_product[<?php echo $product_row; ?>][part_num]" value="<?php echo $order_product['part_num']; ?>" style="width:80px;" />
													</div>
													
													<? /*		<div style="margin-top:10px;">
														<input class="good_onsite checkbox" type="checkbox" id="order_product_<?php echo $product_row; ?>_onsite" name="order_product_good[<?php echo $product_row; ?>][onsite]" data-id="<?php echo $product_row; ?>" data-is-good-product='1' data-order-product-id="<? echo $order_product['order_product_id']; ?>" value="1" <? if ($order_product['onsite']) { ?>checked="checked"<? } ?> />
														<? if (!$order_product['onsite']) { ?>
														<label for="order_product_<?php echo $product_row; ?>_onsite" style="color:#cf4a61;"><i title="Включить/выключить на сайте / в каталоге" class="fa fa-shopping-basket ktooltip_hover_side" aria-hidden="true" style="font-size:20px;"></i>&nbsp;<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:20px;"></i></label>
														<? } else { ?>
														<label for="order_product_<?php echo $product_row; ?>_onsite" style="color:#4ea24e;"><i title="На сайте / в каталоге" class="fa fa-shopping-basket ktooltip_hover_side" aria-hidden="true" style="font-size:20px;"></i></label>
														<? } ?>
														</div>
													*/ ?>
												</td>
												
												<td class="left" style="padding:0 5px;">
													<? if ( $order_product['colored_similar']) { ?>
														<span style="display:inline-block; height:20px; width:20px; border-radius:50%; background-color:<? echo $order_product['colored_similar'] ?>"></span>
													<? } ?>	
													<?php if ($order_product['image'] != "") {?><img src="<?php echo $order_product['image'];?>" style="padding: 1px;"><?php } ?></td>
													
													
													<td class="left">
														
														<? if ($order_product['real_product']['does_not_exist']) { ?>
															<span class="help" style="color:white; background-color:#cf4a61; padding:4px; display:inline-block;">Внимание! Товара с таким кодом не существует в справочнике товаров! Рекомендуется удалить товар из заказа и добавить заново!</span>
														<? } ?>
														
														<textarea rows="3" name="order_product[<?php echo $product_row; ?>][name]" style="width:97%; height:40px;" ><?php echo $order_product['name']; ?></textarea><br />
														
														<?php if ($yam && $this->config->get('config_yam_offer_id_prefix') && $this->config->get('config_yam_offer_id_prefix_enable')) { ?>
															<span style="font-size:11px; display:inline-block; float:right; padding:3px; color:#FFF; background-color:#cf4a61;"><i class="fa fa-yoast" aria-hidden="true"></i> <?php echo $this->config->get('config_yam_offer_id_prefix') . $order_product['product_id']; ?></span>	
														<? } ?>
														
														<?php if ($order_product['ean']) { ?>
															<span style="font-size:11px; display:inline-block; float:right; padding:3px; color:#FFF; background-color:grey;"><?php echo $order_product['ean']; ?></span>	
														<?php } ?>
														
														<?php if ($order_product['asin']) { ?>
															<span style="font-size:11px; display:inline-block; float:right; padding:3px; color:#FFF; background-color:#FF9900;"><?php echo $order_product['asin']; ?></span>	
														<?php } ?>
														
														<span style="font-size:11px; display:inline-block; float:right; padding:3px; color:#FFF; background-color:#4ea24e;"><?php echo $order_product['product_id']; ?></span>
														
														<? if ($order_product['birthday_active_product']) { ?>
															<span class="help" style="color:#4ea24e"><i style="font-size:20px; color:#4ea24e;" class="fa fa-gift" aria-hidden="true"></i> На этот товар действует скидка ко дню рождения -<? echo $birthday_discount_percent; ?>%</span>
														<? } ?>
														
														<? if ($order_product['is_returned']) { ?>
															<span class="help" style="color:#cf4a61">На этот товар оформлен возврат!</span>
														<? } ?>
														
														<? if ($order_product['ao_text']) { ?>
															<span class="help"><? echo $order_product['ao_text'];  ?></span>
														<? } ?>
														
														<? if ($order_product['coupon_active_product'] && $order_has_coupon['type'] == 'P') { ?>
															<span class="help" style="color:#4ea24e">промокод: <? echo $order_has_coupon['code']; ?> (-<? echo (int)$order_has_coupon['discount']; ?>%)</span>
														<? } ?>
														
														<? if ($order_has_cumulative_discount) { ?>
															<? if ($order_product['cumulative_discount_active_product']) { ?>
																<span class="help" style="color:#4ea24e">накопительная скидка: (-<? echo (int)$cumulative_discount_percent; ?>%)</span>
															<? } else { ?>
																<span class="help" style="color:#cf4a61">накопительная скидка не действует</span>
															<? } ?>
														<? } ?>
														
														<div style="clear:both"></div>
														<div style="margin-top:5px; min-width:200px;">
															<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none;" onclick="$('#close-related-and-replace-<?php echo $product_row; ?>').show(); $('#td-related-row-<?php echo $product_row; ?>').load('index.php?route=sale/order/getRelatedProductsAjax&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&product_id=<? echo $order_product['product_id']; ?>'); $('#product-related-row-<?php echo $product_row; ?>').show();"><i class="fa fa-link ktooltip_hover" title="Аксессуары" style="font-size:20px; color:#7F00FF;"></i></a>&nbsp;														
															
															&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none;"  onclick="$('#close-related-and-replace-<?php echo $product_row; ?>').show(); $('#td-replace-row-<?php echo $product_row; ?>').load('index.php?route=sale/order/getReplaceProductsAjax&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&product_id=<? echo $order_product['product_id']; ?>'); $('#product-replace-row-<?php echo $product_row; ?>').show();"><i class="fa fa-refresh ktooltip_hover" title="Заменители" style="font-size:20px; color:#cf4a61;"></i></a>
															
															<?php if ($this->config->get('config_rainforest_enable_api') && !$order_product['amzn_ignore']) { ?>	
																<?php if ($order_product['ean'] || $order_product['asin']) { ?>
																	&nbsp;&nbsp;
																		<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none;" onclick="$('#close-related-and-replace-<?php echo $product_row; ?>').show(); $('#product-amazon-row-<?php echo $product_row; ?>').show(); $('#td-amazon-row-<?php echo $product_row; ?>').load('index.php?route=kp/amazon/getProductOffers&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&product_id=<? echo $order_product['product_id']; ?>');"><i class="fa fa-amazon ktooltip_hover" title="Цены Amazon в базе (возможно, устаревшие)" style="font-size:20px; color:#FF9900;"></i>
																		</a>


																		&nbsp;&nbsp;
																		<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none;" onclick="$('#close-related-and-replace-<?php echo $product_row; ?>').show(); $('#product-amazon-row-<?php echo $product_row; ?>').show(); $('#td-amazon-row-<?php echo $product_row; ?>').load('index.php?route=kp/amazon/getProductOffers&token=<?php echo $token; ?>&explicit=1&order_id=<? echo $order_id; ?>&product_id=<? echo $order_product['product_id']; ?>');"><i class="fa fa-amazon ktooltip_hover" title="Обновить и отобразить цены с Amazon" style="font-size:20px; color:#cf4a61;"></i>
																		</a>
																<?php } ?>
															<?php } ?>
															
															
															<span style='cursor:pointer; display:none;' id="close-related-and-replace-<?php echo $product_row; ?>" onclick="$('#product-related-row-<?php echo $product_row; ?>').hide(); $('#product-replace-row-<?php echo $product_row; ?>').hide(); $('#product-amazon-row-<?php echo $product_row; ?>').hide();"><i class="fa fa-close"></i></span>
															
															
															
															<a class="icon_product" style="float:right;" href="<?php echo $store_url; ?>index.php?route=product/product&product_id=<? echo $order_product['product_id']; ?>" target="_blank"><i class="fa fa-external-link" style="font-size:20px;"></i></a>
															
															
														<? /*
															&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="product_fast_hbt_<? echo $order_product['product_id']; ?>" class="product_fast_hbt" data-product-id='<? echo $order_product['product_id']; ?>'>нал</a>&nbsp;<i class="fa fa-info"></i>&nbsp;&nbsp;
															
															&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="ask_buyers_<? echo $order_product['product_id']; ?>" class="ask_buyers" data-quantity='<? echo $order_product['quantity']; ?>' data-product-id='<? echo $order_product['product_id']; ?>' >нал?</a>&nbsp;<i title="Спросить закупщиков по наличию." class="fa fa-question-circle ktooltip_hover" aria-hidden="true"></i>&nbsp;&nbsp;
															
															&nbsp;&nbsp;<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="ask_buyers_<? echo $order_product['product_id']; ?>" class="ask_hobotun" data-quantity='<? echo $order_product['quantity']; ?>' data-product-id='<? echo $order_product['product_id']; ?>' >нал?</a>&nbsp;<i title="Спросить Хоботуна по наличию." class="fa fa-smile-o  ktooltip_hover" aria-hidden="true"></i>
														*/ ?>

													</div>

													<?php if ($this->config->get('config_rainforest_enable_api') && !$order_product['amzn_ignore']) { ?>	
															<?php if ($order_product['ean'] || $order_product['asin']) { ?>
															<div style="font-size:10px; margin-top:10px;">
																<i class="fa fa-clock-o"></i> LU: <?php echo date('Y-m-d', strtotime($order_product['amzn_last_offers'])); ?><br />
																<?php if (!$order_product['amzn_no_offers']) { ?>
																	<span style="color:#4ea24e"><i class="fa fa-thumbs-up"></i> есть предложения</span><br />
																	<i class="fa fa-info-circle"></i> BP: <?php echo $order_product['amazon_best_price']; ?><br />
																	<i class="fa fa-info-circle"></i> LP: <?php echo $order_product['amazon_lowest_price']; ?>
																<?php } else { ?>
																<span style="color:#cf4a61"><i class="fa fa-thumbs-down"></i> нет предложений</span>
																<?php }?>
															</div>
															<?php } ?>
														<?php } ?>
													
													<span id="fast_hbt_preview_<? echo $order_product['product_id']; ?>"></span>
													<script>
														$('#product_fast_hbt_<? echo $order_product['product_id'] ?>').click(function(){
															$.ajax({
																url:  'index.php?route=catalog/product/getFastHBT&product_id=<? echo $order_product['product_id'] ?>&token=<? echo $token ?>',
																dataType: 'html',
																beforeSend : function(){
																	$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('');
																	$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
																},
																success : function(html){
																	$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html('');
																	$('#fast_hbt_preview_<? echo $order_product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Наличие по <? echo $order_product['product_id'] ?>'});
																}
															})
														});
													</script>
													
													
													<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_product_id]" value="<?php echo $order_product['order_product_id']; ?>" />
													<input type="hidden" name="order_product[<?php echo $product_row; ?>][ao_id]" value="<?php echo $order_product['ao_id']; ?>" />
													<input type="hidden" name="order_product[<?php echo $product_row; ?>][ao_product_id]" value="<?php echo $order_product['ao_product_id']; ?>" />
													<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $order_product['product_id']; ?>" />
													<input type="hidden" name="order_product[<?php echo $product_row; ?>][from_stock]" value="<?php echo (int)$order_product['from_stock']; ?>" id="hidden_from_stock_<?php echo $order_product['order_product_id']; ?>" />
													<input type="hidden" name="order_product[<?php echo $product_row; ?>][from_bd_gift]" value="<?php echo (int)$order_product['from_bd_gift']; ?>" id="hidden_from_from_bd_gift_<?php echo $order_product['order_product_id']; ?>" />
													
													<?php foreach ($order_product['option'] as $option) { ?>
														- <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][order_option_id]" value="<?php echo $option['order_option_id']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>" />
														<?php $option_row++; ?>
													<?php } ?>
													<?php foreach ($order_product['download'] as $download) { ?>
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][order_download_id]" value="<?php echo $download['order_download_id']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][name]" value="<?php echo $download['name']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][filename]" value="<?php echo $download['filename']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][mask]" value="<?php echo $download['mask']; ?>" />
														<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][remaining]" value="<?php echo $download['remaining']; ?>" />
														<?php $download_row++; ?>
														<?php } ?></td>
														<td class="left" colspan="2"  valign="top" style="vertical-align: top;">
															<table style="border:0px; width:100%;" border="0px">
																<tr>
																	<td style="border:0px; vertical-align: top;" valign="top">
																		<input style="width:80%" type="text" name="order_product[<?php echo $product_row; ?>][model]" value="<?php echo $order_product['model']; ?>" />
																		
																	</td>
																	<td style="border:0px; vertical-align: top;" valign="top">
																		<div>
																			<input class="quantity_change" id="quantity_<?php echo $product_row; ?>" data-id="<?php echo $product_row; ?>" type="number" name="order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>" style="width:60px; float:left; margin-left:5px;" />
																			<? if ($order_product['quantity'] > 1 && $is_buyer) { ?>
																				<span style="display:inline-block; width:35px; text-align:center;  float:left; margin-left:5px;">
																					<input type="hidden" value="0" id="divide_<? echo $order_product['order_product_id'] ?>_quantity" />
																					<i class="fa fa-minus divide-minus" style="font-size:9px;cursor:pointer;" data-elem="divide_<? echo $order_product['order_product_id'] ?>"></i>
																					
																					<span id="divide_<? echo $order_product['order_product_id'] ?>" data-order-product-id="<? echo $order_product['order_product_id'] ?>" data-max-divide="<? echo $order_product['quantity'] - 1; ?>" style="font-weight:700;">0</span>
																					
																					<i class="fa fa-plus divide-plus" style="cursor:pointer; font-size:9px;" data-elem="divide_<? echo $order_product['order_product_id'] ?>"></i>
																					<span style="font-size:16px; font-weight:700; cursor:pointer;" onclick="divide_product(<? echo $order_product['order_product_id'] ?>);" class="do_divide" data-order-product-id="<? echo $order_product['order_product_id'] ?>" ><i class="fa fa-braille" aria-hidden="true"></i></span>
																				</span>
																			<? } ?>
																		</div>		
																	</td>
																</tr>
																<tr>
																	<td colspan="2" style="border:0px; border-top:1px; vertical-align: bottom;" valign="bottom">
																		<span class="sources_for_<? echo $order_product['product_id']; ?>"><? include(dirname(__FILE__) . '/order_form.sources.tpl'); ?></span>
																		<textarea rows="3" class="onfocusedit_source" data-row="<?php echo $product_row; ?>" data-order-product-id="<? echo $order_product['order_product_id'] ?>" data-product-id="<? echo $order_product['product_id'] ?>" name="order_product[<?php echo $product_row; ?>][source]" style="width:90%; height:40px; margin-top:5px;"><? echo $order_product['source']; ?></textarea><span></span>
																	</td>
																</tr>
															</table>
															
														</td>
														<td width="120px">
															<? if ($order_product['from_stock']) { ?>
																<div style="margin-top:7px;">
																	<span style="font-size:11px; display:inline-block; padding:2px 3px; background-color:#4ea24e; color:#FFF;" class="ktooltip_hover" title="Покупатель положил в корзину товар из категории СТОК"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Из Сток</span>
																</div>
															<? } ?>
															<? if ($order_product['is_in_stock_category']) { ?>
																<div style="margin-top:7px;" >
																	<span style="font-size:11px; display:inline-block; padding:2px 3px; background-color:#4ea24e; color:#FFF;" class="ktooltip_hover" title="Товар СЕЙЧАС ЕСТЬ в категории СТОК"><i class="fa fa-info-circle" aria-hidden="true"></i> Кат. Сток</span>
																</div>
															<? } ?>
															<div style="margin-top:7px;">
																<input type="hidden" id="<?php echo $order_product['order_product_id']; ?>_from_stock" name="order_product[<?php echo $product_row; ?>][from_stock]" value="<? echo (int)$order_product['from_stock']; ?>" />
																<input id="<?php echo $order_product['order_product_id']; ?>_from_stock_fake" data-fake="<?php echo $order_product['order_product_id']; ?>_from_stock" class="checkbox onchangeedit_orderproduct checkbox_from_stock" type="checkbox" data-field-name='from_stock' data-order-product-id="<?php echo $order_product['order_product_id']; ?>" value="1" <? if ($order_product['from_stock']) { ?>checked="checked"<? } ?> />
																<label for="<?php echo $order_product['order_product_id']; ?>_from_stock_fake" style="color:<? if (!$order_product['from_stock']) { ?>#cf4a61<? } else { ?>#4ea24e<? } ?>">
																	<i style="font-size:18px;" class="fa fa-rocket" aria-hidden="true"></i></label><span></span></div>
																	<? foreach ($countries as $country) { ?>
																		
																		<? if ($country['iso_code_2'] == 'DE' || $country['country_id'] == $shipping_country_id) { ?>
																			
																			<div>
																				<input type="hidden" id="reserve_<? echo mb_strtolower($country['iso_code_2']) ?>_<? echo $order_product['order_product_id'] ?>_quantity" name="reserve_<? echo mb_strtolower($country['iso_code_2']) ?>_<? echo $order_product['order_product_id'] ?>_quantity" value="<? echo min($order_product[$country['warehouse_identifier']], $order_product['quantity']); ?>" />
																				<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($country['iso_code_2']) ?>.png" title="<? echo mb_strtolower($country['iso_code_2']) ?>" /> 
																				
																				<? if ($order_product[$country['warehouse_identifier']] >= $order_product['quantity']) { ?>					
																					<span style="font-size:11px; display:inline-block; padding:2px 3px; background-color:#4ea24e; color:#FFF;"><? echo $order_product[$country['warehouse_identifier']]; ?> / <? echo $order_product[$country['warehouse_identifier'] . '_onway']; ?></span>
																				<? } elseif ($order_product[$country['warehouse_identifier']] < $order_product['quantity'] && $order_product[$country['warehouse_identifier']] > 0) { ?>
																					<span style="font-size:11px; display:inline-block; padding:2px 3px; background-color:#ffaa56; color:#FFF;"><? echo $order_product[$country['warehouse_identifier']]; ?> / <? echo $order_product[$country['warehouse_identifier'] . '_onway']; ?></span>
																				<? } elseif ($order_product[$country['warehouse_identifier']] == 0) { ?>
																					<span style="font-size:11px; display:inline-block; padding:2px 3px; background-color:#cf4a61; color:#FFF;"><? echo $order_product[$country['warehouse_identifier']]; ?> / <? echo $order_product[$country['warehouse_identifier'] . '_onway']; ?></span>
																				<? } ?>																																									
																				
																				<? if (isset($order_product['reserves'][$country['iso_code_2']])) { ?>
																					<input type="hidden" id="reserved_<? echo mb_strtolower($country['iso_code_2']) ?>_<? echo $order_product['order_product_id'] ?>_quantity" name="reserve_<? echo mb_strtolower($country['iso_code_2']) ?>_<? echo $order_product['order_product_id'] ?>_quantity" value="<? echo min($order_product[$country['warehouse_identifier']], $order_product['quantity']); ?>" />
																					
																					<span style="font-size:11px; display:inline-block; padding:2px 3px; background-color:#4ea24e; color:#FFF;">
																						<i class="fa fa-lock" aria-hidden="true"></i>&nbsp;<? echo $order_product['reserves'][$country['iso_code_2']]; ?> шт.</span>
																						
																						&nbsp;<i class="fa fa-unlock" aria-hidden="true" style="color:#cf4a61; cursor:pointer; " onclick="remove_from_reserve('<? echo $order_product['order_product_id'] ?>', '<? echo mb_strtolower($country['iso_code_2']) ?>');"></i>
																					<? } else { ?>
																						
																						<? if (($country['iso_code_2'] == 'DE' || $country['country_id'] == $shipping_country_id) && $order_product[$country['warehouse_identifier']] > 0) {  ?>
																							&nbsp;&nbsp;<span style="cursor:pointer; border-bottom:1px dashed #696969; color:#4ea24e;" onclick="put_to_reserve('<? echo $order_product['order_product_id'] ?>', '<? echo mb_strtolower($country['iso_code_2']) ?>');">
																								<i class="fa fa-lock" aria-hidden="true"></i></span> 
																								<i class="fa fa-minus reserve-minus" style="font-size:9px;cursor:pointer;" data-elem="reserve_<? echo mb_strtolower($country['iso_code_2']) ?>_<? echo $order_product['order_product_id'] ?>"></i>
																								<span id="reserve_<? echo mb_strtolower($country['iso_code_2']) ?>_<? echo $order_product['order_product_id'] ?>" data-warehouse="<? echo mb_strtolower($country['iso_code_2']) ?>" data-order-product-id="<? echo $order_product['order_product_id'] ?>" data-max-reserve="<? echo min($order_product[$country['warehouse_identifier']], $order_product['quantity']); ?>" style="font-weight:700;"><? echo min($order_product[$country['warehouse_identifier']], $order_product['quantity']); ?></span>
																								<i class="fa fa-plus reserve-plus" style="cursor:pointer; font-size:9px;" data-elem="reserve_<? echo mb_strtolower($country['iso_code_2']) ?>_<? echo $order_product['order_product_id'] ?>"></i>
																							<? } ?>
																						<? } ?>
																					</div>
																					
																				<? } ?>
																			<? } ?>		
																			
																			
																			<? foreach ($countries as $country) { ?>
																				<? if ($country['iso_code_2'] != 'DE' && $country['country_id'] != $shipping_country_id) { ?>
																					
																					<div class='hidden_reserved' style="display:none;">																		
																						<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($country['iso_code_2']) ?>.png" title="<? echo mb_strtolower($country['iso_code_2']) ?>" /> 
																						
																						<? if ($order_product[$country['warehouse_identifier']] >= $order_product['quantity']) { ?>																		
																							<span style="font-size:10px; display:inline-block; padding:2px 3px; background-color:#4ea24e; color:#FFF;"><? echo $order_product[$country['warehouse_identifier']]; ?> / <? echo $order_product[$country['warehouse_identifier'] . '_onway']; ?></span>
																						<? } elseif ($order_product[$country['warehouse_identifier']] < $order_product['quantity'] && $order_product[$country['warehouse_identifier']] > 0) { ?>
																							<span style="font-size:10px; display:inline-block; padding:2px 3px; background-color:#ffaa56; color:#FFF;"><? echo $order_product[$country['warehouse_identifier']]; ?> / <? echo $order_product[$country['warehouse_identifier'] . '_onway']; ?></span>
																						<? } elseif ($order_product[$country['warehouse_identifier']] == 0) { ?>
																							<span style="font-size:10px; display:inline-block; padding:2px 3px; background-color:#cf4a61; color:#FFF;"><? echo $order_product[$country['warehouse_identifier']]; ?> / <? echo $order_product[$country['warehouse_identifier'] . '_onway']; ?></span>
																						<? } ?>						
																					</div>
																				<? } ?>
																			<? } ?>
																			<div style="font-size:10px; cursor:pointer;" onclick="$('.hidden_reserved').each(function(){$(this).toggle();});"><i class="fa fa-angle-double-down" aria-hidden="true"></i></div>
																			
																			<? if ($order_product['local_stock']) { ?>
																				
																				<div style="color:#4ea24e;font-size:16px;"><i class="fa fa-life-ring" aria-hidden="true"></i> <b>Есть лок.</b></div>
																				
																			<? } ?>
																		</td>
																		<td class="right" valign="top" style="vertical-align:top">
																			<input class="price_change" id="price_national_<?php echo $product_row; ?>" data-id="<?php echo $product_row; ?>" type="text" name="order_product[<?php echo $product_row; ?>][price_national]" value="<?php echo $order_product['price_national']; ?>" style="width:80%;" /><br />	 				  
																			<input type="hidden" name="order_product[<?php echo $product_row; ?>][original_price_national]" value="<?php echo $order_product['original_price_national']; ?>">					
																			
																			<? if ($order_product['price_has_been_changed_by_buyer']) { ?>
																				<div>
																					<? if ($order_product['price_has_been_changed_by_buyer_up']) { ?>																			
																						<span style="font-size:12px; display:inline-block; padding:2px 3px; margin-top:3px; background-color:#4ea24e; color:#FFF;">цена снизилась: <i class="fa fa-chevron-down" aria-hidden="true"></i>&nbsp; <?php echo $order_product['original_price_national']; ?></span>
																					<? } else { ?>
																						<span style="font-size:12px; display:inline-block; padding:2px 3px; margin-top:3px; background-color:#cf4a61; color:#FFF;">цена выросла: <i class="fa fa-chevron-up" aria-hidden="true"></i>&nbsp; <?php echo $order_product['original_price_national']; ?></span>
																					<? } ?>
																				</div>
																			<? } ?>
																			
																			<? /* вывод цены без скидки - в заказе */?>
																			<div>
																				<span style="font-size:12px;display:inline-block; padding:2px 3px; background-color:grey; color:#fff">В чеке: <?php echo $order_product['price_national_txt']; ?></span>&nbsp;&nbsp;
																				
																				<span class="ktooltip_hover" title="Цена по внутреннему курсу магазина" style="font-size:12px;display:inline-block; padding:2px 3px; margin-top:3px; background-color:grey; color:#fff"><?php echo $order_product['price_in_eur']; ?></span>
																				
																				<span class="ktooltip_hover" title="Цена по реальному курсу банка" style="font-size:12px;display:none; padding:2px 3px; margin-top:3px; background-color:#e4c25a;"><?php echo $order_product['price_in_eur_by_real_cource']; ?></span>
																			</div>
																			
																			<? /* вывод цены со скидкой - фактической цены продажи */?>
																			<div>
																				<span style="font-size:12px;display:inline-block; padding:2px 3px; background-color:#7F00FF; color:#fff">Фактически: <?php echo $order_product['pricewd_national_txt']; ?>
																				<input type="hidden" name="order_product[<?php echo $product_row; ?>][pricewd_national]" value="<?php echo $order_product['pricewd_national']; ?>">
																			</span>&nbsp;&nbsp;
																			<span class="ktooltip_hover" title="Цена по внутреннему курсу магазина" style="font-size:12px;display:inline-block; padding:2px 3px; margin-top:3px;  background-color:#7F00FF; color:#fff"><?php echo $order_product['pricewd_in_eur']; ?></span>
																			
																			<span class="ktooltip_hover" title="Цена по реальному курсу банка" style="font-size:12px;display:none; padding:2px 3px; margin-top:3px; background-color:#cf4a61; color:#FFF"><?php echo $order_product['pricewd_in_eur_by_real_cource']; ?></span>
																		</div>																	
																		
																		<input type="hidden" name="order_product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price']; ?>" />
																		
																		<?php if ($order_product['reward']  && $order_product['points_one']) { ?>
																			<div class="green ktooltip_hover" title="Будет начислено бонусов за покупку товара" style="display:inline-block;font-size:12px; padding:3px 5px; color:#FFF; margin-top:3px; ">
																				+<span><?php echo $order_product['points_one']; ?></span> бонусов
																			</div><br />
																		<?php } ?>
																		
																		<?php if ($order_product['points_used_one_txt']) { ?>
																			<div class="red ktooltip_hover" title="Оплачено бонусами в текущем заказе" style="display:inline-block; padding:3px 5px; color:#FFF; font-size:12px; margin-top:3px; ">
																				Бонусами <?php echo $order_product['points_used_one_txt']; ?>
																			</div><br />
																		<?php } ?>
																		
																		<div style="margin-top:5px;">
																			<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" id="tell_pricemanager_<? echo $order_product['product_id']; ?>" class="tell_pricemanager" data-quantity='<? echo $order_product['quantity']; ?>' data-product-id='<? echo $order_product['product_id']; ?>' >неакт!</a>&nbsp;<i title="Уведомить о неактуальности цены" class="fa fa-warning  ktooltip_hover" aria-hidden="true"></i>
																		</div>
																	</td>
																	<td class="right" valign="top" style="vertical-align:top">
																		<input class="total_nochange" id="total_national_<?php echo $product_row; ?>" type="text" name="order_product[<?php echo $product_row; ?>][total_national]" value="<?php echo $order_product['total_national']; ?>" style="width:80%;" /><br />
																		
																		<? /* вывод цены без скидки - в заказе */?>
																		<div>
																			<span style="font-size:12px;display:inline-block; padding:2px 3px; background-color:grey; color:#fff">В чеке: <?php echo $order_product['total_national_txt']; ?></span>&nbsp;&nbsp;
																			
																			<span class="ktooltip_hover" title="Цена по внутреннему курсу магазина" style="font-size:12px;display:inline-block; padding:2px 3px; margin-top:3px; background-color:grey; color:#fff"><?php echo $order_product['total_in_eur']; ?></span>
																			
																			<span class="ktooltip_hover" title="Цена по реальному курсу банка" style="display:none;font-size:12px;display:none; padding:2px 3px; margin-top:3px; background-color:#e4c25a;"><?php echo $order_product['total_in_eur_by_real_cource']; ?></span>
																		</div>
																		
																		<? /* вывод цены со скидкой - фактической цены продажи */?>
																		<div>
																			<span style="font-size:12px;display:inline-block; padding:2px 3px; background-color:#7F00FF; color:#fff">Фактически: <?php echo $order_product['totalwd_national_txt']; ?>
																			<input type="hidden" name="order_product[<?php echo $product_row; ?>][totalwd_national]" value="<?php echo $order_product['totalwd_national']; ?>">
																		</span>&nbsp;&nbsp;
																		
																		<span class="ktooltip_hover" title="Цена по внутреннему курсу магазина" style="font-size:12px;display:inline-block; padding:2px 3px; margin-top:3px; background-color:#7F00FF; color:#fff"><?php echo $order_product['totalwd_in_eur']; ?></span>
																		
																		<span class="ktooltip_hover" title="Цена по реальному курсу банка" style="display:none;font-size:12px;display:none; padding:2px 3px; margin-top:3px; background-color:#cf4a61; color:#FFF"><?php echo $order_product['totalwd_in_eur_by_real_cource']; ?></span>
																	</div>
																	
																	<input type="hidden" name="order_product[<?php echo $product_row; ?>][total]" value="<?php echo $order_product['total']; ?>" />																	
																	<input type="hidden" name="order_product[<?php echo $product_row; ?>][tax]" value="<?php echo $order_product['tax']; ?>" />
																	
																	<input type="hidden" id="reward_<?php echo $product_row; ?>" name="order_product[<?php echo $product_row; ?>][reward]" value="<?php echo $order_product['reward']; ?>" />
																	
																	<input type="hidden" id="reward_one_<?php echo $product_row; ?>" name="order_product[<?php echo $product_row; ?>][reward_one]" value="<?php echo $order_product['points_one']; ?>" />
																	
																	<?php if ($order_product['reward']  && $order_product['points']) { ?>
																		<div class="green ktooltip_hover" title="Будет начислено бонусов за покупку товара в сумме" style="display:inline-block; padding:3px 5px; font-size:12px; color:#FFF; margin-top:3px; ">
																			+<span id="reward_points_<?php echo $product_row; ?>"><?php echo $order_product['points']; ?></span> бонусов
																		</div><br />
																	<?php } ?>
																	
																	
																	<?php if ($order_product['points_used_total_txt']) { ?>
																		<div class="red ktooltip_hover" title="Оплачено бонусами в текущем заказе" style="display:inline-block; padding:3px 5px; color:#FFF; font-size:12px; margin-top:3px; ">
																			Бонусами <?php echo $order_product['points_used_total_txt']; ?>
																		</div><br />
																	<?php } ?>
																	
																</td>
																<td align="center" width="50px" style="width:50px;">
																	<a class="icon_product" href="<? echo $order_product['product_waitlist_href']; ?>" target="_blank" style="padding-bottom:4px; display:inline-block;"><i class="fa fa-clock-o" style="font-size:20px;"></i></a>
																	<a class="icon_product" href="<? echo $order_product['adminlink']; ?>" target="_blank" style="padding-bottom:4px; display:inline-block;"><i class="fa fa-edit" style="font-size:20px;"></i></a>
																	<? if ($is_buyer || $is_client_manager || true) { ?>
																		<br /><span class="icon_product" onclick="$('#product-buyer-row<?php echo $product_row; ?>').toggle(); $('#product-buyer-supply-row<?php echo $product_row; ?>').toggle()" style="cursor:pointer; ; display:inline-block;"><i class="fa fa-eur" style="font-size:20px;"></i></span>
																		<a class="icon_product" style="padding-bottom:4px; display:inline-block;" onclick="$('#return_dialog').load('index.php?route=sale/order/order_return_ajax&token=<? echo $token ?>', {order_id: '<? echo $order_id; ?>', order_product_id: '<? echo $order_product['order_product_id']; ?>', index: '<?php echo $product_row; ?>'}, function(){ $(this).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Оформление возврата по заказу <? echo $order_id; ?>'}); } )"><i class="fa fa-mail-reply" style="font-size:20px;" ></i></a>
																	<? } ?>
																	
																</td>
															</tr>				
															<? if ($order_product['set']) { ?>
																<? $set_counter = 0; ?>
																<? foreach ($order_product['set'] as $set_product) { ?>
																	<tr>
																		<td colspan="3" style="border-right:2px solid #85B200;">
																			
																		</td>
																		<td class="left" style="padding:0 5px;"><?php if ($set_product['image'] != "") {?><img src="<?php echo $set_product['image'];?>" style="padding: 1px;"><?php } ?></td>
																		<td><?php echo $set_product['name'];?><br />
																			
																			<a style="padding-bottom:4px" id="product_fast_hbt_<? echo $set_product['product_id']; ?>" class="product_fast_hbt" data-product-id='<? echo $set_product['product_id']; ?>'>Быстрое наличие</a>&nbsp;<i class="fa fa-info"></i>
																			
																			<span id="fast_hbt_preview_<? echo $set_product['product_id']; ?>"></span>
																			<script>
																				$('#product_fast_hbt_<? echo $set_product['product_id'] ?>').click(function(){
																					$.ajax({
																						url:  'index.php?route=catalog/product/getFastHBT&product_id=<? echo $set_product['product_id'] ?>&token=<? echo $token ?>',
																						dataType: 'html',
																						beforeSend : function(){
																							$('#fast_hbt_preview_<? echo $set_product['product_id'] ?>').html('');
																							$('#fast_hbt_preview_<? echo $set_product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
																						},
																						success : function(html){
																							$('#fast_hbt_preview_<? echo $set_product['product_id'] ?>').html('');
																							$('#fast_hbt_preview_<? echo $set_product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Наличие по <? echo $set_product['product_id'] ?>'});
																						}
																					})
																				});
																			</script>
																			
																		</td>
																		<td><?php echo $set_product['model'];?><br /><span style="font-size:10px"><a href="<?php echo $store_url; ?>index.php?route=product/product&product_id=<? echo $set_product['product_id']; ?>" target="_blank">сайт &rarr;</a></span></td>
																		<td><?php echo $set_product['quantity'];?></td>
																		<td><?php echo $set_product['price_national'];?>
																		<br /><span style="font-size:10px; color:#666">1С: <b><?php echo $set_product['pricewd_national_txt']; ?></b>
																		</span>
																		<br /><span style="font-size:10px; color:#666">
																			В наборе: <span style="font-weight:700"><? echo $set_product['price_in_eur']; ?></span>!
																			<br />Регулярная: <? if ($set_product['special_in_base_real']) { ?>
																				<span style="font-weight:700"><? echo $set_product['special_in_base_real']; ?></span>
																			<? } else { ?>
																				<span style="font-weight:700"><? echo $set_product['price_in_base_real']; ?></span>
																				<? } ?></span>
																			</td>
																			<td><?php echo $set_product['total_national'];?>
																			<span class="help">Сумма в наборе!</span>
																			<span style="font-size:10px; color:#666">1С: <b><?php echo $set_product['totalwd_national_txt']; ?></b>
																			</span><br /> 
																		</td>
																		<td align="right" style="width:1px;">
																			<a href="<? echo $set_product['adminlink']; ?>" target="_blank" style="padding-bottom:4px"><i class="fa fa-edit" style="font-size:16px;"></i></a>
																			<? if ($is_buyer) { ?>
																				<span onclick="$('#product-buyer-set-supply-row<?php echo  $set_counter; ?>').toggle(); $('#product-set-buyer-row<?php echo  $set_counter; ?>').toggle();" style="cursor:pointer; border-bottom:1px dashed black;"><i class="fa fa-eur" style="font-size:16px;"></i></span>
																			<? } ?>
																		</td>
																	</tr>
																	<? if ($is_buyer) { ?>
																		<tr id="product-buyer-set-supply-row<?php echo $set_counter; ?>" class='price_row buyer-row' style="display:none;">	
																			<td colspan="3" align="right"><span style="font-size:25px; color:#1f4962;">&rarr;</span></td>
																			<td colspan="7" style="padding:5px 0px;">
																				<table style="border:1px solid black; width:100%" id="set_supply_table_<? echo $set_product['order_set_id']; ?>">
																					<tbody>
																						<tr>
																							<td colspan="8" style="text-align:left; padding:5px;">
																								<b style="padding:3px 5px; color:white; background:#1f4962;">Закупка / поставщики</b>&nbsp;
																								<a class="button save_button" style="float:right;" onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();">Сохранить</a><a class="button do-add-set-supply" style="float:right; margin-right:10px;" data-order-set-id="<? echo $set_product['order_set_id']; ?>" data-product-id="<? echo $set_product['product_id']; ?>" data-set-id="<? echo $set_product['set_id']; ?>" >добавить закупку</a>
																							</td>
																						</tr>
																						<tr>
																							<td>							
																							</td>
																							<td>
																								Заказ?
																							</td>
																							<td>
																								Поставщик
																							</td>
																							<td>
																								Кол-во
																							</td>
																							<td>
																								Цена единицы
																							</td>
																							<td>
																								Валюта
																							</td>							
																							<td>
																								&sum;
																							</td>
																							<td>
																								&sum; <? echo $currency_code; ?>
																							</td>
																							<td>
																								Ссылка
																							</td>
																							<td>
																								Коммент
																							</td>
																						</tr>
																						<? if ($set_product['supplies']) { ?>							
																							<?   foreach ($set_product['supplies'] as $_supply) { ?>						
																								<tr id="">							
																					<? /*
																						order_set_id - уникальная запись товара входящего в сервиз в таблице заказанніх товаров
																						order_product_id - 0
																						product_id = идентификатор самого товара в общей таблице
																						set_id - идентификатор самоого НАБОРА в таблице наборов
																					*/ ?>
																					<input type="hidden" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][order_set_id]"  value="<? echo $_supply['order_set_id']; ?>" />
																					<input type="hidden" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][order_product_supply_id][]"  value="<? echo $_supply['order_product_supply_id']; ?>" />
																					<input type="hidden" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][set_id]"  value="<? echo $_supply['set_id']; ?>" />
																					<input type="hidden" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][product_id]"  value="<? echo $_supply['product_id']; ?>" />
																					<input type="hidden" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][order_product_id]"  value="0" />
																					<td width="1" class="left">
																						<img style="cursor:pointer;" src="view/image/delete.png" onclick='var _el = $(this); swal({ title: "Точно удалить запись о закупке?", text: "Это действие нельзя отменить!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, удалить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { _el.parent().parent().remove(); });' />
																					</td>
																					<td class="left">
																						
																						<input type="hidden"  name="order_set_supply[<? echo $_supply['order_set_id']; ?>][is_for_order][]" <? if ($_supply['is_for_order']) { ?>value="1"<? } else { ?>value="0"<? } ?> id="set_supply_is_for_order_<? echo $_supply['order_set_id']; ?>_<? echo $set_supply_row; ?>"  />																								
																						
																						<input type="checkbox" onclick="var _el = $('#set_supply_is_for_order_<? echo $_supply['order_set_id']; ?>_<? echo $set_supply_row; ?>'); if (_el.val() ==1 ){ _el.val('0') } else { _el.val('1') }" <? if ($_supply['is_for_order']) { ?>checked="checked"<? } ?> />
																					</td>
																					<td class="left">
																						<input type="hidden" id="set_supply_supplier_id_<? echo $_supply['order_set_id']; ?>_<? echo $set_supply_row; ?>" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][supplier_id][]"  value="<? echo $_supply['supplier_id']; ?>" />
																						
																						<input type="text" id="set_supply_supplier_autocomplete_<? echo $_supply['order_set_id']; ?>_<? echo $set_supply_row; ?>" class="order_order_set_supply_supplier" data-supply-row="<? echo $set_supply_row; ?>" data-order-set-id="<? echo $_supply['order_set_id']; ?>" value="<? echo $_supply['supplier_name']; ?>" style="width:300px;" />
																					</td>
																					<td class="left">
																						<input type="text" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][amount][]" value="<? echo $_supply['amount']; ?>" style="width:30px;" />
																					</td>
																					<td class="left">
																						<input type="text" name="order_set_supply[<? echo $_supply['order_set_id']; ?>][price][]" value="<? echo $_supply['price']; ?>" style="width:100px;" />
																					</td>
																					<td>
																						<select name="order_set_supply[<? echo $_supply['order_set_id']; ?>][currency][]">
																							<? foreach ($currencies as $_currency) { ?>
																								<option value="<? echo $_currency['code']; ?>" <? if ($_currency['code'] == $_supply['currency']) { ?>selected="selected"<? } ?>><? echo $_currency['code']; ?></option>
																							<? } ?>
																						</select>
																					</td>
																					<td>
																						<? echo $_supply['total']; ?>&nbsp;<? echo $_supply['currency']; ?>
																					</td>
																					<td>
																						<? echo $_supply['total_in_order_currency']; ?>&nbsp;<? echo $currency_code; ?>
																					</td>
																					<td>
																						<textarea name="order_set_supply[<? echo $_supply['order_set_id']; ?>][url][]" rows="2" style="height:30px;"><? echo $_supply['url']; ?></textarea>
																					</td>
																					<td>
																						<textarea name="order_set_supply[<? echo $_supply['order_set_id']; ?>][comment][]" rows="2" style="height:30px;"><? echo $_supply['comment']; ?></textarea>
																					</td>
																				</tr>
																				<? $set_supply_row++; } ?>
																			<? } ?>
																		</tbody>
																	</table>
																</td>
															</tr>				  
															
															
															
															<tr id="product-set-buyer-row<?php echo $set_counter; ?>" class='price_row buyer-row' style="display:none;">
																<td colspan="3" align="right"><span style="font-size:25px; color:#1f4962;">&rarr;</span></td>
																<td colspan="7" style="padding:5px 0px;">
																	<table style="border:1px solid black; border-bottom:2px solid black; width:100%">
																		<tr>
																			<td colspan="4" style="text-align:left;">
																				<b style="padding:3px 5px; color:white; background:#1f4962;">Подбор цен / оптимальные цены</b>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2" style="width:40%; text-align:center;">
																				<b>Продажные цены</b><br />
																				<span class='help'>цены из справочника товаров, без учета личных скидок</span>
																			</td>
																			<td colspan="2" style="width:60%; text-align:center;">
																				<b>Известные поставщики</b>
																				<span class='help'>Оптимальная закупочная цена рассчитывается, исходя из РЕАЛЬНОГО курса валют, который отличается от ВНУТРЕННЕГО!</span>
																			</td>
																		</tr>
																		<tr>
																			<td>
																				Цена в магазине:
																			</td>
																			<td>
																				<b><? echo $set_product['price_in_base_real']; ?></b>
																			</td>
																			<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;">
																				<b>Свои склады:</b>
																			</td>
																			<td>
																				Германия: <? echo $set_product['quantity_stock']; ?>,&nbsp;&nbsp;
																				Москва: <? echo $set_product['quantity_stockM']; ?>,&nbsp;&nbsp;
																				Киев: <? echo $set_product['quantity_stockK']; ?>&nbsp;&nbsp;
																				Минск: <? echo $set_product['quantity_stockM']; ?>&nbsp;&nbsp;
																				Астана: <? echo $set_product['quantity_stockM']; ?>
																			</td>
																		</tr>
																		<tr>
																			<td>																						
																			</td>
																			<td>																						
																			</td>
																			<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;">
																				<b>Локальные поставщики:</b>
																			</td>
																			<td>
																				
																			</td>
																		</tr>
																		<tr>
																			<td>
																				Скидка в магазине:
																			</td>
																			<td>
																				<b><? if ($set_product['special_in_base_real']) { ?><? echo $set_product['special_in_base_real']; ?><? } else { ?>нет<? } ?></b>
																			</td>
																			<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;">
																				AMAZON ASIN:
																			</td>
																			<td>
																				<? if ($set_product['real_product']['asin']) { ?>
																					<? echo $set_product['real_product']['asin']; ?>
																				<? } else { ?>
																					не указан
																				<? } ?>
																			</td>
																			
																		</tr>
																		<tr>
																			<td>
																				<span style="color:#cf4a61;font-weight:700">Актуальная цена магазина:</span>
																			</td>
																			
																			<td>
																				<? if ($set_product['special_in_base_real']) { ?>
																					<span style="color:#cf4a61;font-weight:700"><? echo $set_product['special_in_base_real']; ?></span>
																				<? } else { ?>
																					<span style="color:#cf4a61;font-weight:700"><? echo $set_product['price_in_base_real']; ?></span>
																				<? } ?>
																			</td>
																			
																			<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;" valign="middle">
																				Бренды:
																			</td>
																			<td valign="middle" style="width:100%">
																				<? if ($set_product['real_product']['source']) { ?>
																					<? foreach (explode(PHP_EOL, $set_product['real_product']['source']) as $source_line) { ?>
																						<span onclick="$('#amazon_result').load('index.php?route=catalog/product/getByURLPriceAjax&token=<? echo $token ?>&url=<? echo urlencode($source_line); ?>')" style="cursor:pointer;border-bottom:1px dashed black;"><? echo $source_line ?></span>
																						<br />
																					<? } ?>
																				<? } else { ?>
																					не указаны
																				<? } ?>
																			</td>
																		</tr>
																		<tr>
																			<td>
																				<span style="color:#4ea24e;font-weight:700">Цена по реал. курсу:</span>
																			</td>
																			<td>
																				<span style="color:#4ea24e;font-weight:700"><? echo $set_product['price_in_eur_by_real_cource']; ?></span>
																			</td>
																			<? if ($set_product['real_product']['asin'] || $set_product['real_product']['source']) {  ?>
																				
																				<td colspan="2" style='text-align:left;padding-left:10px;padding-top:5px;'>
																					<a class="button" id='get_prices_modal_<? echo $set_product['product_id'] ?>'>Получить цены у поставщиков</a>
																					<span id="prices_preview_<? echo $set_product['product_id'] ?>"></span>
																				</td>
																				<script>
																					$('#get_prices_modal_<? echo $set_product['product_id'] ?>').click(function(){
																						$.ajax({
																							url:  'index.php?route=catalog/product/getOptimalPrice&product_id=<? echo $set_product['product_id'] ?>&token=<? echo $token ?>',
																							dataType: 'html',
																							beforeSend : function(){
																								$('#prices_preview_<? echo $set_product['product_id'] ?>').html('');
																								$('#prices_preview_<? echo $set_product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
																							},
																							success : function(html){
																								$('#prices_preview_<? echo $set_product['product_id'] ?>').html('');
																								$('#prices_preview_<? echo $set_product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Цены поставщика по товару <? echo $set_product['product_id'] ?>'});
																							}
																						})
																					});
																				</script>
																			<? } else { ?>
																				<td colspan="2">
																				</td>
																			<? } ?>
																		</tr>
																		<tr>
																			<? if ($set_product['price_changed_upon_order_start']) { ?>
																				<td colspan="4" class='warning' style="padding:3px 3px 3px 33px;">Внимание! Цена в базе, либо курс изменились с момента оформления заказа более, чем на 3€! Оптимальная закупочная цена считается от цены товара в заказе!</td>
																			<? } else { ?>
																				<td colspan="4" class='success' style="padding:3px 3px 3px 33px;">Ок, цена в базе не изменялась с момента оформления заказа! Оптимальная закупочная цена считается от цены товара в базе!</td>
																			<? } ?>
																		</tr>
																		<tr>
																			<td colspan="4" style="padding:4px;">
																				<span style="color:#4ea24e;font-weight:700; font-size:14px;">Примерная закупочная цена: <span style="background:#cf4a61; color:white; padding:3px;"><? echo $set_product['buy_max_price']; ?></span>&nbsp;&nbsp;&nbsp;
																				коэффициент наценки: <span style="background:orange; color:white; padding:3px;"><? echo $set_product['buy_max_coef']; ?></span>&nbsp;&nbsp;&nbsp;
																				маржа по тек. курсу: <span style="background:#4ea24e; color:white; padding:3px;"><? echo $set_product['real_difference']; ?></span>
																			</span>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													<? } ?>
													
													<? $set_counter++; ?>
												<? } ?>
											<? } ?>
											
											<tr id="product-related-row-<?php echo $product_row; ?>" style="display:none;">
												<td id="td-related-row-<?php echo $product_row; ?>" colspan="11" style="padding:5px;">
													
												</td>
											</tr>
											<tr id="product-replace-row-<?php echo $product_row; ?>" style="display:none;">
												<td id="td-replace-row-<?php echo $product_row; ?>" colspan="11" style="padding:5px;">
													
												</td>
											</tr>
											
											<tr id="product-amazon-row-<?php echo $product_row; ?>" style="display:none;">
												<td id="td-amazon-row-<?php echo $product_row; ?>" colspan="10" style="padding:5px;">
													<i class="fa fa-spinner fa-spin"></i>
												</td>
											</tr>
											
											<? if ($is_buyer) { ?>
												<tr id="product-buyer-supply-row<?php echo $product_row; ?>" class='price_row buyer-row' style="display:none;">	
													<td></td>
													<td></td>
													<td colspan="9" style="padding:5px 0px;">
														<table style="border:1px solid black; width:100%" id="supply_table_<? echo $order_product['order_product_id']; ?>">
															<tbody>
																<tr>
																	<td colspan="10" style="text-align:left; padding:5px;">
																		<b style="padding:3px 5px; color:white; background:#1f4962;">Закупка / поставщики</b>&nbsp;
																		<a class="button save_button" style="float:right;" onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();">Сохранить</a><a class="button do-add-supply" style="float:right; margin-right:10px;" data-order-product-id="<? echo $order_product['order_product_id']; ?>" data-product-id="<? echo $order_product['product_id']; ?>" >добавить закупку</a>
																	</td>
																</tr>
																<tr>
																	<td>							
																	</td>
																	<td>
																		Заказ?
																	</td>
																	<td>
																		Поставщик
																	</td>
																	<td>
																		Кол-во
																	</td>
																	<td>
																		Цена единицы
																	</td>
																	<td>
																		Валюта
																	</td>							
																	<td>
																		&sum;
																	</td>
																	<td>
																		&sum; <? echo $currency_code; ?>
																	</td>
																	<td>
																		Ссылка
																	</td>
																	<td>
																		Комментарий
																	</td>
																</tr>
																<?  if ($order_product['supplies']) { ?>							
																	<?  foreach ($order_product['supplies'] as $_supply) { ?>						
																		<tr id="">							
																			<input type="hidden" name="order_product_supply[<? echo $_supply['order_product_id']; ?>][product_id]"  value="<? echo $order_product['product_id']; ?>" />
																			<input type="hidden" name="order_product_supply[<? echo $_supply['order_product_id']; ?>][order_product_supply_id][]"  value="<? echo $_supply['order_product_supply_id']; ?>" />
																			<input type="hidden" name="order_product_supply[<? echo $_supply['order_product_id']; ?>][set_id][]"  value="0" />
																			<td width="1" class="left">
																				<img style="cursor:pointer;" src="view/image/delete.png" onclick='var _el = $(this); swal({ title: "Точно удалить запись о закупке?", text: "Это действие нельзя отменить!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, удалить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { _el.parent().parent().remove(); });' />
																			</td>
																			<td class="left">
																				
																				<input type="hidden"  name="order_product_supply[<? echo $_supply['order_product_id']; ?>][is_for_order][]" <? if ($_supply['is_for_order']) { ?>value="1"<? } else { ?>value="0"<? } ?> id="supply_is_for_order_<? echo $_supply['order_product_id']; ?>_<? echo $supply_row; ?>"  />																								
																				<input type="checkbox" onclick="var _el = $('#supply_is_for_order_<? echo $_supply['order_product_id']; ?>_<? echo $supply_row; ?>'); if (_el.val() ==1 ){ _el.val('0') } else { _el.val('1') }" <? if ($_supply['is_for_order']) { ?>checked="checked"<? } ?> />
																				
																			</td>
																			<td class="left">
																				<input type="hidden" id="supply_supplier_id_<? echo $_supply['order_product_id']; ?>_<? echo $supply_row; ?>" name="order_product_supply[<? echo $_supply['order_product_id']; ?>][supplier_id][]"  value="<? echo $_supply['supplier_id']; ?>" />
																				
																				<input type="text" id="supply_supplier_autocomplete_<? echo $_supply['order_product_id']; ?>_<? echo $supply_row; ?>" class="order_product_supply_supplier" data-supply-row="<? echo $supply_row; ?>" data-order-product-id="<? echo $_supply['order_product_id']; ?>" value="<? echo $_supply['supplier_name']; ?>" style="width:300px;" />
																			</td>
																			<td class="left">
																				<input type="text" name="order_product_supply[<? echo $_supply['order_product_id']; ?>][amount][]" value="<? echo $_supply['amount']; ?>" style="width:30px;" />
																			</td>
																			<td class="left">
																				<input type="text" name="order_product_supply[<? echo $_supply['order_product_id']; ?>][price][]" value="<? echo $_supply['price']; ?>" style="width:100px;" />
																			</td>
																			<td>
																				<select name="order_product_supply[<? echo $_supply['order_product_id']; ?>][currency][]">
																					<? foreach ($currencies as $_currency) { ?>
																						<option value="<? echo $_currency['code']; ?>" <? if ($_currency['code'] == $_supply['currency']) { ?>selected="selected"<? } ?>><? echo $_currency['code']; ?></option>
																					<? } ?>
																				</select>
																			</td>
																			<td>
																				<? echo $_supply['total']; ?>&nbsp;<? echo $_supply['currency']; ?>
																			</td>
																			<td>
																				<? echo $_supply['total_in_order_currency']; ?>&nbsp;<? echo $currency_code; ?>
																			</td>
																			<td>
																				<textarea name="order_product_supply[<? echo $_supply['order_product_id']; ?>][url][]" rows="2" style="height:30px;"><? echo $_supply['url']; ?></textarea>
																			</td>
																			<td>
																				<textarea name="order_product_supply[<? echo $_supply['order_product_id']; ?>][comment][]" rows="2" style="height:30px;"><? echo $_supply['comment']; ?></textarea>
																			</td>
																		</tr>
																		<? $supply_row++; } ?>
																	<? } ?>
																</tbody>
															</table>
														</td>
													</tr>				
													
													<tr id="product-buyer-row<?php echo $product_row; ?>" class='price_row buyer-row' style="display:none;">				
														<td></td>
														<td></td>
														<td colspan="9" style="padding:5px 0px;">
															<table style="border:1px solid black; border-bottom:2px solid black; width:100%">
																<tr>
																	<td colspan="4" style="text-align:left;">
																		<b style="padding:3px 5px; color:white; background:#1f4962;">Подбор цен / оптимальные цены</b>
																	</td>
																</tr>
																<tr>
																	<td colspan="2" style="width:40%; text-align:center;">
																		<b>Продажные цены</b><br />
																		<span class='help'>цены из справочника товаров, без учета личных скидок</span>
																	</td>
																	<td colspan="2" style="width:60%; text-align:center;">
																		<b>Известные поставщики</b>
																		<span class='help'>Оптимальная закупочная цена рассчитывается, исходя из РЕАЛЬНОГО курса валют, который отличается от ВНУТРЕННЕГО!</span>
																	</td>
																</tr>
																<tr>
																	<td>
																		Цена в магазине:
																	</td>
																	<td>
																		<b><? echo $order_product['price_in_base_real']; ?></b>
																	</td>
																	<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;">
																		<b>Свои склады:</b>
																	</td>
																	<td>
																		Германия: <? echo $order_product['quantity_stock']; ?>,&nbsp;&nbsp;
																		Москва: <? echo $order_product['quantity_stockM']; ?>,&nbsp;&nbsp;
																		Киев: <? echo $order_product['quantity_stockK']; ?>&nbsp;&nbsp;
																		Минск: <? echo $order_product['quantity_stockM']; ?>&nbsp;&nbsp;
																		Астана: <? echo $order_product['quantity_stockM']; ?>
																	</td>
																</tr>
																<tr>
																	<td>																						
																	</td>
																	<td>																						
																	</td>
																	<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;">
																		<b>Внутренний рынок:</b>
																	</td>
																	<td valign="center">
																		<? if ($order_product['local_suppliers']) { ?>
																			<? foreach ($order_product['local_suppliers'] as $local_supplier) { ?>
																				<b><? echo $local_supplier['supplier_name']; ?></b> - <? echo $this->currency->format($local_supplier['price'], $local_supplier['currency'], 1); ?>
																				<i>(РРЦ <? echo $this->currency->format($local_supplier['price_recommend'], $local_supplier['currency'], 1); ?>)</i>, в наличии 
																				<b><? echo $local_supplier['stock']; ?> шт.</b><br />
																			<? } ?>
																		<? } ?>
																	</td>
																</tr>
																<tr>
																	<td>
																		Скидка в магазине:
																	</td>
																	<td>
																		<b><? if ($order_product['special_in_base_real']) { ?><? echo $order_product['special_in_base_real']; ?><? } else { ?>нет<? } ?></b>
																	</td>
																	<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;">
																		AMAZON ASIN:
																	</td>
																	<td>
																		<? if ($order_product['real_product']['asin']) { ?>
																			<? echo $order_product['real_product']['asin']; ?>
																		<? } else { ?>
																			не указан
																		<? } ?>
																	</td>
																	
																</tr>
																<tr>
																	<td>
																		<span style="color:#cf4a61;font-weight:700">Актуальная цена магазина:</span>
																	</td>
																	
																	<td>
																		<? if ($order_product['special_in_base_real']) { ?>
																			<span style="color:#cf4a61;font-weight:700"><? echo $order_product['special_in_base_real']; ?></span>
																		<? } else { ?>
																			<span style="color:#cf4a61;font-weight:700"><? echo $order_product['price_in_base_real']; ?></span>
																		<? } ?>
																	</td>
																	
																	<td style="width:1px;padding-left:10px;padding-right:5px;white-space:nowrap;" valign="middle">
																		Бренды:
																	</td>
																	<td valign="middle" style="width:100%">
																		<? if ($order_product['real_product']['source']) { ?>
																			<? foreach (explode(PHP_EOL, $order_product['real_product']['source']) as $source_line) { ?>
																				<span onclick="$('#amazon_result').load('index.php?route=catalog/product/getByURLPriceAjax&token=<? echo $token ?>&url=<? echo urlencode($source_line); ?>')" style="cursor:pointer;border-bottom:1px dashed black;"><? echo $source_line ?></span>
																				<br />
																			<? } ?>
																		<? } else { ?>
																			не указаны
																		<? } ?>
																	</td>
																</tr>
																<tr>
																	<td>
																		<span style="color:#4ea24e;font-weight:700">Цена по реал. курсу:</span>
																	</td>
																	<td>
																		<span style="color:#4ea24e;font-weight:700"><? echo $order_product['price_in_eur_by_real_cource']; ?></span>
																	</td>
																	<? if ($order_product['real_product']['asin'] || $order_product['real_product']['source']) {  ?>
																		
																		<td colspan="2" style='text-align:left;padding-left:10px;padding-top:5px;'>
																			<a class="button" id='get_prices_modal_<? echo $order_product['product_id'] ?>'>Получить цены у поставщиков</a>
																			<span id="prices_preview_<? echo $order_product['product_id'] ?>"></span>
																		</td>
																		<script>
																			$('#get_prices_modal_<? echo $order_product['product_id'] ?>').click(function(){
																				$.ajax({
																					url:  'index.php?route=catalog/product/getOptimalPrice&product_id=<? echo $order_product['product_id'] ?>&token=<? echo $token ?>',
																					dataType: 'html',
																					beforeSend : function(){
																						$('#prices_preview_<? echo $order_product['product_id'] ?>').html('');
																						$('#prices_preview_<? echo $order_product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
																					},
																					success : function(html){
																						$('#prices_preview_<? echo $order_product['product_id'] ?>').html('');
																						$('#prices_preview_<? echo $order_product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Цены поставщика по товару <? echo $order_product['product_id'] ?>'});
																					}
																				})
																			});
																		</script>
																	<? } else { ?>
																		<td colspan="2">
																		</td>
																	<? } ?>
																</tr>
																<tr>
																	<? if ($order_product['price_changed_upon_order_start']) { ?>
																		<td colspan="4" class='warning' style="padding:3px 3px 3px 33px;">Внимание! Цена в базе, либо курс изменились с момента оформления заказа более, чем на 3€! Оптимальная закупочная цена считается от цены товара в заказе!</td>
																	<? } else { ?>
																		<td colspan="4" class='success' style="padding:3px 3px 3px 33px;">Ок, цена в базе не изменялась с момента оформления заказа! Оптимальная закупочная цена считается от цены товара в базе!</td>
																	<? } ?>
																</tr>
																<tr>
																	<td colspan="4" style="padding:4px;">
																		<span style="color:#4ea24e;font-weight:700; font-size:14px;">Примерная закупочная цена: <span style="background:#cf4a61; color:white; padding:3px;"><? echo $order_product['buy_max_price']; ?></span>&nbsp;&nbsp;&nbsp;
																		коэффициент наценки: <span style="background:orange; color:white; padding:3px;"><? echo $order_product['buy_max_coef']; ?></span>&nbsp;&nbsp;&nbsp;
																		маржа по тек. курсу: <span style="background:#4ea24e; color:white; padding:3px;"><? echo $order_product['real_difference']; ?></span>
																	</span>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											<? } ?>
											
											<?php $product_row++; ?>
											
											
											
										<?php } ?>
									<?php } else { ?>
										<tr>
											<td class="center" colspan="7"><?php echo $text_no_results; ?></td>
										</tr>
									<?php } ?>
									<tr>
										<td colspan="3" rowspan="2" class="center" style="text-align:right;">
											<span style="display:inline-block;"><img src="view/image/small1c.png" /></span> 
											<a class="button" id="update_parties_from_1c" ><span style="display:inline-block;">Обновить партии</span></a>
											<div><span id="update_partie_result" ><i></i></span></div>
										</td>
										<td colspan="4" style="text-align:right; font-weight:700;">
											Всего:
										</td>
										<td id="quantity_total" style="text-align:right; font-weight:700;">
											
										</td>
										<td style="text-align:right; font-weight:700;">
											На сумму:
										</td>
										<td id="total_total" style="text-align:right; font-weight:700;">
											
										</td>
									</tr>
									<tr>
										<td colspan="5" style="text-align:right;">
											<?if (isset($pricewd_error)) { ?>
												<span style='color:#cf4a61; font-size:11px;'>
													<i class="fa fa-warning"></i>&nbsp;Внимание, возможно, что-то не учтено при пересчете построчной цены товара. Погрешность составляет <? echo $pricewd_diff; ?> Сообщите номер заказа Виктору. Спасибо!
												</span>
											<? } ?>
										</td>
										<td style="text-align:right; font-weight:700;">
											Цена фактическая:
										</td>
										<td style="text-align:right; font-weight:700;">
											<? echo $total_pricewd_total_national; ?>
										</td>
									</tr>
								</tbody>
							</table>
							<div id="return_dialog" style="display:none;">
								
							</div>
							<table class="list orderproduct" style="width:100%">
								<tbody>
									<tr>
										<? if ($products_can_not_be_deleted) { ?>
											
											<td colspan="2" style="text-align:right;"><a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button save_button" style="width:300px; text-align:center;padding:5px 10px;">Сохранить</a></td>
											
										<? } else { ?>
											
											<td class="center" style="background-color:#7F00FF; padding:5px; color:#fff;">Добавление товара</td>
											<td class="left">
												<input type="text" name="product" value="" style="width:550px; padding:5px 10px;" />
												<input type="hidden" id="adding_product_id" name="product_id" value="" />
												<input type="hidden" id="override_price" name="override_price" value="" />
												<input type="hidden" id="ao_id" name="ao_id" value="" />
												&nbsp;&nbsp;<input type="number" name="quantity" value="1" style="width:60px;" />
												&nbsp;&nbsp;<a id="button-product" class="button" style="padding:8px 10px;">Добавить товар</a>&nbsp;&nbsp;&nbsp;&nbsp;
												<a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button save_button" style="padding:8px 10px;">Сохранить заказ</a>
											</td>
											
										<? } ?>
									</tr>
									<tr id="option"></tr>
								</tbody>												
								<? if (!$allowed_to_make_cheque) { ?>
									<tfoot>
										<tr>
											<td colspan="10" class="blue_heading" style="background:#cf4a61;color:white;font-size:16px;padding:7px;text-align:center;">Печать чека запрещена!</td>
										</tr>
										<tr>
											<td colspan="10" class="blue_heading" style="background:#F96E64;color:white;padding:7px;text-align:center;"><? echo $disallow_cheque_comment; ?></td>
										</tr>
									</tfoot>
								<? } ?>
								
								<? if (!$closed && $can_use_cheques) { ?>
									<tfoot>
										<tr>
											<td colspan="10" class="blue_heading" style="color:white;padding:3px;text-align:center;">Отображение чеков</td>
										</tr>
										<tr>
											<td colspan="10" style="padding:5px 0px;">
												<table style="width:100%">
													<tr>
														<td style="padding:5px;">
															Поставка:
															<i class="fa fa-minus" style="cursor:pointer;" id="invoice_num_minus" onclick="spin($('#show-cheque-delivery-num'), -1); get_cheque_name($('#show-cheque-delivery-num').val())"></i>&nbsp;<input id="show-cheque-delivery-num" type="text" value="1" size="1" style="padding:5px 0px; width:40px; text-align:center;" onkeyup="get_cheque_name($(this).val())" />
															<i class="fa fa-plus" style="cursor:pointer;" id="invoice_num_plus" onclick="spin($('#show-cheque-delivery-num'), 1); get_cheque_name($('#show-cheque-delivery-num').val())"></i>
														</td>
														<td>
															Дата: <input id="show-cheque-date" type="text" value="" class="date" style="padding:5px 0px; width:80px; text-align:center;" />
														</td>
														<td>
															<span style="display:inline-block;">
																<input type="text" id="show-cheque-type" value="<? echo (isset($pay_type)?$pay_type:'Наличный расчет'); ?>" style="padding:5px 0px; width:150px; text-align:center;" />
																<br/><span style="font-size:10px; border-bottom:1px dashed black; cursor:pointer;" onclick="$('#show-cheque-type').val('Наличный расчет')">Нал</span>&nbsp;<span style="font-size:10px; border-bottom:1px dashed black; cursor:pointer;" onclick="$('#show-cheque-type').val('Безналичный расчет')">Безнал</span>&nbsp;<span style="font-size:10px; border-bottom:1px dashed black; cursor:pointer;" onclick="$('#show-cheque-type').val('Банковской картой')">Карта</span>
																<span style="font-size:10px; border-bottom:1px dashed black; cursor:pointer;" onclick="$('#show-cheque-type').val('Наложенный платеж')">Наложка</span>
															</span>
														</td>
														<td>
															<input type="text" name="cheque-num" id="cheque-num" style="text-align:center;font-size:16px;width:150px;" /><br />
															<span style="font-size:10px;">Номер чека</span>
														</td>
														<td>
															<input class="checkbox" type="checkbox" name="cheque-prim" id="cheque-prim" onclick="" /><label for="cheque-prim">Примечание</label>
															<br/>
															<br/>
															<input class="checkbox" type="checkbox" name="cheque-return" id="cheque-return" onclick="if ($(this).attr('checked')) { $('#cheque-prim').attr('checked','checked'); }" /><label for="cheque-return">Без возврата!</label>
														</td>
														<td>
															<a id="button-show-cheque" class="button">Отобразить чек</a>
														</td>
														<td style="width:200px">
															<a id="button-save-print-cheque" class="button" style="padding:10px 15px; text-align:center; display:none; width:80%;">Записать чек</a>
														</td>
														
													</tr>
												</table>
												
											</td>
										</tr>
										<tr>
											<td colspan="10">
												<div id="cheque-shown" style="display:none; padding:3px; border:1px solid #85B200"></div>
											</td>
										</tr>
									</tfoot>
								<? } ?>
								
							</table>
						</div>
						<div class="clr"></div>
						<div onclick="$('#tab-eq-order-history').toggle();" class="blue_heading">Показать / скрыть лицевые счета заказа и покупателя</div>
						<div id="tab-eq-order-history" style="display:none; margin-bottom:5px;">
							<table style="width:100%">
								<tr>
									<td style="width:55%">
										<div id="tab-transactionorder">
											<div id="transactionorder" style="border-right:1px solid #1f4962;padding-right:3px;"></div>
											<? if ($is_doTransactions) { ?>
												<table class="form" style="border-right:1px solid #1f4962;padding-right:3px;">
													<tr>
														<td style="width:1px;" width="1"><b>Дата</b></td>
														<td colspan="2"><input id="tr_date_added" class="datetime csi_input" type="text" name="tr_date_added" value="<? echo date('Y-m-d').' '.date('H:i:s'); ?>" style="width:200px;" />
															<span class="help">По умолчанию, или если не заполнено: сегодняшняя дата (<? echo date('Y-m-d'); ?>)</span>
														</td>
													</tr>
													<tr>
														<td style="width:1px;" width="1"><b>Касса операции</b></td>
														<td colspan="2">
															<select name="tr_legalperson_id" id="tr_legalperson_id" style="max-width:400px;">
																<option value="0">-- Не выбрана касса внесения (списания) средств --</option>
																<? foreach ($all_legalpersons as $all_legalperson) { ?>																	
																	<option <? if ($all_legalperson['legalperson_id'] == $guessed_transaction_legalperson_id) { ?>selected="selected"<? } ?> value="<? echo $all_legalperson['legalperson_id']; ?>">
																		<? echo $all_legalperson['legalperson_name_1C']; ?>
																	</option>
																<? } ?>
															</select>
															<br /><span style="color:#cf4a61" ><i class="fa fa-exclamation-circle" aria-hidden="true"></i> обязательно выбери куда зачислится эта транзакция</span>
														</td>
													</tr>
													<tr>
														<td style="width:1px;" width="1"><b>Описание</b></td>
														<td  colspan="2"><input id="tr_description" type="text" name="tr_description" value="Заказ #<? echo $order_id; ?>" style="width:600px;" class="csi_input" />&nbsp;&nbsp;
														</td>
													</tr>
													<tr>
														<td>
															<b>Поступление</b>
														</td>
														<td colspan="2">
															<? if ($shipping_country_id == 176) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата СберБанк России / заказ #<? echo $order_id; ?>');">СберБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата наличными / заказ #<? echo $order_id; ?>')">Наличные</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата б/н ПромСвязьБанк / заказ #<? echo $order_id; ?>')">Безналичными</span>&nbsp;&nbsp;
																
															<? } elseif ($shipping_country_id == 220) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата ПриватБанк / заказ #<? echo $order_id; ?>')">ПриватБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата наличными / заказ #<? echo $order_id; ?>');">Наличные</span>&nbsp;&nbsp;
																
															<? } elseif ($shipping_country_id == 109) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата СберБанк России / заказ #<? echo $order_id; ?>');">СберБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата наличными / заказ #<? echo $order_id; ?>')">Наличные</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата б/н ПромСвязьБанк / заказ #<? echo $order_id; ?>')">Безналичными</span>&nbsp;&nbsp;
																
															<? } elseif ($shipping_country_id == 20) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата СберБанк России / заказ #<? echo $order_id; ?>');">СберБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата наличными / заказ #<? echo $order_id; ?>')">Наличные</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Оплата б/н ПромСвязьБанк / заказ #<? echo $order_id; ?>')">Безналичными</span>&nbsp;&nbsp;
																
															<? } ?>
															
														</td>
													</tr>
													
													<tr>
														<td>
															<b>Возвраты</b>
														</td>
														<td colspan="2">
															
															<? if ($shipping_country_id == 176) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты СберБанк России / заказ #<? echo $order_id; ?>', false, 2);">Возврат СберБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты наличными/ заказ #<? echo $order_id; ?>', false, 2);">Возврат наличные</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты б/н / заказ #<? echo $order_id; ?>', false, 2);">Возврат безналичный</span>&nbsp;&nbsp;<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты б/н / заказ #<? echo $order_id; ?>', false, 1);">Возврат эквайринг</span>																		
																
															<? } elseif ($shipping_country_id == 220) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты ПриватБанк / заказ #<? echo $order_id; ?>', false, 2);">Возврат ПриватБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат наличными / заказ #<? echo $order_id; ?>', false, 2);">Возврат наличные</span>&nbsp;&nbsp;
																
																
															<? } elseif ($shipping_country_id == 109) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты СберБанк России / заказ #<? echo $order_id; ?>', false, 2);">Возврат СберБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты наличными/ заказ #<? echo $order_id; ?>', false, 2);">Возврат наличные</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты б/н / заказ #<? echo $order_id; ?>', false, 2);">Возврат безналичный</span>&nbsp;&nbsp;<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты б/н / заказ #<? echo $order_id; ?>', false, 1);">Возврат эквайринг</span>	
																
																
															<? } elseif ($shipping_country_id == 20) { ?>
																
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты СберБанк России / заказ #<? echo $order_id; ?>', false, 2);">Возврат СберБанк</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты наличными/ заказ #<? echo $order_id; ?>', false, 2);">Возврат наличные</span>&nbsp;&nbsp;
																<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты б/н / заказ #<? echo $order_id; ?>', false, 2);">Возврат безналичный</span>&nbsp;&nbsp;<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="setPaymentSMSInfo('Возврат оплаты б/н / заказ #<? echo $order_id; ?>', false, 1);">Возврат эквайринг</span>	
																
																
															<? } ?>
															
														</td>
													</tr>
													<tr>		
														
														<input type="hidden" id="eq_type" name="eq_type" value="0" class="csi_input" />
														
														<td style="width:1px;" width="1"><b>Сумма</b></td>
														
														<td><input type="text" id="tr_amount" name="tr_amount" value="" data-no-update='0' style="width: 200px;" class="csi_input" />&nbsp;<? echo $currency_code; ?>
														<span class="help">Для снятия суммы введи отрицательное число с "-" впереди</span>
													</td>
													<td style="text-align:right">																	
														<input type="checkbox" id="send_transaction_sms" name="send_transaction_sms" class="checkbox csi_input" value="1" /><label for="send_transaction_sms">Уведомление</label>
													</td>
												</tr>
												<tr>
													<td>
														<b>Суммы</b>
													</td>
													<td colspan="2">
														<table style="width:100%;">
															<tr>
																<td style="padding:3px;">
																	<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$('#eq_type').val('0'); $('#tr_amount').val('<? echo $tip_full; ?>');  getTransactionSMSAjax();">Сумма на счету заказа: внести (<b><? echo $tip_full_txt; ?></b>)</span>
																</td>
																<td style="padding:3px;">
																	<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$('#eq_type').val('0'); $('#tr_amount').val('-<? echo $tip_full; ?>');  getTransactionSMSAjax();">Сумма на счету заказа: снять (<b>-<? echo $tip_full_txt; ?></b>)</span>
																</td>
															</tr>
															<tr>
																<td style="padding:3px;">
																	<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$('#eq_type').val('0'); $('#tr_amount').val('<? echo $tip_total; ?>');  getTransactionSMSAjax();">Вся сумма: внести (<b><? echo $tip_total_txt; ?></b>)</span>
																</td>
																<td style="padding:3px;">
																	<span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$('#eq_type').val('0'); $('#tr_amount').val('-<? echo $tip_total; ?>');  getTransactionSMSAjax();">Вся сумма: снять (<b>-<? echo $tip_total_txt; ?></b>)</span>
																</td>
															</tr>
															<tr>
																<td style="padding:3px;">
																	<? if ($tip_prepay > 0) { ?><span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$('#eq_type').val('0'); $('#tr_amount').val('<? echo $tip_prepay; ?>'); $('#tr_description').val('Предоплата по заказу #<? echo $order_id; ?>'); getTransactionSMSAjax();">Предоплата: внести (<b><? echo $tip_prepay_txt; ?></b>)</span>&nbsp;&nbsp;
																<? } ?>
															</td>
															<td style="padding:3px;">
																
															</td>
														</tr>
														<tr>
															<td style="padding:3px;">
																<? if ($tip_left > 0) { ?><span style="cursor:pointer;border-bottom:1px dashed black;" onclick="$('#tr_amount').val('<? echo $tip_left; ?>'); $('#eq_type').val('0'); getTransactionSMSAjax();">Остаток: внести (<b><? echo $tip_left_txt; ?></b>)</span>&nbsp;&nbsp;
															<? } ?>
														</td>
														<td style="padding:3px;">
															
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td style="width:1px;"><b>SMS</b><br /><span onclick="$('textarea[name=\'transaction_sms_text\']').val('');" style="cursor:pointer;border-bottom:1px dashed black;" >очистить</span></td>
											<td colspan="2"><textarea name="transaction_sms_text" class="csi_input" cols="40" rows="2" style="width: 99%"></textarea>
											</td>
										</tr>
										<tr>
											<td colspan="3" style="text-align: right;">
												<a id="button-transaction-toggler" class="button redbg" style="padding:10px 30px;margin-left:30px;" onclick="$('#button-transaction').show();">Я все проверил!</a>
												
												<a id="button-transaction" class="button" style="display:none; padding:10px 30px;"><span>Добавить транзакцию</span></a></td>
											</tr>
										</table>
									<? } elseif ($closed) { ?>
										<table style="width:100%">
											<tr>
												<td>
													<div class='warning'>Заказ закрыт и заблокирован для редактирования</div>
												</td>
											</tr>
										</table>
									<? } else { ?>
										<table style="width:100%">
											<tr>
												<td>
													<div class='warning'>Нет прав на изменение транзакций</div>
												</td>
											</tr>
										</table>
									<? } ?>
								</div>
							</td>
							
							<td style="width:40%" valign="top">
								<div id="tab-eq-customer-history" style="">
									<div id="transaction" style="font-size:10px;"></div>
									
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="clr"></div>
				
				<div onclick="$('#tab-history').toggle();" class="blue_heading">Показать / скрыть историю, изменение статуса и комментирование</div>
				<div id="tab-history"  style="display:none;">
					<table style="width:100%">
						<tr>
							<td style="width:55%; vertical-align:top;" valign='top'>
								<div id="history"  style="border-right:1px solid #1f4962;padding-right:3px;"></div>
								<table class="form" style="border-right:1px solid #1f4962;padding-right:3px;">
									<tr>
										<td style="width:130px"><b>Статус:</b></td>
										<td id="statuses_select_td"><select name="order_status_id_tc" onchange="$('#order_status_id').val($(this).val()); getCompleteOrderTextAjax(); getStatusSMSTextAjax(); if ($(this).val() == '<? echo $this->config->get('config_cancelled_status_id') ?>') { $('#reject_reason').show(); } else { $('#reject_reason').hide(); }">
											<?php foreach ($order_statuses as $order_status) { ?>
												<?php if ($order_status['order_status_id'] == $order_status_id) { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
												<?php } else { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</td>
									<td><input type="checkbox" name="courier" value="1" id="checkbox_courier" class="checkbox" /><label for="checkbox_courier">Для курьера</label></td>
									<td><input type="checkbox" name="notify" value="1" id="checkbox_notify" class="checkbox" /><label for="checkbox_notify">Уведомление клиенту</label></td>
								</tr>
								<tr id="reject_reason" style="<? if ($order_status_id != $this->config->get('config_cancelled_status_id')) { ?>display:none;<? } ?> padding-left:30px;">
									<td></td>
									<td colspan="2" style="font-size:14px;">
										<span style="display:inline-block; padding:4px; background:#F96E64; color:white;">Пожалуйста, укажи причину отмены заказа</span><br />
										<? foreach ($reject_reasons as $one_reject_reason) { ?>
											<input type="radio" id="reject_reason_radio" name="reject_reason_id" value="<? echo $one_reject_reason['reject_reason_id'] ?>" <? if ($one_reject_reason['reject_reason_id'] == $reject_reason_id) { ?>checked="checked"<? } ?>>&nbsp;&nbsp;<? echo $one_reject_reason['name'] ?></input><br />
										<? } ?>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<span style="padding-top:10px; color:#cf4a61;" id="CompleteOrderTextAjax"></span>
									</td>
								</tr>
								<tr class="emailOptions" style="display: none">
									<td><?php echo $entry_summary; ?></td>
									<td colspan="3">
										<?php if(!empty($order_products)){ ?>
											<label><input type="checkbox" name="show_products" value="1" style="vertical-align: middle;" /> <?php echo $entry_show_products; ?></label><br />
										<?php } ?>
										<?php if(!empty($order_totals)){ ?>
											<hr style="border-top: 1px dotted #ccc; background: none; border-bottom: none; border-left: none; border-right: none; margin: 0;" />
											<label><input type="checkbox" name="show_totals" value="1" style="vertical-align: middle;" /> <?php echo $entry_show_totals; ?></label><br />
										<?php } ?>
										<?php if(!empty($downloads)){ ?>
											<hr style="border-top: 1px dotted #ccc; background: none; border-bottom: none; border-left: none; border-right: none; margin: 0;" />
											<label><input type="checkbox" name="show_downloads" value="1" style="vertical-align: middle;" /> <?php echo $entry_show_downloads; ?></label><br />
										<?php } ?>
										<?php if(!empty($vouchers)){ ?>
											<hr style="border-top: 1px dotted #ccc; background: none; border-bottom: none; border-left: none; border-right: none; margin: 0;" />
											<label><input type="checkbox" name="show_vouchers" value="1" style="vertical-align: middle;" /> <?php echo $entry_show_vouchers; ?></label>
										<?php } ?>
									</td>
								</tr>
								<tr class="emailOptions" style="display: none">
									<td>
										<?php echo $entry_pdf_attach; ?><br />
										[<a href="<?php echo $pdf_download; ?>" target="_blank"><?php echo $text_preview; ?></a>]
									</td>
									<td colspan="3">
										<label><input type="checkbox" name="attach_invoice_pdf" value="1" style="vertical-align: middle;" /></label>
									</td>
									<?php if(!empty($templates_options)){ ?>
									</tr>
									<tr class="emailOptions" style="display: none">
										<td><?php echo $entry_template; ?></td>
										<td colspan="3">
											<select id="field_templates" name="field_template">
												<option value=''><?php echo $text_select; ?></option>
												<?php foreach($templates_options as $item){ ?>
													<option value="<?php echo $item['value']; ?>"><?php echo $item['label']; ?></option>
												<?php } ?>
											</select>
										</td>
									<?php } ?>
								</tr>
								<tr>		
									<td style="width:130px"><b>SMS</b><br /><span onclick="$('textarea[name=\'history_sms_text\']').val('');" style="cursor:pointer;border-bottom:1px dashed black;" >очистить</span><br />
										<? if ($shipping_country_id == 176) { ?>
											<span onclick="$('textarea[name=\'history_sms_text\']').val('Заказ # <? echo $order_id; ?> выполнен. Спасибо за покупку! Ваш <? echo $this->config->get('config_owner'); ?>');" style="cursor:pointer;border-bottom:1px dashed black;" ><? echo $this->config->get('config_owner'); ?></span>
										<? } elseif ($shipping_country_id == 220) { ?>
											<span onclick="$('textarea[name=\'history_sms_text\']').val('Заказ # <? echo $order_id; ?> выполнен. Спасибо за покупку! Ваш <? echo $this->config->get('config_owner'); ?>');" style="cursor:pointer;border-bottom:1px dashed black;" ><? echo $this->config->get('config_owner'); ?></span>
										<? } elseif ($shipping_country_id == 20) { ?>
											<span onclick="$('textarea[name=\'history_sms_text\']').val('Заказ # <? echo $order_id; ?> выполнен. Спасибо за покупку! Ваш <? echo $this->config->get('config_owner'); ?>');" style="cursor:pointer;border-bottom:1px dashed black;" ><? echo $this->config->get('config_owner'); ?></span>
										<? } ?>
										
									</td>
									<td colspan="3"><textarea id="history_sms_text" name="history_sms_text" cols="40" rows="2" style="width: 99%"></textarea><br />
										<span class="help" style="font-size:16px; background-color:#cf4a61; padding:5px; color:#FFF;" >Количество символов: <span id="history_sms_text_count"></span>. <span id="history_sms_text_count_alert"></span></span>
										<span class="help"><i class="fa fa-info-circle"></i> SMS не будет отправлено в случае, если НЕ стоит галочка "Уведомлять" или в поле ввода пусто.</span>
									</td>
									<script>
										$('#history_sms_text').on('change keyup paste', function(){
											console.log('countSMSLength triggered');
											countSMSLength();
										});
									</script>
								</tr>
								<tr>
									<td style="width:130px"><b><?php echo $entry_comment; ?></b></td>
									<td colspan="3"><textarea name="hcomment" cols="40" rows="3" style="width: 99%"></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align:right;">
										<a id="button-history-toggler" class="button redbg" style="padding:10px 30px;margin-left:30px;" onclick="$('#button-history').show();">Я все проверил!</a>
										
										<a id="button-history" class="button" style="padding:10px 30px;margin-left:30px; display:none;">Изменить статус</a>
									</td>
								</tr>
							</table>
						</td>
						<td style="width:42%" valign="top">
							<div class="htabs" style="margin-bottom:4px;">
								<a href="#tab-order-invoice-history">История чеков</a>
								<? if ($shipping_country_id == 176) { ?>	
									<a href="#tab-order-courier-history">История курьерки</a>
								<? } ?>
								<a href="#tab-order-save-history">История сохранений</a>
								<a href="#tab-order-email-history">История Email</a>
								<a href="#tab-order-sms-history">История SMS</a>
								<a href="#tab-order-call-history">История звонков</a>
							</div>
							<div style="clear:both"></div>
							<div id="tab-order-sms-history">
								<div id="order_sms_history"></div>
							</div>
							<div id="tab-order-email-history">
								<div id="order_email_history"></div>
							</div>
							<div id="tab-order-invoice-history">
								<div id="order_invoice_history"></div>
							</div>
							<div id="tab-order-courier-history">
								<div id="order_courier_history"></div>
							</div>
							<div id="tab-order-save-history">
								<div id="order_save_history"></div>
							</div>
							<div id="tab-order-call-history">
								<div id="order_call_history"></div>
							</div>
						</td>
					</tr>
				</table>
				
			</div>
			
			
			<div class="clr"></div>
			<div id="tab-payment">
				<table class="orderadress" style="float:left; width:45%!important;">
					<th colspan="2"><?php echo $tab_payment; ?></th>
					<tr>
						<td><?php echo $entry_address; ?></td>
						<td><div  style="max-width:90%!important;"><select name="payment_address" id="payment_address" class="wide" style="width:350px;">
							<option value="0" selected="selected"><?php echo $text_none; ?></option>
							<?php foreach ($addresses as $address) { ?>
								<option value="<?php echo $address['address_id']; ?>"><?php echo $address['city']; ?><?php if ($address['address_1']) { ?>, <?php echo $address['address_1']; ?><?php } ?><? if ($address['verified']) { ?>/ Вериф.<? } ?><? if ($address['for_print']) { ?> Печатн.<? } ?></option>
							<?php } ?>
						</select></div>
					</td>
				</tr>
				<tr>
					<td> <?php echo $entry_firstname; ?></td>
					<td><input class="onfocusedit_direct" type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" /><span></span>
						<?php if ($error_payment_firstname) { ?>
							<span class="error"><?php echo $error_payment_firstname; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td> <?php echo $entry_lastname; ?></td>
					<td><input class="onfocusedit_direct" type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" /><span></span>
						<?php if ($error_payment_lastname) { ?>
							<span class="error"><?php echo $error_payment_lastname; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_company; ?></td>
							<td><input class="onfocusedit_direct" type="text" name="payment_company" value="<?php echo $payment_company; ?>" /><span></span></td>
						</tr>
						
						<tr id="company-id-display" style="display:none;">
							<td><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></td>
							<td><input type="text" name="payment_company_id" value="<?php echo $payment_company_id; ?>" /></td>
						</tr>
						<tr id="tax-id-display" style="display:none;">
							<td><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></td>
							<td><input type="text" name="payment_tax_id" value="<?php echo $payment_tax_id; ?>" />
								<?php if ($error_payment_tax_id) { ?>
									<span class="error"><?php echo $error_payment_tax_id; ?></span>
									<?php } ?></td>
								</tr>
								<tr>
									<td> <?php echo $entry_city; ?></td>
									<td><input class="onfocusedit_direct" type="text" name="payment_city" value="<?php echo $payment_city; ?>" /><span></span>
										
										<? if ($current_payment_time) { ?>
											<div style="margin-top:4px;"><div style="font-size:14px; display:inline-block; padding:3px; color:#FFF; background-color:grey;"><i class="fa fa-clock-o"></i>&nbsp;текущее время <? echo $current_payment_time; ?></div>
											</div><? } ?>
											
											<?php if (!$payment_city) { ?>
												
												<script>
													$(document).ready(function(){
														$.ajax({
															url: "index.php?route=sale/order/getCityByIpAddrAjax&ip=<?php echo $ip; ?>&token=<?php echo $token; ?>",
															type: 'GET',
															dataType: 'json',
															beforeSend: function(){
																$('input[name=payment_city]').before("<i class='fa fa-spinner fa-spin'></i>");
																$('input[name=payment_postcode]').before("<i class='fa fa-spinner fa-spin'></i>");
															},
															success: function(json){
																$('input[name=payment_city]').parent().children('.fa-spin').remove();
																$('input[name=payment_postcode]').parent().children('.fa-spin').remove();
																
																if (json.city){
																	$('input[name=payment_city]').val(json.city);
																	$('input[name=payment_city]').parent('td').parent('tr').after("<tr><td></td><td style='background-color:#cf4a61; padding:5px; color:#fff;'>Внимание! Город подобран на основании локации клиента! Нужно уточнять!</td></tr>");
																	$('input[name=payment_city]').before("<i class='fa fa-question-circle' style='font-size:24px; color:#cf4a61'></i> ");
																}
																
																if (json.zip){
																	$('input[name=payment_postcode]').val(json.zip);
																	$('input[name=payment_postcode]').parent('td').parent('tr').after("<tr><td></td><td style='background-color:#cf4a61; padding:5px; color:#fff;' colspan='2'>Внимание! Индекс подобран на основании локации клиента! Нужно уточнять!</td></tr>");
																	$('input[name=payment_postcode]').before("<i class='fa fa-question-circle' style='font-size:24px; color:#cf4a61'></i> ");
																}							
															}
														});
													});
												</script>
												
											<?php } ?>
											<?php if ($error_payment_city) { ?>
												<span class="error"><?php echo $error_payment_city; ?></span>
												<?php } ?></td>
											</tr>
											<tr>
												<td><?php echo $entry_address_1; ?></td>
												<td>
													<?php if (!empty($custom_payment_address['payment_custom_beltway_distance']) && !empty($custom_payment_address['payment_custom_beltway_distance']['value'])) { ?>
														<span id="payment-mkad-error" class="error"><i class="fa fa-exclamation-triangle"></i> Внимание! Адрес находится за МКАД. Стоимость доставки увеличена!</span>
													<? } ?>
													<input class="onfocusedit_direct" type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" /><span></span>
													<input class="onchangeedit_direct" type="hidden" id="payment_address_struct" name="payment_address_struct" value="<?php echo $payment_address_struct; ?>" /><span></span>
													<?php if ($error_payment_address_1) { ?>
														<span class="error"><?php echo $error_payment_address_1; ?></span>
													<?php } ?>
													
													<?php if (!$valid_payment_address_struct) { ?>
														<span class="error" id="valid_payment_address_struct"><i class="fa fa-exclamation-triangle"></i> Нет детальной информации о адресе, используй подбор! 
															<?php if ($shipping_code == 'dostavkaplus.sh1') { ?>
																<br /><i class="fa fa-exclamation-triangle"></i> Ошибка уровень 0: Указана доставка курьером! Подбор адреса поможет курьеру не заблудиться в лесу!<?php } ?>
															</span>
														<?php } ?>
														
														<?php if ($suggest_get_payment_address_struct_stage_1) { ?>
															<br /><span class="error" id="suggest_get_payment_address_struct_stage_1">
																<i class="fa fa-exclamation-triangle"></i> Ошибка уровень 1: Получен полный адрес, но нет структуры</span>
															<?php } ?>
															
															<?php if ($suggest_get_payment_address_struct_stage_2) { ?>
																<br /><span class="error" id="suggest_get_payment_address_struct_stage_2">
																	<i class="fa fa-exclamation-triangle"></i> Ошибка уровень 2: Доставка курьером, не разобран адрес! Планируется безусловный автоматический поиск адреса и перезапись.</span>
																<?php } ?>
																
																<?php if ($use_custom_dadata == 'address') { ?>	
																	<?php echo $payment_custom_data; ?>														
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><?php echo $entry_address_2; ?></td>
															<td><input class="onfocusedit_direct" type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" /><span></span></td>
														</tr>
														<tr>
															<td><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
															<td><input class="onfocusedit_direct" type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" /><span></span>
																<?php if ($error_payment_postcode) { ?>
																	<span class="error"><?php echo $error_payment_postcode; ?></span>
																	<?php } ?></td>
																</tr>
																<tr>
																	<td> <?php echo $entry_country; ?></td>
																	<td>
																		<select name="payment_country_id" class="onchangeedit_direct">
																			<option value=""><?php echo $text_select; ?></option>
																			<?php foreach ($countries as $country) { ?>
																				<?php if ($country['country_id'] == $payment_country_id) { ?>
																					<option data-iso="<? echo $country['iso_code_2']; ?>" value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
																				<?php } else { ?>
																					<option data-iso="<? echo $country['iso_code_2']; ?>" value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
																				<?php } ?>
																			<?php } ?>
																		</select><span></span>
																		<?php if (!$payment_country_id && $ip_geoip_full_info['country_name']) { ?>
																			<div style="font-size:12px;"><i class="fa fa-globe" aria-hidden="true"></i> Предположительно, страна <? if ($ip_geoip_full_info['country_code'] && file_exists(DIR_APPLICATION . '/view/image/flags/' . mb_strtolower($ip_geoip_full_info['country_code']) . '.png')) { ?>
																				<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($ip_geoip_full_info['country_code']); ?>.png" title="<? echo mb_strtolower($ip_geoip_full_info['country_code']) ?>" />
																			<? } ?>&nbsp;<?php echo $ip_geoip_full_info['country_name']; ?>
																		</div>
																	<?php } ?>
																	<?php if ($error_payment_country) { ?>
																		<span class="error"><?php echo $error_payment_country; ?></span>
																		<?php } ?></td>
																	</tr>
																	<tr>
																		<td> <?php echo $entry_zone; ?></td>
																		<td><select name="payment_zone_id" class="onchangeedit_direct">
																		</select><span></span>
																		<?php if ($error_payment_zone) { ?>
																			<span class="error"><?php echo $error_payment_zone; ?></span>
																			<?php } ?></td>
																		</tr>
																		<? if ($customer_info['is_mudak']) { ?>
																			<tr><td colspan="2" style="background: #ea8686; padding:3px; text-align:center; font-weight:700;">Обслуживание только на условиях предоплаты!</td></tr>
																		<? } ?>
																		<tr>
																			<td class="left"><b><?php echo $entry_payment; ?></b></td>
																			<td class="left"><select name="payment">
																				<option value=""><?php echo $text_select; ?></option>
																				<?php if ($payment_code) { ?>
																					<option value="<?php echo $payment_code; ?>" selected="selected"><?php echo $payment_method; ?></option>
																				<?php } ?>
																			</select>
																			<input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>" />
																			<input type="hidden" name="payment_code" value="<?php echo $payment_code; ?>" />												
																			<?php if ($error_payment_method) { ?>
																				<span class="error"><?php echo $error_payment_method; ?></span>
																			<?php } ?>
																			&nbsp;&nbsp;<span id="load_payments" style="padding:3px;"><i class="fa fa-refresh"></i></span>
																		</td>
																	</tr>	
																	
																	<? if (!in_array($currency_code, array('EUR', 'UAH', 'RUB'))) { ?>
																		<tr>
																			<th style="background-color:#0054b3; padding:6px;"><i class="fa fa-info-circle" style="font-size:22px;" aria-hidden="true"></i> </th>
																			<th colspan="1" style="background-color:#0054b3; padding:6px; text-align:left;">При оплате Concardis сумма к оплате в евро: <? echo $EUR_FOR_CONCARDIS; ?> EUR <br /> конвертацию в локальную валюту выполняет банк клиента</th>						
																		</tr>
																	<? } ?>
																	<tr>
																		<td class="left"><b>Вторичный способ оплаты</b></td>
																		<td class="left"><select name="payment_secondary">
																			<option value=""><?php echo $text_select; ?></option>
																			<?php if ($payment_secondary_code) { ?>
																				<option value="<?php echo $payment_secondary_code; ?>" selected="selected"><?php echo $payment_secondary_method; ?></option>
																			<?php } ?>
																		</select>
																		<input type="hidden" name="payment_secondary_method" value="<?php echo $payment_secondary_method; ?>" />
																		<input type="hidden" name="payment_secondary_code" value="<?php echo $payment_secondary_code; ?>" />												
																		<?php if ($error_payment_method) { ?>
																			<span class="error"><?php echo $error_payment_method; ?></span>
																		<?php } ?>
																		&nbsp;&nbsp;<span id="load_secondary_payments" style="padding:3px;"><i class="fa fa-refresh"></i></span>																										
																	</td>
																</tr>	
																
																<? if (!empty($pay_paykeeper_alert)) { ?>
																	<tr>
																		<td style="text-align:right; color:#7F00FF;"><i class="fa fa-info-circle" style="font-size:24px;"></i></td>
																		<td style="background-color:#7F00FF; padding:5px; color:#fff;">Внимание! Картами "МИР" нельзя оплачивать платежи на Concardis. Это ограничение самой платежной системы МИР. Только для таких случаев можно включать PayKeeper.</td>
																	</tr>
																<? } ?>
																
																<tr>
																	<td><b>Эквайринг</b></td>
																	<? $this->load->model('sale/order'); ?>
																	<td>
																		<style>.eq_disabled{ filter: grayscale(100%);  -webkit-filter: grayscale(100%);}</style>
																		<table style="border:0px;" border="0">													
																			<tr>
																				<?php if ($pay_paykeeper) { ?>
																					<td style="padding-right:10px; text-align:center;">
																						<img id="pay_equire_img" src="<? echo HTTPS_CATALOG; ?>catalog/view/image/payment/PayKeeper.png" style="cursor:pointer" height="30px"  <? if (!$pay_equire) { ?>class="eq_disabled"<? } ?> />
																						<input type="hidden" class="onchangeedit_direct" id="pay_equire" name="pay_equire" value="<? if ($pay_equire) { ?>1<? } else { ?>0<? } ?>"  />
																						
																						<div style="background-color:#4ea24e; padding:5px; text-align:center; color:#FFF">KZT, BYN, RUB</div>
																						
																						<br />
																						<img src="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'paykeeper'); ?>" />
																						<br />
																						<a style="font-size:12px" href="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'paykeeper', true); ?>" ><? echo $this->model_sale_order->generatePaymentQR($order_id, 'paykeeper', true); ?></a>
																						
																					</td>
																				<? } ?>

																				<?php if ($this->config->get('paypal_status')) { ?>
																					<?php if (in_array($currency_code, array('RUB', 'EUR'))) { ?>	
																						<td style="padding-right:10px; text-align:center;">
																							<img id="pay_equirePP_img" src="<? echo HTTPS_CATALOG; ?>catalog/view/image/payment/pp-logo-200px.png" style="cursor:pointer" height="30px"  <? if (!$pay_equirePP) { ?>class="eq_disabled"<? } ?> />
																							<input type="hidden" class="onchangeedit_direct" id="pay_equirePP" name="pay_equirePP" value="<? if ($pay_equirePP) { ?>1<? } else { ?>0<? } ?>"  />
																							
																							<div style="background-color:#4ea24e; padding:5px; text-align:center; color:#FFF">RUB</div>
																							
																							<br />
																							<img src="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'pp_express'); ?>" />
																							<br />
																							<a style="font-size:12px" href="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'pp_express', true); ?>" ><? echo $this->model_sale_order->generatePaymentQR($order_id, 'pp_express', true); ?></a>
																							
																						</td>
																					<? } ?>
																				<?php } ?>
																				<?php if ($currency_code == 'UAH') { ?>	
																					
																					<?php if ($this->config->get('liqpay_status_fake')) { ?>
																						<td style="padding-right:10px; text-align:center;">
																							<img id="pay_equireLQP_img" src="<? echo HTTPS_CATALOG; ?>catalog/view/image/payment/lqp_logo.jpg" style="cursor:pointer" height="25px"  <? if (!$pay_equireLQP) { ?>class="eq_disabled"<? } ?> />
																							<input type="hidden" class="onchangeedit_direct" id="pay_equireLQP" name="pay_equireLQP" value="<? if ($pay_equireLQP) { ?>1<? } else { ?>0<? } ?>"  />
																							
																							<div style="background-color:#4ea24e; padding:5px; text-align:center; color:#FFF">UAH</div>
																							
																							<br />
																							<img src="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'liqpay'); ?>" />
																							<br />
																							<a style="font-size:12px" href="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'liqpay', true); ?>" ><? echo $this->model_sale_order->generatePaymentQR($order_id, 'liqpay', true); ?></a>
																						</td>
																					<?php } ?>
																					
																					<?php if ($this->config->get('wayforpay_status_fake')) { ?>
																						<td style="padding-right:10px; text-align:center;">
																							<img id="pay_equireWPP_img" src="<? echo HTTPS_CATALOG; ?>catalog/view/image/payment/wpp_full_dark.png" style="cursor:pointer" height="30px"  <? if (!$pay_equireWPP) { ?>class="eq_disabled"<? } ?> />
																							<input type="hidden" class="onchangeedit_direct" id="pay_equireWPP" name="pay_equireWPP" value="<? if ($pay_equireWPP) { ?>1<? } else { ?>0<? } ?>"  />
																							
																							<div style="background-color:#4ea24e; padding:5px; text-align:center; color:#FFF">UAH</div>
																							
																							<br />
																							<img src="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'wayforpay'); ?>" />
																							<br />
																							<a style="font-size:12px" href="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'wayforpay', true); ?>" ><? echo $this->model_sale_order->generatePaymentQR($order_id, 'wayforpay', true); ?></a>
																						</td>
																					<?php } ?>
																					
																				<?php } ?>


																				<?php if ($this->config->get('concardis_status')) { ?>
																					<td style="padding-right:10px; text-align:center;">
																						<img id="pay_equireCP_img" src="<? echo HTTPS_CATALOG; ?>catalog/view/image/payment/concardis-1.png" style="cursor:pointer" height="30px"  <? if (!$pay_equireCP) { ?>class="eq_disabled"<? } ?> />
																						<input type="hidden" class="onchangeedit_direct" id="pay_equireCP" name="pay_equireCP" value="<? if ($pay_equireCP) { ?>1<? } else { ?>0<? } ?>"  />
																						
																						<? if (in_array($currency_code, array('EUR', 'UAH', 'RUB'))) { ?>
																							<div style="background-color:#4ea24e; padding:5px; text-align:center; color:#FFF"><? echo $currency_code; ?></div>
																						<? } else { ?>
																							<div style="background-color:#4ea24e; padding:5px; text-align:center; color:#FFF"><? echo $EUR_FOR_CONCARDIS; ?> EUR</div>
																						<? } ?>
																						
																						<br />
																						<img src="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'concardis'); ?>" />
																						<br />
																						<a style="font-size:12px" href="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'concardis', true); ?>" ><? echo $this->model_sale_order->generatePaymentQR($order_id, 'concardis', true); ?></a>
																					</td>
																					<? if (in_array($currency_code, array('RUB'))) { ?>
																						<td style="padding-right:10px; text-align:center;">
																							<div style="height:30px"></div>
																							<div style="background-color:#4ea24e; padding:5px; text-align:center; color:#FFF"><? echo $EUR_FOR_CONCARDIS; ?> EUR</div>
																							<br />
																							<img src="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'concardis', false, 'EUR'); ?>" />
																							<br />
																							<a style="font-size:12px" href="<? echo $this->model_sale_order->generatePaymentQR($order_id, 'concardis', true, 'EUR'); ?>" ><? echo $this->model_sale_order->generatePaymentQR($order_id, 'concardis', true, 'EUR'); ?></a>
																						</td>
																					<? } ?>
																				<?php } ?>
																			</tr>
																		</table>																											
										<?	/*
											<img id="pay_equire2_img" src="view/image/payment/shoputils_psb.png" style="cursor:pointer" height="40px"  <? if (!$pay_equire2) { ?>class="eq_disabled"<? } ?> />
											
										*/ ?>	
										<input type="hidden" id="pay_equire2" name="pay_equire2" value="<? if ($pay_equire2) { ?>1<? } else { ?>0<? } ?>"  />
									</td>
								</tr>
								<?php if ($this->config->get('concardis_status')) { ?>
									<tr>
										<td><b>Concardis debug:</b></td>													
										<td>Код заявки: <input type="text" id="concardis_id" name="concardis_id" value="<? echo $concardis_id; ?>" style="width:150px;" /> 
											<a id="get_cc_info" class="button"><i class="fa fa-info"></i> инфо по заявке</a> 
											<a id="clear_cc_order_id" class="button redbg" onclick="$('#concardis_id').val('');"><i class="fa fa-trash" aria-hidden="true"></i> удалить заявку</a>
											<br />
											<style>pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
											.string { color: green; }
											.number { color: darkorange; }
											.boolean { color: blue; }
											.null { color: magenta; }
										.key { color: red; }</style>
										<div id="cc_debug_result" style=""></div>
										
									</td>
								</tr>
								
								<?php if ($CONCARDIS_IS_PAID_AND_CHECKED_AUTO) { ?>		
									<tr>
										<td></td>
										<td style="background-color:#4ea24e; padding:10px; color:#fff;">
											<i class="fa fa-check-circle"></i> Все хорошо, оплата Concardis внесена автоматически.
										</td>												
									</tr>																									
								<? } ?>
								
								<?php if ($CONCARDIS_IS_PAID_AND_CHECKED_MANUAL) { ?>		
									<tr>
										<td></td>
										<td style="background-color:#ffeaa8; padding:10px; color:rgb(130, 98, 0);">
											<i class="fa fa-check-circle"></i> Оплата Concardis внесена вручную доблестным бухгалтером. Проверь на всякий случай.
										</td>												
									</tr>																									
								<? } ?>
								
								<?php if ($CONCARDIS_IS_PAID_AND_CHECKED_MANUAL || $CHECK_CONCARDIS_STATUS_STAGE_1) { ?>	
									<?php if ($CHECK_CONCARDIS_STATUS_STAGE_1) { ?>
										<tr>
											<td></td>
											<td style="background-color:#ffeaa8; padding:10px; color:rgb(130, 98, 0);">
												<i class="fa fa-exclamation-triangle"></i> Заявка создана, были как минимум попытки оплаты, но оплата не внесена.
											</td>												
										</tr>	
									<?php } ?>
									<tr>
										<td></td>
										<td style="background-color:#cf4a61; padding:10px; color:#fff;" id="check_concardis_status_stage_id">
										</td>												
									</tr>
								<? } ?>
								
								<?php if ($TRANSACTION_DEBIT_PENDING) { ?>		
									<tr>
										<td></td>
										<td style="background-color:#cf4a61; padding:10px; color:#fff;">Внимание! Состояние оплаты Concardis: <?echo $lastOperation; ?>. Можно попробовать любую другую платежку, однако необходимо уточнить статус платежа у бухгалтерии.</td>												
									</tr>																									
								<? } ?>
								
								<tr>
									<td></td>
									<td style="background-color:#FFF; padding:10px; color:#cf4a61;"><i class="fa fa-info-circle"></i> Очистка идентификатора транзакции при следующей попытке оплаты создаст новую заявку. Будьте внимательны и используйте это только в исключительных случаях.</td>											
								</tr>																									
							<? } ?>
							
							<tr>
								<td><b>Вид оплаты</b></td><td>
									<?
									$pay_types = array(
										'Наличный расчет' => 'cash',
										'Безналичный расчет' => 'cashless',
										'Банковской картой' => 'bank',
										'Наложенный платеж' => 'afterpay'
									);
									?>
									<select name="pay_type" id="select_pay_type" class="onchangeedit_direct">
										<? $current_pay_code = ''; foreach ($pay_types as $pay_type_one => $pay_type_one_code) {  ?>
											<option value="<?php echo $pay_type_one; ?>" data-type="<?php echo $pay_type_one_code; ?>" <? if ($pay_type_one == $pay_type) { $current_pay_code = $pay_type_one_code; ?>selected="selected"<? } ?>><?php echo $pay_type_one; ?></option>
										<? } ?>
									</select><span></span>
									
									<? require_once(dirname(__FILE__) . '/order_form.js.cashless.tpl'); ?>		
									
									<div <? if ($current_pay_code != 'bank') { ?>style="display:none;"<? } ?> id="div_card_info">
										<div style="padding:3px; background-color:#1f4962; color:#FFF; text-align:center; margin-top:5px; margin-bottom:5px;">Выбор карты для оплаты</div>
										<div style="clear:both;"></div>
										Оплата на: <select name="card_id" id="card_id" class="onchangeedit_direct">
											<option value="0">Не выбрана карта</option>
											<? foreach ($cards as $card) { ?>
												<option <? if ($card['legalperson_id'] == $card_id) { ?>selected="selected"<? } ?> value="<? echo $card['legalperson_id']; ?>"><? echo $card['legalperson_name']; ?></option>
											<? } ?>
										</select><span></span>
									</div>
									
									
									<div <? if ($current_pay_code != 'cashless') { ?>style="display:none;"<? } ?> id="div_cashless_info">															
										<div style="padding:3px; background-color:#1f4962; color:#FFF; text-align:center; margin-top:5px; margin-bottom:5px;">Выставление счета</div>
										<div style="clear:both;"></div>
										Выставить счет на: <select name="legalperson_id" id="legalperson_id" class="onchangeedit_direct">
											<option value="0">Не выбрано юрлицо</option>
											<? foreach ($legalpersons as $legalperson) { ?>																	
												<option <? if ($legalperson['legalperson_id'] == $legalperson_id) { ?>selected="selected"<? } ?> value="<? echo $legalperson['legalperson_id']; ?>"><? echo $legalperson['legalperson_name']; ?></option>
											<? } ?>
										</select><span></span>		
										<? if (isset($current_legalperson['info']) && isset($current_legalperson['info']['at_this_moment'])) { ?>
											<div style="clear:both; height:4px;"></div>
											<div style="padding:3px; background-color:grey; color:#FFF; text-align:center; margin-top:5px; margin-bottom:5px;"><i class="fa fa-info"></i> Информация о счете</div>
											<div>
												На текущий момент на счету <b id="clp_at_this_moment"><? echo $current_legalperson['info']['at_this_moment']; ?></b><br />
												Оплачено по безналу с начала месяца <b id="clp_total_already_paid"><? echo $current_legalperson['info']['total_already_paid']; ?></b><br />
												Выставлено неоплаченных счетов на <b id="clp_total_need_to_pay"><? echo $current_legalperson['info']['total_need_to_pay']; ?></b>
												
												<? if ($currency_code == 'RUB') { ?><br /><br /><i class="fa fa-warning" aria-hidden="true" style="color:#f91c02"></i> По ИП Башлаев крайне не рекомендуется превышать<br /> месячный лимит оплат в <b>900 тыс. руб</b><? } ?>
												<? if ($currency_code == 'UAH') { ?><br /><br /><i class="fa fa-warning" aria-hidden="true" style="color:#f91c02"></i> По ИП Домбровский и ИП Романченко не рекомендуется превышать<br /> месячный лимит оплат в <b>100 тыс. грн</b><? } ?>
											</div>
										<? } ?>
										<div style="clear:both; height:4px;"></div>
										<div>	
											<div style="clear:both; height:4px;"></div>
											<div style="padding:3px; background-color:grey; color:#FFF; text-align:center; margin-top:5px; margin-bottom:5px;">Информация о плательщике</div>
											<textarea name="cashless_info" class="onfocusedit_customer" rows="5" cols="50" style="width:90%; resize:both"><? echo $customer_info['cashless_info']; ?></textarea><span></span>
											
											<div style="clear:both; height:5px;"></div>
											<div style="float:left; display:inline-block;">																	
												<div style="padding:2px; font-size:11px; background-color:#1f4962; color:#FFF; text-align:center; margin-bottom:5px;">Счет - фактура</div>
												<div style="float:left; margin-bottom:3px;"><a class="button" id="cashless_preview"><i class="fa fa-eye"></i></a></div>															
												<div style="float:left; margin-bottom:3px; margin-left:3px;"><a class="button" id="cashless_print"><i class="fa fa-print"></i></a></div>
												<div style="float:left; margin-bottom:3px; margin-left:3px;"><a class="button" id="cashless_pdf"><i class="fa fa-file-pdf-o"></i></a></div>
												<div style="clear:both; height:3px;"></div>
												<input class="checkbox" type="checkbox" id="cashless_nostamp" name="cashless_nostamp" value="1" /><label for="cashless_nostamp"><s style="color:#cf4a61">Печать</s></label>
												
											</div>
											
											<div style="float:left;  margin-left:10px; display:inline-block; width:80px;">																	
												<div style="padding:2px; font-size:11px; background-color:#1f4962; color:#FFF; text-align:center; margin-bottom:5px;">Номер</div>
												<div style="float:left; margin-bottom:3px; width:80px;">
													<input type="text" style="width:80px;" class="onchangeedit_direct" name="invoice_no" id="invoice_no_input" data-field="invoice_no" value="<? echo $invoice_no_noprefix; ?>" />
												</div>
											</div>
											
											<div style="float:left;  margin-left:10px; display:inline-block; width:100px;">																	
												<div style="padding:2px; font-size:11px; background-color:#1f4962; color:#FFF; text-align:center; margin-bottom:5px;">Дата</div>
												<div style="float:left; margin-bottom:3px; width:100px;">
													<input type="text" style="width:100px; font-size:10px;" name="invoice_date" class="onchangeedit_direct date" id="invoice_date_input" data-field="invoice_date" value="<? echo $invoice_date; ?>" />
												</div>
											</div>
											
											<div style="float:left; margin-left:10px;display:inline-block;">
												<? if ($invoice_no && $shipping_country_id == 176) { ?>																		
													<div style="padding:2px; font-size:11px; background-color:#1f4962; color:#FFF; text-align:center; margin-bottom:5px;">ТОРГ - 12</div>
													<div style="float:left; margin-bottom:3px;"><a class="button" id="torg12_preview"><i class="fa fa-eye"></i></a></div>															
													<div style="float:left; margin-bottom:3px; margin-left:3px;"><a class="button" id="torg12_print"><i class="fa fa-print"></i></a></div>
													<div style="float:left; margin-bottom:3px; margin-left:3px;"><a class="button" id="torg12_pdf"><i class="fa fa-file-pdf-o"></i></a></div>
													<div style="clear:both; height:3px;"></div>
													<input class="checkbox" type="checkbox" id="torg12_nostamp" name="torg12_nostamp" value="1" /><label for="torg12_nostamp"><s style="color:#cf4a61">Печать</s></label>
												<? } ?>
											</div>
										</div>
										
									</div>
								</td>
							</tr>											
							<tr>
								<td><b>Файл счета</b></td><td><input type="file" name="bill_file" />
									<a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button"><i class="fa fa-paperclip"></i></a><br />
									<? if (isset($bill_file) && $bill_file) { ?>
										<span id="bill_attached">Файл: <b><? echo $bill_file; ?></b>&nbsp;&nbsp;<span style="border-bottom:1px dashed black;cursor:pointer" onclick="$('#bill_attached').load('index.php?route=sale/order/removeBillFileAjax&order_id=<? echo $order_id; ?>&token=<? echo $token; ?>')">удалить</span></span>
									<? } ?>
								</td>
							</tr>
							<tr>
								<td><b>Файл доп. инфо</b></td><td><input type="file" name="bill_file2" />
									<a onclick="$('#form').submit();" class="button"><i class="fa fa-paperclip"></i></a><br />
									<? if (isset($bill_file2) && $bill_file2) { ?>
										<span id="bill2_attached">Файл: <b><? echo $bill_file2; ?></b>&nbsp;&nbsp;<span style="border-bottom:1px dashed black;cursor:pointer" onclick="$('#bill2_attached').load('index.php?route=sale/order/removeBillFile2Ajax&order_id=<? echo $order_id; ?>&token=<? echo $token; ?>')">удалить</span></span>
									<? } ?>
								</td>
							</tr>
							
							
							<th colspan="2">Даты заказа</th>
							<tr>
								<td>Дата <b>заказа</b> у поставщика (резервирование)</td>
								<td>
									<input class="date onfocusedit_direct" autocomplete="false"  name="date_buy" value="<? echo $date_buy; ?>" /><span></span>
									<span class="help">Для покупателя: товар зарезервирован для вас на складе. По умолчанию: <? echo date('m.d'); ?></span>
								</td>
							</tr>
							<tr>
								<td>Дата <b>прибытия</b> в страну (ориентировочно)</td>
								<td>
									<input class="date onfocusedit_direct" autocomplete="false" name="date_country" value="<? echo $date_country; ?>" /><span></span>
									<span class="help">Для покупателя: ожидаемое прибытие товара на склад. По умолчанию: 
										<? echo date('m.d'); ?> <? if ($shipping_country_id == 220) { ?>+1 неделя<? } else { ?>+2 недели<? } ?>
									</span>
								</td>
							</tr>											
							<tr>
								<td>Дата оплаты для действительности условий</td>
								<td>
									<input class="date onfocusedit_direct" autocomplete="false" name="date_maxpay" value="<? echo $date_maxpay; ?>" /><span></span>
									<span class="help">Для покупателя: условия будут действительны при внесении оплаты до этой даты. По умолчанию: <? echo date('m.d'); ?> +2 дня</span>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right">
									<input name="do_recalculate_date" id="do_recalculate_date" value="1" type="checkbox">&nbsp;<a id="button_recalculate_date" onclick="" class="button redbg">Заполнить даты от сегодня</a>&nbsp;&nbsp;<a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button save_button">Сохранить</a>
								</td>
							</tr>
						</table>
						
					</div>
					
					<div id="payment-shipping-copy" style="float:left;margin-left:5px;margin-top:100px;width:20px;font-size:36px;font-weight:900;cursor:pointer;color:#1f4962;">
						<span id="payment-to-shipping">
							<i class="fa fa-arrow-circle-right"></i>
						</span>
						<span style="display:inline-block;height:20px;">
						</span>
						<span id="shipping-to-payment">
							<i class="fa fa-arrow-circle-left"></i>
						</span>
					</div>
					
					<div id="tab-shipping">												
						<table class="orderadress" style="float:right; max-width:45%!important">
							<th colspan="2"><?php echo $tab_shipping; ?></th>
							<? if ($need_to_have_passport_data && (empty($shipping_passport_given) || empty($shipping_passport_serie))) { ?>
								<tr>
									<th colspan="2" style="background-color:#cf4a61; padding:5px;">Внимание! Для отправки курьерской службой необходимы данные, подтверждающие личность получателя!</th>		
								</tr>
							<? } ?>
							<tr>
								<td><?php echo $entry_address; ?></td>
								<td><div  style="max-width:90%!important;"><select name="shipping_address" id="shipping_address" class="wide" style="width:350px;">
									<option value="0" selected="selected"><?php echo $text_none; ?></option>
									<?php foreach ($addresses as $address) { ?>
										<option value="<?php echo $address['address_id']; ?>"><?php echo $address['city']; ?><?php if ($address['address_1']) { ?>, <?php echo $address['address_1']; ?><?php } ?><? if ($address['verified']) { ?>/ Вериф.<? } ?><? if ($address['for_print']) { ?> Печатн.<? } ?></option>
									<?php } ?>
								</select></div></td>
							</tr>
							
							<tr>
								<td class="left" style="background-color:#7F00FF; padding:5px; color:#fff;"><b><?php echo $entry_shipping; ?></b></td>
								<td class="left">
									<select name="shipping" class="wide" style="width:350px;">
										<option value=""><?php echo $text_select; ?></option>
										<?php if ($shipping_code) { ?>
											<option value="<?php echo $shipping_code; ?>" selected="selected"><?php echo $shipping_method; ?></option>
										<?php } ?>
									</select><span></span>
									<input type="hidden" name="shipping_method" value="<?php echo $shipping_method; ?>" />
									<input type="hidden" name="shipping_code" value="<?php echo $shipping_code; ?>" />
									<?php if ($error_shipping_method) { ?>
										<span class="error"><?php echo $error_shipping_method; ?></span>
										<?php } ?>&nbsp;&nbsp;<span id="load_shippings" style="padding:3px;"><i class="fa fa-refresh"></i></span>
										
										<?php if ($courier) { ?>
											<div style="background-color:#7F00FF; padding:5px; color:#fff; margin-top:5px; display:inline-block">Повезет заказ <i class="fa fa-truck" aria-hidden="true"></i> <?php echo $courier['realname']; ?>
										</div>
										
										<?php if ($date_delivery_actual != '0000-00-00') { ?>
											<div style="background-color:#4ea24e; padding:5px; color:#fff; margin-top:5px; display:inline-block">
												Доставка назначена на <?php echo date('d.m.Y', strtotime($date_delivery_actual)); ?>
											</div>
										<?php } ?>
										
									<?php } ?>
								</td>
							</tr>
							
							<tr>
								<td> <?php echo $entry_firstname; ?></td>
								<td><input class="onfocusedit_direct" type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" /><span></span>
									<?php if ($error_shipping_firstname) { ?>
										<span class="error"><?php echo $error_shipping_firstname; ?></span>
										<?php } ?></td>
									</tr>
									<tr>
										<td> <?php echo $entry_lastname; ?></td>
										<td><input class="onfocusedit_direct" type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" /><span></span>
											<?php if ($error_shipping_lastname) { ?>
												<span class="error"><?php echo $error_shipping_lastname; ?></span>
												<?php } ?></td>
											</tr>
											<tr>
												<td>Паспорт серия:</td>
												<td><input class="onfocusedit_direct" type="text" name="shipping_passport_serie" value="<?php echo $shipping_passport_serie; ?>" /><span></span>
													<span class="help">Если получатель отличается от плательщика</span></td>
												</tr>
												<tr>
													<td>Паспорт выдан:</td>
													<td><input class="onfocusedit_direct" type="text" name="shipping_passport_given" value="<?php echo $shipping_passport_given; ?>"/><span></span>
														<span class="help">Если получатель отличается от плательщика</span></td>
													</tr>
													<tr>
														<td><?php echo $entry_company; ?></td>
														<td><input class="onfocusedit_direct" type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" /><span></span></td>
													</tr>
													<tr>
														<td> <?php echo $entry_city; ?></td>
														<td>
															
															<input class="onfocusedit_direct" type="text" id="shipping_city" name="shipping_city" value="<?php echo $shipping_city; ?>" /><span></span>													
															
															<? if ($current_shipping_time) { ?>
																<div style="margin-top:4px;"><div style="font-size:14px; display:inline-block; padding:3px; color:#FFF; background-color:grey;"><i class="fa fa-clock-o"></i>&nbsp;текущее время <? echo $current_shipping_time; ?></div>
																</div><? } ?>
																
																<?php if (!$shipping_city) { ?>
																	<script>
																		$(document).ready(function(){
																			$.ajax({
																				url: "index.php?route=sale/order/getCityByIpAddrAjax&ip=<?php echo $ip; ?>&token=<?php echo $token; ?>",
																				type: 'GET',
																				dataType: 'json',
																				beforeSend: function(){
																					$('input[name=shipping_city]').before("<i class='fa fa-spinner fa-spin'></i>");
																					$('input[name=shipping_postcode]').before("<i class='fa fa-spinner fa-spin'></i>");
																				},
																				success: function(json){
																					$('input[name=shipping_city]').parent().children('.fa-spin').remove();
																					$('input[name=shipping_postcode]').parent().children('.fa-spin').remove();
																					
																					if (json.city){
																						$('input[name=shipping_city]').val(json.city);
																						$('input[name=shipping_city]').parent('td').parent('tr').after("<tr><td></td><td style='background-color:#cf4a61; padding:5px; color:#fff;'>Внимание! Город подобран на основании локации клиента! Нужно уточнять!</td></tr>");
																						$('input[name=shipping_city]').before("<i class='fa fa-question-circle' style='font-size:24px; color:#cf4a61'></i> ");
																					}
																					
																					if (json.zip){
																						$('input[name=shipping_postcode]').val(json.zip);
																						$('input[name=shipping_postcode]').parent('td').parent('tr').after("<tr><td></td><td style='background-color:#cf4a61; padding:5px; color:#fff;' colspan='2'>Внимание! Индекс подобран на основании локации клиента! Нужно уточнять!</td></tr>");
																						$('input[name=shipping_postcode]').before("<i class='fa fa-question-circle' style='font-size:24px; color:#cf4a61'></i> ");
																					}							
																				}
																			});
																		});
																	</script>
																<?php } ?>	
																
															</td>
														</tr>
														<tr>
															<td><?php echo $entry_address_1; ?></td>
															<td>
																<?php if (!empty($custom_shipping_address['shipping_custom_beltway_distance']) && !empty($custom_shipping_address['shipping_custom_beltway_distance']['value'])) { ?>
																	<span id="shipping-mkad-error" class="error"><i class="fa fa-exclamation-triangle"></i> Внимание! Адрес находится за МКАД. Стоимость доставки увеличена.</span>
																<? } ?>
																<input class="onfocusedit_direct" type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" />
																<span></span>
																<input class="onchangeedit_direct" type="hidden" id="shipping_address_struct" name="shipping_address_struct" value="<?php echo $shipping_address_struct; ?>" />
																<span></span>
																<?php if ($error_shipping_address_1) { ?>
																	<span class="error"><?php echo $error_shipping_address_1; ?></span>
																<?php } ?>
																
																<?php if (!$valid_shipping_address_struct) { ?>
																	<span class="error" id="valid_shipping_address_struct"><i class="fa fa-exclamation-triangle"></i> Нет детальной информации о адресе, используй подбор!
																		<?php if ($shipping_code == 'dostavkaplus.sh1') { ?><br /><i class="fa fa-exclamation-triangle"></i> Ошибка уровень 0: Указана доставка курьером! Подбор адреса поможет курьеру не заблудиться в лесу!<?php } ?></span>
																	<?php } ?>
																	
																	<?php if ($suggest_get_shipping_address_struct_stage_1) { ?>
																		<br /><span class="error" id="suggest_get_shipping_address_struct_stage_1"><i class="fa fa-exclamation-triangle"></i> Ошибка уровень 1: Получен полный адрес, но нет структуры</span>
																	<?php } ?>
																	
																	<?php if ($suggest_get_shipping_address_struct_stage_2) { ?>
																		<br /><span class="error" id="suggest_get_shipping_address_struct_stage_2">
																			<i class="fa fa-exclamation-triangle"></i>Ошибка уровень 2: Доставка курьером, не разобран адрес! Планируется безусловный автоматический поиск адреса и перезапись.</span>
																		<?php } ?>
																		
																		<?php if ($use_custom_dadata == 'address') { ?>	
																			<?php echo $shipping_custom_data; ?>														
																		<?php } ?>
																	</td>
																</tr>
																<tr>
																	<td><?php echo $entry_address_2; ?></td>
																	<td><input class="onfocusedit_direct" type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" /><span></span></td>
																</tr>												
																<tr>
																	<td><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
																	<td><input class="onfocusedit_direct" type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" /><span></span>
																		<?php if ($error_shipping_postcode) { ?>
																			<span class="error"><?php echo $error_shipping_postcode; ?></span>
																			<?php } ?></td>
																		</tr>
																		<tr>
																			<td> <?php echo $entry_country; ?></td>
																			<td><select name="shipping_country_id" class="onchangeedit_direct">
																				<option value=""><?php echo $text_select; ?></option>
																				<?php foreach ($countries as $country) { ?>
																					<?php if ($country['country_id'] == $shipping_country_id) { ?>
																						<option  data-iso="<? echo $country['iso_code_2']; ?>" value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
																					<?php } else { ?>
																						<option data-iso="<? echo $country['iso_code_2']; ?>" value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
																					<?php } ?>
																				<?php } ?>
																			</select><span></span>
																			<?php if (!$payment_country_id && $ip_geoip_full_info['country_name']) { ?>
																				<div style="font-size:12px;"><i class="fa fa-globe" aria-hidden="true"></i> Предположительно, страна <? if ($ip_geoip_full_info['country_code'] && file_exists(DIR_APPLICATION . '/view/image/flags/' . mb_strtolower($ip_geoip_full_info['country_code']) . '.png')) { ?>
																					<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($ip_geoip_full_info['country_code']); ?>.png" title="<? echo mb_strtolower($ip_geoip_full_info['country_code']) ?>" />
																				<? } ?>&nbsp;<?php echo $ip_geoip_full_info['country_name']; ?>
																			</div>
																		<?php } ?>
																		<?php if ($error_shipping_country) { ?>
																			<span class="error"><?php echo $error_shipping_country; ?></span>
																			<?php } ?></td>
																		</tr>
																		<tr>
																			<td> <?php echo $entry_zone; ?></td>
																			<td><select name="shipping_zone_id" class="onchangeedit_direct">
																			</select><span></span>
																			<?php if ($error_shipping_zone) { ?>
																				<span class="error"><?php echo $error_shipping_zone; ?></span>
																				<?php } ?></td>
																			</tr>												
																			<tr>
																				<td colspan='2' align="right">
																					<a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button save_button">Сохранить</a>
																				</td>
																			</tr>
																			<th colspan="2">Отправка, ТТН, SMS</th>
																			<tr><td colspan="2">
																				<div id="ttn_history"></div>
																			</td></tr>
																			<tr>
																				<td>Дата отправки:</td>
																				<td><input class="date" name="date_sent" value="<? echo date('Y-m-d'); ?>" />&nbsp;&nbsp;&nbsp;
																					<input type="checkbox" name="send_delivery_sms" value="1"></input><b>Отправить SMS</b></td>
																				</tr>
																				<tr>
																					<td>Накладная ТТН:</td>
																					<td><input type="text" name="ttn" value="" style="width:400px;" /></td>
																				</tr>
																				<tr>
																				</tr>
																				<tr>
																					<td colspan='2'>
																						<textarea name="delivery_sms_text" rows='3' style="width:100%">
																						</textarea>
																					</td>
																				</tr>
																				<td colspan='2' align="right">
																					<a id="delivery_sms_send_button" onclick="addTTNAjax()" class="button save_button">Добавить ТТН</a>
																				</td>
																			</table>
																		</div>
																	<?php if  ($this->config->get('config_order_bottom_text_enable')) { ?>	
																		<div class="clr"></div>																		
																		<div id="tab-bottom-text">
																			<table class="list">
																				<thead>
																					<tr>
																						<th colspan="1" style="padding:5px;">Текст подтверждения<br /><span class="help" style="color:#FFF">Внимание! Перезагрузка шаблона отменяет сохраненные в нем действия!</span></th>
																					</tr>
																				</thead>
																				<tr>
																					<td style="padding:5px;">
																						Шаблон:
																						<? if ($shipping_country_id == 176) { ?>
																							<select style="padding:3px; font-size:14px;" id="text_template">
																								<option value="MOSCOW_COURIER_CASH">
																									Москва : курьер, наличные
																								</option>
																								<option value="MOSCOW_COURIER_NOCASH">
																									Москва : курьер, безналичный
																								</option>
																								<option value="MOSCOW_COURIER_FULL_PREPAY">
																									Москва : курьер, полная предоплата
																								</option>
																								<option value="MOSCOW_COURIER_PARTIAL_PREPAY">
																									Москва : курьер, частичная предоплата
																								</option>
																								<option value="MOSCOW_PICKUP">
																									Москва : самовывоз, постоплата
																								</option>
																								<option value="MOSCOW_PICKUP_FULL_PREPAY">
																									Москва : самовывоз, полная предоплата
																								</option>
																								<option value="MOSCOW_PICKUP_PARTIAL_PREPAY">
																									Москва : самовывоз, частичная предоплата
																								</option>
																								<option value="RU_TRANSPORTER_FULL_PREPAY">
																									Россия : полная предоплата
																								</option>
																								<option value="RU_TRANSPORTER_PARTIAL_PREPAY">
																									Россия : частичная предоплата
																								</option>
																								<option value="RU_TRANSPORTER_FULL_PREPAY_NOCASH">
																									Россия : полная предоплата, безналичный
																								</option>
																								<option value="RU_TRANSPORTER_AFTERPAY">
																									Россия : наложенный платеж
																								</option>
																							</select>
																						<? } elseif ($shipping_country_id == 220) { ?>
																							<select style="padding:3px; font-size:14px;" id="text_template">
																								<option value="KYIV_COURIER_CASH">
																									Киев : курьер, наличные
																								</option>
																								<option value="KYIV_COURIER_NOCASH">
																									Киев : курьер, безналичный
																								</option>
																								<option value="KYIV_COURIER_FULL_PREPAY">
																									Киев : курьер, полная предоплата
																								</option>
																								<option value="KYIV_COURIER_PARTIAL_PREPAY">
																									Киев : курьер, частичная предоплата
																								</option>
																								<option value="UA_TRANSPORTER_FULL_PREPAY">
																									Украина : полная предоплата
																								</option>
																								<option value="UA_TRANSPORTER_PARTIAL_PREPAY">
																									Украина : частичная предоплата
																								</option>
																								<option value="UA_TRANSPORTER_FULL_PREPAY_NOCASH">
																									Украина : полная предоплата, безналичный
																								</option>
																								<option value="UA_TRANSPORTER_AFTERPAY">
																									Украина : наложенный платеж
																								</option>
																							</select>
																						<? } elseif ($shipping_country_id == 20) { ?>
																							<select style="padding:3px; font-size:14px;" id="text_template">
																								<option value="MINSK_COURIER_CASH">
																									Минск : курьер, наличные
																								</option>
																								<option value="MINSK_COURIER_NOCASH">
																									Минск : курьер, безналичный
																								</option>
																								<option value="BY_TRANSPORTER_FULL_PREPAY">
																									Белоруссия : полная предоплата
																								</option>
																								<option value="BY_TRANSPORTER_PARTIAL_PREPAY">
																									Белоруссия : частичная предоплата
																								</option>
																								<option value="BY_TRANSPORTER_FULL_PREPAY_NOCASH">
																									Белоруссия : полная предоплата, безналичный
																								</option>
																								<option value="BY_TRANSPORTER_AFTERPAY">
																									Белоруссия : наложенный платеж
																								</option>
																							</select>
																							
																						<? } elseif ($shipping_country_id == 109) { ?>
																							<select style="padding:3px; font-size:14px;" id="text_template">
																								<option value="ASTANA_COURIER_CASH">
																									Астана : курьер, наличные
																								</option>
																								<option value="ASTANA_COURIER_NOCASH">
																									Астана : курьер, безналичный
																								</option>
																								<option value="KZ_TRANSPORTER_FULL_PREPAY">
																									Казахстан : полная предоплата
																								</option>
																								<option value="KZ_TRANSPORTER_PARTIAL_PREPAY">
																									Казахстан : частичная предоплата
																								</option>
																								<option value="KZ_TRANSPORTER_FULL_PREPAY_NOCASH">
																									Казахстан : полная предоплата, безналичный
																								</option>
																								<option value="KZ_TRANSPORTER_AFTERPAY">
																									Казахстан : наложенный платеж
																								</option>
																							</select>
																						<? } elseif ($shipping_country_id == 81) { ?>
																							<select style="padding:3px; font-size:14px;" id="text_template">
																								<option value="DE_PAY_TO_BANK">
																									Германия : банковский счет
																								</option>
																							</select>
																						<? } ?>
																						&nbsp;&nbsp;<input class="checkbox" type="checkbox" id="is_bad_good" name="is_bad_good" />
																						<label for="is_bad_good">Без возврата!</label>&nbsp;&nbsp;
																						<a class="button save_button" id="update_bottom_text">Подгрузить шаблон текста</a>&nbsp;&nbsp;<a class="button save_button" onclick="CKEDITOR.instances.bottom_text.setReadOnly(false);">Разблокировать поле ввода</a>
																						<? if (mb_strlen($bottom_text)<=2 && mb_strlen($bottom_text_template_try) > 0) { ?>
																							<script>
																								$('#text_template option[value="<? echo $bottom_text_template_try; ?>"]').attr('selected', true);
																								<? if ($is_bad_good_try) { ?>
																									$('#is_bad_good').attr('checked', 'checked');
																								<? } ?>
																								update_bt_template();
																							</script>
																							<br />
																							<div style="background:#4ea24e;color:white;padding:3px;margin-top:4px;width:100%;text-align:center;">Сохраненного текста нет! Подобран и загружен шаблон: <? echo $bottom_text_template_try; ?> (<? echo $shipping_city ?>, <? echo $shipping_country_id ?>, <? echo $payment_method ?>, сумма: <? echo $total; ?> )</div>
																						<? } ?>
																					</td>
																				</tr>
																				<tr>
																					<td style="padding-top:10px;">
																						<textarea id="bottom_text" name="bottom_text"><? echo $bottom_text; ?></textarea>
																					</td>
																				</tr>
																				<tr>
																					<td style="padding:5px 0px;">
																						<a onclick="$('#mailpreview').load('<? echo $url_mailpreview; ?>').dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true});" class="button" style="float:left;">Предпросмотр</a>																						
																						<a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button save_button" style="float:right;">Сохранить</a>
																						<div class="clr"></div>
																					</td>
																				</tr>
																			</table>
																		</div>
																	<?php } else { ?>
																		<input id="bottom_text" name="bottom_text" value="" />
																	<?php } ?>
																		
																		<div id="mailpreview" style="display:none;"></div>
																		
																		<div class="clr"></div>
																		<div id="tab-total">
																			<table class="list">
																				<thead>
																					<tr><th colspan="6">Итого <br /><span style="font-size:10px;font-weight:400">формируется, пересчитывается после пересохранения заказа. некоторые цифры можно менять вручную.</span></th></tr>
																					<tr>
																						<td class="left"><?php echo $column_product; ?></td>
																						<td class="left"><?php echo $column_model; ?></td>
																						<td class="right"><?php echo $column_quantity; ?></td>
																						<td class="right"><?php echo $column_price; ?></td>
																						<td class="right"><?php echo $column_total; ?>, <? echo $currency_code; ?></td>
																					</tr>
																				</thead>
																				<tbody id="total">
																					<?php $total_row = 0; ?>
																					<?php if ($order_products || $order_vouchers || $order_totals) { ?>
																						<?php foreach ($order_products as $order_product) { ?>
																							<tr>
																								<td class="left"><?php echo $order_product['name']; ?><br />
																									<?php foreach ($order_product['option'] as $option) { ?>
																										- <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
																										<?php } ?></td>
																										<td class="left"><?php echo $order_product['model']; ?></td>
																										<td class="right"><?php echo $order_product['quantity']; ?></td>
																										<td class="right"><?php echo $order_product['price_txt']; ?></td>
																										<td class="right"><?php echo $order_product['total_txt']; ?></td>
																									</tr>
																								<?php } ?>
																								<?php foreach ($order_vouchers as $order_voucher) { ?>
																									<tr>
																										<td class="left"><?php echo $order_voucher['description']; ?></td>
																										<td class="left"></td>
																										<td class="right">1</td>
																										<td class="right"><?php echo $order_voucher['amount']; ?></td>
																										<td class="right"><?php echo $order_voucher['amount']; ?></td>
																									</tr>
																								<?php } ?>
																								<? if (count($order_totals) == 0) { ?>
																									<tr id="total-row-1">
																									</tr>
																								<? } ?>
																								
																								<? $a = array('sub_total', 'total'); ?>	
																								<?php foreach ($order_totals as $order_total) { ?>
																									<tr id="total-row<?php echo $total_row; ?>">
																										<td class="right" colspan="4" data-code="<?php echo $order_total['code']; ?>">
																											<? if (!in_array($order_total['code'] ,$a)) { ?>
																												<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$('#total-row<?php echo $total_row; ?>').remove();" />&nbsp;
																												
																												<? if ($order_total['value'] < 0 && $order_total['code'] <> 'reward') { ?>
																													
																													<span style="display:inline-block;width:50px; font-size:14px; font-weight:700">
																														<i class="fa fa-minus total-delivery-num-minus" data-order-total-row="<?php echo $total_row; ?>" style="cursor:pointer;"></i>
																														<span id="total_delivery_<?php echo $total_row; ?>_for_delivery_span"><?php echo $order_total['for_delivery']; ?></span>
																														<i class="fa fa-plus total-delivery-num-plus" data-order-total-row="<?php echo $total_row; ?>" style="cursor:pointer;"></i>
																														&nbsp;
																													</span>
																												<? } else { ?>
																													<span style="display:inline-block;width:50px;"></span>
																												<? } ?>
																												
																											<? } ?>
																											<input type="hidden" name="order_total[<?php echo $total_row; ?>][order_total_id]" value="<?php echo $order_total['order_total_id']; ?>" />
																											<input type="hidden" name="order_total[<?php echo $total_row; ?>][code]" value="<?php echo $order_total['code']; ?>" style="width:60px" />
																											<input type="hidden" name="order_total[<?php echo $total_row; ?>][value]" value="<?php echo $order_total['value']; ?>" />
																											<input type="hidden" id="order_total_<?php echo $total_row; ?>_for_delivery" name="order_total[<?php echo $total_row; ?>][for_delivery]" value="<?php echo $order_total['for_delivery']; ?>" />
																											
																											
																											<input id="title_<?php echo $order_total['code']; ?>" type="text" name="order_total[<?php echo $total_row; ?>][title]" value="<?php echo $order_total['title']; ?>" style="width:300px;" /> :
																											<input id="value_<?php echo $order_total['code']; ?>" type="text" name="order_total[<?php echo $total_row; ?>][value_national]" value="<?php echo $order_total['value_national']; ?>" style="width:200px; border: 1px solid <? echo ($order_total['value_national'] >= 0)?'#4ea24e':'#cf4a61'; ?>" /> <? echo $currency_code; ?>
																											
																											<input type="text" name="order_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $order_total['sort_order']; ?>" style="width:15px;" />
																										</td>
																										<td class="right"><b><input id="text_<?php echo $order_total['code']; ?>" type="text" name="order_total[<?php echo $total_row; ?>][text]" value="<?php echo $order_total['text']; ?>" style="width:150px; font-weight:700; <? if ($order_total['code'] == 'total') { ?>border:2px solid black;<? } ?>" /></b></td>
																									</tr>
																									
																									<? if ($order_total['for_delivery']) { ?>
																										<tr><td colspan='6' style="text-align:right;">
																											<span class="help"><span style="color:#cf4a61;">Внимание!</span> Эта скидка учитывается <span style="color:#cf4a61;">ТОЛЬКО в поставке #<?php echo $order_total['for_delivery']; ?></span></span>
																										</td></tr>
																									<? } ?>
																									
																									<? if (isset($order_total['discount_alert'])) { ?>
																										<tr><td colspan='6' style="text-align:right;">
																											<span class="help" style="font-size:11px; font-weight:700;"><? echo $order_total['discount_alert']; ?></span>
																										</td></tr>
																									<? } ?>
																									
																									<? if ($order_total['code'] == 'sub_total') { ?>
																										<tr><td colspan='6' style="text-align:right;">
																											<span class="help"><span style="color:#4ea24e;">Пересчитывается автоматически</span> при пересохранении! Это сумма всех товаров в заказе.</span>
																										</td></tr>
																									<? } ?>
																									
																									<? if ($order_total['code'] == 'total') { ?>
																										<tr><td colspan='6' style="text-align:right;">
																											<span class="help"><span style="color:#4ea24e;">Пересчитывается автоматически</span> <b>при пересохранении</b>! <b style="color:#cf4a61">Внимание! Эта сумма будет показана покупателю для полной оплаты!</b></span>
																											
																										</td></tr>
																									<? } ?>
																									
																									
																									
																									<? if ($order_total['code'] == 'shoputils_cumulative_discounts') { ?>
																										<tr><td colspan='6' style="text-align:right;">
																											<span class="help">Это накопительная скидка! <span style="color:#4ea24e;"> пересчитывается автоматически</span> <b>при пересохранении</b>!	<? if (isset($overload_scd_discount_percent)) { ?><span class="help"><b style="color:#cf4a61">Внимание! Покупатель достиг скидки в размере <? echo  $overload_scd_discount_percent; ?>! Однако пересчет ведется по зафиксированному проценту на момент оформления!</b></span><? } ?>
																										</td></tr>
																									<? } ?>
																									
																									<? if ($order_total['code'] == 'coupon') { ?>
																										<tr><td colspan='6' style="text-align:right;">
																											<span class="help">
																												
																												
																												<? if (!$order_has_coupon) { ?>
																													<span style="color:#cf4a61;"><b>Внимание! Такого ПРОМОКОДА НЕ СУЩЕСТВУЕТ!</span>&nbsp;&nbsp;
																													<? } else { ?>
																														
																														<? if ($order_has_coupon_is_active) { ?>
																															<span style="color:#4ea24e;"><b><? echo $order_has_coupon['code']; ?></b> - активен.</span>&nbsp;&nbsp;
																														<? } else { ?>
																															<span style="color:#cf4a61;"><b><? echo $order_has_coupon['code']; ?></b> - неактивен.</span>&nbsp;&nbsp;					
																														<? } ?>
																													<? } ?>
																													<span style="color:#4ea24e;">Пересчитывается автоматически</span> при пересохранении! В заказе можно использовать ТОЛЬКО ОДИН купон!</span>
																												</td></tr>
																											<? } ?>
																											
																											<? if ($order_total['code'] == 'reward') { ?>
																												<tr>
																													<td colspan='6' style="text-align:right;">
																														<?php if ($points_used_in_current_order_txt) { ?>
																															<span class='help' style="background-color:#FFF;color:#7F00FF;"><i class="fa fa-info-circle"></i> В текущем заказе зарезервировано <b><?php echo $points_used_in_current_order_txt; ?></b></span>
																														<?php } ?>
																														
																														<?php if ($points_customer_has_txt) { ?>
																															<span class='help' style="background-color:#FFF;color:#cf4a61;"><i class="fa fa-info-circle"></i> На счету покупателя сейчас активных <b><?php echo $points_customer_has_txt; ?></b>, для оплаты можно использовать не более <b><?php echo $points_can_be_used_txt; ?></b></span>
																														<?php } ?>
																														
																													</td>
																												</tr>
																											<? } ?>
																											
																											
																											<? if ($order_total['code'] == 'shipping') { ?>
																												<tr><td colspan='6' style="text-align:right;">
																													<span class="help" ><span style="color:#4ea24e;">Название изменяется автоматически!</span>&nbsp;<span style="color:#cf4a61">Стоимость доставки НЕ пересчитывается автоматически!</span>. Сделать: <span style="border-bottom:1px dashed black; cursor:pointer;" onclick="$('#text_shipping').val($(this).text()); $('#value_shipping').val('0');">Согласно тарифу</span>
																												</td></tr>
																											<? } ?>
																											<? if ($order_total['code'] == 'transfer_plus_prepayment') { ?>
																												<tr><td colspan='6' style="text-align:right;">
																													<span class="help" ><span style="color:#cf4a61">НЕ пересчитывается автоматически! Для изменения проще сделать 2 клика - удалить эту, добавить новую. <b>Внимание! Эта сумма будет показана покупателю для частичной оплаты!</b></span>.</span>
																												</td></tr>
																											<? } ?>
																											<?php $total_row++; ?>
																										<?php } ?>
																									<?php } else { ?>
																										<?php $total_row = 1; ?>
																										<tr id="total-row0">																			
																											<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
																										</tr>
																									</tbody>
																								</table>
																							<?php } ?>
																							<? if (!$closed) { ?>																
																								<table>
																									<tbody>
																										
																										<tr>
																											<td class="right" style="background-color:#cf4a61; padding:5px; color:#fff;  font-weight:700;">
																												Бонусная программа
																											</td>
																											<td style="padding-top:10px;">
																												<?php if (!$order_has_reward && !$order_has_coupon && !$order_has_zeropriceproduct && !$order_has_additional_offer) { ?>
																													<a id="add_custom_reward" class="button" style="width:350px; text-align:center; padding:8px;">Добавить оплату бонусами</a>
																													<input type="text" id="custom_reward" value="<?php echo $points_can_be_used; ?>" style="width:105px; font-size:17px;" /> бонусов
																													
																												<?php } ?>
																												
																												<? if ($order_has_coupon) { ?>
																													<div style="background-color:#FFF; padding:5px; color:#cf4a61;"><b><i class="fa fa-info-circle"></i> Нельзя использовать бонусы вместе с промокодом!</b></div>
																												<?php } ?>
																												
																												<? if ($order_has_additional_offer) { ?>
																													<div style="background-color:#FFF; padding:5px; color:#cf4a61;"><b><i class="fa fa-info-circle"></i> Нельзя использовать бонусы вместе спецпредложением, в котором мы дарим товар!</b></div>
																												<?php } ?>
																												
																												<? if ($order_has_zeropriceproduct) { ?>
																													<div style="background-color:#FFF; padding:5px; color:#cf4a61;"><b><i class="fa fa-info-circle"></i> В заказе содержится товар с нулевой ценой! Нельзя использовать бонусы вместе с подарком. Возможно, это необработанное спецпредложение.</b></div>
																												<?php } ?>
																												
																												
																												<?php if ($points_used_in_current_order_txt) { ?>
																													<div style="background-color:#FFF;color:#7F00FF; padding:5px;"><i class="fa fa-info-circle"></i> В текущем заказе зарезервировано <b><?php echo $points_used_in_current_order_txt; ?></b></div>
																												<?php } ?>
																												
																												<?php if ($points_customer_has_txt) { ?>
																													<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> На счету покупателя сейчас активных <b><?php echo $points_customer_has_txt; ?></b></div>
																													
																													<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> Для оплаты можно использовать не более <b><?php echo $points_can_be_used_txt; ?></b></div>
																													
																												<?php } else { ?>
																													<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> На счету покупателя нет активных бонусов</div>
																												<?php } ?>
																												
																											</td>
																										</tr>
																										
																										<?php if ($this->config->get('coupon_status')) { ?>
																											<tr>
																												<td class="right" style="background-color:#0054b3; padding:5px; color:#fff;  font-weight:700;">
																													Промокод
																												</td>
																												<td style="padding-top:10px;">																			
																													
																													<? if ($order_has_coupon) { ?><div style="background-color:#FFF; padding:5px; color:#cf4a61;"><i class="fa fa-info-circle"></i> В этом заказе уже использован промокод!</div><?php } ?>
																													
																													<span style="<? if ($order_has_coupon) { ?>display:none;<? } ?>">
																														<a id="add_custom_coupon"  class="button" style="width:250px; padding:8px; text-align:center;">Добавить промокод: </a>
																														<input type="text" id="custom_coupon" value="" style="width:110px; font-size:17px;" />
																													</span>
																													
																												</td>
																											</tr>
																										<?php } ?>
																										
																										
																										<tr>
																											<td class="right" style="background-color:#ff7815; padding:5px; color:#fff;  font-weight:700;">
																												Скидки %
																											</td>
																											<td style="padding-top:10px;">
																												<a id="add_custom_discount3"  class="button" style="width:60px; text-align:center; padding:8px;">3%</a>&nbsp;
																												<a id="add_custom_discount5"  class="button" style="width:60px; text-align:center; padding:8px;">5%</a>&nbsp;
																												<a id="add_custom_discount7"  class="button" style="width:60px; text-align:center; padding:8px;">7%</a>&nbsp;
																												<a id="add_custom_discount10"  class="button" style="width:60px; text-align:center; padding:8px;">10%</a>
																												
																												<span>
																													<a id="add_custom_discount"  class="button" style="width:250px; text-align:center; padding:8px;">Указать процент: </a>
																													<input type="number" id="custom_discount" value="5" style="width:55px; font-size:17px;" />%
																													&nbsp;&nbsp;
																												</span>	
																												
																												<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> Добавить в случае, если необходимо сделать ручную скидку. В случае использования бонусов, либо промокодов - суммы пересчитаются</div>
																											</td>
																										</tr>
																										
																										<tr>
																											<td class="right" style="background-color:#ec74ae; padding:5px; color:#fff; font-weight:700;">
																												Скидки <?php echo $currency_code; ?>
																											</td>
																											<td style="padding-top:10px;">						
																												<span>
																													<a id="add_custom_discount2"  class="button" style="width:250px; text-align:center; padding:8px;">Указать сумму: </a>
																													<input type="number" id="custom_discount2" value="100" style="width:200px; font-size:17px;" /><?php echo $currency_code; ?>
																													&nbsp;&nbsp;
																												</span>	
																												
																												<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> Добавить в случае, если необходимо сделать ручную целочисленную скидку. В случае использования бонусов, либо промокодов - суммы пересчитаются</div>
																											</td>
																										</tr>
																										
																										<tr>
																											<td class="right" style="background-color:#4ea24e; padding:5px; color:#fff;  font-weight:700;">
																												Предоплаты
																											</td>
																											<td style="padding-top:10px;">
																												<a id="add_prepay5"  class="button" style="width:60px; text-align:center; padding:8px;">5%</a>&nbsp;
																												<a id="add_prepay10"  class="button" style="width:60px; text-align:center; padding:8px;">10%</a>&nbsp;
																												<a id="add_prepay20"  class="button" style="width:60px; text-align:center; padding:8px;">20%</a>
																												
																												<a id="add_custom_prepay"  class="button" style="width:250px; text-align:center; padding:8px;">Указать процент:</a>
																												<input type="number" id="custom_prepay" value="5" style="width:55px; font-size:17px;" />%
																												
																												<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> Добавить в случае, если покупатель хочет внести частичную предоплату</div>
																											</td>
																										</tr>
																										
																										
																										
																										<tr>
																											<td class="right" style="background-color:#7F00FF; padding:5px; color:#fff;  font-weight:700;">
																												Произвольное поле
																											</td>
																											<td style="padding-top:10px;">
																												<a id="add_custom" class="button" style="width:350px; text-align:center; padding:8px;">Добавить произвольный итог</a>
																												
																												<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> Использовать с осторожностью, итоги будут пересчитаны не во всех случаях</div>
																											</td>
																											
																										</tr>
																										
																										
																										
																										<tr>
																											<td class="right" style="background-color:grey; padding:5px; color:#fff;  font-weight:700;">
																												На случай сбоя итогов
																											</td>
																											<td style="padding-top:10px;">
																												<a id="add_delivery" class="button" style="width:160px; text-align:center;padding:8px;">Доставка ('shipping')</a>&nbsp;
																												<a id="add_sub_total" class="button" style="width:160px; text-align:center;padding:8px;">Сумма ('sub_total')</a>&nbsp;
																												<a id="add_total_total" class="button" style="width:220px; text-align:center;padding:8px;">Конечный итог ('total')</a>&nbsp;
																												
																												<div style="background-color:#FFF; padding:5px; color:#7F00FF;"><i class="fa fa-info-circle"></i> Просто добавить с нулями, и пересохранить - итоги пересчитаются автоматически</div>
																											</td>
																										</tr>	
																									</tbody>
																								</table>
																							<? } ?>
																							
																							<? if (!$closed) { ?>	
																								<table>
																									<tbody>
																										<tr>
																											<td style="padding-top:15px;" align="right"><a onclick="var value = $('#part_num_real').val(); $('input#part_num').val(value); $('#form').submit();" class="button save_button">Сохранить</a>
																											</td>
																										</tr>
																									</tbody>
																								</table>
																							<? } ?>
																							
																							<? require_once(dirname(__FILE__) . '/order_form.js.totals.tpl'); ?>
																							
																							<table class="list" style="display:none;">
																								<tr>
																								</tr>
																								<tr>
																									<td class="left"><?php echo $entry_coupon; ?></td>
																									<td class="left"><input type="text" name="coupon" value="" /></td>
																								</tr>
																								<tr>
																									<td class="left"><?php echo $entry_voucher; ?></td>
																									<td class="left"><input type="text" name="voucher" value="" /></td>
																								</tr>
																								<tr>
																									<td class="left"><?php echo $entry_reward; ?></td>
																									<td class="left"><input type="text" name="reward" value="" /></td>
																								</tr>
																							</tbody>
																						</table>
																					</div>
																					<div class="clr"></div>
																					<div onclick="$('#tab-voucher').toggle();" class="blue_heading">Показать / скрыть управление подарочными сертификатами</div>
																					<div id="tab-voucher" style="display:none">
																						<table class="list">
																							<thead>
																								<tr>
																									<td></td>
																									<td class="left"><?php echo $column_product; ?></td>
																									<td class="left"><?php echo $column_model; ?></td>
																									<td class="right"><?php echo $column_quantity; ?></td>
																									<td class="right"><?php echo $column_price; ?></td>
																									<td class="right"><?php echo $column_total; ?></td>
																								</tr>
																							</thead>
																							<tbody id="voucher">
																								<?php $voucher_row = 0; ?>
																								<?php if ($order_vouchers) { ?>
																									<?php foreach ($order_vouchers as $order_voucher) { ?>
																										<tr id="voucher-row<?php echo $voucher_row; ?>">
																											<td class="center" style="width: 3px;"><img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$('#voucher-row<?php echo $voucher_row; ?>').remove(); $('#main_save').trigger('click');" /></td>
																											<td class="left"><?php echo $order_voucher['description']; ?>
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][order_voucher_id]" value="<?php echo $order_voucher['order_voucher_id']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][voucher_id]" value="<?php echo $order_voucher['voucher_id']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][description]" value="<?php echo $order_voucher['description']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][code]" value="<?php echo $order_voucher['code']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][from_name]" value="<?php echo $order_voucher['from_name']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][from_email]" value="<?php echo $order_voucher['from_email']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][to_name]" value="<?php echo $order_voucher['to_name']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][to_email]" value="<?php echo $order_voucher['to_email']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][voucher_theme_id]" value="<?php echo $order_voucher['voucher_theme_id']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][message]" value="<?php echo $order_voucher['message']; ?>" />
																											<input type="hidden" name="order_voucher[<?php echo $voucher_row; ?>][amount]" value="<?php echo $order_voucher['amount']; ?>" /></td>
																											<td class="left"></td>
																											<td class="right">1</td>
																											<td class="right"><?php echo $order_voucher['amount']; ?></td>
																											<td class="right"><?php echo $order_voucher['amount']; ?></td>
																										</tr>
																										<?php $voucher_row++; ?>
																									<?php } ?>
																								<?php } else { ?>
																									<tr>
																										<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
																									</tr>
																								<?php } ?>
																							</tbody>
																						</table>
																						<table class="list">
																							<thead>
																								<tr>
																									<td colspan="2" class="left"><?php echo $text_voucher; ?></td>
																								</tr>
																							</thead>
																							<tbody>
																								<tr>
																									<td class="left"> <?php echo $entry_to_name; ?></td>
																									<td class="left"><input type="text" name="to_name" value="" /></td>
																								</tr>
																								<tr>
																									<td class="left"> <?php echo $entry_to_email; ?></td>
																									<td class="left"><input type="text" name="to_email" value="" /></td>
																								</tr>
																								<tr>
																									<td class="left"> <?php echo $entry_from_name; ?></td>
																									<td class="left"><input type="text" name="from_name" value="" /></td>
																								</tr>
																								<tr>
																									<td class="left"> <?php echo $entry_from_email; ?></td>
																									<td class="left"><input type="text" name="from_email" value="" /></td>
																								</tr>
																								<tr>
																									<td class="left"> <?php echo $entry_theme; ?></td>
																									<td class="left"><select name="voucher_theme_id">
																										<?php foreach ($voucher_themes as $voucher_theme) { ?>
																											<option value="<?php echo $voucher_theme['voucher_theme_id']; ?>"><?php echo addslashes($voucher_theme['name']); ?></option>
																										<?php } ?>
																									</select></td>
																								</tr>
																								<tr>
																									<td class="left"><?php echo $entry_message; ?></td>
																									<td class="left"><textarea name="message" cols="40" rows="5"></textarea></td>
																								</tr>
																								<tr>
																									<td class="left"> <?php echo $entry_amount; ?></td>
																									<td class="left"><input type="text" name="amount" value="25.00" size="5" /></td>
																								</tr>
																							</tbody>
																							<tfoot>
																								<tr>
																									<td class="left">&nbsp;</td>
																									<td class="left"><a id="button-voucher" class="button"><?php echo $button_add_voucher; ?></a></td>
																								</tr>
																							</tfoot>
																						</table>
																					</div>
																				</form>
																				
																			</div>
																		</div>
																		<? if ($is_buyer) { ?>
																			<? require_once(dirname(__FILE__) . '/order_form.js.buyer.tpl'); ?>											
																		<? } ?>
																		<? require_once(dirname(__FILE__) . '/order_form.js.bottom.tpl'); ?>		
																		
																		
																		<?php echo $footer; ?>																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																		