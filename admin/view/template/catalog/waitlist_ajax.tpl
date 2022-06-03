<div class="box">
    <div class="content" style="min-height:auto;">
		<style>
			table.filter_tbl tr td{border:0px;}
		</style>
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td class="center"></td>
						<td class="center"></td>
						<td class="left">Ожидаемый товар</td>
						<td class="left">Артикул</td>
						<td class="center">Заказ</td>
						<td class="center">Покупатель</td>
						<td class="center">Дата</td>			  
						<td class="center">Кол-во</td>	
						<td class="left">Цена, EUR</td>
						<td class="left">Цена в заказе</td>             
					</tr>
				</thead>
				<tbody>
					<?php if ($products) { ?>
						<? $previous_order_id = 0; ?>
						<?php foreach ($products as $product) { ?>
							<? if ($previous_order_id != $product['order_id']) {  $previous_order_id = $product['order_id']; ?>
								<tr>
									<td colspan="10" style="height:0px;"></td>
								</tr>
							<? } ?>
							<tr> 
								<? if ($product['supplier_has']) { ?>
									<td class="center supplier_has" data-order-product-id="<?php echo $product['order_product_id']; ?>" data-suppler-has='1'><span class="status_color_padding" style="background:#BCF5BC; color:darkgreen; cursor:pointer;">Да</span></td>
									<? } else { ?>
									<td class="center supplier_has" data-order-product-id="<?php echo $product['order_product_id']; ?>" data-suppler-has='0'><span class="status_color_padding" style="background:#FFEAA8; color:#826200; cursor:pointer;">Нет</span></td>
								<? } ?>
								<td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
								<td class="left"><a href="<? echo $product['admin_product_url']; ?>" target="_blank"><b><?php echo $product['name']; ?></b></a>
									<br /><br />
									<span style="font-size:11px;">Всего в листе: <b><? echo $product['total_quantity']; ?></b> шт.: <? foreach ($product['orders'] as $order) { ?>
										<? if ($order['order_id'] == $product['order_id']) { ?>
											<span style="padding:3px; background:rgba(133, 178, 0, 0.47); color:black;">
												<a style="color:black;" href="<? echo $order['order_filter_href']; ?>"><? echo $order['order_id'] ?></a> (<? echo $order['quantity']; ?>)
											</span>
											<? } else { ?>
											<a href="<? echo $order['order_filter_href']; ?>"><? echo $order['order_id'] ?></a> (<? echo $order['quantity']; ?>)
										<? } ?>
										&nbsp;&nbsp;
									<? } ?></span>
								</td>
								<td class="left"><?php echo $product['model']; ?></td>
								<td class="right"><span onclick="$('#order_info_<?php echo $product['order_id']; ?>').toggle();" style="border-bottom:1px dashed black; cursor:pointer;"><b><?php echo $product['order_id']; ?></b></span>
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
												<td class="right"><? echo $product['order']['total_national'].' '.$product['order']['currency_code']; ?></td>
											</tr>
											
											
										</table>								
									</div> <br />			  
									<a href="<? echo $product['admin_order_url']; ?>" style="font-size:10px;">откр. в адм.</a><br />
									<a href="<? echo $product['admin_filter_url']; ?>" style="font-size:10px;">перейти в лист</a><br />
								</td>
								<td class="right"><a href="<? echo $product['admin_customer_url']; ?>" target="_blank"><?php echo $product['customer']['firstname']; ?> <?php echo $product['customer']['lastname']; ?></a><br /><?php echo $product['customer']['telephone']; ?><span class='click2call' data-phone="<?php echo $product['customer']['telephone']; ?>"></span><br />
									<a href="<? echo $product['admin_customer_filter_url']; ?>" style="font-size:10px;">перейти в лист</a><br />
									<span style="font-size:10px;"><? echo $product['customer_total_products']; ?> тов. в <? echo $product['customer_total_orders']; ?> зак.</span>
								</td>
								<td class="right"><?php echo $product['order']['date_added']; ?></td>
								<td class="right"><?php echo $product['quantity']; ?></td>
								<td class="left" style="text-align:center;"><?php if ($product['special']) { ?>
									<span style="text-decoration: line-through;"><?php echo $product['price']; ?> / <?php echo $product['price_national']; ?></span><br/>
									<span style="color: #b00;"><?php echo $product['special']; ?> / <?php echo $product['special_national']; ?></span>
									<?php } else { ?>
									<?php echo $product['price']; ?> / <?php echo $product['price_national']; ?>
								<?php } ?></td>
								<td class="left">
									<?php echo $product['price_in_order']; ?>
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