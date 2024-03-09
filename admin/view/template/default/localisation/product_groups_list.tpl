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

  <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
  <?php } ?>

  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/product-groups.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
        <a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
      </div>
    </div>

    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);">
              </td>
              <td class="center"></td>
              <td class="center"><?php echo $column_name; ?></td>
              <td class="center">В общем фиде</td>  
              <td class="center">Отдельный фид</td>
              <td class="center">Файл своего фида</td>  
              <td class="center">Подсветка</td>
              <td class="center"><?php echo $column_icon; ?></td>
              <td class="center">Товаров</td>
              <td class="center"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          
          <tbody>

            <?php if ($product_groups) { ?>
              <?php foreach ($product_groups as $product_group) { ?>
                <tr>
                  <td style="text-align: center;">
                    <?php if ($product_group['selected']) { ?>
                      <input type="checkbox" name="selected[]" value="<?php echo $product_group['product_group_id']; ?>" checked="checked" />
                    <?php } else { ?>
                      <input type="checkbox" name="selected[]" value="<?php echo $product_group['product_group_id']; ?>" />
                    <?php } ?>
                  </td>

                  <td class="center">
                    <b><?php echo $product_group['product_group_id']; ?></b>
                  </td>

                  <td class="center">
                    <?php echo $product_group['product_group_name']; ?>
                  </td>

                   <td class="center">
                    <?php if (!$product_group['product_group_exclude_remarketing']) { ?>
                        <i class="fa fa-check-circle" style="color:#4ea24e"></i>
                    <?php } else { ?>
                        <i class="fa fa-times-circle" style="color:#cf4a61"></i>
                    <?php } ?>
                  </td>

                  <td class="center">
                    <?php if ($product_group['product_group_feed']) { ?>
                        <i class="fa fa-check-circle" style="color:#4ea24e"></i>
                    <?php } else { ?>
                        <i class="fa fa-times-circle" style="color:#cf4a61"></i>
                    <?php } ?>
                  </td>

                  <td class="center">
                    <?php echo $product_group['product_group_feed_file']; ?>
                  </td>

                  <td class="center" style="color:#<?php echo $product_group['product_group_text_color']; ?>;">
                    <span style="padding:5px; background-color:#<?php echo $product_group['product_group_bg_color']; ?>; color:#<?php echo $product_group['product_group_text_color']; ?>;">
                      <?php echo $product_group['product_group_name']; ?>
                    </span>
                  </td>

                  <td class="center">
                    <?php if ($product_group['product_group_fa_icon']) { ?>
                      <i class="fa <?php echo $product_group['product_group_fa_icon']; ?>"></i>
                    <?php } else { ?>
                      <i class="fa fa-times-circle" style="color:#cf4a61"></i>
                    <?php } ?>
                  </td>

                  <td class="center">
                    <span style="padding:5px; background-color:#000; color:#fff;">
                      <?php echo $product_group['product_group_total_products']; ?>
                    </span>
                  </td>

                  <td class="right">
                    <?php foreach ($product_group['action'] as $action) { ?>
                      <a href="<?php echo $action['href']; ?>" class="button"><?php echo $action['text']; ?></a>
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            <?php } else { ?>
              <tr>
                <td class="center" colspan="8">
                  <?php echo $text_no_results; ?>
                </td>
              </tr>
            <?php } ?>

          </tbody>
        </table>
      </form>

      <div class="pagination">
        <?php echo $pagination; ?>
      </div>

    </div>
  </div>
</div>

<?php echo $footer; ?>