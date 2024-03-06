<div class="emailContent">{$emailtemplate.content1}</div>

<?php if(!empty($customer_id) && isset($invoice)){ ?>
	<br />
	<div class="link">
		<b><?php echo $text_link; ?></b><br />
		<span class="icon">&raquo;</span>
		<a href="<?php echo $invoice_tracking; ?>" target="_blank">
			<b><?php echo $invoice; ?></b>
		</a>
	</div>
<?php } ?>

<?php if(!empty($new_comment)){ ?>
	<br />
	<strong><?php echo $text_comment; ?></strong><br />
	<?php echo $new_comment; ?>
<?php } ?>

<?php /* PACKAGE TRACKING SERVICE */
if (!empty($pts['tracker_carrier_name'])) { ?>
	<?php if (!empty($pts['tracker_thumb'])) { ?>
  		<img src="<?php echo $pts['tracker_thumb']; ?>" width="210" height="70" alt="<?php echo $pts['tracker_carrier_name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" />
	<?php } ?>

	<div class="link">
  		<?php echo htmlentities($pts['tracker_carrier_name'], ENT_QUOTES, 'UTF-8'); ?>
		- <strong><?php echo htmlentities(implode(', ', $pts['tracking_numbers']), ENT_QUOTES, 'UTF-8'); ?></strong>
  		<?php
  		if ($pts['tracker_status']) {
	    	if ($tracking_links_count = count($pts['tracking_links'])) { ?>
	      		<br /><br /><b><?php echo htmlentities($pts['text_tracking_links'], ENT_QUOTES, 'UTF-8'); ?></b>
	      		<?php for ($k = 0; $k < $tracking_links_count; $k++) { ?>
	        		<br /><span class="icon">&raquo;</span>&nbsp;<a href="<?php echo $pts['tracking_links'][$k]; ?>"><b><?php echo htmlentities($pts['tracking_links'][$k], ENT_QUOTES, 'UTF-8'); ?></b></a>
	        	<?php }
	    	}
  		} ?>
  </div>
<?php } ?>

<?php if(!empty($show_products) || !empty($show_vouchers) || !empty($show_totals)){
	$i = 0; ?>
	<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="20">&nbsp;</td></tr></table>

	<table cellpadding="5" cellspacing="0" width="100%" class="table1">
	<?php if(!empty($show_products) || !empty($show_vouchers)){ ?>
	<thead>
		<tr>
	        <th width="50%" bgcolor="#ededed"><b>
	        	<?php echo $text_product; ?>
	        </b></th>
	        <?php if($config['table_quantity']){ ?>
	        	<th width="10%" bgcolor="#ededed" align="center" class="textCenter"><b>
	        		<?php echo $text_quantity; ?>
	        	</b></th>
	        <?php } ?>
	        <th width="<?php if($config['table_quantity']){ ?>20<?php } else { ?>25<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><b>
	        	<?php echo $text_price; ?>
        	</b></th>
	        <th width="<?php if($config['table_quantity']){ ?>20<?php } else { ?>25<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><b>
	        	<?php echo $text_total; ?>
	        </b></th>
		</tr>
	</thead>
	<tbody>
		<?php $colspan = ($config['table_quantity']) ? 3 : 2; ?>
		<?php
		foreach ($products as $product) {
			$row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
	    <tr>
			<td bgcolor="<?php echo $row_style_background; ?>" width="1">
				<?php if($product['image']){ ?>
					<img src="<?php echo $product['image']; ?>" width="50" height="50" alt="" class="product-image" />
				<?php } ?>

				<a href="<?php echo $product['url_tracking']; ?>"><strong class="product-name"><?php echo $product['name']; ?></strong></a>

				<?php if(!empty($product['option'])){ ?>
				<span class="list-product-options">
					<?php foreach ($product['option'] as $option) { ?>
						 <br />- <strong><?php echo $option['name']; ?>:</strong> <?php echo $option['value']; ?>
					<?php } ?>
				</span>
				<?php } ?>

				<br />
				<span class="product-data">
					<?php echo $text_model; ?> <?php echo $product['model']; ?>
				</span>
			</td>
			<?php if($config['table_quantity']){ ?>
				<td bgcolor="<?php echo $row_style_background; ?>" align="center" class="textCenter"><?php echo $product['quantity']; ?></td>
				<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $product['price']; ?></td>
			<?php } else { ?>
				<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $product['quantity']; ?> <b>x</b> <?php echo $product['price']; ?></td>
			<?php } ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php if(isset($show_vouchers)){ ?>
		<?php foreach ($vouchers as $voucher) {
			$row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
		<tr>
	        <td colspan="<?php echo $colspan; ?>" bgcolor="<?php echo $row_style_background; ?>"><?php echo $voucher['description']; ?></td>
			<td bgcolor="<?php echo $row_style_background; ?>"><?php echo $voucher['amount']; ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</tbody>
	<?php } ?>
	<?php if(!empty($show_totals)){ ?>
	<tfoot>
		<?php foreach ($totals as $total) {
			$row_style_background = "#ededed"; ?>
		<tr>
			<td bgcolor="<?php echo $row_style_background; ?>" colspan="<?php echo $colspan; ?>" align="right" class="textRight"><b><?php echo $total['title']; ?></b></td>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</tfoot>
	<?php } ?>
	</table>
<?php } ?>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="20">&nbsp;</td></tr></table>

<?php if(!empty($show_downloads)){ ?>
	<?php foreach ($downloads as $download) { ?>
		<br />
		<div class="link">
			<b><?php echo $text_download; ?></b><br />
			<span class="icon">&raquo;</span>
			<a href="<?php echo $download['href_tracking']; ?>" target="_blank">
				<b><?php echo $download['name']; ?></b>
			</a>
		</div>
	<?php } ?>
<?php } ?>

<div class="emailContent">{$emailtemplate.content2}</div>