	<div id="tab-products">
					<table class="form">						
						<tr>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">ТНВЭД для товаров</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Наценка</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отбор пересечений</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Исключить из пересечений</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Удалять товары не в наличии</span>
							</td>
							<td style="width:15%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Полное меню в дочерних категориях</span>
							</td>
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="tnved" value="<?php echo $tnved; ?>" size="20" />
							</td>
							<td>
								<input type="text" name="overprice" value="<?php echo $overprice; ?>" size="10" />
							</td>
							<td>
								<select name="intersections">
									<?php if ($intersections) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="exclude_from_intersections">
									<?php if ($exclude_from_intersections) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="deletenotinstock">
									<?php if ($deletenotinstock) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="submenu_in_children">
									<?php if ($submenu_in_children) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</table>					
					
					<table class="form">						
						<tr>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Вес упаковки по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Единица измерения веса по-умолчанию</span>
							</td>							
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="default_weight" value="<?php echo $default_weight; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<select name="default_weight_class_id">
									<?php foreach ($weight_classes as $weight_class) { ?>
										<?php if ($weight_class['weight_class_id'] == $default_weight_class_id) { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>							
						</tr>
					</table>
					
					<table class="form">						
						<tr>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Длина упаковок по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ширина упаковок по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Высота упаковок по-умолчанию</span>
							</td>
							<td style="width:25%">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Единица измерения размера по-умолчанию</span>
							</td>							
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="default_length" value="<?php echo $default_length; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<input type="text" name="default_width" value="<?php echo $default_width; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<input type="text" name="default_height" value="<?php echo $default_height; ?>" size="4" style="width:150px;" />
							</td>
							<td>
								<select name="default_length_class_id">
									<?php foreach ($length_classes as $length_class) { ?>
										<?php if ($length_class['length_class_id'] == $default_length_class_id) { ?>
											<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>							
						</tr>
					</table>
				</div>