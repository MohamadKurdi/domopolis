<table class="list">
  <thead>
	<tr>
		<th colspan="2">История отправленных SMS по заказу</td>
	</tr>
    <tr>
      <td class="left" width="1"><b>Дата</b></td>
      <td class="left"><b>Текст SMS</b></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr id="history">
      <td class="left" style="font-size:10px;"><?php echo $history['date_added']; ?></td>
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
