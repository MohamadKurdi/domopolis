<table class="list">
  <thead>
	<tr>
		<th colspan="4">История курьерской службы</td>
	</tr>
    <tr>
      <td class="left" width="1"><b>Дата</b></td>
	  <td class="left" width="1"><b>Код КС</b></td>
      <td class="left"><b>Статус</b></td>
	  <td class="left"><b>Комментарий</b></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr id="history">
      <td class="left" style="font-size:10px;"><?php echo $history['date_status']; ?></td>
	  <td class="left" style="font-size:10px;"><?php echo $history['courier_id']; ?></td>
      <td class="left"  style="font-size:10px;"><?php echo $history['status']; ?></td>
	  <td class="left"  style="font-size:10px;"><?php echo $history['comment']; ?></td>
	 </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>