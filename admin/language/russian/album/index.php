<?php 
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
$_['heading_title'] = 'Фотогалереи';
//Album
$_['album_types'][] = 'Все изображения товаров категории';
$_['album_types'][] = 'Изображения директорий';
$_['album_types'][] = 'Выбранные изображения';

$_['module_types'][] = 'Галереи';
$_['module_types'][] = 'Фотографии';

$_['arr_js_lib_types'][] = 'ColorBox';
$_['arr_js_lib_types'][] = 'LightBox';
$_['arr_js_lib_types'][] = 'FancyBox';

//sql layout names
$_['text_layout_galleries'] = 'Галереи';
$_['text_layout_photos'] = 'Фотографии';

//Image manager
$_['text_image_manager'] = 'Менеджер изображений';

$_['entry_album_name'] 		= 'Название галереи:';
$_['entry_album_seo_name'] 		= 'SEO URL: <span class="help">Должно быть уникальным на всю систему.</span>';
$_['entry_album_type'] 		= 'Тип галереи:';
$_['entry_cover_image'] 		= 'Обложка альбома:';
$_['entry_gallery_photos_limit'] 		= 'Лимит фотографий на странице галереи: <span class="help">0 - Без лимита (показать все)</span>';
$_['entry_gallery_show_limiter'] 		= 'Показывать переключатель лимита фотографий:';
$_['entry_thumb_size'] 		= 'Размер маленьких изображений на странице галереи (Ширина x Высота):';
$_['entry_thumb_size_mod'] 		= 'Размер маленьких изображений на странице модуля (Ширина x Высота):';
$_['entry_popup_size'] 		= 'Размер высплывающих изображений на странице галереи (Ширина x Высота):';
$_['entry_popup_size_mod'] 		= 'Размер высплывающих изображений на странице модуля (Ширина x Высота):';
$_['entry_include_additional_images'] 	= 'Показывать дополнительные изображения товаров:';
$_['entry_category_list'] 	= 'Выберите категорию:';
$_['text_show_album_text'] 		= 'Перейти в галерею';
$_['text_show_galleries_text'] 		= 'Перейти в раздел галерей';
$_['entry_directory'] 		= 'Введите путь и маску файла:<span class="help">Путь задается относительно корневой директории сайта.<br> Обрабатывается только папка image. <br> Можно перечислить несколько путей с маской, каждый путь на новой строке.</span>';
$_['entry_galery_images'] 	= 'Выберите изображения:';
$_['text_new_album'] 		= 'Новая фотогалерея';
$_['text_placeholder_description'] 		= 'Подпись';
$_['entry_title'] 				= 'HTML-тег Title:';
$_['entry_h1'] 					= 'HTML-тег H1:';
$_['entry_meta_keywords'] 		= 'Мета-тег Keywords:';
$_['entry_meta_description'] 	= 'Мета-тег Description:';
$_['entry_description'] 		= 'Описание:';
$_['entry_album_status'] 		= 'Статус:<span class="help">Даже после отключения этот альбом можно будет использовать в модулях, но он не будет отображаться на странице галерей.</span>';
$_['entry_sort_order'] 			= 'Порядок сортировки:';
$_['entry_gallery_cover_image_dimension'] 			= 'Размер изображений обложек в разделе галерей:';
$_['entry_gallery_show_counter'] 			= 'Показывать кол-во изображений на странице галерей:';
$_['entry_gallery_show_description'] 			= 'Показывать описание раздела галерей:';

$_['entry_module_category'] 		= 'Показывать в категориях: <span class="help">Если Вы хотите, чтобы модуль отображался во всех категориях - оставьте пустым.</span>';
$_['entry_module_product'] 			= 'Показывать на странице товаров: <span class="help">Если Вы хотите, чтобы модуль отображался на страницах всех товаров - оставьте пустым.</span>';

// h2
$_['text_album_page_settings'] 	= 'Настройки для отдельной страницы галереи';
$_['text_album_data']			= 'Фотографии альбома';
$_['text_js_library_settings']	= 'Настройки JavaScript библиотек';
$_['text_albums_settings']		= 'Настройки раздела галерей';
$_['text_general_settings']		= 'Общие настройки';
$_['text_modules_settings']		= 'Настройки модулей';

//Settings
$_['entry_enable_full_cache']   = 'Включить полное кэширование: <span class="help">Это понизит кол-во запросов к базе данных.</span>';
$_['entry_js_library']   		= 'JS библиотека галереи:';
$_['entry_galleries_include_seo_path'] 	= 'Включать в seo url раздел галерей:';
$_['entry_galleries_seo_name'] 		= 'SEO URL для раздела галерей: <span class="help">Должно быть уникальным на всю систему.</span>';

$_['entry_gallery_include_colorbox'] = 'Включить вставку файлов colorbox: <span class="help">Если в header.tpl уже включен colorbox, то его рекомендуется отключить.</span>';
$_['entry_gallery_include_lightbox'] = 'Включить вставку файлов lightbox: <span class="help">Если в header.tpl уже включен lightbox, то его рекомендуется отключить.</span>';
$_['entry_gallery_include_fancybox'] = 'Включить вставку файлов fancybox: <span class="help">Если в header.tpl уже включен fancybox, то его рекомендуется отключить.</span>';
$_['entry_gallery_include_jstabs'] = 'Включить вставку файла tabs.js: <span class="help">Если в header.tpl уже включен tabs.js, то его рекомендуется отключить.</span>';
$_['entry_gallery_include_lazyload'] = 'Включить вставку файла lazyload.js: <span class="help">Если в header.tpl уже включен lazyload.js, то его рекомендуется отключить.</span>';
$_['entry_category_layout']   		= 'Схема категорий: <span class="help">Необходимо для того, чтобы можно было отображать модули на странице конкретной категории.</span>';
$_['entry_product_layout']   		= 'Схема товара: <span class="help">Необходимо для того, чтобы можно было отображать модули на странице конкретного товара.</span>';

//Messages 
$_['text_success_edited'] 					= 'Галерея успешно отредактирована и сохранена.';
$_['text_success_added'] 					= 'Галерея успешно добавлена.';
$_['text_success_modules'] 					= 'Модули успешно сохранены.';
$_['text_success_deleted'] 					= 'Выбранные объекты были успешно удалены.';
$_['text_success_copied'] 					= 'Выбранные объекты были успешно скопированы.';
$_['text_success_settings'] 				= 'Настройки успешно сохранены.';
$_['text_success_updated'] 					= 'Модуль успешно обновлен до версии 1.2.';
$_['text_error_no_albums'] 					= 'Отсутствуют галереи. Для создания модуля сначала создайте галерею.';
$_['text_error_check_form'] 				= 'Заполните все поля.';

//Tabs
$_['tab_general'] 	= 'Общие';
$_['tab_data'] 		= 'Данные';

//Table
$_['column_name'] 			= 'Название галереи:';
$_['column_album_type'] 	= 'Тип галереи:';
$_['column_enabled'] 		= 'Статус:';
$_['column_sort_order'] 	= 'Порядок сортировки:';
$_['column_action'] 		= 'Действие:';
$_['text_list_empty'] 		= 'Список пуст';

//Section
$_['section_new_album'] = 'Новая фотогалерея';
$_['section_edit_album'] = 'Редактирование галереи';
$_['section_album'] = 'Фотогалерея';
$_['section_album_list'] = 'Список фотогалерей';
$_['section_modules'] = 'Модули галерей';
$_['section_settings'] = 'Настройки';

//Buttons
$_['button_text_save'] 		= 'Сохранить';
$_['button_text_insert'] 	= 'Создать';
$_['button_text_copy'] 		= 'Копировать';
$_['button_text_edit'] 		= 'Изменить';
$_['button_text_delete'] 	= 'Удалить';
$_['button_text_cancel'] 	= 'Отменить';

//Modules

// Text
$_['text_module']         = 'Модули';
$_['text_new_module_name']= 'Новый модуль';
$_['text_success']        = 'Настройки модуля обновлены!';
$_['text_content_top']    = 'Верх страницы';
$_['text_content_bottom'] = 'Низ страницы';
$_['text_column_left']    = 'Левая колонка';
$_['text_column_right']   = 'Правая колонка';

// Entry
$_['entry_module_name']         = 'Название модуля:<span class="help">Название для административной части.</span>';
$_['entry_module_header']         = 'Заголовок модуля:';
$_['entry_show_gallery_album_description']         = 'Показывать описание галереи на странице галереи:';
$_['entry_use_lazyload']         = 'Использовать LazyLoad:<span class="help">Позволяет загружать изображения только тогда, когда они небходимы.</span>';
$_['entry_show_module_header']         = 'Показывать заголовок';
$_['entry_module_type']         = 'Тип модуля:';
$_['entry_album_list']         = 'Список элементов:';
$_['entry_show_covers']         = 'Показывать обложку галереи:';
$_['entry_cover_size']         = 'Размер обложки (Ширина x Высота):';
$_['entry_show_counter']         = 'Показывать кол-во фотографий:';
$_['entry_show_album_galleries_link']         = 'Показывать кнопку перехода: <span class="help">Снизу будет показана кнопка для перехода в раздел галерей.</span>';
$_['entry_photo_album_list']         = 'Список галерей: <span class="help">Если будет выбрано несколько галерей, то они будут помещены во вкладки.</span>';
$_['entry_show_album_description']         = 'Показывать описание галереи:';
$_['entry_show_album_link']         = 'Показывать кнопку перехода:<span class="help">Снизу будет показана кнопка для перехода в раздел активной галереи.</span>';
$_['entry_show_album_text']         = 'Текст кнопки:';
$_['entry_photos_limit']         = 'Лимит фотографий в модуле: <span class="help">0 - Без лимита</span>';
$_['entry_layout']        = 'Схема:';
$_['entry_position']      = 'Расположение:';
$_['entry_status']        = 'Статус:';
$_['entry_sort_order']    = 'Порядок сортировки:';

// Error
$_['error_permission']    = 'У Вас нет прав для управления этим модулем!';

$_['text_copy'] = ' (копия)';
$_['text_yes'] = 'Да';
$_['text_no'] = 'Нет';
$_['text_browse'] = 'Обзор';
$_['text_clear'] = 'Очистить';
?>