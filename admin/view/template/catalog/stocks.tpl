<?php echo $header; ?>

<style>
	tbody td.tr-divider{
		color:#7F00FF!important;
	}
</style>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head" style="height:70px;">
			<h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>			
			<div class="buttons">
				<span id="sync_results" style="margin-right:30px;"></span><a id="update_stocks" class="button">Обновить остатки</a>&nbsp;<a href="<? echo $dynamics_href; ?>" class="button">Динамика расхода</a>

				<a onclick="$('#explanation').toggle()" class="button">Почитать за скидки</a>&nbsp;
			</div>
			<div style="clear:both"></div>
			<div style="float:left;"><i class="fa fa-info" aria-hidden="true" style="color:#cf4a61;"></i>&nbsp;Последняя синхронизация <? echo $stock_last_sync; ?></div>
			<div style="clear:both"></div>
		</div>
		
		<div class="content">
			<div style="margin-bottom:5px; font-size:16px; font-weight:400; padding:3px 5px 10px 3px;border-bottom: 1px dashed grey;">
				<table class="form" style="margin-bottom:10px;">
					<tr>
						<td style="width: 100px">
							<b style="color:#00ad07">ЛИКВИД:</b>
						</td>
						<td style="white-space:nowrap;">
							Создать скидку <input type="number" id="liquid_percent" style="width: 70px" value="10" />%
						</td>
						<td>
							на товары, которые были куплены в количестве <b>от</b>
						</td> 
						<td style="white-space:nowrap;">
							<input type="number" id="liquid_qty" style="width: 70px" value="1" /> шт.
						</td>
						<td>
							за последние
						</td>
						<td style="white-space:nowrap;">
							<input type="number" id="liquid_month" style="width: 70px" value="3" /> мес.
						</td>
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#00ad07;border-color: #00ad07; color:white;" onclick="createLiquidSpecial(1, 0);">СОЗДАТЬ</a>
						</td>
						<td style="white-space:nowrap;">
							<input type="date" id="liquid-results-date" format="yyyy-mm-dd" value="<?php echo date('Y-m-d'); ?>" style="width:100px;" />
						</td>
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#00ad07; border-color:#00ad07; color:white;" onclick="getResults(1)"><i class="fa fa-refresh"></i> РЕЗУЛЬТАТ</a>
						</td>
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#f91c02; border-color:#f91c02; color:white;" onclick="createLiquidSpecial(1, 1)">ОЧИСТИТЬ</a>
						</td>		
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#f91c02; border-color:#f91c02; color:white;" onclick="clearAllSpecials()"><i class="fa fa-exclamation-triangle"></i>УДАЛИТЬ ВСЕ СКИДКИ</a>
						</td>				
					</tr>
					<tr>
						<td style="width: 100px;  white-space:nowrap;">
							<b style="color:#f91c02">НЕЛИКВИД:</b>
						</td>
						<td style="white-space:nowrap;">
							Создать скидку <input type="number" id="illiquid_percent" style="width: 70px" value="15" />%
						</td>
						<td>
							на товары, которые были куплены в количестве <b>до</b>
						</td> 
						<td style="white-space:nowrap;">
							<input type="number" id="illiquid_qty" style="width: 70px" value="0" /> шт.
						</td>
						<td>
							за последние
						</td>
						<td style="white-space:nowrap;">
							<input type="number" id="illiquid_month" style="width: 70px" value="3" /> мес.
						</td>
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#00ad07;border-color: #00ad07; color:white;" onclick="createLiquidSpecial(0, 0)">СОЗДАТЬ</a>
						</td>
						<td style="white-space:nowrap;">
							<input type="date" id="illiquid-results-date" format="yyyy-mm-dd" value="<?php echo date('Y-m-d'); ?>" style="width:100px;" />
						</td>
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#00ad07; border-color:#00ad07; color:white;" onclick="getResults(0)"><i class="fa fa-refresh"></i> РЕЗУЛЬТАТ</a>
						</td>
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#f91c02; border-color:#f91c02; color:white;" onclick="createLiquidSpecial(0, 1)">ОЧИСТИТЬ</a>
						</td>
						<td style="white-space:nowrap;">
							<a class="button" style="background-color:#f91c02; border-color:#f91c02; color:white;" onclick="clearIlliquid()"><i class="fa fa-exclamation-triangle"></i> ВСЕ ЛИКВИДНО!</a>
						</td>
					</tr>
				</table>	
				<div id="setdiscountsresult"></div>
				<div id="getdiscountsresult"></div>

				<div id="explanation" style="display:none;">
					<span class="help"><i class="fa fa-info-circle"></i> 1. Скидка считается ОТ ОСНОВНОЙ ЦЕНЫ ТОВАРА и задается исключительно в ЕUR. Никакие переназначения цен не влияют на установку скидки. Иначе это невозможно сделать быстро.</span>

					<span class="help"><i class="fa fa-info-circle"></i> 2. Кнопки активации скидок работают только на товары, которые есть в наличии на складе выбранной страны в текущий момент. Товары, которых нет в наличии - при включенной логике "работы только со складом" не отображают на фронте ни свою цену, ни скидку.</span>

					<span class="help"><i class="fa fa-info-circle"></i> 3. Количество "от" и "до" работают с знаком => и <= соответственно. Таким образом, если нужно отобрать товары, которые вообще не продавались, нужно указать "до 0", и товары, которые продались хоть раз "от 1". Внимание! Логика нулевой продажи работает только для активации НЕЛИКВИДА</span>

						<span class="help" style="color:red"><i class="fa fa-info-circle"></i> 4. Скидки создаются и удаляются ТОЛЬКО для выбранного магазина с 26.05.2022</span>

						<span class="help"><i class="fa fa-info-circle"></i> 5. Кнопки активации создания скидок работают с учётом фильтрации по стране, также при создании скидок на неликвид - выбранные товары помечаются, как неликвидные для использования этих данных в интерфейсе установки цен маркетплейсов. Добавление скидок также автоматически очищает скидки с учётом фильтра, иначе возможны непредсказуемые данные на выводе.</span>
					</div>
				</div>			

				<table style="width: 100%;">
					<tr class="filter">
						<td>
							<p>Группировка по бренду</p>
							<select name="filter_by_brand">
								<?php if ($filter_by_brand) { ?>
									<option value="1" selected="selected">Группировать</option>
									<option value="0" >Не группировать</option>
								<?php } else { ?>
									<option value="1">Группировать</option>
									<option value="0"  selected="selected">Не группировать</option>
								<?php } ?>
							</select>							
						</td>

						<td>
							<p><i class="fa fa-briefcase"></i>&nbsp;&nbsp;Отобрать только бренд</p>
							<select name="filter_manufacturer_id">
								<option value="*">- Отображать все бренды -</option>
								<? foreach ($manufacturers as $manufacturer) { ?>
									<option value="<? echo $manufacturer['manufacturer_id'] ?>" <?php if ($manufacturer['manufacturer_id'] == $filter_manufacturer_id) { ?>selected="selected"<?php } ?>><? echo $manufacturer['name']; ?></option>
								<? } ?>
							</select>	
						</td>

						<td>
							<p><i class="fa fa-briefcase"></i>&nbsp;&nbsp;Страна / магазин</p>
							<select name="store_id">
								<? foreach ($stores as $store) { ?>
									<option value="<? echo $store['store_id'] ?>" <?php if ($store_id == $store['store_id']){?>selected="selected"<?php } ?>><? echo $store['name']; ?></option>
								<? } ?>
							</select>	
						</td>

						<td>
							<p><i class="fa fa-sort"></i>&nbsp;&nbsp;Сортировать</p>
							<select name="filter_sort">
								<option value="*">- Как-нибудь -</option>
								<option value="quantity-desc" <?php if ($filter_sort == 'quantity-desc') { ?>selected="selected"<?php } ?>>Больше на складе</option>
								<option value="quantity-asc" <?php if ($filter_sort == 'quantity-asc') { ?>selected="selected"<?php } ?>>Меньше на складе</option>
								<option value="name-asc" <?php if ($filter_sort == 'name-asc') { ?>selected="selected"<?php } ?>>Название А-Я (только по одному бренду)</option>
								<option value="name-desc"<?php if ($filter_sort == 'name-desc') { ?>selected="selected"<?php } ?>>Название Я-А (только по одному бренду)</option>
							</select>	
						</td>

						<td align="right">
							<p>	&#160;</p>
							<a onclick="filter();" class="button">Фильтр</a>
						</td>
					</tr>								
				</table>


				<div class="htabs-wrapper">
					<div style="margin-bottom:5px"></div>									
				</div>	

				<?php if ($this->user->getIsAV()) { ?>
					<div style="margin-bottom:5px; font-size:16px; font-weight:700; padding:3px 5px; border-radius:5px; border:1px dashed grey;">
						<i class="fa fa-info-circle"></i> Всего на складе <?php echo $total_items;?> единиц себестоимостью на сумму <?php echo $total_amount_eur; ?>, или <?php echo $total_amount_by_inner; ?> по внутреннему курсу, или <?php echo $total_amount_by_real; ?> по реальному курсу.

						<br /><small style="font-weight:400"><i class="fa fa-info-circle"></i> реальный курс означает сколько товар имеет фактической ценности и сколько денег в нацвалюте было потрачено на товар, внутренний курс - курс, по которому мы будем это продавать</small>
					</div>
				<?php } ?>

				<table class="list">
					<thead>							 
						<th></th>
						<th>Товар</th>
						<th style="width:80px;">Спрос</th>
						<th style="width:80px;">Количество</th>
						<th style="padding:0px 5px;" style="width:80px;">Едет</th>						
						<?php if ($this->config->get('config_amazon_profitability_in_stocks')) { ?>		
							<th style="width:80px;">Офферы</th>
							<th style="width:80px;">Поставщик</th>
							<th style="width:80px;">Закупка</th>
							<th style="width:80px;">Себестоимость</th>
							<th style="width:100px;">Продажная цена</th>
							<th style="width:80px;">Рентабельность</th>
						<?php } else { ?>
							<th style="width:80px;">Закупка</th>
							<th style="width:80px;">Себестоимость</th>
							<th style="width:80px;">МВЦ</th>
						<?php } ?>
						<th></th>
					</thead>		
					<? foreach ($stocks as $manufacturer => $products) { ?>
						<tr style="border-top:2px dashed grey;">							
							<td class="left" colspan="12" style="padding:10px; font-size:16px;">
								<b style="color:#7F00FF!important;"><? echo $manufacturer; ?></b>

								<?php if ($this->user->getIsAV()) { ?>
									<div style="display:inline-block; float:right; margin-bottom:5px; font-size:14px; font-weight:700; padding:3px 5px;color:#7F00FF!important;">
										<i class="fa fa-info-circle"></i> Всего на складе <?php echo $counts[$manufacturer]['total_items'];?> единиц себестоимостью на сумму <?php echo $counts[$manufacturer]['total_amount_in_eur']; ?>, или <?php echo $counts[$manufacturer]['total_amount_by_inner']; ?> по внутреннему курсу, или <?php echo $counts[$manufacturer]['total_amount_by_real']; ?> по реальному курсу.
									</div>
								<?php } ?>
							</td>
						</tr>														
						<? foreach ($products as $product) { ?>									
							<tr data-tr="tr-<? echo $product['product_id']; ?>">
								<td class="center" width="60">
									<img src="<? echo $product['image']; ?>" loading="lazy" width="50" height="50" />
								</td>
								<td class="left">
									<div style="font-weight: 700"><? echo $product['name']; ?></div>
									<div style="margin-top:5px;"><small><? echo $product['de_name']; ?></small></div>

									<div>
										<span style="font-size:11px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#4ea24e;"><?php echo $product['product_id']; ?></span>

										<?php if ($product['asin']) { ?>
											<span style="font-size:11px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#FF9900;"><?php echo $product['asin']; ?></span>	
										<?php } ?>

										<?php if ($product['ean']) { ?>
											<span style="font-size:11px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:grey;"><?php echo $product['ean']; ?></span>	
										<?php } ?>

										<?php if ($product['sku']) { ?>
											<span style="font-size:11px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#51A62D;"><?php echo $product['sku']; ?></span>	
										<?php } ?>	

										<?php if ($product['tnved']) { ?>
											<span style="font-size:11px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#7F00FF;"><?php echo $product['tnved']; ?></span>	
										<?php } ?>
									</div>
								</td>	
								<td class="center" nowrap style="whitespace:nowrap">
									<? echo $product['total_p_in_orders']; ?> / <? echo $product['total_q_in_orders']?$product['total_q_in_orders']:0;?>
								</td>
								<td class="center">
									<b><? echo $product[$stock_identifier]; ?></b>
								</td>
								<td class="center">
									<b><? echo $product[$stock_identifier . '_onway']; ?></b>
								</td>

								<?php if ($this->config->get('config_amazon_profitability_in_stocks')) { ?>	
									<td class="center" style="white-space:nowrap;">
										<?php if ($product['amazon_offers_type']) { ?>
											<span style="padding:4px 5px; background-color:#e16a5d; display:inline-block; text-decoration:none;font-size:16px; color:#FFF;"><? echo $product['amazon_offers_type']; ?></span>
										<?php } ?>
									</td>
									<td class="center" style="white-space:nowrap;">
										<?php if ($product['amazon_seller_quality']) { ?>
											<span style="padding:4px 5px; background-color:#51A62D; display:inline-block; text-decoration:none;font-size:16px; color:#FFF;"><? echo $product['amazon_seller_quality']; ?></span>
										<?php } ?>
									</td>				
									<td class="center" style="white-space:nowrap;">
										<div>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['amazon_best_price']; ?></span>
										</div>

										<div style="margin-top:5px;">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['amazon_best_price_national']; ?></span>
										</div>
									</td>
									<td class="center" style="white-space:nowrap;">
										<div>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['cost']; ?></span>
										</div>

										<div style="margin-top:5px;">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['cost_national']; ?></span>
										</div>
									</td>
									<td class="center" style="white-space:nowrap;">
										<?php if ($product['front_special']) { ?>
											<div>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF"><? echo $product['front_special']; ?></span>
											</div>

											<div style="margin-top:5px;">
												<span class="status_color" style="display:inline-block; font-size:10px; padding:3px 5px; background:#4ea24e; color:#FFF"><s><? echo $product['front_price']; ?></s></span>
											</div>
										<?php } else { ?>									
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['front_price']; ?></span>
										<?php } ?>
									</td>
									<td class="center" style="white-space:nowrap;">
										<span style="display:inline-block;padding:3px 5px; <?php if ((float)$product['profitability'] < 0) { ?>background:#ff5656;<?php } else { ?>background:#000;<?php } ?> color:#fff; white-space:nowrap;"><? echo $product['profitability']; ?> %</span>
									</td>
								<?php } else { ?>
									<td class="center" style="white-space:nowrap;">
										<div>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['actual_cost']; ?></span>
										</div>

										<div style="margin-top:5px;">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['actual_cost_national']; ?></span>
										</div>
									</td>

									<td class="center" style="white-space:nowrap;">
										<div>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['cost']; ?></span>
										</div>

										<div style="margin-top:5px;">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['cost_national']; ?></span>
										</div>
									</td>

									<td class="center" style="white-space:nowrap;">
										<div>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><? echo $product['min_sale_price']; ?></span>
										</div>

										<div style="margin-top:5px;">
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><? echo $product['min_sale_price_national']; ?></span>
										</div>
									</td>
								<?php } ?>

								<td class="center" nowrap style="whitespace:nowrap">
									<a class="button" href="<? echo $this->url->link('catalog/product/update', 'product_id='.$product['product_id'].'&token=' . $this->session->data['token'], 'SSL') ?>" style="text-decoration:none;" target="_blank" /><i class="fa fa-edit"></i></a>&nbsp;&nbsp;<a class="button" href="<? echo HTTPS_CATALOG . 'index.php?route=product/product&product_id='.$product['product_id']; ?>" style="text-decoration:none;" target="_blank" /><i class="fa fa-eye"></i></a>	
								</td>
							</tr>	
						<?php } ?>
					<?php } ?>
				</table>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				$('#update_stocks').click(function(event) {
					event.preventDefault(); 
					$.ajax({
						url: 'index.php?route=api/info1c/getStocksFrom1C&token=<?php echo $token; ?>',
						dataType: 'text',
						beforeSend : function(){
							$('#sync_results').html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>');
						},
						success: function(text) {
							$('#sync_results').addClass('success').html('Синхронизация успешно закончена. Обновите страницу.');
						},
						error : function(e){
							$('#sync_results').addClass('error').html(e);
						}
					});
					return false;
				});							
			})
		</script>	

		<script>		
			function getResults(liquid){
				if (liquid){
					var date_added = $('#liquid-results-date').val();
				} else {
					var date_added = $('#illiquid-results-date').val();
				}

				$.ajax({
					url: 'index.php?route=catalog/stocks/getProductsWithSettledSpecialFromStocks&hello=world&token=<?php echo $token; ?>',
					type: 'POST',
					data:{
						store_id: 		$('select[name=\'store_id\']').children("option:selected").val(),
						liquid: 		liquid,
						date_added: 	date_added
					},
					dataType: 'html',
					beforeSend : function(){
						$('#getdiscountsresult').html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i> чистим...');
					},
					success: function(html) {
						$('#getdiscountsresult').html(html);
					},
					error : function(e){
						$('#getdiscountsresult').addClass('error').html(e);
					}
				});
			}

			function clearIlliquid(){
				$.ajax({
					url: 'index.php?route=catalog/stocks/clearAllSpecials&hello=world&token=<?php echo $token; ?>',
					type: 'GET',
					dataType: 'text',
					beforeSend : function(){
						$('#setdiscountsresult').html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i> чистим...');
					},
					success: function(text) {
						$('#setdiscountsresult').addClass('success').html('Теперь все товары ликвидны');
					},
					error : function(e){
						$('#sync_results').addClass('error').html(e);
					}
				});						
			}

			function clearAllSpecials(){
				$.ajax({
					url: 'index.php?route=catalog/stocks/clearAllSpecials&hello=world&token=<?php echo $token; ?>',
					type: 'GET',
					dataType: 'text',
					beforeSend : function(){
						$('#setdiscountsresult').html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i> чистим...');
					},
					success: function(text) {
						$('#setdiscountsresult').addClass('success').html('Теперь в магазине нет скидок! PROFIT!');
					},
					error : function(e){
						$('#sync_results').addClass('error').html(e);
					}
				});						
			}			

			function createLiquidSpecial(liquid, only_delete){

				if (liquid){
					var liquid_qty 		= $('#liquid_qty').val();
					var liquid_percent 	= $('#liquid_percent').val();
					var liquid_month 	= $('#liquid_month').val();
				} else {
					var liquid_qty 		= $('#illiquid_qty').val();
					var liquid_percent 	= $('#illiquid_percent').val();
					var liquid_month 	= $('#illiquid_month').val();
				}

				$.ajax({
					url: 'index.php?route=catalog/stocks/setLiquidSpecial&hello=world&token=<?php echo $token; ?>',
					type: 'POST',
					data:{
						store_id: 		$('select[name=\'store_id\']').children("option:selected").val(),
						liquid_qty: 	liquid_qty,
						liquid_percent: liquid_percent,
						liquid_month: 	liquid_month,
						liquid: 		liquid,
						only_delete: 	only_delete,
					},
					dataType: 'text',
					beforeSend : function(){
						$('#setdiscountsresult').html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i> добавляем...');
					},
					success: function(text) {
						if (only_delete){
							$('#setdiscountsresult').addClass('success').html('Успешно удалили ' + text + ' скидок');
						} else {
							$('#setdiscountsresult').addClass('success').html('Успешно добавили ' + text + ' скидок');								
						}
					},
					error : function(e){
						$('#sync_results').addClass('error').html(e);
					}
				});						

				return false;
			}
		</script>

		<script type="text/javascript">
			function filter() {
				url = 'index.php?route=catalog/stocks&token=<?php echo $token; ?>';

				var filter_manufacturer_id = $('select[name=\'filter_manufacturer_id\']').children("option:selected").val();

				if (filter_manufacturer_id != '*') {
					url += '&filter_manufacturer_id=' + encodeURIComponent(filter_manufacturer_id);
				}

				var store_id = $('select[name=\'store_id\']').children("option:selected").val();

				if (store_id != '*') {
					url += '&store_id=' + encodeURIComponent(store_id);
				}

				var filter_by_brand = $('select[name=\'filter_by_brand\']').children("option:selected").val();

				if (filter_by_brand != '*') {
					url += '&filter_by_brand=' + encodeURIComponent(filter_by_brand);
				}

				var filter_sort = $('select[name=\'filter_sort\']').children("option:selected").val();

				if (filter_sort != '*') {
					url += '&filter_sort=' + encodeURIComponent(filter_sort);
				}

				location = url;
			}

			$(document).ready(function() {
				$('#date').datepicker({dateFormat: 'yy-mm-dd'});
				$('#datetime').datepicker({dateFormat: 'yy-mm-dd h:i:s'});
			});
		</script>
<?php echo $footer; ?> 																		