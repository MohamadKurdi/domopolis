<div class="simplecheckout-block checkout-steps__select" id="simplecheckout_payment" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
    <?php if ($display_header) { ?>
        <div class="checkout-steps__select-label"><?php echo $text_checkout_payment_method ?></div>
    <?php } ?>
    <div class="simplecheckout-warning-block" <?php echo $display_error && $has_error_payment ? '' : 'style="display:none"' ?>><?php echo $error_payment ?></div>
    <div class="simplecheckout-block-content checkout-steps__select-right">
        <?php if (!empty($payment_methods)) { ?>
            <?php if ($display_type == 2 ) { ?>
                <?php $current_method = false; ?>
                <select data-onchange="reloadAll" name="payment_method">
                    <option value="" disabled="disabled" <?php if (empty($code)) { ?>selected="selected"<?php } ?>><?php echo $text_select; ?></option>
                    <?php foreach ($payment_methods as $payment_method) { ?>
                        <option value="<?php echo $payment_method['code']; ?>" <?php echo !empty($payment_method['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($payment_method['dummy']) ? 'data-dummy="true"' : '' ?> <?php if ($payment_method['code'] == $code) { ?>selected="selected"<?php } ?>><?php echo $payment_method['title']; ?></option>
                        <?php if ($payment_method['code'] == $code) { $current_method = $payment_method; } ?>
                    <?php } ?>
                </select>
                <?php if ($current_method) { ?>
                    <?php if (!empty($current_method['description'])) { ?>
                        <div class="simplecheckout-methods-description"><?php echo $current_method['description']; ?></div>
                    <?php } ?>
                    <?php if (!empty($rows)) { ?>
                        <table class="simplecheckout-methods-table">
                            <tr>
                                <td colspan="2">
                                    <?php foreach ($rows as $row) { ?>
                                        <?php echo $row ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    <?php } ?>
                <?php } ?>
                <?php } else { ?>
                <table class="simplecheckout-methods-table">
                    <?php foreach ($payment_methods as $payment_method) { ?>
                        <div class="tr_wrap">
                            <tr>
                                <td class="code">
                                    <input type="radio" data-onchange="reloadAll" name="payment_method" value="<?php echo $payment_method['code']; ?>" <?php echo !empty($payment_method['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($payment_method['dummy']) ? 'data-dummy="true"' : '' ?> id="<?php echo $payment_method['code']; ?>" <?php if ($payment_method['code'] == $code) { ?>checked="checked"<?php } ?> />
                                </td>
                                <td class="title">
                                    <label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label>
                                </td>
                            </tr>
                            <?php if (!empty($payment_method['description']) && (!$display_for_selected || ($display_for_selected && $payment_method['code'] == $code))) { ?>
                                <tr class="description_tr">
                                    <td class="code">
                                    </td>
                                    <td class="title">
                                        <label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['description']; ?></label>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($payment_method['code'] == $code && !empty($rows)) { ?>
                                <tr>
                                    <td colspan="2">
                                        <?php foreach ($rows as $row) { ?>
                                            <?php echo $row ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </table>
            <?php } ?>
            <input type="hidden" name="payment_method_current" value="<?php echo $code ?>" />
            <input type="hidden" name="payment_method_checked" value="<?php echo $checked_code ?>" />
        <?php } ?>
        <?php if (empty($payment_methods) && $address_empty && $display_address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $text_payment_address; ?></div>
        <?php } ?>
        <?php if (empty($payment_methods) && !$address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $error_no_payment; ?></div>
        <?php } ?>
    </div>
</div>

</div>
<!--/checkout-steps__item-->
<div class="simplecheckout-block" id="simplecheckout_payment_form_default" style="margin: 0px; padding: 0px; display:none;">
    <h2>defaultpayment</h2>
    <div class="content"></div>
    <div class="buttons" style="display: none;">
        <div class="right">
            <input type="button" value="defaultpayment-confirm" id="button-confirm-default" class="button" style="display: none;">
        </div>
    </div>
    <script type="text/javascript"><!--
        $('#button-confirm-default').bind('click', function() {
            $.ajax({ 
                type: 'get',
                url: 'index.php?route=payment/default/confirm',
                success: function() {
                    location = '<?php echo $default_payment_btn_success; ?>';
                }		
            });
        });
    </script> 
    </div>    