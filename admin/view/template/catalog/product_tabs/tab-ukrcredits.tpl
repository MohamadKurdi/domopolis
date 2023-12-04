<div id="tab-ukrcredits">
<table id="ukrcredits" class="list">
<thead>
	<tr>
		<td class="left"><?php echo $credit_type; ?></td>
		<td class="left"><?php echo $entry_status; ?></td>
		<td class="left"><span data-toggle="tooltip" title="<?php echo $help_partsCount; ?>"><?php echo $partsCount; ?></td>
			<td class="left"><span data-toggle="tooltip" title="<?php echo $help_markup; ?>"><?php echo $markup; ?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="left"><?php echo $credit_type_pp; ?></td>
				<td>
					<select name="product_pp" id="input-product_pp" class="form-control">
						<?php if ($product_pp) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</td>					  
				<td>
					<select name="partscount_pp" id="input-partscount_pp" class="form-control">
						<option value=""><?php echo $text_select; ?></option>
						<?php foreach ($partscounts as $partscount) { ?>
							<?php if ($partscount_pp == $partscount) { ?>
								<option value="<?php echo $partscount; ?>" selected="selected"><?php echo $partscount; ?></option>
							<?php } else { ?>
								<option value="<?php echo $partscount; ?>"><?php echo $partscount; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
				<td class="left"><input type="text" name="markup_pp" value="<?php echo $markup_pp; ?>" placeholder="1.0000" id="input-markup_pp" class="form-control" /></td>
			</tr>
			<tr>
				<td class="left"><?php echo $credit_type_ii; ?></td>
				<td>
					<select name="product_ii" id="input-product_ii" class="form-control">
						<?php if ($product_ii) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</td>	
				<td>
					<select name="partscount_ii" id="input-partscount_ii" class="form-control">
						<option value=""><?php echo $text_select; ?></option>
						<?php foreach ($partscounts as $partscount) { ?>
							<?php if ($partscount_ii == $partscount) { ?>
								<option value="<?php echo $partscount; ?>" selected="selected"><?php echo $partscount; ?></option>
							<?php } else { ?>
								<option value="<?php echo $partscount; ?>"><?php echo $partscount; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
				<td class="left"><input type="text" name="markup_ii" value="<?php echo $markup_ii; ?>" placeholder="1.0000" id="input-markup_ii" class="form-control" /></td>
			</tr>
			<tr>
				<td class="left"><?php echo $credit_type_mb; ?></td>
				<td>
					<select name="product_mb" id="input-product_mb" class="form-control">
						<?php if ($product_mb) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</td>	
				<td>
					<select name="partscount_mb" id="input-partscount_mb" class="form-control">
						<option value=""><?php echo $text_select; ?></option>
						<?php foreach ($partscounts as $partscount) { ?>
							<?php if ($partscount_mb == $partscount) { ?>
								<option value="<?php echo $partscount; ?>" selected="selected"><?php echo $partscount; ?></option>
							<?php } else { ?>
								<option value="<?php echo $partscount; ?>"><?php echo $partscount; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</td>
				<td class="left"><input type="text" name="markup_mb" value="<?php echo $markup_mb; ?>" placeholder="1.0000" id="input-markup_mb" class="form-control" /></td>
			</tr>
		</tbody>
	</table>
</div>