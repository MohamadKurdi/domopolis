<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# product: https://ogp.me/ns/product#">
	<meta charset="UTF-8" />
	<title>Оплата заказа - Secured By PayKeeper</title>
	<link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' />		
	<style>
		body{
			font-family: 'PT Sans',Helvetica,sans-serif;
		}
		.payment-wrap{
			margin: auto;
			display:inline-block;
		}
		.payment-wrap #tmg_paykeeper_form .tmg{
			display: flex;
			flex-direction: column;
			align-items: center;
			background: #FFFFFF;
			box-shadow: 0px 19px 41px rgba(0, 0, 0, 0.42);
			border-radius: 12px;
			padding: 20px;
		}
		.payment-wrap #tmg_paykeeper_form .tmg h3{
			font-size: 18px;
			margin: 0 0 10px 0;
		}
		.payment-wrap #tmg_paykeeper_form .tmg > p{
			font-size: 14px;
		}
		.payment-wrap  .tmg_ps_payment_option input{
			display: none;
		}
		.tmg #tmg_ps_body .tmg_ps_payment_option {
			display: flex;
			align-items: center;
			flex-direction: row-reverse;
			gap: 20px;
		}
		.tmg .tmg_ps_paysys{
			position: unset !important;
			margin: 0 !important;      
		}
		.payment-wrap .tmg #tmg_ps_body .tmg_ps_paysys_desc h3{
			margin-top: 0 !important;
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
