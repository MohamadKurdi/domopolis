
	<div id="tab-feature">
		<table id="feature" class="list">
			<thead>
				<tr>
					<td class="left">Особенность</td>
					<td class="left">Значение</td>
					<td></td>
				</tr>
			</thead>
			<?php $feature_row = 0; ?>
			<?php foreach ($product_features as $product_feature) { ?>
				<tbody id="feature-row<?php echo $feature_row; ?>">
					<tr>
						<td class="left" style="width:300px;">
							<input style="width:90%" type="text" name="product_feature[<?php echo $feature_row; ?>][name]" value="<?php echo $product_feature['name']; ?>" />
							<input type="hidden" name="product_feature[<?php echo $feature_row; ?>][feature_id]" value="<?php echo $product_feature['feature_id']; ?>" /></td>
							<td class="left"><?php foreach ($languages as $language) { ?>
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
								<textarea name="product_feature[<?php echo $feature_row; ?>][product_feature_description][<?php echo $language['language_id']; ?>][text]" rows="2" style="width:90%"><?php echo isset($product_feature['product_feature_description'][$language['language_id']]) ? $product_feature['product_feature_description'][$language['language_id']]['text'] : ''; ?></textarea>
								<br />
							<?php } ?>
						</td>
						<td class="left" style="width:100px;"><a onclick="$('#feature-row<?php echo $feature_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
					</tr>
				</tbody>
				<?php $feature_row++; ?>
			<?php } ?>
			<tfoot>
				<tr>
					<td colspan="2"></td>
					<td class="right"><a onclick="addFeature();" class="button">Добавить особенность</a></td>
				</tr>
			</tfoot>
		</table>
	</div>	

	<script type="text/javascript"><!--
	var feature_row = <?php echo $feature_row; ?>;
	
	function addFeature() {
		html  = '<tbody id="feature-row' + feature_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><input type="text" name="product_feature[' + feature_row + '][name]" value="" /><input type="hidden" name="product_feature[' + feature_row + '][feature_id]" value="" /></td>';
		html += '    <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<textarea name="product_feature[' + feature_row + '][product_feature_description][<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"></textarea><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /><br />';
		<?php } ?>
		html += '    </td>';
		html += '    <td class="right"><a onclick="$(\'#feature-row' + feature_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';	
		html += '</tbody>';
		
		$('#feature tfoot').before(html);
		
		featureautocomplete(feature_row);
		
		feature_row++;
	}
	
	function featureautocomplete(feature_row) {
		$('input[name=\'product_feature[' + feature_row + '][name]\']').autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {	
						response($.map(json, function(item) {
							return {
								category: item.attribute_group,
								label: item.name,
								value: item.attribute_id
							}
						}));
					}
				});
			}, 
			select: function(event, ui) {
				$('input[name=\'product_feature[' + feature_row + '][name]\']').attr('value', ui.item.label);
				$('input[name=\'product_feature[' + feature_row + '][feature_id]\']').attr('value', ui.item.value);
				
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	}
	
	$('#feature tbody').each(function(index, element) {
		featureautocomplete(index);
	});
//--></script> 