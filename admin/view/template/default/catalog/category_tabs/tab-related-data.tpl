		<div id="tab-related-data">
			<table class="form">						
				<tr>
					<td>Связанные категории<br /><span class='help'>
						Для автоподбора аксессуаров / связанных товаров, если они не заданы явно
					</span></td>
					<td><input type="text" name="related_category" value="" style="width:400px;" /><br /><br />
						<div id="related-category" class="scrollbox" style="min-height: 300px;">
							<?php $class = 'odd'; ?>
							<?php foreach ($related_categories as $related_category) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div id="related-category<?php echo $related_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $related_category['name']; ?><img src="view/image/delete.png" alt="" />
									<input type="hidden" name="related_category[]" value="<?php echo $related_category['category_id']; ?>" />
								</div>
							<?php } ?>
						</div>
					</td>
				</tr>
				<tr>
					<td>Характристики<br /><span class='help'>
						Если не пусто, подбор аксессуаров / дополнений происходит с учетом равенства значений этих характеристик
					</span></td>
					<td><div class="scrollbox" style="height:500px;">
						<?php $class = 'even'; ?>
						<?php foreach ($attributes as $attributes_to_cat) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (in_array($attributes_to_cat['attribute_id'], $attributes_similar_category)) { ?>
									<input id="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_similar_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" checked="checked" />
									<label for="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
								<?php } else { ?>
									<input id="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_similar_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" />
									<label for="attributes_similar_category<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>
			</tr>
			<tr>
				<td>Равные атрибуты<br /><span class='help'>
					Для подбора похожих товаров, либо замен из списка в админке (равна категория и равно значение этих атрибутов)
				</span></td>
				<td><div class="scrollbox" style="height:500px;">
					<?php $class = 'even'; ?>
					<?php foreach ($attributes as $attributes_to_cat) { ?>
						<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
						<div class="<?php echo $class; ?>">
							<?php if (in_array($attributes_to_cat['attribute_id'], $attributes_category)) { ?>
								<input id="attributes_<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" checked="checked" />
								<label for="attributes_<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
							<?php } else { ?>
								<input id="attributes_to_cat_<?php echo $attributes_to_cat['attribute_id']; ?>" class="checkbox" type="checkbox" name="attributes_category[]" value="<?php echo $attributes_to_cat['attribute_id']; ?>" />
								<label for="attributes_to_cat_<?php echo $attributes_to_cat['attribute_id']; ?>"><?php echo $attributes_to_cat['name']; ?></label>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
			</td>
		</tr>
	</table>
</div>
<script>
	$('input[name=\'related_category\']').autocomplete({
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
			$('#related-category' + ui.item.value).remove();
			
			$('#related-category').append('<div id="related-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="related_category[]" value="' + ui.item.value + '" /></div>');
			
			$('#related-category div:odd').attr('class', 'odd');
			$('#related-category div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#related-category div img').live('click', function() {
		$(this).parent().remove();
		
		$('#related-category div:odd').attr('class', 'odd');
		$('#related-category div:even').attr('class', 'even');	
	});
</script>