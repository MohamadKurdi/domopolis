<div id="tab-yandex-market">
	<table class="form">
		<td width="33%" valign="top">

			<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Переназначение для Yandex Market</span></h2>

			<table class="form">
				<tr>																			
					<td>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Общая цена YAM</span>
					</td>
					<td>
						<input type="text" name="yam_price" value="<?php echo $yam_price; ?>" />
						<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
							<br />
							<span class="help">включено автоматическое назначение цены исходя из текущей цены фронта, изменения тут перезапишутся через 2 часа</span>
						<?php } else { ?>
							<br />
							<span class="help">автоматическое переназначение цены не включено</span>
						<?php } ?>
					</td>
				</tr>

				<tr>																			
					<td>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Процент наценки YAM</span>
					</td>
					<td>
						<input type="text" name="yam_percent" value="<?php echo $yam_percent; ?>" />
						<br />
						<span class="help">по-умолчанию настроена наценка <? echo $this->config->get('config_yam_plus_percent');?>%</span>
						<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
							<span class="help">включено автоматическое назначение цены исходя из текущей цены фронта</span>
						<?php } ?>
					</td>

				</tr>

				<tr>																			
					<td>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Скидочная цена YAM</span>
					</td>
					<td>
						<input type="text" name="yam_special" value="<?php echo $yam_special; ?>" />
					</td>
				</tr>

				<tr>																			
					<td>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Процент скидки YAM</span>
					</td>
					<td>
						<input type="text" name="yam_special_percent" value="<?php echo $yam_special_percent; ?>" />
						<br />
						<span class="help">Скидка считается от заданной цены маркета</span>
						<span class="help">по-умолчанию настроена скидка <? echo $this->config->get('config_yam_plus_for_main_price');?>%</span>
						<?php if ($this->config->get('config_yam_enable_plus_for_main_price')) { ?>
							<span class="help">включено автоматическое назначение скидки исходя из текущей цены маркета</span>
						<?php } else { ?>
							<span class="help">автоматическое переназначение скидки не включено</span>
						<?php } ?>
					</td>

				</tr>

				<tr style="border-bottom:1px dashed gray">																			
					<td>
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Валюта для YAM</span>
						<br /><span class="help">по умолчанию: RUB</span>
					</td>
					<td>
						<input type="text" name="yam_currency" value="<?php echo $yam_currency; ?>" />
						<br /><span style="font-size:10px;">
							<? foreach ($currencies as $c) { ?>
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $c['code']; ?></span>&nbsp;							
								<? } ?></span>
							</td>
						</tr>


						<? $this->load->model('setting/setting'); ?>
						<? foreach ($stores as $store) { ?>															
							<tr>																			
								<td>
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><? echo $store['name']; ?></span>
									<br /><span class="help">переназначение цены</span>
								</td>
								<td>

									<input style="width:200px;" type="text" name="product_price_national_to_yam[<? echo $store['store_id']; ?>][price]" value="<?php echo isset($product_price_national_to_yam[$store['store_id']])?$product_price_national_to_yam[$store['store_id']]['price']:''; ?>" />&nbsp;
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><? echo $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']); ?></span>

									<span style="display:inline-block; padding:4px 6px; margin:3px; background:#f91c02; color:#FFF;">
										<input type="checkbox" name="product_price_national_to_yam[<? echo $store['store_id']; ?>][dot_not_overload_1c]" value="1" <?php if (isset($product_price_national_to_yam[$store['store_id']]) && $product_price_national_to_yam[$store['store_id']]['dot_not_overload_1c']) print 'checked'; ?>/>не переназначать из 1С
									</span>				

									<?php if (isset($product_price_national_to_yam[$store['store_id']]) && $product_price_national_to_yam[$store['store_id']]['settled_from_1c']) { ?>
										<input type="hidden" name="product_price_national_to_yam[<? echo $store['store_id']; ?>][settled_from_1c]" value="1" />
										<br /><span style="font-size:9px;"><i class="fa fa-info-circle"></i> Цена YAM установлена из 1С</span>
									<? }?>

								</td>
							</tr>
						<? } ?>

					</table>	
				</td>

				<td width="66%" valign="top">
					<h2><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Ценообразование YAM</span></h2>

					<table class="form" id="yam-price-suggest-result">
					</table>
				</td>
				<?php if (!empty($this->request->get['product_id']) && $yam_product_id && $quantity_stockM) { ?>
					<script>
						function parsePrices(json){
							console.log('YAM PRICE PARSE');
							console.log(json);

							jQuery.each(json, function(i, val) {

								var html = '';
								html += '<tr>';
								html += '<td style="font-weight:700">';
								html += val.type;
								html += '<td>';
								html += '<td>';
								html += val.explanation;
								html += '<td>';
								html += '<td style="font-weight:700; white-space:nowrap">';
								html += val.price;
								html += '<td>';
								html += '</tr>';

								$('#yam-price-suggest-result').append(html);

							});
						}

						$(document).ready(function(){
							console.log('YAM PRICE SUGGEST');
							$.get('<?php echo HTTPS_CATALOG; ?>yamarket-partner-api/offerprice?product_id=<?php echo $product_id; ?>', null, function(json){ parsePrices(json) }, 'json');
						});
					<?php } ?>
				</script>
			</table>
		</div>