<?php echo $header; ?>
<div id="content">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading order_head">
            <h1><img src="view/image/information.png" alt=""/> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tabs" class="htabs">
                    <a href="#tab-main"><span style="color:#7F00FF;"><i class="fa fa-refresh"></i> Основное</span></a>
                    <?php if ($this->config->get('config_enable_attributes_values_logic')) { ?>
                    <a href="#tab-value-images"><span style="color:#0054b3;"><i class="fa fa-refresh"></i> Изображения значений</span></a>
                    <?php } ?>
                    <a href="#tab-relations"><span style="color:#cf4a61;"><i class="fa fa-cogs"></i> Сопоставления</span></a>
                </div>

                    <div class="clr"></div>
                    <div class="th_style"></div>

                    <div id="tab-main">
                        <table class="form">
                            <tr>
                                <td style="width:60%;">
                                    <p>
                                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Название</span>
                                    </p>

                                    <?php foreach ($languages as $language) { ?>
                                    <div style="margin-bottom: 5px;">
                                        <input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>" style="width: 80%;"/>
                                        <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>
                                        <?php if (isset($error_name[$language['language_id']])) { ?>
                                        <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br/>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                </td>
                                <td style="width:15%;">
                                    <div>
                                        <p>
                                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Группа</span>
                                        </p>
                                        <select name="attribute_group_id">
                                            <?php foreach ($attribute_groups as $attribute_group) { ?>
                                            <?php if ($attribute_group['attribute_group_id'] == $attribute_group_id) { ?>
                                            <option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div>
                                        <p>
                                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Габариты</span>
                                        </p>

                                        <select name="dimension_type">
                                            <option value=""
                                            <?php if (!$dimension_type) { ?>selected="selected"<? } ?> >Не определяет
                                            габариты</option>
                                            <option value="length"
                                            <?php if ($dimension_type == 'length') { ?>selected="selected"<? } ?> >
                                            Определяет
                                            только длину</option>
                                            <option value="width"
                                            <?php if ($dimension_type == 'width') { ?>selected="selected"<? } ?> >
                                            Определяет
                                            только ширину</option>
                                            <option value="height"
                                            <?php if ($dimension_type == 'height') { ?>selected="selected"<? } ?> >
                                            Определяет
                                            только высоту</option>
                                            <option value="dimensions"
                                            <?php if ($dimension_type == 'dimensions') { ?>selected="selected"<? } ?> >
                                            Определяет размер полностью</option>
                                            <option value="weight"
                                            <?php if ($dimension_type == 'weight') { ?>selected="selected"<? } ?> >
                                            Определяет
                                            только вес</option>
                                            <option value="all"
                                            <?php if ($dimension_type == 'all') { ?>selected="selected"<? } ?> >
                                            Определяет
                                            размер и вес полностью</option>
                                        </select>
                                    </div>
                                </td>
                                <td style="width:15%;">
                                    <p>
                                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Сортировка</span>
                                    </p>

                                    <input type="number" step="1" name="sort_order" value="<?php echo $sort_order; ?>" size="5"/>
                                </td>
                            </tr>
                        </table>


                        <h2>Примеры случайных значений атрибута</h2>
                        <?php if (!empty($rand_attribute_values)) { ?>
                        <div>
                            <?php $i=0; foreach ($rand_attribute_values as $rand_attribute_value) { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF; margin-left:10px; margin-bottom:5px;"><?php echo $rand_attribute_value ?></span>
                            <?php } ?>
                        </div>
                        <?php } ?>

                        <?php if (!empty($rand_attribute_values2)) { ?>
                        <div>
                            <?php $i=0; foreach ($rand_attribute_values2 as $rand_attribute_value) { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF; margin-left:10px; margin-bottom:5px;"><?php echo $rand_attribute_value ?></span>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                    <?php if ($this->config->get('config_enable_attributes_values_logic')) { ?>
                    <div id="tab-value-images">
                        <h2>Изображения значений атрибута</h2>
                        <?php if($attribute_values) { ?>
                        <table class="form">
                            <tr>
                                <td>Значение атрибута</td>
                                <td>Изображение</td>
                                <td></td>
                            </tr>
                            <?php foreach ($attribute_values as $value) { ?>
                            <tr>
                                <td><strong><?=$value['text'] ?></strong></td>
                                <td>
                                    <div class="image">
                                        <img src="<?php echo $value['thumb']; ?>" alt="" id="thumb<?php print md5($value['text']); ?>"/>
                                        <br/>
                                        <input type="hidden" name="image[<?php print $value['text']; ?>]" value="<?php echo $value['image']; ?>" id="image<?php print md5($value['text']); ?>"/>
                                        <a onclick="image_upload('image<?php print md5($value['text']); ?>', 'thumb<?php print md5($value['text']); ?>');">Выбрать</a>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <a onclick="$('#thumb<?php print md5($value['text']); ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php print md5($value['text']); ?>').attr('value', '');">Очистить</a>
                                    </div>
                                </td>
                                <td>
                                    <select name="information_id[<?php echo $value['text']; ?>]">
                                        <option value="0">--- Не выбрано ----</option>
                                        <?php foreach ($informations as $information) { ?>
                                        <?php if ($information['information_id'] == $value['information_id']) { ?>
                                        <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                        <script type="text/javascript">
                            function image_upload(field, thumb) {
                                $('#dialog').remove();

                                $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

                                $('#dialog').dialog({
                                    title: 'Файловый менеджер',
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
                            };
                        </script>

                        <?php } else { ?>
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Значений данного атрибута не найдено, либо отключена логика в настройках!</span>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <div id="tab-relations">
                        <style>
                            .openai-div input[type="text"]{
                                background:#5D5D5D;
                                color:white;
                                font-size:12px;
                                font-weight:700;
                                border:2px solid #5D5D5D;
                                padding:5px 10px;
                            }
                            .openai-div a.button{
                                padding:10px 10px;
                            }

                            .openai-div .openai-request{
                                border-bottom:1px dashed grey;
                                cursor: pointer;
                                line-height: 26px;
                                display:inline-block;
                            }
                        </style>
                        <?php foreach ($languages as $language) { ?>
                        <div style="float:left; margin-right:10px; width:<?php echo round(100/count($languages)) - 2; ?>%">
                            <h2 style="border-bottom: 0px;"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/> Сопоставление атрибута </h2>

                            <?php if ($this->config->get('config_openai_enable_translate_and_synonym')) { ?>
                            <div class="openai-div" style="margin-bottom:10px; padding:15px; border:1px dashed grey;">
                                <table style="width:100%">
                                    <tr>
                                        <td>
                                            <input style="width:90%;" type="text" name="openai-synonyms-request-<?php echo $language['language_id']; ?>" data-target="attribute_variants<?php echo $language['language_id']; ?>" value="" />
                                        </td>
                                        <td style="width:150px; vertical-align:top;">
                                            <a class="button" onclick="synonymsai($(this), '<?php echo $language['language_id']; ?>');">🤖 OpenAI Magic</a><span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php if (!empty($openai_attribute_synonyms_requests[$language['code']])) { ?>
                                            <?php foreach ($openai_attribute_synonyms_requests[$language['code']] as $openai_attribute_synonym_requests) { ?>
                                            <div class="openai-request" onclick="$('input[name=\'openai-synonyms-request-<?php echo $language['language_id']; ?>\']').val($(this).text())"><?php echo $openai_attribute_synonym_requests; ?></div><br />
                                            <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php } ?>

                            <textarea rows="30" style="width:98%" id="attribute_variants<?php echo $language['language_id']; ?>" name="attribute_variants[<?php echo $language['language_id']; ?>]"><?php echo isset($attribute_variants[$language['language_id']]) ? $attribute_variants[$language['language_id']] : ''; ?></textarea>
                        </div>
                        <?php } ?>

                        <script type="text/javascript" >
                            function synonymsai(elem, language_id){
                                let request  	= $("input[name='openai-synonyms-request-" + language_id + "']").val();

                                $.ajax({
                                    url: 'index.php?route=catalog/attribute/synonymsbyai&token=<?php echo $token; ?>',
                                    type: 'POST',
                                    dataType: 'text',
                                    data: {
                                        request: request,
                                    },
                                    success: function(text) {
                                        $('#attribute_variants' + language_id).val(text + '\n' + $('#attribute_variants' + language_id).val());
                                    },
                                    beforeSend: function(){
                                        elem.next().html('<i class="fa fa-spinner fa-spin"></i>');
                                    },
                                    complete: function(){
                                        elem.next().html('<i class="fa fa-check"></i>');
                                    }
                                });
                            }
                        </script>
                    </div>
            </form>
        </div>
    </div>
</div>

<style>
    #tabs > a

    {font-weight:700}
</style>
<script type="text/javascript">
    $('#tabs a').tabs();
</script>
<?php echo $footer; ?>