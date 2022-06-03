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
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">          
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          <div class="clr"></div>
		  </div>
          <div class="th_style"></div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $status_image; ?></td>
                <td valign="top"><div class="image"><img src="<?php echo $thumb[$language['language_id']]; ?>" alt="" id="thumb<?php echo $language['language_id']; ?>" />
                  <input type="hidden" name="status_description[<?php echo $language['language_id']; ?>][image]" value="<?php echo $status_description[$language['language_id']]['image']; ?>" id="image<?php echo $language['language_id']; ?>" />
                  <br />
                  <a onclick="image_upload('image<?php echo $language['language_id']; ?>', 'thumb<?php echo $language['language_id']; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $language['language_id']; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div>
                  <?php if (isset($error_image[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_image[$language['language_id']]; ?></span>
                  <?php } ?>  
                </td>
              </tr>

              <tr>
                <td><span class="required">*</span> <?php echo $status_name; ?></td>
                <td><input type="text" name="status_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($status_description[$language['language_id']]['name']) ? $status_description[$language['language_id']]['name'] : ''; ?>" />
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                  <?php } ?>
                </td>
              </tr>
              
              <tr>
                <td><?php echo $status_url; ?></td>
                <td><input type="text" name="status_description[<?php echo $language['language_id']; ?>][url]" size="100" value="<?php echo isset($status_description[$language['language_id']]['url']) ? $status_description[$language['language_id']]['url'] : ''; ?>" />
              </tr>              
            </table>
          </div>
          <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
$('#languages a').tabs();
//--></script> 
<?php echo $footer; ?>