<?php echo $header; ?>
<div id="content">
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1><?php echo $heading_title; ?></h1>
			
	  		<div id="pagination" style="display:inline-block;float:right;margin-left:30px;font-size:40px;color:#404040;">
				<input type="hidden" value="1" name="_page" id="_page" />
				<input type="hidden" value="" name="_current" id="_current" />
				<input type="hidden" value="" name="_current_tickets" id="_current_tickets" />
				<a style="color:#404040" id="page_minus" onclick="doPage(-1);"><i class="fa fa-chevron-left"></i></a>&nbsp;<a style="color:#404040" id="page_plus" onclick="doPage(1);"><i class="fa fa-chevron-right"></i></a>
			</div>
			
		</div>
		<div class="clear:both"></div>
		<div class="content" style="padding:10px;">
			
			<div class="htabs-wrapper" style="border-bottom:1px solid #1f4962; margin: 0 0 10px 0;">
				<div id="main-htabs" class="main-htabs" style="margin: 0 0 7px 0; display:inline-block;">
					<a href="#tab-my" data-tickets="my"><i class="fa fa-user-circle"></i>Мои</a>
					<a href="#tab-by-today" data-tickets="today"><i class="fa fa-bell t_red"></i>Мои на сегодня</a>
					<a href="#tab-my-group" data-tickets="my_group"><i class="fa fa-users"></i>Групповые</a>
					<a href="#tab-by-call" data-tickets="by_call"><i class="fa fa-phone-square"></i>Звонки</a>
					<a href="#tab-by-order" data-tickets="by_order"><i class="fa fa-cart-arrow-down"></i>По заказам</a>
					<a href="#tab-by-customer" data-tickets="by_customer"><i class="fa fa-user"></i>По клиентам</a>
					<a href="#tab-urgent" data-tickets="urgent"><i class="fa fa-hourglass-o t_red"></i>Срочные</a>
					<a href="#tab-middle-urgent" data-tickets="middle_urgent"><i class="fa fa-hourglass-half t_orange"></i>Средней срочности</a>
					<a href="#tab-non-urgent" data-tickets="non_urgent"><i class="fa fa fa-hourglass t_green"></i>Еще подождут</a>
					<a href="#tab-set-by-me" data-tickets="set_by_me"><i class="fa fa-eye"></i>Я поставил</a>
					<a href="#tab-done" data-tickets="my_done"><i class="fa fa-history"></i>История</a>
				</div>
				
				
				<div class="t_clr"></div>
			</div>	
			
			
			<div id="tab-urgent">			
			</div>
			
			<div id="tab-middle-urgent">		
			</div>
			
			<div id="tab-non-urgent">		
			</div>
			
			<div id="tab-my-group">		
			</div>
			
			<div id="tab-my">			
			</div>
			
			<div id="tab-by-today">			
			</div>
			
			<div id="tab-middle-urgent">		
			</div>
			
			<div id="tab-by-order">			
			</div>
			
			<div id="tab-by-call">			
			</div>
			
			<div id="tab-by-customer">			
			</div>
			
			<div id="tab-set-by-me">
			</div>
			
			<div id="tab-done">		
			</div>
			
		</div>
		
		<script type="text/javascript"><!--
			$(document).ready(function() {
				
				$('#main-htabs a').tickettabs();
				
				$('#date').datepicker({dateFormat: 'yy-mm-dd'});
				$('#datetime').datepicker({dateFormat: 'yy-mm-dd h:i:s'});
			});
			$(document).ready(function() {
				
			});
		//--></script>
		<script type="text/javascript"><!--
			function MakeSortable(type){
				
				if (type == 'my'){
					
					$('.ticket_list').sortable({
						forcePlaceholderSize: true,
						forceHelperSize: true,
						tolerance : "pointer",
						placeholder: "sortable-placeholder",
						revert: true,
						cursor: "move",
						update: function (event, ui) {
							var data = $(this).sortable('serialize');
							
							$.ajax({
								data : data,
								dataType : 'html',
								type : 'POST',
								url : 'index.php?route=user/ticket/resorttickets&token=<? echo $token; ?>',
								success : function(z){
									console.log(z)
								}
							});
						}
					});
				}	
			}
			
			function setTicketDone(){
				$(".a_done").click(function(){
					var ticket_id = $(this).attr('data-ticket');
					var element = $(this);
					var parent = $('#ticket_'+ticket_id);
					
					swal({ 
						title: "Точно закрыть задачу?", 
						text: "Это действие нельзя отменить", 
						type: "success", 
						showCancelButton: true,  
						confirmButtonColor: "#008C00",  
						confirmButtonText: "Да, закрыть!", 
						cancelButtonText: "Отмена",  
					closeOnConfirm: true }, 
					
					function() { 
						$.ajax({
							data : 'ticket_id='+ticket_id,
							dataType : 'html',
							type : 'POST',
							url : 'index.php?route=user/ticket/ticketdone&token=<? echo $token; ?>',
							beforeSend : function(){								
								element.html("<i class='fa fa-spinner'></i>");
							},
							success : function(e){								
								parent.fadeOut(300, function(){ parent.remove();});				
							},
							error : function(e){
								console.log(e);
							}
						});
					});
				});
			}
			
			function setTicketReply(){
				
				$(".a_reply").click(function(){
					var ticket_id = $(this).attr('data-ticket');
					var element = $(this);
					var parent = $('#ticket_'+ticket_id);		
					var reply = $('#reply_'+ticket_id).val();
					
					$.ajax({
						data : 'ticket_id='+ticket_id+'&reply='+encodeURIComponent(reply),
						dataType : 'html',
						type : 'POST',
						url : 'index.php?route=user/ticket/ticketreply&token=<? echo $token; ?>',
						beforeSend : function(){
							element.html("<i class='fa fa-spinner'></i>");
						},
						success : function(e){
							element.html("<i class='fa fa-reply'></i>");
							element.removeClass('t_orange').addClass('t_green');
						},
						error : function(e){
							console.log(e);
						}
					});
					
				});
				
				
			}
			
			function setTicketDelete(){
				$(".a_delete").click(function(){
					var ticket_id = $(this).attr('data-ticket');
					var element = $(this);
					var parent = $('#ticket_'+ticket_id);
					
					swal({ 
						title: "Точно удалить задачу?", 
						text: "Это действие нельзя отменить", 
						type: "warning", 
						showCancelButton: true,  
						confirmButtonColor: "#F96E64",  
						confirmButtonText: "Да, удалить!", 
						cancelButtonText: "Отмена",  
					closeOnConfirm: true }, 
					
					function() { 
						$.ajax({
							data : 'ticket_id='+ticket_id,
							dataType : 'html',
							type : 'POST',
							url : 'index.php?route=user/ticket/ticketdelete&token=<? echo $token; ?>',
							beforeSend : function(){							
								element.html("<i class='fa fa-spinner'></i>");
							},
							success : function(e){								
								parent.fadeOut(300, function(){ parent.remove();});				
							},
							error : function(e){
								console.log(e);
							}
						});
					}
					);				
				});
			}
			
			function setTicketMakeMine(){
				$(".a_makemine").click(function(){
					var ticket_id = $(this).attr('data-ticket');
					var element = $(this);
					var parent = $('#ticket_'+ticket_id);
					
					swal({ 
						title: "Точно присвоить мне?", 
						text: "Это действие нельзя отменить", 
						type: "warning", 
						showCancelButton: true,  
						confirmButtonColor: "#4096EE",
						confirmButtonText: "Да, присвоить!", 
						cancelButtonText: "Отмена",  
					closeOnConfirm: true }, 
					
					function() { 
						$.ajax({
							data : 'ticket_id='+ticket_id,
							dataType : 'html',
							type : 'POST',
							url : 'index.php?route=user/ticket/ticketmakemine&token=<? echo $token; ?>',
							beforeSend : function(){
								element.html("<i class='fa fa-spinner'></i>");
							},
							success : function(e){
								parent.fadeOut(300, function(){ parent.remove();});				
							},
							error : function(e){
								console.log(e);
							}
						});
					}
					);				
				});
			}
			
			function initMasonry(){
				$('.masonry').masonry({
					// options
					itemSelector: '.ticket_item',
					columnWidth: 200
				});
			}
			
			function reloadCurrentPage(){
				
				$($('#_current').val()).load('index.php?route=user/ticket/gettickets&_filter='+$('#_current_tickets').val()+'&page='+$('#_page').val()+'&token=<? echo $token; ?>',function(){
					MakeSortable(ticket_type); 
					setTicketDone(); 
					setTicketDelete(); 
					setTicketMakeMine(); 
					setTicketReply();
					initMasonry();
				});
				
			}
			
			function doPage(z){
				var i = parseInt($('#_page').val());
				
				if (i<=0){ i = 1; }	
				i = i+z;	
				if (i<=0){ i = 1; }
				
				$('#_page').val(i);
				reloadCurrentPage();
			}
			
			$.fn.tickettabs = function() {
				var selector = this;
				
				this.each(function() {
					var obj = $(this); 
					
					$(obj.attr('href')).hide();
					
					$(obj).click(function() {
						$(selector).removeClass('selected');
						
						$(selector).each(function(i, element) {
							$($(element).attr('href')).hide();
						});			
						
						$(this).addClass('selected');
						
						$($(this).attr('href')).show();
						var ticket_type = $(this).attr('data-tickets');
						var _target = $(this).attr('href');
						$(_target).load('index.php?route=user/ticket/gettickets&_filter='+ticket_type+'&token=<? echo $token; ?>',function(){ 
							$('#_current').val(_target);
							$('#_current_tickets').val(ticket_type);
							MakeSortable(ticket_type); 
							setTicketDone(); 
							setTicketDelete(); 
							setTicketMakeMine(); 
							setTicketReply(); 
						});
						
						return false;
					});
				});
				
				$(this).show();	
				$(this).first().click();
			};
		//--></script>
	</div>
</div>
	<?php echo $footer; ?> 	