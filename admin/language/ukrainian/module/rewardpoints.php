<?php
// Heading
$_['heading_title']                = 'Расширенные бонусы';

// Text
$_['text_version']                 = 'Расширенные бонусы v2.0';
$_['text_module']                  = 'Модули';
$_['text_success']                 = 'Успешно: Вы модифицировали Расширенные бонусы!';

// Entry
$_['entry_auto_checkout']          = 'Автоматически использовать бонусы:<br/><span class="help">Если у клиента доступны бонусы, они автоматически будут применены к заказам.</span>';
$_['entry_allow_discounting']      = 'Разрешить Дисконт:<br/><span class="help">Если товар можно приобрести за бонусы, по умолчанию они могут снизить процент от цены.</span>';
$_['entry_purchase_url']           = 'URL адрес приобретения бонусов:<br/><span class="help">если бонусы можно приобрести на сайте, введи адрес. Если нет - оставте поле пустым.</span>';

$_['text_allow_discounting']       = 'Расрешить Дисконт (Default)';
$_['text_full_points']             = 'Требовать все бонусы';

$_['entry_cancelled_orders']       = 'Отмененные заказы:<br /><span class="help">Если клиент отменил заказ, бонусы НЕ начисляются на его счет</span>';
$_['entry_completed_orders']       = 'Завершенные заказы:<br /><span class="help">Если клиент оплатил заказ, бонусы ДОЛЖНЫ быть начислены на его счет.</span>';

//REWARD POINTS DISPLAY FORMATTING
$_['heading_currency']             = 'Настройки показа';
$_['entry_currency_mode']          = 'Режим бонусов:<br/><span class="help">Как призовые баллы показывают и ведут себя.</span>';
$_['entry_hidden_zero']            = 'Скрывать, если ноль:<span class="help">Если за товар нет бонусов отсутствуют, то их не показывать.</span>';
$_['entry_subtext_display']        = 'Тект получения бонусов:<span class="help">Отключите, если используете другой текст.</span>';
$_['entry_pop_notification']       = 'Уведомление:<span class="help">Показывать уведомление на странице товара, если он моджет быть приобетен с помощью бонусов.</span>';
$_['entry_currency_prefix']        = 'Префикс бонусов:<span class="help">Показывать префикс бонусов (на списках товаров) со следующим характером/символом.</span>';
$_['entry_currency_suffix']        = 'Суффикс бонусов:<span class="help">Показывать суффикс бонусов (на списках итоваров) со следующим характером/символом.</span>';

$_['text_zero_display']            = 'Показывать, если ноль (Default)';
$_['text_zero_hide']               = 'Скрывать, если ноль';
$_['text_display_attribute']       = 'Показывать как атрибут (Default)';
$_['text_display_subtext']         = 'Показывать как текст';
$_['text_integer']                 = 'Как целое число (Default)';
$_['text_float']                   = 'Как измененная (Валюта)';

//REWARD POINT BONUSES
$_['heading_bonuses']              = 'Призовые бонусы';

$_['entry_registration_bonus']     = 'Регистрация аккаунта:<span class="help">Сколько бонусов получает клиент при регистрации аккаунта.</span>';
$_['entry_newsletter_bonus']       = 'Подписка на новости:<span class="help">Сколько бонусов получает клиент при подписке на новостную рассылку.</span>';
$_['entry_newsletter_unsubscribe'] = 'Отписка от рассылки:<span class="help">Удалить со счета бонусы, если клиент отказался от рассылки.</span>';
$_['entry_order_bonus']            = 'Первый заказ:<span class="help">Сколько бонусов получает клиент при первом заказе.</span>';
$_['entry_review_bonus']           = 'Отзыв о товаре:<span class="help">Сколько бонусов получает клиеннт, если разместит отзыв о товаре.</span>';
$_['entry_review_limit']           = 'Лимит отзывов о товарах:<span class="help">Общее количество времен клиент может получить премию для рассмотрения продукта.</span>';
$_['entry_review_auto_approve']    = 'Одобрение отзыва:<span class="help">Автоматически одобрите обзоры выше следующего звездного рейтинга.</span>';
$_['text_unlimited']               = 'Неограничено';


//EMAIL REMINDERS
$_['heading_email_reminder']       = 'Пользование электронной почтой о напоминание продукции';

$_['entry_email_reminder_enabled'] = 'Reminders Enabled:<span class="help">If e-mail reminders should be sent out or not.</span>';
$_['entry_email_status']           = 'Order Status Reminder:<span class="help">Select one or more order statuses that is acceptable for a review reminder to be dispatched.</span>';
$_['entry_email_date']             = 'Date Filter:<span class="help">Which date should be using in calculating what review reminder e-mails should be sent.</span>';
$_['entry_email_days']             = 'Days Delayed:<span class="help">The number of days from order date to wait before sending a review reminder e-mail.</span>';
$_['entry_email_subject']          = 'E-Mail Subject:<span class="help">The subject line of review reminder e-mails.</span>';
$_['entry_email_content']          = 'Message Content:<span class="help">The content of the e-mail message that you will be sending.</span>';
$_['entry_email_cron']             = 'Cron Job Status:<span class="help">Shows the last run time of the automated cron job that dispatches reminder e-mails.</span>';
$_['entry_email_test']             = 'Send A Test E-Mail:<span class="help">Enter your e-mail address to send yourself a test e-mail.</span>';

$_['text_email_variables']         = '<span class="help"><strong>Supported Variables</strong><br />
										{first_name} - Имя клиента.<br />
										{last_name} - Фамилия клиента.<br />
										{order_id} - Заказ ID/Номер.<br />
										{review_bonus} - Бонусные призовые баллы для того, чтобы оставить обзор продукции.<br />
										{review_limit} - Максимальное время, в течение которого клиенты могут получить бонусы.<br /></span>';
$_['text_email_send_test']         = 'Отправить текст на E-Mail';
$_['text_success_email']           = 'Успешно: Вы отправили % электронных писем на e-mail!';
$_['text_success_email_test']      = 'Успешно: Вы отправили тестовое письмо на e-mail  <i>%</i>!';
$_['text_success_cron']            = '<span class="help"><font color="green"><strong>Активация</strong></font><br />Last Run Dispatched %s E-Mails.<br /> Last Job Run Time: %s</span>';
$_['text_date_created']            = 'Дата создания';
$_['text_date_modified']           = 'Дата изменения';

$_['button_send_reminders']        = 'Отправить Review Reminders (%s)';

// Error
$_['error_permission']    = 'Внимание: У вас недостаточно прав для изменения настроек модуля Расширенные бонусы!';


?>