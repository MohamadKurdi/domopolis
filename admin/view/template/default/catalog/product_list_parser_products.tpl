<?php echo $header; ?>
<div id="content">
    <div class="box">
        <div class="content">
            <?php if (isset($update_success) && $update_success): ?>
                <div style="background: green; color: #ffffff; padding: 10px; border-radius: 4px;">Цены успешно обновлены.</div>
                <script type="text/javascript">
                    setTimeout(function () {
                        document.location.href = "<?php print $all_url; ?>";
                    }, 5000);
                </script>
            <?php endif; ?>
            <?php if (isset($product) && $product && !isset($update_success)) { ?>
                <form action="" method="post" enctype="multipart/form-data" id="form">
                    <table class="list">
                        <thead>
                            <tr>
                                <td width="10px"><div id="rotateText">Skip</div></td>
                                <td width="10px">#</td>
                                <td>Product name</td>
                                <td>Price</td>
                                <td>Price in parser</td>
                                <td width="50px">Цена с наценкой</td>
                                <td>Разница</td>
                                <td>Акционная цена</td>
                                <td>Акционная цена в парсере</td>
                                <td width="50px">Акционная цена в парсере с наценкой</td>
                                <td>Разница</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product as $p): ?>
                                <tr <?php if ($p['digital']) print "style='background: yellow;'"; ?>>
                                    <td><input type="checkbox" name="product_skip[<?=$p['id']?>]" class="js-product-skip-checkbox" value="<?=$p['id']?>" <?php if ($p['skip']) print 'checked'; ?> /></td>
                                    <td><a href="<?=$p['product_url_edit'] ?>" target="_blank"><?=$p['id'] ?></a></td>
                                    <td><?=$p['name'] ?></td>
                                    <td><?=$p['price'] ?></td>
                                    <td><input type="text" name="price[<?=$p['id']?>]" value="<?=$p['parser_price'] ?>" class="js-parser-price" data-id="<?php echo $p['id']; ?>"/></td>
                                    <td><?=$p['parser_price_with_guess'] ?></td>
                                    <td><i class="fa fa-<?php if ($p['priceDiffStatus'] == '+') print 'chevron-up'; else print 'chevron-down'; ?>"></i> <?=$p['priceDiff'] ?> </td>
                                    <td><?=$p['special'] ?></td>
                                    <td><input type="text" name="special_price[<?=$p['id'] ?>]" value="<?=$p['special_in_parser'] ?>" class="js-parser-special-price" data-id="<?php echo $p['id']; ?>"/></td>
                                    <td><?=$p['special_in_parser_with_guess'] ?></td>
                                    <td><i class="fa fa-<?php if ($p['priceSpecialDiffStatus'] == '+') print 'chevron-up'; else print 'chevron-down'; ?>"></i> <?=$p['priceSpecialDiff'] ?> </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pagination"><?php echo @$pagination; ?></div>

                    <input type="submit" name="ok" value="ОБНОВИТЬ ВСЕ ТОВАРЫ" style="border: none; background: #398439; padding: 5px; color: #ffffff; margin: 10px 0 0 0;" />
                </form>
            <?php } else { ?>
                <h4>Данных нет.</h4>
            <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('.js-product-skip-checkbox').on('click', function () {
            var skip = 0;
            if ($(this).prop('checked')) {
                skip = 1;
            }
            var val = $(this).val();
            // alert(val + ' - ' + skip);
            $.post('<?php echo $update_url; ?>', {id: val, value: skip, type: 'update_skip'});
        });

        $('.js-parser-price').on('blur', function () {
            var val = $(this).val();
            var id = $(this).data('id');

            $.post('<?php echo $update_url; ?>', {id: id, value: val, type: 'update_price'})
        });
        $('.js-parser-special-price').on('blur', function () {
            var val = $(this).val();
            var id = $(this).data('id');

            $.post('<?php echo $update_url; ?>', {id: id, value: val, type: 'update_special_price'})
        });

    });
</script>

<style type="text/css">
    #rotateText {
        -moz-transform: rotate(270deg);
        -webkit-transform: rotate(270deg);
        -o-transform: rotate(270deg);
    }
</style>



<?php echo $footer; ?>