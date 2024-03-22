<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?>
        <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading order_head">
            <h1><img src="view/image/order.png" alt=""/> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="list">
                    <thead>
                    <tr>
                        <td width="1" style="text-align: center;">
                            <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                        </td>
                        <td class="right"></td>
                        <td class="left">
                            <?php if ($sort == 'ad.name') { ?>
                            <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                            <?php } ?>
                        </td>
                        <?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
                        <td class="left">
                            <?php echo $column_name; ?>
                        </td>
                        <?php } ?>
                        <td class="left">
                            <?php if ($sort == 'attribute_group') { ?>
                            <a href="<?php echo $sort_attribute_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_attribute_group; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_attribute_group; ?>"><?php echo $column_attribute_group; ?></a>
                            <?php } ?>
                        </td>
                        <td class="left">
                            <?php if ($sort == 'dimension_type') { ?>
                            <a href="<?php echo $sort_dimension_type; ?>" class="<?php echo strtolower($order); ?>">Габариты</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_dimension_type; ?>">Габариты</a>
                            <?php } ?>
                        </td>
                        <td class="left">
                            Значений
                        </td>
                        <td class="center" colspan="<?php echo count($this->registry->get('languages')); ?>">
                            Редактор значений
                        </td>
                        <td class="right">
                            <?php if ($sort == 'a.sort_order') { ?>
                            <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                            <?php } ?>
                        </td>
                        <td class="right"><?php echo $column_action; ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($attributes) { ?>
                    <?php foreach ($attributes as $attribute) { ?>
                    <tr>
                        <td style="text-align: center;">
                            <?php if ($attribute['selected']) { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $attribute['attribute_id']; ?>" checked="checked"/>
                            <?php } else { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $attribute['attribute_id']; ?>"/>
                            <?php } ?>
                        </td>
                        <td class="center" style="width:50px;">
                            <b style="color:#34913e; font-size:18px;"><?php echo $attribute['attribute_id']; ?></b>
                        </td>
                        <td class="left">
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo $attribute['name']; ?></span>

                            <?php if (!empty($attribute['values'])) { ?>
                            <br/>
                            <?php foreach ($attribute['values'] as $value) { ?>
                            <small style="margin-right:5px;"><?php echo $value['text']; ?></small>
                            <?php } ?>
                            <? } ?>
                        </td>
                        <?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
                        <td class="left">
                            <?php if (!empty($attribute['descriptions'][$this->
                            config->get('config_rainforest_source_language_id')]['name'])) { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><?php echo $attribute['descriptions'][$this->
                                config->get('config_rainforest_source_language_id')]['name']; ?></span>

                            <?php if (!empty($attribute['values_native'])) { ?>
                            <br/>
                            <?php foreach ($attribute['values_native'] as $value) { ?>
                            <small style="margin-right:5px;"><?php echo $value['text']; ?></small>
                            <?php } ?>
                            <? } ?>
                            <? } ?>
                        </td>
                        <?php } ?>
                        <td class="center" style="width:100px;">
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#32bd38; color:#FFF"><?php echo $attribute['attribute_group']; ?></span>
                        </td>
                        <td class="center" style="width:100px;">

                            <?php if ($attribute['dimension_type'] == 'length') { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Длина</span>
                            <?php } elseif ($attribute['dimension_type'] == 'width') { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Ширина</span>
                            <?php } elseif ($attribute['dimension_type'] == 'height') { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Высота</span>
                            <?php } elseif ($attribute['dimension_type'] == 'dimensions') { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Габариты</span>
                            <?php } elseif ($attribute['dimension_type'] == 'weight') { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Вес</span>
                            <?php } elseif ($attribute['dimension_type'] == 'all') { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Всё</span>
                            <?php } else { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Ничего</span>
                            <?php } ?>

                        </td>

                        <td class="center" style="width:50px;">
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><?php echo $attribute['count']; ?></span>
                        </td>

                        <?php foreach ($this->registry->get('languages') as $language_id => $language) { ?>
                        <td class="center" style="width:150px;">
                            <a target="_blank" class="button" href="<?php echo $attribute['values_editor'][$language_id]; ?>">Значения <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/></a>
                        </td>
                        <?php } ?>

                        <td class="center" style="width:50px;">
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $attribute['sort_order']; ?></span>
                        </td>
                        <td class="right" style="width:50px;">
                            <?php foreach ($attribute['action'] as $action) { ?>
                            <a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>
<?php echo $footer; ?>