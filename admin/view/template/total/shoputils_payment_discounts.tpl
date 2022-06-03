<?php 
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/
?>
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
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="shoputils_payment_discounts_status">
                <?php if ($shoputils_payment_discounts_status) { ?>
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
            <td><input type="text" name="shoputils_payment_discounts_sort_order" value="<?php echo $shoputils_payment_discounts_sort_order; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_percent; ?></td>
            <td><input type="text" name="shoputils_payment_discounts_percent" value="<?php echo $shoputils_payment_discounts_percent; ?>" /></td>
          </tr>
          <tr>
            <td>
                <?php echo $entry_payment_methods; ?>
                <div class="help"><?php echo $entry_payment_methods_help; ?></div>
            </td>
            <td>
                <div class="scrollbox" style="width:100%; height:150px;" style="height:110px">
                    <?php $class = 'odd'; ?>
                    <?php foreach ($payment_methods as $key => $method) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                        <?php if (in_array($key, $shoputils_payment_discounts_payment_methods)) { ?>
                        <input type="checkbox" name="shoputils_payment_discounts_payment_methods[]" value="<?php echo $key; ?>"
                               checked="checked"/>
                        <?php echo $method['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="shoputils_payment_discounts_payment_methods[]" value="<?php echo $key; ?>"/>
                        <?php echo $method['name']; ?>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>