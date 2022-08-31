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
		</div>
		<div class="content">
			<style>
				#tabs > a {font-weight:700; font-size: 16px; }
				.list tbody td{padding: 10px 10px 10px 5px;}
			</style>

			<div id="tabs" class="htabs">
				<a href="#tab-cron"><span style="color:#7F00FF;"><i class="fa fa-refresh"></i> Cron-задачи</span></a>
				<a href="#tab-intervals"><span style="color:#7F00FF;"><i class="fa fa-clock-o"></i> Настройки интервалов</span></a>
				<a href="#tab-products"><span style="color:#00ad07;"><i class="fa fa-refresh"></i> Настройки логики товаров</span></a>
				<a href="#tab-pricelogic"><span style="color:#D69241;"><i class="fa fa-refresh"></i> Настройки ценообразования</span></a>

				<a href="#tab-api"><span style="color:#cf4a61;"><i class="fa fa-cogs"></i> Настройки API</span></a>
			</div>

			<div class="clr"></div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<input type="hidden" name="store_id" value="0"/>

				<div id="tab-cron">
					<div style="width:99%; float:left;">
						<table class="list">
							<tr>
								<td style="white-space: nowrap;">
									<i class="fa fa-refresh"></i> <b>Парсер новых товаров Amazon</b>
								</td>
								<td style="width:40px;">
									<input id="config_rainforest_enable_new_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_new_parser" <? if ($config_rainforest_enable_new_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_new_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Этот воркер просматривает категории в поиске новых товаров, либо обычные товары в листинге, либо бестселлеры и добавляет их. Добавляется только название, картинка и цена. Воркер работает только с категориями, которые включены и у которых включен маркер <i>Разрешить загрузку информации о новых товарах</i>.
									</span>
								</td>
							</tr>
							<tr>
								<td style="white-space: nowrap;">
									<i class="fa fa-refresh"></i> <b>Парсер данных о товарах Amazon</b>
								</td>
								<td>
									<input id="config_rainforest_enable_data_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_data_parser" <? if ($config_rainforest_enable_data_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_data_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Изначально товары загружаются с страницы категории без данных, только название картинка и цена. Этот воркер получает полные данные о товарах, включая описание, атрибуты, блоки связей товаров, добавляет варианты, и.т.д. Воркер работает только с категориями, которые включены, у которых включен маркер <i>Разрешить загрузку полной информации о товарах</i> и только с товарами, у которых включен маркер <i>Разрешить загрузку данных</i>
									</span>
								</td>
							</tr>
							<tr>
								<td style="white-space: nowrap;">
									<i class="fa fa-refresh"></i> <b>Разгребатель технической категории</b>
								</td>
								<td>
									<input id="config_rainforest_enable_tech_category_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_tech_category_parser" <? if ($config_rainforest_enable_tech_category_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_tech_category_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> В процессе добавления и обработки товаров появляются сопутствующие товары с изначально неизвестной категорией. Они попадают в "техническую категорию". Этот воркер получает данные о таких товарах и переносит их в нужные категории
									</span>
								</td>
							</tr>
							<tr>
								<td style="white-space: nowrap;">
									<i class="fa fa-refresh"></i> <b>Парсер данных о товарах Amazon L2</b>
								</td>
								<td>
									<input id="config_rainforest_enable_data_l2_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_data_l2_parser" <? if ($config_rainforest_enable_data_l2_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_data_l2_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> После переноса товаров из технической категории воркером <i>разгребатель технической категории</i> у нас имеются структурированные данные о товарах, которые нет смысла получать в общем потоке. Этот воркер обрабатывает уже загруженные данные о товарах и добавляет описание, атрибуты, блоки связей товаров, и прочий контент
									</span>
								</td>
							</tr>
							<tr>
								<td style="white-space: nowrap;">
									<i class="fa fa-refresh"></i> <b>Получение офферов с Amazon</b>
								</td>
								<td>
									<input id="config_rainforest_enable_offers_parser" type="checkbox" class="checkbox" name="config_rainforest_enable_offers_parser" <? if ($config_rainforest_enable_offers_parser){ ?> checked="checked" <? } ?> value="1" /><label for="config_rainforest_enable_offers_parser"></label>
								</td>
								<td>
									<span class="help">
										<i class="fa fa-info-circle"></i> Этот воркер получает и обновляет предложения и цены с Amazon. Также, в зависимости от настроек, он меняет статусы, и может удалять товары.
									</span>
								</td>
							</tr>
						</table>
					</div>					
				</div>

				<div id="tab-intervals">
				</div>

				<div id="tab-products">
				</div>

				<div id="tab-pricelogic">
				</div>

				<div id="tab-api">
				</div>
			</form>

			<script type="text/javascript">

				$('select, textarea, input[type=checkbox], input[type=text], input[type=number]').bind('change', function() {
					var key  = $(this).attr('name');
					var elem = $(this);

					if (elem.attr('type') == 'checkbox'){
						if (elem.attr('checked')){
							var value = 1;
						} else {
							var value = 0;
						}
					} else {
						var value = elem.value;
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