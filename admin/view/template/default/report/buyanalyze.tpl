<?php echo $header; ?>
<!--[if IE]>
	<script type="text/javascript" src="view/javascript/jquery/flot2/excanvas.js"></script>
<![endif]--> 
<style>
	span.red{
	background-color:#ef5e67;	display:inline-block; padding:3px 5px; color:#fff; 
	}
	span.orange{
	background-color:#ff7f00;display:inline-block; padding:3px 5px; color:#fff; 
	}
	span.green{
	background-color:#00ad07;display:inline-block; padding:3px 5px; color:#fff; 
	}
	span.black{
	background-color:#2e3438;display:inline-block; padding:3px 5px;color:#fff; 
	}
</style>
<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot2/jquery.flot.resize.min.js"></script>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
</div>
<div class="box">
    <div class="heading order_head">
		<h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>			
	</div>
    <div class="content">
		<table style="width: 100%;">
			<tbody>
				<tr class="filter f_top">
					<td style="padding-left:20px;">
						<div>
							<p>Дата от</p>
							<input type="text" class="date" name="filter_date_start" value="<?php echo $filter_date_start; ?>" />
						</div>
						
						<div style="margin-top:10px;">
							<p>Дата до</p>
							<input type="text" class="date" name="filter_date_end" value="<?php echo $filter_date_end; ?>" />
						</div>
						
						<div style="margin-top:10px;">
							<input id="filter_problems" class="checkbox" type="checkbox" name="filter_problems" value="1" <?php if ($filter_problems) print 'checked'; ?> />
							<label for="filter_problems">Есть проблемы</label>
						</div>
						
						<div style="margin-top:10px;">
							<input id="filter_set" class="checkbox" type="checkbox" name="filter_set" value="1" <?php if ($filter_set) print 'checked'; ?> />
							<label for="filter_set">Заданы лимиты</label>
						</div>
						
						<div style="margin-top:10px;">
							<input id="filter_not_set" class="checkbox" type="checkbox" name="filter_not_set" value="1" <?php if ($filter_not_set) print 'checked'; ?> />
							<label for="filter_not_set">Не заданы лимиты</label>
						</div>
						
						<div style="margin-top:10px;">
							<p>Динамика</p>
							<select name="filter_dynamics">
								<option value="month" <? if ($filter_dynamics == 'month') { ?>selected="selected" <? } ?>>Месяц</option>
								<option value="week" <? if ($filter_dynamics == 'week') { ?>selected="selected" <? } ?>>Неделя</option>								
							</select>
						</div>
					</td>
					<td>
						<p>Магазин</p>
						<div class="scrollbox" style="max-width:500px; height:200px;">
							<?php if ($stores) { ?>
								<?php $filter_store_id = isset($filter_store_id)?explode(',',html_entity_decode($filter_store_id)):array(); ?>
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
						<div style="margin-top:10px;">
							<input id="filter_debugsql" class="checkbox" type="checkbox" name="filter_debugsql" value="1" <?php if ($filter_debugsql) print 'checked'; ?> />
							<label for="filter_debugsql">Отладка SQL</label>
						</div>
						<div style="margin-top:50px;">
							<a onclick="filterCSV();" class="button">CSV фильтра</a>
							<a onclick="filter();" class="button">Фильтр</a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="filter_bord"></div>
		<table class="list">
			<thead>
				<tr>
					<td class="left"></td>
					<td class="left" style="font-size:14px;"><?php echo $column_name; ?></td>
					<td class="center" style="font-size:14px; text-align:center; width:240px;">Склады и лимиты</td>
					<td class="left" style="font-size:14px;">Цена</td>
					<td class="left" style="font-size:14px;">Закупка</td>
					<td class="center" style="font-size:14px;">Период <i class="fa fa-question-circle ktooltip_hover" title="Всего единиц заказано за период"></i></td>
					<td class="center" style="font-size:14px;"><? echo date('m.Y'); ?> <i class="fa fa-question-circle ktooltip_hover" title="Всего единиц заказано за текущий месяц"></i></td>
					<td class="center" style="font-size:14px;"><? echo date('Y'); ?> <i class="fa fa-question-circle ktooltip_hover" title="Всего единиц заказано за текущий год"></i></td>
					<td class="center" style="font-size:14px;">Месяц ср. <i class="fa fa-question-circle ktooltip_hover" title="Всего единиц заказано В СРЕДНЕМ за месяц за период"></i></td>
					<td class="center" style="font-size:14px;">Неделя ср.  <i class="fa fa-question-circle ktooltip_hover" title="Всего единиц заказано В СРЕДНЕМ за неделю за период"></i></td>
					<td class="center" style="font-size:14px;">Заказ ср. <i class="fa fa-question-circle ktooltip_hover" title="Среднее количество в заказе по периоду"></i></td>
				</tr>
			</thead>
			<tbody>
				<?php if ($products) { ?>
					<?php foreach ($products as $product) { ?>
						<tr>
							<td class="center" rowspan="2"><img src="<?php echo $product['image']; ?>" height="60" width="60" /></td>
							<td class="left"><b><?php echo $product['name']; ?></b> <a href="<? echo $product['adminlink'] ?>" target="_blank"><i class="fa fa-edit" aria-hidden="true"></i></a>
								<br /><?php echo $product['de_name']; ?>
								<a href="<? echo $product['filter_orders'] ?>" target="_blank"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>
								<div>
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc; color:#fff;"><?php echo $product['product_id']; ?></span>&nbsp;
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc; color:#fff;"><?php echo $product['model']; ?></span>&nbsp;
									<? if ($product['ean']) { ?><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc; color:#fff;"><?php echo $product['ean']; ?></span>&nbsp;<? } ?>
									<? if ($product['manufacturer']) { ?><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc; color:#fff;"><?php echo $product['manufacturer']; ?></span><? } ?>
								</div>
							</td>
							<td class="center">
								<table border=0 style="border:0px">
									<? foreach ($product['product_stocks'] as $warehouse => $quantity) { ?>
										<tr>
											<td style="border:0px">
												<img src="<? echo $stocks[$warehouse]['flag'] ?>" title="<? echo $stocks[$warehouse]['name'] ?>" />
											</td>
											<td style="border:0px">
												<input type="text" style="width:20px;" class="min_stock_update onfocusedit_direct" data-type='min' data-product-id="<?php echo $product['product_id']; ?>" data-store-id="<? echo $product['product_stock_limits_structured'][$warehouse]['store_id'] ?>" value="<? echo $product['product_stock_limits_structured'][$warehouse]['min_stock'] ?>" /><br />
												<span style="color:red;font-size:11px;">мин</span>
											</td>
											<td style="border:0px">
												<span style="font-size:20px;" class="<? echo $product['product_stock_limits_structured'][$warehouse]['cls']?>"><? echo $quantity; ?></span><br />
												<? if (isset($product['product_stocks_waits'][$warehouse]) && $product['product_stocks_waits'][$warehouse]) { ?>
													<span class="green" style="font-size:12px;display:inline-block; margin-top:5px;"><i class="fa fa-clock-o"></i> <? echo $product['product_stocks_waits'][$warehouse]; ?></span>
												<? } ?>
											</td>
											<td style="border:0px">
												<input type="text" style="width:20px;" class="rec_stock_update onfocusedit_direct"  data-type='rec' data-product-id="<?php echo $product['product_id']; ?>" data-store-id="<? echo $product['product_stock_limits_structured'][$warehouse]['store_id'] ?>" value="<? echo $product['product_stock_limits_structured'][$warehouse]['rec_stock'] ?>" /><br />
												<span style="color:red;font-size:11px;">рек</span>
											</td>
										</tr>								
									<? } ?>	
								</table>
							</td>
							<td class="left"><? if ($product['oldprice']) { ?>
								<?php echo $product['price']; ?><br />
								<span style="color:red;font-size:10px;"><?php echo $product['oldprice']; ?></span>
								<? } else { ?>
								<?php echo $product['price']; ?>
							<? } ?></td>
							<td class="left"><?php echo $product['actual_cost']; ?>
								<br />
								<div style="font-size:10px"><?php echo $product['actual_cost_date']; ?></div>
								<? if ($product['diff']) { ?>
									<div class="<? echo $product['diff_clr'] ?>" style="display:inline-block; padding:3px; color:#FFF; margin-left:5px;"><?php echo $product['diff_percent']; ?></div>
								<? } ?>
							</td>
							<td class="center" style="font-size:20px;"><? echo $product['total_bought']; ?>
								<div style="font-size:10px">посл. зак. <?php echo $product['bought_last_order']; ?></div>	
							</td>
							<td class="center" style="font-size:20px;"><? echo $product['bought_last_month']; ?></td>
							<td class="center" style="font-size:20px;"><? echo $product['bought_last_year']; ?></td>
							<td class="center" style="font-size:20px;"><? echo $product['bought_avg_month']; ?></td>
							<td class="center" style="font-size:20px;"><? echo $product['bought_avg_week']; ?></td>
							<td class="center" style="font-size:20px;"><? echo $product['bought_avg_in_order']; ?></td>
							<td class="center"></td>
						</tr>
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
												show: true,
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
<style>
	input.onfocusedit_direct, textarea.onfocusedit_direct, textarea.onfocusout_edit_history,  select.onchangeedit_direct, input.onchangeedit_direct, textarea.onfocusedit_source, textarea.onfocusedit_customer{border-left-color:#4ea24e;}
	
	input.onfocusedit_direct.done, textarea.onfocusedit_direct.done, textarea.onfocusout_edit_history.done, select.onchangeedit_direct.done, input.onchangeedit_direct.done, textarea.onfocusedit_source.done, textarea.onfocusedit_customer.done{border-color:#4ea24e;-webkit-transition : border 500ms ease-out;-moz-transition : border 500ms ease-out; -o-transition : border 500ms ease-out;transition : border 500ms ease-out;}
	
	input.onfocusedit_direct.done+span:after, textarea.onfocusedit_direct.done+span:after, textarea.onfocusout_edit_history.done+span:after, select.onchangeedit_direct.done+span:after, textarea.onfocusedit_source.done+span:after,.onchangeedit_orderproduct.done+label+span:after, textarea.onfocusedit_customer.done+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#4ea24e;content:"\f00c"}
	
	input.onfocusedit_direct.loading+span:after, textarea.onfocusedit_direct.loading+span:after, textarea.onfocusout_edit_history.loading+span:after, select.onchangeedit_direct.loading+span:after, textarea.onfocusedit_source.loading+span:after,.onchangeedit_orderproduct.loading+label+span:after,textarea.onfocusedit_customer.loading+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#e4c25a;content:"\f021"}
	
	input.onfocusedit_direct.error+span:after, textarea.onfocusedit_direct.error+span:after, textarea.onfocusout_edit_history.error+span:after, select.onchangeedit_direct.error+span:after, textarea.onfocusedit_source.error+span:after, .onchangeedit_orderproduct.error+label+span:after,textarea.onfocusedit_customer.error+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#cf4a61;content:"\f071"}
</style>
<script type="text/javascript"><!--
	$('input.onfocusedit_direct').focusout(function(){
		var _el = $(this);
		var _val = $(this).val();			
		var _pid = $(this).attr('data-product-id');
		var _sid = $(this).attr('data-store-id');
		var _type = $(this).attr('data-type');
		
		$.ajax({
			url : 'index.php?route=catalog/product/updateProductStockLimitAjax&token=<? echo $token; ?>',
			type: 'POST',
			dataType : 'text',
			data : {
				product_id : _pid,
				store_id : _sid,
				value : _val,
				type : _type
			},
			beforeSend : function(){
				_el.removeClass('done').addClass('loading');
			},
			success : function(text){
				_el.removeClass('loading').addClass('done');
			},
			error : function(error){
				_el.removeClass('loading').addClass('error');
				console.log(error);
			}			
		});		
	});
	
	function filterCSV() {
		url = 'index.php?route=report/buyanalyze/saveDataToCSV&token=<?php echo $token; ?>';
		
		var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
		
		if (filter_date_start) {
			url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
		}
		
		var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
		
		if (filter_date_end) {
			url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
		
		var filter_problems = $('input[name=\'filter_problems\']:checked').val();
		
		if (filter_problems !== undefined) {
			url += '&filter_problems=1';
		}
		
		var filter_not_set = $('input[name=\'filter_not_set\']:checked').val();
		
		if (filter_not_set !== undefined) {
			url += '&filter_not_set=1';
		}
		
		var filter_set = $('input[name=\'filter_set\']:checked').val();
		
		if (filter_set !== undefined) {
			url += '&filter_set=1';
		}
		
		var filter_debugsql = $('input[name=\'filter_debugsql\']:checked').val();
		
		if (filter_debugsql !== undefined) {
			url += '&filter_debugsql=1';
		}
		
		var filter_dynamics  = $('select[name=\'filter_dynamics\']').attr('value');
		if (filter_dynamics != '*') {
			url += '&filter_dynamics=' + encodeURIComponent(filter_dynamics);
		}
		
		location = url;
	}
	
	
	function filter() {
		url = 'index.php?route=report/buyanalyze&token=<?php echo $token; ?>';
		
		var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
		
		if (filter_date_start) {
			url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
		}
		
		var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
		
		if (filter_date_end) {
			url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
		
		var filter_problems = $('input[name=\'filter_problems\']:checked').val();
		
		if (filter_problems !== undefined) {
			url += '&filter_problems=1';
		}
		
		var filter_not_set = $('input[name=\'filter_not_set\']:checked').val();
		
		if (filter_not_set !== undefined) {
			url += '&filter_not_set=1';
		}
		
		var filter_set = $('input[name=\'filter_set\']:checked').val();
		
		if (filter_set !== undefined) {
			url += '&filter_set=1';
		}
		
		var filter_debugsql = $('input[name=\'filter_debugsql\']:checked').val();
		
		if (filter_debugsql !== undefined) {
			url += '&filter_debugsql=1';
		}
		
		var filter_dynamics  = $('select[name=\'filter_dynamics\']').attr('value');
		if (filter_dynamics != '*') {
			url += '&filter_dynamics=' + encodeURIComponent(filter_dynamics);
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