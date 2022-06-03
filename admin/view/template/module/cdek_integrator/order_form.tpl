<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<?php echo $breadcrumb['separator']; ?>
	<?php if ($breadcrumb['href']) { ?>
	<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } else { ?>
	<?php echo $breadcrumb['text']; ?>
	<?php } ?>
	<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning">
		<ul>
			<?php foreach ($error_warning as $message) { ?>
			<li><?php echo $message; ?></li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a class="button" onclick="$('#form').submit();"><?php echo $button_send; ?></a>
				<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<tbody>
					<tr>
						<td><em>Номер отправления</em></td>
						<td>
							<?php echo $number; ?>
							<input type="hidden" name="number" value="<?php echo $number; ?>" />
						</td>
					</tr>
					<tr>
						<td><em><?php echo $text_order_count_items; ?></em></td>
						<td><?php echo $total; ?></td>
					</tr>
					<?php if ($city_default) { ?>
					<tr>
						<td><em><?php echo $text_city; ?></em></td>
						<td><?php echo $city_name; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<h2><?php echo $text_title_orders; ?></h2>
			<div id="vtabs" class="vtabs">
				<?php foreach ($cdek_orders as $row => $order_info) { ?>
				<a href="#order-<?php echo $order_info['order_id']; ?>" <?php if (isset($error['cdek_orders'][$order_info['order_id']])) echo 'class="error-tab"'; ?>><?php echo $text_order_n; ?><?php echo $order_info['order_id']; ?></a>
				<?php } ?>
			</div>
				<div class="cdek-orders">
					<?php foreach ($cdek_orders as $row => $order_info) { ?>
					<div class="vtabs-content order" id="order-<?php echo $order_info['order_id']; ?>" data-order="<?php echo $order_info['order_id']; ?>">
						<div id="tabs-order-<?php echo $order_info['order_id']; ?>" class="htabs">
							<a href="#order-<?php echo $order_info['order_id']; ?>-data"><?php echo $tab_data; ?></a>
							<a href="#order-<?php echo $order_info['order_id']; ?>-recipient"><?php echo $tab_recipient; ?></a>
							<a href="#order-<?php echo $order_info['order_id']; ?>-package" <?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'])) echo 'class="error-tab"'; ?>><?php echo $tab_package; ?></a>
							<a href="#order-<?php echo $order_info['order_id']; ?>-schedule" <?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'])) echo 'class="error-tab"'; ?>><?php echo $tab_schedule; ?></a>
							<a href="#order-<?php echo $order_info['order_id']; ?>-courier" <?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier'])) echo 'class="error-tab"'; ?>><?php echo $tab_courier; ?></a>
							<a href="#order-<?php echo $order_info['order_id']; ?>-additional" <?php if (isset($error['cdek_orders'][$order_info['order_id']]['add_service'])) echo 'class="error-tab"'; ?>><?php echo $tab_additional; ?></a>
						</div>
						<div id="order-<?php echo $order_info['order_id']; ?>-data">
							<table class="form">
								<tbody>
									<tr>
										<td><?php echo $text_order_id; ?></td>
										<td>
											<?php echo $order_info['order_id']; ?>
											<input type="hidden" name="cdek_orders[<?php echo $order_info['order_id']; ?>][order_id]" value="<?php echo $order_info['order_id']; ?>" />
										</td>
									</tr>
									<tr>
										<td><?php echo $text_order_total; ?></td>
										<td><?php echo $order_info['total']; ?></td>
									</tr>
									<tr>
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-city-id"><?php if (!$city_default) { ?><span class="required">*</span> <?php } ?><?php echo $text_city; ?></label></td>
										<td>
											<?php if ($city_default) { ?>
											<?php echo $city_name; ?>
											<input type="hidden" name="cdek_orders[<?php echo $order_info['order_id']; ?>][city_id]" value="<?php echo $city_id; ?>" />
											<input type="hidden" name="cdek_orders[<?php echo $order_info['order_id']; ?>][city_name]" value="<?php echo $city_name; ?>" />
											<?php } else { ?>
											<input type="hidden" class="setting-city-id" name="cdek_orders[<?php echo $order_info['order_id']; ?>][city_id]" value="<?php if (!empty($order_info['city_id'])) echo $order_info['city_id']; ?>"/>
											<a class="js city-from<?php if (empty($order_info['city_id'])) echo ' hidden'; ?>"><?php if (!empty($order_info['city_name'])) echo $order_info['city_name']; ?></a>
											<input type="text" id="cdek-orders-<?php echo $order_info['order_id']; ?>-city-id" class="setting-city-name<?php if (!empty($order_info['city_id'])) echo ' hidden'; ?>" name="cdek_orders[<?php echo $order_info['order_id']; ?>][city_name]" value="<?php if (!empty($order_info['city_name'])) echo $order_info['city_name']; ?>" />
											<?php } ?>
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['city_id'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['city_id']; ?></span>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-tariff-id"><span class="required">*</span> <?php echo $entry_tariff; ?></label></td>
										<td>
											<?php if ($order_info['shipping_method']) { ?>
											<p class="help"><?php echo $text_customer_shipping_method; ?>: <?php echo $order_info['shipping_method']; ?></p>
											<?php }?>
											<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-tariff-id" class="tariff_switcher" name="cdek_orders[<?php echo $order_info['order_id']; ?>][tariff_id]">
												<option value=""><?php echo $text_select; ?></option>
												<?php foreach ($tariff_list as $tariff_id => $tariff_info) { ?>
												<option <?php if (!empty($order_info['tariff_id']) && $order_info['tariff_id'] == $tariff_id) echo 'selected="selected"'; ?> data-mode="<?php echo $tariff_info['mode_id']; ?>" value="<?php echo $tariff_id; ?>"><?php echo $tariff_info['title']; ?></option>
												<?php }?>
											</select>
											<input type="hidden" class="setting-mode-id" name="cdek_orders[<?php echo $order_info['order_id']; ?>][mode_id]" value="<?php if (!empty($order_info['mode_id'])) echo $order_info['mode_id']; ?>"/>
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['tariff_id'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['tariff_id']; ?></span>
											<?php } ?>
											<div class="shipping-price"></div>
										</td>
									</tr>
									<tr class="parent">
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-cod"><span class="required">*</span> <?php echo $entry_cod; ?></label></td>
										<td>
											<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-cod" class="cdek-order-cod slider" name="cdek_orders[<?php echo $order_info['order_id']; ?>][cod]">	
												<?php foreach ($boolean_variables as $key => $value) { ?>
												<option <?php if ($order_info['cod'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
												<?php } ?>
											</select>
											<span class="status"><?php echo (empty($order_info['cod'])) ? "&#9660;" : "&#9650;"; ?></span>
										</td>
									</tr>
									<tr>
										<td colspan="2" class="include">
											<div class="slider-content<?php if (empty($order_info['cod'])) echo " hidden"; ?>" >
												<table class="form">
													<tbody>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-currency-cod"><?php echo $entry_currency_cod; ?></label></td>
															<td>
																<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-currency-cod" name="cdek_orders[<?php echo $order_info['order_id']; ?>][currency_cod]">												
																	<?php foreach ($currency_list as $key => $value) { ?>
																	<option <?php if (isset($order_info['currency_cod']) && $order_info['currency_cod'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
																	<?php } ?>
																</select>
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['currency_cod'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['currency_cod']; ?></span>
																<?php } ?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-delivery-recipient-cost"><?php echo $entry_delivery_recipient_cost; ?></label></td>
										<td>
											<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-delivery-recipient-cost" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][delivery_recipient_cost]" value="<?php if (!empty($order_info['delivery_recipient_cost'])) echo $order_info['delivery_recipient_cost']; ?>" size="2" />
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['delivery_recipient_cost'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['delivery_recipient_cost']; ?></span>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-seller-name"><?php echo $entry_seller_name; ?></label></td>
										<td>
											<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-seller-name" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][seller_name]" value="<?php if (!empty($order_info['seller_name'])) echo $order_info['seller_name']; ?>" maxlength="255" />
										</td>
									</tr>
									<tr>
										<td style="vertical-align:top"><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-cdek-comment"><?php echo $entry_comment; ?></label></td>
										<td>
											<?php if (!empty($order_info['comment'])) { ?>
											<div class="help user-comment">
												<p class="user-comment-title"><?php echo $text_user_comment; ?></p>
												<?php echo $order_info['comment']; ?>
											</div>
											<?php } ?>
											<textarea id="cdek-orders-<?php echo $order_info['order_id']; ?>-cdek-comment" rows="5" cols="50" name="cdek_orders[<?php echo $order_info['order_id']; ?>][cdek_comment]" ><?php if (!empty($order_info['cdek_comment'])) echo $order_info['cdek_comment']; ?></textarea>
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['cdek_comment'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['cdek_comment']; ?></span>
											<?php } ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="order-<?php echo $order_info['order_id']; ?>-recipient" class="recipient">
							<table class="form">
								<tbody>
									<tr>
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-name"><span class="required">*</span> <?php echo $entry_recipient_name; ?></label></td>
										<td>
											<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-name" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][recipient_name]" value="<?php echo $order_info['recipient_name']; ?>" maxlength="128" />
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['recipient_name'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['recipient_name']; ?></span>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-telephone"><span class="required">*</span> <?php echo $entry_recipient_telephone; ?></label></td>
										<td>
											<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-telephone" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][recipient_telephone]" value="<?php echo $order_info['recipient_telephone']; ?>" maxlength="50" />
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['recipient_telephone'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['recipient_telephone']; ?></span>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-email"><?php echo $entry_recipient_email; ?></label></td>
										<td>
											<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-email" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][recipient_email]" value="<?php echo $order_info['recipient_email']; ?>" maxlength="255" />
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['recipient_email'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['recipient_email']; ?></span>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<h3><?php echo $text_shipping_address; ?></h3>
											<?php if ($order_info['shipping_address']) { ?>
											<p class="help"><?php echo $text_customer_shipping_address; ?>: <?php echo $order_info['shipping_address']; ?></p>
											<?php } ?>
										</td>
									</tr>
									<tr class="parent">
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-city-name"><span class="required">*</span> <?php echo $entry_recipient_city; ?></label></td>
										<td>
											<input type="hidden" class="setting-city-id" name="cdek_orders[<?php echo $order_info['order_id']; ?>][recipient_city_id]" value="<?php if (!empty($order_info['recipient_city_id'])) echo $order_info['recipient_city_id']; ?>"/>
											<a class="js city-from<?php if (empty($order_info['recipient_city_id'])) echo ' hidden'; ?>"><?php if (!empty($order_info['recipient_city_name'])) echo $order_info['recipient_city_name']; ?></a>
											<input type="text" id="cdek-orders-<?php echo $order_info['order_id']; ?>-recipient-city-name" class="setting-city-name<?php if (!empty($order_info['recipient_city_id'])) echo ' hidden'; ?>" name="cdek_orders[<?php echo $order_info['order_id']; ?>][recipient_city_name]" value="<?php if (!empty($order_info['recipient_city_name'])) echo $order_info['recipient_city_name']; ?>" />
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['recipient_city_id'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['recipient_city_id']; ?></span>
											<?php } ?>
										</td>
									</tr>
									<tr>
										<td colspan="2" class="include">
											<div class="slider-content">
												<div class="address-mode pvz<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(1, 3))) echo ' hidden';?>">
													<table class="form">
														<tbody>
															<tr>
																<td><span class="required">*</span> <?php echo $entry_pvz; ?></td>
																<td>
																	<select name="cdek_orders[<?php echo $order_info['order_id']; ?>][address][pvz_code]" class="address-pvz-code">
																		<option value=""><?php echo $text_select; ?></option>
																		<?php if (!empty($order_info['pvz_list'])) { ?>
																		<?php foreach ($order_info['pvz_list'] as $pvz_info) { ?>
																		<option <?php if (!empty($order_info['address']['pvz_code']) && $order_info['address']['pvz_code'] == $pvz_info['Code']) echo 'selected="selected"'; ?> value="<?php echo $pvz_info['Code']; ?>" data-name="<?php echo $pvz_info['Name']; ?>" data-y="<?php echo $pvz_info['y']; ?>" data-x="<?php echo $pvz_info['x']; ?>" data-address="<?php echo $pvz_info['Address']; ?>" data-worktime="<?php echo $pvz_info['WorkTime']; ?>" data-phone="<?php echo $pvz_info['Phone']; ?>"><?php echo $pvz_info['Name']; ?> (<?php echo $pvz_info['Address']; ?>)</option>
																		<?php }?>
																		<?php }?>
																	</select>
																	<div class="pvz-wrapper">
																		<?php if (!empty($order_info['pvz_info'])) { ?>
																		<p><?php echo $order_info['pvz_info']['Name']; ?><?php if ($order_info['pvz_info']['x']) { ?> (<a href="http://maps.google.ru/maps?q=<?php echo $order_info['pvz_info']['y']; ?>,<?php echo $order_info['pvz_info']['x']; ?>" target="_blank">на карте</a>)<?php } ?></p>
																		<span class="help">
																		<strong>Адрес</strong>: <?php echo $order_info['pvz_info']['Address']; ?><br />
																		<?php if (!empty($order_info['pvz_info']['Phone']) && trim($order_info['pvz_info']['Phone']) != '-') {?>
																		<strong>Телефон</strong>: <?php echo $order_info['pvz_info']['Phone']; ?><br />
																		<?php } ?>
																		<?php if (!empty( $order_info['pvz_info']['WorkTime']) && trim($order_info['pvz_info']['WorkTime']) != '-') { ?>
																		<strong>Режим работы</strong>: <?php echo $order_info['pvz_info']['WorkTime']; ?>
																		<?php } ?>
																		
																		</span>
																		<?php } ?>
																	</div>
																	<?php if (isset($error['cdek_orders'][$order_info['order_id']]['address']['pvz_code'])) { ?>
																	<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['address']['pvz_code']; ?></span>
																	<?php } ?>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="address-mode address">
													<table class="form">
														<tbody>
															<tr>
																<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-address-street"><span class="required<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(2, 4))) echo ' hidden';?>">*</span> <?php echo $entry_street; ?></label></td>
																<td>
																	<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-address-street" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][address][street]" value="<?php if (!empty($order_info['address']['street'])) echo $order_info['address']['street']; ?>" maxlength="50" />
																	<?php if (isset($error['cdek_orders'][$order_info['order_id']]['address']['street'])) { ?>
																	<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['address']['street']; ?></span>
																	<?php } ?>
																</td>
															</tr>
															<tr>
																<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-address-house"><span class="required<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(2, 4))) echo ' hidden';?>">*</span> <?php echo $entry_house; ?></label></td>
																<td>
																	<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-address-house" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][address][house]" value="<?php if (!empty($order_info['address']['house'])) echo $order_info['address']['house']; ?>" size="2" maxlength="30" />
																	<?php if (isset($error['cdek_orders'][$order_info['order_id']]['address']['house'])) { ?>
																	<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['address']['house']; ?></span>
																	<?php } ?>
																</td>
															</tr>
															<tr>
																<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-address-flat"><span class="required<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(2, 4))) echo ' hidden';?>">*</span> <?php echo $entry_flat; ?></label></td>
																<td>
																	<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-address-flat" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][address][flat]" value="<?php if (!empty($order_info['address']['flat'])) echo $order_info['address']['flat']; ?>" size="2" maxlength="10" />
																	<?php if (isset($error['cdek_orders'][$order_info['order_id']]['address']['flat'])) { ?>
																	<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['address']['flat']; ?></span>
																	<?php } ?>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="order-<?php echo $order_info['order_id']; ?>-package">
							<?php if (count($order_info['packages']) > 1) { ?>
							<div id="vtabs-order-<?php echo $order_info['order_id']; ?>-package" class="htabs">
								<?php foreach ($order_info['packages'] as $package_id => $package_info) { ?>
								<a href="#order-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>"><?php echo $text_package_n; ?><?php echo $package_id; ?></a>
								<?php } ?>
							</div>
							<?php } ?>
							<?php foreach ($order_info['packages'] as $package_id => $package_info) { ?>
							<div id="order-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>">
								<?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['warning'])) { ?>
								<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['warning']; ?></span>
								<?php } ?>
								<br />
								<table class="form">
									<tbody>
										<tr>
											<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-currency"><?php echo $entry_currency; ?></label></td>
											<td>
												<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-currency" name="cdek_orders[<?php echo $order_info['order_id']; ?>][currency]">												
													<?php foreach ($currency_list as $key => $value) { ?>
													<option <?php if (isset($order_info['currency']) && $order_info['currency'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-brcode"><?php echo $entry_brcode; ?></label></td>
											<td><input id="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-brcode" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][brcode]" value="<?php if (!empty($package_info['brcode'])) echo $package_info['brcode']; ?>" maxlength="20" /></td>
										</tr>
										<tr class="parent">
											<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-pack"><?php echo $entry_pack; ?></label></td>
											<td>
												<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-pack" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][pack]" class="slider">	
													<?php foreach ($boolean_variables as $key => $value) { ?>
													<option <?php if (isset($package_info['pack']) && $package_info['pack'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
													<?php } ?>
												</select>
												<span class="status"><?php echo (empty($package_info['pack']) || !$package_info['pack']) ? "&#9660;" : "&#9650;"; ?></span>
											</td>
										</tr>
										<tr>
											<td colspan="2" class="include">
												<div class="slider-content<?php if (!isset($package_info['pack']) || !$package_info['pack']) echo " hidden"; ?>" >
													<table class="form">
														<tbody>
															<tr>
																<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-size-a"><span class="required">*</span> <?php echo $entry_package; ?></label></td>
																<td>
																	<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-size-a" type="text" placeholder="<?php echo $text_short_length; ?>" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][size_a]" value="<?php if (!empty($package_info['size_a'])) echo $package_info['size_a']; ?>" size="2" /> x 
																	<input type="text" placeholder="<?php echo $text_short_width; ?>" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][size_b]" value="<?php if (!empty($package_info['size_b'])) echo $package_info['size_b']; ?>" size="2" /> x 
																	<input type="text" placeholder="<?php echo $text_short_height; ?>" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][size_c]" value="<?php if (!empty($package_info['size_c'])) echo $package_info['size_c']; ?>" size="2" />
																	<?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['size'])) { ?>
																	<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['size']; ?></span>
																	<?php } ?>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
										<tr>
											<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-weight"><span class="required">*</span> <?php echo $entry_order_weight; ?></label></td>
											<td>
												<input class="package-weight" id="cdek-orders-<?php echo $order_info['order_id']; ?>-package-<?php echo $package_id; ?>-weight" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][weight]" value="<?php echo $package_info['weight']; ?>" data-packing_prefix="<?php echo $package_info['additional_weight']['prefix']; ?>" data-packing_weight="<?php echo $package_info['additional_weight']['weight']; ?>" />
												<?php if ((float)$package_info['additional_weight']['weight']) { ?>
												<span class="help inline"><?php echo $package_info['additional_weight']['prefix'] . ' ' . $package_info['additional_weight']['weight']; ?> грамм</span>
												<?php } ?>
												<?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['weight'])) { ?>
												<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['weight']; ?></span>
												<?php } ?>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="list package-items">
									<thead>
										<tr>
											<td class="left"><?php echo $column_title; ?></td>
											<td class="right"><?php echo $column_weight; ?></td>
											<td class="right"><?php echo $column_price; ?></td>
											<td class="right"><?php echo $column_payment; ?></td>
											<td class="right"><?php echo $column_amount; ?></td>
											<td class="right"><?php echo $column_cost; ?></td>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($package_info['item'] as $row => $item) { ?>
										<tr>
											<td class="left">
												<?php echo $item['name']; ?>
												<?php foreach ($item['option'] as $option) { ?>
												<br />
												<?php if ($option['type'] != 'file') { ?>
												&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
												<?php } else { ?>
												&nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
												<?php } ?>
												<?php } ?>
												<input type="hidden" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][item][<?php echo $row; ?>][comment]" value="<?php echo $item['name']; ?>" size="3" />
												<input type="hidden" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][item][<?php echo $row; ?>][ware_key]" value="<?php echo $item['order_product_id']; ?>" size="3" />
											</td>
											<td class="right">
												<input type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][item][<?php echo $row; ?>][weight]" value="<?php echo $item['weight']; ?>" size="5" />
												<?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['weight'])) { ?>
												<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['weight']; ?></span>
												<?php } ?>
											</td>
											<td class="right">
												<input type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][item][<?php echo $row; ?>][cost]" value="<?php echo $item['price']; ?>" size="3" />
												<?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['cost'])) { ?>
												<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['cost']; ?></span>
												<?php } ?>
											</td>
											<td class="right package-item-payment">
												<input type="text" <?php if (!$order_info['cod']) echo ' readonly="readonly" '; ?> name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][item][<?php echo $row; ?>][payment]" data-value="<?php echo $item['payment']; ?>" value="<?php echo ($order_info['cod'] ? $item['payment'] : 0); ?>" size="3" />
												<?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['payment'])) { ?>
												<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['payment']; ?></span>
												<?php } ?>
											</td>
											<td class="right">
												<input type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][package][<?php echo $package_id; ?>][item][<?php echo $row; ?>][amount]" value="<?php echo $item['quantity']; ?>" size="3" />
												<?php if (isset($error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['amount'])) { ?>
												<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['package'][$package_id]['item'][$row]['amount']; ?></span>
												<?php } ?>
											</td>
											<td class="right"><?php echo $item['total']; ?></td>
										</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<?php foreach ($order_info['totals'] as $row =>  $order_total) { ?>
										<tr>
											<td class="right <?php echo $order_total['code']; ?>" colspan="5"><?php echo $order_total['title']; ?></td>
											<td class="right <?php echo $order_total['code']; ?>"><?php echo $order_total['text']; ?></td>
										</tr>
										<?php } ?>
									</tfoot>
								</table>
							</div>
							<?php } ?>
						</div>
						<div id="order-<?php echo $order_info['order_id']; ?>-schedule">
							<table class="form">
								<tbody>
									<tr>
										<td colspan="2">
											<span class="help"><?php echo $text_help_shedule; ?></span>
											<br />
											<span class="help"><em class="red"><?php echo $text_attention; ?></em></span>
											<?php echo $text_help_shedule_detail; ?>
										</td>
									</tr>
									<tr>
										<td colspan="2"><h4><?php echo $text_title_schedule; ?></h4></td>
									</tr>
								</tbody>
							</table>
							<table class="list">
								<thead>
									<tr>
										<td class="left"><span class="required">*</span> <?php echo $column_date; ?></td>
										<td class="left"><span class="required">*</span> <?php echo $column_time; ?></td>
										<td class="left"><?php echo $entry_comment; ?></td>
										<td class="left" style="min-width: 300px;"><?php echo $column_additional; ?></td>
										<td class="left"></td>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($order_info['schedule'])) { ?>
									<?php foreach ($order_info['schedule'] as $attempt_row => $attempt_info) { ?>
									<tr id="<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>" rel="<?php echo $attempt_row; ?>">
										<td class="left">
											<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-date" class="date" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][date]" value="<?php if (!empty($attempt_info['date'])) echo $attempt_info['date']; ?>" size="8" />
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['date'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['date']; ?></span>
											<?php } ?>
										</td>
										<td class="left">
											<nobr><?php echo $text_from; ?> <input id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-time-beg" class="time" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][time_beg]" value="<?php if (!empty($attempt_info['time_beg'])) echo $attempt_info['time_beg']; ?>" size="8" /> <?php echo $text_to; ?> <input class="time" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][time_end]" value="<?php if (!empty($attempt_info['time_end'])) echo $attempt_info['time_end']; ?>" size="8" /></nobr>
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['time'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['time']; ?></span>
											<?php } ?>
										</td>
										<td class="left">
											<textarea id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-comment" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][comment]" rows="5" cols="50" ><?php if (!empty($attempt_info['comment'])) echo $attempt_info['comment']; ?></textarea>
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['comment'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['comment']; ?></span>
											<?php } ?>
										</td>
										<td class="no-padding">
											<table class="form"> 
												<tbody>
													<tr>
														<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-recipient-name"><?php echo $entry_attempt_recipient_name; ?></label></td>
														<td>
															<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-recipient-name" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][recipient_name]" value="<?php if (!empty($attempt_info['recipient_name'])) echo $attempt_info['recipient_name']; ?>" maxlength="128" />
															
														</td>
													</tr>
													<tr>
														<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-phone"><?php echo $entry_attempt_phone; ?></label></td>
														<td>
															<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-phone" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][phone]" value="<?php if (!empty($attempt_info['phone'])) echo $attempt_info['phone']; ?>" maxlength="50" />
														</td>
													</tr>
													<tr class="parent">
														<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-new-address"><?php echo $entry_attempt_new_address; ?></label></td>
														<td>
															<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-new-address" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][new_address]" class="slider">	
																<?php foreach ($boolean_variables as $key => $value) { ?>
																<option <?php if (isset($attempt_info['new_address']) && $attempt_info['new_address'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
																<?php } ?>
															</select>
															<span class="status"><?php echo (!isset($attempt_info['new_address']) || !$attempt_info['new_address']) ? "&#9660;" : "&#9650;"; ?></span>
														</td>
													</tr>
													<tr>
														<td colspan="2" class="include">
															<div class="slider-content<?php if (!isset($attempt_info['new_address']) || !$attempt_info['new_address']) echo ' hidden'; ?>">
																<div class="address-mode pvz<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(1, 3))) echo ' hidden'; ?>">
																	<table class="form">
																		<tbody>
																			<tr>
																				<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-pvz-code"><span class="required">*</span> <?php echo $entry_pvz; ?></label></td>
																				<td>
																					<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-pvz-code" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][pvz_code]">
																						<option value=""><?php echo $text_select; ?></option>
																						<?php if (!empty($order_info['pvz_list'])) { ?>
																						<?php foreach ($order_info['pvz_list'] as $pvz_info) { ?>
																						<option <?php if (!empty($attempt_info['pvz_code']) &&$attempt_info['pvz_code'] == $pvz_info['Code']) echo 'selected="selected"'; ?> value="<?php echo $pvz_info['Code']; ?>"><?php echo $pvz_info['Name']; ?> (<?php echo $pvz_info['Address']; ?>)</option>
																						<?php }?>
																						<?php }?>
																					</select>
																					<?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['pvz_code'])) { ?>
																					<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['pvz_code']; ?></span>
																					<?php } ?>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
																<div class="address-mode address">
																	<table class="form">
																		<tbody>
																			<tr>
																				<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-street"><span class="required<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(2, 4))) echo ' hidden';?>">*</span> <?php echo $entry_street; ?></label></td>
																				<td>
																					<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-street" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][street]" value="<?php if (!empty($attempt_info['street'])) echo $attempt_info['street']; ?>" maxlength="50" />
																					<?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['street'])) { ?>
																					<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['street']; ?></span>
																					<?php } ?>
																				</td>
																			</tr>
																			<tr>
																				<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-house"><span class="required<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(2, 4))) echo ' hidden';?>">*</span> <?php echo $entry_house; ?></label></td>
																				<td>
																					<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-house" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][house]" value="<?php if (!empty($attempt_info['house'])) echo $attempt_info['house']; ?>" size="2" maxlength="30" />
																					<?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['house'])) { ?>
																					<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['house']; ?></span>
																					<?php } ?>
																				</td>
																			</tr>
																			<tr>
																				<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-flat"><span class="required<?php if (empty($order_info['tariff_id']) || empty($tariff_list[$order_info['tariff_id']]['mode_id']) || in_array($tariff_list[$order_info['tariff_id']]['mode_id'], array(2, 4))) echo ' hidden';?>">*</span> <?php echo $entry_flat; ?></label></td>
																				<td>
																					<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>-flat" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][schedule][<?php echo $attempt_row; ?>][flat]" value="<?php if (!empty($attempt_info['flat'])) echo $attempt_info['flat']; ?>" size="2" maxlength="10" />
																					<?php if (isset($error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['flat'])) { ?>
																					<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['schedule'][$attempt_row]['flat']; ?></span>
																					<?php } ?>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="right" width="1">
											<a onclick="$('#<?php echo $order_info['order_id']; ?>-schedule-<?php echo $attempt_row; ?>').remove();" class="button"><?php echo $button_delete; ?></a>
										</td>
									</tr>
									<?php } ?>
									<?php } ?>
								</tbody>
							</table>
							<a class="button" onclick="addAttempt(<?php echo $order_info['order_id']; ?>);"><?php echo $button_add_attempt; ?></a>
						</div>
						<div id="order-<?php echo $order_info['order_id']; ?>-courier">
							<table class="form">
								<tbody>
									<tr>
										<td colspan="2">
											<span class="help"><em class="red"><?php echo $text_attention; ?></em></span>
											<ul class="help">
												<li>Вызов курьера возможно сделать только на будущую дату;</li>
												<li>На один день возможно не более одного вызова курьера на один адрес;</li>
												<li><?php echo $text_courier_hour_range; ?>.</li>
											</ul>
										</td>
									</tr>
									<tr class="parent">
										<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-call"><?php echo $entry_courier_call; ?></label></td>
										<td>
											<select id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-call" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][call]" class="slider">	
												<?php foreach ($boolean_variables as $key => $value) { ?>
												<option <?php if (isset($order_info['courier']['call']) && $order_info['courier']['call'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
												<?php } ?>
											</select>
											<span class="status"><?php echo (empty($order_info['courier']['call']) || !$order_info['courier']['call']) ? "&#9660;" : "&#9650;"; ?></span>
										</td>
									</tr>
									<tr>
										<td colspan="2" class="include">
											<div class="slider-content<?php if (!isset($order_info['courier']['call']) || !$order_info['courier']['call']) echo " hidden"; ?>" >
												<table class="form">
													<tbody>
														<tr>
															<td colspan="2"><h4><?php echo $text_courier; ?></h4></td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-date"><span class="required">*</span> <?php echo $entry_courier_date; ?></label></td>
															<td>
																<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-date" class="date" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][date]" value="<?php if (!empty($order_info['courier']['date'])) echo $order_info['courier']['date']; ?>" size="8" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['date'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['date']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-time-beg"><span class="required">*</span> <?php echo $entry_courier_time; ?></label></td>
															<td>
																<span class="text-from"><?php echo $text_from; ?></span> <input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-time-beg" class="time" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][time_beg]" value="<?php if (!empty($order_info['courier']['time_beg'])) echo $order_info['courier']['time_beg']; ?>" size="8" /> <?php echo $text_to; ?> <input class="time" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][time_end]" value="<?php if (!empty($order_info['courier']['time_end'])) echo $order_info['courier']['time_end']; ?>" size="8" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['time'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['time']; ?></span>
																<?php } ?>
															</td>
														</tr>
														
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-lunch-beg"><?php echo $entry_courier_lunch; ?></label></td>
															<td>
																<span class="text-from"><?php echo $text_from; ?></span> <input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-lunch-beg" class="time" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][lunch_beg]" value="<?php if (!empty($order_info['courier']['lunch_beg'])) echo $order_info['courier']['lunch_beg']; ?>" size="8" /> <?php echo $text_to; ?> <input class="time" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][lunch_end]" value="<?php if (!empty($order_info['courier']['lunch_end'])) echo $order_info['courier']['lunch_end']; ?>" size="8" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['lunch'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['lunch']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td colspan="2"><h4><?php echo $text_courier_address; ?></h4></td>
														</tr>
														<tr>
															<td><?php if (!$city_default) { ?><span class="required">*</span> <?php } ?><?php echo $text_city; ?></td>
															<td>
																<?php if ($city_default) { ?>
																<?php echo $city_name; ?>
																<input type="hidden" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][city_name]" value="<?php echo $city_name; ?>" />
																<input type="hidden" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][city_id]" value="<?php echo $city_id; ?>" />
																<?php } else { ?>
																<input type="hidden" class="setting-city-id" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][city_id]" value="<?php if (!empty($order_info['courier']['city_id'])) echo $order_info['courier']['city_id']; ?>" />
																<a class="js city-from" <?php if (empty($order_info['courier']['city_id'])) echo ' hidden'; ?>><?php if (!empty($order_info['courier']['city_name'])) echo $order_info['courier']['city_name']; ?></a>
																<input type="text" class="setting-city-name<?php if (!empty($order_info['courier']['city_id'])) echo ' hidden'; ?>" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][city_name]" value="<?php if (!empty($order_info['courier']['city_name'])) echo $order_info['courier']['city_name']; ?>" />
																<?php } ?>
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['city_id'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['city_id']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-street"><span class="required">*</span> <?php echo $entry_street; ?></label></td>
															<td>
																<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-street" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][street]" value="<?php if (!empty($order_info['courier']['street'])) echo $order_info['courier']['street']; ?>" maxlength="50" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['street'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['street']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-house"><span class="required">*</span> <?php echo $entry_house; ?></label></td>
															<td>
																<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-house" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][house]" value="<?php if (!empty($order_info['courier']['house'])) echo $order_info['courier']['house']; ?>" size="2" maxlength="30" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['house'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['house']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-flat"><span class="required">*</span> <?php echo $entry_flat; ?></label></td>
															<td>
																<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-flat" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][flat]" value="<?php if (!empty($order_info['courier']['flat'])) echo $order_info['courier']['flat']; ?>" size="2" maxlength="10" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['flat'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['flat']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-send-phone"><span class="required">*</span> <?php echo $entry_courier_send_phone; ?></label></td>
															<td>
																<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-send-phone" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][send_phone]" value="<?php if (!empty($order_info['courier']['send_phone'])) echo $order_info['courier']['send_phone']; ?>" maxlength="255" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['send_phone'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['send_phone']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-send-name"><span class="required">*</span> <?php echo $entry_courier_sender_name; ?></label></td>
															<td>
																<input id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-send-name" type="text" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][sender_name]" value="<?php if (!empty($order_info['courier']['sender_name'])) echo $order_info['courier']['sender_name']; ?>" maxlength="255" />
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['sender_name'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['sender_name']; ?></span>
																<?php } ?>
															</td>
														</tr>
														<tr>
															<td><label for="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-comment"><?php echo $entry_comment; ?></label></td>
															<td>
																<textarea id="cdek-orders-<?php echo $order_info['order_id']; ?>-courier-comment" name="cdek_orders[<?php echo $order_info['order_id']; ?>][courier][comment]" rows="5" cols="50" ><?php if (!empty($order_info['courier']['comment'])) echo $order_info['courier']['comment']; ?></textarea>
																<?php if (isset($error['cdek_orders'][$order_info['order_id']]['courier']['comment'])) { ?>
																<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['courier']['comment']; ?></span>
																<?php } ?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="order-<?php echo $order_info['order_id']; ?>-additional">
							<table class="form">
								<tbody>
									<tr>
										<td><?php echo $entry_add_service; ?></td>
										<td>
											<div class="scrollbox cervices">
												<?php $class = 'even'; ?>
												<?php $checked = array(); ?>
												<?php if (!empty($order_info['add_service'])) { ?>
												<?php foreach ($order_info['add_service'] as $service_id => $cervice_info) $checked[] = $service_id; ?>
												<?php } ?>
												<?php foreach ($add_cervices as $cervice_code => $cervice_info) { ?>
												<?php if (isset($cervice_info['hide'])) continue; ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<input type="checkbox" name="cdek_orders[<?php echo $order_info['order_id']; ?>][add_service][<?php echo $cervice_code; ?>][service_id]" value="<?php echo $cervice_code; ?>" <?php if (in_array($cervice_code, $checked)) echo 'checked="checked"'; ?> />
													<div class="item-description">
														<?php echo $cervice_info['title']; ?><span class="help"><?php echo $cervice_info['description']; ?></span>
													</div>
												</div>
												<?php } ?>
											</div>
											<?php if (isset($error['cdek_orders'][$order_info['order_id']]['add_service'])) { ?>
											<span class="error"><?php echo $error['cdek_orders'][$order_info['order_id']]['add_service']; ?></span>
											<?php } ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--

$('.cdek-order-cod').change(function(){
	
	var cod_enable = $(this).val();
	var context = $(this).closest('.vtabs-content');
	var input = $('table.package-items td.package-item-payment input:text', context);
	
	if (cod_enable == 1) {
		
		$(input).val($(input).data('value'));
		$(input).attr('readonly', '').removeAttr('readonly');
		
	} else {
		
		$(input).data('value', $(input).val());
		$(input).val(0);
		$(input).attr('readonly', 'readonly');
	}
	
});

$('input.package-weight').live('blur keyup', function(){
	
	var self = this;
	var context = $(self).closest('td');
	var weight = $(self).val();
	
	if (!isNaN(weight) && weight > 0) {
		
		$.ajax({
			url: "index.php?route=module/cdek_integrator/getAjaxPackingWeight&token=<?php echo $token; ?>&weight=" + weight,
			dataType: "json",
			beforeSend: function(jqXHR, settings){
				$('.help', context).text('');
				if (!$('.loader', context).length) $(self).after('<img class="loader" src="view/image/cdek_integrator/loader.gif" alt="Загрузка..." title="Загрузка..." />');
			},
			complete: function(jqXHR, textStatus) {
				$('.loader', context).remove();
			},
			success: function(json) {
				
				if (json.packing_weight) {
					
					$(self).data('packing_prefix', json.packing_weight.prefix);
					$(self).data('packing_weight', json.packing_weight.weight);
					
					$('.help', context).text(json.packing_weight.prefix + ' ' + json.packing_weight.weight + ' грамм');
				}
				
			}
		});
		
	} else {
		$('.help', context).text('');
	}
	
});

$('.tariff_switcher').change(function(event){
	
	var context = $(this).closest('.vtabs-content');
	
	$('.address-mode.pvz', context).hide();
	
	if ($(this).val() != 0) {
		
		var mode_id = $('option:selected', this).data('mode');
		
		if (mode_id == 1 || mode_id == 3) {
			$('.address-mode.address .required', context).show();
		} else {
			$('.address-mode.pvz', context).show();
			$('.address-mode.address', context).show();
			$('.address-mode.address .required', context).hide();
		}
		
		$('.setting-mode-id', context).val(mode_id);
		
		//$('.address-mode' + className, context).show();
		$(this).closest('td').find('.error').remove();
	}
	
	getShippingPrice($(context).data('order'));
});

$(".setting-city-name").bind('cityselect', function(){
	
	var context = $(this).closest('tbody');
	
	var city_code = $('input.setting-city-id', context).val();
	
	if (city_code != '') {
		
		var element = $('.address-mode.pvz select', context);
		
		$('option:gt(0)', element).remove();
	
		$.ajax({
			url: "index.php?route=module/cdek_integrator/getPVZByCity&token=<?php echo $token; ?>&city_code=" + city_code,
			dataType: "json",
			beforeSend: function(jqXHR, settings){
				if (!$('.address-mode.pvz .loader', context).length) $(element).after('<img class="loader" src="view/image/cdek_integrator/loader.gif" alt="Загрузка..." title="Загрузка..." />');
			},
			complete: function(jqXHR, textStatus) {
				$('.address-mode.pvz .loader', context).remove();
			},
			success: function(json) {
				
				if (json.List) {
					
					var html = '';
					
					for (var row in json.List) {
						html += '<option value="' + json.List[row].Code + '" data-name="' + json.List[row].Name + '" data-y="' + json.List[row].y + '" data-x="' + json.List[row].x + '" data-address="' + json.List[row].Address + '" data-worktime="' + json.List[row].WorkTime + '" data-phone="' + json.List[row].Phone + '">' + json.List[row].Address + + '</option>';
					}
					
					if (html != '') {
						$(element).append(html);
						
					}
				}
				
				$(element).trigger('change');
			}
		});
	}
	
});

function addAttempt(order_id) {
	
	var context = $('#order-' + order_id + '-schedule table.list tbody:first');
	
	if (!$(context).length) {
		return;
	}
	
	var attemp_row = getAttempRow(context);
	
	var tariff_context = $('#cdek-orders-' + order_id + '-tariff-id');
	
	var mode_id = 2;
	
	if ($(tariff_context).length) {
		
		var tariff_id = $(tariff_context).val();
		
		if (tariff_id != '') {
			mode_id = $('option[value=' + tariff_id + ']', tariff_context).attr('data-mode');
		}
		
	}
	
	var html  = '<tr id="' + order_id + '-schedule-' + attemp_row + '" rel="' + attemp_row + '">';
	html +=		'	<td class="left"><input id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-date" class="date" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][date]" value="" size="8" /></td>';
	html +=		'	<td class="left">';
	html +=		'		<nobr><?php echo $text_from; ?> <input id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-time-beg" class="time" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][time_beg]" value="" size="8" /> <?php echo $text_to; ?> <input class="time" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][time_end]" value="" size="8" /></nobr>';
	html +=		'	</td>';
	html +=		'	<td class="left"><textarea id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-comment" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][comment]" rows="5" cols="50" ></textarea></td>';
	html +=		'	<td class="no-padding">';
	html +=		'		<table class="form">';
	html +=		'			<tbody>';
	html +=		'				<tr>';
	html +=		'					<td><label for="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-recipient-name"><?php echo $entry_attempt_recipient_name; ?></label></td>';
	html +=		'					<td><input id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-recipient-name" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][recipient_name]" value="" maxlength="128" /></td>';
	html +=		'				</tr>';
	html +=		'				<tr>';
	html +=		'					<td><label for="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-phone"><?php echo $entry_attempt_phone; ?></label></td>';
	html +=		'					<td><input id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-phone" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][phone]" value="" maxlength="50" /></td>';
	html +=		'				</tr>';
	html +=		'				<tr class="parent">';
	html +=		'					<td><label for="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-new-address"><?php echo $entry_attempt_new_address; ?></label></td>';
	html +=		'					<td>';
	html +=		'						<select id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-new-address" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][new_address]" class="slider">';
	<?php foreach ($boolean_variables as $key => $value) { ?>
	html +=		'							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>';
	<?php } ?>
	html +=		'						</select>';
	html +=		'						<span class="status">&#9660;</span>';
	html +=		'					</td>';
	html +=		'				</tr>';
	html +=		'				<tr>';
	html +=		'					<td colspan="2" class="include">';
	html +=		'						<div class="slider-content hidden">';
	html +=		'							<div class="address-mode pvz' + (mode_id == 1 || mode_id == 3 ? ' hidden' : '') + '">';
	html +=		'								<table class="form">';
	html +=		'									<tbody>';
	html +=		'										<tr>';
	html +=		'											<td><label for="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-pvz-code"><span class="required">*</span> <?php echo $entry_pvz; ?></label></td>';
	html +=		'											<td>';
	html +=		'												<select id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-pvz-code" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][pvz_code]">';
	
	$('#order-' + order_id + '-recipient .address-mode.pvz select option').each(function(){
		html +=	'													<option value="' + $(this).val() + '">' + $(this).text() + '</option>';
	});
	
	html +=		'												</select>';
	html +=		'											</td>';
	html +=		'										</tr>';
	html +=		'									</tbody>';
	html +=		'								</table>';
	html +=		'							</div>';
	html +=		'							<div class="address-mode address">';
	html +=		'								<table class="form">';
	html +=		'									<tbody>';
	html +=		'										<tr>';
	html +=		'											<td><label for="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-street"><span class="required' + (mode_id == 2 || mode_id == 4 ? ' hidden' : '') + '">*</span> <?php echo $entry_street; ?></label></td>';
	html +=		'											<td><input id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-street" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][street]" value="" maxlength="50" /></td>';
	html +=		'										</tr>';
	html +=		'										<tr>';
	html +=		'											<td><label for="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-house"><span class="required' + (mode_id == 2 || mode_id == 4 ? ' hidden' : '') + '">*</span> <?php echo $entry_house; ?></label></td>';
	html +=		'											<td><input id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-house" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][house]" value="" size="2" maxlength="30" /></td>';
	html +=		'										</tr>';
	html +=		'										<tr>';
	html +=		'											<td><label for="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-flat"><span class="required' + (mode_id == 2 || mode_id == 4 ? ' hidden' : '') + '">*</span> <?php echo $entry_flat; ?></label></td>';
	html +=		'											<td><input id="cdek-orders-' + order_id + '-schedule-' + attemp_row + '-flat" type="text" name="cdek_orders[' + order_id + '][schedule][' + attemp_row + '][flat]" value="" size="2" maxlength="10" /></td>';
	html +=		'										</tr>';
	html +=		'									</tbody>';
	html +=		'								</table>';
	html +=		'							</div>';
	html +=		'						</div>';
	html +=		'					</td>';
	html +=		'				</tr>';
	html +=		'			</tbody>';
	html +=		'		</table>';
	html +=		'	</td>';
	html +=		'	<td class="right" width="1">';
	html +=		'		<a onclick="$(\'#' + order_id + '-schedule-' + attemp_row + '\').remove();" class="button"><?php echo $button_delete; ?></a>';
	html +=		'	</td>';
	html +=		'</tr>';
	
	$(context).append(html);
	
	initTimepicker();
}

function getAttempRow(context) {
	
	var row = exists = 0;
	
	$('tr[rel]', context).each(function(){
		
		var rel = $(this).attr('rel');
		
		if (rel > row) {
			row = rel;
		}
		
		exists = true;
		
	});
	
	return exists ? ++row : row;
}

function initTimepicker() {
	
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.time').timepicker({timeFormat: 'hh:mm'});
	
}

initTimepicker();

$('.cdek-orders .vtabs-content').each(function(i, el){
	getShippingPrice($(el).data('order'));
});

$('input.package-weight').change(function(event){
	getShippingPrice($(this).closest('.vtabs-content').data('order'));
});

$('input.setting-city-id').change(function(event){
	getShippingPrice($(this).closest('.vtabs-content').data('order'));
});

$('select.address-pvz-code').change(function(event){
	
	$(this).parent().find('.pvz-wrapper').html('');
	$(this).parent().find('.error').remove();
	
	if ($(this).val() != '') {
		
		var option = $('option[value=' + $(this).val() + ']', this);
		
		//console.log(option);
		
		var html = '<p>' + $(option).data('name');
		
		if ($(option).data('x')) {
			html += ' (<a href="http://maps.google.ru/maps?q=' + $(option).data('y') + ',' + $(option).data('x') + '" target="_blank">на карте</a>)';
		}
		
		html += '</p>';
		html += '<span class="help">';
		html += '<strong>Адрес</strong>: ' + $(option).data('address') + '<br />';
		
		if ($(option).data('phone') && $(option).data('phone') != '-') {
			html += '<strong>Телефон</strong>: ' + $(option).data('phone') + '<br />';
		}
		
		if ($(option).data('worktime') && $(option).data('worktime') != '-') {
			html += '<strong>Режим работы</strong>: ' + $(option).data('worktime');
		}
		
		html += '</span>';
		
		$(this).parent().find('.pvz-wrapper').html(html);
		
	}
	
});

function getShippingPrice(order_id) {
	
	var context = $('#order-' + order_id);
	var tariff_id = $('#cdek-orders-' + order_id + '-tariff-id').val()
	var error = [];
	
	var container = $('#order-' + order_id + '-data .shipping-price');
	
	if (tariff_id == '') {
		return $(container).html('');
	}
	
	var senderCityId = $("input[name='cdek_orders[" + order_id + "][city_id]']").val();
	
	if (senderCityId == '') {
		error.push('Не указан город отправления!');
	}
	
	var receiverCityId = $("input[name='cdek_orders[" + order_id + "][recipient_city_id]']").val();
	
	if (receiverCityId == '') {
		error.push('Не указан город получателя!');
	}
	
	var weight_el = $(".package-weight", context);
	
	var weight = $(weight_el).val();
	
	if ($(weight_el).data('packing_weight')) {
		
		var weight = parseInt(weight);
		var packing_weight = parseInt($(weight_el).data('packing_weight'));
		
		if ($(weight_el).data('packing_prefix') == '+') {
			weight += packing_weight;
		} else {
			weight -= packing_weight;
		}
		
	}
	
	if (weight == "") {
		error.push('Не указан вес!');
	} else if (isNaN(weight) || weight <= 0) {
		error.push('Вес заполнен с ошибкой!');
	}
	
	if (!error.length) {
		
		var d = new Date(new Date().getTime() + 10800);
		
		var date = {
			version: '1.0',
			dateExecute: d.getFullYear() + '-' + ((d.getMonth() + 1) < 10 ? '0' : '') + (d.getMonth() + 1) + '-' + (d.getDate() < 10 ? '0' : '') + d.getDate(),
			senderCityId: senderCityId,
			receiverCityId: receiverCityId,
			authLogin: '<?php echo $login; ?>',
			secure: '<?php echo $pass; ?>',
			tariffId: tariff_id,
			goods: [{weight: (parseInt(weight)/1000) ,volume: 0.003}]
		};
		
		$.ajax({
			url : 'http://api.cdek.ru/calculator/calculate_price_by_jsonp.php',
			jsonp : 'callback',
			data : {
				json: JSON.stringify(date)
			},
			type : 'GET',
			dataType : "jsonp",
			success : function(data) {
				
				var html = '<ul class="no-mark help">';
				
				if(data.hasOwnProperty("result")) {
					
					html += '<li>Цена доставки: <strong>' + data.result.price + '</strong></li>';
					html += '<li>Срок доставки: ' + data.result.deliveryPeriodMin + ' - ' + data.result.deliveryPeriodMax + 'дн.</li>';
					html += '<li>Планируемая дата доставки: c ' + data.result.deliveryDateMin + ' по ' + data.result.deliveryDateMax + '</li>';
					
					if(data.result.hasOwnProperty("cashOnDelivery")) {
						html += '<li>Ограничение оплаты наличными, от (руб): ' + data.result.cashOnDelivery + '</li>';
					}
					
				} else {
					
					for(var key in data["error"]) {
						html += '<li>Текст ошибки: ' + data["error"][key].text + '</li>';
					}
					
				}
				
				html += '</ul>';
					
				$(container).html(html);
				
			}
		});
		
	} else {
		
		var html = '<ul class="no-mark help">';
		
		for(var key in error) {
			html += '<li>Текст ошибки: ' + error[key] + '</li>';
		}
		
		html += '</ul>';
					
		$(container).html(html);
	}
}

$('#vtabs a').tabs();
<?php foreach ($cdek_orders as $row => $order_info) { ?>
$("#tabs-order-<?php echo $order_info['order_id']; ?> a").tabs();
$("#vtabs-order-<?php echo $order_info['order_id']; ?>-package a").tabs();
<?php } ?>
//--></script> 
<?php echo $footer; ?>