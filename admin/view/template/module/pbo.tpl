<?php echo $header; ?>
	<style>
			a.block-option,
			a.block-image-option
			{
				cursor: pointer;
				display: inline-block;
				vertical-align: middle;
				text-decoration: none;
				border-style: solid;
				margin: 3px;
			}
			a.block-image-option img
			{				
				vertical-align: middle;
			}
			</style>
<link rel="stylesheet" href="view/stylesheet/colorpicker/colorpicker.css" type="text/css" />
<script type="text/javascript" src="view/javascript/colorpicker/colorpicker.js"></script>
<script type="text/javascript" src="view/javascript/colorpicker/eye.js"></script>
<script type="text/javascript" src="view/javascript/colorpicker/utils.js"></script>
<script type="text/javascript" src="view/javascript/colorpicker/layout.js?ver=1.0.2"></script>
<script type="text/javascript" src="view/javascript/colorpicker/misc.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		previewBlockStyle();
		
		colorPickerise($('.color-input'));
		
		$('.form input').keyup(function(){
			previewBlockStyle();
		});
		$('body').mouseup(function(){
			previewBlockStyle();
		});
		
		$('a.block-option, a.block-image-option').click(function(e){
			e.preventDefault();
		});
	});
	
	function previewBlockStyle()
	{
		previewTextBlock();
		previewImageBlock();
	}
	
	function previewTextBlock()
	{
		$textBlock = $('.block-option');
		$textBlockActive = $('.block-option.block-active');
		
		var padding = $('#textBlockPadding').val() + 'px';
		var borderWidth = $('#textBlockBorderWidth').val() + 'px';
		var borderRadius = $('#textBlockBorderRadius').val() + 'px';
		var backgroundColor = $('#textBlockBackgroundColor').val();
		var selectedBackgroundColor = $('#textBlockSelectedBackgroundColor').val();
		var textColor = $('#textBlockTextColor').val();
		var selectedTextColor = $('#textBlockSelectedTextColor').val();
		var borderColor = $('#textBlockBorderColor').val();
		var selectedBorderColor = $('#textBlockSelectedBlockBorderColor').val();
		
		$textBlock.css('padding', padding);		
		$textBlock.css('border-width', borderWidth);		
		$textBlock.css('border-radius', borderRadius);
		
		$textBlock.css('background-color', backgroundColor);
		$textBlockActive.css('background-color', selectedBackgroundColor);
		
		$textBlock.css('color', textColor);
		$textBlockActive.css('color', selectedTextColor);
		
		$textBlock.css('border-color', borderColor);
		$textBlockActive.css('border-color', selectedBorderColor);
	}
	
	function previewImageBlock()
	{
		$imageBlock = $('.block-image-option');
		$imageBlockImage = $('.block-image-option img');
		$imageBlockActive = $('.block-image-option.block-active');
		
		var padding = $('#imageBlockPadding').val() + 'px';
		var borderWidth = $('#imageBlockBorderWidth').val() + 'px';
		var borderRadius = $('#imageBlockBorderRadius').val() + 'px';
		var textColor = $('#imageBlockTextColor').val();
		var borderColor = $('#imageBlockBorderColor').val();
		var selectedTextColor = $('#imageBlockSelectedTextColor').val();
		var selectedBorderColor = $('#imageBlockSelectedBlockBorderColor').val();
		var imageWidth = $('#imageBlockWidth').val() + 'px';
		var imageHeight = $('#imageBlockHeight').val() + 'px';
		
		$imageBlock.css('padding', padding);		
		$imageBlock.css('border-width', borderWidth);
		$imageBlock.css('border-radius', borderRadius);
		
		$imageBlock.css('color', textColor);
		$imageBlockActive.css('color', selectedTextColor);
		
		$imageBlock.css('border-color', borderColor);
		$imageBlockActive.css('border-color', selectedBorderColor);
		
		$imageBlockImage.css('max-width', imageWidth);
		$imageBlockImage.css('max-height', imageHeight);
	}
</script>
<style>
	.scrollbox
	{
		height: 200px;
	}
</style>
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
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<h2><?php echo $entry_general; ?></h2>
		<table class="form">
			<tbody>
				<!--tr>
					<td>
						<?php echo $entry_allow_unselecting; ?>
					</td>
					<td>
						<input type="radio" name="pbo_allow_unselecting" value="true" <?php echo $pbo_allow_unselecting == 'true' ? 'checked="checked"' : ''; ?>><?php echo $text_yes;?>
						<input type="radio" name="pbo_allow_unselecting" value="false"<?php echo $pbo_allow_unselecting == 'false' ? 'checked="checked"' : ''; ?>><?php echo $text_no;?>
					</td>
				</tr-->
				<tr>
					<td>
						<?php echo $entry_apply_to_standard_options; ?>
					</td>
					<td>
						<div class="scrollbox">
							<?php $class = 'odd'; ?>
							<?php foreach ($standard_options as $standard_option) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
									<input
										type="checkbox"
										name="pbo_options[]"
										value="<?php echo $standard_option['option_id']; ?>"
										<?php if (in_array($standard_option['option_id'], $pbo_options)) { ?>
											checked="checked"
										<?php } ?>
									/>(<?php echo $standard_option['type']; ?>) <?php echo $standard_option['name']; ?>
								</div>
							<?php } ?>
						</div>
						<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
					</td>
				</tr>
			</tbody>
		</table>
		<h2><?php echo $entry_text_block; ?></h2>
		<table class="form">
			<tbody>				
				<tr>
					<td>
						<?php echo $entry_preview; ?>
					</td>
					<td>
						<a class="block-option" id="textBlockNormal" title="Default">Default</a>
			  			<a class="block-option block-active" id="textBlockSelected" title="Selected">Selected</a>
						<a class="block-option" id="textBlockNormalWithPrice" title="Default With Price (+$13.75)">Default With Price (+$13.75)</a>
						<a class="block-option block-active" id="textBlockSelectedWithPrice" title="Selected With Price">Selected With Price (+$13.75)</a>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_padding; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_padding" value="<?php echo $pbo_text_block_padding; ?>" id="textBlockPadding" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_border_width; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_border_width" value="<?php echo $pbo_text_block_border_width; ?>" id="textBlockBorderWidth" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_border_radius; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_border_radius" value="<?php echo $pbo_text_block_border_radius; ?>" id="textBlockBorderRadius" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_background_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_background_color" value="<?php echo $pbo_text_block_background_color; ?>" id="textBlockBackgroundColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_text_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_text_color" value="<?php echo $pbo_text_block_text_color; ?>" id="textBlockTextColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_border_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_border_color" value="<?php echo $pbo_text_block_border_color; ?>" id="textBlockBorderColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_selected_background_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_selected_background_color" value="<?php echo $pbo_text_block_selected_background_color; ?>" id="textBlockSelectedBackgroundColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_selected_text_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_selected_text_color" value="<?php echo $pbo_text_block_selected_text_color; ?>" id="textBlockSelectedTextColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_selected_block_border_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_text_block_selected_block_border_color" value="<?php echo $pbo_text_block_selected_block_border_color; ?>" id="textBlockSelectedBlockBorderColor" class="color-input" />
					</td>
				</tr>
			</tbody>
		</table>
		<h2><?php echo $entry_image_block; ?></h2>
		<table class="form">
			<tbody>				
				<tr>
					<td>
						<?php echo $entry_preview; ?>
					</td>
					<td>
						<a class="block-image-option" id="imageBlockNormal" title="Default"><img src="view/image/pbo/iphone.png" alt="Default" /></a>
			  			<a class="block-image-option block-active" id="imageBlockSelected" title="Selected"><img src="view/image/pbo/ipad.jpg" alt="Selected" /></a>
						<a class="block-image-option" id="imageBlockNormalWithPrice" title="Default With Price (+$13.75)"><img src="view/image/pbo/imac.jpg" alt="Default With Price (+$13.75)" />(+$13.75)</a>
			  			<a class="block-image-option block-active" id="imageBlockSelectedWithPrice" title="Selected With Price (+$13.75)"><img src="view/image/pbo/macbook.jpg" alt="Selected With Price (+$13.75)" />(+$13.75)</a>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_padding; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_padding" value="<?php echo $pbo_image_block_padding; ?>" id="imageBlockPadding" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_border_width; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_border_width" value="<?php echo $pbo_image_block_border_width; ?>" id="imageBlockBorderWidth" />
					</td>
				</tr
				<tr>
					<td>
						<?php echo $entry_block_border_radius; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_border_radius" value="<?php echo $pbo_image_block_border_radius; ?>" id="imageBlockBorderRadius" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_width_height; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_width" value="<?php echo $pbo_image_block_width; ?>" id="imageBlockWidth" size="3" />
						x <input type="text" name="pbo_image_block_height" value="<?php echo $pbo_image_block_height; ?>" id="imageBlockHeight" size="3" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_text_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_text_color" value="<?php echo $pbo_image_block_text_color; ?>" id="imageBlockTextColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_selected_text_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_selected_text_color" value="<?php echo $pbo_image_block_selected_text_color; ?>" id="imageBlockSelectedTextColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_border_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_border_color" value="<?php echo $pbo_image_block_border_color; ?>" id="imageBlockBorderColor" class="color-input" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $entry_block_selected_block_border_color; ?>
					</td>
					<td>
						<input type="text" name="pbo_image_block_selected_block_border_color" value="<?php echo $pbo_image_block_selected_block_border_color; ?>" id="imageBlockSelectedBlockBorderColor" class="color-input" />
					</td>
				</tr>
			</tbody>
		</table>
    </form>
	<p>
		Thank you for your purchase, please check <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=WeDoWeb" title="WeDoWeb's OpenCart extensions">our website</a> for more useful extensions.<br/>
		<br/>
		<b><a href="http://wedoweb.com.au" title="WeDoWeb Team">WeDoWeb Team</a></b>
	</p>
  </div>
</div>
<?php echo $footer; ?>