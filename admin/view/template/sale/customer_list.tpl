<?php echo $header; ?>
<style>
	.swal-wide{
    width:650px !important;
	}
	.nbt_change{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	.nbt_change.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
	.rja_change{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	.rja_change.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
</style>
<div id="content">
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
			<h1><img src="view/image/customer.png" alt="" /><?php echo $heading_title; ?></h1>
			<div class="buttons">						
			<? /*	<a href="<?=$export_to_csv; ?>" target="_blank" class="button"><i class="fa fa-envelope-open"></i> CSV MailWizz</a> */ ?>
				<a href="<?=$export_to_csv_gc; ?>" target="_blank" class="button"><i class="fa fa-google"></i> Google Remarketing CSV</a>
				<a href="<?=$export_to_csv_viber; ?>" target="_blank" class="button"><i class="fa fa-vimeo-square" aria-hidden="true"></i> Viber CSV (только телефон)</a>
			<? /*	<a href="index.php?route=module/excelport&token=<?php echo $this->request->get['token']; ?>" class="button">Export / Import</a>		*/ ?>		
				<a onclick="$('form').prop('action', '<?php echo $approve; ?>'); $('form').submit();" class="button"><?php echo $button_approve; ?></a>
				<a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
				<a onclick="$('form').prop('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><?php echo $button_delete; ?></a>
			</div>
			
		</div>
		<div style="clear:both;"></div>
		<div class="content">
			<div style="padding-bottom:10px;">
				<a href="<?=$filter_no_verified_address; ?>" class="button"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;Неверифицированные адреса</a>
				<a href="<?=$filter_birthday_month; ?>" class="button"><i class="fa fa-birthday-cake" aria-hidden="true"></i>&nbsp;ДР в течении месяца</a>
				<a href="<?=$filter_birthday_week; ?>" class="button"><i class="fa fa-birthday-cake" aria-hidden="true"></i>&nbsp;ДР в течении недели</a>
				<a href="<?=$filter_no_discount_url; ?>" class="button"><i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;Проблемы с дисконт. картой</a>
				<a href="<?=$filter_no_passport_url; ?>" class="button"><i class="fa fa-id-card-o" aria-hidden="true"></i>&nbsp;Проблемы с пасп. данными</a>
			</div>
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 100%;">
					<tbody>
						<tr class="filter f_top">
							<td>
								<p>Имя / карта</p>
								<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /><br />
							</td>
							<td><p>Пол:</p>
								<select name="filter_gender" style="height: 34px;">
									<?php if ((int)$filter_gender === 0) { ?>
										<option value="*" selected="selected"></option>
										<option value="1">Мужчина</option>
										<option value="2">Женщина</option>
										<? } elseif ((int)$filter_gender === 1) { ?>
										<option value="*"></option>
										<option value="1" selected="selected">Мужчина</option>
										<option value="2">Женщина</option>
										<? } elseif ((int)$filter_gender === 2) { ?>
										<option value="*"></option>
										<option value="1">Мужчина</option>
										<option value="2"  selected="selected">Женщина</option>
										<? } else { ?>
										<option value="*"></option>
										<option value="1">Мужчина</option>
										<option value="2">Женщина</option>
									<? } ?>
								</select>
							</td>
							<td>
								<p> День рожд.</p>
								<input type="text" id="date_bf" name="filter_birthday_from" value="<?php echo $filter_birthday_from; ?>" style="width: 45px;" />
								<? if ($filter_birthday_from) { ?>&rarr;<span style="font-size:9px;"><? $_a =  explode('-',$filter_birthday_from); ?><? echo $_a[1]; ?>.<? echo $_a[0]; ?></span><? } ?>              
								<input type="text" id="date_bt" name="filter_birthday_to" value="<?php echo $filter_birthday_to; ?>" style="width: 45px;" />
								<? if ($filter_birthday_to) { ?>&larr;<span style="font-size:9px;"><? $_a =  explode('-',$filter_birthday_to); ?><? echo $_a[1]; ?>.<? echo $_a[0]; ?></span><? } ?>
								
								<div style="margin-top:3px;"><input id="filter_no_birthday" class="checkbox" type="checkbox" name="filter_no_birthday" value="1" <?php if ($filter_no_birthday) print 'checked'; ?> />
								<label for="filter_no_birthday">Нет ДР</label></div>
							</td>
							
							<td colspan="2">
								<p>Телефон</p>
								<input type="text" name="filter_phone" value="<?php echo $filter_phone; ?>" />
							</td>
							<td>
								<p>Вкл</p>
								<select name="filter_status">
									<option value="*"></option>
									<?php if ($filter_status) { ?>
										<option value="1" selected="selected"><?php echo $text_yes; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
									<?php } ?>
									<?php if (!is_null($filter_status) && !$filter_status) { ?>
										<option value="0" selected="selected"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="0"><?php echo $text_no; ?></option>
									<?php } ?>
								</select>	
							</td>
							<td>
								<p>Акт</p>
								<select name="filter_approved">
									<option value="*"></option>
									<?php if ($filter_approved) { ?>
										<option value="1" selected="selected"><?php echo $text_yes; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
									<?php } ?>
									<?php if (!is_null($filter_approved) && !$filter_approved) { ?>
										<option value="0" selected="selected"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="0"><?php echo $text_no; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<p>Группа</p>
								<select name="filter_customer_group_id" style="width:75px;">
									<option value="*"></option>
									<?php foreach ($customer_groups as $customer_group) { ?>
										<?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								
							</td>
							<td rowspan="3" colspan="2">
								<p>Список сегментов</p>
								<div class="scrollbox" style="max-width:500px; height:200px;">
									<?php if ($segments) { ?>
										<?php $filter_segment_id = $filter_segment_id?explode(',',html_entity_decode($filter_segment_id)):array(); ?>
										<?php $class = 'odd'; ?>																						
										<?php foreach ($segments as $segment) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div> 
												<input class="checkbox filter_segment_id" id="segment_<?php echo $segment['segment_id']; ?>" type="checkbox" name="filter_segment_id[]" value="<?php echo $segment['segment_id']; ?>" <? if (in_array($segment['segment_id'], $filter_segment_id)) { ?> checked="checked" <? } ?> />
												<label for="segment_<?php echo $segment['segment_id']; ?>">
													<? if ($segment['bg_color']) { ?>
														<span class="status_color" style="display:inline-block;padding:5px;background:#<?php echo $segment['bg_color']; ?>">
															<? if ($segment['fa_icon']) { ?>
																<i class="fa <? echo $segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
															<? } ?>
															<?php echo $segment['name']; ?>
														</span>
														<? } else { ?>
														<? if ($segment['fa_icon']) { ?>
															<i class="fa <? echo $segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
														<? } ?>
														<?php echo $segment['name']; ?>
													<? } ?>													
												</label>
											</div>
										<? } ?>
									<? } ?>
									
								</div>
								<a class="select_all" onclick="$(this).parent().find(':checkbox').prop('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').prop('checked', false);">Снять выделение</a>
								&nbsp;&nbsp;&nbsp;
								<input id="filter_segment_intersection" class="checkbox" type="checkbox" name="filter_segment_intersection" value="1" <?php if ($filter_segment_intersection) print 'checked'; ?> />
								<label for="filter_segment_intersection">Пересечение</label>
							</td>
						</tr>
						<tr class="filter f_top">
							<td colspan="">
								<input id="filter_has_discount" class="checkbox" type="checkbox" name="filter_has_discount" value="1" <?php if ($filter_has_discount) print 'checked'; ?> />
								<label for="filter_has_discount">Есть карта</label>
								<br/>
								<br/>
								<input id="mail_open" class="checkbox" type="checkbox" name="mail_opened" value="1" <?php if ($filter_mail_opened) print 'checked'; ?>>
								<label for="mail_open"><i class="fa fa-envelope-open"></i> открыто</label> 
							</td>	
							<td colspan="">  
								<input id="filter_no_discount" class="checkbox" type="checkbox" name="filter_no_discount" value="1" <?php if ($filter_no_discount) print 'checked'; ?> />
								<label for="filter_no_discount">Нет карты</label>
								<br/>
								<br/>  
								<input id="mail_check" class="checkbox" type="checkbox" name="mail_checked" value="1" <?php if ($filter_mail_checked) print 'checked'; ?>>
								<label for="mail_check">был клик</label>
							</td>	
							<td colspan="">
								<p><i class="fa fa-calendar"></i>&#160;Дата добавления</p>
								<label>
									<input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" style="width:50px;" id="date" />
								</label>
							</td>
							<td>
								<p>E-Mail</p>
								<input type="text" name="filter_email" value="<?php echo $filter_email; ?>" />                
							</td>
							<td colspan="2">
								<p>Страна</p>
								<select name="filter_country_id">
									<option value="*"></option>
									<?php foreach ($countries as $country) { ?>
										<?php if ($country['country_id'] == $filter_country_id) { ?>
											<option value="<?php echo $country['country_id'] ?>" selected="selected"><?php echo $country['name'] ?></option>
											<?php } else { ?>
											<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td colspan="2">
								<p>IP / SRC / Date</p>
								<input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>"/><br />								
							</td>						
							
						</tr>
						<tr class="filter f_top">
							<td colspan="">
								<p><i class="fa fa-envelope-open"></i>&nbsp;&nbsp;
								Статус письма:</p>
								<select name="filter_mail_status" id="">
									<option value="*">-Выбрать статус-</option>
									<?php foreach ($mail_status as $v): ?>
									<option value="<?=$v ?>" <?php if ($filter_mail_status == $v) print 'selected'; ?>><?=$v ?></option>
									<?php endforeach; ?>
								</select>&nbsp;&nbsp;
							</td>
							<td>
							<? /*
								<p>Рассылки:</p>
								<select name="campaing_id" id="" style="width:175px;">
									<option value="*">- Выбери рассылку -</option>
									<?php foreach ($mail_campaings as $m): ?>
									<option value="<?=$m['id'] ?>" <?php if ($filter_mail_campaings == $m['id']) print 'selected'; ?>><?=$m['name'] ? $m['name'] : $m['id'] ?></option>
									<?php endforeach; ?>
								</select>
							*/ ?>
							</td>
							<td>
								<p><i class="fa fa-address-card"></i>&nbsp;&nbsp;
								Заказов всего</p>
								<input type="text" name="filter_order_count" value="<? echo $filter_order_count ?>" />
							</td>
							<td>
								<p>Выполнено</p>
								<input type="text" name="filter_order_good_count" width="100px" value="<? echo $filter_order_good_count ?>" />&nbsp;&nbsp;
							</td>
							<td colspan="2">
								<p>Σ покупок</p>
								<input type="text" name="filter_total_sum" value="<? echo $filter_total_sum ?>" />&nbsp;&nbsp;
							</td>
							<td colspan="2">
								<p>Сред.чек</p>
								<input type="text" name="filter_avg_cheque" value="<? echo $filter_avg_cheque ?>" />&nbsp;&nbsp;								
							</td>
						</tr>
						<tr class="filter f_top">
							<td>
								<input id="push_sign" class="checkbox" type="checkbox" name="push_sign" value="1" <?php if ($filter_push_signed) print 'checked'; ?>>
								<label for="push_sign"><i class="fa fa-bell" aria-hidden="true"></i>&nbsp;Push подписка</label>
							</td>
							<td colspan="2">
								<p><i class="fa fa-briefcase"></i>&nbsp;&nbsp;
								Интересы: бренд</p>
								<select name="filter_interest_brand">
									<option value="*">- Выбери бренд -</option>
									<? foreach ($manufacturers as $manufacturer) { ?>
										<option value="<? echo $manufacturer['manufacturer_id'] ?>"><? echo $manufacturer['name']; ?></option>
									<? } ?>
								</select>
							</td>
							<td colspan="2">
								<p>Категория</p>
								<select name="filter_interest_category">
									<option value="*">- Выбери категорию -</option>
								</select>
							</td>
							<td>
								
								<p>Источник:</p>
								<select name="filter_source">
									<option value="*">- Выбери источник -</option>
									<?php foreach ($sources as $source) { ?>
										<?php if ($source['source'] == $filter_source) { ?>
											<option value="<?php echo $source['source'] ?>" selected="selected"><?php echo $source['source']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $source['source']; ?>"><?php echo $source['source'] ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								
								
							</td>
							<td colspan="2">
								<div>
									<input id="nbt_customer" class="checkbox" type="checkbox" name="nbt_customer" value="1" <?php if ($filter_nbt_customer) print 'checked'; ?>>
									<label for="nbt_customer"><i class="fa fa-deaf" aria-hidden="true"></i>&nbsp;Только недозвон</label>
								</div>
								<div style="margin-top:5px;">
									<input id="nbt_customer_exclude" class="checkbox" type="checkbox" name="nbt_customer_exclude" value="1" <?php if ($filter_nbt_customer_exclude) print 'checked'; ?>>
									<label for="nbt_customer_exclude"><i class="fa fa-deaf" aria-hidden="true"></i>&nbsp;Исключить недозвон</label>
								</div>								
							</td>
							
							<td colspan="">
								
								
							</td>
							
							<td>
							</td>
						</tr>
						<tr class="filter f_top">
							<td>
								<p><i class="fa fa-cart-arrow-down"></i>Посл. усп. звонок до</p>
								<input type="text" class="date" name="filter_last_call" value="<? echo $filter_last_call; ?>" />
							</td>
							
							<td>
								<p><i class="fa fa-cart-arrow-down"></i>Дата первого от</p>
								<input type="text" class="date-new" style="width:90px;" name="order_first_date_from" value="<? echo $order_first_date_from; ?>" />
								
							</td>
							
							<td>
								<p><i class="fa fa-phone-square" aria-hidden="true"></i>Дата первого до</p>
								<input type="text" class="date-new" style="width:90px;" name="order_first_date_to" value="<? echo $order_first_date_to; ?>" />
							</td>
							
							<td colspan="6" align="right">
								<p>	&#160;</p>
							<a onclick="filter();" class="button"><?php echo $button_filter; ?></a>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="filter_bord"></div>
				<div class="pagination" style="margin-bottom:5px;"><?php echo $pagination; ?></div>					
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Имя / карта</a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>">Имя / карта</a>
							<?php } ?></td>
							<td>CRM</td>
							<td><i class="fa fa-birthday-cake" aria-hidden="true"></i>&nbsp;День рожд.</td>
							<td><i class="fa fa-envelope-o"></i>&nbsp;Маркет. рассылки</td>
							<td class="left"><i class="fa fa-envelope-o"></i>&nbsp;<?php if ($sort == 'c.email') { ?>
								<a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
							<?php } ?>
							</td>
							<td>Телефон</td>
							<td class="left"><?php if ($sort == 'c.status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Вкл</a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>">Вкл</a>
							<?php } ?> /
							<?php if ($sort == 'c.approved') { ?>
								<a href="<?php echo $sort_approved; ?>" class="<?php echo strtolower($order); ?>">Акт.</a>
								<?php } else { ?>
								<a href="<?php echo $sort_approved; ?>">Акт</a>
							<?php } ?> / 
							<?php if ($sort == 'customer_group') { ?>
								<a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>">Группа</a>
								<?php } else { ?>
								<a href="<?php echo $sort_customer_group; ?>">Группа</a>
							<?php } ?>
							</td>
							<td class="left">Страна</td>
							<td class="left"><?php if ($sort == 'c.ip') { ?>
								<a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
								<?php } ?> / SRC / <?php if ($sort == 'c.date_added') { ?>
								<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Date</a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_added; ?>">Date</a>
							<?php } ?></td>							
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						
						<?php if ($customers) { ?>
							<?php foreach ($customers as $customer) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($customer['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
									<?php } ?>
									</td>
									<td class="left">
										<? if ($customer['gender'] == 0) { ?>
											<i class='fa fa-transgender' style='color:green; font-size:16px;'></i>&nbsp;	
											<? } elseif ($customer['gender'] == 1) { ?>
											<i class='fa fa-male' style='color:#2c82b8; font-size:16px;'></i>&nbsp;	
											<? } elseif ($customer['gender'] == 2) { ?>
											<i class='fa fa-female' style='color:#d9534f; font-size:16px;'></i>&nbsp;	
										<? } ?>
										
										<span style="font-weight:400; font-size:20px;">
											<? if ($customer['is_mudak']) { ?>
												<i class='fa fa-ambulance' style='color:red; font-size:16px;'></i>&nbsp;
											<? } ?>
										<a href="<? echo $customer['customer_href']; ?>" style="font-size:20px;" target="_blank"><?php echo $customer['name']; ?></a></span>
										&nbsp;<span class="add2ticket" data-query="object=customer&object_value=<? echo $customer['customer_id']; ?>"></span>
										<div style="display:inline-block; background-color:#ccc; padding:3px; color:#FFF; margin-left:5px;"><? echo $customer['customer_id']; ?></div>
										
										<? if ($customer['discount_card']) { ?>
											<div style="margin-top:3px;">
												<div style="display:inline-block; padding:3px; background:#d4ffaa; border:1px dotted #CCC; font-size:12px;  margin-bottom:7px;">
													<i class="fa fa-credit-card-alt" aria-hidden="true"></i> Карта #<? echo $customer['discount_card']; ?><? /*, скидка <? echo $customer['discount_percent']; ?>% */ ?>		
												</div>		
																								
												<div style="display:inline-block; padding:3px; font-size:12px;">
													<? if ($customer['csi_reject']) { ?>
														<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:12px; background-color:#F96E64; display:inline-block; color:#FFF">
															<i class="fa fa-user"></i> CSI: отказ
														</div>
													<? } ?>
													<? if ($customer['csi_average'] > 0) { ?>
														<div style="text-align:right;  display:inline-block;"><div style="display:inline-block;" class="rateYo" data-rateyo-rating="<? echo $customer['csi_average']; ?>"></div></div>
														<? } elseif (!$customer['csi_reject']) { ?>
														<div style="margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:12px; background-color:#F96E64; display:inline-block; color:#FFF">
															CSI: не задан
														</div>
													<? } ?>
													
													<? if ($customer['orders_for_csi']) { ?>
														<? foreach ($customer['orders_for_csi'] as $ofc) { ?>
															<div style="padding:5px 5px; margin-left:20px; display:inline-block; background-color:#<? echo $ofc['order_status']['status_bg_color']; ?>; color:#<? echo $ofc['order_status']['status_txt_color']; ?>; margin-right:10px;">
																<i class="fa <? echo $ofc['order_status']['status_fa_icon']; ?>"></i> <? echo $ofc['order_id']; ?>, <? echo $ofc['order_status']['name'] ?> <? echo $ofc['last_modified'] ?>
															</div>
														<? } ?>	
													<? } ?>
												</div>
											</div>
										<? } ?>
										
										<div>
											<? if ($customer['segments']) { ?>
												<? foreach ($customer['segments'] as $_segment) { ?>
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
										</div>
									</td>
									<td>
										<span class="load_order_history" data-customer-id="<?php echo $customer['customer_id']; ?>" style="cursor:pointer; border-bottom:1px dashed grey;">
									Заказов <? echo $customer['order_good_count']; ?> / <? echo $customer['order_count']; ?>
										
									</span>

									<? if ($customer['order_count']) { ?>
										<br /><span style="padding:2px; background:#ecf3e6; display:inline-block; margin-top:2px;">Σ <? echo (int)$customer['total_cheque']; ?>&nbsp;<? echo $customer['currency']; ?><? } ?></span><? if ($customer['order_count']) { ?><br /><span style="padding:2px; background:#e6e9f3; display:inline-block; margin-top:2px;">СР <? echo (int)$customer['avg_cheque']; ?>&nbsp;<? echo $customer['currency']; ?><? } ?>
										
										</span>								
									</td>
									<td class="left"><?php echo !in_array($customer['birthday'], array('30.11.-0001'))?$customer['birthday']:''; ?></td>
									<td class="left" style="vertical-align:top;">
										<? if ($customer['actiontemplate_history']) { ?>
											<div style="margin-top:3px;">
												<? foreach ($customer['actiontemplate_history'] as $acth) { ?>
													<div style="font-size:10px; padding:2px; display:inline-block; margin-right:3px; margin-bottom:3px; background-color:#ccfcfc;">
														<img src="<? echo $acth['image'] ?>" width="30px" height="30px" alt="<? echo $acth['title']; ?>" style="border-radius:50%; float:left;" />&nbsp;
														<span style="display:inline-block;height:30px;">
															<? echo $acth['title']; ?><br />
														<i class="fa fa-envelope-o"></i>&nbsp; <? echo $acth['date_sent']; ?></span></div>
												<? } ?>				
											</div>
										<? } ?>
									</td>
									<td class="left" style="vertical-align:top;">
										<div style="<?php if ($customer['email_bad']) { ?>color:#ff5656<?php } else { ?>color:#00AD07<? } ?>">
											<? echo $customer['email']; ?>
											<?php if ($customer['email_bad']) { ?><i class="fa fa-exclamation-triangle" style="color:#ff5656"></i><?php } ?>
											<?php if (!$customer['email_bad']) { ?><i class="fa fa-check-circle" style="color:#00AD07"></i><?php } ?>
										</div>
										
										<div>
											<span style="display:inline-block; padding:3px 4px; font-size:10px; <?php if ($customer['email_bad']) { ?>background:#ff5656; color:#FFF<?php } else { ?>background:#00AD07; color:#FFF<?php } ?>">
												<? echo $customer['mail_status']; ?>
											</span>
											<span style="font-size:10px;">
												Открытий: <? echo $customer['mail_opened']; ?>
											</span>
											<span style="font-size:10px;">
												Кликов: <? echo $customer['mail_clicked']; ?>
											</span>
										&nbsp;&nbsp;<i class="fa fa-bell" aria-hidden="true" style="color: <? if ($customer['has_push']) { ?>#4ea24e;<? } else { ?>#cf4a61;<? } ?>"></i></div>										
									</td>
									
									<td>
										<? if ($customer['phone']) { ?>
											<div><?=$customer['phone'] ?><span class='click2call' data-phone="<?=$customer['phone'] ?>"></span></div>
										<? } ?>
										<? if ($customer['fax']) { ?>
											<div><?=$customer['fax'] ?><span class='click2call' data-phone="<?=$customer['fax'] ?>"></span></div>
										<? } ?>
										
										
										<? if ($customer['total_calls']) { ?>
											<div><span class="load_call_history" data-customer-id="<?php echo $customer['customer_id']; ?>" style="cursor:pointer; border-bottom:1px dashed grey;">
											Звонков: <? echo $customer['total_calls']; ?></span></div>
										<? } ?>
										
										<? if ($customer['last_my_call']) { ?>
											<div style="color:white; margin-top:3px; padding:3px; <? if ($customer['last_my_call']['length']) { ?>background:#4ea24e;<? } else { ?>background:#F96E64;<? } ?> font-size:10px;">Посл. мой <? echo date('d.m.Y', strtotime($customer['last_my_call']['date_end'])); ?>
											<br /><? echo $customer['last_my_call']['length'];  ?> сек.</div>
										<? } ?>
										
										<? if ($customer['phone'] || $customer['fax']) { ?>
											<div style="margin-top:8px;">
												<? if ($customer['nbt_customer']) { ?>
													<span class="nbt_change is_nbt" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-deaf" aria-hidden="true"></i> НБТ</span>
													<? } else { ?>
													<span class="nbt_change" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-deaf" aria-hidden="true"></i> НБТ</span>
												<? } ?>
												<? if ($customer['rja_customer']) { ?>
													<span class="rja_change is_nbt" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Отк. адр.</span>
													<? } else { ?>
													<span class="rja_change" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Отк. адр.</span>
												<? } ?>
											</div>
										<? } ?>
									</td>
									
									<td class="left"><?php echo $customer['customer_group']; ?><br /><?php echo $customer['status']; ?> / <?php echo $customer['approved']; ?></td>
									<td class="left">
										<? if ($customer['country']) { ?><img src="<? echo HTTPS_IMAGE ?>flags/<? echo mb_strtolower($customer['country']) ?>.png" /><? } ?>&nbsp;&nbsp;<span style="font-size:10px;"><?php echo $customer['city']; ?></span></td>
									<td class="left" style="font-size:10px;"><?php echo $customer['ip']; ?><br /><?php echo $customer['source']; ?><br /><?php echo $customer['date_added']; ?></td>							
									<td class="right">
										<a class="button" <? if ($customer['printed2912']) { ?>style="color:#FF0000; border-color:#FF0000;"<? } ?> href="<? echo $customer['letter_href']; ?>" target="_blank"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
										<a class="button" onclick=" swal({title: 'Ссылка для входа без пароля',text: '<? echo $customer['preauth_url']; ?>', html: true,  type: 'info',  customClass: 'swal-wide',  showCancelButton: true,  showConfirmButton:false });"><i class="fa fa-link" aria-hidden="true"></i></a>										
										<a class="button go_to_store" data-customer-id="<?php echo $customer['customer_id']; ?>" data-store-id="<?php echo $customer['store_id']; ?>">
											<i style="font-size:16px; cursor:pointer;" class="fa fa-sign-in"></i>
										</a>
										<?php foreach ($customer['action'] as $action) { ?>											
											<a class='button' href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
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
</div>
<div id="mailpreview"></div>
<script>
	$('.load_order_history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order&ajax=1&limit=200&token=<?php echo $token; ?>&filter_customer=' +  $(this).attr('data-customer-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
	$('.load_call_history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/customer/callshistory&ajax=1&limit=200&token=<?php echo $token; ?>&customer_id=' +  $(this).attr('data-customer-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
	
	$(document).ready(function(){
		$(".rja_change").click(function(){
			var _el = $(this);
			var _cid = $(this).attr('data-customer-id');
			$.ajax({
				url : 'index.php?route=sale/customer/setCustomerRJAAjax&customer_id=' + _cid + '&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(i){
					if (i == 1){
						_el.addClass('is_nbt');
						} else {
						_el.removeClass('is_nbt');
					}					
				},
				error : function(e){
					console.log(e);
				}
			});
		});
	});
	
	$(document).ready(function(){
		$(".nbt_change").click(function(){
			var _el = $(this);
			var _cid = $(this).attr('data-customer-id');
			$.ajax({
				url : 'index.php?route=sale/customer/setCustomerNBTAjax&customer_id=' + _cid + '&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(i){
					if (i == 1){
						_el.addClass('is_nbt');
						} else {
						_el.removeClass('is_nbt');
					}					
				},
				error : function(e){
					console.log(e);
				}
			});
		});
	});
</script>
<script type="text/javascript"><!--
	$(document).ready(function(){
		$('.go_to_store').on('click', function(){
			var store_id = $(this).attr('data-store-id');
			var customer_id = $(this).attr('data-customer-id');
			
			swal({ title: "Перейти в магазин?", text: "В личный кабинет покупателя", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, перейти!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
				window.open('index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id='+customer_id+'&store_id=' + store_id)
			});
			
		});		
	});
	
	function filter() {
		url = 'index.php?route=sale/customer&token=<?php echo $token; ?>';
		
		var filter_name = $('input[name=\'filter_name\']').prop('value');
		
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}
		
		var filter_email = $('input[name=\'filter_email\']').prop('value');
		
		if (filter_email) {
			url += '&filter_email=' + encodeURIComponent(filter_email);
		}
		
		var filter_phone = $('input[name=\'filter_phone\']').prop('value');
		
		if (filter_phone) {
			url += '&filter_phone=' + encodeURIComponent(filter_phone);
		}
		
		
		var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').children("option:selected").val();
		
		if (filter_customer_group_id != '*') {
			url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
		}
		
		var filter_status = $('select[name=\'filter_status\']').children("option:selected").val();
		
		if (filter_status != '*') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}
		
		var filter_gender = $('select[name=\'filter_gender\']').children("option:selected").val();
		
		if (filter_gender != '*') {
			url += '&filter_gender=' + encodeURIComponent(filter_gender);
		}
		
		var filter_mail_status = $('select[name=\'filter_mail_status\']').children("option:selected").val();
		
		if (filter_mail_status != '*') {
			url += '&filter_mail_status=' + encodeURIComponent(filter_mail_status);
		}
		
	//	var filter_campaing_id = $('select[name=\'campaing_id\']').children("option:selected").val();
		
	//	if (filter_campaing_id != '*') {
	//		url += '&campaing_id=' + encodeURIComponent(filter_campaing_id);
	//	}
		
		var filter_mail_opened = $('input[name=\'mail_opened\']:checked').val();
		
		if (filter_mail_opened !== undefined) {
			url += '&filter_mail_opened=1';
		}
		
		var filter_mail_checked = $('input[name=\'mail_checked\']:checked').val();
		
		if (filter_mail_checked !== undefined) {
			url += '&filter_mail_checked=1';
		}
		
		var filter_push_signed = $('input[name=\'push_sign\']:checked').val();
		
		if (filter_push_signed  !== undefined) {
			url += '&filter_push_signed=1';
		}
		
		var filter_nbt_customer = $('input[name=\'nbt_customer\']:checked').val();
		
		if (filter_nbt_customer  !== undefined) {
			url += '&filter_nbt_customer=1';
		}
		
		var filter_nbt_customer_exclude = $('input[name=\'nbt_customer_exclude\']:checked').val();
		
		if (filter_nbt_customer_exclude  !== undefined) {
			url += '&filter_nbt_customer_exclude=1';
		}
		
		var filter_segment_intersection = $('input[name=\'filter_segment_intersection\']:checked').val();
		
		if (filter_segment_intersection  !== undefined) {
			url += '&filter_segment_intersection=1';
		}
		
		var filter_has_discount = $('input[name=\'filter_has_discount\']:checked').val();
		
		if (filter_has_discount !== undefined) {
			url += '&filter_has_discount=1';
		}
		
		var filter_no_discount = $('input[name=\'filter_no_discount\']:checked').val();
		
		if (filter_no_discount !== undefined) {
			url += '&filter_no_discount=1';
		}
		
		var filter_no_birthday = $('input[name=\'filter_no_birthday\']:checked').val();
		
		if (filter_no_birthday !== undefined) {
			url += '&filter_no_birthday=1';
		}
		
		
		var filter_approved = $('select[name=\'filter_approved\']').children("option:selected").val();
		
		if (filter_approved != '*') {
			url += '&filter_approved=' + encodeURIComponent(filter_approved);
		}
		
		var filter_ip = $('input[name=\'filter_ip\']').prop('value');
		
		if (filter_ip) {
			url += '&filter_ip=' + encodeURIComponent(filter_ip);
		}
		
		var filter_country_id = $('select[name=\'filter_country_id\']').children("option:selected").val();
		
		if (filter_country_id != '*') {
			url += '&filter_country_id=' + encodeURIComponent(filter_country_id);
		}
		
		var filter_source = $('select[name=\'filter_source\']').children("option:selected").val();
		
		if (filter_source != '*') {
			url += '&filter_source=' + encodeURIComponent(filter_source);
		}
		
		var filter_date_added = $('input[name=\'filter_date_added\']').prop('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		var filter_date_added = $('input[name=\'filter_date_added\']').prop('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		var filter_last_call = $('input[name=\'filter_last_call\']').prop('value');
		
		if (filter_last_call) {
			url += '&filter_last_call=' + encodeURIComponent(filter_last_call);
		}
		
		var filter_birthday_from = $('input[name=\'filter_birthday_from\']').prop('value');
		
		if (filter_birthday_from) {
			url += '&filter_birthday_from=' + encodeURIComponent(filter_birthday_from);
		}
		
		var filter_birthday_to = $('input[name=\'filter_birthday_to\']').prop('value');
		
		if (filter_birthday_to) {
			url += '&filter_birthday_to=' + encodeURIComponent(filter_birthday_to);
		}
		
		var order_first_date_from = $('input[name=\'order_first_date_from\']').prop('value');
		
		if (order_first_date_from) {
			url += '&order_first_date_from=' + encodeURIComponent(order_first_date_from);
		}
		
		var order_first_date_to = $('input[name=\'order_first_date_to\']').prop('value');
		
		if (order_first_date_to) {
			url += '&order_first_date_to=' + encodeURIComponent(order_first_date_to);
		}
		
		var filter_order_count = $('input[name=\'filter_order_count\']').prop('value');
		
		if (filter_order_count) {
			url += '&filter_order_count=' + encodeURIComponent(filter_order_count);
		}
		
		var filter_order_good_count = $('input[name=\'filter_order_good_count\']').prop('value');
		
		if (filter_order_good_count) {
			url += '&filter_order_good_count=' + encodeURIComponent(filter_order_good_count);
		}
		
		var filter_total_sum = $('input[name=\'filter_total_sum\']').prop('value');
		
		if (filter_total_sum) {
			url += '&filter_total_sum=' + encodeURIComponent(filter_total_sum);
		}
		
		var filter_avg_cheque = $('input[name=\'filter_avg_cheque\']').prop('value');
		
		if (filter_avg_cheque) {
			url += '&filter_avg_cheque=' + encodeURIComponent(filter_avg_cheque);
		}
		
		var filter_interest_brand = $('input[name=\'filter_interest_brand\']').prop('value');
		
		if (filter_interest_brand) {
			url += '&filter_interest_brand=' + encodeURIComponent(filter_interest_brand);
		}
		
		var filter_interest_category = $('input[name=\'filter_interest_category\']').prop('value');
		
		if (filter_interest_category) {
			url += '&filter_interest_category=' + encodeURIComponent(filter_interest_category);
		}
		
		var filter_segment_id = $('input:checkbox:checked.filter_segment_id').map(function(){
		return this.value; }).get().join(",");
		
		if (filter_segment_id) {
			url += '&filter_segment_id=' + encodeURIComponent(filter_segment_id);
		}
		
		location = url;
	}
//--></script>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date').datepicker({dateFormat: 'yy-mm-dd'});
		$('#date_bf').datepicker({dateFormat: 'mm-dd'});
		$('#date_bt').datepicker({dateFormat: 'mm-dd'});
		
		$(".rateYo").rateYo({
			precision: 1,
			starWidth: "18px",
			readOnly: true,
			normalFill: "#ccc",
			multiColor: {																
				"startColor": "#cf4a61", //RED
				"endColor"  : "#4ea24e"  //GREEN
			}
		});
	});
//--></script>
<?php echo $footer; ?>
