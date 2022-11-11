<?php

// Heading
$_['heading_title'] = 'SMS-sending';
$_['text_module'] = 'Модули';

// Tabs
$_['oc_sms_sending_tab_connection'] = 'Подключение';
$_['oc_sms_sending_tab_member'] = 'Администратор';
$_['oc_sms_sending_tab_customer'] = 'Покупатель';

// Text
$_['oc_sms_sending_text_login'] = 'Логин';
$_['oc_sms_sending_text_password'] = 'Пароль';
$_['oc_sms_sending_text_signature'] = 'Имя отправителя';
$_['oc_sms_sending_text_debug']= 'Отладка';
$_['oc_sms_sending_text_admin_new_customer'] = 'Зарегистрирован новый покупатель';
$_['oc_sms_sending_text_admin_new_order'] = 'Осуществлен новый заказ';
$_['oc_sms_sending_text_admin_new_email'] = 'Поступило новое письмо с контактной формы магазина';
$_['oc_sms_sending_text_telephone'] = 'Номер телефона';
$_['oc_sms_sending_text_customer_new_order'] = 'Осуществлен новый заказ';
$_['oc_sms_sending_text_customer_new_order_status'] = 'Изменение статуса заказа';
$_['oc_sms_sending_text_customer_new_register'] = 'Успешное завершение регистрации';
$_['oc_sms_sending_text_notify_by_sms'] = 'Уведомлять по SMS';
$_['oc_sms_sending_text_notify'] = 'Тексты уведомлений';
$_['oc_sms_sending_label_admin_new_order'] = 'при новом заказе';
$_['oc_sms_sending_label_customer_new_order'] = 'при новом заказе';
$_['oc_sms_sending_label_customer_new_status'] = 'при изменении статуса заказа';

// Messages
$_['oc_sms_sending_message_customer_new_order_status'] = 'Статус заказа изменен';

// Other
$_['oc_sms_sending_text_connection_tab_description'] =
'Укажите логин и пароль для подключения к шлюзу SMS-sending и имя отправителя.<br />
Обратите внимание, что имя отправителя должно быть предварительно<br />
зарегистрировано в личном кабинете. Для добавления нового имени<br />
необходимо в личном кабинете в меню имена отправителя добавить<br />
новое имя отправителя.';

$_['oc_sms_sending_text_macros_description'] =
'В тексте SMS-уведомлений возможно указание специальных макросов, которые<br />
будут заменены на соответствующие значения при отправке сообщений.<br /><br />
Для уведомлений можно использовать следующие макросы:
<ul>
<li>{ORDER_NUM} - номер заказа
<li>{ORDER_SUM} - итоговая стоимость заказа (всего)
<li>{ORDER_STATUS} - статус заказа
<li>{FIRST_NAME} - имя покупателя
<li>{LAST_NAME} - фамилия покупателя
<li>{CR_PHONE} - номер телефона покупателя
<li>{CR_ADDR} - адрес доставки покупателя
<li>{GOODS_LIST} - список товаров в формате "Товар:Артикул:Количество:Итого"
</ul>';