<style>
	.list.big1 thead td{font-size:14px; font-weight:700;}
	.list.big1 tbody td{font-size:16px;padding: 5px 3px;}	
	.list tbody td a{text-decoration: none; color: gray;}
</style>

<div class="dashboard-heading"><i class="fa fa-amazon"></i> Rainforest API</div>
<div class="dashboard-content" style="min-height: 360px;">
	<div style="margin-bottom: 10px;">
		<?php if ($success) { ?>
			<span style="color:#00ad07; font-size:18px; font-weight: 700;"><i class="fa fa-info-circle"></i> Rainforest API говорит что всё ок
				<br />
				<i class="fa fa-check-circle"></i> скрипты крутятся, лавеха мутится
			</span>
		<?php } else { ?>			
			<?php if ($message == 'ZERO_CREDITS_AND_OVERAGE_IS_USED_NOW') { ?>

				<span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> есть ньюансы:
				превышен лимит запросов в тарифе, и мы используем overage (ZERO_CREDITS_AND_OVERAGE_IS_USED_NOW)
				<br />
				<small><i class="fa fa-info-circle"></i> система пока работает, но каждые 10000 запросов стоят 8.5$</small>
				</span>
			
			<?php } elseif ($message == 'CREDITS_LESS_THEN_5_PERCENT') { ?>

				<span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> есть ньюансы:
				осталось менее 5% от лимита запросов (CREDITS_LESS_THEN_5_PERCENT)
				<br />
				<small><i class="fa fa-info-circle"></i> система пока работает, но скоро закончится лимит запросов</small>
				</span>

			<?php } else { ?>
				<span style="color:#cf4a61; font-size:18px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> есть сложности:

					<?php if ($message == 'CODE_NOT_200_MAYBE_PAYMENT_FAIL') { ?>
						нет оплаты по тарифному плану, либо сломалось API (CODE_NOT_200_MAYBE_PAYMENT_FAIL)
						<br />
						<small><i class="fa fa-info-circle"></i> <?php echo $answer; ?></small>
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

					<br />
					<small><i class="fa fa-info-circle"></i> узнать статус api можно тут: <a href="https://rainforestapi.statuspage.io/" target="_blank">https://rainforestapi.statuspage.io/</a></small>

					<?php if (!empty($debug)) { ?>
						<pre><?php echo $debug; ?></pre>
					<?php } ?>

				</span>
			<? } ?>
		<?php } ?>
	</div>

	<?php if (!empty($answer) && is_array($answer)) { ?>
		<div style="width:30%; float:left;  margin-right:5px;">
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
						<?php if ($answer['account_info']['overage_allowed']) { ?>
							<i class="fa fa-check-circle" style="color:#4ea24e"></i>
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
					</td>
				</tr>
				<tr>
					<td style="color:#3276c2">
						Overage включён
					</td>
					<td>
						<?php if ($answer['account_info']['overage_enabled']) { ?>
							<i class="fa fa-check-circle" style="color:#4ea24e"></i>
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
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

		<div style="width:30%; float:right;">
			<table class="list small">
				<thead>
					<tr>
						<td>
							<b>API Endpoint</b>
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


		<div style="width:30%; float:right; margin-right:5px;">
			<table class="list big1">
				<thead>
					<tr>
						<td class="center">
							<b>Domain</b>
						</td>
						<td class="center">
							<b>ZipCode</b>
						</td>
						<td class="center">
							<i class="fa fa-check-circle" style="color:#4ea24e"></i>
						</td>
						<td class="center">
							<b>Status</b>
						</td>
					</tr>
				</thead>
				<?php foreach ($zipcodes['zipcodes'] as $domain => $list) { ?>
					<? if ($list) { ?>	
						<? foreach ($list as $zipcode) { ?>				
							<tr>
								<td class="center">
									<?php echo $domain; ?>
								</td>
								<td class="center">
									<?php echo $zipcode['zipcode']; ?>
								</td>
								<td class="center">
									<?php if (in_array($zipcode['zipcode'], $active_zipcodes)) { ?>
										<i class="fa fa-check-circle" style="color:#4ea24e"></i>
									<?php } else { ?>
										<i class="fa fa-times-circle" style="color:#cf4a61"></i>
									<? } ?>
								</td>
								<td class="center" style="color: <?php if ($zipcode['status'] == 'available') { ?>#00AD07<? } else { ?>#CF4A61<?php } ?>">
									<?php echo $zipcode['status']; ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</table>
		</div>
	<?php } ?>
	<div>


