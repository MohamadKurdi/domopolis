        <div class="wrap">
            <section class="category-list-children collection-list">                
                <div class="categories-photo__row">
                    <?php $i=1; foreach ($collections as $collection) { ?>
                        <div class="categories-photo__item">
                            <div class="collection-block-container">
                                <div class="collection-image">
                                <a href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">  
                                    <?php if (!$collection['thumb']) { ?>
                                        <img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
                                    <?php } ?>
                                    
                                    <?php if ($collection['thumb']) { ?>
                                        <img src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['thumb']; ?>" />
                                    <?php } ?>
                                    
                                    <div class="categories-photo__label">
                                        <!-- <? if (isset($collection['short_description'])) { ?>
                                            <div class="collection-hover-text">
                                                <? echo $collection['short_description']; ?>
                                            </div>
                                        <? } ?> -->
                                        <?php echo $collection['name']; ?>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    <? } ?>
                </div>
                <script>
                    $(document).ready(function() {
                        $('.categories-photo__row').simpleLoadMore({
                            count: 12,
                            item:'.categories-photo__item',
                            btnHTML:'<button id="load-more-collection_btn" class="pages__show-more btn-border" style="grid-column-start: 2;grid-column-end: 2;">Показать еще колекции</button>'
                        });
                    });     
                </script>
            </section>      
        </div>
