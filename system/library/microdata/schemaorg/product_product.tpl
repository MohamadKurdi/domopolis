<?php if( $this->config->get('schemaorg_status') == 1 && isset($review_status)) { ?> 
	<span itemscope itemtype="http://schema.org/Product"> 
		<meta itemprop="name" content="<?php echo htmlspecialchars( $name, ENT_QUOTES ); ?>"> 
		<meta itemprop="url" content="<?php echo $url; ?>"> 
		<?php if( ! empty( $model ) ) { ?> <meta itemprop="model" content="<?php echo htmlspecialchars( $model, ENT_QUOTES ); ?>"> <?php } ?> 
		<?php if( ! empty( $manufacturer ) ) { ?> <meta itemprop="brand" content="<?php echo htmlspecialchars( $manufacturer, ENT_QUOTES ); ?>"> <?php } ?> 
		<span itemscope itemprop="offers" itemtype="http://schema.org/Offer"> 
			<?php if( $this->config->get('schemaorg_price') == 1 ) { ?> <meta itemprop="price" content="<?php if( ! empty( $special ) ) { echo preg_replace( "/[^0-9.]/", "", $special ); } else { echo preg_replace( "/[^0-9.]/", "", $price ); } ?>"> <?php } ?> 
			<?php if( $this->config->get('schemaorg_price') == 2 ) { ?> <meta itemprop="price" content="<?php if( ! empty( $special ) ) { echo preg_replace( "/[^0-9]/", "", $special ); } else { echo preg_replace( "/[^0-9]/", "", $price ); } ?>"> <?php } ?> 
			<meta itemprop="priceCurrency" content="<?php echo $priceCurrency; ?>"> 
			<link itemprop="availability" href="http://schema.org/<?php echo ( ( $availability ) ? 'InStock' : 'OutOfStock' ) ?>" /> </span>
			<?php if( $review_status ) { ?> <span itemscope itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating"> <meta itemprop="reviewCount" content="<?php echo $reviewCount; ?>"> 
			<meta itemprop="ratingValue" content="<?php echo $ratingValue; ?>"> <meta itemprop="bestRating" content="5"> <meta itemprop="worstRating" content="1"> </span> <?php } ?> 
			<?php if( $thumb ) { ?> <meta itemprop="image" content="<?php echo $image; ?>"> <?php } ?> <?php if( ! empty( $description ) ) { ?> <meta itemprop="description" content="<?php echo str_replace( "\"", "&quot;", utf8_substr(trim(strip_tags( html_entity_decode($description, ENT_QUOTES, 'UTF-8') ), " \t\n\r"), 0, 1500) . '..' ); ?>"> <?php } ?> 
			<?php if ( isset($microdata_reviews) ) { ?> 
				<?php foreach ( $microdata_reviews as $review ) { ?> 
				<span itemscope itemprop="review" itemtype="http://schema.org/Review"> 
					<meta itemprop="itemReviewed " content="<?php echo $heading_title; ?>"> 
					<meta itemprop="author" content="<?php echo $review['author']; ?>"> 
					<meta itemprop="datePublished" content="<?php echo $review['datePublished']; ?>"> 
					<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"> 
					<meta itemprop="ratingValue" content="<?php echo $review['ratingValue']; ?>"> 
					<meta itemprop="bestRating" content="5"> 
					<meta itemprop="worstRating" content="1"> </span>
					<meta itemprop="description" content="<?php echo $review['description']; ?>"> </span> 
				<?php } ?> 
			<?php } ?> </span> 
<?php } ?>