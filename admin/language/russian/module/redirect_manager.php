<?php
$version = 'v156.2';

// Heading
$_['heading_title']				= 'Менеджер редиректов';

// Buttons
$_['button_save_exit']			= 'Сохранить и выйти';
$_['button_save_keep_editing']	= 'Сохранить и продолжить';
$_['button_import_csv']			= 'Импорт CSV';
$_['button_export_csv']			= 'Экспорт CSV';
$_['button_reset_all']			= 'Сбросить все';
$_['button_delete_all']			= 'Удалить все';
$_['button_add_row']			= 'Добавить редирект';

// Entries
$_['entry_sort_by']				= 'Сортировать:';
$_['entry_order']				= 'Порядок:';
$_['entry_ascending']			= 'Возраст.';
$_['entry_descending']			= 'Спад.';
$_['entry_status']				= 'Статус:';
$_['entry_active']				= 'Активный';
$_['entry_from_url']			= 'С УРЛА';
$_['entry_to_url']				= 'НА УРЛ';
$_['entry_date_start']			= 'Дата старта';
$_['entry_date_end']			= 'Дата финиша';
$_['entry_response_code']		= 'Код ответа сервера';
$_['entry_times_used']			= 'Использовано, раз';

// Text
$_['text_help']					= '
	<ol class="help" style="margin: 0; line-height: 1.5">		
		<li>На слэши вначале и в конце можно не обращать внимания.</li>
		<li>Урл редиректится на тот же сайт, с которого был запрошен! Т.е. указывать напрямую http:// - не нужно.</li>
		<li>По дате - поля можно оставить пустыми, можно указать. Тогда будет редирект в зависимости от разрешенных дат</li>		
	</ol>';
$_['text_moved_permanently']	= '301 Moved Permanently';
$_['text_found']				= '302 Found';
$_['text_temporary_redirect']	= '307 Temporary Redirect';
$_['text_warning']				= 'Эту операцию нельзя отменить! Продолжаем?';

// Copyright
$_['copyright']					= '';

// Standard Text
$_['standard_module']			= 'Modules';
$_['standard_shipping']			= 'Shipping';
$_['standard_payment']			= 'Payments';
$_['standard_total']			= 'Order Totals';
$_['standard_feed']				= 'Product Feeds';
$_['standard_success']			= 'Отлично: изменили модуль ' . $_['heading_title'] . '!';
$_['standard_error']			= 'Warning: You do not have permission to modify ' . $_['heading_title'] . '!';