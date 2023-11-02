	<div id="tab-local">
		<table class="form">
			<tr>
				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Страна</span></p>

					<select name="config_country_id">
						<?php foreach ($countries as $country) { ?>
							<?php if ($country['country_id'] == $config_country_id) { ?>
								<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Страна текстом</span></p>
					<input type="text" name="config_countryname" value="<?php echo $config_countryname; ?>" size="30" />
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Режим одной страны</span></p>

					<select name="config_only_one_store_and_country">
						<?php if ($config_only_one_store_and_country) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Геозона</span></p>

					<select name="config_zone_id">
						<?php foreach ($zones as $zone) { ?>
							<?php if ($zone['zone_id'] == $config_zone_id) { ?>
								<option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
							<?php } ?>
						<?php } ?>

					</select>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Google Merchant Local</span></p>
					<input type="text" name="config_googlelocal_code" value="<?php echo $config_googlelocal_code; ?>" size="6" />
					<span class="help">Шесть случайных цифр. При изменении сообщить @rayua</span>
				</td>
			</tr>

			<tr>
				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Cтолица</span></p>
					<input type="text" name="config_default_city" value="<?php echo $config_default_city; ?>" size="20" />
				</td>

				<?php foreach ($languages as $language) { ?>								
					<td style="width:20%;">	
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Столица <?php echo $language['code']; ?></span></p>
						<input type="text" name="config_default_city_<?php echo $language['code']; ?>" value="<?php echo ${'config_default_city_' . $language['code']}; ?>" size="20" />							
					</td>													
				<?php } ?>
			</tr>

			<tr>
				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Язык фронта</span></p>
					<select name="config_language">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $config_language) { ?>
								<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Второй язык фронта</span></p>
					<select name="config_second_language">
						<option value="" <?php if ($config_second_language == '') { ?>selected="selected"<?php } ?>>Не использовать</option>
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $config_second_language) { ?>
								<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Язык админки</span></p>
					<select name="config_admin_language">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $config_admin_language) { ?>
								<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Язык поставщика</span></p>
					<select name="config_de_language">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $config_de_language) { ?>
								<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Активные способы оплаты</span></p>
					<textarea name="config_payment_list" cols="40" rows="8"><?php echo $config_payment_list; ?></textarea>
					<br /><span class="help">выводятся в карте товара, по одному в строке</span>
				</td>
			</tr>	
		</table>						

		<h2>Переводить или копировать</h2>
		<table>
			<tr>
				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Переводить эти языки с RU</span></p>
					<div class="scrollbox" style="height:100px; width:200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($languages as $language) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (!empty($config_translate_from_ru) && in_array($language['code'], $config_translate_from_ru)) { ?>
									
									<input id="config_translate_from_ru_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_translate_from_ru[]" value="<?php echo $language['code']; ?>" checked="checked" />
									<label for="config_translate_from_ru_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } else { ?>
									
									<input id="config_translate_from_ru_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_translate_from_ru[]" value="<?php echo $language['code']; ?>" />
									<label for="config_translate_from_ru_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Переводить эти языки с DE</span></p>
					<div class="scrollbox" style="height:100px; width:200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($languages as $language) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (!empty($config_translate_from_de) && in_array($language['code'], $config_translate_from_de)) { ?>
									
									<input id="config_translate_from_de_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_translate_from_de[]" value="<?php echo $language['code']; ?>" checked="checked" />
									<label for="config_translate_from_de_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } else { ?>
									
									<input id="config_translate_from_de_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_translate_from_de[]" value="<?php echo $language['code']; ?>" />
									<label for="config_translate_from_de_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Переводить эти языки с UK</span></p>
					<div class="scrollbox" style="height:100px; width:200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($languages as $language) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (!empty($config_translate_from_uk) && in_array($language['code'], $config_translate_from_uk)) { ?>
									
									<input id="config_translate_from_uk_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_translate_from_uk[]" value="<?php echo $language['code']; ?>" checked="checked" />
									<label for="config_translate_from_uk_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } else { ?>
									
									<input id="config_translate_from_uk_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_translate_from_uk[]" value="<?php echo $language['code']; ?>" />
									<label for="config_translate_from_uk_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>

				<td style="width:20%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Изменять все эти языки вместе</span></p>
					<div class="scrollbox" style="height:100px; width:200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($languages as $language) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (!empty($config_edit_simultaneously) && in_array($language['code'], $config_edit_simultaneously)) { ?>
									
									<input id="config_edit_simultaneously_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_edit_simultaneously[]" value="<?php echo $language['code']; ?>" checked="checked" />
									<label for="config_edit_simultaneously_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } else { ?>
									
									<input id="config_edit_simultaneously_<?php echo $language['code']; ?>" class="checkbox" type="checkbox" name="config_edit_simultaneously[]" value="<?php echo $language['code']; ?>" />
									<label for="config_edit_simultaneously_<?php echo $language['code']; ?>"><?php echo $language['code']; ?></label>
									
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>
			</tr>

		</table>						

		<h2>Валюты</h2>
		<table>	
			<tr>
				<td style="width:30%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Основная валюта</span></p>
					<select name="config_currency">
						<?php foreach ($currencies as $currency) { ?>
							<?php if ($currency['code'] == $config_currency) { ?>
								<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<span class="help">основная валюта, в которой задаются цены</span>
				</td>

				<td style="width:30%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Валюта фронта</span></p>
					<select name="config_regional_currency">
						<?php foreach ($currencies as $currency) { ?>
							<?php if ($currency['code'] == $config_regional_currency) { ?>
								<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<span class="help">Валюта, в которой отображаются цены в региональном магазине</span>
				</td>

				<td style="width:30%;">
					
				</td>

			</tr>
		</table>						

		<h2>Единицы измерения</h2>
		<table>
			<tr>
				<td style="width:25%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Единица длины</span></p>
					<select name="config_length_class_id">
						<?php foreach ($length_classes as $length_class) { ?>
							<?php if ($length_class['length_class_id'] == $config_length_class_id) { ?>
								<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>

				<td style="width:30%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Конвертировать эти единицы на фронте</span></p>
					<div class="scrollbox" style="height:200px; width:200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($length_classes as $length_class) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (!empty($config_convert_lengths_class_id) && in_array($length_class['length_class_id'], $config_convert_lengths_class_id)) { ?>
									
									<input id="config_convert_lengths_class_id_<?php echo $length_class['length_class_id']; ?>" class="checkbox" type="checkbox" name="config_convert_lengths_class_id[]" value="<?php echo $length_class['length_class_id']; ?>" checked="checked" />
									<label for="config_convert_lengths_class_id_<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></label>
									
								<?php } else { ?>
									
									<input id="config_convert_lengths_class_id_<?php echo $length_class['length_class_id']; ?>" class="checkbox" type="checkbox" name="config_convert_lengths_class_id[]" value="<?php echo $length_class['length_class_id']; ?>" />
									<label for="config_convert_lengths_class_id_<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></label>
									
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>

				<td style="width:25%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Единица веса</span></p>
					<select name="config_weight_class_id">
						<?php foreach ($weight_classes as $weight_class) { ?>
							<?php if ($weight_class['weight_class_id'] == $config_weight_class_id) { ?>
								<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>

				<td style="width:30%;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Конвертировать эти единицы на фронте</span></p>
					<div class="scrollbox" style="height:200px; width:200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($weight_classes as $weight_class) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (!empty($config_convert_weights_class_id) && in_array($weight_class['weight_class_id'], $config_convert_weights_class_id)) { ?>
									
									<input id="config_convert_weights_class_id_<?php echo $weight_class['weight_class_id']; ?>" class="checkbox" type="checkbox" name="config_convert_weights_class_id[]" value="<?php echo $weight_class['weight_class_id']; ?>" checked="checked" />
									<label for="config_convert_weights_class_id_<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></label>
									
								<?php } else { ?>
									
									<input id="config_convert_weights_class_id_<?php echo $weight_class['weight_class_id']; ?>" class="checkbox" type="checkbox" name="config_convert_weights_class_id[]" value="<?php echo $weight_class['weight_class_id']; ?>" />
									<label for="config_convert_weights_class_id_<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></label>
									
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>
			</tr>
		</table>
	</div>