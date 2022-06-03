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
      <h1><?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> Короткое название</td>
            <td><input type="text" name="legalperson_name" value="<?php echo $legalperson_name; ?>" style="width:250px;" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><span class="required">*</span> Название для 1С</td>
            <td><input type="text" name="legalperson_name_1C" value="<?php echo $legalperson_name_1C; ?>" style="width:250px;" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> Реквизиты получателя</td>
            <td><textarea name="legalperson_desc" rows="10" cols="100"><?php echo $legalperson_desc; ?></textarea></td>
          </tr>
		    <tr>
            <td>Реквизиты получателя 2</td>
            <td><textarea name="legalperson_additional" rows="10" cols="100"><?php echo $legalperson_additional; ?></textarea></td>
          </tr>
		    <tr>
            <td>Файл с печатью</td>
            <td><input type="text" name="legalperson_print" value="<?php echo $legalperson_print; ?>" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> Страна</td>
            <td>
				<select name="legalperson_country_id">
                  <?php foreach ($countries as $country) { ?>
                  <?php  if ($country['country_id'] == $legalperson_country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
			</td>
          </tr>
		  <tr>
			
		  </tr>
          <tr>
            <td>Для выставления счетов</td>
            <td><select name="legalperson_legal">
                <?php if ($legalperson_legal) { ?>
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
<?php echo $footer; ?>