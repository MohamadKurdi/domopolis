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
			<h1><img src="view/image/actions.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-data">Настройки, картинки</a>
				<a href="#tab-seo"><?php echo $tab_seo; ?></a>
				<a href="#tab-links">Категории, магазины</a>
				<a href="#tab-products">Товары</a>
				<a href="#tab-design"><?php echo $tab_design; ?></a>
				<div class="clr"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
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
								<tr style="border-bottom:1px dashed gray">
									<td>
										<span class="status_color" style="display:inline-block; padding:3px 5px; margin-bottom:5px; background:#ff7815; color:#FFF">Название акции</span><br />
										<input type="text" name="actions_description[<?php echo $language['language_id']; ?>][caption]" size="100" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['caption'] : ''; ?>" />
										<?php if (isset($error_caption[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_caption[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								
								<tr style="border-bottom:1px dashed gray">										
									<td>
										<table>
											<tr>
												<td width="20%">
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Лейбл</span>													
												</td>
												<td width="20%">
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Фон лейбла</span>													
												</td>
												<td width="20%">
													<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Цвет лейбла</span>													
												</td>
												<td width="20%">
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Текст подсказки</span>													
												</td>
											</tr>
											
											<tr>
												<td>
													<input type="text" name="actions_description[<?php echo $language['language_id']; ?>][label]" size="8" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['label'] : ''; ?>" />
													<span class="help">На товарах, участвующих в акции будет пометка</span>
												</td>
												<td>
													<input id="pick_label_background<?php echo $language['language_id']; ?>" type="text" name="actions_description[<?php echo $language['language_id']; ?>][label_background]" size="8" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['label_background'] : ''; ?>" />
													<span class="help">фон блока лейбла на товаре</span>
												</td>
												<td>
													<input id="pick_label_color<?php echo $language['language_id']; ?>" type="text" name="actions_description[<?php echo $language['language_id']; ?>][label_color]" size="8" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['label_color'] : ''; ?>" />
													<span class="help">цвет блока лейбла на товаре</span>
												</td>
												<td>
													<textarea name="actions_description[<?php echo $language['language_id']; ?>][label_text]" cols="100" rows="2"><?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['label_text'] : ''; ?></textarea>
													<span class="help">Текст всплывающей подсказки</span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								
								<script type="text/javascript">
									$(function()
									{
										$.fn.jPicker.defaults.images.clientPath='view/image/';
										var LiveCallbackElement = $('#Live'),
										LiveCallbackButton = $('#LiveButton');
										$('#pick_label_background<?php echo $language['language_id']; ?>').jPicker({window:{title:'Выбери цвет'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
										$('#pick_label_color<?php echo $language['language_id']; ?>').jPicker({window:{title:'Выбери цвет'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
									});
								</script>
								
								<tr>									
									<td>
										<span class="status_color" style="display:inline-block; padding:3px 5px; margin-bottom:5px; background:#ff7815; color:#FFF">Анонс</span><br />
										<textarea name="actions_description[<?php echo $language['language_id']; ?>][anonnce]" cols="100" rows="2"><?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['anonnce'] : ''; ?></textarea>
									</td>
								</tr>
								<tr>
									<td>
										<span class="status_color" style="display:inline-block; padding:3px 5px; margin-bottom:5px; background:#ff7815; color:#FFF">Короткий текст</span><br />
										<textarea name="actions_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['description'] : ''; ?></textarea>
										<?php if (isset($error_description[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>								
									<td>
										<span class="status_color" style="display:inline-block; padding:3px 5px; margin-bottom:5px; background:#ff7815; color:#FFF">Полное описание</span><br />
										<textarea name="actions_description[<?php echo $language['language_id']; ?>][content]" id="content<?php echo $language['language_id']; ?>"><?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['content'] : ''; ?></textarea>
										<?php if (isset($error_content[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_content[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
				<div id="tab-data">
					<table class="form">
						<tr>
							<td width="16%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Статус</span>													
							</td>
							<td width="16%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Удалять не в наличии</span>													
							</td>
							<td width="16%">
								<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Только в наличии</span>													
							</td>
							<td width="16%">
								<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Вывести все активные</span>													
							</td>
							<td width="16%">
								<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Дата начала</span>													
							</td>
							<td width="16%">
								<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Дата окончания</span>													
							</td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td>
								<select name="status">
									<?php if ($status) { ?>
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
								<select name="only_in_stock">
									<?php if ($only_in_stock) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>														
							</td>
							<td>
								<select name="display_all_active">
									<?php if ($display_all_active) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>														
							</td>
							<td><input class="date_start" type="text" name="date_start" value="<?php echo $date_start; ?>"></input></td>
							<td><input class="date_end" type="text" name="date_end" value="<?php echo $date_end; ?>"></input></td>
						</tr>
					</table>
					<table class="form">
						<tr>
							<td><?php echo $entry_image; ?></td>
							<td>
								<div class="image">
									<img src="<?php echo $preview; ?>" alt="" id="thumb" onclick="image_upload('image', 'thumb');" /><br />
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
									<a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
								</div>
								
								<br />
								<?php foreach ($languages as $language) { ?>
									<div class="image">
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
										
										<img src="<? echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['thumb_overload'] : '' ?>" alt="" id="thumb_overload<?php echo $language['language_id']; ?>" onclick="image_upload('image_overload<?php echo $language['language_id']; ?>', 'thumb_overload<?php echo $language['language_id']; ?>');" /><br />
										
										<input type="hidden" name="actions_description[<?php echo $language['language_id']; ?>][image_overload]" value="<? echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['image_overload'] : '' ?>" id="image_overload<?php echo $language['language_id']; ?>" />
										
										<a onclick="image_upload('image_overload<?php echo $language['language_id']; ?>', 'thumb_overload<?php echo $language['language_id']; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
										<a onclick="$('#thumb_overload<?php echo $language['language_id']; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_overload<?php echo $language['language_id']; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
										
									</div>
								<? } ?>
								
							</td>
						</tr>
						<tr>
							<td>Картинка в листинге<br />
								Ш=380 * В=491
							</td>
							<td>
								<div class="image">
									<img src="<?php echo $preview_to_cat; ?>" alt="" id="thumb_to_cat" onclick="image_upload('image_to_cat', 'thumb_to_cat');" /><br />
									<input type="hidden" name="image_to_cat" value="<?php echo $image_to_cat; ?>" id="image_to_cat" />
									<a onclick="image_upload('image_to_cat', 'thumb_to_cat');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_to_cat').attr('src', '<?php echo $no_image; ?>'); $('#image_to_cat').attr('value', '');"><?php echo $text_clear; ?></a>
								</div>
								<br />
								<?php foreach ($languages as $language) { ?>
									<div class="image">
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
										
										<img src="<? echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['thumb_to_cat_overload'] : '' ?>" alt="" id="thumb_to_cat_overload<?php echo $language['language_id']; ?>" onclick="image_upload('image_to_cat_overload<?php echo $language['language_id']; ?>', 'thumb_to_cat_overload<?php echo $language['language_id']; ?>');" /><br />
										
										<input type="hidden" name="actions_description[<?php echo $language['language_id']; ?>][image_to_cat_overload]" value="<? echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['image_to_cat_overload'] : '' ?>" id="image_to_cat_overload<?php echo $language['language_id']; ?>" />
										
										<a onclick="image_upload('image_to_cat_overload<?php echo $language['language_id']; ?>', 'thumb_to_cat_overload<?php echo $language['language_id']; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
										<a onclick="$('#thumb_to_cat_overload<?php echo $language['language_id']; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_to_cat_overload<?php echo $language['language_id']; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
										
									</div>
								<? } ?>
							</td>
							
							
						</tr>
						
					</table>
				</div>
				<div id="tab-seo">
					<div id="languages-seo" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language-seo<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language-seo<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td><?php echo $entry_h1; ?></td>
									<td><input type="text" name="actions_description[<?php echo $language['language_id']; ?>][h1]" size="100" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['h1'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td><?php echo $entry_title; ?></td>
									<td><input type="text" name="actions_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td><?php echo $entry_meta_keywords; ?></td>
									<td><input type="text" name="actions_description[<?php echo $language['language_id']; ?>][meta_keywords]" size="100" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['meta_keywords'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td><?php echo $entry_meta_description; ?></td>
									<td><input type="text" name="actions_description[<?php echo $language['language_id']; ?>][meta_description]" size="100" value="<?php echo isset($actions_description[$language['language_id']]) ? $actions_description[$language['language_id']]['meta_description'] : ''; ?>" /></td>
								</tr>
							</table>
						</div>
					<?php } ?>
					
					<div>
						<table class="form">
							<td><?php echo $entry_keyword; ?></td>
							<td>	
								<?php foreach ($languages as $language) { ?>
									<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php  if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" size="100" />
									<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<!-- BEGIN LINKS -->
			<div id="tab-links">
				<table class="form">
					<tr>						
						<td style="width: 15%">
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Привязать спецпредложение</span>													
						</td>
						<td style="width: 15%">
							<span class="status_color" style="display:inline-block; padding:3px 5px;background:#00ad07; color:#FFF">Привязать к бренду</span>					
							<span style="border-bottom:1px dashed #CF4A61; cursor:pointer; color:#CF4A61;" onclick="$('#manufacturer').val(''); $('#manufacturer_id').val('');">очистить</span>
						</td>
						<td style="width: 25%">
							<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Привязать к специальной категории</span>
							<span style="border-bottom:1px dashed #CF4A61; cursor:pointer; color:#CF4A61;" onclick="$('#category_related').val(''); $('#category_related_id').val('');">очистить</span>
						</td>
						<td style="width: 15%">
							<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Без пересечений</span>		
						</td>
						<td style="width: 15%">
							<span class="status_color" style="display:inline-block; padding:3px 5px;background:#7F00FF; color:#FFF">Лимит товаров</span>		
						</td>
					</tr>
					<tr>						
						<td>
							<select name="ao_group">
								<option value="">Не привязывать</option>
								<? foreach ($all_ao_groups as $all_ao_group) { ?>
									<? if ($all_ao_group == $ao_group) { ?>
										<option value="<? echo $all_ao_group; ?>" selected="selected"><? echo $all_ao_group; ?></option>
										<? } else { ?>
										<option value="<? echo $all_ao_group; ?>"><? echo $all_ao_group; ?></option>
									<? } ?>
								<? } ?>
							</select>
						</td>
						<td>
							<input type="text" name="manufacturer" id="manufacturer" value="<?php echo $manufacturer ?>" />
							<input type="hidden" name="manufacturer_id" id="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />			
						</td>
						<td>								
							<input type="text" name="category_related" id="category_related" value="<?php echo $category_related; ?>" style="width:90%" />							
							<input type="hidden" name="category_related_id" id="category_related_id" value="<?php echo $category_related_id; ?>" />															
						</td>

						<td>
							<select name="category_related_no_intersections">
								<?php if ($category_related_no_intersections) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</td>
						<td>
							<input type="number" name="category_related_limit_products" value="<?php echo $category_related_limit_products; ?>" />
						</td>
					</tr>
					<tr style="border-bottom:1px dashed gray">
						<td colspan="2"></td>
						<td colspan="3">
							<span class="help">
								Либо специальная, либо обычная категория. Если настройка "Привязка без пересечений" включена, то в акции будут выведены товары привязанной категории без пересечений и разбивки по основным категориями.
							</span>
						</td>
					</tr>
				</table>
				
				<table class="form">
					<tr>						
						<td width="33%">
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Показывать в категориях</span>													
						</td>
						<td width="33%">
							<span class="status_color" style="display:inline-block; padding:3px 5px;background:#00ad07; color:#FFF">Показывать в товарах категории</span>													
						</td>
						<td width="33%">
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Магазины</span>													
						</td>
					</tr>
					<tr>
						
						<td valign="top">
							<input type="text" name="category" value="" style="width:400px" /><br /><br />
							<div id="actions-category" class="scrollbox" style="min-height: 200px; width:450px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($actions_categories as $actions_category) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div id="actions-category<?php echo $actions_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $actions_category['name']; ?><img src="view/image/delete.png" alt="" />
										<input type="hidden" name="actions_category[]" value="<?php echo $actions_category['category_id']; ?>" />
									</div>
								<?php } ?>
							</div>
							<br />
							<span class="help">в этих категориях будет отображаться с какой-то долей вероятности баннер в сетке товаров</span>
							
						</td>
						
						<td>
							<input type="text" name="category_in" value="" style="width:400px"/><br /><br />
							
							<div id="actions-category_in" class="scrollbox" style="min-height: 200px; width:450px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($actions_categories_in as $actions_category_in) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div id="actions-category_in<?php echo $actions_category_in['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $actions_category_in['name']; ?><img src="view/image/delete.png" alt="" />
										<input type="hidden" name="actions_category_in[]" value="<?php echo $actions_category_in['category_id']; ?>" />
									</div>
								<?php } ?>
							</div>	
							<br />
							<span class="help">в товарах этих категорий будет отображаться информации об этой акции</span>
							
						</td>
						
						<td>
							<div class="scrollbox" style="min-height: 200px;">
								<?php $class = 'even'; ?>								
								<?php foreach ($stores as $store) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($store['store_id'], $actions_store)) { ?>
											<input id="actions_store[]_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="actions_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
											<label for="actions_store[]_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } else { ?>
											<input id="actions_store[]_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="actions_store[]" value="<?php echo $store['store_id']; ?>" />
											<label for="actions_store[]_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
							<input type="hidden" name="fancybox" value="0">
						</td>
					</tr>
					
					
				</table>
				
			</div>
			
			<div id="tab-products">
				<table class="form">
					
					<tr>
						<td colspan="3">
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF; width:230px">Поиск товара по артикулу или имени</span>
							<input type="text" name="related" value="" style="width:500px" />
						</td>
					</tr>
					
					<tr>
						<td colspan="3">
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF; width:230px">Добавить товары из категории</span>
							<select id="categoryProduct" onchange="getProducts();">
								<?php foreach($categories as $category) { ?>
									<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
								<?php } ?>
							</select><input name="filter_special" type="checkbox" onchange="getProducts()" /><?php echo $entry_all_products?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:0;" width="45%">
							<select multiple="multiple" id="product" size="20" style="width:100%; height:500px;"></select>
						</td>
						<td style="vertical-align: middle; width: 40px;">
							<input type="button" value="&gt;&gt;" onclick="addRelProduct();" />
							<br /><br />
							<input type="button" value="&lt;&lt;" onclick="removeRelProduct();" />
						</td>
						<td style="padding:0;" width="45%">
							<select multiple="multiple" id="relProduct" size="20" style="width:100%; height:500px;"></select>
						</td>
					</tr>
				</table>
				<div id="product_related">
					<?php foreach($product_related as $product_id) { ?>
						<input type="hidden" name="product_related[]" value="<?php echo $product_id; ?>"/>
					<?php } ?>
				</div>
			</div>
			
			<!-- END LINKS -->
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
							<td class="left"><select name="actions_layout[0][layout_id]">
								<option value=""></option>
								<?php foreach ($layouts as $layout) { ?>
									<?php if (isset($actions_layout[0]) && $actions_layout[0] == $layout['layout_id']) { ?>
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
								<td class="left"><select name="actions_layout[<?php echo $store['store_id']; ?>][layout_id]">
									<option value=""></option>
									<?php foreach ($layouts as $layout) { ?>
										<?php if (isset($actions_layout[$store['store_id']]) && $actions_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
		$('input[name=\'category_related\']').autocomplete({
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
				$('input[name=\'category_related\']').val(ui.item.label);
				$('input[name=\'category_related_id\']').val(ui.item.value);
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	//--></script> 

<script type="text/javascript"><!--
	
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
			$('#actions-category' + ui.item.value).remove();
			
			$('#actions-category').append('<div id="actions-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="actions_category[]" value="' + ui.item.value + '" /></div>');
			
			$('#actions-category div:odd').attr('class', 'odd');
			$('#actions-category div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#actions-category div img').live('click', function() {
		$(this).parent().remove();
		
		$('#actions-category div:odd').attr('class', 'odd');
		$('#actions-category div:even').attr('class', 'even');	
	});
	
	// Category IN
	$('input[name=\'category_in\']').autocomplete({
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
			$('#actions-category_in' + ui.item.value).remove();
			
			$('#actions-category_in').append('<div id="actions-category_in' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="actions_category_in[]" value="' + ui.item.value + '" /></div>');
			
			$('#actions-category_in div:odd').attr('class', 'odd');
			$('#actions-category_in div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#actions-category_in div img').live('click', function() {
		$(this).parent().remove();
		
		$('#actions-category_in div:odd').attr('class', 'odd');
		$('#actions-category_in div:even').attr('class', 'even');	
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
							$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '"  onclick="image_upload(\'image\', \'thumb\');" />');
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
	<?php foreach ($languages as $language) { ?>
		CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		});
		CKEDITOR.replace('content<?php echo $language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		});
	<?php } ?>
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
	
	old_date_end = 0;
	
	$('.date_start').datetimepicker({
		dateFormat: 'dd-mm-yy',
		timeFormat: 'h:m'
	});
	
	$('.date_end').datetimepicker({
		dateFormat: 'dd-mm-yy',
		timeFormat: 'h:m'
	});
	
	<?php
		if ($date_end == 0){
			echo "$('.date_end').datepicker('disable');";
		}
	?>
	
	
	$('#date_never').click(function(){
		if( $('#date_never').is(':checked') ) {
			$('.date_end').datepicker('disable');
			old_date_end = $('.date_end').val();
			$('.date_end').val("0");
			$('.date_end').attr("readOnly", true);
			} else {
			$('.date_end').val(old_date_end);
			$('.date_end').attr("readOnly", false);
			$('.date_end').datepicker('enable');
		}
	});
//--></script> 
<script type="text/javascript"><!--
	$('#tabs a').tabs(); 
	$('#languages a').tabs();
	$('#languages-seo a').tabs();
//--></script> 
<script type="text/javascript"><!--
	function addRelProduct() {
		$('#product :selected').each(function() {
			$(this).remove();
			
			$('#relProduct option[value=\'' + $(this).attr('value') + '\']').remove();
			$('#relProduct').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
			$('#product_related input[value=\'' + $(this).attr('value') + '\']').remove();
			$('#product_related').append('<input type="hidden" name="product_related[]" value="' + $(this).attr('value') + '" />');
		});
	}
	
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
			$('#relProduct option[value=\'' + ui.item.value + '\']').remove();
			$('#relProduct').append('<option value="' + ui.item.value + '">' + ui.item.label + '</option>');
			$('#product_related input[value=\'' + ui.item.value + '\']').remove();
			
			$('#product_related').append('<input type="hidden" name="product_related[]" value="' + ui.item.value + '" />');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	function removeRelProduct() {
		$('#relProduct :selected').each(function() {
			$(this).remove();
			
			$('#product option[value=\'' + $(this).attr('value') + '\']').remove();
			$('#product').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
			$('#product_related input[value=\'' + $(this).attr('value') + '\']').remove();
		});
	}
	function getProducts() {
		var filter_special;
		if ($('input[name=filter_special]').attr('checked')) {
			filter_special = 0;
			} else {
			filter_special = 1;
		}
		
		$('#product option').remove();
		var actions_id = '<?php echo $actions_id;?>'; 	
		$.ajax({
			url: 'index.php?route=catalog/actions/catproduct&token=<?php echo $token;?>&actions_id=' + actions_id + '&category_id=' + $('#categoryProduct').attr('value') + '&filter_special=' + filter_special,
			dataType: 'json',
			success: function(data) {
				for (i = 0; i < data.length; i++) {
					$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
				}
			}
		});
	}
	function getRelProducts() {
		$('#relProduct option').remove();
		
		$.ajax({
			url: 'index.php?route=catalog/actions/relproduct&token=<?php echo $token;?>',
			type: 'POST',
			dataType: 'json',
			data: $('#product_related input'),
			success: function(data) {
				$('#product_related input').remove();
				for (i = 0; i < data.length; i++) {
					$('#relProduct').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
					$('#product_related').append('<input type="hidden" name="product_related[]" value="' + data[i]['product_id'] + '" />');
				} 
			}
		});
	}
	
	getProducts();
	getRelProducts();
//--></script>
<?php echo $footer; ?>																		