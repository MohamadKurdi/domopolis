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
      <h1><img src="view/image/length.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_title; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['title'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_title[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_title[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_unit; ?></td>
              <td><?php foreach ($languages as $language) { ?>
                <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['unit'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_unit[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_unit[$language['language_id']]; ?></span><br />
                <?php } ?>
                <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_value; ?></td>
                <td><input type="text" name="value" value="<?php echo $value; ?>" /></td>
              </tr>
              <tr>
                <td>Системный ключ</td>
                <td>
                  <input type="text" name="system_key" value="<?php echo $system_key; ?>" />
                  <span class="help">Скорее всего, нигде не используется</span>
                </td>
              </tr>
              <tr>
                <td>Amazon</td>
                <td>
                  <input type="text" name="amazon_key" value="<?php echo $amazon_key; ?>" />
                  <span class="help">по этому ключу мы пытаемся определить вес товара на амазоне (логика deprecated, используйте множественное поле ввода)</span>
                </td>
              </tr>
               <tr>
                <td>Варианты указания</td>
                <td>
                  <textarea name="variants" cols="50" rows="5"><?php echo $variants; ?></textarea>
                  <span class="help">Варианты задания, на разных языках, через запятую</span>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
    <?php echo $footer; ?>