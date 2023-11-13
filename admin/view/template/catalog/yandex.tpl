<?php echo $header; ?>
<div id="content">
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1 style="color:#cf4a61; padding-top:10px;padding-left:10px;">YA Market
				<?php if ($this->config->get('config_ozon_enable_price_yam')) { ?>, OZON<?php } ?>
			</h1>					
			
			<style>
				a.button{color:#cf4a61; border-color:#cf4a61}
				a.button.active{color:white; background-color:#cf4a61}
			</style>
			
			<div class="buttons" style="padding-top:10px;padding-left:10px;">
				<a class="button <?php if (!empty($filter_buybox_failed)) { ?>active<? } ?>"  href="<?php echo $href_filter_buybox_failed; ?>"><i class="fa fa-exclamation-triangle"></i>  BBOX <?php if ($total_buybox_failed) { ?>(<?php echo $total_buybox_failed; ?>)<?php } ?></a>			
				
				<a class="button <?php if (!empty($filter_is_illiquid)) { ?>active<? } ?>" href="<?php echo $href_filter_is_illiquid; ?>"><i class="fa fa-exclamation-triangle"></i> Нлквд <?php if ($total_not_liquid) { ?>(<?php echo $total_not_liquid; ?>)<?php } ?></a>
				
				<a class="button <?php if (!empty($filter_yam_hidden)) { ?>active<? } ?>" href="<?php echo $href_filter_yam_hidden; ?>"><i class="fa fa-exclamation-triangle"></i> Скрыто <i class="fa fa-yahoo"></i> <?php if ($total_yam_hidden) { ?>(<?php echo $total_yam_hidden; ?>)<?php } ?></a>
				
				<a class="button <?php if (!empty($filter_notinfeed)) { ?>active<? } ?>" href="<?php echo $href_filter_notinfeed; ?>"><i class="fa fa-exclamation-triangle"></i> -фид <i class="fa fa-yahoo"></i> <?php if ($total_not_in_feed) { ?>(<?php echo $total_not_in_feed; ?>)<?php } ?></a>
				
				<a class="button <?php if (!empty($filter_yam_not_created)) { ?>active<? } ?>" href="<?php echo $href_filter_yam_not_created; ?>"><i class="fa fa-exclamation-triangle"></i> Нет карты <i class="fa fa-yahoo"></i> <?php if ($total_yam_not_created) { ?>(<?php echo $total_yam_not_created; ?>)<?php } ?></a>				
			</div>
		</div>
		<div class="content">

			<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
				<div style="margin-left:10px; float:left; padding-top:10px;">
					<i class="fa fa-info-circle"></i> включено автоматическое назначение цен. менять можно только проценты наценки и скидки<br />
					<i class="fa fa-info-circle"></i> процент наценки по умолчанию: <?php echo $this->config->get('config_yam_plus_percent'); ?>%
				</div>
			<?php } else { ?>
				
			<?php } ?>

			<form>
				<table style="width: 100%;">
					<tr class="filter">
						<td class="left" width="360px;">							
							<input type="text" id="filter_name" name="filter_name" style="width:350px!important;" autocomplete="off" value="<?php echo $filter_name; ?>" placeholder="Название, ID, YAM ID, SKU, EAN" />
						</td>
						
						<td class="left">							
							<select name="filter_manufacturer_id" style="width:250px;">
								<option value="*">- Выбери бренд -</option>
								<? foreach ($manufacturers as $manufacturer) { ?>
									<option value="<? echo $manufacturer['manufacturer_id'] ?>" <?php if ($manufacturer['manufacturer_id'] == $filter_manufacturer_id) { ?>selected="selected"<?php } ?>><? echo $manufacturer['name']; ?></option>
								<? } ?>
							</select>
						</td>
						
						<td class="left">							
							<select name="filter_yam_category_id" style="width:350px;">
								<option value="*">- Выбери категорию Yandex -</option>
								<? foreach ($yandex_categories as $yandex_category) { ?>
									<option value="<? echo $yandex_category['yam_category_id'] ?>" <?php if ($yandex_category['yam_category_id'] == $filter_yam_category_id) { ?>selected="selected"<?php } ?>><? echo $yandex_category['yam_category_name']; ?> (<?php echo $yandex_category['total_products']; ?>)</option>
								<? } ?>
							</select>
						</td>
						
					</tr>
					
					
					<tr>
						<td colspan="3" align="right" style="padding-right:10px;"><a onclick="filter(false);" class="button">Фильтр</a></td>
					</tr>
				</table>
			</form>
			
			<div class="filter_bord"></div>
			
			<table class="list bold-inputs">				
				<thead>
					<tr>
						
						<td class="center" width="1"></td>
						<td class="center"></td>
						
						<td class="left">
							<?php if ($sort == 'pd.name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Товар</a>
							<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><i class="fa fa-sort"></i> Товар</a>
							<?php } ?>
						</td>
						
						
						<td class="left">
							Цена магазина						
						</td>
						
						<td class="left">
							Без комиссии
						</td>
						
						<td class="left">
							₽, Цена <i class="fa fa-yahoo"></i>
						</td>
						
						<td class="left">
							₽, Скидка <i class="fa fa-yahoo"></i>
						</td>
						
						<td class="left">
							Себестоимость
						</td>
						
						<td class="left">
							МВЦ Фронта
						</td>
						
						
						<td class="right" style="width:50px;">
							<?php if ($sort == 'p.quantity_stockM') { ?>
								<a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>">Склад</a>
							<?php } else { ?>
								<a href="<?php echo $sort_quantity; ?>"><i class="fa fa-sort"></i> Склад</a>
							<?php } ?>
						</td>
						
						<?php foreach ($yandex_pricetypes as $price_type) { ?>
							<td class="left" style="font-weight:700;">
								<small style="font-weight:900;font-size:9px;"><?php echo str_replace('_', ' ', $price_type); ?></small>
							</td>
						<?php } ?>
						
						<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
							<td class="left" width="100px">
								Наценка <i class="fa fa-yahoo"></i>.
							</td>
							
							<td class="left" width="100px">
								Скидка <i class="fa fa-yahoo"></i>, %
							</td>
						<?php } ?>
						
						<td class="right" width="35px">
							
						</td>
						
					</tr>
				</thead>
				<tbody>
					
					<?php if ($products) { ?>
						<?php foreach ($products as $product) { ?>
							<tr>
								
								<td class="center">
									
									<?php if ($product['problem']) { ?>
										<i class="fa fa-exclamation-triangle" style="color:#cf4a61; font-size:24px;"></i>
										<?php if ($product['min_sale_price_percent_problem']) { ?>
											<br />
											<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#cf4a61;">ЦЕНА</span>
										<?php } ?>
										
										<?php if ($product['cost_percent_problem']) { ?>
											<br />
											<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#cf4a61;">СБСТ</span>
										<?php } ?>
										
										<?php if ($product['price_too_low_problem']) { ?>
											<br />
											<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#cf4a61;">МВЦ</span>
										<?php } ?>	
										
									<? } elseif ($product['price_warning']) { ?>
										<i class="fa fa-question-circle" style="color:#ff7815; font-size:24px;"></i>
										
									<?php } else { ?>
										<i class="fa fa-check-circle" style="color:#51a881; font-size:24px;"></i>
									<?php } ?>
									
								</td>	
								
								<td class="center">
									<img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" loading="lazy" />
									
									<br />
									<?php if ($product['yam_in_feed']) { ?>
										<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#51a881;">+фид <i class="fa fa-yahoo"></i></span>
									<?php } else { ?>
										<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#cf4a61;">-фид <i class="fa fa-yahoo"></i></span>
									<?php } ?>

									<?php if ($this->config->get('config_ozon_enable_price_yam')) { ?>
										<br />
										<?php if ($product['ozon_in_feed']) { ?>
											<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#51a881;">+фид O</span>
										<?php } else { ?>
											<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#cf4a61;">-фид O</span>
										<?php } ?>
									<?php } ?>
									
									<br />
									
									<span onclick="$(this).load('<?php echo $product['hideOrShow'];?>')" style="cursor:pointer; font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#<?php if ($product['yam_hidden']) { ?>cf4a61<?php } else { ?>51a881<?php } ?>;">
										
										<?php if ($product['yam_hidden']) { ?><i class="fa fa-eye-slash"></i> скрыт<?php } else { ?><i class="fa fa-eye"></i> ок<?php } ?>
										
									</span>
									
									
								</td>
								
								<td class="left">
									<b><?php echo $product['name']; ?></b>
									
									
									<?php if ($product['yam_category_name']) { ?>
										<div style="margin-top:5px;font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#ff7815;">
											<i class="fa fa-yahoo"></i>	<?php echo $product['yam_category_name']; ?>
										</div>
									<?php } ?>
									
									
									<div style="margin-top:5px;">																		
										
										<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#000;"><?php echo $product['manufacturer']; ?></span>
										
										<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#cf4a61;"><?php echo $product['yam_product_id']; ?></span>
										
										<?php if ($product['yam_marketSku']) { ?>
											<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#ffaa56;"><?php echo $product['yam_marketSku']; ?></span>
										<?php } ?>
										
										<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:grey;"><?php echo $product['model']; ?></span>
										<? /*	
											<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#51a881;"><?php echo $product['product_id']; ?></span>
										*/ ?>
										
										<?php if ($product['ean']) { ?>
											<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><?php echo $product['ean']; ?></span>
										<?php } ?>
										
										
									</div>
									
									<div style="margin-top:5px;">
										
										<?php if ($product['yam_disable']) { ?>
											<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;">YAM выкл</span>
										<?php } ?>
										
										<?php if ($product['yam_not_created']) { ?>
											<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;">Карта YAM не создана</span>
										<?php } ?>
										
										<?php if ($product['is_illiquid']) { ?>
											<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;"><i class="fa fa-exclamation-triangle"></i> Неликвид</span>
										<?php } ?>
									</div>
									
								</td>
								
								
								<td class="left" style=" white-space:nowrap; font-weight:700;">
									<?php if ($product['special']) { ?>
										<span style="color:#008000"><?php echo $product['special']; ?></span>
										<br /><small style="color:#cf4a61"><s><?php echo $product['price']; ?></s></small>
									<?php } else { ?>									
										<span style="color:#008000"><?php echo $product['price']; ?></span>
									<?php } ?>
								</td>
								
								<td class="left" style=" white-space:nowrap; font-weight:700;">
									<span style="color:#7F00FF">
										~<?php echo $product['yam_money_default']; ?>
									</span>
									
									<div style="color:#7F00FF" style="margin-top:5px;">
										<?php if ($product['yam_money']) { ?>=<? } ?><?php echo $product['yam_money']; ?>
									</div>
									
									<div>
										<?php if (!empty($product['yam_tariffs'])) { ?>
											<?php foreach ($product['yam_tariffs'] as $yam_tariff) { ?>
												<div style="font-size:9px;font-weight:400;"><?php echo $yam_tariff['type']; ?>, <b><? echo $yam_tariff['percent']; ?>%</b>, <? echo $yam_tariff['amount']; ?></div>
											<?php } ?>
										<?php } ?>
									</div>
									
								</td>
								
								<td class="left" style="white-space:nowrap; font-weight:700;">
									
									
									<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
										<?php echo $product['yam_price']; ?>
									<?php } else { ?>
										
										<input type="text" id="<? echo $product['product_id']; ?>-yam-price" class="onfocusedit_direct" data-field="yam_price" data-product-id="<? echo $product['product_id']; ?>" style="color:#008000" value="<?php echo $product['yam_price']; ?>" />
										
									<?php } ?>
									
									
									<?php if (!$product['yam_set']) { ?>
										<div><small style="font-weight:400;"><i class="fa fa-exclamation-triangle"></i>не задана</small></div>
									<? } ?>
									
									
									<div>
										<small style="color:#cf4a61">API: <?php echo $product['yam_real_price']; ?></small>
									</div>
									
									
								</td>
								
								<td class="left" style="white-space:nowrap; font-weight:700;">
									
									<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
										<?php echo $product['yam_special']; ?>
									<?php } else { ?>
										
										<input type="text" class="onfocusedit_direct" data-field="yam_special" data-product-id="<? echo $product['product_id']; ?>" style="color:#cf4a61" value="<?php echo $product['yam_special']?$product['yam_special']:0; ?>" />
										
									<?php } ?>
									
									<div>
										<small style="color:#cf4a61">API: <?php echo $product['yam_real_price']; ?></small>
									</div>
									
								</td>
								
								<td class="left" style="white-space:nowrap; font-weight:700;">
									
									
									<?php if ($product['cost_formatted']) { ?>
										<?php echo $product['cost_formatted']; ?>
										
										
										<?php if ($product['cost_in_eur']) { ?>
											<div style="margin-top:5px;">

												<span style="display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><small><?php echo $product['cost_in_eur']; ?></small></span>


											</div>
										<?php } ?>
										
										
										<div style="margin-top:5px;">
											<span style="display:inline-block; padding:3px; color:#FFF; background-color:<?php if ($product['cost_percent_problem']) { ?>#cf4a61<?php } else {?>#51a881<?php } ?>;"><?php echo $product['cost_percent_diff']; ?>%</span>
										</div>
									<?php } ?>
									
								</td>
								
								<td class="left" style="white-space:nowrap; font-weight:700;">
									
									<?php if ($product['min_sale_price_formatted']) { ?>
										<?php echo $product['min_sale_price_formatted']; ?>
										
										<?php if ($product['min_sale_price_in_eur']) { ?>
											<div style="margin-top:5px;">

												<span style="display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><small><?php echo $product['min_sale_price_in_eur']; ?></small></span>


											</div>
										<?php } ?>
										
										<div style="margin-top:5px;">
											<span style="display:inline-block; padding:3px; color:#FFF; background-color:<?php if ($product['min_sale_price_percent_problem']) { ?>#cf4a61<?php } else {?>#51a881<?php } ?>;"><?php echo $product['min_sale_price_percent_diff']; ?>%</span>
										</div>
									<?php } ?>
									
								</td>

								<td class="center" style=" white-space:nowrap; font-weight:700;">
									<? if ($product['quantity_stockM'] <= 1) { ?>
										<span style="color: #cf4a61;"><?php echo $product['quantity_stockM']; ?> шт.</span>
									<? } elseif ($product['quantity_stockM'] <= 5) { ?>
										<span style="color: #FFA500;"><?php echo $product['quantity_stockM']; ?> шт.</span>
									<?php } else { ?>
										<span style="color: #008000;"><?php echo $product['quantity_stockM']; ?> шт.</span>
									<?php } ?>
								</td>


								<?php foreach ($yandex_pricetypes as $price_type) { ?>
									<td class="left" style=" white-space:nowrap;">
										<?php if ($product[$price_type]) { ?>

											<?php if (!$product[$price_type . '_equals']) { ?>
												<span style="font-weight:700; color:#cf4a61;">
													<?php echo $product[$price_type]; ?>
												</span>										
											<?php } else { ?>
												<span style="font-weight:400; color:#008000;">
													<?php echo $product[$price_type]; ?>
												</span>
											<?php } ?>


											<div style="font-size:9px; margin-top:10px;">

												<?php if (!$this->config->get('config_yam_enable_plus_percent')) { ?>

													<span style="cursor:pointer" onclick="$('#<? echo $product['product_id']; ?>-yam-price').val('<?php echo $product[$price_type . '_unformatted']; ?>').trigger('change');"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo $price_type; ?></span>

												<?php } else { ?>
													<?php echo $price_type; ?>
												<?php } ?>
											</div>

										<?php } ?>
									</td>
								<?php } ?>

								<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>	

									<td class="left" style=" white-space:nowrap; font-weight:700;" width="100px">
										<?php if ($product['config_yam_plus_percent'] && !$product['yam_percent_overload']) { ?>

											<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
												<input type="number" class="onfocusedit_direct" data-field="yam_percent" data-product-id="<? echo $product['product_id']; ?>" style="color: #008000;" value="<?php echo $product['yam_percent']; ?>" />%</span>
											<?php } else { ?>
												<span style="color: #008000;"><?php echo $product['yam_percent']; ?>%</span>
											<?php } ?>
											
										<?php } else { ?>
											
											<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>
												<input type="number" class="onfocusedit_direct" data-field="yam_percent" data-product-id="<? echo $product['product_id']; ?>" style="color: #cf4a61;" value="<?php echo $product['yam_percent']; ?>" />%</span>
											<?php } else { ?>
												<span style="color: #cf4a61;"><?php echo $product['yam_percent']; ?>%</span>
											<?php } ?>

										<?php } ?>
									</td>

									<td class="left" style=" white-space:nowrap; font-weight:700;">
										<?php if ($product['config_yam_enable_plus_for_main_price'] && !$product['yam_special_percent_overload']) { ?>


											<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>

												<input type="number" class="onfocusedit_direct" data-field="yam_special_percent" data-product-id="<? echo $product['product_id']; ?>" style="color: #008000;" value="<?php echo $product['yam_special_percent']; ?>" />%</span>

											<?php } else { ?>

												<span style="color: #008000;">-<?php echo $product['yam_special_percent']; ?>%</span>


											<?php } ?>

										<?php } else { ?>


											<?php if ($this->config->get('config_yam_enable_plus_percent')) { ?>

												<input type="number" class="onfocusedit_direct" data-field="yam_special_percent" data-product-id="<? echo $product['product_id']; ?>" style="color: #cf4a61;" value="<?php echo $product['yam_special_percent']; ?>" />%</span>

											<?php } else { ?>

												<span style="color: #cf4a61;">-<?php echo $product['yam_special_percent']; ?>%</span>

											<?php } ?>

										<?php } ?>
									</td>

								<?php } ?>


								<td class="right" style="width:35px;">
									<a class="button" href="<?php echo $product['edit']; ?>" target="_blank"><i class="fa fa-edit"></i></a>
									<a class="button" href="<?php echo $product['view']; ?>" target="_blank"><i class="fa fa-eye"></i></a>

									<a class="button" href="<?php echo $product['yam_href']; ?>" target="_blank" rel="noreferrer noopener"><i class="fa fa-yahoo"></i></a>
								</td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
</div>
</div>
<style type="text/css">
	input[type="number"]{width:50px!important; font-weight:700}
	.bold-inputs input[type="text"]{width:50px!important; font-weight:700}
	input.onfocusedit_direct, textarea.onfocusedit_direct, textarea.onfocusout_edit_history,  select.onchangeedit_direct, input.onchangeedit_direct, textarea.onfocusedit_source, textarea.onfocusedit_customer{border-left-color:#4ea24e;}
	
	input.onfocusedit_direct.done, textarea.onfocusedit_direct.done, textarea.onfocusout_edit_history.done, select.onchangeedit_direct.done, input.onchangeedit_direct.done, textarea.onfocusedit_source.done, textarea.onfocusedit_customer.done{border-color:#4ea24e;-webkit-transition : border 500ms ease-out;-moz-transition : border 500ms ease-out; -o-transition : border 500ms ease-out;transition : border 500ms ease-out;}
	
	input.onfocusedit_direct.done+span:after, textarea.onfocusedit_direct.done+span:after, textarea.onfocusout_edit_history.done+span:after, select.onchangeedit_direct.done+span:after, textarea.onfocusedit_source.done+span:after,.onchangeedit_orderproduct.done+label+span:after, textarea.onfocusedit_customer.done+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#4ea24e;content:"\f00c"}
	
	input.onfocusedit_direct.loading+span:after, textarea.onfocusedit_direct.loading+span:after, textarea.onfocusout_edit_history.loading+span:after, select.onchangeedit_direct.loading+span:after, textarea.onfocusedit_source.loading+span:after,.onchangeedit_orderproduct.loading+label+span:after,textarea.onfocusedit_customer.loading+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#e4c25a;content:"\f021"}
	
	input.onfocusedit_direct.error+span:after, textarea.onfocusedit_direct.error+span:after, textarea.onfocusout_edit_history.error+span:after, select.onchangeedit_direct.error+span:after, textarea.onfocusedit_source.error+span:after, .onchangeedit_orderproduct.error+label+span:after,textarea.onfocusedit_customer.error+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#cf4a61;content:"\f071"}
	
</style>
<script type="text/javascript">
	$('input.onfocusedit_direct').on('focusout, change', function(){
		var _value = $(this).val();
		var _el = $(this);
		var _elspan = $(this).next('span');
		var _product_id = $(this).attr('data-product-id');
		var _field = $(this).attr('data-field');
		
		$.ajax({
			url : 'index.php?route=catalog/yandex/updateProductFieldAjax&token=<? echo $token; ?>',
			type: 'POST',
			dataType : 'text',
			data : {
				product_id : _product_id,
				field : _field,
				value : _value,
			},
			beforeSend : function(){
				_el.removeClass('done').addClass('loading');
			},
			success : function(text){
				_el.removeClass('loading').addClass('done');
				
				if (text == 'OK'){
					_el.after('<div><small style="color:#51a881"><i class="fa fa-check-circle"></i> API: OK</small></div>');
				} else {
					_el.after('<div><small style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> API: ' + text + '</small></div>');
				}
			},
			error : function(error){
				_el.removeClass('loading').addClass('error');
				console.log(error);
			}			
		});		
	});
	
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/yandex&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_manufacturer_id = $('select[name=\'filter_manufacturer_id\']').children("option:selected").val();

	if (filter_manufacturer_id != '*') {
		url += '&filter_manufacturer_id=' + encodeURIComponent(filter_manufacturer_id);
	}

	var filter_yam_category_id = $('select[name=\'filter_yam_category_id\']').children("option:selected").val();

	if (filter_yam_category_id != '*') {
		url += '&filter_yam_category_id=' + encodeURIComponent(filter_yam_category_id);
	}



	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}


	location = url;
}
//--></script> 

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 

<?php echo $footer; ?>	