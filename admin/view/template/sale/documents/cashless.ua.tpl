<style>
	table.address tr td{padding:1mm;border:0px;}
	table.top_header_table{border-collapse: collapse}
	table.top_header_table tr td {padding:3px;border:1px solid black; border-collapse:collapse; background:#FFF;}
	table.top_header_table tr td.noborder-top-bottom {border-top:0px; border-bottom:0px; height:10px;}
	table.top_header_table tr td.noborder-bottom {border-bottom:0px;}
	table.top_header_table tr td.noborder-top {border-top:0px;}
	
	table.product {border-collapse: collapse}
	table.product tr td.noborder-top-bottom {border-top:0px; border-bottom:0px;}
	table.product tr td.noborder-bottom {border-bottom:0px;}
	table.product tr td.noborder-top {border-top:0px;}
	table.product tr td.noborder-left {border-left:0px;}
	table.product tr td.noborder-right {border-right:0px;}
	table.product tr td{padding:3px;border:1px solid black;border-collapse: collapse; background:#FFF;}
	table.product tr.heading td{text-align:center;}
</style>
<? 
	$pagebreaks = array();
	if (count($order_products) >= 20){
		
		for ($i=1; $i<=count($order_products); $i++){
			if ($i%20==0){
				$pagebreaks[$i] = true;
			}
		}
	}		
?>
<div style="width:210mm; padding:10px; margin:0 auto;">
	
	<table style="width:100%;" class="top_header_table">
		<tr>
			<td align="left" style="" width="50%">
				<b>Постачальник:</b> 
				
				<? if ($legalperson) { ?>
					<? echo str_replace(PHP_EOL, '<br />', $legalperson['legalperson_desc']); ?>
					<? } else { ?>
					СПД ДОМБРОВСЬКИЙ ОЛЕГ БОРИСОВИЧ<br />
					Код ЄДРПОУ:  2754119573<br />
					р/с. № 26007052605020<br />
					Банк: ПАТ КБ «Приват Банк»<br />
					МФО: 300711
				<? } ?>
			</td>
			<td align="center" valign="top" style="vertical-align:top;">
				<table class="address" style="width:100%;" >
					<tr>
						<td align="center" style="border:0px; font-size:20px; font-weight:700; padding-bottom:10px;">РАХУНОК-ФАКТУРА</td>
					</tr>
					<tr>
						<td  align="center" style="border:0px; padding-bottom:10px;">№ <? echo $invoice_no; ?></td>
					</tr>
					<tr>
						<td  align="center" style="border:0px; padding-bottom:10px;">від <? echo ($order['invoice_date'] == '0000-00-00')?date('d.m.Y'):date('d.m.Y', strtotime($order['invoice_date'])); ?></td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
		<td align="left" colspan="2">
		<table style="width:100%;">
					<tr>
						<td align="left" style="border:0px; font-weight:700; padding-bottom:5px;">Платник:</td>
						<td align="left" style="border:0px;  font-weight:400; padding-bottom:5px;"><? echo str_replace(PHP_EOL, '<br />', $customer['cashless_info']); ?>	</td>
					</tr>					
				</table>			
			</td>		
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<table style="width:100%;" class="product">
		<tr class="heading">
			<td align="center" style="width:1px; text-align:center; word-wrap: normal;"><b>№</b></td>
			<td align="center" style="width:1px; text-align:center; word-wrap: normal;"><b>Артикул</b></td>  
			<td align="center" colspan="1" style="text-align:center; word-wrap: normal;"><b>Найменування</b></td>
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal;"><b>Од. вим.</b></td> 
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal;"><b>Кіл-сть</b></td>		
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal" ><b>Ціна</b></td>
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal" ><b>Сума</b></td>
		</tr>
		<?php $i=0; foreach ($order_products as $product) { $i++; ?>
			<tr>
				<td style="text-align:center;"><?php echo $i; ?></td>
				<td style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:5px;">
					<?php echo $product['model']; ?>
				</td>
				<td>
					<?php echo $product['name']; ?>
				</td>
				<td style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:5px;">
					шт.
				</td>
				<td style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:5px;">
					<?php echo $product['quantity']; ?>
				</td>
				<td style="width:1px; text-align:right; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:4px;">
					<?php echo number_format( $product['pricewd_national'], 2, ',', ' '); ?>
				</td>
				<td style="width:1px; text-align:right; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:4px;">
					<?php echo number_format( $product['totalwd_national'], 2, ',', ' '); ?>
				</td>
			</tr>
		<?php } ?>
		<tr>	
			<td colspan="4" class="noborder-bottom noborder-left"></td>
			<td colspan="2" style="text-align:right; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:5px;">
				<b>Всього:</b>
			</td>
			<td style="width:1px; text-align:right; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:4px;">
				<b><?php echo number_format( $total, 2, ',', ' '); ?></b>
			</td>					
		</tr>	
	</table>
	<div style="clear:both; height: 20px;"></div>
	<table style="width:100%;">
		<tr>
			<td align="left" style="font-size:16px; font-weight:400;">
				<b>Сума до оплати:</b> <? echo $total_txt; ?>.  Без ПДВ					
			</td>
		</tr>
	</table>
	<div style="clear:both; height: 50px;"></div>
	<table style="width:100%;">
		<tr>
			<td></td>
			<td align="right" valign="bottom" style="height:78px; width:200px;text-align:right;vertical-align:bottom; border-bottom:1px solid black;">
				<? if (!$noprint && $stamp) { ?>
					<img src="<? echo HTTPS_SERVER . 'view/image/print/' . $stamp; ?>" style="padding-right:10px; z-index:150;" height="70px;" />
				<? } ?>
			</td>
			<td style="width:1px; text-align:right; white-space: nowrap; word-wrap: normal; vertical-align:bottom;" >&nbsp;&nbsp;<? if ($legalperson) { ?><? echo $legalperson['legalperson_name']; ?><? } else { ?>СПД Домбровський Олег Борисович<? } ?></td>
		</tr>			
	</table>
</div>			