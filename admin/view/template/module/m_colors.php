<? $mattimeo_config = $this->model_setting_setting->getSetting('mattimeotheme', $this->data['store_id']);
?>

<div class="form-horizontal th_colors">


  
	<div class="row-fluid">
    <div class="span2"><h4><?php echo $color_text; ?></h4></div>
		<div class="span8">
			<?php 
			$valueq 	= $color_status; $name	= 'color_status'; $id = 'color_status';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
           </span>
		</div>
       <div class="span2">
        <a class="btn btn-danger pull-right" onclick="$('.th_colors .color').attr('value', '');
                                                            $('.th_colors .color').attr('style', '');
                                                            $('.th_colors #image_preview').attr('src', '<?php echo $no_image; ?>'); 
                                                            $('.th_colors #image_bg').attr('value', '');
                                                            $('.th_colors #image_header_preview').attr('src', '<?php echo $no_image; ?>'); 
                                                            $('.th_colors #image_header_bg').attr('value', '');
                                                            $('.th_colors #image_footer_preview').attr('src', '<?php echo $no_image; ?>'); 
                                                            $('.th_colors #image_footer_bg').attr('value', '');
                                                            "><?php echo $text_reset; ?></a>
       </div>  
	</div><hr />
    
   
    
  <small style="font-style:italic;"><?php echo $entry_colors_help; ?></small>  
   <div class="blue"> <?php echo $foncolor_text; ?></div>

    
  <div class="row-fluid">
					<div class="span2"><?php echo $foncolor_text; ?></div>
					<div class="span10 colorfield">
						<input type="text"  name="color_bg" value="<?php echo $color_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	</div>
    
	<div class="row-fluid">
    <div class="span2"><?php echo $fonimage_text; ?></div>
	<div class="span10"> 
    <?php 
	$repeat_img = array( 
		'no-repeat',
		'repeat',
		'repeat-x',
		'repeat-y',
		'inherit'
	); 
	$position_img = array( 
		'left top',
		'left bottom',
		'right top',
		'right bottom',
		'center top',
		'center bottom',
		'center center'
	); ?>
    
    <table class="customfont">
    <tr><td>   
  <div class="image">
               <img src="<?php echo $image_preview; ?>" alt="" id="image_preview">
                <?php 
				$valueq 			= isset($image_bg) ? $image_bg : '';
				$name			= 'image_bg';
				$id				= 'image_bg';
   			?>
	        		<input type="hidden" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">

                  <br>
                  <a onclick="image_upload('image_bg', 'image_preview');"><?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#image_preview').attr('src', '<?php echo $no_image; ?>'); $('#image_bg').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
   </td>
   <td>
   <select name="repeat_bg">
	<?php foreach ($repeat_img as $key) { ?>
	<?php ($key ==  $mattimeo_config['repeat_bg']) ? $selected = 'selected' : $selected=''; ?>
	<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
	<?php } ?>
	</select>
   </td>
    <td>
    <select name="position_bg">
	<?php foreach ($position_img as $key) { ?>
	<?php ($key ==  $mattimeo_config['position_bg']) ? $selected = 'selected' : $selected=''; ?>
	<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
	<?php } ?>
	</select>
   </td>  
    <td>
    <?php 
							$ar 	= array( 'Inherit' =>false , 'Fixed' =>true ); 
							$valueq 	= $attachment_bg; $name	= 'attachment_bg'; $id = 'attachment_bg';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
   </td>  
   </tr>
   </table>                     
                        
    </div>
</div><hr />


 <div class="row-fluid">
					<div class="span2"><?php echo $pagecolor_text; ?></div>
					<div class="span10 colorfield">
                     <?php 
							$ar 	= array( 'Yes' =>false , 'No' =>true ); 
							$valueq 	= $page_bg_status; $name	= 'page_bg_status'; $id = 'page_bg_status';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
						<input type="text"  name="page_bg" value="<?php echo $page_bg; ?>" class="color {required:false,hash:true}" >
                        
					</div>
	</div><br /><br />
    

			
				
				<ul class="nav nav-tabs" id="tab_colorsetting" > 
                <li><a href="#m-main"><?php echo $tab_main_text; ?></a></li>
                <li><a href="#m-header"><?php echo $tab_header_text; ?></a></li>
                <li><a href="#m-menu"><?php echo $tab_menu_text; ?></a></li>
                <li><a href="#m-buttons"><?php echo $tab_buttons_text; ?> & <?php echo $tab_arrows_text; ?></a></li>
                <li><a href="#m-category"><?php echo $tab_category_text; ?></a></li>
                <li><a href="#m-products"><?php echo $tab_products_text; ?></a></li>
                <li><a href="#m-elements"><?php echo $other_elements_text; ?></a></li>
                <li><a href="#m-footer"><?php echo $tab_footer_text; ?></a></li>
                </ul>
				<div class="tab-content">
                <!--*******************************************************************--> 
                   <div id="m-main" class="tab-pane langu3"> 
               <div class="row-fluid">
					  <div class="span2"><?php echo $main_text_btext; ?></div>
					  <div class="span10 colorfield">
						Text: <input type="text"  name="main_btext" value="<?php echo $main_btext; ?>" class="color {required:false,hash:true}" >
                       <?php echo $main_smalltext_btext; ?> <input type="text"  name="main_smallbtext" value="<?php echo $main_smallbtext; ?>" class="color {required:false,hash:true}" >
					</div>
	         </div><hr />
               <div class="row-fluid">
					<div class="span2"><?php echo $main_text_link; ?></div>
					<div class="span10 colorfield">
						Normal: <input type="text"  name="main_link" value="<?php echo $main_link; ?>" class="color {required:false,hash:true}" >
                        Hover: <input type="text"  name="main_linkhover" value="<?php echo $main_linkhover; ?>" class="color {required:false,hash:true}" >
					</div>
	</div><hr />
   <div class="row-fluid">
   <div class="span2"><?php echo $text_title_fonts; ?></div>
					<div class="span10 colorfield">
						<input type="text"  name="main_pagetitle" value="<?php echo $main_pagetitle; ?>" class="color {required:false,hash:true}" >
                        h2 <input type="text"  name="main_pagetitle_h2" value="<?php echo $main_pagetitle_h2; ?>" class="color {required:false,hash:true}" >
					</div>
	</div><hr />
    <div class="row-fluid">
    <div class="span2"><?php echo $text_module_fonts; ?></div>
					<div class="span10 colorfield">
						Color: <input type="text"  name="main_headermod" value="<?php echo $main_headermod; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="main_headermod_fon" value="<?php echo $main_headermod_fon; ?>" class="color {required:false,hash:true}" >
                        or border-bottom: <input type="text"  name="main_headermod_border" value="<?php echo $main_headermod_border; ?>" class="color {required:false,hash:true}" >
					</div>
	</div><hr />
    <div class="row-fluid">
                       <div class="span2"><?php echo $main_bread_link; ?></div>
					<div class="span10 colorfield">
						Normal: <input type="text"  name="main_bread" value="<?php echo $main_bread; ?>" class="color {required:false,hash:true}" >
                        Hover: <input type="text"  name="main_bread_hover" value="<?php echo $main_bread_hover; ?>" class="color {required:false,hash:true}" >
					</div>
	</div><hr />
    <div class="row-fluid">
    <div class="span2"><?php echo $main_captable_text; ?></div>
					<div class="span10 colorfield">
						Text: <input type="text"  name="captable_font" value="<?php echo $captable_font; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="captable_bg" value="<?php echo $captable_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	</div><hr />
    <div class="row-fluid">
    <div class="span2"><?php echo $pagination_text; ?></div>
					<div class="span10 colorfield">
						Text: <input type="text"  name="pagin_font" value="<?php echo $pagin_font; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="pagin_bg" value="<?php echo $pagin_bg; ?>" class="color {required:false,hash:true}" ><br /><br />
                        Active text: <input type="text"  name="pagin2_font" value="<?php echo $pagin2_font; ?>" class="color {required:false,hash:true}" >
                        Active background: <input type="text"  name="pagin2_bg" value="<?php echo $pagin2_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	</div><hr />
    <div class="row-fluid">
     <div class="span2"><?php echo $main_checkout_text; ?></div>
					<div class="span10 colorfield">
						Text: <input type="text"  name="checkout_color_text" value="<?php echo $checkout_color_text; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="checkout_color_bg" value="<?php echo $checkout_color_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	</div><hr />
    <div class="row-fluid">
     <div class="span2"><?php echo $filterprice_text; ?></div>
					<div class="span10 colorfield">
						<input type="text"  name="filter_color" value="<?php echo $filter_color; ?>" class="color {required:false,hash:true}" >
					</div>
	</div>
                   
                   
                   
                   </div>  
                   
                    <!--*******************************************************************--> 
                   <div id="m-header" class="tab-pane"> 
                      <div class="row-fluid">
					  <div class="span2"><?php echo $foncolor_header_text; ?></div>
					     <div class="span10 colorfield">
                          <?php 
							$ar 	= array( 'Yes' =>false , 'No' =>true ); 
							$valueq 	= $header_bg; $name	= 'header_bg'; $id = 'header_bg';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
                         
						 <input type="text"  name="color_header_bg" value="<?php echo $color_header_bg; ?>" class="color {required:false,hash:true}" >
					     </div>
	                    </div>
                        <div class="row-fluid">
    <div class="span2"><?php echo $fonimage_header_text; ?></div>
	<div class="span10"> 
   <table class="customfont">
    <tr><td>   
  <div class="image">
               <img src="<?php echo $image_header_preview; ?>" alt="" id="image_header_preview">
                <?php 
				$valueq 			= isset($image_header_bg) ? $image_header_bg : '';
				$name			= 'image_header_bg';
				$id				= 'image_header_bg';
   			?>
	        		<input type="hidden" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">

                  <br>
                  <a onclick="image_upload('image_header_bg', 'image_header_preview');"><?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#image_header_preview').attr('src', '<?php echo $no_image; ?>'); $('#image_header_bg').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
   </td>
   <td>
   <select name="repeat_header_bg">
	<?php foreach ($repeat_img as $key) { ?>
	<?php ($key ==  $mattimeo_config['repeat_header_bg']) ? $selected = 'selected' : $selected=''; ?>
	<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
	<?php } ?>
	</select>
   </td>
    <td>
    <select name="position_header_bg">
	<?php foreach ($position_img as $key) { ?>
	<?php ($key ==  $mattimeo_config['position_header_bg']) ? $selected = 'selected' : $selected=''; ?>
	<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
	<?php } ?>
	</select>
   </td>  
   
   </tr>
   </table>                      
    </div></div> <hr />                   
                        
     <div class="row-fluid">
					  <div class="span2"><?php echo $top_header_text; ?></div>
					     <div class="span10 colorfield">
                          <?php 
							$ar 	= array( 'Yes' =>false , 'No' =>true ); 
							$valueq 	= $top_header_bg; $name	= 'top_header_bg'; $id = 'top_header_bg';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
                         
                         
                         
						 <input type="text"  name="color_top_header_bg" value="<?php echo $color_top_header_bg; ?>" class="color {required:false,hash:true}" >
					     </div>
	                    </div><hr />                   
       <div class="row-fluid">
					<div class="span2"><?php echo $top_link_text; ?></div>
					<div class="span10 colorfield">
						Link: <input type="text"  name="top_link" value="<?php echo $top_link; ?>" class="color {required:false,hash:true}" >
                        Hover Link: <input type="text"  name="top_link_hover" value="<?php echo $top_link_hover; ?>" class="color {required:false,hash:true}" >
                        Hover background: <input type="text"  name="top_link_bg" value="<?php echo $top_link_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
        <div class="row-fluid">
					<div class="span2"><?php echo $top_currency_text; ?></div>
					<div class="span10 colorfield">
						Active link<input type="text"  name="currency_link" value="<?php echo $currency_link; ?>" class="color {required:false,hash:true}" >
                        Active Background<input type="text"  name="currency_fon" value="<?php echo $currency_fon; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <hr />                
         <div class="row-fluid">
					<div class="span2"><?php echo $top_cart_text; ?></div>
					<div class="span10 colorfield">
						Link: <input type="text"  name="header_cart_link" value="<?php echo $header_cart_link; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="header_cart_bg" value="<?php echo $header_cart_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <hr />         
             <div class="row-fluid">
					<div class="span2"><?php echo $top_text_text; ?></div>
					<div class="span10 colorfield">
						<input type="text"  name="top_headertext" value="<?php echo $top_headertext; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div>                               
                   </div>  
                   
                    <!--*******************************************************************--> 
                   <div id="m-menu" class="tab-pane"> 
                   
                    <div class="row-fluid">
					<div class="span2"><?php echo $menu_bg_text; ?></div>
					<div class="span10 colorfield">
                     <?php 
							$ar 	= array( 'Yes' =>false , 'No' =>true ); 
							$valueq 	= $menu_bg_status; $name	= 'menu_bg_status'; $id = 'menu_bg_status';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
						<input type="text"  name="menu_bg_color" value="<?php echo $menu_bg_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <hr />
                  <div class="row-fluid">
					<div class="span2"><?php echo $menulink_color_text; ?></div>
					<div class="span10 colorfield">
						Normal link: <input type="text"  name="menulink_color" value="<?php echo $menulink_color; ?>" class="color {required:false,hash:true}" >
                        Hover link: <input type="text"  name="menulink_hover_color" value="<?php echo $menulink_hover_color; ?>" class="color {required:false,hash:true}" >
                        Hover background: <input type="text"  name="menulink_bg_color" value="<?php echo $menulink_bg_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <hr />        
                  <div class="row-fluid">
					<div class="span2"><?php echo $menu2_bg_text; ?></div>
					<div class="span10 colorfield">
						<input type="text"  name="menu2_bg_color" value="<?php echo $menu2_bg_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <hr />
                  <div class="row-fluid">
					<div class="span2"><?php echo $menulink2_color_text; ?></div>
					<div class="span10 colorfield">
						Normal link: <input type="text"  name="menulink2_color" value="<?php echo $menulink2_color; ?>" class="color {required:false,hash:true}" >
                        Hover link: <input type="text"  name="menulink2_hover_color" value="<?php echo $menulink2_hover_color; ?>" class="color {required:false,hash:true}" >
                        Hover background: <input type="text"  name="menulink2_bg_color" value="<?php echo $menulink2_bg_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div>          
                   
                   </div>  
                   
                    <!--*******************************************************************--> 
                   <div id="m-buttons" class="tab-pane"> 
                     <div class="row-fluid">
					<div class="span2"><?php echo $tab_buttons_text; ?></div>
					<div class="span10 colorfield">
						Normal link: <input type="text"  name="button_link_color" value="<?php echo $button_link_color; ?>" class="color {required:false,hash:true}" >
                        Normal background: <input type="text"  name="button_bg_color" value="<?php echo $button_bg_color; ?>" class="color {required:false,hash:true}" ><br /><br />
                        Hover link: <input type="text"  name="button_link2_color" value="<?php echo $button_link2_color; ?>" class="color {required:false,hash:true}" >
                        Hoverl background: <input type="text"  name="button_bg2_color" value="<?php echo $button_bg2_color; ?>" class="color {required:false,hash:true}" >
                       
					</div>
                    </div><hr />
                    <div class="row-fluid">
					<div class="span2"><?php echo $tab_arrows_text; ?></div>
					<div class="span10 colorfield">
						Normal: <input type="text"  name="arrow_bg_color" value="<?php echo $arrow_bg_color; ?>" class="color {required:false,hash:true}" >
                        Hover: <input type="text"  name="arrow2_bg_color" value="<?php echo $arrow2_bg_color; ?>" class="color {required:false,hash:true}" >
                       
					</div>
                    </div>
                    
                   </div>  
                   
                    <!--*******************************************************************--> 
                   <div id="m-category" class="tab-pane"> 
                    <div class="row-fluid">
					<div class="span2"><?php echo $module_cat_text; ?></div>
					<div class="span10 colorfield">
						Normal link: <input type="text"  name="category_link_color" value="<?php echo $category_link_color; ?>" class="color {required:false,hash:true}" >
                        Normal background: <input type="text"  name="category_bg_color" value="<?php echo $category_bg_color; ?>" class="color {required:false,hash:true}" ><br /><br />
                        Hover link: <input type="text"  name="category2_link_color" value="<?php echo $category2_link_color; ?>" class="color {required:false,hash:true}" >
                        Hoverl background: <input type="text"  name="category2_bg_color" value="<?php echo $category2_bg_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                  <div class="row-fluid">
					<div class="span2"><?php echo $module_subcat_text; ?></div>
					<div class="span10 colorfield">
						Normal link: <input type="text"  name="category_sublink_color" value="<?php echo $category_sublink_color; ?>" class="color {required:false,hash:true}" >
                        Normal background: <input type="text"  name="category_subbg_color" value="<?php echo $category_subbg_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                   <div class="row-fluid">
					<div class="span2"><?php echo $module_activcat_text; ?></div>
					<div class="span10 colorfield">
						Link: <input type="text"  name="categoryactiv_link_color" value="<?php echo $categoryactiv_link_color; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="categoryactiv_bg_color" value="<?php echo $categoryactiv_bg_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                  <div class="row-fluid">
					<div class="span2"><?php echo $module_activsubcat_text; ?></div>
					<div class="span10 colorfield">
						Link: <input type="text"  name="categoryactiv2_link_color" value="<?php echo $categoryactiv2_link_color; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> 
                  
                   </div>  
                   
                    <!--*******************************************************************--> 
                   <div id="m-products" class="tab-pane"> 
                   
                    <div class="row-fluid">
					<div class="span2"><?php echo $product_name_text; ?></div>
					<div class="span10 colorfield">
						Normal: <input type="text"  name="product_name" value="<?php echo $product_name; ?>" class="color {required:false,hash:true}" >
                        Hover: <input type="text"  name="product_name_hover" value="<?php echo $product_name_hover; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                  
                  <div class="row-fluid">
					<div class="span2"><?php echo $product_price_text; ?></div>
					<div class="span10 colorfield">
						price: <input type="text"  name="product_price" value="<?php echo $product_price; ?>" class="color {required:false,hash:true}" >
                        old price: <input type="text"  name="product_oldprice" value="<?php echo $product_oldprice; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                  
                  <div class="row-fluid">
					<div class="span2">Sale</div>
					<div class="span10 colorfield">
						Color: <input type="text"  name="product_sale" value="<?php echo $product_sale; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="product_sale_bg" value="<?php echo $product_sale_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                  
                  <div class="row-fluid">
					<div class="span2"><?php echo $product_link_text; ?></div>
					<div class="span10 colorfield">
						Normal: <input type="text"  name="product_link" value="<?php echo $product_link; ?>" class="color {required:false,hash:true}" >
                        Hover: <input type="text"  name="product_link_hover" value="<?php echo $product_link_hover; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                  
                  <div class="row-fluid">
					<div class="span2"><?php echo $product_bg_text; ?></div>
					<div class="span10 colorfield">
						Normal: <input type="text"  name="product_bg" value="<?php echo $product_bg; ?>" class="color {required:false,hash:true}" >
                        Hover border and arrows: <input type="text"  name="product_bg_hover" value="<?php echo $product_bg_hover; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr />
                  
                  <div class="row-fluid">
					<div class="span2"><?php echo $product_tabs_text; ?></div>
					<div class="span10 colorfield">
						Text: <input type="text"  name="product_tabs_link" value="<?php echo $product_tabs_link; ?>" class="color {required:false,hash:true}" >
                        Background: <input type="text"  name="product_tabs_bg" value="<?php echo $product_tabs_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div>  
                   
                   </div>  
                    <!--*******************************************************************--> 
                   <div id="m-elements" class="tab-pane">
                   <b><?php echo $other_category_text; ?></b>
                   <span class="infohelp" style=" display:inline-block">&nbsp;<a id='categmodul'> <?php echo $topmenu_help; ?></a></span>
<div class='helppopup5 disableimg'><img src="view/image/mattimeotheme/category2.png"></div>
                    <br /><br />
                   <div class="row-fluid">
					<div class="span2"><?php echo $other_show_text; ?></div>
					<div class="span10 colorfield">
						Normal link: <input type="text"  name="other_show1_link" value="<?php echo $other_show1_link; ?>" class="color {required:false,hash:true}" >
                        Normal background: <input type="text"  name="other_show1_bg" value="<?php echo $other_show1_bg; ?>" class="color {required:false,hash:true}" ><br /><br />
                        Active link: <input type="text"  name="other_show2_link" value="<?php echo $other_show2_link; ?>" class="color {required:false,hash:true}" >
                        Active background: <input type="text"  name="other_show2_bg" value="<?php echo $other_show2_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <br />
                   <div class="row-fluid">
					<div class="span2"><?php echo $text_mattitle_fonts; ?></div>
					<div class="span10 colorfield">
						 <input type="text"  name="other_heading" value="<?php echo $other_heading; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><br />
                                     <div class="row-fluid">
					<div class="span2"><?php echo $pagecolor_text; ?></div>
					<div class="span10 colorfield">
						 <input type="text"  name="other_bg" value="<?php echo $other_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <hr /> 
                  <b><?php echo $other_banners_text; ?></b>
                   <span class="infohelp" style=" display:inline-block">&nbsp;<a id='bannerheading2'> <?php echo $topmenu_help; ?></a></span>
<div class='helppopup4 disableimg'><img src="view/image/mattimeotheme/bannerheading.png"></div>
                  
                  <br /><br />
                  <div class="row-fluid">
					<div class="span2"><?php echo $banner_heading_text; ?></div>
                   
					<div class="span2 colorfield">
                        Simple: <input type="text"  name="other_banner_heading1" value="<?php echo $other_banner_heading1; ?>" class="color {required:false,hash:true}" >
                        </div>
                     <div class="span2 colorfield">
                        Icon & text: <input type="text"  name="other_banner_heading2" value="<?php echo $other_banner_heading2; ?>" class="color {required:false,hash:true}" >
                        </div>
                        <div class="span2 colorfield">
                        Slider: <input type="text"  name="other_banner_heading3" value="<?php echo $other_banner_heading3; ?>" class="color {required:false,hash:true}" >
                        </div>
                        <div class="span2 colorfield">
                        Double slider: <input type="text"  name="other_banner_heading4" value="<?php echo $other_banner_heading4; ?>" class="color {required:false,hash:true}" >
					</div>
                    </div><br />


                    <div class="row-fluid">
					<div class="span2"><?php echo $banner_slider_text; ?></div>
					<div class="span4 colorfield">
                     Simple: <input type="text"  name="other_bannerslider1" value="<?php echo $other_bannerslider1; ?>" class="color {required:false,hash:true}" >
                     </div>
                     <div class="span2 colorfield">
                      Slider: <input type="text"  name="other_bannerslider3" value="<?php echo $other_bannerslider3; ?>" class="color {required:false,hash:true}" >   
					</div>
                     <div class="span2 colorfield">
                      Double slider: <input type="text"  name="other_bannerslider4" value="<?php echo $other_bannerslider4; ?>" class="color {required:false,hash:true}" >   
					</div>
                    </div><hr />
                  
                   <b><?php echo $other_news_text; ?></b><br /><br />
                   <div class="row-fluid">
					<div class="span2"><?php echo $news_heading_text; ?></div>
					<div class="span10 colorfield">
                        Normal: <input type="text"  name="news_heading" value="<?php echo $news_heading; ?>" class="color {required:false,hash:true}" >
                        Hover: <input type="text"  name="news_heading_hover" value="<?php echo $news_heading_hover; ?>" class="color {required:false,hash:true}" >
                       
					</div>
                    </div><br />
                      <div class="row-fluid">
					<div class="span2"><?php echo $news_data_text; ?></div>
					<div class="span10 colorfield">
                      <input type="text"  name="news_data" value="<?php echo $news_data; ?>" class="color {required:false,hash:true}" >
                    </div>
                    </div><br />
                    <div class="row-fluid">
					<div class="span2"><?php echo $news_button_text; ?></div>
					<div class="span10 colorfield">
                        Normal link: <input type="text"  name="news_button_link" value="<?php echo $news_button_link; ?>" class="color {required:false,hash:true}" >
						Normal background: <input type="text"  name="news_button_bg" value="<?php echo $news_button_bg; ?>" class="color {required:false,hash:true}" ><br /><br />
                        Hover link: <input type="text"  name="news_button_link_hover" value="<?php echo $news_button_link_hover; ?>" class="color {required:false,hash:true}" >
                        Hover background: <input type="text"  name="news_button_bg_hover" value="<?php echo $news_button_bg_hover; ?>" class="color {required:false,hash:true}" >
                       
					</div>
                    </div><hr />
                    
                     <div class="row-fluid">
					<div class="span2"><b><?php echo $footer_heading; ?></b></div>
                   
					<div class="span10 colorfield">
                        Background:<input type="text"  name="footer_custom_bg" value="<?php echo $footer_custom_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> 
                     
                   
                   </div>
                   
                   
                    <!--*******************************************************************--> 
                   <div id="m-footer" class="tab-pane"> 
                  
                  
                   <div class="row-fluid">
					<div class="span2"><?php echo $footer_bg_text; ?></div>
                    <div class="span10 colorfield">
                     <?php 
							$ar 	= array( 'Yes' =>false , 'No' =>true ); 
							$valueq 	= $footer_bg_status; $name	= 'footer_bg_status'; $id = 'footer_bg_status';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
					
						<input type="text"  name="footer_bg" value="<?php echo $footer_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr />
                  
                   <div class="row-fluid">
    <div class="span2"><?php echo $footer_fon_text; ?></div>
	<div class="span10"> 
   <table class="customfont">
    <tr><td>   
  <div class="image">
               <img src="<?php echo $image_footer_preview; ?>" alt="" id="image_footer_preview">
                <?php 
				$valueq 			= isset($image_footer_bg) ? $image_footer_bg : '';
				$name			= 'image_footer_bg';
				$id				= 'image_footer_bg';
   			?>
	        		<input type="hidden" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">

                  <br>
                  <a onclick="image_upload('image_footer_bg', 'image_footer_preview');"><?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#image_footer_preview').attr('src', '<?php echo $no_image; ?>'); $('#image_footer_bg').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
   </td>
   <td>
   <select name="repeat_footer_bg">
	<?php foreach ($repeat_img as $key) { ?>
	<?php ($key ==  $mattimeo_config['repeat_footer_bg']) ? $selected = 'selected' : $selected=''; ?>
	<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
	<?php } ?>
	</select>
   </td>
    <td>
    <select name="position_footer_bg">
	<?php foreach ($position_img as $key) { ?>
	<?php ($key ==  $mattimeo_config['position_footer_bg']) ? $selected = 'selected' : $selected=''; ?>
	<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
	<?php } ?>
	</select>
   </td>  
   
   </tr>
   </table> <br />                     
    </div></div>
                  
                   
                  
                     <div class="row-fluid">
					<div class="span2"><?php echo $footer_heading_text; ?></div>
					<div class="span10 colorfield">
						<input type="text"  name="footer_h3" value="<?php echo $footer_h3; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div> <hr />
                  
                   <div class="row-fluid">
					<div class="span2"><?php echo $footer_text_text; ?></div>
					<div class="span10 colorfield">
						<input type="text"  name="footer_text" value="<?php echo $footer_text; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr /> 
                  
                   <div class="row-fluid">
					<div class="span2"><?php echo $footer_link_text; ?></div>
					<div class="span10 colorfield">
						Normal:<input type="text"  name="footer_link" value="<?php echo $footer_link; ?>" class="color {required:false,hash:true}" >
                        Hover:<input type="text"  name="footer_link_hover" value="<?php echo $footer_link_hover; ?>" class="color {required:false,hash:true}" >
                        Background hover:<input type="text"  name="footer_link_bg" value="<?php echo $footer_link_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div><hr />
                  <div class="row-fluid">
					<div class="span2">Bottom</div>
					<div class="span10 colorfield">
						Text:<input type="text"  name="powered_text" value="<?php echo $powered_text; ?>" class="color {required:false,hash:true}" >
                        Background:<input type="text"  name="powered_bg" value="<?php echo $powered_bg; ?>" class="color {required:false,hash:true}" >
					</div>
	              </div>
                   
                   </div>    
                
                </div>
    
    

  
</div>