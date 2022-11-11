<? if ($order_product['real_product']['source']) { ?>
	<? foreach (explode(PHP_EOL, $order_product['real_product']['source']) as $source_line) { ?>
		<? $_nn = str_replace('www.', '', parse_url($source_line, PHP_URL_HOST)); ?>
		<span>
			<a style="font-size:10px;" href="<? echo $source_line; ?>" title="<? echo $source_line; ?>" target="_blank"><? echo $_nn; ?></a>&nbsp;
			<i class="fa fa-check-circle" aria-hidden="true" style="font-size:10px;cursor:pointer;" onclick="$('textarea[name=\'order_product[<?php echo $product_row; ?>][source]\']').val('<? echo $source_line; ?>');"></i>&nbsp;
			<i class="fa fa-minus-circle" aria-hidden="true" style="font-size:10px;cursor:pointer;" onclick='var _el = $(this); swal({ title: "Точно удалить источник?", text: "Это действие нельзя отменить!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, удалить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { $.get("index.php?route=catalog/product/deleteSourceAjax&token=<? echo $token; ?>&product_id=<? echo $order_product['real_product']['product_id']; ?>&md5_source=<? echo md5($source_line); ?>", function(){ _el.parent().remove(); }) });'></i>
		</span>
		&nbsp;&nbsp;
	<? } ?>																				
<? } ?>