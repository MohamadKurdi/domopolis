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
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div id="htabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a> <a href="#tab-payment"><?php echo $tab_payment; ?></a>
				<?php if ($affiliate_id) { ?>
					<a href="#tab-transaction"><?php echo $tab_transaction; ?></a>
				<?php } ?>
				<div class="clr"></div>
			</div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
							<td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
								<?php if ($error_firstname) { ?>
									<span class="error"><?php echo $error_firstname; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
							<td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
								<?php if ($error_lastname) { ?>
									<span class="error"><?php echo $error_lastname; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_email; ?></td>
							<td><input type="text" name="email" value="<?php echo $email; ?>" />
								<?php if ($error_email) { ?>
									<span class="error"><?php echo $error_email; ?></span>
								<?php  } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
							<td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
								<?php if ($error_telephone) { ?>
									<span class="error"><?php echo $error_telephone; ?></span>
								<?php  } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_fax; ?></td>
							<td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_company; ?></td>
							<td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
							<td><input type="text" name="address_1" value="<?php echo $address_1; ?>" />
								<?php if ($error_address_1) { ?>
									<span class="error"><?php echo $error_address_1; ?></span>
								<?php  } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_address_2; ?></td>
							<td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_city; ?></td>
							<td><input type="text" name="city" value="<?php echo $city; ?>" />
								<?php if ($error_city) { ?>
									<span class="error"><?php echo $error_city ?></span>
								<?php  } ?></td>
						</tr>
						<tr>
							<td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
							<td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
								<?php if ($error_postcode) { ?>
									<span class="error"><?php echo $error_postcode ?></span>
								<?php  } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_country; ?></td>
							<td><select name="country_id">
								<option value="false"><?php echo $text_select; ?></option>
								<?php foreach ($countries as $country) { ?>
									<?php if ($country['country_id'] == $country_id) { ?>
										<option value="<?php echo $country['country_id']; ?>" selected="selected"> <?php echo $country['name']; ?> </option>
										<?php } else { ?>
										<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_country) { ?>
								<span class="error"><?php echo $error_country; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_zone; ?></td>
							<td><select name="zone_id">
							</select>
							<?php if ($error_zone) { ?>
								<span class="error"><?php echo $error_zone; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_code; ?></td>
							<td><input type="code" name="code" value="<?php echo $code; ?>"  />
								<?php if ($error_code) { ?>
									<span class="error"><?php echo $error_code; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_password; ?></td>
							<td><input type="password" name="password" value="<?php echo $password; ?>"  />
								<?php if ($error_password) { ?>
									<span class="error"><?php echo $error_password; ?></span>
								<?php  } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_confirm; ?></td>
							<td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
								<?php if ($error_confirm) { ?>
									<span class="error"><?php echo $error_confirm; ?></span>
								<?php  } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td><select name="status">
								<?php if ($status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select></td>
						</tr>
					</table>
				</div>
				<div id="tab-payment">
					<table class="form">
						<tbody>
							<tr>
								<td><?php echo $entry_commission; ?></td>
								<td><input type="text" name="commission" value="<?php echo $commission; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $entry_tax; ?></td>
								<td><input type="text" name="tax" value="<?php echo $tax; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $entry_payment; ?></td>
								<td><?php if ($config_bonus_visible) { ?>
                                    <input type="radio" name="payment" value="bonus" id="config_bonus_visible" <?php if ($payment == 'bonus') { ?> checked="checked" <?php } ?>/>
                                    <label for="config_bonus_visible"><?php echo $text_bonus; ?></label>
                                    <br><br>
								<?php } ?>
								
								<?php if ($cheque_visible) { ?>
                                    <input type="radio" name="payment" value="cheque" id="cheque" <?php if ($payment == 'cheque') { ?> checked="checked" <?php } ?>/>
                                    <label for="cheque"><?php echo $text_cheque; ?></label>
                                    <br><br>
								<?php } ?>
								
								<?php if ($paypal_visible) { ?>
                                    <input type="radio" name="payment" value="paypal" id="paypal" <?php if ($payment == 'paypal') { ?> checked="checked" <?php } ?>/>
                                    <label for="paypal"><?php echo $text_paypal; ?></label>
                                    <br><br>
								<?php } ?>
								
								<?php if ($bank_visible) { ?>
                                    <input type="radio" name="payment" value="bank" id="bank" value="<?php echo $bank; ?>" <?php if ($payment == 'bank') { ?> checked="checked" <?php } ?>/>
                                    <label for="bank"><?php echo $text_bank; ?></label>
                                    <br><br>
								<?php } ?>
								
								<?php if ($qiwi_visible) { ?>
                                    <input type="radio" name="payment" value="qiwi" id="qiwi" <?php if ($payment == 'qiwi') { ?> checked="checked" <?php } ?>/>
                                    <label for="qiwi"><?php echo $text_qiwi; ?></label>
                                    <br><br>
								<?php } ?>
								
								<?php if ($card_visible) { ?>
                                    <input type="radio" name="payment" value="card" id="card" <?php if ($payment == 'card') { ?> checked="checked" <?php } ?>/>
                                    <label for="card"><?php echo $text_card; ?></label>
                                    <br><br>
								<?php } ?>
								
								<?php if ($yandex_visible) { ?>
                                    <input type="radio" name="payment" value="yandex" id="yandex" <?php if ($payment == 'yandex') { ?> checked="checked" <?php } ?>/>
                                    <label for="yandex"><?php echo $text_yandex; ?></label>
                                    <br><br>
								<?php } ?>
								
								<?php if ($webmoney_visible) { ?>
                                    <input type="radio" name="payment" value="webmoney" id="webmoney" <?php if ($payment == 'webmoney') { ?> checked="checked" <?php } ?>/>
                                    <label for="webmoney"><?php echo $text_webmoney; ?></label>
									<br><br>
								<?php } ?>
								
								<?php if ($webmoney_visibleWMZ) { ?>
                                    <input type="radio" name="payment" value="webmoneyWMZ" id="webmoneyWMZ" <?php if ($payment == 'webmoneyWMZ') { ?> checked="checked" <?php } ?>/>
                                    <label for="webmoneyWMZ"><?php echo $text_webmoneyWMZ; ?></label>
									<br><br>
								<?php } ?>
								
								<?php if ($webmoney_visibleWMU) { ?>
                                    <input type="radio" name="payment" value="webmoneyWMU" id="webmoneyWMU" <?php if ($payment == 'webmoneyWMU') { ?> checked="checked" <?php } ?>/>
                                    <label for="webmoneyWMU"><?php echo $text_webmoneyWMU; ?></label>
									<br><br>
								<?php } ?>
								
								<?php if ($webmoney_visibleWME) { ?>
                                    <input type="radio" name="payment" value="webmoneyWME" id="webmoneyWME" <?php if ($payment == 'webmoneyWME') { ?> checked="checked" <?php } ?>/>
                                    <label for="webmoneyWME"><?php echo $text_webmoneyWME; ?></label>
									<br><br>
								<?php } ?>
								
								<?php if ($webmoney_visibleWMY) { ?>
                                    <input type="radio" name="payment" value="webmoneyWMY" id="webmoneyWMY" <?php if ($payment == 'webmoneyWMY') { ?> checked="checked" <?php } ?>/>
                                    <label for="webmoneyWMY"><?php echo $text_webmoneyWMY; ?></label>
									<br><br>
								<?php } ?>
								
								<?php if ($webmoney_visibleWMB) { ?>
                                    <input type="radio" name="payment" value="webmoneyWMB" id="webmoneyWMB" <?php if ($payment == 'webmoneyWMB') { ?> checked="checked" <?php } ?>/>
                                    <label for="webmoneyWMB"><?php echo $text_webmoneyWMB; ?></label>
									<br><br>
								<?php } ?>
								
								<?php if ($webmoney_visibleWMG) { ?>
                                    <input type="radio" name="payment" value="webmoneyWMG" id="webmoneyWMG" <?php if ($payment == 'webmoneyWMG') { ?> checked="checked" <?php } ?>/>
                                    <label for="webmoneyWMG"><?php echo $text_webmoneyWMG; ?></label>
									<br><br>
								<?php } ?>
								
								<?php if ($AlertPay_visible) { ?>
                                    <input type="radio" name="payment" value="AlertPay" id="AlertPay" <?php if ($payment == 'AlertPay') { ?> checked="checked" <?php } ?>/>
                                    <label for="AlertPay"><?php echo $text_AlertPay; ?></label><br><br>
								<?php } ?>
								
								<?php if ($Moneybookers_visible) { ?>
                                    <input type="radio" name="payment" value="Moneybookers" id="Moneybookers" <?php if ($payment == 'Moneybookers') { ?> checked="checked" <?php } ?>/>
                                    <label for="Moneybookers"><?php echo $text_Moneybookers; ?></label><br><br>
								<?php } ?>
								
								<?php if ($LIQPAY_visible) { ?>
                                    <input type="radio" name="payment" value="LIQPAY" id="LIQPAY" <?php if ($payment == 'LIQPAY') { ?> checked="checked" <?php } ?>/>
                                    <label for="LIQPAY"><?php echo $text_LIQPAY; ?></label><br><br>
								<?php } ?>
								
								<?php if ($SagePay_visible) { ?>
                                    <input type="radio" name="payment" value="SagePay" id="SagePay" <?php if ($payment == 'SagePay') { ?> checked <?php } ?>/>
                                    <label for="SagePay"><?php echo $text_SagePay; ?></label><br><br>
								<?php } ?>
								
								<?php if ($twoCheckout_visible) { ?>
                                    <input type="radio" name="payment" value="twoCheckout" id="twoCheckout" <?php if ($payment == 'twoCheckout') { ?> checked="checked" <?php } ?>/>
                                    <label for="twoCheckout"><?php echo $text_twoCheckout; ?></label><br><br>
								<?php } ?>
								
								<?php if ($GoogleWallet_visible) { ?>
                                    <input type="radio" name="payment" value="GoogleWallet" id="GoogleWallet" <?php if ($payment == 'GoogleWallet') { ?> checked="checked" <?php } ?>/>
                                    <label for="GoogleWallet"><?php echo $text_GoogleWallet; ?></label>
								<?php } ?></td>
															
								<?php if ($payment == 'paypal') { ?>
									<label for="paypal"><?php echo $text_paypal; ?></label>
									<input type="radio" name="payment" value="paypal" id="paypal" checked="checked" />
									<?php } else { ?>
									<label for="paypal"><?php echo $text_paypal; ?></label>
									<input type="radio" name="payment" value="paypal" id="paypal" />
								<?php } ?>
								
								<?php if ($payment == 'bank') { ?>
								<label for="bank"><?php echo $text_bank; ?></label>
									<input type="radio" name="payment" value="bank" id="bank" checked="checked" />
									<?php } else { ?>
									<label for="bank"><?php echo $text_bank; ?></label>
									<input type="radio" name="payment" value="bank" id="bank" />
								<?php } ?>
								
							</td>
							</tr>
						</tbody>
						<tbody id="payment-cheque" class="payment">
                            <tr>
                                <td><?php echo $entry_cheque; ?></td>
                                <td><input type="text" name="cheque" value="<?php echo $cheque; ?>" /></td>
							</tr>
						</tbody>
                        <tbody class="payment" id="payment-paypal">
                            <tr>
                                <td><?php echo $entry_paypal; ?></td>
                                <td><input type="text" name="paypal" value="<?php echo $paypal; ?>" /></td>
							</tr>
						</tbody>
                        <tbody id="payment-bank" class="payment">
                            <tr>
                                <td><?php echo $entry_bank_name; ?></td>
                                <td><input type="text" name="bank_name" value="<?php echo $bank_name; ?>" /></td>
							</tr>
                            <tr>
                                <td><?php echo $entry_bank_branch_number; ?></td>
                                <td><input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" /></td>
							</tr>
                            <tr>
                                <td><?php echo $entry_bank_swift_code; ?></td>
                                <td><input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" /></td>
							</tr>
                            <tr>
                                <td><?php echo $entry_bank_account_name; ?></td>
                                <td><input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" /></td>
							</tr>
                            <tr>
                                <td><?php echo $entry_bank_account_number; ?></td>
                                <td><input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" /></td>
							</tr>
						</tbody>
                        <tbody id="payment-qiwi" class="payment">
                            <tr>
                                <td><?php echo $entry_qiwi; ?></td>
                                <td><span class="required">*</span><input type="text" name="qiwi" value="<?php echo $qiwi; ?>" /></td>
							</tr>
						</tbody>
						
                        <tbody class="payment" id="payment-card">
                            <tr>
                                <td><?php echo $entry_card; ?></td>
                                <td><span class="required">*</span><input type="text" name="card" value="<?php echo $card; ?>" /></td>
							</tr>
						</tbody>
						
                        <tbody class="payment" id="payment-yandex">
                            <tr>
                                <td><?php echo $entry_yandex; ?></td>
                                <td><span class="required">*</span><input type="text" name="yandex" value="<?php echo $yandex; ?>" /></td>
							</tr>
						</tbody>
						
                        <tbody class="payment" id="payment-webmoney">
                            <tr>
                                <td><?php echo $entry_webmoney; ?></td>
                                <td><span class="required">*</span><input type="text" name="webmoney" value="<?php echo $webmoney; ?>" /></td>
							</tr>
						</tbody>
						<tbody class="payment" id="payment-webmoneyWMZ">
                            <tr>
                                <td><?php echo $entry_webmoneyWMZ; ?></td>
                                <td><span class="required">*</span><input type="text" name="webmoneyWMZ" value="<?php echo $webmoneyWMZ; ?>" /></td>
							</tr>
						</tbody>
						<tbody class="payment" id="payment-webmoneyWMU">
                            <tr>
                                <td><?php echo $entry_webmoneyWMU; ?></td>
                                <td><span class="required">*</span><input type="text" name="webmoneyWMU" value="<?php echo $webmoneyWMU; ?>" /></td>
							</tr>
						</tbody>
						<tbody class="payment" id="payment-webmoneyWME">
                            <tr>
                                <td><?php echo $entry_webmoneyWME; ?></td>
                                <td><span class="required">*</span><input type="text" name="webmoneyWME" value="<?php echo $webmoneyWME; ?>" /></td>
							</tr>
						</tbody>
						<tbody class="payment" id="payment-webmoneyWMY">
                            <tr>
                                <td><?php echo $entry_webmoneyWMY; ?></td>
                                <td><span class="required">*</span><input type="text" name="webmoneyWMY" value="<?php echo $webmoneyWMY; ?>" /></td>
							</tr>
						</tbody>
						<tbody class="payment" id="payment-webmoneyWMB">
                            <tr>
                                <td><?php echo $entry_webmoneyWMB; ?></td>
                                <td><span class="required">*</span><input type="text" name="webmoneyWMB" value="<?php echo $webmoneyWMB; ?>" /></td>
							</tr>
						</tbody>
						<tbody class="payment" id="payment-webmoneyWMG">
                            <tr>
                                <td><?php echo $entry_webmoneyWMG; ?></td>
                                <td><span class="required">*</span><input type="text" name="webmoneyWMG" value="<?php echo $webmoneyWMG; ?>" /></td>
							</tr>
						</tbody>
						<tbody class="payment" id="payment-AlertPay">
                            <tr>
                                <td><?php echo $entry_AlertPay; ?></td>
                                <td><span class="required">*</span><input type="text" name="AlertPay" value="<?php echo $AlertPay; ?>" /></td>
							</tr>
						</tbody>
						
						<tbody class="payment" id="payment-Moneybookers">
                            <tr>
                                <td><?php echo $entry_Moneybookers; ?></td>
                                <td><span class="required">*</span><input type="text" name="Moneybookers" value="<?php echo $Moneybookers; ?>" /></td>
							</tr>
						</tbody>
						
						<tbody class="payment" id="payment-LIQPAY">
                            <tr>
                                <td><?php echo $entry_LIQPAY; ?></td>
                                <td><span class="required">*</span><input type="text" name="LIQPAY" value="<?php echo $LIQPAY; ?>" /></td>
							</tr>
						</tbody>
						
						<tbody class="payment" id="payment-SagePay">
                            <tr>
                                <td><?php echo $entry_SagePay; ?></td>
                                <td><span class="required">*</span><input type="text" name="SagePay" value="<?php echo $SagePay; ?>" /></td>
							</tr>
						</tbody>
						
						<tbody class="payment" id="payment-twoCheckout">
                            <tr>
                                <td><?php echo $entry_twoCheckout; ?></td>
                                <td><span class="required">*</span><input type="text" name="twoCheckout" value="<?php echo $twoCheckout; ?>" /></td>
							</tr>
						</tbody>
						
						<tbody class="payment" id="payment-GoogleWallet">
                            <tr>
                                <td><?php echo $entry_GoogleWallet; ?></td>
                                <td><span class="required">*</span><input type="text" name="GoogleWallet" value="<?php echo $GoogleWallet; ?>" /></td>
							</tr>
						</tbody>
						<tbody id="payment-bank" class="payment">
							<tr>
								<td><?php echo $entry_bank_name; ?></td>
								<td><input type="text" name="bank_name" value="<?php echo $bank_name; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $entry_bank_branch_number; ?></td>
								<td><input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $entry_bank_swift_code; ?></td>
								<td><input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" /></td>
							</tr>
							<tr>
								<td><span class="required">*</span> <?php echo $entry_bank_account_name; ?></td>
								<td><input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" /></td>
							</tr>
							<tr>
								<td><span class="required">*</span> <?php echo $entry_bank_account_number; ?></td>
								<td><input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" /></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php if ($affiliate_id) { ?>
					<div id="tab-transaction">
						<table class="form">
                        <tr>
                            <td><?php echo $entry_description; ?></td>
                            <?php if ($payment == 'bonus') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_bonus; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'cheque') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_cheque; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'paypal') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_paypal; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'bank') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_bank; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'qiwi') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_qiwi; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'card') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_card; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'yandex') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_yandex; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'webmoney') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_webmoney; ?>" /></td>
							<?php } ?>
                            <?php if ($payment == 'webmoneyWMZ') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_webmoneyWMZ; ?>" /></td>
							<?php } ?>
                            <?php if ($payment == 'webmoneyWMU') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_webmoneyWMU; ?>" /></td>
							<?php } ?>
                            <?php if ($payment == 'webmoneyWME') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_webmoneyWME; ?>" /></td>
							<?php } ?>
                            <?php if ($payment == 'webmoneyWMY') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_webmoneyWMY; ?>" /></td>
							<?php } ?>
                            <?php if ($payment == 'webmoneyWMB') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_webmoneyWMB; ?>" /></td>
							<?php } ?>
                            <?php if ($payment == 'webmoneyWMG') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_webmoneyWMG; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'AlertPay') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_AlertPay; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'Moneybookers') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_Moneybookers; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'LIQPAY') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_LIQPAY; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'SagePay') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_SagePay; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'twoCheckout') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_twoCheckout; ?>" /></td>
                            <?php } ?>
                            <?php if ($payment == 'GoogleWallet') { ?>
                            <td><input type="text" name="description" value="<?php echo $entry_payment_comment.$text_GoogleWallet; ?>" /></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td><?php echo $entry_amount; ?></td>
                            <td><input type="text" name="amount" value="<?php echo '-'.$request_payment; ?>" /></td>
                        </tr>
							<tr>
								<td><?php echo $entry_amount; ?></td>
								<td><input type="text" name="amount" value="" /></td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: right;"><a id="button-reward" class="button" onclick="addTransaction();"><span><?php echo $button_add_transaction; ?></span></a></td>
							</tr>
						</table>
						<div id="transaction"></div>
					</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	$('select[name=\'country_id\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=sale/affiliate/country&token=<?php echo $token; ?>&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'payment_country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('.wait').remove();
			},			
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#postcode-required').show();
					} else {
					$('#postcode-required').hide();
				}
				
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json != '' && json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
							html += ' selected="selected"';
						}
						
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
					} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}
				
				$('select[name=\'zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	$('select[name=\'country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
	$('input[name=\'payment\']').bind('change', function() {
		$('.payment').hide();
		
		$('#payment-' + this.value).show();
	});
	
	$('input[name=\'payment\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
	$('#transaction .pagination a').live('click', function() {
		$('#transaction').load(this.href);
		
		return false;
	});			
	
	$('#transaction').load('index.php?route=sale/affiliate/transaction&token=<?php echo $token; ?>&affiliate_id=<?php echo $affiliate_id; ?>');
	
	function addTransaction() {
		$.ajax({
			url: 'index.php?route=sale/affiliate/transaction&token=<?php echo $token; ?>&affiliate_id=<?php echo $affiliate_id; ?>',
			type: 'post',
			dataType: 'html',
			data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-transaction').attr('disabled', true);
				$('#transaction').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-transaction').attr('disabled', false);
				$('.attention').remove();
			},
			success: function(html) {
				$('#transaction').html(html);
				
				$('#tab-transaction input[name=\'amount\']').val('');
				$('#tab-transaction input[name=\'description\']').val('');
			}
		});
	}
//--></script> 
<script type="text/javascript"><!--
	$('.htabs a').tabs();
//--></script> 
<?php echo $footer; ?>			