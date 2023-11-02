		<div id="tab-apis">
			<h2>HelpCrunch чат</h2>
			<table class="form">
				<tr>
					<td width="25%">	
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Включить HelpCrunch</span></p>
						<select name="config_helpcrunch_enable">
							<?php if ($config_helpcrunch_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</td>
					
					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">APP ID</span></p>
						<input type="text" name="config_helpcrunch_app_id" value="<?php echo $config_helpcrunch_app_id; ?>" size="50" style="width:300px;" />
					</td>
					
					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">API organisation</span></p>
						<input type="text" name="config_helpcrunch_app_organisation" value="<?php echo $config_helpcrunch_app_organisation; ?>" size="50" style="width:200px;" />
					</td>

					<td width="25%">	
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Передавать пользователя</span></p>
						<select name="config_helpcrunch_send_auth_data">
							<?php if ($config_helpcrunch_send_auth_data) { ?>
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

			<h2> Translate API</h2>
			<table class="form">
				<tr>
					<td width="33%">	
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Библиотека Translate</span></p>
							<select name="config_translation_library">
								<?php foreach ($translategates as $translategate) { ?>
									<?php if ($translategate == $config_translation_library) { ?>
										<option value="<?php echo $translategate; ?>" selected="selected"><?php echo $translategate; ?></option>
									<?php } else { ?>
										<option value="<?php echo $translategate; ?>"><?php echo $translategate; ?></option>
									<?php } ?>
								<?php } ?>				
							</select>
						</div>
					</td>	

					<td width="33%">	
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Включить Translate API</span></p>
						<select name="config_translate_api_enable">
							<?php if ($config_translate_api_enable) { ?>
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

			<h2> Deepl Translate API</h2>
			<table class="form">
				<tr>
					<td width="33%">	
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Включить Deepl Translate API</span></p>
						<select name="config_deepl_translate_api_enable">
							<?php if ($config_deepl_translate_api_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</td>
					
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API KEY</span></p>
						<input type="text" name="config_deepl_translate_api_key" value="<?php echo $config_deepl_translate_api_key; ?>" size="50" style="width:250px;" />
					</td>
					
					<td width="33%">										
					</td>										
				</tr>
			</table>

			<h2> Microsoft Azure Cloud API</h2>
			<table class="form">
				<tr>
					<td width="33%">	
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Включить Microsoft Azure Cloud API</span></p>
						<select name="config_azure_translate_api_enable">
							<?php if ($config_azure_translate_api_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</td>

					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API REGION</span></p>
						<input type="text" name="config_azure_translate_api_region" value="<?php echo $config_azure_translate_api_region; ?>" size="50" style="width:250px;" />
					</td>	
					
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API KEY</span></p>
						<input type="text" name="config_azure_translate_api_key" value="<?php echo $config_azure_translate_api_key; ?>" size="50" style="width:250px;" />
					</td>									
				</tr>
			</table>

			<h2>Yandex Translate (Cloud) API</h2>
			<table class="form">
				<tr>
					<td width="33%">	
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Включить Yandex Translate</span></p>
						<select name="config_yandex_translate_api_enable">
							<?php if ($config_yandex_translate_api_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</td>
					
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API KEY</span></p>
						<input type="text" name="config_yandex_translate_api_key" value="<?php echo $config_yandex_translate_api_key; ?>" size="50" style="width:250px;" />
					</td>
					
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API ID</span></p>
						<input type="text" name="config_yandex_translate_api_id" value="<?php echo $config_yandex_translate_api_id; ?>" size="50" style="width:250px;" />
					</td>

					
				</tr>
			</table>

			<h2>Подключение к 1С (SOAP API)</h2>
			<table class="form">
				<tr>
					
					<td width="40%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">SOAP URI</span></p>
						<input type="text" name="config_odinass_soap_uri" value="<?php echo $config_odinass_soap_uri; ?>" size="50" style="width:250px;" />
					</td>
					
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">SOAP USER</span></p>
						<input type="text" name="config_odinass_soap_user" value="<?php echo $config_odinass_soap_user; ?>" size="50" style="width:250px;" />
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">SOAP PASSWD</span></p>
						<input type="text" name="config_odinass_soap_passwd" value="<?php echo $config_odinass_soap_passwd; ?>" size="50" style="width:250px;" />
					</td>

					<td width="10%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">Загружать РРЦ</span></p>
						<select name="config_odinass_update_local_prices">
							<?php if ($config_odinass_update_local_prices) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</td>

					<td width="10%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">Остатки в пути</span></p>
						<select name="config_odinass_update_stockwaits">
							<?php if ($config_odinass_update_stockwaits) { ?>
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

			<h2>Автообновление курсов валют</h2>
			<table class="form">
				<tr>
					<td style="width:20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">Fixer.io token</span></p>
						<input type="text" name="config_fixer_io_token" value="<?php echo $config_fixer_io_token; ?>" size="50" style="width:250px;" />
					</td>

					<td style="width:20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Автоматическое обновление валют</span></p>
						<select name="config_currency_auto">
							<?php if ($config_currency_auto) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</td>

					<td style="width:20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Порог обновления</span></p>
						<input type="number" step="1" name="config_currency_auto_threshold" value="<?php echo $config_currency_auto_threshold; ?>" size="2" style="width:100px;" />%
					</td>
				</tr>
			</table>

			<h2>Bitrix24 BOT API (Чудо-бот)</h2>
			<table class="form">
				<tr>
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Включить Bitrix24 BOT API</span></p>
						<select name="config_bitrix_bot_enable">
							<?php if ($config_bitrix_bot_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</td>
					
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Bitrix24 Домен</span></p>
						<input type="text" name="config_bitrix_bot_domain" value="<?php echo $config_bitrix_bot_domain; ?>" size="50" style="width:250px;" />
					</td>
					
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Bitrix24 Scope</span></p>
						<input type="text" name="config_bitrix_bot_scope" value="<?php echo $config_bitrix_bot_scope; ?>" size="50" style="width:250px;" />
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Bitrix24 Client ID</span></p>
						<input type="text" name="config_bitrix_bot_client_id" value="<?php echo $config_bitrix_bot_client_id; ?>" size="50" style="width:250px;" />
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Bitrix24 Secret</span></p>
						<input type="text" name="config_bitrix_bot_client_secret" value="<?php echo $config_bitrix_bot_client_secret; ?>" size="50" style="width:250px;" />
					</td>
				</tr>
			</table>



			<h2>Telegram BOT API (уведомления)</h2>

			<table class="form">
				<tr>
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Включить TG Bot API</span></p>
						<select name="config_telegram_bot_enable_alerts">
							<?php if ($config_telegram_bot_enable_alerts) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</td>
					
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Ключ TG Bot API</span></p>
						<input type="text" name="config_telegram_bot_token" value="<?php echo $config_telegram_bot_token; ?>" size="50" style="width:250px;" />
					</td>
					
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Имя бота</span></p>
						<input type="text" name="config_telegram_bot_name" value="<?php echo $config_telegram_bot_name; ?>" size="50" style="width:250px;" />
					</td>
				</tr>
			</table>
			
			
			<h2><i class="fa fa-search"></i> Priceva / PriceControl API (подбор и мониторинг цен конкурентов)</h2>
			
			<table class="form">
				<tr>
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Включить Priceva</span></p>
						<select name="config_priceva_enable_api">
							<?php if ($config_priceva_enable_api) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Включить PriceControl</span></p>
						<select name="config_pricecontrol_enable_api">
							<?php if ($config_pricecontrol_enable_api) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Директория с фидами</span></p>
						<input type="text" name="config_priceva_directory_name" value="<?php echo $config_priceva_directory_name; ?>" size="40" style="width:300px;" />	
					</td>

					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Поле с ссылками конкурентов</span></p>
						<input type="text" name="config_priceva_competitor_field_mapping" value="<?php echo $config_priceva_competitor_field_mapping; ?>" size="40" style="width:300px;" />	
					</td>
				</tr>
				
				<tr>
					<?php foreach ($stores as $store) { ?>
						<td width="<?php echo (int)(100/count($stores))?>%">
							
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF"><i class="fa fa-search"></i> API ключ: <?php echo $store['name']; ?></span></p>
							<input type="text" step="0.1" name="config_priceva_api_key_<?php echo $store['store_id']?>" value="<?php echo ${'config_priceva_api_key_' . $store['store_id']}; ?>" style="width:200px;" />
							
							<br />
							<span class="help"><i class="fa fa-search"></i> кампании будут загружаться только в случае, если ключ будет задан</span>
						</td>
					<?php } ?>
				</tr>
			</table>

			<h2><i class="fa fa-search"></i> Сервис Zadarma (телефония)</h2>

			<table class="form">
				<tr>
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ключ API</span></p>
						<input type="text" name="config_zadarma_api_key" value="<?php echo $config_zadarma_api_key; ?>" size="40" style="width:300px;" />	
					</td>
					<td width="33%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Секрет API</span></p>
						<input type="text" name="config_zadarma_secret_key" value="<?php echo $config_zadarma_secret_key; ?>" size="40" style="width:300px;" />	
					</td>
					<td width="33%">
					</td>
				</tr>
			</table>							

			<h2><i class="fa fa-search"></i> Сервис DADATA (получение списка адресов)</h2>

			<table class="form">
				<tr>
					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Режим работы API</span></p>
						<select name="config_dadata">
							<option value="0" <?php if ($config_dadata == 0) { ?>selected="selected"<?php } ?>>Отключить вообще</option>
							<option value="city" <?php if ($config_dadata == 'city') { ?>selected="selected"<?php } ?>>Подбор города</option>
							<option value="address" <?php if ($config_dadata == 'address') { ?>selected="selected"<?php } ?>>Подбор адреса</option>
						</select>	
					</td>

					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ключ API</span></p>
						<input type="text" name="config_dadata_api_key" value="<?php echo $config_dadata_api_key; ?>" size="40" style="width:300px;" />	
					</td>
					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Секрет API</span></p>
						<input type="text" name="config_dadata_secret_key" value="<?php echo $config_dadata_secret_key; ?>" size="40" style="width:300px;" />	
					</td>

					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ключ IP - API</span></p>
						<input type="text" name="config_ip_api_key" value="<?php echo $config_ip_api_key; ?>" size="40" style="width:300px;" />	
					</td>
				</tr>
			</table>
			
			<h2><i class="fa fa-search"></i> ElasticSearch API (умный поиск на нашем сервере)</h2>
			<table class="form">
				<tr>
					<td width="20%">									
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для подбора товаров</span></p>
						<input type="number" step="0.1" name="config_elasticsearch_fuzziness_product" value="<?php echo $config_elasticsearch_fuzziness_product; ?>" size="3" style="width:100px;" />
						
						<br />
						<span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>
					</td>
					
					<td width="20%">									
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для подбора категорий</span></p>
						<input type="number" step="0.1" name="config_elasticsearch_fuzziness_category" value="<?php echo $config_elasticsearch_fuzziness_category; ?>" size="3" style="width:100px;" />
						
						<br />
						<span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>
					</td>
					
					<td width="20%">									
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для автокомплита</span></p>
						<input type="number" step="0.1" name="config_elasticsearch_fuzziness_autcocomplete" value="<?php echo $config_elasticsearch_fuzziness_autcocomplete; ?>" size="3" style="width:100px;" />
						
						<br />
						<span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>										
					</td>

					<td width="20%">									
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Суффикс именования индексов</span></p>
						<input type="text" name="config_elasticsearch_index_suffix" value="<?php echo $config_elasticsearch_index_suffix; ?>" size="20" style="width:100px;" />
						
						<br />
						<span class="help"><i class="fa fa-search"></i> в случае работы нескольки магазинов на одном движке</span>										
					</td>

					<td width="20%">									
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Использовать свои склады</span></p>
						<select name="config_elasticsearch_use_local_stock">
							<?php if ($config_elasticsearch_use_local_stock) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						
						<br />
						<span class="help"><i class="fa fa-search"></i> Если включено, товары, которые есть на локальном складе - всегда будут вверху любых результатов поиска. При этом товары, которых нет на складе - значительно пессимизируются в выдаче</span>										
					</td>>


				</tr>
			</table>

			<h2><i class="fa fa-search"></i> Reacher API (валидация мейлов)</h2>
			
			<table class="form">
				<tr>
					<td style="width:15%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3f6ad8; color:#FFF">Включить Reacher API</span></p>
						<select name="config_reacher_enable">
							<?php if ($config_reacher_enable) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>

					</td>
					<td style="width:15%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Reacher URL</span></p>
						<input type="text" name="config_reacher_uri" value="<?php echo $config_reacher_uri; ?>" size="40" style="width:90%;" />	
					</td>
					
					<td style="width:15%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Reacher AUTH</span></p>
						<input type="text" name="config_reacher_auth" value="<?php echo $config_reacher_auth; ?>" size="40" style="width:90%;" />											
					</td>

					<td style="width:15%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Reacher KEY</span></p>
						<input type="text" name="config_reacher_key" value="<?php echo $config_reacher_key; ?>" size="40" style="width:90%" />											
					</td>

					<td style="width:15%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Reacher FROM</span></p>
						<input type="text" name="config_reacher_from" value="<?php echo $config_reacher_from; ?>" size="40" style="width:90%" />											
					</td>

					<td style="width:15%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Reacher HELO</span></p>
						<input type="text" name="config_reacher_helo" value="<?php echo $config_reacher_helo; ?>" size="40" style="width:90%;" />											
					</td>
				</tr>
			</table>
			
		</div>