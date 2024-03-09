			<div id="tab-deliveryapis">

				<h2>Настройки воркеров служб доставки</h2>
				<table>
					<tr>
						<td style="width:20%">
							<div>
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Трекер накладных</span></p>
								<select name="config_shipping_enable_tracker_worker">
									<?php if ($config_shipping_enable_tracker_worker) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</div>
							<div>
								<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

								<input type="time" name="config_shipping_enable_tracker_worker_time_start" value="<?php echo $config_shipping_enable_tracker_worker_time_start; ?>" size="50" style="width:70px;" /> - 
								<input type="time" name="config_shipping_enable_tracker_worker_time_end" value="<?php echo $config_shipping_enable_tracker_worker_time_end; ?>" size="50" style="width:70px;" />
							</div>										
						</td>
					</tr>
				</table>


				<h2>Новая Почта API</h2>
				<table class="form">
					<tr>
						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API KEY</span></p>
							<input type="text" name="config_novaposhta_api_key" value="<?php echo $config_novaposhta_api_key; ?>" size="50" style="width:300px;" />
						</td>
						
						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">GUID По-умолчанию</span></p>
							<input type="text" name="config_novaposhta_default_city_guid" value="<?php echo $config_novaposhta_default_city_guid; ?>" size="50" style="width:300px;" />
						</td>

						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">UA Язык для Новой Почты</span></p>
							<select name="config_novaposhta_ua_language">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_novaposhta_ua_language) { ?>
										<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>

						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">RU Язык для Новой Почты</span></p>
							<select name="config_novaposhta_ru_language">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_novaposhta_ru_language) { ?>
										<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>

						
					</tr>
				</table>


				<h2>JUSTIN API</h2>
				<table class="form">
					<tr>
						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API KEY</span></p>
							<input type="text" name="config_justin_api_key" value="<?php echo $config_justin_api_key; ?>" size="50" style="width:300px;" />
						</td>
						
						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API USER</span></p>
							<input type="text" name="config_justin_api_login" value="<?php echo $config_justin_api_login; ?>" size="50" style="width:300px;" />
						</td>

						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">UA Язык для Justin</span></p>
							<select name="config_justin_ua_language">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_justin_ua_language) { ?>
										<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>

						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">RU Язык для Justin</span></p>
							<select name="config_justin_ru_language">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_justin_ru_language) { ?>
										<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>
					</tr>
				</table>

				<h2>Укрпочта API</h2>
				<table class="form">
					<tr>
						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API BEARER</span></p>
							<input type="text" name="config_ukrposhta_api_bearer" value="<?php echo $config_ukrposhta_api_bearer; ?>" size="50" style="width:300px;" />
						</td>
						
						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API TOKEN</span></p>
							<input type="text" name="config_ukrposhta_api_token" value="<?php echo $config_ukrposhta_api_token; ?>" size="50" style="width:300px;" />
						</td>

						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">UA Язык для УкрПочты</span></p>
							<select name="config_ukrposhta_ua_language">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_ukrposhta_ua_language) { ?>
										<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>

						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">RU Язык для УкрПочты</span></p>
							<select name="config_ukrposhta_ru_language">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_ukrposhta_ru_language) { ?>
										<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>
					</tr>
				</table>


				<h2>СДЭК API</h2>
				<table class="form">
					<tr>
						<td width="15%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API LOGIN</span></p>
							<input type="text" name="config_cdek_api_login" value="<?php echo $config_cdek_api_login; ?>" size="50" style="width:90%;" />
						</td>
						
						<td width="15%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffcc00; color:#FFF">API KEY</span></p>
							<input type="text" name="config_cdek_api_key" value="<?php echo $config_cdek_api_key; ?>" size="50" style="width:90%;" />
						</td>

						<td width="15%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Стоимость на чекауте</span></p>
							<select name="config_cdek_calculate_on_checkout">
								<?php if ($config_cdek_calculate_on_checkout) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>										
						</td>

						<td width="15%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Тариф по-умолчанию склад-склад</span></p>
							<select name="config_cdek_api_default_tariff_warehouse">
								<?php foreach ($cdek_tariffs as $cdek_tariff) { ?>
									<?php if ($cdek_tariff['code'] == $config_cdek_api_default_tariff_warehouse) { ?>
										<option value="<?php echo $cdek_tariff['code']; ?>" selected="selected"><?php echo $cdek_tariff['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $cdek_tariff['code']; ?>"><?php echo $cdek_tariff['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>

						<td width="15%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Тариф по-умолчанию склад-двери</span></p>
							<select name="config_cdek_api_default_tariff_doors">
								<?php foreach ($cdek_tariffs as $cdek_tariff) { ?>
									<?php if ($cdek_tariff['code'] == $config_cdek_api_default_tariff_doors) { ?>
										<option value="<?php echo $cdek_tariff['code']; ?>" selected="selected"><?php echo $cdek_tariff['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $cdek_tariff['code']; ?>"><?php echo $cdek_tariff['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>

						<td width="15%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ID города-отправителя</span></p>
							<input type="text" name="config_cdek_api_city_sender_id" value="<?php echo $config_cdek_api_city_sender_id; ?>" size="50" style="width:300px;" />										
						</td>


					</tr>							
				</table>

			</div>
