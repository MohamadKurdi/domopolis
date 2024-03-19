<?php echo $header; ?>
<?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>

<style type="text/css">
    #content-wishlist .catalog__content.product-grid{
        margin-left: 0;
    }
    #content-wishlist .product__grid {
        background: #FFFFFF;
        border: 1px solid #DDE1E4;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    #content-wishlist .product__grid .product_single_wrap {
        position: relative;
        margin-bottom: 21px;
    }
    #content-wishlist .product__grid .product_single_wrap .product__item {
        display: flex;
        align-items: center;
        flex-direction: row;
        border: 0;
        box-shadow: none;
        border-radius: 0;
        padding: 0;
    }
    #content-wishlist .product__grid .product_single_wrap:not(:first-child){
        border-top: 1px solid #DDE1E4;
        padding: 21px 0 0 0;
    }
    #content-wishlist .product__grid .product_single_wrap .icon{
        background: #FFFFFF;
        border: 1px solid #DDE1E4;
        border-radius: 71px;
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }
    #content-wishlist .product__grid .product_single_wrap .product__photo{
        width: 130px;
        height: 130px;
        background: #FFFFFF;
        border: 1px solid #DDE1E4;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 21px;
        min-width: 130px;
        overflow: hidden;
        margin-bottom: 0;
    }
    #content-wishlist .product__grid .product_single_wrap .product__photo img{
        max-height: 100px;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle{
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle .product__title{
        margin-bottom: 0;
        max-width: 325px;
        height: auto;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle .product__title a{
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
        color: #121415;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle  .product__rating{
        margin-bottom: 0;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle .product__price{
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
        align-self: flex-start;
        align-self: start;    
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle .product__price .product__price-old_wrap {
        display: flex;
        gap: 11px;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle .product__price .product__price-new {
        font-weight: 600;
        font-size: 18px;
        line-height: 22px;
        color: #121415;
    }
     #content-wishlist .product__grid .product_single_wrap .product__item .product__bottom_new {
          margin-top: 0;
          margin-left: auto;
          margin-right: 30px;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle .product__price .product__price-old_wrap .product__price-old {
        font-weight: 500;
        font-size: 14px;
        line-height: 17px;
        color: #888F97;
    }
    #content-wishlist .product__grid .product_single_wrap .product__middle .product__price .product__price-old_wrap .price__sale {
        font-weight: 500;
        font-size: 12px;
        line-height: 15px;
        color: #FFF;
        position: inherit;
        background: #EB3274;
        border-radius: 23px;
        padding: 2px 4px;
    }
     #content-wishlist .product__grid .product_single_wrap .product__item .product__bottom_new .product__btn-cart{
        width: auto;
        height: auto;
     }
    #content-wishlist .product__grid .product_single_wrap .product__item .product__bottom_new .product__btn-cart button{
        background: #FFFFFF;
        border: 1px solid #DDE1E4;
        box-shadow: 0px 8px 33px rgba(53, 56, 64, 0.2);
        border-radius: 36px;
        font-weight: 600;
        font-size: 16px;
        line-height: 19px;
        letter-spacing: -0.005em;
        color: #121415;
        padding: 15px 32px;
        width: auto;
        height: auto;
    }
     #content-wishlist .product__grid .product_single_wrap .remove_btn{
        position: absolute;
        right: 15px;
        top: 15px;
        z-index: 1;
    }
    @media screen  and (max-width:560px){
        #content-wishlist .product__grid .product_single_wrap .icon{
            display: none;
        }
        #content-wishlist .product__grid .product_single_wrap .product__photo {
            width: 100px;
            height: 100px;
            margin-right: 0;
            min-width: 100px;
            grid-column-start: 1;
            grid-column-end: 1;
            grid-row-start: 1;
            grid-row-end: 3;
        }
        #content-wishlist .product__grid .product_single_wrap .product__item {
            display: grid;
            grid-template-columns: 100px 1fr;
            align-items: start;
            grid-template-rows: auto auto;
            gap: 10px;
        }
        #content-wishlist .product__grid .product_single_wrap .product__middle{
            grid-column-start: 2;
            grid-column-end: 2;
            grid-row-start: 1;
            grid-row-end: 1;
                gap: 5px;
        }
        #content-wishlist .product__grid .product_single_wrap .product__item .product__bottom_new{
            grid-column-start: 2;
            grid-column-end: 2;
            grid-row-start: 2;
            grid-row-end: 2;
            margin-right: 0;
            display: flex;
            width: 100%;
        }
        #content-wishlist .product__grid .product_single_wrap .product__middle .product__title a {
            font-weight: 500;
            font-size: 14px;
            line-height: 17px;
            color: #121415;
                max-width: 90%;
        }
        #content-wishlist .product__grid .product_single_wrap .remove_btn {
            position: absolute;
            right: 0;
            top: 0;
            z-index: 1;
        }
        #content-wishlist .product__grid .product_single_wrap:not(:first-child)  .remove_btn {
            top: 15px;
        }
        #content-wishlist .product__grid .product_single_wrap .product__item .product__bottom_new .product__btn-cart button{
            width: 100% !important;
            padding: 0 14px;
            font-size: 14px;
            line-height: 17px;
            height: 37px !important;
        }
    }
</style>
<section id="content-wishlist"  class="account_wrap">
    <?php echo $content_top; ?>
    <div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
        </div>
        <div class="account_content">
            <?php if ($success) { ?>
              <div class="success" style="margin-bottom: 20px;color: green; font-weight: 500;"><?php echo $success; ?></div>
          <?php } ?>
          <?php if ($products) { ?>
            <div class="wishlist-info">
                <div class="catalog__content product-grid">
                    <div class="product-grid product__grid" id="product__grid">
                        <?php foreach ($products as $product) { ?>
                            <div class="product_single_wrap">
                                <a href="<? echo $remove_href . $product['product_id']; ?>" class="remove_btn">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 1L1 11M1 1L11 11" stroke="#DDE1E4" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single_wishlist.tpl')); ?>
                            </div>
                           
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="buttons" hidden>
                <div class="right"><a href="<?php echo $continue; ?>" class="btn btn-acaunt"><?php echo $button_continue; ?></a>
                </div>
            </div>
        <?php } else { ?>
            <div class="content"><?php echo $text_empty; ?></div>
            <div class="buttons" hidden>
                <div class="right"><a href="<?php echo $continue; ?>" class="btn btn-acaunt"><?php echo $button_continue; ?></a>
                </div>
            </div>
        <?php } ?>
        <?php echo $content_bottom; ?>
    </div>
</div>
</section>
<?php echo $footer; ?>