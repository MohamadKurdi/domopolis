<div id="tab-store">	
	<h2>Настройки режимов работы админки</h2>
	<table class="form">
		<tr>			
			<td style="width:15%">
				<div style="padding:5px; background-color:pink;">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Режим работы с Amazon</span></p>
					<select name="config_enable_amazon_specific_modes">
						<?php if ($config_enable_amazon_specific_modes) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Если включено, то актуальны режимы ASIN, VAR и TRNSL. В настройках устанавливаются режимы по-умолчанию, далее каждый контент-менеджер переключает их для себя по мере необходимости</span>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Включить хранение данных в файлах</span></p>
					<select name="config_enable_amazon_asin_file_cache">
						<?php if ($config_enable_amazon_asin_file_cache) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Большое количество данных (более 10-20к записей в базу) очень сильно тормозит БД, нужно использовать файловый кэш</span>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Хайлоад режим</span></p>
					<select name="config_enable_highload_admin_mode">
						<?php if ($config_enable_highload_admin_mode) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Если включено, то у всех, кроме суперадминистраторов, пропадает возможность сбрасывать кэши</span>
				</div>
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Режим удаления ASIN</span></p>
					<select name="config_rainforest_asin_deletion_mode">
						<?php if ($config_rainforest_asin_deletion_mode) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select> <a class="link_headr cache-button-good"><i class="fa fa-amazon" aria-hidden="true"></i> ASIN</a> <a class="link_headr cache-button-bad"><i class="fa fa-amazon" aria-hidden="true"></i> ASIN</a>
					<br />
					<span class="help">Если включено, при удалении товаров их ASIN запоминается и не будет больше добавлен. Переключается кнопкой в шапке.
						<a href="<?php echo $product_deletedasin; ?>">Посмотреть список удаленных<i class="fa fa-external-link"></i></a></span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Режим коррекции перевода</span></p>
						<select name="config_rainforest_translate_edition_mode">
							<?php if ($config_rainforest_translate_edition_mode) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select> <a class="link_headr cache-button-good"><i class="fa fa-refresh" aria-hidden="true"></i> TRNSL</a> <a class="link_headr cache-button-bad"><i class="fa fa-refresh" aria-hidden="true"></i> TRNSL</a>
						<br />
						<span class="help">Если включено, то при коррекции значений атрибутов одного товара заменяются значения атрибутов у всех товаров, имеющих равные значения на основном языке Amazon</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Режим редактирования вариантов</span></p>
						<select name="config_rainforest_variant_edition_mode">
							<?php if ($config_rainforest_variant_edition_mode) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select><a class="link_headr cache-button-good"><i class="fa fa-amazon" aria-hidden="true"></i> VAR</a> <a class="link_headr cache-button-bad"><i class="fa fa-amazon" aria-hidden="true"></i> VAR</a>
						<br />
						<span class="help">Если включено, то описания вариантов будут отредактированы одновременно. Также при удалении товара будут удалены все его варианты</span>
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Статистика товаров и категорий в админке</span></p>
						<select name="config_amazon_product_stats_enable">
							<?php if ($config_amazon_product_stats_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">показать на главной странице в админке счетчики категорий, товаров и процесса обработки данных, загруженных из amazon</span>
					</div>			
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Загружать Ocfilter в карте товара</span></p>
						<select name="config_load_ocfilter_in_product">
							<?php if ($config_load_ocfilter_in_product) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">в случае большого количества атрибутов и их значений оцфильтр ложит карту товара, нужно отключать</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Монитор cron на главной</span></p>
						<select name="config_cron_stats_display_enable">
							<?php if ($config_cron_stats_display_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">показать на главной странице в админке монитор регулярных задач</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Удалять картинки</span></p>
						<select name="config_delete_products_images_enable">
							<?php if ($config_delete_products_images_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">удалять ли файлы картинок при удалении товаров</span>
					</div>
				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Не удалять товары в заказах</span></p>
						<select name="config_never_delete_products_in_orders">
							<?php if ($config_never_delete_products_in_orders) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">невозможно удалить даже вручную в админке</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Не удалять товары на складе</span></p>
						<select name="config_never_delete_products_in_warehouse">
							<?php if ($config_never_delete_products_in_warehouse) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">невозможно удалить даже вручную в админке</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Фильтровать шаблоны акций</span></p>
						<select name="config_customer_filter_actiontemplates">
							<?php if ($config_customer_filter_actiontemplates) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">включает логику привязки шаблонов рассылок и промокодов к менеджерам</span>
					</div>
				
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Синхронизировать названия в заказах</span></p>
						<select name="config_sync_product_names_in_orders">
							<?php if ($config_sync_product_names_in_orders) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">при редактировании товара также обновятся названия в заказах, и наоборот</span>
					</div>
				</td>
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Рентабельность в заказах</span></p>
						<select name="config_show_profitability_in_order_list">
							<?php if ($config_show_profitability_in_order_list) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">показать процент рентабельности по каждому заказу в списках</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Закупка Amazon и рентабельность в остатках</span></p>
						<select name="config_amazon_profitability_in_stocks">
							<?php if ($config_amazon_profitability_in_stocks) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">если включено, то показывается закупка и рентабельность Amazon, иначе данные из учетной системы</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Список товаров с названиями в заказах</span></p>
						<select name="config_product_lists_text_in_orders">
							<?php if ($config_product_lists_text_in_orders) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">если включено - список, отключено - картинки</span>
					</div>

				</td>
			</tr>
		</table>

		<h2>Отключение неиспользуемых блоков логики</h2>

		<table class="form">
			<tr>
				<td style="width:18%">

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">CSV PP Без брендов</span></p>
						<select name="config_csvprice_pro_disable_manufacturers">
							<?php if ($config_csvprice_pro_disable_manufacturers) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">Если производителей более 10к, CSVPP не работает корректно, нужно включить эту опцию</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика значений атрибутов</span></p>
						<select name="config_enable_attributes_values_logic">
							<?php if ($config_enable_attributes_values_logic) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">Привязка статей и картинок к изображениям атрибутов. логика нагружает админку и магазин! если это не используется, пусть будет отключено</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика цен B2B</span></p>
						<select name="config_group_price_enable">
							<?php if ($config_group_price_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help"><span style="color:red">логика нагружает магазин!</span> если это не используется, пусть будет отключено</span>
					</div>
				</td>
				<td style="width:18%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика товаров-опций</span></p>
						<select name="config_option_products_enable">
							<?php if ($config_option_products_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help"><span style="color:red">логика нагружает магазин!</span> включить только в случае если товары привязаны друг к другу как опции (не касается товаров с Amazon)</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика HTML статусов</span></p>
						<select name="config_additional_html_status_enable">
							<?php if ($config_additional_html_status_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help"><span style="color:red">логика нагружает магазин!</span> включить только в случае настроен модуль Статусы товаров и это используется</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика цен опций</span></p>
						<select name="config_option_price_enable">
							<?php if ($config_option_price_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help"><span style="color:red">логика нагружает магазин!</span> включить только в случае если товары привязаны друг к другу как опции и подсчёт цен в каталоге выполняется "от - до"</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика цветовых групп</span></p>
						<select name="config_color_grouping_products_enable">
							<?php if ($config_color_grouping_products_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help"><span style="color:red">логика нагружает магазин!</span> включить только в случае если товары привязаны друг к другу как опции и разделяются по "цветовым группам"</span>
					</div>
				</td>
				<td style="width:18%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Загрузки в товарах</span></p>
						<select type="select" name="config_product_downloads_enable">
							<? if ($config_product_downloads_enable) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">логика загрузки цифровых товаров</span>	
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Опции товаров</span></p>
						<select type="select" name="config_product_options_enable">
							<? if ($config_product_options_enable) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">штатная логика опций товаров</span>	
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Смотрели вместе</span></p>
						<select type="select" name="config_product_alsoviewed_enable">
							<? if ($config_product_alsoviewed_enable) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help"><span style="color:red">логика нагружает магазин!</span> если не используется - отключить!</span>	
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Описания в списках</span></p>
						<select type="select" name="config_description_in_lists">
							<? if ($config_description_in_lists) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">если не отображаются - отключить</span>	
					</div>					
				</td>

				<td style="width:18%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Recurring profiles</span></p>
						<select type="select" name="config_product_profiles_enable">
							<? if ($config_product_profiles_enable) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">если не используются - отключить</span>	
				</td>
			</tr>
		</table>


		<h2>Настройки режимов работы фронта</h2>
		<table class="form">
			<tr>					
				<td style="width:18%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Single store mode</span></p>
						<select name="config_single_store_enable">
							<?php if ($config_single_store_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">отключает привязки многих сущностей к табличкам *_to_store. включать только в случае 1 база = 1 магазин</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><img src="<?php echo DIR_FLAGS_NAME; ?>de.png" title="de" />Flags in admin</span></p>
						<select name="config_admin_flags_enable">
							<?php if ($config_admin_flags_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">отображать или нет флаги в админке</span>
					</div>							
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Режим разработки</span></p>
						<select type="select" name="config_no_access_enable">
							<? if ($config_no_access_enable) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">Если включено, фронт будет закрыт 403 кодом в случае, если сессия админки не определена</span>	
					</div>			
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF;">Монобрендовый магазин</span></p>
						<select name="config_monobrand" style=" width:150px;">
							<option value="0">Нет</option>
						</select>	
						<br />
						<span class="help">настройка, позволяющая работать без списка брендов (не используется)</span>
					</div>					
				</td>
				<td style="width:18%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">В брендах только товары</span></p>
						<select type="select" name="config_show_goods_overload">
							<? if ($config_show_goods_overload) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">на странице брендов выводятся только товары, без списков коллекций, и.т.д.</span>	
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Обязательная цена</span></p>
						<select type="select" name="config_no_zeroprice">
							<? if ($config_no_zeroprice) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">Если включено, то из отборов фронта исключаются товары без заданной основной цены</span>										
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Режим изоляции локалей</span></p>
						<select type="select" name="config_warmode_enable">
							<? if ($config_warmode_enable) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">будет отключен переключатель стран, и некоторые другие моменты</span>	
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Специальный фильтр логистики</span></p>
						<select type="select" name="config_special_logistics_enable">
							<? if ($config_special_logistics_enable) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
					</div>
				</td>

				<td style="width:20%">
					<div style="padding:10px; border:1px dashed grey;">
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Отключать пустые категории</span></p>
							<select type="select" name="config_disable_empty_categories">
								<? if ($config_disable_empty_categories) { ?>
									<option value="1" selected='selected' >Включить</option>
									<option value="0" >Отключить</option>
								<? } else { ?>
									<option value="1" >Включить</option>
									<option value="0"  selected='selected' >Отключить</option>
								<? } ?>       
							</select>										
						</div>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включать не-пустые категории</span></p>
							<select type="select" name="config_enable_non_empty_categories">
								<? if ($config_enable_non_empty_categories) { ?>
									<option value="1" selected='selected' >Включить</option>
									<option value="0" >Отключить</option>
								<? } else { ?>
									<option value="1" >Включить</option>
									<option value="0"  selected='selected' >Отключить</option>
								<? } ?>       
							</select>										
						</div>
					</div>		

					<div style="padding:10px; border:1px dashed grey; margin-top:10px;">	
						<div>		
							<p>
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Удалять скидки не на складе</span>
							</p>
							<select type="select" name="config_drop_special_prices_not_in_warehouses">
								<? if ($config_drop_special_prices_not_in_warehouses) { ?>
									<option value="1" selected='selected' >Включить</option>
									<option value="0" >Отключить</option>
								<? } else { ?>
									<option value="1" >Включить</option>
									<option value="0"  selected='selected' >Отключить</option>
								<? } ?>       
							</select>
							<br />
							<span class="help">Сбрасывать скидки для товаров не в наличии на локальном складе</span>			
						</div>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Восстанавливать скидку</span></p>
							<select type="select" name="config_restore_special_prices_not_in_warehouses">
								<? if ($config_restore_special_prices_not_in_warehouses) { ?>
									<option value="1" selected='selected' >Включить</option>
									<option value="0" >Отключить</option>
								<? } else { ?>
									<option value="1" >Включить</option>
									<option value="0"  selected='selected' >Отключить</option>
								<? } ?>       
							</select>	
							<br />
							<span class="help">Если товар появился в наличии, восстановить скидку</span>	
						</div>
					</div>
				</td>

				<td style="width:18%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Отключить логику быстрого заказа</span></p>
						<select type="select" name="config_disable_fast_orders">
							<? if ($config_disable_fast_orders) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">Если включено, то невозможно оформить быстрый заказ на сайте</span>											
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить багфикс SimpleCheckout</span></p>
						<select type="select" name="config_enable_form_bugfix_in_simplecheckout">
							<? if ($config_enable_form_bugfix_in_simplecheckout) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">В случае проблем с некоторыми методами оплаты переводить на подтверждение по-умолчанию</span>											
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить "не перезванивать" в SimpleCheckout</span></p>
						<select type="select" name="config_enable_do_not_call_in_simplecheckout">
							<? if ($config_enable_do_not_call_in_simplecheckout) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">Для работы логики также нужно создать поле "do_not_call" с типом checkbox</span>											
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Вкл. "не перезванивать" если все в нал.</span></p>
						<select type="select" name="config_enable_do_not_call_in_simplecheckout_only_full_in_stock">
							<? if ($config_enable_do_not_call_in_simplecheckout_only_full_in_stock) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
						<br />
						<span class="help">Если отключено - то в любом случае</span>											
					</div>
				</td>

			</tr>
		</table>

		<h2><?php echo $text_product; ?></h2>

		<table class="form">
			<tr>																
				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Счетчик количества</span></p>
					<select name="config_product_count">
						<?php if ($config_product_count) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>									
				</td>
				
				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отдельная табличка нефильтруемых атрибутов</span></p>
					<select name="config_use_separate_table_for_features">
						<?php if ($config_use_separate_table_for_features) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>

					<br />
					<span class="help"><i class="fa fa-info-circle"></i> Если включено, то значения нефильтруемых атрибутов (особенности) записываются и считываются из отдельной таблички</span>
				</td>
				
				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Разрешить скачивание файлов</span></p>
					<select name="config_download">
						<?php if ($config_download) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Прятать артикул в карте</span></p>
					<select name="config_product_hide_sku">
						<?php if ($config_product_hide_sku) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>

					<br />
					<span class="help">Отключает вывод артикула в карте товара</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Подменять SKU/MODEL на код товара на выводе</span></p>
					<select name="config_product_replace_sku_with_product_id">
						<?php if ($config_product_replace_sku_with_product_id) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>

					<br />
					<span class="help"><i class="fa fa-info-circle"></i> Глобальная подмена на фронте артикула на внутренний код товара (целое число). Пожалуйста, используйте с большой осторожностью. Это заменит SKU везде, и в микроразметке в том числе. Поиск затронут не будет</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Добавлять префикс к коду товара/SKU при использовании подмены</span></p>
					<input type="text" name="config_product_use_sku_prefix" value="<?php echo $config_product_use_sku_prefix; ?>" size="10" />

					<br />
					<span class="help"><i class="fa fa-info-circle"></i> Если включена предыдущая настройка, и задан этот префикс, то артикул будет равен префикса+код товара. Например, KP123646</span>
				</td>

			</tr>	

			<tr>
				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Группа атрибутов "особенности"</span></p>

					<select name="config_special_attr_id">
						<?php foreach ($attribute_groups as $attribute_group) { ?>
							<?php if ($attribute_group['attribute_group_id'] == $config_special_attr_id) { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<br />
					<span class="help">Эти атрибуты не фильтруются, а только показываются в отдельном блоке в карте товара</span>
				</td>


				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Группа атрибутов "Спецификации"</span></p>
					<select name="config_specifications_attr_id">
						<?php foreach ($attribute_groups as $attribute_group) { ?>
							<?php if ($attribute_group['attribute_group_id'] == $config_specifications_attr_id) { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<br />
					<span class="help">Эти атрибуты не фильтруются, а только показываются в отдельном блоке в карте товара</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Группа атрибутов по-умолчанию</span></p>
					<select name="config_default_attr_id">
						<?php foreach ($attribute_groups as $attribute_group) { ?>
							<?php if ($attribute_group['attribute_group_id'] == $config_default_attr_id) { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<br />
					<span class="help">В эту группу добавляются атрибуты товара с Амазона</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Группа атрибутов "Габариты"</span></p>
					<select name="config_dimensions_attr_id">
						<?php foreach ($attribute_groups as $attribute_group) { ?>
							<?php if ($attribute_group['attribute_group_id'] == $config_dimensions_attr_id) { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<br />
					<span class="help">В эту группу добавляются атрибуты товара с Амазона</span>
				</td>


				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Название атрибутов - особенностей</span></p>
					<input type="text" name="config_special_attr_name" value="<?php echo $config_special_attr_name; ?>" size="30" />										
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Название атрибутов - cпецификаций</span></p>
					<input type="text" name="config_specifications_attr_name" value="<?php echo $config_specifications_attr_name; ?>" size="30" />										
				</td>
			</tr>	
			<tr>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Второй уровень подкатегорий в категориях</span></p>
					<select name="config_second_level_subcategory_in_categories">
						<?php if ($config_second_level_subcategory_in_categories) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Если выключено - то выводится только один уровень и снижается нагрузка.</span>
				</td>

				<td style="width:15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Товары только в крайних категориях</span></p>
						<select name="config_disable_filter_subcategory">
							<?php if ($config_disable_filter_subcategory) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">Если включено - товары отображаются только в крайних по дереву</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Убрать товары только с уровня 0</span></p>
						<select name="config_disable_filter_subcategory_only_for_main">
							<?php if ($config_disable_filter_subcategory_only_for_main) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">Если включена эта настройка и предыдущая, то товары будут показываться везде, кроме корневых категорий</span>
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отображать подкатегории во всех категориях</span></p>
						<select name="config_display_subcategory_in_all_categories">
							<?php if ($config_display_subcategory_in_all_categories) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">Если выключено - то подкатегории выводятся только в корневых</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Лимит подкатегорий второго уровня</span></p>
						<input type="number" name="config_subcategories_limit" value="<?php echo $config_subcategories_limit; ?>" size="50" style="width:100px;">									
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отображать только товары с полной инфой</span></p>
						<select name="config_rainforest_show_only_filled_products_in_catalog">
							<?php if ($config_rainforest_show_only_filled_products_in_catalog) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<br />
						<span class="help">Если включены специфические режимы амазона - будут показаны только заполненные товары</span>
					</div>
					
				</td>

				<td style="width:15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Сортировка по-умолчанию</span></p>
						<select name="config_sort_default">
							<?php foreach ($this->registry->get('sorts_available') as $sort_name => $sort_sort) { ?>
								<?php if ($config_sort_default == $sort_sort) { ?>
									<option value="<?php echo $sort_sort; ?>" selected="selected"><?php echo $sort_name; ?></option>
								<?php } else { ?>
									<option value="<?php echo $sort_sort; ?>"><?php echo $sort_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
						<br />
						<span class="help">Сортировка по-умолчанию в листингах</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Порядок по-умолчанию</span></p>
						<select name="config_order_default">
							<?php if ($config_order_default == 'ASC') { ?>
								<option value="ASC" selected="selected">ASC</option>
								<option value="DESC">DESC</option>
							<?php } else { ?>													
								<option value="ASC">ASC</option>
								<option value="DESC"  selected="selected">DESC</option>
							<? } ?>
						</select>
					</div>									
				</td>
				<td style="width:15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Логика раздела "все скидки"</span></p>
						<select name="config_special_controller_logic">
							<?php if ($config_special_controller_logic == 'default') { ?>
								<option value="default" selected="selected">По-умолчанию</option>
								<option value="category">Товары из категории</option>
							<?php } else { ?>													
								<option value="default">По-умолчанию</option>
								<option value="category"  selected="selected">Товары из категории</option>
							<? } ?>
						</select>
						<br />
						<span class="help">В любом случае скидки - это категория. От выбора логики зависит, будет ли очищаться категория.</span>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">ID категории товаров со скидками</span></p>
						<input type="number" name="config_special_category_id" value="<?php echo $config_special_category_id; ?>" size="50" style="width:90px;" />
					</div>

				</td>
			</tr>						
		</table>

		<h2>Новинки</h2>

		<table class="form">
			<tr>
				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Новинки без new = 1</span></p>
					<select name="config_ignore_manual_marker_productnews">
						<?php if ($config_ignore_manual_marker_productnews) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Если включено, то при отборе новинок игнорируется маркер new = 1, который ставится вручную</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Новинки без Amazon</span></p>
					<select name="config_productnews_exclude_added_from_amazon">
						<?php if ($config_productnews_exclude_added_from_amazon) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Если включено, то при отборе новинок игнорируются добавленные с Amazon товары</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Интервал добавления</span></p>
					<input type="number" name="config_new_days" value="<?php echo $config_new_days; ?>" size="10" style="width:100px" />
					<br />
					<span class="help">Товар считается новинкой, если он добавлен Х дней от сегодня</span>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Интервал добавления длинный</span></p>
					<input type="number" name="config_newlong_days" value="<?php echo $config_newlong_days; ?>" size="10" style="width:100px" />
					<br />
					<span class="help">Товар считается новинкой, если он добавлен Х дней от сегодня. Логика длинного интервала используется в каких-то контроллерах</span>
				</td>
			</tr>
		</table>

		<h2>Отзывы</h2>

		<table class="form">
			<tr>
				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Разрешить отзывы</span></p>
					<select name="config_review_status">
						<?php if ($config_review_status) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Количество "лучших отзывов" в карте товара</span></p>
					<input type="number" name="config_onereview_amount" value="<?php echo $config_onereview_amount; ?>" size="3" />
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Без модерации</span></p>
					<select name="config_review_statusp">
						<?php if ($config_review_statusp) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Уведомление на мейл</span></p>
					<select name="config_review_email">
						<?php if ($config_review_email) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Можно ли редактировать в ЛК</span></p>
					<select name="config_myreviews_edit">
						<?php if ($config_myreviews_edit) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Модерация после редактирования</span></p>
					<select name="config_myreviews_moder">
						<?php if ($config_myreviews_moder) { ?>
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
				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Поле "недостатки"</span></p>
					<select name="config_review_bad">
						<?php if ($config_review_bad) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Поле "достоинства"</span></p>
					<select name="config_review_good">
						<?php if ($config_review_good) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Добавлять вложения</span></p>
					<select name="config_review_addimage">
						<?php if ($config_review_addimage) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить капчу</span></p>
					<select name="config_review_captcha">
						<?php if ($config_review_captcha) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>
			</tr>

		</table>

		<h2>Другие настройки</h2>
		<table class="form">
			<tr>											
				<td style="width:18%">
					<p>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">META TITLE</span>
					</p>
					<textarea name="config_title" cols="40" rows="5"><?php echo $config_title; ?></textarea>
				</td>

				<td style="width:18%">
					<p>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">META DESCRIPTION</span>
					</p>
					<textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_title; ?></textarea>
				</td>

				<td style="width:18%"> 
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Шаблон</span></p>
						<select name="config_template">
							<?php foreach ($templates as $template) { ?>
								<?php if ($template == $config_template) { ?>
									<option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
								<?php } else { ?>
									<option value="<?php echo $template; ?>"><?php echo $template; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_layout; ?><</span></p>
						<select name="config_layout_id">
							<?php foreach ($layouts as $layout) { ?>
								<?php if ($layout['layout_id'] == $config_layout_id) { ?>
									<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>										
					</div>
				</td>

				<td style="width:18%">										
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Добавить меню в homepage</span></p>
						<select type="select" name="config_mmenu_on_homepage">
							<? if ($config_mmenu_on_homepage) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бренды в мегаменю</span></p>
						<select type="select" name="config_brands_in_mmenu">
							<? if ($config_brands_in_mmenu) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бренды на главной</span></p>
						<select type="select" name="config_brands_on_homepage">
							<? if ($config_brands_on_homepage) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Бестселлеры в мегаменю</span></p>
						<select type="select" name="config_bestsellers_in_mmenu">
							<? if ($config_bestsellers_in_mmenu) { ?>
								<option value="1" selected='selected' >Включить</option>
								<option value="0" >Отключить</option>
							<? } else { ?>
								<option value="1" >Включить</option>
								<option value="0"  selected='selected' >Отключить</option>
							<? } ?>       
						</select>
					</div>
				</td>

				<td style="width:18%">
					
				</td>							

				<td style="width:18%">
					
				</td>
			</tr>
		</table>							
	</div>