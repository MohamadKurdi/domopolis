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
      <h1><img src="view/image/product-groups.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>

    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
        <table class="form">
        
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td>
              <input type="text" name="product_group_name" value="<?php echo $product_group_name; ?>" />
              <?php if (isset($error_name)) { ?>
                <span class="error"><?php echo $error_name; ?></span>
              <?php } ?>
            </td>
          </tr>
          
          <tr>
            <td><?php echo $entry_feed; ?></td>
            <td>
              <select name="product_group_feed">
                <?php if ($product_group_feed) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <tr>
            <td>
                <?php echo $entry_feed_file; ?>                
            </td>
            <td>
                <input type="text" name="product_group_feed_file" value="<?php echo $product_group_feed_file; ?>" />
                <span class="help">
                   <i class="fa fa-info-circle"></i> Будет сформирован товарный фид с названием remarketing_group_feed_ЗНАЧЕНИЕ_ИЗ_ПОЛЯ_X.xml
                </span>
                <span class="help">
                   <i class="fa fa-info-circle"></i> Также в общем фиде товару будет добавлена метка custom_label_3 с значением PRODUCT_GROUP_ЗНАЧЕНИЕ_ИЗ_ПОЛЯ
                </span>
            </td>
          </tr>
          
          <tr>
            <td><?php echo $entry_text_color; ?> #<br/><span class="help">Например: 999999, FF0000</span></td>
            <td>
              <input type="text" id="product_group_text_color" name="product_group_text_color" value="<?php echo $product_group_text_color; ?>" />
            </td>
          </tr>
          
          <tr>
            <td><?php echo $entry_bg_color; ?> #<br/><span class="help">Например: 999999, FF0000</span></td>
            <td>
              <input type="text" id="product_group_bg_color" name="product_group_bg_color" value="<?php echo $product_group_bg_color; ?>" />

            </td>
          </tr>
          
          <tr>
            <td><?php echo $entry_icon; ?></td>
            <td>
              <input type="text" name="product_group_fa_icon" value="<?php echo $product_group_fa_icon; ?>" />
              <span class="help">Иконка font-awesome, может отображаться в некоторых местах</span>
            </td>
          </tr>
          
        </table>
        
      </form>
    </div>
  </div>
  
</div>
  
  <script type="text/javascript">
  $(function()
  {
    $.fn.jPicker.defaults.images.clientPath='view/image/';
    var LiveCallbackElement = $('#Live'),
    LiveCallbackButton = $('#LiveButton');
    $('#product_group_text_color').jPicker({window:{title:'Выбор цвета'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
    $('#product_group_bg_color').jPicker({window:{title:'Выбор цвета'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
  });
</script>

<?php echo $footer; ?>