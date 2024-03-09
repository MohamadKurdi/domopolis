     <!--order-cart__item-->   
     <?php foreach ($products as $product) { ?>
        <div class="order-cart__item">
            <div class="product__photo">
                <?php if ($product['thumb']) { ?>
                    <a href="<?php echo $product['href']; ?>" target="_blank"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                <?php } ?>
            </div>
            <div class="product__title">
                <a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a>
                <?php if ($this->config->get('config_divide_cart_by_stock')) { ?>   
                    <?php if ($product['is_certificate']) { ?>
                        <span class="alert alert-success alert-no-padding"><?php echo $this->language->get('text_has_in_stock'); ?></span>
                    <?php } elseif ($product['fully_in_stock']) { ?>
                        <span class="alert alert-success alert-no-padding"><?php echo $this->language->get('text_has_in_stock'); ?> <?php echo $product['amount_in_stock']; ?> шт</span>
                    <?php } elseif ($product['current_in_stock']) { ?>
                        <span class="alert alert-warning alert-no-padding"><?php echo $this->language->get('text_has_in_stock'); ?> <?php echo $product['amount_in_stock']; ?> шт</span>
                    <?php } else { ?>                       
                        <span class="alert alert-danger alert-no-padding"><?php echo $this->language->get('text_has_no_in_stock'); ?>, <?php echo $text_not_in_stock_delivery_term; ?></span>
                    <?php } ?>
                <?php } ?>   
                
            </div>
            <div class="order-cart__item-right">
                <div class="product__amount"><?php echo $product['quantity']; ?> шт</div>
                    <!-- <?php if (!empty($product['old_price'])) { ?>
                        <div class="price-old" style="text-decoration: line-through;">
                        <?php echo $product['old_price']; ?>
                        </div>
                        <?php } ?>    -->  
                        <div class="product__price-new value "><?php echo $product['price']; ?></div>
                        <? if ($product['points']) { ?>
                            <div class="reward_wrap">
                                <span class="text"><?php echo $product['points']; ?></span>
                                <div class="prompt">
                                    <p><?php echo $text_bonus1; ?></p>
                                    <ul>
                                        <li><?php echo $text_bonus2; ?></li>
                                        <li><?php echo $text_bonus3; ?></li>
                                        <li><?php echo $text_bonus4; ?></li>
                                    </ul>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                </div>
            <?php } ?>
        <!--/order-cart__item-->