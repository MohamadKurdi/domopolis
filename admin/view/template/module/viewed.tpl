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
	<?php if ($error_layout) { ?>
		<div class="warning"><?php echo sprintf($error_layout,$product_layout_name); ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">   
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><?php echo $entry_count; ?></td>
						<td><input type="text" name="viewed_count" value="<?php echo $viewed_count; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_show_on_product; ?></td>
						<td>
							<label><input type="radio" name="show_on_product" value="1" <?php echo $show_on_product ? 'checked="checked"' : ''; ?> /><?php echo $text_yes; ?></label>
							<label><input type="radio" name="show_on_product" value="0" <?php echo !$show_on_product ? 'checked="checked"' : ''; ?> /><?php echo $text_no; ?></label>
						</td>
					</tr>
				</table>
				
				<table class="form">	
					<tr>
						<? for ($i=1; $i<=5; $i++) { ?>
							<th style="width:20%">Блок <? echo $i; ?></th>	
						<? } ?>
					</tr>
					<tr>  
						<? for ($i=1; $i<=5; $i++) { ?>
							<td style="width:20%">Заголовок<br />
							
							<?php foreach ($languages as $language) { ?>
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
							
							<input type="text" name="blockviewed_titles_<? echo $i; ?>_<?php echo $language['language_id']; ?>" value="<? echo ${'blockviewed_titles_'.$i.'_'.$language['language_id']}; ?>" />
							<br />
							<?php } ?>														
						<? } ?>
					</tr>
					<tr>  
						<? for ($i=1; $i<=5; $i++) { ?>
							<td style="width:20%">
								<div style="float:left;">
									<img src="<? echo ${'blockviewed_images_'.$i.'_image'}; ?>" alt="" id="blockviewed_images_<? echo $i; ?>_thumb" />			  
									<input type="hidden" id="blockviewed_images_<? echo $i; ?>_image" name="blockviewed_images_<? echo $i; ?>" value="<? echo ${'blockviewed_images_'.$i}; ?>" /><br />			  
									<a onclick="image_upload('blockviewed_images_<? echo $i; ?>_image', 'blockviewed_images_<? echo $i; ?>_thumb');">Выбр.</a>&nbsp;|&nbsp;
									<a onclick="$('#blockviewed_images_<? echo $i; ?>_thumb').attr('src', '<?php echo $no_image; ?>'); $('#blockviewed_images_<? echo $i; ?>_image').attr('value', '');">Очист.</a>
								</div>
								<div style="float:left;padding-left:10px;">
									W = 280px<br />
									H = 491px
								</div>
							</td>
						<? } ?>
					</tr>
					<tr>  
						<? for ($i=1; $i<=5; $i++) { ?>
							<td style="width:20%"><span style="display:inline-block;width:80px;">Линк</span> <input type="text" name="blockviewed_hrefs_<? echo $i; ?>" value="<? echo ${'blockviewed_hrefs_'.$i}; ?>" /></td>
						<? } ?>
					</tr>
					<tr>  
						<? for ($i=1; $i<=5; $i++) { ?>
							<td style="width:20%">
								<span style="display:inline-block;width:80px;">Авто</span>

								<select name="blockviewed_empty_type_<? echo $i; ?>">
									<?php if (${'blockviewed_empty_type_'.$i} == 'none') { ?>
										<option value="none" selected="selected">Ничего, спрятать блок</option>
									<?php } else { ?>
										<option value="none">Ничего, спрятать блок</option>
									<?php } ?>
									<?php if (${'blockviewed_empty_type_'.$i} == 'top-viewed') { ?>
										<option value="top-viewed" selected="selected">Топ просмотров</option>
									<?php } else { ?>
										<option value="top-viewed">Топ просмотров</option>
									<?php } ?>
									<?php if (${'blockviewed_empty_type_'.$i} == 'new') { ?>
										<option value="new" selected="selected">Новинки</option>
									<?php } else { ?>
										<option value="new">Новинки</option>
									<?php } ?>
								</select>							 
						<? } ?>
					</tr>					
					<tr> 
						<? for ($i=1; $i<=5; $i++) { ?>
							<td style="width:20%">
								<input type="text" name="product<? echo $i; ?>" value="" style="width:90%;margin-bottom:5px;"/><br />
								<div id="blockviewed-product<? echo $i; ?>" class="scrollbox" style="height:250px;">
									<?php $class = 'odd'; ?>
									<?php foreach (${'products'.$i} as $product) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div id="blockviewed-product<? echo $i; ?><?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"><?php echo $product['name']; ?> <img src="view/image/delete.png" alt="" />
											<input type="hidden" value="<?php echo $product['product_id']; ?>" />
										</div>
									<?php } ?>
								</div>
								<input type="hidden" name="blockviewed_product_<? echo $i; ?>" value="<?php echo ${'blockviewed_product_'.$i}; ?>" />
							</td>	
						<? } ?>
					</tr>
				</table>
				
				<table id="module" class="list">
					<thead>
						<tr>
							<td class="left"><?php echo $entry_image; ?></td>
							<td class="left"><?php echo $entry_layout; ?></td>
							<td class="left"><?php echo $entry_position; ?></td>
							<td class="left"><?php echo $entry_status; ?></td>
							<td class="left">Табы</td>
							<td class="right"><?php echo $entry_sort_order; ?></td>							
							<td class="right">Блоки товаров</td>
							<td></td>
						</tr>
					</thead>
					<?php $module_row = 0; ?>
					<?php foreach ($modules as $module) { ?>
						<tbody id="module-row<?php echo $module_row; ?>">
							<tr>
								<td class="left"><input type="text" name="viewed_module[<?php echo $module_row; ?>][image_width]" value="<?php echo $module['image_width']; ?>" size="3" />
									<input type="text" name="viewed_module[<?php echo $module_row; ?>][image_height]" value="<?php echo $module['image_height']; ?>" size="3" />
									<?php if (isset($error_image[$module_row])) { ?>
										<span class="error"><?php echo $error_image[$module_row]; ?></span>
									<?php } ?></td>
									<td class="left"><select name="viewed_module[<?php echo $module_row; ?>][layout_id]">
										<?php foreach ($layouts as $layout) { ?>
											<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
												<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select></td>
									<td class="left">
										<select name="viewed_module[<?php echo $module_row; ?>][position]">
										<?php if ($module['position'] == 'content_top') { ?>
											<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
											<?php } else { ?>
											<option value="content_top"><?php echo $text_content_top; ?></option>
										<?php } ?>
										<?php if ($module['position'] == 'content_bottom') { ?>
											<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
											<?php } else { ?>
											<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
										<?php } ?>
										<?php if ($module['position'] == 'column_left') { ?>
											<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
											<?php } else { ?>
											<option value="column_left"><?php echo $text_column_left; ?></option>
										<?php } ?>
										<?php if ($module['position'] == 'column_right') { ?>
											<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
											<?php } else { ?>
											<option value="column_right"><?php echo $text_column_right; ?></option>
										<?php } ?>
									</select></td>
									<td class="left"><select name="viewed_module[<?php echo $module_row; ?>][status]">
										<?php if ($module['status']) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select></td>
									<td class="left"><select name="viewed_module[<?php echo $module_row; ?>][tabs]">
										<?php if ($module['tabs']) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select></td>	
									<td class="right"><input type="text" name="viewed_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
									<td class="right"><input type="text" name="viewed_module[<?php echo $module_row; ?>][product_block]" value="<?php echo $module['product_block']; ?>" size="25" /></td>
									<td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
							</tr>
						</tbody>
						<?php $module_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="5"></td>
							<td class="right"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div> 
	<? for ($i=1; $i<=5; $i++) { ?>
	<script type="text/javascript"><!--
		$('input[name=\'product<? echo $i; ?>\']').autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&only_enabled=1&filter_name=' +  encodeURIComponent(request.term),
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
				$('#blockviewed-product<? echo $i; ?>' + ui.item.value).remove();
				
				$('#blockviewed-product<? echo $i; ?>').append('<div id="blockviewed-product<? echo $i; ?>' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" value="' + ui.item.value + '" /></div>');
				
				$('#blockviewed-product<? echo $i; ?> div:odd').attr('class', 'odd');
				$('#blockviewed-product<? echo $i; ?> div:even').attr('class', 'even');
				
				data = $.map($('#blockviewed-product<? echo $i; ?> input'), function(element){
					return $(element).attr('value');
				});
				
				$('input[name=\'blockviewed_product_<? echo $i; ?>\']').attr('value', data.join());
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
		
		$('#blockviewed-product<? echo $i; ?> div img').live('click', function() {
			$(this).parent().remove();
			
			$('#blockviewed-product<? echo $i; ?> div:odd').attr('class', 'odd');
			$('#blockviewed-product<? echo $i; ?> div:even').attr('class', 'even');
			
			data = $.map($('#blockviewed-product<? echo $i; ?> input'), function(element){
				return $(element).attr('value');
			});
			
			$('input[name=\'blockviewed_product_<? echo $i; ?>\']').attr('value', data.join());	
		});
	//--></script> 
<? } ?>

<script type="text/javascript"><!--
	function image_upload(field, thumb) {
		$('#dialog').remove();
		
		$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
		
		$('#dialog').dialog({
			title: 'Выбери картинку',
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
			height: 600,
			resizable: false,
			modal: false
		});
	};
//--></script> 
	<script type="text/javascript"><!--
		var module_row = <?php echo $module_row; ?>;
		
		function addModule() {	
			html  = '<tbody id="module-row' + module_row + '">';
			html += '  <tr>';
			html += '    <td class="left"><input type="text" name="viewed_module[' + module_row + '][image_width]" value="80" size="3" /> <input type="text" name="viewed_module[' + module_row + '][image_height]" value="80" size="3" /></td>';	
			html += '    <td class="left"><select name="viewed_module[' + module_row + '][layout_id]">';
			<?php foreach ($layouts as $layout) { ?>
				html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
			<?php } ?>
			html += '    </select></td>';
			html += '    <td class="left"><select name="viewed_module[' + module_row + '][position]">';
			html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
			html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
			html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
			html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
			html += '    </select></td>';
			html += '    <td class="left"><select name="viewed_module[' + module_row + '][status]">';
			html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
			html += '      <option value="0"><?php echo $text_disabled; ?></option>';
			html += '    </select></td>';
			html += '    <td class="right"><input type="text" name="viewed_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
			html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
			html += '  </tr>';
			html += '</tbody>';
			
			$('#module tfoot').before(html);
			
			module_row++;
		}
	//--></script> 
<?php echo $footer; ?>