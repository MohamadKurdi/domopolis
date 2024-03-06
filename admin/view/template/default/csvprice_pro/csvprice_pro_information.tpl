<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <div class="ocl-ui-content">
      
      <?php if (isset($error_license) && !empty($error_license)) { ?><div class="warning"><?php echo $error_license; ?></div><?php } ?>
      <?php if (isset($error) && !empty($error)) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
      <?php if (isset($warning) && !empty($warning)) { ?><div class="warning"><?php echo $warning; ?></div><?php } ?>
      <?php if (isset($success) && !empty($success)) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
      
      <div class="ocl-ui-main-menu">
        <ul>
        	<?php foreach($main_menu as $key => $value){ ?>
        		<?php if($main_cfg[$key] == 1) { ?>
	                <li<?php if($driver == $key) echo ' class="selected"'; ?>><a href="<?php echo $main_url[$key];?>"><?php echo $value;?></a></li>
	            <?php }?>
        	<?php }?>
        </ul>
      </div>

		<div class="ocl-ui-header"><span class="ocl-ui-module-name"><?php echo $ocl_module_name; ?></span><?php echo $tpl_home_page; ?></div>
      
      <div class="ocl-ui-body-content">
          <div id="tab-info">
          	<div class="ocl-ui-wrap">
          		<form action="<?php  echo $action;?>" method="post" id="form_licensekey" enctype="multipart/form-data">
          		<table class="ocl-ui-form ocl-ui-form-vertical">
          			<tbody>
          				<tr>
          					<td><?php echo $entry_license_key;?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $title_licensekey; ?>" /></td>
          					<td><input type="text" name="license_key" class="span500" value="<?php echo $license_key;?>" /> <a onclick="$('#form_licensekey').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_save;?></a></td>
          				</tr>
          			</tbody>
          		</table>
          		</form>
          	</div>
          	<div class="ocl-ui-wrap ocl-ui-wrap-light"><?php echo $text_module_info;?></div>
          	<?php echo $text_module_info2;?>
          </div>
      </div>

  </div>
	<div id="ocl_copyright"><?php echo $ocl_copyright;?></div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
    // Tooltype functions
    function simple_tooltip(target_items, name){
        $(target_items).each(function(i){
            $("body").append("<div class='"+name+"' id='"+name+i+"'><p>"+$(this).attr('title')+"</p></div>");
            var my_tooltip = $("#"+name+i);

            $(this).removeAttr("title").mouseover(function(){
                my_tooltip.css({opacity:1, display:"none"}).fadeIn(200);
            }).mousemove(function(kmouse){
                my_tooltip.css({left:kmouse.pageX+15, top:kmouse.pageY+15});
            }).mouseout(function(){my_tooltip.fadeOut(200);});
        });
    }

    simple_tooltip(".helper","ocl-ui-tooltip");
});
//--></script>
<?php echo $footer; ?>