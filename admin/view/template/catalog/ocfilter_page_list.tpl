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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/ocfilter.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
				<a onclick="location = '<?php echo $insert; ?>'" class="button">Добавить страницу</a>
				<a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a>
        <a href="<?php echo $cancel; ?>" class="button">Вернуться к фильтрам</a>
			</div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="left">Название</td>
              <td class="left">Категория</td>
              <td class="left">Параметры фильтра</td>
              <td class="left">Статус</td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($pages) { ?>
            <?php foreach ($pages as $page) { ?>
            <tr>
              <td class="center">
								<?php if ($page['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $page['ocfilter_page_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $page['ocfilter_page_id']; ?>" />
                <?php } ?>
							</td>
              <td class="left"><?php echo $page['title']; ?></td>
              <td class="left"><?php echo $page['category']; ?></td>
              <td class="left"><?php echo $page['ocfilter_params']; ?></td>
              <td class="right">
								<?php if ($page['status']) { ?>
								<?php echo $text_enabled; ?>
								<?php } else { ?>
								<?php echo $text_disabled; ?>
								<?php } ?>
							</td>
              <td class="right">
                <?php foreach ($page['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>