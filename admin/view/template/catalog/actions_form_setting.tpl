<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
	<div class="warning">
		<?php echo $error_warning; ?>
	</div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/actions.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</div>
		<div class="content">

			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-seo"><?php echo $tab_seo; ?></a></div>
				<div id="tab-general">
					<div id="vtab-general" class="vtabs">
            			<a href="#tab-general-1"><?php echo $text_actions_list_setting; ?>&nbsp;</a>
            			<a href="#tab-general-2"><?php echo $text_actions_page_setting; ?>&nbsp;</a>
            			<a href="#tab-general-3"><?php echo $text_actions_module_setting; ?>&nbsp;</a>
					</div>
					
					<div id="tab-general-1" class="vtabs-content">
						<!-- <table class="form"><tr><td>
					<div><strong><?php echo $text_actions_setting; ?></strong></div>	
					</td></tr></table> -->
					
					<table class="form">
						<tr>
							<td><?php echo $entry_actions_limit; ?></td>
							<td class="right">
							<input type="text" name="actions_setting[actions_limit]" value="<?php echo $actions_setting['actions_limit']; ?>" size="3" />
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_size; ?></td>
							<td>
							<input type="text" name="actions_setting[image_width]" value="<?php echo $actions_setting['image_width']; ?>" size="3" />
							x
							<input type="text" name="actions_setting[image_height]" value="<?php echo $actions_setting['image_height']; ?>" size="3" /></td>
						</tr>
						<tr>
			              <td><?php echo $entry_show_image; ?></td>
			              <td><select name="actions_setting[show_image]">
			                  <?php if ($actions_setting['show_image']) { ?>
			                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			                  <option value="0"><?php echo $text_disabled; ?></option>
			                  <?php } else { ?>
			                  <option value="1"><?php echo $text_enabled; ?></option>
			                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			                  <?php } ?>
			                </select></td>
			            </tr>
			            <tr>
			              <td><?php echo $entry_show_date; ?></td>
			              <td><select name="actions_setting[show_date]">
			                  <?php if ($actions_setting['show_date']) { ?>
			                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			                  <option value="0"><?php echo $text_disabled; ?></option>
			                  <?php } else { ?>
			                  <option value="1"><?php echo $text_enabled; ?></option>
			                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			                  <?php } ?>
			                </select></td>
			            </tr>
					</table>
					
					</div>
					<div id="tab-general-2" class="vtabs-content">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_relproducts_image_size; ?></td>
							<td>
							<input type="text" name="actions_setting[image_relproduct_width]" value="<?php echo $actions_setting['image_relproduct_width']; ?>" size="3" />
							x
							<input type="text" name="actions_setting[image_relproduct_height]" value="<?php echo $actions_setting['image_relproduct_height']; ?>" size="3" /></td>
						</tr>
					    <td><?php echo $entry_show_date; ?></td>
			              <td><select name="actions_setting[show_actions_date]">
			                  <?php if ($actions_setting['show_actions_date']) { ?>
			                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			                  <option value="0"><?php echo $text_disabled; ?></option>
			                  <?php } else { ?>
			                  <option value="1"><?php echo $text_enabled; ?></option>
			                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			                  <?php } ?>
			                </select></td>
			            </tr>
			            </table>	
					</div>
					<div id="tab-general-3" class="vtabs-content">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_size; ?></td>
							<td>
							<input type="text" name="actions_setting[image_module_width]" value="<?php echo $actions_setting['image_module_width']; ?>" size="3" />
							x
							<input type="text" name="actions_setting[image_module_height]" value="<?php echo $actions_setting['image_module_height']; ?>" size="3" /></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_maxlen; ?></td>
							<td><input type="text" name="actions_setting[module_maxlen]" value="<?php echo $actions_setting['module_maxlen']; ?>" size="3" /></td>
						</tr>
						<tr>
			              <td><?php echo $entry_show_image; ?></td>
			              <td><select name="actions_setting[show_module_image]">
			                  <?php if ($actions_setting['show_module_image']) { ?>
			                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			                  <option value="0"><?php echo $text_disabled; ?></option>
			                  <?php } else { ?>
			                  <option value="1"><?php echo $text_enabled; ?></option>
			                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			                  <?php } ?>
			                </select></td>
			            </tr>
			            <tr>
			              <td><?php echo $entry_show_date; ?></td>
			              <td><select name="actions_setting[show_module_date]">
			                  <?php if ($actions_setting['show_module_date']) { ?>
			                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
			                  <option value="0"><?php echo $text_disabled; ?></option>
			                  <?php } else { ?>
			                  <option value="1"><?php echo $text_enabled; ?></option>
			                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			                  <?php } ?>
			                </select></td>
			            </tr>
			            </table>
			            
			            </div>
			            
			         </div>
			         <div id="tab-seo">
			         	<div id="languages" class="htabs">
				            <?php foreach ($languages as $language) { ?>
				            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				            <?php } ?>
				          </div>
				          <?php foreach ($languages as $language) { ?>
				          <div id="language<?php echo $language['language_id']; ?>">
				            <table class="form">
				              <tr>
				                <td><?php echo $entry_h1; ?></td>
				                <td><input type="text" name="actions_setting[seo][<?php echo $language['language_id']; ?>][h1]" maxlength="255" size="100" value="<?php echo isset($actions_setting['seo'][$language['language_id']]) ? $actions_setting['seo'][$language['language_id']]['h1'] : ''; ?>" /></td>
				              </tr>
				              <tr>
				                <td><?php echo $entry_title; ?></td>
				                <td><input type="text" name="actions_setting[seo][<?php echo $language['language_id']; ?>][title]" maxlength="255" size="100" value="<?php echo isset($actions_setting['seo'][$language['language_id']]) ? $actions_setting['seo'][$language['language_id']]['title'] : ''; ?>" /></td>
				              </tr>
				              <tr>
				                <td><?php echo $entry_meta_keywords; ?></td>
				                <td><input type="text" name="actions_setting[seo][<?php echo $language['language_id']; ?>][keywords]" maxlength="255" size="100" value="<?php echo isset($actions_setting['seo'][$language['language_id']]) ? $actions_setting['seo'][$language['language_id']]['keywords'] : ''; ?>" /></td>
				              </tr>
				              <tr>
				                <td><?php echo $entry_meta_description; ?></td>
				                <td><textarea name="actions_setting[seo][<?php echo $language['language_id']; ?>][description]" cols="100" rows="4"><?php echo isset($actions_setting['seo'][$language['language_id']]) ? $actions_setting['seo'][$language['language_id']]['description'] : ''; ?></textarea></td>
				              </tr>
				            </table>
				          </div>
				          <?php } ?>
			         </div>
			</form>

		</div>
	</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs();
$('#vtab-general a').tabs(); 
//--></script> 
<?php echo $footer; ?>