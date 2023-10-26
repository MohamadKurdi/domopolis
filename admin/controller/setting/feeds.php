<?php
class ControllerSettingFeeds extends Controller
{
    private $feeds = [
        'Google Merchant Наличие'           => 'merchant_stock_feed',
        'Google Merchant Supplemental'      => 'supplemental_feed',
        'Ремаркетинг Фид (Facebook, Google' => 'remarketing_full_feed',

        'Хотлайн Фид'                       => 'hotline_full',
        'Yandex Полный Фид'                 => 'yandex_fast_full_feed',
        'Yandex Директ Наличие'             => 'yandex_direct_stock_feed',
        'Yandex Маркет'                     => 'yandex_market_feed',
        'OZON Фид'                          => 'ozon_feed',
        'VKontakte Фид'                     => 'vk_feed',

        'Priceva Фид'                       => 'priceva'
    ];

    private $sitemaps = [
        'Сайтмапы' => ''
    ];

    public function index()
    {
        $this->data['heading_title'] = 'Фиды данных';
        $this->document->setTitle($this->data['heading_title']);

        $sitemaps = glob(DIR_SITEMAPS_CACHE . '*');
        
        $this->data['sitemaps'] = [];

        foreach ($sitemaps as $sitemap) {
            $this->data['sitemaps'][] = [
                'name'  => pathinfo($sitemap, PATHINFO_BASENAME),
                'time'  => date('Y-m-d H:i:s', filemtime($sitemap)),
                'error' => (time() - filemtime($sitemap) > 60 * 60 * 24 * 2),
                'size'  => convertSize(filesize($sitemap)),
                'href'  => (HTTP_CATALOG . DIR_SITEMAPS . pathinfo($sitemap, PATHINFO_BASENAME))
            ];
        }


        $this->data['feeds'] = [];
        foreach ($this->feeds as $group => $prefix) {
            if (empty($this->data[$group])) {
                $this->data[$group] = [];
            }

            $feeds = glob(DIR_REFEEDS . $prefix . '*');

            foreach ($feeds as $feed) {
                $this->data['feeds'][$group][] = [
                'name'  => pathinfo($feed, PATHINFO_BASENAME),
                'time'  => date('Y-m-d H:i:s', filemtime($feed)),
                'error' => (time() - filemtime($feed) > 60 * 60 * 24 * 2),
                'size'  => convertSize(filesize($feed)),
                'href'  => (HTTP_CATALOG . REFEEDS_DIR . pathinfo($feed, PATHINFO_BASENAME))
                ];
            }
        }


        $this->data['token'] = $this->session->data['token'];

        $this->template = 'setting/feeds.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        
        $this->response->setOutput($this->render());
    }
}
