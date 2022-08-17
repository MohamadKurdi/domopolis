
<script>
	window.dataLayer = window.dataLayer || [];	
	
	dataLayer.push({
		event: 'Users',
		eventCategory: 'Users',
		eventAction: <?php if ($logged) { ?>'Logged'<?php } else { ?>'NotLogged'<?php } ?>,
		eventLabel: <?php if ($logged) { ?><?php echo $logged; ?><?php } else { ?>'anonymous'<?php } ?>
	});
</script>