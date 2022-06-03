<?php echo $header; ?>

<div id="content">
	<div class="breadcrumb">
		<?php foreach( $breadcrumbs as $breadcrumb ) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	
	<?php if( $error_warning ) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	
	<?php if( $success ) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/module.png" alt="<?php echo $heading_title; ?>" /><?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="location = '<?php echo $back; ?>';" class="button"><span><?php echo $button_back; ?></span></a>
				<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
			</div>
		</div>
		
		<div class="content">
				<div class="htabs">
					<a style="display: block" class="selected" href="<?php echo $tab_action_settings; ?>"><?php echo $tab_settings; ?></a>
					<a style="display: block" href="<?php echo $tab_action_css; ?>"><?php echo $tab_css; ?></a>
					<a style="display: block" href="<?php echo $tab_action_about; ?>"><?php echo $tab_about; ?></a>
				</div>
			
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<table class="form">
						<tbody>
							<tr>
								<td>
									<?php echo $entry_active_in_views; ?>
								</td>
								<td>
									<label for="v_cart">
										<input id="v_cart" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_v_cart]"<?php echo empty( $settings[$_name.'_v_cart'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_cart; ?>
									</label>
									<br />
									<!--<label for="v_product">
										<input id="v_product" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_v_product]"<?php echo empty( $settings[$_name.'_v_product'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_product; ?>
									</label>
									<br />-->
									<label for="v_category">
										<input id="v_category" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_v_category]"<?php echo empty( $settings[$_name.'_v_category'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_category; ?>
									</label>
									<br />
									<label for="v_search">
										<input id="v_search" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_v_search]"<?php echo empty( $settings[$_name.'_v_search'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_search; ?>
									</label>
									<br />
									<label for="v_manufacturer">
										<input id="v_manufacturer" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_v_manufacturer]"<?php echo empty( $settings[$_name.'_v_manufacturer'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_manufacturer; ?>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $entry_active_in_modules; ?>
								</td>
								<td>
									<label for="m_bestsellers">
										<input id="m_bestsellers" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_m_bestsellers]"<?php echo empty( $settings[$_name.'_m_bestsellers'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_bestsellers; ?>
									</label>
									<br />
									<label for="m_featured">
										<input id="m_featured" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_m_featured]"<?php echo empty( $settings[$_name.'_m_featured'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_featured; ?>
									</label>
									<br />
									<label for="m_latest">
										<input id="m_latest" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_m_latest]"<?php echo empty( $settings[$_name.'_m_latest'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_latest; ?>
									</label>
									<br />
									<label for="m_special">
										<input id="m_special" type="checkbox" value="1" name="settings[<?php echo $_name; ?>_m_special]"<?php echo empty( $settings[$_name.'_m_special'] ) ? '' : ' checked="checked"'; ?> /> <?php echo $text_special; ?>
									</label>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
		</div>
	</div>
</div>
<?php echo $footer; ?>