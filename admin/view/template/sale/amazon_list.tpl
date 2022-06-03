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
	<style>
		.status_color2 {display:inline-block;  background:#f990c3; color:#FFF; padding: 5px 3px;}
		.status_color2.problem {background:#cf4a61; color: #FFF; }
		.status_color2.return {background:#ffaa56; color: #FFF;}
		.status_color2.arriving {background:#d4ffaa; color: #696969; }
		.status_color2.delivered {background:#7fff00; color: #696969; }
		.status_color2.expected {background:#ffd4aa;; color: #696969; }
	</style>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/language.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><i class="fa fa-info" aria-hidden="true" style="color:#cf4a61;"></i>&nbsp;Последняя синхронизация <? echo $amazon_last_sync; ?></div>
		</div>
		<div class="content">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 100%;">
					<tr class="filter">
						<td>
							<p>Amazon Order ID</p>
							<input type="hidden" id="filter_amazon_id" name="filter_amazon_id" value="<?php echo $filter_amazon_id; ?>" />
							<input title="Номер заказа в Amazon" type="text" name="filter_amazon_id_autocomplete" value="<?php echo $filter_amazon_id; ?>" size="35" style="text-align: left;" />
							<span onclick="$('#filter_amazon_id').val(''); $('#filter_amazon_id_autocomplete').val(''); filter();" style="border-bottom:1px dashed #999; cursor:pointer;">очистить</span>
						</td>
						
						<td><p>Заказ покупателя</p>
							<input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="12" style="text-align: left;" />
						</td>
						
						<td>
							<p>ASIN</p>
							<input type="text" name="filter_asin" value="<?php echo $filter_asin; ?>" size="35" style="text-align: left;" />
						</td>	
						
						<td style="color: #999;">
							<p>Оформлен от</p>
							<input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" class="date-new" /> до <input type="text" name="filter_date_added_to" value="<?php echo $filter_date_added_to; ?>" size="12" class="date-new" /> 
						</td>
						
					</tr>
					<tr class="filter">
						<td colspan="2" style="color: #999;">			
							<input type="hidden" id="filter_product_id" name="filter_product_id" value="<?php echo $filter_product_id; ?>" />
							<p>Товар</p>
							<input type="text" id="filter_product_product" name="product" value="<?php echo $filtered_product['name']; ?>" style="width:500px;" />
							<b>ID</b>&nbsp;<span id="filter_product_id_id"><?php echo $filtered_product['product_id']; ?></span>&nbsp;&nbsp;
							<b>SKU</b>&nbsp;<span id="filter_product_model"><?php echo $filtered_product['model']; ?></span>&nbsp;&nbsp;
							<b>EAN</b>&nbsp;<span id="filter_product_ean"><?php echo $filtered_product['ean']; ?></span>&nbsp;&nbsp;
							<b>ASIN</b>&nbsp;<span id="filter_product_asin"><?php echo $filtered_product['asin']; ?></span>&nbsp;&nbsp;
							<span onclick="$('#filter_product_product').val(''); $('#filter_product_id').val(''); filter();" style="border-bottom:1px dashed #999; cursor:pointer;">очистить</span>
						</td>
						
						<td colspan="1" style="color: #999;">			
							<input type="hidden" id="filter_supplier_id" name="filter_supplier_id" value="<?php echo $filter_supplier_id; ?>" />
							<p>Поставщик</p>
							<input type="text" id="filter_supplier_supplier" name="filter_supplier_supplier" value="<?php echo $filtered_supplier['name']; ?>" style="width:200px;" />							
							<span onclick="$('#filter_supplier_supplier').val(''); $('#filter_supplier_id').val(''); filter();" style="border-bottom:1px dashed #999; cursor:pointer;">очистить</span>
						</td>
						
						<td colspan="1" align="right"><a onclick="filter();" class="button">Фильтр</a></td>
					</tr>
				</table>
				<div class="filter_bord"></div>
				<table style="width: 100%;">
					<tr class="filter">
						<td style="color: #999;">							
							<input type="checkbox" class="checkbox" name="filter_is_problem" <? if ($filter_is_problem) { ?>checked="checked"<? } ?> id="filter_is_problem" />
							<label for="filter_is_problem"><i class="fa fa-warning" aria-hidden="true"></i>&nbsp;Проблемный</label>
						</td>
						<td style="color: #999;">
							<input type="checkbox" class="checkbox" name="filter_is_dispatched" <? if ($filter_is_dispatched) { ?>checked="checked"<? } ?>  id="filter_is_dispatched"/>
							<label for="filter_is_dispatched"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Отгружен</label>
						</td>
						<td style="color: #999;">
							<input type="checkbox" class="checkbox" name="filter_is_return" <? if ($filter_is_return) { ?>checked="checked"<? } ?> id="filter_is_return" />
							<label for="filter_is_return"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;Возврат</label>
						</td>
						<td style="color: #999;">
							<p><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Прибывает</p>
							<input type="text" name="filter_date_arriving_from" value="<?php echo $filter_date_arriving_from; ?>" size="12" class="date-new" /> 
							до <input type="text" name="filter_date_arriving_to" value="<?php echo $filter_date_arriving_to; ?>" size="12" class="date-new" /> 
						</td>
						<td style="color: #999;">
							<p><i class="fa fa-spinner" aria-hidden="true"></i>&nbsp;Ожидается к</p>
							<input type="text" name="filter_date_expected_from" value="<?php echo $filter_date_expected_from; ?>" size="12" class="date-new" /> 
							до <input type="text" name="filter_date_expected_to" value="<?php echo $filter_date_expected_to; ?>" size="12" class="date-new" /> 
						</td>
						<td style="color: #999;">
							<p><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Был доставлен</p>
							<input type="text" name="filter_date_delivered_from" value="<?php echo $filter_date_delivered_from; ?>" size="12" class="date-new" /> 
							до <input type="text" name="filter_date_delivered_to" value="<?php echo $filter_date_delivered_to; ?>" size="12" class="date-new" /> 
						</td>
					</tr>
				</table>
				<div class="filter_bord"></div>
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left" style="width:350px;">Amazon ID</td>
							<td class="left">Дата</td>
							<td class="left">Итоги</td>
							<td class="left">Купон</td>
							<td class="left">Поставок</td>
							<td class="left"></td>
							<td class="right"></td>
							<td class="right">Действия</td>
						</tr>
					</thead>
					<tbody>
						<?php if ($orders) { ?>
							<?php foreach ($orders as $order) { ?>
								<tr style="background:#faf9f1">
									<td style="text-align: center;"><?php if ($order['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
									<?php } ?>
									</td>
									<td class="left">
										<? if ($order['cancelled'] || $order['is_problem']) { ?>
											<i class="fa fa-warning" aria-hidden="true"  style="color:#cf4a61;"></i>&nbsp;<b style="color:#cf4a61;"><?php echo $order['amazon_id']; ?></b>
											<? } else { ?>
											<b><?php echo $order['amazon_id']; ?></b>
										<? } ?>										
									</td>
									<td class="center"><b><?php echo $order['date_added']; ?>&nbsp;<a class="button" href="<? echo $order['filter_date_added'] ?>"><i class="fa fa-filter" aria-hidden="true"></i></a></td>
										<td class="center" style="word-wrap:none;"><b><?php echo $order['total']; ?> €</b></td>			  
										<td class="center" style="word-wrap:none;"><b><?php echo $order['gift_card']; ?> €</b></td>
										<td class="left"><b><? echo count($order['deliveries']); ?></b></td>
										<td class="left"></td>
										<td class="center"></td>
										<td class="right"></td>						
									</tr>			
									<? if ($order['deliveries']) { ?>
										<tr>
											<td>
											</td>
											<td colspan="8">
												<table class="list" style="width:100%">
													<? foreach ($order['deliveries'] as $i => $delivery) { ?>
														<tr>
															<td class="left" colspan="10">
																<b  onclick="$(this).load('index.php?route=sale/amazon/getStatusInfoAjax&token=<? echo $token ?>&status=<? echo urlencode($delivery['delivery_status']); ?>')">Поставка № <? echo $i; ?></b> : 
																<? if ($order['cancelled']) { ?>
																	<span class="status_color2 problem"><i class="fa fa-warning" aria-hidden="true"></i>&nbsp;<? echo $delivery['delivery_status_ru']; ?></span>
																	<? } elseif ($delivery['is_problem']) { ?>
																	<span class="status_color2 problem"><i class="fa fa-warning" aria-hidden="true"></i>&nbsp;<? echo $delivery['delivery_status_ru']; ?></span>
																	<? } elseif ($delivery['is_return']) { ?>
																	<span class="status_color2 return"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;<? echo $delivery['delivery_status_ru']; ?></span>
																	<? } elseif ($delivery['is_arriving']) { ?>
																	<span class="status_color2 arriving"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;<? echo $delivery['delivery_status_ru']; ?></span>
																	<? } elseif ($delivery['is_expected']) { ?>
																	<span class="status_color2 expected"><i class="fa fa-spinner" aria-hidden="true"></i>&nbsp;<? echo $delivery['delivery_status_ru']; ?></span>
																	<? } elseif ($delivery['is_delivered']) { ?>
																	<span class="status_color2 delivered"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;<? echo $delivery['delivery_status_ru']; ?></span>
																	<? } elseif ($delivery['is_dispatched']) { ?>
																	<span class="status_color2 arriving"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;<? echo $delivery['delivery_status_ru']; ?></span>
																	<? } else { ?>
																	<span class="status_color2"><? echo $delivery['delivery_status_ru']; ?></span>																
																<? } ?>	
																<? if (!$order['cancelled']) { ?>
																	&nbsp;&nbsp;&nbsp;<span class="status_color2" style=""><? echo $delivery['delivery_status']; ?></span>	
																<? } ?>
															</td>
														</tr>
														<? foreach ($delivery['products'] as $product) { ?>
															<tr>
																<td class="left" width="50px"><img src="<? echo $product['image']; ?>" /></td>
																<td class="left" width="50px">
																	<? if ($product['real_products']) { ?>
																		<? foreach ($product['real_products'] as $real_product) { ?>
																			<img src="<? echo $real_product['aimage']; ?>" />
																		<? } ?>
																	<? } ?>																	
																</td>
																<td class="center" style="width:100px;"><b><? echo $product['asin']; ?></b>&nbsp;<a class="button" href="<? echo $product['filter_asin'] ?>"><i class="fa fa-filter" aria-hidden="true"></i></a></td>
																<td class="left" style="width:30px;"><? echo $product['quantity']; ?></td>
																<td class="left" style="width:80px;"><? echo $product['price']; ?> €</td>
																<td class="left" style="width:80px;"><? echo $product['total']; ?> €</td>
																<td class="left" style="width:150px; font-size:11px;">
																	<? echo $product['supplier']; ?>&nbsp;<a class="button" href="<? echo $product['filter_supplier_id'] ?>"><i class="fa fa-filter" aria-hidden="true"></i></a>
																	&nbsp;<a class="button" href="<? echo $product['supplier_editlink'] ?>" target="_blank"><i class="fa fa-edit" aria-hidden="true"></i></a>
																</td>
																<td class="left" style="width:210px;">
																	<? if ($product['orders']) { ?>
																		<? foreach ($product['orders'] as $_order) { ?>																			
																			<span class="" style="padding: 5px 3px;display: block;">
																				<b><? echo $_order['order_id'] ?></b>&nbsp;<a class="button" href="<? echo $_order['adminlink'] ?>" target="_blank"><i class="fa fa-edit" aria-hidden="true"></i></a>, <? echo $_order['status'] ?>, <? echo $_order['quantity']; ?> шт.
																			</span>
																			
																		<? } ?>
																	<? } ?>
																</td>
																<td class="center" style="width:150px;">
																	<? if ($product['real_products']) { ?>																		
																		<? foreach ($product['real_products'] as $real_product) { ?>
																			<br />
																			<? if (count($product['real_products'])>1) { ?>
																				<i class="fa fa-warning" aria-hidden="true" style="color:#cf4a61;"></i>&nbsp;
																			<? } ?>
																			<b><? echo $real_product['model']; ?></b>
																		<? } ?>
																		<? } else { ?>
																		<b><span style="color:#cf4a61;"><i class="fa fa-warning" aria-hidden="true"></i>&nbsp;ASIN нет в базе</span></b>
																	<? } ?>
																</td>
																<td class="left"><img src="view/images/amazonicon20.png" />&nbsp;<? echo $product['name']; ?>
																	<? if ($product['real_products']) { ?>																		
																		<? foreach ($product['real_products'] as $real_product) { ?>
																			<br />
																			<? if (count($product['real_products'])>1) { ?>
																				<i class="fa fa-warning" aria-hidden="true" style="color:#cf4a61;"></i>&nbsp;
																			<? } ?>
																			<img src="view/images/icon20.png" />&nbsp;<? echo $real_product['name']; ?>&nbsp;
																			<a class="button" href="<? echo $real_product['sitelink'] ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;
																			<a class="button" href="<? echo $real_product['adminlink'] ?>" target="_blank"><i class="fa fa-edit" aria-hidden="true"></i></a>
																		<? } ?>
																		<? } else { ?>
																		<br /><span style="color:#cf4a61;"><i class="fa fa-warning" aria-hidden="true"></i>&nbsp;ASIN нет в базе</span>
																	<? } ?>
																</td>
																
															</tr>
														</tr>
													<? } ?>
												<? } ?>
											</table>
										</td>
									</tr>
								<? } ?>
								
								
							<?php } ?>
							
							
							
							
							<?php } else { ?>
							<tr>
								<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});
//--></script> 
<script type="text/javascript"><!--
	$('input[name=\'filter_amazon_id_autocomplete\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/amazon/autocomplete&token=<?php echo $token; ?>&filter_amazon_id=' + encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							value : item.amazon_id,
							amazon_id: item.amazon_id,
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'filter_amazon_id\']').attr('value', ui.item.amazon_id);
			$('input[name=\'filter_amazon_id_autocomplete\']').attr('value', ui.item.amazon_id);
			filter();
			return false;
		},
		focus: function(event, ui) {
		}
	});
//--></script>
<script type="text/javascript"><!--
	$('input[name=\'filter_supplier_supplier\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/supplier/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							value : item.supplier_name,
							supplier_name: item.supplier_name,
							supplier_id: item.supplier_id,
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'filter_supplier_supplier\']').attr('value', ui.item.value);
			$('input[name=\'filter_supplier_id\']').attr('value', ui.item.supplier_id);
			filter();
			return false;
		},
		focus: function(event, ui) {
		}
	});
//--></script>
<script type="text/javascript"><!--
	$('input[name=\'product\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&only_enabled=1&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							value: item.name,
							product_id: item.product_id,
							model: item.model,
							ean:   item.ean,
							asin:   item.asin,
							option:item.option,
							price: item.price,
							image: item.image
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product\']').attr('value', ui.item.value);
			$('input[name=\'filter_product_id\']').attr('value', ui.item.product_id);
			filter();
			return false;
		},
		focus: function(event, ui) {
			$('input[name=\'product\']').attr('value', ui.item.value);
			$('#filter_product_id_id').text(ui.item.product_id);
			$('#filter_product_model').text(ui.item.model);
			$('#filter_product_ean').text(ui.item.ean);
			$('#filter_product_asin').text(ui.item.asin);
		}
	});
//--></script>
<script>
	function filter() {
		url = 'index.php?route=sale/amazon&token=<?php echo $token; ?>';
		
		<? foreach ($filters as $key => $value) { ?>
			
			<? if ($key != 'sort' && $key != 'order' && $key != 'page') { ?>				
				<? if ($key == 'filter_is_return' || $key == 'filter_is_problem' || $key == 'filter_is_dispatched') { ?>
					
					var <? echo $key; ?> = $('input[name=\'<? echo $key; ?>\']:checked').val();
					
					if (<? echo $key; ?> !== undefined) {
						url += '&<? echo $key; ?>=1';
					}
					
					<? } else { ?>
					
					var <? echo $key; ?> = $('input[name=\'<? echo $key; ?>\']').attr('value');
					
					if (<? echo $key; ?>) {
						url += '&<? echo $key; ?>=' + encodeURIComponent(<? echo $key; ?>);
					}
				<? } ?>
				
			<? } ?>
			
		<? } ?>
		
		location = url;
	}
	
</script>
<?php echo $footer; ?>