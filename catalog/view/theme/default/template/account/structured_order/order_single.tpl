<div class="order-list">
							<div class="order-head">
								<div class="order-id">
									<?php if ($order['preorder']) { ?>
										<b>№ Заявки на уточнение цены</b> #<?php echo $order['order_id']; ?>
										<?php } else { ?>
										<b><?php echo $text_order_id; ?></b> #<?php echo $order['order_id']; ?>
									<?php } ?>
								</div>
								<div class="order-status"><b><?php echo $text_status; ?></b> <?php echo $order['status']; ?></div>
							</div>
							<div class="order-content">
								<div>
									<?php if ($order['preorder']) { ?>
										<b>Заявка оформлен</b> <?php echo $order['date_added']; ?><br />
										<?php } else { ?>
										<b>Заказ оформлен</b> <?php echo $order['date_added']; ?><br />
									<?php } ?>
									<b><?php echo $text_products; ?></b> <?php echo $order['product_count']; ?>
								</div>
								<div>
									<b><?php echo $text_customer; ?></b> <?php echo $order['name']; ?>
								</div>
								<div class="order-info">						
									<a class="button btn btn-acaunt" style="text-transform:none; font-weight:400;" href="<?php echo $order['href']; ?>">
										<i class="fas fa-external-link-alt" title="<?php echo $button_view; ?>"></i><?php echo $button_view; ?>
									</a>											
								</div>
							</div>				
							<div class="order-products-wrap">
								<?php foreach ($order['products'] as $product) { ?>
									<div class="order-product">
										<a href="<?php echo $product['link']; ?>" target="_blank" title="<?php echo $product['name']; ?>">
											<div class="img-wrap">
												<img src="<?php echo $product['image']; ?>" />
											</div>
											<b><?php echo $product['name']; ?></b>
										</a>							
										<div class="order-product-price">
											<?php if ($order['order_info']['preorder'] && $product['price_isnull']) { ?>
												Уточняется
												<?php } else { ?>
												<?php echo $product['price']; ?>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>