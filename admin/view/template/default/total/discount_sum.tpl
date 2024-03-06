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
        <table id="discount" class="list">
            <thead>
                <tr>
                    <td class="left"><?php echo $entry_amount; ?></td>
                    <td class="left"><?php echo $entry_proc; ?></td>
                    <td class="left"></td>
                </tr>
            </thead>
            
            <?php $discount_row = 1; ?>
            <?php  for ($num = 1; $num <= $discount_sum_rows_num; $num++) { 
                $discount_sum_row_count = 'discount_sum_row' . $num . '_count';
                $discount_sum_row_proc = 'discount_sum_row' . $num . '_proc'; ?>         
                <tbody id="discount-row<?php echo $discount_row; ?>">
                    <tr>  
                        <td class="left"><input type="text" name="<?php echo $discount_sum_row_count;?>" value="<?php echo $$discount_sum_row_count; ?>" size="5" /></td>
                        <td class="left"><input type="text" name="<?php echo $discount_sum_row_proc;?>" value="<?php echo $$discount_sum_row_proc; ?>" size="5" /><b> (%)</b></td>
                        <td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                    </tr>
                </tbody>
                     
                <?php $discount_row++; ?>
            <?php } ?>
            
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="right"><a onclick="addDiscount();" class="button"><?php echo $button_add; ?></a></td>
                </tr>
            </tfoot>
        </table>
          
        <input type="text" id="rows_num" style="display:none" name="discount_sum_rows_num" value="<?php echo $discount_sum_rows_num; ?>" />
                   
        <table class="form">
            <tr>
                <td><?php echo $entry_group; ?></td>
                <td><select name="discount_sum_group">
                  <option value="0"><?php echo $text_all; ?></option>
                  <?php if ($discount_sum_group == 777) { ?>
                  <option value="777" selected="selected"><?php echo $text_login; ?></option>
                  <?php } else { ?>
                  <option value="777"><?php echo $text_login; ?></option>
                  <?php } ?>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $discount_sum_group) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
                <td><?php echo $entry_status; ?></td>
                <td>
                    <select name="discount_sum_status">
                        <?php if ($discount_sum_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $entry_sort_order; ?></td>
                <td><input type="text" name="discount_sum_sort_order" value="<?php if (isset($discount_sum_sort_order) && $discount_sum_sort_order <> 0) echo $discount_sum_sort_order;  else  echo '5';  ?>" size="1" /></td>
            </tr>
            <tr>
                <td><?php echo $entry_products; ?></td>
                <td>
                    <a href="<?php echo $href_edit_pro; ?>" class="button"><?php echo $button_edit; ?></a>
                    <br /> 

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
        </table> 
      </form>   
    </div>
  </div>
</div>


<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
    html  = '<tbody id="discount-row' + discount_row + '">';
	html += '  <tr>'; 
            html += '    <td class="left"><input type="text" name="discount_sum_row' + discount_row + '_count" value="<?php echo ''; ?>" size="5" /></td>';
            html += '    <td class="left"><input type="text" name="discount_sum_row' + discount_row + '_proc" value="<?php echo ''; ?>" size="5" /><b> (%)</b></td>';
            html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
    html += '</tbody>';
	
	$('#discount tfoot').before(html);
         
        document.getElementById("rows_num").value = discount_row; 
        <?php $discount_row++; ?>;
        discount_row++;	
}


//--></script> 

<?php echo $footer; ?>