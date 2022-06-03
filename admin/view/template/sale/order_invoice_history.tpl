<style>
	.view-cheque, .pdf-cheque, .pdf-cheque-a5, .print-cheque, .pdf-cheque-auto {cursor:pointer; color:#40A0DD;}
</style>
<table class="list">
	<thead>
		<tr>
			<th colspan="8">История сохраненных чеков по заказу</td>
		</tr>
		<tr>
			<td class="left" width="1"><b>Дата, время</b></td>
			<td class="left" style="text-align:center"><b>Код</b></td>
			<td class="left"><b>Пользователь</b></td>
			<td class="left"><b>См.</b></td>
			<td class="left"><b>Печать</b></td>
			<td class="center"><b>Авто А4/А5</b></td>
			<td class="center"><b>А4</b></td>
			<td class="center"><b>А5</b></td>
		</tr>
	</thead>
	<tbody>
		<?php if (isset($histories) && $histories) { ?>
			<?php foreach ($histories as $history) { ?>
				<tr id="history">
					<td class="left" style="font-size:10px;word-wrap:none;"><?php echo $history['datetime']; ?></td>
					<td class="left"  style="font-size:10px; text-align:center;"><b><?php echo $history['invoice_name']; ?></b></td>	  	  	  
					<td class="left"  style="font-size:10px;"><?php echo $history['realname']; ?><br /><?php echo $history['name']; ?> / <?php echo $history['user_id']; ?></td>
					
					<?php if ($history['forbidden']) { ?>
						
						<td class="left" colspan="5"><span style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> Запрещена работа с чеками, нужны повышенные права.<br /><i class="fa fa-exclamation-triangle"></i> Штраф за печать чека не из 1С - 1000 рублей и анальная пробка на два часа.</span></td>
						
						<?php } else { ?>
						
						<td class="left" style="font-size:20px; padding:0px 5px; text-align:center;">
							<span class="view-cheque" data-order_invoice-author="<?php echo $history['realname']; ?>" data-order_invoice-datetime="<?php echo $history['datetime']; ?>" data-order_invoice_id="<?php echo $history['order_invoice_id']; ?>"><i class="fa fa-eye"></i></span></td>
						<td class="left" style="font-size:20px; padding:0px 5px; text-align:center;">
							<span class="print-cheque" data-order_invoice_id="<?php echo $history['order_invoice_id']; ?>"><i class="fa fa-print"></i></span>
						</td>
						<td class="left" style="font-size:20px; padding:0px 5px; text-align:center;"><span class="pdf-cheque-auto" data-order_invoice_id="<?php echo $history['order_invoice_id']; ?>"><i class="fa fa-file-pdf-o"></i></span></td>
						<td class="left" style="font-size:20px; padding:0px 5px; text-align:center;"><span class="pdf-cheque" data-order_invoice_id="<?php echo $history['order_invoice_id']; ?>"><i class="fa fa-file-pdf-o"></i></span></td>
						<td class="left" style="font-size:20px; padding:0px 5px; text-align:center;"><span class="pdf-cheque-a5" data-order_invoice_id="<?php echo $history['order_invoice_id']; ?>"><i class="fa fa-file-pdf-o"></i></span></td>
					</tr>
				<?php } ?>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="center" colspan="8">Пока что нет данных</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<script>
setChequeViewTriggers();
</script>
