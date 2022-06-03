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
    <div class="heading order_head">
      <h1><?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td class="left">ID</td>
              <td class="left">Страна</td>
			  <td class="left">Название</td>
			  <td class="left">Название 1С</td>
			  <td class="left">Реквизиты</td>
			  <td class="left">Для счетов</td>
			  <td class="left">Файл печати</td>
              <td class="right"></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($legalpersons) { ?>
            <?php foreach ($legalpersons as $legalperson) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($legalperson['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $legalperson['legalperson_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $legalperson['legalperson_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $legalperson['legalperson_id']; ?></td>
              <td class="left"><img src="view/image/flags/<? echo mb_strtolower($legalperson['legalperson_country_iso']) ?>.png" title="<? echo mb_strtolower($legalperson['legalperson_country_iso']) ?>" /> </td>
              <td class="right"><?php echo $legalperson['legalperson_name']; ?></td>
			  <td class="right"><?php echo $legalperson['legalperson_name_1C']; ?></td>
			  <td class="right" style="font-size:10px;"><?php echo $legalperson['legalperson_desc']; ?></td>
			  <td class="right"><?php echo $legalperson['legalperson_legal']?'Да':'Нет'; ?></td>
			   <td class="right"><?php echo $legalperson['legalperson_print']; ?></td>
              <td class="right"><?php foreach ($legalperson['action'] as $action) { ?>
              <a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
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
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>