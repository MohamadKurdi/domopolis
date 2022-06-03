  <div class="box">
    <div class="content" style="min-height:auto;">     
        <table class="list" style="width:100%">
          <thead>
            <tr>         
              <td>ID</td>
			  <td><?php echo $column_order_id; ?></td>
              <td> <?php echo $column_customer; ?></td>
			  <td></td>
              <td><?php echo $column_product; ?></td>			
              <td>Артикул</td>
			  <td class="left">Кол.</td>
			  <td class="left">Цена</td>
			  <td class="left">Итог</td>
			  <td class="left">Перезаказ</td>
              <td><?php echo $column_status; ?></td>
               <td><?php echo $column_date_added; ?></td>
				 <td><?php echo $column_date_modified; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>           
            <?php if ($returns) { ?>
            <?php foreach ($returns as $return) { ?>
            <tr>
              <td class="right"><b style="font-size:16px;"><?php echo $return['return_id']; ?></b></td>
              <td class="right"><a href="<? echo $return['order_href'] ?>" target="_blank"><?php echo $return['order_id']; ?></a></td>
              <td class="left"><a href="<? echo $return['customer_href'] ?>" target="_blank"><?php echo $return['customer']; ?></a></td>
			  <td class="left" width="45px;"><img src="<? echo $return['image']; ?>" /></td>
              <td class="left"><a href="<? echo $return['product_href'] ?>" target="_blank"><?php echo $return['product']; ?></a></td>
              <td class="left"><?php echo $return['model']; ?></td>
			  <td class="left"><?php echo $return['quantity']; ?></td>
			  <td class="left" style="white-space: nowrap;"><?php echo $return['price']; ?></td>
			  <td class="left" style="white-space: nowrap;"><?php echo $return['total']; ?></td>
			  <td class="left"><?php echo $return['reorder_id']; ?></td>
              <td class="left"><?php echo $return['status']; ?></td>
              <td class="left"><?php echo $return['date_added']; ?></td>
              <td class="left"><?php echo $return['date_modified']; ?></td>
              <td class="center">
				<?php foreach ($return['action'] as $action) { ?>
					<a class="button" href="<?php echo $action['href']; ?>" style="padding:3px 5px; margin-bottom:4px;"><i class="fa fa-edit"></i></a>
                <?php } ?>
				<br />
					<a class="button return-history" data-return-id="<?php echo $return['return_id']; ?>" style="padding:3px 5px;"><i class="fa fa-history"></i></a>
			</td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="13"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
  </div>
<div id="mailpreview"></div>
<script>
	$('.return-history').click(function(){
			$.ajax({
				url: 'index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=' +  $(this).attr('data-return-id'),
				dataType: 'html',				
				success : function(html){
					$('#mailpreview').html(html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
				}
			})	
		});	
</script>