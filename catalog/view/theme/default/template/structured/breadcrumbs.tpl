<!--breadcrumbs-section-->
<section class="breadcrumbs-section">
	<div class="wrap">
		<!--breadcrumbs-->
		<div class="breadcrumbs">
			<ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
				<?php $ListItem = 1; ?>
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						<a href="<?php echo $breadcrumb['href']; ?>" itemprop="item">
							<span itemprop="name"><?php echo $breadcrumb['text']; ?></span>
						</a>
						<meta itemprop="position" content="<?php echo $ListItem++; ?>" />
					</li>
				<?php } ?>
			</ul>
		</div>
		
		
		<div class="breadcrumbs-content hidden" style="display:none;">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
					<a href="<?php echo $breadcrumb['href']; ?>" itemprop="url"><span itemprop="title"><?php echo $breadcrumb['text']; ?></span></a>
				</span>
			<?php } ?>		
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
		<?php if (!empty($page_type) && $page_type == 'product'){ ?>
			<?php if (empty($heading_title)) { ?>
				<h1 class="title"><?php echo $heading_title; ?></h1>
			<?php } ?>
			<?php } elseif (!empty($page_type) && $page_type == 'collection') { ?>
			<?php if (empty($heading_title)) { ?>
				<h1 class="title"><?php echo $heading_title; ?></h1>
			<?php } ?>
			<?php } elseif (!empty($page_type) && $page_type == 'manufacturer') { ?>
			<?php if (empty($heading_title)) { ?>
				<h1 class="title"><?php echo $heading_title; ?></h1>
			<?php } ?>	
			<?php } else { ?>
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
