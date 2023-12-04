<div id="tab-general">
	<div id="languages" class="htabs">
		<?php foreach ($languages as $language) { ?>
			<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
		<?php } ?>
		<div class="clr"></div>
	</div>

	<?php foreach ($languages as $language) { ?>
		<div id="language<?php echo $language['language_id']; ?>">
			<table class="form">
				<tr>
					<td><span class="required">*</span> <?php echo $entry_name; ?></td>
					<td>
						<div class="translate_wrap">
							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } ?>

							<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
							<?php } ?>

							<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
							<?php } ?>

							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>&nbsp;

								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>&nbsp;

								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>											

						<input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />											

						<select name="product_description[<?php echo $language['language_id']; ?>][translated]">
							<?php if (isset($product_description[$language['language_id']]) && $product_description[$language['language_id']]['translated']) { ?>
								<option value="1" selected="selected">Переведено</option>
								<option value="0">Надо переводить</option>
							<? } else { ?>
								<option value="1">Переведено</option>
								<option value="0" selected="selected">Надо переводить</option>
							<?php } ?>
						</select>

						<span class="error"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?></span>

						<?php if (isset($error_name[$language['language_id']])) { ?>
							<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
						<?php } ?>
					</td>
				</tr>

				<tr>
					<td>Короткое название<br /><span class="help">макс. 50 символов</span></td>
					<td>
						<div class="translate_wrap">
							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } ?>

							<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
							<?php } ?>

							<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
							<?php } ?>

							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>&nbsp;

								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>&nbsp;

								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<input type="text" name="product_description[<?php echo $language['language_id']; ?>][short_name_d]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['short_name_d'] : ''; ?>" /></td>
					</tr>	

					<tr>
						<td>Имя опции</td>
						<td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name_of_option]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name_of_option'] : ''; ?>" />
							<br /><span class="help">Этот товар является виртуальной опцией какого-либо другого товара с названием, указанного в вкладке "Данные" в поле "Код Группы" с названием опции &uarr;</span>					
						</td>
					</tr>

					<tr>
						<td>Название производителя</td>
						<td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][manufacturer_name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['manufacturer_name'] : ''; ?>" />
							<br /><span class="help">если производитель создан и товар привязан к нему, то это поле будет перезаписано</span>					
						</td>
					</tr>

					<tr>
						<td><?php echo $entry_seo_title; ?> <br /><span id='title_lngth'></span> смв.</td>
						<td>
							<div class="translate_wrap">
								<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

								<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
								<?php } ?>

								<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
								<?php } ?>

								<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
								<?php } ?>

								<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
										Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
									</a>&nbsp;

									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
										Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
									</a>&nbsp;

									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
										Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
									</a>
								<?php } ?>
							</div>
							<input type="text" name="product_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" onKeyDown="$('#title_lngth').html($(this).val().length);" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['seo_title'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td><?php echo $entry_seo_h1; ?></td>
						<td>
							<div class="translate_wrap">
								<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

								<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
								<?php } ?>

								<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
								<?php } ?>

								<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
								<?php } ?>

								<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
										Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
									</a>&nbsp;

									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
										Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
									</a>&nbsp;

									<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
										Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
									</a>
								<?php } ?>
							</div>
							<input type="text" name="product_description[<?php echo $language['language_id']; ?>][seo_h1]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['seo_h1'] : ''; ?>" />
						</td>
					</tr>
					<tr>
						<td><?php echo $entry_meta_description; ?></td>
						<td>
							<div class="translate_wrap">
								<div class="translate_wrap">
									<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

									<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
									<?php } ?>

									<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
									<?php } ?>

									<?php if ($this->config->get('config_translate_from_uk') &&  in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
									<?php } ?>

									<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
											Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</a>&nbsp;

										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
											Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</a>&nbsp;

										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
											Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</a>
									<?php } ?>
								</div>
								<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="200" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_meta_keyword; ?></td>
							<td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="200" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
						</tr>
						<tr>
							<td><?php echo $entry_description; ?></td>
							<td>
								<div class="translate_wrap">
									<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

									<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
									<?php } ?>

									<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
									<?php } ?>

									<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
									<?php } ?>

									<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
											Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</a>&nbsp;

										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
											Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</a>&nbsp;

										<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
											Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										</a>
									<?php } ?>
								</div>
								<textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
							</td>
						</tr>
						<?php if ($this->config->get('config_rainforest_description_symbol_limit')) { ?>	
							<tr>
								<td>Полное описание</td>
								<td>
									<div class="translate_wrap">
										<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

										<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
											<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
										<?php } ?>

										<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
											<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
										<?php } ?>

										<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
											<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
										<?php } ?>

										<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
											<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
												Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
											</a>&nbsp;

											<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
												Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
											</a>&nbsp;

											<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
												Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
											</a>
										<?php } ?>
									</div>
									<textarea name="product_description[<?php echo $language['language_id']; ?>][description_full]" id="description_full<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description_full'] : ''; ?></textarea>
								</td>
							</tr>
						<?php } ?>	
						<tr>
							<td><?php echo $entry_tag; ?></td>
							<td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" size="80" /></td>
						</tr>
					</table>
				</div>
			<?php } ?>
		</div>