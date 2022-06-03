<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<?php if (OC_V2) { ?>
		<div class="page-header">
		    <div class="container-fluid">
		      <div class="pull-right">
		        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			  </div>
		      <h1><?php echo $_language->get('module_title'); ?></h1>
		      <ul class="breadcrumb">
		        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
		        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		        <?php } ?>
		      </ul>
		    </div>
		  </div>
	<?php } else { ?>
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
	<?php } ?>
<div class="container-fluid">
  <?php if (isset($success) && $success) { ?><div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><script type="text/javascript">setTimeout("$('.alert-success').slideUp();",5000);</script><?php } ?>
  <?php if (isset($info) && $info) { ?><div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
  <?php if (isset($error) && $error) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
  <?php if (isset($error_warning) && $error_warning) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
<div class="<?php if(!OC_V2) echo 'box'; ?> panel panel-default">
  <div class="heading order_head">
    <h1><img style="vertical-align:top;padding-right:4px" src="view/full_product_path/img/icon-big.png"/> <?php echo $_language->get('module_title'); ?></h1>
    <div class="buttons"><a class="button" onclick="$('#form').submit();" class="button blue"><?php echo $button_save; ?></a><a class="button" onclick="location = '<?php echo $cancel; ?>';" class="button red"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content panel-body">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <!-- start fpp -->
        <tr>
			<td><?php echo $_language->get('text_fpp_mode'); ?></td>
			<td>
				<select name="full_product_path_mode">
					<option value="0" <?php if($full_product_path_mode == '0') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_0'); ?></option>
					<option value="1" <?php if($full_product_path_mode == '1') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_1'); ?></option>
					<option value="2" <?php if($full_product_path_mode == '2') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_2'); ?></option>
					<option value="3" <?php if($full_product_path_mode == '3') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_3'); ?></option>
				</select>
			</td>
			<td style="padding-left:50px"><?php echo $_language->get('text_fpp_mode_help'); ?></td>
        </tr>
		<tr>
			<td><?php echo $_language->get('text_fpp_depth'); ?></td>
			<td>
				<select name="full_product_path_depth">
					<option value="0" <?php if($full_product_path_depth == '0') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_unlimited'); ?></option>
					<option value="1" <?php if($full_product_path_depth == '1') echo 'selected="selected"'; ?>>1</option>
					<option value="2" <?php if($full_product_path_depth == '2') echo 'selected="selected"'; ?>>2</option>
					<option value="3" <?php if($full_product_path_depth == '3') echo 'selected="selected"'; ?>>3</option>
					<option value="4" <?php if($full_product_path_depth == '4') echo 'selected="selected"'; ?>>4</option>
					<option value="5" <?php if($full_product_path_depth == '5') echo 'selected="selected"'; ?>>5</option>
					<option value="6" <?php if($full_product_path_depth == '6') echo 'selected="selected"'; ?>>6</option>
					<option value="7" <?php if($full_product_path_depth == '7') echo 'selected="selected"'; ?>>7</option>
				</select>
			</td>
			<td style="padding-left:50px"><?php echo $_language->get('text_fpp_depth_help'); ?></td>
        </tr>
		<tr>
			<td><?php echo $_language->get('text_fpp_breadcrumbs_fix'); ?></td>
			<td>
				<select name="full_product_path_breadcrumbs">
					<option value="0" <?php if($full_product_path_breadcrumbs == '0') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_breadcrumbs_0'); ?></option>
					<option value="1" <?php if($full_product_path_breadcrumbs == '1') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_breadcrumbs_1'); ?></option>
					<option value="2" <?php if($full_product_path_breadcrumbs == '2') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_breadcrumbs_2'); ?></option>
				</select>
			</td>
			<td style="padding-left:50px"><?php echo $_language->get('text_fpp_breadcrumbs_help'); ?></td>
        </tr>
		<tr>
			<td><?php echo $_language->get('text_fpp_bc_mode'); ?></td>
			<td>
				<select name="full_product_path_bc_mode">
					<option value="0" <?php if($full_product_path_bc_mode == '0') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_0'); ?></option>
					<option value="1" <?php if($full_product_path_bc_mode == '1') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_1'); ?></option>
					<option value="2" <?php if($full_product_path_bc_mode == '2') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_2'); ?></option>
					<option value="3" <?php if($full_product_path_bc_mode == '3') echo 'selected="selected"'; ?>><?php echo $_language->get('text_fpp_mode_3'); ?></option>
				</select>
			</td>
			<td style="padding-left:50px"><?php echo $_language->get('text_fpp_mode_help'); ?></td>
        </tr>
        <tr>
			<td><?php echo $_language->get('text_fpp_bypasscat'); ?></td>
			<td><input class="switch" type="checkbox" name="full_product_path_bypasscat" id="full_product_path_bypasscat" value="1" <?php if($full_product_path_bypasscat) echo 'checked="checked"'; ?> /></td>
			<td style="padding-left:50px"><?php echo $_language->get('text_fpp_bypasscat_help'); ?></td>
        </tr>
		<tr>
          <td><?php echo $_language->get('text_fpp_directcat'); ?></td>
          <td><input class="switch" type="checkbox" name="full_product_path_directcat" id="full_product_path_directcat" value="1" <?php if($full_product_path_directcat) echo 'checked="checked"'; ?> /></td>
          <td style="padding-left:50px"><?php echo $_language->get('text_fpp_directcat_help'); ?></td>
        </tr>
        <tr>
          <td><?php echo $_language->get('text_fpp_homelink'); ?></td>
          <td><input class="switch" type="checkbox" name="full_product_path_homelink" id="full_product_path_homelink" value="1" <?php if($full_product_path_homelink) echo 'checked="checked"'; ?> /></td>
          <td style="padding-left:50px"><?php echo $_language->get('text_fpp_homelink_help'); ?></td>
        </tr>
        <tr>
			<td><?php echo $_language->get('entry_category'); ?></td>
			<td colspan="2"><div class="scrollbox" style="width:90%;height:350px">
					<?php $class = 'odd'; ?>
					<?php foreach ($categories as $category) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<div class="<?php echo $class; ?>">
						<?php if (in_array($category['category_id'], $full_product_path_categories)) { ?>
						<input id="<?php echo $category['category_id']; ?>" class="checkbox" type="checkbox" name="full_product_path_categories[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
						<label for="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
						<?php } else { ?>
						<input id="<?php echo $category['category_id']; ?>" class="checkbox" type="checkbox" name="full_product_path_categories[]" value="<?php echo $category['category_id']; ?>" />
						<label for="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
				<a class="select_all" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $_language->get('text_select_all'); ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_language->get('text_unselect_all'); ?></a></td>
		</tr>
        <!-- end fpp -->
      </table>
    </form>
</div>
</div>
<script type="text/javascript"><!--
$('input.switch').iToggle({easing: 'swing',speed: 200});
//--></script> 