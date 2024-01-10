<?php echo $header; ?>
<style>
    div.red{
        background-color:#ef5e67;
    }
    div.orange{
        background-color:#ff7f00;
    }
    div.green{
        background-color:#00ad07;
    }
    .black{
        background-color:#2e3438;
    }
     .text-red{
        color:#ef5e67;
    }
    .text-orange{
        color:#ff7f00;
    }
    .text-green{
        color:#00ad07;
    }

    a.button.active { color: #fff !important;background-color: #6A6A6A;text-decoration: none !important; }

    span.smallbutton{
        padding:3px 5px;
        display:inline-block;
        margin-right:10px;
        color:white;
        cursor:pointer;
    }
</style>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div class="box">
        <div class="heading order_head">
            <h1><?php echo $heading_title; ?></h1>
            <div id="rnf-status" style="float: right; line-height: 26px; padding-top: 5px; margin-left:20px;" class="delayed-load short-delayed-load" data-route='setting/rnf/getRainForestStats' data-reload="5000"></div>          
        </div>
        <div style="border:1px dashed grey; padding:10px; margin-bottom:10px;">    
            <table style="width: 100%;">
                <tbody>
                    <tr class="filter f_top">
                        <td class="left" style="width:20%">
                            <div>
                                <p>Дата от</p>
                                <input type="date" class="text" name="filter_date_from" placeholder="0000-00-00" value="<?php echo $filter_date_from; ?>" style="width:80%" />
                            </div>
                        </td>
                        <td class="left" style="width:20%">    
                            <div>
                                <p>Дата от</p>
                                <input type="date" class="text" name="filter_date_to" placeholder="0000-00-00" value="<?php echo $filter_date_to; ?>" style="width:80%" />
                            </div>
                        </td>

                        <td class="left" style="width:20%">    
                            <div>
                                 <p>Кто добавлял</p>
                                <select name="filter_user_id">
                                    <option value="*">Все контент-менеджеры</option>
                                    <? foreach ($users as $user) { ?>
                                        <option value="<?php echo $user['user_id']; ?>" <?php if ($user['user_id'] == $filter_user_id) { ?>selected="selected"<? } ?>><?php echo $user['user']; ?></option>
                                    <? } ?>   
                                </select>
                            </div>
                        </td>

                        <td class="left" style="width:20%; padding-top:15px;">
                            <div>                                
                                <a onclick="filter();" class="button">Фильтр</a>
                            </div>
                        </td>

                        <td class="left">
                            <span style="color:#CF4A61; font-size:14px; font-weight: 700;">
                                <i class="fa fa-info-circle"></i> Минимальная цена: <?php echo $this->config->get('config_rainforest_skip_low_price_products'); ?> €
                            </span><br />
                            <span style="color:#00ad07; font-size:14px; font-weight: 700;">
                                 <i class="fa fa-check-circle"></i> Товаров с офферами: <?php echo $total_product_have_offers; ?>
                            </span><br />
                            <span style="color:#FF7815; font-size:14px; font-weight: 700;">
                                 <i class="fa fa-question-circle"></i> Товаров без офферов: <?php echo $total_product_have_no_offers; ?>
                            </span>
                            <br />
                            <span style="color:#7F00FF; font-size:14px; font-weight: 700;">
                                 <i class="fa fa-question-circle"></i> Всего ASIN прошло через очередь: <?php echo $total_product_were_in_queue; ?>
                            </span>
                            <br />
                            <span style="color:#7F00FF; font-size:14px; font-weight: 700;">
                                 <i class="fa fa-exclamation-circle"></i> Актуальных товаров из очереди: <?php echo $total_product_good_in_queue; ?>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
           </div>

           <div style="border:1px dashed grey; padding:10px; margin-bottom:10px;">
                <? foreach ($periods as $period) { ?>
                    <a class="button <? if ($filter_date_from == $period['filter_date_from'] && $filter_date_to == $period['filter_date_to']) { ?>active<? } ?>" href="<? echo $period['href']; ?>" style="margin-right:10px; margin-bottom:5px;"><? echo $period['period']; ?> <span style="border-radius:5px; padding:3px; color:#fff; background-color:#FF7815" class="delayed-load short-delayed-load" data-route='<?php echo $period['total']; ?>'></span></a>
                <? } ?>
           </div>

            <div class="pagination"><?php echo $pagination; ?></div>
            <div class="filter_bord"></div>
                <table class="list">
                    <thead>
                        <tr>
                            <td class="center" style="width:40px;">Статус</td>
                            <td class="center" style="width:80px;" >Картинка</td>
                            <td class="center">Название <img src="<?php echo DIR_FLAGS_NAME; ?>/<?php echo $this->config->get('config_rainforest_source_language'); ?>.png" /></td>
                            <td class="center">Название <img src="<?php echo DIR_FLAGS_NAME; ?>/<?php echo $this->config->get('config_language'); ?>.png" />"</td>
                            <td class="center" style="width:60px;">Закупка</td>

                            <?php if ($this->config->get('config_amazon_profitability_in_stocks')) { ?>
                                <td class="center" style="width:60px;">Себестоимость</td>
                                <td class="center" style="width:100px;">Продажа</td>
                                <td class="center" style="width:100px;">Чистыми</td>
                                <td class="center" style="width:60px;">Рентабельность</td>
                            <?php } ?>

                            <td class="center" style="width:200px">Категория</td>
                            <td class="center" style="width:100px;">ASIN</td>                                                        
                            <td class="center" style="width:70px;">Код товара</td>                          
                            <td class="center" style="width:100px">Добавлен</td>     
                            <td class="center" style="width:100px">Кем добавлен</td>   
                            <td class="center" style="width:100px">Действие</td>                 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products) { ?>
                            <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td class="center">
                                       <?php if ($product['status']) { ?>
                                            <span style="color:#00ad07; font-size:18px; font-weight: 700;"><i class="fa fa-check-circle"></i></span>
                                       <? } else { ?>
                                            <span style="color:#CF4A61; font-size:18px; font-weight: 700;"><i class="fa fa-times"></i></span>
                                       <? } ?>
                                    </td>

                                    <td class="left">
                                       <?php if ($product['image']) { ?>
                                            <img src="<?php echo $product['image']; ?>" />                                     
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td>

                                    <td class="left">
                                       <?php if ($product['name']) { ?>
                                            <?php echo $product['name']; ?>                                 
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td>

                                     <td class="center">
                                       <?php if ($product['rnf_name']) { ?>
                                            <?php echo $product['rnf_name']; ?>                                 
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td>         

                                    <td class="center">
                                       <?php if ($product['amazon_best_price']) { ?>
                                            <span style="color:#7F00FF; font-weight: 700;"><? echo $product['amazon_best_price_eur']; ?></span>  
                                            <br />                                      
                                            <small style="color:#7F00FF;"><? echo $product['amazon_best_price_national']; ?></span>
                                       <?php } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td>  

                                <?php if ($this->config->get('config_amazon_profitability_in_stocks')) { ?> 
                                    <td class="center">
                                       <?php if ($product['costprice']) { ?>
                                            <span style="color:#FF7815; font-weight: 700;"><? echo $product['costprice_eur']; ?></span>  
                                            <br />                                      
                                            <small style="color:#FF7815;"><? echo $product['costprice_national']; ?></span>                                      
                                       <?php } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td>  

                                    <td class="center">
                                       <?php if ($product['price']) { ?>
                                            <span style="color:#CF4A61; font-weight: 700;"><? echo $product['price_eur']; ?></span>    
                                            <br />                                      
                                            <small style="color:#CF4A61;"><? echo $product['price_national']; ?></span>                                       
                                       <?php } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td> 

                                    <td class="center">
                                       <?php if ($product['abs_profitability']) { ?>
                                            <span style="color:#00ad07; font-weight: 700;"><? echo $product['abs_profitability_eur']; ?></span>    
                                            <br />                                      
                                            <small style="color:#00ad07;"><? echo $product['abs_profitability_national']; ?></span>  
                                       <?php } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td> 

                                    <td class="center">
                                       <?php if ($product['profitability']) { ?>
                                            <span style="display:inline-block;padding:2px 3px; font-size:14px; background:#000; color:#fff; white-space:nowrap;"><?php echo $product['profitability']; ?> %</span>                                       
                                       <?php } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-question-circle"></i></span>
                                       <? } ?>
                                    </td>                  
                                 <?php } ?>                                           

                                    <td class="center">
                                        <?php if ($product['category']) { ?>
                                            <?php if ($product['category_id'] == $this->config->get('config_rainforest_default_technical_category_id') || $product['category_id'] == $this->config->get('config_rainforest_default_unknown_category_id')) { ?>
                                                <span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF"><? echo $product['category']['name']; ?></span>
                                            <?php } else { ?>
                                               <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><? echo $product['category']['name']; ?></span>
                                           <?php } ?>
                                       <? } else { ?>
                                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9243; color:#FFF">АВТО</span>
                                        <? } ?>
                                    </td> 

                                    <td class="center">
                                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF"><?php echo $product['asin']; ?></span>                                                          
                                    </td>

                                    <td class="center">
                                       <?php if ($product['product_id'] > 0) { ?>
                                            <span style="color:#00ad07; font-weight: 700;"><? echo $product['product_id']; ?></span>
                                       <?php } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-refresh"></i></span>
                                       <? } ?>
                                    </td>                                

                                    <td class="center">
                                        <small><?php echo $product['date_added']; ?></small><br />
                                        <small><?php echo $product['time_added']; ?></small>
                                    </td>  

                                    <td class="center">
                                        <small><?php echo $product['user']; ?></small>
                                    </td>   

                                    <td class="center">
                                        <a class="button" href="<?php echo $product['view']; ?>"  target="_blank"><i class="fa fa-eye"></i></a>
                                    </td>                     
                                </tr>                                                      
                            <? } ?>                    
                        <?php } else { ?>
                            <tr>
                                <td class="center" colspan="11">🥹 Нет данных за выбранный период</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>  


<script type="text/javascript">
function filter() {
    url = 'index.php?route=catalog/addasin/report&token=<?php echo $token; ?>';

    var filter_date_from = $('input[name=\'filter_date_from\']').attr('value');

    if (filter_date_from) {
        url += '&filter_date_from=' + encodeURIComponent(filter_date_from);
    }

    var filter_date_to = $('input[name=\'filter_date_to\']').attr('value');

    if (filter_date_to) {
        url += '&filter_date_to=' + encodeURIComponent(filter_date_to);
    }

    var filter_user_id = $('select[name=\'filter_user_id\']').children("option:selected").val();
        
        if (filter_user_id != '*') {
            url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
        }

    location = url;
}
</script>
<?php echo $footer; ?>