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
					<tr <?php if (!$this->config->get('config_single_special_price')) { ?>style="display:none;"<? } ?>>
						<td><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Общая скидочная цена</span></td>
						<td>
							<input type="text" name="price_special" value="<?php echo $price_special; ?>" />&nbsp;<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->config->get('config_currency'); ?></span>				
						</td>									
					</tr>
					<tr <?php if (!$this->config->get('config_single_special_price')) { ?>style="display:none;"<? } ?>>
						<td>
							<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Следующая скидочна цена</span>																							
						</td>
						<td>
							<input type="text" name="price_special_delayed" value="<?php echo $price_special_delayed; ?>" />&nbsp;<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->config->get('config_currency'); ?></span>	
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
							&nbsp;&nbsp;до&nbsp;&nbsp;<input type="text" name="ignore_parse_date_to" class="date" value="<? echo $ignore_parse_date_to ?>" size="12" style="width:150px;" />
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
							<br />
							<span style="font-size:10px;">
								<? foreach ($currencies as $c) { ?>
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $c['code']; ?></span>&nbsp;							
								<? } ?>
							</span>
							</td>
						</tr>
					</table>

					<?php if ($this->config->get('config_product_quality_groups_enable')) { ?>
					<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(255, 120, 21); color:#FFF">Группа качества товара</span></h2>
					<input type="hidden" id="product_group_id" name="product_group_id" value="<?php echo $product_group_id; ?>" />
					<style>
						.product_group{
							padding:5px;
							float:left;
							margin-left:5px;
							border:4px solid white;	
							cursor: pointer;						
						}
						.product_group.active{
							border:4px solid;	
							border-color:rgb(255, 120, 21);	
						}
					</style>
					<table class="form">
						<tr>
							<td class="left">
								<span data-product-group-id="0" class="product_group <?php if ($product_group_id == 0) { ?>active<?php } ?>" style="color:#FFF; background-color:#000"><i class="fa fa-times-circle" ></i> Не в группе</span>

								<?php foreach ($product_groups as $product_group) { ?>
									<span data-product-group-id="<?php echo $product_group['product_group_id']; ?>" class="product_group <?php if ($product_group['product_group_id'] == $product_group_id) { ?>active<?php } ?>" style="color:#<?php echo $product_group['product_group_text_color']; ?>; background-color:#<?php echo $product_group['product_group_bg_color']; ?>">

										<?php if ($product_group['product_group_fa_icon']) { ?>
                      							<i class="fa <?php echo $product_group['product_group_fa_icon']; ?>"></i>
                      					<?php } ?>

										<?php echo $product_group['product_group_name'] ?>											
									</span>
								<?php } ?>

								<script>
									$('.product_group').click(function(){
										$('.product_group').removeClass('active');
										$(this).addClass('active');
										$('#product_group_id').val($(this).attr('data-product-group-id'));
									});
								</script>
							</td>							
						</tr>
					</table>
					<?php } else { ?>	
						<input type="hidden" id="product_group_id" name="product_group_id" value="0" />
					<?php } ?>


					<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Цены локальных поставщиков</span></h2>
					<table class="list">
						<thead>
							<tr>
								<td class="center">Поставщик</td>
								<td class="center">Цена</td>
								<td class="center">Скидка</td>
								<td class="center">Наличие</td>
								<td class="center">Количество</td>
							</tr>
						</thead>
						<?php foreach ($product_suppliers as $product_supplier) { ?>
							<tr>
								<td class="center">
									<?php echo $product_supplier['name']; ?>									
								</td>
								<td class="center">
									<?php echo $product_supplier['price']; ?>									
								</td>
								<td class="center">
									<?php echo $product_supplier['price_special']; ?>									
								</td>
								<td class="center">
									<? if ($product_supplier['stock']) { ?>
										<i class="fa fa-check-circle" style="color:#4ea24e"></i>
									<? } else { ?>
										<i class="fa fa-times-circle" style="color:#cf4a61"></i>
									<? } ?>
								</td>
								<td class="center">
									<?php echo $product_supplier['quantity']; ?>									
								</td>
							</td>
						</tr>
					<?php } ?>
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
									<input style="width:150px;" type="text" name="product_price_to_store[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_to_store[$store['store_id']])?$product_price_to_store[$store['store_id']]['price']:''; ?>" />			<br /><span class="help">текущая цена</span>																					

									<?php if (!empty($product_price_to_store[$store['store_id']]) && !empty($product_price_to_store[$store['store_id']]['price'])) {
										$value_in_national_currency = $this->currency->format($product_price_to_store[$store['store_id']]['price'], $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id'])); 																		
									} ?>

									<?php if (!empty($value_in_national_currency)) { ?>
										<br /><span class="help"><?php echo $value_in_national_currency; ?></span>
									<?php } ?>

								</td>

								<td>
									<input style="width:150px;" type="text" name="product_price_to_store[<? echo $store['store_id']; ?>][price_delayed]" value="<?php echo isset($product_price_to_store[$store['store_id']])?$product_price_to_store[$store['store_id']]['price_delayed']:''; ?>" /><br /><span class="help">следующая цена</span>

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
									<input style="width:150px;" type="text" name="product_price_national_to_store[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_national_to_store[$store['store_id']])?$product_price_national_to_store[$store['store_id']]['price']:''; ?>" />
									<br /><span class="help">текущая цена</span>
								</td>

								<td>
									<input style="width:150px;" type="text" name="product_price_national_to_store[<? echo $store['store_id']; ?>][price_delayed]" value="<?php echo isset($product_price_national_to_store[$store['store_id']])?$product_price_national_to_store[$store['store_id']]['price_delayed']:''; ?>" />
									
									<br /><span class="help">следующая цена</span>
								</td>	

								<td>
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
