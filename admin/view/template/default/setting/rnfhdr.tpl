<?php if ($success) { ?>
	<span style="color:#00ad07; font-size:18px; font-weight: 700;">
		<i class="fa fa-check-circle"></i> Всё системы в норме. Осталось запросов: <?php echo $answer['account_info']['credits_remaining']; ?>
	</span>
<?php } else { ?>

	<?php if ($message == 'ZERO_CREDITS_AND_OVERAGE_IS_USED_NOW') { ?>

		<span style="color:#FF9243; font-size:14px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i>
			превышен лимит запросов в тарифе, и мы используем overage (ZERO_CREDITS_AND_OVERAGE_IS_USED_NOW)
			<small><i class="fa fa-info-circle"></i> система пока работает, но каждые 1000 запросов стоят 1$</small>
		</span>

	<?php } elseif ($message == 'CREDITS_LESS_THEN_5_PERCENT') { ?>

		<span style="color:#FF9243; font-size:14px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i>
			осталось менее 5% от лимита запросов, <?php echo $answer['account_info']['credits_remaining']; ?>
		</span>

	<?php } else { ?>
		<span style="color:#cf4a61; font-size:14px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i>
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

		</span>
	<? } ?>
<?php } ?>