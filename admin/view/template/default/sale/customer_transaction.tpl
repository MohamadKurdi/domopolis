<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list" style="width:100%;">
	<thead>
		<thead>
			<tr>
				<th colspan="5" class="th_style"></td>
			</tr>
			<tr>
				<th colspan="3" class="bordSTYLE">Лицевой счет покупателя</td>
			</tr>
			<tr style="height: 5px;"></tr>
			<tr>
				<td class="left" width="1">Дата</td>
				<td class="left"><?php echo $column_description; ?></td>
				<td class="left">Источник</td>
				<td class="left">Касса</td>
				<td class="right"><? echo $store_currency; ?></td>
			</tr>
		</thead>
		<tbody>
			<?php if ($transactions) { ?>
				<?php foreach ($transactions as $transaction) { ?>
					<tr>
						<td class="left" style="font-size:10px;"><?php echo $transaction['date_added']; ?></td>
						<td class="left" style="font-size:10px;"><?php echo $transaction['description']; ?></td>
						<td class="left" style="font-size:12px;">
							<? if ($transaction['added_from']) { ?><b><? echo $transaction['added_from']; ?></b><? } ?>
						</td>
						<td class="left" style="font-size:12px;">
							<? if ($transaction['legalperson_name_1C']) { ?><b><? echo $transaction['legalperson_name_1C']; ?></b><? } ?>
						</td>
						<td class="right" style="font-size:10px;"><b><?php echo $transaction['amount_national']; ?></b></td>
					</tr>
				<?php } ?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>					
					<td class="right"><b><?php echo $text_balance; ?></b></td>
					<td class="right"><b><?php echo $balance_national; ?></b></td>
				</tr>
				<?php } else { ?>
				<tr>
					<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="pagination"><?php echo $pagination; ?></div>
