<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/adminmattimeo.css" />
<script src="view/javascript/mattimeo/bootstrap.min.js"></script>
<div id="content" class="matt-mod">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
<div class="navbar">
        <div class="navbar-inner">
				<span class="brand"><?php echo $heading_title; ?></span>


				<div class="buttons pull-right">
                <a onclick="$('#form').submit();" class="btn btn-success btn-large"><?php echo $button_save; ?></a>
                <a onclick="buttonApply();" class="btn btn-success btn-large"><?php echo $text_apply; ?></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-large"><?php echo $button_cancel; ?></a></div>
    </div>
    </div>
    <div class="content categorymodul">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="newsform">
        <table id="module" class="all_topmenulink">
          <thead>
            <tr>
			  <td><?php echo $entry_image_category; ?></td>
              <td><?php echo $entry_image_subcategory; ?></td>
              <td><?php echo $entry_image_products; ?></td>
              <td><?php echo $item_limit; ?></td>
              <td><?php echo $entry_layout; ?></td>
              <td><?php echo $entry_position; ?></td>
              <td><?php echo $entry_status; ?></td>
              <td colspan="2"><?php echo $entry_sort_order; ?></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) {?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
           
              <td><input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][image_category_width]" value="<?php echo $module['image_category_width']; ?>" class="shortfield" >
                <input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][image_category_height]" value="<?php echo $module['image_category_height']; ?>" class="shortfield">
                <?php if (isset($error_category_image[$module_row])) { ?>
                <span class="error"><?php echo $error_category_image[$module_row]; ?></span>
                <?php } ?></td>
                <td><input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][image_subcategory_width]" value="<?php echo $module['image_subcategory_width']; ?>" class="shortfield" >
                <input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][image_subcategory_height]" value="<?php echo $module['image_subcategory_height']; ?>" class="shortfield">
                <?php if (isset($error_category[$module_row])) { ?>
                <span class="error"><?php echo $error_category[$module_row]; ?></span>
                <?php } ?></td>
                <td><input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][image_width]" value="<?php echo $module['image_width']; ?>" class="shortfield" >
                  <input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][image_height]" value="<?php echo $module['image_height']; ?>" class="shortfield">
                  <?php if (isset($error_image[$module_row])) { ?>
                  <span class="error"><?php echo $error_image[$module_row]; ?></span>
                  <?php } ?></td>
                 
              <td><input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" class="shortfield">
			    <?php if (isset($error_numproduct[$module_row])) { ?>
                <span class="error"><?php echo $error_numproduct[$module_row]; ?></span>
                <?php } ?></td>
			  <td><select name="mattimeomenucategory_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td><select name="mattimeomenucategory_module[<?php echo $module_row; ?>][position]">
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
               <td>
              <select name="mattimeomenucategory_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>                </td>
              <td><input type="text" name="mattimeomenucategory_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" class="shortfield"></td>
              <td><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn btn-danger">&times;</a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td><a onclick="addModule();" class="btn btn-success"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
        
        <!-- start tab -->  
        <table id="tab" class="all_topmenulink">
          <thead>
            <tr>
              <td ><?php echo $item_products_from; ?></td> 
              <td><?php echo $item_subcateg; ?></td>
              <td><?php echo $item_products; ?></td>
			  <td style="width:15%"><?php echo $item_image; ?></td>
			  <td style="width:45%"><?php echo $item_title; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $tab_row = 0; ?>
          <?php foreach ($tabs as $tab) { ?>
          <tbody id="tab-row<?php echo $tab_row; ?>">
            <tr>	 
              
			  <td><select name="mattimeomenucategory_tab[<?php echo $tab_row; ?>][category_id]">
				<?php foreach ($categories as $category) { ?>
					<?php if($category['category_id'] == $tab['category_id']){ ?>
						<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
					<?php }else{ ?>
						<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
					<?php } ?>
				<?php } ?>
			  </select></td>
              <td>
                  
                  <?php 
			$ar 	= array( 'On' => '1', 'Off' => '0'); 
			$valueq 	= $tab['subcateg']; 
            $name = 'mattimeomenucategory_tab[' . $tab_row . '][subcateg]'; 
            $id = 'subcateg'.$tab_row;
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>  <?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
           <br /><br /> <?php echo $item_column; ?><br />
            <?php 
			$ar 	= array( '1' => '1', '2' => '0'); 
			$valueq 	= $tab['column']; 
            $name = 'mattimeomenucategory_tab[' . $tab_row . '][column]'; 
            $id = 'column'.$tab_row;
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>  <?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
 
				</td>
               <td>
                  
                  <?php 
			$ar 	= array(  'On' => '1', 'Off' => '0'); 
			$valueq 	= $tab['showproducts']; 
            $name = 'mattimeomenucategory_tab[' . $tab_row . '][showproducts]'; 
            $id = 'showproducts'.$tab_row;
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>  <?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
 
				</td> 
                
                
			 <td><div class="image">
                  <img src="<?php echo $tab['thumb']; ?>" alt="" id="thumb<?php echo $tab_row; ?>">
                  <input type="hidden" name="mattimeomenucategory_tab[<?php echo $tab_row; ?>][image]" value="<?php echo $tab['image']; ?>" id="image<?php echo $tab_row; ?>" >
                  <br>
                  <a onclick="image_upload('image<?php echo $tab_row; ?>', 'thumb<?php echo $tab_row; ?>');"><?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $tab_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $tab_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
                </div></td>
			  <td>
				<?php foreach ($languages as $language) { ?>
				  <textarea  name="mattimeomenucategory_tab[<?php echo $tab_row; ?>][title][<?php echo $language['language_id']; ?>]" class="longfield" ><?php echo isset($tab['title'][$language['language_id']]) ? $tab['title'][$language['language_id']] : ''; ?></textarea>
                  
                  
				  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" ><br>
				<?php } ?></td>
              <td><a onclick="$('#tab-row<?php echo $tab_row; ?>').remove();" class="btn btn-danger">&times;</a></td>
            </tr>
          </tbody>
          <?php $tab_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td><a onclick="addTab();" class="btn btn-success"><?php echo $item_add_tab; ?></a></td>
            </tr>
          </tfoot>
        </table>	 
        <!-- end tab --> 

       <input type="hidden" name="buttonForm" value="">
      </form>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
    var tab_row = <?php echo $tab_row; ?>;

    function addTab() {
      html  = '<tbody id="tab-row' + tab_row + '">';
      html += '<tr>';
	 html += '	 <td><select name="mattimeomenucategory_tab['+ tab_row + '][category_id]">';
	  <?php foreach ($categories as $category) { ?>
	   html += '	   <option value="<?php echo $category['category_id']; ?>"><?php echo addslashes($category['name']); ?></option>';
	  <?php } ?>
	  html += '	 </select></td>';
	  html += '<td>';
	  html += '<div class="btn-group" data-toggle="buttons-radio">';
	 
	  html += '<label for="subcateg' + tab_row + '-1"  type="button" class="btn active">';
	  html += '<input type="radio" id="subcateg' + tab_row + '-1" name="mattimeomenucategory_tab[' + tab_row + '][subcateg]" value="1" checked="checked">On';				
	  html += '</label>';
      html += '<label for="subcateg' + tab_row + '-0"  type="button" class="btn">';
	  html += '<input type="radio" id="subcateg' + tab_row + '-0" name="mattimeomenucategory_tab[' + tab_row + '][subcateg]" value="0">Off';				
	  html += '</label>';
	   html += '</div>';
	  
	   html += '<br /><br><?php echo $item_column; ?><br /><div class="btn-group" data-toggle="buttons-radio">';
	 
	  html += '<label for="column' + tab_row + '-1"  type="button" class="btn active">';
	  html += '<input type="radio" id="column' + tab_row + '-1" name="mattimeomenucategory_tab[' + tab_row + '][column]" value="1" checked="checked">1';				
	  html += '</label>';
      html += '<label for="column' + tab_row + '-0"  type="button" class="btn">';
	  html += '<input type="radio" id="column' + tab_row + '-0" name="mattimeomenucategory_tab[' + tab_row + '][column]" value="0">2';				
	  html += '</label>';
	  
	  html += '</div>';
	  html += '</td>';
	   html += '<td>';
	  html += '<div class="btn-group" data-toggle="buttons-radio">';
	 
	  html += '<label for="showproducts' + tab_row + '-1"  type="button" class="btn active">';
	  html += '<input type="radio" id="showproducts' + tab_row + '-1" name="mattimeomenucategory_tab[' + tab_row + '][showproducts]" value="1" checked="checked">On';				
	  html += '</label>';
      html += '<label for="showproducts' + tab_row + '-0"  type="button" class="btn">';
	  html += '<input type="radio" id="showproducts' + tab_row + '-0" name="mattimeomenucategory_tab[' + tab_row + '][showproducts]" value="0">Off';				
	  html += '</label>';
	  
	  html += '</div>';
	  html += '</td>';
	  html += '<td><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + tab_row + '" /><input type="hidden" name="mattimeomenucategory_tab[' + tab_row + '][image]" value="" id="image' + tab_row + '" ><br ><a onclick="image_upload(\'image' + tab_row + '\', \'thumb' + tab_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + tab_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + tab_row + '\').attr(\'value\', \'\');"><?php   echo $text_clear; ?></a></div></td>';
	 
	 
	  html += '<td>';
	  <?php foreach ($languages as $language) { ?>
		html += '<textarea name="mattimeomenucategory_tab[' + tab_row + '][title][<?php echo $language['language_id']; ?>]" class="longfield"></textarea>';
		html += '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"><br>';
	  <?php } ?>
	  html += '</td>';
		
      html += '<td><a onclick="$(\'#tab-row' + tab_row  + '\').remove();" class="btn btn-danger">&times;</a></td>';
      html += '</tr>';
      html += '</tbody>'; 
	
      $('#tab tfoot').before(html);
	  
      tab_row++;
    }
  //--></script>
  
	
 <script type="text/javascript"><!--
    var module_row = <?php echo $module_row; ?>;

    function addModule() {	
      html  = '<tbody id="module-row' + module_row + '">';
      html += '  <tr>';
	  html += '    <td><input type="text" name="mattimeomenucategory_module[' + module_row + '][image_category_width]" value="320" class="shortfield"> <input type="text" name="mattimeomenucategory_module[' + module_row + '][image_category_height]" value="100" class="shortfield"></td>';	
	   html += '    <td><input type="text" name="mattimeomenucategory_module[' + module_row + '][image_subcategory_width]" value="40" class="shortfield"> <input type="text" name="mattimeomenucategory_module[' + module_row + '][image_subcategory_height]" value="40" class="shortfield"></td>';
	   	   html += '    <td><input type="text" name="mattimeomenucategory_module[' + module_row + '][image_width]" value="160" class="shortfield"> <input type="text" name="mattimeomenucategory_module[' + module_row + '][image_height]" value="160" class="shortfield"></td>';	
	  html += '    <td><input type="text" name="mattimeomenucategory_module[' + module_row + '][limit]" value="6"  class="shortfield"></td>';
      html += '    <td><select name="mattimeomenucategory_module[' + module_row + '][layout_id]">';
        <?php foreach ($layouts as $layout) { ?>
          html += '<option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
        <?php } ?>
      html += '    </select></td>';
      html += '    <td><select name="mattimeomenucategory_module[' + module_row + '][position]">';
      html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
      html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	  html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	  html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
      html += '    </select></td>';
	  html += '    <td class="left"><select name="mattimeomenucategory_module[' + module_row + '][status]">';
      html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
      html += '      <option value="0"><?php echo $text_disabled; ?></option>';
      html += '    </select></td>';
      html += '    <td><input type="text" name="mattimeomenucategory_module[' + module_row + '][sort_order]" value="" class="shortfield"></td>';
      html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="btn btn-danger">&times;</a></td>';
      html += '  </tr>';
      html += '</tbody>';
	
      $('#module tfoot').before(html);
	
      module_row++;
    }
    //--></script>  
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '">');
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
<script type="text/javascript">
function buttonApply() {document.newsform.buttonForm.value='apply';$('#form').submit();}
</script>
<script type="text/javascript">
function buttonApply() {document.newsform.buttonForm.value='apply';$('#form').submit();}
$(document).ready(function(){
$('#categmodul').live('click', function() {
		$('.helppopup5').dialog({
			open: function(event, ui) {},
			title: 'Mattimeo Categories',
			width: 870, height: 650, resizable: false, closeOnEscape: true
		});	
	});

	
	
});
</script> 
<?php echo $footer; ?>
