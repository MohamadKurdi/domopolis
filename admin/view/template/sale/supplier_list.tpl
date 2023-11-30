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
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/language.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<table style="width: 100%;">
				<tbody>
					<tr class="filter f_top">
						<td class="left">
							<p><i class="fa fa-user"></i> Поставщик</p>
							<input type="text" name="filter_supplier_name" value="<?php echo $filter_supplier_name; ?>" /><br />
						</td>
						<td class="left">
							<p><i class="fa fa-user"></i> Страна</p>
							<input type="text" name="filter_supplier_country" value="<?php echo $filter_supplier_country; ?>" /><br />
						</td>
						<td class="left">
							<p><i class="fa fa-star"></i> Рейтинг больше чем (*10)</p>
							<input type="number" step="10" name="filter_rating_from" value="<?php echo $filter_rating_from; ?>" /><br />
						</td>
						<td class="left">
							<p><i class="fa fa-edit"></i> Отзывов больше чем</p>
							<input type="number" step="10" name="filter_reviews_from" value="<?php echo $filter_reviews_from; ?>" /><br />
						</td>
						<td class="left">
							<div style="margin-top:5px;">
								<input id="checkbox_14" class="checkbox" type="checkbox" name="filter_has_telephone" <? if ($filter_has_telephone) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_14" style="color:#00AD07;"><i class="fa fa-phone" style="color: #00AD07;"></i>&nbsp;Есть телефон</label>								
							</div>

							<div style="margin-top:5px;">
								<input id="checkbox_15" class="checkbox" type="checkbox" name="filter_has_email" <? if ($filter_has_email) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_15" style="color:#00AD07;"><i class="fa fa-envelope" style="color: #00AD07;"></i>&nbsp;Есть почта</label>								
							</div>
						</td>
						<td class="left">
							<div style="margin-top:5px;">
								<input id="checkbox_16" class="checkbox" type="checkbox" name="filter_has_vat" <? if ($filter_has_vat) { ?>checked="checked"<? } ?> value="1" /> 
								<label for="checkbox_16" style="color:#00AD07;"><i class="fa fa-amazon" style="color: #00AD07;"></i>&nbsp;Есть VAT</label>								
							</div>

						</td>
						<td align="right">
							<p>	&#160;</p>
							<a onclick="filter();" class="button">Фильтр</a>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="filter_bord"></div>
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left" width="1">
								ID
							</td>
							<td class="left">
								<?php if ($sort == 'name') { ?>
									<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Поставщик</a>
								<?php } else { ?>
									<a href="<?php echo $sort_name; ?>">Поставщик</a>
								<?php } ?>
							</td>
							<td class="left">Страна</td>
							<td class="left">Native</td>
							<td class="left">Рейтинг</td>
							<td class="left">Отзывов</td>
							<td class="left">Офферов</td>
							<td class="left">Business Name</td>
							<td class="left">VAT</td>
							<td class="left">Телефон</td>
							<td class="left">Мейл</td>
							<td class="left">Хороший</td>
							<td class="left">Плохой</td>
							<td class="left">Коэфф</td>
							<td class="left">Фид</td>
							<td class="left">Нал</td>
							<td class="left">Цены</td>
							<td class="right">
								<?php if ($sort == 'sort_order') { ?>
								<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>">Сортировка</a>
							<?php } else { ?>
								<a href="<?php echo $sort_sort_order; ?>">Сортировка</a>
								<?php } ?>
							</td>
							<td class="right"><?php echo $column_action; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php if ($suppliers) { ?>
								<?php foreach ($suppliers as $supplier) { ?>
									<tr>
										<td style="text-align: center;">
											<?php if ($supplier['selected']) { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" checked="checked" />
											<?php } else { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" />
											<?php } ?>
										</td>
										<td class="left">
											<b><?php echo $supplier['supplier_id']; ?></b>
										</td>        

										<td class="left">
											<span style="font-size:16px;"><b><?php echo $supplier['supplier_name']; ?></b></span>
											<br /><span style="font-size:10px;"><?php echo $supplier['supplier_type']; ?></span>
											<br /><span style="font-size:10px;"><?php echo $supplier['business_type']; ?></span>
										</td>

										<td class="center" style="font-size:11px;">
											<? if ($supplier['supplier_country']) { ?>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $supplier['supplier_country']; ?></span>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center">
											<? if ($supplier['is_native']) { ?>
												<i class="fa fa-check-circle" style="color:#4ea24e"></i>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center" style="white-space:nowrap;">
											<?php if ($supplier['rating']) { ?>										
												<?php if ($supplier['rating'] > 4.5) { ?>
													<span style="color:#4ea24e"><i class="fa fa-star"></i> <?php echo $supplier['rating']; ?></span>
												<?php } else { ?>
													<span style="color:#cf4a61"><i class="fa fa-star"></i> <?php echo $supplier['rating']; ?></span>
												<?php } ?>		
											<?php } ?>
										</td>

										<td class="center" style="white-space:nowrap;">
											<?php if ($supplier['reviews']) { ?>				
												<?php if ($supplier['reviews'] > 500) { ?>
													<span style="color:#4ea24e"><i class="fa fa-edit"></i><?php echo $supplier['reviews']; ?></span>
												<?php } else { ?>
													<span style="color:#cf4a61"><i class="fa fa-edit"></i><?php echo $supplier['reviews']; ?></span>
												<?php } ?>
											<?php } ?>
										</td>

										<td class="center">
											<? if ($supplier['total_offers']) { ?>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF"><?php echo $supplier['total_offers']; ?></span>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center">
											<? if ($supplier['business_name']) { ?>
												<small><?php echo $supplier['business_name']; ?></small>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center" style="padding:8px;">
											<? if ($supplier['vat_number']) { ?>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $supplier['vat_number']; ?></span>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center" style="padding:8px;">
											<? if ($supplier['telephone']) { ?>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF"><?php echo $supplier['telephone']; ?></span>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center" style="padding:8px;">
											<? if ($supplier['email']) { ?>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF"><?php echo $supplier['email']; ?></span>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center" style="padding:8px;">
											<? if ($supplier['amzn_good']) { ?>
												<i class="fa fa-check-circle" style="color:#00AD07"></i>
											<? } else { ?>
												<i class="fa fa-question-circle" style="color:#ff7815"></i>
											<? } ?>
										</td>

										<td class="center" style="padding:8px;">
											<? if ($supplier['amzn_bad']) { ?>
												<i class="fa fa-exclamation-circle" style="color:#cf4a61"></i>
											<? } else { ?>
												<i class="fa fa-question-circle" style="color:#ff7815"></i>
											<? } ?>
										</td>												

										<td class="center" style="padding:8px;">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:black; color:#FFF">
												<?php if ($supplier['amzn_coefficient']) { ?><?php echo $supplier['amzn_coefficient'] ?><? } elseif ($supplier['amzn_good']) { ?>+10<?php } elseif ($supplier['amzn_bad']) { ?>-20<?php } else { ?>0<?php } ?>
											</span>
										</td>

										<td class="center" style="padding:8px; width:100px;">
											<? if ($supplier['path_to_feed']) { ?>
												<small><?php echo $supplier['path_to_feed']; ?></small>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>	

										<td class="center" style="padding:8px;">
											<? if ($supplier['stock']) { ?>
												<i class="fa fa-check-circle" style="color:#00AD07"></i>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center" style="padding:8px;">
											<? if ($supplier['prices']) { ?>
												<i class="fa fa-check-circle" style="color:#00AD07"></i>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>

										<td class="center">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:black; color:#FFF">
												<?php echo $supplier['sort_order']; ?>
											</span>
										</td>

										<td class="right" width="110px">
											<?php foreach ($supplier['action'] as $action) { ?>
											<a class="button" href="<?php echo $action['href']; ?>" <? if (isset($action['target'])) { ?>target="<? echo $action['target'] ?>"<? } ?>><?php echo $action['text']; ?></a>
											<?php } ?>
										</td>
										</tr>

									<?php } ?>
								<?php } else { ?>
									<tr>
										<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
					<div class="pagination"><?php echo $pagination; ?></div>
				</div>
			</div>
		</div>

		<script>

			function filter() {
				url = 'index.php?route=sale/supplier&token=<?php echo $token; ?>';

				var filter_supplier_name = $('input[name=\'filter_supplier_name\']').prop('value');		
				if (filter_supplier_name) {
					url += '&filter_supplier_name=' + encodeURIComponent(filter_supplier_name);
				}

				var filter_supplier_country = $('input[name=\'filter_supplier_country\']').prop('value');		
				if (filter_supplier_country) {
					url += '&filter_supplier_country=' + encodeURIComponent(filter_supplier_country);
				}

				var filter_rating_from = $('input[name=\'filter_rating_from\']').prop('value');		
				if (filter_rating_from) {
					url += '&filter_rating_from=' + encodeURIComponent(filter_rating_from);
				}

				var filter_reviews_from = $('input[name=\'filter_reviews_from\']').prop('value');		
				if (filter_reviews_from) {
					url += '&filter_reviews_from=' + encodeURIComponent(filter_reviews_from);
				}

				var filter_has_telephone = $('input[name=\'filter_has_telephone\']:checked').val();		
				if (filter_has_telephone  !== undefined) {
					url += '&filter_has_telephone=1';
				}

				var filter_has_email = $('input[name=\'filter_has_email\']:checked').val();		
				if (filter_has_email  !== undefined) {
					url += '&filter_has_email=1';
				}	

				var filter_has_vat = $('input[name=\'filter_has_vat\']:checked').val();		
				if (filter_has_vat  !== undefined) {
					url += '&filter_has_vat=1';
				}			

				location = url;
			}
		</script>

		<?php echo $footer; ?>