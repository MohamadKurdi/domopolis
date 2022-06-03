<table class="list">
  <thead>
	<tr>
		<th colspan="7">История звонков покупателя</td>
	</tr>
    <tr>
	  <td class="left" width="1"><b>Дата, время</b></td>
      <td class="center" style="font-size:16px;"><i class="fa fa-clock-o"></i></td>
	  <td class="left">Менедж.</td>
	  <td class="left"></td>
	  <td class="left"></td>
	  <td class="left"></td>
	  <td class="center">Прослушать</td>
    </tr>
  </thead>
  <tbody>
    <?php if ($user_calls) { ?>
            <?php foreach ($user_calls as $user_call) { ?>
            <tr>            
			  <td class="left" style="font-size:10px;"><?php echo $user_call['date_end']; ?></td>
              <td class="right" style="font-size:10px; whitespace-wrap:none;">
			   <? if ($user_call['length'] > 0) { ?>
				<?php echo $user_call['length']; ?>с.
			  <? } else { ?>
				<span style='color:white; padding:3px; background:#ff5656;'>проп.</span>
			  <? } ?>
			  </td>
			  			
			  <td class="right" style="font-size:10px;"><a href="<?php echo $user_call['manager_sip_link']; ?>" target="_blank"><?php echo $user_call['manager']; ?></a></td>			  
			  
			  <td class="right" style="font-size:10px;"><?php echo $user_call['customer_phone']; ?><span class='click2call' data-phone="<?php echo $user_call['customer_phone']; ?>"></span></td>
			  
			  <? if ($user_call['inbound']) { ?>
				<td class="center"><i style="font-size:14px; color:#2c82b8" class='fa fa-arrow-right'></i></td>
			  <? } else { ?>
				<td class="center"><i style="font-size:14px; color:#85B200" class='fa fa-arrow-left'></i></td>
			  <? } ?>
			  
			  <td class="center" style="font-size:10px;"><?php echo $user_call['internal_pbx_num']; ?></td>
			  <td class="right"><? if ($user_call['length'] > 0) { ?><audio src="<? echo $user_call['filename']; ?>" controls preload='none'></audio>
			  &nbsp;
			   <a href="<? echo $user_call['filename']; ?>" style="text-decoration:none;"><i class="fa fa-download"></i></a><? } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="7">Нету звонков!</td>
            </tr>
            <?php } ?>
  </tbody>
</table>