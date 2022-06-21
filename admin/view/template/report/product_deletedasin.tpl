<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">        
                <a href="<?php echo $archivedtodeleted; ?>" data-toggle="tooltip" title="Добавить все архивные" class="btn btn-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;Добавить все архивные</a>


                <button type="button" id="insert" class="btn btn-success" data-toggle="tooltip" title="<?php echo $button_add; ?>"><i class="fa fa-plus-circle"></i>&nbsp;Добавить SKU</button>
                <button type="button" id="delete" class="btn btn-danger" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash"></i>&nbsp;Удалить</button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;<?php echo $button_cancel; ?></a>
            </div>
            <h1>Удаленные SKU</h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Список удаленных SKU, которые не загружаются от поставщиков</h3>
            </div>
            
            <div class="panel-body">
                <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-add">
                    <div class="well">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-filter_sku">Поиск по SKU</label>
                                    <input type="text" name="filter_sku" value="<?php echo $filter_sku; ?>" placeholder="SKU" id="input-filter_sku" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-filter_name">Поиск по названию</label>
                                    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Название" id="input-filter_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"></label><br />
                                    <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> Найти</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="add-sku" class="well">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">                                 
                                    <label class="control-label" for="input-sku">Добавить SKU</label>
                                    <input type="text" name="sku" value="" placeholder="sku" id="input-sku" class="form-control" />                                 
                                </div>
                                <div class="form-group">                                 
                                    <label class="control-label" for="input-name">Название</label>
                                    <input type="text" name="name" value="" placeholder="Название" id="input-name" class="form-control" />                                 
                                </div>
                            </div>                        
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"></label><br />
                                    <button type="button" id="button-save" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>&nbsp;
                                    <button type="button" id="button-cancel" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="panel-body">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-url-alias">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-left">SKU</td>
                                    <td class="text-left">Название при удалении</td>   
                                    <td class="text-left">Название текущее</td>
                                    <td class="text-left">Архивный</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($skus) { ?>
                                    <?php foreach ($skus as $sku) { ?>
                                        <tr>
                                            <td class="text-center"><?php if (in_array($sku['sku'], $selected)) { ?>
                                                <input type="checkbox" name="selected[]" value="<?php echo $sku['nfsku']; ?>" checked="checked" />
                                                <?php } else { ?>
                                                <input type="checkbox" name="selected[]" value="<?php echo $sku['nfsku']; ?>" />
                                            <?php } ?>
                                            </td>
                                            
                                            <td class="text-left"><code><?php echo $sku['sku']; ?></code></td> 
                                            <td class="text-left">
                                                <?php if ($sku['name']) { ?> 
                                                    <span class="label label-success"><?php echo $sku['name']; ?></span>
                                                <?php } elseif($sku['pname']) { ?>
                                                     <span class="label label-info"><?php echo $sku['pname']; ?></span>
                                                 <?php } else { ?> 
                                                    <span class="label label-danger">Не существует</span>                                       
                                                <?php } ?>                                                                                         
                                            </td> 
                                            <td class="text-left">
                                                <?php if ($sku['pname']) { ?>
                                                    <span class="label label-success"><?php echo $sku['pname']; ?></span>
                                                <?php } else { ?> 
                                                    <span class="label label-danger">Не существует</span>                                       
                                                <?php } ?>
                                            </td>
                                            <td class="text-left">
                                                <?php if ($sku['parchive']) { ?>
                                                     <span class="label label-danger">Да</span>
                                                 <?php } else { ?> 
                                                    <span class="label label-success">Нет</span>                                       
                                                <?php } ?>
                                                
                                            </td>  
                                        </tr>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $('#add-sku').hide();
    
    $('#button-save').on('click', function(){
        $('#form-add').submit();
    });
    
    $('#button-cancel').on('click', function(){
        $('#add-sku').hide();
        $('input[name="sku"]').val('');
    });
    
    $('#insert').on('click', function(){
        $('#add-sku').show();
        $('input[name="sku"]').val('');
    });
    
    $('#delete').on('click', function() {
        if (!confirm('<?php echo $text_confirm; ?>')) {
            return false;
            } else {
            $('#form-url-alias').submit();
        }
    });
    
    $('#button-filter').on('click', function() {
        url = 'index.php?route=report/product_deletedsku&token=<?php echo $token; ?>';
        
        var filter_sku = $('input[name=\'filter_sku\']').val();
        
        if (filter_sku) {
            url += '&filter_sku=' + encodeURIComponent(filter_sku);
        }
        
        var filter_name = $('input[name=\'filter_name\']').val();
        
        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }
        
        location = url;
    });
    
    $('input[name=\'filter_sku\']').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $('#button-filter').trigger('click');
        }
    });
</script>
<?php echo $footer; ?>