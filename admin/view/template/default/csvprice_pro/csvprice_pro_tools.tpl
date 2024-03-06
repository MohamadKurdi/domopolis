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
      
      <!-- BEGIN Categories Driver -->
      <div id="submenu"class="ocl-ui-submenu">
        <ul>
           <li><a href="#tab_backup" id="link_tab_backup"><?php echo $tab_backup; ?></a></li>
           <li><a href="#tab_cache" id="link_tab_cache"><?php echo $tab_cache; ?></a></li>
        </ul>
     </div>

      <div class="ocl-ui-body-content">
          <!-- BEGIN Manufacturers Export Tab Content -->
          <div id="tab_backup" class="ocl-ui-tab-content">
              <form action="<?php echo $action_backup;?>" method="post" id="form_backup" enctype="multipart/form-data" class="form-horizontal">
              	<input type="hidden" name="backup" value="1" />
              </form>
			<div class="ocl-ui-wrap">
				<table class="ocl-ui-form">
					<tbody>
						<tr><td><?php echo $text_create_backup_file; ?></td><td><a onclick="$('#form_backup').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_backup; ?></a></td></tr>
					</tbody>
				</table>
			</div>
			<div class="ocl-ui-wrap">
				<form action="<?php echo $main_url['Tools'];?>" method="post" enctype="multipart/form-data" id="form_restore">
				<table class="ocl-ui-form">
					<tbody>
						<tr>
							<td><?php echo $text_restore_backup; ?></td>
							<td><input type="file" class="span300" name="import" /> <a onclick="$('#form_restore').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_restore; ?></a></td>
						</tr>
					</tbody>
				</table>
				</form>
			</div>
          </div>
          <!-- ////////////////////////  END Manufacturers Export Tab Content ////////////////////////  -->
          <div id="tab_cache" class="ocl-ui-tab-content">
			<form action="<?php echo $main_url['Tools'];?>" method="post" id="form_clear_cache" enctype="multipart/form-data" class="form-horizontal">
			<div class="ocl-ui-wrap span500">
				<input type="hidden" name="clear_cache" value="1" />
				<table class="ocl-ui-form">
					<tr><td><label><input type="checkbox" name="cache_category" value="1" /><?php echo $entry_clear_cache_category;?></label></td></tr>
					<tr><td><label><input type="checkbox" name="cache_manufacturer" value="1" /><?php echo $entry_clear_cache_manufacturer;?></label></td></tr>
					<tr><td><label><input type="checkbox" name="cache_product" value="1" /><?php echo $entry_clear_cache_product;?></label></td></tr>
					<tr><td><label><input type="checkbox" name="cache_seo_pro" value="1" /><?php echo $entry_clear_cache_seo_pro;?></label></td></tr>
					<tr><td><label><input type="checkbox" name="cache_stock_status" value="1" /><?php echo $entry_clear_cache_stock_status;?></label></td></tr>
				</table>
			</div>
			</form>
			<div style="margin-top: 20px;"><a onclick="$('#form_clear_cache').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_exec; ?></a></div>
          </div>

      </div> <!-- class="vtabs-content" -->
    </div> <!-- END class="container" -->
	<div id="ocl_copyright"><?php echo $ocl_copyright;?></div>
</div> <!-- END id="content" -->

<script type="text/javascript"><!--
$(document).ready(function() {
    // Tabs functions
    <?php if(isset($tab_selected)) { ?>
    $('#submenu a').tabs();
    $('#submenu a').removeClass('selected');
    $('.ocl-ui-tab-content').hide();
    $("#link_<?php echo $tab_selected; ?>").addClass('selected');
    $("#<?php echo $tab_selected; ?>").show();
    <?php } ?>
    
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