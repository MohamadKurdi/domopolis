<?php if ($addresses) { ?>
	<input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
	<label for="shipping-address-existing"><?php echo $text_address_existing; ?></label>
	<div id="shipping-existing">
		<select name="address_id" style="width: 100%; margin-bottom: 15px;" size="5">
			<?php foreach ($addresses as $address) { ?>
				<?php if ($address['address_id'] == $address_id) { ?>
					<option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
	</div>
	<p>
		<input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
		<label for="shipping-address-new"><?php echo $text_address_new; ?></label>
	</p>
<?php } ?>
