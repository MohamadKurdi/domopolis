<style>
            .tr_wrap .simplecheckout-methods-items{
                display: flex;
                align-items: center;    
                height: 26px;
                margin-bottom: 45px;
            }
            .tr_wrap .simplecheckout-methods-items .title{
                font-weight: 500;
                font-size: 16px;
                line-height: 19px;
                color: #404345;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .tr_wrap .simplecheckout-methods-items .quote{
                font-size: 13px;
                padding: 0 5px;
                color: #888;
            }
            .tr_wrap .simplecheckout-methods-items .title input[type="radio"]{
                opacity: 0;
                height: 0;
                width: 0;
                display: none;
            }
            .tr_wrap .simplecheckout-methods-items .title label{
                padding-left: 33px;
                position: relative;
                height: 26px;
                display: flex !important;
                align-items: center;
                cursor: pointer;
            }
            .tr_wrap .simplecheckout-methods-items .title label::before {
                width: 24px;
                height: 24px;
                background: #FFFFFF;
                border: 1px solid #DDE1E4;
                border-radius: 6px;
                left: 0;
                position: absolute;
                top: 0;
                transition: background-color 0.2s;
                content: '';
            }
            .tr_wrap .simplecheckout-methods-items input[type="radio"]:checked + label::before {
                background: #BFEA43;
                border-color: #BFEA43;
            }
            .tr_wrap .simplecheckout-methods-items label::after {
                border-color: #121415;
            }
            .tr_wrap .simplecheckout-methods-items input[type="radio"]:checked + label::after{
                content: "";
            }
            .tr_wrap .simplecheckout-methods-items label::after {
                height: 5px;
                width: 9px;
                border-left: 2px solid #121415;
                border-bottom: 2px solid #121415;
                transform: rotate(-45deg);
                left: 7px;
                top: 7px;
                transition: all 0.2s;
                position: absolute;
            }
             .tr_wrap .simplecheckout-methods-items .description{
                font-size: 13px;
                color: #888;
             }
             @media screen and (max-width: 1350px) {
                 .tr_wrap .simplecheckout-methods-items {
                    margin-bottom: 60px;
                }
             }
             @media screen and (max-width: 560px) {
                 .tr_wrap .simplecheckout-methods-items{
                    margin-bottom: 70px;
                }
                .field-group.row-shipping_novaposhta_warehouse, .field-group.row-shipping_novaposhta_flat, .field-group.row-shipping_novaposhta_house_number, .field-group.row-shipping_novaposhta_street, .field-group.row-shipping_novaposhta_warehous, .field-group.row-shipping_courier_city_shipping_address, .field-group.row-shipping_address_city, .field-group.row-customer_password, .field-group.row-customer_telephone, .field-group.row-customer_email, .field-group.row-customer_firstname, .login_tabs .field-group {
                    max-width: 100%;
                    flex: 100%;
                }
             }
</style>
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

                            <div class="simplecheckout-methods-items">
                                <div class="title" valign="middle">
                                   <input type="radio" data-onchange="reloadAll" name="payment_method" value="<?php echo $payment_method['code']; ?>" <?php echo !empty($payment_method['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($payment_method['dummy']) ? 'data-dummy="true"' : '' ?> id="<?php echo $payment_method['code']; ?>" <?php if ($payment_method['code'] == $code) { ?>checked="checked"<?php } ?> />
                                    <label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label>
                                    <?php if (!empty($payment_method['description']) && (!$display_for_selected || ($display_for_selected && $payment_method['code'] == $code))) { ?>
                                        <span class="description" ><?php echo $payment_method['description']; ?></span>
                                    <?php } ?>
                                </div>
                            </div>

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