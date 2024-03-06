	<div id="tab-dimensions">
		<table class="form">
			<tr>
				<td style="width:50%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Цвет</span>
				</td>
				<td style="width:50%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Материал</span>
				</td>
			</tr>
			<tr>
				<td>
					<?php foreach ($languages as $language) { ?>
						<input type="text" name="product_description[<?php echo $language['language_id']; ?>][color]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['color'] : ''; ?>" size="90%" />
						<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
					<?php } ?>
				</td>
				<td>
					<?php foreach ($languages as $language) { ?>
						<input type="text" name="product_description[<?php echo $language['language_id']; ?>][material]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['material'] : ''; ?>" size="90%" />
						<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
					<?php } ?>
				</td>
			</tr>	
		</table>
		<table class="form">
			<tr>
				<td style="width:50%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">НЕТТО (без упаковки)</span>
				</td>
				<td style="width:50%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">БРУТТО (с упаковкой)</span>
				</td>
			</tr>	
			<tr>
				<td>
					<table>
						<tr>																				
							<td style="width:350px;">
								<div><span style="display:inline-block; width:120px;">Длина / Length:</span> <input type="text" name="length" value="<?php echo $length; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
								<div><span style="display:inline-block; width:120px;">Ширина / Width:</span> <input type="text" name="width" value="<?php echo $width; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
								<div><span style="display:inline-block; width:120px;">Высота / Height:</span> <input type="text" name="height" value="<?php echo $height; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
							</td>					
							<td style="width:350px;">
								<div style="margin-bottom:10px;">
									<i class="fa fa-bars"></i>
									<select name="length_class_id" style="width:250px;">
										<?php foreach ($length_classes as $length_class) { ?>
											<?php if ($length_class['length_class_id'] == $length_class_id) { ?>
												<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div>
									<i class="fa fa-amazon"></i> <input type="text" name="length_amazon_key" value="<?php echo $length_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
								</div>
							</td>
						</tr>
						<tr>
							<td style="width:350px;">
								<div><span style="display:inline-block; width:120px;">Вес / Weight:</span> <input type="text" name="weight" value="<?php echo $weight; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
							</td>	
							<td style="width:350px;">							
								<div style="margin-bottom:10px;">
									<i class="fa fa-bars"></i>
									<select name="weight_class_id" style="width:250px;">
										<?php foreach ($weight_classes as $weight_class) { ?>
											<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
												<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>		

								<div>
									<i class="fa fa-amazon"></i> <input type="text" name="weight_amazon_key" value="<?php echo $weight_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
								</div>												
							</td>
						</tr>																						
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td style="width:350px;">
								<div><span style="display:inline-block; width:120px;">Длина / Length:</span> <input type="text" name="pack_length" value="<?php echo $pack_length; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
								<div><span style="display:inline-block; width:120px;">Ширина / Width:</span> <input type="text" name="pack_width" value="<?php echo $pack_width; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
								<div><span style="display:inline-block; width:120px;">Высота / Height:</span> <input type="text" name="pack_height" value="<?php echo $pack_height; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
							</td>					
							<td style="width:350px;">
								<div style="margin-bottom:10px;">
									<i class="fa fa-bars"></i>																							
									<select name="pack_length_class_id" style="width:250px;">
										<?php foreach ($length_classes as $length_class) { ?>
											<?php if ($length_class['length_class_id'] == $pack_length_class_id) { ?>
												<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div>
									<i class="fa fa-amazon"></i> <input type="text" name="pack_length_amazon_key" value="<?php echo $pack_length_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
								</div>
							</td>
						</tr>

						<tr>
							<td style="width:350px;">
								<div><span style="display:inline-block; width:120px;">Вес / Weight:</span> <input type="text" name="pack_weight" value="<?php echo $pack_weight; ?>" size="4" style="width:150px; margin-bottom:3px;" /></div>
							</td>	
							<td style="width:350px;">
								<i class="fa fa-bars"></i>							
								<div style="margin-bottom:10px;">
									<select name="pack_weight_class_id" style="width:250px;">
										<?php foreach ($weight_classes as $weight_class) { ?>
											<?php if ($weight_class['weight_class_id'] == $pack_weight_class_id) { ?>
												<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>	
								<div>
									<i class="fa fa-amazon"></i> <input type="text" name="pack_weight_amazon_key" value="<?php echo $pack_weight_amazon_key; ?>" size="100" style="width:250px; margin-bottom:3px;" />
								</div>													
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>