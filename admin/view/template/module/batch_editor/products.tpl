<?php if ($products) { ?>
	<div class="pagination"><?php echo $pagination; ?></div><br /><br />
	<table id="product" class="list">
		<thead>
			<tr class="sort">
				<td class="center" width="1"><input type="checkbox" onclick="selected_row_all(this);" /></td>
				<td class="left" colspan="3"></td>
				<?php foreach ($setting['fields'] as $name=>$field) { ?>
					<?php if ($name == 'image' || $name == 'points') { ?>
						<td class="left" colspan="2" width="1%">
							<?php } else { ?>
							<td class="left">
							<?php } ?>
							<?php if (${'sort_' . $name}) { ?>
								<a href="<?php echo ${'sort_' . $name}; ?>" class="<?php echo ($sort == ${'sort_' . $name}) ? strtolower($order) : ''; ?>"><?php echo ${'column_' . $name}; ?></a>
								<?php } else { ?>
								<?php echo ${'column_' . $name}; ?>
							<?php } ?>
						</td>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $product) { ?>
					<?php $class = ($product['selected']) ? 'selected' : ''; ?>
					<tr class="selected_row-<?php echo $product['product_id']; ?> <?php echo $class; ?>">
						<td class="center">
							<?php if ($product['selected']) { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
							<?php } ?>
						</td>
						<td class="left" width="1"><a class="related" onclick="editProducts('related_to', <?php echo $product['product_id']; ?>)" title="<?php echo $text_related; ?>"></a></td>
						<td class="left" width="1"><a class="link" target="_blank" href="<?php echo HTTP_CATALOG; ?>index.php?route=product/product&product_id=<?php echo $product['product_id']; ?>" title="<?php echo $text_view; ?>"></a></td>
						<td class="left" width="1"><a class="edit" target="_blank" href="index.php?route=catalog/product/update&token=<?php echo $token; ?>&product_id=<?php echo $product['product_id']; ?>" title="<?php echo $text_edit; ?>"></a></td>
						<?php foreach ($setting['fields'] as $name=>$field) { ?>
							<?php if ($name == 'image') { ?>
								<td class="center">
									<div class="dd_menu" id="product_images_<?php echo $product['product_id']; ?>">
										<div class="dd_menu_title" onclick="toggle_ajax('images', <?php echo $product['product_id']; ?>);"><?php echo ($setting['counter']) ? '(' . $product['images'] . ')' : ''; ?></div>
										<div class="dd_menu_container"></div>
									</div>
								</td>
								<td class="center">
									<div class="image_edit">
										<?php if ($product['image']) { ?>
											<div class="image_remove" id="image_remove-<?php echo $product['product_id']; ?>" title="Удалить"></div>
										<?php } ?>
										<img id="thumb-<?php echo $product['product_id']; ?>" src="<?php echo $product['thumb']; ?>" alt="" title="Редактировать" /><input type="hidden" id="image-<?php echo $product['product_id']; ?>" value="<?php echo $product['image']; ?>" /><a id="image_manager-<?php echo $product['product_id']; ?>" onclick="getImageManager('image_manager', <?php echo $product['product_id']; ?>, 0)"><?php echo $text_path; ?></a>
									</div>
								</td>
								<?php } else if ($name == 'points') { ?>
								<td class="center">
									<div class="dd_menu" id="product_rewards_<?php echo $product['product_id']; ?>">
										<div class="dd_menu_title" onclick="toggle_ajax('rewards', <?php echo $product['product_id']; ?>);"></div>
										<div class="dd_menu_container"></div>
									</div>
								</td>
								<td class="left"><span class="input-points-<?php echo $product['product_id']; ?>"><?php echo $product['points']; ?></span></td>
								<?php } else if ($name == 'name') { ?>
								<td class="left product_name">
									<span class="input-name-<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></span>
									<div>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'descriptions')"><?php echo $column_descriptions; ?></a>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'categories')"><?php echo $column_categories; ?><?php echo ($setting['counter']) ? '(' . $product['categories'] . ')' : ''; ?></a>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'attributes')"><?php echo $column_attributes; ?><?php echo ($setting['counter']) ? '(' . $product['attributes'] . ')' : ''; ?></a>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'options')"><?php echo $column_options; ?><?php echo ($setting['counter']) ? '(' . $product['options'] . ')' : ''; ?></a>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'specials')"><?php echo $column_specials; ?><?php echo ($setting['counter']) ? '(' . $product['specials'] . ')' : ''; ?></a>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'discounts')"><?php echo $column_discounts; ?><?php echo ($setting['counter']) ? '(' . $product['discounts'] . ')' : ''; ?></a>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'related')"><?php echo $column_related; ?><?php echo ($setting['counter']) ? '(' . $product['related'] . ')' : ''; ?></a>
										<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'stores')"><?php echo $column_stores; ?><?php echo ($setting['counter']) ? '(' . $product['stores'] . ')' : ''; ?></a>
									<? /*	<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'downloads')"><?php echo $column_downloads; ?><?php echo ($setting['counter']) ? '(' . $product['downloads'] . ')' : ''; ?></a>
									*/ ?>
									
									<? /*	<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'layouts')"><?php echo $column_design; ?><?php echo ($setting['counter']) ? '(' . $product['layouts'] . ')' : ''; ?></a>
									*/ ?>
									
									<? /*	<?php if (VERSION >= '1.5.5') { ?>
											<a class="button" style="margin-top: 2px;" onclick="getProductList(<?php echo $product['product_id']; ?>, 'filters')"><?php echo $column_filters; ?><?php echo ($setting['counter']) ? '(' . $product['filters'] . ')' : ''; ?></a>
										<?php } ?>
									*/ ?>
									</div>
								</td>
								<?php } else { ?>
								<?php $class = (!$product[$name]) ? 'attention' : ''; ?>
								<?php if (!isset ($field['link']) || in_array ($name, $product_description)) { ?>
									<?php $type = 'input'; ?>
									<?php if ($name == 'quantity') { ?>
										<?php $class = (0 < $product['quantity']) ? 'quantity' : 'quantity_0'; ?>
									<?php } ?>
									<?php } else { ?>
									<?php $type = 'select'; ?>
								<?php } ?>
								<?php if (preg_match ('/^tinyint/', $field['type'])) { ?>
									<?php $type = 'select'; ?>
									<?php if ($name == 'status') { ?>
										<?php $class = ($product['status']) ? 'enabled' : 'disabled'; ?>
										<?php $product['status'] = ($product['status']) ? $text_enabled : $text_disabled; ?>
										<?php } else { ?>
										<?php $product[$name] = ($product[$name]) ? $text_yes : $text_no; ?>
									<?php } ?>
								<?php } ?>
								<td class="left <?php echo $class; ?> td_<?php echo $name; ?><?php echo $product['product_id']; ?>" width="1%"><span class="<?php echo $type; ?>-<?php echo $name; ?>-<?php echo $product['product_id']; ?>"><?php echo $product[$name]; ?></span></td>
							<?php } ?>
						<?php } ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $pagination; ?></div>
		<?php } else { ?>
		<div align="center" class="attention"><?php echo $text_no_results; ?></div>
	<?php } ?>
	<script type="text/javascript"><!--
		$('#product_container .dd_menu .dd_menu_container').click(function (event) {
			event.stopPropagation();
		});
	//--></script>									