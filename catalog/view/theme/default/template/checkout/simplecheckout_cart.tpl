<!--order-cart-->
<div class="simplecheckout-block order-cart" id="simplecheckout_cart" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $has_error ? 'data-error="true"' : '' ?>>
    <?php if ($display_header) { ?>
        <h3 class="title"><?php echo $text_cart ?></h3>
        <!-- <span class="checkout-heading-button">
            <a href="javascript:void(0)" data-onclick="clearCart" class="text-danger" data-confirm-text="<?php echo $text_clear_cart_question ?>"><?php echo $text_clear_cart ?></a>
        </span> -->
    <?php } ?>
    
    <?php if ($this->config->get('config_divide_cart_by_stock')) { ?>
        <?php $reparsedProducts = reparseCartProductsByStock($products); ?>
        <?php if (!empty($reparsedProducts['in_stock'])) { $products = $reparsedProducts['in_stock']; ?>
        <h4><?php echo $in_stock_text_h4; ?></h4>        
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>
        <? unset($product); } ?>

        <?php if (!empty($reparsedProducts['not_in_stock'])) { $products = $reparsedProducts['not_in_stock'];  ?>        
        <h4><?php echo $this->language->get('text_not_stock_' . $this->config->get('config_country_id')); ?></h4>        
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>
        <? unset($product); } ?>

        <?php if (!empty($reparsedProducts['certificates'])) { $products = $reparsedProducts['certificates'];  ?>        
        <h4><?php echo $this->language->get('text_present_certificates'); ?></h4>        
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>
        <? unset($product); } ?>
    <?php } else { ?>
       <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>
   <?php } ?>      

   <!--order-cart__bottom-->
   <div class="order-cart__bottom">
    <div class="edit">
        <a id="edit_cart_popap"  onclick="openCart();">
            <svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 11L11 7M11 7L15 11M11 7L11 15M11 1C5.47715 1 1 5.47715 1 11C1 16.5228 5.47715 21 11 21C16.5228 21 21 16.5228 21 11C21 5.47715 16.5228 1 11 1Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg> <?php echo $text_retranslate_1; ?> </a>
        </div>
        <div class="total"><?php echo $text_retranslate_11; ?>: 
            <?php foreach ($totals as $total) { ?>
                <?php if ($total['code'] == 'sub_total') { ?>
                    <span class="total_value"><?php echo $total['text'];?></span>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <!--/order-cart__bottom-->
</div>
<!--/order-cart-->

<script>

<?php if ($this->config->get('config_vk_enable_pixel')) { ?>      
<?php } ?>

function dataLayerPushStep(step, option){ 
    window.dataLayer = window.dataLayer || [];
    console.log('dataLayer.push ' + option);
    dataLayer.push({
        'event': option,
        'ecommerce': {
            'checkout': {
                'actionField': {'step': step, 'option': option},
                'products': [
                    <?php $i = 0; foreach ($products as $product) { ?>					
                        {
                            'name': '<?php echo prepareEcommString($product['name']); ?>', 
                            'id': '<?php echo prepareEcommString($product['product_id']  . GOOGLE_ID_ENDFIX); ?>',
                            'price': '<?php echo prepareEcommPrice($product['price']) ?>',
                            'brand': '<?php echo prepareEcommString($product['manufacturer']); ?>',
                            'category': '<?php echo prepareEcommString($this->model_catalog_product->getGoogleCategoryPath($product['product_id'])); ?>'
                        }<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
                        <?php $i++; ?>
                    <?php } ?>
                    ]
            }
        }				
    }); 
}

<?php if (!empty($products)) { ?>
  <? if ( IS_AJAX_REQUEST ) { ?>
  <?php } else { ?>
    <?php if ($current_step == 1) { ?>    
        dataLayerPushStep(1, 'CheckoutPageOpened');
    <?php } ?>
<?php } ?>
<?php } ?>

</script>
