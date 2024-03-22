<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>	
	<?php if (!empty($error_warning)) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if (!empty($success)) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><?php echo $heading_title; ?></h1>				
			<div id="rnf-status" style="float: left; line-height: 26px; padding-top: 5px; margin-left:20px;" class="delayed-load short-delayed-load" data-route='setting/rnf/getRainForestStats' data-reload="5000"></div>		

			<div class="clr"></div>
			<span class="help"><i class="fa fa-info-circle"></i> Другие настройки фреймворка, которые не требуют оперативных изменений можно найти в общих настройках магазина, в разделе Rainforest API</span>
			<span class="help"><i class="fa fa-exclamation-triangle"></i> Не меняйте ничего, если не уверены в том, что делаете, сюда вынесены самые критические настройки, напрямую влияющие на работу фреймворка и ценообразования</span>
		</div>
		<div class="content">
			<style>
				#tabs > a {font-weight:700; font-size: 16px; }
				.list tbody td{padding: 10px 10px 10px 5px;}
			</style>

			<div id="tabs" class="htabs">
				<a href="#tab-cron"><span style="color:#7F00FF;"><i class="fa fa-refresh"></i> Cron</span></a>
				<?php if ($this->user->getAdminExtendedStats()) { ?>
					<a href="#tab-cron-results"><span style="color:#0054b3;"><i class="fa fa-refresh"></i> Статистика фреймворка</span></a>
				<?php } ?>
				<a href="#tab-external-api"><span style="color:#cf4a61;"><i class="fa fa-cogs"></i> External API, Framework Debug</span></a>
				<a href="#tab-products"><span style="color:#00ad07;"><i class="fa fa-cogs"></i> Настройки добавления товаров</span></a>
				<a href="#tab-pricelogic"><span style="color:#D69241;"><i class="fa fa-cogs"></i> Настройки ценообразования</span></a>
				<a href="#tab-priceformula"><span style="color:#ff7815;"><i class="fa fa-calculator"></i> Ценовая модель</span></a>
				<a href="#tab-store-settings"><span style="color:#cf4a61;"><i class="fa fa-cogs"></i> Режимы магазина</span></a>					

				<div class="clr"></div>
				<div class="th_style"></div>			
				<input type="hidden" name="store_id" value="0"/>

				<div id="tab-cron">
					<div style="width:99%; float:left;">
						<table class="list">							
							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Парсер категорий Amazon</b>
								</td>
								<td style="width:220px;" class="center">
									<input id="config_rainforest_enable_category_queue_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_category_queue_parser" <? if ($config_rainforest_enable_category_queue_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_category_queue_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Этот воркер просматривает категории в поиске новых товаров в соответствии с вкладкой <span style="color: rgb(0, 173, 7); font-weight: 700; display: inline"><i class="fa fa-star"></i> Автонаполнение Amazon</span>.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-cogs"></i> Интервал загрузки
								</td>
								<td>
									<input type="number" name="config_rainforest_category_queue_update_period" value="<?php echo $config_rainforest_category_queue_update_period; ?>" size="50" style="width:50px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> При загрузке новых товаров для каждой категории запоминается дата и время. В следующий раз новые товары из категории будут загружены не ранее чем через заданное количество дней.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_category_queue_parser_time_start" value="<?php echo $config_rainforest_category_queue_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_category_queue_parser_time_end" value="<?php echo $config_rainforest_category_queue_parser_time_start; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Добавление выполняется в рабочую базу и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Парсер данных о товарах Amazon</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_data_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_data_parser" <? if ($config_rainforest_enable_data_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_data_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Изначально товары загружаются с страницы категории без данных, только название картинка и цена. Этот воркер получает полные данные о товарах, включая описание, атрибуты, блоки связей товаров, добавляет варианты, и.т.д. Воркер работает только с категориями, которые включены, у которых включен маркер <i>Разрешить загрузку полной информации о товарах</i> и только с товарами, у которых включен маркер <i>Разрешить загрузку данных</i>
									</span>
								</td>
							</tr>		
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_data_parser_time_start" value="<?php echo $config_rainforest_data_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_data_parser_time_end" value="<?php echo $config_rainforest_data_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Очередь ручного добавления</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_add_queue_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_add_queue_parser" <? if ($config_rainforest_enable_add_queue_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_add_queue_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Воркер, обслуживающий очередь ручного добавления ASIN. Логика работает паралельно с автоматическим добавлением. Также для корректной работы очереди должен быть включен воркер <i>Разгребатель технической категории</i> и <i>Парсер данных о товарах Amazon L2</i>
									</span>
								</td>
							</tr>									
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_add_queue_parser_time_start" value="<?php echo $config_rainforest_add_queue_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_add_queue_parser_time_end" value="<?php echo $config_rainforest_add_queue_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-refresh"></i> Откладывать получение офферов
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_delay_queue_offers" type="checkbox" class="checkbox" name="config_rainforest_delay_queue_offers" <? if ($config_rainforest_delay_queue_offers){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delay_queue_offers"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Чтоб ускорить добавление товаров, мы используем очередь обновления цен для товаров в заказах.
									</span>
								</td>
							</tr>

							<tr>
								<td class="right">
									<i class="fa fa-refresh"></i> Откладывать получение вариантов
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_delay_queue_variants" type="checkbox" class="checkbox" name="config_rainforest_delay_queue_variants" <? if ($config_rainforest_delay_queue_variants){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delay_queue_variants"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Чтоб ускорить добавление товаров, мы откладываем добавление вариантов
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Очередь добавления вариантов</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_add_variants_queue_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_add_variants_queue_parser" <? if ($config_rainforest_enable_add_variants_queue_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_add_variants_queue_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Воркер, обслуживающий очередь добавления вариантов товаров. Будет работать только в случае, если мы выполняем отложенное добавление вариантов. Значительно ускоряет наполнение каталога за счёт добавления вариантов в фоне.
									</span>
								</td>
							</tr>	
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_add_variants_queue_parser_time_start" value="<?php echo $config_rainforest_add_variants_queue_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_add_variants_queue_parser_time_end" value="<?php echo $config_rainforest_add_variants_queue_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>


							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Разгребатель технической категории</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_tech_category_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_tech_category_parser" <? if ($config_rainforest_enable_tech_category_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_tech_category_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> В процессе добавления и обработки товаров появляются сопутствующие товары с изначально неизвестной категорией. Они попадают в "техническую категорию". Этот воркер получает данные о таких товарах и переносит их в нужные категории
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_tech_category_parser_time_start" value="<?php echo $config_rainforest_tech_category_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_tech_category_parser_time_end" value="<?php echo $config_rainforest_tech_category_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>


							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Парсер данных о товарах Amazon L2</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_data_l2_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_data_l2_parser" <? if ($config_rainforest_enable_data_l2_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_data_l2_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> После переноса товаров из технической категории воркером <i>разгребатель технической категории</i> у нас имеются структурированные данные о товарах, которые нет смысла получать в общем потоке. Этот воркер обрабатывает уже загруженные данные о товарах и добавляет описание, атрибуты, блоки связей товаров, и прочий контент
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_data_l2_parser_time_start" value="<?php echo $config_rainforest_data_l2_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_data_l2_parser_time_end" value="<?php echo $config_rainforest_data_l2_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Поиск и обновление потерянных ASIN</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_recoverasins_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_recoverasins_parser" <? if ($config_rainforest_enable_recoverasins_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_recoverasins_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> В процессе жизни товара на Амазоне у него может измениться асин. Если нам удалось это заметить, то мы пытаемся обновить полную информацию о товаре
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_recoverasins_parser_time_start" value="<?php echo $config_rainforest_recoverasins_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_recoverasins_parser_time_end" value="<?php echo $config_rainforest_recoverasins_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>	

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Воркер репрайсинга</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_reprice_cron" type="checkbox" class="checkbox" name="config_rainforest_enable_reprice_cron" <? if ($config_rainforest_enable_reprice_cron){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_reprice_cron"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Этот воркер обновляет цены фронта в случае изменений ценовой модели категорий
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_reprice_cron_time_start" value="<?php echo $config_rainforest_reprice_cron_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_reprice_cron_time_end" value="<?php echo $config_rainforest_reprice_cron_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Получение офферов с Amazon</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_offers_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_offers_parser" <? if ($config_rainforest_enable_offers_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offers_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Этот воркер получает и обновляет предложения и цены с Amazon. Также, в зависимости от настроек, он меняет статусы, и может удалять товары.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-refresh"></i> Ручное исправление
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_nooffers_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_nooffers_parser" <? if ($config_rainforest_enable_nooffers_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_nooffers_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Разрешает запуск ручного исправления офферов в случае какого-либо сбоя Amazon или Rainforest API
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-refresh"></i> Отложенное назначение цен
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_delay_price_setting" type="checkbox" class="checkbox" name="config_rainforest_delay_price_setting" <? if ($config_rainforest_delay_price_setting){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delay_price_setting"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Если включено - то цены не обновляются при получении офферов, а только при формировании фидов для мерчанта и фейсбука, для того, чтоб цены всегда совпадали и отсутствия резких скачков цен. Это не затрагивает обновление наличия.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-refresh"></i> Отложенное изменение наличия
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_delay_stock_setting" type="checkbox" class="checkbox" name="config_rainforest_delay_stock_setting" <? if ($config_rainforest_delay_stock_setting){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delay_stock_setting"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Если включено - то наличие не обновляется при получении офферов, а только при формировании фидов для мерчанта и фейсбука.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-cogs"></i> Интервал обновления
								</td>
								<td>
									<input type="number" name="config_rainforest_update_period" value="<?php echo $config_rainforest_update_period; ?>" size="50" style="width:50px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Мы не можем обновлять все офферы ежедневно. Поэтому устанавливаем интервал обновления для каждого товара. Цены и наличие каждого отдельно взятого товара будут обновляться с этим интевалом.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_offers_parser_time_start" value="<?php echo $config_rainforest_offers_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_offers_parser_time_end" value="<?php echo $config_rainforest_offers_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Дополнительная очередь офферов</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_offersqueue_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_offersqueue_parser" <? if ($config_rainforest_enable_offersqueue_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offersqueue_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Паралельная очередь офферов. Товары в заказах, новые добавленные товары из очереди ручного добавления.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_offersqueue_parser_time_start" value="<?php echo $config_rainforest_offersqueue_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_offersqueue_parser_time_end" value="<?php echo $config_rainforest_offersqueue_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Обработка выполняется в рабочей базе и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>

							<tr>
								<td class="right">
									<i class="fa fa-refresh"></i> Добавлять товары из заказов в очередь
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_offers_after_order" type="checkbox" class="checkbox" name="config_rainforest_enable_offers_after_order" <? if ($config_rainforest_enable_offers_after_order){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offers_after_order"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Чтоб иметь актуальную цену закупки, товары должны добавляться в очередь после каждого заказа.
									</span>
								</td>
							</tr>												

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Парсер дерева категорий Amazon</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_category_tree_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_category_tree_parser" <? if ($config_rainforest_enable_category_tree_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_category_tree_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Этот воркер просматривает дерево категорий Amazon в соответствии с настройками модели работы и добавляет несуществующие ранее категории. Он не редактирует привязки категорий, только добавляет их.
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Валидатор ASIN</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_asins_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_asins_parser" <? if ($config_rainforest_enable_asins_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_asins_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Иногда на Amazon удаляются некоторые товары. Для избежания участия их в получении цен этот воркер проверяет существование товаров на Amazon. В зависимости от логики, ASIN либо обнуляется либо товар отключается. Используется только в случае, если в каталоге есть товары, добавленные не через API, а вручную
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Монитор качества ZipCode</b>
								</td>
								<td style="width:220px;" class="center">
									<input id="config_rainforest_enable_checkzipcodes_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_checkzipcodes_parser" <? if ($config_rainforest_enable_checkzipcodes_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_checkzipcodes_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Автоматически проверяет зипкоды, используемые апи, и заменяет в случае накопления большого количества ошибок
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-cogs"></i> Количество плохих запросов
								</td>
								<td>
									<input type="number" name="config_rainforest_checkzipcodes_bad_request_limit" value="<?php echo $config_rainforest_checkzipcodes_bad_request_limit; ?>" size="50" style="width:50px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> При достижении лимита зипкод будет заменен на другой из базы
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Валидатор EAN/GTIN</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_eans_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_eans_parser" <? if ($config_rainforest_enable_eans_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_eans_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Для обновления цен с Amazon необходимо знать идентификатор ASIN. Если в каталоге существуют товары у которых задан EAN (GTIN), этот воркер пытается сопоставить EAN с ASIN и записать его в БД. Используется только в случае, если в каталоге есть товары, добавленные не через API, а вручную
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Генератор SEO</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_enable_seogen_cron" type="checkbox" class="checkbox" name="config_enable_seogen_cron" <? if ($config_enable_seogen_cron){ ?> checked="checked" <? } ?> value="1" /><label for="config_enable_seogen_cron"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Невозможно генерировать данные на лету для большого количества товаров. Поэтому это вынесено в регулярные задачи. Задача запускается кажду ночь и создает данные в соответствии с настройками модуля SeoGen.
									</span>
								</td>
							</tr>

							<tr>
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Парсер новых товаров Amazon</b>
									<br /><span style="color:red">устаревший воркер, не рекомендуется включать</span>
								</td>
								<td style="width:220px;" class="center">
									<input id="config_rainforest_enable_new_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_new_parser" <? if ($config_rainforest_enable_new_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_new_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Этот воркер просматривает категории в поиске новых товаров, либо обычные товары в листинге, либо бестселлеры и добавляет их. Добавляется только название, картинка и цена. Воркер работает только с категориями, которые включены и у которых включен маркер <i>Разрешить загрузку информации о новых товарах</i>.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-cogs"></i> Интервал загрузки
								</td>
								<td>
									<input type="number" name="config_rainforest_category_update_period" value="<?php echo $config_rainforest_category_update_period; ?>" size="50" style="width:50px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> При загрузке новых товаров для каждой категории запоминается дата и время. В следующий раз новые товары из категории будут загружены не ранее чем через заданное количество дней.
									</span>
								</td>
							</tr>
							<tr>
								<td class="right">
									<i class="fa fa-clock-o"></i> Время работы, часы
								</td>
								<td>
									<input type="time" name="config_rainforest_new_parser_time_start" value="<?php echo $config_rainforest_new_parser_time_start; ?>" size="50" style="width:70px;" /> - 
									<input type="time" name="config_rainforest_new_parser_time_end" value="<?php echo $config_rainforest_new_parser_time_end; ?>" size="50" style="width:70px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Добавление выполняется в рабочую базу и сильно ее нагружает. Поэтому лучше ограничивать время запуска, например, ночными часами, чтоб уменьшить нагрузки.
									</span>
								</td>
							</tr>
						</table>
					</div>					
				</div>
				<?php if ($this->user->getAdminExtendedStats()) { ?>
					<div id="tab-cron-results" class="delayed-load" data-route='common/home/loadProductStats&tpl=rnf&long=true' data-reload="30000">
					</div>
				<?php } ?>

				<div id="tab-external-api">
					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-code"></i> <b>Debug</b>
							</td>
						</tr>

						<tr>
							<td class="right">
								Library Debug
							</td>
							<td  class="center">
								<input id="config_rainforest_debug_library" type="checkbox" class="checkbox" name="config_rainforest_debug_library" <? if ($config_rainforest_debug_library){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_debug_library"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Подробные логи для библиотеки AmazonRainforest (только нативные запросы библиотеки)
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Library HTTP Debug
							</td>
							<td  class="center">
								<input id="config_rainforest_debug_http_library" type="checkbox" class="checkbox" name="config_rainforest_debug_http_library" <? if ($config_rainforest_debug_http_library){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_debug_http_library"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Подробные логи для HTTP-запросов библиотеки AmazonRainforest (только нативные запросы библиотеки)
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Offers Debug
							</td>
							<td  class="center">
								<input id="config_rainforest_debug_offers" type="checkbox" class="checkbox" name="config_rainforest_debug_offers" <? if ($config_rainforest_debug_offers){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_debug_offers"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Подробные логи для запросов офферов<sup style="color:red">DEV</sup>
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Products Debug
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_debug_products" type="checkbox" class="checkbox" name="config_rainforest_debug_products" <? if ($config_rainforest_debug_products){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_debug_products"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Подробные логи для запросов товаров (SimpleProductParser, RainforestRetriever multiCURL)
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Add v2 Debug
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_debug_products_v2_file" type="checkbox" class="checkbox" name="config_rainforest_debug_products_v2_file" <? if ($config_rainforest_debug_products_v2_file){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_debug_products_v2_file"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включено, то результат запроса подменяется результатом из файла system/temp/rainforest.addasinv2.json
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Categories Debug
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_debug_categories" type="checkbox" class="checkbox" name="config_rainforest_debug_categories" <? if ($config_rainforest_debug_categories){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_debug_categories"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Подробные логи для запросов категорий<sup style="color:red">DEV</sup>
								</span>
							</td>
						</tr>
						
						<tr>
							<td  class="right">
								CURL Connect Timeout
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_debug_curl_connect_timeout" value="<?php echo $config_rainforest_debug_curl_connect_timeout; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Таймаут запросов к Rainforest API, сек (SimpleProductParser, RainforestRetriever, multiCURL)
								</span>
							</td>
						</tr>

						<tr>
							<td  class="right">
								CURL Request Timeout
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_debug_curl_request_timeout" value="<?php echo $config_rainforest_debug_curl_request_timeout; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Таймаут запросов к Rainforest API, сек (SimpleProductParser, RainforestRetriever, multiCURL)
								</span>
							</td>
						</tr>

						<tr>
							<td  class="right">
								Guzzle Request Timeout
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_debug_request_timeout" value="<?php echo $config_rainforest_debug_request_timeout; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Таймаут запросов к Rainforest API, сек (только нативные запросы библиотеки, Guzzle adaptor)
								</span>
							</td>
						</tr>

						<tr>
							<td  class="right">
								PriceLogic Mysql Debug
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_debug_mysql_pricelogic" type="checkbox" class="checkbox" name="config_rainforest_debug_mysql_pricelogic" <? if ($config_rainforest_debug_mysql_pricelogic){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_debug_mysql_pricelogic"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Выводить в консоль выполняемые запросы установки цен и наличия
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>🤖 Настройки переводчика External API</b>
							</td>
						</tr>

						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] != $config_rainforest_source_language) { ?>
								<tr>
									<td  class="right">
										Включить перевод <?php echo mb_strtoupper($language['code']); ?>
									</td>
									<td  class="center">
										<input id="config_rainforest_external_enable_language_<?php echo $language['code']; ?>" type="checkbox" class="checkbox" name="config_rainforest_external_enable_language_<?php echo $language['code']; ?>" <? if (${'config_rainforest_external_enable_language_' . $language['code']}){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_language_<?php echo $language['code']; ?>"></label>
									</td>
									<td>
										<span class="help">
											<i class="fa fa-info-circle"></i> Включить ли автоматический перевод при добавлении на этот язык при использовании External API
										</span>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>				

						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>Обработчик данных External API</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Тестовый ASIN
							</td>
							<td style="width:50px;" class="center">
								<input type="text" name="config_rainforest_external_test_asin" value="<?php echo $config_rainforest_external_test_asin; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Нужно для отладки, чтоб не расходовать запросы. Асин можно взять любой успешно добавленный из очереди.
								</span>
							</td>
						</tr>

						<tr>
							<td  class="right">
								Обрабатывать атрибуты
							</td>
							<td  class="center">
								<input id="config_rainforest_external_enable_attributes" type="checkbox" class="checkbox" name="config_rainforest_external_enable_attributes" <? if ($config_rainforest_external_enable_attributes){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_attributes"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Блок attributes
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Обрабатывать особенности
							</td>
							<td  class="center">
								<input id="config_rainforest_external_enable_features" type="checkbox" class="checkbox" name="config_rainforest_external_enable_features" <? if ($config_rainforest_external_enable_features){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_features"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Блок feature_bullets
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Обрабатывать описания
							</td>
							<td  class="center">
								<input id="config_rainforest_external_enable_descriptions" type="checkbox" class="checkbox" name="config_rainforest_external_enable_descriptions" <? if ($config_rainforest_external_enable_descriptions){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_descriptions"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Блок description
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Обрабатывать описания
							</td>
							<td  class="center">
								<input id="config_rainforest_external_enable_names" type="checkbox" class="checkbox" name="config_rainforest_external_enable_names" <? if ($config_rainforest_external_enable_names){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_names"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Блок title
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Обрабатывать цвет
							</td>
							<td  class="center">
								<input id="config_rainforest_external_enable_color" type="checkbox" class="checkbox" name="config_rainforest_external_enable_color" <? if ($config_rainforest_external_enable_color){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_color"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Попытка определить цвет товара из всех возможных полей
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Обрабатывать материал
							</td>
							<td  class="center">
								<input id="config_rainforest_external_enable_material" type="checkbox" class="checkbox" name="config_rainforest_external_enable_material" <? if ($config_rainforest_external_enable_material){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_material"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Попытка определить материал товара из всех возможных полей
								</span>
							</td>
						</tr>
						<tr>
							<td  class="right">
								Обрабатывать габариты
							</td>
							<td  class="center">
								<input id="config_rainforest_external_enable_dimensions" type="checkbox" class="checkbox" name="config_rainforest_external_enable_dimensions" <? if ($config_rainforest_external_enable_dimensions){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_external_enable_dimensions"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Попытка определить и структурировать габариты товара из всех возможных полей
								</span>
							</td>
						</tr>
					</table>
				</div>

				<div id="tab-products">
					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>Варианты, цена, валидность товаров</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Количество вариантов
							</td>
							<td>
								<input type="number" name="config_rainforest_max_variants" value="<?php echo $config_rainforest_max_variants; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Варианты товара также обрабатываются этим воркером. Эта настройка определяет максимальное количество вариантов одного товара, обработанных за одну итерацию. Фактически их будет больше, потому как варианты могут (но не обязательно) загружаться так же как рекомендуемые и сопуствующие к другим товарам.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Пропускать варианты
							</td>
							<td>
								<input type="number" name="config_rainforest_skip_variants" value="<?php echo $config_rainforest_skip_variants; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Во избежание загрузки товаров, у которых очень большое количество вариантов, мы изначально при загрузке пропускаем товары, у которых вариантов больше заданного числа.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Пропускать товары с количеством офферов менее чем
							</td>
							<td>
								<input type="number" name="config_rainforest_skip_min_offers_products" value="<?php echo $config_rainforest_skip_min_offers_products; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Настройка по-умолчанию для автозаполнения категорий. Не влияет на ручной подбор
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Пропускать товары с ценой больше
							</td>
							<td>
								<input type="number" name="config_rainforest_skip_high_price_products" value="<?php echo $config_rainforest_skip_high_price_products; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Товары с высокой ценой не всегда хорошие. Если задать тут число больше нуля, то товары с ценой больше не будут добавляться. Цена в валюте закупки (евро).
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Пропускать товары с ценой меньше
							</td>
							<td>
								<input type="number" name="config_rainforest_skip_low_price_products" value="<?php echo $config_rainforest_skip_low_price_products; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Товары с низкой ценой иногда бывают довольно трешовые. Если задать тут число больше нуля, то товары с ценой меньше не будут добавляться. Цена в валюте закупки (евро).
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								<i class="fa fa-google"></i> Исключать из фидов товары с ценой меньше
							</td>
							<td>
								<input type="number" name="config_rainforest_merchant_skip_low_price_products" value="<?php echo $config_rainforest_merchant_skip_low_price_products; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> На случай если уже добавлено какое-то количество товаров с низкими ценами, не выгружать их в товарные фиды
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="left" style="color:#cf4a61;">
								<i class="fa fa-cogs"></i> <b>Очистка</b>
							</td>
						</tr>

						<tr>
							<td class="right">
								<i class="fa fa-amazon"></i> Удалять или отключать товары с ценой меньше для автоматически добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_drop_low_price_products" type="checkbox" class="checkbox" name="config_rainforest_drop_low_price_products" <? if ($config_rainforest_drop_low_price_products){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_drop_low_price_products"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle" style="color:red"></i> Товары с низкой ценой иногда бывают довольно трешовые. В некоторых случаях товары не имеют изначальной цены, и цена выясняется уже в процессе получения офферов. Если эта настройка включена, то товары будут удаляться при получении офферов. Обязательно отключать для магазинов, наполняемых вручную! Если товары есть в заказах, то они не удаляются, но отключаются.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-hand-peace-o"></i> Удалять или отключать товары с ценой меньше для товаров добавленных вручную
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_drop_low_price_products_for_manual" type="checkbox" class="checkbox" name="config_rainforest_drop_low_price_products_for_manual" <? if ($config_rainforest_drop_low_price_products_for_manual){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_drop_low_price_products_for_manual"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle" style="color:red"></i> Товары с низкой ценой иногда бывают довольно трешовые. В некоторых случаях товары не имеют изначальной цены, и цена выясняется уже в процессе получения офферов. Если эта настройка включена, то товары будут удаляться при получении офферов. Обязательно отключать для магазинов, наполняемых вручную! Если товары есть в заказах, то они не удаляются, но отключаются.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								<i class="fa fa-amazon"></i> Удалять или отключать невалидные ASIN для автоматически добавленых товаров
								<span class="help"></span>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_delete_invalid_asins" type="checkbox" class="checkbox" name="config_rainforest_delete_invalid_asins" <? if ($config_rainforest_delete_invalid_asins){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delete_invalid_asins"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle" style="color:red"></i> Периодически товары пропадают из Amazon. В таком случае при попытке получить оффер, либо информацию о товаре его ASIN обозначается как INVALID. Если эта настройка включена, то такие товары будут периодически удаляться из базы. Обязательно отключать для магазинов, наполняемых вручную! Если товары есть в заказах, то они не удаляются, но отключаются.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								<i class="fa fa-hand-peace-o"></i> Удалять или отключать невалидные ASIN для товаров добавленных вручную
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_delete_invalid_asins_for_manual" type="checkbox" class="checkbox" name="config_rainforest_delete_invalid_asins_for_manual" <? if ($config_rainforest_delete_invalid_asins_for_manual){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delete_invalid_asins_for_manual"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle" style="color:red"></i> Периодически товары пропадают из Amazon. В таком случае при попытке получить оффер, либо информацию о товаре его ASIN обозначается как INVALID. Если эта настройка включена, то такие товары будут периодически удаляться из базы. Обязательно отключать для магазинов, наполняемых вручную! Если товары есть в заказах, то они не удаляются, но отключаются.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								<b style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> Очищать атрибуты, у которых нет значений</b>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_cleanup_empty_attributes" type="checkbox" class="checkbox" name="config_rainforest_cleanup_empty_attributes" <? if ($config_rainforest_cleanup_empty_attributes){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_cleanup_empty_attributes"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle" style="color:red"></i> Пустые атрибуты будут периодически удаляться
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								<b style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> Очищать бренды без товаров</b>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_cleanup_empty_manufacturers" type="checkbox" class="checkbox" name="config_rainforest_cleanup_empty_manufacturers" <? if ($config_rainforest_cleanup_empty_manufacturers){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_cleanup_empty_manufacturers"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle" style="color:red"></i> Пустые бренды (производители) будут периодически удаляться
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>Сервисные категории</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Техническая категория
							</td>
							<td>
								<input type="number" name="config_rainforest_default_technical_category_id" value="<?php echo $config_rainforest_default_technical_category_id; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> При быстром добавлении товара мы не можем определить его категорию сразу, поэтому товар присваивается изначально категории с этим ID
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Сканировать ТК
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_check_technical_category_id" type="checkbox" class="checkbox" name="config_rainforest_check_technical_category_id" <? if ($config_rainforest_check_technical_category_id){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_check_technical_category_id"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Сканировать ли соответствия категориям каталога, если товар в технической категории
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Неопределенная категория
							</td>
							<td>
								<input type="number" name="config_rainforest_default_unknown_category_id" value="<?php echo $config_rainforest_default_unknown_category_id; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Товар будет присвоен этой категории, если при первой попытке обработки мы не смогли найти соответствия категории Amazon категориям каталога
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Сканировать НК
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_check_unknown_category_id" type="checkbox" class="checkbox" name="config_rainforest_check_unknown_category_id" <? if ($config_rainforest_check_unknown_category_id){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_check_unknown_category_id"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Сканировать ли соответствия категориям каталога, если товар в неопределенной категории
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>Блоки связей товаров</b>
							</td>
						</tr>

						<tr>
							<td class="right">
								Рекурсивное добавление
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_recursive_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_recursive_adding" <? if ($config_rainforest_enable_recursive_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_recursive_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Добавлять ли связанные товары при получении полной информации. Это общая настройка. Если она отключена, то ни один из блоков не будет обработан, но привязки в случае нахождения товара связанного в справочнике будут выполняться.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Обработка <i>"Сравните с похожими"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_compare_with_similar_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_compare_with_similar_parsing" <? if ($config_rainforest_enable_compare_with_similar_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_compare_with_similar_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "compare_with_similar", сравните с похожими с карты товара на Amazon. Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Сравните с похожими"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Сравните с похожими"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_compare_with_similar_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_compare_with_similar_adding" <? if ($config_rainforest_enable_compare_with_similar_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_compare_with_similar_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Сравните с похожими"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>


						<tr>
							<td class="right">
								Обработка <i>"Сопутствующие товары"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_related_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_related_parsing" <? if ($config_rainforest_enable_related_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_related_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "related", сопутствующие, с карты товара на Amazon. Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Сопутствующие товары"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Сопутствующие товары"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_related_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_related_adding" <? if ($config_rainforest_enable_related_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_related_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Сопутствующие товары"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Обработка <i>"Товары Спонсоров"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_sponsored_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_sponsored_parsing" <? if ($config_rainforest_enable_sponsored_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_sponsored_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "sponsored", товары спонсоров, с карты товара на Amazon. Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Товары Спонсоров"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Товары Спонсоров"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_sponsored_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_sponsored_adding" <? if ($config_rainforest_enable_sponsored_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_sponsored_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Товары Спонсоров"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Обработка <i>"Предложения похожих"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_similar_to_consider_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_similar_to_consider_parsing" <? if ($config_rainforest_enable_similar_to_consider_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_similar_to_consider_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "similar_to_consider", предложения похожих, с карты товара на Amazon. Обычно это то же самое, что и Сравните с похожими. Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Предложения похожих"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Предложения похожих"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_similar_to_consider_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_similar_to_consider_adding" <? if ($config_rainforest_enable_similar_to_consider_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_similar_to_consider_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Предложения похожих"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Обработка <i>"Смотрели до покупки"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_view_to_purchase_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_view_to_purchase_parsing" <? if ($config_rainforest_enable_view_to_purchase_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_view_to_purchase_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "view_to_purchase", товары, которые смотрели до того, как купить конкретный, с карты товара на Amazon.Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Смотрели до покупки"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Смотрели до покупки"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_view_to_purchase_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_view_to_purchase_adding" <? if ($config_rainforest_enable_view_to_purchase_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_view_to_purchase_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Смотрели до покупки"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Обработка <i>"Также смотрели"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_also_viewed_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_also_viewed_parsing" <? if ($config_rainforest_enable_also_viewed_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_also_viewed_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "also_viewed", также просмотренные товары, с карты товара на Amazon.Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Также смотрели"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Также смотрели"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_also_viewed_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_also_viewed_adding" <? if ($config_rainforest_enable_also_viewed_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_also_viewed_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Также смотрели"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Обработка <i>"Также купили"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_also_bought_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_also_bought_parsing" <? if ($config_rainforest_enable_also_bought_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_also_bought_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "also_bought", товары которые купили покупатели, купившие текущий товар, с карты товара на Amazon.Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Также купили"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Также купили"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_also_bought_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_also_bought_adding" <? if ($config_rainforest_enable_also_bought_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_also_bought_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Также купили"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Обработка <i>"Шоппинг по виду"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_shop_by_look_parsing" type="checkbox" class="checkbox" name="config_rainforest_enable_shop_by_look_parsing" <? if ($config_rainforest_enable_shop_by_look_parsing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_shop_by_look_parsing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обрабатывать или нет блок "shop_by_look", блок с товарами, которые подходят по стилю или цвету, с карты товара на Amazon.Если включено, то для каждого товара из этого блока производится поиск по базе магазина и в случае нахождения выполняется связывание. Если товар не найден, то добавление зависит от настройки <i>Добавление "Шоппинг по виду"</i>
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавление <i>"Шоппинг по виду"</i>
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_shop_by_look_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_shop_by_look_adding" <? if ($config_rainforest_enable_shop_by_look_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_shop_by_look_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если <i>Обработка "Шоппинг по виду"</i> включена, то эта настройка определяет, добавлять ли новый товар из этого блока в базу магазина, если его в ней нет. Если обработка блока в целом отключена, то эта настройка не имеет значения.
								</span>
							</td>
						</tr>


						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>Отзывы</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Добавлять отзывы
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_review_adding" type="checkbox" class="checkbox" name="config_rainforest_enable_review_adding" <? if ($config_rainforest_enable_review_adding){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_review_adding"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Нужно выбрать, добавлять ли отзывы с Amazon с автоматическим переводом при разборе полной информации о товаре. Все дальнейшие настройки неактуальны при отключении данной.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Максимум отзывов на товар
							</td>
							<td>
								<input type="number" name="config_rainforest_max_review_per_product" value="<?php echo $config_rainforest_max_review_per_product; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Добавлять не более этого количества отзывов на один товар
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Минимальная оценка
							</td>
							<td>
								<input type="number" name="config_rainforest_min_review_rating" value="<?php echo $config_rainforest_min_review_rating; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Оценки на Amazon имеют значения от 1 до 5. Отзывы с рейтингом ниже заданного будут пропущены.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Максимальная длина отзыва
							</td>
							<td>
								<input type="number" name="config_rainforest_max_review_length" value="<?php echo $config_rainforest_max_review_length; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> На Amazon есть писатели, генерирующие отзывы длиной с "Войну и мир". Во избежание перегрузки переводчика и базы данных, мы пропускаем отзывы с длиной более заданной. Длина = количество символов без учёта разметки, которая удаляется.
								</span>
							</td>
						</tr>



						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>Переводчик, текст</b>
							</td>
						</tr>

						<tr>
							<td class="right">
								Ограничить описание до перевода
							</td>
							<td>
								<input type="number" name="config_rainforest_description_symbol_limit" value="<?php echo $config_rainforest_description_symbol_limit; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Ограничит описание заданным количеством символов по предложениям. Результирующее количество может быть несколько больше заданного числа.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Язык Amazon
							</td>
							<td class="center">
								<select name="config_rainforest_source_language">
									<?php foreach ($languages as $language) { ?>
										<?php if ($language['code'] == $config_rainforest_source_language) { ?>
											<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Amazon доступен не нескольки языках. Здесь нужно указать код языка, который будет доступен как основной язык контента на Amazon. Он обязательно должен быть создан в магазине и иметь тот же код ISO2, что и в Yandex (или Google) Translator API.
								</span>
							</td>
						</tr>

						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] != $config_rainforest_source_language) { ?>
								<tr>
									<td  class="right">
										Включить перевод <?php echo mb_strtoupper($language['code']); ?>
									</td>
									<td  class="center">
										<input id="config_rainforest_enable_language_<?php echo $language['code']; ?>" type="checkbox" class="checkbox" name="config_rainforest_enable_language_<?php echo $language['code']; ?>" <? if (${'config_rainforest_enable_language_' . $language['code']}){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_language_<?php echo $language['code']; ?>"></label>
									</td>
									<td>
										<span class="help">
											<i class="fa fa-info-circle"></i> Включить ли автоматический перевод при добавлении на этот язык. Если нет - поля в табличках описаний будут создаваться, но пустые. Перевод возможно будет сделать позже.
										</span>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>

						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>🤖 Интеграция с Open AI</b>
							</td>
						</tr>

						<tr>
							<td class="right">
								Экспортные названия
							</td>
							<td class="center">
								<input id="config_rainforest_export_names_with_openai" type="checkbox" class="checkbox" name="config_rainforest_export_names_with_openai" <? if ($config_rainforest_export_names_with_openai){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_export_names_with_openai"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если OpenAI включен, то при добавлении товара будет автоматически создаваться название товара для экспортных документов, длиной не более 50 символов (либо слов, зависит от запроса к AI). Для расширенной настройки нужно изменять значения в настройках магазина, в разделе OpenAI.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Длина экспортного названия
							</td>
							<td>
								<input type="number" name="config_openai_exportnames_length" value="<?php echo $config_openai_exportnames_length; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Длина задается в символах, либо словах (зависит от запроса к AI)
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Адекватные названия
							</td>
							<td class="center">
								<input id="config_rainforest_short_names_with_openai" type="checkbox" class="checkbox" name="config_rainforest_short_names_with_openai" <? if ($config_rainforest_short_names_with_openai){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_short_names_with_openai"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если OpenAI включен, то при добавлении товара его название будет сокращено (можно изменить в настройках) при помощи OpenAI
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Длина адекватного названия
							</td>
							<td>
								<input type="number" name="config_openai_shortennames_length" value="<?php echo $config_openai_shortennames_length; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Длина задается в символах, либо словах (зависит от запроса к AI)
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Сокращать до перевода
							</td>
							<td>
								<input id="config_openai_enable_shorten_names_before_translation" type="checkbox" class="checkbox" name="config_openai_enable_shorten_names_before_translation" <? if ($config_openai_enable_shorten_names_before_translation){ ?> checked="checked" <? } ?> value="1" /><label for="config_openai_enable_shorten_names_before_translation"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> сокращает названия до заданного количества символов до перевода
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Сокращать после перевода
							</td>
							<td>
								<input id="config_openai_enable_shorten_names_after_translation" type="checkbox" class="checkbox" name="config_openai_enable_shorten_names_after_translation" <? if ($config_openai_enable_shorten_names_after_translation){ ?> checked="checked" <? } ?> value="1" /><label for="config_openai_enable_shorten_names_after_translation"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> сокращает названия до заданного количества символов после перевода
								</span>
							</td>
						</tr>

					</table>
				</div>

				<div id="tab-pricelogic">
					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-cogs"></i> <b>Общие настройки</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Включить логику ценообразования
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_pricing" type="checkbox" class="checkbox" name="config_rainforest_enable_pricing" <? if ($config_rainforest_enable_pricing){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_pricing"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Для того, чтоб работали все настройки, необходимо включить ценообразование в принципе. Если эта настройка будет выключена - цены не будут переназначаться ни при каких условиях. Воркеры <i>Получение офферов с Amazon</i> и <i>Офферы для товаров в заказах</i> будут работать, однако контролировать только наличие в зависимости от текущих настроек
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Получать офферы только товаров с полной информацией
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_offers_only_for_filled" type="checkbox" class="checkbox" name="config_rainforest_enable_offers_only_for_filled" <? if ($config_rainforest_enable_offers_only_for_filled){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offers_only_for_filled"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Получать ли офферы только для товаров, которые заполнены информацией с Amazon в результате работы воркеров <i>Парсер данных о товарах Amazon</i> и <i>Парсер данных о товарах Amazon L2</i>. Если отключено - то поиск офферов будет произведен для всех товаров. Следует отключать для магазинов, в которых возможно ручное добавление. 
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Получать офферы сразу после заказа
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_offers_after_order" type="checkbox" class="checkbox" name="config_rainforest_enable_offers_after_order" <? if ($config_rainforest_enable_offers_after_order){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offers_after_order"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если эта настройка включена, то при оформлении заказа товары добавляются в очередь на получение офферов помимо основного потока. Это нужно для оперативного контроля наличия закупщиком заказанных товаров. Очередь обрабатывает воркер <i>Офферы для товаров в заказах</i>, поэтому для корректной работы он должен быть включен.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Тестовый ASIN
							</td>
							<td style="width:50px;" class="center">
								<input type="text" name="config_rainforest_test_asin" value="<?php echo $config_rainforest_test_asin; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Нужно для отладки, чтоб не менять постоянно тестовый асин в коде
								</span>
							</td>
						</tr>


						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-cogs"></i> <b>Изменение статуса и наличия для вручную добавленных товаров</b>
								<span class="help">товары с маркером 'added_from_amazon' = 0</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-hand-peace-o"></i> Изменять статус наличия для вручную добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_nooffers_action_for_manual" type="checkbox" class="checkbox" name="config_rainforest_nooffers_action_for_manual" <? if ($config_rainforest_nooffers_action_for_manual){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_nooffers_action_for_manual"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Изменять ли статус товара в зависимости от того, есть у него офферы на Amazon или нет. Это изменяет статусы по складам, проверяя также наличие на конкретном складе, а не общий статус.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-hand-peace-o"></i> Изменять количество для вручную добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_nooffers_quantity_for_manual" type="checkbox" class="checkbox" name="config_rainforest_nooffers_quantity_for_manual" <? if ($config_rainforest_nooffers_quantity_for_manual){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_nooffers_quantity_for_manual"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включена предыдущая настройка <i>Изменять статус наличия</i>, и включена эта настройка, то помимо изменения статуса будет также изменяться количество товара по схеме есть в наличии = 9999, нет в наличии = 0.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-fa-hand-peace-o"></i> Статус, если нет офферов для вручную добавленных товаров
							</td>
							<td style="width:100px;" class="center">
								<select name="config_rainforest_nooffers_status_id_for_manual" style="width:90px;">
									<?php foreach ($stock_statuses as $stock_status) { ?>
										<?php if ($stock_status['stock_status_id'] == $config_rainforest_nooffers_status_id_for_manual) { ?>
											<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включена предыдущая настройка <i>Изменять статус наличия</i>, то при отсутствии офферов на Amazon, товары на складах, где товара нет в наличии - будут иметь этот статус. Если настройка <i>Изменять статус наличия</i> отключена, то статус изменен не будет.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-hand-peace-o"></i> Удалять, если нет офферов для вручную добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_delete_no_offers_for_manual" type="checkbox" class="checkbox" name="config_rainforest_delete_no_offers_for_manual" <? if ($config_rainforest_delete_no_offers_for_manual){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delete_no_offers_for_manual"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если товара нет на Amazon несколько итераций проверок подряд, то он будет удалён и добавлен в игнорируемые, в случае включения <i class="fa fa-amazon"></i>ASIN по умолчанию. Количество итераций задается следующей настройкой
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-hand-peace-o"></i> Количество итераций для удаления для вручную добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_delete_no_offers_counter_for_manual" value="<?php echo $config_rainforest_delete_no_offers_counter_for_manual; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включена предыдущая настройка <i>Удалять, если нет офферов</i>, то в случае когда товара нет в наличии на Amazon это количество раз - он будет удален.
								</span>
							</td>
						</tr>




						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-cogs"></i> <b>Изменение статуса и наличия для автоматически добавленных товаров</b>
								<span class="help">товары с маркером 'added_from_amazon' = 1</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-amazon"></i> Изменять статус наличия для автоматически добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_nooffers_action" type="checkbox" class="checkbox" name="config_rainforest_nooffers_action" <? if ($config_rainforest_nooffers_action){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_nooffers_action"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Изменять ли статус товара в зависимости от того, есть у него офферы на Amazon или нет. Это изменяет статусы по складам, проверяя также наличие на конкретном складе, а не общий статус.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-amazon"></i> Изменять количество для автоматически добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_nooffers_quantity" type="checkbox" class="checkbox" name="config_rainforest_nooffers_quantity" <? if ($config_rainforest_nooffers_quantity){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_nooffers_quantity"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включена предыдущая настройка <i>Изменять статус наличия</i>, и включена эта настройка, то помимо изменения статуса будет также изменяться количество товара по схеме есть в наличии = 9999, нет в наличии = 0.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								<i class="fa fa-amazon"></i> Статус, если нет офферов для автоматически добавленных товаров
							</td>
							<td style="width:100px;" class="center">
								<select name="config_rainforest_nooffers_status_id" style="width:90px;">
									<?php foreach ($stock_statuses as $stock_status) { ?>
										<?php if ($stock_status['stock_status_id'] == $config_rainforest_nooffers_status_id) { ?>
											<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включена предыдущая настройка <i>Изменять статус наличия</i>, то при отсутствии офферов на Amazon, товары на складах, где товара нет в наличии - будут иметь этот статус. Если настройка <i>Изменять статус наличия</i> отключена, то статус изменен не будет.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								<i class="fa fa-amazon"></i> Удалять, если нет офферов для автоматически добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_delete_no_offers" type="checkbox" class="checkbox" name="config_rainforest_delete_no_offers" <? if ($config_rainforest_delete_no_offers){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_delete_no_offers"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если товара нет на Amazon несколько итераций проверок подряд, то он будет удалён и добавлен в игнорируемые, в случае включения <i class="fa fa-amazon"></i>ASIN по умолчанию. Количество итераций задается следующей настройкой
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								<i class="fa fa-amazon"></i> Количество итераций для удаления для автоматически добавленных товаров
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_delete_no_offers_counter" value="<?php echo $config_rainforest_delete_no_offers_counter; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включена предыдущая настройка <i>Удалять, если нет офферов</i>, то в случае когда товара нет в наличии на Amazon это количество раз - он будет удален.
								</span>
							</td>
						</tr>


						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-cogs"></i> <b>Исключения из логики ценообразования</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Получать офферы для товаров на складе
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_enable_offers_for_stock" type="checkbox" class="checkbox" name="config_rainforest_enable_offers_for_stock" <? if ($config_rainforest_enable_offers_for_stock){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offers_for_stock"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Нужно ли получать офферы для товаров, которые есть на локальных складах сейчас. Если отключено, то мы не будем получать офферы и обновлять цену товарам, которые есть в наличии хотя б на одном из складов. Это работает вне зависимости от того, когда была произведена последняя закупка товара.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Пропускать товары с актуальной скидкой
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_disable_offers_if_has_special" type="checkbox" class="checkbox" name="config_rainforest_disable_offers_if_has_special" <? if ($config_rainforest_disable_offers_if_has_special){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_disable_offers_if_has_special"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> если у товара на момент обновления есть актуальная скидка, то его цена не будет обновляться
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Пропускать товары с признаком "Не обновлять цены"
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_disable_offers_use_field_ignore_parse" type="checkbox" class="checkbox" name="config_rainforest_disable_offers_use_field_ignore_parse" <? if ($config_rainforest_disable_offers_use_field_ignore_parse){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_disable_offers_use_field_ignore_parse"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> если у товара включен признак "не обновлять цены", используемое для 1С и парсеров, то его цены также не будут обновляться
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Пропускать товары, которые были закуплены
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_pass_offers_for_ordered" type="checkbox" class="checkbox" name="config_rainforest_pass_offers_for_ordered" <? if ($config_rainforest_pass_offers_for_ordered){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_pass_offers_for_ordered"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включено, то товары, которые были куплены за некоторое количество дней (определяется следующей настройкой) - будут исключены из логики получения офферов и ценообразования Amazon. Это нужно для уменьшения флуктуаций цен товаров.
								</span>
							</td>
						</tr>
						<tr>
							<td class="right">
								Количество дней от закупки
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_pass_offers_for_ordered_days" value="<?php echo $config_rainforest_pass_offers_for_ordered_days; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> В случае включения предыдущей настройки <i>Пропускать товары, которые были закуплены</i> данная настройка определяет количество дней, которые должны пройти от последней закупки товара для того, чтоб мы снова начали получать по нему офферы.
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-cogs"></i> <b>Исключения по доставке</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Максимальное количество дней
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_max_delivery_days_for_offer" value="<?php echo $config_rainforest_max_delivery_days_for_offer; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Обычно если сроки больше недели то товара у поставщика нет и он точно так же где-то ждёт его под заказ и не факт что он там есть. Как-то так. (с) Валера. Офферы, которые имеют срок доставки от этого количества дней - будут проигнорированы и не будут участвовать в формировании цены и наличия.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Максимальная стоимость доставки, абсолютное значение
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_max_delivery_price" value="<?php echo $config_rainforest_max_delivery_price; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Исключать оффер, если стоимость доставки превышает это абсолютное значение
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Максимальная стоимость доставки, от цены
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_max_delivery_price_multiplier" value="<?php echo $config_rainforest_max_delivery_price_multiplier; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Исключать оффер, если стоимость доставки превышает цену товара в столько раз
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-cogs"></i> <b>Исключения поставщиков</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Минимальный рейтинг поставщика Amazon
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_supplierminrating" value="<?php echo $config_rainforest_supplierminrating; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если рейтинг поставщика на Amazon менее этого значения, то оффер будет исключен при переборе и установке цен. Внимание: если все офферы товара имеют поставщиков с плохим рейтингом - товар приравнивается к такому, которого нет в наличии.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Минимальный рейтинг поставщика Amazon
							</td>
							<td style="width:50px;" class="center">
								<input type="number" name="config_rainforest_supplierminrating_inner" value="<?php echo $config_rainforest_supplierminrating_inner; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если рейтинг поставщика в внутреннем справочнике менее этого значения, то оффер будет исключен при переборе и установке цен. Внимание: если все офферы товара имеют поставщиков с плохим рейтингом - товар приравнивается к такому, которого нет в наличии. Управление справочником поставщиков и их рейтингами можно найти в разделе <i>Закупка -> Справочник поставщиков</i>.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Пропускать офферы из не-основных стран
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_skip_not_native_offers" type="checkbox" class="checkbox" name="config_rainforest_skip_not_native_offers" <? if ($config_rainforest_skip_not_native_offers){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_skip_not_native_offers"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если получилось определить страну поставщика, то эта настройка исключает офферы поставщиков из любой другой страны, кроме заданной ниже</i>.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Основная страна для Amazon
							</td>
							<td style="width:50px;" class="center">
								<input type="text" name="config_rainforest_native_country_code" value="<?php echo $config_rainforest_native_country_code; ?>" size="50" style="width:50px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> ISO2-код страны. Если включена предыдущая настройка, то этот код необходимо задать, иначе все офферы будут считаться плохими
									<br />
									<?php foreach ($supplier_countries as $supplier_country) { ?>
										<span style="margin-right:5px;"><?php echo $supplier_country; ?></span>
									<?php } ?>
								</span>
							</td>
						</tr>

					</table>
				</div>

				<div id="tab-priceformula">									
					<div style="float:left; width:59%;">
						<div>							
							<textarea name="config_rainforest_main_formula" rows="6" style="width:70%; font-size:11px; padding:6px; float:left; border-color:#7F00FF;" ><?php echo $config_rainforest_main_formula; ?></textarea>

							<span style="float:right; width:10%; font-size:32px; margin-left:20px; cursor:pointer;" onclick="$('#formulas_overload').toggle();">ЕЩЕ <i class="fa fa-caret-down"></i></span>
							<input type="number" step="1" name="config_rainforest_main_formula_count" value="<?php echo $config_rainforest_main_formula_count; ?>" size="50" style="float:right; width:10%;font-size:14px; padding:6px;" />						
						</div>

						<div class="clr"></div>
						<div id="formulas_overload" style="display:none;">
							<table class="form">
								<tr>
									<td width="1%">										
									</td>
									<td width="5%">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Цена закупки от, <?php echo $config_currency; ?></span>
									</td>
									<td width="5%">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Цена закупки до, <?php echo $config_currency; ?></span>
									</td>
									<td width="5%">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Себестоимость, если нет веса</span>
									</td>
									<td width="5%">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Умножать, если нет веса</span>
									</td>
									<td width="84%">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Переназначение основной формулы</span>
									</td>
								</tr>
								<?php for ($crmfc = 1; $crmfc <= $config_rainforest_main_formula_count; $crmfc++){ ?>
									<tr>
										<td width="1%">
											<b><?php echo $crmfc; ?></b>
										</td>
										<td width="5%">
											<input type="number" step="1" name="config_rainforest_main_formula_min_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_min_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#00ad07;" />
										</td>
										<td width="5%">
											<input type="number" step="1" name="config_rainforest_main_formula_max_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_max_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#cf4a61;" />
										</td>
										<td width="5%">
											<input type="number" step=".01" name="config_rainforest_main_formula_costprice_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_costprice_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#D69241;" />
										</td>
										<td width="5%">
											<input type="number" step=".1" name="config_rainforest_main_formula_default_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_default_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#D69241;" />
										</td>
										<td width="84%">
											<textarea name="config_rainforest_main_formula_overload_<?php echo $crmfc; ?>" rows="6" style="width:95%; font-size:11px; border-color:#7F00FF;" ><?php echo ${'config_rainforest_main_formula_overload_' . $crmfc}; ?></textarea>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>

						<div class="clr"></div>
						<div id="calculator_results" style="min-height:500px; margin-top:10px;">
						</div>
					</div>

					<div style="float:right; width:39%;">
						<div>
							<button class="button" style="padding:10px; width:100%; font-size:20px; margin-right:4px;" onclick="savePriceModel();"><i class="fa fa-check"></i> Сохранить</button>
							<span class="help"><i class="fa fa-info-circle"></i> В этом разделе изменение полей на лету отключено, чтоб изменения формулы и коэффициентов не влияли на текущую модель. Если хочешь изменить модель ценообразования после тестирования формул и (или) коэффициентов - нужно нажать кнопку сохранить и дождаться окончания процесса. После нажатия кнопки ценовая модель изменится и цены товара будут формироваться исходя из новой модели. Любое изменение поля вызывает запрос на тестовый пересчёт цен.</span>
						</div>

						<table class="list">
						<tr>
							<td colspan="2" class="left" style="color:#D69241;">
								<i class="fa fa-calculator"></i> <b>Калькулятор</b>
							</td>
						</tr>
						<tr>
							<td>
								Рандомные товары
								<span class="help"><i class="fa fa-info-circle"></i> иначе от дешевых к дорогим</span>
							</td>
							<td>
								<input id="calculator_show_random" type="checkbox" class="checkbox" name="calculator_show_random" value="1" /><label for="calculator_show_random"></label>								
							</td>
						</tr>
						<tr>
							<td>
								Лимит товаров
								<span class="help"><i class="fa fa-info-circle"></i> на каждый ценовой диапазон</span>
							</td>
							<td>
								<input type="number" step="1" name="calculator_limit_products" value="3" style="width:100px;" />								
							</td>
						</tr>
						<tr>
							<td>
								Ценовые диапазоны
								<span class="help"><i class="fa fa-info-circle"></i> от-до через пробел</span>
							</td>
							<td>
								<input type="text" name="calculator_zones_config" value="<?php echo $zones_config;?>" style="width:90%;" />								
							</td>
						</tr>
						<tr>
							<td>
								Конкретные товары
								<span class="help"><i class="fa fa-info-circle"></i> идентификаторы через пробел</span>
							</td>
							<td>
								<input type="text" name="calculator_explicit_products" value="" style="width:90%;" />								
							</td>
						</tr>
						<tr>
							<td>
								Товары из категорий
								<span class="help"><i class="fa fa-info-circle"></i> идентификаторы через пробел</span>
							</td>
							<td>
								<input type="text" name="calculator_explicit_categories" value="" style="width:90%;" />								
							</td>
						</tr>
						<tr>
							<td>								
							</td>
							<td>
								<button class="button" id="button-recalculate" style="cursor:pointer;padding: 7px; color: #6A6A6A;  border: 2px solid #6A6A6A;  border-radius: 5px;  background-color: #fff;" onclick="recalculate(); return false;"><i class="fa fa-refresh"></i> Пересчитать</button>
							</td>
						</tr>
					</table>

						
						<table class="list">
							<tr>
								<td colspan="2" class="left" style="color:#D69241;">
									<i class="fa fa-calculator"></i> <b>Операнды</b>
								</td>
							</tr>

							<tr>
								<td><b>PRICE</b></td><td><i class="fa fa-info-circle"></i> цена товара у поставщика</td>
							</tr>

							<tr>
								<td><b>WEIGHT</b></td><td> <i class="fa fa-info-circle"></i> подсчитанный вес товара</td>
							</tr>

							<tr>
								<td><b>KG_LOGISTIC</b></td><td><i class="fa fa-info-circle"></i> стоимость логистики одного килограмма</td>
							</tr>

							<?php foreach ($stores as $store) { ?>
								<tr>
									<td class="right">
										<?php echo $store['name']; ?>
									</td>
									<td class="center">
										<input type="number" step="0.01" name="config_rainforest_kg_price_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_kg_price_' . $store['store_id']}; ?>" style="width:100px;" />
									</td>
								</tr>
							<?php } ?>

							<tr>
								<td><b>VAT_SRC</b></td><td><i class="fa fa-info-circle"></i> VAT/НДС страны - поставщика</td>
							</tr>

							<?php foreach ($stores as $store) { ?>
								<tr>
									<td class="right">
										<?php echo $store['name']; ?>
									</td>
									<td class="center">
										<input type="number" step="0.01" name="config_rainforest_formula_vat_src_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_formula_vat_src_' . $store['store_id']}; ?>" style="width:100px;" />
									</td>
								</tr>
							<?php } ?>

							<tr>
								<td><b>VAT_DST</b></td><td><i class="fa fa-info-circle"></i> VAT/НДС страны - получателя</td>
							</tr>

							<?php foreach ($stores as $store) { ?>
								<tr>
									<td class="right">
										<?php echo $store['name']; ?>
									</td>
									<td class="center">
										<input type="number" step="0.01" name="config_rainforest_formula_vat_dst_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_formula_vat_dst_' . $store['store_id']}; ?>" style="width:100px;" />
									</td>
								</tr>
							<?php } ?>

							<tr>
								<td><b>TAX</b></td><td><i class="fa fa-info-circle"></i> дополнительный налог</td>
							</tr>

							<?php foreach ($stores as $store) { ?>
								<tr>
									<td class="right">
										<?php echo $store['name']; ?>
									</td>
									<td class="center">
										<input type="number" step="0.01" name="config_rainforest_formula_tax_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_formula_tax_' . $store['store_id']}; ?>" style="width:100px;" />
									</td>
								</tr>
							<?php } ?>

							<tr>
								<td><b>SUPPLIER</b></td><td><i class="fa fa-info-circle"></i> процент поставщика</td>
							</tr>

							<?php foreach ($stores as $store) { ?>
								<tr>
									<td class="right">
										<?php echo $store['name']; ?>
									</td>
									<td class="center">
										<input type="number" step="0.01" name="config_rainforest_formula_supplier_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_formula_supplier_' . $store['store_id']}; ?>" style="width:100px;" />
									</td>
								</tr>
							<?php } ?>

							<tr>
								<td><b>INVOICE</b></td><td><i class="fa fa-info-circle"></i> коэффициент инвойса</td>
							</tr>

							<?php foreach ($stores as $store) { ?>
								<tr>
									<td class="right">
										<?php echo $store['name']; ?>
									</td>
									<td class="center">
										<input type="number" step="0.01" name="config_rainforest_formula_invoice_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_formula_invoice_' . $store['store_id']}; ?>" style="width:100px;" />
									</td>
								</tr>
							<?php } ?>

							<tr>
								<td><b>:COSTDVDR:</b></td><td><i class="fa fa-info-circle"></i> разделитель себестоимости для подсчёта рентабельности</td>
							</tr>

							<tr>
								<td><b>PLUS</b></td><td><i class="fa fa-info-circle"></i> операция добавления (знак +)</td>
							</tr>

							<tr>
								<td><b>MINUS</b></td><td><i class="fa fa-info-circle"></i> операция отрицания (знак -)</td>
							</tr>

							<tr>
								<td><b>MULTIPLY</b></td><td><i class="fa fa-info-circle"></i> операция умножения (знак *)</td>
							</tr>

							<tr>
								<td><b>DIVIDE</b></td><td><i class="fa fa-info-circle"></i> операция деления (знак /)</td>
							</tr>	
						</table>
					
					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-calculator"></i> <b>Другие параметры</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Цена по-умолчанию
							</td>
							<td style="width:100px;" class="center">
								<select name="config_rainforest_default_store_id">
									<option value="-1" <?php if (-1 == $config_rainforest_default_store_id) { ?>selected="selected"<? } ?>>Переназначать все</option>
									<?php foreach ($stores as $store) { ?>
										<option value="<?php echo $store['store_id']; ?>" <?php if ($store['store_id'] == $config_rainforest_default_store_id) { ?>selected="selected"<? } ?>><?php echo $store['name']; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Скомпилированная цена для выбранного магазина будет установлена как основная цена товара. Всем остальным магазинам цена будет переназначена и зафиксирована. В случае если выбрано <i>Переназначать все</i>, то основная цена установлена не будет, но цены всех магазинов будут переназначены.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Объемный вес макс Х
							</td>
							<td style="width:100px;" class="center">
								<input type="number" step="0.1" name="config_rainforest_volumetric_max_wc_multiplier" value="<?php echo $config_rainforest_volumetric_max_wc_multiplier; ?>" style="width:100px;" />
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Защита от некорректного большого объемного веса, в случае если Амазон отдает габарит товара, который фактически складывается. Если объемный вес будет больше в Х раз чем фактический, то объемный учтён не будет.
								</span>
							</td>
						</tr>						

						<?php foreach ($stores as $store) { ?>
							<tr>
								<td class="right">
									Использовать объемный вес, <?php echo $store['name']; ?>
								</td>
								<td style="width:100px;" class="center">
									<input id="config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>" type="checkbox" class="checkbox" name="config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>" <? if (${'config_rainforest_use_volumetric_weight_' . $store['store_id']}){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Переменная <b>WEIGHT</b> означает не просто вес, а объемный вес товара, в случае успешного подсчёта габаритов товара. Подробнее можно посмотреть на Википедии.
									</span>
								</td>
							</tr>
						<?php } ?>

						<?php foreach ($stores as $store) { ?>
							<tr>
								<td class="right">
									Коэффициент объемного веса, <?php echo $store['name']; ?>
								</td>
								<td style="width:100px;" class="center">
									<input type="number" step="100" name="config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_volumetric_weight_coefficient_' . $store['store_id']}; ?>" style="width:100px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Делитель для подсчёта объемного веса. Длина (см) × Ширина (см) × Высота (см) / Делитель = Объёмный вес 
									</span>
								</td>
							</tr>
						<?php } ?>

						<?php foreach ($stores as $store) { ?>
							<tr>
								<td class="right">
									Простое ЦО, если не задан вес, <?php echo $store['name']; ?>
								</td>
								<td style="width:100px;" class="center">
									<input type="number" step="0.1" name="config_rainforest_default_multiplier_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_default_multiplier_' . $store['store_id']}; ?>" style="width:100px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> В случае если у товара не задан вес, а также не задан вес по-умолчанию для основной категории товара, то к такому товару применяется простая модель ЦО (умножать на этот множитель)
									</span>
								</td>
							</tr>
						<?php } ?>

						<?php foreach ($stores as $store) { ?>
							<tr>
								<td class="right">
									Множитель себестоимости, <?php echo $store['name']; ?>
								</td>
								<td style="width:100px;" class="center">
									<input type="number" step="0.01" name="config_rainforest_default_costprice_multiplier_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_default_costprice_multiplier_' . $store['store_id']}; ?>" style="width:100px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> В случае если у товара не задан вес, а также не задан вес по-умолчанию для основной категории товара, то для подсчёта себестоимости и рентабельности нужно задать этот коэффициент
									</span>
								</td>
							</tr>
						<?php } ?>

						<?php foreach ($stores as $store) { ?>
							<tr>
								<td class="right">
									Максимальный множитель, <?php echo $store['name']; ?>
								</td>
								<td style="width:100px;" class="center">
									<input type="number" step="0.1" name="config_rainforest_max_multiplier_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_max_multiplier_' . $store['store_id']}; ?>" style="width:100px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Максимальная наценка, Х раз. Для товаров у которых вес задан очень некорректно. Если продажная цена по формулам будет в Х раз больше, чем цена закупки, то будет использован "множитель без веса"
									</span>
								</td>
							</tr>
						<?php } ?>
					</table>
				</div>
				</div>

				<div id="tab-store-settings">
					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#cf4a61;">
								<i class="fa fa-cogs"></i> <b>Общий режим</b>
							</td>
						</tr>

						<tr>
							<td class="right">
								Режим работы с Amazon
							</td>
							<td style="width:50px;" class="center">
								<input id="config_enable_amazon_specific_modes" type="checkbox" class="checkbox" name="config_enable_amazon_specific_modes" <? if ($config_enable_amazon_specific_modes){ ?> checked="checked" <? } ?> value="1" /><label for="config_enable_amazon_specific_modes"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Включает или отключает специфические настройки и логику для магазинов, у которых большая часть контента загружается с Amazon. Для магазинов, которые используют ручное наполнение - это лучше не использовать, поскольку возможны непредвиденные изменения контента в случае выполнения некорректных операций. Включение также разблокирует супер-режимы группового редактирования вариантов, и значений атрибутов. 
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" class="left" style="color:#cf4a61;">
								<i class="fa fa-cogs"></i> <b>Настройки режимов работы фронта</b>
							</td>
						</tr>

						<tr>
							<td class="right">
								Отображать список брендов в основном меню сайта
							</td>
							<td style="width:50px;" class="center">
								<input id="config_brands_in_mmenu" type="checkbox" class="checkbox" name="config_brands_in_mmenu" <? if ($config_brands_in_mmenu){ ?> checked="checked" <? } ?> value="1" /><label for="config_brands_in_mmenu"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Включает или отключает подготовку списка брендов для вывода на фронте в основном меню сайта.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Второй уровень подкатегорий в категориях
							</td>
							<td style="width:50px;" class="center">
								<input id="config_second_level_subcategory_in_categories" type="checkbox" class="checkbox" name="config_second_level_subcategory_in_categories" <? if ($config_second_level_subcategory_in_categories){ ?> checked="checked" <? } ?> value="1" /><label for="config_second_level_subcategory_in_categories"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> По-умолчанию на фронте в категориях выполняется отбор только одного уровня подкатегорий. Если включить, то будет отбираться также второй уровень дочерних подкатегорий по дереву от текущей<br />
									<span style="color:red"><i class="fa fa-exclamation-triangle"></i> В случае, если используется фильтр MegaFilter или OCFilter, эта настройка также переназначает в настройку фильтра на выводе!</span>
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Товары только в крайних категориях
							</td>
							<td style="width:50px;" class="center">
								<input id="config_disable_filter_subcategory" type="checkbox" class="checkbox" name="config_disable_filter_subcategory" <? if ($config_disable_filter_subcategory){ ?> checked="checked" <? } ?> value="1" /><label for="config_disable_filter_subcategory"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включить эту настройку, то товары будут отображаться без учёта подкатегорий, а только по прямой привязке товара к категории. В общем случае это означает, что в категориях, имеющих дочерние - товары отображаться не будут.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Отображать подкатегории во всех категориях
							</td>
							<td style="width:50px;" class="center">
								<input id="config_display_subcategory_in_all_categories" type="checkbox" class="checkbox" name="config_display_subcategory_in_all_categories" <? if ($config_display_subcategory_in_all_categories){ ?> checked="checked" <? } ?> value="1" /><label for="config_display_subcategory_in_all_categories"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Если включить эту настройку, то подкатегории будут отображаться сверху списка товаров в всех категориях, имеющих дочерние. Иначе отображение включено только для корневых категорий (помеченных L1 в справочнике категорий).
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Отображать только товары с загруженной информацией
							</td>
							<td style="width:50px;" class="center">
								<input id="config_rainforest_show_only_filled_products_in_catalog" type="checkbox" class="checkbox" name="config_rainforest_show_only_filled_products_in_catalog" <? if ($config_rainforest_show_only_filled_products_in_catalog){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_show_only_filled_products_in_catalog"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Для магазинов, работающих в режиме <i>Режим работы с Amazon</i>. Если <i>Режим работы с Amazon</i> отключен, то эта настройка ни на что не влияет. Разрешает или запрещает отображение товаров, которые уже были загружены первично воркером <i>Парсер новых товаров Amazon</i>, однако еще не загружена полная информация (описания, фото, атрибуты, и прочее) одним из воркеров <i>Парсер данных о товарах Amazon</i>, <i>Парсер данных о товарах Amazon L2</i>
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Счетчик товаров
							</td>
							<td style="width:50px;" class="center">
								<input id="config_product_count" type="checkbox" class="checkbox" name="config_product_count" <? if ($config_product_count){ ?> checked="checked" <? } ?> value="1" /><label for="config_product_count"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Количество товаров в категориях отображается рядом с названием категории в меню, дереве и прочих местах. Пересчет количества товаров выполняется ежедневно, но не на лету, поскольку это очень сильно нагружает БД.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Отображать только товары с основной ценой
							</td>
							<td style="width:50px;" class="center">
								<input id="config_no_zeroprice" type="checkbox" class="checkbox" name="config_no_zeroprice" <? if ($config_no_zeroprice){ ?> checked="checked" <? } ?> value="1" /><label for="config_no_zeroprice"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Движок имеет несколько уровней переназначения цен и ценовых политик. Включение этой настройки исключит из показа на фронте товары, которые не имеют заданной основной цены (или цены по-умолчанию, поле price в товаре). В случае работы с РРЦ эту настройку лучше отключать. Если отключено, то производится проверка наличия хотя бы одной из цен переназначений. В любом случае, товары, имеющие все цены нулевыми не будут выведены на фронте. Для магазинов, работающих в <i>Режиме работы с Amazon</i> товары без основной цены - это товары, по которым еще не производилось получение офферов воркером <i>Получение офферов с Amazon</i>
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Прятать артикул в карте товара
							</td>
							<td style="width:50px;" class="center">
								<input id="config_product_hide_sku" type="checkbox" class="checkbox" name="config_product_hide_sku" <? if ($config_product_hide_sku){ ?> checked="checked" <? } ?> value="1" /><label for="config_product_hide_sku"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Включает или отключает отображение артикула в карте товара на фронте, но не из микроразметки. 
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Подменять SKU/MODEL на код товара
							</td>
							<td style="width:50px;" class="center">
								<input id="config_product_replace_sku_with_product_id" type="checkbox" class="checkbox" name="config_product_replace_sku_with_product_id" <? if ($config_product_replace_sku_with_product_id){ ?> checked="checked" <? } ?> value="1" /><label for="config_product_replace_sku_with_product_id"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Глобально подменяет на фронте артикула на внутренний код товара (целое число). Пожалуйста, используйте с большой осторожностью. Это заменит SKU везде, и в микроразметке в том числе. При этом поиск будет работать как и в обычном режиме и выдавать результаты так же и по артикулу. Дополнительно можно задать префикс для кода товара в общих настройках.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Логика сортировки товаров по-умолчанию
							</td>
							<td style="width:50px;" class="center">
								<select name="config_sort_default" style="width:70px;">
									<?php foreach ($this->registry->get('sorts_available') as $sort_name => $sort_sort) { ?>
										<?php if ($config_sort_default == $sort_sort) { ?>
											<option value="<?php echo $sort_sort; ?>" selected="selected"><?php echo $sort_name; ?></option>
										<?php } else { ?>
											<option value="<?php echo $sort_sort; ?>"><?php echo $sort_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Выбор сортировки товаров по-умолчанию в категориях, брендах, поиске и прочих листингах. Все возможные сортировки описаны в файле system/config/sorts.json. Важно: это вторичная сортировка, первичной является сортировка по наличию - товары на складе в текущей стране (если их несколько), либо те, которые есть у поставщика - будут отображены в начале листинга.
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Порядок сортировки
							</td>
							<td style="width:50px;" class="center">
								<select name="config_order_default">
									<?php if ($config_order_default == 'ASC') { ?>
										<option value="ASC" selected="selected">ASC</option>
										<option value="DESC">DESC</option>
									<?php } else { ?>													
										<option value="ASC">ASC</option>
										<option value="DESC"  selected="selected">DESC</option>
									<? } ?>
								</select>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> DESC - от большего к меньшему (по уменьшению), ASC - от меньшего к большему (по возрастанию)
								</span>
							</td>
						</tr>

						<tr>
							<td class="right">
								Включить отображение информации о сроках
							</td>
							<td style="width:50px;" class="center">
								<input id="config_delivery_outstock_enable" type="checkbox" class="checkbox" name="config_delivery_outstock_enable" <? if ($config_delivery_outstock_enable){ ?> checked="checked" <? } ?> value="1" /><label for="config_delivery_outstock_enable"></label>
							</td>
							<td>
								<span class="help">
									<i class="fa fa-info-circle"></i> Включает либо отключает вывод информации о сроках доставки на карте товара и в корзине, для товаров не в наличии. Для товаров в наличии на складе в стране сроки будут отображены в любом случае.
								</span>
							</td>
						</tr>


					</table>
				</div>
			</div>			


			<script type="text/javascript">
				function recalculate(){
					var mainFormula 				= $('textarea[name=config_rainforest_main_formula]').val();
					var weightCoefficient 			= $('input[name=config_rainforest_kg_price_0]').val();
					var defaultMultiplier 			= $('input[name=config_rainforest_default_multiplier_0]').val();
					var defaultCostPriceMultiplier	= $('input[name=config_rainforest_default_costprice_multiplier_0]').val();
					var maxMultiplier 				= $('input[name=config_rainforest_max_multiplier_0]').val();
					var useVolumetricWeight 		= $('input[name=config_rainforest_use_volumetric_weight_0]').attr('checked')?1:0;
					var volumetricWeightCoefficient = $('input[name=config_rainforest_volumetric_weight_coefficient_0]').val();
					var volumetricMaxWCMultiplier 	= $('input[name=config_rainforest_volumetric_max_wc_multiplier]').val();
					var showRandomProducts 			= $('input[name=calculator_show_random]').attr('checked')?1:0;
					var limitProducts				= $('input[name=calculator_limit_products]').val();
					var zonesConfig					= $('input[name=calculator_zones_config]').val();
					var explicitProducts			= $('input[name=calculator_explicit_products]').val();
					var explicitCategories			= $('input[name=calculator_explicit_categories]').val();
					<?php for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){ ?>	
						var mainFormula_min_<?php echo $crmfc; ?> = $('input[name=config_rainforest_main_formula_min_<?php echo $crmfc; ?>]').val();
						var mainFormula_max_<?php echo $crmfc; ?> = $('input[name=config_rainforest_main_formula_max_<?php echo $crmfc; ?>]').val();
						var mainFormula_costprice_<?php echo $crmfc; ?> = $('input[name=config_rainforest_main_formula_costprice_<?php echo $crmfc; ?>]').val();
						var mainFormula_default_<?php echo $crmfc; ?> = $('input[name=config_rainforest_main_formula_default_<?php echo $crmfc; ?>]').val();
						var mainFormula_overload_<?php echo $crmfc; ?> = $('textarea[name=config_rainforest_main_formula_overload_<?php echo $crmfc; ?>]').val();						
					<?php } ?>

					$.ajax({
						type: 'POST',
						dataType: 'html',
						url: 'index.php?route=setting/rnf/calculate&token=<?php echo $token; ?>',
						data: {
							main_formula: 					mainFormula,
							<?php for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){ ?>	
								main_formula_min_<?php echo $crmfc; ?>: mainFormula_min_<?php echo $crmfc; ?>,
								main_formula_max_<?php echo $crmfc; ?>: mainFormula_max_<?php echo $crmfc; ?>,
								main_formula_costprice_<?php echo $crmfc; ?>: mainFormula_costprice_<?php echo $crmfc; ?>,
								main_formula_default_<?php echo $crmfc; ?>: mainFormula_default_<?php echo $crmfc; ?>,
								main_formula_overload_<?php echo $crmfc; ?>: mainFormula_overload_<?php echo $crmfc; ?>,
							<?php } ?>
							weight_coefficient: 			weightCoefficient,					
							default_multiplier: 			defaultMultiplier,
							default_costprice_multipiler:   defaultCostPriceMultiplier,
							max_multiplier: 				maxMultiplier,
							use_volumetric_weight: 			useVolumetricWeight,
							volumetric_weight_coefficient: 	volumetricWeightCoefficient,
							volumetric_max_wc_multiplier:   volumetricMaxWCMultiplier,
							show_random_products:  			showRandomProducts,
							limit_products:  				limitProducts,
							zones_config:  					zonesConfig,
							explicit_products: 				explicitProducts,
							explicit_categories: 			explicitCategories
						},
						beforeSend: function(){
							$('#calculator_results').html('<i class="fa fa-calculator" style="font-size:128px"></i>');
							$('#button-recalculate').html('<i class="fa fa-spinner fa-spin"></i> считаем');
						},
						success: function(html){
							$('#calculator_results').html(html);
							$('#button-recalculate').html('<i class="fa fa-refresh"></i> Пересчитать');
						}
					});					
				}

				$('#tab-priceformula select, #tab-priceformula textarea, #tab-priceformula input[type=checkbox], #tab-priceformula input[type=text], #tab-priceformula input[type=number]').bind('change', function() {
					recalculate();
				});		

				function saveSettingAjax(key, value, elem){
					var store_id = $('input[name=store_id]').val();

					$.ajax({
						type: 'POST',
						async: false,
						url: 'index.php?route=setting/setting/editSettingAjax&store_id=' + store_id + '&token=<?php echo $token; ?>',
						data: {
							key: key,
							value: value						
						},
						beforeSend: function(){
							if (elem){
								elem.css('border-color', 'yellow');
								elem.css('border-width', '2px');						
							}
						},
						success: function(){
							if (elem){
								elem.css('border-color', 'green');
								elem.css('border-width', '2px');
							}
						}
					});

				}

				function savePriceModel(){
					saveSettingAjax('config_rainforest_main_formula', $('textarea[name=config_rainforest_main_formula]').val(), $('textarea[name=config_rainforest_main_formula]'));
					saveSettingAjax('config_rainforest_default_store_id', $('select[name=config_rainforest_default_store_id]').val(), $('select[name=config_rainforest_default_store_id]'));
					saveSettingAjax('config_rainforest_volumetric_max_wc_multiplier', $('input[name=config_rainforest_volumetric_max_wc_multiplier]').val(), $('input[name=config_rainforest_volumetric_max_wc_multiplier]'));

					<?php for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){ ?>
						saveSettingAjax('config_rainforest_main_formula_min_<?php echo $crmfc; ?>', $('input[name=config_rainforest_main_formula_min_<?php echo $crmfc; ?>]').val(), $('input[name=config_rainforest_main_formula_min_<?php echo $crmfc; ?>]'));

						saveSettingAjax('config_rainforest_main_formula_max_<?php echo $crmfc; ?>', $('input[name=config_rainforest_main_formula_max_<?php echo $crmfc; ?>]').val(), $('input[name=config_rainforest_main_formula_max_<?php echo $crmfc; ?>]'));

						saveSettingAjax('config_rainforest_main_formula_default_<?php echo $crmfc; ?>', $('input[name=config_rainforest_main_formula_default_<?php echo $crmfc; ?>]').val(), $('input[name=config_rainforest_main_formula_default_<?php echo $crmfc; ?>]'));

						saveSettingAjax('config_rainforest_main_formula_costprice_<?php echo $crmfc; ?>', $('input[name=config_rainforest_main_formula_costprice_<?php echo $crmfc; ?>]').val(), $('input[name=config_rainforest_main_formula_costprice_<?php echo $crmfc; ?>]'));

						saveSettingAjax('config_rainforest_main_formula_overload_<?php echo $crmfc; ?>', $('textarea[name=config_rainforest_main_formula_overload_<?php echo $crmfc; ?>]').val(), $('textarea[name=config_rainforest_main_formula_overload_<?php echo $crmfc; ?>]'));
					<?php } ?>

					<?php foreach ($stores as $store) { ?>
						saveSettingAjax('config_rainforest_kg_price_<?php echo $store['store_id']?>', $('input[name=config_rainforest_kg_price_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_kg_price_<?php echo $store['store_id']?>]'));

						saveSettingAjax('config_rainforest_default_multiplier_<?php echo $store['store_id']?>', $('input[name=config_rainforest_default_multiplier_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_default_multiplier_<?php echo $store['store_id']?>]'));

						saveSettingAjax('config_rainforest_default_costprice_multiplier_<?php echo $store['store_id']?>', $('input[name=config_rainforest_default_costprice_multiplier_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_default_costprice_multiplier_<?php echo $store['store_id']?>]'));

						saveSettingAjax('config_rainforest_max_multiplier_<?php echo $store['store_id']?>', $('input[name=config_rainforest_max_multiplier_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_max_multiplier_<?php echo $store['store_id']?>]'));

						saveSettingAjax('config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>', $('input[name=config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>]').attr('checked')?1:0, $('input[name=config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>]'));

						saveSettingAjax('config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>', $('input[name=config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>]'));
					<?php } ?>
				}


				$('select, textarea, input[type=checkbox], input[type=text], input[type=time], input[type=number]').bind('change', function() {
					var key  = $(this).attr('name');

					<?php foreach (['config_rainforest_main_formula', 'config_rainforest_volumetric_max_wc_multiplier', 'config_rainforest_default_store_id', 'calculator_show_random', 'calculator_limit_products', 'calculator_zones_config', 'calculator_explicit_products', 'calculator_explicit_categories'] as $not_change_input) { ?>
						if (key == '<?php echo $not_change_input; ?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}
					<?php } ?>

					<?php for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){ ?>					
						if (key == 'config_rainforest_main_formula_min_<?php echo $crmfc; ?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_main_formula_max_<?php echo $crmfc; ?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_main_formula_default_<?php echo $crmfc; ?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_main_formula_costprice_<?php echo $crmfc; ?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_main_formula_overload_<?php echo $crmfc; ?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}
					<?php } ?>

					<?php foreach ($stores as $store) { ?>
						if (key == 'config_rainforest_default_multiplier_<?php echo $store['store_id']?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_default_costprice_multiplier_<?php echo $store['store_id']?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_max_multiplier_<?php echo $store['store_id']?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_kg_price_<?php echo $store['store_id']?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}

						if (key == 'config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}
					<?php } ?>

					var elem = $(this);
					var value = $(this).val();

					if (elem.attr('type') == 'checkbox'){
						if (elem.attr('checked')){
							value = 1;
						} else {
							value = 0;
						}
					}

					saveSettingAjax(key, value, elem);				
				});
			</script>

			<script type="text/javascript">
				$('#tabs a').tabs();
			</script>

		</div>
	</div>
</div>
<?php echo $footer; ?>