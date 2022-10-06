<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    
    <div class="warning"><b>ВНИМАНИЕ! РЕДАКТИРОВАНИЕ НАСТРОЕК ЗАПРЕЩЕНО БЕЗ ВЕДОМА ПРОГРАММИСТА. ОДНО НЕВЕРНОЕ ДЕЙСТВИЕ И ВЕСЬ ЧЕКАУТ БУДЕТ СЛОМАН!</b></div>
    
    <div class="box">
        <div class="heading order_head">
            <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

                <div id="tabs" class="vtabs">
                    <a href="#tab-general"><?php echo $tab_general; ?></a>
                    <?php $module_row = 1; ?>
                    <?php foreach ($modules as $module) { ?>
                        <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo isset($module['title'][$this->config->get('config_language_id')]) ? $module['title'][$this->config->get('config_language_id')] : $tab_module . ' ' . $module_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" />
                           <br /><i style="font-size:10px;">Код: dostavkaplus.sh<?php echo $module_row; ?></i>
                       </a>
                       <?php $module_row++; ?>
                   <?php } ?>
                   <span id="module-add"><?php echo $button_add_module; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span>
               </div>


               <div id="tab-general" class="vtabs-content">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_name; ?></td>
                        <td><input type="text" name="<?php echo $name; ?>_name" value="<?php echo ${$name.'_name'}; ?>" size="100" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="<?php echo $name; ?>_status">
                            <?php if (${$name.'_status'}) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_show_error_text; ?></td>
                    <td>
                        <input id="<?php echo $name; ?>_show_error_text" class="checkbox" type="checkbox" name="<?php echo $name; ?>_show_error_text" value="1" <?php if (${$name.'_show_error_text'}) { ?>checked="checked"<?php } ?> />
                        <label for="<?php echo $name; ?>_show_error_text"></label>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_sort_order; ?></td>
                    <td><input type="text" name="<?php echo $name; ?>_sort_order" value="<?php echo ${$name.'_sort_order'}; ?>" size="3" /></td>
                </tr>
            </table>
        </div>

        <?php $module_row = 1; ?>
        <?php  foreach ($modules as $module) { ?>
            <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
                <table class="form">
                    <?php foreach ($languages as $language) { ?>
                        <?php if ($language['status'] == 1) { ?>
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                                <td><input size="100" type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['title'][$language['language_id']]) ? $module['title'][$language['language_id']] : ''; ?>" />
                                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br/>
                                    <?php if (isset($error_title[$module_row][$language['language_id']])) { ?>
                                        <span class="error"><?php echo $error_title[$module_row][$language['language_id']]; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td><?php echo $entry_info; ?></td>
                                <td><textarea id="<?php echo $name; ?>_module_<?php echo $module_row; ?>_info_<?php echo $language['language_id']; ?>" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][info][<?php echo $language['language_id']; ?>]" cols="80" rows="7"><?php echo isset($module['info'][$language['language_id']]) ? $module['info'][$language['language_id']] : ''; ?></textarea>
                                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
                                </td>
                            </tr>

                            <tr>
                                <td>Текст, который будет, если нет цены:<br /><span class="help">К примеру, "Согласно тарифу"</span></td>
                                <td><input size="100" type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][txtprice][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['txtprice'][$language['language_id']]) ? $module['txtprice'][$language['language_id']] : ''; ?>" />
                                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
                                </td>
                            </tr>                           
                        <?php } ?>
                    <?php  }?>

                    <tr>
                        <td><?php echo $entry_price; ?></td>
                        <td><input type="nubmer" step="1" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][price]" value="<?php if (isset($module['price'])) echo $module['price']; ?>" size="5" /></td>
                    </tr>

                    <tr>
                        <td>Стоимость для отображения в карте товара, от</td>
                        <td><input type="nubmer" step="1" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][price_from]" value="<?php if (isset($module['price_from'])) echo $module['price_from']; ?>" size="5" /></td>
                    </tr>

                    <tr>
                        <td>Валюта стоимости</td>
                        <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][curr]" value="<?php if (isset($module['curr'])) echo $module['curr']; ?>" size="5" />&nbsp;&nbsp;	
                         <? foreach ($currencies as $c) { ?>
                            <? echo $c['code']; ?>&nbsp;							
                        <? } ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo $entry_weight_class; ?></td>
                    <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][weight_class_id]">
                        <?php foreach ($weight_classes as $weight_class) { ?>
                            <?php if ($weight_class['weight_class_id'] == $module['weight_class_id']) { ?>
                                <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select></td>
                </tr>

                <tr>
                    <td><?php echo $entry_rate; ?></td>
                    <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][rate]" cols="60" rows="5"><?php if (isset($module['rate'])) echo $module['rate']; ?></textarea></td>
                </tr>

                <tr>
                    <td>Доставка, зависящая от цены<br /><span class="help">Например, 499:50,600:100</span></td>
                    <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][sumrate]" cols="60" rows="5"><?php if (isset($module['sumrate'])) echo $module['sumrate']; ?></textarea></td>
                </tr>

                <tr>
                    <td>Только полная предоплата!</td>
                    <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][only_prepay]">
                        <?php if ($module['only_prepay']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select></td>
                </tr>

                <tr>
                    <td>Бесплатно только при полной предоплате, иначе по тарифу</td>
                    <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][cod]">
                        <?php if ($module['cod']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select></td>
                </tr>

                <tr>
                    <td>Ставить галочку, если активен</td>
                    <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][checkactive]">
                        <?php if ($module['checkactive']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select></td>
                </tr>

                <tr>
                    <td>Применять при неизвестной стране</td>
                    <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][applytoallcountries]">
                        <?php if ($module['applytoallcountries']) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select></td>
                </tr>

                <tr>
                    <td><?php echo $entry_cost; ?></td>
                    <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][cost]" value="<?php if (isset($module['cost'])) echo $module['cost']; ?>" size="5" /></td>
                </tr>

                <tr>
                    <td><?php echo $entry_image; ?></td>
                    <td><div class="image"><img src="<?php if (isset($module['thumb'])) echo $module['thumb']; else echo $no_image; ?>" alt="" id="thumb<?php echo $module_row; ?>" /><br />
                        <input type="hidden" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][image]" value="<?php if (isset($module['image'])) echo $module['image']; ?>" id="image<?php echo $module_row; ?>" />
                        <a onclick="image_upload('image<?php echo $module_row; ?>', 'thumb<?php echo $module_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $module_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $module_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_store; ?></td>
                        <td>
                            <div class="scrollbox">
                                <?php $class = 'even'; ?>
                                <?php foreach ($stores as $store) { ?>
                                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                    <div class="<?php echo $class; ?>">
                                        <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]" value="<?php echo $store['store_id']; ?>" <?php if (isset($module['store']) and in_array($store['store_id'], $module['store'])) { ?>checked="checked"<?php } ?> />
                                        <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                            <a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>

                            <?php if (isset($error_store[$module_row])) { ?>
                                <span class="error"><?php echo $error_store[$module_row]; ?></span>
                            <?php } ?>

                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_city_rate; ?></td>
                        <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][city_rate]" cols="60" rows="5"><?php if (isset($module['city_rate'])) echo $module['city_rate']; ?></textarea></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_city_rate2; ?></td>
                        <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][city_rate2]" cols="60" rows="5"><?php if (isset($module['city_rate2'])) echo $module['city_rate2']; ?></textarea></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_min_total; ?></td>
                        <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][min_total]" value="<?php if (isset($module['min_total'])) echo $module['min_total']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_max_total; ?></td>
                        <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][max_total]" value="<?php if (isset($module['max_total'])) echo $module['max_total']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_min_weight; ?></td>
                        <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][min_weight]" value="<?php if (isset($module['min_weight'])) echo $module['min_weight']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_max_weight; ?></td>
                        <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][max_weight]" value="<?php if (isset($module['max_weight'])) echo $module['max_weight']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_geo_zone; ?></td>
                        <td>
                            <div class="scrollbox">
                                <?php foreach ($geo_zones as $geo_zone) {  ?>
                                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                    <div class="<?php echo $class; ?>">
                                        <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php if (isset($module['geo_zone']) and in_array($geo_zone['geo_zone_id'], $module['geo_zone'])) { ?>checked="checked"<?php } ?> />
                                        <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                            <a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][status]">
                            <?php if ($module['status']) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                    </tr>
                </table>

            </div>
            <?php $module_row++; ?>
        <?php } ?>

    </form>
    <div class="clr"></div>
</div>
</div>
</div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php $module_row = 1;?>
<?php foreach ($modules as $module) { ?>
    <?php foreach ($languages as $language) {
        if ($language['status'] == 1 && false) {?>
            CKEDITOR.replace('<?php echo $name; ?>_module_<?php echo $module_row; ?>_info_<?php echo $language['language_id']; ?>', {
                filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
            });
        <?php } }
        $module_row++;
    }?>
    //--></script>

    <script type="text/javascript"><!--
    <?php $module_row = count($modules) + 1; ?>

    var module_row = <?php echo $module_row; ?>;

    function addModule() {
        html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';

        html += '<table class="form">';

        html += '<?php foreach ($languages as $language) { if ($language["status"] == 1) { ?>';
        html += '   <tr>';
        html += '       <td><span class="required">*</span> <?php echo $entry_title; ?></td>';
        html += '       <td><input size="100" type="text" name="<?php echo $name; ?>_module[' + module_row + '][title][<?php echo $language['language_id']; ?>]" value="" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_info; ?></td>';
        html += '       <td><textarea id="<?php echo $name; ?>_module_' + module_row + '_info_<?php echo $language['language_id']; ?>" name="<?php echo $name; ?>_module[' + module_row + '][info][<?php echo $language['language_id']; ?>]" cols="80" rows="7"></textarea>';
        html += '           <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />';
        html += '       </td>';
        html += '   </tr>';
        html += '<?php } } ?>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_price; ?></td>';
        html += '      <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][price]" value="" size="3" /></td>';
        html += '    </tr>';

        html += '<tr>';
        html += '<td><?php echo $entry_weight_class; ?></td>';
        html += '<td><select name="<?php echo $name; ?>_module[' + module_row + '][weight_class_id]">';
        html += '   <?php foreach ($weight_classes as $weight_class) { ?>';
        html += '       <option value="<?php echo $weight_class["weight_class_id"]; ?>"><?php echo $weight_class["title"]; ?></option>';
        html += '   <?php } ?>';
        html += '</select></td>';
        html += '</tr>';

        html += '    <tr>';
        html += '    <td><?php echo $entry_rate; ?></td>';
        html += '    <td><textarea name="<?php echo $name; ?>_module[' + module_row + '][rate]" cols="60" rows="5"></textarea></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '    <td><?php echo $entry_cost; ?></td>';
        html += '    <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][cost]" value="" size="3" /></td>';
        html += '    </tr>';
        html += '<tr>';
        html += '<td><?php echo $entry_image; ?></td>';
        html += '<td><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + module_row + '" /><br />';
        html += '<input type="hidden" name="<?php echo $name; ?>_module[' + module_row + '][image]" value="<?php if (isset($module['image'])) echo $module['image']; ?>" id="image<?php echo $module_row; ?>" />';
        html += '<a onclick="image_upload(\'image' + module_row + '\', \'thumb' + module_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + module_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + module_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></td>';
        html += '</tr>';
        html += '    <tr>';
        html += '       <td><?php echo $entry_store; ?></td>';
        html += '       <td>';
        html += '           <div class="scrollbox">';
        html += '           <?php $class = 'even'; ?>';
        html += '           <div class="<?php echo $class; ?>">';
        html += '               <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_0" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]" value="0" />';
        html += '               <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_0"><?php echo $text_default; ?></label>';
        html += '           </div>';
        html += '       <?php foreach ($stores as $store) { ?>';
        html += '       <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>';
        html += '       <div class="<?php echo $class; ?>">';
        html += '           <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]" value="<?php echo $store['store_id']; ?>" />';
        html += '           <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>';
        html += '       </div>';
        html += '       <?php } ?>';
        html += '       </div></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_city_rate; ?></td>';
        html += '       <td><textarea name="<?php echo $name; ?>_module[' + module_row + '][city_rate]" cols="60" rows="5"></textarea></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_city_rate2; ?></td>';
        html += '       <td><textarea name="<?php echo $name; ?>_module[' + module_row + '][city_rate2]" cols="60" rows="5"></textarea></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_min_total; ?></td>';
        html += '       <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][min_total]" value="" /></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_max_total; ?></td>';
        html += '       <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][max_total]" value="" /></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_min_weight; ?></td>';
        html += '       <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][min_weight]" value="" /></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_max_weight; ?></td>';
        html += '       <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][max_weight]" value="" /></td>';
        html += '   </tr>';
        html += '   <tr>';
        html += '       <td><?php echo $entry_geo_zone; ?></td>';
        html += '       <td>';
        html += '           <div class="scrollbox">';
        html += '       <?php foreach ($geo_zones as $geo_zone) { ?>';
        html += '       <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>';
        html += '       <div class="<?php echo $class; ?>">';
        html += '           <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
        html += '           <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></label>';
        html += '       </div>';
        html += '       <?php } ?>';
        html += '       </div></td>';
        html += '   </tr>';

        html += '    <tr>';
        html += '      <td><?php echo $entry_status; ?></td>';
        html += '      <td><select name="<?php echo $name; ?>_module[' + module_row + '][status]">';
        html += '        <option value="1"><?php echo $text_enabled; ?></option>';
        html += '        <option value="0"><?php echo $text_disabled; ?></option>';
        html += '      </select></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_sort_order; ?></td>';
        html += '      <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
        html += '    </tr>';
        html += '</table>';
        html += '</div>';


        $('#form').append(html);

    <?php /* foreach ($languages as $language) { if ($language["status"] == 1) {  ?>
            CKEDITOR.replace('<?php echo $name; ?>_module_' + module_row + '_info_<?php echo $language['language_id']; ?>', {
                filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
                filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
               filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
               filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
            });
        <?php } } */ ?>

        $('#language-' + module_row + ' a').tabs();

        $('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $tab_module; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');

        $('.vtabs a').tabs();

        $('#module-' + module_row).trigger('click');

        module_row++;
    }
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
                        success: function(text) {
                            $('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
                        }
                    });
                }
            },
            bgiframe: false,
            width: 1024,
            height: 550,
            resizable: false,
            modal: false,
            dialogClass: 'dlg'
        });
    };
    //--></script>
    <script type="text/javascript"><!--
    $('.vtabs a').tabs();
    //--></script>
    <script type="text/javascript"><!--
    <?php $module_row = 1; ?>
    <?php foreach ($modules as $module) { ?>
        $('#language-<?php echo $module_row; ?> a').tabs();
        <?php $module_row++; ?>
    <?php } ?>
    //--></script>


    <?php echo $footer; ?>