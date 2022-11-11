<? if( $this->config->get( 'opengraph_status' ) == 1 ) {
	$this->document ->addMeta( 'og:type', 'product', 'property' ) 
	->addMeta( 'og:title', $product_info['name'], 'property' ) 
	->addMeta( 'og:url', $this->url->link( 'product/product', 'product_id=' . $product_info['product_id'] ), 'property' ) 
	->addMeta( 'product:price:amount', $product_info['special'] ? $this->currency->format($product_info['special'], '', '', false) : $this->currency->format($product_info['price'], '', '', false), 'property' ) 
	->addMeta( 'product:price:currency', $this->currency->getCode(), 'property' ) 
	->addMeta( 'og:image', $this->model_tool_image->resize( $product_info['image'], 600, 600 ), 'property' ) 
	->addMeta( 'og:description', str_replace( "\"", "&quot;", utf8_substr(trim(strip_tags( html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8') ), " \t\n\r"), 0, 500) . '..' ), 'property' ) 
	->addMeta( 'og:site_name', $this->config->get('config_name'), 'property' ); } ?>