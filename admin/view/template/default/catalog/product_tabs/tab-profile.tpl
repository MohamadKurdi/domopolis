	<div id="tab-profile">
		<table class="list">
			<thead>
				<tr>
					<td class="left"><?php echo $entry_profile ?></td>
					<td class="left"><?php echo $entry_customer_group ?></td>
					<td class="left"></td>
				</tr>
			</thead>
			<tbody>
				<?php $profileCount = 0; ?>
				<?php foreach ($product_profiles as $product_profile): ?>
					<?php $profileCount++ ?>

					<tr id="profile-row<?php echo $profileCount ?>">
						<td class="left">
							<select name="product_profiles[<?php echo $profileCount ?>][profile_id]">
								<?php foreach ($profiles as $profile): ?>
									<?php if ($profile['profile_id'] == $product_profile['profile_id']): ?>
										<option value="<?php echo $profile['profile_id'] ?>" selected="selected"><?php echo $profile['name'] ?></option>
									<?php else: ?>
										<option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</td>
						<td class="left">
							<select name="product_profiles[<?php echo $profileCount ?>][customer_group_id]">
								<?php foreach ($customer_groups as $customer_group): ?>
									<?php if ($customer_group['customer_group_id'] == $product_profile['customer_group_id']): ?>
										<option value="<?php echo $customer_group['customer_group_id'] ?>" selected="selected"><?php echo $customer_group['name'] ?></option>
									<?php else: ?>
										<option value="<?php echo $customer_group['customer_group_id'] ?>"><?php echo $customer_group['name'] ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</td>
						<td class="right">
							<a class="button" onclick="$('#profile-row<?php echo $profileCount ?>').remove()"><?php echo $button_remove ?></a>
						</td>
					</tr>

				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"></td>
					<td class="right"><a onclick="addProfile()" class="button"><?php echo $button_add_profile ?></a></td>
				</tr>
			</tfoot>
		</table>
	</div>

	<script type="text/javascript"><!--
	
	var profileCount = <?php echo $profileCount ?>;
	
	function addProfile() {
		profileCount++;
		
		var html = '';
		html += '<tr id="profile-row' + profileCount + '">';
		html += '  <td class="left">';
		html += '    <select name="product_profiles[' + profileCount + '][profile_id]">';
		<?php foreach ($profiles as $profile): ?>
			html += '      <option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>';
		<?php endforeach; ?>
		html += '    </select>';
		html += '  </td>';
		html += '  <td class="left">';
		html += '    <select name="product_profiles[' + profileCount + '][customer_group_id]">';
		<?php foreach ($customer_groups as $customer_group): ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id'] ?>"><?php echo $customer_group['name'] ?></option>';
		<?php endforeach; ?>
		html += '    <select>';
		html += '  </td>';
		html += '  <td class="right">';
		html += '    <a class="button" onclick="$(\'#profile-row' + profileCount + '\').remove()"><?php echo $button_remove ?></a>';
		html += '  </td>';
		html += '</tr>';
		
		$('#tab-profile table tbody').append(html);
	}
	
	
	//--></script>