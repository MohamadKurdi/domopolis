<?php echo $header; ?>
<link rel="stylesheet" href="catalog/view/theme/dp/css/sumoselect.css" />
<script src="catalog/view/theme/dp/js/sumoselect.min.js"></script> 
<?php include($this->checkTemplate(dirname(__FILE__),'/structured_manufacturer/head.tpl')); ?>

<style type="text/css">
    #content_collection .swiper-wrapper.content-collection {
        transition: all 1s ease-in-out 0s;

    }
    .swiper-button-next::after, 
    .swiper-button-prev::after{
        color: #1f9f9d;
        font-size: 65px;
        opacity: .6;
    }
    .swiper-button-next:hover::after, 
    .swiper-button-prev:hover::after{
        opacity: 1;
    }
    .swiper-button-next, 
    .swiper-button-prev {
       top: 55%;
    }
    .wrap-collection-alphabet{
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .wrap-collection-alphabet .collections-slider-letter{
        cursor: pointer;
        display: block;
        font-size: 16px;
        padding: 20px 15px;
        transition: all ease .3s;
        width: 12px;
        text-align: center;
        white-space: nowrap;
        user-select: none;
    }
    .wrap-collection-alphabet .collections-slider-letter:hover{
        color: #1f9f9d;
        font-size: 25px;
        padding: 20px 20px 20px 10px;
        font-weight: 500;
    }
    #content_collection .collection-all{
       padding: 20px 0;
    }

    #content_collection .collection-all ul.list-collection{
        display: grid;
        grid-template-columns: repeat(3,minmax(35px,1fr));
        gap: 20px;
    }
    #content_collection .collection-all ul li a {
        width: 100%;
    }
    #content_collection .collection-all ul li{
        padding: 0;
    }
    #content_collection .collection-all span.collections-slider-letter {
        font-size: 0;
        margin: 0;
        position: relative;
        left: 0;
        top: 0;
        transform: translate(0,0);
        color: #f5f5f5;
        z-index: -1;
    }
    @media screen and (max-width: 1000px) {
        #content_collection .collection-all ul.list-collection{

            grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
            gap: 15px;
        }

    }
    @media screen and (max-width: 1000px) {
        #content_collection {
            display: flex;
            flex-direction: column;
        }
    }
</style>
<?php 
$url = $_SERVER['REQUEST_URI'];
$url = explode('?', $url);
$url = $url[0];
 ?>
<div id="content_collection">
    <div class="wrap collection-select">
        <div class="content">
            <h3 class="title"><?php echo $text_know_what_collection; ?></h3>
            <div class="catalog__sort-btn">
                <select id="collection-select" class="form-control" onchange="location = this.value;">
                    <option value="" selected="selected"><?php echo $text_brands_head_collections; ?></option>
                    <?php foreach ($collections as $letterArrayElem) { ?>
                        <?php foreach ($letterArrayElem['collection'] as $collection) { ?>
                            <option value="<?php echo $collection['href']; ?>" ><?php echo $collection['name']; ?></option>
                        <?php }  ?>                                 
                    <?php } ?>
                </select>
            </div>
            <div class="collection-alphabet-mobile">
                <span style="display: block; text-align: center;"><?php echo $text_or; ?></span>
                <div class="catalog__sort-btn">
                <select id="collection-alphabet-select" class="form-control" onchange="location = this.value;">
                    <option value="" selected="selected"><?php echo $text_choose_letter; ?></option>
                    <?php if ($collections) { ?>
                            <?php foreach ($collections as $collection) { ?>
                                <option value="<?php echo $collections_link; ?>#<?php echo $collection['name']; ?>">
                                        <a href="<?php echo $collections_link; ?>#<?php echo $collection['name']; ?>"><?php echo $collection['name']; ?></a>
                                </option>
                            <?php } ?>
                    <?php } ?> 
                </select>
                </div>
            </div>
          </div>
    </div>   
     <div class="collection-conten">  
        
            <div class="wrap nav-coolection">
                <div class="wrap-collection-alphabet">  

                    <?php foreach ($collections as $letterArrayElem) { ?> 
                         <?php 
                            $i = count($letterArrayElem['collection']);
                            $alphabetLetter = str_replace(" ", "", $letterArrayElem['name']);
                         ?>  
                        <a class="collections-slider-letter" href="<?php echo $url; ?>#<?php echo $alphabetLetter;?>"><?php echo $alphabetLetter;?></a>
                    <? } ?>  
                </div>
            </div>
            <div class=" wrap content-collection">    
                
               
                    
                <div class="swiper-slide collection-all">
                        
                        <ul class="list-collection">  
                             <?php foreach ($collections as $letterArrayElem) { ?> 
                                <?php 
                            $i = count($letterArrayElem['collection']);
                            $alphabetLetter = str_replace(" ", "", $letterArrayElem['name']);
                         ?>  
  

                            <?php foreach ($letterArrayElem['collection'] as $i=> $collection) { ?> 
                                   
                                    <li <?php if ($i == 0){ ?> id="<?php echo $alphabetLetter; ?>" <?php } ?>>
                                       
                                         

                                        <a  href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">  
                                            <?php if (!$collection['thumb']) { ?>
                                                <img loading="lazy" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
                                            <?php } ?>
                                            
                                            <?php if ($collection['thumb']) { ?>
                                                <img loading="lazy" width="400" height="300" class="swiper-lazy swiper-lazy-loaded" src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['thumb']; ?>" />
                                            <?php } ?>                                    
                                            <div class="categori-photo">
                                                <span><?php echo $text_brands_head_collection; ?></span>
                                                <p><?php echo $collection['name']; ?></p>
                                            </div>
                                        </a>
                                      
                                    </li>
                            <?php } ?> 
                              <? } ?>  
                        </ul>

 
                    </div>
                    
                  
            </div>
    </div>


</div>

  


    <script>
            $('select').SumoSelect();
            $(document).ready(function() {
                var $page = $('html, body');
                $('option[href*="#"]').click(function() {
                    $page.animate({
                        scrollTop: $($.attr(this, 'href')).offset().top - 60
                    }, 400);
                    return false;
                });
            });



     
    </script>

<?php echo $footer; ?>