<div class="form-horizontal ft-contacts">
<h4><?php echo $text_product; ?></h4> 
<hr />
<div class="row-fluid">
					 <div class="span2"><?php echo $text_product_zoom; ?></div>
					 <div class="span10">
						<?php 
							$ar 	= array( 'Default Colorbox' =>0 , 'Magnify zoom and popup' =>1  ); 
							$valueq 	= $product_zoom; $name	= 'product_zoom'; $id = 'product_zoom';
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
     <div class="span2"><b><?php echo $text_product_block; ?></b></div>
		 <div class="span10">
			<?php 
			$valueq 	= $status_product; $name = 'status_product'; $id = 'status_product';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></span>
			</span>
		</div>
	</div>
    
				
				<ul class="nav nav-tabs" id="products_page">
					
					<?php foreach ($languages as $language) { ?>
					<li>
						<a href="#ft-product<?php echo $language['language_id']; ?>">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<div class="tab-content">
                <?php foreach ($languages as $language) { ?>
					<div class="tab-pane langu" id="ft-product<?php echo $language['language_id']; ?>"> 
                    <div class="row-fluid">
							<div class="span10">
								<textarea name="matt_array[<?php echo $language['language_id']; ?>][product_text]" id="matt_array-<?php echo $language['language_id']; ?>-product_text"><?php echo isset($matt[$language['language_id']]['product_text']) ? $matt[$language['language_id']]['product_text'] : ''; ?></textarea>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
                
				<br /><br /> 
                <div class="row-fluid">
     <div class="span2"><b><?php echo $text_product_tab; ?></b></div>
		 <div class="span10">
			<?php 
			$valueq 	= $status_product_tab; $name = 'status_product_tab'; $id = 'status_product_tab';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></label>
			</span>
		</div>
	</div>
    
				<ul class="nav nav-tabs" id="products_tab">
					
					<?php foreach ($languages as $language) { ?>
					<li>
						<a href="#ft-product-tab<?php echo $language['language_id']; ?>">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<div class="tab-content">
                <?php foreach ($languages as $language) { ?>
					<div class="tab-pane langu2" id="ft-product-tab<?php echo $language['language_id']; ?>"> <div class="row-fluid">
							 <div class="span2"><?php echo $text_product_title; ?></div>
							 <div class="span10">
								<input placeholder="<?php echo $text_product_title; ?>" name="matt_array[<?php echo $language['language_id']; ?>][product_title_tab]" value="<?php echo isset($matt[$language['language_id']]['product_title_tab']) ? $matt[$language['language_id']]['product_title_tab'] : ''; ?>" id="matt_array-<?php echo $language['language_id']; ?>-product_title_tab" type="text">
							</div>
						</div><br /> 
                        <div class="row-fluid">
							<div class="span10">
								<textarea name="matt_array[<?php echo $language['language_id']; ?>][product_text_tab]" id="matt_array-<?php echo $language['language_id']; ?>-product_text_tab"><?php echo isset($matt[$language['language_id']]['product_text_tab']) ? $matt[$language['language_id']]['product_text_tab'] : ''; ?></textarea>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>  
    


	

</div>