<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/pretty-checkbox.min.css" media="screen" />
<? if (empty($customer_id)) { $customer_id = '-1'; } ?>
<style>
	#nbt_customer{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	#nbt_customer.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
	
	#rja_customer{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	#rja_customer.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
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
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/customer.png" alt="" />Покупатель:<span class="customer_name"> <?php echo $firstname; ?> <?php echo $lastname; ?></span>
				
				<? if ($nbt_customer) { ?>
					&nbsp;&nbsp;&nbsp;<span id="nbt_customer" class="is_nbt ktooltip_hover" title="Недозвон" style="display:inline-block; font-size: 28px;"><i class="fa fa-deaf" aria-hidden="true"></i> НБТ</span>
					<? } else { ?>
					&nbsp;&nbsp;&nbsp;<span id="nbt_customer" class="ktooltip_hover" title="Все ок с дозвоном" style="display:inline-block;  font-size: 28px;"><i class="fa fa-deaf" aria-hidden="true"></i> НБТ</span>
				<? } ?>
				
				<? if ($rja_customer) { ?>
					&nbsp;&nbsp;&nbsp;<span id="rja_customer" class="is_nbt ktooltip_hover" title="Отказ давать адрес" style="display:inline-block; font-size: 28px;"><i class="fa fa-snowflake-o" aria-hidden="true"></i> ОТКАЗ АДР.</span>
					<? } else { ?>
					&nbsp;&nbsp;&nbsp;<span id="rja_customer" class="ktooltip_hover" title="Все ок с адресом" style="display:inline-block;  font-size: 28px;"><i class="fa fa-snowflake-o" aria-hidden="true"></i> ОТКАЗ АДР.</span>
				<? } ?>
				
			</h1>
			
			<div class="buttons"><a href="<? echo $letter_href; ?>" target="_blank" class="button">Конверт!</a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<style>
			#tab_orders_switch, #tab_waitlist_switch, #tab_returns_switch, #tab_calls_switch, #tab_viewed_switch { border-bottom: initial; }
		</style>
		<div class="content">
			<div id="htabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-add">Адреса</a>
				<?php if ($customer_id) { ?>					
					<a href="#tab-marketingmail">Почта</a>
					<? /*	<a href="#tab-marketingsms">Отправка SMS</a> */ ?> 
					<a href="#tab-reward">Программа лояльности (бонусы)</a>
					<a href="#tab-transaction"><?php echo $tab_transaction; ?></a>
					<a href="#tab-history">Хронология</a>
					<a href="#tab-viewed" id='tab_viewed_switch'>Интересы</a>
					<a href="#tab-orders" id='tab_orders_switch'>Заказы <? if ($orders_for_csi) { ?>
						<sup style="color:red; font-weight:700;">CSI: +<? echo count($orders_for_csi); ?></sup>
					<? } ?>
					</a>
					<a href="#tab-calls" id='tab_calls_switch'>Звонки</a>
					<a href="#tab-waitlist" id='tab_waitlist_switch'>Лист ожидания</a>
					<a href="#tab-returns" id='tab_returns_switch'>Возвраты</a>
					<a href="#tab-temp-signs" id='tab_returns_switch'>Метки</a>
				<?php } ?>
				<a href="#tab-ip"><?php echo $tab_ip; ?></a>
				<div style="clear: both;"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<div id="tab-general-content">	
						
						<table class="form" id="customer-table" style="width:100%">
							<tr>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo $entry_firstname; ?></span>
								</td>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo $entry_lastname; ?></span>
								</td>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Почта</span>
								</td>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Телефон</span>
								</td>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Второй телефон</span>
								</td>
							</tr>
							
							<tr style="border-bottom:1px dashed gray">
								<td>
									<input type="text" autocomplete="false"  name="firstname" value="<?php echo $firstname; ?>" />
									
									<?php if ($error_firstname) { ?>
										<span class="error"><?php echo $error_firstname; ?></span>
									<?php } ?>
									
								</td>
								<td>
									<input type="text" autocomplete="false"  name="lastname" value="<?php echo $lastname; ?>" />
									
									<?php if ($error_lastname) { ?>
										<span class="error"><?php echo $error_lastname; ?></span>
									<?php } ?>
								</td>
								<td>
									<input type="text" autocomplete="false"  name="email" value="<?php echo $email; ?>" />
									
									<?php if ($error_email) { ?>
										<span class="error"><?php echo $error_email; ?></span>
									<?php  } ?>
								</td>
								<td>
									<input type="text" autocomplete="false"  id='telephone' name="telephone" value="<?php echo $telephone; ?>" style="width:80%" />
									<span class='click2call' data-phone="<?php echo $telephone; ?>"></span>				
									<?php if ($error_telephone) { ?>
										<span class="error"><?php echo $error_telephone; ?></span>
									<?php  } ?>
									
								</td>
								<td>
									<input type="text" autocomplete="false"  name="fax" value="<?php echo $fax; ?>" style="width:80%" />
									<span class='click2call' data-phone="<?php echo $fax; ?>"></span>	
								</td>
							</tr>
						</table>
						
						<table class="form" id="customer-table2" style="width:100%">
							<tr>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Новости, акции компании </span>
								</td>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"> Акции, промокоды и скидки</span>
								</td>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Персональные рекомендации</span>
								</td>
								
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">SMS / Viber</span>
								</td>
								
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">День рождения</span>
								</td>
							</tr>
							
							<tr style="border-bottom:1px dashed gray">
								<td>
									<select name="newsletter_news">
										<?php if ($newsletter_news) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<select name="newsletter">
										<?php if ($newsletter) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<select name="newsletter_personal">
										<?php if ($newsletter_personal) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
								
								<td>
									<select name="viber_news">
										<?php if ($viber_news) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
								
								<td>
									<input class="date" type="text" name="birthday" value="<?php echo ($birthday != '0000-00-00' && $birthday != '1970-01-01')?$birthday:''; ?>" style="width:200px;" />
								</td>
							</tr>
						</table>
						
						<table class="form" id="customer-table2" style="width:100%">
							<tr>
								<td width="16%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Виртуальный магазин</span>
								</td>
								<td width="16%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Пол</span>
								</td>
								<td width="16%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Группа покупателей</span>
								</td>
								<td width="16%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF">Только предоплата</span>
								</td>
								<td width="16%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Статус</span>
								</td>
								<td width="16%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Уведомления</span>
								</td>
							</tr>
							
							<tr style="border-bottom:1px dashed gray">
								<td>
									<?php foreach ($stores as $store) { ?>
										<?php if ($store['store_id'] == $store_id) { ?>
											<?php echo $store['name']; ?>										
										<?php } ?>
									<?php } ?>
									<br /><span class="help">изменить привязку виртуального магазина нельзя с 21.11.2021</span>
									<input type="hidden" name="store_id" value="<? echo $store_id; ?>" />
								</td>
								
								<td>
									<select name="gender">
										<option value="0" <?php if ($gender == 0) { ?>selected="selected"<?php } ?>>Неведома зверушка</option>
										<option value="1" <?php if ($gender == 1) { ?>selected="selected"<?php } ?>>Мужчина</option>
										<option value="2" <?php if ($gender == 2) { ?>selected="selected"<?php } ?>>Женщина</option>										
									</select>
								</td>
								
								<td>
									<select name="customer_group_id" style="width:160px;">
										<?php foreach ($customer_groups as $customer_group) { ?>
											<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
												<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
								
								<td>
									<select name="mudak">
										<?php if ($mudak) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<option value="1"><?php echo $text_enabled; ?></option>
										<?php } ?>
									</select>
								</td>
								
								<td>
									<select name="status">
										<?php if ($status) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
								
								<td>
									<select name="notify">
										<?php if ($notify) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							
							<tr id="fakefields">
								<td style="height:1px;">Фейковые поля</td>
								<td style="height:1px;">
									<input name="username_fake" type="text" />
									<input name="password_fake" type="password" />
									<input type="text" autocomplete="false"  name="discount_card" value="<?php echo $discount_card; ?>" />
								</td>
							</tr>
							<script>
								$(document).ready(function(){
									setTimeout(function(){$('#fakefields').hide()}, 100);
								});
							</script>
							
						</table>
						
						<table class="form" id="customer-table3" style="width:100%">
							<tr>
								<td width="25%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Безналичный расчет</span>
								</td>
								<td width="25%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Комментарий / описание</span>
								</td>
								<td width="25%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Паспорт серия</span>
								</td>
								<td width="25%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Паспорт выдан</span>
								</td>
							</tr>
							
							<tr style="border-bottom:1px dashed gray">
								<td>
									<textarea name="cashless_info" rows="5" cols="30" style="resize:both"><? echo $cashless_info; ?></textarea>
								</td>
								
								<td>
									<textarea name="customer_comment" rows="5" cols="30"><? echo $customer_comment; ?></textarea>
								</td>
								
								<td>
									<input type="text" autocomplete="off"  name="passport_serie" value="<?php echo $passport_serie; ?>" />
								</td>
								
								<td>
									<textarea name="passport_given" rows="5" cols="30"><? echo $passport_given; ?></textarea>
								</td>
								
							</tr>
						</table>
						
						<table class="form" id="customer-table3" style="width:100%">
							<tr>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF">Пароль</span>
								</td>
								<td width="20%">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF">Пароль еще раз</span>
								</td>
								<td width="60%">
								</td>
							</tr>
							
							<tr>
								
								<td>
									<input type="password" name="password" value="<?php echo $password; ?>" autocomplete="off" />
									<?php if ($error_password) { ?>
										<span class="error"><?php echo $error_password; ?></span>
									<?php  } ?>
								</td>
								
								<td>
									<input type="password" name="confirm" value="<?php echo $confirm; ?>" />
									<?php if ($error_confirm) { ?>
										<span class="error"><?php echo $error_confirm; ?></span>
									<?php  } ?>
								</td>
								<td width="60%">
								</td>
							</tr>
						</table>
						
						
						<script type="text/javascript">
							$(function(){
								//   $('#customer-table').after('<div id="simple_custom_customer" class="simple-container"></div>');
								//  $('#simple_custom_customer').load('index.php?route=module/simple/custom&token=<?php echo $token; ?>&set=customer&type=customer&id=<?php echo $customer_id; ?>');
							});
						</script>
					</div>
				</div>	
				
				<div id="tab-add">										
					
					<div>
						<a class="button" id="address-add" style="display:inline-block;float:right;cursor:pointer;" onclick="addAddress();"><?php echo $button_add_address; ?>&nbsp;<img style="vertical-align: top;" src="view/image/add.png" alt="" /></a><div style="clear:both;"></div>
					</div>
					
					<div style="height:10px;"></div>
					<div id="address-add-list">				
						<?php $address_row = 1; ?>
						<? $adr_array = array(); ?>
						<?php foreach ($addresses as $address) { ?>
							<? $adr_array[] = $address; ?>
							<div id="tab-address-<?php echo $address_row; ?>" style="width:100%; margin-left:10px; float:left; border:1px solid #1f4962">
								<input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
								<script type="text/javascript">
									$(function(){
										$('#tab-address-<?php echo $address_row; ?> table').after('<div id="simple_custom_address_<?php echo $address_row; ?>" class="simple-container"></div>');
										$('#simple_custom_address_<?php echo $address_row; ?>').load('index.php?route=module/simple/custom&set=address&token=<?php echo $token; ?>&type=address&id=<?php echo $address['address_id']; ?>');
									});
								</script>
								<table width="100%">
									<tr><th class="blue_heading" style="color:white;padding: 5px 0;" colspan="4" style="">Адрес <?php echo $address_row; ?>&nbsp;&nbsp;&nbsp;<a class="" style="color:white;cursor:pointer;font-size:10px;padding: 3px;" onclick="$('#tab-address-<?php echo $address_row; ?>').remove();">удалить</a></th></tr>
									<tr>
										<td style="padding:10px 3px;">Имя, Отчество, Фамилия</td>
										<td style="padding:10px 3px;">
											<input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" />
											<?php if (isset($error_address_firstname[$address_row])) { ?>
												<span class="error"><?php echo $error_address_firstname[$address_row]; ?></span>
											<?php } ?>
											<input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" />
											<?php if (isset($error_address_lastname[$address_row])) { ?>
												<span class="error"><?php echo $error_address_lastname[$address_row]; ?></span>
											<?php } ?>	
										</td>
										<td style="padding:10px 3px;">Паспорт серия</td>
										<td style="padding:10px 3px;"><input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][passport_serie]" value="<?php echo $address['passport_serie']; ?>" style="width:300px;" />
										</td>
									</tr>									
									
									<tr>										
										<td colspan="2" style="padding:10px 3px;">
											<input id="address[<?php echo $address_row; ?>][default]" class="checkbox" type="checkbox" name="address[<?php echo $address_row; ?>][default]" value="1" <?php if (($address['address_id'] == $address_id) || !$addresses) { ?> checked="checked" <?php } ?> />
											<label for="address[<?php echo $address_row; ?>][default]">Адрес по-умолчанию</label><br />
											<span class="help">подставляется по-умолчанию при оформлении заказа</span><br />
										</td>
									</td>
									<td rowspan="3">Паспорт выдан</td>
									<td rowspan="3"><textarea name="address[<?php echo $address_row; ?>][passport_given]" rows="3" cols="30" style="width:300px;"><?php echo $address['passport_given']; ?></textarea></td>
								</tr>
								<tr>
									<td colspan="2" style="padding:10px 3px;">
										<input id="address[<?php echo $address_row; ?>][verified]" class="checkbox" type="checkbox" name="address[<?php echo $address_row; ?>][verified]" value="1" <?php if ($address['verified']) { ?> checked="checked" <?php } ?> />
										<label for="address[<?php echo $address_row; ?>][verified]"><b><i class="fa fa-check-square" aria-hidden="true"></i> Адрес верифицирован</b></label>
										<br />
										<span class="help">адрес проверен по телефону, либо на этот адрес производилась успешно доставка</span><br />
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding:10px 3px;">
										<input id="address[<?php echo $address_row; ?>][for_print]" class="checkbox" type="checkbox" name="address[<?php echo $address_row; ?>][for_print]" value="1" <?php if ($address['for_print']) { ?> checked="checked" <?php } ?> />
										<label for="address[<?php echo $address_row; ?>][for_print]"><b><i class="fa fa-envelope-square" aria-hidden="true"></i> Адрес для почты</b></label><br />
										<span class="help">на этот адрес клиент желает получать печатную продукцию</span><br />
									</td>
								</tr>
								
								<tr class="company-id-display">
									<td><?php echo $entry_company_id; ?></td>
									<td><input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][company_id]" value="<?php echo $address['company_id']; ?>" style="width:300px;" /></td>
									<td><?php echo $entry_company; ?></td>
									<td><input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" style="width:300px;" /></td>
								</tr>
								
								<tr class="tax-id-display">
									<td><?php echo $entry_tax_id; ?></td>
									<td><input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][tax_id]" value="<?php echo $address['tax_id']; ?>" style="width:300px;" />
										<?php if (isset($error_address_tax_id[$address_row])) { ?>
											<span class="error"><?php echo $error_address_tax_id[$address_row]; ?></span>
										<?php } ?></td>
								</tr>
								<tr>
									<td style="padding:5px 3px;">Первая строка адреса</td>
									<td style="padding:5px 3px;">
										<i class="fa fa-map-marker" aria-hidden="true"></i> <input type="text" autocomplete="false" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" style="width:300px;" />
										<?php if (isset($error_address_address_1[$address_row])) { ?>
											<span class="error"><?php echo $error_address_address_1[$address_row]; ?></span>
										<?php } ?>
									</td>
									
									<td style="padding:5px 3px;"><?php echo $entry_city; ?></td>
									<td style="padding:5px 3px;">
										<input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" style="width:300px;" />
										<?php if (isset($error_address_city[$address_row])) { ?>
											<span class="error"><?php echo $error_address_city[$address_row]; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td style="padding:5px 3px;">Вторая строка адреса</td>
									<td style="padding:5px 3px;">
										<i class="fa fa-map-marker" aria-hidden="true"></i><input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" style="width:300px;" />
									</td>
									
									<td style="padding:5px 3px;"><?php echo $entry_country; ?></td>
									<td style="padding:5px 3px;"><select name="address[<?php echo $address_row; ?>][country_id]" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $address['zone_id']; ?>');" style="width:300px;">
										<option value=""><?php echo $text_select; ?></option>
										<?php foreach ($countries as $country) { ?>
											<?php if ($country['country_id'] == $address['country_id']) { ?>
												<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<?php if (isset($error_address_country[$address_row])) { ?>
										<span class="error"><?php echo $error_address_country[$address_row]; ?></span>
									<?php } ?>
									</td>
								</tr>
								<tr>
									<td style="padding:5px 3px;"><span id="postcode-required<?php echo $address_row; ?>" class="required">*</span> <?php echo $entry_postcode; ?></td>
									<td style="padding:5px 3px;"><input type="text" autocomplete="false"  name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" style="width:300px;" /></td>
									<td style="padding:5px 3px;"><?php echo $entry_zone; ?></td>
									<td style="padding:5px 3px;"><select name="address[<?php echo $address_row; ?>][zone_id]">
									</select>
									<?php if (isset($error_address_zone[$address_row])) { ?>
										<span class="error"><?php echo $error_address_zone[$address_row]; ?></span>
									<?php } ?></td>
									
								</tr>
							</table>
						</div>
						<?php $address_row++; ?>
					<?php } ?>
				</div>
			</div>
			
			
			<?php if ($customer_id) { ?>
				
				<div id="tab-marketingmail">
					<div id="actiontemplateslist" style="float:left; width:450px;">
						<? foreach ($actiontemplates as $template) { ?>
							<div class="tselector" style="cursor:pointer; float:left; margin-left:20px; margin-bottom:20px; padding:5px; width:110px; text-align:center; border:2px solid #ccc; border-radius:5px;" data-id="<? echo $template['actiontemplate_id'] ?>" data-customer-id="<? echo $customer_id; ?>">
								<img src="<? echo $template['image'] ?>" /><br />
								<? echo $template['title']; ?><br />
								
								<? if ($template['code']) { ?>
									<span style="font-size:8px;"><? echo $template['code']; ?></span><br />
								<? } ?>
								
								<span id="at_span_result_<? echo $template['actiontemplate_id'] ?>" class="status_color" style="padding:3px 5px; border-radius:5px; background-color:#<? if ($template['sent']) { ?>aaffaa<? } else { ?>e4c25a<? } ?>">
									<? if ($template['sent']) { ?>
										<i class="fa fa-check-square" aria-hidden="true"></i>&nbsp; <? echo date('d.m.Y', strtotime($template['sent']['date_sent'])); ?>
										<? } else { ?>
										Не отправляли
									<? } ?>
								</span>
							</div>
						<? } ?><br /><br />
						<div class="clr"></div>
						<div style="margin-left:20px; display:none; text-align:center;" id="send-button">
							<input type="hidden" id="template_id" value="" />
							<span id="sendingstatus"></span>
							<a class="button" style="width:90%;" id="sendmarketingmail">Отправить E-MAIL</a>
						</div>
					</div>
					<div id="actiontemplatespreview_wrapper" style="width:calc(100% - 500px); background-color:white; text-align:center; float:left; margin-left:30px;">
						<div id="actiontemplatespreview_title" style="font-size:22px; margin-bottom:10px;"></div>
						<div id="actiontemplatespreview"></div>
					</div>
					<div class="clr"></div>
					
					<script>
						$('.tselector').click(function(){
							$('.tselector').css('border-color', '#ccc');
							$('.tselector').removeClass('active_tselector');
							var _el = $(this);
							$.ajax({
								dataType : 'json',
								type : 'GET',
								url : 'index.php?route=catalog/actiontemplate/loadTemplate&token=<? echo $token; ?>&customer_id=<? echo $customer_id; ?>&template_id='+$(this).attr('data-id'),
								beforeSend : function(){
									$('#actiontemplatespreview_title').html('');
									$('#actiontemplatespreview').html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:64px;"></i>');
								},
								success : function(json){
									$('#actiontemplatespreview_title').html(json.title);
									$('#actiontemplatespreview').html(json.html);
									$('#template_id').val(_el.attr('data-id'));
									_el.css('border-color', '#4ea24e'); 
									$('#send-button').show();
									_el.addClass('active_tselector');
								}
							});
						});
						
						$('#sendmarketingmail').click(function(){
							var _button = $(this);
							var _tid = $(this).attr('data-id');
							$.ajax({
								dataType : 'json',
								type : 'POST',
								url : 'index.php?route=catalog/actiontemplate/sendMailWithTemplate&token=<? echo $token; ?>&customer_id=<? echo $customer_id; ?>&template_id='+_tid,
								data : {
									customer_id : '<? echo $customer_id; ?>',
									template_id : parseInt($('input#template_id').val())									
								},
								beforeSend : function(){
									_button.hide();
									$('#sendingstatus').html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:24px;"></i>');									
								},
								success : function(json){
									$('#sendingstatus').html('EMAIL отправлен! Красавчик!');
									$('#at_span_result_'+_tid).html('<i class="fa fa-check-square" aria-hidden="true"></i> '+json.date_sent);
									$('#at_span_result_'+_tid).css('background-color', '#aafaa');
									_button.hide();
								},
								error : function(json){
									$('#sendingstatus').html('Произошла ошибка.');	
									_button.show();
								}
							});
						});
					</script>
				</div>
				
				<div id="tab-marketingsms">
					
				</div>
				
					<div id="tab-viewed">
						<div id="viewed-list"></div>
					</div>
					<div id="tab-history">
						<div id="history"></div>
						<table class="form">
							<tr>      
								<td colspan='2'><textarea name="comment" cols="40" rows="8" style="width: 99%;"></textarea></td>
							</tr>
							<tr>
								<td style="width:1px; padding:2px 10px; white-space:nowrap;">Необходимо перезвонить в:</td>
								<td><input type="text" autocomplete="false"  name="need_call" class="datetime" width="250px;" /></td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: right;"><a id="button-history" class="button"><span>Добавить историю взаимоотношений</span></a></td>
							</tr>
						</table>
						<script>
							$('.datetime').datetimepicker({
								dateFormat: 'yy-mm-dd',
								timeFormat: 'h:m'
							});	
						</script>
					</div>
					<div id="tab-transaction">
						<table width="100%">                   
							<tr>
								<td><?php echo $entry_description; ?></td>
								<td><input type="text" autocomplete="false"  name="description" value="" style="width:500px;" /></td>
							</tr>
							<tr>
								<td><?php echo $entry_amount; ?></td>
								<td><input type="text" autocomplete="false"  name="amount" value="" />&nbsp;<? echo $currency_code; ?> <span class="help">Для снятия суммы введи отрицательное число с "-" впереди</span></td>
							</tr>
							<tr>
								<td>Привязка к заказу</td>
								<td><input type="text" autocomplete="false"  name="order_id" value="" /> <span class="help">Если операция связана с конкретным заказом, введи сюда код</span></td>
							</tr>
							<tr>
								<td colspan='2' style="text-align:center;">Покупатель привязан к магазину: <? echo $store_name; ?>. Взаиморасчеты выполняются в валюте: <? echo $currency_name; ?> (<? echo $currency_code; ?>). Параллельный подсчет ведется в центральной валюте: Евро.</td>               
							</tr>
							<tr>
								<td colspan="2" style="text-align: right;"><a id="button-transaction" class="button" onclick="addTransaction();"><span>Добавить финансовую транзакцию</span></a></td>
							</tr>
						</table>
						<div id="transaction"></div>
					</div>
					
					<div id="tab-reward">
						<table class="form">
							<tr>
								
								<td>
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Количество бонусов</span>									
								</td>
								
								<td>
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Описание, или причина транзакции</span>
								</td>
								
								<td style="text-align: right;"></td>
							</tr>
							<tr>
								<td style="text-align: left;" width="20%">
									<input type="number" autocomplete="false"  name="points" value="" style="width:150px;" />									
								</td>
								<td style="text-align: left;" width="50%"><input type="text" autocomplete="false"  name="description" value="" style="width:400px;" /></td>
								
								<td style="text-align: left;" width="30%">
									<a id="button-reward" class="button" onclick="addRewardPoints();"><span><?php echo $button_add_reward; ?></span></a>
								</td>
							</tr>
							
							<tr>
								<td style="text-align: left;">
									<i class="fa fa-info-circle"></i> Для снятия суммы введи отрицательное число с "-" впереди								
								</td>
								<td style="text-align: left;">
									<i class="fa fa-info-circle"></i> Обязательно укажи причину начисления либо списания
								</td>
								<td style="text-align: left;">
									<i class="fa fa-exclamation-circle"></i> Внимание! История начислений либо списаний не удаляется, а только корректируется, будь внимательным.
								</td>
							</tr>
						</table>
						<hr />
						<div id="reward"></div>
					</div>
					
					
					<div id="tab-orders">
						<div id="orders-list"></div>
					</div>
					<div id="tab-calls">
						<div id="calls-list"></div>
					</div>
					<div id="tab-waitlist">
						<div id="waitlist-list"></div>
					</div>
					
					<div id="tab-returns">
						<div id="returns-list"></div>
					</div>
					
					<div id="tab-temp-signs">				
						<table class="" style="width:100%">
							<tr>
								<td>Отправили СМС поздравление с НГ 2018</td>
								<td>
									<input id="cron_sent" class="checkbox" type="checkbox" name="cron_sent" value="1" <?php if ($cron_sent) { ?> checked="checked" <?php } ?> />
									<label for="cron_sent"></label>
								</td>								
							</tr>
						</table>				
					</div>
					
					<div id="tab-ip">
						<table class="list">
							<thead>
								<tr>
									<td class="left"><?php echo $column_ip; ?></td>
									<td class="right"><?php echo $column_total; ?></td>
									<td class="left"><?php echo $column_date_added; ?></td>
									<td class="right"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php if ($ips) { ?>
									<?php foreach ($ips as $ip) { ?>
										<tr>
											<td class="left"><a href="http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>" target="_blank"><?php echo $ip['ip']; ?></a></td>
											<td class="right"><a href="<?php echo $ip['filter_ip']; ?>" target="_blank"><?php echo $ip['total']; ?></a></td>
											<td class="left"><?php echo $ip['date_added']; ?></td>
											<td class="right"><?php if ($ip['ban_ip']) { ?>
												<a class="button" id="<?php echo str_replace('.', '-', $ip['ip']); ?>" onclick="removeBanIP('<?php echo $ip['ip']; ?>');"><?php echo $text_remove_ban_ip; ?></a>
												<?php } else { ?>
												<a class="button" id="<?php echo str_replace('.', '-', $ip['ip']); ?>" onclick="addBanIP('<?php echo $ip['ip']; ?>');"><?php echo $text_add_ban_ip; ?></a>
											<?php } ?></td>
										</tr>
									<?php } ?>
									<?php } else { ?>
									<tr>
										<td class="center" colspan="4"><?php echo $text_no_results; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("#rja_customer").click(function(){
			var _el = $(this);
			var _cid = $(this).attr('data-customer-id');
			$.ajax({
				url : 'index.php?route=sale/customer/setCustomerRJAAjax&customer_id=<? echo $customer_id; ?>&token=<?php echo $token; ?>',
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
		$("#nbt_customer").click(function(){
			var _el = $(this);
			var _cid = $(this).attr('data-customer-id');
			$.ajax({
				url : 'index.php?route=sale/customer/setCustomerNBTAjax&customer_id=<? echo $customer_id; ?>&token=<?php echo $token; ?>',
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
	
	
	function setRateYo(){
		
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
		
	}
	
	$(document).ready(function(){				
		$('#waitlist-list').load('index.php?route=catalog/waitlist&token=<?php echo $token; ?>&limit=200&ajax=1&filter_customer_id=<?php echo $customer_id; ?>');		
		$('#viewed-list').load('index.php?route=sale/customer/customerViewed&token=<?php echo $token; ?>&limit=200&ajax=1&customer_id=<?php echo $customer_id; ?>');						
		$('#orders-list').load('index.php?route=sale/order&token=<?php echo $token; ?>&limit=200&ajax=1&filter_customer=<?php echo $customer_id; ?>', function(){ setRateYo();  });		
		$('#returns-list').load('index.php?route=sale/return&token=<?php echo $token; ?>&limit=200&ajax=1&filter_customer=<?php echo $customer_id; ?>');	
		$('#calls-list').load('index.php?route=sale/customer/callshistory&token=<?php echo $token; ?>&limit=200&ajax=1&customer_id=<?php echo $customer_id; ?>', function(){ setAudioTrigger(); });
	});
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
</script>
<script type="text/javascript"><!--
	$('select[name=\'customer_group_id\']').live('change', function() {
		var customer_group = [];
		
		<?php foreach ($customer_groups as $customer_group) { ?>
			customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
			customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
			customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
		<?php } ?>	
		
		if (customer_group[this.value]) {
			if (customer_group[this.value]['company_id_display'] == '1') {
				$('.company-id-display').show();
				} else {
				$('.company-id-display').hide();
			}
			
			if (customer_group[this.value]['tax_id_display'] == '1') {
				$('.tax-id-display').show();
				} else {
				$('.tax-id-display').hide();
			}
		}
	});
	
	$('select[name=\'customer_group_id\']').trigger('change');
	
	$('#tab-address-1').on('click', function(){
		if ($('input[name="address[1][firstname]"]').val() == "") $('input[name="address[1][firstname]"]').val($('input[name=firstname]').val());
		$('input[name=firstname]').keyup(function(event) {
			$('input[name="address[1][firstname]"]').val($('input[name=firstname]').val());
		});
		if ($('input[name="address[1][lastname]"]').val() == "") $('input[name="address[1][lastname]"]').val($('input[name=lastname]').val());
		$('input[name=lastname]').keyup(function(event) {
			$('input[name="address[1][lastname]"]').val($('input[name=lastname]').val());
		});
	});
	
	
//--></script> 
<script type="text/javascript"><!--
	function country(element, index, zone_id) {
		if (element.value != '') {
			$.ajax({
				url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
				dataType: 'json',
				beforeSend: function() {
					$('select[name=\'address[' + index + '][country_id]\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('.wait').remove();
				},			
				success: function(json) {
					if (json['postcode_required'] == '1') {
						$('#postcode-required' + index).show();
						} else {
						$('#postcode-required' + index).hide();
					}
					
					html = '<option value=""><?php echo $text_select; ?></option>';
					
					if (json['zone'] != '') {
						for (i = 0; i < json['zone'].length; i++) {
							html += '<option value="' + json['zone'][i]['zone_id'] + '"';
							
							if (json['zone'][i]['zone_id'] == zone_id) {
								html += ' selected="selected"';
							}
							
							html += '>' + json['zone'][i]['name'] + '</option>';
						}
						} else {
						html += '<option value="0"><?php echo $text_none; ?></option>';
					}
					
					$('select[name=\'address[' + index + '][zone_id]\']').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}
	
	$('select[name$=\'[country_id]\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
	var address_row = <?php echo $address_row; ?>;
	
	function addAddress() {
		html = '';
		html+= '<div id="tab-address-' + address_row + '" style="width:45%; margin-left:10px; float:left; border:1px solid #1f4962">';
		html+= '	<table width="100%">';
		html+= '		<tr><th class="blue_heading" style="color:white;padding: 5px 0;" colspan="4" style="">Адрес ' + address_row + '&nbsp;&nbsp;&nbsp;<a class="" style="color:white;cursor:pointer;font-size:10px;padding: 3px;" onclick="$(\'#tab-address-' + address_row + '\').remove();">удалить</a></th></tr>';
		html+= '		<tr>';
		html+= '			<td style="padding:10px 3px;">Имя, Отчество, Фамилия</td>';
		html+= '			<td style="padding:10px 3px;">';
		html+= '				<input type="text" autocomplete="false"  name="address[' + address_row + '][firstname]" value="" />';
		html+= '				<input type="text" autocomplete="false"  name="address[' + address_row + '][lastname]" value="" />';
		html+= '			</td>';
		html+= '			<td style="padding:10px 3px;">Паспорт серия</td>';
		html+= '			<td style="padding:10px 3px;"><input type="text" autocomplete="false"  name="address[' + address_row + '][passport_serie]" value="" style="width:300px;" />';
		html+= '			</td>';
		html+= '		</tr>';						
		
		html+= '		<tr>';										
		html+= '			<td colspan="2" style="padding:10px 3px;">';
		html+= '				<input id="address[' + address_row + '][default]" class="checkbox" type="checkbox" name="address[' + address_row + '][default]" value="1" />';
		html+= '				<label for="address[' + address_row + '][default]"><b><i class="fa fa-check-square" aria-hidden="true"></i> Адрес по-умолчанию</b></label>';
		html+= '				<br />';
		html+= '				<span class="help">подставляется по-умолчанию при оформлении заказа</span><br />';
		html+= '			</td>';
		html+= '		</td>';
		html+= '		<td rowspan="3">Паспорт выдан</td>';
		html+= '		<td rowspan="3"><textarea name="address[' + address_row + '][passport_given]" rows="3" cols="30" style="width:300px;"></textarea></td>';
		html+= '	</tr>';
		html+= '	<tr>';
		html+= '		<td colspan="2" style="padding:10px 3px;">';
		html+= '			<input id="address[' + address_row + '][verified]" class="checkbox" type="checkbox" name="address[' + address_row + '][verified]" value="1" />';
		html+= '			<label for="address[' + address_row + '][verified]"><b><i class="fa fa-check-square" aria-hidden="true"></i> Адрес верифицирован</b></label>';
		html+= '			<br />';
		html+= '			<span class="help">адрес проверен по телефону, либо на этот адрес производилась успешно доставка</span><br />';
		html+= '		</td>';
		html+= '	</tr>';
		html+= '	<tr>';
		html+= '		<td colspan="2" style="padding:10px 3px;">';
		html+= '			<input id="address[' + address_row + '][for_print]" class="checkbox" type="checkbox" name="address[' + address_row + '][for_print]" value="1" />';
		html+= '			<label for="address[' + address_row + '][for_print]"><b><i class="fa fa-envelope-square" aria-hidden="true"></i> Адрес для почты</b></label><br />';
		html+= '			<span class="help">на этот адрес клиент желает получать печатную продукцию</span><br />';
		html+= '		</td>';
		html+= '	</tr>';
		
		html+= '	<tr>';
		html+= '		<td style="padding:5px 3px;">Первая строка адреса</td>';
		html+= '		<td style="padding:5px 3px;">';
		html+= '			<i class="fa fa-map-marker" aria-hidden="true"></i> <input type="text" autocomplete="false" name="address[' + address_row + '][address_1]" value="" style="width:300px;" />';
		html+= '		</td>';
		
		html+= '		<td style="padding:5px 3px;"><?php echo $entry_city; ?></td>';
		html+= '		<td style="padding:5px 3px;">';
		html+= '			<input type="text" autocomplete="false"  name="address[' + address_row + '][city]" value="" style="width:300px;" />';	
		html+= '		</td>';
		html+= '	</tr>';
		html+= '	<tr>';
		html+= '		<td style="padding:5px 3px;">Вторая строка адреса</td>';
		html+= '		<td style="padding:5px 3px;">';
		html+= '			<i class="fa fa-map-marker" aria-hidden="true"></i><input type="text" autocomplete="false"  name="address[' + address_row + '][address_2]" value="" style="width:300px;" />';
		html+= '		</td>';
		
		html+= '		<td style="padding:5px 3px;"><?php echo $entry_country; ?></td>';
		html+= '		<td style="padding:5px 3px;"><select name="address[' + address_row + '][country_id]" onchange="country(this, \'' + address_row + '\', \'\');" style="width:300px;">';
		html+= '			<option value=""><?php echo $text_select; ?></option>';
		<?php foreach ($countries as $country) { ?>				
			html+= '					<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>';
		<?php } ?>
		html+= '		</select>';		
		html+= '		</td>';
		html+= '	</tr>';
		html+= '	<tr>';
		html+= '		<td style="padding:5px 3px;"><span id="postcode-required' + address_row + '" class="required">*</span> <?php echo $entry_postcode; ?></td>';
		html+= '		<td style="padding:5px 3px;"><input type="text" autocomplete="false"  name="address[' + address_row + '][postcode]" value="<?php echo $address['postcode']; ?>" style="width:300px;" /></td>';
		html+= '		<td style="padding:5px 3px;"><?php echo $entry_zone; ?></td>';
		html+= '		<td style="padding:5px 3px;"><select name="address[' + address_row + '][zone_id]">';
		html+= '		</select>';
		html+= '	</td>';
		
		html+= '	</tr>';
		html+= '</table>';
		html+= '</div>';
		
		$('#address-add-list').append(html);
		
		$('select[name=\'address[' + address_row + '][country_id]\']').trigger('change');	
		
		address_row++;
	}
//--></script> 
<script type="text/javascript"><!--
	$('#history .pagination a').live('click', function() {
		$('#history').load(this.href);
		
		return false;
	});			
	
	$('#history').load('index.php?route=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');
	
	$('#button-history').bind('click', function() {
		$.ajax({
			url: 'index.php?route=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
			type: 'post',
			dataType: 'html',
			data: 'need_call='+encodeURIComponent($('#tab-history input[name=\'need_call\']').val())+'&comment=' + encodeURIComponent($('#tab-history textarea[name=\'comment\']').val()),
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-history').attr('disabled', true);
				$('#history').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-history').attr('disabled', false);
				$('.attention').remove();
				$('#tab-history textarea[name=\'comment\']').val('');
			},
			success: function(html) {
				$('#history').html(html);
				
				$('#tab-history input[name=\'comment\']').val('');
			}
		});
	});
//--></script> 
<script type="text/javascript"><!--
	$('#transaction .pagination a').live('click', function() {
		$('#transaction').load(this.href);
		
		return false;
	});			
	
	$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');
	
	$('#button-transaction').bind('click', function() {
		$.ajax({
			url: 'index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
			type: 'post',
			dataType: 'html',
			data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()) + '&order_id=' + encodeURIComponent($('#tab-transaction input[name=\'order_id\']').val()),
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-transaction').attr('disabled', true);
				$('#transaction').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-transaction').attr('disabled', false);
				$('.attention').remove();
			},
			success: function(html) {
				$('#transaction').html(html);
				
				$('#tab-transaction input[name=\'amount\']').val('');
				$('#tab-transaction input[name=\'description\']').val('');
			}
		});
	});
//--></script> 
<script type="text/javascript"><!--
	$('#reward .pagination a').live('click', function() {
		$('#reward').load(this.href);
		
		return false;
	});			
	
	$('#reward').load('index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');
	
	function addRewardPoints() {
		$.ajax({
			url: 'index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
			type: 'post',
			dataType: 'html',
			data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-reward').attr('disabled', true);
				$('#reward').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-reward').attr('disabled', false);
				$('.attention').remove();
			},
			success: function(html) {
				$('#reward').html(html);
				
				$('#tab-reward input[name=\'points\']').val('');
				$('#tab-reward input[name=\'description\']').val('');
			}
		});
	}
	
	function addBanIP(ip) {
		var id = ip.replace(/\./g, '-');
		
		$.ajax({
			url: 'index.php?route=sale/customer/addbanip&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: 'ip=' + encodeURIComponent(ip),
			beforeSend: function() {
				$('.success, .warning').remove();
				
				$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');		
			},
			complete: function() {
				
			},			
			success: function(json) {
				$('.attention').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#' + id).replaceWith('<a id="' + id + '" onclick="removeBanIP(\'' + ip + '\');"><?php echo $text_remove_ban_ip; ?></a>');
				}
			}
		});	
	}
	
	function removeBanIP(ip) {
		var id = ip.replace(/\./g, '-');
		
		$.ajax({
			url: 'index.php?route=sale/customer/removebanip&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: 'ip=' + encodeURIComponent(ip),
			beforeSend: function() {
				$('.success, .warning').remove();
				
				$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');					
			},	
			success: function(json) {
				$('.attention').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#' + id).replaceWith('<a id="' + id + '" onclick="addBanIP(\'' + ip + '\');"><?php echo $text_add_ban_ip; ?></a>');
				}
			}
		});	
	};
//--></script> 
<script type="text/javascript"><!--
	$('.htabs a').tabs();
	$('.vtabs a').tabs();
//--></script> 
<?php echo $footer; ?>			