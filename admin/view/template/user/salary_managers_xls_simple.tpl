<html><head><meta charset="UTF-8"></head><body>
	<table class="list" style="width:100%">		
		<tr>
			<td></td>
			<td></td>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>		
		<tr>
			<td>				
			</td>
			<td>				
			</td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td>
					<? echo $manager['name']; ?>
				</td>				
			<? } ?>			
			<td>
				Итого
			</td>
			<td>
				Курс подсчета
			</td>
			<td>
				Итого, грн.
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td>
					К оплате
				</td>
			<? } ?>
			<td></td><td></td><td></td><td></td><td></td>
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
					<td></td>
				<? } ?>
				<td></td><td></td><td></td><td></td><td></td>
			</tr>
			<? asort($orders);  foreach ($orders as $order) { ?>				
			<? } ?>
			<tr>
				<td>					
				</td>
				<td>
					<? echo $order['currency_code']; ?>
				</td>
				<? unset($manager); ?>
				<? foreach ($data_by_managers as $manager) { ?>				
					<td>
						<? echo isset($manager['count_data']['cstats']['total_good_sum'][$country])?$manager['count_data']['cstats']['total_good_sum'][$country]['left_to_pay']:number_format(0, 0, '.', ''); ?>
					</td>
				<? } ?>				
				<td>
					<? echo isset($general_totals_by_countries[$country]['left_to_pay_text'])?$general_totals_by_countries[$country]['left_to_pay']:number_format(0, 0, '.', ''); ?>	
				</td>								
				
				<td>
					<? echo isset($general_totals_by_countries[$country]['course_real'])?$general_totals_by_countries[$country]['course_real']:number_format(0, 0, '.', ''); ?>	
				</td>
				
				<td>
					<? echo isset($general_totals_by_countries[$country]['total_text_uah'])?$general_totals_by_countries[$country]['total_text_uah']:number_format(0, 0, '.', ''); ?>	
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
				</td>
				<? unset($manager); ?>
				<? foreach ($data_by_managers as $manager) { ?>
					<td></td>
				<? } ?>
				<td></td><td></td><td></td><td></td><td></td>
			</tr>
		<? } ?>	
		<tr>
			<td>
			</td>
			<td>
			</td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td></td><td></td><td></td><td></td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
			</td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td>Общий итог, грн.</td><td><? echo number_format($fucken_big_total_pay, 0, '.', ''); ?></td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
			</td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td>1%</td><td><? echo number_format($fucken_big_total_pay_1p, 0, '.', ''); ?></td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
			</td>
			<? unset($manager); ?>
			<? foreach ($data_by_managers as $manager) { ?>
				<td></td>
			<? } ?>
			<td></td><td>0.6%</td><td><? echo number_format($fucken_big_total_pay_06p, 0, '.', ''); ?></td>
		</tr>
	</table>
</body>
</html>