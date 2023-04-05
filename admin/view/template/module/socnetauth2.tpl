<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
	  
	          <?php if (count($stores) > 0) { ?>
			  <style>
			  .stores {
    margin-right: 5px;
    display: inline-block;
}
			  </style>
        <div class="stores">
            Магазин:
            <select name="store_id" id="store_id" onchange="location='<?php echo str_replace("'", "\\'",$action_without_store); ?>'+'&store_id='+$(this).val()">
                <?php foreach ($stores as $key => $value) { ?>
                    <option value="<?php echo $value['store_id'] ?>" <?php echo $store_id == $value['store_id'] ? 'selected="selected"' : '' ?>><?php echo $value['store_id'] ?> - <?php echo $value['name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>
	  
	  <a onclick="document.getElementById('stay_field').value='0'; $('#form').submit();" class="button"
	  ><span><?php echo $button_save_and_go; ?></span></a>
	  <a onclick="set_tab(); document.getElementById('stay_field').value='1'; $('#form').submit();" class="button"
	  ><span><?php echo $button_save_and_stay; ?></span></a>
	  
	  <a onclick="location = '<?php echo $cancel; ?>';" class="button"
	  ><span><?php echo $button_cancel; ?></span></a>
	  
	  </div>
    </div>	  
	  <style>
	  .clist 
	  {
		border-top:  1px #ccc solid;
		border-left:  1px #ccc solid;
	  }
	  
	  .clist td
	  {
		padding: 5px;
		border-right: 1px #ccc solid;
		border-bottom: 1px #ccc solid;
	  }
	  
	  .plus
	  {
		background: green;
		text-align: center;
	  }
	  
	  .minus
	  {
		background: #F58C6C;
		text-align: center;
	  }
	  
	  .vopros
	  {
		text-align: center;
	  }
	  </style>
	  
	<script>
		function set_tab()
		{
			if( $('#link-tab-general').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-general';
			}
			
			if( $('#link-tab-vkontakte').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-vkontakte';
			}
			
			if( $('#link-tab-facebook').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-facebook';
			}
			
			if( $('#link-tab-twitter').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-twitter';
			}
			
			if( $('#link-tab-odnoklassniki').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-odnoklassniki';
			}
			/* start metka: a1 */
			if( $('#link-tab-gmail').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-gmail';
			}
			
			if( $('#link-tab-mailru').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-mailru';
			}
			/* end metka: a1 */
			
			if( $('#link-tab-dobor').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-dobor';
			}
			
			if( $('#link-tab-widget').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-widget';
			}
			
			if( $('#link-tab-popup').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-popup';
			}
			
			if( $('#link-tab-design').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-design';
			}
			
			if( $('#link-tab-support').attr('class') == 'selected' )
			{
				document.getElementById('hiddentab').value = 'link-tab-support';
			}
		}
		
	$.fn.tabs = function() {
	var selector = this;
	
	this.each(function() {
		var obj = $(this); 
		
		$(obj.attr('href')).hide();
		
		$(obj).click(function() {
			$(selector).removeClass('selected');
			
			$(selector).each(function(i, element) {
				$($(element).attr('href')).hide();
			});
			
			$(this).addClass('selected');
			
			$($(this).attr('href')).show();
			
			return false;
		});
	});

	$(this).show();
	
	$(this).first().click();
};
	</script>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="stay_field" name="stay" value="1">
		<input type="hidden" id="hiddentab" name="tab" value="<?php echo $tab; ?>">
		
		<div id="tabs" class="htabs">
			<a href="#tab-general" id="link-tab-general"><?php echo $tab_general; ?></a>
			
			
		    <a href="#tab-vkontakte" id="link-tab-vkontakte"><?php echo $tab_vkontakte; ?></a>
		    <a href="#tab-facebook" id="link-tab-facebook"><?php echo $tab_facebook; ?></a>
		    <a href="#tab-twitter" id="link-tab-twitter"><?php echo $tab_twitter; ?></a>
		    <a href="#tab-odnoklassniki" id="link-tab-odnoklassniki"><?php echo $tab_odnoklassniki; ?></a>
			<?php /* start metka: a1 */ ?>
		    <a href="#tab-gmail" id="link-tab-gmail"><?php echo $tab_gmail; ?></a>
		    <a href="#tab-mailru" id="link-tab-gmail"><?php echo $tab_mailru; ?></a>
			<?php /* end metka: a1 */ ?>
			
			<a href="#tab-dobor" id="link-tab-dobor"><?php echo $tab_dobor; ?></a>
			<a href="#tab-widget"  id="link-tab-widget"><?php echo $tab_widget; ?></a>
			<a href="#tab-popup"  id="link-tab-popup"><?php echo $tab_popup; ?></a>
			<a href="#tab-design"   id="link-tab-design"><?php echo $tab_design; ?></a>
			<a href="#tab-support" id="link-tab-support"><?php echo $tab_support; ?></a>
		<div class="clr"></div>
		</div>
      
	  <div class="th_style"></div>
	  <div id="tab-general">
	  
        <table class="form">
		<tr>
			<td><?php echo $entry_version; ?></td>
			<td>2.17</td>
		</tr>
		<tr>
			<td><?php echo $entry_status; ?></td>
			<td><select name="socnetauth2_status">
                  <?php if ( $socnetauth2_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
		</tr>
		<tr>
			<td><?php echo $entry_format; ?></td>
			<td>
				<style>
				.format_table td
				{
					border-bottom: 1px #ccc solid;
					padding: 3px;
				}
				</style>
				<table class="format_table" cellpadding=0 cellspacing=0>
				<tr>
					<td></td>
					<td><b><?php echo $text_format_kvadrat; ?></b></td>
					<td><b><?php echo $text_format_bline; ?></b></td>
					<td><b><?php echo $text_format_lline; ?></b></td>
				</tr>
				
				<tr>
					<td></td>
					<td valign=top><img src="view/image/socnetauth2/kvadrat.gif" style="width: 250px;"></td>
					<td valign=top><img src="view/image/socnetauth2/bline.gif" style="width: 250px;"></td>
					<td valign=top><img src="view/image/socnetauth2/lline.gif" style="width: 250px;"></td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_account_page; ?></b>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_account_format" 
							value="kvadrat" 
							id="socnetauth2_account_format_kvadrat"
							<?php if( $socnetauth2_account_format=='kvadrat' ) { ?> checked <?php } ?>
							><label for="socnetauth2_account_format_kvadrat"
							><?php echo $text_format_kvadrat; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_account_format" 
							value="bline" 
							id="socnetauth2_account_format_bline"
							<?php if( $socnetauth2_account_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_account_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_account_format" 
							value="lline" 
							id="socnetauth2_account_format_lline"
							<?php if( $socnetauth2_account_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_account_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_checkout_page; ?></b>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_checkout_format" 
							value="kvadrat" 
							id="socnetauth2_checkout_format_kvadrat"
							<?php if( $socnetauth2_checkout_format=='kvadrat' ) { ?> checked <?php } ?>
							><label for="socnetauth2_checkout_format_kvadrat"
							><?php echo $text_format_kvadrat; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_checkout_format" 
							value="bline" 
							id="socnetauth2_checkout_format_bline"
							<?php if( $socnetauth2_checkout_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_checkout_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_checkout_format" 
							value="lline" 
							id="socnetauth2_checkout_format_lline"
							<?php if( $socnetauth2_checkout_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_checkout_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_simple_page; ?></b>
					</td>
					<td>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simple_format" 
							value="bline" 
							id="socnetauth2_simple_format_bline"
							<?php if( $socnetauth2_simple_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simple_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simple_format" 
							value="lline" 
							id="socnetauth2_simple_format_lline"
							<?php if( $socnetauth2_simple_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simple_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_reg_page; ?></b>
					</td>
					<td>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_reg_format" 
							value="bline" 
							id="socnetauth2_reg_format_bline"
							<?php if( $socnetauth2_reg_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_reg_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_reg_format" 
							value="lline" 
							id="socnetauth2_reg_format_lline"
							<?php if( $socnetauth2_reg_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_reg_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo $text_format_simplereg_page; ?></b>
					</td>
					<td>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simplereg_format" 
							value="bline" 
							id="socnetauth2_simplereg_format_bline"
							<?php if( $socnetauth2_simplereg_format=='bline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simplereg_format_bline"
							><?php echo $text_format_bline; ?></label>
					</td>
					<td>
						<input type="radio" 
							name="socnetauth2_simplereg_format" 
							value="lline" 
							id="socnetauth2_simplereg_format_lline"
							<?php if( $socnetauth2_simplereg_format=='lline' ) { ?> checked <?php } ?>
							><label for="socnetauth2_simplereg_format_lline"
							><?php echo $text_format_lline; ?></label>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<div id="showtype_block" style="display: none;">
		<table class="form">
		<tr>
			<td><?php echo $entry_showtype; ?></td>
			<td>
				<select name="socnetauth2_showtype">
					<option value="window"
					<?php if( $socnetauth2_showtype=='window' ) { ?> selected <?php } ?>
					><?php echo $text_showtype_window; ?></option>
					<option value="redirect"
					<?php if( $socnetauth2_showtype=='redirect' ) { ?> selected <?php } ?>
					><?php echo $text_showtype_redirect; ?></option>
				</select>
				<div><i><?php echo $text_showtype_notice; ?></i></div>
			</td>
		</tr>
		</table>
		</div>
		
		<table class="form">
		<tr>
			<td><?php echo $entry_label; ?></td>
			<td>
			
			<?php foreach ($languages as $language) { ?>
			<p>
			<input type="text" name="socnetauth2_label[<?php echo $language['language_id']; ?>]" value="<?php if( !empty($socnetauth2_label[ $language['language_id'] ]) ) echo $socnetauth2_label[ $language['language_id'] ]; ?>" style="width: 300px;">&nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
			</p>
			<?php } ?>
			
			</td>
		</tr>
		<tr>
			<td><?php echo $entry_save_to_addr; ?></td>
			<td>
				<select name="socnetauth2_save_to_addr">
					<option value="customer_addr"
					<?php if( $socnetauth2_save_to_addr=='customer_addr' ) { ?> selected <?php } ?>
					><?php echo $text_customer_addr; ?></option>
					<option value="customer_only"
					<?php if( $socnetauth2_save_to_addr=='customer_only' ) { ?> selected <?php } ?>
					><?php echo $text_customer_only; ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php echo $entry_shop_folder; ?></td>
			<td>
				<input type="text" name="socnetauth2_shop_folder" 
						value="<?php echo $socnetauth2_shop_folder; ?>" />
			</td>
		</tr>
		
		<tr>
			<td><?php echo $entry_email_auth; ?></td>
			<td>
				<p><input type="radio" name="socnetauth2_email_auth" value="none" 
					   id="socnetauth2_email_auth_none"
					   <?php if( $socnetauth2_email_auth == 'none' ) { ?> checked <?php } ?>
					   
					   >
					   <label for="socnetauth2_email_auth_none">
						<?php echo $entry_email_auth_none; ?>
					   </label></p>
				<p><input type="radio" name="socnetauth2_email_auth" value="confirm" 
					   id="socnetauth2_email_auth_confirm"
					   <?php if( $socnetauth2_email_auth == 'confirm' ) { ?> checked <?php } ?>
					   >
					   <label for="socnetauth2_email_auth_confirm">
						<?php echo $entry_email_auth_confirm; ?>
					   </label></p>
				<p><input type="radio" name="socnetauth2_email_auth" value="noconfirm" 
					   id="socnetauth2_email_auth_noconfirm"
					   <?php if( $socnetauth2_email_auth == 'noconfirm' ) { ?> checked <?php } ?>
					   >
					   <label for="socnetauth2_email_auth_noconfirm">
						<?php echo $entry_email_auth_noconfirm; ?>
					   </label></p>
			
			</td>
		</tr>
		
			
			
			<?php /* start kin update: r1 */ ?>
			
            <tr>
				<td><?php echo $entry_vkontakte_retargeting; ?></td>
				<td><a class="button" href="<?php echo $vkontakte_retargeting; ?>"><i class="fa fa-download"></i></a></td>
			</tr>
            <tr>
				<td><?php echo $entry_facebook_retargeting; ?></td>
				<td><a class="button" href="<?php echo $facebook_retargeting; ?>"><i class="fa fa-download"></i></a></td>
			</tr>
			
			
			<?php /* end kin update: r1 */ ?>
		<tr>
			<td colspan=2><b><?php echo $entry_admin_header; ?></b></td>
		</tr>
		<tr>
			<td><?php echo $entry_admin_customer; ?></td>
			<td>
			<input id="socnetauth2_admin_customer" class="checkbox" type="checkbox" name="socnetauth2_admin_customer" value="1" <?php if($socnetauth2_admin_customer) { ?> checked <?php } ?>>
			<label for="socnetauth2_admin_customer"></label>
			</td>
		</tr>		
		<tr>
			<td><?php echo $entry_admin_customer_list; ?></td>
			<td>
			<input id="socnetauth2_admin_customer_list" class="checkbox" type="checkbox" name="socnetauth2_admin_customer_list" value="1" <?php if($socnetauth2_admin_customer_list) { ?> checked <?php } ?>>
			<label for="socnetauth2_admin_customer_list"></label>
			</td>
		</tr>		
		<tr>
			<td><?php echo $entry_admin_order; ?></td>
			<td>
			<input id="socnetauth2_admin_order" class="checkbox" type="checkbox" name="socnetauth2_admin_order" value="1" <?php if($socnetauth2_admin_order) { ?> checked <?php } ?>>
				<label for="socnetauth2_admin_order"></label>
				</td>
		</tr>		
		<tr>
			<td><?php echo $entry_admin_order_list; ?></td>
			<td>
			<input id="socnetauth2_admin_order_list" class="checkbox" type="checkbox" name="socnetauth2_admin_order_list" value="1" <?php if($socnetauth2_admin_order_list) { ?> checked <?php } ?>>
			<label for="socnetauth2_admin_order_list"></label>
			</td>
		</tr>		
		
		</table>
	  </div>
	  
	  
	  
	  
	  <div id="tab-vkontakte">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_vkontakte_status; ?></td>
				<td>
				<select name="socnetauth2_vkontakte_status">
                  <?php if ( $socnetauth2_vkontakte_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_vkontakte_appid; ?></td>
				<td><input type="text" name="socnetauth2_vkontakte_appid" style="width: 300px;" value="<?php echo $socnetauth2_vkontakte_appid; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_vkontakte_appsecret; ?></td>
				<td><input type="text" name="socnetauth2_vkontakte_appsecret" style="width: 300px;" value="<?php echo $socnetauth2_vkontakte_appsecret; ?>" /></td>
			</tr>
			<?php /* start kin update: n1 */ ?>
            <tr>
				<td><?php echo $entry_vkontakte_customer_group_id; ?></td>
				<td><select name="socnetauth2_vkontakte_customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_vkontakte_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
			</tr>
			<?php /* end kin update: n1 */ ?>
		</table>
		
	
		
	  </div>
	  <div id="tab-facebook">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_facebook_status; ?></td>
				<td>
				<select name="socnetauth2_facebook_status">
                  <?php if ( $socnetauth2_facebook_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_facebook_appid; ?></td>
				<td><input type="text" name="socnetauth2_facebook_appid" style="width: 300px;" value="<?php echo $socnetauth2_facebook_appid; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_facebook_appsecret; ?></td>
				<td><input type="text" name="socnetauth2_facebook_appsecret" style="width: 300px;" value="<?php echo $socnetauth2_facebook_appsecret; ?>" /></td>
			</tr>
			<?php /* start kin update: n1 */ ?>
            <tr>
				<td><?php echo $entry_facebook_customer_group_id; ?></td>
				<td><select name="socnetauth2_facebook_customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_facebook_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
			</tr>
			<?php /* end kin update: n1 */ ?>
			
		</table>
	  

	  </div>
	  <div id="tab-twitter">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_twitter_status; ?></td>
				<td>
				<select name="socnetauth2_twitter_status">
                  <?php if ( $socnetauth2_twitter_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_twitter_consumer_key; ?></td>
				<td><input type="text" name="socnetauth2_twitter_consumer_key" style="width: 300px;" value="<?php echo $socnetauth2_twitter_consumer_key; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_twitter_consumer_secret; ?></td>
				<td><input type="text" name="socnetauth2_twitter_consumer_secret" style="width: 300px;" value="<?php echo $socnetauth2_twitter_consumer_secret; ?>" /></td>
			</tr>
			<?php /* start kin update: n1 */ ?>
            <tr>
				<td><?php echo $entry_twitter_customer_group_id; ?></td>
				<td><select name="socnetauth2_twitter_customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_twitter_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
			</tr>
			<?php /* end kin update: n1 */ ?>
		</table>

	  </div>
	  <div id="tab-odnoklassniki">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_odnoklassniki_status; ?></td>
				<td>
				<select name="socnetauth2_odnoklassniki_status">
                  <?php if ( $socnetauth2_odnoklassniki_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_odnoklassniki_application_id; ?></td>
				<td><input type="text" name="socnetauth2_odnoklassniki_application_id" style="width: 300px;" value="<?php echo $socnetauth2_odnoklassniki_application_id; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_odnoklassniki_public_key; ?></td>
				<td><input type="text" name="socnetauth2_odnoklassniki_public_key" style="width: 300px;" value="<?php echo $socnetauth2_odnoklassniki_public_key; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_odnoklassniki_secret_key; ?></td>
				<td><input type="text" name="socnetauth2_odnoklassniki_secret_key" style="width: 300px;" value="<?php echo $socnetauth2_odnoklassniki_secret_key; ?>" /></td>
			</tr>
			<?php /* start kin update: n1 */ ?>
            <tr>
				<td><?php echo $entry_odnoklassniki_customer_group_id; ?></td>
				<td><select name="socnetauth2_odnoklassniki_customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_odnoklassniki_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
			</tr>
			<?php /* end kin update: n1 */ ?>
			
		</table>
	  

	  </div>
	  
	  <?php /* start metka: a1 */ ?>
	  <div id="tab-gmail">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_gmail_status; ?></td>
				<td>
				<select name="socnetauth2_gmail_status">
                  <?php if ( $socnetauth2_gmail_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_gmail_client_id; ?></td>
				<td><input type="text" name="socnetauth2_gmail_client_id" style="width: 300px;" 
				value="<?php echo $socnetauth2_gmail_client_id; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_gmail_client_secret; ?></td>
				<td><input type="text" name="socnetauth2_gmail_client_secret"
				style="width: 300px;" value="<?php echo $socnetauth2_gmail_client_secret; ?>" /></td>
			</tr>
			<?php /* start kin update: n1 */ ?>
            <tr>
				<td><?php echo $entry_gmail_customer_group_id; ?></td>
				<td><select name="socnetauth2_gmail_customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_gmail_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
			</tr>
			<?php /* end kin update: n1 */ ?>
			
		</table>
	  

	  </div>
	  
	  
	  <div id="tab-mailru">
        <table class="form">
          <tbody>
            <tr>
				<td><?php echo $entry_mailru_status; ?></td>
				<td>
				<select name="socnetauth2_mailru_status">
                  <?php if ( $socnetauth2_mailru_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
				</td>
			</tr>
            <tr>
				<td><?php echo $entry_mailru_id; ?></td>
				<td><input type="text" name="socnetauth2_mailru_id" style="width: 300px;" 
				value="<?php echo $socnetauth2_mailru_id; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_mailru_private; ?></td>
				<td><input type="text" name="socnetauth2_mailru_private"
				style="width: 300px;" value="<?php echo $socnetauth2_mailru_private; ?>" /></td>
			</tr>
            <tr>
				<td><?php echo $entry_mailru_secret; ?></td>
				<td><input type="text" name="socnetauth2_mailru_secret"
				style="width: 300px;" value="<?php echo $socnetauth2_mailru_secret; ?>" /></td>
			</tr>
			<?php /* start kin update: n1 */ ?>
            <tr>
				<td><?php echo $entry_mailru_customer_group_id; ?></td>
				<td><select name="socnetauth2_mailru_customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $socnetauth2_mailru_customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
			</tr>
			<?php /* end kin update: n1 */ ?>
			
		</table>

		
	  </div>
	  <?php /* end metka: a1 */ ?>
	  
	  <div id="tab-dobor">
        <table class="form">
		<tr> 
			<td colspan=2><b><?php echo $entry_confirm_data; ?></b></td>
		</tr>
		<tr>
			<td><?php echo $entry_dobortype; ?></td>
			<td>
				<select name="socnetauth2_dobortype">
					<option value="one"
						<?php if( $socnetauth2_dobortype == 'one' ) { ?> selected <?php } ?>
					><?php   echo $entry_dobortype_one; ?></option>
					<option value="every"
						<?php if( $socnetauth2_dobortype == 'every' ) { ?> selected <?php } ?>
					><?php echo $entry_dobortype_every; ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_firstname; ?></td>
			<td><select name="socnetauth2_confirm_firstname_status">
                  <?php if ( $socnetauth2_confirm_firstname_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_firstname_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_firstname_required" value="1"
				id="socnetauth2_confirm_firstname_required"
				<?php if( $socnetauth2_confirm_firstname_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_firstname_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_lastname; ?></td>
			<td><select name="socnetauth2_confirm_lastname_status">
                <?php if ( $socnetauth2_confirm_lastname_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_lastname_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_lastname_required" value="1"
				id="socnetauth2_confirm_lastname_required"
				<?php if( $socnetauth2_confirm_lastname_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_lastname_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_email; ?></td>
			<td><select name="socnetauth2_confirm_email_status">
                <?php if ( $socnetauth2_confirm_email_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_email_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_email_required" value="1"
				id="socnetauth2_confirm_email_required"
				<?php if( $socnetauth2_confirm_email_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_email_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_phone; ?></td>
			<td><select name="socnetauth2_confirm_telephone_status">
                 <?php if ( $socnetauth2_confirm_telephone_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_telephone_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_telephone_required" value="1"
				id="socnetauth2_confirm_telephone_required"
				<?php if( $socnetauth2_confirm_telephone_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_telephone_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_company; ?></td>
			<td><select name="socnetauth2_confirm_company_status">
                 <?php if ( $socnetauth2_confirm_company_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_company_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_company_required" value="1"
				id="socnetauth2_confirm_company_required"
				<?php if( $socnetauth2_confirm_company_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_company_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_postcode; ?></td>
			<td><select name="socnetauth2_confirm_postcode_status">
                 <?php if ( $socnetauth2_confirm_postcode_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_postcode_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_postcode_required" value="1"
				id="socnetauth2_confirm_postcode_required"
				<?php if( $socnetauth2_confirm_postcode_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_postcode_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_country; ?></td>
			<td><select name="socnetauth2_confirm_country_status">
                 <?php if ( $socnetauth2_confirm_country_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_country_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_country_required" value="1"
				id="socnetauth2_confirm_country_required"
				<?php if( $socnetauth2_confirm_country_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_country_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_zone; ?></td>
			<td><select name="socnetauth2_confirm_zone_status">
                 <?php if ( $socnetauth2_confirm_zone_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_zone_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_zone_required" value="1"
				id="socnetauth2_confirm_zone_required"
				<?php if( $socnetauth2_confirm_zone_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_zone_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_city; ?></td>
			<td><select name="socnetauth2_confirm_city_status">
                 <?php if ( $socnetauth2_confirm_city_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_city_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_city_required" value="1"
				id="socnetauth2_confirm_city_required"
				<?php if( $socnetauth2_confirm_city_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_city_required"><?php echo $text_required;?></label></td>
		</tr>
		<tr>
			<td><?php echo $entry_confirm_address_1; ?></td>
			<td><select name="socnetauth2_confirm_address_1_status">
                 <?php if ( $socnetauth2_confirm_address_1_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $socnetauth2_confirm_address_1_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select>
				<input class="checkbox" type="checkbox" name="socnetauth2_confirm_address_1_required" value="1"
				id="socnetauth2_confirm_address_1_required"
				<?php if( $socnetauth2_confirm_address_1_required ) { ?>
				checked
				<?php } ?>
				><label for="socnetauth2_confirm_address_1_required"><?php echo $text_required;?></label>
			</td>
		</tr>
		
		</table>
	  </div>
	  <!-- /////////////////////////////////////////////////////////////////////////////// -->
	  <div id="tab-widget">
	  
	  <table class="form">
	  <tr>
		<td>
			<?php echo $entry_widget_name; ?>
		</td>
		<td>
	  		<?php foreach ($languages as $language) { ?>
			<p>
			<input type="text" name="socnetauth2_widget_name[<?php echo $language['language_id']; ?>]" value="<?php if( !empty($socnetauth2_widget_name[ $language['language_id'] ]) ) echo $socnetauth2_widget_name[ $language['language_id'] ]; ?>" style="width: 300px;">&nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
			</p>
			<?php } ?>
		</td>
	   </tr
	   <tr>
			<td><?php echo $entry_widget_format; ?></td>
			<td>
				<table>
				<tr>
					<td width=200><input type="radio" name="socnetauth2_widget_format" 
					value="kvadrat" 
					onClick="show_widget_showtype_block()"
					id="socnetauth2_widget_format_kvadrat"
					<?php if( $socnetauth2_widget_format=='kvadrat' ) { ?> checked <?php } ?> 
					><label for="socnetauth2_widget_format_kvadrat"><?php echo $text_format_kvadrat; ?></label></td>
					
					<td width=200><input type="radio" name="socnetauth2_widget_format" 
					onClick="show_widget_showtype_block()"
					value="lline" id="socnetauth2_widget_format_lline"
					<?php if( $socnetauth2_widget_format=='lline' ) { ?> checked <?php } ?>
					><label for="socnetauth2_widget_format_lline"><?php echo $text_format_lline; ?></label></td>
				</tr>
				<tr>
					<td valign=top><img src="view/image/socnetauth2/kvadrat.gif"></td>
					<td valign=top><img src="view/image/socnetauth2/lline.gif"></td>
				</tr>
				</table>
			</td>
		</tr>
		
		<?php /*
		<tr>
			<td><?php echo $entry_widget_showtype; ?></td>
			<td>
				<select name="socnetauth2_widget_showtype">
					<option value="window"
					<?php if( $socnetauth2_widget_showtype=='window' ) { ?> selected <?php } ?>
					><?php echo $text_showtype_window; ?></option>
					<option value="redirect"
					<?php if( $socnetauth2_widget_showtype=='redirect' ) { ?> selected <?php } ?>
					><?php echo $text_showtype_redirect; ?></option>
				</select>
			</td>
		</tr>
		*/ ?>
		
		<tr>
			<td><?php echo $entry_widget_after; ?></td>
			<td>
				<select name="socnetauth2_widget_after">
					<option value="show"
					<?php if( $socnetauth2_widget_after=='show' ) { ?> selected <?php } ?>
					><?php echo $text_widget_after_show; ?></option>
					<option value="hide"
					<?php if( $socnetauth2_widget_after=='hide' ) { ?> selected <?php } ?>
					><?php echo $text_widget_after_hide; ?></option>
				</select>
			</td>
		</tr>
		
	   <!--
	  <tr>
		<td>
			<?php echo $entry_show_standart_auth; ?>
		</td>
		<td>
			<input type="checkbox" name="socnetauth2_show_standart_auth" value="1" 
			<?php if( !empty( $socnetauth2_show_standart_auth ) ) { ?> checked <?php } ?> >
		</td>
	   </tr>
	   -->
	   </table>
	   	   
	  <br><br>
	   <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_widget_layout; ?></td>
              <td class="left"><?php echo $entry_widget_position; ?></td>
              <td class="left"><?php echo $entry_widget_status; ?></td>
              <td class="right"><?php echo $entry_widget_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($socnetauth2_modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="socnetauth2_modules[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="socnetauth2_modules[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="socnetauth2_modules[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="socnetauth2_modules[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"
			  ><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="right"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
	  </div>
	  <!-- /////////////////////////////////////////////////////////////////////////////// -->
	  
	  
	  
	  <div id="tab-popup">
	  
	  <table class="form">
	  <?php /* kin insert metka: f1 */ ?>
	  <tr>
		<td>
			<?php echo $entry_mobile_control; ?>
		</td>
		<td>
			<select name="socnetauth2_mobile_control">
                  <?php if ( $socnetauth2_mobile_control ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
		</td>
	   </tr>
	  <?php /* end kin metka: f1 */ ?>
	  
	  
	  <tr>
		<td>
			<?php echo $entry_popup_name; ?>
		</td>
		<td>
	  		<?php foreach ($languages as $language) { ?>
			<p>
			<input type="text" name="socnetauth2_popup_name[<?php echo $language['language_id']; ?>]" value="<?php if( !empty($socnetauth2_popup_name[ $language['language_id'] ]) ) echo $socnetauth2_popup_name[ $language['language_id'] ]; ?>" style="width: 300px;">&nbsp;<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" />
			</p>
			<?php } ?>
		</td>
	   </tr>
	   </table>
	  <br><br>
	   <table id="popup" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_widget_layout; ?></td>
              <td class="left"><?php echo $entry_widget_status; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $popup_row = 0; ?>
          <?php foreach ($socnetauth2_popups as $popup) { ?>
          <tbody id="popup-row<?php echo $popup_row; ?>">
            <tr>
              <td class="left"><select name="socnetauth2_popups[<?php echo $popup_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $popup['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="right"><select name="socnetauth2_popups[<?php echo $popup_row; ?>][status]">
                  <?php if ($popup['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><a onclick="$('#popup-row<?php echo $popup_row; ?>').remove();" class="button"
			  ><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $popup_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="right"><a onclick="addPopup();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
	  </div>
	  <!-- /////////////////////////////////////////////////////////////////////////////// -->
	  
	  
	  
	  <div id="tab-design">
	  <p><?php echo $text_design_notice2; ?></p>
	  
		<table class="list">
		<thead>
		<tr>
			<td class="left" width=33%><?php echo $text_design_col_element; ?></td>
			<td class="left" width=33%><?php echo $text_design_col_file; ?></td>
			<td class="left" width=34%><?php echo $text_design_col_comment; ?></td>
		</tr>
		</thead>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_account; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_account.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_checkout; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_checkout.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_simple; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_simple.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_simplereg; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_simplereg.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_reg; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_reg.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_popup; ?></td>
			<td class="left">/catalog/view/theme/default/template/module/socnetauth2_popup.tpl</td>
			<td class="left"></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_confirm; ?></td>
			<td class="left">/admin/view/template/socnetauth2_blocks/socnetauth2_confirm.tpl</td>
			<td class="left"><?php echo $text_design_notice; ?></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_frame; ?></td>
			<td class="left">/catalog/view/theme/default/template/account/socnetauth2_frame.tpl</td>
			<td class="left"></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_socnetauth2_frame_success; ?></td>
			<td class="left">/catalog/view/theme/default/template/account/socnetauth2_frame_success.tpl</td>
			<td class="left"></td>
		</tr>
		<tr>
			<td class="left"><?php echo $text_design_row_module_socnetauth2; ?></td>
			<td class="left">/catalog/view/theme/default/template/module/socnetauth2.tpl</td>
			<td class="left"></td>
		</tr>
		</table>
		
		
		
	  </div>
	  
	  
	  
	  
      </form>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 



$('#<?php echo $tab; ?>').click();

</script>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="socnetauth2_modules[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="socnetauth2_modules[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="socnetauth2_modules[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="socnetauth2_modules[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}

var popup_row = <?php echo $popup_row; ?>;

function addPopup() {	
	html  = '<tbody id="popup-row' + popup_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="socnetauth2_popups[' + popup_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="socnetauth2_popups[' + popup_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><a onclick="$(\'#popup-row' + popup_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#popup tfoot').before(html);
	
	popup_row++;
}
//--></script> 

    </div>
  </div>
</div>
<?php echo $footer; ?>