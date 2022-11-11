<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
    <?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
    <?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
    <div class="box">
        <div class="heading order_head">
            <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
		</div>
        <div class="content">
			<div style="width:100%; margin-bottom:10px;">
				<div class="dashboard-heading">Телефония</div>
				<div class="dashboard-content">
					<table style="width:100%">						
						<? foreach ($groups as $group) { ?>	
						
							<? $managers = $group['users']; ?>
							<tr>
								<td colspan="2" style="background-color:#faf9f1;">
									<b><? echo $group['name']; ?></b> <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ccc;"><?php echo $group['sip_queue']; ?></span>
								</td>
							</tr>
							<? foreach ($managers as $manager) { ?>
								<? if ($manager['internal_pbx_num']) { ?>
									<tr>
										<td style="width:250px;"><b><a href="index.php?route=user/user_sip/history&token=<?php echo $token; ?>&user_id=<? echo $manager['user_id']; ?>"><? echo $manager['realname']; ?></a></b></td>
										<td style="padding-top:5px; padding-bottom:5px;">
										
											<span class="peerinfo<? echo $group['sip_queue']; ?>" data-peerid="<? echo $manager['internal_pbx_num'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="font-size:14px;"><i class="fa fa-spinner fa-spin"></i> получаю информацию...</span>
											&nbsp;&nbsp;&nbsp;
											<span class="penaltyinfo<? echo $group['sip_queue']; ?>" data-peerid="<? echo $manager['internal_pbx_num'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="font-size:14px;"><i class="fa fa-spinner fa-spin"></i> получаю приоритет...</span>
											&nbsp;&nbsp;&nbsp;
										<? /*	
											&nbsp;&nbsp;&nbsp;<span class="peeronduty" data-managerid="<? echo $manager['user_id'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="display:inline-block; float:right; cursor:pointer; border-bottom:1px dashed white; padding:3px; color:white;background:#cf4a61;"><i class="fa fa-heart" aria-hidden="true"></i></span>
										*/ ?>
											&nbsp;&nbsp;&nbsp;<span class="peeron0" data-managerid="<? echo $manager['internal_pbx_num'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#cf4a61;"><i class="fa fa-phone-square" aria-hidden="true"></i> 0</span>
											&nbsp;&nbsp;&nbsp;<span class="peeron5" data-managerid="<? echo $manager['internal_pbx_num'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#e4c25a;"><i class="fa fa-phone-square" aria-hidden="true"></i> 5</span>
											&nbsp;&nbsp;&nbsp;<span class="peeron10" data-managerid="<? echo $manager['internal_pbx_num'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#ffaa56;"><i class="fa fa-phone-square" aria-hidden="true"></i> 10</span>
											&nbsp;&nbsp;&nbsp;<span class="peeron15" data-managerid="<? echo $manager['internal_pbx_num'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#4ea24e;"><i class="fa fa-phone-square" aria-hidden="true"></i> 15</span>
											&nbsp;&nbsp;&nbsp;<span class="peeron20" data-managerid="<? echo $manager['internal_pbx_num'] ?>" data-queue-id="<? echo $group['sip_queue']; ?>"  style="display:inline-block; float:right; cursor:pointer;  margin-right:10px; border-bottom:1px dashed white; padding:3px; color:white;background:#1f4962;"><i class="fa fa-phone-square" aria-hidden="true"></i> 20</span>
										</td>
									</tr>	
								<? } ?>
							<? } ?>
						<? } ?>
					</table>
					<table style="width:100%">
						<tr>
							<td style="width:350px;padding-top:30px;"><b>GSM-VOIP шлюз GoIP4</b></td>
							<td style="padding-top:30px; padding-bottom:10px;"><span id="goip4status"><i class="fa fa-spinner fa-spin"></i> подключаюсь...</span></td>	
							<?php for ($i=1; $i<=$this->config->get('config_goip4_simnumber'); $i++) { ?>
							<tr>
								<td style="width:250px;"><b>LINE 1 / <?php echo $this->config->get('config_goip4_simnumber_' . $i); ?></b></td>
								<td style=""><span id="getline<?php echo $i; ?>balance" style="cursor:pointer; border-bottom:1px dashed black; display:inline-block;">Проверить баланс</span> <span id="goip4line<?php echo $i; ?>status"></span></td>
							</tr>
							<?php } ?>							
						</tr>		
					</table>	
				</div>
			</div>
			<script>
				function reloadPenalties(queue_id){
					
					console.log(queue_id);
				
					$('.penaltyinfo'+queue_id).each(function(i, e){				
						$(this).html('<i class="fa fa-spinner fa-spin"></i> получаю приоритет...');
					});
					
					$('.peerinfo'+queue_id).each(function(i, e){					
						$(this).load('index.php?route=api/extcall/getPeerAjax&peer='+$(this).attr('data-peerid')+'&queue_id='+$(this).attr('data-queue-id')+'&token=<?php echo $token; ?>');
					});
					
					$('.penaltyinfo'+queue_id).each(function(i, e){				
						$(this).load('index.php?route=api/extcall/getQueueMemberPenaltyAjax&member_id='+$(this).attr('data-peerid')+'&queue_id='+$(this).attr('data-queue-id')+'&token=<?php echo $token; ?>');
					});				
				}
				
				$(document).ready(function(){
				
					
					<? foreach ($groups as $group) { ?>	
						reloadPenalties(<? echo $group['sip_queue'] ?>);
					<? } ?>
					
					$('#goip4status').load('index.php?route=api/goip4/getStatus&general=1&token=<?php echo $token; ?>');
					
					$('#getline1balance').click(function(){
						$('#goip4line1status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getKyivstarBalance&token=<?php echo $token; ?>');
					});
					
					$('#getline2balance').click(function(){
						$('#goip4line2status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getVodafoneBalance&token=<?php echo $token; ?>');
					});
					
					$('#getline3balance').click(function(){
						$('#goip4line3status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getKyivstarBalance&line=3&token=<?php echo $token; ?>');
					});
					
					$('#getline4balance').click(function(){
						$('#goip4line4status').html('<i class="fa fa-spinner fa-spin"></i> получаем баланс...').load('index.php?route=api/goip4/getVodafoneBalance&line=4&token=<?php echo $token; ?>');
					});
					
					$('#amistatus').load('index.php?route=api/extcall/getAStatusAjax&ajax=1&token=<?php echo $token; ?>');			
					
					$('.peeron0').click(function(){
						var q = $(this).attr('data-queue-id');
						$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=0&queue_id='+q+'&token=<?php echo $token; ?>', 
						function(){ reloadPenalties(q); }
						);
					});
					
					$('.peeron5').click(function(){
						var q = $(this).attr('data-queue-id');
						$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=5&queue_id='+q+'&token=<?php echo $token; ?>',
						function(){ reloadPenalties(q); }
						);
					});
					
					$('.peeron10').click(function(){
						var q = $(this).attr('data-queue-id');
						$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=10&queue_id='+q+'&token=<?php echo $token; ?>', 
						function(){ reloadPenalties(q); }
						);
					});
					
					$('.peeron15').click(function(){
						var q = $(this).attr('data-queue-id');
						$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=15&queue_id='+q+'&token=<?php echo $token; ?>', 
						function(){ reloadPenalties(q); }
						);
					});
					
					$('.peeron20').click(function(){
						var q = $(this).attr('data-queue-id');
						$.get('index.php?route=api/extcall/setPenaltyAjax&manager_id='+$(this).attr('data-managerid')+'&penalty=20&queue_id='+q+'&token=<?php echo $token; ?>', 
						function(){ reloadPenalties(q); }
						);
					});
					
					$('.peeronduty').click(function(){	
						var q = $(this).attr('data-queue-id');
						$.get('index.php?route=api/extcall/setMainManagerAjax&manager_id='+$(this).attr('data-managerid')+'&queue_id='+q+'&token=<?php echo $token; ?>',
						function(){ reloadPenalties(q); }
						);
					});
				});		
				
				
			</script>
			
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'username') { ?>
								<a href="<?php echo $sort_username; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_username; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_username; ?>"><?php echo $column_username; ?></a>
							<?php } ?></td>
							<td></td>
							<td>Телефон (Внутренний номер)</td>
							<td>Статус</td>
							<td>Разговоров</td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($users) { ?>
							
							<? foreach ($users as $name => $group) { ?>
								<tr>
									<td colspan="6" class="left"><b><? echo $name; ?></b></td>
								</td>
								<?php foreach ($group as $user) { ?>
									<tr <? if ($user['is_av']) { ?>style="background:pink;"<? } ?>>
										<td style="text-align: center;"><?php if ($user['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
											<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
										<?php } ?></td>
										<td class="left"><?php echo $user['username']; ?></td>
										<td class="left"><?php echo $user['firstname']; ?> <?php echo $user['lastname']; ?></td>
										<td class="left"><?=$user['internal_pbx_num'] ?></td>
										
										<? if ($user['internal_pbx_num'] != 'del') { ?>
											
											<td class="left"><span id="peer<? echo $user['internal_pbx_num'] ?>" style="font-size:14px;"><i class="fa fa-spinner fa-spin"></i></span></td>
											
											<script>
												$(document).ready(function(){							
													$('#peer<? echo $user['internal_pbx_num'] ?>').load('index.php?route=api/extcall/getPeerAjax&peer=<? echo $user['internal_pbx_num'] ?>&token=<?php echo $token; ?>');					
												});
											</script>
											
											<? } else { ?> 
											<td class="left"></td>
										<? } ?>
										<td class="left"><?=$user['call_count'] ?></td>
										<td class="right"><?php foreach ($user['action'] as $action) { ?>
											<a class='button' href="<?php echo $action['href']; ?>"><i class="fa fa-history"></i></a>
										<?php } ?></td>
									</tr>
								<?php } ?>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<?php echo $footer; ?>