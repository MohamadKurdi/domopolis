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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <?php if ($discount_product_status) { ?>  
            <input type="hidden" id="status" name="discount_product_status" value="1" />
        <?php } else { ?>
            <input type="hidden" id="status" name="discount_product_status" value="0" />
        <?php } ?>
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_discount; ?></td>
              <td class="left"><?php echo $column_status; ?></td>
              <td class="left"><?php echo $column_products; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="left" style="font-size: 1.4em;"><?php echo $text_user; ?></td>
              <td class="left"><select name="discount_action" id="status_user" onchange="selaction()">
                  <?php if ($discount_action) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
              </select>
              </td>
              <td class="left">
                    <a href="<?php echo $href_edit_user; ?>"><?php echo $button_edit; ?></a>
                    <br /><br />
                    <?php if ($discount_product1) { ?>
                        <input type="checkbox" name="discount_product1" value="1" checked="checked"><?php echo $entry_product1; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_product1" value="1"><?php echo $entry_product1; ?>
                    <?php } ?>
                    <br />

                    <?php if ($discount_product2) { ?>
                        <input type="checkbox" name="discount_product2" value="1" checked="checked"><?php echo $entry_product2; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_product2" value="1"><?php echo $entry_product2; ?>
                    <?php } ?>
                    <br />

                    <?php if ($discount_product3) { ?>
                        <input type="checkbox" name="discount_product3" value="1" checked="checked"><?php echo $entry_product3; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_product3" value="1"><?php echo $entry_product3; ?>
                    <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="left" style="font-size: 1.4em;"><?php echo $text_regular; ?></td>
              <td class="left"><select name="discount_regular_action" id="status_regular" onchange="selaction()">
                  <?php if ($discount_regular_action) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
              </select>
              </td>
              <td class="left">
                    <a href="<?php echo $href_edit_regular; ?>"><?php echo $button_edit; ?></a>
                    <br /><br />
                    <?php if ($discount_regular_product1) { ?>
                        <input type="checkbox" name="discount_regular_product1" value="1" checked="checked"><?php echo $entry_product1; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_regular_product1" value="1"><?php echo $entry_product1; ?> 
                    <?php } ?>
                    <br />

                    <?php if ($discount_regular_product2) { ?>
                        <input type="checkbox" name="discount_regular_product2" value="1" checked="checked"><?php echo $entry_product2; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_regular_product2" value="1"><?php echo $entry_product2; ?> 
                    <?php } ?>
                    <br />

                    <?php if ($discount_regular_product3) { ?>
                        <input type="checkbox" name="discount_regular_product3" value="1" checked="checked"><?php echo $entry_product3; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_regular_product3" value="1"><?php echo $entry_product3; ?>
                    <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="left" style="font-size: 1.4em;"><?php echo $text_count; ?></td>
              <td class="left"><select name="discount_count_action" id="status_count" onchange="selaction()">
                  <?php if ($discount_count_action) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
              </select>
              </td>
              <td class="left">
                    <a href="<?php echo $href_edit_count; ?>"><?php echo $button_edit; ?></a>
                    <br /><br />
                    <?php if ($discount_count_product1) { ?>
                        <input type="checkbox" name="discount_count_product1" value="1" checked="checked"><?php echo $entry_product1; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_count_product1" value="1"><?php echo $entry_product1; ?> 
                    <?php } ?>
                    <br />

                    <?php if ($discount_count_product2) { ?>
                        <input type="checkbox" name="discount_count_product2" value="1" checked="checked"><?php echo $entry_product2; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_count_product2" value="1"><?php echo $entry_product2; ?>
                    <?php } ?>
                    <br />

                    <?php if ($discount_count_product3) { ?>
                        <input type="checkbox" name="discount_count_product3" value="1" checked="checked"><?php echo $entry_product3; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_count_product3" value="1"><?php echo $entry_product3; ?>
                    <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="left" style="font-size: 1.4em;"><?php echo $text_sum; ?></td>
              <td class="left"><select name="discount_sum_action" id="status_sum" onchange="selaction()">
                  <?php if ($discount_sum_action) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
              </select>
              </td>
              <td class="left">
                  <a href="<?php echo $href_edit_sum; ?>"><?php echo $button_edit; ?></a>
                  <br /><br />
                    <?php if ($discount_sum_product1) { ?>
                        <input type="checkbox" name="discount_sum_product1" value="1" checked="checked"><?php echo $entry_product1; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_sum_product1" value="1"><?php echo $entry_product1; ?>
                    <?php } ?>
                    <br />

                    <?php if ($discount_sum_product2) { ?>
                        <input type="checkbox" name="discount_sum_product2" value="1" checked="checked"><?php echo $entry_product2; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_sum_product2" value="1"><?php echo $entry_product2; ?>
                    <?php } ?>
                    <br />

                    <?php if ($discount_sum_product3) { ?>
                        <input type="checkbox" name="discount_sum_product3" value="1" checked="checked"><?php echo $entry_product3; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_sum_product3" value="1"><?php echo $entry_product3; ?>
                    <?php } ?>
              </td>
            </tr>
            <tr>
              <td class="left" style="font-size: 1.4em;"><?php echo $text_cumulative; ?></td>
              <td class="left"><select name="discount_cumulative_action" id="status_cumulative" onchange="selaction()">
                  <?php if ($discount_cumulative_action) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
              </select>        
              </td>
              <td class="left">
                  <a href="<?php echo $href_edit_cumulative; ?>"><?php echo $button_edit; ?></a>
                  <br /><br />
                    <?php if ($discount_cumulative_product1) { ?>
                        <input type="checkbox" name="discount_cumulative_product1" value="1" checked="checked"><?php echo $entry_product1; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_cumulative_product1" value="1"><?php echo $entry_product1; ?>
                    <?php } ?>
                    <br />

                    <?php if ($discount_cumulative_product2) { ?>
                        <input type="checkbox" name="discount_cumulative_product2" value="1" checked="checked"><?php echo $entry_product2; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_cumulative_product2" value="1"><?php echo $entry_product2; ?>
                    <?php } ?>
                    <br />

                    <?php if ($discount_cumulative_product3) { ?>
                        <input type="checkbox" name="discount_cumulative_product3" value="1" checked="checked"><?php echo $entry_product3; ?>
                    <?php } else { ?>
                        <input type="checkbox" name="discount_cumulative_product3" value="1"><?php echo $entry_product3; ?>
                    <?php } ?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function selaction() {
    if (document.getElementById('status_user').value == 1 || document.getElementById('status_regular').value == 1 || 
        document.getElementById('status_count').value == 1 || document.getElementById('status_cumulative').value == 1 || document.getElementById('status_sum').value == 1) {
    
        document.getElementById('status').value = 1;
    } else {
    
        document.getElementById('status').value = 0;    
    }	
};
</script> 
<?php echo $footer; ?>