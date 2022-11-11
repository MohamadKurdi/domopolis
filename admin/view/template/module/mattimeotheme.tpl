<?php echo $header; ?>

<?php $status = array('Disabled' => 0, 'Enabled' => 1); ?>

<link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/adminmattimeo.css" />
<script src="view/javascript/mattimeo/bootstrap.min.js"></script>
<script src="view/javascript/mattimeo/admin.js"></script>
<script type="text/javascript" src="view/javascript/jscolor/jscolor.js"></script>

<div id="content" class="matt-mod">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"  name="newsform" >
	<div class="box" id="main-wrapper">
		
		<div class="navbar">
        <div class="navbar-inner">
				<span class="brand"><?php echo $heading_title; ?></span>

				<div class="buttons pull-right">
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
					<a onclick="$('#form').submit();" class="btn btn-success btn-large"><?php echo $button_save; ?></a>
                     <a onclick="buttonApply();" class="btn btn-success btn-large"><?php echo $text_apply; ?></a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-large"><?php echo $button_cancel; ?></a>
				</div>
                </div>
		</div>
		
				<div id="customcontent">
                    
                    	<ul id="tabs1" class="nav nav-tabs">
                            <li class="active"><a href="#general"><?php echo $text_generalsett; ?></a></li>
                            <li><a href="#top_header"><?php echo $text_header; ?></a></li>
							<li><a href="#top_menu"><?php echo $text_menu_generalmenu; ?></a></li>
                            <li><a href="#top_slider"><?php echo $text_slider; ?></a></li>
                            <li><a href="#product_page"><?php echo $text_product; ?></a></li> 
                             <li><a href="#m_customblock"><?php echo $custom_comptext_text; ?></a></li>
                            <li><a href="#m_widget"><?php echo $footer_heading; ?></a></li>
                            <li><a href="#all_contact"><?php echo $text_contact_heading; ?></a></li>
                            <li><a href="#fonts"><?php echo $text_fonts; ?></a></li>
                            <li><a href="#colors"><?php echo $color_text; ?></a></li>
                           
						</ul>

						<div class="tab-content">
                            <!-- General Settings --> 
                            <div id="general" class="tab-pane active">
							<?php include "m_general.php"; ?>
							</div>
                            
                             <!-- Header --> 
                            <div id="top_header" class="tab-pane">
							<?php include "m_header.php"; ?>
							</div>
                        
		                    <!-- Top menu --> 
                            <div id="top_menu" class="tab-pane">
							<?php include "m_topmenu.php"; ?>
							</div>
                            
                            <!-- Slider --> 
                            <div id="top_slider" class="tab-pane">
							<?php include "m_slider.php"; ?>
							</div>
                            
                             <!-- SProduct Page --> 
                            <div id="product_page" class="tab-pane">
							<?php include "m_product.php"; ?>
							</div>
                            
                            <!-- Custom block --> 
                            <div id="m_customblock" class="tab-pane">
							<?php include "m_customblock.php"; ?>
							</div>
                            
                              <!-- Widget --> 
                            <div id="m_widget" class="tab-pane">
							<?php include "m_widget.php"; ?>
							</div>
                            
                             <!-- Footer Contact --> 
                            <div id="all_contact" class="tab-pane">
							<?php include "m_footercontact.php"; ?>
							</div>

                            
                             <!-- Fonts --> 
                            <div id="fonts" class="tab-pane">
							<?php include "m_fonts.php"; ?>
							</div>
                            
                             <!-- Colors --> 
                            <div id="colors" class="tab-pane">
							<?php include "m_colors.php"; ?>
							</div>
                            
						</div>
                        
                        
					</div>
          <input type="hidden" name="buttonForm" value="">

		
	</div>
</form>
</div>


<script type="text/javascript"><!--
function image_upload(field, previewImg) {
	
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + previewImg).replaceWith('<img src="' + data + '" alt="" class="PreviewImage" id="' + previewImg + '" />');
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

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
// SET FCK EDITOR FOR ABOUT US CONTENT

<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('mattimeomod-<?php echo $language["language_id"]; ?>-topmenulink_custom', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('matt_array-<?php echo $language["language_id"]; ?>-comptext_text', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('matt_array-<?php echo $language["language_id"]; ?>-comptext_header_text', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('matt_array-<?php echo $language["language_id"]; ?>-product_text', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('matt_array-<?php echo $language["language_id"]; ?>-product_text_tab', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('matt_array-<?php echo $language["language_id"]; ?>-footer_payment_text', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>


//--></script> 
<script type="text/javascript">
function buttonApply() {document.newsform.buttonForm.value='apply';$('#form').submit();}
</script>



<?php echo $footer; ?>