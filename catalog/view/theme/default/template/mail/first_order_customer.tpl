<div class="emailContent">{$emailtemplate.content1}</div>

<?php if ($customer_id && isset($order_url)) { ?>
<br />
<div class="link">
	<b><?php echo $text_update_link; ?></b><br />
	<span>&raquo;</span>
	<a href="<?php echo $order_url_tracking; ?>" target="_blank">
		<b><?php echo $order_url; ?></b>
	</a>
</div>
<?php } ?>

<?php if($comment){ ?>
<br />
<b><?php echo $text_update_comment; ?></b><br />
<?php echo $comment; ?>
<br /><br />
<?php } ?>

<br />
<div class="emailContent">{$emailtemplate.content2}</div>