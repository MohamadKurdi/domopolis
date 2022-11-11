<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <div class="ocl-ui-content">
      
      <?php if (isset($error_license) && !empty($error_license)) { ?><div class="warning"><?php echo $error_license; ?></div><?php } ?>
      <?php if (isset($error) && !empty($error)) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
      <?php if (isset($warning) && !empty($warning)) { ?><div class="warning"><?php echo $warning; ?></div><?php } ?>
      <?php if (isset($success) && !empty($success)) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
      
      <div class="ocl-ui-main-menu">
        <ul>
        	<?php foreach($main_menu as $key => $value){ ?>
        		<?php if($main_cfg[$key] == 1) { ?>
	                <li<?php if($driver == $key) echo ' class="selected"'; ?>><a href="<?php echo $main_url[$key];?>"><?php echo $value;?></a></li>
	            <?php }?>
        	<?php }?>
        </ul>
      </div>

		<div class="ocl-ui-header"><span class="ocl-ui-module-name"><?php echo $ocl_module_name; ?></span><?php echo $tpl_home_page; ?></div>
      
      <!-- BEGIN Customer Driver -->
		<div id="submenu"class="ocl-ui-submenu">
            <ul>
               <li><a href="#tab_export" id="link_tab_export"><?php echo $tab_export; ?></a></li>
               <!-- <li><a href="#tab_help" id="link_tab_help"><?php echo $tab_help; ?></a></li> -->
            </ul>
        </div>
        <!-- BEGIN Customers vtabs-content -->
        <div class="ocl-ui-body-content">
        
        <!-- BEGIN Customers Tab Content -->
            <div id="tab_export" class="ocl-ui-tab-content">
                <form action="<?php echo $action_export; ?>" method="post" id="form_export" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
                <table style="width: 100%;">
                    <tr><td style="width: 50%;vertical-align: top;">
                        
                            <table class="ocl-ui-form">
                                <tr><td><?php echo $entry_customer_group;?></td><td>
                                    <select name="csv_export[customer_group_id]" class="span140">
                                            <option value="0" <?php if ($csv_export['customer_group_id'] == 0) echo ' selected="selected"'; ?>><?php echo $text_all; ?></option>
                                        <?php foreach ($customer_groups as $customer_group) { ?>
                                            <?php if ($customer_group['customer_group_id'] == $csv_export['customer_group_id']) { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td></tr>
                                
                                <tr><td><?php echo $entry_file_format;?></td><td>
                                    <select name="csv_export[file_format]" class="span140">
                                        <option value="csv" <?php if( isset($csv_export['file_format']) AND $csv_export['file_format'] == 'csv' ) echo ' selected="selected"';?>>&nbsp;CSV&nbsp;</option>
                                        <option value="vcf" <?php if( isset($csv_export['file_format']) AND $csv_export['file_format'] == 'vcf' ) echo ' selected="selected"';?>>&nbsp;vCard&nbsp;</option>
                                    </select>
                                </td></tr>
                                <tr><td><?php echo $entry_file_encoding;?></td><td>
                                    <select name="csv_export[file_encoding]" class="span140">
                                        <option value="UTF-8" <?php if( isset($csv_export['file_encoding']) AND $csv_export['file_encoding'] == 'UTF-8' ) echo ' selected="selected"';?>>&nbsp;UTF-8&nbsp;</option>
                                        <option value="WINDOWS-1251" <?php if( isset($csv_export['file_encoding']) AND $csv_export['file_encoding'] == 'WINDOWS-1251' ) echo ' selected="selected"';?>>&nbsp;Windows-1251&nbsp;</option>
                                    </select>
                                </td></tr>
                                <tr><td><?php echo $entry_csv_delimited;?></td><td>
                                    <select name="csv_export[csv_delimite]" class="span80">
                                        <option value=";" <?php if( isset($csv_export['csv_delimite']) AND $csv_export['csv_delimite'] == ';' ) echo ' selected="selected"';?>>;</option>
                                        <option value="," <?php if( isset($csv_export['csv_delimite']) AND $csv_export['csv_delimite'] == ',' ) echo ' selected="selected"';?>>,</option>
                                        <option value="\t" <?php if( isset($csv_export['csv_delimite']) AND $csv_export['csv_delimite'] == '\t' ) echo ' selected="selected"';?>>TAB</option>
                                    </select>
                                </td></tr>
                                <tr><td><?php echo $entry_date_start;?></td><td><input type="text" name="csv_export[date_start]" value="<?php echo $csv_export['date_start'];?>" size="16" class="datetime" /></td></tr>
                                <tr><td><?php echo $entry_date_end;?></td><td><input type="text" name="csv_export[date_end]" value="<?php echo $csv_export['date_end'];?>" size="16" class="datetime" /></td></tr>
                            </table>
                    </td>
                    <td style="width: 50%;vertical-align: top;padding-left: 10px;">
                        <div style="margin-bottom: 6px;">
                            <a onclick="$('.ocl-ui-field-set').find(':checkbox:not(:checked)').parent().parent().parent().hide();"><?php echo $text_hide_all; ?></a>
                            /
                            <a onclick="$('.ocl-ui-field-set').find('tr').show();"><?php echo $text_show_all; ?></a>
                        </div>
                        <table class="ocl-ui-field-set" style="margin-bottom: 6px;">
                        <?php foreach( $csv_export['fields_set_data'] as $field ) { ?>
                            <tr id="row_<?php echo $field['uid']; ?>">
                                <td>
                                    <label class="checkbox inline" title="<?php echo $field['uid']; ?>">
                                        <input<?php if (array_key_exists($field['uid'], $csv_export['fields_set'])) echo ' checked="checked"';?> type="checkbox" name="csv_export[fields_set][<?php echo $field['uid']; ?>]" id="<?php echo $field['uid']; ?>" value="1" />
                                        <?php echo $fields_set_help[$field['uid']]; ?>
                                    </label>
                                </td>
                                <td><?php echo $field['uid']; ?></td>
                            </tr>
                        <?php } ?>
                        </table>
                        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all;?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all;?></a>
                    </td></tr>
                </table>
                </form>
                <div class="ocl-ui-buttons"><a onclick="$('#form_export').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_export; ?></a></div>
            </div><!-- END Export Customers Tab Content -->
    </div><!-- END Customers vtabs-content -->
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
<?php echo $js_datepicker_regional;?>
$('.datetime').datetimepicker({
    dateFormat: 'yy-mm-dd',
    timeFormat: 'hh:mm',
	changeMonth: true,
	changeYear: true
});
//--></script>
      <!-- END Customer Driver -->
  </div>
	<div id="ocl_copyright"><?php echo $ocl_copyright;?></div>
</div>

<script type="text/javascript"><!--
function setBackgroundColor(obj) {
    var row = '#row_' + $(obj).attr('id') + ' td';
    if($(obj).attr('checked') == 'checked'){
        $(row).addClass('selected');
    } else {
        $(row).removeClass('selected');
    }
}
function initFieldsSet() {
    $('.field_id').attr('checked', 'checked');
    $('.ocl-ui-field-set input[type=checkbox]').each(function() {
        setBackgroundColor(this);
    });
}

$(document).ready(initFieldsSet);

$('.ocl-ui-field-set input[type=checkbox]').change(function(){
    setBackgroundColor(this);
});

$(document).ready(function() {
    // Tabs functions
    <?php if(isset($tab_selected)) { ?>
    $('#submenu a').tabs();
    $('#submenu a').removeClass('selected');
    $('.ocl-ui-tab-content').hide();
    $("#link_<?php echo $tab_selected; ?>").addClass('selected');
    $("#<?php echo $tab_selected; ?>").show();
    <?php } ?>
    
    // Tooltype functions
    function simple_tooltip(target_items, name){
        $(target_items).each(function(i){
            $("body").append("<div class='"+name+"' id='"+name+i+"'><p>"+$(this).attr('title')+"</p></div>");
            var my_tooltip = $("#"+name+i);

            $(this).removeAttr("title").mouseover(function(){
                my_tooltip.css({opacity:1, display:"none"}).fadeIn(200);
            }).mousemove(function(kmouse){
                my_tooltip.css({left:kmouse.pageX+15, top:kmouse.pageY+15});
            }).mouseout(function(){my_tooltip.fadeOut(200);});
        });
    }

    simple_tooltip(".helper","ocl-ui-tooltip");
});
//--></script>
<?php echo $footer; ?>