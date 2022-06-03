<? foreach ($reject_reasons as $reject_reason) { ?>
	<input type="radio" name="reject_reason" value="<? echo $reject_reason['reject_reason_id'] ?>"><? echo $reject_reason['name'] ?></input>
<? } ?>