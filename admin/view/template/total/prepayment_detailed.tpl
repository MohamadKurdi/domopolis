<!-- Header Begin -->
<?php echo $header; ?>
<!-- Header End -->
<!-- Prepayment body Begin -->
<script type="text/javascript">
	function processNumericInput(textField, minValue, maxValue) {
		
		textField.style.borderColor = "";
		
		if (/\D/g.test(textField.value)) {
			textField.value = textField.value.replace(/\D/g,'');
		}
		
		var value = textField.value;
		// check whether value is numeric
		if (!isNaN(parseFloat(value)) && isFinite(value)) {
			
			if (minValue >= value) {
				textField.value = minValue;
			}
			
			if (maxValue <= value) {
				textField.value = maxValue;
			}
		}
		
		return true;
	}
</script>
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
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="saveSettings()" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="filter" class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="prepayment_detailed_status">
                <?php if ($prepayment_detailed_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td>
				<input type="text" name="prepayment_detailed_sort_order" value="<?php echo $prepayment_detailed_sort_order; ?>" size="1" />
				<?php echo $text_hint; ?>
			</td>
          </tr>
		  <tr>
			<td colspan="2">
				<p><?php echo $text_module_help; ?> </p>
				<table class="list">
					<thead><tr><td class="center"><?php echo $entry_condition; ?></td></tr></thead>
				</table>
		  </tr>
		  
				<?php 
					$filter_row = 0;
					foreach ($filters as $filter) { ?>
		  <tr id="filter-row<?php echo $filter_row; ?>">            
     		<td colspan="2">
					<table class="list">
						<thead>
							<tr>						
								<td colspan="3" class="center"><?php echo $entry_turn_on_prepayment_when; ?></td>
								<td rowspan="2" class="center"><?php echo $entry_count_as; ?></td>
								<td rowspan="2" class="center">&nbsp;</td>
							</tr>
							<tr>						
								<td class="center"><?php echo $entry_turn_on_prepayment_for_shipping; ?></td>
								<td class="center"><?php echo $entry_turn_on_prepayment_for_payment_method; ?></td>
								<td class="center"><?php echo $entry_for_total_items_price; ?></td>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td>
								<?php 
									$shipping_codes = array();
									foreach ($shippings as $shipping) { 
										if (!isset($filter["shipping_method"][$shipping['fname']])) {
											$filter["shipping_method"][$shipping['fname']] = "";
										}
										$shipping_codes[] = $shipping['fname'];
								?>
								<input type="checkbox" 
									name="prepayment_detailed_filter[<?php echo $filter_row; ?>][shipping_method][<?php echo $shipping['fname']; ?>]" id="prepayment_filter_shipping_method_<?php echo $shipping['fname'] . "_" . $filter_row; ?>" <?php echo $filter["shipping_method"][$shipping['fname']]; ?> value="checked" /> <label for="prepayment_filter_shipping_method_<?php echo $shipping['fname'] . "_" . $filter_row; ?>" name="shipping_method_label_<?php echo $filter_row; ?>"><?php echo $shipping['hname']; ?></label><br />
								<?php } ?>
							</td>
							<td>
								<?php 
									$payment_method_codes = array();
									foreach ($payment_methods as $payment_method) { 
										if (!isset($filter["payment_method"][$payment_method['fname']])) {
											$filter["payment_method"][$payment_method['fname']] = "";
										}
										$payment_method_codes[] = $payment_method['fname'];
								?>
								<input type="checkbox" 
									name="prepayment_detailed_filter[<?php echo $filter_row; ?>][payment_method][<?php echo $payment_method['fname']; ?>]" id="prepayment_filter_payment_method_<?php echo $payment_method['fname'] . "_" . $filter_row; ?>" <?php echo $filter["payment_method"][$payment_method['fname']]; ?> value="checked" onclick="resetValidationForLabels('payment_method_label_<?php echo $filter_row; ?>', 'prepayment_payment_method_validation_tip_<?php echo $filter_row; ?>')"/> <label for="prepayment_filter_payment_method_<?php echo $payment_method['fname'] . "_" . $filter_row; ?>" name="payment_method_label_<?php echo $filter_row; ?>"><?php echo $payment_method['hname']; ?></label><br />
								<?php } ?>
								<span style="color:red; font-weight: bold; display:none;" id="prepayment_payment_method_validation_tip_<?php echo $filter_row ?>"><?php echo $entry_payment_method_validation_tip; ?></span>
							</td>
							<td>
								<?php echo $entry_from; ?> <input size="6" name="prepayment_detailed_filter[<?php echo $filter_row; ?>][price_from]" value="<?php echo $filter['price_from']; ?>" type="text" onkeyup="processNumericInput(this, 0, 999999);" /> <br />
								<?php echo $entry_to; ?> <input size="6" name="prepayment_detailed_filter[<?php echo $filter_row; ?>][price_to]" value="<?php echo $filter['price_to']; ?>" type="text" onkeyup="processNumericInput(this, 0, 999999);" />
							</td>
							<td>
<?php 
	$prepayment_amount_fixed_selection = "";
	$prepayment_amount_percent_selection = "";
	
	switch ($filter["amount_percent_fixed_option"]) {
		case "fixed": $prepayment_amount_fixed_selection = "checked"; break;
		case "percent": $prepayment_amount_percent_selection = "checked"; break;
	}
?>
								<input name="prepayment_detailed_filter[<?php echo $filter_row; ?>][amount_percent_fixed_option]" id="prepayment_detailed_amount_fixed_option_<?php echo $filter_row?>" value="fixed" type="radio" <?php echo $prepayment_amount_fixed_selection ?> onclick="resetValidationForValues(<?php echo $filter_row ?>);"/> <label for="prepayment_detailed_amount_fixed_option_<?php echo $filter_row?>"><?php echo $entry_prepayment_amount_fixed_selection; ?></label>: <input size="4" name="prepayment_detailed_filter[<?php echo $filter_row; ?>][amount_fixed]" id="prepayment_detailed_amount_fixed_value_<?php echo $filter_row; ?>" value="<?php echo $filter["amount_fixed"]; ?>" type="text" onkeyup="processNumericInput(this, 0, 999999);" /><br />
								<input name="prepayment_detailed_filter[<?php echo $filter_row; ?>][amount_percent_fixed_option]" id="prepayment_detailed_amount_percent_option_<?php echo $filter_row?>" value="percent" type="radio" <?php echo $prepayment_amount_percent_selection ?> onclick="resetValidationForValues(<?php echo $filter_row ?>);"><label for="prepayment_detailed_amount_percent_option_<?php echo $filter_row?>"><?php echo $entry_prepayment_percent_part; ?></label> <br />
								
								<div style="padding-left:20px;">
<?php 
//print_r($totals);
	for ($iTotal = 0; $iTotal < count($totals); $iTotal++) {
		$total = $totals[$iTotal];
		if (!array_key_exists("amount_percent_" . $total["code"], $filter)) {
			$filter["amount_percent_" . $total["code"]] = 0;
		}
		echo '<input size="4" name="prepayment_detailed_filter[' . $filter_row .'][amount_percent_' . $total["code"] . ']" id="prepayment_detailed_amount_percent_' . $total["code"] . '_value_' . $filter_row . '" value="' . $filter["amount_percent_" . $total["code"]] . '" type="text" onkeyup="processNumericInput(this, 0, 9999);" />% ' . $entry_from . ' ' . $total["name"] . '<br />'; 
		if ($iTotal != count($totals) - 1) {
			echo "<span>+</span> <br />";
		}
	}
?>								
								</div>
							</td>
							<td style="text-align:center;" rowspan="2">
								<a onclick="removeFilter('#filter-row<?php echo $filter_row; ?>')" class="button"><?php echo $button_remove ?></a>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $entry_prepayment_comment; ?>
							</td>
							<td colspan="3" style="padding: 5px;">
								<textarea name="prepayment_detailed_filter[<?php echo $filter_row; ?>][comment]" style="width: 100%" rows="5" ><?php echo $filter['comment']; ?></textarea>
							</td>
						</tr>
						</tbody>
					</table>
			</td>                  
		  </tr>
				<?php $filter_row++; } ?>
				
		<tfoot>
          <tr>		  
			<td colspan="2" style="text-align: center;">
				<a onclick="addFilter();" class="button"><?php echo $button_add_filter ?></a>
				<a onclick="saveSettings();" class="button"><?php echo $button_save; ?></a>
			</td>
          </tr>
        </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var filter_rows_count = <?php echo $filter_row; ?>;
var shipping_codes = new Array(<?php if (!empty($shipping_codes)) echo "\"" . implode("\",\"", $shipping_codes) . "\""; ?>);
var payment_codes = new Array(<?php if (!empty($payment_method_codes)) echo "\"" . implode("\",\"", $payment_method_codes) . "\""; ?>);

function addFilter() {	
	html  = '<tr id="filter-row' + filter_rows_count + '"><td colspan="2"><table class="list">\r\n';
	html += '	<thead>\r\n';
	html += '		<tr>\r\n';
	html += '			<td colspan="3" class="center"><?php echo $entry_turn_on_prepayment_when; ?></td>\r\n';
	html += '			<td rowspan="2" class="center"><?php echo $entry_count_as; ?></td>\r\n';
	html += '			<td rowspan="2" class="center">&nbsp;</td>\r\n';
	html += '		</tr>\r\n';
	html += '		<tr>\r\n';
	html += '			<td class="center"><?php echo $entry_turn_on_prepayment_for_shipping; ?></td>\r\n';
	html += '			<td class="center"><?php echo $entry_turn_on_prepayment_for_payment_method; ?></td>\r\n';
	html += '			<td class="center"><?php echo $entry_for_total_items_price; ?></td>\r\n';
	html += '		</tr>\r\n'
			+ '	</thead>\r\n'
			+ '	<tbody>\r\n'
			+ '	<tr>\r\n'
			+ ' 	<td>\r\n';
							
	<?php foreach ($shippings as $shipping) { ?>
		html += '		<input type="checkbox" ';
		html += 'name="prepayment_detailed_filter[' + filter_rows_count + '][shipping_method][<?php echo $shipping['fname']; ?>]" id="prepayment_filter_shipping_method_<?php echo $shipping['fname']; ?>_' + filter_rows_count + '" value="checked" /> <label for="prepayment_filter_shipping_method_<?php echo $shipping['fname']; ?>_' + filter_rows_count + '" name="shipping_method_label_' + filter_rows_count + '"><?php echo $shipping['hname']; ?></label><br />\r\n';
	<?php } ?>
	html += '			<span style="color:red; font-weight: bold; display:none;" id="prepayment_shipping_method_validation_tip_' + filter_rows_count + '"><?php echo $entry_shipping_validation_tip; ?></span>';
	html += '		</td>';
	html += '		<td>';
	<?php foreach ($payment_methods as $payment_method) { ?>
		html += '		<input type="checkbox" ';
		html += 'name="prepayment_detailed_filter[' + filter_rows_count + '][payment_method][<?php echo $payment_method['fname']; ?>]" id="prepayment_filter_payment_method_<?php echo $payment_method['fname']; ?>_' + filter_rows_count + '" value="checked" onclick="resetValidationForLabels(\'payment_method_label_' + filter_rows_count + '\', \'prepayment_payment_method_validation_tip_' + filter_rows_count + '\')"/> <label for="prepayment_filter_payment_method_<?php echo $payment_method['fname']; ?>_' + filter_rows_count + '" name="payment_method_label_' + filter_rows_count + '"><?php echo $payment_method['hname']; ?></label><br />';
	<?php } ?>
	html += '			<span style="color:red; font-weight: bold; display:none;" id="prepayment_payment_method_validation_tip_' + filter_rows_count + '"><?php echo $entry_payment_method_validation_tip; ?></span>';
	html += '		</td>\r\n';
	html += '		<td>\r\n';
	html += '			<?php echo $entry_from; ?> <input size="6" name="prepayment_detailed_filter[' + filter_rows_count + '][price_from]" value="" type="text" onkeyup="processNumericInput(this, 0, 999999);" /> <br />\r\n';
	html += '			<?php echo $entry_to; ?> <input size="6" name="prepayment_detailed_filter[' + filter_rows_count + '][price_to]" value="" type="text" onkeyup="processNumericInput(this, 0, 999999);" />\r\n';
	html += '		</td>\r\n';
	html += '		<td>\r\n';
	html += '			<input name="prepayment_detailed_filter[' + filter_rows_count + '][amount_percent_fixed_option]" id="prepayment_detailed_amount_fixed_option_' + filter_rows_count + '" value="fixed" type="radio" checked onclick="resetValidationForValues(' + filter_rows_count + ');"/> <label for="prepayment_detailed_amount_fixed_option_' + filter_rows_count + '"><?php echo $entry_prepayment_amount_fixed_selection; ?></label>: <input size="4" id="prepayment_detailed_amount_fixed_value_' + filter_rows_count + '" name="prepayment_detailed_filter[' + filter_rows_count + '][amount_fixed]" value="10" type="text" onkeyup="processNumericInput(this, 0, 999999);" /><br />\r\n';
	html += '			<input name="prepayment_detailed_filter[' + filter_rows_count + '][amount_percent_fixed_option]" id="prepayment_detailed_amount_percent_option_' + filter_rows_count + '" value="percent" type="radio" onclick="resetValidationForValues(' + filter_rows_count + ')";><label for="prepayment_detailed_amount_percent_option_' + filter_rows_count + '"><?php echo $entry_prepayment_percent_part; ?></label><br />\r\n';
								
	html += '			<div style="padding-left:20px;">';
	
<?php 
//print_r($totals);
	for ($iTotal = 0; $iTotal < count($totals); $iTotal++) {
		$total = $totals[$iTotal];		
		echo 'html += \'<input size="4" name="prepayment_detailed_filter[\' + filter_rows_count + \'][amount_percent_' . $total["code"] . ']" id="prepayment_detailed_amount_percent_' . $total["code"] . '_value_\' + filter_rows_count + \'" value="0" type="text" onkeyup="processNumericInput(this, 0, 9999);" />% ' . $entry_from . ' ' . $total["name"] . '<br />\';'; 
		if ($iTotal != count($totals) - 1) {
			echo "html += '<span>+</span> <br />';";
		}
	}
?>
	
	html += '			</div>';
								
	html += '		</td>\r\n';
	html += '		<td style="text-align:center;" rowspan="2">\r\n';
	html += '			<a onclick="removeFilter(\'#filter-row' + filter_rows_count + '\')" class="button"><?php echo $button_remove ?></a>\r\n';
	html += '		</td>\r\n';
	html += '	</tr>\r\n';
	html += '	<tr>\r\n';
	html += '		<td>\r\n';
	html += '			<?php echo $entry_prepayment_comment; ?>';
	html += '		</td>\r\n';
	html += '		<td colspan="3" style="padding: 5px;">\r\n';
	html += '			<textarea name="prepayment_detailed_filter[' + filter_rows_count + '][comment]" style="width: 100%" rows="5" ></textarea>';
	html += '		</td>\r\n';
	html += '	</tr>\r\n';
	html += ' </tbody>\r\n';
	html += '</table>\r\n</td></tr>';
	
	$('#filter tfoot').before(html);
	
	filter_rows_count++;
}

function removeFilter(filterRowElement) {
	if (confirm('<?php echo $entry_confirm_remove_filter; ?>')) {
		$(filterRowElement).remove(); 	
	}
}

function saveSettings() {
	
	var success = true;
	
	// validate required fields
	for (var i = 0; i < filter_rows_count; i++) {
		if ($('#prepayment_detailed_amount_fixed_value_' + i).length == 0) {
			continue;
		}
		var amountFixedValue = $('#prepayment_detailed_amount_fixed_value_' + i).val();
		
		if ($('#prepayment_detailed_amount_fixed_option_' + i).attr('checked') == 'checked'
				&& (isNaN(parseFloat(amountFixedValue)) || !isFinite(amountFixedValue))) {
			success = false;
			$('#prepayment_detailed_amount_fixed_value_' + i).css("border-color", "red");
		}
		
		var paymentChecked = false;
		
		// check if at least one of payment methods is checked
		for (var payment_code_index = 0; payment_code_index < payment_codes.length; payment_code_index++) {
			var id = '#prepayment_filter_payment_method_' + payment_codes[payment_code_index] + "_" + i;
			if ($(id).attr('checked') == 'checked') {				
				paymentChecked = true;
				$('#prepayment_payment_method_validation_tip_' + i).css("display", "none");
				break;
			}
		}
		
		if (!paymentChecked) {
			$('label[name="payment_method_label_' + i + '"]').css("color", "red");
			$('#prepayment_payment_method_validation_tip_' + i).css("display", "inline");
			success = false;
		}
	}
	
	if (success) {
		$('#form').submit();
	}
}

function resetValidationForValues(filter_num) {
	$('#prepayment_detailed_amount_fixed_value_' + filter_num).css("border-color", "");
	$('#prepayment_detailed_amount_percent_shipping_value_' + filter_num).css("border-color", "");
	$('#prepayment_detailed_amount_percent_total_price_value_' + filter_num).css("border-color", "");
}

function resetValidationForLabels(labelName, validationTipId) {
	$('label[name="' + labelName+ '"]').css("color", "");
	$('#' + validationTipId).css("display", "none");
}
//--></script>

<!-- Prepayment body End -->
<!-- Footer Start -->
<?php echo $footer; ?>
<!-- Footer End -->