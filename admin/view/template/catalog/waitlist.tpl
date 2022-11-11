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
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a style="display:none;" id="neworder_button" onclick="$('#form').attr('action', '<?php echo $createneworder; ?>'); $('#form').submit();" class="button">Новый заказ!</a>
			<a onclick="$('form').submit();" class="button">Удалить из листа</a><a onclick="filter();" class="button">Фильтр</a></div>
		</div>
		<div class="content">	
			<style>
				table.filter_tbl tr td{border:0px;}
			</style>
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>						
							<td></td>
							<td width="100px" style="text-align: center; width:100px;">Наличие</td>
							<td width="100px" style="text-align: center;">Заявка</td>
							<td class="center" style="width:60px;"></td>
							<td class="left"><?php if ($sort == 'pd.name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Ожидаемый товар</a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>">Ожидаемый товар</a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'p.model') { ?>
								<a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>">Артикул</a>
								<?php } else { ?>
								<a href="<?php echo $sort_model; ?>">Артикул</a>
							<?php } ?></td>
							<td>Поставщик</td>
							<td class="center">Заказ</td>
							<td class="center">Покупатель</td>
							<td class="center">Дата</td>			  
							<td class="center">Кол-во</td>	
							<td class="left"><?php if ($sort == 'p.price') { ?>
								<a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>">Цена, EUR</a>
								<?php } else { ?>
								<a href="<?php echo $sort_price; ?>">Цена, EUR</a>
							<?php } ?></td>
							<td class="left">Цена</td>             
						</tr>
					</thead>
					<tbody>
						<script>
							function count_checkboxes(){
								var $b = $('input[name*=\'selected\']');
								if ($b.filter(':checked').length > 0){
									$('#neworder_button').show();
									} else {
									$('#neworder_button').hide();
								}
							}
						</script>
						<tr class="filter">
							<td><input type="checkbox" name="filter_supplier_has" <? if (isset($filter_supplier_has) && $filter_supplier_has) { ?>checked='checked'<? } ?>></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td width="300px">
								<table border='0' class="filter_tbl">
									<tr><td style="border:0px;">Название:</td>
										<td style="border:0px;"><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" style="width:200px;" /></td>
									</tr>
									<tr><td style="border:0px;">ID:</td>
										<td style="border:0px;"><input type="text" name="filter_product_id" value="<?php echo $filter_product_id; ?>" style="width:200px;" />
										</td></tr>
								</table>
							</td>
							<td><input style="width: 70%;" type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>
							<td></td>
							<td><input style="width: 70%;" type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" /></td>
							<td>
								<table border='0' class="filter_tbl">
									<tr><td style="border:0px;">Имя:</td>
										<td style="border:0px;"><input type="text" name="filter_customer_name" value="" style="width:120px;" /></td>
									</tr>
									<tr><td style="border:0px;">ID:</td>
										<td style="border:0px;"><input type="text" name="filter_customer_id" value="<?php echo $filter_customer_id; ?>" style="width:120px;" />
										</td>
									</tr>
								</table>			  			
								<td></td><td></td>					  
								<td align="left"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="5"/></td>
								<td></td>
							</tr>
							<?php if ($products) { ?>
								<? $previous_order_id = 0; ?>
								<?php foreach ($products as $product) { ?>
									<? if ($previous_order_id != $product['order_id']) {  $previous_order_id = $product['order_id']; ?>
										<tr>
											<td colspan="13" class="boarder_style" style="height:0px;border-top:2px solid #1f4962;"></td>
										</tr>
									<? } ?>
									<tr>
										<td style="text-align: center;"><?php if ($product['selected']) { ?>
											<input onclick="count_checkboxes()" type="checkbox" name="selected[]" value="<?php echo $product['order_product_id']; ?>" checked="checked" />
											<?php } else { ?>
											<input onclick="count_checkboxes()" type="checkbox" name="selected[]" value="<?php echo $product['order_product_id']; ?>" />
										<?php } ?></td>
										
										<td><?php echo $product['order_product_id']; ?></td>
										
										<? if ($product['on_stock']) { ?>
											<td class="center supplier_has" data-order-product-id="<?php echo $product['order_product_id']; ?>" data-suppler-has='1'>
											
											<span class="status_color_padding change_supplier_has" width="100px" style="background:#BCF5BC; color:darkgreen; cursor:pointer;" data-product-id='<? echo $product['product_id']; ?>' data-order-product-id='<?php echo $product['order_product_id']; ?>'>На складе</span>
												
												<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" class="ask_buyers" data-product-id='<? echo $product['product_id']; ?>' data-order-product-id='<?php echo $product['order_product_id']; ?>'>нал?</a>&nbsp;<i title="Спросить бренд-менеджеров по наличию." class="fa fa-user-o  ktooltip_hover" aria-hidden="true"></i>
											</td>
											<? } elseif ($product['supplier_has']) { ?>
												
												<td class="center supplier_has" data-order-product-id="<?php echo $product['order_product_id']; ?>" data-suppler-has='1'>
													
											<span class="status_color_padding change_supplier_has" width="100px" style="background:#FFEAA8; color:darkgreen; cursor:pointer;" data-product-id='<? echo $product['product_id']; ?>' data-order-product-id='<?php echo $product['order_product_id']; ?>'>Поставщик</span>
												
												<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" class="ask_buyers" data-product-id='<? echo $product['product_id']; ?>' data-order-product-id='<?php echo $product['order_product_id']; ?>'>нал?</a>&nbsp;<i title="Спросить бренд-менеджеров по наличию." class="fa fa-user-o  ktooltip_hover" aria-hidden="true"></i>
											</td>
												
											
											<?php } else { ?>
											<td class="center supplier_has" data-order-product-id="<?php echo $product['order_product_id']; ?>" data-suppler-has='0'>
											
											<span class="status_color_padding change_supplier_has" width="100px" style="background:#cf4a61; color:#fff; cursor:pointer;" data-product-id='<? echo $product['product_id']; ?>' data-order-product-id='<?php echo $product['order_product_id']; ?>'>Нет в нал</span>
												
												<a style="padding-bottom:4px;margin-top:4px;display:inline-block; text-decoration:none; border-bottom:1px dashed;" class="ask_buyers" data-product-id='<? echo $product['product_id']; ?>' data-order-product-id='<?php echo $product['order_product_id']; ?>'>нал?</a>&nbsp;<i title="Спросить бренд-менеджеров по наличию." class="fa fa-user-o  ktooltip_hover" aria-hidden="true"></i>
											</td>
										<? } ?>
										
										<? if ($product['is_prewaitlist']) { ?>
											<td class="center is_prewaitlist" data-order-product-id="<?php echo $product['order_product_id']; ?>" data-is-prewaitlist="1">
												<span class="status_color_padding" style="background:#cf4a61; color:#FFF; cursor:pointer;">Заявка</span>
											</td>
											<? } else { ?>
											<td class="center is_prewaitlist" data-order-product-id="<?php echo $product['order_product_id']; ?>" data-is-prewaitlist="0">
												<span class="status_color_padding" style="background:#7fff00; color:#696969; cursor:pointer;">Готовы ждать</span>
											</td>
										<? } ?>
										
										<td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
										
										
										<td class="left"><a href="<? echo $product['admin_product_url']; ?>" target="_blank"><b><?php echo $product['name']; ?></b></a> &nbsp;&nbsp;<a href="<? echo $product['product_filter_url']; ?>">фильтр</a>
											<br /><br />
											<span style="font-size:11px;">Всего в листе: <b><? echo $product['total_quantity']; ?></b> шт.: <? foreach ($product['orders'] as $order) { ?>
												<? if ($order['order_id'] == $product['order_id']) { ?>
													<span style="padding:3px; background:rgba(133, 178, 0, 0.47); color:black;">
														<? if ($order['order_filter_href']) {?>
															<a style="color:black;" href="<? echo $order['order_filter_href']; ?>"><? echo $order['order_id'] ?></a> (<? echo $order['quantity']; ?>)
															<? } else { ?>
															<? echo $order['order_id'] ?> (<? echo $order['quantity']; ?>)
														<? } ?>
													</span>
													<? } else { ?>
													<? if ($order['order_filter_href']) {?>
														<a href="<? echo $order['order_filter_href']; ?>"><? echo $order['order_id'] ?></a> (<? echo $order['quantity']; ?>)
														<? } else { ?>
														<? echo $order['order_id'] ?> (<? echo $order['quantity']; ?>)
													<? } ?>
												<? } ?>
												&nbsp;&nbsp;
											<? } ?>
											</span>
										</td>
										<td class="left" style="wrap-whitespace:none;">
											<div>АРТ: <?php echo $product['model']; ?></div>
											<? if ($product['ean']) { ?>
												<div><span style="display:inline-block; padding:3px; color:#FFF; background-color:grey;">EAN: <?php echo $product['ean']; ?></span></div>
											<? } ?>
											<? if ($product['asin']) { ?>
												<div>ASIN: <?php echo $product['asin']; ?></div>
											<? } ?>
											
										</td>
										<td>
											<div>
												<? if ($product['asin']) { ?>
													<a style="font-size:10px;" href="https://www.amazon.de/gp/offer-listing/<? echo $product['asin']; ?>/ref=olp_f_primeEligible&f_primeEligible=true&f_new=true" title="" target="_blank"><i class="fa fa-amazon"></i> offer-listing</a>&nbsp;
													<a style="font-size:10px;" href="https://www.amazon.de/dp/<? echo $product['asin']; ?>" title="" target="_blank"><i class="fa fa-amazon"></i> direct</a>
												<? } ?>
											</div>
											<div>
												<? foreach (explode(PHP_EOL, $product['source']) as $source_line) { ?>
													<? $_nn = str_replace('www.', '', parse_url($source_line, PHP_URL_HOST)); ?>
													<a style="font-size:10px;" href="<? echo $source_line; ?>" title="<? echo $source_line; ?>" target="_blank"><? echo $_nn; ?></a>
												<? } ?>
											</div>
											<div style="margin-top:3px;">
												<span  id='get_prices_modal_<? echo $product['product_id'] ?>' style="display: inline-block; cursor:pointer; border-bottom:1px dashed black;">проверить</span>
												<span id="prices_preview_<? echo $product['product_id'] ?>"></span>
												
												<script>
													$('#get_prices_modal_<? echo $product['product_id'] ?>').click(function(){
														$.ajax({
															url:  'index.php?route=catalog/product/getOptimalPrice&product_id=<? echo $product['product_id'] ?>&token=<? echo $token ?>',
															dataType: 'html',
															beforeSend : function(){
																$('#prices_preview_<? echo $product['product_id'] ?>').html('');
																$('#prices_preview_<? echo $product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
															},
															success : function(html){
																$('#prices_preview_<? echo $product['product_id'] ?>').html('');
																$('#prices_preview_<? echo $product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Цены поставщика по товару <? echo $product['product_id'] ?>'});				
															}
														})	
													});	
												</script>
											</div>	
											
										</td>
										<td class="right">
											<? if ($product['order_id']) { ?>
												<span class="status_color_padding" onclick="$('#order_info_<?php echo $product['order_id']; ?>').toggle();" style="background:#BCF5BC; color:darkgreen; cursor:pointer;">
													<i class="fa fa-info-circle"></i> Заказ <?php echo $product['order_id']; ?>
												</span>
												<div class="order_info" id="order_info_<?php echo $product['order_id']; ?>" style="position:absolute; display:none;border:1px solid black; padding:10px; background:white;">
													<table class="list">
														<tr>
															<td colspan="2" style="text-align:center;"><b>Информация о заказе:</b></td>
														</tr>
														<tr>
															<td class="left">Имя:</td>
															<td class="right"><? echo $product['order']['firstname'].' '.$product['order']['lastname']; ?></td>
														</tr>
														<tr>
															<td class="left">Телефон:</td>
															<td class="right"><? echo $product['order']['telephone']; ?><span class='click2call' data-phone="<? echo $product['order']['telephone']; ?>"></span></td>
														</tr>
														<tr>
															<td class="left">Город:</td>
															<td class="right"><? echo $product['order']['shipping_city']; ?></td>
														</tr>
														<tr>
															<td class="left">Магазин:</td>
															<td class="right"><? echo $product['order']['store_url']; ?></td>
														</tr>
														<tr>
															<td class="left">Статус:</td>
															<td class="right"><? echo $product['order']['order_status_id']; ?></td>
														</tr>
														<tr>
															<td class="left">Оформлен в:</td>
															<td class="right"><? echo $product['order']['date_added']; ?></td>
														</tr>
														<tr>
															<td class="left">Сумма:</td>
															<td class="right"><? echo (int)$product['order']['total_national'].' '.$product['order']['currency_code']; ?></td>
														</tr>
													</table>								
												</div> <br />			  
												<a href="<? echo $product['admin_order_url']; ?>" style="font-size:10px;">откр. в адм.</a>&nbsp;<a href="<? echo $product['admin_filter_url']; ?>" style="font-size:10px;">фильтр</a>
												<? } else { ?>											
												<span class="status_color_padding" style="background:#BCF5BC; color:darkgreen;"><i class="fa fa-info-circle"></i> Заявка</span>
											<? } ?>
										</td>
										<td class="right">
											<a href="<? echo $product['admin_customer_url']; ?>" target="_blank"><?php echo $product['customer']['firstname']; ?> <?php echo $product['customer']['lastname']; ?></a><br /><?php echo $product['customer']['telephone']; ?><span class='click2call' data-phone="<?php echo $product['customer']['telephone']; ?>"></span>
											<br />
											
											<?php if ($product['admin_customer_filter_url']) { ?>
												<a href="<? echo $product['admin_customer_filter_url']; ?>" style="font-size:10px;">все ож. товары</a><br />
											<? } ?>
											
											<?php if ($product['customer_total_products'] && $product['customer']['customer_id']) { ?>
												<span style="font-size:10px;"><? echo $product['customer_total_products']; ?> тов. в <? echo $product['customer_total_orders']; ?> зак.</span>
											<? } ?>
											
										</td>
										<td class="right">
											<?php echo date('d.m.Y', strtotime($product['order']['date_added'])); ?><br />
											<?php echo date('H:i:s', strtotime($product['order']['date_added'])); ?>
										</td>
										<td class="right"><?php echo $product['quantity']; ?></td>
										<td class="left" style="text-align:center;"><?php if ($product['special']) { ?>
											<span style="text-decoration: line-through;"><?php echo $product['price']; ?> / <?php echo $product['price_national']; ?></span><br/>
											<span style="color: #b00;"><?php echo $product['special']; ?> / <?php echo $product['special_national']; ?></span>
											<?php } else { ?>
											<?php echo $product['price']; ?> / <?php echo $product['price_national']; ?>																					
										<?php } ?>
										&nbsp;<i class="fa fa-info ktooltip_click" title="Текущая цена на сайте"></i>
										</td>
										<td class="left">
											<?php echo $product['price_in_order']; ?>&nbsp;<i class="fa fa-info ktooltip_click" title="Цена в заказе или заявке покупателя"></i>
										</td>            
									</tr>		
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="center" colspan="10">Нет товаров в листе ожидания, либо неверная фильтрация</td>
								</tr>			
							<?php } ?>
						</tbody>
					</table>
				</form>
				<div class="pagination"><?php echo $pagination; ?></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$('td.is_prewaitlist span.status_color_padding').click(function(){
				var order_product_id = $(this).attr('data-order-product-id');
				var is_prewaitlist = ($(this).attr('data-is-prewaitlist') == '1');
				var td = $(this);
				
				if (is_prewaitlist){
					var title = "Клиент точно готов ждать?";
					var text = "Перевести заявку в лист ожидания, клиент будет ждать.";
					} else {
					var title = "Клиент не готов ждать, отложить до выяснения?";
					var text = "Пока не понятно, вернуть в заявки";
				}
				
				swal({ title: title, text: text, type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Изменить статус", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { 
					$.ajax({	
						url: 'index.php?route=catalog/waitlist/setPreWaitListAjax&token=<?php echo $token; ?>',
						type: 'post',
						dataType: 'text',
						data: {
							'order_product_id' : order_product_id				
						},
						success: function(text){
							if (text == '1'){
								td.html('Заявка');
								td.attr('data-is-prewaitlist', '1');
								td.css("background-color","#cf4a61");
								td.css("color","#fff");
								} else {
								td.html('Готовы ждать');
								td.attr('data-is-prewaitlist', '0');
								td.css("background-color","#7fff00");
								td.css("color","#696969");
							}
						},
						error: function(html){
							console.log(html)
						}
						
					});
				});		
				
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			$('.ask_buyers').click(function(){
				var _el = $(this);
				$.ajax({
					url : 'index.php?route=api/bitrixbot/askBrandManagersAboutWaitListAjax&product_id='+$(this).attr('data-product-id')+'&token=<?php echo $token; ?>',
					type : 'GET',
					beforeSend : function(){
						_el.next('i').removeClass('fa-user-o');
						_el.next('i').addClass('fa-spinner').addClass('fa-spin');
					},
					success : function(){
						_el.next('i').removeClass('fa-spinner').removeClass('fa-spin');
						_el.next('i').addClass('fa-user-o');
					},
					error : function(e){
						console.log(e);
					}
				});
			});
			
			$('.change_supplier_has').click(function(){
				let td = $(this);
				let order_product_id = $(this).attr('data-order-product-id');
				let suppler_has = ($(this).attr('data-suppler-has') == '1');		
				
				console.log(order_product_id);
				
				if (suppler_has){
					var title = "Товара точно нет в наличии?"
					} else {
					var title = "Товар точно есть в наличии?"
				}
				
				swal({ title: title, text: "Менеджера будут уведомлены!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Изменить наличие", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { 
					$.ajax({	
						url: 'index.php?route=catalog/waitlist/setSupplierHasAjax&token=<?php echo $token; ?>',
						type: 'POST',
						dataType: 'text',
						data: {
							'order_product_id' : order_product_id				
						},
						success: function(text){
							if (text == '1'){
								td.html('Да');
								td.attr('data-suppler-has', '1');
								td.css("background-color","#BCF5BC");
								td.css("color","darkgreen");
								} else {
								td.html('Нет');
								td.attr('data-suppler-has', '0');
								td.css("background-color","#FFEAA8");
								td.css("color","#826200");
							}
						},
						error: function(html){
							console.log(html)
						}
						
					});
				});		
				
			});
		});
	</script>
	<script type="text/javascript"><!--
		function filter() {
			url = 'index.php?route=catalog/waitlist&token=<?php echo $token; ?>';
			
			var filter_name = $('input[name=\'filter_name\']').attr('value');
			
			if (filter_name) {
				url += '&filter_name=' + encodeURIComponent(filter_name);
			}
			
			var filter_supplier_has = $('input[name=\'filter_supplier_has\']').is(':checked');
			
			if (filter_supplier_has) {
				url += '&filter_supplier_has=1';
			}
			
			var filter_model = $('input[name=\'filter_model\']').attr('value');
			
			if (filter_model) {
				url += '&filter_model=' + encodeURIComponent(filter_model);
			}
			
			var filter_price = $('input[name=\'filter_price\']').attr('value');
			
			if (filter_price) {
				url += '&filter_price=' + encodeURIComponent(filter_price);
			}
			
			var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
			
			if (filter_order_id) {
				url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
			}
			
			var filter_customer_id = $('input[name=\'filter_customer_id\']').attr('value');
			
			if (filter_customer_id) {
				url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
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
	<script type="text/javascript"><!--
		$.widget('custom.catcomplete', $.ui.autocomplete, {
			_renderMenu: function(ul, items) {
				var self = this, currentCategory = '';
				
				$.each(items, function(index, item) {
					if (item.category != currentCategory) {
						ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
						
						currentCategory = item.category;
					}
					
					self._renderItem(ul, item);
				});
			}
		});
		
		$('input[name=\'filter_customer_name\']').autocomplete({
			delay: 500,
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {		
						response($.map(json, function(item) {
							return {
								category: item.customer_group,
								label: item.name,
								value: item.customer_id
							}
						}));
					}
				});
			}, 
			select: function(event, ui) {
				$('input[name=\'filter_customer_name\']').val(ui.item.label);
				$('input[name=\'filter_customer_id\']').val(ui.item.value);
				
				//	filter();
				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	//--></script> 
<?php echo $footer; ?>									