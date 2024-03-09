<div id="tab-design" style="display:none;">
	<table class="list">
		<thead>
			<tr>
				<td class="left"><?php echo $entry_store; ?></td>
				<td class="left"><?php echo $entry_layout; ?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="left"><?php echo $text_default; ?></td>
				<td class="left"><select name="product_layout[0][layout_id]">
					<option value=""></option>
					<?php foreach ($layouts as $layout) { ?>
						<?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
							<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select></td>
			</tr>
		</tbody>
		<?php foreach ($stores as $store) { ?>
			<tbody>
				<tr>
					<td class="left"><?php echo $store['name']; ?></td>
					<td class="left"><select name="product_layout[<?php echo $store['store_id']; ?>][layout_id]">
						<option value=""></option>
						<?php foreach ($layouts as $layout) { ?>
							<?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
								<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select></td>
				</tr>
			</tbody>
		<?php } ?>
	</table>
</div>