<div id="tab-data">
	<table class="form">
		<tr><th colspan='4'>Основная информация</th></tr>
		<tr>
			<td><span class="required">*</span> <?php echo $entry_model; ?></td>
			<td>
				<input type="text" name="model" value="<?php echo $model; ?>" />
				<?php if ($error_model) { ?>
					<span class="error"><?php echo $error_model; ?></span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>Экспортное название<br /><span class="help">макс. 50 символов</span></td>
			<td><input type="text" name="short_name" value="<?php echo $short_name; ?>" size="50" maxlength="50" /></td>

			<td><img src="<?php echo DIR_FLAGS_NAME; ?>de.png" title="de"> Экспортное название DE<br /><span class="help">макс. 50 символов</span></td>
			<td><input type="text" name="short_name_de" value="<?php echo $short_name_de; ?>" size="50" maxlength="50" /></td>
		</tr>
		<tr>
			<td><?php echo $entry_sku; ?></td>
			<td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>

			<td><?php echo $entry_upc; ?></td>
			<td><input type="text" name="upc" value="<?php echo $upc; ?>" /></td>
		</tr>
		<tr>
			<td><?php echo $entry_ean; ?></td>
			<td><input type="text" name="ean" value="<?php echo $ean; ?>" /></td>

			<td><?php echo $entry_jan; ?></td>
			<td><input type="text" name="jan" value="<?php echo $jan; ?>" /></td>
		</tr>

		<tr>
			<td><?php echo $entry_mpn; ?></td>
			<td><input type="text" name="mpn" value="<?php echo $mpn; ?>" /></td>
			<td><b>OLD ASIN</b>
				<br /><span class="help">поле не изменять</span>
			</td>
			<td><input type="text" name="old_asin" value="<?php echo $old_asin; ?>" /></td>
		</tr>

		<tr>
			<td>ТН ВЭД</td>
			<td><input type="text" name="tnved" value="<?php echo $tnved; ?>" /></td>

			<td>ASIN</td>
			<td><input type="text" name="asin" value="<?php echo $asin; ?>" /></td>
		</tr>

		<tr>
			<td><?php echo $entry_location; ?></td>
			<td><input type="text" name="location" value="<?php echo $location; ?>" /></td>

			<td><?php echo $entry_status; ?></td>
			<td><select name="status">
				<?php if ($status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			</select></td>
		</tr>

		<tr><th colspan='4'>Автосоздание опций</th></tr>

		<tr>
			<td colspan="1">Код группы / Наименование родителя<br /><span class="help">Поле ISBN в стоковой логике</span></td>
			<td colspan="3"><input type="text" name="isbn" value="<?php echo $isbn; ?>" />
				<br /><span class="help">Этот товар является виртуальной опцией какого-либо другого товара с названием, указанным в данном поле</span>
			</td>
		</tr>
		<tr>
			<td colspan="1">Опция</td>
			<td colspan="3">
				<select name="is_option_with_id">
					<option value="0" <? if (!$is_option_with_id) { ?>selected="selected"<? } ?>>Не является никакой виртуальной опцией, это нормальный товар</option>

					<? foreach($_all_options_list as $_joption) { ?>
						<option value="<? echo $_joption['option_id'] ?>" <? if ($is_option_with_id == $_joption['option_id']) { ?>selected="selected"<? } ?>>
							<? echo $_joption['name'] ?> : <? echo $_joption['option_id'] ?> : <? echo $_joption['type'] ?>
						</option>		
					<? } ?>


				</select>
				<br /><span class="help">Этот товар является виртуальной опцией какого-либо другого товара, наименование которого указано в поле "Код группы / Наименование родителя" с идентификатором опции, выбранном в этом поле и значением, указанном в мультиязычном поле Имя опции</span>
			</td>
		</tr>
		<tr>
			<td colspan="1">Цветовая группа</td>

			<td colspan="3"><input type="text" style="width:500px;" name="color_group" value="<?php echo $color_group; ?>" />
				<br /><span class="help">Это идентификатор группы товаров, которые являются одним и тем же товаром, но с другимим цветами</span>


				<? if (isset($color_grouped_products)) { ?>
					<br />
					<? foreach ($color_grouped_products as $_cgp) { ?>
						<div style="float:left; text-align:center; width:150px; margin-right:20px;">
							<img src="<? echo $_cgp['image']; ?>" /><br />
							<? echo $_cgp['name']; ?><br />
							<? if (!$_cgp['this']) { ?><a href="<? echo $_cgp['action']; ?>">[Изменить]</a><? } ?>
						</div>
					<? } ?>	
				<? } ?>

			</td>
		</tr>

		<tr><th colspan='4'>Количества для закупки</th></tr>
		<tr>
			<td>Минимальный порог</td>
			<td><input type="text" name="min_buy" value="<?php echo $min_buy; ?>" size="2" /><br />

			</td>

			<td>Предельное количество</td>
			<td><input type="text" name="max_buy" value="<?php echo $max_buy; ?>" size="2" /><br /></td>
		</tr>	


		<tr><th colspan='4'>Настройки отображения</th></tr>
		<tr>
			<td>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-clock-o"></i> Дата добавления</span>
			</td>
			<td>
				<input type="text" name="date_added" value="<?php echo $date_added; ?>" size="12" class="date" style="width:150px;" />
				<br /><span class="help"><i class="fa fa-info-circle"></i> Если у товара стоит маркер "новинка", то этот товар будет отображаться в новинках в случае если с этой даты прошло менее 45 дней, либо задана дата до которой отображать его в новинках</span>
			</td>

			<td>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-clock-o"></i> Новинка</span>
			</td>
			<td>
				<input type="checkbox" name="new" value="1" <?php if (isset($new) && $new) print 'checked'; ?> />

				<input type="text" name="new_date_to" class="date" value="<? echo $new_date_to ?>" size="12" style="width:150px;" />
				<br /><span class="help">
					<i class="fa fa-info-circle"></i> товар будет отображаться в новинках ДО ЭТОЙ ДАТЫ (либо с даты добавления прошло менее 45 дней)</span>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_minimum; ?></td>
				<td><input type="text" name="minimum" value="<?php echo $minimum; ?>" size="2" /></td>

				<td>
					Снимать со склада
				</td>
				<td>
					<select name="subtract">
						<?php if ($subtract) { ?>
							<option value="1" selected="selected"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0" selected="selected"><?php echo $text_no; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Кол-во в упаковке<span class="help">количество товара в одной упаковке, для закупки</span></td>
				<td><input type="text" name="package" value="<?php echo $package; ?>" size="2" /></td>

				<td></td>
				<td></td>
			</tr>

			<tr>
				<td><?php echo $entry_shipping; ?></td>
				<td><?php if ($shipping) { ?>
					<input type="radio" name="shipping" value="1" checked="checked" />
					<?php echo $text_yes; ?>
					<input type="radio" name="shipping" value="0" />
					<?php echo $text_no; ?>
				<?php } else { ?>
					<input type="radio" name="shipping" value="1" />
					<?php echo $text_yes; ?>
					<input type="radio" name="shipping" value="0" checked="checked" />
					<?php echo $text_no; ?>
					<?php } ?></td>

					<td></td>
					<td></td>
				</tr>

				<tr>
					<td><?php echo $entry_sort_order; ?></td>
					<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>

					<td>Товар доступен до</td>
					<td>
						<input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" />
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_tax_class; ?></td>
					<td><select name="tax_class_id">
						<option value="0"><?php echo $text_none; ?></option>
						<?php foreach ($tax_classes as $tax_class) { ?>
							<?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
								<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
							<?php } ?>
						<?php } ?>
					</select></td>
				</tr>

				<tr><th colspan='4'>Картинка, URL</th></tr>	
				<tr>
					<td><?php echo $entry_image; ?></td>
					<td>
						<div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
							<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
							<a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div>
						</td>

						<td><?php echo $entry_keyword; ?></td>
						<td>
							<?php foreach ($languages as $language) { ?>
								<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php  if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
							<?php } ?>
						</td>
					</td>
				</tr>
			</table>
		</div>
