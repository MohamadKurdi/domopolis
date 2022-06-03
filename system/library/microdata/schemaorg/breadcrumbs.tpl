<?php if($this->config->get('schemaorg_status')== 1) { ?> 
	<?php if( ! empty( $breadcrumbs ) ) { ?> 
		<?php foreach( $breadcrumbs as $breadcrumb ) { ?>
			<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"> 
				<meta itemprop="url" content="<?php echo $breadcrumb['href']; ?>" > 
				<meta itemprop="title" content="<?php echo $breadcrumb['text']; ?>"> 
			</span> 
		<?php } ?> 
		
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
		
	<?php } ?> 
	
	
<?php } ?>