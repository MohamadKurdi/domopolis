<?php if ($products) { ?>   
    <style type="text/css">
        .new_v_slider .swiper-container{
            padding-bottom: 25px;
            padding-top: 10px;
        }
        .new_v_slider h2.title{
            font-weight: 600;
            font-size: 26px;
            line-height: 32px;
            color: #121415;
            margin-bottom: 32px;
            text-align: left;
            font-family: 'Inter', sans-serif;
        }
        .new_v_slider .reviews__item{
            padding: 0;
            display: flex;
            flex-direction: column;
            background: #FFFFFF;
            border: 1px solid #DDE1E4;
            border-radius: 12px;
            transition: border 0.3s ease,box-shadow 0.5s ease;
            cursor: pointer;
        }
        .new_v_slider .reviews__item:hover{
            box-shadow: 0 8px 20px rgb(0 0 0 / 20%);
            border-color: #c0e856;
        }
        .new_v_slider .reviews__item .info__reviews{
            display: flex;
            gap: 15px;
            padding: 24px 20px;
            border-bottom: 1px solid #DDE1E4;
            flex-wrap: nowrap;
        }
        .new_v_slider .reviews__item .info__reviews .product__photo{
            max-width: 118px;
            min-width: 118px;
        }
        .new_v_slider .reviews__item .info__reviews .product__photo img{
            max-height: 118px;
            width: 100%;
        }
        .new_v_slider .reviews__item .info__reviews .about{
            flex: 1;
        }
        .new_v_slider .reviews__item .info__reviews .about .product__title{
            height: 53px;
            position: relative;
            margin-bottom: 6px;
        }
        .new_v_slider .reviews__item .info__reviews .about .product__title a {
            display: block;
            transition: color 0.2s ease;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 500;
            font-size: 14px;
            line-height: 17px;
            color: #121415;
            word-break: break-word;
        }
        .new_v_slider .reviews__item .info__reviews .about .product__rating{
            display: flex;
            align-items: center;
        }
        .new_v_slider .reviews__item .info__reviews .about .product__rating .count_reviews{
            font-weight: 500;
            font-size: 12px;
            line-height: 15px;
            color: #696F74;
        }
        .new_v_slider  .product__bottom_top .product__info{
            padding-left: 0;
        }
        .new_v_slider .reviews__item .description__reviews_wrap{
            display: flex;
            flex-direction: column;
            padding: 20px 20px 23px;
        }
        .new_v_slider .reviews__item .description__reviews{
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
            height: 44px;
        }
        .new_v_slider .reviews__item .description__reviews .name{
            font-weight: 600;
            font-size: 18px;
            line-height: 22px;
            color: #121415;
            max-width: 207px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .new_v_slider .reviews__item .description__reviews .product__rating{
            margin-bottom: 0;
        }
        .new_v_slider .reviews__item .description__reviews_wrap .reviews{
            margin-bottom: 20px;
        }
        .new_v_slider .reviews__item .description__reviews_wrap .reviews .reviews__desc{
            font-weight: 400;
            font-size: 16px;
            line-height: 151.02%;
            color: #121415;
            display: -webkit-box;
            -webkit-line-clamp: 8;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 193px;
            max-height: 193px;
        }
        .new_v_slider .reviews__item .description__reviews_wrap .date span{
            font-weight: 500;
            font-size: 14px;
            line-height: 17px;
            color: #888F97;
        }


        .new_v_slider .reviews__item .info__reviews .product__price-old_wrap {
          display: flex;
          gap: 11px;
        }
        .new_v_slider .reviews__item .info__reviews .product__price-old_wrap .product__price-old {
          font-weight: 500;
          font-size: 14px;
          line-height: 17px;
          color: #888F97;
        }
        .new_v_slider .reviews__item .info__reviews .product__price-old_wrap .price__sale {
          font-weight: 500;
          font-size: 12px;
          line-height: 15px;
          color: #FFF;
          position: inherit;
          background: #EB3274;
          border-radius: 23px;
          padding: 2px 4px;
        }
        .new_v_slider .reviews__item .info__reviews .product__price-new {
          font-weight: 600;
          font-size: 18px;
          line-height: 22px;
          color: #121415;
        }
        @media screen and (max-width: 560px){
            .new_v_slider .reviews__item .info__reviews{
                padding: 15px;
                gap: 8px;
            }
            .new_v_slider .reviews__item .info__reviews .product__photo {
                max-width: 94px;
                margin: 0;
                display: flex;
                align-items: center;
            }
            .new_v_slider .reviews__item .info__reviews .about .product__title a{
                font-weight: 500;
                font-size: 12px;
                line-height: 15px;
                color: #121415;
            }
            .new_v_slider .reviews__item .info__reviews .product__price-new{
                font-size: 14px;
                line-height: 17px;
            }
            .new_v_slider .reviews__item .description__reviews_wrap{
                padding: 15px;
            }
            .new_v_slider .reviews__item .description__reviews{
                height: auto;
            }
            .new_v_slider .reviews__item .description__reviews .name{
                font-weight: 500;
                font-size: 16px;
                line-height: 19px;
            }
            .new_v_slider .reviews__item .description__reviews_wrap .reviews .reviews__desc{
                font-size: 14px;
                line-height: 151.02%;
            }
            .new_v_slider .reviews__item .description__reviews_wrap .reviews{
                margin-bottom: 20px;
            }
            .new_v_slider .reviews__item .description__reviews_wrap .date span{
                font-size: 12px;
                line-height: 15px;
            }
        }
    </style>
    <section class="reviews inside new_v_slider">
        <div class="wrap">
            <div class="title_wrap">
                <?php if($customtitle) { ?>
                    <h2 class="title center"><?php echo $customtitle; ?></h2>
                <?php } ?>
            </div>
            <div class="reviews-slider-v1">

                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($products as $product) { ?>
                            <!--swiper-slide-->
                                <div class="swiper-slide">
                                    <!--reviews__item-->
                                    <div class="reviews__item" onclick="location.href='<?php echo $product['href']; ?>'">    

                                        <!--info__reviews-->
                                        <div class="info__reviews">
                                           
                                            <div class="product__photo">
                                                <?php if ($product['thumb']) { ?>
                                                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" loading="lazy"></a>
                                                <?php } ?>
                                            </div>
                                            <div class="about">
                                               <!--product__middle-->                                 
                                                <div class="product__title">
                                                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                                </div>
                                                <!--/product__middle-->
                                                <?php if ($this->config->get('config_review_status')) { ?>
                                                    <div class="product__rating">
                                                        <span class="rate rate-<?php echo $product['rating']; ?>"></span>  
                                                        <span class="count_reviews">(<?php echo $product['count_reviews']; ?>)</span>                                          
                                                    </div>
                                                <?php } ?>
                                                <!--product__bottom-->
                                                <div class="product__bottom_top">
                                                    <?php if (empty($product['need_ask_about_stock']) && empty($product['can_not_buy'])) { ?>
                                                        <div class="product__info">
                                                            <div class="product__line">
                                                                <?php if ($product['price']) { ?>
                                                                    <div class="product__price">
                                                                        <?php if (!$product['special']) { ?>
                                                                            <div class="product__price-new"><?php echo $product['price']; ?></div>
                                                                        <?php } else { ?>
                                                                            <div class="product__price-old_wrap">
                                                                                <div class="product__price-old">
                                                                                    <?php echo $product['price']; ?>
                                                                                </div>
                                                                                <?php if ($product['special']) { ?>
                                                                                    <div class="price__sale">-<?php echo $product['saving']; ?>%</div>          
                                                                                <?php } ?>
                                                                            </div>
                                                                            <div class="product__price-new">
                                                                                <?php echo $product['special']; ?>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    

                                                </div>
                                                <!--/product__bottom-->
                                                
                                            </div>                               
                                        </div>
                                        <!--/info__reviews-->
                                        <div class="description__reviews_wrap">
                                            <?php if ($product['reviews2']) { ?>
                                                <?php foreach ($product['reviews2'] as $review) { ?>
                                                    <div class="description__reviews">
                                                        <span class="name"><?php echo $review['author']; ?></span>
                                                       
                                                        <div class="product__rating">
                                                            <?php if ($this->config->get('config_review_status')) { ?>
                                                                <span class="rate rate-<?php echo $review['rating']; ?>"></span>                                            
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <!--reviews-col-->
                                                    <div class="reviews">                                
                                                        <div class="reviews__desc">
                                                            <?php echo utf8_substr(strip_tags(html_entity_decode($review['text'], ENT_QUOTES, 'UTF-8')), 0, 300) . '..' ?> 
                                                        </div>
                                                    </div>
                                                    <div  class="date">
                                                        <span><?php echo $review['date_added']; ?></span>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>    
                                        </div>
                                        
                                        <!--/reviews-col-->
                                    </div>
                                    <!--/reviews__item-->
                                </div>
                        <?php } ?>
                        <!--/swiper-slide-->
                    </div>
                </div>

                <div class="nav_slider">
                   <!-- arrows -->
                   <div class="swiper-prev-slide">
                         <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="swiper-next-slide">
                        <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <!-- /arrows -->   
                </div>
            </div>

        </div> 
    </section>

<script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@type": "CreativeWorkSeries",
    "name": "Отзывы о <?php echo $microdata['name']; ?>",
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?php echo $microdata['rating_value']; ?>",
        "bestRating": "5",
        "ratingCount": "<?php echo $microdata['rating_count']; ?>"
    }
}
</script>
<? } ?>