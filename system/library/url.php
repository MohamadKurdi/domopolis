<?php
class Url {
    private $url;
    private $registry   = null;
    private $config     = null;
    private $cache      = null;
    private $db         = null;

    private $rewrite = array();

    public function __construct($url, $registry = false) {
        if ($registry && is_object($registry)){
            $this->registry = $registry;
            $this->config   = $registry->get('config');
            $this->cache    = $registry->get('cache');
        } else {
            global $config;
            $this->config = $config;
        }

        if (is_bool($url)) {
            $this->url = '';          
        } else {
            $this->url = $url;
        }
    }

    public function addRewrite($rewrite) {
        $this->rewrite[] = $rewrite;
    }

    public function link($route, $args = '', $connection = 'SSL', $language_id = false) {
        return $this->linkCached($route, $args, $language_id);
    }

    public function checkIfGenerate($param){        
        if (!$this->config->get('config_seo_url_do_generate')){
            if (is_object($this->registry) && $this->registry->has('short_uri_queries') && !empty($this->registry->get('short_uri_queries')[$param])){
                return false;
            }
        }

        return true;        
    }

    public function linkfromid($param, $id){
        $keywords = [];

        if ($this->config->get('config_seo_url_from_id') && is_object($this->registry) && $this->registry->has('short_uri_queries') && !empty($this->registry->get('short_uri_queries')[$param])){
            foreach ($this->registry->get('languages') as $language){
                $keywords[$language['language_id']] = $this->registry->get('short_uri_queries')[$param] . $id;
            }
        }

        return $keywords;
    }

    private function rewriteSimpleCheckout($route){
        $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');

        if (!empty($this->config) && method_exists($this->config, 'get') && $this->config->get('simple_settings')) {
            if ($this->config->get('simple_replace_cart') && $route == 'checkout/cart' && $get_route != 'checkout/cart') {                
                $route = 'checkout/simplecheckout';

                if ($this->config->get('simple_popup_checkout')) {
                    $args .= '&popup=1';
                }
            }

            if ($this->config->get('simple_replace_checkout')) {
                foreach (array('checkout/checkout', 'checkout/unicheckout', 'checkout/uni_checkout', 'checkout/oct_fastorder', 'checkout/buy', 'revolution/revcheckout', 'checkout/pixelshopcheckout') as $page) {
                    if ($route == $page && $get_route != $page) {
                        $route = 'checkout/simplecheckout';

                        if ($this->config->get('simple_popup_checkout')) {
                            $args .= '&popup=1';
                        }

                        break;
                    }
                }
            }

            if ($this->config->get('simple_replace_register') && $route == 'account/register' && $get_route != 'account/register') {
                $route = 'account/simpleregister';

                if ($this->config->get('simple_popup_register')) {
                    $args .= '&popup=1';
                }
            }

            if ($this->config->get('simple_replace_edit') && $route == 'account/edit' && $get_route != 'account/edit') {
                $route = 'account/simpleedit';
            }

            if ($this->config->get('simple_replace_address') && $route == 'account/address/update' && $get_route != 'account/address/update') {
                $route = 'account/simpleaddress/update';
            }

            if ($this->config->get('simple_replace_address') && $route == 'account/address/insert' && $get_route != 'account/address/insert') {
                $route = 'account/simpleaddress/insert';
            }

            if ($this->config->get('simple_replace_address') && $route == 'account/address/edit' && $get_route != 'account/address/edit') {
                $route = 'account/simpleaddress/update';
            }

            if ($this->config->get('simple_replace_address') && $route == 'account/address/add' && $get_route != 'account/address/add') {
                $route = 'account/simpleaddress/insert';
            }
        }

        return $route;
    }

    private function linkUnCached($route, $args, $language_id = false){
        $route = $this->rewriteSimpleCheckout($route);

        if (empty($this->url)) {
            $url = 'https://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/index.php?route=' . $route;
        } else {
            $url = ($this->url . 'index.php?route=' . $route);
        }

        if ($args) {
            if (is_array($args)) {
                $url .= '&amp;' . http_build_query($args);
            } else {
                $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
            }
        }

        foreach ($this->rewrite as $rewrite) {  
            if ($language_id && method_exists($rewrite, 'rewriteLanguage')){            
                $url = $rewrite->rewriteLanguage($url, $language_id);
            } else {
                $url = $rewrite->rewrite($url);
            }
        }

        if (defined('IS_ADMIN') && IS_ADMIN){
            $url = str_replace('&amp;', '&', $url);
        }

        return $url;
    }

    private function linkCached($route, $args, $language_id = false) {                
        $route = $this->rewriteSimpleCheckout($route);

        if (!empty($this->cache)){
            return $this->linkUnCached($route, $args, $language_id = false);
        }

        if (!$url = $this->cache->get($this->registry->createCacheQueryStringData(__METHOD__, [$route], [$args, $language_id]))){
            $url = $this->linkUnCached($route, $args, $language_id = false);

            $this->cache->set($this->registry->createCacheQueryStringData(__METHOD__, [$route], [$args, $language_id]), $url);
        }

        return $url;
    }
}