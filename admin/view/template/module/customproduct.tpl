<?php echo $header; ?>
<style>
	.scrollbox{
	min-height: 25px;
	max-height: 100px;
	overflow-y: auto;
	height: auto;
	width: 300px;
	}
</style>
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
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table id="module" class="list">
					<thead>
						<tr>
							<td class="left" style="width: 130px;"><?php echo $entry_name; ?></td>
							<td class="left" style="width: 200px;">Текст</td>
							<td class="left" style="width: 200px;">Линк</td>
							<td class="left"><?php echo $entry_product; ?></td>           
							<td class="left">Картинки</td>          
							<td class="left">Схема / позиция / кат.</td> 			            
						</tr>
					</thead>
					<?php $module_row = 0; ?>
					<?php foreach ($modules as $module) { ?>
						<tbody id="module-row<?php echo $module_row; ?>">
							<tr style="border-bottom:2px solid blue;">
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<input type="text" name="customproduct_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][name]" value="<?php echo $module[$language['language_id']]['name']; ?>" style='width: 130px;' /><br>
									<?php } ?>
								</td>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<textarea name="customproduct_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][description]" style='width: 150px; height:30px;'><?php echo isset($module[$language['language_id']]['description'])?trim($module[$language['language_id']]['description']):''; ?></textarea><br />
									<?php } ?>
								</td>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<textarea name="customproduct_module[<?php echo $module_row; ?>][<?php echo $language['language_id']; ?>][href]" style='width: 150px; height:30px;'><?php echo isset($module[$language['language_id']]['href'])?trim($module[$language['language_id']]['href']):''; ?></textarea><br />
									<?php } ?>
								</td>
								<td class="left">
									<input type="text" id="product_<?php echo $module_row; ?>" value="" style="width: 294px;" placeholder="<?php echo $text_product_placeholder; ?>" />
									<br><div class="scrollbox" id="hit-product_<?php echo $module_row; ?>" style="height:200px;max-height:200px;">
										<?php $class = 'odd'; ?>
										<?php foreach ($module['product_infos'] as $product) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div id="hit-product_<?php echo $module_row; ?><?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"><?php echo $product['name']; ?> <img src="view/image/delete.png" />
												<input type="hidden" value="<?php echo $product['product_id']; ?>" />
											</div>
										<?php } ?>
									</div>
									<input type="hidden" id='hit_product_i_<?php echo $module_row; ?>' name="customproduct_module[<?php echo $module_row; ?>][products]" value="<?php echo $module['products']; ?>" />
									<script type="text/javascript"><!--
										$('#product_<?php echo $module_row; ?>').autocomplete({
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
												$('#hit-product_<?php echo $module_row; ?>_'+ ui.item.value).remove();
												$('#hit-product_<?php echo $module_row; ?>').append('<div id="hit-product_<?php echo $module_row; ?>_'+ ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" value="' + ui.item.value + '" /></div>');
												$('#hit-product_<?php echo $module_row; ?> div:odd').attr('class', 'odd');
												$('#hit-product_<?php echo $module_row; ?> div:even').attr('class', 'even');
												
												data = $.map($('#hit-product_<?php echo $module_row; ?> input'), function(element){
													return $(element).attr('value');
												});
												
												$('#hit_product_i_<?php echo $module_row; ?>').attr('value', data.join());
												
												return false;
											}
										});
										
										$('#hit-product_<?php echo $module_row; ?> div img').live('click', function() {
											$(this).parent().remove();
											
											$('#hit-product_<?php echo $module_row; ?> div:odd').attr('class', 'odd');
											$('#hit-product_<?php echo $module_row; ?> div:even').attr('class', 'even');
											
											data = $.map($('#hit-product_<?php echo $module_row; ?> input'), function(element){
												return $(element).attr('value');
											});
											
											$('#hit_product_i_<?php echo $module_row; ?>').attr('value', data.join());    
										});
									//--></script>
									<br />
									Случайно
									<input type="text" name="customproduct_module[<?php echo $module_row; ?>][random_limit]" value="<?php echo isset($module['random_limit'])?$module['random_limit']:5; ?>" size="3" />
									из
									<select name="customproduct_module[<?php echo $module_row; ?>][random]">
										<?php if (!isset($module['random']) || !$module['random']) { ?>
											<option value="0" selected="selected">Не брать</option>
										<? } else { ?>
											<option value="0">Не брать</option>
										<? } ?>
										<?php if ($module['random'] == 'specials') { ?>
											<option value="specials" selected="selected">со скидками</option>
											<?php } else { ?>
											<option value="specials">со скидками</option>
										<?php } ?>
										<?php if ($module['random'] == 'list') { ?>
											<option value="list" selected="selected">со списка</option>
											<?php } else { ?>
											<option value="list">со списка</option>
										<?php } ?>	
									</select>
								</td> 
								<td class="left">
									Размер картинки товара (Ш*В):<input type="text" name="customproduct_module[<?php echo $module_row; ?>][image_width]" value="<?php echo $module['image_width']; ?>" size="3" />
									<input type="text" name="customproduct_module[<?php echo $module_row; ?>][image_height]" value="<?php echo $module['image_height']; ?>" size="3" />
									<?php if (isset($error_image[$module_row])) { ?>
										<span class="error"><?php echo $error_image[$module_row]; ?></span>
									<?php } ?><br /><br />
									
									
									<table style="width:100%">
										<tr>
											<td>
												Размер 1 (Ш*В):
												<input type="text" name="customproduct_module[<?php echo $module_row; ?>][big_image_width]" value="<?php echo $module['big_image_width']; ?>" size="3" />
												<input type="text" name="customproduct_module[<?php echo $module_row; ?>][big_image_height]" value="<?php echo $module['big_image_height']; ?>" size="3" /><br />
											</td>
											<td>
												Размер 2 (Ш*В):
												<input type="text" name="customproduct_module[<?php echo $module_row; ?>][big_image2_width]" value="<?php echo $module['big_image2_width']; ?>" size="3" />
												<input type="text" name="customproduct_module[<?php echo $module_row; ?>][big_image2_height]" value="<?php echo $module['big_image2_height']; ?>" size="3" /><br />
											</td>
										</tr>
										<tr>
											<td>
												<img src="<?php echo $module['thumb']; ?>" alt="" id="customproduct_module_<?php echo $module_row; ?>_thumb" />			  
												<input type="hidden" id="customproduct_module_<?php echo $module_row; ?>_image" name="customproduct_module[<?php echo $module_row; ?>][image]" value="<?php echo isset($module['image']) ? $module['image'] : ''; ?>" /><br />			  
												<a onclick="image_upload('customproduct_module_<?php echo $module_row; ?>_image', 'customproduct_module_<?php echo $module_row; ?>_thumb');">Выбр.</a>&nbsp;|&nbsp;
												<a onclick="$('#customproduct_module_<?php echo $module_row; ?>_thumb').attr('src', '<?php echo $no_image; ?>'); $('#customproduct_module_<?php echo $module_row; ?>_image').attr('value', '');">Очист.</a>
											</td>
											<td>
												<img src="<?php echo $module['thumb2']; ?>" alt="" id="customproduct_module_<?php echo $module_row; ?>_thumb2" />			  
												<input type="hidden" id="customproduct_module_<?php echo $module_row; ?>_image2" name="customproduct_module[<?php echo $module_row; ?>][image2]" value="<?php echo isset($module['image2']) ? $module['image2'] : ''; ?>" /><br />			  
												<a onclick="image_upload('customproduct_module_<?php echo $module_row; ?>_image2', 'customproduct_module_<?php echo $module_row; ?>_thumb2');">Выбр.</a>&nbsp;|&nbsp;
												<a onclick="$('#customproduct_module_<?php echo $module_row; ?>_thumb2').attr('src', '<?php echo $no_image; ?>'); $('#customproduct_module_<?php echo $module_row; ?>_image2').attr('value', '');">Очист.</a>
											</td>
										</tr>				
									</table>
									
									
									
									<br />
									
								</td>     
								
								<td class="left"><select name="customproduct_module[<?php echo $module_row; ?>][layout_id]">
									<?php foreach ($layouts as $layout) { ?>
										<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
											<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								<br /><br />
								<select name="customproduct_module[<?php echo $module_row; ?>][position]">
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
								</select><br /><br />
								<select name="customproduct_module[<?php echo $module_row; ?>][category]">
									<option value="0"><?php echo $text_all; ?></option>
									<?php foreach($category_array['0'] as $category){ ?>
										<option value="<?php echo $category['category_id']; ?>" <?php if ($module['category']==$category['category_id']) { ?>selected="selected"<?php } ?>><?php echo $category['name']; ?></option>
									<?php } ?>
								</select><br /><br />
								
								<select name="customproduct_module[<?php echo $module_row; ?>][status]">
									<?php if ($module['status']) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select><br /><br />
								
								<input type="text" name="customproduct_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /><br /><br />
								<input type="text" name="customproduct_module[<?php echo $module_row; ?>][module_group]" value="<?php echo isset($module['module_group'])?$module['module_group']:''; ?>" size="10" style="margin-bottom:5px;" /> / Группа модулей<br />
								<input type="text" name="customproduct_module[<?php echo $module_row; ?>][custom_group]" value="<?php echo isset($module['custom_group'])?$module['custom_group']:''; ?>" size="10" /> / Группа блоков
								</td>             
								<td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
							</tr>
						</tbody>
						<?php $module_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="7">
								
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
		<div id='module_copyright'>
			<div style="text-align: center;"><?php echo $text_copyright; ?></div>			
		</div>
	</div>
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
				height: 400,
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
			html += '    <td class="left">';
			<?php foreach ($languages as $language) { ?>
				html += '      <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
				html += '      <input type="text" name="customproduct_module[' + module_row + '][<?php echo $language['language_id']; ?>][name]" value="" size="15" style="width: 141px;" /><br>';
			<?php } ?>
			html += '    </td>';
			
			html += '    <td class="left">';
			html += '<input type="text" id="product_' + module_row + '" value="" style="width: 294px;" placeholder="<?php echo $text_product_placeholder; ?>" /><br>';
			html += '<div class="scrollbox" id="hit-product_' + module_row + '">';
			html += '</div>';
			html += '<input type="hidden" id="hit_product_i_' + module_row + '" name="customproduct_module[' + module_row + '][products]" value="" /></td>';
			html += '    </td>';
			html += '    <td class="left"><input type="text" name="customproduct_module[' + module_row + '][image_width]" value="80" size="3" /> <input type="text" name="customproduct_module[' + module_row + '][image_height]" value="80" size="3" /></td>';    
			html += '    <td class="left"><select name="customproduct_module[' + module_row + '][layout_id]">';
			<?php foreach ($layouts as $layout) { ?>
				html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
			<?php } ?>
			html += '    </select></td>';
			html += '    <td class="left"><select name="customproduct_module[' + module_row + '][position]">';
			html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
			html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
			html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
			html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
			html += '    </select></td>';
			html += '    <td class="left"><select name="customproduct_module[' + module_row + '][category]">';
			html += '      <option value="0"><?php echo $text_all; ?></option>';
			<?php foreach($category_array['0'] as $category){ ?>
				html += '      <option value="<?php echo $category['category_id']; ?>""><?php echo $category['name']; ?></option>';
			<?php } ?>
			html += '    </select></td>';
			html += '    <td class="left"><select name="customproduct_module[' + module_row + '][status]">';
			html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
			html += '      <option value="0"><?php echo $text_disabled; ?></option>';
			html += '    </select></td>';
			html += '    <td class="right"><input type="text" name="customproduct_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
			html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
			html += '  </tr>';
			html += '</tbody>';
			
			$('#module tfoot').before(html);
			
			var module_row_=module_row;
			
			$('#product_'+module_row_).autocomplete({
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
					$('#hit-product_'+module_row_+'_'+ ui.item.value).remove();
					$('#hit-product_'+module_row_).append('<div id="hit-product_'+module_row_+'_'+ ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" value="' + ui.item.value + '" /></div>');
					$('#hit-product_'+module_row_+' div:odd').attr('class', 'odd');
					$('#hit-product_'+module_row_+' div:even').attr('class', 'even');
					
					data = $.map($('#hit-product_'+module_row_+' input'), function(element){
						return $(element).attr('value');
					});
					
					$('#hit_product_i_'+module_row_).attr('value', data.join());
                    
					return false;
				}
			});
			
			$('#hit-product_'+module_row_+' div img').live('click', function() {
				$(this).parent().remove();
				
				$('#hit-product_'+module_row_+' div:odd').attr('class', 'odd');
				$('#hit-product_'+module_row_+' div:even').attr('class', 'even');
				
				data = $.map($('#hit-product_'+module_row_+' input'), function(element){
					return $(element).attr('value');
				});
				
				$('#hit_product_i_'+module_row_).attr('value', data.join());    
			});
			
			module_row++;
		}
	//--></script> 
	
<?php echo $footer; ?>