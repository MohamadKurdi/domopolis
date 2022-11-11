<?php
// Heading
$_['heading_title']    = 'Prepayment 2.3_feofan.net';

// Text
$_['text_total']       = 'Prepayment';
$_['text_success']     = 'Settings updated!';
$_['text_hint']     = '<span style="color:#ff0000"> - To make this module work properly please set Sort Order setting with greater value (ex. 999) than the value of all modules in category \'Order totals\'. </span>';
$_['text_module_help'] = 'You can create the list of order conditions which will activate prepayment module. Please set the way of calculating the amount of the prepayment in the right part of each table.';

// Entry
$_['entry_status']     = 'Status:';
$_['entry_condition'] = 'Prepayment activation conditions';
$_['entry_sort_order'] = 'Sort order:';

$_['entry_turn_on_prepayment_when'] = 'Activate prepayment module when <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Prepayment module will be activated if specified conditions are true.">[?]</span>:';
$_['entry_turn_on_prepayment_for_shipping'] = 'The following shipping method is chosen <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Activate the module when one of the checked shipping methods is chosen.">[?]</span>';
$_['entry_turn_on_prepayment_for_payment_method'] = 'The following payment method is chosen<span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Activate the module when one of the checked payment methods is chosen. Please check at least one of the methods.">[?]</span>';
$_['entry_for_total_items_price'] = 'The total amount <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="If you want to activate module only for orders which have specific total price of the items. These fields may be empty.">[?]</span>';
$_['entry_count_as'] = 'Calculate prepayment amount as <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Please specify how to calculate the prepayment amount.">[?]</span>:';
$_['entry_prepayment_percent_part'] = 'Percent of the:';
$_['entry_prepayment_percent_part_shipping'] = 'shipping';
$_['entry_prepayment_percent_part_total_price'] = 'total items price';
$_['entry_prepayment_comment'] = 'The text to show to the customer';

$_['entry_from'] = 'from';
$_['entry_to'] = 'to';

$_['entry_prepayment_amount_fixed_selection'] = 'Fixed prepayment amount';
$_['entry_prepayment_by_shipping'] = 'shipping price';
$_['entry_prepayment_by_total_price'] = 'total items price';
$_['entry_confirm_remove_filter'] = 'Are you sure you want to delete the condition?';

$_['entry_payment_method_validation_tip'] = 'Please check at least one of the payment methods';

$_['button_add_filter'] = 'Add condition of the prepayment activation';

// Error
$_['error_permission'] = 'Access denied!';
?>