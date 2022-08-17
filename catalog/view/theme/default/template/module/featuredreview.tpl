<?php if ($products) { ?>	
<style type="text/css">
    .new_v_slider{

    }
    .new_v_slider .info__reviews{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .new_v_slider .info__reviews .left__info{
        flex-basis: 70%;
        display: flex;
        align-items: center;
    }
    .new_v_slider .info__reviews .right__info{
        flex-basis: 30%;
        padding: 0 15px;
    }
    .new_v_slider .info__reviews .reviews__author-name .name{
        font-weight: 500;
    }
    .new_v_slider .info__reviews .product__photo{
        margin-bottom: 0;
    }    
    .new_v_slider .info__reviews .right__info .product__rating .rate{
        margin: auto;
    }
    .new_v_slider .product-col{
        padding-left: 30px;
        margin-left: 30px;
        padding-right:0; 
        margin-right:0;
        border-right: 0px;
        border-left: 1px solid #eae9e8;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .new_v_slider .product-col .product__title:after{
        display: none;
    }
    .new_v_slider .product-col .product__title{
        height: auto;
        border-bottom: 1px solid #eae9e8;
        padding-bottom: 10px;
    }
    .new_v_slider .product-col .product__title a{
        font-size: 16px;
        line-height: 22px;      
    }
    .new_v_slider .product-col .product__info{
        padding-left: 0;
    }
    .new_v_slider .product-col  .reviews__all-rev{
        text-align: left;
    }
    .new_v_slider .product-col .product__price-old{
        margin-right: 10px;
    }
    @media screen and (max-width: 940px) {
        .new_v_slider .info__reviews .left__info{
            flex-basis: 100%;
        }
        .new_v_slider .info__reviews .right__info {
            flex-basis: 100%;
        }
        .new_v_slider .product-col {
            padding-left: 20px;
            margin-left: 20px;
        }
    }
    @media screen and (max-width: 768px) {
        .new_v_slider .product-col{
            padding-left: 0;
            margin-left: 0;
            border-top: 1px solid #eae9e8;
            border-left: 0;
            padding-top: 15px;
        }
        .new_v_slider .product-col .product__title{
            border-bottom: 0;
            padding-bottom: 0;
        }
        .new_v_slider .product-col .product__bottom{
            display: block;
        }
        .new_v_slider .info__reviews .product__photo{
            /*display: block;*/
            /*margin-bottom: 20px;*/
        }
        .new_v_slider .product-col .reviews__all-rev {
            margin-top: 15px;
        }
    }
</style>
<section class="reviews inside gray-bg new_v_slider">
    <div class="wrap">
        <?php if($customtitle) { ?>
            <h2 class="title center" style="text-transform: uppercase;font-weight: 400;font-size: 28px;"><?php echo $customtitle; ?></h2>
        <?php } ?>
        <div class="reviews-slider-v1">
        
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($products as $product) { ?>
                    <!--swiper-slide-->
                    <div class="swiper-slide">
                        <!--reviews__item-->
                        <div class="reviews__item">                        
                            <!--info__reviews-->
                            <div class="info__reviews">
                                <div class="left__info">
                                    <?php if ($product['reviews2']) { ?>
                                        <div class="reviews__head">
                                            <?php foreach ($product['reviews2'] as $review) { ?>
                                                <div class="reviews__author-name">
                                                    <span class="name"><?php echo $review['author']; ?></span>
                                                    <?php if ($review['purchased'] == 1 ) { ?>
														<span title="Купил(а) в этом магазине" alt="Купил(а) в этом магазине" style="display: inline-block;vertical-align: sub;width: 24px;height: 25px;margin-right: 10px;text-align: center;line-height: 25px;">
															<svg id="Capa_1" enable-background="new 0 0 512 512" height="20" viewBox="0 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><g><path d="m508.996 156.995c-2.833-3.774-7.277-5.995-11.996-5.995h-92.076c-7.301-50.816-51.119-90-103.924-90-52.804 0-96.623 39.184-103.924 90h-78.055l-19.599-138.107c-1.049-7.396-7.38-12.893-14.851-12.893h-69.571c-8.284 0-15 6.716-15 15s6.716 15 15 15h56.55c.025.175 42.112 297.596 42.12 297.646 2.607 17.204 11.015 32.968 23.729 44.638-10.009 8.26-16.399 20.756-16.399 34.716 0 20.723 14.085 38.209 33.181 43.414-2.044 5.137-3.181 10.73-3.181 16.586 0 24.813 20.187 45 45 45s45-20.187 45-45c0-5.258-.915-10.305-2.58-15.01h125.16c-1.665 4.705-2.58 9.751-2.58 15.01 0 24.813 20.187 45 45 45s45-20.187 45-45-20.187-45-45-45h-240c-8.271 0-15-6.729-15-15s6.729-15 15-15h224.7c33.444 0 63.05-22.36 72.024-54.39l48.679-167.422c1.318-4.532.426-9.419-2.407-13.193zm-102.996 295.005c8.271 0 15 6.729 15 15s-6.729 15-15 15-15-6.729-15-15 6.729-15 15-15zm-210 0c8.271 0 15 6.729 15 15s-6.729 15-15 15-15-6.729-15-15 6.729-15 15-15zm105-361c41.355 0 75 33.645 75 75s-33.645 75-75 75-75-33.645-75-75 33.645-75 75-75zm132.851 238.468c-5.345 19.155-23.089 32.532-43.151 32.532-8.541 0-190.327 0-202.801 0-22.39 0-41.119-16.307-44.559-38.781l-20.074-142.219h73.81c7.301 50.816 51.12 90 103.924 90 52.805 0 96.623-39.184 103.924-90h72.094s-43.153 148.416-43.167 148.468zm-158.457-122.862c5.857 5.857 15.355 5.858 21.213 0l45-45c5.858-5.858 5.858-15.355 0-21.213-5.857-5.858-15.355-5.858-21.213 0l-34.394 34.394-4.394-4.393c-5.857-5.858-15.355-5.858-21.213 0s-5.858 15.355 0 21.213z" fill="#51a881"/></g></g></svg>
														</span>
													<?php } ?>
                                                    <span class="date"><?php echo $review['date_added']; ?></span>
                                                </div>
                                                <!-- <div class="reviews__title">Мультиварка достойная. Брать можно смело. Не разочаруетесь</div> -->
                                                <div class="reviews__desc">
                                                    <?php echo  utf8_substr(strip_tags(html_entity_decode($review['text'], ENT_QUOTES, 'UTF-8')), 0, 300) . '..' ?> 
                                                </div>
                                            <?php } ?>
                                        </div>                                    
                                    <?php } else { ?>
                                        <?php echo $no_reviews; ?>
                                    <?php } ?>
                                </div>
                                <div class="right__info">
                                    <!--product__photo-->
                                    <div class="product__photo">
                                        <?php if ($product['thumb']) { ?>
                                            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" loading="lazy"></a>
                                        <?php } ?>
                                    </div>
                                    <!--/product__photo-->
                                    <div class="product__rating">
                                        <?php if ($this->config->get('config_review_status')) { ?>
                                            <span class="rate rate-<?php echo $product['rating']; ?>"></span>                                            
                                        <?php } ?>
                                    </div>
                                </div>                               
                            </div>
                            <!--/info__reviews-->

                            <!--product-col-->
                            <div class="product-col">                                
                                <!--product__middle-->
                                <div class="product__middle">                                    
                                    <div class="product__title">
                                        <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                                    </div>
                                </div>
                                <!--/product__middle-->
                                <!--product__bottom-->
                                <div class="product__bottom">                                  
                                    <div class="product__info">
                                        <div class="product__line">
                                            <?php if ($product['price']) { ?>
                                                <div class="product__price">
                                                    <?php if (!$product['special']) { ?>
                                                        <div class="product__price-new"><?php echo $product['price']; ?></div>
                                                    <?php } else { ?>
                                                        <div class="product__price-old"><?php echo $product['price']; ?></div>
                                                        <div class="product__price-new"><?php echo $product['special']; ?></div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="reviews__all-rev">
                                    <a href="<?php echo $product['href']; ?>/#reviews-col"> <span><?php echo $text_all_reviews; ?></span>
                                        <svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11 15L15 11M15 11L11 7M15 11H7M21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 5.47715 5.47715 1 11 1C16.5228 1 21 5.47715 21 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </div>
                                <!--/product__bottom-->
                            </div>
                            <!--/product-col-->
                        </div>
                        <!--/reviews__item-->
                    </div>
                    <?php } ?>
                    <!--/swiper-slide-->
                </div>
            </div>
            <!-- arrows -->
            <div class="swiper-prev-slide">
                <svg width="60" height="60" viewbox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="1" y="1" width="58" height="58" stroke="#51A881" stroke-width="2"></rect>
                    <path d="M35 42L23 30L35 18" stroke="#51A881" stroke-width="3"></path>
                </svg>
            </div>
            <div class="swiper-next-slide">
                <svg width="60" height="60" viewbox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="-1" y="1" width="58" height="58" transform="matrix(-1 0 0 1 58 0)" stroke="#51A881" stroke-width="2"></rect>
                    <path d="M25 42L37 30L25 18" stroke="#51A881" stroke-width="3"></path>
                </svg>
            </div>
            <!-- /arrows -->
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
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