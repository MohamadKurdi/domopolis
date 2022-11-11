<div class="emailContent">{$emailtemplate.content1}</div>

<?php if(isset($account_approve) && $account_approve){ ?>
<br />
<div class="link">
	<?php echo $text_approve; ?><br />
	<span class="icon">&raquo;</span>
	<a href="<?php echo $account_approve; ?>" target="_blank">
		<b><?php echo $account_approve; ?></b>
	</a>
</div>
<?php } ?>

<div class="emailContent">{$emailtemplate.content2}</div>