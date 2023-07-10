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
			<h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?> : <?php echo $name; ?></h1>
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
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-data">Данные</a>
				<a href="#tab-reward">Бонусная программа</a>
				<a href="#tab-tags">Теги товаров</a>
				<a href="#tab-pagecontent">Контент на странице</a>
				<a href="#tab-design"><?php echo $tab_design; ?></a>
				<div class="clr"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					
					
					<table class="form">						
						
						<tr>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Название</span>
							</td>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Подсказка</span>
							</td>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Показывать в меню брендов</span>
							</td>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Показать список товаров в каталоге</span>
							</td>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Сортировка</span>
							</td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="name" value="<?php echo $name; ?>" size="30" />
							</td>
							<td>
								<input type="text" name="tip" value="<?php echo $tip; ?>" size="30" />
							</td>
							<td>
								<select name="menu_brand">
									<?php if ($menu_brand) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="show_goods">
									<?php if ($show_goods) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
							</td>
						</tr>
						
					</table>
					
					<table class="form">						
						
						<tr>
							<td style="width:40%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Мониторинг цен Priceva/PriceControl</span>
							</td>
							<td style="width:60%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Название файла с фидом Priceva/PriceControl</span>
							</td>							
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
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
							<td>
								<input type="text" name="priceva_feed" value="<?php echo $priceva_feed; ?>" size="30" />
								<br />
								<?php if ($priceva_feed) { ?>
									<?php if ($this->config->get('config_single_store_enable')) { ?>
										<div>
											<?php echo HTTPS_CATALOG; ?>feeds/<?php echo $this->config->get('config_priceva_directory_name'); ?>/<?php echo $this->config->get('config_priceva_directory_name'); ?>_<?php echo $priceva_feed; ?>_0.xml
										</div>
									<?php } ?>
								<?php } ?>
							</td>
							
						</tr>
						
					</table>
					
					<table class="form">
						
						<tr>
							<td><?php echo $entry_store; ?></td>
							<td>
								<div class="scrollbox" style="max-width:350px;">
									<?php $class = 'even'; ?>							
									<?php foreach ($stores as $store) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
											<?php if (in_array($store['store_id'], $manufacturer_store)) { ?>
												<input id="store_<?php echo $store['store_id']; ?>" class="checkbox"type="checkbox" name="manufacturer_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
												<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } else { ?>
												<input id="store_<?php echo $store['store_id']; ?>" class="checkbox"type="checkbox" name="manufacturer_store[]" value="<?php echo $store['store_id']; ?>" />
												<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_keyword; ?></td>
							<td><?php foreach ($languages as $language) { ?>
								<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_image; ?></td>
							<td valign="top"><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
								<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
							<br /><a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
						</tr>
						
						<tr>
							<td>Фоновая картинка блока</td>
							<td valign="top"><div class="back_image"><img src="<?php echo $back_thumb; ?>" alt="" id="back_thumb" />
								<input type="hidden" name="back_image" value="<?php echo $back_image; ?>" id="back_image" />
							<br /><a onclick="image_upload('back_image', 'back_thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#back_thumb').attr('src', '<?php echo $no_image; ?>'); $('#back_image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
						</tr>
						
						<tr>
							<td>Баннер</td>
							<td valign="top"><div class="image"><img src="<?php echo $thumb_banner; ?>" alt="" id="thumb_banner" />
								<input type="hidden" name="banner" value="<?php echo $banner; ?>" id="banner" />
							<br /><a onclick="image_upload('banner', 'thumb_banner');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_banner').attr('src', '<?php echo $no_image; ?>'); $('#banner').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
						</tr>
						
						<tr>
							<td>Размер баннера (Ш*В)</td>
							<td><input type="text" name="banner_width" value="<?php echo $banner_width; ?>" size="5" /> * <input type="text" name="banner_height" value="<?php echo $banner_height; ?>" size="5" /></td>
						</tr>
					</table>
				</div>
				
				<div id="tab-reward">
					<?php $reward_row = 0; ?>
					<? 
						$stores_reward = $stores;
						array_unshift($stores_reward, array('store_id' => -1, 'name' => 'Для всех магазинов'));
					?>
					
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
				
				<div id="tab-data">
					
					<div id="languages" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td>Альтернативные названия, для умного поиска (каждое с новой строки)</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][alternate_name]" cols="100" rows="20"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['alternate_name'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Страна происхожедния:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][location]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['location'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Тайтл:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['seo_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>H1:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][seo_h1]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['seo_h1'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Meta Description:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Meta Keywords:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Короткое описание (500 макс):</td>
									<td><textarea rows="10" cols="100" name="manufacturer_description[<?php echo $language['language_id']; ?>][short_description]" id="short_description<?php echo $language['language_id']; ?>"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['short_description'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Описание:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['description'] : ''; ?></textarea></td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
				
				<div id="tab-tags">
					
					<div id="languages-tags" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language-tag<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language-tag<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td>Товары тайтл:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][products_title]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['products_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Товары Meta Description:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][products_meta_description]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['products_meta_description'] : ''; ?></textarea></td>
								</tr>
								
								<tr>
									<td>Коллекции тайтл:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][collections_title]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['collections_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Коллекции Meta Description:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][collections_meta_description]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['collections_meta_description'] : ''; ?></textarea></td>
								</tr>
								
								<tr>
									<td>Категории тайтл:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][categories_title]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['categories_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Категории Meta Description:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][categories_meta_description]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['categories_meta_description'] : ''; ?></textarea></td>
								</tr>
								
								<tr>
									<td>Статьи тайтл:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][articles_title]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['articles_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Статьи Meta Description:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][articles_meta_description]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['articles_meta_description'] : ''; ?></textarea></td>
								</tr>
								
								<tr>
									<td>Новинки тайтл:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][newproducts_title]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['newproducts_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Новинки Meta Description:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][newproducts_meta_description]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['newproducts_meta_description'] : ''; ?></textarea></td>
								</tr>
								
								<tr>
									<td>Акции, скидки тайтл:</td>
									<td><input type="text" name="manufacturer_description[<?php echo $language['language_id']; ?>][special_title]" size="100" value="<?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['special_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Акции, скидки Meta Description:</td>
									<td><textarea name="manufacturer_description[<?php echo $language['language_id']; ?>][special_meta_description]" cols="40" rows="5"><?php echo isset($manufacturer_description[$language['language_id']]) ? $manufacturer_description[$language['language_id']]['special_meta_description'] : ''; ?></textarea></td>
								</tr>
								
							</table>
						</div>
					<?php } ?>
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
								<td class="left"><select name="manufacturer_layout[0][layout_id]">
									<option value=""></option>
									<?php foreach ($layouts as $layout) { ?>
										<?php if (isset($manufacturer_layout[0]) && $manufacturer_layout[0] == $layout['layout_id']) { ?>
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
									<td class="left"><select name="manufacturer_layout[<?php echo $store['store_id']; ?>][layout_id]">
										<option value=""></option>
										<?php foreach ($layouts as $layout) { ?>
											<?php if (isset($manufacturer_layout[$store['store_id']]) && $manufacturer_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
				
				<div id="tab-pagecontent">
					<div id="languages2" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language2<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /><?php echo $language['name']; ?></a>
						<?php } ?>
						<span style="vertical-align: -15px;"><input class="checkbox" type="checkbox" name="copyrussian" value="1" id="copyrussian" />
						<label for="copyrussian">Копировать ru на все.</label></span>
					</div>
					
					<? $max_row = 0; ?>
					<?php foreach ($languages as $language) { ?>
						<div id="language2<?php echo $language['language_id']; ?>">
							<table id="table_content_<?php echo $language['language_id']; ?>" style="width:100%">
								<? if (isset($manufacturer_page_content[$language['language_id']])) { ?>			
									<? $row=0; foreach ($manufacturer_page_content[$language['language_id']] as $page_content) { ?>
										
										<tr id="tr_content_<?php echo $page_content['manufacturer_page_content_id'] ; ?>">				
											<? if ($row%2==0) { ?>
												<td style="border-left:2px solid green; padding:10px;">
													<? } else { ?>
													<td style="border-left:2px solid orange; padding:10px;">
													<? } ?>
													<table style="width:100%">
														<tr>
															<td colspan="2">
																<a style="float:right;" class="button" onclick="$('#tr_content_<?php echo $page_content['manufacturer_page_content_id'] ; ?>').remove();" data-language-id="<?php echo $language['language_id']; ?>">удалить</a>
															</td>
														</tr>
														<tr>												
															<td>
																Заголовок: 
															</td>
															<td>
																<input type="text" name="manufacturer_page_content[<?php echo $language['language_id']; ?>][<?php echo $page_content['manufacturer_page_content_id'] ; ?>][title]" size="255" style="width:400px; margin-bottom:15px" value="<? echo $page_content['title'] ?>" />										
																&nbsp;&nbsp;&nbsp;Сортировка: <input type="text" name="manufacturer_page_content[<?php echo $language['language_id']; ?>][<?php echo $page_content['manufacturer_page_content_id'] ; ?>][sort_order]" size="2" style="width:30px; margin-bottom:10px" value="<? echo $page_content['sort_order'] ?>" />
															</td>
														</tr>
														<tr>
															<td>
																Контент / текст
															</td>
															<td>
																<textarea style="width:600px;" name="manufacturer_page_content[<?php echo $language['language_id']; ?>][<?php echo $page_content['manufacturer_page_content_id'] ; ?>][content]" id="content<?php echo $page_content['manufacturer_page_content_id'] ; ?>"><? echo $page_content['content'] ?></textarea>
															</td>
														</tr>
														<tr>
															<td>
																Тип блока
															</td>
															<td>
																<select name="manufacturer_page_content[<?php echo $language['language_id']; ?>][<?php echo $page_content['manufacturer_page_content_id'] ; ?>][type]">
																	<? if ($page_content['type'] == 'collections') { ?>
																		<option value="products" >Товары</option>
																		<option value="collections" selected="selected">Коллекции</option>
																		<option value="categories">Категории</option>
																		<? } elseif ($page_content['type'] == 'categories') { ?>
																		<option value="products" >Товары</option>
																		<option value="collections">Коллекции</option>
																		<option value="categories"  selected="selected">Категории</option>	
																		<? } else { ?>
																		<option value="products" selected="selected">Товары</option>
																		<option value="collections">Коллекции</option>
																		<option value="categories">Категории</option>						
																	<? } ?>														
																</select>
															</td>
														</tr>
														<tr>
															<td colspan="2">
																<table style="width:100%">
																	<tr>
																		
																		<td style="width:33%"> 
																			Товары<br />
																			<input type="text" class="product-a" data-row='<? echo $row; ?>' data-language-id='<?php echo $language['language_id']; ?>' data-content-id='<?php echo $page_content['manufacturer_page_content_id'] ; ?>' style="width:400px;" value="" /><br /><br />
																			<div id="products-<? echo $row; ?>" class="scrollbox" style="width:400px; min-height: 200px;">
																				<?php $class = 'odd'; ?>
																				<?php foreach ($page_content['real_products'] as $real_product) { ?>
																					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																					<div id="product-<? echo $row; ?>-<?php echo $real_product['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $real_product['name']; ?><img src="view/image/delete.png" class='delete-product-row' data-row-id="<? echo $row; ?>" alt="" />
																						<input type="hidden" name="manufacturer_page_content[<?php echo $language['language_id']; ?>][<?php echo $page_content['manufacturer_page_content_id'] ; ?>][products][]" value="<?php echo $real_product['product_id']; ?>" />
																					</div>
																				<?php } ?>
																			</div>
																		</td>
																		<td style="width:33%">
																			Коллекции<br />
																			<input type="text" class="collection-a" data-row='<? echo $row; ?>' data-language-id='<?php echo $language['language_id']; ?>' data-content-id='<?php echo $page_content['manufacturer_page_content_id'] ; ?>' style="width:400px;" value="" /><br /><br />
																			
																			<div id="collections-<? echo $row; ?>" class="scrollbox" style="width:400px; min-height: 200px;">
																				<?php $class = 'odd'; ?>
																				<?php foreach ($page_content['real_collections'] as $real_collection) { ?>
																					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																					<div id="collection-<? echo $row; ?>-<?php echo $real_collection['collection_id']; ?>" class="<?php echo $class; ?>"> <?php echo $real_collection['name']; ?>
																						<img src="view/image/delete.png" class='delete-collection-row' data-row-id="<? echo $row; ?>"  alt="" />
																						<input type="hidden" name="manufacturer_page_content[<?php echo $language['language_id']; ?>][<?php echo $page_content['manufacturer_page_content_id'] ; ?>][collections][]" value="<?php echo $real_collection['collection_id']; ?>" />
																					</div>
																				<?php } ?>
																			</div>
																			
																		</td>
																		<td style="width:33%">
																			Категории<br />
																			<input type="text" class="category-a" data-row='<? echo $row; ?>' data-language-id='<?php echo $language['language_id']; ?>' data-content-id='<?php echo $page_content['manufacturer_page_content_id'] ; ?>' style="width:400px;" value="" /><br /><br />
																			
																			<div id="categories-<? echo $row; ?>" class="scrollbox" style="width:400px; min-height: 200px;">
																				<?php $class = 'odd'; ?>
																				<?php foreach ($page_content['real_categories'] as $real_category) { ?>
																					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																					<div id="category-<? echo $row; ?>-<?php echo $real_category['category_id']; ?>" class="<?php echo $class; ?>"> <?php echo $real_category['name']; ?>
																						<img src="view/image/delete.png" class='delete-category-row' data-row-id="<? echo $row; ?>"  alt="" />
																						<input type="hidden" name="manufacturer_page_content[<?php echo $language['language_id']; ?>][<?php echo $page_content['manufacturer_page_content_id'] ; ?>][categories][]" value="<?php echo $real_category['category_id']; ?>" />
																					</div>
																				<?php } ?>
																			</div>
																			
																		</td>
																	</tr>
																</table>
															</td>
														</tr>					
													</table>
												</td>
											</tr>
											<? if ($page_content['manufacturer_page_content_id'] > $max_row) $max_row = $page_content['manufacturer_page_content_id']; ?>  			
										<? $row++; } ?>
									<? } ?>
								</table>
								<a style="margin-top:20px; float:right;" class="button add-menu-content" data-language-id="<?php echo $language['language_id']; ?>">Добавить</a>
							</div>
						<? } ?>	
						
					</div>
					
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript" >
		
		$('.product-a').autocomplete({
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
				var row_id = $(this).attr('data-row');
				var lang_id = $(this).attr('data-language-id');
				var content_id = $(this).attr('data-content-id');
				
				$('#products-' + row_id +'-'+ ui.item.value).remove();
				
				$('#products-' + row_id).append('<div id="product-' + row_id + '-' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" class="delete-product-row" data-row-id="'+ row_id +'" alt="" /><input type="hidden" name="manufacturer_page_content[' + lang_id + '][' + content_id + '][products][]" value="' + ui.item.value + '" /></div>');
				
				$('#products-' + row_id +' div:odd').attr('class', 'odd');
				$('#products-' + row_id +' div:even').attr('class', 'even');
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
		$('img.delete-product-row').live('click', function() {
			$(this).parent().remove();
			
			var row_id = $(this).attr('data-row');
			
			$('#products-' + row_id +' div:odd').attr('class', 'odd');
			$('#products-' + row_id +' div:even').attr('class', 'even');
		});
	</script> 
	
	<script type="text/javascript" >
		
		$('.category-a').autocomplete({
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
				var row_id = $(this).attr('data-row');
				var lang_id = $(this).attr('data-language-id');
				var content_id = $(this).attr('data-content-id');
				
				$('#categories-' + row_id +'-'+ ui.item.value).remove();
				
				$('#categories-' + row_id).append('<div id="category-' + row_id + '-' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" class="delete-category-row" data-row-id="'+ row_id +'" alt="" /><input type="hidden" name="manufacturer_page_content[' + lang_id + '][' + content_id + '][categories][]" value="' + ui.item.value + '" /></div>');
				
				$('#categories-' + row_id +' div:odd').attr('class', 'odd');
				$('#categories-' + row_id +' div:even').attr('class', 'even');
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
		$('img.delete-category-row').live('click', function() {
			$(this).parent().remove();
			
			var row_id = $(this).attr('data-row');
			
			$('#category-' + row_id +' div:odd').attr('class', 'odd');
			$('#category-' + row_id +' div:even').attr('class', 'even');
		});
	</script> 
	
	<script type="text/javascript" >
		
		$('.collection-a').autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/collection/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {		
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
				var row_id = $(this).attr('data-row');
				var lang_id = $(this).attr('data-language-id');
				var content_id = $(this).attr('data-content-id');
				
				$('#collections-' + row_id +'-'+ ui.item.value).remove();
				
				$('#collections-' + row_id).append('<div id="collection-' + row_id + '-' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" class="delete-collection-row" data-row-id="'+ row_id +'" alt="" /><input type="hidden" name="manufacturer_page_content[' + lang_id + '][' + content_id + '][collections][]" value="' + ui.item.value + '" /></div>');
				
				$('#collections-' + row_id +' div:odd').attr('class', 'odd');
				$('#collections-' + row_id +' div:even').attr('class', 'even');
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
		$('img.delete-collection-row').live('click', function() {
			$(this).parent().remove();
			
			var row_id = $(this).attr('data-row');
			
			$('#collections-' + row_id +' div:odd').attr('class', 'odd');
			$('#collections-' + row_id +' div:even').attr('class', 'even');
		});
	</script> 
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
			html += ' <input type="text" name="manufacturer_page_content[' + language_id + '][' + module_row + '][title]" size="255" style="width:400px; margin-bottom:15px" value="" /> &nbsp;&nbsp;&nbsp;Сортировка: <input type="text" name="manufacturer_page_content[' + language_id + '][' + module_row + '][sort_order]" size="2" style="width:30px; margin-bottom:10px" value="0" />'																				
			html +=	'</td>';
			html +=	'</tr>';
			html += '<tr>';												
			html += '<td>Контент / текст:</td>';
			html +=	'<td>';
			html += '<textarea style="width:600px;" name="manufacturer_page_content[' + language_id + '][' + module_row + '][content]" id="content'+ module_row +'"></textarea>';
			html +=	'</td>';
			html +=	'</tr>';		
			html += '<tr>';												
			html += '<td>Тип блока</td>';
			html +=	'<td>';
			html += '<select name="manufacturer_page_content[' + language_id + '][' + module_row + '][standalone]">';
			html += '<option value="products" selected="selected">Товары</option><option value="collections">Коллекции</option><option value="categories">Категории</option>';
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
				resizable: true,
				modal: false
			});
		};
	//--></script> 
	<script type="text/javascript"><!--
		$('#tabs a').tabs();
		$('#languages a').tabs();
		$('#languages2 a').tabs();  
		$('#languages-tags a').tabs();  
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
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});			
		<?php } ?>
	//--></script>
<?php echo $footer; ?>	