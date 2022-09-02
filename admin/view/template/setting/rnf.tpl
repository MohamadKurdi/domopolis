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
		</div>
		<div class="content">
			<style>
				#tabs > a {font-weight:700; font-size: 16px; }
				.list tbody td{padding: 10px 10px 10px 5px;}
			</style>

			<div id="tabs" class="htabs">
				<a href="#tab-cron"><span style="color:#7F00FF;"><i class="fa fa-refresh"></i> Cron-задачи</span></a>

				<a href="#tab-cron-results"><span style="color:#0054b3;"><i class="fa fa-refresh"></i> Статистика работы фреймворка</span></a>

				<a href="#tab-products"><span style="color:#00ad07;"><i class="fa fa-refresh"></i> Настройки добавления товаров</span></a>
				<a href="#tab-pricelogic"><span style="color:#D69241;"><i class="fa fa-refresh"></i> Настройки ценообразования</span></a>
				<a href="#tab-api"><span style="color:#cf4a61;"><i class="fa fa-cogs"></i> Режимы магазина</span></a>					

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

				<div id="tab-cron-results" class="delayed-load" data-route='common/home/loadProductStats&tpl=rnf&long=true' data-reload="100000">
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

					</table>
				</div>

				<div id="tab-pricelogic">
				</div>

				<div id="tab-api">
				</div>
			</div>			

			<script type="text/javascript">

				$('select, textarea, input[type=checkbox], input[type=text], input[type=number]').bind('change', function() {
					var key  = $(this).attr('name');
					var elem = $(this);
					var value = $(this).val();

					if (elem.attr('type') == 'checkbox'){
						if (elem.attr('checked')){
							value = 1;
						} else {
							value = 0;
						}
					}

					var store_id = $('input[name=store_id]').val();

					$.ajax({
						type: 'POST',
						url: 'index.php?route=setting/setting/editSettingAjax&store_id=' + store_id + '&token=<?php echo $token; ?>',
						data: {
							key: key,
							value: value						
						},
						beforeSend: function(){
							elem.css('border-color', 'yellow');
							elem.css('border-width', '2px');						
						},
						success: function(){
							elem.css('border-color', 'green');
							elem.css('border-width', '2px');
						}
					});

				});
			</script>

			<script type="text/javascript">
				$('#tabs a').tabs();
			</script>

		</div>
	</div>
</div>
<?php echo $footer; ?>