<?php
// Heading
$_['heading_title']    = 'Предоплата 2.3';

// Text
$_['text_total']       = 'Предоплата заказа';
$_['text_success']     = 'Настройки модуля обновлены!';
$_['text_hint']     = '<span style="color:#ff0000"> - Для корректной работы модуля, установите порядок сортировки бОльшим (например 999), чем у остальных модулей категории "Учитывать в заказе". </span>';
$_['text_module_help'] = 'Ниже Вы можете создать список условий, при которых будет активироваться модуль предоплаты, а также указать способ вычисления суммы предоплаты.';

// Entry
$_['entry_status']     = 'Статус:';
$_['entry_condition'] = 'Условия включения предоплаты';
$_['entry_sort_order'] = 'Порядок сортировки:';

$_['entry_turn_on_prepayment_when'] = 'Включить предоплату при выполнении следующих условий <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Модуль Предоплата будет включен при выполнении указанных ниже условий заказа">[?]</span>:';
$_['entry_turn_on_prepayment_for_shipping'] = 'Выбран способ доставки <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Включить модуль предоплаты, когда выбран один из отмеченных ниже способов доставки.">[?]</span>';
$_['entry_turn_on_prepayment_for_payment_method'] = 'Выбран способ оплаты <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Включить модуль предоплаты, когда выбран один из отмеченных ниже способов оплаты. Должен быть отмечен хотя бы один способ оплаты.">[?]</span>';
$_['entry_for_total_items_price'] = 'Суммарная стоимость товаров <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Если Вы хотите включать предоплату только для определенной суммы заказа, укажите диапазон. Эти поля не являются обязательными.">[?]</span>';
$_['entry_count_as'] = 'Вычислять сумму предоплаты как <span style="color:#ff0000; text-decoration:underline; cursor:pointer;" title="Укажите, как должна вычисляться сумма предоплаты. ">[?]</span>:';
$_['entry_prepayment_percent_part'] = 'Часть стоимости:';
$_['entry_prepayment_percent_part_shipping'] = 'от доставки';
$_['entry_prepayment_percent_part_total_price'] = 'от суммарной стоимости товаров';
$_['entry_prepayment_comment'] = 'Текст для показа покупателю';

$_['entry_from'] = 'от';
$_['entry_to'] = 'до';

$_['entry_prepayment_amount_fixed_selection'] = 'Фиксированная сумма предоплаты';
$_['entry_prepayment_by_shipping'] = 'суммы доставки';
$_['entry_prepayment_by_total_price'] = 'суммарной стоимости товаров';
$_['entry_confirm_remove_filter'] = 'Вы действительно хотите удалить фильтр предоплаты?';

$_['entry_payment_method_validation_tip'] = 'Не отмечен ни один способ оплаты';

$_['button_add_filter'] = 'Добавить условие включения предоплаты';

// Error
$_['error_permission'] = 'У Вас нет прав для управления этим модулем!';
?>