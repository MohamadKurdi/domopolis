<style>
        .alert.alert-small{font-size:12px!important;}

        #ajaxtable_table tr .price-block .reward_wrap .text b{color:#51a62d!important;}
    </style>    <!--order-cart-->
<div class="simplecheckout-block order-cart" id="simplecheckout_cart" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $has_error ? 'data-error="true"' : '' ?>> 
    <?php if ($this->config->get('config_divide_cart_by_stock')) { ?>
        <?php $reparsedProducts = reparseCartProductsByStock($products); ?>
        <?php if (!empty($reparsedProducts['in_stock'])) { $products = $reparsedProducts['in_stock']; ?>
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>
        <? unset($product); } ?>

        <?php if (!empty($reparsedProducts['not_in_stock'])) { $products = $reparsedProducts['not_in_stock'];  ?>
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>      
        <? unset($product); } ?>

        <?php if (!empty($reparsedProducts['certificates'])) { $products = $reparsedProducts['certificates'];  ?>
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>
        <? unset($product); } ?>
    <?php } else { ?>
        <?php include($this->checkTemplate(dirname(__FILE__),'/../structured/order_cart_items.tpl')); ?>
    <?php } ?>

    <!--order-cart__bottom-->
    <div class="order-cart__bottom">
        <div class="edit">
            <a id="edit_cart_popap"  onclick="openCart();" style="display: none">
             <?php echo $text_retranslate_1; ?> 
            </a>
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
     var VKRetargetFunction = function(){
        if((typeof VK !== 'undefined')){
            var vkproduct = [<?php $i = 0; $total_vk_price = 0; foreach ($products as $product) { ?>                 
                {                   
                    'id': '<?php echo prepareEcommString($product['product_id']); ?>',
                    'price': '<?php echo prepareEcommPrice($product['price']) ?>',
                    'price_from': 0                  
                }<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
                <?php $i++; $total_vk_price+=prepareEcommPrice($product['price']); ?>
                <?php } ?>]; 

                console.log('VK trigger init_checkout');      
                VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'init_checkout', {
                    'products' : vkproduct, 
                    'currency_code': '<?php echo $this->config->get('config_regional_currency'); ?>', 
                    'total_price': '<?php echo prepareEcommPrice($total_vk_price); ?>'
                });  
            }
        }
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


        var contentPopup = $('#simplecheckout_cart');

        function startLoader(){
            contentPopup.css('opacity','0.5');
        }
        
        function endLoader(){
            contentPopup.css('opacity','1');
        }

        function validateCart(input) {
            input.value = input.value.replace(/[^\d,]/g, '');
        };


        function qtValCart(id){
            let quantity = parseInt($(id).parent().children('.qt').val());
            (quantity == 0) ? quantity = minimum: false;

            pid = $(id).parent().children('.product_id').val();
            $.ajax({
                url: '/index.php?route=common/popupcart&update='+pid+'&qty='+quantity,
                type: 'post',
                dataType: 'html',
                beforeSend : function(){
                    startLoader();
                },
                complete : function(){
                    endLoader();
                    reloadAll();
                },
                success:function(data) {

                }
            });
        }

        function plusCart(id){
            let quantity = parseInt($(id).parent().children('.qt').val());
            let minimum = parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
            quantity = quantity + minimum;
            (quantity == 0) ? quantity = minimum: false;

            pid = $(id).parent().children('.product_id').val();
            $.ajax({
                url: '/index.php?route=common/popupcart&update='+pid+'&qty='+quantity,
                type: 'post',
                dataType: 'html',
                beforeSend : function(){
                    startLoader();
                },
                complete : function(){
                    endLoader();
                    reloadAll();
                },
                success:function(data) {
                    pushEcommerceInfo(pid, quantity, 'addToCart');
                    pushVKRetargetingInfo(pid, 'add_to_cart');
                }
            });
        }

        function minusCart(id){
            let quantity = parseInt($(id).parent().children('.qt').val());
            let minimum = parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
            quantity = quantity - minimum;
            (quantity == 0) ? quantity = minimum: false;
            pid = $(id).parent().children('.product_id').val();
            $.ajax({
                url: '/index.php?route=common/popupcart&update='+pid+'&qty='+quantity,
                type: 'post',
                dataType: 'html',
                beforeSend : function(){
                    startLoader();
                },
                complete : function(){
                    endLoader();
                    reloadAll();
                },
                success:function(data) {
                    pushEcommerceInfo(pid, quantity, 'removeFromCart');
                    pushVKRetargetingInfo(pid, 'remove_from_cart');
                }
            });
        }

        function delNewCart(id){
        
            var checkedData = [];
            
                var pid = $(id).parent().children('.product_id').val();
                var quantityTotal = parseInt($(id).parent().parent().parent().children('.quantity').children().children('.qt').val());
                var quantity = 0;
                pushEcommerceInfo(pid, quantityTotal, 'removeFromCart');
                pushVKRetargetingInfo(pid, 'remove_from_cart');
                checkedData.push(pid + '_qt_' + quantity);
                console.log(checkedData);
            
            startLoader();
            setTimeout(function(){
                $.ajax({
                    url: '/index.php?route=common/popupcart&update=explicit',
                    type: 'post',
                    data: {explicit : checkedData},
                    dataType: 'html',
                    beforeSend : function(){
                        startLoader();
                    },
                    complete : function(){
                        endLoader();
                        reloadAll();
                    },
                    success: function(data) {
                        
                    }
                });
            },1000)

        }

        function pushVKRetargetingInfo(pid, event){
                if((typeof VK !== 'undefined')){

                    $.ajax({
                        url: "/index.php?route=product/product/getEcommerceInfo",
                        data: "product_id=" + pid,
                        dataType: "json",
                        error: function(e){
                            console.log(e);
                        },
                        success: function(json) {
                            console.log('VK trigger ' + event);

                            VK.Retargeting.ProductEvent(
                                json.config_vk_pricelist_id, 
                                event, 
                                {
                                    'products' : [{
                                        id: json.product_id,
                                        price_from: 0,
                                        price: json.price
                                    }], 
                                    'currency_code': json.currency, 
                                    'total_price': json.price
                                }
                                );
                        }
                    });
                }
            }

            function pushEcommerceInfo(pid, quantity, event){

                $.ajax({
                    url: "/index.php?route=product/product/getEcommerceInfo",
                    data: "product_id=" + pid,
                    dataType: "json",
                    error: function(e){
                        console.log(e);
                    },
                    success: function(json) {
                        window.dataLayer = window.dataLayer || [];
                        console.log('dataLayer.push ' + event);
                        dataLayer.push({
                            'event': event,
                            'ecommerce': {
                                'currencyCode': json.currency,
                                'add': {
                                    'products': [{
                                        'id':   json.product_id,
                                        'name': json.name,
                                        'price':json.price,
                                        'brand':json.brand,
                                        'category':json.category,
                                        'quantity': quantity
                                    }]
                                }
                            }
                        });
                    }
                });

            }
	
</script>
