
<!--author sv2109 (sv2109@gmail.com) license for 1 product copy granted for feofan (feofan.net, am@feofan.net)-->
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
    <div class="heading order_head">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form-settings').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      
      <div id="tabs" class="htabs">
        <a href="#tab-statuses"><?php echo $tab_statuses; ?></a>
        <a href="#tab-general"><?php echo $tab_general; ?></a>
      <div class="clr"></div>
	  </div>
    <div class="th_style"></div>
        <div id="tab-statuses">
          <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-delete">
          
          <div class="buttons" style="float: right; margin:  0 5px 7px 0;">
            <a href="<?php echo $insert; ?>" class="button" style="margin-right: 5px;"><?php echo $button_insert; ?></a>
            <a onclick="$('#form-delete').submit();" class="button"><?php echo $button_delete; ?></a>
          </div>
          <table id="statuses" class="list">
            <thead>
              <tr>
                <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                <td class="left"><?php echo $status_image; ?></td>                            
                <td class="left"><?php echo $status_name; ?></td>              
                <td class="left"><?php echo $status_url; ?></td>
                <td class="left"><?php echo $status_action; ?></td>  
              </tr>
            </thead>
            <tbody>

            <?php if ($statuses) { ?>
            <?php foreach ($statuses as $status) { ?>
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $status['status_id']; ?>" />
              </td>
              <td class="left"><img src="<?php echo $status['thumb']; ?>" /></td>
              <td class="left"><?php echo $status['name']; ?></td>
              <td class="left"><a href="<?php echo $status['url']; ?>"><?php echo $status['url']; ?></a></td>
              <td class="left"><?php foreach ($status['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
            
            </tbody>
          </table>

          <div class="pagination">  
            <?php echo $pagination; ?>
          </div>  
        
        </form>  
        </div>
        
        <div id="tab-general">  
        <form action="<?php echo $action_settings; ?>" method="post" enctype="multipart/form-data" id="form-settings">
          <table id="general" class="list" style="width: 700px;">
			      <thead>
              <tr>
				        <td class="left"></td>
                <td class="left"><?php echo $text_product; ?></td>
				        <td class="left"><?php echo $text_category; ?></td>
              </tr>								  
			      </thead>
            <tbody >
              <tr>
                <td class="left"><?php echo $text_image_width; ?><br /><?php echo $text_image_width_help; ?></td>              
                <td width="1" style="text-align: left;">
                  <input type="text" name="product_status_options[product][image_width]" value="<?php echo isset($options['product']['image_width']) ? $options['product']['image_width'] : '0';?>">
                </td>
                <td width="1" style="text-align: left;">
                  <input type="text" name="product_status_options[category][image_width]" value="<?php echo isset($options['category']['image_width']) ? $options['category']['image_width'] : '0';?>">
                </td>
              </tr>
			  <tr>
                <td class="left"><?php echo $text_image_height; ?><br /><?php echo $text_image_height_help; ?></td>              
                <td width="1" style="text-align: left;">
                  <input type="text" name="product_status_options[product][image_height]" value="<?php echo isset($options['product']['image_height']) ? $options['product']['image_height'] : '0';?>">
                </td>
                <td width="1" style="text-align: left;">
                  <input type="text" name="product_status_options[category][image_height]" value="<?php echo isset($options['category']['image_height']) ? $options['category']['image_height'] : '0';?>">
                </td>
              </tr>
			        <tr>
                <td class="left"><?php echo $text_name_display; ?></td>              
                <td width="1" style="text-align: left;">
				          <select name="product_status_options[product][name_display]">
                    <option value="disable" <?php echo (isset($options['product']['name_display']) && $options['product']['name_display'] == 'disable') ?  'selected="selected"' : '';?>><?php echo $text_name_display_disable; ?></option>
                    <option value="next" <?php echo (isset($options['product']['name_display']) && $options['product']['name_display'] == 'next') ?  'selected="selected"' : '';?>><?php echo $text_name_display_next; ?></option>
                    <option value="tip" <?php echo (isset($options['product']['name_display']) && $options['product']['name_display'] == 'tip') ?  'selected="selected"' : '';?>><?php echo $text_name_display_tip; ?></option>
                  </select>					
                </td>
                <td width="1" style="text-align: left;">
				          <select name="product_status_options[category][name_display]">
                    <option value="disable" <?php echo (isset($options['category']['name_display']) && $options['category']['name_display'] == 'disable') ?  'selected="selected"' : '';?>><?php echo $text_name_display_disable; ?></option>
                    <option value="next" <?php echo (isset($options['category']['name_display']) && $options['category']['name_display'] == 'next') ?  'selected="selected"' : '';?>><?php echo $text_name_display_next; ?></option>
                    <option value="tip" <?php echo (isset($options['category']['name_display']) && $options['category']['name_display'] == 'tip') ?  'selected="selected"' : '';?>><?php echo $text_name_display_tip; ?></option>
                  </select>					
                </td>
              </tr>
			        <tr>
                <td class="left"><?php echo $text_status_display; ?></td>              
                <td width="1" style="text-align: left;">
				          <select name="product_status_options[product][status_display]">
                    <option value="inline" <?php echo (isset($options['product']['status_display']) && $options['product']['status_display'] == 'inline') ?  'selected="selected"' : '';?>><?php echo $text_status_display_inline; ?></option>
                    <option value="new_line" <?php echo (isset($options['product']['status_display']) && $options['product']['status_display'] == 'new_line') ?  'selected="selected"' : '';?>><?php echo $text_status_display_new_line; ?></option>
                  </select>					
                </td>
                <td width="1" style="text-align: left;">
				          <select name="product_status_options[category][status_display]">
                    <option value="inline" <?php echo (isset($options['category']['status_display']) && $options['category']['status_display'] == 'inline') ?  'selected="selected"' : '';?>><?php echo $text_status_display_inline; ?></option>
                    <option value="new_line" <?php echo (isset($options['category']['status_display']) && $options['category']['status_display'] == 'new_line') ?  'selected="selected"' : '';?>><?php echo $text_status_display_new_line; ?></option>
                  </select>					
                </td>
              </tr>
			        <tr>
                <td class="left"><?php echo $text_css; ?></td>              
                <td width="1" style="text-align: left;">
				          <textarea rows="10" name="product_status_options[product][css]"><?php echo isset($options['product']['css']) ? $options['product']['css']: '';?></textarea>					
                </td>
                <td width="1" style="text-align: left;">
				          <textarea rows="10" name="product_status_options[category][css]"><?php echo isset($options['category']['css']) ? $options['category']['css']: '';?></textarea>					
                </td>
              </tr>
 
            </tbody>
          </table>
        
        </form>
        </div>

    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<?php echo $footer; ?>