<?php echo $header; ?>
<style>
.center {
	background-color: #EFEFEF;
	padding: 7px;
	text-align: center;
	font-weight: bold;
}
</style>
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
  <?php if ($error_logs) { ?>
  <div class="warning"><?php echo $error_logs; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').attr('action' , '<?php echo $action; ?>&return=<?php echo base64_encode($action); ?>'); $('#form').submit();" class="button"><?php echo $button_stay; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a><a href="<?php echo $code; ?>" class="button"><?php echo $button_code; ?></a><a href="<?php echo $log; ?>" class="button"><?php echo $button_log; ?></a></div>
    </div>
    <div class="content">
	  <div class="attention">The following line should be added to the CRON. <a href="http://www.youtube.com/watch?v=ibcE3BrUKpw" target="_blank">How to set cron job in directadmin?</a><br />If you use SSL on the server remember to change 'http' to 'https'.<br /><br /><b>wget <?php echo $cron; ?> -O /dev/null</b></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tabs" class="htabs">
          <?php foreach ($stores as $store) { ?>
          <a href="#tab-store-<?php echo $store['store_id']; ?>" id="store-<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></a>
          <?php } ?>
		</div>
        <?php foreach ($stores as $store) { ?>
        <div id="tab-store-<?php echo $store['store_id']; ?>">
		  <table style="width: 100%;">
			<thead>
			  <tr>
                <td class="center" width="50%"><?php echo $heading_setting; ?></td>
                <td class="center"><?php echo $heading_discount; ?></td>
              </tr>
			</thead>
			<tbody>
			  <tr>
                <td valign="top">
				  <table class="form">
				    <tr>
				      <td><?php echo $entry_status; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_status]">
				        <?php if (isset($module[$store['store_id']]['review_booster_status']) && $module[$store['store_id']]['review_booster_status'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				        <option value="0"><?php echo $text_disabled; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_enabled; ?></option>
				        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_status_order; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_status_order_id][]" multiple style="width: 220px; height: 190px;">
				        <?php foreach ($order_statuses as $status) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_status_order_id']) && is_array($module[$store['store_id']]['review_booster_status_order_id']) && in_array($status['order_status_id'], $module[$store['store_id']]['review_booster_status_order_id'])) { ?>
				        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
				        <?php } else { ?>
				        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
				        <?php } ?>
						<?php } ?>
				      </select><div style="padding-top: 4px; font-size: 11px; color: #666666;"><?php echo $text_multiselect; ?></div></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_day; ?></td>
				      <td><input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_day]" value="<?php echo isset($module[$store['store_id']]['review_booster_day']) ? $module[$store['store_id']]['review_booster_day'] : ''; ?>" size="5" />
					  <?php if (isset($module[$store['store_id']]['review_booster_previous_customer']) && $module[$store['store_id']]['review_booster_previous_customer'] == '1') { ?>
					   <input type="checkbox" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_previous_customer]" value="1" checked />
					  <?php } else { ?>
					   <input type="checkbox" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_previous_customer]" value="1" />
					  <?php } echo' ' . $entry_previous_customer; ?></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_product_layout; ?></td>
				      <td><?php if (isset($module[$store['store_id']]['review_booster_product_layout']) && $module[$store['store_id']]['review_booster_product_layout'] == 'horizontal') { ?>
					    <input type="radio" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_layout]" value="horizontal" checked="checked" />
					    <?php echo $text_horizontal; ?>
					    <input type="radio" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_layout]" value="vertical" />
					    <?php echo $text_vertical; ?>
					    <?php } else { ?>
					    <input type="radio" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_layout]" value="horizontal" />
					    <?php echo $text_horizontal; ?>
					    <input type="radio" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_layout]" value="vertical" checked="checked" />
					    <?php echo $text_vertical; ?>
					    <?php } ?></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_column; ?></td>
				      <td><input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_column]" value="<?php echo isset($module[$store['store_id']]['review_booster_product_column']) ? $module[$store['store_id']]['review_booster_product_column'] : ''; ?>" size="5" /></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_product_name; ?></td>
				      <td>#<input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_name_color]" value="<?php echo isset($module[$store['store_id']]['review_booster_product_name_color']) ? $module[$store['store_id']]['review_booster_product_name_color'] : '000000'; ?>" id="colorpicker1" size="6" /> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_name_size]">
					    <?php foreach ($sizes as $size) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_name_size']) && $module[$store['store_id']]['review_booster_product_name_size'] == $size) { ?>
						<option value="<?php echo $size; ?>" selected="selected"><?php echo $size; ?>px</option>
						<?php } else { ?>
						<option value="<?php echo $size; ?>"><?php echo $size; ?>px</option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_name_weight]">
					    <?php foreach ($weights as $weight) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_name_weight']) && $module[$store['store_id']]['review_booster_product_name_weight'] == $weight) { ?>
						<option value="<?php echo $weight; ?>" selected="selected"><?php echo $weight; ?></option>
						<?php } else { ?>
						<option value="<?php echo $weight; ?>"><?php echo $weight; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_name_style]">
					    <?php foreach ($styles as $style) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_name_style']) && $module[$store['store_id']]['review_booster_product_name_style'] == $style) { ?>
						<option value="<?php echo $style; ?>" selected="selected"><?php echo $style; ?></option>
						<?php } else { ?>
						<option value="<?php echo $style; ?>"><?php echo $style; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_name_transform]">
					    <?php foreach ($transforms as $transform) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_name_transform']) && $module[$store['store_id']]['review_booster_product_name_transform'] == $transform) { ?>
						<option value="<?php echo $transform; ?>" selected="selected"><?php echo $transform; ?></option>
						<?php } else { ?>
						<option value="<?php echo $transform; ?>"><?php echo $transform; ?></option>
						<?php } ?>
						<?php } ?>
						</select></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_product_description; ?></td>
				      <td>#<input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_description_color]" value="<?php echo isset($module[$store['store_id']]['review_booster_product_description_color']) ? $module[$store['store_id']]['review_booster_product_description_color'] : '000000'; ?>" id="colorpicker2" size="6" /> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_description_size]">
					    <?php foreach ($sizes as $size) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_description_size']) && $module[$store['store_id']]['review_booster_product_description_size'] == $size) { ?>
						<option value="<?php echo $size; ?>" selected="selected"><?php echo $size; ?>px</option>
						<?php } else { ?>
						<option value="<?php echo $size; ?>"><?php echo $size; ?>px</option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_description_weight]">
					    <?php foreach ($weights as $weight) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_description_weight']) && $module[$store['store_id']]['review_booster_product_description_weight'] == $weight) { ?>
						<option value="<?php echo $weight; ?>" selected="selected"><?php echo $weight; ?></option>
						<?php } else { ?>
						<option value="<?php echo $weight; ?>"><?php echo $weight; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_description_style]">
					    <?php foreach ($styles as $style) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_description_style']) && $module[$store['store_id']]['review_booster_product_description_style'] == $style) { ?>
						<option value="<?php echo $style; ?>" selected="selected"><?php echo $style; ?></option>
						<?php } else { ?>
						<option value="<?php echo $style; ?>"><?php echo $style; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_description_transform]">
					    <?php foreach ($transforms as $transform) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_description_transform']) && $module[$store['store_id']]['review_booster_product_description_transform'] == $transform) { ?>
						<option value="<?php echo $transform; ?>" selected="selected"><?php echo $transform; ?></option>
						<?php } else { ?>
						<option value="<?php echo $transform; ?>"><?php echo $transform; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_description_limit]">
					    <?php foreach ($characters_limit as $limit) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_description_limit']) && $module[$store['store_id']]['review_booster_product_description_limit'] == $limit) { ?>
						<option value="<?php echo $limit; ?>" selected="selected"><?php echo $text_chars . $limit; ?></option>
						<?php } else { ?>
						<option value="<?php echo $limit; ?>"><?php echo $text_chars . $limit; ?></option>
						<?php } ?>
						<?php } ?>
						</select></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_product_price; ?></td>
				      <td>#<input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_price_color]" value="<?php echo isset($module[$store['store_id']]['review_booster_product_price_color']) ? $module[$store['store_id']]['review_booster_product_price_color'] : '0191ac'; ?>" id="colorpicker3" size="6" /> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_price_size]">
					    <?php foreach ($sizes as $size) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_price_size']) && $module[$store['store_id']]['review_booster_product_price_size'] == $size) { ?>
						<option value="<?php echo $size; ?>" selected="selected"><?php echo $size; ?>px</option>
						<?php } else { ?>
						<option value="<?php echo $size; ?>"><?php echo $size; ?>px</option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_price_weight]">
					    <?php foreach ($weights as $weight) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_price_weight']) && $module[$store['store_id']]['review_booster_product_price_weight'] == $weight) { ?>
						<option value="<?php echo $weight; ?>" selected="selected"><?php echo $weight; ?></option>
						<?php } else { ?>
						<option value="<?php echo $weight; ?>"><?php echo $weight; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_price_style]">
					    <?php foreach ($styles as $style) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_price_style']) && $module[$store['store_id']]['review_booster_product_price_style'] == $style) { ?>
						<option value="<?php echo $style; ?>" selected="selected"><?php echo $style; ?></option>
						<?php } else { ?>
						<option value="<?php echo $style; ?>"><?php echo $style; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_price_align]">
					    <?php foreach ($aligns as $align) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_product_price_align']) && $module[$store['store_id']]['review_booster_product_price_align'] == $align) { ?>
						<option value="<?php echo $align; ?>" selected="selected"><?php echo $align; ?></option>
						<?php } else { ?>
						<option value="<?php echo $align; ?>"><?php echo $align; ?></option>
						<?php } ?>
						<?php } ?>
						</select> <select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_price_status]">
				        <?php if (isset($module[$store['store_id']]['review_booster_price_status']) && $module[$store['store_id']]['review_booster_price_status'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_show; ?></option>
				        <option value="0"><?php echo $text_hide; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_show; ?></option>
				        <option value="0" selected="selected"><?php echo $text_hide; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_product_image; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_product_image]">
				        <?php if (isset($module[$store['store_id']]['review_booster_product_image']) && $module[$store['store_id']]['review_booster_product_image'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
				        <option value="0"><?php echo $text_no; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_yes; ?></option>
				        <option value="0" selected="selected"><?php echo $text_no; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_color_link; ?></td>
				      <td>#<input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_color_link]" value="<?php echo isset($module[$store['store_id']]['review_booster_color_link']) ? $module[$store['store_id']]['review_booster_color_link'] : '0191ac'; ?>" id="colorpicker4" size="6" /></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_color_link_hover; ?></td>
				      <td>#<input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_color_link_hover]" value="<?php echo isset($module[$store['store_id']]['review_booster_color_link_hover']) ? $module[$store['store_id']]['review_booster_color_link_hover'] : '000000'; ?>" id="colorpicker5" size="6" /></td>
				    </tr>
				  </table>
				</td>
                <td valign="top">
				  <table class="form">
				    <tr>
				      <td><?php echo $entry_status; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_discount_status]">
				        <?php if (isset($module[$store['store_id']]['review_booster_discount_status']) && $module[$store['store_id']]['review_booster_discount_status'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				        <option value="0"><?php echo $text_disabled; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_enabled; ?></option>
				        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_discount; ?></td>
				      <td><?php if ($coupons) { ?><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_discount]">
				        <?php foreach ($coupons as $coupon) { ?>
						<?php if ($coupon['status']) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_discount']) && $module[$store['store_id']]['review_booster_discount'] == $coupon['coupon_id']) { ?>
				        <option value="<?php echo $coupon['coupon_id']; ?>" selected="selected"><?php echo $coupon['name']; ?></option>
				        <?php } else { ?>
				        <option value="<?php echo $coupon['coupon_id']; ?>"><?php echo $coupon['name']; ?></option>
				        <?php } ?>
						<?php } ?>
						<?php } ?>
				      </select><?php } else { ?>
					  <?php echo $text_discount; ?>
					  <?php } ?></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_logged; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_discount_logged]">
				        <?php if (isset($module[$store['store_id']]['review_booster_discount_logged']) && $module[$store['store_id']]['review_booster_discount_logged'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
				        <option value="0"><?php echo $text_no; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_yes; ?></option>
				        <option value="0" selected="selected"><?php echo $text_no; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_review; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_discount_review]">
				        <?php if (isset($module[$store['store_id']]['review_booster_discount_review']) && $module[$store['store_id']]['review_booster_discount_review'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
				        <option value="0"><?php echo $text_no; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_yes; ?></option>
				        <option value="0" selected="selected"><?php echo $text_no; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
				      <td class="center" colspan="2"><?php echo $heading_review; ?></td>
				    </tr>
					<tr>
					  <td><?php echo $entry_approve_review; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_approve_review]">
				        <?php if (isset($module[$store['store_id']]['review_booster_approve_review']) && $module[$store['store_id']]['review_booster_approve_review'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
				        <option value="0"><?php echo $text_no; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_yes; ?></option>
				        <option value="0" selected="selected"><?php echo $text_no; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
					  <td><?php echo $entry_review_rating; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_review_rating]">
				        <?php foreach ($ratings as $key => $rating) { ?>
						<?php if (isset($module[$store['store_id']]['review_booster_review_rating']) && $module[$store['store_id']]['review_booster_review_rating'] == $rating) { ?>
				        <option value="<?php echo $rating; ?>" selected="selected"><?php echo $rating; ?></option>
				        <?php } else { ?>
				        <option value="<?php echo $rating; ?>"><?php echo $rating; ?></option>
				        <?php } ?>
						<?php } ?>
				      </select></td>
				    </tr>
					<tr>
					  <td><?php echo $entry_review_snippet; ?></td>
				      <td><select name="review_booster[<?php echo $store['store_id']; ?>][review_booster_review_snippet]">
				        <?php if (isset($module[$store['store_id']]['review_booster_review_snippet']) && $module[$store['store_id']]['review_booster_review_snippet'] == 1) { ?>
				        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
				        <option value="0"><?php echo $text_no; ?></option>
				        <?php } else { ?>
				        <option value="1"><?php echo $text_yes; ?></option>
				        <option value="0" selected="selected"><?php echo $text_no; ?></option>
				        <?php } ?>
				      </select></td>
				    </tr>
					<tr>
				      <td class="center" colspan="2"><?php echo $heading_test; ?></td>
				    </tr>
					<tr>
				      <td colspan="2"><div class="attention"><?php echo $text_setting_error_test; ?></div></td>
				    </tr>
					<tr>
				      <td><?php echo $entry_email; ?></td>
				      <td><input type="text" name="test" value="" size="50" /> <a onclick="sendEmailTest(<?php echo $store['store_id']; ?>);" class="button"><?php echo $button_send; ?></a></td>
				    </tr>
				  </table>
				</td>
              </tr>
			</tbody>
		  </table>
		  <div id="language-<?php echo $store['store_id']; ?>" class="htabs" style="position: relative;">
            <?php foreach ($languages as $language) { ?>
            <a href="#tab-language-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
		  </div><a id="shortcode"><img src="view/image/log.png" alt="Shortcode" title="Shortcode" /></a> <a onClick="loadTemplate(this, '<?php echo $store['store_id']; ?>');"><img src="view/image/layout.png" alt="Load default template" title="Load default template" /></a>
          <?php foreach ($languages as $language) { ?>
          <div id="tab-language-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>">
			<table class="form">
			  <tr>
			    <td><span class="required">*</span> <?php echo $entry_title; ?></td>
			    <td><input type="text" name="review_booster[<?php echo $store['store_id']; ?>][review_booster_title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module[$store['store_id']]['review_booster_title'][$language['language_id']]) ? $module[$store['store_id']]['review_booster_title'][$language['language_id']] : ''; ?>" size="100" /></td>
			  </tr>
			  <tr>
                <td colspan="2"><textarea name="review_booster[<?php echo $store['store_id']; ?>][review_booster_description][<?php echo $language['language_id']; ?>]" id="description-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($module[$store['store_id']]['review_booster_description'][$language['language_id']]) ? $module[$store['store_id']]['review_booster_description'][$language['language_id']] : ''; ?></textarea></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
  <div id="dialog" title="Shortcodes">
    <p><?php echo $text_shortcode; ?></p>
  </div>
  <div id="dialog_rich" title="Rich sippets">
    <p><img src="view/image/rich_snippet.png" alt="" title="" /></p>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($stores as $store) { ?>
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
<?php } ?>
//--></script>
<script type="text/javascript"><!--
function loadTemplate(element, store_id) {
	var language_id = $('#language-' + store_id + ' a').filter('.selected').attr('href').replace(/#tab-language-(\d+)-(\d+)/g, "$2");

	if (element.value != '*') {
		$('textarea[id=\'description-' + store_id + '-' + language_id + '\']').load('index.php?route=module/review_booster/loadtemplate&load=true&token=<?php echo $token; ?>', function(){
			CKEDITOR.instances["description-" + store_id + "-" + language_id].setData($('textarea[id=\'description-' + store_id + '-' + language_id + '\']').text());
		});
	} else {
		CKEDITOR.instances["description-" + store_id + "-" + language_id].setData('');
	}
}

function sendEmailTest(store_id) {
	if ($("input[name='test']").val().length > 0) {
		$.ajax({
			url: '<?php echo (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) ? str_replace('http', 'https', $cron) : $cron; ?>&test=1&store_id=' + store_id + '&email=' + $("input[name='test']").val(),
			dataType: 'text',
			success: function(data) {
				if (data == 'ok') {
					alert('<?php echo $text_success_test; ?>');
				} else {
					alert('<?php echo $error_test; ?>');
				}
			}
		});
	}

	return false;
}

$("#dialog").dialog({width: 340, autoOpen: false});

$("#shortcode").click(function() {
	$("#dialog").dialog("open");
});

$("#dialog_rich").dialog({width: 570, autoOpen: false});

$("#rich").click(function() {
	$("#dialog_rich").dialog("open");
});

$('#tabs a').tabs();
$('.vtabs a').tabs();

$('#colorpicker1, #colorpicker2, #colorpicker3, #colorpicker4, #colorpicker5').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	}
}).bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
});
//--></script> 
<script type="text/javascript"><!--
<?php foreach ($stores as $store) { ?>
$('#language-<?php echo $store['store_id']; ?> a').tabs();
<?php } ?> 
//--></script> 
<?php echo $footer; ?>