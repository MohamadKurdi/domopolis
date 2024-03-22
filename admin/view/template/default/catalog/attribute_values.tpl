<?php echo $header; ?>
<div id="content">
    <div class="box">
        <div class="heading order_head">
            <h1><?php echo $heading_title; ?></h1>
        </div>
        <div class="content">
            <style>
                .list > tbody > tr > td{padding:10px;}
                small{color:#808080;  font-size:9px;}
                small > span.smbtn{padding:2px; background-color:#808080; color:#fff; font-size:9px;}
                span.smbtn > a{color:#fff!important;}

                input.process, textarea.process{
                    border:2px solid #ff7f00;
                    background-color: #ead985;
                }
                input.finished, textarea.finished{
                    border:2px solid #00ad07;
                    background-color: #e9ece6;
                }
            </style>
            <table class="list">
                <thead>
                    <td style="width:400px" class="center">
                        Текущее значение
                    </td>
                    <td style="width:400px" class="center">
                        Заменить на значение
                    </td>
                    <td style="width:50px">
                        Товаров
                    </td>
                    <td class="center">
                       Примеры товаров
                    </td>
                    <td style="width:100px">
                    </td>
                    <td style="width:100px">
                    </td>
                </thead>
            <?php $i=1; foreach ($attribute_values as $attribute_value) { $i++; ?>
                <tr id="attribute-value-tr-<?php echo $i; ?>">
                    <td style="width:400px" class="center">
                        <textarea id="from-text-<?php echo $i; ?>" rows="3" style="width:380px;"><?php echo $attribute_value['text']; ?></textarea>
                    </td>
                    <td style="width:400px" class="center">
                        <textarea id="to-text-<?php echo $i; ?>" rows="3" style="width:380px;"></textarea>
                    </td>
                    <td style="width:50px" class="center">
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><?php echo $attribute_value['product_count']; ?></span>
                    </td>
                    <td class="left">
                        <?php foreach ($attribute_value['products'] as $product) { ?>
                        <div>
                            <small><?php echo $product['name']; ?>
                                <span class="smbtn" style="padding:3px 5px; background:#32bd38; color:#FFF"><?php echo $product['product_id']; ?></span>
                                <?php if ($product['asin']) { ?>
                                <span class="smbtn" style="padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $product['asin']; ?></span>
                                <?php } ?>
                                <span class="smbtn" style="padding:3px 5px; background:#e16a5d; color:#FFF"><a href="<?php echo $product['view']?>" target="_blank"><i class="fa
                                fa-eye"></i></a></span>
                               </span>
                                <span class="smbtn" style="padding:3px 5px; background:#e16a5d; color:#FFF"><a href="<?php echo $product['edit']?>" target="_blank"><i class="fa fa-edit"></i></a></span>
                            </small>
                        </div>
                        <?php } ?>
                    </td>
                    <td style="width:100px" class="center">
                        <a class="button button-replace" data-row-id="<?php echo $i; ?>" onclick="replace_value(<?php echo $i; ?>);"><i class="fa fa-refresh"></i> Заменить</a>
                    </td>
                    <td style="width:100px" class="center">
                        <a class="button buttin-delete" data-row-id="<?php echo $i; ?>" onclick="delete_value(<?php echo $i; ?>);"><i class="fa fa-times"></i> Удалить</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <script>
                function delete_value(row)
                {
                    swal({
                        title: "Эта операция необратима!",
                        text: "Значение атрибута будет удалено у всех товаров сразу",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#F96E64",
                        confirmButtonText: "Да, удалить значение!",
                        cancelButtonText: "Отмена",
                        closeOnConfirm: true
                    }, function () {
                        var value_from_textarea = $('#from-text-' + row);
                        var value_to_textarea = $('#to-text-' + row);

                        $.ajax({
                            url: 'index.php?route=catalog/attribute/value_delete&token=<?php echo $token; ?>',
                            type: 'POST',
                            dataType: 'text',
                            data: {
                                value_from: value_from_textarea.val(),
                            },
                            success: function (text) {
                            },
                            beforeSend: function () {
                                value_from_textarea.removeClass('process finished').addClass('process');
                                value_to_textarea.removeClass('process finished').addClass('process');
                            },
                            complete: function () {
                                $('#attribute-value-tr-' + row).hide('slow');
                            }
                        });
                    });
                }

                function replace_value(row){
                    var value_from_textarea = $('#from-text-' + row);
                    var value_to_textarea = $('#to-text-' + row);

                    $.ajax({
                        url: 'index.php?route=catalog/attribute/value_replace&token=<?php echo $token; ?>',
                        type: 'POST',
                        dataType: 'text',
                        data: {
                            value_from: value_from_textarea.val(),
                            value_to: value_to_textarea.val()
                        },
                        success: function(text) {
                            value_from_textarea.val(value_to_textarea.val());
                            value_to_textarea.val('');
                        },
                        beforeSend: function(){
                            value_from_textarea.removeClass('process finished').addClass('process');
                            value_to_textarea.removeClass('process finished').addClass('process');
                        },
                        complete: function(){
                            value_from_textarea.removeClass('process finished').addClass('finished');
                            value_to_textarea.removeClass('process finished').addClass('finished');
                        }
                    });
                }
            </script>

            <div class="pagination"><?php echo $pagination; ?></div>
        </div>
    </div>
</div>

<?php echo $footer; ?>
