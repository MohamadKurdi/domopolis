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
    div.black{
        background-color:#2e3438;
    }
</style>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading order_head">
            <h1><?php echo $heading_title; ?></h1>
            <div id="rnf-status" style="float: left; line-height: 26px; padding-top: 5px; margin-left:20px;" class="delayed-load short-delayed-load" data-route='setting/rnf/getRainForestStats' data-reload="5000"></div>      

            <div class="clr"></div>
            <span class="help"><i class="fa fa-info-circle"></i> Для работы очереди добавления должны быть включены обслуживающие крон-задачи</span>        
        </div>
        <div class="content">            
            <table style="width: 100%;">
                <tbody>
                    <tr class="filter f_top">
                        <td class="left">
                            <div>
                                <input type="text" class="text" name="filter_asin" placeholder="Искать по ASIN" value="<?php echo $filter_asin; ?>" style="width:90%" />
                            </div>
                        </td>
                        <td class="left">    
                            <div>
                                <input type="text" class="text" name="filter_name" placeholder="Искать по названию" value="<?php echo $filter_name; ?>" style="width:90%" />
                            </div>
                        </td>

                        <td class="right" style="width:150px;">    
                            <div>
                                <a href="<?php echo $filter_problems_href; ?>" class="button" style="color:#CF4A61; border-color:#CF4A61;"><i class="fa fa-exclamation-triangle"></i> Проблемные (<?php echo $filter_problems_count; ?>)</a>
                            </div>
                        </td>

                        <td class="right" style="width:90px;">
                            <div>                                
                                <a onclick="filter();" class="button">Фильтр</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="filter_bord" style="margin-bottom:15px;"></div>

            <form action="<?php echo $add; ?>" method="post" enctype="multipart/form-data" id="form-add">
                <table style="width: 100%;">
                    <tbody>
                        <tr class="filter f_top">
                            <td class="left" style="width:50%">
                                <div>
                                    <input type="text" class="text" name="asin" placeholder="Добавить ASIN" style="width:90%" />
                                    <span class="help">можно несколько через запятую</span>
                                </div>
                            </td>
                            <td class="left" style="width:40%">
                                <div>
                                    <input type="text" name="category" style="width:90%" placeholder="Добавить в категорию" />
                                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
                                    <span class="help">будет добавлен в эту категорию, если не задать, попробуем определить сами</span>
                                </div>
                            </td>
                            <td style="right" style="width:10%">
                                <div>                                
                                    <a onclick="$('#form-add').submit();" class="button">Добавить</a>
                                </div>
                            </td>
                            <td style="right" style="width:10%">
                                <div>                                
                                    <a onclick="$('#form').submit();" class="button">Удалить</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </form>

            <div class="filter_bord"></div>

            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="list">
                    <thead>
                        <tr>
                            <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                            <td class="center" style="width:1px;"></td>
                            <td class="center" style="width:100px;">ASIN</td>
                            <td class="left" style="width:50px;" >Картинка</td>
                            <td class="left" style="width:70px;" >Код товара</td>
                            <td class="left" style="width:1px;" >Статус</td>
                            <td class="left">Название</td>
                            <td class="left">Категория</td>
                            <td class="left" style="width:150px">Добавлен</td>
                            <td class="left" style="width:150px">Создан</td>       
                            <td class="left" style="width:100px">Кем добавлен</td>   
                            <td class="left" style="width:100px">Действие</td>                 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products) { ?>
                            <?php foreach ($products as $product) { ?>
                                <tr>

                                    <td class="text-center">
                                        <input type="checkbox" name="selected[]" value="<?php echo $product['asin']; ?>" />
                                    </td>

                                    <td class="center">
                                       <?php if ($product['product_id']) { ?>
                                            <span style="color:#00ad07; font-size:18px; font-weight: 700;"><i class="fa fa-check-circle"></i></span>
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-refresh"></i></span>
                                       <? } ?>
                                    </td>

                                    <td class="center">
                                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF"><?php echo $product['asin']; ?></span>                    
                                    </td>

                                    <td class="center">
                                       <?php if ($product['image']) { ?>
                                            <img src="<?php echo $product['image']; ?>" />
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-refresh"></i></span>
                                       <? } ?>
                                    </td>

                                    <td class="center">
                                       <?php if ($product['product_id']) { ?>
                                            <span style="color:#00ad07; font-weight: 700;"><? echo $product['product_id']; ?></span>
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-refresh"></i></span>
                                       <? } ?>
                                    </td>

                                     <td class="center">
                                       <?php if ($product['status']) { ?>
                                            <span style="color:#00ad07; font-size:18px; font-weight: 700;"><i class="fa fa-check-circle"></i></span>
                                       <? } else { ?>
                                            <span style="color:#CF4A61; font-size:18px; font-weight: 700;"><i class="fa fa-times"></i></span>
                                       <? } ?>
                                    </td>

                                    <td class="left">
                                        <?php if ($product['product_id']) { ?>
                                            <small><? echo $product['name']; ?></small>
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-refresh"></i></span>
                                       <? } ?>
                                    </td> 

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
                                        <small><?php echo $product['date_added']; ?></small>
                                    </td>  

                                    <td class="center">
                                          <?php if ($product['date_created']) { ?>
                                            <small><? echo $product['date_created']; ?></small>
                                       <? } else { ?>
                                            <span style="color:#FF9243; font-size:18px; font-weight: 700;"><i class="fa fa-refresh"></i></span>
                                       <? } ?>
                                    </td>

                                    <td class="center">
                                        <small><?php echo $product['user']; ?></small>
                                    </td>   

                                    <td class="center">
                                        <a class="button" href="<?php echo $product['edit']; ?>" target="_blank"><i class="fa fa-edit"></i></a>
                                        <a class="button" href="<?php echo $product['view']; ?>"  target="_blank"><i class="fa fa-eye"></i></a>
                                    </td>                     
                                </tr>                                                      
                            <? } ?>                    
                        <?php } else { ?>
                            <tr>
                                <td class="center" colspan="2"><?php echo $text_no_results; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>  

<script type="text/javascript"><!--
        $('input[name=\'category\']').autocomplete({
            delay: 500,
            source: function(request, response) {       
                $.ajax({
                    url: 'index.php?route=catalog/category/autocomplete_final&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                    dataType: 'json',
                    success: function(json) {
                        json.unshift({
                            'category_id':  0,
                            'name':  '<?php echo $text_none; ?>'
                        });
                        
                        response($.map(json, function(item) {
                            return {
                                label: item.name,
                                value: item.category_id
                            }
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $('input[name=\'category\']').val(ui.item.label);
                $('input[name=\'category_id\']').val(ui.item.value);
                
                return false;
            },
            focus: function(event, ui) {
                return false;
            }
        });
    //--></script> 

<script type="text/javascript"><!--
function filter() {
    url = 'index.php?route=catalog/addasin&token=<?php echo $token; ?>';

    var filter_asin = $('input[name=\'filter_asin\']').attr('value');

    if (filter_asin) {
        url += '&filter_asin=' + encodeURIComponent(filter_asin);
    }

    var filter_name = $('input[name=\'filter_name\']').attr('value');

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    location = url;
}
</script>
<?php echo $footer; ?>