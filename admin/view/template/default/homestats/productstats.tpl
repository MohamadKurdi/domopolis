<style>
	.list.big thead td, .list.big tr.small td{font-size:12px; font-weight:400;}
	.list.big tbody td{font-size:14px;}
	.list.small-bottom-margin{margin-bottom: 0px;}
	.list.no-top-border td{border-top:0px;}
	.list tbody td a{text-decoration: none; color: gray;}
</style>


<div class="dashboard-heading">
	<i class="fa fa-ambulance"></i> Что происходит с контентом?			
</div>
<div class="dashboard-content" style="min-height:91px;">
	<div>
		<div style="width:50%; float:left;">
			<table class="list big small-bottom-margin">
				<thead>
					<tr>
						<td colspan="8" class="left">
							Товары
						</td>
					</tr>					
				</thead>
				<tr class="small">
					<td style="color:#66c7a3"><i class="fa fa-plus" aria-hidden="true"></i> <a style="color:#66c7a3" href="<?php echo $filter_total_products_added_today; ?>">Td<i class="fa fa-filter"></i></a></td>
					<td style="color:#3276c2"><i class="fa fa-plus" aria-hidden="true"></i> <a style="color:#3276c2" href="<?php echo $filter_total_products_added_yesterday; ?>">Ys <i class="fa fa-filter"></i></a></td>
					<td style="color:#24a4c1"><i class="fa fa-plus" aria-hidden="true"></i> Wk</td>		
					<td style="color:#24a4c1"><i class="fa fa-plus" aria-hidden="true"></i> Mn</td>
					<td style="color:#9832FF"><a style="color:#9832FF" href="<?php echo $filter_product_in_queue; ?>"><i class="fa fa-refresh" aria-hidden="true"></i> AQue</a></td>
					<td style="color:#9832FF"><i class="fa fa-refresh" aria-hidden="true"></i> VQue</a></td>
					<td style="color:#9832FF"><i class="fa fa-plus" aria-hidden="true"></i> AQue</a></td>
					<td style="color:#fa4934"><a style="color:#fa4934" href="<?php echo $filter_total_products_invalid_asin; ?>">Bad <i class="fa fa-filter"></i></a></td>								
				</tr>
				<tr class="small">
					<td><?php echo $total_products_added_today; ?></td>
					<td><?php echo $total_products_added_yesterday; ?></td>
					<td><?php echo $total_products_added_week; ?></td>
					<td><?php echo $total_products_added_month; ?></td>
					<td><?php echo $total_product_in_queue; ?></td>
					<td><?php echo $total_product_in_variants_queue; ?></td>
					<td><?php echo $total_products_in_queue_today; ?></td>
					<td style="color:#fa4934"><?php echo $total_products_invalid_asin; ?></td>					
				</tr>
			</table>
			<table class="list big small-bottom-margin">
				<tr class="small">
					<td style="color:#66c7a3">Total</td>
					<td style="color:#66c7a3">Variants</td>
					<td style="color:#fa4934"><a style="color:#fa4934" href="<?php echo $filter_total_products_enabled; ?>">Enabled<i class="fa fa-filter"></i></a></td>
					<td style="color:#3276c2"><a style="color:#3276c2" href="<?php echo $filter_total_products_in_tech; ?>">Tech<i class="fa fa-filter"></i></a></td>
					<td style="color:#24a4c1"><a style="color:#24a4c1" href="<?php echo $filter_total_product_parsed; ?>">Ready <i class="fa fa-filter"></i></a></td>
					<td style="color:#24a4c1">To load</td>
					<td style="color:#fa4934">Doubles</td>						
				</tr>
				<tr class="small">
					<td><?php echo $total_products; ?></td>
					<td><?php echo $total_products_no_variants; ?></td>
					<td><?php echo $total_product_enabled; ?></td>
					<td><?php echo $total_products_in_tech; ?> </td>
					<td><?php echo $total_product_parsed; ?></td>
					<td><?php echo $total_product_need_to_be_parsed; ?></td>
					<td style="color:#fa4934"><?php echo $total_products_double_asin; ?></td>
				</tr>				
			</table>
		</div>
		<div style="width:50%; float:left;">
			<table class="list big small-bottom-margin">
				<thead>
					<tr>
						<td colspan="7" class="left">
							Офферы
						</td>
					</tr>					
				</thead>
				<tr class="small">
					<td style="color:#3276c2"><i class="fa fa-hourglass" aria-hidden="true"></i> Ждет</td>
					<td style="color:#9832FF"><i class="fa fa-refresh" aria-hidden="true"></i> OQue</td>
					<td style="color:#24a4c1">Σ Всего</td>
					<td style="color:#3276c2"><i class="fa fa-refresh" aria-hidden="true"></i> Сегодня</td>
					<td style="color:#3276c2"><i class="fa fa-refresh" aria-hidden="true"></i> Вчера</td>
					<td style="color:#66c7a3"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <a style="color:#66c7a3" href="<?php echo $filter_total_product_have_offers; ?>">В нал <i class="fa fa-filter"></i></a></td>		
					<td style="color:#fa4934"><i class="fa fa-thumbs-down" aria-hidden="true"></i> <a style="color:#fa4934" href="<?php echo $filter_total_product_have_no_offers; ?>">Нету <i class="fa fa-filter"></i></a></td>				
				</tr>
				<tr class="small">
					<td><?php echo $total_product_to_get_offers; ?></td>
					<td><?php echo $total_product_to_get_offers_in_queue; ?></td>
					<td><?php echo $total_product_got_offers; ?></td>
					<td><?php echo $total_product_got_offers_today; ?></td>
					<td><?php echo $total_product_got_offers_yesterday; ?></td>
					<td><?php echo $total_product_have_offers; ?></td>
					<td><?php echo $total_product_have_no_offers; ?></td>
				</tr>		
			</table>
            <table class="list big small-bottom-margin">
                <tr class="small">
                    <td style="color:#66c7a3"><i class="fa fa-language" aria-hidden="true"></i> Час</td>
                    <td style="color:#66c7a3"><i class="fa fa-language" aria-hidden="true"></i> Сегодня</td>
                    <td style="color:#3276c2"><i class="fa fa-language" aria-hidden="true"></i> Вчера</td>
                    <td style="color:#24a4c1"><i class="fa fa-language" aria-hidden="true"></i> Неделя</td>
                    <td style="color:#fa4934"><i class="fa fa-language" aria-hidden="true"></i> Месяц</td>
                </tr>
                <tr class="small">
                    <td><?php echo $translated_total_hour; ?></td>
                    <td><?php echo $translated_total_today; ?></td>
                    <td><?php echo $translated_total_yesterday; ?></td>
                    <td><?php echo $translated_total_week; ?></td>
                    <td><?php echo $translated_total_month; ?></td>
                </tr>
            </table>
		</div>
		<div class="clr"></div>		
	</div>
 <?php /*
	<div>
		<div style="width:50%; float:left;">
			<table class="list big small-bottom-margin no-top-border">
				<thead>
					<tr>
						<td colspan="4" class="left">
							Категории
						</td>
					</tr>
					<tr>
						<td style="color:#66c7a3"><i class="fa fa-list" aria-hidden="true"></i> Всего</td>
						<td style="color:#3276c2"><i class="fa fa-amazon" aria-hidden="true"></i> Конечных</td>
						<td style="color:#24a4c1"><i class="fa fa-share" aria-hidden="true"></i> Синхрон</td>
						<td style="color:#fa4934"><i class="fa fa-refresh" aria-hidden="true"></i> Полные</td>
					</tr>
					<tr>
						<td><?php echo $total_categories; ?></td>
						<td><?php echo $total_categories_final; ?></td>
						<td><?php echo $total_categories_enable_load; ?></td>
						<td><?php echo $total_categories_enable_full_load; ?></td>
					</tr>
				</thead>
			</table>
		</div>
		<div style="width:50%; float:left;">	

		</div>
		<div class="clr"></div>		
	</div>
*/ ?>
</div>
