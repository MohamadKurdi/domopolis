<?php echo $header; ?>

<style>
	.list.big1 thead td{font-size:16px; padding: 10px 3px; font-weight:700;}
	.list.big1 tbody td{font-size:16px;padding: 5px 3px;}	
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
								Конфиг
							</td>
							<td>
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
								<? echo $process['route']; ?>
							</td>
							<td>
								<? echo $process['config']; ?>
							</td>
							<td>
								<? print_r($process['args']); ?>
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
	</div>
	<?php echo $footer; ?>