<table class="list">
	<thead>
		<tr>  
			<td class="left"></td>
			<td class="right">Время звонка</td>
			<td class="center">Длительность</td>
			<td class="center">Менеджер</td>
			<td class="center">Телефон покупателя</td>
			<td class="center">Направление</td>
			<td class="center">Внутр. телефон</td>
			<td class="center">Послушать</td>
		</tr>
	</thead>
	<tbody>
		<?php if ($user_calls) { ?>
            <?php foreach ($user_calls as $user_call) { ?>
				<tr>
					<td class="left" style="width:1px;"><?php echo $user_call['customer_call_id']; ?></td>
					<td class="right"><?php echo $user_call['date_end']; ?></td>
					<td class="right"> 
						<? if ($user_call['length'] > 0) { ?>
							<?php echo $user_call['length']; ?> сек.
							<? } else { ?>
							<span style='color:white; padding:3px; background:#cf4a61'>пропущ.</span>
						<? } ?></td>
			  			
						<td class="right"><a href="<?php echo $user_call['manager_sip_link']; ?>" target="_blank"><?php echo $user_call['manager']; ?> / <?php echo $user_call['manager_id']; ?></a></td>			  
						
						<td class="right"><?php echo $user_call['customer_phone']; ?><span class='click2call' data-phone="<?php echo $user_call['customer_phone']; ?>"></span></td>
						
						<? if ($user_call['inbound']) { ?>
							<td class="center"><i style="font-size:14px; color:#2c82b8" class='fa fa-arrow-right'></i></td>
							<? } else { ?>
							<td class="center"><i style="font-size:14px; color:#4ea24e" class='fa fa-arrow-left'></i></td>
						<? } ?>
						
						<td class="center"><?php echo $user_call['internal_pbx_num']; ?></td>
						
						
						<td class="right">
							<? if ($user_call['length'] > 0) { ?>
								<audio src="<? echo $user_call['filename']; ?>" controls preload='none'></audio>&nbsp;
							<a class="button" href="<? echo $user_call['filename']; ?>" style="vertical-align: 10px;"><i class="fa fa-download"></i></a>
							<? } ?>
						</td>
				</tr>
			<?php } ?>
            <?php } else { ?>
            <tr>
				<td class="center" colspan="8">Нету звонков!</td>
			</tr>
		<?php } ?>
	</tbody>
</table>