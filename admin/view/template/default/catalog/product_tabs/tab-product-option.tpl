	<div id="tab-product-option">
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