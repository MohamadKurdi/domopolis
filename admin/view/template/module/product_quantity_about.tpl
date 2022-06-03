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
			<div class="buttons"><a onclick="location = '<?php echo $back; ?>';" class="button"><span><?php echo $button_back; ?></span></a></div>
		</div>
		
		<div class="content">
				<div class="htabs">
					<a style="display: block" href="<?php echo $tab_action_settings; ?>"><?php echo $tab_settings; ?></a>
					<a style="display: block" class="selected" href="<?php echo $tab_action_about; ?>"><?php echo $tab_about; ?></a>
				</div>
			
				<table class="form">
					<tr>
						<td><?php echo $entry_ext_version; ?></td>
						<td><?php echo $ext_version; ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_author; ?></td>
						<td><?php echo $ext_author; ?></td>
					</tr>
				</table>
		</div>
	</div>
</div>
<?php echo $footer; ?>