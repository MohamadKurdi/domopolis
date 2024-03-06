<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php echo $newspanel; ?>
<div class="box">
<div class="heading order_head">
	<h1><?php echo $heading_title; ?></h1>
	<div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a>
	<a class="button" onclick="$('form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a> 
	<a class="button" onclick="$('form').submit();" class="button sterge"><span><?php echo $button_delete; ?></span></a></div>
</div>
 <div class="content">
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
	<table class="list">
		<thead>
			<tr>
				<td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'delete\']').attr('checked', this.checked);$.uniform.update();" /></td>
				<td class="left">
					<?php if ($sort == 'nd.title') { ?>
						<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
					<?php } else { ?>
						<a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
					<?php } ?>
				</td>
				<td class="left">
					<?php if ($sort == 'na.name') { ?>
						<a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_nauthor; ?></a>
					<?php } else { ?>
						<a href="<?php echo $sort_author; ?>"><?php echo $entry_nauthor; ?></a>
					<?php } ?>
				</td>
				<td class="right">
					<?php if ($sort == 'n.status') { ?>
						<?php echo $column_status; ?>
					<?php } else { ?>
						<?php echo $column_status; ?>
					<?php } ?>
				</td>
				<td class="right">Просмотров</td>
				<td class="right"><?php echo $column_action; ?></td>
			</tr>
		</thead>
		<tbody>
			<?php if ($news) { ?>
				<?php $class = 'odd'; ?>
				<?php foreach ($news as $news_story) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<tr class="<?php echo $class; ?>">
						<td align="center" style="padding-top: 4px;">
							<?php if ($news_story['delete']) { ?>
								<input type="checkbox" name="delete[]" value="<?php echo $news_story['news_id']; ?>" checked="checked" />
							<?php } else { ?>
								<input type="checkbox" name="delete[]" value="<?php echo $news_story['news_id']; ?>" />
							<?php } ?>
						</td>
						<td class="left"><?php echo $news_story['title']; ?></td>
						<td class="left"><?php echo $news_story['author']; ?></td>
						<td class="right"><?php echo $news_story['status']; ?></td>
						<td class="right"><?php echo $news_story['viewed']; ?></td>
						<td class="right">
							<?php foreach ($news_story['action'] as $action) { ?>
							<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			<?php } else { ?>
				<tr class="even">
					<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>
</div>
