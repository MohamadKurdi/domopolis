<style>
	.list.big1 thead td{font-size:14px; font-weight:700;}
	.list.big1 tbody td{font-size:16px;padding: 5px 3px;}	
	.list tbody td a{text-decoration: none; color: gray;}
</style>

<div class="dashboard-heading"><i class="fa fa-amazon"></i> Rainforest API</div>
<div class="dashboard-content">
	<div style="margin-bottom: 10px;">
		<?php if ($success) { ?>
			<span style="color:#00ad07; font-size:18px; font-weight: 700;"><i class="fa fa-check-circle"></i> судя по ответу от Rainforest, всё работает
				<br />
				<small><i class="fa fa-info-circle"></i> скрипты крутятся, лавеха мутится</small>
			</span>
		<?php } else { ?>

			
			<?php if ($message == 'ZERO_CREDITS_AND_OVERAGE_IS_USED_NOW') { ?>

				<span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> есть ньюансы:
				превышен лимит запросов в тарифе, и мы используем overage (ZERO_CREDITS_AND_OVERAGE_IS_USED_NOW)
				<br />
				<small><i class="fa fa-info-circle"></i> система пока работает, но каждые 1000 запросов стоят 1$</small>
				</span>
			
			<?php } elseif ($message == 'CREDITS_LESS_THEN_10_PERCENT') { ?>

				<span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> есть ньюансы:
				осталось менее 10% от лимита запросов (CREDITS_LESS_THEN_10_PERCENT)
				<br />
				<small><i class="fa fa-info-circle"></i> система пока работает, но скоро закончится лимит запросов</small>
				</span>

			<?php } else { ?>
				<span style="color:#cf4a61; font-size:18px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> есть сложности:

					<?php if ($message == 'PAYMENT_FAIL') { ?>
						нет оплаты по тарифному плану, либо сломалось API (PAYMENT_FAIL)
					<?php } ?>

					<?php if ($message == 'JSON_DECODE') { ?>
						не удалось разобрать ответ, сломалось API (JSON_DECODE)
					<?php } ?>

					<?php if ($message == 'NO_ACCOUNT_INFO') { ?>
						не удалось разобрать ответ, сломалось API (NO_ACCOUNT_INFO)
					<?php } ?>

					<?php if ($message == 'ZERO_CREDITS_AND_OVERAGE_NOT_ENABLED') { ?>
						превышен лимит запросов в тарифе, и отключен overage (ZERO_CREDITS_AND_OVERAGE_NOT_ENABLED)
					<?php } ?>

					<?php if ($message == 'ZERO_CREDITS_AND_OVERAGE_OVERLIMIT') { ?>
						превышен лимит запросов в тарифе, и превышен overage (ZERO_CREDITS_AND_OVERAGE_OVERLIMIT)
					<?php } ?>


					<br />
					<small><i class="fa fa-info-circle"></i> система не работает. скрипты не крутятся, лавеха не мутится</small>

				</span>
			<? } ?>
		<?php } ?>
	</div>

	<?php if (!empty($answer)) { ?>
		<div style="width:49%; float:left;">
			<table class="list big1">
				<tr>
					<td style="color:#66c7a3">
						<b>Тариф</b>
					</td>
					<td>
						<b><?php echo $answer['account_info']['plan']; ?></b>
					</td>
				</tr>
				<tr>
					<td style="color:#3276c2">
						Лимит
					</td>
					<td>
						<?php echo $answer['account_info']['credits_limit']; ?>
					</td>
				</tr>
				<tr>
					<td style="color:#fa4934">
						Использовано
					</td>
					<td>
						<?php echo $answer['account_info']['credits_used']; ?>
					</td>
				</tr>
				<tr>
					<td style="color:#7f00ff">
						Осталось
					</td>
					<td>
						<?php echo $answer['account_info']['credits_remaining']; ?>
					</td>
				</tr>
				<tr>
					<td style="color:#fa4934">
						До
					</td>
					<td>
						<?php echo date('Y-m-d', strtotime($answer['account_info']['credits_reset_at'])); ?>
					</td>
				</tr>
				<tr>
					<td style="color:#66c7a3">
						Overage разрешен
					</td>
					<td>
						<?php echo $answer['account_info']['overage_allowed']?'Да':'Нет'; ?>
					</td>
				</tr>
				<tr>
					<td style="color:#3276c2">
						Overage включён
					</td>
					<td>
						<?php echo $answer['account_info']['overage_enabled']?'Да':'Нет'; ?>
					</td>
				</tr>
				<tr>
					<td style="color:#fa4934">
						Запросов overage
					</td>
					<td>
						<?php echo $answer['account_info']['overage_limit']; ?>
					</td>
				</tr>
				<tr>
					<td style="color:#7f00ff">
						Использовано
					</td>
					<td>
						<?php echo $answer['account_info']['overage_used']; ?>
					</td>
				</tr>
			</table>
		</div>

		<div style="width:49%; float:right;">
			<table class="list small">
				<thead>
					<tr>
						<td>
							<b>Rainfores API</b>
						</td>
						<td>
							<b>Статус</b>
						</td>
					</tr>
				</thead>
				<?php foreach ($answer['account_info']['status'] as $rnf_system) { ?>
					<tr>
						<td>
							<?php echo $rnf_system['component']; ?>
						</td>
						<td style="color: <?php if ($rnf_system['status'] == 'operational') { ?>#00AD07<? } else { ?>#CF4A61<?php } ?>">
							<?php echo $rnf_system['status']; ?>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	<?php } ?>
	<div>


