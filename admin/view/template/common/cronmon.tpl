<?php echo $header; ?>

<style>
	.list.big1 thead td{font-size:14px; padding: 10px 3px; font-weight:700;}
	.list.big1 tbody td{font-size:14px;padding: 5px 3px;}	
	.list.big1 tbody td i{font-size:24px;}
	.list tbody td a{text-decoration: none; color: gray;}
</style>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>

	<div class="box">   
		<div class="content">		
			
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
								Контроллер
							</td>
							<td>
								pid файл
							</td>							
							<td>
								Конфиг
							</td>
							<td style="width:150px;">
								Параметры
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
					<?php foreach ($groups as $group => $processes) { ?>
						<tr>
							<td class="left" colspan="9" style="padding-left: 10px;color:#64a1e1; font-weight: 700; font-size:18px;"><?php echo $group?$group:'Нет описания в cron.json'; ?></td>
						</tr>	

						<?php foreach ($processes as $process) { ?>
							<tr>
								<td class="center" style="width:50px;">
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
									<b style="color:#7F00FF"><? echo $process['route']; ?></b>
								</td>
								<td>
									<? echo $process['file']; ?>
								</td>							
								<td>
									<? echo $process['config']; ?>
								</td>
								<td>
									<?php if ($process['args']) { ?>
										<b><?php echo implode(' ', $process['args']); ?></b>
									<? } else { ?>
									<? } ?>
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
					<?php } ?>
				</tbody>
			</table>


		</div>
	</div>
	<?php echo $footer; ?>