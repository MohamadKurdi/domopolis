<?php if ($products) { ?>
  
  <?php
    $this->language->load('module/category');
    $button_compare = $this->language->get('button_compare');
    $button_wishlist = $this->language->get('button_wishlist');
    $this->language->load('module/mattimeotheme');
    $button_quick = $this->language->get('entry_quickview');
  ?>
  <section class="reviews inside gray-bg">
      <div class="wrap">
    <?php if($customtitle) { ?>
            <h2 class="title center" style="text-transform: uppercase;font-weight: 400;font-size: 28px;"><?php echo $customtitle; ?></h2>
    <?php } ?>
    
    <div class="reviews-slider-v1 two-slide">
<div class="swiper-container">
        <div class="swiper-wrapper">
          <!--swiper-slide-->
          <?php foreach ($products as $product) { ?>
          <div class="swiper-slide">
            <!--reviews__item-->
            <div class="reviews__item">
              <!--info__reviews-->
              <div class="info__reviews">
                <div class="product__title v-catalog">
                  <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                </div>
                <?php if ($product['reviews2']) { ?>
                <div class="reviews__head">
                  <?php foreach ($product['reviews2'] as $review) { ?>
                  <div class="reviews__author-name">
                    <?php if ($this->config->get('config_review_status')) { ?>
                      <span class="rate rate-<?php echo $product['rating']; ?>"></span>
                    <?php } ?> 
                    <span class="name"><?php echo $review['author']; ?></span>
                    <span class="date"><?php echo $review['date_added']; ?></span>
                  </div>
                  <div class="reviews__desc">
                    <?php echo utf8_substr(strip_tags(html_entity_decode($review['text'], ENT_QUOTES, 'UTF-8')), 0, 300) . '..' ?>
                  </div>
                  <?php } ?>
                </div>
                <?php } else { ?>
                    <?php echo $no_reviews; ?>
                <?php } ?>
              </div>
              <!--/info__reviews-->
            </div>
            <!--/reviews__item-->
          </div>
          <?php } ?>
          <!--/swiper-slide-->
        </div>
      </div>
      <!-- arrows -->
      <div class="swiper-prev-slide v2">
        <svg width="25" height="44" viewbox="0 0 25 44" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M23 42L3 22L23 2" stroke="#51A881" stroke-width="3"></path>
        </svg>
      </div>
      <div class="swiper-next-slide v2">
        <svg width="25" height="44" viewbox="0 0 25 44" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M2 42L22 22L2 2" stroke="#51A881" stroke-width="3"></path>
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
