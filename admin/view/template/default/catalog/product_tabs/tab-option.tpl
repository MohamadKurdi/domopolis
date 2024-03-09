	<div id="tab-option">
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
