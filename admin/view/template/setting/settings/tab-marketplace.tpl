<div id="tab-marketplace">
	<?php if ($this->config->get('config_country_id') == 220) { ?>
		<h2>Настройки Hotline</h2>
		<table class="form">
			<tr>		
				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить выгрузку на Hotline</span></p>
					<select name="config_hotline_feed_enable">
						<?php if ($config_hotline_feed_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Идентификатор магазина</span></p>
					<input type="text" name="config_hotline_merchant_id" value="<?php echo $config_hotline_merchant_id; ?>" size="30" style="width:150px;" />
				</td>	

				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Использовать категории Hotline</span></p>
					<select name="config_hotline_enable_category_tree">
						<?php if ($config_hotline_enable_category_tree) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Добавлять в наше дерево категорий также дерево категорий Hotline и пытаться сопоставить категории при формировании фидов</span>
				</td>	
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Лимит товаров на фид</span></p>
						<input type="number" step="1000" name="config_hotline_feed_limit" value="<?php echo $config_hotline_feed_limit; ?>" size="30" style="width:150px;" />	
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Лимит товаров на выборку</span></p>
						<input type="number" step="100" name="config_hotline_one_iteration_limit" value="<?php echo $config_hotline_one_iteration_limit; ?>" size="30" style="width:150px;" />	
					</div>									
				</td>	
				<td width="20%"></td>
			</tr>

		</table>
	<?php } ?>

	<?php if ($this->config->get('config_country_id') == 176) { ?>	
		<h2>Настройки Ozon Seller + исключение брендов</h2>

		<table class="form">
			<tr>		
				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">OZON ценообразование так же как в Yandex</span></p>
					<select name="config_ozon_enable_price_yam">
						<?php if ($config_ozon_enable_price_yam) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">если включено, то в Ozon пойдет та же цена, что и в Яндекс.Маркет. Иначе - цена фронта</span>
				</td>	

				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Название склада в ЛК Ozon Seller</span></p>
					<input type="text" name="config_ozon_warehouse_0" value="<?php echo $config_ozon_warehouse_0; ?>" size="30" style="width:200px;" />
					<br />
					<span class="help">Обязательное требование, нужно скопировать из личного кабинета Озона</span>
				</td>

				<td width="30%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Исключить бренды из фида Ozon</span></p>
					<div class="scrollbox" style="height:250px;">
						<?php $class = 'odd'; ?>
						<?php if ($config_ozon_warehouse_0) { ?>
							<?php foreach ($manufacturers as $manufacturer) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
									<?php if (in_array($manufacturer['manufacturer_id'], $config_ozon_exclude_manufacturers)) { ?>
										<input id="config_ozon_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>" class="checkbox" type="checkbox" name="config_ozon_exclude_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
										<label for="config_yandex_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
									<?php } else { ?>
										<input id="config_ozon_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>" class="checkbox" type="checkbox" name="config_ozon_exclude_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
										<label for="config_ozon_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</td>

				<td width="30%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Исключить бренды из фида Yandex Market</span></p>
					<div class="scrollbox" style="height:250px;">
						<?php $class = 'odd'; ?>
						<?php if ($config_yam_fbs_campaign_id) { ?>
							<?php foreach ($manufacturers as $manufacturer) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
									<?php if (in_array($manufacturer['manufacturer_id'], $config_yandex_exclude_manufacturers)) { ?>
										<input id="config_yandex_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>" class="checkbox" type="checkbox" name="config_yandex_exclude_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
										<label for="config_yandex_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
									<?php } else { ?>
										<input id="config_yandex_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>" class="checkbox" type="checkbox" name="config_yandex_exclude_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
										<label for="config_yandex_exclude_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } ?>
					</div>

				</td>

			</tr>
		</table>

		<h2>Yandex Market Ценообразование</h2>
		<table class="form">
			<tr>		
				<td width="50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market использовать логику "своей цены"</span></p>
					<select name="config_yam_offer_id_price_enable">
						<?php if ($config_yam_offer_id_price_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">товары в фиде маркета будут подаваться с переназначенными ценами</span>
				</td>	

				<td width="50%">
					<a href="<?php echo $deprecated_yam_module; ?>">[Deprecated] Модуль Яндекс-маркета</a><br />
					<a href="<?php echo $deprecated_hotline_module; ?>">[Deprecated] Модуль Hotline</a><br />
				</td>	
				
			</tr>
			
			<tr>		
				<td width="50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market включить автоматическое назначение наценки в фиде</span></p>
					<select name="config_yam_enable_plus_percent">
						<?php if ($config_yam_enable_plus_percent) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">если включено, то ручные цены будут задаваться при формировании фида автоматически</span>
				</td>
				
				<td width="50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market процент наценки или скидки</span></p>
					<input type="text" name="config_yam_plus_percent" value="<?php echo $config_yam_plus_percent; ?>" size="30" style="width:150px;" />%
					<br />
					<span class="help">20 для наценки, -20 для скидки. высчитывается исходя из финальной цены товара (если нет РРЦ, но есть скидка - то из нее, если нет - то из регулярной цены</span>
				</td>
				
			</tr>
			
			<tr>
				
				<td width="50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market ебануть скидку на все товары маркета</span></p>
					<select name="config_yam_enable_plus_for_main_price">
						<?php if ($config_yam_plus_for_main_price) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">если включено, то на все товары маркета в фиде будет ебнута скидка от основной цены Маркета (цена фронта 1000, цена маркета (+20%) = 1200, скидка будет отправлена 1200 - 10% = 1080. Будет использовано две цены, PRICE, OLDPRICE. В случае если не включено, используется исключительно одна цена, price</span>
				</td>
				
				<td width="50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market процент ебнутой наценки или скидки</span></p>
					<input type="text" name="config_yam_plus_for_main_price" value="<?php echo $config_yam_plus_for_main_price; ?>" size="30" style="width:150px;" />%
					<br />
					<span class="help">20 для наценки, -20 для скидки. высчитывается исходя из финальной цены товара (если нет РРЦ, но есть скидка - то из нее, если нет - то из регулярной цены</span>
				</td>
				
			</tr>
			
			<tr>								
				<td width="50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market нахуй скидки каталога</span></p>
					<select name="config_yam_fuck_specials">
						<?php if ($config_yam_fuck_specials) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Если включено, то скидки для ЯМ не учитывают скидки магазина и всегда считаются от основной цены</span>
				</td>


				<td width="50%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market процент комиссии </span></p>
					<input type="text" name="config_yam_default_comission" value="<?php echo $config_yam_default_comission; ?>" size="30" style="width:150px;" />%
					<br />
					<span class="help">Средняя по больнице комиссия ЯМ для подсчетов</span>
				</td>
				
			</tr>								
		</table>
		
		<h2>Yandex Market НАСТРОЙКИ</h2>
		<table class="form">
			<tr>		
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market Идентификатор FBS кампании</span></p>
					<input type="text" name="config_yam_fbs_campaign_id" value="<?php echo $config_yam_fbs_campaign_id; ?>" size="30" style="width:150px;" />
				</td>
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market Идентификатор основного склада</span></p>
					<input type="text" name="config_yam_fbs_warehouse_id" value="<?php echo $config_yam_fbs_warehouse_id; ?>" size="30" style="width:150px;" />
				</td>
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market поле для синхронизации количества</span></p>
					<input type="text" name="config_yam_stock_field" value="<?php echo $config_yam_stock_field; ?>" size="30" style="width:150px;" />
				</td>
				
			</tr>
			
			<tr>		
				
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market yaMarketToken</span></p>
					<input type="text" name="config_yam_yaMarketToken" value="<?php echo $config_yam_yaMarketToken; ?>" size="30" style="width:250px;" />
					
					<br />
					<span class="help">Нужно получать токены каждый раз при сбое авторизации, потому как их сбрасывает Яндекс</span>	
				</td>
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market yandexOauthID</span></p>
					<input type="text" name="config_yam_yandexOauthID" value="<?php echo $config_yam_yandexOauthID; ?>" size="30" style="width:250px;" />
					
					<br />
					<span class="help">Нужно получать токены каждый раз при сбое авторизации, потому как их сбрасывает Яндекс</span>	
				</td>
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market yandexOauthSecret</span></p>
					<input type="text" name="config_yam_yandexOauthSecret" value="<?php echo $config_yam_yandexOauthSecret; ?>" size="30" style="width:250px;" />
					
					<br />
					<span class="help">Нужно получать токены каждый раз при сбое авторизации, потому как их сбрасывает Яндекс</span>	
				</td>
			</tr>
			
			<tr>		
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market yandexAccessToken</span></p>
					<input type="text" name="config_yam_yandexAccessToken" value="<?php echo $config_yam_yandexAccessToken; ?>" size="30" style="width:250px;" />
					
					<br />
					<span class="help">Нужно получать токены каждый раз при сбое авторизации, потому как их сбрасывает Яндекс</span>	
				</td>
				
			</tr>
			
			<tr>		
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market шаблон названия фида</span></p>
					<input type="text" name="config_yam_offer_feed_template" value="<?php echo $config_yam_offer_feed_template; ?>" size="40" style="width:300px;" />
					<br />
					<span class="help">используй шорткод {store_id} для подстановки идентификатора магазина, {yam_prefix} для подстановки префикса</span>
				</td>
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market включить свои идентификаторы</span></p>
					<select name="config_yam_offer_id_prefix_enable">
						<?php if ($config_yam_offer_id_prefix_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">товары в фиде маркета будут подаваться с идентификаторами вида <?php echo $config_yam_offer_id_prefix; ?>КОД_ТОВАРА</span>
				</td>
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Yandex Market префикс</span></p>
					<input type="text" name="config_yam_offer_id_prefix" value="<?php echo $config_yam_offer_id_prefix; ?>" size="30" style="width:250px;" />
					
					<br />
					<span class="help">товары в фиде маркета будут подаваться с идентификаторами вида <?php echo $config_yam_offer_id_prefix; ?>КОД_ТОВАРА</span>	
				</td>
			</tr>
			
			<tr>		
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Market использовать категории Яндекса</span></p>
					<select name="config_yam_enable_category_tree">
						<?php if ($config_yam_enable_category_tree) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">Добавлять в наше дерево категорий также дерево категорий Яндекса и пытаться сопоставить категории при формировании фидов</span>
				</td>
				
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Не включать ссылку на товар в маркете</span></p>
					<select name="config_yam_offer_id_link_disable">
						<?php if ($config_yam_offer_id_link_disable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help">товары в фиде маркета будут подаваться без ссылки, для отвязки товаров от магазина</span>
				</td>
				
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Исключать товары с похожими названиями</span></p>
					<textarea cols="20" rows="5" name="config_yam_excludewords"><?php echo $config_yam_excludewords; ?></textarea>
					<br />
					<span class="help">каждое с новой строки, например, "пепельниц зажигалк" для исключения товаров связанных с курением</span>

					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Дефолтная категория Yandex</span></p>
					<input type="text" name="config_yam_default_category_id" value="<?php echo $config_yam_default_category_id; ?>" size="30" style="width:250px;" />
					<br />
					<span class="help">в случае если не получилось никак определить</span>
				</td>
			</tr>								
		</table>

	<?php } ?>
</div>