	<div id="tab-additional-offer">
		<table id="additional-offer" class="list">
			<thead>
				<tr>
					<td class="left">Группа</td>										
					<td class="right">Магазины</td>
					<td class="right">Приоритет</td>
					<td class="right">Кол-во</td>
					<td class="right"><?php echo $entry_name; ?></td>
					<td class="right">Скидка</td>
					<td class="left">Даты</td>
					<td class="left" colspan="2">Картинка</td>
				</tr>
			</thead>
			<?php $additional_offer_row = 0; ?>
			<?php foreach ($product_additional_offer as $product_ao) { ?>
				<tbody id="additional-offer-row<?php echo $additional_offer_row; ?>">
					<tr>
						<td class="left">
							<input type="hidden" name="product_additional_offer[<?php echo $additional_offer_row; ?>][product_additional_offer_id]" value="<?php echo $product_ao['product_additional_offer_id']; ?>" />
							<select name="product_additional_offer[<?php echo $additional_offer_row; ?>][customer_group_id]" style="width:150px;">
								<?php foreach ($customer_groups as $customer_group) { ?>
									<?php if ($customer_group['customer_group_id'] == $product_ao['customer_group_id']) { ?>
										<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<br /><br />
							<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][ao_group]" value="<?php echo $product_ao['ao_group']; ?>" size="20" />

						</td>
						<td>
							<div class="scrollbox" style="min-height: 100px;">
								<?php $class = 'even'; ?>												
								<?php foreach ($stores as $store) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($store['store_id'], $product_ao['store_id'])) { ?>
											<input id="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="product_additional_offer[<?php echo $additional_offer_row; ?>][store_id][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
											<label for="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
										<?php } else { ?>
											<input id="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="product_additional_offer[<?php echo $additional_offer_row; ?>][store_id][]" value="<?php echo $store['store_id']; ?>" />
											<label for="ao_<?php echo $additional_offer_row; ?>_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
										<?php } ?>
									</div>
								<?php } ?>

							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Все</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять</a>
						</td>


						<td class="right">
							<input type="number" step="1" name="product_additional_offer[<?php echo $additional_offer_row; ?>][priority]" value="<?php echo $product_ao['priority']; ?>" size="2" style="width:50px;" />
						</td>
						<td class="right">
							<input type="number" step="1" name="product_additional_offer[<?php echo $additional_offer_row; ?>][quantity]" value="<?php echo $product_ao['quantity']; ?>" size="2" style="width:50px;" />
						</td>

						<td class="right" width="350px;">
							<input type="text" value="<?php echo $product_ao['product_ao_name']; ?>" size="30" class="ao_product_name" data-row="<?php echo $additional_offer_row; ?>" name="aop<?php echo $additional_offer_row; ?>" style="width:340px;" />
							<input type="hidden" value="<?php echo $product_ao['ao_product_id']; ?>" name="product_additional_offer[<?php echo $additional_offer_row; ?>][ao_product_id]" value="" />
						</td>

						<td class="right" width="150px;">
							<div>
								<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][price]" value="<?php echo $product_ao['price']; ?>" /><br />
								<small>фикс. цена</small>
							</div>

							<div style="margin-top:5px;">
								<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][percent]" value="<?php echo $product_ao['percent']; ?>" /><br />
								<small>процент</small>
							</div>
						</td>
						<td class="right" width="150px;">
							<div>
								<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][date_start]" value="<?php echo $product_ao['date_start']; ?>" class="date" />
								<br />
								<small>действует от</small>
							</div>

							<div style="margin-top:5px;">
								<input type="text" name="product_additional_offer[<?php echo $additional_offer_row; ?>][date_end]" value="<?php echo $product_ao['date_end']; ?>" class="date" /><br />
								<small>действует до</small>
							</div>
						</td>
						<td class="left">
							<div class="image"><img src="<?php echo $product_ao['thumb']; ?>" alt="" id="thumb<?php echo $additional_offer_row; ?>" />
								<input type="hidden" name="product_additional_offer[<?php echo $additional_offer_row; ?>][image]" value="<?php echo $product_ao['image']; ?>" id="image<?php echo $additional_offer_row; ?>" />
								<br />

								<a class="remove_selection" onclick="image_upload('image<?php echo $additional_offer_row; ?>', 'thumb<?php echo $additional_offer_row; ?>');">Добавить</a>
								<a class="select_all" onclick="$('#thumb<?php echo $additional_offer_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $additional_offer_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div>
							</td>


							<td class="left">
								<a class="remove_selection" onclick="$('#additional-offer-row<?php echo $additional_offer_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a>
							</td>
						</tr>

						<tr style="display:none;">
							<td class="left" colspan="8">												
								<textarea style="width:90%;" id="ao-description<?php echo $additional_offer_row; ?>"  name="product_additional_offer[<?php echo $additional_offer_row; ?>][description]"><?php echo $product_ao['description']; ?></textarea>
							</td>
						</tr>
					</tbody>
					<?php $additional_offer_row++; ?>
				<?php } ?>
				<tfoot>
					<tr>		
						<td class="right" colspan="10"><a onclick="addAdditionalOffer();" class="button"><?php echo $button_add_ao; ?></a></td>
					</tr>
				</tfoot>
			</table>
		</div>																						
		<script type="text/javascript">
			var additional_offer_row = <?php echo $additional_offer_row; ?>;
			
			function addAdditionalOffer() {
				html = '<tbody id="additional-offer-row' + additional_offer_row + '">';

				html += '  <tr>';

				html += '    <td class="left">';
				html += '      <input type="hidden" name="product_additional_offer[' + additional_offer_row + '][product_additional_offer_id]" value="" />';
				html += '      <select name="product_additional_offer[' + additional_offer_row + '][customer_group_id]" style="width:150px;">';

				<?php foreach ($customer_groups as $customer_group) { ?>
					html += '        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
				<?php } ?>

				html += '      </select>';
				html += '      <br /><br />';
				html += '      <input type="text" name="product_additional_offer[' + additional_offer_row + '][ao_group]" value="" size="20" />';

				html += '    </td>';
				
				html += '    <td>';
				html += '      <div class="scrollbox" style="min-height: 100px;">';

				<?php $class = 'even'; ?>
				<?php foreach ($stores as $store) { ?>

					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>

					html += '        <div class="<?php echo $class; ?>">';
					
					html += '          <input id="ao_' + additional_offer_row + '_store_' + <?php echo $store['store_id']; ?> + '" class="checkbox" type="checkbox" name="product_additional_offer[' + additional_offer_row + '][store_id][]" value="<?php echo $store['store_id']; ?>" />';

					html += '          <label for="ao_' + additional_offer_row + '_store_' + <?php echo $store['store_id']; ?> + '"><?php echo $store['name']; ?></label>';

					html += '        </div>';

				<?php } ?>

				html += '      </div>';  
				html += '      <a class="select_all" onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);">Все</a><a class="remove_selection" onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);">Снять</a>';
				html += '    </td>';

				html += '    <td class="right">';
				html += '      <input type="number" step="1" name="product_additional_offer[' + additional_offer_row + '][priority]" value="" size="2" style="width:50px;" />';
				html += '    </td>';

				html += '    <td class="right">';
				html += '      <input type="number" step="1" name="product_additional_offer[' + additional_offer_row + '][quantity]" value="1" size="2" style="width:50px;" />';
				html += '    </td>';

				html += '    <td class="right" width="350px;">';
				html += '      <input type="text" value="" size="30" class="ao_product_name" data-row="' + additional_offer_row + '" name="aop' + additional_offer_row + '" style="width:340px;" />';
				html += '      <input type="hidden" value="" name="product_additional_offer[' + additional_offer_row + '][ao_product_id]" />';
				html += '    </td>';

				html += '    <td class="right" width="150px;">';
				html += '      <div>';
				html += '        <input type="text" name="product_additional_offer[' + additional_offer_row + '][price]" value="" /><br />'; 
				html += '        <small>фикс. цена</small>';
				html += '      </div>';
				
				html += '      <div style="margin-top:5px;">';
				html += '        <input type="text" name="product_additional_offer[' + additional_offer_row + '][percent]" value="" /><br />';
				html += '        <small>процент</small>';
				html += '      </div>';
				html += '    </td>';

				html += '    <td class="right" width="150px;">';
				html += '      <div>';
				html += '        <input type="text" name="product_additional_offer[' + additional_offer_row + '][date_start]" value="" class="date" />';
				html += '        <br />';
				html += '        <small>действует от</small>';
				html += '      </div>';

				html += '      <div style="margin-top:5px;">';
				html += '        <input type="text" name="product_additional_offer[' + additional_offer_row + '][date_end]" value="" class="date" /><br />';
				html += '        <small>действует до</small>';
				html += '      </div>';
				html += '    </td>';

				html += '    <td class="left">';
				html += '      <div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + additional_offer_row + '" /><input type="hidden" name="product_additional_offer[' + additional_offer_row + '][image]" value="" id="image' + additional_offer_row + '" /><br />';
				html += '        <a class="remove_selection" onclick="image_upload(\'image' + additional_offer_row + '\', \'thumb' + additional_offer_row + '\');">Добавить</a>';  
				html += '        <a class="select_all" onclick="$(\'#thumb' + additional_offer_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + additional_offer_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div>';
				html += '      </td>';

				html += '    <td class="left">';
				html += '      <a class="remove_selection" onclick="$(\'#additional-offer-row' + additional_offer_row + '\').remove();" class="button"><?php echo $button_remove; ?></a>';
				html += '    </td>';

				html += '  </tr>';
				
				html += '<tr style="display:none;">';
				html += '  <td class="left" colspan="8">';                
				html += '    <textarea style="width:90%;" id="ao-description' + additional_offer_row + '" name="product_additional_offer[' + additional_offer_row + '][description]"></textarea>';
				html += '  </td>';
				html += '</tr>';

				html += '</tbody>';

				$('#additional-offer tfoot').before(html);

				$('#additional-offer-row' + additional_offer_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

				CKEDITOR.replace('ao-description' + additional_offer_row, {
					filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
				});

				ao_autocomplete(additional_offer_row);

				additional_offer_row++;
			}
			
			function ao_autocomplete(additional_offer_row){
				$('input[name=\'aop' + additional_offer_row + '\']').autocomplete({
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
						$(this).val(ui.item.label);
						$('input[name=\'product_additional_offer[' + additional_offer_row + '][ao_product_id]\']').val(ui.item.value);
						
						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
				
			}
			
			$('.ao_product_name').each(function(index, element) {
				ao_autocomplete($(this).attr('data-row'));
			});
		</script> 
