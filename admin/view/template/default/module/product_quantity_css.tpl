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
					<a style="display: block" href="<?php echo $tab_action_settings; ?>"><?php echo $tab_settings; ?></a>
					<a style="display: block" class="selected" href="<?php echo $tab_action_css; ?>"><?php echo $tab_css; ?></a>
					<a style="display: block" href="<?php echo $tab_action_about; ?>"><?php echo $tab_about; ?></a>
				</div>
			
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<table class="form">
						<tbody>
							<tr>
								<td>
									<?php echo $entry_css; ?>
								</td>
								<td>
									<textarea name="css[<?php echo $_name; ?>_css]" style="width:99%; height:300px"><?php echo $css[$_name . '_css']; ?></textarea>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
		</div>
	</div>
</div>
<?php echo $footer; ?>