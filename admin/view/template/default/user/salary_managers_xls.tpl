<html><head><meta charset="UTF-8"></head><body>
	<table class="list" style="width:100%">		
		<tr>
			<td></td>
			<td></td>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td><td></td><td></td>
			<? } ?>
			<td></td><td></td><td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td><td></td><td></td>
			<? } ?>
			<td></td><td></td><td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td><td></td><td></td>
			<? } ?>
			<td></td><td></td><td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td><td></td><td></td>
			<? } ?>
			<td></td><td></td><td></td>
		</tr>		
		<tr>
			<td>
				Заказ №
			</td>
			<td>
				Валюта
			</td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td>
					<? echo $manager['name']; ?>
				</td>
				<td></td>
				<td></td>
			<? } ?>	
			<td>
				Итого по мес.
			</td>
			<td>
				Выплачено по мес.
			</td>
			<td>
				К выплате по мес.
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td>
					Итого
				</td>
				<td>
					Уже выплачено
				</td>
				<td>
					К оплате
				</td>
			<? } ?>
			<td></td><td></td><td></td>
		</tr>
		<? $ttl = count($data_by_countries); $cntr = 1; ?>
		<?  foreach ($data_by_countries as $country => &$orders) { ?>		
			<tr>
				<td>
					<? echo $country; ?>
				</td>
				<td>				
				</td>
				<? unset($manager); ?>
				<? foreach ($data_by_managers as $manager) { ?>
					<td></td><td></td><td></td>
				<? } ?>
				<td></td><td></td><td></td>
			</tr>
			<? asort($orders);  foreach ($orders as $order) { ?>
				<tr>
					<td>
						<? echo $order['order_id']; ?>
					</td>
					<td>
						<? echo $order['currency_code']; ?>
					</td>
					<? unset($manager); ?>
					<? foreach ($data_by_managers as $manager) { ?>
						<td>
							<? if ($order['manager_id'] == $manager['user_id']) { ?>
								<? echo number_format($order['total'], 0, '.', ''); ?>
								<? } else { ?>
								<? echo number_format(0, 0, '.', ''); ?>
							<? } ?>
						</td>
						<td>
							<? if ($order['manager_id'] == $manager['user_id']) { ?>
								<? echo number_format($order['already_paid'], 0, '.', ''); ?>
								<? } else { ?>
								<? echo number_format(0, 0, '.', ''); ?>
							<? } ?>
						</td>
						<td>
							<? if ($order['manager_id'] == $manager['user_id']) { ?>
								<? echo number_format($order['left_to_pay'], 0, '.', ''); ?>
								<? } else { ?>
								<? echo number_format(0, 0, '.', ''); ?>
							<? } ?>
						</td>
					<? } ?>
					
					<? /* if ($cntr <= $ttl) { ?>
						<? $incntr = 1; foreach ($general_totals_by_countries as $gtcountry => $gtbc) { ?>
							<? if ($incntr == $cntr) { ?>
								<td>
									<? echo $gtbc['total_text']; ?>	
								</td>
								<td>
									<? echo $gtbc['already_paid_text']; ?>
								</td>
								<td>
									<? echo $gtbc['left_to_pay_text']; ?>										
								</td>
								<td>
									<? echo $gtcountry; ?>
								</td>
							<? } ?>
							<? $incntr++; ?>
						<? } ?>	
						<? $cntr++; ?>
					<? } */ ?>				
				</tr>
			<? } ?>
			<tr>
				<td>
					Итого
				</td>
				<td>
					<? echo $order['currency_code']; ?>
				</td>
				<? unset($manager); ?>
				<? foreach ($data_by_managers as $manager) { ?>
					<td>
					<? echo isset($manager['count_data']['cstats']['total_good_sum'][$country])?$manager['count_data']['cstats']['total_good_sum'][$country]['total_text']:number_format(0, 0, '.', ''); ?>
				</td>
				<td>
					<? echo isset($manager['count_data']['cstats']['total_good_sum'][$country])?$manager['count_data']['cstats']['total_good_sum'][$country]['already_paid_text']:number_format(0, 0, '.', ''); ?>
				</td>
				<td>
					<? echo isset($manager['count_data']['cstats']['total_good_sum'][$country])?$manager['count_data']['cstats']['total_good_sum'][$country]['left_to_pay_text']:number_format(0, 0, '.', ''); ?>
				</td>
				<? } ?>
				<td>
					<? echo isset($general_totals_by_countries[$country]['total_text'])?$general_totals_by_countries[$country]['total_text']:number_format(0, 0, '.', ''); ?>	
				</td>
				<td>
					<? echo isset($general_totals_by_countries[$country]['already_paid_text'])?$general_totals_by_countries[$country]['already_paid_text']:number_format(0, 0, '.', ''); ?>	
				</td>
				<td>
					<? echo isset($general_totals_by_countries[$country]['left_to_pay_text'])?$general_totals_by_countries[$country]['left_to_pay_text']:number_format(0, 0, '.', ''); ?>	
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
				</td>
				<? unset($manager); ?>
				<? foreach ($data_by_managers as $manager) { ?>
					<td></td><td></td><td></td>
				<? } ?>
				<td></td><td></td><td></td>
			</tr>
		<? } ?>		
	</table>
</body>
</html>