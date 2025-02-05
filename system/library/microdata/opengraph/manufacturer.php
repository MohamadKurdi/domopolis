<?php
 if ( $this->config->get( 'opengraph_status' ) == 1 ) { 
    $this->document 
    ->addMeta( 'og:type', 'og:website', 'property' ) 
    ->addMeta( 'og:title', $manufacturer_info['name'], 'property' ) 
    ->addMeta( 'og:url', $this->url->link( 'product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url ), 'property' ) 
    ->addMeta( 'og:image', $this->model_tool_image->resize( $manufacturer_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height') ), 'property' ) 
    ->addMeta( 'og:description', str_replace( "\"", "&quot;", utf8_substr(trim(strip_tags( html_entity_decode($manufacturer_info['description'], ENT_QUOTES, 'UTF-8') ), " \t\n\r"), 0, 500) . '..' ), 'property' ) 
    ->addMeta( 'og:site_name', $this->config->get('config_name'), 'property' ); 
}