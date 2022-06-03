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
        <input type="hidden" id="clickedRow" name="clickedRow" value="0" />
        <table id="discount" class="list">
            <thead>
                <tr>
                    <td class="left"><?php echo $entry_proc; ?></td>
                    <td class="left"><?php echo $entry_group; ?></td>
                    <td class="left"></td>
                </tr>
            </thead>
                
            <?php $discount_row = 1; ?>
            <?php  for ($num = 1; $num <= $discount_rows_num; $num++) { 
                $discount_row_proc = 'discount_row' . $num . '_proc'; 
                $discount_group_row = 'discount_group_row' . $num; 
                $discount_customer_list = 'discount_row' . $num . '_customer_list'; ?> 
                
                <tbody id="discount-row<?php echo $discount_row; ?>">
                    <tr>  
                        <td class="left"> 
                            <?php if (is_array($$discount_customer_list)) { ?>
                            <?php foreach ($$discount_customer_list as $customer_list) { ?>
                                <input type="hidden" name="<?php echo $discount_customer_list; ?>[]" value="<?php echo $customer_list['customer_id']; ?>" size="5" />
                            <?php } } ?>       
                            <input type="text" name="<?php echo $discount_row_proc;?>" value="<?php echo $$discount_row_proc; ?>" size="5" /><b>(%)</b></td>
                        <td class="left"><select name="<?php echo $discount_group_row; ?>" id="select_group<?php echo $num; ?>" onchange="showHref(<?php echo $num; ?>)">
                          <?php if ($$discount_group_row == 0) { ?>
                                <option value="0" selected="selected"><?php echo $text_all; ?></option>
                          <?php } else { ?>
                                <option value="0"><?php echo $text_all; ?></option>
                          <?php } ?>
                          <?php foreach ($customer_groups as $customer_group) { ?>
                            <?php if ($customer_group['customer_group_id'] == $$discount_group_row) { ?>
                                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                            <?php } ?>
                          <?php } ?>
                          <?php if ($$discount_group_row == 777) { ?>
                                <option value="777" selected="selected"><?php echo $text_my_change; ?></option>
                          <?php } else { ?>
                                <option value="777"><?php echo $text_my_change; ?></option>
                          <?php } ?>
                          </select>                            
                          <?php if ($$discount_group_row == 777) { ?>
                                <a onclick="document.getElementById('clickedRow').value = <?php echo $num; ?>; $('#form').submit();" id="href_change<?php echo $num; ?>" style="visibility: visible;"><?php echo $text_edit_list; ?></a>
                          <?php } else { ?>
                                <a onclick="document.getElementById('clickedRow').value = <?php echo $num; ?>; $('#form').submit();" id="href_change<?php echo $num; ?>" style="visibility: hidden;"><?php echo $text_edit_list; ?></a>
                          <?php } ?>         
                        </td>      
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
          
        <input type="text" id="rows_num" style="display:none" name="discount_rows_num" value="<?php echo $discount_rows_num; ?>" />
          
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="discount_status">
                <?php if ($discount_status) { ?>
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
            <td><input type="text" name="discount_sort_order" value="<?php if (isset($discount_sort_order) && $discount_sort_order <> 0) echo $discount_sort_order;  else  echo '5';  ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_products; ?></td>
            <td>
                <a href="<?php echo $href_edit_pro; ?>" class="button"><?php echo $button_edit; ?></a>
                <br /> 

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
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function showHref(select_row) {
    var selected_group = document.getElementById('select_group' + select_row);
    var selection = selected_group.options[selected_group.selectedIndex].value;
    if (selection == 777) {
        document.getElementById('href_change' + select_row).style.visibility = "visible";
    } else {
        document.getElementById('href_change' + select_row).style.visibility = "hidden";
    }
};
//--></script> 

<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;
function addDiscount() {
    html  = '<tbody id="discount-row' + discount_row + '">';
	html += '  <tr>'; 
        html += '    <input type="hidden" name="discount_row' + discount_row + '_customer_list" value="0" size="1" />';
        html += '    <td class="left"><input type="text" name="discount_row' + discount_row + '_proc" value="<?php echo ''; ?>" size="5" /><b> (%)</b></td>';    
           html += '    <td class="left"><select name="discount_group_row' + discount_row + '"     id="select_group' + discount_row + '" onchange="showHref(' + discount_row + ')">';
           html += '    <option value="0"><?php echo $text_all; ?></option>';
           html += '    <?php foreach ($customer_groups as $customer_group) { ?>';
           html += '        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
           html += '    <?php } ?>';
           html += '    <option value="777"><?php echo $text_my_change; ?></option>';
           html += '    </select><a onclick="document.getElementById(\'clickedRow\').value =' + discount_row + '; $(\'#form\').submit();" id="href_change' + discount_row + '" style="visibility: hidden;"><?php echo $text_edit_list; ?></a></td>';       
        html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';     
    html += '</tbody>';
	$('#discount tfoot').before(html);
         
        document.getElementById("rows_num").value = discount_row; 
        <?php $discount_row++; ?>;
        discount_row++;	
};
//--></script> 
<?php echo $footer; ?>