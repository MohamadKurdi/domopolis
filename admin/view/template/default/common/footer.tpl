</div>
<div id="footer"></div>
<div id="top">	
<svg viewBox="0 0 32 32"  fill="none" stroke="#51a881" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
    <path d="M30 20 L16 8 2 20" />
</svg>
</div>
<? if ($this->user->isLogged()) { ?>

		<div id="alert_history_preview"></div>
		<script>
			$('a#alert_history_preview_click').click(function(){
				$.ajax({
					url: 'index.php?route=user/user_alerts&token=<?php echo $token; ?>&ajax=1',
					dataType: 'html',				
					success : function(html){
						$('#alert_history_preview').html(html).dialog({width:800, height:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: "Мои уведомления"})				
					}
				});
				return false;
			});	
		</script>	

	<div id="add_task_dialog"></div>
	
	<? if (isset($socnet_auth_code)) { ?>
		<? echo $socnet_auth_code; ?>
	<? } ?>
	<style type="text/css">
			.pull-right {
            float:right;
			}
			.label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: bold;
            line-height: 1;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
			}
			.label-success {
            background-color: #5cb85c;
			}
			.label-info {
            background-color: #5bc0de;
			}
			.label-warning {
            background-color: #f0ad4e;
			}
			.label-danger {
            background-color: #d9534f;
			}
		</style>
		
	<style>
					.admin_home{width:100%;background: #f3f3f3 url(/admin/view/image/bg_grey.png) repeat;}
					.admin_home tr td{text-align:center; padding-bottom:30px;}
					.admin_home tr td i{font-size:72px; color:#3a4247;}
					.admin_home tr td a{color:#3a4247;}
					div.admin_button {float:left; text-align:center; border: 1px solid #ededed; margin-right:10px; margin-bottom:10px;}
					div.admin_button_status {float:left; padding: 20px; text-align:center;margin-right:10px; margin-bottom:10px;border-radius: 2px;width: 115px;height: 115px;}
					.admin_button i{font-size:56px;opacity: 0.8;padding: 20px;}
					.admin_button_status i {font-size:56px;opacity: 0.8;}
					.admin_button i:hover, .admin_button_status i:hover {opacity: 1;}
					.admin_button a, .admin_button_status a {text-decoration:none; color:#FFF; }

	.tooltip {
	position: absolute;
	z-index: 1070;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-style: normal;
	font-weight: normal;
	letter-spacing: normal;
	line-break: auto;
	line-height: 1.42857;
	text-align: left;
	text-align: start;
	text-decoration: none;
	text-shadow: none;
	text-transform: none;
	white-space: normal;
	word-break: normal;
	word-spacing: normal;
	word-wrap: normal;
	font-size: 11px;
	opacity: 0;
	filter: alpha(opacity=0); }
	.tooltip.in {
    opacity: 0.9;
    filter: alpha(opacity=90); }
	.tooltip.top {
    margin-top: -3px;
    padding: 5px 0; }
	.tooltip.right {
    margin-left: 3px;
    padding: 0 5px; }
	.tooltip.bottom {
    margin-top: 3px;
    padding: 5px 0; }
	.tooltip.left {
    margin-left: -3px;
    padding: 0 5px; }
	
	.tooltip-inner {
	max-width: 200px;
	padding: 3px 8px;
	color: #fff;
	text-align: center;
	background-color: #000;
	border-radius: 3px; }
	
	.tooltip-arrow {
	position: absolute;
	width: 0;
	height: 0;
	border-color: transparent;
	border-style: solid; }
	
	.tooltip.top .tooltip-arrow {
	bottom: 0;
	left: 50%;
	margin-left: -5px;
	border-width: 5px 5px 0;
	border-top-color: #000; }
	.tooltip.top-left .tooltip-arrow {
    bottom: 0;
    right: 5px;
    margin-bottom: -5px;
    border-width: 5px 5px 0;
    border-top-color: #000; }
	.tooltip.top-right .tooltip-arrow {
    bottom: 0;
    left: 5px;
    margin-bottom: -5px;
    border-width: 5px 5px 0;
    border-top-color: #000; }
	.tooltip.right .tooltip-arrow {
    top: 50%;
    left: 0;
    margin-top: -5px;
    border-width: 5px 5px 5px 0;
    border-right-color: #000; }
	.tooltip.left .tooltip-arrow {
    top: 50%;
    right: 0;
    margin-top: -5px;
    border-width: 5px 0 5px 5px;
    border-left-color: #000; }
	.tooltip.bottom .tooltip-arrow {
    top: 0;
    left: 50%;
    margin-left: -5px;
    border-width: 0 5px 5px;
    border-bottom-color: #000; }
	.tooltip.bottom-left .tooltip-arrow {
    top: 0;
    right: 5px;
    margin-top: -5px;
    border-width: 0 5px 5px;
    border-bottom-color: #000; }
	.tooltip.bottom-right .tooltip-arrow {
    top: 0;
    left: 5px;
    margin-top: -5px;
    border-width: 0 5px 5px;
    border-bottom-color: #000; }
	.list.big tbody td.sum{font-size:9px !important;}
	.list.big thead td {font-size: 13px !important;}
	.list.big tbody td.discount {font-size: 10px !important;}
</style>
	<style>
		.ktooltip_click{cursor:pointer;}
		#top {
		    bottom: 40px;
		    cursor: pointer;
		    display: none;
		    border: 3px solid #51a881;
		    position: fixed;
		    right: 61px;
		    width: 45px;
		    height: 45px;
		    line-height: 45px;
		    text-align: center;
		    opacity: 1;
		    -webkit-transition: .3s all;
		    -o-transition: .3s all;
		    transition: .3s all;
		    z-index: 99;
		    color: #51a881;
		    border-radius: 100px;
		    box-shadow: 1px 1px 10px #626262cc;
		}
		#top:hover {
		    background: #51a881;
		    color: #fff;
		}
		#top svg{
			width: 20px;
			height: 20px;
			position: absolute;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			margin: auto;
		}
		#top:hover svg path {
			stroke: #fff;
		} 
	</style>
	<script type="text/javascript">
		// Кнопка на вверх
		var top_show = 150;
		var delay = 1000;
		$(document).ready(function () {
		    $(window).scroll(function () {
		        if ($(this).scrollTop() > top_show) $("#top").fadeIn();
		        else $("#top").fadeOut();
		    });
		    $("#top").click(function () {
		        $("body, html").animate(
		            {
		                scrollTop: 0,
		            },
		            delay
		        );
		    });
		});
        $(document).ready(function() {
            
            $('.ktooltip_hover').tooltipster(
			{
				trigger: 'hover',
				theme: 'tooltipster-punk'
			});
			$('.ktooltip_hover_side').tooltipster(
			{
				trigger: 'hover',
				theme: 'tooltipster-punk',
				side: 'right'
			}
			);
			$('.ktooltip_click').tooltipster(
			{
				trigger: 'click',
				theme: 'tooltipster-punk'
			}
			);
		});
	</script>	
	<style>
		<?php $colors = array(
			'#ff7815','#64a1e1','#32bd38','#ec74ae', '#fa4934'
		);?>
		.delayed-load{			
		margin:0 auto;
		}
		.lds-hourglass {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
		}
		.lds-hourglass:after {
		content: " ";
		display: block;
		border-radius: 50%;
		width: 0;
		height: 0;
		margin: 8px;
		box-sizing: border-box;
		border: 32px solid <?php echo $colors[mt_rand(0, count($colors)-1)]?>;
		border-color: <?php echo $colors[mt_rand(0, count($colors)-1)]; ?> transparent <?php echo $colors[mt_rand(0, count($colors)-1)]; ?> transparent;
		animation: lds-hourglass 1.2s infinite;
		}
		@keyframes lds-hourglass {
		0% {
		transform: rotate(0);
		animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
		}
		50% {
		transform: rotate(900deg);
		animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
		}
		100% {
		transform: rotate(1800deg);
		}
		}
		
	</style>
	<script type="text/javascript">
		
		function loadDelayedLoad(element, spinner = true){
			
			let route = element.attr('data-route');
			$.ajax({
				url: 'index.php?route=' + route + '&nolog=1&token=<? echo $token ?>',
				type: "GET",
				async: true,
				beforeSend: function(){
					if (spinner){
						if (element.hasClass('short-delayed-load')){
							element.html("<i class='fa fa-spinner fa-spin'></i>");
						} else {
							element.html("<div style='height:200px;padding-top:60px;text-align:center;'><div class='lds-hourglass'></div></div>");
						}
					}
				},
				success: function(html){
					element.html(html);
				},
				error: function(){
					element.html('<pre>Ошибка! ' + route + '</pre>');
				}
			});		
		}
		
		$(document).ready(function() {
			$('.delayed-load').each(function(){
				let current = $(this);
				loadDelayedLoad(current);	

				if (current.attr('data-reload')){
					setInterval(function(){ loadDelayedLoad(current, false); }, parseInt(current.attr('data-reload')));
				}

			});
		});
	</script>
	<script type="text/javascript">
		if (!(typeof(setAudioTrigger) === typeof(Function))){
			function setAudioTrigger(){
				$('audio[class!=donotstop]').on('play', function(){
					var current = $(this);					
					$('audio').each(function(){						
						if ($(this)[0].paused == false && $(this)[0].src != current[0].src){
							$(this).trigger("pause");
						}
					})
				});
			}
		}
		
		$(document).ready(function() {
			setInterval(function() { $.ajax({ url: 'index.php?route=common/home/session&nolog=1&token=<? echo $token ?>' }); }, 10000);   				
		});
	</script>
	<? if ($this->user->getIPBX()) { ?>
		<style>
			.click2call{padding:2px;display:inline-block;font-family: FontAwesome;font-size:20px;color:#1f4962;cursor:pointer;}
			.click2call:before{content:"\f098"}
			.fixed-header {
			position: fixed!important;
			top:0; left:0;
			width: calc(100% - 60px);
			opacity:0.8;
			}
		</style>		
		<script>
			$(document).ready(function(){											
				$(document).on('click', '.click2call', function(){
					var phone = $(this).attr('data-phone');
					swal({ title: "Позвонить?", text: "На номер: "+phone, type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Позвонить сейчас!", cancelButtonText: "Отмена",  closeOnConfirm: true },
					function(){
						$.ajax({
							url : 'index.php?route=api/extcall/originateCallAjax&token=<? echo $token ?>',
							type : 'POST',
							dataType : 'html',
							data : {
								'phone' : phone
							},
							error: function(e){
								console.log(e)
							},
							success: function(e){
								swal({title:'Вызов начался!', text:"Приятного общения:)", type:"success"});
							}							
						});						
					});					
				});
			});
		</script>
		<? } else { ?>
		<style>.click2call{display:none;}</style>
	<? } ?>
	<style>
		.add2ticket{padding:2px;display:inline-block;font-family: FontAwesome;font-size:20px;color:#1f4962;cursor:pointer;}
		.add2ticket:before{content:"\f271"}			
	</style>		
	<script>
		function addMyTask(query){
			$('#add_task_dialog').load('index.php?route=user/ticket/showAddTicketFormAjax&token=<? echo $token ?>', 
			{ 	
				query : query
			}, 
			function(){ $(this).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Создание новой задачи'}); });
		}
		
		
		$(document).ready(function(){						
			
			setAudioTrigger();
			
			$('#trigger_add_task').click(function(){
				addMyTask('<? echo base64_encode($this->request->server['QUERY_STRING']); ?>');					
			});
			
			$('.add2ticket').on('click', function(){
				addMyTask(btoa($(this).attr('data-query')));
			});
			
			$(window).scroll(function(){
				if ($(window).scrollTop() >= 48) {
					$('#header > #menu').addClass('fixed-header');
				}
				else {
					$('#header > #menu').removeClass('fixed-header');
				}
			});
			
			
		});
	</script>
	<?php if ($this->config->get('config_enable_malert_in_admin') && $this->user->getAlertNameSpace()) { ?>
		<audio src='view/javascript/sounds/alert_beep.mp3' class="donotstop" id="beep_alert" style="display:none;" type="audio/mp3" preload="auto"></audio>
		<audio src='view/javascript/sounds/incoming_call2.mp3' class="donotstop" id="incoming_alert" style="display:none;" type="audio/mp3" preload="auto"></audio>
		<audio src='view/javascript/sounds/i_alarm.mp3' class="donotstop" id="fuck_alarm" style="display:none;" type="audio/mp3" preload="auto"></audio>
		<script type="text/javascript">		
			function generate_noty(type, text, url, sound) {
				
				var n = noty({
					text        : text,
					type        : type,
					dismissQueue: true,
					layout      : 'bottomRight',
					closeWith   : ['click'],
					theme       : 'relax2',
					maxVisible  : 10,
					timeout     : 600,
					callback: {					
						onShow      : function(){							
							<? if ($this->user->getID() != 3) { ?>
								$('#'+sound).trigger('play');
								<? } else { ?>
								if (sound == 'fuck_alarm'){
									$('#'+sound).trigger('play');
								}
							<? } ?>
						}
					},
					animation   : {
						open  : 'animated bounceInRight',
						close : 'animated bounceOutRight',
						easing: 'swing',
						speed : 500
					},
					buttons     : [
					{addClass: 'btn btn-large btn-success', text: 'Открыть!', onClick: function ($noty) {
						$noty.close();									
						window.open( url, '_blank' );							
					}
					},
					{addClass: 'btn btn-large btn-danger', text: 'Отмена', onClick: function ($noty) {
						$noty.close();							
					}
					}
					]
				});				
			}
			
			function generate_noty_nourl(type, text, sound) {
				
				var n = noty({
					text        : text,
					type        : type,
					dismissQueue: true,
					layout      : 'bottomRight',
					closeWith   : ['click'],
					theme       : 'relax2',
					maxVisible  : 10,
					timeout     : 50, 
					callback: {					
						onShow      : function(){
							<? if ($this->user->getID() != 3) { ?>
								$('#'+sound).trigger('play');
								<? } else { ?>
								if (sound == 'fuck_alarm'){
									$('#'+sound).trigger('play');
								}
							<? } ?>
						}
					},
					animation   : {
						open  : 'animated bounceInRight',
						close : 'animated bounceOutRight',
						easing: 'swing',
						speed : 500
					},
					buttons     : [
					{addClass: 'btn btn-large btn-danger', text: 'Закрыть', onClick: function ($noty) {
						$noty.close();							
					}
					}
					]
				});				
			}
						
			$(document).ready(function(){
				
				var hidden, state, visibilityChange; 
				if (typeof document.hidden !== "undefined") {
					hidden = "hidden";
					visibilityChange = "visibilitychange";
					state = "visibilityState";
					} else if (typeof document.mozHidden !== "undefined") {
					hidden = "mozHidden";
					visibilityChange = "mozvisibilitychange";
					state = "mozVisibilityState";
					} else if (typeof document.msHidden !== "undefined") {
					hidden = "msHidden";
					visibilityChange = "msvisibilitychange";
					state = "msVisibilityState";
					} else if (typeof document.webkitHidden !== "undefined") {
					hidden = "webkitHidden";
					visibilityChange = "webkitvisibilitychange";
					state = "webkitVisibilityState";
				}
				// Add a listener that constantly changes the title								
				
				function poll() {
					setTimeout(function() {							
						if (document[state] == 'visible') {
							$.ajax({
								url : 'index.php?route=api/alerts/getAlert&token=<? echo $token ?>',
								type : 'GET',
								dataType : 'json',						
								error: function(e){								
									poll;
								},
								success: function(json){
									if (json && json.type){
										if (json.url != 'undefined') {
											generate_noty(json.type, json.text, json.url, json.sound);
											} else {
											generate_noty_nourl(json.type, json.text, json.sound);
										}
									}
								},
								complete : poll
							});
						}
					}, 2500);
				};
				
				poll();
				
				document.addEventListener(visibilityChange, function() {				
					if (document[state] == 'visible') {
						poll();
					}	
				}, false);
				
			});				
		</script>
	<? } ?>	
<? } ?>
</body>
</html>