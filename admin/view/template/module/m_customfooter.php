
<div class="form-horizontal custom-ft">

	<div class="control-group">
	<label class="control-label"><h4><?php echo $footer_heading; ?></h4></label>	
		<div class="controls">
			
			<?php 
			$ar 	= array( 'Only on home page' => 'common/home', 'On all pages' => 'all'); 
			$dbKey 	= $footer_status_home; $name = 'footer_status_home'; $id = 'footer_status_home';
			?>
			<div class="btn-group" data-toggle="buttons-radio">
				<?php foreach ($ar as $key => $value) { ?>
					<?php ($value ==  $dbKey) ? $selected = ' active' : $selected=''; ?>
					<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
						<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($dbKey == $value) { ?>checked="checked"<?php }?>>
						<?php echo $key; ?>
					</label>
				<?php } ?>
			</div>

		</div>
	</div>


	<div class="tabs-below tabbable" data-theme="tab_switch" id='custom-ft'>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_about"><?php echo $footer_comptext_text; ?></a>
				<?php 
					$dbKey 	= $comptext_status; $name = 'comptext_status'; $id	= 'comptext_status';
				?>
				<span class="switch">
					<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($dbKey) { ?>checked="checked"<?php }?> value="1">
					<label for="<?php echo $id; ?>" class="switch-img"></label>
				</span>
                
			</li>
            <li> <a href="#tab_twitter"><?php echo $footer_twitter; ?></a>
				
				<?php 
					$dbKey 	= $twitter_f_status; $name	= 'twitter_f_status'; $id = 'twitter_f_status';
				?>
				<span class="switch">
					<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($dbKey) { ?>checked="checked"<?php }?> value="1">
					<label for="<?php echo $id; ?>" class="switch-img"></label>
				</span>
               
			</li>
            
			
			<li><a href="#tab_facebook"><?php echo $footer_facebook; ?></a>
				
				<?php 
					$dbKey 	= $facebook_f_status; $name	= 'facebook_f_status'; $id	= 'facebook_f_status';
				?>
				<span class="switch">
					<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($dbKey) { ?>checked="checked"<?php }?> value="1">
					<label for="<?php echo $id; ?>" class="switch-img"></label>
				</span>
                
			</li> 
            <li><a href="#tab_vk"><?php echo $footer_vk; ?></a>
				
				<?php 
					$dbKey 	= $vk_f_status; $name = 'vk_f_status'; $id = 'vk_f_status';
				?>
				<span class="switch">
					<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($dbKey) { ?>checked="checked"<?php }?> value="1">
					<label for="<?php echo $id; ?>" class="switch-img"></label>
				</span>
                
			</li>
           
		</ul>
		<div class="tab-content">
			<div id="tab_about" class="tab-pane active">
				
				<ul class="nav nav-tabs" id="footer_comptextus">
					
					<?php foreach ($languages as $language) { ?>
					<li>
						<a href="#ft-about<?php echo $language['language_id']; ?>">
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<div class="tab-content">

					<?php foreach ($languages as $language) { ?>
					<div class="tab-pane langu" id="ft-about<?php echo $language['language_id']; ?>">
						<div class="control-group">
							<label class="control-label"><?php echo $footer_title; ?></label>
							<div class="controls">
								<input placeholder="<?php echo $footer_title; ?>" name="matt_array[<?php echo $language['language_id']; ?>][comptext_title]" value="<?php echo isset($matt[$language['language_id']]['comptext_title']) ? $matt[$language['language_id']]['comptext_title'] : ''; ?>" id="matt_array-<?php echo $language['language_id']; ?>-comptext_title" type="text">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo $footer_descr; ?></label>
							<div class="controls">
								<textarea name="matt_array[<?php echo $language['language_id']; ?>][comptext_text]" id="matt_array-<?php echo $language['language_id']; ?>-comptext_text"><?php echo isset($matt[$language['language_id']]['comptext_text']) ? $matt[$language['language_id']]['comptext_text'] : ''; ?></textarea>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				
			</div>
			<div id="tab_twitter" class="tab-pane">
				<div class="control-group">
					<label class="control-label"><?php echo $footer_title; ?></label>
					<div class="controls">
						<input type="text" name="twitter_f_title" value="<?php echo $twitter_f_title; ?>">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Twitter Username</label>
					<div class="controls">
						<input type="text" name="twitter_f_user" value="<?php echo $twitter_f_user; ?>" >
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Widget ID</label>
					<div class="controls">
						<input type="text" name="twitter_f_id" value="<?php echo $twitter_f_id; ?>" >
						<span class="infohelp"><a id='twitterhelp'><?php echo $footer_help; ?></a></span>
						<div class='helppopup1 disableimg'><img src="view/image/mattimeotheme/twitter.png"></div>
					</div>
				</div>
                				<div class="control-group">
					<label class="control-label"><?php echo $footer_twitterlink; ?></label>
					<div class="controls colorfield">
						<input type="text"  name="twitter_f_link" value="<?php echo $twitter_f_link; ?>" class="color {required:false,hash:true}" >
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo $footer_theme; ?></label>
					<div class="controls">
						<?php 
							$ar 	= array( 'Dark' => 'dark', 'Light' => 'light' );
							$dbKey 	= $twitter_f_theme; $name = 'twitter_f_theme'; $id	= 'twitter_f_theme';
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $dbKey) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($dbKey == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
					</div>
				</div>
			
				
			</div>
			<div id="tab_facebook" class="tab-pane">
				
				<div class="control-group">
					<label class="control-label"><?php echo $footer_title; ?></label>
					<div class="controls">
						<input type="text" name="facebook_f_title" value="<?php echo $facebook_f_title; ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Facebook Name</label>
					<div class="controls">
						<input type="text" name="facebook_f_name" value="<?php echo $facebook_f_name; ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo $footer_theme; ?></label>
					<div class="controls">
						<?php 
						$ar 	= array( 'Light theme' => 'light', 'Dark theme' => 'dark' ); 
						$dbKey 	= $facebook_f_theme; $name = 'facebook_f_theme'; $id = 'facebook_f_theme';
						?>
						<div class="btn-group" data-toggle="buttons-radio" style='margin-top:5px; margin-right:10px;'>
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $dbKey) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($dbKey == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
            <div id="tab_vk" class="tab-pane">
				
				<div class="control-group">
					<label class="control-label"><?php echo $footer_title; ?></label>
					<div class="controls">
						<input type="text" name="vk_f_title" value="<?php echo $vk_f_title; ?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Vkontakte id</label>
					<div class="controls">
						<input type="text" name="vk_f_id" value="<?php echo $vk_f_id; ?>">
					</div>
				</div>
				
			</div>
            
            
		</div>
	</div>
</div>

