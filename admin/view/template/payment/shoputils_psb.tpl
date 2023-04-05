<?php
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/
?>
<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a
        href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
    <div class="heading order_head">
        <h1><img src="view/image/payment/shoputils_psb.jpg" width="25" height="25"><?php echo $heading_title; ?></h1>
        <div class="buttons"><a onclick="$('#form').submit();"
                                class="button"><span><?php echo $button_save; ?></span></a><a
                onclick="location='<?php echo $cancel; ?>';"
                class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
        <div id="tabs" class="htabs">
            <a href="#tab_general"><?php echo $tab_general; ?></a>
            <a href="#tab_emails"><?php echo $tab_emails; ?></a>
            <a href="#tab_settings"><?php echo $tab_settings; ?></a>
            <a href="#tab_log"><?php echo $tab_log; ?></a>
            <a href="#tab_information"><?php echo $tab_information; ?></a>
			<div class="clr"></div>
        </div>
		<div class="th_style"></div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div id="tab_general">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_geo_zone; ?></td>
                        <td><select name="shoputils_psb_geo_zone_id">
                            <option value="0"><?php echo $text_all_zones; ?></option>
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                            <?php if ($geo_zone['geo_zone_id'] == $shoputils_psb_geo_zone_id) { ?>
                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                    selected="selected"><?php echo $geo_zone['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="shoputils_psb_sort_order"
                                   value="<?php echo $shoputils_psb_sort_order; ?>"
                                   size="3"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="shoputils_psb_status">
                            <?php if ($shoputils_psb_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>
					<tr>
                        <td>Вторичный метод</td>
                        <td><select name="shoputils_psb_ismethod">
                            <?php if ($shoputils_psb_ismethod) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_minimal_order; ?>
                            <div class="help">
                                <?php echo $help_minimal_order; ?>
                            </div>
                        </td>
                        <td><input type="text" name="shoputils_psb_minimal_order"
                                   value="<?php echo $shoputils_psb_minimal_order; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_maximal_order; ?>
                            <div class="help">
                                <?php echo $help_maximal_order; ?>
                            </div>
                        </td>
                        <td><input type="text" name="shoputils_psb_maximal_order"
                                   value="<?php echo $shoputils_psb_maximal_order; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_order_confirm_status; ?>
                            <div class="help">
                                <?php echo $help_order_confirm_status; ?>
                            </div>
                        </td>
                        <td><select name="shoputils_psb_order_confirm_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $shoputils_psb_order_confirm_status_id) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"
                                    selected="selected"><?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_order_status; ?>
                            <div class="help">
                                <?php echo $help_order_status; ?>
                            </div>
                        </td>
                        <td><select name="shoputils_psb_order_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $shoputils_psb_order_status_id) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"
                                    selected="selected"><?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_order_fail_status; ?>
                            <div class="help">
                                <?php echo $help_order_fail_status; ?>
                            </div>
                        </td>
                        <td><select name="shoputils_psb_order_fail_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $shoputils_psb_order_fail_status_id) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"
                                    selected="selected"><?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_laterpay_mode; ?>
                            <div class="help"><?php echo $help_laterpay_mode; ?></div>
                        </td>
                        <td>
                          <label>
                            <?php if ($shoputils_psb_laterpay_mode) { ?>
                            <input type="radio" name="shoputils_psb_laterpay_mode" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_laterpay_mode" value="1" />
                            <?php echo $text_yes; ?>
                            <?php } ?>
                          </label>
                          <label>
                            <?php if (!$shoputils_psb_laterpay_mode) { ?>
                            <input type="radio" name="shoputils_psb_laterpay_mode" value="0" checked="checked" />
                            <?php echo $text_no; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_laterpay_mode" value="0" />
                            <?php echo $text_no; ?>
                            <?php } ?>
                          </label>
                        </td>
                    </tr>
                    <tr id="laterpay-mode-tr" style="display: none;">
                        <td>
                            <?php echo $entry_order_later_status; ?>
                            <div class="help">
                                <?php echo $help_order_later_status; ?>
                            </div>
                        </td>
                        <td><select name="shoputils_psb_order_later_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $shoputils_psb_order_later_status_id) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"
                                    selected="selected"><?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_title; ?><br><span
                                class="help"><?php echo $help_title; ?></span></td>
                        <td>
                          <?php foreach ($oc_languages as $language) { ?>
                            <input type="text" size="80" id="title<?php echo $language['language_id']; ?>"
                                   name="shoputils_psb_langdata[<?php echo $language['language_id']; ?>][title]"
                                   value="<?php echo !empty($shoputils_psb_langdata[$language['language_id']]['title'])
                                               ? $shoputils_psb_langdata[$language['language_id']]['title'] : $title_default[0]; ?>"/>
                            &nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br />
                          <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_instruction; ?><br><span
                                class="help"><?php echo $help_instruction; ?></span></td>
                        <td>
                          <?php foreach ($oc_languages as $language) { ?>
                            <textarea cols="80" rows="10" id="instruction<?php echo $language['language_id']; ?>"
                                   placeholder="<?php echo $placeholder_instruction; ?>"
                                   name="shoputils_psb_langdata[<?php echo $language['language_id']; ?>][instruction]"
                                   ><?php echo !empty($shoputils_psb_langdata[$language['language_id']]['instruction'])
                                               ? $shoputils_psb_langdata[$language['language_id']]['instruction'] : ''; ?></textarea>
                            &nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br />
                          <?php } ?>
                        </td>
                    </tr>
                </table>
            </div><!-- </div id="tab_general"> -->
            <div id="tab_emails">
                <table class="form">
                    <tr>
                        <td>
                            <?php echo $entry_notify_customer_success; ?>
                            <div class="help"><?php echo $help_notify_customer_success; ?></div>
                        </td>
                        <td>
                          <label>
                            <?php if ($shoputils_psb_notify_customer_success) { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_success" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_success" value="1" />
                            <?php echo $text_yes; ?>
                            <?php } ?>
                          </label>
                          <label>
                            <?php if (!$shoputils_psb_notify_customer_success) { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_success" value="0" checked="checked" />
                            <?php echo $text_no; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_success" value="0" />
                            <?php echo $text_no; ?>
                            <?php } ?>
                          </label>
                        </td>
                    </tr>
                    <tr id="mail-customer-success-subject-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_customer_success_subject; ?>
                            <div class="help"><?php echo $help_mail_customer_success_subject; ?></div>
                        </td>
                        <td>
                          <?php foreach ($oc_languages as $language) { ?>
                            <input type="text" size="80" id="mail-customer-success-subject<?php echo $language['language_id']; ?>"
                                   name="shoputils_psb_langdata[<?php echo $language['language_id']; ?>][mail_customer_success_subject]"
                                   value="<?php echo !empty($shoputils_psb_langdata[$language['language_id']]['mail_customer_success_subject'])
                                               ? $shoputils_psb_langdata[$language['language_id']]['mail_customer_success_subject'] : $sample_mail_customer_success_subject; ?>"/>
                            &nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br />
                          <?php } ?>
                            <?php if ($error_mail_customer_success_subject) { ?>
                            <span class="error"><?php echo $error_mail_customer_success_subject; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr id="mail-customer-success-content-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_customer_success_content; ?>
                            <div class="help"><?php echo $help_mail_customer_success_content; ?></div>
                            <?php echo $list_helper; ?>
                        </td>
                        <td>
                          <?php foreach ($oc_languages as $language) { ?>
                            <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br />
                            <textarea cols="80" rows="10" id="mail-customer-success-content<?php echo $language['language_id']; ?>"
                                   name="shoputils_psb_langdata[<?php echo $language['language_id']; ?>][mail_customer_success_content]"
                                   ><?php echo !empty($shoputils_psb_langdata[$language['language_id']]['mail_customer_success_content'])
                                               ? $shoputils_psb_langdata[$language['language_id']]['mail_customer_success_content'] : $sample_mail_customer_success_content; ?></textarea><br />
                          <?php } ?>
                            <?php if ($error_mail_customer_success_content) { ?>
                            <span class="error"><?php echo $error_mail_customer_success_content; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_notify_customer_fail; ?>
                            <div class="help"><?php echo $help_notify_customer_fail; ?></div>
                        </td>
                        <td>
                          <label>
                            <?php if ($shoputils_psb_notify_customer_fail) { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_fail" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_fail" value="1" />
                            <?php echo $text_yes; ?>
                            <?php } ?>
                          </label>
                          <label>
                            <?php if (!$shoputils_psb_notify_customer_fail) { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_fail" value="0" checked="checked" />
                            <?php echo $text_no; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_notify_customer_fail" value="0" />
                            <?php echo $text_no; ?>
                            <?php } ?>
                          </label>
                        </td>
                    </tr>
                    <tr id="mail-customer-fail-subject-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_customer_fail_subject; ?>
                            <div class="help"><?php echo $help_mail_customer_fail_subject; ?></div>
                        </td>
                        <td>
                          <?php foreach ($oc_languages as $language) { ?>
                            <input type="text" size="80" id="mail-customer-fail-subject<?php echo $language['language_id']; ?>"
                                   name="shoputils_psb_langdata[<?php echo $language['language_id']; ?>][mail_customer_fail_subject]"
                                   value="<?php echo !empty($shoputils_psb_langdata[$language['language_id']]['mail_customer_fail_subject'])
                                               ? $shoputils_psb_langdata[$language['language_id']]['mail_customer_fail_subject'] : $sample_mail_customer_fail_subject; ?>"/>
                            &nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br />
                          <?php } ?>
                            <?php if ($error_mail_customer_fail_subject) { ?>
                            <span class="error"><?php echo $error_mail_customer_fail_subject; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr id="mail-customer-fail-content-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_customer_fail_content; ?>
                            <div class="help"><?php echo $help_mail_customer_fail_content; ?></div>
                            <?php echo $list_helper; ?>
                        </td>
                        <td>
                          <?php foreach ($oc_languages as $language) { ?>
                            <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br />
                            <textarea cols="80" rows="10" id="mail-customer-fail-content<?php echo $language['language_id']; ?>"
                                   name="shoputils_psb_langdata[<?php echo $language['language_id']; ?>][mail_customer_fail_content]"
                                   ><?php echo !empty($shoputils_psb_langdata[$language['language_id']]['mail_customer_fail_content'])
                                               ? $shoputils_psb_langdata[$language['language_id']]['mail_customer_fail_content'] : $sample_mail_customer_fail_content; ?></textarea><br />
                          <?php } ?>
                            <?php if ($error_mail_customer_fail_content) { ?>
                            <span class="error"><?php echo $error_mail_customer_fail_content; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_notify_admin_success; ?>
                            <div class="help"><?php echo $help_notify_admin_success; ?></div>
                        </td>
                        <td>
                          <label>
                            <?php if ($shoputils_psb_notify_admin_success) { ?>
                            <input type="radio" name="shoputils_psb_notify_admin_success" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_notify_admin_success" value="1" />
                            <?php echo $text_yes; ?>
                            <?php } ?>
                          </label>
                          <label>
                              <?php if (!$shoputils_psb_notify_admin_success) { ?>
                                  <input type="radio" name="shoputils_psb_notify_admin_success" value="0" checked="checked" />
                                  <?php echo $text_no; ?>
                              <?php } else { ?>
                                <input type="radio" name="shoputils_psb_notify_admin_success" value="0" />
                                <?php echo $text_no; ?>
                              <?php } ?>
                          </label>
                        </td>
                    </tr>
                    <tr id="mail-admin-success-subject-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_admin_success_subject; ?>
                            <div class="help"><?php echo $help_mail_admin_success_subject; ?></div>
                        </td>
                        <td>
                            <input type="text" name="shoputils_psb_mail_admin_success_subject"
                                   value="<?php echo !empty($shoputils_psb_mail_admin_success_subject)
                                   ? $shoputils_psb_mail_admin_success_subject : $sample_mail_admin_success_subject ?>"
                                   style="width:70%"/>
                            <?php if ($error_mail_admin_success_subject) { ?>
                            <span class="error"><?php echo $error_mail_admin_success_subject; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr id="mail-admin-success-content-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_admin_success_content; ?>
                            <div class="help"><?php echo $help_mail_admin_success_content; ?></div>
                            <?php echo $list_helper; ?>
                        </td>
                        <td>
                            <textarea type="text" name="shoputils_psb_mail_admin_success_content" id="mail-admin-success-content"
                                  ><?php echo !empty($shoputils_psb_mail_admin_success_content)
                                  ? $shoputils_psb_mail_admin_success_content : $sample_mail_admin_success_content; ?></textarea>
                            <?php if ($error_mail_admin_success_content) { ?>
                            <span class="error"><?php echo $error_mail_admin_success_content; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_notify_admin_fail; ?>
                            <div class="help"><?php echo $help_notify_admin_fail; ?></div>
                        </td>
                        <td>
                          <label>
                            <?php if ($shoputils_psb_notify_admin_fail) { ?>
                            <input type="radio" name="shoputils_psb_notify_admin_fail" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_notify_admin_fail" value="1" />
                            <?php echo $text_yes; ?>
                            <?php } ?>
                          </label>
                          <label>
                            <?php if (!$shoputils_psb_notify_admin_fail) { ?>
                            <input type="radio" name="shoputils_psb_notify_admin_fail" value="0" checked="checked" />
                            <?php echo $text_no; ?>
                            <?php } else { ?>
                            <input type="radio" name="shoputils_psb_notify_admin_fail" value="0" />
                            <?php echo $text_no; ?>
                            <?php } ?>
                          </label>
                        </td>
                    </tr>
                    <tr id="mail-admin-fail-subject-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_admin_fail_subject; ?>
                            <div class="help"><?php echo $help_mail_admin_fail_subject; ?></div>
                        </td>
                        <td>
                            <input type="text" name="shoputils_psb_mail_admin_fail_subject"
                                   value="<?php echo !empty($shoputils_psb_mail_admin_fail_subject)
                                   ? $shoputils_psb_mail_admin_fail_subject : $sample_mail_admin_fail_subject ?>"
                                   style="width:70%"/>
                            <?php if ($error_mail_admin_fail_subject) { ?>
                            <span class="error"><?php echo $error_mail_admin_fail_subject; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr id="mail-admin-fail-content-tr" style="display: none;">
                        <td>
                            <span class="required">*</span><?php echo $entry_mail_admin_fail_content; ?>
                            <div class="help"><?php echo $help_mail_admin_fail_content; ?></div>
                            <?php echo $list_helper; ?>
                        </td>
                        <td>
                            <textarea type="text" name="shoputils_psb_mail_admin_fail_content" id="mail-admin-fail-content"
                                  ><?php echo !empty($shoputils_psb_mail_admin_fail_content)
                                  ? $shoputils_psb_mail_admin_fail_content : $sample_mail_admin_fail_content; ?></textarea>
                            <?php if ($error_mail_admin_fail_content) { ?>
                            <span class="error"><?php echo $error_mail_admin_fail_content; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div><!-- </div id="tab_emails"> -->
            <div id="tab_settings">
                <table class="form">
                    <tr>
                        <td>
                            <span class="required">*</span><?php echo $entry_terminal; ?>
                            <div class="help"><?php echo $help_terminal; ?></div>
                        </td>
                        <td>
                            <input type="text" name="shoputils_psb_terminal" value="<?php echo $shoputils_psb_terminal ?>"
                                   style="width:70%"/>
                            <?php if ($error_terminal) { ?>
                            <span class="error"><?php echo $error_terminal; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span><?php echo $entry_terminal_test; ?>
                            <div class="help"><?php echo $help_terminal_test; ?></div>
                        </td>
                        <td>
                            <input type="text" name="shoputils_psb_terminal_test" value="<?php echo $shoputils_psb_terminal_test ?>"
                                   style="width:70%"/>
                            <?php if ($error_terminal_test) { ?>
                            <span class="error"><?php echo $error_terminal_test; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span><?php echo $entry_merchant_id; ?>
                            <div class="help"><?php echo $help_merchant_id; ?></div>
                        </td>
                        <td>
                            <input type="text" name="shoputils_psb_merchant_id" value="<?php echo $shoputils_psb_merchant_id; ?>"
                                   style="width:70%"/>
                            <?php if ($error_merchant_id) { ?>
                            <span class="error"><?php echo $error_merchant_id; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span><?php echo $entry_merchant_key; ?>
                            <div class="help"><?php echo $help_merchant_key; ?></div>
                        </td>
                        <td>
                            <input type="text" name="shoputils_psb_merchant_key" value="<?php echo $shoputils_psb_merchant_key; ?>"
                                   style="width:70%"/>
                            <?php if ($error_merchant_key) { ?>
                            <span class="error"><?php echo $error_merchant_key; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span><?php echo $entry_merchant_name; ?>
                            <div class="help"><?php echo $help_merchant_name; ?></div>
                        </td>
                        <td>
                            <input type="text" name="shoputils_psb_merchant_name" value="<?php echo $shoputils_psb_merchant_name; ?>"
                                   style="width:70%"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_test_mode; ?>
                            <div class="help"><?php echo $help_test_mode; ?></div>
                        </td>
                        <td>
                            <select name="shoputils_psb_test_mode">
                                <?php foreach ($test_modes as $key => $title) { ?>
                                <?php if ($key == $shoputils_psb_test_mode) { ?>
                                <option value="<?php echo $key; ?>"
                                        selected="selected"><?php echo $title ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $key; ?>"><?php echo $title ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div><!-- </div id="tab_settings"> -->
            <div id="tab_log">
                <table class="form">
                    <tr>
                        <td>
                            <input type="hidden" name="shoputils_psb_log_filename" value="<?php echo $log_filename ?>" />
                            <input type="hidden" name="shoputils_psb_version" value="<?php echo $version ?>" />
                            <?php echo $entry_log; ?>
                            <div class="help"><?php echo $help_log; ?></div>
                        </td>
                        <td>
                            <select name="shoputils_psb_log">
                                <?php foreach ($logs as $key => $value) { ?>
                                <?php if ($key == $shoputils_psb_log) { ?>
                                <option value="<?php echo $key; ?>"
                                        selected="selected"><?php echo $value; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                        <td style="text-align: right;">
                            <div class="buttons"><a id="button-clear" class="button"><?php echo $button_clear; ?></a></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $entry_log_file; ?>
                            <div class="help"><?php echo $help_log_file; ?></div>
                        </td>
                        <td colspan="2">
                             <div class="scrollbox" style="height:300px; width:700px;">
                             <pre id="pre-log" style="font-size:11px; padding:0 10px;"><?php
                                foreach ($log_lines as $log_line) {
                                    echo htmlentities($log_line, ENT_NOQUOTES, 'UTF-8');
                                }
                                ?>
                            </pre>
                            </div>
                        </td>
                    </tr>
                </table>
            </div><!-- </div id="tab_log"> -->
            <div id="tab_information">
                <div class="success" style="padding:30px;">
                    <?php echo $text_info ?>
                </div>
                <table class="form">
                    <?php foreach ($informations as $text => $value) { ?>
                    <tr>
                        <td style="width:  300px">
                            <?php echo $text; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $value; ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <?php echo $entry_calculate; ?>
                            <div class="help"><?php echo $help_calculate; ?></div>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $entry_component1; ?><br />
                            <input type="text" name="component1" value="" style="width:70%"/><br /><br />
                            <?php echo $entry_component2; ?><br />
                            <input type="text" name="component2" value="" style="width:70%"/><br /><br />
                            <div style="margin: 5px 0;">
                                <a id="button_calculate" class="button"><span><?php echo $button_calculate; ?></span></a>
                            </div>
                            <div id="calculate-result"></div>
                        </td>
                    </tr>
                </table>
            </div><!-- </div id="tab_information"> -->
        </form>
    </div>
    <div style="padding: 15px 15px; border:1px solid #ccc; margin-top: 15px; box-shadow:0 0px 5px rgba(0,0,0,0.1);"><?php echo $text_copyright; ?></div>
</div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
  CKEDITOR.config.autoParagraph = false;
  CKEDITOR.replace('mail-admin-success-content', {
    filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
  });
  CKEDITOR.replace('mail-admin-fail-content', {
    filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
  });
  <?php foreach ($oc_languages as $language) { ?>
    CKEDITOR.replace('mail-customer-success-content<?php echo $language['language_id']; ?>', {
      filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
    });
    CKEDITOR.replace('mail-customer-fail-content<?php echo $language['language_id']; ?>', {
      filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
    });
  <?php } ?>

  $('#tabs a').tabs();

  $(document).ready(function(){
    $('input:radio[name^="shoputils_psb_laterpay_mode"]:checked').each(function(indx){
      changeLaterpayMode($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_customer_success"]:checked').each(function(indx){
      changeNotifyCustomerSuccess($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_customer_fail"]:checked').each(function(indx){
      changeNotifyCustomerFail($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_admin_success"]:checked').each(function(indx){
      changeNotifyAdminSuccess($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_admin_fail"]:checked').each(function(indx){
      changeNotifyAdminFail($(this).val());
    });
  });
    $('input:radio[name^="shoputils_psb_laterpay_mode"]').change(function(){
      changeLaterpayMode($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_customer_success"]').change(function(){
      changeNotifyCustomerSuccess($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_customer_fail"]').change(function(){
      changeNotifyCustomerFail($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_admin_success"]').change(function(){
      changeNotifyAdminSuccess($(this).val());
    });
    $('input:radio[name^="shoputils_psb_notify_admin_fail"]').change(function(){
      changeNotifyAdminFail($(this).val());
    });
  function changeLaterpayMode(value) {
      value == '1' ? $("#laterpay-mode-tr").fadeIn('100') : $("#laterpay-mode-tr").fadeOut('100').delay(200);
  }
  function changeNotifyCustomerSuccess(value) {
      value == '1' ? $("#mail-customer-success-subject-tr").fadeIn('100') : $("#mail-customer-success-subject-tr").fadeOut('100').delay(200);
      value == '1' ? $("#mail-customer-success-content-tr").fadeIn('100') : $("#mail-customer-success-content-tr").fadeOut('100').delay(200);
  }
  function changeNotifyCustomerFail(value) {
      value == '1' ? $("#mail-customer-fail-subject-tr").fadeIn('100') : $("#mail-customer-fail-subject-tr").fadeOut('100').delay(200);
      value == '1' ? $("#mail-customer-fail-content-tr").fadeIn('100') : $("#mail-customer-fail-content-tr").fadeOut('100').delay(200);
  }
  function changeNotifyAdminSuccess(value) {
      value == '1' ? $("#mail-admin-success-subject-tr").fadeIn('100') : $("#mail-admin-success-subject-tr").fadeOut('100').delay(200);
      value == '1' ? $("#mail-admin-success-content-tr").fadeIn('100') : $("#mail-admin-success-content-tr").fadeOut('100').delay(200);
  }
  function changeNotifyAdminFail(value) {
      value == '1' ? $("#mail-admin-fail-subject-tr").fadeIn('100') : $("#mail-admin-fail-subject-tr").fadeOut('100').delay(200);
      value == '1' ? $("#mail-admin-fail-content-tr").fadeIn('100') : $("#mail-admin-fail-content-tr").fadeOut('100').delay(200);
  }


  $('#button-clear').bind('click', function() {
    if (confirm('<?php echo $text_confirm; ?>')){
      $.ajax({
        url: '<?php echo $clear_log; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-clear').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
        },
        complete: function() {
          $('.loading').remove();
        },
        success: function(json) {
          $('.success, .warning').remove();
                
          if (json['error']) {
            $('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
            $('.warning').fadeIn('slow');
          }
          
          if (json['success']) {
                    $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
            
            $('#pre-log').empty();
            $('.success').fadeIn('slow');
          }

          $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        }
      });
    }
  });

  $('#button_calculate').bind('click', function() {
      $.ajax({
        url: '<?php echo $key_calculate; ?>',
        type: 'post',
        dataType: 'json',
        data: 'component1=' + encodeURIComponent($('input[name=\'component1\']').val()) + '&component2=' + encodeURIComponent($('input[name=\'component2\']').val()),
        beforeSend: function() {
          $('#button_calculate').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
        },
        complete: function() {
          $('.loading').remove();
        },
        success: function(json) {
          $('.success, .warning').remove();
                
          if (json['error']) {
            $('#calculate-result').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
            $('.warning').fadeIn('slow');
          }
          
          if (json['success']) {
              $('#calculate-result').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
              $('[name="shoputils_psb_merchant_key"]').attr('value', json['result']);
              $('.success').fadeIn('slow');
          }
        }
      });
  });
//--></script>
<?php echo $footer; ?>
