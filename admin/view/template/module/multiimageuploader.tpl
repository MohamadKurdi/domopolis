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
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_folder; ?></td>
          <td>
            <?php echo DIR_IMAGE."data/";?><input type="text" name="multiimageuploader_folder" value="<?php echo $multiimageuploader_folder;?>"/>
            <?php if ($error_folder) { ?>
            <span class="error"><?php echo $error_folder; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_segmet; ?></td>
          <td>
           <select name="multiimageuploader_segment">
            <option value="" <?php if (!$multiimageuploader_segment){ echo " selected";}?>><?php echo $entry_segmet_by_none;?></option>
            <option value="date" <?php if ($multiimageuploader_segment == "date"){ echo " selected";}?>><?php echo $entry_segmet_by_date;?></option>
           </select>
           </td>
        </tr>
        <tr>
          <td><?php echo $entry_delete_def_image; ?></td>
          <td>
           <select name="multiimageuploader_deletedef">
            <option value="0" <?php if (!$multiimageuploader_deletedef){ echo " selected";}?>><?php echo $text_no;?></option>
            <option value="1" <?php if ($multiimageuploader_deletedef){ echo " selected";}?>><?php echo $text_yes;?></option>
           </select>
           </td>
        </tr>

      </table>
     
    </form>
  </div>
</div>

<?php echo $footer; ?>