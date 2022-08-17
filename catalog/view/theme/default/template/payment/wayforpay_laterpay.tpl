<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
	<head>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	</head>
	<body>
		<div style="display:none;">
			<form action="<?php echo $action ?>" method="post" id="wayforpay">
				<?php
					foreach ($fields as $k => $v) {
						if(is_array($v)){
							foreach ($v as $vv) {
								echo "<input type=\"hidden\" name=\"{$k}[]\" value=\"{$vv}\" />";
							}
							} else {
							echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />";
						}
						
					}
				?>
			</form>
			</div>
			
			<script>
				$(document).ready(function(){
				$("form#wayforpay").submit();	
			})
		</script>
	</body>
</html>