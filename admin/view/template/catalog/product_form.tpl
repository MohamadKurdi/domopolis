<?php echo $header; ?>
<style type="text/css">
	table.ordermain,table.history{width:100%;border-collapse:collapse;margin-bottom:20px}
	table.orderadress,table.orderproduct{width:48%;border-collapse:collapse;margin-bottom:20px}
	table.ordermain > tbody > tr > th,table.orderadress > tbody > tr > th,table.list > thead > tr > th,table.history > tbody > tr > th,table.form > tbody > tr > th{background: #1f4962;color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
	table.ordermain > tbody > tr > td{width:25%}
	table.ordermain > tbody > tr > td:nth-child(odd),table.orderadress > tbody > tr > td:nth-child(odd){background:#EFEFEF}
	table.ordermain > tbody > tr > td,table.orderadress > tbody > tr > td{padding:5px;color:#000;border-bottom:1px dotted #CCC}
	.clr{clear:both}
	input[type="text"]{width:70%;}
	.blue_heading{text-align:center; padding:8px 0;cursor:pointer; background: #40a0dd;color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
</style>
<? require_once(DIR_TEMPLATEINCLUDE . 'structured/translate.js.tpl'); ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>

	<script type="text/javascript" src="view/javascript/fileuploader.js"></script>
	<link rel="stylesheet" type="text/css" href="view/stylesheet/fileuploader.css" />

	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/product.png" alt="" /> <?php echo $product_id; ?> / <? echo $model; ?> <?php if ($asin) { ?> / <?php echo $asin; ?> <? } ?></h1>

			<div style="float:left; padding-left:30px;">
				<?php if ($this->session->data['config_rainforest_asin_deletion_mode']) { ?>
					<small style="color:#cf4a61;display: block;"><i class="fa fa-info-circle"></i> Включен режим исключения ASIN. Товары, которые будут удалены - никогда более не будут добавлены с Amazon!</small>
				<?php } ?>
				<?php if ($this->session->data['config_rainforest_variant_edition_mode']) { ?>
					<small style="color:#cf4a61;display: block;"><i class="fa fa-info-circle"></i> Включен режим группового редактирования вариантов. Удаление одного варианта удалит и остальные!</small>
				<?php } ?>
				<?php if ($this->session->data['config_rainforest_translate_edition_mode']) { ?>
					<small style="color:#cf4a61;display: block;"><i class="fa fa-info-circle"></i> Включен режим коррекции переводов. Одинаковые значения цветов, материалов, атрибутов будут перезаписаны!</small>
				<?php } ?>
			</div>
			
			<div class="buttons"><a onclick="apply()" class="button"><span>Применить</span></a>
				<script language="javascript">
					function apply(){
						$('#form').append('<input type="hidden" id="apply" name="apply" value="1"  />');
						$('#form').submit();
					}
				</script><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
			</div>
			<div class="content">
				<div id="tabs" class="htabs">
					<a href="#tab-general" style="background-color:#ff7815; color:#FFF;">Текст</a>
					<a href="#tab-data" style="background-color:#ff7815; color:#FFF;">Товар</a>
					<a href="#tab-price" style="background-color:#00ad07; color:#FFF;">Цены</a>					
					<a href="#tab-priceva" style="background-color:#7F00FF; color:#FFF;"><i class="fa fa-product-hunt"></i> Конкуренты</a>					
					<a href="#tab-amazon" style="background-color:#FF9900; color:#FFF;"><i class="fa fa-amazon"></i> Amazon</a>
					<a href="#tab-yandex-market" style="background-color:#cf4a61; color:#FFF;"><i class="fa fa-yahoo"></i> Yandex.Market</a>
					<a href="#tab-special" style="background-color:#00ad07; color:#FFF;">Скидки</a>
					<a href="#tab-markdown" style="background-color:#00ad07; color:#FFF;">Уценка</a>
					<a href="#tab-additional-offer" style="background-color:#00ad07; color:#FFF;">Специальные</a>
					<a href="#tab-discount" style="background-color:#00ad07; color:#FFF;">Дискаунт</a>
					<a href="#tab-reward" style="background-color:#00ad07; color:#FFF;">Бонусы</a>
					<a href="#tab-parsing" style="background-color:#7F00FF; color:#FFF;">Фиды, парсинг</a>
					<a href="#tab-stock" style="background-color:#7F00FF; color:#FFF;">Склад</a>
					<a href="#tab-size" style="background-color:#7F00FF; color:#FFF;">Габариты, цвет</a>
					<a href="#tab-variants" style="background-color:#ff7815; color:#FFF;">Варианты</a>
					<a href="#tab-links" style="background-color:#ff7815; color:#FFF;">Связи</a>
					<a href="#tab-attribute" style="background-color:#ff7815; color:#FFF;">Атрибуты</a>						
					<a href="#tab-image" style="background-color:#ff7815; color:#FFF;">Картинки</a>
					<a href="#tab-videos" style="background-color:#ff7815; color:#FFF;">Видео</a>
					<? /*<a href="#tab-option" style="background-color:#ff7815; color:#FFF;">ОП</a>*/ ?>
					<? /*<a href="#tab-product-option" style="background-color:#ff7815; color:#FFF;">ТоП</a>	*/ ?>	
					<? /*	<a href="#tab-profile" style="display:none;">РП</a> */ ?>
					<? /* <a href="#tab-design" style="display:none;">МКТ</a> */ ?>
					<div class="clr"></div>
				</div>
				<div class="th_style"></div>
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<div id="tab-general">
						<div id="languages" class="htabs">
							<?php foreach ($languages as $language) { ?>
								<a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
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
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } ?>

												<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
												<?php } ?>

												<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="view/image/flags/uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
												<?php } ?>

												<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
														Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>&nbsp;

													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
														Перевести <img src="view/image/flags/gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>&nbsp;

													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
														Перевести <img src="view/image/flags/fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
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
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
												<?php } ?>

												<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
												<?php } ?>

												<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="view/image/flags/uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
												<?php } ?>

												<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
														Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>&nbsp;

													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
														Перевести <img src="view/image/flags/gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>&nbsp;

													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
														Перевести <img src="view/image/flags/fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>
												<?php } ?>
											</div>
											<input type="text" name="product_description[<?php echo $language['language_id']; ?>][short_name_d]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['short_name_d'] : ''; ?>" /></td>
										</tr>	

										<tr>
											<td>Имя опции</td>
											<td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name_of_option]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name_of_option'] : ''; ?>" />
												<br /><span class="help">Этот товар является виртуальной опцией какого-либо другого товара с названием, указанного в вкладке "Данные" в поле "Код Группы" с названием опции &uarr;</span>
												<?php if (isset($error_name[$language['language_id']])) { ?>
													<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
													<?php } ?></td>
												</tr>

												<tr>
													<td><?php echo $entry_seo_title; ?> <br /><span id='title_lngth'></span> смв.</td>
													<td>
														<div class="translate_wrap">
															<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','product_description[2]');"><i class="fa fa-copy"></i> Копировать с <?php echo $this->config->get('config_admin_language'); ?></a>

															<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
															<?php } ?>

															<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
															<?php } ?>

															<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="view/image/flags/uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
															<?php } ?>

															<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
																	Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																</a>&nbsp;

																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
																	Перевести <img src="view/image/flags/gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																</a>&nbsp;

																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
																	Перевести <img src="view/image/flags/fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
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
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
															<?php } ?>

															<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
															<?php } ?>

															<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="view/image/flags/uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
															<?php } ?>

															<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
																	Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																</a>&nbsp;

																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
																	Перевести <img src="view/image/flags/gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																</a>&nbsp;

																<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
																	Перевести <img src="view/image/flags/fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
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
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
																<?php } ?>

																<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
																<?php } ?>

																<?php if ($this->config->get('config_translate_from_uk') &&  in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="view/image/flags/uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
																<?php } ?>

																<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
																		Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																	</a>&nbsp;

																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
																		Перевести <img src="view/image/flags/gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																	</a>&nbsp;

																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
																		Перевести <img src="view/image/flags/fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
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
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','product_description[6]');">Перевести <img src="view/image/flags/ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
																<?php } ?>

																<?php if ($this->config->get('config_translate_from_de') && in_array($language['code'], $this->config->get('config_translate_from_de'))) { ?>
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
																<?php } ?>

																<?php if ($this->config->get('config_translate_from_uk') && in_array($language['code'], $this->config->get('config_translate_from_uk'))) { ?>
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'uk','<?php echo $language['code']; ?>','input','product_description[2]');">Перевести <img src="view/image/flags/uk.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>	
																<?php } ?>

																<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','product_description[26]');">
																		Перевести <img src="view/image/flags/de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																	</a>&nbsp;

																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','product_description[26]');">
																		Перевести <img src="view/image/flags/gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																	</a>&nbsp;

																	<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','product_description[26]');">
																		Перевести <img src="view/image/flags/fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
																	</a>
																<?php } ?>
															</div>
															<textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
														</td>
													</tr>
													<tr>
														<td><?php echo $entry_tag; ?></td>
														<td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" size="80" /></td>
													</tr>
												</table>
											</div>
										<?php } ?>
									</div>

									<div id="tab-data">
										<table class="form">
											<tr><th colspan='4'>Основная информация</th></tr>
											<tr>
												<td><span class="required">*</span> <?php echo $entry_model; ?></td>
												<td><input type="text" name="model" value="<?php echo $model; ?>" />
													<?php if ($error_model) { ?>
														<span class="error"><?php echo $error_model; ?></span>
														<?php } ?></td>


													</tr>
													<tr>
														<td><img src="view/image/flags/ru.png" title="ru"> Короткое название RU<br /><span class="help">макс. 50 символов</span></td>
														<td><input type="text" name="short_name" value="<?php echo $short_name; ?>" style="width:300px;" size="50" /></td>

														<td><img src="view/image/flags/de.png" title="de"> Короткое название DE<br /><span class="help">макс. 50 символов</span></td>
														<td><input type="text" name="short_name_de" value="<?php echo $short_name_de; ?>" style="width:300px;" size="50" /></td>
													</tr>
													<tr>
														<td><?php echo $entry_sku; ?></td>
														<td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>

														<td><?php echo $entry_upc; ?></td>
														<td><input type="text" name="upc" value="<?php echo $upc; ?>" /></td>
													</tr>
													<tr>
														<td><?php echo $entry_ean; ?></td>
														<td><input type="text" name="ean" value="<?php echo $ean; ?>" /></td>

														<td><?php echo $entry_jan; ?></td>
														<td><input type="text" name="jan" value="<?php echo $jan; ?>" /></td>
													</tr>

													<tr>
														<td><?php echo $entry_mpn; ?></td>
														<td><input type="text" name="mpn" value="<?php echo $mpn; ?>" /></td>
														<td><b>OLD ASIN</b>
														<br /><span class="help">поле не изменять</span>
														</td>
														<td><input type="text" name="old_asin" value="<?php echo $old_asin; ?>" /></td>
													</tr>

													<tr>
														<td>ТН ВЭД</td>
														<td><input type="text" name="tnved" value="<?php echo $tnved; ?>" /></td>

														<td>ASIN</td>
														<td><input type="text" name="asin" value="<?php echo $asin; ?>" /></td>
													</tr>

													<tr>
														<td><?php echo $entry_location; ?></td>
														<td><input type="text" name="location" value="<?php echo $location; ?>" /></td>

														<td><?php echo $entry_status; ?></td>
														<td><select name="status">
															<?php if ($status) { ?>
																<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
																<option value="0"><?php echo $text_disabled; ?></option>
															<?php } else { ?>
																<option value="1"><?php echo $text_enabled; ?></option>
																<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
															<?php } ?>
														</select></td>
													</tr>

													<tr><th colspan='4'>Автосоздание опций</th></tr>

													<tr>
														<td colspan="1">Код группы / Наименование родителя<br /><span class="help">Поле ISBN в стоковой логике</span></td>
														<td colspan="3"><input type="text" name="isbn" value="<?php echo $isbn; ?>" />
															<br /><span class="help">Этот товар является виртуальной опцией какого-либо другого товара с названием, указанным в данном поле</span>
														</td>
													</tr>
													<tr>
														<td colspan="1">Опция</td>
														<td colspan="3">
															<select name="is_option_with_id">
																<option value="0" <? if (!$is_option_with_id) { ?>selected="selected"<? } ?>>Не является никакой виртуальной опцией, это нормальный товар</option>

																<? foreach($_all_options_list as $_joption) { ?>
																	<option value="<? echo $_joption['option_id'] ?>" <? if ($is_option_with_id == $_joption['option_id']) { ?>selected="selected"<? } ?>>
																		<? echo $_joption['name'] ?> : <? echo $_joption['option_id'] ?> : <? echo $_joption['type'] ?>
																	</option>		
																<? } ?>


															</select>
															<br /><span class="help">Этот товар является виртуальной опцией какого-либо другого товара, наименование которого указано в поле "Код группы / Наименование родителя" с идентификатором опции, выбранном в этом поле и значением, указанном в мультиязычном поле Имя опции</span>
														</td>
													</tr>
													<tr>
														<td colspan="1">Цветовая группа</td>

														<td colspan="3"><input type="text" style="width:500px;" name="color_group" value="<?php echo $color_group; ?>" />
															<br /><span class="help">Это идентификатор группы товаров, которые являются одним и тем же товаром, но с другимим цветами</span>


															<? if (isset($color_grouped_products)) { ?>
																<br />
																<? foreach ($color_grouped_products as $_cgp) { ?>
																	<div style="float:left; text-align:center; width:150px; margin-right:20px;">
																		<img src="<? echo $_cgp['image']; ?>" /><br />
																		<? echo $_cgp['name']; ?><br />
																		<? if (!$_cgp['this']) { ?><a href="<? echo $_cgp['action']; ?>">[Изменить]</a><? } ?>
																	</div>
																<? } ?>	
															<? } ?>

														</td>
													</tr>

												</tr>

												<tr><th colspan='4'>Количества для закупки</th></tr>
												<tr>
													<td>Минимальный порог</td>
													<td><input type="text" name="min_buy" value="<?php echo $min_buy; ?>" size="2" /><br />

													</td>

													<td>Предельное количество</td>
													<td><input type="text" name="max_buy" value="<?php echo $max_buy; ?>" size="2" /><br /></td>
												</tr>	


												<tr><th colspan='4'>Настройки отображения</th></tr>
												<tr>
													<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-clock-o"></i> Дата добавления</span></td>
													<td>
														<input type="text" name="date_added" value="<?php echo $date_added; ?>" size="12" class="date" style="width:150px;" />
														<br /><span class="help"><i class="fa fa-info-circle"></i> Если у товара стоит маркер "новинка", то этот товар будет отображаться в новинках в случае если с этой даты прошло менее 45 дней, либо задана дата до которой отображать его в новинках</span>
													</td>

													<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-clock-o"></i> Новинка</span></td>
													<td>
														<input type="checkbox" name="new" value="1" <?php if (isset($new) && $new) print 'checked'; ?> />

														<input type="text" name="new_date_to" class="date" value="<? echo $new_date_to ?>" size="12" style="width:150px;" />
														<br /><span class="help">
															<i class="fa fa-info-circle"></i> товар будет отображаться в новинках ДО ЭТОЙ ДАТЫ (либо с даты добавления прошло менее 45 дней)</span>
														</td>
													</tr>
													<tr>
														<td><?php echo $entry_minimum; ?></td>
														<td><input type="text" name="minimum" value="<?php echo $minimum; ?>" size="2" /></td>

														<td>
															Снимать со склада
														</td>
														<td>
															<select name="subtract">
																<?php if ($subtract) { ?>
																	<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																	<option value="0"><?php echo $text_no; ?></option>
																<?php } else { ?>
																	<option value="1"><?php echo $text_yes; ?></option>
																	<option value="0" selected="selected"><?php echo $text_no; ?></option>
																<?php } ?>
															</select>
														</td>
													</tr>
													<tr>
														<td>Кол-во в упаковке<span class="help">количество товара в одной упаковке, для закупки</span></td>
														<td><input type="text" name="package" value="<?php echo $package; ?>" size="2" /></td>

														<td></td>
														<td></td>
													</tr>

													<tr>
														<td><?php echo $entry_shipping; ?></td>
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

															<td></td>
															<td></td>
														</tr>

														<tr>
															<td><?php echo $entry_sort_order; ?></td>
															<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>

															<td>Товар доступен до</td>
															<td>
																<input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" />
															</td>
														</tr>
														<tr>
															<td><?php echo $entry_tax_class; ?></td>
															<td><select name="tax_class_id">
																<option value="0"><?php echo $text_none; ?></option>
																<?php foreach ($tax_classes as $tax_class) { ?>
																	<?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
																		<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
																	<?php } else { ?>
																		<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
																	<?php } ?>
																<?php } ?>
															</select></td>
														</tr>

														<tr><th colspan='4'>Картинка, URL</th></tr>	
														<tr>
															<td><?php echo $entry_image; ?></td>
															<td>
																<div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
																	<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
																	<a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div>
																</td>

																	<td><?php echo $entry_keyword; ?></td>
																	<td>
																		<?php foreach ($languages as $language) { ?>
																		<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php  if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
																		<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
																		<?php } ?>
																	</td>
																	</td>
															</tr>


														</table>
												</div>

														<div id="tab-priceva">
															<?php if ($priceva) { ?>			
																<?php foreach ($priceva as $store_name => $priceva_data) { ?>
																	<h2 style="color:#7F00FF"><i class="fa fa-product-hunt"></i> Данные о конкурентах с ценами <?php echo $store_name; ?> (Priceva API)</h2>

																	<table class="list">
																		<thead>
																			<tr>
																				<td class="left">Конкурент</td>
																				<td class="left">Ссылка</td>
																				<td class="left">Цена KP</td>
																				<td class="left">Скидка KP</td>
																				<td class="left">Цена</td>
																				<td class="left">Скидка</td>
																				<td class="left">Наличие</td>
																				<td class="left">Посл. обновл.</td>
																				<td class="left">Релевантность</td>
																			</tr>
																		</thead>
																		<tbody>
																			<?php foreach ($priceva_data as $priceva_line) { ?>
																				<tr>
																					<td class="left" style="white-space: nowrap;">
																						<b><?php echo $priceva_line['company_name']; ?></b>
																						<br />
																						<small><?php echo $priceva_line['region_name']; ?></small>
																					</td>
																					<td class="left"><a href="<?php echo $priceva_line['url']; ?>" target="_blank"><small><?php echo $priceva_line['url']; ?></small></a></td>

																					<td class="left" style="white-space: nowrap;"><b><?php echo $front[$store_name]['price']; ?></b></td>
																					<td class="left" style="white-space: nowrap; color: #cf4a61;"><b><?php echo $front[$store_name]['special']; ?></b></td>

																					<td class="left" style="white-space: nowrap;"><b><?php echo $priceva_line['price']; ?></b></td>
																					<td class="left" style="white-space: nowrap; color: #cf4a61;"><b><?php echo $priceva_line['discount']; ?></b></td>

																					<td class="left" style="white-space: nowrap;">
																						<?php if ($priceva_line['in_stock']) { ?>
																							<span style="display:inline-block; padding:3px; color:#FFF; background-color:#51a881;">В наличии</span>
																						<?php } else { ?>
																							<span style="display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;">Нет в наличии</span>
																						<?php } ?>
																					</td>

																					<td class="left" style="white-space: nowrap;">
																						<span style="display:inline-block; padding:3px; color:#FFF; background-color:#000;"><?php echo $priceva_line['last_check_date']; ?>
																					</td>
																					<td class="left" style="white-space: nowrap;">
																						<span style="display:inline-block; padding:3px; color:#FFF; background-color:grey;"><?php echo $priceva_line['relevance_status']; ?></i>
																						</td>

																					</tr>
																				<?php } ?>
																			</tbody>
																		</table>
																	<?php } ?>
																<?php } ?>
															</div>

															<div id="tab-amazon" <?php if (!$this->config->get('config_rainforest_enable_api')) { ?>style="display: none;"<?php } ?>>
																<table class="form">
																	<tr>																		
																		<td class="left" width="14%">
																			<b>ASIN</b>
																		</td>
																		<td class="left" width="14%">
																			<b>Ссылка</b>
																		</td>
																		<td class="left" width="14%">
																			<b>EAN (GTIN)</b>
																		</td>
																		<td class="left" width="14%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Разрешить загрузку данных</span>
																		</td>
																		<td class="left" width="14%">
																			<b>Данные обновлены</b>
																		</td>
																		<td class="left" width="14%">	
																			<b>Офферы</b>															
																		</td>

																		<td class="left" width="14%">
																			<input type="hidden" name="added_from_amazon" value="<?php echo $added_from_amazon; ?>" />
																			<?php if ($added_from_amazon) { ?>																				
																				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Добавлен с Amazon</span>
																			<?php } else {  ?>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Добавлен вручную</span>
																			<?php } ?>
																		</td>

																		<td class="left" width="14%">
																			<?php if ($amzn_not_found) { ?>
																				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Товар не найден на Amazon</span>
																			<?php } else {  ?>
																				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Товар найден на Amazon</span>
																			<?php } ?>
																		</td>
																	</tr>
																	<tr>
																		<td class="left" width="14%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">
																				<?php if (!empty($asin)) { ?>
																					<?php echo $asin; ?>
																				<?php } else { ?>
																					Не задан
																				<?php } ?>
																			</span>

																			
																			<?php if (!empty($old_asin)) { ?>
																				<br /><br />
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF">																<?php echo $old_asin; ?>								
																			</span>
																			<?php } ?>
																		</td>
																		<td class="left" width="14%">
																			<?php if ($amazon_product_link) { ?>
																				<a href="<? echo $amazon_product_link; ?>" target="_blank"><? echo $amazon_product_link; ?></a>
																			<?php } ?>
																		</td>
																		<td class="left" width="14%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">
																				<?php if (!empty($ean)) { ?>
																					<?php echo $ean; ?>
																				<?php } else { ?>
																					Не задан
																				<?php } ?>
																			</span>
																		</td>
																		<td class="left" width="14%">
																			<select name="fill_from_amazon">
																				<?php if ($fill_from_amazon) { ?>
																					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
																					<option value="0"><?php echo $text_disabled; ?></option>
																				<?php } else { ?>
																					<option value="1"><?php echo $text_enabled; ?></option>
																					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
																				<?php } ?>
																			</select>
																		</td>
																		<td class="left" width="14%">
																			<input type="text" style="width:100px;" class="date" name="amzn_last_search" value="<?php echo $amzn_last_search; ?>" />
																		</td>
			
																		<td class="left" width="14%">
																			<a class="button" onclick="$('#amazon_offers').html('<i class=\'fa fa-spinner fa-spin\'></i>');$('#amazon_offers').load('index.php?route=kp/amazon/getProductOffers&token=<?php echo $token; ?>&explicit=1&product_id=<? echo $product_id; ?>');">Загрузить офферы</a>
																		</td>

																		<td class="left" width="14%">

																			<?php if (!empty($description_filled_from_amazon)) { ?>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Описания загружены</span>
																			<?php } else { ?>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Описания еще не загружены</span>
																			<?php } ?>
																			<br /><br />

																			<?php if (!empty($filled_from_amazon)) { ?>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Данные загружены</span>

																					<?php if (!empty($amazon_product_json)) { ?>
																						<a href="<?php echo $amazon_product_json; ?>" target="_blank"><i class="fa fa-download"></i></a>
																					<?php } else { ?>																					
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-check"></i>Данные проебаны</span>
																					<?php } ?>	
																			<?php } else { ?>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Данные еще не загружены</span>
																			<?php } ?>

																		</td>

																		<td class="left" width="14%">

																			<span class="help"><i class="fa fa-info-circle"></i> если "найден", то это означает, что товар с данным ASIN существует на Amazon и мы можем получить по нему данные, но при этом он не обязательно доступен к покупке</span>
																		</td>
																	</tr>
																</table>

																<table class="form">
																	<tr>
																		<td class="left" width="15%">
																			<b>Предложения обновлены</b>
																		</td>
																		<td class="left" width="15%">
																			<b style="color:#cf4a61">Не парсить Amazon</b>
																		</td>
																		<td class="left" width="15%">
																			<b style="color:#cf4a61">Цена BEST OFFER</b>
																		</td>
																		<td class="left" width="15%">
																			<b style="color:#cf4a61">Цена LOWEST OFFER</b>
																		</td>
																		<td class="left" width="15%">
																			<?php if ($amzn_no_offers) { ?>
																				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Нет предложений на Amazon</span>
																			<?php } else {  ?>
																				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Есть предложения на Amazon</span>
																			<?php } ?>
																		</td>
																		<td class="left" width="15%">
																			<?php if ($amzn_no_offers) { ?>
																				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Счетчик: <?php echo $amzn_no_offers_counter; ?></span>
																			<?php } ?>
																		</td>
																	</tr>

																	<tr>
																		<td class="left" width="15%">
																			<input type="text" style="width:150px;" class="datetime" name="amzn_last_offers" value="<?php echo $amzn_last_offers; ?>" />
																		</td>
																		<td class="left" width="15%">
																			<select name="amzn_ignore">
																				<?php if ($amzn_ignore) { ?>
																					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
																					<option value="0"><?php echo $text_disabled; ?></option>
																				<?php } else { ?>
																					<option value="1"><?php echo $text_enabled; ?></option>
																					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
																				<?php } ?>
																			</select>
																		</td>
																		<td class="left" width="15%">
																			<b><?php echo $amazon_best_price; ?></b>
																		</td>
																		<td class="left" width="15%">
																			<b><?php echo $amazon_lowest_price; ?></b>
																		</td>
																		<td class="left" width="15%">
																			<span class="help"><i class="fa fa-info-circle"></i> маркер НЕ ПАРСИТЬ можно устанавливать в любом случае, даже если предложения есть, в случае если, например, товар поставляется только с локальных складов и не планируется закупка с Amazon</span>
																		</td>
																		<td class="left" width="15%">
																			<span class="help"><i class="fa fa-info-circle"></i> если нет предложений, то это значит, что на данный момент товар находится в статусе Currently unavailable. We don't know when or if this item will be back in stock. Нужно принять решение, сканировать ли далее этот товар или нет. Если нет - нужно установить маркер "Не парсить Амазон"</span>
																		</td>
																	</tr>
																</table>


																<div style="margin-top:10px" id="amazon_offers">
																	<?php require_once(DIR_TEMPLATE . 'sale/amazon_offers_list.tpl'); ?>
																</div>

															</div>

															<div id="tab-yandex-market">
																<table class="form">
																	<td width="33%" valign="top">

																		<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Переназначение для Yandex Market</span></h2>

																		<table class="form">
																			<tr>																			
																				<td>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Общая цена YAM</span>
																				</td>
																				<td>
																					<input type="text" name="yam_price" value="<?php echo $yam_price; ?>" />
																					<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
																						<br />
																						<span class="help">включено автоматическое назначение цены исходя из текущей цены фронта, изменения тут перезапишутся через 2 часа</span>
																					<?php } else { ?>
																						<br />
																						<span class="help">автоматическое переназначение цены не включено</span>
																					<?php } ?>
																				</td>
																			</tr>

																			<tr>																			
																				<td>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Процент наценки YAM</span>
																				</td>
																				<td>
																					<input type="text" name="yam_percent" value="<?php echo $yam_percent; ?>" />
																					<br />
																					<span class="help">по-умолчанию настроена наценка <? echo $this->config->get('config_yam_plus_percent');?>%</span>
																					<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
																						<span class="help">включено автоматическое назначение цены исходя из текущей цены фронта</span>
																					<?php } ?>
																				</td>

																			</tr>

																			<tr>																			
																				<td>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Скидочная цена YAM</span>
																				</td>
																				<td>
																					<input type="text" name="yam_special" value="<?php echo $yam_special; ?>" />
																				</td>
																			</tr>

																			<tr>																			
																				<td>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Процент скидки YAM</span>
																				</td>
																				<td>
																					<input type="text" name="yam_special_percent" value="<?php echo $yam_special_percent; ?>" />
																					<br />
																					<span class="help">Скидка считается от заданной цены маркета</span>
																					<span class="help">по-умолчанию настроена скидка <? echo $this->config->get('config_yam_plus_for_main_price');?>%</span>
																					<?php if ($this->config->get('config_yam_enable_plus_for_main_price')) { ?>
																						<span class="help">включено автоматическое назначение скидки исходя из текущей цены маркета</span>
																					<?php } else { ?>
																						<span class="help">автоматическое переназначение скидки не включено</span>
																					<?php } ?>
																				</td>

																			</tr>

																			<tr style="border-bottom:1px dashed gray">																			
																				<td>
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Валюта для YAM</span>
																					<br /><span class="help">по умолчанию: RUB</span>
																				</td>
																				<td>
																					<input type="text" name="yam_currency" value="<?php echo $yam_currency; ?>" />
																					<br /><span style="font-size:10px;">
																						<? foreach ($currencies as $c) { ?>
																							<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $c['code']; ?></span>&nbsp;							
																							<? } ?></span>
																						</td>
																					</tr>


																					<? $this->load->model('setting/setting'); ?>
																					<? foreach ($stores as $store) { ?>															
																						<tr>																			
																							<td>
																								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><? echo $store['name']; ?></span>
																								<br /><span class="help">переназначение цены</span>
																							</td>
																							<td>

																								<input style="width:200px;" type="text" name="product_price_national_to_yam[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_national_to_yam[$store['store_id']])?$product_price_national_to_yam[$store['store_id']]['price']:''; ?>" />&nbsp;
																								<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']); ?></span>

																								<span style="display:inline-block; padding:4px 6px; margin:3px; background:#f91c02; color:#FFF;">
																									<input type="checkbox" name="product_price_national_to_yam[<? echo $store['store_id']; ?>][dot_not_overload_1c]" value="1" <?php if (isset($product_price_national_to_yam[$store['store_id']]) && $product_price_national_to_yam[$store['store_id']]['dot_not_overload_1c']) print 'checked'; ?>/>не переназначать из 1С
																								</span>				

																								<?php if (isset($product_price_national_to_yam[$store['store_id']]) && $product_price_national_to_yam[$store['store_id']]['settled_from_1c']) { ?>
																									<input type="hidden" name="product_price_national_to_yam[<? echo $store['store_id']; ?>][settled_from_1c]" value="1" />
																									<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> Цена YAM установлена из 1С</span>
																								<? }?>

																							</td>
																						</tr>
																					<? } ?>

																				</table>	
																			</td>

																			<td width="66%" valign="top">
																				<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ценообразование YAM</span></h2>

																				<table class="form" id="yam-price-suggest-result">
																				</table>
																			</td>
																			<?php if (!empty($this->request->get['product_id']) && $yam_product_id && $quantity_stockM) { ?>
																				<script>
																					function parsePrices(json){
																						console.log('YAM PRICE PARSE');
																						console.log(json);

																						jQuery.each(json, function(i, val) {

																							var html = '';
																							html += '<tr>';
																							html += '<td style="font-weight:700">';
																							html += val.type;
																							html += '<td>';
																							html += '<td>';
																							html += val.explanation;
																							html += '<td>';
																							html += '<td style="font-weight:700; white-space:nowrap">';
																							html += val.price;
																							html += '<td>';
																							html += '</tr>';

																							$('#yam-price-suggest-result').append(html);

																						});

																					}


																					$(document).ready(function(){
																						console.log('YAM PRICE SUGGEST');
																						$.get('<?php echo HTTPS_CATALOG; ?>yamarket-partner-api/offerprice?product_id=<?php echo $product_id; ?>', null, function(json){ parsePrices(json) }, 'json');
																					});
																				<?php } ?>
																			</script>
																		</table>
																	</div>

																	<div id="tab-price">
																		<table class="form">
																			<tr style="border-bottom:1px dashed gray">
																				<td width="50%" valign="top">
																					<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Общие настройки</span></h2>

																					<table class="form">
																						<tr>
																							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Общая цена</span></td>
																							<td>
																								<input type="text" name="price" value="<?php echo $price; ?>" />&nbsp;<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->config->get('config_currency'); ?></span>				
																							</td>									
																						</tr>
																						<tr>
																							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Не обновлять цены</span></td>
																							<td>
																								<input type="checkbox" name="ignore_parse" value="1" <?php if (isset($ignore_parse) && $ignore_parse) print 'checked'; ?>/>
																								&nbsp;&nbsp;до&nbsp;&nbsp;<input type="text" name="ignore_parse_date_to" class="date" value="<? echo $ignore_parse_date_to ?>" size="12" style="width:200px;" />
																							</td>
																						</tr>
																						<tr>
																							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Гипер-наценка</span></td>
																							<td><input type="checkbox" name="big_business" value="1" <?php if (isset($big_business) && $big_business) print 'checked'; ?> />
																								<span class="help">в парсерах и при обновлении цен будет использован повышенный коэффициент</span>
																							</td>							
																						</tr>
																					</table>
																				</td>
																				<td width="50%" valign="top">
																					<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Переназначение в нацвалюте (сертификаты)</span></h2>
																					<table class="form">
																						<tr>
																							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Фикс. цена в нацвалюте</span><br /><span class="help">только для единичных товаров</span></td>
																							<td><input type="text" name="price_national" value="<?php echo $price_national; ?>" /></td>
																						</tr>	
																						<tr>
																							<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Валюта нацвалюты</span></td>
																							<td>
																								<input type="text" name="currency" value="<?php echo $currency; ?>" />
																								<br /><span style="font-size:10px;"><? foreach ($currencies as $c) { ?>
																									<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $c['code']; ?></span>&nbsp;							
																									<? } ?></span>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>				
																			</table>

																			<table class="form">
																				<tr style="border-bottom:1px dashed gray">
																					<td width="50%" valign="top">
																						<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Переназначение в основной валюте, <? echo $this->config->get('config_currency'); ?></span></h2>

																						<table class="form">
																							<? $this->load->model('setting/setting'); ?>

																							<? foreach ($stores as $store) { ?>

																								<tr>
																									<td>
																										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $store['name']; ?></span>
																										<br /><span class="help">переназначение цены</span>
																									</td>
																									<td>
																										<input style="width:200px;" type="text" name="product_price_to_store[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_to_store[$store['store_id']])?$product_price_to_store[$store['store_id']]['price']:''; ?>" />
																										&nbsp;<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF">
																											<? echo $this->model_setting_setting->getKeySettingValue('config', 'config_currency', $store['store_id']); ?>
																										</span>			

																										<?php if (!empty($product_price_to_store[$store['store_id']]) && !empty($product_price_to_store[$store['store_id']]['price'])) {

																											$value_in_national_currency = $this->currency->format($product_price_to_store[$store['store_id']]['price'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id'])); 																								
																										} ?>

																										<?php if (!empty($value_in_national_currency)) { ?>
																											<br /><span class="help"><?php echo $value_in_national_currency; ?></span>
																										<?php } ?>

																									</td>
																									<td>
																										<span style="display:inline-block; padding:4px 6px; margin:3px; background:#4ea24e; color:#FFF;">
																											<input type="checkbox" name="product_price_to_store[<? echo $store['store_id']; ?>][dot_not_overload_1c]" value="1" <?php if (isset($product_price_to_store[$store['store_id']]) && $product_price_to_store[$store['store_id']]['dot_not_overload_1c']) print 'checked'; ?>/>не переназначать из 1С
																										</span>				

																										<?php if (isset($product_price_to_store[$store['store_id']]) && $product_price_to_store[$store['store_id']]['settled_from_1c']) { ?>
																											<input type="hidden" name="product_price_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="1" />
																											<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> Цена установлена из 1С</span>
																										<? } else { ?>
																											<input type="hidden" name="product_price_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="0" />
																											<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> Цена установлена не из 1С</span>
																										<?php } ?>
																									</td>
																								</tr>
																							<? } ?>

																						</table>
																					</td>
																					<td width="50%" valign="top">

																						<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Переназначение в нацвалюте, в большинстве случаев - РРЦ</span></h2>

																						<table class="form">
																							<? $this->load->model('setting/setting'); ?>

																							<? foreach ($stores as $store) { ?>	
																								<tr>
																									<td>
																										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><? echo $store['name']; ?></span>
																										<br /><span class="help">переназначение цены</span>
																									</td>
																									<td>

																										<input style="width:200px;" type="text" name="product_price_national_to_store[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_national_to_store[$store['store_id']])?$product_price_national_to_store[$store['store_id']]['price']:''; ?>" />&nbsp;
																										<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']); ?></span>
																									</td>
																									<td>
																										<span style="display:inline-block; padding:4px 6px; margin:3px; background:#ff7815; color:#FFF;">
																											<input type="checkbox" name="product_price_national_to_store[<? echo $store['store_id']; ?>][dot_not_overload_1c]" value="1" <?php if (isset($product_price_national_to_store[$store['store_id']]) && $product_price_national_to_store[$store['store_id']]['dot_not_overload_1c']) print 'checked'; ?>/>не переназначать из 1С
																										</span>				

																										<?php if (isset($product_price_national_to_store[$store['store_id']]) && $product_price_national_to_store[$store['store_id']]['settled_from_1c']) { ?>
																											<input type="hidden" name="product_price_national_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="1" />
																											<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> РРЦ установлена из 1С</span>
																										<? } else { ?>
																											<input type="hidden" name="product_price_national_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="0" />
																											<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> РРЦ установлена не из 1С</span>
																										<?php } ?>

																									</td>
																								</tr>
																							<? } ?>

																						</table>	
																					</td>
																				</tr>				
																			</table>
																		</div>

																		<div id="tab-parsing">

																			<h2>
																				SKU <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $sku; ?></span> 
																				MODEL <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $model; ?></span>
																				OFFER-ID <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $yam_product_id; ?></span>
																			</h2>


																			<table class="form">
																				<tr>
																					<td>
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF0000; color:#FFF">Неликвидный товар</span>
																					</td>
																					<td>
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF0000; color:#FFF">Исключить из YAM</span>
																					</td>
																					<td>
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF0000; color:#FFF">Код товара YAM</span>
																					</td>
																					<td>
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Включить Priceva</span>
																					</td>
																					<td>
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF0000; color:#FFF">Исключить из Priceva</span>
																					</td>
																					<td>
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Мин цена Priceva</span>
																					</td>

																				</tr>

																				<tr style="border-bottom:1px dashed gray">
																					<td style="width:16.6%">
																						<select name="is_illiquid">
																							<?php if ($is_illiquid) { ?>
																								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
																								<option value="0"><?php echo $text_disabled; ?></option>
																							<?php } else { ?>
																								<option value="1"><?php echo $text_enabled; ?></option>
																								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
																							<?php } ?>
																						</select>
																					</td>


																					<td style="width:16.6%">
																						<select name="yam_disable">
																							<?php if ($yam_disable) { ?>
																								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
																								<option value="0"><?php echo $text_disabled; ?></option>
																							<?php } else { ?>
																								<option value="1"><?php echo $text_enabled; ?></option>
																								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
																							<?php } ?>
																						</select>
																					</td>

																					<td style="width:16.6%">
																						<input type="text" name="yam_product_id" value="<?php echo $yam_product_id; ?>" />
																					</td>

																					<td style="width:16.6%">
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
																					<td style="width:16.6%">
																						<select name="priceva_disable">
																							<?php if ($priceva_disable) { ?>
																								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
																								<option value="0"><?php echo $text_disabled; ?></option>
																							<?php } else { ?>
																								<option value="1"><?php echo $text_enabled; ?></option>
																								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
																							<?php } ?>
																						</select>
																					</td>

																					<td style="width:16.6%">
																						<input type="text" name="mpp_price" value="<?php echo $mpp_price; ?>" />&nbsp;<? echo $this->config->get('config_currency'); ?>
																					</td>
																				</tr>
																			</table>	

																			<table class="form">
																				<tr>
																					<td style="width:50%">
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ссылки на конкурентов - Россия</span>
																					</td>
																					<td style="width:50%">
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ссылки на конкурентов - Украина</span>
																					</td>					
																				</tr>
																				<tr style="border-bottom:1px dashed gray">
																					<td>
																						<textarea name="competitors" style="width:90%; height:200px;"><? echo $competitors; ?></textarea>
																						<br />
																						<span class="help">cсылки на страницы, где находится товар на сайтах конкурентов. по одной в строке. только Россия</span>
																					</td>
																					<td>
																						<textarea name="competitors_ua" style="width:90%; height:200px;"><? echo $competitors_ua; ?></textarea>
																						<br />
																						<span class="help">cсылки на страницы, где находится товар на сайтах конкурентов. по одной в строке. Только Украина</span>
																					</td>	


																				</tr>
																			</table>


																			<table class="form">
																				<tr>
																					<td style="width:25%">
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Закупочная цена</span>
																					</td>
																					<td style="width:25%">
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Закупочная акционная цена</span>
																					</td>
																					<td style="width:25%">
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Цена из парсера</span>
																					</td>
																					<td style="width:25%">
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Акционная цена из парсера</span>
																					</td>

																				</tr>

																				<tr style="border-bottom:1px dashed gray">
																					<td><input type="text" name="cost" value="<?php echo $cost; ?>" /></td>
																					<td><input type="text" name="special_cost" value="<?php echo $special_cost; ?>" /></td>
																					<td><?php echo $parser_price; ?></td>
																					<td><?php echo $parser_special_price; ?></td>
																				</tr>
																			</table>

																			<table class="form">
																				<tr>
																					<td>
																						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Источники цен, поставщики</span>
																					</td>
																				</tr>
																				<tr style="border-bottom:1px dashed gray">
																					<td>
																						<textarea name="source" style="width:90%; heigth:200px;"><? echo $source; ?></textarea>
																						<br />
																						<span class="help">Ссылки на страницы, где находится товар. По одной в строке</span>
																					</td>
																				</tr>
																			</tr>
																		</table>
																	</div>

																	<div id="tab-stock">
																		<table>
																			<tr>
																				<td>
																					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Общий статус наличия</span></p>
																					<select name="stock_status_id">
																						<?php foreach ($stock_statuses as $stock_status) { ?>
																							<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
																								<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
																							<?php } else { ?>
																								<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
																							<?php } ?>
																						<?php } ?>
																					</select>
																				</td>
																				<td>
																					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Наличие</span></p>

																					<input type="number" step="1" name="quantity" value="<?php echo $quantity; ?>" />

																				</td>

																			</tr>
																		</table>

																		<table class="form">
																			<tr>
																				<?php foreach ($stores as $store) { ?>
																					<td width="<?php echo (int)(100/count($stores))?>%">
																						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Статус: <?php echo $store['name']; ?></span></p>
																						<select name="product_stock_status[<?php echo $store['store_id']; ?>]">

																							<?php if (empty($product_stock_status[$store['store_id']])) { ?>
																								<option value="0" selected="selected">Не переназначать</option>
																							<?php } else { ?>
																								<option value="0">Не переназначать</option>	
																							<?php } ?>

																							<?php foreach ($stock_statuses as $stock_status) { ?>
																								<?php if (!empty($product_stock_status[$store['store_id']]) && $stock_status['stock_status_id'] == (int)$product_stock_status[$store['store_id']]) { ?>
																									<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
																								<?php } else { ?>
																									<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
																								<?php } ?>
																							<?php } ?>
																						</select>
																					</td>
																				<?php } ?>
																			</tr>
																		</table>

																		<table class="form">

																			<tr>																	
																				<?php foreach ($stores as $store) { ?>
																					<td width="<?php echo (int)(100/(count($stores))) ?>%">
																						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF">Склад: <?php echo $store['name']; ?></span></p>

																						<input type="number" step="1" name="<?php echo $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id']); ?>" value="<?php echo ${$this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id'])}; ?>" />
																					</td>
																				<?php }	?>
																			</tr>

																			<tr>

																				<?php foreach ($stores as $store) { ?>
																					<td width="<?php echo (int)(100/(count($stores))) ?>%">
																						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Едет: <?php echo $store['name']; ?></span></p>

																						<input type="number" step="1" name="<?php echo $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id']); ?>_onway" value="<?php echo ${$this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id']) . '_onway'}; ?>" />
																					</td>
																				<?php }	?>
																			</tr>

																		</table>
																		<table class="form">
																			<tr><th colspan='4'>Необходимость обеспечения наличия на складе</th></tr>
																			<tr>
																				<? $i=1; ?>			
																				<? unset($store); foreach ($stores as $store) { ?>				
																					<td><? echo $store['name']; ?><br /><span class="help">минимальное и рекомендуемое наличие</span></td>
																					<td>
																						<input type="text" name="product_stock_limits[<? echo $store['store_id']; ?>][min_stock]" value="<?php echo isset($product_stock_limits[$store['store_id']])?$product_stock_limits[$store['store_id']]['min_stock']:0; ?>" style="width:50px;" />[Мин]&nbsp;&nbsp;
																						<input type="text" name="product_stock_limits[<? echo $store['store_id']; ?>][rec_stock]" value="<?php echo isset($product_stock_limits[$store['store_id']])?$product_stock_limits[$store['store_id']]['rec_stock']:0; ?>" style="width:50px;" />[Рек]
																					</td>
																					<? $i++; ?>
																					<? if ($i%2==0) { ?></tr><tr><? } ?>
																				<? } ?>
																			</tr>
																		</table>
																	</div>
																	<div id="tab-size">
																		<table class="form">
																			<tr>
																				<td style="width:50%">
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Цвет</span>
																				</td>
																				<td style="width:50%">
																					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Материал</span>
																				</td>
																			</tr>
																			<tr>
																				<td>
																					<?php foreach ($languages as $language) { ?>
																						<input type="text" name="product_description[<?php echo $language['language_id']; ?>][color]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['color'] : ''; ?>" size="90%" />
																						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
																					<?php } ?>
																				</td>
																				<td>
																					<?php foreach ($languages as $language) { ?>
																						<input type="text" name="product_description[<?php echo $language['language_id']; ?>][material]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['material'] : ''; ?>" size="90%" />
																						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
																					<?php } ?>
																				</td>
																			</tr>	
																		</table>
																<table class="form">
																	<tr>
																		<td style="width:50%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">НЕТТО (без упаковки)</span>
																		</td>
																		<td style="width:50%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">БРУТТО (с упаковкой)</span>
																		</td>
																	</tr>	
																	<tr>
																		<td>
																			<table>
																				<tr>																				
																					<td style="width:350px;">
																						<div><span style="display:inline-block; width:120px;">Длина / Length:</span> <input type="text" name="length" value="<?php echo $length; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																						<div><span style="display:inline-block; width:120px;">Ширина / Width:</span> <input type="text" name="width" value="<?php echo $width; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																						<div><span style="display:inline-block; width:120px;">Высота / Height:</span> <input type="text" name="height" value="<?php echo $height; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																					</td>					
																					<td style="width:350px;">
																						<div style="margin-bottom:10px;">
																							<i class="fa fa-bars"></i>
																							<select name="length_class_id" style="width:250px;">
																								<?php foreach ($length_classes as $length_class) { ?>
																									<?php if ($length_class['length_class_id'] == $length_class_id) { ?>
																										<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
																									<?php } else { ?>
																										<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
																									<?php } ?>
																								<?php } ?>
																							</select>
																						</div>
																						<div>
																							<i class="fa fa-amazon"></i> <input type="text" name="length_amazon_key" value="<?php echo $length_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td style="width:350px;">
																						<div><span style="display:inline-block; width:120px;">Вес / Weight:</span> <input type="text" name="weight" value="<?php echo $weight; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																					</td>	
																					<td style="width:350px;">							
																						<div style="margin-bottom:10px;">
																							<i class="fa fa-bars"></i>
																							<select name="weight_class_id" style="width:250px;">
																								<?php foreach ($weight_classes as $weight_class) { ?>
																									<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
																										<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
																									<?php } else { ?>
																										<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
																									<?php } ?>
																								<?php } ?>
																							</select>
																						</div>		

																						<div>
																							<i class="fa fa-amazon"></i> <input type="text" name="weight_amazon_key" value="<?php echo $weight_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
																						</div>												
																					</td>
																				</tr>																						
																			</table>
																		</td>
																		<td>
																			<table>
																				<tr>
																					<td style="width:350px;">
																						<div><span style="display:inline-block; width:120px;">Длина / Length:</span> <input type="text" name="pack_length" value="<?php echo $pack_length; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																						<div><span style="display:inline-block; width:120px;">Ширина / Width:</span> <input type="text" name="pack_width" value="<?php echo $pack_width; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																						<div><span style="display:inline-block; width:120px;">Высота / Height:</span> <input type="text" name="pack_height" value="<?php echo $pack_height; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																					</td>					
																					<td style="width:350px;">
																						<div style="margin-bottom:10px;">
																							<i class="fa fa-bars"></i>																							
																						<select name="pack_length_class_id" style="width:250px;">
																							<?php foreach ($length_classes as $length_class) { ?>
																								<?php if ($length_class['length_class_id'] == $pack_length_class_id) { ?>
																									<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
																								<?php } else { ?>
																									<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
																								<?php } ?>
																							<?php } ?>
																						</select>
																						</div>
																						<div>
																							<i class="fa fa-amazon"></i> <input type="text" name="pack_length_amazon_key" value="<?php echo $pack_length_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
																						</div>
																					</td>
																				</tr>

																				<tr>
																					<td style="width:350px;">
																						<div><span style="display:inline-block; width:120px;">Вес / Weight:</span> <input type="text" name="pack_weight" value="<?php echo $pack_weight; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
																					</td>	
																					<td style="width:350px;">
																							<i class="fa fa-bars"></i>							
																						<div style="margin-bottom:10px;">
																							<select name="pack_weight_class_id" style="width:250px;">
																								<?php foreach ($weight_classes as $weight_class) { ?>
																									<?php if ($weight_class['weight_class_id'] == $pack_weight_class_id) { ?>
																										<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
																									<?php } else { ?>
																										<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
																									<?php } ?>
																								<?php } ?>
																							</select>
																						</div>	
																						<div>
																							<i class="fa fa-amazon"></i> <input type="text" name="pack_weight_amazon_key" value="<?php echo $pack_weight_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
																						</div>													
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
															</div>

															<div id="tab-variants">
																<table class="form">
																	<tr>
																		<td style="width:50%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Основной товар варианта</span>
																		</td>
																		<td style="width:50%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">В любом случае отобразить на фронте</span>
																		</td>																	
																	</tr>
																	<tr>
																		<td>
																		<?php if ($other_variant_products && !$main_variant_id) { ?>

																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-info-circle"></i> Текущий товар - основной</span>

																		<?php } else { ?>

																			<input id="main_variant_product" value="<?php echo $main_variant_product; ?>" name="main_variant_product" type="text" style="width:80%;" /> 
																			<span style="border-bottom:1px dashed black;" onclick="$('#main_variant_product').val(''); $('#main_variant_id').val('');">очистить</span>
																			<br />
																			<span class="help">автоподбор</span>
																			
																		<? } ?>
																		<input  id="main_variant_id" name="main_variant_id" value="<?php echo $main_variant_id; ?>" type="hidden" />
																		</td>
																		<td>
																			<select name="display_in_catalog">
																				<?php if ($display_in_catalog) { ?>
																					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
																					<option value="0"><?php echo $text_disabled; ?></option>
																				<?php } else { ?>
																					<option value="1"><?php echo $text_enabled; ?></option>
																					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
																				<?php } ?>
																			</select>
																			<br />
																			<span class="help">Эта карта товара будет отображена на фронте в любом случае, даже если является вариантом другого товара</span>
																		</td>																	
																	</tr>																	
																</table>
																<table class="form">
																	<tr>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Полное название варианта</span>
																		</td>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Характеристика - 1</span>
																		</td>	
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Характеристика - 2</span>
																		</td>																
																	</tr>
																	<tr>
																		<td style="width:33%" valign="top">
																			<?php foreach ($languages as $language) { ?>
																				<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_name'] : ''; ?>" style="width: 90%" />
																						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
																			<?php } ?>
																		</td>
																		<td style="width:33%">
																			<?php foreach ($languages as $language) { ?>
																				<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_name_1]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_name_1'] : ''; ?>" style="width: 40%" /> 
																				<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
																				<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_value_1]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_value_1'] : ''; ?>" style="width: 40%" />

																					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
																			<?php } ?>

																			<br />		
																			<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Характеристика - 1 определяет цвет</span></p>
																			<select name="variant_1_is_color">
																				<?php if ($variant_1_is_color) { ?>
																					<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																					<option value="0"><?php echo $text_no; ?></option>
																				<?php } else { ?>
																					<option value="1"><?php echo $text_yes; ?></option>
																					<option value="0" selected="selected"><?php echo $text_no; ?></option>
																				<?php } ?>
																			</select>

																		</td>	
																		<td style="width:33%">
																			<?php foreach ($languages as $language) { ?>
																				<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_name_2]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_name_2'] : ''; ?>" style="width: 40%" />

																				<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
																				<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_value_2]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_value_2'] : ''; ?>" style="width: 40%" />

																						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
																			<?php } ?>

																			<br />
																			<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Группа - 2 определяет цвет</span></p>
																			<select name="variant_2_is_color">
																				<?php if ($variant_2_is_color) { ?>
																					<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																					<option value="0"><?php echo $text_no; ?></option>
																				<?php } else { ?>
																					<option value="1"><?php echo $text_yes; ?></option>
																					<option value="0" selected="selected"><?php echo $text_no; ?></option>
																				<?php } ?>
																			</select>

																		</td>																
																	</tr>
																</table>

																<?php if ($other_variant_products) { ?>	

																	<?php if (!$main_variant_id) { ?>	
																		<div style="clear:both; height:10px;"></div>																
																		<div style="float:left; width:90%;">
																			<?php foreach ($languages as $language) { ?>
																				<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
																					<textarea id="main_variant_name_editor_<?php echo $language['language_id']; ?>" style="width:90%"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?></textarea>
																				<? } ?>
																			<?php } ?>

																			<?php if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']) { ?>
																				<script>
																					<?php foreach ($languages as $language) { ?>
																						<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>

																							$('#main_variant_name_editor_<?php echo $language['language_id']; ?>').on('focusout, change', function(){
																								let value = $(this).val();
																								let main_textarea = $(this);

																								$.ajax({
																									url : 'index.php?route=catalog/product/parsevariantnames&token=<?php echo $token; ?>',
																									data: {
																										product_id: <?php echo $product_id; ?>,
																										name: value,
																										language_id: '<?php echo $language['language_id']; ?>'
																									},
																									type: 'POST',
																									dataType: 'json',
																									beforeSend: function(){
																										$('#variants-editor-status').html('<i class="fa fa-spinner fa-spin"></i>');
																									},
																									success: function (json){
																										$('#variants-editor-status').html('<i class="fa fa-check"></i>');
																										main_textarea.val(json.main_name);

																										$.each(json.variants, function(i, item) {
																											$('#variant_name_'+item.product_id+'_'+<?php echo $language['language_id']; ?>).val(item.name);
																										});

																									}
																								});


																							});

																						<?php } ?>
																					<?php } ?>
																				</script>
																			<?php } ?>

																		</div>
																		<div id="variants-editor-status" style="float:right; width:10%; font-size:26px; padding-top: 30px;">
																			<i class="fa fa-hourglass"></i>
																		</div>
																	<?php } ?>
																	<div style="clear:both; height:20px;"></div>																
																	<table class="list">
																		<?php foreach ($other_variant_products as $other_variant_product) { ?>																			
																			<tr>
																				<td>
																					<a href="<?php echo $other_variant_product['link']; ?>" target='_blank'><?echo $other_variant_product['product_id']; ?> <i class="fa fa-edit"></i></a>
																				</td>
																				<td>
																					<img src="<?echo $other_variant_product['thumb']; ?>">
																				</td>
																				<td>
																					<b><?echo $other_variant_product['asin']; ?></b>
																				</td>
																				<td style="width:60%">
																					<?php foreach ($languages as $language) { ?>
																						<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
																							<textarea cols="2" style="width:98%" id="variant_name_<?echo $other_variant_product['product_id']; ?>_<?php echo $language['language_id'];?>" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['name']"><?echo $other_variant_product['name']; ?></textarea>
																						<?php } ?>
																					<?php } ?>
																				</td>
																				<td style="width:150px">
																					<?php foreach ($languages as $language) { ?>
																						<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
																							<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_name']"><?echo $other_variant_product['variant_name']; ?></textarea>	
																						<?php } ?>
																					<?php } ?>
																				</td>
																				<td style="width:100px">
																					<?php foreach ($languages as $language) { ?>
																						<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
																							<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_name_1']"><?echo $other_variant_product['variant_name_1']; ?></textarea>	
																						<?php } ?>
																					<?php } ?>									
																				</td>
																				<td style="width:100px">
																					<?php foreach ($languages as $language) { ?>
																						<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
																							<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_value_1']"><?echo $other_variant_product['variant_value_1']; ?></textarea>	
																						<?php } ?>
																					<?php } ?>									
																				</td>
																				<td style="width:100px">
																					<?php foreach ($languages as $language) { ?>
																						<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
																							<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_name_2']"><?echo $other_variant_product['variant_name_2']; ?></textarea>
																						<?php } ?>
																					<?php } ?>										
																				</td>
																				<td style="width:100px">
																					<?php foreach ($languages as $language) { ?>
																						<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
																							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
																							<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_value_2']"><?echo $other_variant_product['variant_value_2']; ?></textarea>		
																						<?php } ?>
																					<?php } ?>								
																				</td>							
																			</tr>
																		<?php } ?>
																	</table>
																<?php } ?>
															</div>


															<div id="tab-links">
																<table class="form">
																	<tr>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Бренд</span>
																		</td>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Коллекция</span>
																		</td>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Основная категория</span>
																		</td>
																	</tr>
																	<tr style="border-bottom: 1px dotted #000;">
																		<td style="width:33%">
																			<input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" />
																			<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
																			<br />
																			<span class="help">
																				<i class="fa fa-info-circle"></i> автоподбор, начни вводить и выбери из списка	
																			</span>
																		</td>

																		<td style="width:33%">

																			<input type="text" name="collection" value="<? if(isset($collection)) { ?><? echo $collection['name']; ?><? } ?>" />
																			<input type="hidden" name="collection_id" value="<? if(isset($collection)) { ?><? echo $collection['collection_id']; ?><? } else { ?>0<? } ?>" />
																			<br />
																			<span class="help">
																				<i class="fa fa-info-circle"></i> автоподбор, если задан бренд - то подбираются только коллекции бренда	
																			</span>

																		</td>

																		<td style="width:33%">
																			<select name="main_category_id">
																				<option value="0" selected="selected"><?php echo $text_none; ?></option>
																				<?php foreach ($product_categories as $category) { ?>
																					<?php if ($category['category_id'] == $main_category_id) { ?>
																						<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
																					<?php } else { ?>
																						<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
																					<?php } ?>
																				<?php } ?>
																			</select>
																			<br />
																			<span class="help">
																				<i class="fa fa-info-circle"></i> категория выбирается из заданных категорий отображения	
																			</span>
																		</td>
																	</tr>
																</table>


																<table class="form">

																	<tr>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Показывать в категориях</span>
																		</td>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Включить в магазинах</span>
																		</td>
																		<td style="width:33%">
																			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Сервиз или набор</span>
																		</td>
																	</tr>

																	<tr>

																		<td style="width:33%">																			
																			<input type="text" name="category" value="" /><br /><br />
																			<div id="product-category" class="scrollbox" style="min-height: 200px;">
																				<?php $class = 'odd'; ?>
																				<?php foreach ($product_categories as $product_category) { ?>
																					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																					<div id="product-category<?php echo $product_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $product_category['name']; ?><img src="view/image/delete.png" alt="" />
																						<input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
																					</div>
																				<?php } ?>
																			</div>	
																		</td>
																		
																		<td style="width:33%">																			
																			<div class="scrollbox" style="min-height: 200px;">
																				<?php $class = 'even'; ?>
																				<?php foreach ($stores as $store) { ?>
																					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																					<div class="<?php echo $class; ?>">
																						<?php if (in_array($store['store_id'], $product_store)) { ?>
																							<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
																							<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
																						<?php } else { ?>
																							<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
																							<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
																						<?php } ?>
																					</div>
																				<?php } ?>

																			</div>
																			<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
																		</td>

																		<td style="width:33%">					
																		<div>														
																			<input id="has_child" class="checkbox" name="has_child" value="1" <? if ($has_child) { ?>checked="checked"<? } ?> type="checkbox" />
																			<label for="has_child">Включить сервиз/набор</label>
																		</div>
																		<input type="text" name="child" value="" /> 
																		<br /><br />
																		<div id="product-child" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($product_child as $product_child) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-child<?php echo $product_child['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_child['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_child[]" value="<?php echo $product_child['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>

																	
																</tr>
															</table>

															<h2>Перелинковка товаров (+Amazon)</h2>

															<table class="form">
																<tr>
																	<td style="width:25%">
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Похожие или замена</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><i class="fa fa-amazon"></i> compare_with_similar</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий сведения о других товарах из раздела «Сравнить с похожими товарами» на странице товара.</span>
																	</td>
																	<td style="width:25%">
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Sponsored Products</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><i class="fa fa-amazon"></i> sponsored_products</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий сведения о спонсируемых продуктах, отображаемых на текущей странице продукта.</span>
																	</td>
																	<td style="width:25%">
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF">Сопутствующие</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF"><i class="fa fa-amazon"></i> frequently_bought_together</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Объект, содержащий сведения о других продуктах, которые, по мнению Amazon, «часто покупаются вместе» с текущим продуктом. Штатная логика related</span>
																	</td>
																	<td style="width:25%">
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF">Похожие - 2</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF"><i class="fa fa-amazon"></i> similar_to_consider</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Объект, содержащий сведения о разделе «похожий элемент для рассмотрения», если он показан.</span>
																	</td>
																</tr>

																<tr>
																	<td style="width:25%">																			
																		<input type="text" name="similar" value="" /><br /><br />
																		<div id="product-similar" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_similar as $product_similar) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-similar<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_similar['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_similar[]" value="<?php echo $product_similar['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>

																	<td style="width:25%">																			
																		<input type="text" name="sponsored" value="" /><br /><br />
																		<div id="product-sponsored" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_sponsored as $product_sponsored) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-sponsored<?php echo $product_sponsored['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_sponsored['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_sponsored[]" value="<?php echo $product_sponsored['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>

																	<td style="width:25%">																			
																		<input type="text" name="related" value="" /><br /><br />
																		<div id="product-related" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_related as $product_related) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-related<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_related['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>

																	<td style="width:25%">																			
																		<input type="text" name="similar_to_consider" value="" /><br /><br />
																		<div id="product-similar_to_consider" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_similar_to_consider as $product_similar_to_consider) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-similar_to_consider<?php echo $product_similar_to_consider['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_similar_to_consider['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_similar_to_consider[]" value="<?php echo $product_related['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>
																</tr>

																<tr>
																	<td style="width:25%">
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Купили после просмотра</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><i class="fa fa-amazon"></i> view_to_purchase</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий информацию о других продуктах, которые клиенты Amazon купили после просмотра текущего продукта. Обычно отображается в разделе на странице продукта под заголовком «Что еще покупают покупатели после просмотра этого товара?».</span>
																	</td>
																	<td style="width:25%">
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF">Также смотрели</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF"><i class="fa fa-amazon"></i> also_viewed</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий информацию о других продуктах, которые клиенты Amazon просматривали вместе с текущим продуктом.</span>
																	</td>
																	<td style="width:25%">
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Также купили</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><i class="fa fa-amazon"></i> also_bought</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий информацию о других продуктах, которые также купили клиенты Amazon, купившие текущий продукт. Обычно отображается в разделе на странице продукта под заголовком «Клиенты, которые купили этот товар, также купили».</span>
																	</td>
																	<td style="width:25%">	
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF">Shop By Look</span>
																		<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF"><i class="fa fa-amazon"></i> shop_by_look</span>

																		<span class="help"><i class="fa fa-info-circle"></i> Объект, содержащий сведения о разделе «Поиск по внешнему виду» на странице продукта.</span>																	
																	</td>
																</tr>
																<tr>
																	<td style="width:25%">																			
																		<input type="text" name="view_to_purchase" value="" /><br /><br />
																		<div id="product-view_to_purchase" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_view_to_purchase as $product_view_to_purchase) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-view_to_purchase<?php echo $product_view_to_purchase['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_view_to_purchase['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_view_to_purchase[]" value="<?php echo $product_related['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>
																	<td style="width:25%">																			
																		<input type="text" name="also_viewed" value="" /><br /><br />
																		<div id="product-also_viewed" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_also_viewed as $product_also_viewed) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-also_viewed<?php echo $product_also_viewed['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_also_viewed['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_also_viewed[]" value="<?php echo $product_related['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>
																	<td style="width:25%">																			
																		<input type="text" name="also_bought" value="" /><br /><br />
																		<div id="product-also_bought" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_also_bought as $product_also_bought) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-also_bought<?php echo $product_also_bought['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_also_bought['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_also_bought[]" value="<?php echo $product_related['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>
																	<td style="width:25%">																			
																		<input type="text" name="shop_by_look" value="" /><br /><br />
																		<div id="product-shop_by_look" class="scrollbox" style="min-height: 200px;">
																			<?php $class = 'odd'; ?>
																			<?php foreach ($products_shop_by_look as $product_shop_by_look) { ?>
																				<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																				<div id="product-shop_by_look<?php echo $product_shop_by_look['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_shop_by_look['name']; ?><img src="view/image/delete.png" alt="" />
																					<input type="hidden" name="product_shop_by_look[]" value="<?php echo $product_related['product_id']; ?>" />
																				</div>
																			<?php } ?>
																		</div>
																	</td>
																</tr>

																</table>

															<h2>Группировки и другая шляпа</h2>

															<table class="form">
																		<tr>
																		<td style="width:33%">
																			<div>
																			<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Принадлежит к группам</span></p>
																			<div class="scrollbox facats">
																				<?php $class = 'odd'; ?>
																				<?php foreach ($faproduct_facategory_all as $category) { ?>
																					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																					<div class="<?php echo $class; ?>">
																						<?php if (in_array($category['facategory_id'], $faproduct_facategory)) { ?>
																							<input id="facategory_<?php echo $category['facategory_id']; ?>" class="checkbox" type="checkbox" name="faproduct_facategory[]" value="<?php echo $category['facategory_id']; ?>" checked="checked" />
																							<label for="facategory_<?php echo $category['facategory_id']; ?>"><?php echo $category['name']; ?></label>
																						<?php } else { ?>
																							<input id="facategory_<?php echo $category['facategory_id']; ?>" class="checkbox" type="checkbox" name="faproduct_facategory[]" value="<?php echo $category['facategory_id']; ?>" />
																							<label for="facategory_<?php echo $category['facategory_id']; ?>"><?php echo $category['name']; ?></label>
																						<?php } ?>
																					</div>
																				<?php } ?>
																			</div>														
																			<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
																			</div>

																			<div>
																			<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Показать в карте товары группы</span></p>
																			<select name="facategory_show">
																					<option value="0" selected="selected"><?php echo $text_none; ?></option>
																					<?php foreach ($faproduct_facategory_all as $category) { ?>
																						<?php if ($category['facategory_id'] == $facategory_show) { ?>
																							<option value="<?php echo $category['facategory_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
																						<?php } else { ?>
																							<option value="<?php echo $category['facategory_id']; ?>"><?php echo $category['name']; ?></option>
																						<?php } ?>
																					<?php } ?>
																				</select>
																			</div>
																		</td>

																			<td style="width:33%">
																				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Фильтры</span></p>
																				<input type="text" name="filter" value="" /><br /><br />
																				<div id="product-filter" class="scrollbox" style="min-height: 200px;">
																					<?php $class = 'odd'; ?>
																					<?php foreach ($product_filters as $product_filter) { ?>
																						<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																						<div id="product-filter<?php echo $product_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $product_filter['name']; ?><img src="view/image/delete.png" alt="" />
																							<input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
																						</div>
																					<?php } ?>
																				</div>
																			</td>
																		
																			<td style="width:33%">
																				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Загрузки</span></p>
																				<input type="text" name="download" value="" /><br /><br />
																				<div id="product-download" class="scrollbox" style="min-height: 200px;">
																					<?php $class = 'odd'; ?>
																					<?php foreach ($product_downloads as $product_download) { ?>
																						<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																						<div id="product-download<?php echo $product_download['download_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_download['name']; ?><img src="view/image/delete.png" alt="" />
																							<input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
																						</div>
																					<?php } ?>
																				</div></td>
																			</tr>
																		</table>
																	</div>


																	<div id="tab-attribute">
																		<table id="attribute" class="list">
																			<thead>
																				<tr>
																					<td class="left"><?php echo $entry_attribute; ?></td>
																					<td class="left"><?php echo $entry_text; ?></td>
																					<td></td>
																				</tr>
																			</thead>
																			<?php $attribute_row = 0; ?>
																			<?php foreach ($product_attributes as $product_attribute) { ?>
																				<tbody id="attribute-row<?php echo $attribute_row; ?>">
																					<tr>
																						<td class="left" style="width:300px;">
																							<input style="width:90%" type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" />
																							<input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
																						<td class="left"><?php foreach ($languages as $language) { ?>
																							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
																								<textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="2" style="width:90%"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
																								<br />
																								<?php } ?>
																						</td>
																						<td class="left" style="width:100px;"><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
																							</tr>
																						</tbody>
																						<?php $attribute_row++; ?>
																					<?php } ?>
																					<tfoot>
																						<tr>
																							<td colspan="2"></td>
																							<td class="right"><a onclick="addAttribute();" class="button"><?php echo $button_add_attribute; ?></a></td>
																						</tr>
																					</tfoot>
																				</table>
																			</div>				
																			<div id="tab-option" style="display:none;">
																				<div id="vtab-option" class="vtabs">
																					<?php $option_row = 0; ?>
																					<?php foreach ($product_options as $product_option) { ?>
																						<a href="#tab-option-<?php echo $option_row; ?>" id="option-<?php echo $option_row; ?>"><?php echo $product_option['name']; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#option-<?php echo $option_row; ?>').remove(); $('#tab-option-<?php echo $option_row; ?>').remove(); $('#vtabs a:first').trigger('click'); return false;" /></a>
																						<?php $option_row++; ?>
																					<?php } ?>
																					<span id="option-add">
																						<input name="option" value="" style="width: 130px;" />
																						&nbsp;<img src="view/image/add.png" alt="<?php echo $button_add_option; ?>" title="<?php echo $button_add_option; ?>" /></span></div>
																						<?php $option_row = 0; ?>
																						<?php $option_value_row = 0; ?>
																						<?php foreach ($product_options as $product_option) { ?>
																							<div id="tab-option-<?php echo $option_row; ?>" class="vtabs-content">
																								<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />			
																								<input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
																								<input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
																								<input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
																								<table class="form">
																									<tr>
																										<td><?php echo $entry_required; ?></td>
																										<td><select name="product_option[<?php echo $option_row; ?>][required]">
																											<?php if ($product_option['required']) { ?>
																												<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																												<option value="0"><?php echo $text_no; ?></option>
																											<?php } else { ?>
																												<option value="1"><?php echo $text_yes; ?></option>
																												<option value="0" selected="selected"><?php echo $text_no; ?></option>
																											<?php } ?>
																										</select></td>
																									</tr>
																									<?php if ($product_option['type'] == 'text') { ?>
																										<tr>
																											<td><?php echo $entry_option_value; ?></td>
																											<td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
																										</tr>
																									<?php } ?>
																									<?php if ($product_option['type'] == 'textarea') { ?>
																										<tr>
																											<td><?php echo $entry_option_value; ?></td>
																											<td><textarea name="product_option[<?php echo $option_row; ?>][option_value]" cols="40" rows="5"><?php echo $product_option['option_value']; ?></textarea></td>
																										</tr>
																									<?php } ?>
																									<?php if ($product_option['type'] == 'file') { ?>
																										<tr style="display: none;">
																											<td><?php echo $entry_option_value; ?></td>
																											<td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
																										</tr>
																									<?php } ?>
																									<?php if ($product_option['type'] == 'date') { ?>
																										<tr>
																											<td><?php echo $entry_option_value; ?></td>
																											<td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="date" /></td>
																										</tr>
																									<?php } ?>
																									<?php if ($product_option['type'] == 'datetime') { ?>
																										<tr>
																											<td><?php echo $entry_option_value; ?></td>
																											<td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="datetime" /></td>
																										</tr>
																									<?php } ?>
																									<?php if ($product_option['type'] == 'time') { ?>
																										<tr>
																											<td><?php echo $entry_option_value; ?></td>
																											<td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="time" /></td>
																										</tr>
																									<?php } ?>
																								</table>
																								<?php if ($product_option['type'] == 'select' || $product_option['type'] == 'block' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
																									<table id="option-value<?php echo $option_row; ?>" class="list">
																										<thead>
																											<tr>
																												<td class="left"><?php echo $entry_option_value; ?></td>
																												<td class="right"><?php echo $entry_quantity; ?></td>
																												<td class="left"><?php echo $entry_subtract; ?></td>
																												<td class="right"><?php echo $entry_price; ?></td>
																												<td class="right"><?php echo $entry_option_points; ?></td>
																												<td class="right"><?php echo $entry_weight; ?></td>
																												<td class="right" style="width:10%;"><?php echo $entry_sku; ?></td>
																												<td class="right"><?php echo $entry_image; ?></td>
																												<td></td>
																											</tr>
																										</thead>
																										<?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
																											<tbody id="option-value-row<?php echo $option_value_row; ?>">
																												<tr <? if ($option_value_row % 2 == 0) { ?>style="border-left:2px solid green;"<? } else {?>style="border-left:2px solid orange;"<? } ?>>
																													<td class="left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]">
																														<?php if (isset($option_values[$product_option['option_id']])) { ?>
																															<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																																<?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
																																	<option value="<?php echo $option_value['option_value_id']; ?>" selected="selected"><?php echo $option_value['name']; ?></option>
																																<?php } else { ?>
																																	<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
																																<?php } ?>
																															<?php } ?>
																														<?php } ?>
																													</select>
																													<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][this_is_product_id]" value="<?php echo $product_option_value['this_is_product_id']; ?>" />
																													<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
																													<td class="right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="3" /></td>
																													<td class="left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]">
																														<?php if ($product_option_value['subtract']) { ?>
																															<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																															<option value="0"><?php echo $text_no; ?></option>
																														<?php } else { ?>
																															<option value="1"><?php echo $text_yes; ?></option>
																															<option value="0" selected="selected"><?php echo $text_no; ?></option>
																														<?php } ?>
																													</select></td>
																													<td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]">
																														<option value="+" <?php echo ($product_option_value['price_prefix'] == '+') ? 'selected="selected"' : '' ?>>+</option>
																														<option value="-" <?php echo ($product_option_value['price_prefix'] == '-') ? 'selected="selected"' : '' ?>>-</option>
																														<option value="*" <?php echo ($product_option_value['price_prefix'] == '*') ? 'selected="selected"' : '' ?>>*</option>
																														<option value="%" <?php echo ($product_option_value['price_prefix'] == '%') ? 'selected="selected"' : '' ?>>%</option>
																														<option value="=" <?php echo ($product_option_value['price_prefix'] == '=') ? 'selected="selected"' : '' ?>>=</option>
																														<option value="1" <?php echo ($product_option_value['price_prefix'] == '1') ? 'selected="selected"' : '' ?>>1</option>
																													</select>
																													<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" size="5" /></td>
																													<td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]">
																														<?php if ($product_option_value['points_prefix'] == '+') { ?>
																															<option value="+" selected="selected">+</option>
																														<?php } else { ?>
																															<option value="+">+</option>
																														<?php } ?>
																														<?php if ($product_option_value['points_prefix'] == '-') { ?>
																															<option value="-" selected="selected">-</option>
																														<?php } else { ?>
																															<option value="-">-</option>
																														<?php } ?>
																													</select>
																													<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" size="3" /></td>
																													<td class="right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]">
																														<?php if ($product_option_value['weight_prefix'] == '+') { ?>
																															<option value="+" selected="selected">+</option>
																														<?php } else { ?>
																															<option value="+">+</option>
																														<?php } ?>
																														<?php if ($product_option_value['weight_prefix'] == '-') { ?>
																															<option value="-" selected="selected">-</option>
																														<?php } else { ?>
																															<option value="-">-</option>
																														<?php } ?>
																													</select>
																													<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" size="3" /></td>
																													<td class="right">
																														<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][ob_sku]" value="<?php echo $product_option_value['ob_sku']; ?>" size="4" />
																														<br/><input type="checkbox" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][ob_sku_override]" value="1" size="4" id="sku_override_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" <?php echo (isset($product_option_value['ob_sku_override']) && $product_option_value['ob_sku_override']) ? 'checked="checked"' : ''; ?>/><lable for="sku_override_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>"><?php echo $text_sku_override; ?></label>
																														</td>
																														<td>
																															<img src="<?php echo $product_option_value['preview']; ?>" alt="<?php echo $product_option_value['ob_image']; ?>" id="preview_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" onclick="image_upload('image_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>', 'preview_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>');"/>
																															<input type="hidden" id="image_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][ob_image]" value="<?php echo $product_option_value['ob_image']; ?>" />
																															<br/><a onclick="$('#preview_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>').attr('src', '<?php echo $this->model_tool_image->resize('no_image.jpg', 38, 38); ?>'); $('#image_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>').attr('value', '');">Clear</a>
																														</td>
																														<td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>

																													</tr>
																													<tr <?php if ($option_value_row % 2 == 0) { ?>style="border-left:2px solid green;border-bottom:2px solid green;"<?php } else {?>style="border-left:2px solid orange;border-bottom:2px solid orange;"<? } ?>><td class="left"><?php echo $entry_info; ?></td><td colspan="8" class="left"><input style="font-size:10px;" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][ob_info]" value="<?php echo $product_option_value['ob_info']; ?>" size="100">
																														<?php if ($product_option_value['this_is_product_id']) { ?>
																															<span style="font-size:10px;"><br />Привязан товар: <?php echo $product_option_value['this_is_product_id']; ?>
																															<?php if ($product_option_value['linked_product']) { ?>
																																<span style="color:green">ok, <a target="_blank" style="color:green;" href="<?php echo $this->url->link('catalog/product/update', 'product_id='. $product_option_value['this_is_product_id'] .'&token=' . $this->session->data['token'], 'SSL'); ?>"><?php echo $product_option_value['linked_product']['name']; ?></a></span>
																															<?php } else { ?>
																																<span style="color:red">Товар не существует, перезапустите парсер опций</span>
																															<?php } ?>
																														</span>
																													<?php } ?>
																												</td></tr>
																											</tbody>
																											<?php $option_value_row++; ?>
																										<?php } ?>
																										<tfoot>
																											<tr>
																												<td colspan="7"></td>
																												<td class="left" colspan="2"><a onclick="addOptionValue('<?php echo $option_row; ?>');" class="button"><?php echo $button_add_option_value; ?></a></td>
																											</tr>
																										</tfoot>
																									</table>
																									<select id="option-values<?php echo $option_row; ?>" style="display: none;">
																										<?php if (isset($option_values[$product_option['option_id']])) { ?>
																											<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																												<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
																											<?php } ?>
																										<?php } ?>
																									</select>
																								<?php } ?>
																							</div>
																							<?php $option_row++; ?>
																						<?php } ?>

																						<?php //Q: Options Boost ?>
																						<?php $this->load->language('catalog/options_boost'); ?>
																						<div style="padding-left:210px;"><?php echo $this->language->get('entry_batch'); ?>
																						<table border="0">
																							<tr>
																								<td style="padding: 0;"><select id="category_batchoption" style="margin-bottom: 5px;" onchange="getProductsBatchOption();">
																									<?php foreach ($categories as $category) { ?>
																										<option value="<?php echo $category['category_id']; ?>"><?php echo addslashes($category['name']); ?></option>
																									<?php } ?>
																								</select></td>
																								<td></td>
																								<td><input id="batchdelete" class="checkbox" type="checkbox" name="batchdelete" value="1" /><label for="batchdelete"><?php echo $this->language->get('entry_batchdelete');?></label></td>
																							</tr>
																							<tr>
																								<td style="padding: 0;">
																									<select multiple="multiple" id="batchoption_product" size="6" style="width: 300px;">
																									</select>
																								</td>
																								<td style="vertical-align: middle;">
																									<input type="button" value="--&gt;" onclick="addBatchOption();" />
																									<br />
																									<input type="button" value="&lt;--" onclick="removeBatchOption();" />
																								</td>
																								<td style="padding: 0;">
																									<select multiple="multiple" id="batchoption" size="6" style="width: 300px;">
																									</select>
																								</td>
																							</tr>
																						</table>
																						<div id="product_batchoption"></div>
																					</div>
																				</div>
																				<div id="tab-profile" style="display:none;">
																					<table class="list">
																						<thead>
																							<tr>
																								<td class="left"><?php echo $entry_profile ?></td>
																								<td class="left"><?php echo $entry_customer_group ?></td>
																								<td class="left"></td>
																							</tr>
																						</thead>
																						<tbody>
																							<?php $profileCount = 0; ?>
																							<?php foreach ($product_profiles as $product_profile): ?>
																								<?php $profileCount++ ?>

																								<tr id="profile-row<?php echo $profileCount ?>">
																									<td class="left">
																										<select name="product_profiles[<?php echo $profileCount ?>][profile_id]">
																											<?php foreach ($profiles as $profile): ?>
																												<?php if ($profile['profile_id'] == $product_profile['profile_id']): ?>
																													<option value="<?php echo $profile['profile_id'] ?>" selected="selected"><?php echo $profile['name'] ?></option>
																												<?php else: ?>
																													<option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>
																												<?php endif; ?>
																											<?php endforeach; ?>
																										</select>
																									</td>
																									<td class="left">
																										<select name="product_profiles[<?php echo $profileCount ?>][customer_group_id]">
																											<?php foreach ($customer_groups as $customer_group): ?>
																												<?php if ($customer_group['customer_group_id'] == $product_profile['customer_group_id']): ?>
																													<option value="<?php echo $customer_group['customer_group_id'] ?>" selected="selected"><?php echo $customer_group['name'] ?></option>
																												<?php else: ?>
																													<option value="<?php echo $customer_group['customer_group_id'] ?>"><?php echo $customer_group['name'] ?></option>
																												<?php endif; ?>
																											<?php endforeach; ?>
																										</select>
																									</td>
																									<td class="right">
																										<a class="button" onclick="$('#profile-row<?php echo $profileCount ?>').remove()"><?php echo $button_remove ?></a>
																									</td>
																								</tr>

																							<?php endforeach; ?>
																						</tbody>
																						<tfoot>
																							<tr>
																								<td colspan="2"></td>
																								<td class="right"><a onclick="addProfile()" class="button"><?php echo $button_add_profile ?></a></td>
																							</tr>
																						</tfoot>
																					</table>
																				</div>
																				<div id="tab-product-option" style="display:none;">
																					<div id="vtab-product-option" class="vtabs">
																						<?php $product_product_option_row = 0; ?>
																						<?php foreach ($product_product_options as $product_product_option) { ?>
																							<a href="#tab-product-option-<?php echo $product_product_option_row; ?>" id="product-option-<?php echo $product_product_option_row; ?>"><?php echo $product_product_option['name']; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#vtab-product-option a:first').trigger('click'); $('#product-option-<?php echo $product_product_option_row; ?>').remove(); $('#tab-product-option-<?php echo $product_product_option_row; ?>').remove(); return false;" /></a>
																							<?php $product_product_option_row++; ?>
																						<?php } ?>
																						<span id="product-option-add">
																							<select name="product_category_option" style="width: 136px;">
																								<option value="0"><?php echo $text_none; ?></option>
																								<?php foreach ($categories as $category) { ?>
																									<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
																								<?php } ?>
																							</select>
																							&nbsp;<img src="view/image/add.png" alt="<?php echo $button_add_option; ?>" title="<?php echo $button_add_option; ?>" /></span></div>
																							<?php $product_product_option_row = 0; ?>
																							<?php $product_product_option_value_row = 0; ?>
																							<?php foreach ($product_product_options as $product_option) { ?>
																								<div id="tab-product-option-<?php echo $product_product_option_row; ?>" class="vtabs-content">
																									<input type="hidden" name="product_product_option[<?php echo $product_product_option_row; ?>][category_id]" value="<?php echo $product_option['category_id']; ?>" />
																									<table class="form">
																										<tr>
																											<td><?php echo $entry_type; ?></td>
																											<td><select name="product_product_option[<?php echo $product_product_option_row; ?>][type]">
																												<option value="radio"<?php if ($product_option['type'] == 'radio') { ?> selected="selected"<?php } ?>><?php echo $text_type_radio; ?></option>
																												<option value="select"<?php if ($product_option['type'] == 'select') { ?> selected="selected"<?php } ?>><?php echo $text_type_select; ?></option>
																												<option value="checkbox"<?php if ($product_option['type'] == 'checkbox') { ?> selected="selected"<?php } ?>><?php echo $text_type_checkbox; ?></option>
																											</select></td>
																										</tr>
																										<tr>
																											<td><?php echo $entry_required; ?></td>
																											<td><select name="product_product_option[<?php echo $product_product_option_row; ?>][required]">
																												<option value="0"<?php if ($product_option['required'] == '0') { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
																												<option value="1"<?php if ($product_option['required'] == '1') { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
																											</select></td>
																										</tr>
																										<tr>
																											<td><?php echo $entry_sort_order; ?></td>
																											<td><input type="text" name="product_product_option[<?php echo $product_product_option_row; ?>][sort_order]" value="<?php echo $product_option['sort_order']; ?>" size="2" /></td>
																										</tr>
																										<tr>
																											<td><?php echo $entry_name; ?></td>
																											<td><input type="text" name="product_option<?php echo $product_product_option_row; ?>" value="" /></td>
																										</tr>
																									</table>
																									<table id="product-option-value<?php echo $product_product_option_row; ?>" class="list">
																										<thead>
																											<tr>
																												<td class="left"><?php echo $entry_image; ?></td>
																												<td class="left"><?php echo $entry_name; ?></td>
																												<td class="right"><?php echo $entry_price; ?></td>
																												<td class="right" data="mi-compat"><?php echo $entry_sort_order; ?></td>
																												<td></td>
																											</tr>
																										</thead>
																										<?php foreach ($product_option['product_option'] as $product_option_value) { ?>
																											<tbody id="product-product-option-row<?php echo $product_product_option_row . $product_product_option_value_row; ?>">
																												<tr>
																													<td class="left"><img src="<?php echo $product_option_value['image']; ?>" alt="" /></td>
																													<td class="left"><?php echo $product_option_value['name']; ?></td>
																													<td class="right">
																														<?php if ($product_option_value['special']) { ?>
																															<span style="text-decoration: line-through;"><?php echo $product_option_value['price']; ?></span><br/><span style="color: #b00;"><?php echo $product_option_value['special']; ?></span>
																														<?php } else { ?>
																															<?php echo $product_option_value['price']; ?>
																														<?php } ?>
																													</td>
																													<td class="right"><input type="text" name="product_product_option[<?php echo $product_product_option_row; ?>][product_option][<?php echo $product_product_option_value_row; ?>][sort_order]" value="<?php echo $product_option_value['sort_order']; ?>" size="2" /></td>
																													<td><a onclick="$('#product-product-option-row<?php echo $product_product_option_row . $product_product_option_value_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a><input type="hidden" name="product_product_option[<?php echo $product_product_option_row; ?>][product_option][<?php echo $product_product_option_value_row; ?>][product_id]" value="<?php echo $product_option_value['product_option_id']; ?>" /></td>
																												</tr>
																											</tbody>
																											<?php $product_product_option_value_row++; ?>
																										<?php } ?>
																										<tfoot></tfoot>
																									</table>			
																								</div>
																								<?php $product_product_option_row++; ?>
																							<?php } ?>
																						</div>
																						<div id="tab-discount">
																							<table id="discount" class="list">
																								<thead>
																									<tr>
																										<td class="left"><?php echo $entry_customer_group; ?></td>
																										<td class="right"><?php echo $entry_quantity; ?></td>
																										<td class="right"><?php echo $entry_priority; ?></td>
																										<td class="right"><?php echo $entry_price; ?></td>
																										<td class="left"><?php echo $entry_date_start; ?></td>
																										<td class="left"><?php echo $entry_date_end; ?></td>
																										<td></td>
																									</tr>
																								</thead>
																								<?php $discount_row = 0; ?>
																								<?php foreach ($product_discounts as $product_discount) { ?>
																									<tbody id="discount-row<?php echo $discount_row; ?>">
																										<tr>
																											<td class="left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]">
																												<?php foreach ($customer_groups as $customer_group) { ?>
																													<?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
																														<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
																													<?php } else { ?>
																														<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
																													<?php } ?>
																												<?php } ?>
																											</select></td>
																											<td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
																											<td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" /></td>
																											<td class="right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
																											<td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" class="date" /></td>
																											<td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" class="date" /></td>
																											<td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
																										</tr>
																									</tbody>
																									<?php $discount_row++; ?>
																								<?php } ?>
																								<tfoot>
																									<tr>
																										<td colspan="6"></td>
																										<td class="right"><a onclick="addDiscount();" class="button"><?php echo $button_add_discount; ?></a></td>
																									</tr>
																								</tfoot>
																							</table>
																						</div>
																						<div id="tab-special">
																							<table id="special" class="list">
																								<thead>
																									<tr>
																										<td class="left"><?php echo $entry_customer_group; ?></td>
																										<td class="right">Виртуальный магазин</td>
																										<td class="right">Валюта</td>
																										<td class="right"><?php echo $entry_priority; ?></td>
																										<td class="right"><?php echo $entry_price; ?></td>										
																										<td class="left"><?php echo $entry_date_start; ?></td>
																										<td class="left" colspan="2"><?php echo $entry_date_end; ?></td>
																									</tr>
																								</thead>
																								<?php $special_row = 0; ?>

																								<? 
																								$stores_special = $stores;
																								array_unshift($stores_special, array('store_id' => 0, 'name' => 'Основной магазин'));
																								array_unshift($stores_special, array('store_id' => -1, 'name' => 'Для всех магазинов'));
																								?>

																								<?php foreach ($product_specials as $product_special) { ?>
																									<tbody id="special-row<?php echo $special_row; ?>">
																										<tr>
																											<td class="left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]">
																												<?php foreach ($customer_groups as $customer_group) { ?>
																													<?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
																														<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
																													<?php } else { ?>
																														<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
																													<?php } ?>
																												<?php } ?>
																											</select></td>
																											<td class="left">
																												<select name="product_special[<?php echo $special_row; ?>][store_id]">
																													<?php foreach ($stores_special as $store) { ?>
																														<?php if ($store['store_id'] == $product_special['store_id']) { ?>
																															<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
																														<?php } else { ?>
																															<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
																														<?php } ?>
																													<?php } ?>
																												</select>
																											</td>
																											<td class="left">
																												<select name="product_special[<?php echo $special_row; ?>][currency_scode]">
																													<option value="">Дефолтная</option>
																													<?php unset($c); foreach ($currencies as $c) { ?>
																														<?php if ($c['code'] == $product_special['currency_scode']) { ?>
																															<option value="<?php echo $c['code']; ?>" selected="selected"><?php echo $c['code']; ?></option>
																														<?php } else { ?>
																															<option value="<?php echo $c['code']; ?>"><?php echo $c['code']; ?></option>
																														<?php } ?>
																													<?php } ?>
																												</select>
																											</td>
																											<td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
																											<td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
																											<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" /></td>
																											<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" /></td>
																											<td class="left"><a onclick="$('#special-row<?php echo $special_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
																										</tr>
																									</tbody>
																									<?php $special_row++; ?>
																								<?php } ?>
																								<tfoot>
																									<tr>
																										<td colspan="6"></td>
																										<td class="right"><a onclick="addSpecial();" class="button"><?php echo $button_add_special; ?></a></td>
																									</tr>
																								</tfoot>
																							</table>
																						</div>
																						<div id="tab-markdown">
																							<table id="additional-offer" class="form">
																								<tr>						
																									<td>Уцененный товар</td>
																									<td><select name="is_markdown">
																										<?php if ($is_markdown) { ?>
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
																								<td>Родительский товар</td>
																								<td>
																									<input id="markdown_product" value="<?php echo $markdown_product; ?>" name="markdown_product" type="text" style="width:400px;" /> <span style="border-bottom:1px dashed black;" onclick="$('#markdown_product').val(''); $('#markdown_product_id').val('');">очистить</span>
																									<br />
																									<span class="help">автоподбор</span>
																									<input  id="markdown_product_id" name="markdown_product_id" value="<?php echo $markdown_product_id; ?>" type="hidden" />
																								</td>
																							</tr>
																						</table>

																						<div id="tab-markdown-descriptions">
																							<div id="markdown-languages" class="htabs">
																								<?php foreach ($languages as $language) { ?>
																									<a href="#markdown-language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
																								<?php } ?>
																								<div class="clr"></div>
																							</div>

																							<?php foreach ($languages as $language) { ?>
																								<div id="markdown-language<?php echo $language['language_id']; ?>">
																									<table class="form">
																										<tr>
																											<td>Уценка: внешний вид</td>
																											<td>
																												<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_appearance]" id="markdown_appearance<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_appearance'] : ''; ?></textarea>
																											</td>
																										</tr>
																										<tr>
																											<td>Уценка: состояние</td>
																											<td>
																												<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_condition]" id="markdown_condition<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_condition'] : ''; ?></textarea>
																											</td>
																										</tr>
																										<tr>
																											<td>Уценка: упаковка</td>
																											<td>
																												<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_pack]" id="markdown_pack<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_pack'] : ''; ?></textarea>
																											</td>
																										</tr>
																										<tr>
																											<td>Уценка: комплектация</td>
																											<td>
																												<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_equipment]" id="markdown_equipment<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_equipment'] : ''; ?></textarea>
																											</td>
																										</tr>
																									</table>
																								</div>
																							<?php } ?>
																						</div>

																					</div>
																					<!--Additional offer-->

																					<? 
																					$stores_ao = $stores;							
																					?>

																					<div id="tab-additional-offer">
																						<table id="additional-offer" class="list">
																							<thead>
																								<tr>
																									<td class="left">Группа</td>										
																									<td class="right">Магазины</td>
																									<td class="right">Приор.</td>
																									<td class="right">Кол-во</td>
																									<td class="right"><?php echo $entry_name; ?></td>
																									<td class="right"><?php echo $entry_price; ?>|<?php echo $entry_percent; ?></td>
																									<td class="left">Даты</td>
																									<td class="left" colspan="2"><?php echo $entry_image; ?></td>
																								</tr>
																							</thead>
																							<?php $additional_offer_row = 0; ?>
																							<?php foreach ($product_additional_offer as $product_ao) { ?>
																								<input type="hidden" name="product_additional_offer[<?php echo $additional_offer_row; ?>][product_additional_offer_id]" value="<?php echo $product_ao['product_additional_offer_id']; ?>" />
																								<tbody id="additional-offer-row<?php echo $additional_offer_row; ?>">
																									<tr>
																										<td class="left"><select name="product_additional_offer[<?php echo $additional_offer_row; ?>][customer_group_id]" style="width:150px;">
																											<?php foreach ($customer_groups as $customer_group) { ?>
																												<?php if ($customer_group['customer_group_id'] == $product_ao['customer_group_id']) { ?>
																													<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
																												<?php } else { ?>
																													<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
																												<?php } ?>
																											<?php } ?>
																										</select>
																										<br /><br />
																										<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][ao_group]" value="<?php echo $product_ao['ao_group']; ?>" size="20" />

																									</td>
																									<td>
																										<div class="scrollbox" style="min-height: 100px;">
																											<?php $class = 'even'; ?>												
																											<?php foreach ($stores_ao as $store_ao) { ?>
																												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																												<div class="<?php echo $class; ?>">
																													<?php if (in_array($store_ao['store_id'], $product_ao['store_id'])) { ?>
																														<input id="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store_ao['store_id']; ?>" class="checkbox" type="checkbox" name="product_additional_offer[<?php echo $additional_offer_row; ?>][store_id][]" value="<?php echo $store_ao['store_id']; ?>" checked="checked" />
																														<label for="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store_ao['store_id']; ?>"><?php echo $store_ao['name']; ?></label>
																													<?php } else { ?>
																														<input id="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store_ao['store_id']; ?>" class="checkbox" type="checkbox" name="product_additional_offer[<?php echo $additional_offer_row; ?>][store_id][]" value="<?php echo $store_ao['store_id']; ?>" />
																														<label for="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store_ao['store_id']; ?>"><?php echo $store_ao['name']; ?></label>
																													<?php } ?>
																												</div>
																											<?php } ?>

																										</div>
																										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Все</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять</a>
																									</td>


																									<td class="right"><input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][priority]" value="<?php echo $product_ao['priority']; ?>" size="2" style="width:20px;" /></td>
																									<td class="right"><input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][quantity]" value="<?php echo $product_ao['quantity']; ?>" size="2" style="width:20px;" /></td>

																									<td class="right" width="350px;">
																										<input type="text" value="<?php echo $product_ao['product_ao_name']; ?>" size="30" class="ao_product_name" data-row="<?php echo $additional_offer_row; ?>" name="aop<?php echo $additional_offer_row; ?>" style="width:340px;" />
																										<input type="hidden" value="<?php echo $product_ao['ao_product_id']; ?>" name="product_additional_offer[<?php echo $additional_offer_row; ?>][ao_product_id]" value="" />
																									</td>

																									<td class="right">
																										<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][price]" value="<?php echo $product_ao['price']; ?>" />$$
																										<br /><br />
																										<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][percent]" value="<?php echo $product_ao['percent']; ?>" />%</td>
																										<td class="left" width="120px;">

																											<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][date_start]" value="<?php echo $product_ao['date_start']; ?>" class="date" /><i class="fa fa-hourglass-start" aria-hidden="true"></i>
																											<br /><br />
																											<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][date_end]" value="<?php echo $product_ao['date_end']; ?>" class="date" /><i class="fa fa-hourglass-end" aria-hidden="true"></i>


																										</td>
																										<td class="left"><div class="image"><img src="<?php echo $product_ao['thumb']; ?>" alt="" id="thumb<?php echo $additional_offer_row; ?>" />
																											<input type="hidden" name="product_additional_offer[<?php echo $additional_offer_row; ?>][image]" value="<?php echo $product_ao['image']; ?>" id="image<?php echo $additional_offer_row; ?>" />
																											<br />
																											<a onclick="image_upload('image<?php echo $additional_offer_row; ?>', 'thumb<?php echo $additional_offer_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $additional_offer_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $additional_offer_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>


																											<td class="left">

																												<? /*		<a onclick="$('#additional-offer-row<?php echo $additional_offer_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a> */ ?></td>
																											</tr>

																											<tr style="display:none;">
																												<td class="left" colspan="8">												
																													<textarea style="width:90%;" id="ao-description<?php echo $additional_offer_row; ?>"  name="product_additional_offer[<?php echo $additional_offer_row; ?>][description]"><?php echo $product_ao['description']; ?></textarea>
																												</td>
																											</tr>
																										</tbody>
																										<?php $additional_offer_row++; ?>
																									<?php } ?>
																									<tfoot>
																										<tr>		
																											<td class="right" colspan="10"><a onclick="addAdditionalOffer();" class="button"><?php echo $button_add_ao; ?></a></td>
																										</tr>
																									</tfoot>
																								</table>
																							</div>
																							<!--Additional offer-->
																							<div id="tab-image">
																								<table id="images" class="list">
																									<thead>
																										<tr>
																											<td class="left"><?php echo $entry_image; ?></td>
																											<td class="right"><?php echo $entry_sort_order; ?></td>
																											<?php if ($this->config->get('pim_status')) { ?>
																												<td>Сделать главной</td>
																											<?php } ?>
																											<td></td>
																										</tr>
																									</thead>
																									<?php $image_row = 0; ?>
																									<?php foreach ($product_images as $product_image) { ?>
																										<tbody id="image-row<?php echo $image_row; ?>">
																											<tr>
																												<td class="left"><div class="image"><img src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
																													<input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" />
																													<br />
																													<a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
																													<td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" size="2" /></td>
																													<?php if ($this->config->get('pim_status')) {?>
																														<td><input type="radio" name="def_img" value="<?php  if (isset($product_image['image'])) { echo $product_image['image']; } ?>">
																														</td>
																													<?php } ?>    
																													<td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
																												</tr>
																											</tbody>
																											<?php $image_row++; ?>
																										<?php } ?>
																										<tfoot>
																											<tr>
																												<td class="right" colspan="4"><?php if ($this->config->get('pim_status')) {?>
																													<a id="file-uploader" class="button"></a>
																													<a onclick="image_upload();" class="button">Управление изображениями</a>&nbsp;<?php } ?>
																													<a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a>																										
																												</td>
																											</tr>
																										</tfoot>
																									</table>																									
																								</div>
																								<div id="tab-videos">

																									<table id="videos" class="list">
																										<thead>
																											<tr>
																												<td class="left"style="width:150px;">Превьюшка</td>
																												<td class="left" style="width:200px;">Видео</td>
																												<td class="right">Заголовок</td>																					
																												<td class="right" style="width:50px;">Сортировка</td>
																												<td style="width:100px;"></td>
																											</tr>
																										</thead>
																										<?php $video_row = 0; ?>
																										<?php foreach ($product_videos as $product_video) { ?>
																											<tbody id="video-row<?php echo $video_row; ?>">
																												<tr>
																													<td class="left">
																														<div class="video">
																															<img src="<?php echo $product_video['thumb']; ?>" alt="" id="videothumb<?php echo $video_row; ?>" />
																															<input type="hidden" name="product_video[<?php echo $video_row; ?>][image]" value="<?php echo $product_video['image']; ?>" id="videoimage<?php echo $video_row; ?>" />
																															<br />
																															<a onclick="image_upload('videoimage<?php echo $video_row; ?>', 'videothumb<?php echo $video_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $video_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#videoimage<?php echo $video_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
																														</div>
																														</td>

																														<td class="right">
																															<textarea name="product_video[<?php echo $video_row; ?>][video]" rows="4" cols="50"><?php echo $product_video['video']; ?></textarea>

																															<br />
																															<a href="<?php echo $product_video['play'] ?>" target="_blank"><i class="fa fa-play"></i> <?php echo $product_video['play']; ?></a>
																														</td>

																														<td class="right">
																															<?php foreach ($languages as $language) { ?>
																																<input style="width:90%;" type="text" name="product_video[<?php echo $video_row; ?>][product_video_description][<?php echo $language['language_id'] ?>][title];" 
																																value="<?php if (isset($product_video['product_video_description'][$language['language_id']])) { echo $product_video['product_video_description'][$language['language_id']]['title']; } ?>" />
																																<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
																															<?php } ?>
																														</td>

																														<td class="right">
																															<input type="text" name="product_video[<?php echo $video_row; ?>][sort_order]" value="<?php echo $product_video['sort_order']; ?>" size="2" />
																														</td>

																														<td class="left">
																															<a onclick="$('#video-row<?php echo $video_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a>
																														</td>
																													</tr>
																												</tbody>
																												<?php $video_row++; ?>
																											<?php } ?>
																											<tfoot>
																												<tr>
																													<td class="right" colspan="5">
																														<a onclick="addvideo();" class="button">Добавить видео</a>																										
																													</td>
																												</tr>
																											</tfoot>
																										</table>

																									<table class="list">
																										<tr>																											
																											<td>
																												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Youtube Видео (старая логика, работает только с Youtube)</span>
																												<textarea name="youtube" cols="200" rows="3"><?php echo $youtube; ?></textarea>
																												<span class="help">
																													Например, Mf8YQR9n47U<br />
																													Если несколько, то через запятую Mf8YQR9n47U,Zk8YQR9n47U 
																												</span>
																											</td>
																										</tr>
																									</table>


																								</div>
																								<div id="tab-reward">
																									<table class="form" style="display:none;">
																										<tr>
																											<td><?php echo $entry_points; ?></td>
																											<td><input type="text" name="points" value="<?php echo $points; ?>" /></td>
																										</tr>
																									</table>

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

																										<?php foreach ($product_reward as $reward) { ?>
																											<tbody id="reward-row<?php echo $reward_row; ?>">
																												<tr>

																													<td class="center">
																														<select name="product_reward[<?php echo $reward_row; ?>][store_id]">
																															<?php foreach ($stores_reward as $store) { ?>
																																<?php if ($store['store_id'] == $reward['store_id']) { ?>
																																	<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
																																<?php } else { ?>
																																	<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
																																<?php } ?>
																															<?php } ?>
																														</select>
																													</td>

																													<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][points]" value="<?php echo $reward['points']; ?>" size="10" />☯</td>

																													<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][percent]" value="<?php echo $reward['percent']; ?>" size="2" />%</td>								
																													<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][date_start]" value="<?php echo $reward['date_start']; ?>" class="date" /></td>

																													<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][date_end]" value="<?php echo $reward['date_end']; ?>" class="date" /></td>


																													<td class="center">

																														<select name="product_reward[<?php echo $reward_row; ?>][coupon_acts]">
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

																										html += '    <td class="center"><select name="product_reward[' + reward_row + '][store_id]">';
																										<?php foreach ($stores_reward as $store) { ?>
																											html += '      <option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
																										<?php } ?>
																										html += '    </select></td>';

																										html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][points]" value="" size="10" />☯</td>';
																										html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][percent]" value="" size="2" />%</td>';							
																										html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][date_start]" value="" class="date" /></td>';
																										html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][date_end]" value="" class="date" /></td>';
																										html += '       <td class="center"><select name="product_reward[' + reward_row + '][coupon_acts]">';
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
																								<div id="tab-design" style="display:none;">
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
																												<td class="left"><select name="product_layout[0][layout_id]">
																													<option value=""></option>
																													<?php foreach ($layouts as $layout) { ?>
																														<?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
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
																													<td class="left"><select name="product_layout[<?php echo $store['store_id']; ?>][layout_id]">
																														<option value=""></option>
																														<?php foreach ($layouts as $layout) { ?>
																															<?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
																								</div>
																							</form>
																						</div>
																					</div>
																				</div>


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

																					CKEDITOR.replace('markdown_appearance<?php echo $language['language_id']; ?>', {
																						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
																					});

																					CKEDITOR.replace('markdown_condition<?php echo $language['language_id']; ?>', {
																						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
																					});

																					CKEDITOR.replace('markdown_pack<?php echo $language['language_id']; ?>', {
																						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
																					});

																					CKEDITOR.replace('markdown_equipment<?php echo $language['language_id']; ?>', {
																						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
																						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
																					});
																				<?php } ?>
																				//--></script> 
																				<!--Additional offer-->

<!--Additional offer-->
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';

		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

				currentCategory = item.category;
			}

			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'collection\']').autocomplete({
	delay: 500,
	source: function(request, response) {		
		$.ajax({
			url: 'index.php?route=catalog/collection/autocomplete&token=<?php echo $token; ?>&filter_manufacturer_id='+ $('input[name=manufacturer_id]').val() +'&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			error:function(json){
				console.log(json)				
			},
			success: function(json) {
				json.unshift({
					'collection_id':  0,
					'name':  'Не выбрано'
				});

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
		$('input[name=\'collection\']').val(ui.item.label);
		$('input[name=\'collection_id\']').val(ui.item.value);

		return false;
	},
	focus: function(event, ui) {
		return false;
	}
});

	// Manufacturer
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
			$('input[name=\'manufacturer\']').attr('value', ui.item.label);
			$('input[name=\'manufacturer_id\']').attr('value', ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	// Category
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
			$('#product-category' + ui.item.value).remove();
			
			$('#product-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_category[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-category div:odd').attr('class', 'odd');
			$('#product-category div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-category div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-category div:odd').attr('class', 'odd');
		$('#product-category div:even').attr('class', 'even');	
	});
	
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
			$('#product-filter' + ui.item.value).remove();
			
			$('#product-filter').append('<div id="product-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_filter[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-filter div:odd').attr('class', 'odd');
			$('#product-filter div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-filter div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-filter div:odd').attr('class', 'odd');
		$('#product-filter div:even').attr('class', 'even');	
	});
	
	// Downloads
	$('input[name=\'download\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.download_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-download' + ui.item.value).remove();
			
			$('#product-download').append('<div id="product-download' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_download[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-download div:odd').attr('class', 'odd');
			$('#product-download div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-download div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-download div:odd').attr('class', 'odd');
		$('#product-download div:even').attr('class', 'even');	
	});

	// Related
	$('input[name=\'main_variant_product\']').autocomplete({
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
			$('input[name=\'main_variant_id\']').val(ui.item.value);
			$('input[name=\'main_variant_product\']').val(ui.item.label);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	// Related
	$('input[name=\'markdown_product\']').autocomplete({
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
			$('input[name=\'markdown_product_id\']').val(ui.item.value);
			$('input[name=\'markdown_product\']').val(ui.item.label);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	// Related
	$('input[name=\'related\']').autocomplete({
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
			$('#product-related' + ui.item.value).remove();
			
			$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-related div:odd').attr('class', 'odd');
			$('#product-related div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-related div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-related div:odd').attr('class', 'odd');
		$('#product-related div:even').attr('class', 'even');	
	});
	
	// similar
	$('input[name=\'similar\']').autocomplete({
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
			$('#product-similar' + ui.item.value).remove();
			
			$('#product-similar').append('<div id="product-similar' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_similar[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-similar div:odd').attr('class', 'odd');
			$('#product-similar div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-similar div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-similar div:odd').attr('class', 'odd');
		$('#product-similar div:even').attr('class', 'even');	
	});

	// similar_to_consider
	$('input[name=\'similar_to_consider\']').autocomplete({
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
			$('#product-similar_to_consider' + ui.item.value).remove();
			
			$('#product-similar_to_consider').append('<div id="product-similar_to_consider' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_similar_to_consider[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-similar_to_consider div:odd').attr('class', 'odd');
			$('#product-similar_to_consider div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-similar_to_consider div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-similar_to_consider div:odd').attr('class', 'odd');
		$('#product-similar_to_consider div:even').attr('class', 'even');	
	});

	// view_to_purchase
	$('input[name=\'view_to_purchase\']').autocomplete({
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
			$('#product-view_to_purchase' + ui.item.value).remove();
			
			$('#product-view_to_purchase').append('<div id="product-view_to_purchase' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_view_to_purchase[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-view_to_purchase div:odd').attr('class', 'odd');
			$('#product-view_to_purchase div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-view_to_purchase div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-view_to_purchase div:odd').attr('class', 'odd');
		$('#product-view_to_purchase div:even').attr('class', 'even');	
	});

	// also_viewed
	$('input[name=\'also_viewed\']').autocomplete({
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
			$('#product-also_viewed' + ui.item.value).remove();
			
			$('#product-also_viewed').append('<div id="product-also_viewed' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_also_viewed[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-also_viewed div:odd').attr('class', 'odd');
			$('#product-also_viewed div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-also_viewed div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-also_viewed div:odd').attr('class', 'odd');
		$('#product-also_viewed div:even').attr('class', 'even');	
	});

	// also_bought
	$('input[name=\'also_bought\']').autocomplete({
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
			$('#product-also_bought' + ui.item.value).remove();
			
			$('#product-also_bought').append('<div id="product-also_bought' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_also_bought[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-also_bought div:odd').attr('class', 'odd');
			$('#product-also_bought div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-also_bought div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-also_bought div:odd').attr('class', 'odd');
		$('#product-also_bought div:even').attr('class', 'even');	
	});

	// shop_by_look
	$('input[name=\'shop_by_look\']').autocomplete({
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
			$('#product-shop_by_look' + ui.item.value).remove();
			
			$('#product-shop_by_look').append('<div id="product-shop_by_look' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_shop_by_look[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-shop_by_look div:odd').attr('class', 'odd');
			$('#product-shop_by_look div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-shop_by_look div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-shop_by_look div:odd').attr('class', 'odd');
		$('#product-shop_by_look div:even').attr('class', 'even');	
	});

	// sponsored
	$('input[name=\'sponsored\']').autocomplete({
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
			$('#product-sponsored' + ui.item.value).remove();
			
			$('#product-sponsored').append('<div id="product-sponsored' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_sponsored[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-sponsored div:odd').attr('class', 'odd');
			$('#product-sponsored div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-sponsored div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-sponsored div:odd').attr('class', 'odd');
		$('#product-sponsored div:even').attr('class', 'even');	
	});
	
	// Child
	$('input[name=\'child\']').autocomplete({
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
			$('#product-child' + ui.item.value).remove();
			
			$('#product-child').append('<div id="product-child' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_child[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-child div:odd').attr('class', 'odd');
			$('#product-child div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-child div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-child div:odd').attr('class', 'odd');
		$('#product-child div:even').attr('class', 'even');	
	});
	//--></script> 
	<script type="text/javascript"><!--
	var attribute_row = <?php echo $attribute_row; ?>;
	
	function addAttribute() {
		html  = '<tbody id="attribute-row' + attribute_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
		html += '    <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /><br />';
		<?php } ?>
		html += '    </td>';
		html += '    <td class="right"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';	
		html += '</tbody>';
		
		$('#attribute tfoot').before(html);
		
		attributeautocomplete(attribute_row);
		
		attribute_row++;
	}
	
	function attributeautocomplete(attribute_row) {
		$('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {	
						response($.map(json, function(item) {
							return {
								category: item.attribute_group,
								label: item.name,
								value: item.attribute_id
							}
						}));
					}
				});
			}, 
			select: function(event, ui) {
				$('input[name=\'product_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
				$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	}
	
	$('#attribute tbody').each(function(index, element) {
		attributeautocomplete(index);
	});
	//--></script> 
	<script type="text/javascript"><!--	
	var option_row = <?php echo $option_row; ?>;
	
	$('input[name=\'option\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item.category,
							label: item.name,
							value: item.option_id,
							type: item.type,
							option_value: item.option_value
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			html  = '<div id="tab-option-' + option_row + '" class="vtabs-content">';
			html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + ui.item.label + '" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + ui.item.value + '" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + ui.item.type + '" />';
			html += '	<table class="form">';
			html += '	  <tr>';
			html += '		<td><?php echo $entry_required; ?></td>';
			html += '       <td><select name="product_option[' + option_row + '][required]">';
			html += '	      <option value="1"><?php echo $text_yes; ?></option>';
			html += '	      <option value="0"><?php echo $text_no; ?></option>';
			html += '	    </select></td>';
			html += '     </tr>';
			
			if (ui.item.type == 'text') {
				html += '     <tr>';
				html += '       <td><?php echo $entry_option_value; ?></td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
				html += '     </tr>';
			}
			
			if (ui.item.type == 'textarea') {
				html += '     <tr>';
				html += '       <td><?php echo $entry_option_value; ?></td>';
				html += '       <td><textarea name="product_option[' + option_row + '][option_value]" cols="40" rows="5"></textarea></td>';
				html += '     </tr>';						
			}
			
			if (ui.item.type == 'file') {
				html += '     <tr style="display: none;">';
				html += '       <td><?php echo $entry_option_value; ?></td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
				html += '     </tr>';			
			}
			
			if (ui.item.type == 'date') {
				html += '     <tr>';
				html += '       <td><?php echo $entry_option_value; ?></td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="date" /></td>';
				html += '     </tr>';			
			}
			
			if (ui.item.type == 'datetime') {
				html += '     <tr>';
				html += '       <td><?php echo $entry_option_value; ?></td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="datetime" /></td>';
				html += '     </tr>';			
			}
			
			if (ui.item.type == 'time') {
				html += '     <tr>';
				html += '       <td><?php echo $entry_option_value; ?></td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="time" /></td>';
				html += '     </tr>';			
			}
			
			html += '  </table>';
			
			if (ui.item.type == 'select' || ui.item.type == 'block' || ui.item.type == 'radio' || ui.item.type == 'checkbox' || ui.item.type == 'image') {
				html += '  <table id="option-value' + option_row + '" class="list">';
				html += '  	 <thead>'; 
				html += '      <tr>';
				html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
				html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
				html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
				html += '        <td class="right"><?php echo $entry_price; ?></td>';
				html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
				html += '        <td class="right"><?php echo $entry_weight; ?></td>';
				html += '        <td></td>';
				html += '      </tr>';
				html += '  	 </thead>';
				html += '    <tfoot>';
				html += '      <tr>';
				html += '        <td colspan="6"></td>';
				html += '        <td class="right"><a onclick="addOptionValue(' + option_row + ');" class="button"><?php echo $button_add_option_value; ?></a></td>';
				html += '      </tr>';
				html += '    </tfoot>';
				html += '  </table>';
				html += '  <select id="option-values' + option_row + '" style="display: none;">';
				
				for (i = 0; i < ui.item.option_value.length; i++) {
					html += '  <option value="' + ui.item.option_value[i]['option_value_id'] + '">' + ui.item.option_value[i]['name'] + '</option>';
				}
				
				html += '  </select>';			
				html += '</div>';	
			}
			
			$('#tab-option').append(html);
			
			$('#option-add').before('<a href="#tab-option-' + option_row + '" id="option-' + option_row + '">' + ui.item.label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#option-' + option_row + '\').remove(); $(\'#tab-option-' + option_row + '\').remove(); $(\'#vtab-option a:first\').trigger(\'click\'); return false;" /></a>');
			
			$('#vtab-option a').tabs();		
			
			$('#option-' + option_row).trigger('click');		
			
			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
			$('.datetime').datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'h:m'
			});	
			
			$('.time').timepicker({timeFormat: 'h:m'});	
			
			option_row++;
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	//--></script> 
	<script type="text/javascript"><!--		
	var option_value_row = <?php echo $option_value_row; ?>;
	
	function addOptionValue(option_row) {	
		html  = '<tbody id="option-value-row' + option_value_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]">';
		html += $('#option-values' + option_row).html();
		html += '    </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
		html += '<input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][this_is_product_id]" value="" />';
		html += '    <td class="right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>'; 
		html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]">';
		html += '      <option value="1"><?php echo $text_yes; ?></option>';
		html += '      <option value="0"><?php echo $text_no; ?></option>';
		html += '    </select></td>';
		html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]">';
		html += '      <option value="+">+</option>';
		html += '      <option value="-">-</option>';
		html += '      <option value="*">*</option>';
		html += '      <option value="%">%</option>';
		html += '      <option value="=">=</option>';
		html += '      <option value="1">1</option>';
		html += '    </select>';
		html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
		html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]">';
		html += '      <option value="+">+</option>';
		html += '      <option value="-">-</option>';
		html += '      <option value="*">*</option>';
		html += '      <option value="%">%</option>';
		html += '      <option value="=">=</option>';
		html += '      <option value="1">1</option>';
		html += '    </select>';
		html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';	
		html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]">';
		html += '      <option value="+">+</option>';
		html += '      <option value="-">-</option>';
		html += '      <option value="*">*</option>';
		html += '      <option value="%">%</option>';
		html += '      <option value="=">=</option>';
		html += '      <option value="1">1</option>';
		html += '    </select>';
		html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
		html += '    <td class="right">';
		html += '      <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][ob_sku]" value="" size="3" />';
		html += '      <br/><input type="checkbox" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][ob_sku_override]" value="1" size="4" id="sku_override_'+ option_row + '_' + option_value_row + '" /><lable for="sku_override_'+ option_row + '_' + option_value_row + '"><?php echo $text_sku_override; ?></label>';
		html += '    </td>';
		html += '    <td>';
		html += '      <img src="<?php echo HTTPS_SERVER; ?>../image/cache/no_image-38x38.jpg" alt="" id="preview_'+ option_row + '_' + option_value_row + '" onclick="image_upload(\'image_'+ option_row + '_' + option_value_row + '\', \'preview_'+ option_row + '_' + option_value_row + '\');" />';
		html += '      <input type="hidden" id="image_' + option_row + '_' + option_value_row + '" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][ob_image]" value="" />';
		html += '    </td>';
		html += '    <td class="right"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';
		html += '  <tr><td class="left"><?php echo $entry_info; ?></td><td colspan="8" class="left"><input name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][ob_info]" value="" size="100"></td></tr>';
		html += '</tbody>';
		
		$('#option-value' + option_row + ' tfoot').before(html);
		
		option_value_row++;
	}
	//--></script> 
	<script type="text/javascript"><!--
	var discount_row = <?php echo $discount_row; ?>;
	
	function addDiscount() {
		html  = '<tbody id="discount-row' + discount_row + '">';
		html += '  <tr>'; 
		html += '    <td class="left"><select name="product_discount[' + discount_row + '][customer_group_id]">';
		<?php foreach ($customer_groups as $customer_group) { ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '    </select></td>';		
		html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
		html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
		html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" /></td>';
		html += '    <td class="left"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
		html += '    <td class="left"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
		html += '    <td class="right"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';	
		html += '</tbody>';
		
		$('#discount tfoot').before(html);
		
		$('#discount-row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
		
		discount_row++;
	}
	//--></script> 
	<script type="text/javascript"><!--
	var special_row = <?php echo $special_row; ?>;
	
	function addSpecial() {
		html  = '<tbody id="special-row' + special_row + '">';
		html += '  <tr>'; 
		html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
		<?php foreach ($customer_groups as $customer_group) { ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '    </select></td>';
		html += '    <td class="left"><select name="product_special[' + special_row + '][store_id]">';
		<?php foreach ($stores_special as $store) { ?>
			html += '      <option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
		<?php } ?>
		html += '    </select></td>';
		html += '    <td class="left"><select name="product_special[' + special_row + '][currency_scode]">';
		html += '    <option value="">Дефолтная</option>';
		<?php foreach ($currencies as $c) { ?>
			html += '      <option value="<?php echo $c['code']; ?>"><?php echo addslashes($c['code']); ?></option>';
		<?php } ?>
		html += '    </select></td>';
		html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
		html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
		html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
		html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
		html += '    <td class="right"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';
		html += '</tbody>';
		
		$('#special tfoot').before(html);
		
		$('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
		
		special_row++;
	}
	//--></script> 
	<!--Additional offer-->
	<script type="text/javascript"><!--
	var additional_offer_row = <?php echo $additional_offer_row; ?>;
	
	function addAdditionalOffer() {
		html  = '<tbody id="additional-offer-row' + additional_offer_row + '">';
		html += '  <tr>'; 
		html += '    <td class="left"><select name="product_additional_offer[' + additional_offer_row + '][customer_group_id]">';
		<?php foreach ($customer_groups as $customer_group) { ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
		<?php } ?>
		html += '    </select></td>';		
		html += '    <td class="right"><input type="text" name="product_additional_offer[' + additional_offer_row + '][priority]" value="" size="2" /></td>';
		html += '    <td class="right"><input type="text" name="product_additional_offer[' + additional_offer_row + '][quantity]" value="1" size="2" /></td>';
		html += '    <td class="right"><input type="text" size="15" name="aop' + additional_offer_row + '" /><input type="hidden" name="product_additional_offer[' + additional_offer_row + '][ao_product_id]" value="" size="10" /></td>';
		html += '    <td class="right"><input type="text" name="product_additional_offer[' + additional_offer_row + '][price]" value="" /></td>';
		html += '    <td class="right"><input type="text" name="product_additional_offer[' + additional_offer_row + '][percent]" value="" /></td>';
		html += '    <td class="left"><input type="text" name="product_additional_offer[' + additional_offer_row + '][date_start]" value="" class="date" /></td>';
		html += '    <td class="left"><input type="text" name="product_additional_offer[' + additional_offer_row + '][date_end]" value="" class="date" /></td>';
		html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + additional_offer_row + '" /><input type="hidden" name="product_additional_offer[' + additional_offer_row + '][image]" value="" id="image' + additional_offer_row + '" /><br /><a onclick="image_upload(\'image' + additional_offer_row + '\', \'thumb' + additional_offer_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + additional_offer_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + additional_offer_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		html += '    <td class="right"><a onclick="$(\'#additional-offer-row' + additional_offer_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';
		html += '<tr>';
		html += '  <td class="left" colspan="8">';
		html += '       <textarea id="ao-description' + additional_offer_row + '" name="product_additional_offer[' + additional_offer_row + '][description]"></textarea>';
		html += '   </td>';
		html += '</tr>';
		html += '</tbody>';
		
		$('#additional-offer tfoot').before(html);
		
		$('#additional-offer-row' + additional_offer_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
		
		CKEDITOR.replace('ao-description' + additional_offer_row, {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		});
		
		ao_autocomplete(additional_offer_row);
		
		additional_offer_row++;
	}
	
	function ao_autocomplete(additional_offer_row){
		$('input[name=\'aop' + additional_offer_row + '\']').autocomplete({
			delay: 0,
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
				$(this).val(ui.item.label);
				$('input[name=\'product_additional_offer[' + additional_offer_row + '][ao_product_id]\']').val(ui.item.value);
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
	}
	
	$('.ao_product_name').each(function(index, element) {
		ao_autocomplete($(this).attr('data-row'));
	});
	
	
	//--></script> 
	<!--Additional offer-->
	<script type="text/javascript"><!--
	function image_upload(field, thumb) {
		$('#dialog').remove();
		
		$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
		
		$('#dialog').dialog({
			title: '<?php echo $text_image_manager; ?>',
			close: function (event, ui) {
				if ($('#' + field).attr('value')) {
					$.ajax({
						url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
						dataType: 'text',
						success: function(text) {
							//	alert(text);
							$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
							<?php if ($this->config->get('pim_status')) {?>
								var row =field.replace('image','');  $('#radio_'+row).replaceWith('<input type="radio" name="def_img" value='+$('#' + field).attr('value')+'>');
							<?php } ?>
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
	var video_row = <?php echo $video_row; ?>;
	
	function addvideo() {
		html  = '<tbody id="video-row' + video_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><div class="video"><img src="<?php echo $no_image; ?>" alt="" id="videothumb' + video_row + '" /><input type="hidden" name="product_video[' + video_row + '][image]" value="" id="videoimage' + video_row + '" /><br /><a onclick="image_upload(\'videoimage' + video_row + '\', \'videothumb' + video_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#videothumb' + video_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#video' + video_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';

		html +='<td class="right">';
		html +='<textarea name="product_video[' + video_row + '][video]" rows="4" cols="50"></textarea>';
		html +='</td>';
		html +='	<td class="right">';
		<?php foreach ($languages as $language) { ?>
		html += '<input style="width:90%;" type="text" name="product_video[' + video_row + '][product_video_description][<?php echo $language['language_id'] ?>][title];" value="" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br/>';
		<?php } ?>
		html +='	</td>';
		html += '    <td class="right"><input type="text" name="product_video[' + video_row + '][sort_order]" value="" size="2" /></td>';
		html += '    <td class="right"><a onclick="$(\'#video-row' + video_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';
		html += '</tbody>';
		
		$('#videos tfoot').before(html);
		
		video_row++;
	}
	//--></script> 


	<script type="text/javascript"><!--
	var image_row = <?php echo $image_row; ?>;
	
	function addImage() {
		html  = '<tbody id="image-row' + image_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		html += '    <td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
		<?php if ($this->config->get('pim_status')) {?>
			html += '<td><input type="radio" name="def_img" id="radio_' + image_row + '" value="" disabled="disabled"></td>';
		<?php } ?>
		html += '    <td class="right"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';
		html += '</tbody>';
		
		$('#images tfoot').before(html);
		
		image_row++;
	}
	//--></script> 
	<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
	<script type="text/javascript"><!--
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').timepicker({timeFormat: 'h:m'});
	//--></script> 
	<script type="text/javascript"><!--
	$('#tabs a').tabs(); 
	$('#languages a').tabs();
	$('#markdown-languages a').tabs(); 
	$('#vtab-option a').tabs();
	$('#vtab-product-option a').tabs();
	
	var profileCount = <?php echo $profileCount ?>;
	
	function addProfile() {
		profileCount++;
		
		var html = '';
		html += '<tr id="profile-row' + profileCount + '">';
		html += '  <td class="left">';
		html += '    <select name="product_profiles[' + profileCount + '][profile_id]">';
		<?php foreach ($profiles as $profile): ?>
			html += '      <option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>';
		<?php endforeach; ?>
		html += '    </select>';
		html += '  </td>';
		html += '  <td class="left">';
		html += '    <select name="product_profiles[' + profileCount + '][customer_group_id]">';
		<?php foreach ($customer_groups as $customer_group): ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id'] ?>"><?php echo $customer_group['name'] ?></option>';
		<?php endforeach; ?>
		html += '    <select>';
		html += '  </td>';
		html += '  <td class="right">';
		html += '    <a class="button" onclick="$(\'#profile-row' + profileCount + '\').remove()"><?php echo $button_remove ?></a>';
		html += '  </td>';
		html += '</tr>';
		
		$('#tab-profile table tbody').append(html);
	}
	
	
	//--></script>
	<script type="text/javascript"><!--
	var product_product_option_row = <?php echo $product_product_option_value_row; ?>;
	function addAutocomplete(product_option_row, category_id) {
		$('input[name=\'product_option' + product_option_row + '\']').autocomplete({
			delay: 0,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term) + '&filter_category_id=' + category_id,
					dataType: 'json',
					success: function(json) {		
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.product_id,
								price: item.price,
								special: item.special,
								image: item.image
							}
						}));
					},
					error: function(json){
						console.log(json);
					}
				});		
			},
			select: function(event, ui) {
				html = '<tbody id="product-product-option-row' + product_option_row + '' + product_product_option_row + '">';
				html += '<tr>';
				html += '<td class="left"><img src="' + ui.item.image + '" alt="" /></td>';
				html += '<td class="left">' + ui.item.label + '</td>';
				if (ui.item.special) {
					html += '<td class="right"><span style="text-decoration: line-through;">' + ui.item.price + '</span><br/><span style="color: #b00;">' + ui.item.special + '</span></td>';
				} else {
					html += '<td class="right">' + ui.item.price + '</td>';
				}
				html += '<td class="right"><input type="text" name="product_product_option[' + product_option_row + '][product_option][' + product_product_option_row + '][sort_order]" value="0" size="2" /></td>';
				html += '<td class="right"><a onclick="$(\'#product-product-option-row' + product_option_row + '' + product_product_option_row + '\').remove();" class="button"><?php echo $button_remove; ?></a><input type="hidden" name="product_product_option[' + product_option_row + '][product_option][' + product_product_option_row + '][product_id]" value="' + ui.item.value + '" /></td>';
				html += '</tr>';
				html += '</tbody>';
				$('#product-option-value' + product_option_row + ' tfoot').before(html);
				product_product_option_row++;
				$(this).val('');			
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	}
	<?php $pp_option_row = 0; ?>
	<?php foreach ($product_product_options as $product_option) { ?>
		addAutocomplete(<?php echo $pp_option_row; ?>, <?php echo $product_option['category_id']; ?>);
		<?php $pp_option_row++; ?>
	<?php } ?>
--></script>

<script language="javascript">
	$(document).ready(function(){
		var hash = window.location.hash;
		$('a[href="'+hash+'"]').click();
	});
</script>

<script type="text/javascript"><!--	
var product_option_row = <?php echo $product_product_option_row; ?>;

$('select[name=\'product_category_option\']').change(function(){

	var category_id = $(this).val();

	if (category_id) {

		html  = '<div id="tab-product-option-' + product_option_row + '" class="vtabs-content">';
		html += '	<input type="hidden" name="product_product_option[' + product_option_row + '][category_id]" value="' + category_id + '" />';
		html += '	<table class="form">';
		html += '	  <tr>';
		html += '		<td><?php echo $entry_type; ?></td>';
		html += '       <td><select name="product_product_option[' + product_option_row + '][type]">';
		html += '	      <option value="radio"><?php echo $text_type_radio; ?></option>';
		html += '	      <option value="select"><?php echo $text_type_select; ?></option>';
		html += '	      <option value="checkbox"><?php echo $text_type_checkbox; ?></option>';
		html += '	    </select></td>';
		html += '     </tr>';
		html += '	  <tr>';
		html += '		<td><?php echo $entry_required; ?></td>';
		html += '       <td><select name="product_product_option[' + product_option_row + '][required]">';
		html += '	      <option value="0"><?php echo $text_no; ?></option>';
		html += '	      <option value="1"><?php echo $text_yes; ?></option>';
		html += '	    </select></td>';
		html += '     </tr>';
		html += '	  <tr>';
		html += '		<td><?php echo $entry_sort_order; ?></td>';
		html += '       <td><input type="text" name="product_product_option[' + product_option_row + '][sort_order]" value="0" size="2" /></td>';
		html += '     </tr>';
		html += '	  <tr>';
		html += '		<td><?php echo $entry_name; ?></td>';
		html += '       <td><input type="text" name="product_option' + product_option_row + '" value="" /></td>';
		html += '     </tr>';
		html += '  </table>';

		html += '  <table id="product-option-value' + product_option_row + '" class="list">';
		html += '  	 <thead>'; 
		html += '      <tr>';
		html += '        <td class="left"><?php echo $entry_image; ?></td>';
		html += '        <td class="left"><?php echo $entry_name; ?></td>';
		html += '        <td class="right"><?php echo $entry_price; ?></td>';
		html += '        <td class="right" data="mi-compat"><?php echo $entry_sort_order; ?></td>';
		html += '        <td></td>';
		html += '      </tr>';
		html += '  	 </thead>';
		html += '  	 <tfoot></tfoot>';
		html += '  </table>';			
		html += '</div>';	

		$('#tab-product-option').append(html);

		$('#product-option-add').before('<a href="#tab-product-option-' + product_option_row + '" id="product-option-' + product_option_row + '">' + $('select[name=\'product_category_option\'] option:selected').text() + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtab-product-option a:first\').trigger(\'click\'); $(\'#product-option-' + product_option_row + '\').remove(); $(\'#tab-product-option-' + product_option_row + '\').remove(); return false;" /></a>');

		$('#vtab-product-option a').tabs();

		$('#product-option-' + product_option_row).trigger('click');

		$('select[name=\'product_category_option\'] option:first').attr('selected', 'selected');

		addAutocomplete(product_option_row, category_id);

		product_option_row++;

	}

	return false;
})

//--></script>
<script type="text/javascript"><!--
function addBatchOption() {
	jQuery('#batchoption_product :selected').each(function() {
		jQuery(this).remove();

		jQuery('#batchoption option[value=\'' + jQuery(this).attr('value') + '\']').remove();

		jQuery('#batchoption').append('<option value="' + jQuery(this).attr('value') + '">' + jQuery(this).text() + '</option>');

		jQuery('#product_batchoption input[value=\'' + jQuery(this).attr('value') + '\']').remove();

		jQuery('#product_batchoption').append('<input type="hidden" name="product_batchoption[]" value="' + jQuery(this).attr('value') + '" />');
	});
}

function removeBatchOption() {
	jQuery('#batchoption :selected').each(function() {
		jQuery(this).remove();

		jQuery('#batchoption_product').append('<option value="' + jQuery(this).attr('value') + '">' + jQuery(this).text() + '</option>');

		jQuery('#product_batchoption input[value=\'' + jQuery(this).attr('value') + '\']').remove();
	});
}

function getProducts() {
	jQuery('#product option').remove();

	<?php if (isset($this->request->get['product_id'])) {?>
		var product_id = '<?php echo $this->request->get['product_id'] ?>';
	<?php } else { ?>
		var product_id = 0;
	<?php } ?>

	<?php if (defined('_JEXEC')) { ?>
		var ajaxurl = 'index.php?option=com_aceshop&format=raw&tmpl=component&route=catalog/product/ob_category&token=<?php echo $token; ?>&category_id=' + jQuery('#category').attr('value');
	<?php } else { ?>
		var ajaxurl = 'index.php?route=catalog/product/ob_category&token=<?php echo $token; ?>&category_id=' + jQuery('#category').attr('value');
	<?php } ?>

	jQuery.ajax({
		url: ajaxurl,
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
				if (data[i]['product_id'] == product_id) { continue; }
				jQuery('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
			}
		}
	});
}

function getProductsBatchOption() {
	jQuery('#batchoption_product option').remove();

	<?php if (isset($this->request->get['product_id'])) {?>
		var product_id = '<?php echo $this->request->get['product_id'] ?>';
	<?php } else { ?>
		var product_id = 0;
	<?php } ?>

	<?php if (defined('_JEXEC')) { ?>
		var ajaxurl = 'index.php?option=com_aceshop&format=raw&tmpl=component&route=catalog/product/ob_category&token=<?php echo $token; ?>&category_id=' + jQuery('#category_batchoption').attr('value');
	<?php } else { ?>
		var ajaxurl = 'index.php?route=catalog/product/ob_category&token=<?php echo $token; ?>&category_id=' + jQuery('#category_batchoption').attr('value');
	<?php } ?>

	jQuery.ajax({
		url: ajaxurl,
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
				if (data[i]['product_id'] == product_id) { continue; }
				jQuery('#batchoption_product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
			}
		}
	});
}


getProductsBatchOption();

//--></script>

<script type="text/javascript"><!--
ocfilter.php = {
	text_select: '<?php echo $text_select; ?>',
	ocfilter_select_category: '<?php echo $ocfilter_select_category; ?>',
	entry_values: '<?php echo $entry_values; ?>',
	tab_ocfilter: '<?php echo $tab_ocfilter; ?>'
};

ocfilter.php.languages = [];

<?php foreach ($languages as $language) { ?>
	ocfilter.php.languages.push({
		'language_id': <?php echo $language['language_id']; ?>,
		'name': '<?php echo $language['name']; ?>',
		'image': '<?php echo $language['image']; ?>'
	});
<?php } ?>

//--></script>

<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;
function addPowerImage(img) {
	<?php if ($this->config->get('pim_miu')){?>
		addMultiImage(img);
	<?php } else { ?>
		
		if (image_row>0) {
			var k = (image_row-1);
			
			if ($('#image'+k).val() == "") {
				$('#image-row'+k).remove();
			}
		}
		html ='<tbody id="image-row'+ image_row +'">';
		html+='<tr>';
		html+='<td class="left"><div class="image"><img src="<?php echo HTTPS_CATALOG."image/"; ?>/'+img+'" alt="" id="thumb' + image_row + '" height=100 />';
		html+='<input type="hidden" name="product_image[' + image_row + '][image]" value="' + img + '" id="image' + image_row + '" />';
		html+='<br />';
		html+='<a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
		html+='<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		html+='<td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="<?php if (isset($product_image['image'])) { echo $product_image["sort_order"]; } ?>" size="2" /></td>';
		<?php if (!$this->config->get('pim_miu')) {?>
			html += '<td><input type="radio" name="def_img" value="'+img+'"></td>';
		<?php } ?>
		html+='<td class="left"><a onclick=\'$("#image-row' + image_row + '").remove();\' class="button"><?php echo $button_remove; ?></a></td>';
		html+='</tr>';
		html+='</tbody>';	
		$('#images tfoot').before(html);
		image_row++;
	<?php } ?>
}
//--></script> 

<script type="text/javascript">
	var uploader = new qq.FileUploader({
		element: document.getElementById('file-uploader'),
		action: 'index.php?route=tool/upload&token=<?php echo $token;?>',
		allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
		onComplete: function(id, fileName, responseJSON){addMultiImage(responseJSON.fileName); },
	});
	var image_row = <?php echo $image_row; ?>;
	function addMultiImage(img) {

		html ='<tbody id="image-row'+ image_row +'">';
		html+='<tr>';
		html+='<td class="left"><div class="image"><img src="<?php echo HTTP_CATALOG."image/"; ?>'+img+'" alt="" id="thumb' + image_row + '" height=100 />';
		html+='<input type="hidden" name="product_image[' + image_row + '][image]" value="' + img + '" id="image' + image_row + '" />';
		html+='<br />';
		html+='<a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
		html+='<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		html+='<td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="<?php if (isset($product_image['image'])) { echo $product_image["sort_order"]; } ?>" size="2" /></td>';
		html += '<td><input type="radio" name="def_img" value="'+img+'"></td>';
		html+='<td class="left"><a onclick=\'$("#image-row' + image_row + '").remove();\' class="button"><?php echo $button_remove; ?></a></td>';
		html+='</tr>';
		html+='</tbody>';	
		$('#images tfoot').before(html);
		image_row++;
	}
</script> 

<?php echo $footer; ?>																																		