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
                    <td class="left"><?php echo $entry_name_color; ?></td>
                    <td class="left"><?php echo $entry_cumulative; ?></td>
                    <td class="left"><?php echo $entry_proc; ?></td>
                    <td class="left"><?php echo $entry_start_date; ?></td>
                    <td class="left"><?php echo $entry_end_date; ?></td>
                    <td class="left"><?php echo $entry_sum_date; ?></td>
                    <td class="left"></td>
                </tr>
            </thead>
            <?php $discount_row = 1; ?>
            <?php  for ($num = 1; $num <= $discount_cumulative_rows_num; $num++) { 
                $discount_cumulative_row_name = 'discount_cumulative_row' . $num . '_name'; 
                $discount_cumulative_row_color = 'discount_cumulative_row' . $num . '_color'; 
                $discount_cumulative_row_cumulative = 'discount_cumulative_row' . $num . '_cumulative';
                $discount_cumulative_row_proc = 'discount_cumulative_row' . $num . '_proc';   
                $discount_cumulative_row_start = 'discount_cumulative_row' . $num . '_start'; 
                $discount_cumulative_row_end = 'discount_cumulative_row' . $num . '_end'; 
                $discount_cumulative_row_sum = 'discount_cumulative_row' . $num . '_sum'; 
            ?> 
                <tbody id="discount-row<?php echo $discount_row; ?>">
                    <tr>  
                        <td class="left">
                            <input type="text" name="<?php echo $discount_cumulative_row_name;?>" value="<?php echo $$discount_cumulative_row_name; ?>" size="15" />   
                            <select name="<?php echo $discount_cumulative_row_color;?>" class="status_color">
                                <option value="0" selected="selected"><?php echo $entry_not_color; ?></option>
                                
                                <?php if ($$discount_cumulative_row_color == 'bronze') { ?>
                                    <option value="bronze" class="bronze" selected="selected"><?php echo $entry_bronze; ?></option>
                                <?php } else { ?>
                                    <option value="bronze" class="bronze"><?php echo $entry_bronze; ?></option>
                                <?php } ?>
                                
                                <?php if ($$discount_cumulative_row_color == 'silver') { ?>
                                    <option value="silver" class="silver" selected="selected"><?php echo $entry_silver; ?></option>
                                <?php } else { ?>
                                    <option value="silver" class="silver"><?php echo $entry_silver; ?></option>
                                <?php } ?>
                                
                                <?php if ($$discount_cumulative_row_color == 'gold') { ?>
                                    <option value="gold" class="gold" selected="selected"><?php echo $entry_gold; ?></option>
                                <?php } else { ?>
                                    <option value="gold" class="gold"><?php echo $entry_gold; ?></option>
                                <?php } ?>
                                
                                <?php if ($$discount_cumulative_row_color == 'red') { ?>
                                    <option value="red" class="red" selected="selected"><?php echo $entry_red; ?></option>
                                <?php } else { ?>
                                    <option value="red" class="red"><?php echo $entry_red; ?></option>
                                <?php } ?>
                                
                                <?php if ($$discount_cumulative_row_color == 'green') { ?>
                                    <option value="green" class="green" selected="selected"><?php echo $entry_green; ?></option>
                                <?php } else { ?>
                                    <option value="green" class="green"><?php echo $entry_green; ?></option>
                                <?php } ?>
                                
                                <?php if ($$discount_cumulative_row_color == 'blue') { ?>
                                    <option value="blue" class="blue" selected="selected"><?php echo $entry_blue; ?></option>
                                <?php } else { ?>
                                    <option value="blue" class="blue"><?php echo $entry_blue; ?></option>
                                <?php } ?>
                                
                                <?php if ($$discount_cumulative_row_color == 'black') { ?>
                                    <option value="black" class="black" selected="selected"><?php echo $entry_black; ?></option>
                                <?php } else { ?>
                                    <option value="black" class="black"><?php echo $entry_black; ?></option>
                                <?php } ?>
                            </select>   
                        </td>
                        <td class="left"><input type="text" name="<?php echo $discount_cumulative_row_cumulative;?>" value="<?php echo $$discount_cumulative_row_cumulative; ?>" size="5" /></td>
                        <td class="left"><input type="text" name="<?php echo $discount_cumulative_row_proc;?>" value="<?php echo $$discount_cumulative_row_proc; ?>" size="5" /><b> (%)</b></td>
                        <td class="left"><input type="text" name="<?php echo $discount_cumulative_row_start;?>" value="<?php echo $$discount_cumulative_row_start; ?>" class="date" /></td>
                        <td class="left"><input type="text" name="<?php echo $discount_cumulative_row_end;?>" value="<?php echo $$discount_cumulative_row_end; ?>" class="date" /></td>
                        <td class="left">
                            <select name="<?php echo $discount_cumulative_row_sum;?>">
                                <option value="0" selected="selected"><?php echo $text_all_time; ?></option>
                                <?php if ($$discount_cumulative_row_sum == 1) { ?>
                                    <option value="1" selected="selected"><?php echo $text_period_time; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_period_time; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                    </tr>
                </tbody> 
                <?php $discount_row++; ?>
            <?php } ?>
            
            <tfoot>
                <tr>
                    <td colspan="6"></td>
                    <td class="right"><a onclick="addDiscount();" class="button"><?php echo $button_add; ?></a></td>
                </tr>
            </tfoot>
        </table>
          
        <input type="text" id="rows_num" style="display:none" name="discount_cumulative_rows_num" value="<?php echo $discount_cumulative_rows_num; ?>" />
                   
        <table class="form">
            <tr>
                <td><?php echo $entry_sum_status; ?></td>
                <td><select name="discount_cumulative_order_status">
                  <option value="0"><?php echo $text_all_status; ?></option>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $discount_cumulative_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
                <td><?php echo $entry_group; ?></td>
                <td><select name="discount_cumulative_group">
                  <option value="0"><?php echo $text_all; ?></option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $discount_cumulative_group) { ?>
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
                    <select name="discount_cumulative_status">
                        <?php if ($discount_cumulative_status) { ?>
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
                <td><input type="text" name="discount_cumulative_sort_order" value="<?php if (isset($discount_cumulative_sort_order) && $discount_cumulative_sort_order <> 0) echo $discount_cumulative_sort_order;  else  echo '5';  ?>" size="1" /></td>
            </tr>
            <tr>
                <td><?php echo $entry_products; ?></td>
                <td>
                    <a href="<?php echo $href_edit_pro; ?>" class="button"><?php echo $button_edit; ?></a>
                    <br /> 

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
            html += '    <td class="left"><input type="text" name="discount_cumulative_row' + discount_row + '_name" value="" size="15" />';
            html += '       <select name="discount_cumulative_row' + discount_row + '_color" class="status_color">';
            html += '           <option value="0" selected="selected"><?php echo $entry_not_color; ?></option>';
            html += '           <option value="bronze" class="bronze"><?php echo $entry_bronze; ?></option>';
            html += '           <option value="silver" class="silver"><?php echo $entry_silver; ?></option>';
            html += '           <option value="gold" class="gold"><?php echo $entry_gold; ?></option>';
            html += '           <option value="red" class="red"><?php echo $entry_red; ?></option>';
            html += '           <option value="green" class="green"><?php echo $entry_green; ?></option>';                      
            html += '           <option value="blue" class="blue"><?php echo $entry_blue; ?></option>';
            html += '           <option value="black" class="black"><?php echo $entry_black; ?></option>';
            html += '       </select>';
            html += '    </td>';
        
            html += '    <td class="left"><input type="text" name="discount_cumulative_row' + discount_row + '_cumulative" value="" size="5" /></td>';
            html += '    <td class="left"><input type="text" name="discount_cumulative_row' + discount_row + '_proc" value="" size="5" /><b> (%)</b></td>';
            html += '    <td class="left"><input type="text" name="discount_cumulative_row' + discount_row + '_start" value="0000-00-00" class="date" /></td>';
            html += '    <td class="left"><input type="text" name="discount_cumulative_row' + discount_row + '_end" value="0000-00-00" class="date" /></td>';            

            html += '    <td class="left"><select name="discount_cumulative_row' + discount_row + '_sum">';
            html += '      <option value="0" selected="selected"><?php echo $text_all_time; ?></option>';
            html += '      <option value="1"><?php echo $text_period_time; ?></option>';
            html += '    </select></td>';
            
            html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
    html += '</tbody>';
	
    $('#discount tfoot').before(html);

    document.getElementById("rows_num").value = discount_row; 
    <?php $discount_row++; ?>;
    discount_row++;
    
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
}


//--></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 
<style> 
select.status_color option.bronze { color: #CD7F32 ;} 
select.status_color option.silver { color: #C0C0C0; }
select.status_color option.gold { color: #FFD700; }
select.status_color option.red { color: #FF0000; }
select.status_color option.green { color: #00FF00; }
select.status_color option.blue { color: #0000FF; }
select.status_color option.black { color: #000000; }
</style>
<?php echo $footer; ?>