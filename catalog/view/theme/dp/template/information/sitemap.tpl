<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<style type="text/css">
    #content-sitemap .cat_1 > li > a{
        display: block;
        font-weight: 600;
        font-size: 26px;
        line-height: 32px;
        color: #121415;
        margin-bottom: 16px;
        font-family: 'Unbounded', sans-serif;
    }
    #content-sitemap .cat_1 {
        display: block;
        width: 100%;
    }
    #content-sitemap .cat_1 .cat_2 > li > a{
        font-weight: 600;
        font-size: 16px;
        line-height: 20px;
        color: #696F74;
        font-family: 'Unbounded', sans-serif;
        margin-bottom: 12px;
        display: block;
        width: 241px;
    }
    #content-sitemap .cat_1 .cat_2{
        display: flex;
        flex-wrap: wrap;
        gap: 26px;
        margin-bottom: 24px;
    }
    #content-sitemap .cat_1 .cat_2 > li{
        /*        width: calc(25% - 20px);*/
        /*max-width: 241px;*/
    }
    #content-sitemap .cat_1 .cat_2 .cat_3 li > a{
        background: #E9EBF1;
        border-radius: 12px;
        display: block;
        width: 100%;
        font-weight: 400;
        font-size: 14px;
        line-height: 17px;
        color: #696F74;
        padding: 19px 20px;
        margin-bottom: 8px;
        transition: background-color .3s ease-in-out;
        width: 241px;
    }
    #content-sitemap .cat_1 .cat_2 .cat_3{
        column-width: 241px;
    }
    #content-sitemap .cat_1 .cat_2 .cat_3.column-three{
        columns: 3;
    }
    #content-sitemap .cat_1 .cat_2 .cat_3.column-two{
        columns: 2;
    }
    #content-sitemap .cat_1 .cat_2 .cat_3.column-one{
        columns: 1;
        width: 241px;
    }
    #content-sitemap .cat_1 .cat_2 .cat_3 li > a:hover{
        background: #DADBDF;
    }
    @media screen and (max-width: 560px) {
        #content-sitemap .cat_1 > li > a{
            font-size: 20px;
            line-height: 25px;
            margin-bottom: 12px;
        }
        #content-sitemap .cat_1 .cat_2 > li > a{
            font-size: 14px;
            line-height: 17px;
            margin-bottom: 12px;
        }
        #content-sitemap .cat_1 .cat_2 > li,
        #content-sitemap .cat_1 .cat_2 .cat_3{
            display: flex;
            width: 100%;
            flex-direction: column;
        }
        #content-sitemap .cat_1 .cat_2 .cat_3 li > a{
            width: 100%;
        }
        #content-sitemap .cat_1 .cat_2 .cat_3.column-three,
        #content-sitemap .cat_1 .cat_2 .cat_3.column-two,
        #content-sitemap .cat_1 .cat_2 .cat_3.column-one{
            columns: 1;
            display: flex;
            width: 100%;
            flex-direction: column;
        }
        #content-sitemap .cat_1 .cat_2 > li > a{
            width: 100%;
        }
    }
</style>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<div id="content-sitemap"><?php echo $content_top; ?>
    <div class="wrap">
        <div class="sitemap-info">
            <?php foreach ($categories as $category_1) { ?>
                <ul class="cat_1">        
                    <li>
                        <a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
                        <?php if ($category_1['children']) { ?>
                        <ul class="cat_2">
                            <?php foreach ($category_1['children'] as $category_2) { ?>
                                <li>
                                    <a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
                                    <?php if ($category_2['children']) { ?>
                                        <ul  class="cat_3 <?php if (count($category_2['children']) >= 30) { ?> column-three<?php } elseif (count($category_2['children']) >= 15) { ?> column-two <?php } else { ?> column-one<?php } ?>">
                                            <?php foreach ($category_2['children'] as $category_3) { ?>
                                                <li>
                                                    <a href="<?php echo $category_3['href']; ?>">
                                                        <?php echo $category_3['name']; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </li>
                </ul>
            <?php } ?>
            <ul class="cat_1">
                <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
                <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
                    <ul class="cat_2">
                        <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
                        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
                        <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
                        <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
                        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a></li>
                <li><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li>
                <li><a href="<?php echo $search; ?>"><?php echo $text_search; ?></a></li>
            </ul>
            <ul  class="cat_1">
                <li>
                <span class="text"><?php echo $text_information; ?></span>
                    <ul class="cat_2">
                        <?php foreach ($informations as $information) { ?>
                            <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                        <?php } ?>
                        <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>