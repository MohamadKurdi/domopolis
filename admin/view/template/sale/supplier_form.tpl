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
			<h1>
				<?php echo $heading_title; ?>
				<?php if (!empty($supplier_name)) { ?> / <?php echo $supplier_name; ?><?php } ?>
			</h1>
			<div class="buttons"><a href="<?php echo $amazon_link; ?>" target="_blank" class="button"><i class="fa fa-amazon"></i></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div style="width:100%;">
					<table class="form">
						<tr>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Наименование</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Business name</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Короткий код</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Тип поставщика</span></td>							
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Головной поставщик</span></td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="supplier_name" value="<?php echo $supplier_name; ?>" />
								<br /><span class="help">Читаемое название</span>
							</td>
							<td>
								<input type="text" name="business_name" value="<?php echo $business_name; ?>" />
								<br /><span class="help">Юридическое название (Amazon)</span>
							</td>
							<td>
								<input type="text" name="supplier_code" value="<?php echo $supplier_code; ?>" />
								<br /><span class="help">К примеру, VBOG</span>
							</td>
							<td>
								<? $_a = array(' ', 'Официальный поставщик / производитель', 'Официальный магазин сторонних поставщиков', 'Amazon', 'EBay'); ?>
								<select name="supplier_type">
									<? foreach ($_a as $_s) { ?>
										<option value="<? echo $_s; ?>" <? if ($supplier_type == $_s) { ?>selected="selected" <? } ?>><? echo $_s; ?></option>
									<? } ?>
								</select>
								<br /><span class="help">Тип поставщика. Нигде не используется</span>
							</td>							
							<td>
								<input type="hidden" name="supplier_parent_id" value="<?php echo $supplier_parent_id; ?>" style="width:300px" />
								<input name="supplier_parent" value="<? echo $supplier_parent; ?>" style="width:300px;" placeholder="Автоподбор" />
								<br /><span class="help">Головной, или родительский</span>
							</td>
						</tr>
					</table>


					<table class="form">
						<tr>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Seller ID</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Amazon Registration Number</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon VAT NUMBER</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Business Type</span></td>	
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Ccылка на Amazon</span></td>			
						</tr>

						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="amazon_seller_id" value="<?php echo $amazon_seller_id; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> Seller ID в Amazon</span>
							</td>
							<td>
								<input type="text" name="registration_number" value="<?php echo $registration_number; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> Registration Number в Amazon</span>
							</td>
							<td>
								<input type="text" name="vat_number" value="<?php echo $vat_number; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> VAT NUMBER в Amazon</span>
							</td>
							<td>
								<input type="text" name="business_type" value="<?php echo $business_type; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> Business Type в Amazon</span>
							</td>
							<td>
								<input type="text" name="store_link" value="<?php echo $store_link; ?>" />

								<?php if ($store_link) { ?>
									<br />
									<span class="help"><a href="<?php echo $store_link; ?>" target="_blank">открыть ссылку</a></span>
								<?php } ?>
							</td>

						</tr>
					</table>

					<table class="form">
						<tr>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Приоритетный Поставщик</span></td>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Плохой Поставщик</span></td>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Произвольный Коэффициент</span></td>			
						</tr>

						<tr style="border-bottom:1px dashed gray">
							<td>
								<select name="amzn_good">
									<?php if ($amzn_good) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
								<br /><span class="help"><i class="fa fa-info-circle"></i> если поставщик приоритетный, то при оценивании его предложения к рейтингу добавляется 10</span>
							</td>
							<td>
								<select name="amzn_bad">
									<?php if ($amzn_bad) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
								<br /><span class="help"><i class="fa fa-info-circle"></i> если поставщик плохой, то при оценивании его предложения от рейтинга отнимается 20</span>
							</td>
							<td>
								<input type="number" step="1" name="amzn_coefficient" value="<?php echo $amzn_coefficient; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> если нет понимания, что выбрать из предыдущих, то введи здесь коэффициент оптимизации или пессимизации рейтинга. Внимание! Для игнорирования офферов поставщика понизь рейтинг меньше 100 (например, -200, -500)</span>
							</td>
						</tr>
					</table>
					
					<table class="form">
						<tr>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Страна</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Почта</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Нативный поставщик</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Рынок</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Сортировка</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Описание / комментарий</span></td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="supplier_country" value="<?php echo $supplier_country; ?>"  />
							</td>
							<td>
								<input type="text" name="telephone" value="<?php echo $telephone; ?>"  />
							</td>
							<td>
								<input type="text" name="email" value="<?php echo $email; ?>"  />
							</td>
							<td>
								<select name="is_native">
									<?php if ($is_native) { ?>
										<option value="1" selected="selected">Да</option>
										<option value="0">Нет</option>
									<?php } else { ?>
										<option value="1">Да</option>
										<option value="0" selected="selected">Нет</option>
									<?php } ?>
								</select>								
							</td>
							<td>
								<select name="supplier_inner">
									<?php if ($supplier_inner) { ?>
										<option value="1" selected="selected">Внутренний рынок</option>
										<option value="0">Внешний рынок</option>
									<?php } else { ?>
										<option value="1">Внутренний рынок</option>
										<option value="0" selected="selected">Внешний рынок</option>
									<?php } ?>
								</select>
							</td>
							<td>
								<input type="number" step="1" name="sort_order" value="<?php echo $sort_order; ?>" size="3" />
							</td>
							
							<td>
								<textarea name="supplier_comment" cols="50" rows="3"><?php echo $supplier_comment; ?></textarea>
							</td>
						</tr>
					</table>
					
					<table class="form">
						<tr>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Коэффициент - 1</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Коэффициент - 2</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Коэффициент - 3</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Сроки доставки - 1</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Сроки доставки - 2</span></td>
						</tr>
						<tr>							
							<td>
								<input type="text" name="supplier_m_coef" value="<?php echo $supplier_m_coef; ?>" />
								<br /><span class="help">пока не используется</span>
							</td>
							
							<td>
								<input type="text" name="supplier_l_coef" value="<?php echo $supplier_l_coef; ?>" />
								<br /><span class="help">пока не используется</span>
							</td>
							
							<td>
								<input type="text" name="supplier_n_coef" value="<?php echo $supplier_n_coef; ?>" />
								<br /><span class="help">пока не используется</span>
							</td>
							
							<td>
								<input type="text" name="terms_instock" value="<?php echo $terms_instock; ?>" />
								<br /><span class="help">если есть в наличии у поставщика, дней от-до, или одно число</span>
							</td>
							
							<td>
								<input type="text" name="terms_outstock" value="<?php echo $terms_outstock; ?>" />
								<br /><span class="help">если нет в наличии у поставщика и на локальном складе, дней от-до, или одно число</span>
							</td>
						</tr>
					</table>

					<table class="form">
						<tr>
							<td width="50%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">About this Seller</span></td>
							<td width="50%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Detailed Information</span></td>
						</tr>
						<tr>
							<td>
								<textarea name="about_this_seller" style="width:90%" rows="10"><?php echo $about_this_seller; ?></textarea>
							</td>
							<td>
								<textarea name="detailed_information" style="width:90%" rows="10"><?php echo $detailed_information; ?></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div style="clear:both;"></div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('input[name=\'supplier_parent\']').autocomplete({
		delay: 500,
		source: function(request, response) {		
			$.ajax({
				url: 'index.php?route=sale/supplier/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						'supplier_id':  0,
						'supplier_name':  'Не выбрано'
					});

					response($.map(json, function(item) {
						return {
							label: item.supplier_name,
							value: item.supplier_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'supplier_parent\']').val(ui.item.label);
			$('input[name=\'supplier_parent_id\']').val(ui.item.value);

			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
</script> 
<?php echo $footer; ?>