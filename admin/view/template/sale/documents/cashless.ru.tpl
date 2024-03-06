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
	
	<table style="width:100%;">
		<tr>
			<td align="left" style="font-size:20px; font-weight:700;">
				Счет на оплату № <? echo $invoice_no; ?> - <? echo $order['order_id'] ?> от <? echo ($order['invoice_date'] == '0000-00-00')?date('d.m.Y'):date('d.m.Y', strtotime($order['invoice_date'])); ?>
			</td>
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<table style="width:100%;">
		<tr>
			<td align="left" width="150px" style="font-size:16px; width:150px;">
				<b>Поставщик:</b>
			</td>
			<td style="width:70%">
				<? echo $legalperson['legalperson_desc']; ?>			
			</td>
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<table style="width:100%;" class="top_header_table">
		<tr>
			<td colspan="4" style="width:40%; text-align:left;" class="noborder-bottom"><? echo $legalperson['BANK_BANK']; ?></td>
			<td rowspan="2" style="vertical-align:top; width:1px;  white-space:nowrap;">БИК</td>
			<td style="width:60%" class="noborder-bottom"><? echo $legalperson['BANK_BIK']; ?></td>
		</tr>
		<tr>
			<td colspan="4" class="noborder-top-bottom"></td>
			<td class="noborder-top-bottom"></td>
		</tr>
		<tr>
			<td colspan="4" class="noborder-top-bottom"><? echo $legalperson['BANK_CITY']; ?></td>
			<td rowspan="2" style="vertical-align:top; width:1px; white-space:nowrap">Счет №</td>
			<td class="noborder-bottom"><? echo $legalperson['BANK_ACCOUNT']; ?></td>
		</tr>
		<tr>
			<td colspan="4" class="noborder-top">Банк получателя</td>
			<td class="noborder-top"></td>
		</tr>
		<tr>
			<td>ИНН</td>
			<td colspan="3"><? echo $legalperson['USER_INN']; ?></td>			
			<td rowspan="5" style="vertical-align:middle; width:1px; white-space:nowrap">Счет №</td>
			<td rowspan="5" style="vertical-align:middle; width:1px; white-space:nowrap"><? echo $legalperson['USER_ACCOUNT']; ?></td>
		</tr>
		<tr>
			<td colspan="4" class="noborder-top-bottom"></td>
		</tr>
		<tr>
			<td colspan="4" class="noborder-top-bottom"><? echo $legalperson['legalperson_desc']; ?></td>
		</tr>
		<tr>
			<td colspan="4" class="noborder-top-bottom"></td>
		</tr>
		<tr>
			<td colspan="4" class="noborder-top">Получатель</td>
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<table style="width:100%;">
		<tr>
			<td align="left" width="150px" style="font-size:16px; width:150px;">
				<b>Покупатель:</b>		
			</td>
			<td style="width:70%">
				<? echo str_replace(PHP_EOL, '<br />', $customer['cashless_info']); ?>	
			</td>
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	
	<table style="width:100%;" class="product">
		<tr class="heading">
			<td align="center" style="width:1px; text-align:center; word-wrap: normal;"><b>№</b></td>
			<td align="center" style="width:1px; text-align:center; word-wrap: normal;"><b>Артикул</b></td>  
			<td align="center" colspan="1" style="text-align:center; word-wrap: normal;"><b>Наименование</b></td>
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal;"><b>Кол-во</b></td>
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal;"><b>Ед. изм</b></td> 
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal" ><b>Цена</b></td>
			<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal" ><b>Сумма</b></td>
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
					<?php echo $product['quantity']; ?>
				</td>
				<td style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:5px;">
					шт.
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
				<b>Итого к оплате:</b>
			</td>
			<td style="width:1px; text-align:right; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:4px;">
				<b><?php echo number_format( $total, 2, ',', ' '); ?></b>
			</td>					
		</tr>
		<tr>	
			<td colspan="4" class="noborder-top-bottom noborder-left"></td>
			<td colspan="2" style="text-align:right; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:5px;">
			<b>Без НДС:</b>
			</td>
			<td style="width:1px; text-align:right; white-space: nowrap; word-wrap: normal; padding-left:5px;padding-right:4px;">
				<b>0,00</b>
			</td>					
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<table style="width:100%;">
		<tr>
			<td align="left" style="font-size:16px; font-weight:400;">
				<? $a = explode(',', number_format( $total, 2, ',', ' ') ); ?>
				Всего наименований <? echo $total_quantity; ?> на сумму <? echo number_format( str_replace(' ', '', $a[0]), 0, ',', ' '); ?> <? echo $morped_currency; ?>, <? echo $a[1]; ?> копеек					
			</td>
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<table style="width:100%;">
		<tr>
			<td align="left" style="font-size:16px; font-weight:400;">
				<b><? echo $total_txt; ?></b>
			</td>
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<table style="width:100%;">
		<tr>
			<td align="left" width="150px" style="font-size:16px; width:150px;">
				<b>Условия оплаты:</b> Предоплата
			</td>
		</tr>
	</table>
	<div style="clear:both; height: 10px;"></div>
	<div>			
		<table style="width:100%;">
			<tr>
				<td style="height:1px;" height="1px"></td>
				<td></td>
				<td rowspan="4" style="width:200px;" width="200px">
					<? if (!$noprint && $stamp) { ?><img src="<? echo HTTPS_SERVER . 'view/image/print/' . $stamp; ?>" style="" width="200px" height="200px" /><? } ?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td align="left" width="150px" style="vertical-align:bottom; font-size:16px; width:150px; white-space: nowrap; word-wrap: normal;">
					Генеральный директор
				</td>
				<td align="left" style="vertical-align:bottom; font-size:16px; white-space: nowrap; word-wrap: normal;  width:120px;">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
				<td align="left" style="vertical-align:bottom; font-size:16px; white-space: nowrap; word-wrap: normal;">
					(<? echo $legalperson['USER_SIGN']; ?>)
				</td>
			</tr>
			<tr>
				<td style="height:1px;" height="1px"></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="left" width="150px" style="vertical-align:top; font-size:16px; white-space: nowrap; word-wrap: normal;">
					Главный бухгалтер
				</td>
				<td align="left" style="vertical-align:top; font-size:16px; white-space: nowrap; word-wrap: normal; width:120px;">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
				<td align="left" style="vertical-align:top; font-size:16px; white-space: nowrap; word-wrap: normal;">
					(<? echo $legalperson['USER_SIGN']; ?>)
				</td>
			</tr>
		</table>
		
		<table style="width:100%;">
			<tr>
				
			</tr>
			<tr>
				
			</tr>
		</table>
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
</div>			