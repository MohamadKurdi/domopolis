<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Конверт для <? echo $name; ?></title>		
		<style>
			body {font-family:Arial;}
			.tbl tr td{min-height:50px;}
		</style>
	</head>
	<body style="height:90mm; width:210mm;">		
		<table width="100%" height="100%" style="border:0">
			<tr>
				<td style="width:55%; padding:2mm 0 0 5mm; border:0; font-size:16px; vertical-align:top;" valign="top">
					<? if ($country_id == 176) { ?>
						ИП САРМИН<br />
						Ул. 3-е почтовое отделение 58-54<br />				
						Московская обл.<br />
						140003 г. Люберцы<br />
						<? } elseif($country_id == 220) { ?>
						Китчен Профи<br />
						ул. Срибнокильская 3-В - 103<br />
						02095 Киев<br />
					<? } ?>
				</td>
				<td style="width:40%; padding:0 10mm 5mm 0; border:0; word-wrap: normal; word-break: normal; font-size:16px; vertical-align:bottom;" valign="bottom">
					<? echo $name; ?><br />
					<? echo $company?$company.'<br />':''; ?>
					<? echo $address1; ?><br />
					<? echo $address2?$address2.'<br />':''; ?>
					
					<? if ('г. ' . $city == $zone) { ?>
						
						<? echo $postcode; ?>&nbsp;<? echo $city; ?>	
						
						<? } else { ?>
						
						<? echo $zone?$zone.'<br />':''; ?>
						<? echo $postcode; ?>&nbsp;<? echo $city; ?>
						
					<? } ?>
				</td>
			</tr>
		</table>
	</body>
</html>	