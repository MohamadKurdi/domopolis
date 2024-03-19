<div class="emailContent">{$emailtemplate.content1}</div>

<?php if(!empty($comment)){ ?>
	<br />
	<strong><?php echo $text_comment; ?></strong><br />
	<?php echo $comment; ?><br />
<?php } ?>

<?php if(!empty($return_product)){ ?>
	<br />	
	<div class="heading2"><?php echo $text_product; ?></div>
	
	<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="15">&nbsp;</td></tr></table>

	<table cellpadding="0" cellspacing="0" width="100%" class="table1">
	<thead>
		<tr>
	        <th align="center" bgcolor="#ededed" colspan="2">
	        	<?php echo $text_product_name; ?>
	        </th>
		</tr>
	</thead>
	<tbody>
		<?php $row_style_background = "#f6f6f6"; ?>
	    <tr>
			<td bgcolor="<?php echo $row_style_background; ?>" valign="top" colspan="2">
				<?php echo $return_product['name']; ?>
				
				<?php if(isset($return_product['model']) || isset($return_product['quantity'])){ ?>
				<p class="list-product-options">
					<?php if(isset($return_product['model'])){ ?>
						&raquo; <strong><?php echo $text_model; ?>:</strong>
							<?php echo $return_product['model']; ?><br />
					<?php } ?>
					<?php if(isset($return_product['quantity'])){ ?>
						&raquo; <strong><?php echo $text_quantity; ?>:</strong>
							<?php echo $return_product['quantity']; ?><br />
					<?php } ?>
				</p>
				<?php } ?>
			</td>
		</tr>
		<?php $row_style_background = "#fafafa"; ?>
		<tr>
			<td bgcolor="<?php echo $row_style_background; ?>" valign="top"><b><?php echo $text_reason; ?></b></td>
			<td bgcolor="<?php echo $row_style_background; ?>" valign="top"><?php echo $return_product['reason']; ?></td>
		</tr>
		<?php $row_style_background = "#f6f6f6"; ?>
		<tr>
			<td bgcolor="<?php echo $row_style_background; ?>" valign="top"><b><?php echo $text_opened; ?></b></td>
			<td bgcolor="<?php echo $row_style_background; ?>" valign="top"><?php echo $return_product['opened']; ?></td>
		</tr>
	</tbody>
	</table>
<?php } ?>

<?php if(!empty($return_link)){ ?>
<br /><br />
<div class="link">
	<?php echo $text_action; ?>:<br />
	<span class="icon">&raquo;</span>
	<a href="<?php echo $return_link; ?>" target="_blank">
		<b><?php echo $return_link; ?></b>
	</a>
</div>
<?php } ?>

<div class="emailContent">{$emailtemplate.content2}</div>