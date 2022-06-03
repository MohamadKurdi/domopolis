<style>
	.view-mail, .pdf-mail, .restore-save {cursor:pointer; color:#40A0DD;}
</style>
<table class="list">
	<thead>
		<tr>
			<th colspan="4">История сохранений заказа</td>
		</tr>
		<tr>
			<td class="left" width="1"><b>Дата, время</b></td>
			<td class="left"><b>Кто</b></td>
			<td class="left"><b></b></td>
			<td class="left"><b>Восстановление</b></td>
		</tr>
	</thead>
	<tbody>
		<?php if (isset($histories)) { ?>
			<?php foreach ($histories as $history) { ?>
				<tr id="history">
					<td class="left" style="font-size:10px;width:100px;"><?php echo $history['datetime']; ?></td>
					<td class="left"  style="font-size:10px;"><?php echo $history['name']; ?> / <?php echo $history['user_id']; ?></td>
					<td class="left"  style="font-size:10px;"><?php echo $history['realname']; ?></td>
					
					<td class="left"  style="font-size:10px;">
						<? if ($this->user->getISAV() || $this->user->getIsMM() && $history['length'] > 0) { ?>
							<span class="restore-save" data-order-save-id="<? echo $history['order_save_id']; ?>" data-order-id="<? echo $history['order_id']; ?>"><i class="fa fa-history" aria-hidden="true"></i></span> (данные: <? echo $history['length']; ?>)
						<? }  ?>
					</td>	  
					
				</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="center" colspan="4">Пока что нет информации</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<? if ($this->user->getISAV() || $this->user->getIsMM()) { ?>
	<script>
		$('.restore-save').on('click', function(){
			var order_save_id = $(this).attr('data-order-save-id');
			var order_id = $(this).attr('data-order-id');
			
			swal({ title: "Эта операция необратима!", text: "Будьте внимательны", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, восстановить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
				$.ajax({
					url : 'index.php?route=sale/order/restoreOrderSave&token=<? echo $token ?>',
					type : 'POST',
					dataType: 'html',
					data : {
						order_save_id : order_save_id,
						order_id: order_id,
					},
					success : function(e){
						if (e == 'restored') {
							swal("Заказ восстановлен!", "Перезагрузите кнопкой F5!", "success");
							} else {
							swal("Ошибка!", "Попробуйте другое сохранение", "error");
						}					
					},
					error : function (e){
						swal("Ошибка!", "Попробуйте другое сохранение", "error");
					}
					
				});
			});
		});
		</script>
	<? } ?>	