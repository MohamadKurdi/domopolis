<?php echo $header; ?>
<div id="content">
	<h1 style="display: inline-block; float: left;">
		<a href="javascript: void(0);" onclick="$('.js-form').show();" alt="Изменить название" title="Изменить название"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;
		<?php print $name ? $name : 'Неизвесная рассылка' ?>

	</h1>
	<div style="clear: both;"></div>

	<form method="post" style="display: none;" class="js-form">
		<input type="text" name="name" value="<?php echo $name; ?>">
		<input type="submit" name="ok" value="Обновить">
	</form>

	<div class="box" id="form">

		<canvas id="myChart" height="100"></canvas>

		<div id="tabs" class="htabs">
			<?php $i = 0; ?>
			<?php foreach ($mailing_info as $k => $mi): ?>
			<a href="#<?=md5($k); ?>" class="<?php if ($i == 0) print 'selected'; ?>" style="display: inline;"><?=$k ?></a>
			<?php $i++; ?>
			<?php endforeach; ?>
		</div>

		<div class="content">
			<?php foreach ($mailing_info as $k => $mi): ?>
			<div id="<?=md5($k); ?>" style="display: <?php if ($i == 0) print 'block'; else print 'none'; ?>;">
				<h3>Октрыто: <?=$mi['opened'] ?></h3>
				<h3>Не открыто: <?=$mi['not_opened'] ?></h3>
				<br>
				<br>
				<h3>Кликнуто: <?=$mi['clicked'] ?></h3>
				<h3>Не кликнуто: <?=$mi['not_clicked'] ?></h3>

			</div>
			<?php $i++; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#tabs a').tabs();

		var ctx = document.getElementById("myChart");
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: [<?php foreach ($mailing_info as $k => $mi): ?>"<?php print $k; ?>",<?php endforeach; ?>],
				datasets: [{
					label: '',
					data: [<?php foreach ($mailing_info as $k => $mi): ?>"<?php print $tabs_count_array[$k]; ?>",<?php endforeach; ?>]
				}]
			},
			options: {
				fullWidth: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				}
			}
		});
	});
</script>

<?php echo $footer; ?>
