	<div id="tab-markdown">
		<table id="table-markdown" class="form">
			<tr>						
				<td>Уцененный товар</td>
				<td><select name="is_markdown">
					<?php if ($is_markdown) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>						
			<td>Родительский товар</td>
			<td>
				<input id="markdown_product" value="<?php echo $markdown_product; ?>" name="markdown_product" type="text" style="width:400px;" /> <span style="border-bottom:1px dashed black;" onclick="$('#markdown_product').val(''); $('#markdown_product_id').val('');">очистить</span>
				<br />
				<span class="help">автоподбор</span>
				<input  id="markdown_product_id" name="markdown_product_id" value="<?php echo $markdown_product_id; ?>" type="hidden" />
			</td>
		</tr>
	</table>

	<div id="tab-markdown-descriptions">
		<div id="markdown-languages" class="htabs">
			<?php foreach ($languages as $language) { ?>
				<a href="#markdown-language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
			<?php } ?>
			<div class="clr"></div>
		</div>

		<?php foreach ($languages as $language) { ?>
			<div id="markdown-language<?php echo $language['language_id']; ?>">
				<table class="form">
					<tr>
						<td>Уценка: внешний вид</td>
						<td>
							<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_appearance]" id="markdown_appearance<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_appearance'] : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Уценка: состояние</td>
						<td>
							<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_condition]" id="markdown_condition<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_condition'] : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Уценка: упаковка</td>
						<td>
							<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_pack]" id="markdown_pack<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_pack'] : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Уценка: комплектация</td>
						<td>
							<textarea name="product_description[<?php echo $language['language_id']; ?>][markdown_equipment]" id="markdown_equipment<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['markdown_equipment'] : ''; ?></textarea>
						</td>
					</tr>
				</table>
			</div>
		<?php } ?>
	</div>

</div>
<script>
		$('input[name=\'markdown_product\']').autocomplete({
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
			$('input[name=\'markdown_product_id\']').val(ui.item.value);
			$('input[name=\'markdown_product\']').val(ui.item.label);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
</script>