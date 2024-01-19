<?php echo $header; ?>
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
			<h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-data">Описание</a>
				<?php if ($coupon_id) { ?>
					<a href="#tab-history"><?php echo $tab_history; ?></a>
				<?php } ?>
				<div class="clr"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<table class="form">
						<tr style="border-bottom:1px dashed gray">
							<td colspan="4">
								<table>
									<tr>
										<td style="width:15%">
											<span class="required">*</span> <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Название для списков</span>
										</td>
										<td style="width:15%">
											<span class="required">*</span> <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Код</span>
										</td>
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Статус</span>
										</td>
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Сегментация</span>
										</td>
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">В товарах</span>
										</td>
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Только в наличии</span>
										</td>
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Тип промокода</span>
										</td>	
									</tr>
									<tr>
										<td>
											<input name="name" value="<?php echo $name; ?>" />
											<?php if ($error_name) { ?>
												<span class="error"><?php echo $error_name; ?></span>
											<?php } ?>
										</td>
										<td>
											
											<input type="text" name="code" value="<?php echo $code; ?>" /><br />
											<?php if ($error_code) { ?>
												<span class="error"><?php echo $error_code; ?></span>
											<?php } ?>
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
											<select name="show_in_segments">
												<?php if ($show_in_segments) { ?>
													<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php } else { ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</td>
										<td>
											<select name="display_list">
												<?php if ($display_list) { ?>
													<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php } else { ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</td>
										
										<td>
											<select name="only_in_stock">
												<?php if ($only_in_stock) { ?>
													<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php } else { ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</td>
										
										<td>
											<select name="type">
												<?php if ($type == 'P') { ?>
													<option value="P" selected="selected"><?php echo $text_percent; ?></option>
													<?php } else { ?>
													<option value="P"><?php echo $text_percent; ?></option>
												<?php } ?>
												<?php if ($type == 'F') { ?>
													<option value="F" selected="selected"><?php echo $text_amount; ?></option>
													<?php } else { ?>
													<option value="F"><?php echo $text_amount; ?></option>
												<?php } ?>
												<?php if ($type == '3') { ?>
													<option value="3" selected="selected">Третий в подарок</option>
													<?php } else { ?>
													<option value="3">Третий в подарок</option>
												<?php } ?>
												<?php if ($type == '4') { ?>
													<option value="4" selected="selected">Четвертый в подарок</option>
													<?php } else { ?>
													<option value="4">Четвертый в подарок</option>
												<?php } ?>
												<?php if ($type == '5') { ?>
													<option value="5" selected="selected">Пятый в подарок</option>
													<?php } else { ?>
													<option value="5">Пятый в подарок</option>
												<?php } ?>
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td colspan="4">
								<table>
									<tr>
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Промо-группа</span>
										</td>
										
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Менеджер</span>
										</td>
										
										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF">Отображать в личном кабинете</span>
										</td>

										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Минимальный чек</span>
										</td>

										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Валюта минимального чека</span>
										</td>

										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Случайный</span>
										</td>

										<td style="width:15%">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Случайная подстрока</span>
										</td>
									</tr>
									<tr>
										<td>
											<input type="text" name="promo_type" value="<?php echo $promo_type; ?>" />
										</td>	
										<td>
											<select name="manager_id">
												<option style="padding:2px;" value='0' <? if (!$manager_id) { ?>selected="selected"<? } ?>>Менеджер не привязан</option>
												<? foreach ($managers as $mngr) { ?>
													<option style="padding:2px;" value='<? echo $mngr['user_id']; ?>' <? if ($manager_id == $mngr['user_id']) { ?>selected="selected"<? } ?>><? echo $mngr['realname']; ?></option>
												<? } ?>
											</select>
										</td>
										
										<td>
											<select name="display_in_account">
												<?php if ($display_in_account) { ?>
													<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php } else { ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</td>

										<td>
											<input type="text" name="total" value="<?php echo $total; ?>" />
										</td>
										<td>
											<input type="text" name="min_currency" value="<?php if (isset($min_currency)) echo $min_currency; ?>" size="5" />&nbsp;&nbsp;	
											<? foreach ($currencies as $c) { ?>
												<? echo $c['code']; ?>&nbsp;							
											<? } ?>
										</td>

										<td>
											<select name="random">
												<?php if ($random) { ?>
													<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php } else { ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</td>
										<td>
											<input type="text" name="random_string" value="<?php echo $random_string; ?>" />
										</td>
											
									</tr>
								</table>
							</td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td colspan="4">
								<table>
									<tr>
										<td>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Скидка фикс или процент</span>
										</td>										

										<td>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Валюта скидки, если фикс</span>
										</td>															
									</tr>
									<tr>							
										<td>
											<input type="text" name="discount" value="<?php echo $discount; ?>" />
										</td>
										<td>
											<input type="text" name="currency" value="<?php if (isset($currency)) echo $currency; ?>" size="5" />&nbsp;&nbsp;	
											<? foreach ($currencies as $c) { ?>
												<? echo $c['code']; ?>&nbsp;							
											<? } ?>
										</td>											
									</tr>
								</table>
							</td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td colspan="2"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Скидка, зависящая от цены</span>
								<br /><span class="help">Зависит от выбора фикс/процент. Например, 10000:1000,20000:2000! <b>Несовместимо к применению к отдельным товарам!</b></span>
							</td>
							<td colspan="2">
								<textarea name="discount_sum" cols="60" rows="2"><?php if (isset($discount_sum)) echo $discount_sum; ?></textarea>
							</td>
						</tr>

						<tr>
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Товары</span>
								<br /><span class="help">Конкретные товары, к которым применим промокод</span>
							</td>
							<td><input type="text" name="product" value="" style="width:200px;" /></td>
							
							
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Категории</span>
								<br /><span class="help">Выбрать все товары в выбранной категории</span>
							</td>
							<td><input type="text" name="category" value="" style="width:200px;" /></td>
							
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><div id="coupon-product" class="scrollbox">
								<?php $class = 'odd'; ?>
								<?php foreach ($coupon_product as $coupon_product) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div id="coupon-product<?php echo $coupon_product['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_product['name']; ?><img src="view/image/delete.png" alt="" />
										<input type="hidden" name="coupon_product[]" value="<?php echo $coupon_product['product_id']; ?>" />
									</div>
								<?php } ?>
							</div></td>
							
							<td>&nbsp;</td>
							<td><div id="coupon-category" class="scrollbox">
								<?php $class = 'odd'; ?>
								<?php foreach ($coupon_category as $coupon_category) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div id="coupon-category<?php echo $coupon_category['category_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_category['name']; ?><img src="view/image/delete.png" alt="" />
										<input type="hidden" name="coupon_category[]" value="<?php echo $coupon_category['category_id']; ?>" />
									</div>
								<?php } ?>
							</div></td>
						</tr>
						
						<tr>
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Коллекции</span> <br /><span class="help">Выбрать все товары коллекции.</span></td>
							<td><input type="text" name="collection" value="" style="width:200px;" /></td>
							
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Бренды</span>
							<br /><span class="help">Выбрать все товары бренда.</span></td>
							<td><input type="text" name="manufacturer" value="" style="width:200px;" /></td>
							
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><div id="coupon-collection" class="scrollbox">
								<?php $class = 'odd'; ?>
								<?php foreach ($coupon_collection as $coupon_collection) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div id="coupon-collection<?php echo $coupon_collection['collection_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_collection['name']; ?><img src="view/image/delete.png" alt="" />
										<input type="hidden" name="coupon_collection[]" value="<?php echo $coupon_collection['collection_id']; ?>" />
									</div>
								<?php } ?>
							</div></td>
							
							
							<td>&nbsp;</td>
							<td><div id="coupon-manufacturer" class="scrollbox">
								<?php $class = 'odd'; ?>
								<?php foreach ($coupon_manufacturer as $coupon_manufacturer) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div id="coupon-manufacturer<?php echo $coupon_manufacturer['manufacturer_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_manufacturer['name']; ?><img src="view/image/delete.png" alt="" />
										<input type="hidden" name="coupon_manufacturer[]" value="<?php echo $coupon_manufacturer['manufacturer_id']; ?>" />
									</div>
								<?php } ?>
							</div></td>
							
						</tr>  
						
						
						<tr style="border-bottom:1px dashed gray">
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Привязка акции</span></td>
							<td colspan="3">	
								<select name="action_id">
									<option value="0">Выбери акцию, в которой используется этот промокод</option>
									<? foreach ($actions as $action) { ?>
										<? if ($action['actions_id'] == $action_id) { ?>
											<option value="<? echo $action['actions_id'] ?>" selected="selected"><? echo $action['caption'] ?></option>
											<? } else { ?>
											<option value="<? echo $action['actions_id'] ?>"><? echo $action['caption'] ?></option>
										<? } ?>
									<? } ?>
								</select>
								<br />
								<span class="help">
									<i class="fa fa-info-circle"></i> Если есть текстовое описание акции, и она привязана, то в каталоге на товарах, на которые действует промокод, будет выводиться пояснение и лейбл из этой акции<br />
									<i class="fa fa-info-circle"></i> Также в случая прямой привязки товаров к акции, купон действует и на них
								</span>	
							</td>		
						</tr>
						
						
						<tr>
							<td>
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><i class="fa fa-birthday-cake" aria-hidden="true"></i> День Рождения</span>
							</td>
							<td>	
								<select name="birthday">
									<?php if ($birthday) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
								<br />
								<span class="help">используется одним покупателем не более чем раз в 365 дней</span>
							</td>
							
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Дней с момента отправки</span></td>
							<td><input type="text" name="days_from_send" value="<?php echo $days_from_send; ?>" /></td>
						</tr>
						<tr  style="border-bottom:1px dashed gray">
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Привязка шаблона рассылки</span></td>
							<td colspan="3">	
								<select name="actiontemplate_id">
									<option value="0">Выбери акционное предложение из шаблонов рассылки</option>
									<? foreach ($actiontemplates as $actiontemplate) { ?>
										<? if ($actiontemplate['actiontemplate_id'] == $actiontemplate_id) { ?>
											<option value="<? echo $actiontemplate['actiontemplate_id'] ?>" selected="selected"><? echo $actiontemplate['title'] ?></option>
											<? } else { ?>
											<option value="<? echo $actiontemplate['actiontemplate_id'] ?>"><? echo $actiontemplate['title'] ?></option>
										<? } ?>
									<? } ?>
								</select>
								<br />
								<span class="help">Работает вместе с "дней с момента отправки"</span>	
							</td>		
						</tr>
						
						<tr>
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF">Авторизация клиента</span></td>
							<td><?php if ($logged) { ?>
								<input type="radio" name="logged" value="1" checked="checked" />
								<?php echo $text_yes; ?>
								<input type="radio" name="logged" value="0" />
								<?php echo $text_no; ?>
								<?php } else { ?>
								<input type="radio" name="logged" value="1" />
								<?php echo $text_yes; ?>
								<input type="radio" name="logged" value="0" checked="checked" />
								<?php echo $text_no; ?>
							<?php } ?></td>
							
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF"><?php echo $entry_shipping; ?></span></td>
							<td><?php if ($shipping) { ?>
								<input type="radio" name="shipping" value="1" checked="checked" />
								<?php echo $text_yes; ?>
								<input type="radio" name="shipping" value="0" />
								<?php echo $text_no; ?>
								<?php } else { ?>
								<input type="radio" name="shipping" value="1" />
								<?php echo $text_yes; ?>
								<input type="radio" name="shipping" value="0" checked="checked" />
								<?php echo $text_no; ?>
							<?php } ?></td>
						</tr>
						
						<tr>
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF"><?php echo $entry_date_start; ?></span></td>
							<td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date-start" /></td>
							
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF"><?php echo $entry_date_end; ?></span></td>
							<td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date-end" /></td>
							
						</tr>
						<tr>
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF">Количество применений купона</span>
								<br /><span class="help">Сколько раз максимально может использоваться купон. Для бесконечного использования оставить пустым</span>
							</td>
							<td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" /></td>
							
							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF">Количество применений одним покупателем</span>
								<br /><span class="help">Сколько раз максимально может использоваться купон одним покупателем. Для бесконечного использования оставить пустым</span>
							</td>
							<td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>
							
						</tr>
					</table>
				</div>
				
				<div id="tab-data">
					
					<div id="languages" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td>Полное или длинное название</td>
									<td>
									<input type="text" name="coupon_description[<?php echo $language['language_id']; ?>][full_name]" size="255" style="width:450px" value="<?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['full_name'] : ''; ?>" />
									<br />
									<span class="help">будет отображаться в личном кабинете в разделе промокоды</span>
									</td>
								</tr>
								<tr>
									<td>Короткое описание</td>
									<td>
										<textarea rows='10' cols='100' name="coupon_description[<?php echo $language['language_id']; ?>][short_description]" id="short_description<?php echo $language['language_id']; ?>"><?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['short_description'] : ''; ?></textarea>
									<br />
									<span class="help">будет отображаться в личном кабинете в разделе промокоды</span>
									</td>
								</tr>
								<tr>
									<td>Описание (пока неизвестно для чего)</td>
									<td>
									<textarea name="coupon_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['description'] : ''; ?></textarea>
									
									<br />
									<span class="help">пока неизвестно для чего, возможно где-то выводить</span>
									</td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
				
				
				<?php if ($coupon_id) { ?>
					<div id="tab-history">
						<div id="history"></div>
					</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	$('input[name=\'category[]\']').bind('change', function() {
		var filter_category_id = this;
		
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_category_id=' +  filter_category_id.value + '&limit=10000',
			dataType: 'json',
			success: function(json) {
				for (i = 0; i < json.length; i++) {
					if ($(filter_category_id).attr('checked') == 'checked') {
						$('#coupon-product' + json[i]['product_id']).remove();
						
						$('#coupon-product').append('<div id="coupon-product' + json[i]['product_id'] + '">' + json[i]['name'] + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="coupon_product[]" value="' + json[i]['product_id'] + '" /></div>');
						} else {
						$('#coupon-product' + json[i]['product_id']).remove();
					}			
				}
				
				$('#coupon-product div:odd').attr('class', 'odd');
				$('#coupon-product div:even').attr('class', 'even');			
			}
		});
	});
	
	$('input[name=\'product\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#coupon-product' + ui.item.value).remove();
			
			$('#coupon-product').append('<div id="coupon-product' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="coupon_product[]" value="' + ui.item.value + '" /></div>');
			
			$('#coupon-product div:odd').attr('class', 'odd');
			$('#coupon-product div:even').attr('class', 'even');
			
			$('input[name=\'product\']').val('');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#coupon-product div img').live('click', function() {
		$(this).parent().remove();
		
		$('#coupon-product div:odd').attr('class', 'odd');
		$('#coupon-product div:even').attr('class', 'even');	
	});
	
	
	$('input[name=\'category\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.category_id
						}
					}));
				}
			});
			
		}, 
		select: function(event, ui) {
			$('#coupon-category' + ui.item.value).remove();
			
			$('#coupon-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="coupon_category[]" value="' + ui.item.value + '" /></div>');
			
			$('#coupon-category div:odd').attr('class', 'odd');
			$('#coupon-category div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#coupon-category div img').live('click', function() {
		$(this).parent().remove();
		
		$('#coupon-category div:odd').attr('class', 'odd');
		$('#coupon-category div:even').attr('class', 'even');	
	});
	
	$('input[name=\'collection\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/collection/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.collection_id
						}
					}));
				}
			});
			
		}, 
		select: function(event, ui) {
			$('#coupon-collection' + ui.item.value).remove();
			
			$('#coupon-collection').append('<div id="product-collection' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="coupon_collection[]" value="' + ui.item.value + '" /></div>');
			
			$('#coupon-collection div:odd').attr('class', 'odd');
			$('#coupon-collection div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#coupon-collection div img').live('click', function() {
		$(this).parent().remove();
		
		$('#coupon-collection div:odd').attr('class', 'odd');
		$('#coupon-collection div:even').attr('class', 'even');	
	});
	
	
	$('input[name=\'manufacturer\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.manufacturer_id
						}
					}));
				}
			});
			
		}, 
		select: function(event, ui) {
			$('#coupon-manufacturer' + ui.item.value).remove();
			
			$('#coupon-manufacturer').append('<div id="product-manufacturer' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="coupon_manufacturer[]" value="' + ui.item.value + '" /></div>');
			
			$('#coupon-manufacturer div:odd').attr('class', 'odd');
			$('#coupon-manufacturer div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#coupon-manufacturer div img').live('click', function() {
		$(this).parent().remove();
		
		$('#coupon-manufacturer div:odd').attr('class', 'odd');
		$('#coupon-manufacturer div:even').attr('class', 'even');	
	});
	
//--></script> 
<script type="text/javascript"><!--
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
//--></script>
<?php if ($coupon_id) { ?>
	<script type="text/javascript"><!--
		$('#history .pagination a').live('click', function() {
			$('#history').load(this.href);
			
			return false;
		});			
		
		$('#history').load('index.php?route=sale/coupon/history&token=<?php echo $token; ?>&coupon_id=<?php echo $coupon_id; ?>');
	//--></script>
<?php } ?>
<script type="text/javascript"><!--
	$('#tabs a').tabs(); 
	$('#languages a').tabs();
//--></script> 

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
	<?php foreach ($languages as $language) { ?>
		CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		});
		
		CKEDITOR.replace('short_description<?php echo $language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		});
	<?php } ?>
//--></script>
<?php echo $footer; ?>		