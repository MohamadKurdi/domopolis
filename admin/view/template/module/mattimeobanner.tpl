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
<!--
<textarea rows="20" cols="200"><?php print_r($this->request->post); ?></textarea>
<textarea rows="20" cols="100"><?php print_r($modules); ?></textarea>
-->
 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="newsform">
<div id="customcontent">
<div  class="nav-tabs">
   <table>
      <?php foreach ($modules as $module_row => $module) {?>
         <tr mattimeobanner_object="group" index1="<?php echo $module_row; ?>">
            <td  class="modbanner_tab"><a mattimeobanner_object="del_group" index1="<?php echo $module_row; ?>" class="btn btn-danger">&times;</a>
            <input type="text" name="mattimeobanner_module[<?php echo $module_row; ?>][group]" value="<?php echo $module['group']; ?>">
            </td>
         </tr> 
      <?php $module_row++; ?>
      <?php }    ?>
   </table>
   <a onclick="addModule();" class="btn btn-success"><?php echo $button_add_module; ?></a>
</div>

    <div class="content categorymodul vtabs-content">
   <table class="all_topmenulink modulbanner"> 
     <thead>
       <tr>
	        <td><?php echo $entry_image_category; ?></td>
         <td><?php echo $entry_dinamic; ?>
         <span class="infohelp" style=" display:inline-block">&nbsp;<a id='bannerheading'> View a sample</a></span>
<div class='helppopup3 disableimg'><img src="view/image/mattimeotheme/bannermodul.png"></div>
         </td>
         <td><?php echo $entry_layout; ?></td>
         <td><?php echo $entry_position; ?></td>
         <td><?php echo $entry_status; ?></td>
         <td colspan="2"><?php echo $entry_sort_order; ?></td>
       </tr>
     </thead>

<?php foreach ($modules as $module_row => $module) {?>
   <tbody mattimeobanner_object="body" index1="<?php echo $module_row; ?>" >
   <tr>
	  <td>
		  <input type="text" name="mattimeobanner_module[<?php echo $module_row; ?>][image_width]" value="<?php echo $module['image_width']; ?>" class="shortfield" >
         <input type="text" name="mattimeobanner_module[<?php echo $module_row; ?>][image_height]" value="<?php echo $module['image_height']; ?>" class="shortfield">
         <?php if (isset($error_category_image[$module_row])) { ?>
         <span class="error"><?php echo $error_category_image[$module_row]; ?></span>
         <?php } ?>
     </td>

     <td>      <?php 
			$ar 	= array('simple' => '1', 'icon & text' => '2', 'slider' => '3', 'double slider' => '4', 'hotline' => '5');
			$valueq 	= isset($module['dinamic']) ? $module['dinamic'] : '';
            $name = 'mattimeobanner_module['.$module_row.'][dinamic]'; 
            $id = 'dinamic'.$module_row;
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
					<label type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
						<?php echo $key; ?>
					</label>
				<?php } ?>
			</div>
     </td>
         
	  <td><select name="mattimeobanner_module[<?php echo $module_row; ?>][layout_id]">
            <?php foreach ($layouts as $layout) { ?>
            <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
     </td>

     <td><select name="mattimeobanner_module[<?php echo $module_row; ?>][position]">
      <?php if ($module['position'] == 'topcontent') { ?>
         <option value="topcontent" selected="selected">Full width</option>
         <?php } else { ?>
         <option value="topcontent">Full width</option>
         <?php } ?>
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
         
       </select>
     </td>

     <td>
         <select name="mattimeobanner_module[<?php echo $module_row; ?>][status]">
             <?php if ($module['status']) { ?>
             <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
             <option value="0"><?php echo $text_disabled; ?></option>
             <?php } else { ?>
             <option value="1"><?php echo $text_enabled; ?></option>
             <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
             <?php } ?>
           </select>                
     </td>

      <td>
         <input type="text" name="mattimeobanner_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" class="shortfield">
      </td>
      
</tr>             
        <!-- start tab -->  
<tr>
<td colspan="7">
  <table  class="all_topmenulink">
  <thead>
      <tr>
			  <td style="width:10%"><?php echo $item_image; ?></td>
           <td style="width:25%"><?php echo $item_heading; ?></td>
			  <td style="width:45%"><?php echo $item_title; ?></td>
           <td style="width:20%"><?php echo $item_link; ?></td>
           <td></td>
      </tr>
    </thead>

    <?php //$tab_row = 0; ?>
    <?php foreach ($module['tabs'] as $tab_row => $tab) { ?>

       <tbody mattimeobanner_object="tab" index1="<?php echo $module_row; ?>" index2="<?php echo $tab_row; ?>">
         <tr>	   
			  <td>
			      <div class="image">
                  <img src="<?php echo $tab['thumb']; ?>" alt="" 
                     mattimeobanner_object="tab_image_thumb" index1="<?php echo $module_row; ?>" index2="<?php echo $tab_row; ?>" >
                  <input type="hidden" name="mattimeobanner_module[<?php echo $module_row; ?>][tabs][<?php echo $tab_row; ?>][image]" value="<?php echo $tab['image']; ?>" 
                     id="image<?php echo $module_row; ?>-<?php echo $tab_row; ?>"
                     mattimeobanner_object="tab_image" index1="<?php echo $module_row; ?>" index2="<?php echo $tab_row; ?>" >
                  <br>
                  <a mattimeobanner_object="tab_image_add" index1="<?php echo $module_row; ?>" index2="<?php echo $tab_row; ?>" >
                     <?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;
                  <a mattimeobanner_object="tab_image_del" index1="<?php echo $module_row; ?>" index2="<?php echo $tab_row; ?>" >
                  <?php echo $text_clear; ?></a>
                </div>
           </td>
           <td>
   				<?php foreach ($languages as $language) { ?>
                   <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >
   				  <input type="text"  name="mattimeobanner_module[<?php echo $module_row; ?>][tabs][<?php echo $tab_row; ?>][headingtext][<?php echo $language['language_id']; ?>]" value="<?php echo isset($tab['headingtext'][$language['language_id']]) ? $tab['headingtext'][$language['language_id']] : ''; ?>" class="input-medium" >
   				  <br>
   				<?php } ?>
				</td>
			  <td>
   				<?php foreach ($languages as $language) { ?>
                   <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >
   				  <textarea  name="mattimeobanner_module[<?php echo $module_row; ?>][tabs][<?php echo $tab_row; ?>][title][<?php echo $language['language_id']; ?>]" class="longfield"><?php echo isset($tab['title'][$language['language_id']]) ? $tab['title'][$language['language_id']] : ''; ?></textarea>
   				  <br>
   				<?php } ?>
				</td>
            <td>
				  <input type="text"  name="mattimeobanner_module[<?php echo $module_row; ?>][tabs][<?php echo $tab_row; ?>][href]" value="<?php echo isset($tab['href']) ? $tab['href'] : ''; ?>" class="input-medium" > 
				</td>
            <td>
               <a mattimeobanner_object="del_tab" index1="<?php echo $module_row; ?>" index2="<?php echo $tab_row; ?>" class="btn btn-danger">&times;</a> 
            </td>
         </tr>
       </tbody>

    <?php } ?>
      <tfoot>
        <tr>
          <td><a mattimeobanner_object="add_tab" index1="<?php echo $module_row; ?>" class="btn btn-success"><?php echo $item_add_tab; ?></a></td>
        </tr>
      </tfoot>
  </table>

   </td>
        <!-- end tab --> 
  </tr>
</tbody>

<?php } ?>
       
</table>
</div>
</div>
 <input type="hidden" name="buttonForm" value="">
</form>
    
</div>


  <script type="text/javascript"><!--


$('[mattimeobanner_object=del_group]').on('click',function(){   

      var $r = $(this).attr('index1');

   $('[mattimeobanner_object=body][index1=' + $r + ']').remove();
   $('[mattimeobanner_object=group][index1=' + $r + ']').remove();
   chooseTab(1);

});

$('[mattimeobanner_object=del_tab]').on('click',function(){   

      var i1 = $(this).attr('index1');
      var i2 = $(this).attr('index2');
      var $tab = $('[mattimeobanner_object = tab][index1=' + i1 + '][index2=' + i2 + ']')

      if ($tab.siblings('tbody').is('tbody')){
         $tab.remove();
      };
});

 
$('[mattimeobanner_object=group]').on('click',function(){   
   var $curMod = $(this).attr('index1');
   chooseTab($curMod);
});


$('[mattimeobanner_object=tab_image_del]').on('click',function(){   
   $('[mattimeobanner_object=tab_image_thumb][index1=' + $(this).attr('index1') + '][index2=' + $(this).attr('index2') + ']').attr('src', '<?php echo $no_image; ?>'); 
   $('[mattimeobanner_object=tab_image][index1=' + $(this).attr('index1') + '][index2=' + $(this).attr('index2') + ']').attr('value', ''); 
});

function chooseTab($curMod){


   if ($curMod==1) {

      $('[mattimeobanner_object=group]').removeClass('selectedModule');
      $('[mattimeobanner_object=group]').eq(1).addClass('selectedModule');

      $('[mattimeobanner_object=body]').css("display","none");
      $('[mattimeobanner_object=body]').eq(1).css("display","");   
      
   } else {

      $('[mattimeobanner_object=group]').each(function($i,$item){

         if ($($item).attr('index1')==$curMod ) {
            $($item).addClass('selectedModule');
            $isFind=1;
         } else {
            $($item).removeClass('selectedModule')
         }
      });

      $('[mattimeobanner_object=body]').each(function($i,$item){

         if ($($item).attr('index1')==$curMod ) {
            $($item).css("display","")
         } else {
            $($item).css("display","none")
         }
      })
   }
};
 
$(document).ready(function(){
	
   $('[mattimeobanner_object=group][index1=0]').css("display","none");
   $('[mattimeobanner_object=body][index1=0]').css("display","none");
   chooseTab('1');

});

function addModule()
{
      var $curMod = Number($('[mattimeobanner_object=group]').last().attr('index1')) + 1;

// module section
      var $clone = $('[mattimeobanner_object=group][index1=0]').clone(-1,-1);
      $clone.attr('index1',$curMod);    
      $clone.find('[index1]').attr('index1',$curMod);    
      $clone.find('input').attr('name','mattimeobanner_module[' + $curMod + '][group]');
      $clone.css("display","");
      $clone.insertAfter($('[mattimeobanner_object=group]').last());

//tab section
      $clone = $('[mattimeobanner_object=body][index1=0]').clone(-1,-1);
      $clone.attr('index1',$curMod);    
      $clone.find('[index1]').attr('index1',$curMod); 
      $clone.find('#image0-0').attr('id','image' + $curMod + '-0');

      $clone.find('input, select, textarea').each(function(indx){
         var nm = $(this).attr('name');
         $(this).attr('name',nm.replace('mattimeobanner_module[0]','mattimeobanner_module[' + $curMod + ']'));
      });

      $clone.css("display","");
      $clone.insertAfter($('[mattimeobanner_object=body]').last());

      chooseTab($curMod);

};

$('[mattimeobanner_object=add_tab]').on('click',function(){   

      var $curMod = $(this).attr('index1');
      var $lastTab = $('[mattimeobanner_object = tab][index1=' + $curMod + ']').last();
      var $curTab = Number($lastTab.attr('index2'))+1;
      
      var $clone = $('[mattimeobanner_object = tab][index1=0][index2=0]').clone(-1,-1);
//      console.dir($clone);

      $clone.find('input, select, textarea').each(function(indx){
         var nm = $(this).attr('name');
         $(this).attr('name',nm.replace('mattimeobanner_module[0][tabs][0]','mattimeobanner_module[' + $curMod + '][tabs][' + $curTab + ']'));
      });

      $clone.attr('index1',$curMod);
      $clone.attr('index2',$curTab);
      $clone.find('[index1]').attr('index1',$curMod); 
      $clone.find('[index2]').attr('index2',$curTab); 
      $clone.find('#image0-0').attr('id','image' + $curMod + '-' + $curTab);
      
      $clone.insertAfter($lastTab);

});


  //--></script>
  
  
<script type="text/javascript"><!--

$('[mattimeobanner_object=tab_image_add]').on('click',function(){   

var $fieldId = $('[mattimeobanner_object=tab_image][index1=' + $(this).attr('index1') + '][index2=' + $(this).attr('index2') + ']').attr('id');
var $thumb = $('[mattimeobanner_object=tab_image_thumb][index1=' + $(this).attr('index1') + '][index2=' + $(this).attr('index2') + ']');

	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent($fieldId) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + $fieldId).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + $fieldId).attr('value')),
					dataType: 'text',
					success: function(data) {
						$thumb.attr('src', data);
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

});

//--></script>
<script type="text/javascript">
function buttonApply() {document.newsform.buttonForm.value='apply';$('#form').submit();}
$(document).ready(function(){
	$('#bannerheading').live('click', function() {
		$('.helppopup3').dialog({
			open: function(event, ui) {},
			title: 'Title and text banners',
			width: 870, height: 720, resizable: false, closeOnEscape: true
		});	
	});

	
	
});
</script> 

<?php echo $footer; ?>
