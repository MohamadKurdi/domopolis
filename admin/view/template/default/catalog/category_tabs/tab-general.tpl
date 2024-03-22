<div id="tab-general">
	<div id="languages" class="htabs">
		<?php foreach ($languages as $language) { ?>
			<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /><?php echo $language['name']; ?></a>
		<?php } ?>
		<div class="clr"></div>
	</div>

	<?php foreach ($languages as $language) { ?>
		<div id="language<?php echo $language['language_id']; ?>">
			<table class="form">
				<tr>
					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">Название</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" />
						<?php if (isset($error_name[$language['language_id']])) { ?>
							<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
						<?php } ?>
					</td>

					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">Название в меню</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
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
					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">Тэглайн</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<textarea name="category_description[<?php echo $language['language_id']; ?>][tagline]" style="width:90%" rows="3"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['tagline'] : ''; ?></textarea>
					</td>
					<td style="width:50%">
					</td>
				</tr>


				<tr>
					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">SEO: H1 Tag</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<input type="text" name="category_description[<?php echo $language['language_id']; ?>][seo_h1]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['seo_h1'] : ''; ?>" />
					</td>

					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">SEO: Meta Title</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<input type="text" name="category_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['seo_title'] : ''; ?>" />
					</td>
				</tr>

				<tr>
					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">SEO: Meta Description</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" style="width:90%" rows="3"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
					</td>

					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">SEO: Meta Keywords</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" style="width:90%" rows="3"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">Google Tree</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<input type="text" name="category_description[<?php echo $language['language_id']; ?>][google_tree]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['google_tree'] : ''; ?>" />
						<span class="help">Заполняется автоматически, но можно редактировать</span>								
					</td>

					<td style="width:50%">
						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">Множественное</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<input type="text" name="category_description[<?php echo $language['language_id']; ?>][all_prefix]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['all_prefix'] : ''; ?>" />
						<span class="help">Смотреть все тарелки</span>											
					</td>
				</tr>
			</table>

			<div style="padding-top:10px; margin-bottom:20px; border-bottom:1px dashed #5D5D5D;"></div>
			<style>
				.openai-div input[type="text"]{
					background:#5D5D5D; 
					color:white; 
					font-size:12px;
					font-weight:700;
					border:2px solid #5D5D5D;
					padding:5px 10px;
				}
				.openai-div a.button{
					padding:10px 10px;									
				}

				.openai-div .openai-request{
					border-bottom:1px dashed grey;
					cursor: pointer;
					line-height: 26px;
					display:inline-block;
				}
			</style>
			<table class="form">								
				<tr>
					<td style="width:70%">

						<?php if ($this->config->get('config_openai_enable_category_descriptions')) { ?>
							<div class="openai-div" style="margin-bottom:10px; padding:15px; border:1px dashed grey;">
								<table style="width:100%">
									<tr>
										<td>
											<input style="width:90%;" type="text" name="openai-description-request-<?php echo $language['language_id']; ?>" data-target="description<?php echo $language['language_id']; ?>" value="" />														
										</td>
										<td style="width:150px; vertical-align:top;">
											<a class="button" onclick="descriptionai($(this), '<?php echo $language['language_id']; ?>');">🤖 OpenAI Magic</a><span></span>
										</td>
									</tr>
									<tr>
										<td colspan="2">																
											<?php if (!empty($openai_category_descripion_requests[$language['code']])) { ?>
												<?php foreach ($openai_category_descripion_requests[$language['code']] as $openai_category_descripion_request) { ?>
													<div class="openai-request" onclick="$('input[name=\'openai-description-request-<?php echo $language['language_id']; ?>\']').val($(this).text())"><?php echo $openai_category_descripion_request; ?></div><br />
												<?php } ?>
											<?php } ?>
										</td>
									</tr>
								</table>
							</div>
						<?php } ?>

						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">Описание</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
					</td>

					<td style="width:30%; vertical-align:top" valign="top">

						<?php if ($this->config->get('config_openai_enable_category_alternatenames')) { ?>
							<div class="openai-div" style="margin-bottom:10px; padding:15px; border:1px dashed grey;">
								<table style="width:100%">
									<tr>
										<td>
											<input style="width:90%;" type="text" name="openai-alternatenames-request-<?php echo $language['language_id']; ?>" value="" />
										</td>
										<td style="width:150px; vertical-align:top;">
											<a class="button" onclick="alternatenameai($(this), '<?php echo $language['language_id']; ?>');">🤖 OpenAI Magic</a><span></span>
										</td>														
									</tr>
									<tr>
										<td colspan="2">
											<?php if (!empty($openai_alternatenames_requests[$language['code']])) { ?>
												<?php foreach ($openai_alternatenames_requests[$language['code']] as $openai_alternatenames_request) { ?>
													<div class="openai-request" onclick="$('input[name=\'openai-alternatenames-request-<?php echo $language['language_id']; ?>\']').val($(this).text())"><?php echo $openai_alternatenames_request; ?></div><br />
												<?php } ?>
											<?php } ?>
										</td>
									</tr>
								</table>
							</div>
						<?php } ?>


						<div class="translate_wrap">
							<span class="status_color" style="display:inline-block; padding:5px 15px; background:#00ad07; color:#FFF">Варианты для поиска</span>

							<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','category_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
							<?php if ($this->config->get('config_translate_from_ru') && in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','category_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
							<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','category_description[26]');">
									Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								</a>
							<?php } ?>
						</div>
						<textarea rows="40" style="width:90%" name="category_description[<?php echo $language['language_id']; ?>][alternate_name]" id="alternate_name<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['alternate_name'] : ''; ?></textarea>
					</td>									
				</tr>
			</table>
		</div>
	<?php } ?>
</div>

<script type="text/javascript" >
		function descriptionai(elem, language_id){			
			let request  	= $("input[name='openai-description-request-" + language_id + "']").val();

			$.ajax({
				url: 'index.php?route=catalog/category/descriptionbyai&token=<?php echo $token; ?>',
				type: 'POST',
				dataType: 'text',
				data: {
					request: request,					
				},
				success: function(text) {
					if (typeof (CKEDITOR.instances['description' + language_id]) == 'object'){
						CKEDITOR.instances['description' + language_id].setData(text);
					} else {
						$('#description' + language_id).val(text);
					}
				},
				beforeSend: function(){
					elem.next().html('<i class="fa fa-spinner fa-spin"></i>');
				},
				complete: function(){
					elem.next().html('<i class="fa fa-check"></i>');
				}
			});
		}
	</script>

	<script type="text/javascript" >
		function alternatenameai(elem, language_id){			
			let request  	= $("input[name='openai-alternatenames-request-" + language_id + "']").val();

			$.ajax({
				url: 'index.php?route=catalog/category/alternatenamesbyai&token=<?php echo $token; ?>',
				type: 'POST',
				dataType: 'text',
				data: {
					request: request,					
				},
				success: function(text) {
					$('#alternate_name' + language_id).val(text + '\n' + $('#alternate_name' + language_id).val());
				},
				beforeSend: function(){
					elem.next().html('<i class="fa fa-spinner fa-spin"></i>');
				},
				complete: function(){
					elem.next().html('<i class="fa fa-check"></i>');
				}
			});
		}
	</script>
	<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
	<script type="text/javascript">
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
	</script>