		<div id="tab-server">
			<h2>Базовые настройки SEO</h2>

			<table class="form">
				<input type="hidden" name="config_secure" value="1" />
				<input type="hidden" name="config_shared" value="0" />
				<input type="hidden" name="config_robots" value="" />
				<tr>
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Использовать ЧПУ</span></p>
						<select name="config_seo_url">
							<?php if ($config_seo_url) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Настройка только для совместимости, никогда не отключать</span>
					</td>
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика ЧПУ</span></p>
						<select name="config_seo_url_type">
							<?php foreach ($seo_types as $seo_type) { ?>
								<?php if ($seo_type['type'] == $config_seo_url_type) { ?>
									<option value="<?php echo $seo_type['type']; ?>" selected="selected"><?php echo $seo_type['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $seo_type['type']; ?>"><?php echo $seo_type['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
						<span class="help">Настройка только для совместимости, нужно использовать seo_pro</span>
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">ЧПУ = идентификатор</span></p>
						<select name="config_seo_url_from_id">
							<?php if ($config_seo_url_from_id) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Товары p12345, категории c12345</span>
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">ЧПУ товаров с категориями</span></p>
						<select name="config_seo_url_include_path">
							<?php if ($config_seo_url_include_path) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">/category/subcategory/product (только для seo_pro)</span>
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Постфикс ЧПУ</span></p>
						<input type="text" name="config_seo_url_postfix" value="<?php echo $config_seo_url_postfix; ?>" size="10" />
						<span class="help">Например .html, (только для seo_pro)</span>
					</td>
				</tr>

				<tr>
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Генерировать ЧПУ (Highload опция)</span></p>
						<select name="config_seo_url_do_generate">
							<?php if ($config_seo_url_do_generate) { ?>
								<option value="1" selected="selected">Генерировать</option>
								<option value="0">Не генерировать</option>
							<?php } else { ?>													
								<option value="1">Генерировать</option>
								<option value="0"  selected="selected">Не генерировать</option>
							<? } ?>
						</select>
						<span class="help">Только для ЧПУ = идентификатор, позволяет при отключении значительно разгрузить базу. используется конфиг shorturlmap.json</span>										
					</td>

					<td width="20%">

					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Индексировать пагинацию категорий</span></p>
						<select name="config_index_category_pages">
							<?php if ($config_index_category_pages) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Если выключено, то page>1 закрыто noindex</span>										
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Индексировать пагинацию брендов</span></p>
						<select name="config_index_manufacturer_pages">
							<?php if ($config_index_manufacturer_pages) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Если выключено, то page>1 закрыто noindex</span>										
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Формировать сайтмапы</span></p>
						<select name="google_sitemap_status">
							<?php if ($google_sitemap_status) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Настройка только для совместимости, либо для магазина в разработке</span>										
					</td>

				</tr>

				<tr>
					<td width="20%"> 
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">301 Редирект ЧПУ</span></p>
						<select name="config_seo_url_do_redirect_to_new">
							<?php if ($config_seo_url_do_redirect_to_new) { ?>
								<option value="1" selected="selected">Переадресация</option>
								<option value="0">Нет переадресации</option>
							<?php } else { ?>													
								<option value="1">Переадресация</option>
								<option value="0"  selected="selected">Нет переадресации</option>
							<? } ?>
						</select>
						<span class="help">Делать редирект при переходе на новую логику. Нужна табличка url_alias_old</span>																				
					</td>	

					<td width="20%"> 
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">301 редирект языка</span></p>
						<select name="config_seo_url_do_redirect_to_new_with_language">
							<?php if ($config_seo_url_do_redirect_to_new_with_language) { ?>
								<option value="1" selected="selected">Да</option>
								<option value="0">Нет</option>
							<?php } else { ?>													
								<option value="1">Да</option>
								<option value="0"  selected="selected">Нет</option>
							<? } ?>
						</select>
						<span class="help">Вместе с сменой логики урлов также был изменен язык</span>																				
					</td>	

					<td width="20%"> 
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Этот язык был вторичным</span></p>
						<input type="text" name="config_seo_url_do_redirect_to_new_lang_was_second" value="<?php echo $config_seo_url_do_redirect_to_new_lang_was_second; ?>" size="10" />							
						<span class="help">Этот язык был вторичным и имел префикс, он будет редиректиться на урл без префикса</span>																				
					</td>	

					<td width="20%"> 
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Этот язык стал вторичным</span></p>
						<input type="text" name="config_seo_url_do_redirect_to_new_lang_became_second" value="<?php echo $config_seo_url_do_redirect_to_new_lang_became_second; ?>" size="10" />		
						<span class="help">Этот язык не имел префикса, а теперь имеет</span>																				
					</td>
				</tr>
			</table>

			<h2>Минификатор - продакшн</h2>
			<table class="form">
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Preload Links (от статик-субдомена)</span></p>
						<textarea name="config_preload_links" cols="100" rows="5"><?php echo $config_preload_links; ?></textarea>
					</td>
					<td width="50%">
					</td>
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Список скриптов в хедер</span></p>
						<textarea name="config_header_min_scripts" cols="100" rows="5"><?php echo $config_header_min_scripts; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Исключить скрипты из хедера</span></p>
						<textarea name="config_header_excluded_scripts" cols="100" rows="5"><?php echo $config_header_excluded_scripts; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addScript();</span>
					</td>									
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Список стилей в хедер</span></p>
						<textarea name="config_header_min_styles" cols="100" rows="5"><?php echo $config_header_min_styles; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Исключить стили из хедера</span></p>
						<textarea name="config_header_excluded_styles" cols="100" rows="5"><?php echo $config_header_excluded_styles; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addStyle();</span>
					</td>
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Список скриптов в футер</span></p>
						<textarea name="config_footer_min_scripts" cols="100" rows="5"><?php echo $config_footer_min_scripts; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Исключить скрипты из футера</span></p>
						<textarea name="config_footer_excluded_scripts" cols="100" rows="5"><?php echo $config_footer_excluded_scripts; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addScript();</span>
					</td>
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Список стилей в футер</span></p>
						<textarea name="config_footer_min_styles" cols="100" rows="5"><?php echo $config_footer_min_styles; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Исключить стили из футера</span></p>
						<textarea name="config_footer_excluded_styles" cols="100" rows="5"><?php echo $config_footer_excluded_styles; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addStyle();</span>
					</td>
				</tr>
			</table>

			<h2 style="color:#FF7815">Минификатор - режим разработки</h2>
			<table class="form">
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Preload Links (от статик-субдомена)</span></p>
						<textarea name="config_preload_links_dev" cols="100" rows="5"><?php echo $config_preload_links_dev; ?></textarea>
					</td>
					<td width="50%">
					</td>
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Список скриптов в хедер</span></p>
						<textarea name="config_header_min_scripts_dev" cols="100" rows="5"><?php echo $config_header_min_scripts_dev; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Исключить скрипты из хедера</span></p>
						<textarea name="config_header_excluded_scripts_dev" cols="100" rows="5"><?php echo $config_header_excluded_scripts_dev; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addScript();</span>
					</td>									
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Список стилей в хедер</span></p>
						<textarea name="config_header_min_styles_dev" cols="100" rows="5"><?php echo $config_header_min_styles_dev; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Исключить стили из хедера</span></p>
						<textarea name="config_header_excluded_styles_dev" cols="100" rows="5"><?php echo $config_header_excluded_styles_dev; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addStyle();</span>
					</td>
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Список скриптов в футер</span></p>
						<textarea name="config_footer_min_scripts_dev" cols="100" rows="5"><?php echo $config_footer_min_scripts_dev; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Исключить скрипты из футера</span></p>
						<textarea name="config_footer_excluded_scripts_dev" cols="100" rows="5"><?php echo $config_footer_excluded_scripts_dev; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addScript();</span>
					</td>
				</tr>
				<tr>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Список стилей в футер</span></p>
						<textarea name="config_footer_min_styles_dev" cols="100" rows="5"><?php echo $config_footer_min_styles_dev; ?></textarea>
						<span class="help">будут использоваться на всех страницах</span>
					</td>
					<td width="50%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Исключить стили из футера</span></p>
						<textarea name="config_footer_excluded_styles_dev" cols="100" rows="5"><?php echo $config_footer_excluded_styles_dev; ?></textarea>
						<span class="help">в случае если они добавлены фреймворком, $document->addStyle();</span>
					</td>
				</tr>
			</table>


			<h2>Сервер</h2>
			<table class="form">								
				<tr>
					<td><?php echo $entry_file_extension_allowed; ?></td>
					<td><textarea name="config_file_extension_allowed" cols="40" rows="5"><?php echo $config_file_extension_allowed; ?></textarea></td>
				</tr>
				<tr>
					<td><?php echo $entry_file_mime_allowed; ?></td>
					<td><textarea name="config_file_mime_allowed" cols="60" rows="5"><?php echo $config_file_mime_allowed; ?></textarea></td>
				</tr>              
				<tr>
					<td><?php echo $entry_maintenance; ?></td>
					<td>
						<select name="config_maintenance">
							<?php if ($config_maintenance) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>

					</td>
				</tr>
				<tr>
					<td><?php echo $entry_password; ?></td>
					<td>
						<select name="config_password">
							<?php if ($config_password) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</td>
				</tr>            
				<tr>
					<td><?php echo $entry_encryption; ?></td>
					<td><input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" />
						<?php if ($error_encryption) { ?>
							<span class="error"><?php echo $error_encryption; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_compression; ?></td>
							<td><input type="text" name="config_compression" value="<?php echo $config_compression; ?>" size="3" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_error_display; ?></td>
							<td>
								<select name="config_error_display">
									<?php if ($config_error_display) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_error_log; ?></td>
							<td>
								<select name="config_error_log">
									<?php if ($config_error_log) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_error_filename; ?></td>
							<td><input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" />
								<?php if ($error_error_filename) { ?>
									<span class="error"><?php echo $error_error_filename; ?></span>
									<?php } ?></td>
								</tr>											
							</table>
						</div>