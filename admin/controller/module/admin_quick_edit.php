<?php
define('EXTENSION_NAME', 'Admin Quick Edit PRO');
define('EXTENSION_VERSION', '3.5.4');
define('EXTENSION_TYPE', 'vQmod');
define('EXTENSION_COMPATIBILITY', 'OpenCart 1.5.6.x');
define('EXTENSION_URL', 'http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3805');
define('EXTENSION_SUPPORT', 'support@opencart.ee');
define('EXTENSION_SUPPORT_FORUM', 'http://forum.opencart.com/viewtopic.php?f=123&t=45057');

function column_sort($a, $b) {
    if ($a['index'] == $b['index']) {
        return 0;
    }
    return ($a['index'] < $b['index']) ? -1 : 1;
}

class ControllerModuleAdminQuickEdit extends Controller {
    private $error = array();
    private $defaults = array(
        'admin_quick_edit_status'                   => false,
        'aqe_installed'                             => 1,
        'aqe_match_anywhere'                        => false,
        'aqe_alternate_row_colour'                  => false,
        'aqe_row_hover_highlighting'                => false,
        'aqe_highlight_status'                      => false,
        'aqe_interval_filter'                       => false,
        'aqe_quick_edit_on'                         => 'click',
        'aqe_list_view_image_width'                 => 40,
        'aqe_list_view_image_height'                => 40,
        'aqe_single_language_editing'               => false,
        'aqe_catalog_categories_status'             => false,
        'aqe_catalog_products_status'               => false,
        'aqe_catalog_filters_status'                => false,
        'aqe_catalog_profiles_status'               => false,
        'aqe_catalog_products_filter_sub_category'  => false,
        'aqe_catalog_attributes_status'             => false,
        'aqe_catalog_attribute_groups_status'       => false,
        'aqe_catalog_options_status'                => false,
        'aqe_catalog_manufacturers_status'          => false,
        'aqe_catalog_downloads_status'              => false,
        'aqe_catalog_reviews_status'                => false,
        'aqe_catalog_information_status'            => false,
        'aqe_sales_voucher_themes_status'           => false,
        'aqe_sales_vouchers_status'                 => false,
        'aqe_sales_orders_status'                   => false,
        'aqe_sales_returns_status'                  => false,
        'aqe_sales_orders_notify_customer'          => false,
        'aqe_sales_returns_notify_customer'         => false,
        'aqe_sales_affiliates_status'               => false,
        'aqe_sales_customers_status'                => false,
        'aqe_sales_coupons_status'                  => false
        );

    private $column_defaults = array(
        'aqe_catalog_products'      => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'sortable' => 1, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'p.product_id'     , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'image'             => array('display' => 1, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' =>  1, 'align' => 'center', 'qe_type' =>   'image_quick_edit', 'sort' => ''                 , 'filter' => array('show' => 0, 'type' => 0, 'autocomplete' => false)),
        'category'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>     'cat_quick_edit', 'sort' => ''                 , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'manufacturer'      => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' =>  3, 'align' =>   'left', 'qe_type' => 'manufac_quick_edit', 'sort' => 'm.name'           , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'name'              => array('display' => 1, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' =>  4, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'pd.name'          , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>  'name', 'value' => 'product_id')))),
        'tag'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' =>  5, 'align' =>   'left', 'qe_type' =>     'tag_quick_edit', 'sort' => ''                 , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'model'             => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'sortable' => 1, 'index' =>  6, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.model'          , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' => 'model', 'value' => 'product_id')))),
        'price'             => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'sortable' => 1, 'index' =>  7, 'align' =>  'right', 'qe_type' =>   'price_quick_edit', 'sort' => 'p.price'          , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'quantity'          => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'sortable' => 1, 'index' =>  8, 'align' =>  'right', 'qe_type' =>     'qty_quick_edit', 'sort' => 'p.quantity'       , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'sku'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' =>  9, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.sku'            , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>   'sku', 'value' => 'product_id')))),
        'asin'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' =>  9, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.asin'            , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>   'sku', 'value' => 'product_id')))),
        'upc'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 10, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.upc'            , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>   'upc', 'value' => 'product_id')))),
        'ean'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 11, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.ean'            , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>   'ean', 'value' => 'product_id')))),
        'jan'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 12, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.jan'            , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>   'jan', 'value' => 'product_id')))),
        'isbn'              => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 13, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.isbn'           , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>  'isbn', 'value' => 'product_id')))),
        'mpn'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 14, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.mpn'            , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>   'mpn', 'value' => 'product_id')))),
        'location'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 15, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.location'       , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => 'location'))),
        'seo'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 16, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'seo'              , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => array('return' => array('label' =>   'seo', 'value' => 'product_id')))),
        'tax_class'         => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 17, 'align' =>   'left', 'qe_type' => 'tax_cls_quick_edit', 'sort' => 'tc.title'         , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'minimum'           => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 18, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'p.minimum'        , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'subtract'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 19, 'align' =>   'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'p.subtract'       , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'stock_status'      => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 20, 'align' =>   'left', 'qe_type' =>   'stock_quick_edit', 'sort' => 'ss.name'          , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'requires_shipping' => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 21, 'align' =>   'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'p.shipping'       , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'date_available'    => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 22, 'align' =>   'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'p.date_available' , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'date_added'    => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 22, 'align' =>   'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'p.date_available' , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'date_modified'     => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'sortable' => 1, 'index' => 23, 'align' =>   'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'p.date_modified'  , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'length'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 24, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'p.length'         , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'width'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 25, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'p.width'          , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'height'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 26, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'p.height'         , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'weight'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 27, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'p.weight'         , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'length_class'      => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 28, 'align' =>   'left', 'qe_type' =>  'length_quick_edit', 'sort' => 'lc.title'         , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'weight_class'      => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 29, 'align' =>   'left', 'qe_type' =>  'weight_quick_edit', 'sort' => 'wc.title'         , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'points'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 30, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'p.points'         , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'filter'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 31, 'align' =>   'left', 'qe_type' =>  'filter_quick_edit', 'sort' => ''                 , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'download'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 32, 'align' =>   'left', 'qe_type' =>      'dl_quick_edit', 'sort' => ''                 , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'store'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'sortable' => 1, 'index' => 33, 'align' =>   'left', 'qe_type' =>   'store_quick_edit', 'sort' => ''                 , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'sortable' => 1, 'index' => 34, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'p.sort_order'     , 'filter' => array('show' => 1, 'type' => 0, 'autocomplete' => false)),
        'status'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'sortable' => 1, 'index' => 35, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' => 'p.status'         , 'filter' => array('show' => 1, 'type' => 1, 'autocomplete' => false)),
        'view_in_store'     => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'sortable' => 1, 'index' => 36, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => ''                 , 'filter' => array('show' => 0, 'type' => 0, 'autocomplete' => false)),
        'action'            => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'sortable' => 1, 'index' => 37, 'align' =>  'right', 'qe_type' =>                   '', 'sort' => ''                 , 'filter' => array('show' => 0, 'type' => 0, 'autocomplete' => false)),
        ),
        'aqe_catalog_products_actions' => array(
            'attributes'        => array('display' => 0, 'index' =>  0, 'hide' =>  true, 'short' => 'attr',  'qe_type' =>    'attr_quick_edit', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => '')),
            'discounts'         => array('display' => 0, 'index' =>  1, 'hide' =>  true, 'short' => 'dscnt', 'qe_type' =>   'dscnt_quick_edit', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => '')),
            'images'            => array('display' => 0, 'index' =>  2, 'hide' =>  true, 'short' => 'img',   'qe_type' =>  'images_quick_edit', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => '')),
            'filters'           => array('display' => 0, 'index' =>  3, 'hide' =>  true, 'short' => 'fltr',  'qe_type' => 'filters_quick_edit', 'ref' =>  'filter', 'btn' => array('class' => '', 'icon' => '')),
            'options'           => array('display' => 0, 'index' =>  4, 'hide' =>  true, 'short' => 'opts',  'qe_type' =>  'option_quick_edit', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => '')),
            'profiles'          => array('display' => 0, 'index' =>  5, 'hide' =>  true, 'short' => 'prof',  'qe_type' => 'profile_quick_edit', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => '')),
            'related'           => array('display' => 0, 'index' =>  6, 'hide' =>  true, 'short' => 'rel',   'qe_type' => 'related_quick_edit', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => '')),
            'specials'          => array('display' => 0, 'index' =>  7, 'hide' =>  true, 'short' => 'spcl',  'qe_type' => 'special_quick_edit', 'ref' =>   'price', 'btn' => array('class' => '', 'icon' => '')),
            'descriptions'      => array('display' => 0, 'index' =>  8, 'hide' =>  true, 'short' => 'desc',  'qe_type' =>   'descr_quick_edit', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => '')),
            'view'              => array('display' => 1, 'index' =>  9, 'hide' => false, 'short' => 'vw',    'qe_type' =>                   '', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => 'icon-eye-open')),
            'edit'              => array('display' => 1, 'index' => 10, 'hide' => false, 'short' => 'ed',    'qe_type' =>                   '', 'ref' =>        '', 'btn' => array('class' => '', 'icon' => 'icon-pencil')),
        ),
        'aqe_catalog_categories' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => ''),
        'image'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  1, 'align' => 'center', 'qe_type' =>   'image_quick_edit', 'sort' => ''),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' => ''),
        'column'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => ''),
        'top'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'align' =>   'left', 'qe_type' =>     'top_quick_edit', 'sort' => ''),
        'seo'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  5, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => ''),
        'filter'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  6, 'align' =>   'left', 'qe_type' =>  'filter_quick_edit', 'sort' => ''),
        'store'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  7, 'align' =>   'left', 'qe_type' =>   'store_quick_edit', 'sort' => ''),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  8, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => ''),
        'status'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  9, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' => ''),
        'view_in_store'     => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' => 10, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => ''),
        ),
        'aqe_catalog_filters' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' =>  'fg.filter_group_id', 'ref' => 'id'),
        'group_name'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' =>            'fgd.name', 'ref' => 'group'),
        'filter'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' => 'filters_quick_edit', 'sort' =>                    '', 'ref' => 'filters'),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  3, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' =>                    '', 'ref' => 'sort_order')
        ),
        'aqe_catalog_profiles' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' =>        'p.profile_id'),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' =>             'pd.name'),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  2, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' =>        'p.sort_order'),
        'status'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' =>            'p.status'),
        'price'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'align' =>  'right', 'qe_type' =>  'number_quick_edit', 'sort' =>             'p.price'),
        'frequency'         => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  5, 'align' =>   'left', 'qe_type' =>    'freq_quick_edit', 'sort' =>         'p.frequency'),
        'duration'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  6, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' =>          'p.duration'),
        'cycle'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  7, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' =>             'p.cycle'),
        'trial_status'      => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  8, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' =>      'p.trial_status'),
        'trial_price'       => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  9, 'align' =>  'right', 'qe_type' =>  'number_quick_edit', 'sort' =>       'p.trial_price'),
        'trial_frequency'   => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 10, 'align' =>   'left', 'qe_type' =>    'freq_quick_edit', 'sort' =>   'p.trial_frequency'),
        'trial_duration'    => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 11, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' =>    'p.trial_duration'),
        'trial_cycle'       => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 12, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' =>       'p.trial_cycle')
        ),
        'aqe_catalog_attributes' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'a.attribute_id'),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'ad.name'),
        'attribute_group'   => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>   'group_quick_edit', 'sort' => 'attribute_group'),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  3, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'a.sort_order'),
        ),
        'aqe_catalog_attribute_groups' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'ag.attribute_group_id'),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'agd.name'),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  2, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'ag.sort_order'),
        ),
        'aqe_catalog_options' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'o.option_id'),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'od.name'),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  2, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'o.sort_order'),
        ),
        'aqe_catalog_manufacturers' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'manufacturer_id'),
        'image'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  1, 'align' => 'center', 'qe_type' =>   'image_quick_edit', 'sort' => ''),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'name'),
        'seo'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'seo'),
        'store'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'align' =>   'left', 'qe_type' =>   'store_quick_edit', 'sort' => ''),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  5, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'sort_order'),
        'view_in_store'     => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  6, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => ''),
        ),
        'aqe_catalog_downloads' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'd.download_id'),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'dd.name'),
        'filename'          => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  2, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'd.filename'),
        'mask'              => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'd.mask'),
        'remaining'         => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  4, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'd.remaining'),
        ),
        'aqe_catalog_reviews' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'r.review_id'),
        'product'           => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  0, 'align' =>   'left', 'qe_type' => 'product_quick_edit', 'sort' => 'pd.name'),
        'author'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'r.author'),
        'text'              => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>    'text_quick_edit', 'sort' => ''),
        'rating'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  3, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'r.rating'),
        'status'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  4, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' => 'r.status'),
        'date_added'        => array('display' => 1, 'qe_status' => 0, 'editable' => 1, 'index' =>  5, 'align' =>   'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'r.date_added'),
        ),
        'aqe_catalog_information' => array(
        'id'                => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => 'i.information_id'),
        'title'             => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>   'title_quick_edit', 'sort' => 'id.title'),
        'seo'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'seo'),
        'bottom'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'align' =>   'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'i.bottom'),
        'store'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'align' =>   'left', 'qe_type' =>   'store_quick_edit', 'sort' => ''),
        'sort_order'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  5, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'i.sort_order'),
        'status'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  6, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' => 'i.status'),
        'view_in_store'     => array('display' => 0, 'qe_status' => 0, 'editable' => 0, 'index' =>  7, 'align' =>   'left', 'qe_type' =>                   '', 'sort' => ''),
        ),
        'aqe_sales_returns' => array(
        'return_id'         => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' =>  0, 'filter' => array('show' => 0, 'type' => 0), 'align' => 'right', 'qe_type' =>          'quick_edit', 'sort' => 'r.return_id'),
        'order_id'          => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'filter' => array('show' => 0, 'type' => 0), 'align' => 'right', 'qe_type' =>          'quick_edit', 'sort' => 'r.order_id'),
        'customer_id'       => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' => 'customer_quick_edit', 'sort' => 'customer_name'),
        'customer'          => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  3, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>     'name_quick_edit', 'sort' => 'customer'),
        'email'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>          'quick_edit', 'sort' => 'r.email'),
        'telephone'         => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  5, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>          'quick_edit', 'sort' => 'r.telephone'),
        'product_id'        => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  6, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>  'product_quick_edit', 'sort' => 'product_name'),
        'product'           => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  7, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>          'quick_edit', 'sort' => 'r.product'),
        'model'             => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  8, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>          'quick_edit', 'sort' => 'r.model'),
        'quantity'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  9, 'filter' => array('show' => 1, 'type' => 0), 'align' => 'right', 'qe_type' =>          'quick_edit', 'sort' => 'r.quantity'),
        'return_reason'     => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 10, 'filter' => array('show' => 1, 'type' => 1), 'align' =>  'left', 'qe_type' =>   'reason_quick_edit', 'sort' => 'reason'),
        'opened'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 11, 'filter' => array('show' => 1, 'type' => 1), 'align' =>  'left', 'qe_type' =>   'yes_no_quick_edit', 'sort' => 'r.opened'),
        'comment'           => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 12, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>     'text_quick_edit', 'sort' => 'r.comment'),
        'return_action'     => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 13, 'filter' => array('show' => 1, 'type' => 1), 'align' =>  'left', 'qe_type' =>   'action_quick_edit', 'sort' => 'action'),
        'return_status'     => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' => 14, 'filter' => array('show' => 1, 'type' => 1), 'align' =>  'left', 'qe_type' =>   'status_quick_edit', 'sort' => 'status'),
        'date_ordered'      => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 15, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>     'date_quick_edit', 'sort' => 'r.date_ordered'),
        'date_added'        => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' => 16, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>     'date_quick_edit', 'sort' => 'r.date_added'),
        'date_modified'     => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' => 17, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>     'date_quick_edit', 'sort' => 'r.date_modified'),
        ),
        'aqe_sales_coupons' => array(
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  0, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'name'),
        'code'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'code'),
        'type'              => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>    'type_quick_edit', 'sort' => 'type'),
        'total'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'total'),
        'logged'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'align' =>   'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'logged'),
        'shipping'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  5, 'align' =>   'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'shipping'),
        'discount'          => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  6, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'discount'),
        'date_start'        => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  7, 'align' =>   'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'date_start'),
        'date_end'          => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  8, 'align' =>   'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'date_end'),
        'uses_total'        => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  9, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'uses_total'),
        'uses_customer'     => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 10, 'align' =>  'right', 'qe_type' =>         'quick_edit', 'sort' => 'uses_customer'),
        'status'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' => 11, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' => 'status'),
        ),
        'aqe_sales_customers' => array(
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  0, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'name'),
        'email'             => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'c.email'),
        'telephone'         => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'c.telephone'),
        'fax'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'c.fax'),
        'newsletter'        => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'filter' => array('show' => 1, 'type' => 1), 'align' =>  'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'c.newsletter'),
        'customer_group'    => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  5, 'filter' => array('show' => 1, 'type' => 1), 'align' =>  'left', 'qe_type' =>   'group_quick_edit', 'sort' => 'customer_group'),
        'status'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  6, 'filter' => array('show' => 0, 'type' => 1), 'align' =>  'left', 'qe_type' =>  'status_quick_edit', 'sort' => 'c.status'),
        'approved'          => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  7, 'filter' => array('show' => 0, 'type' => 1), 'align' =>  'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'c.approved'),
        'ip'                => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' =>  8, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'c.ip'),
        'date_added'        => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' =>  9, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'c.date_added'),
        ),
        'aqe_sales_orders' => array(
        'status'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  0, 'align' => 'left', 'qe_type' =>  'status_quick_edit', 'sort' => ''),
        ),
        'aqe_sales_affiliates' => array(
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  0, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'name'),
        'email'             => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.email'),
        'telephone'         => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.telephone'),
        'fax'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  3, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.fax'),
        'company'           => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.company'),
        'address_1'         => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  5, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.address_1'),
        'address_2'         => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  6, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.address_2'),
        'city'              => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  7, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.city'),
        'postcode'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  8, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.postcode'),
        'country'           => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  9, 'filter' => array('show' => 1, 'type' => 1), 'align' =>  'left', 'qe_type' => 'country_quick_edit', 'sort' => 'country'),
        'region'            => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 10, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>  'region_quick_edit', 'sort' => 'region'),
        'tracking_code'     => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 11, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.code'),
        'commission'        => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 12, 'filter' => array('show' => 1, 'type' => 0), 'align' => 'right', 'qe_type' =>  'number_quick_edit', 'sort' => 'a.commission'),
        'tax'               => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' => 13, 'filter' => array('show' => 1, 'type' => 0), 'align' => 'right', 'qe_type' =>         'quick_edit', 'sort' => 'a.tax'),
        'balance'           => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' => 14, 'filter' => array('show' => 1, 'type' => 0), 'align' => 'right', 'qe_type' =>  'number_quick_edit', 'sort' => 'balance'),
        'approved'          => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' => 15, 'filter' => array('show' => 0, 'type' => 1), 'align' =>  'left', 'qe_type' =>  'yes_no_quick_edit', 'sort' => 'a.approved'),
        'date_added'        => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' => 16, 'filter' => array('show' => 0, 'type' => 0), 'align' =>  'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'a.date_added'),
        'ip'                => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' => 17, 'filter' => array('show' => 1, 'type' => 0), 'align' =>  'left', 'qe_type' =>         'quick_edit', 'sort' => 'a.ip'),
        'status'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' => 18, 'filter' => array('show' => 0, 'type' => 1), 'align' =>  'left', 'qe_type' =>  'status_quick_edit', 'sort' => 'a.status'),
        ),
        'aqe_sales_vouchers' => array(
        'code'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  0, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'v.code'),
        'from_name'         => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'v.from_name'),
        'from_email'        => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  2, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'v.from_email'),
        'to_name'           => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  3, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'v.to_name'),
        'to_email'          => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  4, 'align' =>   'left', 'qe_type' =>         'quick_edit', 'sort' => 'v.to_email'),
        'amount'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  5, 'align' =>  'right', 'qe_type' =>  'amount_quick_edit', 'sort' => 'v.amount'),
        'theme'             => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  6, 'align' =>   'left', 'qe_type' =>   'theme_quick_edit', 'sort' => 'theme'),
        'message'           => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  7, 'align' =>   'left', 'qe_type' =>    'text_quick_edit', 'sort' => 'v.message'),
        'status'            => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  8, 'align' =>   'left', 'qe_type' =>  'status_quick_edit', 'sort' => 'v.status'),
        'date_added'        => array('display' => 1, 'qe_status' => 0, 'editable' => 0, 'index' =>  9, 'align' =>   'left', 'qe_type' =>    'date_quick_edit', 'sort' => 'v.date_added'),
        ),
        'aqe_sales_voucher_themes' => array(
        'image'             => array('display' => 0, 'qe_status' => 0, 'editable' => 1, 'index' =>  0, 'align' => 'center', 'qe_type' =>   'image_quick_edit', 'sort' => ''),
        'name'              => array('display' => 1, 'qe_status' => 1, 'editable' => 1, 'index' =>  1, 'align' =>   'left', 'qe_type' =>    'name_quick_edit', 'sort' => 'name'),
        ),
        );

    public function index() {
        $this->document->addScript('view/javascript/jquery.jeditable.js');
        $this->document->addScript('view/javascript/admin.quick.edit.pro.js');

        $this->document->addStyle('view/stylesheet/aqe_style.css');

        $this->data = array_merge($this->data, $this->language->load('module/admin_quick_edit'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            foreach($this->column_defaults as $page => $value) {
                $conf = $this->config->get($page);
                if ($conf === null) {
                    $conf = $value;
                }
                $new_conf = array();
                foreach($value as $column => $value) {
                    $new_conf[$column] = array();
                    if (isset($this->request->post['display'][$page][$column])) {
                        $new_conf[$column]['display'] = 1;
                    } else {
                        $new_conf[$column]['display'] = 0;
                    }
                    if(isset($this->column_defaults[$page][$column]['qe_status'])) {
                        if (isset($this->request->post['quick_edit'][$page][$column])) {
                            $new_conf[$column]['qe_status'] = 1;
                        } else {
                            $new_conf[$column]['qe_status'] = 0;
                        }
                    }
                    $new_conf[$column]['index'] = isset($conf[$column]['index']) ? $conf[$column]['index'] : $this->column_defaults[$page][$column]['index'];
                    $new_conf[$column] = $new_conf[$column] + $this->column_defaults[$page][$column];
                }
                $this->request->post[$page] = $new_conf;
            }

            unset($this->request->post['quick_edit']);
            unset($this->request->post['display']);

            // Go through all checkbox settings and explicitly set the values to 0 if they were not checked
            $checkbox_settings = array(
                'aqe_match_anywhere',
                'aqe_alternate_row_colour',
                'aqe_row_hover_highlighting',
                'aqe_highlight_status',
                'aqe_interval_filter',
                'aqe_single_language_editing',
                'aqe_catalog_products_filter_sub_category',
                'aqe_sales_orders_notify_customer',
                'aqe_sales_returns_notify_customer'
            );

            # Loop through all settings for the post/config values
            foreach ($checkbox_settings as $checkbox_setting) {
                if (!isset($this->request->post[$checkbox_setting])) {
                    $this->request->post[$checkbox_setting] = 0;
                }
            }

            $this->request->post['aqe_list_view_image_width'] = (int)$this->request->post['aqe_list_view_image_width'];
            $this->request->post['aqe_list_view_image_height'] = (int)$this->request->post['aqe_list_view_image_height'];

            $this->model_setting_setting->editSetting('admin_quick_edit', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (!isset($this->request->post['ajax'])) {
                $this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $this->data['ext_name'] = EXTENSION_NAME;
        $this->data['ext_version'] = EXTENSION_VERSION;
        $this->data['ext_type'] = EXTENSION_TYPE;
        $this->data['ext_compatibility'] = EXTENSION_COMPATIBILITY;
        $this->data['ext_url'] = EXTENSION_URL;
        $this->data['ext_support'] = EXTENSION_SUPPORT;
        $this->data['ext_support_forum'] = EXTENSION_SUPPORT_FORUM;
        $this->data['ext_subject'] = sprintf($this->language->get('text_ext_subject'), EXTENSION_NAME);

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (!class_exists('VQMod')) {
            $this->data['error_warning'] = $this->language->get('error_vqmod');
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/admin_quick_edit', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('module/admin_quick_edit', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['update_url'] = html_entity_decode($this->url->link('module/admin_quick_edit/update', 'token=' . $this->session->data['token'], 'SSL'));

        $this->load->model('setting/store');

        $multistore = $this->model_setting_store->getTotalStores();

        # Loop through all settings for the post/config values
        foreach (array_keys($this->defaults) as $setting) {
            if (isset($this->request->post[$setting])) {
                $this->data[$setting] = $this->request->post[$setting];
            } else {
                $this->data[$setting] = $this->config->get($setting);
                if ($this->data[$setting] === null) {
                    $this->data['error_warning'] = $this->language->get('error_unsaved_settings');
                    if (isset($this->defaults[$setting])) {
                        $this->data[$setting] = $this->defaults[$setting];
                    }
                }
            }
        }

        foreach($this->column_defaults as $key => $value) {
            $conf = $this->config->get($key);
            if (!is_array($conf))
                $conf = $value;
            foreach($value as $k => $v) {
                if (!isset($conf[$k])) {
                    $this->data['error_warning'] = $this->language->get('error_unsaved_settings');
                }

                $display = isset($conf[$k]['display']) ? $conf[$k]['display'] : $this->column_defaults[$key][$k]['display'];
                $index = isset($conf[$k]['index']) ? $conf[$k]['index'] : $this->column_defaults[$key][$k]['index'];
                if (isset($this->column_defaults[$key][$k]['qe_status'])) {
                    $qe_status = isset($conf[$k]['qe_status']) ? $conf[$k]['qe_status'] : $this->column_defaults[$key][$k]['qe_status'];
                }
                $conf[$k] = $this->column_defaults[$key][$k];
                $conf[$k]['name'] = $this->language->get('txt_' . $k);
                $conf[$k]['display'] = $display;
                $conf[$k]['index'] = $index;
                if (isset($this->column_defaults[$key][$k]['qe_status'])) {
                    $conf[$k]['qe_status'] = $qe_status;
                }
                if ($k == 'view_in_store' && !$multistore) {
                    unset($conf[$k]);
                }
            }
            uasort($conf, 'column_sort');
            $this->data[$key] = $conf;
        }

        $this->template = 'module/admin_quick_edit.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function install() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('admin_quick_edit', array_merge($this->defaults, $this->column_defaults));
    }

    public function uninstall() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('admin_quick_edit');
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/admin_quick_edit')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['aqe_list_view_image_width'] || !$this->request->post['aqe_list_view_image_height']) {
            $this->error['warning'] = $this->language->get('error_image');
        } elseif (!is_numeric($this->request->post['aqe_list_view_image_width']) ||
                  !is_numeric($this->request->post['aqe_list_view_image_height']) ||
                  (int)$this->request->post['aqe_list_view_image_width'] < 1 ||
                  (int)$this->request->post['aqe_list_view_image_height'] < 1)
        {
            $this->error['warning'] = $this->language->get('error_image_size');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $this->language->load('module/admin_quick_edit');

        $this->load->model('setting/setting');

        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateData($this->request->post)) {
            $page = $this->request->post['page'];
            $conf = array();
            $conf[$page] = $this->config->get($page);
            foreach($this->request->post['data'] as $idx => $value) {
                list($setting, $column) = explode('-', $value);
                if (isset($conf[$setting])) {
                    $conf[$setting][$column]['index'] = $idx;
                } else {
                    $json['error'] = $this->language->get('error_update_order');
                }

            }
            $settings = $this->model_setting_setting->getSetting('admin_quick_edit');
            foreach($conf as $k => $v)
                $settings[$k] = $v;
            $this->model_setting_setting->editSetting('admin_quick_edit', $settings);

            $json['success'] = 1;
        } else {
            $json['error'] = $this->language->get('error_update_order');
        }

        $this->response->setOutput(json_encode($json));
    }

    private function validateData($post) {
        if (empty($post['page'])) {
            return false;
        } else {
            $page = $post['page'];
        }
        if (!array_key_exists($page, $this->column_defaults)) {
            return false;
        }
        if (empty($post['data'])) {
            return false;
        }
        foreach($post['data'] as $value) {
            list($setting, $column) = explode('-', $value);
            if ($setting != $page) {
                return false;
            }
            if (!array_key_exists($column, $this->column_defaults[$page])) {
                return false;
            }
        }
        return true;
    }
}
?>
