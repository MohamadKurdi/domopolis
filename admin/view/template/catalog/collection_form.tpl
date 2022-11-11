<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
		<?php } if ($success) {
			echo '<div class="success">' . $success . '</div>';
		?>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="apply()" class="button"><span>Применить</span></a>
			<script language="javascript">
				function apply(){
				$('#form').append('<input type="hidden" id="apply" name="apply" value="1"  />');
				$('#form').submit();
				}
			</script><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-data">Данные</a>
				<a href="#tab-reward">Бонусная программа</a>
				<a href="#tab-image">Картинки</a>
			<div class="clr"></div></div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_name; ?></td>
							<td><input type="text" name="name" value="<?php echo $name; ?>" size="100" />
								<?php if ($error_name) { ?>
									<span class="error"><?php echo $error_name; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_store; ?></td>
							<td><div class="scrollbox">
								<?php $class = 'even'; ?>								
								<?php foreach ($stores as $store) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($store['store_id'], $collection_store)) { ?>
											<input id="collection_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="collection_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
											<label for="collection_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } else { ?>
											<input id="collection_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="collection_store[]" value="<?php echo $store['store_id']; ?>" />
											<label for="collection_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
							</td>
						</tr>
						
						<tr>
							<td>Производитель / Бренд</td>
							<td><input type="text" name="manufacturer" value="<?php echo $manufacturer; ?>" size="100" />
							<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" /></td>
						</tr>
						
						<tr>
							<td>Родительская коллекция</td>
							<td><input type="text" name="parent" value="<?php echo $parent; ?>" size="100" />
							<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" /></td>
						</tr>
						
						<tr>
							<td>Тип коллекции</td>
							<td>	<?php foreach ($languages as $language) { ?>
								<input type="text" name="collection_description[<?php echo $language['language_id']; ?>][type]" value="<?php if (isset($collection_description[$language['language_id']]['type'])) { echo $collection_description[$language['language_id']]['type']; } ?>" />
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
							<?php } ?></td>
						</tr>
						
						<tr>
							<td>Это виртуальная коллекция!</td>
							<td><?php if ($virtual) { ?>
								<input id="virtual1" class="checkbox" type="checkbox" name="virtual" value="1" checked="checked" /><label for="virtual1"></label>
								<?php } else { ?>
								<input id="virtual1" class="checkbox" type="checkbox" name="virtual" value="1" /><label for="virtual1"></label>
							<?php } ?></td>
						</tr>
						
						<tr>
							<td>Не показывать в бренде!</td>
							<td><?php if ($no_brand) { ?>
								<input id="no_brand1" class="checkbox" type="checkbox" name="no_brand" value="1" checked="checked" /><label for="no_brand1"></label>
								<?php } else { ?>
								<input id="no_brand1" class="checkbox" type="checkbox" name="no_brand" value="1" /><label for="no_brand1"></label>
							<?php } ?></td>
						</tr>
						
						<tr>
							<td><?php echo $entry_keyword; ?></td>
							<td>	<?php foreach ($languages as $language) { ?>
								<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_image; ?></td>
							<td valign="top"><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
								<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
							<br /><a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
						</tr>
						<tr>
							<td>Большой баннер</td>
							<td valign="top"><div class="image"><img src="<?php echo $banner_thumb; ?>" alt="" id="banner_thumb" />
								<input type="hidden" name="banner" value="<?php echo $banner; ?>" id="banner" />
							<br /><a onclick="image_upload('banner', 'banner_thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#banner_thumb').attr('src', '<?php echo $no_image; ?>'); $('#banner').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
						</tr>
						<tr>
							<td>Не обновлять картинку автоматически!</td>
							<td><?php if ($not_update_image) { ?>
								<input id="not_update_image1" class="checkbox" type="checkbox" name="not_update_image" value="1" checked="checked" /><label for="not_update_image1"></label>
								<?php } else { ?>
								<input type="checkbox" name="not_update_image" value="1" />
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
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
							<a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td>Название</td>
									<td><input type="text" name="collection_description[<?php echo $language['language_id']; ?>][name_overload]" size="255" value="<?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['name_overload'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Альтернативные названия, для умного поиска (каждое с новой строки)</td>
									<td><textarea name="collection_description[<?php echo $language['language_id']; ?>][alternate_name]" cols="100" rows="20"><?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['alternate_name'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Мета тайтл:</td>
									<td><input type="text" name="collection_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['seo_title'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Мета H1:</td>
									<td><input type="text" name="collection_description[<?php echo $language['language_id']; ?>][seo_h1]" size="100" value="<?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['seo_h1'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>Meta Description:</td>
									<td><textarea name="collection_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Meta Keywords:</td>
									<td><textarea name="collection_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Короткое описание</td>
									<td><textarea rows='10' cols='100' name="collection_description[<?php echo $language['language_id']; ?>][short_description]" id="short_description<?php echo $language['language_id']; ?>"><?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['short_description'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Описание:</td>
									<td><textarea name="collection_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['description'] : ''; ?></textarea></td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
				
				<div id="tab-image">
					<table id="images" class="list">
						<thead>
							<tr>
								<td class="left"><?php echo $entry_image; ?></td>
								<td class="right"><?php echo $entry_sort_order; ?></td>
								<td></td>
							</tr>
						</thead>
						<?php $image_row = 0; ?>
						<?php foreach ($collection_images as $collection_image) { ?>
							<tbody id="image-row<?php echo $image_row; ?>">
								<tr>
									<td class="left"><div class="image"><img src="<?php echo $collection_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
										<input type="hidden" name="collection_image[<?php echo $image_row; ?>][image]" value="<?php echo $collection_image['image']; ?>" id="image<?php echo $image_row; ?>" />
										<br />
									<a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
									<td class="right"><input type="text" name="collection_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $collection_image['sort_order']; ?>" size="2" /></td>
									<td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button">Удалить</a></td>
								</tr>
							</tbody>
							<?php $image_row++; ?>
						<?php } ?>
						<tfoot>
							<tr>
								<td colspan="2" class="right"></td>
								<td class="right"><a onclick="addImage();" class="button">Добавить картинку</a></td>
							</tr>
						</tfoot>
					</table>
				</div>
				
				
				<input type="hidden" name="continue" id="jb-save-cont" value="0">
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	var image_row = <?php echo $image_row; ?>;
	
	function addImage() {
		html  = '<tbody id="image-row' + image_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="collection_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		html += '    <td class="right"><input type="text" name="collection_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
		html += '    <td class="right"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button">Удалить</a></td>';
		html += '  </tr>';
		html += '</tbody>';
		
		$('#images tfoot').before(html);
		
		image_row++;
	}
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
	$('input[name=\'parent\']').autocomplete({
		delay: 500,
		source: function(request, response) {		
			$.ajax({
				url: 'index.php?route=catalog/collection/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
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
			$('input[name=\'parent\']').val(ui.item.label);
			$('input[name=\'parent_id\']').val(ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
//--></script>
<script type="text/javascript"><!--
	$('input[name=\'manufacturer\']').autocomplete({
		delay: 500,
		source: function(request, response) {		
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				error:function(json){
					console.log(json)				
				},
				success: function(json) {
					json.unshift({
						'manufacturer_id':  0,
						'name':  'Не выбрано'
					});
					
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
			$('input[name=\'manufacturer\']').val(ui.item.label);
			$('input[name=\'manufacturer_id\']').val(ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
//--></script>    
<script type="text/javascript"><!--
	$('#tabs a').tabs(); 
	$('#languages a').tabs();
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