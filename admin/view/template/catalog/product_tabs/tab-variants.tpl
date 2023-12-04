<div id="tab-variants">
	<table class="form">
		<tr>
			<td style="width:50%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Основной товар варианта</span>
			</td>
			<td style="width:50%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">В любом случае отобразить на фронте</span>
			</td>																	
		</tr>
		<tr>
			<td>
				<?php if ($other_variant_products && !$main_variant_id) { ?>

					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-info-circle"></i> Текущий товар - основной</span>

				<?php } else { ?>

					<input id="main_variant_product" value="<?php echo $main_variant_product; ?>" name="main_variant_product" type="text" style="width:80%;" /> 
					<span style="border-bottom:1px dashed black;" onclick="$('#main_variant_product').val(''); $('#main_variant_id').val('');">очистить</span>
					<br />
					<span class="help">автоподбор</span>
					
				<? } ?>
				<input  id="main_variant_id" name="main_variant_id" value="<?php echo $main_variant_id; ?>" type="hidden" />
			</td>
			<td>
				<select name="display_in_catalog">
					<?php if ($display_in_catalog) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
				<br />
				<span class="help">Эта карта товара будет отображена на фронте в любом случае, даже если является вариантом другого товара</span>
			</td>																	
		</tr>																	
	</table>
	<table class="form">
		<tr>
			<td style="width:33%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Полное название варианта</span>
			</td>
			<td style="width:33%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Характеристика - 1</span>
			</td>	
			<td style="width:33%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Характеристика - 2</span>
			</td>																
		</tr>
		<tr>
			<td style="width:33%" valign="top">
				<?php foreach ($languages as $language) { ?>
					<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_name'] : ''; ?>" style="width: 90%" />
					<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
				<?php } ?>
			</td>
			<td style="width:33%">
				<?php foreach ($languages as $language) { ?>
					<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_name_1]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_name_1'] : ''; ?>" style="width: 40%" /> 
					<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
					<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_value_1]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_value_1'] : ''; ?>" style="width: 40%" />

					<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
				<?php } ?>

				<br />		
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Характеристика - 1 определяет цвет</span></p>
				<select name="variant_1_is_color">
					<?php if ($variant_1_is_color) { ?>
						<option value="1" selected="selected"><?php echo $text_yes; ?></option>
						<option value="0"><?php echo $text_no; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_yes; ?></option>
						<option value="0" selected="selected"><?php echo $text_no; ?></option>
					<?php } ?>
				</select>

			</td>	
			<td style="width:33%">
				<?php foreach ($languages as $language) { ?>
					<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_name_2]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_name_2'] : ''; ?>" style="width: 40%" />

					<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
					<input type="text" name="product_description[<?php echo $language['language_id']; ?>][variant_value_2]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['variant_value_2'] : ''; ?>" style="width: 40%" />

					<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
				<?php } ?>

				<br />
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Группа - 2 определяет цвет</span></p>
				<select name="variant_2_is_color">
					<?php if ($variant_2_is_color) { ?>
						<option value="1" selected="selected"><?php echo $text_yes; ?></option>
						<option value="0"><?php echo $text_no; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_yes; ?></option>
						<option value="0" selected="selected"><?php echo $text_no; ?></option>
					<?php } ?>
				</select>

			</td>																
		</tr>
	</table>

	<?php if ($other_variant_products) { ?>	

		<?php if (!$main_variant_id) { ?>	
			<div style="clear:both; height:10px;"></div>																
			<div style="float:left; width:90%;">
				<?php foreach ($languages as $language) { ?>
					<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
						<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
						<textarea id="main_variant_name_editor_<?php echo $language['language_id']; ?>" style="width:90%"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?></textarea>
					<? } ?>
				<?php } ?>

				<?php if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']) { ?>
					<script>
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>

								$('#main_variant_name_editor_<?php echo $language['language_id']; ?>').on('focusout, change', function(){
									let value = $(this).val();
									let main_textarea = $(this);

									$.ajax({
										url : 'index.php?route=catalog/product/parsevariantnames&token=<?php echo $token; ?>',
										data: {
											product_id: <?php echo $product_id; ?>,
											name: value,
											language_id: '<?php echo $language['language_id']; ?>'
										},
										type: 'POST',
										dataType: 'json',
										beforeSend: function(){
											$('#variants-editor-status').html('<i class="fa fa-spinner fa-spin"></i>');
										},
										success: function (json){
											$('#variants-editor-status').html('<i class="fa fa-check"></i>');
											main_textarea.val(json.main_name);

											$.each(json.variants, function(i, item) {
												$('#variant_name_'+item.product_id+'_'+<?php echo $language['language_id']; ?>).val(item.name);
											});

										}
									});


								});

							<?php } ?>
						<?php } ?>
					</script>
				<?php } ?>

			</div>
			<div id="variants-editor-status" style="float:right; width:10%; font-size:26px; padding-top: 30px;">
				<i class="fa fa-hourglass"></i>
			</div>
		<?php } ?>
		<div style="clear:both; height:20px;"></div>																
		<table class="list">
			<?php foreach ($other_variant_products as $other_variant_product) { ?>																			
				<tr>
					<td>
						<a href="<?php echo $other_variant_product['link']; ?>" target='_blank'><?echo $other_variant_product['product_id']; ?> <i class="fa fa-edit"></i></a>
					</td>
					<td>
						<img src="<?echo $other_variant_product['thumb']; ?>">
					</td>
					<td>
						<b><?echo $other_variant_product['asin']; ?></b>
					</td>
					<td style="width:60%">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<textarea cols="2" style="width:98%" id="variant_name_<?echo $other_variant_product['product_id']; ?>_<?php echo $language['language_id'];?>" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['name']"><?echo $other_variant_product['name']; ?></textarea>
							<?php } ?>
						<?php } ?>
					</td>
					<td style="width:150px">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_name']"><?echo $other_variant_product['variant_name']; ?></textarea>	
							<?php } ?>
						<?php } ?>
					</td>
					<td style="width:100px">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_name_1']"><?echo $other_variant_product['variant_name_1']; ?></textarea>	
							<?php } ?>
						<?php } ?>									
					</td>
					<td style="width:100px">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_value_1']"><?echo $other_variant_product['variant_value_1']; ?></textarea>	
							<?php } ?>
						<?php } ?>									
					</td>
					<td style="width:100px">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_name_2']"><?echo $other_variant_product['variant_name_2']; ?></textarea>
							<?php } ?>
						<?php } ?>										
					</td>
					<td style="width:100px">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $this->config->get('config_admin_language')) { ?>
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<textarea cols="2" style="width:98%" name="variant_description[<?echo $other_variant_product['product_id']; ?>][<?php echo $language['language_id'];?>]['variant_value_2']"><?echo $other_variant_product['variant_value_2']; ?></textarea>		
							<?php } ?>
						<?php } ?>								
					</td>							
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
</div>
