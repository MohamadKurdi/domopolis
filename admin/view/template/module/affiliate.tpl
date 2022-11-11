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
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  		<table class="list">
			  <thead>
				<tr>
				  <td colspan="5" ><?php echo $entry_payment; ?></td>
				</tr>
			  </thead>
                        <tr>
                            <td rowspan="6">
							<input class="checkbox" type="checkbox" name="config_bonus_visible" id="config_bonus_visible" <?php if ($config_bonus_visible) { ?> checked="checked" <?php } ?> />
                                <label for="config_bonus_visible"></label><?php echo $text_bonus; ?></td>
                            <td colspan="2">
					</tr><tr></td><td>
                                <input class="checkbox" type="checkbox" name="cheque" id="cheque" <?php if ($cheque) { ?> checked="checked"<?php } ?> />
                                <label for="cheque"><?php echo $text_cheque; ?></label>
                            </td><td>
                                <input class="checkbox" type="checkbox" name="paypal" id="paypal" <?php if ($paypal) { ?> checked="checked" <?php } ?>/>
                                <label for="paypal"><?php echo $text_paypal; ?></label>
                            </td><td>
                                <input class="checkbox" type="checkbox" name="bank" id="bank" value="<?php echo $bank; ?>" <?php if ($bank) { ?> checked="checked" <?php } ?> />
                                <label for="bank"><?php echo $text_bank; ?></label>
                    </tr><tr></td><td>
                                <input class="checkbox" type="checkbox" name="qiwi" id="qiwi" <?php if ($qiwi) { ?> checked="checked" <?php } ?> />
                                <label for="qiwi"><?php echo $text_qiwi; ?></label>
                            </td><td>
                                <input class="checkbox" type="checkbox" name="card" id="card" <?php if ($card) { ?> checked="checked" <?php } ?> />
                                <label for="card"><?php echo $text_card; ?></label>
                            </td><td>
                                <input class="checkbox" type="checkbox" name="yandex" id="yandex" <?php if ($yandex) { ?> checked="checked"<?php } ?> />
                                <label for="yandex"><?php echo $text_yandex; ?></label>
                    </tr><tr></td><td>
                                <input class="checkbox" type="checkbox" name="webmoney" id="webmoney" <?php if ($webmoney) { ?> checked="checked" <?php } ?> />
                                <label for="webmoney"><?php echo $text_webmoney; ?></label>
						   </td><td>
                                <input class="checkbox" type="checkbox" name="webmoneyWMZ" id="webmoneyWMZ" <?php if ($webmoneyWMZ) { ?> checked="checked" <?php } ?>/>
                                <label for="webmoneyWMZ"><?php echo $text_webmoneyWMZ; ?></label>
                           </td><td>
                                <input class="checkbox" type="checkbox" name="webmoneyWMU" id="webmoneyWMU" <?php if ($webmoneyWMU) { ?> checked="checked" <?php } ?>/>
                                <label for="webmoneyWMU"><?php echo $text_webmoneyWMU; ?></label>
                   </tr><tr></td><td>
                                <input class="checkbox" type="checkbox" name="webmoneyWME" id="webmoneyWME" <?php if ($webmoneyWME) { ?> checked="checked" <?php } ?>/>
                                <label for="webmoneyWME"><?php echo $text_webmoneyWME; ?></label>
                           </td><td>
                                <input class="checkbox" type="checkbox" name="webmoneyWMY" id="webmoneyWMY" <?php if ($webmoneyWMY) { ?> checked="checked" <?php } ?> />
                                <label for="webmoneyWMY"><?php echo $text_webmoneyWMY; ?></label>
							</td><td>
                                <input class="checkbox" type="checkbox" name="webmoneyWMB" id="webmoneyWMB" <?php if ($webmoneyWMB) { ?> checked="checked" <?php } ?> />
                                <label for="webmoneyWMB"><?php echo $text_webmoneyWMB; ?></label>
                   </tr><tr></td><td>
                                <input class="checkbox" type="checkbox" name="webmoneyWMG" id="webmoneyWMG" <?php if ($webmoneyWMG) { ?> checked="checked" <?php } ?> />
                                <label for="webmoneyWMG"><?php echo $text_webmoneyWMG; ?></label>
                           </td><td>
                                <input class="checkbox" type="checkbox" name="AlertPay" id="AlertPay" <?php if ($AlertPay) { ?> checked="checked" <?php } ?>/>
                                <label for="AlertPay"><?php echo $text_AlertPay; ?></label>
							</td><td>
                                <input class="checkbox" type="checkbox" name="Moneybookers" id="Moneybookers" <?php if ($Moneybookers) { ?> checked="checked" <?php } ?>/>
                                <label for="Moneybookers"><?php echo $text_Moneybookers; ?></label>
					</tr><tr></td><td>
                                <input class="checkbox" type="checkbox" name="LIQPAY" id="LIQPAY" <?php if ($LIQPAY) { ?> checked="checked" <?php } ?>/>
                                <label for="LIQPAY"><?php echo $text_LIQPAY; ?></label>
							</td><td>
                                <input class="checkbox" type="checkbox" name="SagePay" id="SagePay" <?php if ($SagePay) { ?> checked="checked" <?php } ?>/>
                                <label for="SagePay"><?php echo $text_SagePay; ?></label>
							</td><td>
                                <input class="checkbox" type="checkbox" name="twoCheckout" id="twoCheckout" <?php if ($twoCheckout) { ?> checked="checked" <?php } ?>/>
                                <label for="twoCheckout"><?php echo $text_twoCheckout; ?></label>
							</td><td>
                                <input class="checkbox" type="checkbox" name="GoogleWallet" id="GoogleWallet" <?php if ($GoogleWallet) { ?> checked="checked" <?php } ?>/>
                                <label for="GoogleWallet"><?php echo $text_GoogleWallet; ?></label>
                            </td>
                        </tr>
                    </tbody>
                </table>
				<table class="list">
				  <thead>
					<tr>
					  <td colspan="6" >...</td>
					</tr>
				  </thead>
                    <tbody>
						<tr>
                            <td class="middle">
                                <input class="checkbox" type="checkbox" name="affiliate_add" id="affiliate_add" <?php if ($affiliate_add) { ?>checked="checked" <?php } ?> />
                                <label for="affiliate_add"><?php echo $entry_add; ?></label>
                            </td>
                            <td class="middle">
                                <input class="checkbox" type="checkbox" name="config_affiliate_number_tracking" id="config_affiliate_number_tracking" <?php if ($config_affiliate_number_tracking) { ?> checked="checked" <?php } ?>/>
                                <label for="config_affiliate_number_tracking"></label><?php echo $entry_number_tracking; ?>
                            </td>
                            <td class="middle">
                                <input class="checkbox" type="checkbox" name="category_visible" id="category_visible" <?php if ($category_visible) { ?> checked="checked" <?php } ?>/>
                                <label for="category_visible"><?php echo $entry_category_visible; ?></label>
                            </td>
							<td rowspan="2" class="middle"><?php echo $entry_affiliate_sumbol; ?>
							   <select name="config_affiliate_sumbol">
                                    <?php for ($i=1; $i<3; $i++) { ?>
                                    <?php if ($i == $affiliate_sumbol) { ?>
                                    <option value="<?php echo $i; ?>" selected="selected">
										<?php if ($i == 1) { echo '&'; } else { echo '?'; } ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $i; ?>">
										<?php if ($i == 1) { echo '&'; } else { echo '?'; } ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
							</td>
                        </tr><tr>
                            <td><?php echo $entry_days; ?> </br>
							<input type="text" name="affiliate_days" value="<?php echo $affiliate_days; ?>" /></td>
                            <td><?php echo $entry_total; ?></br>
							<input type="text" name="affiliate_total" value="<?php echo $affiliate_total; ?>" /></td>
                            <td><?php echo $entry_order_status; ?></br>
							   <select name="affiliate_order_status_id">
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $affiliate_order_status_id) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
							</td>
                        </tr>
                    </tbody>
                </table>
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="affiliate_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="affiliate_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="affiliate_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="affiliate_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="right"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="affiliate_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="affiliate_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="affiliate_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="affiliate_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>