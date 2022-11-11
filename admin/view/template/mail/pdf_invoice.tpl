<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td align="left" valign="top">
				<table border="0" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<td><img alt="" src="<?php echo $store['config_logo']; ?>" /></td>
						</tr>
						<tr>
							<td style="line-height:0.5pt">&nbsp;</td>
						</tr>
						<tr>
							<td align="left">
								<table border="0" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td width="90"><?php echo $text_date_added; ?></td>
											<td><?php echo $order['date_added']; ?></td>
										</tr>

										<tr>
											<td width="90"><?php echo $text_order_id; ?></td>
											<td><?php echo $order['order_id']; ?></td>
										</tr>

										<?php if($order['invoice_no']){ ?>
										<tr>
											<td width="90"><?php echo $text_invoice_no; ?></td>
											<td><?php echo $order['invoice_prefix'] . $order['invoice_no']; ?></td>
										</tr>
										<?php } ?>

										<?php if($order['payment_method']){ ?>
										<tr>
											<td width="90"><?php echo $text_payment_method; ?></td>
											<td><?php echo $order['payment_method']; ?></td>
										</tr>
										<?php } ?>

										<?php if($order['shipping_method']){ ?>
										<tr>
											<td width="90"><?php echo $text_shipping_method; ?></td>
											<td><?php echo $order['shipping_method']; ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
			<td align="right" valign="top">
				<b style="font-size: 120%;"><?php if($store['config_url']){ ?><a href="<?php echo $store['config_url']; ?>" style="text-decoration:none; color:#000000" target="_blank"><?php echo $store['config_name']; ?></a><?php } else { echo $store['config_name']; } ?></b>

				<?php if($store['config_address']){ ?>
					<br /><?php echo $store['config_address']; ?>
				<?php } ?>

				<?php if($store['config_telephone']){ ?>
					<br /><?php echo $store['config_telephone']; ?>
				<?php } ?>

				<?php if($store['config_email']){ ?>
					<br /><a href="mailto:<?php echo $store['config_email']; ?>" style="text-decoration:none; color:#000000" target="_blank"><?php echo $store['config_email']; ?></a>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" height="8">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td bgcolor="<?php echo $config['invoice_color']; ?>" style="color:#ffffff"<?php if($order['shipping_address']){ ?> width="50%"<?php } ?>><?php echo $text_to; ?></td>
						<?php if($order['shipping_address']){ ?>
							<td bgcolor="<?php echo $config['invoice_color']; ?>" style="color:#ffffff" width="50%"><?php echo $text_ship_to; ?></td>
						<?php } ?>
					</tr>
					<tr>
						<td>
							<p>
								<?php echo $order['payment_address']; ?>

								<?php if($order['payment_company_id'] || $order['payment_tax_id']){ ?>
									<br style="line-height: 0.5" />
								<?php } ?>

								<?php if($order['payment_company_id']){ ?>
									<br /><b><?php echo $text_company_id; ?></b> <?php echo $order['payment_company_id']; ?>
								<?php } ?>

								<?php if($order['payment_tax_id']){ ?>
									<br /><b><?php echo $text_tax_id; ?></b> <?php echo $order['payment_tax_id']; ?>
								<?php } ?>
							</p>
						</td>
						<?php if($order['shipping_address']){ ?>
						<td>
							<p><?php echo $order['shipping_address']; ?></p>
						</td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
