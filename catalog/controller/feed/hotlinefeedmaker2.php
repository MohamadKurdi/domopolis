<?php
class ControllerFeedHotlineFeedMaker2 extends Controller
{
    private $steps          = [0, 100, 500, 1000, 2000, 5000, 7000, 10000, 15000, 20000, 25000, 1000000000];    
    private $tree_csv       = 'https://hotline.ua/download/hotline/hotline_tree_uk.csv';
    private $maxNameLength  = 150;

    private $stockMode              = false;
    private $exclude_language_id    = null;
    private $language_id            = null;
    private $languages              = [];
    private $urlcode                = '';
    private $eanLog                 = false;



    public function tree(){        
        $csv = file_get_contents($this->tree_csv);

        

    }



























}