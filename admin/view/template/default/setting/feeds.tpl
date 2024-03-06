<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>	
	<div class="box">
		<div class="heading order_head">
			<h1><?php echo $heading_title; ?></h1>							
		</div>
		<div class="content">
			<div style="width:50%; float: left">
				<table class="list">
					<?php foreach ($feeds as $group => $group_feeds) { ?>
						<tr>
							<td colspan="4" style="padding-top: 10px;">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo $group; ?></span>
							</td>
						</tr>
						<?php foreach ($group_feeds as $feed) { ?>
							<tr>
								<td>
									<b <?php if ($feed['error']) { ?>style="color:red"<? } ?>><?php echo $feed['name']; ?></b>
								</td>
								<td>
									<?php echo $feed['size']; ?>
								</td>
								<td>
									<?php echo $feed['time']; ?>
								</td>
								<td>
									<a href="<?php echo $feed['href']; ?>" target="_blank"><i class="fa fa-external-link"></i></a>
								</td>
							</tr>
						<?php } ?>
					</tr>
				<?php } ?>
			</table>
		</div>


		<div style="width:49%; float: right">
			<table class="list">
				<tr>
					<td colspan="4">
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Сайтмапы</span>
					</td>
				</tr>
				<?php foreach ($sitemaps as $sitemap) { ?>
					<tr>
						<td>
							<b <?php if ($sitemap['error']) { ?>style="color:red"<? } ?>><?php echo $sitemap['name']; ?></b>
						</td>
						<td>
							<?php echo $sitemap['size']; ?>
						</td>
						<td>
							<?php echo $sitemap['time']; ?>
						</td>
						<td>
							<a href="<?php echo $sitemap['href']; ?>" target="_blank"><i class="fa fa-external-link"></i></a>
						</td>
					</tr>
				</tr>
			<?php } ?>
		</table>
	</div>
</div>
</div>
</div>
<?php echo $footer; ?>