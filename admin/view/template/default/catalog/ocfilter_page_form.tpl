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
      <h1><img src="view/image/filter.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <form id="form" action="<?php echo $action; ?>" method="post">
        <table class="form">
          <tr>
            <td>Название (H1)</td>
            <td>
              <input type="text" name="title" value="<?php echo $title; ?>" placeholder="Название (H1)" id="input-title" size="90" required />
            </td>
          </tr>
          <tr>
            <td>Meta Title</td>
            <td>
              <input type="text" name="meta_title" value="<?php echo $meta_title; ?>" placeholder="Meta Title" id="input-meta-title" size="90" />
            </td>
          </tr>
          <tr>
            <td>Meta Description</td>
            <td>
              <textarea name="meta_description" placeholder="Meta Description" id="input-meta-description" rows="2" cols="90"><?php echo $meta_description; ?></textarea>
            </td>
          </tr>
          <tr>
            <td>Meta Keyword</td>
            <td>
              <textarea name="meta_keyword" placeholder="Meta Keyword" id="input-meta-keyword" rows="2" cols="90"><?php echo $meta_keyword; ?></textarea>
            </td>
          </tr>
          <tr>
            <td>Категория</td>
            <td>
              <input type="text" name="category" value="<?php echo $category ?>" placeholder="Введите название категории" id="input-category" size="90" />
              <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
            </td>
          </tr>
          <tr>
            <td>Параметры фильтра<br /><small class="help">Здесь только параметры фильтра! Без категории!</small></td>
            <td>
              <input type="text" name="ocfilter_params" value="<?php echo $ocfilter_params; ?>" placeholder="Параметры фильтра" id="input-title" size="90" required />
            </td>
          </tr>
          <tr>
            <td>SEO Текст</td>
            <td>
              <textarea name="description" placeholder="SEO Текст" id="input-description"><?php echo $description; ?></textarea>
            </td>
          </tr>
          <tr>
            <td>SEO URL<br /><small class="help">Должен быть уникальным на всю систему</small></td>
            <td>
              <input type="text" name="keyword" value="<?php echo $keyword; ?>" size="90" required />
            </td>
          </tr>
          <tr>
            <td>Статус</td>
            <td>
              <select name="status" id="input-status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.replace('input-description', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script>
<script type="text/javascript"><!--
// Category
$('input[name=\'category\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
    $('input[name=\'category_id\']').val(ui.item.value);
    $('input[name=\'category\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});
//--></script>
<?php echo $footer; ?>