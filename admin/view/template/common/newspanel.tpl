<link rel="stylesheet" href="view/blog-res/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="view/blog-res/news-blog.css" />
<?php if (isset($this->request->get['route'])) { ?>
	<?php if ($this->request->get['route'] != "common/home") { ?>
		<!--<link rel="stylesheet" href="view/blog-res/unitheme/css/uniform.css" />-->
		<script src="view/blog-res/jquery.uniform.min.js"></script>
		<script type='text/javascript'>
			$(function () {
				$("select, :radio, :checkbox").uniform();
			});
		</script>
	<?php } ?>
<?php } ?>
<script src="view/blog-res/tooltipsy.min.js"></script>
<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box blogwidget" style="margin-bottom: 8px;">
	<div class="insideblogwidget">
		<?php if ($validatingblog === "none") { ?>
			<span class="bnline">You need to install the blog module</span><br/>
			<a class="blogbutton" href="<?php echo $adddb; ?>">Install</a>
			<?php } elseif ($validatingblog === "nu") { ?>
			<span class="bnline">You need to upgrade the blog module</span><br/>
			<a class="blogbutton" href="<?php echo $updb; ?>">Upgrade</a>
			<?php } elseif ($validatingblog === "nu2") { ?>
			<span class="bnline">You need to upgrade the blog module</span><br/>
			<a class="blogbutton" href="<?php echo $updb2; ?>">Upgrade</a>
			<?php } elseif ($validatingblog === "nu3") { ?>
			<span class="bnline">You need to upgrade the blog module</span><br/>
			<a class="blogbutton" href="<?php echo $updb3; ?>">Upgrade</a>
			<?php } elseif ($validatingblog === "ok") { ?>
			<div class="bwinfo">
				<img src="view/blog-res/blog.png" alt="Blog" />
				<a class="bwlink" href="<?php echo $npages; ?>" title="<?php echo $entry_npages; ?>"><i class="fa fa-book"></i><span class="bli">Art</span></a>
				<a class="bwlink bwodd" href="<?php echo $ncategory; ?>" title="<?php echo $entry_ncategory; ?>"><i class="fa fa-folder-open"></i><span class="bli">Cat</span></a>
				<a class="bwlink" href="<?php echo $tocomments; ?>" title="<?php echo $text_commod; ?>"><i class="fa fa-comments"></i><span class="bli" style="margin-left: -10px;">Com</span></a>
				<a class="bwlink bwodd" href="<?php echo $nauthor; ?>" title="<?php echo $text_nauthor; ?>"><i class="fa fa-user"></i><span class="bli" style="margin-left: -9px;">Aut</span></a>
				<a class="bwlink" href="<?php echo $ncmod; ?>" title="<?php echo $entry_ncmod; ?>"><i class="fa fa-cogs"></i><span class="bli" style="margin-left: -12px;">Amm</span></a>
				<a class="bwlink bwodd" href="<?php echo $nmod; ?>" title="<?php echo $entry_nmod; ?>"><i class="fa fa-archive"></i><span class="bli" style="margin-left: -10px;">Lnm</span></a>
				<div class="bwstats">
					<table>
						<tr><td class="bwodd"><?php echo $text_tcaa; ?></td><td class="bwresult"><span><?php echo $total_comments_approval; ?></span></td>
							<td><?php echo $text_comtot; ?></td><td class="bwresult"><span><span><?php echo $total_coments; ?></span></span></td>
							<td class="bwodd"><?php echo $text_articles; ?></td><td class="bwresult"><span><?php echo $total_articles; ?></span></td>
						</tr>
					</table>
				</div>
			</div>
			<script type='text/javascript'>
				$('.bwlink').tooltipsy({
					offset: [0, 5]
				});
			</script>
		<?php } ?>
	</div>
</div>
