<div id="tab-stock">
	<div style="float:left; width:49%;">
	<table>
		<tr>
			<td>
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Общий статус наличия</span></p>
				<select name="stock_status_id">
					<?php foreach ($stock_statuses as $stock_status) { ?>
						<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
							<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</td>
			<td>
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Наличие</span></p>
				<input type="number" step="1" name="quantity" value="<?php echo $quantity; ?>" />
			</td>
		</tr>
	</table>
	<table class="form">
		<tr>
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/count($stores))?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Статус: <?php echo $store['name']; ?></span></p>
					<select name="product_stock_status[<?php echo $store['store_id']; ?>]">

						<?php if (empty($product_stock_status[$store['store_id']])) { ?>
							<option value="0" selected="selected">Не переназначать</option>
						<?php } else { ?>
							<option value="0">Не переназначать</option>	
						<?php } ?>

						<?php foreach ($stock_statuses as $stock_status) { ?>
							<?php if (!empty($product_stock_status[$store['store_id']]) && $stock_status['stock_status_id'] == (int)$product_stock_status[$store['store_id']]) { ?>
								<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
			<?php } ?>
		</tr>
	</table>
	<table class="form">
		<tr>																	
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/(count($stores))) ?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF">Склад: <?php echo $store['name']; ?></span></p>

					<input type="number" step="1" name="<?php echo $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id']); ?>" value="<?php echo ${$this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id'])}; ?>" />
				</td>
			<?php }	?>
		</tr>
		<tr>
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/(count($stores))) ?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Едет: <?php echo $store['name']; ?></span></p>

					<input type="number" step="1" name="<?php echo $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id']); ?>_onway" value="<?php echo ${$this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id']) . '_onway'}; ?>" />
				</td>
			<?php }	?>
		</tr>
	</table>
	</div>

	<div style="float:left; width:49%;">
		<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Наличие локальных поставщиков</span></h2>
		<table class="list">
			<thead>
				<tr>
					<td class="center">Поставщик</td>
					<td class="center">Цена</td>
					<td class="center">Скидка</td>
					<td class="center">Наличие</td>
					<td class="center">Количество</td>
				</tr>
			</thead>
			<?php foreach ($product_suppliers as $product_supplier) { ?>
				<tr>
					<td class="center">
						<?php echo $product_supplier['name']; ?>									
					</td>
					<td class="center">
						<?php echo $product_supplier['price']; ?>									
					</td>
					<td class="center">
						<?php echo $product_supplier['price_special']; ?>									
					</td>
					<td class="center">
						<? if ($product_supplier['stock']) { ?>
							<i class="fa fa-check-circle" style="color:#4ea24e"></i>
						<? } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
					</td>
					<td class="center">
						<?php echo $product_supplier['quantity']; ?>									
					</td>
				</td>
			</tr>
		<?php } ?>
	</table>

	<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Обеспечение склада</span></h2>
	<table class="form">
		<tr>
			<? $i=1; ?>			
			<? unset($store); foreach ($stores as $store) { ?>				
				<td><? echo $store['name']; ?><br /><span class="help">Минимальное и рекомендуемое наличие</span></td>
				<td>
					<input type="number" step="1" name="product_stock_limits[<? echo $store['store_id']; ?>][min_stock]" value="<?php echo isset($product_stock_limits[$store['store_id']])?$product_stock_limits[$store['store_id']]['min_stock']:0; ?>" style="width:50px;" /> Минимальное
					<input type="number" step="1" name="product_stock_limits[<? echo $store['store_id']; ?>][rec_stock]" value="<?php echo isset($product_stock_limits[$store['store_id']])?$product_stock_limits[$store['store_id']]['rec_stock']:0; ?>" style="width:50px;" /> Рекомендуемое
				</td>
				<? $i++; ?>
				<? if ($i%2==0) { ?></tr><tr><? } ?>
			<? } ?>
		</tr>
	</table>
</div>


</div>