<?php echo $header; ?>

<style type="text/css">
    .compare_wrap{
        display: flex;
        flex-direction: column;
    }
    .compare_wrap .head_compare{
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(250px,1fr));    
        margin-bottom: 16px;

    }
    .compare_wrap .head_compare .product_item{
        padding: 16px;
        border-bottom: 1px solid #e9e9e9;
        border-right: 1px solid #e9e9e9;
        display: flex;
        flex-direction: row;
    }
    .compare_wrap .head_compare .product_item .img{
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 72px;
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
        font-size: 14px;
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
        border-radius: 5px;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
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
        font-size: 17px;
    }
    .compare_wrap .section_compare .comparison-grid{
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
        grid-row-gap: 16px;
    }
    .compare_wrap .section_compare .compare-td{
        padding-right: 16px;
        font-size: 14px;
        font-weight: 500;
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
    
</style>
<?php echo $column_right; ?>
<div id="content-compare" class="account_wrap">
    <?php echo $content_top; ?>
    <?php include(dirname(__FILE__).'/../structured/breadcrumbs.tpl'); ?>
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
                                                        <span class="price-new"><?php echo $products[$product['product_id']]['price']; ?></span>
                                                    <?php } else { ?>
                                                        <span class="price-old"><?php echo $products[$product['product_id']]['price']; ?></span> 
                                                        <span class="price-new"><?php echo $products[$product['product_id']]['special']; ?></span>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>    
                                        </div>
                                        <div class="add_cart_wrap">
                                            <?php  if ($product['need_ask_about_stock']) { ?>       
                                                <p style="color: #e16a5d;font-size: 13px;font-weight: 500;">Наличие уточняйте</p>
                                            <?php  } elseif ($product['can_not_buy']) { ?>
                                                <p style="color: #e16a5d;font-size: 13px;font-weight: 500;">Нет в наличии</p>
                                            <?php } else { ?>     
                                                <?php if($product['is_set']){ ?>
                                                    <a class="button" href="<?php echo $product['href']; ?>">
                                                        <svg width="15" height="15" viewBox="0 0 26 25" fill="none" xmlns="https://www.w3.org/2000/svg">
                                                            <path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                <?php } else { ?>
                                                    <button type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="btn btn-acaunt" >
                                                        <svg width="15" height="15" viewBox="0 0 26 25" fill="none" xmlns="https://www.w3.org/2000/svg">
                                                            <path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
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
                                <div class="comparison-characteristic__heading"><?php echo $text_model; ?></div>
                                <div class="comparison-grid">
                                    <?php foreach ($products as $product) { ?>
                                        <div class="compare-td">  
                                            <div class="name"><?php echo $products[$product['product_id']]['name']; ?></div> 
                                            <?php echo $products[$product['product_id']]['model']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="compare_row">
                                <div class="comparison-characteristic__heading">
                                    <?php echo $text_manufacturer; ?>
                                </div>
                                <div class="comparison-grid">
                                    <?php foreach ($products as $product) { ?>
                                        <div class="compare-td">  
                                            <div class="name"><?php echo $products[$product['product_id']]['name']; ?></div> 
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
                                            <div class="name">
                                                <?php echo $products[$product['product_id']]['name']; ?>
                                            </div> 
                                            <?php echo $products[$product['product_id']]['availability']; ?>
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
                                                <div class="name">
                                                    <?php echo $products[$product['product_id']]['name']; ?>
                                                </div> 
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
                                            <div class="name">
                                                <?php echo $products[$product['product_id']]['name']; ?>
                                            </div> 
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
                                            <div class="name">
                                                <?php echo $products[$product['product_id']]['name']; ?>
                                            </div> 
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
                                                    <div class="name"><?php echo $products[$product['product_id']]['name']; ?></div> 
                                                   <?php echo $products[$product['product_id']]['attribute'][$key]; ?>
                                                </div>      
                                            <?php } else { ?>
                                                <div class="compare-td">
                                                    <div class="name"><?php echo $products[$product['product_id']]['name']; ?></div>
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