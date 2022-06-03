<?php
// Heading
$_['heading_title']          = 'Отзывы после покупки';

// Text
$_['text_module']            = 'Модули';
$_['text_success']           = 'Success: You have modified module after purchase review invitation!';
$_['text_help_customized']   = 'Можно использовать шорткоды {firstname} {lastname} {store_name} {store_email} {store_telephone} {purchased_products_table} {unsubscribe_link}';

// Tabs
$_['tab_general']            = 'Общие настройки'; 
$_['tab_mail']               = 'Почта'; 

// Entry
$_['entry_cron_password']    = 'Пароль cron:';
$_['entry_start_date']       = 'Дата начала: <span class="help">(Optional) Слать сообщения ТОЛЬКО для заказов, оформленных после этой даты.</span>';
$_['entry_days_after']       = 'Возраст: <span class="help">(Optional) Слать сообщения ТОЛЬКО для заказов, старше N дней</span>';
$_['entry_allowed_statuses'] = 'Статусы заказов:<span class="help">Слать сообщения только для заказов этого статуса<br /> (Завершенные)</span>';
$_['entry_allow_unsubscribe']= 'Разрешить отписку?<span class="help">if option is enabled and customer unsubscribe, then extension can\'t send anymore review invitations to that customer</span>';
$_['entry_log_to_admin']     = 'Отсылать лог админу:';
$_['entry_use_html_email']   = 'Send Review Invitation with HTML Email Extension:<span class="help"><br />If HTML Email Extension is not installed on your store then is used default html mail (like in old versions of this extensions)</span>';
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
$_['error_html_email_not_installed'] = 'Review Invitation Emails can\'t be sent with HTML Email Extension because this extension is not available on your store. Please set option to Disabled!';

?>