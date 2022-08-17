<div class="emailContent">{$emailtemplate.content1}</div>


<?php if(!empty($products)){ ?>
<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>

<table cellpadding="5" cellspacing="0" width="100%" class="table1">
<thead>
	<tr>
    	<th bgcolor="#ededed" class="textCenter" style="text-align:center; font:16px Verdana;">Детализация заказа #<?php echo $order_id; ?></th>
   	</tr>
</thead>
<tbody>
	<tr>
		<td bgcolor="#fafafa">
			<table cellpadding="5" cellspacing="0" width="100%" class="tableStack">
			<tbody>
				<tr>
			    	<td width="60%" valign="top">
							<b>Номер заказа:</b> <?php echo $order_id; ?><? if ($changed>0) { ?>-<? echo $changed; ?><? } ?>							
							<br /><b>Дата заказа</b>: <?php echo $date_added; ?>
							<?php if($payment_method){ ?>	
								<br /><b>Способ оплаты:</b> <?php echo $payment_method; ?>
							<? } ?>	
							<?php if ($shipping_method) { ?>					
								<br /><b>Способ доставки:</b> <?php echo $shipping_method; ?>
							<? } ?>
			        <td width="40%" valign="top">
			        	<b><?php echo $text_email; ?></b> <a href="mailto:<?php echo $email; ?>?subject=Re: <?php echo htmlspecialchars($subject, ENT_COMPAT, 'UTF-8'); ?>" style="color:<?php echo $config['body_link_color']; ?>; word-wrap:break-word;"><?php echo $email; ?></a>
			          	<br /><b><?php echo $text_telephone; ?></b> <a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a>
						<? if (isset($fax) && $fax) { ?>
						<br /><b>Доп. телефон:</b> <a href="tel:<?php echo $fax; ?>"><?php echo $fax; ?></a>
						<? } ?>						
			          	<?php if($affiliate && false){ ?>
							<br /><b><?php echo $text_affiliate; ?></b> [#<?php echo $affiliate['affiliate_id']; ?>] <a href="mailto:<?php echo $affiliate['email']; ?>"><?php echo $affiliate['firstname'].' '.$affiliate['lastname']; ?></a>
						<?php } ?>
			        </td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#f6f6f6">
			<table cellpadding="5" cellspacing="0" width="100%" class="tableStack">
			<tbody>
				<tr>
			    	<td width="60%" class="address">
		    			<b>Адрес плательщика</b>
		    			<br /><p><?php echo $payment_address; ?></p>		    			
			    	</td>
			    	<?php if ($shipping_address) { ?>
			        <td width="40%" class="address">
		        		<b>Адрес получателя</b>
		        		<br /><p><?php echo $shipping_address; ?></p>		        		
			        </td>
			        <?php } ?>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
</tbody>
</table>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>

<table cellpadding="5" cellspacing="0" width="100%" class="table1">
<thead>
	<tr>
        <th width="40%" bgcolor="#ededed" class="textCenter"><?php echo $text_new_product; ?></th>
		<th width="16%" bgcolor="#ededed" class="textCenter">Артикул</th>
        <?php if($config['table_quantity']){ ?>
	        <th width="10%" bgcolor="#ededed" align="center" class="textCenter"><?php echo $text_quantity; ?></th>
        <?php } ?>
        <th width="<?php if($config['table_quantity']){ ?>14<?php } else { ?>15<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><?php echo $text_new_price; ?></th>
        <th width="<?php if($config['table_quantity']){ ?>14<?php } else { ?>15<?php } ?>%" bgcolor="#ededed" align="right" class="textRight"><?php echo $text_new_total; ?></th>
	</tr>
</thead>
<tbody>
	<?php $colspan = ($config['table_quantity']) ? 4 : 5; ?>
	
	<?php if (isset($products_nogood) && count($products_nogood)>0) { $i = 0;
	foreach ($products_nogood as $product) {
		$row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
    <tr>
		<td bgcolor="<?php echo $row_style_background; ?>">
			<?php if($product['image']){ ?>
				<img src="<?php echo $product['image']; ?>" alt="" class="product-image" />
			<?php } ?>
			<span class="product-name"><?php echo $product['name']; ?></span>

			<?php if(!empty($product['option'])){ ?>
			<span class="list-product-options">
				<?php foreach ($product['option'] as $option) { ?>
					<br />- <strong><?php echo $option['name']; ?>:</strong> <?php echo $option['value']; ?>
					<?php if($option['stock_subtract']) { ?>(<span style="color: <?php if($product['stock_quantity'] <= 0) { echo '#FF0000'; } elseif($product['stock_quantity'] <= 5) { echo '#FFA500'; } else { echo '#008000'; }?>"><?php echo $option['stock_quantity']; ?></span>)<?php } ?>
				<?php } ?>
			</span>
			<?php } ?>
		</td>
		<td bgcolor="<?php echo $row_style_background; ?>">
			<span class="product-data">
				<?php echo $product['model']; ?>				
			</span>
		</td>
		<?php if($config['table_quantity']){ ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="center" class="textCenter" style="word-break: normal">Нет в наличии</td>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $product['price']; ?></td>
		<?php } else { ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price">0 <b>x</b> <?php echo $product['price']; ?></td>
		<?php } ?>
		<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price">
			<?php echo $product['total']; ?>
		</td>
	</tr>
	<?php } ?>
	<tr>	
		<td colspan=<? echo $colspan+1; ?> style="padding-bottom:2px;border-bottom-width:3px;"></td>
	</tr>
	<tr>	
		<td colspan=<? echo $colspan+1; ?> style="padding-bottom:2px;"></td>
	</tr>
	<? } ?>
	
	<?php $i = 0;
	foreach ($products as $product) {
		$row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
    <tr>
		<td bgcolor="<?php echo $row_style_background; ?>">
			<?php if($product['image']){ ?>
				<img src="<?php echo $product['image']; ?>" alt="" class="product-image" />
			<?php } ?>
			<span class="product-name"><?php echo $product['name']; ?></span>

			<?php if(!empty($product['option'])){ ?>
			<span class="list-product-options">
				<?php foreach ($product['option'] as $option) { ?>
					<br />- <strong><?php echo $option['name']; ?>:</strong> <?php echo $option['value']; ?>
					<?php if($option['stock_subtract']) { ?>(<span style="color: <?php if($product['stock_quantity'] <= 0) { echo '#FF0000'; } elseif($product['stock_quantity'] <= 5) { echo '#FFA500'; } else { echo '#008000'; }?>"><?php echo $option['stock_quantity']; ?></span>)<?php } ?>
				<?php } ?>
			</span>
			<?php } ?>
		</td>
		<td bgcolor="<?php echo $row_style_background; ?>">
			<span class="product-data">
				<?php echo $product['model']; ?>				
			</span>
		</td>
		<?php if($config['table_quantity']){ ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="center" class="textCenter"><?php echo $product['quantity']; ?></td>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $product['price']; ?></td>
		<?php } else { ?>
			<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $product['quantity']; ?> <b>x</b> <?php echo $product['price']; ?></td>
		<?php } ?>
		<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price">
			<?php echo $product['total']; ?>
		</td>
	</tr>	
	<?php } ?>
	<?php
	if(isset($vouchers)){
		foreach ($vouchers as $voucher) {
			$row_style_background = ($i++ % 2) ? "#f6f6f6" : "#fafafa"; ?>
	<tr>
        <td bgcolor="<?php echo $row_style_background; ?>" colspan="<?php echo $colspan; ?>"><?php echo $voucher['description']; ?></td>
		<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price"><?php echo $voucher['amount']; ?></td>
	</tr>
	<?php }
	} ?>
</tbody>
<tfoot>
	<?php foreach ($totals as $total) {
		$row_style_background = "#ededed"; ?>
	<tr>
		<td bgcolor="<?php echo $row_style_background; ?>" colspan="<?php echo $colspan; ?>" align="right" class="textRight"><b><?php echo $total['title']; ?></b></td>
		<td bgcolor="<?php echo $row_style_background; ?>" align="right" class="textRight price" style="word-break: normal; word-wrap:normal;"><?php echo $total['text']; ?></td>
	</tr>
	<?php } ?>
</tfoot>
</table>

<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>

<?php } ?>

<?php if(!empty($comment)){ ?>
<br />
<b><?php echo $text_new_comment; ?></b><br />
<?php echo $comment; ?><br />
<?php } ?>

<?php if(!empty($order_link)){ ?>
<div class="link">
	<b><?php echo $text_order_link; ?></b><br />
	<span class="icon">&raquo;</span>
	<a href="<?php echo $order_link; ?>" target="_blank">
		<b><?php echo $order_link; ?></b>
	</a>
</div>
<?php } ?>

<div class="emailContent">{$emailtemplate.content2}<? if(isset($bottom_text)) { ?><? echo htmlspecialchars_decode($bottom_text); ?><? } ?></div>
<br /><br />
<? if (false) { ?>
<div style="text-align:center;">
<? if ($payment_will_be_done_on_delivery) { ?>
	<span style="font-size:16px;">Подтвердите, пожалуйста, заказ на условиях данного предложения:</span>
<? } else { ?>
	<span style="font-size:16px;">Подтвердить и оплатить заказ на условиях данного предложения:</span>
<? } ?>
<br /><br />
<a style="text-align:center; background-color:#DEDEDE; color:#363636; border:1px solid #363636; font-size:14px; font-weight:700; padding:10px 30px; border-radius:5px;" href="<? echo $customer_confirm_url; ?>">
	<? if ($payment_will_be_done_on_delivery) { ?>
		Подтвердить заказ
	<? } else { ?>
		<? if (isset($only_full_payment) && $only_full_payment) { ?>
			Подтвердить и оплатить заказ
		<? } else { ?>
			Подтвердить и оплатить заказ полностью или частично
		<? } ?>
	<? } ?>
</a>
</div><br /><br />
<a href="<? echo $customer_confirm_url; ?>" target="_blank" style="font-size:10px;"><? echo $customer_confirm_url; ?></a>
<? } ?>