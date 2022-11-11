<?php echo $header; ?>
<!--[if IE]>
	<script type="text/javascript" src="view/javascript/jquery/flot2/excanvas.js"></script>
<![endif]--> 
<style>
	div.red{
		background-color:#ef5e67;
	}
	div.orange{
		background-color:#ff7f00;
	}
	div.green{
		background-color:#00ad07;
	}
	div.black{
		background-color:#2e3438;
	}
</style>
<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.resize.min.js"></script>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"> <a href="<?php echo $reset; ?>" class="button">Обнулить общую статистику</a></div>
		</div>
		<div class="content">
			<table style="width: 100%;">
				<tbody>
					<tr class="filter f_top">
						<td>
							<div>
								<p>Дата от</p>
								<input type="text" class="date" name="filter_date_from" value="<?php echo $filter_date_from; ?>" />
							</div>
							
							<div style="margin-top:10px;">
								<p>Дата до</p>
								<input type="text" class="date" name="filter_date_to" value="<?php echo $filter_date_to; ?>" />
							</div>
						</td>
						<td>
							<p>Магазин</p>
							<div class="scrollbox" style="max-width:500px; height:200px;">
								<?php if ($stores) { ?>
									<?php $filter_store_id = $filter_store_id?explode(',',html_entity_decode($filter_store_id)):array(); ?>
									<?php $class = 'odd'; ?>
									<?php foreach ($stores as $store) { ?>
										<div class="<? echo $class ?>"> 
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<input class="checkbox filter_store_id" id="store_<?php echo $store['store_id']; ?>" type="checkbox" name="filter_store_id[]" value="<?php echo $store['store_id']; ?>" <? if (in_array($store['store_id'], $filter_store_id)) { ?> checked="checked" <? } ?> />
											<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
										</div>
									<? } ?>									
								<? } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
						</td>
						<td>
							<p>Бренд</p>
							<div class="scrollbox" style="max-width:500px; height:200px;">
								<?php if ($manufacturers) { ?>
									<?php $filter_manufacturer_id = $filter_manufacturer_id?explode(',',html_entity_decode($filter_manufacturer_id)):array(); ?>
									<?php $class = 'odd'; ?>
									<?php foreach ($manufacturers as $manufacturer) { ?>
										<div class="<? echo $class ?>"> 
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<input class="checkbox filter_manufacturer_id" id="manufacturer_<?php echo $manufacturer['manufacturer_id']; ?>" type="checkbox" name="filter_manufacturer_id[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" <? if (in_array($manufacturer['manufacturer_id'], $filter_manufacturer_id)) { ?> checked="checked" <? } ?> />
											<label for="manufacturer_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
										</div>
									<? } ?>									
								<? } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
						</td>
						<td>
							<p>Категория</p>
							<div class="scrollbox" style="max-width:500px; height:200px;">
								<?php if ($categories) { ?>
									<?php $filter_category_id = $filter_category_id?explode(',',html_entity_decode($filter_category_id)):array(); ?>
									<?php $class = 'odd'; ?>
									<?php foreach ($categories as $category) { ?>
										<div class="<? echo $class ?>"> 
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<input class="checkbox filter_category_id" id="category_<?php echo $category['category_id']; ?>" type="checkbox" name="filter_category_id[]" value="<?php echo $category['category_id']; ?>" <? if (in_array($category['category_id'], $filter_category_id)) { ?> checked="checked" <? } ?> />
											<label for="category_<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
										</div>
									<? } ?>									
								<? } ?>
							</div>
							<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
						</td>
						<td style="vertical-align:bottom; text-align:right;">
							<a onclick="filter();" class="button">Фильтр</a>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="filter_bord"></div>
			<table class="list">
				<thead>
					<tr>
						<td class="left"></td>
						<td class="left"><?php echo $column_name; ?></td>
						<td class="left">Бренд</td>
						<td class="left"><?php echo $column_model; ?></td>
						<td class="left">EAN</td>
						<td class="left">Цена</td>
						<td class="left">Закупка</td>
						<td class="left"></td>
						<td class="right"><?php echo $column_viewed; ?></td>
						<td class="right">
							<?php if ($sort == 'cart') { ?>
								<a href="<?php echo $sort_cart; ?>" class="<?php echo strtolower($order); ?>">В корзине</a>
								<?php } else { ?>
								<a href="<?php echo $sort_cart; ?>">В корзине</a>
							<? } ?>
						</td>
						<td class="right">PI корзины</td>
						<td class="right"><?php if ($sort == 'bought') { ?>
								<a href="<?php echo $sort_bought; ?>" class="<?php echo strtolower($order); ?>">Заказано</a>
								<?php } else { ?>
								<a href="<?php echo $sort_bought; ?>">Заказано</a>
							<? } ?></td>
						<td class="right">PI заказов</td>
					</tr>
				</thead>
				<tbody>
					<?php if ($products) { ?>
						<?php foreach ($products as $product) { ?>
							<tr>
								<td class="center" rowspan="2"><img src="<?php echo $product['image']; ?>" height="60" width="60" /></td>
								<td class="left"><b><?php echo $product['name']; ?></b> <a href="<? echo $product['adminlink'] ?>" target="_blank"><i class="fa fa-edit" aria-hidden="true"></i></a></td>
								<td class="left"><?php echo $product['manufacturer']; ?></td>
								<td class="left"><?php echo $product['model']; ?></td>
								<td class="left"><?php echo $product['ean']; ?></td>
								<td class="left">
									<? if ($product['oldprice']) { ?>
										<?php echo $product['price']; ?><br />
										<span style="color:red;font-size:10px;"><?php echo $product['oldprice']; ?></span>
									<? } else { ?>
										<?php echo $product['price']; ?>
									<? } ?>
								</td>
								<td class="left"><?php echo $product['actual_cost']; ?>
									<br />
									<span style="font-size:10px"><?php echo $product['actual_cost_date']; ?></span>
								</td>
								<td class="left">
									<? if ($product['diff']) { ?>
									<div class="<? echo $product['diff_clr'] ?>" style="display:inline-block; padding:3px; color:#FFF; margin-left:5px;"><?php echo $product['diff_percent']; ?></div>
									<? } ?>
								</td>
								<td class="center"><div style="display:inline-block; background-color:#6A6A6A; padding:3px; color:#FFF; margin-left:5px;"><?php echo $product['viewed']; ?></div></td>
								<td class="center"><div style="display:inline-block; background-color:#<? if ($product['cart']) { ?>ff7f00<? } else { ?>ef5e67<? } ?>; padding:3px; color:#FFF; margin-left:5px;"><?php echo $product['cart']; ?></div></td>
								<td class="center"><div style="display:inline-block; background-color:#<? if ($product['cart_pi'] > 0) { ?>00ad07<? } else { ?>ef5e67<? } ?>; padding:3px; color:#FFF; margin-left:5px;"><?php echo $product['cart_pi']; ?></div></td>
								<td class="center"><div style="display:inline-block; background-color:#<? if ($product['bought']) { ?>ff7f00<? } else { ?>ef5e67<? } ?>; padding:3px; color:#FFF; margin-left:5px;"><?php echo $product['bought']; ?></div></td>
								<td class="center"><div style="display:inline-block; background-color:#<? if ($product['bought_pi'] > 0) { ?>00ad07<? } else { ?>ef5e67<? } ?>; padding:3px; color:#FFF; margin-left:5px;"><?php echo $product['bought_pi']; ?></div></td>
							</tr>							
							<tr>
								<? if ($product['chart']) { ?>
									<td colspan="12">
									<div id="plot_<? echo $product['product_id']; ?>" style="height:50px;"></div>						
									<script>
										$(document).ready(function() {
											var json_<? echo $product['product_id']; ?> = JSON.parse('<? echo json_encode($product['chart']['chart_data']); ?>');
											var option_<? echo $product['product_id']; ?> = {	
												shadowSize: 0,
												colors: ['#4ea24e', '#1065D2'],
												bars: { 
													show: false,
													fill: false,
													lineWidth: 0.5
												},
												grid: {
													backgroundColor: '#FFFFFF',
													hoverable: true,
													borderWidth: 0
												},
												points: {
													show: false
												},													
												xaxis: {
													show: false,
													ticks: json_<? echo $product['product_id']; ?>['xaxis']
												}
											}	
											$.plot($('#plot_<? echo $product['product_id']; ?>'), [json_<? echo $product['product_id']; ?>.viewed], option_<? echo $product['product_id']; ?>);
											$('#plot_<? echo $product['product_id']; ?>').bind('plothover', function(event, pos, item) {
												$('.tooltip').remove();
												
												if (item) {
													$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
													
													$('#tooltip').css({
														position: 'absolute',
														left: item.pageX - ($('#tooltip').outerWidth() / 2),
														top: item.pageY - $('#tooltip').outerHeight(),
														pointer: 'cursor'
													}).fadeIn('slow');	
													
													$($('#plot_<? echo $product['product_id']; ?>')).css('cursor', 'pointer');		
													} else {
													$($('#plot_<? echo $product['product_id']; ?>')).css('cursor', 'auto');
												}
											});
										});
									</script>
								</td>
							</tr>
						<? } ?>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="4"><?php echo $text_no_results; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
</div>
</div>	
<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=report/product_viewed&token=<?php echo $token; ?>';
		
		var filter_date_from = $('input[name=\'filter_date_from\']').attr('value');
		
		if (filter_date_from) {
			url += '&filter_date_from=' + encodeURIComponent(filter_date_from);
		}
		
		var filter_date_to = $('input[name=\'filter_date_to\']').attr('value');
		
		if (filter_date_to) {
			url += '&filter_date_to=' + encodeURIComponent(filter_date_to);
		}
		
		var filter_store_id = $('input:checkbox:checked.filter_store_id').map(function(){
		return this.value; }).get().join(",");
		
		if (filter_store_id) {
			url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
		}
		
		var filter_manufacturer_id = $('input:checkbox:checked.filter_manufacturer_id').map(function(){
		return this.value; }).get().join(",");
		
		if (filter_manufacturer_id) {
			url += '&filter_manufacturer_id=' + encodeURIComponent(filter_manufacturer_id);
		}
		
		var filter_category_id = $('input:checkbox:checked.filter_category_id').map(function(){
		return this.value; }).get().join(",");
		
		if (filter_category_id) {
			url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
		}
		
		location = url;
	}
</script>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date').datepicker({dateFormat: 'yy-mm-dd'});					
	});
//--></script>
<?php echo $footer; ?>	