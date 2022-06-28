<style>
	.list.big1 thead td{font-size:14px; font-weight:700;}
	.list.big1 tbody td{font-size:16px;padding: 5px 3px;}	
	.list tbody td a{text-decoration: none; color: gray;}
</style>

<div class="dashboard-heading"><i class="fa fa-spinner"></i> Мониторинг процессов</div>
<div class="dashboard-content">
<table class="list big1">
	<tbody>
			<thead>
				<tr>	
					<td>
					</td>
					<td>
						Процесс
					</td>
					<td>
						Начат
					</td>
					<td>
						Закончен
					</td>
					<td>
						Инфо
					</td>
				</tr>
			</thead>
		<?php foreach ($processes as $process) { ?>
			<tr>
				<td>
					<?php if ($process['running']) { ?>
						<i class="fa fa-spinner fa-spin" style="color:#32bd38"></i>
					<?php } ?>

					<?php if ($process['failed']) { ?>
						<i class="fa fa-exclamation-circle" style="color:#fa4934"></i>
					<?php } ?>

					<?php if ($process['finished']) { ?>
						<i class="fa fa-check" style="color:#32bd38"></i>
					<?php } ?>

					<?php if (!$process['start']) { ?>
						<i class="fa fa-hourglass" style="color:#7f00ff"></i>
					<?php } ?>
				</td>
				<td>
					<b><? echo $process['name']; ?></b>
				</td>
				<td>
					<? echo $process['start']; ?>
				</td>
				<td>
					<? if ($process['stop']) { ?>
						<?php echo $process['stop'] ?>
					<? } else { ?>

						<? if ($process['running']) { ?>
							<span style="color:#32bd38">работает</span>
						<? } else { ?>
							<? if ($process['never']) { ?>
								<span style="color:#7f00ff">ждет</span>
							<? } else { ?>
								<span style="color:#fa4934">сбой</span>
							<? } ?>
						<? } ?>

					<? } ?>
				</td>
				<td>
					<? echo $process['status']; ?>					
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
</div>