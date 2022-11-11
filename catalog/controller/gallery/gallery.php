<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerGalleryGallery extends controller{
    private $cacher = array();
    private $current_language_id;

    public function index(){
        if (isset($this->request->get['album_id'])) {
            $album_id = (int)$this->request->get['album_id'];
            if (!empty($album_id)) {
                $this->redirect($this->url->link('gallery/gallery', 'album_id='.$album_id));
            }else{
                $this->section_galleries();
            }
        }else{
            $this->section_galleries();
        }
    }
    private function section_galleries(){
        $this->data['microtime'] = microtime(true);
        
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('catalog/gallery');
        $this->language->load('product/gallery');
        $this->current_language_id = $this->config->get('config_language_id');
        
        $desc = $this->config->get('config_galleries_description');
        if ($this->config->get('config_gallery_show_description') && !empty($desc[$this->current_language_id])) {
            $this->data['galleries_description'] = html_entity_decode($desc[$this->current_language_id], ENT_QUOTES, 'UTF-8');
        }

        $titl = $this->config->get('config_galleries_title');
        if (!empty($titl[$this->current_language_id])) {
            $this->data['title'] = $titl[$this->current_language_id];
        }else{
            $this->data['title'] = $this->language->get('text_gallery_list');
        }
        $this->document->setTitle($this->data['title']);

        $h1_titl = $this->config->get('config_galleries_h1_title');
        if (!empty($h1_titl[$this->current_language_id])) {
            $this->data['h1_title'] = $h1_titl[$this->current_language_id];
        }else{
            $this->data['h1_title'] = $this->data['title'];
        }

        $kwd = $this->config->get('config_galleries_meta_keywords');
        if (!empty($kwd[$this->current_language_id])) {
            $this->document->setKeywords($kwd[$this->current_language_id]);
        }

        $meta_desc = $this->config->get('config_galleries_meta_description');
        if (!empty($meta_desc[$this->current_language_id])) {
            $this->document->setDescription($meta_desc[$this->current_language_id]);
        }

        $albums = $this->model_catalog_gallery->getAlbums();
        
        foreach ($albums as $key => $album) {
            if ($this->config->get('config_gallery_show_counter')) {
                $cached = $this->cache->get('album_photos.'.md5($album['album_id'].'0'));
                if (!empty($cached)) {
                    $albums[$key]['images'] = $cached;  
                }else{
                    $cached = $this->getAlbumImages($album['album_id'], 1, 0);
                    $this->cache->set('album_photos.'.md5($album['album_id'].'0'), $cached);
                    $albums[$key]['images'] = $cached;
                }
            }
            //counter
            $album_name_postfix = ($this->config->get('config_gallery_show_counter') ? ' ('.count($albums[$key]['images']).')' : '');

            $albums[$key]['album_name'] = $albums[$key]['album_data']['album_name'][$this->current_language_id].$album_name_postfix;
            $albums[$key]['album_link'] = $this->url->link('gallery/photos', 'album_id='.$albums[$key]['album_id']);
            if (!empty($albums[$key]['album_data']['cover_image']['image'])) {
                $albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize($albums[$key]['album_data']['cover_image']['image'], $this->config->get('config_gallery_cover_image_width'), $this->config->get('config_gallery_cover_image_height'));
            }else{
                $albums[$key]['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_gallery_cover_image_width'), $this->config->get('config_gallery_cover_image_height'));
            }
        }
        $this->data['config_gallery_cover_image_width'] = $this->config->get('config_gallery_cover_image_width');
        $this->data['config_gallery_cover_image_height'] = $this->config->get('config_gallery_cover_image_height');

        $this->data['albums'] = $albums;

        // ***************************************************************************************************** 
        #назначаем заголовок страницы (обязательно)

        $this->document->addStyle('catalog/view/theme/default/stylesheet/photo_gallery.manager.css');
        
        $this->data['heading_title'] = $this->data['title'];
        
        #Добавляем хлебные крошки (обязательно)
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),         
            'separator' => false
        ); 
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->data['title'],
            'href'      => $this->url->link('gallery/gallery', '', 'SSL'),          
            'separator' => $this->language->get('text_separator')
        );
        
        # стандартный код загрузки файла шаблона (обязательно)
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/gallery.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/gallery/gallery.tpl';
        } else {
            $this->template = 'default/template/gallery/gallery.tpl';
        }
 
        # (Не обязательно) Если хотите использовать модули в левой или правой колонке, то:
        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->data['microtime'] = microtime(true) - $this->data['microtime'];

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