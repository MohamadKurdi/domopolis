<div id="tab-price">
	<table class="form">
		<tr style="border-bottom:1px dashed gray">
			<td width="50%" valign="top">
				<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Общие настройки</span></h2>

				<table class="form">
					<tr>
						<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Общая цена</span></td>
						<td>
							<input type="text" name="price" value="<?php echo $price; ?>" />&nbsp;<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->config->get('config_currency'); ?></span>				
						</td>									
					</tr>
					<tr>
						<td>
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Следующая цена</span>																								
						</td>
						<td>
							<input type="text" name="price_delayed" value="<?php echo $price_delayed; ?>" />&nbsp;<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->config->get('config_currency'); ?></span>	

							<span class="help">Будет назначена при переформировании гугл-фидов</span>			
						</td>									
					</tr>																						
					<tr>
						<td>
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Себестоимость</span>																								
						</td>
						<td>
							<input type="text" name="costprice" value="<?php echo $costprice; ?>" />&nbsp;<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->config->get('config_currency'); ?></span>	

							<span class="help">себестоимость, определенная по формулам, если используется Rainforest API, либо можно задавать вручную</span>			
						</td>									
					</tr>
					<tr>
						<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Не обновлять цены</span></td>
						<td>
							<input type="checkbox" name="ignore_parse" value="1" <?php if (isset($ignore_parse) && $ignore_parse) print 'checked'; ?>/>
							&nbsp;&nbsp;до&nbsp;&nbsp;<input type="text" name="ignore_parse_date_to" class="date" value="<? echo $ignore_parse_date_to ?>" size="12" style="width:200px;" />
						</td>
					</tr>
					<tr>
						<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Гипер-наценка</span></td>
						<td><input type="checkbox" name="big_business" value="1" <?php if (isset($big_business) && $big_business) print 'checked'; ?> />
							<span class="help">в парсерах и при обновлении цен будет использован повышенный коэффициент</span>
						</td>							
					</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Переназначение в нацвалюте (сертификаты)</span></h2>
				<table class="form">
					<tr>
						<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Фикс. цена в нацвалюте</span><br /><span class="help">только для единичных товаров</span></td>
						<td><input type="text" name="price_national" value="<?php echo $price_national; ?>" /></td>
					</tr>	
					<tr>
						<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Валюта нацвалюты</span></td>
						<td>
							<input type="text" name="currency" value="<?php echo $currency; ?>" />
							<br /><span style="font-size:10px;"><? foreach ($currencies as $c) { ?>
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $c['code']; ?></span>&nbsp;							
								<? } ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>				
		</table>

		<table class="form">
			<tr style="border-bottom:1px dashed gray">
				<td width="50%" valign="top">
					<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Переназначение в основной валюте, <? echo $this->config->get('config_currency'); ?></span></h2>

					<table class="form">
						<? $this->load->model('setting/setting'); ?>

						<? foreach ($stores as $store) { ?>

							<tr>
								<td style="width:150px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $store['name']; ?></span>
									<br /><span class="help">переназначение цены</span>
								</td>
								<td>
									<input style="width:200px;" type="text" name="product_price_to_store[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_to_store[$store['store_id']])?$product_price_to_store[$store['store_id']]['price']:''; ?>" />			<br /><span class="help">текущая цена</span>																					

									<?php if (!empty($product_price_to_store[$store['store_id']]) && !empty($product_price_to_store[$store['store_id']]['price'])) {

										$value_in_national_currency = $this->currency->format($product_price_to_store[$store['store_id']]['price'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id'])); 																		
									} ?>

									<?php if (!empty($value_in_national_currency)) { ?>
										<br /><span class="help"><?php echo $value_in_national_currency; ?></span>
									<?php } ?>

								</td>

								<td>
									<input style="width:200px;" type="text" name="product_price_to_store[<? echo $store['store_id']; ?>][price_delayed]" value="<?php echo isset($product_price_to_store[$store['store_id']])?$product_price_to_store[$store['store_id']]['price_delayed']:''; ?>" /><br /><span class="help">следующая цена</span>

									<?php if (!empty($product_price_to_store[$store['store_id']]) && !empty($product_price_to_store[$store['store_id']]['price_delayed'])) {

										$value_in_national_currency = $this->currency->format($product_price_to_store[$store['store_id']]['price_delayed'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id'])); 																		
									} ?>

									<?php if (!empty($value_in_national_currency)) { ?>
										<br /><span class="help"><?php echo $value_in_national_currency; ?></span>
									<?php } ?>

								</td>


								<td>
									<span style="display:inline-block; padding:4px 6px; margin:3px; background:#4ea24e; color:#FFF;">
										<input type="checkbox" name="product_price_to_store[<? echo $store['store_id']; ?>][dot_not_overload_1c]" value="1" <?php if (isset($product_price_to_store[$store['store_id']]) && $product_price_to_store[$store['store_id']]['dot_not_overload_1c']) print 'checked'; ?>/>не переназначать из 1С
									</span>				

									<?php if (isset($product_price_to_store[$store['store_id']]) && $product_price_to_store[$store['store_id']]['settled_from_1c']) { ?>
										<input type="hidden" name="product_price_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="1" />
										<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> Цена установлена из 1С</span>
									<? } else { ?>
										<input type="hidden" name="product_price_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="0" />
										<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> Цена установлена не из 1С</span>
									<?php } ?>
								</td>
							</tr>
						<? } ?>

					</table>
				</td>
				<td width="50%" valign="top">

					<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Переназначение в нацвалюте, в большинстве случаев - РРЦ</span></h2>

					<table class="form">
						<? $this->load->model('setting/setting'); ?>

						<? foreach ($stores as $store) { ?>	
							<tr>
								<td>
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><? echo $store['name']; ?></span>
									<br /><span class="help">переназначение цены</span>
								</td>
								<td>
									<input style="width:200px;" type="text" name="product_price_national_to_store[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_national_to_store[$store['store_id']])?$product_price_national_to_store[$store['store_id']]['price']:''; ?>" />&nbsp;
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']); ?></span>
								</td>
								<td>
									<span style="display:inline-block; padding:4px 6px; margin:3px; background:#ff7815; color:#FFF;">
										<input type="checkbox" name="product_price_national_to_store[<? echo $store['store_id']; ?>][dot_not_overload_1c]" value="1" <?php if (isset($product_price_national_to_store[$store['store_id']]) && $product_price_national_to_store[$store['store_id']]['dot_not_overload_1c']) print 'checked'; ?>/>не переназначать из 1С
									</span>				

									<?php if (isset($product_price_national_to_store[$store['store_id']]) && $product_price_national_to_store[$store['store_id']]['settled_from_1c']) { ?>
										<input type="hidden" name="product_price_national_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="1" />
										<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> РРЦ установлена из 1С</span>
									<? } else { ?>
										<input type="hidden" name="product_price_national_to_store[<? echo $store['store_id']; ?>][settled_from_1c]" value="0" />
										<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> РРЦ установлена не из 1С</span>
									<?php } ?>

								</td>
							</tr>
						<? } ?>

					</table>	
				</td>
			</tr>				
		</table>
	</div>
