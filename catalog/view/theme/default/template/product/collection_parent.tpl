<?php
	$this->language->load('module/mattimeotheme');
	$button_quick = $this->language->get('entry_quickview');
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>



    <style type="text/css">
        .collection-alphabet{
            bottom: inherit !important;
        }
        .wrap-collection-alphabet{
            background-color: #fff;
            border-radius: 4px;
            min-height: 62px;
            margin-top: 15px;
            box-shadow: 0 2px 20px 0 #e2e1e1;
        }

        .collection-alphabet.swiper-pagination-bullets{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin: 0;
            padding: 0 20px;
            height: auto;
            position: relative;
        }
        .collection-alphabet .swiper-pagination-bullet{
            background: transparent !important;
            margin: 0 !important;
            width: 35px;
        }

        .collection-alphabet .swiper-pagination-bullet:hover,
        .collection-alphabet .swiper-pagination-bullet-active{
            background: transparent !important;
        }

        .collection-alphabet.swiper-pagination-bullets .swiper-pagination-bullet-active.box .text{
            color: #51a881;
            font-size: 25px;
            padding: 20px 20px 20px 10px;
            font-weight: 500;
        }
        .collection-alphabet.swiper-pagination-bullets .box .text{
            cursor: pointer;
            display: block;
            font-size: 16px;
            padding: 20px 15px;
            transition: all ease .3s;
            width: 12px;
            text-align: center;
            white-space: nowrap;
        }
        .collection-all{
            position: relative;
            width:auto;
            height:auto;
            padding: 50px 75px 0 75px;
        }
        .collection-all ul.list-collection{
            display: -ms-flexbox;
            display: flex;
            padding: 20px 0;
            box-sizing: content-box;
            
        }
        .collection-all ul.list-collection.bottom-row{
           margin-left: 110px;     
        }
        .collection-all ul li{
            width: auto;
            margin: 0;
            padding: 0 20px;
            max-height: 400px;
        }
        .collection-all ul li a{
            display: block;
            text-decoration: none;
            width: 400px;
        }
        .collection-all ul li a .categori-photo{
            width: 66%;
            padding: 12px 25px;
            margin-top: -50px;
            position: relative;
            background: #fff;
        }
        .collection-all ul li a .categori-photo span{
            line-height: 20px;
            margin-bottom: 4px;
            font-size: 12px;
            color: #7f7f7f;
        }
        .collection-all ul li a .categori-photo p{
            font-size: 19px;
            margin-bottom: 0;
            line-height: 30px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .collection-all ul li a img{
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }
        
        .collection-all span.collections-slider-letter{
            font-size: 350px;
            margin: 0;
            position: absolute;
            left: -37px;
            top: 58%;
            transform: translate(0,-50%);
            color: #f5f5f5;
            z-index: -1;
        }
        


        .collection-select .content{
            margin: 70px 0 60px;
            text-align: center;
        }
        .collection-select .title{
            
        }
        .collection-select .title::before {
            display: inline-block;
            position: absolute;
            left: 50%;
            top: -25px;
            content: " ";
            border-top: 1px solid #000;
            width: 69px;
            transform: translateX(-50%);
        }
        .collection-select .catalog__sort-btn #collection-select{
            
        }
        .collection-select  .SumoSelect > .CaptionCont {
            width: 250px;
            text-align: right;
        }
        .collection-conten .swiper-container{
            padding:0 40px;
        }
        @media screen and (max-width: 1400px){
            .collection-all ul li a img{
                max-height: 240px;
            }
            .collection-all {
                padding: 15px 75px 0 75px;
            }
            .collection-all ul.list-collection {
                padding: 5px 0;
            }
           
        }
        @media screen and (min-width: 768px) {
            .collection-alphabet-mobile{
                display: none;
            }
        }
        @media screen and (max-width: 768px){
            #content_collection{
                display: flex;
                flex-direction: column-reverse;
            }
            .content-collection{
                flex-direction: column;
            }
            .collection-all{
                padding: 0;
                display: flex;
                flex-direction: column;
            }
            .collection-all ul.list-collection{
                flex-direction: column;
                align-items: center;
            }
            .collection-all ul.list-collection.bottom-row{
                margin-left: 0;
            }
            .collection-all span.collections-slider-letter{
                position: relative;
                top: 0;
                left: 0;
                font-size: 65px;
                transform: translate(0,0%);
                display: block;
                width: 100%;
                height: 70px;
                color: #bdbdbd;
                line-height: 70px;
                margin-bottom: 3px;
            }
            .collection-all ul li,
            .collection-all ul li a{
                width: 100%;
                padding: 0;
            }
            .nav-coolection{
                padding: 0;
            }
            .collection-conten .swiper-container{
                padding: 0 20px;            
            }
            .collection-all ul li a img{
                max-height: 350px;
            }
            .collection-select .content {
                margin: 10px 0 15px;
            }
            .collection-select .title::before{
                display: none;
            }
            .nav-coolection{
                display: none;
            }
        }
        
    </style>

<div id="content_collection">
    <div class="collection-conten">  
        <div class="swiper-container">

            <?php
                $count_collectio = count($children); 
            ?> 
                 

            <div class="wrap nav-coolection">
                <div class="wrap-collection-alphabet"<?php if ($count_collectio  == 1) { ?>style="opacity: 0;min-height: 0;"<?php } ?>>  
                    <div class="swiper-pagination collection-alphabet"></div>
                </div>
            </div>
            <div class="swiper-wrapper content-collection">    
                
                <?php foreach ($children as $letterArrayElem) { ?> 
                    
                <div class="swiper-slide collection-all">
                        <?php 
                            $i = count($letterArrayElem['collection']);
                            $alphabetLetter = str_replace(" ", "", $letterArrayElem['name']);
                         ?>  

                        <ul class="list-collection top-row">  

                            <span class="collections-slider-letter" id="<?php echo $alphabetLetter;?>"><?php echo $alphabetLetter;?></span>  

                            <?php foreach ($letterArrayElem['collection'] as $i=> $collection) { ?> 
                                 <?php if (($i + 1) % 2 != 0)  {  ?> 
                                    <li>
                                        <a href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">  
                                            <?php if (!$collection['thumb']) { ?>
                                                <img loading="lazy" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
                                            <?php } ?>
                                            
                                            <?php if ($collection['thumb']) { ?>
                                                <img loading="lazy" src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['name']; ?>" />
                                            <?php } ?>                                    
                                            <div class="categori-photo">
                                                <span><?php echo $text_brands_head_collection; ?></span>
                                                <p><?php echo $collection['name']; ?></p>
                                            </div>
                                        </a> 
                                    </li>    
                                 <?php } ?>                           
                            <?php } ?>  

                        </ul>

                        <?php if ($i >= 1) { ?>

                            <ul class="list-collection bottom-row"> 
                                <?php foreach ($letterArrayElem['collection']  as $i=>  $collection) { ?> 
                                       <?php if (($i + 1) % 2 == 0)  {  ?>                                               
                                        <li>
                                            <a href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">  
                                                <?php if (!$collection['thumb']) { ?>
                                                    <img loading="lazy" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
                                                <?php } ?>
                                                
                                                <?php if ($collection['thumb']) { ?>
                                                    <img class="swiper-lazy swiper-lazy-loaded" loading="lazy" src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['thumb']; ?>" />
                                                <?php } ?>
                                                
                                                <div class="categori-photo">
                                                    <span><?php echo $text_brands_head_collection; ?></span>
                                                    <p><?php echo $collection['name']; ?></p>
                                                </div>
                                            </a> 
                                        </li>    
                                     <?php } ?>                             
                                <?php } ?>    
                            </ul>

                        <?php } ?> 
                    </div>
                    
                <? } ?>     
            </div>
        </div>
    </div>

 
    <div class="wrap collection-select">
        <div class="content">
            <h3 class="title"><?php echo $text_know_what_collection; ?></h3>
            <div class="catalog__sort-btn">
                <select id="collection-select" class="form-control" onchange="location = this.value;">
                    <option value="" selected="selected"><?php echo $text_brands_head_collections; ?></option>
                    <?php foreach ($children as $letterArrayElem) { ?>
                        <?php foreach ($letterArrayElem['collection'] as $collection) { ?>
                            <option value="<?php echo $collection['href']; ?>" ><?php echo $collection['name']; ?></option>
                        <?php }  ?>                                 
                    <?php } ?>
                </select>
            </div>
            <div class="collection-alphabet-mobile"<?php if ($count_collectio  == 1) { ?>style="display:none;"<?php } ?> >
                <span style="display: block; text-align: center;"><?php echo $text_or; ?></span>
                <div class="catalog__sort-btn">
                <select id="collection-alphabet-select" class="form-control" onchange="location = this.value;">
                    <option value="" selected="selected"><?php echo $text_choose_letter; ?></option>
                    <?php if ($children) { ?>
                            <?php foreach ($children as $collection) { ?>
                                <option value="<?php echo $collection['anchor']; ?>">
                                        <a href="<?php echo $collection['anchor']; ?>"><?php echo $collection['name']; ?></a>
                                </option>
                            <?php } ?>
                    <?php } ?> 
                </select>
                </div>
            </div>
          </div>
    </div>   
</div>





    <script>
            $(document).ready(function() {
                var $page = $('html, body');
                $('option[href*="#"]').click(function() {
                    $page.animate({
                        scrollTop: $($.attr(this, 'href')).offset().top - 60
                    }, 400);
                    return false;
                });
            });



        var menu = [
            <?php if ($children) {                
                 foreach ($children as $collection) {
                  ?> '<?php echo (str_replace(" ", "", $collection['name'])); ?>', <?php
                    
                 } 
            } ?>
        ];  


        if(document.documentElement.clientWidth > 768) {  
            window.onload = function() {
              var mySwiper = new Swiper('.swiper-container', {
                loop: true,
                slidesPerView: "auto",
                freeMode: true,
                lazy: true,
                centeredSlides: false,
                spaceBetween: 0,
                grabCursor: true,
                pagination: {
                  el: '.swiper-pagination',
                  clickable: true,
                  renderBullet: function(index, className) {
                    return '\
                      <div class="box ' + className + '">\
                      <div class="text">' + (menu[index]) + '</div>\
                      </div>';
                  },
                }
              })
            };
        }; 
    </script>


<?php echo $footer; ?>

