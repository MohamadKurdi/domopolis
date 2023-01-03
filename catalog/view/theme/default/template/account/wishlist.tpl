<?php echo $header; ?>
<?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>

<style type="text/css">
    .product_single_wrap{
        position: relative;
    }
    .product_single_wrap .remove_btn{
        position: absolute;
        right: 10px;
        top: 10px;
        z-index: 100;
        background: #e16a5d;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        border-radius: 100px;
        font-size: 12px;
    }
    .product-grid.product__grid{
        grid-template-columns: repeat(3,minmax(0,1fr));
        display: grid;
    }
    @media screen and (max-width:560px){
        .wishlist-info{
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .wishlist-info,
        .product__grid{
            justify-content: space-between;
        }
        .cart__item{
            flex-basis: 49%;
            margin-bottom: 10px;
            padding: 10px;
            border: none;
        }
        .cart__item:hover .product__btn-cart button, .cart__item:hover .product__btn-cart a,
        .cart__item .product__btn-cart button, .cart__item .product__btn-cart a{
            font-size: 0;
        }
        .product__rating{
            display: none
        }
        .product__title a {
            font-size: 12px;
            line-height: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart__item .product__delivery{
            font-size: 10px;
            line-height: 1;
        }
        .product__label > div{
            font-size: 10px;
            padding: 4px 6px;
            line-height: 1;
            height: auto
        }
        .cart__item .price__sale {
            right: 10px;
            left: inherit;
            top: 10px;
            font-size: 10px;
            padding: 4px 6px;
            line-height: 1;
        }
        .product__photo{
            margin-bottom: 10px
        }
        .product__price-new {
            font-size: 15px;
            line-height: 1;
            margin: 0
        }
        .product__price-old {
            font-size: 12px;
            line-height: 1;
            margin: 0;
            align-self: start;
        }
        .product__price {
            display: flex;
            flex-wrap: wrap;
            align-self: start;
            flex-direction: column;
            justify-content: start;
            align-items: start;
            text-align: left;
        }
        .cart__item .product__btn-cart .number__product__block{
            display: none
        }
         .cart__item .product__btn-cart button{
            padding-left: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
         }
          .cart__item .product__btn-cart button svg{
            margin: 0 !important;
            width: 15px;
            height: 15px
          }
         .cart__item .product__btn-cart {
            position: absolute;
            margin: 0;
            z-index: 1;
            width: auto;
            right: 10px;
            bottom: 10px;
            height: 30px
        }
        .product_add-favorite {
            top: 10px;
            width: 40px;
            height: 35px;
            bottom: initial;
            left: 10px;
            flex-direction: column;
        }
        .product_add-favorite button{
            margin-left: 0 !important;
            margin-top: 0 !important;
            font-size: 15px !important;
        }
        .product_add-favorite button i{
            font-size: 15px !important;
        }
        .product_add-favorite button svg{
            width: 25px;
            height: 25px
        }
        .cart__item:hover .product__btn-cart{
            width: auto
        }
        .cart__item:hover .product__btn-cart button{
            padding-left: 0
        }
        .cart__item:hover .product__price{
            flex-direction: column;
        }
        .cart__item .product__info{
            padding-bottom: 0 !important
        }
        .cart__item .product__title{
            height: 45px
        }
        .cart__item .product__info{
            margin-bottom: 0 !important
        }
        #content-wishlist .price__btn-group .price__btn-buy {
            position: absolute;
            font-size: 0;
            width: 45px !important;
            padding: 0;
            height: 45px;
            right: 0;
            bottom: 17px;
        }
        #content-wishlist .price__btn-group .price__btn-buy svg{
            margin-right: 0
        }
        .cart__item:last-child{
            border-bottom: 0;
        }
        .cart__item .product__middle {
            margin-bottom: 0;
        }
        .cart__item .product__code,
        .cart__item .product__availability {
            font-size: 13px;
        }
        .cart__item .price__new {
            font-size: 16px;
        }
        .cart__item .price__old{
            font-size: 13px;
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
                                <a href="<? echo $remove_href . $product['product_id']; ?>" class="remove_btn"><i class="fas fa-times"></i></a>
                                <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
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