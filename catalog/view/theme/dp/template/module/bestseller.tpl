<!-- Вас так же может заинтересовать-->
<?php if ($products) { ?>
    <style type="text/css">
    .bestseller_slider .nav-group{
      position: absolute;
      right: 0px;
      top: 0;
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .bestseller_slider .nav-group > div{
      cursor: pointer;
    }
    .bestseller_slider{
      margin-bottom: 40px;
      position: relative;
    }
    .bestseller_slider .head_slider{
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 36px;
    }
    .bestseller_slider .head_slider .title{
      font-weight: 600;
      font-size: 26px;
      line-height: 32px;
      color: #121415;
    }
    .bestseller_slider .head_slider .nav-group{
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .bestseller_slider .head_slider .nav-group > div{
      cursor: pointer;
      display: flex;
    }

    .bestseller_slider .swiper-container {
      padding: 0 0 45px;
    }

  </style>

  <div class="section_slider bestseller_slider" id="bestseller-carousel-section">
    <div class="head_slider">
      <span class="title"><?php echo $heading_title; ?></span>  
    </div>
      <div class="default_slider_section">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <?php foreach ($products as $product) { ?>
              <div class="swiper-slide">
                <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
              </div>
            <? } unset($product); ?>
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
      <!-- Add Pagination -->
      <div class="swiper-pagination"></div> 
  </div>

<?php } ?>
