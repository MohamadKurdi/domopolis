<?php echo $header; ?>
<div id="content">
	<h1>Массовые Email рассылки</h1>
	<?php if ($compaings): ?>
		<table class="list">
			<thead>
				<tr>
					<td><strong>#</strong><td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($compaings as $x): ?>
					<tr>
						<td>
							<a href="<?php print $x['url'] ?>"><?php echo $x['name']; ?></a>
						</td>
						<td>
							<?php echo $x['id']; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<strong>Рассылок не найдено</strong>
	<?php endif; ?>
</div>




<?php echo $footer; ?>
