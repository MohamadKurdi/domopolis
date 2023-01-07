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
<div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>" style="width: 80%;" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_name[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_attribute_group; ?></td>
            <td>
                <select name="attribute_group_id">
                    <?php foreach ($attribute_groups as $attribute_group) { ?>
                        <?php if ($attribute_group['attribute_group_id'] == $attribute_group_id) { ?>
                            <option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>Габариты</td>
            <td>
                <select name="dimension_type">
                    <option value="" <?php if (!$dimension_type) { ?>selected="selected"<? } ?> >Не определяет габариты</option>
                    <option value="length" <?php if ($dimension_type == 'length') { ?>selected="selected"<? } ?> > Определяет только длину</option>
                    <option value="width" <?php if ($dimension_type == 'width') { ?>selected="selected"<? } ?> > Определяет только ширину</option>
                    <option value="height" <?php if ($dimension_type == 'height') { ?>selected="selected"<? } ?> > Определяет только высоту</option>
                    <option value="dimensions" <?php if ($dimension_type == 'dimensions') { ?>selected="selected"<? } ?> > Определяет размер полностью</option>
                    <option value="weight" <?php if ($dimension_type == 'weight') { ?>selected="selected"<? } ?> > Определяет только вес</option>
                    <option value="all" <?php if ($dimension_type == 'all') { ?>selected="selected"<? } ?> > Определяет размер и вес полностью</option>
                </select>
            </td>
        </tr>

        <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /><br />
             <span class="help">-1 для того, чтоб не отображался в карте товара</span></td>
         </tr>

        <?php if (!empty($rand_attribute_values)) { ?>
           <tr>
            <td>Примеры значений атрибута</td>
                <td>
                    <?php $i=0; foreach ($rand_attribute_values as $rand_attribute_value) { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF; margin-left:10px; margin-bottom:5px;"><?php echo $rand_attribute_value ?></span>                         
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>

        <?php if (!empty($rand_attribute_values2)) { ?>
           <tr>
            <td>Примеры значений атрибута</td>
                <td>
                    <?php $i=0; foreach ($rand_attribute_values2 as $rand_attribute_value) { ?>
                            <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF; margin-left:10px; margin-bottom:5px;"><?php echo $rand_attribute_value ?></span>                         
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>

         <?php if (!empty($attribute_values)) { ?>
           <tr>
            <td>Изображения значений атрибута</td>
            <td>
                <?php if($attribute_values) { ?>
                    <table class="form">
                        <tr>
                            <td>Значение атрибута</td>
                            <td>Изображение</td>
                            <td></td>
                        </tr>
                        <?php foreach ($attribute_values as $value): ?>
                            <tr>
                                <td><strong><?=$value['text'] ?></strong></td>
                                <td>
                                    <div class="image">
                                        <img src="<?php echo $value['thumb']; ?>" alt="" id="thumb<?php print md5($value['text']); ?>" />
                                        <br />
                                        <input type="hidden" name="image[<?php print $value['text']; ?>]" value="<?php echo $value['image']; ?>" id="image<?php print md5($value['text']); ?>" />
                                        <a onclick="image_upload('image<?php print md5($value['text']); ?>', 'thumb<?php print md5($value['text']); ?>');">Выбрать</a>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <a onclick="$('#thumb<?php print md5($value['text']); ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php print md5($value['text']); ?>').attr('value', '');">Очистить</a>
                                    </div>
                                </td>
                                <td>
                                    <select name="information_id[<?=$value['text']?>]">
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
                        <?php endforeach; ?>
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
                                            success: function(text) {
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
            </td>
        </tr>
    <?php } ?>
    </table>
</form>
</div>
</div>
</div>
<?php echo $footer; ?>