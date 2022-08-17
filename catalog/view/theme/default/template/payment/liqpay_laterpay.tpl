
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
	<head>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	</head>
	<body>
		<div style="display:none;">
			<form method="POST" action="<?=$action?>" id="liqpay" accept-charset="utf-8">
				<input type="hidden" name="data"  value="<?=$data?>" />
				<input type="hidden" name="signature" value="<?=$signature?>" />
				<div class="buttons">
					<div class="right">
						<table class="pay"><tr><td><img src="/image/data/payment/liqpay_logo.png" /></td><td><input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" /></td></tr></table>
					</div>
				</div>
			</form>
		</div>
		
		<script>
			$(document).ready(function(){
				$("form#liqpay").submit();	
			})
		</script>
	</body>
</html>