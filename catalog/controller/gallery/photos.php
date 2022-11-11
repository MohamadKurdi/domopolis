<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryPhotos extends controller{
    private $cacher = array();
    private $current_language_id;

    public function index(){
        if (isset($this->request->get['album_id'])) {
            $album_id = (int)$this->request->get['album_id'];
            if (!empty($album_id)) {
                $this->section_photos($album_id);
            }else{
                $this->redirect($this->url->link('gallery/gallery'));
            }
        }else{
            $this->redirect($this->url->link('gallery/gallery'));
        }
    }
    private function section_photos($album_id){
        $this->data['microtime'] = microtime(true);
        
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('catalog/gallery');
        $this->load->language('product/gallery');
        $this->data['text_gallery_list']    = $this->language->get('text_gallery_list');
        $this->data['text_limit']           = $this->language->get('text_limit');

        $this->current_language_id = $this->config->get('config_language_id');
        
        $pre_album = $this->model_catalog_gallery->getAlbum($album_id);
        $total_cached_images_name = 'album_photos.total.'.md5($album_id.'10'.$this->current_language_id);

        if ($this->config->get('config_gallery_modules_cache_enabled')) {
            $total_cached = $this->cache->get($total_cached_images_name);
            if (!empty($total_cached)) {
                $pre_album['total_images'] = count($total_cached); 
            }else{
                $total_cached = $this->getAlbumImages($album_id, 1, 0);
                $this->cache->set($total_cached_images_name, $total_cached);
                $pre_album['total_images'] = count($total_cached);
            }
        }else{
            $pre_album['total_images'] = count($this->getAlbumImages($album_id, 1, 0));
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else { 
            $page = 1;
        } 

        if (isset($this->request->get['limit'])) {
            $limit = intval($this->request->get['limit']);
            if (($limit < 0) || ($limit >= $pre_album['total_images'])) {
                $this->redirect($this->url->link('gallery/photos', 'album_id=' . $album_id. '&limit=0'));
            }else{
                $url = '&limit='.(int)$limit;
            }
        } else {
            $limit = $pre_album['album_data']['photos_limit'];
            $this->cacher['limit'] = $limit;
            $url = '&limit='.$limit;
        }        
        $this->data['limit'] = $limit;
        
        $cached_page_name = 'album_gallery.'.md5($album_id.$page.$limit.$this->current_language_id);
        
        $this->cacher = $this->cache->get($cached_page_name);
        if (!empty($this->cacher) && $this->config->get('config_gallery_modules_cache_enabled')) {
            foreach ($this->cacher as $key => $value) {
                $this->data[$key] = $value;
            }
            $album = $this->data['album'];
        }else{

            $album = $pre_album;
            if (empty($album)) {
                $this->redirect($this->url->link('gallery/gallery'));
            }

            $this->cacher['limits'] = array();
            if ($album['album_data']['photos_limit'] != 0) {
                $this->cacher['limits'][$album['album_data']['photos_limit']] = array(
                    'text'  => $album['album_data']['photos_limit'],
                    'value' => $album['album_data']['photos_limit'],
                    'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=' . $album['album_data']['photos_limit'])
                );
            }
            $this->cacher['limits'][25] = array(
                'text'  => 25,
                'value' => 25,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=25')
            );
            
            $this->cacher['limits'][50] = array(
                'text'  => 50,
                'value' => 50,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=50')
            );

            $this->cacher['limits'][75] = array(
                'text'  => 75,
                'value' => 75,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=75')
            );
            
            $this->cacher['limits'][100] = array(
                'text'  => 100,
                'value' => 100,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=100')
            );
            $this->cacher['limits'][0] = array(
                'text'  => $this->language->get('text_limit_all'),
                'value' => 0,
                'href'  => $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&limit=0')
            );

            //Add data
            $album['album_name'] = $album['album_data']['album_name'][$this->current_language_id];
            
            $album['album_link'] = $this->url->link('gallery/gallery', 'album_id='.$album['album_id']);
            
            $cached_images_name = 'album_photos.'.md5($album_id.$page.$limit.$this->current_language_id);
            
            if ($this->config->get('config_gallery_modules_cache_enabled')) {   
                $cached = $this->cache->get($cached_images_name);
                if (!empty($cached)) {
                    $album['images'] = $cached;  
                }else{
                    $cached = $this->getAlbumImages($album_id, $page, $limit);
                    $this->cache->set($cached_images_name, $cached);
                    $album['images'] = $cached;
                }
            }else{
                $album['images'] = $this->getAlbumImages($album_id, $page, $limit);
            }
            
            if ($limit == 0) {
                $this->cacher['show_pagination'] = false;
            }else{
                $this->cacher['show_pagination'] = true;
                $pagination = new Pagination();
                $pagination->total = $album['total_images'];
                $pagination->page = $page;
                $pagination->limit = $limit;
                $pagination->text = $this->language->get('text_pagination');
                $pagination->url = $this->url->link('gallery/photos', 'album_id=' . $this->request->get['album_id']. '&page={page}'. $url);
                $this->cacher['pagination'] = $pagination->render();
            }
       

            foreach ($album['images'] as $img_key => $image) {
                if (empty($image['image'])) {
                    $image['thumb'] = $this->model_tool_image->resize('no_image.jpg', $album['album_data']['thumb_width'] , $album['album_data']['thumb_height']);
                    $image['popup'] = $this->model_tool_image->resize('no_image.jpg', $album['album_data']['popup_width'] , $album['album_data']['popup_height']);
                }else{
                    $image['thumb'] = $this->model_tool_image->resize($image['image'], $album['album_data']['thumb_width'] , $album['album_data']['thumb_height']);
                    $image['popup'] = $this->model_tool_image->resize($image['image'], $album['album_data']['popup_width'] , $album['album_data']['popup_height']);
                }
                $album['images'][$img_key] = $image;
            }
            
            //Metadata
            $album['album_title'] = (!empty($album['album_data']['album_title'][$this->current_language_id]) ? $album['album_data']['album_title'][$this->current_language_id] : $album['album_name']);
            $album['album_h1_title'] = (!empty($album['album_data']['album_h1_title'][$this->current_language_id]) ? $album['album_data']['album_h1_title'][$this->current_language_id] : $album['album_name']);
            $album['album_keywords'] = (!empty($album['album_data']['album_keywords'][$this->current_language_id]) ? $album['album_data']['album_keywords'][$this->current_language_id] : $album['album_name']);
            $album['album_meta_description'] = (!empty($album['album_data']['album_meta_description'][$this->current_language_id]) ? $album['album_data']['album_meta_description'][$this->current_language_id] : $album['album_name']);
            if (!empty($album['album_data']['album_description']) && $album['album_data']['show_album_description']) {
                $album['album_description'] = html_entity_decode($album['album_data']['album_description'][$this->current_language_id], ENT_QUOTES, 'UTF-8');
            }
            
            switch ($album['album_data']['js_lib_type']) {
                case 0: // ColorBox 
                    if ($this->config->get('config_gallery_include_colorbox')) {
                        $album['scripts'][] = 'catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js';
                        $album['styles'][] ='catalog/view/javascript/jquery/colorbox/colorbox.css';
                    }
                    $album['js_lib_type_text'] = 'colorbox';
                break;
                case 1: // LightBox 
                    if ($this->config->get('config_gallery_include_lightbox')) {
                        $album['scripts'][] = 'catalog/view/javascript/jquery/lightbox/lightbox.min.js';
                        $album['styles'][] ='catalog/view/javascript/jquery/lightbox/lightbox.css';
                    }
                    $album['js_lib_type_text'] = 'lightbox';
                break;
                case 2: // FancyBox
                    if ($this->config->get('config_gallery_include_fancybox')) { 
                        $album['scripts'][] = 'catalog/view/javascript/jquery/fancybox/jquery.fancybox.pack.js';
                        $album['styles'][] ='catalog/view/javascript/jquery/fancybox/jquery.fancybox.css';
                    }
                    $album['js_lib_type_text'] = 'fancybox';
                break;
            }
            
            //lazyload
            if ($this->config->get('config_gallery_include_lazyload')) {
                $album['scripts'][] = 'catalog/view/javascript/jquery/jquery.lazyload.min.js';
            }
            if (!isset($album['album_data']['use_lazyload'])) {
                $album['album_data']['use_lazyload'] = false;
            }
            #$album['album_data']['lazyload_image'] = $this->model_tool_image->resize('lazyload_image.jpg', $album['album_data']['thumb_width'], $album['album_data']['thumb_height']);
            $album['album_data']['lazyload_image'] = '#';

            $this->cacher['album'] = $album;
            if ($this->config->get('config_gallery_modules_cache_enabled')) {
                $this->cache->set($cached_page_name, $this->cacher);
            }
            foreach ($this->cacher as $key => $value) {
                $this->data[$key] = $value;
            }
            
            $album = $this->data['album'];
        }

        /////////////////////////////////////////////////////
        if (!empty($album['scripts'])) {
            foreach ($album['scripts'] as $key => $script) {
                $this->document->addScript($script);
            }
        }
        if (!empty($album['styles'])) {
            foreach ($album['styles'] as $key => $style) {
                $this->document->addStyle($style);
            }
        }

        
        // ***************************************************************************************************** 
        #назначаем заголовок страницы (обязательно)

        $this->document->setTitle($album['album_title']);
        $this->document->addStyle('catalog/view/theme/default/stylesheet/photo_gallery.manager.css');
        
        $this->data['heading_title'] = $album['album_h1_title'];
        $galleres_title = $this->config->get('config_galleries_title');
        if (empty($galleres_title[$this->current_language_id])) {
            $galleres_title[$this->current_language_id] = $this->language->get('text_gallery_list');
        }

        #Добавляем хлебные крошки (обязательно)
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),         
            'separator' => false
        ); 
        $this->data['breadcrumbs'][] = array(
            'text'      => $galleres_title[$this->current_language_id],
            'href'      => $this->url->link('gallery/gallery', '', 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );
        $this->data['breadcrumbs'][] = array(
            'text'      => $album['album_title'],
            'href'      => $this->url->link('gallery/photos', 'album_id='.$album_id. (($page == 1)? '' : '&page='.$page ).$url, 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );

        //Добавляем Last-Modified
        if (isset($album['last_modified']) && $album['last_modified'] != '0000-00-00 00:00:00') {
            $this->response->addHeader('Last-Modified: '.date('r', strtotime($album['last_modified'])));
        }else{
            $this->response->addHeader('Last-Modified: '.date('r'));
        }

        # стандартный код загрузки файла шаблона (обязательно)

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/photos.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/gallery/photos.tpl';
        } else {
            $this->template = 'default/template/gallery/photos.tpl';
        }
 
        # (Не обязательно) Если хотите использовать модули в левой или правой колонке, то:
        $this->document->setDescription((!empty($album['album_data']['album_meta_description'][$this->current_language_id]) ? $album['album_data']['album_meta_description'][$this->current_language_id] : $album['album_name']));
        $this->document->setKeywords((!empty($album['album_data']['album_meta_keywords'][$this->current_language_id]) ? $album['album_data']['album_meta_keywords'][$this->current_language_id] : $album['album_name']));
        
        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->data['microtime'] = microtime(true) - $this->data['microtime'];
        $this->data['no_conflict'] = substr(md5(rand(0, 99)), 20);
        #рендеринг шаблона (Обязательно)
        $this->response->setOutput($this->render());
        // ***************************************************************************************************** 
    }
    private function getAlbumImages($album_id, $page = 1, $limit = 0){
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('catalog/gallery');
        
        $start = ($page - 1) * $limit;
        $limit = $limit + $start;

        $album = $this->model_catalog_gallery->getAlbum($album_id);

        $album_photos = array();
        switch ($album['album_type']) {
            case 0: //Category
                foreach ($album['album_data']['album_categories'] as $category_id) {
                    $products = $this->model_catalog_category->getCategories($category_id);
                    $data = array(
                        'filter_category_id' => $category_id
                    );
                    
                    $products = $this->model_catalog_product->getProducts($data);

                    foreach ($products as $product){
                        if ($product['image']) {
                            $key = md5($product['image']);
                            $album_photos[$key]['image']    = $product['image'];
                            $album_photos[$key]['title']    = $product['name'];
                        }
                        if ($album['album_data']['include_additional_images']) {
                            $images = $this->model_catalog_product->getProductImages($product['product_id']);
                            if (!empty($images)) {
                                foreach ($images as $image) {
                                    $key = md5($image['image']);
                                    $album_photos[$key]['image']    = $image['image'];
                                    $album_photos[$key]['title']    = $product['name'];
                                }
                            }
                        }
                    }
                }
                break;
            case 1: //Directory
                foreach (explode(PHP_EOL, $album['album_data']['album_directory']) as $directory) {
                    if (!empty($directory)) {
                        
                    $data = glob($directory);
                    $data = array_filter($data, 'is_file');
                        if (!empty($data)) {
                            foreach ($data as $value) {
                                $key = md5($value);
                                if ($value{0} == 'i') {
                                    $value = str_replace('image/', '', $value);
                                }
                                $album_photos[$key]['image'] = $value;
                                $album_photos[$key]['title'] = '';
                            }
                        }
                    }
                }
                break;
            case 2: //Custom images
                if (!empty($album['album_data']['gallery_images'])) {
                    foreach ($album['album_data']['gallery_images'] as $gallery_image) {
                        $key = md5($gallery_image['image']);
                        $album_photos[$key]['image'] = $gallery_image['image'];
                        $album_photos[$key]['title'] = $gallery_image['description'][$this->current_language_id];
                    }
                }
            break;
        }
        //Limit photos
        $result = array();
        
        if (($page == 1) && ($limit == 0)) {
            $result = $album_photos;
        }else{
            reset($album_photos);
            for ($counter = 0; $counter < $limit; $counter ++) { 
                if ($counter < $start) {
                    next($album_photos);
                }else{
                    $elem = current($album_photos);
                    if (!empty($elem)) {
                        $result[] = $elem;
                        next($album_photos);
                    }else{
                        break;
                    }
                }
            }
        }
          return $result;
    }
}
?>