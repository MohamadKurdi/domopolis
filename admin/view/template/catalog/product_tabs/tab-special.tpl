<div id="tab-special">
	<table id="special" class="list">
		<thead>
			<tr>
				<td class="left"><?php echo $entry_customer_group; ?></td>
				<td class="right">Виртуальный магазин</td>
				<td class="right">Валюта</td>
				<td class="right"><?php echo $entry_priority; ?></td>
				<td class="right"><?php echo $entry_price; ?></td>										
				<td class="left"><?php echo $entry_date_start; ?></td>
				<td class="left"><?php echo $entry_date_end; ?></td>
			</tr>
		</thead>
		<?php $special_row = 0; ?>

		<?php 
			$stores_special = $stores;																						
			array_unshift($stores_special, array('store_id' => -1, 'name' => 'Для всех магазинов'));
		?>

		<?php foreach ($product_specials as $product_special) { ?>
			<tbody id="special-row<?php echo $special_row; ?>">
				<tr>
					<td class="left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]">
						<?php foreach ($customer_groups as $customer_group) { ?>
							<?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
								<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select></td>
					<td class="left">
						<select name="product_special[<?php echo $special_row; ?>][store_id]">
							<?php foreach ($stores_special as $store) { ?>
								<?php if ($store['store_id'] == $product_special['store_id']) { ?>
									<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td class="left">
						<select name="product_special[<?php echo $special_row; ?>][currency_scode]">
							<option value="">Дефолтная</option>
							<?php foreach ($currencies as $currency) { ?>
								<?php if ($currency['code'] == $product_special['currency_scode']) { ?>
									<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['code']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $currency['code']; ?>"><?php echo $currency['code']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
					<td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
					<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" /></td>
					<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" /></td>
					<td class="left"><a onclick="$('#special-row<?php echo $special_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
				</tr>
			</tbody>
			<?php $special_row++; ?>
		<?php } ?>
		<tfoot>
			<tr>
				<td class="right" colspan="8"><a onclick="addSpecial();" class="button">Добавить скидку</a></td>
			</tr>
		</tfoot>
	</table>

	<?php if ($product_specials_backup) { ?>
		<h2><i class="fa fa-save"></i> Есть автосохраненные скидки</h2>

		<table id="special-backup" class="list">
		<thead>
			<tr>
				<td class="left"><?php echo $entry_customer_group; ?></td>
				<td class="right">Виртуальный магазин</td>
				<td class="right">Валюта</td>
				<td class="right"><?php echo $entry_priority; ?></td>
				<td class="right"><?php echo $entry_price; ?></td>										
				<td class="left"><?php echo $entry_date_start; ?></td>
				<td class="left"><?php echo $entry_date_end; ?></td>
			</tr>
		</thead>
			<?php foreach ($product_specials_backup as $product_special_backup) { ?>
			<tbody id="special-backup-row">
				<tr>
					<td class="left">
						<?php foreach ($customer_groups as $customer_group) { ?>
							<?php if ($customer_group['customer_group_id'] == $product_special_backup['customer_group_id']) { ?>
								<?php echo $customer_group['name']; ?>
							<?php } ?>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($stores_special as $store) { ?>
							<?php if ($store['store_id'] == $product_special_backup['store_id']) { ?>
								<?php echo $store['name']; ?>
							<?php } ?>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($currencies as $currency) { ?>
							<?php if ($currency['code'] == $product_special_backup['currency_scode']) { ?>
								<?php echo $currency['code']; ?>
							<?php } ?>
						<?php } ?>
					</td>
					<td class="right">
						<?php echo $product_special_backup['priority']; ?>
					</td>
					<td class="right">
						<?php echo $product_special_backup['price']; ?>						
					</td>
					<td class="left">
						<?php echo $product_special_backup['date_start']; ?>							
					</td>
					<td class="left">
						<?php echo $product_special_backup['date_end']; ?>
					</td>					
				</tr>
			</tbody>
		<?php } ?>
					<tfoot>
			<tr>
				<td class="right" colspan="7">
					<a onclick="removeAllHistories();" class="button">Очистить</a>
					<a onclick="restoreAllHistories();" class="button">Восстановить</a>
				</td>
			</tr>
		</tfoot>
	</table>
	<?php } ?>
</div>

<script type="text/javascript">
	var special_row = <?php echo $special_row; ?>;
	
	function addSpecial() {
		html  = '<tbody id="special-row' + special_row + '">';
		html += '  <tr>'; 
		html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
		<?php foreach ($customer_groups as $customer_group) { ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '    </select></td>';
		html += '    <td class="left"><select name="product_special[' + special_row + '][store_id]">';
		<?php foreach ($stores_special as $store) { ?>
			html += '      <option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
		<?php } ?>
		html += '    </select></td>';
		html += '    <td class="left"><select name="product_special[' + special_row + '][currency_scode]">';
		html += '    <option value="">Дефолтная</option>';
		<?php foreach ($currencies as $c) { ?>
			html += '      <option value="<?php echo $c['code']; ?>"><?php echo addslashes($c['code']); ?></option>';
		<?php } ?>
		html += '    </select></td>';
		html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
		html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
		html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
		html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
		html += '    <td class="right"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';
		html += '</tbody>';
		
		$('#special tfoot').before(html);
		
		$('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
		
		special_row++;
	}
	</script> 