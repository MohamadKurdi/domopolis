<?php echo $header; ?>
<?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(FILE),'/../structured/breadcrumbs.tpl')); ?>

<style type="text/css">
    
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
                <?php foreach ($products as $product) { ?>
                <!--cart__item-->
                <div class="cart__item" id="wishlist-row<?php echo $product['product_id']; ?>">
                    <div class="delete">
                        <?php if($product['is_set']){ ?>
                            <a class="1" href="<?php echo $product['remove']; ?>"><i class="far fa-times-circle"></i></a>
                        <?php } else { ?>
                            <a class="12" href="<?php echo $product['remove']; ?>"><i class="far fa-times-circle"></i></a>
                        <?php } ?>
                    </div>
                    <!--product__photo-->
                    <div class="product__photo">
                        <?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>">
                            <img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<? echo $product['name']; ?>" loading="lazy">
                       </a>
                        <?php } ?>
                    </div>
                    <!--/product__photo-->
                    <!--product__middle-->
                    <div class="product__middle">
                        <div class="product__rating"><span class="rate rate-5"></span></div>
                        <div class="product__title">
                            <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                        </div>
                        <div class="product__code">Код товара: <?php echo $product['product_id']; ?></div>
                    </div>
                    <!--/product__middle-->
                    <!--product__bottom-->
                    <div class="product__bottom">
                        
                        <div class="product__info" style="margin-bottom: 20px;">
                            <?php  if ($product['need_ask_about_stock']) { ?>        
                                <!-- <div class="product__availability" style="margin-bottom: 10px;">Наличие уточняйте</div> -->
                            <?php  } elseif ($product['can_not_buy']) { ?>
                                <!-- <div class="product__availability" style="margin-bottom: 10px;">Нет в наличии</div> -->
                            <?php } else { ?>  
                                <div class="product__availability" style="margin-bottom: 10px;">В наличии</div>
                            <?php } ?>
                            
                            <div class="product__price">
                              <?php if (!$product['special']) { ?>
                                <div class="price__new"><?php echo $product['price']; ?></div>
                              <?php } else { ?>
                                <div class="price__new"><?php echo $product['special']; ?></div>
                                <div class="price__old"><?php echo $product['price']; ?></div>
                                
                              <?php } ?>
                            </div>
                            <? if ($product['points']) { ?>
                                <div class="reward_wrap">
                                    <span class="text"><?php echo $product['points']; ?></span>
                                </div>
                            <? } ?>
                        </div>
                        <div class="price__btn-group">
                            <?php  if ($product['need_ask_about_stock']) { ?>        
                                <p style="color: #ccc;font-size: 16px;font-weight: 700;">Наличие уточняйте</p>
                                            
                            <?php  } elseif ($product['can_not_buy']) { ?>
                                <span style="color: #ccc;font-size: 16px;font-weight: 700;">Нет в наличии</span>
                            <?php } else { ?>  
                            
                          <button class="price__btn-buy btn" onclick="addToCart('<?php echo $product['product_id']; ?>');">
                            <svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="https://www.w3.org/2000/svg">
                              <path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>В корзину</span>       
                          </button>
                          <?php } ?>
                        </div>
                    </div>
                <!--/product__bottom-->
                </div>
                <!--/cart__item-->
                <?php } ?>
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