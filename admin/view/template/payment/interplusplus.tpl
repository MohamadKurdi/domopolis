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
	 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
	  	<?php if (count($stores) > 0) { ?>
				<div class="stores" style="margin-right: 5px; display: inline-block;">
					Магазин:&nbsp;
					<select name="store_id" id="store_id" onchange="location='<?php echo str_replace("'", "\\'",$action_without_store); ?>'+'&store_id='+$(this).val()">
							<?php foreach ($stores as $key => $value) { ?>
									<option value="<?php echo $value['store_id'] ?>" <?php echo $store_id == $value['store_id'] ? 'selected="selected"' : '' ?>><?php echo $value['store_id'] ?> - <?php echo $value['name'] ?></option>
							<?php } ?>
					</select>
				</div>
			<?php } ?>
	  
	  <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
  <div class="content">   
      <table class="form">
      	
      	<tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_login; ?></td>
        <td><input type="text" name="interplusplus_login" value="<?php echo $interplusplus_login; ?>" />
          <br />
          <?php if ($error_login) { ?>
          <span class="error"><?php echo $error_login; ?></span>
          <?php } ?></td>
      	</tr>
      	<tr>
        <td><span class="required">*</span> <?php echo $entry_password1; ?></td>
        <td><input type="text" name="interplusplus_password1" value="<?php echo $interplusplus_password1; ?>" />
          <br />
          <?php if ($error_password1) { ?>
          <span class="error"><?php echo $error_password1; ?></span>
          <?php } ?></td>
      	</tr>
      	<tr>
        	<td><span class="required">*</span> Success URL:</td>
        	<td><?php echo $copy_success_url; ?></td>
      	</tr>
      	<tr>
        	<td><span class="required">*</span> Fail URL:</td>
        	<td><?php echo $copy_fail_url; ?></td>
      	</tr>
        <tr>
          <td><span class="required">*</span> Waiting URL:</td>
          <td><?php echo $copy_waiting_url; ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Result URL:</td>
          <td><?php echo $copy_result_url; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_name_tab; ?></td>
          <td><?php if ($interplusplus_name_attach) { ?>
            <input type="radio" name="interplusplus_name_attach" value="1" checked="checked" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_name_attach" value="0" />
            <?php echo $text_default; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_name_attach" value="1" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_name_attach" value="0" checked="checked" />
            <?php echo $text_default; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_name; ?></td>
          <td><textarea name="interplusplus_name" cols="50" rows="1"><?php echo isset($interplusplus_name) ? $interplusplus_name : ''; ?></textarea><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_style; ?></td>
          <td><?php if ($interplusplus_style) { ?>
            <input type="radio" name="interplusplus_style" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_style" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_style" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_style" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_button_later; ?></td>
          <td><?php if ($interplusplus_button_later) { ?>
            <input type="radio" name="interplusplus_button_later" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_button_later" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_button_later" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_button_later" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_success_alert_admin_tab; ?></td>
          <td><?php if ($interplusplus_success_alert_admin) { ?>
            <input type="radio" name="interplusplus_success_alert_admin" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_success_alert_admin" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_success_alert_admin" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_success_alert_admin" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_success_alert_customer_tab; ?></td>
          <td><?php if ($interplusplus_success_alert_customer) { ?>
            <input type="radio" name="interplusplus_success_alert_customer" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_success_alert_customer" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_success_alert_customer" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_success_alert_customer" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_instruction_tab; ?></td>
          <td><?php if ($interplusplus_instruction_attach) { ?>
            <input type="radio" name="interplusplus_instruction_attach" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_instruction_attach" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_instruction_attach" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_instruction_attach" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_instruction; ?></td>
          <td><textarea name="interplusplus_instruction" cols="50" rows="3"><?php echo isset($interplusplus_instruction) ? $interplusplus_instruction : ''; ?></textarea><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_mail_instruction_tab; ?></td>
          <td><?php if ($interplusplus_mail_instruction_attach) { ?>
            <input type="radio" name="interplusplus_mail_instruction_attach" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_mail_instruction_attach" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_mail_instruction_attach" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_mail_instruction_attach" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_mail_instruction; ?></td>
          <td><textarea name="interplusplus_mail_instruction" cols="50" rows="3"><?php echo isset($interplusplus_mail_instruction) ? $interplusplus_mail_instruction : ''; ?></textarea><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_success_comment_tab; ?></td>
          <td><?php if ($interplusplus_success_comment_attach) { ?>
            <input type="radio" name="interplusplus_success_comment_attach" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_success_comment_attach" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_success_comment_attach" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="interplusplus_success_comment_attach" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_success_comment; ?></td>
          <td><textarea name="interplusplus_success_comment" cols="50" rows="3"><?php echo isset($interplusplus_success_comment) ? $interplusplus_success_comment : ''; ?></textarea><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_success_page_tab; ?></td>
          <td><?php if ($interplusplus_success_page_text_attach) { ?>
            <input type="radio" name="interplusplus_success_page_text_attach" value="1" checked="checked" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_success_page_text_attach" value="0" />
            <?php echo $text_default; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_success_page_text_attach" value="1" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_success_page_text_attach" value="0" checked="checked" />
            <?php echo $text_default; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_success_page_text; ?></td>
          <td><textarea name="interplusplus_success_page_text" cols="50" rows="3"><?php echo isset($interplusplus_success_page_text) ? $interplusplus_success_page_text : ''; ?></textarea><br /></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_fail_page_tab; ?></td>
          <td><?php if ($interplusplus_fail_page_text_attach) { ?>
            <input type="radio" name="interplusplus_fail_page_text_attach" value="1" checked="checked" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_fail_page_text_attach" value="0" />
            <?php echo $text_default; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_fail_page_text_attach" value="1" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_fail_page_text_attach" value="0" checked="checked" />
            <?php echo $text_default; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_fail_page_text; ?></td>
          <td><textarea name="interplusplus_fail_page_text" cols="50" rows="3"><?php echo isset($interplusplus_fail_page_text) ? $interplusplus_fail_page_text : ''; ?></textarea><br /></td>
        </tr>
         <tr>
          <td><?php echo $entry_interplusplus_waiting_page_tab; ?></td>
          <td><?php if ($interplusplus_waiting_page_text_attach) { ?>
            <input type="radio" name="interplusplus_waiting_page_text_attach" value="1" checked="checked" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_waiting_page_text_attach" value="0" />
            <?php echo $text_default; ?>
            <?php } else { ?>
            <input type="radio" name="interplusplus_waiting_page_text_attach" value="1" />
            <?php echo $text_my; ?>
            <input type="radio" name="interplusplus_waiting_page_text_attach" value="0" checked="checked" />
            <?php echo $text_default; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_interplusplus_waiting_page_text; ?></td>
          <td><textarea name="interplusplus_waiting_page_text" cols="50" rows="3"><?php echo isset($interplusplus_waiting_page_text) ? $interplusplus_waiting_page_text : ''; ?></textarea><br /></td>
        </tr>
        <tr>
        <td><?php echo $entry_on_status; ?></td>
        <td><select name="interplusplus_on_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $interplusplus_on_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      	</tr>
      	<tr>
        <td><?php echo $entry_order_status; ?></td>
        <td><select name="interplusplus_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $interplusplus_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      	</tr>
      	<tr>
        <td><?php echo $entry_geo_zone; ?></td>
        <td><select name="interplusplus_geo_zone_id">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $interplusplus_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      	</tr>
      	<tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="interplusplus_status">
            <?php if ($interplusplus_status) { ?>
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
          <td><input type="text" name="interplusplus_sort_order" value="<?php echo $interplusplus_sort_order; ?>" size="1" /></td>
        </tr>
      </table>

        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 