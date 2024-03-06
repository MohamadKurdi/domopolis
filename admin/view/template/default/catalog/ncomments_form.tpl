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
 <?php echo $newspanel; ?>
  <div class="box blogbox">
    <div class="heading order_head">
      <h1><?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button sterge"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
			<tr>
            <td>Language:</td>
            <td>
				<select name="language_id">
					<?php foreach ($languages as $language) { ?>
						<?php if ($language['language_id'] == $language_id) { ?>
							<option selected="selected" value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_author; ?></td>
            <td><input type="text" name="author" value="<?php echo $author; ?>" />
              <?php if ($error_author) { ?>
              <span class="error"><?php echo $error_author; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_product; ?></td>
            <td>
			<input type="text" name="article" value="<?php echo $article; ?>" />
            <input type="hidden" name="news_id" value="<?php echo $news_id; ?>" />
            <?php if ($error_article) { ?>
              <span class="error"><?php echo $error_article; ?></span>
            <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_text; ?></td>
            <td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
              <?php if ($error_text) { ?>
              <span class="error"><?php echo $error_text; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'article\']').autocomplete({
	delay: 0,
	source: function(request, response) {
	 if (encodeURIComponent(request.term).length > 2) {
		$.ajax({
			url: 'index.php?route=catalog/news/autocomplete&token=<?php echo $token; ?>&filter_aname=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.title,
						value: item.news_id
					}
				}));
			}
		});
	 }
		
	}, 
	select: function(event, ui) {
		$('input[name=\'article\']').val(ui.item.label);
		$('input[name=\'news_id\']').val(ui.item.value);
		
		return false;
	}
});
//--></script> 
<?php echo $footer; ?>