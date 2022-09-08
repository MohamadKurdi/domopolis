<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>	
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
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
				<a href="#tab-cron"><span style="color:#7F00FF;"><i class="fa fa-refresh"></i> Cron-задачи</span></a>
				<a href="#tab-cron-results"><span style="color:#0054b3;"><i class="fa fa-refresh"></i> Статистика работы фреймворка</span></a>
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
									<i class="fa fa-refresh"></i> <b>Парсер новых товаров Amazon</b>
								</td>
								<td style="width:40px;" class="center">
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
								<td style="white-space: nowrap;color:#7F00FF;">
									<i class="fa fa-refresh"></i> <b>Офферы для товаров в заказах</b>
								</td>
								<td style="width:40px;" class="center">
									<input id="config_rainforest_enable_offersqueue_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_offersqueue_parser" <? if ($config_rainforest_enable_offersqueue_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offersqueue_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Чтоб иметь актуальную цену закупки, этот воркер получает цены и наличие для только что заказанных товаров.
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
						</table>
					</div>					
				</div>

				<div id="tab-cron-results" class="delayed-load" data-route='common/home/loadProductStats&tpl=rnf&long=true' data-reload="30000">
				</div>

				<div id="tab-products">
					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#00ad07;">
								<i class="fa fa-cogs"></i> <b>Варианты</b>
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
								Обработка <br /><i>"Сравните с похожими"</i>
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
								Добавление <br /><i>"Сравните с похожими"</i>
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
								Обработка <br /><i>"Сопутствующие товары"</i>
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
								Добавление <br /><i>"Сопутствующие товары"</i>
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
								Обработка <br /><i>"Товары Спонсоров"</i>
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
								Добавление <br /><i>"Товары Спонсоров"</i>
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
								Обработка <br /><i>"Предложения похожих"</i>
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
								Добавление <br /><i>"Предложения похожих"</i>
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
								Обработка <br /><i>"Смотрели до покупки"</i>
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
								Добавление <br /><i>"Смотрели до покупки"</i>
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
								Обработка <br /><i>"Также смотрели"</i>
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
								Добавление <br /><i>"Также смотрели"</i>
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
								Обработка <br /><i>"Также купили"</i>
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
								Добавление <br /><i>"Также купили"</i>
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
								Обработка <br /><i>"Шоппинг по виду"</i>
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
								Добавление <br /><i>"Шоппинг по виду"</i>
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
								<i class="fa fa-cogs"></i> <b>Переводчик</b>
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
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-cogs"></i> <b>Изменение статуса и наличия</b>
							</td>
						</tr>
						<tr>
							<td class="right">
								Изменять статус наличия
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
								Изменять количество
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
								Статус, если нет офферов
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
								Удалять, если нет офферов
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
								Количество итераций для удаления
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

					</table>
				</div>

				<div id="tab-priceformula">					
					<div style="float:left; width:60%;">
						<div>
							<input type="text" name="config_rainforest_main_formula" value="<?php echo $config_rainforest_main_formula; ?>" style="width:80%; font-size:24px; padding:10px;" />						
							<button class="button" style="padding:10px; float:right; font-size:24px; margin-right:4px;" onclick="savePriceModel();"><i class="fa fa-check"></i> Сохранить</button>

							<div class="clr"></div>
							<span class="help"><i class="fa fa-info-circle"></i> В этом разделе изменение полей на лету отключено, чтоб изменения формулы и коэффициентов не влияли на текущую модель. Если хочешь изменить модель ценообразования после тестирования формул и (или) коэффициентов - нужно нажать кнопку сохранить и дождаться окончания процесса. После нажатия кнопки ценовая модель изменится и цены товара будут формироваться исходя из новой модели. Любое изменение поля вызывает запрос на тестовый пересчёт цен.</span>
						</div>

						<div id="calculator_results" style="min-height:500px; margin-top:10px;">
						</div>
					</div>

					<div style="float:right; width:40%;">
						<table class="list">
							<tr>
								<td colspan="2" class="left" style="color:#D69241;">
									<i class="fa fa-calculator"></i> <b>Операнды</b>
								</td>
							</tr>

							<tr>
								<td><b>PRICE</b></td><td>цена товара у поставщика</td>
							</tr>

							<tr>
								<td><b>WEIGHT</b></td><td>подсчитанный вес товара</td>
							</tr>

							<tr>
								<td><b>KG_LOGISTIC</b></td><td>стоимость логистики одного килограмма</td>
							</tr>

							<tr>
								<td><b>PLUS</b></td><td>операция добавления (знак +)</td>
							</tr>

							<tr>
								<td><b>MULTIPLY</b></td><td>операция умножения (знак *)</td>
							</tr>

							<tr>
								<td><b>DIVIDE</b></td><td>операция деления (знак /)</td>
							</tr>	
						</table>
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
								<input type="text" name="calculator_zones_config" value="0 20 50 100 1000 10000" style="width:90%;" />								
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
								Еще раз
							</td>
							<td>
								<span class="button" style="cursor:pointer;" onclick="recalculate(); return false;"><i class="fa fa-refresh"></i> пересчитать</span>
							</td>
						</tr>
					</table>

					<table class="list">
						<tr>
							<td colspan="3" class="left" style="color:#D69241;">
								<i class="fa fa-calculator"></i> <b>Коэффициенты</b>
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
						<?php foreach ($stores as $store) { ?>
							<tr>
								<td class="right">
									Стоимость логистики 1 кг в Евро, <?php echo $store['name']; ?>
								</td>
								<td style="width:100px;" class="center">
									<input type="number" step="0.1" name="config_rainforest_kg_price_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_kg_price_' . $store['store_id']}; ?>" style="width:100px;" />
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Заданная стоимость логистики 1 кг груза в страну-назначения. В формуле - переменная <b>KG_LOGISTIC</b>
									</span>
								</td>
							</tr>
						<?php } ?>

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
									<i class="fa fa-info-circle"></i> По-умолчанию на фронте в категориях выполняется отбор только одного уровня подкатегорий. Если включить, то будет отбираться также второй уровень дочерних подкатегорий по дереву от текущей.
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
					var mainFormula 				= $('input[name=config_rainforest_main_formula]').val();
					var weightCoefficient 			= $('input[name=config_rainforest_kg_price_0]').val();
					var defaultMultiplier 			= $('input[name=config_rainforest_default_multiplier_0]').val();
					var useVolumetricWeight 		= $('input[name=config_rainforest_use_volumetric_weight_0]').attr('checked')?1:0;
					var volumetricWeightCoefficient = $('input[name=config_rainforest_volumetric_weight_coefficient_0]').val();
					var showRandomProducts 			= $('input[name=calculator_show_random]').attr('checked')?1:0;
					var limitProducts				= $('input[name=calculator_limit_products]').val();
					var zonesConfig					= $('input[name=calculator_zones_config]').val();
					var explicitProducts			= $('input[name=calculator_explicit_products]').val();

					$.ajax({
						type: 'POST',
						dataType: 'html',
						url: 'index.php?route=setting/rnf/calculate&hello=world&token=<?php echo $token; ?>',
						data: {
							main_formula: 					mainFormula,
							weight_coefficient: 			weightCoefficient,					
							default_multiplier: 			defaultMultiplier,
							use_volumetric_weight: 			useVolumetricWeight,
							volumetric_weight_coefficient: 	volumetricWeightCoefficient,
							show_random_products:  			showRandomProducts,
							limit_products:  				limitProducts,
							zones_config:  					zonesConfig,
							explicit_products: 				explicitProducts
						},
						beforeSend: function(){
							$('#calculator_results').html('<i class="fa fa-calculator" style="font-size:128px"></i>');
						},
						success: function(html){
							$('#calculator_results').html(html);
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
					saveSettingAjax('config_rainforest_main_formula', $('input[name=config_rainforest_main_formula]').val(), $('input[name=config_rainforest_main_formula]'));
					saveSettingAjax('config_rainforest_default_store_id', $('select[name=config_rainforest_default_store_id]').val(), $('select[name=config_rainforest_default_store_id]'));

					<?php foreach ($stores as $store) { ?>
						saveSettingAjax('config_rainforest_kg_price_<?php echo $store['store_id']?>', $('input[name=config_rainforest_kg_price_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_kg_price_<?php echo $store['store_id']?>]'));
						saveSettingAjax('config_rainforest_default_multiplier_<?php echo $store['store_id']?>', $('input[name=config_rainforest_default_multiplier_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_default_multiplier_<?php echo $store['store_id']?>]'));
						saveSettingAjax('config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>', $('input[name=config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>]').attr('checked')?1:0, $('input[name=config_rainforest_use_volumetric_weight_<?php echo $store['store_id']?>]'));
						saveSettingAjax('config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>', $('input[name=config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>]').val(), $('input[name=config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>]'));
					<?php } ?>
				}


				$('select, textarea, input[type=checkbox], input[type=text], input[type=number]').bind('change', function() {
					var key  = $(this).attr('name');

					<?php foreach (['config_rainforest_main_formula', 'config_rainforest_default_store_id', 'calculator_show_random', 'calculator_limit_products', 'calculator_zones_config', 'calculator_explicit_products'] as $not_change_input) { ?>
						if (key == '<?php echo $not_change_input; ?>'){
							console.log('Pricelogic skip autosave: ' + key);
							return;
						}
					<?php } ?>

					<?php foreach ($stores as $store) { ?>

						if (key == 'config_rainforest_default_multiplier_<?php echo $store['store_id']?>'){
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