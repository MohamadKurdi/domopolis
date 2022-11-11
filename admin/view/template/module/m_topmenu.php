<div class="form-horizontal">

       
     <!--General menu-->   
   
       <div class="row-fluid">
       <div class="span2"><h4><?php echo $text_menu_generalmenu; ?></h4></div>
					<div class="span10">
						<?php 
							$ar 	= array( 'Mattimeo' => '0', 'Mattimeo full width' => '1', 'Default' => '2' );
							$valueq 	= $gen_topmenu; $name = 'gen_topmenu'; $id	= 'gen_topmenu';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
                            <span class="infohelp" style=" display:inline-block">&nbsp;<a id='displaymenu'> <?php echo $topmenu_help; ?></a></span>
						<div class='helppopup2 disableimg'><img src="view/image/mattimeotheme/topmenu.png"></div>
						</div>
					</div>
				</div><hr />
         <div class="row-fluid">
       <div class="span2"><?php echo $text_m_menucolumn; ?></div>
					<div class="span10">
						<?php 
							$ar 	= array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' );
							$valueq 	= $gen_m_column; $name = 'gen_m_column'; $id	= 'gen_m_column';
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
					</div>
				</div><hr />        
        <div class="row-fluid">
		<div class="span2"><?php echo $text_m_home; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $gen_m_home; $name = 'gen_m_home'; $id = 'gen_m_home';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr /> 
        <div class="row-fluid">
		<div class="span2"><?php echo $text_m_account; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $gen_m_account; $name	= 'gen_m_account'; $id = 'gen_m_account';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	</div><hr />       
                
        <div class="row-fluid">
		<div class="span2"><?php echo $text_m_info; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $gen_m_info; $name = 'gen_m_info'; $id = 'gen_m_info';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />
       <div class="row-fluid">
		<div class="span2">Производитель<br />Коллекция для мультибренда</div>
		<div class="span10">
          <?php 
			$valueq 	= $gen_m_brand; $name	= 'gen_m_brand'; $id = 'gen_m_brand';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />
       <div class="row-fluid">
		<div class="span2"><?php echo $text_m_spec; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $gen_m_spec; $name = 'gen_m_spec'; $id = 'gen_m_spec';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />
       <div class="row-fluid">
		<div class="span2"><?php echo $text_news; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $gen_news; $name = 'gen_news'; $id = 'gen_news';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />

  <div class="tabs-below tabbable" data-theme="tab_switch" id='m_topmenu'>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_alllink"><?php echo $text_top_alllink; ?></a>
				<?php 
			$valueq 	= $status_menu; $name	= 'status_menu'; $id = 'status_menu';
			?>
				<span class="switch">
					<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
					<label for="<?php echo $id; ?>" class="switch-img"></label>
				</span>
                
			</li> 
            <li><a href="#tab_customlink"><?php echo $text_top_html; ?></a>
				<?php 
			$valueq 	= $status_menu2; $name	= 'status_menu2'; $id = 'status_menu2';
			?>
				<span class="switch">
					<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
					<label for="<?php echo $id; ?>" class="switch-img"></label>
				</span>
                
			</li> 
            </ul>
            
     <div class="tab-content">
                
         <!--All link-->  
			<div id="tab_alllink" class="tab-pane active">       

         <table class="all_topmenulink">
         <thead><tr><td><?php echo $text_top_cusomlink; ?></td><td><?php echo $text_top_url; ?></td><td><?php echo $entry_sort_order; ?></td><td></td></tr></thead>
        	<?php	
          if(isset($mattlink['Top_m_links'])){
	          foreach($mattlink['Top_m_links'] as $linkId => $link){ 
      	       ?>
                <tbody id="numberlink<?php echo $linkId; ?>">
                <tr><td>
      			<?php 
             foreach ($languages as $langId => $lang) {
               
                  if (array_key_exists($lang['language_id'], $link['namelink']))
                     $valueq = isset($link['namelink'][$lang['language_id']]) ? $link['namelink'][$lang['language_id']] : '';
                  else 
                     $valueq = '';
      				$name			= 'mattimeolink[Top_m_links][' . $linkId . '][namelink][' . $lang['language_id'] . ']';
      				$id				= 'Top_m_links-' . $linkId . '-namelink' . '-' . $lang['language_id'];
					
      			   ?>
                     <input  type="text" class='input-medium' placeholder="<?php echo $text_top_cusomlink; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>" >
                     <img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" />
                      <?php } ?>
                     </td>
                     <td>
      			   <?php 
               
				$valueq 			= isset($link['url']) ? $link['url'] : '';
				$name			= 'mattimeolink[Top_m_links][' . $linkId . '][url]';
				$id				= 'Top_m_links-' . $linkId . '-url';
   			?>
	        		<input type="text" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">
                    </td>
                    <td>
            <?php 
				$valueq 			= isset($link['sort']) ? $link['sort'] : '';
				$name			= 'mattimeolink[Top_m_links][' . $linkId . '][sort]';
				$id				= 'Top_m_links-' . $linkId . '-sort';
   			?>
	        		<input type="text" class='input-medium' placeholder="<?php echo $entry_sort_order; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">
            </td>
            <td>
			   <a title="Remove" onclick="$('#numberlink<?php echo $linkId; ?>').remove();" class="btn btn-danger">&times;</a>

			   </td></tr>
            </tbody>
            	<?php  
            }     
			}
        	else $linkId=0;
   	?>
            <tfoot>
            <tr><td><label class="control-label addNew"><a class="btn btn-success"><?php echo $text_addlink; ?></a></label></td></tr>
            </tfoot>            </table>	
          </div>
          
          <!--end All link--> 
     
     <!--Custom html-->
			<div id="tab_customlink" class="tab-pane">
   
               <ul class="nav nav-tabs" id="ft-menu-tab">
					
					<?php foreach ($languages as $language) { ?>
					<li>
						<a href="#ft-menu<?php echo $language['language_id']; ?>">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
						</a>
					</li>
					<?php } ?>
	          </ul>
	         <div  class="tab-content">
		<?php foreach ($languages as $language) { ?>
							<div class="tab-pane langu" id="ft-menu<?php echo $language['language_id']; ?>"> 
							<div class="row-fluid">
							<div class="span2"><?php echo $text_top_cusomlink; ?></div>
							   <div class="span10">
                               <input placeholder="<?php echo $text_top_cusomlink; ?>" name="mattimeomod[<?php echo $language['language_id']; ?>][topmenulink_lang]" value="<?php echo isset($mattData[$language['language_id']]['topmenulink_lang']) ? $mattData[$language['language_id']]['topmenulink_lang'] : ''; ?>" id="mattimeomod-<?php echo $language['language_id']; ?>-topmenulink_lang" type="text" >
                                </div>
                                </div><br />
                            
                             <div class="row-fluid">
							<div class="span2"><?php echo $text_top_html; ?></div>
							   <div class="span10">
                                <textarea  name="mattimeomod[<?php echo $language['language_id']; ?>][topmenulink_custom]"  id="mattimeomod-<?php echo $language['language_id']; ?>-topmenulink_custom"><?php echo isset($mattData[$language['language_id']]['topmenulink_custom']) ? $mattData[$language['language_id']]['topmenulink_custom'] : ''; ?></textarea>
                              </div>
                              </div>
                          
							</div> 
                             <?php } ?>            
                    </div>
                    
           </div>
         <!--end Custom Html-->
</div>
</div>
</div>
	
<script type="text/javascript">
$('.addNew .btn').live('click', function(){
	addNewlink();
});
var numberlink = <?php echo ++$linkId; ?>;
function addNewlink(){
		
	     html = '     <tbody id="numberlink' + numberlink + '">';
		 html += '    <tr><td>';
         <?php foreach ($languages as $language) { ?>
		 html += '    <input class="input-medium"  placeholder="<?php echo $text_top_cusomlink; ?>" type="text" name="mattimeolink[Top_m_links][' + numberlink + '][namelink][<?php echo $language['language_id']; ?>]"  value="">';
		 html += '    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >';
		 <?php } ?> 
		 html += '	  </td>';
		 html += '	  <td>';
         html += '	  <input class="input-medium" title="URL" placeholder="<?php echo $text_top_url; ?>" type="text" name="mattimeolink[Top_m_links][' + numberlink + '][url]"  value="">';
		 html += '	  </td>';
		 html += '	  <td>';
         html += '	  <input class="input-medium" title="Sort order" placeholder="<?php echo $entry_sort_order; ?>" type="text" name="mattimeolink[Top_m_links][' + numberlink + '][sort]"  value="">';
		 html += '	  </td>';
	     html += '	  <td>';
	     html += '	<a title="Remove" onclick="$(\'#numberlink' + numberlink + '\').remove();" class="btn btn-danger">&times;</a>';
		 html += '	</td></tr>';
		 html += '	</tbody>';
	
	$('.all_topmenulink tfoot').before(html);
	numberlink++;
}

</script>	

