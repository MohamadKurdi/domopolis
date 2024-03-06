<div id="tab-priceva">
	<?php if ($priceva) { ?>			
		<?php foreach ($priceva as $store_name => $priceva_data) { ?>
			<h2 style="color:#7F00FF"><i class="fa fa-product-hunt"></i> Данные о конкурентах с ценами <?php echo $store_name; ?> (Priceva API)</h2>
			<table class="list">
				<thead>
					<tr>
						<td class="left">Конкурент</td>
						<td class="left">Ссылка</td>
						<td class="left">Цена KP</td>
						<td class="left">Скидка KP</td>
						<td class="left">Цена</td>
						<td class="left">Скидка</td>
						<td class="left">Наличие</td>
						<td class="left">Посл. обновл.</td>
						<td class="left">Релевантность</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($priceva_data as $priceva_line) { ?>
						<tr>
							<td class="left" style="white-space: nowrap;">
								<b><?php echo $priceva_line['company_name']; ?></b>
								<br />
								<small><?php echo $priceva_line['region_name']; ?></small>
							</td>
							<td class="left"><a href="<?php echo $priceva_line['url']; ?>" target="_blank"><small><?php echo $priceva_line['url']; ?></small></a></td>

							<td class="left" style="white-space: nowrap;"><b><?php echo $front[$store_name]['price']; ?></b></td>
							<td class="left" style="white-space: nowrap; color: #cf4a61;"><b><?php echo $front[$store_name]['special']; ?></b></td>

							<td class="left" style="white-space: nowrap;"><b><?php echo $priceva_line['price']; ?></b></td>
							<td class="left" style="white-space: nowrap; color: #cf4a61;"><b><?php echo $priceva_line['discount']; ?></b></td>

							<td class="left" style="white-space: nowrap;">
								<?php if ($priceva_line['in_stock']) { ?>
									<span style="display:inline-block; padding:3px; color:#FFF; background-color:#51a881;">В наличии</span>
								<?php } else { ?>
									<span style="display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;">Нет в наличии</span>
								<?php } ?>
							</td>

							<td class="left" style="white-space: nowrap;">
								<span style="display:inline-block; padding:3px; color:#FFF; background-color:#000;"><?php echo $priceva_line['last_check_date']; ?>
							</td>
							<td class="left" style="white-space: nowrap;">
								<span style="display:inline-block; padding:3px; color:#FFF; background-color:grey;"><?php echo $priceva_line['relevance_status']; ?></i>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		<?php } ?>
	</div>