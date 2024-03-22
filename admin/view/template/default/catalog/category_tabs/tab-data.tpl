<div id="tab-data">
    <table class="form">
        <tr>
            <td style="width:30%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $entry_parent; ?></span>
                </p>

                <input type="text" name="path" value="<?php echo $path; ?>" style="width:90%"/>
                <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>"/>
                <span class="help">Для построения дерева категорий</span>
            </td>
            <td style="width:30%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Виртуальная родительская категория</span>
                </p>

                <input type="text" name="virtual_path" value="<?php echo $virtual_path; ?>" style="width:90%"/>
                <input type="hidden" name="virtual_parent_id" value="<?php echo $virtual_parent_id; ?>"/>
                <span class="help">Только в меню, товары не учитываются</span>
            </td>
            <td style="width:40%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Родительская Amazon</span>
                </p>

                <input type="text" name="amazon_parent_category_id" value="<?php echo $amazon_parent_category_id; ?>" style="width:90%"/>
                <span class="help" style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> При смене родительской категории магазина НЕОБХОДИМО очистить это поле, иначе категория привяжется заново</span><br/>
                <?php if ($amazon_parent_category_name) { ?>
                <br/><span style="color:#00ad07"><i class="fa fa-check"></i> родительская Amazon <?php echo $amazon_parent_category_name; ?></span>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td style="width:30%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $entry_store; ?></span>
                </p>

                <div class="scrollbox" style="width:90%;">
                    <?php $class = 'even'; ?>
                    <?php foreach ($stores as $store) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                        <?php if (in_array($store['store_id'], $category_store)) { ?>
                        <input id="category_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked"/>
                        <label for="category_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
                        <?php } else { ?>
                        <input id="category_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>"/>
                        <label for="category_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </td>
            <td style="width:30%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">ЧПУ</span>
                </p>

                <?php foreach ($languages as $language) { ?>
                <div style="margin-bottom: 5px;">
                    <input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>"/>
                    <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/>
                </div>
                <?php } ?>
            </td>
            <td style="width:30%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Картинка</span>
                </p>

                <input type="text" name="image" value="<?php echo $image; ?>" id="image" style="width:90%"/>
                <div class="image" style="margin-top: 5px">
                    <img src="<?php echo $thumb; ?>" alt="" id="thumb"/><br/>
                    <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
            </td>
        </tr>
    </table>
    <table class="form">
        <tr>
            <td style="width:20%">
                <div style="margin-bottom: 5px;">
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF">Статус</span>
                    </p>

                    <select name="status">
                        <?php if ($status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div style="margin-bottom: 5px;">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF">Главное меню</span>
                </p>

                <select name="top">
                    <?php if ($top) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                </select>
                </div>
            </td>

            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF">Популярные категории (homepage)</span>
                    <span class="help">В модуле "популярные категории" выводятся самые просматриваемые категории, но можно добавить любую категорию вручную</span>
                </p>

                <select name="homepage">
                    <?php if ($homepage == 1) { ?>
                    <option value="1" selected="selected">Принудительно включить</option>
                    <option value="0">Автоматически</option>
                    <option value="-1">Исключить</option>
                    <?php } elseif ($homepage == 0) { ?>
                    <option value="1">Принудительно включить</option>
                    <option value="0" selected="selected">Автоматически</option>
                    <option value="-1">Исключить</option>
                    <?php } elseif ($homepage == -1) { ?>
                    <option value="1">Принудительно включить</option>
                    <option value="0">Автоматически</option>
                    <option value="-1" selected="selected">Исключить</option>
                    <?php } ?>
                </select>
            </td>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF">Специальная категория</span>
                    <span class="help">Специальная категория - исключена из выборок популярных и самых покупаемых в некоторых местах</span>
                </p>

                <select name="special">
                    <?php if ($special == 1) { ?>
                    <option value="1" selected="selected">Это специальная категория</option>
                    <option value="0">Это обычная категория</option>
                    <?php } elseif ($special == 0) { ?>
                    <option value="1">Это специальная категория</option>
                    <option value="0" selected="selected">Это обычная категория</option>
                    <?php } ?>
                </select>
            </td>
            <td style="width:20%">
                <p>
                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF">Популярная категория</span>
                    <span class="help">Популярная категория, этот признак не задействован в логике и оставлен для совместимости</span>
                </p>

                <select name="popular">
                    <?php if ($popular) { ?>
                    <option value="1" selected="selected">Это популярная категория</option>
                    <option value="0">Это обычная категория</option>
                    <?php } else { ?>
                    <option value="1">Это популярная категория</option>
                    <option value="0" selected="selected">Это обычная категория</option>
                    <?php } ?>
                </select>
            </td>
            <td style="width:20%">
                <div style="margin-bottom: 5px;">
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF">Колонок в меню</span>
                    </p>

                    <input type="number" step="1" name="column" value="<?php echo $column; ?>" size="5"/>
                </div>
                <div style="margin-bottom: 5px;">
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF"><?php echo $entry_sort_order; ?></span>
                    </p>

                    <input type="number" step="1" name="sort_order" value="<?php echo $sort_order; ?>" size="5"/>
                </div>
            </td>

            <td style="width:20%">
                <div style="margin-bottom: 5px;">
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF"><i class="fa fa-exclamation-triangle"></i> Выключить или включить дерево</span>
                    </p>

                    <select name="status_tree">
                        <option value="0" selected="selected">Не трогать дерево при нажатии кнопки сохранить</option>
                        <option value="1">Выключить/включить всё дерево ниже при нажатии кнопки сохранить</option>
                    </select>
                </div>
                <div style="margin-bottom: 5px;">
                    <p>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:darkred; color:#FFF"><i class="fa fa-exclamation-triangle"></i> Выключить или включить дочерние</span>
                    </p>

                    <select name="status_children">
                        <option value="0" selected="selected">Не трогать дочерние при нажатии кнопки сохранить</option>
                        <option value="1">Выключить/включить только дочерние при нажатии кнопки сохранить</option>
                    </select>
                </div>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    $('input[name=\'path\']').autocomplete({
        delay: 500,
        source: function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function (json) {
                    json.unshift({
                        'category_id': 0,
                        'name': '<?php echo $text_none; ?>'
                    });

                    response($.map(json, function (item) {
                        return {
                            label: item.name,
                            value: item.category_id
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('input[name=\'path\']').val(ui.item.label);
            $('input[name=\'parent_id\']').val(ui.item.value);

            return false;
        },
        focus: function (event, ui) {
            return false;
        }
    });
</script>
<script type="text/javascript">
    $('input[name=\'virtual_path\']').autocomplete({
        delay: 500,
        source: function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
                dataType: 'json',
                success: function (json) {
                    json.unshift({
                        'category_id': 0,
                        'name': '<?php echo $text_none; ?>'
                    });

                    response($.map(json, function (item) {
                        return {
                            label: item.name,
                            value: item.category_id
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('input[name=\'virtual_path\']').val(ui.item.label);
            $('input[name=\'virtual_parent_id\']').val(ui.item.value);

            return false;
        },
        focus: function (event, ui) {
            return false;
        }
    });
</script>
