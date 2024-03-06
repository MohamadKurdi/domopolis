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
        <div class="heading order_head" style="height:80px;">
            <h1><?php echo $heading_title; ?></h1>
            <div class="buttons"><a href="<?php echo $deleted_asins; ?>" class="button">Удаленные ASIN</a><a onclick="$('#form').submit();" class="button">Удалить выделенные</a></div>

            <div class="clr"></div>
            <span class="help"><i class="fa fa-info-circle"></i> исключения должны быть на языке оригинала, чтоб пропускать товары при первичном добавлении</span>                
            <span class="help"><i class="fa fa-info-circle"></i> поиск выполняется по вхождению подстроки с пробелами в названии товара. это значит что мы проверяем вхождение '_Miele_', а не 'Miele'</span>
            <div class="clr"></div>
        </div>
        <div class="content">          

            <div style="border:1px dashed grey; padding:10px; margin-bottom:10px;">
                <table style="width: 100%;">
                    <tbody>
                        <tr class="filter f_top" style="width:40%;">
                            <td class="left">
                                <div>
                                    <input type="text" class="text" name="filter_text" placeholder="Искать по тексту" value="<?php echo $filter_text; ?>" style="width:90%" />
                                </div>
                            </td>
                            <td class="left" style="width:40%;">    
                                <div>                         
                                    <input type="text" name="filter_category" value="<?php echo $filter_category; ?>" style="width:90%" placeholder="Искать по категории" />
                                    <input type="hidden" name="filter_category_id" value="<?php echo $filter_category_id; ?>" />                                    
                                </div>                    
                            </td>

                            <td class="right" style="width:20%;">
                                <div>
                                    <a onclick="filter();" class="button">Фильтр</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="clr"></div>
            <div style="border:1px dashed #cf4a61; padding:10px; margin-bottom:10px;">
                <form action="<?php echo $save?>" method="post" enctype="multipart/form-data" id="form-add">
                    <table style="width: 100%;">
                        <tbody>
                            <tr class="filter f_top">
                                <td class="left" style="width:40%;" width="40%">
                                    <div>
                                        <input type="text" class="text" name="text" placeholder="Текст для исключения" value="" style="width:90%" />
                                    </div>
                                </td>
                                <td class="left" style="width:40%;" width="40%">                                    
                                    <div>
                                        <input type="text" name="category" style="width:90%" placeholder="Исключать только в этой категории" />
                                        <input type="hidden" name="category_id" value="<?php echo $filter_category_id; ?>" />                                    
                                    </div>                            
                                </td>

                                <td class="right" style="width:20%;" width="20%">
                                    <div>
                                        <a onclick="$('#form-add').submit();" class="button">Добавить</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>


            <div class="filter_bord"></div>
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="list">
                    <thead>
                        <tr>
                            <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                            <td class="center" style="">Текст</td>
                            <td class="left" style="width:300px;">Категория</td>  
                            <td class="center" style="width:1px;">Использований</td>
                            <td class="center" style="width:150px">Когда добавлен</td>   
                            <td class="right" style="width:200px">Кем добавлен</td>                    
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($texts) { ?>
                            <?php foreach ($texts as $text) { ?>
                                <tr>
                                   <td class="text-center">
                                    <input type="checkbox" name="selected[]" value="<?php echo $text['clear_text']; ?>" />
                                </td>
                                <td class="center">
                                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF"><?php echo $text['text']; ?></span>                                    
                                </td>
                                <td class="left">
                                    <?php echo $text['category']; ?>
                                </td>
                                <td class="center">
                                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $text['times']; ?></span>
                                </td>    
                                <td class="center">
                                    <?php echo $text['date_added']; ?>
                                </td>    
                                <td class="right">
                                    <?php echo $text['user']; ?>
                                </td>                        
                            </tr>                                                      
                        <? } ?>                    
                    <?php } else { ?>
                        <tr>
                            <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>
</div> 
<script type="text/javascript">
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
</script> 
<script type="text/javascript">
    $('input[name=\'filter_category\']').autocomplete({
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
            $('input[name=\'filter_category\']').val(ui.item.label);
            $('input[name=\'filter_category_id\']').val(ui.item.value);

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });
</script> 
<script type="text/javascript">
    function filter() {
        url = 'index.php?route=report/product_excludedasin&token=<?php echo $token; ?>';        
        
        var filter_text = $('input[name=\'filter_text\']').attr('value');
        
        if (filter_text) {
            url += '&filter_text=' + encodeURIComponent(filter_text);
        }

        var filter_category_id = $('input[name=\'filter_category_id\']').attr('value');
        
        if (filter_category_id) {
            url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
        }
        
        location = url;
    }
</script>
<?php echo $footer; ?>  