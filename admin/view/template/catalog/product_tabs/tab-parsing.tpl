<div id="tab-parsing">

	<div <?php if ($this->config->get('config_country_id') != 176) { ?> style="display:none"<?php } ?>>
		<h2>
			SKU <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $sku; ?></span> 
			MODEL <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $model; ?></span>
			OFFER-ID <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $yam_product_id; ?></span>
		</h2>


		<table class="form">
			<tr>																					
				<td>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Исключить из YAM</span>
				</td>
				<td>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Код товара YAM</span>
				</td>
				<td>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Включить Priceva/PriceControl</span>
				</td>
				<td>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Исключить из Priceva/PriceControl</span>
				</td>																					
			</tr>

			<tr style="border-bottom:1px dashed gray">
				<td style="width:16.6%">
					<select name="yam_disable">
						<?php if ($yam_disable) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</td>

				<td style="width:16.6%">
					<input type="text" name="yam_product_id" value="<?php echo $yam_product_id; ?>" />
				</td>

				<td style="width:16.6%">
					<select name="priceva_enable">
						<?php if ($priceva_enable) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</td>
				<td style="width:16.6%">
					<select name="priceva_disable">
						<?php if ($priceva_disable) { ?>
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
	</div>
	<div <?php if ($this->config->get('config_country_id') != 220) { ?> style="display:none"<?php } ?>>
		<table class="form">
			<tr>																					
				<td>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Исключить из Hotline</span>
				</td>									
			</tr>
			<tr style="border-bottom:1px dashed gray">
				<td style="width:16.6%">
					<select name="hotline_disable">
						<?php if ($hotline_disable) { ?>
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
	</div>

	<table class="form">
		<tr>
			<td style="width:50%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ссылки на конкурентов</span>
			</td>
			<td style="width:50%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ссылки на конкурентов - Украина</span>
			</td>					
		</tr>
		<tr style="border-bottom:1px dashed gray">
			<td>
				<textarea name="competitors" style="width:90%; height:200px;"><? echo $competitors; ?></textarea>
				<br />
				<span class="help">cсылки на страницы, где находится товар на сайтах конкурентов. по одной в строке</span>
			</td>
			<td>
				<textarea name="competitors_ua" style="width:90%; height:200px;"><? echo $competitors_ua; ?></textarea>
				<br />
				<span class="help">cсылки на страницы, где находится товар на сайтах конкурентов. по одной в строке. Только Украина</span>
			</td>	
		</tr>
	</table>

	<table class="form">
		<tr>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Поставщик - источник товара</span>
			</td>
			<td>
				<select name="added_from_supplier">
					<option value="0" <?php if (!$added_from_supplier) { ?>selected="selected"<? } ?>>Не добавлен от поставщика</option>
					<?php foreach ($parser_suppliers as $supplier) { ?>
						<option value="<?php echo $supplier['supplier_id']; ?>" <?php if ($supplier['supplier_id'] == $added_from_supplier) { ?>selected="selected"<? } ?>><?php echo $supplier['supplier_name']; ?></option>
					<?php } ?>
				</select>																					
			</td>
		</tr>
	</table>

	<table class="form">
		<tr>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Неликвидный товар</span>
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Закупочная цена</span>
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Закупочная акционная цена</span>
			</td>
			<td>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Мин цена</span>
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Цена из парсера</span>
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7f00; color:#FFF">Акционная цена из парсера</span>
			</td>
		</tr>

		<tr style="border-bottom:1px dashed gray">
			<td>
				<select name="is_illiquid">
					<?php if ($is_illiquid) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>																					
			</td>	
			<td>
				<input type="text" name="cost" value="<?php echo $cost; ?>" />
			</td>
			<td>
				<input type="text" name="special_cost" value="<?php echo $special_cost; ?>" />
			</td>
			<td>																						
				<input type="text" name="mpp_price" value="<?php echo $mpp_price; ?>" />&nbsp;<? echo $this->config->get('config_currency'); ?>
			</td>
			<td>
				<?php echo $parser_price; ?>
			</td>
			<td>
				<?php echo $parser_special_price; ?>																							
			</td>
		</tr>
	</table>

	<table class="form">
		<tr>
			<td>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Источники цен, поставщики</span>
			</td>
		</tr>
		<tr style="border-bottom:1px dashed gray">
			<td>
				<textarea name="source" style="width:90%; heigth:200px;"><? echo $source; ?></textarea>
				<br />
				<span class="help">Ссылки на страницы, где находится товар. По одной в строке</span>
			</td>
		</tr>
	</tr>
</table>
</div>