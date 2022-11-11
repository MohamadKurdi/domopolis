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
  <?php if ($warning) { ?>
  <div class="warning"><?php echo $warning; ?></div>
  <?php } ?>
  <div class="box">
<style type="text/css">
.new_left{
	padding: 5px 5px !important;
	background: #ddd !important;
}
.listy {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
	margin-bottom: 20px;
}
.listy td {
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;
}
.listy thead td {
	background-color: #EFEFEF;
	padding: 0px 5px;
}
.listy tbody td {
	vertical-align: middle;
	padding: 0px 5px;
	background-color: #F4F4F8;
}
.listy .left {
	text-align: left;
	padding: 5px;
}
.listy .right {
	text-align: right;
	padding: 5px;
}
.listy .center {
	text-align: center;
	padding: 5px;
}
.listy tr.filter td, .listy tr:hover.filter td {
	padding: 5px;
	background: #E7EFEF;
}
</style>
    <div class="heading order_head">
      <h1><img src="view/image/module.png" /> <?php echo $heading_title; ?></h1>
	  <div class="buttons">
		<a class="button" style="float: left; background: #ccc; cursor: default;"><?php echo $button_keyworder_manufacturer; ?></a>
		<a onclick="location = '<?php echo $settings; ?>'" class="button" style="float: left;"><?php echo $button_settings; ?></a>
		<img src="view/image/setting.png" style="float: left;margin: 0 50px 0 10px;" />
		<a onclick="location = '<?php echo $scan; ?>'" class="button"><?php echo $button_scan; ?></a>
		<a onclick="location = '<?php echo $cancel; ?>'" class="button"><?php echo $button_cancel; ?></a>
	  </div>
    </div>
    <div class="content">
	  <form method="post" enctype="multipart/form-data">
        <table class="listy" style="margin: 0 0 10px 0;">
          <thead>
			<tr class="filter">
				<td style="width: 20%">
				  <select name="filter_manufacturer_id">
					<option value="*"><?php echo $text_select_manufacturer; ?></option>
					<?php foreach($manufacturers as $manufacturer) { ?>
					<option value="<?php echo $manufacturer['manufacturer_id'] ?>"<?php if($filter_manufacturer_id == $manufacturer['manufacturer_id']) echo 'selected="selected"'; ?>><?php echo $manufacturer['name'] ?></option>
					<?php }?>
				  </select>
				</td>
				<td style="width: 20%">
				  <select name="filter_category_id">
					<option value="*"><?php echo $text_select_category; ?></option>
					<?php foreach($categories as $category) { ?>
					<option value="<?php echo $category['category_id'] ?>" <?php if($filter_category_id == $category['category_id']) echo 'selected="selected"'; ?>><?php echo $category['name'] ?></option>
					<?php }?>
				  </select>
				</td>
				<td class="left"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
			</tr>
          </thead>
		</table>
        <?php if ($keyworder) { ?>
	      <?php foreach ($keyworder as $keyword) { ?>
			<table class="listy">
			  <tbody>
				<tr class="tr_<?php echo $keyword['keyworder_id']; ?>"><input type="hidden" name="keyworder_id" value="<?php echo $keyword['keyworder_id']; ?>" />
				<td style="padding: 0; border: none; border-bottom: 1px solid #DDDDDD;">
				<table class="listy" style="margin: 0;">
				<tbody>
				<tr>
					<td class="new_left"><?php echo $column_brand; ?></td>
					<td class="new_left" colspan="2"><?php echo $column_category; ?></td>
					<td class="new_left"><?php echo $text_category_status; ?></td>
					<td class="new_left"><?php echo $text_manufacturer_status; ?></td>
				</tr>
				<tr>
					<td class="left" style="background: #92BDCA;"><b><?php echo $keyword['manufacturer_name']; ?></b> <input type="hidden" name="manufacturer_id" value="<?php echo $keyword['manufacturer_id']; ?>" /></td>
					<td class="left" colspan="2" style="background: #92BDCA;"><b><?php echo $keyword['category_name']; ?></b> <input type="hidden" name="category_id" value="<?php echo $keyword['category_id']; ?>" /></td>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
						  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
						  <select name="infos[<?php echo $language['language_id']; ?>][category_status]">
							<?php if ($keyword['keyworder_description'][$language['language_id']]['category_status'] == 1) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						  </select>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
						  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
						  <select name="infos[<?php echo $language['language_id']; ?>][keyworder_status]">
							<?php if ($keyword['keyworder_description'][$language['language_id']]['keyworder_status'] == 1) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						  </select>
						<?php } ?>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td class="new_left"><?php echo $column_h1; ?></td>
					<td class="new_left"><?php echo $column_title; ?></td>
					<td class="new_left"><?php echo $column_meta_keyword; ?></td>
					<td class="new_left"><?php echo $column_meta_description; ?></td>
					<td class="new_left"><?php echo $column_description; ?></td>
					<td class="new_left">Картинка</td>
				</tr>
				<tr>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> : <? echo $language['code']; ?><br />
							<input type="text" name="infos[<?php echo $language['language_id']; ?>][seo_h1]" value="<?php echo isset($keyword['keyworder_description'][$language['language_id']]) ? $keyword['keyworder_description'][$language['language_id']]['seo_h1'] : ''; ?>" /></br></br>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> : <? echo $language['code']; ?><br />
							<input type="text" name="infos[<?php echo $language['language_id']; ?>][seo_title]" value="<?php echo isset($keyword['keyworder_description'][$language['language_id']]) ? $keyword['keyworder_description'][$language['language_id']]['seo_title'] : ''; ?>" /></br></br>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> : <? echo $language['code']; ?><br />
							<input type="text" name="infos[<?php echo $language['language_id']; ?>][meta_keyword]" value="<?php echo isset($keyword['keyworder_description'][$language['language_id']]) ? $keyword['keyworder_description'][$language['language_id']]['meta_keyword'] : ''; ?>" /></br></br>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> : <? echo $language['code']; ?><br />
							<textarea name="infos[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="2"><?php echo isset($keyword['keyworder_description'][$language['language_id']]) ? $keyword['keyworder_description'][$language['language_id']]['meta_description'] : ''; ?></textarea></br></br>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> : <? echo $language['code']; ?><br />
							<textarea name="infos[<?php echo $language['language_id']; ?>][description]" cols="40" rows="2"><?php echo isset($keyword['keyworder_description'][$language['language_id']]) ? $keyword['keyworder_description'][$language['language_id']]['description'] : ''; ?></textarea></br></br>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach ($languages as $language) { ?>
							<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> : <? echo $language['code']; ?>
							<img src="<?php echo $keyword['keyworder_description'][$language['language_id']]['thumb']; ?>" alt="" id="<?php echo $keyword['keyworder_id']; ?>_infos_<?php echo $language['language_id']; ?>_thumb" /><br />

							<input type="hidden" id="<?php echo $keyword['keyworder_id']; ?>_infos_<?php echo $language['language_id']; ?>_image" name="infos[<?php echo $language['language_id']; ?>][image]" value="<?php echo isset($keyword['keyworder_description'][$language['language_id']]) ? $keyword['keyworder_description'][$language['language_id']]['image'] : ''; ?>" />

							 <a onclick="image_upload('<?php echo $keyword['keyworder_id']; ?>_infos_<?php echo $language['language_id']; ?>_image', '<?php echo $keyword['keyworder_id']; ?>_infos_<?php echo $language['language_id']; ?>_thumb');">Выбрать</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#<?php echo $keyword['keyworder_id']; ?>_infos_<?php echo $language['language_id']; ?>_thumb').attr('src', '<?php echo $no_image; ?>'); $('#<?php echo $keyword['keyworder_id']; ?>_infos_<?php echo $language['language_id']; ?>_image').attr('value', '');">Очист.</a>


							</br></br>
						<?php } ?>
					</td>
				</tr>
				</tbody>
				</table>
				</td>
				<td style="padding: 0; border: 2px solid #DDDDDD;  border-top: 1px solid #DDDDDD; border-left: none; text-align: center;">
					<a class="save button" id="<?php echo $keyword['keyworder_id']; ?>" style="border-radius: 3px; padding: 20px; background: #57B5D1;"><?php echo $button_save; ?></a>
				</td>
				</tr>
			  </tbody>
			</table>
			  <?php } ?>
            <?php } else { ?>
			<table class="listy">
		      <tbody>
				<tr>
					<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
				</tr>
		      </tbody>
			</table>
            <?php } ?>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
<?php if ($this->user->hasPermission('modify', 'module/keyworder')) { ?>
$('.save').bind('click', function() {
	var keyworder_id = $(this).attr("id");

	$.ajax({
		url: 'index.php?route=module/keyworder/saveKeyworder&token=<?php echo $this->session->data['token']; ?>',
		type: 'post',
		dataType: 'json',
		data: $('.tr_' + keyworder_id + ' input[name=\'keyworder_id\']<?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' input[name=\'infos[<?php echo $language['language_id']; ?>][seo_h1]\']<?php } ?><?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' input[name=\'infos[<?php echo $language['language_id']; ?>][seo_title]\']<?php } ?><?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' input[name=\'infos[<?php echo $language['language_id']; ?>][image]\']<?php } ?><?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' input[name=\'infos[<?php echo $language['language_id']; ?>][meta_keyword]\']<?php } ?><?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' textarea[name=\'infos[<?php echo $language['language_id']; ?>][meta_description]\']<?php } ?><?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' textarea[name=\'infos[<?php echo $language['language_id']; ?>][description]\']<?php } ?><?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' select[name=\'infos[<?php echo $language['language_id']; ?>][category_status]\']<?php } ?><?php foreach ($languages as $language) { ?>, .tr_' + keyworder_id + ' select[name=\'infos[<?php echo $language['language_id']; ?>][keyworder_status]\']<?php } ?>'),
		success: function(json) {
			$('.tr_' + keyworder_id + ' > td .left').css('background-color', '#EAF7D9');
			$('.tr_' + keyworder_id + ' > td:last-child').css('background-color', '#EAF7D9');
			$('.tr_' + keyworder_id + ' > td').effect('highlight', {color: '#BBDF8D'}, 3000);
		}
	});
});
<?php } else { ?>
$('.save').bind('click', function() {
	var keyworder_id = $(this).attr("id");

	$('.tr_' + keyworder_id + ' > td .left').css('background', '#FFD1D1');
	$('.tr_' + keyworder_id + ' > td:last-child').css('background', '#FFD1D1');
	$('.tr_' + keyworder_id + ' > td').effect('highlight', {color: '#F8ACAC'}, 3000);
});
<?php } ?>
setInterval (function () {
    $('.breadcrumb + .success').fadeOut('slow');
}, 7000);
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=module/keyworder&token=<?php echo $this->session->data['token']; ?>';

	var category_id = $('select[name=\'filter_category_id\']').attr('value');

	if (category_id != '*') {
		url += '&filter_category_id=' + encodeURIComponent(category_id);
	}

	var manufacturer_id = $('select[name=\'filter_manufacturer_id\']').attr('value');

	if (manufacturer_id != '*') {
		url += '&filter_manufacturer_id=' + encodeURIComponent(manufacturer_id);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: 'Выбери картинку',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
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
<?php echo $footer; ?>
