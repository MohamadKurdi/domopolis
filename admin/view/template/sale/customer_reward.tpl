<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<style>
	.reward .reward-points{margin-bottom:5px; padding:4px 7px; font-weight:400; font-size:16px;}
	.reward .reward-points.green{color:#02760e; background:#FFF}
	.reward .reward-points.red{color:#ce1400; background:#FFF}
</style>

<?php if ($rewards_queue) { ?>
	
	<h2>Очередь начисления</h2>
	
	<table class="list reward">
		<thead>
			<tr>
				<td class="left">Служебный код</td>
				<td class="left">Заказ</td>
				<td class="left">Описание</td>
				<td class="right">Бонусов</td>
				<td class="right">Дата добавления</td>
				<td class="right">Дата начисления</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rewards_queue as $reward) { ?>
				<tr>
					
					<td class="left">
						<span class="status_color" style="display:inline-block; padding:6px 4px; background: #000; color:white; font-size:10px; "><?php echo $reward['reason_code']; ?></span>
					</td>
					
					<td class="left">
						<?php if ($reward['order_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:10px 8px; background: #aaff56; "><?php echo $reward['order_id']; ?></span>
						<?php } ?>
					</td>
					
					
					<td class="left"><small><?php echo $reward['description']; ?></small></td>
					
					<td class="right">
						<div class="reward-points <?php echo $reward['class']; ?>">
							<?php echo $reward['points']; ?>
						</div>
					</td>
					
										
					<td class="left">
						<i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $reward['date_added']; ?>						
					</td>
					
					<td class="right">
						<?php if ($reward['date_activate']) { ?>
							<div class="reward-points green">
								<?php echo $reward['date_activate']; ?>
							</div>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			
		</tbody>
	</table>	
<?php } ?>



<h2>История начислений</h2>
<table class="list reward">
	<thead>
		<tr>
			<td class="left">Служебный код</td>
			<td class="left">Ответственный</td>
			<td class="left">Дата добавления</td>
			<td class="left">Заказ</td>
			<td class="left">Описание</td>
			<td class="right">Бонусы</td>
			<td class="right">Потрачено</td>
			<td class="right">Активные</td>
			<td class="right">Дата сгорания</td>
		</tr>
	</thead>
	<tbody>
		<?php if ($rewards) { ?>
			<?php foreach ($rewards as $reward) { ?>
				<tr>
					
					<td class="left">
						<span class="status_color" style="display:inline-block; padding:6px 4px; background: #000; color:white; font-size:10px; "><?php echo $reward['reason_code']; ?></span>
					</td>
					
					<td class="left">
						<?php if ($reward['user']) { ?>
							<span class="status_color" style="display:inline-block; padding:6px 4px; background: #7F00FF; color:white; font-size:10px; ">
							<?php echo $reward['user']; ?>
						</span>
					<?php } ?>
					</td>
					
					<td class="left">
						<i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $reward['date_added']; ?>
						<br /><i class="fa fa-clock-o"></i> <?php echo $reward['time_added']; ?>
					</td>
					
					<td class="left">
						<?php if ($reward['order_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:10px 8px; background: #aaff56; "><?php echo $reward['order_id']; ?></span>
						<?php } ?>
					</td>
					
					
					<td class="left"><small><?php echo $reward['description']; ?></small></td>
					
					<td class="right">
						<div class="reward-points <?php echo $reward['class']; ?>">
							<?php echo $reward['points']; ?>
						</div>
					</td>
					
					<td class="right">		
						<?php if ($reward['points_paid']) { ?>
							<div class="reward-points red">
								<?php echo $reward['points_paid']; ?>	
							</div>
						<?php } ?>
					</td>
					
					<td class="right">
						<?php if ($reward['points_active']) { ?>
							<div class="reward-points green">
								<?php echo $reward['points_active']; ?>
							</div>
						<?php } ?>
					</td>
					
					<td class="right">
						<?php if ($reward['date_inactivate']) { ?>
							<div class="reward-points red">
								<?php echo $reward['date_inactivate']; ?>
							</div>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="7"></td>
				<td class="right"></td>
				<td class="right"><div style="margin-bottom:5px; padding:4px 7px; font-weight:700; font-size:30px;"><?php echo $balance; ?></div></td>
				<td class="right"></td>
			</tr>    
			<?php } else { ?>
			<tr>
				<td class="center" colspan="3"><?php echo $text_no_results; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>