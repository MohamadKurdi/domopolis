<? if (isset($tickets)) { ?>
	<div class="masonry ticket_list">
	<? } ?>
	<? if (!isset($tickets) && isset($ticket)) { $tickets = array($ticket); } ?>
	<? if (count($tickets) > 0) { ?>
		<? foreach ($tickets as $ticket){ ?>			
			<div class="ticket_item <? echo $ticket['priority']; ?> <? echo $ticket['at_time_is_near']?'at_time_is_near':''; ?> <? echo $ticket['max_time_is_near']?'max_time_is_near':''; ?>" id="ticket_<? echo $ticket['ticket_id']; ?>">
				<div class="ticket_item_wrapper">
					<div class="t_row t_hover_draggable">
						<div class="t_left"><b><i class="fa fa-ticket"></i> <? echo $ticket['ticket_id']; ?></b>
							<? if ($ticket['is_whose_ticket']) { ?>
								&nbsp;<span class="t_selectedblue t_font12"><? echo $ticket['is_whose_ticket']; ?></span>
							<? } ?>
						</div>
						<? if ($ticket['date_max']) { ?><div class="t_right t_selectedbrown t_font12"><i class="fa fa-hourglass-o"></i>&nbsp;<? echo $ticket['date_max']; ?></div><? } ?>
						<? if ($ticket['date_at']) { ?><div class="t_right t_selectedorange t_font12"  style="margin-right:5px;"><i class="fa fa-clock-o"></i>&nbsp;<? echo $ticket['date_at']; ?></div><? } ?>					
						<div class="t_clr"></div>
					</div>
					
					<? if ($ticket['entity_url']) { ?>						
						<div class="t_row">
							<div class="t_left">
								<? echo $ticket['entity_name']; ?> <a href="<? echo $ticket['entity_url']; ?>" target="_blank"><? echo $ticket['entity_xname']?$ticket['entity_xname']:$ticket['entity_id']; ?></a>
							</div>
							<? if ($ticket['entity_addon']) { ?>
								<div class="t_right">
									<? echo $ticket['entity_addon']; ?>
								</div>
							<? } ?>
						</div>
					<? } ?>
					<div class="t_clr"></div>
					
					<div class="t_row">
						<? echo $ticket['message']; ?>
					</div>
					
					<? if ($ticket['is_my_ticket']) { ?>
						<div class="t_row">				
							<textarea rows='2' style="width:100%" id="reply_<? echo $ticket['ticket_id']; ?>"><? echo $ticket['reply']; ?></textarea>
						</div>
					<? } ?>
					<div class="t_row t_buttons">
						<? if ($ticket['is_posted_by_me']) { ?>
							<a title='Удалить' class="a_delete t_clickable t_red" data-ticket="<? echo $ticket['ticket_id']; ?>"><i class="fa fa-close"></i></a>&nbsp;
						<? } ?>
						<? if ($ticket['is_my_ticket'] && !$ticket['user_id']) { ?>
							<a title='Присвоить мне' class="a_makemine t_clickable t_blue" data-ticket="<? echo $ticket['ticket_id']; ?>"><i class="fa fa-user-plus"></i></a>&nbsp;
						<? } ?>
						<? if ($ticket['is_my_ticket'] && $ticket['status']) { ?>
							<a title='Ответить' class="a_reply t_clickable t_orange" data-ticket="<? echo $ticket['ticket_id']; ?>"><i class="fa fa-reply"></i></a>&nbsp;					
							<a title='Закрыть задачу' class="a_done t_clickable t_green" data-ticket="<? echo $ticket['ticket_id']; ?>"><i class="fa fa-check"></i></a>
						<? } ?>
						<div class="t_clr"></div>
					</div>
				</div>
			</div>
		<? } ?>
		<? } else { ?>
		<div class="ticket_list t_red" style="width:90%;text-align:center; padding:30px; font-size:40px;">
			<i class="fa fa-warning"></i>&nbsp;&nbsp;Нет задач
		</div>
	<? } ?>
	<? if (isset($tickets)) { ?>	
	</div>
<? } ?>