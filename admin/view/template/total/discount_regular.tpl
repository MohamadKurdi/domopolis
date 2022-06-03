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
        <table class="form">
          <tr>
            <td><?php echo $entry_total; ?></td>
            <td><input type="text" name="discount_regular_total" value="<?php echo $discount_regular_total; ?>" size="5" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_sum_status; ?></td>
            <td><select name="discount_regular_order_status">
              <option value="0"><?php echo $text_all_status; ?></option>
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $discount_regular_order_status) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_group; ?></td>
            <td><select name="discount_regular_group">
              <option value="0"><?php echo $text_all; ?></option>
              <?php foreach ($customer_groups as $customer_group) { ?>
              <?php if ($customer_group['customer_group_id'] == $discount_regular_group) { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_proc; ?></td>
            <td><input type="text" name="discount_regular_proc" value="<?php echo $discount_regular_proc; ?>" size="1" /><?php echo ' %'; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="discount_regular_status">
                <?php if ($discount_regular_status) { ?>
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
            <td><input type="text" name="discount_regular_sort_order" value="<?php if (isset($discount_regular_sort_order) && $discount_regular_sort_order <> 0) echo $discount_regular_sort_order;  else  echo '5';  ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_products; ?></td>
            <td>
                <a href="<?php echo $href_edit_pro; ?>" class="button"><?php echo $button_edit; ?></a>
                <br /> 

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
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>