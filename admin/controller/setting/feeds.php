<?php
class ControllerSettingFeeds extends Controller
{
    private $feeds = [
        
    ];

    private $sitemaps = [
        'Сайтмапы' => ''
    ];

    public function index()
    {
        $this->data['heading_title'] = 'Фиды данных';
        $this->document->setTitle($this->data['heading_title']);

        $sitemaps = glob(DIR_SITEMAPS_CACHE . '*');
        
        $data['sitemaps'] = [];

        foreach ($sitemaps as $sitemap) {
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
