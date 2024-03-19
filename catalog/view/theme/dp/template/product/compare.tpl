<?php echo $header; ?>

<style type="text/css">
    .compare_wrap{
        display: flex;
        flex-direction: column;
        background: #FFFFFF;
        border: 1px solid #DDE1E4;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
        border-radius: 12px;
        padding: 20px;
    }
    .compare_wrap .head_compare{
        display: grid;
/*        grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); */
        grid-template-columns: repeat(4, 1fr);    
        margin-bottom: 16px;

    }
    .compare_wrap .head_compare .product_item{
        padding: 16px;
        border-bottom: 1px solid #e9e9e9;
        border-right: 1px solid #e9e9e9;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .compare_wrap .head_compare .product_item:last-child{
        border-right: 0;
    }
    .compare_wrap .head_compare .product_item .img{
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
/*        width: 72px;*/
/*        height: 72px;*/
        width: 100%;
        height: 150px;
            display: flex;
    }
    .compare_wrap .head_compare .product_item .img img{
        max-height: 100%;
        max-width: 100%;
    }
    .compare_wrap .head_compare .product_item .product__body{
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
        height: 100%;
        padding-left: 8px;       
         position: relative;
    }
    .compare_wrap .head_compare .product_item .product__body .remove_btn{
        position: absolute;
        color: #e16a5d;
        right: 0;
        top: 0;
    }
    .compare_wrap .head_compare .product_item .product__body .name{
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
        color: #121415;
        margin-right: 20px;
        display: block;
    }
    .compare_wrap .head_compare .product_item .product__body .product__footer{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .compare_wrap .head_compare .product_item .product__body .product__footer .price{
        position: relative;
        padding: 0;
        margin-bottom: 0;
        border: 0;
        display: flex;
        align-items: flex-start;
        flex-direction: column;
    }
    .compare_wrap .head_compare .product_item .product__body .product__footer .price .price-new{
        font-size: 16px;
        font-weight: 500;
        white-space: nowrap;
    }
    .compare_wrap .head_compare .product_item .product__body .product__footer .price .price-old{
        font-size: 11px;
        color: #e16a5d;
        text-decoration: line-through;
        white-space: nowrap;
        padding-top: 1px;
    }
    .compare_wrap .head_compare .product_item .product__body .product__footer .btn.btn-acaunt{
        margin: 0;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #BFEA43;
        border-radius: 13px;
        border: none;
    }
    .compare_wrap .section_compare{
        display: flex;
        flex-direction: column;

    }

    .compare_wrap .section_compare .title_wrap h3{
        font-size: 20px;
        margin: 0;
    }
    .compare_wrap .section_compare .title_wrap button{
        background: none;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        display: flex;
        padding-bottom: 10px;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-bottom: 10px;
        border-bottom: 1px solid #e9e9e9;
        border-radius: 0;
        font-size: 20px;
        font-weight: 500;
    }
    .compare_wrap .section_compare .title_wrap button svg{
        transition: .15s ease-in-out;
    }
    .compare_wrap .section_compare.close .title_wrap button svg{
        transform: rotate(180deg);
        
    }

    .compare_wrap .section_compare{
        padding-bottom: 24px;
        padding-top: 8px;
    }
    .compare_wrap .section_compare.close .compare_hide_wrap{
        height: 0;
        overflow: hidden;
    }
    .compare_wrap .section_compare.close .title_wrap{
        margin-bottom: 0;
    }
    .compare_wrap .section_compare .compare_row{

    }
    .compare_wrap .section_compare .comparison-characteristic__heading{
        position: sticky;
        top: 0;
        padding-top: 8px;
        padding-bottom: 8px;
        margin-bottom: 8px;
        font-size: 18px;
        font-weight: 500;
        color: #121415;
    }
    .compare_wrap .section_compare .comparison-grid{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-row-gap: 16px;
    }
    .compare_wrap .section_compare .compare-td{
        padding-right: 16px;
        font-weight: 500;
        font-size: 14px;
        line-height: 17px;
        color: #404345;
    }
    .compare_wrap .head_compare .product_item .product__body .product__price-new{
        font-weight: 600;
        font-size: 18px;
        line-height: 22px;
        color: #121415;
    }
     .compare_wrap .head_compare .product_item .product__body .product__price-old_wrap {
    display: flex;
    gap: 11px;
}
      .compare_wrap .head_compare .product_item .product__body .product__price-old_wrap .product__price-old {
    font-weight: 500;
    font-size: 14px;
    line-height: 17px;
    color: #888F97;
}
       .compare_wrap .head_compare .product_item .product__body .product__price-old_wrap .price__sale {
    font-weight: 500;
    font-size: 12px;
    line-height: 15px;
    color: #FFF;
    position: inherit;
    background: #EB3274;
    border-radius: 23px;
    padding: 2px 4px;
}


    .compare_wrap .section_compare .name{
        margin-bottom: 8px;
        font-size: 12px;
        color: #797878;
        line-height: 16px;
        font-weight: 300;
    }
    .compare_wrap .section_compare .rate {
        margin-bottom: 5px;
    }
    <?php if (!$logged) { ?> 
       .account_wrap .two_column {
            grid-template-columns: 1fr;
        }   
        .account_wrap .two_column .side_bar {
            border-right: 0;
        }  
    <?php } ?>    
</style>
<?php echo $column_right; ?>
<div id="content-compare" class="account_wrap">
    <?php echo $content_top; ?>
    <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
    <div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
        </div>
        <div class="account_content">
        
            <?php if ($success) { ?>
                <div class="success" style="display: none;"><?php echo $success; ?></div>
            <?php } ?>
            <?php if ($products) { ?>
                <?php $col_products = count($products); ?>
                <div class="compare_wrap">
                    <div class="head_compare prod_<?php echo $col_products; ?>">
                        <?php foreach ($products as $product) { ?>
                            <div class="product_item count_prod_<?php echo $col_products; ?>">
                                <div class="img">
                                    <?php if ($products[$product['product_id']]['thumb']) { ?>
                                        <img src="<?php echo $products[$product['product_id']]['thumb']; ?>" alt="<?php echo $products[$product['product_id']]['name']; ?>" />
                                    <?php } ?>
                                </div>
                                <div class="product__body">
                                    
                                    <a class="remove_btn" href="<?php echo $product['remove']; ?>"><i class="far fa-times-circle"></i></a>                                    
                                    
                                    <a class="name" href="<?php echo $products[$product['product_id']]['href']; ?>"><?php echo $products[$product['product_id']]['name']; ?></a>
                                    <!-- <span class="rate rate-<?php echo $product['rating']; ?>"></span> -->
                                    <div class="product__footer">
                                        <div class="price">

                                            <?php  if (!$product['need_ask_about_stock'] && !$product['can_not_buy']) { ?>  
                                            
                                          
                                                <?php if ($products[$product['product_id']]['price']) { ?>
                                                    <?php if (!$products[$product['product_id']]['special']) { ?>
                                                        <div class="product__price-new"><?php echo $products[$product['product_id']]['price']; ?></div>
                                                    <?php } else { ?>
                                                        <div class="product__price-old_wrap">
                                                            <div class="product__price-old">
                                                               <?php echo $products[$product['product_id']]['price']; ?>
                                                            </div>
                                                            <?php if ($product['special']) { ?>
                                                                <div class="price__sale">-<?php echo $products[$product['product_id']]['saving']; ?>%</div>          
                                                            <?php } ?>
                                                        </div>
                                                        <div class="product__price-new">
                                                           <?php echo $products[$product['product_id']]['special']; ?>
                                                        </div>
                                                       
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>    
                                        </div>
                                        <div class="add_cart_wrap">
                                            <?php  if ($product['need_ask_about_stock']) { ?>       
                                                <p style="color: #e16a5d;font-size: 13px;font-weight: 500;"><?php echo $product['stock_status']; ?></p>
                                            <?php  } elseif ($product['can_not_buy']) { ?>
                                                <p style="color: #e16a5d;font-size: 13px;font-weight: 500;"><?php echo $product['stock_status']; ?></p>
                                            <?php } else { ?>     
                                                <?php if($product['is_set']){ ?>
                                                    <a class="button" href="<?php echo $product['href']; ?>">
                                                        <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="https://www.w3.org/2000/svg">
                                                            <path d="M14.9986 6C14.9986 7.06087 14.5772 8.07828 13.827 8.82843C13.0769 9.57857 12.0595 10 10.9986 10C9.93775 10 8.92033 9.57857 8.17018 8.82843C7.42004 8.07828 6.99861 7.06087 6.99861 6M2.63183 5.40138L1.93183 13.8014C1.78145 15.6059 1.70626 16.5082 2.0113 17.2042C2.2793 17.8157 2.74364 18.3204 3.3308 18.6382C3.99908 19 4.90447 19 6.71525 19H15.282C17.0928 19 17.9981 19 18.6664 18.6382C19.2536 18.3204 19.7179 17.8157 19.9859 17.2042C20.291 16.5082 20.2158 15.6059 20.0654 13.8014L19.3654 5.40138C19.236 3.84875 19.1713 3.07243 18.8275 2.48486C18.5247 1.96744 18.0739 1.5526 17.5331 1.29385C16.919 1 16.14 1 14.582 1L7.41525 1C5.85724 1 5.07823 1 4.46413 1.29384C3.92336 1.5526 3.47251 1.96744 3.16974 2.48486C2.82591 3.07243 2.76122 3.84875 2.63183 5.40138Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                <?php } else { ?>
                                                    <button type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="btn btn-acaunt" >
                                                         <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="https://www.w3.org/2000/svg">
                                                            <path d="M14.9986 6C14.9986 7.06087 14.5772 8.07828 13.827 8.82843C13.0769 9.57857 12.0595 10 10.9986 10C9.93775 10 8.92033 9.57857 8.17018 8.82843C7.42004 8.07828 6.99861 7.06087 6.99861 6M2.63183 5.40138L1.93183 13.8014C1.78145 15.6059 1.70626 16.5082 2.0113 17.2042C2.2793 17.8157 2.74364 18.3204 3.3308 18.6382C3.99908 19 4.90447 19 6.71525 19H15.282C17.0928 19 17.9981 19 18.6664 18.6382C19.2536 18.3204 19.7179 17.8157 19.9859 17.2042C20.291 16.5082 20.2158 15.6059 20.0654 13.8014L19.3654 5.40138C19.236 3.84875 19.1713 3.07243 18.8275 2.48486C18.5247 1.96744 18.0739 1.5526 17.5331 1.29385C16.919 1 16.14 1 14.582 1L7.41525 1C5.85724 1 5.07823 1 4.46413 1.29384C3.92336 1.5526 3.47251 1.96744 3.16974 2.48486C2.82591 3.07243 2.76122 3.84875 2.63183 5.40138Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>     
                                                    </button>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="section_compare">
                        <div class="title_wrap">
                            <h3 class="title" hidden=""><?php echo $text_product; ?></h3>
                            <button>
                                <?php echo $text_product; ?>
                                <svg width="14" height="7" viewBox="0 0 14 7" fill="none" xmlns="https://www.w3.org/2000/svg">
                                    <path d="M1 1L7 6L13 1" stroke="#FFC34F" stroke-width="2" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="compare_hide_wrap">
                            <div class="compare_row">
                                <div class="comparison-characteristic__heading">
                                    <?php echo $text_manufacturer; ?>
                                </div>
                                <div class="comparison-grid">
                                    <?php foreach ($products as $product) { ?>
                                        <div class="compare-td">  
                                            <?php echo $products[$product['product_id']]['manufacturer']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="compare_row">
                                <div class="comparison-characteristic__heading">
                                    <?php echo $text_availability; ?>
                                </div>
                                <div class="comparison-grid">
                                    <?php foreach ($products as $product) { ?>
                                        <div class="compare-td">  
                                            <?php echo $products[$product['product_id']]['stock_status']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($review_status) { ?>
                                <div class="compare_row">
                                    <div class="comparison-characteristic__heading">
                                        <?php echo $text_rating; ?>
                                    </div>
                                    <div class="comparison-grid">
                                        <?php foreach ($products as $product) { ?>
                                            <div class="compare-td">  
                                                <span class="rate rate-<?php echo $product['rating']; ?>"></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="compare_row">
                                <div class="comparison-characteristic__heading">
                                    <?php echo $text_summary; ?>
                                </div>
                                <div class="comparison-grid">
                                    <?php foreach ($products as $product) { ?>
                                        <div class="compare-td">  
                                             <?php echo $products[$product['product_id']]['description']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="compare_row">
                                <div class="comparison-characteristic__heading">
                                    <?php echo $text_dimension; ?>
                                </div>
                                <div class="comparison-grid">
                                    <?php foreach ($products as $product) { ?>
                                        <div class="compare-td">  
                                            <?php echo $products[$product['product_id']]['length']; ?> x <?php echo $products[$product['product_id']]['width']; ?> x <?php echo $products[$product['product_id']]['height']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>     
                        </div>                               
                    </div>
                    <?php foreach ($attribute_groups as $attribute_group) { ?>
                        <div class="section_compare">
                            <div class="title_wrap">
                                <h3 class="title" hidden=""><?php echo $attribute_group['name']; ?></h3>
                                <button>
                                    <?php echo $attribute_group['name']; ?>
                                    <svg width="14" height="7" viewBox="0 0 14 7" fill="none" xmlns="https://www.w3.org/2000/svg">
                                        <path d="M1 1L7 6L13 1" stroke="#FFC34F" stroke-width="2" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="compare_hide_wrap">
                                <?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>    
                                <div class="compare_row">
                                    <div class="comparison-characteristic__heading">
                                        <?php echo $attribute['name']; ?></div>
                                    <div class="comparison-grid">
                                        <?php foreach ($products as $product) { ?>
                                            <?php if (isset($products[$product['product_id']]['attribute'][$key])) { ?>
                                                <div class="compare-td">  
                                                   <?php echo $products[$product['product_id']]['attribute'][$key]; ?>
                                                </div>      
                                            <?php } else { ?>
                                                <div class="compare-td">
                                                    -
                                                </div>    
                                            <?php } ?>
                                            
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="content"><?php echo $text_empty; ?></div>
            <?php } ?>
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.querySelectorAll('.section_compare .title_wrap button').forEach(function(e){
        e.addEventListener('click', function(){
            e.closest('.section_compare').classList.toggle('close')
        })
    })
</script>
<?php echo $footer; ?>