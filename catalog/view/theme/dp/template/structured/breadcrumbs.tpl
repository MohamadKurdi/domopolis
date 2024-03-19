<!--breadcrumbs-section-->
<section class="breadcrumbs-section">
	<div class="wrap">
		<!--breadcrumbs-->
		<div class="breadcrumbs">
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li>
						<a href="<?php echo $breadcrumb['href']; ?>">
							<span><?php echo $breadcrumb['text']; ?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		
		<script type="application/ld+json">
			{
				"@context": "http://schema.org",
				"@type": "BreadcrumbList",
				"itemListElement": [<?php $bi = 1; foreach ($breadcrumbs as $breadcrumb) { ?>{
					"@type": "ListItem",
					"position": <?php echo $bi; ?>,
					"item": {
						"@id": "<?php echo $breadcrumb['href']; ?>",
						"name": "<?php echo $breadcrumb['text']; ?>"
					}
				}<?php if($bi != count($breadcrumbs)){ ?>,<?php } ?><?php $bi++; } ?>]
			}	  
		</script>
		
		
		<!--/breadcrumbs-->
		<!-- collection, category, product, manufacturer -->

		<?php if (empty($page_type) || (!empty($page_type) && $page_type != 'product')) { ?>
			<?php if (!empty($heading_title)) { ?>
				<h1 class="title"><?php echo $heading_title; ?></h1>
			<?php } ?>
		<?php } ?>

		<?php if (!empty($tagline)) { ?>
			<h4 class="tagline"><?php echo $tagline; ?></h4>
		<?php } ?>
		
	</div>
</section>
<!--/breadcrumbs-section-->
