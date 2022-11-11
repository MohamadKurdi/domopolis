<?php echo $header; ?>
<!--[if IE]>
    <script type="text/javascript" src="view/javascript/jquery/flot2/excanvas.js"></script>
<![endif]--> 
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
            <div class="buttons"><a onclick="$('form').submit();" class="button">Удалить выделенные</a></div>
        </div>
        <div class="content">            
            <table style="width: 100%;">
                <tbody>
                    <tr class="filter f_top">
                        <td class="left">
                            <div>
                                <p>Искать ASIN</p>
                                <input type="text" class="text" name="filter_asin" value="<?php echo $filter_asin; ?>" style="width:90%" />
                            </div>
                         </td>
                         <td class="left">    
                            <div>
                                <p>Искать название</p>
                                <input type="text" class="text" name="filter_name" value="<?php echo $filter_name; ?>" style="width:90%" />
                            </div>
                        </td>
                       
                        <td style="right">
                            <a onclick="filter();" class="button">Фильтр</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="filter_bord"></div>
             <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                    <tr>
                        <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                        <td class="center" style="width:200px;">ASIN</td>
                        <td class="left">Название</td>  
                        <td class="left" style="width:150px">Когда удалён</td>   
                        <td class="left" style="width:200px">Кем удалён</td>                    
                    </tr>
                </thead>
                <tbody>
                    <?php if ($asins) { ?>
                        <?php foreach ($asins as $asin) { ?>
                            <tr>

                                 <td class="text-center">
                                    <input type="checkbox" name="selected[]" value="<?php echo $asin['asin']; ?>" />
                                </td>

                                <td class="center">
                                    <span class="status_color" style="display:inline-block; padding:3px 5px; background:#f91c02; color:#FFF"><?php echo $asin['asin']; ?></span>                                    
                                </td>
                                <td class="left">
                                    <?php echo $asin['name']; ?>
                                 </td>  
                                 <td class="left">
                                    <?php echo $asin['date_added']; ?>
                                 </td>    
                                  <td class="left">
                                    <?php echo $asin['user']; ?>
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
    function filter() {
        url = 'index.php?route=report/product_deletedasin&token=<?php echo $token; ?>';
        
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