<?php echo $header; ?>
<? require_once(DIR_TEMPLATEINCLUDE . 'structured/translate.js.tpl'); ?>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
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
			<h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="apply()" class="button"><span>Применить</span></a>
			<script language="javascript">
				function apply(){
				$('#form').append('<input type="hidden" id="apply" name="apply" value="1"  />');
				$('#form').submit();
				}
			</script>
			<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<? echo $categoryocshop; ?>" class="button">Вернуться в дерево</a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-data"><?php echo $tab_data; ?></a>
				<a href="#tab-products">Настройки товаров</a>
				<a href="#tab-reward">Бонусная программа</a>
				<?php if ($this->config->get('config_yam_enable_category_tree') || $this->config->get('config_rainforest_enable_api')) { ?>
					<a href="#tab-amazon-sync" style="color:#FF9900;font-weight:700;"><i class="fa fa-amazon"></i> Синхронизация Amazon (RNF API), <span style="color:#cf4a61"><i class="fa fa-yahoo"></i> Yandex Market</span></a>
				<?php } ?>
				<a href="#tab-related-data">Умные подборы</a>
				<a href="#tab-design"><?php echo $tab_design; ?></a>
				<a href="#tab-menucontent">Контент в меню</a><div class="clr"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<div id="languages" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /><?php echo $language['name']; ?></a>
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
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
										<input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" />
										<?php if (isset($error_name[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								
								<tr>
									<td>Альтернативные названия, для умного поиска (каждое с новой строки)</td>
									<td>
										<div class="translate_wrap">
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
										<textarea rows="20" cols="100" name="category_description[<?php echo $language['language_id']; ?>][alternate_name]" id="alternate_name<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['alternate_name'] : ''; ?></textarea>
									</td>
								</tr>
								
								
								<tr>
									<td><span class="required">*</span> Имя, отображаемое в меню</td>
									<td>
										
										<div class="translate_wrap">
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
										<input type="text" name="category_description[<?php echo $language['language_id']; ?>][menu_name]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['menu_name'] : ''; ?>" />
										<?php if (isset($error_name[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td>Множеств. префикс</td>
									<td><input type="text" name="category_description[<?php echo $language['language_id']; ?>][all_prefix]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['all_prefix'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td><?php echo $entry_seo_h1; ?></td>
									<td>
										<div class="translate_wrap">
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
										<input type="text" name="category_description[<?php echo $language['language_id']; ?>][seo_h1]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['seo_h1'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td><?php echo $entry_seo_title; ?></td>
									<td>
										<div class="translate_wrap">
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
									<input type="text" name="category_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['seo_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>
									<?php echo $entry_meta_description; ?><br /></td>
									<td>
										<div class="translate_wrap">
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
										<textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
									</td>
								</tr>

								<tr>
									<td>Дерево Google</td>
									<td>
										<div class="translate_wrap">
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
										<input type="text" name="category_description[<?php echo $language['language_id']; ?>][google_tree]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['google_tree'] : ''; ?>" />										
									</td>
								</tr>

								<tr>
									<td><?php echo $entry_meta_keyword; ?></td>
									<td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td><?php echo $entry_description; ?></td>
									<td>
										<div class="translate_wrap">
											<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
											<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
												<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
													Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												</a>
											<?php } ?>
										</div>
										<textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
									</td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
				
				<div id="tab-products">
					<table class="form">						
						<tr>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">ТНВЭД для товаров</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Наценка</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отбор пересечений</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Исключить из пересечений</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Удалять товары не в наличии</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Полное меню в дочерних категориях</span>
							</td>
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="tnved" value="<?php echo $tnved; ?>" size="20" />
							</td>
							<td>
								<input type="text" name="overprice" value="<?php echo $overprice; ?>" size="10" />
							</td>
							<td>
								<select name="intersections">
									<?php if ($intersections) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="exclude_from_intersections">
									<?php if ($exclude_from_intersections) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="deletenotinstock">
									<?php if ($deletenotinstock) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="submenu_in_children">
									<?php if ($submenu_in_children) { ?>
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
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Мониторинг цен Priceva</span>
							</td>
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="google_category" value="<?php echo $google_category; ?>" style="width:90%" />
								<input type="hidden" name="google_category_id" value="<?php echo $google_category_id; ?>" />
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
					
					<table class="form">						
						<tr>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Вес упаковки по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Единица измерения веса по-умолчанию</span>
							</td>							
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="default_weight" value="<?php echo $default_weight; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<select name="default_weight_class_id">
									<?php foreach ($weight_classes as $weight_class) { ?>
										<?php if ($weight_class['weight_class_id'] == $default_weight_class_id) { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>							
						</tr>
					</table>
					
					<table class="form">						
						<tr>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Длина упаковок по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ширина упаковок по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Высота упаковок по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Единица измерения размера по-умолчанию</span>
							</td>							
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="default_length" value="<?php echo $default_length; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<input type="text" name="default_width" value="<?php echo $default_width; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<input type="text" name="default_height" value="<?php echo $default_height; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<select name="default_length_class_id">
									<?php foreach ($length_classes as $length_class) { ?>
										<?php if ($length_class['length_class_id'] == $default_length_class_id) { ?>
											<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>							
						</tr>
					</table>
				</div>
				
				<div id="tab-reward">
					
					<table id="reward" class="list">
						<thead>
							<tr>
								<td class="center"><b>Виртуальный магазин</b></td>
								<td class="center"><b>Количество бонусов</b></td>
								<td class="center"><b>Процент бонусов</b></td>	
								<td class="center"><b>Дата начала</b></td>
								<td class="center"><b>Дата окончания</b></td>
								<td class="center"><b>Промокод</b></td>
								<td class="right"></td>
							</tr>
						</thead>
						<?php $reward_row = 0; ?>
						
						<? 
							$stores_reward = $stores;
							array_unshift($stores_reward, array('store_id' => -1, 'name' => 'Для всех магазинов'));
						?>
						
						<?php foreach ($rewards as $reward) { ?>
							<tbody id="reward-row<?php echo $reward_row; ?>">
								<tr>
									
									<td class="center">
										<select name="reward[<?php echo $reward_row; ?>][store_id]">
											<?php foreach ($stores_reward as $store) { ?>
												<?php if ($store['store_id'] == $reward['store_id']) { ?>
													<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</td>
									
									<td class="center"><input type="text" name="reward[<?php echo $reward_row; ?>][points]" value="<?php echo $reward['points']; ?>" size="10" />☯</td>
									
									<td class="center"><input type="text" name="reward[<?php echo $reward_row; ?>][percent]" value="<?php echo $reward['percent']; ?>" size="2" />%</td>								
									<td class="center"><input type="text" name="reward[<?php echo $reward_row; ?>][date_start]" value="<?php echo $reward['date_start']; ?>" class="date" /></td>
									
									<td class="center"><input type="text" name="reward[<?php echo $reward_row; ?>][date_end]" value="<?php echo $reward['date_end']; ?>" class="date" /></td>
									
									
									<td class="center">
										
										<select name="reward[<?php echo $reward_row; ?>][coupon_acts]">
											<?php if ($reward['coupon_acts']) { ?>
												<option value="1" selected="selected"><?php echo $text_yes; ?></option>
												<option value="0"><?php echo $text_no; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_yes; ?></option>
												<option value="0" selected="selected"><?php echo $text_no; ?></option>
											<?php } ?>
										</select>
										
										
									</td>
									
									
									<td class="right"><a onclick="$('#reward-row<?php echo $reward_row; ?>').remove();" class="button">Удалить</a></td>
								</tr>
							</tbody>
							<?php $reward_row++; ?>
						<?php } ?>
						<tfoot>
							<tr>
								<td colspan="6"></td>
								<td class="right"><a onclick="addReward();" class="button">Добавить</a></td>
							</tr>
						</tfoot>
					</table>
					
					<script type="text/javascript"><!--
						var reward_row = <?php echo $reward_row; ?>;
						
						function addReward() {
							html  = '<tbody id="reward-row' + reward_row + '">';
							html += '  <tr>'; 
							
							html += '    <td class="center"><select name="reward[' + reward_row + '][store_id]">';
							<?php foreach ($stores_reward as $store) { ?>
								html += '      <option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
							<?php } ?>
							html += '    </select></td>';
							
							html += '    <td class="center"><input type="text" name="reward[' + reward_row + '][points]" value="" size="10" />☯</td>';
							html += '    <td class="center"><input type="text" name="reward[' + reward_row + '][percent]" value="" size="2" />%</td>';							
							html += '    <td class="center"><input type="text" name="reward[' + reward_row + '][date_start]" value="" class="date" /></td>';
							html += '    <td class="center"><input type="text" name="reward[' + reward_row + '][date_end]" value="" class="date" /></td>';
							html += '       <td class="center"><select name="reward[' + reward_row + '][coupon_acts]">';
							html += '	      <option value="0"><?php echo $text_no; ?></option>';
							html += '	      <option value="1"><?php echo $text_yes; ?></option>';
							html += '	    </select></td>';
							html += '    <td class="right"><a onclick="$(\'#reward-row' + reward_row + '\').remove();" class="button">Удалить</a></td>';
							html += '  </tr>';
							html += '</tbody>';
							
							$('#reward tfoot').before(html);
							
							$('#reward-row' + reward_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
							
							reward_row++;
						}
					//--></script> 
					
					
				</div>
				
				<div id="tab-amazon-sync">
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
						
						<script type="text/javascript"><!--
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
								//--></script>
						
					</table>
					
					
					<table class="form">
						<tr>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Разрешить загрузку информации о новых товарах</span>									
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Разрешить загрузку полной информации о товарах</span>									
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Конечная категория Amazon</span>									
							</td>
							<td style="width:55%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Категория Amazon</span>
																
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><a style="color:#FFF;text-decoration:none" href="index.php?route=catalog/category/getAmazonCategoriesCSV&token=<?php echo $token; ?>"><i class="fa fa-amazon"></i> скачать полный список категорий Amazon в CSV</span> <sup style="color:red">NEW</sup>						
							</td>							
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td style="width:15%">
								<select name="amazon_sync_enable">
									<?php if ($amazon_sync_enable) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>


							<td style="width:15%">
								<select name="amazon_can_get_full">
									<?php if ($amazon_can_get_full) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>

							<td style="width:15%">
								<select name="amazon_final_category">
									<?php if ($amazon_final_category) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							
							<td style="width:55%">
								<input type="text" name="amazon_category_name" value="<?php echo $amazon_category_name; ?>" style="width:90%;" />
								<?php if (!$amazon_category_id) { ?>
									<br /><span id="span-alert-no-amazon-id" style="color:#ef5e67"><i class="fa fa-exclamation-triangle"></i> идентификатор не задан, попробуй подбор. Внимание, подбор работает только на НЕМЕЦКОМ языке</span>
									<?php } else { ?>
									<br /><span  style="color:#00ad07"><i class="fa fa-check"></i> идентификатор категории <span id="span-alert-amazon-id"><?php echo $amazon_category_id; ?></span></span>
								<? } ?>			

								<?php if ($amazon_category_id && $amazon_category_link) { ?>
									<br /><span  style="color:#00ad07"><i class="fa fa-check"></i> ccылка категории <a href="<?php echo $amazon_category_link; ?>" target="_blank"><?php echo $amazon_category_link; ?></a></span>
								<?php } ?>													

								<input type="hidden" name="amazon_category_id" value="<?php echo $amazon_category_id; ?>" style="width:90%;" />
								
								<script type="text/javascript"><!--
									$('input[name=\'amazon_category_name\']').autocomplete({
										delay: 500,
										source: function(request, response) {		
											$.ajax({
												url: 'index.php?route=catalog/category/amazon_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
												dataType: 'json',
												success: function(json) {
													response($.map(json, function(item) {
														return {
															label: item.path,
															value: item.id
														}
													}));
												}
											});
										},
										select: function(event, ui) {
											$('input[name=\'amazon_category_name\']').val(ui.item.label);
											$('input[name=\'amazon_category_id\']').val(ui.item.value);
											$('#span-alert-amazon-id').text(ui.item.value);
											$('#span-alert-no-amazon-id').hide();
											
											return false;
										},
										focus: function(event, ui) {
											return false;
										}
									});
								//--></script>
							</td>
						</tr>
					</table>
					
					<table class="form">
						<tr>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Дата последней синхронизации</span>
							</td>
							<td style="width:75%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Правила наценки</span>
							</td>							
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td style="width:25%">
								<input type="text" name="amazon_last_sync" value="<?php echo $amazon_last_sync; ?>" style="width:150px;"  />
							</td>
							
							<td style="width:75%">
								<input type="text" name="amazon_overprice_rules" value="<?php echo $amazon_overprice_rules; ?>" style="width:90%;"  />
								<br />
								<span class="help"><i class="fa fa-info-circle"></i> наценка задается порогами в процентах, что-то вроде 0:10;100:5;200:10;300:15</span>
							</td>
						</tr>
					</table>
				</div>
				
				<div id="tab-related-data">
					<table class="form">						
						<tr>
							<td>Связанные категории<br /><span class='help'>
								Для автоподбора аксессуаров / связанных товаров, если они не заданы явно
							</span></td>
							<td><input type="text" name="related_category" value="" style="width:400px;" /><br /><br />
								<div id="related-category" class="scrollbox" style="min-height: 300px;">
									<?php $class = 'odd'; ?>
									<?php foreach ($related_categories as $related_category) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div id="related-category<?php echo $related_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $related_category['name']; ?><img src="view/image/delete.png" alt="" />
											<input type="hidden" name="related_category[]" value="<?php echo $related_category['category_id']; ?>" />
										</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						<tr>
							<td>Характристики<br /><span class='help'>
								Если не пусто, подбор аксессуаров / дополнений происходит с учетом равенства значений этих характеристик
							</span></td>
							<td><div class="scrollbox" style="height:500px;">
								<?php $class = 'even'; ?>
								<?php foreach ($attributes as $attributes_to_cat) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($attributes_to_cat['attribute_id'], $attributes_similar_category)) { ?>
											<input id="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_similar_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" checked="checked" />
											<label for="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
											<?php } else { ?>
											<input id="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_similar_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" />
											<label for="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
							</td>
						</tr>
						<tr>
							<td>Равные атрибуты<br /><span class='help'>
								Для подбора похожих товаров, либо замен из списка в админке (равна категория и равно значение этих атрибутов)
							</span></td>
							<td><div class="scrollbox" style="height:500px;">
								<?php $class = 'even'; ?>
								<?php foreach ($attributes as $attributes_to_cat) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($attributes_to_cat['attribute_id'], $attributes_category)) { ?>
											<input id="attributes_<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" checked="checked" />
											<label for="attributes_<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
											<?php } else { ?>
											<input id="attributes_to_cat_<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" />
											<label for="attributes_to_cat_<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
							</td>
						</tr>
					</table>
				</div>
				
				
				<div id="tab-data">
					<table class="form">
						<tr>
							<td><?php echo $entry_parent; ?></td>
							<td>
								<input type="text" name="path" value="<?php echo $path; ?>" size="100" />
								<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
							</td>
						</tr>

						<?php if ($this->config->get('config_rainforest_enable_api')) { ?>
						<tr>
							<td>Родительская Amazon</td>
							<td>
								<input type="text" name="amazon_parent_category_id" value="<?php echo $amazon_parent_category_id; ?>" size="100" />
								<br />
								<span class="help" style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> При смене родительской категории магазина НЕОБХОДИМО очистить это поле, иначе категория привяжется заново</span>
								<?php if ($amazon_parent_category_name) { ?>
									<br /><span  style="color:#00ad07"><i class="fa fa-check"></i> родительская Amazon <?php echo $amazon_parent_category_name; ?></span>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
						
						<tr><th colspan="2">Акции</th></tr>
						<tr>
							<td>Показывать акции сверху<br /><span class="help">(Автодополнение)</span></td>
							<td><input type="text" name="action" value="" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<div id="category-action" class="scrollbox" style="width:400px;">
									<?php $class = 'odd'; ?>
									<?php foreach ($category_actions as $category_action) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div id="category-action<?php echo $category_action['action_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_action['name']; ?><img src="view/image/delete.png" alt="" />
											<input type="hidden" name="category_action[]" value="<?php echo $category_action['action_id']; ?>" />
										</div>
									<?php } ?>
								</div>
							</td>
						</tr>
						
						<tr><th colspan="2">Фильтр (не используется)</th></tr>
						<tr>
							<td><?php echo $entry_filter; ?></td>
							<td><input type="text" name="filter" value="" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<div id="category-filter" class="scrollbox" style="width:400px;">
									<?php $class = 'odd'; ?>
									<?php foreach ($category_filters as $category_filter) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div id="category-filter<?php echo $category_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_filter['name']; ?><img src="view/image/delete.png" alt="" />
											<input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
										</div>
									<?php } ?>
								</div></td>
						</tr>
						<tr>
							<td>Виртуальная родительская категория<span class="help">Только в меню, товары не учитываются</span></td>
							<td><input type="text" name="virtual_path" value="<?php echo $virtual_path; ?>" size="100" />
							<input type="hidden" name="virtual_parent_id" value="<?php echo $virtual_parent_id; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_store; ?></td>
							<td>
								<div class="scrollbox" style="width:350px;">
									<?php $class = 'even'; ?>											
									<?php foreach ($stores as $store) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
											<?php if (in_array($store['store_id'], $category_store)) { ?>
												<input id="category_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
												<label for="category_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
												<?php } else { ?>
												<input id="category_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
												<label for="category_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							</td>
						</tr>								
						<tr>
							<td><?php echo $entry_keyword; ?></td>
							<td><?php foreach ($languages as $language) { ?>
								<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_image; ?></td>
							<td valign="top"><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
								<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
								<br />
							<a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
						</tr>
						<tr>
							<td><?php echo $entry_top; ?></td>
							<td><?php if ($top) { ?>
								<input id="checkbox_24" class="checkbox" type="checkbox" name="top" value="1" checked="checked" />
								<label for="checkbox_24"></label>
								<?php } else { ?>
								<input id="checkbox_25" class="checkbox" type="checkbox" name="top" value="1" />
								<label for="checkbox_25"></label>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_column; ?></td>
							<td><input type="text" name="column" value="<?php echo $column; ?>" size="1" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td><select name="status">
								<?php if ($status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
							</td>
						</tr>
						<tr>
							<td style="color:red;">Выключить или включить дерево</td>
							<td>
								<i class="fa fa-exclamation-triangle"></i>
								<select name="status_tree">
								<option value="0" selected="selected">Не трогать дерево при нажатии кнопки сохранить</option>
								<option value="1">Выключить/включить всё дерево ниже при нажатии кнопки сохранить</option>
							</select>
							</td>
						</tr>

						<tr>
							<td style="color:red;">Выключить или включить дочерние</td>
							<td>
							<i class="fa fa-exclamation-triangle"></i>
							<select name="status_children">
								<option value="0" selected="selected">Не трогать дочерние при нажатии кнопки сохранить</option>
								<option value="1">Выключить/включить только дочерние при нажатии кнопки сохранить</option>
							</select>
							</td>
						</tr>
					</table>
				</div>
				<div id="tab-design">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><?php echo $entry_store; ?></td>
								<td class="left"><?php echo $entry_layout; ?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left"><?php echo $text_default; ?></td>
								<td class="left"><select name="category_layout[0][layout_id]">
									<option value=""></option>
									<?php foreach ($layouts as $layout) { ?>
										<?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
											<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select></td>
							</tr>
						</tbody>
						<?php foreach ($stores as $store) { ?>
							<tbody>
								<tr>
									<td class="left"><?php echo $store['name']; ?></td>
									<td class="left"><select name="category_layout[<?php echo $store['store_id']; ?>][layout_id]">
										<option value=""></option>
										<?php foreach ($layouts as $layout) { ?>
											<?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
												<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select></td>
								</tr>
							</tbody>
						<?php } ?>
					</table>
					
					<table class="list">
						<tr>
							<td>SVG иконка</td>
							<td><textarea name="menu_icon" rows="20" cols="50" style="width:90%"><? echo $menu_icon; ?></textarea></td>
						</tr>
					</table>
					
				</div>
				<div id="tab-menucontent">
					<div id="languages2" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language2<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /><?php echo $language['name']; ?></a>
						<?php } ?>
						<span style="vertical-align: -15px;"><input class="checkbox" type="checkbox" name="copyrussian" value="1" id="copyrussian" />
						<label for="copyrussian">Копировать ru на все.</label></span>
						<div class="clr"></div>
					</div>
					<? $max_row = 0; ?>
					<?php foreach ($languages as $language) { ?>
						<div id="language2<?php echo $language['language_id']; ?>">
							<table id="table_content_<?php echo $language['language_id']; ?>" style="width:100%">
								<? if (isset($category_menu_content[$language['language_id']])) { ?>			
									<? $row=0; foreach ($category_menu_content[$language['language_id']] as $menu_content) { ?>
										
										<tr id="tr_content_<?php echo $menu_content['category_menu_content_id'] ; ?>">				
											<? if ($row%2==0) { ?>
												<td style="border-left:2px solid green; padding:10px;">
													<? } else { ?>
													<td style="border-left:2px solid orange; padding:10px;">
													<? } ?>
													<table style="width:100%">
														<tr>
															<td colspan="2">
																<a style="float:right;" class="button" onclick="$('#tr_content_<?php echo $menu_content['category_menu_content_id'] ; ?>').remove();" data-language-id="<?php echo $language['language_id']; ?>"><i class="fa fa-trash-o"></i></a>
															</td>
														</tr>
														<tr>												
															<td>
																Заголовок: 
															</td>
															<td>
																<input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][title]" size="255" style="width:400px; margin-bottom:15px" value="<? echo $menu_content['title'] ?>" />										
																&nbsp;&nbsp;&nbsp;Сортировка: <input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][sort_order]" size="2" style="width:30px; margin-bottom:10px" value="<? echo $menu_content['sort_order'] ?>" />
															</td>
														</tr>
														<tr>
															<td>
																Ссылка: 
															</td>
															<td>
																<input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][href]" size="1024" style="width:600px; margin-bottom:15px" value="<? echo $menu_content['href'] ?>" />
															</td>
														</tr>
														<tr>
															<td>
																Контент / текст
															</td>
															<td>
																<textarea style="width:600px;" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][content]" id="content<?php echo $menu_content['category_menu_content_id'] ; ?>"><? echo $menu_content['content'] ?></textarea>
															</td>
														</tr>
														<tr>
															<td>
																Изображение
															</td>
															<td>
																<div class="image" style="float:left;">
																	<img src="<?php echo $menu_content['thumb']; ?>" alt="" id="thumb<?php echo $menu_content['category_menu_content_id'] ; ?>" />
																	
																	<input type="hidden" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][image]" value="<?php echo $menu_content['image']; ?>" id="image<?php echo $menu_content['category_menu_content_id'] ; ?>" /><br />
																	
																	<a onclick="image_upload('image<?php echo $menu_content['category_menu_content_id'] ; ?>', 'thumb<?php echo $menu_content['category_menu_content_id'] ; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $menu_content['category_menu_content_id'] ; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $menu_content['category_menu_content_id'] ; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
																</div>
																<div style="padding-top:50px;">
																	Ширина: <input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][width]" size="5" style="width:60px;" value="<? echo $menu_content['width'] ?>" />
																	Высота: <input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][height]" size="5" style="width:60px;" value="<? echo $menu_content['height'] ?>" />
																</div>
																</td>
														</tr>
														<tr>
															<td>
																Это баннер под списком
															</td>
															<td>
																<select name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][standalone]">
																	<? if ($menu_content['standalone']) { ?>
																		<option value="0" >нет, этот блок будет отображен в списке</option>
																		<option value="1" selected="selected">да, этот блок будет отображен под списком</option>
																		<? } else { ?>
																		<option value="0" selected="selected">нет, этот блок будет отображен в списке</option>
																		<option value="1">да, этот блок будет отображен под списком</option>
																	<? } ?>
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<? if ($menu_content['category_menu_content_id'] > $max_row) $max_row = $menu_content['category_menu_content_id']; ?>  			
										<? $row++; } ?>
									<? } ?>
								</table>
								<a style="margin-top:20px; float:right;" class="button add-menu-content" data-language-id="<?php echo $language['language_id']; ?>">Добавить</a>
								<div class="clr"></div>
							</div>
						<? } ?>		  
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" >
		$('a.add-menu-content').click(function(){
			
			
			var module_row = <?php echo ($max_row + 200); ?>;
			var language_id = $(this).attr('data-language-id');
			
			html  = '<tr id="tr_content_' + module_row + '">';
			html += '<td style="border-left:2px solid green; padding:10px;">';
			html += '<table style="width:100%">';
			html +=	'<tr>';
			html +=	'<td colspan="2">';
			html +=	'<a style="float:right;" class="button" onclick="$(\'#tr_content_' + module_row + '\').remove();" data-language-id="' + language_id + '">удалить</a>';
			html += '</td>';
			html += '</tr>';
			html += '<tr>';												
			html += '<td>Заголовок:</td>';
			html +=	'<td>';
			html += ' <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][title]" size="255" style="width:400px; margin-bottom:15px" value="" /> &nbsp;&nbsp;&nbsp;Сортировка: <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][sort_order]" size="2" style="width:30px; margin-bottom:10px" value="0" />'																				
			html +=	'</td>';
			html +=	'</tr>';
			html += '<tr>';												
			html += '<td>Сcылка:</td>';
			html +=	'<td>';
			html += ' <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][href]" size="1024" style="width:600px; margin-bottom:15px" value="" />';
			html +=	'</td>';
			html +=	'</tr>';
			html += '<tr>';												
			html += '<td>Контент / текст:</td>';
			html +=	'<td>';
			html += '<textarea style="width:600px;" name="category_menu_content[' + language_id + '][' + module_row + '][content]" id="content'+ module_row +'"></textarea>';
			html +=	'</td>';
			html +=	'</tr>';		
			html += '<tr>';												
			html += '<td>Изображение:</td>';
			html +=	'<td>';
			html += '<div class="image" style="float:left;">';
			html += '<img src="<?php echo $no_image; ?>" alt="" id="thumb' + module_row + '" />';
			html += '<input type="hidden" name="category_menu_content[' + language_id + '][' + module_row + '][image]" value="" id="image'+ module_row + '" /><br />';
			html += '<a onclick="image_upload(\'image' + module_row + '\', \'thumb'+ module_row + '\')"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + module_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + module_row + '\').attr(\'value\', \'\')"><?php echo $text_clear; ?></a>';
			html += '';
			html += '';
			html += '</div><div style="padding-top:50px;">';
			html +=	'Ширина: <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][width]" size="5" style="width:60px;" value="" />';
			html += 'Высота: <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][height]" size="5" style="width:60px;" value="" />';
			html +=		'</div>';
			html +=	'</td>';
			html +=	'</tr>';
			html += '<tr>';												
			html += '<td>Это баннер под списком:</td>';
			html +=	'<td>';
			html += '<select name="category_menu_content[' + language_id + '][' + module_row + '][standalone]">';
			html += '<option value="0" selected="selected">нет, этот блок будет отображен в списке</option><option value="1">да, этот блок будет отображен под списком</option>';
			html += '</select>';
			html +=	'</td>';
			html +=	'</tr>';	
			html += '</table>';
			html += '</td>';
			html += '</tr>';
			
			
			$('table#table_content_'+ language_id).append(html);		
			
			module_row++;
		});
	</script>
	
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
			
			<? if (isset($category_menu_content[$language['language_id']]) && false) { ?>
				<? foreach ($category_menu_content[$language['language_id']] as $menu_content) { ?>
					CKEDITOR.replace('content<?php echo $menu_content['category_menu_content_id'] ; ?>', {
						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
					});
					
				<? } ?>
			<? } ?>
			
		<?php } ?>
	//--></script>
	<script>
		$('input[name=\'related_category\']').autocomplete({
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
				$('#related-category' + ui.item.value).remove();
				
				$('#related-category').append('<div id="related-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="related_category[]" value="' + ui.item.value + '" /></div>');
				
				$('#related-category div:odd').attr('class', 'odd');
				$('#related-category div:even').attr('class', 'even');
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
		$('#related-category div img').live('click', function() {
			$(this).parent().remove();
			
			$('#related-category div:odd').attr('class', 'odd');
			$('#related-category div:even').attr('class', 'even');	
		});
	</script>
	<script type="text/javascript"><!--
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
	//--></script> 
	<script type="text/javascript"><!--
		$('input[name=\'path\']').autocomplete({
			delay: 500,
			source: function(request, response) {		
				$.ajax({
					url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {
						json.unshift({
							'category_id':  0,
							'name':  '<?php echo $text_none; ?>'
						});
						
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
				$('input[name=\'path\']').val(ui.item.label);
				$('input[name=\'parent_id\']').val(ui.item.value);
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	//--></script> 
	<script type="text/javascript"><!--
		$('input[name=\'virtual_path\']').autocomplete({
			delay: 500,
			source: function(request, response) {		
				$.ajax({
					url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {
						json.unshift({
							'category_id':  0,
							'name':  '<?php echo $text_none; ?>'
						});
						
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
				$('input[name=\'virtual_path\']').val(ui.item.label);
				$('input[name=\'virtual_parent_id\']').val(ui.item.value);
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	//--></script> 
	
	<script type="text/javascript"><!--
		// Filter
		$('input[name=\'action\']').autocomplete({
			delay: 100,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/actions/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {		
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.action_id
							}
						}));
					}
				});
			}, 
			select: function(event, ui) {
				$('#category-action' + ui.item.value).remove();
				
				$('#category-action').append('<div id="category-action' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_action[]" value="' + ui.item.value + '" /></div>');
				
				$('#category-action div:odd').attr('class', 'odd');
				$('#category-action div:even').attr('class', 'even');
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
		$('#category-action div img').live('click', function() {
			$(this).parent().remove();
			
			$('#category-action div:odd').attr('class', 'odd');
			$('#category-action div:even').attr('class', 'even');	
		});
	//--></script> 
	
	<script type="text/javascript"><!--
		// Filter
		$('input[name=\'filter\']').autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {		
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.filter_id
							}
						}));
					}
				});
			}, 
			select: function(event, ui) {
				$('#category-filter' + ui.item.value).remove();
				
				$('#category-filter').append('<div id="category-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_filter[]" value="' + ui.item.value + '" /></div>');
				
				$('#category-filter div:odd').attr('class', 'odd');
				$('#category-filter div:even').attr('class', 'even');
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
		$('#category-filter div img').live('click', function() {
			$(this).parent().remove();
			
			$('#category-filter div:odd').attr('class', 'odd');
			$('#category-filter div:even').attr('class', 'even');	
		});
	//--></script> 
	<script type="text/javascript"><!--
		function image_upload(field, thumb) {
			$('#dialog').remove();
			
			$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			
			$('#dialog').dialog({
				title: '<?php echo $text_image_manager; ?>',
				close: function (event, ui) {
					if ($('#' + field).attr('value')) {
						$.ajax({
							url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
							dataType: 'text',
							success: function(data) {
								$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
							}
						});
					}
				},	
				bgiframe: false,
				width: <?php echo $this->config->get('pim_width')?$this->config->get('pim_width'):800;?>,
				height: <?php echo $this->config->get('pim_height')?$this->config->get('pim_height'):400;?>,
				resizable: false,
				modal: false
			});
		};
	//--></script> 
	<script type="text/javascript"><!--
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('#tabs a').tabs(); 
		$('#languages a').tabs();
		$('#languages2 a').tabs();
	//--></script> 
<?php echo $footer; ?>																																												