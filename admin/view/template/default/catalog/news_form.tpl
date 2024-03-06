<?php echo $header; ?>
<style type="text/css">
	#sdesc1, #sdesc2, #sdesc3, #sdesc4, #sdesc5, #sdesc6, #sdesc7, #sdesc8, #sdesc9, #sdesc10 { display: none; }
</style>
<? require_once(dirname(__FILE__) . '/../structured/translate.js.tpl'); ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php echo $newspanel; ?>
	<div class="box">
		<div class="heading order_head">
			<h1><?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button sterge"><span><?php echo $button_cancel; ?></span></a></div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-seo"><?php echo $tab_seo; ?></a>
				<a href="#tab-custom"><?php echo $tab_custom; ?></a>
				<a href="#tab-recipe">Составляющие рецепта</a>
				<a href="#tab-settings"><?php echo $tab_settings; ?></a>
				<a href="#tab-related"><?php echo $tab_related; ?></a>
				<a href="#tab-video"><?php echo $tab_video; ?></a>
				<a href="#tab-gallery"><?php echo $tab_gallery; ?></a>
				<a href="#tab-design"><?php echo $entry_layout; ?></a>
				<div class="clr"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div class="page">
					<div id="tab-general">
						<div id="languages" class="htabs">
							<?php foreach ($languages as $language) { ?>
								<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
							<?php } ?>
						</div>
						<?php foreach ($languages as $language) { ?>
							<div id="language<?php echo $language['language_id']; ?>" class="gltab">
								<table class="form">
									<tr>
										<td class="left" width="25%"><span class="required">*</span> <?php echo $entry_title; ?></td>
										<td>
											
											<div class="translate_wrap">
												<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'input','news_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
												<?php if (in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','input','news_description[2]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
													<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','input','news_description[26]');">
														Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>&nbsp;
													
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'en','<?php echo $language['code']; ?>','input','news_description[26]');">
														Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>gb.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>&nbsp;
													
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'fr','<?php echo $language['code']; ?>','input','news_description[26]');">
														Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>fr.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>
												<?php } ?>
											</div>
											
											
											<input size='200' type="text" name="news_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset( $news_description[$language['language_id']]['title']) ? $news_description[$language['language_id']]['title'] : ''; ?>" />
											<?php if (isset($error_title[$language['language_id']])) { ?>
												<span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
											<?php } ?>
										</td>
									</tr>
									
									<tr>
										<td class="left"><span class="required">*</span> <?php echo $entry_description; ?></td>
										<td>
											
											<div class="translate_wrap">
												<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','news_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
												<?php if (in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','news_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
													<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
													<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','news_description[26]');">
														Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
													</a>
												<?php } ?>
											</div>
											
											<textarea name="news_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset( $news_description[$language['language_id']]['description']) ? $news_description[$language['language_id']]['description'] : ''; ?></textarea>
											<?php if (isset($error_description[$language['language_id']])) { ?>
												<br /><span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
											<?php } ?>
										</td>
									</tr>
								</table>
								<div style="background: #eee; padding-top: 5px; padding-bottom: 5px;"><h3 class="addsuperh3" id="hidesdesc<?php echo $language['language_id']; ?>"><?php echo $entry_addsdesc; ?></h3></div>
								<div id="sdesc<?php echo $language['language_id']; ?>">
									<table class="form">
										<tr>
											<td class="left"><span class="required">*</span> <?php echo $entry_description2; ?></td>
											<td>
												
												<div class="translate_wrap">
													<a class="btn-copy<?php echo $language['language_id']; ?> btn-copy" onclick="getCopy($(this),'textarea','news_description[2]');"><i class="fa fa-copy"></i> Копировать с ru</a>
													<?php if (in_array($language['code'], $this->config->get('config_translate_from_ru'))) { ?>
														<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'ru','<?php echo $language['code']; ?>','textarea','news_description[5]');">Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>ru.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></a>
														<?php } elseif ($language['code'] == $this->config->get('config_admin_language')) { ?>
														<a class="btn-translate<?php echo $language['language_id']; ?> btn-translate" onclick="getTranslate($(this), 'de','<?php echo $language['code']; ?>','textarea','news_description[26]');">
															Перевести <img src="<?php echo DIR_FLAGS_NAME; ?>de.png" /> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
														</a>
													<?php } ?>
												</div>
												
												<textarea name="news_description[<?php echo $language['language_id']; ?>][description2]" id="descriptions<?php echo $language['language_id']; ?>"><?php echo isset( $news_description[$language['language_id']]['description2']) ? $news_description[$language['language_id']]['description2'] : ''; ?></textarea>
											</td>
										</tr>
									</table>
								</div>
							</div>
						<?php } ?>
						<table class="form">
							<tr>
								<td class="left"><?php echo $entry_image; ?></td>
								<td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
								<a class="button" onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;<a class="button sterge" onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
							</tr>
							<tr>	  
								<td class="left" width="25%"><?php echo $entry_status; ?></td>
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
							<tr>	  
								<td class="left" width="25%"><?php echo $entry_nauthor; ?></td>
								<td><select name="nauthor_id">
									<?php foreach ($authors as $author) { ?>
										<?php if ($nauthor_id == $author['nauthor_id']) { ?>
											<option value="<?php echo $author['nauthor_id']; ?>" selected="selected"><?php echo $author['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $author['nauthor_id']; ?>"><?php echo $author['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select></td>
							</tr>
						</table>
					</div>
					<div id="tab-seo">
						<table class="form">
							<tr>
								<td class="left"><?php echo $entry_keyword; ?></td>
								<td><?php foreach ($languages as $language) { ?>
									<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php  if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
									<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
								<?php } ?></td>
							</tr>
						</table>
						<div id="languagesseo" class="htabs">
							<?php foreach ($languages as $language) { ?>
								<a href="#languageseo<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
							<?php } ?>
						</div>
						<?php foreach ($languages as $language) { ?>
							<div id="languageseo<?php echo $language['language_id']; ?>">
								<table class="form">
									<tr>
										<td class="left" width="25%"><?php echo $entry_ctitle; ?></td>
										<td><input type="text" name="news_description[<?php echo $language['language_id']; ?>][ctitle]" value="<?php echo isset( $news_description[$language['language_id']]['ctitle']) ? $news_description[$language['language_id']]['ctitle'] : ''; ?>" />
										</td>
									</tr>
									<tr>
										<td class="left"><?php echo $entry_meta_desc; ?></td>
										<td><textarea name="news_description[<?php echo $language['language_id']; ?>][meta_desc]" cols="60" rows="3"><?php echo isset( $news_description[$language['language_id']]['meta_desc']) ? $news_description[$language['language_id']]['meta_desc'] : ''; ?></textarea>
										</td>
									</tr>
									<tr>
										<td class="left"> <?php echo $entry_meta_key; ?></td>
										<td><textarea name="news_description[<?php echo $language['language_id']; ?>][meta_key]" cols="60" rows="3"><?php echo isset( $news_description[$language['language_id']]['meta_key']) ? $news_description[$language['language_id']]['meta_key'] : ''; ?></textarea>
										</td>
									</tr>
									<tr>
										<td class="left" width="25%"><?php echo $entry_ntags; ?></td>
										<td><input size="80" type="text" name="news_description[<?php echo $language['language_id']; ?>][ntags]" value="<?php echo isset( $news_description[$language['language_id']]['ntags']) ? $news_description[$language['language_id']]['ntags'] : ''; ?>" />
										</td>
									</tr>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="tab-custom">
						<table class="form">
							<tr>
								<td class="left"><?php echo $entry_datea; ?></td>
								<td><input class="datetime" type="text" name="date_added" value="<?php echo $date_added; ?>" /></td>
							</tr>
							<tr>
								<td class="left"><?php echo $entry_dateu; ?></td>
								<td><input class="datetime" type="text" name="date_updated" value="<?php echo $date_updated; ?>" /></td>
							</tr>
							<tr>
								<td class="left"><?php echo $entry_datep; ?></td>
								<td><input class="datetime" type="text" name="date_pub" value="<?php echo $date_pub; ?>" /></td>
							</tr>
							<tr>
								<td class="left"><?php echo $entry_image2; ?></td>
								<td><div class="image"><img src="<?php echo $thumb2; ?>" alt="" id="fthumb2" /><br />
									<input type="hidden" name="image2" value="<?php echo $image2; ?>" id="fimage2" />
								<a class="button" onclick="image_upload('fimage2', 'fthumb2');"><?php echo $text_browse; ?></a>&nbsp;<a class="button sterge" onclick="$('#fthumb2').attr('src', '<?php echo $no_image; ?>'); $('#fimage2').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
							</tr>
						</table>
						<div id="languagesc" class="htabs">
							<?php foreach ($languages as $language) { ?>
								<a href="#languagesc<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
							<?php } ?>
						</div>
						<?php foreach ($languages as $language) { ?>
							<div id="languagesc<?php echo $language['language_id']; ?>">
								<table class="form">
									<tr>
										<td class="left" width="25%"><?php echo $entry_cfield; ?> 1</td>
										<td><input size="80" type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield1]" value="<?php echo isset( $news_description[$language['language_id']]['cfield1']) ? $news_description[$language['language_id']]['cfield1'] : ''; ?>" />
										</td>
									</tr>
									<tr>
										<td class="left" width="25%"><?php echo $entry_cfield; ?> 2</td>
										<td><input size="80" type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield2]" value="<?php echo isset( $news_description[$language['language_id']]['cfield2']) ? $news_description[$language['language_id']]['cfield2'] : ''; ?>" />
										</td>
									</tr>
									<tr>
										<td class="left" width="25%"><?php echo $entry_cfield; ?> 3</td>
										<td><input size="80" type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield3]" value="<?php echo isset( $news_description[$language['language_id']]['cfield3']) ? $news_description[$language['language_id']]['cfield3'] : ''; ?>" />
										</td>
									</tr>
									<tr>
										<td class="left" width="25%"><?php echo $entry_cfield; ?> 4</td>
										<td><input size="80" type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield4]" value="<?php echo isset( $news_description[$language['language_id']]['cfield4']) ? $news_description[$language['language_id']]['cfield4'] : ''; ?>" />
										</td>
									</tr>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="tab-recipe">
						<div id="languarecipe" class="htabs">
							<?php foreach ($languages as $language) { ?>
								<a href="#languarecipe<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
							<?php } ?>
						</div>
						<?php foreach ($languages as $language) { ?>
							<div id="languarecipe<?php echo $language['language_id']; ?>">
								<table class="form">
									<thead>
										<td>Сколько : Единица изм. : Чего</td>
									</thead>
									
									
									<? 
										$recipe = unserialize($news_description[$language['language_id']]['recipe']);
									?>
									<? if (is_array($recipe)) { ?>		
										<? foreach ($recipe as $key => $val) { ?>
											
											<tr>					
												<td class="left">
													<input size="10" type="text" name="news_description[<?php echo $language['language_id']; ?>][recipe][<? echo $key; ?>][]" value="<? echo $val[0]; ?>" />
													<input size="10" type="text" name="news_description[<?php echo $language['language_id']; ?>][recipe][<? echo $key; ?>][]" value="<? echo $val[1]; ?>" />
													<input size="80" type="text" name="news_description[<?php echo $language['language_id']; ?>][recipe][<? echo $key; ?>][]" value="<? echo $val[2]; ?>" />
												</td>
											</tr>
										<? } ?>
									<? } ?>
									
									<? for ($i = count($recipe); $i<=20; $i++) { ?>
										<tr>
											<td class="left">
												<input size="10" type="text" name="news_description[<?php echo $language['language_id']; ?>][recipe][<? echo $i; ?>][]" value="" />
												<input size="10" type="text" name="news_description[<?php echo $language['language_id']; ?>][recipe][<? echo $i; ?>][]" value="" />
												<input size="80" type="text" name="news_description[<?php echo $language['language_id']; ?>][recipe][<? echo $i; ?>][]" value="" />
											</td>
										</tr>
									<? } ?>
									
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="tab-settings">
						<table class="form">
							<tr>
								<td class="left"><?php echo $entry_acom; ?></td>
								<td><select name="acom">
									<?php if ($acom) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select></td>
							</tr>
							<tr>
								<td class="left"><?php echo $entry_sort_order; ?></td>
								<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
							</tr>
							<tr>
								<td class="left"><?php echo $entry_category; ?></td>
								<td><div class="scrollbox">
									<?php $class = 'odd'; ?>
									<?php foreach ($ncategories as $category) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
											<?php if (in_array($category['ncategory_id'], $news_ncategory)) { ?>
												<input id="" class="" type="checkbox" name="news_ncategory[]" value="<?php echo $category['ncategory_id']; ?>" checked="checked" />
												<?php echo $category['name']; ?>
												<?php } else { ?>
												<input id="" class="" type="checkbox" name="news_ncategory[]" value="<?php echo $category['ncategory_id']; ?>" />
												<?php echo $category['name']; ?>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
								<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);$.uniform.update();"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);$.uniform.update();"><?php echo $text_unselect_all; ?></a></td>
							</tr>
							<tr>
								<td class="left"><?php echo $entry_store; ?></td>
								<td><div class="scrollbox">
									<?php $class = 'even'; ?>
									<?php foreach ($stores as $store) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
											<?php if (in_array($store['store_id'], $news_store)) { ?>
												<input type="checkbox" name="news_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
												<?php echo $store['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="news_store[]" value="<?php echo $store['store_id']; ?>" />
												<?php echo $store['name']; ?>
											<?php } ?>
										</div>
									<?php } ?>
								</div></td>
							</tr>
							
						</table>
					</div>
					<div id="tab-related">
						<table class="form">
							<tr><td class="left"><?php echo $entry_nrelated; ?></td>
								<td><input type="text" name="nrelated" value="" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><div class="scrollbox" id="news-nrelated">
									<?php $class = 'odd'; ?>
									<?php foreach ($news_nrelated as $news_nrelated) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div id="news-nrelated<?php echo $news_nrelated['news_id']; ?>" class="<?php echo $class; ?>"> <?php echo $news_nrelated['title']; ?><img src="view/image/delete.png" />
											<input type="hidden" name="news_nrelated[]" value="<?php echo $news_nrelated['news_id']; ?>" />
										</div>
									<?php } ?>
								</div>
								</td></tr>
								<tr><td class="left"><?php echo $entry_related; ?></td>
									<td><input type="text" name="related" value="" /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td><div class="scrollbox" id="news-related">
										<?php $class = 'odd'; ?>
										<?php foreach ($news_related as $news_related) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div id="news-related<?php echo $news_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $news_related['name']; ?><img src="view/image/delete.png" />
												<input type="hidden" name="news_related[]" value="<?php echo $news_related['product_id']; ?>" />
											</div>
										<?php } ?>
									</div>
									</td></tr>
						</table>
					</div>
					<div id="tab-video">
						<table id="video" class="list">
							<thead>
								<tr>
									<td class="left"><?php echo $entry_video_id; ?></td>
									<td class="left"><?php echo $entry_video_text; ?></td>
									<td class="left"><?php echo $entry_video_size; ?></td>
									<td class="right"><?php echo $entry_sort_order; ?></td>
									<td></td>
								</tr>
							</thead>
							<?php $video_row = 0; ?>
							<?php foreach ($news_video as $video) { ?>
								<tbody id="video-row<?php echo $video_row; ?>">
									<tr>
										<td class="left">
											<input type="text" name="news_video[<?php echo $video_row; ?>][video]" value="<?php echo $video['video']; ?>" size="20" />
										</td>
										<td class="left">
											<?php $video['text'] = unserialize($video['text']); ?>
											<?php foreach ($languages as $language) { ?>
												<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												<textarea name="news_video[<?php echo $video_row; ?>][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"><?php echo isset($video['text'][$language['language_id']]) ? $video['text'][$language['language_id']] : '' ; ?></textarea><br />
											<?php } ?>
										</td>
										<td class="left"><input type="text" name="news_video[<?php echo $video_row; ?>][width]" value="<?php echo $video['width']; ?>" size="3" />
											<input type="text" name="news_video[<?php echo $video_row; ?>][height]" value="<?php echo $video['height']; ?>" size="3" />
										</td>
										<td class="right"><input type="text" name="news_video[<?php echo $video_row; ?>][sort_order]" value="<?php echo $video['sort_order']; ?>" size="2" /></td>
										<td class="left"><a onclick="$('#video-row<?php echo $video_row; ?>').remove();" class="button sterge"><?php echo $button_remove; ?></a></td>
									</tr>
								</tbody>
								<?php $video_row++; ?>
							<?php } ?>
							<tfoot>
								<tr>
									<td colspan="4"></td>
									<td class="left"><a onclick="addVideo();" class="button"><?php echo $button_add_video; ?></a></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div id="tab-gallery">
						<table class="form">
							<tr>
								<td><?php echo $entry_gallery_thumb; ?></td>
								<td>
									<input type="text" name="gal_thumb_w" value="<?php echo $gal_thumb_w; ?>" size="2" />
									<input type="text" name="gal_thumb_h" value="<?php echo $gal_thumb_h; ?>" size="2" />
								</td>
							</tr>
							<tr>
								<td><?php echo $entry_gallery_popup; ?></td>
								<td>
									<input type="text" name="gal_popup_w" value="<?php echo $gal_popup_w; ?>" size="2" />
									<input type="text" name="gal_popup_h" value="<?php echo $gal_popup_h; ?>" size="2" />
								</td>
							</tr>
							<tr>
								<td><?php echo $entry_gallery_slidert; ?></td>
								<td>
									<select name="gal_slider_t">
										<?php if ($gal_slider_t == 1) { ?>
											<option value="1" selected="selected">Classic</option>
											<option value="2">Slideshow</option>
											<?php } else { ?>
											<option value="1">Classic</option>
											<option value="2" selected="selected">Slideshow</option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><?php echo $entry_gallery_slider; ?></td>
								<td>
									<input type="text" name="gal_slider_w" value="<?php echo $gal_slider_w; ?>" size="2" />
									<input type="text" name="gal_slider_h" value="<?php echo $gal_slider_h; ?>" size="2" />
								</td>
							</tr>
						</table>
						<table id="gallery" class="list">
							<thead>
								<tr>
									<td class="left"><?php echo $entry_image; ?></td>
									<td class="left"><?php echo $entry_gallery_text; ?></td>
									<td class="right"><?php echo $entry_sort_order; ?></td>
									<td></td>
								</tr>
							</thead>
							<?php $image_row = 0; ?>
							<?php foreach ($news_gallery as $news_image) { ?>
								<tbody id="image-row<?php echo $image_row; ?>">
									<tr>
										<td class="left"><div class="image">
											<img src="<?php echo $news_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
											<input type="hidden" name="news_gallery[<?php echo $image_row; ?>][image]" value="<?php echo $news_image['image']; ?>" id="image<?php echo $image_row; ?>" />
											<br />
										<a class="button" onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a><a class="button sterge" onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div>
										</td>
										<td class="left">
											<?php $news_image['text'] = unserialize($news_image['text']); ?>
											<?php foreach ($languages as $language) { ?>
												<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												<textarea name="news_gallery[<?php echo $image_row; ?>][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"><?php echo isset($news_image['text'][$language['language_id']]) ? $news_image['text'][$language['language_id']] : '' ; ?></textarea><br />
											<?php } ?>
										</td>
										<td class="right"><input type="text" name="news_gallery[<?php echo $image_row; ?>][sort_order]" value="<?php echo $news_image['sort_order']; ?>" size="2" /></td>
										<td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button sterge"><?php echo $button_remove; ?></a></td>
									</tr>
								</tbody>
								<?php $image_row++; ?>
							<?php } ?>
							<tfoot>
								<tr>
									<td colspan="3"></td>
									<td class="left"><a onclick="addGallery();" class="button"><?php echo $button_add_image; ?></a></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div id="tab-design">
						<table class="form">
							<tr>
								<td class="left"><?php echo $entry_store; ?></td>
								<td class="left"><?php echo $entry_layout; ?></td>
							</tr>
							<tr>
								<td class="left"><?php echo $text_default; ?></td>
								<td class="left"><select name="news_layout[0][layout_id]">
									<option value=""></option>
									<?php foreach ($layouts as $layout) { ?>
										<?php if (isset($news_layout[0]) && $news_layout[0] == $layout['layout_id']) { ?>
											<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select></td>
							</tr>
							<?php foreach ($stores as $store) { ?>
								<tr>
									<td class="left"><?php echo $store['name']; ?></td>
									<td class="left"><select name="news_layout[<?php echo $store['store_id']; ?>][layout_id]">
										<option value=""></option>
										<?php foreach ($layouts as $layout) { ?>
											<?php if (isset($news_layout[$store['store_id']]) && $news_layout[$store['store_id']] == $layout['layout_id']) { ?>
												<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select></td>
								</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</form>
			<script type="text/javascript"><!--
				$('input[name=\'nrelated\']').autocomplete({
					delay: 0,
					source: function(request, response) {
						if (encodeURIComponent(request.term).length > 2) {
							$.ajax({
								url: 'index.php?route=catalog/news/autocomplete&token=<?php echo $token; ?>&filter_aname=' +  encodeURIComponent(request.term),
								dataType: 'json',
								success: function(json) {		
									response($.map(json, function(item) {
										return {
											label: item.title,
											value: item.news_id
										}
									}));
								}
							});
						}
						
					}, 
					select: function(event, ui) {
						$('#news-nrelated' + ui.item.value).remove();
						
						$('#news-nrelated').append('<div id="news-nrelated' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="news_nrelated[]" value="' + ui.item.value + '" /></div>');
						
						$('#news-nrelated div:odd').attr('class', 'odd');
						$('#news-nrelated div:even').attr('class', 'even');
						
						return false;
					}
				});
				
				$('#news-nrelated div img').live('click', function() {
					$(this).parent().remove();
					
					$('#news-nrelated div:odd').attr('class', 'odd');
					$('#news-nrelated div:even').attr('class', 'even');	
				});
			//--></script> 
			<script type="text/javascript"><!--
				$('input[name=\'related\']').autocomplete({
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
						$('#news-related' + ui.item.value).remove();
						
						$('#news-related').append('<div id="news-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="news_related[]" value="' + ui.item.value + '" /></div>');
						
						$('#news-related div:odd').attr('class', 'odd');
						$('#news-related div:even').attr('class', 'even');
						
						return false;
					}
				});
				
				$('#news-related div img').live('click', function() {
					$(this).parent().remove();
					
					$('#news-related div:odd').attr('class', 'odd');
					$('#news-related div:even').attr('class', 'even');	
				});
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
						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					});
					CKEDITOR.replace('descriptions<?php echo $language['language_id']; ?>', {
						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					});
				<?php } ?>
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
									url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
									dataType: 'text',
									success: function(text) {
										$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
									}
								});
							}
						},	
						bgiframe: false,
						width: 800,
						height: 400,
						resizable: false,
						modal: false
					});
				};
			//--></script> 
			<script type="text/javascript"><!--
				var image_row = <?php echo $image_row; ?>;
				
				function addGallery() {
					html  = '<tbody id="image-row' + image_row + '">';
					html += '  <tr>';
					html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="news_gallery[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a class="button" onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a><a class="button sterge" onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
					html += '    <td class="left">';
					<?php foreach ($languages as $language) { ?>
						html += '	<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><textarea name="news_gallery[' + image_row  + '][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"></textarea><br />';
					<?php } ?>
					html += '</td>';
					html += '    <td class="right"><input type="text" name="news_gallery[' + image_row + '][sort_order]" value="" size="2" /></td>';
					html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button sterge"><?php echo $button_remove; ?></a></td>';
					html += '  </tr>';
					html += '</tbody>';
					
					$('#gallery tfoot').before(html);
					
					image_row++;
				}
				var video_row = <?php echo $video_row; ?>;
				
				function addVideo() {
					html  = '<tbody id="video-row' + video_row + '">';
					html += '  <tr>';
					html += '    <td class="left"><input type="text" name="news_video[' + video_row + '][video]" value="" size="20" /></td>';
					html += '    <td class="left">';
					<?php foreach ($languages as $language) { ?>
						html += '	<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><textarea name="news_video[' + video_row  + '][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"></textarea><br />';
					<?php } ?>
					html += '</td>';
					html += '    <td class="left"><input type="text" name="news_video[' + video_row + '][width]" value="" size="3" /><input type="text" name="news_video[' + video_row + '][height]" value="" size="3" /></td>';
					html += '    <td class="right"><input type="text" name="news_video[' + video_row + '][sort_order]" value="" size="2" /></td>';
					html += '    <td class="left"><a onclick="$(\'#video-row' + video_row  + '\').remove();" class="button sterge"><?php echo $button_remove; ?></a></td>';
					html += '  </tr>';
					html += '</tbody>';
					
					$('#video tfoot').before(html);
					
					video_row++;
				}
			//--></script> 
			<script type="text/javascript"><!--
				$('#tabs a').tabs(); 
				$('#languages a').tabs();
				$('#languagesseo a').tabs();
				$('#languagesc a').tabs();
				$('#languarecipe a').tabs();
			//--></script> 
			
			<script type="text/javascript"><!--
				<?php foreach ($languages as $language) { ?>
					var superh3stat<?php echo $language['language_id']; ?> = $('#hidesdesc<?php echo $language['language_id']; ?>').hasClass('active');
					$('#hidesdesc<?php echo $language['language_id']; ?>').bind('click', function() {
						if (!superh3stat<?php echo $language['language_id']; ?>) {
							$("#sdesc<?php echo $language['language_id']; ?>").slideDown('slow');
							$(this).addClass('active');
							superh3stat<?php echo $language['language_id']; ?> = true;
							} else {
							$("#sdesc<?php echo $language['language_id']; ?>").slideUp('slow');
							$(this).removeClass('active');
							superh3stat<?php echo $language['language_id']; ?> = false;
						}
					});
				<?php } ?>
			//--></script> 
			<script type="text/javascript" src="view/javascript/blog-res/jquery-ui-timepicker-addon.js"></script>
			<script type="text/javascript"><!--
				$(document).ready(function() {
					if ($.browser.msie && $.browser.version == 6) {
						$('.datetime').bgIframe();
					}
					$('.datetime').datetimepicker({
						dateFormat: 'yy-mm-dd',
						timeFormat: 'h:m'
					});
				});
			//--></script> 
		</div>
		