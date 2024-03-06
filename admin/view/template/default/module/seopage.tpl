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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<h2><?php echo $header_1; ?></h2>
        <table class="form">
			
          <tr>
            <td> <?php echo $entry_google; ?></td>
            <td>
			   <label class="radio-inline">
                <?php if ($seopage_google) { ?>
                <input type="radio" name="seopage_google" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_google" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_google) { ?>
                <input type="radio" name="seopage_google" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_google" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			</td>
          </tr>          
		  <tr>
            <td> <?php echo $entry_yandex; ?></td>
            <td>
			   <label class="radio-inline">
                <?php if ($seopage_yandex) { ?>
                <input type="radio" name="seopage_yandex" class="yes" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_yandex" class="yes" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_yandex) { ?>
                <input type="radio" name="seopage_yandex" class="no" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_yandex" class="no" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			</td>
          </tr>          
		  <tr id="follow">
            <td> <?php echo '->'; ?></td>
            <td>
			   <label class="radio-inline">
                <?php if ($seopage_follow) { ?>
                <input type="radio" name="seopage_follow" value="1" checked="checked" />
                <?php echo $text_follow; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_follow" value="1" />
                <?php echo $text_follow; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_follow) { ?>
                <input type="radio" name="seopage_follow" value="0" checked="checked" />
                <?php echo $text_nofollow; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_follow" value="0" />
                <?php echo $text_nofollow; ?>
                <?php } ?>
              </label>
			</td>
          </tr>
		</table>
		
		<h2><?php echo $header_2; ?></h2>
		 
		<table class="form">
		  <tr>
            <td> <?php echo $entry_description; ?></td>
            <td>
			   <label class="radio-inline">
                <?php if ($seopage_description) { ?>
                <input type="radio" name="seopage_description" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_description" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_description) { ?>
                <input type="radio" name="seopage_description" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_description" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			</td>
          </tr> 
		  <tr>
            <td> <?php echo $entry_page; ?></td>
            <td>
			    <label class="radio-inline">
                <?php if ($seopage_page) { ?>
                <input type="radio" name="seopage_page" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_page" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_page) { ?>
                <input type="radio" name="seopage_page" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_page" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			</td>
          </tr>
		  <tr>
            <td> <?php echo $entry_metapattern; ?></td>
            <td>
			   <input type="text" name="seopage_metapattern" value="<?php echo $seopage_metapattern; ?>" id="seopage_metapattern" class="form-control" />
              <p><?php echo $text_metapattern; ?></p>
			</td>
          </tr>
		  <tr>
            <td> <?php echo $entry_301; ?></td>
            <td>
			    <label class="radio-inline">
                <?php if ($seopage_301) { ?>
                <input type="radio" name="seopage_301" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_301" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_301) { ?>
                <input type="radio" name="seopage_301" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_301" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			</td>
          </tr>
		  <tr>
            <td> <?php echo $entry_h1; ?></td>
            <td>
			   <label class="radio-inline">
                <?php if ($seopage_h1) { ?>
                <input type="radio" name="seopage_h1" class="yes1" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_h1" class="yes1" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_h1) { ?>
                <input type="radio" name="seopage_h1" class="no1" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_h1" class="no1" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			</td>
          </tr> 
		  <tr class="h1">
            <td> <?php echo $entry_span; ?></td>
            <td>
			   <label class="radio-inline">
                <?php if ($seopage_span) { ?>
                <input type="radio" name="seopage_span" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_span" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$seopage_span) { ?>
                <input type="radio" name="seopage_span" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="seopage_span" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			</td>
          </tr>	  		  
		  <tr class="h1">
            <td> <?php echo $entry_pattern; ?></td>
            <td>
			   <input type="text" name="seopage_pattern" value="<?php echo $seopage_pattern; ?>" id="seopage_pattern" class="form-control" />
              <p><?php echo $text_pattern; ?></p>
			</td>
          </tr>
		  <tr class="h1">
            <td> <?php echo $entry_style; ?></td>
            <td>
			  <textarea type="text" name="seopage_style" id="seopage_style" class="form-control" rows="6"><?php echo $seopage_style; ?></textarea>
			</td>
          </tr>
		
		  
        </table>
        
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {                            
    $(".yes").change(function () {
        if ($(".yes").prop("checked", true) ) {
            $('#follow').show();
        }
	});
	$(".no").change(function () {
        if ($(".no").prop("checked", true) ) {
            $('#follow').hide();
        }
	});
	if ($(".no").is(":checked")) {
            $('#follow').hide();
        }
});
</script>
<script>
$(document).ready(function () {                            
    $(".yes1").change(function () {
        if ($(".yes1").prop("checked", true) ) {
            $('.h1').hide();
        }
	});
	$(".no1").change(function () {
        if ($(".no1").prop("checked", true) ) {
            $('.h1').show();
        }
	});
	if ($(".yes1").is(":checked")) {
            $('.h1').hide();
        }
});
</script>
<?php echo $footer; ?>