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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons">
		<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="form">
			<tbody>
				
	<td>
	<label>
	<?php if ($on_off == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_off" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_off">
	<?php }?>
	<?php echo $on_off_text;?>
	</label>
	</td>
	
	<tr> <!-- куки -->
		
    <td><b><?php echo $entry_plpopup_cookie; ?></b></td>
    <td><input name="config_plpopup_cookie" size="10" value="<?php echo $config_plpopup_cookie; ?>"/></td>
    
    </tr>
    
    <tr> <!-- разрешение экрана -->
		
    <td><b><?php echo $entry_plpopup_sr; ?></b></td>
    <td><input name="config_plpopup_sr" size="10" value="<?php echo $config_plpopup_sr; ?>"/></td>
    
    </tr>
    
    <tr> <!-- таймаут -->
		
    <td><b><?php echo $entry_plpopup_tm; ?></b></td>
    <td><input name="config_plpopup_tm" size="10" value="<?php echo $config_plpopup_tm; ?>"/></td>
    
    </tr>
    
    
    <tr> <!-- фон -->
		
    <td><b><?php echo $entry_plpopup_pic; ?></b></td>
    <td><input name="config_plpopup_pic" size="45" value="<?php echo $config_plpopup_pic; ?>"/></td>
    
    <td><?php echo $entry_plpopup_bg; ?></td>
    <td><input type="color" name="config_plpopup_bg" size="10" value="<?php echo $config_plpopup_bg; ?>"/></td>
    
    </tr>
    
    <tr> <!-- размер окна -->
		
    <td><b><?php echo $entry_plpopup_am; ?></b></td>
    <td><?php echo $entry_plpopup_amo; ?></td> 
    <td><?php echo $entry_plpopup_amw; ?></td>
    <td><input name="config_plpopup_amw" size="2" value="<?php echo $config_plpopup_amw; ?>"/></td>
    <td><?php echo $entry_plpopup_amh; ?></td>
    <td><input name="config_plpopup_amh" size="2" value="<?php echo $config_plpopup_amh; ?>"/></td>
    
    </tr>
								
	<tr> <!-- заголовок 1 -->
		
    <td><b><?php echo $entry_plpopup_headtop; ?></b></td>
    <td><textarea name="config_plpopup_headtop" cols="50" rows="10" ><?php echo $config_plpopup_headtop; ?></textarea>
    <label>
	<?php if ($on_offlt == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlt" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlt">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linktop; ?></b><br />
    <input name="config_plpopup_linktop" size="50" value="<?php echo $config_plpopup_linktop; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_headtopsize; ?> </td>
    <td><input name="config_plpopup_headtopsize" size="2" value="<?php echo $config_plpopup_headtopsize; ?>"/></td>
    
    <td><?php echo $entry_plpopup_headtopcolor; ?> </td>
    <td><input type="color" name="config_plpopup_headtopcolor" size="2" value="<?php echo $config_plpopup_headtopcolor; ?>"/></td>
    
    <td><?php echo $entry_plpopup_headtopfw; ?> </td>
    <td><input name="config_plpopup_headtopfw" size="2" value="<?php echo $config_plpopup_headtopfw; ?>"/></td>
    
    <td><?php echo $entry_plpopup_headtoptype; ?> </td>
    <td><input type="type" name="config_plpopup_headtoptype" size="2" value="<?php echo $config_plpopup_headtoptype; ?>"/></td>
        
    <td><?php echo $entry_plpopup_headtopleft; ?></td>
    <td><input name="config_plpopup_headtopleft" size="2" value="<?php echo $config_plpopup_headtopleft; ?>"/></td>
    
    <td><?php echo $entry_plpopup_headtopheight; ?></td>
    <td><input name="config_plpopup_headtopheight" size="2" value="<?php echo $config_plpopup_headtopheight; ?>"/></td>
    
    </tr>
    
    
    <tr> <!-- заголовок 2 -->
		
    <td><b><?php echo $entry_plpopup_headtop2; ?></b></td>
    <td><textarea name="config_plpopup_headtop2" cols="50" rows="10" ><?php echo $config_plpopup_headtop2 ?></textarea>
    <label>
	<?php if ($on_offlt2 == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlt2" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlt2">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linktop2; ?></b><br />
    <input name="config_plpopup_linktop2" size="50" value="<?php echo $config_plpopup_linktop2; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_headtopsize2; ?> </td>
    <td><input name="config_plpopup_headtopsize2" size="2" value="<?php echo $config_plpopup_headtopsize2; ?>"/></td>
    
	<td><?php echo $entry_plpopup_headtopcolor2; ?> </td>
    <td><input type="color" name="config_plpopup_headtopcolor2" size="2" value="<?php echo $config_plpopup_headtopcolor2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_headtopfw2; ?> </td>
    <td><input name="config_plpopup_headtopfw2" size="2" value="<?php echo $config_plpopup_headtopfw2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_headtoptype2; ?> </td>
    <td><input type="type" name="config_plpopup_headtoptype2" size="2" value="<?php echo $config_plpopup_headtoptype2; ?>"/></td>
     
    
    <td><?php echo $entry_plpopup_headtopleft2; ?></td>
    <td><input name="config_plpopup_headtopleft2" size="2" value="<?php echo $config_plpopup_headtopleft2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_headtopheight2; ?></td>
    <td><input name="config_plpopup_headtopheight2" size="2" value="<?php echo $config_plpopup_headtopheight2; ?>"/></td>

    </tr>
    
    <tr> <!-- текст 1 -->
    
    <td><b><?php echo $entry_plpopup_home; ?></b></td>
    <td><textarea name="config_plpopup_home" cols="50" rows="10" ><?php echo $config_plpopup_home; ?></textarea>
    <label>
	<?php if ($on_offlh == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linkhome; ?></b><br />
    <input name="config_plpopup_linkhome" size="50" value="<?php echo $config_plpopup_linkhome; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_homesize; ?> </td>
    <td><input name="config_plpopup_homesize" size="2" value="<?php echo $config_plpopup_homesize; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homecolor; ?> </td>
    <td><input type="color" name="config_plpopup_homecolor" size="2" value="<?php echo $config_plpopup_homecolor; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homefw; ?> </td>
    <td><input name="config_plpopup_homefw" size="2" value="<?php echo $config_plpopup_homefw; ?>"/></td>
    
    <td><?php echo $entry_plpopup_hometype; ?> </td>
    <td><input type="type" name="config_plpopup_hometype" size="2" value="<?php echo $config_plpopup_hometype; ?>"/></td>
        
    <td><?php echo $entry_plpopup_homeleft; ?> </td>
    <td><input name="config_plpopup_homeleft" size="2" value="<?php echo $config_plpopup_homeleft; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homeheight; ?> </td>
    <td><input name="config_plpopup_homeheight" size="2" value="<?php echo $config_plpopup_homeheight; ?>"/></td>
    
    </tr>
    
    
    <tr> <!-- текст 2 -->
		
    <td><b><?php echo $entry_plpopup_home2; ?></b></td>
    <td><textarea name="config_plpopup_home2" cols="50" rows="10" ><?php echo $config_plpopup_home2; ?></textarea>
    <label>
	<?php if ($on_offlh2 == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh2" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh2">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linkhome2; ?></b><br />
    <input name="config_plpopup_linkhome2" size="50" value="<?php echo $config_plpopup_linkhome2; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_homesize2; ?> </td>
    <td><input name="config_plpopup_homesize2" size="2" value="<?php echo $config_plpopup_homesize2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homecolor2; ?> </td>
    <td><input type="color" name="config_plpopup_homecolor2" size="2" value="<?php echo $config_plpopup_homecolor2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homefw2; ?> </td>
    <td><input name="config_plpopup_homefw2" size="2" value="<?php echo $config_plpopup_homefw2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_hometype2; ?> </td>
    <td><input type="type" name="config_plpopup_hometype2" size="2" value="<?php echo $config_plpopup_hometype2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homeleft2; ?> </td>
    <td><input name="config_plpopup_homeleft2" size="2" value="<?php echo $config_plpopup_homeleft2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homeheight2; ?> </td>
    <td><input name="config_plpopup_homeheight2" size="2" value="<?php echo $config_plpopup_homeheight2; ?>"/></td>
    
    </tr>
    
    
    <tr> <!-- текст 3 -->
		
    <td><b><?php echo $entry_plpopup_home3; ?></b></td>
    <td><textarea name="config_plpopup_home3" cols="50" rows="10" ><?php echo $config_plpopup_home3; ?></textarea>
    <label>
	<?php if ($on_offlh3 == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh3" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh3">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linkhome3; ?></b><br />
    <input name="config_plpopup_linkhome3" size="50" value="<?php echo $config_plpopup_linkhome3; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_homesize3; ?> </td>
    <td><input name="config_plpopup_homesize3" size="2" value="<?php echo $config_plpopup_homesize3; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homecolor3; ?> </td>
    <td><input type="color" name="config_plpopup_homecolor3" size="2" value="<?php echo $config_plpopup_homecolor3; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homefw3; ?> </td>
    <td><input name="config_plpopup_homefw3" size="2" value="<?php echo $config_plpopup_homefw3; ?>"/></td>
    
    <td><?php echo $entry_plpopup_hometype3; ?> </td>
    <td><input type="type" name="config_plpopup_hometype3" size="2" value="<?php echo $config_plpopup_hometype3; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homeleft3; ?> </td>
    <td><input name="config_plpopup_homeleft3" size="2" value="<?php echo $config_plpopup_homeleft3; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homeheight3; ?> </td>
    <td><input name="config_plpopup_homeheight3" size="2" value="<?php echo $config_plpopup_homeheight3; ?>"/></td>
    
    </tr>
    
    
    <tr> <!-- текст 4 -->
		
    <td><b><?php echo $entry_plpopup_home4; ?></b></td>
    <td><textarea name="config_plpopup_home4" cols="50" rows="10" ><?php echo $config_plpopup_home4; ?></textarea>
    <label>
	<?php if ($on_offlh4 == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh4" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlh4">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linkhome4; ?></b><br />
    <input name="config_plpopup_linkhome4" size="50" value="<?php echo $config_plpopup_linkhome4; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_homesize4; ?> </td>
    <td><input name="config_plpopup_homesize4" size="2" value="<?php echo $config_plpopup_homesize4; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homecolor4; ?> </td>
    <td><input type="color" name="config_plpopup_homecolor4" size="2" value="<?php echo $config_plpopup_homecolor4; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homefw4; ?> </td>
    <td><input name="config_plpopup_homefw4" size="2" value="<?php echo $config_plpopup_homefw4; ?>"/></td>
    
    <td><?php echo $entry_plpopup_hometype4; ?> </td>
    <td><input type="type" name="config_plpopup_hometype4" size="2" value="<?php echo $config_plpopup_hometype4; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homeleft4; ?> </td>
    <td><input name="config_plpopup_homeleft4" size="2" value="<?php echo $config_plpopup_homeleft4; ?>"/></td>
    
    <td><?php echo $entry_plpopup_homeheight4; ?> </td>
    <td><input name="config_plpopup_homeheight4" size="2" value="<?php echo $config_plpopup_homeheight4; ?>"/></td>
    
    </tr>
    
    
    
  <tr> <!-- подвал 1 -->
		
    <td><b><?php echo $entry_plpopup_footer; ?></b></td>
    <td><textarea name="config_plpopup_footer" cols="50" rows="10" ><?php echo $config_plpopup_footer; ?></textarea>
    <label>
	<?php if ($on_offlf == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlf" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlf">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linkfooter; ?></b><br />
    <input name="config_plpopup_linkfooter" size="50" value="<?php echo $config_plpopup_linkfooter; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_footersize; ?> </td>
    <td><input name="config_plpopup_footersize" size="2" value="<?php echo $config_plpopup_footersize; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footercolor; ?> </td>
    <td><input type="color" name="config_plpopup_footercolor" size="2" value="<?php echo $config_plpopup_footercolor; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footerfw; ?> </td>
    <td><input name="config_plpopup_footerfw" size="2" value="<?php echo $config_plpopup_footerfw; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footertype; ?> </td>
    <td><input type="type" name="config_plpopup_footertype" size="2" value="<?php echo $config_plpopup_footertype; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footerleft; ?></td>
    <td><input name="config_plpopup_footerleft" size="2" value="<?php echo $config_plpopup_footerleft; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footerheight; ?></td>
    <td><input name="config_plpopup_footerheight" size="2" value="<?php echo $config_plpopup_footerheight; ?>"/></td>
    
    </tr>
    
    
    <tr> <!-- подвал 2 -->
    
    <td><b><?php echo $entry_plpopup_footer2; ?></b></td>
    <td><textarea name="config_plpopup_footer2" cols="50" rows="10" ><?php echo $config_plpopup_footer2; ?></textarea>
    <label>
	<?php if ($on_offlf2 == true) {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlf2" checked> 
	<?php } else {?>
	<input type="checkbox" style="vertical-align: middle;" name="on_offlf2">
	<?php }?>
	</label>
    <b><?php echo $entry_plpopup_linkfooter2; ?></b><br />
    <input name="config_plpopup_linkfooter2" size="50" value="<?php echo $config_plpopup_linkfooter2; ?>"/>
    </td>
    
    <td><?php echo $entry_plpopup_footersize2; ?> </td>
    <td><input name="config_plpopup_footersize2" size="2" value="<?php echo $config_plpopup_footersize2; ?>"/></td>
    
	<td><?php echo $entry_plpopup_footercolor2; ?> </td>
    <td><input type="color" name="config_plpopup_footercolor2" size="2" value="<?php echo $config_plpopup_footercolor2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footerfw2; ?> </td>
    <td><input name="config_plpopup_footerfw2" size="2" value="<?php echo $config_plpopup_footerfw2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footertype2; ?> </td>
    <td><input type="type" name="config_plpopup_footertype2" size="2" value="<?php echo $config_plpopup_footertype2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footerleft2; ?></td>
    <td><input name="config_plpopup_footerleft2" size="2" value="<?php echo $config_plpopup_footerleft2; ?>"/></td>
    
    <td><?php echo $entry_plpopup_footerheight2; ?></td>
    <td><input name="config_plpopup_footerheight2" size="2" value="<?php echo $config_plpopup_footerheight2; ?>"/></td>

    </tr>
      
	<tr>
	<td><b><?php echo $entry_plpopup_style; ?></b></td>
    <td>
	<textarea name="config_plpopup_style" cols="50" rows="15" ><?php echo $config_plpopup_style; ?></textarea>              
    </td>
    </tr>
	
			</tbody>
		</table>
		<table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left"><select name="plpopup_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="plpopup_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>  
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>    
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
               </select></td>
            <td class="left"><select name="plpopup_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="plpopup_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
          </tr>
        </tfoot>
		</table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="plpopup_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="plpopup_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="plpopup_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="plpopup_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>
<?php echo $footer; ?>
