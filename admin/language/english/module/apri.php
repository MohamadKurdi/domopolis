<?php
// Heading
$_['heading_title']          = 'After Purchase Review Invitation';

// Text
$_['text_module']            = 'Modules';
$_['text_success']           = 'Success: You have modified module after purchase review invitation!';
$_['text_help_customized']   = 'For customized mail subject / message you can use {firstname} {lastname} {store_name} {store_email} {store_telephone} {purchased_products_table} {unsubscribe_link}<br />Check email_example.txt from zip archive purchased from oc-extensions.com';

// Tabs
$_['tab_general']            = 'General'; 
$_['tab_mail']               = 'Mail'; 

// Entry
$_['entry_cron_password']    = 'Cron password:';
$_['entry_start_date']       = 'Start Date: <span class="help">(Optional) send review mail invitation ONLY for orders added after Start Date.</span>';
$_['entry_days_after']       = 'Days after: <span class="help">(Optional) send invitation only for orders older than x days</span>';
$_['entry_allowed_statuses'] = 'Allowed Order Status:<span class="help">send invitation for customers that have orders wit specified status<br />Recomended: Complete</span>';
$_['entry_allow_unsubscribe']= 'Allow unsubscribe?<span class="help">if option is enabled and customer unsubscribe, then extension can\'t send anymore review invitations to that customer</span>';
$_['entry_log_to_admin']     = 'Send Log Summary to Admin:';
$_['entry_use_html_email']   = 'Send Review Invitation with <a href="http://www.oc-extensions.com/HTML-Email">HTML Email Extension</a>:<span class="help"><br />If HTML Email Extension is not installed on your store then is used default html mail (like in old versions of this extensions)</span>';
$_['entry_mail_subject']     = 'Mail subject:';
$_['entry_mail_message']     = 'Mail message:';
$_['entry_mail_log_subject'] = 'Admin Summnary Mail - subject:';
$_['entry_mail_log_message'] = 'Admin Summnary Mail - message:<span class="help">{recipients_list} - list of customers that received email</span>';

// Error
$_['error_permission']       = 'Warning: You do not have permission to modify module after purchase review invitation!';
$_['error_cron_password']    = 'Error: Cron password - required!';
$_['error_allowed_statuses'] = 'Error: Allowed Statuses - required!';
$_['error_mail_subject']     = 'Error: Mail subject is required!';
$_['error_mail_message']     = 'Error: Mail message is required!';
$_['error_mail_message_unsubscribe'] = 'Remove UNSUBSCRIBE LINK from mail message when \'Allow unsubscribe\' option is Disabled';
$_['error_mail_log_subject'] = 'Error: Mail Log subject is required!';
$_['error_mail_log_message'] = 'Error: Mail Log message is required!';
$_['error_html_email_not_installed'] = 'Review Invitation Emails can\'t be sent with <a href="http://www.oc-extensions.com/HTML-Email">HTML Email Extension</a> because this extension is not available on your store. Please set option to Disabled!';

?>