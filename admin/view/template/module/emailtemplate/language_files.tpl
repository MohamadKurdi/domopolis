<?php echo $header; ?>

<div id="content">
	<div class="breadcrumb">
 	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
  		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  	<?php } ?>
	</div>

	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($error_attention) { ?><div class="attention"><?php echo $error_attention; ?></div><?php } ?>
	<?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="box" id="emailtemplate">
			<div class="heading">
				<h1><img src="view/image/language.png" alt="<?php echo $heading_language; ?>" /><?php echo $heading_language; ?></h1>

				<div class="buttons">
					<a href="<?php echo $cancel; ?>" class="button button-secondary"><span><?php echo $button_back; ?></span></a>
				</div>
			</div>

			<div class="content">
				<table class="form">
					<?php if(!empty($languages)){ ?>
					<tr>
						<td align="right">Language</td>
						<td>
							<select name="language">
								<?php foreach($languages as $language){ ?>
								<option value="<?php echo $language['language_id']; ?>"<?php if($id == $language['language_id']) echo ' selected="selected"'; ?>><?php echo $language['name']; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td align="right"><label for="field_admin">Admin?</label></td>
						<td>
							<input type="checkbox" name="admin" id="field_admin" value="1" style="vertical-align:middle; margin-top: 1px;"<?php if($type == 'admin') echo ' checked="checked"'; ?> />
						</td>
					</tr>
					<tr>
						<td align="right">Search</td>
						<td>
							<input type="text" name="language_search" value="<?php echo $language_search; ?>" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td class="buttons">
							<button type="submit" class="button"><span>Find</span></button>
						</td>
					</tr>
				</table>

				<h2><?php echo $language_files_count; ?> Language Files</h2>

				<p>The language editor works in a way that is doesn't edit core opencart language, by creating a new files with an underscore at the end with any custom changes. Depending on your permissions settings you may need to create this manually, on save it will let you know if you need to. </p>

				<div class="vtabs">
					<?php foreach($language_files as $key => $file){ ?>
					<?php if(!empty($file['files'])){ ?>
						<a href="#tab-<?php echo $key; ?>"><?php echo $key; ?></a>
					<?php } ?>
					<?php } ?>
				</div>

				<?php foreach($language_files as $key => $file){ ?>
				<?php if(!empty($file['files'])){ ?>
					<div class="vtabs-content" id="tab-<?php echo $key; ?>" style="display:none">
						<table class="list">
							<thead>
								<tr>
									<td style="font-weight:bold; padding:8px 5px;"><?php echo $file['dir']; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php foreach($file['files'] as $filename){ ?>
								<tr onclick="window.location = '<?php echo $filename['action']; ?>'">
									<td style="font-weight:bold; padding:5px;">
										<?php echo html_entity_decode($filename['file'], ENT_QUOTES, 'UTF-8'); ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
				<?php } ?>

			</div>
		</div>
	</form>
</div>

<link type="text/css" href="view/stylesheet/module/emailtemplate.css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>

<?php echo $footer; ?>