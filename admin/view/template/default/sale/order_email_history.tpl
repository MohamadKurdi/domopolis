<style>
	.view-mail, .pdf-mail, .print-mail {cursor:pointer; color:#40A0DD;}
</style>
<table class="list">
  <thead>
	<tr>
		<th colspan="5">История отправленных Email по заказу</td>
	</tr>
    <tr>
      <td class="left" width="1"><b>Дата, время</b></td>
      <td class="left"><b>Тема письма, кому</b></td>
	  <td class="left"><b></b></td>
	  <td class="left"><b></b></td>
	  <td class="left"><b></b></td>
    </tr>
  </thead>
  <tbody>
    <?php if (isset($histories) && $histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr id="history">
      <td class="left" style="font-size:10px;word-wrap:none;"><?php echo $history['sent']; ?></td>
      <td class="left"  style="font-size:10px;"><b><?php echo $history['subject']; ?></b>
	  </td>
	  <td class="left" style="font-size:20px; padding:0px 5px; text-align:center;"><span class="view-mail" data-emailtemplate-log="<?php echo $history['emailtemplate_log_id']; ?>"><i class="fa fa-eye"></i></span></td>
	   <td class="left" style="font-size:20px; padding:0px 5px; text-align:center;"><span class="print-mail" data-emailtemplate-log="<?php echo $history['emailtemplate_log_id']; ?>"><i class="fa fa-print"></i></span></td>
	  <td class="left" style="font-size:20px; padding:0px 5px; text-align:center;"><span class="pdf-mail" data-emailtemplate-log="<?php echo $history['emailtemplate_log_id']; ?>"><i class="fa fa-file-pdf-o"></i></span></td>
	 </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="5">Пока что нет данных</td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<script>
	$(document).ready(function(){
		$('.view-mail').click(function(){
			$.ajax({
				url: 'index.php?route=module/emailtemplate/fetch_log&token=<?php echo $token; ?>&id=' +  $(this).attr('data-emailtemplate-log'),
				dataType: 'json',
				success : function(json){
					$('#mailpreview').html(json.html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true})				
				}
			})	
		});	
		
		
		$('.print-mail').click(function(){
			 window.open('index.php?route=module/emailtemplate/fetch_log&output=html&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-emailtemplate-log'), '_blank');			
		});		

		$('.pdf-mail').click(function(){
			 window.open('index.php?route=sale/order/emailhistory2pdf&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-emailtemplate-log'), '_blank');			
		});		
	})
</script>
