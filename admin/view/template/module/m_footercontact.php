
<div class="form-horizontal ft-contacts">
<h4><?php echo $text_contact_heading; ?></h4>
<hr />
 <div class="row-fluid">
		 <div class="span2"><b><?php echo $text_contact_copyright; ?></b></div>
		 <div class="span10">
           <input type="text" name="f_contact_copyright" value="<?php echo $f_contact_copyright; ?>">
		</div>
		</div><hr />


<ul class="nav nav-tabs" id="tab_footersite" > 
                <li><a href="#footer_contact"><?php echo $text_contact_footer; ?></a></li>
                <li><a href="#footer_socicon"><?php echo $text_socicon; ?></a></li>
                 <li><a href="#footer_extras"><?php echo $text_extras; ?></a></li>
                  <li><a href="#footer_custtext"><?php echo $text_product_block; ?></a></li>
                  <li><a href="#footer_payicon"><?php echo $footer_payment; ?></a></li>
                </ul>
				<div class="tab-content">
  <!--*******************************************************************--> 
       <div id="footer_contact" class="tab-pane langu5"> 
                      
         <div class="row-fluid">
     <div class="span2"><b><?php echo $text_contact_footer; ?></b></div>
		 <div class="span10">
			<?php 
			$valueq 	= $f_contact_status; $name	= 'f_contact_status'; $id = 'f_contact_status';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	</div><hr />

	 <div class="row-fluid">
		 <div class="span2"><?php echo $text_contact_phone; ?></div>
		 <div class="span10">
          <textarea type="text" name="f_contact_phone" cols="100" rows="2"><?php echo $f_contact_phone; ?></textarea>
		</div>
	</div><hr />

	 <div class="row-fluid">
		 <div class="span2"><?php echo $text_contact_fax; ?></div>
		 <div class="span10">
          <input type="text" name="f_contact_fax" value="<?php echo $f_contact_fax; ?>">
		</div>
        </div><hr />

	 <div class="row-fluid">
		 <div class="span2"><?php echo $text_contact_email; ?></div>
        <div class="span10">
          <input type="text" name="f_contact_email" value="<?php echo $f_contact_email; ?>">
		</div>
        </div><hr />
     <div class="row-fluid">
		 <div class="span2"><?php echo $text_contact_skype; ?></div>
       <div class="span10">
          <input type="text" name="f_contact_skype" value="<?php echo $f_contact_skype; ?>">
		</div>
		</div><hr />

         <div class="row-fluid">
		 <div class="span2"><?php echo $text_contact_address; ?></div>
		 <div class="span10">
          <textarea name="f_contact_address"  cols="100" rows="3"><?php echo $f_contact_address; ?></textarea>
		</div>
		</div>
                
                
      </div>
  <!--*******************************************************************--> 
   <div id="footer_socicon" class="tab-pane"> 

   <table id="networkicons">
    <?php 
	if(isset($mattnetworks)){
	foreach($mattnetworks as $network_row => $mattnetwork){  ?>
     <tbody  id="network<?php echo $network_row; ?>">
     <tr><td><div class="image">
              <img src="<?php echo $mattnetwork['thumb']; ?>" alt="" id="thumbnetw<?php echo $network_row; ?>">
                <?php 
				$valueq 			= isset($mattnetwork['image']) ? $mattnetwork['image'] : '';
				$name			= 'mattnetwork[' . $network_row . '][image]';
				$id				= 'imagenetw' . $network_row;
   			?>
	        		<input type="hidden" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">

                  <br>
                  <a onclick="image_upload('imagenetw<?php echo $network_row; ?>', 'thumbnetw<?php echo $network_row; ?>');"><?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumbnetw<?php echo $network_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#imagenetw<?php echo $network_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
                </div></td>
                <td><input class="input-medium" type="text" name="mattnetwork[<?php echo $network_row; ?>][title]" value="<?php echo $mattnetwork['title']; ?>" /></td>
                <td><input type="text" name="mattnetwork[<?php echo $network_row; ?>][href]" value="<?php echo $mattnetwork['href']; ?>" /></td>
                <td><a onclick="$('#network<?php echo $network_row; ?>').remove();" class="btn btn-danger">&times;</a></td>
                </tr>
     </tbody>    
     <?php $network_row++; ?>
     <?php 
	   } 
	   } else  $network_row=0; ?>
             <tfoot>
            <tr>
              <td><label class="addNetwork"><a class="btn btn-success"><?php echo $footer_add_icon; ?></a></label></td>
            </tr>
          </tfoot>
        </table>	   
        
                                            
                   
        </div>
    <!--*******************************************************************--> 
   <div id="footer_extras" class="tab-pane">  
        
                  
        <div class="row-fluid">
		 <div class="span2"><?php echo $link_contact; ?></div>
		 <div class="span10">
          <?php 
			$valueq 	= $f_link1; $name = 'f_link1'; $id = 'f_link1';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />  
       
        <div class="row-fluid">
		 <div class="span2"><?php echo $link_brand; ?></div>
		 <div class="span10">
          <?php 
			$valueq 	= $f_link2; $name = 'f_link2'; $id = 'f_link2';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr /> 
       
        <div class="row-fluid">
		 <div class="span2"><?php echo $link_gift; ?></div>
		 <div class="span10">
          <?php 
			$valueq 	= $f_link3; $name = 'f_link3'; $id = 'f_link3';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />  
       
        <div class="row-fluid">
		 <div class="span2"><?php echo $link_affiliates; ?></div>
		 <div class="span10">
          <?php 
			$valueq 	= $f_link4; $name = 'f_link4'; $id = 'f_link4';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div> <hr /> 
       
        <div class="row-fluid">
		 <div class="span2"><?php echo $link_specials; ?></div>
		 <div class="span10">
          <?php 
			$valueq 	= $f_link5; $name = 'f_link5'; $id = 'f_link5';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div><hr />  
       
        <div class="row-fluid">
		 <div class="span2"><?php echo $link_returns; ?></div>
		 <div class="span10">
          <?php 
			$valueq 	= $f_link6; $name = 'f_link6'; $id = 'f_link6';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div> <hr /> 
       
        <div class="row-fluid">
		 <div class="span2"><?php echo $link_sitemap; ?></div>
		 <div class="span10">
          <?php 
			$valueq 	= $f_link7; $name = 'f_link7'; $id = 'f_link7';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	   </div>                            
                   
      </div>
     <!--*******************************************************************--> 
                   <div id="footer_custtext" class="tab-pane"> 
                     
                   
                   
 <ul class="nav nav-tabs" id="custom_footer_text">
					
					<?php foreach ($languages as $language) { ?>
					<li>
						<a href="#cust_text<?php echo $language['language_id']; ?>">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<div class="tab-content">
                <?php foreach ($languages as $language) { ?>
					<div class="tab-pane langu4" id="cust_text<?php echo $language['language_id']; ?>">
                    <div class="row-fluid">
							<div class="span2"><?php echo $footer_title; ?></div>
							<div class="span10">
								<input placeholder="<?php echo $footer_title; ?>" name="matt_array[<?php echo $language['language_id']; ?>][compfootertext_title]" value="<?php echo isset($matt[$language['language_id']]['compfootertext_title']) ? $matt[$language['language_id']]['compfootertext_title'] : ''; ?>" id="matt_array-<?php echo $language['language_id']; ?>-compfootertext_title" type="text">
							</div>
						</div><br />
						<div class="row-fluid">
							<div class="span10">
								<textarea name="matt_array[<?php echo $language['language_id']; ?>][footer_payment_text]" id="matt_array-<?php echo $language['language_id']; ?>-footer_payment_text"><?php echo isset($matt[$language['language_id']]['footer_payment_text']) ? $matt[$language['language_id']]['footer_payment_text'] : ''; ?></textarea>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
                                         
                   
                   </div>  
      <!--*******************************************************************--> 
                   <div id="footer_payicon" class="tab-pane">  
                   
                    <table id="paymenticons">
    <?php 
	if(isset($mattimgs)){
	foreach($mattimgs as $payment_row => $mattimg){  ?>
     <tbody  id="payment<?php echo $payment_row; ?>">
     <tr><td><div class="image">
              <img src="<?php echo $mattimg['thumb']; ?>" alt="" id="thumb<?php echo $payment_row; ?>">
                <?php 
				$valueq 			= isset($mattimg['image']) ? $mattimg['image'] : '';
				$name			= 'mattimg[' . $payment_row . '][image]';
				$id				= 'image' . $payment_row;
   			?>
	        		<input type="hidden" class='input-medium' placeholder="<?php echo $text_top_url; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $valueq; ?>">

                  <br>
                  <a onclick="image_upload('image<?php echo $payment_row; ?>', 'thumb<?php echo $payment_row; ?>');"><?php echo $text_browse; ?>
                  </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $payment_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $payment_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
                </div></td>
                <td><input class="input-medium" type="text" name="mattimg[<?php echo $payment_row; ?>][title]" value="<?php echo $mattimg['title']; ?>" /></td>
                <td><a onclick="$('#payment<?php echo $payment_row; ?>').remove();" class="btn btn-danger">&times;</a></td>
                </tr>
     </tbody>    
     <?php $payment_row++; ?>
     <?php 
	   } 
	   } else  $payment_row=0; ?>
                  <tfoot>
            <tr>
              <td><label class="addPayment"><a class="btn btn-success"><?php echo $footer_add_icon; ?></a></label></td>
            </tr>
          </tfoot>
        </table>	  
                         
                   
                   </div>  
                                 
                                                  
                </div>
</div>

<script type="text/javascript"><!--
$('.addNetwork .btn').live('click', function(){
	addNetworkIcon();
});
var network_row = <?php echo $network_row; ?>;

function addNetworkIcon(){
	   html  = '<tbody id="network' + network_row + '">';
       html += '<tr>';
       html += '<td><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumbnetw' + network_row + '" /><input type="hidden" name="mattnetwork[' + network_row + '][image]" value="" id="imagenetw' + network_row + '" ><br ><a onclick="image_upload(\'imagenetw' + network_row + '\', \'thumbnetw' + network_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumbnetw' + network_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#imagenetw' + network_row + '\').attr(\'value\', \'\');"><?php   echo $text_clear; ?></a></div></td>';
	  html += '<td><input class="input-medium" type="text" name="mattnetwork[' + network_row + '][title]" value="" placeholder="Title"></td>';
	  html += '<td><input type="text" name="mattnetwork[' + network_row + '][href]" value="" placeholder="Link"></td>';
      html += '<td><a onclick="$(\'#network' + network_row  + '\').remove();" class="btn btn-danger">&times;</a></td>';
      html += '</tr>';
      html += '</tbody>'; 
	  
	  $('#networkicons tfoot').before(html);
	network_row++;
}


$('.addPayment .btn').live('click', function(){
	addIcon();
});
var payment_row = <?php echo $payment_row; ?>;

function addIcon(){
	   html  = '<tbody id="payment' + payment_row + '">';
       html += '<tr>';
       html += '<td><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + payment_row + '" /><input type="hidden" name="mattimg[' + payment_row + '][image]" value="" id="image' + payment_row + '" ><br ><a onclick="image_upload(\'image' + payment_row + '\', \'thumb' + payment_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + payment_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + payment_row + '\').attr(\'value\', \'\');"><?php   echo $text_clear; ?></a></div></td>';
	  html += '<td><input class="input-medium" type="text" name="mattimg[' + payment_row + '][title]" value="" placeholder="Title"></td>';
      html += '<td><a onclick="$(\'#payment' + payment_row  + '\').remove();" class="btn btn-danger">&times;</a></td>';
      html += '</tr>';
      html += '</tbody>'; 
	  
	  $('#paymenticons tfoot').before(html);
	payment_row++;
}

//--></script>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#featured-product' + ui.item.value).remove();
		
		$('#featured-product').append('<div id="featured-product' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" value="' + ui.item.value + '" /></div>');

		$('#featured-product div:odd').attr('class', 'odd');
		$('#featured-product div:even').attr('class', 'even');
		
		data = $.map($('#featured-product input'), function(element){
			return $(element).attr('value');
		});
						
		$('input[name=\'featured_product2\']').attr('value', data.join());
					
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#featured-product div img').live('click', function() {
	$(this).parent().remove();
	
	$('#featured-product div:odd').attr('class', 'odd');
	$('#featured-product div:even').attr('class', 'even');

	data = $.map($('#featured-product input'), function(element){
		return $(element).attr('value');
	});
					
	$('input[name=\'featured_product2\']').attr('value', data.join());	
});
//--></script> 