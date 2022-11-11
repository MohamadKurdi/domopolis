<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# product: https://ogp.me/ns/product#">
		<meta charset="UTF-8" />
		<title>Оплата заказа - Secured By PayKeeper</title>
		<?php if ($icon) { ?>
			<link href="<?php echo $icon; ?>" rel="icon" />
		<?php } ?>
		<link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' />
		<base href="https://kitchen-profi.ru/" />
		<style>
			body{
			background-image: url(https://kitchen-profi.ru/image/data/background/bg.gif);
			font-family: 'PT Sans',Helvetica,sans-serif;
			}
			.payment-wrap{
			margin: auto;
			display:inline-block;
			}
		</style>
	</head>
	<body style="text-align:center;">
		<div class="payment-wrap">
			<img src="<?php echo $logo; ?>" height="60" />&nbsp;&nbsp;
			<img src="catalog/view/image/payment/PayKeeper.png" alt="paykeeper logo" height="60" />
			<br /><br />
			<div id='tmg_paykeeper_form'>
				<?php echo $paykeeper_form; ?>
			</div>
		</div>
	</body>
</html>
