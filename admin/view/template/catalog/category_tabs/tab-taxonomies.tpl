<div id="tab-taxonomies">
	<h2>Google, Priceva/PriceContol</h2>
	<table class="form">						
		<tr>
			<td style="width:40%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Таксономия FB, Google</span>
			</td>
			<td style="width:20%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Отдельный фид</span>
			</td>
			<td style="width:20%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Исключить из общего фида</span>
			</td>
			<td style="width:20%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Мониторинг цен Priceva/PriceContol</span>
			</td>
		</tr>
		<tr style="border-bottom:1px dashed gray">
			<td>
				<input type="text" name="google_category" value="<?php echo $google_category; ?>" style="width:90%" />
				<input type="hidden" name="google_category_id" value="<?php echo $google_category_id; ?>" />
				<span class="help">Только если магазин использует логику с присвоениями refeedmaker v1</span>
			</td>
			<td>
				<select name="separate_feeds">
					<?php if ($separate_feeds) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
			</td>
			<td>
				<select name="no_general_feed">
					<?php if ($no_general_feed) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
				<span class="help">Безусловно исключает категорию и все ее дочерние из фидов (для обеих версий refeedmaker)</span>
			</td>

			<td>
				<select name="priceva_enable">
					<?php if ($priceva_enable) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
			</td>

		</tr>
	</table>

	<?php if ($this->config->get('config_country_id') == 220) { ?>
		<h2>Hotline</h2>
		<table class="form">
			<tr>
				<td style="width:60%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сопоставление категории Hotline</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><a href="https://hotline.ua/download/hotline/hotline_tree_uk.csv" style="color:#FFF;text-decoration:none">Нажми сюда, чтоб скачать полный список категорий Хотлайн в CSV</a></span>
				</td>
				<td style="width:40%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить выгрузку на Hotline</span>
				</td>
			</tr>

			<tr style="border-bottom:1px dashed gray">
				<td style="width:60%">
					<input type="text" name="hotline_category_name" value="<?php echo $hotline_category_name; ?>" style="width:90%;" />

					<br /><span  style="color:#00ad07"><i class="fa fa-info-circle"></i> Хотлайн крайне рекомендует использовать только конечные категории, помеченные маркером [FINAL]</span>
				</td>

				<td>
					<select name="hotline_enable">
						<?php if ($hotline_enable) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			<script type="text/javascript">
				$('input[name=\'hotline_category_name\']').autocomplete({
					delay: 500,
					source: function(request, response) {		
						$.ajax({
							url: 'index.php?route=catalog/category/hotline_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {
								response($.map(json, function(item) {
									return {
										label: item.name,
										value: item.name2
									}
								}));
							}
						});
					},
					select: function(event, ui) {
						$('input[name=\'hotline_category_name\']').val(ui.item.value);

						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
			</script>
		</table>
	<?php } ?>


	<?php if ($this->config->get('config_country_id') == 176) { ?>	
		<table class="form">
			<tr>
				<td style="width:100%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сопоставление категории Yandex Market</span>
				</td>
			</tr>

			<tr style="border-bottom:1px dashed gray">
				<td style="width:100%">
					<input type="text" name="yandex_category_name" value="<?php echo $yandex_category_name; ?>" style="width:90%;" />

					<br /><span  style="color:#00ad07"><i class="fa fa-info-circle"></i> Яндекс крайне рекомендует использовать только конечные категории, помеченные маркером [FINAL], документация тут: https://yandex.ru/support/partnermarket-dsbs/elements/categories.html</span>
				</td>
			</tr>

			<script type="text/javascript">
				$('input[name=\'yandex_category_name\']').autocomplete({
					delay: 500,
					source: function(request, response) {		
						$.ajax({
							url: 'index.php?route=catalog/category/yandex_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {
								response($.map(json, function(item) {
									return {
										label: item.name,
										value: item.name2
									}
								}));
							}
						});
					},
					select: function(event, ui) {
						$('input[name=\'yandex_category_name\']').val(ui.item.value);

						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
			</script>

		</table>
	<?php } ?>
</div>

	<script type="text/javascript">
		$('input[name=\'google_category\']').autocomplete({
			delay: 500,
			source: function(request, response) {		
				$.ajax({
					url: 'index.php?route=catalog/category/google_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {
						json.unshift({
							'google_base_category_id':  0,
							'name':  '<?php echo $text_none; ?>'
						});
						
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.google_base_category_id
							}
						}));
					}
				});
			},
			select: function(event, ui) {
				$('input[name=\'google_category\']').val(ui.item.label);
				$('input[name=\'google_category_id\']').val(ui.item.value);
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	</script> 