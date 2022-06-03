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
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-data">Данные</a>				
				<a href="#tab-image">Картинки</a>
			<div class="clr"></div></div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					
					<table class="form">						
						
						<tr>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Название</span>
							</td>
							
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">ISO код</span>
							</td>
							
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Сортировка</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Файл шаблона</span>
							</td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="name" value="<?php echo $name; ?>" size="30" />
							</td>
							
							<td>
								<input type="text" name="flag" value="<?php echo $flag; ?>" size="5" />
							</td>
							
							<td>
								<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="30" />
							</td>
							<td>
								<input type="text" name="template" value="<?php echo $template; ?>" size="30" />
							</td>
							
						</tr>	
					</table>
					
					<table class="form">						
						
						<tr>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Магазины</span>
							</td>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Подбирать по стране</span>
							</td>
							<td style="width:20%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">SEO URL</span>
							</td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							
							<td>
								<div class="scrollbox" style="max-width:350px; height:250px;">
									<?php $class = 'even'; ?>								
									<?php foreach ($stores as $store) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
											<?php if (in_array($store['store_id'], $countrybrand_store)) { ?>
												<input id="countrybrand_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="countrybrand_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
												<label for="countrybrand_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
												<?php } else { ?>
												<input id="countrybrand_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="countrybrand_store[]" value="<?php echo $store['store_id']; ?>" />
												<label for="countrybrand_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
								<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
								
							</td>
							
							<td>	
								<?php foreach ($languages as $language) { ?>
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <input type="text" style="margin-bottom:5px;" id="countrybrand_description_<?php echo $language['language_id']; ?>_type" name="countrybrand_description[<?php echo $language['language_id']; ?>][type]" value="<?php if (isset($countrybrand_description[$language['language_id']]['type'])) { echo $countrybrand_description[$language['language_id']]['type']; } ?>" />
									
									<i class="fa fa-arrow-left"></i>

									
									<?php if (!empty($locations[$language['language_id']])) { ?>
										<select class="copyselect" data-language-id="<?php echo $language['language_id']; ?>" id="locations_<?php echo $locations[$language['language_id']]; ?>">
											<?php foreach ($locations[$language['language_id']] as $location) { ?>
											<option value="<?php echo $location; ?>"><?php echo $location; ?></option>											
											<?php } ?>
										</select>
									<?php } ?><br />
													
								<?php } ?>
								<script>
									$('.copyselect').change(function(){
										var _elem = $(this);
										var _val = $(this).children('option:selected').val();
										var _language_id = _elem.attr('data-language-id');
										
										$('#countrybrand_description_' + _language_id + '_type').val(_val);
									});
								</script>
							</td>
							
							<td>	
								<?php foreach ($languages as $language) { ?>
									<input type="text" style="margin-bottom:5px;" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<?php } ?>
							</td>
							
						</tr>
						
					</table>
					
					
					<table class="form">
						
						<tr>
							<td style="width:50%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Картинка для каталога</span>
							</td>
							<td style="width:50%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Большой баннер</span>
							</td>
						</tr>
						
						<tr>
							<td valign="top">
								<div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
								<br /><a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div>
							</td>

							<td valign="top">
								<div class="image"><img src="<?php echo $banner_thumb; ?>" alt="" id="banner_thumb" />
									<input type="hidden" name="banner" value="<?php echo $banner; ?>" id="banner" />
								<br /><a onclick="image_upload('banner', 'banner_thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#banner_thumb').attr('src', '<?php echo $no_image; ?>'); $('#banner').attr('value', '');"><?php echo $text_clear; ?></a></div>
							</td>
						</tr>
						
					</table>
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
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Название</span></p>
										<input type="text" name="countrybrand_description[<?php echo $language['language_id']; ?>][name_overload]" size="255" value="<?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['name_overload'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Альтернативные названия, для умного поиска (каждое с новой строки)</span></p>
										<textarea name="countrybrand_description[<?php echo $language['language_id']; ?>][alternate_name]" cols="100" rows="20"><?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['alternate_name'] : ''; ?></textarea>
									</td>
								</tr>
								<tr>
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Мета тайтл:</span></p>
										<input type="text" name="countrybrand_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['seo_title'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Мета H1:</span></p>
									<input type="text" name="countrybrand_description[<?php echo $language['language_id']; ?>][seo_h1]" size="100" value="<?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['seo_h1'] : ''; ?>" /></td>
								</tr>
								<tr>
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Мета Description:</span></p>
									<textarea name="countrybrand_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Мета Keywords:</span></p>
									<textarea name="countrybrand_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Короткое описание:</span></p>
									<textarea rows='10' cols='100' name="countrybrand_description[<?php echo $language['language_id']; ?>][short_description]" id="short_description<?php echo $language['language_id']; ?>"><?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['short_description'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> Описание:</span></p>
									<textarea name="countrybrand_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($countrybrand_description[$language['language_id']]) ? $countrybrand_description[$language['language_id']]['description'] : ''; ?></textarea></td>
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
						<?php foreach ($countrybrand_images as $countrybrand_image) { ?>
							<tbody id="image-row<?php echo $image_row; ?>">
								<tr>
									<td class="left"><div class="image"><img src="<?php echo $countrybrand_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
										<input type="hidden" name="countrybrand_image[<?php echo $image_row; ?>][image]" value="<?php echo $countrybrand_image['image']; ?>" id="image<?php echo $image_row; ?>" />
										<br />
									<a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
									<td class="right"><input type="text" name="countrybrand_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $countrybrand_image['sort_order']; ?>" size="2" /></td>
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
		html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="countrybrand_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		html += '    <td class="right"><input type="text" name="countrybrand_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
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
				url: 'index.php?route=catalog/countrybrand/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				error:function(json){
					console.log(json)				
				},
				success: function(json) {
					json.unshift({
						'countrybrand_id':  0,
						'name':  'Не выбрано'
					});
					
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.countrybrand_id
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