<?php

 if ( $this->config->get( 'opengraph_status' ) == 1 ) { 
    $this->document 
    ->addMeta( 'og:type', 'og:website', 'property' ) 
    ->addMeta( 'og:title', $category_info['name'], 'property' ) 
    ->addMeta( 'og:url', $this->url->link('product/category', 'path=' . $this->request->get['path']), 'property' ) 
    ->addMeta( 'og:image', $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height')), 'property' ) 
    ->addMeta( 'og:description', str_replace( "\"", "&quot;", utf8_substr(trim(strip_tags( html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8') ), " \t\n\r"), 0, 500) . '..' ), 'property' )
    ->addMeta( 'og:site_name', $this->config->get('config_name'), 'property' ); 
}