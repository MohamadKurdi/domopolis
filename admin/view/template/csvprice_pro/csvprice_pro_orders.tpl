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
      
      <!-- BEGIN Orders Driver -->
		<div id="submenu"class="ocl-ui-submenu">
            <ul>
            	<li><a href="#tab_setting" id="link_tab_setting"><?php echo $tab_setting; ?></a></li>
				<li><a href="#tab_export" id="link_tab_export"><?php echo $tab_export; ?></a></li>
            </ul>
        </div>
        
        <!-- BEGIN Orderss vtabs-content -->
        <div class="ocl-ui-body-content">
        
	        <div id="tab_setting" class="ocl-ui-tab-content">
	        	<form action="<?php echo $main_url['Orders'];?>" method="post" id="form_orders_setting" name="form_orders_setting" enctype="multipart/form-data" class="ocl-ui-form-horizontal">

        			<table class="ocl-ui-form">
                        <tbody>
                        	<tr>
                                <td><?php echo $entry_file_encoding; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_file_encoding; ?>" /></td>
                                <td><select name="csv_setting[file_encoding]" class="span140">
                                	<option value="UTF-8" <?php if ( $csv_setting['file_encoding'] == 'UTF-8' ) echo 'selected'; ?>>UTF-8</option>
                                	<option value="WINDOWS-1251" <?php if ( $csv_setting['file_encoding'] == 'WINDOWS-1251' ) echo 'selected'; ?>>Windows-1251</option>
                                	</select>
                                </td>
                            </tr>
                            <tr>
                            	<td><?php echo $entry_csv_delimiter; ?></td>
                            	<td><select class="span80" name="csv_setting[delimiter]">
									<option value=";"<?php if($csv_setting['delimiter'] == ';'){echo ' selected="selected"';}?>> ; </option>
									<option value=","<?php if($csv_setting['delimiter'] == ','){echo ' selected="selected"';}?>> , </option>
									<option value="TAB"<?php if($csv_setting['delimiter'] == 'TAB'){echo ' selected="selected"';}?>> TAB </option>
									<option value="|"<?php if($csv_setting['delimiter'] == '|'){echo ' selected="selected"';}?>> | </option>
								</select></td>
                            </tr>
                            <tr><td><?php echo $entry_csv_delimiter_text; ?></td>
                            	<td><input type="text" class="span60" name="csv_setting[delimiter_text]" value='<?php echo (isset($csv_setting['delimiter_text'])) ? $csv_setting['delimiter_text'] : '';?>' /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_include_csv_title; ?></td>
                                <td>
                                    <select class="span80" name="csv_setting[csv_title]">
                                        <?php if (isset($csv_setting['csv_title']) && $csv_setting['csv_title'] == 1) { ?>
                                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                            <option value="0"><?php echo $text_no; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_yes; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
						</tbody>
					</table>

					<div style="margin-top: 10px;">
						<a onclick="$('.ocl-ui-field-set').find(':checkbox').attr('checked', true);initFieldsSet();"><?php echo $text_select_all; ?></a> / <a onclick="$('.ocl-ui-field-set').find(':checkbox').attr('checked', false);initFieldsSet();"><?php echo $text_unselect_all; ?></a>
					</div>
					<table class="ocl-ui-field-set ocl-ui-first-child" style="margin-top: 10px;">
                        <tbody class="ocl-ui-sortable">
                        	<?php foreach( $csv_setting['fields_set'] as $key => $value ) { ?>
								<tr>
									<td><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
								<td>
									<input type="hidden" name="csv_setting[fields_set][<?php echo $key; ?>][status]" value="0">
									<label><input class="fields_set" <?php	if ($value['status']) echo 'checked="checked"';?> type="checkbox" name="csv_setting[fields_set][<?php echo $key; ?>][status]" value="1"><?php echo $key; ?></label>
								</td>
								<td><span class="help-inline"><?php echo $fields_set_help[$key]; ?></span></td>
								<td><input type="text" name="csv_setting[fields_set][<?php echo $key; ?>][caption]" value="<?php echo $csv_setting['fields_set'][$key]['caption']; ?>"></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div style="margin-top: 10px;margin-bottom: 20px;">
						<a onclick="$('.ocl-ui-field-set').find(':checkbox').attr('checked', true);initFieldsSet();"><?php echo $text_select_all; ?></a> / <a onclick="$('.ocl-ui-field-set').find(':checkbox').attr('checked', false);initFieldsSet();"><?php echo $text_unselect_all; ?></a>
					</div>
			</form>
			<div class="ocl-ui-buttons left"><a onclick="$('#form_orders_setting').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_save;?></a></div>
			</div>

        
        <!-- BEGIN Orderss Tab Content -->
            <div id="tab_export" class="ocl-ui-tab-content">
                <form action="<?php echo $action_export; ?>" method="post" id="form_export" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
                	<table class="ocl-ui-form">
                        <tbody>
                        	<tr>
                        		<td><?php echo $entry_export_order_id; ?></td>
                        		<td><input class="span200" type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
                        	</tr>
                        	<tr>
                        		<td><?php echo $entry_export_customer; ?></td>
                        		<td><input class="span200" type="text" name="filter_customer" value="" /></td>
                        	</tr>
                        	<tr>
                        		<td><?php echo $entry_order_status; ?></td>
                        		<td><select  class="span200" name="filter_order_status_id">
									  <option value="*"></option>
									  <?php if ($filter_order_status_id == '0') { ?>
									  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
									  <?php } else { ?>
									  <option value="0"><?php echo $text_missing; ?></option>
									  <?php } ?>
									  <?php foreach ($order_statuses as $order_status) { ?>
									  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
									  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									  <?php } else { ?>
									  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									  <?php } ?>
									  <?php } ?>
									</select>
								</td>
                        	</tr>
                        	<tr>
                        		<td><?php echo $entry_export_date_added; ?></td>
                        		<td><input type="text" name="filter_date_added_start" value="<?php echo $filter_date_added_start; ?>" class="date span140" /> - <input type="text" name="filter_date_added_end" value="<?php echo $filter_date_added_end; ?>" class="date span140" /></td>
                        	</tr>
                        	<tr>
                        		<td><?php echo $entry_export_date_modified; ?></td>
                        		<td>
                        			<input type="text" name="filter_date_modified_start" value="<?php echo $filter_date_modified_start; ?>" class="date span140" /> - <input type="text" name="filter_date_modified_end" value="<?php echo $filter_date_modified_end; ?>" class="date span140" />
                        		</td>
                        	</tr>
                        	<tr>
                        		<td><?php echo $entry_export_total_sum; ?></td>
                        		<td>
                        			<select class="span60" name="filter_total_prefix">
										<option value="1"> = </option>
										<option value="2"> >= </option>
										<option value="3"> <= </option>
										<option value="4"> <> </option>
									</select>
									<input class="span180" type="text" name="filter_total_sum" value="<?php echo $filter_total_sum; ?>" style="text-align: right;" />
                        		</td>
                        	</tr>
                        </tbody>
					</table>
                </form>
                <div class="ocl-ui-buttons"><a onclick="$('#form_export').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_export; ?></a></div>
            </div><!-- END Export Orderss Tab Content -->
            

		</div><!-- END Orderss vtabs-content -->
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
<?php echo $js_datepicker_regional;?>
$(document).ready(function() {
	$('.date').datetimepicker({
	    dateFormat: 'yy-mm-dd',
	    timeFormat: 'hh:mm',
		changeMonth: true,
		changeYear: true
	});
});
//--></script>
      <!-- END Orders Driver -->
  </div>
	<div id="ocl_copyright"><?php echo $ocl_copyright;?></div>
</div>

<script type="text/javascript"><!--
function setBackgroundColor(obj) {
    var row = obj.parent().parent().parent();
    if(obj.attr('checked') == 'checked'){
        row.find('td').addClass('selected');
    } else {
        row.find('td').removeClass('selected');
    }
}
function initFieldsSet() {
    $('.field_id').attr('checked', 'checked');
    $('.ocl-ui-field-set input[type=checkbox]').each(function() {
        setBackgroundColor($(this));
    });
}

$(document).ready(initFieldsSet);

var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};

$(document).ready(function() {
	$('.ocl-ui-field-set input[type=checkbox]').change(function(){
	    setBackgroundColor($(this));
	});

	$(".ocl-ui-sortable").sortable({
    	helper: fixHelper,
    	cursor: 'move',
	    opacity: 0.6,
	    placeholder: 'ui-placeholder',
	    'start': function (event, ui) {
	        ui.placeholder.html("<td colspan='4'>&nbsp;</td>")
	    }
	});//.disableSelection();
	
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
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'filter_customer\']').catcomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.name,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_customer\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>