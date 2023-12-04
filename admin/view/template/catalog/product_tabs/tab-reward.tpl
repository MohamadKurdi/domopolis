	<div id="tab-reward">
		<table class="form" style="display:none;">
			<tr>
				<td><?php echo $entry_points; ?></td>
				<td><input type="text" name="points" value="<?php echo $points; ?>" /></td>
			</tr>
		</table>

		<table id="reward" class="list">
			<thead>
				<tr>
					<td class="center"><b>Виртуальный магазин</b></td>
					<td class="center"><b>Количество бонусов</b></td>
					<td class="center"><b>Процент бонусов</b></td>	
					<td class="center"><b>Дата начала</b></td>
					<td class="center"><b>Дата окончания</b></td>
					<td class="center"><b>Промокод</b></td>
					<td class="right"></td>
				</tr>
			</thead>
			<?php $reward_row = 0; ?>

			<? 
			$stores_reward = $stores;
			array_unshift($stores_reward, array('store_id' => -1, 'name' => 'Для всех магазинов'));
			?>

			<?php foreach ($product_reward as $reward) { ?>
				<tbody id="reward-row<?php echo $reward_row; ?>">
					<tr>

						<td class="center">
							<select name="product_reward[<?php echo $reward_row; ?>][store_id]">
								<?php foreach ($stores_reward as $store) { ?>
									<?php if ($store['store_id'] == $reward['store_id']) { ?>
										<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>

						<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][points]" value="<?php echo $reward['points']; ?>" size="10" />☯</td>

						<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][percent]" value="<?php echo $reward['percent']; ?>" size="2" />%</td>								
						<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][date_start]" value="<?php echo $reward['date_start']; ?>" class="date" /></td>

						<td class="center"><input type="text" name="product_reward[<?php echo $reward_row; ?>][date_end]" value="<?php echo $reward['date_end']; ?>" class="date" /></td>


						<td class="center">

							<select name="product_reward[<?php echo $reward_row; ?>][coupon_acts]">
								<?php if ($reward['coupon_acts']) { ?>
									<option value="1" selected="selected"><?php echo $text_yes; ?></option>
									<option value="0"><?php echo $text_no; ?></option>
								<?php } else { ?>
									<option value="1"><?php echo $text_yes; ?></option>
									<option value="0" selected="selected"><?php echo $text_no; ?></option>
								<?php } ?>
							</select>


						</td>


						<td class="right"><a onclick="$('#reward-row<?php echo $reward_row; ?>').remove();" class="button">Удалить</a></td>
					</tr>
				</tbody>
				<?php $reward_row++; ?>
			<?php } ?>
			<tfoot>
				<tr>
					<td colspan="6"></td>
					<td class="right"><a onclick="addReward();" class="button">Добавить</a></td>
				</tr>
			</tfoot>
		</table>

	</div>

	<script type="text/javascript">
		var reward_row = <?php echo $reward_row; ?>;

		function addReward() {
			html  = '<tbody id="reward-row' + reward_row + '">';
			html += '  <tr>'; 

			html += '    <td class="center"><select name="product_reward[' + reward_row + '][store_id]">';
			<?php foreach ($stores_reward as $store) { ?>
				html += '      <option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
			<?php } ?>
			html += '    </select></td>';

			html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][points]" value="" size="10" />☯</td>';
			html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][percent]" value="" size="2" />%</td>';							
			html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][date_start]" value="" class="date" /></td>';
			html += '    <td class="center"><input type="text" name="product_reward[' + reward_row + '][date_end]" value="" class="date" /></td>';
			html += '       <td class="center"><select name="product_reward[' + reward_row + '][coupon_acts]">';
			html += '	      <option value="0"><?php echo $text_no; ?></option>';
			html += '	      <option value="1"><?php echo $text_yes; ?></option>';
			html += '	    </select></td>';
			html += '    <td class="right"><a onclick="$(\'#reward-row' + reward_row + '\').remove();" class="button">Удалить</a></td>';
			html += '  </tr>';
			html += '</tbody>';

			$('#reward tfoot').before(html);

			$('#reward-row' + reward_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

			reward_row++;
		}
	</script> 

