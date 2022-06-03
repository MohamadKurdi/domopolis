<div class="form-horizontal">
<h4><?php echo $text_header; ?></h4>
<hr />


 <div class="row-fluid">
   <div class="span2"><b><?php echo $text_contact_header; ?></b></div>
		<div class="span10">
			<?php 
			$valueq 	= $h_contact_status; $name	= 'h_contact_status'; $id = 'h_contact_status';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	</div>
    
				
				<ul class="nav nav-tabs" id="header_comptextus">
					
					<?php foreach ($languages as $language) { ?>
					<li>
						<a href="#ft-contact<?php echo $language['language_id']; ?>">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<div class="tab-content">
                <?php foreach ($languages as $language) { ?>
					<div class="tab-pane langu" id="ft-contact<?php echo $language['language_id']; ?>">
						<div class="row-fluid">
							<div class="span10">
								<textarea name="matt_array[<?php echo $language['language_id']; ?>][comptext_header_text]" id="matt_array-<?php echo $language['language_id']; ?>-comptext_header_text"><?php echo isset($matt[$language['language_id']]['comptext_header_text']) ? $matt[$language['language_id']]['comptext_header_text'] : ''; ?></textarea>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			<hr />	

        
        <!--Top menu-->
<b><?php echo $text_menu_topmenu; ?></b><br /><br />
          <div class="row-fluid">
		<div class="span2"><?php echo $text_m_home; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_home; $name = 'top_m_home'; $id = 'top_m_home';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />
       <div class="row-fluid">
		<div class="span2"><?php echo $text_m_welcome; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_welcome; $name = 'top_m_welcome'; $id = 'top_m_welcome';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />
       <div class="row-fluid">
		<div class="span2"><?php echo $text_m_wishlist; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_wish; $name = 'top_m_wish'; $id = 'top_m_wish';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div> <hr />
       <div class="row-fluid">
		<div class="span2"><?php echo $text_m_compare; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_compare; $name = 'top_m_compare'; $id = 'top_m_compare';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div> <hr />
        <div class="row-fluid">
		<div class="span2"><?php echo $text_m_account; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_account; $name	= 'top_m_account'; $id = 'top_m_account';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	</div><hr />       
      <div class="row-fluid">
		<div class="span2"><?php echo $text_m_cart; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_cart; $name	= 'top_m_cart'; $id = 'top_m_cart';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	</div><hr /> 
     <div class="row-fluid">
		<div class="span2"><?php echo $text_m_checkout; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_checkout; $name	= 'top_m_checkout'; $id = 'top_m_checkout';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	</div><hr />                        
        
       <div class="row-fluid">
		<div class="span2"><?php echo $text_m_brand; ?></div>
		<div class="span10">
          <?php 
			$valueq 	= $top_m_brand; $name	= 'top_m_brand'; $id = 'top_m_brand';
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
			$valueq 	= $top_m_spec; $name = 'top_m_spec'; $id = 'top_m_spec';
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
			$valueq 	= $top_news; $name = 'top_news'; $id = 'top_news';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />
        <table class="all_topmenulink2">
         <thead>
         <tr><td><?php echo $text_top_cusomlink; ?></td><td><?php echo $text_top_url; ?></td><td><?php echo $entry_sort_order; ?></td><td></td></tr></thead>
        	<?php	
          if(isset($mattlink2['Header_m_links'])){
	          foreach($mattlink2['Header_m_links'] as $linkId2 => $link){ 
      	       ?>
                <tbody id="numberlink2<?php echo $linkId2; ?>">
                <tr><td>
      			
      			<?php 
             foreach ($languages as $langId => $lang) {
               
                  if (array_key_exists($lang['language_id'], $link['namelink']))
                     $valueq = isset($link['namelink'][$lang['language_id']]) ? $link['namelink'][$lang['language_id']] : '';
                  else 
                     $valueq = '';
      				$name			= 'mattimeolinkheader[Header_m_links][' . $linkId2 . '][namelink][' . $lang['language_id'] . ']';
      				$id				= 'Header_m_links-' . $linkId2 . '-namelink' . '-' . $lang['language_id'];
      			   ?>
                     <input  type="text" class='input-medium' placeholder="<?php echo $text_top_cusomlink; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>" >
                     <img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" >
                      <?php } ?>
                     </td>
                     <td>
      			   <?php 
           
				$valueq 			= isset($link['url']) ? $link['url'] : '';
				$name			= 'mattimeolinkheader[Header_m_links][' . $linkId2 . '][url]';
				$id				= 'Header_m_links-' . $linkId2 . '-url';
   			?>
	        		<input type="text" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>"></td>
                    <td>
            <?php 
				$valueq 			= isset($link['sort']) ? $link['sort'] : '';
				$name			= 'mattimeolinkheader[Header_m_links][' . $linkId2 . '][sort]';
				$id				= 'Header_m_links-' . $linkId2 . '-sort';
   			?>
	        		<input type="text" class='input-medium' placeholder="<?php echo $entry_sort_order; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">
           </td>
           <td>  
			   <a title="Remove" onclick="$('#numberlink2<?php echo $linkId2; ?>').remove();" class="btn btn-danger">&times;</a>

			   </td></tr>
            </tbody>
            	<?php  
            }     
			}
        	else $linkId2=0;
   	?>
            <tfoot>
            <tr><td><label class="control-label addNew2"><a class="btn btn-success"><?php echo $text_addlink; ?></a></label></td></tr>
            </tfoot>            </table> 
        
        
        
        </div>
        

<script type="text/javascript">
$('.addNew2 .btn').live('click', function(){
	addNewlink2();
});
var numberlink2 = <?php echo ++$linkId2; ?>;
function addNewlink2(){
		
	     html = '     <tbody id="numberlink2' + numberlink2 + '">';
		 html += '    <tr>'; 
		 html += '    <td>';
         <?php foreach ($languages as $language) { ?>
		
		 html += '    <input class="input-medium"  placeholder="<?php echo $text_top_cusomlink; ?>" type="text" name="mattimeolinkheader[Header_m_links][' + numberlink2 + '][namelink][<?php echo $language['language_id']; ?>]"  value="">';
		 html += '    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" >';
		 
		  <?php } ?>
		 html += '	  </td>'; 
		 html += '	  <td>';
         html += '	  <input class="input-medium" title="URL" placeholder="<?php echo $text_top_url; ?>" type="text" name="mattimeolinkheader[Header_m_links][' + numberlink2 + '][url]"  value="">';
		 html += '	  </td>';
		 html += '	  <td>';
         html += '	  <input class="input-medium" title="Sort order" placeholder="<?php echo $entry_sort_order; ?>" type="text" name="mattimeolinkheader[Header_m_links][' + numberlink2 + '][sort]"  value="">';
	     html += '	  </td>';
		 html += '	  <td>';
	     html += '	<a title="Remove" onclick="$(\'#numberlink2' + numberlink2 + '\').remove();" class="btn btn-danger">&times;</a>';
		 html += '	</td></tr>';
		 html += '	</tbody>';
	
	$('.all_topmenulink2 tfoot').before(html);
	numberlink2++;
}

</script>	

