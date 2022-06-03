<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?><?php echo $breadcrumb['separator']; ?>
        <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><?php } ?>
    </div>
    
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    
    <div class="box" id="add_specials_offer">
        <div class="heading order_head">
            <h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>
        </div>
        <div class="content">
            <table class="form" id="product-filter" style="width: 100%;">
                <tr>
                    <td colspan="3"><h2>Фильтрация для выбора основных товаров</h2></td>                  
                </tr>
                <tr>
                    <td></td>
                    <td style="width:60%;" rowspan="8">
                        <small>Всего товаров: <span id="product-total"></span></small>
                        <div class="scrollbox" id="check-all" style="height: 22px; padding:6px 0px; width: 90%; background: #00ad07; color:#FFF; cursor:pointer;">
                            <input type="checkbox" name="" value="true"/> Все товары
                        </div>
                        <div class="scrollbox" id="filtered-products" style="height: 330px; width: 90%;"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="store_id" class="filter_data" style="width: 300px;">
                            <option value="-1">Все магазины</option>
                            <?php foreach ($stores as $store) { ?>
                                <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <select name="manufacturer_id" class="filter_data" style="width: 300px;" >
                            <option value="0">Все производители</option>
                            <?php foreach ($manufacturers as $manufacturer) { ?>
                                <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="text" placeholder="Название товара" name="name" value="" class="filter_data" style="width: 300px;"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="text" placeholder="Модель" name="model" id="product_filter_model" value="" class="filter_data" style="width: 300px;"/>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <input type="text" placeholder="Код спецпредложения" name="ao_group" id="product_filter_ao_group" value="" class="filter_data" style="width: 300px;"/>
                    </td>
                </tr>
                
                 <tr>
                    <td colspan="2">
                        <input type="text" placeholder="Код скидочного товара" name="ao_product_id" id="product_filter_ao_product_id" value="" class="filter_data" style="width: 300px;"/>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <select name="category_id" class="filter_data" style="margin-bottom: 5px; width: 300px;" >
                            <option value="0">Все категории</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                            <?php } ?>
                        </select>
                        <br />
                        <input class="checkbox" type="checkbox" id="filter_sub_category" name="filter_sub_category"/>
                        <label for="filter_sub_category"><?php echo $entry_subcategory; ?></label>
                    </td>
                </tr>
            </table>
            <div class="htabs">
                <a href="#additional_offer">Добавление спецпредложения</a>
                <a href="#del"><?php echo $tab_del; ?></a>
                <a href="#offers"><?php echo $tab_additional_offer; ?></a>
                <div class="clr"></div>
            </div>
            <div class="th_style"></div>
            <div id="additional_offer">
                <form action="<?php echo $add_additional_offer; ?>" method="post" enctype="multipart/form-data" id="add_additional_offer">
                    <table id="additional-offers" class="list">
                        <thead>
                            <tr>
                                <td class="right">Покупатели</td>
                                <td class="right">Магазины</td>
                                <td class="right">Группа</td>
                                <td class="right"><?php echo $entry_priority; ?></td>
                                <td class="right"><?php echo $entry_quantity; ?></td>
                                <td class="right"><b>Товар спецпредложения</b></td>
                                <td class="right"><?php echo $entry_price; ?></td>
                                <td class="right"><?php echo $entry_percent; ?></td>
                                <td class="left"><?php echo $entry_date_start; ?></td>
                                <td class="left"><?php echo $entry_date_end; ?></td>
                                <td class="left"><?php echo $entry_image; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="left">
                                    <select name="product_additional_offer[customer_group_id]" style="width:100px;">
                                        <?php foreach ($customer_groups as $customer_group) { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                
                                <td class="left">
                                    <div class="scrollbox" style="min-height: 130px;">
                                        <?php $class = 'even'; ?>												
                                        <?php foreach ($stores as $store_ao) { ?>
                                            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                            <div class="<?php echo $class; ?>">
                                                <input id="ao_store_<?php echo $store_ao['store_id']; ?>" class="checkbox" type="checkbox" name="product_additional_offer[store_id][]" value="<?php echo $store_ao['store_id']; ?>" />
                                                <label for="ao_store_<?php echo $store_ao['store_id']; ?>"><?php echo $store_ao['name']; ?></label>
                                            </div>
                                        <?php } ?>
                                        
                                    </div>
                                    <a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Все</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять</a>
                                </td>
                                
                                <td class="right">
                                    <input type="text" placeholder="Группа спецпредложения" name="product_additional_offer[ao_group]" value="" style="width:100px;"/>
                                </td>
                                
                                <td class="right">
                                    <input type="text" name="product_additional_offer[priority]" value="" size="2"/>
                                </td>
                                
                                <td class="right">
                                    <input type="text" name="product_additional_offer[quantity]" value="" size="2"/>
                                </td>
                                <td class="right">
                                    <input type="text" value="" size="15" name="product_additional_offer[aop]" style="width:300px;"/>
                                    <input type="hidden" value="" name="product_additional_offer[ao_product_id]" class="js-product-id" value=""/>
                                </td>
                                <td class="right">
                                    <input type="text" name="product_additional_offer[price]" placeholder="Цена" class="js-input-price" value="" style="width:100px;"/>
                                </td>
                                <td class="right">
                                    <input type="text" name="product_additional_offer[percent]" placeholder="Процент" value="" style="width:100px;"/>
                                </td>
                                <td class="left">
                                    <input type="text" name="product_additional_offer[date_start]" value="" class="date" style="width:100px;"/>
                                </td>
                                <td class="left">
                                    <input type="text" name="product_additional_offer[date_end]" value="" class="date" style="width:100px;"/>
                                </td>
                                <td class="left">
                                    <div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb_ao"/>
                                        <input type="hidden" name="product_additional_offer[image]" value="" id="image_ao"/>
                                        <br/>
                                        <a onclick="image_upload('image_ao', 'thumb_ao');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_ao').attr('src', '<?php echo $no_image; ?>'); $('#image_ao').attr('value', '');"><?php echo $text_clear; ?></a>
                                    </div>
                                </td>
                            </tr>
                            <tr style="display:none;">
                                <td class="left" colspan="9">
                                    <strong>Описание</strong> <br/>
                                    <textarea id="ao-description" style="width: 99%;" rows="5" name="product_additional_offer[description]"></textarea>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="9" class="left">
                                    <div class="buttons">
                                        <a class="button submit-form" data-form='add_additional_offer'><?php echo $button_additional_offer; ?></a>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
            <div id="del">
                <form action="<?php echo $del_specials; ?>" method="post" enctype="multipart/form-data" id="del_specials">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <input class="checkbox" type="checkbox" name="del[additional_offer]" id="additional_offer-del"/><label for="additional_offer-del"><?php echo $tab_additional_offer; ?></label>
                                </td>
                            </tr>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="left">
                                    <div class="buttons">
                                        <a class="button submit-form" data-form='del_specials'><?php echo $button_del; ?></a>
                                    </div>
                                </a></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
        <div id="offers">
            <?php if($additional_offers): ?>
            <a href="javascript: void(0);" class="button js-delete">Удалить</a>
            <a href="javascript: void(0);" class="button js-update">Обновить</a>
            <form action="" class="js-form-all">
                <table class="list">
                    <thead>
                        <tr>
                            <td></td>
                            <td class="center">Цена</td>
                            <td class="center">Процент скидки</td>
                            <td class="center">Группа</td>
                            <td class="center">Основной товар</td>
                            <td class="center">Товар спецпредложения</td>
                        </tr>
                    </thead>
                    <?php foreach ($additional_offers as $ao): ?>
                    <tr>
                        <td ><input type="checkbox" name="ao_id" value="<?=$ao['id'] ?>"/></td>
                        <td class="right">
                            <input type="text" name="price[<?=$ao['id'] ?>]" value="<?=$ao['price'] ?>" style="width:60px;"/>
                        </td>
                        <td class="right">
                            <input type="text" name="percent[<?=$ao['id'] ?>]" value="<?=$ao['percent'] ?>"  style="width:60px;"/>
                        </td>
                        <td class="right">
                            <input type="text" name="ao_group[<?=$ao['id'] ?>]" value="<?=$ao['ao_group'] ?>"  style="width:150px;"/><br />
                            <span style="cursor:pointer; display:inline-block; border-bottom:1px dashed;" onclick="$('input#product_filter_ao_group').val('<?=$ao['ao_group'] ?>'); $('input#product_filter_ao_group').trigger('change');"><i class="fa fa-filter"></i> отобрать товары</span>
                        </td>
                        <td class="right">
                            <span class="status_color" style="display:inline-block; padding:10px 8px; background: #aaff56;"><?=$ao['name_1'] ?></span>
                            <br /><b><?php echo $ao['product_id1']; ?></b>, <b><?php echo $ao['sku1']; ?></b>
                        </td>
                        <td class="right">
                            <span class="status_color" style="display:inline-block; padding:10px 8px; background: #ffaa56; "><?=$ao['name_2'] ?></span>
                            <br /><b><?php echo $ao['product_id2']; ?></b>, <b><?php echo $ao['sku2']; ?></b>&nbsp;&nbsp;
                            <span style="cursor:pointer; display:inline-block; border-bottom:1px dashed;" onclick="$('input#product_filter_ao_product_id').val('<?=$ao['product_id2'] ?>'); $('input#product_filter_ao_product_id').trigger('change');"><i class="fa fa-filter"></i> отобрать товары</span>
                        </td>                       
                    </tr>
                    <?php endforeach; ?>
                </table>
                <a href="javascript: void(0);" class="button js-delete">Удалить</a>
                <a href="javascript: void(0);" class="button js-update">Обновить</a>
            </form>
            
            <script type="text/javascript">
                $('.js-delete').on('click', function () {
                    if (confirm("Вы уверены что хотите удалить записи?")) {
                        var delete_id_string = [];
                        $.each($("input[name='ao_id']:checked"), function (i, v) {
                            $(v).closest('tr').hide();
                            delete_id_string[i] = $(v).val();
                        });
                        $.post('<?php print $delete_url; ?>', {delete_id: delete_id_string}, function (data) {});
                    }
                    
                });
                
                $('.js-update').on('click', function () {
                    $.post('<?php print $update_url; ?>', $('.js-form-all').serialize(), function (data) {
                        console.log(data);
                        alert("Изменение применены!");							
                    });
                });
            </script>
            <?php else: ?>
            Комплектов нет.
            <?php endif;?>
        </div>
    </div>
</div>
</div>
<style>
    #filtered-products div:hover{background: lightgrey;} #filtered-products div
    
    {height:16px;}
</style>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
    
    
    $('input[name=\'product_additional_offer[aop]\']').autocomplete({
        delay: 0,
        source: function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item.name,
                            value: item.product_id,
                            price: item.price
                        }
                    }));                                        
                }
            });
            
        },
        select: function (event, ui) {
            $(this).val(ui.item.label);                       
            $(this).closest('td').find('.js-product-id').val(ui.item.value);
            
            $('#aopname').remove();
            $(this).after('<b id="aopname">'+ ui.item.label +'</b>');
            
            // $(this).next('input').val(ui.item.value);
            //$(this).closest('tr').find('.js-input-price').val(ui.item.price);
            
            return false;
        },
        focus: function (event, ui) {
            return false;
        }
    });
//--></script>
<script type="text/javascript"><!--
    $(function () {
        $(".submit-form").live('click', function () {
            $.ajax({
                url: $('#' + $(this).data('form')).attr('action'),
                type: 'post',
                data: $('#' + $(this).data('form') + ' input[type="text"], #' + $(this).data('form') + ' input[type="radio"], #' + $(this).data('form') + ' input[type="hidden"], #' + $(this).data('form') + ' input[type="checkbox"]:checked, #' + $(this).data('form') + ' select, #' + $(this).data('form') + ' textarea, #filtered-products input[type="checkbox"]:checked, #check-all input[type="checkbox"]:checked').add($('#product-filter .filter_data, #product-filter input[type="checkbox"]:checked')),
                dataType: 'json',
                success: function (json) {
                    productTotal = parseInt(json['total']);
                    $('.success').remove();
                    $('#add_specials_offer').before('<div class="' + json['message']['type'] + '" style="display: none;">' + json['message']['message'] + '</div>');
                    $('.' + json['message']['type']).fadeIn('slow');
                }
            });
        });
        $('.vtabs a').tabs();
    });
//--></script>
<script type="text/javascript"><!--
    var productTotal = 0;
    
    function load_products(start, limit) {
        $.ajax({
            url: 'index.php?route=catalog/addspecials/productFilter&product_list=1&start=' + start + '&limit=' + limit + '&token=<?php echo $token; ?>',
            type: 'post',
            data: $('#product-filter .filter_data, #product-filter input[type="checkbox"]:checked'),
            dataType: 'json',
            success: function (json) {
                productTotal = parseInt(json['total']);
                $('#product-total').html(json['total']);
                html = '';
                index = 0;
                products = json['products'];
                check_all = $('input', '#check-all').prop('checked') ? 'checked="checked"' : '';
                for (product in products) {
                    if (index % 2 == 0) {
                        html += '<div class="odd" style="cursor:pointer;">';
                        } else {
                        html += '<div class="even" style="cursor:pointer;">';
                    }
                    html += '<input type="checkbox" ' + check_all + ' name="product_to_change[]" value="' + json['products'][product]['product_id'] + '" />' + json['products'][product]['name'];
                    
                    html += '</div>';
                    index++;
                }
                $('#filtered-products').append(html);
            }
        });
    }
    
    $('div', '#filtered-products').live('click', function (e) {
        if (e.target.tagName != "INPUT") {
            $('input', $(this)).prop('checked', !$('input', $(this)).prop('checked'));
        }
    });
    
    $('#check-all').live('click', function (e) {
        if (e.target.tagName != "INPUT") {
            $('input', $(this)).prop('checked', !$('input', $(this)).prop('checked'));
        }
        if ($('input', $(this)).prop('checked')) {
            $('input', '#filtered-products').prop('checked', true);
            } else {
            $('input', '#filtered-products').prop('checked', false);
        }
    });
    
    start = 30;
    limit = 30;
    
    $('.filter_data, #product-filter input[type="checkbox"]').on('change', function () {
        $('#filtered-products').html('');
        start = 30;
        limit = 30;
        load_products(0, limit);
    });
    
    load_products(0, limit);
    
    $('#filtered-products').bind('scroll', function () {
        productCount = $('#filtered-products div').size();
        scrollTop = (productCount - 15) * 22;
        if ($(this).scrollTop() == scrollTop && start <= productTotal) {
            load_products(start, limit);
            start += limit;
        }
    })
//--></script>
<script type="text/javascript"><!--
    $('.htabs a').tabs();
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
//--></script>
<script type="text/javascript"><!--
    function image_upload(field, thumb) {
        $('#dialog').remove();
        
        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
        
        $('#dialog').dialog({
            title: '<?php echo $text_image_manager; ?>',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
                        dataType: 'text',
                        success: function (text) {
                            $('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
                        }
                    });
                }
            },
            bgiframe: false,
            width: 800,
            height: 400,
            resizable: false,
            modal: false
        });
    }
    ;
//--></script>

<?php echo $footer; ?>