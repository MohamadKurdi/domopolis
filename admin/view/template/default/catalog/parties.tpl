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
			<input type="text" name="filter_partie" style="width: 100px;text-align:center;" value="<? echo $filter_partie; ?>" /><a onclick="filter();" class="button">Фильтр</a></div>
		</div>
		<div class="content">	
			<style>
				table.filter_tbl tr td{border:0px;}
			</style>
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>               
						</tr>
					</thead>
					<tbody>
						
						<tr class="filter">
						</tr>
						
						<? if (isset($parties) && count($parties) > 0) { ?>
							
							<? foreach ($parties as $partie) { ?>			
								
								<thead>
									<tr>
										<th colspan="10" style="font-size:18px; text-align:center; font-weight:initial; color:#FFF; padding:5px 0px; background:#1f4962">
											Партия <b><? echo $partie['part_num']; ?></b>, заказы с <? echo $partie['min_date']; ?> по <? echo $partie['max_date']; ?>
											<span style="display:inline-block; float:right; margin-right:10px; font-size:14px;"><a href="index.php?route=catalog/parties/getPartieCheques&partie=<? echo trim($partie['part_num']); ?>&token=<? echo $token; ?>" style="color:white;" target="_blank">получить все чеки</a></span>
										</th>
									</tr>
									<tr> 
										<td class="center" style="width:1px">Заказ</td>
										<td class="center">Название товара</td>
										<td class="center">Артикул</td>
										<td class="center">EAN</td>
										<td class="center" style="width:1px">Колво</td>
										<td class="center" style="width:1px">Цена</td>
										<td class="center" style="width:1px">Со скидкой</td>
										<td class="center">Поставка</td>
										<td class="center">Партия</td>
										<td class="center">Действия</td>
									</tr>
								</thead>
								<tr class="filter">
									
								</tr>
								<? foreach ($partie['orders'] as $order) { ?>
									<tr>
										<td rowspan="<? echo count($order['products'])+1; ?>" style="vertical-align:middle; text-align:center;font-size:14px; font-weight:700;" valign="middle">
											<a href="<? echo $order['href']; ?>" style="text-decoration:none;" target="_blank">
												<?if ($order['has_problem']) { ?>
													<span style='color:#cf4a61;'>
														<i class="fa fa-warning"></i>&nbsp;# <? echo $order['order_id']; ?>
													</span>
													<? } else { ?>
													# <? echo $order['order_id']; ?>
												<? } ?></a><br />
												<span class="status_color" style="display:inline-block; padding:5px 10px; background: #<?php echo $order['order_status']['status_bg_color']; ?>; color: #<?php echo $order['order_status']['status_txt_color']; ?>;"><? if ($order['order_status']['status_fa_icon']) { ?>
													<i class="fa <? echo $order['order_status']['status_fa_icon']; ?>" aria-hidden="true"></i>
												<? } ?><?php echo $order['order_status']['name']; ?></span><br /><br />
												<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#f990c3;"><span style="font-size:10px;"><? echo $order['manager_name']; ?></span></span>				
										</td>
										<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
										<td rowspan="<? echo count($order['products'])+1; ?>" style="vertical-align:middle; white-space:nowrap; text-align:center;font-size:14px; padding:5px 5px;" valign="middle">
											<a class="button view-cheques" data-order-id="<? echo $order['order_id']; ?>" style="margin-bottom:5px; white-space:nowrap;">Тов. чеки</a>							
										</td>
									</tr>
									<? foreach ($order['products'] as $product) { ?>
										<tr <? if (md5($partie['part_num']) != md5( $product['part_num'])) { ?>class="hidden_tr_<? echo $order['order_id']; ?>"<? } ?>>
											<td class="left">
												<? echo $product['name']; ?>
											</td>
											<td class="center">
												<? echo $product['model']; ?>
											</td>
											<td class="center">
												<? echo $product['ean']; ?>
											</td>
											<td class="center">
												<? echo $product['quantity']; ?>
											</td>
											<td class="left" style="white-space:nowrap;">
												<? echo $product['total_txt']; ?>
											</td>
											<td class="left" style="white-space:nowrap;">
												<? echo $product['totalwd_txt']; ?>
											</td>
											<td class="left">
												<? echo $product['delivery_num']; ?>
											</td>
											<td class="center">
												<? if (md5($partie['part_num']) == md5( $product['part_num'])) { ?>
													<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#abce8e;">
														<? echo $product['part_num']; ?>
													</span>
													<? } elseif ($product['part_num']) { ?>
													<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#e4c25a;">
														<? echo $product['part_num']; ?>
													</span>
													<? } else { ?>
													<span style='color:#cf4a61;'>
														<i class="fa fa-warning"></i>
													</span>
												<? } ?>
											</td>
										</tr>
									<? } ?>			
									
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class="center" style="white-space:nowrap; font-weight:700;"><? echo $order['total_national_txt']; ?></td>
										<td></td>
										<td style="white-space:nowrap; font-weight:700;">
											<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#abce8e;">
												<? echo $order['total_by_order_in_this_partie_txt']; ?>
											</span>
										</td>
										<td></td>
									</tr>
									
									<tr>
										<td colspan="10" style="height:0px;border-bottom:2px solid #1f4962;padding-bottom:5px;margin-bottom:5px;"></td>
									</tr>	
								<? } ?>
								
								
								<tr>
									<td colspan="6" class="right"><b>Всего по партии в заказах</b><br />
										<span class="help">Сумма не учитывает, входит ли товар в партию, только суммирует заказы, в которых есть хотя бы один товар из партии</span>
									</td>
									<td colspan="4" class="right">
										<span class="status_color right" style="display:inline-block; padding:5px 10px; background-color:#e4c25a; font-size:20px; font-weight:700;">
										<? echo $partie['total_by_partie_explicit']; ?></span>
									</td>
								</tr>
								
								<tr>
									<td colspan="6" class="right"><b>Всего по партии в товарах</b><br />
									<span class="help">В сумме учтены только те товары, которые входят в данную партию. Суммируется "цена товара с учетом скидки".</span></td>
									<td colspan="4" class="right">
									<span class="status_color right" style="display:inline-block; padding:5px 10px; background-color:#abce8e; font-size:20px; font-weight:700;">
										<? echo $partie['total_by_partie']; ?>
									</span></td>
								</tr>
								
								
								<? foreach ($partie['total_by_managers'] as $manager_id => $manager) { ?>
								<tr>
									<td colspan="6" class="right">
									<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#f990c3;"><? echo $manager['manager_name']; ?>
										</span><br /><br />
										<span class="help" style="display:inline-block; padding:5px 10px; background-color:#e4c25a;">сумма по всем заказам, в которых есть хотя бы 1 товар из партии</span>
										&nbsp;&nbsp;
										<span class="help" style="display:inline-block; padding:5px 10px; background-color:#abce8e;">сумма только по товарам, входящим в партию</span>
								</td>
									<td colspan="4" class="right">
									<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#e4c25a; font-size:20px; font-weight:700;">
										<? echo $manager['total_explicit_txt']; ?>
									</span>&nbsp;&nbsp;
									<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#abce8e; font-size:20px; font-weight:700;">
										<? echo $manager['total_in_partie_txt']; ?>
									</span>
									</td>
								</tr>
								<? } ?>
								
							<? } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="10">Нет партий, соответствующих условиям, либо неверная фильтрация</td>
							</tr>			
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
	<div id="mailpreview"></div>
</div>
<script>
	$('.view-cheques').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order/invoicehistory&token=<?php echo $token; ?>&order_id=' +  $(this).attr('data-order-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
</script>
<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=catalog/parties&token=<?php echo $token; ?>';
		
		var filter_partie = $('input[name=\'filter_partie\']').attr('value');
		
		if (filter_partie) {
			url += '&filter_partie=' + encodeURIComponent(filter_partie);
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
	
	$('input[name=\'filter_customer_name\']').catcomplete({
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
			
			filter();
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
//--></script> 
<?php echo $footer; ?>