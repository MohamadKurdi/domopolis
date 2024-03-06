	<div id="tab-terms">
		<h2>Сроки поставки. Задаются через дефис, цифрами, 15-30, 4-7, 1-2</h2>
		<table class="form">
			<tr>
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если есть в наличии в текущей стране</span></p>
					<input type="text" name="config_delivery_instock_term" value="<?php echo $config_delivery_instock_term; ?>" size="10" />
				</td>
				
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если нету в наличии в текущей стране, но есть в Германии</span></p>
					<input type="text" name="config_delivery_central_term" value="<?php echo $config_delivery_central_term; ?>" size="10" />
				</td>
				<?php if ($config_country_id == 176) { ?>		
					<td style="width:33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если есть в наличии на складе РФ</span></p>
						<input type="text" name="config_delivery_russia_term" value="<?php echo $config_delivery_russia_term; ?>" size="10" />
					</td>
				<?php } ?>
			</tr>

			<tr>
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если есть в наличии на складе в Украине</span></p>
					<input type="text" name="config_delivery_ukrainian_term" value="<?php echo $config_delivery_ukrainian_term; ?>" size="10" />
				</td>

				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если нет в наличии в Германии</span></p>
					<input type="text" name="config_delivery_outstock_term" value="<?php echo $config_delivery_outstock_term; ?>" size="10" />
				</td>
			</tr>
			
			<tr>
				<td style="width:33%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Отображать сроки, если нет в наличии на складе в стране</span></p>
						<select name="config_delivery_outstock_enable">
							<?php if ($config_delivery_outstock_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить в заказах текст с информацией о доставке</span></p>
						<select name="config_order_bottom_text_enable">
							<?php if ($config_order_bottom_text_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>
				</td>

				<td style="width:33%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Разделять корзину по наличию</span></p>
						<select name="config_divide_cart_by_stock">
							<?php if ($config_divide_cart_by_stock) { ?>
								<option value="1" selected="selected">Разделять</option>
								<option value="0">Не разделять</option>
							<?php } else { ?>													
								<option value="1">Разделять</option>
								<option value="0"  selected="selected">Не разделять</option>
							<? } ?>
						</select>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Не показывать текст "под заказ"</span></p>
						<select name="config_display_dt_preorder_text">
							<?php if ($config_display_dt_preorder_text) { ?>
								<option value="1" selected="selected">Только сроки</option>
								<option value="0">Показывать</option>
							<?php } else { ?>													
								<option value="1">Только сроки</option>
								<option value="0"  selected="selected">Показывать</option>
							<? } ?>
						</select>
						<span class="help">Под заказ, x-y дней, либо только х-y дней</span>
					</div>
				</td>

				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика подсчёта сроков в карте товара</span></p>
					<select name="config_delivery_display_logic">
						<?php if ($config_delivery_display_logic == 'v1') { ?>
							<option value="v1" selected="selected">Логика v1, без блоков, разделять отправку и доставку</option>
							<option value="v2"></option>
						<?php } else { ?>													
							<option value="v1">Логика v1, без блоков, разделять отправку и доставку</option>
							<option value="v2"  selected="selected">Логика v2, блоками, даты в заголовке, не разделять отправку и доставку</option>
						<? } ?>
					</select>
				</td>
			</tr>
		</table>		
		
		<h2>Идентификаторы складов</h2>
		
		<table class="form">
			<tr>
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Идентификатор склада</span></p>
					<input type="text" name="config_warehouse_identifier" value="<?php echo $config_warehouse_identifier; ?>" size="30" />
					<br />
					<span class="help">идентификатор склада, с которого выполняется отправка в эту страну, это обычная логика обработки наличия</span>
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Идентификатор реального склада</span></p>
					<input type="text" name="config_warehouse_identifier_local" value="<?php echo $config_warehouse_identifier_local; ?>" size="30" />
					<br />
					<span class="help">идентификатор локального склада, для совместимости с логикой вычисления сроков поставки</span>
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Работать только со складом</span></p>
					<select name="config_warehouse_only">
						<?php if ($config_warehouse_only) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">всё чего нет на складе - на фронте отдается в ноль</span>
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Статус если нет на складе</span></p>
					<select name="config_overload_stock_status_id">
						<?php foreach ($stock_statuses as $stock_status) { ?>
							<?php if ($stock_status['stock_status_id'] == $config_overload_stock_status_id) { ?>
								<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<br />
					<span class="help">всё чего нет на складе - отдается этот статус</span>
				</td>
			</tr>
			
		</table>
		
		<h2>Самовывоз</h2>
		
		<table class="form">
			<tr>
				<td>Возможность самовывоза</td>
				<td>
					<select name="config_pickup_enable">
						<?php if ($config_pickup_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>
			</tr>
			
			<tr>
				<td>Время работы пункта самовывоза</td>
				<td>
					<input type="text" name="config_pickup_times" value="<?php echo $config_pickup_times; ?>" size="50" />
					<br />
					<span class="help">Формат: 10:19;10:19;10:19;10:19;10:19;false;false;</span>
				</td>
			</tr>							
			
		</table>
		
		<?php setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian'); ?>
		<?php $monthes = array(
			1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
			5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
			9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
		); ?>
		<?php 
		$colors = array(
			1 => '#7F00FF', 
			2 => '#7F00FF', 
			3 => '#00ad07', 
			4 => '#00ad07',
			5 => '#00ad07', 
			6 => '#cf4a61', 
			7 => '#cf4a61', 
			8 => '#cf4a61',
			9 => '#ff7815', 
			10 => '#ff7815', 
			11 => '#ff7815', 
			12 => '#7F00FF'
		);
		?>
		<h2>Выходные дни самовывоза в этом и следующем годах, <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">если доживем</span>. 
			Сейчас <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $monthes[date('n')]; ?> <?php echo date('Y'); ?></span>. 
			Следующий <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><?php echo $monthes[date('n', strtotime('+1 month'))]; ?> <?php echo date('Y', strtotime('+1 month')); ?></span></h2>
			<table class="form">
				<tr>
					<?php for ($i=1; $i<=12; $i++) { ?>
						<td width="8%" style="width:8%" class="text-left">
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:<?php echo $colors[$i]; ?>; color:#FFF">
								<?php echo $i; ?></span>
								
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:<?php echo $colors[$i]; ?>; color:#FFF">
									<?php echo $monthes[$i]; ?></span>
									
									<br />
									<textarea rows="10" cols="8" name="config_pickup_dayoff_<?php echo $i; ?>"><?php echo ${'config_pickup_dayoff_' . $i};?></textarea>
									
									<?php if (date('n') == $i) { ?>
										<br /><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">
											Текущий
										</span>
									<?php } ?>
									
									<?php if (date('n', strtotime('+1 month')) == $i) { ?>
										<br /><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">
											Следующий
										</span>
									<?php } ?>
									
									<?php if (date('n', strtotime('+1 month')) != $i && date('n') != $i && date('n') > $i) { ?>
										<br />
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo date('Y', strtotime('+1 year')); ?>
									</span>
								<? } ?>
								
								<?php if (date('n', strtotime('+1 month')) != $i && date('n') != $i && date('n') < $i) { ?>
									<br />
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo date('Y'); ?>
								</span>
							<? } ?>
						</td>												
						<?php  if ($i==100) { ?></tr><tr><? } ?>
					<?php } ?>
				</tr>
			</table>
		</div>