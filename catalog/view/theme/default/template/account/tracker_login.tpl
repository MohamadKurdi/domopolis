<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<?php include(dirname(__FILE__).'/../structured/breadcrumbs.tpl'); ?>
<div id="content" class="order-login-page">
	<div class="wrap">
	<?php echo $content_top; ?>
	<div class="login-content">
				
        <style>
			.content {font-size: 16px; padding: 3% 0 5% 20%!important;}
			.content a{font-size: 16px; text-decoration: none;}
			.content a:hover{text-decoration: underline;}
			.content b {padding-bottom: 5px; display: inline-block;}
            .content .login-entry-field {padding: 10px; font-size:large; width: 67%;}
			.checkbox{vertical-align:top;margin:0 3px 0 0;width:17px;height:17px}
			.checkbox + label{cursor:pointer}
			.checkbox:not(checked){position:absolute;opacity:0}
			.checkbox:not(checked) + label{position:relative;padding:0 0 0 40px}
			.checkbox:not(checked) + label:before{content:'';position:absolute;left:0;top:0;width:35px;height:17px;border-radius:13px;background:#CDD1DA;box-shadow:inset 0 2px 3px rgba(0,0,0,.2)}
			.checkbox:not(checked) + label:after{content:'';position:absolute;top:2px;left:2px;width:13px;height:13px;border-radius:10px;background:#FFF;box-shadow:0 2px 5px rgba(0,0,0,.3);transition:all .2s}
			.checkbox:checked + label:before{background:#9FD468}
			.checkbox:checked + label:after{left:19px}
			.checkbox:focus + label:before{box-shadow:0 0 0 3px rgba(255,255,0,.5)}
            .forgotten-text {margin-right: 33%; float: right;}
			@media (max-width: 790px) {
            .forgotten-text {float: none;white-space:nowrap}
			
            }
		</style>
		<form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">
			<div class="content simplecheckout-customer-right" style="font-size: 16px;    padding: 3% 0 5% 20%;">
				
				<b>Номер заказа</b><br />
				<input type="text" class="login-entry-field field" name="order_id" value="<?php echo $order_id; ?>" placeholder="Номер заказа, например 112434"/>
				<br /><br />
				<b>Ваш e-mail, номер дисконтной карты или телефон</b><br />
				<input type="text" class="login-entry-field field" name="auth" value="<?php echo $auth; ?>" placeholder="Ваш e-mail, номер дисконтной карты или телефон" />
				<br />								
				<br />
				<?php if ($error_warning) { ?>
					<div class="warning" style="color: red"><?php echo $error_warning; ?></div>
				<?php } ?>
				<input type="submit" value="Проверить заказ" class="button btn btn-acaunt" />
			</div>
			
		</form>
		<!--/div-->
	</div>
<?php echo $content_bottom; ?></div></div>
<script type="text/javascript"><!--
	$('#login input').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#login').submit();
		}
	});
//--></script> 
<?php echo $footer; ?>