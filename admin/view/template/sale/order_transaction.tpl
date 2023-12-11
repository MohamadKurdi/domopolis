<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
	<thead>
		<tr>
			<th colspan="8" class="th_style"></th>
		</tr>
		
		<tr>
			<? if ($has_rights) { ?>
				<th colspan="3" class="bordSTYLE">Лицевой счет заказа</th>
				<? } else { ?>
				<th colspan="3" class="bordSTYLE">Лицевой счет заказа</th>
			<? } ?>
		</tr>
		<tr style="height: 5px;"></tr>
		<tr>
			<td class="left">Дата</td>
			<td class="left"><?php echo $column_description; ?></td>
			<td class="left">Источник</td>	 
			<td class="left">Касса</td>	 
			<td class="left">SMS</td>
			<td class="right">Сумма (<? echo $store_currency; ?>)</td>
			<? if ($has_rights) { ?>
				<td></td>
				<td></td>
			<? } ?>
		</tr>
	</thead>
	<tbody>
		<?php if ($transactions) { ?>
			<?php foreach ($transactions as $transaction) { ?>
				<tr>
					<td class="left"><span style="font-size:10px;"><?php echo $transaction['date_added']; ?></span></td>
					<td class="left" style="font-size:12px;"><?php echo $transaction['description']; ?></td>
					<td class="left" style="font-size:12px;">
						<? if ($transaction['added_from']) { ?><b><? echo $transaction['added_from']; ?></b><? } ?>
					</td>
					<td class="left" style="font-size:12px;">
						<? if ($transaction['legalperson_name_1C']) { ?><b><? echo $transaction['legalperson_name_1C']; ?></b><? } ?>
					</td>
					<td class="left"><? echo $transaction['sms_sent']; ?></td>
					<td class="right" style="white-space: nowrap;"><b id="transaction_text_<?php echo $transaction['transaction_id']; ?>"><?php echo $transaction['amount_national']; ?></b>
					<input type="text" style="display:none;" data-id="<?php echo $transaction['transaction_id']; ?>" id="transaction_amount_<?php echo $transaction['transaction_id']; ?>" name="transaction_amount_<?php echo $transaction['transaction_id']; ?>" value="<?php echo $transaction['amount_national_value']; ?>"></td>
					<? if ($has_rights) { ?>
						<td class="right" style="width:1px;"><a id="change-transaction" class="button save_button change-transaction" data-number="<?php echo $transaction['transaction_id']; ?>">Изм.</a><a id="change-transaction-real" class="button" style="display:none;" data-number="<?php echo $transaction['transaction_id']; ?>">ОК</a></td>
						
						<td class="right" style="width:1px;"><a id="delete-transaction" class="button save_button delete-transaction" data-number="<?php echo $transaction['transaction_id']; ?>">Удалить</a></td>
					<? } ?>
				</tr>
			<?php } ?>   
			<?php } else { ?>
			<tr>
				<td class="center" colspan="8">Пока нет транзакций</td>
			</tr>
		<?php } ?>
		<tr>
			<td>&nbsp;</td>	
			<td>&nbsp;</td>	 
			<td>&nbsp;</td>	 
			<td>&nbsp;</td>	 
			<td class="right"><b>Всего по заказу:</b></td>
			<td class="right"><b><?php echo $balance_national; ?></b></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>	 
			<td>&nbsp;</td>	 
			<td class="right"><b>На балансе покупателя:</b></td>
			<td class="right"><b><?php echo $customer_balance_national;  ?></b></td>
		</tr>	
		<tr>
			
			<?  if ($ifplus) { ?>
				<td>&nbsp;</td>	
				<td>&nbsp;</td>	
				<td class="right" style="color:red"><b>На текущий момент всего к оплате</b></td>
				<td class="right" style="color:red"><b><?php echo $current_difference; ?></b></td>
				<? } else { ?>
				<td>&nbsp;</td>	
				<td>&nbsp;</td>	
				<td class="right" style="color:green"><b>Переплата по заказу на текущий момент:</b></td>
				<td class="right"  style="color:green"><b><?php echo $current_difference; ?></b></td>
			<? } ?>
		</tr>
		
		<? if ($notequal) { ?>
			<tr>
				<td colspan='6' style="color:red; font-size:10px; padding-top:5px;">На данный момент остаточная сумма на счету покупателя <b>(<?php echo $customer_balance_national;  ?>)</b> не совпадает с суммой на счету заказа <b>(<?php echo $balance_national; ?>)</b>. При корректных проводках, они должны совпадать, <b>НО ТОЛЬКО, если в данный момент покупатель имеет один оформленный и обрабатываемый/незавершенный заказ</b>.</td>
			</tr>
		<? } ?>
		
	</tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>
